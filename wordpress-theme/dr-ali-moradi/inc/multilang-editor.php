<?php
/**
 * Fill in Persian and Arabic (title, slug, content -- plus role, summary,
 * excerpt and related links for team members) alongside the English
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
	return array( 'post', 'team_member' );
}

/**
 * Which translatable fields the box offers for a post type. Every type has a
 * title, slug, and body; team members also carry their card fields.
 */
function dam_multilang_fields( $post_type ) {
	$fields = array(
		'title'   => array( 'label' => __( 'Title', 'dr-ali-moradi' ), 'kind' => 'text' ),
		'slug'    => array( 'label' => __( 'Slug', 'dr-ali-moradi' ), 'kind' => 'slug' ),
		'content' => array( 'label' => 'team_member' === $post_type ? __( 'Biography', 'dr-ali-moradi' ) : __( 'Content', 'dr-ali-moradi' ), 'kind' => 'textarea' ),
	);
	if ( 'team_member' === $post_type ) {
		$fields['role']    = array( 'label' => __( 'Role', 'dr-ali-moradi' ), 'kind' => 'text', 'meta' => 'dam_role' );
		$fields['summary'] = array( 'label' => __( 'Short summary', 'dr-ali-moradi' ), 'kind' => 'text', 'meta' => 'dam_summary' );
		$fields['excerpt'] = array( 'label' => __( 'Excerpt (profile intro line)', 'dr-ali-moradi' ), 'kind' => 'text' );
		$fields['links']   = array( 'label' => __( 'Related links — one per line: Title|https://…', 'dr-ali-moradi' ), 'kind' => 'links', 'meta' => 'dam_related_links' );
	}
	return $fields;
}

/** Current value of one field on an existing translation, as the box shows it. */
function dam_multilang_field_value( $post, $name, $field ) {
	if ( ! $post ) {
		return '';
	}
	if ( 'title' === $name ) {
		return $post->post_title;
	}
	if ( 'slug' === $name ) {
		return $post->post_name;
	}
	if ( 'content' === $name ) {
		return $post->post_content;
	}
	if ( 'excerpt' === $name ) {
		return $post->post_excerpt;
	}
	$value = get_post_meta( $post->ID, $field['meta'], true );
	if ( 'links' === $field['kind'] ) {
		$lines = array();
		foreach ( (array) $value as $link ) {
			if ( ! empty( $link['url'] ) ) {
				$lines[] = ( isset( $link['title'] ) ? $link['title'] : '' ) . '|' . $link['url'];
			}
		}
		return implode( "\n", $lines );
	}
	return (string) $value;
}

/** Parse "Title|URL" lines into the array shape the related-links meta stores. */
function dam_multilang_parse_links( $text ) {
	$links = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) $text ) as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		$url   = count( $parts ) > 1 ? $parts[1] : $parts[0];
		if ( '' !== $url ) {
			$links[] = array( 'title' => count( $parts ) > 1 ? sanitize_text_field( $parts[0] ) : '', 'url' => esc_url_raw( $url ) );
		}
	}
	return $links;
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
		<?php foreach ( array( 'fa' => array( __( 'Persian', 'dr-ali-moradi' ), $fa_post ), 'ar' => array( __( 'Arabic', 'dr-ali-moradi' ), $ar_post ) ) as $code => $column ) : ?>
		<div class="dam-multilang-column">
			<h3><?php echo esc_html( $column[0] ); ?></h3>
			<?php foreach ( dam_multilang_fields( $post->post_type ) as $name => $field ) :
				$value = dam_multilang_field_value( $column[1], $name, $field );
				$input = 'dam_' . $code . '_' . $name;
				?>
				<p><label><?php echo esc_html( $field['label'] ); ?>
				<?php if ( in_array( $field['kind'], array( 'textarea', 'links' ), true ) ) : ?>
					<textarea name="<?php echo esc_attr( $input ); ?>" rows="<?php echo 'links' === $field['kind'] ? 4 : 14; ?>" class="widefat" <?php echo 'slug' === $name ? '' : 'dir="' . ( 'links' === $field['kind'] ? 'ltr' : 'rtl' ) . '"'; ?>><?php echo esc_textarea( $value ); ?></textarea>
				<?php else : ?>
					<input type="text" name="<?php echo esc_attr( $input ); ?>" class="widefat" <?php echo 'slug' === $name ? '' : 'dir="rtl"'; ?> value="<?php echo esc_attr( $value ); ?>" />
				<?php endif; ?>
				</label></p>
			<?php endforeach; ?>
		</div>
		<?php endforeach; ?>
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

	$fields = dam_multilang_fields( $post->post_type );

	foreach ( array( 'fa', 'ar' ) as $target_lang ) {
		$input = array();
		foreach ( $fields as $name => $field ) {
			$key   = 'dam_' . $target_lang . '_' . $name;
			$value = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
			if ( 'text' === $field['kind'] ) {
				$value = sanitize_text_field( $value );
			} elseif ( 'slug' === $field['kind'] ) {
				$value = sanitize_title( $value );
			} elseif ( 'links' === $field['kind'] ) {
				$value = trim( $value );
			}
			$input[ $name ] = $value;
		}

		if ( '' === implode( '', $input ) ) {
			continue;
		}

		$post_args = array(
			'post_type'   => $post->post_type,
			'post_status' => $post->post_status,
		);
		if ( '' !== $input['title'] ) {
			$post_args['post_title'] = $input['title'];
		}
		if ( '' !== $input['content'] ) {
			$post_args['post_content'] = $input['content'];
		}
		if ( '' !== $input['slug'] ) {
			$post_args['post_name'] = $input['slug'];
		}
		if ( isset( $input['excerpt'] ) && '' !== $input['excerpt'] ) {
			$post_args['post_excerpt'] = $input['excerpt'];
		}

		if ( isset( $translations[ $target_lang ] ) ) {
			$post_args['ID'] = $translations[ $target_lang ];
			wp_update_post( $post_args );
			$target_id = $translations[ $target_lang ];
		} else {
			if ( '' === $input['title'] ) {
				$post_args['post_title'] = $post->post_title;
			}
			$target_id = wp_insert_post( $post_args );
			if ( $target_id && ! is_wp_error( $target_id ) ) {
				pll_set_post_language( $target_id, $target_lang );
				$translations[ $target_lang ] = $target_id;
			} else {
				continue;
			}
		}

		// Card fields stored as post meta (team members).
		foreach ( $fields as $name => $field ) {
			if ( empty( $field['meta'] ) || '' === $input[ $name ] ) {
				continue;
			}
			update_post_meta( $target_id, $field['meta'], 'links' === $field['kind'] ? dam_multilang_parse_links( $input[ $name ] ) : $input[ $name ] );
		}
	}

	// Structure that follows the English member into each translation: which
	// team area it belongs to (each language has its own linked term), its
	// order, and its photo when the translation has none of its own.
	if ( 'team_member' === $post->post_type ) {
		$area_ids = wp_get_object_terms( $post_id, 'team_area', array( 'fields' => 'ids' ) );
		foreach ( array( 'fa', 'ar' ) as $target_lang ) {
			if ( empty( $translations[ $target_lang ] ) ) {
				continue;
			}
			$target_id = $translations[ $target_lang ];
			$terms     = array();
			foreach ( (array) $area_ids as $area_id ) {
				$translated = function_exists( 'pll_get_term' ) ? pll_get_term( $area_id, $target_lang ) : 0;
				if ( $translated ) {
					$terms[] = (int) $translated;
				}
			}
			if ( $terms ) {
				wp_set_object_terms( $target_id, $terms, 'team_area' );
			}
			wp_update_post( array( 'ID' => $target_id, 'menu_order' => (int) $post->menu_order ) );
			if ( ! has_post_thumbnail( $target_id ) && has_post_thumbnail( $post_id ) ) {
				set_post_thumbnail( $target_id, get_post_thumbnail_id( $post_id ) );
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
