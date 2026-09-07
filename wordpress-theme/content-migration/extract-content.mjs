#!/usr/bin/env node
// @ts-nocheck
/**
 * Extracts content from the current Next.js/Vite site's typed content
 * modules (app/*.ts) and writes normalized JSON for WordPress import.
 *
 * Why execute the modules instead of scraping text: the source files are
 * typed ES modules with nested arrays/objects, not markup — running them
 * and serializing the real exports is reliable against formatting changes
 * in a way regex extraction is not.
 *
 * Requires Node 22.13+ (already this repo's minimum) for built-in
 * TypeScript type-stripping — no extra dev dependency is added just for a
 * one-time migration script.
 *
 * Usage: node --experimental-strip-types wordpress-theme/content-migration/extract-content.mjs
 */

import { fileURLToPath, pathToFileURL } from "node:url";
import path from "node:path";
import fs from "node:fs/promises";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const repoRoot = path.resolve(__dirname, "../..");
const appDir = path.join(repoRoot, "app");
const outDir = path.join(__dirname, "export");

async function importAppModule(name) {
  const url = pathToFileURL(path.join(appDir, name)).href;
  return import(url);
}

function collectMediaPaths(value, into) {
  if (typeof value === "string") {
    if (value.startsWith("/media/")) {
      into.add(value);
    }
    return;
  }
  if (Array.isArray(value)) {
    value.forEach((item) => collectMediaPaths(item, into));
    return;
  }
  if (value && typeof value === "object") {
    Object.values(value).forEach((item) => collectMediaPaths(item, into));
  }
}

async function main() {
  await fs.mkdir(outDir, { recursive: true });

  const siteContent = await importAppModule("site-content.ts");
  const blogContent = await importAppModule("blog-content.ts");
  const structuredContent = await importAppModule("structured-content.ts");
  const aboutContent = await importAppModule("about-content.ts");

  const locales = ["en", "fa", "ar"];

  // --- Posts (News/Blog/Innovation/Research updates -> WordPress Posts) ---
  const posts = blogContent.contentPosts.map((post) => ({
    slug: post.slug,
    date: post.date,
    readMinutes: post.readMinutes,
    tags: post.tags,
    image: post.image,
    category: post.category, // { en, fa, ar }
    title: post.title, // { en, fa, ar }
    excerpt: post.excerpt, // { en, fa, ar }
  }));
  await fs.writeFile(path.join(outDir, "posts.json"), JSON.stringify(posts, null, 2));

  // --- Team members -> CPT team_member ---
  const team = structuredContent.teamMembers.map((member) => ({
    slug: member.slug,
    name: member.name,
    role: member.role,
    summary: member.summary,
    bio: member.bio,
    image: member.image,
    areas: member.areas,
  }));
  await fs.writeFile(path.join(outDir, "team.json"), JSON.stringify(team, null, 2));

  // --- About page copy -> WordPress Page "About" (page.html template) ---
  await fs.writeFile(
    path.join(outDir, "about.json"),
    JSON.stringify(aboutContent.aboutPageCopy, null, 2)
  );

  // --- Homepage content (reference only) ---
  // front-page.html is embedded in the theme, not a WordPress Page, so
  // this is not imported as content; it is the source of truth for
  // finalizing the theme's hardcoded homepage copy and the Theme Options
  // defaults (impact numbers, appointment URL) during that follow-up pass.
  await fs.writeFile(
    path.join(outDir, "homepage-content.json"),
    JSON.stringify(siteContent.content, null, 2)
  );

  // --- Starter seeds for Condition / Innovation CPT entries ---
  // The current site only carries homepage teaser copy for these (title +
  // description), not full detail-page content. This seeds real starting
  // entries per locale from that teaser copy; each should be reviewed and
  // expanded with full patient-facing copy before publish (see
  // wordpress-theme/open-items.md on the docs branch).
  const conditions = {};
  const innovations = {};
  for (const locale of locales) {
    const localeContent = siteContent.content[locale];
    conditions[locale] = (localeContent.conditions || []).map(([title, text]) => ({ title, text }));
    innovations[locale] = (localeContent.innovations || []).map(([category, title, text]) => ({
      category,
      title,
      text,
    }));
  }
  await fs.writeFile(path.join(outDir, "conditions-seed.json"), JSON.stringify(conditions, null, 2));
  await fs.writeFile(path.join(outDir, "innovations-seed.json"), JSON.stringify(innovations, null, 2));

  // --- Media manifest: every /media/... path referenced by the above ---
  const mediaPaths = new Set();
  collectMediaPaths(posts, mediaPaths);
  collectMediaPaths(team, mediaPaths);
  collectMediaPaths(siteContent.content, mediaPaths);

  const manifest = [...mediaPaths].sort().map((relPath) => ({
    relPath,
    sourceFile: path.join(repoRoot, "public", relPath.replace(/^\//, "")),
    filename: path.basename(relPath),
  }));
  await fs.writeFile(path.join(outDir, "media-manifest.json"), JSON.stringify(manifest, null, 2));

  console.log(`Extracted: ${posts.length} posts, ${team.length} team members, ${manifest.length} media files.`);
  console.log(`Output written to ${path.relative(repoRoot, outDir)}/`);
}

main().catch((error) => {
  console.error(error);
  process.exitCode = 1;
});
