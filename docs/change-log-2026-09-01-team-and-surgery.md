# گزارش اصلاح تیم‌ها، تصاویر و راهنمای جراحی — 2026-09-01

## خروجی این نسخه

- نسخه پروژه از `0.1.0` به `0.2.1` ارتقا یافت.
- لینک‌های Instagram و Telegram فوتر به نشانی‌های جدید منتقل و آیکون‌های SVG رسمی جایگزین شدند؛ لینک Aparat حفظ شد و آیکون آن نیز تغییر کرد.
- ترکیب اعضای کلینیک، Innovation و Research بر اساس فهرست جدید بازتنظیم شد.
- اعضای غیرتکراری Bone and Joint Research Laboratory از صفحه رسمی آزمایشگاه استخراج شدند و برای هر عضو، کارت، صفحه اختصاصی سه‌زبانه و لینک منبع ساخته شد.
- محتوای دکتر مهلا دلیری از فایل Word تأییدشده پروژه و محتوای دکتر نفیسه جیرفتی از صفحه حرفه‌ای رسمی بازنویسی شد.
- تصاویر پرسنلی خانم‌ها با مدل موی جمع‌شده، بدون شال و روسری و با ظاهر حرفه‌ای یکدست تولید شد. مریم جعفری با لباس رسمی غیرپزشکی نمایش داده می‌شود.
- تصویر دکتر ایزدپناه/محمدرضا یزدان‌پناه و تصویر جدید دکتر مهلا دلیری در قالب پرتره سایت بازسازی شد.
- کاور Innovation با حضور دکتر مهلا دلیری و کاور Education با ترکیب آموزشی جدید جایگزین شد.
- تصویر اصلی بخش داستان صفحه About به پرتره مورد استفاده در پروفایل دکتر علی مرادی تغییر کرد.
- صفحه Before surgery از قالب سایدبار خارج و به اینفوگرافیک مرحله‌ای قبل و بعد از عمل تبدیل شد؛ متن فارسی و انگلیسی بازنویسی و ترجمه عربی افزوده شد.
- هشدار ایمنی صریح اضافه شد: دستور اختصاصی جراح، بیمارستان، متخصص بیهوشی و نسخه بیمار بر متن عمومی سایت اولویت دارد.
- اسکریپت `scripts/deploy-github-release.sh` برای دریافت مستقیم نسخه `main` از GitHub، build اتمیک، restart با PM2، health check و rollback خودکار اضافه شد.

## ترکیب تیم‌ها

### کلینیک

1. دکتر علی مرادی
2. مونا میبدی
3. دکتر محمدرضا یزدان‌پناه
4. مهسا جعفری
5. مریم جعفری
6. مجتبی اسدپور

### Innovation

فهرست اصلی شامل دکتر علی مرادی، دکتر علیرضا اکبرزاده، دکتر مهلا دلیری، دکتر نفیسه جیرفتی، دکتر محمدرضا اکبرزاده، دکتر افسانه جهانی، مائده شرف‌الدین و نعیمه کلالی است و اعضای غیرتکراری آزمایشگاه نیز به آن افزوده می‌شوند.

### Research

فهرست اصلی شامل دکتر علی مرادی، دکتر مهلا دلیری و دکتر نفیسه جیرفتی است و اعضای غیرتکراری آزمایشگاه نیز به آن افزوده می‌شوند.

## منابع محتوایی

- فهرست اعضای آزمایشگاه: <https://orthopresearch.com/index.php/orc-labs/bone-joint/people-bone-joint-lab>
- پروفایل دکتر نفیسه جیرفتی: <https://orthopresearch.com/index.php/component/sppagebuilder/?view=page&id=54>
- محتوای دکتر مهلا دلیری: `C:/Users/sayid/Documents/Dr.Mahla-Daliri.docx`

## وضعیت تصاویر و موارد نیازمند داده تکمیلی

- تصویر واقعی و قابل‌تأیید دکتر سروش در منابع عمومی پیدا نشد. در کاور Education یک همکار مرد از زاویه غیرقابل‌شناسایی قرار گرفته تا هویت اشتباه به فرد واقعی نسبت داده نشود. پس از دریافت عکس معتبر، این بخش باید جایگزین شود.
- برای مجتبی اسدپور و دکتر محمدرضا اکبرزاده تصویر مرجع تأییدشده تحویل نشده است؛ فعلاً placeholder برند استفاده می‌شود.
- تصاویر تولیدی، بازنمایی ویرایشی بر اساس تصاویر مرجع هستند و پیش از انتشار نهایی باید توسط هر عضو تأیید شوند.

## ثبت تولید تصاویر با ImageGen

حالت استفاده‌شده برای پرتره‌ها: **edit / transform from supplied or official reference images**.

پرامپت پایه دقیق پرتره‌ها:

> Create a square, photorealistic professional staff portrait for Dr. Ali Moradi's website, preserving the person's facial identity from the supplied reference. Match the established portrait system: bright modern medical corridor, soft natural daylight, clean pale blue-white background, chest-up composition, realistic skin texture, direct approachable expression, centered framing, no text, no logo. For women: no hijab, scarf, cap, or head covering; hair neatly pulled back into a low bun in the style of the supplied hairstyle reference, with face unchanged. Use medical scrubs or a white clinical coat only for clinical roles; use simple formal office clothing for non-clinical staff.

تنظیم اختصاصی مونا میبدی:

> Use teal medical scrubs without a cap; keep the supplied facial identity unchanged and make the result natural rather than synthetic.

تنظیم اختصاصی مریم جعفری:

> She is not a doctor. Use simple, modest formal office clothing with no white coat, scrubs, medical props, scarf, or head covering.

پرامپت دقیق کاور Innovation، حالت **edit / composite from supplied portraits**:

> Create a wide 2.25:1 photorealistic website cover for medical innovation. Place Dr. Ali Moradi, Dr. Mahla Daliri, and Dr. Alireza Akbarzadeh together in a bright modern orthopedic research laboratory, studying a bionic hand and an external-fixator prototype. Preserve each supplied face. Dr. Mahla Daliri must have no scarf or head covering; her dark hair is neatly pulled back. Keep the left 45 percent calm and uncluttered for page text, use natural blue-white daylight, credible medical detail, no text, no logos.

پرامپت دقیق کاور Education، حالت **edit / composite from supplied portraits**:

> Create a wide 2.25:1 photorealistic website cover for orthopedic education. Show Dr. Ali Moradi teaching with an anatomical hand model in a bright modern teaching laboratory. Include a male collaborator on the left seen mostly from behind so no unverified identity is invented, and a female team member on the right with hair neatly tied back and no head covering. Preserve Dr. Moradi's supplied face. Keep the left 42 percent visually quiet for page text; natural daylight, pale blue-white palette, no text, no logos.

## استقرار سریع از GitHub

پس از Public شدن موقت Repository، این دستور به‌عنوان `root` اجرا می‌شود:

```bash
curl -fsSL https://raw.githubusercontent.com/moghadam-pro/dr.ali.moradi-webiste/main/scripts/deploy-github-release.sh | sudo bash -s -- main
```

اسکریپت با کاربر `drmomin` و Node موجود در login shell اجرا می‌شود، build را در release موقت انجام می‌دهد، سپس برنامه `dr-alimoradi-demo` را با PM2 restart می‌کند و `http://127.0.0.1:3006/` را بررسی می‌کند. در صورت شکست build یا health check، نسخه قبلی restore می‌شود.

### اصلاح سازگاری CloudPanel

در اولین اجرای نسخه `0.2.0` مشخص شد CloudPanel در shell غیرتعاملی، Node.js سیستم (`v18`) را به‌جای Node.js کاربر سایت (`v22`) انتخاب می‌کند. نسخه `0.2.1` فرمان استقرار را در interactive shell کاربر `drmomin` اجرا می‌کند و پیش از `npm ci` نیز حداقل نسخه Node.js را کنترل می‌کند تا خطا پیش از نصب وابستگی‌ها گزارش شود.
