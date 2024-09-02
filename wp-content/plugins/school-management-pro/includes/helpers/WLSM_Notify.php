<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Setting.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Email.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_SMS.php';

class WLSM_Notify {
	public static function notify_for_student_admission( $data ) {
		$school_id = $data['school_id'];

		$settings_email_student_admission = WLSM_M_Setting::get_settings_email_student_admission( $school_id );
		$email_student_admission_enable   = $settings_email_student_admission['enable'];

		$settings_sms_student_admission = WLSM_M_Setting::get_settings_sms_student_admission( $school_id );
		$sms_student_admission_enable   = $settings_sms_student_admission['enable'];

		if ( $email_student_admission_enable || $sms_student_admission_enable ) {
			global $wpdb;
			$student = $wpdb->get_row(
				$wpdb->prepare( 'SELECT sr.name as student_name, sr.email, sr.phone, sr.admission_number, sr.enrollment_number, c.label as class_label, se.label as section_label, sr.roll_number, u.user_email as login_email, u.user_login as username, s.label as school_name FROM ' . WLSM_STUDENT_RECORDS . ' as sr 
					JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
					JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
					JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
					JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
					JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id 
					LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id 
					WHERE cs.school_id = %d AND ss.ID = %d AND sr.ID = %d', $school_id, $data['session_id'], $data['student_id'] )
			);

			if ( ! $student ) {
				return false;
			}

			$email_to = $student->email ? $student->email : $student->login_email;
			$sms_to   = $student->phone ? $student->phone : '';

			if ( ! ( $email_to || $sms_to ) ) {
				return false;
			}

			$for = 'student_admission';

			$name = stripcslashes( $student->student_name );

			$placeholders = array(
				'[STUDENT_NAME]'      => $name,
				'[CLASS]'             => stripcslashes( $student->class_label ),
				'[SECTION]'           => stripcslashes( $student->section_label ),
				'[ROLL_NUMBER]'       => $student->roll_number,
				'[ENROLLMENT_NUMBER]' => $student->enrollment_number,
				'[ADMISSION_NUMBER]'  => $student->admission_number,
				'[LOGIN_USERNAME]'    => $student->username,
				'[LOGIN_EMAIL]'       => $student->login_email,
				'[LOGIN_PASSWORD]'    => $data['password'],
				'[SCHOOL_NAME]'       => stripcslashes( $student->school_name ),
			);

			if ( $email_student_admission_enable && $email_to ) {
				// Student Admission Email template.
				$subject = $settings_email_student_admission['subject'];
				$body    = $settings_email_student_admission['body'];

				WLSM_Email::send_email( $data['school_id'], $email_to, $subject, $body, $name, $for, $placeholders );
			}

			if ( $sms_student_admission_enable && $sms_to ) {
				// Student Admission SMS template.
				$message = $settings_sms_student_admission['message'];
				$template_id = $settings_sms_student_admission['template_id'];

				WLSM_SMS::send_sms( $data['school_id'], $sms_to, $message, $template_id, $for, $placeholders );
			}
		}
	}

	public static function notify_for_invoice_generated( $data ) {
		$school_id = $data['school_id'];
		$session_id = $data['session_id'];
		$invoice_id = $data['invoice_id'];

		$settings_email_invoice_generated = WLSM_M_Setting::get_settings_email_invoice_generated( $school_id );
		$email_invoice_generated_enable   = $settings_email_invoice_generated['enable'];

		$settings_sms_invoice_generated = WLSM_M_Setting::get_settings_sms_invoice_generated( $school_id );
		$sms_invoice_generated_enable   = $settings_sms_invoice_generated['enable'];

		if ( $email_invoice_generated_enable || $sms_invoice_generated_enable ) {
			global $wpdb;
			$invoice = $wpdb->get_row(
				$wpdb->prepare( 'SELECT i.ID, i.label as invoice_title, i.invoice_number, i.date_issued, i.due_date, (i.amount ) as payable, sr.name as student_name, sr.phone, sr.email, sr.admission_number, sr.enrollment_number, sr.roll_number, c.label as class_label, se.label as section_label, u.user_email as login_email, s.label as school_name FROM ' . WLSM_INVOICES . ' as i 
					JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id 
					JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
					JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
					JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
					JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
					JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id 
					LEFT OUTER JOIN ' . WLSM_PAYMENTS . ' as p ON p.invoice_id = i.ID 
					LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id 
					WHERE cs.school_id = %d AND ss.ID = %d AND i.ID = %d', $school_id, $data['session_id'], $data['invoice_id'] )
			);

			if ( ! $invoice ) {
				return false;
			}

			$email_to = $invoice->email ? $invoice->email : $invoice->login_email;
			$sms_to   = $invoice->phone ? $invoice->phone : '';

			if ( ! ( $email_to || $sms_to ) ) {
				return false;
			}

			$for = 'invoice_generated';

			$name = stripcslashes( $invoice->student_name );

			$placeholders = array(
				'[INVOICE_TITLE]'       => $invoice->invoice_title,
				'[INVOICE_NUMBER]'      => $invoice->invoice_number,
				'[INVOICE_PAYABLE]'     => WLSM_Config::sanitize_money( $invoice->payable ),
				'[INVOICE_DATE_ISSUED]' => WLSM_Config::get_date_text( $invoice->date_issued ),
				'[INVOICE_DUE_DATE]'    => WLSM_Config::get_date_text( $invoice->due_date ),
				'[STUDENT_NAME]'        => $name,
				'[CLASS]'               => stripcslashes( $invoice->class_label ),
				'[SECTION]'             => stripcslashes( $invoice->section_label ),
				'[ROLL_NUMBER]'         => $invoice->roll_number,
				'[ENROLLMENT_NUMBER]'   => $invoice->enrollment_number,
				'[ADMISSION_NUMBER]'    => $invoice->admission_number,
				'[SCHOOL_NAME]'         => stripcslashes( $invoice->school_name ),
			);

			$invoice = WLSM_M_Staff_Accountant::fetch_invoice($school_id, $session_id, $invoice_id);

			require_once WLSM_PLUGIN_DIR_PATH . 'includes/vendor/autoload.php';

			// $payments = WLSM_M_Staff_Accountant::get_invoice_payments($invoice_id);

			ob_start();
			require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/invoice_email.php';
			
			$html = ob_get_clean();
	
			// $mpdf = new \Mpdf\Mpdf();
			// $mpdf->WriteHTML($html);
			// $mpdf->Output();

			// // $mpdf->WriteHTML(utf8_encode($html));
			// $attachments = $mpdf->Output('invoice.pdf', 'S');
			$attachments= [];

			if ( $email_invoice_generated_enable && $email_to ) {
				// Invoice Generated Email template.
				$subject = $settings_email_invoice_generated['subject'];
				$body    = $settings_email_invoice_generated['body'];

				WLSM_Email::send_email( $school_id, $email_to, $subject, $body, $name, $for, $placeholders, $attachments );
				
				$result = print_r( $school_id, true );
				error_log( $result );
			}

			if ( $sms_invoice_generated_enable && $sms_to ) {
				// Invoice Generated SMS template.
				$message = $settings_sms_invoice_generated['message'];
				$template_id = $settings_sms_invoice_generated['template_id'];

				WLSM_SMS::send_sms( $data['school_id'], $sms_to, $message, $template_id, $for, $placeholders );
			}
		}
	}

	public static function notify_for_online_fee_submission( $data ) {
		$school_id = $data['school_id'];

		$settings_email_online_fee_submission = WLSM_M_Setting::get_settings_email_online_fee_submission( $school_id );
		$email_online_fee_submission_enable   = $settings_email_online_fee_submission['enable'];

		$settings_sms_online_fee_submission = WLSM_M_Setting::get_settings_sms_online_fee_submission( $school_id );
		$sms_online_fee_submission_enable   = $settings_sms_online_fee_submission['enable'];

		if ( $email_online_fee_submission_enable || $sms_online_fee_submission_enable ) {
			global $wpdb;
			$payment = $wpdb->get_row(
				$wpdb->prepare( 'SELECT sr.name as student_name, sr.admission_number, sr.enrollment_number, sr.roll_number, sr.phone, sr.email, p.receipt_number, p.amount, p.payment_method, p.created_at, p.invoice_label, p.invoice_id, i.label as invoice_title, c.label as class_label, se.label as section_label, u.user_email as login_email, s.label as school_name FROM ' . WLSM_PAYMENTS . ' as p 
					JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = p.school_id 
					JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = p.student_record_id 
					JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
					JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
					JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
					JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
					LEFT OUTER JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id 
					LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id 
					WHERE p.school_id = %d AND ss.ID = %d AND p.ID = %d', $school_id, $data['session_id'], $data['payment_id'] )
			);

			if ( ! $payment ) {
				return false;
			}

			$email_to = $payment->email ? $payment->email : $payment->login_email;
			$sms_to   = $payment->phone ? $payment->phone : '';

			if ( ! ( $email_to || $sms_to ) ) {
				return false;
			}

			$for = 'online_fee_submission';

			$name = stripcslashes( $payment->student_name );

			$placeholders = array(
				'[INVOICE_TITLE]'     => $payment->invoice_title ? $payment->invoice_title : $payment->invoice_label,
				'[RECEIPT_NUMBER]'    => $payment->receipt_number,
				'[AMOUNT]'            => WLSM_Config::sanitize_money( $payment->amount ),
				'[PAYMENT_METHOD]'    => WLSM_M_Invoice::get_payment_method_text( $payment->payment_method ),
				'[DATE]'              => WLSM_Config::get_date_text( $payment->created_at ),
				'[STUDENT_NAME]'      => $name,
				'[CLASS]'             => stripcslashes( $payment->class_label ),
				'[SECTION]'           => stripcslashes( $payment->section_label ),
				'[ROLL_NUMBER]'       => $payment->roll_number,
				'[ENROLLMENT_NUMBER]' => $payment->enrollment_number,
				'[ADMISSION_NUMBER]'  => $payment->admission_number,
				'[SCHOOL_NAME]'       => stripcslashes( $payment->school_name ),
			);

			if ( $email_online_fee_submission_enable && $email_to ) {
				// Online Fee Submission Email template.
				$subject = $settings_email_online_fee_submission['subject'];
				$body    = $settings_email_online_fee_submission['body'];

				WLSM_Email::send_email( $data['school_id'], $email_to, $subject, $body, $name, $for, $placeholders );
			}

			if ( $sms_online_fee_submission_enable && $sms_to ) {
				// Online Fee Submission SMS template.
				$message = $settings_sms_online_fee_submission['message'];
				$template_id = $settings_sms_online_fee_submission['template_id'];

				WLSM_SMS::send_sms( $data['school_id'], $sms_to, $message,$template_id, $for, $placeholders );
			}
		}
	}

	public static function notify_for_offline_fee_submission( $data ) {
		$school_id = $data['school_id'];

		$settings_email_offline_fee_submission = WLSM_M_Setting::get_settings_email_offline_fee_submission( $school_id );
		$email_offline_fee_submission_enable   = $settings_email_offline_fee_submission['enable'];

		$settings_sms_offline_fee_submission = WLSM_M_Setting::get_settings_sms_offline_fee_submission( $school_id );
		$sms_offline_fee_submission_enable   = $settings_sms_offline_fee_submission['enable'];

		if ( $email_offline_fee_submission_enable || $sms_offline_fee_submission_enable ) {
			global $wpdb;
			$payment = $wpdb->get_row(
				$wpdb->prepare( 'SELECT sr.name as student_name, sr.admission_number, sr.enrollment_number, sr.roll_number, sr.phone, sr.email, p.receipt_number, p.amount, p.payment_method, p.created_at, p.invoice_label, p.invoice_id, i.label as invoice_title, c.label as class_label, se.label as section_label, u.user_email as login_email, s.label as school_name FROM ' . WLSM_PAYMENTS . ' as p 
					JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = p.school_id 
					JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = p.student_record_id 
					JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
					JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
					JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
					JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
					LEFT OUTER JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id 
					LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id 
					WHERE p.school_id = %d AND ss.ID = %d AND p.ID = %d', $school_id, $data['session_id'], $data['payment_id'] )
			);

			if ( ! $payment ) {
				return false;
			}

			$email_to = $payment->email ? $payment->email : $payment->login_email;
			$sms_to   = $payment->phone ? $payment->phone : '';

			if ( ! ( $email_to || $sms_to ) ) {
				return false;
			}

			$for = 'offline_fee_submission';

			$name = stripcslashes( $payment->student_name );

			$placeholders = array(
				'[INVOICE_TITLE]'     => $payment->invoice_title ? $payment->invoice_title : $payment->invoice_label,
				'[RECEIPT_NUMBER]'    => $payment->receipt_number,
				'[AMOUNT]'            => WLSM_Config::sanitize_money( $payment->amount ),
				'[PAYMENT_METHOD]'    => WLSM_M_Invoice::get_payment_method_text( $payment->payment_method ),
				'[DATE]'              => WLSM_Config::get_date_text( $payment->created_at ),
				'[STUDENT_NAME]'      => $name,
				'[CLASS]'             => stripcslashes( $payment->class_label ),
				'[SECTION]'           => stripcslashes( $payment->section_label ),
				'[ROLL_NUMBER]'       => $payment->roll_number,
				'[ENROLLMENT_NUMBER]' => $payment->enrollment_number,
				'[ADMISSION_NUMBER]'  => $payment->admission_number,
				'[SCHOOL_NAME]'       => stripcslashes( $payment->school_name ),
			);

			if ( $email_offline_fee_submission_enable && $email_to ) {
				// Offline Fee Submission Email template.
				$subject = $settings_email_offline_fee_submission['subject'];
				$body    = $settings_email_offline_fee_submission['body'];

				WLSM_Email::send_email( $data['school_id'], $email_to, $subject, $body, $name, $for, $placeholders );
			}

			if ( $sms_offline_fee_submission_enable && $sms_to ) {
				// Offline Fee Submission SMS template.
				$message = $settings_sms_offline_fee_submission['message'];
				$template_id = $settings_sms_offline_fee_submission['template_id'];

				WLSM_SMS::send_sms( $data['school_id'], $sms_to, $message, $template_id, $for, $placeholders );
			}
		}
	}

	public static function notify_for_student_admission_to_parent( $data ) {
		$school_id = $data['school_id'];

		$settings_sms_student_admission_to_parent = WLSM_M_Setting::get_settings_sms_student_admission_to_parent( $school_id );
		$sms_student_admission_to_parent_enable   = $settings_sms_student_admission_to_parent['enable'];

		if ( $sms_student_admission_to_parent_enable ) {
			global $wpdb;
			$student = $wpdb->get_row(
				$wpdb->prepare( 'SELECT sr.name as student_name, sr.email, sr.phone, sr.father_phone, sr.mother_phone, sr.admission_number, sr.enrollment_number, c.label as class_label, se.label as section_label, sr.roll_number, u.user_email as login_email, u.user_login as username, s.label as school_name FROM ' . WLSM_STUDENT_RECORDS . ' as sr 
					JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
					JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
					JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
					JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
					JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id 
					LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id 
					WHERE cs.school_id = %d AND ss.ID = %d AND sr.ID = %d', $school_id, $data['session_id'], $data['student_id'] )
			);

			if ( ! $student ) {
				return false;
			}

			$sms_to = $student->father_phone ? $student->father_phone : ( $student->mother_phone ? $student->mother_phone : '' );

			if ( ! $sms_to ) {
				return false;
			}

			$for = 'student_admission_to_parent';

			$name = stripcslashes( $student->student_name );

			$placeholders = array(
				'[STUDENT_NAME]'      => $name,
				'[CLASS]'             => stripcslashes( $student->class_label ),
				'[SECTION]'           => stripcslashes( $student->section_label ),
				'[ROLL_NUMBER]'       => $student->roll_number,
				'[ENROLLMENT_NUMBER]' => $student->enrollment_number,
				'[ADMISSION_NUMBER]'  => $student->admission_number,
				'[LOGIN_USERNAME]'    => $student->username,
				'[LOGIN_EMAIL]'       => $student->login_email,
				'[LOGIN_PASSWORD]'    => $data['password'],
				'[SCHOOL_NAME]'       => stripcslashes( $student->school_name ),
			);

			if ( $sms_student_admission_to_parent_enable && $sms_to ) {
				// Student Admission To Parent SMS template.
				$message = $settings_sms_student_admission_to_parent['message'];
				$template_id = $settings_sms_student_admission_to_parent['template_id'];

				WLSM_SMS::send_sms( $data['school_id'], $sms_to, $message, $template_id, $for, $placeholders );
			}
		}
	}

	public static function notify_for_invoice_generated_to_parent( $data ) {
		$school_id = $data['school_id'];

		$settings_sms_invoice_generated_to_parent = WLSM_M_Setting::get_settings_sms_invoice_generated_to_parent( $school_id );
		$sms_invoice_generated_to_parent_enable   = $settings_sms_invoice_generated_to_parent['enable'];

		if ( $sms_invoice_generated_to_parent_enable ) {
			global $wpdb;
			$invoice = $wpdb->get_row(
				$wpdb->prepare( 'SELECT i.ID, i.label as invoice_title, i.invoice_number, i.date_issued, i.due_date, (i.amount ) as payable, sr.name as student_name, sr.phone, sr.father_phone, sr.mother_phone, sr.email, sr.admission_number, sr.enrollment_number, sr.roll_number, c.label as class_label, se.label as section_label, u.user_email as login_email, s.label as school_name FROM ' . WLSM_INVOICES . ' as i 
					JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id 
					JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
					JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
					JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
					JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
					JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id 
					LEFT OUTER JOIN ' . WLSM_PAYMENTS . ' as p ON p.invoice_id = i.ID 
					LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id 
					WHERE cs.school_id = %d AND ss.ID = %d AND i.ID = %d', $school_id, $data['session_id'], $data['invoice_id'] )
			);

			if ( ! $invoice ) {
				return false;
			}

			$sms_to = $invoice->father_phone ? $invoice->father_phone : ( $invoice->mother_phone ? $invoice->mother_phone : '' );

			if ( ! $sms_to ) {
				return false;
			}

			$for = 'invoice_generated_to_parent';

			$name = stripcslashes( $invoice->student_name );

			$placeholders = array(
				'[INVOICE_TITLE]'       => $invoice->invoice_title,
				'[INVOICE_NUMBER]'      => $invoice->invoice_number,
				'[INVOICE_PAYABLE]'     => WLSM_Config::sanitize_money( $invoice->payable ),
				'[INVOICE_DATE_ISSUED]' => WLSM_Config::get_date_text( $invoice->date_issued ),
				'[INVOICE_DUE_DATE]'    => WLSM_Config::get_date_text( $invoice->due_date ),
				'[STUDENT_NAME]'        => $name,
				'[CLASS]'               => stripcslashes( $invoice->class_label ),
				'[SECTION]'             => stripcslashes( $invoice->section_label ),
				'[ROLL_NUMBER]'         => $invoice->roll_number,
				'[ENROLLMENT_NUMBER]'   => $invoice->enrollment_number,
				'[ADMISSION_NUMBER]'    => $invoice->admission_number,
				'[SCHOOL_NAME]'         => stripcslashes( $invoice->school_name ),
			);

			if ( $sms_invoice_generated_to_parent_enable && $sms_to ) {
				// Invoice Generated SMS template.
				$message = $settings_sms_invoice_generated_to_parent['message'];
				$template_id = $settings_sms_invoice_generated_to_parent['template_id'];

				WLSM_SMS::send_sms( $data['school_id'], $sms_to, $message, $template_id, $for, $placeholders );
			}
		}
	}

	public static function notify_for_online_fee_submission_to_parent( $data ) {
		$school_id = $data['school_id'];

		$settings_sms_online_fee_submission_to_parent = WLSM_M_Setting::get_settings_sms_online_fee_submission_to_parent( $school_id );
		$sms_online_fee_submission_to_parent_enable   = $settings_sms_online_fee_submission_to_parent['enable'];

		if ( $sms_online_fee_submission_to_parent_enable ) {
			global $wpdb;
			$payment = $wpdb->get_row(
				$wpdb->prepare( 'SELECT sr.name as student_name, sr.admission_number, sr.enrollment_number, sr.roll_number, sr.phone, sr.father_phone, sr.mother_phone, sr.email, p.receipt_number, p.amount, p.payment_method, p.created_at, p.invoice_label, p.invoice_id, i.label as invoice_title, c.label as class_label, se.label as section_label, u.user_email as login_email, s.label as school_name FROM ' . WLSM_PAYMENTS . ' as p 
					JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = p.school_id 
					JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = p.student_record_id 
					JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
					JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
					JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
					JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
					LEFT OUTER JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id 
					LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id 
					WHERE p.school_id = %d AND ss.ID = %d AND p.ID = %d', $school_id, $data['session_id'], $data['payment_id'] )
			);

			if ( ! $payment ) {
				return false;
			}

			$sms_to = $payment->father_phone ? $payment->father_phone : ( $payment->mother_phone ? $payment->mother_phone : '' );

			if ( ! $sms_to ) {
				return false;
			}

			$for = 'online_fee_submission_to_parent';

			$name = stripcslashes( $payment->student_name );

			$placeholders = array(
				'[INVOICE_TITLE]'     => $payment->invoice_title ? $payment->invoice_title : $payment->invoice_label,
				'[RECEIPT_NUMBER]'    => $payment->receipt_number,
				'[AMOUNT]'            => WLSM_Config::sanitize_money( $payment->amount ),
				'[PAYMENT_METHOD]'    => WLSM_M_Invoice::get_payment_method_text( $payment->payment_method ),
				'[DATE]'              => WLSM_Config::get_date_text( $payment->created_at ),
				'[STUDENT_NAME]'      => $name,
				'[CLASS]'             => stripcslashes( $payment->class_label ),
				'[SECTION]'           => stripcslashes( $payment->section_label ),
				'[ROLL_NUMBER]'       => $payment->roll_number,
				'[ENROLLMENT_NUMBER]' => $payment->enrollment_number,
				'[ADMISSION_NUMBER]'  => $payment->admission_number,
				'[SCHOOL_NAME]'       => stripcslashes( $payment->school_name ),
			);

			if ( $sms_online_fee_submission_to_parent_enable && $sms_to ) {
				// Online Fee Submission To Parent SMS template.
				$message = $settings_sms_online_fee_submission_to_parent['message'];
				$template_id = $settings_sms_online_fee_submission_to_parent['template_id'];

				WLSM_SMS::send_sms( $data['school_id'], $sms_to, $message, $template_id, $for, $placeholders );
			}
		}
	}

	public static function notify_for_offline_fee_submission_to_parent( $data ) {
		$school_id = $data['school_id'];

		$settings_sms_offline_fee_submission_to_parent = WLSM_M_Setting::get_settings_sms_offline_fee_submission_to_parent( $school_id );
		$sms_offline_fee_submission_to_parent_enable   = $settings_sms_offline_fee_submission_to_parent['enable'];

		if ( $sms_offline_fee_submission_to_parent_enable ) {
			global $wpdb;
			$payment = $wpdb->get_row(
				$wpdb->prepare( 'SELECT sr.name as student_name, sr.admission_number, sr.enrollment_number, sr.roll_number, sr.phone, sr.father_phone, sr.mother_phone, sr.email, p.receipt_number, p.amount, p.payment_method, p.created_at, p.invoice_label, p.invoice_id, i.label as invoice_title, c.label as class_label, se.label as section_label, u.user_email as login_email, s.label as school_name FROM ' . WLSM_PAYMENTS . ' as p 
					JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = p.school_id 
					JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = p.student_record_id 
					JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
					JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
					JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
					JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
					LEFT OUTER JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id 
					LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id 
					WHERE p.school_id = %d AND ss.ID = %d AND p.ID = %d', $school_id, $data['session_id'], $data['payment_id'] )
			);

			if ( ! $payment ) {
				return false;
			}

			$sms_to = $payment->father_phone ? $payment->father_phone : ( $payment->mother_phone ? $payment->mother_phone : '' );

			if ( ! $sms_to ) {
				return false;
			}

			$for = 'offline_fee_submission_to_parent';

			$name = stripcslashes( $payment->student_name );

			$placeholders = array(
				'[INVOICE_TITLE]'     => $payment->invoice_title ? $payment->invoice_title : $payment->invoice_label,
				'[RECEIPT_NUMBER]'    => $payment->receipt_number,
				'[AMOUNT]'            => WLSM_Config::sanitize_money( $payment->amount ),
				'[PAYMENT_METHOD]'    => WLSM_M_Invoice::get_payment_method_text( $payment->payment_method ),
				'[DATE]'              => WLSM_Config::get_date_text( $payment->created_at ),
				'[STUDENT_NAME]'      => $name,
				'[CLASS]'             => stripcslashes( $payment->class_label ),
				'[SECTION]'           => stripcslashes( $payment->section_label ),
				'[ROLL_NUMBER]'       => $payment->roll_number,
				'[ENROLLMENT_NUMBER]' => $payment->enrollment_number,
				'[ADMISSION_NUMBER]'  => $payment->admission_number,
				'[SCHOOL_NAME]'       => stripcslashes( $payment->school_name ),
			);

			if ( $sms_offline_fee_submission_to_parent_enable && $sms_to ) {
				// Offline Fee Submission To Parent SMS template.
				$message = $settings_sms_offline_fee_submission_to_parent['message'];
				$template_id = $settings_sms_offline_fee_submission_to_parent['template_id'];

				WLSM_SMS::send_sms( $data['school_id'], $sms_to, $message, $template_id, $for, $placeholders );
			}
		}
	}

	public static function notify_for_absent_student( $data ) {
		$school_id       = $data['school_id'];
		$attendance_date = $data['attendance_date'];

		$settings_sms_absent_student = WLSM_M_Setting::get_settings_sms_absent_student( $school_id );
		$sms_absent_student_enable   = $settings_sms_absent_student['enable'];

		$settings_email_student_absent_to_student = WLSM_M_Setting::get_settings_email_student_absent_to_student( $school_id );
		$email_student_absent_to_student_enable   = $settings_email_student_absent_to_student['enable'];

		if (  $email_student_absent_to_student_enable || $sms_absent_student_enable) {
			global $wpdb;
			$student = $wpdb->get_row(
				$wpdb->prepare( 'SELECT sr.name as student_name, sr.father_phone, sr.mother_phone, sr.email, sr.admission_number, sr.enrollment_number, c.label as class_label, se.label as section_label, sr.roll_number, s.label as school_name FROM ' . WLSM_STUDENT_RECORDS . ' as sr 
					JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
					JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
					JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
					JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
					JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id 
					WHERE cs.school_id = %d AND ss.ID = %d AND sr.ID = %d', $school_id, $data['session_id'], $data['student_id'] )
			);

			if ( ! $student ) {
				return false;
			}

			$sms_to = $student->father_phone ? $student->father_phone : ( $student->mother_phone ? $student->mother_phone : '' );

			$email_to = $student->email ? $student->email : $student->login_email;

			$for = 'absent_student';

			$name = stripcslashes( $student->student_name );

			$placeholders = array(
				'[ATTENDANCE_DATE]'   => $attendance_date,
				'[STUDENT_NAME]'      => $name,
				'[CLASS]'             => stripcslashes( $student->class_label ),
				'[SECTION]'           => stripcslashes( $student->section_label ),
				'[ROLL_NUMBER]'       => $student->roll_number,
				'[ENROLLMENT_NUMBER]' => $student->enrollment_number,
				'[ADMISSION_NUMBER]'  => $student->admission_number,
				'[SCHOOL_NAME]'       => stripcslashes( $student->school_name ),
			);
			error_log( print_r( $sms_to, true  ) );
			if ( $sms_absent_student_enable && $sms_to ) {
				// Absent Student SMS template.
				$message = $settings_sms_absent_student['message'];
				$template_id = $settings_sms_absent_student['template_id'];
			
				WLSM_SMS::send_sms( $data['school_id'], $sms_to, $message, $template_id, $for, $placeholders );
			}

			if ( $email_student_absent_to_student_enable && $email_to ) {
				// Student Absent to Student Email template.
				$subject = $settings_email_student_absent_to_student['subject'];
				$body    = $settings_email_student_absent_to_student['body'];

				WLSM_Email::send_email( $data['school_id'], $email_to, $subject, $body, $name, $for, $placeholders );
				
			}

		}
	}

	public static function notify_for_invoice_due_date( ) {
		global $wpdb;
		$schools =    $wpdb->get_results( "SELECT ID FROM ".WLSM_SCHOOLS." ORDER BY ID DESC" );
		foreach ($schools as $school) {
			
			$school_id = $school->ID;
		

		$settings_email_invoice_due_date = WLSM_M_Setting::get_settings_email_student_invoice_due_date_student( $school_id );
		$email_invoice_due_date_enable   = $settings_email_invoice_due_date['enable'];

		$settings_sms_invoice_due_date = WLSM_M_Setting::get_settings_sms_student_invoice_due_date_student( $school_id );
		$sms_invoice_due_date_enable   = $settings_sms_invoice_due_date['enable'];

		if ( $email_invoice_due_date_enable || $sms_invoice_due_date_enable ) {
			global $wpdb;
			$invoices = $wpdb->get_results(
				$wpdb->prepare( 'SELECT i.ID, i.label as invoice_title, i.invoice_number, i.date_issued, i.due_date, (i.amount ) as payable, sr.name as student_name, sr.phone, sr.email, sr.admission_number, sr.enrollment_number, sr.roll_number, c.label as class_label, se.label as section_label, u.user_email as login_email, s.label as school_name FROM ' . WLSM_INVOICES . ' as i 
					JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id 
					JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
					JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
					JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
					JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
					JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id 
					LEFT OUTER JOIN ' . WLSM_PAYMENTS . ' as p ON p.invoice_id = i.ID 
					LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id 
					WHERE YEAR(i.due_date) = YEAR(NOW()) AND MONTH(i.due_date) = MONTH(NOW()) AND DAY(i.due_date) = DAY(NOW())')
			);

			if ( ! $invoices ) {
				return false;
			}

			foreach ($invoices as $invoice) {		

			$email_to = $invoice->email ? $invoice->email : $invoice->login_email;
			$sms_to   = $invoice->phone ? $invoice->phone : '';

			if ( ! ( $email_to || $sms_to ) ) {
				return false;
			}

			$for = 'invoice_due_date';

			$name = stripcslashes( $invoice->student_name );

			$placeholders = array(
				'[INVOICE_TITLE]'       => $invoice->invoice_title,
				'[INVOICE_NUMBER]'      => $invoice->invoice_number,
				'[INVOICE_PAYABLE]'     => WLSM_Config::sanitize_money( $invoice->payable ),
				'[INVOICE_DATE_ISSUED]' => WLSM_Config::get_date_text( $invoice->date_issued ),
				'[INVOICE_DUE_DATE]'    => WLSM_Config::get_date_text( $invoice->due_date ),
				'[STUDENT_NAME]'        => $name,
				'[CLASS]'               => stripcslashes( $invoice->class_label ),
				'[SECTION]'             => stripcslashes( $invoice->section_label ),
				'[ROLL_NUMBER]'         => $invoice->roll_number,
				'[ENROLLMENT_NUMBER]'   => $invoice->enrollment_number,
				'[ADMISSION_NUMBER]'    => $invoice->admission_number,
				'[SCHOOL_NAME]'         => stripcslashes( $invoice->school_name ),
			);

			if ( $email_invoice_due_date_enable && $email_to ) {
				// Invoice due_date Email template.
				$subject = $settings_email_invoice_due_date['subject'];
				$body    = $settings_email_invoice_due_date['body'];

				WLSM_Email::send_email( $school_id, $email_to, $subject, $body, $name, $for, $placeholders );
			}

			if ( $sms_invoice_due_date_enable && $sms_to ) {
				// Invoice due_date SMS template.
				$message = $settings_sms_invoice_due_date['message'];
				$template_id = $settings_sms_invoice_due_date['template_id'];

				WLSM_SMS::send_sms( $school_id, $sms_to, $message, $template_id, $for, $placeholders );
			}
		}
			}
		}
	}

	public static function notify_for_custom_message( $data ) {
		$school_id  = $data['school_id'];
		$student_id = $data['student_id'];
		$email      = $data['email'];
		$sms        = $data['sms'];

		if ( $email['send'] || $sms['send'] ) {
			global $wpdb;
			$student = $wpdb->get_row(
				$wpdb->prepare( 'SELECT sr.name as student_name, sr.email, sr.phone, sr.father_phone, sr.mother_phone, sr.admission_number, sr.enrollment_number, c.label as class_label, se.label as section_label, sr.roll_number, u.user_email as login_email, u.user_login as username, s.label as school_name FROM ' . WLSM_STUDENT_RECORDS . ' as sr 
					JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
					JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
					JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
					JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
					JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id 
					LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id 
					WHERE cs.school_id = %d AND ss.ID = %d AND sr.ID = %d', $school_id, $data['session_id'], $data['student_id'] )
			);

			if ( ! $student ) {
				return false;
			}

			$email_to = $student->email ? $student->email : $student->login_email;

			if ( isset( $sms['to_parent'] ) && $sms['to_parent'] ) {
				$sms_to = $student->father_phone ? $student->father_phone : ( $student->mother_phone ? $student->mother_phone : '' );
			} else {
				$sms_to = $student->phone ? $student->phone : '';
			}

			if ( ! ( $email_to || $sms_to ) ) {
				return false;
			}

			$for = 'custom_message';

			$name = stripcslashes( $student->student_name );

			$placeholders = array(
				'[STUDENT_NAME]'      => $name,
				'[CLASS]'             => stripcslashes( $student->class_label ),
				'[SECTION]'           => stripcslashes( $student->section_label ),
				'[ROLL_NUMBER]'       => $student->roll_number,
				'[ENROLLMENT_NUMBER]' => $student->enrollment_number,
				'[ADMISSION_NUMBER]'  => $student->admission_number,
				'[LOGIN_USERNAME]'    => $student->username,
				'[LOGIN_EMAIL]'       => $student->login_email,
				'[SCHOOL_NAME]'       => stripcslashes( $student->school_name ),
			);

			if ( $email['send'] && $email_to ) {
				// Custom Message Email template.
				$subject = $email['subject'];
				$body    = $email['body'];

				WLSM_Email::send_email( $data['school_id'], $email_to, $subject, $body, $name, $for, $placeholders );
			}

			if ( $sms['send'] && $sms_to ) {
				// Custom Message SMS template.
				$message     = $sms['message'];
				$template_id = $sms['template_id'];

				WLSM_SMS::send_sms( $data['school_id'], $sms_to, $message, $template_id, $for, $placeholders );
			}
		}
	}

	public static function notify_for_homework_message( $data ) {
		$school_id  = $data['school_id'];
		$student_id = $data['student_id'];

		$settings_sms_student_homework = WLSM_M_Setting::get_settings_sms_student_homework( $school_id );
		$sms_student_homework_enable   = $settings_sms_student_homework['enable'];
		$message                       = $settings_sms_student_homework['message'];
		$template_id                   = $settings_sms_student_homework['template_id'];

		$options        = $data['sms'];

		global $wpdb;
		$student = $wpdb->get_row(
			$wpdb->prepare(
				'SELECT sr.phone, sr.father_phone, sr.mother_phone, sr.name as student_name, sr.email, sr.phone, sr.admission_number, sr.enrollment_number, c.label as class_label, se.label as section_label, sr.roll_number, s.label as school_name FROM ' . WLSM_STUDENT_RECORDS . ' as sr 
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
				JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id 
				WHERE cs.school_id = %d AND sr.ID = %d',
				$school_id,
				$student_id
			)
		);

		if ( ! $student ) {
			return false;
		}

		$for = 'student_registration_to_student';

		$placeholders = array(
			'[STUDENT_NAME]'      => $student->student_name,
			'[CLASS]'             => stripcslashes( $student->class_label ),
			'[SECTION]'           => stripcslashes( $student->section_label ),
			'[ROLL_NUMBER]'       => $student->roll_number,
			'[ENROLLMENT_NUMBER]' => $student->enrollment_number,
			'[ADMISSION_NUMBER]'  => $student->admission_number,
			'[SCHOOL_NAME]'       => stripcslashes( $student->school_name ),
		);

		if ( $sms_student_homework_enable ) {
			$sms_to_parent = $student->father_phone ? $student->father_phone : ( $student->mother_phone ? $student->mother_phone : '' );
			$sms_to = $student->phone ? $student->phone :  '' ;
			
			if (( $options['to_student'] )) {
				if ( $sms_to ) {
					WLSM_SMS::send_sms( $school_id, $sms_to, $message, $template_id, $for );
				}
			}
			

			if (isset( $options['to_parent'] )) {
				if ( $sms_to_parent ) {
					WLSM_SMS::send_sms( $school_id, $sms_to_parent, $message, $template_id, $for );
				}
			}
			
		}
	}

	public static function notify_for_inquiry_received_to_inquisitor( $data ) {
		$school_id = $data['school_id'];

		$settings_email_inquiry_received_to_inquisitor = WLSM_M_Setting::get_settings_email_inquiry_received_to_inquisitor( $school_id );
		$email_inquiry_received_to_inquisitor_enable   = $settings_email_inquiry_received_to_inquisitor['enable'];

		$settings_sms_inquiry_received_to_inquisitor = WLSM_M_Setting::get_settings_sms_inquiry_received_to_inquisitor( $school_id );
		$sms_inquiry_received_to_inquisitor_enable   = $settings_sms_inquiry_received_to_inquisitor['enable'];

		if ( $email_inquiry_received_to_inquisitor_enable || $sms_inquiry_received_to_inquisitor_enable ) {
			global $wpdb;
			$inquiry = $wpdb->get_row(
				$wpdb->prepare( 'SELECT iq.ID, iq.name, iq.phone, iq.email, c.label as class_label FROM ' . WLSM_INQUIRIES . ' as iq 
					JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = iq.school_id 
					LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = iq.class_school_id 
					LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
					WHERE iq.school_id = %d AND iq.ID = %d', $school_id, $data['inquiry_id'] )
			);

			if ( ! $inquiry ) {
				return false;
			}

			$email_to = $inquiry->email ? $inquiry->email : '';
			$sms_to   = $inquiry->phone ? $inquiry->phone : '';

			if ( ! ( $email_to || $sms_to ) ) {
				return false;
			}

			$for = 'inquiry_received_to_inquisitor';

			$name = stripcslashes( $inquiry->name );

			$placeholders = array(
				'[NAME]'  => $name,
				'[PHONE]' => $inquiry->phone,
				'[EMAIL]' => $inquiry->email,
				'[CLASS]' => stripcslashes( $inquiry->class_label ),
			);

			if ( $email_inquiry_received_to_inquisitor_enable && $email_to ) {
				// Inquiry Received to Inquisitor Email template.
				$subject = $settings_email_inquiry_received_to_inquisitor['subject'];
				$body    = $settings_email_inquiry_received_to_inquisitor['body'];

				WLSM_Email::send_email( $data['school_id'], $email_to, $subject, $body, $name, $for, $placeholders );
			}

			if ( $sms_inquiry_received_to_inquisitor_enable && $sms_to ) {
				// Inquiry Received to Inquisitor SMS template.
				$message = $settings_sms_inquiry_received_to_inquisitor['message'];
				$template_id = $settings_sms_inquiry_received_to_inquisitor['template_id'];

				WLSM_SMS::send_sms( $data['school_id'], $sms_to, $message, $template_id, $for, $placeholders );
			}
		}
	}

	public static function notify_for_inquiry_received_to_admin( $data ) {
		$school_id = $data['school_id'];

		$settings_email_inquiry_received_to_admin = WLSM_M_Setting::get_settings_email_inquiry_received_to_admin( $school_id );
		$email_inquiry_received_to_admin_enable   = $settings_email_inquiry_received_to_admin['enable'];

		$settings_sms_inquiry_received_to_admin = WLSM_M_Setting::get_settings_sms_inquiry_received_to_admin( $school_id );
		$sms_inquiry_received_to_admin_enable   = $settings_sms_inquiry_received_to_admin['enable'];

		if ( $email_inquiry_received_to_admin_enable || $sms_inquiry_received_to_admin_enable ) {
			global $wpdb;
			$inquiry = $wpdb->get_row(
				$wpdb->prepare( 'SELECT iq.ID, iq.name, iq.phone, iq.email, c.label as class_label FROM ' . WLSM_INQUIRIES . ' as iq 
					JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = iq.school_id 
					LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = iq.class_school_id 
					LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
					WHERE iq.school_id = %d AND iq.ID = %d', $school_id, $data['inquiry_id'] )
			);

			if ( ! $inquiry ) {
				return false;
			}

			$settings_inquiry = WLSM_M_Setting::get_settings_inquiry( $school_id );
			$email_to         = $settings_inquiry['admin_email'];
			$sms_to           = $settings_inquiry['admin_phone'];

			if ( ! ( $email_to || $sms_to ) ) {
				return false;
			}

			$for = 'inquiry_received_to_admin';

			$name = stripcslashes( $inquiry->name );

			$placeholders = array(
				'[NAME]'  => $name,
				'[PHONE]' => $inquiry->phone,
				'[EMAIL]' => $inquiry->email,
				'[CLASS]' => stripcslashes( $inquiry->class_label ),
			);

			if ( $email_inquiry_received_to_admin_enable && $email_to ) {
				// Inquiry Received to Admin Email template.
				$subject = $settings_email_inquiry_received_to_admin['subject'];
				$body    = $settings_email_inquiry_received_to_admin['body'];

				WLSM_Email::send_email( $data['school_id'], $email_to, $subject, $body, '', $for, $placeholders );
			}

			if ( $sms_inquiry_received_to_admin_enable && $sms_to ) {
				// Inquiry Received to Admin SMS template.
				$message = $settings_sms_inquiry_received_to_admin['message'];
				$template_id = $settings_sms_inquiry_received_to_admin['template_id'];

				WLSM_SMS::send_sms( $data['school_id'], $sms_to, $message, $template_id, $for, $placeholders );
			}
		}
	}

	public static function notify_for_student_registration_to_student( $data ) {
		$school_id = $data['school_id'];

		$settings_email_student_registration_to_student = WLSM_M_Setting::get_settings_email_student_registration_to_student( $school_id );
		$email_student_registration_to_student_enable   = $settings_email_student_registration_to_student['enable'];

		$settings_sms_student_registration_to_student = WLSM_M_Setting::get_settings_sms_student_registration_to_student( $school_id );
		$sms_student_registration_to_student_enable   = $settings_sms_student_registration_to_student['enable'];

		if ( $email_student_registration_to_student_enable || $sms_student_registration_to_student_enable ) {
			global $wpdb;
			$student = $wpdb->get_row(
				$wpdb->prepare( 'SELECT sr.name as student_name, sr.email, sr.phone, sr.admission_number, sr.enrollment_number, c.label as class_label, se.label as section_label, sr.roll_number, u.user_email as login_email, u.user_login as username, s.label as school_name FROM ' . WLSM_STUDENT_RECORDS . ' as sr 
					JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
					JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
					JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
					JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
					JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id 
					LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id 
					WHERE cs.school_id = %d AND ss.ID = %d AND sr.ID = %d', $school_id, $data['session_id'], $data['student_id'] )
			);

			if ( ! $student ) {
				return false;
			}

			$email_to = $student->email ? $student->email : $student->login_email;
			$sms_to   = $student->phone ? $student->phone : '';

			if ( ! ( $email_to || $sms_to ) ) {
				return false;
			}

			$for = 'student_registration_to_student';

			$name = stripcslashes( $student->student_name );

			$placeholders = array(
				'[STUDENT_NAME]'      => $name,
				'[CLASS]'             => stripcslashes( $student->class_label ),
				'[SECTION]'           => stripcslashes( $student->section_label ),
				'[ROLL_NUMBER]'       => $student->roll_number,
				'[ENROLLMENT_NUMBER]' => $student->enrollment_number,
				'[ADMISSION_NUMBER]'  => $student->admission_number,
				'[LOGIN_USERNAME]'    => $student->username,
				'[LOGIN_EMAIL]'       => $student->login_email,
				'[LOGIN_PASSWORD]'    => $data['password'],
				'[SCHOOL_NAME]'       => stripcslashes( $student->school_name ),
			);

			if ( $email_student_registration_to_student_enable && $email_to ) {
				// Student Registration to Student Email template.
				$subject = $settings_email_student_registration_to_student['subject'];
				$body    = $settings_email_student_registration_to_student['body'];

				WLSM_Email::send_email( $data['school_id'], $email_to, $subject, $body, $name, $for, $placeholders );
			}

			if ( $sms_student_registration_to_student_enable && $sms_to ) {
				// Student Registration to Student SMS template.
				$message = $settings_sms_student_registration_to_student['message'];
				$template_id = $settings_sms_student_registration_to_student['template_id'];

				WLSM_SMS::send_sms( $data['school_id'], $sms_to, $message, $template_id, $for, $placeholders );
			}
		}
	}

	public static function notify_for_student_registration_to_admin( $data ) {
		$school_id = $data['school_id'];

		$settings_email_student_registration_to_admin = WLSM_M_Setting::get_settings_email_student_registration_to_admin( $school_id );
		$email_student_registration_to_admin_enable   = $settings_email_student_registration_to_admin['enable'];

		$settings_sms_student_registration_to_admin = WLSM_M_Setting::get_settings_sms_student_registration_to_admin( $school_id );
		$sms_student_registration_to_admin_enable   = $settings_sms_student_registration_to_admin['enable'];

		if ( $email_student_registration_to_admin_enable || $sms_student_registration_to_admin_enable ) {
			global $wpdb;
			$student = $wpdb->get_row(
				$wpdb->prepare( 'SELECT sr.name as student_name, sr.email, sr.phone, sr.admission_number, sr.enrollment_number, c.label as class_label, se.label as section_label, sr.roll_number, u.user_email as login_email, u.user_login as username, s.label as school_name FROM ' . WLSM_STUDENT_RECORDS . ' as sr 
					JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
					JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
					JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
					JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
					JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id 
					LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id 
					WHERE cs.school_id = %d AND ss.ID = %d AND sr.ID = %d', $school_id, $data['session_id'], $data['student_id'] )
			);

			if ( ! $student ) {
				return false;
			}

			$settings_registration = WLSM_M_Setting::get_settings_registration( $school_id );
			$email_to              = $settings_registration['admin_email'];
			$sms_to                = $settings_registration['admin_phone'];

			if ( ! ( $email_to || $sms_to ) ) {
				return false;
			}

			$for = 'student_registration_to_admin';

			$name = stripcslashes( $student->student_name );

			$placeholders = array(
				'[STUDENT_NAME]'      => $name,
				'[CLASS]'             => stripcslashes( $student->class_label ),
				'[SECTION]'           => stripcslashes( $student->section_label ),
				'[ROLL_NUMBER]'       => $student->roll_number,
				'[ENROLLMENT_NUMBER]' => $student->enrollment_number,
				'[ADMISSION_NUMBER]'  => $student->admission_number,
				'[LOGIN_USERNAME]'    => $student->username,
				'[LOGIN_EMAIL]'       => $student->login_email,
				'[LOGIN_PASSWORD]'    => $data['password'],
				'[SCHOOL_NAME]'       => stripcslashes( $student->school_name ),
			);

			if ( $email_student_registration_to_admin_enable && $email_to ) {
				// Student Registration to Admin Email template.
				$subject = $settings_email_student_registration_to_admin['subject'];
				$body    = $settings_email_student_registration_to_admin['body'];

				WLSM_Email::send_email( $data['school_id'], $email_to, $subject, $body, $name, $for, $placeholders );
			}

			if ( $sms_student_registration_to_admin_enable && $sms_to ) {
				// Student Registration to Admin SMS template.
				$message = $settings_sms_student_registration_to_admin['message'];
				$template_id = $settings_sms_student_registration_to_admin['template_id'];

				WLSM_SMS::send_sms( $data['school_id'], $sms_to, $message, $template_id, $for, $placeholders );
			}
		}
	}
}
