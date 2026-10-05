<?php
/** Explicit, resumable import of the approved innovation catalogue into editable Pages. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'admin_menu', function() {
 add_management_page( 'Innovation Pages', 'برگه‌های نوآوری', 'manage_options', 'dam-innovation-pages', 'dam_innovation_pages_screen' );
} );
function dam_innovation_page_seed() {
 return json_decode( file_get_contents( DAM_THEME_DIR . '/content/innovation-pages.json' ), true )['projects'];
}
function dam_innovation_page_find( $key ) {
 $ids = get_posts( array( 'post_type' => 'page', 'post_status' => 'any', 'fields' => 'ids', 'numberposts' => 1, 'meta_key' => '_dam_innovation_page_key', 'meta_value' => $key, 'lang' => '', 'suppress_filters' => true ) );
 return $ids ? (int) $ids[0] : 0;
}
function dam_innovation_page_hub( $lang ) {
 $pages = get_posts( array( 'post_type' => 'page', 'post_status' => 'publish', 'name' => 'innovations', 'numberposts' => -1, 'lang' => '', 'suppress_filters' => true ) );
 foreach ( $pages as $page ) { if ( pll_get_post_language( $page->ID ) === $lang ) { return $page->ID; } }
 return 0;
}
function dam_innovation_page_operations() {
 $ops = array(); $seen = array();
 foreach ( dam_innovation_page_seed() as $index => $project ) {
  foreach ( $project['images'] as $image ) { if ( ! isset( $seen[$image] ) ) { $seen[$image] = true; $ops[] = array( 'kind' => 'image', 'index' => $index, 'image' => $image ); } }
  foreach ( array( 'en', 'fa', 'ar' ) as $lang ) { $ops[] = array( 'kind' => 'page', 'index' => $index, 'lang' => $lang ); }
 }
 $ops[] = array( 'kind' => 'finalize' ); return $ops;
}
function dam_innovation_pages_screen() {
 if ( ! current_user_can( 'manage_options' ) ) { return; }
 ?>
 <div class="wrap"><h1>برگه‌های نوآوری / Innovation Pages</h1><p>14 projects · 42 editable Pages (EN/FA/AR). Import source illustrations into Media, preserve existing Pages on resume, then link translations and replace the catalogue destinations. No automatic frontend migration.</p>
 <button class="button button-primary" id="dam-innovation-start">Create Pages / ایجاد برگه‌ها</button><p id="dam-innovation-status" role="status"></p><pre id="dam-innovation-report" style="white-space:pre-wrap"></pre></div>
 <script>(function(){const ops=<?php echo wp_json_encode( dam_innovation_page_operations() ); ?>,nonce=<?php echo wp_json_encode( wp_create_nonce( 'dam_innovation_pages' ) ); ?>;
 const button=document.getElementById('dam-innovation-start'),status=document.getElementById('dam-innovation-status'),report=document.getElementById('dam-innovation-report');
 button.onclick=async()=>{button.disabled=true;report.textContent='';let failed=0;
 for(let i=0;i<ops.length;i++){status.textContent=(i+1)+' / '+ops.length+' — '+ops[i].kind;const body=new FormData();body.append('action','dam_innovation_pages');body.append('_ajax_nonce',nonce);body.append('step',i);
 try{const r=await fetch(ajaxurl,{method:'POST',body,credentials:'same-origin'});const result=await r.json();if(!result.success)throw new Error(result.data);report.textContent+=JSON.stringify(result.data)+'\n';}
 catch(e){failed++;report.textContent+='ERROR '+(i+1)+' '+e.message+'\n';if(ops[i].kind!=='image')break;}}
 status.textContent='Completed / پایان; errors / خطا: '+failed;button.disabled=false;};})();</script>
 <?php
}
add_action( 'wp_ajax_dam_innovation_pages', 'dam_innovation_pages_ajax' );
function dam_innovation_pages_ajax() {
 check_ajax_referer( 'dam_innovation_pages' );
 if ( ! current_user_can( 'manage_options' ) || ! current_user_can( 'publish_pages' ) || ! current_user_can( 'upload_files' ) ) { wp_send_json_error( 'Administrator access required.', 403 ); }
 if ( ! function_exists( 'pll_set_post_language' ) || ! function_exists( 'pll_save_post_translations' ) ) { wp_send_json_error( 'Polylang is required.', 400 ); }
 $steps = dam_innovation_page_operations(); $step = filter_var( $_POST['step'] ?? null, FILTER_VALIDATE_INT );
 if ( false === $step || null === $step || ! isset( $steps[$step] ) ) { wp_send_json_error( 'Invalid step.', 400 ); }
 // Atomic lock prevents two browser sessions creating the same Page/media.
 $lock = 'dam_innovation_import_lock';
 if ( ! add_option( $lock, time(), '', false ) ) {
  if ( (int) get_option( $lock ) < time() - 600 ) { delete_option( $lock ); }
  wp_send_json_error( 'Import in progress; retry shortly.', 409 );
 }
 try { $result = dam_innovation_pages_run( $steps[$step] ); } finally { delete_option( $lock ); }
 if ( is_wp_error( $result ) ) { wp_send_json_error( $result->get_error_message(), 400 ); }
 wp_send_json_success( $result );
}
function dam_innovation_image_map() { return (array) get_option( 'dam_innovation_image_map', array() ); }
function dam_innovation_pages_run( $op ) {
 $projects = dam_innovation_page_seed();
 if ( 'finalize' === $op['kind'] ) { return dam_innovation_pages_finalize( $projects ); }
 $project = $projects[$op['index']];
 if ( 'image' === $op['kind'] ) {
  $url = $op['image']; $map = dam_innovation_image_map();
  if ( ! empty( $map[$url] ) && get_post_type( $map[$url] ) === 'attachment' ) { return array( 'media' => $map[$url], 'existing' => true ); }
  require_once ABSPATH . 'wp-admin/includes/file.php'; require_once ABSPATH . 'wp-admin/includes/media.php'; require_once ABSPATH . 'wp-admin/includes/image.php';
  // URL is selected exclusively from the checked-in manifest, not from request input.
  $tmp = download_url( $url, 45 ); if ( is_wp_error( $tmp ) ) { return $tmp; }
  $hash = hash_file( 'sha256', $tmp ); $hashes = dam_patient_import_hash_index();
  if ( isset( $hashes[$hash] ) ) { $id = $hashes[$hash]; unlink( $tmp ); }
  else {
   $name = sanitize_file_name( basename( wp_parse_url( $url, PHP_URL_PATH ) ) );
   $id = media_handle_sideload( array( 'name' => $name, 'tmp_name' => $tmp ), 0, $project['translations']['en']['title'] );
   if ( is_wp_error( $id ) ) { if ( is_file( $tmp ) ) { unlink( $tmp ); } return $id; }
   $hashes[$hash] = $id; set_transient( 'dam_patient_media_hashes', $hashes, HOUR_IN_SECONDS );
   update_post_meta( $id, '_wp_attachment_image_alt', $project['translations']['en']['title'] );
  }
  $map[$url] = (int) $id; update_option( 'dam_innovation_image_map', $map, false ); return array( 'media' => $id, 'source' => $url );
 }
 $lang = $op['lang']; $key = $project['key'] . ':' . $lang; $id = dam_innovation_page_find( $key );
 if ( $id ) { return array( 'id' => $id, 'existing' => true, 'url' => get_permalink( $id ) ); }
 $parent = dam_innovation_page_hub( $lang ); if ( ! $parent ) { return new WP_Error( 'missing_hub', 'Innovation hub missing for ' . $lang ); }
 $copy = $project['translations'][$lang]; $content = str_replace( '{{hub}}', esc_url( get_permalink( $parent ) ), $copy['content'] );
 foreach ( dam_innovation_image_map() as $source => $attachment ) { $content = str_replace( esc_url( $source ), esc_url( wp_get_attachment_url( $attachment ) ), $content ); }
 $id = wp_insert_post( wp_slash( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_parent' => $parent, 'post_name' => $project['key'], 'post_title' => $copy['title'], 'post_excerpt' => $copy['intro'], 'post_content' => $content, 'meta_input' => array( '_dam_innovation_page_key' => $key, DAM_PAGE_CONTENT_META => DAM_PAGE_CONTENT_VERSION, '_wp_page_template' => DAM_PAGE_TEMPLATE ) ) ), true );
 if ( is_wp_error( $id ) ) { return $id; }
 pll_set_post_language( $id, $lang );
 return array( 'id' => $id, 'lang' => $lang, 'url' => get_permalink( $id ) );
}
/** Replace only catalogue buttons; covers, sidebar anchors and operator prose are retained. */
function dam_innovation_hub_links( $content, $urls, $label ) {
 foreach ( $urls as $index => $url ) {
  $pattern = '~(<h2 id="section-' . $index . '">[\s\S]*?</p>)(?:\s*<a class="button"[^>]*>[\s\S]*?</a>)+~';
  $content = preg_replace_callback( $pattern, function( $match ) use ( $url, $label ) { return $match[1] . '<a class="button" href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>'; }, $content, 1, $count );
  if ( 1 !== $count ) { return new WP_Error( 'hub_structure', 'Catalogue section not found: ' . $index ); }
 }
 return $content;
}
function dam_innovation_pages_finalize( $projects ) {
 $all = array(); $labels = array( 'en' => 'Read the project page', 'fa' => 'مشاهده صفحه پروژه', 'ar' => 'عرض صفحة المشروع' );
 foreach ( $projects as $project ) {
  $translations = array(); foreach ( array_keys( $labels ) as $lang ) {
   $id = dam_innovation_page_find( $project['key'] . ':' . $lang );
   if ( ! $id || get_post_status( $id ) !== 'publish' ) { return new WP_Error( 'incomplete', 'Missing published Page: ' . $project['key'] . ':' . $lang ); }
   $translations[$lang] = $id; $all[$lang][] = get_permalink( $id );
  }
  pll_save_post_translations( $translations );
 }
 // Validate all hubs before modifying any hub.
 $updates = array(); foreach ( $labels as $lang => $label ) {
  $hub = dam_innovation_page_hub( $lang ); $content = get_post_field( 'post_content', $hub );
  $changed = dam_innovation_hub_links( $content, $all[$lang], $label ); if ( is_wp_error( $changed ) ) { return $changed; }
  $updates[$hub] = $changed;
 }
 foreach ( $updates as $hub => $content ) {
  if ( ! metadata_exists( 'post', $hub, '_dam_pre_innovation_catalogue_links' ) ) { update_post_meta( $hub, '_dam_pre_innovation_catalogue_links', get_post_field( 'post_content', $hub ) ); }
  $result = wp_update_post( wp_slash( array( 'ID' => $hub, 'post_content' => $content ) ), true ); if ( is_wp_error( $result ) ) { return $result; }
 }
 if ( class_exists( '\RankMath\Sitemap\Cache' ) ) { \RankMath\Sitemap\Cache::invalidate_storage(); }
 update_option( 'dam_innovation_pages_report', $all, false ); return array( 'completed' => 42, 'pages' => $all );
}
