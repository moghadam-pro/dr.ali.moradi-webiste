<?php
/**
 * Static, theme-authored strings (not tied to a specific post) that need
 * translation. Registered through Polylang's free string-translation
 * feature (Languages -> String translation) so they can be translated
 * without editing template parts per language — see the "known
 * constraint" note in wordpress-theme/architecture.md on the docs branch.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dam_register_polylang_strings() {
	if ( ! function_exists( 'pll_register_string' ) ) {
		return;
	}

	$group = 'Dr. Ali Moradi Theme';

	pll_register_string( 'appointment_cta_label', 'Book an appointment', $group );
	pll_register_string( 'whatsapp_label', 'WhatsApp', $group );
	pll_register_string( 'impact_as_of_prefix', 'As of', $group );
	pll_register_string( 'search_placeholder', 'Search the site', $group );
	pll_register_string( 'not_found_title', 'Nothing found here', $group );
	pll_register_string( 'not_found_body', 'The page you are looking for may have moved. Try the search below or return to the homepage.', $group );
}
add_action( 'init', 'dam_register_polylang_strings' );

/**
 * Small helper so block render.php files don't need to repeat the
 * function_exists() guard everywhere.
 */
function dam_translate_string( $name, $fallback ) {
	if ( function_exists( 'pll__' ) ) {
		return pll__( $fallback );
	}
	return $fallback;
}

/**
 * One-off REST bridge used by the content-migration import script
 * (wordpress-theme/content-migration/import-to-wordpress.mjs) to link
 * per-locale posts/pages/CPT entries into a single Polylang translation
 * group. Polylang's free REST integration sets a single post's `lang` on
 * create (?lang=xx, already used directly against the core wp/v2
 * endpoints) but does not expose translation-group linking outside of
 * Polylang Pro's REST API. `pll_save_post_translations()` itself is a
 * free-tier function (part of Polylang's public API since early
 * versions) — this only exposes it over REST from the theme's own code,
 * it does not depend on Polylang Pro.
 */
function dam_register_migration_rest_routes() {
	register_rest_route(
		'dr-ali-moradi/v1',
		'/link-translations',
		array(
			'methods'             => 'POST',
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			},
			'callback'            => function ( WP_REST_Request $request ) {
				if ( ! function_exists( 'pll_save_post_translations' ) ) {
					return new WP_Error( 'polylang_missing', 'Polylang is not active.', array( 'status' => 500 ) );
				}

				$translations = $request->get_param( 'translations' );
				if ( ! is_array( $translations ) || count( $translations ) < 2 ) {
					return new WP_Error( 'invalid_translations', 'Provide at least two {lang: post_id} pairs.', array( 'status' => 400 ) );
				}

				foreach ( $translations as $lang => $post_id ) {
					if ( ! get_post( (int) $post_id ) ) {
						return new WP_Error( 'invalid_post_id', "Post {$post_id} for language {$lang} does not exist.", array( 'status' => 400 ) );
					}
				}

				$group = pll_save_post_translations( array_map( 'intval', $translations ) );

				return rest_ensure_response( array( 'translations' => $group ) );
			},
		)
	);

	register_rest_route(
		'dr-ali-moradi/v1',
		'/link-term-translations',
		array(
			'methods'             => 'POST',
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			},
			'callback'            => function ( WP_REST_Request $request ) {
				if ( ! function_exists( 'pll_save_term_translations' ) ) {
					return new WP_Error( 'polylang_missing', 'Polylang is not active.', array( 'status' => 500 ) );
				}

				$translations = $request->get_param( 'translations' );
				if ( ! is_array( $translations ) || count( $translations ) < 2 ) {
					return new WP_Error( 'invalid_translations', 'Provide at least two {lang: term_id} pairs.', array( 'status' => 400 ) );
				}

				foreach ( $translations as $lang => $term_id ) {
					if ( ! get_term( (int) $term_id ) ) {
						return new WP_Error( 'invalid_term_id', "Term {$term_id} for language {$lang} does not exist.", array( 'status' => 400 ) );
					}
				}

				$group = pll_save_term_translations( array_map( 'intval', $translations ) );

				return rest_ensure_response( array( 'translations' => $group ) );
			},
		)
	);
}
add_action( 'rest_api_init', 'dam_register_migration_rest_routes' );
