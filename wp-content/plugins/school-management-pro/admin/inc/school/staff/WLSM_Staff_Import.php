<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Import.php';

class WLSM_Staff_Import {
	public static function bulk_import_student() {
		$current_user = WLSM_M_Role::can( 'manage_admissions' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['bulk-import-student'], 'bulk-import-student' ) ) {
				die();
			}

			// Start validation.
			$errors = array();

			$csv = ( isset( $_FILES['csv'] ) && is_array( $_FILES['csv'] ) ) ? $_FILES['csv'] : NULL;

			if ( isset( $csv['tmp_name'] ) && ! empty( $csv['tmp_name'] ) ) {
				if ( ! WLSM_Helper::is_valid_file( $csv, 'csv' ) ) {
					$errors['csv'] = esc_html__( 'Please provide valid csv file.', 'school-management' );
				}
			} else {
				$errors['csv'] = esc_html__( 'Please provide valid csv file.', 'school-management' );
			}

			if ( count( $errors ) >= 1 ) {
				wp_send_json_error( $errors );
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

				$csv_file = fopen( $csv['tmp_name'], 'r' );

				fgetcsv( $csv_file );

				$row = 1;
				while ( $line = fgetcsv( $csv_file ) ) {
					$row++;

					$name              = sanitize_text_field( $line[0] );
					$admission_number  = sanitize_text_field( $line[1] );
					$admission_date    = ! empty( $line[2] ) ? DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $line[2] ) ) : NULL;
					$class_label       = sanitize_text_field( $line[3] );
					$section_label     = sanitize_text_field( $line[4] );
					$roll_number       = sanitize_text_field( $line[5] );
					$gender            = sanitize_text_field( strtolower($line[6]) );
					$dob               = ! empty( $line[7] ) ? DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $line[7] ) ) : NULL;
					$phone             = sanitize_text_field( $line[8] );
					$email             = sanitize_text_field( $line[9] );
					$address           = sanitize_text_field( $line[10] );
					$religion          = sanitize_text_field( $line[11] );
					$caste             = sanitize_text_field( $line[12] );
					$blood_group       = sanitize_text_field( $line[13] );
					$father_name       = sanitize_text_field( $line[14] );
					$father_phone      = sanitize_text_field( $line[15] );
					$father_occupation = sanitize_text_field( $line[16] );
					$mother_name       = sanitize_text_field( $line[17] );
					$mother_phone      = sanitize_text_field( $line[18] );
					$mother_occupation = sanitize_text_field( $line[19] );
					$is_active         = (bool) $line[20];
					$username          = sanitize_text_field( $line[21] );
					$password          = sanitize_text_field( $line[22] );
					$confirm_password  = sanitize_text_field( $line[23] );
					$parent_email  = sanitize_text_field( $line[24] );
					$parent_username  = sanitize_text_field( $line[25] );
					$parent_password  = sanitize_text_field( $line[26] );

					// Personal Detail.
					if ( empty( $name ) ) {
						throw new Exception( esc_html__( 'Please specify student name.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 1 ) );
					}
					if ( strlen( $name ) > 60 ) {
						throw new Exception( esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 1 ) );
					}
					if ( ! empty( $religion ) && strlen( $religion ) > 40 ) {
						throw new Exception( esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 12 ) );
					}
					if ( ! empty( $caste ) && strlen( $caste ) > 40 ) {
						throw new Exception( esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 13 ) );
					}
					if ( ! empty( $phone ) && strlen( $phone ) > 40 ) {
						throw new Exception( esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 9 ) );
					}
					if ( ! empty( $email ) ) {
						if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
							throw new Exception( esc_html__( 'Please provide a valid email.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 10 ) );
						} else if ( strlen( $email ) > 60 ) {
							throw new Exception( esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 10 ) );
						}
					}

					if ( empty( $email) ) {
						throw new Exception( esc_html__( 'Please provide a valid email.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 10 ) );
					}

					if ( ! in_array( $gender, array_keys( WLSM_Helper::gender_list() ) ) ) {
						throw new Exception( esc_html__( 'Please specify gender.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 7 ) );
					}
					if ( ! empty( $blood_group ) && ! in_array( $blood_group, array_keys( WLSM_Helper::blood_group_list() ) ) ) {
						throw new Exception( esc_html__( 'Please specify blood group.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 14 ) );
					}
					if ( ! empty( $dob ) ) {
						$dob = $dob->format( 'Y-m-d' );
					} else {
						$dob = NULL;
					}
					// Admission Detail.
					if ( empty( $admission_date ) ) {
						throw new Exception( esc_html__('Please provide admission date. [ CHANGE DATE FORMAT INSIDE SCHOOL MANAGEMENT SETTINGS ]', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 3 ) );
					} else {
						$admission_date = $admission_date->format( 'Y-m-d' );
					}
					if ( empty( $admission_number ) ) {
						throw new Exception( esc_html__( 'Please provide admission number. or Duplicate roll number', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 2 ) );
					}
					if ( strlen( $admission_number ) > 60 ) {
						throw new Exception( esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 2 ) );
					}
					if ( ! empty( $roll_number ) && strlen( $roll_number ) > 30 ) {
						throw new Exception( esc_html__( 'Maximum length cannot exceed 30 characters.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 6 ) );
					}
					if ( empty( $class_label ) ) {
						throw new Exception( esc_html__( 'Please specify class.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 4 ) );
					} else {
						// Search class with this label.
						// Checks if class exists in the school.
						$class_school = WLSM_M_Staff_Class::get_class_with_label( $school_id, $class_label );
						if ( ! $class_school ) {
							throw new Exception( esc_html__( 'Class not found.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 4 ) );
						} else {
							$class_school_id = $class_school->ID;
							$class_id        = $class_school->class_id;
						}

						if ( empty( $section_label ) ) {
							throw new Exception( esc_html__( 'Please specify section.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 5 ) );
						} else {
							// Search section with this label.
							// Checks if section exists.
							$section = WLSM_M_Staff_Class::get_section_with_label( $school_id, $section_label, $class_school_id );

							if ( ! $section ) {
								throw new Exception( esc_html__( 'Section not found.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 5 ) );
							} else {
								$section_id = $section->ID;
							}

							// Checks if admission number already exists for this session.
							$student_exists = WLSM_M_Staff_General::get_admitted_student_id( $school_id, $session_id, $admission_number );

							if ( $student_exists ) {
								throw new Exception( esc_html__( 'Admission number already exists in this session.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 2 ) );
							}

							// Checks if roll number already exists in the class for this session.
							$student_exists = WLSM_M_Staff_General::get_student_with_roll_number( $school_id, $session_id, $class_id, $roll_number );

							if ( $student_exists ) {
								throw new Exception( esc_html__( 'Roll number already exists in this class.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 6 ) );
							}

							if ($class_id) {
								$fees = WLSM_M_Staff_Accountant::fetch_fees_by_class($school_id, $class_id);
								$is_insert = true;
							}

						}
					}

					// Parent Detail.
					if ( ! empty( $father_name ) && strlen( $father_name ) > 60 ) {
						throw new Exception( esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 15 ) );
					}
					if ( ! empty( $father_phone ) && strlen( $father_phone ) > 40 ) {
						throw new Exception( esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 16 ) );
					}
					if ( ! empty( $father_occupation ) && strlen( $father_occupation ) > 60 ) {
						throw new Exception( esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 17 ) );
					}
					if ( ! empty( $mother_name ) && strlen( $mother_name ) > 60 ) {
						throw new Exception( esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 18 ) );
					}
					if ( ! empty( $mother_phone ) && strlen( $mother_phone ) > 40 ) {
						throw new Exception( esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 19 ) );
					}
					if ( ! empty( $mother_occupation ) && strlen( $mother_occupation ) > 60 ) {
						throw new Exception( esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 20 ) );
					}

					if (! empty($username)) {
						$user = get_user_by('login', $username);
						if ($user) {
							throw new Exception( esc_html__( 'Username already exists.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 21 ) );
						}
					}
					if (! empty($password)) {
						if ($password !== $confirm_password) {
							throw new Exception( esc_html__( 'Passwords does not matching', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 22 ) );
						}
					}

					if ( $is_active ) {
						$is_active = 1;
					} else {
						$is_active = 0;
					}

					if (!empty($username)) {
						// Create New user.
						$user_data = array(
							'user_email' => $email,
							'user_login' => $username,
							'user_pass'  => $password,
						);
						$user_id = wp_insert_user($user_data);

						if (is_wp_error($user_id)) {
							throw new Exception($user_id->get_error_message());
						}
					}

					if (!empty($parent_username)) {
						// Create New parent_user_id.
						$user_data = array(
							'user_email' => $parent_email,
							'user_login' => $parent_username,
							'user_pass'  => $parent_password,
						);
						$parent_user_id = wp_insert_user($user_data);

						if (is_wp_error($parent_user_id)) {
							throw new Exception($parent_user_id->get_error_message());
						}
					}

					// Student record data.
					$student_record_data = array(
						'admission_number'  => $admission_number,
						'name'              => $name,
						'gender'            => $gender,
						'dob'               => $dob,
						'phone'             => $phone,
						'email'             => $email,
						'address'           => $address,
						'religion'          => $religion,
						'caste'             => $caste,
						'blood_group'       => $blood_group,
						'father_name'       => $father_name,
						'father_phone'      => $father_phone,
						'father_occupation' => $father_occupation,
						'mother_name'       => $mother_name,
						'mother_phone'      => $mother_phone,
						'mother_occupation' => $mother_occupation,
						'admission_date'    => $admission_date,
						'roll_number'       => $roll_number,
						'section_id'        => $section_id,
						'is_active'         => $is_active,
						'user_id'           => $user_id,
						'parent_user_id'           => $parent_user_id,
					);

					$student_record_data['session_id'] = $session_id;

					$enrollment_number = WLSM_M_Staff_General::get_enrollment_number( $school_id );

					$student_record_data['enrollment_number'] = $enrollment_number;

					$student_record_data['created_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->insert( WLSM_STUDENT_RECORDS, $student_record_data );

					$new_student_id = $wpdb->insert_id;
					$student_id     = $new_student_id;

					// Fees.
					$place_holders_fee_labels = array();

					$fee_order = 10;
					$list_data = array();
					$invoice_number = WLSM_M_Invoice::get_invoice_number($school_id);
					$amount  = 0;
					if (count($fees)) {
						foreach ($fees as $key => $value) {
							array_push($place_holders_fee_labels, '%s');
							$fee_order++;
							// Student fee data.
							if ($is_insert) {
								// Student fee does not exist, insert student fee.

								$student_fee_data['label']             = $fees[$key]->label;
								$student_fee_data['period']             = $fees[$key]->period;
								$student_fee_data['amount']             = $fees[$key]->amount;
								$student_fee_data['student_record_id'] = $student_id;

								$student_fee_data['created_at'] = current_time('Y-m-d H:i:s');
								$success = $wpdb->insert(WLSM_STUDENT_FEES, $student_fee_data);

								$invoice_discount = 0;
								// Get the percent of amount if discount is given
								$invoice_amount = $student_fee_data['amount'];
								$invoice_amount_discounted = $invoice_discount/100 * $invoice_amount;
								$invoice_amount = $invoice_amount - $invoice_amount_discounted;

								// Invoice data.
								$invoice_data = array(
									'label'           => $student_fee_data['label'],
									'amount'          => $invoice_amount,
									'discount'        => $invoice_discount,
									'date_issued'     => $student_fee_data['created_at'],
									'due_date'        => $student_fee_data['created_at'],
									'partial_payment' => 0,
								);
								$fee_data = array(
									'label'           => $student_fee_data['label'],
									'period'          => $student_fee_data['period'],
									'amount'          => $student_fee_data['amount'],
									'partial_payment' => 0,
								);

								if($fees[$key]->active_on_dashboard === '0'){
									$label          = 'Admission invoice';
									$amount         += $student_fee_data['amount'];

									array_push($list_data, $fee_data );
								}

								$invoice_data['invoice_number']    = $invoice_number;
								$invoice_data['student_record_id'] = $new_student_id;

								$invoice_data['added_by'] = $user_id;

								$invoice_data['created_at'] = $student_fee_data['created_at'];

								if($fees[$key]->active_on_dashboard === '1'){
									$success = $wpdb->insert(WLSM_INVOICES, $invoice_data);
								}

								if (false === $success) {
									throw new Exception($wpdb->last_error);
								}
							} else {
								// Check if student fee exists for this fee label.
								$student_fee_exist = $wpdb->get_row($wpdb->prepare('SELECT sft.ID FROM ' . WLSM_STUDENT_FEES . ' as sft WHERE sft.student_record_id = %d AND sft.label = %s', $student_id, $value));

								if ($student_fee_exist) {
									// Student fee exists, update student fee.
									$student_fee_data['updated_at'] = current_time('Y-m-d H:i:s');

									$success = $wpdb->update(WLSM_STUDENT_FEES, $student_fee_data, array('ID' => $student_fee_exist->ID, 'student_record_id' => $student_id));
								} else {
									// Student fee does not exist, insert student fee.
									$student_fee_data['label']             = $value;
									$student_fee_data['student_record_id'] = $student_id;

									$student_fee_data['created_at'] = current_time('Y-m-d H:i:s');

									$success = $wpdb->insert(WLSM_STUDENT_FEES, $student_fee_data);
								}
							}
						}

						// Create group invoice with fee type list
						if($fees[$key]->active_on_dashboard !== 1){
							$invoice_data['label']        = $label;
							$discounted       = $invoice_discount/100 * $amount;
							$invoice_data['amount']       = $amount - $discounted;
							$invoice_data['discount']     = $invoice_discount;
										$list_data_type = serialize($list_data);
							$invoice_data['fee_list']     = $list_data_type;
										$success        = $wpdb->insert(WLSM_INVOICES, $invoice_data);
						}
						if (!$is_insert) {
							// Delete student fees not in fees array.
							$student_id_fee_labels = array_merge(array($student_id), array_values($fees));

							$success = $wpdb->query($wpdb->prepare('DELETE FROM ' . WLSM_STUDENT_FEES . ' WHERE student_record_id = %d AND label NOT IN (' . implode(', ', $place_holders_fee_labels) . ')', $student_id_fee_labels));
						}
					} else {
						// Delete student fees not in fees array.
						$success = $wpdb->query($wpdb->prepare('DELETE FROM ' . WLSM_STUDENT_FEES . ' WHERE student_record_id = %d', $student_id));
					}

					$buffer = ob_get_clean();
					if ( ! empty( $buffer ) ) {
						throw new Exception( $buffer );
					}

					if ( false === $success ) {
						throw new Exception( $wpdb->last_error );
					}
				}

				fclose( $csv_file );

				$message = esc_html__( 'Students imported successfully.', 'school-management' );

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success( array( 'message' => $message ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				fclose( $csv_file );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function bulk_import_books() {
		$current_user = WLSM_M_Role::can( 'manage_library' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];
		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['bulk-import-books'], 'bulk-import-books' ) ) {
				die();
			}

			// Start validation.
			$errors = array();

			$csv = ( isset( $_FILES['csv'] ) && is_array( $_FILES['csv'] ) ) ? $_FILES['csv'] : NULL;

			if ( isset( $csv['tmp_name'] ) && ! empty( $csv['tmp_name'] ) ) {
				if ( ! WLSM_Helper::is_valid_file( $csv, 'csv' ) ) {
					$errors['csv'] = esc_html__( 'Please provide valid csv file.', 'school-management' );
				}
			} else {
				$errors['csv'] = esc_html__( 'Please provide valid csv file.', 'school-management' );
			}

			if ( count( $errors ) >= 1 ) {
				wp_send_json_error( $errors );
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

				$csv_file = fopen( $csv['tmp_name'], 'r' );

				fgetcsv( $csv_file );

				$row = 1;
				while ( $line = fgetcsv( $csv_file ) ) {
					$row++;

					$title       = sanitize_text_field( $line[0] );
					$author      = sanitize_text_field( $line[1] );
					$subject     = sanitize_text_field( $line[2] );
					$price       = sanitize_text_field( $line[3] );
					$quantity    = sanitize_text_field( $line[4] );
					$description = sanitize_text_field( $line[5] );
					$rack_number = sanitize_text_field( $line[6] );
					$book_number = sanitize_text_field( $line[7] );
					$isbn_number = sanitize_text_field( $line[8] );

					// book Detail.
					if ( empty( $title ) ) {
						throw new Exception( esc_html__( 'Please specify book title.', 'school-management' ). WLSM_Import::get_csv_error_msg( $row, 1 ));
					}
					if ( strlen( $title ) > 100 ) {
						throw new Exception(esc_html__( 'Maximum length cannot exceed 100 characters.', 'school-management' ). WLSM_Import::get_csv_error_msg( $row, 1 ));
					}

					if ( strlen( $author ) > 60 ) {
						throw new Exception(esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' ). WLSM_Import::get_csv_error_msg( $row, 2));
					}

					if ( strlen( $subject ) > 100 ) {
						throw new Exception(esc_html__( 'Maximum length cannot exceed 100 characters.', 'school-management'). WLSM_Import::get_csv_error_msg( $row, 3 ) );
					}

					if ( strlen( $rack_number ) > 40 ) {
						throw new Exception(esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' ). WLSM_Import::get_csv_error_msg( $row, 6 ));
					}

					if ( strlen( $book_number ) > 100 ) {
						throw new Exception(esc_html__( 'Maximum length cannot exceed 100 characters.', 'school-management' ). WLSM_Import::get_csv_error_msg( $row, 8 ));
					}

					if ( strlen( $isbn_number ) > 100 ) {
						throw new Exception(esc_html__( 'Maximum length cannot exceed 100 characters.', 'school-management' ). WLSM_Import::get_csv_error_msg( $row, 8 ));
					}

					// Book data.
					$data = array(
						'title'       => $title,
						'author'      => $author,
						'subject'     => $subject,
						'description' => $description,
						'rack_number' => $rack_number,
						'book_number' => $book_number,
						'isbn_number' => $isbn_number,
						'price'       => $price,
						'quantity'    => $quantity
					);

					if ( $book_id ) {
						$data['updated_at'] = current_time( 'Y-m-d H:i:s' );

						$success = $wpdb->update( WLSM_BOOKS, $data, array( 'ID' => $book_id, 'school_id' => $school_id ) );
					} else {
						$data['created_at'] = current_time( 'Y-m-d H:i:s' );

						$data['school_id'] = $school_id;

						$success = $wpdb->insert( WLSM_BOOKS, $data );
					}

					$buffer = ob_get_clean();
					if ( ! empty( $buffer ) ) {
						throw new Exception( $buffer );
					}

					if ( false === $success ) {
						throw new Exception( $wpdb->last_error );
					}
				}

				fclose( $csv_file );

				$message = esc_html__( 'books imported successfully.', 'school-management' );

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success( array( 'message' => $message ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				fclose( $csv_file );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function bulk_import_staff() {
		$current_user = WLSM_M_Role::can( 'manage_employees' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];
		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['bulk-import-staff'], 'bulk-import-staff' ) ) {
				die();
			}

			// Start validation.
			$errors = array();

			$csv = ( isset( $_FILES['csv'] ) && is_array( $_FILES['csv'] ) ) ? $_FILES['csv'] : NULL;

			if ( isset( $csv['tmp_name'] ) && ! empty( $csv['tmp_name'] ) ) {
				if ( ! WLSM_Helper::is_valid_file( $csv, 'csv' ) ) {
					$errors['csv'] = esc_html__( 'Please provide valid csv file.', 'school-management' );
				}
			} else {
				$errors['csv'] = esc_html__( 'Please provide valid csv file.', 'school-management' );
			}

			if ( count( $errors ) >= 1 ) {
				wp_send_json_error( $errors );
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

				$csv_file = fopen( $csv['tmp_name'], 'r' );

				fgetcsv( $csv_file );

				$row = 1;
				while ( $line = fgetcsv( $csv_file ) ) {
					$row++;

					$name          = sanitize_text_field( $line[0] );
					$gender        = sanitize_text_field( $line[1] );
					$dob           = ! empty( $line[2] ) ? DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $line[2] ) ) : NULL;
					$address       = sanitize_text_field( $line[3] );
					$phone         = sanitize_text_field( $line[4] );
					$email         = sanitize_text_field( $line[5] );
					$joining_date  = ! empty( $line[6] ) ? DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $line[6] ) ) : NULL;
					$role          = sanitize_text_field( $line[7] );
					$note          = sanitize_text_field( $line[8] );
					$salary        = sanitize_text_field( $line[9] );
					$designation   = sanitize_text_field( $line[10] );
					$class_label   = sanitize_text_field( $line[11] );
					$section_label = sanitize_text_field( $line[12] );
					$is_active     = (bool)( $line[13] );

					// staff Detail.
					if ( empty( $name ) ) {
						throw new Exception( esc_html__( 'Please specify student name.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 1 ) );
					}
					if ( strlen( $name ) > 60 ) {
						throw new Exception( esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 1 ) );
					}

					if (!empty($phone) && strlen($phone) > 40) {
						throw new Exception(esc_html__('Maximum length cannot exceed 40 characters.', 'school-management')). WLSM_Import::get_csv_error_msg( $row, 4 );
					}
					if (!empty($email)) {
						if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
							throw new Exception(esc_html__('Please provide a valid email.', 'school-management')). WLSM_Import::get_csv_error_msg( $row, 5 );
						} elseif (strlen($email) > 60) {
							throw new Exception( esc_html__('Maximum length cannot exceed 60 characters.', 'school-management')). WLSM_Import::get_csv_error_msg( $row, 5 );
						}
					}
					if (!empty($designation) && strlen($designation) > 80) {
						throw new Exception( esc_html__('Maximum length cannot exceed 80 characters.', 'school-management')). WLSM_Import::get_csv_error_msg( $row, 10 );
					}
					if (!in_array($gender, array_keys(WLSM_Helper::gender_list()))) {
						$gender = NULL;
					}

					if (!empty($dob)) {
						$dob = $dob->format('Y-m-d');
					} else {
						$dob = NULL;
					}

					// Joining Detail.
					if (!empty($joining_date)) {
						$joining_date = $joining_date->format('Y-m-d');
					} else {
						$joining_date = NULL;
					}

					if ( empty( $class_label ) ) {
						throw new Exception( esc_html__( 'Please specify class.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 4 ) );
					} else {
						// Search class with this label.
						// Checks if class exists in the school.
						$class_school = WLSM_M_Staff_Class::get_class_with_label( $school_id, $class_label );
						if ( ! $class_school ) {
							throw new Exception( esc_html__( 'Class not found.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 4 ) );
						} else {
							$class_school_id = $class_school->ID;
							$class_id        = $class_school->class_id;
						}

						if ( empty( $section_label ) ) {
							throw new Exception( esc_html__( 'Please specify section.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 5 ) );
						} else {
							// Search section with this label.
							// Checks if section exists.
							$section = WLSM_M_Staff_Class::get_section_with_label( $school_id, $section_label, $class_school_id );

							if ( ! $section ) {
								throw new Exception( esc_html__( 'Section not found.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 5 ) );
							} else {
								$section_id = $section->ID;
							}

						}
					}
					if (WLSM_M_Role::get_admin_key() === $role) {
						$staff_role = NULL;
					}
					if (!empty($role)) {
						$role_id = WLSM_M_Staff_Class::get_staff_role_id( $school_id, $role );
						$staff_role = $role_id->ID;
					}
					$role_permissions = [];
					$permissions = [];
					// Permissions.
						if (empty($staff_role)) {
							$staff_role = NULL;
						} else {
							$staff_role_exists = WLSM_M_Staff_General::fetch_role($school_id, $staff_role);
							if (!$staff_role_exists) {
								$errors['role'] = esc_html__('Please select valid staff role.', 'school-management');
							} else {
								$role_permissions = $staff_role_exists->permissions;

								if (is_serialized($role_permissions)) {
									$role_permissions = unserialize($role_permissions);
									$permissions      = array_unique(array_merge($role_permissions, $permissions));
								}
							}
						}

					// Admin data.
					$admin_data = array(
						'name'         => $name,
						'gender'       => $gender,
						'dob'          => $dob,
						'phone'        => $phone,
						'email'        => $email,
						'address'      => $address,
						'salary'       => $salary,
						'designation'  => $designation,
						'note'         => $note,
						'joining_date' => $joining_date,
						'section_id'   => $section_id,
						'role_id'      => $staff_role,
						'is_active'    => $is_active,
					);
					$role = 'employee';
					$staff_data = array(
						'role'        => $role,
						'permissions' => serialize($role_permissions),
						// 'user_id'     => $user_id,
					);

					if ($staff_data) {
						// Add staff.
						$staff_data['created_at'] = current_time('Y-m-d H:i:s');

						$staff_data['school_id'] = $school_id;

						$success = $wpdb->insert(WLSM_STAFF, $staff_data);

						$staff_id = $wpdb->insert_id;
					}


				if ($admin_data) {
					// Add admin.
					$admin_data['created_at'] = current_time('Y-m-d H:i:s');

					$admin_data['staff_id'] = $staff_id;

					$success = $wpdb->insert(WLSM_ADMINS, $admin_data);
				}

					if ( false === $success ) {
						throw new Exception( $wpdb->last_error );
					}
				}

				fclose( $csv_file );

				$message = esc_html__( 'Staff imported successfully.', 'school-management' );

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success( array( 'message' => $message ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				fclose( $csv_file );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}


	public static function bulk_import_attendance() {
		$current_user = WLSM_M_Role::can('manage_attendance');

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$errors = array();

			$csv = ( isset( $_FILES['csv'] ) && is_array( $_FILES['csv'] ) ) ? $_FILES['csv'] : NULL;

			$subject_id    = isset($_POST['subject_id']) ? absint($_POST['subject_id']) : 0;
			$attendance_by = isset($_POST['attendance_by']) ? sanitize_text_field($_POST['attendance_by']) : 'all';

			if ( isset( $csv['tmp_name'] ) && ! empty( $csv['tmp_name'] ) ) {
				if ( ! WLSM_Helper::is_valid_file( $csv, 'csv' ) ) {
					$errors['csv'] = esc_html__( 'Please provide valid csv file.', 'school-management' );
				}
			} else {
				$errors['csv'] = esc_html__( 'Please provide valid csv file.', 'school-management' );
			}

			if ( count( $errors ) >= 1 ) {
				wp_send_json_error( $errors );
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

				$csv_file = fopen( $csv['tmp_name'], 'r' );

				fgetcsv( $csv_file );

				$row = 1;
				while ( $line = fgetcsv( $csv_file ) ) {
					$row++;

					$name            = sanitize_text_field( $line[0] );
					$roll_number     = sanitize_text_field( $line[1] );
					$student_id      = sanitize_text_field( $line[2] );
					$attendance_date = ! empty( $line[3] ) ? DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $line[3] ) ) : NULL;
					$status          = sanitize_text_field( $line[4] );

					// Personal Detail.
					if ( empty( $name ) ) {
						throw new Exception( esc_html__( 'Please specify student name.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 1 ) );
					}
					if ( strlen( $name ) > 60 ) {
						throw new Exception( esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 1 ) );
					}

					if ( empty( $attendance_date ) ) {
						throw new Exception( esc_html__('Please provide admission date. [ CHANGE DATE FORMAT INSIDE SCHOOL MANAGEMENT SETTINGS ]', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 3 ) );
					} else {
						$attendance_date = $attendance_date->format( 'Y-m-d' );
					}

					if (!empty($status)) {
						if ($attendance_by === 'subject' ) {
							$sql = 'INSERT INTO ' . WLSM_ATTENDANCE . ' (attendance_date, student_record_id, added_by, subject_id, status) VALUES ("%s", %d, %d, %d, "%s") ON DUPLICATE KEY UPDATE status = "%s", subject_id = %d, updated_at = "%s"';

							$success = $wpdb->query($wpdb->prepare($sql, $attendance_date, $student_id, get_current_user_id(), $subject_id, $status, $status, $subject_id, current_time('Y-m-d H:i:s')));

						} else {
							$sql = 'INSERT INTO ' . WLSM_ATTENDANCE . ' (attendance_date, student_record_id, added_by, status) VALUES ("%s", %d, %d, "%s") ON DUPLICATE KEY UPDATE status = "%s", updated_at = "%s"';

							$success = $wpdb->query($wpdb->prepare($sql, $attendance_date, $student_id, get_current_user_id(), $status, $status, current_time('Y-m-d H:i:s')));
						}


					$buffer = ob_get_clean();
					if ( ! empty( $buffer ) ) {
						throw new Exception( $buffer );
					}

					if ( false === $success ) {
						throw new Exception( $wpdb->last_error );
					}
					}
				}

				fclose( $csv_file );

				$message = esc_html__( 'Staff imported successfully.', 'school-management' );

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success( array( 'message' => $message ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				fclose( $csv_file );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function bulk_import_exam_results() {
		$current_user = WLSM_M_Role::can( 'manage_admissions' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$exam_id = isset( $_POST['exam_id'] ) ? absint( $_POST['exam_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'bulk-import-results-' . $exam_id ], 'bulk-import-results-' . $exam_id ) ) {
				die();
			}

			$exam = WLSM_M_Staff_Examination::fetch_exam( $school_id, $exam_id );

			if ( ! $exam ) {
				die;
			}

			// Start validation.
			$errors = array();

			$csv = ( isset( $_FILES['csv'] ) && is_array( $_FILES['csv'] ) ) ? $_FILES['csv'] : NULL;

			if ( isset( $csv['tmp_name'] ) && ! empty( $csv['tmp_name'] ) ) {
				if ( ! WLSM_Helper::is_valid_file( $csv, 'csv' ) ) {
					$errors['csv'] = esc_html__( 'Please provide valid csv file.', 'school-management' );
				}
			} else {
				$errors['csv'] = esc_html__( 'Please provide valid csv file.', 'school-management' );
			}

			if ( count( $errors ) >= 1 ) {
				wp_send_json_error( $errors );
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

				$exam_papers = WLSM_M_Staff_Examination::get_exam_papers_by_exam_id( $school_id, $exam_id );

				$cols_before_papers = 4;

				$csv_file = fopen( $csv['tmp_name'], 'r' );

				fgetcsv( $csv_file );

				$row = 1;
				while ( $line = fgetcsv( $csv_file ) ) {
					$row++;

					$exam_roll_number = isset( $line[0] ) ? sanitize_text_field( $line[0] ) : '';

					if ( empty( $exam_roll_number ) ) {
						throw new Exception( esc_html__( 'Please provide exam roll number.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 1 ) );
					}

					// Checks if admit card exists for exam roll number.
					$admit_card = WLSM_M_Staff_Examination::get_admit_card_by_exam_roll_number( $school_id, $exam_id, $exam_roll_number );

					if ( ! $admit_card ) {
						throw new Exception( esc_html__( 'Admit card not found.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, 1 ) );
					}

					$admit_card_id = $admit_card->ID;

					$obtained_marks_col_before = 3;

					$exam_results = WLSM_M_Staff_Examination::get_exam_results_by_admit_card( $school_id, $admit_card_id );

					$i = 0;
					foreach ( $exam_papers as $key => $exam_paper ) {
						$i++;

						$obtained_marks_col = $obtained_marks_col_before + $i;
						$obtained_remark_col = $obtained_marks_col + 1;
						$i++;

						$obtained_marks_input = ( isset( $line[ $obtained_marks_col ] ) && ( '' !== $line[ $obtained_marks_col ] ) ) ? WLSM_Config::sanitize_marks( $line[ $obtained_marks_col ] ) : '';
						$obtained_remark_input = ( isset( $line[ $obtained_remark_col ] ) && ( '' !== $line[ $obtained_remark_col ] ) ) ? ( $line[ $obtained_remark_col ] ) : '';

						if ( '' === $obtained_marks_input ) {
							throw new Exception( esc_html__( 'Please specify marks obtained.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, $obtained_marks_col + 1 ) );
						}

						if ( $obtained_marks_input > $exam_paper->maximum_marks ) {
							throw new Exception( esc_html__( 'Marks obtained can\'t be greater than maximum marks.', 'school-management' ) . WLSM_Import::get_csv_error_msg( $row, $obtained_marks_col + 1 ) );
						}

						$marks_obtained = $obtained_marks_input;

						if ( isset( $exam_results[ $exam_paper->ID ] ) ) {
							// If result exists, update.
							$exam_result = $exam_results[ $exam_paper->ID ];

							$exam_result_data = array(
								'obtained_marks' => $marks_obtained,
								'remark'         => $obtained_remark_input,
								'updated_at'     => current_time( 'Y-m-d H:i:s' )
							);

							$success = $wpdb->update( WLSM_EXAM_RESULTS, $exam_result_data, array( 'ID' => $exam_result->ID ) );

						} else {
							// If result do not exist, insert.
							$exam_result_data = array(
								'obtained_marks' => $marks_obtained,
								'remark'         => $obtained_remark_input,
								'exam_paper_id'  => $exam_paper->ID,
								'admit_card_id'  => $admit_card_id
							);

							$exam_result_data['created_at'] = current_time( 'Y-m-d H:i:s' );

							$success = $wpdb->insert( WLSM_EXAM_RESULTS, $exam_result_data );
						}
					}

					$buffer = ob_get_clean();
					if ( ! empty( $buffer ) ) {
						throw new Exception( $buffer );
					}

					if ( false === $success ) {
						throw new Exception( $wpdb->last_error );
					}
				}

				fclose( $csv_file );

				$message = esc_html__( 'Exam results imported successfully.', 'school-management' );

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success( array( 'message' => $message ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				fclose( $csv_file );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}
}
