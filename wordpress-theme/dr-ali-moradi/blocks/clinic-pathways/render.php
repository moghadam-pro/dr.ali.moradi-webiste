<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$hub    = dam_clinic_hub_copy( $locale );
$images = array(
	'clinic-services'   => dam_media_url( 'clinic-services-cover' ),
	'hospital-services' => dam_media_url( 'hospital-services-cover' ),
);
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'clinic-pathways section-space section-shell' ) ); ?>>
	<div class="clinic-pathway-grid">
		<?php foreach ( $hub['pathways'] as $path ) :
			$url = dam_localized_page_url( $path['slug'], $locale );
			?>
			<div class="clinic-pathway-card reveal">
				<a class="clinic-pathway-link" href="<?php echo esc_url( $url ); ?>">
					<span class="clinic-pathway-image"><img class="fill-img" src="<?php echo esc_url( $images[ $path['slug'] ] ?? '' ); ?>" alt=""></span>
					<span class="clinic-pathway-title"><?php echo esc_html( $path['title'] ); ?></span>
				</a>
			</div>
		<?php endforeach; ?>
	</div>
</section>
