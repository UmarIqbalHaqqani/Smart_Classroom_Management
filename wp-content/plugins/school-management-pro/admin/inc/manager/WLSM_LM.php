<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/WLSM_LC.php';

final class WLSM_LM {
	private $api_url         = 'https://weblizar.com/members/softsale/api';
	private $key             = null;
	public $error_message    = null;
	public $license_key      = '';
	private static $instance = null;

	private function __construct() {
		$license_key = trim( get_option( 'wlsm-key' ) );
	}

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function validate( $key ) {
		$this->license_key = $key;

		return $this->is_valid();
	}

	private function key_exists() {
		return ! ! strlen( $this->license_key );
	}

	public function is_valid() {
		if ( get_option( 'wlsm-valid' ) ) {
			return true;
		}
		if ( $this->key_exists() ) {
			$checker = new WLSM_LC( $this->license_key, $this->api_url, md5( $this->license_key ) );
			if ( ! $checker->checkLicenseKey() ) {
				$this->error_message = $checker->getMessage();

				return false;
			} else {
				$this->error_message   = null;
				$activation_cache      = trim( get_option( 'wlsm-cache' ) );
				$prev_activation_cache = $activation_cache;
				$checker               = new WLSM_LC( $this->license_key, $this->api_url, md5( $this->license_key ) );
				$ret                   = empty( $activation_cache ) ? $checker->activate( $activation_cache ) : $checker->checkActivation( $activation_cache );
				if ( ! $ret ) {
					$this->error_message = $checker->getMessage();

					return false;
				}
				update_option( 'wlsm-key', $this->license_key );
				update_option( 'wlsm-valid', true );
				if ( $prev_activation_cache != $activation_cache ) {
					update_option( 'wlsm-cache', $activation_cache );
				}

				return true;
			}
		}
		$this->error_message = esc_html__( 'Please provide a license key.', 'school-management' );

		return false;
	}
}
