<?php
/**
 * Theme bootstrap. Everything content-structural (post types, taxonomies,
 * meta fields, theme options, dynamic blocks) lives in inc/ so this file
 * stays a simple loader.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DAM_THEME_VERSION', '0.7.0' );
define( 'DAM_THEME_DIR', get_template_directory() );
define( 'DAM_THEME_URI', get_template_directory_uri() );

require_once DAM_THEME_DIR . '/inc/post-types.php';
require_once DAM_THEME_DIR . '/inc/taxonomies.php';
require_once DAM_THEME_DIR . '/inc/meta-fields.php';
require_once DAM_THEME_DIR . '/inc/theme-options.php';
require_once DAM_THEME_DIR . '/inc/icons.php';
require_once DAM_THEME_DIR . '/inc/homepage-content.php';
require_once DAM_THEME_DIR . '/inc/interior-content.php';
require_once DAM_THEME_DIR . '/inc/team.php';
require_once DAM_THEME_DIR . '/inc/about-content.php';
require_once DAM_THEME_DIR . '/inc/nav-walker.php';
require_once DAM_THEME_DIR . '/inc/blocks.php';
require_once DAM_THEME_DIR . '/inc/polylang.php';

/**
 * Theme setup: text domain, supports, nav menu locations.
 *
 * Menus are registered here and managed by the operator under
 * Appearance -> Menus; they are rendered by the theme's own
 * dr-ali-moradi/site-navigation block (see blocks/site-navigation),
 * not the core Navigation block, so menu editing always happens in the
 * classic Menus screen rather than inside the Site Editor.
 */
function dam_setup() {
	load_theme_textdomain( 'dr-ali-moradi', DAM_THEME_DIR . '/languages' );

	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ) );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'dr-ali-moradi' ),
			'footer'  => __( 'Footer Navigation', 'dr-ali-moradi' ),
		)
	);
}
add_action( 'after_setup_theme', 'dam_setup' );

/**
 * Editor styles so the block editor roughly matches the front end.
 */
function dam_enqueue_editor_assets() {
	add_editor_style( 'assets/css/editor.css' );
}
add_action( 'after_setup_theme', 'dam_enqueue_editor_assets' );

/**
 * Front-end stylesheet. theme.json/Global Styles cover most presentation;
 * this file only holds the handful of rules block support doesn't reach
 * (e.g. component-specific layout for the theme's own dynamic blocks).
 */
function dam_enqueue_assets() {
	wp_enqueue_style( 'dr-ali-moradi-style', DAM_THEME_URI . '/assets/css/style.css', array(), DAM_THEME_VERSION );
	wp_enqueue_script( 'dr-ali-moradi-site', DAM_THEME_URI . '/assets/js/site.js', array(), DAM_THEME_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'dam_enqueue_assets' );

/**
 * Adds a `locale-{lang}` body class (matching the reference design's own
 * `.locale-fa` / `.locale-ar` selectors) so Persian/Arabic pages pick up
 * the Vazirmatn/Scheherazade font stack without per-template overrides.
 */
/**
 * URL for a theme-bundled asset with the same `?ver=` cache-busting the
 * enqueued CSS/JS already use. Without this, a file that 404s once (e.g.
 * mid-deploy) gets that failure cached by the browser for as long as the
 * host's `Cache-Control` header says -- confirmed on this host to be a
 * literal 10 years -- so a later successful deploy of the *same* URL
 * never fixes it for anyone who already hit the 404. See progress-log.md.
 */
function dam_theme_asset_url( $relative_path ) {
	return DAM_THEME_URI . $relative_path . '?ver=' . DAM_THEME_VERSION;
}

function dam_locale_body_class( $classes ) {
	$locale     = dam_current_locale();
	$classes[]  = 'locale-' . $locale;
	$classes[]  = 'en' === $locale ? 'ltr' : 'rtl';
	return $classes;
}
add_filter( 'body_class', 'dam_locale_body_class' );
