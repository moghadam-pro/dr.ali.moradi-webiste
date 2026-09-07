<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$t      = dam_site_copy( $locale );
$ic     = dam_interior_pages_copy( $locale );
$phone  = get_option( 'dam_clinic_phone', '' );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'contact-details reveal' ) ); ?>>
	<p class="section-index"><?php echo esc_html( $t['footer']['contact'] ); ?></p>
	<h2><?php echo esc_html( $ic['contact']['beforeTitle'] ); ?></h2>
	<p><?php echo esc_html( $ic['contact']['beforeText'] ); ?></p>
	<a class="button" href="<?php echo esc_url( dam_appointment_url() ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $t['continueNobat'] ); ?><?php echo dam_icon( 'external-link', 16 ); ?></a>
	<div class="contact-list">
		<span><?php echo dam_icon( 'map-pin' ); ?><?php echo esc_html( $t['contact']['office'] ); ?></span>
		<span><?php echo dam_icon( 'mail' ); ?><a href="mailto:info@DrAliMoradi.com">info@DrAliMoradi.com</a></span>
		<?php if ( $phone ) : ?>
			<span><?php echo dam_icon( 'phone' ); ?><a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>" dir="ltr"><?php echo esc_html( $phone ); ?></a></span>
		<?php endif; ?>
	</div>
</div>
