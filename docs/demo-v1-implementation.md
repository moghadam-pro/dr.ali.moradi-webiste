# Demo V1 Implementation

## Status

The first interactive demo and its content/visual revision are implemented. The
current version passes the production build, TypeScript validation, route smoke
tests, server-rendered HTML tests, and browser checks at desktop and mobile widths.

The demo is self-contained in this repository. It does not connect to, read from,
or write to the current `dralimoradi.com` host or its data.

## Implemented experience

- English-first homepage at `/`
- Fully translated Persian experience under `/fa`
- Fully translated Arabic experience under `/ar`
- Functional internal routes for Clinical Care, Research, Innovation, Education,
  About, News, and Contact
- Locale-preserving navigation and language switching
- Responsive desktop, tablet, and mobile layouts
- Sticky desktop header and functional mobile menu
- Scroll-triggered section reveals
- Reduced-motion support
- Route loading state
- External appointment CTA using only `https://nobat.ir/9705`
- Confirmed emergency attendance on Saturday, Monday, and Wednesday from 15:45
  to 18:30
- Scheduled appointments linked only to `https://nobat.ir/9705`
- Validated contact form that prepares a message in the visitor's email app
- Medical and emergency disclaimer
- Complete favicon set, web manifest, browser tile configuration, canonical and
  language links, Open Graph metadata, Twitter card, robots file, and sitemap
- Local Manrope and Vazirmatn font packages; no runtime font dependency

## Homepage sections

1. Hero and working professional statement
2. Four professional facets
3. Audience pathways
4. Interactive narrative: From injury to restored function
5. Clinical expertise
6. Selected innovation stories
7. Working evidence metrics
8. Appointment pathways
9. Research and recognition updates
10. About preview and complete footer

## Image production and supplied brand assets

- Hero source: the approved `for-hero.png` supplied for this revision
- Edited derivative: `public/media/edited/dr-moradi-hero-v2.jpg`
- Social preview: `public/social-banner.jpg`, 1200 x 630
- Locale-aware supplied SVG logo sets are used in the header and footer
- The site icon package is installed under `public/icons`
- Homepage innovation cards use approved local or source-backed product images
- Homepage news cards use the supplied certificate archive rather than generic icons
- Raw sources remain outside the repository and unchanged
- The hero edit uses identity-preserving retouch, cool blue-gray tonal unification,
  controlled highlights, and a simplified operating-room environment
- Clinical before-and-after images are excluded from the demo pending patient
  consent and sensitive-content approval

## Knowledge-base additions

The new certificate archive was reviewed and grouped into:

- research and publication recognition
- innovation and invention recognition
- academic, editorial, and professional service
- teaching and research awards

Selected verified summaries are used across the News, Research, Innovation, About,
and Education pages. Content that would overstate affiliations or clinical claims
has been excluded.

## Validation completed

- Production build succeeds
- Lint completes without warnings or errors
- Homepage server-render test passes
- English internal route tests pass
- Persian and Arabic route tests pass
- Reduced-motion and mobile-breakpoint checks pass
- No horizontal overflow at 1280-pixel desktop and 390-pixel mobile viewports
- English, Persian, and Arabic pages use the correct logo and text direction
- Homepage image assets load successfully in the browser
- Contact validation reports all required errors before opening an email draft

## Next revision priorities

1. Review the new hero treatment and scientific wording with Dr. Moradi.
2. Confirm the final online-consultation pathway.
3. Add approved condition detail pages and downloadable patient resources.
4. Convert the structured publication and patent source into filterable content.
5. Add approved education videos and selected innovation detail media.
6. Complete the final production-domain handoff after editorial approval.
