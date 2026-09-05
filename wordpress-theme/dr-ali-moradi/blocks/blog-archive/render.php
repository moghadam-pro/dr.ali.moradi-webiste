<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$labels = dam_blog_labels( $locale );
$cover  = dam_media_url( 'blog-cover' );

$posts = get_posts(
	array(
		'post_type'      => 'post',
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'no_found_rows'  => true,
	)
);
?>
<section class="interior-cover">
	<img class="fill-img" src="<?php echo esc_url( $cover ); ?>" alt="">
	<div class="interior-cover-gradient" aria-hidden="true"></div>
	<div class="interior-cover-content section-shell">
		<p class="section-index light"><?php echo esc_html( $labels['kicker'] ); ?></p>
		<h1><?php echo esc_html( $labels['title'] ); ?></h1>
		<p><?php echo esc_html( $labels['intro'] ); ?></p>
	</div>
</section>

<section <?php echo get_block_wrapper_attributes( array( 'class' => 'blog-archive section-space section-shell' ) ); ?>>
	<div class="blog-grid">
		<?php foreach ( $posts as $post ) :
			$categories   = get_the_category( $post->ID );
			$category     = $categories ? $categories[0]->name : '';
			$read_minutes = get_post_meta( $post->ID, 'dam_read_minutes', true );
			$link         = get_permalink( $post );
			?>
			<div class="blog-card reveal">
				<a class="blog-card-image" href="<?php echo esc_url( $link ); ?>">
					<?php if ( has_post_thumbnail( $post ) ) : ?>
						<?php echo get_the_post_thumbnail( $post, 'medium', array( 'class' => 'fill-img', 'alt' => get_the_title( $post ) ) ); ?>
					<?php endif; ?>
				</a>
				<div class="blog-card-copy">
					<?php if ( $category ) : ?><p class="card-tag"><?php echo esc_html( $category ); ?></p><?php endif; ?>
					<h2><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( get_the_title( $post ) ); ?></a></h2>
					<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $post->post_content ), 24 ) ); ?></p>
					<div class="blog-card-meta">
						<?php if ( $read_minutes ) : ?><span><?php echo esc_html( $read_minutes . ' ' . $labels['minutes'] ); ?></span><?php endif; ?>
						<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $labels['read'] ); ?><?php echo dam_icon( 'arrow-right', 15 ); ?></a>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
