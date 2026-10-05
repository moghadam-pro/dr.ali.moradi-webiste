<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$term = is_tax() ? get_queried_object() : null;
dam_render_patient_archive( is_tax( 'patient_category' ) ? $term->term_id : 0, $term ? dam_patient_term_name( $term ) : '' );
