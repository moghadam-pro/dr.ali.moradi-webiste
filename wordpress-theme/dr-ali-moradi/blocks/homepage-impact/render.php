<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$metrics = array();
for ( $i = 1; $i <= 4; $i++ ) {
	$metrics[] = array( dam_theme_mod( 'impact_' . $i . '_title', $locale ), dam_theme_mod( 'impact_' . $i . '_subtitle', $locale ) );
}
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'impact section-space' ) ); ?>>
	<div class="section-shell">
		<div class="section-heading reveal">
			<p class="section-index"><?php echo esc_html( dam_theme_mod( 'impact_kicker', $locale ) ); ?></p>
			<p><?php echo esc_html( dam_theme_mod( 'impact_title', $locale ) ); ?>. <?php echo esc_html( dam_theme_mod( 'impact_subtitle', $locale ) ); ?></p>
		</div>
		<div class="metrics">
			<?php foreach ( $metrics as $metric ) : ?>
				<div class="metric reveal">
					<strong><?php echo esc_html( $metric[0] ); ?></strong>
					<span><?php echo esc_html( $metric[1] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
