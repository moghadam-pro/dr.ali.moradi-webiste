# Decisions Log

Chronological record of decisions made in the planning conversation, before
any code was written. Dates are approximate to the working session
(2026-09-02).

## 1. Starting question

The operator (a doctor's clinic assistant, not technical) needs to be able
to change almost anything on the site without help. The site must stay
trilingual (EN/FA/AR, RTL-aware) and SEO-optimized. Starting point was the
existing Next.js/Vite demo at https://dralimoradi.moghadam.pro/, whose
README already stated the intent: "A structure that can be converted into a
WordPress theme without a rewrite."

## 2. First architecture pass (page-builder based) — superseded

Initial proposal: custom lightweight theme + Elementor Pro for layout,
ACF Pro for structured fields, Polylang Pro and Rank Math Pro for
multilingual/SEO, WPForms for forms, WP Rocket/Wordfence/UpdraftPlus for
performance/security/backup. This was presented as a first draft and was
explicitly superseded by the next round of requirements — recorded here
only for context, not as the implemented plan.

## 3. Requirements tightened — final architecture

The operator specified, directly:

1. **Dedicated theme settings** — everything controllable from theme
   settings.
2. **No page is part of the theme except the homepage** — every other page
   must be a real WordPress Page.
3. **General templates** define how pages are structured/displayed.
4. **Header and footer** are edited once, in theme settings, and used by
   every page.
5. **Menus** are managed in WordPress's own Menus screen.
6. **Anything that can naturally be a WordPress Post** (chronological,
   blog-like content) should be a Post, not a custom type.
7. **Custom post types** that are genuinely needed (e.g. team members) ship
   bundled with the theme.
8. **All forms** — contact and research-collaboration alike — go through
   the operator's own **MPro Forms** plugin.
9. **Only two off-the-shelf plugins are allowed, and only their free
   tiers**: Polylang and Rank Math. No Elementor, no ACF, no Pro upgrades.

This ruled out the page-builder/ACF draft (points 1 and 9 are incompatible
with Elementor/ACF) and pointed to a native WordPress **block theme / Full
Site Editing** approach — see `architecture.md` for the resulting design and
the reasoning for each structural choice (why block theme instead of
Customizer, why native custom fields instead of ACF, how Polylang free
covers what would normally need Pro, etc.).

## 4. Infrastructure decisions

- **Backups**: host/server-level. No backup plugin.
- **Caching & security**: Cloudflare-level. No cache/security plugin.
- **Domains**: build happens on a temporary install, `tmp.saveon.me`. The
  live production domain `dralimoradi.com` is untouched until the build is
  approved, at which point the whole temporary site is backed up as a unit
  and restored onto the production domain — so nothing in the theme or
  content may hardcode the temporary domain.

## 5. Build process decisions

- Development happens on branch `claude/wordpress-doctor-site-architecture-m4766g`
  in `moghadam-pro/dr.ali.moradi-webiste`; documentation is kept on a
  separate `docs` branch, under `wordpress-theme/`, so implementation and
  documentation histories don't mix.
- Content is migrated by script, in bulk (extract once from the existing
  `app/*.ts` content files, import once into WordPress), not page-by-page
  by hand — see `content-migration-plan.md`.
- Every commit carries a mandatory `Co-Authored-By` footer and session link
  by platform policy; this cannot be suppressed even though the operator
  asked for no trace of the assistant in the repository. Flagged back to
  the operator rather than silently either violating the platform
  requirement or ignoring the operator's instruction.
- No browser-automation tool is available in this environment, so instead
  of an interactive login session, WordPress **Application Passwords**
  (REST API) and/or SSH/SFTP + WP-CLI are used to deploy the theme and
  import content — functionally equivalent, and better suited to a
  one-shot bulk import than a driven browser session.
- Final acceptance test: compare the finished temporary site, page by page
  and language by language, against the live reference at
  https://dralimoradi.moghadam.pro/ for content parity.
