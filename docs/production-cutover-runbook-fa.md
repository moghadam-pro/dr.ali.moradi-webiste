# برنامه انتقال نسخه WordPress به دامنه اصلی

آخرین به‌روزرسانی: ۲۰۲۶-۰۹-۱۲

این سند منبع حقیقت عملیاتی برای انتقال نسخه جدید وب‌سایت دکتر علی مرادی به
دامنه اصلی، نگهداری نسخه قبلی به‌صورت آرشیو و بازگشت امن در صورت بروز خطاست.
اطلاعات محرمانه مانند رمز، Token، Private Key، Cookie و محتوای `.env` نباید در
این سند یا هیچ فایل دیگری در Git ثبت شوند.

## محیط‌ها و نقش هر نسخه

| نقش | نشانی | پیاده‌سازی | وضعیت مطلوب |
|---|---|---|---|
| مرجع توسعه | <https://dralimoradi.moghadam.pro/> | React/Vinext | مرجع تصویری و محتوایی؛ پس از Cutover نیز موقتاً برای مقایسه نگهداری شود |
| Staging کاندید انتشار | <https://tmp.saveon.me/> | قالب اختصاصی WordPress | منبع نسخه‌ای که پس از تأیید و Clone کامل به Production منتقل می‌شود |
| Production فعلی/نهایی | <https://dralimoradi.com/> | WordPress | ابتدا سایت فعلی است؛ پس از Cutover باید نسخه جدید را ارائه کند |
| آرشیو نسخه قبلی | <https://legacy.dralimoradi.com/> | Clone مستقل از WordPress فعلی | نسخه قبل از Cutover را فقط برای مراجعه تاریخی ارائه کند |
| Repository خصوصی | <https://github.com/moghadam-pro/dr.ali.moradi-webiste> | Source، قالب، اسکریپت و مستندات | تاریخچه و منبع کنترل نسخه؛ جایگزین Backup دیتابیس و Uploadهای WordPress نیست |

از تاریخ ۲۰۲۶-۰۹-۰۵، قالب WordPress منبع اصلی ادامه توسعه است و نسخه React نقش
مرجع تطبیق را دارد. جزئیات تطبیق در
`docs/wordpress-react-parity-audit-2026-09-05-fa.md` ثبت شده است.

## وضعیت مشاهده‌شده در ۲۰۲۶-۰۹-۱۲

این بخش نتیجه بررسی Read-only از بیرون است و ممکن است با تغییر DNS یا تنظیمات
سرور تغییر کند:

- `dralimoradi.moghadam.pro` پاسخ `200` می‌دهد و پشت Cloudflare است.
- `dralimoradi.com` پاسخ `200` می‌دهد و WordPress فعلی را از Nginx ارائه می‌کند.
- `tmp.saveon.me` در DNS به IP خصوصی `10.10.34.34` Resolve می‌شود و از محیط
  بررسی فعلی روی HTTP/HTTPS قابل دسترسی نبود. سلامت آن باید از شبکه‌ای که به این
  IP دسترسی دارد یا از داخل سرور بررسی شود.
- `dralimoradi.com` و `legacy.dralimoradi.com` هر دو به `185.106.200.38` Resolve
  می‌شوند.
- درخواست HTTP به `legacy.dralimoradi.com` در حال حاضر با `301` به
  `https://dralimoradi.com/` منتقل می‌شود. بنابراین آرشیو هنوز به‌صورت یک سایت
  مستقل و قابل دسترس تأیید نشده است.
- REST API سایت اصلی نشانه‌های WPML را دارد، درحالی‌که مستندات Staging استفاده
  از Polylang را ثبت کرده‌اند. دیتابیس، `wp-content` و تنظیمات افزونه‌های این دو
  نصب نباید با هم ادغام شوند.

### بررسی DirectAdmin در ۲۰۲۶-۰۹-۱۲

- پنل Hosting سایت اصلی DirectAdmin Evolution در
  <https://server141i.irwebspace.com:2223/> است.
- فهرست **Site Redirects** خالی است؛ ریدایرکت Legacy در DirectAdmin تعریف نشده است.
- Subdomain با نام `legacy.dralimoradi.com` وجود دارد و Document Root مستقل آن
  `/domains/legacy.dralimoradi.com/public_html` است.
- داخل Document Root آرشیو یک نصب کامل WordPress شامل `wp-admin`، `wp-content`،
  `wp-includes` و فایل‌های Root وجود دارد.
- فایل `.htaccess` آرشیو فقط Rewrite استاندارد WordPress را دارد و هیچ قانون
  Redirect به دامنه اصلی در آن ثبت نشده است.
- پاسخ عمومی Legacy با هدر `X-Redirect-By: WordPress` به دامنه اصلی منتقل می‌شود.
  بنابراین علت محتمل، باقی‌ماندن مقادیر `home`/`siteurl` یا ثابت‌های متناظر روی
  `https://dralimoradi.com` در نصب کپی‌شده است، نه تنظیمات DirectAdmin یا `.htaccess`.
- DirectAdmin فقط یک دیتابیس پُر با حدود `223.84 MB` و `47` جدول نشان می‌دهد؛
  دیتابیس دوم خالی و بدون جدول است. تا زمان بررسی امن `wp-config.php` فرض عملیاتی
  این است که کپی Legacy هنوز دیتابیس مستقل ندارد و احتمالاً به دیتابیس Production
  متصل است.
- مجوز `wp-config.php` در هر دو Document Root برابر `0666` و بیش‌ازحد باز است.
  بدون نمایش یا تغییر محتوای فایل، باید پس از Backup به مجوز محدود متناسب با
  تنظیمات هاست (معمولاً `0640` یا `0600` با Owner/Group صحیح) اصلاح شود.

## تصمیم پیشنهادی

روش کم‌ریسک، **Clone کامل WordPress Staging به یک Production مستقل** است؛ نه
نصب دوباره قالب و Import مجدد محتوا روی دیتابیس فعلی سایت اصلی. دلیل این تصمیم:

1. بخش مهمی از وضعیت واقعی Staging داخل دیتابیس و Media Library است: ترجمه‌ها،
   منوها، تنظیمات قالب، فرم MPro Forms، تنظیمات SEO و Customizationهای WordPress.
2. Repository کد و مستندات را نگه می‌دارد، ولی Snapshot کامل دیتابیس و Uploadهای
   جاری WordPress نیست.
3. سایت فعلی Production از WPML و Staging از Polylang استفاده می‌کنند؛ Merge
   درون یک دیتابیس می‌تواند Translation Linkها، منوها و Meta را خراب کند.
4. Clone مستقل امکان Preview قبل از Cutover و Rollback سریع با بازگرداندن Vhost
   یا Document Root قبلی را فراهم می‌کند.

اگر Hosting ابزار Clone اتمیک و قابل بازگشت دارد، همان ابزار بر Migration دستی
ترجیح دارد. در غیر این صورت از Backup کامل فایل‌ها و دیتابیس، انتقال `wp-content`
و دیتابیس و سپس `wp search-replace` سازگار با داده‌های Serialized استفاده شود.

## دروازه‌های اجباری قبل از Cutover

Cutover فقط وقتی انجام شود که همه موارد زیر پاسخ مثبت داشته باشند:

- Staging از شبکه یا سرور قابل دسترسی و Backup کامل آن دریافت شده باشد.
- نسخه فعلی Production از فایل‌ها و دیتابیس Backup شده و Restore آزمایشی یا حداقل
  اعتبارسنجی Archive انجام شده باشد.
- `legacy.dralimoradi.com` با HTTPS، Document Root و دیتابیس مستقل بالا آمده باشد؛
  نه اینکه به Production ریدایرکت شود.
- همه URLهای منتشرشده در سه زبان Crawl شده و خطاهای `4xx/5xx`، PHP Warning و
  Mixed Content نداشته باشند.
- موارد باز P0/P1 گزارش تطبیق و موارد `docs/wordpress-theme/open-items.md` تصمیم‌گیری
  یا بسته شده باشند؛ به‌ویژه ترجمه عربی فرم، صفحات Single مربوط به Condition و
  Innovation، Canonical/Schema/Sitemap، مسیرهای URL و محتوای نیازمند تأیید پزشک.
- مقصد فرم تماس، ایمیل‌ها، لینک نوبت‌دهی و شبکه‌های اجتماعی با داده واقعی آزمایش
  شده باشد.
- نقشه Redirect مسیرهای قدیمی آماده و روی نسخه Preview آزمایش شده باشد.
- روش Rollback، مسئول اجرا و مدت پنجره نگهداری مشخص باشند.

## مراحل اجرایی

### مرحله ۱ — Inventory و Freeze

1. دسترسی‌های Hosting/SSH، پنل DNS، wp-admin هر دو WordPress و محل Backup مشخص شود.
2. نسخه WordPress/PHP/MariaDB، افزونه‌ها، Cronها، SMTP، حجم دیتابیس و `uploads`،
   Document Rootها و نام دیتابیس‌ها ثبت شوند.
3. ویرایش محتوا روی Production و Staging از زمان Backup نهایی تا پایان Cutover
   متوقف شود.
4. TTL رکوردهایی که قرار است تغییر کنند، حداقل ۲۴ ساعت زودتر به حدود ۳۰۰ ثانیه
   کاهش یابد. اگر Cutover فقط با تعویض Vhost/Document Root روی همان IP انجام شود،
   تغییر DNS لازم نیست.

### مرحله ۲ — ساخت آرشیو مستقل از Production فعلی

1. از فایل‌ها و دیتابیس `dralimoradi.com` Snapshot زمان‌دار بگیرید.
2. یک Vhost، Document Root و دیتابیس مستقل برای `legacy.dralimoradi.com` بسازید.
3. Snapshot سایت فعلی را در Legacy Restore کنید.
4. با ابزار Serialization-safe دامنه را از `dralimoradi.com` به
   `legacy.dralimoradi.com` تغییر دهید. در WP-CLI، جایگزینی دامنه باید روی
   Tableهای همان نصب انجام شود و ستون `guid` بدون دلیل تغییر نکند.
5. ایمیل‌های تراکنشی، فرم‌های فعال، درگاه‌ها و Jobهایی که نباید از آرشیو اجرا
   شوند غیرفعال شوند. روی آرشیو `noindex` اعمال شود.
6. گواهی TLS معتبر نصب و صفحه اصلی، wp-admin، تصاویر و چند URL قدیمی بررسی شود.

**Gate A:** تا وقتی Legacy مستقل با پاسخ `200` و Assetهای سالم تأیید نشده، سایت
فعلی Production دست‌نخورده می‌ماند.

### مرحله ۳ — ساخت کاندید Production از Staging

1. از دیتابیس، `wp-content` و فایل‌های پیکربندی Staging Backup کامل بگیرید.
2. Clone را در Document Root و دیتابیس تازه‌ای برای Production Restore کنید؛
   روی دیتابیس فعلی Production Import نکنید.
3. Secretها و تنظیمات محیطی Production مانند SMTP، Cache و Security Saltها را
   جداگانه اعمال کنید؛ آن‌ها را از Git یا متن چت منتقل نکنید.
4. جایگزینی `tmp.saveon.me` با `dralimoradi.com` را Serialization-safe انجام دهید.
5. Permalinkها، Cacheها، Rewrite Rules و Sitemap بازسازی شوند.
6. Canonical، Open Graph، `hreflang`، robots و آدرس Mediaها بررسی شوند تا اثری از
   دامنه Staging یا مرجع React باقی نماند.

### مرحله ۴ — Preview بدون تغییر عمومی

کاندید Production را با Preview URL کنترل‌شده، نگاشت محلی `hosts` یا قابلیت
Preview پنل Hosting روی دامنه اصلی آزمایش کنید. حداقل این موارد بررسی شوند:

- صفحه اصلی و صفحات کلیدی انگلیسی، فارسی و عربی؛
- RTL، فونت، منوها، Language Switcher و لینک ترجمه متناظر؛
- Blog، Team، Gallery، Condition و Innovation؛
- فرم تماس، لینک نوبت‌دهی، جستجو و صفحه 404؛
- تصاویر، فایل‌ها، موبایل و دسکتاپ؛
- Sitemap، robots، Canonical، Schema، Redirectها و پاسخ‌های HTTP؛
- خطاهای PHP، Nginx و Browser Console.

**Gate B:** تأیید Preview باید قبل از هر تغییر عمومی ثبت شود.

### مرحله ۵ — Cutover

1. Maintenance Window شروع و Backup نهایی از هر دو سایت گرفته شود.
2. اگر IP ثابت می‌ماند، Vhost یا Document Root دامنه اصلی به Clone جدید متصل
   شود. اگر سرور تغییر می‌کند، رکوردهای DNS به مقصد جدید تغییر کنند.
3. TLS، Cache/CDN و Rewriteها Reload شوند.
4. صفحه اصلی، REST، Sitemap، فرم و مجموعه URLهای Smoke Test بلافاصله کنترل شوند.
5. زمان Cutover، Snapshotها و نسخه قالب/Commit متناظر در Log ثبت شوند.

### مرحله ۶ — پایش و Rollback

در ۳۰ دقیقه اول و سپس طی ۲۴ تا ۷۲ ساعت، خطاهای `5xx/404`، PHP، فرم‌ها، Cache،
Core Web Vitals و Indexability پایش شوند. Backupها حداقل تا پایان این دوره حذف
نشوند.

Rollback زمانی اجرا شود که خطای گسترده، خرابی دیتابیس، عدم ارسال فرم یا مشکل
بحرانی ترجمه/مسیریابی در زمان توافق‌شده رفع نشود. Rollback ترجیحاً فقط با
برگرداندن Vhost/Document Root یا DNS به نسخه قبلی انجام شود؛ دیتابیس‌های قدیم و
جدید با هم Merge نشوند.

## چک‌لیست ثبت هر انتشار

- تاریخ و ساعت Freeze و Cutover:
- مسئول اجرا و تأییدکننده:
- Commit قالب منتشرشده:
- شناسه/مسیر Backup سایت فعلی:
- شناسه/مسیر Backup Staging:
- روش Cutover: Vhost / Document Root / DNS
- نتیجه Gate A و Gate B:
- نتیجه Smoke Test سه‌زبانه:
- نتیجه فرم تماس و نوبت‌دهی:
- محل Log و Monitoring:
- آخرین زمان مجاز Rollback:
- نتیجه نهایی و موارد باقی‌مانده:

## وضعیت این برنامه

- همگام‌سازی Repository محلی با `origin/main` در ۲۰۲۶-۰۹-۱۲ انجام شد؛ Commit
  مبنا `badd892f5fb33918664154e03656be69df8917ab` است.
- بررسی Read-only دامنه‌ها انجام شد.
- Backup کامل DirectAdmin شامل Domain Directory، Subdomain List، داده و تنظیمات
  دیتابیس، ایمیل، FTP و Trash در ۲۰۲۶-۰۹-۱۲ ساخته شد. فایل
  `/backups/backup-Sep-12-2026-1.tar.gz` با حجم `512.75 MB` و مجوز `0640` ثبت شد
  و DirectAdmin در ساعت نمایش‌داده‌شده `2:00 PM` پیام آماده‌بودن Backup را صادر کرد.
- کپی Off-host بکاپ توسط مالک پروژه روی سیستم محلی دانلود شد.
- دیتابیس موجود و پُر `dralimor_dmsdralimoradi` به نسخه آرشیو اختصاص یافت. در جدول
  `dmswp_options` هر دو مقدار `home` و `siteurl` به
  `https://legacy.dralimoradi.com` تغییر داده و نتیجه مجدداً از دیتابیس خوانده شد.
- فایل‌های اصلی `wp-config.php` و `.htaccess` آرشیو از بکاپ Off-host
  `old-site-backup_20260910.zip` بازیابی شدند. هیچ رمز، Salt یا مقدار محرمانه‌ای
  در Repository ثبت نشده است.
- آزمون عملی آرشیو در ۲۰۲۶-۰۹-۱۲ موفق بود: صفحه اصلی، مسیر `/en/`، مسیر
  `/wp-json/` و صفحه ورود همگی پاسخ `200` دادند؛ `/wp-admin/` نیز کاربر ناشناس را
  به صفحه ورود هدایت کرد. تنظیم `blog_public=0` (متای `noindex,nofollow`) و
  `users_can_register=0` نیز تأیید شد.
- مجوز `wp-config.php` آرشیو از حالت قابل‌خواندن برای عموم به `0640`
  (`-rw-r-----`) محدود شد. پس از تغییر مجوز، صفحه اصلی، `/en/`، `/wp-json/` و
  `wp-login.php` مجدداً بررسی شدند و همگی پاسخ `200` دادند.
- تا زمان جایگزینی فایل‌های Production، نصب قدیمی در `dralimoradi.com` به‌علت
  استفاده از دیتابیس منتقل‌شده با `301` به آرشیو هدایت می‌شود. این وضعیت موقت و
  بخشی از پنجره Cutover است، نه Redirect تعریف‌شده در DirectAdmin.
- دیتابیس خالی `dralimor_base` با صفر جدول برای نصب تازه Production آماده است.
- مرحله بعدی پس از Gate تأیید: محدودکردن مجوز `wp-config.php` آرشیو، تخلیه دقیق
  `/domains/dralimoradi.com/public_html`، نصب WordPress تازه روی `dralimor_base`
  و مهاجرت Serialization-safe از `tmp.saveon.me` است.
