# تغییرات ساختار، کلینیک و محتوای یکپارچه — 2026-08-22

این سند وضعیت قابل ادامه پروژه پس از اصلاحات جدید را ثبت می‌کند.

## تصمیم‌های ساختاری جاری

- `Blog`، `News` و محتوای خبری `Innovation` یک مدل پست مشترک دارند و با Tag و
  Category تفکیک می‌شوند.
- همه این مطالب از یک Renderer عمومی Single Post استفاده می‌کنند.
- قالب پیش‌فرض Routeهای محتوایی `interior` است. Registry قالب‌ها شامل
  `interior`، `single-post`، `about`، `blank` و `form` است؛ طراحی اختصاصی چهار
  قالب آخر در اصلاحات بعدی تکمیل می‌شود.
- قالب داخلی از Cover، محتوای اصلی با Sidebar اختیاری، Appointment Banner و
  Footer تشکیل می‌شود.

## تغییرات رابط

- فاصله عمودی عمومی سکشن‌ها روی دسکتاپ و تبلت ۸۰ پیکسل شد.
- Section Index به ۱۸ پیکسل و خط آن به ۴×۳۲ پیکسل تغییر کرد.
- Journey روی دسکتاپ Gap صد پیکسلی، Arrowهای ۴۸×۴۸ و بدون Drop Shadow دارد؛
  چیدمان تبلت و موبایل مستقل باقی مانده است.
- گرادیان Cover صفحات داخلی به `linear-gradient(#4293c275 0%, #4293c2e3 100%)`
  تغییر کرد.
- شمارنده‌های Innovation و Research به عدد پس‌زمینه ۱۲۸ پیکسلی و خاکستری روشن
  تبدیل شدند و در ارتفاع رکورد دخالت ندارند.

## Clinic و Patient Resources

- دو زیرصفحه Clinic Services و Hospital Services با لینک بازگشت به Hub ساخته شد.
- اعضای تیم به همراه عکس، نقش، معرفی کوتاه و صفحه اختصاصی اضافه شدند.
- دو مجموعه Clinic و Hospital، هرکدام با ۱۶ تصویر، Strip قابل جابه‌جایی و صفحه
  گالری کامل ساخته شدند.
- Modal گالری با Mouse، دکمه‌های قبلی/بعدی/بستن و Keyboard شامل ArrowLeft،
  ArrowRight و Escape کنترل می‌شود.
- Footer به صفحات Before Surgery، After Surgery، FAQ و Rehabilitation Guidance
  متصل شد.
- تصاویر عمومی تیم از سایت قبلی و تصاویر محیطی غیر بیمار از آرشیو محلی پروژه
  انتخاب و برای وب بهینه شدند؛ هیچ تصویر بیمار در این گالری‌ها استفاده نشده است.

## فایل‌های اصلی

- `app/structured-content.ts`: Registry قالب‌ها، تیم، زیرصفحات و گالری‌ها
- `app/blog-content.ts`: مدل محتوای یکپارچه و Tagها
- `app/site-page.tsx`: Route renderer، Clinic Hub، Team و Gallery Modal
- `app/globals.css`: طراحی و Responsive
- `app/[...slug]/page.tsx`: Metadata مسیرهای پویا
- `app/sitemap.ts`: مسیرهای جدید
- `tests/rendered-html.test.mjs`: پوشش رندر، Route و CSS قراردادهای جدید

## ادامه کار

برای ادامه روی سیستم دیگر، ابتدا `main` را Pull کنید، این سند را بخوانید و سپس
`npm test` و `npm run build` را اجرا کنید. انتشار یا Push این مرحله باید جداگانه
و با تصمیم کارفرما انجام شود.
