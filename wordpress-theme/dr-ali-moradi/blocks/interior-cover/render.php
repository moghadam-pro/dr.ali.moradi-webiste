<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale   = dam_current_locale();
$page_key = ! empty( $attributes['pageKey'] ) ? $attributes['pageKey'] : dam_current_page_key();

// The About page has its own dedicated copy/design (see
// inc/about-content.php, blocks/about-*) distinct from the generic
// interior-page fallback entry of the same key in
// inc/interior-content.php, so its cover pulls from that instead.
$ic = dam_interior_pages_copy( $locale );
if ( 'about' === $page_key ) {
	$data = dam_about_page_copy( $locale );
} elseif ( 'contact' === $page_key ) {
	$data = $ic['contact'];
} else {
	$data = $ic['pages'][ $page_key ] ?? ( dam_clinic_subpages_copy( $locale )[ $page_key ] ?? null );
}

if ( ! $data ) {
	return;
}

// Cover-image slugs match the reference site's own filenames
// (pageCoverImages in app/page-extras.ts), which don't all match our
// page keys 1:1 -- e.g. our "innovations" (plural, hub page) vs. its
// "innovation-cover.jpg", and "clinical-care" vs. "clinic-cover.jpg".
$cover_slugs = array(
	'clinical-care'    => 'clinic-cover',
	'innovations'      => 'innovation-cover',
	'research'         => 'research-cover',
	'education'        => 'education-cover',
	'about'            => 'about-cover',
	'blog'             => 'blog-cover',
	'contact'          => 'clinic-cover',
	'clinic-services'  => 'clinic-08',
	'hospital-services' => 'hospital-14',
	'before-surgery'   => 'doctor',
	'after-surgery'    => '04-life',
	'faq'              => 'clinic-cover',
	'rehabilitation'   => '04-life',
);
$cover_slug = $cover_slugs[ $page_key ] ?? ( $page_key . '-cover' );
$image      = ! empty( $attributes['image'] ) ? $attributes['image'] : dam_media_url( $cover_slug );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'interior-cover' ) ); ?>>
	<img class="fill-img" src="<?php echo esc_url( $image ); ?>" alt="">
	<div class="interior-cover-gradient" aria-hidden="true"></div>
	<div class="interior-cover-content section-shell">
		<p class="section-index light"><?php echo esc_html( $data['kicker'] ); ?></p>
		<h1><?php echo esc_html( $data['title'] ); ?></h1>
		<?php if ( ! empty( $data['intro'] ) ) : ?><p><?php echo esc_html( $data['intro'] ); ?></p><?php endif; ?>
	</div>
</section>
<?php if ( ! empty( $attributes['backLink'] ) ) :
	$back_url = dam_localized_page_url( $attributes['backLink'], $locale );
	$back_copy = dam_interior_pages_copy( $locale );
	?>
	<div class="interior-back section-shell"><a href="<?php echo esc_url( $back_url ); ?>"><?php echo dam_icon( 'chevron-left', 16 ); ?><?php echo esc_html( $back_copy['pages'][ $attributes['backLink'] ]['title'] ?? '' ); ?></a></div>
<?php endif; ?>
