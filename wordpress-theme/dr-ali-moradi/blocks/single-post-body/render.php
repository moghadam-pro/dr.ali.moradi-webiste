<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post = get_queried_object();
if ( ! ( $post instanceof WP_Post ) ) {
	return;
}

$locale       = dam_current_locale();
$labels       = dam_blog_labels( $locale );
$categories   = get_the_category( $post->ID );
$category     = $categories ? $categories[0]->name : '';
$read_minutes = get_post_meta( $post->ID, 'dam_read_minutes', true );
$excerpt      = trim( wp_strip_all_tags( $post->post_content ) );
$blog_url     = dam_localized_page_url( 'blog', $locale );

$date_format = 'ar' === $locale ? 'j F Y' : ( 'fa' === $locale ? 'j F Y' : 'F j, Y' );
$date        = get_the_date( $date_format, $post );
?>
<section class="interior-cover">
	<?php if ( has_post_thumbnail( $post ) ) : ?>
		<?php echo get_the_post_thumbnail( $post, 'large', array( 'class' => 'fill-img', 'alt' => '' ) ); ?>
	<?php endif; ?>
	<div class="interior-cover-gradient" aria-hidden="true"></div>
	<div class="interior-cover-content section-shell">
		<?php if ( $category ) : ?><p class="section-index light"><?php echo esc_html( $category ); ?></p><?php endif; ?>
		<h1><?php echo esc_html( get_the_title( $post ) ); ?></h1>
		<?php if ( $excerpt ) : ?><p><?php echo esc_html( $excerpt ); ?></p><?php endif; ?>
	</div>
</section>

<article <?php echo get_block_wrapper_attributes( array( 'class' => 'single-post-article section-space section-shell' ) ); ?>>
	<div class="article-meta">
		<span><?php echo esc_html( $date ); ?></span>
		<?php if ( $read_minutes ) : ?><span><?php echo esc_html( $read_minutes . ' ' . $labels['minutes'] ); ?></span><?php endif; ?>
	</div>
	<div class="article-layout">
		<aside><a href="<?php echo esc_url( $blog_url ); ?>"><?php echo dam_icon( 'chevron-left', 16 ); ?><?php echo esc_html( $labels['back'] ); ?></a></aside>
		<div class="article-body">
			<section><h2><?php echo esc_html( $labels['overview'] ); ?></h2><p><?php echo esc_html( $excerpt ); ?></p></section>
			<section><h2><?php echo esc_html( $labels['assessment'] ); ?></h2><p><?php echo esc_html( $labels['assessmentText'] ); ?></p></section>
			<section><h2><?php echo esc_html( $labels['nextSteps'] ); ?></h2><p><?php echo esc_html( $labels['nextText'] ); ?></p></section>
			<p class="article-disclaimer"><?php echo dam_icon( 'shield-check', 20 ); ?><?php echo esc_html( $labels['disclaimer'] ); ?></p>
		</div>
	</div>
</article>
