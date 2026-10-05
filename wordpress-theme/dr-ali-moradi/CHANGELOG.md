# Changelog

All notable changes to the Dr. Ali Moradi WordPress theme are recorded here.
This project follows [Semantic Versioning](https://semver.org/) and this file
follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

## [3.1.1] - 2026-10-06

### Fixed

- Replaced generated page kicker descriptions with authored introductions; filled
  empty multilingual blog descriptions while preserving explicit SEO overrides.
- Removed exact post/Page URL duplicates from XML sitemaps.
- Added translated names to patient tags as well as categories.


- Patient gallery pagination follows the visitor's language even when Persian
  plugins localize WordPress numbers; route/query digits remain intact.
- Shared patient views preserve language across case/category links and the
  header switcher. Care-category labels and navigation follow that language.
- Added editable EN/FA/AR case title/excerpt/body and category-name translations,
  with original-content fallback and one shared media library.
- Patient titles/descriptions/canonical metadata follow translated content;
  existing custom Rank Math metadata remains respected. Gallery images are
  included through Rank Math's sitemap image hook.
- Added checksum-based reuse of existing Media Library originals, local staged
  media import outside the web root, per-case media ordering and a private ZIP
  packaging helper. Source IDs still make retries resumable.

Production deployment and complete import verification are recorded separately
in the progress log; this source version alone is not proof of deployment.

## [3.1.0] - 2026-10-05

### Added

- Patients post type with Persian/Arabic admin labels, hierarchical categories,
  tags, excerpt, treatment content, and ordered image/video/video-link galleries.
- Exactly one protected Clinic/Hospital care category per patient; Hospital is
  the owner-approved import default. REST rejects missing/conflicting choices;
  classic/quick/bulk edits retain a valid previous setting.
- Patient pages, category/tag/catalogue archives, descendant case galleries,
  category cards using the first descendant image and a 60% color overlay,
  expandable category-to-patient sidebar, and pagination.
- Administrator-only resumable manifest importer, with nonce/capability checks,
  source-ID deduplication and restricted authenticated media download hosts.

### Changed

- Clinical Care preview strips and existing clinic/hospital gallery page URLs
  read published Patients rather than fixed Media Library slugs.
- Lightbox preserves media aspect ratio, includes title, description and patient
  link, supports videos, restores keyboard focus and traps focus while open.
- Synchronized production 3.0.0 before implementing this feature.

### Operator notes

- Case records/categories are shared across languages; gallery UI follows the
  viewing page language. Operators can edit imported names and category nesting.
- Patient title is the name/title; excerpt is the short description; editor
  holds the case/treatment details. Gallery entries have individual captions.
- Source clinical text is imported only where supplied; missing details remain
  empty. Source manifests and signed download URLs must remain outside Git.

## [3.0.0] - 2026-09-29

Major release under the versioning policy: it retires an operator-facing role
and menu and runs a cleanup migration on upgrade.

### Added

- Team Members now have the same **Persian & Arabic translation** box as
  Posts. Filling it in on the English member creates or updates the Persian
  and Arabic members, links the three as Polylang translations, and carries
  over the title, slug, biography, role, short summary, excerpt, and related
  links (one `Title|URL` per line).
- When a team member is saved in English, each translation also receives the
  matching translated Team Area, the same order, and the same photo when the
  translation has none of its own.

### Removed

- The **Homepage Content** admin-menu entry.
- The **Content Manager — مدیر محتوا** role that appeared when adding a user,
  its dedicated `dam_edit_homepage_content` capability, and the Customizer
  capability mapping (`inc/roles.php`). Homepage Customizer panels use the
  standard Customizer capability again.

### Migration

- `inc/role-cleanup.php` removes the role and takes the capability away from
  Administrators on the first administrator request. If any user still holds the
  role it is left in place and the removal is retried on later requests, so no
  account is ever left without a role.

## [2.0.0] - 2026-09-29

This is a major release: the source of truth for the designed pages moves from
theme files into WordPress Pages, and the Customizer/footer operator workflow
changes. A versioned migration (below) performs the move on the first
administrator request after the upgrade.

### Added

- Page content now lives in WordPress. About, Clinical Care, Innovation,
  Research, Clinic services, Hospital services, Before surgery, After surgery,
  FAQ, Rehabilitation, Contact, and both galleries are stored in each page's
  own content, separately for English, Persian, and Arabic. Designed sections
  are Custom HTML blocks that reuse the existing markup and classes, so the
  look is unchanged, and the operator edits them under Pages.
- The `dr-ali-moradi/page-section` block keeps query-driven parts live inside
  page content: team grids, clinic/hospital gallery strips, full galleries,
  and the latest innovation posts.
- The `Designed Page` template (`page-designed`) used by those pages.
- A versioned, repeat-safe page-content migration (`dam_page_content_version`,
  report in `dam_page_content_report`). It never re-seeds a page it has already
  seeded and stores each page's previous content in the
  `_dam_pre_designed_content` post meta.
- Footer link columns use WordPress menus: `Footer — Quick access` (the
  existing `footer` location) and the new `Footer — Patient resources`
  (`footer_resources`), assigned per language through Polylang. The migration
  creates and assigns a menu for every language that has none, from the links
  the footer used to hard-code.

### Changed

- The homepage Customizer is now one panel per language (English, Persian,
  Arabic), each containing one section per homepage block, listed in the same
  order as the homepage: Hero, Connected Practice, Pathways, Innovation, Impact,
  Appointments, Recognition, About preview, Footer. Opening a panel switches the
  live preview to that language. Saved values are unchanged (the setting IDs did
  not change).
- Recognition is now listed after Appointments in the Customizer, matching the
  order it appears on the homepage.

### Removed

- The footer bottom bar (copyright, medical disclaimer, credit) is no longer
  editable in the Customizer; it renders the fixed localized text.
- The hard-coded Explore and Resources link settings in the Customizer, replaced
  by the footer menus above.

### Fixed

- The Customizer controls pane now uses the same Persian admin font as the rest
  of wp-admin when WP-Parsidate's font option is enabled. The plugin only
  enqueues its Vazir stylesheet on `admin_enqueue_scripts`, which WordPress does
  not fire on the Customizer screen, so the pane fell back to the system font.

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
