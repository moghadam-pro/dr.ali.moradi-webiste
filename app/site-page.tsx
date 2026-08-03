"use client";

import {
  ArrowRight,
  Award,
  Bone,
  BookOpen,
  BrainCircuit,
  CalendarDays,
  Check,
  ChevronDown,
  ChevronRight,
  CircleHelp,
  Clock3,
  ExternalLink,
  FileText,
  Hand,
  Languages,
  Lightbulb,
  Mail,
  MapPin,
  Menu,
  Microscope,
  MoveUpRight,
  Phone,
  Quote,
  ShieldCheck,
  Sparkles,
  Stethoscope,
  X,
} from "lucide-react";
import Image from "next/image";
import { FormEvent, useEffect, useMemo, useState } from "react";

type Locale = "en" | "fa" | "ar";

const pageSlugs = [
  "clinical-care",
  "research",
  "innovation",
  "education",
  "about",
  "news",
] as const;

const copy = {
  en: {
    nav: ["Clinical Care", "Research", "Innovation", "Education", "About", "News"],
    appointment: "Make an appointment",
    eyebrow: "Hand surgery · Research · Medical innovation",
    heroTitle: "Advancing hand care through research and innovation.",
    heroBody:
      "Dr. Ali Moradi brings clinical precision, research leadership, and engineering thinking together to restore function across the hand and upper extremity.",
    heroPrimary: "Explore clinical care",
    heroSecondary: "Discover his work",
    credentials: ["Hand & upper extremity surgeon", "Associate Professor of Orthopedics", "PhD in Orthotics & Prosthetics"],
    pathsTitle: "Choose the path that fits your visit",
    pathsBody: "One practice, four connected fields — designed for patients, researchers, innovators, and learners.",
    storyTitle: "From injury to restored function",
    storyBody: "A connected journey from clinical observation to practical impact.",
    expertiseTitle: "Specialized care for the hand and upper extremity",
    innovationTitle: "Selected innovation stories",
    impactTitle: "Evidence, not decoration",
    appointmentTitle: "Find the right appointment pathway",
    appointmentBody: "Booking is completed securely through the approved external service.",
    newsTitle: "Research and recognition",
    aboutTitle: "Surgery beyond protocols",
    aboutBody:
      "Clinical practice is only one part of the work. Dr. Moradi's research, inventions, teaching, and leadership aim to turn complex problems into better tools and clearer care pathways.",
    footerLine: "Hand care, research, innovation, and education — connected by purpose.",
  },
  fa: {
    nav: ["خدمات درمانی", "پژوهش", "نوآوری", "آموزش", "درباره دکتر", "اخبار"],
    appointment: "دریافت نوبت",
    eyebrow: "جراحی دست · پژوهش · نوآوری پزشکی",
    heroTitle: "توسعه درمان دست با تکیه بر پژوهش و نوآوری.",
    heroBody:
      "دکتر علی مرادی، دقت بالینی، رهبری پژوهشی و نگاه مهندسی را برای بازگرداندن عملکرد دست و اندام فوقانی در کنار هم قرار می‌دهد.",
    heroPrimary: "مشاهده خدمات درمانی",
    heroSecondary: "آشنایی با فعالیت‌ها",
    credentials: ["جراح دست و اندام فوقانی", "دانشیار ارتوپدی", "دکتری تخصصی ارتز و پروتز"],
    pathsTitle: "مسیر مناسب خود را انتخاب کنید",
    pathsBody: "چهار حوزه به‌هم‌پیوسته برای بیماران، پژوهشگران، نوآوران و دانش‌پژوهان.",
    storyTitle: "از آسیب تا بازگشت عملکرد",
    storyBody: "روایتی پیوسته از مشاهده بالینی تا اثری عملی در زندگی بیمار.",
    expertiseTitle: "مراقبت تخصصی از دست و اندام فوقانی",
    innovationTitle: "نوآوری‌های منتخب",
    impactTitle: "دستاوردهای مستند",
    appointmentTitle: "مسیر مناسب نوبت را انتخاب کنید",
    appointmentBody: "ثبت نوبت از طریق سامانه خارجی تأییدشده انجام می‌شود.",
    newsTitle: "پژوهش و افتخارات",
    aboutTitle: "جراحی فراتر از پروتکل‌ها",
    aboutBody:
      "فعالیت بالینی تنها بخشی از این مسیر است. پژوهش، اختراع، آموزش و مدیریت علمی دکتر مرادی با هدف تبدیل مسائل پیچیده به ابزارهای بهتر و مسیرهای درمانی روشن‌تر دنبال می‌شود.",
    footerLine: "درمان دست، پژوهش، نوآوری و آموزش؛ متصل با یک هدف مشترک.",
  },
  ar: {
    nav: ["الرعاية السريرية", "البحث", "الابتكار", "التعليم", "عن الطبيب", "الأخبار"],
    appointment: "حجز موعد",
    eyebrow: "جراحة اليد · البحث · الابتكار الطبي",
    heroTitle: "تطوير رعاية اليد من خلال البحث والابتكار.",
    heroBody:
      "يجمع الدكتور علي مرادي بين الدقة السريرية والبحث العلمي والتفكير الهندسي لاستعادة وظيفة اليد والطرف العلوي.",
    heroPrimary: "استكشف الرعاية السريرية",
    heroSecondary: "اكتشف أعماله",
    credentials: ["جراح اليد والطرف العلوي", "أستاذ مشارك في جراحة العظام", "دكتوراه في الأطراف الاصطناعية والأجهزة التقويمية"],
    pathsTitle: "اختر المسار المناسب لزيارتك",
    pathsBody: "أربعة مجالات مترابطة للمرضى والباحثين والمبتكرين والمتعلمين.",
    storyTitle: "من الإصابة إلى استعادة الوظيفة",
    storyBody: "رحلة مترابطة من الملاحظة السريرية إلى الأثر العملي.",
    expertiseTitle: "رعاية متخصصة لليد والطرف العلوي",
    innovationTitle: "قصص ابتكار مختارة",
    impactTitle: "إنجازات موثقة",
    appointmentTitle: "اختر مسار الموعد المناسب",
    appointmentBody: "يتم الحجز من خلال الخدمة الخارجية المعتمدة.",
    newsTitle: "البحث والتقدير",
    aboutTitle: "جراحة تتجاوز البروتوكولات",
    aboutBody:
      "الممارسة السريرية جزء من المسار. وتهدف أبحاث الدكتور مرادي واختراعاته وتعليمه وقيادته إلى تحويل المشكلات المعقدة إلى أدوات أفضل ومسارات علاج أوضح.",
    footerLine: "رعاية اليد والبحث والابتكار والتعليم — يجمعها هدف واحد.",
  },
};

const pathCards = [
  { icon: Stethoscope, title: "Clinical care", text: "Conditions, treatments, care locations, and patient resources.", slug: "clinical-care", no: "01" },
  { icon: Microscope, title: "Research", text: "Biomechanics, clinical studies, publications, and collaboration.", slug: "research", no: "02" },
  { icon: Lightbulb, title: "Innovation", text: "Devices and systems designed around real clinical problems.", slug: "innovation", no: "03" },
  { icon: BookOpen, title: "Education", text: "Clinical teaching, courses, workshops, and learning resources.", slug: "education", no: "04" },
];

const conditions = [
  ["Trauma & emergencies", "Fractures, complex wounds, tendon, nerve, and vascular injuries"],
  ["Nerve & tendon care", "Carpal tunnel, cubital tunnel, tendon injuries, and reconstruction"],
  ["Hand & wrist disorders", "Degenerative conditions, instability, arthritis, and overuse"],
  ["Microsurgery", "Nerve grafts, tendon transfers, free flaps, and limb salvage"],
  ["Congenital hand", "Specialized evaluation and treatment for pediatric hand differences"],
  ["Sports injuries", "Ligament injury, tendonitis, and activity-related upper-limb conditions"],
];

const innovations = [
  { icon: Bone, tag: "Biomechanics", title: "External fixation systems", text: "Smarter fixation concepts informed by fracture biomechanics and clinical workflow." },
  { icon: Hand, tag: "Human–machine interface", title: "Bionic hand control", text: "Implanted magnetic sensing and control approaches for more intuitive prosthetic function." },
  { icon: BrainCircuit, tag: "Regenerative mechanics", title: "Magnetic distraction", text: "A research-led approach to controlled joint distraction and tissue preservation." },
];

const pageData: Record<string, { kicker: string; title: string; intro: string; sections: { title: string; text: string; items?: string[] }[] }> = {
  "clinical-care": {
    kicker: "Clinical care",
    title: "Specialized pathways for hand and upper-extremity care.",
    intro: "Patient-centered evaluation, treatment, reconstruction, and follow-up across private office and hospital settings.",
    sections: [
      { title: "Conditions", text: "A structured, patient-friendly library will help visitors understand common conditions without attempting online diagnosis.", items: ["Trauma and emergencies", "Nerve and tendon conditions", "Hand and wrist disorders", "Congenital and pediatric hand", "Microsurgery and reconstruction", "Sports and overuse injuries"] },
      { title: "Care locations", text: "Private office and hospital services will be presented separately with clear differences, maps, and visit instructions." },
      { title: "Patient resources", text: "Before-surgery guidance, after-surgery instructions, frequently asked questions, and approved educational media." },
    ],
  },
  research: {
    kicker: "Research",
    title: "Questions from the clinic, tested through rigorous research.",
    intro: "Clinical studies, orthopedic biomechanics, muscle sensing, robotics, and outcome research form a connected program.",
    sections: [
      { title: "Core themes", text: "Research topics connect upper-extremity surgery with engineering and rehabilitation.", items: ["Clinical hand research", "Orthopedic and muscle biomechanics", "Bionic hand control", "Rehabilitation robotics", "Patient-reported outcomes", "Biomaterials and coatings"] },
      { title: "Publications and patents", text: "The final library will use structured records, verified counts, and links to approved sources rather than a static list." },
      { title: "Collaboration", text: "A dedicated pathway will support research collaboration, thesis supervision, training, and institutional enquiries." },
    ],
  },
  innovation: {
    kicker: "Innovation",
    title: "Engineering ideas shaped by real clinical constraints.",
    intro: "Each innovation story starts with a problem, documents the development path, and connects claims to patents or research evidence.",
    sections: [
      { title: "External fixation", text: "A portfolio of fixation ideas and biomechanics research designed to improve stability, usability, and treatment planning." },
      { title: "Bionic hand and prosthetic control", text: "Research into implanted magnetic sensors, control signals, and more intuitive human–machine interaction." },
      { title: "Innovation ecosystem", text: "Avisa, BJRL, Akam, EuWalk, Integrom, and Fixodyn will be organized as one ecosystem rather than competing top-level pages." },
    ],
  },
  education: {
    kicker: "Education",
    title: "Knowledge designed to travel from evidence to practice.",
    intro: "A future-ready educational library for students, residents, fellows, clinicians, and research trainees.",
    sections: [
      { title: "Learning pathways", text: "Content will be grouped by audience and level instead of publishing one unstructured media archive.", items: ["Medical students and interns", "Orthopedic residents", "Hand fellows", "Researchers and thesis students"] },
      { title: "Lectures and courses", text: "Recorded lectures, courses, workshops, and events with clear dates, learning objectives, and supporting material." },
      { title: "Clinical teaching", text: "Approved cases, surgical pearls, manuals, and reading lists — with sensitive content clearly labeled." },
    ],
  },
  about: {
    kicker: "About Dr. Moradi",
    title: "A surgeon, researcher, inventor, and educator.",
    intro: "Dr. Ali Moradi is an Associate Professor of Orthopedics at Mashhad University of Medical Sciences and a specialist in hand and upper-extremity surgery.",
    sections: [
      { title: "Training", text: "His training spans orthopedic surgery, hand and upper-extremity research at Massachusetts General Hospital, hand and microsurgery, wrist arthroscopy, and a PhD in Orthotics and Prosthetics." },
      { title: "Leadership", text: "His work includes leadership across orthopedic research, bone and joint research, medical robotics, and medical innovation." },
      { title: "Recognition", text: "The newly received certificate archive includes research awards, best-paper recognition, reviewer and editorial roles, innovation diplomas, invited lectures, and professional committee appointments." },
    ],
  },
  news: {
    kicker: "News & insights",
    title: "Research, recognition, and work in progress.",
    intro: "A curated editorial stream — not a social-media mirror — for significant work and useful updates.",
    sections: [
      { title: "Best Paper Award", text: "Recognition for research on optimal magnetic-sensor configuration for bionic-hand control." },
      { title: "Top-cited research", text: "Wiley recognition for highly cited work on hydroxyapatite-based coatings for external-fixator pins." },
      { title: "COTARIUM 2025", text: "Presentations covering a magnetic internal distractor and a surgical approach for bionic-hand motor control." },
    ],
  },
};

function localizedHref(locale: Locale, slug = "") {
  if (locale === "en") return slug ? `/${slug}` : "/";
  return slug ? `/${locale}/${slug}` : `/${locale}`;
}

function Reveal({ children, className = "" }: { children: React.ReactNode; className?: string }) {
  return <div className={`reveal ${className}`}>{children}</div>;
}

export function SitePage({ locale, page }: { locale: Locale; page: string }) {
  const t = copy[locale];
  const rtl = locale !== "en";
  const [menuOpen, setMenuOpen] = useState(false);
  const [languageOpen, setLanguageOpen] = useState(false);

  useEffect(() => {
    document.documentElement.lang = locale;
    document.documentElement.dir = rtl ? "rtl" : "ltr";
    const observer = new IntersectionObserver(
      (entries) => entries.forEach((entry) => entry.isIntersecting && entry.target.classList.add("is-visible")),
      { threshold: 0.12 },
    );
    document.querySelectorAll(".reveal").forEach((element) => observer.observe(element));
    return () => observer.disconnect();
  }, [locale, page, rtl]);

  const nav = useMemo(() => pageSlugs.map((slug, index) => ({ slug, label: t.nav[index] })), [t.nav]);
  const currentSlug = page === "home" ? "" : page;

  return (
    <main className={rtl ? "rtl" : "ltr"}>
      <a className="skip-link" href="#content">Skip to content</a>
      <header className="site-header">
        <a className="brand" href={localizedHref(locale)} aria-label="Dr. Ali Moradi home">
          <Image src="/brand/logo-lockup.svg" alt="Dr. Ali Moradi" width={180} height={52} priority />
        </a>
        <nav className="desktop-nav" aria-label="Primary navigation">
          {nav.map((item) => <a key={item.slug} className={page === item.slug ? "active" : ""} href={localizedHref(locale, item.slug)}>{item.label}</a>)}
        </nav>
        <div className="header-actions">
          <div className="language-control">
            <button className="language-button" onClick={() => setLanguageOpen(!languageOpen)} aria-expanded={languageOpen} aria-label="Choose language"><Languages size={17} /><span>{locale.toUpperCase()}</span><ChevronDown size={14} /></button>
            {languageOpen && <div className="language-menu">
              {(["en", "fa", "ar"] as Locale[]).map((lang) => <a key={lang} href={localizedHref(lang, currentSlug)}>{lang === "en" ? "English" : lang === "fa" ? "فارسی" : "العربية"}</a>)}
            </div>}
          </div>
          <a className="button button-small" href="https://nobat.ir/9705" target="_blank" rel="noreferrer">{t.appointment}<ExternalLink size={15} /></a>
          <button className="menu-button" onClick={() => setMenuOpen(!menuOpen)} aria-label="Toggle menu">{menuOpen ? <X /> : <Menu />}</button>
        </div>
      </header>
      {menuOpen && <nav className="mobile-nav" aria-label="Mobile navigation">
        {nav.map((item) => <a key={item.slug} href={localizedHref(locale, item.slug)}>{item.label}<ChevronRight size={17} /></a>)}
        <a className="button" href="https://nobat.ir/9705" target="_blank" rel="noreferrer">{t.appointment}<ExternalLink size={16} /></a>
      </nav>}

      <div id="content">
        {page === "home" ? <HomePage locale={locale} t={t} /> : page === "contact" ? <ContactPage /> : <InteriorPage locale={locale} page={page} />}
      </div>
      <Footer locale={locale} line={t.footerLine} />
    </main>
  );
}

function HomePage({ locale, t }: { locale: Locale; t: (typeof copy)[Locale] }) {
  return <>
    <section className="hero section-shell">
      <div className="hero-copy">
        <p className="eyebrow"><span />{t.eyebrow}</p>
        <h1>{t.heroTitle}</h1>
        <p className="hero-body">{t.heroBody}</p>
        <div className="hero-actions">
          <a className="button" href={localizedHref(locale, "clinical-care")}>{t.heroPrimary}<ArrowRight size={17} /></a>
          <a className="text-link" href="#pathways">{t.heroSecondary}<ChevronDown size={17} /></a>
        </div>
        <div className="credential-list">
          {t.credentials.map((item) => <span key={item}><Check size={14} />{item}</span>)}
        </div>
      </div>
      <div className="hero-visual">
        <div className="portrait-frame">
          <Image src="/media/edited/dr-moradi-hero-v1.png" alt="Dr. Ali Moradi in a white medical coat" fill priority unoptimized sizes="(max-width: 820px) 90vw, 45vw" />
          <div className="portrait-caption"><strong>Dr. Ali Moradi</strong><span>MD, PhD</span></div>
        </div>
        <div className="orbit orbit-one" /><div className="orbit orbit-two" />
        <div className="hero-note"><Quote size={18} /><span>Surgery beyond protocols</span></div>
      </div>
    </section>

    <section className="facet-bar section-shell" aria-label="Professional fields">
      {[
        [Hand, "Hand & wrist", "Expertise"], [BrainCircuit, "Advanced", "Technology"], [Lightbulb, "Innovation", "Entrepreneurship"], [Microscope, "Research", "Leadership"],
      ].map(([Icon, top, bottom]) => <div className="facet" key={String(top)}><Icon size={24} /><span><small>{String(top)}</small><strong>{String(bottom)}</strong></span></div>)}
    </section>

    <section id="pathways" className="pathways section-space section-shell">
      <Reveal className="section-heading"><p className="section-index">01 / PATHWAYS</p><h2>{t.pathsTitle}</h2><p>{t.pathsBody}</p></Reveal>
      <div className="path-grid">
        {pathCards.map(({ icon: Icon, title, text, slug, no }) => <Reveal className="path-card" key={slug}>
          <div className="card-number">{no}</div><Icon size={28} /><h3>{title}</h3><p>{text}</p><a href={localizedHref(locale, slug)} aria-label={`Explore ${title}`}><ArrowRight size={18} /></a>
        </Reveal>)}
      </div>
    </section>

    <section className="journey section-space">
      <div className="section-shell journey-grid">
        <Reveal className="journey-copy"><p className="section-index light">02 / CONNECTED PRACTICE</p><h2>{t.storyTitle}</h2><p>{t.storyBody}</p><a className="text-link light" href={localizedHref(locale, "innovation")}>Explore the complete journey<ArrowRight size={17} /></a></Reveal>
        <div className="journey-steps">
          {[
            ["01", "Understand", "Examine the condition and define the real functional problem."],
            ["02", "Reconstruct", "Choose a precise clinical pathway to restore structure and movement."],
            ["03", "Invent", "Build new tools when existing solutions do not answer the problem."],
            ["04", "Share", "Turn evidence into education, collaboration, and wider impact."],
          ].map(([n, title, text]) => <Reveal className="journey-step" key={n}><span>{n}</span><div><h3>{title}</h3><p>{text}</p></div></Reveal>)}
        </div>
      </div>
    </section>

    <section className="expertise section-space section-shell">
      <Reveal className="section-heading split-heading"><div><p className="section-index">03 / CLINICAL CARE</p><h2>{t.expertiseTitle}</h2></div><a className="text-link" href={localizedHref(locale, "clinical-care")}>View all conditions<ArrowRight size={17} /></a></Reveal>
      <div className="expertise-grid">
        {conditions.map(([title, text], index) => <Reveal className="expertise-item" key={title}><span>0{index + 1}</span><h3>{title}</h3><p>{text}</p><MoveUpRight size={18} /></Reveal>)}
      </div>
    </section>

    <section className="innovation section-space section-shell">
      <Reveal className="section-heading"><p className="section-index">04 / INNOVATION</p><h2>{t.innovationTitle}</h2><p>Clinical problems become research questions; selected questions become practical systems.</p></Reveal>
      <div className="innovation-grid">
        {innovations.map(({ icon: Icon, tag, title, text }, index) => <Reveal className={`innovation-card innovation-${index + 1}`} key={title}>
          <div className="innovation-art"><Icon size={68} strokeWidth={1.1} /><span /><span /></div>
          <p className="card-tag">{tag}</p><h3>{title}</h3><p>{text}</p><a href={localizedHref(locale, "innovation")}>Read the story<ArrowRight size={16} /></a>
        </Reveal>)}
      </div>
    </section>

    <section className="impact section-space">
      <div className="section-shell">
        <Reveal className="section-heading split-heading"><div><p className="section-index light">05 / IMPACT</p><h2>{t.impactTitle}</h2></div><p className="as-of">Working figures · pending final confirmation</p></Reveal>
        <div className="metrics">
          {[["150+", "Peer-reviewed articles"], ["04", "Books"], ["30", "National patents"], ["08", "US patents"]].map(([number, label]) => <Reveal className="metric" key={label}><strong>{number}</strong><span>{label}</span></Reveal>)}
        </div>
      </div>
    </section>

    <section className="appointments section-space section-shell">
      <Reveal className="section-heading"><p className="section-index">06 / APPOINTMENTS</p><h2>{t.appointmentTitle}</h2><p>{t.appointmentBody}</p></Reveal>
      <div className="appointment-grid">
        <Reveal className="appointment-card urgent"><div className="appointment-icon"><ShieldCheck /></div><p className="card-tag">Urgent in-person</p><h3>Emergency appointment</h3><p>For acute hand or upper-extremity injuries requiring prompt specialist review.</p><div className="schedule"><CalendarDays size={17} /><span>Saturday, Monday & Wednesday</span></div><div className="schedule"><Clock3 size={17} /><span>15:45–18:30</span></div><span className="pending-label">Visit instructions — provisional</span></Reveal>
        <Reveal className="appointment-card featured"><div className="appointment-icon"><CalendarDays /></div><p className="card-tag">Planned visit</p><h3>Scheduled in-person appointment</h3><p>For non-urgent specialist consultation at the private office.</p><a className="button" href="https://nobat.ir/9705" target="_blank" rel="noreferrer">Continue to Nobat.ir<ExternalLink size={16} /></a><small>Opens the approved external booking service.</small></Reveal>
        <Reveal className="appointment-card"><div className="appointment-icon"><CircleHelp /></div><p className="card-tag">Priority follow-up</p><h3>Online appointment</h3><p>A provisional pathway for patients outside Mashhad or requiring document review.</p><span className="pending-label">Details coming in the next revision</span></Reveal>
      </div>
      <p className="medical-note"><ShieldCheck size={17} />Life- or limb-threatening emergencies require immediate emergency medical services. This website does not provide diagnosis or emergency response.</p>
    </section>

    <section className="news section-space section-shell">
      <Reveal className="section-heading split-heading"><div><p className="section-index">07 / LATEST</p><h2>{t.newsTitle}</h2></div><a className="text-link" href={localizedHref(locale, "news")}>All updates<ArrowRight size={17} /></a></Reveal>
      <div className="news-grid">
        {[
          ["Research recognition", "Best Paper Award for bionic-hand sensor configuration", "The newly supplied certificate archive confirms recognition for magnetic-sensor research supporting bionic-hand control."],
          ["Research impact", "Top-cited work in external-fixator coatings", "Wiley recognition highlights highly cited work on hydroxyapatite-based pin coatings."],
          ["Conference", "Two innovation presentations at COTARIUM 2025", "A magnetic internal distractor and a surgical approach for bionic-hand motor control were presented."],
        ].map(([tag, title, text]) => <Reveal className="news-card" key={title}><div className="news-icon"><Award /></div><p className="card-tag">{tag}</p><h3>{title}</h3><p>{text}</p><a href={localizedHref(locale, "news")}>Read update<ArrowRight size={16} /></a></Reveal>)}
      </div>
    </section>

    <section className="about-preview section-space">
      <div className="section-shell about-grid">
        <Reveal className="about-monogram"><span>AM</span><div className="pulse-line" /></Reveal>
        <Reveal className="about-copy"><p className="section-index light">08 / ABOUT</p><h2>{t.aboutTitle}</h2><p>{t.aboutBody}</p><div className="about-actions"><a className="button button-light" href={localizedHref(locale, "about")}>Meet Dr. Moradi<ArrowRight size={17} /></a><a className="text-link light" href={localizedHref(locale, "research")}>Research profile<FileText size={17} /></a></div></Reveal>
      </div>
    </section>
  </>;
}

function InteriorPage({ locale, page }: { locale: Locale; page: string }) {
  const data = pageData[page] || pageData["clinical-care"];
  return <>
    <section className="page-hero section-shell"><p className="eyebrow"><span />{data.kicker}</p><h1>{data.title}</h1><p>{data.intro}</p><div className="page-hero-orbit"><span /><span /></div></section>
    <section className="page-content section-space section-shell">
      <aside><p>On this page</p>{data.sections.map((section, i) => <a key={section.title} href={`#section-${i}`}>{section.title}</a>)}<a href="https://nobat.ir/9705" target="_blank" rel="noreferrer">Appointment<ExternalLink size={14} /></a></aside>
      <div className="content-sections">
        {data.sections.map((section, i) => <Reveal className="content-section" key={section.title}><span className="section-count">0{i + 1}</span><div><h2 id={`section-${i}`}>{section.title}</h2><p>{section.text}</p>{section.items && <ul>{section.items.map(item => <li key={item}><Check size={16} />{item}</li>)}</ul>}</div></Reveal>)}
        <Reveal className="next-step"><Sparkles /><div><p>First-version content</p><h2>This page is ready for the next editorial pass.</h2><span>The structure is functional; detailed approved copy and media will be added in revisions.</span></div><a className="button" href={page === "clinical-care" ? "https://nobat.ir/9705" : localizedHref(locale, "contact")}>{page === "clinical-care" ? "Make an appointment" : "Start a conversation"}<ArrowRight size={16} /></a></Reveal>
      </div>
    </section>
  </>;
}

function ContactPage() {
  const [sent, setSent] = useState(false);
  const [errors, setErrors] = useState<Record<string, string>>({});
  function submit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const data = new FormData(event.currentTarget);
    const next: Record<string, string> = {};
    if (String(data.get("name") || "").trim().length < 2) next.name = "Please enter your name.";
    if (!/^\S+@\S+\.\S+$/.test(String(data.get("email") || ""))) next.email = "Please enter a valid email.";
    if (String(data.get("message") || "").trim().length < 20) next.message = "Please add at least 20 characters.";
    setErrors(next);
    if (!Object.keys(next).length) setSent(true);
  }
  return <>
    <section className="page-hero section-shell"><p className="eyebrow"><span />Contact</p><h1>A clear route for every enquiry.</h1><p>Use the approved booking platform for appointments. The form below demonstrates the validated contact experience for professional and research enquiries.</p></section>
    <section className="contact-layout section-space section-shell">
      <div className="contact-details"><p className="section-index">CONTACT</p><h2>Before you write</h2><p>Do not send urgent medical information through this form. For appointments, continue directly to Nobat.ir.</p><a className="button" href="https://nobat.ir/9705" target="_blank" rel="noreferrer">Book on Nobat.ir<ExternalLink size={16} /></a><div className="contact-list"><span><MapPin />Mashhad, Iran</span><span><Mail />Professional contact details pending approval</span><span><Phone />Clinic phone pending approval</span></div></div>
      {sent ? <div className="form-success"><div><Check /></div><h2>Thank you.</h2><p>This is a demo confirmation. No information has been sent or stored.</p><button className="text-link" onClick={() => setSent(false)}>Send another message<ArrowRight size={16} /></button></div> : <form className="contact-form" onSubmit={submit} noValidate>
        <div className="field-row"><label>Name<input name="name" autoComplete="name" aria-invalid={!!errors.name} /></label><label>Email<input name="email" type="email" autoComplete="email" aria-invalid={!!errors.email} /></label></div>
        {(errors.name || errors.email) && <div className="error-row"><span>{errors.name}</span><span>{errors.email}</span></div>}
        <label>Enquiry type<select name="type" defaultValue="research"><option value="research">Research collaboration</option><option value="education">Education and training</option><option value="media">Media and professional enquiry</option></select></label>
        <label>Message<textarea name="message" rows={6} aria-invalid={!!errors.message} placeholder="Please do not include sensitive medical information." /></label>{errors.message && <span className="field-error">{errors.message}</span>}
        <label className="consent"><input type="checkbox" required /><span>I understand this demo form does not provide medical advice or emergency support.</span></label>
        <button className="button" type="submit">Validate enquiry<ArrowRight size={16} /></button>
      </form>}
    </section>
  </>;
}

function Footer({ locale, line }: { locale: Locale; line: string }) {
  return <footer className="site-footer"><div className="section-shell footer-grid"><div className="footer-brand"><Image src="/brand/logo-lockup-reverse.svg" alt="Dr. Ali Moradi" width={220} height={68} /><p>{line}</p></div><div><h3>Explore</h3><a href={localizedHref(locale, "clinical-care")}>Clinical care</a><a href={localizedHref(locale, "research")}>Research</a><a href={localizedHref(locale, "innovation")}>Innovation</a></div><div><h3>Resources</h3><a href={localizedHref(locale, "education")}>Education</a><a href={localizedHref(locale, "about")}>About</a><a href={localizedHref(locale, "contact")}>Contact</a></div><div><h3>Appointments</h3><p>Approved external booking</p><a className="footer-book" href="https://nobat.ir/9705" target="_blank" rel="noreferrer">Nobat.ir / 9705<ExternalLink size={14} /></a></div></div><div className="section-shell footer-bottom"><span>© 2026 Dr. Ali Moradi</span><span>Medical information is educational and does not replace professional assessment.</span></div></footer>;
}
