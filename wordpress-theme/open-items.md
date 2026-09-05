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
5. ~~**RTL display bug in the contact-info block.**~~ Fixed 2026-09-03:
   added `dir="ltr"` to the phone number link in
   `blocks/contact-info/render.php`.
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

9. ~~**Visual/CSS design pass.**~~ Fully resolved 2026-09-05: every
   page now has its dedicated design, ported directly from the
   reference site's own source (this repo's `main`/`app/*`) with
   matching CSS, copy (en/fa/ar), and RTL mirroring — homepage,
   header/footer, About, Contact, Research, Innovation (earlier
   2026-09-05 entries), and now also the **Clinical Care hub**
   (pathway cards + real team grid + two rotating/lightbox case
   galleries), **Blog archive + single-post pages** (real WP post
   content), and **team-member profile pages**. See progress-log.md's
   "Clinical Care hub, blog, and team-profile pages completed" entry
   for the pages/images created and the bugs found verifying it live.

10. ~~**Menu location assignment needs a manual finishing step.**~~ See
    item 6 above — same fix category, listed once.
11. **Polylang has no per-language mechanism for the static front page
    or posts page.** Fixed in the theme's own code (2026-09-05):
    `dam_translate_front_page_option()` in `inc/polylang.php` filters
    `option_page_on_front`/`option_page_for_posts` to the current
    language's translation. One remaining, non-blocking cosmetic
    side-effect: visiting `/fa/` or `/ar/` 301-redirects once to that
    language's front-page-placeholder's own canonical URL before
    rendering the homepage (content/functionality unaffected). See
    `progress-log.md`'s 2026-09-05 entry for what was tried and why an
    empty placeholder slug (a possible fix) wasn't attempted.

12. **MPro Forms contact form built; its labels aren't localized —
    and on Arabic, part of it silently falls back to Persian, not
    English.** A real form (`[mpro_form id="1"]`, built by hand — MPro
    Forms has no REST/CLI API) is embedded on all three Contact pages,
    fields matching `homepage-content.json`'s `contact` object. The
    fixed field labels ("Full name", "Enquiry type", "Subject",
    "Message", etc.) stay in English on every locale, as previously
    noted. Found this session, verified live on `/ar/contact-ar/`: two
    of the form's own *configurable* values — the "Enquiry type"
    dropdown's placeholder option and the consent checkbox's label —
    **do** vary per language (English page shows "Select" / "Yes";
    Persian page shows "انتخاب کنید" / "بله"), but the Arabic page also
    shows the Persian text "انتخاب کنید" / "بله" verbatim instead of an
    Arabic translation or an English fallback. This points to the form
    being built with a Persian base value for these two fields and an
    English translation added on top, with no Arabic translation ever
    added — so Arabic falls through to the Persian raw value rather
    than to English. This is inside MPro Forms' own form-builder
    configuration (Forms → the "Contact us" form → its field options),
    not the theme's code, so it needs an Arabic translation added
    there — flagged for the operator/form owner rather than guessed
    at or edited here.

## Content decisions needed before Education can be built

7. **Education has no content anywhere.** Neither `tmp.saveon.me` nor
   the live reference site (`https://dralimoradi.moghadam.pro/education`,
   which falls back to a generic page) has any real Education copy —
   not even homepage teaser text the way Clinical Care/Innovation/
   Research have. No Education hub page or nav item was created rather
   than inventing content. Needs real copy from the operator.
8. ~~**No "all posts" listing page exists.**~~ Resolved 2026-09-05: a
   real "Blog" page (label as-is from `homepage-content.json`'s `nav`
   array — the final wording is still an open content decision, see
   below) now exists in all three languages via Settings → Reading's
   "Posts page" mechanism, and the "Blog" nav item links to it in all
   three Primary menus.

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
