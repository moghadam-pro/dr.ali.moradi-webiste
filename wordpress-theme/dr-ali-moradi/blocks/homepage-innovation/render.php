<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$t      = dam_site_copy( $locale );
$images = array(
	dam_media_url( 'external-fixator-2', DAM_THEME_URI . '/assets/img/innovation/external-fixator.jpg' ),
	dam_media_url( 'bionic-hand-2', DAM_THEME_URI . '/assets/img/innovation/bionic-hand.png' ),
	dam_media_url( 'magnetic-distractor-2', DAM_THEME_URI . '/assets/img/innovation/magnetic-distractor.png' ),
);
$innovation_url = dam_localized_page_url( 'innovations', $locale );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'innovation section-space section-shell' ) ); ?>>
	<div class="section-heading reveal">
		<p class="section-index"><?php echo esc_html( $t['innovationIndex'] ); ?></p>
		<p><?php echo esc_html( $t['innovationTitle'] ); ?>. <?php echo esc_html( $t['innovationIntro'] ); ?></p>
	</div>
	<div class="innovation-grid">
		<?php foreach ( $t['innovations'] as $index => $story ) : ?>
			<div class="innovation-card reveal">
				<div class="innovation-art"><img class="fill-img" src="<?php echo esc_url( $images[ $index ] ); ?>" alt="<?php echo esc_attr( $story[1] ); ?>"></div>
				<p class="card-tag"><?php echo esc_html( $story[0] ); ?></p>
				<h3><?php echo esc_html( $story[1] ); ?></h3>
				<p><?php echo esc_html( $story[2] ); ?></p>
				<a href="<?php echo esc_url( $innovation_url ); ?>"><?php echo esc_html( $t['readStory'] ); ?><?php echo dam_icon( 'arrow-right', 16 ); ?></a>
			</div>
		<?php endforeach; ?>
	</div>
</section>
