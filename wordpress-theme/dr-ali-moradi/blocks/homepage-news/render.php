<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();

$cat_id = (int) dam_theme_mod( 'recognition_category', $locale );
if ( $cat_id && function_exists( 'pll_get_term' ) ) {
	$cat_id = (int) ( pll_get_term( $cat_id, $locale ) ?: $cat_id );
}
$posts = dam_customizer_section_posts( 'recognition', $locale );

if ( empty( $posts ) ) {
	return;
}

$news_url = $cat_id ? get_category_link( $cat_id ) : home_url( '/' );
if ( is_wp_error( $news_url ) ) {
	$news_url = home_url( '/' );
}
$columns  = max( 1, min( 3, (int) dam_theme_mod( 'recognition_columns', $locale ) ) );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'news section-space section-shell' ) ); ?>>
	<div class="section-heading split-heading reveal">
		<div>
			<p class="section-index"><?php echo esc_html( dam_theme_mod( 'recognition_kicker', $locale ) ); ?></p>
			<p><?php echo esc_html( dam_theme_mod( 'recognition_title', $locale ) ); ?></p>
			<span class="section-heading-intro"><?php echo esc_html( dam_theme_mod( 'recognition_subtitle', $locale ) ); ?></span>
		</div>
		<a class="text-link" href="<?php echo esc_url( $news_url ); ?>"><?php echo esc_html( dam_theme_mod( 'recognition_link_label', $locale ) ); ?><?php echo dam_icon( 'arrow-right', 17 ); ?></a>
	</div>
	<div class="news-grid card-grid-columns-<?php echo esc_attr( $columns ); ?>">
		<?php foreach ( $posts as $post ) :
			$categories = get_the_category( $post->ID );
			$category   = $categories ? $categories[0]->name : '';
			?>
			<div class="news-card reveal">
				<a class="news-card-link" href="<?php echo esc_url( get_permalink( $post ) ); ?>" aria-label="<?php echo esc_attr( get_the_title( $post ) ); ?>">
					<?php if ( has_post_thumbnail( $post ) ) : ?>
						<?php echo get_the_post_thumbnail( $post, 'large', array( 'class' => 'fill-img', 'alt' => '' ) ); ?>
					<?php endif; ?>
					<span class="news-card-gradient" aria-hidden="true"></span>
					<span class="news-card-content">
						<?php if ( $category ) : ?><small><?php echo esc_html( $category ); ?></small><?php endif; ?>
						<strong><?php echo esc_html( get_the_title( $post ) ); ?></strong><em><?php echo esc_html( get_the_excerpt( $post ) ); ?></em>
					</span>
				</a>
			</div>
		<?php endforeach; ?>
	</div>
</section>
