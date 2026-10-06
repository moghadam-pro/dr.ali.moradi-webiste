# Changelog

## 2026-10-06 — Patient GUID privacy / theme 3.5.1

- Removed legacy Patient and attachment GUIDs left in WordPress after the first
  privacy pass. The guarded manifest can be replayed to repair existing records
  without renaming files a second time.

## 2026-10-06 — Patient name privacy / theme 3.5.0

- Abbreviated identifiable patient names to initials and assigned neutral case
  slugs. Matching full names were removed from case text and gallery captions.
- Renamed patient-owned media whose filenames contained Persian text so public
  image URLs no longer expose those names. The private export and operation
  manifest remain outside Git.
- Added guarded, repeatable privacy operations to the existing Patient import
  tool and tests for title-hash protection, anonymized URLs and media metadata.

## 2026-10-06 — Curated Innovation and sensitive case media / theme 3.4.0

- Repaired the English Innovation Page's missing card container, sidebar and
  numbering after items 1 and 11 were swapped. Applied the same order to the
  Persian and Arabic Pages, preserving their editable Page content.
- Replaced the homepage's latest-Innovation-Posts query with three fixed cards
  in each language. Their images, labels, titles, descriptions, links and button
  labels are editable in the corresponding Homepage Customizer section.
- Blur clinical case photos/videos by default on gallery thumbnails, category
  covers and lightbox; visitors can explicitly reveal each item. Operators can
  mark reviewed media safe in the Patient editor. Sensitive images are excluded
  from the image sitemap.
- Made project-link reconciliation follow project keys so reordering cannot
  route a card to another project's Page.

## 2026-10-06 — Project design and content completion / theme 3.3.0

- Redesigned all fourteen innovation projects across 42 EN/FA/AR editable Pages.
  Added bounded figure galleries, document sections, local anchors and related links.
- Removed duplicate figures and static video placeholders from authored layouts;
  kept real project media and external source citations.
- Fixed patient-list admin language/RTL leakage and added category/tag columns.
- Moved four randomly selected Patients from Hospital to Clinic, retaining disease
  categories and media. No private patient inventory is stored in Git.
- Expanded the 27 Posts missing featured images, preserving introductions and
  using nine generated topic-specific covers shared across translations.
- Added explicit content updates with hash checks, backups and idempotent resume.

## 2026-10-06 — Preserve Arabic characters / theme 3.2.1

- Scoped WP-Parsidate character normalization to avoid altering authored Arabic
  text on Arabic frontend pages; retained Persian and administrator behavior.
- Added focused checks for Arabic, Persian, English and administrator contexts.
- Published 42 Innovation Pages; verified catalogue links, translations and
  sitemap. Imported 66 new illustrations and reused existing/identical files.

## 2026-10-06 — Innovation detail Pages / theme 3.2.0

- Added source-based descriptions and illustrations for fourteen projects in
  English, Persian and Arabic, stored in normal editable WordPress Pages.
- Added explicit resumable administrator import, Media hash reuse, translation
  linking and internal catalogue destinations with one Page for H3.
- Preserved sidebar anchors and hub content; source links appear only for
  external references, with no links back to the legacy doctor subdomain.
- Added migration-contract checks for languages, references, H3 deduplication,
  safe structure validation and repeatable catalogue updates.

## 2026-10-06 — Innovation catalogue placement correction

- Replaced the four generic Innovation sections with the fourteen references in
  all three language Pages, keeping the existing cover and live blocks.
- Added fourteen matching heading anchors to each Page sidebar.
- Removed the Continue exploring/next-step card and the duplicate appended list.
- Updated the tracked Page snapshots and content/deployment documentation.

## 2026-10-06 — Patient media, languages and SEO / theme 3.1.1

- Added checksum-verified hosting imports and reusable private ZIP packaging.
- Reuse exact existing Media Library originals and keep per-patient media order.
- Added editable English/Persian/Arabic case copy and category names, shared media,
  locale-preserving links, navigation and correct pagination digits/labels.
- Added localized patient SEO metadata, canonical/alternate links and all gallery
  images to Rank Math patient sitemaps.
- Enabled patient-category XML sitemap on production, flushed permalink rules
  to fix sitemap 404 responses, reviewed patient metadata and dismissed the
  new-post-type notice after configuration.
- Completed media ingestion: 314 actual media (305 images and nine videos) across
  71 cases; excluded one AppleDouble metadata file and reused nine existing IDs.
- Replaced Before/After Surgery content in six English/Persian/Arabic Pages with
  the supplied guidance, no sidebar, working consent PDF and linked follow-up.
- Added the preparation/recovery summary before Clinical Care's pathway cards
  in all three language Pages.
- Restored all 14 Innovation references (15 links) in the three Innovation Pages;
  seven deleted old-domain project references now use their verified legacy archive.
- Fixed missing Blog descriptions, page metadata reduced to section kickers and
  duplicate resource URLs in post/Page sitemaps.
- Validation: full npm test, WordPress behavior/release checks and PHP syntax.

## 2026-10-05 — Patients / theme 3.1.0

- Reconciled the active production 3.0.0 theme with Git and upstream history.
- Added Patients, disease categories/tags, mandatory Clinic/Hospital setting,
  treatment content and ordered image/video/video-link galleries.
- Replaced fixed Clinical Care gallery content with published patient records;
  preserved existing full-gallery destinations.
- Added patient/category/tag archives, category covers with 60% overlay,
  expandable category-to-patient sidebar, pagination and captioned lightbox.
- Added administrator-only resumable Drive import with source-ID deduplication.
- Added behavior checks and documented the operator/import workflow.
- Removed the repeated H1 in full-gallery blocks and clarified the empty-media
  state. Added pasted JSON manifests alongside file selection.
- Production import verified: 71 cases, 62 disease/procedure categories and two
  care categories; 9 of 315 media imported. Remaining media and final live
  verification are pending browser-control recovery (see progress log).

Detailed release history: [theme CHANGELOG](wordpress-theme/dr-ali-moradi/CHANGELOG.md).
Observed deployment/import results: [progress log](docs/wordpress-theme/progress-log.md).
