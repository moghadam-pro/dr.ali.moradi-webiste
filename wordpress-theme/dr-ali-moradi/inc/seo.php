<?php
/**
 * SEO fixes on top of Rank Math + Polylang.
 *
 * The static front page WordPress/Polylang require behind the scenes
 * (a real Page per language, `front-page-placeholder-{lang}`) is an
 * internal implementation detail -- `front-page.html` always renders the
 * real homepage regardless of that page's own content. Without the fixes
 * below, three real, confirmed bugs reach search engines and social
 * shares on every non-English homepage:
 *   1. WordPress's own redirect_canonical() 301s the clean language root
 *      (`/fa/`, `/ar/`) to that placeholder page's own permalink
 *      (`/fa/front-page-placeholder-fa/`), so the indexable URL is the
 *      internal one, not the clean one.
 *   2. Rank Math then emits that placeholder URL as the canonical link
 *      and in the hreflang alternates.
 *   3. The placeholder page's own body text -- a note explaining it only
 *      exists to satisfy WordPress's "static front page" requirement --
 *      leaks out as the meta description, og:description, and
 *      twitter:description search engines and social previews show.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The clean, canonical URL for a language's homepage -- `/`, `/fa/`,
 * `/ar/` -- never the internal front-page-placeholder permalink.
 */
function dam_front_page_clean_url( $locale = null ) {
	$locale = $locale ? $locale : dam_current_locale();
	if ( function_exists( 'pll_home_url' ) ) {
		return pll_home_url( $locale );
	}
	return home_url( '/' );
}

/**
 * Stop WordPress redirecting the clean language root to the internal
 * placeholder page's own permalink.
 */
function dam_disable_front_page_redirect( $redirect_url ) {
	if ( is_front_page() ) {
		return false;
	}
	return $redirect_url;
}
add_filter( 'redirect_canonical', 'dam_disable_front_page_redirect' );

/**
 * Real, already-approved homepage copy (the same hero description shown
 * on the page itself) instead of the placeholder page's internal note.
 */
function dam_front_page_seo_description( $description ) {
	if ( ! is_front_page() ) {
		return $description;
	}
	$copy = dam_site_copy( dam_current_locale() );
	return isset( $copy['heroDescription'] ) ? $copy['heroDescription'] : $description;
}
add_filter( 'rank_math/frontend/description', 'dam_front_page_seo_description' );

/**
 * Canonical and Open Graph URLs must point at the clean language root.
 */
function dam_front_page_seo_canonical( $url ) {
	if ( is_front_page() ) {
		return dam_front_page_clean_url();
	}
	return $url;
}
add_filter( 'rank_math/frontend/canonical', 'dam_front_page_seo_canonical' );
add_filter( 'rank_math/opengraph/url', 'dam_front_page_seo_canonical' );

/**
 * Polylang's hreflang alternates must point at each language's clean
 * root too, not its placeholder permalink.
 */
function dam_front_page_hreflang( $hreflangs ) {
	if ( ! is_front_page() || ! is_array( $hreflangs ) ) {
		return $hreflangs;
	}
	foreach ( $hreflangs as $lang => $url ) {
		$hreflangs[ $lang ] = dam_front_page_clean_url( $lang );
	}
	return $hreflangs;
}
add_filter( 'pll_hreflang_array', 'dam_front_page_hreflang' );
