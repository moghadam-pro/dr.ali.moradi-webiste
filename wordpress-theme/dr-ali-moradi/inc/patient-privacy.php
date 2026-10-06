<?php
/** Explicit, guarded redaction of identifying Patient names and media paths. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function dam_patient_privacy_initials( $name ) {
	$parts = preg_split( '/\s+/u', trim( (string) $name ), -1, PREG_SPLIT_NO_EMPTY );
	$initials = array();
	foreach ( $parts as $part ) {
		if ( preg_match( '/^\X/u', $part, $match ) ) { $initials[] = $match[0] . '.'; }
	}
	return implode( ' ', $initials );
}

function dam_patient_privacy_replace( $value, $original, $abbreviated ) {
	if ( is_string( $value ) ) { return str_replace( $original, $abbreviated, $value ); }
	if ( ! is_array( $value ) ) { return $value; }
	foreach ( $value as $key => $part ) { $value[ $key ] = dam_patient_privacy_replace( $part, $original, $abbreviated ); }
	return $value;
}

/** WordPress retains the original GUID when an existing post is updated. */
function dam_patient_privacy_guid( $id, $guid ) {
	global $wpdb;
	$post = get_post( $id );
	if ( ! $post ) { return new WP_Error( 'invalid_post', 'Post no longer exists.' ); }
	if ( $post->guid === $guid ) { return true; }
	$updated = $wpdb->update( $wpdb->posts, array( 'guid' => $guid ), array( 'ID' => $id ), array( '%s' ), array( '%d' ) );
	if ( false === $updated ) { return new WP_Error( 'guid_failed', 'Could not neutralize the original GUID.' ); }
	clean_post_cache( $id );
	return true;
}

function dam_patient_privacy_patient( $op ) {
	$id = absint( $op['id'] ?? 0 );
	$post = $id ? get_post( $id ) : null;
	if ( ! $post || 'patient' !== $post->post_type || ! current_user_can( 'edit_post', $id ) ) { return new WP_Error( 'invalid_patient', 'Editable Patient required.' ); }
	$guid = 'urn:dralimoradi:patient:' . $id;
	if ( get_post_meta( $id, '_dam_patient_privacy_version', true ) ) {
		$updated = dam_patient_privacy_guid( $id, $guid );
		return is_wp_error( $updated ) ? $updated : array( 'id' => $id, 'status' => 'already-redacted' );
	}
	$expected = $op['expected_title_sha256'] ?? '';
	if ( ! preg_match( '/^[a-f0-9]{64}$/', $expected ) || ! hash_equals( $expected, hash( 'sha256', trim( $post->post_title ) ) ) ) { return new WP_Error( 'changed_title', 'Patient title changed since private export.' ); }
	$original = trim( $post->post_title );
	if ( str_contains( $original, '— Case' ) || str_starts_with( $original, 'پرونده' ) ) { return new WP_Error( 'not_person_name', 'Clinical case title must not be abbreviated.' ); }
	$abbreviated = dam_patient_privacy_initials( $original );
	if ( ! $abbreviated || $abbreviated === $original ) { return new WP_Error( 'invalid_name', 'Cannot abbreviate this title.' ); }
	$result = wp_update_post( wp_slash( array(
		'ID' => $id,
		'post_title' => $abbreviated,
		'post_name' => 'case-' . $id,
		'post_content' => dam_patient_privacy_replace( $post->post_content, $original, $abbreviated ),
		'post_excerpt' => dam_patient_privacy_replace( $post->post_excerpt, $original, $abbreviated ),
	) ), true );
	if ( is_wp_error( $result ) ) { return $result; }
	foreach ( array( 'dam_patient_gallery', 'dam_patient_translations', 'rank_math_title', 'rank_math_description', 'rank_math_focus_keyword' ) as $key ) {
		$value = get_post_meta( $id, $key, true );
		$redacted = dam_patient_privacy_replace( $value, $original, $abbreviated );
		if ( $redacted !== $value ) { update_post_meta( $id, $key, $redacted ); }
	}
	// WordPress otherwise exposes the old full-name path through old-slug redirects.
	delete_post_meta( $id, '_wp_old_slug' );
	update_post_meta( $id, '_dam_patient_privacy_version', 1 );
	$updated = dam_patient_privacy_guid( $id, $guid );
	if ( is_wp_error( $updated ) ) { return $updated; }
	if ( class_exists( '\\RankMath\\Sitemap\\Cache' ) ) { \RankMath\Sitemap\Cache::invalidate_storage(); }
	return array( 'id' => $id, 'status' => 'redacted', 'title' => $abbreviated, 'slug' => 'case-' . $id );
}

/** Prepare a reversible set of same-directory moves for every registered size. */
function dam_patient_privacy_media_plan( $id, $relative, $meta, $file ) {
	if ( ! $relative || ! $file || ! is_array( $meta ) || empty( $meta['file'] ) ) { return new WP_Error( 'invalid_media', 'Complete image metadata required.' ); }
	$dir = dirname( $file );
	$extension = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
	if ( ! preg_match( '/^(jpe?g|png|webp|gif|heic|heif)$/', $extension ) ) { return new WP_Error( 'invalid_media', 'Image attachment required.' ); }
	$base = 'case-media-' . $id;
	$new_file = $base . '.' . $extension;
	$planned = array( $file => $dir . '/' . $new_file );
	foreach ( (array) ( $meta['sizes'] ?? array() ) as $size => $details ) {
		if ( empty( $details['file'] ) || basename( $details['file'] ) !== $details['file'] ) { return new WP_Error( 'invalid_size', 'Unexpected derived filename.' ); }
		$size_extension = strtolower( pathinfo( $details['file'], PATHINFO_EXTENSION ) );
		$new_size = $base . '-' . sanitize_file_name( $size ) . '.' . $size_extension;
		$planned[ $dir . '/' . $details['file'] ] = $dir . '/' . $new_size;
		$meta['sizes'][ $size ]['file'] = $new_size;
	}
	if ( ! empty( $meta['original_image'] ) ) {
		$old_original = $meta['original_image'];
		if ( basename( $old_original ) !== $old_original ) { return new WP_Error( 'invalid_original', 'Unexpected original filename.' ); }
		$new_original = $base . '-original.' . strtolower( pathinfo( $old_original, PATHINFO_EXTENSION ) );
		$planned[ $dir . '/' . $old_original ] = $dir . '/' . $new_original;
		$meta['original_image'] = $new_original;
	}
	$subdirectory = trim( dirname( $relative ), './' );
	$meta['file'] = ( $subdirectory ? $subdirectory . '/' : '' ) . $new_file;
	return array( 'moves' => $planned, 'meta' => $meta, 'new_path' => $dir . '/' . $new_file );
}

function dam_patient_privacy_media( $op ) {
	$id = absint( $op['id'] ?? 0 );
	$post = $id ? get_post( $id ) : null;
	if ( ! $post || 'attachment' !== $post->post_type || 'patient' !== get_post_type( $post->post_parent ) || ! wp_attachment_is_image( $id ) || ! current_user_can( 'edit_post', $id ) ) { return new WP_Error( 'invalid_media', 'Patient image attachment required.' ); }
	if ( get_post_meta( $id, '_dam_patient_privacy_media_version', true ) ) {
		$updated = dam_patient_privacy_guid( $id, wp_get_attachment_url( $id ) );
		return is_wp_error( $updated ) ? $updated : array( 'id' => $id, 'status' => 'already-redacted' );
	}
	$relative = get_post_meta( $id, '_wp_attached_file', true );
	$expected = $op['expected_path_sha256'] ?? '';
	if ( ! preg_match( '/^[a-f0-9]{64}$/', $expected ) || ! hash_equals( $expected, hash( 'sha256', $relative ) ) ) { return new WP_Error( 'changed_path', 'Attachment path changed since private export.' ); }
	$file = get_attached_file( $id );
	$uploads = wp_upload_dir();
	$root = realpath( $uploads['basedir'] ?? '' );
	$dir = $file ? realpath( dirname( $file ) ) : false;
	if ( ! $root || ! $dir || ! str_starts_with( $dir . '/', rtrim( $root, '/' ) . '/' ) ) { return new WP_Error( 'invalid_path', 'Attachment is outside the uploads directory.' ); }
	$original_meta = wp_get_attachment_metadata( $id );
	$plan = dam_patient_privacy_media_plan( $id, $relative, $original_meta, $file );
	if ( is_wp_error( $plan ) ) { return $plan; }
	foreach ( $plan['moves'] as $from => $to ) {
		if ( ! is_file( $from ) || file_exists( $to ) || realpath( dirname( $from ) ) !== $dir || realpath( dirname( $to ) ) !== $dir ) { return new WP_Error( 'file_conflict', 'Original or derived file missing, or destination exists.' ); }
	}
	$moved = array();
	foreach ( $plan['moves'] as $from => $to ) {
		if ( ! rename( $from, $to ) ) {
			foreach ( array_reverse( $moved, true ) as $old => $new ) { rename( $new, $old ); }
			return new WP_Error( 'move_failed', 'Could not rename image set; moved files were restored.' );
		}
		$moved[ $from ] = $to;
	}
	if ( ! update_attached_file( $id, $plan['new_path'] ) || ! wp_update_attachment_metadata( $id, $plan['meta'] ) ) {
		update_attached_file( $id, $file );
		wp_update_attachment_metadata( $id, $original_meta );
		foreach ( array_reverse( $moved, true ) as $old => $new ) { rename( $new, $old ); }
		return new WP_Error( 'metadata_failed', 'Attachment metadata failed; files were restored.' );
	}
	$url = wp_get_attachment_url( $id );
	wp_update_post( array( 'ID' => $id, 'post_title' => 'Patient media ' . $id, 'post_name' => 'case-media-' . $id, 'post_excerpt' => '', 'post_content' => '' ) );
	update_post_meta( $id, '_wp_attachment_image_alt', '' );
	delete_post_meta( $id, '_wp_old_slug' );
	$gallery = get_post_meta( $post->post_parent, 'dam_patient_gallery', true );
	if ( is_array( $gallery ) ) {
		foreach ( $gallery as &$item ) { if ( (int) ( $item['id'] ?? 0 ) === $id ) { $item['url'] = $url; } }
		unset( $item );
		update_post_meta( $post->post_parent, 'dam_patient_gallery', $gallery );
	}
	update_post_meta( $id, '_dam_patient_privacy_media_version', 1 );
	$updated = dam_patient_privacy_guid( $id, $url );
	if ( is_wp_error( $updated ) ) { return $updated; }
	return array( 'id' => $id, 'status' => 'redacted', 'file' => basename( $plan['new_path'] ) );
}

function dam_patient_privacy_run( $op ) {
	if ( ! current_user_can( 'manage_options' ) ) { return new WP_Error( 'forbidden', 'Administrator access required.' ); }
	if ( 'anonymize_patient' === ( $op['kind'] ?? '' ) ) { return dam_patient_privacy_patient( $op ); }
	if ( 'anonymize_media' === ( $op['kind'] ?? '' ) ) { return dam_patient_privacy_media( $op ); }
	return new WP_Error( 'invalid_kind', 'Unknown privacy operation.' );
}
