<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locale  = dam_current_locale();
$t       = dam_site_copy( $locale );
$nobat   = dam_appointment_url();
$portrait_fallback = dam_media_url( 'doctor', DAM_THEME_URI . '/assets/img/appointments/doctor.jpg' );
$portrait          = dam_theme_mod( 'appointment_image', $locale ) ?: $portrait_fallback;

$kicker      = dam_theme_mod( 'appointments_kicker', $locale );
$title       = dam_theme_mod( 'appointment_title', $locale );
$body        = dam_theme_mod( 'appointment_body', $locale );
$cards       = array();
for ( $i = 1; $i <= 4; $i++ ) {
	if ( dam_theme_mod( 'appointment_' . $i . '_enabled', $locale ) ) {
		$cards[ $i ] = array(
			'eyebrow' => dam_theme_mod( 'appointment_' . $i . '_eyebrow', $locale ),
			'title'   => dam_theme_mod( 'appointment_' . $i . '_title', $locale ),
			'body'    => dam_theme_mod( 'appointment_' . $i . '_body', $locale ),
		);
	}
}
$cta_label   = dam_theme_mod( 'appointment_cta_label', $locale );
$urgent_days = dam_theme_mod( 'appointment_urgent_days', $locale );
$urgent_hrs  = dam_theme_mod( 'appointment_urgent_hours', $locale );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'appointments section-space section-shell' ) ); ?>>
	<div class="section-heading reveal">
		<p class="section-index"><?php echo esc_html( $kicker ); ?></p>
		<p><?php echo esc_html( $title ); ?>. <?php echo esc_html( $body ); ?></p>
	</div>
	<div class="appointment-layout">
		<div class="appointment-accordion reveal">
			<?php $first_open = true; foreach ( $cards as $slot => $card ) :
				$is_open = $first_open;
				$first_open = false;
				?>
				<div class="appointment-item<?php echo $is_open ? ' is-open' : ''; ?>">
					<button type="button" class="appointment-trigger" data-appointment-trigger aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>">
						<span><small><?php echo esc_html( $card['eyebrow'] ); ?></small><?php echo esc_html( $card['title'] ); ?></span>
						<?php echo dam_icon( 'chevron-down', 20, 1.5 ); ?>
					</button>
					<div class="appointment-panel">
						<p><?php echo esc_html( $card['body'] ); ?></p>
						<?php if ( 1 === $slot ) : ?>
							<a class="button" href="<?php echo esc_url( $nobat ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $cta_label ); ?><?php echo dam_icon( 'external-link', 16 ); ?></a>
							<small><?php echo esc_html( $t['opensBooking'] ); ?></small>
						<?php elseif ( 2 === $slot ) : ?>
							<span class="pending-label"><?php echo esc_html( $t['onlineNote'] ); ?></span>
						<?php elseif ( 3 === $slot ) : ?>
							<div class="schedule"><?php echo dam_icon( 'calendar-days', 18 ); ?><span><?php echo esc_html( $urgent_days ); ?></span></div>
							<div class="schedule"><?php echo dam_icon( 'clock', 18 ); ?><span><?php echo esc_html( $urgent_hrs ); ?></span></div>
							<span class="pending-label"><?php echo esc_html( $t['urgentInstruction'] ); ?></span>
						<?php elseif ( 4 === $slot ) : ?>
							<span class="pending-label"><?php echo esc_html( $t['screeningNote'] ); ?></span>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="appointment-portrait reveal"><img class="fill-img" src="<?php echo esc_url( $portrait ); ?>" alt=""></div>
	</div>
	<p class="medical-note"><?php echo dam_icon( 'shield-check', 18 ); ?><?php echo esc_html( $t['medicalNote'] ); ?></p>
</section>
