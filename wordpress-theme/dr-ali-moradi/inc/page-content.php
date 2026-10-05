<?php
/**
 * WordPress Pages as the source of truth for the designed interior pages.
 *
 * About, Clinical Care, Innovation, Research, the clinic/hospital services,
 * the patient-resource pages, Contact, and the two galleries used to be
 * printed from static copy inside the theme, so the operator could not edit
 * them from Pages. This file turns each of them into HTML stored in the
 * page's own content, once per language:
 *
 * - the designed sections (cover, numbered sections, cards, timeline, ...)
 *   are written as Custom HTML blocks, one per section, using the same markup
 *   and classes the theme's renderers produce, so the design is unchanged;
 * - query-driven parts (breadcrumbs, team grids, gallery strips, latest
 *   innovation posts) stay dynamic blocks so they keep updating themselves.
 *
 * The seed runs once per content version, never touches a page the operator
 * has already had seeded, and keeps the previous content in post meta.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DAM_PAGE_CONTENT_VERSION', '2.0.0' );
define( 'DAM_PAGE_CONTENT_OPTION', 'dam_page_content_version' );
define( 'DAM_PAGE_CONTENT_REPORT_OPTION', 'dam_page_content_report' );
define( 'DAM_PAGE_CONTENT_META', '_dam_designed_page' );
define( 'DAM_PAGE_CONTENT_BACKUP_META', '_dam_pre_designed_content' );
define( 'DAM_PAGE_TEMPLATE', 'page-designed' );

/** Page key (English slug) => page builder. */
function dam_designed_pages() {
	return array(
		'about'             => 'about',
		'clinical-care'     => 'clinic',
		'research'          => 'interior',
		'innovations'       => 'interior',
		'clinic-services'   => 'interior',
		'hospital-services' => 'interior',
		'before-surgery'    => 'interior',
		'after-surgery'     => 'interior',
		'faq'               => 'interior',
		'rehabilitation'    => 'interior',
		'contact'           => 'contact',
		'clinic-gallery'    => 'gallery',
		'hospital-gallery'  => 'gallery',
	);
}

/**
 * Dynamic pieces that live inside page content (team grids, gallery strips,
 * latest posts). One block, one `section` attribute.
 */
function dam_render_page_section_block( $section, $locale ) {
	$hub = dam_clinic_hub_copy( $locale );
	switch ( $section ) {
		case 'team-clinic':
			dam_render_team_section( 'clinic' );
			break;
		case 'team-research':
			dam_render_team_section( 'research' );
			break;
		case 'team-innovation':
			dam_render_team_section( 'innovation' );
			break;
		case 'innovation-posts':
			dam_render_innovation_posts_section( $locale );
			break;
		case 'gallery-clinic':
			dam_render_gallery_row( 'clinic', $hub['clinicGalleryTitle'], $hub['clinicGalleryIntro'], dam_localized_page_url( 'clinic-gallery', $locale ) );
			break;
		case 'gallery-hospital':
			dam_render_gallery_row( 'hospital', $hub['hospitalGalleryTitle'], $hub['hospitalGalleryIntro'], dam_localized_page_url( 'hospital-gallery', $locale ), true );
			break;
		case 'gallery-full-clinic':
			dam_render_gallery_full( 'clinic', $hub['clinicGalleryTitle'] );
			break;
		case 'gallery-full-hospital':
			dam_render_gallery_full( 'hospital', $hub['hospitalGalleryTitle'] );
			break;
	}
}

/** Render one of the theme's own dynamic blocks to a string. */
function dam_capture_block( $name, $attrs = array() ) {
	$json = $attrs ? ' ' . wp_json_encode( $attrs ) : '';
	return trim( do_blocks( '<!-- wp:dr-ali-moradi/' . $name . $json . ' /-->' ) );
}

/** A Custom HTML block. */
function dam_html_block( $html ) {
	return "<!-- wp:html -->\n" . trim( $html ) . "\n<!-- /wp:html -->";
}

/** A dynamic block of the theme. */
function dam_dynamic_block( $name, $attrs = array() ) {
	$json = $attrs ? ' ' . wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES ) : '';
	return '<!-- wp:dr-ali-moradi/' . $name . $json . ' /-->';
}

/** Cover of a gallery page (mirrors blocks/gallery-full). */
function dam_gallery_cover_html( $area, $locale ) {
	$hub    = dam_clinic_hub_copy( $locale );
	$clinic = 'clinic' === $area;
	$title  = $clinic ? $hub['clinicGalleryTitle'] : $hub['hospitalGalleryTitle'];
	$intro  = $clinic ? $hub['clinicGalleryIntro'] : $hub['hospitalGalleryIntro'];
	$cover  = dam_media_url( $clinic ? 'clinic-08' : 'hospital-14' );
	ob_start();
	?>
<section class="interior-cover">
	<img class="fill-img" src="<?php echo esc_url( $cover ); ?>" alt="">
	<div class="interior-cover-gradient" aria-hidden="true"></div>
	<div class="interior-cover-content section-shell">
		<p class="section-index light"><?php echo esc_html( $hub['pathwaysKicker'] ); ?></p>
		<h1><?php echo esc_html( $title ); ?></h1>
		<p><?php echo esc_html( $intro ); ?></p>
	</div>
</section>
	<?php
	return ob_get_clean();
}

/**
 * The block-markup content for one designed page in one language.
 * Runs with the locale forced, because every renderer reads the current one.
 */
function dam_build_page_content( $key, $type, $locale, $existing_content = '' ) {
	$GLOBALS['dam_locale_override'] = $locale;
	$GLOBALS['dam_seed_mode']       = true;

	$parts = array();
	try {
		if ( 'about' === $type ) {
			$parts[] = dam_html_block( dam_capture_block( 'interior-cover', array( 'pageKey' => 'about' ) ) );
			$parts[] = dam_dynamic_block( 'breadcrumbs' );
			foreach ( array( 'about-story', 'about-practice', 'about-journey', 'about-ecosystem', 'about-recognition' ) as $block ) {
				$html = dam_capture_block( $block );
				if ( '' !== $html ) {
					$parts[] = dam_html_block( $html );
				}
			}
		} elseif ( 'clinic' === $type ) {
			$parts[] = dam_html_block( dam_capture_block( 'interior-cover', array( 'pageKey' => $key ) ) );
			$parts[] = dam_dynamic_block( 'breadcrumbs' );
			$parts[] = dam_html_block( dam_capture_block( 'clinic-pathways' ) );
			$parts[] = dam_dynamic_block( 'page-section', array( 'section' => 'team-clinic' ) );
			$parts[] = dam_dynamic_block( 'page-section', array( 'section' => 'gallery-clinic' ) );
			$parts[] = dam_dynamic_block( 'page-section', array( 'section' => 'gallery-hospital' ) );
		} elseif ( 'interior' === $type ) {
			$parts[] = dam_html_block( dam_capture_block( 'interior-cover', array( 'pageKey' => $key ) ) );
			$parts[] = dam_dynamic_block( 'breadcrumbs' );
			$parts[] = dam_html_block( dam_capture_block( 'interior-body', array( 'pageKey' => $key ) ) );
			if ( 'innovations' === $key ) {
				$parts[] = dam_dynamic_block( 'page-section', array( 'section' => 'team-innovation' ) );
				$parts[] = dam_dynamic_block( 'page-section', array( 'section' => 'innovation-posts' ) );
			} elseif ( 'research' === $key ) {
				$parts[] = dam_dynamic_block( 'page-section', array( 'section' => 'team-research' ) );
			}
		} elseif ( 'contact' === $type ) {
			// Keep whatever form shortcode the operator already placed on the page.
			$shortcode = preg_match( '/\[mpro_form[^\]]*\]/', $existing_content, $match ) ? $match[0] : '[mpro_form id="1"]';
			$parts[]   = dam_html_block( dam_capture_block( 'interior-cover', array( 'pageKey' => 'contact' ) ) );
			$parts[]   = dam_dynamic_block( 'breadcrumbs' );
			$parts[]   = "<!-- wp:group {\"tagName\":\"section\",\"className\":\"contact-layout section-space section-shell\",\"layout\":{\"type\":\"default\"}} -->\n"
				. "<section class=\"wp-block-group contact-layout section-space section-shell\">\n"
				. dam_html_block( dam_capture_block( 'contact-details' ) ) . "\n\n"
				. "<!-- wp:group {\"className\":\"contact-form-shell reveal\",\"layout\":{\"type\":\"constrained\"}} -->\n"
				. "<div class=\"wp-block-group contact-form-shell reveal\">\n<!-- wp:shortcode -->\n" . $shortcode . "\n<!-- /wp:shortcode -->\n</div>\n<!-- /wp:group -->\n"
				. "</section>\n<!-- /wp:group -->";
		} elseif ( 'gallery' === $type ) {
			$area    = 'clinic-gallery' === $key ? 'clinic' : 'hospital';
			$parts[] = dam_html_block( dam_gallery_cover_html( $area, $locale ) );
			$parts[] = dam_dynamic_block( 'breadcrumbs' );
			$parts[] = dam_dynamic_block( 'page-section', array( 'section' => 'gallery-full-' . $area ) );
		}
	} finally {
		unset( $GLOBALS['dam_locale_override'], $GLOBALS['dam_seed_mode'] );
	}

	return implode( "\n\n", $parts );
}

/** Which language a page is in ('en' when Polylang is not active). */
function dam_page_language( $page_id ) {
	return function_exists( 'pll_get_post_language' ) ? ( pll_get_post_language( $page_id ) ?: 'en' ) : 'en';
}

function dam_site_locales() {
	if ( function_exists( 'pll_languages_list' ) ) {
		$list = pll_languages_list();
		if ( $list ) {
			return $list;
		}
	}
	return array( 'en' );
}

/**
 * Write default content into one language's copy of one page, unless that
 * page was already seeded (never overwrite what the operator has edited).
 *
 * @return string 'seeded' | 'kept' | 'missing'
 */
function dam_seed_designed_page( $key, $type, $locale ) {
	$page = dam_localized_page( $key, $locale );
	if ( ! $page || 'page' !== $page->post_type || dam_page_language( $page->ID ) !== $locale ) {
		return 'missing';
	}
	if ( get_post_meta( $page->ID, DAM_PAGE_CONTENT_META, true ) ) {
		return 'kept';
	}

	$content = dam_build_page_content( $key, $type, $locale, $page->post_content );
	if ( '' === trim( $content ) ) {
		return 'missing';
	}

	// Custom HTML must be saved exactly as generated, whichever role runs this.
	kses_remove_filters();
	update_post_meta( $page->ID, DAM_PAGE_CONTENT_BACKUP_META, wp_slash( $page->post_content ) );
	$result = wp_update_post( wp_slash( array( 'ID' => $page->ID, 'post_content' => $content ) ), true );
	kses_init();
	if ( is_wp_error( $result ) ) {
		return 'missing';
	}

	update_post_meta( $page->ID, '_wp_page_template', DAM_PAGE_TEMPLATE );
	update_post_meta( $page->ID, DAM_PAGE_CONTENT_META, DAM_PAGE_CONTENT_VERSION );
	return 'seeded';
}

/** Menu id assigned to a footer location in one language, or 0. */
function dam_footer_menu_assigned( $location, $locale ) {
	if ( function_exists( 'pll_languages_list' ) ) {
		$options = get_option( 'polylang', array() );
		return (int) ( $options['nav_menus'][ get_stylesheet() ][ $location ][ $locale ] ?? 0 );
	}
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	return (int) ( $locations[ $location ] ?? 0 );
}

function dam_footer_menu_assign( $location, $locale, $menu_id ) {
	if ( function_exists( 'pll_languages_list' ) ) {
		$options = get_option( 'polylang', array() );
		$options['nav_menus'][ get_stylesheet() ][ $location ][ $locale ] = $menu_id;
		update_option( 'polylang', $options );
		return;
	}
	$locations              = get_theme_mod( 'nav_menu_locations', array() );
	$locations[ $location ] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Create the two footer menus for every language that has none, from the
 * links the footer used to hard-code, and assign them to their locations so
 * they can be edited on Appearance > Menus like any other menu.
 */
function dam_seed_footer_menus() {
	$created = 0;
	$names   = array( 'footer' => 'Footer — Quick access', 'footer_resources' => 'Footer — Patient resources' );
	foreach ( dam_site_locales() as $locale ) {
		foreach ( $names as $location => $name ) {
			if ( dam_footer_menu_assigned( $location, $locale ) ) {
				continue;
			}
			$menu_id = wp_create_nav_menu( $name . ' (' . strtoupper( $locale ) . ')' );
			if ( is_wp_error( $menu_id ) ) {
				continue;
			}
			foreach ( dam_footer_default_links( $location, $locale ) as $position => $link ) {
				$args = array(
					'menu-item-title'    => $link['label'],
					'menu-item-url'      => $link['url'],
					'menu-item-type'     => 'custom',
					'menu-item-status'   => 'publish',
					'menu-item-position' => $position + 1,
				);
				if ( ! empty( $link['page_id'] ) ) {
					$args['menu-item-type']      = 'post_type';
					$args['menu-item-object']    = 'page';
					$args['menu-item-object-id'] = $link['page_id'];
					unset( $args['menu-item-url'] );
				}
				wp_update_nav_menu_item( $menu_id, 0, $args );
			}
			dam_footer_menu_assign( $location, $locale, $menu_id );
			$created++;
		}
	}
	return $created;
}

/** Versioned, repeat-safe runner (first administrator request after upgrade). */
function dam_maybe_seed_page_content() {
	if ( wp_doing_ajax() || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( version_compare( (string) get_option( DAM_PAGE_CONTENT_OPTION, '0.0.0' ), DAM_PAGE_CONTENT_VERSION, '>=' ) ) {
		return;
	}
	// Wait until the post-type consolidation has finished so pages are stable.
	if ( function_exists( 'dam_has_pending_legacy_content' ) && dam_has_pending_legacy_content() ) {
		return;
	}
	if ( get_transient( 'dam_page_content_running' ) ) {
		return;
	}
	set_transient( 'dam_page_content_running', 1, 120 );

	$report = array( 'version' => DAM_PAGE_CONTENT_VERSION, 'time' => gmdate( 'c' ), 'pages' => array(), 'footer_menus_created' => 0 );
	$errors = 0;
	foreach ( dam_designed_pages() as $key => $type ) {
		foreach ( dam_site_locales() as $locale ) {
			try {
				$report['pages'][ $key . ':' . $locale ] = dam_seed_designed_page( $key, $type, $locale );
			} catch ( Throwable $e ) {
				$report['pages'][ $key . ':' . $locale ] = 'error: ' . $e->getMessage();
				$errors++;
			}
		}
	}
	try {
		$report['footer_menus_created'] = dam_seed_footer_menus();
	} catch ( Throwable $e ) {
		$report['footer_menus_created'] = 'error: ' . $e->getMessage();
		$errors++;
	}

	update_option( DAM_PAGE_CONTENT_REPORT_OPTION, $report, false );
	if ( 0 === $errors ) {
		update_option( DAM_PAGE_CONTENT_OPTION, DAM_PAGE_CONTENT_VERSION, false );
	}
	delete_transient( 'dam_page_content_running' );
}
add_action( 'admin_init', 'dam_maybe_seed_page_content', 30 );
