<?php
/**
 * Appointment button. The URL is never hardcoded in a template — it comes
 * from Theme Options (default https://nobat.ir/9705) so changing the
 * booking destination is a one-field edit, not a code change.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$url = get_option( 'dam_appointment_url', 'https://nobat.ir/9705' );
if ( ! $url ) {
	return;
}

$label = dam_translate_string( 'appointment_cta_label', 'Book an appointment' );
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<a class="dam-appointment-cta wp-element-button" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer">
		<?php echo esc_html( $label ); ?>
	</a>
</div>
