<?php
/**
 * Shared renderer for a "gallery row" (a 4-thumbnail rotating preview
 * strip with a lightbox, linking to a full-gallery page) -- used twice
 * on the Clinical Care hub (clinic cases, hospital cases). The
 * rotation/lightbox behavior itself lives in assets/js/site.js; this
 * only prints the initial markup and the full image list as a data
 * attribute for that script to read.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dam_render_gallery_row( $area, $title, $intro, $view_all_url, $dark = false ) {
	$locale = dam_current_locale();
	$hub    = dam_clinic_hub_copy( $locale );
	$images = dam_gallery_images( $area );

	$row_class = 'clinic-gallery-row' . ( $dark ? ' hospital-gallery-row' : '' );
	?>
	<section class="<?php echo esc_attr( $row_class ); ?> section-space">
		<div class="section-shell">
			<div class="section-heading split-heading reveal">
				<div><p class="section-index"><?php echo esc_html( $title ); ?></p><p><?php echo esc_html( $intro ); ?></p></div>
				<a class="text-link" href="<?php echo esc_url( $view_all_url ); ?>"><?php echo esc_html( $hub['viewGallery'] ); ?><?php echo dam_icon( 'arrow-right', 17 ); ?></a>
			</div>
			<div class="gallery-strip" data-gallery-strip data-images="<?php echo esc_attr( wp_json_encode( $images ) ); ?>" data-title="<?php echo esc_attr( $title ); ?>">
				<button type="button" class="gallery-nav gallery-prev" data-gallery-prev aria-label="<?php echo esc_attr( $hub['previous'] ); ?>"><?php echo dam_icon( 'chevron-left', 20 ); ?></button>
				<div class="gallery-strip-track">
					<?php for ( $i = 0; $i < 4 && $i < count( $images ); $i++ ) : ?>
						<button type="button" class="gallery-thumb" data-gallery-thumb data-index="<?php echo (int) $i; ?>" aria-label="<?php echo esc_attr( $title . ' ' . ( $i + 1 ) ); ?>">
							<img class="fill-img" src="<?php echo esc_url( $images[ $i ] ); ?>" alt="<?php echo esc_attr( $title . ' ' . ( $i + 1 ) ); ?>">
							<span><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
						</button>
					<?php endfor; ?>
				</div>
				<button type="button" class="gallery-nav gallery-next" data-gallery-next aria-label="<?php echo esc_attr( $hub['next'] ); ?>"><?php echo dam_icon( 'chevron-right', 20 ); ?></button>
			</div>
		</div>
	</section>
	<?php
}

/**
 * One shared lightbox modal per page, reused by every gallery-strip
 * instance (site.js fills in the image and count when a thumbnail is
 * clicked). Printed once in the footer so it exists regardless of how
 * many gallery rows a page has.
 */
function dam_render_gallery_modal() {
	// Printed on every page load: it's a small, hidden, inert block of
	// markup, so it's simpler to always have it available than to try
	// to detect in advance whether the current page contains a gallery
	// row.
	$locale = dam_current_locale();
	$hub    = dam_clinic_hub_copy( $locale );
	?>
	<div class="gallery-modal" data-gallery-modal hidden role="dialog" aria-modal="true">
		<div class="gallery-modal-panel">
			<button type="button" class="gallery-modal-close" data-gallery-close aria-label="<?php echo esc_attr( $hub['close'] ); ?>"><?php echo dam_icon( 'x', 20 ); ?></button>
			<div class="gallery-modal-image"><img data-gallery-modal-image src="" alt=""></div>
			<button type="button" class="gallery-modal-nav gallery-modal-prev" data-gallery-modal-prev aria-label="<?php echo esc_attr( $hub['previous'] ); ?>"><?php echo dam_icon( 'chevron-left', 22 ); ?></button>
			<button type="button" class="gallery-modal-nav gallery-modal-next" data-gallery-modal-next aria-label="<?php echo esc_attr( $hub['next'] ); ?>"><?php echo dam_icon( 'chevron-right', 22 ); ?></button>
			<span class="gallery-modal-count" data-gallery-modal-count></span>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'dam_render_gallery_modal' );

/** Full (non-rotating) grid of every image in a gallery, for the
 * dedicated "view full gallery" pages, with the same lightbox. */
function dam_render_gallery_full( $area, $title ) {
	$locale = dam_current_locale();
	$hub    = dam_clinic_hub_copy( $locale );
	$images = dam_gallery_images( $area );
	?>
	<section class="gallery-page section-space section-shell">
		<div class="gallery-full-grid" data-gallery-strip data-images="<?php echo esc_attr( wp_json_encode( $images ) ); ?>" data-title="<?php echo esc_attr( $title ); ?>">
			<div class="gallery-full-track">
				<?php foreach ( $images as $i => $image ) : ?>
					<button type="button" class="gallery-thumb" data-gallery-thumb data-index="<?php echo (int) $i; ?>" aria-label="<?php echo esc_attr( $title . ' ' . ( $i + 1 ) ); ?>">
						<img class="fill-img" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title . ' ' . ( $i + 1 ) ); ?>">
						<span><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}
