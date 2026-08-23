import assert from "node:assert/strict";
import { access } from "node:fs/promises";
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
  assert.match(html, /Awards and certificates/);
  assert.match(html, /\/media\/news\/best-paper\.jpg/);
  assert.match(html, /\/media\/news\/top-cited\.jpg/);
  assert.match(html, /\/media\/news\/congress-recognition\.jpg/);
  assert.match(html, /\/media\/news\/cotarium-2025\.jpg/);
  assert.equal((html.match(/class="reveal news-card"/g) ?? []).length, 4);
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
    "research", "innovation", "innovation/dynamometer", "innovation/magnetic-joint-distraction",
    "innovation/dynamic-distal-radius-external-fixator", "innovation/bionic-hand-h3",
    "innovation/bionic-hand-h5", "innovation/integrated-stem", "innovation/hip-exoskeleton-hexa",
    "education", "about", "blog", "news", "contact", "team/ali-moradi",
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
  for (const asset of [
    "../public/media/clinic/clinic-services-cover.jpg",
    "../public/media/clinic/hospital-services-cover.jpg",
    "../public/media/team/ali-moradi.jpg",
    "../public/media/team/mahsa-jafari.jpg",
    "../public/media/team/mahla-daliri.jpg",
    "../public/media/team/mona-meybodi.jpg",
    "../public/media/team/alireza-akbarzadeh.jpg",
    "../public/media/team/maedeh-sharafoddin.jpg",
    "../public/media/team/afsaneh-jahani.jpg",
    "../public/media/team/naeemeh-kalali.jpg",
    "../public/media/pages/team-profile-cover.jpg",
    "../public/media/pages/innovation-cover.jpg",
    "../public/media/pages/research-cover.jpg",
    "../public/media/pages/education-cover.jpg",
    "../public/downloads/pre-surgery-consent-form.pdf",
  ]) await access(new URL(asset, import.meta.url));

  const clinic = await render("/clinical-care").then((response) => response.text());
  assert.match(clinic, /\/media\/pages\/clinic-cover\.jpg/);
  assert.match(clinic, /Clinic services/);
  assert.match(clinic, /Hospital services/);
  assert.match(clinic, /Meet the team/);
  assert.match(clinic, /Dr\. Mona Meybodi/);
  assert.match(clinic, /Dr\. Mahla Daliri/);
  assert.match(clinic, /Clinic surgical cases/);
  assert.match(clinic, /Hospital surgical cases/);
  assert.match(clinic, /\/media\/clinic\/clinic-services-cover\.jpg/);
  assert.match(clinic, /\/media\/clinic\/hospital-services-cover\.jpg/);
  assert.match(clinic, /href="\/clinical-care\/clinic-services"/);
  assert.match(clinic, /href="\/clinical-care\/hospital-services"/);
  assert.match(clinic, /href="\/clinical-care\/clinic-gallery"/);
  assert.match(clinic, /href="\/clinical-care\/hospital-gallery"/);

  const clinicServices = await render("/clinical-care/clinic-services").then((response) => response.text());
  assert.match(clinicServices, /Back to the main clinic page/);
  assert.match(clinicServices, /Cubital tunnel syndrome/);

  const hospitalServices = await render("/clinical-care/hospital-services").then((response) => response.text());
  assert.match(hospitalServices, /Tendon lacerations/);
  assert.match(hospitalServices, /Nerve and vascular injuries/);

  const fullGallery = await render("/clinical-care/clinic-gallery").then((response) => response.text());
  assert.equal((fullGallery.match(/class="gallery-thumb"/g) ?? []).length, 16);

  const resources = await render("/patient-resources/before-surgery").then((response) => response.text());
  assert.match(resources, /Before-surgery preparation guide/);
  assert.match(resources, /Fasting and the day of surgery/);
  assert.match(resources, /pre-surgery-consent-form\.pdf/);
  assert.match(resources, /download=""/);

  const research = await render("/research").then((response) => response.text());
  assert.match(research, /https:\/\/scholar\.google\.com\/citations\?user=UhXLjGEAAAAJ&amp;hl=en/);
  assert.match(research, /Maedeh Sharafoddin/);

  const innovation = await render("/innovation").then((response) => response.text());
  assert.match(innovation, /Dr\. Alireza Akbarzadeh/);
  assert.equal((innovation.match(/class="reveal content-section"/g) ?? []).length, 14);
  assert.match(innovation, /href="\/innovation\/dynamometer"/);
  assert.match(innovation, /http:\/\/avisa-med\.com\/index\.php\/products\/schanz-pins/);

  const innovationDetail = await render("/innovation/bionic-hand-h3").then((response) => response.text());
  assert.match(innovationDetail, /Back to all innovations/);
  assert.match(innovationDetail, /myoelectrically controlled prosthetic hand/i);
  assert.match(innovationDetail, /https:\/\/dralimoradi\.com\/bionic-hand-h3\//);

  const education = await render("/education").then((response) => response.text());
  assert.match(education, /page-content section-space section-shell large-counts/);
  assert.match(education, /\/media\/pages\/education-cover\.jpg/);

  const about = await render("/about").then((response) => response.text());
  assert.doesNotMatch(about, /Academic and clinical identity|Training|Research and leadership/);
  assert.doesNotMatch(about, /page-content section-space section-shell/);

  const member = await render("/team/alireza-akbarzadeh").then((response) => response.text());
  assert.match(member, /\/media\/pages\/team-profile-cover\.jpg/);
  assert.match(member, /intentionally provisional draft/);

  const archive = await render("/blog").then((response) => response.text());
  assert.equal((archive.match(/class="reveal blog-card"/g) ?? []).length, 23);
  assert.match(archive, /Understanding carpal tunnel syndrome/);
  assert.match(archive, /When to seek emergency care/);

  const news = await render("/news").then((response) => response.text());
  assert.equal((news.match(/class="reveal blog-card"/g) ?? []).length, 4);
  assert.match(news, /Best Paper Award for bionic-hand sensor research/);

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
  assert.match(css, /\.news-grid\s*\{[^}]*repeat\(4/);
  assert.match(css, /\.news-card-gradient\s*\{[^}]*linear-gradient/);
  assert.match(css, /\.news-card-content strong\s*\{[^}]*-webkit-line-clamp:\s*3/);
  assert.match(css, /\.hero-credentials li\s*\{[^}]*font-size:\s*clamp\(13px, 1vw, 14px\)/);
  assert.match(css, /\.hero-description\s*\{[^}]*font-size:\s*clamp\(13px, 1vw, 14px\)/);
  assert.match(css, /\.team-clinic \.team-grid\s*\{[^}]*repeat\(4/);
  assert.match(css, /\.team-card-image\s*\{[^}]*height:\s*230px/);
  assert.match(css, /\.clinic-pathway-image\s*\{[^}]*height:\s*150px/);
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
  assert.match(css, /\.content-links\s*\{[^}]*display:\s*flex/);
  assert.match(css, /\.gallery-modal\s*\{[^}]*position:\s*fixed/);
  assert.match(css, /\.gallery-strip-track, \.gallery-full-track\s*\{[^}]*repeat\(4/);
  assert.match(css, /\.blog-grid\s*\{[^}]*repeat\(3/);
  assert.match(css, /@media \(max-width: 560px\)[\s\S]*?\.blog-grid\s*\{\s*grid-template-columns:\s*1fr/);
});
