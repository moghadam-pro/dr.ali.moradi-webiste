<?php
/** Administrator-only, resumable Drive manifest importer. No stored credentials. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'admin_menu', function() {
	add_management_page( 'Import Patients', 'درون‌ریزی بیماران', 'manage_options', 'dam-patient-import', 'dam_patient_import_screen' );
} );
function dam_patient_import_screen() {
	if ( ! current_user_can( 'manage_options' ) ) { return; }
	?>
	<div class="wrap"><h1>درون‌ریزی بیماران / Import Patients</h1>
	<p>Manifest JSON: categories, cases, then media. Re-importing the same source IDs resumes without duplicates. Existing operator edits are preserved. Keep this page open until completed.</p>
	<p><label for="dam-import-json">Manifest JSON (paste or choose a file)</label></p><textarea id="dam-import-json" rows="8" class="large-text code"></textarea><br>
	<input type="file" id="dam-import-manifest" accept="application/json,.json"><button class="button button-primary" id="dam-import-start">Start / شروع</button>
	<p id="dam-import-progress" role="status"></p><pre id="dam-import-errors" style="white-space:pre-wrap"></pre></div>
	<script>
	(function(){
	var nonce = <?php echo wp_json_encode( wp_create_nonce( 'dam_patient_import' ) ); ?>;
	var button = document.getElementById('dam-import-start');
	button.onclick = async function(){
	 var file = document.getElementById('dam-import-manifest').files[0]; var pasted = document.getElementById('dam-import-json').value; if(!file && !pasted.trim()) return;
	 var progress = document.getElementById('dam-import-progress'), errors = document.getElementById('dam-import-errors');
	 var manifest; try { manifest = JSON.parse(pasted.trim() ? pasted : await file.text()); } catch(e) { errors.textContent = 'Invalid JSON'; return; }
	 if(!Array.isArray(manifest.operations)) { errors.textContent = 'Missing operations'; return; }
	 button.disabled = true; errors.textContent = ''; var failed = 0, completed = 0;
	 for(var i=0;i<manifest.operations.length;i++) {
	  progress.textContent = (i+1)+' / '+manifest.operations.length+' — '+manifest.operations[i].kind;
	  var body = new FormData(); body.append('action','dam_patient_import'); body.append('_ajax_nonce',nonce); body.append('operation',JSON.stringify(manifest.operations[i]));
	  try { var response = await fetch(ajaxurl,{method:'POST',body:body,credentials:'same-origin'}); var result = await response.json(); if(!result.success) throw new Error(typeof result.data === 'string' ? result.data : JSON.stringify(result.data)); completed++; }
	  catch(e) { failed++; errors.textContent += (i+1)+': '+manifest.operations[i].key+' — '+e.message+'\n'; }
	 }
	 progress.textContent = 'Completed / پایان: '+completed+'; Failed / خطا: '+failed; button.disabled = false;
	};
	})();
	</script>
	<?php
}
function dam_patient_import_find( $key, $type ) {
	$posts = get_posts( array( 'post_type' => $type, 'post_status' => 'any', 'numberposts' => 1, 'meta_key' => '_dam_drive_source_id', 'meta_value' => $key, 'lang' => '', 'suppress_filters' => true ) );
	return $posts ? $posts[0]->ID : 0;
}
function dam_patient_import_term( $key ) {
	$terms = get_terms( array( 'taxonomy' => 'patient_category', 'hide_empty' => false, 'meta_key' => '_dam_drive_source_id', 'meta_value' => $key, 'number' => 1 ) );
	return ! is_wp_error( $terms ) && $terms ? (int) $terms[0]->term_id : 0;
}
add_action( 'wp_ajax_dam_patient_import', 'dam_patient_import_operation' );
function dam_patient_import_operation() {
	check_ajax_referer( 'dam_patient_import' );
	if ( ! current_user_can( 'manage_options' ) ) { wp_send_json_error( 'Administrator access required.', 403 ); }
	$op = json_decode( wp_unslash( $_POST['operation'] ?? '' ), true );
	if ( ! is_array( $op ) || empty( $op['key'] ) || ! preg_match( '/^[a-zA-Z0-9_-]{1,100}$/', $op['key'] ) ) { wp_send_json_error( 'Invalid source key.', 400 ); }
	$key = $op['key'];
	// Serial UI requests; lock also protects another operator importing the same ID.
	$lock = 'dam_patient_import_' . md5( $key );
	if ( get_transient( $lock ) ) { wp_send_json_error( 'Source is currently being imported; retry shortly.', 409 ); }
	set_transient( $lock, true, 10 * MINUTE_IN_SECONDS );
	$result = dam_patient_import_run( $op );
	delete_transient( $lock );
	if ( is_wp_error( $result ) ) { wp_send_json_error( $result->get_error_message(), 400 ); }
	wp_send_json_success( $result );
}
/** Hash originals, including WordPress's pre-scaling original image. */
function dam_patient_import_hash_index() {
	$index = get_transient( 'dam_patient_media_hashes' );
	if ( is_array( $index ) ) { return $index; }
	$index = array();
	foreach ( get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => -1, 'fields' => 'ids', 'suppress_filters' => true, 'lang' => '' ) ) as $id ) {
		$file = get_attached_file( $id );
		$meta = wp_get_attachment_metadata( $id );
		$files = array( $file );
		if ( $file && ! empty( $meta['original_image'] ) ) { $files[] = dirname( $file ) . '/' . $meta['original_image']; }
		foreach ( $files as $path ) { if ( $path && is_file( $path ) && is_readable( $path ) ) { $index[ hash_file( 'sha256', $path ) ] = (int) $id; } }
	}
	set_transient( 'dam_patient_media_hashes', $index, HOUR_IN_SECONDS );
	return $index;
}
function dam_patient_import_run( $op ) {
	$key = $op['key'];
	if ( 'category' === $op['kind'] ) {
		$id = dam_patient_import_term( $key );
		if ( $id ) { return array( 'id' => $id, 'existing' => true ); }
		$parent = empty( $op['parent'] ) ? 0 : dam_patient_import_term( $op['parent'] );
		if ( ! empty( $op['parent'] ) && ! $parent ) { return new WP_Error( 'missing_parent', 'Parent category has not been imported.' ); }
		$term = wp_insert_term( sanitize_text_field( $op['title'] ?? '' ), 'patient_category', array( 'parent' => $parent, 'slug' => sanitize_title( $op['title'] ?? '' ) . '-' . substr( md5( $key ), 0, 6 ) ) );
		if ( is_wp_error( $term ) ) { return $term; }
		update_term_meta( $term['term_id'], '_dam_drive_source_id', $key );
		return array( 'id' => $term['term_id'] );
	}
	if ( 'patient' === $op['kind'] ) {
		$id = dam_patient_import_find( $key, 'patient' );
		if ( $id ) { return array( 'id' => $id, 'existing' => true ); }
		$terms = array();
		foreach ( (array) ( $op['categories'] ?? array() ) as $source ) {
			$term = dam_patient_import_term( $source );
			if ( ! $term ) { return new WP_Error( 'missing_category', 'Category has not been imported.' ); }
			$terms[] = $term;
		}
		$locations = dam_patient_location_ids();
		if ( ! isset( $locations[ $op['location'] ?? '' ] ) ) { return new WP_Error( 'missing_location', 'Clinic or Hospital is required.' ); }
		$terms[] = $locations[ $op['location'] ];
		$id = wp_insert_post( array( 'post_type' => 'patient', 'post_status' => 'publish', 'post_title' => sanitize_text_field( $op['title'] ?? '' ), 'post_content' => wp_kses_post( $op['content'] ?? '' ), 'post_excerpt' => sanitize_textarea_field( $op['excerpt'] ?? '' ), 'meta_input' => array( '_dam_drive_source_id' => $key, '_dam_import_location_review' => true ), 'tax_input' => array( 'patient_category' => $terms ) ), true );
		return is_wp_error( $id ) ? $id : array( 'id' => $id );
	}
	if ( 'media' !== $op['kind'] ) { return new WP_Error( 'invalid_kind', 'Unknown operation.' ); }
	$patient = dam_patient_import_find( $op['patient'] ?? '', 'patient' );
	if ( ! $patient ) { return new WP_Error( 'missing_patient', 'Patient has not been imported.' ); }
	$id = dam_patient_import_find( $key, 'attachment' );
	$hash = strtolower( $op['sha256'] ?? '' );
	if ( $hash && ! preg_match( '/^[a-f0-9]{64}$/', $hash ) ) { return new WP_Error( 'invalid_hash', 'Invalid SHA256.' ); }
	if ( ! $id && $hash ) { $index = dam_patient_import_hash_index(); $id = $index[ $hash ] ?? 0; }
	if ( ! $id ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		if ( ! empty( $op['local'] ) ) {
			// Staging is outside the web root; only flat, source-ID filenames allowed.
			if ( ! preg_match( '/^[a-zA-Z0-9_-]+\.(jpg|jpeg|png|bmp|heif|heic|mp4)$/i', $op['local'] ) ) { return new WP_Error( 'invalid_path', 'Invalid staging filename.' ); }
			$root = realpath( dirname( rtrim( ABSPATH, '/\\' ) ) . '/dam-patient-staging' );
			$tmp = $root ? realpath( $root . '/' . $op['local'] ) : false;
			if ( ! $tmp || dirname( $tmp ) !== $root || ! is_file( $tmp ) ) { return new WP_Error( 'missing_file', 'Staged file not found.' ); }
			if ( ! $hash || ! hash_equals( $hash, hash_file( 'sha256', $tmp ) ) ) { return new WP_Error( 'hash_mismatch', 'Staged file SHA256 mismatch.' ); }
		} else {
		$url = esc_url_raw( $op['url'] ?? '' );
		$host = wp_parse_url( $url, PHP_URL_HOST );
		// Only streamed connector storage URLs; never publicize private Drive files.
		if ( 'https' !== wp_parse_url( $url, PHP_URL_SCHEME ) || ! preg_match( '/^[a-z0-9-]+\.oaiusercontent\.com$/i', (string) $host ) ) { return new WP_Error( 'invalid_source', 'Expected an authenticated connector download URL.' ); }
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		if ( function_exists( 'set_time_limit' ) ) { @set_time_limit( 300 ); }
		$tmp = download_url( $url, 240 );
		if ( is_wp_error( $tmp ) ) { return $tmp; }
		if ( $hash && ! hash_equals( $hash, hash_file( 'sha256', $tmp ) ) ) { @unlink( $tmp ); return new WP_Error( 'hash_mismatch', 'Downloaded file SHA256 mismatch.' ); }
		}
		$name = sanitize_file_name( $op['filename'] ?? 'patient-image.jpg' );
		$file = array( 'name' => 'patient-' . substr( md5( $key ), 0, 12 ) . '-' . $name, 'tmp_name' => $tmp );
		$id = media_handle_sideload( $file, $patient, sanitize_text_field( $op['title'] ?? '' ) );
		if ( is_wp_error( $id ) ) { if ( empty( $op['local'] ) ) { @unlink( $tmp ); } return $id; }
		if ( $hash ) { $index = dam_patient_import_hash_index(); $index[ $hash ] = (int) $id; set_transient( 'dam_patient_media_hashes', $index, HOUR_IN_SECONDS ); }
	}
	if ( ! in_array( $key, get_post_meta( $id, '_dam_drive_source_id', false ), true ) ) { add_post_meta( $id, '_dam_drive_source_id', $key, false ); }
	$gallery = (array) get_post_meta( $patient, 'dam_patient_gallery', true );
	$orders = (array) get_post_meta( $patient, '_dam_patient_import_orders', true );
	$orders[ $id ] = absint( $op['order'] ?? 0 );
	update_post_meta( $patient, '_dam_patient_import_orders', $orders );
	$ids = array_column( $gallery, 'id' );
	if ( ! in_array( (int) $id, array_map( 'intval', $ids ), true ) ) {
		$gallery[] = array( 'id' => (int) $id, 'url' => wp_get_attachment_url( $id ), 'type' => str_starts_with( (string) get_post_mime_type( $id ), 'video/' ) ? 'video' : 'image', 'title' => '', 'description' => '', 'order' => absint( $op['order'] ?? 0 ) );
		usort( $gallery, function( $a, $b ) use ( $orders ) { return (int) ( $orders[ $a['id'] ] ?? get_post_meta( $a['id'], '_dam_patient_media_order', true ) ) <=> (int) ( $orders[ $b['id'] ] ?? get_post_meta( $b['id'], '_dam_patient_media_order', true ) ); } );
		update_post_meta( $patient, 'dam_patient_gallery', $gallery );
	}
	if ( wp_attachment_is_image( $id ) && ! has_post_thumbnail( $patient ) ) { set_post_thumbnail( $patient, $id ); }
	return array( 'id' => $id );
}
