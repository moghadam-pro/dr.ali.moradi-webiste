<?php
/**
 * Native (ACF-free) custom fields for the theme's post types.
 *
 * Scalar fields are registered with register_post_meta() and edited
 * through a small block-editor sidebar panel (assets/js/theme-fields.js),
 * giving the operator an ACF-like editing experience with no plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Field definitions, keyed by post type. Shared between PHP (meta
 * registration) and JS (the sidebar panel), so there is exactly one place
 * to add or change a field.
 */
function dam_meta_field_definitions() {
	return array(
		'team_member' => array(
			array( 'key' => 'dam_role', 'label' => __( 'Role', 'dr-ali-moradi' ), 'type' => 'text' ),
			array( 'key' => 'dam_summary', 'label' => __( 'Short summary', 'dr-ali-moradi' ), 'type' => 'text' ),
		),
		'post'        => array(
			array( 'key' => 'dam_read_minutes', 'label' => __( 'Read time (minutes)', 'dr-ali-moradi' ), 'type' => 'number' ),
		),
		'innovation'  => array(
			array(
				'key'     => 'dam_status',
				'label'   => __( 'Development Status', 'dr-ali-moradi' ),
				'type'    => 'select',
				'options' => array(
					array( 'label' => __( 'Select a status', 'dr-ali-moradi' ), 'value' => '' ),
					array( 'label' => __( 'Concept', 'dr-ali-moradi' ), 'value' => 'concept' ),
					array( 'label' => __( 'Prototype', 'dr-ali-moradi' ), 'value' => 'prototype' ),
					array( 'label' => __( 'Clinical validation', 'dr-ali-moradi' ), 'value' => 'clinical-validation' ),
					array( 'label' => __( 'Deployed', 'dr-ali-moradi' ), 'value' => 'deployed' ),
				),
			),
			array( 'key' => 'dam_evidence_url', 'label' => __( 'Evidence link (patent / publication)', 'dr-ali-moradi' ), 'type' => 'url' ),
		),
		'publication' => array(
			array( 'key' => 'dam_year', 'label' => __( 'Year', 'dr-ali-moradi' ), 'type' => 'number' ),
			array( 'key' => 'dam_external_url', 'label' => __( 'External link', 'dr-ali-moradi' ), 'type' => 'url' ),
		),
		'condition'   => array(),
	);
}

function dam_register_post_meta() {
	foreach ( dam_meta_field_definitions() as $post_type => $fields ) {
		foreach ( $fields as $field ) {
			register_post_meta(
				$post_type,
				$field['key'],
				array(
					'show_in_rest' => true,
					'single'       => true,
					'type'         => 'number' === $field['type'] ? 'number' : 'string',
					'auth_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
				)
			);
		}
	}
}
add_action( 'init', 'dam_register_post_meta' );

function dam_enqueue_meta_fields_script() {
	$screen = get_current_screen();
	if ( ! $screen || 'post' !== $screen->base ) {
		return;
	}

	$definitions = dam_meta_field_definitions();
	if ( empty( $definitions[ $screen->post_type ] ) ) {
		return;
	}

	wp_enqueue_script(
		'dr-ali-moradi-theme-fields',
		DAM_THEME_URI . '/assets/js/theme-fields.js',
		array( 'wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-data', 'wp-i18n' ),
		DAM_THEME_VERSION,
		true
	);

	wp_add_inline_script(
		'dr-ali-moradi-theme-fields',
		'window.drAliMoradiThemeFields = ' . wp_json_encode( $definitions ) . ';',
		'before'
	);
}
add_action( 'enqueue_block_editor_assets', 'dam_enqueue_meta_fields_script' );
