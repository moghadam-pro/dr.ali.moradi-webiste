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
