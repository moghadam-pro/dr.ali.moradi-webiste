# Content Migration Plan

## Source of truth

All current content lives in typed, already-localized (`en`/`fa`/`ar`)
TypeScript modules under `app/` in this repository:

| Source file | Content |
|---|---|
| `app/site-content.ts` | Homepage strings: nav, hero, facets, path cards, journey, expertise/condition teasers, innovation teasers, impact metrics, appointment cards, footer |
| `app/about-content.ts` | About page: biography, positions, awards, CV |
| `app/blog-content.ts` | Unified blog/news/innovation article collection (tagged, per-language title/excerpt) |
| `app/structured-content.ts` | Team members (`teamMembers`), page-template registry, interior page data shape |
| `app/content-overrides.ts` | Per-locale overrides layered onto base content |
| `app/page-extras.ts` | Additional per-page supplementary content |

Because these are typed ES modules (not markup to scrape), the reliable way
to extract them is to **execute** the modules and serialize their exports —
not regex/string scraping, which would be fragile against formatting
changes.

## Extraction

Script: `wordpress-theme/content-migration/extract-content.mjs`.

- Runs under Node 22.13+ (already the project's minimum) using Node's
  built-in TypeScript type-stripping (`--experimental-strip-types`), so no
  new dev dependency (`tsx`, `ts-node`, etc.) is added to the repository
  just for a one-time migration script.
- Imports each content module directly from `app/`, walks the exported
  objects, and writes one normalized JSON file per content type to
  `wordpress-theme/content-migration/export/`:
  - `posts.json` — blog/news/innovation articles → WordPress Posts
  - `team.json` → CPT `team_member`
  - `pages.json` — homepage sections + interior pages (About, Clinical
    Care, Innovation, Research, Education, Contact) → WordPress Pages,
    tagged with which page template applies
  - `theme-options.json` — impact metrics, appointment info, contact
    details → Theme Options
- Each JSON record keeps all three locales side by side (matching the
  source shape), so the import step can create one WordPress entity per
  locale and link them as Polylang translations of each other.
- Media references (`/media/...` paths in `public/`) are collected into
  `media-manifest.json` with source path, target filename, and which
  content record(s) use them, so the importer can upload each file once and
  reuse its attachment ID everywhere it's referenced.

## Import

Script: `wordpress-theme/content-migration/import-to-wordpress.mjs`
(scaffolded; execution is pending WordPress site credentials — see
`open-items.md`).

Chosen mechanism: **WordPress REST API with an Application Password**,
not the classic WXR/"Tools → Import" importer. Reasoning:

- WXR round-trips through XML and does not understand Polylang's
  translation-group linking or custom post-type/meta structures well.
- The REST API lets the script create the exact CPT/meta/taxonomy/Polylang
  language-link structure directly, in one automated pass, matching the
  "import everything at once, not page-by-page" instruction — while still
  being a single script run, not a manual per-page workflow.

Import order (so foreign keys — media IDs, translation groups — exist
before anything references them):

1. Upload media from `media-manifest.json` to the Media Library; record
   returned attachment IDs.
2. Create Team members, Conditions, Publications (CPTs) per locale; link
   each locale group as Polylang translations of one another.
3. Create Posts (blog/news/innovation) per locale, same linking.
4. Create Pages per locale (About, Clinical Care, Research, Innovation,
   Education, Contact, legal/utility pages), assigning the correct page
   template from `architecture.md`.
5. Write Theme Options (contact details, impact metrics, appointment URL)
   once (these are not per-language content, aside from any label text
   handled via `pll_register_string`).
6. Generate the 301 redirect map (old Next.js route → new WordPress URL)
   from `app/sitemap.ts` and the new page slugs, for import into Rank
   Math's redirection manager.

## Verification

After import, compare the temporary WordPress site against the live
reference site (https://dralimoradi.moghadam.pro/) page by page and
language by language, checking:

- Every nav item, page, post, and team member present on the live site
  exists on the new site, in all three languages.
- Text content matches (allowing for template-driven presentation
  differences, not content differences).
- Images resolve and are attributed to the correct content.
- Internal links resolve; the external appointment link
  (`https://nobat.ir/9705`) is preserved exactly.

Findings are recorded in `progress-log.md`, not silently fixed and
forgotten, so there's a record of what was checked.
