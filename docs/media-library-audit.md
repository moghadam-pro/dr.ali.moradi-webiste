# Media Library Audit

## Source library

`pictures and videos/`

The reviewed library contains 73 JPEG photographs, 8 HEIC photographs, and 13 MOV
files (including the logo motion), approximately 753 MB in total.

Raw media is intentionally not committed to Git. Approved, optimized derivatives
will be created only when page compositions and responsive crops are known.

## Photography groups

### Office and documentary portraits

- `DSC00641-DSC00652`
- Informal office scenes, desk work, and close portraits
- Useful for About, biography, editorial, or process storytelling

### External-fixator product photography

- `DSC00653-DSC00659`
- Device packaging, close details, and device on a skeletal hand model
- Strong candidate for innovation and external-fixator pages

### Clinical white-coat portraits

- `DSC00670-DSC00673`
- Clean, neutral portraits suitable for clinic and About contexts

### Studio/interview portrait series

- `DSC00687-DSC00708`
- Multiple wardrobe and lighting variants in navy, green, and white coat
- Strong candidates for hero, research, and profile compositions

### Formal and professional portrait series

- `DSC00714-DSC00733`
- White coat, suit, seated interview, and desk scenes
- Strong candidates for About, Education, leadership, and long-form editorial pages

### Additional portraits and demonstration scenes

- `DSC00741-DSC00747`
- Includes casual portraits and a product demonstration

### Surgery photographs

- `IMG_6294`, `IMG_6298`, `IMG_6299`, `IMG_6311`, `IMG_6313`, `IMG_6314`
- Authentic operating-room material
- Use only in appropriate clinical detail contexts, never as an autoplay or
  unexpected landing-page visual
- Provide a sensitive-content decision before prominent use

### Signage, recognition, and event material

- `IMG_7347`, `IMG_7349`: Dr. Moradi holding branded signage
- `DSC00686`: award/trophy
- `photo_2024-05-09_16-46-36.jpg`: group/event photograph

## Video groups

- `IMG_5410-IMG_5414`: close product/device demonstrations
- `IMG_5415-IMG_5421`: Dr. Moradi writing/signing in the office
- `logo-motion.mov`: existing logo animation on a dark background

Before web use, videos need orientation normalization, trimming, color review,
poster frames, captions where speech exists, muted/autoplay decisions, and modern
web encodes. The existing logo motion uses the old lockup and should not be reused
unchanged after the new English lockup is adopted.

## Initial shortlist

- Hero/profile exploration: `DSC00690-DSC00705`, `DSC00719-DSC00725`
- Clinic and credentials: `DSC00670-DSC00673`, `DSC00714-DSC00721`
- Innovation/product: `DSC00653-DSC00659`, `DSC00746-DSC00747`
- Research/leadership: `DSC00687-DSC00694`, `DSC00729-DSC00733`
- Surgery detail pages: `IMG_6294-IMG_6314`

## Production rules

- Preserve original files as the master source.
- Apply EXIF orientation before export.
- Never overwrite raw media.
- Export responsive AVIF/WebP/JPEG derivatives from selected originals.
- Define focal points and alt text per usage, not per file globally.
- Confirm image rights and patient consent before any identifiable clinical use.
- Avoid using multiple near-identical portraits on the same page.

## First-demo derivatives

- `public/media/edited/dr-moradi-hero-v1.png`
  - Source: `DSC00720.JPG`
  - Purpose: homepage hero portrait
  - Processing: EXIF/orientation normalization, identity-preserving professional
    retouch, simplified blue-gray studio environment, tonal matching, and responsive
    crop preparation
  - Integrity rule: the source master remains unchanged; future revisions must keep
    Dr. Moradi's identity, age, facial structure, hands, clothing, and lapel badge
    consistent with the source
- `public/og.png`
  - Purpose: 1200 x 630 social preview
  - Uses the approved first-demo portrait treatment and the exact English message:
    `Dr. Ali Moradi` / `Hand Surgery · Research · Innovation`

No clinical before-and-after images are used in the first demo. See
`certificates-and-clinical-assets-audit.md` for the consent and sensitive-content
gate that applies to those files.
