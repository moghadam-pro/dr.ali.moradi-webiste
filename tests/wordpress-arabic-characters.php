<?php
namespace WPParsidate\App\Convert { class FixArabic {} }
namespace {
define( 'ABSPATH', __DIR__ );
function add_action() {}
function add_filter() {}
function is_admin() { return $GLOBALS['admin']; }
function dam_current_locale() { return $GLOBALS['language']; }
function remove_filter( $tag, $callback, $priority ) { $GLOBALS['removed'][] = array( $tag, $callback, $priority ); }
require dirname(__DIR__) . '/wordpress-theme/dr-ali-moradi/inc/i18n.php';
function ensure( $ok, $message ) { if ( ! $ok ) { throw new \Exception( $message ); } }
$normalizer = new \WPParsidate\App\Convert\FixArabic();
foreach ( array('the_content','the_title','comment_text','wp_list_categories','the_excerpt','wp_title') as $tag ) {
 $wp_filter[$tag] = (object) array('callbacks'=>array(1000=>array(array('function'=>array($normalizer,'fixArabic')),array('function'=>'unrelated_filter'))));
}
foreach ( array('en','fa') as $language ) { $admin=false; $removed=array(); dam_preserve_arabic_characters(); ensure(!$removed, 'Other language behavior must remain unchanged.'); }
$language='ar'; $admin=true; $removed=array(); dam_preserve_arabic_characters(); ensure(!$removed,'Admin settings must remain unchanged.');
$admin=false; dam_preserve_arabic_characters(); ensure(count($removed)===6,'Remove only six Arabic-to-Persian callbacks on Arabic frontend.');
foreach($removed as $entry) { ensure(is_array($entry[1])&&$entry[1][0]===$normalizer,'Unrelated filters must remain unchanged.'); }
echo "Arabic character preservation checks passed.\n";
}
