# Progress Log

Running record of what has actually been built, in order. Each entry says
what changed and where, so this file plus `git log` on
`claude/wordpress-doctor-site-architecture-m4766g` is a complete record
without needing to replay the chat.

## 2026-09-02

- Architecture agreed (see `architecture.md`) and documentation branch
  (`docs`) created with this folder.
- Theme scaffolded on `claude/wordpress-doctor-site-architecture-m4766g`,
  under `wordpress-theme/dr-ali-moradi/`:
  - `theme.json` — Global Styles (color palette, layout widths) and
    self-hosted `@font-face` declarations for Inter, Vazirmatn, and
    Scheherazade New, with correct per-script `unicode-range` values
    (Latin + Arabic subsets). The `.woff2` files themselves were vendored
    from this repo's own `@fontsource-variable/inter`,
    `@fontsource-variable/vazirmatn`, and `@fontsource/scheherazade-new`
    packages into `assets/fonts/`, matching the fonts the current React
    site already uses. `customTemplates` (page-hub, page-contact,
    page-full-width) and `templateParts` (header, footer) declared.
  - `templates/` — `index.html` (required fallback), `front-page.html`
    (the one theme-embedded page, per the "nothing but the homepage is
    part of the theme" instruction), `page.html`, `page-hub.html`,
    `page-contact.html`, `page-full-width.html`, `single.html`,
    `archive.html`, `search.html`, `404.html`, and CPT singular templates
    named per WordPress's own template hierarchy —
    `single-team_member.html`, `single-condition.html`,
    `single-innovation.html` — rather than the `singular-*` names used
    loosely in `architecture.md`'s first draft of the template table;
    `publication` and `patient_resource` fall back to `single.html` for
    now (acceptable — no bespoke layout needed yet).
  - `parts/header.html`, `parts/footer.html` — editable in the Site
    Editor; navigation and CTA come from the theme's own dynamic blocks,
    not hardcoded text, so they stay translatable (see the Polylang
    constraint noted in `architecture.md`).
  - `inc/post-types.php`, `inc/taxonomies.php` — the five CPTs
    (`team_member`, `condition`, `innovation`, `publication`,
    `patient_resource`) and their taxonomies
    (`condition_category`, `publication_type`, `team_area`), plus the
    standard `category` terms the current unified blog/news model uses.
  - `inc/meta-fields.php` + `assets/js/theme-fields.js` — native
    (ACF-free) scalar custom fields, edited through a generic block-editor
    sidebar panel driven by one shared field-definition list in PHP.
  - `inc/theme-options.php` — the single Theme Options screen (Settings
    API) for phone/WhatsApp/address, social links, the appointment URL
    (defaults to the approved `https://nobat.ir/9705`), and impact
    numbers with an "as of" date.
  - `blocks/contact-info`, `blocks/impact-stats`, `blocks/appointment-cta`,
    `blocks/site-navigation` — dynamic blocks reading from Theme Options
    and, for navigation, from `wp_nav_menu()` against a registered
    `theme_location`, so menus are always managed on the classic
    Appearance → Menus screen rather than inside the Site Editor.
  - `inc/polylang.php` — `pll_register_string()` registration for the
    theme's own static labels.
  - All PHP files pass `php -l` (PHP 8.4); all JSON files (including
    every `block.json`) validated; every block template's opening/closing
    block comments checked for balance.
- Content extraction script written and **run successfully**:
  `wordpress-theme/content-migration/extract-content.mjs`, executed with
  Node 22's built-in TypeScript stripping
  (`node --experimental-strip-types`), produced 23 posts, 8 team members,
  and a 16-file media manifest from `app/site-content.ts`,
  `app/blog-content.ts`, `app/structured-content.ts`, and
  `app/about-content.ts` with no errors. Output is gitignored
  (`wordpress-theme/content-migration/export/`) since it's regenerable by
  re-running the script.
- Confirmed while building the extraction script: the current content
  model has no phone number or street address anywhere (appointment flow
  is entirely through the external `nobat.ir` link) — so the Theme
  Options phone/WhatsApp/address fields will start empty; not a bug, just
  something the operator (or Dr. Moradi) needs to fill in once available.
- `import-to-wordpress.mjs` (REST API import) is written and reads the
  extracted JSON correctly, but the Polylang translation-linking step is
  left as a documented stub — the exact REST fields depend on the target
  site's configured languages, which are only knowable once WordPress
  site access exists.
- **Not yet done, blocked on access** (see `open-items.md`): installing
  the theme on `https://tmp.saveon.me/`, running the real import, visual
  design/CSS pass to match the approved look, and the final content
  parity check against `https://dralimoradi.moghadam.pro/`.

## 2026-09-02 (continued) — theme deployed, Polylang wired, content imported

Access unblocked: the operator logged into `https://tmp.saveon.me/`
wp-admin in a Claude-driven browser session, so the theme could be
installed and content imported directly, without SSH/SFTP or the operator
generating anything by hand (an Application Password was generated
programmatically from the already-authenticated session instead).

- **Theme installed and activated** via Appearance → Themes → Upload
  Theme (a zip built with .NET's `System.IO.Compression`, not PowerShell's
  `Compress-Archive` — the latter writes zip entries with backslash path
  separators on Windows, which WordPress's unzip treats as a literal
  character rather than a directory separator, so the theme silently
  "had no style.css" on first attempt).
- **Polylang (free) was already installed and pre-configured** on this
  site with exactly the three required languages — English (default),
  فارسی (`fa`/`fa_IR`), العربية (`ar`) — no setup needed there. Rank Math
  SEO was also already installed. This session additionally:
  - Enabled translation for all five custom post types and all three
    custom taxonomies under Settings → Languages → Custom post types and
    Taxonomies (previously off).
  - Changed Permalinks from "Plain" (`?p=123`) to "Post name": required
    for both clean/SEO URLs (architecture.md) and for `/wp-json/` to
    resolve as pretty URLs at all — with Plain permalinks the REST API
    only responds under `/?rest_route=`.
- **Polylang free-tier REST gap found and worked around in the theme's
  own code**, not a plugin: Polylang's REST integration sets a single
  entry's language on create via `?lang=xx` (confirmed working), but
  translation-group *linking* (`translations[lang]=id`) is documented
  only for Polylang Pro's REST API and does nothing on the free tier.
  Since only free Polylang is allowed, `inc/polylang.php` now registers
  two small REST routes (`dr-ali-moradi/v1/link-translations` and
  `.../link-term-translations`, `manage_options`-gated) that call
  Polylang's free, public PHP functions `pll_save_post_translations()`
  / `pll_save_term_translations()` directly. This is the theme's own
  code calling Polylang's own free API — not a Polylang Pro dependency.
- **Theme Options wired to the REST API**: `register_setting()` was
  registered on `admin_init`, which never runs during a REST request, so
  the fields were invisible to `/wp/v2/settings` even with
  `show_in_rest`. Moved to `init`. Also added the `dam_summary`
  (team_member) and `dam_read_minutes` (post) meta fields that
  `architecture.md`'s content model called for but the initial scaffold
  had not wired up.
- **Content extraction re-run** (`extract-content.mjs`) against the
  current `app/*.ts` — same result as before (23 posts, 8 team members,
  16 media files) — and **`import-to-wordpress.mjs` finished and run for
  real** against `https://tmp.saveon.me/`. Bugs found and fixed while
  running it against the live REST API (not visible from reading the
  code):
  - **Media dedup**: matching by WordPress's `search` param (a literal
    title `LIKE` match) failed whenever the filename's extension made the
    search string longer than the stored (extension-less) title,
    silently re-uploading the same file on every re-run. Fixed to match
    on exact attachment `slug` instead.
  - **Term name collisions**: WordPress's term-uniqueness check is
    case-insensitive, so a source category string like "Clinical
    education" collided with the theme's own seeded "Clinical Education"
    term (`inc/taxonomies.php`). Fixed by catching WordPress's
    `term_exists` error and reusing the existing term id.
  - **The core bug**: entries were first looked up per locale via
    `?slug=X&lang=fa`, on the assumption (stated in the original stub's
    comments) that `lang` filters collection queries the way it filters
    writes. On this site it does not — `?lang=fa` and `?lang=en` returned
    identical result sets — so every locale's lookup found the
    already-created English entry and reused it, meaning the first full
    run created only English content: three identical ids
    (`{"en":16,"fa":16,"ar":16}`) instead of three distinct, linked
    entries. Confirmed this is a free-tier limitation, not a
    misconfiguration: WordPress also auto-suffixes ("-2", "-3") a second
    post created with an already-used slug in a different language
    unless Polylang's Pro-only "Share slugs" feature is active, so a
    shared slug can't reliably identify *or* create a specific locale's
    entry either way. Fixed by giving every non-default-locale entry an
    explicit, distinct slug (`{slug}-fa`, `{slug}-ar`) and looking up by
    that exact slug — deterministic, and no dependency on `lang`
    filtering working at all. Re-run after the fix correctly produced
    three distinct, Polylang-linked entries per group; spot-checked live
    on the site (see below).
  - **Tags simplification**: the source data has no per-locale text for
    post tags (English keywords like `"blog"`, `"care"`) and Polylang
    requires a post's taxonomy terms to be in the post's own language, so
    manufacturing "translated" duplicate terms for an English keyword
    would be make-work with no real translation. Tags are only assigned
    on the English entry; `fa`/`ar` posts carry no tags. Flagged here
    rather than silently decided and forgotten.
  - Categories and the `team_area` taxonomy needed no such fix — their
    source text genuinely differs per locale, so per-locale terms were
    created correctly from the start.
- **Imported, live on `https://tmp.saveon.me/`**: 8 `team_member` entries,
  23 `post` entries, and the About page, each in English, Persian, and
  Arabic, correctly linked as Polylang translation groups. Spot-checked
  in the browser: `/team/ali-moradi/`, `/fa/team/ali-moradi-fa/` (RTL,
  translated bio and `team_area` terms), `/ar/understanding-carpal-tunnel-syndrome-ar/`
  (RTL, translated title/category/excerpt, correct featured image) all
  render correctly with the theme's actual styling (not just raw
  content).
- **Theme Options populated** with the real values already visible on
  the live reference site's footer (`https://dralimoradi.moghadam.pro/`):
  phone `+98 51 3229 0968`, private office and hospital-clinic addresses,
  the impact/evidence numbers (150+ articles, 4 books, 30 national
  patents, 8 international patent families) from `about-content.ts`'s
  CV data. Left blank rather than fabricated: WhatsApp number, social
  media URLs (labels exist in the source content but not the actual
  links), and the impact numbers' "as of" date — all three genuinely
  absent from the current content model, matching `open-items.md`'s
  existing "content decisions still open" list.
- **Known cosmetic issue, not yet fixed**: the phone number in the
  footer/contact-info block visually reverses in RTL locales (`fa`/`ar`)
  — a bidi/`dir` styling issue in the block's markup, not a data problem
  (the stored value is correct). Needs a `dir="ltr"` (or unicode-bidi
  isolate) wrapper around the number in `blocks/contact-info`.
- **Not yet done** as of this entry — see the next entry below for what
  was completed after it.

## 2026-09-02 (continued further) — conditions, innovations, hub pages, contact, menus

- **Conditions and Innovations CPTs imported** from the existing
  `conditions-seed.json` / `innovations-seed.json` starter/teaser copy
  (6 conditions, 3 innovations, each in en/fa/ar, Polylang-linked) —
  same status as before: teaser copy only, needs real patient-facing
  detail content before launch, per the "Which conditions have approved
  patient-facing copy for launch" item already in `open-items.md`.
  `innovation` gained a `dam_category` meta field (architecture.md's
  content model calls for one; the seed data has a category per entry
  that had nowhere to live).
- **Section hub pages built for Clinical Care, Innovation, and
  Research** (`page-hub` template) — but first checked the live
  reference site (`https://dralimoradi.moghadam.pro/clinic`,
  `/innovation`, `/research`, `/education`): none of the four hub
  routes render dedicated content there today; every one falls back to
  the same generic page. So there was no existing hub-page copy to
  migrate. What these three pages actually contain is the homepage's
  already-published "choose the path that fits your visit" teaser text
  (`pathCards` in homepage-content.json) plus links to the imported
  CPT/page content — real, already-live text, not new copy. **Education
  was intentionally not created**: it has no equivalent teaser or any
  other content anywhere in the source, on either site, so building it
  would mean inventing content rather than migrating it. Flagged in
  `open-items.md` for the operator to supply real content for.
- **Contact page built** from the homepage's real `contact` object
  (intro, office/hospital addresses, "before you write" notice) with an
  explicit placeholder paragraph noting the MPro Forms contact form
  still needs to be inserted once available.
- **Menus**: all 6 (Primary/Footer × en/fa/ar) created, populated from
  the real `nav` labels already in homepage-content.json
  (`["Clinic","Innovation","Research","Education","About me","Blog"]`
  per locale), and linked to their Site Editor locations. Education and
  Blog are omitted from the nav for the same reason Education's hub
  page is: Education has no destination page, and there is no working
  "all posts" archive URL yet (`front-page.html` takes over `/`, so
  there's nowhere for a "Blog" link to point — flagged as an open
  question, not solved here, since it's a real content-architecture
  decision).
  - **Real bug found and fixed while doing this**: `?lang=` does not
    filter WordPress REST *collection* queries in this Polylang setup
    (confirmed by testing: `?lang=fa` and `?lang=en` on the same
    collection returned identical results) even though it correctly
    sets a single entry's language on create. The very first import run
    relied on `?lang=` to tell locales apart when looking up an
    existing entry by a shared slug, so every locale's lookup found the
    same first-created (English) entry and reused it — all "three"
    locale versions of every team member, post, and page were actually
    one English row with the same id repeated three times
    (`{"en":16,"fa":16,"ar":16}`). Also confirmed (by testing) that a
    shared slug can't work as a fallback either: without Polylang Pro's
    "Share slugs" feature, WordPress auto-suffixes ("-2", "-3") a second
    post created with an already-used slug in a different language,
    *even after* the two posts are linked as Polylang translations —
    the exemption from the uniqueness check is a Pro-only behavior.
    Fixed by giving every non-default-locale entry an explicit slug
    (`{slug}-fa`, `{slug}-ar`) and looking up by that exact slug, which
    needs no `lang` filtering to work correctly. Re-imported everything
    after the fix; all team members, posts, conditions, innovations,
    and pages now have three genuinely distinct, correctly linked
    entries — spot-checked live (e.g. `/fa/team/ali-moradi-fa/`,
    `/ar/understanding-carpal-tunnel-syndrome-ar/`).
  - **Second real bug found and fixed**: menu *locations* could not be
    assigned via the REST API the way menu creation and content import
    could. `/wp/v2/menus`'s `locations` field only accepts
    `get_registered_nav_menus()` keys (`primary`, `footer`) — Polylang's
    per-language locations (`primary___fa`, shown as "Primary Navigation
    فارسی" in Appearance → Menus) aren't in that list, so REST rejects
    them outright. Worked around this the same way as the translation-
    linking gap: `inc/polylang.php` now exposes `/set-menu-location`,
    calling `set_theme_mod()` directly — but testing (added a temporary
    debug comment to `blocks/site-navigation/render.php`, since
    `has_nav_menu()` was silently returning false with no visible error)
    showed Polylang does not treat that write as equivalent to its own
    classic-screen save: Polylang hooks the theme_mods option on save
    and reprocesses it into its own per-language storage, and a raw
    write outside that hook leaves `has_nav_menu()` reporting *every*
    location as unset, not just the ones never touched. The REST route
    still runs and pre-seeds the assignment, but each of the 6 menus
    then had to be opened once in Appearance → Menus and saved via that
    screen's own "Save Menu" button before the front end actually
    picked up any of them — confirmed this is genuinely required (not
    caching) by testing the debug output before and after that manual
    save. This is now the documented, repeatable extra step after
    running the importer; removed the debug code once confirmed working.
- Verified live, in the browser, for all three languages: primary and
  footer navigation render with the correct localized labels and
  correct localized links (e.g. `/fa/innovations-fa/` linking to
  `/fa/innovation/external-fixation-systems-fa/`), and the Clinical
  Care / Innovation / Research / Contact pages render their real content
  correctly with the theme's actual styling.
- **Still not done**:
  - Education content (hub page + nav item) — needs the operator to
    supply real content; not fabricated here (see above).
  - A working "all posts" listing page/URL — `front-page.html` takes
    over `/`, so there's currently no page a "Blog" nav item or a "View
    all posts" link could point to. Needs a decision (a dedicated posts
    page at another slug? drop the front-page override on that one
    route?) before it can be built — flagged rather than guessed at.
  - `publication` and `patient_resource` CPTs — no seed data was ever
    extracted for these (unlike conditions/innovations); not imported.
  - Full page-by-page, language-by-language parity check against
    `https://dralimoradi.moghadam.pro/` — still only spot-checked, not
    exhaustive.
  - The RTL phone-number display bug noted above — still open.
  - A visual/CSS polish pass against the approved look — still open.

## 2026-09-03 — RTL fix, site-wide health check, Rank Math setup

- **RTL phone-number bug fixed**: added `dir="ltr"` to the phone link in
  `blocks/contact-info/render.php`, so the number reads correctly
  left-to-right inside `fa`/`ar` pages. Deployed and left the theme in
  this state (see below for what else changed in the same deploy).
- **Visual design gap identified and discussed with the operator**: the
  operator asked why the site doesn't look like the React version on
  their subdomain. Confirmed by comparing screenshots: `front-page.html`
  and the other templates are functional placeholders (plain WP block
  markup — a solid-color hero, no photo, no icons, no decorative
  treatment) built to get the content pipeline (CPTs, multilingual,
  menus) working first, not a visual match to the reference design. The
  color palette and fonts in `theme.json` are already correct, and the
  reference design's actual photography already exists in the repo
  (`public/media/hero/`, `public/media/edited/`) — a visual/CSS pass is
  possible without new assets, just not yet done. Given the choice
  between starting that (large) redesign pass now versus continuing
  content/technical work, **the operator chose to continue
  content/technical work** — the visual pass remains open, tracked in
  `open-items.md`, not abandoned.
- **Site-wide automated health check** (script-driven, not manual) across
  every published entry (137 URLs: 24 team_member + 70 posts + 18
  condition + 9 innovation + 16 pages, across en/fa/ar):
  - All 137 return HTTP 200 (no broken links/404s from the import).
  - Zero pages contain PHP `Notice:`/`Warning:`/`Deprecated:`/`Fatal
    error:` text in their rendered HTML.
  - All 90 `fa`/`ar` pages correctly render `dir="rtl"` on `<html>`.
- **Rank Math setup wizard completed** (it had never been run — the
  free XML sitemap was 404ing because of this, not a bug). Chose "Easy"
  mode; business type "Physician" (available in the free tier — more
  precise than `architecture.md`'s original assumption that only the
  generic "Local Business"/"Medical Business" types were free; corrected
  here since real behavior beats the earlier assumption); Person name
  set to "Dr. Ali Moradi" for the Google Knowledge Panel field. Declined
  the two steps that require connecting an external account (Rank
  Math's own account, and Google Search Console/Analytics) — those are
  the operator's own accounts to connect, not something done on their
  behalf. Enabled the sitemap for every public post type and taxonomy
  (`team_member`, `condition`, `innovation`, `publication`,
  `patient_resource`, `category`, `condition_category`,
  `publication_type`, `team_area`) — `publication`/`patient_resource`
  have no content yet but are enabled so nothing needs revisiting once
  they do. Verified `https://tmp.saveon.me/sitemap_index.xml` now
  returns real per-type sitemaps, and `post-sitemap.xml` correctly lists
  all three language variants of each post.
- **Removed WordPress's default placeholder content**: the "Hello
  world!" post and "Sample Page" (both auto-created on install, never
  part of the real site).
- **301 redirect map (content-migration-plan.md step 6) deliberately not
  built yet** — checked `app/sitemap.ts`: it would need every old
  Next.js URL (`/[locale]/team/{slug}`, `/[locale]/blog/{slug}`, etc.)
  mapped to the new WordPress URLs, several of which now differ in
  shape (e.g. `fa`/`ar` entries get a `-fa`/`-ar` slug suffix — see the
  slug-collision bug fixed on 2026-09-02). This redirect map only
  matters once a domain that was actually live at the *old* URLs cuts
  over to the new site — `tmp.saveon.me` was never public at those old
  paths, so building it now would be premature, not skipped work.
  Revisit at the `dralimoradi.com` cutover, using the now-finalized
  slug scheme.

## 2026-09-05 — a working "Blog" page, and a third Polylang gap found and fixed

Closes the "no working posts listing / Blog nav item" item from
`open-items.md`.

- **`templates/home.html` added** — a query-loop template (post
  featured image, category, title, excerpt, date; paginated) for
  WordPress's standard Posts-page mechanism, modeled on `archive.html`.
  First created directly via the `/wp/v2/templates` REST endpoint (no
  theme re-zip/upload needed for a block-template-only change — worth
  remembering for future template edits), then also committed as a real
  theme file so a fresh theme deploy reproduces it.
- **A real "Blog" page** now exists in all three languages
  (`ensureTranslatedEntry`-based, same as every other page), plus a
  small placeholder page to satisfy `page_on_front`'s requirement of a
  real page id. Settings → Reading now has `show_on_front: page`,
  `page_on_front`/`page_for_posts` pointing at these. The "Blog" nav
  item (previously omitted — see the 2026-09-02 entry) is now in all
  three Primary menus, linking correctly.
- **Third Polylang free-tier gap found and fixed, in the theme's own
  code**: confirmed by testing that Polylang has no per-language
  mechanism at all for the static front page or posts page — unlike
  Nav Menu locations (a distinct location per language, set from
  Appearance → Menus), the classic Reading Settings screen has one
  flat, language-mixed page picker, and saving it (via REST or through
  that screen itself) only ever stores one global page id. Concretely:
  with the placeholder correctly linked as a Polylang translation group
  and `page_on_front` set to its English id, `/fa/` and `/ar/` still
  rendered the posts listing instead of `front-page.html` — before *and
  after* re-saving Reading Settings through wp-admin (ruling out "needs
  a real save," the fix the menu-location gap needed). Fixed with
  `dam_translate_front_page_option()` in `inc/polylang.php`: filters
  `option_page_on_front` / `option_page_for_posts` to resolve to the
  *current language's* Polylang translation of whichever page is
  configured. Both `is_front_page()` and the posts-page query read
  these options directly, so this one filter fixes both. Verified live
  in all three languages after the fix.
- **Known minor cosmetic side effect, not fixed**: visiting `/fa/` or
  `/ar/` (not `/`) now 301-redirects once to that language's
  placeholder page's own canonical URL (e.g.
  `/fa/front-page-placeholder-fa/`) before rendering the real homepage.
  Content and functionality are correct; only an extra redirect hop and
  a not-quite-clean URL after landing. An empty placeholder-page slug
  might avoid this; not tried.
- **Caught before it shipped**: the placeholder page's title was
  initially a long "do not delete" warning, which — since a page's
  title is used for the browser tab and SEO `<title>` tag regardless of
  whether `front-page.html` overrides the visible body — leaked that
  warning into every homepage's `<title>`. Fixed: short clean titles
  ("Home"/"خانه"/"الرئيسية"), with the warning moved into the page's
  body content instead, seen only by an editor who opens it in
  wp-admin.
- Re-ran the full automated health check (141 URLs, all three
  languages) after all of the above: 0 broken links, 0 PHP
  errors/warnings, 0 pages missing `dir="rtl"`.

## 2026-09-05 (continued) — a real MPro Forms contact form

The Contact page had a "MPro Forms placeholder" text note instead of an
actual form (architecture.md: "All forms ... are built and rendered by
MPro Forms"). Built one:

- Used wp-admin → Forms → New form → "Contact us (complete)" template
  (MPro Forms has no REST or CLI API — its form builder is the only way
  to create one) and relabeled its fields to match the real copy
  already in `homepage-content.json`'s `contact` object: the "Enquiry
  type" dropdown's three options (Research collaboration / Education
  and training / Media and professional enquiry), the message field's
  sensitive-medical-information placeholder, and the consent checkbox.
  The consent text is *adapted*, not copied verbatim: the old React
  site's version specifically described a mailto-style form ("opens my
  email application"), which is factually wrong for MPro Forms (stores
  entries server-side, has its own inbox) — kept the underlying
  instruction (don't send sensitive medical info; use Nobat.ir for a
  real visit) and dropped the no-longer-true mechanism description.
- Embedded via `[mpro_form id="1"]` on all three language versions of
  the Contact page (`import-to-wordpress.mjs`'s `importContactPage()`
  updated to embed this shortcode for future runs; the three
  already-existing Contact pages were patched directly since the
  importer's idempotent `ensureEntry` intentionally never overwrites an
  existing entry's content).
- Verified live: the form renders with the correct fields, options, and
  placeholder text on `/contact/`; `/fa/contact-fa/` and
  `/ar/contact-ar/` both return 200 with the form present and no PHP
  errors. Did not submit a test entry (would create a fake row in the
  operator's real inbox for no benefit).
- **Known limitation, not solved**: MPro Forms doesn't hook into
  Polylang string translation, so the form's field labels stay in
  English even on the `fa`/`ar` Contact pages. Fixing this would mean
  changes to MPro Forms itself (the operator's own plugin, out of scope
  here) or building a second, separately-labelled form per language —
  not done, to avoid guessing at a design the operator hasn't asked
  for.

## 2026-09-05 (continued further) — homepage/header/footer visual redesign

The operator asked for a visual redesign matching the reference site
(`https://dralimoradi.moghadam.pro/`) exactly, after previously choosing
to defer it (see open-items.md item 9). Discovered that this same
repository (on `main`, and therefore in both worktrees) *is* the
reference site's own Next.js source — `app/site-page.tsx`,
`app/site-content.ts`, `app/globals.css` — so the redesign was built by
porting that real markup/CSS/copy directly rather than approximating it
from screenshots.

**What changed**, all in the theme's own code (no new plugin):

- `theme.json`'s color palette replaced with the reference's exact hex
  values (`--ink #102630`, `--blue #4293c2`, `--orange #f7941d`,
  `--blue-deep #062b3e`, etc.) — the previous palette was a placeholder
  guess, not a real match despite what open-items.md item 9 said.
- `assets/css/style.css` replaced with a ported version of the
  reference's `globals.css` (same class names on purpose, for future
  diffing against the source), plus a `.fill-img` utility class standing
  in for the `position:absolute;inset:0` that Next.js's `<Image fill>`
  injects inline (there is no Next.js here, so each such image gets the
  class explicitly instead).
- `assets/js/site.js` (new): vanilla-JS equivalents of the reference's
  React state — mobile-nav toggle, language-dropdown toggle, the
  appointment accordion, scroll-reveal via `IntersectionObserver`, and
  the interior-page cover's scroll-shrink effect.
- `inc/icons.php` (new): a small hand-drawn icon set in the same
  24x24 stroke style as the reference's `lucide-react` icons (check,
  chevron, arrow, calendar, phone, mail, map-pin, etc.) — not
  byte-identical SVG paths, since lucide's exact path data wasn't
  available to copy from here, but visually equivalent generic
  pictograms.
- `inc/homepage-content.php` (new): per-locale (en/fa/ar) copy for the
  header, footer, and every homepage section, transcribed verbatim from
  the reference's own `app/site-content.ts` — the same "static
  translated-copy object" approach the reference itself uses for
  marketing/UI strings, as opposed to Theme-Options-editable content.
  Also `dam_media_url( $slug )`, a small helper that looks up a Media
  Library attachment by slug and returns its URL (see below), and
  `dam_localized_page_url( $slug )` for linking to a page's translation
  by slug.
- `inc/nav-walker.php` (new): two `Walker_Nav_Menu` subclasses that
  print the Primary menu as bare `<a class="nav-link">` tags (desktop)
  or full-width rows with a trailing chevron (mobile) instead of
  `<ul><li>` — matching the reference's flat markup, which the existing
  `dr-ali-moradi/site-navigation` block's plain `wp_nav_menu()` call
  couldn't produce.
- Ten new dynamic blocks, each a single self-contained
  `render.php` (matching the existing pattern from `impact-stats` /
  `appointment-cta`): `site-header` and `site-footer` (replacing
  `parts/header.html` / `parts/footer.html`'s content wholesale — a
  tightly-structured section like the header needs to be one block, not
  several, so WordPress's per-block wrapper `<div>`s don't break the
  exact parent/child CSS), and eight `homepage-*` blocks (hero,
  connected-practice journey, pathways, innovation stories, impact
  numbers, appointments accordion, news/awards, about-preview) used
  directly in `templates/front-page.html` in place of the old generic
  placeholder markup (plain headings/paragraphs/columns).
- `homepage-news` queries the 4 latest real posts (not a hardcoded
  "awards" tag filter like the reference, since no such taxonomy exists
  on the imported posts yet) — a deliberate simplification, not a bug.
- `homepage-innovation` and `homepage-journey`'s "read more" links point
  to the `innovations` hub page generically rather than deep-linking to
  specific Innovation CPT posts per card, to avoid guessing at slug
  matches.
- Footer's "Explore" column links only to pages that actually exist
  (`clinical-care`, `innovations`, `research`, `about`, `blog` — not
  `education`, which still has no content per open-items.md item 7) —
  checked with `get_page_by_path()` at render time so the column
  self-updates if Education is ever added.
- Footer's social-media column now has real links (Instagram, Telegram,
  Aparat) found hardcoded in the reference's own `Footer()` component —
  this resolves open-items.md item 4's "no WhatsApp/social links
  anywhere" for everything except WhatsApp, which genuinely isn't in
  the reference either.

**Two real bugs found and fixed while deploying this:**

1. **Theme-upload endpoint has a much lower request-size ceiling than
   Media Library uploads, on this host.** Packaging the theme zip with
   its ~1.9MB of new decorative photography (hero background, the four
   connected-practice images, three innovation photos, the appointments
   portrait, the about-page office photo) made Appearance → Themes →
   Upload Theme fail with "The package could not be installed. The
   theme is missing the style.css stylesheet" — misleading, since the
   zip was verified byte-for-byte intact (`unzip -t`, `unzip -l`) with
   `style.css` correctly at `dr-ali-moradi/style.css`. Confirmed by
   testing: a code-only zip (no images) of the exact same theme
   installed without issue, and Media Library's own uploader reports a
   64MB ceiling and accepted every one of those same images
   individually without complaint. Conclusion: the failure is a
   silently-truncated upload (likely `post_max_size`, or a
   reverse-proxy/WAF body-size limit ahead of PHP) specific to the
   classic theme-zip-upload form, not a zip problem or a Media Library
   problem. **Fix**: decorative photography now uploads through Media
   Library (queried by attachment slug at render time via
   `dam_media_url()`) instead of shipping inside the theme zip; only
   the small brand-logo SVGs (WordPress core blocks SVG uploads by
   default) stay bundled in the theme. The full-resolution source files
   still live in the theme's own `assets/img/` in git as a durable
   backup/reference and as the `dam_media_url()` fallback value, they
   are just excluded from the deployed zip.
2. **Cloudflare kept serving a stale `style.css` after every theme
   update, because the cache-busting version never changed.**
   `DAM_THEME_VERSION` (used as the `?ver=` query string on the
   enqueued stylesheet) had stayed `'0.1.0'` since the very first
   deploy, so `style.css?ver=0.1.0` was byte-identical, from
   Cloudflare's point of view, before and after this whole redesign —
   it kept serving the old ~600-byte file from the very start of this
   project. Confirmed by comparing `document.styleSheets[...].cssRules
   .length` (9 rules — matching the old file) against a same-origin
   `fetch()` of the same URL (which returned the new, correct, several
   -hundred-rule content instead) with the same URL and browser cache.
   **Fix**: bump `DAM_THEME_VERSION` on every deploy that touches CSS
   or JS; this constant genuinely needs to change per release, not just
   exist.

## 2026-09-05 (yet again) — About, Contact, Research, and Innovation redesigned

Continued the reference-matching redesign onto the interior pages,
after the operator asked to keep going. Same method as the homepage:
read the exact markup/CSS/copy from the reference site's own source
(this repo's `main`/`app/*`) and port it, rather than approximating.

New reusable pieces:
- `blocks/interior-cover` — the photo cover (kicker/title/intro) at the
  top of every interior page. Resolves which page's copy to show via
  `dam_current_page_key()` (strips the importer's `-fa`/`-ar` slug
  suffix), so the same block works for all three languages of a page
  without per-language wiring, and special-cases `about` and `contact`
  to pull from their own dedicated copy files instead of the generic
  interior-page fallback entry of the same key.
- `blocks/interior-body` — the numbered content-sections + sticky
  sidebar table of contents + closing "next step" card, used on
  Research and Innovation. Copy transcribed verbatim from the
  reference's `app/site-content.ts` `pages` object into the new
  `inc/interior-content.php`. Research's next-step card links to the
  real Google Scholar profile; everything else links to an internal
  page.
- `blocks/page-appointment-cta` — the closing booking CTA + medical-
  emergency notice shared by every interior page.
- `inc/team.php`'s `dam_render_team_section( $area )` (a plain
  function, not a block, since it's called inline from
  `interior-body`) — queries the real `team_member` CPT posts by the
  existing `team_area` taxonomy, matching the reference's
  `<TeamSection area="..."/>` reuse on Research/Innovation. No team
  content was invented; this only queries what the content-migration
  import already created (confirmed live: Research shows Dr. Moradi,
  Dr. Jahani, Maedeh Sharafoddin, and Dr. Kalali with their real photos
  and bios, matching the reference exactly).

**About page** got its own dedicated template (`page-about`, added to
`theme.json`) and five dedicated blocks (`about-story`, `about-practice`,
`about-journey`, `about-ecosystem`, `about-recognition`), since its
real design — five distinct full-width sections (portrait + story,
dark "connected practice" with three principle cards, a sticky training
timeline, a three-card "fields of work" grid, and a closing recognition
banner) — doesn't fit the generic interior-body pattern at all. Copy
transcribed verbatim from `app/about-content.ts` into the new
`inc/about-content.php`. The "fields of work" grid only shows cards for
pages that actually exist (Research, Innovation) and leaves out
Education, the same existence-check pattern already used for the
footer's Explore column.

**Contact page** got a `contact-details` block for its left column
(kicker, "before you write" notice, Nobat button, office address,
email, phone), matching the reference's two-column `contact-layout`.
The page's own stored content used to duplicate this copy (from the
original `importContactPage()`, which wrote intro/office/clinic/
beforeTitle/beforeText paragraphs *and* the MPro Forms shortcode into
one page); since `contact-details` now renders that copy itself, the
three live Contact pages were patched directly (via an authenticated
`fetch()` from the page's own wp-admin editor tab, using the
`wpApiSettings.nonce` already loaded there — no Application Password
needed for this one-off) to hold just the shortcode, and
`importContactPage()` was updated the same way so a future re-import
doesn't reintroduce the duplication. MPro Forms renders its own markup
the theme doesn't control, so rather than trying to restyle its
internal fields, the wrapping element around it just gets the same
card treatment as `.contact-form` via a new `.contact-form-shell`
class.

**Two content-model corrections found and fixed while wiring this up:**
- `pathSlugs`/footer/journey/innovation-story links all used the
  reference's own slug `innovation` (singular) for the Innovation hub,
  but this site's actual imported page slug is `innovations` (plural)
  — confirmed via `/wp-json/wp/v2/pages`. Fixed every reference to the
  correct plural slug; this also fixed the footer's "Explore" column,
  which was silently one link short (4 of 5) before the fix because the
  existence check (`get_page_by_path('innovation')`) was quietly
  failing.
- `about`'s *generic* interior-page fallback entry in
  `inc/interior-content.php` (title: "A surgeon, researcher, inventor,
  and educator.") is intentionally different text from the *real*
  About page's own copy in `inc/about-content.php` (title: "A surgeon
  shaped by curiosity, evidence, and making.") — these are two
  different objects in the reference source for two different
  purposes (a rarely-reached fallback vs. the real page), and
  `interior-cover` needs to read the right one per page or the About
  page's cover shows the wrong title.

**Verified live** on `tmp.saveon.me` (English): About (all five
sections, real office/doctor photos, correct Research/Innovation-only
ecosystem cards), Contact (cover, contact-details column, MPro form in
a proper card), Research (sidebar TOC, giant background numbers,
Scholar CTA, real team grid), and Innovation (same pattern, correct
copy) all match the reference's layout and content closely.

**Not done yet, and why:**
- **Clinical Care** currently falls back to the generic `interior-body`
  (numbered sections) instead of its own dedicated `ClinicPage` design
  (pathway cards to Clinic-services/Hospital-services + a team section
  + before/after surgical-case galleries). That real design needs
  content that doesn't exist in this WordPress site yet: the
  `clinical-care/clinic-services` and `clinical-care/hospital-services`
  sub-pages, and ~32 real gallery images (`app/structured-content.ts`'s
  `galleryCollections`) that were never imported. Not fabricated;
  flagged instead.
- Blog archive/single-post pages, team-member profile pages, and the
  clinic/hospital surgical-case gallery pages still use the old
  placeholder templates from before this redesign.
- Arabic has not been separately re-checked on any of these interior
  pages (Persian and English were spot-checked during the homepage
  pass; today's interior-page verification was English only).

## 2026-09-05 (still going) — five real homepage bugs the operator caught

The operator reviewed the homepage again and reported five concrete,
reproducible bugs, correctly pushing back on the earlier "verified
live" claim — the spot-checks that preceded it weren't thorough
enough. Investigated each with the browser's devtools (computed styles,
matched CSS rules, before/after screenshots against the reference at
matching viewport sizes) rather than guessing, and found genuine CSS
defects, not content or design differences:

1. **Extra margin-top above every section.** WordPress core injects
   `:where(.wp-site-blocks) > * { margin-block-start: 24px }` between
   every top-level block in a template; the reference has no such
   gap (its sections get all their visible spacing from their own
   `.section-space`/`.section-shell` padding). Overridden with
   `.wp-site-blocks > * { margin-block-start: 0; }` — a real selector
   (non-`:where()`) beats WordPress's zero-specificity default
   regardless of stylesheet order.
2. **Part of the hero (facet bar, credential checklist) clipped and
   hidden under the next section.** Root cause: `* { box-sizing:
   border-box; }` — the very first rule in the reference's own
   `globals.css` — was silently dropped when this file was first
   ported. Without it, `.hero-layout`'s `height: 100%` plus its own
   `padding-top`/`padding-bottom` (56px + 26px) rendered as `860px +
   82px = 942px` instead of `860px` total, and the 82px of overflow got
   clipped by `.hero { overflow: hidden }` — confirmed by comparing
   `.hero-layout`'s `scrollHeight` against `.hero`'s own height on both
   sites at an identical viewport size.
3. **Header not sticky.** `position: sticky` was set on the *inner*
   `.site-header` block, but a sticky element can't stick past the
   bottom edge of its own containing block — and the *outer*
   `<header class="wp-block-template-part">` that `wp:template-part`
   auto-generates around it is only ~1px taller than the header
   itself, leaving essentially no room to actually stick (confirmed by
   watching `getBoundingClientRect().top` move in exact lockstep with
   `scrollY` while scrolling, instead of clamping at 0). Moved
   `position: sticky` to that outer wrapper instead — a direct child
   of `.wp-site-blocks`, which spans the full page height — scoped
   with `:has(.site-header)` so the footer's own auto-generated
   wrapper isn't affected.
4. **Appointment CTA button wider than its card, cut off on the
   trailing edge.** Same root cause as #2: `.appointment-panel .button
   { width: 100% }` without `box-sizing: border-box` added the
   button's own horizontal padding and border on top of 100% instead
   of inside it.
5. **Footer had one empty column.** `.footer-grid`'s
   `grid-template-columns` has 5 tracks (brand, Explore, Patient
   resources, Contact, Social) copied from the reference, but only 4
   divs are ever rendered — Patient resources has no source content
   yet (see open-items.md) and was correctly left out — so the 5th
   track sat empty and the Contact/Social columns silently landed in
   the wrong tracks via grid auto-placement. Added a
   `.footer-grid--no-resources` modifier (applied in
   `site-footer/render.php`) with a matching 4-track template instead
   of the reference's 5-track one, for the desktop layout and both
   `820px`/`1120px` responsive breakpoints.

While fixing these, also re-audited the *entire* file against the
reference's original `app/globals.css` per the operator's request and
found several other base resets the first port had silently dropped —
`html`/`body`/`main`'s `overflow-x`/sizing rules and
`button, input, select, textarea { font: inherit }` — added them back
even though no specific visible symptom had been reported for them yet.

**Verified live** on `tmp.saveon.me`, English and Persian: hero fully
visible with no clipping, header now stays fixed while scrolling, the
appointment button fits its card exactly, the footer's four columns
are evenly sized with no gap, and no section has a stray gap above it.

**Verified live** on `tmp.saveon.me` in English and Persian: hero
(background photo, orbits, credentials, quote card, facet bar),
connected-practice journey strip, pathways cards, innovation cards,
impact numbers, appointment accordion (all four items, first one
open by default, real Nobat.ir link), news grid (showing real post
thumbnails), about-preview, and the full footer — all match the
reference's layout, spacing, and color treatment closely, including
correct RTL mirroring in Persian (logo/nav/language-switcher swap
sides, orbit/wash gradients flip, credential dots/icons mirror). Did
not yet re-check Arabic specifically, and did not yet touch any
interior page template (`page-hub`, `page-contact`, `page.html`,
`archive.html`, `single.html`, team-profile/gallery templates) — those
still use the old placeholder block markup and colors from before this
session, so there will be a visible seam between the homepage and any
interior page until those get the same treatment.

## 2026-09-05 (continued once more) — real icons, another cache bug, operator feedback

The operator reviewed the redesign and gave three pieces of feedback:
don't guess/recreate anything that already exists in the git repo or
the live reference site (icons, SVGs, styles included); the English
header logo wasn't loading; and to re-check for CSS drift against the
live site. Addressed all three:

- **`inc/icons.php`'s icons were hand-drawn approximations, not the
  real thing.** The first pass wrote plausible-looking Lucide-style
  SVGs from memory rather than the actual path data (`lucide-react`
  isn't installed in this checkout — no `node_modules` — so there was
  no local copy to read). Fixed by opening
  `https://dralimoradi.moghadam.pro/` itself in a browser and reading
  each icon's real path data straight out of the rendered DOM
  (`document.querySelectorAll('svg.lucide')`, grouped by its
  `lucide-<name>` class) across the homepage, the appointments
  accordion's third (initially-collapsed) item, and an interior page
  for `Sparkles` — 21 icons total, all now byte-identical to the
  reference's own rendering instead of lookalikes.
- **Footer social icons: the live site has moved ahead of this repo's
  `main` for this one detail.** The reference's own `app/site-page.tsx`
  in this repo still renders Instagram/Telegram/Aparat as generic
  lucide `Camera`/`Send`/`PlayCircle` icons, but the *live* site
  currently renders real branded icon files from `/icons/social/*.svg`
  with a specific CSS `filter:` recolor rule. Since the operator's
  instruction is to pull from whichever of the two sources (repo or
  live site) actually has the asset, downloaded those three SVGs
  directly and copied the exact filter rule from the live site's
  computed CSS rather than approximating a tint.
- **Real bug: the English logo 404'd once, and this host caches static
  files for 10 years.** `assets/img/brand/logo.en.svg` returned 404 on
  first load because an earlier zip deploy didn't yet include
  `assets/img/brand/` (it was added in a later commit this same day).
  The theme's static files are served with `Cache-Control:
  max-age=315360000` (10 years) — confirmed via `fetch()` with
  `cf-cache-status`/`cache-control` headers — so a browser that ever
  hit that 404 keeps it forever, even after the file starts deploying
  successfully; a plain `fetch()` bypassing cache proved the file was
  fine on the server the whole time. Same root cause as the
  `DAM_THEME_VERSION` stylesheet-caching bug from the previous entry,
  just on an `<img src>` instead of an enqueued asset, which don't get
  WordPress's own `?ver=` treatment automatically. Fixed with a new
  `dam_theme_asset_url()` helper (append `?ver=DAM_THEME_VERSION`,
  same pattern as the enqueue calls) applied to both header and footer
  logo `<img>` tags.
- **CSS re-check**: spot-compared several sections pixel-by-pixel
  against the live site (facet bar, appointments portrait background,
  footer layout) and found the ported CSS accurate except for the
  footer-social icon filter noted above.
- **"English should load by default" — investigated, could not
  reproduce.** Checked Polylang's settings (English has the default-
  language star, "Detect browser language" is deactivated) and tested
  both a fresh visit to `/` and a visit to `/fa/` followed by a visit
  to `/` (to check for Polylang's cookie-remembered-language behavior)
  — both correctly served English (`<html lang="en-US">`, English
  title). Whatever the operator saw may have been from before this
  session's `dam_translate_front_page_option` fix (2026-09-05, earlier
  entry) or a since-cleared browser/cookie state; flagged rather than
  silently assumed-fixed, since it couldn't be reproduced to confirm
  the fix actually addressed it.

## 2026-09-05 (yet again) — Clinical Care hub, blog, and team-profile pages completed

Closes open-items.md item 9's last remaining piece. The operator asked
to continue reviewing pages, focus this phase on the blog and
team-member profiles (real content already exists for both), and fully
complete the Clinical Care hub. Same method as every prior redesign
phase: port the reference's own source (`app/site-page.tsx`) rather
than approximate it.

**New blocks/templates:**
- `blocks/clinic-pathways`, `inc/clinic-content.php` — the hub's two
  pathway cards (Clinic services / Hospital services) plus
  `dam_render_clinical_care_body()`, which replaces the generic
  `interior-body` numbered-sections layout for the Clinical Care page
  specifically with the reference's own dedicated design.
- `blocks/blog-archive`, `blocks/single-post-body`, `inc/blog-content.php`
  — a real WP-post-backed archive grid and single-post layout (Overview
  / Assessment / Next steps sections built from each post's actual
  content, not invented), replacing the old generic placeholder.
- `blocks/team-profile` — a team member's own page (photo, role,
  summary, real post-content bio, a "draft background" disclosure
  matching the reference's own provisional-CV notice, and a "back to
  the team" link resolved per member via the `team_area` taxonomy).
- `blocks/gallery-full`, `inc/gallery.php`, `templates/page-gallery.html`
  — the two dedicated "view full gallery" pages (16-image grid + shared
  lightbox modal) and the rotating 4-thumbnail preview strip used on
  the hub itself.
- Uploaded the 10 missing clinic gallery images
  (`clinic-07.jpg`–`clinic-16.jpg` — only `clinic-01`–`06` had been
  uploaded in an earlier session) so all 16 clinic images match the 16
  hospital images already present.
- Created the 8 real WordPress pages this content needed (en/fa/ar,
  Polylang-linked, same `ensureTranslatedEntry`/`localizedSlug` pattern
  as every other page): `clinic-services`, `hospital-services`,
  `clinic-gallery`, `hospital-gallery` (`page-hub`/`page-gallery`
  templates), and the four patient-resources pages `before-surgery`,
  `after-surgery`, `faq`, `rehabilitation` (`page-hub` — their copy was
  already written in `dam_clinic_subpages_copy()` from an earlier
  session but the pages themselves never existed).

**Four real bugs found and fixed while verifying all of this live**
(not visible from reading the code — each confirmed with the browser's
devtools before fixing):
1. **Gallery lightbox modal stayed open on every page load.**
   `.gallery-modal { display: grid }` unconditionally overrides the
   browser's own `[hidden] { display: none }` default — author
   stylesheet rules always beat user-agent rules regardless of
   selector specificity or source order. The element's `hidden`
   attribute was actually working correctly (`site.js`'s open/close
   logic was never the problem); the CSS just ignored it. Fixed with
   an explicit `.gallery-modal[hidden] { display: none; }` rule.
2. **The hub's team section silently rendered nothing.** The
   taxonomy lookup queried `team_area` by the slug "clinic" (matching
   the `team-clinic` CSS class name convention used elsewhere), but the
   real imported term slug is "clinical-care" — a mismatch, not a
   missing-content problem (Polylang was already correctly resolving
   the query to the current language's own term; confirmed by checking
   the Persian hub page separately). Fixed in
   `dam_render_team_section()` by mapping the CSS-class value to the
   real term slug only for the taxonomy query, keeping the
   `team-clinic` class name intact; `dam_team_member_back_slug()` had
   the identical bug and got the same fix.
3. **`/blog/` never rendered the new `blog-archive` block.** The
   `home` FSE template had a stale customization saved in the database
   from an earlier point in the project — WordPress's own default
   Query Loop markup, `source: "custom"` in `/wp/v2/templates` — which
   permanently shadows the theme's own `templates/home.html` file
   until reverted, regardless of how many times the theme zip is
   redeployed. Found by comparing the rendered page's HTML (plain
   `post-featured-image`/`post-title`/`post-excerpt` core-block markup,
   no `blog-grid` class anywhere) against `home.html`'s actual content.
   Fixed with a `DELETE /wp/v2/templates/dr-ali-moradi//home`, which
   WordPress treats as "revert to theme file" rather than an actual
   delete (confirmed `source` flipped back to `"theme"` afterward). All
   other templates were checked the same way and were not affected —
   this was an isolated, one-time stray edit, not a systemic issue.
4. **Every single blog post page rendered at ~60% of full width.**
   `.single-post { max-width: 1120px; }` was written to size the
   article's own wrapper `<article class="single-post ...">`, but
   WordPress's `body_class()` also emits the literal string
   `single-post` on every single post of post type `post` — so the
   same selector matched `<body>` itself, capping the entire page
   (header included) at 1120px instead of just the article column.
   Confirmed via `getComputedStyle(document.body).width` returning the
   exact `"1120px"` on a post page vs. the real viewport width
   elsewhere. Fixed by renaming the wrapper's own class to
   `single-post-article` (and the CSS selector to match) — a
   collision worth remembering when naming any future block-wrapper
   class against WordPress's own `body_class()` output (`home`,
   `blog`, `archive`, `search`, `single`, `page`, `error404`, etc. are
   all reserved this way).

**One design gap found and closed, not a bug:** the footer's copy
already had `Before surgery` / `After surgery` / `Frequently asked
questions` / `Rehabilitation guidance` labels and a `Patient resources`
column heading sitting unused since the homepage/footer redesign
(`inc/homepage-content.php`'s `footer.before`/`.after`/`.faq`/`.rehab`),
and the reference's own `app/site-page.tsx` confirms a fifth
"Patient resources" footer column linking to exactly those four pages
— it was disabled (`footer-grid--no-resources`, a 4-column layout) only
because those pages didn't exist yet. Now that they do,
`blocks/site-footer/render.php` renders the column conditionally (same
`get_page_by_path()` existence-check pattern as the Explore column) and
falls back to the 4-column layout automatically if any of the four
pages is ever removed.

**Verified live** on `tmp.saveon.me`: Clinical Care hub in English and
Persian (pathway cards, team grid with correct per-locale members,
both galleries with working rotation/lightbox/next-prev, "view full
gallery" → real 16-image grid pages), a clinic-services sub-page, the
blog archive and a single post (English), a team-profile page and its
"back to the team" link, and the restored 5-column footer in both
English and Persian (RTL-mirrored).

**Not done in this phase:**
- Arabic was not separately re-checked on any of today's new pages
  (English and Persian were spot-checked).
- The generic interior-page fallback copy for `clinical-care` in
  `inc/interior-content.php` is now fully unused (the real hub page
  always takes the `dam_render_clinical_care_body()` branch) but was
  left in place rather than removed, since removing it isn't needed for
  correctness and wasn't asked for.
