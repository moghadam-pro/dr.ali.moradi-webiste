<?php
/** Explicit guarded updates to existing, editable Pages and Posts. Never runs on frontend. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'admin_menu', function() {
 add_management_page( 'Content updates', 'به‌روزرسانی محتوا', 'manage_options', 'dam-content-updates', 'dam_content_updates_screen' );
} );
function dam_content_updates_screen() {
 if ( ! current_user_can( 'manage_options' ) ) { return; }
 $export = array();
 foreach ( get_posts( array( 'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => -1, 'lang' => '', 'suppress_filters' => true ) ) as $page ) {
  $export[] = array( 'id' => $page->ID, 'type' => 'page', 'slug' => $page->post_name, 'title' => $page->post_title, 'lang' => dam_page_language( $page->ID ), 'url' => get_permalink( $page ), 'content' => $page->post_content, 'expected_sha256' => hash( 'sha256', trim( $page->post_content ) ) );
 }
 ?>
 <div class="wrap"><h1>به‌روزرسانی محتوای برگه‌ها و نوشته‌ها</h1><p>Explicit JSON updates to existing Pages/Posts. Each update checks the previous content hash, preserves translations and terms, and uses WordPress revisions. Images must already exist in Media. No automatic updates.</p>
 <label for="dam-content-manifest">JSON manifest</label><textarea id="dam-content-manifest" class="large-text code" rows="12"></textarea><p><button id="dam-content-apply" class="button button-primary">Apply content updates / ثبت تغییرات</button></p><p id="dam-content-status" role="status"></p><pre id="dam-content-report" style="white-space:pre-wrap"></pre></div>
 <details><summary>Published Page snapshot / خروجی برگه‌های عمومی</summary><textarea id="dam-content-export" readonly class="large-text code" rows="8"><?php echo esc_textarea( wp_json_encode( $export, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) ); ?></textarea></details>
 <script>(()=>{const button=document.getElementById('dam-content-apply'),input=document.getElementById('dam-content-manifest'),status=document.getElementById('dam-content-status'),report=document.getElementById('dam-content-report'),nonce=<?php echo wp_json_encode( wp_create_nonce( 'dam_content_updates' ) ); ?>;
 button.onclick=async()=>{let ops;try{ops=JSON.parse(input.value);if(!Array.isArray(ops)||!ops.length||ops.length>150)throw new Error('Expected 1–150 operations.');}catch(e){status.textContent=e.message;return;}button.disabled=true;report.textContent='';let failed=0;
 for(let i=0;i<ops.length;i++){status.textContent=(i+1)+' / '+ops.length;const body=new FormData();body.append('action','dam_content_updates');body.append('_ajax_nonce',nonce);body.append('operation',JSON.stringify(ops[i]));try{const result=await(await fetch(ajaxurl,{method:'POST',body,credentials:'same-origin'})).json();if(!result.success)throw new Error(result.data);report.textContent+=JSON.stringify(result.data)+'\n';}catch(e){failed++;report.textContent+='ERROR '+ops[i].id+': '+e.message+'\n';}}
 status.textContent='Completed / پایان: '+ops.length+'; errors / خطا: '+failed;button.disabled=false;};})();</script>
 <?php
}
add_action( 'wp_ajax_dam_content_updates', 'dam_content_updates_ajax' );
function dam_content_updates_ajax() {
 check_ajax_referer( 'dam_content_updates' );
 if ( ! current_user_can( 'manage_options' ) || ! current_user_can( 'unfiltered_html' ) ) { wp_send_json_error( 'Administrator HTML access required.', 403 ); }
 $op = json_decode( wp_unslash( $_POST['operation'] ?? '' ), true );
 if ( ! is_array( $op ) ) { wp_send_json_error( 'Invalid operation.', 400 ); }
 $lock = 'dam_content_update_lock';
 if ( ! add_option( $lock, time(), '', false ) ) {
  if ( (int) get_option( $lock ) < time() - 600 ) { delete_option( $lock ); }
  wp_send_json_error( 'Another update is running; retry shortly.', 409 );
 }
 try { $result = dam_content_update_run( $op ); } finally { delete_option( $lock ); }
 if ( is_wp_error( $result ) ) { wp_send_json_error( $result->get_error_message(), 400 ); }
 wp_send_json_success( $result );
}
function dam_content_update_run( $op ) {
 $id = absint( $op['id'] ?? 0 ); $post = get_post( $id );
 if ( ! $post || ! in_array( $post->post_type, array( 'page', 'post' ), true ) || $post->post_type !== ( $op['type'] ?? '' ) || ! current_user_can( 'edit_post', $id ) ) { return new WP_Error( 'invalid_target', 'Editable Page/Post required.' ); }
 if ( ! isset( $op['content'], $op['expected_sha256'] ) || ! is_string( $op['content'] ) || strlen( $op['content'] ) > 500000 || ! preg_match( '/^[a-f0-9]{64}$/', $op['expected_sha256'] ) ) { return new WP_Error( 'invalid_content', 'Content and previous SHA-256 required.' ); }
 $current = hash( 'sha256', trim( $post->post_content ) ); $target = hash( 'sha256', trim( $op['content'] ) );
 if ( $current !== $target && ! hash_equals( $op['expected_sha256'], $current ) ) { return new WP_Error( 'content_changed', 'Live content has changed; export it before updating.' ); }
 $fields = array( 'ID' => $id, 'post_content' => $op['content'] );
 foreach ( array( 'title' => 'post_title', 'slug' => 'post_name' ) as $key => $field ) {
  if ( ! isset( $op[$key] ) ) { continue; }
  if ( ! is_string( $op[$key] ) || ! isset( $op['expected_' . $key] ) || ( $post->$field !== $op[$key] && $post->$field !== $op['expected_' . $key] ) ) { return new WP_Error( 'metadata_changed', 'Title/slug changed since snapshot.' ); }
  if ( 'slug' === $key && ( ! preg_match( '/^[a-z0-9-]+$/', $op[$key] ) || strlen( $op[$key] ) > 100 ) ) { return new WP_Error( 'invalid_slug', 'Simple ASCII slug required.' ); }
  $fields[$field] = $op[$key];
 }
 $old_url = isset( $op['slug'] ) ? get_permalink( $id ) : '';
 $metadata_changed = ( isset( $fields['post_name'] ) && $fields['post_name'] !== $post->post_name ) || ( isset( $fields['post_title'] ) && $fields['post_title'] !== $post->post_title );
 $image = 0;
 if ( ! empty( $op['image_name'] ) && ! has_post_thumbnail( $id ) ) {
  $name = sanitize_file_name( $op['image_name'] );
  $ids = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => -1, 'fields' => 'ids', 'meta_key' => '_wp_attached_file', 'meta_value' => $name, 'meta_compare' => 'LIKE', 'lang' => '', 'suppress_filters' => true ) );
  foreach ( $ids as $media_id ) { if ( basename( get_post_meta( $media_id, '_wp_attached_file', true ) ) === $name && wp_attachment_is_image( $media_id ) ) { $image = $media_id; break; } }
  if ( ! $image ) { return new WP_Error( 'missing_media', 'Upload the named cover to Media first.' ); }
 }
 if ( $current !== $target || $metadata_changed ) {
  // Keep the exact previous body even if site revisions have been disabled.
  add_post_meta( $id, '_dam_content_backup_' . $current, $post->post_content, true );
  $result = wp_update_post( wp_slash( $fields ), true );
  if ( is_wp_error( $result ) ) { return $result; }
 }
 if ( $image ) { set_post_thumbnail( $id, $image ); }
 if ( $old_url && $old_url !== get_permalink( $id ) ) {
  $redirects = get_option( 'dam_slug_redirects', array() );
  $redirects[ trim( wp_parse_url( $old_url, PHP_URL_PATH ), '/' ) ] = get_permalink( $id );
  update_option( 'dam_slug_redirects', $redirects, false );
 }
 if ( isset( $op['title'] ) && $metadata_changed ) {
  foreach ( get_posts( array( 'post_type' => 'nav_menu_item', 'post_status' => 'publish', 'numberposts' => -1, 'meta_key' => '_menu_item_object_id', 'meta_value' => $id, 'suppress_filters' => true ) ) as $item ) {
   if ( 'page' === get_post_meta( $item->ID, '_menu_item_object', true ) ) { wp_update_post( wp_slash( array( 'ID' => $item->ID, 'post_title' => $op['title'] ) ) ); }
  }
 }
 if ( class_exists( '\RankMath\Sitemap\Cache' ) ) { \RankMath\Sitemap\Cache::invalidate_storage(); }
 return array( 'id' => $id, 'content' => $current === $target ? 'already-current' : 'updated', 'thumbnail' => get_post_thumbnail_id( $id ) );
}
