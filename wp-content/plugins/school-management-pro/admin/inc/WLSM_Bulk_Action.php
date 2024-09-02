<?php
defined( 'ABSPATH' ) || die();

class WLSM_Bulk_Action {
	public static function bulk_action() {
		$action = isset( $_POST['bulk_action'] ) ? sanitize_text_field( $_POST['bulk_action'] ) : '';
		$entity = isset( $_POST['entity'] ) ? sanitize_text_field( $_POST['entity'] ) : '';

		if ( ! wp_verify_nonce( $_POST[ 'nonce' ], 'bulk-action-' . $entity ) ) {
			die();
		}

		if ( empty( $action ) ) {
			wp_send_json_error( esc_html__( 'Please select an option.', 'school-management' ) );
		}

		$method_name = $action . '_' . $entity;

		// Call action_entity() method.
		if ( ! method_exists( 'WLSM_Bulk_Action', $method_name ) ) {
			wp_send_json_error( esc_html__( 'Invalid selection.', 'school-management' ) );
		}

		self::$method_name();
	}

	public static function delete_invoices() {
		$current_user = WLSM_M_Role::can( 'delete_invoices' );

		if ( ! $current_user ) {
			die();
		}

		WLSM_Helper::check_demo();

		global $wpdb;

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		$invoice_ids = ( isset( $_POST['bulk_values'] ) && is_array( $_POST['bulk_values'] ) ) ? array_map( 'absint', $_POST['bulk_values'] ) : array();

		if ( empty( $invoice_ids ) ) {
			wp_send_json_error( esc_html__( 'Please select atleast one invoice.', 'school-management' ) );
		}

		try {
			$wpdb->query( 'BEGIN;' );

			ob_start();

			foreach ( $invoice_ids as $invoice_id ) {
				// Checks if invoice exists.
				$invoice = WLSM_M_Staff_Accountant::fetch_invoice($school_id, $session_id, $invoice_id);

				if ( ! $invoice ) {
					throw new Exception( esc_html__( 'Invoice not found.', 'school-management' ) );
				}

				$success = $wpdb->delete( WLSM_INVOICES, array( 'ID' => $invoice_id ) );

				WLSM_Helper::check_buffer();

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}
			}

			$wpdb->query( 'COMMIT;' );

			$message = esc_html__( 'Invoices deleted successfully.', 'school-management' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function delete_students() {
		$current_user = WLSM_M_Role::can( 'delete_students' );

		if ( ! $current_user ) {
			die();
		}

		WLSM_Helper::check_demo();

		global $wpdb;

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		$student_ids = ( isset( $_POST['bulk_values'] ) && is_array( $_POST['bulk_values'] ) ) ? array_map( 'absint', $_POST['bulk_values'] ) : array();

		if ( empty( $student_ids ) ) {
			wp_send_json_error( esc_html__( 'Please select atleast one student.', 'school-management' ) );
		}

		try {
			$wpdb->query( 'BEGIN;' );

			ob_start();

			foreach ( $student_ids as $student_id ) {
				// Checks if student exists.
				$student = WLSM_M_Staff_General::get_student( $school_id, $session_id, $student_id );

				if ( ! $student ) {
					throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
				}

				$success = $wpdb->delete( WLSM_STUDENT_RECORDS, array( 'ID' => $student_id ) );

				WLSM_Helper::check_buffer();

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}
			}

			$wpdb->query( 'COMMIT;' );

			$message = esc_html__( 'Students deleted successfully.', 'school-management' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}
}
