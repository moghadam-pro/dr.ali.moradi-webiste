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
 *   2. Rank Math then emits a wrong URL as the canonical link and og:url
 *      -- confirmed to be a *different* wrong value per install (the
 *      fa/ar placeholder's own broken permalink on one site, the
 *      English placeholder's home_url() on another), so the fix
 *      rewrites the tags themselves rather than one specific string.
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
 *
 * Deliberately does not call get_permalink()/get_page_link() or
 * Polylang's own pll_home_url() for this: confirmed live (by making
 * this function return an unmistakable marker string and checking the
 * response) that every one of those still resolves to the placeholder
 * page's own permalink for this front-page placeholder post, not a
 * clean URL -- so building the URL by hand from the known, fixed
 * per-language prefix is the only reliable option here. English is the
 * default language and stays unprefixed (see reference_urls memory);
 * Persian and Arabic use their locale code as the prefix, matching
 * Polylang's actual URLs everywhere else on this site.
 *
 * The English case can't use home_url('/') either: Polylang filters
 * home_url() itself to resolve a bare root path through the *current
 * request's* language front page -- so calling it while viewing /fa/
 * or /ar/ routes back through the same broken permalink this function
 * exists to avoid (confirmed live: hreflang="en" came out pointing at
 * the fa placeholder while viewing the Persian homepage). get_option()
 * on the raw 'home' value sidesteps that filter entirely.
 */
function dam_front_page_clean_url( $locale = null ) {
	$locale = $locale ? $locale : dam_current_locale();
	if ( 'en' === $locale ) {
		return trailingslashit( get_option( 'home' ) );
	}
	return home_url( '/' . $locale . '/' );
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
 * Rewrite the placeholder URL directly in the rendered front-page HTML.
 *
 * Filtering this at the source (page_link/_get_page_link, which
 * get_permalink() is documented to call for a page; also Rank Math's
 * own rank_math/frontend/canonical, rank_math/opengraph/url, and
 * Polylang's pll_home_url()/pll_hreflang_array) never changed the
 * output -- confirmed live with an unconditional test filter returning
 * a fixed marker string, which still never appeared anywhere in the
 * response. On this install, get_permalink() for this placeholder page
 * genuinely resolves to its own hierarchical permalink rather than the
 * front page's home_url() shortcut, and every plugin above (correctly)
 * reads that same value. Rewriting the finished HTML is what actually
 * fixes the output regardless of which internal function produced it.
 */
function dam_front_page_start_buffer() {
	if ( is_front_page() ) {
		ob_start( 'dam_front_page_rewrite_placeholder_urls' );
	}
}
add_action( 'template_redirect', 'dam_front_page_start_buffer' );

function dam_front_page_rewrite_placeholder_urls( $html ) {
	$clean = dam_front_page_clean_url();

	// Canonical link tag -- force its href to the current locale's clean URL
	// regardless of what value Rank Math computed (confirmed the wrong
	// value differs by site: the fa/ar placeholder's own broken permalink
	// on one install, the *English* placeholder's home_url() on another --
	// this targets the tag itself instead of one specific bad string).
	$html = preg_replace(
		'#(<link[^>]*\srel="canonical"[^>]*\shref=")[^"]*(")#',
		'${1}' . $clean . '${2}',
		$html
	);

	// Open Graph URL.
	$html = preg_replace(
		'#(<meta[^>]*\sproperty="og:url"[^>]*\scontent=")[^"]*(")#',
		'${1}' . $clean . '${2}',
		$html
	);

	// hreflang alternates -- each rewritten to its own language's clean URL.
	foreach ( array( 'en', 'fa', 'ar' ) as $lang ) {
		$html = preg_replace(
			'#(<link rel="alternate" href=")[^"]*(" hreflang="' . preg_quote( $lang, '#' ) . '")#',
			'${1}' . dam_front_page_clean_url( $lang ) . '${2}',
			$html
		);
	}

	return $html;
}
