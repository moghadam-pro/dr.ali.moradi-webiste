<?php
/**
 * Fill in Persian and Arabic (title, slug, content) alongside the English
 * post being edited, instead of creating and switching between three
 * separate posts by hand. Lives on the English post only -- that post
 * drives its own translations. Saving creates the fa/ar posts (linked via
 * Polylang's translation group) if they don't exist yet, or updates them
 * in place if they do; a language left blank here is simply not touched.
 *
 * This is a plain-text/HTML field per language, not a second and third
 * block editor -- Polylang itself has no supported way to drive three
 * separate block-editor instances from one screen and save them as three
 * linked posts. Good enough for how these articles are actually written
 * (translated from one already-approved English draft), without taking
 * on that scope.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dam_multilang_editor_post_types() {
	return array( 'post' );
}

function dam_add_multilang_meta_box() {
	foreach ( dam_multilang_editor_post_types() as $post_type ) {
		add_meta_box(
			'dam-multilang-editor',
			__( 'Persian & Arabic translation', 'dr-ali-moradi' ),
			'dam_render_multilang_meta_box',
			$post_type,
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'dam_add_multilang_meta_box' );

function dam_render_multilang_meta_box( $post ) {
	if ( function_exists( 'pll_get_post_language' ) ) {
		$lang = pll_get_post_language( $post->ID );
		if ( $lang && 'en' !== $lang ) {
			echo '<p>' . esc_html__( 'This box only applies to the English post -- open the English version of this article to manage its translations.', 'dr-ali-moradi' ) . '</p>';
			return;
		}
	}

	wp_nonce_field( 'dam_multilang_editor', 'dam_multilang_editor_nonce' );

	$fa_id   = function_exists( 'pll_get_post' ) ? pll_get_post( $post->ID, 'fa' ) : 0;
	$ar_id   = function_exists( 'pll_get_post' ) ? pll_get_post( $post->ID, 'ar' ) : 0;
	$fa_post = $fa_id ? get_post( $fa_id ) : null;
	$ar_post = $ar_id ? get_post( $ar_id ) : null;
	?>
	<p class="description"><?php esc_html_e( 'Fill in the Persian and Arabic title, slug, and content here; saving this English post creates or updates both translations. Leave a language blank to leave its existing translation untouched.', 'dr-ali-moradi' ); ?></p>
	<div class="dam-multilang-grid">
		<div class="dam-multilang-column">
			<h3><?php esc_html_e( 'English (this post)', 'dr-ali-moradi' ); ?></h3>
			<p class="dam-multilang-slug"><strong><?php esc_html_e( 'Slug', 'dr-ali-moradi' ); ?>:</strong> <?php echo esc_html( $post->post_name ? $post->post_name : __( '(set after first save)', 'dr-ali-moradi' ) ); ?></p>
			<?php if ( $fa_id || $ar_id ) : ?>
				<p class="dam-multilang-status">
					<?php if ( $fa_id ) : ?><a href="<?php echo esc_url( get_edit_post_link( $fa_id ) ); ?>"><?php esc_html_e( 'Edit Persian post directly', 'dr-ali-moradi' ); ?></a><?php endif; ?>
					<?php if ( $ar_id ) : ?><br /><a href="<?php echo esc_url( get_edit_post_link( $ar_id ) ); ?>"><?php esc_html_e( 'Edit Arabic post directly', 'dr-ali-moradi' ); ?></a><?php endif; ?>
				</p>
			<?php endif; ?>
		</div>
		<div class="dam-multilang-column">
			<h3><?php esc_html_e( 'Persian', 'dr-ali-moradi' ); ?></h3>
			<p><label><?php esc_html_e( 'Title', 'dr-ali-moradi' ); ?><input type="text" name="dam_fa_title" class="widefat" dir="rtl" value="<?php echo esc_attr( $fa_post ? $fa_post->post_title : '' ); ?>" /></label></p>
			<p><label><?php esc_html_e( 'Slug', 'dr-ali-moradi' ); ?><input type="text" name="dam_fa_slug" class="widefat" value="<?php echo esc_attr( $fa_post ? $fa_post->post_name : '' ); ?>" /></label></p>
			<p><label><?php esc_html_e( 'Content', 'dr-ali-moradi' ); ?><textarea name="dam_fa_content" rows="14" class="widefat" dir="rtl"><?php echo esc_textarea( $fa_post ? $fa_post->post_content : '' ); ?></textarea></label></p>
		</div>
		<div class="dam-multilang-column">
			<h3><?php esc_html_e( 'Arabic', 'dr-ali-moradi' ); ?></h3>
			<p><label><?php esc_html_e( 'Title', 'dr-ali-moradi' ); ?><input type="text" name="dam_ar_title" class="widefat" dir="rtl" value="<?php echo esc_attr( $ar_post ? $ar_post->post_title : '' ); ?>" /></label></p>
			<p><label><?php esc_html_e( 'Slug', 'dr-ali-moradi' ); ?><input type="text" name="dam_ar_slug" class="widefat" value="<?php echo esc_attr( $ar_post ? $ar_post->post_name : '' ); ?>" /></label></p>
			<p><label><?php esc_html_e( 'Content', 'dr-ali-moradi' ); ?><textarea name="dam_ar_content" rows="14" class="widefat" dir="rtl"><?php echo esc_textarea( $ar_post ? $ar_post->post_content : '' ); ?></textarea></label></p>
		</div>
	</div>
	<?php
}

function dam_save_multilang_editor( $post_id, $post ) {
	if ( ! isset( $_POST['dam_multilang_editor_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['dam_multilang_editor_nonce'] ), 'dam_multilang_editor' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( ! in_array( $post->post_type, dam_multilang_editor_post_types(), true ) ) {
		return;
	}
	if ( ! function_exists( 'pll_set_post_language' ) || ! function_exists( 'pll_save_post_translations' ) || ! function_exists( 'pll_get_post' ) || ! function_exists( 'pll_get_post_language' ) ) {
		return;
	}

	$lang = pll_get_post_language( $post_id );
	if ( $lang && 'en' !== $lang ) {
		return; // Only the English post drives translation sync.
	}

	static $saving = false;
	if ( $saving ) {
		return; // wp_insert_post()/wp_update_post() below re-fire save_post; don't recurse.
	}
	$saving = true;

	if ( ! $lang ) {
		pll_set_post_language( $post_id, 'en' );
	}

	$translations = array( 'en' => $post_id );
	foreach ( array( 'fa', 'ar' ) as $existing_lang ) {
		$existing_id = pll_get_post( $post_id, $existing_lang );
		if ( $existing_id ) {
			$translations[ $existing_lang ] = $existing_id;
		}
	}

	foreach ( array( 'fa', 'ar' ) as $target_lang ) {
		$title   = isset( $_POST[ 'dam_' . $target_lang . '_title' ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'dam_' . $target_lang . '_title' ] ) ) : '';
		$slug    = isset( $_POST[ 'dam_' . $target_lang . '_slug' ] ) ? sanitize_title( wp_unslash( $_POST[ 'dam_' . $target_lang . '_slug' ] ) ) : '';
		$content = isset( $_POST[ 'dam_' . $target_lang . '_content' ] ) ? wp_unslash( $_POST[ 'dam_' . $target_lang . '_content' ] ) : '';

		if ( '' === $title && '' === $content && '' === $slug ) {
			continue;
		}

		$post_args = array(
			'post_type'   => $post->post_type,
			'post_status' => $post->post_status,
		);
		if ( '' !== $title ) {
			$post_args['post_title'] = $title;
		}
		if ( '' !== $content ) {
			$post_args['post_content'] = $content;
		}
		if ( '' !== $slug ) {
			$post_args['post_name'] = $slug;
		}

		if ( isset( $translations[ $target_lang ] ) ) {
			$post_args['ID'] = $translations[ $target_lang ];
			wp_update_post( $post_args );
		} else {
			if ( '' === $title ) {
				$post_args['post_title'] = $post->post_title;
			}
			$new_id = wp_insert_post( $post_args );
			if ( $new_id && ! is_wp_error( $new_id ) ) {
				pll_set_post_language( $new_id, $target_lang );
				$translations[ $target_lang ] = $new_id;
			}
		}
	}

	if ( count( $translations ) > 1 ) {
		pll_save_post_translations( $translations );
	}

	$saving = false;
}
add_action( 'save_post', 'dam_save_multilang_editor', 20, 2 );

function dam_multilang_editor_admin_css() {
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, dam_multilang_editor_post_types(), true ) ) {
		return;
	}
	?>
	<style>
		.dam-multilang-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; align-items: start; }
		.dam-multilang-column textarea { font-family: inherit; }
		.dam-multilang-slug { word-break: break-all; }
		@media (max-width: 900px) { .dam-multilang-grid { grid-template-columns: 1fr; } }
	</style>
	<?php
}
add_action( 'admin_head', 'dam_multilang_editor_admin_css' );
