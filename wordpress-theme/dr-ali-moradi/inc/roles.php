<?php
/**
 * Least-privilege role for the clinic content operator.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DAM_CONTENT_MANAGER_ROLE', 'dam_content_manager' );
define( 'DAM_HOMEPAGE_CAPABILITY', 'dam_edit_homepage_content' );
define( 'DAM_CONTENT_MANAGER_ROLE_VERSION', '1.0.0' );
define( 'DAM_CONTENT_MANAGER_ROLE_OPTION', 'dam_content_manager_role_version' );

/**
 * Capabilities needed for Posts, media, categories, and Team Members.
 * Team Members use WordPress's built-in post capabilities.
 */
function dam_content_manager_capabilities() {
	return array(
		'read'                   => true,
		'edit_posts'             => true,
		'edit_others_posts'      => true,
		'edit_published_posts'   => true,
		'publish_posts'          => true,
		'delete_posts'           => true,
		'delete_others_posts'    => true,
		'delete_published_posts' => true,
		'upload_files'           => true,
		'manage_categories'      => true,
		DAM_HOMEPAGE_CAPABILITY  => true,
	);
}

/**
 * Create or repair the role only when its schema changes.
 */
function dam_sync_content_manager_role() {
	if ( DAM_CONTENT_MANAGER_ROLE_VERSION === get_option( DAM_CONTENT_MANAGER_ROLE_OPTION ) ) {
		return;
	}

	$capabilities = dam_content_manager_capabilities();
	$role         = get_role( DAM_CONTENT_MANAGER_ROLE );

	if ( ! $role ) {
		$role = add_role(
			DAM_CONTENT_MANAGER_ROLE,
			__( 'Content Manager — مدیر محتوا', 'dr-ali-moradi' ),
			$capabilities
		);
	}

	if ( $role ) {
		foreach ( array_keys( (array) $role->capabilities ) as $capability ) {
			if ( ! isset( $capabilities[ $capability ] ) ) {
				$role->remove_cap( $capability );
			}
		}
		foreach ( $capabilities as $capability => $grant ) {
			$role->add_cap( $capability, $grant );
		}
	}

	// Administrators must retain access after the Homepage panel switches from
	// the broad edit_theme_options capability to its dedicated capability.
	$administrator = get_role( 'administrator' );
	if ( $administrator ) {
		$administrator->add_cap( DAM_HOMEPAGE_CAPABILITY );
	}

	if ( $role && $administrator ) {
		update_option( DAM_CONTENT_MANAGER_ROLE_OPTION, DAM_CONTENT_MANAGER_ROLE_VERSION, false );
	}
}
add_action( 'after_switch_theme', 'dam_sync_content_manager_role' );
add_action( 'admin_init', 'dam_sync_content_manager_role', 5 );

/**
 * Permit this role to enter the Customizer without granting the much broader
 * edit_theme_options capability. Individual settings still enforce their own
 * dedicated capability.
 */
function dam_map_content_manager_customize_capability( $required_caps, $capability, $user_id, $args ) {
	if ( 'customize' !== $capability ) {
		return $required_caps;
	}

	$user = get_userdata( $user_id );
	if ( $user && ! empty( $user->allcaps[ DAM_HOMEPAGE_CAPABILITY ] ) ) {
		return array( DAM_HOMEPAGE_CAPABILITY );
	}

	return $required_caps;
}
add_filter( 'map_meta_cap', 'dam_map_content_manager_customize_capability', 10, 4 );

/**
 * Give operators an obvious entry point without exposing Appearance screens.
 */
function dam_content_manager_admin_menu() {
	add_menu_page(
		__( 'Homepage Content — محتوای صفحه نخست', 'dr-ali-moradi' ),
		'محتوای صفحه نخست',
		DAM_HOMEPAGE_CAPABILITY,
		'dam-homepage-content',
		'dam_render_content_manager_page',
		'dashicons-admin-home',
		21
	);
}
add_action( 'admin_menu', 'dam_content_manager_admin_menu' );

function dam_render_content_manager_page() {
	$customizer_url = add_query_arg(
		array( 'autofocus[panel]' => 'dam_homepage_content' ),
		admin_url( 'customize.php' )
	);
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Homepage Content — محتوای صفحه نخست', 'dr-ali-moradi' ); ?></h1>
		<p><?php esc_html_e( 'Edit the English, Persian, and Arabic homepage sections in the live preview — محتوای صفحه نخست سه زبان را با پیش‌نمایش زنده ویرایش کنید.', 'dr-ali-moradi' ); ?></p>
		<p><a class="button button-primary" href="<?php echo esc_url( $customizer_url ); ?>"><?php esc_html_e( 'Open Homepage Editor — بازکردن ویرایشگر', 'dr-ali-moradi' ); ?></a></p>
	</div>
	<?php
}
