# Patients catalogue — theme 3.1.0

## Operator workflow

The Persian admin menu is **بیماران** with **همه بیماران**, **افزودن بیمار**,
**دسته بندی**, and **برچسب ها**. The post type is `patient`; its taxonomy keys
are `patient_category` (hierarchical) and `patient_tag`.

- Title: patient name or case title.
- Excerpt: short description shown in the patient page and lightbox.
- Editor: case history and actions/treatment performed.
- Featured image: optional cover; the importer selects the first image.
- Patient gallery: ordered Media Library images/videos or external video links,
  each with an optional title and description. Add/remove/reorder in the native
  metabox. External links open in a new tab; they are not arbitrary HTML embeds.

Exactly one care category is mandatory: **کلینیک** (`clinic`) or **بیمارستان**
(`hospital`). Disease categories and tags are additional. REST saves with an
explicit invalid selection return 400; classic/quick/bulk/taxonomy writes
normalize to the previous setting, or Hospital for a new record. Protected
care categories cannot be deleted, reparented, or have their routing slug changed.
Names/descriptions remain editable. The owner chose Hospital as the import default;
operators must reclassify Clinic cases after import.

Case records and their category tree are shared between languages. English,
Persian and Arabic pages render the same cases with locale-appropriate gallery
controls. No duplicate patient records are created solely for translations.

## Front end

- `/patients/`: catalogue; `/patients/{slug}/`: individual case.
- `/patient-category/{path}/`: category gallery includes descendant categories.
- `/patient-tag/{slug}/`: tag archive with the same category sidebar.
- Existing `/clinic-gallery/` and `/hospital-gallery/` page destinations and
  their localized equivalents remain intact and query the care category.
- Clinical Care's dynamic `page-section` gallery blocks query published Patients.
  Existing Page text and unrelated sections are preserved.
- Category cards use the first available image from a published descendant case,
  a 60% color overlay, and the category title. Empty categories render a solid
  card and explicit empty state; retired static case photographs are not fallback
  patient records.
- Archive galleries paginate by 12 cases; all gallery media of those cases appears.
  Sidebar uses native `details`/`summary`, +/− icons, nested categories and direct
  patient links. Preview strips contain up to 80 media items, initially showing 4.
- Shared lightbox preserves image aspect ratio within viewport bounds and shows
  title, description and patient link. Video playback stops on navigation/close;
  Escape, arrows, focus trapping and focus restoration are supported.

## Drive import

Source: `My Drive/_projects/dr.ali-moradi/Patients-site-2`. Folder names that
identify people become cases; disease/procedure folders become categories.
Folders with direct media but no named patient receive one case under that
category. Numbered folders receive a neutral case title. Supplied DOCX text is
copied into the case history; no diagnosis or treatment is inferred from photos.
Apple metadata files are ignored. Operators can revise classification/nesting.

The initial inventory contains 62 disease/procedure categories, 71 cases and
315 media files (306 images, 9 MP4 videos). The two care categories are additional.

Tools → **درون‌ریزی بیماران** accepts a local JSON manifest, selected as a file
or pasted into the JSON field:

```json
{"operations":[
  {"kind":"category","key":"source-folder","title":"Category","parent":""},
  {"kind":"patient","key":"source-case","title":"Case","categories":["source-folder"],"location":"hospital","content":"","excerpt":""},
  {"kind":"media","key":"source-file","patient":"source-case","filename":"image.jpg","order":0,"url":"https://approved-connector-storage/download"}
]}
```

Import categories before cases, and cases before media. The page performs serial,
nonce-protected administrator AJAX requests with a per-source lock. Stable source
IDs deduplicate records/attachments and preserve operator edits on re-import.
Attachment metadata and gallery order are generated through WordPress's own media
APIs. Only HTTPS authenticated `*.oaiusercontent.com` download references supplied
by the Drive connector are accepted. WordPress's safe download validates redirects.
References expire; generate fresh batches and retry only failed source IDs.
Use small media batches (approximately 5–7 files) because server download and
thumbnail generation can outlast the temporary connector URL validity.

### Observed production state, 2026-10-05

Theme 3.1.0 was uploaded and WordPress confirmed replacement. All 133 structure
operations completed: 62 disease/procedure categories and 71 published cases,
plus the two automatically created care categories. Hospital is the default.
The first 60-media batch reported 9 completed and 51 failed (expired references,
mostly Forbidden). Thus **306 of the 315 media remain unverified/unimported**.
Resume by source ID; do not re-create patients or categories. Signed references
must be refreshed immediately before each small batch.

Final source fixes (gallery H2 and empty-media copy) have passed local tests but
their production deployment is pending. The importer's paste-field source was
saved through the Theme File Editor with a success notice; its active-page
behavior could not be rechecked because browser control stopped loading its
request-header policy. Final visual checks of populated galleries, video,
mobile/RTL and modal interactions remain pending. Do not treat this inventory
or successful local tests as confirmation of complete media ingestion.

Store the inventory, clinical text and signed media manifests outside Git (for
this run, `/tmp/dam-drive-inventory.json` and `/tmp/dam-patient-*.json`). Never commit
patient media, identifying source manifests, bearer URLs or credentials. Drive
sharing is unchanged; downloads are copied to this authorized WordPress site.

## Validation and recovery

`npm run test:wordpress` checks the release contract and behavioral cases for
missing/conflicting care settings, category retention, safe media URLs, attachment
MIME handling and Persian labels. PHP syntax and JavaScript syntax are checked
separately. Live verification must additionally inspect imports, category nesting,
patient details, media loading, modal dimensions and multilingual gallery pages.

The pre-change production theme export is retained in `~/Downloads/dr-ali-moradi.zip`.
The importer adds records and does not delete existing site content. Re-running a
manifest resumes through source IDs without replacing operator-edited case text.
Deployment and exact observed counts are recorded in `progress-log.md`.
