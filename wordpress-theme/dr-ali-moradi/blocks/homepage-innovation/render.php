<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$kicker   = dam_theme_mod( 'innovation_kicker', $locale );
$title    = dam_theme_mod( 'innovation_title', $locale );
$subtitle = dam_theme_mod( 'innovation_subtitle', $locale );
$columns  = max( 1, min( 3, (int) dam_theme_mod( 'innovation_columns', $locale ) ) );
$project_keys = array( 'magnetic-control-artificial-limb', 'magnetic-joint-distraction', 'dynamic-distal-radius-external-fixator' );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'innovation section-space section-shell' ) ); ?>>
	<div class="section-heading reveal">
		<p class="section-index"><?php echo esc_html( $kicker ); ?></p>
		<p><?php echo esc_html( $title ); ?>. <?php echo esc_html( $subtitle ); ?></p>
	</div>
	<div class="innovation-grid card-grid-columns-<?php echo esc_attr( $columns ); ?>">
		<?php for ( $i = 1; $i <= 3; $i++ ) :
			$tag         = dam_theme_mod( "innovation_card_{$i}_tag", $locale );
			$card_title  = dam_theme_mod( "innovation_card_{$i}_title", $locale );
			$body        = dam_theme_mod( "innovation_card_{$i}_body", $locale );
			$link        = dam_theme_mod( "innovation_card_{$i}_url", $locale );
			$image       = dam_theme_mod( "innovation_card_{$i}_image", $locale );
			$link_label  = dam_theme_mod( "innovation_card_{$i}_button_label", $locale );
			// Follow the selected project's localized Page and current featured image.
			$page_id = dam_innovation_page_find( $project_keys[ $i - 1 ] . ':' . $locale );
			if ( $page_id && 'publish' === get_post_status( $page_id ) ) {
				$link = get_permalink( $page_id );
				$card_title = get_the_title( $page_id );
				$body = get_post_field( 'post_excerpt', $page_id );
				$project_image = get_the_post_thumbnail_url( $page_id, 'large' );
				// Designed Pages may use a cover in their editable HTML instead.
				if ( ! $project_image && preg_match( '/<img\b[^>]*\bsrc=["\x27]([^"\x27]+)["\x27]/i', get_post_field( 'post_content', $page_id ), $cover ) ) {
					$project_image = html_entity_decode( $cover[1], ENT_QUOTES, 'UTF-8' );
				}
				if ( $project_image ) {
					$image = $project_image;
				}
			}
			?>
			<div class="innovation-card reveal">
				<div class="innovation-art">
					<?php if ( $image ) : ?>
						<img class="fill-img" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $card_title ); ?>" loading="lazy">
					<?php endif; ?>
				</div>
				<?php if ( $tag ) : ?><p class="card-tag"><?php echo esc_html( $tag ); ?></p><?php endif; ?>
				<h3><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $card_title ); ?></a></h3>
				<p><?php echo esc_html( $body ); ?></p>
				<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $link_label ); ?><?php echo dam_icon( 'arrow-right', 16 ); ?></a>
			</div>
		<?php endfor; ?>
	</div>
</section>
