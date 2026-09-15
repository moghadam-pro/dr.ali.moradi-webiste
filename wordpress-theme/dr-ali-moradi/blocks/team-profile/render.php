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

$locale       = dam_current_locale();
$labels       = dam_team_labels( $locale );
$role         = get_post_meta( $post->ID, 'dam_role', true );
$summary      = get_post_meta( $post->ID, 'dam_summary', true );
$excerpt      = has_excerpt( $post ) ? get_the_excerpt( $post ) : '';
$related_links = get_post_meta( $post->ID, 'dam_related_links', true );
$cover        = dam_media_url( 'team-profile-cover' );
?>
<section class="interior-cover">
	<img class="fill-img" src="<?php echo esc_url( $cover ); ?>" alt="">
	<div class="interior-cover-gradient" aria-hidden="true"></div>
	<div class="interior-cover-content section-shell">
		<p class="section-index light"><?php echo esc_html( $labels['profileIntro'] ); ?></p>
		<h1><?php echo esc_html( get_the_title( $post ) ); ?></h1>
		<?php if ( $role ) : ?><p><?php echo esc_html( $role ); ?></p><?php endif; ?>
	</div>
</section>
<?php dam_render_breadcrumbs(); ?>

<section <?php echo get_block_wrapper_attributes( array( 'class' => 'team-profile section-space section-shell' ) ); ?>>
	<div class="team-profile-image reveal">
		<?php if ( has_post_thumbnail( $post ) ) : ?>
			<?php echo get_the_post_thumbnail( $post, 'large', array( 'class' => 'fill-img', 'alt' => get_the_title( $post ) ) ); ?>
		<?php endif; ?>
	</div>
	<div class="team-profile-body reveal">
		<p class="section-index"><?php echo esc_html( $labels['expertise'] ); ?></p>
		<h2><?php echo esc_html( $role ); ?></h2>
		<?php if ( $excerpt ) : ?><p class="team-profile-excerpt"><?php echo esc_html( $excerpt ); ?></p><?php endif; ?>
		<div class="article-body"><?php echo apply_filters( 'the_content', $post->post_content ); ?></div>
		<?php if ( $summary ) : ?>
			<div class="team-profile-note">
				<?php echo dam_icon( 'sparkles' ); ?>
				<div><h3><?php echo esc_html( $labels['collaboration'] ); ?></h3><p><?php echo esc_html( $summary ); ?></p></div>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $related_links ) && is_array( $related_links ) ) : ?>
			<div class="team-profile-links">
				<h3><?php echo esc_html( $labels['relatedLinks'] ); ?></h3>
				<ul>
					<?php foreach ( $related_links as $link ) :
						if ( empty( $link['url'] ) ) {
							continue;
						}
						?>
						<li><a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $link['title'] ? $link['title'] : $link['url'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php wp_reset_postdata(); ?>
