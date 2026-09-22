<?php
/**
 * Versioned, repeat-safe content migrations for the theme.
 *
 * Version 1.0.0 consolidates conditions, innovations, publications, and
 * patient resources into standard Posts. IDs, authors, dates, statuses,
 * content, excerpts, media, comments, metadata, and Polylang language links
 * remain attached to the same database records.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DAM_CONTENT_SCHEMA_VERSION', '1.0.0' );
define( 'DAM_CONTENT_SCHEMA_OPTION', 'dam_content_schema_version' );
define( 'DAM_CONTENT_MIGRATION_REPORT_OPTION', 'dam_content_migration_report' );

/**
 * The four retired types and their replacement category translations.
 */
function dam_legacy_content_map() {
	return array(
		'condition'        => array(
			'legacy_base' => 'conditions',
			'categories'  => array(
				'en' => array( 'name' => 'Clinical Conditions', 'slug' => 'clinical-conditions' ),
				'fa' => array( 'name' => 'بیماری‌ها', 'slug' => 'clinical-conditions-fa' ),
				'ar' => array( 'name' => 'الحالات المرضية', 'slug' => 'clinical-conditions-ar' ),
			),
		),
		'innovation'       => array(
			'legacy_base' => 'innovation',
			'categories'  => array(
				'en' => array( 'name' => 'Innovation', 'slug' => 'innovation-news' ),
				'fa' => array( 'name' => 'نوآوری', 'slug' => 'innovation-fa' ),
				'ar' => array( 'name' => 'الابتكار', 'slug' => 'innovation-ar' ),
			),
		),
		'publication'      => array(
			'legacy_base' => 'publications',
			'categories'  => array(
				'en' => array( 'name' => 'Publications', 'slug' => 'publications' ),
				'fa' => array( 'name' => 'انتشارات', 'slug' => 'publications-fa' ),
				'ar' => array( 'name' => 'المنشورات', 'slug' => 'publications-ar' ),
			),
		),
		'patient_resource' => array(
			'legacy_base' => 'patient-resources',
			'categories'  => array(
				'en' => array( 'name' => 'Patient Resources', 'slug' => 'patient-resources' ),
				'fa' => array( 'name' => 'منابع بیماران', 'slug' => 'patient-resources-fa' ),
				'ar' => array( 'name' => 'موارد المرضى', 'slug' => 'patient-resources-ar' ),
			),
		),
	);
}

/**
 * Return raw IDs for a post type without locale/status filters.
 */
function dam_legacy_content_ids( $post_type ) {
	global $wpdb;

	return array_map(
		'intval',
		$wpdb->get_col(
			$wpdb->prepare(
				"SELECT ID FROM {$wpdb->posts} WHERE post_type = %s ORDER BY ID ASC",
				$post_type
			)
		)
	);
}

function dam_has_pending_legacy_content() {
	if ( version_compare( (string) get_option( DAM_CONTENT_SCHEMA_OPTION, '0.0.0' ), DAM_CONTENT_SCHEMA_VERSION, '>=' ) ) {
		return false;
	}

	foreach ( array_keys( dam_legacy_content_map() ) as $post_type ) {
		if ( dam_legacy_content_ids( $post_type ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Register retired types without UI/routes only until their records migrate.
 * This keeps Polylang translation APIs aware of the existing records during
 * the one-time post-type transition while removing their admin menu entries.
 */
function dam_register_pending_legacy_post_types() {
	if ( ! dam_has_pending_legacy_content() ) {
		return;
	}

	foreach ( array_keys( dam_legacy_content_map() ) as $post_type ) {
		register_post_type(
			$post_type,
			array(
				'public'             => true,
				'publicly_queryable' => false,
				'show_ui'            => false,
				'show_in_menu'       => false,
				'show_in_rest'       => false,
				'exclude_from_search' => true,
				'has_archive'        => false,
				'rewrite'            => false,
				'query_var'          => false,
				'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
			)
		);
	}
}

/**
 * Resolve a post language without assuming Polylang is active.
 */
function dam_migration_post_language( $post_id ) {
	if ( function_exists( 'pll_get_post_language' ) ) {
		$language = pll_get_post_language( $post_id, 'slug' );
		if ( $language ) {
			return $language;
		}
	}

	if ( taxonomy_exists( 'language' ) ) {
		$languages = wp_get_object_terms( $post_id, 'language', array( 'fields' => 'slugs' ) );
		if ( ! is_wp_error( $languages ) && $languages ) {
			return (string) reset( $languages );
		}
	}

	return 'en';
}

/**
 * Create/reuse localized replacement categories and link translations.
 */
function dam_ensure_migration_categories( $definitions ) {
	$term_ids = array();
	$english  = get_term_by( 'slug', $definitions['en']['slug'], 'category' );

	foreach ( $definitions as $language => $definition ) {
		$term = false;

		if ( $english && 'en' !== $language && function_exists( 'pll_get_term' ) ) {
			$translated_id = pll_get_term( $english->term_id, $language );
			$term          = $translated_id ? get_term( $translated_id, 'category' ) : false;
		}

		if ( ! $term || is_wp_error( $term ) ) {
			$term = get_term_by( 'slug', $definition['slug'], 'category' );
		}

		if ( ! $term ) {
			$created = wp_insert_term(
				$definition['name'],
				'category',
				array( 'slug' => $definition['slug'] )
			);
			if ( is_wp_error( $created ) ) {
				return $created;
			}
			$term = get_term( $created['term_id'], 'category' );
		}

		$term_ids[ $language ] = (int) $term->term_id;
		if ( function_exists( 'pll_set_term_language' ) ) {
			pll_set_term_language( $term->term_id, $language );
		}
	}

	if ( function_exists( 'pll_save_term_translations' ) ) {
		pll_save_term_translations( $term_ids );
	}

	return $term_ids;
}

function dam_legacy_permalink_path( $post, $language, $legacy_base ) {
	$prefix = 'en' === $language ? '' : trailingslashit( $language );
	return '/' . $prefix . trailingslashit( $legacy_base ) . trailingslashit( $post->post_name );
}

/**
 * Run the schema migration once from an authenticated admin request.
 */
function dam_maybe_migrate_legacy_content() {
	if ( ! current_user_can( 'manage_options' ) || wp_doing_ajax() ) {
		return;
	}

	$current_version = (string) get_option( DAM_CONTENT_SCHEMA_OPTION, '0.0.0' );
	if ( version_compare( $current_version, DAM_CONTENT_SCHEMA_VERSION, '>=' ) ) {
		return;
	}

	if ( get_transient( 'dam_content_migration_lock' ) ) {
		return;
	}
	set_transient( 'dam_content_migration_lock', 1, 5 * MINUTE_IN_SECONDS );

	$report = array(
		'version'    => DAM_CONTENT_SCHEMA_VERSION,
		'run_at_gmt' => current_time( 'mysql', true ),
		'migrated'   => array(),
		'errors'     => array(),
	);
	$translation_groups = array();

	try {
		foreach ( dam_legacy_content_map() as $post_type => $mapping ) {
			$category_ids = dam_ensure_migration_categories( $mapping['categories'] );
			if ( is_wp_error( $category_ids ) ) {
				$report['errors'][] = $post_type . ': ' . $category_ids->get_error_message();
				continue;
			}

			$report['migrated'][ $post_type ] = 0;
			foreach ( dam_legacy_content_ids( $post_type ) as $post_id ) {
				$post = get_post( $post_id );
				if ( ! $post ) {
					continue;
				}

				$language = dam_migration_post_language( $post_id );
				if ( ! isset( $category_ids[ $language ] ) ) {
					$language = 'en';
				}

				if ( function_exists( 'pll_get_post_translations' ) ) {
					$translations = array_filter( array_map( 'intval', pll_get_post_translations( $post_id ) ) );
					if ( $translations ) {
						$group_key = implode( '-', array_values( $translations ) );
						$translation_groups[ $group_key ] = $translations;
					}
				}

				update_post_meta( $post_id, '_dam_legacy_post_type', $post_type );
				update_post_meta(
					$post_id,
					'_dam_legacy_permalink_path',
					dam_legacy_permalink_path( $post, $language, $mapping['legacy_base'] )
				);

				$updated = wp_update_post(
					array(
						'ID'        => $post_id,
						'post_type' => 'post',
					),
					true
				);

				if ( is_wp_error( $updated ) ) {
					$report['errors'][] = $post_type . ' #' . $post_id . ': ' . $updated->get_error_message();
					continue;
				}

				wp_set_post_categories( $post_id, array( $category_ids[ $language ] ), true );
				if ( function_exists( 'pll_set_post_language' ) ) {
					pll_set_post_language( $post_id, $language );
				}
				++$report['migrated'][ $post_type ];
			}
		}

		if ( function_exists( 'pll_save_post_translations' ) ) {
			foreach ( $translation_groups as $translations ) {
				pll_save_post_translations( $translations );
			}
		}

		if ( empty( $report['errors'] ) ) {
			update_option( DAM_CONTENT_SCHEMA_OPTION, DAM_CONTENT_SCHEMA_VERSION, false );
			flush_rewrite_rules( false );
		}

		update_option( DAM_CONTENT_MIGRATION_REPORT_OPTION, $report, false );
	} finally {
		delete_transient( 'dam_content_migration_lock' );
	}
}
add_action( 'admin_init', 'dam_maybe_migrate_legacy_content', 20 );

/**
 * Preserve inbound links to the retired CPT URLs after conversion.
 */
function dam_redirect_legacy_content_urls() {
	global $wp;

	if ( ! is_404() || empty( $wp->request ) ) {
		return;
	}

	$request_path = '/' . trailingslashit( trim( $wp->request, '/' ) );
	$posts        = get_posts(
		array(
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'meta_key'               => '_dam_legacy_permalink_path',
			'meta_value'             => $request_path,
			'no_found_rows'          => true,
			'suppress_filters'       => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	if ( $posts ) {
		wp_safe_redirect( get_permalink( $posts[0] ), 301, 'Dr Ali Moradi theme content migration' );
		exit;
	}
}
add_action( 'template_redirect', 'dam_redirect_legacy_content_urls', 1 );
