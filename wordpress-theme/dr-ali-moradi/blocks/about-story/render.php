<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$copy   = dam_about_page_copy( $locale );
$image  = dam_media_url( 'dr-moradi-hero-v1' );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'about-story section-space' ) ); ?>>
	<div class="section-shell about-story-grid">
		<div class="about-story-media reveal"><img class="fill-img" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $copy['storyTitle'] ); ?>"></div>
		<div class="about-story-copy reveal">
			<p class="section-index"><?php echo esc_html( $copy['storyKicker'] ); ?></p>
			<h2><?php echo esc_html( $copy['storyTitle'] ); ?></h2>
			<?php foreach ( $copy['storyText'] as $paragraph ) : ?><p><?php echo esc_html( $paragraph ); ?></p><?php endforeach; ?>
			<div class="about-credentials">
				<?php foreach ( $copy['credentials'] as $credential ) : ?>
					<span><?php echo dam_icon( 'check', 15 ); ?><?php echo esc_html( $credential ); ?></span>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
