<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale   = dam_current_locale();
$page_key = ! empty( $attributes['pageKey'] ) ? $attributes['pageKey'] : dam_current_page_key();
$ic       = dam_interior_pages_copy( $locale );
$data     = $ic['pages'][ $page_key ] ?? null;

if ( ! $data || empty( $data['sections'] ) ) {
	return;
}

$nobat_url = dam_appointment_url();
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'page-content section-space section-shell large-counts' ) ); ?>>
	<aside>
		<p><?php echo esc_html( $ic['onThisPage'] ); ?></p>
		<?php foreach ( $data['sections'] as $i => $section ) : ?>
			<a href="#section-<?php echo (int) $i; ?>"><?php echo esc_html( $section['title'] ); ?></a>
		<?php endforeach; ?>
		<a href="<?php echo esc_url( $nobat_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $ic['pageAppointment'] ); ?><?php echo dam_icon( 'external-link', 14 ); ?></a>
	</aside>
	<div class="content-sections">
		<?php foreach ( $data['sections'] as $i => $section ) : ?>
			<div class="content-section reveal">
				<span class="section-count"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
				<div>
					<h2 id="section-<?php echo (int) $i; ?>"><?php echo esc_html( $section['title'] ); ?></h2>
					<p><?php echo esc_html( $section['text'] ); ?></p>
					<?php if ( ! empty( $section['items'] ) ) : ?>
						<ul>
							<?php foreach ( $section['items'] as $item ) : ?>
								<li><?php echo dam_icon( 'check', 17 ); ?><?php echo esc_html( $item ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>

		<?php if ( 'research' === $page_key ) :
			$scholar = $ic['scholar'];
			?>
			<div class="next-step reveal">
				<?php echo dam_icon( 'sparkles' ); ?>
				<div><p><?php echo esc_html( $ic['nextLabel'] ); ?></p><h2><?php echo esc_html( $scholar['title'] ); ?></h2><span><?php echo esc_html( $scholar['text'] ); ?></span></div>
				<a class="button" href="https://scholar.google.com/citations?user=UhXLjGEAAAAJ&amp;hl=en" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $scholar['action'] ); ?><?php echo dam_icon( 'external-link', 16 ); ?></a>
			</div>
		<?php else :
			$next_slug = 'about' === $page_key ? 'research' : 'contact';
			$next_url  = dam_localized_page_url( $next_slug, $locale );
			?>
			<div class="next-step reveal">
				<?php echo dam_icon( 'sparkles' ); ?>
				<div><p><?php echo esc_html( $ic['nextLabel'] ); ?></p><h2><?php echo esc_html( $data['ctaTitle'] ); ?></h2><span><?php echo esc_html( $data['ctaText'] ); ?></span></div>
				<a class="button" href="<?php echo esc_url( $next_url ); ?>"><?php echo esc_html( $ic['exploreMore'] ); ?><?php echo dam_icon( 'arrow-right', 16 ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php
if ( 'innovations' === $page_key ) {
	dam_render_team_section( 'innovation' );
} elseif ( 'research' === $page_key ) {
	dam_render_team_section( 'research' );
}
