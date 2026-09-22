<?php
/**
 * Lightweight release-contract checks that do not require a WordPress install.
 */

define( 'ABSPATH', __DIR__ );

function add_action() {}

function trailingslashit( $value ) {
	return rtrim( $value, "/\\" ) . '/';
}

require dirname( __DIR__ ) . '/wordpress-theme/dr-ali-moradi/inc/content-migrations.php';

function dam_test_assert( $condition, $message ) {
	if ( ! $condition ) {
		fwrite( STDERR, $message . PHP_EOL );
		exit( 1 );
	}
}

$map = dam_legacy_content_map();
dam_test_assert(
	array_keys( $map ) === array( 'condition', 'innovation', 'publication', 'patient_resource' ),
	'Legacy migration map does not contain the expected four content types.'
);

foreach ( $map as $mapping ) {
	dam_test_assert(
		array_keys( $mapping['categories'] ) === array( 'en', 'fa', 'ar' ),
		'Every replacement category must define en/fa/ar translations.'
	);
}

$post = (object) array( 'post_name' => 'sample-entry' );
dam_test_assert(
	'/conditions/sample-entry/' === dam_legacy_permalink_path( $post, 'en', 'conditions' ),
	'English legacy path is incorrect.'
);
dam_test_assert(
	'/fa/conditions/sample-entry/' === dam_legacy_permalink_path( $post, 'fa', 'conditions' ),
	'Localized legacy path is incorrect.'
);

$style = file_get_contents( dirname( __DIR__ ) . '/wordpress-theme/dr-ali-moradi/style.css' );
dam_test_assert( 1 === preg_match( '/^Version:\s*1\.1\.0\r?$/m', $style ), 'Theme header is not version 1.1.0.' );

$customizer = file_get_contents( dirname( __DIR__ ) . '/wordpress-theme/dr-ali-moradi/inc/customizer.php' );
foreach ( array(
	'dam_homepage_content', 'hero_background_image', 'hero_orbits_enabled',
	'journey_step_', 'journey_link_url', 'pathway_', 'innovation_source',
	'innovation_category', '{$prefix}_post_', 'impact_',
	'appointment_', 'appointment_image', 'recognition_source',
	'recognition_category', 'about_cta_url',
	'about_image', 'footer_credit_url', 'footer_social_',
) as $required_setting ) {
	dam_test_assert(
		false !== strpos( $customizer, $required_setting ),
		'Required Customizer contract is missing: ' . $required_setting
	);
}

dam_test_assert(
	false !== strpos( $customizer, 'api.previewer.previewUrl.set' ),
	'Language sections do not switch the live preview URL.'
);

$functions = file_get_contents( dirname( __DIR__ ) . '/wordpress-theme/dr-ali-moradi/functions.php' );
dam_test_assert(
	false !== strpos( $functions, "wp_get_theme( get_template() )" ),
	'Theme assets are not reading the canonical style.css version.'
);
dam_test_assert(
	false === strpos( $functions, "define( 'DAM_THEME_VERSION', '" ),
	'A second hard-coded theme version was reintroduced.'
);

fwrite( STDOUT, "WordPress theme release checks passed.\n" );
