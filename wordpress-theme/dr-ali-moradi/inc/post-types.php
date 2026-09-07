<?php
/**
 * Custom post types bundled with the theme.
 *
 * Rule of thumb (see wordpress-theme/architecture.md on the docs branch):
 * anything chronological/blog-like is a standard Post; anything that is a
 * structured catalogue entry with fixed fields is a CPT registered here.
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
			'supports'      => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'template'      => array(
				array( 'core/paragraph', array( 'placeholder' => __( 'Biography...', 'dr-ali-moradi' ) ) ),
			),
		)
	);

	register_post_type(
		'condition',
		array(
			'label'         => __( 'Conditions', 'dr-ali-moradi' ),
			'labels'        => array(
				'name'          => __( 'Conditions', 'dr-ali-moradi' ),
				'singular_name' => __( 'Condition', 'dr-ali-moradi' ),
				'add_new_item'  => __( 'Add New Condition', 'dr-ali-moradi' ),
				'edit_item'     => __( 'Edit Condition', 'dr-ali-moradi' ),
			),
			'public'        => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-heart',
			'has_archive'   => true,
			'rewrite'       => array( 'slug' => 'conditions' ),
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		)
	);

	register_post_type(
		'innovation',
		array(
			'label'         => __( 'Innovations', 'dr-ali-moradi' ),
			'labels'        => array(
				'name'          => __( 'Innovations', 'dr-ali-moradi' ),
				'singular_name' => __( 'Innovation', 'dr-ali-moradi' ),
				'add_new_item'  => __( 'Add New Innovation', 'dr-ali-moradi' ),
				'edit_item'     => __( 'Edit Innovation', 'dr-ali-moradi' ),
			),
			'public'        => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-lightbulb',
			'has_archive'   => true,
			'rewrite'       => array( 'slug' => 'innovation' ),
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		)
	);

	register_post_type(
		'publication',
		array(
			'label'         => __( 'Publications', 'dr-ali-moradi' ),
			'labels'        => array(
				'name'          => __( 'Publications', 'dr-ali-moradi' ),
				'singular_name' => __( 'Publication', 'dr-ali-moradi' ),
				'add_new_item'  => __( 'Add New Publication', 'dr-ali-moradi' ),
				'edit_item'     => __( 'Edit Publication', 'dr-ali-moradi' ),
			),
			'public'        => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-media-document',
			'has_archive'   => true,
			'rewrite'       => array( 'slug' => 'publications' ),
			'supports'      => array( 'title', 'editor', 'custom-fields' ),
		)
	);

	register_post_type(
		'patient_resource',
		array(
			'label'         => __( 'Patient Resources', 'dr-ali-moradi' ),
			'labels'        => array(
				'name'          => __( 'Patient Resources', 'dr-ali-moradi' ),
				'singular_name' => __( 'Patient Resource', 'dr-ali-moradi' ),
				'add_new_item'  => __( 'Add New Patient Resource', 'dr-ali-moradi' ),
				'edit_item'     => __( 'Edit Patient Resource', 'dr-ali-moradi' ),
			),
			'public'        => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-media-text',
			'has_archive'   => true,
			'rewrite'       => array( 'slug' => 'patient-resources' ),
			'supports'      => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		)
	);
}
add_action( 'init', 'dam_register_post_types' );
