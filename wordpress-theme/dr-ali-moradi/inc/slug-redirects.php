<?php
/**
 * 301-redirects for URLs whose slug changed during the fa/ar
 * same-slug-as-English cleanup (see docs/wordpress-theme/progress-log.md
 * on the docs branch for context -- every fa/ar page/post previously
 * needed its own "-fa"/"-ar"-suffixed slug distinct from the English
 * version; Polylang supports identical slugs across languages, so that
 * suffix was never actually necessary).
 *
 * WordPress's own automatic old-slug redirect (_wp_old_slug postmeta,
 * checked by wp_old_slug_redirect() on template_redirect) did not fire
 * for slugs changed through a REST API update on this install --
 * confirmed live: renaming a page's slug via the REST API and then
 * requesting its old URL returned a real 404, not a 301, even several
 * seconds later. Rather than debug why, this records an explicit
 * old-path -> new-URL map at rename time and redirects from it
 * directly, independent of core's mechanism.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dam_slug_redirect_map() {
	return get_option( 'dam_slug_redirects', array() );
}

function dam_maybe_redirect_old_slug() {
	if ( ! is_404() ) {
		return;
	}
	$map = dam_slug_redirect_map();
	if ( empty( $map ) ) {
		return;
	}
	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
	if ( isset( $map[ $path ] ) ) {
		wp_safe_redirect( $map[ $path ], 301 );
		exit;
	}
}
add_action( 'template_redirect', 'dam_maybe_redirect_old_slug' );

/**
 * Admin-only REST route used once, from the browser console while
 * logged in, to record the old-path -> new-URL pairs collected while
 * bulk-renaming slugs. Not part of any ongoing editing workflow.
 */
function dam_register_slug_redirect_route() {
	register_rest_route(
		'dam/v1',
		'/slug-redirects',
		array(
			'methods'             => 'POST',
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			},
			'callback'            => function ( WP_REST_Request $request ) {
				$incoming = $request->get_param( 'redirects' );
				if ( ! is_array( $incoming ) ) {
					return new WP_Error( 'dam_invalid_redirects', 'redirects must be an object', array( 'status' => 400 ) );
				}
				$map = dam_slug_redirect_map();
				foreach ( $incoming as $old_path => $new_url ) {
					$map[ trim( (string) $old_path, '/' ) ] = esc_url_raw( (string) $new_url );
				}
				update_option( 'dam_slug_redirects', $map, false );
				return array( 'count' => count( $map ) );
			},
		)
	);
}
add_action( 'rest_api_init', 'dam_register_slug_redirect_route' );
