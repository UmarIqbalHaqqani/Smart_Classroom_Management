<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Examination.php';

class WLSM_P_Exam {
	public static function get_exam_time_table() {
		if ( ! wp_verify_nonce( $_POST['get-exam-time-table'], 'get-exam-time-table' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$school_id = isset( $_POST['school_id'] ) ? absint( $_POST['school_id'] ) : 0;
			$exam_id   = isset( $_POST['exam_id'] ) ? absint( $_POST['exam_id'] ) : 0;

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

			if ( empty( $exam_id ) ) {
				$errors['exam_id'] = esc_html__( 'Please select an exam.', 'school-management' );
			} else {
				// Checks if exam exists in the school.
				$exam = WLSM_M_Staff_Examination::get_school_published_exam_time_table( $school_id, $exam_id );
				if ( ! $exam ) {
					$errors['exam_id'] = esc_html__( 'Exam not found.', 'school-management' );
				}
			}

			if ( count( $errors ) > 0 ) {
				wp_send_json_error( $errors );
			}

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

				$html = require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/forms/partials/exam-time-table.php';

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

	public static function get_exam_admit_card() {
		if ( ! wp_verify_nonce( $_POST['get-exam-admit-card'], 'get-exam-admit-card' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$school_id = isset( $_POST['school_id'] ) ? absint( $_POST['school_id'] ) : 0;
			$exam_id   = isset( $_POST['exam_id'] ) ? absint( $_POST['exam_id'] ) : 0;

			$exam_roll_number = isset( $_POST['exam_roll_number'] ) ? sanitize_text_field( $_POST['exam_roll_number'] ) : '';

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

			if ( empty( $exam_id ) ) {
				$errors['exam_id'] = esc_html__( 'Please select an exam.', 'school-management' );
			} else {
				// Checks if exam exists in the school.
				$exam = WLSM_M_Staff_Examination::get_school_published_exam_admit_card( $school_id, $exam_id );
				if ( ! $exam ) {
					$errors['exam_id'] = esc_html__( 'Exam not found.', 'school-management' );
				}

				$exam_classes = WLSM_M_Staff_Examination::fetch_exam_classes_label( $school_id, $exam_id );
				$exam_papers  = WLSM_M_Staff_Examination::fetch_exam_papers( $school_id, $exam_id );
			}

			if ( empty( $exam_roll_number ) ) {
				$errors['exam_roll_number'] = esc_html__( 'Please provide exam roll number.', 'school-management' );
			}

			if ( count( $errors ) > 0 ) {
				wp_send_json_error( $errors );
			}

			// Checks if admit card exists for exam roll number.
			$admit_card = WLSM_M_Staff_Examination::get_admit_card_by_exam_roll_number( $school_id, $exam_id, $exam_roll_number );

			if ( ! $admit_card ) {
				throw new Exception( esc_html__( 'Admit card not found.', 'school-management' ) );
			}

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

				require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/exam_admit_card.php';

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

	public static function get_exam_result() {
		if ( ! wp_verify_nonce( $_POST['get-exam-result'], 'get-exam-result' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$school_id = isset( $_POST['school_id'] ) ? absint( $_POST['school_id'] ) : 0;
			$session_id = isset( $_POST['session_id'] ) ? absint( $_POST['session_id'] ) : 0;
			$exam_id   = isset( $_POST['exam_id'] ) ? absint( $_POST['exam_id'] ) : 0;

			$exam_roll_number = isset( $_POST['exam_roll_number'] ) ? sanitize_text_field( $_POST['exam_roll_number'] ) : '';

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

			if ( empty( $exam_id ) ) {
				$errors['exam_id'] = esc_html__( 'Please select an exam.', 'school-management' );
			}

			if ( empty( $exam_roll_number ) ) {
				$errors['exam_roll_number'] = esc_html__( 'Please provide exam roll number.', 'school-management' );
			}

			if ( count( $errors ) > 0 ) {
				wp_send_json_error( $errors );
			}

			// Checks if admit card exists for exam roll number.
			$admit_card = $wpdb->get_row( $wpdb->prepare( 'SELECT ac.ID, ac.exam_id, ac.roll_number, ac.student_record_id as student_id, sr.name, sr.phone, sr.enrollment_number, sr.photo_id, sr.admission_number, sr.father_name, sr.email, c.label as class_label, se.label as section_label, ss.label as session_label FROM ' . WLSM_ADMIT_CARDS . ' as ac
				JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ac.exam_id
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
				WHERE ex.school_id = %d AND ac.exam_id = %d AND ac.roll_number = %s AND ex.results_published = 1 AND ex.is_active = 1', $school_id, $exam_id, $exam_roll_number ) );

			if ( ! $admit_card ) {
				throw new Exception( esc_html__( 'Exam result not found.', 'school-management' ) );
			}

			$admit_card_id = $admit_card->ID;

			$exam = WLSM_M_Staff_Examination::fetch_exam( $school_id, $admit_card->exam_id );

			$exam_id     = $exam->ID;
			$exam_title  = $exam->exam_title;
			$exam_center = $exam->exam_center;
			$start_date  = $exam->start_date;
			$end_date    = $exam->end_date;
			$show_rank   = $exam->show_rank;
			$show_remark = $exam->show_remark;
			$show_eremark = $exam->show_eremark;
			$psychomotor_enable = $exam->psychomotor_analysis;

			$enable_max_marks = $exam->enable_total_marks;
			$enable_obtained = $exam->results_obtained_marks;
			$psychomotor =  WLSM_Config::sanitize_psychomotor( $exam->psychomotor );

			$exam_papers  = WLSM_M_Staff_Examination::get_exam_papers_by_admit_card( $school_id, $admit_card_id );
			$exam_results = WLSM_M_Staff_Examination::get_exam_results_by_admit_card( $school_id, $admit_card_id );

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

				require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/exam_results.php';

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
