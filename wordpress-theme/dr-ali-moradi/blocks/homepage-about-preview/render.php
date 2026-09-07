<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale  = dam_current_locale();
$t       = dam_site_copy( $locale );
$about_url    = dam_localized_page_url( 'about', $locale );
$research_url = dam_localized_page_url( 'research', $locale );
$office_image = dam_media_url( 'office', DAM_THEME_URI . '/assets/img/about/office.jpg' );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'about-preview section-space' ) ); ?>>
	<div class="section-shell about-grid">
		<div class="about-copy reveal">
			<p class="section-index light"><?php echo esc_html( $t['aboutIndex'] ); ?></p>
			<p><?php echo esc_html( $t['aboutBody'] ); ?></p>
			<div class="about-actions">
				<a class="button button-light" href="<?php echo esc_url( $about_url ); ?>"><?php echo esc_html( $t['meetDoctor'] ); ?><?php echo dam_icon( 'arrow-right', 17 ); ?></a>
				<a class="text-link light" href="<?php echo esc_url( $research_url ); ?>"><?php echo esc_html( $t['researchProfile'] ); ?><?php echo dam_icon( 'file-text', 17 ); ?></a>
			</div>
		</div>
		<div class="about-media reveal">
			<div class="about-rings" aria-hidden="true"></div>
			<div class="pulse-line" aria-hidden="true"></div>
			<img class="fill-img" src="<?php echo esc_url( $office_image ); ?>" alt="">
		</div>
	</div>
</section>
