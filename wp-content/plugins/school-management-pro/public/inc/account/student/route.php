<?php
defined( 'ABSPATH' ) || die();

$action = '';
if ( isset( $_GET['action'] ) && ! empty( $_GET['action'] ) ) {
	$action = sanitize_text_field( $_GET['action'] );
}
?>
<div class="wlsm-container wlsm-container-student">
<?php
if ( 'fee-invoices' === $action ) {
	wp_enqueue_script( 'razorpay-checkout', '//checkout.razorpay.com/v1/checkout.js', array(), NULL, true );
	wp_enqueue_script( 'paystack-checkout', '//js.paystack.co/v1/inline.js', array(), NULL, true );
	wp_enqueue_script( 'stripe-checkout', '//checkout.stripe.com/checkout.js', array(), NULL, true );
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/fee_invoices.php';
} elseif ( 'fee-structure' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/fee_structure.php';
} elseif ( 'payment-history' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/payment_history.php';
} elseif ( 'study-materials' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/study_materials.php';
} elseif ( 'homework' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/homework.php';
} elseif ( 'noticeboard' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/noticeboard.php';
} elseif ( 'events' === $action ) {
	$id = '';
	if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
		$id = absint( $_GET['id'] );
	}

	if ( ! empty( $id ) ) {
		require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/event.php';
	} else {
		require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/events.php';
	}
} elseif ( 'class-time-table' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/class_time_table.php';
} elseif ( 'live-classes' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/live_classes.php';
} elseif ( 'books-issued' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/books_issued.php';
} elseif ( 'exams-time-table' === $action ) {
	$id = '';
	if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
		$id = absint( $_GET['id'] );
	}

	if ( ! empty( $id ) ) {
		require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/exam_time_table.php';
	} else {
		require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/exams_time_table.php';
	}
} elseif ( 'exam-admit-card' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/exam_admit_card.php';
} elseif ( 'exam-results' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/exam_results.php';
} elseif ( 'attendance' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/attendance.php';
} elseif ( 'certificates' === $action ) {
	$id = '';
	if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
		$id = absint( $_GET['id'] );
	}

	if ( ! empty( $id ) ) {
		require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/certificate.php';
	} else {
		require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/certificates.php';
	}
} elseif ( 'settings' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/settings.php';
} elseif ( 'leave-request' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/leave_request.php';
} elseif ('submit-homework' === $action) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/submit_homework.php';
} elseif ('submit-homework?id=' === $action) {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/submit_homework.php';
} else {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/dashboard.php';
}
?>
</div>
