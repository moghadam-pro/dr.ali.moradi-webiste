# Technical Direction

## Delivery model

Phase 1 is an interactive, client-facing website preview delivered as a production
build. The source implementation may use modern front-end technology and is not
restricted to pure HTML and CSS.

The architecture must still keep content, presentation, routing, and reusable page
patterns clearly separated so that a later WordPress implementation can reuse the
information architecture and front-end system without redesigning the product.

## Proposed stack

- **Astro:** content-focused multi-page routing and static production output
- **TypeScript:** safer data models, forms, and multilingual content contracts
- **React islands:** only for stateful or interaction-heavy components
- **Motion:** route transitions, scroll reveals, and purposeful micro-interactions
- **Zod:** shared form validation schemas
- **CSS custom properties and component styles:** portable design tokens and a
  WordPress-friendly visual layer

This stack is provisional until the page and content model are finalized, but it is
the default implementation direction.

## Routing and language model

- English is the default initial experience.
- English routes use the root path, for example `/about/`.
- Persian routes use `/fa/`, for example `/fa/about/`.
- Arabic routes use `/ar/`, for example `/ar/about/`.
- Each page provides explicit alternate-language links.
- Direction, language, typography, metadata, and structured data are set per route.
- Navigation must work in the compiled build without relying on a development
  server fallback.

## Interaction requirements

- Visible navigation progress during route changes
- Page-level loading treatment where content or media needs time to resolve
- Image placeholders or skeletons where appropriate
- Scroll-triggered reveals based on intersection, not arbitrary timers
- Respect for `prefers-reduced-motion`
- Hover, focus, pressed, disabled, success, and error states
- No animation that delays access to medical or appointment information

## Forms

Potential in-site forms such as contact, research-position, internship, or inquiry
forms must include:

- typed validation schemas
- inline, accessible field errors
- keyboard and screen-reader support
- submitting, success, and failure states
- spam-protection and backend integration boundaries
- a preview-safe submission mode until a real endpoint is approved

Appointment booking is not an in-site form. All appointment calls to action link to
an approved external booking destination.

## Build quality gates

- All internal links resolve in the production build
- All language routes render with the correct `lang` and `dir`
- No missing loading, empty, validation, success, or failure state
- No layout shift caused by font or primary media loading
- Responsive QA covers desktop, tablet, and representative mobile widths
- Keyboard navigation and reduced-motion behavior are tested
- The final build can be hosted as static files

