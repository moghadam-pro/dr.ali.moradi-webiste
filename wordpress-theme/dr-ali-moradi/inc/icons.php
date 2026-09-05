<?php
/**
 * Small inline-SVG icon set, drawn in the same stroke style (24x24,
 * stroke="currentColor", stroke-width 1.8-2, round caps/joins) as the
 * lucide-react icons used by the reference design, so the redesign can
 * match its look without pulling in an icon library/build step.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dam_icon( $name, $size = 18, $stroke = 2 ) {
	$paths = array(
		'check'          => '<polyline points="20 6 9 17 4 12"/>',
		'chevron-down'   => '<polyline points="6 9 12 15 18 9"/>',
		'chevron-right'  => '<polyline points="9 18 15 12 9 6"/>',
		'chevron-left'   => '<polyline points="15 18 9 12 15 6"/>',
		'arrow-right'    => '<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>',
		'external-link'  => '<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>',
		'quote'          => '<path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V21z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-3.75 4v4z"/>',
		'shield-check'   => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
		'calendar-days'  => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
		'clock'          => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
		'map-pin'        => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
		'mail'           => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/>',
		'phone'          => '<path d="M13.8 19.4a2 2 0 0 1-2.2.5 16.7 16.7 0 0 1-6.5-4.8 16.4 16.4 0 0 1-4.8-6.5 2 2 0 0 1 .5-2.2l1.8-1.8a2 2 0 0 1 2.9.1l1.7 2.1a2 2 0 0 1 .1 2.4l-1 1.4a12.7 12.7 0 0 0 4.6 4.6l1.4-1a2 2 0 0 1 2.4.1l2.1 1.7a2 2 0 0 1 .1 2.9z"/>',
		'camera'         => '<path d="M15 7 13.3 4.4A2 2 0 0 0 11.6 3.5H8.4a2 2 0 0 0-1.7.9L5 7H3a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1z"/><circle cx="12" cy="13" r="3.5"/>',
		'send'           => '<path d="m22 2-7 20-4-9-9-4z"/><path d="M22 2 11 13"/>',
		'play-circle'    => '<circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16"/>',
		'languages'      => '<path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/>',
		'menu'           => '<line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/>',
		'x'              => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
		'hand'           => '<path d="M18 11V6a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v3"/><path d="M14 10V4a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v6"/><path d="M10 10.5V6a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v10"/><path d="M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-3.6a2 2 0 0 1 2.83-2.82L7 15"/>',
		'brain-circuit'  => '<path d="M12 4.5a2.5 2.5 0 0 0-4.96-.46 2.5 2.5 0 0 0-1.98 3 2.5 2.5 0 0 0-1.32 4.24 3 3 0 0 0 .34 5.58 2.5 2.5 0 0 0 2.96 3.08A2.5 2.5 0 0 0 12 19.5"/><path d="M12 4.5a2.5 2.5 0 0 1 4.96-.46 2.5 2.5 0 0 1 1.98 3 2.5 2.5 0 0 1 1.32 4.24 3 3 0 0 1-.34 5.58 2.5 2.5 0 0 1-2.96 3.08A2.5 2.5 0 0 1 12 19.5"/><circle cx="12" cy="12" r="1"/>',
		'lightbulb'      => '<path d="M15 14c.2-1 .7-1.7 1.5-2.5a5 5 0 1 0-8.5-4.5 5 5 0 0 0 1.5 7c.7.8 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/>',
		'microscope'     => '<path d="M6 18h8"/><path d="M3 22h18"/><path d="M14 22a7 7 0 1 0 0-14h-1"/><path d="M9 14h2"/><path d="M9 12a2 2 0 0 1-2-2V6h4v4a2 2 0 0 1-2 2Z"/><path d="M12 6V3a1 1 0 0 0-1-1H9.5"/>',
		'stethoscope'    => '<path d="M4.8 2.3A.3.3 0 1 0 4.3 2a.3.3 0 0 0 .5.3Z"/><path d="M8 2v2.4a3.2 3.2 0 0 1-3.2 3.2h-.5A3.2 3.2 0 0 1 1 4.4V2"/><path d="M8 2h1.2"/><path d="M2.6 2h1.2"/><path d="M4.5 7.5v3a6.5 6.5 0 0 0 13 0v-1"/><circle cx="20" cy="10" r="2"/>',
		'sparkles'       => '<path d="M12 3v4"/><path d="M12 17v4"/><path d="M3 12h4"/><path d="M17 12h4"/><path d="m18.4 5.6-2.8 2.8"/><path d="m8.4 15.6-2.8 2.8"/><path d="m5.6 5.6 2.8 2.8"/><path d="m15.6 15.6 2.8 2.8"/>',
		'file-text'      => '<path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="14" y2="17"/>',
	);

	if ( empty( $paths[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg xmlns="http://www.w3.org/2000/svg" width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="%2$s" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
		(int) $size,
		esc_attr( $stroke ),
		$paths[ $name ]
	);
}
