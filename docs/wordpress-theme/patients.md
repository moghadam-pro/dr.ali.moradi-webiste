# Patients catalogue — implemented in 3.1.1, updated in 3.3.0

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

## Languages and SEO

Patients share one case and one media gallery across English, Persian and Arabic.
The case editor provides translated title, excerpt and body fields for each
language; blank fields retain the original. Category and tag editing provide translated
names. No clinical translation is generated automatically.

Shared catalogue URLs use `?patient_lang=fa` or `?patient_lang=ar`; English uses
the base URL. Language switching retains the case/archive and page. Navigation,
RTL direction, care labels and pagination follow the selected locale, including
ASCII numbers on English pages even with Persian date plugins installed.
Existing translated Clinic/Hospital Pages retain their Polylang paths.

Rank Math titles/descriptions follow translated copy. Untranslated shared cases
canonicalize to their base URL; actual translated cases receive language
alternates. The patient XML sitemap includes all gallery images, and category
archives are enabled in their own sitemap. Preserve operator-set SEO text.

## Bulk hosting import

For large inventories, download originals locally and package them outside Git:

```sh
python3 wordpress-theme/content-migration/package-patient-media.py \
  /private/inventory.json /private/originals /private/bundles --max-mb 150
```

Upload ZIPs with the hosting File Manager into the domain's
`dam-patient-staging` folder, **beside** `public_html`, then extract there.
Paste each matching JSON manifest in Tools → Import Patients and wait for its
completed/failed count before the next manifest. This creates real WordPress
attachments, thumbnails, gallery associations and featured images; uploading
files alone does not register them in the Media Library.

Local imports require flat source-ID filenames and SHA256 verification. The
importer accepts files only within that fixed staging directory. It checks source
IDs and hashes existing attachment originals (including pre-scaling originals)
to reuse exact matches, preserving existing attachment parent/content. Gallery
ordering is stored per patient. Newly registered files move into WordPress's
normal uploads path; retained duplicate staged files and ZIPs can be moved to
host Trash after verification. Use rolling batches to stay within hosting quota.

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
315 listed files: 314 actual media (305 images, 9 MP4 videos) and one
AppleDouble metadata file (`._1.jpg`) excluded from media import. The two care categories are additional.

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

### Observed production state, 2026-10-06

Theme 3.1.1 replaced the installed theme through WordPress ZIP upload. All 71
cases and 64 categories are present. Eight private hosting batches registered
313 media, and the final 389 MB MP4 was uploaded separately and registered with
one completed/zero failed. Total: **314 media (305 images, nine videos)**.
Nine previously imported source IDs were reused without duplicate attachments.
The initial apparent 315th image was AppleDouble metadata, not a photograph.

The WXR audit before the final video confirmed 313 expected source IDs, all
linked to their correct case, zero empty cases and exactly one mandatory care
category per case. The final video operation completed successfully. Hospital
remains the owner-approved import default; operators can reclassify Clinic cases.
Originals are registered in normal WordPress uploads with thumbnails and patient
links. Private ZIPs remain outside the public web root for recovery.

### Current production check, theme 3.3.0

The owner requested four randomly selected cases to move from Hospital to
Clinic. The normal editor updated those four location terms and their Rank Math
primary term. A final WordPress export reports 71 Patients: **Clinic 4** and
**Hospital 67**. The selected records retain their disease categories, gallery
and multilingual fields. Patient identifiers and private export files are kept
outside Git.

The All Patients admin screen previously inherited the frontend catalogue's
language attributes. That made the Persian admin table render left-to-right.
The frontend detection now excludes `is_admin()`; production verification shows
`fa-IR`, RTL document/table direction, normal category/tag columns, and no
visible error notice. The frontend English catalogue still uses English/LTR.

Live checks confirm localized English/Persian/Arabic pagination, translated
navigation, captioned image modals and an MP4 with loaded metadata (1080×1920,
6.57 seconds). Closing its modal pauses playback and clears the source. Rank
Math patient/category XML endpoints return 200 after permalink refresh; the
new-post-type notice was dismissed after configuring the new content types.

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
