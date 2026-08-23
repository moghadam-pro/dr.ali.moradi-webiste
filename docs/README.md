# Project documentation

This directory is the durable project knowledge base for the Dr. Ali Moradi
website. Start with the Persian conversation archive for the complete project
history, then use the focused documents for implementation details.

## Primary handoff documents

- [آرشیو ساختاریافته گفت‌وگو و تصمیم‌های پروژه](project-conversation-archive-fa.md)
- [گزارش اصلاح محتوا، تیم، نوآوری و راهنمای جراحی — 2026-08-23](change-log-2026-08-23.md)
- [راهنمای بروزرسانی سرور از Repository خصوصی](private-repository-server-update-runbook-fa.md)
- [ساختار قالب‌ها، کلینیک، گالری و محتوای یکپارچه — 2026-08-22](change-log-2026-08-22-structure-and-clinic.md)
- [اصلاح Hero Copy و راهنمای نگهداری — 2026-08-22](change-log-2026-08-22.md)
- [آخرین اصلاحیه و وضعیت اجرایی — 2026-08-17](change-log-2026-08-17.md)
- [Project discovery](project-discovery.md)
- [MVP requirements](mvp-requirements.md)
- [Content strategy and sitemap](content-strategy-and-sitemap.md)
- [Technical direction](technical-direction.md)
- [Demo V1 implementation](demo-v1-implementation.md)
- [Delivery roadmap](roadmap.md)

## Content and source audits

- [Source content inventory](source-content-inventory.md)
- [Approved website content audit](approved-website-content-audit.md)
- [CV content summary](cv-content-summary.md)
- [Innovation source links](innovation-links.md)
- [Media library audit](media-library-audit.md)
- [Certificates and clinical assets audit](certificates-and-clinical-assets-audit.md)

## Brand and visual system

- [Brand system](brand-system.md)
- [Visual and image constraints](visual-image-constraints.md)

## Reports

- [گزارش روند شناخت و طراحی](progress-report-fa.md)
- [Client-ready Word report](dr.ali-moradi-report-20250802.docx)

## Documentation policy

- Decisions marked as **current** supersede earlier iterations.
- Supplied Mobina concepts are content references only, never visual templates.
- Medical, academic, numerical, and appointment claims require final client
  approval before production launch.
- Because this repository is private, non-secret operational identifiers needed
  for cross-system continuity may be documented: server address, runtime user,
  application paths, internal port, process name, and deployment profile.
- Passwords, access tokens, private keys, certificate private material, cookies,
  `.env` contents, and other authentication secrets must never be committed.
- On a new system, pull `main`, read this index and the newest dated change log,
  then run `npm test` before starting a new revision.
