<?php
/**
 * Appearance -> Customize support for the front page's own copy (hero,
 * connected-practice journey, pathways, appointments, about-preview).
 *
 * Every value here is per-language: the site is trilingual (Polylang), and
 * unlike Theme Options' contact/impact fields (one value shared across all
 * languages) this is real narrative copy that already reads differently
 * per language today, so a single shared value would silently overwrite
 * the other two languages' text the first time anyone edited it. Settings
 * are therefore stored as theme_mods suffixed with the language currently
 * being PREVIEWED ("dam_hero_description_fa", not "dam_hero_description"),
 * resolved via dam_current_locale() -- which correctly reports the
 * previewed language because WP passes the previewed URL to customize.php
 * (`?url=https://dralimoradi.com/fa/`) and Polylang reads that to set the
 * current language for the whole Customizer request, sidebar included, not
 * only the live-preview iframe. In practice: to edit the Persian or Arabic
 * homepage, browse to that language's front page first, then open
 * Customize from the admin toolbar from there (rather than opening
 * Customize from wp-admin, which previews English).
 *
 * Repeating rows (credential bullets, journey steps, pathway cards,
 * appointment cards) reuse the same "one row per line, `field|field`"
 * textarea convention Theme Options already uses for social links and
 * impact stats, rather than a JS repeater control -- consistent with the
 * rest of the theme's ACF-free, no-build-step approach to editable lists.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * array of rows (each row itself an array of column values) <-> the
 * "col|col|col" one-row-per-line textarea format an operator edits.
 */
function dam_customizer_rows_to_text( $rows ) {
	$lines = array();
	foreach ( (array) $rows as $row ) {
		$cols    = array_map(
			function ( $value ) {
				return str_replace( '|', '-', (string) $value );
			},
			(array) $row
		);
		$lines[] = implode( '|', $cols );
	}
	return implode( "\n", $lines );
}

function dam_customizer_text_to_rows( $text ) {
	$rows = array();
	foreach ( preg_split( '/\r\n|\r|\n/', trim( (string) $text ) ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		$rows[] = array_map( 'trim', explode( '|', $line ) );
	}
	return $rows;
}

/**
 * The full set of Customizer-backed values for one locale, defaulting to
 * that locale's current static copy (dam_site_copy()) so an operator who
 * has never touched the Customizer still sees -- and can start editing
 * from -- the real current homepage text instead of a blank field.
 */
function dam_customizer_defaults( $locale ) {
	$t = dam_site_copy( $locale );
	return array(
		'hero_name_first'      => $t['heroName'][0],
		'hero_name_last'       => $t['heroName'][1],
		'hero_credentials'     => dam_customizer_rows_to_text( $t['heroCredentials'] ),
		'hero_description'     => $t['heroDescription'],
		'hero_quote'           => $t['heroQuote'],
		'hero_facets'          => dam_customizer_rows_to_text( $t['facets'] ),
		'hero_credential_list' => implode( "\n", $t['credentials'] ),

		'journey_kicker'     => $t['connectedIndex'],
		'journey_intro'      => $t['storyBody'],
		'journey_link_label' => $t['journeyLink'],
		'journey_steps'      => dam_customizer_rows_to_text( $t['journey'] ),

		'pathways_kicker' => $t['pathwaysIndex'],
		'pathways_title'  => $t['pathsTitle'],
		'pathways_body'   => $t['pathsBody'],
		'pathway_cards'   => dam_customizer_rows_to_text(
			array_map(
				function ( $card, $cta ) {
					return array( $card[0], $card[1], $cta );
				},
				$t['pathCards'],
				$t['pathCtas']
			)
		),

		'appointments_kicker'      => $t['appointmentsIndex'],
		'appointment_title'        => $t['appointmentTitle'],
		'appointment_body'         => $t['appointmentBody'],
		'appointment_cards'        => dam_customizer_rows_to_text( $t['appointmentCards'] ),
		'appointment_cta_label'    => $t['continueNobat'],
		'appointment_urgent_days'  => $t['urgentDays'],
		'appointment_urgent_hours' => $t['urgentHours'],

		'about_kicker'         => $t['aboutIndex'],
		'about_body'           => $t['aboutBody'],
		'about_cta_label'      => $t['meetDoctor'],
		'about_research_label' => $t['researchProfile'],
	);
}

/**
 * Reads one Customizer-backed value for the given (or current) locale.
 */
function dam_theme_mod( $key, $locale = null ) {
	$locale   = $locale ? $locale : dam_current_locale();
	$defaults = dam_customizer_defaults( $locale );
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	return get_theme_mod( 'dam_' . $key . '_' . $locale, $default );
}

/** Same, parsed as "col|col" rows for the repeating fields. */
function dam_theme_mod_rows( $key, $locale = null ) {
	return dam_customizer_text_to_rows( dam_theme_mod( $key, $locale ) );
}

/**
 * Registers one theme_mod setting (language-suffixed) plus its control.
 */
function dam_customizer_field( $wp_customize, $key, $section, $label, $type, $locale ) {
	$defaults = dam_customizer_defaults( $locale );
	$mod_key  = 'dam_' . $key . '_' . $locale;

	$wp_customize->add_setting(
		$mod_key,
		array(
			'type'              => 'theme_mod',
			'default'           => isset( $defaults[ $key ] ) ? $defaults[ $key ] : '',
			'sanitize_callback' => 'textarea' === $type ? 'sanitize_textarea_field' : 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		$mod_key,
		array(
			'label'   => $label,
			'section' => $section,
			'type'    => $type,
		)
	);
}

function dam_customize_register( $wp_customize ) {
	$locale = dam_current_locale();

	$wp_customize->add_panel(
		'dam_homepage',
		array(
			'title'       => __( 'Homepage Content', 'dr-ali-moradi' ),
			'description' => __( 'Editing the language of the page you opened Customize from. To edit Persian or Arabic, browse to /fa/ or /ar/ on the live site first, then open Customize from there.', 'dr-ali-moradi' ),
			'priority'    => 50,
		)
	);

	// Hero.
	$wp_customize->add_section( 'dam_hero', array( 'title' => __( 'Hero', 'dr-ali-moradi' ), 'panel' => 'dam_homepage' ) );
	dam_customizer_field( $wp_customize, 'hero_name_first', 'dam_hero', __( 'Name -- first part (e.g. "Dr.")', 'dr-ali-moradi' ), 'text', $locale );
	dam_customizer_field( $wp_customize, 'hero_name_last', 'dam_hero', __( 'Name -- highlighted part', 'dr-ali-moradi' ), 'text', $locale );
	dam_customizer_field( $wp_customize, 'hero_credentials', 'dam_hero', __( 'Credential bullets -- one per line, "Bold part|Rest of line"', 'dr-ali-moradi' ), 'textarea', $locale );
	dam_customizer_field( $wp_customize, 'hero_description', 'dam_hero', __( 'Description paragraph', 'dr-ali-moradi' ), 'textarea', $locale );
	dam_customizer_field( $wp_customize, 'hero_quote', 'dam_hero', __( 'Pull-quote', 'dr-ali-moradi' ), 'textarea', $locale );
	dam_customizer_field( $wp_customize, 'hero_facets', 'dam_hero', __( 'Facet strip (4 items) -- one per line, "Small label|Bold label"', 'dr-ali-moradi' ), 'textarea', $locale );
	dam_customizer_field( $wp_customize, 'hero_credential_list', 'dam_hero', __( 'Checkmark credential list -- one per line', 'dr-ali-moradi' ), 'textarea', $locale );

	// Connected practice / journey.
	$wp_customize->add_section( 'dam_journey', array( 'title' => __( 'Connected Practice', 'dr-ali-moradi' ), 'panel' => 'dam_homepage' ) );
	dam_customizer_field( $wp_customize, 'journey_kicker', 'dam_journey', __( 'Kicker', 'dr-ali-moradi' ), 'text', $locale );
	dam_customizer_field( $wp_customize, 'journey_intro', 'dam_journey', __( 'Intro text', 'dr-ali-moradi' ), 'textarea', $locale );
	dam_customizer_field( $wp_customize, 'journey_link_label', 'dam_journey', __( '"Explore the journey" link label', 'dr-ali-moradi' ), 'text', $locale );
	dam_customizer_field( $wp_customize, 'journey_steps', 'dam_journey', __( 'Steps (4 cards) -- one per line, "Small label|Title"', 'dr-ali-moradi' ), 'textarea', $locale );

	// Pathways.
	$wp_customize->add_section( 'dam_pathways', array( 'title' => __( 'Pathways', 'dr-ali-moradi' ), 'panel' => 'dam_homepage' ) );
	dam_customizer_field( $wp_customize, 'pathways_kicker', 'dam_pathways', __( 'Kicker', 'dr-ali-moradi' ), 'text', $locale );
	dam_customizer_field( $wp_customize, 'pathways_title', 'dam_pathways', __( 'Title', 'dr-ali-moradi' ), 'text', $locale );
	dam_customizer_field( $wp_customize, 'pathways_body', 'dam_pathways', __( 'Body text', 'dr-ali-moradi' ), 'textarea', $locale );
	dam_customizer_field( $wp_customize, 'pathway_cards', 'dam_pathways', __( 'Cards -- one per line, "Title|Text|Button label" (keep exactly 3 lines, in Clinic/Innovation/Research order)', 'dr-ali-moradi' ), 'textarea', $locale );

	// Appointments.
	$wp_customize->add_section( 'dam_appointments', array( 'title' => __( 'Appointments', 'dr-ali-moradi' ), 'panel' => 'dam_homepage' ) );
	dam_customizer_field( $wp_customize, 'appointments_kicker', 'dam_appointments', __( 'Kicker', 'dr-ali-moradi' ), 'text', $locale );
	dam_customizer_field( $wp_customize, 'appointment_title', 'dam_appointments', __( 'Title', 'dr-ali-moradi' ), 'text', $locale );
	dam_customizer_field( $wp_customize, 'appointment_body', 'dam_appointments', __( 'Body text', 'dr-ali-moradi' ), 'textarea', $locale );
	dam_customizer_field( $wp_customize, 'appointment_cards', 'dam_appointments', __( 'Accordion cards (4 items) -- one per line, "Small label|Title|Description" (keep exactly 4 lines, in Planned/Online/Urgent/Screening order)', 'dr-ali-moradi' ), 'textarea', $locale );
	dam_customizer_field( $wp_customize, 'appointment_cta_label', 'dam_appointments', __( '"Continue to Nobat.ir" button label', 'dr-ali-moradi' ), 'text', $locale );
	dam_customizer_field( $wp_customize, 'appointment_urgent_days', 'dam_appointments', __( 'Urgent-triage days', 'dr-ali-moradi' ), 'text', $locale );
	dam_customizer_field( $wp_customize, 'appointment_urgent_hours', 'dam_appointments', __( 'Urgent-triage hours', 'dr-ali-moradi' ), 'text', $locale );

	// About preview.
	$wp_customize->add_section( 'dam_about_preview', array( 'title' => __( 'About Preview', 'dr-ali-moradi' ), 'panel' => 'dam_homepage' ) );
	dam_customizer_field( $wp_customize, 'about_kicker', 'dam_about_preview', __( 'Kicker', 'dr-ali-moradi' ), 'text', $locale );
	dam_customizer_field( $wp_customize, 'about_body', 'dam_about_preview', __( 'Body text', 'dr-ali-moradi' ), 'textarea', $locale );
	dam_customizer_field( $wp_customize, 'about_cta_label', 'dam_about_preview', __( '"Meet the doctor" button label', 'dr-ali-moradi' ), 'text', $locale );
	dam_customizer_field( $wp_customize, 'about_research_label', 'dam_about_preview', __( '"Research profile" link label', 'dr-ali-moradi' ), 'text', $locale );
}
add_action( 'customize_register', 'dam_customize_register' );
