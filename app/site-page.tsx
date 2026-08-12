"use client";

import {
  ArrowRight, BrainCircuit, CalendarDays, Check, ChevronDown, ChevronRight,
  Camera, Clock3, ExternalLink, FileText, Hand, Languages, Lightbulb,
  Mail, MapPin, Menu, Microscope, Phone, PlayCircle, Quote, Send,
  ShieldCheck, Sparkles, Stethoscope, X,
} from "lucide-react";
import type { LucideIcon } from "lucide-react";
import Image from "next/image";
import { FormEvent, useEffect, useMemo, useState } from "react";
import { content, type InteriorPageData, type Locale, pageSlugs, type SiteCopy } from "./site-content";

const pathIcons: LucideIcon[] = [Stethoscope, Lightbulb, Microscope];
const pathCardSlugs = ["clinical-care", "innovation", "research"] as const;
const facetIcons: LucideIcon[] = [Hand, BrainCircuit, Lightbulb, Microscope];
const innovationImages = [
  "/media/innovation/external-fixator.jpg",
  "/media/innovation/bionic-hand.png",
  "/media/innovation/magnetic-distractor.png",
];
const newsImages = ["/media/news/best-paper-meeting.jpg", "/media/news/top-cited.jpg", "/media/news/congress-recognition.jpg"];
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

  const nav = useMemo(() => t.nav.map((label) => ({ label })), [t.nav]);
  const currentSlug = page === "home" ? "" : page;
  const headerLogo = locale === "en" ? "/brand/logo.en.svg" : "/brand/logo.fa-ar.svg";

  return (
    <main className={`${rtl ? "rtl" : "ltr"} locale-${locale}`}>
      <a className="skip-link" href="#content">{t.skip}</a>
      <header className="site-header">
        <a className="brand" href={localizedHref(locale)} aria-label={t.homeLabel}>
          <Image src={headerLogo} alt="Dr. Ali Moradi" width={153} height={50} priority />
        </a>
        <nav className="desktop-nav" aria-label={t.primaryNav}>
          {nav.map((item) => <button className="nav-link" type="button" key={item.label}>{item.label}</button>)}
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
        {nav.map((item) => <button className="mobile-nav-link" type="button" key={item.label}>{item.label}<ChevronRight className={rtl ? "flip-icon" : ""} size={17} /></button>)}
        <a className="button" href="https://nobat.ir/9705" target="_blank" rel="noreferrer">{t.appointment}<ExternalLink size={16} /></a>
      </nav>}

      <div id="content">
        {page === "home" ? <HomePage locale={locale} t={t} rtl={rtl} /> : page === "contact" ? <ContactPage t={t} rtl={rtl} /> : <InteriorPage locale={locale} page={page} t={t} rtl={rtl} />}
      </div>
      <Footer locale={locale} t={t} />
    </main>
  );
}

function HomePage({ locale, t, rtl }: { locale: Locale; t: SiteCopy; rtl: boolean }) {
  const [openAppointment, setOpenAppointment] = useState(0);

  return <>
    <section className="hero">
      <Image className="hero-background" src="/media/hero/hero-bg.png" alt={t.heroAlt} fill priority unoptimized sizes="100vw" />
      <div className="hero-wash" aria-hidden="true" />
      <div className="hero-orbits" aria-hidden="true">
        <div className="orbit orbit-one" />
        <div className="orbit orbit-two" />
        <div className="hero-dot dot-one" />
        <div className="hero-dot dot-two" />
      </div>
      <div className="hero-layout section-shell">
        <div className="hero-copy">
          <h1>{t.heroTitle}</h1>
          <p className="eyebrow"><span />{t.eyebrow}</p>
          <p className="hero-body">{t.heroBody}</p>
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
            {index < 3 && <ChevronRight className="connected-arrow" size={76} strokeWidth={1} aria-hidden="true" />}
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
        <p className="card-tag">{tag}</p><h3>{title}</h3><p>{text}</p><a href={localizedHref(locale, "innovation")}>{t.readStory}<DirectionalArrow rtl={rtl} size={16} /></a>
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
      <Reveal className="section-heading split-heading"><div><p className="section-index">{t.indexes[6]}</p><p>{t.newsTitle}</p></div><a className="text-link" href={localizedHref(locale, "news")}>{t.allUpdates}<DirectionalArrow rtl={rtl} /></a></Reveal>
      <div className="news-grid">{t.news.map(([tag, title, text], index) => <Reveal className="news-card" key={title}><div className="news-image"><Image src={newsImages[index]} alt={title} fill unoptimized sizes="(max-width: 820px) 90vw, 31vw" /></div><p className="card-tag">{tag}</p><h3>{title}</h3><p>{text}</p><a href={localizedHref(locale, "news")}>{t.readUpdate}<DirectionalArrow rtl={rtl} size={16} /></a></Reveal>)}</div>
    </section>

    <section className="about-preview section-space"><div className="section-shell about-grid">
      <Reveal className="about-copy"><p className="section-index light">{t.indexes[7]}</p><p>{t.aboutBody}</p><div className="about-actions"><a className="button button-light" href={localizedHref(locale, "about")}>{t.meetDoctor}<DirectionalArrow rtl={rtl} /></a><a className="text-link light" href={localizedHref(locale, "research")}>{t.researchProfile}<FileText size={17} /></a></div></Reveal>
      <Reveal className="about-media"><div className="about-rings" aria-hidden="true" /><div className="pulse-line" aria-hidden="true" /><Image src="/media/about/office.jpg" alt={t.aboutImageAlt} fill unoptimized sizes="(max-width: 820px) 100vw, 42vw" /></Reveal>
    </div></section>
  </>;
}

function InteriorPage({ locale, page, t, rtl }: { locale: Locale; page: string; t: SiteCopy; rtl: boolean }) {
  const pages = t.pages as Record<string, InteriorPageData>;
  const data = pages[page] || pages["clinical-care"];
  return <>
    <section className="page-hero section-shell"><p className="eyebrow"><span />{data.kicker}</p><h1>{data.title}</h1><p>{data.intro}</p><div className="page-hero-orbit"><span /><span /></div></section>
    <section className="page-content section-space section-shell">
      <aside><p>{t.onThisPage}</p>{data.sections.map((section, i) => <a key={section.title} href={`#section-${i}`}>{section.title}</a>)}<a href="https://nobat.ir/9705" target="_blank" rel="noreferrer">{t.pageAppointment}<ExternalLink size={14} /></a></aside>
      <div className="content-sections">
        {data.sections.map((section, i) => <Reveal className="content-section" key={section.title}><span className="section-count">0{i + 1}</span><div><h2 id={`section-${i}`}>{section.title}</h2><p>{section.text}</p>{section.items && <ul>{section.items.map(item => <li key={item}><Check size={17} />{item}</li>)}</ul>}</div></Reveal>)}
        <Reveal className="next-step"><Sparkles /><div><p>{t.nextLabel}</p><h2>{data.ctaTitle}</h2><span>{data.ctaText}</span></div><a className="button" href={page === "clinical-care" ? "https://nobat.ir/9705" : localizedHref(locale, page === "news" ? "research" : "contact")} target={page === "clinical-care" ? "_blank" : undefined} rel={page === "clinical-care" ? "noreferrer" : undefined}>{page === "clinical-care" ? t.appointment : t.exploreMore}<DirectionalArrow rtl={rtl} size={16} /></a></Reveal>
      </div>
    </section>
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
    <section className="page-hero section-shell"><p className="eyebrow"><span />{t.contact.kicker}</p><h1>{t.contact.title}</h1><p>{t.contact.intro}</p><div className="page-hero-orbit"><span /><span /></div></section>
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
      <div><h3>{t.footer.resources}</h3><a href={localizedHref(locale, "clinical-care")}>{t.footer.before}</a><a href={localizedHref(locale, "clinical-care")}>{t.footer.after}</a><a href={localizedHref(locale, "clinical-care")}>{t.footer.faq}</a><a href={localizedHref(locale, "education")}>{t.footer.rehab}</a></div>
      <div className="footer-contact"><h3>{t.footer.contact}</h3><a href="mailto:info@DrAliMoradi.com"><Mail />info@DrAliMoradi.com</a><a href="tel:+985132290968" dir="ltr"><Phone />+98 51 3229 0968</a><p><MapPin />{t.contact.office}</p><p><MapPin />{t.contact.clinic}</p><a href={mapUrl} target="_blank" rel="noreferrer"><MapPin />{t.footer.map}<ExternalLink size={13} /></a></div>
      <div className="footer-social"><h3>{t.footer.social}</h3><a href="https://www.instagram.com/dr_ali_moradi_handsurgeon" target="_blank" rel="noreferrer"><Camera />Instagram</a><a href="https://t.me/DrAliMoradi" target="_blank" rel="noreferrer"><Send />Telegram</a><a href="https://www.aparat.com/dr_ali_moradi_handsurgeon" target="_blank" rel="noreferrer"><PlayCircle />Aparat</a></div>
    </div>
    <div className="section-shell footer-bottom"><span>{t.footer.copyright}</span><span>{t.footer.disclaimer}</span><a href="https://moghadam.pro" target="_blank" rel="noreferrer">{t.footer.credit}</a></div>
  </footer>;
}
