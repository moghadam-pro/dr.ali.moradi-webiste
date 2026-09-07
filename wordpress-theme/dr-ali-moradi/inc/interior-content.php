<?php
/**
 * Per-locale copy for the generic interior-page template (Research,
 * Innovation, Education, and the About/Clinical-care fallback), the
 * Contact page, and the About page's own multi-section design.
 * Transcribed verbatim from the reference site's app/site-content.ts
 * and app/about-content.ts (same rule as inc/homepage-content.php: this
 * is the theme's own UI/marketing copy, kept as static locale-keyed data
 * the same way the reference itself keeps it, not pulled from a CMS
 * field).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resolves the current page to a locale-independent "page key" (e.g.
 * "research", "innovations", "about") by stripping the "-fa"/"-ar"
 * suffix the import script gave non-English translations (see
 * content-migration/import-to-wordpress.mjs's localizedSlug()). Used to
 * look up this page's entry in dam_interior_pages_copy() regardless of
 * which language's copy of the page is being viewed.
 */
function dam_current_page_key() {
	$queried = get_queried_object();
	$slug    = $queried instanceof WP_Post ? $queried->post_name : '';
	return preg_replace( '/-(fa|ar)$/', '', $slug );
}

function dam_interior_pages_copy( $locale = null ) {
	static $all = null;
	if ( null === $all ) {
		$all = array(
			'en' => array(
				'onThisPage' => 'On this page',
				'pageAppointment' => 'Appointment',
				'nextLabel' => 'Continue exploring',
				'exploreMore' => 'Explore more',
				'pages' => array(
					'clinical-care' => array(
						'kicker' => 'Clinical care', 'title' => 'Specialized pathways for hand and upper-extremity care.', 'intro' => 'Patient-centered evaluation, treatment, reconstruction, and follow-up across the private office and hospital settings.',
						'sections' => array(
							array( 'title' => 'Private office care', 'text' => 'The private office is the main setting for specialist consultation, follow-up, selected outpatient procedures, and minor surgery in a focused and private environment.', 'items' => array( "Carpal tunnel release and trigger-finger treatment", "De Quervain's disease and tendon disorders", 'Tennis and golfer\'s elbow', 'Simple hand fractures and closed reduction', 'Selected nerve, tendon, injection, casting, and splinting services' ) ),
							array( 'title' => 'Hospital services', 'text' => 'Major and subspecialty operations are performed in equipped hospital settings. Imam Reza Hospital also supports university-based clinical care and teaching, while Razavi Hospital provides private hospital services.' ),
							array( 'title' => 'Patient resources', 'text' => 'Preparation before surgery, recovery after surgery, rehabilitation instructions, and frequently asked questions support informed care. Individual instructions from the treating team always take priority.' ),
							array( 'title' => 'Appointment pathways', 'text' => 'Planned appointments use the approved Nobat.ir link. Urgent office triage is available on Saturday, Monday, and Wednesday from 15:45 to 18:30 for acute hand and upper-extremity injuries.' ),
						), 'ctaTitle' => 'Choose the correct care pathway', 'ctaText' => 'Use the approved booking service for planned visits; seek emergency services immediately for life- or limb-threatening injuries.',
					),
					'research' => array(
						'kicker' => 'Research', 'title' => 'Questions from the clinic, tested through rigorous research.', 'intro' => 'Clinical studies, orthopedic biomechanics, prosthetic control, rehabilitation robotics, and outcome research form a connected program.',
						'sections' => array(
							array( 'title' => 'Hand and wrist research', 'text' => 'The program addresses carpal tunnel syndrome, distal-radius and distal radioulnar joint conditions, fracture fixation, wrist disorders, and patient outcomes.' ),
							array( 'title' => 'Biomechanics and medical engineering', 'text' => 'Research connects external-fixation mechanics, magnetic joint distraction, osseointegrated prostheses, robotic rehabilitation, and exoskeleton systems with clinical needs.' ),
							array( 'title' => 'Bionic hand control', 'text' => 'Implanted magnetic controls and sensor configurations are investigated as potential interfaces between residual muscle activity and prosthetic-hand motion.' ),
							array( 'title' => 'Publications and collaboration', 'text' => 'The latest available CV records more than 150 peer-reviewed articles, four books, approximately 30 national patents, and eight international patent families. Collaboration spans clinical research, engineering, thesis supervision, and professional education.' ),
						), 'ctaTitle' => 'Research connected to practice', 'ctaText' => 'Explore how clinical questions progress through evidence, engineering, and collaborative investigation.',
					),
					'innovations' => array(
						'kicker' => 'Innovation', 'title' => 'Engineering ideas shaped by real clinical constraints.', 'intro' => 'Each innovation begins with a practical problem and is connected to research evidence, development work, or intellectual property.',
						'sections' => array(
							array( 'title' => 'External fixation', 'text' => 'Fixation designs and biomechanical studies explore stability, usability, and more precise management of fractures and distraction procedures.' ),
							array( 'title' => 'Bionic hand and prosthetic control', 'text' => 'Magnetic sensors, implanted controls, and surgical interfaces are studied to support more intuitive control of upper-limb prostheses.' ),
							array( 'title' => 'Magnetic joint distraction', 'text' => 'Magnetic internal distraction investigates controlled mechanical loading while reducing dependence on bulky external mechanisms.' ),
							array( 'title' => 'Innovation ecosystem', 'text' => 'Projects including Avisa, BJRL, Akam, EuWalk, Integrom, and Fixodyn sit within one clinical-engineering ecosystem focused on real care challenges.' ),
						), 'ctaTitle' => 'From observation to usable systems', 'ctaText' => 'Innovation stories connect the clinical problem, development path, research evidence, and intended use.',
					),
					'about' => array(
						'kicker' => 'About Dr. Moradi', 'title' => 'A surgeon, researcher, inventor, and educator.', 'intro' => 'Dr. Ali Moradi is an Associate Professor of Orthopedics at Mashhad University of Medical Sciences and a hand and upper-extremity surgeon.',
						'sections' => array(
							array( 'title' => 'Academic and clinical identity', 'text' => 'His work connects specialist hand and upper-extremity care with orthopedic research, medical engineering, teaching, and innovation.' ),
							array( 'title' => 'Training', 'text' => 'He received his MD and completed orthopedic residency at Mashhad University of Medical Sciences, followed by hand and upper-extremity research at Massachusetts General Hospital, clinical training in hand and microsurgery at Tehran University of Medical Sciences, wrist-arthroscopy training at Clinique Bizet in Paris, and a PhD in Orthotics and Prosthetics.' ),
							array( 'title' => 'Research and leadership', 'text' => 'His work spans hand and wrist conditions, clinical outcomes, biomechanics, external fixation, prosthetic interfaces, magnetic sensing, robotics, and rehabilitation technologies.' ),
							array( 'title' => 'Recognition', 'text' => 'The professional archive includes best-paper and top-cited recognition, innovation certificates, invited lectures, reviewing and editorial contributions, and scientific committee roles.' ),
						), 'ctaTitle' => 'One practice, connected disciplines', 'ctaText' => 'Clinical care, research, engineering, and education are developed as parts of one professional mission.',
					),
				),
				'scholar' => array( 'title' => 'View the Google Scholar profile', 'text' => 'Explore Dr. Moradi’s publications, citations, and latest research output in the public Google Scholar profile.', 'action' => 'Open Google Scholar' ),
				'contact' => array(
					'kicker' => 'Contact', 'title' => 'A clear route for every enquiry.', 'intro' => 'Use the approved booking platform for appointments. Professional, research, education, and media enquiries may be sent by email.',
					'beforeTitle' => 'Before you write', 'beforeText' => 'Do not send urgent or sensitive medical information through this form. For a planned visit, use Nobat.ir.',
					'formName' => 'Name', 'formEmail' => 'Email', 'formType' => 'Enquiry type', 'formMessage' => 'Message',
					'formOptions' => array( 'Research collaboration', 'Education and training', 'Media and professional enquiry' ),
					'placeholder' => 'Please do not include sensitive medical information.',
				),
			),
			'fa' => array(
				'onThisPage' => 'در این صفحه',
				'pageAppointment' => 'نوبت‌دهی',
				'nextLabel' => 'ادامه مسیر',
				'exploreMore' => 'ادامه مطالعه',
				'pages' => array(
					'clinical-care' => array(
						'kicker' => 'خدمات درمانی', 'title' => 'مسیرهای تخصصی درمان دست و اندام فوقانی.', 'intro' => 'ارزیابی، درمان، بازسازی و پیگیری بیمارمحور در مطب و مراکز بیمارستانی.',
						'sections' => array(
							array( 'title' => 'خدمات مطب', 'text' => 'مطب، محل اصلی مشاوره تخصصی، پیگیری، برخی اقدامات سرپایی و جراحی‌های محدود در محیطی متمرکز و خصوصی است.', 'items' => array( 'آزادسازی تونل کارپ و درمان انگشت ماشه‌ای', 'بیماری دکرون و اختلالات تاندون', 'آرنج تنیس‌بازان و گلف‌بازان', 'شکستگی‌های ساده دست و جااندازی بسته', 'خدمات منتخب عصب، تاندون، تزریق، گچ و آتل' ) ),
							array( 'title' => 'خدمات بیمارستانی', 'text' => 'جراحی‌های بزرگ و فوق‌تخصصی در مراکز بیمارستانی مجهز انجام می‌شوند. بیمارستان امام رضا بستر خدمات دانشگاهی و آموزشی و بیمارستان رضوی بستر خدمات خصوصی بیمارستانی را فراهم می‌کند.' ),
							array( 'title' => 'منابع بیماران', 'text' => 'آمادگی پیش از عمل، مراقبت پس از عمل، راهنمای توان‌بخشی و پاسخ به پرسش‌های رایج برای تصمیم‌گیری آگاهانه ارائه می‌شوند. دستور اختصاصی تیم درمان همیشه اولویت دارد.' ),
							array( 'title' => 'مسیرهای نوبت', 'text' => 'ویزیت برنامه‌ریزی‌شده از لینک Nobat.ir انجام می‌شود. ارزیابی فوری آسیب‌های حاد دست و اندام فوقانی شنبه، دوشنبه و چهارشنبه از ساعت ۱۵:۴۵ تا ۱۸:۳۰ در مطب انجام می‌شود.' ),
						), 'ctaTitle' => 'مسیر درست درمان را انتخاب کنید', 'ctaText' => 'برای ویزیت برنامه‌ریزی‌شده از سامانه تأییدشده استفاده کنید و در شرایط تهدیدکننده جان یا اندام فوراً به اورژانس مراجعه کنید.',
					),
					'research' => array(
						'kicker' => 'پژوهش', 'title' => 'پرسش‌های بالینی با پژوهش دقیق آزموده می‌شوند.', 'intro' => 'مطالعات بالینی، بیومکانیک ارتوپدی، کنترل پروتز، رباتیک توان‌بخشی و سنجش پیامدها یک برنامه به‌هم‌پیوسته را شکل می‌دهند.',
						'sections' => array(
							array( 'title' => 'پژوهش دست و مچ', 'text' => 'موضوعات پژوهش شامل تونل کارپ، شکستگی دیستال رادیوس، مفصل رادیواولنار دیستال، تثبیت شکستگی، بیماری‌های مچ و پیامدهای گزارش‌شده توسط بیمار است.' ),
							array( 'title' => 'بیومکانیک و مهندسی پزشکی', 'text' => 'پژوهش‌ها مکانیک فیکساتور خارجی، دیستراکشن مغناطیسی مفصل، پروتزهای متصل به استخوان، رباتیک توان‌بخشی و اسکلت بیرونی را به نیازهای بالینی پیوند می‌دهند.' ),
							array( 'title' => 'کنترل دست بیونیک', 'text' => 'کنترل‌های کاشتنی مغناطیسی و پیکربندی حسگرها به‌عنوان رابط میان فعالیت عضله باقیمانده و حرکت پروتز دست بررسی می‌شوند.' ),
							array( 'title' => 'انتشارات و همکاری', 'text' => 'آخرین رزومه موجود بیش از ۱۵۰ مقاله داوری‌شده، چهار کتاب، حدود ۳۰ ثبت اختراع داخلی و هشت خانواده ثبت اختراع بین‌المللی را ثبت کرده است. همکاری‌ها شامل پژوهش بالینی، مهندسی، راهنمایی پایان‌نامه و آموزش حرفه‌ای است.' ),
						), 'ctaTitle' => 'پژوهش متصل به درمان', 'ctaText' => 'ببینید پرسش بالینی چگونه از مسیر شواهد، مهندسی و پژوهش مشترک پیش می‌رود.',
					),
					'innovations' => array(
						'kicker' => 'نوآوری', 'title' => 'ایده‌های مهندسی‌شده برای محدودیت‌های واقعی درمان.', 'intro' => 'هر نوآوری با یک مسئله عملی آغاز می‌شود و به شواهد پژوهشی، توسعه فنی یا مالکیت فکری متصل است.',
						'sections' => array(
							array( 'title' => 'فیکساتور خارجی', 'text' => 'طراحی‌های تثبیت و مطالعات بیومکانیک، پایداری، سهولت استفاده و مدیریت دقیق‌تر شکستگی و دیستراکشن را بررسی می‌کنند.' ),
							array( 'title' => 'دست بیونیک و کنترل پروتز', 'text' => 'حسگرهای مغناطیسی، کنترل کاشتنی و رابط‌های جراحی برای کنترل طبیعی‌تر پروتز اندام فوقانی مطالعه می‌شوند.' ),
							array( 'title' => 'دیستراکشن مغناطیسی مفصل', 'text' => 'دیستراکشن داخلی مغناطیسی، بارگذاری مکانیکی کنترل‌شده را با وابستگی کمتر به سازوکارهای حجیم خارجی بررسی می‌کند.' ),
							array( 'title' => 'زیست‌بوم نوآوری', 'text' => 'پروژه‌هایی مانند Avisa، BJRL، Akam، EuWalk، Integrom و Fixodyn در یک زیست‌بوم مشترک بالینی–مهندسی برای حل چالش‌های واقعی درمان قرار می‌گیرند.' ),
						), 'ctaTitle' => 'از مشاهده تا سامانه کاربردی', 'ctaText' => 'روایت هر نوآوری، مسئله بالینی، مسیر توسعه، شواهد و کاربرد مورد نظر را به هم متصل می‌کند.',
					),
					'about' => array(
						'kicker' => 'درباره دکتر مرادی', 'title' => 'جراح، پژوهشگر، مخترع و مدرس.', 'intro' => 'دکتر علی مرادی، دانشیار ارتوپدی دانشگاه علوم پزشکی مشهد و جراح دست و اندام فوقانی است.',
						'sections' => array(
							array( 'title' => 'هویت علمی و بالینی', 'text' => 'فعالیت او، درمان تخصصی دست و اندام فوقانی را با پژوهش ارتوپدی، مهندسی پزشکی، آموزش و نوآوری پیوند می‌دهد.' ),
							array( 'title' => 'تحصیلات و دوره‌ها', 'text' => 'او پزشکی عمومی و دستیاری ارتوپدی را در دانشگاه علوم پزشکی مشهد گذراند و پس از آن دوره پژوهشی دست و اندام فوقانی را در بیمارستان عمومی ماساچوست، آموزش بالینی جراحی دست و میکروسرجری را در دانشگاه علوم پزشکی تهران، آموزش آرتروسکوپی مچ را در کلینیک بیزه پاریس و دکتری تخصصی ارتز و پروتز را تکمیل کرد.' ),
							array( 'title' => 'پژوهش و مدیریت علمی', 'text' => 'حوزه‌های فعالیت او شامل بیماری‌های دست و مچ، پیامدهای بالینی، بیومکانیک، فیکساتور خارجی، رابط پروتز، حسگر مغناطیسی، رباتیک و فناوری توان‌بخشی است.' ),
							array( 'title' => 'افتخارات', 'text' => 'آرشیو حرفه‌ای شامل جوایز مقاله برتر و پراستناد، گواهی‌های نوآوری، سخنرانی‌های دعوت‌شده، داوری و فعالیت‌های تحریریه و عضویت در کمیته‌های علمی است.' ),
						), 'ctaTitle' => 'یک فعالیت حرفه‌ای، چند حوزه متصل', 'ctaText' => 'درمان، پژوهش، مهندسی و آموزش بخش‌های یک مأموریت حرفه‌ای مشترک هستند.',
					),
				),
				'scholar' => array( 'title' => 'مشاهده پروفایل Google Scholar', 'text' => 'فهرست مقاله‌ها، استنادها و تازه‌ترین خروجی پژوهشی دکتر مرادی را در پروفایل عمومی Google Scholar دنبال کنید.', 'action' => 'بازکردن Google Scholar' ),
				'contact' => array(
					'kicker' => 'تماس', 'title' => 'برای هر درخواست، یک مسیر روشن.', 'intro' => 'برای نوبت از سامانه تأییدشده استفاده کنید. درخواست‌های حرفه‌ای، پژوهشی، آموزشی و رسانه‌ای را می‌توان با ایمیل ارسال کرد.',
					'beforeTitle' => 'پیش از ارسال', 'beforeText' => 'اطلاعات حساس پزشکی یا موارد اورژانسی را از این فرم ارسال نکنید. برای ویزیت برنامه‌ریزی‌شده از Nobat.ir استفاده کنید.',
					'formName' => 'نام', 'formEmail' => 'ایمیل', 'formType' => 'نوع درخواست', 'formMessage' => 'پیام',
					'formOptions' => array( 'همکاری پژوهشی', 'آموزش و دوره', 'درخواست رسانه‌ای یا حرفه‌ای' ),
					'placeholder' => 'لطفاً اطلاعات حساس پزشکی وارد نکنید.',
				),
			),
			'ar' => array(
				'onThisPage' => 'في هذه الصفحة',
				'pageAppointment' => 'المواعيد',
				'nextLabel' => 'تابع الاستكشاف',
				'exploreMore' => 'استكشف المزيد',
				'pages' => array(
					'clinical-care' => array(
						'kicker' => 'الرعاية السريرية', 'title' => 'مسارات متخصصة لرعاية اليد والطرف العلوي.', 'intro' => 'تقييم وعلاج وإعادة بناء ومتابعة تتمحور حول المريض في العيادة الخاصة والمستشفيات.',
						'sections' => array(
							array( 'title' => 'رعاية العيادة الخاصة', 'text' => 'العيادة الخاصة هي الموقع الرئيسي للاستشارة والمتابعة والإجراءات المختارة والجراحة الصغرى في بيئة مخصصة وخاصة.', 'items' => array( 'تحرير النفق الرسغي وعلاج الإصبع الزنادي', 'داء دي كيرفان واضطرابات الأوتار', 'مرفق لاعب التنس والغولف', 'كسور اليد البسيطة والرد المغلق', 'خدمات مختارة للأعصاب والأوتار والحقن والجبائر' ) ),
							array( 'title' => 'خدمات المستشفى', 'text' => 'تُجرى العمليات الكبرى والمتخصصة في مستشفيات مجهزة. يدعم مستشفى الإمام رضا الرعاية الجامعية والتعليم، ويوفر مستشفى رضوي خدمات المستشفى الخاصة.' ),
							array( 'title' => 'موارد المرضى', 'text' => 'تدعم إرشادات ما قبل الجراحة وما بعدها والتأهيل والأسئلة الشائعة الرعاية الواعية. تبقى تعليمات فريق العلاج الفردية هي الأولوية.' ),
							array( 'title' => 'مسارات المواعيد', 'text' => 'تُحجز الزيارات المخططة عبر رابط Nobat.ir المعتمد. ويتاح التقييم العاجل لإصابات اليد والطرف العلوي الحادة أيام السبت والاثنين والأربعاء من ١٥:٤٥ إلى ١٨:٣٠ في العيادة.' ),
						), 'ctaTitle' => 'اختر مسار الرعاية الصحيح', 'ctaText' => 'استخدم خدمة الحجز المعتمدة للزيارات المخططة واطلب الطوارئ فوراً للحالات المهددة للحياة أو الطرف.',
					),
					'research' => array(
						'kicker' => 'البحث', 'title' => 'أسئلة من العيادة تُختبر ببحث دقيق.', 'intro' => 'تشكل الدراسات السريرية والميكانيكا الحيوية والتحكم بالأطراف والروبوتات التأهيلية وبحوث النتائج برنامجاً مترابطاً.',
						'sections' => array(
							array( 'title' => 'أبحاث اليد والمعصم', 'text' => 'يتناول البرنامج النفق الرسغي وكسور الكعبرة البعيدة والمفصل الكعبري الزندي البعيد وتثبيت الكسور واضطرابات المعصم ونتائج المرضى.' ),
							array( 'title' => 'الميكانيكا الحيوية والهندسة الطبية', 'text' => 'تربط الأبحاث ميكانيكا التثبيت الخارجي والإلهاء المفصلي المغناطيسي والأطراف المتصلة بالعظم وروبوتات التأهيل والهياكل الخارجية بالاحتياجات السريرية.' ),
							array( 'title' => 'التحكم باليد الإلكترونية', 'text' => 'تُدرس أدوات التحكم المغناطيسية المزروعة وإعدادات الحساسات كواجهة بين نشاط العضلات المتبقي وحركة اليد التعويضية.' ),
							array( 'title' => 'المنشورات والتعاون', 'text' => 'تسجل أحدث سيرة ذاتية أكثر من ١٥٠ مقالة محكّمة وأربعة كتب ونحو ٣٠ براءة وطنية وثماني عائلات براءات دولية. يشمل التعاون البحث السريري والهندسة والإشراف الأكاديمي والتعليم المهني.' ),
						), 'ctaTitle' => 'بحث متصل بالممارسة', 'ctaText' => 'استكشف انتقال الأسئلة السريرية عبر الدليل والهندسة والتحقيق التعاوني.',
					),
					'innovations' => array(
						'kicker' => 'الابتكار', 'title' => 'أفكار هندسية تشكّلت بقيود سريرية حقيقية.', 'intro' => 'يبدأ كل ابتكار بمشكلة عملية ويرتبط بدليل بحثي أو تطوير أو ملكية فكرية.',
						'sections' => array(
							array( 'title' => 'التثبيت الخارجي', 'text' => 'تستكشف تصاميم التثبيت ودراسات الميكانيكا الحيوية الثبات وسهولة الاستخدام والتعامل الأدق مع الكسور والإلهاء.' ),
							array( 'title' => 'اليد الإلكترونية والتحكم بالطرف', 'text' => 'تُدرس الحساسات المغناطيسية والتحكم المزروع والواجهات الجراحية لدعم تحكم أكثر طبيعية في الأطراف العلوية التعويضية.' ),
							array( 'title' => 'الإلهاء المفصلي المغناطيسي', 'text' => 'يبحث الإلهاء الداخلي المغناطيسي في تحميل ميكانيكي مضبوط مع تقليل الاعتماد على الآليات الخارجية الكبيرة.' ),
							array( 'title' => 'منظومة الابتكار', 'text' => 'تندرج مشروعات Avisa وBJRL وAkam وEuWalk وIntegrom وFixodyn ضمن منظومة سريرية هندسية واحدة تركز على تحديات الرعاية الحقيقية.' ),
						), 'ctaTitle' => 'من الملاحظة إلى النظام القابل للاستخدام', 'ctaText' => 'تربط قصص الابتكار المشكلة السريرية ومسار التطوير والدليل والاستخدام المقصود.',
					),
					'about' => array(
						'kicker' => 'عن الدكتور مرادي', 'title' => 'جراح وباحث ومخترع ومعلّم.', 'intro' => 'الدكتور علي مرادي أستاذ مشارك في جراحة العظام بجامعة مشهد للعلوم الطبية وجراح لليد والطرف العلوي.',
						'sections' => array(
							array( 'title' => 'الهوية الأكاديمية والسريرية', 'text' => 'يربط عمله الرعاية المتخصصة لليد والطرف العلوي بأبحاث العظام والهندسة الطبية والتعليم والابتكار.' ),
							array( 'title' => 'التدريب', 'text' => 'حصل على الطب وأكمل إقامة جراحة العظام في جامعة مشهد للعلوم الطبية، ثم أبحاث اليد والطرف العلوي في Massachusetts General Hospital، والتدريب السريري في جراحة اليد والجراحة المجهرية في جامعة طهران للعلوم الطبية، وتدريب تنظير المعصم في Clinique Bizet بباريس، ودكتوراه في تقويم الأطراف والأجهزة التعويضية.' ),
							array( 'title' => 'البحث والقيادة', 'text' => 'تشمل أعماله حالات اليد والمعصم والنتائج السريرية والميكانيكا الحيوية والتثبيت الخارجي وواجهات الأطراف والحساسات المغناطيسية والروبوتات وتقنيات التأهيل.' ),
							array( 'title' => 'التقدير', 'text' => 'يضم الأرشيف المهني جوائز أفضل بحث والأبحاث واسعة الاستشهاد وشهادات الابتكار والمحاضرات المدعوة والتحكيم والعمل التحريري وأدوار اللجان العلمية.' ),
						), 'ctaTitle' => 'ممارسة واحدة وتخصصات مترابطة', 'ctaText' => 'الرعاية والبحث والهندسة والتعليم أجزاء من رسالة مهنية واحدة.',
					),
				),
				'scholar' => array( 'title' => 'عرض ملف Google Scholar', 'text' => 'تابع المقالات والاستشهادات وأحدث المخرجات البحثية للدكتور مرادي في ملفه العام.', 'action' => 'فتح Google Scholar' ),
				'contact' => array(
					'kicker' => 'اتصل بنا', 'title' => 'مسار واضح لكل استفسار.', 'intro' => 'استخدم منصة الحجز المعتمدة للمواعيد. ويمكن إرسال الاستفسارات المهنية والبحثية والتعليمية والإعلامية بالبريد الإلكتروني.',
					'beforeTitle' => 'قبل أن تكتب', 'beforeText' => 'لا ترسل معلومات طبية حساسة أو طارئة عبر هذا النموذج. استخدم Nobat.ir للزيارة المخططة.',
					'formName' => 'الاسم', 'formEmail' => 'البريد الإلكتروني', 'formType' => 'نوع الاستفسار', 'formMessage' => 'الرسالة',
					'formOptions' => array( 'تعاون بحثي', 'تعليم وتدريب', 'استفسار إعلامي أو مهني' ),
					'placeholder' => 'يرجى عدم إدخال معلومات طبية حساسة.',
				),
			),
		);
	}

	$locale = $locale ? $locale : dam_current_locale();
	return isset( $all[ $locale ] ) ? $all[ $locale ] : $all['en'];
}
