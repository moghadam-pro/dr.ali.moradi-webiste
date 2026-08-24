# گزارش اصلاح کاورها و صفحه درباره من — 2026-08-25

## نتیجه

- کاورهای `Innovation` و `Education` با حفظ هویت اعضای واقعی تیم بازطراحی شدند.
- کاور `Research` با هویت واقعی دکتر علی مرادی، پشت میز و در حال نوشتن ساخته شد.
- کاور `About` از همان فضای اتاق عمل بازتر شد تا دکتر در کراپ کم‌ارتفاع کامل‌تر دیده شود.
- کاور آرشیو `Blog / News` با الهام مستقیم از گواهی‌ها و لوح‌های واقعی دکتر ساخته شد.
- اعداد بزرگ `.section-count` از خاکستری به نارنجی بسیار کم‌رنگ `rgba(247,148,29,.14)` تغییر کردند.
- صفحه `About` بدون سایدبار و با پنج بخش مستقل، ترکیب تصویر و متن، خط زمانی حرفه‌ای و نسخه‌های انگلیسی، فارسی و عربی بازطراحی شد.

## فایل‌های نهایی

- `public/media/pages/innovation-cover.jpg`
- `public/media/pages/education-cover.jpg`
- `public/media/pages/research-cover.jpg`
- `public/media/pages/about-cover.jpg`
- `public/media/pages/blog-cover.jpg`

همه تصاویر نهایی به JPEG بهینه، Progressive و حداکثر عرض 2200 پیکسل تبدیل شده‌اند.

## روش تولید

هر پنج تصویر با ابزار داخلی ImageGen و حالت‌های identity-preserve یا compositing تولید شدند. برای تصاویر تیمی، عکس‌های پرسنلی ارسالی مرجع مستقیم هویت بودند. جست‌وجوی آرشیو لوکال، Google Drive و رسانه‌های سایت قدیمی برای فایل‌های اصلی عکس پشت‌میز و میز افتخارات نتیجه‌ای نداشت؛ بنابراین این دو تصویر با اتکا به عکس واقعی دکتر و اسناد واقعی موجود بازسازی شدند و نباید به‌عنوان عکس مستند یک رخداد مشخص معرفی شوند.

## پرامپت‌های نهایی

### Innovation

```text
Use case: identity-preserve
Asset type: full-width website interior cover for the Innovation page
Primary request: Create a premium photorealistic medical-innovation laboratory scene using the exact identities from the three reference portraits.
Input images: Image 1 is Dr. Ali Moradi; Image 2 is Dr. Afsaneh Jahani; Image 3 is Dr. Alireza Akbarzadeh. Preserve each face, age, skin tone, and identity faithfully.
Scene/backdrop: contemporary orthopedic engineering laboratory with a refined bionic hand prototype, a compact external-fixator prototype, and subtle technical work surfaces.
Subject: Dr. Moradi and the two innovation collaborators are naturally reviewing a prototype together; credible professional body language, white coats or clean clinical-engineering attire.
Style/medium: editorial healthcare photography, realistic skin and fabric, polished but not stock-photo artificial.
Composition/framing: very wide cinematic banner, all three people positioned in the center-right half and shown at medium distance; generous calm negative space on the left for white page title; enough headroom and safe crop for a shallow 400px cover.
Lighting/mood: soft daylight, pale blue-gray clinical palette with restrained warm orange accents.
Constraints: identities must match the references; correct hands and anatomy; no text, no logos, no watermark, no surgical gore, no extra people, no duplicated people.
Avoid: close-up faces, cropped heads, exaggerated futuristic holograms, plastic skin, unreadable interface text.
```

### Education

```text
Use case: identity-preserve
Asset type: full-width website interior cover for the Education page
Primary request: Create a sophisticated photorealistic medical teaching scene using the exact identities from the three reference portraits.
Input images: Image 1 is Dr. Ali Moradi, the educator; Image 2 is Maedeh Sharafoddin; Image 3 is Dr. Naeemeh Kalali. Preserve each face, age, skin tone, and identity faithfully.
Scene/backdrop: bright contemporary medical teaching studio or skills laboratory with a hand-and-wrist anatomical model, a clean radiograph display without readable patient data, and a tablet or notebook.
Subject: Dr. Moradi is calmly explaining a hand-surgery concept while the two team members engage as research and education collaborators; natural, credible interaction.
Style/medium: premium editorial academic healthcare photography, realistic skin and fabric.
Composition/framing: extremely wide cinematic banner; people grouped in the center-right half at medium distance, not close-up; generous softly lit negative space on the left for page title; safe headroom and lower-body margin for a shallow 400px crop.
Lighting/mood: soft natural daylight, calm blue-gray and white palette with subtle warm orange detail.
Constraints: identities must match references; correct hands and anatomy; no text, no logos, no watermark, no surgical gore, no extra people, no duplicate faces.
Avoid: classroom stock-photo posing, exaggerated gestures, crowded lab, cropped heads, futuristic holograms, plastic skin.
```

### Research

```text
Use case: identity-preserve
Asset type: full-width website interior cover for the Research page
Primary request: Reconstruct a natural editorial photograph of Dr. Ali Moradi seated at his own professional office desk, thoughtfully writing research notes by hand.
Input images: Both images show Dr. Ali Moradi. Preserve his exact identity, facial structure, age, hair, skin tone, and natural appearance.
Scene/backdrop: refined but credible orthopedic academic office; desk with an open research notebook, a few journal pages, pen, subtle hand-and-wrist anatomical model, and softly blurred bookshelves.
Subject: Dr. Moradi wears a white coat over a light striped shirt, seated at the desk and actively writing, with his gaze toward the page rather than the camera.
Style/medium: photorealistic documentary/editorial medical photography, realistic hands and handwriting gesture, no staged stock-photo feeling.
Composition/framing: very wide panoramic banner; Dr. Moradi positioned in the right third and shown farther back from camera, including torso, forearms, desk, notebook, and surrounding office context; large calm negative space on the left for page title; safe crop for a shallow 400px cover.
Lighting/mood: soft window daylight, quiet scholarly atmosphere, pale blue-gray with warm wood details.
Constraints: preserve identity; anatomically correct hands; pen naturally held; no readable patient data; no text, no logos, no watermark, no extra people.
Avoid: close-up portrait, looking at camera, cropped hands, fake handwriting, awards invented on wall, futuristic holograms, plastic skin.
```

### About

```text
Use case: identity-preserve
Asset type: full-width website interior cover for the About page
Primary request: Recompose the supplied photograph as the same operating-room portrait, but place Dr. Ali Moradi noticeably farther from the camera so his full upper body, crossed forearms, surgical loupe, scrub top, cap, and more of the surrounding room remain visible in a shallow cover crop.
Input images: Image 1 is the edit target and identity reference. Preserve Dr. Moradi's exact face, age, expression, scrubs, surgical cap, loupe, pose, and the same bright operating-room environment.
Composition/framing: very wide panoramic banner, Dr. Moradi positioned in the right third at about 30–35% smaller scale than the source; add believable environmental space around him and generous calm negative space on the left for page copy; safe headroom and lower margin for a 400px-tall crop.
Lighting/mood: retain the soft pale-blue clinical lighting and clean professional atmosphere.
Constraints: change only framing, scale, and extended background; preserve identity and clothing; anatomically correct crossed arms and hands; no text, no logos, no watermark, no extra people.
Avoid: changing facial features, zooming in, cropping the cap or hands, adding equipment in front of him, artificial skin, dramatic cinematic lighting.
```

### Blog / News archive

```text
Use case: compositing
Asset type: full-width website interior cover for the Blog and News archive
Primary request: Create a photorealistic editorial still-life of Dr. Ali Moradi's professional awards, certificates, and recognition pieces carefully arranged across a refined office table, drawing visual authenticity from the supplied real certificate and award references.
Input images: Image 1 is a real Best Paper certificate; Image 2 is a real outdoor recognition photograph with framed plaques; Image 3 is a real presentation certificate. Use them as provenance and visual references, not as floating screens.
Scene/backdrop: warm contemporary academic office, walnut desk surface, a few framed certificates and dark-blue award plaques laid out naturally, one subtle metallic trophy, pen and closed research notebook.
Style/medium: premium documentary still-life photography, tactile paper, glass reflections, realistic frames and table surface.
Composition/framing: very wide panoramic banner viewed from a slightly elevated three-quarter angle; the certificates and plaques occupy the center-right and lower-right area; generous darker uncluttered negative space on the left for white page title; safe crop for a shallow 400px cover.
Lighting/mood: soft side window light, dignified and restrained, warm wood balanced with Dr. Moradi's blue visual system.
Constraints: no people, no invented readable claims, no fake signatures, no legible private identifiers, no watermark, no brand logos added; certificate details may be softly out of focus while still reading as authentic professional documents.
Avoid: trophy overload, luxury excess, confetti, obvious mockup look, floating certificates, incorrect prominent text.
```

## مبنای محتوای About

محتوا از آخرین خلاصه رزومه موجود در `docs/cv-content-summary.md` استخراج شده است. اطلاعات خصوصی، ایمیل‌ها، شماره‌های شخصی و اطلاعات معرف‌ها وارد صفحه نشده‌اند. آمارهای مقاله و اختراع نیز در متن درباره من به شکل عدد قطعی تکرار نشده‌اند تا فقط از داده ساختاریافته و تأییدشده نمایش داده شوند.
