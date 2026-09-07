<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post = get_queried_object();
if ( ! ( $post instanceof WP_Post ) ) {
	return;
}

$locale  = dam_current_locale();
$labels  = dam_team_labels( $locale );
$role    = get_post_meta( $post->ID, 'dam_role', true );
$summary = get_post_meta( $post->ID, 'dam_summary', true );
$bio     = trim( wp_strip_all_tags( $post->post_content ) );
$draft   = dam_team_draft_background( $locale );
$cover   = dam_media_url( 'team-profile-cover' );
$back_slug = dam_team_member_back_slug( $post->ID );
$back_url  = dam_localized_page_url( $back_slug, $locale );
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

<div class="interior-back section-shell"><a href="<?php echo esc_url( $back_url ); ?>"><?php echo dam_icon( 'chevron-left', 16 ); ?><?php echo esc_html( $labels['back'] ); ?></a></div>

<section <?php echo get_block_wrapper_attributes( array( 'class' => 'team-profile section-space section-shell' ) ); ?>>
	<div class="team-profile-image reveal">
		<?php if ( has_post_thumbnail( $post ) ) : ?>
			<?php echo get_the_post_thumbnail( $post, 'large', array( 'class' => 'fill-img', 'alt' => get_the_title( $post ) ) ); ?>
		<?php endif; ?>
	</div>
	<div class="team-profile-body reveal">
		<p class="section-index"><?php echo esc_html( $labels['expertise'] ); ?></p>
		<h2><?php echo esc_html( $role ); ?></h2>
		<div><p><?php echo esc_html( trim( $bio . ' ' . $draft ) ); ?></p></div>
		<?php if ( $summary ) : ?>
			<div class="team-profile-note">
				<?php echo dam_icon( 'sparkles' ); ?>
				<div><h3><?php echo esc_html( $labels['collaboration'] ); ?></h3><p><?php echo esc_html( $summary ); ?></p></div>
			</div>
		<?php endif; ?>
	</div>
</section>
