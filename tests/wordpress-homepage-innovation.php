<?php
define( 'ABSPATH', __DIR__ );
function dam_current_locale() { return $GLOBALS['test_locale']; }
function dam_theme_mod( $key, $locale ) { return 'fallback-' . $key; }
function dam_innovation_page_find( $key ) { return $key; }
function get_post_status( $id ) { return str_contains( $id, 'dynamic-distal' ) ? 'draft' : 'publish'; }
function get_permalink( $id ) { return 'https://example.test/' . $id; }
function get_the_title( $id ) { return 'Title ' . $id; }
function get_post_field( $field, $id ) { return 'post_excerpt' === $field ? 'Summary ' . $id : '<img src="https://example.test/cover.png">'; }
function get_the_post_thumbnail_url( $id, $size ) { return str_contains( $id, 'magnetic-control' ) ? $GLOBALS['test_image'] : false; }
function get_block_wrapper_attributes( $attributes ) { return ''; }
function esc_html( $value ) { return htmlspecialchars( $value, ENT_QUOTES ); }
function esc_attr( $value ) { return esc_html( $value ); }
function esc_url( $value ) { return esc_html( $value ); }
function dam_icon( $name, $size ) { return ''; }
function verify_card( $condition, $message ) { if ( ! $condition ) { throw new RuntimeException( $message ); } }
foreach ( array( 'en', 'fa', 'ar' ) as $lang ) {
 $GLOBALS['test_locale'] = $lang;
 foreach ( array( 'original', 'updated' ) as $revision ) {
  $GLOBALS['test_image'] = 'https://example.test/' . $revision . '.png';
  ob_start(); include dirname( __DIR__ ) . '/wordpress-theme/dr-ali-moradi/blocks/homepage-innovation/render.php'; $html = ob_get_clean();
  verify_card( str_contains( $html, 'magnetic-control-artificial-limb:' . $lang ), 'Localized project missing.' );
  verify_card( str_contains( $html, $GLOBALS['test_image'] ), 'Featured image change was not reflected.' );
  verify_card( str_contains( $html, 'https://example.test/cover.png' ), 'Designed Page cover fallback missing.' );
  verify_card( str_contains( $html, 'fallback-innovation_card_3_url' ), 'Draft Page must not replace curated fallback.' );
 }
}
echo "Homepage Innovation project checks passed.\n";
