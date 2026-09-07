<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale   = dam_current_locale();
$copy     = dam_about_page_copy( $locale );
$image    = dam_media_url( 'blog-cover' );
$blog_url = dam_localized_page_url( 'blog', $locale );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'about-recognition section-space' ) ); ?>>
	<div class="section-shell about-recognition-grid reveal">
		<div class="about-recognition-image"><img class="fill-img" src="<?php echo esc_url( $image ); ?>" alt=""></div>
		<div>
			<p class="section-index"><?php echo esc_html( $copy['recognitionKicker'] ); ?></p>
			<h2><?php echo esc_html( $copy['recognitionTitle'] ); ?></h2>
			<p><?php echo esc_html( $copy['recognitionText'] ); ?></p>
			<a class="button" href="<?php echo esc_url( $blog_url ); ?>"><?php echo esc_html( $copy['recognitionAction'] ); ?><?php echo dam_icon( 'arrow-right', 16 ); ?></a>
		</div>
	</div>
</section>
