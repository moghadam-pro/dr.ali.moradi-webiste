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
dam_test_assert( 1 === preg_match( '/^Version:\s*2\.0\.0\r?$/m', $style ), 'Theme header is not version 2.0.0.' );

$customizer = file_get_contents( dirname( __DIR__ ) . '/wordpress-theme/dr-ali-moradi/inc/customizer.php' );
foreach ( array(
	'dam_homepage_', 'dam_customizer_section_order', 'hero_background_image', 'hero_orbits_enabled',
	'journey_step_', 'journey_link_url', 'pathway_', 'innovation_source',
	'innovation_category', '{$prefix}_post_', 'impact_',
	'appointment_', 'appointment_image', 'recognition_source',
	'recognition_category', 'about_cta_url',
	'about_image', 'footer_social_', 'dam_customizer_parsidate_font',
) as $required_setting ) {
	dam_test_assert(
		false !== strpos( $customizer, $required_setting ),
		'Required Customizer contract is missing: ' . $required_setting
	);
}

dam_test_assert(
	false !== strpos( $customizer, 'api.previewer.previewUrl.set' ),
	'Language panels do not switch the live preview URL.'
);

// Homepage sections must be listed in the order front-page.html renders them.
preg_match( "/return array\( ('hero'[^)]*) \);/", $customizer, $order_match );
$customizer_order = array_map( function ( $item ) { return trim( $item, " '" ); }, explode( ',', $order_match[1] ?? '' ) );
$front_page = file_get_contents( dirname( __DIR__ ) . '/wordpress-theme/dr-ali-moradi/templates/front-page.html' );
preg_match_all( '/wp:dr-ali-moradi\/homepage-([a-z-]+)/', $front_page, $blocks );
$block_to_section = array( 'hero' => 'hero', 'journey' => 'journey', 'pathways' => 'pathways', 'innovation' => 'innovation', 'impact' => 'impact', 'appointments' => 'appointments', 'news' => 'recognition', 'about-preview' => 'about' );
$page_order = array_map( function ( $block ) use ( $block_to_section ) { return $block_to_section[ $block ]; }, $blocks[1] );
$page_order[] = 'footer';
dam_test_assert( $page_order === $customizer_order, 'Customizer section order differs from the homepage order.' );

foreach ( array( 'footer_copyright', 'footer_disclaimer', 'footer_credit', 'footer_explore_clinical_care', 'footer_resource_' ) as $removed ) {
	dam_test_assert( false === strpos( $customizer, $removed ), 'Footer setting should no longer be editable in the Customizer: ' . $removed );
}

$footer = file_get_contents( dirname( __DIR__ ) . '/wordpress-theme/dr-ali-moradi/blocks/site-footer/render.php' );
dam_test_assert( false !== strpos( $footer, "dam_render_footer_links( 'footer_resources'" ) && false !== strpos( $footer, "dam_render_footer_links( 'footer'" ), 'Footer link columns are not driven by WordPress menus.' );

$page_content = file_get_contents( dirname( __DIR__ ) . '/wordpress-theme/dr-ali-moradi/inc/page-content.php' );
foreach ( array( 'about', 'clinical-care', 'research', 'innovations' ) as $designed_page ) {
	dam_test_assert( false !== strpos( $page_content, "'{$designed_page}'" ), 'Designed page is not seeded into WordPress content: ' . $designed_page );
}
dam_test_assert( is_file( dirname( __DIR__ ) . '/wordpress-theme/dr-ali-moradi/templates/page-designed.html' ), 'The designed-page template is missing.' );

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
