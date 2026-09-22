<?php
/**
 * Appearance -> Customize support for the front page's own copy (hero,
 * connected-practice journey, pathways, appointments, about-preview).
 *
 * Every value here is per-language: the site is trilingual (Polylang), and
 * unlike Theme Options' contact/impact fields (one value shared across all
 * languages) this is real narrative copy that already reads differently
 * per language today, so a single shared value would silently overwrite
 * the other two languages' text the first time anyone edited it.
 *
 * Three independent top-level panels -- "Homepage Content (English)",
 * "(فارسی)", "(العربية)" -- rather than one panel whose target language
 * depends on which front-end URL the Customizer happened to be opened
 * from. That URL-detection approach was tried first and dropped: it needs
 * Polylang to correctly resolve the *previewed* language for the sidebar's
 * own request (not just the live-preview iframe), which is an easy thing
 * for an operator to get wrong silently (edit Persian, actually save to
 * English). Three fixed, always-visible panels remove that failure mode
 * entirely at the cost of the live-preview iframe only ever showing the
 * language of the page Customize was opened from -- editing Persian or
 * Arabic copy here won't visually preview live; check the live site after
 * Publish instead. Settings are stored as theme_mods suffixed with the
 * language the FIELD belongs to ("dam_hero_description_fa"), fixed at
 * registration time per panel, not derived from the current request.
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

/**
 * One independent top-level panel per language, each holding the same 5
 * sections -- rather than one panel that edits "whichever language you're
 * currently previewing". Three fixed, always-visible panels are simpler
 * and more reliable for a non-technical operator than a single panel
 * whose target language depends on which front-end URL the Customizer
 * happened to be opened from: nothing to get wrong, and no dependency on
 * Polylang correctly detecting the previewed language for the sidebar's
 * own request. The trade-off, accepted deliberately: the live-preview
 * iframe only ever shows the language of the page Customize was opened
 * from, so editing Persian or Arabic copy here won't visually preview
 * live -- check the result on the live site after Publish instead.
 */
function dam_customize_register( $wp_customize ) {
	$panel_titles = array(
		'en' => __( 'Homepage Content (English)', 'dr-ali-moradi' ),
		'fa' => __( 'Homepage Content (فارسی)', 'dr-ali-moradi' ),
		'ar' => __( 'Homepage Content (العربية)', 'dr-ali-moradi' ),
	);

	foreach ( $panel_titles as $locale => $panel_title ) {
		$panel_id = 'dam_homepage_' . $locale;

		$wp_customize->add_panel(
			$panel_id,
			array(
				'title'    => $panel_title,
				'priority' => 50,
			)
		);

		$hero_section         = 'dam_hero_' . $locale;
		$journey_section      = 'dam_journey_' . $locale;
		$pathways_section     = 'dam_pathways_' . $locale;
		$appointments_section = 'dam_appointments_' . $locale;
		$about_section        = 'dam_about_preview_' . $locale;

		$wp_customize->add_section( $hero_section, array( 'title' => __( 'Hero', 'dr-ali-moradi' ), 'panel' => $panel_id ) );
		dam_customizer_field( $wp_customize, 'hero_name_first', $hero_section, __( 'Name -- first part (e.g. "Dr.")', 'dr-ali-moradi' ), 'text', $locale );
		dam_customizer_field( $wp_customize, 'hero_name_last', $hero_section, __( 'Name -- highlighted part', 'dr-ali-moradi' ), 'text', $locale );
		dam_customizer_field( $wp_customize, 'hero_credentials', $hero_section, __( 'Credential bullets -- one per line, "Bold part|Rest of line"', 'dr-ali-moradi' ), 'textarea', $locale );
		dam_customizer_field( $wp_customize, 'hero_description', $hero_section, __( 'Description paragraph', 'dr-ali-moradi' ), 'textarea', $locale );
		dam_customizer_field( $wp_customize, 'hero_quote', $hero_section, __( 'Pull-quote', 'dr-ali-moradi' ), 'textarea', $locale );
		dam_customizer_field( $wp_customize, 'hero_facets', $hero_section, __( 'Facet strip (4 items) -- one per line, "Small label|Bold label"', 'dr-ali-moradi' ), 'textarea', $locale );
		dam_customizer_field( $wp_customize, 'hero_credential_list', $hero_section, __( 'Checkmark credential list -- one per line', 'dr-ali-moradi' ), 'textarea', $locale );

		$wp_customize->add_section( $journey_section, array( 'title' => __( 'Connected Practice', 'dr-ali-moradi' ), 'panel' => $panel_id ) );
		dam_customizer_field( $wp_customize, 'journey_kicker', $journey_section, __( 'Kicker', 'dr-ali-moradi' ), 'text', $locale );
		dam_customizer_field( $wp_customize, 'journey_intro', $journey_section, __( 'Intro text', 'dr-ali-moradi' ), 'textarea', $locale );
		dam_customizer_field( $wp_customize, 'journey_link_label', $journey_section, __( '"Explore the journey" link label', 'dr-ali-moradi' ), 'text', $locale );
		dam_customizer_field( $wp_customize, 'journey_steps', $journey_section, __( 'Steps (4 cards) -- one per line, "Small label|Title"', 'dr-ali-moradi' ), 'textarea', $locale );

		$wp_customize->add_section( $pathways_section, array( 'title' => __( 'Pathways', 'dr-ali-moradi' ), 'panel' => $panel_id ) );
		dam_customizer_field( $wp_customize, 'pathways_kicker', $pathways_section, __( 'Kicker', 'dr-ali-moradi' ), 'text', $locale );
		dam_customizer_field( $wp_customize, 'pathways_title', $pathways_section, __( 'Title', 'dr-ali-moradi' ), 'text', $locale );
		dam_customizer_field( $wp_customize, 'pathways_body', $pathways_section, __( 'Body text', 'dr-ali-moradi' ), 'textarea', $locale );
		dam_customizer_field( $wp_customize, 'pathway_cards', $pathways_section, __( 'Cards -- one per line, "Title|Text|Button label" (keep exactly 3 lines, in Clinic/Innovation/Research order)', 'dr-ali-moradi' ), 'textarea', $locale );

		$wp_customize->add_section( $appointments_section, array( 'title' => __( 'Appointments', 'dr-ali-moradi' ), 'panel' => $panel_id ) );
		dam_customizer_field( $wp_customize, 'appointments_kicker', $appointments_section, __( 'Kicker', 'dr-ali-moradi' ), 'text', $locale );
		dam_customizer_field( $wp_customize, 'appointment_title', $appointments_section, __( 'Title', 'dr-ali-moradi' ), 'text', $locale );
		dam_customizer_field( $wp_customize, 'appointment_body', $appointments_section, __( 'Body text', 'dr-ali-moradi' ), 'textarea', $locale );
		dam_customizer_field( $wp_customize, 'appointment_cards', $appointments_section, __( 'Accordion cards (4 items) -- one per line, "Small label|Title|Description" (keep exactly 4 lines, in Planned/Online/Urgent/Screening order)', 'dr-ali-moradi' ), 'textarea', $locale );
		dam_customizer_field( $wp_customize, 'appointment_cta_label', $appointments_section, __( '"Continue to Nobat.ir" button label', 'dr-ali-moradi' ), 'text', $locale );
		dam_customizer_field( $wp_customize, 'appointment_urgent_days', $appointments_section, __( 'Urgent-triage days', 'dr-ali-moradi' ), 'text', $locale );
		dam_customizer_field( $wp_customize, 'appointment_urgent_hours', $appointments_section, __( 'Urgent-triage hours', 'dr-ali-moradi' ), 'text', $locale );

		$wp_customize->add_section( $about_section, array( 'title' => __( 'About Preview', 'dr-ali-moradi' ), 'panel' => $panel_id ) );
		dam_customizer_field( $wp_customize, 'about_kicker', $about_section, __( 'Kicker', 'dr-ali-moradi' ), 'text', $locale );
		dam_customizer_field( $wp_customize, 'about_body', $about_section, __( 'Body text', 'dr-ali-moradi' ), 'textarea', $locale );
		dam_customizer_field( $wp_customize, 'about_cta_label', $about_section, __( '"Meet the doctor" button label', 'dr-ali-moradi' ), 'text', $locale );
		dam_customizer_field( $wp_customize, 'about_research_label', $about_section, __( '"Research profile" link label', 'dr-ali-moradi' ), 'text', $locale );
	}
}
add_action( 'customize_register', 'dam_customize_register' );
