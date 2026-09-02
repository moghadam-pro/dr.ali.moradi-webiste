<?php
/**
 * Renders phone / WhatsApp / office address from Theme Options.
 * Edit once in Appearance -> Theme Options, updates in header, footer,
 * and anywhere else this block is placed.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phone    = get_option( 'dam_clinic_phone', '' );
$whatsapp = get_option( 'dam_clinic_whatsapp', '' );
$address  = get_option( 'dam_clinic_address_office', '' );

if ( ! $phone && ! $whatsapp && ! $address ) {
	return;
}

$whatsapp_label = dam_translate_string( 'whatsapp_label', 'WhatsApp' );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'dam-contact-info' ) ); ?>>
	<?php if ( $phone ) : ?>
		<a class="dam-contact-info__phone" dir="ltr" href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
	<?php endif; ?>
	<?php if ( $whatsapp ) : ?>
		<a class="dam-contact-info__whatsapp" href="https://wa.me/<?php echo esc_attr( preg_replace( '/\D+/', '', $whatsapp ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $whatsapp_label ); ?></a>
	<?php endif; ?>
	<?php if ( $address ) : ?>
		<address class="dam-contact-info__address"><?php echo wp_kses_post( nl2br( esc_html( $address ) ) ); ?></address>
	<?php endif; ?>
</div>
