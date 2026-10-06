<?php
/** Guarded authored-content updates must reject stale/unsafe targets and resume safely. */
define('ABSPATH', '/');
function add_action(...$args) {}
function absint($v) { return abs((int)$v); }
class WP_Error { public $code; function __construct($code,$message){$this->code=$code;} }
function is_wp_error($v){return $v instanceof WP_Error;}
$GLOBALS['posts']=[1=>(object)['ID'=>1,'post_type'=>'page','post_content'=>'Original','post_title'=>'Old title','post_name'=>'old-slug'],2=>(object)['ID'=>2,'post_type'=>'patient','post_content'=>'Private']];
function get_post($id){return $GLOBALS['posts'][$id]??null;}
function current_user_can($cap,...$args){return true;}
function has_post_thumbnail($id){return false;}
function get_post_thumbnail_id($id){return 0;}
function add_post_meta(...$args){$GLOBALS['backups'][]=$args;}
function wp_slash($v){return $v;}
function wp_update_post($v,$err=true){$GLOBALS['writes']=($GLOBALS['writes']??0)+1;foreach($v as $key=>$value){if($key!=='ID')$GLOBALS['posts'][$v['ID']]->$key=$value;}return $v['ID'];}
function get_permalink($id){return 'https://example.test/fa/'.$GLOBALS['posts'][$id]->post_name.'/';}
function get_posts($args){return [];}
function get_option($key,$default=[]){return $GLOBALS['options'][$key]??$default;}
function update_option($key,$value,...$args){$GLOBALS['options'][$key]=$value;}
function wp_parse_url($url,$component){return parse_url($url,$component);}
require __DIR__.'/../wordpress-theme/dr-ali-moradi/inc/content-updates.php';
function assert_check($v,$message){if(!$v)throw new RuntimeException($message);}
$op=['id'=>1,'type'=>'page','content'=>'Updated','expected_sha256'=>hash('sha256','Original')];
$wrong=$op;$wrong['id']=2;$wrong['type']='patient';assert_check(dam_content_update_run($wrong)->code==='invalid_target','Patient data must never be a target.');
$stale=$op;$stale['expected_sha256']=hash('sha256','Stale');assert_check(dam_content_update_run($stale)->code==='content_changed','Stale content must not overwrite operator edits.');
assert_check(dam_content_update_run($op)['content']==='updated','Approved update applies.');
assert_check(count($GLOBALS['backups'])===1 && $GLOBALS['backups'][0][2]==='Original','Original content backup retained.');
assert_check(dam_content_update_run($op)['content']==='already-current' && $GLOBALS['writes']===1,'Resume must not rewrite current content.');
$rename=$op+['title'=>'New title','expected_title'=>'Old title','slug'=>'new-slug','expected_slug'=>'old-slug'];
$bad=$rename;$bad['expected_title']='Stale title';assert_check(dam_content_update_run($bad)->code==='metadata_changed','Stale title rejects rename.');
$bad=$rename;$bad['slug']='../unsafe';assert_check(dam_content_update_run($bad)->code==='invalid_slug','Unsafe slug rejects rename.');
dam_content_update_run($rename);
assert_check($GLOBALS['posts'][1]->post_name==='new-slug','Authorized rename applies.');
assert_check($GLOBALS['options']['dam_slug_redirects']['fa/old-slug']==='https://example.test/fa/new-slug/','Language-specific redirect preserves old URLs.');
dam_content_update_run($rename);assert_check($GLOBALS['writes']===2,'Resuming renamed content does not write twice.');
echo "WordPress content update guards passed.\n";
