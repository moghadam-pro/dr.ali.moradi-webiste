# تحویل ادامه کار قالب WordPress دکتر علی مرادی

تاریخ: ۲۰۲۶-۰۹-۲۲  
وضعیت: آماده ادامه روی سیستم دسکتاپ؛ هنوز روی Production منتشر نشده است.

## نقطه شروع Git

- ریپوی اصلی: `https://github.com/moghadam-pro/dr.ali.moradi-webiste`
- ریپوی موقت خصوصی این مرحله:
  `https://github.com/moghadam-pro/dr-ali-moradi-theme-work-20260922`
- Branch ادامه کار: `codex/content-types-to-posts`
- آخرین Commit هنگام نوشتن این فایل: Commit مربوط به همین Handoff
- Commit کد مهاجرت: `7c484fa`
- Commit مستندات نسخه و مهاجرت: `be69afb`
- نقطه شروع Branch از ریپوی اصلی: `28b4c1a`

برای ادامه، ریپوی موقت را Clone یا Fetch کنید و دقیقاً Branch زیر را Checkout
کنید:

```bash
git fetch origin
git switch codex/content-types-to-posts
git pull --ff-only origin codex/content-types-to-posts
```

اگر Clone از ریپوی اصلی انجام شده است، remote ریپوی موقت را جداگانه اضافه کنید؛
هیچ تغییر این Branch هنوز به `upstream/main` پوش نشده است.

## درخواست اصلی مالک پروژه

1. قالب اختصاصی WordPress استاندارد و نسخه‌بندی شود.
2. هر مرحله در Changelog و مستندات ثبت و روی Git Push شود.
3. منوهای مدیریت زیر حذف شوند:
   - بیماری‌ها
   - نوآوری‌ها
   - انتشارات
   - منابع بیماران
4. محتوای موجود آن‌ها بدون حذف اطلاعات به نوشته‌های عادی WordPress منتقل شود.
5. دسته‌بندی‌های مناسب و سه‌زبانه اضافه شود.
6. سایت اصلی برای نصب و تست `https://dralimoradi.com` است.
7. دسترسی wp-admin از طریق Chrome کاربر موجود است.

## کارهای انجام‌شده

### ۱. ساخت محیط امن Git

- پوشه کاری ابتدا یک Git خالی و بدون remote بود.
- ریپوی اصلی شناسایی و Fetch شد.
- ریپوی موقت خصوصی
  `moghadam-pro/dr-ali-moradi-theme-work-20260922` ساخته شد.
- `main` و سپس Branch `codex/content-types-to-posts` در ریپوی موقت Push شدند.
- remoteها در وضعیت فعلی:
  - `origin`: ریپوی موقت خصوصی
  - `upstream`: ریپوی اصلی

### ۲. مطالعه ساختار و مستندات

مستندات اصلی پروژه، معماری WordPress، برنامه مهاجرت محتوا، تصمیم‌ها، موارد باز،
گزارش پیشرفت و Runbook انتقال Production بررسی شدند. نتیجه مهم این است که قالب
یک Block Theme سه‌زبانه با Polylang و Rank Math است و `team_member` باید به‌عنوان
CPT باقی بماند.

### ۳. موجودی Read-only سایت اصلی

REST API عمومی و wp-admin بررسی شدند. موجودی منتشرشده قبل از مهاجرت:

| نوع محتوا | تعداد | توضیح |
|---|---:|---|
| Posts | 70 | دسته‌بندی‌ها و ارتباط‌های سه‌زبانه فعال‌اند |
| Conditions | 18 | ۶ موضوع در سه زبان |
| Innovations | 9 | ۳ موضوع در سه زبان |
| Publications | 0 | خالی |
| Patient Resources | 0 | خالی |

دسته‌بندی Innovation از قبل در هر سه زبان وجود دارد و باید همان گروه ترجمه
موجود دوباره استفاده شود، نه اینکه دسته تکراری ساخته شود.

### ۴. استانداردسازی نسخه قالب

- نسخه هدف قالب `1.0.0` تعیین شد؛ تغییر مدل محتوا breaking است و این نسخه اولین
  baseline پایدار SemVer خواهد بود.
- هدر `style.css` منبع واحد نسخه شد.
- `DAM_THEME_VERSION` دیگر hard-code نیست و با `wp_get_theme()` از همان هدر
  خوانده می‌شود.
- نسخه واحد برای صفحه Appearance و cache-busting فایل‌های CSS/JS استفاده می‌شود.
- `Author URI`، `Domain Path`، `Update URI` و `Tested up to` تکمیل شدند.

فایل‌های مرتبط:

- `wordpress-theme/dr-ali-moradi/style.css`
- `wordpress-theme/dr-ali-moradi/functions.php`
- `docs/wordpress-theme/versioning.md`
- `wordpress-theme/dr-ali-moradi/CHANGELOG.md`

### ۵. پیاده‌سازی مهاجرت CPTها به Post

فایل جدید:

`wordpress-theme/dr-ali-moradi/inc/content-migrations.php`

رفتار مهاجرت:

- فقط در یک درخواست احراز‌شده مدیر و روی `admin_init` اجرا می‌شود.
- با option نسخه و transient lock، idempotent و در برابر اجرای هم‌زمان محافظت
  شده است.
- رکوردها کپی یا حذف نمی‌شوند؛ فقط `post_type` همان رکورد به `post` تغییر می‌کند.
- موارد زیر حفظ می‌شوند:
  - ID
  - عنوان، محتوا و خلاصه
  - نویسنده
  - تاریخ و وضعیت انتشار
  - تصویر شاخص و Media
  - Commentها
  - Metadata
  - زبان و گروه ترجمه Polylang
- مسیر قدیمی در `_dam_legacy_permalink_path` ذخیره می‌شود.
- نوع قبلی در `_dam_legacy_post_type` ذخیره می‌شود.
- مسیرهای قدیمی CPT فقط در حالت 404 با 301 به permalink جدید هدایت می‌شوند.
- نتیجه اجرا در option زیر ثبت می‌شود:
  `dam_content_migration_report`
- نسخه schema در option زیر ثبت می‌شود:
  `dam_content_schema_version`

نگاشت دسته‌ها:

| CPT قدیمی | دسته مقصد |
|---|---|
| `condition` | Clinical Conditions / بیماری‌ها / الحالات المرضية |
| `innovation` | گروه موجود Innovation / نوآوری / الابتكار |
| `publication` | Publications / انتشارات / المنشورات |
| `patient_resource` | Patient Resources / منابع بیماران / موارد المرضى |

تا قبل از پایان مهاجرت، post typeهای قدیمی به‌صورت موقت و بدون UI/REST/route
ثبت می‌شوند تا API ترجمه Polylang بتواند ارتباط‌های قبلی را بخواند. بعد از
مهاجرت دیگر ثبت نمی‌شوند و منوهای آن‌ها نمایش داده نخواهد شد.

### ۶. اصلاح مدل محتوا و مستندات

- فقط `team_member` به‌عنوان CPT عمومی باقی مانده است.
- taxonomyهای `condition_category` و `publication_type` از ثبت قالب حذف شدند.
- دسته‌های پایه جدید به Seeder قالب اضافه شدند.
- Architecture، migration plan، decisions log، open items، progress log و
  READMEها به‌روزرسانی شدند.
- `single-condition.html` و `single-innovation.html` فعلاً حذف نشده‌اند؛ فقط
  compatibility تاریخی هستند و بعد از مهاجرت استفاده نمی‌شوند.

### ۷. تست‌های انجام‌شده

- PHP syntax check روی تمام فایل‌های PHP قالب: موفق
- `git diff --check`: موفق
- تست جدید قرارداد انتشار:
  `tests/wordpress-theme-release.php`: موفق
- `npm test`: موفق
  - Build کامل Vinext/Vite
  - ۵ تست رندر و مسیرها
  - تست قرارداد WordPress
- `npm ci` برای اجرای تست‌ها انجام شد.
- Audit نصب Node تعداد ۲۱ هشدار dependency موجود را گزارش کرد
  (۲ low، ۲ moderate، ۱۶ high و ۱ critical). هیچ `npm audit fix` یا تغییر
  خودکار dependency انجام نشد چون خارج از محدوده این تغییر و بالقوه breaking است.

## مانع مهم قبل از انتشار Production

قالب نصب‌شده روی `dralimoradi.com` دقیقاً با Git یکسان نیست:

- قالب زنده در فایل عمومی `style.css` نسخه `0.2.1` دارد.
- Git اولیه هدر `0.1.0` و ثابت cache داخلی `0.9.4` داشت.
- نسخه زنده برای assetها از file modification time استفاده می‌کند؛ نمونه
  مشاهده‌شده `style.css?ver=1789601881` است که به ۲۰۲۶-۰۹-۱۷ برمی‌گردد.
- فایل‌های عمومی زیر در Production از Git جدیدتر یا متفاوت‌اند:
  - `assets/css/style.css`
  - `assets/css/editor.css` (بخش عمده تفاوت فقط line ending است)
  - `assets/js/theme-fields.js`
  - چند `templates/*.html`
  - چند `blocks/*/block.json`
  - `theme.json`
  - تعدادی Font و Logo
- CSS زنده قابلیت‌هایی مانند Breadcrumb، pagination آرشیو، اطلاعات کامل‌تر
  صفحه نوشته، team profile links و فونت Abar را دارد که در Git اولیه کامل نبود.
- JavaScript زنده برای Team Member یک کنترل چندلینکی دارد که در Git اولیه نبود.

نتیجه: Upload کردن zip فعلی Branch روی Production می‌تواند تغییرات زنده و
commit‌نشده را overwrite کند. به همین دلیل هیچ فایل یا دیتابیس Production در
این مرحله تغییر داده نشده است.

این مانع در `docs/wordpress-theme/open-items.md` با شماره ۱۴ ثبت شده است.

## دسترسی‌ها و وضعیت مرورگر

- Chrome کاربر به wp-admin سایت اصلی Login است.
- آدرس DirectAdmin از مستندات:
  `https://server141i.irwebspace.com:2223/`
- در آخرین بررسی DirectAdmin Login نبود؛ مالک باید ابتدا در Chrome وارد شود.
- Theme File Editor وردپرس هشدار ویرایش مستقیم نمایش داد. هشدار تأیید شد، اما
  Accessibility لایه Chrome بعد از آن پایدار نبود؛ هیچ فایل PHP از داخل UI
  تغییر داده نشد.
- هیچ Application Password جدید، Token، Cookie یا Secret ایجاد یا ذخیره نشده
  است.
- هیچ Secret نباید در Git، این فایل یا گزارش‌های بعدی نوشته شود.

## ترتیب دقیق ادامه کار

### مرحله A — دریافت Snapshot قالب زنده

1. مطمئن شوید Branch `codex/content-types-to-posts` و آخرین Commit این فایل
   Checkout شده است.
2. مالک در Chrome وارد DirectAdmin شود.
3. از مسیر Production فقط پوشه فعال زیر را به‌صورت zip دانلود کنید:
   `/domains/dralimoradi.com/public_html/wp-content/themes/dr-ali-moradi`
4. قبل از هر تغییر، checksum و تاریخ Snapshot را ثبت کنید.
5. فایل zip یا محتوای آن را وارد Git نکنید تا اول بررسی شود هیچ Secret یا فایل
   محیطی داخل آن نیست.
6. فایل‌های قالب زنده را با Branch مقایسه کنید و تغییرات واقعی Production را
   به Git منتقل کنید؛ تغییرات مهاجرت `7c484fa` نباید از بین بروند.
7. تفاوت‌های line ending یا cache-generated را از تغییرات واقعی جدا کنید.

### مرحله B — بازبینی ادغام

1. به‌خصوص این فایل‌های PHP زنده را با Git تطبیق دهید:
   - `functions.php`
   - `inc/post-types.php`
   - `inc/taxonomies.php`
   - `inc/meta-fields.php`
   - `inc/blog-content.php`
   - فایل‌های render مربوط به Blog، Team، Header و Footer
2. منطق filemtime زنده را با مدل نسخه واحد SemVer ادغام کنید؛ نسخه header باید
   همچنان منبع واحد باقی بماند.
3. رفتار جدید Team links، Breadcrumb، pagination و محتوای واقعی single post را
   حفظ کنید.
4. تمام PHP lintها و `npm test` را دوباره اجرا کنید.
5. یک Commit مستقل برای «sync production theme snapshot» بسازید و Push کنید.
6. مستندات و Changelog را با نتیجه ادغام به‌روزرسانی، Commit و Push کنید.

### مرحله C — آماده‌سازی و Backup قبل از مهاجرت

1. از دیتابیس Production و پوشه فعال قالب Backup قابل بازیابی بگیرید.
2. قبل از تغییر، دوباره تعدادها را ثبت کنید:
   - Posts: انتظار 70 یا بیشتر در صورت محتوای جدید
   - Conditions: انتظار 18
   - Innovations: انتظار 9
   - Publications: انتظار 0
   - Patient Resources: انتظار 0
3. فهرست ID، زبان، title، status، categories و permalink همه ۲۷ رکورد را به‌عنوان
   audit snapshot ذخیره کنید؛ Secret یا داده شخصی وارد Git نشود.
4. zip نصب قالب را از Commit نهایی و با ریشه صحیح
   `dr-ali-moradi/style.css` بسازید.
5. حجم و ساختار zip را با `unzip -t` و `unzip -l` کنترل کنید.

### مرحله D — انتشار و اجرای مهاجرت

1. قالب به‌روزشده را از Appearance → Themes یا File Manager جایگزین کنید.
2. اولین درخواست wp-admin مدیر باید migration را اجرا کند.
3. گزارش option `dam_content_migration_report` را بررسی کنید؛ `errors` باید خالی
   باشد و شمارش مهاجرت با snapshot قبل از انتشار برابر باشد.
4. مقدار `dam_content_schema_version` باید `1.0.0` شود.
5. در منوی مدیریت فقط «اعضای تیم» از CPTهای اختصاصی باقی بماند.
6. دسته‌های سه‌زبانه ایجاد و innovationها به دسته موجود متصل شده باشند.
7. مجموع Posts باید به‌اندازه تعداد مهاجرت‌شده افزایش یابد؛ در snapshot فعلی
   انتظار 97 نوشته منتشرشده است، مگر اینکه در فاصله زمانی محتوای جدید اضافه شود.
8. هیچ رکورد condition/innovation/publication/patient_resource نباید پس از
   موفقیت migration در دیتابیس باقی بماند.

### مرحله E — کنترل عملکرد و SEO

1. حداقل یک گروه ترجمه Condition و یک گروه Innovation را در سه زبان بررسی کنید.
2. title، content، excerpt، date، author، featured image، metadata و دسته را با
   snapshot قبل از مهاجرت مقایسه کنید.
3. old URLها را بررسی کنید؛ نمونه‌ها:
   - `/conditions/hand-and-wrist-disorders/`
   - `/fa/conditions/hand-and-wrist-disorders/`
   - `/ar/conditions/hand-and-wrist-disorders/`
   - `/innovation/external-fixation-systems/`
4. هر URL قدیمی باید یک 301 و سپس یک صفحه 200 نوشته بدهد؛ redirect chain یا
   loop قابل قبول نیست.
5. Blog archive، homepage latest cards، category archives، sitemap و Rank Math
   را بررسی کنید.
6. توجه ویژه: رکوردهای مهاجرت‌شده تاریخ ۲۰۲۶-۰۹-۰۲ دارند و ممکن است ترتیب Latest
   Posts صفحه اصلی را تغییر دهند یا کارت بدون تصویر تولید کنند. این رفتار باید
   بصری بررسی و در صورت نیاز query صفحه اصلی با دسته‌های موردنظر محدود شود؛ تاریخ
   رکوردها برای حل ظاهری دستکاری نشود.
7. English، Persian و Arabic، RTL/LTR، mobile و desktop بررسی شوند.
8. نتیجه نهایی همراه با commit، checksum zip، شمارش قبل/بعد و URLهای تست‌شده در
   `progress-log.md` ثبت و Push شود.

### مرحله F — تکمیل Release

1. فقط پس از ادغام Snapshot زنده، انتشار موفق و verification، tag زیر ساخته و
   Push شود:
   `theme-v1.0.0`
2. Branch موقت مستقیماً و بدون بازبینی روی `upstream/main` Push نشود.
3. پس از تأیید مالک، تغییرات به ریپوی اصلی از طریق merge/PR منتقل شود.
4. ریپوی موقت تا پایان تأیید و امکان rollback حذف نشود.

## نکات ایمنی مهم برای Agent بعدی

- بدون Snapshot کامل قالب زنده، theme zip فعلی را روی Production نصب نکن.
- هیچ رکورد محتوایی را Delete نکن؛ migration باید in-place باشد.
- هیچ دسته Innovation تکراری نساز؛ ابتدا گروه ترجمه موجود را resolve کن.
- تغییر type را با ویرایش دستی ۲۷ رکورد در UI انجام نده؛ migration نسخه‌دار برای
  همین کار نوشته شده است.
- `npm audit fix --force` اجرا نکن.
- Secret، Cookie، Application Password، Token، `.env` یا محتوای `wp-config.php`
  را نمایش یا Commit نکن.
- قبل از عملیات برگشت‌ناپذیر Production، Backup و شمارش قبل از مهاجرت را تأیید
  کن.
- هر مرحله منطقی را جدا Commit و فوراً روی همین Branch Push کن.

## فرمان‌های کنترل سریع

```bash
git status --short --branch
git log --oneline --decorate -6
php tests/wordpress-theme-release.php
find wordpress-theme/dr-ali-moradi -name '*.php' -print0 | xargs -0 -n1 php -l
npm test
git diff --check
```

## فایل‌های مرجع اصلی

- `wordpress-theme/dr-ali-moradi/CHANGELOG.md`
- `wordpress-theme/dr-ali-moradi/inc/content-migrations.php`
- `docs/wordpress-theme/versioning.md`
- `docs/wordpress-theme/architecture.md`
- `docs/wordpress-theme/content-migration-plan.md`
- `docs/wordpress-theme/open-items.md`
- `docs/wordpress-theme/progress-log.md`
- `docs/production-cutover-runbook-fa.md`

این فایل منبع شروع Session بعدی است. ابتدا وضعیت Git و مانع Production را با
آن تطبیق دهید و سپس از مرحله A ادامه دهید.
