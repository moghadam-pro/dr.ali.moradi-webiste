<?php
/**
 * Blog archive/single-post copy, transcribed verbatim from the
 * reference site's app/blog-content.ts `blogLabels` (same static-copy
 * rule as the theme's other inc/*-content.php files). The
 * Overview/Assessment/Next-steps/disclaimer structure on a single post
 * is the reference's own generic per-post template -- every post on
 * the reference site gets the same three boilerplate sections around
 * its own excerpt, it isn't unique per-post content that needed to be
 * invented here.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dam_blog_labels( $locale = null ) {
	static $all = null;
	if ( null === $all ) {
		$all = array(
			'en' => array(
				'kicker' => 'Blog', 'title' => 'Practical education for hand and upper-extremity health.', 'intro' => 'A growing library for patients, learners, and collaborators. Content is educational and never replaces individual assessment.',
				'read' => 'Read article', 'minutes' => 'min read', 'back' => 'Back to all articles',
				'overview' => 'Overview', 'assessment' => 'Why assessment matters', 'nextSteps' => 'Treatment and next steps',
				'assessmentText' => 'Similar symptoms may arise from different tissues or levels of injury. History, examination, and appropriate imaging or tests help define the diagnosis, severity, and safest care pathway.',
				'nextText' => 'Options may include observation, activity adjustment, therapy, medication, splinting, an office procedure, or surgery. The right sequence depends on the individual case and should be agreed with the treating team.',
				'disclaimer' => 'This article is general education. It does not provide a diagnosis or emergency response.',
			),
			'fa' => array(
				'kicker' => 'وبلاگ', 'title' => 'آموزش کاربردی سلامت دست و اندام فوقانی.', 'intro' => 'کتابخانه‌ای رو به رشد برای بیماران، فراگیران و همکاران؛ این محتوا آموزشی است و جایگزین ارزیابی فردی نیست.',
				'read' => 'مطالعه مقاله', 'minutes' => 'دقیقه مطالعه', 'back' => 'بازگشت به همه مقاله‌ها',
				'overview' => 'مرور موضوع', 'assessment' => 'چرا ارزیابی اهمیت دارد؟', 'nextSteps' => 'درمان و گام بعدی',
				'assessmentText' => 'علائم مشابه ممکن است از بافت‌ها یا سطوح متفاوت آسیب ناشی شوند. شرح حال، معاینه و تصویر یا آزمایش مناسب به تشخیص، تعیین شدت و انتخاب ایمن‌ترین مسیر کمک می‌کند.',
				'nextText' => 'گزینه‌ها می‌توانند شامل پایش، اصلاح فعالیت، توان‌بخشی، دارو، آتل، اقدام مطب یا جراحی باشند. ترتیب درست به شرایط فردی وابسته است و باید با تیم درمان تعیین شود.',
				'disclaimer' => 'این مقاله آموزش عمومی است و تشخیص پزشکی یا پاسخ اورژانسی ارائه نمی‌کند.',
			),
			'ar' => array(
				'kicker' => 'المدونة', 'title' => 'تثقيف عملي لصحة اليد والطرف العلوي.', 'intro' => 'مكتبة متنامية للمرضى والمتعلمين والمتعاونين؛ المحتوى تعليمي ولا يستبدل التقييم الفردي.',
				'read' => 'اقرأ المقال', 'minutes' => 'دقائق قراءة', 'back' => 'العودة إلى كل المقالات',
				'overview' => 'نظرة عامة', 'assessment' => 'لماذا يهم التقييم؟', 'nextSteps' => 'العلاج والخطوات التالية',
				'assessmentText' => 'قد تنشأ أعراض متشابهة من أنسجة أو مستويات إصابة مختلفة. يساعد التاريخ والفحص والصور أو الاختبارات المناسبة في تحديد التشخيص والشدة والمسار الأكثر أماناً.',
				'nextText' => 'قد تشمل الخيارات المراقبة وتعديل النشاط والتأهيل والدواء والجبيرة وإجراء في العيادة أو الجراحة. يعتمد التسلسل الصحيح على الحالة الفردية ويُتفق عليه مع الفريق المعالج.',
				'disclaimer' => 'هذا المقال للتثقيف العام ولا يقدم تشخيصاً أو استجابة للطوارئ.',
			),
		);
	}
	$locale = $locale ? $locale : dam_current_locale();
	return isset( $all[ $locale ] ) ? $all[ $locale ] : $all['en'];
}
