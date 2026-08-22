# راهنمای بروزرسانی سرور از Repository خصوصی

این سند روش فعلی و تأییدشده انتشار نسخه دمو را ثبت می‌کند تا روی هر سیستم دیگری
بدون نیاز به تاریخچه چت قابل تکرار باشد.

## تصمیم جاری

- Repository خصوصی است و سرور برای دریافت Source به GitHub متصل نمی‌شود.
- نسخه انتشار روی سیستم توسعه از یک Commit مشخص به صورت Zip ساخته می‌شود.
- Zip در مسیری خارج از پوشه فعال سایت آپلود و Extract می‌شود.
- اسکریپت `scripts/deploy-uploaded-release.sh` ابتدا Build می‌گیرد، از نسخه فعال
  Backup می‌سازد، Release را جایگزین می‌کند، سرویس را Restart و Health Check را
  اجرا می‌کند.
- در صورت شکست پس از جایگزینی، اسکریپت Backup را خودکار بازمی‌گرداند.
- مقصد عمومی نسخه دمو `https://dralimoradi.moghadam.pro/` است.

طبق تصمیم جدید مالک پروژه، Repository خصوصی است و مشخصات عملیاتی غیرمحرمانه سرور
برای همگام‌سازی بین سیستم‌ها در Git ثبت می‌شوند. Password، Token، Private Key،
Certificate و محتوای `.env` همچنان نباید در Git قرار گیرند.

## ۱. همگام‌سازی سیستم توسعه

پیش از ساخت Release:

```bash
git status --short
git switch main
git fetch --prune origin
git pull --ff-only origin main
npm test
```

اگر `git status` تغییر محلی نشان می‌دهد، پیش از Pull آن را از بین نبرید. برای
نجات تغییرات منتشرنشده ابتدا یک Branch بازیابی بسازید:

```bash
git switch -c recovery/unpublished-local-work
git add --all
git commit -m "recovery: preserve unpublished local changes"
git push -u origin recovery/unpublished-local-work
```

## ۲. ساخت Zip تغییرناپذیر از Commit

از Working Tree با Finder یا ابزارهای عمومی Zip نسازید. بسته باید با
`git archive` از Commit مورد نظر ساخته شود تا `.env`، `node_modules` و فایل‌های
ثبت‌نشده وارد Release نشوند:

```bash
MORADI_RELEASE_COMMIT="$(git rev-parse --short=7 origin/main)"
MORADI_RELEASE_ZIP="$HOME/Desktop/dr-alimoradi-${MORADI_RELEASE_COMMIT}.zip"

git archive \
  --format=zip \
  --output="$MORADI_RELEASE_ZIP" \
  "origin/main"

unzip -t "$MORADI_RELEASE_ZIP"
shasum -a 256 "$MORADI_RELEASE_ZIP"
ls -lh "$MORADI_RELEASE_ZIP"
```

ادامه کار فقط زمانی مجاز است که `unzip -t` پیام زیر را نمایش دهد:

```text
No errors detected in compressed data
```

Hash چاپ‌شده باید برای مقایسه پس از آپلود نگهداری شود.

## ۳. پروفایل دقیق سرور فعلی

منبع قابل Source شدن این تنظیمات فایل
`scripts/server-profile.dralimoradi-demo.env` است. پروفایل فعلی:

```bash
MORADI_SERVER_LABEL="hel-20231219"
MORADI_SERVER_ADDRESS="65.109.217.252"
MORADI_PUBLIC_URL="https://dralimoradi.moghadam.pro/"

MORADI_DEPLOY_USER="drmomin"
MORADI_RELEASE_ROOT="/home/drmomin/releases"
MORADI_LIVE_APP_DIR="/home/drmomin/htdocs/dralimoradi.moghadam.pro"
MORADI_NVM_DIR="/home/drmomin/.nvm"
MORADI_NODE_VERSION="22"

MORADI_PM2_APP="dr-alimoradi-demo"
MORADI_INTERNAL_PORT="3006"
MORADI_HEALTH_URL="http://127.0.0.1:3006/"
```

Nginx/CloudPanel درخواست دامنه عمومی را به سرویس Node روی پورت داخلی ۳۰۰۶ هدایت
می‌کند. PM2 Process متعلق به کاربر `drmomin` است؛ بنابراین فرمان‌های PM2 و Build
باید با همین کاربر اجرا شوند.

فایل Zip باید در `/home/drmomin/releases` آپلود شود و نام آن شامل Commit کوتاه
باشد. نمونه نام فایل:

```text
dr-alimoradi-<release-id>.zip
```

## ۴. آماده‌سازی متغیرها و اعتبارسنجی فایل آپلودشده

روی سرور، Release ID را با Commit داخل نام Zip برابر قرار دهید:

```bash
MORADI_DEPLOY_USER="drmomin"
MORADI_RELEASE_ID="<commit-or-release-id>"
MORADI_RELEASE_ROOT="/home/drmomin/releases"

sha256sum "$MORADI_RELEASE_ROOT/dr-alimoradi-${MORADI_RELEASE_ID}.zip"
unzip -t "$MORADI_RELEASE_ROOT/dr-alimoradi-${MORADI_RELEASE_ID}.zip"
```

Hash باید دقیقاً با Hash سیستم توسعه برابر باشد. در صورت اختلاف یا خطای Zip، فایل
نباید Extract یا Deploy شود و باید دوباره در حالت Binary آپلود شود.

## ۵. Extract در پوشه تازه

برای جلوگیری از ترکیب‌شدن فایل‌های دو نسخه، هر Release در پوشه‌ای تازه Extract
می‌شود:

```bash
MORADI_EXTRACTED_DIR="$MORADI_RELEASE_ROOT/dr-alimoradi-${MORADI_RELEASE_ID}"

sudo install -d \
  -o "$MORADI_DEPLOY_USER" \
  -g "$MORADI_DEPLOY_USER" \
  -m 755 \
  "$MORADI_EXTRACTED_DIR"

sudo chown "$MORADI_DEPLOY_USER:$MORADI_DEPLOY_USER" \
  "$MORADI_RELEASE_ROOT/dr-alimoradi-${MORADI_RELEASE_ID}.zip"

sudo -u "$MORADI_DEPLOY_USER" unzip -q \
  "$MORADI_RELEASE_ROOT/dr-alimoradi-${MORADI_RELEASE_ID}.zip" \
  -d "$MORADI_EXTRACTED_DIR"

test -f "$MORADI_EXTRACTED_DIR/package.json"
test -f "$MORADI_EXTRACTED_DIR/package-lock.json"
test -f "$MORADI_EXTRACTED_DIR/scripts/deploy-uploaded-release.sh"
```

دستور `install` نباید Source و Destination یکسان داشته باشد. برای تغییر مالکیت
فایل موجود از `chown` استفاده می‌شود.

## ۶. اجرای Deployment با مالک Process

PM2 برای هر کاربر Process List جدا دارد. اسکریپت باید با همان کاربری اجرا شود که
Process سایت را مدیریت می‌کند، نه با PM2 کاربر `root`.

ابتدا از `/root` خارج شوید تا Login Shell کاربر سرویس با خطای Working Directory
مواجه نشود:

```bash
cd /tmp
```

ابتدا Profile همان Release را Source کنید و سپس Deployment را اجرا کنید:

```bash
source "$MORADI_EXTRACTED_DIR/scripts/server-profile.dralimoradi-demo.env"

sudo -u "$MORADI_DEPLOY_USER" -H env \
  MORADI_NVM_DIR="$MORADI_NVM_DIR" \
  MORADI_NODE_VERSION="$MORADI_NODE_VERSION" \
  MORADI_EXTRACTED_DIR="$MORADI_EXTRACTED_DIR" \
  MORADI_LIVE_APP_DIR="$MORADI_LIVE_APP_DIR" \
  MORADI_PM2_APP="$MORADI_PM2_APP" \
  MORADI_HEALTH_URL="$MORADI_HEALTH_URL" \
  bash -c '
    set -Eeuo pipefail
    export NVM_DIR="$MORADI_NVM_DIR"
    source "$NVM_DIR/nvm.sh"
    nvm use "$MORADI_NODE_VERSION" >/dev/null

    bash "$MORADI_EXTRACTED_DIR/scripts/deploy-uploaded-release.sh" \
      "$MORADI_EXTRACTED_DIR" \
      "$MORADI_LIVE_APP_DIR" \
      "pm2:$MORADI_PM2_APP" \
      "$MORADI_HEALTH_URL"
  '
```

URL مربوط به Health Check باید متن خام URL باشد؛ عبارت Markdown مانند
`[http://...](http://...)` در Shell معتبر نیست.

## ۷. عملیات خودکار اسکریپت

اسکریپت عمومی این مراحل را انجام می‌دهد:

1. قفل‌کردن Deployment برای جلوگیری از اجرای هم‌زمان؛
2. کپی Source به Stage مستقل و حذف `.git`، `.env`، Build قبلی و `node_modules`؛
3. انتقال فایل‌های `.env*` از نسخه فعال به Stage؛
4. اجرای `npm ci --include=dev`؛
5. اجرای `npm run build` و کنترل وجود `dist/server/index.js`؛
6. ساخت Backup زمان‌دار کنار پوشه برنامه؛
7. جایگزینی فایل‌های نسخه فعال؛
8. Restart کردن PM2 یا systemd؛
9. شش تلاش Health Check؛
10. Rollback و Restart مجدد در صورت شکست.

خروجی موفق باید با این پیام تمام شود:

```text
Deployment completed successfully. Backup: <backup-directory>
```

## ۸. کنترل بعد از انتشار

```bash
curl -I "$MORADI_HEALTH_URL"
curl -I "$MORADI_PUBLIC_URL"

sudo -u "$MORADI_DEPLOY_USER" -H env \
  MORADI_NVM_DIR="$MORADI_NVM_DIR" \
  MORADI_NODE_VERSION="$MORADI_NODE_VERSION" \
  MORADI_PM2_APP="$MORADI_PM2_APP" \
  bash -c '
    export NVM_DIR="$MORADI_NVM_DIR"
    source "$NVM_DIR/nvm.sh"
    nvm use "$MORADI_NODE_VERSION" >/dev/null
    pm2 list
    pm2 logs "$MORADI_PM2_APP" --lines 30 --nostream
  '
```

پاسخ Local و Public باید موفق و Process در PM2 باید `online` باشد.

## ۹. خطاهای مشاهده‌شده و راه‌حل

### Zip معتبر نیست

نشانه:

```text
End-of-central-directory signature not found
```

راه‌حل: ساخت مجدد با `git archive`، اجرای `unzip -t` روی سیستم توسعه و مقایسه
SHA-256 پیش و پس از آپلود.

### اسکریپت پیدا نمی‌شود

نشانه:

```text
scripts/deploy-uploaded-release.sh: No such file or directory
```

علت معمول: Zip Extract نشده، Zip خراب است یا یک پوشه اضافی بالاتر از Root پروژه
ساخته شده است. وجود `package.json` و اسکریپت در Root پوشه Extractشده کنترل شود.

### PM2 Process پیدا نمی‌شود

علت معمول: فرمان با کاربر `root` اجرا شده ولی Process متعلق به کاربر Runtime است.
Deployment و فرمان‌های PM2 باید با مالک Process اجرا شوند.

### Permission denied در `node_modules`

مالکیت پوشه فعال و Stage باید متعلق به کاربر Runtime باشد. Build با `root` و
Restart با کاربر دیگر انجام نشود.

### سایت ظاهراً نسخه قدیمی است

1. Commit مبنای Zip با `origin/main` مقایسه شود؛
2. سلامت Build و PM2 بررسی شود؛
3. Local Health URL مستقیم کنترل شود؛
4. سپس Public URL و Cache مرورگر یا Proxy بررسی شود؛
5. وجود فایل‌ها یا محتوای شاخص نسخه جدید در پوشه فعال کنترل شود.

نکته: Git زمان دقیق Push را نگهداری نمی‌کند؛ `git log` زمان Commit را نمایش
می‌دهد. همچنین تغییراتی که فقط در Working Tree سیستم دیگری مانده باشند از Remote
قابل بازیابی نیستند.

## ۱۰. قواعد امنیتی

- Token، Password، Private Key و فایل `.env` هرگز وارد Zip یا Git نشوند.
- مشخصات عملیاتی غیرمحرمانه این سرور می‌توانند در Repository خصوصی ثبت شوند.
- Password، Token، Private Key، Certificate، Cookie و محتوای `.env` ثبت نشوند.
- Zip مستقیماً داخل پوشه فعال سایت Extract نشود.
- دستورهای حذف بازگشت‌ناپذیر برای Release یا Backup اجرا نشوند.
- Backup تا تأیید نسخه جدید نگهداری شود.
- Release همیشه به Commit مشخص و Hash قابل مقایسه متصل باشد.

## ۱۱. نقطه شروع روی سیستم دیگر

پس از Pull کردن `main`، این فایل‌ها به‌ترتیب خوانده شوند:

1. `docs/README.md`؛
2. جدیدترین Change Log تاریخ‌دار؛
3. همین Runbook؛
4. `scripts/server-profile.dralimoradi-demo.env`؛
5. `scripts/deploy-uploaded-release.sh`؛
6. سپس `npm test` اجرا شود.

این ترتیب منبع حقیقت مشترک برای ادامه توسعه و انتشار است.
