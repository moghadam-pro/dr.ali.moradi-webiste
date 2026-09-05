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

// Icons are the reference site's actual branded social-network SVGs
// (fetched from /icons/social/ on the live site, which uses these
// instead of the generic lucide icons that the git repo's copy of
// app/site-page.tsx still had at the time this was ported -- the live
// site is ahead of the last commit in this repo's `main` for this one
// detail; using the live version per the operator's instruction to read
// assets from whichever of the two sources currently has them).
$social = array(
	array( 'label' => 'Instagram', 'url' => 'https://www.instagram.com/dr_ali_moradi_handsurgeon', 'icon' => 'instagram' ),
	array( 'label' => 'Telegram', 'url' => 'https://t.me/DrAliMoradi', 'icon' => 'telegram' ),
	array( 'label' => 'Aparat', 'url' => 'https://www.aparat.com/dr_ali_moradi_handsurgeon', 'icon' => 'aparat' ),
);
?>
<footer <?php echo get_block_wrapper_attributes( array( 'class' => 'site-footer' ) ); ?>>
	<div class="section-shell footer-grid">
		<div class="footer-brand">
			<img src="<?php echo esc_url( dam_theme_asset_url( '/assets/img/brand/' . $logo ) ); ?>" width="153" height="50" alt="Dr. Ali Moradi">
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
				<a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( dam_theme_asset_url( '/assets/img/social/' . $link['icon'] . '.svg' ) ); ?>" width="16" height="16" alt=""><?php echo esc_html( $link['label'] ); ?></a>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="section-shell footer-bottom">
		<span><?php echo esc_html( $t['footer']['copyright'] ); ?></span>
		<span><?php echo esc_html( $t['footer']['disclaimer'] ); ?></span>
		<a href="https://moghadam.pro" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $t['footer']['credit'] ); ?></a>
	</div>
</footer>
