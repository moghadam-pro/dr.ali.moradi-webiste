import assert from "node:assert/strict";
import test from "node:test";

async function render(path = "/") {
  const workerUrl = new URL("../dist/server/index.js", import.meta.url);
  workerUrl.searchParams.set("test", `${process.pid}-${Date.now()}`);
  const { default: worker } = await import(workerUrl.href);

  return worker.fetch(
    new Request(`http://localhost${path}`, { headers: { accept: "text/html" } }),
    { ASSETS: { fetch: async () => new Response("Not found", { status: 404 }) } },
    { waitUntil() {}, passThroughOnException() {} },
  );
}

test("server-renders the completed homepage", async () => {
  const response = await render();
  assert.equal(response.status, 200);
  assert.match(response.headers.get("content-type") ?? "", /^text\/html\b/i);
  const html = await response.text();
  assert.match(html, /Dr\. Ali Moradi/);
  assert.match(html, /Advancing hand care through research and innovation/i);
  assert.match(html, /Product/);
  assert.match(html, /Clinic/);
  assert.match(html, /About me/);
  assert.match(html, /Where Surgery Goes Beyond Protocols/);
  assert.match(html, /\/media\/connected-practice\/01-injury\.jpg/);
  assert.match(html, /\/media\/connected-practice\/02-innovation\.jpg/);
  assert.match(html, /\/media\/connected-practice\/03-application\.jpg/);
  assert.match(html, /\/media\/connected-practice\/04-life\.jpg/);
  assert.match(html, /From/);
  assert.match(html, /Back to/);
  assert.doesNotMatch(html, /section\.connected-practice\.jpg/);
  assert.match(html, /Explore Clinical Care/);
  assert.match(html, /Explore Innovation/);
  assert.match(html, /Explore Research/);
  assert.doesNotMatch(html, /01 \/ PATHWAYS|03 \/ CLINICAL CARE/);
  assert.match(html, /https:\/\/nobat\.ir\/9705/);
  assert.match(html, /Hand &amp; Upper Extremity Surgeon/);
  assert.doesNotMatch(html, /codex-preview|Your site is taking shape|react-loading-skeleton/i);
});

test("renders internal and localized routes", async () => {
  const localizedPages = ["clinical-care", "research", "innovation", "education", "about", "news", "contact"];
  const paths = ["/", ...localizedPages.map((page) => `/${page}`), "/fa", "/ar", ...["fa", "ar"].flatMap((locale) => localizedPages.map((page) => `/${locale}/${page}`))];
  for (const path of paths) {
    const response = await render(path);
    assert.equal(response.status, 200, path);
    const html = await response.text();
    assert.match(html, /Dr\. Ali Moradi/, path);
    assert.doesNotMatch(html, /First-version content|ready for the next editorial pass|Your site is taking shape/i, path);
  }
});

test("ships the approved brand, social, and favicon metadata", async () => {
  const response = await render();
  const html = await response.text();
  assert.match(html, /\/brand\/logo\.en\.svg/);
  assert.match(html, /\/media\/hero\/hero-bg\.png/);
  assert.match(html, /https:\/\/dralimoradi\.moghadam\.pro\/social-banner\.jpg\?v=20260803/);
  assert.match(html, /https:\/\/dralimoradi\.moghadam\.pro\/favicon\.ico\?v=20260803/);
  assert.match(html, /site\.webmanifest/);
  assert.match(html, /favicon-32x32\.png/);
  assert.match(html, /#4293C2/i);
});

test("includes reduced-motion and responsive safeguards", async () => {
  const css = await import("node:fs/promises").then(({ readFile }) => readFile(new URL("../app/globals.css", import.meta.url), "utf8"));
  assert.match(css, /prefers-reduced-motion:\s*reduce/);
  assert.match(css, /@media \(max-width: 560px\)/);
  assert.match(css, /@media \(max-width: 820px\)/);
  assert.match(css, /\.connected-grid\s*\{[^}]*grid-template-columns:\s*1fr/);
  assert.match(css, /\.connected-arrow[^}]*rotate\(90deg\)/);
  assert.match(css, /overflow-x:\s*clip/);
  assert.match(css, /"Inter Variable"/);
  assert.match(css, /"Vazirmatn Variable"/);
  assert.match(css, /font-family:\s*"Scheherazade New"/);
  assert.doesNotMatch(css, /font-family:\s*Amiri/);
  assert.match(css, /\.rtl \.hero-background\s*\{[^}]*scaleX\(-1\)/);
  assert.match(css, /\.hero-note\s*\{[^}]*min-height:\s*160px[^}]*padding:\s*18px[^}]*rgba\(6,43,62,\.6\)[^}]*blur\(2px\)/);
  assert.doesNotMatch(css, /\.hero-note\s*\{[^}]*margin-bottom:\s*96px/);
  assert.match(css, /@media \(max-width: 1120px\)[\s\S]*?\.path-grid\s*\{\s*grid-template-columns:\s*1fr/);
  assert.doesNotMatch(css, /#176f98/i);
});
