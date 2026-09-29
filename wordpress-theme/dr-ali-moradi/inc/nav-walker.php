<?php
/**
 * Two flat (no <ul>/<li>) nav walkers matching the reference design's
 * markup, which renders each top-level menu item as a bare <a> (desktop)
 * or a full-width row with a trailing chevron (mobile) -- not a <ul> list.
 * The menus themselves are still managed on the classic Appearance ->
 * Menus screen (see blocks/site-navigation), these walkers only change
 * how that same menu is printed to HTML.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Dam_Desktop_Nav_Walker extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_lvl( &$output, $depth = 0, $args = null ) {}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		if ( $depth > 0 ) {
			return;
		}
		$output .= sprintf(
			'<a class="nav-link" href="%s">%s</a>',
			esc_url( $item->url ),
			esc_html( $item->title )
		);
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {}
}

class Dam_Mobile_Nav_Walker extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_lvl( &$output, $depth = 0, $args = null ) {}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		if ( $depth > 0 ) {
			return;
		}
		$output .= sprintf(
			'<a class="mobile-nav-link" href="%s">%s %s</a>',
			esc_url( $item->url ),
			esc_html( $item->title ),
			dam_icon( 'chevron-right', 17 )
		);
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {}
}

/**
 * Footer link columns: each top-level menu item is printed as a bare <a>,
 * exactly like the hard-coded links the footer used before it was moved onto
 * WordPress menus, so the existing footer CSS keeps applying.
 */
class Dam_Footer_Nav_Walker extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_lvl( &$output, $depth = 0, $args = null ) {}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		if ( $depth > 0 ) {
			return;
		}
		$target = '_blank' === $item->target ? ' target="_blank" rel="noopener noreferrer"' : '';
		$output .= sprintf( '<a href="%s"%s>%s</a>', esc_url( $item->url ), $target, esc_html( $item->title ) );
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {}
}

/**
 * Links a footer column shows when no WordPress menu is assigned to its
 * location for the current language yet (a fresh install, or a language whose
 * menu has not been created). Once a menu is assigned it always wins.
 */
function dam_footer_default_links( $group, $locale ) {
	$t     = dam_site_copy( $locale );
	$links = array();
	if ( 'footer_resources' === $group ) {
		$sources = array( 'before-surgery' => $t['footer']['before'], 'after-surgery' => $t['footer']['after'], 'faq' => $t['footer']['faq'], 'rehabilitation' => $t['footer']['rehab'] );
	} else {
		$sources = array();
		foreach ( array( 'clinical-care', 'innovations', 'research', 'about', 'blog' ) as $slug ) {
			$sources[ $slug ] = $t['footerExplore'][ $slug ] ?? $slug;
		}
	}
	foreach ( $sources as $slug => $label ) {
		$page  = dam_localized_page( $slug, $locale );
		$links[] = array(
			'label'   => $label,
			'url'     => dam_localized_page_url( $slug, $locale ),
			// Only a page that really exists in this language can be a page menu item.
			'page_id' => ( $page && function_exists( 'dam_page_language' ) && dam_page_language( $page->ID ) === $locale ) ? $page->ID : 0,
		);
	}
	return $links;
}

/**
 * Prints one footer link column's links: the WordPress menu assigned to
 * `$location` (Appearance > Menus, per language through Polylang), or the
 * built-in defaults above when none is assigned.
 */
function dam_render_footer_links( $location, $locale ) {
	if ( has_nav_menu( $location ) ) {
		wp_nav_menu(
			array(
				'theme_location' => $location,
				'container'      => false,
				'items_wrap'     => '%3$s',
				'fallback_cb'    => false,
				'depth'          => 1,
				'walker'         => new Dam_Footer_Nav_Walker(),
			)
		);
		return;
	}
	foreach ( dam_footer_default_links( $location, $locale ) as $link ) {
		printf( '<a href="%s">%s</a>', esc_url( $link['url'] ), esc_html( $link['label'] ) );
	}
}
