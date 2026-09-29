<?php
/**
 * Live (query-driven) sections that sit inside a page's own content: the
 * team grids, the clinic/hospital gallery strips, and the latest innovation
 * posts. The designed text sections around them are ordinary page content.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

dam_render_page_section_block( isset( $attributes['section'] ) ? $attributes['section'] : '', dam_current_locale() );
