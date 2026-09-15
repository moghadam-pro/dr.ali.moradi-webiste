<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;
$post = get_queried_object();
if ( ! ( $post instanceof WP_Post ) ) {
	return;
}
setup_postdata( $post );

$locale          = dam_current_locale();
$labels          = dam_blog_labels( $locale );
$categories      = get_the_category( $post->ID );
$category        = $categories ? $categories[0]->name : '';
$read_minutes    = get_post_meta( $post->ID, 'dam_read_minutes', true );
$excerpt         = get_the_excerpt( $post );
$blog_url        = dam_localized_page_url( 'blog', $locale );
$post_tags       = get_the_tags( $post->ID );
$blog_categories = get_categories( array( 'hide_empty' => true ) );
$author_name     = get_the_author_meta( 'display_name', $post->post_author );
$author_url      = get_author_posts_url( $post->post_author );
$comment_count   = get_comments_number( $post );

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
<?php dam_render_breadcrumbs(); ?>

<article <?php echo get_block_wrapper_attributes( array( 'class' => 'single-post-article section-space section-shell' ) ); ?>>
	<div class="article-meta">
		<span><?php echo esc_html( $date ); ?></span>
		<?php if ( $read_minutes ) : ?><span><?php echo esc_html( $read_minutes . ' ' . $labels['minutes'] ); ?></span><?php endif; ?>
	</div>
	<div class="article-layout">
		<aside>
			<div class="article-sidebar-group article-post-info">
				<h3><?php echo esc_html( $labels['postInfo'] ); ?></h3>
				<ul class="article-post-info-list">
					<li>
						<span class="article-post-info-label"><?php echo esc_html( $labels['publishedOn'] ); ?></span>
						<time datetime="<?php echo esc_attr( get_the_date( 'c', $post ) ); ?>"><?php echo esc_html( $date ); ?></time>
					</li>
					<?php if ( $read_minutes ) : ?>
					<li>
						<span class="article-post-info-label"><?php echo esc_html( $labels['readTime'] ); ?></span>
						<span><?php echo esc_html( $read_minutes . ' ' . $labels['minutes'] ); ?></span>
					</li>
					<?php endif; ?>
					<li>
						<span class="article-post-info-label"><?php echo esc_html( $labels['author'] ); ?></span>
						<a href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( $author_name ); ?></a>
					</li>
					<?php if ( $post_tags ) : ?>
					<li>
						<span class="article-post-info-label"><?php echo esc_html( $labels['tags'] ); ?></span>
						<div class="article-tag-list">
							<?php foreach ( $post_tags as $tag ) : ?>
								<a href="<?php echo esc_url( get_tag_link( $tag ) ); ?>"><?php echo esc_html( $tag->name ); ?></a>
							<?php endforeach; ?>
						</div>
					</li>
					<?php endif; ?>
					<?php if ( $categories ) : ?>
					<li>
						<span class="article-post-info-label"><?php echo esc_html( $labels['category'] ); ?></span>
						<div class="article-tag-list">
							<?php foreach ( $categories as $cat ) : ?>
								<a href="<?php echo esc_url( get_category_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
							<?php endforeach; ?>
						</div>
					</li>
					<?php endif; ?>
					<li>
						<span class="article-post-info-label"><?php echo esc_html( $labels['comments'] ); ?></span>
						<span><?php echo esc_html( $comment_count ); ?></span>
					</li>
				</ul>
			</div>

			<a class="article-back" href="<?php echo esc_url( $blog_url ); ?>"><?php echo dam_icon( 'chevron-left', 16 ); ?><?php echo esc_html( $labels['back'] ); ?></a>

			<?php if ( $blog_categories ) : ?>
			<hr class="article-sidebar-divider" />
			<div class="article-sidebar-group">
				<h3><?php echo esc_html( $labels['categories'] ); ?></h3>
				<ul class="article-category-list">
					<?php foreach ( $blog_categories as $cat ) : ?>
						<li><a href="<?php echo esc_url( get_category_link( $cat ) ); ?>"><span><?php echo esc_html( $cat->name ); ?></span><span class="article-category-count"><?php echo (int) $cat->count; ?></span></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
		</aside>
		<div class="article-body">
			<?php echo apply_filters( 'the_content', $post->post_content ); ?>
		</div>
	</div>
</article>
<?php wp_reset_postdata(); ?>
