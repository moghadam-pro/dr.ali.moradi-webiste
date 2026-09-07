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
 * Makes the static front page (Settings -> Reading -> "Homepage displays")
 * and the posts page ("Posts page") resolve to the current language's
 * translation of whichever page is configured, instead of always the one
 * page ID stored in the (single, global, not Polylang-aware) `page_on_front`
 * / `page_for_posts` options.
 *
 * Confirmed by testing that Polylang does not do this on its own for
 * either option: unlike Nav Menu locations (which Polylang gives a
 * distinct location per language, set from Appearance -> Menus), the
 * classic Reading Settings screen has one flat page picker with no
 * per-language variant, and saving it — through the REST API or through
 * that screen itself — only ever stores a single id. Confirmed
 * concretely: with `page_on_front` set to the English translation of a
 * placeholder page (all three languages linked as Polylang
 * translations), `/fa/` and `/ar/` still rendered the posts listing
 * instead of `front-page.html`, both before and after re-saving Reading
 * Settings through wp-admin.
 *
 * `is_front_page()` and the query parsing behind `is_home()` both read
 * these two options directly, so filtering the *option value itself* to
 * the current-language translation fixes both without touching how
 * WordPress otherwise decides what "the front page" or "the posts page"
 * means — the theme's own code bridging a free-tier Polylang gap, same
 * as the /link-translations and /set-menu-location REST routes above.
 */
function dam_translate_front_page_option( $post_id ) {
	if ( ! $post_id || ! function_exists( 'pll_get_post' ) || ! function_exists( 'pll_current_language' ) ) {
		return $post_id;
	}

	$language = pll_current_language();
	if ( ! $language ) {
		return $post_id;
	}

	$translated = pll_get_post( (int) $post_id, $language );
	return $translated ? $translated : $post_id;
}
add_filter( 'option_page_on_front', 'dam_translate_front_page_option' );
add_filter( 'option_page_for_posts', 'dam_translate_front_page_option' );

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

	register_rest_route(
		'dr-ali-moradi/v1',
		'/set-menu-location',
		array(
			'methods'             => 'POST',
			'permission_callback' => function () {
				return current_user_can( 'edit_theme_options' );
			},
			'callback'            => function ( WP_REST_Request $request ) {
				// Assigns a menu to a nav menu location with a plain
				// set_theme_mod() write, including Polylang's per-language
				// location keys (e.g. "primary___fa" for "Primary
				// Navigation فارسی"). Those compound keys are only
				// understood by Polylang's own admin-screen markup, not by
				// core's get_registered_nav_menus(), so the core
				// `/wp/v2/menus` REST endpoint's schema validation rejects
				// them.
				//
				// Known limitation, confirmed by testing: Polylang hooks
				// `pre_update_option_theme_mods_{theme}` and reprocesses
				// this option on its own terms when the *classic Menus
				// screen* submits it, splitting the per-language mapping
				// into its own internal storage; a raw REST/API write like
				// this one is not run through that processing, so
				// `has_nav_menu()` keeps reading the location as unset
				// (Polylang's read-side filter zeroes every location,
				// including ones this route never touched) until each menu
				// is opened once in Appearance -> Menus and actually saved
				// via that screen's own "Save Menu" button. This route is
				// still useful to pre-seed the assignment before that
				// manual step, and for a plain (non-Polylang) location.
				// See progress-log.md on the docs branch.
				$location = $request->get_param( 'location' );
				$menu_id  = (int) $request->get_param( 'menu_id' );

				if ( ! $location || ! $menu_id || ! wp_get_nav_menu_object( $menu_id ) ) {
					return new WP_Error( 'invalid_params', 'Provide a location and a valid menu_id.', array( 'status' => 400 ) );
				}

				$locations             = get_theme_mod( 'nav_menu_locations', array() );
				$locations[ $location ] = $menu_id;
				set_theme_mod( 'nav_menu_locations', $locations );

				return rest_ensure_response( array( 'nav_menu_locations' => $locations ) );
			},
		)
	);
}
add_action( 'rest_api_init', 'dam_register_migration_rest_routes' );
