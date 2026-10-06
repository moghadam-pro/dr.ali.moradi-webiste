<?php
/** Authored introductions must replace kickers without overwriting custom SEO. */
define( 'ABSPATH', __DIR__ );
function add_filter( $name, $callback, ...$args ) { $GLOBALS['seo_filters'][ $name ][] = $callback; }
function add_action( ...$args ) {}
function esc_attr_e($text,$domain){echo htmlspecialchars($text,ENT_QUOTES);}
function esc_html($text){return htmlspecialchars($text,ENT_QUOTES);}
function is_front_page() { return $GLOBALS['front'] ?? false; }
function is_page( $slug = null ) { return null === $slug || $slug === ( $GLOBALS['slug'] ?? 'clinical-care' ); }
function is_home() { return false; }
function get_queried_object_id() { return 1; }
function get_post_meta( ...$args ) { return $GLOBALS['custom'] ?? ''; }
function get_post( $id ) { return (object) array( 'post_content' => $GLOBALS['content'] ); }
function wp_strip_all_tags( $text ) { return strip_tags( $text ); }
function wp_html_excerpt( $text, $length, $suffix ) { return $text; }
function dam_current_locale() { return 'en'; }
function dam_blog_labels( $locale ) { return array( 'intro' => 'Approved blog introduction.' ); }
function get_post_type( $id ) { return 7 === $id ? 'patient' : 'post'; }
function dam_patient_media( $id ) { return array(
 array( 'type' => 'image', 'url' => 'https://example.test/sensitive.jpg', 'title' => 'Clinical', 'sensitive' => true ),
 array( 'type' => 'image', 'url' => 'https://example.test/safe.jpg', 'title' => 'Reviewed', 'sensitive' => false ),
); }
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
$image_filter = $GLOBALS['seo_filters']['rank_math/sitemap/urlimages'][0];
$gallery_images = $image_filter( array( array( 'src' => 'https://example.test/sensitive.jpg' ) ), 7 );
if ( count( $gallery_images ) !== 1 || $gallery_images[0]['src'] !== 'https://example.test/safe.jpg' ) { throw new RuntimeException( 'Sensitive media leaked into patient image sitemap.' ); }
$front = true;
$home_crumbs = dam_get_breadcrumb_items();
if ( count( $home_crumbs ) !== 1 || $home_crumbs[0]['url'] !== null ) { throw new RuntimeException( 'Home must expose one current-page breadcrumb.' ); }
if ( dam_interior_page_parent_key( 'hospital-surgery-care' ) !== 'clinical-care' || dam_interior_page_parent_key( 'clinic-surgery-care' ) !== 'clinical-care' ) { throw new RuntimeException( 'Renamed guides must keep their clinical parent.' ); }
$project='<div class="dam-project"><header>Hero</header><nav>Project sections</nav></div>';
$with_trail=dam_project_breadcrumb_content($project);
if (strpos($with_trail,'</header>')>strpos($with_trail,'site-breadcrumbs') || strpos($with_trail,'site-breadcrumbs')>strpos($with_trail,'<nav>Project sections')) { throw new RuntimeException('Project trail must follow the hero and precede section navigation.'); }
if (dam_project_breadcrumb_content($with_trail)!==$with_trail || dam_project_breadcrumb_content('<p>Ordinary page</p>')!=='<p>Ordinary page</p>') { throw new RuntimeException('Breadcrumb filter must not duplicate trails or modify ordinary content.'); }
echo "WordPress SEO behavior checks passed.\n";
