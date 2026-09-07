<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$t      = dam_site_copy( $locale );
$icons  = array( 'stethoscope', 'lightbulb', 'microscope' );
$slugs  = $t['pathSlugs'];
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'pathways section-space section-shell' ) ); ?>>
	<div class="section-heading reveal">
		<p class="section-index"><?php echo esc_html( $t['pathwaysIndex'] ); ?></p>
		<p><?php echo esc_html( $t['pathsTitle'] ); ?>. <?php echo esc_html( $t['pathsBody'] ); ?></p>
	</div>
	<div class="path-grid">
		<?php foreach ( $t['pathCards'] as $index => $card ) :
			$icon = $icons[ $index ] ?? 'stethoscope';
			$url  = dam_localized_page_url( $slugs[ $index ], $locale );
			?>
			<div class="path-card reveal">
				<span class="path-icon-main"><?php echo dam_icon( $icon, 42 ); ?></span>
				<span class="path-icon-ghost" aria-hidden="true"><?php echo dam_icon( $icon, 170, 1 ); ?></span>
				<h3><?php echo esc_html( $card[0] ); ?></h3>
				<p><?php echo esc_html( $card[1] ); ?></p>
				<a class="button button-small" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $t['pathCtas'][ $index ] ); ?><?php echo dam_icon( 'arrow-right', 16 ); ?></a>
			</div>
		<?php endforeach; ?>
	</div>
</section>
