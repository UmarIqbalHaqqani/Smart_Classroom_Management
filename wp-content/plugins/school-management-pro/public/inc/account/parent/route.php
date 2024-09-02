<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Parent.php';

$action = '';
if ( isset( $_GET['action'] ) && ! empty( $_GET['action'] ) ) {
	$action = sanitize_text_field( $_GET['action'] );
}

$student_id = '';
if ( isset( $_GET['student_id'] ) && ! empty( $_GET['student_id'] ) ) {
	$student_id = absint( $_GET['student_id'] );
}
?>
<div class="wlsm-container wlsm-container-parent">
<?php

if ( $student_id && ! in_array( $student_id, $unique_student_ids ) ) {
	die;
}

if ( 'fee-invoices' === $action ) {
	wp_enqueue_script( 'razorpay-checkout', '//checkout.razorpay.com/v1/checkout.js', array(), NULL, true );
	wp_enqueue_script( 'paystack-checkout', '//js.paystack.co/v1/inline.js', array(), NULL, true );
	wp_enqueue_script( 'stripe-checkout', '//checkout.stripe.com/checkout.js', array(), NULL, true );
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/parent/fee_invoices.php';
} elseif ( 'payment-history' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/parent/payment_history.php';
} elseif ( 'attendance' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/parent/attendance.php';
} elseif ( 'noticeboard' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/parent/noticeboard.php';
} elseif ( 'class-time-table' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/parent/class_time_table.php';
} elseif ( 'exam-results' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/parent/exam_results.php';
} elseif ( 'settings' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/parent/settings.php';
} else {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/parent/dashboard.php';
}
?>
</div>
