# Homepage Customizer — theme 1.1.0

## Operator workflow

Open **Appearance → Customize → Homepage Content — تغییر محتوای صفحه نخست**.
The panel contains one section for English, Persian, and Arabic. Opening a
language section changes the Customizer preview URL to that language's real
homepage, so saved and unsaved edits are reviewed in the correct locale.

WordPress Customizer does not support nested panels. The language entries are
therefore native sections inside one panel, with visual headings separating
Hero, Connected Practice, Pathways, Innovation, Impact, Appointments,
Recognition, About, and Footer controls.

## Editable content

- **Hero:** copy, credentials, facets, background image, and circular-orbit
  visibility.
- **Connected Practice:** section copy and link plus independent number,
  eyebrow, title, and image fields for all four stages.
- **Pathways:** section copy plus title, body, button label, and URL for each
  of three cards.
- **Innovation and Recognition:** section copy, one-to-three card count,
  one-to-three grid columns, and either the latest posts from a selected
  category or up to three manually selected posts.
- **Impact:** section title/subtitle and four independent value/label pairs.
- **Appointments:** section image/copy and separate enable, eyebrow, title,
  and description controls for all four appointment modes.
- **About:** copy, image, both button labels, and both button URLs.
- **Footer:** logo, biography, booking action, all Explore and Resource links,
  headings, email, phone, two addresses, map action, three social links,
  copyright, medical disclaimer, and site credit.

## Compatibility and data storage

Settings are stored as locale-suffixed theme mods such as
`dam_hero_background_image_fa`. Existing delimiter-based values for Connected
Practice, Pathways, and Appointments are read as defaults after upgrade, but
new edits are stored in independent fields. No database migration is required.

All user-provided text, URLs, images, checkboxes, and select values have
Customizer sanitization callbacks. Dynamic post queries are limited to
published Posts; Polylang translations are resolved when available.

## Release verification

1. Run PHP syntax checks for every theme PHP file.
2. Run `php tests/wordpress-theme-release.php`.
3. Open each language section and confirm the preview switches to EN/FA/AR.
4. Change one field in every area and confirm the preview updates after its
   refresh without publishing.
5. Verify dynamic and manual card modes, card counts, and 1/2/3-column grids.
6. Disable appointment entries and hero orbits, then verify their markup is
   absent.
7. Publish a test change, verify all three public homepages and mobile layouts,
   then restore the intended final content.
