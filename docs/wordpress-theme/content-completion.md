# Authored content completion — 2026-10-06

## Innovation Pages

All 14 projects retain normal WordPress Page content and their EN/FA/AR Polylang
relationships (42 Pages). The latest bodies are in
`wordpress-theme/content-migration/innovation-redesign-2026-10-06/`.
`index.json` maps public IDs, languages and URLs to body snapshots.

The layout has a split introduction, compact local navigation, three readable
project sections, bounded image cards, separate archived certificates, external
references where applicable and previous/next project navigation. Medical figures
use `object-fit: contain`; the full image opens through its card link. Repeated
Media URLs and static player placeholder images are omitted from the Page body.
They are not deleted from Media. Magnetic distraction links to its archived
YouTube demonstration. The coated-pin project has a text-led introduction because
its reference provides no project images. Shared CSS is scoped to `.dam-project`
and loaded only for imported project Pages; the editor receives the same styles.

## Posts

A fresh WordPress Posts export contained 97 Posts: none had an empty body, but 27
had no featured image and only a short introduction. These are nine EN/FA/AR
translation groups: trauma, nerve/tendon care, hand/wrist disorders, microsurgery,
outpatient procedures, rehabilitation, external fixation, bionic hand control and
magnetic distraction. Their original introductions remain; three educational
sections and relevant reading/project links were added. The other 70 Posts were
preserved.

Bodies and the public inventory are in
`wordpress-theme/content-migration/post-completion-2026-10-06/`. Nine selected
original generated images are in `public/media/posts-generated/`. Each topic is
uploaded once and reused across its three translations. Media titles, alt text and
descriptions distinguish conceptual educational images from real patients or
actual prototypes. The articles also identify their cover as conceptual.

Medical reading uses AAOS OrthoInfo and ASSH educational resources; engineering
articles describe the existing project archive without asserting clinical outcomes,
regulatory approval or product availability. No patient case details are invented.

## Safe operator workflow

Tools → Content updates accepts a reviewed JSON array. Each operation contains
an existing Page/Post ID, type, previous trimmed-body SHA-256 and new HTML body.
Optional `image_name` must resolve to an existing image in Media and is used only
when the target has no featured image. The tool requires administrator HTML and
per-post edit permissions, a nonce and an atomic lock. Changed live bodies are
rejected rather than overwritten. Existing translations, titles, terms and URLs
stay intact. WordPress revisions and exact previous-body metadata backups support
recovery. Re-running an already applied body does not rewrite it. Patient records
are expressly excluded from this tool.

## Patients

The patient catalogue detection now excludes `is_admin()`, keeping frontend
language attributes out of WordPress list/editor screens. Category and tag columns
are visible in All Patients. Four randomly chosen records were moved from Hospital
to Clinic using the editor, with the disease terms and media retained. Selection
identifiers and patient exports are private temporary files outside the repository.

## Verified production result

Theme 3.3.0 was installed successfully over the exact Git 3.2.1 release.
All 42 Page updates and all 27 Post updates completed without errors. A fresh
WordPress export matches all 69 reviewed bodies exactly; all 97 Posts now have a
nonempty body and a featured image. Nine new Media attachments (1074–1082) are
shared by the translation groups. The `index.json` records those attachment IDs.

All 42 public project URLs return HTTP 200 with one H1, three project sections,
project CSS, the correct language and three translation links. Galleries have no
duplicate URLs, static player placeholders or old doctor-subdomain links. Live
browser checks cover the Persian desktop gallery and all 14 project layouts at
390 pixels, plus Persian and Arabic mobile pages; no horizontal overflow occurs.

All Patients now uses `fa-IR`, RTL document/table direction and category/tag
columns. A final export confirms 71 Patients: Clinic 4, Hospital 67. Exactly the
four selected records changed location terms; disease terms, gallery values and
translation fields are retained. Import bookkeeping and the selected location
meta are excluded from the gallery-content comparison.

All local build/rendered-HTML tests, WordPress behavior/security checks and PHP
syntax checks passed. Deployment details are recorded in `progress-log.md`.
