<?php
/** Guarded privacy checks with explicitly synthetic names; no real patient fixtures. */
define( 'ABSPATH', __DIR__ );
class WP_Error { public function __construct( public $code, public $message ) {} }
function is_wp_error( $value ) { return $value instanceof WP_Error; }
function absint( $value ) { return abs( (int) $value ); }
function current_user_can( $capability, ...$args ) { return true; }
function get_post( $id ) { return $GLOBALS['posts'][ $id ] ?? null; }
function get_post_meta( $id, $key, $single = true ) { return $GLOBALS['meta'][ $id ][ $key ] ?? ''; }
function update_post_meta( $id, $key, $value ) { $GLOBALS['meta'][ $id ][ $key ] = $value; }
function delete_post_meta( $id, $key ) { unset( $GLOBALS['meta'][ $id ][ $key ] ); }
function wp_slash( $value ) { return $value; }
function sanitize_file_name( $value ) { return preg_replace( '/[^a-zA-Z0-9_-]/', '-', $value ); }
function get_post_type( $id ) { return $GLOBALS['posts'][ $id ]->post_type ?? ''; }
function wp_attachment_is_image( $id ) { return 853 === $id; }
function get_attached_file( $id ) { return $GLOBALS['attached'][ $id ] ?? false; }
function update_attached_file( $id, $path ) { $GLOBALS['attached'][ $id ] = $path; $GLOBALS['meta'][ $id ]['_wp_attached_file'] = substr( $path, strlen( $GLOBALS['upload_root'] ) + 1 ); return true; }
function wp_get_attachment_metadata( $id ) { return $GLOBALS['meta'][ $id ]['_wp_attachment_metadata'] ?? false; }
function wp_update_attachment_metadata( $id, $metadata ) { $GLOBALS['meta'][ $id ]['_wp_attachment_metadata'] = $metadata; return true; }
function wp_upload_dir() { return array( 'basedir' => $GLOBALS['upload_root'] ); }
function wp_get_attachment_url( $id ) { return 'https://example.test/uploads/' . basename( $GLOBALS['attached'][ $id ] ); }
function wp_update_post( $data, $wp_error = false ) { foreach ( $data as $key => $value ) { if ( 'ID' !== $key ) { $GLOBALS['posts'][ $data['ID'] ]->$key = $value; } } return $data['ID']; }
function clean_post_cache( $id ) {}
class PrivacyWpdb {
 public $posts = 'wp_posts';
 public function update( $table, $data, $where, $formats, $where_formats ) { $GLOBALS['posts'][ $where['ID'] ]->guid = $data['guid']; return 1; }
}
$GLOBALS['wpdb'] = new PrivacyWpdb();
require dirname( __DIR__ ) . '/wordpress-theme/dr-ali-moradi/inc/patient-privacy.php';
function verify_privacy( $condition, $message ) { if ( ! $condition ) { throw new RuntimeException( $message ); } }

verify_privacy( dam_patient_privacy_initials( 'نام آزمایشی' ) === 'ن. آ.', 'Persian first and last names must become initials.' );
verify_privacy( dam_patient_privacy_initials( 'نام دوم آزمایشی' ) === 'ن. د. آ.', 'All name components must be abbreviated.' );
verify_privacy( dam_patient_privacy_initials( 'نمونه' ) === 'ن.', 'Single surnames must be abbreviated.' );
$GLOBALS['posts'][621] = (object) array( 'ID' => 621, 'post_type' => 'patient', 'post_title' => 'نام آزمایشی', 'post_content' => 'گزارش نام آزمایشی', 'post_excerpt' => '', 'post_name' => 'نام-آزمایشی', 'guid' => 'https://example.test/patients/نام-آزمایشی/' );
$GLOBALS['meta'][621] = array( 'dam_patient_gallery' => array( array( 'title' => 'نام آزمایشی', 'description' => '' ) ), '_wp_old_slug' => 'نام-آزمایشی' );
$op = array( 'kind' => 'anonymize_patient', 'id' => 621, 'expected_title_sha256' => hash( 'sha256', 'نام آزمایشی' ) );
verify_privacy( is_wp_error( dam_patient_privacy_run( array_replace( $op, array( 'expected_title_sha256' => str_repeat( '0', 64 ) ) ) ) ), 'Stale title must be rejected.' );
$result = dam_patient_privacy_run( $op );
verify_privacy( ! is_wp_error( $result ) && $result['status'] === 'redacted', 'Reviewed title must be redacted.' );
verify_privacy( $GLOBALS['posts'][621]->post_title === 'ن. آ.' && $GLOBALS['posts'][621]->post_name === 'case-621', 'Public name and slug must be anonymous.' );
verify_privacy( $GLOBALS['posts'][621]->guid === 'urn:dralimoradi:patient:621', 'Feed GUID must not retain the original name URL.' );
verify_privacy( $GLOBALS['posts'][621]->post_content === 'گزارش ن. آ.' && $GLOBALS['meta'][621]['dam_patient_gallery'][0]['title'] === 'ن. آ.', 'Case body and gallery caption must lose the full name.' );
verify_privacy( ! isset( $GLOBALS['meta'][621]['_wp_old_slug'] ), 'Old personal-name URLs must not redirect.' );
verify_privacy( dam_patient_privacy_run( $op )['status'] === 'already-redacted', 'Retry must be idempotent.' );
$GLOBALS['posts'][621]->guid = 'https://example.test/patients/old-name/';
verify_privacy( dam_patient_privacy_run( $op )['status'] === 'already-redacted' && $GLOBALS['posts'][621]->guid === 'urn:dralimoradi:patient:621', 'Retry must repair a legacy patient GUID.' );

$meta = array( 'file' => '2026/10/original-name-scaled.jpg', 'sizes' => array( 'thumbnail' => array( 'file' => 'original-name-scaled-150x150.jpg' ) ), 'original_image' => 'original-name.jpg' );
$plan = dam_patient_privacy_media_plan( 853, '2026/10/original-name-scaled.jpg', $meta, '/tmp/original-name-scaled.jpg' );
verify_privacy( ! is_wp_error( $plan ) && count( $plan['moves'] ) === 3, 'Original, displayed and thumbnail files need a move plan.' );
verify_privacy( $plan['meta']['file'] === '2026/10/case-media-853.jpg' && $plan['meta']['sizes']['thumbnail']['file'] === 'case-media-853-thumbnail.jpg' && $plan['meta']['original_image'] === 'case-media-853-original.jpg', 'Metadata must reference neutral names.' );
$root = sys_get_temp_dir() . '/dam-patient-privacy-test-' . getmypid();
mkdir( $root . '/2026/10', 0777, true );
$GLOBALS['upload_root'] = $root;
$GLOBALS['attached'][853] = $root . '/2026/10/original-name-scaled.jpg';
$GLOBALS['posts'][853] = (object) array( 'ID' => 853, 'post_type' => 'attachment', 'post_parent' => 621, 'post_title' => 'original-name', 'guid' => 'https://example.test/uploads/original-name-scaled.jpg' );
$GLOBALS['meta'][853] = array( '_wp_attached_file' => '2026/10/original-name-scaled.jpg', '_wp_attachment_metadata' => $meta );
$GLOBALS['meta'][621]['dam_patient_gallery'] = array( array( 'id' => 853, 'url' => 'https://example.test/uploads/original-name-scaled.jpg' ) );
foreach ( array_keys( $plan['moves'] ) as $path ) { file_put_contents( $root . '/2026/10/' . basename( $path ), 'test-image' ); }
$media_op = array( 'kind' => 'anonymize_media', 'id' => 853, 'expected_path_sha256' => hash( 'sha256', '2026/10/original-name-scaled.jpg' ) );
$media_result = dam_patient_privacy_run( $media_op );
verify_privacy( ! is_wp_error( $media_result ) && $media_result['status'] === 'redacted', 'Patient image should be anonymized: ' . ( is_wp_error( $media_result ) ? $media_result->code : 'unexpected result' ) );
foreach ( $plan['moves'] as $from => $to ) {
 verify_privacy( ! file_exists( $root . '/2026/10/' . basename( $from ) ) && file_exists( $root . '/2026/10/' . basename( $to ) ), 'Every image size must move to a neutral filename.' );
}
verify_privacy( $GLOBALS['meta'][853]['_wp_attachment_metadata']['file'] === '2026/10/case-media-853.jpg' && $GLOBALS['meta'][621]['dam_patient_gallery'][0]['url'] === 'https://example.test/uploads/case-media-853.jpg', 'Attachment and gallery metadata must use the new URL.' );
verify_privacy( dam_patient_privacy_run( $media_op )['status'] === 'already-redacted', 'Image rename retry must be idempotent.' );
$GLOBALS['posts'][853]->guid = 'https://example.test/uploads/original-name-scaled.jpg';
verify_privacy( dam_patient_privacy_run( $media_op )['status'] === 'already-redacted' && $GLOBALS['posts'][853]->guid === 'https://example.test/uploads/case-media-853.jpg', 'Retry must repair a legacy image GUID.' );
foreach ( $plan['moves'] as $to ) { unlink( $root . '/2026/10/' . basename( $to ) ); }
rmdir( $root . '/2026/10' ); rmdir( $root . '/2026' ); rmdir( $root );
echo "Patient privacy checks passed.\n";
