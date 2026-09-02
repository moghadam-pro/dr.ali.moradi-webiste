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
6. **Menu location assignment needs a manual finishing step.** Polylang
   does not accept the core `/wp/v2/menus` REST field for its
   per-language locations, and a direct `set_theme_mod()` write (the
   theme's `/set-menu-location` bridge) doesn't take effect either —
   confirmed by testing that Polylang only properly links a menu to a
   location when its own classic Appearance → Menus screen is used to
   save it. After running the importer, each of the 6 menus must be
   opened once in Appearance → Menus and saved via that screen's "Save
   Menu" button before navigation actually appears on the front end.
   Already done once for the current `tmp.saveon.me` state; needed again
   only if the menus are recreated from scratch.

## Content decisions needed before Education and a "Blog" page can be built

7. **Education has no content anywhere.** Neither `tmp.saveon.me` nor
   the live reference site (`https://dralimoradi.moghadam.pro/education`,
   which falls back to a generic page) has any real Education copy —
   not even homepage teaser text the way Clinical Care/Innovation/
   Research have. No Education hub page or nav item was created rather
   than inventing content. Needs real copy from the operator.
8. **No "all posts" listing page exists, and building one is a design
   decision, not a mechanical step.** `front-page.html` is the theme's
   homepage and takes over the `/` URL, so there is currently no page a
   "Blog"/"News" nav item could point to. Options: a dedicated posts
   page at another slug (e.g. `/news/`, via Settings → Reading's "Posts
   page"), or something else — needs a decision before it's built, so
   the "Blog" nav item and a working posts archive are both still
   missing.

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
