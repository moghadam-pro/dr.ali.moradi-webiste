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
