# Content Strategy and Sitemap Proposal

Status: proposed for content workshop

Primary launch language: English

Other languages: Persian and Arabic

## Strategic direction

The website should present Dr. Ali Moradi as one coherent professional identity across four connected domains:

1. Clinical care
2. Research
3. Innovation
4. Education

It should not behave like four separate websites and should not reproduce the information architecture or visual design of the current website or the supplied Mobina concepts. Approved source material may be reused after editorial and factual review.

The primary homepage job is to help each visitor choose the correct path quickly while establishing verifiable professional credibility. Patients must reach care and appointment information without navigating through research content; researchers and institutional visitors must reach publications, projects, and collaboration information without navigating through patient content.

## Recommended primary navigation

- Clinical Care
- Research
- Innovation
- Education
- About
- News
- Language selector: EN / FA / AR
- Primary CTA: Make an Appointment

Use text language codes instead of a flag-only language control. A flag represents a country, not a language, and is ambiguous for both Persian and Arabic.

## Recommended homepage sequence

### 1. Header and hero

The hero should contain:

- Brand lockup
- Primary navigation and accessible language selector
- External `Make an Appointment` action
- A new photographic/art-directed portrait composition, not the current site or Mobina layout
- A concise, fact-checked professional statement
- One primary clinical CTA and one secondary `Explore his work` CTA
- Four professional facets represented by a consistent custom icon family

Recommended facet labels:

- Hand & Wrist Expertise
- Advanced Technology
- Innovation & Entrepreneurship
- Research Leadership

The supplied icon concepts may inform meaning but should not be copied stylistically. All hand illustrations should use one consistent orientation and visual grammar.

### 2. Choose your path

Four clear audience pathways:

- I need clinical care
- I want to explore research
- I want to explore innovations
- I am looking for education and training

This replaces the generic three-card block in the old concept and makes the four-domain structure understandable immediately.

### 3. Interactive story: From injury to restored function

A restrained scroll-driven narrative connects the four domains:

1. Understand the condition
2. Treat and reconstruct
3. Invent and improve
4. Teach and extend impact

The visual can transition from anatomical hand structures to clinical reconstruction, then to a device or prosthetic system, and finally to restored movement. It should be optional, keyboard accessible, performant on mobile, and replaced by a static sequence when `prefers-reduced-motion` is enabled.

### 4. Clinical expertise preview

Show a curated, patient-friendly subset rather than the full clinical catalogue:

- Trauma and emergencies
- Nerve and tendon conditions
- Hand and wrist disorders
- Reconstructive and microsurgery
- Congenital and pediatric hand conditions
- Sports and overuse injuries

Each item should lead to a structured condition or service page. Avoid diagnostic promises and outcome claims.

### 5. Selected innovation stories

Feature three evidence-rich projects at launch:

- External fixation systems
- Bionic hand and prosthetic control
- Magnetic distraction technology

Each story should explain the problem, the idea, the development status, Dr. Moradi's role, and the supporting patent/publication/evidence. Product imagery should come from the audited local library, with new visuals produced only where the source material cannot support the required composition.

### 6. Evidence and impact

Use a small number of verified metrics with explicit labels and an `as of` date. Current source candidates are:

- 150+ peer-reviewed articles
- 4 books
- 30 national patents
- 8 US patents

Counts must be reconciled with the latest CV before publication. Avoid decorative counters that animate from zero if they slow comprehension or imply live data.

### 7. Appointment gateway

Use three direct cards rather than an accordion:

- Emergency Appointment — In-Person Urgent
- Scheduled In-Person Appointment — Planned Visit
- Online Appointment — Teleconsultation / Priority Follow-up

Each card should state eligibility, timing, required preparation, and a single action. Booking remains outside this website. External destinations, opening behavior, and tracking must be documented.

Safety and clarity requirements:

- Urgent-visit content must not imply that a clinic visit replaces emergency services for life- or limb-threatening conditions.
- Replace `even-numbered days` with named weekdays and local timezone/calendar language after confirmation; the current phrase is ambiguous internationally.
- Replace promotional wording such as `an intelligent bridge` with direct patient instructions.
- If an urgent path has no booking URL, use `View urgent visit instructions`, `Call`, or `Get directions`, not `Book now`.

### 8. Latest research and news

Show three curated items with type labels such as Research, Recognition, Innovation, or Clinic Update. Use one content model for news and articles, with optional links to LinkedIn or external sources. Do not use social-media exports as final page content without editing, accessibility text, and source attribution.

### 9. About preview and contact footer

Use a concise biography and a link to the complete About page. Reserve `photo_2024-05-09_16-46-36.jpg` primarily for the About page as requested; only reuse it on the homepage if the final crop and page rhythm justify duplication.

The footer contains the full brand lockup, essential contact information, care locations, page links, social links, legal links, and language access. Do not repeat a long biography in the footer.

## Interaction model: scientific discovery, not game mechanics

Recommended:

- Scroll-linked line drawing and controlled image transitions
- Hotspots that reveal anatomical, clinical, or engineering layers
- Before/after states of a device or idea, never unverifiable patient outcomes
- A four-path interactive map connecting Care, Research, Innovation, and Education
- Subtle loading transitions and section reveals
- Clear completion states for forms

Avoid:

- Points, badges, streaks, leaderboards, or rewards
- Gamifying emergencies, diagnosis, surgery, or recovery
- Quizzes that appear to provide diagnosis
- Autoplay surgical footage
- Excessive parallax, scroll hijacking, or motion without a reduced-motion alternative
- Interactions whose content is unavailable to keyboard or screen-reader users

## Proposed sitemap

### Home

### Clinical Care

- Overview
- Conditions
  - Trauma and emergencies
  - Elective and degenerative conditions
  - Congenital and pediatric conditions
  - Reconstructive and microsurgery
  - Sports and overuse injuries
  - Tumors and infections
- Treatments and Services
- Care Locations
  - Private Office
  - Hospital Services
- Patient Resources
  - Before Surgery
  - After Surgery
  - Frequently Asked Questions
  - Brochures and Videos
- Appointment Guide

Conditions should use a reusable detail-page template. Only conditions with approved, patient-safe copy should be published at launch.

### Research

- Overview
- Clinical Research
- Orthopedic and Muscle Biomechanics
- Publications and Patents
- Ongoing Projects
- Research Team
- Collaboration and Opportunities
- Thesis Supervision
- Research Training and BJRL

### Innovation

- Overview
- External Fixation Systems
- Bionic Hand and Prosthetic Control
- Rehabilitation Robotics
- Magnetic Distraction Technology
- Innovation Ecosystem and Partners

Companies and initiatives such as Avisa, BJRL, Akam, EuWalk, Integrom, and Fixodyn belong in an ecosystem or portfolio view unless each has enough approved content to justify a dedicated page.

### Education

- Overview
- Lectures and Courses
- Workshops and Events
- Clinical Teaching and Cases
- Reading Lists, Manuals, and Videos
- Training Pathways

### About

- Biography
- Positions and Affiliations
- Awards and Recognition
- Downloadable CV

### News

- All News
- Article detail pages

### Contact

- Contact form
- Office and hospital locations
- Phone, email, WhatsApp, maps, and social links

### Utility and legal

- Search
- Privacy Policy
- Terms / Medical Disclaimer
- Accessibility Statement
- 404 page

## Claims requiring correction or explicit approval

The following supplied wording should not be published verbatim until it is reconciled with the CV and approved by Dr. Moradi:

- `Hand Surgeon | Harvard University`: the available CV supports a research fellowship at Massachusetts General Hospital and Harvard-affiliated experience, not necessarily a current Harvard position. Use a precise institution/role/date formulation.
- `PhD of Artificial Limbs`: the available CV terminology is `PhD in Orthotics and Prosthetics`. This is the safer professional wording.
- `Top Researcher`: this is subjective unless tied to a named award and year. Prefer `Research Leadership` or a verified metric.
- Any number of publications, books, patents, awards, or procedures: display only after source reconciliation and include an `as of` date where appropriate.

Suggested provisional hero credential line:

> Hand & Upper Extremity Surgeon<br>
> Research, Innovation, and Education in Hand Reconstruction<br>
> PhD in Orthotics and Prosthetics

The final line should be shorter after the homepage message hierarchy is approved.

## MVP page priority

### Launch-critical

- Home
- Clinical Care overview
- Conditions index and approved detail pages
- Care Locations
- Patient Resources overview plus Before Surgery, After Surgery, and FAQs
- Appointment Guide
- Research overview and Publications
- Innovation overview and three project stories
- Education overview
- About
- News index and article template
- Contact
- Privacy, disclaimer, accessibility, and 404

### Phase after launch

- Full research opportunity and thesis workflows
- Complete educational library
- Rich team profiles
- Advanced innovation ecosystem pages
- Search across the complete resource library
- Additional interactive scientific modules

## Content decisions still needed

1. Confirm the exact, legally and academically accurate hero credentials.
2. Provide the final external URL for each appointment pathway.
3. Replace `even-numbered days` with named weekdays and confirm timezone and holiday exceptions.
4. Confirm the latest publication, book, national patent, and US patent counts and their effective date.
5. Decide whether Research Team and Our Team are one shared team or distinct clinical and research teams.
6. Identify which conditions have approved patient-facing copy for MVP.
7. Confirm whether `Blog` should be called `News`, `Insights`, or `News & Insights`; `News` is the provisional recommendation.
8. Approve the first three innovation stories and the evidence available for each.

## Source review notes

- `Website Tree-2.docx` was reviewed both structurally and as rendered pages.
- The three handwritten sketches are sufficiently readable for information-architecture decisions. A few small annotations remain uncertain, but none blocks this proposal.
- The two icon reference images were found in the `template` folder rather than the originally supplied root paths.
- Newly added local media was detected and should be added to the media audit before visual production begins.
