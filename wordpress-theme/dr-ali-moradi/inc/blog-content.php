<?php
/**
 * Blog archive/single-post copy, transcribed verbatim from the
 * reference site's app/blog-content.ts `blogLabels` (same static-copy
 * rule as the theme's other inc/*-content.php files). The single-post
 * body itself renders the post's own editor content (see
 * blocks/single-post-body/render.php) -- these labels only cover the
 * archive hero and the single-post sidebar (back link, tags,
 * categories headings).
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
				'tags' => 'Tags', 'categories' => 'Categories',
				'postInfo' => 'Post Info', 'publishedOn' => 'Published on', 'readTime' => 'Read time',
				'author' => 'Author', 'category' => 'Category', 'comments' => 'Comments',
			),
			'fa' => array(
				'kicker' => 'وبلاگ', 'title' => 'آموزش کاربردی سلامت دست و اندام فوقانی.', 'intro' => 'کتابخانه‌ای رو به رشد برای بیماران، فراگیران و همکاران؛ این محتوا آموزشی است و جایگزین ارزیابی فردی نیست.',
				'read' => 'مطالعه مقاله', 'minutes' => 'دقیقه مطالعه', 'back' => 'بازگشت به همه مقاله‌ها',
				'tags' => 'برچسب‌ها', 'categories' => 'دسته‌بندی‌های وبلاگ',
				'postInfo' => 'اطلاعات این پست', 'publishedOn' => 'تاریخ انتشار', 'readTime' => 'زمان مطالعه',
				'author' => 'نویسنده', 'category' => 'دسته‌بندی', 'comments' => 'دیدگاه‌ها',
			),
			'ar' => array(
				'kicker' => 'المدونة', 'title' => 'تثقيف عملي لصحة اليد والطرف العلوي.', 'intro' => 'مكتبة متنامية للمرضى والمتعلمين والمتعاونين؛ المحتوى تعليمي ولا يستبدل التقييم الفردي.',
				'read' => 'اقرأ المقال', 'minutes' => 'دقائق قراءة', 'back' => 'العودة إلى كل المقالات',
				'tags' => 'الوسوم', 'categories' => 'تصنيفات المدونة',
				'postInfo' => 'معلومات المقال', 'publishedOn' => 'تاريخ النشر', 'readTime' => 'وقت القراءة',
				'author' => 'الكاتب', 'category' => 'التصنيف', 'comments' => 'التعليقات',
			),
		);
	}
	$locale = $locale ? $locale : dam_current_locale();
	return isset( $all[ $locale ] ) ? $all[ $locale ] : $all['en'];
}
