<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$t      = dam_site_copy( $locale );
$news_url = dam_localized_page_url( 'blog', $locale );

$posts = get_posts(
	array(
		'post_type'      => 'post',
		'posts_per_page' => 4,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'no_found_rows'  => true,
	)
);

if ( empty( $posts ) ) {
	return;
}
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'news section-space section-shell' ) ); ?>>
	<div class="section-heading split-heading reveal">
		<div>
			<p class="section-index"><?php echo esc_html( $t['awardsKicker'] ); ?></p>
			<p><?php echo esc_html( $t['awardsTitle'] ); ?></p>
			<span class="section-heading-intro"><?php echo esc_html( $t['awardsIntro'] ); ?></span>
		</div>
		<a class="text-link" href="<?php echo esc_url( $news_url ); ?>"><?php echo esc_html( $t['allUpdates'] ); ?><?php echo dam_icon( 'arrow-right', 17 ); ?></a>
	</div>
	<div class="news-grid">
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
						<strong><?php echo esc_html( get_the_title( $post ) ); ?></strong>
					</span>
				</a>
			</div>
		<?php endforeach; ?>
	</div>
</section>
