<?php
/** Guarded name redaction and image filename plan without a WordPress install. */
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
function wp_update_post( $data, $wp_error = false ) { foreach ( $data as $key => $value ) { if ( 'ID' !== $key ) { $GLOBALS['posts'][ $data['ID'] ]->$key = $value; } } return $data['ID']; }
require dirname( __DIR__ ) . '/wordpress-theme/dr-ali-moradi/inc/patient-privacy.php';
function verify_privacy( $condition, $message ) { if ( ! $condition ) { throw new RuntimeException( $message ); } }

verify_privacy( dam_patient_privacy_initials( 'راضیه غلامیان' ) === 'ر. غ.', 'Persian first and last names must become initials.' );
verify_privacy( dam_patient_privacy_initials( 'علی اکبر ملانیا' ) === 'ع. ا. م.', 'All name components must be abbreviated.' );
verify_privacy( dam_patient_privacy_initials( 'تقدیسی' ) === 'ت.', 'Single surnames must be abbreviated.' );
$GLOBALS['posts'][621] = (object) array( 'ID' => 621, 'post_type' => 'patient', 'post_title' => 'راضیه غلامیان', 'post_content' => 'گزارش راضیه غلامیان', 'post_excerpt' => '', 'post_name' => 'راضیه-غلامیان' );
$GLOBALS['meta'][621] = array( 'dam_patient_gallery' => array( array( 'title' => 'راضیه غلامیان', 'description' => '' ) ), '_wp_old_slug' => 'راضیه-غلامیان' );
$op = array( 'kind' => 'anonymize_patient', 'id' => 621, 'expected_title_sha256' => hash( 'sha256', 'راضیه غلامیان' ) );
verify_privacy( is_wp_error( dam_patient_privacy_run( array_replace( $op, array( 'expected_title_sha256' => str_repeat( '0', 64 ) ) ) ) ), 'Stale title must be rejected.' );
$result = dam_patient_privacy_run( $op );
verify_privacy( ! is_wp_error( $result ) && $result['status'] === 'redacted', 'Reviewed title must be redacted.' );
verify_privacy( $GLOBALS['posts'][621]->post_title === 'ر. غ.' && $GLOBALS['posts'][621]->post_name === 'case-621', 'Public name and slug must be anonymous.' );
verify_privacy( $GLOBALS['posts'][621]->guid === 'urn:dralimoradi:patient:621', 'Feed GUID must not retain the original name URL.' );
verify_privacy( $GLOBALS['posts'][621]->post_content === 'گزارش ر. غ.' && $GLOBALS['meta'][621]['dam_patient_gallery'][0]['title'] === 'ر. غ.', 'Case body and gallery caption must lose the full name.' );
verify_privacy( ! isset( $GLOBALS['meta'][621]['_wp_old_slug'] ), 'Old personal-name URLs must not redirect.' );
verify_privacy( dam_patient_privacy_run( $op )['status'] === 'already-redacted', 'Retry must be idempotent.' );

$meta = array( 'file' => '2026/10/original-name-scaled.jpg', 'sizes' => array( 'thumbnail' => array( 'file' => 'original-name-scaled-150x150.jpg' ) ), 'original_image' => 'original-name.jpg' );
$plan = dam_patient_privacy_media_plan( 853, '2026/10/original-name-scaled.jpg', $meta, '/tmp/original-name-scaled.jpg' );
verify_privacy( ! is_wp_error( $plan ) && count( $plan['moves'] ) === 3, 'Original, displayed and thumbnail files need a move plan.' );
verify_privacy( $plan['meta']['file'] === '2026/10/case-media-853.jpg' && $plan['meta']['sizes']['thumbnail']['file'] === 'case-media-853-thumbnail.jpg' && $plan['meta']['original_image'] === 'case-media-853-original.jpg', 'Metadata must reference neutral names.' );
echo "Patient privacy checks passed.\n";
