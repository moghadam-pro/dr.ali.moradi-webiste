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
  assert.match(html, /\/media\/edited\/dr-moradi-hero-v2\.jpg/);
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
  assert.match(css, /overflow-x:\s*clip/);
  assert.doesNotMatch(css, /#176f98/i);
});
