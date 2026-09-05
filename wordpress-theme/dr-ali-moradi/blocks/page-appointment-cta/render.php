<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$t      = dam_site_copy( $locale );
$ic     = dam_interior_pages_copy( $locale );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'page-appointment section-space section-shell' ) ); ?>>
	<div class="next-step page-appointment-card reveal">
		<?php echo dam_icon( 'calendar-days' ); ?>
		<div><p><?php echo esc_html( $ic['pageAppointment'] ); ?></p><h2><?php echo esc_html( $t['appointmentTitle'] ); ?></h2><span><?php echo esc_html( $t['appointmentBody'] ); ?></span></div>
		<a class="button" href="<?php echo esc_url( dam_appointment_url() ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $t['continueNobat'] ); ?><?php echo dam_icon( 'external-link', 16 ); ?></a>
	</div>
	<p class="medical-note"><?php echo dam_icon( 'shield-check', 18 ); ?><?php echo esc_html( $t['medicalNote'] ); ?></p>
</section>
