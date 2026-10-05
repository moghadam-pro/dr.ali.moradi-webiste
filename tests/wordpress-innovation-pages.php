<?php
/** Migration contracts: all projects/languages, reference policy and idempotent hub edits. */
define( 'ABSPATH', __DIR__ );
define( 'DAM_THEME_DIR', dirname( __DIR__ ) . '/wordpress-theme/dr-ali-moradi' );
function add_action() {}
function esc_url( $s ) { return htmlspecialchars( $s, ENT_QUOTES ); }
function esc_html( $s ) { return htmlspecialchars( $s, ENT_QUOTES ); }
class WP_Error { public function __construct( $code, $message ) {} }
function is_wp_error( $s ) { return $s instanceof WP_Error; }
require DAM_THEME_DIR . '/inc/innovation-pages.php';
function verify( $ok, $message ) { if ( ! $ok ) { throw new Exception( $message ); } }
$projects = dam_innovation_page_seed(); verify( count( $projects ) === 14, 'Fourteen records required.' );
verify( count( array_unique( array_column( $projects, 'key' ) ) ) === 14, 'Unique project keys required.' );
foreach ( $projects as $project ) {
 verify( array_keys( $project['translations'] ) === array( 'en', 'fa', 'ar' ), 'Three languages required.' );
 foreach ( $project['translations'] as $copy ) {
  verify( substr_count( $copy['content'], '<h2>' ) >= 3, 'Substantive project sections required.' );
  preg_match_all( '~href="([^"]+)"~', $copy['content'], $links );
  foreach ( $links[1] as $link ) { verify( ! str_contains( $link, 'legacy.dralimoradi.com' ), 'Old-domain reference links prohibited.' ); }
 }
 foreach ( $project['references'] as $reference ) { verify( ! str_ends_with( parse_url( $reference, PHP_URL_HOST ), 'dralimoradi.com' ), 'Only external references allowed.' ); }
}
$ops = dam_innovation_page_operations(); verify( count( array_filter( $ops, fn( $op ) => $op['kind'] === 'page' ) ) === 42, 'Forty-two Pages required.' );
$urls = array_map( fn( $p ) => 'https://example.test/innovations/' . $p['key'] . '/', $projects );
foreach ( array(164,165,166) as $id ) {
 $original = file_get_contents( dirname(__DIR__) . '/wordpress-theme/content-migration/page-updates-2026-10-06/' . $id . '.html' );
 $changed = dam_innovation_hub_links( $original, $urls, 'Read project' );
 verify( ! is_wp_error( $changed ), 'Current hub structure must migrate.' );
 verify( substr_count( $changed, '>Read project</a>' ) === 14, 'H3 must have only one internal destination.' );
 verify( substr_count( $changed, 'href="#section-' ) === 14, 'Sidebar anchors must be preserved.' );
 verify( ! str_contains( $changed, 'legacy.dralimoradi.com' ), 'Hub must have no archive references.' );
 verify( dam_innovation_hub_links( $changed, $urls, 'Read project' ) === $changed, 'Hub update must be idempotent.' );
}
verify( is_wp_error( dam_innovation_hub_links( '<p>Operator changed layout</p>', $urls, 'Read project' ) ), 'Unexpected structure must fail safely.' );
// Existing Page exits before parent lookup or any write, preserving operator edits.
function get_posts( $query ) { return array( 4321 ); }
function get_permalink( $id ) { return 'https://example.test/operator-edited/'; }
function wp_insert_post() { throw new Exception( 'Existing Page must never be overwritten.' ); }
$result = dam_innovation_pages_run( array( 'kind' => 'page', 'index' => 0, 'lang' => 'fa' ) );
verify( $result['id'] === 4321 && $result['existing'] === true, 'Resume must preserve existing Page.' );
echo "Innovation Page migration contracts passed.\n";
