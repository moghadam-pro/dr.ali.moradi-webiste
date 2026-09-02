<?php
/**
 * "Theme Options" admin screen — the single place for global, scalar data
 * that must update everywhere at once (header, footer, homepage): contact
 * details, the external appointment link, and the evidence/impact numbers.
 *
 * Built with WordPress's own Settings API. No plugin dependency.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DAM_OPTIONS_GROUP', 'dam_theme_options' );

function dam_theme_options_menu() {
	add_theme_page(
		__( 'Theme Options', 'dr-ali-moradi' ),
		__( 'Theme Options', 'dr-ali-moradi' ),
		'manage_options',
		'dam-theme-options',
		'dam_render_theme_options_page'
	);
}
add_action( 'admin_menu', 'dam_theme_options_menu' );

/**
 * Fields: option name => [label, type, description].
 * type "textarea-lines" stores newline-separated "label|value" pairs and
 * is used for the two repeating settings (impact stats, social links)
 * since there is no ACF repeater; still a plain form field, no code
 * required from the operator.
 */
function dam_theme_option_fields() {
	return array(
		'dam_clinic_phone'          => array( __( 'Clinic phone', 'dr-ali-moradi' ), 'text' ),
		'dam_clinic_whatsapp'       => array( __( 'WhatsApp number', 'dr-ali-moradi' ), 'text' ),
		'dam_clinic_address_office' => array( __( 'Private office address', 'dr-ali-moradi' ), 'textarea' ),
		'dam_clinic_address_hospital' => array( __( 'Hospital address', 'dr-ali-moradi' ), 'textarea' ),
		'dam_appointment_url'       => array( __( 'Appointment booking URL', 'dr-ali-moradi' ), 'url', 'https://nobat.ir/9705' ),
		'dam_social_links'          => array( __( 'Social links (one per line, "Label|URL")', 'dr-ali-moradi' ), 'textarea-lines' ),
		'dam_impact_stats'          => array( __( 'Impact numbers (one per line, "Label|Value")', 'dr-ali-moradi' ), 'textarea-lines' ),
		'dam_impact_as_of'          => array( __( 'Impact numbers as of (date)', 'dr-ali-moradi' ), 'text' ),
	);
}

function dam_register_theme_options() {
	foreach ( dam_theme_option_fields() as $key => $field ) {
		register_setting(
			DAM_OPTIONS_GROUP,
			$key,
			array(
				'type'              => 'string',
				'sanitize_callback' => 'url' === $field[1] ? 'esc_url_raw' : 'sanitize_textarea_field',
				'default'           => isset( $field[2] ) ? $field[2] : '',
			)
		);
	}
}
add_action( 'admin_init', 'dam_register_theme_options' );

function dam_render_theme_options_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Theme Options', 'dr-ali-moradi' ); ?></h1>
		<p><?php esc_html_e( 'These values are used across the site (header, footer, homepage) by the theme\'s contact-info, appointment, and impact-stats blocks. Changing a value here updates every place it appears.', 'dr-ali-moradi' ); ?></p>
		<form action="options.php" method="post">
			<?php settings_fields( DAM_OPTIONS_GROUP ); ?>
			<table class="form-table" role="presentation">
				<?php foreach ( dam_theme_option_fields() as $key => $field ) : ?>
					<tr>
						<th scope="row"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field[0] ); ?></label></th>
						<td>
							<?php if ( in_array( $field[1], array( 'textarea', 'textarea-lines' ), true ) ) : ?>
								<textarea class="large-text" rows="4" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>"><?php echo esc_textarea( get_option( $key, isset( $field[2] ) ? $field[2] : '' ) ); ?></textarea>
							<?php else : ?>
								<input class="regular-text" type="<?php echo 'url' === $field[1] ? 'url' : 'text'; ?>" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( get_option( $key, isset( $field[2] ) ? $field[2] : '' ) ); ?>" />
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Parses a "Label|Value" per-line option into an array of [label, value]
 * pairs. Used by the impact-stats and contact-info blocks.
 */
function dam_parse_lines_option( $option_key ) {
	$raw   = get_option( $option_key, '' );
	$lines = array_filter( array_map( 'trim', explode( "\n", (string) $raw ) ) );
	$pairs = array();

	foreach ( $lines as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( count( $parts ) === 2 ) {
			$pairs[] = array( 'label' => $parts[0], 'value' => $parts[1] );
		}
	}

	return $pairs;
}
