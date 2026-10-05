<?php
/** Guarded authored-content updates must reject stale/unsafe targets and resume safely. */
define('ABSPATH', '/');
function add_action(...$args) {}
function absint($v) { return abs((int)$v); }
class WP_Error { public $code; function __construct($code,$message){$this->code=$code;} }
function is_wp_error($v){return $v instanceof WP_Error;}
$GLOBALS['posts']=[1=>(object)['ID'=>1,'post_type'=>'page','post_content'=>'Original'],2=>(object)['ID'=>2,'post_type'=>'patient','post_content'=>'Private']];
function get_post($id){return $GLOBALS['posts'][$id]??null;}
function current_user_can($cap,...$args){return true;}
function has_post_thumbnail($id){return false;}
function get_post_thumbnail_id($id){return 0;}
function add_post_meta(...$args){$GLOBALS['backups'][]=$args;}
function wp_slash($v){return $v;}
function wp_update_post($v,$err){$GLOBALS['writes']=($GLOBALS['writes']??0)+1;$GLOBALS['posts'][$v['ID']]->post_content=$v['post_content'];return $v['ID'];}
require __DIR__.'/../wordpress-theme/dr-ali-moradi/inc/content-updates.php';
function assert_check($v,$message){if(!$v)throw new RuntimeException($message);}
$op=['id'=>1,'type'=>'page','content'=>'Updated','expected_sha256'=>hash('sha256','Original')];
$wrong=$op;$wrong['id']=2;$wrong['type']='patient';assert_check(dam_content_update_run($wrong)->code==='invalid_target','Patient data must never be a target.');
$stale=$op;$stale['expected_sha256']=hash('sha256','Stale');assert_check(dam_content_update_run($stale)->code==='content_changed','Stale content must not overwrite operator edits.');
assert_check(dam_content_update_run($op)['content']==='updated','Approved update applies.');
assert_check(count($GLOBALS['backups'])===1 && $GLOBALS['backups'][0][2]==='Original','Original content backup retained.');
assert_check(dam_content_update_run($op)['content']==='already-current' && $GLOBALS['writes']===1,'Resume must not rewrite current content.');
echo "WordPress content update guards passed.\n";
