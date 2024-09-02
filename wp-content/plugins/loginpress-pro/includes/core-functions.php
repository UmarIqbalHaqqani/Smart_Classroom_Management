<?php
/**
 * Helper function for translation.
 *
 * @package LoginPress Pro
 */

if ( ! function_exists( 'loginpress__' ) ) {
	/**
	 * Wrapper for __() gettext function.
	 *
	 * @param string $string     Translatable text string.
	 * @param string $textdomain Text domain, default: loginpress.
	 *
	 * @return string The translation.
	 */
	function loginpress__( $string, $textdomain = 'loginpress' ) {
		return __( $string, $textdomain );
	}
}

if ( ! function_exists( 'loginpress_e' ) ) {
	/**
	 * Wrapper for _e() gettext function.
	 *
	 * @param string $string     Translatable text string.
	 * @param string $textdomain Text domain, default: loginpress.
	 *
	 * @return void
	 */
	function loginpress_e( $string, $textdomain = 'loginpress' ) {
		echo __( $string, $textdomain );
	}
}
