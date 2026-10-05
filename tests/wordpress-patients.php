<?php
/** Behavior tests for mandatory care categories and media sanitization. */
define( 'ABSPATH', __DIR__ );
$hooks = array(); $post_terms = array(); $meta = array();
function add_action( $name, $fn, $priority = 10, $args = 1 ) { $GLOBALS['hooks'][$name][] = $fn; }
function add_filter( $name, $fn, $priority = 10, $args = 1 ) { $GLOBALS['hooks'][$name][] = $fn; }
function get_term_by( $field, $value, $taxonomy ) { return (object) array( 'term_id' => 'clinic' === $value ? 10 : 20 ); }
function get_post_type( $id ) { return 'patient'; }
function wp_get_object_terms( $id, $taxonomy, $args ) { return $GLOBALS['post_terms'][$id] ?? array(); }
function is_wp_error( $value ) { return $value instanceof WP_Error; }
function get_post_meta( $id, $key, $single = true ) { return $GLOBALS['meta'][$id][$key] ?? ''; }
function update_post_meta( $id, $key, $value ) { $GLOBALS['meta'][$id][$key] = $value; }
function wp_set_object_terms( $id, $terms, $taxonomy ) { $GLOBALS['post_terms'][$id] = $terms; dam_enforce_patient_location( $id, $terms, array(), $taxonomy ); }
function absint( $value ) { return abs( (int) $value ); }
function esc_url_raw( $url, $protocols = array() ) { return preg_match( '#^https?://#', $url ) ? $url : ''; }
function sanitize_text_field( $value ) { return strip_tags( $value ); }
function sanitize_textarea_field( $value ) { return strip_tags( $value ); }
function wp_get_attachment_url( $id ) { return 'https://example.test/media/' . $id; }
function get_post_mime_type( $id ) { return 3 === $id ? 'application/pdf' : ( 2 === $id ? 'video/mp4' : 'image/jpeg' ); }
function is_singular( $type ) { return false; }
function is_post_type_archive( $type ) { return true; }
function is_tax( $taxonomies ) { return false; }
function esc_attr( $s ) { return $s; }
function is_admin() { return $GLOBALS['test_admin'] ?? true; }
function dam_current_locale() { return $GLOBALS['test_locale'] ?? 'en'; }
function paginate_links( $args ) { return '<a href="/gallery/page/2/?id=123">۲</a><span>۳</span><a href="/gallery/page/4/">' . $args['next_text'] . '</a>'; }
function determine_locale() { return 'fa_IR'; }
class WP_Error { public function __construct( ...$args ) {} }
require dirname( __DIR__ ) . '/wordpress-theme/dr-ali-moradi/inc/patients.php';
function verify( $condition, $message ) { if ( ! $condition ) { throw new RuntimeException( $message ); } }
$post_terms[1] = array( 31, 32 );
dam_enforce_patient_location( 1, array(), array(), 'patient_category' );
verify( $post_terms[1] === array( 31, 32, 20 ), 'Missing location must default to Hospital and retain disease terms.' );
$post_terms[1] = array( 31, 10 );
dam_enforce_patient_location( 1, array(), array(), 'patient_category' );
verify( $post_terms[1] === array( 31, 10 ) && $meta[1]['_dam_patient_location'] === 'clinic', 'Clinic choice must survive.' );
$post_terms[1] = array( 31, 10, 20 );
dam_enforce_patient_location( 1, array(), array(), 'patient_category' );
verify( $post_terms[1] === array( 31, 10 ), 'Double selection must retain the previous setting.' );
$post_terms[1] = array( 31 );
dam_enforce_patient_location( 1, array(), array(), 'patient_category' );
verify( $post_terms[1] === array( 31, 10 ), 'Removing care setting must restore previous setting.' );
$rest = $hooks['rest_pre_insert_patient'][0];
verify( is_wp_error( $rest( (object) array(), array( 'patient_category' => array( 31 ) ) ) ), 'REST must reject missing care setting.' );
verify( is_wp_error( $rest( (object) array(), array( 'patient_category' => array( 10, 20 ) ) ) ), 'REST must reject conflicting settings.' );
verify( ! is_wp_error( $rest( (object) array(), array( 'patient_category' => array( 10, 31 ) ) ) ), 'REST must accept one setting plus disease.' );
$media = dam_sanitize_patient_gallery( array(
 array( 'id' => 1, 'url' => 'javascript:alert(1)', 'title' => '<b>Case</b>' ),
 array( 'id' => 2, 'type' => 'image' ), array( 'id' => 3 ),
 array( 'url' => 'javascript:alert(1)', 'type' => 'link' ),
 array( 'url' => 'https://video.example.test/watch', 'type' => 'link' ),
) );
verify( count( $media ) === 3 && $media[0]['title'] === 'Case', 'Gallery must reject unsafe URLs and non-media attachments.' );
verify( $media[1]['type'] === 'video', 'Attachment MIME must determine media type.' );
verify( $media[0]['url'] === 'https://example.test/media/1', 'Attachment URL must come from WordPress.' );
verify( dam_patient_label( 'Patients', 'بیماران' ) === 'بیماران', 'Persian admin labels must be localized.' );
$GLOBALS['test_admin'] = false;
foreach ( array( 'en' => array( '>2<', 'Next »' ), 'fa' => array( '>۲<', 'بعدی »' ), 'ar' => array( '>٢<', 'التالي »' ) ) as $locale => $expected ) {
 $GLOBALS['test_locale'] = $locale;
 $pagination = dam_patient_pagination( 4, 1 );
 verify( str_contains( $pagination, $expected[0] ) && str_contains( $pagination, $expected[1] ), 'Pagination must follow visitor language despite Persian digit filters.' );
 verify( str_contains( $pagination, '/gallery/page/2/?id=123' ), 'Pagination must not translate URL digits.' );
}
$meta[1]['dam_patient_translations'] = array( 'ar' => array( 'title' => 'حالة', 'excerpt' => 'وصف' ) );
verify( dam_patient_translated_field( 'Original', 1, 'title' ) === 'حالة', 'Translated case field must follow locale.' );
verify( dam_patient_translated_field( 'Original body', 1, 'content' ) === 'Original body', 'Missing translation must preserve source content.' );
$GLOBALS['test_admin'] = true;
verify( ! dam_patient_is_catalogue(), 'Admin patient query must never be treated as frontend catalogue.' );
$attributes = $hooks['language_attributes'][0];
verify( $attributes( 'lang="fa-IR" dir="rtl"' ) === 'lang="fa-IR" dir="rtl"', 'Admin language and direction must be preserved.' );
$GLOBALS['test_admin'] = false; $GLOBALS['test_locale'] = 'en';
verify( $attributes( 'lang="fa-IR" dir="rtl"' ) === 'lang="en" dir="ltr"', 'Frontend patient language override must still work.' );
echo "Patient behavior checks passed.\n";
