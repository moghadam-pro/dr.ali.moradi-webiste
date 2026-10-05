<?php
/** Authored introductions must replace kickers without overwriting custom SEO. */
define( 'ABSPATH', __DIR__ );
function add_filter( ...$args ) {}
function add_action( ...$args ) {}
function is_front_page() { return false; }
function is_page( $slug = null ) { return null === $slug || $slug === ( $GLOBALS['slug'] ?? 'clinical-care' ); }
function is_home() { return false; }
function get_queried_object_id() { return 1; }
function get_post_meta( ...$args ) { return $GLOBALS['custom'] ?? ''; }
function get_post( $id ) { return (object) array( 'post_content' => $GLOBALS['content'] ); }
function wp_strip_all_tags( $text ) { return strip_tags( $text ); }
function wp_html_excerpt( $text, $length, $suffix ) { return $text; }
function dam_current_locale() { return 'en'; }
function dam_blog_labels( $locale ) { return array( 'intro' => 'Approved blog introduction.' ); }
require dirname( __DIR__ ) . '/wordpress-theme/dr-ali-moradi/inc/seo.php';
function verify_seo( $actual, $expected ) { if ( $actual !== $expected ) { throw new RuntimeException( 'SEO description mismatch.' ); } }
$lead = 'Patient-centered evaluation, treatment, reconstruction, and follow-up across office and hospital care.';
$content = '<p class="section-index">Clinical care</p><h1>Care</h1><p>' . $lead . '</p><p>Later content.</p>';
verify_seo( dam_authored_page_seo_description( 'Clinical care' ), $lead );
$lead = 'ارزیابی، درمان، بازسازی و پیگیری بیمارمحور در مطب خصوصی و بیمارستان‌ها با دکتر علی مرادی.';
$content = '<p>خدمات درمانی</p><p>' . $lead . '</p>';
verify_seo( dam_authored_page_seo_description( 'خدمات درمانی' ), $lead );
$custom = 'Operator SEO';
verify_seo( dam_authored_page_seo_description( 'Operator SEO' ), 'Operator SEO' );
$custom = ''; $slug = 'blog';
verify_seo( dam_authored_page_seo_description( '' ), 'Approved blog introduction.' );
echo "WordPress SEO behavior checks passed.\n";
