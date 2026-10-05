<?php
/** Patient catalogue, independent of article categories and translated pages. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function dam_patient_label( $en, $fa, $ar = '' ) {
	$locale = is_admin() ? substr( determine_locale(), 0, 2 ) : dam_current_locale();
	return 'fa' === $locale ? $fa : ( 'ar' === $locale && $ar ? $ar : $en );
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
			'public' => true, 'show_in_rest' => true, 'hierarchical' => $hierarchical,
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
		$out[] = array( 'id' => $id, 'url' => $url, 'type' => in_array( $type, array( 'image', 'video', 'link' ), true ) ? $type : 'link', 'title' => sanitize_text_field( $item['title'] ?? '' ), 'description' => sanitize_textarea_field( $item['description'] ?? '' ) );
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
	if ( ! $items && has_post_thumbnail( $patient_id ) ) { $items = array( array( 'id' => get_post_thumbnail_id( $patient_id ), 'url' => get_the_post_thumbnail_url( $patient_id, 'large' ), 'type' => 'image', 'title' => '', 'description' => '' ) ); }
	foreach ( $items as &$item ) {
		$item['title'] = $item['title'] ?: get_the_title( $patient_id );
		$item['description'] = $item['description'] ?: wp_strip_all_tags( get_the_excerpt( $patient_id ) );
		$item['patientUrl'] = get_permalink( $patient_id );
		$item['preview'] = 'image' === $item['type'] ? ( $item['id'] ? wp_get_attachment_image_url( $item['id'], 'medium_large' ) : $item['url'] ) : get_the_post_thumbnail_url( $patient_id, 'medium_large' );
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
		foreach ( dam_patient_media( $patient->ID ) as $item ) { if ( 'image' === $item['type'] ) { return $item['preview'] ?: $item['url']; } }
	}
	return '';
}

function dam_render_patient_tree( $parent = 0, $depth = 0 ) {
	if ( $depth > 30 ) { return; }
	$terms = get_terms( array( 'taxonomy' => 'patient_category', 'parent' => $parent, 'hide_empty' => false ) );
	if ( is_wp_error( $terms ) ) { return; }
	echo '<ul>';
	foreach ( $terms as $term ) {
		echo '<li><details><summary><span class="patient-tree-toggle" aria-hidden="true"></span><a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a></summary>';
		dam_render_patient_tree( $term->term_id, $depth + 1 );
		$patients = dam_patient_query( $term->term_id, true )->posts;
		if ( $patients ) {
			echo '<ul class="patient-tree-patients">';
			foreach ( $patients as $patient ) { echo '<li><a href="' . esc_url( get_permalink( $patient ) ) . '">' . esc_html( $patient->post_title ) . '</a></li>'; }
			echo '</ul>';
		}
		echo '</details></li>';
	}
	echo '</ul>';
}

function dam_render_patient_gallery( $items, $preview = false ) {
	if ( ! $items ) { echo '<p class="patient-empty">' . esc_html( dam_patient_label( 'No published cases yet.', 'هنوز پرونده‌ای منتشر نشده است.', 'لا توجد حالات منشورة بعد.' ) ) . '</p>'; return; }
	$hub = dam_clinic_hub_copy( dam_current_locale() );
	?>
	<div class="<?php echo $preview ? 'gallery-strip' : 'gallery-full-grid'; ?>" data-gallery-strip data-images="<?php echo esc_attr( wp_json_encode( $items ) ); ?>">
		<?php if ( $preview ) : ?><button class="gallery-nav gallery-prev" data-gallery-prev aria-label="<?php echo esc_attr( $hub['previous'] ); ?>"><?php echo dam_icon( 'chevron-left', 20 ); ?></button><?php endif; ?>
		<div class="<?php echo $preview ? 'gallery-strip-track' : 'gallery-full-track'; ?>">
			<?php foreach ( $preview ? array_slice( $items, 0, 4 ) : $items as $i => $item ) : ?>
			<button type="button" class="gallery-thumb" data-gallery-thumb data-index="<?php echo (int) $i; ?>" aria-label="<?php echo esc_attr( $item['title'] ); ?>">
				<?php if ( $item['preview'] ) : ?><img class="fill-img" loading="lazy" src="<?php echo esc_url( $item['preview'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>"><?php endif; ?>
				<span><?php echo 'image' === $item['type'] ? esc_html( $item['title'] ) : '▶ ' . esc_html( $item['title'] ); ?></span>
			</button>
			<?php endforeach; ?>
		</div>
		<?php if ( $preview ) : ?><button class="gallery-nav gallery-next" data-gallery-next aria-label="<?php echo esc_attr( $hub['next'] ); ?>"><?php echo dam_icon( 'chevron-right', 20 ); ?></button><?php endif; ?>
	</div>
	<?php
}

function dam_render_patient_archive( $term_id = 0, $title = '' ) {
	if ( ! $title ) { $title = dam_patient_label( 'Patients', 'بیماران', 'المرضى' ); }
	$parent = $term_id;
	$children = get_terms( array( 'taxonomy' => 'patient_category', 'parent' => $parent, 'hide_empty' => false ) );
	$page = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
	$query = dam_patient_query( $term_id, false, 12, $page );
	if ( is_tax( 'patient_tag' ) ) {
		$query = new WP_Query( array( 'post_type' => 'patient', 'post_status' => 'publish', 'posts_per_page' => 12, 'paged' => $page, 'lang' => '', 'tax_query' => array( array( 'taxonomy' => 'patient_tag', 'terms' => get_queried_object_id() ) ) ) );
		$children = array();
	}
	?>
	<section class="section-shell section-space patient-archive"><h1><?php echo esc_html( $title ); ?></h1>
	<div class="patient-archive-layout">
		<aside class="patient-sidebar" aria-label="<?php echo esc_attr( dam_patient_label( 'Patient categories', 'دسته‌بندی بیماران', 'تصنيفات المرضى' ) ); ?>"><h2><?php echo esc_html( dam_patient_label( 'Categories', 'دسته‌بندی‌ها', 'التصنيفات' ) ); ?></h2><?php dam_render_patient_tree(); ?></aside>
		<div>
		<?php if ( $children && ! is_wp_error( $children ) ) : ?><div class="patient-category-grid">
			<?php foreach ( $children as $term ) : $image = dam_patient_term_image( $term->term_id ); ?>
			<a class="patient-category-card" href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php if ( $image ) : ?><img loading="lazy" src="<?php echo esc_url( $image ); ?>" alt=""><?php endif; ?><span><?php echo esc_html( $term->name ); ?></span></a>
			<?php endforeach; ?>
		</div><?php endif; ?>
		<?php $items = array(); foreach ( $query->posts as $patient ) { $items = array_merge( $items, dam_patient_media( $patient->ID ) ); } dam_render_patient_gallery( $items ); ?>
		<nav class="patient-pagination" aria-label="<?php echo esc_attr( dam_patient_label( 'Pagination', 'صفحه‌بندی', 'ترقيم الصفحات' ) ); ?>"><?php echo wp_kses_post( paginate_links( array( 'total' => $query->max_num_pages, 'current' => $page ) ) ); ?></nav>
		</div>
	</div></section>
	<?php
}
