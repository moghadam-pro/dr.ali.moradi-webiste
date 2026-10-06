<?php
/** Patient catalogue, independent of article categories and translated pages. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function dam_patient_label( $en, $fa, $ar = '' ) {
	$locale = is_admin() ? substr( determine_locale(), 0, 2 ) : dam_current_locale();
	return 'fa' === $locale ? $fa : ( 'ar' === $locale && $ar ? $ar : $en );
}

function dam_patient_is_catalogue() {
	return ! is_admin() && ( is_singular( 'patient' ) || is_post_type_archive( 'patient' ) || is_tax( array( 'patient_category', 'patient_tag' ) ) );
}
function dam_patient_language_url( $url, $locale = '' ) {
	$locale = $locale ?: dam_current_locale();
	$url = remove_query_arg( 'patient_lang', $url );
	return 'en' === $locale ? $url : add_query_arg( 'patient_lang', $locale, $url );
}
add_action( 'wp', function() {
	if ( dam_patient_is_catalogue() && isset( $_GET['patient_lang'] ) && in_array( $_GET['patient_lang'], array( 'en', 'fa', 'ar' ), true ) ) {
		$GLOBALS['dam_locale_override'] = $_GET['patient_lang'];
	}
} );
add_filter( 'post_type_link', function( $url, $post ) { return ! is_admin() && 'patient' === $post->post_type ? dam_patient_language_url( $url ) : $url; }, 20, 2 );
add_filter( 'term_link', function( $url, $term, $taxonomy ) { return ! is_admin() && in_array( $taxonomy, array( 'patient_category', 'patient_tag' ), true ) ? dam_patient_language_url( $url ) : $url; }, 20, 3 );
add_filter( 'language_attributes', function( $attributes ) { return dam_patient_is_catalogue() ? 'lang="' . esc_attr( dam_current_locale() ) . '" dir="' . ( 'en' === dam_current_locale() ? 'ltr' : 'rtl' ) . '"' : $attributes; } );
add_filter( 'wp_nav_menu_args', function( $args ) {
	if ( is_admin() || ! dam_patient_is_catalogue() || empty( $args['theme_location'] ) ) { return $args; }
	// Polylang stores translated locations in its own nav_menus option; the
	// ___fa/___ar admin locations are virtual, not saved theme modifications.
	$options = get_option( 'polylang', array() );
	$menu = $options['nav_menus'][ get_stylesheet() ][ $args['theme_location'] ][ dam_current_locale() ] ?? 0;
	if ( $menu ) { $args['menu'] = $menu; $args['theme_location'] = ''; }
	return $args;
}, 1000 );
function dam_patient_translated_field( $value, $id, $field ) {
	$translations = get_post_meta( $id, 'dam_patient_translations', true );
	return $translations[ dam_current_locale() ][ $field ] ?? $value;
}
add_filter( 'the_title', function( $title, $id ) { return ! is_admin() && 'patient' === get_post_type( $id ) ? dam_patient_translated_field( $title, $id, 'title' ) : $title; }, 20, 2 );
add_filter( 'get_the_excerpt', function( $excerpt, $post ) { return ! is_admin() && 'patient' === $post->post_type ? dam_patient_translated_field( $excerpt, $post->ID, 'excerpt' ) : $excerpt; }, 20, 2 );
add_filter( 'the_content', function( $content ) { return ! is_admin() && is_singular( 'patient' ) && in_the_loop() ? dam_patient_translated_field( $content, get_the_ID(), 'content' ) : $content; }, 8 );
function dam_patient_term_name( $term ) {
	if ( 'clinic' === $term->slug ) { return dam_patient_label( 'Clinic', 'کلینیک', 'العيادة' ); }
	if ( 'hospital' === $term->slug ) { return dam_patient_label( 'Hospital', 'بیمارستان', 'المستشفى' ); }
	$translated = get_term_meta( $term->term_id, 'dam_patient_name_' . dam_current_locale(), true );
	return $translated ?: $term->name;
}
add_filter( 'get_the_terms', function( $terms, $id, $taxonomy ) {
	if ( is_admin() || ! is_array( $terms ) || ! in_array( $taxonomy, array( 'patient_category', 'patient_tag' ), true ) ) { return $terms; }
	return array_map( function( $term ) { $copy = clone $term; $copy->name = dam_patient_term_name( $term ); return $copy; }, $terms );
}, 20, 3 );
/** Normalize after plugins convert digits, without changing numbers in URLs. */
function dam_patient_pagination( $pages, $page ) {
	$links = paginate_links( array( 'total' => $pages, 'current' => $page, 'prev_text' => dam_patient_label( '« Previous', '« قبلی', '« السابق' ), 'next_text' => dam_patient_label( 'Next »', 'بعدی »', 'التالي »' ) ) );
	$ascii = array_combine( preg_split( '//u', '۰۱۲۳۴۵۶۷۸۹٠١٢٣٤٥٦٧٨٩', -1, PREG_SPLIT_NO_EMPTY ), str_split( '01234567890123456789' ) );
	return preg_replace_callback( '/>([^<]*)</u', function( $match ) use ( $ascii ) {
		$text = strtr( $match[1], $ascii );
		$locale = dam_current_locale();
		if ( 'en' !== $locale ) { $text = strtr( $text, array_combine( str_split( '0123456789' ), preg_split( '//u', 'fa' === $locale ? '۰۱۲۳۴۵۶۷۸۹' : '٠١٢٣٤٥٦٧٨٩', -1, PREG_SPLIT_NO_EMPTY ) ) ); }
		return '>' . $text . '<';
	}, (string) $links );
}

function dam_register_patients() {
	register_post_type( 'patient', array(
		'labels' => array(
			'name' => dam_patient_label( 'Patients', 'بیماران', 'المرضى' ),
			'singular_name' => dam_patient_label( 'Patient', 'بیمار', 'المريض' ),
			'all_items' => dam_patient_label( 'All Patients', 'همه بیماران', 'جميع المرضى' ),
			'add_new' => dam_patient_label( 'Add Patient', 'افزودن بیمار', 'إضافة مريض' ),
			'add_new_item' => dam_patient_label( 'Add Patient', 'افزودن بیمار', 'إضافة مريض' ),
			'edit_item' => dam_patient_label( 'Edit Patient', 'ویرایش بیمار', 'تعديل المريض' ),
		),
		'public' => true, 'show_in_rest' => true, 'menu_icon' => 'dashicons-heart',
		'has_archive' => 'patients', 'rewrite' => array( 'slug' => 'patients' ),
		'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'revisions' ),
	) );
	foreach ( array( 'patient_category' => true, 'patient_tag' => false ) as $taxonomy => $hierarchical ) {
		$name = $hierarchical ? dam_patient_label( 'Categories', 'دسته بندی', 'التصنيفات' ) : dam_patient_label( 'Tags', 'برچسب ها', 'الوسوم' );
		register_taxonomy( $taxonomy, 'patient', array(
			'labels' => array( 'name' => $name, 'singular_name' => $name, 'menu_name' => $name ),
			'public' => true, 'show_in_rest' => true, 'show_admin_column' => true, 'hierarchical' => $hierarchical,
			'rewrite' => array( 'slug' => $hierarchical ? 'patient-category' : 'patient-tag', 'hierarchical' => $hierarchical ),
		) );
	}
	register_post_meta( 'patient', 'dam_patient_gallery', array(
		'single' => true, 'type' => 'array', 'default' => array(),
		'sanitize_callback' => 'dam_sanitize_patient_gallery',
		'auth_callback' => function( $allowed, $key, $id ) { return current_user_can( 'edit_post', $id ); },
		'show_in_rest' => array( 'schema' => array( 'type' => 'array', 'items' => array(
			'type' => 'object', 'additionalProperties' => false, 'properties' => array(
				'id' => array( 'type' => 'integer' ), 'url' => array( 'type' => 'string' ),
				'type' => array( 'type' => 'string', 'enum' => array( 'image', 'video', 'link' ) ),
				'title' => array( 'type' => 'string' ), 'description' => array( 'type' => 'string' ),
				'sensitive' => array( 'type' => 'boolean' ),
			),
		) ) ),
	) );
}
add_action( 'init', 'dam_register_patients', 11 );

function dam_sanitize_patient_gallery( $items ) {
	$out = array();
	foreach ( (array) $items as $item ) {
		if ( ! is_array( $item ) ) { continue; }
		$id = absint( $item['id'] ?? 0 );
		$url = $id ? wp_get_attachment_url( $id ) : esc_url_raw( $item['url'] ?? '', array( 'https', 'http' ) );
		if ( ! $url ) { continue; }
		$type = $item['type'] ?? 'image';
		if ( $id ) {
			$mime = get_post_mime_type( $id );
			if ( ! preg_match( '#^(image|video)/#', (string) $mime ) ) { continue; }
			$type = str_starts_with( $mime, 'video/' ) ? 'video' : 'image';
		}
		$type = in_array( $type, array( 'image', 'video', 'link' ), true ) ? $type : 'link';
		// Existing clinical media is hidden by default until each item is reviewed.
		$sensitive = 'link' !== $type && ( ! array_key_exists( 'sensitive', $item ) || filter_var( $item['sensitive'], FILTER_VALIDATE_BOOLEAN ) );
		$out[] = array( 'id' => $id, 'url' => $url, 'type' => $type, 'title' => sanitize_text_field( $item['title'] ?? '' ), 'description' => sanitize_textarea_field( $item['description'] ?? '' ), 'sensitive' => $sensitive );
	}
	return $out;
}

function dam_patient_location_ids() {
	$ids = array();
	foreach ( array( 'clinic' => 'کلینیک', 'hospital' => 'بیمارستان' ) as $slug => $name ) {
		$term = get_term_by( 'slug', $slug, 'patient_category' );
		if ( $term ) { $ids[ $slug ] = (int) $term->term_id; }
	}
	return $ids;
}

function dam_seed_patient_locations() {
	foreach ( array( 'clinic' => 'کلینیک', 'hospital' => 'بیمارستان' ) as $slug => $name ) {
		if ( ! get_term_by( 'slug', $slug, 'patient_category' ) ) { wp_insert_term( $name, 'patient_category', array( 'slug' => $slug ) ); }
	}
	if ( '3.1.0' !== get_option( 'dam_patient_schema_version' ) ) {
		flush_rewrite_rules( false );
		update_option( 'dam_patient_schema_version', '3.1.0', false );
	}
}
add_action( 'admin_init', 'dam_seed_patient_locations' );

/** Normalize every taxonomy write (REST, classic editor, quick edit, import). */
function dam_enforce_patient_location( $id, $terms = array(), $tt_ids = array(), $taxonomy = '' ) {
	static $running = false;
	if ( $running || 'patient_category' !== $taxonomy || 'patient' !== get_post_type( $id ) ) { return; }
	$locations = dam_patient_location_ids();
	if ( count( $locations ) !== 2 ) { return; }
	$current = wp_get_object_terms( $id, $taxonomy, array( 'fields' => 'ids' ) );
	if ( is_wp_error( $current ) ) { return; }
	$current = array_map( 'intval', $current );
	$selected = array_intersect( $current, array_values( $locations ) );
	$previous = get_post_meta( $id, '_dam_patient_location', true );
	$chosen = count( $selected ) === 1 ? (int) reset( $selected ) : ( $locations[ $previous ] ?? $locations['hospital'] );
	$next = array_values( array_diff( $current, array_values( $locations ) ) );
	$next[] = $chosen;
	update_post_meta( $id, '_dam_patient_location', array_search( $chosen, $locations, true ) );
	if ( count( $selected ) !== 1 ) {
		$running = true;
		wp_set_object_terms( $id, $next, $taxonomy );
		$running = false;
	}
}
add_action( 'set_object_terms', 'dam_enforce_patient_location', 20, 4 );
add_action( 'deleted_term_relationships', function( $id, $ids, $taxonomy ) { dam_enforce_patient_location( $id, array(), array(), $taxonomy ); }, 20, 3 );
add_action( 'save_post_patient', function( $id ) { if ( ! wp_is_post_revision( $id ) ) { dam_enforce_patient_location( $id, array(), array(), 'patient_category' ); } }, 30 );
add_filter( 'rest_pre_insert_patient', function( $post, $request ) {
	if ( isset( $request['patient_category'] ) ) {
		$selected = array_intersect( array_map( 'intval', $request['patient_category'] ), array_values( dam_patient_location_ids() ) );
		if ( count( $selected ) !== 1 ) { return new WP_Error( 'patient_location_required', dam_patient_label( 'Select exactly one care setting: Clinic or Hospital.', 'دقیقاً یکی از دسته‌های کلینیک یا بیمارستان را انتخاب کنید.', 'اختر العيادة أو المستشفى.' ), array( 'status' => 400 ) ); }
	}
	return $post;
}, 10, 2 );
add_filter( 'map_meta_cap', function( $caps, $cap, $user_id, $args ) {
 if ( 'delete_term' === $cap && ! empty( $args[0] ) && in_array( (int) $args[0], array_values( dam_patient_location_ids() ), true ) ) { return array( 'do_not_allow' ); }
 return $caps;
}, 10, 4 );
add_action( 'pre_delete_term', function( $id, $taxonomy ) {
 if ( 'patient_category' === $taxonomy && in_array( (int) $id, array_values( dam_patient_location_ids() ), true ) ) { wp_die( 'Clinic and Hospital categories are required.', '', array( 'response' => 403 ) ); }
}, 10, 2 );
add_filter( 'wp_update_term_parent', function( $parent, $id, $taxonomy ) {
 return 'patient_category' === $taxonomy && in_array( (int) $id, array_values( dam_patient_location_ids() ), true ) ? 0 : $parent;
}, 10, 3 );
add_filter( 'wp_update_term_data', function( $data, $id, $taxonomy ) {
	if ( 'patient_category' === $taxonomy ) {
		$slug = array_search( (int) $id, dam_patient_location_ids(), true );
		if ( $slug ) { $data['slug'] = $slug; $data['parent'] = 0; }
	}
	return $data;
}, 10, 3 );

/** Shared case records: no duplicated patient translations needed for galleries. */
add_filter( 'pll_get_post_types', function( $types ) { unset( $types['patient'] ); return $types; }, 20 );
add_filter( 'pll_get_taxonomies', function( $types ) { unset( $types['patient_category'], $types['patient_tag'] ); return $types; }, 20 );

function dam_patient_query( $term_id = 0, $direct = false, $limit = -1, $page = 1 ) {
	$args = array( 'post_type' => 'patient', 'post_status' => 'publish', 'posts_per_page' => $limit, 'paged' => $page, 'orderby' => array( 'title' => 'ASC', 'ID' => 'ASC' ), 'ignore_sticky_posts' => true, 'lang' => '' );
	if ( $term_id ) { $args['tax_query'] = array( array( 'taxonomy' => 'patient_category', 'field' => 'term_id', 'terms' => (int) $term_id, 'include_children' => ! $direct ) ); }
	return new WP_Query( $args );
}

function dam_patient_media( $patient_id ) {
	$items = dam_sanitize_patient_gallery( get_post_meta( $patient_id, 'dam_patient_gallery', true ) );
	if ( ! $items && has_post_thumbnail( $patient_id ) ) { $items = array( array( 'id' => get_post_thumbnail_id( $patient_id ), 'url' => get_the_post_thumbnail_url( $patient_id, 'large' ), 'type' => 'image', 'title' => '', 'description' => '', 'sensitive' => true ) ); }
	foreach ( $items as &$item ) {
		$item['title'] = $item['title'] ?: get_the_title( $patient_id );
		$item['description'] = $item['description'] ?: wp_strip_all_tags( get_the_excerpt( $patient_id ) );
		$item['patientUrl'] = get_permalink( $patient_id );
		$item['preview'] = 'image' === $item['type'] ? ( $item['id'] ? wp_get_attachment_image_url( $item['id'], 'medium_large' ) : $item['url'] ) : get_the_post_thumbnail_url( $patient_id, 'medium_large' );
		// HEIC originals without a generated browser-compatible preview are files,
		// not broken <img> elements. Retain the original and the reveal protection.
		if ( 'image' === $item['type'] && preg_match( '/\.(heic|heif|tiff?)(?:\?|$)/i', (string) $item['preview'] ) ) {
			$item['type'] = 'link'; $item['preview'] = '';
			$item['mediaLabel'] = dam_patient_label( 'Open original file', 'باز کردن فایل اصلی', 'فتح الملف الأصلي' );
		}
	}
	return $items;
}

function dam_patient_gallery_items( $term_id, $limit = -1 ) {
	$items = array();
	foreach ( dam_patient_query( $term_id )->posts as $patient ) {
		foreach ( dam_patient_media( $patient->ID ) as $item ) {
			$items[] = $item;
			if ( $limit > 0 && count( $items ) >= $limit ) { return $items; }
		}
	}
	return $items;
}

function dam_patient_term_image( $term_id ) {
	foreach ( dam_patient_query( $term_id )->posts as $patient ) {
		foreach ( dam_patient_media( $patient->ID ) as $item ) { if ( 'image' === $item['type'] ) { return $item; } }
	}
	return array();
}

function dam_render_patient_tree( $parent = 0, $depth = 0, $active = null ) {
	if ( $depth > 30 ) { return; }
	if ( null === $active ) {
		$active = array();
		if ( is_tax( 'patient_category' ) ) { $active[] = get_queried_object_id(); }
		elseif ( is_singular( 'patient' ) ) { $active = wp_get_object_terms( get_the_ID(), 'patient_category', array( 'fields' => 'ids' ) ); }
		if ( is_wp_error( $active ) ) { $active = array(); }
		foreach ( $active as $id ) { $active = array_merge( $active, get_ancestors( $id, 'patient_category', 'taxonomy' ) ); }
		$active = array_map( 'intval', $active );
	}
	$terms = get_terms( array( 'taxonomy' => 'patient_category', 'parent' => $parent, 'hide_empty' => false ) );
	if ( is_wp_error( $terms ) ) { return; }
	echo '<ul>';
	foreach ( $terms as $term ) {
		$patients = array_filter( dam_patient_query( $term->term_id, true )->posts, function( $patient ) use ( $term ) {
			$assigned = wp_get_object_terms( $patient->ID, 'patient_category', array( 'fields' => 'ids' ) );
			if ( is_wp_error( $assigned ) ) { return true; }
			$locations = array_values( dam_patient_location_ids() );
			if ( in_array( (int) $term->term_id, $locations, true ) && array_diff( $assigned, $locations ) ) { return false; }
			foreach ( $assigned as $id ) { if ( in_array( (int) $term->term_id, array_map( 'intval', get_ancestors( $id, 'patient_category', 'taxonomy' ) ), true ) ) { return false; } }
			return true;
		} );
		$children = get_terms( array( 'taxonomy' => 'patient_category', 'parent' => $term->term_id, 'hide_empty' => false ) );
		$expandable = $patients || ( $children && ! is_wp_error( $children ) );
		$current = is_tax( 'patient_category' ) && (int) get_queried_object_id() === (int) $term->term_id;
		echo '<li class="patient-tree-node"><a class="patient-tree-category"' . ( $current ? ' aria-current="page"' : '' ) . ' href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( dam_patient_term_name( $term ) ) . '</a>';
		if ( ! $expandable ) { echo '</li>'; continue; }
		echo '<details' . ( in_array( (int) $term->term_id, $active, true ) ? ' open' : '' ) . '><summary aria-label="' . esc_attr( dam_patient_label( 'Expand category: ', 'باز و بسته کردن دسته: ', 'توسيع التصنيف: ' ) . dam_patient_term_name( $term ) ) . '"><span class="patient-tree-toggle" aria-hidden="true"></span></summary>';
		dam_render_patient_tree( $term->term_id, $depth + 1, $active );
		if ( $patients ) {
			echo '<ul class="patient-tree-patients">';
			foreach ( $patients as $patient ) { echo '<li><a' . ( is_singular( 'patient' ) && (int) get_the_ID() === (int) $patient->ID ? ' aria-current="page"' : '' ) . ' href="' . esc_url( get_permalink( $patient ) ) . '">' . esc_html( get_the_title( $patient ) ) . '</a></li>'; }
			echo '</ul>';
		}
		echo '</details></li>';
	}
	echo '</ul>';
}

function dam_render_patient_gallery( $items, $preview = false, $visible_limit = 0 ) {
	if ( ! $items ) { echo '<p class="patient-empty">' . esc_html( dam_patient_label( 'No case media available yet.', 'هنوز رسانه‌ای برای نمایش وجود ندارد.', 'لا توجد وسائط للحالات بعد.' ) ) . '</p>'; return; }
	$hub = dam_clinic_hub_copy( dam_current_locale() );
	?>
	<div class="<?php echo $preview ? 'gallery-strip' : 'gallery-full-grid'; ?>" data-gallery-strip data-images="<?php echo esc_attr( wp_json_encode( $items ) ); ?>">
		<?php if ( $preview ) : ?><button class="gallery-nav gallery-prev" data-gallery-prev aria-label="<?php echo esc_attr( $hub['previous'] ); ?>"><?php echo dam_icon( 'chevron-left', 20 ); ?></button><?php endif; ?>
		<div class="<?php echo $preview ? 'gallery-strip-track' : 'gallery-full-track'; ?>">
			<?php foreach ( $visible_limit ? array_slice( $items, 0, $visible_limit ) : ( $preview ? array_slice( $items, 0, 4 ) : $items ) as $i => $item ) : ?>
			<button type="button" class="gallery-thumb<?php echo ! empty( $item['sensitive'] ) ? ' is-sensitive' : ''; ?>" data-gallery-thumb data-index="<?php echo (int) $i; ?>" aria-label="<?php echo esc_attr( $item['title'] ); ?>">
				<?php if ( $item['preview'] ) : ?><img class="fill-img" loading="lazy" src="<?php echo esc_url( $item['preview'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>"><?php endif; ?>
				<span class="gallery-sensitive-warning" aria-hidden="true">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M10.7429 5.09232C11.1494 5.03223 11.5686 5 12.0004 5C17.1054 5 20.4553 9.50484 21.5807 11.2868C21.7169 11.5025 21.785 11.6103 21.8231 11.7767C21.8518 11.9016 21.8517 12.0987 21.8231 12.2236C21.7849 12.3899 21.7164 12.4985 21.5792 12.7156C21.2793 13.1901 20.8222 13.8571 20.2165 14.5805M6.72432 6.71504C4.56225 8.1817 3.09445 10.2194 2.42111 11.2853C2.28428 11.5019 2.21587 11.6102 2.17774 11.7765C2.1491 11.9014 2.14909 12.0984 2.17771 12.2234C2.21583 12.3897 2.28393 12.4975 2.42013 12.7132C3.54554 14.4952 6.89541 19 12.0004 19C14.0588 19 15.8319 18.2676 17.2888 17.2766M3.00042 3L21.0004 21M9.8791 9.87868C9.3362 10.4216 9.00042 11.1716 9.00042 12C9.00042 13.6569 10.3436 15 12.0004 15C12.8288 15 13.5788 14.6642 14.1217 14.1213" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
					<span><?php echo esc_html( dam_patient_label( 'Sensitive image', 'تصویر حساس', 'صورة حساسة' ) ); ?> · <span class="gallery-sensitive-action"><?php echo esc_html( dam_patient_label( 'View', 'نمایش', 'عرض' ) ); ?></span></span>
				</span>
				<span><?php echo 'image' === $item['type'] ? esc_html( $item['title'] ) : '▶ ' . esc_html( $item['title'] ); ?></span>
			</button>
			<?php endforeach; ?>
		</div>
		<?php if ( $preview ) : ?><button class="gallery-nav gallery-next" data-gallery-next aria-label="<?php echo esc_attr( $hub['next'] ); ?>"><?php echo dam_icon( 'chevron-right', 20 ); ?></button><?php endif; ?>
	</div>
	<?php
}

/** Shared cover and complete category trail for taxonomy and case pages. */
function dam_render_patient_cover( $title, $term_id = 0, $patient_id = 0 ) {
	$locale = dam_current_locale();
	$area = $patient_id ? get_post_meta( $patient_id, '_dam_patient_location', true ) : 'hospital';
	if ( 'clinic' !== $area ) { $area = 'hospital'; }
	if ( $patient_id ) {
		$terms = wp_get_object_terms( $patient_id, 'patient_category' );
		if ( ! is_wp_error( $terms ) ) {
			$best_depth = -1;
			foreach ( $terms as $term ) {
				if ( in_array( $term->slug, array( 'clinic', 'hospital' ), true ) ) { $area = $term->slug; continue; }
				$depth = count( get_ancestors( $term->term_id, 'patient_category', 'taxonomy' ) );
				if ( $depth > $best_depth ) { $term_id = $term->term_id; $best_depth = $depth; }
			}
		}
	} elseif ( $term_id ) {
		$term = get_term( $term_id, 'patient_category' );
		if ( $term && ! is_wp_error( $term ) && 'clinic' === $term->slug ) { $area = 'clinic'; }
	}
	$hub = dam_clinic_hub_copy( $locale );
	$items = array(
		array( 'label' => dam_breadcrumb_home_label( $locale ), 'url' => dam_front_page_clean_url( $locale ) ),
		array( 'label' => dam_patient_label( 'Clinic', 'کلینیک', 'العيادة' ), 'url' => dam_localized_page_url( 'clinical-care', $locale ) ),
		array( 'label' => $hub[ 'clinic' === $area ? 'clinicGalleryTitle' : 'hospitalGalleryTitle' ], 'url' => dam_localized_page_url( $area . '-gallery', $locale ) ),
	);
	if ( $term_id ) {
		foreach ( array_merge( array_reverse( get_ancestors( $term_id, 'patient_category', 'taxonomy' ) ), array( $term_id ) ) as $id ) {
			$term = get_term( $id, 'patient_category' );
			if ( $term && ! is_wp_error( $term ) ) { $items[] = array( 'label' => dam_patient_term_name( $term ), 'url' => get_term_link( $term ) ); }
		}
	}
	if ( $patient_id || ! $term_id ) { $items[] = array( 'label' => $title, 'url' => null ); }
	$items[ count( $items ) - 1 ]['url'] = null;
	?>
	<section class="interior-cover">
		<img class="fill-img" src="<?php echo esc_url( dam_media_url( 'clinic' === $area ? 'clinic-08' : 'hospital-14' ) ); ?>" alt="">
		<div class="interior-cover-gradient" aria-hidden="true"></div>
		<div class="interior-cover-content section-shell"><p class="section-index light"><?php echo esc_html( $hub['pathwaysKicker'] ); ?></p><h1><?php echo esc_html( $title ); ?></h1></div>
	</section>
	<nav class="site-breadcrumbs" aria-label="<?php echo esc_attr( dam_patient_label( 'Breadcrumb', 'مسیر صفحه', 'مسار التنقل' ) ); ?>"><div class="section-shell"><ol class="dam-breadcrumb-list">
	<?php foreach ( $items as $i => $item ) : ?><li><?php if ( $item['url'] ) : ?><a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a><?php else : ?><span aria-current="page"><?php echo esc_html( $item['label'] ); ?></span><?php endif; ?><?php if ( $i < count( $items ) - 1 ) : ?><span class="dam-breadcrumb-sep" aria-hidden="true"><?php echo esc_html( dam_breadcrumb_separator( $locale ) ); ?></span><?php endif; ?></li><?php endforeach; ?>
	</ol></div></nav>
	<?php
}

function dam_render_patient_archive( $term_id = 0, $title = '', $heading_level = 1 ) {
	if ( ! $title ) { $title = dam_patient_label( 'Patients', 'بیماران', 'المرضى' ); }
	if ( 1 === $heading_level ) { dam_render_patient_cover( $title, $term_id ); $heading_level = 2; }
	$parent = $term_id;
	$children = get_terms( array( 'taxonomy' => 'patient_category', 'parent' => $parent, 'hide_empty' => false ) );
	$page = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
	$query = dam_patient_query( $term_id, false, 12, $page );
	if ( is_tax( 'patient_tag' ) ) {
		$query = new WP_Query( array( 'post_type' => 'patient', 'post_status' => 'publish', 'posts_per_page' => 12, 'paged' => $page, 'lang' => '', 'tax_query' => array( array( 'taxonomy' => 'patient_tag', 'terms' => get_queried_object_id() ) ) ) );
		$children = array();
	}
	?>
	<section class="section-shell section-space patient-archive"><h<?php echo 2 === $heading_level ? '2' : '1'; ?>><?php echo esc_html( $title ); ?></h<?php echo 2 === $heading_level ? '2' : '1'; ?>>
	<div class="patient-archive-layout">
		<aside class="patient-sidebar" aria-label="<?php echo esc_attr( dam_patient_label( 'Patient categories', 'دسته‌بندی بیماران', 'تصنيفات المرضى' ) ); ?>"><h2><?php echo esc_html( dam_patient_label( 'Categories', 'دسته‌بندی‌ها', 'التصنيفات' ) ); ?></h2><?php dam_render_patient_tree(); ?></aside>
		<div>
		<?php if ( $children && ! is_wp_error( $children ) ) : ?><div class="patient-category-grid">
			<?php foreach ( $children as $term ) : $image = dam_patient_term_image( $term->term_id ); ?>
			<a class="patient-category-card<?php echo ! empty( $image['sensitive'] ) ? ' is-sensitive' : ''; ?>" href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php if ( $image ) : ?><img loading="lazy" src="<?php echo esc_url( $image['preview'] ?: $image['url'] ); ?>" alt=""><?php endif; ?><span><?php echo esc_html( dam_patient_term_name( $term ) ); ?></span><?php if ( ! empty( $image['sensitive'] ) ) : ?><small><?php echo esc_html( dam_patient_label( 'Sensitive image', 'تصویر حساس', 'صورة حساسة' ) ); ?></small><?php endif; ?></a>
			<?php endforeach; ?>
		</div><?php endif; ?>
		<div class="patient-albums">
		<?php foreach ( $query->posts as $patient ) : $items = dam_patient_media( $patient->ID ); ?>
		<article class="patient-album">
		<?php dam_render_patient_gallery( $items, true, 1 ); ?>
		<h3><a href="<?php echo esc_url( get_permalink( $patient ) ); ?>"><?php echo esc_html( get_the_title( $patient ) ); ?></a></h3>
		<p><?php echo esc_html( count( $items ) . ' ' . dam_patient_label( 'media items', 'رسانه', 'وسائط' ) ); ?></p>
		</article>
		<?php endforeach; ?>
		</div>
		<nav class="patient-pagination" aria-label="<?php echo esc_attr( dam_patient_label( 'Pagination', 'صفحه‌بندی', 'ترقيم الصفحات' ) ); ?>"><?php echo wp_kses_post( dam_patient_pagination( $query->max_num_pages, $page ) ); ?></nav>
		</div>
	</div></section>
	<?php
}
