# Homepage Customizer — theme 2.0.0

## Operator workflow

Open **Appearance → Customize**. There are three top-level panels, one per
language: **Homepage — English**, **صفحه نخست — فارسی**, and
**الصفحة الرئيسية — العربية**. Opening a panel switches the live preview to that
language's real homepage.

WordPress Customizer does not support nested panels, so the hierarchy is
panel (language) → section (homepage block) → controls. Sections are listed in
the same order the blocks appear on the homepage, and a release test fails if
the two orders ever diverge:

1. Hero
2. Connected Practice
3. Pathways
4. Innovation
5. Impact
6. Appointments
7. Recognition
8. About preview
9. Footer

Sections that repeat items (journey steps, pathway cards, impact items,
appointment options) group them under a heading inside the section.

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
- **About preview:** copy, image, both button labels, and both button URLs.
- **Footer:** logo, biography, booking action, column titles, email, phone, two
  addresses, map action, and the three social links (Instagram, Telegram,
  Aparat).

### Footer menus and the fixed bottom bar

The two footer link columns are ordinary WordPress menus, edited under
**Appearance → Menus**. Assign a menu to **Footer — Quick access** or
**Footer — Patient resources** for each language (Polylang lists the locations
once per language). If a language has no menu assigned yet, the footer shows the
built-in default links so the column is never empty.

The footer bottom bar (copyright, medical disclaimer, credit) is deliberately
not editable; it prints fixed localized text.

### Persian font in the controls pane

The Customizer controls pane uses WP-Parsidate's Vazir font whenever the
plugin's font option is enabled. WP-Parsidate only enqueues that stylesheet on
`admin_enqueue_scripts`, which WordPress does not fire on the Customizer screen,
so the theme enqueues it on `customize_controls_enqueue_scripts`.

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
3. Open each language panel and confirm the preview switches to EN/FA/AR.
4. Change one field in every area and confirm the preview updates after its
   refresh without publishing.
5. Verify dynamic and manual card modes, card counts, and 1/2/3-column grids.
6. Disable appointment entries and hero orbits, then verify their markup is
   absent.
7. Publish a test change, verify all three public homepages and mobile layouts,
   then restore the intended final content.
