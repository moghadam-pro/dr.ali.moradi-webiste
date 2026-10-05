# Page content lives in WordPress — theme 2.0.0

Every designed page is edited from **Pages** in WordPress, in each language's
own copy of the page. Nothing about those pages is read from theme files at
render time any more.

## What is stored in the page

| Page (English slug) | Stored in the page content |
|---|---|
| `about` | cover, story, practice, journey timeline, ecosystem cards, recognition banner |
| `clinical-care` | cover and the two pathway cards; team grid and both gallery strips are live blocks |
| `research`, `innovations` | cover and the numbered sections with the "on this page" sidebar and next-step card; team grid (and latest innovation posts on `innovations`) are live blocks |
| `clinic-services`, `hospital-services`, `before-surgery`, `after-surgery`, `faq`, `rehabilitation` | cover and numbered sections |
| `contact` | cover, contact details, and the existing MPro Forms shortcode |
| `clinic-gallery`, `hospital-gallery` | cover; the image grid is a live block |

Designed sections are **Custom HTML** blocks (one per section) that use the same
classes as the previous renderers, so the design is unchanged. To edit a
section, open the page, select its Custom HTML block, and edit the text. The
"Live Page Section" block (`dr-ali-moradi/page-section`) and the Breadcrumbs
block keep updating themselves and should be moved or removed as blocks, not
edited as HTML.

Pages use the **Designed Page** template (`page-designed`): header, page
content, the appointment call-to-action, footer.

## Migration behaviour

`inc/page-content.php` runs on the first administrator request after the theme
is upgraded and records its result in the `dam_page_content_report` option.

- It writes default content for each page in each language that exists (through
  Polylang's translation links). Missing translations are reported as
  `missing`, never created.
- A page that already has `_dam_designed_page` post meta is never rewritten, so
  editing a page and re-running the migration is safe.
- The page's previous content is kept in the `_dam_pre_designed_content` post
  meta before it is replaced.
- Images resolve through the Media Library by slug, exactly as before; a page
  seeded on a site without those media items shows empty images until they are
  uploaded.
- The Contact page keeps its existing `[mpro_form ...]` shortcode.
- It creates the two footer menus for languages that have none and assigns
  them to their locations.
- The version option (`dam_page_content_version`) is only saved when the run had
  no errors, so a failed run is retried on the next administrator request.

To regenerate a page's default design, delete its `_dam_designed_page` post
meta, delete `dam_page_content_version`, and load any admin screen.

## Not covered

Blog posts, team-member profiles, the blog archive, and single posts are
already WordPress content or query-driven and are unchanged. Education still has
no source content and no page.

## 2026-10-06 content updates

The six Before/After Surgery Pages now contain the owner's reorganized surgical
service instructions in English, Persian and Arabic. They retain their existing
cover/template, use a full-width numbered body without an aside and link to each
other. The existing consent PDF from `public/downloads` is registered as Media
Library attachment 940; its active URL is
`https://dralimoradi.com/wp-content/uploads/2026/10/pre-surgery-consent-form.pdf`.
This is the existing acknowledgement document, not a newly authored hospital form.

All three Clinical Care Pages include a short preparation/recovery section
before the Clinic/Hospital pathway cards, linking to the matching language's two
instruction Pages. Existing team and patient gallery blocks are preserved.

The three Innovation Pages retain their existing cover and live blocks. The
14-record reference catalogue replaces the original four generic sections in
the main numbered body, with fourteen matching anchor links in its sidebar.
The next-step/Continue exploring card is removed. Seven
archived Dr. Moradi project pages are served by `legacy.dralimoradi.com`; external
product/research references retain their supplied destinations. H3 has two links
and remains one record. See [reference inventory](../innovation-links.md).

The exact twelve published Page bodies are tracked in
`wordpress-theme/content-migration/page-updates-2026-10-06/` by production Page ID.
They were saved through the WordPress Page code editor, not rendered from theme
PHP or an automatic overwrite migration. Future edits belong in WordPress Pages.


## Innovation detail import (3.2.0)

Use Tools → برگه‌های نوآوری / Innovation Pages and click Create Pages. This is
an explicit administrator action, never a frontend seed. The checked-in
`content/innovation-pages.json` holds 14 project records with EN/FA/AR content
adapted from the supplied primary references (menus, footer copy and duplicate
responsive content excluded). Each resulting Page is parented to its language's
Innovation hub and uses `page-designed`, ordinary Custom HTML and breadcrumbs.

The importer registers project illustrations in Media, reusing SHA-256 matches
of existing originals. Source URL mappings persist for resumption. Existing
Pages identified by `_dam_innovation_page_key` are not overwritten, so operator
edits survive reruns. Failed image downloads retain their original image URL
in the Page rather than blocking creation; review the import report before
claiming media transfer complete. A nonce, administrator capabilities and an
atomic import lock protect writes. No credentials are stored.

The final step requires all 42 Pages to be published, links 14 Polylang groups,
validates all three hub layouts, then changes only project buttons. Fourteen
sidebar anchors, prose, cover and dynamic team/post blocks are retained. H3's
two references become one internal button; its external Avisa source appears
on the H3 Page. Old doctor-domain source links are omitted. Original hub link
content is backed up in `_dam_pre_innovation_catalogue_links`, and the report is
stored in `dam_innovation_pages_report`. Normal Page edits and WordPress
revisions remain the operator workflow after import.

Theme 3.2.1 also preserves native Arabic characters on Arabic frontend Pages by
removing only WP-Parsidate’s Arabic-to-Persian normalization callbacks for that
language. Stored content, Persian pages and administrator settings are retained.
