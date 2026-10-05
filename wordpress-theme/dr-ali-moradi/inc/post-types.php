<?php
/**
 * Custom post types bundled with the theme.
 *
 * Rule of thumb (see wordpress-theme/architecture.md on the docs branch):
 * only team profiles remain structured catalogue entries. Conditions,
 * innovations, publications, and patient resources were consolidated into
 * standard Posts in theme version 1.0.0.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dam_register_post_types() {

	register_post_type(
		'team_member',
		array(
			'label'         => __( 'Team Members', 'dr-ali-moradi' ),
			'labels'        => array(
				'name'          => __( 'Team Members', 'dr-ali-moradi' ),
				'singular_name' => __( 'Team Member', 'dr-ali-moradi' ),
				'add_new_item'  => __( 'Add New Team Member', 'dr-ali-moradi' ),
				'edit_item'     => __( 'Edit Team Member', 'dr-ali-moradi' ),
			),
			'public'        => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-groups',
			'has_archive'   => false,
			'rewrite'       => array( 'slug' => 'team' ),
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
			'template'      => array(
				array( 'core/paragraph', array( 'placeholder' => __( 'Biography...', 'dr-ali-moradi' ) ) ),
			),
		)
	);

	if ( function_exists( 'dam_register_pending_legacy_post_types' ) ) {
		dam_register_pending_legacy_post_types();
	}
}
add_action( 'init', 'dam_register_post_types' );
