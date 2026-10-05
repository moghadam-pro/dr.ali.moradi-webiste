<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$t      = dam_site_copy( $locale );

$posts = dam_customizer_section_posts( 'innovation', $locale );
if ( empty( $posts ) ) {
	return;
}
$kicker   = dam_theme_mod( 'innovation_kicker', $locale );
$title    = dam_theme_mod( 'innovation_title', $locale );
$subtitle = dam_theme_mod( 'innovation_subtitle', $locale );
$columns  = max( 1, min( 3, (int) dam_theme_mod( 'innovation_columns', $locale ) ) );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'innovation section-space section-shell' ) ); ?>>
	<div class="section-heading reveal">
		<p class="section-index"><?php echo esc_html( $kicker ); ?></p>
		<p><?php echo esc_html( $title ); ?>. <?php echo esc_html( $subtitle ); ?></p>
	</div>
	<div class="innovation-grid card-grid-columns-<?php echo esc_attr( $columns ); ?>">
		<?php foreach ( $posts as $post ) :
			$categories = get_the_category( $post->ID );
			$category   = $categories ? $categories[0]->name : '';
			$link       = get_permalink( $post );
			?>
			<div class="innovation-card reveal">
				<div class="innovation-art">
					<?php if ( has_post_thumbnail( $post ) ) : ?>
						<?php echo get_the_post_thumbnail( $post, 'large', array( 'class' => 'fill-img', 'alt' => get_the_title( $post ) ) ); ?>
					<?php endif; ?>
				</div>
				<?php if ( $category ) : ?><p class="card-tag"><?php echo esc_html( $category ); ?></p><?php endif; ?>
				<h3><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( get_the_title( $post ) ); ?></a></h3>
				<p><?php echo esc_html( get_the_excerpt( $post ) ); ?></p>
				<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $t['readStory'] ); ?><?php echo dam_icon( 'arrow-right', 16 ); ?></a>
			</div>
		<?php endforeach; ?>
	</div>
</section>
