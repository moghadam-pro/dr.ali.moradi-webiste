<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$t      = dam_site_copy( $locale );
$images = array(
	dam_media_url( '01-injury', DAM_THEME_URI . '/assets/img/connected-practice/01-injury.jpg' ),
	dam_media_url( '02-innovation', DAM_THEME_URI . '/assets/img/connected-practice/02-innovation.jpg' ),
	dam_media_url( '03-application', DAM_THEME_URI . '/assets/img/connected-practice/03-application.jpg' ),
	dam_media_url( '04-life', DAM_THEME_URI . '/assets/img/connected-practice/04-life.jpg' ),
);
$innovation_url = dam_localized_page_url( 'innovations', $locale );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'journey section-space' ) ); ?>>
	<div class="section-shell">
		<div class="section-heading split-heading journey-heading reveal">
			<div>
				<p class="section-index light"><?php echo esc_html( $t['connectedIndex'] ); ?></p>
				<p><?php echo esc_html( $t['storyBody'] ); ?></p>
			</div>
			<a class="text-link light" href="<?php echo esc_url( $innovation_url ); ?>"><?php echo esc_html( $t['journeyLink'] ); ?><?php echo dam_icon( 'arrow-right', 17 ); ?></a>
		</div>
		<div class="connected-grid">
			<?php foreach ( $t['journey'] as $index => $step ) : ?>
				<div class="connected-card reveal">
					<div class="connected-image"><img class="fill-img" src="<?php echo esc_url( $images[ $index ] ); ?>" alt="<?php echo esc_attr( $step[1] ); ?>"></div>
					<?php if ( $index < 3 ) : ?>
						<span class="connected-arrow"><?php echo dam_icon( 'chevron-right', 48, 1 ); ?></span>
					<?php endif; ?>
					<div class="connected-meta">
						<span><?php echo esc_html( $t['journeyStepNumbers'][ $index ] ); ?></span>
						<div><small><?php echo esc_html( $step[0] ); ?></small><h3><?php echo esc_html( $step[1] ); ?></h3></div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
