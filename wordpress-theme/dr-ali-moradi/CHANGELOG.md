# Changelog

All notable changes to the Dr. Ali Moradi WordPress theme are recorded here.
This project follows [Semantic Versioning](https://semver.org/) and this file
follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

## [1.2.0] - 2026-09-23

### Added

- A least-privilege `Content Manager — مدیر محتوا` role for the clinic
  operator, with access to Posts, categories, media, Team Members, and the
  multilingual Homepage Content editor.
- A dedicated Homepage Content admin-menu entry that opens the live Customizer
  workflow without exposing the broader Appearance screens.
- A versioned role schema that creates or repairs the role once and preserves
  administrator access to the Homepage Content panel.

### Security

- Homepage Customizer settings now require the dedicated
  `dam_edit_homepage_content` capability instead of `edit_theme_options`.
- Content Managers can enter the Customizer but cannot manage users, plugins,
  themes, site options, or the full Site Editor.

## [1.1.0] - 2026-09-22

### Added

- One Homepage Content Customizer panel containing English, Persian, and
  Arabic sections; opening a language section switches the live preview to
  that language's real homepage.
- Hero background-image selection and a switch for the circular orbit artwork.
- Individual Connected Practice stage text and image controls plus an editable
  full-journey link.
- Individual title, body, button label, and button URL controls for all three
  Pathways cards.
- Dynamic-by-category and manual-post modes, card count, and grid column
  controls for Innovation and Recognition.
- Full Impact controls for the section heading and all four value/label pairs.
- An appointment image control and separate enable, eyebrow, title, and body
  controls for each of the four appointment options.
- About-preview image and button URL controls.
- Detailed footer controls for branding, booking, navigation, resources,
  contact information, addresses, map, social links, legal copy, and credit.

### Changed

- Legacy delimiter-based Connected Practice, Pathways, and Appointments values
  remain readable as defaults while new edits use independent fields.
- Homepage card grids now support one, two, or three configured columns while
  retaining their responsive single-column layout on small screens.

## [1.0.0] - 2026-09-22

### Added

- The production-only SEO, breadcrumb, generic archive, Customizer,
  multilingual-editor, slug-redirect, translation, and team-link features
  recovered from the active `0.2.1` theme snapshot.
- The Abar variable font, Persian and Arabic translation catalogs, and the
  production theme screenshot.
- A versioned, idempotent content-schema migration for legacy `condition`,
  `innovation`, `publication`, and `patient_resource` records.
- Localized Post categories for Clinical Conditions, Innovation,
  Publications, and Patient Resources in English, Persian, and Arabic.
- Permanent redirects from the retired custom-post-type URLs to the migrated
  Post permalinks.
- A migration report stored in the `dam_content_migration_report` option for
  post-deployment verification.

### Changed

- Production rendering improvements now preserve real post content and
  excerpts, paginated archives, localized breadcrumbs, richer post metadata,
  team related links, and locale-aware team navigation.
- Bundled CSS and JavaScript use per-file modification times for cache
  invalidation while the `style.css` header remains the release-version source.
- Conditions, innovations, publications, and patient resources now use native
  WordPress Posts and categories. Team Members remains the only public custom
  post type owned by the theme.
- The WordPress `style.css` header is now the single source of the release
  version. Asset cache keys read the same value through `wp_get_theme()`.
- Theme metadata now includes the author URI, language-domain path, custom
  update URI, and the tested WordPress version.

### Removed

- The four retired content-type menus and the obsolete Condition Category and
  Publication Type taxonomy menus.

### Migration notes

- Existing records keep their IDs, authors, dates, statuses, content,
  excerpts, featured media, comments, metadata, and Polylang relationships.
- The migration runs on the first authenticated administrator request after
  deployment. It creates categories even when a retired content type is empty.
- On the production inventory captured before this release, the expected move
  is 18 Conditions and 9 Innovations; Publications and Patient Resources are
  empty.

## [0.2.1] - 2026-09-17

### Notes

- Production baseline observed on `dralimoradi.com` before the 1.0.0 work.
- This release was installed on the site but its complete source was not
  present in the Git repository; the production snapshot must be reconciled
  before deploying 1.0.0.
