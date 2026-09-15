<?php
/**
 * Used only by templates with no PHP render file of their own to hook
 * dam_render_breadcrumbs() into after a cover section (see inc/seo.php) --
 * everywhere else the theme's own dynamic blocks call that function
 * directly right after their inline cover markup.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

dam_render_breadcrumbs();
