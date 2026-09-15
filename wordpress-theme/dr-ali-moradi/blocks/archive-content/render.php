<?php
/**
 * Category/tag/date/author/search archive listing. Uses the main query
 * (have_posts()/the_post()) since this block renders as part of the
 * actual archive template WordPress already resolved for the request --
 * not a custom WP_Query -- so it correctly reflects whichever archive is
 * being viewed. Styled with the same blog-grid/blog-card classes as the
 * dedicated /blog/ page (blocks/blog-archive) instead of the generic
 * core block markup the site previously fell back to here.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$labels = dam_blog_labels( $locale );
$cover  = dam_media_url( 'blog-cover' );

$title       = get_the_archive_title();
$description = get_the_archive_description();
?>
<section class="interior-cover">
	<img class="fill-img" src="<?php echo esc_url( $cover ); ?>" alt="">
	<div class="interior-cover-gradient" aria-hidden="true"></div>
	<div class="interior-cover-content section-shell">
		<h1><?php echo wp_kses_post( $title ); ?></h1>
		<?php if ( $description ) : ?><p><?php echo wp_kses_post( $description ); ?></p><?php endif; ?>
	</div>
</section>

<section <?php echo get_block_wrapper_attributes( array( 'class' => 'blog-archive section-space section-shell' ) ); ?>>
	<?php if ( have_posts() ) : ?>
		<div class="blog-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				$categories   = get_the_category();
				$category     = $categories ? $categories[0]->name : '';
				$read_minutes = get_post_meta( get_the_ID(), 'dam_read_minutes', true );
				$link         = get_permalink();
				?>
				<div class="blog-card reveal">
					<a class="blog-card-image" href="<?php echo esc_url( $link ); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'medium', array( 'class' => 'fill-img', 'alt' => get_the_title() ) ); ?>
						<?php endif; ?>
					</a>
					<div class="blog-card-copy">
						<?php if ( $category ) : ?><p class="card-tag"><?php echo esc_html( $category ); ?></p><?php endif; ?>
						<h2><a href="<?php echo esc_url( $link ); ?>"><?php the_title(); ?></a></h2>
						<p><?php echo esc_html( get_the_excerpt() ); ?></p>
						<div class="blog-card-meta">
							<?php if ( $read_minutes ) : ?><span><?php echo esc_html( $read_minutes . ' ' . $labels['minutes'] ); ?></span><?php endif; ?>
							<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $labels['read'] ); ?><?php echo dam_icon( 'arrow-right', 15 ); ?></a>
						</div>
					</div>
				</div>
			<?php endwhile; ?>
		</div>

		<?php
		$pagination = paginate_links(
			array(
				'prev_text' => dam_icon( 'chevron-left', 16 ),
				'next_text' => dam_icon( 'chevron-right', 16 ),
				'type'      => 'list',
			)
		);
		if ( $pagination ) :
			?>
			<nav class="archive-pagination" aria-label="<?php esc_attr_e( 'Pagination', 'dr-ali-moradi' ); ?>">
				<?php echo wp_kses_post( $pagination ); ?>
			</nav>
		<?php endif; ?>
	<?php else : ?>
		<p class="archive-empty"><?php esc_html_e( 'Nothing found.', 'dr-ali-moradi' ); ?></p>
	<?php endif; ?>
</section>
