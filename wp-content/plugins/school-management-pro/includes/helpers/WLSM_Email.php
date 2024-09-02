<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Setting.php';

class WLSM_Email {
	public static function email_carriers() {
		return array(
			'wp_mail' => esc_html__( 'WP Mail', 'school-management' ),
			'smtp'    => esc_html__( 'SMTP', 'school-management' ),
		);
	}

	public static function send_email( $school_id, $to, $subject, $body, $name = '', $email_for = '', $placeholders = array(), $attachments = null ) {
		if ( ! empty( $email_for ) && count( $placeholders ) ) {
			if ( 'student_admission' === $email_for ) {
				$available_placeholders = array_keys( self::student_admission_placeholders() );
			} elseif ( 'invoice_generated' === $email_for ) {
				$available_placeholders = array_keys( self::invoice_generated_placeholders() );
			}  elseif ( 'invoice_due_date' === $email_for ) {
				$available_placeholders = array_keys( self::invoice_generated_placeholders() );
			} elseif ( 'online_fee_submission' === $email_for ) {
				$available_placeholders = array_keys( self::online_fee_submission_placeholders() );
			} elseif ( 'offline_fee_submission' === $email_for ) {
				$available_placeholders = array_keys( self::offline_fee_submission_placeholders() );
			} elseif ( 'absent_student' === $email_for ) {
				$available_placeholders = array_keys( self::absent_student_placeholders() );
			} elseif ( 'custom_message' === $email_for ) {
				$available_placeholders = array_keys( self::custom_message_placeholders() );
			} elseif ( 'inquiry_received_to_inquisitor' === $email_for ) {
				$available_placeholders = array_keys( self::inquiry_received_to_inquisitor_placeholders() );
			} elseif ( 'inquiry_received_to_admin' === $email_for ) {
				$available_placeholders = array_keys( self::inquiry_received_to_admin_placeholders() );
			} elseif ( 'student_registration_to_student' === $email_for ) {
				$available_placeholders = array_keys( self::student_registration_to_student_placeholders() );
			} elseif ( 'student_registration_to_admin' === $email_for ) {
				$available_placeholders = array_keys( self::student_registration_to_admin_placeholders() );
			}

			if ( isset( $available_placeholders ) ) {
				foreach ( $placeholders as $key => $value ) {
					if ( in_array( $key, $available_placeholders ) ) {
						$subject = str_replace( $key, $value, $subject );
						$body    = str_replace( $key, $value, $body );
					}
				}
			}
		}

		$settings_email = WLSM_M_Setting::get_settings_email( $school_id );
		$email_carrier  = $settings_email['carrier'];

		if ( 'wp_mail' === $email_carrier ) {
			$wp_mail    = WLSM_M_Setting::get_settings_wp_mail( $school_id );
			$from_name  = $wp_mail['from_name'];
			$from_email = $wp_mail['from_email'];

			if ( is_array( $to ) ) {
				foreach ( $to as $key => $value ) {
					$to[ $key ]	= $name[ $key ] . ' <' . $value . '>';
				}
			} else {
				if ( ! empty( $name ) ) {
					$to = "$name <$to>";
				}
			}

			$headers = array();
			array_push( $headers, 'Content-Type: text/html; charset=UTF-8' );
			if ( ! empty( $from_name ) ) {
				array_push( $headers, "From: $from_name <$from_email>" );
			}

			$status = wp_mail( $to, html_entity_decode( $subject ), $body, $headers, array(), $attachments );
			return $status;

		} elseif ( 'smtp' === $email_carrier ) {
			$smtp       = WLSM_M_Setting::get_settings_smtp( $school_id );
			$from_name  = $smtp['from_name'];
			$host       = $smtp['host'];
			$username   = $smtp['username'];
			$password   = $smtp['password'];
			$encryption = $smtp['encryption'];
			$port       = $smtp['port'];


			global $wp_version;
	
			require_once(ABSPATH . WPINC . '/PHPMailer/PHPMailer.php');
			require_once(ABSPATH . WPINC . '/PHPMailer/SMTP.php');
			require_once(ABSPATH . WPINC . '/PHPMailer/Exception.php');
			$mail = new PHPMailer\PHPMailer\PHPMailer( true );

			try {
				$mail->CharSet  = 'UTF-8';
				$mail->Encoding = 'base64';

				if ( $host && $port ) {
					$mail->IsSMTP();
					$mail->Host = $host;
					if ( ! empty( $username ) && ! empty( $password ) ) {
						$mail->SMTPAuth = true;
						$mail->Password = $password;
					} else {
						$mail->SMTPAuth = false;
					}
					if ( ! empty( $encryption ) ) {
						$mail->SMTPSecure = $encryption;
					} else {
						$mail->SMTPSecure = NULL;
					}
					$mail->Port = $port;
				}

				$mail->Username = $username;

				$mail->setFrom( $mail->Username, $from_name );

				$mail->Subject = html_entity_decode( $subject );
				$mail->Body    = $body;

				$result = print_r( $attachments, true );
				// error_log( $result );
				if ($attachments) {
					$mail->addStringAttachment( $attachments, 'invoice.pdf', 'base64', 'application/pdf' );
				}

					$mail->IsHTML( true );

					if ( is_array( $to ) ) {
						foreach ( $to as $key => $value ) {
							$mail->AddAddress( $value, $name[ $key ] );
						}
					} else {
						$mail->AddAddress( $to, $name );
					}

					$status = $mail->Send();
					return $status;

			} catch( Exception $e ) {
			}

			return false;
		}
	}

	public static function student_admission_placeholders() {
		return array(
			'[STUDENT_NAME]'      => esc_html__( 'Student Name', 'school-management' ),
			'[CLASS]'             => esc_html__( 'Class', 'school-management' ),
			'[SECTION]'           => esc_html__( 'Section', 'school-management' ),
			'[ROLL_NUMBER]'       => esc_html__( 'Roll Number', 'school-management' ),
			'[ENROLLMENT_NUMBER]' => esc_html__( 'Enrollment Number', 'school-management' ),
			'[ADMISSION_NUMBER]'  => esc_html__( 'Admission Number', 'school-management' ),
			'[LOGIN_USERNAME]'    => esc_html__( 'Login Username', 'school-management' ),
			'[LOGIN_EMAIL]'       => esc_html__( 'Login Email Number', 'school-management' ),
			'[LOGIN_PASSWORD]'    => esc_html__( 'Login Password', 'school-management' ),
			'[SCHOOL_NAME]'       => esc_html__( 'School Name', 'school-management' ),
		);
	}

	public static function student_registration_to_student_placeholders() {
		return array(
			'[STUDENT_NAME]'      => esc_html__( 'Student Name', 'school-management' ),
			'[CLASS]'             => esc_html__( 'Class', 'school-management' ),
			'[SECTION]'           => esc_html__( 'Section', 'school-management' ),
			'[ROLL_NUMBER]'       => esc_html__( 'Roll Number', 'school-management' ),
			'[ENROLLMENT_NUMBER]' => esc_html__( 'Enrollment Number', 'school-management' ),
			'[ADMISSION_NUMBER]'  => esc_html__( 'Admission Number', 'school-management' ),
			'[LOGIN_USERNAME]'    => esc_html__( 'Login Username', 'school-management' ),
			'[LOGIN_EMAIL]'       => esc_html__( 'Login Email Number', 'school-management' ),
			'[LOGIN_PASSWORD]'    => esc_html__( 'Login Password', 'school-management' ),
			'[SCHOOL_NAME]'       => esc_html__( 'School Name', 'school-management' ),
		);
	}

	public static function student_registration_to_admin_placeholders() {
		return array(
			'[STUDENT_NAME]'      => esc_html__( 'Student Name', 'school-management' ),
			'[CLASS]'             => esc_html__( 'Class', 'school-management' ),
			'[SECTION]'           => esc_html__( 'Section', 'school-management' ),
			'[ROLL_NUMBER]'       => esc_html__( 'Roll Number', 'school-management' ),
			'[ENROLLMENT_NUMBER]' => esc_html__( 'Enrollment Number', 'school-management' ),
			'[ADMISSION_NUMBER]'  => esc_html__( 'Admission Number', 'school-management' ),
			'[LOGIN_USERNAME]'    => esc_html__( 'Login Username', 'school-management' ),
			'[LOGIN_EMAIL]'       => esc_html__( 'Login Email Number', 'school-management' ),
			'[LOGIN_PASSWORD]'    => esc_html__( 'Login Password', 'school-management' ),
			'[SCHOOL_NAME]'       => esc_html__( 'School Name', 'school-management' ),
		);
	}

	public static function invoice_generated_placeholders() {
		return array(
			'[INVOICE_TITLE]'       => esc_html__( 'Invoice Title', 'school-management' ),
			'[INVOICE_NUMBER]'      => esc_html__( 'Invoice Number', 'school-management' ),
			'[INVOICE_PAYABLE]'     => esc_html__( 'Invoice Payable', 'school-management' ),
			'[INVOICE_DATE_ISSUED]' => esc_html__( 'Invoice Date Issued', 'school-management' ),
			'[INVOICE_DUE_DATE]'    => esc_html__( 'Invoice Due Date', 'school-management' ),
			'[STUDENT_NAME]'        => esc_html__( 'Student Name', 'school-management' ),
			'[CLASS]'               => esc_html__( 'Class', 'school-management' ),
			'[SECTION]'             => esc_html__( 'Section', 'school-management' ),
			'[ROLL_NUMBER]'         => esc_html__( 'Roll Number', 'school-management' ),
			'[ENROLLMENT_NUMBER]'   => esc_html__( 'Enrollment Number', 'school-management' ),
			'[ADMISSION_NUMBER]'    => esc_html__( 'Admission Number', 'school-management' ),
			'[SCHOOL_NAME]'         => esc_html__( 'School Name', 'school-management' ),
		);
	}

	public static function online_fee_submission_placeholders() {
		return array(
			'[INVOICE_TITLE]'       => esc_html__( 'Invoice Title', 'school-management' ),
			'[RECEIPT_NUMBER]'      => esc_html__( 'Receipt Number', 'school-management' ),
			'[AMOUNT]'              => esc_html__( 'AMOUNT', 'school-management' ),
			'[PAYMENT_METHOD]'      => esc_html__( 'Payment Method', 'school-management' ),
			'[DATE]'                => esc_html__( 'Date', 'school-management' ),
			'[STUDENT_NAME]'        => esc_html__( 'Student Name', 'school-management' ),
			'[CLASS]'               => esc_html__( 'Class', 'school-management' ),
			'[SECTION]'             => esc_html__( 'Section', 'school-management' ),
			'[ROLL_NUMBER]'         => esc_html__( 'Roll Number', 'school-management' ),
			'[ENROLLMENT_NUMBER]'   => esc_html__( 'Enrollment Number', 'school-management' ),
			'[ADMISSION_NUMBER]'    => esc_html__( 'Admission Number', 'school-management' ),
			'[SCHOOL_NAME]'         => esc_html__( 'School Name', 'school-management' ),
		);
	}

	public static function offline_fee_submission_placeholders() {
		return array(
			'[INVOICE_TITLE]'       => esc_html__( 'Invoice Title', 'school-management' ),
			'[RECEIPT_NUMBER]'      => esc_html__( 'Receipt Number', 'school-management' ),
			'[AMOUNT]'              => esc_html__( 'AMOUNT', 'school-management' ),
			'[PAYMENT_METHOD]'      => esc_html__( 'Payment Method', 'school-management' ),
			'[DATE]'                => esc_html__( 'Date', 'school-management' ),
			'[STUDENT_NAME]'        => esc_html__( 'Student Name', 'school-management' ),
			'[CLASS]'               => esc_html__( 'Class', 'school-management' ),
			'[SECTION]'             => esc_html__( 'Section', 'school-management' ),
			'[ROLL_NUMBER]'         => esc_html__( 'Roll Number', 'school-management' ),
			'[ENROLLMENT_NUMBER]'   => esc_html__( 'Enrollment Number', 'school-management' ),
			'[ADMISSION_NUMBER]'    => esc_html__( 'Admission Number', 'school-management' ),
			'[SCHOOL_NAME]'         => esc_html__( 'School Name', 'school-management' ),
		);
	}

	public static function absent_student_placeholders() {
		return array(
			'[ATTENDANCE_DATE]'   => esc_html__('Attendance Date', 'school-management'),
			'[STUDENT_NAME]'      => esc_html__('Student Name', 'school-management'),
			'[CLASS]'             => esc_html__('Class', 'school-management'),
			'[SECTION]'           => esc_html__('Section', 'school-management'),
			'[ROLL_NUMBER]'       => esc_html__('Roll Number', 'school-management'),
			'[ENROLLMENT_NUMBER]' => esc_html__('Enrollment Number', 'school-management'),
			'[ADMISSION_NUMBER]'  => esc_html__('Admission Number', 'school-management'),
			'[SCHOOL_NAME]'       => esc_html__('School Name', 'school-management'),
		);
	}

	public static function inquiry_received_to_inquisitor_placeholders() {
		return array(
			'[NAME]'  => esc_html__( 'Inquisitor Name', 'school-management' ),
			'[PHONE]' => esc_html__( 'Inquisitor Phone', 'school-management' ),
			'[EMAIL]' => esc_html__( 'Inquisitor Email', 'school-management' ),
			'[CLASS]' => esc_html__( 'Inquisitor Class', 'school-management' )
		);
	}

	public static function inquiry_received_to_admin_placeholders() {
		return array(
			'[NAME]'  => esc_html__( 'Inquisitor Name', 'school-management' ),
			'[PHONE]' => esc_html__( 'Inquisitor Phone', 'school-management' ),
			'[EMAIL]' => esc_html__( 'Inquisitor Email', 'school-management' ),
			'[CLASS]' => esc_html__( 'Inquisitor Class', 'school-management' )
		);
	}

	public static function custom_message_placeholders() {
		return array(
			'[STUDENT_NAME]'      => esc_html__( 'Student Name', 'school-management' ),
			'[CLASS]'             => esc_html__( 'Class', 'school-management' ),
			'[SECTION]'           => esc_html__( 'Section', 'school-management' ),
			'[ROLL_NUMBER]'       => esc_html__( 'Roll Number', 'school-management' ),
			'[ENROLLMENT_NUMBER]' => esc_html__( 'Enrollment Number', 'school-management' ),
			'[ADMISSION_NUMBER]'  => esc_html__( 'Admission Number', 'school-management' ),
			'[LOGIN_USERNAME]'    => esc_html__( 'Login Username', 'school-management' ),
			'[LOGIN_EMAIL]'       => esc_html__( 'Login Email Number', 'school-management' ),
			'[SCHOOL_NAME]'       => esc_html__( 'School Name', 'school-management' ),
		);
	}
}
