# گزارش تطبیق نسخه WordPress با مرجع React

تاریخ بررسی: ۱۴ شهریور ۱۴۰۵ / 2026-09-05  
نسخه WordPress: <https://tmp.saveon.me/>  
نسخه مرجع React: <https://dralimoradi.moghadam.pro/>  
کامیت قالب بررسی‌شده: `ac46a71`  
کامیت مستندات پیش از این گزارش: `bfc37bd`

## هدف و دامنه بررسی

این ممیزی برای اطمینان از انتقال کامل نسخه React به قالب WordPress انجام شد. بررسی شامل معماری قالب، موجودی محتوای سه زبان، مسیرها، نمایش دسکتاپ و موبایل، RTL، فرم‌ها، تعامل‌ها، متادیتا، Sitemap و نمونه‌هایی از همه خانواده‌های اصلی قالب بود.

نسخه WordPress از این مرحله به بعد منبع اصلی توسعه پروژه است. نسخه React فقط به‌عنوان مرجع تصویری و محتوایی برای کنترل برابری نگهداری می‌شود.

## جمع‌بندی مدیریتی

زیرساخت قالب WordPress، طراحی صفحه نخست، صفحات اصلی کلینیک/پژوهش/درباره، صفحات وبلاگ و بخش‌های تعاملی با کیفیت خوبی منتقل شده‌اند. قالب از نظر نحوی سالم است، مسیرهای محتوایی منتشرشده پاسخ `200` می‌دهند، چیدمان نمونه‌های موبایل اسکرول افقی ندارد و جهت RTL و فونت‌ها درست اعمال شده‌اند.

بااین‌حال انتقال هنوز «کامل» نیست. مهم‌ترین فاصله‌ها عبارت‌اند از کمبود محتوای Innovation، انتقال‌نیافتن ۲۰ عضو تیم، نبود صفحات Education و News، ناهماهنگی محتوای بخش Latest، ترجمه ناقص فرم تماس و چند خطای جدی SEO/Schema/Sitemap. بنابراین نسخه WordPress برای ادامه توسعه مناسب است، اما پیش از انتقال نهایی به دامنه اصلی باید موارد اولویت P0 و P1 این گزارش بسته شوند.

## وضعیت معماری WordPress

- قالب به‌صورت Native Full Site Editing / Block Theme ساخته شده است.
- Elementor و ACF در معماری فعلی وابستگی ضروری نیستند.
- Polylang Free برای سه زبان و Rank Math Free برای SEO در نظر گرفته شده‌اند.
- فرم تماس با MPro Forms کار می‌کند.
- صفحه نخست از Dynamic Blockهای قالب رندر می‌شود؛ صفحات داخلی، نوشته‌ها و CPTها محتوای واقعی WordPress هستند.
- Post Typeهای اختصاصی شامل `team_member`، `condition`، `innovation`، `publication` و `patient_resource` هستند.
- تنظیمات عمومی تماس، شبکه‌های اجتماعی، آدرس، لینک نوبت و آمار Impact از Theme Options خوانده می‌شوند.
- فایل‌های اصلی قالب در `wordpress-theme/dr-ali-moradi/` قرار دارند.

## پوشش آزمون

- تعداد URLهای مرجع React در Sitemap: ۲۲۸ مسیر؛ ۷۶ مسیر برای هر زبان.
- تعداد محتوای منتشرشده WordPress که از REST API استخراج شد: ۱۶۵ مسیر؛ ۵۵ مسیر برای هر زبان.
- هر ۱۶۵ URL منتشرشده WordPress با پاسخ `200` بررسی شد.
- هر ۲۲۸ URL Sitemap مرجع React نیز پاسخ `200` داشت.
- در Crawl محتوایی WordPress خطای PHP یا شکست RTL مشاهده نشد.
- صفحه نخست، Clinical Care، Research، Innovation، About، Blog، Contact، صفحات سرویس، گالری، نوشته و پروفایل تیم در دسکتاپ بررسی شدند.
- نسخه موبایل انگلیسی، فارسی و عربی صفحه نخست و نمونه صفحات Clinical Care، Blog و Contact بررسی شد.
- منوی موبایل، آکاردئون نوبت و لایت‌باکس گالری به‌صورت واقعی در مرورگر تست شدند.
- همه فایل‌های PHP قالب با `php -l` و فایل `theme.json` با JSON parser اعتبارسنجی شدند؛ خطای نحوی وجود ندارد.

## موارد منتقل‌شده و سالم

### طراحی و ریسپانسیو

- ارتفاع و ترکیب کلی سکشن‌های صفحه نخست در دسکتاپ بسیار نزدیک به مرجع است.
- Hero، Connected Practice، Pathways، Innovation، Impact، Appointments، About و Footer از نظر ساختار اصلی منتقل شده‌اند.
- در نمونه‌های موبایل عرض ۳۹۰ پیکسل اسکرول افقی مشاهده نشد.
- جهت فارسی و عربی RTL است.
- فونت انگلیسی `Inter Variable`، فارسی `Vazirmatn Variable` و عربی `Scheherazade New` در WordPress درست اعمال شده‌اند.
- منوی موبایل باز و بسته می‌شود.
- آکاردئون نوبت حالت فعال را بین چهار گزینه جابه‌جا می‌کند.
- گالری باز می‌شود، تصویر بعدی را نمایش می‌دهد و بسته می‌شود.

### محتوا و مسیرهای موجود

- سه زبان برای Home، About، Clinical Care، Innovation، Research، Contact و Blog وجود دارد.
- دو صفحه خدمات Clinic/Hospital، دو گالری و چهار Patient Resource در هر سه زبان ایجاد شده‌اند.
- ۲۳ نوشته وبلاگ برای هر زبان وجود دارد.
- ۸ عضو تیم برای هر زبان و ۳ Innovation برای هر زبان وارد شده‌اند.
- صفحات نمونه نوشته و پروفایل تیم از نظر ساختار اصلی با React هم‌راستا هستند.

## فاصله‌های بحرانی — P0

### ۱. محتوای Innovation کامل منتقل نشده است

نسخه مرجع ۱۴ مسیر/موضوع نوآوری را در صفحه Innovation نمایش می‌دهد و برای ۷ مورد صفحه جزئیات اختصاصی دارد. نسخه WordPress فقط ۴ بخش عمومی در Hub و ۳ رکورد عمومی CPT دارد.

موضوع‌های مرجع که باید با دیتای تاییدشده منتقل شوند:

- Dynamometer
- Magnetic joint distraction
- Dynamic distal-radius external fixator
- Dynamic hip external fixator
- Intra-osseous DRUJ prosthesis
- Lag plate
- Artificial finger pulley
- Bionic Hand H3
- Bionic-hand training software
- Bionic Hand H5
- Magnetic control for an artificial limb
- Integrated stem
- Hip Exoskeleton HEXA
- Coated Schanz pins

همچنین مسیر `/innovation/` در WordPress اکنون Archive مربوط به CPT است، درحالی‌که Hub اصلی در `/innovations/` قرار دارد. در نسخه React، `/innovation` مسیر Hub اصلی است. این برخورد مسیر باید پیش از Cutover حل شود.

### ۲. بیست عضو تیم منتقل نشده‌اند

نسخه React در مجموع ۲۸ پروفایل تیم دارد، ولی WordPress فقط ۸ پروفایل را وارد کرده است. نتیجه این کمبود در شبکه تیم صفحات Clinical Care، Research و Innovation دیده می‌شود.

- Clinical Care در WordPress چهار نفر و در مرجع شش نفر نمایش می‌دهد.
- Research در WordPress چهار نفر و در مرجع ۲۲ نفر نمایش می‌دهد.
- Innovation در WordPress سه نفر و در مرجع ۲۴ نفر نمایش می‌دهد.

عضوها باید با نقش، تصویر، گروه‌های مرتبط و ترجمه‌های سه‌زبانه از داده‌های فعلی React وارد شوند.

### ۳. دو مسیر اصلی وجود ندارند

- صفحه `Education` و نسخه‌های فارسی/عربی آن ایجاد نشده است.
- صفحه `News` و نسخه‌های فارسی/عربی آن ایجاد نشده است.
- منوی WordPress به‌جای Education یک گزینه اضافی Home دارد؛ Footer نیز لینک Education را ندارد.

### ۴. متادیتای صفحه نخست محتوای داخلی سیستم را منتشر می‌کند

Title/Description و Open Graph صفحه نخست WordPress از محتوای Placeholder Page گرفته شده‌اند و توضیح داخلی مربوط به نحوه کار `front-page.html` را به موتور جستجو و شبکه‌های اجتماعی نشان می‌دهند. صفحه فارسی نیز همین مشکل را به زبان فارسی دارد.

علاوه بر آن:

- `og:image` و `twitter:image` صفحه نخست خالی هستند.
- لینک‌های Favicon در خروجی WordPress وجود ندارند.
- Canonical صفحه نخست فارسی به مسیر نامناسب `front-page-placeholder-fa` اشاره می‌کند.

### ۵. Canonical و Open Graph آرشیو وبلاگ فارسی/عربی خراب است

- Canonical و `og:url` صفحه `/fa/blog-fa/` به یک URL مرکب و `404` اشاره می‌کند.
- Canonical و `og:url` صفحه `/ar/blog-ar/` نیز به یک URL مرکب و `404` اشاره می‌کند.

این مورد احتمالاً از فیلتر سفارشی تشخیص Front Page یا اتصال Posts Page در Polylang/Rank Math ناشی شده است.

### ۶. Sitemap فعلی کامل و تازه نیست

- `page-sitemap.xml` فقط ۱۶ صفحه را نشان می‌دهد و ۲۴ صفحه جدیدتر را جا انداخته است.
- `hello-world` در Sitemap دیده می‌شود، اما URL مستقیم آن `404` است.
- مسیرهای تازه سرویس‌ها، گالری‌ها و منابع بیمار در Sitemap صفحه منعکس نشده‌اند.

بعد از اصلاح محتوا و تنظیمات Rank Math باید Index و Sitemap Cache بازسازی شود.

### ۷. Schema صفحه نخست نادرست است

- ساعات کاری `09:00–17:00` برای تمام روزهای هفته در Physician Schema ثبت شده، درحالی‌که این داده تایید نشده و با اطلاعات فعلی پروژه سازگار نیست.
- صفحه نخست به‌اشتباه `Article` نیز معرفی شده است.
- نویسنده Schema با نام کاربری داخلی `dralimin` و Gravatar منتشر می‌شود.
- توضیح Placeholder وارد Article/Physician metadata شده است.

### ۸. سایت Sandbox در حال حاضر قابل ایندکس است

`robots.txt` و Meta Robots اجازه `index, follow` می‌دهند. چون `tmp.saveon.me` یک محیط موقت و نسخه تکراری محتوای مرجع است، تا زمان Cutover باید `noindex, nofollow` شود یا دسترسی آن کنترل شود.

## موارد مهم — P1

### بخش Latest صفحه نخست

نسخه WordPress آخرین نوشته‌ها را نشان می‌دهد، اما مرجع چهار کارت دستاورد/Recognition مشخص دارد. سه تصویر در WordPress تکراری یا نامرتبط‌اند. محتوا و تصاویر صحیح مرجع عبارت‌اند از:

- Research impact in external-fixator pin coatings
- Orthopedic congress presentation recognition
- Best Paper Award for bionic-hand sensor research
- Featured poster in bionic-hand motor control

### ترجمه فرم تماس

فرم WordPress از نظر ارسال و Required Validation بهتر از فرم مرجع است، اما رابط آن کامل ترجمه نشده است.

- در فارسی، بیشتر Labelها، Optionها، Placeholder و Submit انگلیسی مانده‌اند.
- در عربی، تقریباً تمام Labelها انگلیسی‌اند و حتی مقادیر پیش‌فرض Select/Consent به‌اشتباه فارسی هستند.
- فرم باید برای هر زبان رشته‌های مستقل و تست Validation/Success/Error داشته باشد.

### صفحات جزئیات CPT

صفحات Single برای `innovation` و `condition` در حال حاضر قالب عمومی WordPress دارند؛ Cover، ساختار محتوایی، CTA نوبت و Footer استاندارد صفحات داخلی را ندارند. این صفحات در صورت Index شدن تجربه ناهماهنگی ارائه می‌کنند.

### ساختار URL و Redirectها

URLهای WordPress با مرجع یکسان نیستند:

- نوشته‌های WordPress در ریشه‌اند، اما در React زیر `/blog/` بودند.
- Service، Gallery و Patient Resource در WordPress در ریشه‌اند، اما در React Nested بودند.
- Hub نوآوری در WordPress `/innovations/` است و در React `/innovation` بود.

قبل از انتقال دامنه باید Redirect Map دائمی `301` تهیه و آزمایش شود تا لینک‌های موجود و SEO از بین نروند.

### Alt Text تصاویر

تصاویر اصلی Hero، پزشک در بخش Appointment و تصویر Office در صفحه نخست Alt معنی‌دار ندارند. چند تصویر تزئینی نیز باید به‌درستی Decorational باقی بمانند، اما تصاویر محتوایی باید Alt سه‌زبانه داشته باشند. تصویر لایت‌باکس نیز هنگام بازشدن Alt خالی دارد.

## موارد تکمیلی — P2

- ترتیب نوشته‌های Blog در WordPress براساس تاریخ انتشار است و با ترتیب Editorial ثابت نسخه React تفاوت دارد.
- خط طولانی مربوط به Alborz Award در Hero موبایل فشرده و کم‌خوانا است؛ این مشکل در مرجع React نیز وجود دارد و باید در WordPress اصلاح شود.
- عنوان H1 صفحات سرویس Clinic/Hospital و بخش‌بندی محتوایی WordPress نسبت به React خلاصه‌تر و متفاوت است؛ اگر مرجع React نسخه تاییدشده نهایی است، متن‌های جزئی‌تر باید منتقل شوند.
- اطلاعات شبکه‌های اجتماعی WordPress با مرجع یکسان نیست: Instagram و Telegram به مسیرهای متفاوت اشاره می‌کنند و باید مقدار تاییدشده کارفرما انتخاب شود.

## تفاوت موجودی مسیرها

موجودی مرجع برای هر زبان:

- ۱ صفحه نخست
- ۸ مسیر اصلی: Clinical Care، Innovation، Research، Education، About، Blog، Contact و News
- ۲ صفحه خدمات
- ۴ Patient Resource
- ۷ صفحه جزئیات Innovation
- ۲ گالری
- ۲۸ پروفایل تیم
- ۲۳ نوشته وبلاگ

موجودی WordPress برای هر زبان:

- ۱۵ Page شامل Home، About، Clinical Care، Innovation، Research، Contact، Blog، دو Service، دو Gallery و چهار Patient Resource
- ۲۳ Post
- ۸ Team Member
- ۶ Condition
- ۳ Innovation

## ترتیب پیشنهادی اصلاحات

1. محیط `tmp.saveon.me` را Noindex کنید و خطاهای Placeholder/Canonical/Schema را ببندید.
2. Sitemap و Rank Math index را بازسازی کنید و URL collision مربوط به Innovation را حل کنید.
3. Education و News را در سه زبان بسازید و منو/Footer را اصلاح کنید.
4. ۲۰ عضو تیم باقی‌مانده را با ترجمه و گروه‌بندی وارد کنید.
5. چهارده موضوع Innovation و صفحات جزئیات تاییدشده را منتقل کنید.
6. Latest صفحه نخست را با Recognitionهای مرجع و تصاویر صحیح جایگزین کنید.
7. ترجمه کامل فرم تماس را انجام دهید.
8. Single Templateهای Condition و Innovation، Alt Textها و Redirect Map را تکمیل کنید.
9. پس از اصلاحات، Crawl سه‌زبانه، QA موبایل و تست Rank Math را دوباره اجرا کنید.

## نتیجه نهایی

نسخه WordPress از نظر زیرساخت و پایه بصری آماده ادامه توسعه است و از این پس باید تنها نسخه قابل ویرایش پروژه باشد. اما هنوز نمی‌توان انتقال را «کامل و بدون قلم‌افتادگی» دانست. مهم‌ترین کسری‌ها مربوط به Innovation، تیم، Education/News و SEO است. بسته‌شدن موارد P0 شرط انتقال امن به دامنه اصلی است.
