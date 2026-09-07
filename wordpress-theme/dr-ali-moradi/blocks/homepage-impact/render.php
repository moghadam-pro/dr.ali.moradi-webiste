<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$t      = dam_site_copy( $locale );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'impact section-space' ) ); ?>>
	<div class="section-shell">
		<div class="section-heading reveal">
			<p class="section-index"><?php echo esc_html( $t['impactIndex'] ); ?></p>
			<p><?php echo esc_html( $t['impactTitle'] ); ?>. <?php echo esc_html( $t['figuresNote'] ); ?></p>
		</div>
		<div class="metrics">
			<?php foreach ( $t['metrics'] as $metric ) : ?>
				<div class="metric reveal">
					<strong><?php echo esc_html( $metric[0] ); ?></strong>
					<span><?php echo esc_html( $metric[1] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
