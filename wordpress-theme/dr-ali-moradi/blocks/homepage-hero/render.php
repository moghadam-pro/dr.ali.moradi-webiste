<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$t      = dam_site_copy( $locale );
$facet_icons = array( 'hand', 'brain-circuit', 'lightbulb', 'microscope' );
$hero_image  = dam_media_url( 'hero-bg-v2', DAM_THEME_URI . '/assets/img/hero/hero-bg-v2.jpg' );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'hero' ) ); ?>>
	<img class="hero-background fill-img" src="<?php echo esc_url( $hero_image ); ?>" alt="">
	<div class="hero-wash" aria-hidden="true"></div>
	<div class="hero-orbits" aria-hidden="true">
		<div class="orbit orbit-one"></div>
		<div class="orbit orbit-two"></div>
		<div class="hero-dot dot-one"></div>
		<div class="hero-dot dot-two"></div>
	</div>
	<div class="hero-layout section-shell">
		<div class="hero-copy">
			<h1><span><?php echo esc_html( $t['heroName'][0] ); ?></span> <strong><?php echo esc_html( $t['heroName'][1] ); ?></strong></h1>
			<ul class="hero-credentials">
				<?php foreach ( $t['heroCredentials'] as $credential ) : ?>
					<li>
						<span class="hero-credential-dot" aria-hidden="true"></span>
						<span class="hero-credential-copy"><strong><?php echo esc_html( $credential[0] ); ?></strong> <span><?php echo esc_html( $credential[1] ); ?></span></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<p class="hero-description"><?php echo esc_html( $t['heroDescription'] ); ?></p>
		</div>

		<div class="hero-note"><?php echo dam_icon( 'quote', 36 ); ?><span><?php echo esc_html( $t['heroQuote'] ); ?></span></div>

		<div class="facet-bar section-shell" aria-label="<?php echo esc_attr( $t['pathsTitle'] ); ?>">
			<?php foreach ( $t['facets'] as $index => $facet ) : ?>
				<div class="facet">
					<?php echo dam_icon( $facet_icons[ $index ] ?? 'stethoscope', 27 ); ?>
					<span><small><?php echo esc_html( $facet[0] ); ?></small><strong><?php echo esc_html( $facet[1] ); ?></strong></span>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="credential-list">
			<?php foreach ( $t['credentials'] as $credential ) : ?>
				<span><?php echo dam_icon( 'check', 15 ); ?><?php echo esc_html( $credential ); ?></span>
			<?php endforeach; ?>
		</div>
	</div>
</section>
