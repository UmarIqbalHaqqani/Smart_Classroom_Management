<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Class.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_General.php';

class WLSM_P_Registration {
	public static function submit_registration() {
		if ( ! wp_verify_nonce( $_POST['wlsm-submit-registration'], 'wlsm-submit-registration' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$gdpr_enable = get_option( 'wlsm_gdpr_enable' );

			$school_id = isset( $_POST['school_id'] ) ? absint( $_POST['school_id'] ) : 0;

			// Personal Detail.
			$name            = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
			$gender          = isset( $_POST['gender'] ) ? sanitize_text_field( $_POST['gender'] ) : '';
			$survey          = isset( $_POST['survey'] ) ? sanitize_text_field( $_POST['survey'] ) : '';
			$dob             = isset( $_POST['dob'] ) ? DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $_POST['dob'] ) ) : null;
			$address         = isset( $_POST['address'] ) ? sanitize_text_field( $_POST['address'] ) : '';
			$city            = isset( $_POST['city'] ) ? sanitize_text_field( $_POST['city'] ) : '';
			$state           = isset( $_POST['state'] ) ? sanitize_text_field( $_POST['state'] ) : '';
			$country         = isset( $_POST['country'] ) ? sanitize_text_field( $_POST['country'] ) : '';
			$email           = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
			$phone           = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
			$religion        = isset( $_POST['religion'] ) ? sanitize_text_field( $_POST['religion'] ) : '';
			$caste           = isset( $_POST['caste'] ) ? sanitize_text_field( $_POST['caste'] ) : '';
			$blood_group     = isset( $_POST['blood_group'] ) ? sanitize_text_field( $_POST['blood_group'] ) : '';
			$id_number       = isset( $_POST['id_number'] ) ? sanitize_text_field( $_POST['id_number'] ) : '';
			$student_type_id       = isset( $_POST['student_type_id'] ) ? sanitize_text_field( $_POST['student_type_id'] ) : '';
			$id_proof        = ( isset( $_FILES['id_proof'] ) && is_array( $_FILES['id_proof'] ) ) ? $_FILES['id_proof'] : null;
			$parent_id_proof = ( isset( $_FILES['parent_id_proof'] ) && is_array( $_FILES['parent_id_proof'] ) ) ? $_FILES['parent_id_proof'] : null;

			// Admission Detail.
			$class_id   = isset( $_POST['class_id'] ) ? absint( $_POST['class_id'] ) : 0;
			$section_id = isset( $_POST['section_id'] ) ? absint( $_POST['section_id'] ) : 0;
			$photo      = ( isset( $_FILES['photo'] ) && is_array( $_FILES['photo'] ) ) ? $_FILES['photo'] : null;

			// Parent Detail.
			$father_name       = isset( $_POST['father_name'] ) ? sanitize_text_field( $_POST['father_name'] ) : '';
			$father_phone      = isset( $_POST['father_phone'] ) ? sanitize_text_field( $_POST['father_phone'] ) : '';
			$father_occupation = isset( $_POST['father_occupation'] ) ? sanitize_text_field( $_POST['father_occupation'] ) : '';
			$mother_name       = isset( $_POST['mother_name'] ) ? sanitize_text_field( $_POST['mother_name'] ) : '';
			$mother_phone      = isset( $_POST['mother_phone'] ) ? sanitize_text_field( $_POST['mother_phone'] ) : '';
			$mother_occupation = isset( $_POST['mother_occupation'] ) ? sanitize_text_field( $_POST['mother_occupation'] ) : '';

			// Student Login Detail.
			$username    = isset( $_POST['username'] ) ? sanitize_text_field( $_POST['username'] ) : '';
			$login_email = isset( $_POST['login_email'] ) ? sanitize_text_field( $_POST['login_email'] ) : '';
			$password    = isset( $_POST['password'] ) ? $_POST['password'] : '';

			// Parent / Guardian Login Detail.
			$allow_parent_login = isset( $_POST['allow_parent_login'] ) ? (bool) ( $_POST['allow_parent_login'] ) : '';
			$parent_username    = isset( $_POST['parent_username'] ) ? sanitize_text_field( $_POST['parent_username'] ) : '';
			$parent_login_email = isset( $_POST['parent_login_email'] ) ? sanitize_text_field( $_POST['parent_login_email'] ) : '';
			$parent_password    = isset( $_POST['parent_password'] ) ? $_POST['parent_password'] : '';

			// Transport Detail.
			$route_vehicle_id = isset( $_POST['route_vehicle_id'] ) ? absint( $_POST['route_vehicle_id'] ) : 0;
			$medium           = isset( $_POST['medium'] ) ? sanitize_text_field( $_POST['medium'] ) : '';

			$subjects_ids = (isset($_POST['subjects']) && is_array($_POST['subjects'])) ? $_POST['subjects'] : array();
			$activity_ids = (isset($_POST['activity']) && is_array($_POST['activity'])) ? $_POST['activity'] : array();
			// Start validation.
			$errors = array();

			$current_session_id = get_option( 'wlsm_current_session' );

			// Check if current session exists.
			$session = WLSM_M_Session::get_session( $current_session_id );
			if ( ! $session ) {
				throw new Exception( esc_html__( 'Current session not found. Please contact the administrator.', 'school-management' ) );
			}

			$session_id = $session->ID;

			// Personal Detail.
			if ( empty( $name ) ) {
				$errors['name'] = esc_html__( 'Please specify student name.', 'school-management' );
			}
			if ( strlen( $name ) > 60 ) {
				$errors['name'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
			}
			if ( ! empty( $religion ) && strlen( $religion ) > 40 ) {
				$errors['religion'] = esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' );
			}
			if ( ! empty( $caste ) && strlen( $caste ) > 40 ) {
				$errors['caste'] = esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' );
			}
			if ( ! empty( $phone ) && strlen( $phone ) > 40 ) {
				$errors['phone'] = esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' );
			}
			if ( ! empty( $email ) ) {
				if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
					$errors['email'] = esc_html__( 'Please provide a valid email.', 'school-management' );
				} elseif ( strlen( $email ) > 60 ) {
					$errors['email'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
				}
			}
			if ( ! empty( $city ) && strlen( $city ) > 60 ) {
				$errors['city'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
			}
			if ( ! empty( $state ) && strlen( $state ) > 60 ) {
				$errors['state'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
			}
			if ( ! empty( $country ) && strlen( $country ) > 60 ) {
				$errors['country'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
			}
			if ( ! in_array( $gender, array_keys( WLSM_Helper::gender_list() ) ) ) {
				throw new Exception( esc_html__( 'Please specify gender.', 'school-management' ) );
			}
			if ( ! empty( $blood_group ) && ! in_array( $blood_group, array_keys( WLSM_Helper::blood_group_list() ) ) ) {
				throw new Exception( esc_html__( 'Please specify blood group.', 'school-management' ) );
			}
			if ( ! empty( $dob ) ) {
				$dob = $dob->format( 'Y-m-d' );
			} else {
				$dob = null;
			}

			// Admission Detail.
			if ( empty( $school_id ) ) {
				$errors['school_id'] = esc_html__( 'Please select a school.', 'school-management' );
				wp_send_json_error( $errors );
			} else {
				if ( empty( $class_id ) ) {
					$errors['class_id'] = esc_html__( 'Please select a class.', 'school-management' );
					wp_send_json_error( $errors );
				} else {
					// Checks if class exists in the school.
					$class_school = WLSM_M_Staff_Class::fetch_class( $school_id, $class_id );
					if ( ! $class_school ) {
						$errors['class_id'] = esc_html__( 'Class not found.', 'school-management' );
						wp_send_json_error( $errors );
					} else {
						$class_school_id = $class_school->ID;
						$class_label     = $class_school->label;
					}
				}
			}

			$admission_date = current_time( 'Y-m-d' );

			if ( empty( $section_id ) ) {
				$errors['section_id'] = esc_html__( 'Please select section.', 'school-management' );
				wp_send_json_error( $errors );
			}
			if ( isset( $photo['tmp_name'] ) && ! empty( $photo['tmp_name'] ) ) {
				if ( ! WLSM_Helper::is_valid_file( $photo, 'image' ) ) {
					$errors['photo'] = esc_html__( 'Please provide photo in JPG, JPEG or PNG format.', 'school-management' );
				}
			}
			if ( isset( $id_proof['tmp_name'] ) && ! empty( $id_proof['tmp_name'] ) ) {
				if ( ! WLSM_Helper::is_valid_file( $id_proof, 'attachment' ) ) {
					$errors['id_proof'] = esc_html__( 'File type is not supported.', 'school-management' );
				}
			}
			if ( isset( $parent_id_proof['tmp_name'] ) && ! empty( $parent_id_proof['tmp_name'] ) ) {
				if ( ! WLSM_Helper::is_valid_file( $parent_id_proof, 'attachment' ) ) {
					$errors['parent_id_proof'] = esc_html__( 'File type is not supported.', 'school-management' );
				}
			}

			// Parent Detail.
			if ( ! empty( $father_name ) && strlen( $father_name ) > 60 ) {
				$errors['father_name'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
			}
			if ( ! empty( $father_phone ) && strlen( $father_phone ) > 40 ) {
				$errors['father_phone'] = esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' );
			}
			if ( ! empty( $father_occupation ) && strlen( $father_occupation ) > 60 ) {
				$errors['father_occupation'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
			}
			if ( ! empty( $mother_name ) && strlen( $mother_name ) > 60 ) {
				$errors['mother_name'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
			}
			if ( ! empty( $mother_phone ) && strlen( $mother_phone ) > 40 ) {
				$errors['mother_phone'] = esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' );
			}
			if ( ! empty( $mother_occupation ) && strlen( $mother_occupation ) > 60 ) {
				$errors['mother_occupation'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
			}

			// Checks if section exists.
			$section = WLSM_M_Staff_Class::get_section( $school_id, $section_id, $class_school_id );
			if ( ! $section ) {
				$errors['section_id'] = esc_html__( 'Section not found.', 'school-management' );
				wp_send_json_error( $errors );
			}

			// Student Login Detail.
			if ( empty( $username ) ) {
				$errors['username'] = esc_html__( 'Please provide username.', 'school-management' );
			}
			if ( empty( $login_email ) ) {
				$errors['login_email'] = esc_html__( 'Please provide login email.', 'school-management' );
			}
			if ( ! filter_var( $login_email, FILTER_VALIDATE_EMAIL ) ) {
				$errors['login_email'] = esc_html__( 'Please provide a valid email.', 'school-management' );
			}
			if ( empty( $password ) ) {
				$errors['password'] = esc_html__( 'Please provide login password.', 'school-management' );
			}

			// Parent / Guardian Login Detail.
			if ( $allow_parent_login ) {
				if ( empty( $parent_username ) ) {
					$errors['parent_username'] = esc_html__( 'Please provide username.', 'school-management' );
				}
				if ( empty( $parent_login_email ) ) {
					$errors['parent_login_email'] = esc_html__( 'Please provide login email.', 'school-management' );
				}
				if ( ! filter_var( $parent_login_email, FILTER_VALIDATE_EMAIL ) ) {
					$errors['parent_login_email'] = esc_html__( 'Please provide a valid email.', 'school-management' );
				}
				if ( empty( $parent_password ) ) {
					$errors['parent_password'] = esc_html__( 'Please provide login password.', 'school-management' );
				}
			}

			// Transport Detail.
			if ( ! empty( $route_vehicle_id ) ) {
				$route_vehicle = WLSM_M_Staff_Transport::get_route_vehicle( $school_id, $route_vehicle_id );
				if ( ! $route_vehicle ) {
					$errors['route_vehicle_id'] = esc_html__( 'Please select valid transport route vehicle.', 'school-management' );
				}
			} else {
				$route_vehicle_id = null;
			}

			if ( $gdpr_enable ) {
				$gdpr = isset( $_POST['gdpr'] ) ? (bool) ( $_POST['gdpr'] ) : false;
				if ( ! $gdpr ) {
					$errors['gdpr'] = esc_html__( 'Please check for GDPR consent.', 'school-management' );
				}
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

		if ( count( $errors ) < 1 ) {
			try {
				// Registration settings.
				$settings_registration               = WLSM_M_Setting::get_settings_registration( $school_id );
				$school_registration_login_user      = $settings_registration['login_user'];
				$school_registration_redirect_url    = $settings_registration['redirect_url'];
				$school_registration_create_invoice  = $settings_registration['create_invoice'];
				$school_registration_success_message = $settings_registration['success_message'];

				$wpdb->query( 'BEGIN;' );

				// Parent user data.
				if ( $allow_parent_login ) {
					// New user.
					$parent_user_data = array(
						'user_email' => $parent_login_email,
						'user_login' => $parent_username,
						'user_pass'  => $parent_password,
					);

					$parent_user_id = wp_insert_user( $parent_user_data );
					if ( is_wp_error( $parent_user_id ) ) {
						throw new Exception( $parent_user_id->get_error_message() );
					}
				} else {
					$parent_user_id = null;
				}

				// Student user data.
				// New user.
				$user_data = array(
					'user_email' => $login_email,
					'user_login' => $username,
					'user_pass'  => $password,
				);

				$user_id = wp_insert_user( $user_data );
				if ( is_wp_error( $user_id ) ) {
					throw new Exception( $user_id->get_error_message() );
				}

				$settings_registration = WLSM_M_Setting::get_settings_registration( $school_id );
				$school_student_active = $settings_registration['student_aprove'];

				if ( $school_student_active === true ) {

					$is_active = 0;

				} else {
					$is_active = 1;
				}

				// Student record data.
				$student_record_data = array(
					'name'              => $name,
					'gender'            => $gender,
					'survey'            => $survey,
					'dob'               => $dob,
					'phone'             => $phone,
					'email'             => $email,
					'address'           => $address,
					'city'              => $city,
					'state'             => $state,
					'country'           => $country,
					'religion'          => $religion,
					'caste'             => $caste,
					'medium'            => $medium,
					'blood_group'       => $blood_group,
					'id_number'         => $id_number,
					'student_type'      => $student_type_id,
					'father_name'       => $father_name,
					'father_phone'      => $father_phone,
					'father_occupation' => $father_occupation,
					'mother_name'       => $mother_name,
					'mother_phone'      => $mother_phone,
					'mother_occupation' => $mother_occupation,
					'admission_date'    => $admission_date,
					'section_id'        => $section_id,
					'route_vehicle_id'  => $route_vehicle_id,
					'user_id'           => $user_id,
					'parent_user_id'    => $parent_user_id,
					'is_active'         => $is_active,
					'from_front'        => 1,
				);

				// Admission number.
				$admission_number = WLSM_M_Staff_General::get_admission_number( $school_id, $session_id );

				$student_record_data['admission_number'] = $admission_number;

				// Roll number.
				$roll_number = WLSM_M_Staff_General::get_roll_number( $school_id, $session_id, $class_id );

				$student_record_data['roll_number'] = $roll_number;

				if ( $gdpr_enable ) {
					$student_record_data['gdpr_agreed'] = $gdpr;
				}

				if ( ! empty( $photo ) ) {
					$photo = media_handle_upload( 'photo', 0 );
					if ( is_wp_error( $photo ) ) {
						throw new Exception( $photo->get_error_message() );
					}
					$student_record_data['photo_id'] = $photo;
				}

				if ( ! empty( $id_proof ) ) {
					$id_proof = media_handle_upload( 'id_proof', 0 );
					if ( is_wp_error( $id_proof ) ) {
						throw new Exception( $id_proof->get_error_message() );
					}
					$student_record_data['id_proof'] = $id_proof;
				}

				if ( ! empty( $parent_id_proof ) ) {
					$parent_id_proof = media_handle_upload( 'parent_id_proof', 0 );
					if ( is_wp_error( $parent_id_proof ) ) {
						throw new Exception( $parent_id_proof->get_error_message() );
					}
					$student_record_data['parent_id_proof'] = $parent_id_proof;
				}

				$student_record_data['session_id'] = $session_id;

				$enrollment_number = WLSM_M_Staff_General::get_enrollment_number( $school_id );

				$student_record_data['enrollment_number'] = $enrollment_number;

				$student_record_data['added_by'] = $user_id;

				$student_record_data['created_at']   = current_time( 'Y-m-d H:i:s' );

				$student_record_data['activities'] = serialize($activity_ids);

				$success = $wpdb->insert( WLSM_STUDENT_RECORDS, $student_record_data );

				$new_student_id = $wpdb->insert_id;

				WLSM_Helper::check_buffer();

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$placeholders = array(
					'[NAME]'  => stripcslashes( $name ),
					'[PHONE]' => $phone,
					'[EMAIL]' => $email,
					'[CLASS]' => stripcslashes( $class_label ),
				);

				$school_registration_success_placeholders = array_keys( WLSM_Helper::registration_success_message_placeholders() );

				foreach ( $placeholders as $key => $value ) {
					if ( in_array( $key, $school_registration_success_placeholders ) ) {
						$school_registration_success_message = str_replace( $key, $value, $school_registration_success_message );
					}
				}

				// Fees.
				$fees = WLSM_M_Staff_Accountant::fetch_fees_by_class( $school_id, $class_id );

				$fee_order = 10;
				if ( count( $fees ) ) {
					foreach ( $fees as $fee ) {
						$fee_order++;

						// Student fee data.
						$student_fee_data = array(
							'amount'            => $fee->amount,
							'period'            => $fee->period,
							'label'             => $fee->label,
							'fee_order'         => $fee_order,
							'student_record_id' => $new_student_id,
						);

						$student_fee_data['created_at'] = current_time( 'Y-m-d H:i:s' );

						$success = $wpdb->insert( WLSM_STUDENT_FEES, $student_fee_data );

						if ( false === $success ) {
							throw new Exception( $wpdb->last_error );
						}

						if ( $school_registration_create_invoice ) {
							// Invoice data.
							$invoice_data = array(
								'label'           => $student_fee_data['label'],
								'amount'          => $student_fee_data['amount'],
								'date_issued'     => $student_fee_data['created_at'],
								'due_date'        => $student_fee_data['created_at'],
								'partial_payment' => 0,
							);

							$invoice_number = WLSM_M_Invoice::get_invoice_number( $school_id );

							$invoice_data['invoice_number']    = $invoice_number;
							$invoice_data['student_record_id'] = $new_student_id;

							$invoice_data['added_by'] = $user_id;

							$invoice_data['created_at'] = $student_fee_data['created_at'];

							$success = $wpdb->insert( WLSM_INVOICES, $invoice_data );

							if ( false === $success ) {
								throw new Exception( $wpdb->last_error );
							}

							WLSM_Helper::check_buffer();
						}
					}
				}

				// get activity fees
				if ($activity_ids) {
					foreach ($activity_ids as $id) {
						$activity_fees = WLSM_Helper::get_activity_fees($id);


						// Create invoice for activity fee.
						$invoice_data = array(
							'label'           => $activity_fees['title'],
							'amount'          => $activity_fees['fees'],
							'date_issued'     => current_time('Y-m-d H:i:s'),
							'due_date'        => current_time('Y-m-d H:i:s'),
							'partial_payment' => 0,
							'invoice_number'  => WLSM_M_Invoice::get_invoice_number($school_id),
							'student_record_id' => $new_student_id,
							'created_at'      => current_time('Y-m-d H:i:s'),
						);
						$success = $wpdb->insert(WLSM_INVOICES, $invoice_data);
						if (false === $success) {
							throw new Exception($wpdb->last_error);
						}

						WLSM_Helper::check_buffer();
					}
				}


				$message = $school_registration_success_message;

				if ($subjects_ids){
					foreach ($subjects_ids as $subject_id) {
						$subject_data = array(
							'student_id' => $new_student_id,
							'subject_id' => $subject_id,
						);
						$success = $wpdb->insert(WLSM_STUDENTS_SUBJECTS, $subject_data);
					}
				}

				$wpdb->query( 'COMMIT;' );

				if ( isset( $new_student_id ) ) {
					// Notify for student registration to student and admin.
					$data = array(
						'school_id'  => $school_id,
						'session_id' => $session_id,
						'student_id' => $new_student_id,
						'password'   => $password,
					);

					wp_schedule_single_event( time() + 30, 'wlsm_notify_for_student_registration_to_student', $data );
					wp_schedule_single_event( time() + 30, 'wlsm_notify_for_student_registration_to_admin', $data );
				}

				if ( $school_registration_login_user ) {
					wp_set_current_user( $user_id, $username );
					wp_set_auth_cookie( $user_id );
					do_action( 'wp_login', $username );
				}

				wp_send_json_success(
					array(
						'message'      => $message,
						'redirect_url' => $school_registration_redirect_url,
					)
				);
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function submit_staff_registration() {
		if ( ! wp_verify_nonce( $_POST['wlsm-submit-staff-registration'], 'wlsm-submit-staff-registration' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$school_id = isset( $_POST['school_id'] ) ? absint( $_POST['school_id'] ) : 0;

			// Personal Detail.
			$name    = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
			$gender  = isset( $_POST['gender'] ) ? sanitize_text_field( $_POST['gender'] ) : '';
			$dob     = isset( $_POST['dob'] ) ? DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $_POST['dob'] ) ) : null;
			$address = isset( $_POST['address'] ) ? sanitize_text_field( $_POST['address'] ) : '';
			$email   = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
			$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
			$salary  = isset( $_POST['salary'] ) ? WLSM_Config::sanitize_money( $_POST['salary'] ) : 0;

			// Joining Detail.
			$joining_date = isset( $_POST['joining_date'] ) ? DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $_POST['joining_date'] ) ) : null;

			$designation = isset( $_POST['designation'] ) ? sanitize_text_field( $_POST['designation'] ) : '';
			$note        = isset( $_POST['note'] ) ? sanitize_text_field( $_POST['note'] ) : '';

			// Class Teacher.
			$class_id   = isset( $_POST['class_id'] ) ? absint( $_POST['class_id'] ) : 0;
			$section_id = isset( $_POST['section_id'] ) ? absint( $_POST['section_id'] ) : 0;

			// Login Detail.
			$username    = isset( $_POST['username'] ) ? sanitize_text_field( $_POST['username'] ) : '';
			$login_email = isset( $_POST['login_email'] ) ? sanitize_text_field( $_POST['login_email'] ) : '';
			$password    = isset( $_POST['password'] ) ? $_POST['password'] : '';

			// Start validation.
			$errors = array();
			// Personal Detail.
			if ( empty( $name ) ) {
				$errors['name'] = esc_html__( 'Please specify name.', 'school-management' );
			}
			if ( ! empty( $phone ) && strlen( $phone ) > 40 ) {
				$errors['phone'] = esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' );
			}

			if ( ! empty( $email ) ) {
				if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
					$errors['email'] = esc_html__( 'Please provide a valid email.', 'school-management' );
				} elseif ( strlen( $email ) > 60 ) {
					$errors['email'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
				}
			}

			if ( empty( $school_id ) ) {
				$errors['school_id'] = esc_html__( 'Please select a school.', 'school-management' );
			}

			// staff Login Detail.
			if ( empty( $username ) ) {
				$errors['username'] = esc_html__( 'Please provide username.', 'school-management' );
			}
			if ( empty( $login_email ) ) {
				$errors['login_email'] = esc_html__( 'Please provide login email.', 'school-management' );
			}
			if ( ! filter_var( $login_email, FILTER_VALIDATE_EMAIL ) ) {
				$errors['login_email'] = esc_html__( 'Please provide a valid email.', 'school-management' );
			}
			if ( empty( $password ) ) {
				$errors['password'] = esc_html__( 'Please provide login password.', 'school-management' );
			}

			if ( ! empty( $dob ) ) {
				$dob = $dob->format( 'Y-m-d' );
			} else {
				$dob = null;
			}

			// Class Teacher.
			if ( $class_id ) {
				// Checks if class exists in the school.
				$class_school = WLSM_M_Staff_Class::get_class( $school_id, $class_id );
				if ( ! $class_school ) {
					$errors['class_id'] = esc_html__( 'Class not found.', 'school-management' );
				} else {
					$class_school_id = $class_school->ID;
					if ( ! $section_id ) {
						$errors['section_id'] = esc_html__( 'Please select a section.', 'school-management' );
					} else {
						// Checks if section exists.
						$section = WLSM_M_Staff_Class::get_section( $school_id, $section_id, $class_school_id );
						if ( ! $section ) {
							$errors['section_id'] = esc_html__( 'Section not found.', 'school-management' );
						}
					}
				}
			} else {
				$section_id = null;
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

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				// New user.
				$user_data = array(
					'user_email' => $login_email,
					'user_login' => $username,
					'user_pass'  => $password,
				);

				$user_id = wp_insert_user( $user_data );

				if ( is_wp_error( $user_id ) ) {
					throw new Exception( $user_id->get_error_message() );
				}

				$permissions = array();
				$permissions = serialize($permissions);
				if ( $user_id ) {
					$staff_data['created_at'] = current_time( 'Y-m-d H:i:s' );

					$staff_data['school_id'] = $school_id;
					$staff_data['role']      = 'employee';
					$staff_data['user_id']   = $user_id;
					$staff_data['permissions']   = $permissions;

					$success = $wpdb->insert( WLSM_STAFF, $staff_data );

					$staff_id = $wpdb->insert_id;
				}

				// Student record data.
				$staff_record_data = array(
					'name'         => $name,
					'gender'       => $gender,
					'dob'          => $dob,
					'phone'        => $phone,
					'email'        => $email,
					'address'      => $address,
					'email'        => $email,
					'address'      => $address,
					'salary'       => $salary,
					'designation'  => $designation,
					'note'         => $note,
					'joining_date' => $joining_date,
					'section_id'   => $section_id,
					'is_active'    => 1,
					'staff_id'      => $staff_id,
				);
				$success           = $wpdb->insert( WLSM_ADMINS, $staff_record_data );

				WLSM_Helper::check_buffer();

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$message = esc_html( 'Staff User Created Successfully.', 'school-management' );

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success(
					array(
						'message' => $message,
					)
				);
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function submit_school_register() {
		if ( ! wp_verify_nonce( $_POST['wlsm-school-register'], 'wlsm-school-register' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			// Personal Detail.
			$name    = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
			$phone   = isset( $_POST['school_phone'] ) ? sanitize_text_field( $_POST['school_phone'] ) : '';
			$address = isset( $_POST['school_address'] ) ? sanitize_text_field( $_POST['school_address'] ) : '';
			$email   = isset( $_POST['school_email'] ) ? sanitize_text_field( $_POST['school_email'] ) : '';

			// Login Detail.
			$username    = isset( $_POST['user_name'] ) ? sanitize_text_field( $_POST['user_name'] ) : '';
			$login_email = isset( $_POST['login_email'] ) ? sanitize_text_field( $_POST['login_email'] ) : '';
			$password    = isset( $_POST['password'] ) ? $_POST['password'] : '';

			// Start validation.
			$errors = array();
			// Personal Detail.
			if ( empty( $name ) ) {
				$errors['name'] = esc_html__( 'Please specify name.', 'school-management' );
			}
			if ( ! empty( $phone ) && strlen( $phone ) > 40 ) {
				$errors['school_phone'] = esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' );
			}

			if ( ! empty( $email ) ) {
				if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
					$errors['school_email'] = esc_html__( 'Please provide a valid email.', 'school-management' );
				} elseif ( strlen( $email ) > 60 ) {
					$errors['school_email'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
				}
			}

			// staff Login Detail.
			if ( empty( $username ) ) {
				$errors['user_name'] = esc_html__( 'Please provide username.', 'school-management' );
			}
			if ( empty( $login_email ) ) {
				$errors['login_email'] = esc_html__( 'Please provide login email.', 'school-management' );
			}
			if ( ! filter_var( $login_email, FILTER_VALIDATE_EMAIL ) ) {
				$errors['login_email'] = esc_html__( 'Please provide a valid email.', 'school-management' );
			}
			if ( empty( $password ) ) {
				$errors['password'] = esc_html__( 'Please provide login password.', 'school-management' );
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

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				// New user.
				$user_data = array(
					'user_email' => $login_email,
					'user_login' => $username,
					'user_pass'  => $password,
				);

				$user_id = wp_insert_user( $user_data );

				if ( is_wp_error( $user_id ) ) {
					throw new Exception( $user_id->get_error_message() );
				}

				$school_record_data = array(
					'label'     => $name,
					'phone'     => $phone,
					'email'     => $email,
					'address'   => $address,
					'is_active' => 1,
				);
				$staff_record_data['created_at'] = current_time( 'Y-m-d H:i:s' );
				$success           = $wpdb->insert( WLSM_SCHOOLS, $school_record_data );

				$school_id = $wpdb->insert_id;

				if ( $user_id ) {
					$staff_data['created_at'] = current_time( 'Y-m-d H:i:s' );
					$staff_data = array(
						// 'school_id' => $school_id, //if you want to assign school without admin aprovel
						'user_id'   => $user_id,
						'role'      => WLSM_M_Role::get_admin_key(),
					);
					$success = $wpdb->insert( WLSM_STAFF, $staff_data );
				}

				$staff_id = $wpdb->insert_id;

				$admin_data = array(
					'name'     => $name,
					'staff_id' => $staff_id,
				);
				$admin_data['assigned_by_manager'] = 1;

				$admin_data['created_at'] = current_time( 'Y-m-d H:i:s' );

				$success = $wpdb->insert( WLSM_ADMINS, $admin_data );

				WLSM_Helper::check_buffer();

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$message = esc_html( 'School Created Successfully.', 'school-management' );

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success(
					array(
						'message' => $message,
					)
				);
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}
}
