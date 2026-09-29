<?php
/**
 * One-time removal of the Content Manager role and its Homepage Content menu
 * entry, which theme 1.2.0 added and 3.0.0 retires.
 *
 * The role is only deleted while no user has it, so an account is never left
 * without a role; if someone still holds it, the role stays and the removal is
 * retried on the next administrator request. The dedicated capability is
 * always taken away from Administrators, since nothing checks it any more.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DAM_ROLE_CLEANUP_VERSION', '1.0.0' );
define( 'DAM_ROLE_CLEANUP_OPTION', 'dam_role_cleanup_version' );

function dam_cleanup_content_manager_role() {
	if ( DAM_ROLE_CLEANUP_VERSION === get_option( DAM_ROLE_CLEANUP_OPTION ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$administrator = get_role( 'administrator' );
	if ( $administrator ) {
		$administrator->remove_cap( 'dam_edit_homepage_content' );
	}

	if ( get_role( 'dam_content_manager' ) ) {
		$holders = get_users( array( 'role' => 'dam_content_manager', 'fields' => 'ID', 'number' => 1 ) );
		if ( $holders ) {
			return; // Still assigned to someone: keep the role and retry later.
		}
		remove_role( 'dam_content_manager' );
	}

	delete_option( 'dam_content_manager_role_version' );
	update_option( DAM_ROLE_CLEANUP_OPTION, DAM_ROLE_CLEANUP_VERSION, false );
}
add_action( 'admin_init', 'dam_cleanup_content_manager_role', 5 );
