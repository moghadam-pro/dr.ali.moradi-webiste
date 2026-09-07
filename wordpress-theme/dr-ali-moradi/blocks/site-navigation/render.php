<?php
/**
 * Deliberately uses wp_nav_menu() against a registered theme_location
 * instead of the core Navigation block, so menu editing always happens on
 * Appearance -> Menus, not inside the Site Editor. Location is registered
 * in functions.php / dam_setup().
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$location = isset( $attributes['location'] ) ? $attributes['location'] : 'primary';

if ( ! has_nav_menu( $location ) ) {
	return;
}
?>
<nav <?php echo get_block_wrapper_attributes( array( 'class' => 'dam-site-navigation dam-site-navigation--' . esc_attr( $location ) ) ); ?> aria-label="<?php echo 'footer' === $location ? esc_attr__( 'Footer navigation', 'dr-ali-moradi' ) : esc_attr__( 'Primary navigation', 'dr-ali-moradi' ); ?>">
	<?php
	wp_nav_menu(
		array(
			'theme_location' => $location,
			'container'      => false,
			'menu_class'     => 'dam-site-navigation__list',
			'fallback_cb'    => false,
			'depth'          => 2,
		)
	);
	?>
</nav>
