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
  assert.match(html, /Hand Surgeon/);
  assert.match(html, /Harvard University/);
  assert.match(html, /Ph\.D of/);
  assert.match(html, /Artificial Limbs/);
  assert.match(html, /Recipient of the 2026 Alborz Award/);
  assert.match(html, /Iran’s Nobel Prize/);
  assert.match(html, /Associate Professor of Orthopedics at Mashhad University of Medical Sciences/);
  assert.doesNotMatch(html, />Product</);
  assert.match(html, /Clinic/);
  assert.match(html, /About me/);
  assert.match(html, /href="\/clinical-care"/);
  assert.match(html, /href="\/innovation"/);
  assert.match(html, /href="\/research"/);
  assert.match(html, /href="\/education"/);
  assert.match(html, /href="\/about"/);
  assert.match(html, /href="\/blog"/);
  assert.match(html, /Where Surgery Goes Beyond Protocols/);
  assert.ok(html.indexOf("CONNECTED PRACTICE") < html.indexOf("PATHWAYS"));
  assert.match(html, /\/media\/connected-practice\/01-injury\.jpg/);
  assert.match(html, /\/media\/connected-practice\/02-innovation\.jpg/);
  assert.match(html, /\/media\/connected-practice\/03-application\.jpg/);
  assert.match(html, /\/media\/connected-practice\/04-life\.jpg/);
  assert.match(html, /From/);
  assert.match(html, /Back to/);
  assert.match(html, /GP appointment \(general practitioner screening\)/i);
  assert.match(html, /\/media\/appointments\/doctor\.jpg/);
  assert.match(html, /\/media\/about\/office\.jpg/);
  assert.match(html, /\/media\/news\/best-paper-meeting\.jpg/);
  assert.match(html, /\/media\/news\/congress-recognition\.jpg/);
  assert.doesNotMatch(html, /Surgery beyond protocols/);
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
  const localizedPages = [
    "clinical-care", "clinical-care/clinic-services", "clinical-care/hospital-services",
    "clinical-care/clinic-gallery", "clinical-care/hospital-gallery",
    "patient-resources/before-surgery", "patient-resources/after-surgery",
    "patient-resources/faq", "patient-resources/rehabilitation",
    "research", "innovation", "education", "about", "blog", "news", "contact", "team/ali-moradi",
  ];
  const article = "understanding-carpal-tunnel-syndrome";
  const paths = ["/", ...localizedPages.map((page) => `/${page}`), `/blog/${article}`, "/fa", "/ar", ...["fa", "ar"].flatMap((locale) => [...localizedPages.map((page) => `/${locale}/${page}`), `/${locale}/blog/${article}`])];
  for (const path of paths) {
    const response = await render(path);
    assert.equal(response.status, 200, path);
    const html = await response.text();
    assert.match(html, /Dr\. Ali Moradi/, path);
    assert.doesNotMatch(html, /First-version content|ready for the next editorial pass|Your site is taking shape/i, path);
  }
});

test("renders the clinic hub, resources, galleries, team, and unified post archive", async () => {
  const clinic = await render("/clinical-care").then((response) => response.text());
  assert.match(clinic, /\/media\/pages\/clinic-cover\.jpg/);
  assert.match(clinic, /Clinic services/);
  assert.match(clinic, /Hospital services/);
  assert.match(clinic, /Meet the team/);
  assert.match(clinic, /Dr\. Mona Meybodi/);
  assert.match(clinic, /Inside the clinic/);
  assert.match(clinic, /Hospital-based care/);
  assert.match(clinic, /href="\/clinical-care\/clinic-services"/);
  assert.match(clinic, /href="\/clinical-care\/hospital-services"/);
  assert.match(clinic, /href="\/clinical-care\/clinic-gallery"/);
  assert.match(clinic, /href="\/clinical-care\/hospital-gallery"/);

  const clinicServices = await render("/clinical-care/clinic-services").then((response) => response.text());
  assert.match(clinicServices, /Back to the main clinic page/);
  assert.match(clinicServices, /Selected office procedures/);

  const fullGallery = await render("/clinical-care/clinic-gallery").then((response) => response.text());
  assert.equal((fullGallery.match(/class="gallery-thumb"/g) ?? []).length, 16);

  const resources = await render("/patient-resources/before-surgery").then((response) => response.text());
  assert.match(resources, /Before-surgery guidance/);
  assert.match(resources, /Medication and fasting/);

  const research = await render("/research").then((response) => response.text());
  assert.match(research, /https:\/\/scholar\.google\.com\/citations\?user=UhXLjGEAAAAJ&amp;hl=en/);
  assert.match(research, /Maedeh Sharafoddin/);

  const innovation = await render("/innovation").then((response) => response.text());
  assert.match(innovation, /Dr\. Alireza Akbarzadeh/);

  const archive = await render("/blog").then((response) => response.text());
  assert.equal((archive.match(/class="reveal blog-card"/g) ?? []).length, 21);
  assert.match(archive, /Understanding carpal tunnel syndrome/);
  assert.match(archive, /When to seek emergency care/);

  const news = await render("/news").then((response) => response.text());
  assert.equal((news.match(/class="reveal blog-card"/g) ?? []).length, 3);
  assert.match(news, /Hand reconstruction and myoelectric prostheses/);

  const single = await render("/blog/understanding-carpal-tunnel-syndrome").then((response) => response.text());
  assert.match(single, /<title>Understanding carpal tunnel syndrome \| Dr\. Ali Moradi<\/title>/);
  assert.match(single, /Why assessment matters/);
  assert.match(single, /Back to all articles/);
});

test("ships the approved brand, social, and favicon metadata", async () => {
  const response = await render();
  const html = await response.text();
  assert.match(html, /\/brand\/logo\.en\.svg/);
  assert.match(html, /\/media\/hero\/hero-bg-v2\.jpg/);
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
  assert.match(css, /\.section-space\s*\{[^}]*padding-top:\s*80px[^}]*padding-bottom:\s*80px/);
  assert.match(css, /\.section-index\s*\{[^}]*font-size:\s*18px/);
  assert.match(css, /\.section-index::before\s*\{[^}]*flex:\s*0 0 4px[^}]*width:\s*4px[^}]*height:\s*32px/);
  assert.match(css, /\.connected-grid\s*\{[^}]*gap:\s*100px/);
  assert.match(css, /\.connected-arrow\s*\{[^}]*width:\s*48px[^}]*height:\s*48px/);
  assert.doesNotMatch(css, /\.connected-arrow\s*\{[^}]*drop-shadow/);
  assert.doesNotMatch(css, /\.innovation\s*\{[^}]*padding-top:\s*30px/);
  assert.match(css, /\.appointment-layout\s*\{[^}]*grid-template-columns/);
  assert.match(css, /\.news-card h3\s*\{[^}]*-webkit-line-clamp:\s*2/);
  assert.match(css, /overflow-x:\s*clip/);
  assert.match(css, /"Inter Variable"/);
  assert.match(css, /"Vazirmatn Variable"/);
  assert.match(css, /font-family:\s*"Scheherazade New"/);
  assert.doesNotMatch(css, /font-family:\s*Amiri/);
  assert.match(css, /\.rtl \.hero-background\s*\{[^}]*scaleX\(-1\)/);
  assert.match(css, /\.rtl \.hero-wash,\s*\.rtl \.hero-orbits\s*\{[^}]*scaleX\(-1\)/);
  assert.match(css, /\.hero-note\s*\{[^}]*min-height:\s*160px[^}]*padding:\s*18px[^}]*rgba\(6,43,62,\.6\)[^}]*blur\(2px\)/);
  assert.doesNotMatch(css, /\.hero-note\s*\{[^}]*margin-bottom:\s*96px/);
  assert.match(css, /@media \(max-width: 1120px\)[\s\S]*?\.path-grid\s*\{\s*grid-template-columns:\s*1fr/);
  assert.doesNotMatch(css, /#176f98/i);
  assert.match(css, /\.interior-cover\s*\{[^}]*max-height:\s*400px/);
  assert.match(css, /\.interior-cover-gradient\s*\{[^}]*linear-gradient\(#4293c275 0%, #4293c2e3 100%\)/i);
  assert.match(css, /\.large-counts \.section-count\s*\{[^}]*position:\s*absolute[^}]*#edf2f4[^}]*font-size:\s*128px/);
  assert.match(css, /\.gallery-modal\s*\{[^}]*position:\s*fixed/);
  assert.match(css, /\.gallery-strip-track, \.gallery-full-track\s*\{[^}]*repeat\(4/);
  assert.match(css, /\.blog-grid\s*\{[^}]*repeat\(3/);
  assert.match(css, /@media \(max-width: 560px\)[\s\S]*?\.blog-grid\s*\{\s*grid-template-columns:\s*1fr/);
});
