# WordPress Theme — Source

This folder holds the actual code: the theme and the content-migration
scripts. Full documentation (architecture, decision log, migration plan,
open items, progress log) lives on the `docs` branch, under
`wordpress-theme/`, not here — see
https://github.com/moghadam-pro/dr.ali.moradi-webiste/blob/docs/wordpress-theme/README.md

## Layout

- `dr-ali-moradi/` — the WordPress block theme.
  - `style.css`, `theme.json` — theme header, Global Styles, custom
    templates/template-parts declarations.
  - `templates/` — block templates (front-page, page, page-hub,
    page-contact, page-full-width, single, archive, single-team_member,
    single-condition, single-innovation, search, 404, index).
  - `parts/` — header and footer template parts.
  - `blocks/` — the theme's own dynamic Gutenberg blocks (contact-info,
    impact-stats, appointment-cta, site-navigation).
  - `inc/` — post types, taxonomies, native custom-field registration,
    Theme Options admin page, block registration, Polylang string
    registration.
  - `assets/` — CSS, JS (the custom-fields editor panel), and the
    self-hosted Inter/Vazirmatn/Scheherazade New font files (vendored from
    this repo's own `@fontsource*` packages, matching the fonts the
    current React site already uses).
- `content-migration/` — the extraction script (works now) and the import
  script (scaffolded, blocked on WordPress site credentials). See its own
  `README.md`.

## Status

Scaffolding is complete enough to install the theme and see real pages
render; visual/CSS polish to match the approved design, and the actual
deployment + content import to `tmp.saveon.me`, are the next steps — see
`progress-log.md` on the `docs` branch for the up-to-date state.
