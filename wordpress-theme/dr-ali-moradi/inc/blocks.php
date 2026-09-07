<?php
/**
 * Registers every dynamic block that ships in blocks/<name>/block.json.
 * Each block's render.php pulls from Theme Options and/or the classic
 * nav-menu system, so header/footer/homepage placements stay in sync with
 * a single edit.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dam_register_blocks() {
	$blocks_dir = DAM_THEME_DIR . '/blocks';
	if ( ! is_dir( $blocks_dir ) ) {
		return;
	}

	foreach ( glob( $blocks_dir . '/*', GLOB_ONLYDIR ) as $block_dir ) {
		if ( file_exists( $block_dir . '/block.json' ) ) {
			register_block_type( $block_dir );
		}
	}
}
add_action( 'init', 'dam_register_blocks' );

/**
 * Groups the theme's own dynamic blocks under one category in the block
 * inserter, instead of scattering them across "Widgets"/"Theme".
 */
function dam_block_categories( $categories ) {
	array_unshift(
		$categories,
		array(
			'slug'  => 'dr-ali-moradi',
			'title' => __( 'Dr. Ali Moradi', 'dr-ali-moradi' ),
		)
	);
	return $categories;
}
add_filter( 'block_categories_all', 'dam_block_categories' );
