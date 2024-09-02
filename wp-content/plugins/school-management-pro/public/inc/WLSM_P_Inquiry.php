<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Class.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_General.php';

class WLSM_P_Inquiry {
	public static function submit_inquiry() {
		if ( ! wp_verify_nonce( $_POST['wlsm-submit-inquiry'], 'wlsm-submit-inquiry' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$gdpr_enable = get_option( 'wlsm_gdpr_enable' );

			// Inquiry.
			$name      = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
			$phone     = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
			$email     = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
			$message   = isset( $_POST['message'] ) ? sanitize_text_field( $_POST['message'] ) : '';
			$class_id  = isset( $_POST['class_id'] ) ? absint( $_POST['class_id'] ) : 0;
			$section_id  = isset( $_POST['section_id'] ) ? absint( $_POST['section_id'] ) : 0;
			$school_id = isset( $_POST['school_id'] ) ? absint( $_POST['school_id'] ) : 0;
			$reference = isset( $_POST['reference'] ) ? sanitize_text_field( $_POST['reference'] ) : '';

			// Start validation.
			$errors = array();

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

			// Inquiry settings.
			$settings_inquiry               = WLSM_M_Setting::get_settings_inquiry( $school_id );
			$school_inquiry_phone_required  = $settings_inquiry['phone_required'];
			$school_inquiry_email_required  = $settings_inquiry['email_required'];
			$school_inquiry_success_message = $settings_inquiry['success_message'];

			if ( empty( $name ) ) {
				$errors['name'] = esc_html__( 'Please specify name.', 'school-management' );
			}
			if ( strlen( $name ) > 60 ) {
				$errors['name'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
			}

			if ( $school_inquiry_phone_required && empty( $phone ) ) {
				$errors['phone'] = esc_html__( 'Please provide your phone number.', 'school-management' );
			} else if ( strlen( $phone ) > 40 ) {
				$errors['phone'] = esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' );
			}

			if ( $school_inquiry_email_required && empty( $email ) ) {
				$errors['email'] = esc_html__( 'Please provide a valid email.', 'school-management' );
			} else if ( ! empty( $email ) && strlen( $email ) > 60 ) {
				$errors['email'] = esc_html__( 'Please provide a valid email.', 'school-management' );
			} else if ( strlen( $email ) > 60 ) {
				$errors['email'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
			}

			if ($email) {
				$inquiry = WLSM_M_Staff_General::get_inquiry_email($email);
					if ($inquiry) {
						$errors['email'] = esc_html__('This email already exists', 'school-management');
					}
			}

			if ( empty( $message ) ) {
				$errors['message'] = esc_html__( 'Please write your message.', 'school-management' );
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
				$wpdb->query( 'BEGIN;' );

				$settings_inquiry               = WLSM_M_Setting::get_settings_inquiry( $school_id );
				$school_inquiry_redirect_url    = $settings_inquiry['inquiry_redirect_url'];

				// Inquiry data.
				$data = array(
					'name'            => $name,
					'phone'           => $phone,
					'email'           => $email,
					'message'         => $message,
					'class_school_id' => $class_school_id,
					'section_id'      => $section_id,
					'school_id'       => $school_id,
					'reference'       => $reference,
				);

				if ( $gdpr_enable ) {
					$data['gdpr_agreed'] = $gdpr;
				}

				$data['created_at'] = current_time( 'Y-m-d H:i:s' );

				$success = $wpdb->insert( WLSM_INQUIRIES, $data );

				$new_inquiry_id = $wpdb->insert_id;

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$placeholders = array(
					'[NAME]'  => stripcslashes( $name ),
					'[PHONE]' => $phone,
					'[EMAIL]' => $email,
					'[CLASS]' => stripcslashes( $class_label ),
				);

				$school_inquiry_success_placeholders = array_keys( WLSM_Helper::inquiry_success_message_placeholders() );

				foreach ( $placeholders as $key => $value ) {
					if ( in_array( $key, $school_inquiry_success_placeholders ) ) {
						$school_inquiry_success_message = str_replace( $key, $value, $school_inquiry_success_message );
					}
				}

				$message = $school_inquiry_success_message;
				$reset   = true;

				$wpdb->query( 'COMMIT;' );

				if ( isset( $new_inquiry_id ) ) {
					// Notify for inquiry received to inquisitor and admin.
					$data = array(
						'school_id'  => $school_id,
						'inquiry_id' => $new_inquiry_id,
					);
					wp_schedule_single_event( time() + 30, 'wlsm_notify_for_inquiry_received_to_inquisitor', $data );
					wp_schedule_single_event( time() + 30, 'wlsm_notify_for_inquiry_received_to_admin', $data );
				}

				wp_send_json_success( array( 'message' => $message, 'redirect_url' => $school_inquiry_redirect_url ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}
}
