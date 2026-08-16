# Change Log — 2026-08-17

این سند درخواست، تصمیم‌ها و تغییرات مرحله فعلی پروژه را برای ادامه کار روی هر
سیستم دیگری ثبت می‌کند. وضعیت این سند **جاری** است و در تعارض با تصمیم‌های قدیمی،
اصلاحیه‌های این سند اولویت دارند.

## روش شروع کار روی یک سیستم دیگر

1. آخرین شاخه `main` را از Git دریافت کنید.
2. ابتدا `docs/README.md` و سپس جدیدترین فایل `docs/change-log-*.md` را بخوانید.
3. وضعیت Build و تست‌ها را با `npm test` بررسی کنید.
4. تغییرات بعدی را همراه با سند تصمیم و نتیجه آزمون در Git ثبت کنید.

اطلاعات خصوصی میزبان، نام کاربری سرور، مسیرهای داخلی سرور، گواهی‌ها و اسکریپت
وابسته به یک حساب میزبانی نباید وارد Repository شوند.

## درخواست ثبت‌شده در این مرحله

- حذف گزینه Product از هدر؛
- فعال‌شدن لینک تمام گزینه‌های باقی‌مانده منو؛
- حفظ React و معماری فعلی؛
- ساخت الگوی مشترک صفحات داخلی با کاور فول‌عرض، حداکثر ارتفاع ۴۰۰ پیکسل، متن
  سفید، گرادیان آبی از شفافیت ۴۰ درصد به صفر و کاهش ارتفاع با اسکرول؛
- جایگزینی صرفاً عکس پس‌زمینه Hero با فایل جدید؛
- رنگ مشکی برای `Dr.` و رنگ Primary Blue برای `Ali Moradi`؛
- جایگزینی عنوان علمی Hero با متن دقیق
  `Hand Surgeon (Harvard University), Ph.D of Artificial Limbs`؛
- جایگزینی خط سوم Hero با متن دقیق
  `Recipient of the 2026 Alborz Award (Iran’s Nobel Prize)`؛
- ساخت کامل صفحه Clinic شامل خدمات، مطب خصوصی و سه نمای تصویری، نوع و دامنه
  خدمات، FAQ، نوبت و فوتر؛
- ساخت صفحه About براساس داده‌های موجود؛
- اتصال صفحه Contact موجود؛
- ساخت آرشیو Blog در شش ردیف سه‌ستونه، در مجموع ۱۸ مقاله؛
- ساخت صفحه Single Post استاندارد برای هر مقاله؛
- ساخت نسخه اولیه صفحات Innovation، Research و Education برای اصلاحیه بعدی؛
- حفظ ترجمه کامل انگلیسی، فارسی و عربی؛
- ثبت کامل تغییرات و تصمیم‌ها در Git برای همگام‌سازی چند سیستم.

## وضعیت پیاده‌سازی

### منو و مسیرها

منوی جاری:

1. Clinic → `/clinical-care`
2. Innovation → `/innovation`
3. Research → `/research`
4. Education → `/education`
5. About me → `/about`
6. Blog → `/blog`

زبان انگلیسی در Root است. فارسی و عربی به‌ترتیب زیر `/fa/` و `/ar/` قرار دارند.
مسیر تو‌در‌توی مقاله‌ها نیز در هر سه زبان حفظ می‌شود؛ برای مثال:

- `/blog/understanding-carpal-tunnel-syndrome`
- `/fa/blog/understanding-carpal-tunnel-syndrome`
- `/ar/blog/understanding-carpal-tunnel-syndrome`

مسیر قدیمی `/news` برای سازگاری به آرشیو Blog هدایت محتوایی می‌شود.

### Hero صفحه نخست

- Asset جدید: `public/media/hero/hero-bg-v2.jpg`
- عکس بدون بازسازی یا تغییر محتوایی وارد شده است.
- رفتار Mirror برای فارسی و عربی، گرادیان و دایره‌های تزئینی قبلی حفظ شده است.
- `Dr.` مشکی و `Ali Moradi` آبی Primary است.
- عنوان علمی و Award Line دقیقاً مطابق متن درخواست‌شده جایگزین شده‌اند.

ادعای Harvard University، عنوان Ph.D و نام‌گذاری «Iran’s Nobel Prize» در نسخه
دمو طبق دستور مستقیم کارفرما درج شده‌اند، اما پیش از انتشار Production همچنان به
تأیید نهایی علمی و حقوقی دکتر نیاز دارند.

### الگوی صفحات داخلی

کامپوننت مشترک کاور داخلی دارای این ویژگی‌ها است:

- فول‌عرض؛
- ارتفاع حداکثر ۴۰۰ و حداقل ۲۸۰ پیکسل در دسکتاپ؛
- گرادیان Primary Blue از ۴۰ درصد به صفر؛
- عنوان و توضیح سفید روی تصویر؛
- کاهش تدریجی ارتفاع در شروع اسکرول؛
- اندازه‌های مستقل و خوانا برای Tablet و Mobile؛
- سکشن پیشنهاد نوبت از مسیر رسمی `https://nobat.ir/9705` قبل از فوتر.

### صفحه Clinic

صفحه کلینیک در هر سه زبان شامل موارد زیر است:

- Clinic Services با سه مسیر مشاوره تخصصی، اقدامات مطب و پیگیری/توان‌بخشی؛
- معرفی مطب خصوصی و تفاوت آن با جراحی‌های نیازمند بیمارستان؛
- گالری سه‌نمایی با Cropهای مستقل؛
- توضیح Type of Services؛
- ده مورد در Scope of Clinical Services؛
- شش سؤال متداول درباره رزرو، مدارک، اورژانس، محل انجام جراحی، توان‌بخشی و فرم
  تماس؛
- CTA نوبت و Disclaimer اورژانس.

### کاور تولیدی Clinic

Asset نهایی: `public/media/pages/clinic-cover.jpg`

مرجع بصری: `public/media/about/office.jpg`

هدف تولید: گسترش فضای واقعی مطب به یک نمای معماری بزرگ‌تر، خلوت و حرفه‌ای با
چوب طبیعی، جزئیات آبی تیره، کف روشن، ویترین‌های علمی بدون متن خوانا و بدون لوگو
یا ادعای ساختگی.

Prompt ثبت‌شده:

> Create a photorealistic, high-end architectural photograph for a hand-surgery
> clinic website page cover, using the referenced real private-office image only
> as the architectural and material reference. Expand the scene into a larger,
> more detailed and spacious reception/corridor environment while preserving its
> recognizable design language: warm natural wood wall panels and curved joinery,
> deep navy-blue base accents and floor inlay, light gray terrazzo flooring,
> discreet recessed ceiling lights, glass display niches and softly blurred framed
> professional certificates. Clean, uncluttered, calm, hygienic, contemporary,
> premium but credible medical environment in Mashhad. No people. No readable
> text, no invented logo, no watermark, no signage. Wide cinematic horizontal
> composition suitable for a full-width website cover, with visual detail across
> the frame and safe darker/clean negative space along the lower-left area for
> white title text. Natural neutral daylight, controlled highlights, consistent
> cool-blue and warm-wood brand color harmony, studio-quality architectural
> retouching, realistic materials and straight vertical lines.

### About، Research، Innovation و Education

- محتوای Source-backed قبلی حفظ و در الگوی جدید صفحات داخلی نمایش داده شد.
- صفحه About شامل هویت بالینی و دانشگاهی، آموزش، مسیر حرفه‌ای، پژوهش، رهبری و
  افتخارات است.
- صفحات Research، Innovation و Education ساختار کامل، کاور، محتوای اولیه و CTA
  نوبت دارند و برای بازبینی محتوایی مرحله بعد آماده‌اند.

### Contact

- Route واقعی `/contact` و نسخه‌های `/fa/contact` و `/ar/contact` متصل است.
- Validation نام، ایمیل، حداقل طول پیام و Consent حفظ شده است.
- فرم اطلاعات حساس پزشکی را نمی‌پذیرد و پس از اعتبارسنجی Email Client را با متن
  آماده باز می‌کند.
- کاور مشترک و سکشن پیشنهاد نوبت افزوده شده‌اند.

### Blog

- آرشیو شامل دقیقاً ۱۸ کارت در Grid سه‌ستونه دسکتاپ است؛
- Tablet دو ستون و Mobile یک ستون دارد؛
- عنوان و خلاصه با Line Clamp کنترل می‌شوند؛
- موضوع‌ها شامل بیماری‌های شایع دست، آمادگی و مراقبت پس از عمل، عصب و تاندون،
  میکروسرجری، فیکساتور، دست بیونیک، حسگر مغناطیسی، رباتیک توان‌بخشی، خواندن
  پژوهش و علائم هشدار هستند؛
- هر مطلب Route مستقل، کاور، Category، Date، Reading Time، سه بخش محتوایی،
  Disclaimer و CTA نوبت دارد؛
- Title، Description، Canonical، Hreflang، Open Graph و Twitter Metadata برای هر
  Route داخلی و مقاله ساخته می‌شود؛
- محتوای فعلی Blog آموزشی و اولیه است و برای Production نیازمند بازبینی پزشکی
  نهایی است.

## فایل‌های اصلی این تغییر

- `app/site-page.tsx` — Navigation، Hero، صفحات داخلی، Clinic، Blog و Contact
- `app/site-content.ts` — Navigation و متن‌های سه‌زبانه
- `app/page-extras.ts` — محتوای سه‌زبانه Clinic و نگاشت تصاویر کاور
- `app/blog-content.ts` — داده ۱۸ مقاله در سه زبان
- `app/[...slug]/page.tsx` — Routeهای تو‌در‌تو و Metadata پویا
- `app/globals.css` — الگوی کاور، Clinic، Blog، Single Post و Responsive
- `tests/rendered-html.test.mjs` — آزمون Route، محتوا، Metadata و Responsive
- `public/media/hero/hero-bg-v2.jpg` — پس‌زمینه جدید Hero
- `public/media/pages/clinic-cover.jpg` — کاور تولیدشده و بهینه‌شده Clinic

## نتیجه کنترل کیفیت

- `npx tsc --noEmit` — موفق
- `npm run lint` — موفق، بدون Warning یا Error
- `npm run build` — موفق
- `npm test` — پنج تست از پنج تست موفق
- Routeهای اصلی EN/FA/AR — موفق
- Route نمونه Single Post در EN/FA/AR — موفق
- شمارش ۱۸ کارت Blog — موفق
- Clinic Sections و FAQ — موفق
- Metadata، Favicon و Social Preview قبلی — حفظ و موفق
- Responsive و Reduced Motion Safeguards — موفق

## موارد باز برای اصلاحیه بعدی

1. تأیید نهایی متن Hero توسط دکتر، به‌ویژه Harvard، Ph.D و عنوان جایزه؛
2. بازبینی پزشکی ۱۸ مطلب آموزشی Blog؛
3. تعیین تصویر اختصاصی و تأییدشده برای هر صفحه و هر مقاله؛
4. تشریح و اصلاح محتوای Innovation، Research و Education؛
5. تصمیم درباره جزئیات Online Appointment و GP Screening؛
6. تعریف URL و محتوای نهایی Patient Resources؛
7. آماده‌سازی نگاشت نهایی React Components به WordPress/Elementor پس از تأیید UI.
