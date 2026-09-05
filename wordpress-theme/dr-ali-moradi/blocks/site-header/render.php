<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale = dam_current_locale();
$rtl    = 'en' !== $locale;
$t      = dam_site_copy( $locale );
$logo   = 'en' === $locale ? 'logo.en.svg' : 'logo.fa-ar.svg';
$home   = function_exists( 'pll_home_url' ) ? pll_home_url( $locale ) : home_url( '/' );

$menu_icon_open  = dam_icon( 'menu', 24 );
$menu_icon_close = dam_icon( 'x', 24 );
?>
<a class="skip-link" href="#content"><?php echo esc_html( $t['skip'] ?? 'Skip to content' ); ?></a>
<header <?php echo get_block_wrapper_attributes( array( 'class' => 'site-header' ) ); ?>>
	<a class="brand" href="<?php echo esc_url( $home ); ?>" aria-label="<?php esc_attr_e( 'Dr. Ali Moradi home', 'dr-ali-moradi' ); ?>">
		<img src="<?php echo esc_url( DAM_THEME_URI . '/assets/img/brand/' . $logo ); ?>" width="153" height="50" alt="Dr. Ali Moradi">
	</a>

	<?php if ( has_nav_menu( 'primary' ) ) : ?>
	<nav class="desktop-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'dr-ali-moradi' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'items_wrap'     => '%3$s',
				'walker'         => new Dam_Desktop_Nav_Walker(),
				'fallback_cb'    => false,
			)
		);
		?>
	</nav>
	<?php endif; ?>

	<div class="header-actions">
		<?php if ( function_exists( 'pll_the_languages' ) ) :
			$languages = pll_the_languages( array( 'raw' => 1, 'hide_if_empty' => 0 ) );
			if ( $languages ) :
				$flags = array( 'en' => '🇬🇧', 'fa' => '🇮🇷', 'ar' => '🇸🇦' );
				$names = array( 'en' => 'English', 'fa' => 'فارسی', 'ar' => 'العربية' );
				usort( $languages, function ( $a, $b ) {
					$order = array( 'en' => 0, 'fa' => 1, 'ar' => 2 );
					return ( $order[ $a['slug'] ] ?? 9 ) <=> ( $order[ $b['slug'] ] ?? 9 );
				} );
				?>
				<div class="language-control">
					<button type="button" class="language-button" data-language-toggle aria-expanded="false" aria-label="<?php echo esc_attr( $t['chooseLanguage'] ); ?>">
						<?php echo dam_icon( 'languages', 18 ); ?><span><?php echo esc_html( strtoupper( $locale ) ); ?></span><?php echo dam_icon( 'chevron-down', 14 ); ?>
					</button>
					<div class="language-menu" data-language-menu hidden>
						<?php foreach ( $languages as $language ) : ?>
							<a class="<?php echo $language['slug'] === $locale ? 'selected' : ''; ?>" href="<?php echo esc_url( $language['url'] ); ?>">
								<?php echo esc_html( ( $flags[ $language['slug'] ] ?? '' ) . ' ' . ( $names[ $language['slug'] ] ?? $language['name'] ) ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif;
		endif; ?>

		<a class="button button-small" href="<?php echo esc_url( dam_appointment_url() ); ?>" target="_blank" rel="noopener noreferrer">
			<?php echo esc_html( $t['appointment'] ); ?><?php echo dam_icon( 'external-link', 15 ); ?>
		</a>

		<?php if ( has_nav_menu( 'primary' ) ) : ?>
		<button type="button" class="menu-button" data-menu-toggle aria-expanded="false" aria-label="<?php echo esc_attr( $t['menuToggle'] ); ?>" data-icon-open="<?php echo esc_attr( $menu_icon_open ); ?>" data-icon-close="<?php echo esc_attr( $menu_icon_close ); ?>">
			<?php echo $menu_icon_open; ?>
		</button>
		<?php endif; ?>
	</div>
</header>

<?php if ( has_nav_menu( 'primary' ) ) : ?>
<nav class="mobile-nav" data-mobile-nav aria-label="<?php esc_attr_e( 'Mobile navigation', 'dr-ali-moradi' ); ?>" hidden>
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'container'      => false,
			'items_wrap'     => '%3$s',
			'walker'         => new Dam_Mobile_Nav_Walker(),
			'fallback_cb'    => false,
		)
	);
	?>
	<a class="button" href="<?php echo esc_url( dam_appointment_url() ); ?>" target="_blank" rel="noopener noreferrer">
		<?php echo esc_html( $t['appointment'] ); ?><?php echo dam_icon( 'external-link', 16 ); ?>
	</a>
</nav>
<?php endif; ?>
