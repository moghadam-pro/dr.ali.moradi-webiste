# Open Items

Things that block further progress, or decisions still needed from the
operator. Updated as items are resolved.

## Resolved (2026-09-02, continued)

1. ~~**WordPress site access for `tmp.saveon.me`.**~~ Resolved without
   SSH/SFTP: the operator logged into wp-admin directly in a
   Claude-driven browser session, which was then used to upload the
   theme (Appearance → Themes → Upload Theme) and generate an
   Application Password (Users → Profile) for the REST-based content
   import — no server file access needed, since a plain zip upload
   through wp-admin covers theme installation, and Polylang/Rank Math
   were already installed on the site.
2. ~~**No browser-automation tool in this environment.**~~ One is
   available in this environment (unlike the session that wrote the
   items below) and was used for theme installation and spot-checking;
   the bulk content import still runs as a script via the REST API,
   which remains the right tool for a one-shot bulk import.

## Blocking

None currently — see `progress-log.md`'s "Not yet done" list for
sequenced next work (hub pages, menus, parity check) that needs content
decisions and time, not new access or approvals.

## Not blocking, but flagged

3. **Attribution footer.** Every commit in this repository carries a
   mandatory `Co-Authored-By: Claude Sonnet 5` line and a session link, by
   platform policy — this cannot be turned off, even though the operator
   asked for no trace of the assistant. Commit messages and documentation
   are written neutrally (no first-person narration) but the footer line
   stays.
4. **Theme Options left blank, not fabricated.** WhatsApp number and
   social media URLs (Instagram/Telegram/Aparat labels exist in
   `app/site-content.ts`'s footer copy, but not the actual links) are not
   present anywhere in the current content model. Fill in via Appearance
   → Theme Options once available.
5. **RTL display bug in the contact-info block.** The phone number
   visually reverses/reorders on `fa`/`ar` pages (a bidi/`dir` styling
   issue in `blocks/contact-info`'s markup, not a data problem — the
   stored value is correct). Needs a `dir="ltr"` or unicode-bidi-isolate
   wrapper around the number.

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
