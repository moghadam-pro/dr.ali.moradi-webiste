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
