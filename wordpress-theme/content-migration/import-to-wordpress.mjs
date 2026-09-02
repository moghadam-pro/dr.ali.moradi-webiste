#!/usr/bin/env node
/**
 * Imports the JSON produced by extract-content.mjs into a WordPress site
 * via the REST API, using an Application Password.
 *
 * NOT YET RUN — this script is scaffolded but execution is pending
 * WordPress site credentials for https://tmp.saveon.me/ (an Application
 * Password for an administrator account). See wordpress-theme/open-items.md
 * on the docs branch for what is needed and why a REST-API import was
 * chosen over the classic WXR importer.
 *
 * Usage (once credentials are available):
 *   WP_BASE_URL=https://tmp.saveon.me \
 *   WP_USER=admin \
 *   WP_APP_PASSWORD="xxxx xxxx xxxx xxxx xxxx xxxx" \
 *   node wordpress-theme/content-migration/import-to-wordpress.mjs
 *
 * Import order matters (see content-migration-plan.md): media first (so
 * attachment IDs exist), then CPT entries, then Posts, then Pages, then
 * Theme Options, then the redirect map. Each step is idempotent — safe to
 * re-run — by looking up an existing entity by slug/meta before creating
 * a new one.
 */

import fs from "node:fs/promises";
import path from "node:path";
import { fileURLToPath } from "node:url";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const exportDir = path.join(__dirname, "export");

const WP_BASE_URL = process.env.WP_BASE_URL;
const WP_USER = process.env.WP_USER;
const WP_APP_PASSWORD = process.env.WP_APP_PASSWORD;

function requireEnv() {
  const missing = ["WP_BASE_URL", "WP_USER", "WP_APP_PASSWORD"].filter((key) => !process.env[key]);
  if (missing.length) {
    console.error(`Missing environment variables: ${missing.join(", ")}`);
    console.error("This script cannot run yet — see wordpress-theme/open-items.md.");
    process.exit(1);
  }
}

function authHeader() {
  const token = Buffer.from(`${WP_USER}:${WP_APP_PASSWORD}`).toString("base64");
  return { Authorization: `Basic ${token}` };
}

async function wpRequest(endpoint, options = {}) {
  const response = await fetch(`${WP_BASE_URL}/wp-json/wp/v2/${endpoint}`, {
    ...options,
    headers: {
      "Content-Type": "application/json",
      ...authHeader(),
      ...(options.headers || {}),
    },
  });
  if (!response.ok) {
    const body = await response.text();
    throw new Error(`WP REST ${endpoint} -> ${response.status}: ${body}`);
  }
  return response.json();
}

async function readJson(name) {
  return JSON.parse(await fs.readFile(path.join(exportDir, name), "utf8"));
}

/**
 * Uploads one media file and returns its attachment ID. Looks up by
 * filename first (via the "search" param) so re-running the import does
 * not create duplicate attachments.
 */
async function ensureMedia(entry) {
  const existing = await wpRequest(`media?search=${encodeURIComponent(entry.filename)}`);
  if (existing.length) {
    return existing[0].id;
  }

  const fileBuffer = await fs.readFile(entry.sourceFile);
  const uploaded = await wpRequest("media", {
    method: "POST",
    headers: {
      "Content-Disposition": `attachment; filename="${entry.filename}"`,
      "Content-Type": "application/octet-stream",
    },
    body: fileBuffer,
  });
  return uploaded.id;
}

/**
 * Creates (or finds) one post/CPT entry for a single locale, then links
 * the three locale versions together as Polylang translations via the
 * `lang` and `translations` fields Polylang's REST integration exposes
 * once "Custom Post Types" translation is enabled for that type.
 */
async function ensureTranslatedEntry(postType, locale, data, translationGroup) {
  // Deliberately left as a documented stub: the exact Polylang REST
  // field names depend on the site's enabled languages, which are only
  // known once wp-admin access exists. Fill in once credentials land.
  throw new Error("ensureTranslatedEntry: implement once WordPress site access is available.");
}

async function main() {
  requireEnv();

  const media = await readJson("media-manifest.json");
  console.log(`Would upload ${media.length} media files.`);

  const posts = await readJson("posts.json");
  console.log(`Would create ${posts.length} posts x 3 locales.`);

  const team = await readJson("team.json");
  console.log(`Would create ${team.length} team_member entries x 3 locales.`);

  console.log("\nThis is a dry-run stub. Wire up ensureTranslatedEntry() once WP_BASE_URL/WP_USER/WP_APP_PASSWORD are available and the target site's Polylang languages are confirmed, then re-run for real.");
}

main().catch((error) => {
  console.error(error);
  process.exitCode = 1;
});
