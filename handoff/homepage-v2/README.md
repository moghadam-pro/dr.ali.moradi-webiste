# Homepage v2 handoff

This directory is the dependency-free HTML/CSS/JavaScript implementation of the
approved Figma homepage (`home-1440w`, node `2071:27750`). It intentionally does
not replace the existing Vinext demo.

## Preview

Serve this directory with any static web server. The page uses relative asset
paths, so it can be previewed at the root or inside a project subdirectory.

## WordPress / Hello Elementor transfer

The markup is semantic and section-based. Each direct child of `<main>` maps to
one Elementor container or one PHP template part. CSS custom properties at the
top of `assets/css/home.css` contain the reusable colors, shell width, type, line,
and shadow tokens. JavaScript is limited to the mobile menu, language menu,
appointment accordion, sticky header, and scroll reveals.

For an Elementor build:

1. Create a Hello Elementor child theme and enqueue `home.css` and `home.js` only
   on the front page.
2. Recreate each semantic section as an Elementor container, preserving the class
   names so the same CSS can be reused.
3. Upload `assets/images` without renaming files; replace relative URLs with
   WordPress media URLs or child-theme URLs.
4. Keep the appointment CTA as an external link to `https://nobat.ir/9705`.
5. Keep content in WordPress fields/widgets; do not put translated strings in JS.

## Plugin-free multilingual recommendation

Use WordPress Multisite in subdirectory mode:

- English: `/` (default)
- Persian: `/fa/`
- Arabic: `/ar/`

All three sites use one Hello Elementor child theme and the same design tokens,
while each site owns its native WordPress content, menus, SEO metadata, and media
references. This is the cleanest core-only solution: language URLs are stable,
RTL can be applied at the document level, and no translation plugin owns the
content model. The tradeoff is that editors must keep three content trees in sync.

Font mapping:

- `html[lang="en"]`: Inter
- `html[lang="fa"]`: Vazirmatn
- `html[lang="ar"]`: Amiri

For production, language links should resolve to the corresponding page on each
site and `hreflang` tags should be generated in the child theme from a small page
mapping table.

## Validation notes

- Desktop reference: 1440 px wide, 1320 px content shell
- Responsive breakpoints: 1240, 980, and 620 px
- No horizontal overflow by design
- Reduced-motion preference disables reveal animation
- Appointment controls are real accessible buttons with `aria-expanded`
- Raw source photography remains outside Git; only approved optimized derivatives
  are committed
