# Dr. Ali Moradi — Personal Brand Website

A multilingual personal-brand website for Dr. Ali Moradi, bringing his clinical,
scientific, innovation, rehabilitation-robotics, and entrepreneurial work into one
coherent digital presence.

## Project status

The approved React demo is the active foundation and is maintained on `main`.
Development now proceeds one page at a time, beginning with the homepage. The
current homepage refresh preserves the existing component architecture and icon
set while updating the header, typography, and hero composition.

The exact pre-refresh version that was running on the online demo is preserved at:

```text
backup/live-react-before-home-refresh-2026-08-10
7c6f9bc9d8d190c481ece8c81568e91e210fedc5
```

Design source: [Dr. AliMoradi — website v1.0.0 on Figma](https://www.figma.com/design/UCIClfqLygF9UTERhXzawE/Dr.-AliMoradi---website-v1.0.0?node-id=2071-27750&t=BnaFPMxOBdiCwy18-1)

## Product principles

- Desktop-first presentation with full responsiveness across mobile screen sizes
- English, Persian, and Arabic support, with English as the default
- Correct RTL/LTR behavior per language
- Semantic, accessible HTML with maintainable CSS
- Purposeful, accessible interaction using typed JavaScript where needed
- A structure that can be converted into a WordPress theme without a rewrite

## Technical architecture

- React 19 and TypeScript
- Next-compatible App Router components compiled with Vinext/Vite
- Shared content model for English, Persian, and Arabic
- Locally bundled Inter, Vazirmatn, and Scheherazade New fonts
- Lucide icon components; the approved homepage icon choices must remain stable
- Responsive CSS, loading states, scroll-reveal motion, and reduced-motion support
- External appointment flow through `https://nobat.ir/9705` only

The React structure remains the source of truth for the demo. A later WordPress
handoff can translate these components into templates and Elementor sections
without changing the approved visual or content architecture.

## Current homepage scope

- Header order: Product, Clinic, Innovation, Research, Education, About me, Blog
- Header navigation labels are intentionally non-navigating during page-by-page review
- Language trigger is text-only; flags remain inside the language menu
- Full-width laboratory hero using `public/media/hero/hero-bg.png`
- Existing four facet icons preserved and moved into the hero
- Hero CTA removed; appointment CTA remains in the header
- Section labels use an orange rule and no numeric prefix
- Pathways contains three routes: Clinical Care, Innovation, and Research
- Connected Practice is rebuilt responsively from the four approved source images, with linked directional steps on desktop and mobile
- The former homepage Clinical Care detail section has been removed
- Desktop, tablet, mobile, LTR, and RTL layouts are maintained

Other pages remain in the repository but will be corrected individually in later
review rounds.

## Run the demo

Requirements: Node.js 22.13 or newer.

```bash
npm install
npm run dev
```

Open `http://localhost:3000`.

## Production build

```bash
npm run build
npm run start
```

The demo is self-contained. It does not read from or write to the current
`dralimoradi.com` host.

## Documentation

- [Project discovery](docs/project-discovery.md)
- [MVP requirements](docs/mvp-requirements.md)
- [Source content inventory](docs/source-content-inventory.md)
- [Approved website content audit](docs/approved-website-content-audit.md)
- [Visual and image constraints](docs/visual-image-constraints.md)
- [Brand system](docs/brand-system.md)
- [CV content summary](docs/cv-content-summary.md)
- [Innovation source links](docs/innovation-links.md)
- [Media library audit](docs/media-library-audit.md)
- [Certificates and clinical assets audit](docs/certificates-and-clinical-assets-audit.md)
- [Content strategy and sitemap](docs/content-strategy-and-sitemap.md)
- [Technical direction](docs/technical-direction.md)
- [Delivery roadmap](docs/roadmap.md)
- [Demo V1 implementation](docs/demo-v1-implementation.md)

## Working process

Each approved milestone is developed, reviewed, documented, and committed as a
separate logical step. Before a major visual revision, the last approved state is
preserved in a dated backup branch. This keeps the Git history readable, makes
rollback straightforward, and prepares the repository for client handoff.
