<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$copy   = dam_about_page_copy( $locale );
$image  = dam_media_url( 'office' );
$icons  = array( 'stethoscope', 'microscope', 'lightbulb' );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'about-practice section-space' ) ); ?>>
	<div class="section-shell">
		<div class="about-practice-lead reveal">
			<div><p class="section-index"><?php echo esc_html( $copy['practiceKicker'] ); ?></p><h2><?php echo esc_html( $copy['practiceTitle'] ); ?></h2><p><?php echo esc_html( $copy['practiceText'] ); ?></p></div>
			<div class="about-practice-image"><img class="fill-img" src="<?php echo esc_url( $image ); ?>" alt=""></div>
		</div>
		<div class="about-principles">
			<?php foreach ( $copy['principles'] as $index => $principle ) : ?>
				<div class="about-principle reveal">
					<?php echo dam_icon( $icons[ $index ] ?? 'stethoscope', 24 ); ?>
					<span>0<?php echo (int) ( $index + 1 ); ?></span>
					<h3><?php echo esc_html( $principle['title'] ); ?></h3>
					<p><?php echo esc_html( $principle['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
