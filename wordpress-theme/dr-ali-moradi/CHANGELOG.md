# Changelog

All notable changes to the Dr. Ali Moradi WordPress theme are recorded here.
This project follows [Semantic Versioning](https://semver.org/) and this file
follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

## [1.0.0] - 2026-09-22

### Added

- A versioned, idempotent content-schema migration for legacy `condition`,
  `innovation`, `publication`, and `patient_resource` records.
- Localized Post categories for Clinical Conditions, Innovation,
  Publications, and Patient Resources in English, Persian, and Arabic.
- Permanent redirects from the retired custom-post-type URLs to the migrated
  Post permalinks.
- A migration report stored in the `dam_content_migration_report` option for
  post-deployment verification.

### Changed

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
