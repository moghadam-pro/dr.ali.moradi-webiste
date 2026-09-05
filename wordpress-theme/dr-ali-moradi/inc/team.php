<?php
/**
 * Shared team-grid renderer, used on the Clinical Care hub and on the
 * Research/Innovation interior pages (matching the reference's
 * <TeamSection area="..." /> reuse). Queries the real team_member CPT
 * posts (already imported) by the team_area taxonomy rather than
 * duplicating team bios as static copy.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dam_team_labels( $locale ) {
	$labels = array(
		'en' => array( 'kicker' => 'People', 'title' => 'Meet the team', 'intro' => 'Clinical, research, and engineering work is delivered through coordinated specialist roles.', 'readProfile' => 'Read More' ),
		'fa' => array( 'kicker' => 'اعضای تیم', 'title' => 'آشنایی با تیم', 'intro' => 'فعالیت بالینی، پژوهشی و مهندسی با همکاری نقش‌های تخصصی و هماهنگ پیش می‌رود.', 'readProfile' => 'بیشتر بخوانید' ),
		'ar' => array( 'kicker' => 'الفريق', 'title' => 'تعرّف إلى الفريق', 'intro' => 'تتقدم الأعمال السريرية والبحثية والهندسية عبر أدوار تخصصية منسقة.', 'readProfile' => 'اقرأ المزيد' ),
	);
	return $labels[ $locale ] ?? $labels['en'];
}

function dam_render_team_section( $area ) {
	$locale = dam_current_locale();
	$labels = dam_team_labels( $locale );

	$members = get_posts(
		array(
			'post_type'      => 'team_member',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'no_found_rows'  => true,
			'tax_query'      => array(
				array(
					'taxonomy' => 'team_area',
					'field'    => 'slug',
					'terms'    => $area,
				),
			),
		)
	);

	if ( empty( $members ) ) {
		return;
	}
	?>
	<section class="team-section team-<?php echo esc_attr( $area ); ?> section-space">
		<div class="section-shell">
			<div class="section-heading split-heading reveal">
				<div><p class="section-index"><?php echo esc_html( $labels['kicker'] ); ?></p><p><?php echo esc_html( $labels['title'] ); ?>. <?php echo esc_html( $labels['intro'] ); ?></p></div>
			</div>
			<div class="team-grid">
				<?php foreach ( $members as $member ) :
					$role    = get_post_meta( $member->ID, 'dam_role', true );
					$summary = get_post_meta( $member->ID, 'dam_summary', true );
					$link    = get_permalink( $member );
					?>
					<div class="team-card reveal">
						<a class="team-card-image" href="<?php echo esc_url( $link ); ?>">
							<?php if ( has_post_thumbnail( $member ) ) : ?>
								<?php echo get_the_post_thumbnail( $member, 'medium', array( 'class' => 'fill-img', 'alt' => '' ) ); ?>
							<?php endif; ?>
						</a>
						<div class="team-card-copy">
							<?php if ( $role ) : ?><p><?php echo esc_html( $role ); ?></p><?php endif; ?>
							<h3><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( get_the_title( $member ) ); ?></a></h3>
							<?php if ( $summary ) : ?><span><?php echo esc_html( $summary ); ?></span><?php endif; ?>
							<a class="text-link" href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $labels['readProfile'] ); ?></a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}
