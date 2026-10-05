<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$icons  = array( 'stethoscope', 'lightbulb', 'microscope' );

$kicker = dam_theme_mod( 'pathways_kicker', $locale );
$title  = dam_theme_mod( 'pathways_title', $locale );
$body   = dam_theme_mod( 'pathways_body', $locale );
$cards = array();
for ( $i = 1; $i <= 3; $i++ ) {
	$cards[] = array(
		'title' => dam_theme_mod( 'pathway_' . $i . '_title', $locale ),
		'body'  => dam_theme_mod( 'pathway_' . $i . '_body', $locale ),
		'label' => dam_theme_mod( 'pathway_' . $i . '_button_label', $locale ),
		'url'   => dam_theme_mod( 'pathway_' . $i . '_button_url', $locale ),
	);
}
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'pathways section-space section-shell' ) ); ?>>
	<div class="section-heading reveal">
		<p class="section-index"><?php echo esc_html( $kicker ); ?></p>
		<p><?php echo esc_html( $title ); ?>. <?php echo esc_html( $body ); ?></p>
	</div>
	<div class="path-grid">
		<?php foreach ( $cards as $index => $card ) :
			$icon = $icons[ $index ] ?? 'stethoscope';
			?>
			<div class="path-card reveal">
				<span class="path-icon-main"><?php echo dam_icon( $icon, 42 ); ?></span>
				<span class="path-icon-ghost" aria-hidden="true"><?php echo dam_icon( $icon, 170, 1 ); ?></span>
				<h3><?php echo esc_html( $card['title'] ); ?></h3>
				<p><?php echo esc_html( $card['body'] ); ?></p>
				<a class="button button-small" href="<?php echo esc_url( $card['url'] ); ?>"><?php echo esc_html( $card['label'] ); ?><?php echo dam_icon( 'arrow-right', 16 ); ?></a>
			</div>
		<?php endforeach; ?>
	</div>
</section>
