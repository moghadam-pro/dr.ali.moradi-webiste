#!/usr/bin/env node
/**
 * Imports the JSON produced by extract-content.mjs into a WordPress site
 * via the REST API, using an Application Password.
 *
 * Import order matters (see content-migration-plan.md): media first (so
 * attachment IDs exist), then CPT entries (team members), then Posts, then
 * the About page. Each step is idempotent — safe to re-run — by looking up
 * an existing entity by slug/name + language before creating a new one.
 *
 * Polylang (free) sets a single entry's language on create via the ?lang=
 * query param (confirmed working against the free REST integration), but
 * does not expose translation-group *linking* over REST outside of
 * Polylang Pro. Since only the free tier is allowed on this project, the
 * theme itself exposes two small REST routes
 * (wordpress-theme/dr-ali-moradi/inc/polylang.php) that call Polylang's
 * free-tier PHP API functions (`pll_save_post_translations()` /
 * `pll_save_term_translations()`) directly:
 *   POST /wp-json/dr-ali-moradi/v1/link-translations       { translations: {lang: post_id} }
 *   POST /wp-json/dr-ali-moradi/v1/link-term-translations  { translations: {lang: term_id} }
 *
 * Usage:
 *   WP_BASE_URL=https://tmp.saveon.me \
 *   WP_USER=dralimin \
 *   WP_APP_PASSWORD="xxxx xxxx xxxx xxxx xxxx xxxx" \
 *   node wordpress-theme/content-migration/import-to-wordpress.mjs
 */

import fs from "node:fs/promises";
import path from "node:path";
import { fileURLToPath } from "node:url";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const exportDir = path.join(__dirname, "export");

const WP_BASE_URL = process.env.WP_BASE_URL;
const WP_USER = process.env.WP_USER;
const WP_APP_PASSWORD = process.env.WP_APP_PASSWORD;

function requireEnv() {
  const missing = ["WP_BASE_URL", "WP_USER", "WP_APP_PASSWORD"].filter((key) => !process.env[key]);
  if (missing.length) {
    console.error(`Missing environment variables: ${missing.join(", ")}`);
    process.exit(1);
  }
}

function authHeader() {
  const token = Buffer.from(`${WP_USER}:${WP_APP_PASSWORD}`).toString("base64");
  return { Authorization: `Basic ${token}` };
}

class WpApiError extends Error {
  constructor(message, body) {
    super(message);
    this.body = body;
  }
}

async function wpFetch(url, options = {}) {
  const response = await fetch(url, {
    ...options,
    headers: {
      ...authHeader(),
      ...(options.headers || {}),
    },
  });
  if (!response.ok) {
    const text = await response.text();
    let parsed;
    try {
      parsed = JSON.parse(text);
    } catch {
      parsed = null;
    }
    throw new WpApiError(`${options.method || "GET"} ${url} -> ${response.status}: ${text.slice(0, 500)}`, parsed);
  }
  return response.json();
}

function wpUrl(endpoint, params = {}) {
  const url = new URL(`${WP_BASE_URL}/wp-json/wp/v2/${endpoint}`);
  for (const [key, value] of Object.entries(params)) {
    if (value !== undefined && value !== null) url.searchParams.set(key, value);
  }
  return url.toString();
}

async function wpGet(endpoint, params) {
  return wpFetch(wpUrl(endpoint, params));
}

async function wpPost(endpoint, params, body) {
  return wpFetch(wpUrl(endpoint, params), {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(body),
  });
}

async function customPost(route, body) {
  return wpFetch(`${WP_BASE_URL}/wp-json/dr-ali-moradi/v1/${route}`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(body),
  });
}

async function readJson(name) {
  return JSON.parse(await fs.readFile(path.join(exportDir, name), "utf8"));
}

const LOCALES = ["en", "fa", "ar"];

function paragraphBlock(text) {
  const escaped = String(text || "").replace(/&/g, "&amp;").replace(/</g, "&lt;");
  return `<!-- wp:paragraph -->\n<p>${escaped}</p>\n<!-- /wp:paragraph -->`;
}

function headingBlock(text, level = 2) {
  const escaped = String(text || "").replace(/&/g, "&amp;").replace(/</g, "&lt;");
  return `<!-- wp:heading {"level":${level}} -->\n<h${level}>${escaped}</h${level}>\n<!-- /wp:heading -->`;
}

function linkParagraphBlock(href, text) {
  const escapedText = String(text || "").replace(/&/g, "&amp;").replace(/</g, "&lt;");
  return `<!-- wp:paragraph -->\n<p><a href="${href}">${escapedText}</a></p>\n<!-- /wp:paragraph -->`;
}

function listBlock(items) {
  const li = items.map(({ href, text }) => `<!-- wp:list-item --><li><a href="${href}">${text}</a></li><!-- /wp:list-item -->`).join("\n");
  return `<!-- wp:list -->\n<ul class="wp-block-list">\n${li}\n</ul>\n<!-- /wp:list -->`;
}

/** Localized front-end URL for a path: default locale has no prefix. */
function localizedPath(locale, path) {
  return locale === DEFAULT_LOCALE ? `/${path}/` : `/${locale}/${path}/`;
}

/**
 * The `condition`/`innovation` seed data has no stable id or slug of its
 * own (see conditions-seed.json / innovations-seed.json) — just parallel
 * per-locale arrays aligned by index. Slugs are derived from the English
 * title so re-running the script is stable and idempotent.
 */
function slugify(text) {
  return String(text)
    .toLowerCase()
    .replace(/[&]/g, " and ")
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "");
}

/* ---------------------------------------------------------------------- */
/* Media                                                                   */
/* ---------------------------------------------------------------------- */

const mediaCache = new Map();

async function ensureMedia(entry) {
  if (mediaCache.has(entry.relPath)) return mediaCache.get(entry.relPath);

  // Exact slug match, not WP's fuzzy `search` param: `search` does a plain
  // LIKE match against the title, which is set from the filename *without*
  // its extension, so searching for "name.jpg" (including the extension)
  // can fail to match an already-uploaded "name" title and cause a
  // duplicate re-upload on every re-run.
  const baseSlug = path.parse(entry.filename).name;
  const existing = await wpGet("media", { slug: baseSlug });
  if (existing[0]) {
    mediaCache.set(entry.relPath, existing[0].id);
    return existing[0].id;
  }

  let fileBuffer;
  try {
    fileBuffer = await fs.readFile(entry.sourceFile);
  } catch (error) {
    console.warn(`  ! media file missing on disk, skipping: ${entry.sourceFile}`);
    return null;
  }

  const ext = path.extname(entry.filename).toLowerCase();
  const contentType = { ".jpg": "image/jpeg", ".jpeg": "image/jpeg", ".png": "image/png", ".webp": "image/webp", ".svg": "image/svg+xml" }[ext] || "application/octet-stream";

  const uploaded = await wpFetch(wpUrl("media"), {
    method: "POST",
    headers: {
      "Content-Disposition": `attachment; filename="${entry.filename}"`,
      "Content-Type": contentType,
    },
    body: fileBuffer,
  });
  mediaCache.set(entry.relPath, uploaded.id);
  console.log(`  uploaded media: ${entry.relPath} -> #${uploaded.id}`);
  return uploaded.id;
}

/* ---------------------------------------------------------------------- */
/* Term (taxonomy) translation groups                                     */
/* ---------------------------------------------------------------------- */

const termCache = new Map(); // key: `${taxBase}:${lang}:${name}` -> id

async function ensureTerm(taxBase, lang, name, slug) {
  const cacheKey = `${taxBase}:${lang}:${name}`;
  if (termCache.has(cacheKey)) return termCache.get(cacheKey);

  const existing = await wpGet(taxBase, { search: name, lang, per_page: 50 });
  const match = existing.find((t) => t.name === name);
  if (match) {
    termCache.set(cacheKey, match.id);
    return match.id;
  }

  try {
    const created = await wpPost(taxBase, { lang }, { name, slug: slug || undefined });
    termCache.set(cacheKey, created.id);
    return created.id;
  } catch (error) {
    // WP's term-name collision check is case-insensitive, so a source
    // string like "Clinical education" can collide with a theme-seeded
    // "Clinical Education" term that our own case-sensitive lookup above
    // did not recognize as a match. Reuse the existing term id instead of
    // failing the whole import over a casing difference.
    if (error instanceof WpApiError && error.body?.code === "term_exists" && error.body?.data?.term_id) {
      const id = error.body.data.term_id;
      termCache.set(cacheKey, id);
      return id;
    }
    throw error;
  }
}

/**
 * Ensures one term per locale for the given taxonomy and links them as a
 * Polylang translation group. `namesByLocale` = { en: "X", fa: "Y", ar: "Z" }.
 * Returns { en: id, fa: id, ar: id }.
 */
async function ensureTermGroup(taxBase, namesByLocale, slugsByLocale = {}) {
  const ids = {};
  for (const locale of LOCALES) {
    const name = namesByLocale[locale];
    if (!name) continue;
    ids[locale] = await ensureTerm(taxBase, locale, name, slugsByLocale[locale]);
  }
  if (Object.keys(ids).length >= 2) {
    await customPost("link-term-translations", { translations: ids });
  }
  return ids;
}

/* ---------------------------------------------------------------------- */
/* Post / CPT translation groups                                          */
/* ---------------------------------------------------------------------- */

const DEFAULT_LOCALE = "en";

/**
 * The site's free-tier Polylang install does not have "Share slugs" (a Pro
 * feature) enabled, so WordPress's own slug-uniqueness check auto-suffixes
 * ("-2", "-3", ...) the second and third post created with the same slug in
 * a different language — silently producing unpredictable slugs and, worse,
 * making a same-slug lookup unable to tell locales apart (an earlier version
 * of this script relied on `?lang=` to disambiguate an identical slug across
 * locales, which does not filter WP's collection endpoints in this Polylang
 * version and caused every locale to resolve to the same, first-created
 * entry). Giving each non-default locale its own explicit slug sidesteps
 * both problems: lookups are exact and language-unambiguous, and creation
 * never collides.
 */
function localizedSlug(baseSlug, locale) {
  return locale === DEFAULT_LOCALE ? baseSlug : `${baseSlug}-${locale}`;
}

async function findEntry(postTypeBase, slug) {
  const existing = await wpGet(postTypeBase, { slug, status: "any", per_page: 5 });
  return existing[0] || null;
}

async function ensureEntry(postTypeBase, locale, slug, payload) {
  const existing = await findEntry(postTypeBase, slug);
  if (existing) return existing.id;
  const created = await wpPost(postTypeBase, { lang: locale }, { ...payload, slug });
  return created.id;
}

/**
 * Creates (or finds) one entry per locale for a translation group and links
 * them via the theme's Polylang REST bridge. `payloadByLocale` maps
 * locale -> the REST create payload for that locale (no `slug` needed —
 * `baseSlug` is localized per entry by `localizedSlug()`).
 */
async function ensureTranslatedEntry(postTypeBase, baseSlug, payloadByLocale) {
  const ids = {};
  for (const locale of LOCALES) {
    const payload = payloadByLocale[locale];
    if (!payload) continue;
    ids[locale] = await ensureEntry(postTypeBase, locale, localizedSlug(baseSlug, locale), payload);
  }
  if (Object.keys(ids).length >= 2) {
    await customPost("link-translations", { translations: ids });
  }
  return ids;
}

/* ---------------------------------------------------------------------- */
/* Team members -> CPT team_member                                        */
/* ---------------------------------------------------------------------- */

const AREA_LABELS = {
  clinic: { en: "Clinical Care", fa: "مراقبت بالینی", ar: "الرعاية السريرية" },
  research: { en: "Research", fa: "پژوهش", ar: "البحث" },
  innovation: { en: "Innovation", fa: "نوآوری", ar: "الابتكار" },
};

async function importTeam() {
  const team = await readJson("team.json");
  const media = await readJson("media-manifest.json");
  const mediaByPath = new Map(media.map((m) => [m.relPath, m]));

  console.log(`\n=== Team members (${team.length}) ===`);
  for (const member of team) {
    console.log(`- ${member.slug}`);

    let featuredMediaId = null;
    if (member.image && mediaByPath.has(member.image)) {
      featuredMediaId = await ensureMedia(mediaByPath.get(member.image));
    }

    const areaTermIdsByLocale = {}; // locale -> [termId, ...]
    for (const areaKey of member.areas || []) {
      const labels = AREA_LABELS[areaKey];
      if (!labels) continue;
      const group = await ensureTermGroup("team_area", labels);
      for (const [locale, id] of Object.entries(group)) {
        (areaTermIdsByLocale[locale] ||= []).push(id);
      }
    }

    const payloadByLocale = {};
    for (const locale of LOCALES) {
      if (!member.name[locale]) continue;
      payloadByLocale[locale] = {
        status: "publish",
        title: member.name[locale],
        content: paragraphBlock(member.bio[locale]),
        meta: {
          dam_role: member.role[locale] || "",
          dam_summary: member.summary[locale] || "",
        },
        featured_media: featuredMediaId || undefined,
        team_area: areaTermIdsByLocale[locale] || undefined,
      };
    }

    const ids = await ensureTranslatedEntry("team_member", member.slug, payloadByLocale);
    console.log(`  -> ${JSON.stringify(ids)}`);
  }
}

/* ---------------------------------------------------------------------- */
/* Posts (blog/news/innovation updates) -> WordPress Posts                */
/* ---------------------------------------------------------------------- */

async function importPosts() {
  const posts = await readJson("posts.json");
  const media = await readJson("media-manifest.json");
  const mediaByPath = new Map(media.map((m) => [m.relPath, m]));

  console.log(`\n=== Posts (${posts.length}) ===`);
  for (const post of posts) {
    console.log(`- ${post.slug}`);

    let featuredMediaId = null;
    if (post.image && mediaByPath.has(post.image)) {
      featuredMediaId = await ensureMedia(mediaByPath.get(post.image));
    }

    const categoryGroup = post.category
      ? await ensureTermGroup("categories", post.category)
      : {};

    // Tags have no per-locale text in the source data (just English
    // keywords like "blog"/"care") and Polylang requires a post's terms to
    // match its own language for a translated taxonomy, so tags are only
    // assigned on the English entry rather than manufacturing meaningless
    // "translated" duplicates of an English word.
    const tagIds = [];
    for (const tag of post.tags || []) {
      tagIds.push(await ensureTerm("tags", DEFAULT_LOCALE, tag));
    }

    const payloadByLocale = {};
    for (const locale of LOCALES) {
      if (!post.title[locale]) continue;
      payloadByLocale[locale] = {
        status: "publish",
        date: post.date ? `${post.date}T09:00:00` : undefined,
        title: post.title[locale],
        excerpt: post.excerpt[locale] || "",
        content: paragraphBlock(post.excerpt[locale]),
        featured_media: featuredMediaId || undefined,
        categories: categoryGroup[locale] ? [categoryGroup[locale]] : undefined,
        tags: locale === DEFAULT_LOCALE ? tagIds : undefined,
        meta: {
          dam_read_minutes: post.readMinutes || "",
        },
      };
    }

    const ids = await ensureTranslatedEntry("posts", post.slug, payloadByLocale);
    console.log(`  -> ${JSON.stringify(ids)}`);
  }
}

/* ---------------------------------------------------------------------- */
/* Conditions -> CPT `condition`                                          */
/* ---------------------------------------------------------------------- */

/**
 * Starter/teaser copy only (title + one-line description), carried over
 * verbatim from the homepage's condition teasers — the same text already
 * published on the live reference site. Not full patient-facing detail
 * pages; `condition_category` is intentionally left unassigned since the
 * seed data has no grouping of its own (see open-items.md).
 */
async function importConditions() {
  const seed = await readJson("conditions-seed.json");

  console.log(`\n=== Conditions (${seed.en.length}) ===`);
  for (let index = 0; index < seed.en.length; index += 1) {
    const baseSlug = slugify(seed.en[index].title);
    console.log(`- ${baseSlug}`);

    const payloadByLocale = {};
    for (const locale of LOCALES) {
      const entry = seed[locale]?.[index];
      if (!entry) continue;
      payloadByLocale[locale] = {
        status: "publish",
        title: entry.title,
        content: paragraphBlock(entry.text),
        excerpt: entry.text,
      };
    }

    const ids = await ensureTranslatedEntry("condition", baseSlug, payloadByLocale);
    console.log(`  -> ${JSON.stringify(ids)}`);
  }
}

/* ---------------------------------------------------------------------- */
/* Innovations -> CPT `innovation`                                        */
/* ---------------------------------------------------------------------- */

async function importInnovations() {
  const seed = await readJson("innovations-seed.json");

  console.log(`\n=== Innovations (${seed.en.length}) ===`);
  for (let index = 0; index < seed.en.length; index += 1) {
    const baseSlug = slugify(seed.en[index].title);
    console.log(`- ${baseSlug}`);

    const payloadByLocale = {};
    for (const locale of LOCALES) {
      const entry = seed[locale]?.[index];
      if (!entry) continue;
      payloadByLocale[locale] = {
        status: "publish",
        title: entry.title,
        content: paragraphBlock(entry.text),
        excerpt: entry.text,
        meta: {
          dam_category: entry.category || "",
        },
      };
    }

    const ids = await ensureTranslatedEntry("innovation", baseSlug, payloadByLocale);
    console.log(`  -> ${JSON.stringify(ids)}`);
  }
}

/* ---------------------------------------------------------------------- */
/* Section hub pages (Clinical Care, Innovation, Research)                */
/* ---------------------------------------------------------------------- */

/**
 * `content-migration-plan.md` and `architecture.md` call for Clinical
 * Care / Research / Innovation / Education hub pages. Checked against the
 * live reference site (dralimoradi.moghadam.pro) before building these:
 * none of the four hub routes (/clinic, /innovation, /research,
 * /education) actually render dedicated content there today — each falls
 * back to the same generic page — so there is no existing hub-page copy
 * to migrate. What *does* already exist and is already published is the
 * homepage's three-card "choose the path that fits your visit" teaser
 * (`pathCards` in homepage-content.json) for Clinical Care, Innovation,
 * and Research. These three hub pages reuse that real, already-live text
 * rather than inventing new copy, and add links to the CPT content
 * imported above. Education has no equivalent existing teaser anywhere
 * in the source content, so it is intentionally not created here rather
 * than fabricated — see open-items.md.
 *
 * Slugs deliberately differ from the CPT archive slugs they link to
 * (`conditions`, `innovation`) so the hub page and the archive don't
 * collide on the same URL.
 */
const HUB_PAGES = [
  { pathCardIndex: 0, slug: "clinical-care" },
  { pathCardIndex: 1, slug: "innovations" },
  { pathCardIndex: 2, slug: "research" },
];

async function importHubPages() {
  const homepage = await readJson("homepage-content.json");
  const innovationsSeed = await readJson("innovations-seed.json");

  console.log(`\n=== Section hub pages ===`);
  for (const hub of HUB_PAGES) {
    console.log(`- ${hub.slug}`);

    const payloadByLocale = {};
    for (const locale of LOCALES) {
      const [title, intro] = homepage[locale]?.pathCards?.[hub.pathCardIndex] || [];
      if (!title) continue;

      const blocks = [paragraphBlock(intro)];

      if (hub.slug === "clinical-care") {
        const label = homepage[locale]?.viewConditions || "View all conditions";
        blocks.push(linkParagraphBlock(localizedPath(locale, "conditions"), label));
      }

      if (hub.slug === "innovations") {
        const items = innovationsSeed.en.map((entry, index) => {
          const baseSlug = slugify(entry.title);
          const localTitle = innovationsSeed[locale]?.[index]?.title || entry.title;
          return { href: localizedPath(locale, `innovation/${localizedSlug(baseSlug, locale)}`), text: localTitle };
        });
        blocks.push(listBlock(items));
      }

      payloadByLocale[locale] = {
        status: "publish",
        title,
        content: blocks.join("\n\n"),
        template: "page-hub",
      };
    }

    const ids = await ensureTranslatedEntry("pages", hub.slug, payloadByLocale);
    console.log(`  -> ${JSON.stringify(ids)}`);
  }
}

/* ---------------------------------------------------------------------- */
/* Contact page                                                           */
/* ---------------------------------------------------------------------- */

/**
 * The MPro Forms shortcode for the real contact form (built once, by hand,
 * in wp-admin -> Forms -> New form -> "Contact us (complete)" template,
 * then relabeled to match the fields/copy already in homepage-content.json's
 * `contact` object: Enquiry type options ("Research collaboration",
 * "Education and training", "Media and professional enquiry"), the
 * sensitive-medical-information notice as the message field's placeholder,
 * and as the consent checkbox's label — adapted from the old React site's
 * consent text, which specifically described a mailto-style form ("opens my
 * email application"); MPro Forms stores entries server-side instead, so
 * that description no longer applied, but the underlying instruction (don't
 * send sensitive medical info, use Nobat.ir for a real visit) still does.
 * Not scripted end-to-end: MPro Forms' form builder has no REST/CLI API,
 * only its own wp-admin UI, so the form itself (id 1 on tmp.saveon.me) was
 * built by hand once; this constant just records the resulting shortcode
 * so re-running the importer keeps embedding it.
 *
 * One known limitation, not solved here: the form's field labels are only
 * in English — MPro Forms doesn't hook into Polylang string translation,
 * so `fa`/`ar` visitors see an English-labelled form embedded in their
 * otherwise-translated Contact page.
 */
const CONTACT_FORM_SHORTCODE = '[mpro_form id="1"]';

async function importContactPage() {
  const homepage = await readJson("homepage-content.json");

  console.log(`\n=== Contact page ===`);
  const payloadByLocale = {};
  for (const locale of LOCALES) {
    const c = homepage[locale]?.contact;
    if (!c) continue;

    const blocks = [];
    if (c.intro) blocks.push(paragraphBlock(c.intro));
    if (c.office) blocks.push(paragraphBlock(c.office));
    if (c.clinic) blocks.push(paragraphBlock(c.clinic));
    if (c.beforeTitle) blocks.push(headingBlock(c.beforeTitle, 3));
    if (c.beforeText) blocks.push(paragraphBlock(c.beforeText));
    blocks.push(`<!-- wp:shortcode -->${CONTACT_FORM_SHORTCODE}<!-- /wp:shortcode -->`);

    payloadByLocale[locale] = {
      status: "publish",
      title: c.title || "Contact",
      content: blocks.join("\n\n"),
      template: "page-contact",
    };
  }

  const ids = await ensureTranslatedEntry("pages", "contact", payloadByLocale);
  console.log(`  -> ${JSON.stringify(ids)}`);
}

/* ---------------------------------------------------------------------- */
/* Menus                                                                  */
/* ---------------------------------------------------------------------- */

/* ---------------------------------------------------------------------- */
/* Posts listing ("Blog") page                                            */
/* ---------------------------------------------------------------------- */

/**
 * `front-page.html` renders at "/" and takes priority over WordPress's
 * normal "latest posts" home, so there was previously no URL a "View all
 * posts" or "Blog" link could point to. Fixes that the standard way: a
 * dedicated Posts page (Settings -> Reading), rendered by the new
 * templates/home.html. That requires `show_on_front` to be "page" (not
 * "posts"), which in turn requires a real `page_on_front` id even though
 * its own content is never shown — front-page.html still wins at "/"
 * regardless of this setting, because is_front_page() is true for
 * whichever page page_on_front names. A small placeholder page fills
 * that requirement.
 *
 * Known cosmetic side effect, not fixed here: visiting "/fa/" or "/ar/"
 * (as opposed to "/") 301-redirects once to that language's placeholder
 * page's own canonical URL (e.g. "/fa/front-page-placeholder-fa/") before
 * rendering the real homepage — WordPress's canonical-redirect logic
 * doesn't know the placeholder's slug is meant to be invisible. Content
 * and functionality are unaffected; only a minor extra redirect hop and a
 * not-quite-clean URL in the address bar after landing. Giving the
 * placeholder pages an empty slug might avoid this but wasn't tried.
 *
 * The "Blog" label (not "News" or "News & Insights") is the literal
 * value already in homepage-content.json's own `nav` array — used as-is
 * rather than picked, since the final label is an explicit open content
 * decision for Dr. Moradi (see open-items.md) this script doesn't try to
 * resolve.
 */
async function importBlogIndexPage() {
  console.log(`\n=== Posts listing page ===`);

  // Needs a translation in every language, not just English: `page_on_front`
  // is a single global option, and neither the classic Reading Settings
  // screen nor Polylang resolve it per-language on their own (confirmed by
  // testing — see dam_translate_front_page_option() in inc/polylang.php,
  // which is what actually makes "/fa/" and "/ar/" resolve to front-page.html
  // instead of silently falling back to the posts listing). The title is
  // kept short and clean ("Home"/"خانه"/"الرئيسية"), not a warning label:
  // this page's own title/content are never shown to a visitor (front-page.html
  // always wins at "/"), but the title still leaks into the browser tab and
  // the SEO <title> tag, so a long "do not delete" label would be publicly
  // visible. The warning instead lives in the page's body content, seen
  // only by an editor who opens it in wp-admin.
  const placeholderTitle = { en: "Home", fa: "خانه", ar: "الرئيسية" };
  const placeholderNote = {
    en: 'This page exists only to satisfy WordPress’s "static front page" requirement. front-page.html always renders the real homepage regardless of this page’s content. Do not delete it; if it is ever deleted, create a new page and reselect it in Settings → Reading → Homepage displays.',
    fa: "این صفحه فقط برای برآورده کردن نیاز وردپرس به یک «صفحه اصلی ثابت» وجود دارد. front-page.html همیشه هوم‌پیج واقعی را نمایش می‌دهد، صرف‌نظر از محتوای این صفحه. این صفحه را حذف نکنید؛ در صورت حذف، یک صفحه جدید بسازید و آن را در تنظیمات ← خواندن ← نمایش صفحه اصلی انتخاب کنید.",
    ar: "توجد هذه الصفحة فقط لتلبية متطلب ووردبريس لـ «صفحة أمامية ثابتة». يعرض front-page.html دائمًا الصفحة الرئيسية الحقيقية، بغض النظر عن محتوى هذه الصفحة. لا تحذف هذه الصفحة؛ إذا حُذفت، أنشئ صفحة جديدة وأعد اختيارها في الإعدادات ← القراءة ← تعرض الصفحة الرئيسية.",
  };
  const placeholderPayload = {};
  for (const locale of LOCALES) {
    placeholderPayload[locale] = {
      status: "publish",
      title: placeholderTitle[locale],
      content: paragraphBlock(placeholderNote[locale]),
    };
  }
  const placeholderIds = await ensureTranslatedEntry("pages", "front-page-placeholder", placeholderPayload);
  const homeId = placeholderIds[DEFAULT_LOCALE];
  console.log(`  front-page placeholder -> ${JSON.stringify(placeholderIds)}`);

  const homepage = await readJson("homepage-content.json");
  const blogLabel = { en: "Blog", fa: "وبلاگ", ar: "المدونة" };
  const payloadByLocale = {};
  for (const locale of LOCALES) {
    payloadByLocale[locale] = { status: "publish", title: blogLabel[locale] };
  }
  const blogIds = await ensureTranslatedEntry("pages", "blog", payloadByLocale);
  console.log(`  blog index -> ${JSON.stringify(blogIds)}`);

  await wpFetch(`${WP_BASE_URL}/wp-json/wp/v2/settings`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      show_on_front: "page",
      page_on_front: homeId,
      page_for_posts: blogIds[DEFAULT_LOCALE],
    }),
  });
  console.log(`  Reading settings: show_on_front=page, page_on_front=${homeId}, page_for_posts=${blogIds.en}`);
}

async function ensureMenu(name) {
  const existing = await wpFetch(`${WP_BASE_URL}/wp-json/wp/v2/menus?search=${encodeURIComponent(name)}`);
  const match = existing.find((m) => m.name === name);
  if (match) return match.id;
  const created = await wpFetch(`${WP_BASE_URL}/wp-json/wp/v2/menus`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ name }),
  });
  return created.id;
}

async function setMenuLocation(location, menuId) {
  await customPost("set-menu-location", { location, menu_id: menuId });
}

/** Adds a menu item if one for the same target doesn't already exist. */
async function ensureMenuItem(menuId, item) {
  const existing = await wpFetch(`${WP_BASE_URL}/wp-json/wp/v2/menu-items?menus=${menuId}&per_page=50`);
  const isMatch = (i) =>
    item.object_id ? i.object_id === item.object_id && i.object === item.object : i.url === item.url;
  if (existing.some(isMatch)) return;

  await wpFetch(`${WP_BASE_URL}/wp-json/wp/v2/menu-items`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ ...item, menus: menuId, status: "publish" }),
  });
}

/**
 * Primary nav mirrors the real, already-published `nav` labels from
 * homepage-content.json (["Clinic","Innovation","Research","Education",
 * "About me","Blog"]) for the items that have a destination page.
 * Education is intentionally left out: it has no real page to link to
 * (see importHubPages()'s note on Education). Blog now links to the
 * page importBlogIndexPage() sets up as the Posts page. Footer repeats
 * the same links plus Contact, which the reference site's primary nav
 * doesn't carry as a top-level item.
 *
 * IMPORTANT — manual step still required after running this: this creates
 * the 6 menus, adds their items, and pre-seeds their location assignment
 * via the theme's /set-menu-location bridge, but Polylang does not treat
 * that REST write as a real save — it keeps reporting every location as
 * unset (has_nav_menu() returns false site-wide) until each of the 6
 * menus is opened once in Appearance -> Menus and saved via that screen's
 * own "Save Menu" button. See the note on that endpoint in
 * inc/polylang.php and progress-log.md on the docs branch.
 */
async function importMenus() {
  const homepage = await readJson("homepage-content.json");
  const home = { en: "Home", fa: "خانه", ar: "الرئيسية" };

  console.log(`\n=== Menus ===`);
  for (const locale of LOCALES) {
    const nav = homepage[locale]?.nav || [];
    const contactLabel = homepage[locale]?.contact?.kicker || "Contact";

    async function pageId(baseSlug) {
      const found = await findEntry("pages", localizedSlug(baseSlug, locale));
      return found?.id;
    }

    const homeHref = locale === DEFAULT_LOCALE ? "/" : `/${locale}/`;
    const items = [
      { title: home[locale], type: "custom", url: `${WP_BASE_URL}${homeHref}` },
      { title: nav[0], pageSlug: "clinical-care" },
      { title: nav[1], pageSlug: "innovations" },
      { title: nav[2], pageSlug: "research" },
      { title: nav[4], pageSlug: "about" },
      { title: nav[5], pageSlug: "blog" },
    ];

    for (const kind of ["primary", "footer"]) {
      const menuName = `${kind === "primary" ? "Primary" : "Footer"} Navigation (${locale.toUpperCase()})`;
      const menuId = await ensureMenu(menuName);
      const location = locale === DEFAULT_LOCALE ? kind : `${kind}___${locale}`;
      await setMenuLocation(location, menuId);

      const menuOrder = kind === "primary" ? items : [...items, { title: contactLabel, pageSlug: "contact" }];
      let order = 1;
      for (const entry of menuOrder) {
        if (entry.pageSlug) {
          const id = await pageId(entry.pageSlug);
          if (!id) continue;
          await ensureMenuItem(menuId, { title: entry.title, type: "post_type", object: "page", object_id: id, menu_order: order });
        } else {
          await ensureMenuItem(menuId, { title: entry.title, type: "custom", url: entry.url, menu_order: order });
        }
        order += 1;
      }
      console.log(`- ${menuName} (id ${menuId}) -> ${location}`);
    }
  }
}

/* ---------------------------------------------------------------------- */
/* About page -> WordPress Page (page.html template)                      */
/* ---------------------------------------------------------------------- */

async function importAboutPage() {
  const about = await readJson("about.json");

  console.log(`\n=== About page ===`);
  const payloadByLocale = {};
  for (const locale of LOCALES) {
    const a = about[locale];
    if (!a) continue;

    const blocks = [];
    if (a.intro) blocks.push(paragraphBlock(a.intro));
    if (a.storyTitle) blocks.push(headingBlock(a.storyTitle, 2));
    for (const paragraph of a.storyText || []) blocks.push(paragraphBlock(paragraph));
    if (a.practiceTitle) blocks.push(headingBlock(a.practiceTitle, 2));
    if (a.practiceText) blocks.push(paragraphBlock(a.practiceText));
    for (const principle of a.principles || []) {
      blocks.push(headingBlock(principle.title, 3));
      blocks.push(paragraphBlock(principle.text));
    }
    if (a.credentials?.length) {
      const items = a.credentials.map((c) => `<!-- wp:list-item --><li>${c}</li><!-- /wp:list-item -->`).join("\n");
      blocks.push(`<!-- wp:list -->\n<ul class="wp-block-list">\n${items}\n</ul>\n<!-- /wp:list -->`);
    }

    payloadByLocale[locale] = {
      status: "publish",
      title: a.title || "About",
      content: blocks.join("\n\n"),
    };
  }

  const ids = await ensureTranslatedEntry("pages", "about", payloadByLocale);
  console.log(`  -> ${JSON.stringify(ids)}`);
}

/* ---------------------------------------------------------------------- */

async function main() {
  requireEnv();
  await importTeam();
  await importPosts();
  await importConditions();
  await importInnovations();
  await importHubPages();
  await importContactPage();
  await importAboutPage();
  await importBlogIndexPage();
  await importMenus();
  console.log("\nDone.");
}

main().catch((error) => {
  console.error(error);
  process.exitCode = 1;
});
