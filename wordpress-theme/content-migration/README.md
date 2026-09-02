# Content Migration Scripts

See `wordpress-theme/content-migration-plan.md` on the `docs` branch for the
full plan and reasoning. Quick reference:

## 1. Extract (works now, no site access needed)

```bash
node --experimental-strip-types wordpress-theme/content-migration/extract-content.mjs
```

Reads `app/site-content.ts`, `app/blog-content.ts`, `app/structured-content.ts`,
and `app/about-content.ts` directly (executes the modules, not regex/text
scraping) and writes normalized JSON to `export/` (gitignored — regenerate
any time by re-running the script):

- `posts.json` — 23 blog/news/innovation articles, 3 locales each
- `team.json` — 8 team members
- `about.json` — full About page copy, 3 locales
- `homepage-content.json` — full homepage content dump (reference only —
  the homepage is a theme template, not an imported Page; used to finalize
  `templates/front-page.html` copy and Theme Options defaults)
- `conditions-seed.json`, `innovations-seed.json` — starter entries from
  the homepage teaser copy; each needs full patient-facing detail-page
  content added before publish (flagged in `wordpress-theme/open-items.md`)
- `media-manifest.json` — every referenced `/media/...` file with its
  source path in `public/`

Verified working: last run produced 23 posts, 8 team members, 16 media
files, no errors.

## 2. Import (scaffolded, blocked on credentials)

```bash
WP_BASE_URL=https://tmp.saveon.me \
WP_USER=<admin username> \
WP_APP_PASSWORD="<application password>" \
node wordpress-theme/content-migration/import-to-wordpress.mjs
```

`import-to-wordpress.mjs` is written and reads the export correctly, but
the WordPress-side translation-linking step (`ensureTranslatedEntry`) is
left as a documented stub — it depends on the target site's Polylang
configuration (which languages are registered, in what order), which is
only knowable once site access exists. See `wordpress-theme/open-items.md`.
