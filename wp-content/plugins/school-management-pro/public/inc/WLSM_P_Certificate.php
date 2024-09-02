<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_General.php';

class WLSM_P_Certificate {
	public static function get_certificate() {
		if ( ! wp_verify_nonce( $_POST['get-certificate'], 'get-certificate' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$school_id      = isset( $_POST['school_id'] ) ? absint( $_POST['school_id'] ) : 0;
			$certificate_id = isset( $_POST['certificate_id'] ) ? absint( $_POST['certificate_id'] ) : 0;

			$enrollment_number = isset( $_POST['enrollment_number'] ) ? sanitize_text_field( $_POST['enrollment_number'] ) : '';

			// Start validation.
			$errors = array();

			if ( empty( $school_id ) ) {
				$errors['school_id'] = esc_html__( 'Please select a school.', 'school-management' );

			} else {
				// Checks if school exists.
				$school = WLSM_M_School::get_active_school( $school_id );

				if ( ! $school ) {
					$errors['school_id'] = esc_html__( 'Please select a school.', 'school-management' );
				}
			}

			if ( count( $errors ) > 0 ) {
				wp_send_json_error( $errors );
			}

			if ( empty( $certificate_id ) ) {
				$errors['certificate_id'] = esc_html__( 'Please select certificate.', 'school-management' );
			}

			if ( empty( $enrollment_number ) ) {
				$errors['enrollment_number'] = esc_html__( 'Please provide enrollment number.', 'school-management' );
			}

			if ( count( $errors ) > 0 ) {
				wp_send_json_error( $errors );
			}

			// Checks if certificate exists for enrollment number.
			$certificate_student = $wpdb->get_row(
				$wpdb->prepare( 'SELECT cfsr.ID, cfsr.certificate_id, cfsr.student_record_id as student_id, cfsr.certificate_number, cfsr.date_issued, cf.label, ss.ID as session_id, ss.label as session_label, ss.start_date as session_start_date, ss.end_date as session_end_date FROM ' . WLSM_CERTIFICATE_STUDENT . ' as cfsr 
					JOIN ' . WLSM_CERTIFICATES . ' as cf ON cf.ID = cfsr.certificate_id 
					JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = cfsr.student_record_id 
					JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
					JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
					JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
					JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
					WHERE cs.school_id = %d AND cf.ID = %d AND sr.enrollment_number = %s', $school_id, $certificate_id, $enrollment_number
				)
			);

			if ( ! $certificate_student ) {
				throw new Exception( esc_html__( 'Certificate not found.', 'school-management' ) );
			}

			$session_id = $certificate_student->session_id;
			$student_id = $certificate_student->student_id;

			require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/certificate_student.php';

			$student = WLSM_M_Staff_General::fetch_student( $school_id, $session_id, $student_id );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = esc_html__( 'An unexpected error occurred!', 'school-management' );
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		if ( count( $errors ) < 1 ) {
			try {
				ob_start();

				$from_front = true;
				$from_ajax  = true;

				require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/certificate.php';

				$html = ob_get_clean();

				wp_send_json_success( array( 'html' => $html ) );

			} catch ( Exception $exception ) {
				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					$response = esc_html__( 'An unexpected error occurred!', 'school-management' );
				} else {
					$response = $exception->getMessage();
				}
				wp_send_json_error( $response );
			}
		}
		wp_send_json_error( $errors );
	}
}
