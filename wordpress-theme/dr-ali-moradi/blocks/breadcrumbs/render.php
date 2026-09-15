<?php
/**
 * Rank Math's own breadcrumb trail (real Schema.org BreadcrumbList data,
 * respects any crumb customization in Rank Math's own settings), placed
 * in the shared header template part so every template gets it from one
 * edit. Not shown on the front page -- there's nothing to trail back
 * from on the homepage itself.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_front_page() || ! function_exists( 'rank_math_the_breadcrumbs' ) ) {
	return;
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'site-breadcrumbs' ) ); ?>>
	<div class="section-shell">
		<?php rank_math_the_breadcrumbs(); ?>
	</div>
</div>
