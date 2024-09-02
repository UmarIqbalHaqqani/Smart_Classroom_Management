<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Library.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Parent.php';

class WLSM_Api {
	const NS             = 'sm';
	const GLOBAL_PREFIX  = 'global';
	const SCHOOL_PREFIX  = 'school';
	const STUDENT_PREFIX = 'student';
	const PARENT_PREFIX  = 'parent';

	// Checks if user is student or parent.
	public static function token_before_dispatch( $data, $user ) {

		$user_id = $user->ID;
		$student = WLSM_M::get_student( $user_id );
		$student_logo = WLSM_M::get_student_profile( $user_id );
		if(wp_get_attachment_url( $student_logo->photo_id ))
			{
			 $student_photo = wp_get_attachment_url( $student_logo->photo_id );
			} else {
				$student_photo =" ";
			}

		if ( $student ) {
			$data['user_type'] = self::STUDENT_PREFIX;
			$data['photo'] = esc_url( $student_photo );
			$data['user_display_name'] = $student->student_name;

			$school_id = $student->school_id;

			$school = WLSM_M_School::fetch_school( $school_id );

			$school_data = array();

			if ( $school ) {
				// General settings.
				$settings_general = WLSM_M_Setting::get_settings_general( $school_id );
				//$school_logo      = $settings_general['school_logo'];

				if(wp_get_attachment_url ($settings_general['school_logo'])){
					$school_logo = wp_get_attachment_url ($settings_general['school_logo']);
				} else {
					$school_logo = "";
				}


				$school_data = array(
					'name'    => esc_html( WLSM_M_School::get_label_text( $school->label ) ),
					'phone'   => esc_html( WLSM_M_School::get_phone_text( $school->phone ) ),
					'email'   => esc_html( WLSM_M_School::get_email_text( $school->email ) ),
					'address' => esc_html( WLSM_M_School::get_address_text( $school->address ) ),
					'logo'    => esc_url( $school_logo ),
				);
			}

			$data['school'] = $school_data;

		} else {
			$unique_student_ids = WLSM_M_Parent::get_parent_student_ids( $user_id );
			if ( count( $unique_student_ids ) ) {
				$data['user_type'] = self::PARENT_PREFIX;

			} else {
				return self::no_account();
			}
		}



		return $data;
	}

	// API routes.
	public static function register_rest_routes() {
		// Global - Settings.
		register_rest_route(
			self::NS, self::GLOBAL_PREFIX . '/settings',
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( 'WLSM_Api', 'global_settings' ),
				'permission_callback' => '__return_true',
			)
		);

		// Account Settings.
		register_rest_route(
			self::NS, 'account-settings',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( 'WLSM_Api', 'account_settings' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// School - Settings.
		register_rest_route(
			self::NS, self::SCHOOL_PREFIX . '/settings',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'school_settings' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Profile.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/profile',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'student_profile' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Dashboard.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/dashboard',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'student_dashboard' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Study materials.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/study-materials',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'student_study_materials' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Noticeboard.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/noticeboard',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'student_noticeboard' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Fee Invoices.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/fee-invoices',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'fee_invoices' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Fee Invoice.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/fee-invoices/(?P<invoice_id>\d+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'fee_invoice' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Payment History.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/payments',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'student_payments' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Payment Receipt.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/payments/(?P<payment_id>\d+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'student_payment' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Events.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/events',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'student_events' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Event.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/events/(?P<event_id>\d+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'student_event' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Join event.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/events/(?P<event_id>\d+)/join',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( 'WLSM_Api', 'student_join_event' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Unjoin event.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/events/(?P<event_id>\d+)/unjoin',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( 'WLSM_Api', 'student_unjoin_event' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Class time table.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/class-time-table',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'student_class_time_table' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Books Issued.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/books-issued',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'student_books_issued' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Live Classes.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/live-classes',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'student_live_classes' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Attendance.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/attendance',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'student_attendance' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Study materials.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/study-materials',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'student_study_materials' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Study material.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/study-materials/(?P<study_material_id>\d+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'student_study_material' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Homeworks.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/homework',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'student_homeworks' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Homework.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/homework/(?P<homework_id>\d+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'student_homework' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Exams time table.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/exam-time-table',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'exams_time_table' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Exam time table.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/exam-time-table/(?P<exam_id>\d+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'exam_time_table' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Admit cards.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/admit-cards',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'admit_cards' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Admit card.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/admit-cards/(?P<admit_card_id>\d+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'admit_card' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Exams results.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/exam-results',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'exam_results' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Exam result.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/exam-results/(?P<admit_card_id>\d+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'exam_result' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Overall result.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/overall-result',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'overall_result' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Leave requests.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/leave-requests',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'student_leave_requests' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Submit Leave request.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/submit-leave-request',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( 'WLSM_Api', 'student_submit_leave_request' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		 // Student - Homework request.
		 register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/submit-homework-request',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( 'WLSM_Api', 'student_submit_homework_request' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - Submit Invoice payment request.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/submit-invoice-payment-request',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( 'WLSM_Api', 'student_submit_invoice_payment_request' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Student - About school.
		register_rest_route(
			self::NS, self::STUDENT_PREFIX . '/about-school',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'student_about_school' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Parent - Students.
		register_rest_route(
			self::NS, self::PARENT_PREFIX . '/students',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'parent_students' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Parent - Noticeboard.
		register_rest_route(
			self::NS, self::PARENT_PREFIX . '/students/(?P<student_id>\d+)' . '/noticeboard',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'parent_noticeboard' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Parent - Fee Invoices.
		register_rest_route(
			self::NS, self::PARENT_PREFIX . '/students/(?P<student_id>\d+)' . '/fee-invoices',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'parent_fee_invoices' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Parent - Fee Invoice.
		register_rest_route(
			self::NS, self::PARENT_PREFIX . '/students/(?P<student_id>\d+)' . '/fee-invoices/(?P<invoice_id>\d+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'parent_fee_invoice' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Parent - Payment History.
		register_rest_route(
			self::NS, self::PARENT_PREFIX . '/students/(?P<student_id>\d+)' . '/payments',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'parent_payments' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Parent - Payment Receipt.
		register_rest_route(
			self::NS, self::PARENT_PREFIX . '/students/(?P<student_id>\d+)' . '/payments/(?P<payment_id>\d+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'parent_payment' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Parent - Class time table.
		register_rest_route(
			self::NS, self::PARENT_PREFIX . '/students/(?P<student_id>\d+)' . '/class-time-table',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'parent_class_time_table' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Parent - Attendance.
		register_rest_route(
			self::NS, self::PARENT_PREFIX . '/students/(?P<student_id>\d+)' . '/attendance',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'parent_attendance' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Parent - Exams results.
		register_rest_route(
			self::NS, self::PARENT_PREFIX . '/students/(?P<student_id>\d+)' . '/exam-results',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'parent_exam_results' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);

		// Parent - Exam result.
		register_rest_route(
			self::NS, self::PARENT_PREFIX . '/students/(?P<student_id>\d+)' . '/exam-results/(?P<admit_card_id>\d+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WLSM_Api', 'parent_exam_result' ),
				'permission_callback' => function( $request ) {
					return is_user_logged_in();
				}
			)
		);
	}

	// Global - Settings.
	public static function global_settings() {
		$active_currency    = WLSM_Config::currency();
		$active_date_format = WLSM_Config::date_format();

		$message = esc_html__( 'Global settings retrieved successfully.', 'school-management' );

		$response = array(
			'success' => true,
			'message' => $message,
		);

		$response['data'] = array(
			'date_format'   => $active_date_format,
			'currency_code' => $active_currency
		);

		return new WP_REST_Response( $response, 200 );
	}

	// Account Settings.
	public static function account_settings( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M::get_student( $user_id );

			if ( $student ) {
				$data['user_type'] = self::STUDENT_PREFIX;

			} else {
				$unique_student_ids = WLSM_M_Parent::get_parent_student_ids( $user_id );
				if ( count( $unique_student_ids ) ) {
					$data['user_type'] = self::PARENT_PREFIX;

				} else {
					return self::no_account();
				}
			}

			$params = $request->get_params();

			$email            = isset( $params['email'] ) ? sanitize_email( $params['email'] ) : '';
			$password         = isset( $params['password'] ) ? $params['password'] : '';
			$password_confirm = isset( $params['password_confirm'] ) ? $params['password_confirm'] : '';

			if ( empty( $email ) ) {
				throw new Exception( esc_html__( 'Please provide email address.', 'school-management' ) );
			}

			if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				throw new Exception( esc_html__( 'Please provide a valid email.', 'school-management' ) );
			}

			if ( empty( $password ) ) {
				throw new Exception( esc_html__( 'Please provide password.', 'school-management' ) );
			}

			if ( empty( $password_confirm ) ) {
				throw new Exception( esc_html__( 'Please confirm password.', 'school-management' ) );
			}

			if ( $password !== $password_confirm ) {
				throw new Exception( esc_html__( 'Passwords do not match.', 'school-management' ) );
			}

			$user = wp_get_current_user();

			$data = array(
				'ID'         => $user->ID,
				'user_email' => $email,
				'user_pass'  => $password,
			);

			$user_id = wp_update_user( $data );

			if ( is_wp_error( $user_id ) ) {
				throw new Exception( $user_id->get_error_message() );
			}

			WLSM_Helper::check_buffer();

			$success = true;
			$message = esc_html__( 'Account settings updated.', 'school-management' );

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// School - Settings.
	public static function school_settings() {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			die;

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Profile.
	public static function student_profile( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M::get_student_profile( $user_id );
			$student_common_details = WLSM_M::get_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			if(wp_get_attachment_url( $student->photo_id ))
			{
			 $photo_url = wp_get_attachment_url( $student->photo_id );
			} else {
				$photo_url =" ";
			}

			$common_details = self::student_common_details( $student_common_details );
			$other_details  = array(
				'enrollment_number' => esc_html( $student_common_details->enrollment_number ),
				'name'    => esc_html( WLSM_M_Staff_Class::get_name_text( $student->student_name ) ),
				'photo'   => esc_url( $photo_url ),
				'session' => esc_html( WLSM_M_Session::get_label_text( $student->session_label ) ),
				'class'   => esc_html( WLSM_M_Class::get_label_text( $student->class_label ) ),
				'section' => esc_html( WLSM_M_Class::get_label_text( $student->section_label ) ),
				'roll_number' => esc_html( WLSM_M_Staff_Class::get_roll_no_text( $student_common_details->roll_number ) ),
				'father_name' => esc_html( WLSM_M_Staff_Class::get_name_text( $student_common_details->father_name ) ),

			);
			$response_data['student'] = array_merge( $common_details, $other_details );

			$success = true;
			$message = esc_html__( 'Student profile retrieved successfully.', 'school-management' );

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Dashboard.
	public static function student_dashboard( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M::get_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$student_logo = WLSM_M::get_student_profile( $user_id );
			if(wp_get_attachment_url( $student_logo->photo_id ))
			{
			 $student_photo = wp_get_attachment_url( $student_logo->photo_id );
			} else {
				$student_photo =" ";
			}


			$common_details = self::student_common_details( $student );
			$other_details  = array(
				'name'    => esc_html( WLSM_M_Staff_Class::get_name_text( $student->student_name ) ),
				'enrollment_number' => esc_html( $student->enrollment_number ),
				'session'           => esc_html( WLSM_M_Session::get_label_text( $student->session_label ) ),
				'class'             => esc_html( WLSM_M_Class::get_label_text( $student->class_label ) ),
				'section'           => esc_html( WLSM_M_Class::get_label_text( $student->section_label ) ),
				'roll_number'       => esc_html( WLSM_M_Staff_Class::get_roll_no_text( $student->roll_number ) ),
				'father_name'       => esc_html( WLSM_M_Staff_Class::get_name_text( $student->father_name ) ),
				'photo'       		=> esc_url( $student_photo ),
			);
			$response_data['student'] = array_merge( $common_details, $other_details );

			$attendance = WLSM_M_Staff_General::get_student_attendance_stats( $student->ID );

			$response_data['attendance'] = $attendance;

			$params = $request->get_params();

			// Query.
			$notices_query = WLSM_M::notices_query();

			// Total.
			$notices_total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM ({$notices_query}) AS combined_table", $class_school_id, $class_school_id, $school_id ) );

			// Current page and per page.
			$notices_page     = isset( $params['notices_page'] ) ? absint( $params['notices_page'] ) : 1;
			$notices_per_page = isset( $params['notices_per_page'] ) ? absint( $params['notices_per_page'] ) : 7;

			// Page offset.
			$notices_page_offset = ( $notices_page * $notices_per_page ) - $notices_per_page;

			// Paginated data.
			$notices = $wpdb->get_results( $wpdb->prepare( $notices_query . ' ORDER BY n.ID DESC LIMIT %d, %d', $class_school_id, $class_school_id, $school_id, $notices_page_offset, $notices_per_page ) );

			// Paginated response.
			$notices_data       = array();
			$notices_pagination = array(
				'current_page' => $notices_page,
				'per_page'     => $notices_per_page,
			);

			// Format response.
			if ( count( $notices ) ) {
				$today = new DateTime();
				$today->setTime( 0, 0, 0 );

				foreach ( $notices as $key => $notice ) {
					$link_to = $notice->link_to;
					$link    = '#';

					if ( 'url' === $link_to ) {
						if ( ! empty ( $notice->url ) ) {
							$link = $notice->url;
						}
					} else if ( 'attachment' === $link_to ) {
						if ( ! empty ( $notice->attachment ) ) {
							$attachment = $notice->attachment;
							$link       = wp_get_attachment_url( $attachment );
						}
					} else {
						$link = '#';
					}

					$notice_date = DateTime::createFromFormat( 'Y-m-d H:i:s', $notice->created_at );
					$notice_date->setTime( 0, 0, 0 );

					$interval = $today->diff( $notice_date );

					$notices_data[] = array(
						'id'       => $notice->ID,
						'title'    => esc_html( stripslashes( $notice->title ) ),
						'link'     => esc_url( $link ),
						'date'     => esc_html( WLSM_Config::get_date_text( $notice->created_at ) ),
						'is_new'   => ( $interval->days < 7 ) ? true : false
					);
				}

				$notices_pagination['total_pages']   = ceil( $notices_total / $notices_per_page );
				$notices_pagination['total_records'] = $notices_total;
			}

			$success = true;
			$message = esc_html__( 'Student dashboard retrieved successfully.', 'school-management' );

			$response_data['noticeboard'] = array(
				'new_notice_icon' => esc_url( WLSM_PLUGIN_URL . 'assets/images/newicon.gif' ),
				'data'            => $notices_data,
				'pagination'      => $notices_pagination
			);

			unset( $response_data['noticeboard']['pagination'] );

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Noticeboard.
	public static function student_noticeboard( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			// Query.
			$notices_query = WLSM_M::notices_query();

			// Total.
			$notices_total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM ({$notices_query}) AS combined_table", $class_school_id, $class_school_id, $school_id ) );

			// Current page and per page.
			$notices_page     = isset( $params['notices_page'] ) ? absint( $params['notices_page'] ) : 1;
			$notices_per_page = isset( $params['notices_per_page'] ) ? absint( $params['notices_per_page'] ) : WLSM_M::notices_per_page();

			// Page offset.
			$notices_page_offset = ( $notices_page * $notices_per_page ) - $notices_per_page;

			// Paginated data.
			$notices = $wpdb->get_results( $wpdb->prepare( $notices_query . ' ORDER BY n.ID DESC LIMIT %d, %d', $class_school_id, $class_school_id, $school_id, $notices_page_offset, $notices_per_page ) );

			// Paginated response.
			$notices_data       = array();
			$notices_pagination = array(
				'current_page' => $notices_page,
				'per_page'     => $notices_per_page,
			);

			// Format response.
			if ( count( $notices ) ) {
				$today = new DateTime();
				$today->setTime( 0, 0, 0 );

				foreach ( $notices as $key => $notice ) {
					$link_to = $notice->link_to;
					$link    = '#';

					if ( 'url' === $link_to ) {
						if ( ! empty ( $notice->url ) ) {
							$link = $notice->url;
						}
					} else if ( 'attachment' === $link_to ) {
						if ( ! empty ( $notice->attachment ) ) {
							$attachment = $notice->attachment;
							$link       = wp_get_attachment_url( $attachment );
						}
					} else {
						$link = '#';
					}

					$notice_date = DateTime::createFromFormat( 'Y-m-d H:i:s', $notice->created_at );
					$notice_date->setTime( 0, 0, 0 );

					$interval = $today->diff( $notice_date );

					$notices_data[] = array(
						'id'          => $notice->ID,
						'title'       => esc_html( stripslashes( $notice->title ) ),
						'description' => esc_html( stripslashes( $notice->description ) ),
						'link'        => esc_url( $link ),
						'date'        => esc_html( WLSM_Config::get_date_text( $notice->created_at ) ),
						'is_new'      => ( $interval->days < 7 ) ? true : false
					);
				}

				$notices_pagination['total_pages']   = ceil( $notices_total / $notices_per_page );
				$notices_pagination['total_records'] = $notices_total;
			}

			$success = true;
			$message = esc_html__( 'Noticeboard retrieved successfully.', 'school-management' );

			$response_data['noticeboard'] = array(
				'new_notice_icon' => esc_url( WLSM_PLUGIN_URL . 'assets/images/newicon.gif' ),
				'data'            => $notices_data,
				'pagination'      => $notices_pagination
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Fee Invoices.
	public static function fee_invoices( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			$invoices = WLSM_M_Staff_Accountant::get_student_pending_invoices( $student->ID );

			$invoices_data = array();

			if ( count( $invoices ) ) {
				foreach ( $invoices as $row ) {
					$due = $row->payable - $row->paid;
					$invoices_data[] = array(
						'id'              => $row->ID,
						'invoice_number'  => esc_html( $row->invoice_number ),
						'invoice_title'   => esc_html( WLSM_M_Staff_Accountant::get_invoice_title_text( $row->invoice_title ) ),
						'payable'         => esc_html( WLSM_Config::sanitize_money( $row->payable ) ),
						'payable_display' => esc_html( WLSM_Config::get_money_text( $row->payable, $school_id  ) ),
						'paid'            => esc_html( WLSM_Config::sanitize_money( $row->paid ) ),
						'paid_display'    => esc_html( WLSM_Config::get_money_text( $row->paid, $school_id  ) ),
						'due'             => esc_html( WLSM_Config::sanitize_money( $due ) ),
						'due_display'     => esc_html( WLSM_Config::get_money_text( $due, $school_id  ) ),
						'status'          => esc_html( $row->status ),
						'status_text'     => esc_html( WLSM_M_Invoice::get_status_text( $row->status, false ) ),
						'show_pay_now'    => WLSM_M_Invoice::get_paid_key() !== $row->status,
						'date_issued'     => esc_html( WLSM_Config::get_date_text( $row->date_issued ) ),
						'due_date'        => esc_html( WLSM_Config::get_date_text( $row->due_date ) ),
					);
				}
			}

			$success = true;
			$message = esc_html__( 'Fee invoices retrieved successfully.', 'school-management' );

			$response_data['invoices'] = array(
				'data' => $invoices_data
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Fee Invoice.
	public static function fee_invoice( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			$invoice_id = isset( $params['invoice_id'] ) ? absint( $params['invoice_id'] ) : 0;

			$invoice = WLSM_M_Staff_Accountant::get_student_pending_invoice( $invoice_id );

			if ( ! $invoice ) {
				throw new Exception( esc_html__( 'Invoice not found.', 'school-management' ) );
			}

			$due = $invoice->payable - $invoice->paid;

			$invoice_partial_payment = $invoice->partial_payment;

			$currency = WLSM_Config::currency($school_id);

			// Razorpay settings.
			$settings_razorpay      = WLSM_M_Setting::get_settings_razorpay( $school_id );
			$school_razorpay_enable = $settings_razorpay['enable'];

			// Stripe settings.
			$settings_stripe      = WLSM_M_Setting::get_settings_stripe( $school_id );
			$school_stripe_enable = $settings_stripe['enable'];

			// PayPal settings.
			$settings_paypal      = WLSM_M_Setting::get_settings_paypal( $school_id );
			$school_paypal_enable = $settings_paypal['enable'];

			// Pesapal settings.
			$settings_pesapal      = WLSM_M_Setting::get_settings_pesapal( $school_id );
			$school_pesapal_enable = $settings_pesapal['enable'];

			// Paystack settings.
			$settings_paystack      = WLSM_M_Setting::get_settings_paystack( $school_id );
			$school_paystack_enable = $settings_paystack['enable'];

			// Paytm settings.
			$settings_paytm      = WLSM_M_Setting::get_settings_paytm( $school_id );
			$school_paytm_enable = $settings_paytm['enable'];

			// bank settings.
			$settings_bank      = WLSM_M_Setting::get_settings_bank_transfer( $school_id );
			$school_bank_enable = $settings_bank['enable'];

			$success = true;
			$message = esc_html__( 'Fee invoice retrieved successfully.', 'school-management' );

			$response_data['invoice'] = array(
				'id'                      => $invoice->ID,
				'invoice_number'          => esc_html( $invoice->invoice_number ),
				'invoice_title'           => esc_html( WLSM_M_Staff_Accountant::get_invoice_title_text( $invoice->invoice_title ) ),
				'date_issued'             => esc_html( WLSM_Config::get_date_text( $invoice->date_issued ) ),
				'due_date'                => esc_html( WLSM_Config::get_date_text( $invoice->due_date ) ),
				'student_name'            => esc_html( WLSM_M_Staff_Class::get_name_text( $invoice->student_name ) ),
				'enrollment_number'       => esc_html( $invoice->enrollment_number ),
				'class'                   => esc_html( WLSM_M_Class::get_label_text( $invoice->class_label ) ),
				'section'                 => esc_html( WLSM_M_Staff_Class::get_section_label_text( $invoice->section_label ) ),
				'fees_due'                => esc_html( WLSM_Config::sanitize_money( $due ) ),
				'fees_due_display'        => esc_html( WLSM_Config::get_money_text( $due, $school_id  ) ),
				'partial_payment_allowed' => (bool) $invoice_partial_payment,
				'currency'                => $currency
			);

			$payment_methods = array();

			if ( $school_razorpay_enable && WLSM_Payment::currency_supports_razorpay( $currency ) ) {
				$payment_methods['razorpay'] = esc_html( WLSM_M_Invoice::get_payment_method_text( 'razorpay' ) );

				$school_razorpay_key    = $settings_razorpay['razorpay_key'];
				$school_razorpay_secret = $settings_razorpay['razorpay_secret'];

				$response_data['razorpay_api_data'] = array(
					'school_razorpay_key'    => $school_razorpay_key,
					'school_razorpay_secret' => $school_razorpay_secret
				);
			}

			if ( $school_stripe_enable && WLSM_Payment::currency_supports_stripe( $currency ) ) {
				$payment_methods['stripe'] = esc_html( WLSM_M_Invoice::get_payment_method_text( 'stripe' ) );

				$school_stripe_publishable_key = $settings_stripe['publishable_key'];
				$school_stripe_secret_key      = $settings_stripe['secret_key'];

				$response_data['stripe_api_data'] = array(
					'publishable_key' => $school_stripe_publishable_key,
					'secret_key'      => $school_stripe_secret_key
				);
			}

			if ( $school_paypal_enable && WLSM_Payment::currency_supports_paypal( $currency ) ) {
				$payment_methods['paypal'] = esc_html( WLSM_M_Invoice::get_payment_method_text( 'paypal' ) );

				$school_paypal_business_email = $settings_paypal['business_email'];
				$school_paypal_mode           = $settings_paypal['mode'];
				$school_paypal_notify_url     = $settings_paypal['notify_url'];

				$response_data['paypal_api_data'] = array(
					'school_paypal_business_email' => $school_paypal_business_email,
					'school_paypal_mode'           => $school_paypal_mode,
					'school_paypal_notify_url'     => $school_paypal_notify_url
				);
			}

			if ( $school_pesapal_enable && WLSM_Payment::currency_supports_pesapal( $currency ) ) {
				$payment_methods['pesapal'] = esc_html( WLSM_M_Invoice::get_payment_method_text( 'pesapal' ) );

				$school_pesapal_consumer_key    = $settings_pesapal['consumer_key'];
				$school_pesapal_consumer_secret = $settings_pesapal['consumer_secret'];
				$school_pesapal_mode            = $settings_pesapal['mode'];
				$school_pesapal_notify_url      = $settings_pesapal['notify_url'];

				$response_data['paypal_api_data'] = array(
					'school_pesapal_consumer_key'    => $school_pesapal_consumer_key,
					'school_pesapal_consumer_secret' => $school_pesapal_consumer_secret,
					'school_pesapal_notify_url'      => $school_pesapal_notify_url,
					'school_pesapal_mode'            => $school_pesapal_mode
				);
			}

			if ( $school_paystack_enable && WLSM_Payment::currency_supports_paystack( $currency ) ) {
				$payment_methods['paystack'] = esc_html( WLSM_M_Invoice::get_payment_method_text( 'paystack' ) );

				$school_paystack_public_key = $settings_paystack['paystack_public_key'];
				$school_paystack_secret_key = $settings_paystack['paystack_secret_key'];

				$response_data['paystack_api_data'] = array(
					'school_paystack_public_key'    => $school_paystack_public_key,
					'school_paystack_secret_key' => $school_paystack_secret_key
				);

			}

			if ( $school_paytm_enable && WLSM_Payment::currency_supports_paytm( $currency ) ) {
				$payment_methods['paytm'] = esc_html( WLSM_M_Invoice::get_payment_method_text( 'paytm' ) );

				// Paytm settings.
				$school_paytm_merchant_id      = $settings_paytm['merchant_id'];
				$school_paytm_merchant_key     = $settings_paytm['merchant_key'];
				$school_paytm_industry_type_id = $settings_paytm['industry_type_id'];
				$school_paytm_website          = $settings_paytm['website'];
				$school_paytm_mode             = $settings_paytm['mode'];


				$response_data['paytm_api_data'] = array(
					'school_paytm_merchant_id'      => $school_paytm_merchant_id,
					'school_paytm_merchant_key'     => $school_paytm_merchant_key,
					'school_paytm_industry_type_id' => $school_paytm_industry_type_id,
					'school_paytm_website'          => $school_paytm_website,
					'school_paytm_mode'             => $school_paytm_mode
				);
			}

			if ( $school_bank_enable  ) {
				$payment_methods['bank-transfer'] = esc_html( WLSM_M_Invoice::get_payment_method_text( 'bank-transfer' ) );

				$settings_bank      = WLSM_M_Setting::get_settings_bank_transfer( $school_id );
				$school_bank_enable = $settings_bank['enable'];
				$branch             = $settings_bank['branch'];
				$account            = $settings_bank['account'];
				$name               = $settings_bank['name'];
				$message            = $settings_bank['message'];


				$response_data['bank-tranfer-data'] = array(
					'branch'  => $branch,
					'account' => $account,
					'name'    => $name,
					'message' => $message,
				);
			}



			$response_data['payment_methods'] = $payment_methods;

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Payment History.
	public static function student_payments( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			// Query.
			$payments_query = WLSM_M::payments_query();

			// Total.
			$payments_total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM ({$payments_query}) AS combined_table", $student->ID ) );

			// Current page and per page.
			$payments_page     = isset( $params['payments_page'] ) ? absint( $params['payments_page'] ) : 1;
			$payments_per_page = isset( $params['payments_per_page'] ) ? absint( $params['payments_per_page'] ) : WLSM_M::payments_per_page();

			// Page offset.
			$payments_page_offset = ( $payments_page * $payments_per_page ) - $payments_per_page;

			// Paginated data.
			$payments = $wpdb->get_results( $wpdb->prepare( $payments_query . ' ORDER BY p.ID DESC LIMIT %d, %d', $student->ID, $payments_page_offset, $payments_per_page ) );

			// Paginated response.
			$payments_data       = array();
			$payments_pagination = array(
				'current_page' => $payments_page,
				'per_page'     => $payments_per_page,
			);

			// Format response.
			if ( count( $payments ) ) {
				foreach ( $payments as $key => $row ) {
					if ( $row->invoice_id ) {
						$invoice_title = $row->invoice_title;
					} else {
						$invoice_title = $row->invoice_label;
					}

					$payments_data[] = array(
						'id'             => $row->ID,
						'receipt_number' => esc_html( WLSM_M_Invoice::get_receipt_number_text( $row->receipt_number ) ),
						'amount'         => esc_html( WLSM_Config::sanitize_money( $row->amount ) ),
						'amount_display' => esc_html( WLSM_Config::get_money_text( $row->amount, $school_id  ) ),
						'payment_method' => esc_html( WLSM_M_Invoice::get_payment_method_text( $row->payment_method ) ),
						'transaction_id' => esc_html( WLSM_M_Invoice::get_transaction_id_text( $row->transaction_id ) ),
						'date'           => esc_html( WLSM_Config::get_date_text( $row->created_at ) ),
						'invoice'        => esc_html( WLSM_M_Staff_Accountant::get_invoice_title_text( $invoice_title ) )
					);
				}

				$payments_pagination['total_pages']   = ceil( $payments_total / $payments_per_page );
				$payments_pagination['total_records'] = $payments_total;
			}

			$success = true;
			$message = esc_html__( 'Payments retrieved successfully.', 'school-management' );

			$response_data['payments'] = array(
				'data'       => $payments_data,
				'pagination' => $payments_pagination
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Payment Receipt.
	public static function student_payment( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			$payment_id = isset( $params['payment_id'] ) ? absint( $params['payment_id'] ) : 0;

			$payment = WLSM_M_Staff_Accountant::get_student_payment( $student_id, $payment_id );

			if ( ! $payment ) {
				throw new Exception( esc_html__( 'Payment not found.', 'school-management' ) );
			}

			$success = true;
			$message = esc_html__( 'Payment details retrieved successfully.', 'school-management' );

			if ( $payment->invoice_id ) {
				$invoice_title = esc_html( WLSM_M_Staff_Accountant::get_invoice_title_text( $payment->invoice_title ) );
			} else {
				$invoice_title = esc_html( WLSM_M_Staff_Accountant::get_invoice_title_text( $payment->invoice_label ) );
			}

			$response_data['payment'] = array(
				'id'                => $payment->ID,
				'receipt_number'    => esc_html( WLSM_M_Invoice::get_receipt_number_text( $payment->receipt_number ) ),
				'amount'            => esc_html( WLSM_Config::sanitize_money( $payment->amount ) ),
				'amount_display'    => esc_html( WLSM_Config::get_money_text( $payment->amount, $school_id  ) ),
				'payment_method'    => esc_html( WLSM_M_Invoice::get_payment_method_text( $payment->payment_method ) ),
				'transaction_id'    => esc_html( WLSM_M_Invoice::get_transaction_id_text( $payment->transaction_id ) ),
				'date'              => esc_html( WLSM_Config::get_date_text( $payment->created_at ) ),
				'invoice'           => esc_html( $invoice_title ),
				'student_name'      => esc_html( WLSM_M_Staff_Class::get_name_text( $payment->student_name ) ),
				'enrollment_number' => esc_html( $payment->enrollment_number ),
				'phone'             => esc_html( WLSM_M_Staff_Class::get_phone_text( $payment->phone ) ),
				'email'             => esc_html( WLSM_M_Staff_Class::get_name_text( $payment->email ) ),
				'class'             => esc_html( WLSM_M_Class::get_label_text( $payment->class_label ) ),
				'section'           => esc_html( WLSM_M_Class::get_label_text( $payment->section_label ) ),
				'roll_number'       => esc_html( WLSM_M_Staff_Class::get_roll_no_text( $payment->roll_number ) ),
				'father_name'       => esc_html( WLSM_M_Staff_Class::get_name_text( $payment->father_name ) ),
				'father_phone'      => esc_html( WLSM_M_Staff_Class::get_phone_text( $payment->father_phone ) ),
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Events.
	public static function student_events( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			// Query.
			$events_query = WLSM_M::events_query();

			// Total.
			$events_total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM ({$events_query}) AS combined_table", $student->ID, $school_id ) );

			// Current page and per page.
			$events_page     = isset( $params['events_page'] ) ? absint( $params['events_page'] ) : 1;
			$events_per_page = isset( $params['events_per_page'] ) ? absint( $params['events_per_page'] ) : WLSM_M::events_per_page();

			// Page offset.
			$events_page_offset = ( $events_page * $events_per_page ) - $events_per_page;

			// Paginated data.
			$events = $wpdb->get_results( $wpdb->prepare( $events_query . ' ORDER BY ev.ID DESC LIMIT %d, %d', $student->ID, $school_id, $events_page_offset, $events_per_page ) );

			// Paginated response.
			$events_data       = array();
			$events_pagination = array(
				'current_page' => $events_page,
				'per_page'     => $events_per_page,
			);

			// Format response.
			if ( count( $events ) ) {
				foreach ( $events as $key => $event ) {
					$events_data[] = array(
						'id'          => $event->ID,
						'title'       => esc_html( WLSM_M_Staff_Class::get_name_text( $event->title ) ),
						'event_date'  => esc_html( WLSM_Config::get_date_text( $event->event_date ) ),
						'image'       => esc_url( wp_get_attachment_url( $event->image_id ) ),
						'has_joined'  => $event->student_joined ? true : false
					);
				}

				$events_pagination['total_pages']   = ceil( $events_total / $events_per_page );
				$events_pagination['total_records'] = $events_total;
			}

			$success = true;
			$message = esc_html__( 'Events retrieved successfully.', 'school-management' );

			$response_data['events'] = array(
				'data'       => $events_data,
				'pagination' => $events_pagination
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Event.
	public static function student_event( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			$event_id = isset( $params['event_id'] ) ? absint( $params['event_id'] ) : 0;

			$event = WLSM_M_Staff_Class::fetch_active_event( $school_id, $event_id, $student_id );

			if ( ! $event ) {
				throw new Exception( esc_html__( 'Event not found.', 'school-management' ) );
			}

			$success = true;
			$message = esc_html__( 'Event details retrieved successfully.', 'school-management' );

			$response_data['event'] = array(
				'id'          => $event->ID,
				'title'       => esc_html( WLSM_M_Staff_Class::get_name_text( $event->title ) ),
				'event_date'  => esc_html( WLSM_Config::get_date_text( $event->event_date ) ),
				'image'       => esc_url( wp_get_attachment_url( $event->image_id ) ),
				'description' => wp_kses_post( stripslashes( $event->description ) ),
				'has_joined'  => $event->student_joined ? true : false
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Join event.
	public static function student_join_event( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$wpdb->query( 'BEGIN;' );

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$params = $request->get_params();

			$event_id = isset( $params['event_id'] ) ? absint( $params['event_id'] ) : 0;

			$event = WLSM_M_Staff_Class::fetch_active_event( $school_id, $event_id, $student_id );

			if ( ! $event ) {
				throw new Exception( esc_html__( 'Event not found.', 'school-management' ) );
			}

			if ( $event->student_joined ) {
				throw new Exception( esc_html__( 'You have already joined.', 'school-management' ) );
			}

			// Event participant data.
			$data = array(
				'student_record_id' => $student_id,
				'event_id'          => $event_id,
			);

			$data['created_at'] = current_time( 'Y-m-d H:i:s' );

			$success = $wpdb->insert( WLSM_EVENT_RESPONSES, $data );

			WLSM_Helper::check_buffer();

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			$success = true;
			$message = esc_html__( 'You have joined the event successfully.', 'school-management' );

			$response_data['event'] = array(
				'id'         => $event->ID,
				'title'      => esc_html( WLSM_M_Staff_Class::get_name_text( $event->title ) ),
				'event_date' => esc_html( WLSM_Config::get_date_text( $event->event_date ) ),
			);

		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Unjoin event.
	public static function student_unjoin_event( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$wpdb->query( 'BEGIN;' );

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$params = $request->get_params();

			$event_id = isset( $params['event_id'] ) ? absint( $params['event_id'] ) : 0;

			$event = WLSM_M_Staff_Class::fetch_active_event( $school_id, $event_id, $student_id );

			if ( ! $event ) {
				throw new Exception( esc_html__( 'Event not found.', 'school-management' ) );
			}

			if ( ! $event->student_joined ) {
				throw new Exception( esc_html__( 'You have not joined this event.', 'school-management' ) );
			}

			$event_response_id = $event->event_response_id;

			$success = $wpdb->delete( WLSM_EVENT_RESPONSES, array( 'ID' => $event_response_id ) );

			WLSM_Helper::check_buffer();

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			$success = true;
			$message = esc_html__( 'You have left from this event.', 'school-management' );

			$response_data['event'] = array(
				'id'         => $event->ID,
				'title'      => esc_html( WLSM_M_Staff_Class::get_name_text( $event->title ) ),
				'event_date' => esc_html( WLSM_Config::get_date_text( $event->event_date ) ),
			);

		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Class time table.
	public static function student_class_time_table( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$section_id = $student->section_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$section = WLSM_M_Staff_Class::get_school_section( $school_id, $student->section_id );

			if ( ! $section ) {
				die;
			}

			$class_label   = $section->class_label;
			$section_label = $section->label;

			$data = array();

			foreach ( WLSM_Helper::days_list() as $key => $day ) {
				$routines = WLSM_M_Staff_Class::get_section_routines_by_day( $school_id, $section_id, $key );

				$day_routine = array(
					'day' => $day
				);

				$routines_data = array();
				foreach ( $routines as $routine ) {
					$routine_data = array();
					$routine_data['subject'] = sprintf(
						wp_kses(
							/* translators: 1: subject label, 2: subject code */
							_x( '%1$s (%2$s)', 'Subject', 'school-management' ),
							array( 'span' => array( 'class' => array() ) )
						),
						esc_html( WLSM_M_Staff_Class::get_subject_label_text( $routine->subject_label ) ),
						esc_html( $routine->subject_code )
					);

					$routine_data['start_time'] = esc_html( WLSM_Config::get_time_text( $routine->start_time ) );
					$routine_data['end_time']   = esc_html( WLSM_Config::get_time_text( $routine->end_time ) );

					$routine_data['room'] = esc_html( $routine->room_number );

					if ( $routine->teacher_name ) {
						$routine_data['teacher'] = esc_html( WLSM_M_Staff_Class::get_name_text( $routine->teacher_name ) );
					}

					array_push( $routines_data, $routine_data );
				}

				$day_routine['routines'] = $routines_data;

				array_push( $data, $day_routine );
			}

			$success = true;
			$message = esc_html__( 'Class time table retrieved successfully.', 'school-management' );

			$response_data['class_time_table'] = array(
				'class'   => esc_html( WLSM_M_Class::get_label_text( $class_label ) ),
				'section' => esc_html( WLSM_M_Staff_Class::get_section_label_text( $section_label ) ),
				'data'    => $data
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Books issued.
	public static function student_books_issued( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			// Query.
			$books_issued_query = WLSM_M::books_issued_query();

			// Total.
			$books_issued_total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM ({$books_issued_query}) AS combined_table", $school_id, $session_id, $student->ID ) );

			// Current page and per page.
			$books_issued_page     = isset( $params['books_issued_page'] ) ? absint( $params['books_issued_page'] ) : 1;
			$books_issued_per_page = isset( $params['books_issued_per_page'] ) ? absint( $params['books_issued_per_page'] ) : WLSM_M::books_issued_per_page();

			// Page offset.
			$books_issued_page_offset = ( $books_issued_page * $books_issued_per_page ) - $books_issued_per_page;

			// Paginated data.
			$books_issued = $wpdb->get_results( $wpdb->prepare( $books_issued_query . ' ORDER BY bki.date_issued DESC LIMIT %d, %d', $school_id, $session_id, $student->ID, $books_issued_page_offset, $books_issued_per_page ) );

			// Paginated response.
			$books_issued_data       = array();
			$books_issued_pagination = array(
				'current_page' => $books_issued_page,
				'per_page'     => $books_issued_per_page,
			);

			// Format response.
			if ( count( $books_issued ) ) {
				foreach ( $books_issued as $key => $row ) {
					$books_issued_data[] = array(
						'id'              => $row->ID,
						'book_title'      => esc_html( WLSM_M_Staff_Library::get_book_title( $row->title ) ),
						'issued_quantity' => esc_html( WLSM_M_Staff_Library::get_book_quantity( $row->issued_quantity ) ),
						'date_issued'     => esc_html( WLSM_Config::get_date_text( $row->date_issued ) ),
						'return_date'     => esc_html( WLSM_Config::get_date_text( $row->return_date ) ),
						'return_status'   => strip_tags( WLSM_M_Staff_Library::get_book_issued_status_text( $row->returned_at ) ),
						'returned_at'     => esc_html( WLSM_Config::get_date_text( $row->returned_at ) ),
						'author'          => esc_html( WLSM_M_Staff_Library::get_book_author( $row->author ) ),
						'subject'         => esc_html( WLSM_M_Staff_Library::get_book_subject( $row->subject ) ),
						'rack_number'     => esc_html( WLSM_M_Staff_Library::get_book_rack_number( $row->rack_number ) ),
						'book_number'     => esc_html( WLSM_M_Staff_Library::get_book_number( $row->book_number ) ),
						'isbn_number'     => esc_html( WLSM_M_Staff_Library::get_book_isbn_number( $row->isbn_number ) ),
					);
				}

				$books_issued_pagination['total_pages']   = ceil( $books_issued_total / $books_issued_per_page );
				$books_issued_pagination['total_records'] = $books_issued_total;
			}

			$success = true;
			$message = esc_html__( 'Books issued retrieved successfully.', 'school-management' );

			$response_data['books_issued'] = array(
				'data'       => $books_issued_data,
				'pagination' => $books_issued_pagination
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Live classes.
	public static function student_live_classes( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$school_id  = $student->school_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			// Query.
			$meetings_query = WLSM_M::meetings_query();

			// Total.
			$meetings_total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM ({$meetings_query}) AS combined_table", $school_id, $class_school_id ) );

			// Current page and per page.
			$meetings_page     = isset( $params['live_classes_page'] ) ? absint( $params['live_classes_page'] ) : 1;
			$meetings_per_page = isset( $params['live_classes_per_page'] ) ? absint( $params['live_classes_per_page'] ) : WLSM_M::meetings_per_page();

			// Page offset.
			$meetings_page_offset = ( $meetings_page * $meetings_per_page ) - $meetings_per_page;

			// Paginated data.
			$meetings = $wpdb->get_results( $wpdb->prepare( $meetings_query . ' ORDER BY mt.start_at DESC, mt.ID DESC LIMIT %d, %d', $school_id, $class_school_id, $meetings_page_offset, $meetings_per_page ) );

			// Paginated response.
			$meetings_data       = array();
			$meetings_pagination = array(
				'current_page' => $meetings_page,
				'per_page'     => $meetings_per_page,
			);

			// Format response.
			if ( count( $meetings ) ) {
				foreach ( $meetings as $key => $row ) {

					$sdk_key =  get_the_author_meta( 'sdk_key', $row->user_id ) ;
					$sdk_secret =  get_the_author_meta( 'sdk_secret', $row->user_id ) ;
					$api_key =  get_the_author_meta( 'api_key', $row->user_id ) ;
					$api_secret =  get_the_author_meta( 'api_secret', $row->user_id ) ;

					$meetings_data[] = array(
						'id'              => $row->ID,
						'meeting_id'      => $row->meeting_id,
						'topic'           => esc_html( $row->topic ),
						'duration'        => esc_html( $row->duration ),
						'start_date_time' => esc_html( WLSM_Config::get_at_text( $row->start_at ) ),
						'type'            => esc_html( WLSM_Helper::get_meeting_type( $row->type ) ),
						'join_url'        => esc_url( $row->join_url ),
						'password'        => esc_html( $row->password ),
						'subject'         => esc_html( $row->subject_name ),
						'teacher'         => esc_html( $row->name ),
						'sdk_key'         => esc_html( $sdk_key),
						'sdk_secret'      => esc_html( $sdk_secret ),
						'api_key'         => esc_html( $api_key),
						'api_secret'      => esc_html( $api_secret ),
					);
				}

				$meetings_pagination['total_pages']   = ceil( $meetings_total / $meetings_per_page );
				$meetings_pagination['total_records'] = $meetings_total;
			}

			$success = true;
			$message = esc_html__( 'Live classes retrieved successfully.', 'school-management' );

			$response_data['live_classes'] = array(
				'data'       => $meetings_data,
				'pagination' => $meetings_pagination
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Attendance.
	public static function student_attendance( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$section_id = $student->section_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$attendance = WLSM_M_Staff_General::get_student_attendance_report( $student_id );

			$data = array();

			$total_attendance = 0;
			$total_present    = 0;
			$total_absent     = 0;

			$attendance_monthly = array();
			foreach ( $attendance as $monthly ) {
				$month = new DateTime();
				$month->setDate( $monthly->year, $monthly->month, 1 );
				$total_attendance += $monthly->total_attendance;
				$total_present    += $monthly->total_present;
				$total_absent     += $monthly->total_absent;

				$attendance_data = array(
					'month'            => esc_html( $month->format( 'F Y' ) ),
					'total_attendance' => esc_html( $monthly->total_attendance ),
					'total_present'    => esc_html( $monthly->total_present ),
					'total_absent'     => esc_html( $monthly->total_absent ),
					'total_holidays'   => esc_html( $monthly->total_holiday ),
					'total_late'       => esc_html( $monthly->total_late ),
				);

				array_push( $attendance_monthly, $attendance_data );
			}

			$data['overall'] = array(
				'total_attendance' => $total_attendance,
				'total_present'    => $total_present,
				'total_absent'     => $total_absent,
				'percentage_value' => WLSM_Config::sanitize_percentage( $total_attendance, $total_present, 1 ),
				'percentage_text'  => WLSM_Config::get_percentage_text( $total_attendance, $total_present, 1 )
			);

			$data['monthly'] = $attendance_monthly;

			$success = true;
			$message = esc_html__( 'Attendance retrieved successfully.', 'school-management' );

			$response_data['attendance'] = $data;

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Study materials.
	public static function student_study_materials( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			// Query.
			$study_materials_query = WLSM_M::study_materials_query();

			// Total.
			$study_materials_total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM ({$study_materials_query}) AS combined_table", $class_school_id ) );

			// Current page and per page.
			$study_materials_page     = isset( $params['study_materials_page'] ) ? absint( $params['study_materials_page'] ) : 1;
			$study_materials_per_page = isset( $params['study_materials_per_page'] ) ? absint( $params['study_materials_per_page'] ) : WLSM_M::study_materials_per_page();

			// Page offset.
			$study_materials_page_offset = ( $study_materials_page * $study_materials_per_page ) - $study_materials_per_page;

			// Paginated data.
			$study_materials = $wpdb->get_results( $wpdb->prepare( $study_materials_query . ' ORDER BY cssm.ID DESC LIMIT %d, %d', $class_school_id, $study_materials_page_offset, $study_materials_per_page ) );

			// Paginated response.
			$study_materials_data       = array();
			$study_materials_pagination = array(
				'current_page' => $study_materials_page,
				'per_page'     => $study_materials_per_page,
			);

			// Format response.
			if ( count( $study_materials ) ) {
				foreach ( $study_materials as $key => $study_material ) {
					$study_materials_data[] = array(
						'id'    => $study_material->ID,
						'title' => esc_html( stripslashes( $study_material->title ) ),
						'date'  => esc_html( WLSM_Config::get_date_text( $study_material->created_at ) ),
					);
				}

				$study_materials_pagination['total_pages']   = ceil( $study_materials_total / $study_materials_per_page );
				$study_materials_pagination['total_records'] = $study_materials_total;
			}

			$success = true;
			$message = esc_html__( 'Study materials retrieved successfully.', 'school-management' );

			$response_data['study_materials'] = array(
				'data'       => $study_materials_data,
				'pagination' => $study_materials_pagination
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Study material.
	public static function student_study_material( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			$study_material_id = isset( $params['study_material_id'] ) ? absint( $params['study_material_id'] ) : 0;

			$study_material = $wpdb->get_row( $wpdb->prepare( WLSM_M::study_material_query(), $class_school_id, $study_material_id ) );

			if ( ! $study_material ) {
				throw new Exception( esc_html__( 'Study material not found.', 'school-management' ) );
			}

			$attachments = $study_material->attachments;
			if ( is_serialized( $attachments ) ) {
				$attachments = unserialize( $attachments );
			} else {
				if ( ! is_array( $attachments ) ) {
					$attachments = array();
				}
			}

			$attachments_data = array();
			if ( count( $attachments ) ) {
				foreach ( $attachments as $attachment ) {
					if ( ! empty ( $attachment ) ) {
						$file_name = basename ( get_attached_file( $attachment ) );
						array_push(
							$attachments_data,
							array(
								'file_name' => esc_html( $file_name ),
								'url'       => esc_url( wp_get_attachment_url( $attachment ) )
							)
						);
					}
				}
			}

			$success = true;
			$message = esc_html__( 'Study material retrieved successfully.', 'school-management' );

			$response_data['study_material'] = array(
				'id'           => $study_material->ID,
				'title'        => esc_html( stripslashes( $study_material->title ) ),
				'description'  => esc_html( stripslashes( $study_material->description ) ),
				'downloadable' => (intval( $study_material->downloadable ) ),
				'date'         => esc_html( WLSM_Config::get_date_text( $study_material->created_at ) ),
				'url'          => esc_url($study_material->url),
				'attachments'  => $attachments_data
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Homeworks.
	public static function student_homeworks( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$section_id = $student->section_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			// Query.
			$homeworks_query = WLSM_M::homeworks_query();

			// Total.
			$homeworks_total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM ({$homeworks_query}) AS combined_table", $school_id, $session_id, $section_id ) );

			// Current page and per page.
			$homeworks_page     = isset( $params['homeworks_page'] ) ? absint( $params['homeworks_page'] ) : 1;
			$homeworks_per_page = isset( $params['homeworks_per_page'] ) ? absint( $params['homeworks_per_page'] ) : WLSM_M::homeworks_per_page();

			// Page offset.
			$homeworks_page_offset = ( $homeworks_page * $homeworks_per_page ) - $homeworks_per_page;

			// Paginated data.
			$homeworks = $wpdb->get_results( $wpdb->prepare( $homeworks_query . ' ORDER BY hw.homework_date DESC LIMIT %d, %d', $school_id, $session_id, $section_id, $homeworks_page_offset, $homeworks_per_page ) );

			// Paginated response.
			$homeworks_data       = array();
			$homeworks_pagination = array(
				'current_page' => $homeworks_page,
				'per_page'     => $homeworks_per_page,
			);

			// Format response.
			if ( count( $homeworks ) ) {
				foreach ( $homeworks as $key => $homework ) {
					$homeworks_data[] = array(
						'id'    => $homework->ID,
						'title' => esc_html( stripslashes( $homework->title ) ),
						'date'  => esc_html( WLSM_Config::get_date_text( $homework->homework_date ) ),
					);
				}

				$homeworks_pagination['total_pages']   = ceil( $homeworks_total / $homeworks_per_page );
				$homeworks_pagination['total_records'] = $homeworks_total;
			}

			$success = true;
			$message = esc_html__( 'Homework retrieved successfully.', 'school-management' );

			$response_data['homeworks'] = array(
				'data'       => $homeworks_data,
				'pagination' => $homeworks_pagination
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Homework.
	public static function student_homework( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$section_id = $student->section_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			$homework_id = isset( $params['homework_id'] ) ? absint( $params['homework_id'] ) : 0;

			$homework = $wpdb->get_row( $wpdb->prepare( WLSM_M::homework_query(), $school_id, $session_id, $section_id, $homework_id ) );

			$homework_status = $wpdb->get_row( $wpdb->prepare( WLSM_M::homework_query_submission(), $school_id, $session_id, $section_id, $homework_id, $student_id ) );

			if ( ! $homework ) {
				throw new Exception( esc_html__( 'Homework not found.', 'school-management' ) );
			}

			$attachments = $homework->attachments;
						if (is_serialized($attachments)) {
							$attachments = unserialize($attachments);
						} else {
							if (!is_array($attachments)) {
								$attachments = array();
							}
						}
						foreach ($attachments as $attachment) {
							if (!empty($attachment)) {
								$file_name = basename(get_attached_file($attachment));
								$file_link = wp_get_attachment_url($attachment);
						}
					}
			if( $homework_status){
				$sub_date = $homework_status->created_at;
				$update_date = $homework_status->updated_at;
				$status = 1;
			}else {
				$status = 0;
			}

			$success = true;
			$message = esc_html__( 'Homework retrieved successfully.', 'school-management' );

			$response_data['homework'] = array(
				'id'              => $homework->ID,
				'title'           => esc_html( stripslashes( $homework->title ) ),
				'description'     => esc_html( stripslashes( $homework->description ) ),
				'downloadable'     => intval( $homework->downloadable ),
				'date'            => esc_html( WLSM_Config::get_date_text( $homework->homework_date ) ),
				'attachment_link' => ( $file_link ),
				'attachment_name' => ( $file_name ),
				'Submitted'       => (  $status ),
				'Submitted_date'  => (  $sub_date ),
				'updated_date'    => (  $update_date ),

			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Submit homework.
	public static function student_submit_homework_request( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$wpdb->query( 'BEGIN;' );

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			$submission_id       = isset($_POST['submission_id']) ? absint($_POST['submission_id']): 0;
			$description = isset($_POST['description']) ? sanitize_text_field($_POST['description']): '';
			$homework_update_id = isset($_POST['homework_update']) ? sanitize_text_field($_POST['homework_update']): '';
			$homework_sub_id = isset($_POST['homework_sub_id']) ? sanitize_text_field($_POST['homework_sub_id']): '';
			$attachment = ( isset( $_POST['attachments'] ) && sanitize_text_field( $_POST['attachments'] ) ) ? $_POST['attachments'] : NULL;

			if (empty($submission_id)) {
				$errors['submission_id'] = esc_html__('Please Enter Submission Subject', 'school-management');
			}

			if (empty($description)) {
				$errors['description'] = esc_html__('Please Enter discription', 'school-management');
			}



			if (isset($attachment['tmp_name']) && !empty($attachment['tmp_name'])) {
				if (!WLSM_Helper::is_valid_file($attachment, 'attachment')) {
					$errors['attachment'] = esc_html__('Please provide attachment PDF format.', 'school-management');
				}
			}
			$title = rand(3, 5);
			// Upload dir.
			$upload_dir  = wp_upload_dir();
			$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

			$img             = str_replace( 'data:image/jpeg;base64,', '', $attachment );
			$img             = str_replace( ' ', '+', $img );
			$decoded         = base64_decode( $img );
			$filename        = $title . '.jpeg';
			$file_type       = 'image/jpeg';
			$hashed_filename = md5( $filename . microtime() ) . '_' . $filename;

			// Save the image in the uploads directory.
			$upload_file = file_put_contents( $upload_path . $hashed_filename, $decoded );

			$attachment = array(
				'post_mime_type' => $file_type,
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $hashed_filename ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
				'guid'           => $upload_dir['url'] . '/' . basename( $hashed_filename )
			);

			$attach_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $hashed_filename );

			// Student leave data.
			$data = array(
                'submission_id' => $submission_id,
                'description'   => $description,
                'school_id'     => $school_id,
                'session_id'    => $session_id,
                'student_id'    => $student_id,
                'attachments'    => $attach_id,
            );

            $data['created_at'] = current_time('Y-m-d H:i:s');
				if ($homework_update_id) {
					$success = $wpdb->update(WLSM_HOMEWORK_SUBMISSION, $data, array(
					'ID'            => $homework_sub_id,
					'submission_id' => $submission_id,
				));
				}else{
					$success = $wpdb->insert(WLSM_HOMEWORK_SUBMISSION, $data );
				}

			WLSM_Helper::check_buffer();

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$homework_id = $wpdb->insert_id;

			$wpdb->query( 'COMMIT;' );

			$success = true;
			$message = esc_html__( 'homework submitted successfully.', 'school-management' );

			$response_data['homework'] = array(
				'id'          => $homework_id,
				'description' => esc_html( ( $description ) ),
			);

		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Exams time table.
	public static function exams_time_table( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$exams = WLSM_M_Staff_Examination::get_class_school_exams_time_table( $school_id, $class_school_id );

			$exams_data = array();

			if ( count( $exams ) ) {
				foreach ( $exams as $key => $exam ) {
					$exams_data[] = array(
						'id'         => $exam->ID,
						'title'      => esc_html( stripslashes( $exam->exam_title ) ),
						'start_date' => esc_html( WLSM_Config::get_date_text( $exam->start_date ) ),
						'end_date'   => esc_html( WLSM_Config::get_date_text( $exam->end_date ) )
					);
				}
			}

			$success = true;
			$message = esc_html__( 'Exams retrieved successfully.', 'school-management' );

			$response_data['exams'] = array(
				'data' => $exams_data,
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Exam time table.
	public static function exam_time_table( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$section_id = $student->section_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			$exam_id = isset( $params['exam_id'] ) ? absint( $params['exam_id'] ) : 0;

			$exam = WLSM_M_Staff_Examination::get_class_school_exam_time_table( $school_id, $class_school_id, $exam_id );

			if ( ! $exam ) {
				throw new Exception( esc_html__( 'Exam not found.', 'school-management' ) );
			}

			$exam_classes = WLSM_M_Staff_Examination::fetch_exam_classes_label( $school_id, $exam_id );
			$exam_papers  = WLSM_M_Staff_Examination::fetch_exam_papers( $school_id, $exam_id );

			$exam_title = $exam->exam_title;
			$start_date = $exam->start_date;
			$end_date   = $exam->end_date;

			$class_names = array();
			foreach ( $exam_classes as $exam_class ) {
				array_push( $class_names, WLSM_M_Class::get_label_text( $exam_class->label ) );
			}

			$class_names = implode( ', ', $class_names );

			$data = array();

			foreach ( $exam_papers as $key => $exam_paper ) {
				$exam_data = array();

				$exam_data['subject']    = esc_html( stripcslashes( $exam_paper->subject_label ) );
				$exam_data['paper_code'] = esc_html( $exam_paper->paper_code );
				$exam_data['paper_date'] = esc_html( WLSM_Config::get_date_text( $exam_paper->paper_date ) );
				$exam_data['start_time'] = esc_html( WLSM_Config::get_time_text( $exam_paper->start_time ) );
				$exam_data['end_time']   = esc_html( WLSM_Config::get_time_text( $exam_paper->end_time ) );

				if ( $exam->enable_room_numbers ) {
					$exam_data['room_number'] = esc_html( $exam_paper->room_number );
				}

				array_push( $data, $exam_data );
			}

			$success = true;
			$message = esc_html__( 'Exam time table retrieved successfully.', 'school-management' );

			$response_data['exam'] = array(
				'title'            => esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ),
				'start_date'       => esc_html( WLSM_Config::get_date_text( $start_date ) ),
				'end_date'         => esc_html( WLSM_Config::get_date_text( $end_date ) ),
				'class'            => esc_html( $class_names ),
				'show_room_number' => (bool) $exam->enable_room_numbers,
				'data'             => $data,
				'exam_center'      => esc_html( WLSM_M_Staff_Examination::get_exam_center_text( $exam->exam_center ) )
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Admit cards.
	public static function admit_cards( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$admit_cards = WLSM_M_Staff_Examination::get_student_admit_cards( $school_id, $student->ID );

			$admit_cards_data = array();

			if ( count( $admit_cards ) ) {
				foreach ( $admit_cards as $key => $admit_card ) {
					$admit_cards_data[] = array(
						'id'         => $admit_card->ID,
						'exam_title' => esc_html( stripslashes( $admit_card->exam_title ) ),
						'start_date' => esc_html( WLSM_Config::get_date_text( $admit_card->start_date ) ),
						'end_date'   => esc_html( WLSM_Config::get_date_text( $admit_card->end_date ) )
					);
				}
			}

			$success = true;
			$message = esc_html__( 'Admit cards retrieved successfully.', 'school-management' );

			$response_data['admit_cards'] = array(
				'data' => $admit_cards_data,
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Admit card.
	public static function admit_card( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			$admit_card_id = isset( $params['admit_card_id'] ) ? absint( $params['admit_card_id'] ) : 0;

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

			$exam_id    = $exam->ID;
			$exam_title = $exam->exam_title;
			$start_date = $exam->start_date;
			$end_date   = $exam->end_date;

			$exam_classes = WLSM_M_Staff_Examination::fetch_exam_classes_label( $school_id, $exam_id );
			$exam_papers  = WLSM_M_Staff_Examination::fetch_exam_papers( $school_id, $exam_id );

			$class_names = array();
			foreach ( $exam_classes as $exam_class ) {
				array_push( $class_names, WLSM_M_Class::get_label_text( $exam_class->label ) );
			}

			$class_names = implode( ', ', $class_names );

			$photo_id = $admit_card->photo_id;

			$data = array();

			foreach ( $exam_papers as $key => $exam_paper ) {
				$exam_data = array();

				$exam_data['subject']    = esc_html( stripcslashes( $exam_paper->subject_label ) );
				$exam_data['paper_code'] = esc_html( $exam_paper->paper_code );
				$exam_data['paper_date'] = esc_html( WLSM_Config::get_date_text( $exam_paper->paper_date ) );
				$exam_data['start_time'] = esc_html( WLSM_Config::get_time_text( $exam_paper->start_time ) );
				$exam_data['end_time']   = esc_html( WLSM_Config::get_time_text( $exam_paper->end_time ) );

				if ( $exam->enable_room_numbers ) {
					$exam_data['room_number'] = esc_html( $exam_paper->room_number );
				}

				array_push( $data, $exam_data );
			}

			$success = true;
			$message = esc_html__( 'Admit card retrieved successfully.', 'school-management' );

			$response_data['exam'] = array(
				'title'            => esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ),
				'start_date'       => esc_html( WLSM_Config::get_date_text( $start_date ) ),
				'end_date'         => esc_html( WLSM_Config::get_date_text( $end_date ) ),
				'class'            => esc_html( $class_names ),
				'show_room_number' => (bool) $exam->enable_room_numbers,
				'data'             => $data,
				'exam_center'      => esc_html( WLSM_M_Staff_Examination::get_exam_center_text( $exam->exam_center ) )
			);

			$response_data['admit_card'] = array(
				'student_name'      => esc_html( WLSM_M_Staff_Class::get_name_text( $admit_card->name ) ),
				'enrollment_number' => esc_html( $admit_card->enrollment_number ),
				'session_label'     => esc_html( WLSM_M_Session::get_label_text( $admit_card->session_label ) ),
				'class'             => esc_html( WLSM_M_Class::get_label_text( $admit_card->class_label ) ),
				'section'           => esc_html( WLSM_M_Class::get_label_text( $admit_card->section_label ) ),
				'roll_number'       => esc_html( WLSM_M_Staff_Class::get_roll_no_text( $admit_card->roll_number ) ),
				'phone'             => esc_html( WLSM_M_Staff_Class::get_phone_text( $admit_card->phone ) ),
				'email'             => esc_html( WLSM_M_Staff_Class::get_name_text( $admit_card->email ) ),
				'photo'             => esc_url( wp_get_attachment_url( $photo_id ) )
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Exams results.
	public static function exam_results( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$exam_results = WLSM_M_Staff_Examination::get_student_published_exam_results( $school_id, $student_id );

			$results_data = array();

			if ( count( $exam_results ) ) {
				foreach ( $exam_results as $key => $value ) {
					$results_data[] = array(
						'id'         => $value->ID,
						'title'      => esc_html( stripslashes( $value->exam_title ) ),
						'start_date' => esc_html( WLSM_Config::get_date_text( $value->start_date ) ),
						'end_date'   => esc_html( WLSM_Config::get_date_text( $value->end_date ) )
					);
				}
			}

			$success = true;
			$message = esc_html__( 'Exam results retrieved successfully.', 'school-management' );

			$response_data['results'] = array(
				'data' => $results_data,
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Exam result.
	public static function exam_result( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			$admit_card_id = isset( $params['admit_card_id'] ) ? absint( $params['admit_card_id'] ) : 0;

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

			$exam_papers  = WLSM_M_Staff_Examination::get_exam_papers_by_admit_card( $school_id, $admit_card_id );
			$exam_results = WLSM_M_Staff_Examination::get_exam_results_by_admit_card( $school_id, $admit_card_id );

			$grade_criteria = WLSM_Config::sanitize_grade_criteria( $exam->grade_criteria );

			$enable_overall_grade = $grade_criteria['enable_overall_grade'];
			$marks_grades         = $grade_criteria['marks_grades'];

			$show_marks_grades = count( $marks_grades );

			$student_rank = WLSM_M_Staff_Examination::calculate_exam_ranks( $school_id, $exam_id, array(), $admit_card->ID );

			$data = array();

			$total_maximum_marks  = 0;
			$total_obtained_marks = 0;

			foreach ( $exam_papers as $key => $exam_paper ) {
				$results_data = array();

				if ( $admit_card && isset( $exam_results[ $exam_paper->ID ] ) ) {
					$exam_result    = $exam_results[ $exam_paper->ID ];
					$obtained_marks = $exam_result->obtained_marks;
				} else {
					$obtained_marks = '';
				}

				$percentage = WLSM_Config::sanitize_percentage( $exam_paper->maximum_marks, WLSM_Config::sanitize_marks( $obtained_marks ) );

				$total_maximum_marks  += $exam_paper->maximum_marks;
				$total_obtained_marks += WLSM_Config::sanitize_marks( $obtained_marks );

				$results_data['paper_code']     = esc_html( $exam_paper->paper_code );
				$results_data['subject_name']   = esc_html( stripcslashes( $exam_paper->subject_label ) );
				$results_data['subject_type']   = esc_html( WLSM_Helper::get_subject_type_text( $exam_paper->subject_type ) );
				$results_data['maximum_marks']  = esc_html( $exam_paper->maximum_marks );
				$results_data['obtained_marks'] = esc_html( $obtained_marks );

				if ( $show_marks_grades ) {
					$results_data['grade']  = esc_html( WLSM_Helper::calculate_grade( $marks_grades, $percentage ) );
				}

				array_push( $data, $results_data );
			}

			$total_percentage = WLSM_Config::sanitize_percentage( $total_maximum_marks, $total_obtained_marks );

			$success = true;
			$message = esc_html__( 'Exam result retrieved successfully.', 'school-management' );

			$response_data['result'] = array(
				'title'                => esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ),
				'start_date'           => esc_html( WLSM_Config::get_date_text( $start_date ) ),
				'end_date'             => esc_html( WLSM_Config::get_date_text( $end_date ) ),
				'student_name'         => esc_html( WLSM_M_Staff_Class::get_name_text( $admit_card->name ) ),
				'enrollment_number'    => esc_html( WLSM_M_Staff_Class::get_roll_no_text( $admit_card->enrollment_number ) ),
				'session'              => esc_html( WLSM_M_Session::get_label_text( $admit_card->session_label ) ),
				'class'                => esc_html( WLSM_M_Class::get_label_text( $admit_card->class_label ) ),
				'section'              => esc_html( WLSM_M_Class::get_label_text( $admit_card->section_label ) ),
				'roll_number'          => esc_html( WLSM_M_Staff_Class::get_roll_no_text( $admit_card->roll_number ) ),
				'show_marks_grades'    => (bool) $show_marks_grades,
				'show_overall_grade'   => (bool) $enable_overall_grade,
				'data'                 => $data,
				'total_maximum_marks'  => $total_maximum_marks,
				'total_obtained_marks' => $total_obtained_marks,
				'percentage_value'     => esc_html( $total_percentage ),
				'percentage_text'      => esc_html( WLSM_Config::get_percentage_text( $total_maximum_marks, $total_obtained_marks ) ),
			);

			if ( $show_marks_grades && $enable_overall_grade ) {
				$response_data['result']['overall_grade'] = esc_html( WLSM_Helper::calculate_grade( $marks_grades, $total_percentage ) );
			}

			$response_data['result']['student_rank'] = esc_html( $student_rank );

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Overall result.
	public static function overall_result( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			// Checks if student exists.
			$student = WLSM_M_Staff_General::fetch_student( $school_id, $session_id, $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$admit_cards = WLSM_M_Staff_Examination::get_student_exam_results_assessment( $school_id, $student_id );

			$results_data = array();

			$overall_maximum_marks  = 0;
			$overall_obtained_marks = 0;
			foreach ( $admit_cards as $admit_card ) {
				$exam_id       = $admit_card->exam_id;
				$exam_title    = $admit_card->exam_title;
				$start_date    = $admit_card->start_date;
				$end_date      = $admit_card->end_date;
				$admit_card_id = $admit_card->ID;

				$exam_papers  = WLSM_M_Staff_Examination::get_exam_papers_by_admit_card( $school_id, $admit_card_id );
				$exam_results = WLSM_M_Staff_Examination::get_exam_results_by_admit_card( $school_id, $admit_card_id );

				$total_maximum_marks  = 0;
				$total_obtained_marks = 0;

				foreach ( $exam_papers as $key => $exam_paper ) {
					if ( $admit_card && isset( $exam_results[ $exam_paper->ID ] ) ) {
						$exam_result    = $exam_results[ $exam_paper->ID ];
						$obtained_marks = $exam_result->obtained_marks;
					} else {
						$obtained_marks = '';
					}

					$percentage = WLSM_Config::sanitize_percentage( $exam_paper->maximum_marks, WLSM_Config::sanitize_marks( $obtained_marks ) );

					$total_maximum_marks  += $exam_paper->maximum_marks;
					$total_obtained_marks += WLSM_Config::sanitize_marks( $obtained_marks );
				}

				$total_percentage = WLSM_Config::sanitize_percentage( $total_maximum_marks, $total_obtained_marks );

				$overall_maximum_marks  += $total_maximum_marks;
				$overall_obtained_marks += WLSM_Config::sanitize_marks( $total_obtained_marks );

				$results_data[] = array(
					'id'               => $admit_card->ID,
					'title'            => esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ),
					'exam_date'        => esc_html( WLSM_Config::get_date_text( $start_date ) ),
					'maximum_marks'    => esc_html( $total_maximum_marks ),
					'obtained_marks'   => esc_html( $total_obtained_marks ),
					'percentage_value' => esc_html( WLSM_Config::sanitize_percentage( $total_maximum_marks, $total_obtained_marks ) ),
					'percentage_text'  => esc_html( WLSM_Config::get_percentage_text( $total_maximum_marks, $total_obtained_marks ) ),
				);
			}

			$success = true;
			$message = esc_html__( 'Overall result retrieved successfully.', 'school-management' );

			$response_data['result'] = array(
				'student_name'             => esc_html( WLSM_M_Staff_Class::get_name_text( $student->student_name ) ),
				'enrollment_number'        => esc_html( $student->enrollment_number ),
				'session'                  => esc_html( WLSM_M_Session::get_label_text( $session_label ) ),
				'class'                    => esc_html( WLSM_M_Class::get_label_text( $student->class_label ) ),
				'section'                  => esc_html( WLSM_M_Class::get_label_text( $student->section_label ) ),
				'roll_number'              => esc_html( WLSM_M_Staff_Class::get_roll_no_text( $student->roll_number ) ),
				'phone'                    => esc_html( WLSM_M_Staff_Class::get_phone_text( $student->phone ) ),
				'father_name'              => esc_html( WLSM_M_Staff_Class::get_name_text( $student->father_name ) ),
				'father_phone'             => esc_html( WLSM_M_Staff_Class::get_phone_text( $student->father_phone ) ),
				'data'                     => $results_data,
				'overall_maximum_marks'    => esc_html( $overall_maximum_marks ),
				'overall_obtained_marks'   => esc_html( $overall_obtained_marks ),
				'overall_percentage_value' => esc_html( WLSM_Config::sanitize_percentage( $overall_maximum_marks, $overall_obtained_marks ) ),
				'overall_percentage_text'  => esc_html( WLSM_Config::get_percentage_text( $overall_maximum_marks, $overall_obtained_marks ) ),
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Leave requests.
	public static function student_leave_requests( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			// Query.
			$leaves_query = WLSM_M::leaves_query();

			// Total.
			$leaves_total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM ({$leaves_query}) AS combined_table", $school_id, $session_id, $student_id ) );

			// Current page and per page.
			$leaves_page     = isset( $params['leaves_page'] ) ? absint( $params['leaves_page'] ) : 1;
			$leaves_per_page = isset( $params['leaves_per_page'] ) ? absint( $params['leaves_per_page'] ) : WLSM_M::leaves_per_page();

			// Page offset.
			$leaves_page_offset = ( $leaves_page * $leaves_per_page ) - $leaves_per_page;

			// Paginated data.
			$leaves = $wpdb->get_results( $wpdb->prepare( $leaves_query . ' ORDER BY lv.ID DESC LIMIT %d, %d', $school_id, $session_id, $student_id, $leaves_page_offset, $leaves_per_page ) );

			// Paginated response.
			$leaves_data       = array();
			$leaves_pagination = array(
				'current_page' => $leaves_page,
				'per_page'     => $leaves_per_page,
			);

			// Format response.
			if ( count( $leaves ) ) {
				foreach ( $leaves as $key => $leave ) {
					if ( $leave->end_date ) {
						$leave_date = sprintf(
							/* translators: 1: leave start date, 2: leave end date */
							esc_html__( '%1$s to %2$s', 'school-management' ),
							esc_html( WLSM_Config::get_date_text( $leave->start_date ) ),
							esc_html( WLSM_Config::get_date_text( $leave->end_date ) )
						);

					} else {
						$leave_date = esc_html( WLSM_Config::get_date_text( $leave->start_date ) );
					}

					$leaves_data[] = array(
						'id'         => $leave->ID,
						'reason'     => esc_html( WLSM_Config::limit_string( WLSM_M_Staff_Class::get_name_text( $leave->description ) ) ),
						'leave_date' => $leave_date,
						'approval'   => esc_html( WLSM_M_Staff_Class::get_leave_approval_text( $leave->is_approved ) )
					);
				}

				$leaves_pagination['total_pages']   = ceil( $leaves_total / $leaves_per_page );
				$leaves_pagination['total_records'] = $leaves_total;
			}

			$success = true;
			$message = esc_html__( 'Leave requests retrieved successfully.', 'school-management' );

			$response_data['leaves'] = array(
				'data'       => $leaves_data,
				'pagination' => $leaves_pagination
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Submit invoice payment request.
	public static function student_submit_invoice_payment_request( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$wpdb->query( 'BEGIN;' );

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			$invoice_id     = isset( $params['id'] ) ? sanitize_text_field( $params['id'] ) : '';
			$amount         = isset( $params['amount'] ) ? sanitize_text_field( $params['amount'] ) : '';
			$transaction_id = isset( $params['transaction_id'] ) ? sanitize_text_field( $params['transaction_id'] ) : '';
			$payment_method = isset( $params['payment_method'] ) ? sanitize_text_field( $params['payment_method'] ) : '';
			$attachment     = ( isset( $_POST['attachment'] ) && sanitize_text_field( $_POST['attachment'] ) ) ? $_POST['attachment'] : NULL;

			if ($payment_method == 'bank-transfer') {
				if (isset($attachment['tmp_name']) && !empty($attachment['tmp_name'])) {
					if (!WLSM_Helper::is_valid_file($attachment, 'attachment')) {
						$errors['attachment'] = esc_html__('Please provide attachment PDF format.', 'school-management');
					}
				}
				$title = rand(3, 5);
				// Upload dir.
				$upload_dir  = wp_upload_dir();
				$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

				$img             = str_replace( 'data:image/jpeg;base64,', '', $attachment );
				$img             = str_replace( ' ', '+', $img );
				$decoded         = base64_decode( $img );
				$filename        = $title . '.jpeg';
				$file_type       = 'image/jpeg';
				$hashed_filename = md5( $filename . microtime() ) . '_' . $filename;

				// Save the image in the uploads directory.
				$upload_file = file_put_contents( $upload_path . $hashed_filename, $decoded );

				$attachment = array(
					'post_mime_type' => $file_type,
					'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $hashed_filename ) ),
					'post_content'   => '',
					'post_status'    => 'inherit',
					'guid'           => $upload_dir['url'] . '/' . basename( $hashed_filename )
				);

				$attach_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $hashed_filename );
			}


			$receipt_number = WLSM_M_Invoice::get_receipt_number($school_id);
			$invoice = WLSM_M_Staff_Accountant::get_student_pending_invoice( $invoice_id );
			$data = array(
				'receipt_number'    => $receipt_number,
				'amount'            => $amount,
				'transaction_id'    => $transaction_id,
				'payment_method'    => $payment_method,
				'invoice_label'     => $invoice->invoice_title,
				'invoice_payable'   => $invoice->payable,
				'student_record_id' => $invoice->student_id,
				'invoice_id'        => $invoice_id,
				'school_id'         => $school_id,
				'added_by'          => 1,
			);
			$data['created_at'] = current_time('Y-m-d H:i:s');

			if ($payment_method == 'bank-transfer') {
				$receipt_number = WLSM_M_Invoice::get_receipt_number($school_id);
				$pending_payment_data = array(
					'receipt_number'    => $receipt_number,
					'amount'            => $amount,
					'payment_method'    => $payment_method,
					'transaction_id'    => $transaction_id,
					'invoice_label'     => $invoice->invoice_title,
					'invoice_payable'   => $invoice->payable,
					'student_record_id' => $invoice->student_id,
					'invoice_id'        => $invoice_id,
					'school_id'         => $school_id,
					'attachment'        => $attach_id,
				);
				$pending_payment_data['created_at'] = current_time( 'Y-m-d H:i:s' );
				$success = $wpdb->insert( WLSM_PENDING_PAYMENTS, $pending_payment_data );
			} else {
				$success = $wpdb->insert( WLSM_PAYMENTS, $data );
			}

			$invoice_status = WLSM_M_Staff_Accountant::refresh_invoice_status($invoice_id);

			if (WLSM_M_Invoice::get_paid_key() === $invoice_status && ($invoice_status !== $invoice->status)) {
				$reload = true;
			} else {
				$reload = false;
			}

			WLSM_Helper::check_buffer();

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$payment_id = $wpdb->insert_id;

			$wpdb->query( 'COMMIT;' );

			$success = true;
			$message = esc_html__( 'Request submitted successfully.', 'school-management' );

			$response_data['payment'] = array(
				'id'         => $payment_id,
				// 'reason'     => esc_html( WLSM_Config::limit_string( WLSM_M_Staff_Class::get_name_text( $description ) ) ),
				// 'leave_date' => esc_html( $leave_date ),
			);

		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Submit leave request.
	public static function student_submit_leave_request( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$wpdb->query( 'BEGIN;' );

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$params = $request->get_params();

			$description   = isset( $params['reason'] ) ? sanitize_text_field( $params['reason'] ) : '';
			$start_date    = isset( $params['start_date'] ) ? DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $params['start_date'] ) ) : NULL;
			$end_date      = isset( $params['end_date'] ) ? DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $params['end_date'] ) ) : NULL;
			$multiple_days = isset( $params['is_multiple_days'] ) ? (bool) $params['is_multiple_days'] : 0;

			$event = WLSM_M_Staff_Class::fetch_active_event( $school_id, $event_id, $student_id );

			if ( $multiple_days ) {
				if ( $start_date >= $end_date ) {
					throw new Exception( esc_html__( 'Start date must be lower than end date.', 'school-management' ) );
				}
			}

			if ( empty( $description ) ) {
				throw new Exception( esc_html__( 'Please specify reason.', 'school-management' ) );
			}

			if ( empty( $start_date ) ) {
				if ( $multiple_days ) {
					throw new Exception( esc_html__( 'Please specify leave start date.', 'school-management' ) );
				} else {
					throw new Exception( esc_html__( 'Please specify leave date.', 'school-management' ) );
				}
			} else {
				$start_date = $start_date->format( 'Y-m-d' );
			}

			if ( $multiple_days ) {
				if ( empty( $end_date ) ) {
					throw new Exception( esc_html__( 'Please specify leave end date.', 'school-management' ) );
				} else {
					$end_date = $end_date->format( 'Y-m-d' );
				}
			} else {
				$end_date = NULL;
			}

			// Student leave data.
			$data = array(
				'student_record_id' => $student_id,
				'description'       => $description,
				'start_date'        => $start_date,
				'end_date'          => $end_date,
				'school_id'         => $school_id,
			);

			$data['created_at'] = current_time( 'Y-m-d H:i:s' );

			$success = $wpdb->insert( WLSM_LEAVES, $data );

			WLSM_Helper::check_buffer();

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$leave_id = $wpdb->insert_id;

			$wpdb->query( 'COMMIT;' );

			$success = true;
			$message = esc_html__( 'Leave request submitted successfully.', 'school-management' );

			if ( $end_date ) {
				$leave_date = sprintf(
					/* translators: 1: leave start date, 2: leave end date */
					esc_html__( '%1$s to %2$s', 'school-management' ),
					esc_html( WLSM_Config::get_date_text( $start_date ) ),
					esc_html( WLSM_Config::get_date_text( $end_date ) )
				);

			} else {
				$leave_date = esc_html( WLSM_Config::get_date_text( $start_date ) );
			}

			$response_data['leave'] = array(
				'id'         => $leave_id,
				'reason'     => esc_html( WLSM_Config::limit_string( WLSM_M_Staff_Class::get_name_text( $description ) ) ),
				'leave_date' => esc_html( $leave_date ),
			);

		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - About school.
	public static function student_about_school( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$student = WLSM_M_User::user_is_student( $user_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$school_id = $student->school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$school = WLSM_M_School::fetch_school( $school_id );
			if ( ! $school ) {
				throw new Exception( esc_html__( 'School not found.', 'school-management' ) );
			}

			// General settings.
			$settings_general = WLSM_M_Setting::get_settings_general( $school_id );
			$school_logo      = $settings_general['school_logo'];

			$success = true;
			$message = esc_html__( 'School details retrieved successfully.', 'school-management' );

			$response_data['school'] = array(
				'name'    => esc_html( WLSM_M_School::get_label_text( $school->label ) ),
				'phone'   => esc_html( WLSM_M_School::get_phone_text( $school->phone ) ),
				'email'   => esc_html( WLSM_M_School::get_email_text( $school->email ) ),
				'address' => esc_html( WLSM_M_School::get_address_text( $school->address ) ),
				'logo'    => esc_url( wp_get_attachment_url( $school_logo ) ),
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Student - Common details.
	public static function student_common_details( $student ) {
		$school_data = array();

		$school_id = $student->school_id;

		$school = WLSM_M_School::fetch_school( $school_id );

		if ( $school ) {
			// General settings.
			$settings_general = WLSM_M_Setting::get_settings_general( $school_id );
			if(wp_get_attachment_url ($settings_general['school_logo'])){
				$school_logo = wp_get_attachment_url ($settings_general['school_logo']);
			} else {
				$school_logo = "";
			}


			$school_data = array(
				'name'        => esc_html( WLSM_M_School::get_label_text( $school->label ) ),
				'phone'       => esc_html( WLSM_M_School::get_phone_text( $school->phone ) ),
				'email'       => esc_html( WLSM_M_School::get_email_text( $school->email ) ),
				'address'     => esc_html( WLSM_M_School::get_address_text( $school->address ) ),
				'description' => esc_html( WLSM_M_School::get_address_text( $school->description ) ),
				'logo'        => esc_url( $school_logo ),
			);
		}

		return array(
			'id'     => esc_html( $student->ID ),
			'school' => $school_data,
		);
	}

	// Parent - Students.
	public static function parent_students( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$unique_student_ids = WLSM_M_Parent::get_parent_student_ids( $user_id );

			if ( ! count( $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Parent not found.', 'school-management' ) );
			}

			$students = WLSM_M_Parent::fetch_students( $unique_student_ids );

			$response_data = array();

			$students_data = array();

			if ( count( $students ) ) {
				foreach ( $students as $key => $student ) {
					$students_data[] = array(
						'id'                => $student->ID,
						'name'              => esc_html( WLSM_M_Staff_Class::get_name_text( $student->student_name ) ),
						'school'            => esc_html( WLSM_M_School::get_label_text( $student->school_name ) ),
						'session'           => esc_html( WLSM_M_Session::get_label_text( $student->session_label ) ),
						'class'             => esc_html( WLSM_M_Class::get_label_text( $student->class_label ) ),
						'section'           => esc_html( WLSM_M_Staff_Class::get_section_label_text( $student->section_label ) ),
						'admission_number'  => esc_html( WLSM_M_Staff_Class::get_admission_no_text( $student->admission_number ) ),
						'enrollment_number' => esc_html( $student->enrollment_number ),
						'roll_number'       => esc_html( WLSM_M_Staff_Class::get_roll_no_text( $student->roll_number ) ),
					);
				}
			}

			$success = true;
			$message = esc_html__( 'Students retrieved successfully.', 'school-management' );

			$response_data['students'] = array(
				'data' => $students_data,
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Parent - Noticeboard.
	public static function parent_noticeboard( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$unique_student_ids = WLSM_M_Parent::get_parent_student_ids( $user_id );

			if ( ! count( $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Parent not found.', 'school-management' ) );
			}

			$params = $request->get_params();

			$student_id = isset( $params['student_id'] ) ? absint( $params['student_id'] ) : 0;

			if ( $student_id && ! in_array( $student_id, $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student = WLSM_M_Parent::fetch_student( $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			// Query.
			$notices_query = WLSM_M::notices_query();

			// Total.
			$notices_total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM ({$notices_query}) AS combined_table", $class_school_id, $class_school_id, $school_id ) );

			// Current page and per page.
			$notices_page     = isset( $params['notices_page'] ) ? absint( $params['notices_page'] ) : 1;
			$notices_per_page = isset( $params['notices_per_page'] ) ? absint( $params['notices_per_page'] ) : WLSM_M::notices_per_page();

			// Page offset.
			$notices_page_offset = ( $notices_page * $notices_per_page ) - $notices_per_page;

			// Paginated data.
			$notices = $wpdb->get_results( $wpdb->prepare( $notices_query . ' ORDER BY n.ID DESC LIMIT %d, %d', $class_school_id, $class_school_id, $school_id, $notices_page_offset, $notices_per_page ) );

			// Paginated response.
			$notices_data       = array();
			$notices_pagination = array(
				'current_page' => $notices_page,
				'per_page'     => $notices_per_page,
			);

			// Format response.
			if ( count( $notices ) ) {
				$today = new DateTime();
				$today->setTime( 0, 0, 0 );

				foreach ( $notices as $key => $notice ) {
					$link_to = $notice->link_to;
					$link    = '#';

					if ( 'url' === $link_to ) {
						if ( ! empty ( $notice->url ) ) {
							$link = $notice->url;
						}
					} else if ( 'attachment' === $link_to ) {
						if ( ! empty ( $notice->attachment ) ) {
							$attachment = $notice->attachment;
							$link       = wp_get_attachment_url( $attachment );
						}
					} else {
						$link = '#';
					}

					$notice_date = DateTime::createFromFormat( 'Y-m-d H:i:s', $notice->created_at );
					$notice_date->setTime( 0, 0, 0 );

					$interval = $today->diff( $notice_date );

					$notices_data[] = array(
						'id'          => $notice->ID,
						'title'       => esc_html( stripslashes( $notice->title ) ),
						'description' => esc_html( stripslashes( $notice->description ) ),
						'link'        => esc_url( $link ),
						'date'        => esc_html( WLSM_Config::get_date_text( $notice->created_at ) ),
						'is_new'      => ( $interval->days < 7 ) ? true : false
					);
				}

				$notices_pagination['total_pages']   = ceil( $notices_total / $notices_per_page );
				$notices_pagination['total_records'] = $notices_total;
			}

			$success = true;
			$message = esc_html__( 'Noticeboard retrieved successfully.', 'school-management' );

			$response_data['noticeboard'] = array(
				'new_notice_icon' => esc_url( WLSM_PLUGIN_URL . 'assets/images/newicon.gif' ),
				'data'            => $notices_data,
				'pagination'      => $notices_pagination
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Parent - Fee Invoices.
	public static function parent_fee_invoices( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$unique_student_ids = WLSM_M_Parent::get_parent_student_ids( $user_id );

			if ( ! count( $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Parent not found.', 'school-management' ) );
			}

			$params = $request->get_params();

			$student_id = isset( $params['student_id'] ) ? absint( $params['student_id'] ) : 0;

			if ( $student_id && ! in_array( $student_id, $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student = WLSM_M_Parent::fetch_student( $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$invoices = WLSM_M_Staff_Accountant::get_student_pending_invoices( $student->ID );

			$invoices_data = array();

			if ( count( $invoices ) ) {
				foreach ( $invoices as $row ) {
					$due = $row->payable - $row->paid;
					$invoices_data[] = array(
						'id'              => $row->ID,
						'invoice_number'  => esc_html( $row->invoice_number ),
						'invoice_title'   => esc_html( WLSM_M_Staff_Accountant::get_invoice_title_text( $row->invoice_title ) ),
						'payable'         => esc_html( WLSM_Config::sanitize_money( $row->payable ) ),
						'payable_display' => esc_html( WLSM_Config::get_money_text( $row->payable, $school_id  ) ),
						'paid'            => esc_html( WLSM_Config::sanitize_money( $row->paid ) ),
						'paid_display'    => esc_html( WLSM_Config::get_money_text( $row->paid, $school_id  ) ),
						'due'             => esc_html( WLSM_Config::sanitize_money( $due ) ),
						'due_display'     => esc_html( WLSM_Config::get_money_text( $due, $school_id  ) ),
						'status'          => esc_html( $row->status ),
						'status_text'     => esc_html( WLSM_M_Invoice::get_status_text( $row->status, false ) ),
						'show_pay_now'    => WLSM_M_Invoice::get_paid_key() !== $row->status,
						'date_issued'     => esc_html( WLSM_Config::get_date_text( $row->date_issued ) ),
						'due_date'        => esc_html( WLSM_Config::get_date_text( $row->due_date ) ),
					);
				}
			}

			$success = true;
			$message = esc_html__( 'Fee invoices retrieved successfully.', 'school-management' );

			$response_data['invoices'] = array(
				'data' => $invoices_data
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Parent - Fee Invoice.
	public static function parent_fee_invoice( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$unique_student_ids = WLSM_M_Parent::get_parent_student_ids( $user_id );

			if ( ! count( $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Parent not found.', 'school-management' ) );
			}

			$params = $request->get_params();

			$student_id = isset( $params['student_id'] ) ? absint( $params['student_id'] ) : 0;

			if ( $student_id && ! in_array( $student_id, $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student = WLSM_M_Parent::fetch_student( $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$invoice_id = isset( $params['invoice_id'] ) ? absint( $params['invoice_id'] ) : 0;

			$invoice = WLSM_M_Staff_Accountant::get_student_pending_invoice( $invoice_id );

			if ( ! $invoice ) {
				throw new Exception( esc_html__( 'Invoice not found.', 'school-management' ) );
			}

			$due = $invoice->payable - $invoice->paid;

			$invoice_partial_payment = $invoice->partial_payment;

			$currency = WLSM_Config::currency($school_id);

			// Razorpay settings.
			$settings_razorpay      = WLSM_M_Setting::get_settings_razorpay( $school_id );
			$school_razorpay_enable = $settings_razorpay['enable'];

			// Stripe settings.
			$settings_stripe      = WLSM_M_Setting::get_settings_stripe( $school_id );
			$school_stripe_enable = $settings_stripe['enable'];

			// PayPal settings.
			$settings_paypal      = WLSM_M_Setting::get_settings_paypal( $school_id );
			$school_paypal_enable = $settings_paypal['enable'];

			// Pesapal settings.
			$settings_pesapal      = WLSM_M_Setting::get_settings_pesapal( $school_id );
			$school_pesapal_enable = $settings_pesapal['enable'];

			// Paystack settings.
			$settings_paystack      = WLSM_M_Setting::get_settings_paystack( $school_id );
			$school_paystack_enable = $settings_paystack['enable'];

			// Paytm settings.
			$settings_paytm      = WLSM_M_Setting::get_settings_paytm( $school_id );
			$school_paytm_enable = $settings_paytm['enable'];

			$success = true;
			$message = esc_html__( 'Fee invoice retrieved successfully.', 'school-management' );

			$response_data['invoice'] = array(
				'id'                      => $invoice->ID,
				'invoice_number'          => esc_html( $invoice->invoice_number ),
				'invoice_title'           => esc_html( WLSM_M_Staff_Accountant::get_invoice_title_text( $invoice->invoice_title ) ),
				'date_issued'             => esc_html( WLSM_Config::get_date_text( $invoice->date_issued ) ),
				'due_date'                => esc_html( WLSM_Config::get_date_text( $invoice->due_date ) ),
				'student_name'            => esc_html( WLSM_M_Staff_Class::get_name_text( $invoice->student_name ) ),
				'enrollment_number'       => esc_html( $invoice->enrollment_number ),
				'class'                   => esc_html( WLSM_M_Class::get_label_text( $invoice->class_label ) ),
				'section'                 => esc_html( WLSM_M_Staff_Class::get_section_label_text( $invoice->section_label ) ),
				'fees_due'                => esc_html( WLSM_Config::sanitize_money( $due ) ),
				'fees_due_display'        => esc_html( WLSM_Config::get_money_text( $due, $school_id  ) ),
				'partial_payment_allowed' => (bool) $invoice_partial_payment,
				'currency'                => $currency
			);

			$payment_methods = array();

			if ( $school_razorpay_enable && WLSM_Payment::currency_supports_razorpay( $currency ) ) {
				$payment_methods['razorpay'] = esc_html( WLSM_M_Invoice::get_payment_method_text( 'razorpay' ) );
			}

			if ( $school_stripe_enable && WLSM_Payment::currency_supports_stripe( $currency ) ) {
				$payment_methods['stripe'] = esc_html( WLSM_M_Invoice::get_payment_method_text( 'stripe' ) );
			}

			if ( $school_paypal_enable && WLSM_Payment::currency_supports_paypal( $currency ) ) {
				$payment_methods['paypal'] = esc_html( WLSM_M_Invoice::get_payment_method_text( 'paypal' ) );
			}

			if ( $school_pesapal_enable && WLSM_Payment::currency_supports_pesapal( $currency ) ) {
				$payment_methods['pesapal'] = esc_html( WLSM_M_Invoice::get_payment_method_text( 'pesapal' ) );
			}

			if ( $school_paystack_enable && WLSM_Payment::currency_supports_paystack( $currency ) ) {
				$payment_methods['paystack'] = esc_html( WLSM_M_Invoice::get_payment_method_text( 'paystack' ) );
			}

			if ( $school_paytm_enable && WLSM_Payment::currency_supports_paytm( $currency ) ) {
				$payment_methods['paytm'] = esc_html( WLSM_M_Invoice::get_payment_method_text( 'paytm' ) );
			}

			$response_data['payment_methods'] = $payment_methods;

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Parent - Payment History.
	public static function parent_payments( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$unique_student_ids = WLSM_M_Parent::get_parent_student_ids( $user_id );

			if ( ! count( $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Parent not found.', 'school-management' ) );
			}

			$params = $request->get_params();

			$student_id = isset( $params['student_id'] ) ? absint( $params['student_id'] ) : 0;

			if ( $student_id && ! in_array( $student_id, $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student = WLSM_M_Parent::fetch_student( $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			// Query.
			$payments_query = WLSM_M::payments_query();

			// Total.
			$payments_total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM ({$payments_query}) AS combined_table", $student->ID ) );

			// Current page and per page.
			$payments_page     = isset( $params['payments_page'] ) ? absint( $params['payments_page'] ) : 1;
			$payments_per_page = isset( $params['payments_per_page'] ) ? absint( $params['payments_per_page'] ) : WLSM_M::payments_per_page();

			// Page offset.
			$payments_page_offset = ( $payments_page * $payments_per_page ) - $payments_per_page;

			// Paginated data.
			$payments = $wpdb->get_results( $wpdb->prepare( $payments_query . ' ORDER BY p.ID DESC LIMIT %d, %d', $student->ID, $payments_page_offset, $payments_per_page ) );

			// Paginated response.
			$payments_data       = array();
			$payments_pagination = array(
				'current_page' => $payments_page,
				'per_page'     => $payments_per_page,
			);

			// Format response.
			if ( count( $payments ) ) {
				foreach ( $payments as $key => $row ) {
					if ( $row->invoice_id ) {
						$invoice_title = $row->invoice_title;
					} else {
						$invoice_title = $row->invoice_label;
					}

					$payments_data[] = array(
						'id'             => $row->ID,
						'receipt_number' => esc_html( WLSM_M_Invoice::get_receipt_number_text( $row->receipt_number ) ),
						'amount'         => esc_html( WLSM_Config::sanitize_money( $row->amount ) ),
						'amount_display' => esc_html( WLSM_Config::get_money_text( $row->amount, $school_id  ) ),
						'payment_method' => esc_html( WLSM_M_Invoice::get_payment_method_text( $row->payment_method ) ),
						'transaction_id' => esc_html( WLSM_M_Invoice::get_transaction_id_text( $row->transaction_id ) ),
						'date'           => esc_html( WLSM_Config::get_date_text( $row->created_at ) ),
						'invoice'        => esc_html( WLSM_M_Staff_Accountant::get_invoice_title_text( $invoice_title ) )
					);
				}

				$payments_pagination['total_pages']   = ceil( $payments_total / $payments_per_page );
				$payments_pagination['total_records'] = $payments_total;
			}

			$success = true;
			$message = esc_html__( 'Payments retrieved successfully.', 'school-management' );

			$response_data['payments'] = array(
				'data'       => $payments_data,
				'pagination' => $payments_pagination
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Parent - Payment Receipt.
	public static function parent_payment( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$unique_student_ids = WLSM_M_Parent::get_parent_student_ids( $user_id );

			if ( ! count( $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Parent not found.', 'school-management' ) );
			}

			$params = $request->get_params();

			$student_id = isset( $params['student_id'] ) ? absint( $params['student_id'] ) : 0;

			if ( $student_id && ! in_array( $student_id, $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student = WLSM_M_Parent::fetch_student( $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$payment_id = isset( $params['payment_id'] ) ? absint( $params['payment_id'] ) : 0;

			$payment = WLSM_M_Staff_Accountant::get_student_payment( $student_id, $payment_id );

			if ( ! $payment ) {
				throw new Exception( esc_html__( 'Payment not found.', 'school-management' ) );
			}

			$success = true;
			$message = esc_html__( 'Payment details retrieved successfully.', 'school-management' );

			if ( $payment->invoice_id ) {
				$invoice_title = esc_html( WLSM_M_Staff_Accountant::get_invoice_title_text( $payment->invoice_title ) );
			} else {
				$invoice_title = esc_html( WLSM_M_Staff_Accountant::get_invoice_title_text( $payment->invoice_label ) );
			}

			$response_data['payment'] = array(
				'id'                => $payment->ID,
				'receipt_number'    => esc_html( WLSM_M_Invoice::get_receipt_number_text( $payment->receipt_number ) ),
				'amount'            => esc_html( WLSM_Config::sanitize_money( $payment->amount ) ),
				'amount_display'    => esc_html( WLSM_Config::get_money_text( $payment->amount, $school_id  ) ),
				'payment_method'    => esc_html( WLSM_M_Invoice::get_payment_method_text( $payment->payment_method ) ),
				'transaction_id'    => esc_html( WLSM_M_Invoice::get_transaction_id_text( $payment->transaction_id ) ),
				'date'              => esc_html( WLSM_Config::get_date_text( $payment->created_at ) ),
				'invoice'           => esc_html( $invoice_title ),
				'student_name'      => esc_html( WLSM_M_Staff_Class::get_name_text( $payment->student_name ) ),
				'enrollment_number' => esc_html( $payment->enrollment_number ),
				'phone'             => esc_html( WLSM_M_Staff_Class::get_phone_text( $payment->phone ) ),
				'email'             => esc_html( WLSM_M_Staff_Class::get_name_text( $payment->email ) ),
				'class'             => esc_html( WLSM_M_Class::get_label_text( $payment->class_label ) ),
				'section'           => esc_html( WLSM_M_Class::get_label_text( $payment->section_label ) ),
				'roll_number'       => esc_html( WLSM_M_Staff_Class::get_roll_no_text( $payment->roll_number ) ),
				'father_name'       => esc_html( WLSM_M_Staff_Class::get_name_text( $payment->father_name ) ),
				'father_phone'      => esc_html( WLSM_M_Staff_Class::get_phone_text( $payment->father_phone ) ),
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Parent - Class time table.
	public static function parent_class_time_table( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$unique_student_ids = WLSM_M_Parent::get_parent_student_ids( $user_id );

			if ( ! count( $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Parent not found.', 'school-management' ) );
			}

			$params = $request->get_params();

			$student_id = isset( $params['student_id'] ) ? absint( $params['student_id'] ) : 0;

			if ( $student_id && ! in_array( $student_id, $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student = WLSM_M_Parent::fetch_student( $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$section_id = $student->section_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$section = WLSM_M_Staff_Class::get_school_section( $school_id, $student->section_id );

			if ( ! $section ) {
				die;
			}

			$class_label   = $section->class_label;
			$section_label = $section->label;

			$data = array();

			foreach ( WLSM_Helper::days_list() as $key => $day ) {
				$routines = WLSM_M_Staff_Class::get_section_routines_by_day( $school_id, $section_id, $key );

				$day_routine = array(
					'day' => $day
				);

				$routines_data = array();
				foreach ( $routines as $routine ) {
					$routine_data = array();
					$routine_data['subject'] = sprintf(
						wp_kses(
							/* translators: 1: subject label, 2: subject code */
							_x( '%1$s (%2$s)', 'Subject', 'school-management' ),
							array( 'span' => array( 'class' => array() ) )
						),
						esc_html( WLSM_M_Staff_Class::get_subject_label_text( $routine->subject_label ) ),
						esc_html( $routine->subject_code )
					);

					$routine_data['start_time'] = esc_html( WLSM_Config::get_time_text( $routine->start_time ) );
					$routine_data['end_time']   = esc_html( WLSM_Config::get_time_text( $routine->end_time ) );

					$routine_data['room'] = esc_html( $routine->room_number );

					if ( $routine->teacher_name ) {
						$routine_data['teacher'] = esc_html( WLSM_M_Staff_Class::get_name_text( $routine->teacher_name ) );
					}

					array_push( $routines_data, $routine_data );
				}

				$day_routine['routines'] = $routines_data;

				array_push( $data, $day_routine );
			}

			$success = true;
			$message = esc_html__( 'Class time table retrieved successfully.', 'school-management' );

			$response_data['class_time_table'] = array(
				'class'   => esc_html( WLSM_M_Class::get_label_text( $class_label ) ),
				'section' => esc_html( WLSM_M_Staff_Class::get_section_label_text( $section_label ) ),
				'data'    => $data
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Parent - Attendance.
	public static function parent_attendance( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$unique_student_ids = WLSM_M_Parent::get_parent_student_ids( $user_id );

			if ( ! count( $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Parent not found.', 'school-management' ) );
			}

			$params = $request->get_params();

			$student_id = isset( $params['student_id'] ) ? absint( $params['student_id'] ) : 0;

			if ( $student_id && ! in_array( $student_id, $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student = WLSM_M_Parent::fetch_student( $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$section_id = $student->section_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$attendance = WLSM_M_Staff_General::get_student_attendance_report( $student_id );

			$data = array();

			$total_attendance = 0;
			$total_present    = 0;
			$total_absent     = 0;

			$attendance_monthly = array();
			foreach ( $attendance as $monthly ) {
				$month = new DateTime();
				$month->setDate( $monthly->year, $monthly->month, 1 );
				$total_attendance += $monthly->total_attendance;
				$total_present    += $monthly->total_present;
				$total_absent     += $monthly->total_absent;

				$attendance_data = array(
					'month'            => esc_html( $month->format( 'F Y' ) ),
					'total_attendance' => esc_html( $monthly->total_attendance ),
					'total_present'    => esc_html( $monthly->total_present ),
					'total_absent'     => esc_html( $monthly->total_absent ),
				);

				array_push( $attendance_monthly, $attendance_data );
			}

			$data['overall'] = array(
				'total_attendance' => $total_attendance,
				'total_present'    => $total_present,
				'total_absent'     => $total_absent,
				'percentage_value' => WLSM_Config::sanitize_percentage( $total_attendance, $total_present, 1 ),
				'percentage_text'  => WLSM_Config::get_percentage_text( $total_attendance, $total_present, 1 )
			);

			$data['monthly'] = $attendance_monthly;

			$success = true;
			$message = esc_html__( 'Attendance retrieved successfully.', 'school-management' );

			$response_data['attendance'] = $data;

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Parent - Exams results.
	public static function parent_exam_results( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$unique_student_ids = WLSM_M_Parent::get_parent_student_ids( $user_id );

			if ( ! count( $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Parent not found.', 'school-management' ) );
			}

			$params = $request->get_params();

			$student_id = isset( $params['student_id'] ) ? absint( $params['student_id'] ) : 0;

			if ( $student_id && ! in_array( $student_id, $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student = WLSM_M_Parent::fetch_student( $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$exam_results = WLSM_M_Staff_Examination::get_student_published_exam_results( $school_id, $student_id );

			$results_data = array();

			if ( count( $exam_results ) ) {
				foreach ( $exam_results as $key => $value ) {
					$results_data[] = array(
						'id'         => $value->ID,
						'title'      => esc_html( stripslashes( $value->exam_title ) ),
						'start_date' => esc_html( WLSM_Config::get_date_text( $value->start_date ) ),
						'end_date'   => esc_html( WLSM_Config::get_date_text( $value->end_date ) )
					);
				}
			}

			$success = true;
			$message = esc_html__( 'Exam results retrieved successfully.', 'school-management' );

			$response_data['results'] = array(
				'data' => $results_data,
			);

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	// Parent - Exam result.
	public static function parent_exam_result( $request ) {
		$user_id = get_current_user_id();

		try {
			global $wpdb;

			$unique_student_ids = WLSM_M_Parent::get_parent_student_ids( $user_id );

			if ( ! count( $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Parent not found.', 'school-management' ) );
			}

			$params = $request->get_params();

			$student_id = isset( $params['student_id'] ) ? absint( $params['student_id'] ) : 0;

			if ( $student_id && ! in_array( $student_id, $unique_student_ids ) ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$student = WLSM_M_Parent::fetch_student( $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			$response_data = array();

			$student_id = $student->ID;
			$school_id  = $student->school_id;
			$session_id = $student->session_id;

			$class_school_id = $student->class_school_id;

			$common_details = self::student_common_details( $student );
			$response_data['student'] = $common_details;

			$admit_card_id = isset( $params['admit_card_id'] ) ? absint( $params['admit_card_id'] ) : 0;

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

			$exam_papers  = WLSM_M_Staff_Examination::get_exam_papers_by_admit_card( $school_id, $admit_card_id );
			$exam_results = WLSM_M_Staff_Examination::get_exam_results_by_admit_card( $school_id, $admit_card_id );

			$grade_criteria = WLSM_Config::sanitize_grade_criteria( $exam->grade_criteria );

			$enable_overall_grade = $grade_criteria['enable_overall_grade'];
			$marks_grades         = $grade_criteria['marks_grades'];

			$show_marks_grades = count( $marks_grades );

			$student_rank = WLSM_M_Staff_Examination::calculate_exam_ranks( $school_id, $exam_id, array(), $admit_card->ID );

			$data = array();

			$total_maximum_marks  = 0;
			$total_obtained_marks = 0;

			foreach ( $exam_papers as $key => $exam_paper ) {
				$results_data = array();

				if ( $admit_card && isset( $exam_results[ $exam_paper->ID ] ) ) {
					$exam_result    = $exam_results[ $exam_paper->ID ];
					$obtained_marks = $exam_result->obtained_marks;
				} else {
					$obtained_marks = '';
				}

				$percentage = WLSM_Config::sanitize_percentage( $exam_paper->maximum_marks, WLSM_Config::sanitize_marks( $obtained_marks ) );

				$total_maximum_marks  += $exam_paper->maximum_marks;
				$total_obtained_marks += WLSM_Config::sanitize_marks( $obtained_marks );

				$results_data['paper_code']     = esc_html( $exam_paper->paper_code );
				$results_data['subject_name']   = esc_html( stripcslashes( $exam_paper->subject_label ) );
				$results_data['subject_type']   = esc_html( WLSM_Helper::get_subject_type_text( $exam_paper->subject_type ) );
				$results_data['maximum_marks']  = esc_html( $exam_paper->maximum_marks );
				$results_data['obtained_marks'] = esc_html( $obtained_marks );

				if ( $show_marks_grades ) {
					$results_data['grade']  = esc_html( WLSM_Helper::calculate_grade( $marks_grades, $percentage ) );
				}

				array_push( $data, $results_data );
			}

			$total_percentage = WLSM_Config::sanitize_percentage( $total_maximum_marks, $total_obtained_marks );

			$success = true;
			$message = esc_html__( 'Exam result retrieved successfully.', 'school-management' );

			$response_data['result'] = array(
				'title'                => esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ),
				'start_date'           => esc_html( WLSM_Config::get_date_text( $start_date ) ),
				'end_date'             => esc_html( WLSM_Config::get_date_text( $end_date ) ),
				'student_name'         => esc_html( WLSM_M_Staff_Class::get_name_text( $admit_card->name ) ),
				'enrollment_number'    => esc_html( WLSM_M_Staff_Class::get_roll_no_text( $admit_card->enrollment_number ) ),
				'session'              => esc_html( WLSM_M_Session::get_label_text( $admit_card->session_label ) ),
				'class'                => esc_html( WLSM_M_Class::get_label_text( $admit_card->class_label ) ),
				'section'              => esc_html( WLSM_M_Class::get_label_text( $admit_card->section_label ) ),
				'roll_number'          => esc_html( WLSM_M_Staff_Class::get_roll_no_text( $admit_card->roll_number ) ),
				'show_marks_grades'    => (bool) $show_marks_grades,
				'show_overall_grade'   => (bool) $enable_overall_grade,
				'data'                 => $data,
				'total_maximum_marks'  => $total_maximum_marks,
				'total_obtained_marks' => $total_obtained_marks,
				'percentage_value'     => esc_html( $total_percentage ),
				'percentage_text'      => esc_html( WLSM_Config::get_percentage_text( $total_maximum_marks, $total_obtained_marks ) ),
			);

			if ( $show_marks_grades && $enable_overall_grade ) {
				$response_data['result']['overall_grade'] = esc_html( WLSM_Helper::calculate_grade( $marks_grades, $total_percentage ) );
			}

			$response_data['result']['student_rank'] = esc_html( $student_rank );

			WLSM_Helper::check_buffer();

		} catch ( Exception $exception ) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = array(
			'success' => (bool) $success,
			'message' => $message,
		);

		if ( isset( $response_data ) ) {
			$response['data'] = $response_data;
		}

		return new WP_REST_Response( $response, 200 );
	}

	public static function no_account() {
		return new WP_Error(
			'sm_no_account',
			esc_html__( 'There is no student or parent account.', 'school-management' ),
			array(
				'status' => 403,
			)
		);
	}
}
