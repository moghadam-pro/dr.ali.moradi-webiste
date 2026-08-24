"use client";

import {
  ArrowRight, BrainCircuit, CalendarDays, Check, ChevronDown, ChevronLeft, ChevronRight,
  Camera, Clock3, ExternalLink, FileText, Hand, Languages, Lightbulb,
  Mail, MapPin, Menu, Microscope, Phone, PlayCircle, Quote, Send,
  ShieldCheck, Sparkles, Stethoscope, X,
} from "lucide-react";
import type { LucideIcon } from "lucide-react";
import Image from "next/image";
import { FormEvent, useEffect, useMemo, useRef, useState } from "react";
import { content, type InteriorPageData, type Locale, pageSlugs, type SiteCopy } from "./site-content";
import { blogLabels, findBlogPost, postsForTag } from "./blog-content";
import { pageCoverImages } from "./page-extras";
import { contentOverrides } from "./content-overrides";
import { aboutPageCopy } from "./about-content";
import {
  clinicHubCopy, findTeamMember, galleryCollections, getTeamMembers,
  resolvePageTemplate, supplementalCoverImages, supplementalPages,
  teamLabels, type GalleryRoute, type TeamArea,
} from "./structured-content";

const pathIcons: LucideIcon[] = [Stethoscope, Lightbulb, Microscope];
const pathCardSlugs = ["clinical-care", "innovation", "research"] as const;
const facetIcons: LucideIcon[] = [Hand, BrainCircuit, Lightbulb, Microscope];
const innovationImages = [
  "/media/innovation/external-fixator.jpg",
  "/media/innovation/bionic-hand.png",
  "/media/innovation/magnetic-distractor.png",
];
const awardsSectionCopy = {
  en: { kicker: "RECOGNITION", title: "Awards and certificates", intro: "Selected awards, certificates, and professional recognition from Dr. Moradi’s clinical, academic, and innovation work." },
  fa: { kicker: "افتخارات", title: "جوایز و گواهی‌ها", intro: "منتخبی از جوایز، گواهی‌ها و تقدیرهای حرفه‌ای دکتر مرادی در فعالیت‌های بالینی، دانشگاهی و نوآوری." },
  ar: { kicker: "التقدير", title: "الجوائز والشهادات", intro: "مجموعة مختارة من جوائز الدكتور مرادي وشهاداته والتقدير المهني لأعماله السريرية والأكاديمية والابتكارية." },
} as const;
const connectedPracticeImages = [
  "/media/connected-practice/01-injury.jpg",
  "/media/connected-practice/02-innovation.jpg",
  "/media/connected-practice/03-application.jpg",
  "/media/connected-practice/04-life.jpg",
];
const connectedStepNumbers = {
  en: ["01", "02", "03", "04"],
  fa: ["۰۱", "۰۲", "۰۳", "۰۴"],
  ar: ["٠١", "٠٢", "٠٣", "٠٤"],
} as const;
const languageNames = { en: "🇬🇧 English", fa: "🇮🇷 فارسی", ar: "🇸🇦 العربية" };
const navSlugs = ["clinical-care", "innovation", "research", "education", "about", "blog"] as const;
function localizedHref(locale: Locale, slug = "") {
  if (locale === "en") return slug ? `/${slug}` : "/";
  return slug ? `/${locale}/${slug}` : `/${locale}`;
}

function Reveal({ children, className = "" }: { children: React.ReactNode; className?: string }) {
  return <div className={`reveal ${className}`}>{children}</div>;
}

function DirectionalArrow({ rtl, size = 17 }: { rtl: boolean; size?: number }) {
  return <ArrowRight className={rtl ? "flip-icon" : ""} size={size} />;
}

export function SitePage({ locale, page }: { locale: Locale; page: string }) {
  const t = content[locale] as SiteCopy;
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

  const nav = useMemo(() => t.nav.map((label, index) => ({ label, slug: navSlugs[index] })), [t.nav]);
  const currentSlug = page === "home" ? "" : page;
  const headerLogo = locale === "en" ? "/brand/logo.en.svg" : "/brand/logo.fa-ar.svg";
  const template = resolvePageTemplate(page);

  return (
    <main className={`${rtl ? "rtl" : "ltr"} locale-${locale}`}>
      <a className="skip-link" href="#content">{t.skip}</a>
      <header className="site-header">
        <a className="brand" href={localizedHref(locale)} aria-label={t.homeLabel}>
          <Image src={headerLogo} alt="Dr. Ali Moradi" width={153} height={50} priority />
        </a>
        <nav className="desktop-nav" aria-label={t.primaryNav}>
          {nav.map((item) => <a className="nav-link" href={localizedHref(locale, item.slug)} key={item.slug}>{item.label}</a>)}
        </nav>
        <div className="header-actions">
          <div className="language-control">
            <button className="language-button" onClick={() => setLanguageOpen(!languageOpen)} aria-expanded={languageOpen} aria-label={t.chooseLanguage}>
              <Languages size={18} /><span>{locale.toUpperCase()}</span><ChevronDown size={14} />
            </button>
            {languageOpen && <div className="language-menu">
              {(["en", "fa", "ar"] as Locale[]).map((lang) => <a className={locale === lang ? "selected" : ""} key={lang} href={localizedHref(lang, currentSlug)}>{languageNames[lang]}</a>)}
            </div>}
          </div>
          <a className="button button-small" href="https://nobat.ir/9705" target="_blank" rel="noreferrer">{t.appointment}<ExternalLink size={15} /></a>
          <button className="menu-button" onClick={() => setMenuOpen(!menuOpen)} aria-label={t.menuToggle}>{menuOpen ? <X /> : <Menu />}</button>
        </div>
      </header>
      {menuOpen && <nav className="mobile-nav" aria-label={t.mobileNav}>
        {nav.map((item) => <a className="mobile-nav-link" href={localizedHref(locale, item.slug)} key={item.slug}>{item.label}<ChevronRight className={rtl ? "flip-icon" : ""} size={17} /></a>)}
        <a className="button" href="https://nobat.ir/9705" target="_blank" rel="noreferrer">{t.appointment}<ExternalLink size={16} /></a>
      </nav>}

      <div id="content">
        {page === "home" ? <HomePage locale={locale} t={t} rtl={rtl} />
          : page === "clinical-care" ? <ClinicPage locale={locale} t={t} />
          : page === "contact" ? <ContactPage t={t} rtl={rtl} />
          : page in galleryCollections ? <GalleryPage locale={locale} page={page as GalleryRoute} t={t} rtl={rtl} />
          : page.startsWith("team/") ? <TeamProfilePage locale={locale} slug={page.slice(5)} t={t} rtl={rtl} />
          : page === "about" ? <AboutPage locale={locale} t={t} rtl={rtl} />
          : page === "blog" || page === "news" ? <BlogArchive locale={locale} t={t} rtl={rtl} filter={page === "news" ? "news" : "blog"} />
          : template === "single-post" ? <BlogPostPage locale={locale} slug={page.slice(5)} t={t} rtl={rtl} />
          : <InteriorPage locale={locale} page={page} t={t} rtl={rtl} />}
      </div>
      <Footer locale={locale} t={t} />
    </main>
  );
}

function HomePage({ locale, t, rtl }: { locale: Locale; t: SiteCopy; rtl: boolean }) {
  const [openAppointment, setOpenAppointment] = useState(0);
  const innovationPosts = postsForTag("innovation").slice(0, 3);
  const awardsPosts = postsForTag("awards").slice(0, 4);
  const awardsCopy = awardsSectionCopy[locale];

  return <>
    <section className="hero">
      <Image className="hero-background" src="/media/hero/hero-bg-v2.jpg" alt={t.heroAlt} fill priority unoptimized sizes="100vw" />
      <div className="hero-wash" aria-hidden="true" />
      <div className="hero-orbits" aria-hidden="true">
        <div className="orbit orbit-one" />
        <div className="orbit orbit-two" />
        <div className="hero-dot dot-one" />
        <div className="hero-dot dot-two" />
      </div>
      <div className="hero-layout section-shell">
        <div className="hero-copy">
          <h1><span>{t.heroName[0]}</span> <strong>{t.heroName[1]}</strong></h1>
          <ul className="hero-credentials">
            {t.heroCredentials.map(([lead, detail]) => <li key={lead}>
              <span className="hero-credential-dot" aria-hidden="true" />
              <span className="hero-credential-copy"><strong>{lead}</strong> <span>{detail}</span></span>
            </li>)}
          </ul>
          <p className="hero-description">{t.heroDescription}</p>
        </div>
        <div className="hero-note"><Quote size={36} /><span>{t.heroQuote}</span></div>
        <div className="facet-bar section-shell" aria-label={t.pathsTitle}>
          {t.facets.map(([top, bottom], index) => { const Icon = facetIcons[index]; return <div className="facet" key={top}><Icon size={27} /><span><small>{top}</small><strong>{bottom}</strong></span></div>; })}
        </div>
        <div className="credential-list">{t.credentials.map((item) => <span key={item}><Check size={15} />{item}</span>)}</div>
      </div>
    </section>

    <section className="journey section-space" aria-label={t.storyTitle}>
      <div className="section-shell">
        <Reveal className="section-heading split-heading journey-heading">
          <div><p className="section-index light">{t.indexes[1]}</p><p>{t.storyBody}</p></div>
          <a className="text-link light" href={localizedHref(locale, "innovation")}>{t.journeyLink}<DirectionalArrow rtl={rtl} /></a>
        </Reveal>
        <div className="connected-grid">
          {t.journey.map(([overline, title], index) => <Reveal className="connected-card" key={title}>
            <div className="connected-image"><Image src={connectedPracticeImages[index]} alt={title} fill unoptimized sizes="(max-width: 560px) 34vw, 22vw" /></div>
            {index < 3 && <ChevronRight className="connected-arrow" size={48} strokeWidth={1} aria-hidden="true" />}
            <div className="connected-meta"><span>{connectedStepNumbers[locale][index]}</span><div><small>{overline}</small><h3>{title}</h3></div></div>
          </Reveal>)}
        </div>
      </div>
    </section>

    <section id="pathways" className="pathways section-space section-shell">
      <Reveal className="section-heading"><p className="section-index">{t.indexes[0]}</p><p>{t.pathsTitle}. {t.pathsBody}</p></Reveal>
      <div className="path-grid">
        {t.pathCards.map(([title, text], index) => { const Icon = pathIcons[index]; const slug = pathCardSlugs[index]; return <Reveal className="path-card" key={slug}>
          <Icon className="path-icon-main" size={42} /><Icon className="path-icon-ghost" size={170} strokeWidth={1} aria-hidden="true" />
          <h3>{title}</h3><p>{text}</p><a className="button button-small" href={localizedHref(locale, slug)} aria-label={title}>{t.pathCtas[index]}<DirectionalArrow rtl={rtl} size={16} /></a>
        </Reveal>; })}
      </div>
    </section>

    <section className="innovation section-space section-shell">
      <Reveal className="section-heading"><p className="section-index">{t.indexes[3]}</p><p>{t.innovationTitle}. {t.innovationIntro}</p></Reveal>
      <div className="innovation-grid">{t.innovations.map(([tag, title, text], index) => <Reveal className={`innovation-card innovation-${index + 1}`} key={title}>
        <div className="innovation-art"><Image src={innovationImages[index]} alt={title} fill unoptimized sizes="(max-width: 820px) 90vw, 31vw" /></div>
        <p className="card-tag">{tag}</p><h3>{title}</h3><p>{text}</p><a href={localizedHref(locale, `blog/${innovationPosts[index]?.slug ?? "external-fixation-explained"}`)}>{t.readStory}<DirectionalArrow rtl={rtl} size={16} /></a>
      </Reveal>)}</div>
    </section>

    <section className="impact section-space"><div className="section-shell">
      <Reveal className="section-heading"><p className="section-index">{t.indexes[4]}</p><p>{t.impactTitle}. {t.figuresNote}</p></Reveal>
      <div className="metrics">{t.metrics.map(([number, label]) => <Reveal className="metric" key={label}><strong>{number}</strong><span>{label}</span></Reveal>)}</div>
    </div></section>

    <section className="appointments section-space section-shell">
      <Reveal className="section-heading"><p className="section-index">{t.indexes[5]}</p><p>{t.appointmentTitle}. {t.appointmentBody}</p></Reveal>
      <div className="appointment-layout">
        <Reveal className="appointment-accordion">
          {t.appointmentCards.map(([tag, title, text], index) => {
            const isOpen = openAppointment === index;
            return <div className={`appointment-item ${isOpen ? "is-open" : ""}`} key={title}>
              <button className="appointment-trigger" type="button" onClick={() => setOpenAppointment(isOpen ? -1 : index)} aria-expanded={isOpen}>
                <span><small>{tag}</small>{title}</span><ChevronDown size={20} strokeWidth={1.5} />
              </button>
              {isOpen && <div className="appointment-panel">
                <p>{text}</p>
                {index === 0 && <><a className="button" href="https://nobat.ir/9705" target="_blank" rel="noreferrer">{t.continueNobat}<ExternalLink size={16} /></a><small>{t.opensBooking}</small></>}
                {index === 1 && <span className="pending-label">{t.onlineNote}</span>}
                {index === 2 && <><div className="schedule"><CalendarDays size={18} /><span>{t.urgentDays}</span></div><div className="schedule"><Clock3 size={18} /><span>{t.urgentHours}</span></div><span className="pending-label">{t.urgentInstruction}</span></>}
                {index === 3 && <span className="pending-label">{t.screeningNote}</span>}
              </div>}
            </div>;
          })}
        </Reveal>
        <Reveal className="appointment-portrait"><Image src="/media/appointments/doctor.jpg" alt={t.appointmentPortraitAlt} fill unoptimized sizes="(max-width: 820px) 100vw, 42vw" /></Reveal>
      </div>
      <p className="medical-note"><ShieldCheck size={18} />{t.medicalNote}</p>
    </section>

    <section className="news section-space section-shell">
      <Reveal className="section-heading split-heading"><div><p className="section-index">{awardsCopy.kicker}</p><p>{awardsCopy.title}</p><span className="section-heading-intro">{awardsCopy.intro}</span></div><a className="text-link" href={localizedHref(locale, "news")}>{t.allUpdates}<DirectionalArrow rtl={rtl} /></a></Reveal>
      <div className="news-grid">{awardsPosts.map((post) => <Reveal className="news-card" key={post.slug}>
        <a className="news-card-link" href={localizedHref(locale, `blog/${post.slug}`)} aria-label={post.title[locale]}>
          <Image src={post.image} alt="" fill unoptimized sizes="(max-width: 560px) 92vw, (max-width: 1120px) 45vw, 23vw" />
          <span className="news-card-gradient" aria-hidden="true" />
          <span className="news-card-content"><small>{post.category[locale]}</small><strong>{post.title[locale]}</strong></span>
        </a>
      </Reveal>)}</div>
    </section>

    <section className="about-preview section-space"><div className="section-shell about-grid">
      <Reveal className="about-copy"><p className="section-index light">{t.indexes[7]}</p><p>{t.aboutBody}</p><div className="about-actions"><a className="button button-light" href={localizedHref(locale, "about")}>{t.meetDoctor}<DirectionalArrow rtl={rtl} /></a><a className="text-link light" href={localizedHref(locale, "research")}>{t.researchProfile}<FileText size={17} /></a></div></Reveal>
      <Reveal className="about-media"><div className="about-rings" aria-hidden="true" /><div className="pulse-line" aria-hidden="true" /><Image src="/media/about/office.jpg" alt={t.aboutImageAlt} fill unoptimized sizes="(max-width: 820px) 100vw, 42vw" /></Reveal>
    </div></section>
  </>;
}

function InteriorCover({ image, imageAlt, kicker, title, intro }: { image: string; imageAlt: string; kicker: string; title: string; intro: string }) {
  const cover = useRef<HTMLElement>(null);
  useEffect(() => {
    let frame = 0;
    const update = () => {
      cancelAnimationFrame(frame);
      frame = requestAnimationFrame(() => cover.current?.style.setProperty("--cover-shrink", `${Math.min(window.scrollY * .42, 120)}px`));
    };
    update();
    window.addEventListener("scroll", update, { passive: true });
    return () => { cancelAnimationFrame(frame); window.removeEventListener("scroll", update); };
  }, []);
  return <section className="interior-cover" ref={cover}>
    <Image src={image} alt={imageAlt} fill priority unoptimized sizes="100vw" />
    <div className="interior-cover-gradient" aria-hidden="true" />
    <div className="interior-cover-content section-shell"><p className="section-index light">{kicker}</p><h1>{title}</h1>{intro && <p>{intro}</p>}</div>
  </section>;
}

function PageAppointmentCta({ t }: { t: SiteCopy }) {
  return <section className="page-appointment section-space section-shell"><Reveal className="next-step page-appointment-card">
    <CalendarDays /><div><p>{t.pageAppointment}</p><h2>{t.appointmentTitle}</h2><span>{t.appointmentBody}</span></div>
    <a className="button" href="https://nobat.ir/9705" target="_blank" rel="noreferrer">{t.continueNobat}<ExternalLink size={16} /></a>
  </Reveal><p className="medical-note"><ShieldCheck size={18} />{t.medicalNote}</p></section>;
}

function InteriorPage({ locale, page, t, rtl }: { locale: Locale; page: string; t: SiteCopy; rtl: boolean }) {
  const pages = t.pages as Record<string, InteriorPageData>;
  const normalizedPage = page === "news" ? "blog" : page;
  const data = contentOverrides[locale][normalizedPage] || supplementalPages[locale][normalizedPage] || pages[normalizedPage] || contentOverrides[locale].about;
  const image = supplementalCoverImages[normalizedPage]
    || pageCoverImages[normalizedPage]
    || (normalizedPage.startsWith("innovation/") ? pageCoverImages.innovation : pageCoverImages.about);
  const backToClinic = normalizedPage.startsWith("clinical-care/");
  const backToInnovation = normalizedPage.startsWith("innovation/");
  const innovationBackLabel = locale === "fa" ? "بازگشت به فهرست نوآوری‌ها" : locale === "ar" ? "العودة إلى قائمة الابتكارات" : "Back to all innovations";
  const scholarCopy = locale === "fa"
    ? { title: "مشاهده پروفایل Google Scholar", text: "فهرست مقاله‌ها، استنادها و تازه‌ترین خروجی پژوهشی دکتر مرادی را در پروفایل عمومی Google Scholar دنبال کنید.", action: "بازکردن Google Scholar" }
    : locale === "ar"
      ? { title: "عرض ملف Google Scholar", text: "تابع المقالات والاستشهادات وأحدث المخرجات البحثية للدكتور مرادي في ملفه العام.", action: "فتح Google Scholar" }
      : { title: "View the Google Scholar profile", text: "Explore Dr. Moradi’s publications, citations, and latest research output in the public Google Scholar profile.", action: "Open Google Scholar" };
  return <>
    <InteriorCover image={image} imageAlt={data.title} kicker={data.kicker} title={data.title} intro={data.intro} />
    {backToClinic && <div className="interior-back section-shell"><a href={localizedHref(locale, "clinical-care")}><DirectionalArrow rtl={!rtl} size={16} />{clinicHubCopy[locale].backToClinic}</a></div>}
    {backToInnovation && <div className="interior-back section-shell"><a href={localizedHref(locale, "innovation")}><DirectionalArrow rtl={!rtl} size={16} />{innovationBackLabel}</a></div>}
    {data.sections.length > 0 && <section className="page-content section-space section-shell large-counts">
      <aside><p>{t.onThisPage}</p>{data.sections.map((section, i) => <a key={section.title} href={`#section-${i}`}>{section.title}</a>)}<a href="https://nobat.ir/9705" target="_blank" rel="noreferrer">{t.pageAppointment}<ExternalLink size={14} /></a></aside>
      <div className="content-sections">
        {data.sections.map((section, i) => <Reveal className="content-section" key={section.title}><span className="section-count">{String(i + 1).padStart(2, "0")}</span><div><h2 id={`section-${i}`}>{section.title}</h2><p>{section.text}</p>{section.items && <ul>{section.items.map(item => <li key={item}><Check size={17} />{item}</li>)}</ul>}{section.links && <div className="content-links">{section.links.map((link) => {
          const href = link.external || link.href.startsWith("/") ? link.href : localizedHref(locale, link.href);
          return <a className="text-link" href={href} key={`${section.title}-${link.href}`} target={link.external ? "_blank" : undefined} rel={link.external ? "noreferrer" : undefined} download={link.download || undefined}>{link.label}{link.external ? <ExternalLink size={15} /> : <DirectionalArrow rtl={rtl} size={15} />}</a>;
        })}</div>}</div></Reveal>)}
        {normalizedPage === "research"
          ? <Reveal className="next-step"><Sparkles /><div><p>{t.nextLabel}</p><h2>{scholarCopy.title}</h2><span>{scholarCopy.text}</span></div><a className="button" href="https://scholar.google.com/citations?user=UhXLjGEAAAAJ&hl=en" target="_blank" rel="noreferrer">{scholarCopy.action}<ExternalLink size={16} /></a></Reveal>
          : <Reveal className="next-step"><Sparkles /><div><p>{t.nextLabel}</p><h2>{data.ctaTitle}</h2><span>{data.ctaText}</span></div><a className="button" href={localizedHref(locale, normalizedPage === "about" ? "research" : "contact")}>{t.exploreMore}<DirectionalArrow rtl={rtl} size={16} /></a></Reveal>}
      </div>
    </section>}
    {normalizedPage === "innovation" && <TeamSection locale={locale} rtl={rtl} area="innovation" />}
    {normalizedPage === "research" && <TeamSection locale={locale} rtl={rtl} area="research" />}
    <PageAppointmentCta t={t} />
  </>;
}

function AboutPage({ locale, t, rtl }: { locale: Locale; t: SiteCopy; rtl: boolean }) {
  const copy = aboutPageCopy[locale];
  const principleIcons = [Stethoscope, Microscope, Lightbulb];
  return <div className="about-page">
    <InteriorCover image={pageCoverImages.about} imageAlt={copy.title} kicker={copy.kicker} title={copy.title} intro={copy.intro} />

    <section className="about-story section-space"><div className="section-shell about-story-grid">
      <Reveal className="about-story-media"><Image src="/media/edited/dr-moradi-hero-v1.png" alt={copy.storyTitle} fill unoptimized sizes="(max-width: 820px) 92vw, 45vw" /></Reveal>
      <Reveal className="about-story-copy"><p className="section-index">{copy.storyKicker}</p><h2>{copy.storyTitle}</h2>
        {copy.storyText.map((paragraph) => <p key={paragraph}>{paragraph}</p>)}
        <div className="about-credentials">{copy.credentials.map((credential) => <span key={credential}><Check size={15} />{credential}</span>)}</div>
      </Reveal>
    </div></section>

    <section className="about-practice section-space"><div className="section-shell">
      <Reveal className="about-practice-lead"><div><p className="section-index">{copy.practiceKicker}</p><h2>{copy.practiceTitle}</h2><p>{copy.practiceText}</p></div>
        <div className="about-practice-image"><Image src="/media/about/office.jpg" alt={copy.practiceTitle} fill unoptimized sizes="(max-width: 820px) 92vw, 42vw" /></div>
      </Reveal>
      <div className="about-principles">{copy.principles.map((principle, index) => { const Icon = principleIcons[index]; return <Reveal className="about-principle" key={principle.title}><Icon /><span>0{index + 1}</span><h3>{principle.title}</h3><p>{principle.text}</p></Reveal>; })}</div>
    </div></section>

    <section className="about-journey section-space"><div className="section-shell about-journey-grid">
      <Reveal className="about-journey-intro"><p className="section-index">{copy.journeyKicker}</p><h2>{copy.journeyTitle}</h2><p>{copy.journeyText}</p><div className="about-journey-image"><Image src="/media/appointments/doctor.jpg" alt={copy.journeyTitle} fill unoptimized sizes="(max-width: 820px) 92vw, 34vw" /></div></Reveal>
      <div className="about-timeline">{copy.timeline.map((item) => <Reveal className="about-timeline-item" key={`${item.years}-${item.title}`}><span>{item.years}</span><div><h3>{item.title}</h3><p>{item.text}</p></div></Reveal>)}</div>
    </div></section>

    <section className="about-ecosystem section-space"><div className="section-shell">
      <Reveal className="section-heading"><p className="section-index">{copy.ecosystemKicker}</p><h2>{copy.ecosystemTitle}</h2><p>{copy.ecosystemText}</p></Reveal>
      <div className="about-ecosystem-grid">{copy.ecosystem.map((item) => <Reveal className="about-ecosystem-card" key={item.slug}><a href={localizedHref(locale, item.slug)}><span className="about-ecosystem-image"><Image src={item.image} alt="" fill unoptimized sizes="(max-width: 820px) 92vw, 31vw" /></span><span className="about-ecosystem-copy"><strong>{item.title}</strong><small>{item.text}</small><i>{t.exploreMore}<DirectionalArrow rtl={rtl} size={15} /></i></span></a></Reveal>)}</div>
    </div></section>

    <section className="about-recognition section-space"><Reveal className="section-shell about-recognition-grid">
      <div className="about-recognition-image"><Image src="/media/pages/blog-cover.jpg" alt={copy.recognitionTitle} fill unoptimized sizes="(max-width: 820px) 92vw, 48vw" /></div>
      <div><p className="section-index">{copy.recognitionKicker}</p><h2>{copy.recognitionTitle}</h2><p>{copy.recognitionText}</p><a className="button" href={localizedHref(locale, "news")}>{copy.recognitionAction}<DirectionalArrow rtl={rtl} size={16} /></a></div>
    </Reveal></section>
    <PageAppointmentCta t={t} />
  </div>;
}

function TeamSection({ locale, rtl, area }: { locale: Locale; rtl: boolean; area: TeamArea }) {
  const labels = teamLabels[locale];
  const members = getTeamMembers(area);
  return <section className={`team-section team-${area} section-space`}><div className="section-shell">
    <Reveal className="section-heading split-heading"><div><p className="section-index">{labels.kicker}</p><p>{labels.title}. {labels.intro}</p></div></Reveal>
    <div className="team-grid">{members.map((member) => <Reveal className="team-card" key={member.slug}>
      <a className="team-card-image" href={localizedHref(locale, `team/${member.slug}`)}><Image src={member.image} alt={member.name[locale]} fill unoptimized sizes="(max-width: 560px) 92vw, (max-width: 1120px) 44vw, 24vw" /></a>
      <div className="team-card-copy"><p>{member.role[locale]}</p><h3><a href={localizedHref(locale, `team/${member.slug}`)}>{member.name[locale]}</a></h3><span>{member.summary[locale]}</span><a className="text-link" href={localizedHref(locale, `team/${member.slug}`)}>{labels.readProfile}<DirectionalArrow rtl={rtl} size={15} /></a></div>
    </Reveal>)}</div>
  </div></section>;
}

function GalleryCollection({ locale, rtl, images, title, full = false }: { locale: Locale; rtl: boolean; images: string[]; title: string; full?: boolean }) {
  const labels = clinicHubCopy[locale];
  const [offset, setOffset] = useState(0);
  const [active, setActive] = useState<number | null>(null);
  const shown = full ? images.map((_, index) => index) : Array.from({ length: 4 }, (_, index) => (offset + index) % images.length);

  useEffect(() => {
    if (active === null) return;
    const previousOverflow = document.body.style.overflow;
    document.body.style.overflow = "hidden";
    const onKeyDown = (event: KeyboardEvent) => {
      if (event.key === "Escape") setActive(null);
      if (event.key === "ArrowLeft") setActive((current) => current === null ? null : (current - 1 + images.length) % images.length);
      if (event.key === "ArrowRight") setActive((current) => current === null ? null : (current + 1) % images.length);
    };
    window.addEventListener("keydown", onKeyDown);
    return () => { document.body.style.overflow = previousOverflow; window.removeEventListener("keydown", onKeyDown); };
  }, [active, images.length]);

  const move = (direction: number) => setOffset((current) => (current + direction + images.length) % images.length);
  return <>
    <div className={full ? "gallery-full-grid" : "gallery-strip"}>
      {!full && <button className="gallery-nav gallery-prev" type="button" onClick={() => move(-1)} aria-label={labels.previous}><ChevronLeft className={rtl ? "flip-icon" : ""} /></button>}
      <div className={full ? "gallery-full-track" : "gallery-strip-track"}>{shown.map((index) => <button className="gallery-thumb" type="button" onClick={() => setActive(index)} key={`${images[index]}-${index}`} aria-label={`${title} ${index + 1}`}>
        <Image src={images[index]} alt={`${title} ${index + 1}`} fill unoptimized sizes={full ? "(max-width: 560px) 92vw, (max-width: 820px) 46vw, 24vw" : "(max-width: 560px) 92vw, (max-width: 820px) 46vw, 23vw"} />
        <span>{String(index + 1).padStart(2, "0")}</span>
      </button>)}</div>
      {!full && <button className="gallery-nav gallery-next" type="button" onClick={() => move(1)} aria-label={labels.next}><ChevronRight className={rtl ? "flip-icon" : ""} /></button>}
    </div>
    {active !== null && <div className="gallery-modal" role="dialog" aria-modal="true" aria-label={title} onClick={() => setActive(null)}>
      <div className="gallery-modal-panel" onClick={(event) => event.stopPropagation()}>
        <button className="gallery-modal-close" type="button" onClick={() => setActive(null)} aria-label={labels.close} autoFocus><X /></button>
        <div className="gallery-modal-image"><Image src={images[active]} alt={`${title} ${active + 1}`} fill priority unoptimized sizes="94vw" /></div>
        <button className="gallery-modal-nav gallery-modal-prev" type="button" onClick={() => setActive((active - 1 + images.length) % images.length)} aria-label={labels.previous}><ChevronLeft /></button>
        <button className="gallery-modal-nav gallery-modal-next" type="button" onClick={() => setActive((active + 1) % images.length)} aria-label={labels.next}><ChevronRight /></button>
        <span className="gallery-modal-count">{active + 1} / {images.length}</span>
      </div>
    </div>}
  </>;
}

function ClinicPage({ locale, t }: { locale: Locale; t: SiteCopy }) {
  const data = (t.pages as Record<string, InteriorPageData>)["clinical-care"];
  const c = clinicHubCopy[locale];
  const rtl = locale !== "en";
  const clinicGallery = galleryCollections["clinical-care/clinic-gallery"].images;
  const hospitalGallery = galleryCollections["clinical-care/hospital-gallery"].images;
  return <>
    <InteriorCover image={pageCoverImages["clinical-care"]} imageAlt={data.title} kicker={data.kicker} title={data.title} intro={data.intro} />
    <section className="clinic-pathways section-space section-shell">
      <div className="clinic-pathway-grid">{c.pathways.map((path) => <Reveal className="clinic-pathway-card" key={path.slug}>
        <a className="clinic-pathway-link" href={localizedHref(locale, path.slug)}>
          <span className="clinic-pathway-image"><Image src={path.image} alt="" fill unoptimized sizes="(max-width: 820px) 92vw, 46vw" /></span>
          <span className="clinic-pathway-title">{path.title}</span>
        </a>
      </Reveal>)}</div>
    </section>
    <TeamSection locale={locale} rtl={rtl} area="clinic" />
    <section className="clinic-gallery-row section-space"><div className="section-shell">
      <Reveal className="section-heading split-heading"><div><p className="section-index">{c.clinicGalleryTitle}</p><p>{c.clinicGalleryIntro}</p></div><a className="text-link" href={localizedHref(locale, "clinical-care/clinic-gallery")}>{c.viewGallery}<DirectionalArrow rtl={rtl} /></a></Reveal>
      <GalleryCollection locale={locale} rtl={rtl} images={clinicGallery} title={c.clinicGalleryTitle} />
    </div></section>
    <section className="clinic-gallery-row hospital-gallery-row section-space"><div className="section-shell">
      <Reveal className="section-heading split-heading"><div><p className="section-index">{c.hospitalGalleryTitle}</p><p>{c.hospitalGalleryIntro}</p></div><a className="text-link" href={localizedHref(locale, "clinical-care/hospital-gallery")}>{c.viewGallery}<DirectionalArrow rtl={rtl} /></a></Reveal>
      <GalleryCollection locale={locale} rtl={rtl} images={hospitalGallery} title={c.hospitalGalleryTitle} />
    </div></section>
    <PageAppointmentCta t={t} />
  </>;
}

function GalleryPage({ locale, page, t, rtl }: { locale: Locale; page: GalleryRoute; t: SiteCopy; rtl: boolean }) {
  const c = clinicHubCopy[locale];
  const gallery = galleryCollections[page];
  const isClinic = gallery.area === "clinic";
  const title = isClinic ? c.clinicGalleryTitle : c.hospitalGalleryTitle;
  const intro = isClinic ? c.clinicGalleryIntro : c.hospitalGalleryIntro;
  return <>
    <InteriorCover image={supplementalCoverImages[page]} imageAlt={title} kicker={c.pathwaysKicker} title={title} intro={intro} />
    <div className="interior-back section-shell"><a href={localizedHref(locale, "clinical-care")}><DirectionalArrow rtl={!rtl} size={16} />{c.backToClinic}</a></div>
    <section className="gallery-page section-space section-shell"><GalleryCollection locale={locale} rtl={rtl} images={gallery.images} title={title} full /></section>
    <PageAppointmentCta t={t} />
  </>;
}

function TeamProfilePage({ locale, slug, t, rtl }: { locale: Locale; slug: string; t: SiteCopy; rtl: boolean }) {
  const member = findTeamMember(slug);
  if (!member) return <InteriorPage locale={locale} page="about" t={t} rtl={rtl} />;
  const labels = teamLabels[locale];
  const draftBackground = locale === "fa"
    ? "این متن سابقه فعلاً پیش‌نویس است و پس از دریافت رزومه تأییدشده جایگزین می‌شود. نسخه نهایی، تحصیلات، مسئولیت‌ها، پروژه‌های منتخب و زمینه‌های مرتبط فعالیت را ثبت خواهد کرد. همه تاریخ‌ها، وابستگی‌های سازمانی و عناوین حرفه‌ای پیش از انتشار نهایی با خود عضو تیم بازبینی می‌شوند."
    : locale === "ar"
      ? "هذا النص المهني مسودة مؤقتة إلى أن تصل السيرة الذاتية الموثقة. ستوثق النسخة النهائية التعليم والمسؤوليات والمشاريع المختارة ومجالات المساهمة ذات الصلة. وستُراجع جميع التواريخ والجهات والصفات المهنية مع عضو الفريق قبل النشر النهائي."
      : "This background text is an intentionally provisional draft until a verified CV is supplied. The final version will document education, appointments, selected projects, and relevant areas of contribution. All dates, affiliations, and professional titles will be reviewed with the team member before final publication.";
  const backArea = member.areas.includes("clinic") ? "clinical-care" : member.areas.includes("innovation") ? "innovation" : "research";
  return <>
    <InteriorCover image="/media/pages/team-profile-cover.jpg" imageAlt={member.name[locale]} kicker={labels.profileIntro} title={member.name[locale]} intro={member.role[locale]} />
    <div className="interior-back section-shell"><a href={localizedHref(locale, backArea)}><DirectionalArrow rtl={!rtl} size={16} />{labels.back}</a></div>
    <section className="team-profile section-space section-shell">
      <Reveal className="team-profile-image"><Image src={member.image} alt={member.name[locale]} fill priority unoptimized sizes="(max-width: 820px) 92vw, 34vw" /></Reveal>
      <div className="team-profile-body">
        <Reveal><p className="section-index">{labels.expertise}</p><h2>{member.role[locale]}</h2><p>{member.bio[locale]} {draftBackground}</p></Reveal>
        <Reveal className="team-profile-note"><Sparkles /><div><h3>{labels.collaboration}</h3><p>{member.summary[locale]}</p></div></Reveal>
      </div>
    </section>
    <PageAppointmentCta t={t} />
  </>;
}

function BlogArchive({ locale, t, rtl, filter }: { locale: Locale; t: SiteCopy; rtl: boolean; filter: "blog" | "news" }) {
  const labels = blogLabels[locale];
  const posts = postsForTag(filter);
  return <>
    <InteriorCover image={pageCoverImages.blog} imageAlt={labels.title} kicker={labels.kicker} title={labels.title} intro={labels.intro} />
    <section className="blog-archive section-space section-shell"><div className="blog-grid">{posts.map((post) => <Reveal className="blog-card" key={post.slug}>
      <a className="blog-card-image" href={localizedHref(locale, `blog/${post.slug}`)}><Image src={post.image} alt={post.title[locale]} fill unoptimized sizes="(max-width: 820px) 92vw, 31vw" /></a>
      <div className="blog-card-copy"><p className="card-tag">{post.category[locale]}</p><h2><a href={localizedHref(locale, `blog/${post.slug}`)}>{post.title[locale]}</a></h2><p>{post.excerpt[locale]}</p><div className="blog-card-meta"><span>{post.readMinutes} {labels.minutes}</span><a href={localizedHref(locale, `blog/${post.slug}`)}>{labels.read}<DirectionalArrow rtl={rtl} size={15} /></a></div></div>
    </Reveal>)}</div></section>
    <PageAppointmentCta t={t} />
  </>;
}

function BlogPostPage({ locale, slug, t, rtl }: { locale: Locale; slug: string; t: SiteCopy; rtl: boolean }) {
  const post = findBlogPost(slug);
  if (!post) return <BlogArchive locale={locale} t={t} rtl={rtl} filter="blog" />;
  const labels = blogLabels[locale];
  const formattedDate = new Intl.DateTimeFormat(locale === "fa" ? "fa-IR" : locale === "ar" ? "ar" : "en-GB", { year: "numeric", month: "long", day: "numeric" }).format(new Date(post.date));
  return <>
    <InteriorCover image={post.image} imageAlt={post.title[locale]} kicker={post.category[locale]} title={post.title[locale]} intro={post.excerpt[locale]} />
    <article className="single-post section-space section-shell">
      <div className="article-meta"><span>{formattedDate}</span><span>{post.readMinutes} {labels.minutes}</span></div>
      <div className="article-layout"><aside><a href={localizedHref(locale, "blog")}><DirectionalArrow rtl={!rtl} size={16} />{labels.back}</a></aside><div className="article-body">
        <section><h2>{labels.overview}</h2><p>{post.excerpt[locale]}</p></section>
        <section><h2>{labels.assessment}</h2><p>{labels.assessmentText}</p></section>
        <section><h2>{labels.nextSteps}</h2><p>{labels.nextText}</p></section>
        <p className="article-disclaimer"><ShieldCheck size={20} />{labels.disclaimer}</p>
      </div></div>
    </article>
    <PageAppointmentCta t={t} />
  </>;
}

function ContactPage({ t, rtl }: { t: SiteCopy; rtl: boolean }) {
  const [sent, setSent] = useState(false);
  const [errors, setErrors] = useState<Record<string, string>>({});
  function submit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const data = new FormData(event.currentTarget);
    const next: Record<string, string> = {};
    const name = String(data.get("name") || "").trim();
    const email = String(data.get("email") || "").trim();
    const message = String(data.get("message") || "").trim();
    if (name.length < 2) next.name = t.contact.errors[0];
    if (!/^\S+@\S+\.\S+$/.test(email)) next.email = t.contact.errors[1];
    if (message.length < 20) next.message = t.contact.errors[2];
    if (data.get("consent") !== "on") next.consent = t.contact.errors[3];
    setErrors(next);
    if (!Object.keys(next).length) {
      const type = String(data.get("type") || t.contact.formOptions[0]);
      const subject = encodeURIComponent(`${type} — ${name}`);
      const body = encodeURIComponent(`${message}\n\n${name}\n${email}`);
      setSent(true);
      window.location.href = `mailto:info@DrAliMoradi.com?subject=${subject}&body=${body}`;
    }
  }
  return <>
    <InteriorCover image={pageCoverImages.contact} imageAlt={t.contact.title} kicker={t.contact.kicker} title={t.contact.title} intro={t.contact.intro} />
    <section className="contact-layout section-space section-shell">
      <div className="contact-details"><p className="section-index">{t.footer.contact}</p><h2>{t.contact.beforeTitle}</h2><p>{t.contact.beforeText}</p><a className="button" href="https://nobat.ir/9705" target="_blank" rel="noreferrer">{t.continueNobat}<ExternalLink size={16} /></a><div className="contact-list"><span><MapPin />{t.contact.office}</span><span><Mail /><a href="mailto:info@DrAliMoradi.com">info@DrAliMoradi.com</a></span><span><Phone /><a href="tel:+985132290968" dir="ltr">+98 51 3229 0968</a></span></div></div>
      {sent ? <div className="form-success"><div><Check /></div><h2>{t.contact.readyTitle}</h2><p>{t.contact.readyText}</p><button className="text-link" onClick={() => setSent(false)}>{t.contact.another}<DirectionalArrow rtl={rtl} size={16} /></button></div> : <form className="contact-form" onSubmit={submit} noValidate>
        <div className="field-row"><label>{t.contact.formName}<input name="name" autoComplete="name" aria-invalid={!!errors.name} /></label><label>{t.contact.formEmail}<input name="email" type="email" autoComplete="email" aria-invalid={!!errors.email} /></label></div>
        {(errors.name || errors.email) && <div className="error-row"><span>{errors.name}</span><span>{errors.email}</span></div>}
        <label>{t.contact.formType}<select name="type" defaultValue={t.contact.formOptions[0]}>{t.contact.formOptions.map(option => <option value={option} key={option}>{option}</option>)}</select></label>
        <label>{t.contact.formMessage}<textarea name="message" rows={6} aria-invalid={!!errors.message} placeholder={t.contact.placeholder} /></label>{errors.message && <span className="field-error">{errors.message}</span>}
        <label className="consent"><input name="consent" type="checkbox" /><span>{t.contact.consent}</span></label>{errors.consent && <span className="field-error consent-error">{errors.consent}</span>}
        <button className="button" type="submit">{t.contact.submit}<DirectionalArrow rtl={rtl} size={16} /></button>
      </form>}
    </section>
    <PageAppointmentCta t={t} />
  </>;
}

function Footer({ locale, t }: { locale: Locale; t: SiteCopy }) {
  const footerLogo = locale === "en" ? "/brand/logo.en-footer.svg" : "/brand/logo.fa-ar-footer.svg";
  const mapUrl = "https://www.google.com/maps/search/?api=1&query=Mashhad+Poursina+Building+Arya+Hospital";
  const footerPages = t.pages as Record<string, InteriorPageData>;
  return <footer className="site-footer">
    <div className="section-shell footer-grid">
      <div className="footer-brand"><Image src={footerLogo} alt="Dr. Ali Moradi" width={153} height={50} /><p>{t.footer.bio}</p><a className="footer-book" href="https://nobat.ir/9705" target="_blank" rel="noreferrer">{t.footer.booking}<ExternalLink size={15} /></a></div>
      <div><h3>{t.footer.explore}</h3>{pageSlugs.slice(0, 6).map((slug) => <a key={slug} href={localizedHref(locale, slug)}>{footerPages[slug].kicker}</a>)}</div>
      <div><h3>{t.footer.resources}</h3><a href={localizedHref(locale, "patient-resources/before-surgery")}>{t.footer.before}</a><a href={localizedHref(locale, "patient-resources/after-surgery")}>{t.footer.after}</a><a href={localizedHref(locale, "patient-resources/faq")}>{t.footer.faq}</a><a href={localizedHref(locale, "patient-resources/rehabilitation")}>{t.footer.rehab}</a></div>
      <div className="footer-contact"><h3>{t.footer.contact}</h3><a href="mailto:info@DrAliMoradi.com"><Mail />info@DrAliMoradi.com</a><a href="tel:+985132290968" dir="ltr"><Phone />+98 51 3229 0968</a><p><MapPin />{t.contact.office}</p><p><MapPin />{t.contact.clinic}</p><a href={mapUrl} target="_blank" rel="noreferrer"><MapPin />{t.footer.map}<ExternalLink size={13} /></a></div>
      <div className="footer-social"><h3>{t.footer.social}</h3><a href="https://www.instagram.com/dr_ali_moradi_handsurgeon" target="_blank" rel="noreferrer"><Camera />Instagram</a><a href="https://t.me/DrAliMoradi" target="_blank" rel="noreferrer"><Send />Telegram</a><a href="https://www.aparat.com/dr_ali_moradi_handsurgeon" target="_blank" rel="noreferrer"><PlayCircle />Aparat</a></div>
    </div>
    <div className="section-shell footer-bottom"><span>{t.footer.copyright}</span><span>{t.footer.disclaimer}</span><a href="https://moghadam.pro" target="_blank" rel="noreferrer">{t.footer.credit}</a></div>
  </footer>;
}
