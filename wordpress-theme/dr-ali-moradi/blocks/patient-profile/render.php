<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$id = get_the_ID();
?>
<article class="patient-profile section-shell section-space">
<h1><?php echo esc_html( get_the_title( $id ) ); ?></h1>
<p class="patient-summary"><?php echo esc_html( get_the_excerpt( $id ) ); ?></p>
<div class="patient-terms"><?php echo get_the_term_list( $id, 'patient_category', '', ' · ' ); ?> <?php echo get_the_term_list( $id, 'patient_tag', '', ' · ' ); ?></div>
<div class="article-body"><?php the_content(); ?></div>
<?php dam_render_patient_gallery( dam_patient_media( $id ) ); ?>
</article>
