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
