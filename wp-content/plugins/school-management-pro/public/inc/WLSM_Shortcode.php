<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_School.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Session.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Config.php';

class WLSM_Shortcode {
	public static function account( $attr ) {
		self::enqueue_assets();
		ob_start();
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/route.php';
	}

	public static function fees( $attr ) {
		self::enqueue_assets();
		wp_enqueue_script( 'razorpay-checkout', '//checkout.razorpay.com/v1/checkout.js', array(), NULL, true );
		wp_enqueue_script( 'paystack-checkout', '//js.paystack.co/v1/inline.js', array(), NULL, true );
		wp_enqueue_script( 'stripe-checkout', '//checkout.stripe.com/checkout.js', array(), NULL, true );
		ob_start();
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/forms/fees.php';
	}

	public static function inquiry( $attr ) {
		self::enqueue_assets();
		ob_start();
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/forms/inquiry.php';
	}

	public static function registration( $attr ) {
		self::enqueue_assets();
		ob_start();
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/forms/registration.php';
	}

	public static function school_register( $attr ) {
		self::enqueue_assets();
		ob_start();
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/forms/school_register.php';
	}

	public static function staff_registration( $attr ) {
		self::enqueue_assets();
		ob_start();
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/forms/registration_staff.php';
	}

	public static function exam_time_table( $attr ) {
		self::enqueue_assets();
		ob_start();
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/forms/exam-time-table.php';
	}

	public static function exam_admit_card( $attr ) {
		self::enqueue_assets();
		ob_start();
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/forms/exam-admit-card.php';
	}

	public static function exam_result( $attr ) {
		self::enqueue_assets();
		ob_start();
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/forms/exam-result.php';
	}

	public static function certificate( $attr ) {
		self::enqueue_assets();
		ob_start();
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/forms/certificate.php';
	}

	public static function lesson( $attr ) {
		self::enqueue_assets();
		ob_start();
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/lesson/route.php';
	}

	public static function invoice_history( $attr ) {
		self::enqueue_assets();
		ob_start();
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/forms/invoice_history.php';
	}

	public static function zoom_redirect( $attr ) {
		self::enqueue_assets();
		ob_start();
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/forms/zoom_redirect.php';
	}

	public static function noticeboard( $attr ) {
		self::enqueue_assets();
		ob_start();
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/noticeboard/index.php';
	}

	public static function enqueue_assets() {

		wp_enqueue_style( 'jquery-confirm', WLSM_PLUGIN_URL . 'assets/css/jquery-confirm.min.css' );
		wp_enqueue_style( 'toastr', WLSM_PLUGIN_URL . 'assets/css/toastr.min.css' );
		wp_enqueue_style( 'zebra-datepicker', WLSM_PLUGIN_URL . 'assets/css/zebra_datepicker.min.css' );
		wp_enqueue_style( 'sumoselect', WLSM_PLUGIN_URL . 'assets/js/select/sumoselect.min.css' );

		wp_enqueue_style( 'wlsm-print-preview', WLSM_PLUGIN_URL . 'assets/css/print/wlsm-preview.css', array(), '5.1', 'all' );
		wp_enqueue_style( 'wlsm', WLSM_PLUGIN_URL . 'assets/css/wlsm.css', array(), '5.1', 'all' );
		wp_enqueue_style( 'wlsm-dashboard', WLSM_PLUGIN_URL . 'assets/css/wlsm-dashboard.css', array(), '5.1', 'all' );

		wp_enqueue_script( 'jquery-confirm', WLSM_PLUGIN_URL . 'assets/js/jquery-confirm.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'toastr', WLSM_PLUGIN_URL . 'assets/js/toastr.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'zebra-datepicker', WLSM_PLUGIN_URL . 'assets/js/zebra_datepicker.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'sumoselects', WLSM_PLUGIN_URL . 'assets/js/select/jquery.sumoselect.min.js', array( 'jquery' ), true, true );

		wp_enqueue_script( 'wlsm-public', WLSM_PLUGIN_URL . 'assets/js/wlsm.js', array( 'jquery', 'jquery-form' ), '5.1', true );
		wp_localize_script( 'wlsm-public', 'wlsmdateformat', WLSM_Config::date_format() );
		wp_localize_script( 'wlsm-public', 'wlsmajaxurl', admin_url( 'admin-ajax.php' ) );
		wp_localize_script( 'wlsm-public', 'wlsmadminurl', admin_url() );

		wp_enqueue_script('razorpay-checkout', '//checkout.razorpay.com/v1/checkout.js', array(), NULL, true);
		wp_enqueue_script('paystack-checkout', '//js.paystack.co/v1/inline.js', array(), NULL, true);
		wp_enqueue_script('stripe-checkout', '//checkout.stripe.com/checkout.js', array(), NULL, true);
	}
}
