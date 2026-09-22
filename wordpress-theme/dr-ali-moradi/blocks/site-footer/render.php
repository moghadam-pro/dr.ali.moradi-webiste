<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale       = dam_current_locale();
$default_logo = 'en' === $locale ? 'logo.en-footer.svg' : 'logo.fa-ar-footer.svg';
$logo         = dam_theme_mod( 'footer_logo', $locale );
$logo         = $logo ? $logo : dam_theme_asset_url( '/assets/img/brand/' . $default_logo );
$email        = sanitize_email( dam_theme_mod( 'footer_email', $locale ) );
$phone        = dam_theme_mod( 'footer_phone', $locale );
$map_url      = dam_theme_mod( 'footer_map_url', $locale );

$explore_links = array();
foreach ( array( 'clinical_care', 'innovations', 'research', 'about', 'blog' ) as $key ) {
	$label = dam_theme_mod( 'footer_explore_' . $key . '_label', $locale );
	$url   = dam_theme_mod( 'footer_explore_' . $key . '_url', $locale );
	if ( $label && $url ) {
		$explore_links[] = array( 'label' => $label, 'url' => $url );
	}
}

$resource_links = array();
foreach ( array( 'before', 'after', 'faq', 'rehab' ) as $key ) {
	$label = dam_theme_mod( 'footer_resource_' . $key . '_label', $locale );
	$url   = dam_theme_mod( 'footer_resource_' . $key . '_url', $locale );
	if ( $label && $url ) {
		$resource_links[] = array( 'label' => $label, 'url' => $url );
	}
}

$social = array();
foreach ( array( 'instagram', 'telegram', 'aparat' ) as $key ) {
	$label = dam_theme_mod( 'footer_social_' . $key . '_label', $locale );
	$url   = dam_theme_mod( 'footer_social_' . $key . '_url', $locale );
	if ( $label && $url ) {
		$social[] = array( 'label' => $label, 'url' => $url, 'icon' => $key );
	}
}

$footer_grid_class = 'footer-grid' . ( $resource_links ? '' : ' footer-grid--no-resources' );
?>
<footer <?php echo get_block_wrapper_attributes( array( 'class' => 'site-footer' ) ); ?>>
	<div class="section-shell <?php echo esc_attr( $footer_grid_class ); ?>">
		<div class="footer-brand">
			<img src="<?php echo esc_url( $logo ); ?>" width="153" height="50" alt="Dr. Ali Moradi">
			<p><?php echo esc_html( dam_theme_mod( 'footer_bio', $locale ) ); ?></p>
			<a class="footer-book" href="<?php echo esc_url( dam_theme_mod( 'footer_booking_url', $locale ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( dam_theme_mod( 'footer_booking_label', $locale ) ); ?><?php echo dam_icon( 'external-link', 15 ); ?></a>
		</div>

		<?php if ( $explore_links ) : ?>
		<div>
			<h3><?php echo esc_html( dam_theme_mod( 'footer_explore_title', $locale ) ); ?></h3>
			<?php foreach ( $explore_links as $link ) : ?>
				<a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<?php if ( $resource_links ) : ?>
		<div>
			<h3><?php echo esc_html( dam_theme_mod( 'footer_resources_title', $locale ) ); ?></h3>
			<?php foreach ( $resource_links as $link ) : ?>
				<a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<div class="footer-contact">
			<h3><?php echo esc_html( dam_theme_mod( 'footer_contact_title', $locale ) ); ?></h3>
			<?php if ( $email ) : ?><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo dam_icon( 'mail' ); ?><?php echo esc_html( $email ); ?></a><?php endif; ?>
			<?php if ( $phone ) : ?><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" dir="ltr"><?php echo dam_icon( 'phone' ); ?><?php echo esc_html( $phone ); ?></a><?php endif; ?>
			<?php if ( dam_theme_mod( 'footer_office_address', $locale ) ) : ?><p><?php echo dam_icon( 'map-pin' ); ?><?php echo esc_html( dam_theme_mod( 'footer_office_address', $locale ) ); ?></p><?php endif; ?>
			<?php if ( dam_theme_mod( 'footer_clinic_address', $locale ) ) : ?><p><?php echo dam_icon( 'map-pin' ); ?><?php echo esc_html( dam_theme_mod( 'footer_clinic_address', $locale ) ); ?></p><?php endif; ?>
			<?php if ( $map_url ) : ?><a href="<?php echo esc_url( $map_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo dam_icon( 'map-pin' ); ?><?php echo esc_html( dam_theme_mod( 'footer_map_label', $locale ) ); ?><?php echo dam_icon( 'external-link', 13 ); ?></a><?php endif; ?>
		</div>

		<?php if ( $social ) : ?>
		<div class="footer-social">
			<h3><?php echo esc_html( dam_theme_mod( 'footer_social_title', $locale ) ); ?></h3>
			<?php foreach ( $social as $link ) : ?>
				<a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( dam_theme_asset_url( '/assets/img/social/' . $link['icon'] . '.svg' ) ); ?>" width="16" height="16" alt=""><?php echo esc_html( $link['label'] ); ?></a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>

	<div class="section-shell footer-bottom">
		<span><?php echo esc_html( dam_theme_mod( 'footer_copyright', $locale ) ); ?></span>
		<span><?php echo esc_html( dam_theme_mod( 'footer_disclaimer', $locale ) ); ?></span>
		<a href="<?php echo esc_url( dam_theme_mod( 'footer_credit_url', $locale ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( dam_theme_mod( 'footer_credit_label', $locale ) ); ?></a>
	</div>
</footer>
