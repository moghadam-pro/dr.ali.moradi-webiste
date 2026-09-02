# Open Items

Things that block further progress, or decisions still needed from the
operator. Updated as items are resolved.

## Blocking

1. **WordPress site access for `tmp.saveon.me`.** Deploying the theme's
   files and running the bulk content import both require either:
   - An **Application Password** for an administrator account (created
     from Users → Profile → Application Passwords in wp-admin — takes a
     few seconds, no developer needed), used against the REST API for
     content import, **and**
   - **SFTP/SSH access** (or equivalent file access) to upload the theme
     folder into `wp-content/themes/`, since there is no browser-automation
     tool available in this environment to use the Appearance → Themes
     upload screen interactively.

   Without at least the SSH/SFTP access, the theme cannot be installed on
   the temporary site; without the Application Password, content cannot be
   imported. Both are needed to complete the "upload it yourself" request.

2. **No browser-automation tool in this environment.** The request to open
   an internal browser session for wp-admin login isn't something this
   session can do — there is no such tool available. Application
   Passwords / SSH+WP-CLI are used instead, which is also more efficient
   for a bulk, scripted import than a driven browser session would be.

## Not blocking, but flagged

3. **Attribution footer.** Every commit in this repository carries a
   mandatory `Co-Authored-By: Claude Sonnet 5` line and a session link, by
   platform policy — this cannot be turned off, even though the operator
   asked for no trace of the assistant. Commit messages and documentation
   are written neutrally (no first-person narration) but the footer line
   stays.

## Content decisions still open (carried over from `docs/content-strategy-and-sitemap.md`)

These predate this WordPress work and still need Dr. Moradi's review before
final publish, independent of the platform migration:

- Hero credential line wording (Harvard affiliation, "PhD of Artificial
  Limbs" vs "PhD in Orthotics and Prosthetics").
- Final action/copy for emergency and online appointment modes.
- Confirmed publication/book/patent counts "as of" a current date.
- Whether the article/news section is labeled "Blog," "News," or
  "News & Insights."
- Which conditions have approved patient-facing copy for launch.
