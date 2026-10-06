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

/** Use authored page introductions instead of short section kickers. */
function dam_authored_page_seo_description( $description ) {
	if ( is_front_page() || ( ! is_page() && ! is_home() ) ) { return $description; }
	$id = get_queried_object_id();
	// Explicit Rank Math copy remains the operator's override.
	if ( get_post_meta( $id, 'rank_math_description', true ) ) { return $description; }
	if ( is_home() || is_page( 'blog' ) ) { return dam_blog_labels( dam_current_locale() )['intro']; }
	$post = get_post( $id );
	if ( ! $post ) { return $description; }
	preg_match_all( '#<p\b[^>]*>(.*?)</p>#is', $post->post_content, $paragraphs );
	foreach ( $paragraphs[1] as $paragraph ) {
		$text = trim( preg_replace( '/\s+/u', ' ', html_entity_decode( wp_strip_all_tags( $paragraph ), ENT_QUOTES, 'UTF-8' ) ) );
		// Count Unicode characters so short RTL kickers are skipped too.
		if ( preg_match_all( '/./us', $text ) >= 50 ) { return wp_html_excerpt( $text, 160, '…' ); }
	}
	return $description;
}

// Migrated resource posts can share the same public URL as a maintained Page.
// Keep the Page entry and omit only exact duplicate post URLs from XML.
add_filter( 'rank_math/sitemap/entry', function( $entry, $type, $object ) {
	if ( ! $entry || 'post' !== $type || ! isset( $object->post_type ) || 'post' !== $object->post_type ) { return $entry; }
	static $page_urls = null;
	if ( null === $page_urls ) {
		$page_urls = array();
		foreach ( get_posts( array( 'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => -1, 'fields' => 'ids', 'suppress_filters' => true, 'lang' => '' ) ) as $id ) { $page_urls[ untrailingslashit( get_permalink( $id ) ) ] = true; }
	}
	return isset( $page_urls[ untrailingslashit( $entry['loc'] ?? '' ) ] ) ? false : $entry;
}, 30, 3 );

/** Case metadata follows the active gallery language, with no invented clinical text. */
add_filter( 'rank_math/frontend/title', function( $title ) {
	if ( ! function_exists( 'dam_patient_is_catalogue' ) || ! dam_patient_is_catalogue() ) { return $title; }
	if ( is_singular( 'patient' ) && get_post_meta( get_the_ID(), 'rank_math_title', true ) ) { return $title; }
	$name = is_singular( 'patient' ) ? get_the_title() : ( is_tax() ? dam_patient_term_name( get_queried_object() ) : dam_patient_label( 'Patients', 'بیماران', 'المرضى' ) );
	$page = max( 1, (int) get_query_var( 'paged' ) );
	return $name . ( $page > 1 ? ' — ' . dam_patient_label( 'Page', 'صفحه', 'صفحة' ) . ' ' . $page : '' ) . ' | Dr. Ali Moradi';
}, 30 );
function dam_patient_seo_description( $description ) {
	$description = dam_authored_page_seo_description( $description );
	if ( ! function_exists( 'dam_patient_is_catalogue' ) || ! dam_patient_is_catalogue() ) { return $description; }
	if ( is_singular( 'patient' ) ) {
		$custom = get_post_meta( get_the_ID(), 'rank_math_description', true );
		if ( $custom && empty( get_post_meta( get_the_ID(), 'dam_patient_translations', true )[ dam_current_locale() ]['excerpt'] ) ) { return $description; }
		$excerpt = wp_strip_all_tags( get_the_excerpt() );
		if ( $excerpt ) { return wp_trim_words( $excerpt, 35, '…' ); }
	}
	if ( is_tax() && term_description() ) { return wp_strip_all_tags( term_description() ); }
	return dam_patient_label( 'Clinical and hospital case galleries of Dr. Ali Moradi.', 'گالری پرونده‌های بیماران کلینیک و بیمارستان دکتر علی مرادی.', 'معارض الحالات السريرية وحالات المستشفى للدكتور علي مرادي.' );
}
add_filter( 'rank_math/frontend/description', 'dam_patient_seo_description', 30 );
add_filter( 'rank_math/opengraph/facebook/description', 'dam_patient_seo_description', 30 );
add_filter( 'rank_math/opengraph/twitter/description', 'dam_patient_seo_description', 30 );
add_filter( 'rank_math/sitemap/urlimages', function( $images, $id ) {
	if ( 'patient' !== get_post_type( $id ) ) { return $images; }
	// Rank Math may already add the featured image. Publish only photos the
	// operator has explicitly marked safe for unblurred viewing.
	$images = array();
	foreach ( dam_patient_media( $id ) as $item ) {
		if ( 'image' === $item['type'] && empty( $item['sensitive'] ) && ! in_array( $item['url'], array_column( $images, 'src' ), true ) ) {
			$images[] = array( 'src' => $item['url'], 'title' => $item['title'] );
		}
	}
	return $images;
}, 20, 2 );
// Sitemaps use one canonical case URL, independent of the language requesting XML.
add_filter( 'rank_math/sitemap/xml_post_url', function( $url, $post ) { return 'patient' === $post->post_type ? remove_query_arg( 'patient_lang', $url ) : $url; }, 20, 2 );
add_filter( 'rank_math/frontend/canonical', function( $url ) {
	if ( ! function_exists( 'dam_patient_is_catalogue' ) || ! dam_patient_is_catalogue() ) { return $url; }
	// Shared case records without translated copy canonicalize to their base URL.
	$translations = is_singular( 'patient' ) ? get_post_meta( get_the_ID(), 'dam_patient_translations', true ) : array();
	return ! empty( $translations[ dam_current_locale() ] ) ? dam_patient_language_url( $url ) : remove_query_arg( 'patient_lang', $url );
}, 30 );
add_action( 'wp_head', function() {
	if ( ! is_singular( 'patient' ) ) { return; }
	$translations = (array) get_post_meta( get_the_ID(), 'dam_patient_translations', true );
	$base = remove_query_arg( 'patient_lang', get_permalink() );
	echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $base ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="en" href="' . esc_url( $base ) . '">' . "\n";
	foreach ( array( 'fa', 'ar' ) as $locale ) {
		if ( ! empty( $translations[ $locale ] ) ) { echo '<link rel="alternate" hreflang="' . $locale . '" href="' . esc_url( dam_patient_language_url( $base, $locale ) ) . '">' . "\n"; }
	}
}, 5 );

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

	// x-default points search engines at the English (default-language) homepage.
	if ( false === strpos( $html, 'hreflang="x-default"' ) ) {
		$html = preg_replace(
			'#(<link rel="alternate" href=")[^"]*(" hreflang="en")#',
			'<link rel="alternate" href="' . dam_front_page_clean_url( 'en' ) . '" hreflang="x-default" />' . "\n" . '${0}',
			$html,
			1
		);
	}

	return $html;
}

/**
 * Physician structured data (Schema.org) for the homepage and About page.
 *
 * Only verifiable facts already published on the site are included. The
 * sameAs list holds only profiles confirmed in the theme (Google Scholar);
 * add ORCID, PubMed, university, LinkedIn or ResearchGate URLs through the
 * `dam_physician_same_as` filter once the operator confirms them.
 */
function dam_physician_schema() {
	$home = trailingslashit( get_option( 'home' ) );
	$same = apply_filters( 'dam_physician_same_as', array( 'https://scholar.google.com/citations?user=UhXLjGEAAAAJ' ) );
	return array(
		'@context'        => 'https://schema.org',
		'@type'           => array( 'Physician', 'Person' ),
		'@id'             => $home . '#physician',
		'name'            => 'Ali Moradi, MD, PhD',
		'honorificPrefix' => 'Dr.',
		'jobTitle'        => 'Hand & Upper Extremity Surgeon; Associate Professor of Orthopedic Surgery',
		'description'     => 'Hand and upper-extremity surgeon and Associate Professor of Orthopedics at Mashhad University of Medical Sciences, Mashhad, Iran, working across clinical care, research, medical innovation, and education.',
		'url'             => $home,
		'image'           => get_site_icon_url( 512 ) ? get_site_icon_url( 512 ) : null,
		'medicalSpecialty' => array( 'Orthopedic', 'Surgical' ),
		'worksFor'        => array( '@type' => 'CollegeOrUniversity', 'name' => 'Mashhad University of Medical Sciences' ),
		'workLocation'    => array( '@type' => 'City', 'name' => 'Mashhad', 'containedInPlace' => array( '@type' => 'Country', 'name' => 'Iran' ) ),
		'knowsAbout'      => array( 'Hand surgery', 'Upper extremity surgery', 'Wrist surgery', 'Peripheral nerve surgery', 'Tendon injuries', 'Hand and wrist fractures', 'Prosthetics and bionic hand research', 'Rehabilitation robotics', 'Biomechanics' ),
		'sameAs'          => array_values( array_filter( $same ) ),
	);
}

function dam_output_physician_schema() {
	if ( ! is_front_page() && 'about' !== dam_current_page_key() ) {
		return;
	}
	$schema = array_filter( dam_physician_schema() );
	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dam_output_physician_schema', 20 );

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
		'hospital-surgery-care' => 'clinical-care',
		'clinic-surgery-care' => 'clinical-care',
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
 * currently rendering. The front page shows a single current-page crumb;
 * 404 responses have no published-page trail.
 */
function dam_get_breadcrumb_items() {
	if ( is_front_page() ) {
		return array( array( 'label' => dam_breadcrumb_home_label( dam_current_locale() ), 'url' => null ) );
	}
	if ( is_404() ) {
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
		if ( ! $parent_key ) {
			$page = get_post( get_queried_object_id() );
			if ( $page && ! empty( $page->post_parent ) ) {
				$parent = get_post( $page->post_parent );
				$parent_key = $parent ? $parent->post_name : null;
			}
		}
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

/** Authored innovation project HTML has its own hero, outside the cover block. */
function dam_project_breadcrumb_content( $content ) {
	if ( ! is_page() || ! empty( $GLOBALS['dam_seed_mode'] ) || strpos( $content, 'class="dam-project"' ) === false || strpos( $content, 'site-breadcrumbs' ) !== false ) { return $content; }
	ob_start();
	dam_render_breadcrumbs();
	$trail = ob_get_clean();
	return preg_replace_callback( '#</header>#', function() use ( $trail ) { return '</header>' . $trail; }, $content, 1 );
}
add_filter( 'render_block_core/post-content', 'dam_project_breadcrumb_content', 20 );

/**
 * Renders the crumb list built above. Placed just under each page's cover
 * image (interior-cover/team-profile/single-post-body/archive-content/
 * blog-archive/gallery-full all call this directly right after their own
 * <section class="interior-cover">; pages without a cover get it via the
 * dr-ali-moradi/breadcrumbs block instead) rather than in the shared
 * header part, so it reads as part of the page instead of a header bar.
 */
function dam_render_breadcrumbs() {
	if ( ! empty( $GLOBALS['dam_seed_mode'] ) ) {
		return;
	}
	$items = dam_get_breadcrumb_items();
	if ( count( $items ) < 1 ) {
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
