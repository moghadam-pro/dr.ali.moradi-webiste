<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$id = get_the_ID();
dam_render_patient_cover( get_the_title( $id ), 0, $id );
?>
<div class="section-shell section-space patient-archive-layout">
<aside class="patient-sidebar" aria-label="<?php echo esc_attr( dam_patient_label( 'Patient categories', 'دسته‌بندی بیماران', 'تصنيفات المرضى' ) ); ?>"><h2><?php echo esc_html( dam_patient_label( 'Categories', 'دسته‌بندی‌ها', 'التصنيفات' ) ); ?></h2><?php dam_render_patient_tree(); ?></aside>
<article class="patient-profile">
<h2><?php echo esc_html( get_the_title( $id ) ); ?></h2>
<p class="patient-summary"><?php echo esc_html( get_the_excerpt( $id ) ); ?></p>
<div class="patient-terms"><?php echo get_the_term_list( $id, 'patient_category', '', ' · ' ); ?> <?php echo get_the_term_list( $id, 'patient_tag', '', ' · ' ); ?></div>
<div class="article-body"><?php the_content(); ?></div>
<?php dam_render_patient_gallery( dam_patient_media( $id ), true, 1 ); ?>
</article>
</div>
