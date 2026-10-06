<?php
/** Navigation, accessible tree and incremental case-gallery regressions. */
require __DIR__ . '/wordpress-patients.php';
function esc_html( $s ) { return htmlspecialchars( $s, ENT_QUOTES ); }
function esc_url( $s ) { return $s; }
function get_queried_object_id() { return 32; }
function get_the_ID() { return 101; }
function get_ancestors( $id, $taxonomy, $type ) { return 32 === (int) $id ? array( 31 ) : array(); }
function get_terms( $args ) {
 $id = 0 === (int) $args['parent'] ? 31 : ( 31 === (int) $args['parent'] ? 32 : 0 );
 return $id ? array( (object) array( 'term_id' => $id, 'slug' => 'category-' . $id, 'name' => 'Category ' . $id ) ) : array();
}
function get_term_link( $term ) { return '/category/' . $term->term_id . '/?patient_lang=' . dam_current_locale(); }
function get_term_meta( $id, $key, $single ) { return ''; }
function get_permalink( $post ) { return '/patients/case-101/'; }
function get_the_title( $post ) { return 'Case 101'; }
function wp_json_encode( $value ) { return json_encode( $value ); }
function dam_icon( $name, $size ) { return '<svg></svg>'; }
function dam_clinic_hub_copy( $locale ) { return array( 'previous' => 'Previous', 'next' => 'Next' ); }
class WP_Query {
 public $posts;
 public function __construct( $args ) { $this->posts = array( (object) array( 'ID' => 101 ) ); }
}
$GLOBALS['test_context'] = 'patient_category';
$GLOBALS['post_terms'][101] = array( 31, 32, 20 );
ob_start(); dam_render_patient_tree(); $tree = ob_get_clean();
preg_match_all( '/<summary[^>]*>(.*?)<\/summary>/s', $tree, $summaries );
foreach ( $summaries[1] as $summary ) { verify( ! preg_match( '/<(?:a|button|input)\b/', $summary ), 'Disclosure controls must contain no nested interactive element.' ); }
verify( 2 === substr_count( $tree, '<details open>' ), 'Selected category and its ancestor must be expanded.' );
verify( str_contains( $tree, 'aria-current="page" href="/category/32/' ), 'Current category must have a navigation state.' );
verify( 1 === substr_count( $tree, '/patients/case-101/' ), 'Cases assigned to parent and child must appear only under the most specific category.' );
$GLOBALS['test_context'] = 'patient';
ob_start(); dam_render_patient_tree(); $tree = ob_get_clean();
verify( str_contains( $tree, 'aria-current="page" href="/patients/case-101/' ) && 2 === substr_count( $tree, '<details open>' ), 'Case navigation must expand its category path and mark the case active.' );
$items = array_fill( 0, 6, array( 'type' => 'image', 'url' => 'https://example.test/photo.jpg', 'preview' => 'https://example.test/preview.jpg', 'title' => 'Case', 'sensitive' => true ) );
ob_start(); dam_render_patient_gallery( $items, true, 1 ); $gallery = ob_get_clean();
verify( 1 === substr_count( $gallery, '<img ' ), 'Initial case gallery must load exactly one preview, rather than all media.' );
verify( 1 === substr_count( $gallery, 'data-gallery-thumb' ) && str_contains( $gallery, 'data-gallery-next' ), 'Single preview must retain working gallery navigation.' );
verify( 6 === substr_count( $gallery, 'photo.jpg' ), 'All six originals must remain available as gallery metadata.' );
echo "Patient navigation checks passed.\n";
