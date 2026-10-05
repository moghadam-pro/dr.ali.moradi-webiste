<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$t      = dam_site_copy( $locale );
$facet_icons = array( 'hand', 'brain-circuit', 'lightbulb', 'microscope' );
$hero_fallback = dam_media_url( 'hero-bg-v2', DAM_THEME_URI . '/assets/img/hero/hero-bg-v2.jpg' );
$hero_image    = dam_theme_mod( 'hero_background_image', $locale ) ?: $hero_fallback;
$show_orbits   = (bool) dam_theme_mod( 'hero_orbits_enabled', $locale );

$name_first  = dam_theme_mod( 'hero_name_first', $locale );
$name_last   = dam_theme_mod( 'hero_name_last', $locale );
$credentials = dam_theme_mod_rows( 'hero_credentials', $locale );
$description = dam_theme_mod( 'hero_description', $locale );
$quote       = dam_theme_mod( 'hero_quote', $locale );
$facets      = dam_theme_mod_rows( 'hero_facets', $locale );
$check_list  = array_filter( array_map( 'trim', explode( "\n", dam_theme_mod( 'hero_credential_list', $locale ) ) ) );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'hero' ) ); ?>>
	<img class="hero-background fill-img" src="<?php echo esc_url( $hero_image ); ?>" alt="">
	<div class="hero-wash" aria-hidden="true"></div>
	<?php if ( $show_orbits ) : ?><div class="hero-orbits" aria-hidden="true">
		<div class="orbit orbit-one"></div>
		<div class="orbit orbit-two"></div>
		<div class="hero-dot dot-one"></div>
		<div class="hero-dot dot-two"></div>
	</div><?php endif; ?>
	<div class="hero-layout section-shell">
		<div class="hero-copy">
			<h1><span><?php echo esc_html( $name_first ); ?></span> <strong><?php echo esc_html( $name_last ); ?></strong></h1>
			<ul class="hero-credentials">
				<?php foreach ( $credentials as $credential ) : ?>
					<li>
						<span class="hero-credential-dot" aria-hidden="true"></span>
						<span class="hero-credential-copy"><strong><?php echo esc_html( $credential[0] ?? '' ); ?></strong> <span><?php echo esc_html( $credential[1] ?? '' ); ?></span></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<p class="hero-description"><?php echo esc_html( $description ); ?></p>
		</div>

		<div class="hero-note"><?php echo dam_icon( 'quote', 36 ); ?><span><?php echo esc_html( $quote ); ?></span></div>

		<div class="facet-bar section-shell" aria-label="<?php echo esc_attr( $t['pathsTitle'] ); ?>">
			<?php foreach ( $facets as $index => $facet ) : ?>
				<div class="facet">
					<?php echo dam_icon( $facet_icons[ $index ] ?? 'stethoscope', 27 ); ?>
					<span><small><?php echo esc_html( $facet[0] ?? '' ); ?></small><strong><?php echo esc_html( $facet[1] ?? '' ); ?></strong></span>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="credential-list">
			<?php foreach ( $check_list as $credential ) : ?>
				<span><?php echo dam_icon( 'check', 15 ); ?><?php echo esc_html( $credential ); ?></span>
			<?php endforeach; ?>
		</div>
	</div>
</section>
