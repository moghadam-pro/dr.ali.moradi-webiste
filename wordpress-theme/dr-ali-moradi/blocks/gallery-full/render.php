<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale   = dam_current_locale();
$hub      = dam_clinic_hub_copy( $locale );
$page_key = dam_current_page_key();
$is_clinic = 'clinic-gallery' === $page_key;
$area      = $is_clinic ? 'clinic' : 'hospital';
$title     = $is_clinic ? $hub['clinicGalleryTitle'] : $hub['hospitalGalleryTitle'];
$intro     = $is_clinic ? $hub['clinicGalleryIntro'] : $hub['hospitalGalleryIntro'];
$cover     = dam_media_url( $is_clinic ? 'clinic-08' : 'hospital-14' );
$back_url  = dam_localized_page_url( 'clinical-care', $locale );
?>
<section class="interior-cover">
	<img class="fill-img" src="<?php echo esc_url( $cover ); ?>" alt="">
	<div class="interior-cover-gradient" aria-hidden="true"></div>
	<div class="interior-cover-content section-shell">
		<p class="section-index light"><?php echo esc_html( $hub['pathwaysKicker'] ); ?></p>
		<h1><?php echo esc_html( $title ); ?></h1>
		<p><?php echo esc_html( $intro ); ?></p>
	</div>
</section>
<div class="interior-back section-shell"><a href="<?php echo esc_url( $back_url ); ?>"><?php echo dam_icon( 'chevron-left', 16 ); ?><?php echo esc_html( $hub['backToClinic'] ); ?></a></div>
<?php dam_render_gallery_full( $area, $title ); ?>
