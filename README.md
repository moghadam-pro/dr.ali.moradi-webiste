# Dr. Ali Moradi — Personal Brand Website

A multilingual personal-brand website for Dr. Ali Moradi, bringing his clinical,
scientific, innovation, rehabilitation-robotics, and entrepreneurial work into one
coherent digital presence.

## Project status

The first interactive demo is implemented. It includes a complete English-first
homepage, fully translated Persian and Arabic experiences, source-backed internal
pages, responsive layouts, scroll reveals, loading states, the approved external
appointment pathway, and a validated email contact workflow.

## Product principles

- Desktop-first presentation with full responsiveness across mobile screen sizes
- Persian, English, and Arabic support
- Correct RTL/LTR behavior per language
- Semantic, accessible HTML with maintainable CSS
- Purposeful, accessible interaction using typed JavaScript where needed
- A structure that can be converted into a WordPress theme without a rewrite

## Phase 1 deliverable

The first production deliverable will be a deployable static build of a modern,
interactive, multilingual website. The implementation is not limited to pure
HTML/CSS. It may use a component framework, typed JavaScript, an animation library,
and a build tool as long as navigation and all preview functionality work in the
compiled output.

It will not include a custom CMS, ecommerce, payment, or an internal
appointment-booking system.

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
`dralimoradi.com` host. Appointment actions use only the approved external URL:
`https://nobat.ir/9705`.

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
separate logical step. This keeps the Git history readable and suitable for client
handoff.
