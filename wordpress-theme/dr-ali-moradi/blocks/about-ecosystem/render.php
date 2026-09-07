<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$copy   = dam_about_page_copy( $locale );
$images = array(
	'research'    => dam_media_url( 'research-cover' ),
	'innovations' => dam_media_url( 'innovation-cover' ),
	'education'   => dam_media_url( 'education-cover' ),
);
$explore_more = dam_interior_pages_copy( $locale )['exploreMore'];

// Only link to ecosystem cards whose page actually exists -- Education
// has no source content yet (see open-items.md), so its card is left
// out rather than linking to a page that was never created.
$cards = array_values( array_filter( $copy['ecosystem'], function ( $item ) {
	return (bool) get_page_by_path( $item['slug'] );
} ) );

if ( empty( $cards ) ) {
	return;
}
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'about-ecosystem section-space' ) ); ?>>
	<div class="section-shell">
		<div class="section-heading reveal">
			<p class="section-index"><?php echo esc_html( $copy['ecosystemKicker'] ); ?></p>
			<h2><?php echo esc_html( $copy['ecosystemTitle'] ); ?></h2>
			<p><?php echo esc_html( $copy['ecosystemText'] ); ?></p>
		</div>
		<div class="about-ecosystem-grid">
			<?php foreach ( $cards as $item ) :
				$url = dam_localized_page_url( $item['slug'], $locale );
				?>
				<div class="about-ecosystem-card reveal"><a href="<?php echo esc_url( $url ); ?>">
					<span class="about-ecosystem-image"><img class="fill-img" src="<?php echo esc_url( $images[ $item['slug'] ] ?? '' ); ?>" alt=""></span>
					<span class="about-ecosystem-copy"><strong><?php echo esc_html( $item['title'] ); ?></strong><small><?php echo esc_html( $item['text'] ); ?></small><i><?php echo esc_html( $explore_more ); ?></i></span>
				</a></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
