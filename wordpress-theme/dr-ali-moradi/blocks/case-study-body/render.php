<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post = get_queried_object();
if ( ! ( $post instanceof WP_Post ) || ! in_array( $post->post_type, array( 'condition', 'innovation' ), true ) ) {
	return;
}

$locale = dam_current_locale();
$type   = $post->post_type;

$labels_by_locale = array(
	'en' => array(
		'condition'        => 'Clinical condition',
		'innovation'       => 'Innovation project',
		'overview'         => 'Overview',
		'details'          => 'At a glance',
		'category'         => 'Category',
		'status'           => 'Development status',
		'evidence'         => 'Evidence and references',
		'open_evidence'    => 'Open the supporting source',
		'back_condition'   => 'Back to clinical care',
		'back_innovation'  => 'Back to innovations',
		'empty'            => 'Further detail will be added after clinical and editorial review.',
		'medical_note'     => 'This page provides general information and does not replace an individual clinical assessment.',
		'statuses'         => array( 'concept' => 'Concept', 'prototype' => 'Prototype', 'clinical-validation' => 'Clinical validation', 'deployed' => 'Deployed' ),
	),
	'fa' => array(
		'condition'        => 'بیماری و مشکل بالینی',
		'innovation'       => 'پروژه نوآوری',
		'overview'         => 'معرفی',
		'details'          => 'اطلاعات کلیدی',
		'category'         => 'دسته‌بندی',
		'status'           => 'وضعیت توسعه',
		'evidence'         => 'شواهد و منابع',
		'open_evidence'    => 'مشاهده منبع مرتبط',
		'back_condition'   => 'بازگشت به خدمات درمانی',
		'back_innovation'  => 'بازگشت به نوآوری‌ها',
		'empty'            => 'جزئیات بیشتر پس از بازبینی بالینی و محتوایی افزوده می‌شود.',
		'medical_note'     => 'مطالب این صفحه اطلاعات عمومی است و جایگزین ارزیابی و توصیه پزشکی فردی نیست.',
		'statuses'         => array( 'concept' => 'ایده', 'prototype' => 'نمونه اولیه', 'clinical-validation' => 'اعتبارسنجی بالینی', 'deployed' => 'در حال استفاده' ),
	),
	'ar' => array(
		'condition'        => 'حالة سريرية',
		'innovation'       => 'مشروع ابتكاري',
		'overview'         => 'نظرة عامة',
		'details'          => 'معلومات أساسية',
		'category'         => 'الفئة',
		'status'           => 'مرحلة التطوير',
		'evidence'         => 'الأدلة والمراجع',
		'open_evidence'    => 'فتح المصدر الداعم',
		'back_condition'   => 'العودة إلى الرعاية السريرية',
		'back_innovation'  => 'العودة إلى الابتكارات',
		'empty'            => 'ستضاف تفاصيل أخرى بعد المراجعة السريرية والتحريرية.',
		'medical_note'     => 'تقدم هذه الصفحة معلومات عامة ولا تغني عن التقييم الطبي الفردي.',
		'statuses'         => array( 'concept' => 'فكرة', 'prototype' => 'نموذج أولي', 'clinical-validation' => 'تحقق سريري', 'deployed' => 'قيد الاستخدام' ),
	),
);

$labels       = $labels_by_locale[ $locale ] ?? $labels_by_locale['en'];
$is_condition = 'condition' === $type;
$kicker       = $labels[ $type ];
$back_url     = dam_localized_page_url( $is_condition ? 'clinical-care' : 'innovations', $locale );
$back_label   = $labels[ $is_condition ? 'back_condition' : 'back_innovation' ];
$excerpt      = trim( get_the_excerpt( $post ) );
$content      = trim( $post->post_content );
$category     = '';
$status       = '';
$evidence_url = '';

if ( $is_condition ) {
	$terms = get_the_terms( $post->ID, 'condition_category' );
	if ( is_array( $terms ) && $terms ) {
		$category = $terms[0]->name;
	}
} else {
	$category     = trim( (string) get_post_meta( $post->ID, 'dam_category', true ) );
	$status_key   = trim( (string) get_post_meta( $post->ID, 'dam_status', true ) );
	$status       = $labels['statuses'][ $status_key ] ?? $status_key;
	$evidence_url = trim( (string) get_post_meta( $post->ID, 'dam_evidence_url', true ) );
}

$has_details = $category || $status || $evidence_url;
?>
<section class="interior-cover case-study-cover<?php echo has_post_thumbnail( $post ) ? '' : ' case-study-cover--fallback'; ?>">
	<?php if ( has_post_thumbnail( $post ) ) : ?>
		<?php echo get_the_post_thumbnail( $post, 'full', array( 'class' => 'fill-img', 'alt' => '' ) ); ?>
	<?php endif; ?>
	<div class="interior-cover-gradient" aria-hidden="true"></div>
	<div class="interior-cover-content section-shell">
		<p class="section-index light"><?php echo esc_html( $kicker ); ?></p>
		<h1><?php echo esc_html( get_the_title( $post ) ); ?></h1>
		<?php if ( $excerpt ) : ?><p><?php echo esc_html( $excerpt ); ?></p><?php endif; ?>
	</div>
</section>

<div class="interior-back section-shell"><a href="<?php echo esc_url( $back_url ); ?>"><?php echo dam_icon( 'chevron-left', 16 ); ?><?php echo esc_html( $back_label ); ?></a></div>

<article <?php echo get_block_wrapper_attributes( array( 'class' => 'case-study section-space section-shell' ) ); ?>>
	<div class="case-study-layout">
		<aside class="case-study-facts">
			<p class="section-index"><?php echo esc_html( $labels['details'] ); ?></p>
			<?php if ( $category ) : ?>
				<div class="case-study-fact"><span><?php echo esc_html( $labels['category'] ); ?></span><strong><?php echo esc_html( $category ); ?></strong></div>
			<?php endif; ?>
			<?php if ( $status ) : ?>
				<div class="case-study-fact"><span><?php echo esc_html( $labels['status'] ); ?></span><strong><?php echo esc_html( $status ); ?></strong></div>
			<?php endif; ?>
			<?php if ( $evidence_url ) : ?>
				<div class="case-study-fact"><span><?php echo esc_html( $labels['evidence'] ); ?></span><a href="<?php echo esc_url( $evidence_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $labels['open_evidence'] ); ?><?php echo dam_icon( 'external-link', 15 ); ?></a></div>
			<?php endif; ?>
			<?php if ( ! $has_details ) : ?><p class="case-study-facts-empty"><?php echo esc_html( $kicker ); ?></p><?php endif; ?>
		</aside>

		<div class="case-study-content">
			<p class="section-index"><?php echo esc_html( $labels['overview'] ); ?></p>
			<?php if ( $content ) : ?>
				<div class="case-study-prose"><?php echo apply_filters( 'the_content', $content ); ?></div>
			<?php elseif ( $excerpt ) : ?>
				<div class="case-study-prose"><p><?php echo esc_html( $excerpt ); ?></p></div>
			<?php else : ?>
				<div class="case-study-prose"><p><?php echo esc_html( $labels['empty'] ); ?></p></div>
			<?php endif; ?>

			<?php if ( $is_condition ) : ?>
				<p class="case-study-note"><?php echo dam_icon( 'shield-check', 20 ); ?><span><?php echo esc_html( $labels['medical_note'] ); ?></span></p>
			<?php endif; ?>
		</div>
	</div>
</article>
