<?php
/**
 * Taxonomies for the theme's custom post types.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dam_register_taxonomies() {

	register_taxonomy(
		'condition_category',
		array( 'condition' ),
		array(
			'label'        => __( 'Condition Categories', 'dr-ali-moradi' ),
			'labels'       => array(
				'name'          => __( 'Condition Categories', 'dr-ali-moradi' ),
				'singular_name' => __( 'Condition Category', 'dr-ali-moradi' ),
			),
			'public'       => true,
			'show_in_rest' => true,
			'hierarchical' => true,
			'rewrite'      => array( 'slug' => 'condition-category' ),
		)
	);

	register_taxonomy(
		'publication_type',
		array( 'publication' ),
		array(
			'label'        => __( 'Publication Types', 'dr-ali-moradi' ),
			'labels'       => array(
				'name'          => __( 'Publication Types', 'dr-ali-moradi' ),
				'singular_name' => __( 'Publication Type', 'dr-ali-moradi' ),
			),
			'public'       => true,
			'show_in_rest' => true,
			'hierarchical' => true,
			'rewrite'      => array( 'slug' => 'publication-type' ),
		)
	);

	register_taxonomy(
		'team_area',
		array( 'team_member' ),
		array(
			'label'        => __( 'Team Areas', 'dr-ali-moradi' ),
			'labels'       => array(
				'name'          => __( 'Team Areas', 'dr-ali-moradi' ),
				'singular_name' => __( 'Team Area', 'dr-ali-moradi' ),
			),
			'public'       => true,
			'show_in_rest' => true,
			'hierarchical' => true,
			'rewrite'      => array( 'slug' => 'team-area' ),
		)
	);

	// Reused by Posts to keep News/Blog/Innovation updates as one content
	// model with a type label, matching app/blog-content.ts today.
	register_taxonomy_for_object_type( 'category', 'post' );
}
add_action( 'init', 'dam_register_taxonomies' );

/**
 * Seed the standard "category" taxonomy with the labels the current site
 * uses for its unified blog/news feed, so the terms exist before content
 * import runs. Safe to run repeatedly (wp_insert_term no-ops if the term
 * already exists).
 */
function dam_seed_post_categories() {
	$categories = array(
		'clinical-education' => __( 'Clinical Education', 'dr-ali-moradi' ),
		'recovery'            => __( 'Recovery', 'dr-ali-moradi' ),
		'innovation-news'     => __( 'Innovation', 'dr-ali-moradi' ),
		'research-literacy'   => __( 'Research Literacy', 'dr-ali-moradi' ),
		'awards-certificates' => __( 'Awards & Certificates', 'dr-ali-moradi' ),
	);

	foreach ( $categories as $slug => $name ) {
		if ( ! term_exists( $slug, 'category' ) ) {
			wp_insert_term( $name, 'category', array( 'slug' => $slug ) );
		}
	}
}
add_action( 'after_switch_theme', 'dam_seed_post_categories' );
