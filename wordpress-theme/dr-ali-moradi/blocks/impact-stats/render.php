<?php
/**
 * Renders the "Label|Value" pairs from dam_impact_stats plus the "as of"
 * date. Editing the list in Theme Options updates every placement.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$stats = dam_parse_lines_option( 'dam_impact_stats' );
if ( empty( $stats ) ) {
	return;
}

$as_of  = get_option( 'dam_impact_as_of', '' );
$prefix = dam_translate_string( 'impact_as_of_prefix', 'As of' );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'dam-impact-stats' ) ); ?>>
	<ul class="dam-impact-stats__list">
		<?php foreach ( $stats as $stat ) : ?>
			<li class="dam-impact-stats__item">
				<span class="dam-impact-stats__value"><?php echo esc_html( $stat['value'] ); ?></span>
				<span class="dam-impact-stats__label"><?php echo esc_html( $stat['label'] ); ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php if ( $as_of ) : ?>
		<p class="dam-impact-stats__as-of"><?php echo esc_html( $prefix . ' ' . $as_of ); ?></p>
	<?php endif; ?>
</div>
