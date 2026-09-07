# Architecture — Dr. Ali Moradi WordPress Theme

Status: agreed, in development.

## Goals

- Non-technical clinic operator can edit almost everything without touching
  code: header, footer, page content, page-level SEO, menus.
- Three languages: English (default), Persian, Arabic — correct RTL/LTR
  behavior per language.
- SEO-optimized (metadata, sitemaps, structured data, clean URLs).
- Minimal plugin surface, by explicit instruction:
  - **Polylang** (free) — multilingual.
  - **Rank Math** (free) — SEO.
  - **MPro Forms** (operator's own plugin) — all forms (contact, research
    collaboration, internship/thesis inquiries).
  - No page builder (no Elementor), no ACF, no other third-party plugin.
    Everything else — custom post types, custom fields, page templates,
    header/footer editing, theme-wide settings — is built into the theme's
    own code.

## Theme type: Full Site Editing (block theme)

A classic PHP theme with a Customizer panel cannot give a non-technical
operator visual control over header/footer/layout without either a page
builder or ACF, both of which are excluded. WordPress's native **block theme**
system (`theme.json` + HTML block templates + template parts), introduced
as Full Site Editing, provides that control natively, with zero extra
plugins:

- **Global Styles** (Appearance → Editor → Styles): colors, typography
  (Vazirmatn, Inter, Scheherazade New — already bundled in the current
  React build), spacing. Edited visually, applies site-wide.
- **Template parts** (`parts/header.html`, `parts/footer.html`): edited
  visually in the Site Editor; a change applies to every page that uses
  them, satisfying "header and footer are edited once, in theme settings,
  and used everywhere."
- **Block templates** (`templates/*.html`): the reusable page structures
  described below.

## The one exception: the homepage

`templates/front-page.html` is the only template whose structure lives
inside the theme (hero, "choose your path," connected practice, impact
numbers, appointment gateway, latest news, about preview — mirroring the
current site's homepage narrative). Its *content* (text, numbers, images)
is editable through theme options and dynamic blocks (see below), but its
*layout* is not a page the operator creates.

**Every other page is a real WordPress Page**, created under
Pages → Add New, with a template assigned from Page Attributes.

## Page templates (Page Attributes → Template)

| Template file | Purpose |
|---|---|
| `page.html` | Standard page: title + free-form block content |
| `page-hub.html` | Section-hub pages (Clinical Care, Research, Innovation, Education overviews) — card grids, sub-sections |
| `page-contact.html` | Contact page: locations, map placeholder, MPro Forms contact form |
| `page-full-width.html` | Full-width page, no title/cover treatment |
| `single.html` | Single blog/news post (shared across all categories); also the fallback for the `publication` and `patient_resource` CPTs until they need a bespoke layout |
| `archive.html` | Blog/News listing, filterable by category |
| `single-team_member.html` | Single Team Member (CPT) |
| `single-condition.html` | Single Condition (CPT) |
| `single-innovation.html` | Single Innovation project (CPT) |
| `search.html`, `404.html`, `index.html` | Utility templates (`index.html` is the required block-theme fallback) |

Filenames follow WordPress's own block-template hierarchy convention
(`single-{post_type}.html`), not the looser `singular-*` naming used in an
earlier draft of this table — see `progress-log.md` for the as-built
state.

The operator never chooses a layout from scratch — only from this fixed
list, so the visual system cannot be broken by accident.

## Content model

Rule used to decide "standard Post" vs "custom post type": anything with
blog-like, chronological, categorized character is a **Post**; anything
that is a structured catalogue entry with fixed fields is a **CPT**.

| Content | Type | Notes |
|---|---|---|
| News / blog / research & innovation updates | Standard Post | Categories: Research, Innovation, Recognition, Clinic Update — matches the current unified blog/news model in `app/blog-content.ts` |
| Team members | CPT `team_member` | role, summary, bio, photo, areas (clinic/research/innovation) |
| Clinical conditions | CPT `condition` + taxonomy `condition_category` | matches `docs/content-strategy-and-sitemap.md` condition groups |
| Innovation projects | CPT `innovation` | problem, idea, development status, evidence |
| Publications (articles/books/patents) | CPT `publication` + taxonomy `publication_type` | |
| Patient resources (Before/After Surgery, FAQ, downloads) | CPT `patient_resource` | supports file/PDF attachment |

All CPTs and taxonomies are registered in the theme itself
(`inc/post-types.php`, `inc/taxonomies.php`) — not a separate plugin, per
instruction. This does mean the content types stop being registered (though
the data itself stays in the database) if the theme were ever deactivated;
accepted trade-off given the explicit "inside the theme" requirement.

## Custom fields without ACF

- **Scalar fields** (publication year, patent link, project status, team
  member role) use `register_post_meta()` plus a small custom panel in the
  block editor sidebar (`PluginDocumentSettingPanel`), built once in the
  theme. Same editing experience as ACF, no plugin dependency.
- **Repeating structured content** (e.g. project milestones, team grids)
  uses custom Gutenberg blocks with locked structure (`templateLock`) —
  the operator edits text/images inside, not the layout.

## Theme-wide settings

Two mechanisms, matched to the kind of setting:

1. **Site Editor** (Global Styles + template parts) for anything visual:
   colors, fonts, header/footer layout.
2. **A single "Theme Options" admin page** (`inc/theme-options.php`, built
   with the core Settings API — no plugin) for global scalar data that must
   update everywhere at once: phone, WhatsApp, clinic addresses, social
   links, the external appointment URL (`https://nobat.ir/9705`), and the
   impact/evidence numbers (articles, books, national/US patents) together
   with an "as of" date, mirroring the working figures already collected in
   `docs/content-strategy-and-sitemap.md`.

These values are read by a small set of **dynamic custom blocks** (contact
info, impact stats, appointment CTA) that can be placed in the header,
footer, or homepage — edit once in Theme Options, every placement updates.

## Menus

Standard WordPress menus (Appearance → Menus), one per language, wired into
the theme's `parts/header.html` and `parts/footer.html` via the core
Navigation block. No custom menu code.

## Forms

All forms (contact, research collaboration, internship/thesis inquiry) are
built and rendered by **MPro Forms**. The theme only provides placement —
its block or shortcode is inserted into the relevant Page's content — and
matching visual styling in Global Styles so the form doesn't look foreign.

## Multilingual — Polylang (free)

- Posts, Pages, all CPTs and taxonomies are made translatable via
  Polylang's Settings → Languages → Custom Post Types screen (available in
  the free version — no Pro dependency, since there is no ACF integration
  to pay for).
- One WordPress menu per language, assigned per language to the Navigation
  block.
- Static labels used inside the theme's own dynamic blocks (e.g. "Book an
  appointment") go through `pll_register_string()`, which is also part of
  the free plugin.
- **Known constraint to design around:** a block-theme template part is
  shared markup; any text typed directly into `header.html`/`footer.html`
  would be the same in every language. To avoid that, every piece of
  language-dependent text in the header/footer must come from a dynamic
  block (Navigation block, or the theme's own PHP-rendered blocks reading
  `pll_register_string()` / Theme Options), never typed directly into the
  template part.

## SEO — Rank Math (free)

- Per-page/post/CPT title and description (Rank Math attaches to any
  public post type automatically).
- Multilingual XML sitemap (works with Polylang without Pro).
- Structured data via Rank Math's free Schema Generator: `Person` for Dr.
  Moradi's profile, `Local Business` / `Medical Business` for the clinic —
  customized manually since a dedicated `Physician` template isn't in the
  free tier.
- 301 redirect map from the current site's URLs (see
  `content-migration-plan.md`) — Rank Math's redirection manager is
  available for free.

## Hosting-adjacent concerns (explicitly out of scope for the theme)

- **Backups**: handled at host/server level, not by a plugin.
- **Caching & security**: handled at Cloudflare level, not by a plugin.

These are noted here only so the theme is never built assuming a caching or
backup plugin exists.

## Domains

- Build and content work happens on a temporary WordPress install:
  https://tmp.saveon.me/
- The live production domain, https://dralimoradi.com/, is not touched
  during this phase. When the temporary site is complete and approved, the
  whole site is backed up as a unit and moved onto the production domain —
  the theme and content model must not assume the temporary domain name
  anywhere (no hardcoded `tmp.saveon.me` URLs in content, options, or
  code) so that move is a clean cutover.
