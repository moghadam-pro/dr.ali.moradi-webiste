<?php
/**
 * Persian/Arabic admin translation for this theme's own strings.
 *
 * load_theme_textdomain() (called in functions.php) reports success on
 * this install -- but with Polylang active, __() calls for this
 * theme's text domain still returned the untranslated English string,
 * confirmed live by logging load_theme_textdomain()'s return value,
 * determine_locale(), and an actual __() call side by side: locale
 * resolved correctly to fa_IR, the .mo file was confirmed present on
 * disk at the exact path load_theme_textdomain() was given, and it
 * still reported "loaded" -- yet the translation table stayed empty.
 * Polylang overrides WordPress's own load_textdomain() (via the core
 * override_load_textdomain filter) to substitute its own per-language
 * file resolution; for a theme text domain it doesn't otherwise manage,
 * that override appears to swallow the load without ever importing
 * this theme's own .mo file.
 *
 * Fixed by parsing the .mo file directly with WordPress's own MO class
 * and translating through the gettext filters, which every __() call
 * passes through regardless of how (or whether) the text domain was
 * "loaded" -- independent of load_theme_textdomain()/Polylang's
 * override entirely.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dam_i18n_translations() {
	static $by_locale = array();

	$locale = determine_locale();
	if ( array_key_exists( $locale, $by_locale ) ) {
		return $by_locale[ $locale ];
	}

	$mo_file = DAM_THEME_DIR . '/languages/dr-ali-moradi-' . $locale . '.mo';
	if ( ! file_exists( $mo_file ) ) {
		$by_locale[ $locale ] = null;
		return null;
	}

	$mo = new MO();
	$mo->import_from_file( $mo_file );
	$by_locale[ $locale ] = $mo;

	return $mo;
}

function dam_i18n_gettext( $translation, $text, $domain ) {
	if ( 'dr-ali-moradi' !== $domain ) {
		return $translation;
	}
	$mo = dam_i18n_translations();
	return $mo ? $mo->translate( $text ) : $translation;
}
add_filter( 'gettext', 'dam_i18n_gettext', 10, 3 );

function dam_i18n_gettext_with_context( $translation, $text, $context, $domain ) {
	if ( 'dr-ali-moradi' !== $domain ) {
		return $translation;
	}
	$mo = dam_i18n_translations();
	return $mo ? $mo->translate( $text, $context ) : $translation;
}
add_filter( 'gettext_with_context', 'dam_i18n_gettext_with_context', 10, 4 );
