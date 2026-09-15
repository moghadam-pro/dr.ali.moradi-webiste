<?php
/**
 * Blog archive/single-post copy, transcribed verbatim from the
 * reference site's app/blog-content.ts `blogLabels` (same static-copy
 * rule as the theme's other inc/*-content.php files). The single-post
 * body itself renders the post's own editor content (see
 * blocks/single-post-body/render.php) -- these labels only cover the
 * archive hero and the single-post sidebar (back link, tags,
 * categories headings).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The "Innovation" category (seeded as 'innovation-news' by
 * dam_seed_post_categories()) an operator tags a post with from the normal
 * Categories panel in the post editor. Resolved to the current locale's own
 * translated term the same way dam_team_member_back_slug() resolves
 * team_area terms -- Polylang gives every language its own term object
 * with its own (differently-slugged) name, so the English slug only
 * matches the English term directly.
 */
function dam_innovation_category_id( $locale = null ) {
	$locale = $locale ? $locale : dam_current_locale();
	// get_term_by() is subject to the same Polylang current-language term
	// filtering as get_terms() -- confirmed live: looking up the English
	// 'innovation-news' slug while browsing /fa/ or /ar/ returned nothing,
	// because that term only exists in the English language. get_terms()
	// with an explicit `lang => ''` bypasses Polylang's filter so the
	// lookup finds the term regardless of which language is being viewed.
	$terms = get_terms(
		array(
			'taxonomy'   => 'category',
			'slug'       => 'innovation-news',
			'hide_empty' => false,
			'lang'       => '',
		)
	);
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return 0;
	}
	$term_id = $terms[0]->term_id;
	if ( function_exists( 'pll_get_term' ) ) {
		$translated = pll_get_term( $term_id, $locale );
		if ( $translated ) {
			return $translated;
		}
	}
	return $term_id;
}

/**
 * Posts in the Innovation category, newest first. Shared by the homepage's
 * "Selected innovation stories" section (latest 3) and the Innovations
 * hub page's own list underneath the team section (everything after those
 * same 3, via $offset) so the two never show the same story twice.
 */
function dam_innovation_posts( $locale = null, $limit = -1, $offset = 0 ) {
	$locale = $locale ? $locale : dam_current_locale();
	$cat_id = dam_innovation_category_id( $locale );
	if ( ! $cat_id ) {
		return array();
	}
	return get_posts(
		array(
			'post_type'      => 'post',
			'posts_per_page' => $limit,
			'offset'         => $offset,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
			'cat'            => $cat_id,
		)
	);
}

/**
 * The Innovation-category posts not already shown in the homepage's
 * 3-card "Selected innovation stories" section, listed under the
 * Innovations page's own team row -- reuses the same blog-card markup
 * as the /blog/ archive so a card looks the same wherever it appears.
 */
function dam_render_innovation_posts_section( $locale ) {
	// WP_Query silently ignores `offset` when `posts_per_page` is -1 (no
	// LIMIT clause means no OFFSET either) -- confirmed live: this came
	// back showing all 6 posts, the same 3 duplicated from the homepage
	// included, instead of just the remaining 3. A large finite page size
	// keeps the LIMIT/OFFSET pair in the query.
	$posts = dam_innovation_posts( $locale, 50, 3 );
	if ( empty( $posts ) ) {
		return;
	}
	$blog_labels = dam_blog_labels( $locale );
	$heading     = array(
		'en' => 'More innovation stories',
		'fa' => 'سایر روایت‌های نوآوری',
		'ar' => 'المزيد من قصص الابتكار',
	);
	?>
	<section class="innovation-posts section-space section-shell">
		<div class="section-heading reveal">
			<p><?php echo esc_html( $heading[ $locale ] ?? $heading['en'] ); ?></p>
		</div>
		<div class="blog-grid">
			<?php foreach ( $posts as $post ) :
				$categories   = get_the_category( $post->ID );
				$category     = $categories ? $categories[0]->name : '';
				$read_minutes = get_post_meta( $post->ID, 'dam_read_minutes', true );
				$link         = get_permalink( $post );
				?>
				<div class="blog-card reveal">
					<a class="blog-card-image" href="<?php echo esc_url( $link ); ?>">
						<?php if ( has_post_thumbnail( $post ) ) : ?>
							<?php echo get_the_post_thumbnail( $post, 'medium', array( 'class' => 'fill-img', 'alt' => get_the_title( $post ) ) ); ?>
						<?php endif; ?>
					</a>
					<div class="blog-card-copy">
						<?php if ( $category ) : ?><p class="card-tag"><?php echo esc_html( $category ); ?></p><?php endif; ?>
						<h2><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( get_the_title( $post ) ); ?></a></h2>
						<p><?php echo esc_html( get_the_excerpt( $post ) ); ?></p>
						<div class="blog-card-meta">
							<?php if ( $read_minutes ) : ?><span><?php echo esc_html( $read_minutes . ' ' . $blog_labels['minutes'] ); ?></span><?php endif; ?>
							<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $blog_labels['read'] ); ?><?php echo dam_icon( 'arrow-right', 15 ); ?></a>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
}

function dam_blog_labels( $locale = null ) {
	static $all = null;
	if ( null === $all ) {
		$all = array(
			'en' => array(
				'kicker' => 'Blog', 'title' => 'Practical education for hand and upper-extremity health.', 'intro' => 'A growing library for patients, learners, and collaborators. Content is educational and never replaces individual assessment.',
				'read' => 'Read article', 'minutes' => 'min read', 'back' => 'Back to all articles',
				'tags' => 'Tags', 'categories' => 'Categories',
				'postInfo' => 'Post Info', 'publishedOn' => 'Published on', 'readTime' => 'Read time',
				'author' => 'Author', 'category' => 'Category', 'comments' => 'Comments',
			),
			'fa' => array(
				'kicker' => 'وبلاگ', 'title' => 'آموزش کاربردی سلامت دست و اندام فوقانی.', 'intro' => 'کتابخانه‌ای رو به رشد برای بیماران، فراگیران و همکاران؛ این محتوا آموزشی است و جایگزین ارزیابی فردی نیست.',
				'read' => 'مطالعه مقاله', 'minutes' => 'دقیقه مطالعه', 'back' => 'بازگشت به همه مقاله‌ها',
				'tags' => 'برچسب‌ها', 'categories' => 'دسته‌بندی‌های وبلاگ',
				'postInfo' => 'اطلاعات این پست', 'publishedOn' => 'تاریخ انتشار', 'readTime' => 'زمان مطالعه',
				'author' => 'نویسنده', 'category' => 'دسته‌بندی', 'comments' => 'دیدگاه‌ها',
			),
			'ar' => array(
				'kicker' => 'المدونة', 'title' => 'تثقيف عملي لصحة اليد والطرف العلوي.', 'intro' => 'مكتبة متنامية للمرضى والمتعلمين والمتعاونين؛ المحتوى تعليمي ولا يستبدل التقييم الفردي.',
				'read' => 'اقرأ المقال', 'minutes' => 'دقائق قراءة', 'back' => 'العودة إلى كل المقالات',
				'tags' => 'الوسوم', 'categories' => 'تصنيفات المدونة',
				'postInfo' => 'معلومات المقال', 'publishedOn' => 'تاريخ النشر', 'readTime' => 'وقت القراءة',
				'author' => 'الكاتب', 'category' => 'التصنيف', 'comments' => 'التعليقات',
			),
		);
	}
	$locale = $locale ? $locale : dam_current_locale();
	return isset( $all[ $locale ] ) ? $all[ $locale ] : $all['en'];
}
