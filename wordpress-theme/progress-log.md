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
- **Not yet done** (unchanged from before, still open):
  - Menus (Appearance → Menus) — not created. The content available so
    far (home, About, posts, team) doesn't yet include the Clinical
    Care / Research / Innovation / Education hub pages
    `content-migration-plan.md` calls for, so a real primary/footer menu
    per language is better built once those exist rather than rebuilt
    twice.
  - The interior hub pages themselves (Clinical Care, Research,
    Innovation, Education, Contact) and the `condition`/`innovation`/
    `publication`/`patient_resource` CPT content — `conditions-seed.json`
    / `innovations-seed.json` exist (starter/teaser copy only, per that
    file's own comment) but have not been imported; they need real
    patient-facing copy, not a mechanical dump, per `open-items.md`.
  - Full page-by-page, language-by-language parity check against
    `https://dralimoradi.moghadam.pro/` — only spot-checked so far (team
    member and post detail pages, both confirmed correct); the homepage,
    About page, and every other page/language combination still need a
    deliberate side-by-side pass, with findings logged here.
  - A visual/CSS polish pass — the homepage and the pages checked above
    already render with the theme's real styling (not unstyled HTML), but
    no dedicated comparison against the approved look has been done yet.
