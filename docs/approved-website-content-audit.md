# Approved Website Content Audit

## Source and authority

The current public website at `https://dralimoradi.com/` is an approved content
source according to the project owner. Its visual design, layout, component choices,
and technical structure are explicitly not references for the redesign.

Audit date: 2 August 2026.

## Default-language decision

The redesign opens in English by default. Persian and Arabic are separate localized
route trees. Current content availability differs by language, so the launch plan
must distinguish approved source copy from copy that still needs translation.

## Public content architecture found

### Primary pages

- Home
- Clinic
- Clinic Service
- Hospital Services
- Appointment Booking
- Research
- Clinical Research
- Biomechanics
- Robotics
- Education
- News / Blog
- About Me

### Robotics and product pages

- Bionic Hand H3
- Bionic Hand H5
- Motor Control
- Integrated Stem / Radius Segmented Intramedullary Stem
- Lower Body Exoskeleton (LEXA)
- Hip Exoskeleton (HEXA)
- Magnetic Distractor
- Intra-osseous Distal Radioulnar Prosthesis
- External Fixators

### Education

- Externship
- Internship
- Orthopedics Residency
- Hand Fellowship
- Orthopedic Essentials for Medical Interns
- Orthobox application
- Internship/thesis-supervisor request form

### People and teams

The current website includes individual pages for clinic staff, research fellows,
laboratory researchers, and engineering collaborators. These can become a reusable
People content type rather than individually designed one-off pages.

## Approved biography baseline

The current About page states that Dr. Ali Moradi:

- received his MD from Mashhad University of Medical Sciences in 2005
- completed orthopedic residency at MUMS from 2005 to 2009
- was a Hand and Upper Extremity research fellow at Massachusetts General Hospital
  from 2013 to 2015
- received clinical fellowships in Hand and Microsurgery and in Hand and Wrist
  Arthroscopy in 2018
- completed PhD studies on Artificial Limbs at MUMS in 2019
- is an associate professor of Orthopedics
- holds leadership roles in hand surgery, the Bone and Joint Laboratory, the
  Orthopedic Research Center, and the MUMS Technology Committee
- is CEO of Avisa Medical Innovators of East

The final English copy may be edited for clarity and consistency without changing
the approved factual meaning.

## Approved publication baseline

The current About page states:

- more than 150 journal articles
- 4 co-authored published books
- 30 national patents
- 8 US patents
- more than 19 years of general orthopedics experience
- 11 years of hand-surgery experience

These figures supersede the unverified `157 / 4 / 433` values shown in earlier
design screenshots. A publication-stat component should use centrally managed data
so the same value appears everywhere.

## Clinical scope found

The approved source covers:

- hand, wrist, and upper-extremity evaluation and care
- nerve compression disorders
- tendon and ligament conditions
- joint arthritis
- sports and overuse injuries
- fractures and dislocations
- deep cuts, crush injuries, and tendon/nerve/vessel trauma
- minimally invasive and surgical treatment
- clinic services and hospital-based services

The new information architecture should separate patient-friendly condition
content from service-location and appointment information.

## Research scope found

- Carpal tunnel syndrome and compressive neuropathies
- Distal radius fractures and DRUJ pathology
- Patient registries and outcome measurement
- Biomechanics and external-fixator development
- Magnetic joint distraction
- Intra-osseous prostheses
- Bionic hands and prosthetic motor control
- Rehabilitation robotics and exoskeletons
- Interdisciplinary research positions and fellowships

## Appointment decision

Although the current website contains an Appointment Booking page with multiple
appointment descriptions, the redesign does not implement appointment booking.
Appointment calls to action lead to an external approved booking service.

The useful patient-facing explanation of appointment types may be retained, but it
must not imply that submission occurs inside Dr. Moradi's website.

## Content-quality issues to correct during migration

The audit found implementation or editorial inconsistencies that must not be copied:

- Homepage publication counters resolve as zero while the About page provides
  non-zero approved totals.
- Some cards repeat unrelated descriptions.
- At least one team-member page appears to contain another person's biography.
- Some names have inconsistent spellings between headings and body copy.
- Several English passages contain spacing, grammar, pronoun, or punctuation errors.
- `Research` is misspelled as `Reseach` in one home-page heading.
- News and Blog terminology is inconsistent.
- Appointment language sometimes sounds like an internal booking workflow.

Approved facts should be preserved while copy defects and incorrect content
relationships are repaired.

## Migration principle

Content will be normalized into reusable structured records for pages, people,
clinical conditions, research areas, products, publications, news, and education.
The old page markup, layout, styling, and component structure will not be migrated.
