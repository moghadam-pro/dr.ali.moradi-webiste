<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$copy   = dam_about_page_copy( $locale );
$image  = dam_media_url( 'doctor' );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'about-journey section-space' ) ); ?>>
	<div class="section-shell about-journey-grid">
		<div class="about-journey-intro reveal">
			<p class="section-index"><?php echo esc_html( $copy['journeyKicker'] ); ?></p>
			<h2><?php echo esc_html( $copy['journeyTitle'] ); ?></h2>
			<p><?php echo esc_html( $copy['journeyText'] ); ?></p>
			<div class="about-journey-image"><img class="fill-img" src="<?php echo esc_url( $image ); ?>" alt=""></div>
		</div>
		<div class="about-timeline">
			<?php foreach ( $copy['timeline'] as $item ) : ?>
				<div class="about-timeline-item reveal">
					<span><?php echo esc_html( $item['years'] ); ?></span>
					<div><h3><?php echo esc_html( $item['title'] ); ?></h3><p><?php echo esc_html( $item['text'] ); ?></p></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
