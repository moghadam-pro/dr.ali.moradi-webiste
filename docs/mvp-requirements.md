# MVP Requirements

## Confirmed scope

### Content areas

- Dr. Moradi introduction and biography
- Hand-surgery expertise and services
- Research and publications
- Inventions and innovations
- Rehabilitation robotics and prostheses
- Avista company introduction with an external link
- Appointment information with links to external booking services
- Contact information
- News or articles, pending final confirmation

### Languages and direction

- English (`ltr`) — primary and default initial language
- Persian (`rtl`)
- Arabic (`rtl`)

Language versions must share the same design system while allowing for differences
in copy length, typography, and content availability.

### Front-end delivery

- Desktop-first layout
- Full responsiveness across current mobile screen sizes
- Semantic HTML
- Component-based implementation with a static production build
- Maintainable styling and typed application logic
- Fully working navigation between all implemented pages in the compiled preview
- Page-transition and route-loading feedback
- Loading, empty, success, and error states where applicable
- Purposeful motion and interaction
- Scroll-triggered content reveals with reduced-motion support
- Working forms with client-side validation and accessible error feedback
- Keyboard-accessible interaction and visible focus states
- Baseline metadata and semantic structure for SEO
- File and component organization suitable for later WordPress theme conversion

## Explicitly out of scope for Phase 1

- Ecommerce and medical-equipment sales
- Online payments
- Custom appointment calendar or booking engine
- Appointment-management dashboard
- SMS notifications
- Synchronization with third-party appointment availability

## Appointment behavior

Phase 1 explains appointment options and provides a clear external booking link.
Appointment booking is entirely outside Dr. Moradi's website. The website does not
store appointments, expose time-slot availability, or implement booking forms.

The discovery call mentioned in-person, online, and emergency appointment paths.
Their exact definitions, eligibility, schedules, and destination links remain open.

## Content and safety constraints

- Medical claims must use client-approved wording.
- Credentials, affiliations, publications, and invention details require verification.
- Emergency language must not imply a level of availability or medical service that
  has not been formally confirmed.
- Personal and clinical contact information must be approved before publication.

## Open decisions

- Final sitemap and page count
- News/articles inclusion in the MVP
- Whether all three language versions launch simultaneously
- Translation owner and approval process
- Visual direction, color system, and typography
- Exact appointment pathways and external URLs
- Content model for publications, inventions, projects, and articles
- Analytics, cookie, and privacy requirements
- Hosting and deployment approach

## Typography

- English: Manrope
- Persian: Vazirmatn
- Arabic: Vazirmatn

Font loading must avoid layout shift, include suitable fallbacks, and preserve
readability across Latin, Persian, and Arabic content.
