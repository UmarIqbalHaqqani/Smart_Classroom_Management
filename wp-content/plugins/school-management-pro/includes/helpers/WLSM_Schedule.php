<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Notify.php';

class WLSM_Schedule {
	public static function notify_for_student_admission( $school_id, $session_id, $student_id, $password ) {
		WLSM_Notify::notify_for_student_admission(
			array(
				'school_id'  => $school_id,
				'session_id' => $session_id,
				'student_id' => $student_id,
				'password'   => $password,
			)
		);
	}

	public static function notify_for_invoice_generated( $school_id, $session_id, $invoice_id ) {
		WLSM_Notify::notify_for_invoice_generated(
			array(
				'school_id'  => $school_id,
				'session_id' => $session_id,
				'invoice_id' => $invoice_id,
			)
		);
	}

	public static function notify_for_invoice_due_date() {
		WLSM_Notify::notify_for_invoice_due_date( );
	}
	
	public static function notify_for_online_fee_submission( $school_id, $session_id, $payment_id ) {
		WLSM_Notify::notify_for_online_fee_submission(
			array(
				'school_id'  => $school_id,
				'session_id' => $session_id,
				'payment_id' => $payment_id,
			)
		);
	}

	public static function notify_for_offline_fee_submission( $school_id, $session_id, $payment_id ) {
		WLSM_Notify::notify_for_offline_fee_submission(
			array(
				'school_id'  => $school_id,
				'session_id' => $session_id,
				'payment_id' => $payment_id,
			)
		);
	}

	public static function notify_for_student_admission_to_parent( $school_id, $session_id, $student_id, $password ) {
		WLSM_Notify::notify_for_student_admission_to_parent(
			array(
				'school_id'  => $school_id,
				'session_id' => $session_id,
				'student_id' => $student_id,
				'password'   => $password,
			)
		);
	}

	public static function notify_for_invoice_generated_to_parent( $school_id, $session_id, $invoice_id ) {
		WLSM_Notify::notify_for_invoice_generated_to_parent(
			array(
				'school_id'  => $school_id,
				'session_id' => $session_id,
				'invoice_id' => $invoice_id,
			)
		);
	}

	public static function notify_for_online_fee_submission_to_parent( $school_id, $session_id, $payment_id ) {
		WLSM_Notify::notify_for_online_fee_submission_to_parent(
			array(
				'school_id'  => $school_id,
				'session_id' => $session_id,
				'payment_id' => $payment_id,
			)
		);
	}

	public static function notify_for_offline_fee_submission_to_parent( $school_id, $session_id, $payment_id ) {
		WLSM_Notify::notify_for_offline_fee_submission_to_parent(
			array(
				'school_id'  => $school_id,
				'session_id' => $session_id,
				'payment_id' => $payment_id,
			)
		);
	}

	public static function notify_for_absent_student( $school_id, $session_id, $student_id, $attendance_date ) {
		WLSM_Notify::notify_for_absent_student(
			array(
				'school_id'       => $school_id,
				'session_id'      => $session_id,
				'student_id'      => $student_id,
				'attendance_date' => $attendance_date,
			)
		);
	}

	public static function notify_for_custom_message( $school_id, $session_id, $student_id, $email, $sms ) {
		WLSM_Notify::notify_for_custom_message(
			array(
				'school_id'  => $school_id,
				'session_id' => $session_id,
				'student_id' => $student_id,
				'email'      => $email,
				'sms'        => $sms,
			)
		);
	}

	public static function notify_for_homework_message( $school_id, $student_id, $sms ) {
		WLSM_Notify::notify_for_homework_message(
			array(
				'school_id'  => $school_id,
				'student_id' => $student_id,
				'sms'        => $sms,
			)
		);
	}

	public static function notify_for_inquiry_received_to_inquisitor( $school_id, $inquiry_id ) {
		WLSM_Notify::notify_for_inquiry_received_to_inquisitor(
			array(
				'school_id'  => $school_id,
				'inquiry_id' => $inquiry_id,
			)
		);
	}

	public static function notify_for_inquiry_received_to_admin( $school_id, $inquiry_id ) {
		WLSM_Notify::notify_for_inquiry_received_to_admin(
			array(
				'school_id'  => $school_id,
				'inquiry_id' => $inquiry_id,
			)
		);
	}

	public static function notify_for_student_registration_to_student( $school_id, $session_id, $student_id, $password ) {
		WLSM_Notify::notify_for_student_registration_to_student(
			array(
				'school_id'  => $school_id,
				'session_id' => $session_id,
				'student_id' => $student_id,
				'password'   => $password,
			)
		);
	}

	public static function notify_for_student_registration_to_admin( $school_id, $session_id, $student_id, $password ) {
		WLSM_Notify::notify_for_student_registration_to_admin(
			array(
				'school_id'  => $school_id,
				'session_id' => $session_id,
				'student_id' => $student_id,
				'password'   => $password,
			)
		);
	}
}
