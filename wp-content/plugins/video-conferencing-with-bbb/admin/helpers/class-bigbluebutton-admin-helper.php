<?php
/**
 * The admin helper class.
 *
 * @link       https://blindsidenetworks.com
 * @since      3.0.0
 *
 * @package    Bigbluebutton
 * @subpackage Bigbluebutton/public/helpers
 */

/**
 * The admin helper class.
 *
 * A list of helper functions that is used across BigBlueButton admin classes..
 *
 * @package    Bigbluebutton
 * @subpackage Bigbluebutton/public/helpers
 * @author     Blindside Networks <contact@blindsidenetworks.com>
 */
class Bigbluebutton_Admin_Helper {

	/**
	 * Generate random alphanumeric string.
	 *
	 * @since   3.0.0
	 *
	 * @param   Integer $length         Length of random string.
	 * @return  String  $default_code   The resulting random string.
	 */
	public static function generate_random_code( $length = 6 ) {
		$default_code = bin2hex( random_bytes( $length / 2 ) );
		return $default_code;
	}

	public static function check_posts() {
		$args = array(
			'post_type'   => 'bbb-room',
			'post_status' => 'publish',
			'numberposts' => 3,
			'fields'      => 'ids',
			'orderby'     => 'none',
		);

		$posts = get_posts( $args );

		if ( ! Bigbluebutton_Loader::is_bbb_pro_active() ) {
			if ( $posts && count( $posts ) >= ( 1 + 1 ) ) {
				return true;
			}
		}

		return false;
	}
}
