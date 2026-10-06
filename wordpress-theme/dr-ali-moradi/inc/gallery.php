<?php
/** Live patient galleries, retaining the existing gallery page URLs. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function dam_render_gallery_row( $area, $title, $intro, $view_all_url, $dark = false ) {
	$hub = dam_clinic_hub_copy( dam_current_locale() );
	$locations = dam_patient_location_ids();
	$items = isset( $locations[ $area ] ) ? dam_patient_gallery_items( $locations[ $area ], 80 ) : array();
	?>
	<section class="clinic-gallery-row<?php echo $dark ? ' hospital-gallery-row' : ''; ?> section-space">
	<div class="section-shell"><div class="section-heading split-heading reveal">
	<div><p class="section-index"><?php echo esc_html( $title ); ?></p><p><?php echo esc_html( $intro ); ?></p></div>
	<a class="text-link" href="<?php echo esc_url( $view_all_url ); ?>"><?php echo esc_html( $hub['viewGallery'] ); ?><?php echo dam_icon( 'arrow-right', 17 ); ?></a>
	</div><?php dam_render_patient_gallery( $items, true ); ?></div>
	</section>
	<?php
}
function dam_render_gallery_full( $area, $title ) {
	$locations = dam_patient_location_ids();
	dam_render_patient_archive( $locations[ $area ] ?? 0, $title, 2 );
}
function dam_render_gallery_modal() {
	$hub = dam_clinic_hub_copy( dam_current_locale() );
	?>
	<div class="gallery-modal" data-gallery-modal hidden role="dialog" aria-modal="true" aria-labelledby="patient-modal-title" aria-describedby="patient-modal-description">
	<div class="gallery-modal-panel">
	<button type="button" class="gallery-modal-close" data-gallery-close aria-label="<?php echo esc_attr( $hub['close'] ); ?>"><?php echo dam_icon( 'x', 20 ); ?></button>
	<div class="gallery-modal-image"><img data-gallery-modal-image alt=""><video data-gallery-modal-video controls playsinline hidden></video><a data-gallery-modal-video-link hidden target="_blank" rel="noopener noreferrer"><?php echo esc_html( dam_patient_label( 'Watch video', 'نمایش ویدیو', 'مشاهدة الفيديو' ) ); ?></a><div class="gallery-modal-sensitive" data-gallery-modal-sensitive hidden><p><?php echo esc_html( dam_patient_label( 'Sensitive clinical image', 'تصویر حساس پزشکی', 'صورة طبية حساسة' ) ); ?></p><button type="button" data-gallery-modal-reveal><?php echo esc_html( dam_patient_label( 'Show image', 'نمایش تصویر', 'عرض الصورة' ) ); ?></button></div></div>
	<div class="gallery-modal-caption"><h2 id="patient-modal-title" data-gallery-modal-title></h2><p id="patient-modal-description" data-gallery-modal-description></p><a data-gallery-modal-patient><?php echo esc_html( dam_patient_label( 'View patient', 'مشاهده صفحه بیمار', 'عرض المريض' ) ); ?></a><span data-gallery-modal-count></span></div>
	<button type="button" class="gallery-modal-nav gallery-modal-prev" data-gallery-modal-prev aria-label="<?php echo esc_attr( $hub['previous'] ); ?>"><?php echo dam_icon( 'chevron-left', 22 ); ?></button>
	<button type="button" class="gallery-modal-nav gallery-modal-next" data-gallery-modal-next aria-label="<?php echo esc_attr( $hub['next'] ); ?>"><?php echo dam_icon( 'chevron-right', 22 ); ?></button>
	</div></div>
	<?php
}
add_action( 'wp_footer', 'dam_render_gallery_modal' );
