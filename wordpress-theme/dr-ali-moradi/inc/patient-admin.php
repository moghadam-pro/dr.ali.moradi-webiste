<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'add_meta_boxes_patient', function() {
	add_meta_box( 'dam-patient-gallery', dam_patient_label( 'Patient gallery', 'گالری بیمار', 'معرض المريض' ), 'dam_patient_gallery_metabox', 'patient', 'normal', 'high' );
	add_meta_box( 'dam-patient-translations', 'English / فارسی / العربية', 'dam_patient_translations_metabox', 'patient', 'normal' );
} );
function dam_patient_translations_metabox( $post ) {
	wp_nonce_field( 'dam_patient_translations', 'dam_patient_translations_nonce' );
	$values = (array) get_post_meta( $post->ID, 'dam_patient_translations', true );
	echo '<p>ترجمهٔ عنوان، توضیح کوتاه و شرح بیمار؛ فیلد خالی از محتوای اصلی استفاده می‌کند. رسانه‌ها مشترک هستند.</p>';
	foreach ( array( 'en' => 'English', 'fa' => 'فارسی', 'ar' => 'العربية' ) as $lang => $label ) {
		echo '<details><summary>' . esc_html( $label ) . '</summary>';
		foreach ( array( 'title' => 'Title / عنوان', 'excerpt' => 'Short description / توضیح کوتاه', 'content' => 'Case and treatment / شرح و اقدامات' ) as $field => $name ) {
			$key = 'dam-patient-' . $lang . '-' . $field;
			echo '<p><label for="' . esc_attr( $key ) . '">' . esc_html( $name ) . '</label><textarea class="large-text" id="' . esc_attr( $key ) . '" dir="' . ( 'en' === $lang ? 'ltr' : 'rtl' ) . '" name="dam_patient_translations[' . esc_attr( $lang ) . '][' . esc_attr( $field ) . ']" rows="' . ( 'content' === $field ? '6' : '2' ) . '">' . esc_textarea( $values[ $lang ][ $field ] ?? '' ) . '</textarea></p>';
		}
		echo '</details>';
	}
}
add_action( 'save_post_patient', function( $id ) {
	if ( wp_is_post_revision( $id ) || wp_is_post_autosave( $id ) || ! current_user_can( 'edit_post', $id ) || empty( $_POST['dam_patient_translations_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['dam_patient_translations_nonce'] ) ), 'dam_patient_translations' ) ) { return; }
	$values = array();
	foreach ( array( 'en', 'fa', 'ar' ) as $lang ) {
		foreach ( array( 'title', 'excerpt', 'content' ) as $field ) {
			$value = wp_unslash( $_POST['dam_patient_translations'][ $lang ][ $field ] ?? '' );
			if ( trim( $value ) ) { $values[ $lang ][ $field ] = 'content' === $field ? wp_kses_post( $value ) : sanitize_textarea_field( $value ); }
		}
	}
	update_post_meta( $id, 'dam_patient_translations', $values );
}, 20 );
function dam_patient_term_translation_fields( $term ) {
	wp_nonce_field( 'dam_patient_term_translation', 'dam_patient_term_translation_nonce' );
	foreach ( array( 'en', 'fa', 'ar' ) as $lang ) {
		echo '<tr class="form-field"><th><label for="dam-name-' . $lang . '">' . strtoupper( $lang ) . ' title</label></th><td><input id="dam-name-' . $lang . '" name="dam_patient_names[' . $lang . ']" value="' . esc_attr( get_term_meta( $term->term_id, 'dam_patient_name_' . $lang, true ) ) . '"></td></tr>';
	}
}
foreach ( array( 'patient_category', 'patient_tag' ) as $taxonomy ) { add_action( $taxonomy . '_edit_form_fields', 'dam_patient_term_translation_fields' ); }
function dam_patient_save_term_translations( $id ) {
	if ( ! current_user_can( 'manage_categories' ) || empty( $_POST['dam_patient_term_translation_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['dam_patient_term_translation_nonce'] ) ), 'dam_patient_term_translation' ) ) { return; }
	foreach ( array( 'en', 'fa', 'ar' ) as $lang ) { update_term_meta( $id, 'dam_patient_name_' . $lang, sanitize_text_field( wp_unslash( $_POST['dam_patient_names'][ $lang ] ?? '' ) ) ); }
}
foreach ( array( 'patient_category', 'patient_tag' ) as $taxonomy ) { add_action( 'edited_' . $taxonomy, 'dam_patient_save_term_translations' ); }
function dam_patient_gallery_metabox( $post ) {
	wp_nonce_field( 'dam_patient_gallery', 'dam_patient_gallery_nonce' );
	$items = dam_sanitize_patient_gallery( get_post_meta( $post->ID, 'dam_patient_gallery', true ) );
	?>
	<p><?php echo esc_html( dam_patient_label( 'Use the title for the patient name, excerpt for the short description, and editor for the case and treatment details. Select exactly one Clinic/Hospital category, alongside any disease categories.', 'نام بیمار در عنوان، توضیح کوتاه در چکیده و شرح بیمار و اقدامات در ویرایشگر درج شود. کنار دسته‌های بیماری دقیقاً یکی از دسته‌های کلینیک یا بیمارستان را انتخاب کنید.', 'استخدم العنوان للاسم والمقتطف للوصف والمحرر لتفاصيل العلاج. اختر العيادة أو المستشفى مع تصنيفات الحالة.' ) ); ?></p>
	<div id="dam-patient-gallery-editor"></div>
	<input type="hidden" id="dam-patient-gallery-data" name="dam_patient_gallery_data" value="<?php echo esc_attr( wp_json_encode( $items ) ); ?>">
	<button type="button" class="button" id="dam-patient-add-media"><?php echo esc_html( dam_patient_label( 'Add photos / videos', 'افزودن عکس / فیلم', 'إضافة صور / فيديو' ) ); ?></button>
	<button type="button" class="button" id="dam-patient-add-link"><?php echo esc_html( dam_patient_label( 'Add video link', 'افزودن لینک ویدیو', 'إضافة رابط فيديو' ) ); ?></button>
	<?php
}
add_action( 'admin_enqueue_scripts', function() {
	$screen = get_current_screen();
	if ( ! $screen || 'patient' !== $screen->post_type || 'post' !== $screen->base ) { return; }
	wp_enqueue_media();
	wp_enqueue_script( 'dam-patient-admin', DAM_THEME_URI . '/assets/js/patient-admin.js', array( 'jquery', 'media-views' ), DAM_THEME_VERSION, true );
	wp_localize_script( 'dam-patient-admin', 'damPatientEditor', array(
		'title' => dam_patient_label( 'Title', 'عنوان', 'العنوان' ), 'description' => dam_patient_label( 'Description', 'توضیح', 'الوصف' ),
		'sensitive' => dam_patient_label( 'Blur sensitive media until the visitor chooses to view it', 'تاری رسانهٔ حساس تا وقتی بازدیدکننده نمایش را انتخاب کند', 'طمس الوسائط الحساسة حتى يختار الزائر عرضها' ),
		'url' => dam_patient_label( 'Video URL', 'لینک ویدیو', 'رابط الفيديو' ), 'remove' => dam_patient_label( 'Remove', 'حذف', 'حذف' ),
		'up' => dam_patient_label( 'Move up', 'انتقال به بالا', 'نقل للأعلى' ), 'down' => dam_patient_label( 'Move down', 'انتقال به پایین', 'نقل للأسفل' ),
	) );
} );
add_action( 'save_post_patient', function( $id ) {
	if ( wp_is_post_revision( $id ) || wp_is_post_autosave( $id ) || ! current_user_can( 'edit_post', $id ) || ! isset( $_POST['dam_patient_gallery_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['dam_patient_gallery_nonce'] ) ), 'dam_patient_gallery' ) ) { return; }
	$items = json_decode( wp_unslash( $_POST['dam_patient_gallery_data'] ?? '[]' ), true );
	if ( is_array( $items ) ) { update_post_meta( $id, 'dam_patient_gallery', dam_sanitize_patient_gallery( $items ) ); }
}, 20 );
