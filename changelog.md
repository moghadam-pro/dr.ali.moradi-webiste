# Changelog

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
