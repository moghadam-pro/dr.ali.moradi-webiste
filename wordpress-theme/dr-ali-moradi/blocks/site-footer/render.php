<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$t      = dam_site_copy( $locale );
$logo   = 'en' === $locale ? 'logo.en-footer.svg' : 'logo.fa-ar-footer.svg';
$phone  = get_option( 'dam_clinic_phone', '' );
$map_url = 'https://www.google.com/maps/search/?api=1&query=Mashhad+Poursina+Building+Arya+Hospital';

// Only link to Explore/footer pages that actually exist -- Education has
// no source content yet (see open-items.md), so it is left out rather
// than linking to a page that was never created.
$explore_slugs = array( 'clinical-care', 'innovations', 'research', 'about', 'blog' );
$explore_links = array();
foreach ( $explore_slugs as $slug ) {
	if ( get_page_by_path( $slug ) ) {
		$explore_links[ $slug ] = dam_localized_page_url( $slug, $locale );
	}
}

$social = array(
	array( 'label' => 'Instagram', 'url' => 'https://www.instagram.com/dr_ali_moradi_handsurgeon', 'icon' => 'camera' ),
	array( 'label' => 'Telegram', 'url' => 'https://t.me/DrAliMoradi', 'icon' => 'send' ),
	array( 'label' => 'Aparat', 'url' => 'https://www.aparat.com/dr_ali_moradi_handsurgeon', 'icon' => 'play-circle' ),
);
?>
<footer <?php echo get_block_wrapper_attributes( array( 'class' => 'site-footer' ) ); ?>>
	<div class="section-shell footer-grid">
		<div class="footer-brand">
			<img src="<?php echo esc_url( DAM_THEME_URI . '/assets/img/brand/' . $logo ); ?>" width="153" height="50" alt="Dr. Ali Moradi">
			<p><?php echo esc_html( $t['footer']['bio'] ); ?></p>
			<a class="footer-book" href="<?php echo esc_url( dam_appointment_url() ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $t['footer']['booking'] ); ?><?php echo dam_icon( 'external-link', 15 ); ?></a>
		</div>

		<?php if ( $explore_links ) : ?>
		<div>
			<h3><?php echo esc_html( $t['footer']['explore'] ); ?></h3>
			<?php foreach ( $explore_links as $slug => $url ) : ?>
				<a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $t['footerExplore'][ $slug ] ?? $slug ); ?></a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<div class="footer-contact">
			<h3><?php echo esc_html( $t['footer']['contact'] ); ?></h3>
			<a href="mailto:info@DrAliMoradi.com"><?php echo dam_icon( 'mail' ); ?>info@DrAliMoradi.com</a>
			<?php if ( $phone ) : ?>
				<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>" dir="ltr"><?php echo dam_icon( 'phone' ); ?><?php echo esc_html( $phone ); ?></a>
			<?php endif; ?>
			<p><?php echo dam_icon( 'map-pin' ); ?><?php echo esc_html( $t['contact']['office'] ); ?></p>
			<p><?php echo dam_icon( 'map-pin' ); ?><?php echo esc_html( $t['contact']['clinic'] ); ?></p>
			<a href="<?php echo esc_url( $map_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo dam_icon( 'map-pin' ); ?><?php echo esc_html( $t['footer']['map'] ); ?><?php echo dam_icon( 'external-link', 13 ); ?></a>
		</div>

		<div class="footer-social">
			<h3><?php echo esc_html( $t['footer']['social'] ); ?></h3>
			<?php foreach ( $social as $link ) : ?>
				<a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo dam_icon( $link['icon'] ); ?><?php echo esc_html( $link['label'] ); ?></a>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="section-shell footer-bottom">
		<span><?php echo esc_html( $t['footer']['copyright'] ); ?></span>
		<span><?php echo esc_html( $t['footer']['disclaimer'] ); ?></span>
		<a href="https://moghadam.pro" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $t['footer']['credit'] ); ?></a>
	</div>
</footer>
