# Changelog

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
