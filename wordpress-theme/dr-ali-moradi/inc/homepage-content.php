<?php
/**
 * Translated copy for the header, footer, and homepage sections that the
 * visual redesign needs verbatim from the reference site
 * (https://dralimoradi.moghadam.pro/), transcribed directly from that
 * site's own source (app/site-content.ts) rather than re-authored, so the
 * English/Persian/Arabic wording matches exactly. This mirrors how the
 * reference site itself keeps this copy as static, locale-keyed data
 * rather than pulling it from a CMS -- these are theme-authored UI/marketing
 * strings, not editable "content" in the Theme Options sense.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dam_current_locale() {
	if ( function_exists( 'pll_current_language' ) ) {
		$lang = pll_current_language();
		if ( $lang ) {
			return $lang;
		}
	}
	return 'en';
}

function dam_site_copy( $locale = null ) {
	static $all = null;
	if ( null === $all ) {
		$all = array(
			'en' => array(
				'skip' => 'Skip to content',
				'nav' => array( 'Clinic', 'Innovation', 'Research', 'Education', 'About me', 'Blog' ),
				'appointment' => 'Make an appointment',
				'chooseLanguage' => 'Choose language',
				'menuToggle' => 'Toggle menu',
				'heroName' => array( 'Dr.', 'Ali Moradi' ),
				'heroCredentials' => array(
					array( 'Hand Surgeon', '(Harvard University)' ),
					array( 'Ph.D of', 'Artificial Limbs' ),
					array( 'Recipient of the 2026 Alborz Award', '(Iran’s Nobel Prize)' ),
				),
				'heroDescription' => 'Dr. Ali Moradi is an Associate Professor of Orthopedics at Mashhad University of Medical Sciences and a hand and upper-extremity surgeon whose clinical practice is connected to research, medical engineering, innovation, and education.',
				'heroQuote' => 'Where Surgery Goes Beyond Protocols',
				'credentials' => array( 'Hand & upper-extremity surgeon', 'Associate Professor of Orthopedics', 'PhD in Orthotics & Prosthetics' ),
				'facets' => array( array( 'Hand & wrist', 'Expertise' ), array( 'Advanced', 'Technology' ), array( 'Innovation', 'Entrepreneurship' ), array( 'Research', 'Leadership' ) ),
				'pathwaysIndex' => 'PATHWAYS',
				'connectedIndex' => 'CONNECTED PRACTICE',
				'innovationIndex' => 'INNOVATION',
				'impactIndex' => 'IMPACT',
				'appointmentsIndex' => 'APPOINTMENTS',
				'recognitionIndex' => 'RECOGNITION',
				'aboutIndex' => 'ABOUT',
				'storyBody' => 'A connected journey from clinical observation to practical impact.',
				'journeyLink' => 'Explore the complete journey',
				'journey' => array( array( 'From', 'Injury' ), array( 'To', 'Innovation' ), array( 'To', 'Application' ), array( 'Back to', 'Life' ) ),
				'journeyStepNumbers' => array( '01', '02', '03', '04' ),
				'pathsTitle' => 'Choose the path that fits your visit',
				'pathsBody' => 'One practice, three connected fields — designed for patients, researchers, and innovators.',
				'pathCards' => array(
					array( 'Clinical Care', 'Explore specialist assessment, diagnosis, surgical and non-surgical treatment, follow-up, and patient resources for hand, wrist, nerve, tendon, fracture, and upper-extremity conditions across office and hospital care pathways in Mashhad with coordinated rehabilitation guidance.' ),
					array( 'Innovation', 'Discover clinically driven devices and engineering systems developed from real surgical challenges, including external fixation, magnetic distraction, bionic-hand control, biomechanics, prototyping, research validation, and pathways toward practical medical application and responsible technology transfer.' ),
					array( 'Research', 'Explore peer-reviewed clinical and engineering research across hand and wrist surgery, biomechanics, outcomes, prosthetic control, rehabilitation robotics, publications, patents, academic collaboration, and evidence that connects laboratory investigation with everyday patient care.' ),
				),
				'pathCtas' => array( 'Explore Clinical Care', 'Explore Innovation', 'Explore Research' ),
				'pathSlugs' => array( 'clinical-care', 'innovations', 'research' ),
				'innovationTitle' => 'Selected innovation stories',
				'innovationIntro' => 'Clinical problems become research questions; selected questions become practical systems.',
				'innovations' => array(
					array( 'Biomechanics', 'External fixation systems', 'Fixation concepts informed by fracture biomechanics, surgical precision, and clinical workflow.' ),
					array( 'Human–machine interface', 'Bionic hand control', 'Magnetic sensing and control research for more intuitive prosthetic-hand function.' ),
					array( 'Regenerative mechanics', 'Magnetic distraction', 'A research-led approach to controlled joint distraction and tissue preservation.' ),
				),
				'readStory' => 'Read the story',
				'impactTitle' => 'Evidence, not decoration',
				'figuresNote' => 'Current figures from the latest available CV',
				'metrics' => array( array( '150+', 'Peer-reviewed articles' ), array( '04', 'Books' ), array( '30', 'National patents' ), array( '08', 'International patent families' ) ),
				'appointmentTitle' => 'Find the right appointment pathway',
				'appointmentBody' => 'Scheduled visits are booked securely through the approved external service.',
				'appointmentCards' => array(
					array( 'Planned visit', 'Clinic appointment (in-person visit)', 'For non-urgent specialist consultation at the private office. Scheduled visits are reserved securely through the approved Nobat.ir service.' ),
					array( 'Priority follow-up', 'Virtual appointment (online consultation)', 'For patients outside Mashhad or cases that first require review of medical history, images, reports, and related documents.' ),
					array( 'Urgent in-person', 'Emergency visit', 'For acute hand or upper-extremity injuries requiring prompt specialist triage and review at the private office.' ),
					array( 'Initial assessment', 'GP appointment (general practitioner screening)', 'An initial screening pathway for cases that may first need general assessment before referral to the appropriate specialist route.' ),
				),
				'urgentDays' => 'Saturday, Monday & Wednesday',
				'urgentHours' => '15:45–18:30',
				'urgentInstruction' => 'Attend the private office for initial urgent triage during these hours.',
				'continueNobat' => 'Continue to Nobat.ir',
				'opensBooking' => 'Opens the approved external booking service.',
				'onlineNote' => 'Detailed online-consultation instructions will be confirmed separately.',
				'screeningNote' => 'Detailed GP screening instructions will be confirmed separately.',
				'medicalNote' => 'Life- or limb-threatening emergencies require immediate emergency medical services. This website does not provide diagnosis or emergency response.',
				'awardsKicker' => 'RECOGNITION',
				'awardsTitle' => 'Awards and certificates',
				'awardsIntro' => 'Selected awards, certificates, and professional recognition from Dr. Moradi’s clinical, academic, and innovation work.',
				'allUpdates' => 'All updates',
				'aboutBody' => 'Dr. Ali Moradi provides specialist care for hand, wrist, and upper-extremity conditions through a practice that connects clinical judgment with research and medical engineering. His work spans complex reconstruction, microsurgery, fracture and tendon care, prosthetic interfaces, innovation, and rehabilitation technologies. By combining evidence with practical experience, he develops more precise treatment pathways, teaches the next generation of clinicians, and translates difficult clinical questions into useful tools for patients and care teams.',
				'meetDoctor' => 'Meet Dr. Moradi',
				'researchProfile' => 'Research profile',
				'continueNobatShort' => 'Continue to Nobat.ir',
				'footer' => array(
					'bio' => 'Dr. Ali Moradi is an Associate Professor of Orthopedics at Mashhad University of Medical Sciences and a hand and upper-extremity surgeon whose clinical practice is connected to research, medical engineering, innovation, and education.',
					'explore' => 'Explore', 'resources' => 'Patient resources', 'contact' => 'Contact', 'social' => 'Social media',
					'before' => 'Before surgery', 'after' => 'After surgery', 'faq' => 'Frequently asked questions', 'rehab' => 'Rehabilitation guidance',
					'map' => 'Open office map', 'booking' => 'Approved external booking',
					'disclaimer' => 'Medical information is educational and does not replace professional assessment.',
					'copyright' => '© 2026 Dr. Ali Moradi', 'credit' => 'Designed and developed by Moghadam.pro',
				),
				'footerExplore' => array(
					'clinical-care' => 'Clinical care', 'innovations' => 'Innovation', 'research' => 'Research', 'education' => 'Education', 'about' => 'About Dr. Moradi', 'blog' => 'News & insights',
				),
				'contact' => array(
					'office' => 'East Golestan 6, opposite Arya Hospital parking, No. 17, Poursina Building, 3rd floor, Mashhad',
					'clinic' => 'Imam Reza Hospital Special Clinic, Imam Reza Square, Mashhad',
				),
			),
			'fa' => array(
				'skip' => 'رفتن به محتوای اصلی',
				'nav' => array( 'کلینیک', 'نوآوری', 'پژوهش', 'آموزش', 'درباره من', 'وبلاگ' ),
				'appointment' => 'دریافت نوبت',
				'chooseLanguage' => 'انتخاب زبان',
				'menuToggle' => 'باز و بسته کردن منو',
				'heroName' => array( 'دکتر', 'علی مرادی' ),
				'heroCredentials' => array(
					array( 'جراح دست', '(دانشگاه هاروارد)' ),
					array( 'دکتری تخصصی', 'اندام‌های مصنوعی' ),
					array( 'برگزیده جایزه البرز ۱۴۰۵', '(نوبل ایران)' ),
				),
				'heroDescription' => 'دکتر علی مرادی، دانشیار ارتوپدی دانشگاه علوم پزشکی مشهد و جراح دست و اندام فوقانی است. فعالیت بالینی او با پژوهش، مهندسی پزشکی، نوآوری و آموزش تخصصی پیوند دارد.',
				'heroQuote' => 'جایی که جراحی فراتر از پروتکل‌ها می‌رود',
				'credentials' => array( 'جراح دست و اندام فوقانی', 'دانشیار ارتوپدی', 'دکتری تخصصی ارتز و پروتز' ),
				'facets' => array( array( 'تخصص', 'دست و مچ' ), array( 'فناوری', 'پیشرفته' ), array( 'نوآوری', 'کارآفرینی' ), array( 'پژوهش', 'رهبری علمی' ) ),
				'pathwaysIndex' => 'مسیرها',
				'connectedIndex' => 'فعالیت یکپارچه',
				'innovationIndex' => 'نوآوری',
				'impactIndex' => 'دستاوردها',
				'appointmentsIndex' => 'نوبت‌دهی',
				'recognitionIndex' => 'افتخارات',
				'aboutIndex' => 'درباره',
				'storyBody' => 'مسیر پیوسته‌ای از مشاهده بالینی تا اثری عملی در زندگی بیمار.',
				'journeyLink' => 'مشاهده مسیر کامل',
				'journey' => array( array( 'از', 'آسیب' ), array( 'به', 'نوآوری' ), array( 'به', 'کاربرد' ), array( 'بازگشت به', 'زندگی' ) ),
				'journeyStepNumbers' => array( '۰۱', '۰۲', '۰۳', '۰۴' ),
				'pathsTitle' => 'مسیر مناسب خود را انتخاب کنید',
				'pathsBody' => 'سه حوزه به‌هم‌پیوسته برای بیماران، پژوهشگران و نوآوران.',
				'pathCards' => array(
					array( 'خدمات درمانی', 'ارزیابی تخصصی، تشخیص، درمان جراحی و غیرجراحی، پیگیری و منابع بیماران برای بیماری‌ها و آسیب‌های دست، مچ، عصب، تاندون، شکستگی‌ها و اندام فوقانی در مسیرهای درمانی مطب و بیمارستان همراه با راهنمایی توان‌بخشی.' ),
					array( 'نوآوری', 'آشنایی با ابزارها و سامانه‌های مهندسی برآمده از مسائل واقعی جراحی؛ از فیکساتور خارجی و دیستراکشن مغناطیسی تا کنترل دست بیونیک، بیومکانیک، نمونه‌سازی، اعتبارسنجی پژوهشی و مسیر انتقال مسئولانه فناوری پزشکی.' ),
					array( 'پژوهش', 'مرور پژوهش‌های بالینی و مهندسی داوری‌شده در جراحی دست و مچ، بیومکانیک، پیامدهای درمان، کنترل پروتز، رباتیک توان‌بخشی، مقالات، ثبت اختراع، همکاری دانشگاهی و شواهد پیونددهنده آزمایشگاه با مراقبت روزمره بیمار.' ),
				),
				'pathCtas' => array( 'مشاهده خدمات درمانی', 'مشاهده نوآوری', 'مشاهده پژوهش' ),
				'pathSlugs' => array( 'clinical-care', 'innovations', 'research' ),
				'innovationTitle' => 'روایت‌های منتخب نوآوری',
				'innovationIntro' => 'مسائل بالینی به پرسش پژوهشی و برخی پرسش‌ها به سامانه‌های کاربردی تبدیل می‌شوند.',
				'innovations' => array(
					array( 'بیومکانیک', 'سامانه‌های فیکساتور خارجی', 'ایده‌های تثبیت بر پایه بیومکانیک شکستگی، دقت جراحی و روند واقعی درمان.' ),
					array( 'رابط انسان و ماشین', 'کنترل دست بیونیک', 'پژوهش حسگرهای مغناطیسی برای کنترل طبیعی‌تر پروتز دست.' ),
					array( 'مکانیک بازساختی', 'دیستراکشن مغناطیسی', 'رویکردی پژوهش‌محور برای کشش کنترل‌شده مفصل و حفظ بافت.' ),
				),
				'readStory' => 'مطالعه داستان',
				'impactTitle' => 'دستاوردهای مستند',
				'figuresNote' => 'آخرین اعداد موجود در رزومه',
				'metrics' => array( array( '+۱۵۰', 'مقاله علمی داوری‌شده' ), array( '۰۴', 'کتاب' ), array( '۳۰', 'ثبت اختراع داخلی' ), array( '۰۸', 'خانواده ثبت اختراع بین‌المللی' ) ),
				'appointmentTitle' => 'مسیر مناسب نوبت را انتخاب کنید',
				'appointmentBody' => 'نوبت‌های برنامه‌ریزی‌شده از طریق سامانه خارجی تأییدشده ثبت می‌شوند.',
				'appointmentCards' => array(
					array( 'ویزیت برنامه‌ریزی‌شده', 'نوبت کلینیک (ویزیت حضوری)', 'برای مشاوره تخصصی غیراورژانسی در مطب. نوبت‌های برنامه‌ریزی‌شده از طریق سامانه تأییدشده Nobat.ir ثبت می‌شوند.' ),
					array( 'پیگیری اولویت‌دار', 'نوبت مجازی (مشاوره آنلاین)', 'برای بیماران خارج از مشهد یا پرونده‌هایی که ابتدا به بررسی شرح حال، تصاویر، گزارش‌ها و مدارک پزشکی نیاز دارند.' ),
					array( 'مراجعه فوری حضوری', 'ویزیت اورژانسی', 'برای آسیب حاد دست یا اندام فوقانی که به تریاژ و بررسی سریع متخصص در مطب نیاز دارد.' ),
					array( 'ارزیابی اولیه', 'نوبت پزشک عمومی (غربالگری اولیه)', 'مسیر ارزیابی اولیه برای پرونده‌هایی که ممکن است پیش از ارجاع به مسیر تخصصی مناسب، به بررسی عمومی نیاز داشته باشند.' ),
				),
				'urgentDays' => 'شنبه، دوشنبه و چهارشنبه',
				'urgentHours' => '۱۵:۴۵ تا ۱۸:۳۰',
				'urgentInstruction' => 'در این ساعات برای ارزیابی اولیه اورژانسی به مطب مراجعه کنید.',
				'continueNobat' => 'ادامه در Nobat.ir',
				'opensBooking' => 'سامانه تأییدشده نوبت‌دهی در پنجره جدید باز می‌شود.',
				'onlineNote' => 'جزئیات نهایی مشاوره آنلاین جداگانه تأیید خواهد شد.',
				'screeningNote' => 'جزئیات نهایی غربالگری پزشک عمومی جداگانه تأیید خواهد شد.',
				'medicalNote' => 'در شرایط تهدیدکننده جان یا اندام، فوراً به اورژانس مراجعه کنید. این وب‌سایت تشخیص یا پاسخ اورژانسی ارائه نمی‌کند.',
				'awardsKicker' => 'افتخارات',
				'awardsTitle' => 'جوایز و گواهی‌ها',
				'awardsIntro' => 'منتخبی از جوایز، گواهی‌ها و تقدیرهای حرفه‌ای دکتر مرادی در فعالیت‌های بالینی، دانشگاهی و نوآوری.',
				'allUpdates' => 'همه تازه‌ها',
				'aboutBody' => 'دکتر علی مرادی مراقبت تخصصی بیماری‌ها و آسیب‌های دست، مچ و اندام فوقانی را در مسیری ارائه می‌کند که قضاوت بالینی را به پژوهش و مهندسی پزشکی پیوند می‌دهد. حوزه فعالیت او از بازسازی‌های پیچیده، میکروسرجری و درمان شکستگی و تاندون تا رابط‌های پروتز، نوآوری و فناوری‌های توان‌بخشی امتداد دارد. او با ترکیب شواهد علمی و تجربه عملی، مسیرهای درمانی دقیق‌تری طراحی می‌کند، به آموزش نسل بعدی پزشکان می‌پردازد و پرسش‌های دشوار بالینی را به ابزارهای کاربردی برای بیماران و تیم‌های درمان تبدیل می‌کند.',
				'meetDoctor' => 'آشنایی با دکتر مرادی',
				'researchProfile' => 'پروفایل پژوهشی',
				'footer' => array(
					'bio' => 'دکتر علی مرادی، دانشیار ارتوپدی دانشگاه علوم پزشکی مشهد و جراح دست و اندام فوقانی است. فعالیت بالینی او با پژوهش، مهندسی پزشکی، نوآوری و آموزش تخصصی پیوند دارد.',
					'explore' => 'دسترسی سریع', 'resources' => 'منابع بیماران', 'contact' => 'ارتباط', 'social' => 'شبکه‌های اجتماعی',
					'before' => 'راهنمای پیش از عمل', 'after' => 'مراقبت پس از عمل', 'faq' => 'پرسش‌های رایج', 'rehab' => 'راهنمای توان‌بخشی',
					'map' => 'مشاهده نقشه مطب', 'booking' => 'سامانه تأییدشده نوبت',
					'disclaimer' => 'اطلاعات پزشکی سایت آموزشی است و جایگزین ارزیابی پزشک نیست.',
					'copyright' => '© ۲۰۲۶ دکتر علی مرادی', 'credit' => 'طراحی و توسعه توسط Moghadam.pro',
				),
				'footerExplore' => array(
					'clinical-care' => 'خدمات درمانی', 'innovations' => 'نوآوری', 'research' => 'پژوهش', 'education' => 'آموزش', 'about' => 'درباره دکتر مرادی', 'blog' => 'وبلاگ',
				),
				'contact' => array(
					'office' => 'مشهد، گلستان شرقی ۶، روبه‌روی پارکینگ بیمارستان آریا، پلاک ۱۷، ساختمان پورسینا، طبقه ۳',
					'clinic' => 'مشهد، بیمارستان امام رضا، کلینیک ویژه',
				),
			),
			'ar' => array(
				'skip' => 'الانتقال إلى المحتوى',
				'nav' => array( 'العيادة', 'الابتكار', 'البحث', 'التعليم', 'عني', 'المدونة' ),
				'appointment' => 'حجز موعد',
				'chooseLanguage' => 'اختيار اللغة',
				'menuToggle' => 'فتح أو إغلاق القائمة',
				'heroName' => array( 'الدكتور', 'علي مرادي' ),
				'heroCredentials' => array(
					array( 'جراح اليد', '(جامعة هارفارد)' ),
					array( 'دكتوراه في', 'الأطراف الصناعية' ),
					array( 'الحاصل على جائزة البرز لعام ٢٠٢٦', '(نوبل إيران)' ),
				),
				'heroDescription' => 'الدكتور علي مرادي أستاذ مشارك في جراحة العظام بجامعة مشهد للعلوم الطبية وجراح لليد والطرف العلوي. ترتبط ممارسته السريرية بالبحث والهندسة الطبية والابتكار والتعليم المتخصص.',
				'heroQuote' => 'حيث تتجاوز الجراحة البروتوكولات',
				'credentials' => array( 'جراح اليد والطرف العلوي', 'أستاذ مشارك في جراحة العظام', 'دكتوراه في تقويم الأطراف والأجهزة التعويضية' ),
				'facets' => array( array( 'خبرة', 'اليد والمعصم' ), array( 'تقنية', 'متقدمة' ), array( 'ابتكار', 'وريادة أعمال' ), array( 'بحث', 'وقيادة علمية' ) ),
				'pathwaysIndex' => 'المسارات',
				'connectedIndex' => 'ممارسة مترابطة',
				'innovationIndex' => 'الابتكار',
				'impactIndex' => 'الأثر',
				'appointmentsIndex' => 'المواعيد',
				'recognitionIndex' => 'التقدير',
				'aboutIndex' => 'نبذة',
				'storyBody' => 'رحلة مترابطة من الملاحظة السريرية إلى أثر عملي في حياة المريض.',
				'journeyLink' => 'استكشف الرحلة الكاملة',
				'journey' => array( array( 'من', 'الإصابة' ), array( 'إلى', 'الابتكار' ), array( 'إلى', 'التطبيق' ), array( 'العودة إلى', 'الحياة' ) ),
				'journeyStepNumbers' => array( '٠١', '٠٢', '٠٣', '٠٤' ),
				'pathsTitle' => 'اختر المسار المناسب لزيارتك',
				'pathsBody' => 'ثلاثة مجالات مترابطة للمرضى والباحثين والمبتكرين.',
				'pathCards' => array(
					array( 'الرعاية السريرية', 'استكشف التقييم المتخصص والتشخيص والعلاج الجراحي وغير الجراحي والمتابعة وموارد المرضى لحالات اليد والمعصم والأعصاب والأوتار والكسور والطرف العلوي ضمن مسارات رعاية العيادة والمستشفى في مشهد مع إرشاد تأهيلي منسق.' ),
					array( 'الابتكار', 'تعرّف إلى الأجهزة والأنظمة الهندسية المنطلقة من تحديات جراحية حقيقية، بما يشمل التثبيت الخارجي والإلهاء المغناطيسي والتحكم باليد الإلكترونية والميكانيكا الحيوية والنمذجة والتحقق البحثي ومسارات التطبيق الطبي ونقل التقنية المسؤول.' ),
					array( 'البحث', 'استكشف الأبحاث السريرية والهندسية المحكمة في جراحة اليد والمعصم والميكانيكا الحيوية والنتائج والتحكم بالأطراف التعويضية وروبوتات التأهيل والمنشورات وبراءات الاختراع والتعاون الأكاديمي والأدلة التي تصل التحقيق المخبري بالرعاية اليومية للمريض.' ),
				),
				'pathCtas' => array( 'استكشف الرعاية السريرية', 'استكشف الابتكار', 'استكشف البحث' ),
				'pathSlugs' => array( 'clinical-care', 'innovations', 'research' ),
				'innovationTitle' => 'قصص ابتكار مختارة',
				'innovationIntro' => 'تتحول المشكلات السريرية إلى أسئلة بحثية، وتتحول بعض الأسئلة إلى أنظمة عملية.',
				'innovations' => array(
					array( 'الميكانيكا الحيوية', 'أنظمة التثبيت الخارجي', 'مفاهيم تثبيت تستند إلى ميكانيكا الكسر والدقة الجراحية وسير العمل السريري.' ),
					array( 'واجهة الإنسان والآلة', 'التحكم باليد الإلكترونية', 'أبحاث الاستشعار المغناطيسي لتحكم أكثر طبيعية في اليد التعويضية.' ),
					array( 'الميكانيكا التجديدية', 'الإلهاء المغناطيسي', 'نهج بحثي للإلهاء المفصلي المضبوط والحفاظ على الأنسجة.' ),
				),
				'readStory' => 'اقرأ القصة',
				'impactTitle' => 'إنجازات موثقة',
				'figuresNote' => 'أحدث أرقام متاحة في السيرة الذاتية',
				'metrics' => array( array( '+١٥٠', 'مقالة محكّمة' ), array( '٠٤', 'كتب' ), array( '٣٠', 'براءة وطنية' ), array( '٠٨', 'عائلات براءات دولية' ) ),
				'appointmentTitle' => 'اختر مسار الموعد المناسب',
				'appointmentBody' => 'تُحجز الزيارات المخططة بأمان عبر الخدمة الخارجية المعتمدة.',
				'appointmentCards' => array(
					array( 'زيارة مخططة', 'موعد العيادة (زيارة حضورية)', 'لاستشارة اختصاصية غير طارئة في العيادة الخاصة. تُحجز الزيارات المخططة عبر خدمة Nobat.ir المعتمدة.' ),
					array( 'متابعة ذات أولوية', 'موعد افتراضي (استشارة عبر الإنترنت)', 'للمرضى خارج مشهد أو للملفات التي تحتاج أولاً إلى مراجعة التاريخ الطبي والصور والتقارير والوثائق ذات الصلة.' ),
					array( 'حضور عاجل', 'زيارة طارئة', 'لإصابات اليد أو الطرف العلوي الحادة التي تحتاج إلى فرز وتقييم اختصاصي سريع في العيادة الخاصة.' ),
					array( 'تقييم أولي', 'موعد طبيب عام (فحص أولي)', 'مسار فحص أولي للحالات التي قد تحتاج إلى تقييم عام قبل إحالتها إلى المسار الاختصاصي المناسب.' ),
				),
				'urgentDays' => 'السبت والاثنين والأربعاء',
				'urgentHours' => '١٥:٤٥–١٨:٣٠',
				'urgentInstruction' => 'راجع العيادة الخاصة خلال هذه الساعات للتقييم العاجل الأولي.',
				'continueNobat' => 'المتابعة عبر Nobat.ir',
				'opensBooking' => 'تُفتح خدمة الحجز الخارجية المعتمدة.',
				'onlineNote' => 'ستُعتمد تعليمات الاستشارة عبر الإنترنت بصورة منفصلة.',
				'screeningNote' => 'ستُعتمد تعليمات الفحص الأولي لدى الطبيب العام بصورة منفصلة.',
				'medicalNote' => 'تتطلب الحالات المهددة للحياة أو الطرف خدمات الطوارئ فوراً. لا يقدم هذا الموقع تشخيصاً أو استجابة طارئة.',
				'awardsKicker' => 'التقدير',
				'awardsTitle' => 'الجوائز والشهادات',
				'awardsIntro' => 'مجموعة مختارة من جوائز الدكتور مرادي وشهاداته والتقدير المهني لأعماله السريرية والأكاديمية والابتكارية.',
				'allUpdates' => 'كل التحديثات',
				'aboutBody' => 'يقدم الدكتور علي مرادي رعاية متخصصة لحالات اليد والمعصم والطرف العلوي ضمن ممارسة تربط الحكم السريري بالبحث والهندسة الطبية. تمتد أعماله من إعادة البناء المعقدة والجراحة المجهرية وعلاج الكسور والأوتار إلى واجهات الأطراف التعويضية والابتكار وتقنيات التأهيل. ومن خلال الجمع بين الدليل العلمي والخبرة العملية، يطور مسارات علاج أكثر دقة، ويسهم في تعليم الجيل القادم من الأطباء، ويحوّل الأسئلة السريرية الصعبة إلى أدوات مفيدة للمرضى وفرق الرعاية.',
				'meetDoctor' => 'تعرّف إلى الدكتور مرادي',
				'researchProfile' => 'الملف البحثي',
				'footer' => array(
					'bio' => 'الدكتور علي مرادي أستاذ مشارك في جراحة العظام بجامعة مشهد للعلوم الطبية وجراح لليد والطرف العلوي. ترتبط ممارسته السريرية بالبحث والهندسة الطبية والابتكار والتعليم المتخصص.',
					'explore' => 'استكشف', 'resources' => 'موارد المرضى', 'contact' => 'الاتصال', 'social' => 'وسائل التواصل',
					'before' => 'قبل الجراحة', 'after' => 'بعد الجراحة', 'faq' => 'الأسئلة الشائعة', 'rehab' => 'إرشادات التأهيل',
					'map' => 'افتح خريطة العيادة', 'booking' => 'الحجز الخارجي المعتمد',
					'disclaimer' => 'المعلومات الطبية تعليمية ولا تستبدل التقييم المهني.',
					'copyright' => '© ٢٠٢٦ الدكتور علي مرادي', 'credit' => 'التصميم والتطوير بواسطة Moghadam.pro',
				),
				'footerExplore' => array(
					'clinical-care' => 'الرعاية السريرية', 'innovations' => 'الابتكار', 'research' => 'البحث', 'education' => 'التعليم', 'about' => 'عن الدكتور مرادي', 'blog' => 'المدونة',
				),
				'contact' => array(
					'office' => 'مشهد، شارع كلستان الشرقي ٦، مقابل موقف مستشفى آريا، رقم ١٧، مبنى بورسينا، الطابق الثالث',
					'clinic' => 'العيادة الخاصة في مستشفى الإمام رضا، ساحة الإمام رضا، مشهد',
				),
			),
		);
	}

	$locale = $locale ? $locale : dam_current_locale();
	return isset( $all[ $locale ] ) ? $all[ $locale ] : $all['en'];
}

/**
 * Looks up a Media Library attachment by its slug and returns its URL.
 * Used for the redesign's decorative homepage photography, which is
 * uploaded through Media Library (see progress-log.md) rather than
 * bundled in the theme zip, since the zip upload endpoint on this host
 * has a much lower request-size ceiling than the Media Library's own
 * uploader. Looking the URL up by slug (instead of hardcoding the
 * current site's domain) keeps this correct after the eventual move to
 * the production domain.
 */
function dam_media_url( $slug, $fallback = '' ) {
	static $cache = array();
	if ( array_key_exists( $slug, $cache ) ) {
		return $cache[ $slug ];
	}
	$attachment = get_page_by_path( $slug, OBJECT, 'attachment' );
	$url        = $attachment ? wp_get_attachment_url( $attachment->ID ) : $fallback;
	$cache[ $slug ] = $url;
	return $url;
}

/**
 * Builds a localized front-end URL for a page slug the way the reference
 * site does (English un-prefixed, fa/ar prefixed) -- Polylang's own
 * pll_home_url()/home_url() already add the right prefix once the page is
 * resolved, so this just resolves the slug's translated page permalink.
 */
function dam_localized_page_url( $slug, $locale = null ) {
	$locale = $locale ? $locale : dam_current_locale();
	$page   = get_page_by_path( $slug );
	if ( ! $page ) {
		return home_url( '/' . $slug . '/' );
	}
	if ( function_exists( 'pll_get_post' ) ) {
		$translated = pll_get_post( $page->ID, $locale );
		if ( $translated ) {
			return get_permalink( $translated );
		}
	}
	return get_permalink( $page );
}
