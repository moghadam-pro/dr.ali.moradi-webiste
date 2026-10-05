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
		'en' => array( 'kicker' => 'People', 'title' => 'Meet the team', 'intro' => 'Clinical, research, and engineering work is delivered through coordinated specialist roles.', 'readProfile' => 'Read More', 'back' => 'Back to the team', 'profileIntro' => 'Professional profile', 'expertise' => 'Area of work', 'collaboration' => 'Contribution to the team', 'relatedLinks' => 'Related links' ),
		'fa' => array( 'kicker' => 'اعضای تیم', 'title' => 'آشنایی با تیم', 'intro' => 'فعالیت بالینی، پژوهشی و مهندسی با همکاری نقش‌های تخصصی و هماهنگ پیش می‌رود.', 'readProfile' => 'بیشتر بخوانید', 'back' => 'بازگشت به اعضای تیم', 'profileIntro' => 'پروفایل حرفه‌ای', 'expertise' => 'حوزه فعالیت', 'collaboration' => 'نقش در تیم', 'relatedLinks' => 'لینک‌های مرتبط' ),
		'ar' => array( 'kicker' => 'الفريق', 'title' => 'تعرّف إلى الفريق', 'intro' => 'تتقدم الأعمال السريرية والبحثية والهندسية عبر أدوار تخصصية منسقة.', 'readProfile' => 'اقرأ المزيد', 'back' => 'العودة إلى الفريق', 'profileIntro' => 'الملف المهني', 'expertise' => 'مجال العمل', 'collaboration' => 'الدور في الفريق', 'relatedLinks' => 'روابط ذات صلة' ),
	);
	return $labels[ $locale ] ?? $labels['en'];
}

/** Which hub page a team member's own team_area hub is, based on the
 * team_area taxonomy terms on their post (first match wins, in this
 * priority order -- mirrors the reference's own
 * `member.areas.includes("clinic") ? ... : ...` fallback chain).
 *
 * Terms are matched by their *English* slug via pll_get_term(), never the
 * slug wp_get_post_terms() itself returns -- on a non-English post that
 * call returns the Persian/Arabic term (its own, differently-slugged
 * translation), so comparing it against the hardcoded English slugs below
 * silently never matched and every non-English member fell through to the
 * "research" default (confirmed live: a Persian Innovation-team member's
 * hub incorrectly resolved to /fa/research/). */
function dam_team_member_back_slug( $post_id ) {
	$terms = wp_get_post_terms( $post_id, 'team_area', array( 'fields' => 'ids' ) );
	foreach ( $terms as $term_id ) {
		$en_term_id = function_exists( 'pll_get_term' ) ? pll_get_term( $term_id, 'en' ) : $term_id;
		$term       = $en_term_id ? get_term( $en_term_id, 'team_area' ) : null;
		$slug       = ( $term && ! is_wp_error( $term ) ) ? $term->slug : null;
		if ( 'clinical-care' === $slug ) {
			return 'clinical-care';
		}
		if ( 'innovation' === $slug ) {
			return 'innovations';
		}
		if ( 'research' === $slug ) {
			return 'research';
		}
	}
	return 'research';
}

/**
 * `$area` doubles as the `team-{$area}` CSS class (matching the
 * reference's own class names, e.g. `team-clinic`), but the real
 * `team_area` taxonomy term for the clinic pathway was imported with the
 * slug `clinical-care` (matching the Clinical Care hub page's own slug),
 * not `clinic` -- so the term lookup needs its own mapping rather than
 * reusing `$area` directly.
 */
function dam_render_team_section( $area ) {
	$locale    = dam_current_locale();
	$labels    = dam_team_labels( $locale );
	$term_slug = 'clinic' === $area ? 'clinical-care' : $area;

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
					'terms'    => $term_slug,
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
