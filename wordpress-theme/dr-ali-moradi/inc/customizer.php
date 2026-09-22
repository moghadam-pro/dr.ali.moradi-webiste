<?php
/**
 * One multilingual homepage Customizer panel with a live preview for each
 * language. Native WordPress panels cannot contain other panels, so the one
 * top-level panel contains three language sections; headings inside each
 * section group the editable homepage areas.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dam_customizer_rows_to_text( $rows ) {
	$lines = array();
	foreach ( (array) $rows as $row ) {
		$lines[] = implode( '|', array_map( function ( $value ) { return str_replace( '|', '-', (string) $value ); }, (array) $row ) );
	}
	return implode( "\n", $lines );
}

function dam_customizer_text_to_rows( $text ) {
	$rows = array();
	foreach ( preg_split( '/\r\n|\r|\n/', trim( (string) $text ) ) as $line ) {
		if ( '' !== trim( $line ) ) {
			$rows[] = array_map( 'trim', explode( '|', $line ) );
		}
	}
	return $rows;
}

function dam_customizer_localized_url( $slug, $locale ) {
	return function_exists( 'dam_localized_page_url' ) ? dam_localized_page_url( $slug, $locale ) : home_url( '/' . $slug . '/' );
}

function dam_customizer_defaults( $locale ) {
	$t = dam_site_copy( $locale );
	$d = array(
		'hero_name_first' => $t['heroName'][0], 'hero_name_last' => $t['heroName'][1],
		'hero_credentials' => dam_customizer_rows_to_text( $t['heroCredentials'] ),
		'hero_description' => $t['heroDescription'], 'hero_quote' => $t['heroQuote'],
		'hero_facets' => dam_customizer_rows_to_text( $t['facets'] ),
		'hero_credential_list' => implode( "\n", $t['credentials'] ),
		'hero_background_image' => '', 'hero_orbits_enabled' => true,

		'journey_kicker' => $t['connectedIndex'], 'journey_intro' => $t['storyBody'],
		'journey_link_label' => $t['journeyLink'],
		'journey_link_url' => dam_customizer_localized_url( 'innovations', $locale ),

		'pathways_kicker' => $t['pathwaysIndex'], 'pathways_title' => $t['pathsTitle'],
		'pathways_body' => $t['pathsBody'],

		'innovation_kicker' => $t['innovationIndex'], 'innovation_title' => $t['innovationTitle'],
		'innovation_subtitle' => $t['innovationIntro'], 'innovation_source' => 'dynamic',
		'innovation_category' => function_exists( 'dam_innovation_category_id' ) ? dam_innovation_category_id( $locale ) : 0,
		'innovation_count' => 3, 'innovation_columns' => 3,

		'impact_kicker' => $t['impactIndex'], 'impact_title' => $t['impactTitle'],
		'impact_subtitle' => $t['figuresNote'],

		'appointments_kicker' => $t['appointmentsIndex'], 'appointment_title' => $t['appointmentTitle'],
		'appointment_body' => $t['appointmentBody'], 'appointment_image' => '',
		'appointment_cta_label' => $t['continueNobat'],
		'appointment_urgent_days' => $t['urgentDays'], 'appointment_urgent_hours' => $t['urgentHours'],

		'recognition_kicker' => $t['awardsKicker'], 'recognition_title' => $t['awardsTitle'],
		'recognition_subtitle' => $t['awardsIntro'], 'recognition_link_label' => $t['allUpdates'],
		'recognition_source' => 'dynamic',
		'recognition_category' => function_exists( 'dam_category_id_by_slug' ) ? dam_category_id_by_slug( 'awards-certificates', $locale ) : 0,
		'recognition_count' => 3, 'recognition_columns' => 3,

		'about_kicker' => $t['aboutIndex'], 'about_body' => $t['aboutBody'],
		'about_cta_label' => $t['meetDoctor'], 'about_cta_url' => dam_customizer_localized_url( 'about', $locale ),
		'about_research_label' => $t['researchProfile'], 'about_research_url' => dam_customizer_localized_url( 'research', $locale ),
		'about_image' => '',

		'footer_logo' => '', 'footer_bio' => $t['footer']['bio'],
		'footer_booking_label' => $t['footer']['booking'],
		'footer_booking_url' => function_exists( 'dam_appointment_url' ) ? dam_appointment_url() : 'https://nobat.ir/9705',
		'footer_explore_title' => $t['footer']['explore'], 'footer_resources_title' => $t['footer']['resources'],
		'footer_contact_title' => $t['footer']['contact'], 'footer_social_title' => $t['footer']['social'],
		'footer_email' => 'info@DrAliMoradi.com', 'footer_phone' => get_option( 'dam_clinic_phone', '' ),
		'footer_office_address' => $t['contact']['office'], 'footer_clinic_address' => $t['contact']['clinic'],
		'footer_map_label' => $t['footer']['map'],
		'footer_map_url' => 'https://www.google.com/maps/search/?api=1&query=Mashhad+Poursina+Building+Arya+Hospital',
		'footer_copyright' => $t['footer']['copyright'], 'footer_disclaimer' => $t['footer']['disclaimer'],
		'footer_credit_label' => $t['footer']['credit'], 'footer_credit_url' => 'https://moghadam.pro',
	);

	foreach ( $t['journey'] as $index => $step ) {
		$i = $index + 1;
		$d[ "journey_step_{$i}_number" ] = $t['journeyStepNumbers'][ $index ];
		$d[ "journey_step_{$i}_eyebrow" ] = $step[0];
		$d[ "journey_step_{$i}_title" ] = $step[1];
		$d[ "journey_step_{$i}_image" ] = '';
	}
	foreach ( $t['pathCards'] as $index => $card ) {
		$i = $index + 1;
		$d[ "pathway_{$i}_title" ] = $card[0]; $d[ "pathway_{$i}_body" ] = $card[1];
		$d[ "pathway_{$i}_button_label" ] = $t['pathCtas'][ $index ];
		$d[ "pathway_{$i}_button_url" ] = dam_customizer_localized_url( $t['pathSlugs'][ $index ], $locale );
	}
	foreach ( $t['metrics'] as $index => $metric ) {
		$i = $index + 1; $d[ "impact_{$i}_title" ] = $metric[0]; $d[ "impact_{$i}_subtitle" ] = $metric[1];
	}
	foreach ( $t['appointmentCards'] as $index => $card ) {
		$i = $index + 1; $d[ "appointment_{$i}_enabled" ] = true;
		$d[ "appointment_{$i}_eyebrow" ] = $card[0]; $d[ "appointment_{$i}_title" ] = $card[1];
		$d[ "appointment_{$i}_body" ] = $card[2];
	}

	// Keep previously saved delimiter-based settings visible after upgrading.
	$legacy_journey = dam_customizer_text_to_rows( get_theme_mod( 'dam_journey_steps_' . $locale, '' ) );
	foreach ( array_slice( $legacy_journey, 0, 4 ) as $index => $step ) {
		$i = $index + 1;
		$d[ "journey_step_{$i}_number" ] = $step[0] ?? $d[ "journey_step_{$i}_number" ];
		$d[ "journey_step_{$i}_eyebrow" ] = $step[1] ?? $d[ "journey_step_{$i}_eyebrow" ];
		$d[ "journey_step_{$i}_title" ] = $step[2] ?? $d[ "journey_step_{$i}_title" ];
	}
	$legacy_pathways = dam_customizer_text_to_rows( get_theme_mod( 'dam_pathway_cards_' . $locale, '' ) );
	foreach ( array_slice( $legacy_pathways, 0, 3 ) as $index => $card ) {
		$i = $index + 1;
		$d[ "pathway_{$i}_title" ] = $card[0] ?? $d[ "pathway_{$i}_title" ];
		$d[ "pathway_{$i}_body" ] = $card[1] ?? $d[ "pathway_{$i}_body" ];
	}
	$legacy_appointments = dam_customizer_text_to_rows( get_theme_mod( 'dam_appointment_cards_' . $locale, '' ) );
	foreach ( array_slice( $legacy_appointments, 0, 4 ) as $index => $card ) {
		$i = $index + 1;
		$d[ "appointment_{$i}_eyebrow" ] = $card[0] ?? $d[ "appointment_{$i}_eyebrow" ];
		$d[ "appointment_{$i}_title" ] = $card[1] ?? $d[ "appointment_{$i}_title" ];
		$d[ "appointment_{$i}_body" ] = $card[2] ?? $d[ "appointment_{$i}_body" ];
	}
	foreach ( array( 'innovation', 'recognition' ) as $prefix ) {
		for ( $i = 1; $i <= 3; $i++ ) { $d[ "{$prefix}_post_{$i}" ] = 0; }
	}
	foreach ( array( 'clinical-care', 'innovations', 'research', 'about', 'blog' ) as $slug ) {
		$key = str_replace( '-', '_', $slug );
		$d[ "footer_explore_{$key}_label" ] = $t['footerExplore'][ $slug ] ?? $slug;
		$d[ "footer_explore_{$key}_url" ] = dam_customizer_localized_url( $slug, $locale );
	}
	foreach ( array( 'before' => 'before-surgery', 'after' => 'after-surgery', 'faq' => 'faq', 'rehab' => 'rehabilitation' ) as $key => $slug ) {
		$d[ "footer_resource_{$key}_label" ] = $t['footer'][ $key ];
		$d[ "footer_resource_{$key}_url" ] = dam_customizer_localized_url( $slug, $locale );
	}
	foreach ( array(
		'instagram' => array( 'Instagram', 'https://www.instagram.com/dr_ali_moradi_handsurgeon' ),
		'telegram' => array( 'Telegram', 'https://t.me/DrAliMoradi' ),
		'aparat' => array( 'Aparat', 'https://www.aparat.com/dr_ali_moradi_handsurgeon' ),
	) as $key => $values ) {
		$d[ "footer_social_{$key}_label" ] = $values[0]; $d[ "footer_social_{$key}_url" ] = $values[1];
	}
	return $d;
}

function dam_theme_mod( $key, $locale = null ) {
	$locale = $locale ? $locale : dam_current_locale();
	$defaults = dam_customizer_defaults( $locale );
	return get_theme_mod( 'dam_' . $key . '_' . $locale, $defaults[ $key ] ?? '' );
}

function dam_theme_mod_rows( $key, $locale = null ) {
	return dam_customizer_text_to_rows( dam_theme_mod( $key, $locale ) );
}

/** Resolve Customizer card sources consistently for Innovation/Recognition. */
function dam_customizer_section_posts( $prefix, $locale ) {
	$count  = max( 1, min( 3, (int) dam_theme_mod( $prefix . '_count', $locale ) ) );
	$source = dam_theme_mod( $prefix . '_source', $locale );

	if ( 'manual' === $source ) {
		$posts = array();
		for ( $i = 1; $i <= 3; $i++ ) {
			$post_id = (int) dam_theme_mod( $prefix . '_post_' . $i, $locale );
			if ( $post_id && function_exists( 'pll_get_post' ) ) {
				$post_id = (int) ( pll_get_post( $post_id, $locale ) ?: $post_id );
			}
			$post = $post_id ? get_post( $post_id ) : null;
			if ( $post && 'post' === $post->post_type && 'publish' === $post->post_status && ! isset( $posts[ $post->ID ] ) ) {
				$posts[ $post->ID ] = $post;
			}
		}
		return array_slice( array_values( $posts ), 0, $count );
	}

	$category_id = (int) dam_theme_mod( $prefix . '_category', $locale );
	if ( $category_id && function_exists( 'pll_get_term' ) ) {
		$category_id = (int) ( pll_get_term( $category_id, $locale ) ?: $category_id );
	}
	if ( ! $category_id ) {
		return array();
	}
	return get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => $count,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
			'cat'            => $category_id,
			'lang'           => $locale,
		)
	);
}

function dam_sanitize_checkbox( $value ) { return (bool) $value; }

function dam_sanitize_select( $value, $setting ) {
	$control = $setting->manager->get_control( $setting->id );
	return $control && array_key_exists( (string) $value, $control->choices ) ? $value : $setting->default;
}

function dam_customizer_heading( $wp_customize, $section, $id, $label, $priority ) {
	if ( ! class_exists( 'DAM_Customize_Heading_Control' ) ) {
		class DAM_Customize_Heading_Control extends WP_Customize_Control {
			public $type = 'dam_heading';
			public function render_content() {
				echo '<h2 style="margin:24px -12px 10px;padding:12px;background:#f0f0f1;border-inline-start:4px solid #2271b1;font-size:14px;">' . esc_html( $this->label ) . '</h2>';
			}
		}
	}
	$wp_customize->add_control( new DAM_Customize_Heading_Control( $wp_customize, $id, array( 'settings' => array(), 'label' => $label, 'section' => $section, 'priority' => $priority ) ) );
}

function dam_customizer_field( $wp_customize, $key, $section, $label, $type, $locale, $priority, $choices = array() ) {
	$defaults = dam_customizer_defaults( $locale ); $id = 'dam_' . $key . '_' . $locale;
	$sanitize = 'sanitize_text_field';
	if ( 'textarea' === $type ) { $sanitize = 'sanitize_textarea_field'; }
	if ( in_array( $type, array( 'url', 'image' ), true ) ) { $sanitize = 'esc_url_raw'; }
	if ( 'checkbox' === $type ) { $sanitize = 'dam_sanitize_checkbox'; }
	if ( 'select' === $type ) { $sanitize = 'dam_sanitize_select'; }
	$wp_customize->add_setting( $id, array( 'type' => 'theme_mod', 'default' => $defaults[ $key ] ?? '', 'sanitize_callback' => $sanitize, 'transport' => 'refresh' ) );
	$args = array( 'label' => $label, 'section' => $section, 'priority' => $priority, 'type' => $type );
	if ( $choices ) { $args['choices'] = $choices; }
	if ( 'image' === $type ) { $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $id, $args ) ); }
	else { $wp_customize->add_control( $id, $args ); }
}

function dam_customizer_add_fields( $wp_customize, $section, $locale, &$priority, $fields ) {
	foreach ( $fields as $field ) {
		dam_customizer_field( $wp_customize, $field[0], $section, $field[1], $field[2], $locale, $priority++, $field[3] ?? array() );
	}
}

function dam_customizer_category_choices() {
	$choices = array( 0 => __( 'Select a category', 'dr-ali-moradi' ) );
	$terms = get_terms( array( 'taxonomy' => 'category', 'hide_empty' => false, 'lang' => '' ) );
	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$lang = function_exists( 'pll_get_term_language' ) ? pll_get_term_language( $term->term_id, 'slug' ) : '';
			$choices[ $term->term_id ] = ( $lang ? '[' . strtoupper( $lang ) . '] ' : '' ) . $term->name;
		}
	}
	return $choices;
}

function dam_customizer_post_choices() {
	$choices = array( 0 => __( 'Select a post', 'dr-ali-moradi' ) );
	$posts = get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 200, 'orderby' => 'date', 'order' => 'DESC', 'lang' => '' ) );
	foreach ( $posts as $post ) {
		$lang = function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $post->ID, 'slug' ) : '';
		$choices[ $post->ID ] = ( $lang ? '[' . strtoupper( $lang ) . '] ' : '' ) . get_the_title( $post );
	}
	return $choices;
}

function dam_customize_register( $wp_customize ) {
	$wp_customize->add_panel( 'dam_homepage_content', array(
		'title' => __( 'Homepage Content — تغییر محتوای صفحه نخست', 'dr-ali-moradi' ),
		'description' => __( 'Choose a language. Its real homepage opens in the live preview.', 'dr-ali-moradi' ),
		'priority' => 50,
	) );
	$languages = array( 'en' => __( 'English homepage', 'dr-ali-moradi' ), 'fa' => 'صفحه نخست فارسی', 'ar' => 'الصفحة الرئيسية العربية' );
	$categories = dam_customizer_category_choices(); $posts = dam_customizer_post_choices();
	$numbers = array( 1 => '1', 2 => '2', 3 => '3' );
	$sources = array( 'dynamic' => __( 'Latest posts from category', 'dr-ali-moradi' ), 'manual' => __( 'Manually selected posts', 'dr-ali-moradi' ) );

	foreach ( $languages as $locale => $title ) {
		$section = 'dam_homepage_' . $locale; $p = 10;
		$wp_customize->add_section( $section, array( 'title' => $title, 'panel' => 'dam_homepage_content' ) );

		dam_customizer_heading( $wp_customize, $section, "dam_heading_hero_{$locale}", __( 'Hero', 'dr-ali-moradi' ), $p++ );
		dam_customizer_add_fields( $wp_customize, $section, $locale, $p, array(
			array( 'hero_background_image', __( 'Background image', 'dr-ali-moradi' ), 'image' ), array( 'hero_orbits_enabled', __( 'Show circular orbit lines', 'dr-ali-moradi' ), 'checkbox' ),
			array( 'hero_name_first', __( 'Name — first part', 'dr-ali-moradi' ), 'text' ), array( 'hero_name_last', __( 'Name — highlighted part', 'dr-ali-moradi' ), 'text' ),
			array( 'hero_credentials', __( 'Credentials — one per line: Bold|Text', 'dr-ali-moradi' ), 'textarea' ), array( 'hero_description', __( 'Description', 'dr-ali-moradi' ), 'textarea' ),
			array( 'hero_quote', __( 'Quote', 'dr-ali-moradi' ), 'textarea' ), array( 'hero_facets', __( 'Facets — one per line: Small|Bold', 'dr-ali-moradi' ), 'textarea' ),
			array( 'hero_credential_list', __( 'Checkmark list — one item per line', 'dr-ali-moradi' ), 'textarea' ),
		) );

		dam_customizer_heading( $wp_customize, $section, "dam_heading_journey_{$locale}", __( 'Connected Practice', 'dr-ali-moradi' ), $p++ );
		dam_customizer_add_fields( $wp_customize, $section, $locale, $p, array(
			array( 'journey_kicker', __( 'Section label', 'dr-ali-moradi' ), 'text' ), array( 'journey_intro', __( 'Intro text', 'dr-ali-moradi' ), 'textarea' ),
			array( 'journey_link_label', __( 'Journey link label', 'dr-ali-moradi' ), 'text' ), array( 'journey_link_url', __( 'Journey link URL', 'dr-ali-moradi' ), 'url' ),
		) );
		for ( $i = 1; $i <= 4; $i++ ) {
			dam_customizer_heading( $wp_customize, $section, "dam_heading_journey_{$locale}_{$i}", sprintf( __( 'Journey step %d', 'dr-ali-moradi' ), $i ), $p++ );
			dam_customizer_add_fields( $wp_customize, $section, $locale, $p, array(
				array( "journey_step_{$i}_number", __( 'Step number', 'dr-ali-moradi' ), 'text' ), array( "journey_step_{$i}_eyebrow", __( 'Small label', 'dr-ali-moradi' ), 'text' ),
				array( "journey_step_{$i}_title", __( 'Title', 'dr-ali-moradi' ), 'text' ), array( "journey_step_{$i}_image", __( 'Image', 'dr-ali-moradi' ), 'image' ),
			) );
		}

		dam_customizer_heading( $wp_customize, $section, "dam_heading_pathways_{$locale}", __( 'Pathways', 'dr-ali-moradi' ), $p++ );
		dam_customizer_add_fields( $wp_customize, $section, $locale, $p, array(
			array( 'pathways_kicker', __( 'Section label', 'dr-ali-moradi' ), 'text' ), array( 'pathways_title', __( 'Title', 'dr-ali-moradi' ), 'text' ), array( 'pathways_body', __( 'Subtitle', 'dr-ali-moradi' ), 'textarea' ),
		) );
		for ( $i = 1; $i <= 3; $i++ ) {
			dam_customizer_heading( $wp_customize, $section, "dam_heading_pathway_{$locale}_{$i}", sprintf( __( 'Pathway card %d', 'dr-ali-moradi' ), $i ), $p++ );
			dam_customizer_add_fields( $wp_customize, $section, $locale, $p, array(
				array( "pathway_{$i}_title", __( 'Title', 'dr-ali-moradi' ), 'text' ), array( "pathway_{$i}_body", __( 'Content', 'dr-ali-moradi' ), 'textarea' ),
				array( "pathway_{$i}_button_label", __( 'Button label', 'dr-ali-moradi' ), 'text' ), array( "pathway_{$i}_button_url", __( 'Button URL', 'dr-ali-moradi' ), 'url' ),
			) );
		}

		foreach ( array( 'innovation' => __( 'Innovation', 'dr-ali-moradi' ), 'recognition' => __( 'Recognition', 'dr-ali-moradi' ) ) as $prefix => $heading ) {
			dam_customizer_heading( $wp_customize, $section, "dam_heading_{$prefix}_{$locale}", $heading, $p++ );
			$section_fields = array(
				array( "{$prefix}_kicker", __( 'Section label', 'dr-ali-moradi' ), 'text' ), array( "{$prefix}_title", __( 'Title', 'dr-ali-moradi' ), 'text' ),
				array( "{$prefix}_subtitle", __( 'Subtitle', 'dr-ali-moradi' ), 'textarea' ), array( "{$prefix}_source", __( 'Card source', 'dr-ali-moradi' ), 'select', $sources ),
				array( "{$prefix}_category", __( 'Category', 'dr-ali-moradi' ), 'select', $categories ), array( "{$prefix}_count", __( 'Number of cards', 'dr-ali-moradi' ), 'select', $numbers ),
				array( "{$prefix}_columns", __( 'Grid columns', 'dr-ali-moradi' ), 'select', $numbers ),
			);
			if ( 'recognition' === $prefix ) { array_splice( $section_fields, 3, 0, array( array( 'recognition_link_label', __( 'Archive link label', 'dr-ali-moradi' ), 'text' ) ) ); }
			for ( $i = 1; $i <= 3; $i++ ) { $section_fields[] = array( "{$prefix}_post_{$i}", sprintf( __( 'Manual post %d', 'dr-ali-moradi' ), $i ), 'select', $posts ); }
			dam_customizer_add_fields( $wp_customize, $section, $locale, $p, $section_fields );
		}

		dam_customizer_heading( $wp_customize, $section, "dam_heading_impact_{$locale}", __( 'Impact', 'dr-ali-moradi' ), $p++ );
		dam_customizer_add_fields( $wp_customize, $section, $locale, $p, array(
			array( 'impact_kicker', __( 'Section label', 'dr-ali-moradi' ), 'text' ), array( 'impact_title', __( 'Main title', 'dr-ali-moradi' ), 'text' ), array( 'impact_subtitle', __( 'Main subtitle', 'dr-ali-moradi' ), 'textarea' ),
		) );
		for ( $i = 1; $i <= 4; $i++ ) {
			dam_customizer_heading( $wp_customize, $section, "dam_heading_impact_{$locale}_{$i}", sprintf( __( 'Impact item %d', 'dr-ali-moradi' ), $i ), $p++ );
			dam_customizer_add_fields( $wp_customize, $section, $locale, $p, array( array( "impact_{$i}_title", __( 'Title/value', 'dr-ali-moradi' ), 'text' ), array( "impact_{$i}_subtitle", __( 'Subtitle', 'dr-ali-moradi' ), 'text' ) ) );
		}

		dam_customizer_heading( $wp_customize, $section, "dam_heading_appointments_{$locale}", __( 'Appointments', 'dr-ali-moradi' ), $p++ );
		dam_customizer_add_fields( $wp_customize, $section, $locale, $p, array(
			array( 'appointments_kicker', __( 'Section label', 'dr-ali-moradi' ), 'text' ), array( 'appointment_title', __( 'Title', 'dr-ali-moradi' ), 'text' ),
			array( 'appointment_body', __( 'Subtitle', 'dr-ali-moradi' ), 'textarea' ), array( 'appointment_image', __( 'Section image', 'dr-ali-moradi' ), 'image' ),
			array( 'appointment_cta_label', __( 'Booking button label', 'dr-ali-moradi' ), 'text' ), array( 'appointment_urgent_days', __( 'Urgent days', 'dr-ali-moradi' ), 'text' ),
			array( 'appointment_urgent_hours', __( 'Urgent hours', 'dr-ali-moradi' ), 'text' ),
		) );
		for ( $i = 1; $i <= 4; $i++ ) {
			dam_customizer_heading( $wp_customize, $section, "dam_heading_appointment_{$locale}_{$i}", sprintf( __( 'Appointment option %d', 'dr-ali-moradi' ), $i ), $p++ );
			dam_customizer_add_fields( $wp_customize, $section, $locale, $p, array(
				array( "appointment_{$i}_enabled", __( 'Enabled', 'dr-ali-moradi' ), 'checkbox' ), array( "appointment_{$i}_eyebrow", __( 'Small label', 'dr-ali-moradi' ), 'text' ),
				array( "appointment_{$i}_title", __( 'Title', 'dr-ali-moradi' ), 'text' ), array( "appointment_{$i}_body", __( 'Description', 'dr-ali-moradi' ), 'textarea' ),
			) );
		}

		dam_customizer_heading( $wp_customize, $section, "dam_heading_about_{$locale}", __( 'About preview', 'dr-ali-moradi' ), $p++ );
		dam_customizer_add_fields( $wp_customize, $section, $locale, $p, array(
			array( 'about_kicker', __( 'Section label', 'dr-ali-moradi' ), 'text' ), array( 'about_body', __( 'Content', 'dr-ali-moradi' ), 'textarea' ),
			array( 'about_cta_label', __( 'Primary button label', 'dr-ali-moradi' ), 'text' ), array( 'about_cta_url', __( 'Primary button URL', 'dr-ali-moradi' ), 'url' ),
			array( 'about_research_label', __( 'Research link label', 'dr-ali-moradi' ), 'text' ), array( 'about_research_url', __( 'Research link URL', 'dr-ali-moradi' ), 'url' ),
			array( 'about_image', __( 'Image', 'dr-ali-moradi' ), 'image' ),
		) );

		dam_customizer_heading( $wp_customize, $section, "dam_heading_footer_{$locale}", __( 'Footer', 'dr-ali-moradi' ), $p++ );
		$footer_fields = array(
			array( 'footer_logo', __( 'Footer logo', 'dr-ali-moradi' ), 'image' ), array( 'footer_bio', __( 'Biography', 'dr-ali-moradi' ), 'textarea' ),
			array( 'footer_booking_label', __( 'Booking label', 'dr-ali-moradi' ), 'text' ), array( 'footer_booking_url', __( 'Booking URL', 'dr-ali-moradi' ), 'url' ),
			array( 'footer_explore_title', __( 'Explore column title', 'dr-ali-moradi' ), 'text' ), array( 'footer_resources_title', __( 'Resources column title', 'dr-ali-moradi' ), 'text' ),
			array( 'footer_contact_title', __( 'Contact column title', 'dr-ali-moradi' ), 'text' ), array( 'footer_social_title', __( 'Social column title', 'dr-ali-moradi' ), 'text' ),
			array( 'footer_email', __( 'Email', 'dr-ali-moradi' ), 'text' ), array( 'footer_phone', __( 'Phone', 'dr-ali-moradi' ), 'text' ),
			array( 'footer_office_address', __( 'Office address', 'dr-ali-moradi' ), 'textarea' ), array( 'footer_clinic_address', __( 'Clinic address', 'dr-ali-moradi' ), 'textarea' ),
			array( 'footer_map_label', __( 'Map label', 'dr-ali-moradi' ), 'text' ), array( 'footer_map_url', __( 'Map URL', 'dr-ali-moradi' ), 'url' ),
			array( 'footer_copyright', __( 'Copyright', 'dr-ali-moradi' ), 'text' ), array( 'footer_disclaimer', __( 'Medical disclaimer', 'dr-ali-moradi' ), 'textarea' ),
			array( 'footer_credit_label', __( 'Credit label', 'dr-ali-moradi' ), 'text' ), array( 'footer_credit_url', __( 'Credit URL', 'dr-ali-moradi' ), 'url' ),
		);
		dam_customizer_add_fields( $wp_customize, $section, $locale, $p, $footer_fields );
		foreach ( array( 'clinical_care' => 'Clinical care', 'innovations' => 'Innovation', 'research' => 'Research', 'about' => 'About', 'blog' => 'Blog' ) as $key => $label ) {
			dam_customizer_heading( $wp_customize, $section, "dam_heading_footer_explore_{$locale}_{$key}", sprintf( __( 'Footer link: %s', 'dr-ali-moradi' ), $label ), $p++ );
			dam_customizer_add_fields( $wp_customize, $section, $locale, $p, array( array( "footer_explore_{$key}_label", __( 'Label', 'dr-ali-moradi' ), 'text' ), array( "footer_explore_{$key}_url", __( 'URL', 'dr-ali-moradi' ), 'url' ) ) );
		}
		foreach ( array( 'before' => 'Before surgery', 'after' => 'After surgery', 'faq' => 'FAQ', 'rehab' => 'Rehabilitation' ) as $key => $label ) {
			dam_customizer_heading( $wp_customize, $section, "dam_heading_footer_resource_{$locale}_{$key}", sprintf( __( 'Footer resource: %s', 'dr-ali-moradi' ), $label ), $p++ );
			dam_customizer_add_fields( $wp_customize, $section, $locale, $p, array( array( "footer_resource_{$key}_label", __( 'Label', 'dr-ali-moradi' ), 'text' ), array( "footer_resource_{$key}_url", __( 'URL', 'dr-ali-moradi' ), 'url' ) ) );
		}
		foreach ( array( 'instagram' => 'Instagram', 'telegram' => 'Telegram', 'aparat' => 'Aparat' ) as $key => $label ) {
			dam_customizer_heading( $wp_customize, $section, "dam_heading_footer_social_{$locale}_{$key}", sprintf( __( 'Social link: %s', 'dr-ali-moradi' ), $label ), $p++ );
			dam_customizer_add_fields( $wp_customize, $section, $locale, $p, array( array( "footer_social_{$key}_label", __( 'Label', 'dr-ali-moradi' ), 'text' ), array( "footer_social_{$key}_url", __( 'URL', 'dr-ali-moradi' ), 'url' ) ) );
		}
	}
}
add_action( 'customize_register', 'dam_customize_register' );

function dam_customizer_controls_scripts() {
	$urls = array();
	foreach ( array( 'en', 'fa', 'ar' ) as $locale ) {
		$urls[ $locale ] = function_exists( 'pll_home_url' ) ? pll_home_url( $locale ) : home_url( 'en' === $locale ? '/' : '/' . $locale . '/' );
	}
	$script = '(function(api,urls){api.bind("ready",function(){Object.keys(urls).forEach(function(locale){var section=api.section("dam_homepage_"+locale);if(section){section.expanded.bind(function(expanded){if(expanded){api.previewer.previewUrl.set(urls[locale]);}});}});});})(wp.customize,' . wp_json_encode( $urls ) . ');';
	wp_add_inline_script( 'customize-controls', $script );
}
add_action( 'customize_controls_enqueue_scripts', 'dam_customizer_controls_scripts' );
