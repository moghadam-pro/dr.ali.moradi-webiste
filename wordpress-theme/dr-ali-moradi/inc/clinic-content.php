<?php
/**
 * Clinical Care hub copy (pathway cards, gallery-row labels) and the
 * generic-interior-page copy for the clinic-services/hospital-services
 * pathway sub-pages and the four patient-resources sub-pages.
 * Transcribed verbatim from the reference site's app/structured-content.ts
 * (same static-copy rule as the theme's other inc/*-content.php files).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dam_clinic_hub_copy( $locale = null ) {
	static $all = null;
	if ( null === $all ) {
		$all = array(
			'en' => array(
				'pathwaysKicker' => 'Care locations', 'pathwaysTitle' => 'Choose the right care setting', 'pathwaysIntro' => 'Explore outpatient clinic services and hospital-based surgery through two focused pathways.',
				'pathways' => array(
					array( 'slug' => 'clinic-services', 'title' => 'Clinic services', 'text' => 'Consultation, selected office procedures, follow-up, and early recovery guidance.' ),
					array( 'slug' => 'hospital-services', 'title' => 'Hospital services', 'text' => 'Major and subspecialty operations planned for an appropriately equipped hospital.' ),
				),
				'clinicGalleryTitle' => 'Clinic surgical cases', 'clinicGalleryIntro' => 'Selected outpatient and focused hand-surgery cases managed through the clinic pathway.',
				'hospitalGalleryTitle' => 'Hospital surgical cases', 'hospitalGalleryIntro' => 'Selected complex reconstructions and hospital-based upper-extremity operations.',
				'viewGallery' => 'View full gallery', 'previous' => 'Previous image', 'next' => 'Next image', 'close' => 'Close gallery', 'backToClinic' => 'Back to the main clinic page',
			),
			'fa' => array(
				'pathwaysKicker' => 'محل ارائه خدمات', 'pathwaysTitle' => 'مسیر مناسب مراقبت را انتخاب کنید', 'pathwaysIntro' => 'خدمات سرپایی کلینیک و جراحی‌های بیمارستانی در دو مسیر مستقل معرفی شده‌اند.',
				'pathways' => array(
					array( 'slug' => 'clinic-services', 'title' => 'خدمات کلینیک', 'text' => 'مشاوره، اقدامات منتخب مطب، پیگیری و راهنمای اولیه دوران بهبود.' ),
					array( 'slug' => 'hospital-services', 'title' => 'خدمات بیمارستان', 'text' => 'جراحی‌های بزرگ و فوق‌تخصصی در بیمارستان مجهز و متناسب با نیاز بیمار.' ),
				),
				'clinicGalleryTitle' => 'نمونه‌های جراحی کلینیک', 'clinicGalleryIntro' => 'منتخبی از اقدامات متمرکز و جراحی‌های دست در مسیر خدمات کلینیک.',
				'hospitalGalleryTitle' => 'نمونه‌های جراحی بیمارستان', 'hospitalGalleryIntro' => 'منتخبی از بازسازی‌های پیچیده و جراحی‌های اندام فوقانی در بیمارستان.',
				'viewGallery' => 'مشاهده گالری کامل', 'previous' => 'تصویر قبلی', 'next' => 'تصویر بعدی', 'close' => 'بستن گالری', 'backToClinic' => 'بازگشت به صفحه اصلی کلینیک',
			),
			'ar' => array(
				'pathwaysKicker' => 'مواقع الرعاية', 'pathwaysTitle' => 'اختر بيئة الرعاية المناسبة', 'pathwaysIntro' => 'تُعرض خدمات العيادة والجراحة في المستشفى ضمن مسارين واضحين.',
				'pathways' => array(
					array( 'slug' => 'clinic-services', 'title' => 'خدمات العيادة', 'text' => 'الاستشارة والإجراءات المختارة والمتابعة وإرشادات التعافي الأولية.' ),
					array( 'slug' => 'hospital-services', 'title' => 'خدمات المستشفى', 'text' => 'العمليات الكبرى والتخصصية في مستشفى مجهز يناسب احتياجات المريض.' ),
				),
				'clinicGalleryTitle' => 'نماذج جراحات العيادة', 'clinicGalleryIntro' => 'حالات مختارة من الإجراءات المركزة وجراحات اليد ضمن مسار العيادة.',
				'hospitalGalleryTitle' => 'نماذج جراحات المستشفى', 'hospitalGalleryIntro' => 'نماذج مختارة من الترميمات المعقدة وجراحات الطرف العلوي في المستشفى.',
				'viewGallery' => 'عرض المعرض الكامل', 'previous' => 'الصورة السابقة', 'next' => 'الصورة التالية', 'close' => 'إغلاق المعرض', 'backToClinic' => 'العودة إلى صفحة العيادة الرئيسية',
			),
		);
	}
	$locale = $locale ? $locale : dam_current_locale();
	return isset( $all[ $locale ] ) ? $all[ $locale ] : $all['en'];
}

/**
 * The clinic-services/hospital-services pathway sub-pages, keyed the
 * same shape as dam_interior_pages_copy()'s 'pages' entries so they can
 * be rendered by the same interior-body block.
 */
function dam_clinic_subpages_copy( $locale = null ) {
	static $all = null;
	if ( null === $all ) {
		$all = array(
			'en' => array(
				'clinic-services' => array(
					'kicker' => 'Clinic services', 'title' => 'Specialist care in the private office.', 'intro' => 'Assessment, selected outpatient procedures, follow-up, and recovery guidance in one focused pathway.',
					'sections' => array(
						array( 'title' => 'Consultation and assessment', 'text' => 'History, examination, imaging review, and a care pathway shaped around the individual condition.' ),
						array( 'title' => 'Selected office procedures', 'text' => 'Suitable minor procedures, injections, wound care, casting, and splinting may be provided when clinically appropriate.' ),
						array( 'title' => 'Follow-up and rehabilitation', 'text' => 'Recovery monitoring and early guidance on movement, protection, and rehabilitation support a safe return of function.' ),
					), 'ctaTitle' => 'Plan a specialist visit', 'ctaText' => 'Planned in-person appointments use the approved Nobat.ir pathway.',
				),
				'hospital-services' => array(
					'kicker' => 'Hospital services', 'title' => 'Major surgery in an equipped center.', 'intro' => 'Hospital care is selected when safety, anesthesia, equipment, or postoperative needs require specialist facilities.',
					'sections' => array(
						array( 'title' => 'Subspecialty surgery', 'text' => 'Complex reconstruction, microsurgery, and equipment-dependent operations are planned in a hospital environment.' ),
						array( 'title' => 'Selecting the care location', 'text' => 'The center is selected after assessment of the diagnosis, complexity, and safety requirements.' ),
						array( 'title' => 'Before-and-after coordination', 'text' => "Preparation, documentation, follow-up, and rehabilitation should follow the treating team's individual instructions." ),
					), 'ctaTitle' => 'Coordinate the surgical pathway', 'ctaText' => 'Clinical assessment determines the appropriate hospital setting.',
				),
				'before-surgery' => array(
					'kicker' => 'Patient resources', 'title' => 'Before-surgery guidance.', 'intro' => 'General preparation for the day of surgery; individual surgeon and hospital instructions always take priority.',
					'sections' => array(
						array( 'title' => 'Documents and information', 'text' => 'Prepare relevant imaging, reports, medication and allergy lists, and previous treatment information.' ),
						array( 'title' => 'Medication and fasting', 'text' => 'Do not stop medication on your own. Confirm fasting and medication changes only with the treating team.' ),
						array( 'title' => 'Planning the return home', 'text' => 'Arrange an escort, transport, home support, and expected limits on work or driving in advance.' ),
					), 'ctaTitle' => 'Confirm your individual instructions', 'ctaText' => 'Contact the treating team before surgery if any instruction is unclear.',
				),
				'after-surgery' => array(
					'kicker' => 'Patient resources', 'title' => 'After-surgery care.', 'intro' => "General protection and warning-sign guidance; the treating team's individual instructions come first.",
					'sections' => array(
						array( 'title' => 'Dressings and protection', 'text' => 'Keep dressings and splints dry and secure as instructed, and do not remove them without guidance.' ),
						array( 'title' => 'Pain, swelling, and movement', 'text' => 'Follow the prescribed medication, elevation, and permitted movement plan exactly.' ),
						array( 'title' => 'Warning signs', 'text' => 'Uncontrolled pain, color change, increasing numbness, bleeding, fever, or rapidly increasing swelling needs prompt assessment.' ),
					), 'ctaTitle' => 'Keep the follow-up plan', 'ctaText' => 'Confirm the visit, dressing, and rehabilitation schedule with the treating team.',
				),
				'faq' => array(
					'kicker' => 'Patient resources', 'title' => 'Frequently asked questions.', 'intro' => 'General answers for planning a visit and treatment.',
					'sections' => array(
						array( 'title' => 'How do I book?', 'text' => 'Planned visits are booked only through the approved Nobat.ir profile.' ),
						array( 'title' => 'What should I bring?', 'text' => 'Bring imaging, reports, medication lists, and information about previous operations or treatment.' ),
						array( 'title' => 'Are all procedures performed in the office?', 'text' => 'No. Suitable outpatient procedures use the clinic; major surgery uses an equipped hospital.' ),
						array( 'title' => 'What about an urgent injury?', 'text' => 'Life- or limb-threatening injuries require emergency services and must not wait for routine booking.' ),
					), 'ctaTitle' => 'Individual answers require assessment', 'ctaText' => 'This guidance does not replace examination or individual medical instructions.',
				),
				'rehabilitation' => array(
					'kicker' => 'Patient resources', 'title' => 'Rehabilitation guidance.', 'intro' => 'Movement should begin at the right time and range for the tissue being treated.',
					'sections' => array(
						array( 'title' => 'Protection and movement', 'text' => 'The balance between protection and motion depends on the injury, treatment, and healing stage.' ),
						array( 'title' => 'Hand therapy', 'text' => 'When needed, a hand therapist addresses motion, swelling, scar, strength, and return to activity.' ),
						array( 'title' => 'Home exercises', 'text' => 'Perform only prescribed exercises at the stated dose and range, and stop for an unusual increase in pain.' ),
					), 'ctaTitle' => 'Rehabilitation is individualized', 'ctaText' => 'The treating team sets the timing and progression of exercise.',
				),
			),
			'fa' => array(
				'clinic-services' => array(
					'kicker' => 'خدمات کلینیک', 'title' => 'مراقبت تخصصی در محیط مطب.', 'intro' => 'ارزیابی، اقدامات منتخب سرپایی، پیگیری و راهنمای دوران بهبود در یک مسیر متمرکز.',
					'sections' => array(
						array( 'title' => 'مشاوره و ارزیابی', 'text' => 'شرح حال، معاینه، بررسی تصاویر و تعریف مسیر درمان متناسب با شرایط هر بیمار.' ),
						array( 'title' => 'اقدامات منتخب مطب', 'text' => 'برخی اقدامات محدود، تزریق‌ها، مراقبت زخم، گچ و آتل در صورت مناسب‌بودن شرایط بالینی انجام می‌شوند.' ),
						array( 'title' => 'پیگیری و توان‌بخشی', 'text' => 'پایش روند بهبود و راهنمای اولیه حرکت، محافظت و زمان شروع توان‌بخشی بخشی از مسیر مراقبت است.' ),
					), 'ctaTitle' => 'برای ویزیت برنامه‌ریزی کنید', 'ctaText' => 'نوبت حضوری فقط از مسیر تأییدشده Nobat.ir ثبت می‌شود.',
				),
				'hospital-services' => array(
					'kicker' => 'خدمات بیمارستان', 'title' => 'جراحی‌های بزرگ در مرکز مجهز.', 'intro' => 'خدمات بیمارستانی زمانی انتخاب می‌شوند که ایمنی، بیهوشی، تجهیزات یا مراقبت پس از عمل به امکانات تخصصی نیاز داشته باشد.',
					'sections' => array(
						array( 'title' => 'جراحی‌های فوق‌تخصصی', 'text' => 'بازسازی‌های پیچیده، میکروسرجری و جراحی‌های وابسته به تجهیزات در محیط بیمارستانی برنامه‌ریزی می‌شوند.' ),
						array( 'title' => 'انتخاب محل درمان', 'text' => 'محل انجام جراحی پس از ارزیابی تشخیص، سطح پیچیدگی و نیازهای ایمنی مشخص می‌شود.' ),
						array( 'title' => 'هماهنگی قبل و بعد از عمل', 'text' => 'مدارک، آمادگی، پیگیری و توان‌بخشی باید طبق دستور اختصاصی تیم درمان انجام شوند.' ),
					), 'ctaTitle' => 'مسیر جراحی را با تیم درمان هماهنگ کنید', 'ctaText' => 'ارزیابی بالینی تعیین می‌کند کدام مرکز برای جراحی مناسب است.',
				),
				'before-surgery' => array(
					'kicker' => 'منابع بیمار', 'title' => 'راهنمای پیش از جراحی.', 'intro' => 'آمادگی عمومی برای روز عمل؛ دستور اختصاصی جراح و بیمارستان همیشه اولویت دارد.',
					'sections' => array(
						array( 'title' => 'مدارک و اطلاعات', 'text' => 'تصاویر، گزارش‌ها، فهرست داروها، حساسیت‌ها و سوابق درمانی مرتبط را آماده کنید.' ),
						array( 'title' => 'دارو و ناشتا بودن', 'text' => 'هیچ دارویی را خودسرانه قطع نکنید. زمان ناشتا بودن و تغییر داروها را فقط از تیم درمان بپرسید.' ),
						array( 'title' => 'برنامه بازگشت', 'text' => 'برای همراه، حمل‌ونقل، مراقبت در منزل و محدودیت کار یا رانندگی از قبل برنامه‌ریزی کنید.' ),
					), 'ctaTitle' => 'دستور اختصاصی خود را تأیید کنید', 'ctaText' => 'در صورت ابهام، پیش از روز عمل با تیم درمان تماس بگیرید.',
				),
				'after-surgery' => array(
					'kicker' => 'منابع بیمار', 'title' => 'مراقبت پس از جراحی.', 'intro' => 'راهنمای عمومی برای محافظت و تشخیص علائم هشدار؛ دستور اختصاصی تیم درمان مقدم است.',
					'sections' => array(
						array( 'title' => 'پانسمان و محافظت', 'text' => 'پانسمان و آتل را طبق دستور خشک و ثابت نگه دارید و بدون هماهنگی آن را باز نکنید.' ),
						array( 'title' => 'درد، تورم و حرکت', 'text' => 'داروها، بالا نگه‌داشتن اندام و تمرین‌های مجاز را دقیقاً طبق برنامه انجام دهید.' ),
						array( 'title' => 'علائم هشدار', 'text' => 'درد کنترل‌نشده، تغییر رنگ، بی‌حسی رو به افزایش، خونریزی، تب یا تورم سریع به ارزیابی فوری نیاز دارد.' ),
					), 'ctaTitle' => 'برنامه پیگیری را حفظ کنید', 'ctaText' => 'زمان ویزیت، تعویض پانسمان و شروع توان‌بخشی را با تیم درمان تأیید کنید.',
				),
				'faq' => array(
					'kicker' => 'منابع بیمار', 'title' => 'پرسش‌های متداول.', 'intro' => 'پاسخ‌های عمومی برای برنامه‌ریزی مراجعه و درمان.',
					'sections' => array(
						array( 'title' => 'چگونه نوبت بگیرم؟', 'text' => 'ویزیت برنامه‌ریزی‌شده فقط از پروفایل تأییدشده Nobat.ir ثبت می‌شود.' ),
						array( 'title' => 'چه مدارکی همراه داشته باشم؟', 'text' => 'تصاویر، گزارش‌ها، فهرست داروها و اطلاعات عمل یا درمان قبلی را همراه بیاورید.' ),
						array( 'title' => 'آیا همه اقدامات در مطب انجام می‌شوند؟', 'text' => 'خیر؛ اقدامات سرپایی مناسب در مطب و جراحی‌های بزرگ در بیمارستان مجهز انجام می‌شوند.' ),
						array( 'title' => 'آسیب فوری چه می‌شود؟', 'text' => 'موارد تهدیدکننده جان یا اندام باید فوراً به اورژانس مراجعه کنند و منتظر نوبت معمول نمانند.' ),
					), 'ctaTitle' => 'پاسخ اختصاصی نیازمند ارزیابی است', 'ctaText' => 'این راهنما جایگزین معاینه یا دستور پزشکی فردی نیست.',
				),
				'rehabilitation' => array(
					'kicker' => 'منابع بیمار', 'title' => 'راهنمای توان‌بخشی.', 'intro' => 'حرکت باید در زمان مناسب، با دامنه مجاز و متناسب با بافت ترمیم‌شده آغاز شود.',
					'sections' => array(
						array( 'title' => 'محافظت و حرکت', 'text' => 'تعادل میان محافظت و حرکت به نوع آسیب، روش درمان و مرحله ترمیم بستگی دارد.' ),
						array( 'title' => 'هندتراپی', 'text' => 'در صورت نیاز، درمانگر دست روی حرکت، ورم، اسکار، قدرت و بازگشت به فعالیت کار می‌کند.' ),
						array( 'title' => 'تمرین خانگی', 'text' => 'فقط تمرین‌های تجویزشده را با تعداد و دامنه مشخص انجام دهید و در صورت افزایش غیرعادی درد متوقف کنید.' ),
					), 'ctaTitle' => 'برنامه توان‌بخشی اختصاصی است', 'ctaText' => 'زمان شروع و پیشرفت تمرین را تیم درمان مشخص می‌کند.',
				),
			),
			'ar' => array(
				'clinic-services' => array(
					'kicker' => 'خدمات العيادة', 'title' => 'رعاية تخصصية في العيادة.', 'intro' => 'التقييم والإجراءات الخارجية المختارة والمتابعة وإرشادات التعافي ضمن مسار مركز.',
					'sections' => array(
						array( 'title' => 'الاستشارة والتقييم', 'text' => 'التاريخ والفحص ومراجعة الصور وتحديد مسار يناسب حالة كل مريض.' ),
						array( 'title' => 'إجراءات مختارة', 'text' => 'تُجرى بعض الإجراءات المحدودة والحقن والعناية بالجروح والجبائر عندما تكون مناسبة سريرياً.' ),
						array( 'title' => 'المتابعة والتأهيل', 'text' => 'تدعم متابعة التعافي وإرشادات الحركة والحماية وتوقيت التأهيل استعادة الوظيفة الآمنة.' ),
					), 'ctaTitle' => 'خطط لزيارتك', 'ctaText' => 'تُحجز الزيارة الحضورية فقط عبر مسار Nobat.ir المعتمد.',
				),
				'hospital-services' => array(
					'kicker' => 'خدمات المستشفى', 'title' => 'الجراحة الكبرى في مركز مجهز.', 'intro' => 'تُختار الرعاية في المستشفى عندما تتطلب السلامة أو التخدير أو التجهيزات أو المتابعة مرافق تخصصية.',
					'sections' => array(
						array( 'title' => 'الجراحة التخصصية', 'text' => 'تُخطط إعادة البناء المعقدة والجراحة المجهرية والعمليات المعتمدة على تجهيزات في بيئة المستشفى.' ),
						array( 'title' => 'اختيار مكان العلاج', 'text' => 'يُحدد المركز بعد تقييم التشخيص والتعقيد ومتطلبات السلامة.' ),
						array( 'title' => 'التنسيق قبل الجراحة وبعدها', 'text' => 'يجب اتباع تعليمات الفريق الفردية للاستعداد والمتابعة والتأهيل.' ),
					), 'ctaTitle' => 'نسق مسار الجراحة مع الفريق', 'ctaText' => 'يحدد التقييم السريري المركز المناسب للجراحة.',
				),
				'before-surgery' => array(
					'kicker' => 'موارد المريض', 'title' => 'دليل ما قبل الجراحة.', 'intro' => 'إعداد عام ليوم العملية؛ تبقى تعليمات الجراح والمستشفى الفردية هي الأولوية.',
					'sections' => array(
						array( 'title' => 'الوثائق والمعلومات', 'text' => 'جهز الصور والتقارير وقائمة الأدوية والحساسيات والتاريخ الطبي المرتبط.' ),
						array( 'title' => 'الأدوية والصيام', 'text' => 'لا توقف دواءً بنفسك؛ أكد الصيام وأي تعديل دوائي مع الفريق المعالج.' ),
						array( 'title' => 'خطة العودة', 'text' => 'رتب المرافق والنقل والرعاية المنزلية وحدود العمل أو القيادة مسبقاً.' ),
					), 'ctaTitle' => 'أكد تعليماتك الفردية', 'ctaText' => 'تواصل مع الفريق قبل يوم العملية عند وجود أي غموض.',
				),
				'after-surgery' => array(
					'kicker' => 'موارد المريض', 'title' => 'العناية بعد الجراحة.', 'intro' => 'إرشاد عام للحماية والتعرف إلى علامات التحذير؛ تعليمات الفريق الفردية أولاً.',
					'sections' => array(
						array( 'title' => 'الضماد والحماية', 'text' => 'حافظ على الضماد والجبيرة جافين وثابتين ولا تفتحهما دون توجيه.' ),
						array( 'title' => 'الألم والتورم والحركة', 'text' => 'اتبع الأدوية ورفع الطرف والتمارين المسموحة وفق الخطة المحددة.' ),
						array( 'title' => 'علامات التحذير', 'text' => 'الألم غير المضبوط أو تغير اللون أو الخدر المتزايد أو النزف أو الحمى أو التورم السريع يحتاج إلى تقييم عاجل.' ),
					), 'ctaTitle' => 'حافظ على خطة المتابعة', 'ctaText' => 'أكد موعد الزيارة وتغيير الضماد وبدء التأهيل مع الفريق.',
				),
				'faq' => array(
					'kicker' => 'موارد المريض', 'title' => 'الأسئلة الشائعة.', 'intro' => 'إجابات عامة لتخطيط الزيارة والعلاج.',
					'sections' => array(
						array( 'title' => 'كيف أحجز موعداً؟', 'text' => 'تُحجز الزيارة المخططة فقط عبر ملف Nobat.ir المعتمد.' ),
						array( 'title' => 'ماذا أحضر؟', 'text' => 'أحضر الصور والتقارير وقائمة الأدوية ومعلومات العمليات أو العلاجات السابقة.' ),
						array( 'title' => 'هل تُجرى كل الإجراءات في العيادة؟', 'text' => 'لا؛ الإجراءات الخارجية المناسبة في العيادة والجراحة الكبرى في مستشفى مجهز.' ),
						array( 'title' => 'ماذا عن الإصابة العاجلة؟', 'text' => 'الحالات المهددة للحياة أو الطرف تحتاج إلى الطوارئ فوراً ولا تنتظر موعداً روتينياً.' ),
					), 'ctaTitle' => 'الإجابة الفردية تحتاج إلى تقييم', 'ctaText' => 'هذا الدليل لا يستبدل الفحص أو التعليمات الطبية الفردية.',
				),
				'rehabilitation' => array(
					'kicker' => 'موارد المريض', 'title' => 'إرشادات التأهيل.', 'intro' => 'يبدأ التحريك في الوقت والمدى المناسبين بحسب النسيج المعالج.',
					'sections' => array(
						array( 'title' => 'الحماية والحركة', 'text' => 'يعتمد التوازن بين الحماية والحركة على الإصابة والعلاج ومرحلة الالتئام.' ),
						array( 'title' => 'علاج اليد', 'text' => 'عند الحاجة يعمل معالج اليد على الحركة والتورم والندبة والقوة والعودة إلى النشاط.' ),
						array( 'title' => 'التمرين المنزلي', 'text' => 'نفذ التمارين الموصوفة فقط وتوقف عند زيادة غير معتادة في الألم.' ),
					), 'ctaTitle' => 'خطة التأهيل فردية', 'ctaText' => 'يحدد الفريق توقيت بدء التمرين وتقدمه.',
				),
			),
		);
	}
	$locale = $locale ? $locale : dam_current_locale();
	return isset( $all[ $locale ] ) ? $all[ $locale ] : $all['en'];
}

/**
 * The Clinical Care hub's own body (pathway cards + team + two case
 * galleries) -- matches the reference's dedicated ClinicPage component,
 * used in place of the generic interior-body numbered-sections layout
 * that every other page sharing the page-hub template gets.
 */
function dam_render_clinical_care_body( $locale ) {
	$hub = dam_clinic_hub_copy( $locale );

	echo do_blocks( '<!-- wp:dr-ali-moradi/clinic-pathways /-->' );

	dam_render_team_section( 'clinic' );

	dam_render_gallery_row(
		'clinic',
		$hub['clinicGalleryTitle'],
		$hub['clinicGalleryIntro'],
		dam_localized_page_url( 'clinic-gallery', $locale )
	);
	dam_render_gallery_row(
		'hospital',
		$hub['hospitalGalleryTitle'],
		$hub['hospitalGalleryIntro'],
		dam_localized_page_url( 'hospital-gallery', $locale ),
		true
	);
}

/**
 * The 16-image clinic/hospital surgical-case galleries. Filenames match
 * the Media Library slugs uploaded for this (clinic-01..16,
 * hospital-01..16).
 */
function dam_gallery_images( $area ) {
	$count  = 16;
	$images = array();
	for ( $i = 1; $i <= $count; $i++ ) {
		$slug     = $area . '-' . str_pad( (string) $i, 2, '0', STR_PAD_LEFT );
		$images[] = dam_media_url( $slug );
	}
	return $images;
}
