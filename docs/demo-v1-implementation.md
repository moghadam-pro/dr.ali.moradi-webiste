# Demo V1 Implementation

## Status

The first interactive demo is implemented and passes the production build, lint,
route smoke tests, and server-rendered HTML tests.

The demo is self-contained in this repository. It does not connect to, read from,
or write to the current `dralimoradi.com` host or its data.

## Implemented experience

- English-first homepage at `/`
- Persian homepage at `/fa`
- Arabic homepage at `/ar`
- Functional internal routes for Clinical Care, Research, Innovation, Education,
  About, News, and Contact
- Locale-preserving navigation and language switching
- Responsive desktop, tablet, and mobile layouts
- Sticky desktop header and functional mobile menu
- Scroll-triggered section reveals
- Reduced-motion support
- Route loading state
- External appointment CTA using only `https://nobat.ir/9705`
- Three appointment pathways with emergency and online copy clearly marked as
  provisional
- Validated demo contact form with a no-storage confirmation state
- Medical and emergency disclaimer
- Site-specific title, description, favicon, Open Graph, and social preview
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

## Image production

- Hero source: `DSC00720.JPG`
- Edited derivative: `public/media/edited/dr-moradi-hero-v1.png`
- Social preview: `public/og.png`, 1200 x 630
- Raw sources remain outside the repository and unchanged
- The hero edit uses identity-preserving retouch, tonal unification, and a simplified
  blue-gray studio environment
- Clinical before-and-after images are excluded from the demo pending patient
  consent and sensitive-content approval

## Knowledge-base additions

The new certificate archive was reviewed and grouped into:

- research and publication recognition
- innovation and invention recognition
- academic, editorial, and professional service
- teaching and research awards

Selected working summaries are used in the first-demo News and About pages. Exact
award names, dates, organizations, translations, and collaborators remain subject
to final verification.

## Validation completed

- Production build succeeds
- Lint completes without warnings or errors
- Homepage server-render test passes
- English internal route tests pass
- Persian and Arabic route tests pass
- Reduced-motion and mobile-breakpoint checks pass

## Next revision priorities

1. Review the visual direction and hero wording with Dr. Moradi.
2. Confirm complete Persian and Arabic editorial translations for internal pages.
3. Select and professionally process the next approved media set.
4. Replace provisional emergency and online appointment actions with final copy.
5. Add approved condition detail pages and patient resources.
6. Convert the structured publication and patent source into filterable content.
7. Confirm production host requirements and generate the final handoff build.
