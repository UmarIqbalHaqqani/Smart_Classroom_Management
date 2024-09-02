<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_User.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Parent.php';

class WLSM_P_Print {
	public static function student_print_id_card() {
		$user_id = get_current_user_id();

		if ( ! wp_verify_nonce( $_POST[ 'st-print-id-card-' . $user_id ], 'st-print-id-card-' . $user_id ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			// Checks if student exists.
			$student = WLSM_M_Staff_General::fetch_student( $school_id, $session_id, $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		ob_start();
		$from_front = true;
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/id_card.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	public static function parent_print_id_card() {
		$student_id = isset( $_POST['student_id'] ) ? absint( $_POST['student_id'] ) : 0;

		if ( ! wp_verify_nonce( $_POST[ 'pr-print-id-card-' . $student_id ], 'pr-print-id-card-' . $student_id ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$user_id = get_current_user_id();

			$unique_student_ids = WLSM_M_Parent::get_parent_student_ids( $user_id );

			if ( ! in_array( $student_id, $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student = WLSM_M_Parent::get_student( $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			// Checks if student exists.
			$student = WLSM_M_Staff_General::fetch_student( $school_id, $session_id, $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		ob_start();
		$from_front = true;
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/id_card.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	public static function student_print_payment() {
		$payment_id = isset( $_POST['payment_id'] ) ? absint( $_POST['payment_id'] ) : 0;

		if ( ! wp_verify_nonce( $_POST[ 'st-print-invoice-payment-' . $payment_id ], 'st-print-invoice-payment-' . $payment_id ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$user_id = get_current_user_id();

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			// Checks if payment exists.
			$payment = WLSM_M_Staff_Accountant::get_student_payment( $student_id, $payment_id );

			if ( ! $payment ) {
				throw new Exception( esc_html__( 'Payment not found.', 'school-management' ) );
			}

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		ob_start();
		$from_front = true;
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/payment.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	public static function parent_print_payment() {
		$payment_id = isset( $_POST['payment_id'] ) ? absint( $_POST['payment_id'] ) : 0;

		if ( ! wp_verify_nonce( $_POST[ 'pr-print-invoice-payment-' . $payment_id ], 'pr-print-invoice-payment-' . $payment_id ) ) {
			die();
		}

		$student_id = isset( $_POST['student_id'] ) ? absint( $_POST['student_id'] ) : 0;

		try {
			ob_start();
			global $wpdb;

			$user_id = get_current_user_id();

			$unique_student_ids = WLSM_M_Parent::get_parent_student_ids( $user_id );

			if ( ! in_array( $student_id, $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student = WLSM_M_Parent::get_student( $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			// Checks if payment exists.
			$payment = WLSM_M_Staff_Accountant::get_student_payment( $student_id, $payment_id );

			if ( ! $payment ) {
				throw new Exception( esc_html__( 'Payment not found.', 'school-management' ) );
			}

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		ob_start();
		$from_front = true;
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/payment.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	public static function student_print_class_time_table() {
		try {
			ob_start();
			global $wpdb;

			$user_id = get_current_user_id();

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$section_id = $student->section_id;

			if ( ! wp_verify_nonce( $_POST[ 'st-print-class-time-table-' . $section_id ], 'st-print-class-time-table-' . $section_id ) ) {
				die();
			}

			$section = WLSM_M_Staff_Class::get_school_section( $school_id, $section_id );

			$class_label   = $section->class_label;
			$section_label = $section->label;

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		ob_start();
		$from_front = true;
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/class_time_table.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	public static function parent_print_class_time_table() {
		$student_id = isset( $_POST['student_id'] ) ? absint( $_POST['student_id'] ) : 0;

		try {
			ob_start();
			global $wpdb;

			$user_id = get_current_user_id();

			$unique_student_ids = WLSM_M_Parent::get_parent_student_ids( $user_id );

			if ( ! in_array( $student_id, $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student = WLSM_M_Parent::get_student( $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$section_id = $student->section_id;

			if ( ! wp_verify_nonce( $_POST[ 'pr-print-class-time-table-' . $section_id ], 'pr-print-class-time-table-' . $section_id ) ) {
				die();
			}

			$section = WLSM_M_Staff_Class::get_school_section( $school_id, $section_id );

			$class_label   = $section->class_label;
			$section_label = $section->label;

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		ob_start();
		$from_front = true;
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/class_time_table.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	public static function student_print_exam_time_table() {
		$exam_id = isset( $_POST['exam_id'] ) ? absint( $_POST['exam_id'] ) : 0;

		if ( ! wp_verify_nonce( $_POST[ 'st-print-exam-time-table-' . $exam_id ], 'st-print-exam-time-table-' . $exam_id ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$user_id = get_current_user_id();

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			// Checks if exam exists.
			$exam = WLSM_M_Staff_Examination::get_class_school_exam_time_table( $school_id, $class_school_id, $exam_id );

			if ( ! $exam ) {
				throw new Exception( esc_html__( 'Exam not found.', 'school-management' ) );
			}

			$exam_classes = WLSM_M_Staff_Examination::fetch_exam_classes_label( $school_id, $exam_id );
			$exam_papers  = WLSM_M_Staff_Examination::fetch_exam_papers( $school_id, $exam_id );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		ob_start();
		$from_front = true;
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/exam_time_table.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	public static function student_print_exam_admit_card() {
		$admit_card_id = isset( $_POST['admit_card_id'] ) ? absint( $_POST['admit_card_id'] ) : 0;

		if ( ! wp_verify_nonce( $_POST[ 'st-print-exam-admit-card-' . $admit_card_id ], 'st-print-exam-admit-card-' . $admit_card_id ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$user_id = get_current_user_id();

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			// Checks if admit card exists.
			$admit_card = WLSM_M_Staff_Examination::fetch_student_admit_card( $school_id, $student_id, $admit_card_id );

			if ( ! $admit_card ) {
				throw new Exception( esc_html__( 'Admit card not found.', 'school-management' ) );
			}

			$exam_id = $admit_card->exam_id;

			// Checks if exam exists.
			$exam = WLSM_M_Staff_Examination::fetch_exam( $school_id, $exam_id );

			if ( ! $exam ) {
				throw new Exception( esc_html__( 'Exam not found.', 'school-management' ) );
			}

			$exam_classes = WLSM_M_Staff_Examination::fetch_exam_classes_label( $school_id, $exam_id );
			$exam_papers  = WLSM_M_Staff_Examination::fetch_exam_papers( $school_id, $exam_id );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		ob_start();
		$from_front = true;
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/exam_admit_card.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	public static function student_print_exam_results() {
		$admit_card_id = isset( $_POST['admit_card_id'] ) ? absint( $_POST['admit_card_id'] ) : 0;

		if ( ! wp_verify_nonce( $_POST[ 'st-print-exam-results-' . $admit_card_id ], 'st-print-exam-results-' . $admit_card_id ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$user_id = get_current_user_id();

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			// Checks if admit card exists for published exam result.
			$admit_card = WLSM_M_Staff_Examination::get_student_published_exam_result( $school_id, $student->ID, $admit_card_id );

			if ( ! $admit_card ) {
				throw new Exception( esc_html__( 'Exam result not found.', 'school-management' ) );
			}

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
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		ob_start();
		$from_front = true;
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/exam_results.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	public static function student_exam_result_subjectwise() {
		$student_id = isset( $_POST['student_id'] ) ? absint( $_POST['student_id'] ) : 0;

		if ( ! wp_verify_nonce( $_POST[ 'result-subject-wise-' . $student_id ], 'result-subject-wise-' . $student_id ) ) {
			die();
		}
			global $wpdb;
			$user_id = get_current_user_id();
			$unique_student_ids = WLSM_M_Parent::get_parent_student_ids( $user_id );

			if ( ! in_array( $student_id, $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student = WLSM_M_Parent::get_student( $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			if ($student) {
				$student_id = $student->ID;
			}

		try {
			ob_start();
			global $wpdb;


			// Checks if student exists.
			$student = WLSM_M_Staff_General::fetch_student( $school_id, $session_id, $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$class_school_id = $student->class_school_id;

			$class_id = $student->class_id;

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		ob_start();
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/result_subject_wise.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	public static function parent_print_exam_results() {
		$student_id    = isset( $_POST['student_id'] ) ? absint( $_POST['student_id'] ) : 0;
		$admit_card_id = isset( $_POST['admit_card_id'] ) ? absint( $_POST['admit_card_id'] ) : 0;

		if ( ! wp_verify_nonce( $_POST[ 'pr-print-exam-results-' . $admit_card_id ], 'pr-print-exam-results-' . $admit_card_id ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$user_id = get_current_user_id();

			$unique_student_ids = WLSM_M_Parent::get_parent_student_ids( $user_id );

			if ( ! in_array( $student_id, $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student = WLSM_M_Parent::get_student( $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			// Checks if admit card exists for published exam result.
			$admit_card = WLSM_M_Staff_Examination::get_student_published_exam_result( $school_id, $student->ID, $admit_card_id );

			if ( ! $admit_card ) {
				throw new Exception( esc_html__( 'Exam result not found.', 'school-management' ) );
			}

			$exam = WLSM_M_Staff_Examination::fetch_exam( $school_id, $admit_card->exam_id );

			$exam_id            = $exam->ID;
			$exam_title         = $exam->exam_title;
			$exam_center        = $exam->exam_center;
			$start_date         = $exam->start_date;
			$end_date           = $exam->end_date;
			$show_rank          = $exam->show_rank;
			$show_remark        = $exam->show_remark;
			$show_eremark       = $exam->show_eremark;
			$psychomotor_enable = $exam->psychomotor_analysis;

			$enable_max_marks = $exam->enable_total_marks;
			$enable_obtained  = $exam->results_obtained_marks;

			$psychomotor =  WLSM_Config::sanitize_psychomotor( $exam->psychomotor );

			$exam_papers  = WLSM_M_Staff_Examination::get_exam_papers_by_admit_card( $school_id, $admit_card_id );
			$exam_results = WLSM_M_Staff_Examination::get_exam_results_by_admit_card( $school_id, $admit_card_id );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		ob_start();
		$from_front = true;
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/exam_results.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	public static function student_print_results_assessment() {
		$student_id = isset( $_POST['student_id'] ) ? absint( $_POST['student_id'] ) : 0;

		if ( ! wp_verify_nonce( $_POST[ 'st-print-results-assessment-' . $student_id ], 'st-print-results-assessment-' . $student_id ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$user_id = get_current_user_id();

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			// Checks if student exists.
			$student = WLSM_M_Staff_General::fetch_student( $school_id, $session_id, $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		ob_start();
		$from_front = true;
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/result_assessment.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	public static function student_print_results_subject_wise() {
		$student_id = isset( $_POST['student_id'] ) ? absint( $_POST['student_id'] ) : 0;

		if ( ! wp_verify_nonce( $_POST[ 'st-print-results-subject-wise-' . $student_id ], 'st-print-results-subject-wise-' . $student_id ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$user_id = get_current_user_id();

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			// Checks if student exists.
			$student = WLSM_M_Staff_General::fetch_student( $school_id, $session_id, $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$class_id      = $student->class_id;
			$session_label = $student->session_label;

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		ob_start();
		$from_front = true;
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/result_subject_wise.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	public static function print_exam_time_table() {
		$exam_id = isset( $_POST['exam_id'] ) ? absint( $_POST['exam_id'] ) : 0;

		if ( ! wp_verify_nonce( $_POST[ 'print-exam-time-table-' . $exam_id ], 'print-exam-time-table-' . $exam_id ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$school_id = isset( $_POST['school_id'] ) ? absint( $_POST['school_id'] ) : 0;

			// Checks if exam exists.
			$exam = WLSM_M_Staff_Examination::fetch_exam( $school_id, $exam_id );

			if ( ! $exam ) {
				throw new Exception( esc_html__( 'Exam not found.', 'school-management' ) );
			}

			$exam_classes = WLSM_M_Staff_Examination::fetch_exam_classes_label( $school_id, $exam_id );
			$exam_papers  = WLSM_M_Staff_Examination::fetch_exam_papers( $school_id, $exam_id );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		ob_start();
		$from_front = true;
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/exam_time_table.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}
}
