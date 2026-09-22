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

/**
 * Custom breadcrumb trail, replacing rank_math_the_breadcrumbs().
 *
 * Rank Math's own breadcrumb only ever produced "Home / Current Item" --
 * confirmed live on both a plain page and a team_member profile -- because
 * this site's archive-less CPTs (team_member) have nothing for it to
 * insert a middle crumb from, and its "Home" text plus separator are one
 * global string never translated per Polylang language (confirmed: showed
 * English "Home" and an en-dash separator on the Persian site). Building
 * the trail from the theme's own already-localized page/CPT/taxonomy data
 * gives every page a correct, fully-translated hierarchy instead.
 */
function dam_breadcrumb_home_label( $locale ) {
	$labels = array( 'en' => 'Home', 'fa' => 'خانه', 'ar' => 'الرئيسية' );
	return $labels[ $locale ] ?? $labels['en'];
}

/** LTR (English) reads "/"; RTL (Persian/Arabic) reads "\" instead. */
function dam_breadcrumb_separator( $locale ) {
	return 'en' === $locale ? '/' : '\\';
}

function dam_breadcrumb_post_type_label( $post_type ) {
	$object = get_post_type_object( $post_type );
	return $object ? $object->labels->name : '';
}

/**
 * The generic-interior-page sub-pages (clinic/hospital pathway pages, the
 * four patient-resource pages, the two case-study galleries) don't have a
 * real WP page_parent set -- every page in the DB is a flat top-level
 * post -- so their place in the hierarchy is declared here instead,
 * mirroring the same relationship gallery-full/render.php's own back-link
 * already assumed (they all sit under Clinical Care).
 */
/**
 * About and Contact are the only interior pages whose WP page title is a
 * full marketing sentence written for the cover <h1> ("A surgeon shaped by
 * curiosity, evidence, and making.") rather than a short label -- every
 * other interior page's actual page title (Research, Clinic services,
 * Rehabilitation guidance, ...) is already breadcrumb-length. Reuses the
 * short label already shown for the same page elsewhere (footer nav /
 * the page's own kicker) instead of maintaining a separate label set.
 */
function dam_breadcrumb_page_label( $page_key, $locale, $fallback_title ) {
	if ( 'about' === $page_key ) {
		$label = dam_site_copy( $locale )['footerExplore']['about'] ?? null;
		return $label ? $label : $fallback_title;
	}
	if ( 'contact' === $page_key ) {
		$label = dam_interior_pages_copy( $locale )['contact']['kicker'] ?? null;
		return $label ? $label : $fallback_title;
	}
	return $fallback_title;
}

function dam_interior_page_parent_key( $page_key ) {
	$map = array(
		'clinic-services'   => 'clinical-care',
		'hospital-services' => 'clinical-care',
		'before-surgery'    => 'clinical-care',
		'after-surgery'     => 'clinical-care',
		'faq'               => 'clinical-care',
		'rehabilitation'    => 'clinical-care',
		'clinic-gallery'    => 'clinical-care',
		'hospital-gallery'  => 'clinical-care',
	);
	return $map[ $page_key ] ?? null;
}

/**
 * Builds the ordered crumb list ( array of ['label' => ..., 'url' => ...
 * or null for the current, non-linked page] ) for whatever WordPress is
 * currently rendering. Empty on the front page and on 404s -- neither has
 * a real trail to show.
 */
function dam_get_breadcrumb_items() {
	if ( is_front_page() || is_404() ) {
		return array();
	}

	$locale = dam_current_locale();
	$items  = array(
		array( 'label' => dam_breadcrumb_home_label( $locale ), 'url' => dam_front_page_clean_url( $locale ) ),
	);

	if ( is_singular( 'post' ) ) {
		$items[] = array( 'label' => dam_blog_labels( $locale )['kicker'], 'url' => dam_localized_page_url( 'blog', $locale ) );
		$cats    = get_the_category();
		if ( $cats ) {
			$items[] = array( 'label' => $cats[0]->name, 'url' => get_category_link( $cats[0] ) );
		}
		$items[] = array( 'label' => get_the_title(), 'url' => null );
	} elseif ( is_singular( 'team_member' ) ) {
		$hub_key = dam_team_member_back_slug( get_the_ID() );
		$hub     = dam_localized_page( $hub_key, $locale );
		$items[] = array(
			'label' => dam_breadcrumb_post_type_label( 'team_member' ),
			'url'   => $hub ? get_permalink( $hub ) : null,
		);
		$items[] = array( 'label' => get_the_title(), 'url' => null );
	} elseif ( is_singular( array( 'condition', 'innovation', 'publication', 'patient_resource' ) ) ) {
		$post_type = get_post_type();
		$items[]   = array( 'label' => dam_breadcrumb_post_type_label( $post_type ), 'url' => get_post_type_archive_link( $post_type ) );
		$items[]   = array( 'label' => get_the_title(), 'url' => null );
	} elseif ( is_post_type_archive() ) {
		$items[] = array( 'label' => post_type_archive_title( '', false ), 'url' => null );
	} elseif ( is_category() || is_tag() ) {
		$items[] = array( 'label' => dam_blog_labels( $locale )['kicker'], 'url' => dam_localized_page_url( 'blog', $locale ) );
		$items[] = array( 'label' => single_term_title( '', false ), 'url' => null );
	} elseif ( is_tax( 'condition_category' ) ) {
		$items[] = array( 'label' => dam_breadcrumb_post_type_label( 'condition' ), 'url' => get_post_type_archive_link( 'condition' ) );
		$items[] = array( 'label' => single_term_title( '', false ), 'url' => null );
	} elseif ( is_tax( 'publication_type' ) ) {
		$items[] = array( 'label' => dam_breadcrumb_post_type_label( 'publication' ), 'url' => get_post_type_archive_link( 'publication' ) );
		$items[] = array( 'label' => single_term_title( '', false ), 'url' => null );
	} elseif ( is_tax() ) {
		$items[] = array( 'label' => single_term_title( '', false ), 'url' => null );
	} elseif ( is_search() ) {
		/* translators: %s: the visitor's search query. */
		$items[] = array( 'label' => sprintf( __( 'Search results for "%s"', 'dr-ali-moradi' ), get_search_query() ), 'url' => null );
	} elseif ( is_home() ) {
		$items[] = array( 'label' => dam_blog_labels( $locale )['kicker'], 'url' => null );
	} elseif ( is_page() ) {
		$page_key   = dam_current_page_key();
		$parent_key = dam_interior_page_parent_key( $page_key );
		if ( $parent_key ) {
			$parent = dam_localized_page( $parent_key, $locale );
			if ( $parent ) {
				$items[] = array( 'label' => dam_breadcrumb_page_label( $parent_key, $locale, get_the_title( $parent ) ), 'url' => get_permalink( $parent ) );
			}
		}
		$items[] = array( 'label' => dam_breadcrumb_page_label( $page_key, $locale, get_the_title() ), 'url' => null );
	} elseif ( is_singular() ) {
		$items[] = array( 'label' => get_the_title(), 'url' => null );
	}

	return $items;
}

/**
 * Renders the crumb list built above. Placed just under each page's cover
 * image (interior-cover/team-profile/single-post-body/archive-content/
 * blog-archive/gallery-full all call this directly right after their own
 * <section class="interior-cover">; pages without a cover get it via the
 * dr-ali-moradi/breadcrumbs block instead) rather than in the shared
 * header part, so it reads as part of the page instead of a header bar.
 */
function dam_render_breadcrumbs() {
	$items = dam_get_breadcrumb_items();
	if ( count( $items ) < 2 ) {
		return;
	}
	$locale    = dam_current_locale();
	$separator = dam_breadcrumb_separator( $locale );
	$last      = count( $items ) - 1;
	?>
	<nav class="site-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'dr-ali-moradi' ); ?>">
		<div class="section-shell">
			<ol class="dam-breadcrumb-list">
				<?php foreach ( $items as $i => $item ) : ?>
					<li>
						<?php if ( $item['url'] && $i !== $last ) : ?>
							<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
						<?php else : ?>
							<span aria-current="page"><?php echo esc_html( $item['label'] ); ?></span>
						<?php endif; ?>
						<?php if ( $i !== $last ) : ?><span class="dam-breadcrumb-sep" aria-hidden="true"><?php echo esc_html( $separator ); ?></span><?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</nav>
	<?php
}
