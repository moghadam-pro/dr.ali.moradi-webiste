# WordPress Theme — Source

This folder holds the production theme and content-migration scripts. The
documentation is maintained in the same repository under `docs/wordpress-theme/`:
[documentation index](../docs/wordpress-theme/README.md),
[current content and verification](../docs/wordpress-theme/content-completion.md),
and [deployment log](../docs/wordpress-theme/progress-log.md).
For work on another computer, begin with the
[current Persian handoff](../docs/wordpress-theme/handoff-2026-10-06-fa.md).

## Layout

- `dr-ali-moradi/` — the WordPress block theme.
  - `style.css`, `CHANGELOG.md`, `theme.json` — canonical SemVer header,
    release history, Global Styles, and custom
    templates/template-parts declarations.
  - `templates/` — block templates (front-page, page, page-hub,
    page-contact, page-full-width, single, archive, single-team_member,
    single-condition, single-innovation, search, 404, index).
  - `parts/` — header and footer template parts.
  - `blocks/` — the theme's own dynamic Gutenberg blocks (contact-info,
    impact-stats, appointment-cta, site-navigation).
  - `inc/` — the Team Member post type, Post categories, versioned content
    migrations, native custom-field registration,
    Theme Options admin page, block registration, Polylang string
    registration.
  - `assets/` — CSS, JS (the custom-fields editor panel), and the
    self-hosted Inter/Vazirmatn/Scheherazade New font files (vendored from
    this repo's own `@fontsource*` packages, matching the fonts the
    current React site already uses).
- `content-migration/` — extraction/import scripts and reviewed HTML snapshots
  for the 42 multilingual Innovation Pages and 27 expanded Posts.

## Content model

- Posts hold articles, conditions, innovations, publications, and patient
  resources; categories distinguish those families.
- `team_member` and `patient` are the public catalogues retained by the theme.
- Version 1.0.0 migrates the four retired custom post types without changing
  record IDs and preserves their old URLs with permanent redirects.

See `docs/wordpress-theme/progress-log.md` for deployment state and
`docs/wordpress-theme/versioning.md` for the release policy.

## Production state (3.5.0; 3.5.1 pending)

Theme 3.5.0 is installed. Version 3.5.1 and its tag are in GitHub; production
upload and the GUID repair replay remain pending. The handoff records the
browser permission failure and the exact steps and private inputs for resuming.

See [operator workflow and import](../docs/wordpress-theme/patients.md). Patients
include hierarchical categories, tags and ordered multimedia. Clinical Care and
its existing full-gallery pages query the mandatory Clinic/Hospital category.
The production snapshot was synchronized before the releases. All 71 Patients
and 314 case media are registered; the current mandatory category split is
Clinic 4 / Hospital 67. The patient list keeps the Persian RTL admin layout.

The fourteen Innovation projects have 42 ordinary, editable EN/FA/AR Pages.
Their scoped layout, verified public URLs and reviewed Page bodies are described
in [content completion](../docs/wordpress-theme/content-completion.md).
Nine generated educational covers and authored sections complete the 27 Posts
that lacked featured images. The final WordPress export found zero empty bodies
and zero Posts without featured images among 97 Posts. The operator can edit
all of these through the normal WordPress Page/Post editors.
