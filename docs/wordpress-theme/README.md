# WordPress Theme — Documentation Index

This folder documents the WordPress conversion of the Dr. Ali Moradi website.
Documentation and source are maintained together in the main repository.
The theme source lives under `wordpress-theme/dr-ali-moradi/`.

Current Patients implementation: theme 3.1.0, branch `codex/patients-gallery`.

## Contents

- [`patients.md`](patients.md) — Patients content model, galleries, operator workflow and resumable Drive import.
- [`architecture.md`](architecture.md) — the agreed technical architecture:
  theme type, templates, custom post types, plugin boundaries, multilingual
  and SEO approach.
- [`decisions-log.md`](decisions-log.md) — chronological record of decisions
  made during the planning conversation, with the reasoning behind each one.
- [`content-migration-plan.md`](content-migration-plan.md) — how content is
  extracted from the current Next.js/Vite site and mapped to WordPress
  content types, and how it is imported.
- [`open-items.md`](open-items.md) — access, credentials, and decisions still
  needed before work can continue past the current point.
- [`progress-log.md`](progress-log.md) — running log of what has been built,
  updated as work proceeds.
- [`page-content.md`](page-content.md) — how the designed pages are stored
  in WordPress Pages (theme 2.0.0) and how the migration seeds them.
- [`homepage-customizer.md`](homepage-customizer.md) — the per-language
  Customizer panels and the footer menus.
- [`versioning.md`](versioning.md) — Semantic Versioning policy, release
  checklist, and the single-source-of-truth rule.

## Source site

- Current live demo: https://dralimoradi.moghadam.pro/
- Production WordPress domain: https://dralimoradi.com/
- Temporary WordPress site for the build: https://tmp.saveon.me/
- Source repository: `moghadam-pro/dr.ali.moradi-webiste`
