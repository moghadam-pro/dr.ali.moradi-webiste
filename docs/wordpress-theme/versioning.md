# WordPress Theme Versioning

The custom theme follows Semantic Versioning (`MAJOR.MINOR.PATCH`).

- `MAJOR`: a breaking content model, template contract, or operator workflow
  change that needs a migration or explicit deployment notes.
- `MINOR`: a backward-compatible feature, template, block, or admin capability.
- `PATCH`: a backward-compatible bug, accessibility, content-display, or
  security fix.

## Single source of truth

`wordpress-theme/dr-ali-moradi/style.css` is the canonical version source.
WordPress reads it for the Appearance screen, and `functions.php` reads the same
header with `wp_get_theme()` for CSS, JavaScript, and image cache-busting.

Do not hard-code a second version constant. A release is incomplete if the
theme header and changelog disagree.

## Release checklist

1. Select the next SemVer number from the change scope.
2. Update `Version:` in the theme `style.css` header.
3. Move release notes from `Unreleased` into a dated changelog section.
4. Run PHP syntax checks and the relevant project tests.
5. Build the installable theme archive from the exact release commit.
6. Tag the commit as `theme-vMAJOR.MINOR.PATCH` and push the commit and tag.
7. Record the commit, archive checksum, deployment target, migration report,
   and verification result in `progress-log.md`.

## Production rule

The installed production theme must be exported or otherwise reconciled with
Git before replacement. A theme upload replaces the installed directory, so a
release archive must never be built from an older Git snapshot when production
contains uncommitted files.
