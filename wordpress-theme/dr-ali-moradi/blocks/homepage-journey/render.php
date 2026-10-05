<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$fallback_images = array(
	dam_media_url( '01-injury', DAM_THEME_URI . '/assets/img/connected-practice/01-injury.jpg' ),
	dam_media_url( '02-innovation', DAM_THEME_URI . '/assets/img/connected-practice/02-innovation.jpg' ),
	dam_media_url( '03-application', DAM_THEME_URI . '/assets/img/connected-practice/03-application.jpg' ),
	dam_media_url( '04-life', DAM_THEME_URI . '/assets/img/connected-practice/04-life.jpg' ),
);

$kicker     = dam_theme_mod( 'journey_kicker', $locale );
$intro      = dam_theme_mod( 'journey_intro', $locale );
$link_label = dam_theme_mod( 'journey_link_label', $locale );
$link_url   = dam_theme_mod( 'journey_link_url', $locale );
$steps      = array();
for ( $i = 1; $i <= 4; $i++ ) {
	$steps[] = array(
		'number'  => dam_theme_mod( 'journey_step_' . $i . '_number', $locale ),
		'eyebrow' => dam_theme_mod( 'journey_step_' . $i . '_eyebrow', $locale ),
		'title'   => dam_theme_mod( 'journey_step_' . $i . '_title', $locale ),
		'image'   => dam_theme_mod( 'journey_step_' . $i . '_image', $locale ) ?: $fallback_images[ $i - 1 ],
	);
}
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'journey section-space' ) ); ?>>
	<div class="section-shell">
		<div class="section-heading split-heading journey-heading reveal">
			<div>
				<p class="section-index light"><?php echo esc_html( $kicker ); ?></p>
				<p><?php echo esc_html( $intro ); ?></p>
			</div>
			<a class="text-link light" href="<?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $link_label ); ?><?php echo dam_icon( 'arrow-right', 17 ); ?></a>
		</div>
		<div class="connected-grid">
			<?php foreach ( $steps as $index => $step ) : ?>
				<div class="connected-card reveal">
					<div class="connected-image"><img class="fill-img" src="<?php echo esc_url( $step['image'] ); ?>" alt="<?php echo esc_attr( $step['title'] ); ?>"></div>
					<?php if ( $index < 3 ) : ?>
						<span class="connected-arrow"><?php echo dam_icon( 'chevron-right', 48, 1 ); ?></span>
					<?php endif; ?>
					<div class="connected-meta">
						<span><?php echo esc_html( $step['number'] ); ?></span>
						<div><small><?php echo esc_html( $step['eyebrow'] ); ?></small><h3><?php echo esc_html( $step['title'] ); ?></h3></div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
