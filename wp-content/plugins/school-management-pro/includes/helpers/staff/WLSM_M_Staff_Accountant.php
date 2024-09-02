<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Config.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Invoice.php';

class WLSM_M_Staff_Accountant {
	public static function get_invoices_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_INVOICES );
	}

	public static function 	fetch_invoices_query( $school_id, $session_id, $filter ) {
		require WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/partials/fetch_invoices_query.php';

		$query = 'SELECT i.ID, i.label as invoice_title, i.invoice_number, i.date_issued, i.due_date, i.amount, (i.amount) as payable, COALESCE(SUM(p.amount), 0) as paid, ((i.amount) - COALESCE(SUM(p.amount), 0)) as due, i.status, sr.name as student_name, sr.father_name,  sr.phone, sr.admission_number, sr.enrollment_number, c.label as class_label, se.label as section_label FROM ' . WLSM_INVOICES . ' as i
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_PAYMENTS . ' as p ON p.invoice_id = i.ID
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id ) . $where;
		return $query;
	}

	public static function 	fetch_invoices_report( $school_id, $session_id, $filter ) {
		require WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/partials/fetch_invoices_query.php';

		$query = 'SELECT COALESCE(SUM(i.amount)) as payable, COALESCE(SUM(p.amount), 0) as paid, ((i.amount) - COALESCE(SUM(p.amount), 0)) as due , sr.name as student_name, sr.father_name,  sr.phone, sr.admission_number, sr.enrollment_number, c.label as class_label, se.label as section_label FROM ' . WLSM_STUDENT_RECORDS . ' as sr
		JOIN ' . WLSM_INVOICES . ' as i ON sr.ID = i.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN (SELECT invoice_id, SUM(amount) as amount FROM ' . WLSM_PAYMENTS . ' GROUP BY invoice_id) as p ON p.invoice_id = i.ID
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id ) . $where;
		return $query;
	}

	public static function 	get_invoices_report_total( $school_id, $session_id, $class_id, $section_id ) {
		global $wpdb;

		if ($section_id === 0) {
			$query = $wpdb->get_row( $wpdb->prepare('SELECT COALESCE(SUM(p.amount), 0) as paid, SUM(i.amount - COALESCE(p.amount, 0)) as due FROM ' . WLSM_INVOICES . ' as i
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			LEFT OUTER JOIN ' . WLSM_PAYMENTS . ' as p ON p.invoice_id = i.ID
			WHERE cs.school_id = %d AND ss.ID = %d AND c.ID = %d', $school_id , $session_id , $class_id  ) );
		} else {
			$query = $wpdb->get_row( $wpdb->prepare('SELECT COALESCE(SUM(p.amount), 0) as paid, SUM(i.amount - COALESCE(p.amount, 0)) as due FROM ' . WLSM_INVOICES . ' as i
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			LEFT OUTER JOIN ' . WLSM_PAYMENTS . ' as p ON p.invoice_id = i.ID
			WHERE cs.school_id = %d AND ss.ID = %d AND c.ID = %d AND se.ID = %d', $school_id , $session_id , $class_id , $section_id ) );
		}
		return $query;
	}

	public static function fetch_invoices_report_query_group_by() {
		$group_by = 'GROUP BY sr.ID';
		return $group_by;
	}

	public static function fetch_invoices( $school_id, $session_id ) {
		require WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/partials/fetch_invoices_query.php';

		$query = 'SELECT i.ID, i.label as invoice_title, i.invoice_number, i.date_issued, i.due_date, i.amount, (i.amount ) as payable, COALESCE(SUM(p.amount), 0) as paid, ((i.amount ) - COALESCE(SUM(p.amount), 0)) as due, i.status, sr.name as student_name, sr.phone, sr.admission_number, sr.enrollment_number, c.label as class_label, se.label as section_label FROM ' . WLSM_INVOICES . ' as i
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_PAYMENTS . ' as p ON p.invoice_id = i.ID
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id );
		return $query;
	}

	public static function fetch_invoices_query_group_by() {
		$group_by = 'GROUP BY i.ID';
		return $group_by;
	}

	public static function fetch_invoices_query_count( $school_id, $session_id, $filter ) {
		require WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/partials/fetch_invoices_query.php';

		$query = 'SELECT COUNT(DISTINCT i.ID) FROM ' . WLSM_INVOICES . ' as i
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id ) . $where;
		return $query;
	}

	public static function get_invoice( $school_id, $session_id, $id ) {
		global $wpdb;
		$invoice = $wpdb->get_row( $wpdb->prepare( 'SELECT i.ID, i.status, p.ID as payment_id FROM ' . WLSM_INVOICES . ' as i
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		LEFT OUTER JOIN ' . WLSM_PAYMENTS . ' as p ON p.invoice_id = i.ID
		WHERE cs.school_id = %d AND ss.ID = %d AND i.ID = %d', $school_id, $session_id, $id ) );
		return $invoice;
	}

	public static function fetch_invoice( $school_id, $session_id, $id ) {
		global $wpdb;
		$invoice = $wpdb->get_row( $wpdb->prepare( 'SELECT i.ID, i.label as invoice_title, i.invoice_number, i.fee_list, i.description as invoice_description, i.date_issued, i.due_date, i.amount, i.invoice_amount_total, i.discount, i.due_date_amount, (i.amount) as payable, COALESCE(SUM(p.amount), 0) as paid, i.partial_payment, i.status, sr.ID as student_id, sr.name as student_name, sr.phone, sr.email, sr.admission_number, sr.enrollment_number, sr.roll_number, sr.father_name, sr.father_phone, c.label as class_label, se.label as section_label FROM ' . WLSM_INVOICES . ' as i
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_PAYMENTS . ' as p ON p.invoice_id = i.ID
		WHERE cs.school_id = %d AND ss.ID = %d AND i.ID = %d', $school_id, $session_id, $id ) );
		return $invoice;
	}

	public static function fetch_bulk_invoices( $school_id, $session_id, $class_id ) {
		global $wpdb;
		$invoice =  ( $wpdb->prepare( 'SELECT i.ID, i.label as invoice_title, i.invoice_number, i.fee_list, i.description as invoice_description, i.date_issued, i.due_date, i.amount, i.invoice_amount_total, i.discount, i.due_date_amount, (i.amount) as payable, p.amount as paid, i.partial_payment, i.status, sr.ID as student_id, sr.name as student_name, sr.phone, sr.email, sr.admission_number, sr.enrollment_number, sr.roll_number, sr.father_name, sr.father_phone, c.label as class_label, se.label as section_label FROM ' . WLSM_INVOICES . ' as i
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_PAYMENTS . ' as p ON p.invoice_id = i.ID
		WHERE cs.school_id = %d AND ss.ID = %d AND cs.class_id = %d', $school_id, $session_id, $class_id ) );
		return $invoice;
	}

	public static function get_invoice_payments( $invoice_id ) {
		global $wpdb;
		$payments = $wpdb->get_results( $wpdb->prepare( 'SELECT p.ID, p.receipt_number, p.amount, p.payment_method, p.transaction_id, p.created_at, p.note FROM ' . WLSM_PAYMENTS . ' as p
		WHERE p.invoice_id = %d ORDER BY p.ID DESC', $invoice_id ) );
		return $payments;
	}

	public static function fetch_invoice_payments_query( $school_id, $session_id, $invoice_id ) {
		$query = 'SELECT p.ID, p.receipt_number, p.amount, p.payment_method, p.transaction_id, p.created_at, p.note FROM ' . WLSM_PAYMENTS . ' as p
		JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id ) . ' AND p.invoice_id = ' . absint( $invoice_id );
		return $query;
	}

	public static function fetch_payments_query_group_by() {
		$group_by = 'GROUP BY p.ID';
		return $group_by;
	}

	public static function fetch_invoice_payments_query_count( $school_id, $session_id, $invoice_id ) {
		$query = 'SELECT COUNT(DISTINCT p.ID) FROM ' . WLSM_PAYMENTS . ' as p
		JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id ) . ' AND p.invoice_id = ' . absint( $invoice_id );
		return $query;
	}

	public static function get_invoice_payments_total( $invoice_id ) {
		global $wpdb;
		$total = $wpdb->get_var(
			$wpdb->prepare( 'SELECT COALESCE(SUM(p.amount), 0) as paid FROM ' . WLSM_PAYMENTS . ' as p
				JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id
				WHERE i.ID = %d GROUP BY i.ID', $invoice_id )
		);
		return $total;
	}

	public static function get_invoice_payment( $invoice_id, $id ) {
		global $wpdb;
		$payment = $wpdb->get_row( $wpdb->prepare( 'SELECT p.ID FROM ' . WLSM_PAYMENTS . ' as p
		WHERE p.invoice_id = %d AND p.ID = %d', $invoice_id, $id ) );
		return $payment;
	}

	public static function get_invoice_by_id( $invoice_id ) {
		global $wpdb;
		$invoice = $wpdb->get_row( $wpdb->prepare( 'SELECT i.ID, i.amount, i.discount FROM ' . WLSM_INVOICES . ' as i WHERE i.ID = %d', $invoice_id ) );
		return $invoice;
	}

	public static function fetch_payments_query( $school_id, $session_id, $start_date = null, $end_date = null ) {
		$query = 'SELECT sr.name as student_name, sr.admission_number, sr.enrollment_number, sr.phone, sr.father_name, sr.father_phone, p.ID, p.receipt_number, p.amount, p.payment_method, p.transaction_id, p.created_at, p.note, p.invoice_label, p.invoice_payable, p.invoice_id, i.label as invoice_title, c.label as class_label, se.label as section_label FROM ' . WLSM_PAYMENTS . ' as p
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = p.school_id
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = p.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id
		WHERE p.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id );

		if (!empty($start_date)) {
		$query = ('SELECT sr.name as student_name, sr.admission_number, sr.enrollment_number, sr.phone, sr.father_name, sr.father_phone, p.ID, p.receipt_number, p.amount, p.payment_method, p.transaction_id, p.created_at, p.note, p.invoice_label, p.invoice_payable, p.invoice_id, i.label as invoice_title, c.label as class_label, se.label as section_label FROM ' . WLSM_PAYMENTS . ' as p
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = p.school_id
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = p.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id
		WHERE p.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id ). ' AND p.created_at BETWEEN "'.  $start_date.'"
		AND "'.($end_date).'"');
		}
		return $query;
	}

	public static function fetch_payments_query_count( $school_id, $session_id ) {
		$query = 'SELECT COUNT(DISTINCT p.ID) FROM ' . WLSM_PAYMENTS . ' as p
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = p.school_id
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = p.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id
		WHERE p.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id );
		return $query;
	}

	public static function fetch_payment( $school_id, $session_id, $id ) {
		global $wpdb;
		$payment = $wpdb->get_row( $wpdb->prepare( 'SELECT sr.name as student_name, sr.admission_number, sr.enrollment_number, sr.roll_number, sr.phone, sr.email, sr.father_name, sr.father_phone, p.ID, p.receipt_number, p.amount, p.payment_method, p.transaction_id, p.created_at, p.note, p.invoice_label, p.invoice_payable, p.invoice_id, i.label as invoice_title, c.label as class_label, se.label as section_label, ((i.amount ) - COALESCE(SUM(p.amount), 0)) as due, p.added_by FROM ' . WLSM_PAYMENTS . ' as p
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = p.school_id
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = p.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id
		WHERE p.school_id = %d AND ss.ID = %d AND p.ID = %d', $school_id, $session_id, $id ) );
		return $payment;
	}

	public static function get_payment( $school_id, $session_id, $id ) {
		global $wpdb;
		$payment = $wpdb->get_row( $wpdb->prepare( 'SELECT p.ID, p.invoice_id FROM ' . WLSM_PAYMENTS . ' as p
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = p.school_id
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = p.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		LEFT OUTER JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id
		WHERE p.school_id = %d AND ss.ID = %d AND p.ID = %d', $school_id, $session_id, $id ) );
		return $payment;
	}

	public static function get_payment_note( $school_id, $session_id, $id ) {
		global $wpdb;
		$payment = $wpdb->get_row( $wpdb->prepare( 'SELECT p.ID, p.note FROM ' . WLSM_PAYMENTS . ' as p
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = p.school_id
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = p.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		LEFT OUTER JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id
		WHERE p.school_id = %d AND ss.ID = %d AND p.ID = %d', $school_id, $session_id, $id ) );
		return $payment;
	}

	public static function fetch_pending_payments_query( $school_id, $session_id ) {
		$query = 'SELECT sr.name as student_name, sr.admission_number, sr.enrollment_number, sr.phone, sr.father_name, sr.father_phone, p.ID, p.receipt_number, p.amount, p.payment_method, p.transaction_id, p.attachment, p.created_at, p.note, p.invoice_label, p.invoice_payable, p.invoice_id, i.label as invoice_title, c.label as class_label, se.label as section_label FROM ' . WLSM_PENDING_PAYMENTS . ' as p
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = p.school_id
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = p.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id
		WHERE p.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id );
		return $query;
	}

	public static function fetch_pending_payments_query_count( $school_id, $session_id ) {
		$query = 'SELECT COUNT(DISTINCT p.ID) FROM ' . WLSM_PENDING_PAYMENTS . ' as p
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = p.school_id
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = p.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id
		WHERE p.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id );
		return $query;
	}

	public static function fetch_pending_payment( $school_id, $session_id, $id ) {
		global $wpdb;
		$payment = $wpdb->get_row( $wpdb->prepare( 'SELECT sr.ID as student_record_id, p.ID, p.receipt_number, p.amount, p.payment_method, p.transaction_id, p.attachment, p.created_at, p.note, p.invoice_label, p.invoice_payable, p.invoice_id FROM ' . WLSM_PENDING_PAYMENTS . ' as p
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = p.school_id
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = p.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id
		WHERE p.school_id = %d AND ss.ID = %d AND p.ID = %d', $school_id, $session_id, $id ) );
		return $payment;
	}

	public static function get_pending_payment( $school_id, $session_id, $id ) {
		global $wpdb;
		$payment = $wpdb->get_row( $wpdb->prepare( 'SELECT p.ID, p.invoice_id FROM ' . WLSM_PENDING_PAYMENTS . ' as p
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = p.school_id
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = p.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		LEFT OUTER JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id
		WHERE p.school_id = %d AND ss.ID = %d AND p.ID = %d', $school_id, $session_id, $id ) );
		return $payment;
	}

	public static function calculate_payable_amount( $invoice ) {
		return $invoice->amount - $invoice->discount;
	}

	public static function refresh_invoice_status( $invoice_id ) {
		global $wpdb;

		ob_start();

		$invoice = self::get_invoice_by_id( $invoice_id );

		$paid    = self::get_invoice_payments_total( $invoice_id );

		$payable = self::calculate_payable_amount( $invoice );

		$invoice_status = WLSM_M_Invoice::get_status_key( $payable, $paid );

		$data = array(
			'status'     => $invoice_status,
			'updated_at' => current_time( 'Y-m-d H:i:s' ),
		);

		$success = $wpdb->update( WLSM_INVOICES, $data, array( 'ID' => $invoice_id ) );

		$buffer = ob_get_clean();
		if ( ! empty( $buffer ) ) {
			throw new Exception( $buffer );
		}

		if ( false === $success ) {
			throw new Exception( $wpdb->last_error );
		}

		return $invoice_status;
	}

	public static function get_expenses_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_EXPENSES );
	}

	public static function get_income_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_INCOME );
	}

	public static function fetch_expense_category_query( $school_id ) {
		$query = 'SELECT ec.ID, ec.label FROM ' . WLSM_EXPENSE_CATEGORIES . ' as ec
		WHERE ec.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function fetch_expense_category_query_group_by() {
		$group_by = 'GROUP BY ec.ID';
		return $group_by;
	}

	public static function fetch_expense_category_query_count( $school_id ) {
		$query = 'SELECT COUNT(ec.ID) FROM ' . WLSM_EXPENSE_CATEGORIES . ' as ec
		WHERE ec.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function get_expense_category( $school_id, $id ) {
		global $wpdb;
		$expense_category = $wpdb->get_row( $wpdb->prepare( 'SELECT ec.ID FROM ' . WLSM_EXPENSE_CATEGORIES . ' as ec
		WHERE ec.school_id = %d AND ec.ID = %d', $school_id, $id ) );
		return $expense_category;
	}

	public static function fetch_expense_category( $school_id, $id ) {
		global $wpdb;
		$expense_category = $wpdb->get_row( $wpdb->prepare( 'SELECT ec.ID, ec.label FROM ' . WLSM_EXPENSE_CATEGORIES . ' as ec
		WHERE ec.school_id = %d AND ec.ID = %d', $school_id, $id ) );
		return $expense_category;
	}

	public static function fetch_income_category_query( $school_id ) {
		$query = 'SELECT ic.ID, ic.label FROM ' . WLSM_INCOME_CATEGORIES . ' as ic
		WHERE ic.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function fetch_income_category_query_group_by() {
		$group_by = 'GROUP BY ic.ID';
		return $group_by;
	}

	public static function fetch_income_category_query_count( $school_id ) {
		$query = 'SELECT COUNT(ic.ID) FROM ' . WLSM_INCOME_CATEGORIES . ' as ic
		WHERE ic.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function get_income_category( $school_id, $id ) {
		global $wpdb;
		$income_category = $wpdb->get_row( $wpdb->prepare( 'SELECT ic.ID FROM ' . WLSM_INCOME_CATEGORIES . ' as ic
		WHERE ic.school_id = %d AND ic.ID = %d', $school_id, $id ) );
		return $income_category;
	}

	public static function fetch_income_category( $school_id, $id ) {
		global $wpdb;
		$income_category = $wpdb->get_row( $wpdb->prepare( 'SELECT ic.ID, ic.label FROM ' . WLSM_INCOME_CATEGORIES . ' as ic
		WHERE ic.school_id = %d AND ic.ID = %d', $school_id, $id ) );
		return $income_category;
	}

	public static function fetch_expense_query( $school_id, $start_date, $end_date, $session_start_date = null, $session_end_date = null ) {
		$query = 'SELECT ep.ID, ep.label, ep.invoice_number, ep.amount, ep.expense_date, ep.note, ec.label as expense_category FROM ' . WLSM_EXPENSES . ' as ep
		LEFT OUTER JOIN ' . WLSM_EXPENSE_CATEGORIES . ' as ec ON ec.ID = ep.expense_category_id
		WHERE ep.school_id = ' . absint( $school_id ) .' AND ep.expense_date between "' . $session_start_date . '" and "' . $session_end_date . '"';


		if ( ! empty( $start_date && $end_date ) ) {
			$query .= ' AND ep.expense_date BETWEEN "' . $start_date . '" AND "' . $end_date . '"';
		}
		return $query;
	}

	public static function fetch_expense_query_group_by() {
		$group_by = 'GROUP BY ep.ID';
		return $group_by;
	}

	public static function fetch_expense_query_count( $school_id, $session_id, $session_start_date = null, $session_end_date = null ) {
		$query = 'SELECT COUNT(DISTINCT ep.ID) FROM ' . WLSM_EXPENSES . ' as ep
		LEFT OUTER JOIN ' . WLSM_EXPENSE_CATEGORIES . ' as ec ON ec.ID = ep.expense_category_id
		WHERE ep.school_id = ' . absint( $school_id ).' AND ep.session_id = ' . absint( $session_id ) . ' AND ep.expense_date between "' . $session_start_date . '" and "' . $session_end_date . '"';
		return $query;
	}

	public static function get_expense( $school_id, $id ) {
		global $wpdb;
		$expense = $wpdb->get_row( $wpdb->prepare( 'SELECT ep.ID FROM ' . WLSM_EXPENSES . ' as ep
		WHERE ep.school_id = %d AND ep.ID = %d', $school_id, $id ) );
		return $expense;
	}

	public static function fetch_expense( $school_id, $id ) {
		global $wpdb;
		$expense = $wpdb->get_row( $wpdb->prepare( 'SELECT ep.ID, ep.label, ep.invoice_number, ep.amount, ep.expense_date, ep.note, ep.attachment, ep.expense_category_id FROM ' . WLSM_EXPENSES . ' as ep
		WHERE ep.school_id = %d AND ep.ID = %d', $school_id, $id ) );
		return $expense;
	}

	public static function fetch_expense_categories( $school_id ) {
		global $wpdb;
		$expense_categories = $wpdb->get_results( $wpdb->prepare( 'SELECT ec.ID, ec.label FROM ' . WLSM_EXPENSE_CATEGORIES . ' as ec WHERE ec.school_id = %d ORDER BY ec.ID ASC', $school_id ) );
		return $expense_categories;
	}

	public static function get_expense_note( $school_id, $id ) {
		global $wpdb;
		$expense = $wpdb->get_row( $wpdb->prepare( 'SELECT ep.ID, ep.note FROM ' . WLSM_EXPENSES . ' as ep
		WHERE ep.school_id = %d AND ep.ID = %d', $school_id, $id ) );
		return $expense;
	}

	public static function fetch_income_query( $school_id, $start_date, $end_date ) {
		$query = 'SELECT im.ID, im.label, im.invoice_number, im.amount, im.income_date, im.note, ic.label as income_category FROM ' . WLSM_INCOME . ' as im
		LEFT OUTER JOIN ' . WLSM_INCOME_CATEGORIES . ' as ic ON ic.ID = im.income_category_id
		WHERE im.school_id = ' . absint( $school_id );

		if ($start_date && $end_date) {
			$query .= ' AND im.income_date BETWEEN "' . $start_date . '" AND "' . $end_date . '"';
		}

		return $query;
	}

	public static function fetch_income_query_group_by() {
		$group_by = 'GROUP BY im.ID';
		return $group_by;
	}

	public static function fetch_income_query_count( $school_id ) {
		$query = 'SELECT COUNT(DISTINCT im.ID) FROM ' . WLSM_INCOME . ' as im
		LEFT OUTER JOIN ' . WLSM_INCOME_CATEGORIES . ' as ic ON ic.ID = im.income_category_id
		WHERE im.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function get_income( $school_id, $id ) {
		global $wpdb;
		$income = $wpdb->get_row( $wpdb->prepare( 'SELECT im.ID FROM ' . WLSM_INCOME . ' as im
		WHERE im.school_id = %d AND im.ID = %d', $school_id, $id ) );
		return $income;
	}

	public static function fetch_income( $school_id, $id ) {
		global $wpdb;
		$income = $wpdb->get_row( $wpdb->prepare( 'SELECT im.ID, im.label, im.invoice_number, im.amount, im.income_date, im.note, im.attachment, im.income_category_id FROM ' . WLSM_INCOME . ' as im
		WHERE im.school_id = %d AND im.ID = %d', $school_id, $id ) );
		return $income;
	}

	public static function fetch_income_categories( $school_id ) {
		global $wpdb;
		$income_categories = $wpdb->get_results( $wpdb->prepare( 'SELECT ic.ID, ic.label FROM ' . WLSM_INCOME_CATEGORIES . ' as ic WHERE ic.school_id = %d ORDER BY ic.ID ASC', $school_id ) );
		return $income_categories;
	}

	public static function get_income_note( $school_id, $id ) {
		global $wpdb;
		$income = $wpdb->get_row( $wpdb->prepare( 'SELECT im.ID, im.note FROM ' . WLSM_INCOME . ' as im
		WHERE im.school_id = %d AND im.ID = %d', $school_id, $id ) );
		return $income;
	}

	public static function get_student_pending_invoices( $student_id ) {
		global $wpdb;
		$invoices = $wpdb->get_results(
			$wpdb->prepare( 'SELECT i.ID, i.label as invoice_title, i.invoice_number, i.date_issued, i.due_date, i.amount, (i.amount ) as payable, COALESCE(SUM(p.amount), 0) as paid, ((i.amount ) - COALESCE(SUM(p.amount), 0)) as due, i.status, sr.name as student_name, sr.enrollment_number, c.label as class_label, se.label as section_label FROM ' . WLSM_INVOICES . ' as i
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				LEFT OUTER JOIN ' . WLSM_PAYMENTS . ' as p ON p.invoice_id = i.ID
				WHERE sr.ID = %d AND (i.status = "%s" OR i.status = "%s") GROUP BY i.ID ORDER BY i.ID DESC', $student_id, WLSM_M_Invoice::get_unpaid_key(), WLSM_M_Invoice::get_partially_paid_key() )
		);
		return $invoices;
	}

	public static function get_student_pending_invoices_paid($student_id, $fee_paid)
	{
		global $wpdb;
		$invoices = $wpdb->get_results(
			$wpdb->prepare('SELECT i.ID, i.label as invoice_title, wif.label as fees_title, wif.active_on_dashboard, i.invoice_number, i.date_issued, i.due_date, i.amount, (i.amount ) as payable, COALESCE(SUM(p.amount), 0) as paid, ((i.amount ) - COALESCE(SUM(p.amount), 0)) as due, i.status, sr.name as student_name, sr.enrollment_number, c.label as class_label, se.label as section_label FROM ' . WLSM_INVOICES .' as i

				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_CLASSES .' as c ON c.ID = cs.class_id
				JOIN ' . WLSM_FEES . ' as wif ON wif.label = i.label
				LEFT OUTER JOIN ' . WLSM_PAYMENTS . ' as p ON p.invoice_id = i.ID
				WHERE sr.ID = %d AND wif.active_on_dashboard = '.$fee_paid.' AND (i.status = "%s" OR i.status = "%s") GROUP BY i.ID ORDER BY i.ID DESC', $student_id, WLSM_M_Invoice::get_unpaid_key(), WLSM_M_Invoice::get_partially_paid_key())
		);
		return $invoices;
	}

	public static function get_student_pending_invoice( $invoice_id ) {
		global $wpdb;
		$invoice = $wpdb->get_row(
			$wpdb->prepare( 'SELECT i.ID, i.label as invoice_title, i.invoice_number, i.date_issued, i.due_date, i.amount, (i.amount ) as payable, COALESCE(SUM(p.amount), 0) as paid, ((i.amount ) - COALESCE(SUM(p.amount), 0)) as due, i.status, i.due_date_amount, i.partial_payment, i.student_record_id as student_id, sr.name as student_name, sr.phone, sr.email, sr.address, sr.admission_number, sr.enrollment_number, sr.admission_number, sr.session_id, c.label as class_label, se.label as section_label, cs.school_id, u.user_email as login_email FROM ' . WLSM_INVOICES . ' as i
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				LEFT OUTER JOIN ' . WLSM_PAYMENTS . ' as p ON p.invoice_id = i.ID
				LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id
				WHERE (i.status = "%s" OR i.status = "%s") AND i.ID = %d', WLSM_M_Invoice::get_unpaid_key(), WLSM_M_Invoice::get_partially_paid_key(), $invoice_id )
		);
		return $invoice;
	}

	public static function get_student_invoices( $student_id ) {
		global $wpdb;
		$invoices = $wpdb->get_results(
			$wpdb->prepare( 'SELECT i.ID, i.label as invoice_title, i.invoice_number, i.date_issued, i.due_date, i.amount, (i.amount ) as payable, COALESCE(SUM(p.amount), 0) as paid, ((i.amount ) - COALESCE(SUM(p.amount), 0)) as due, i.status, sr.name as student_name, sr.enrollment_number, c.label as class_label, se.label as section_label FROM ' . WLSM_INVOICES . ' as i
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				LEFT OUTER JOIN ' . WLSM_PAYMENTS . ' as p ON p.invoice_id = i.ID
				WHERE sr.ID = %d GROUP BY i.ID ORDER BY i.ID DESC', $student_id )
		);
		return $invoices;
	}

	public static function get_student_payments( $student_id ) {
		global $wpdb;
		$payments = $wpdb->get_results(
			$wpdb->prepare( 'SELECT sr.name as student_name, sr.admission_number, sr.phone, sr.father_name, sr.father_phone, p.ID, p.receipt_number, p.amount, p.payment_method, p.transaction_id, p.created_at, p.note, p.invoice_label, p.invoice_payable, p.invoice_id, i.label as invoice_title, c.label as class_label, se.label as section_label FROM ' . WLSM_PAYMENTS . ' as p
				JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = p.school_id
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = p.student_record_id
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				LEFT OUTER JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id
				WHERE sr.ID = %d GROUP BY p.ID ORDER BY p.ID DESC', $student_id )
		);
		return $payments;
	}

	public static function get_student_payment( $student_id, $payment_id ) {
		global $wpdb;
		$payment = $wpdb->get_row(
			$wpdb->prepare( 'SELECT sr.name as student_name, sr.roll_number, sr.admission_number, sr.enrollment_number, sr.phone, sr.email, sr.father_name, sr.father_phone, p.ID, p.receipt_number, p.amount, p.payment_method, p.transaction_id, p.created_at, p.note, p.invoice_label, p.invoice_payable, p.invoice_id, i.label as invoice_title, c.label as class_label, se.label as section_label FROM ' . WLSM_PAYMENTS . ' as p
				JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = p.school_id
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = p.student_record_id
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				LEFT OUTER JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id
				WHERE sr.ID = %d AND p.ID = %d', $student_id, $payment_id )
		);
		return $payment;
	}

	public static function get_total_payments_received( $school_id, $session_id ) {
		global $wpdb;

		return $wpdb->get_var(
			$wpdb->prepare( 'SELECT COALESCE(SUM(p.amount), 0) as sum FROM ' . WLSM_PAYMENTS . ' as p
				JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = p.school_id
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = p.student_record_id
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				WHERE p.school_id = %d AND ss.ID = %d', $school_id, $session_id )
		);
	}

	public static function get_total_fees_structure_amount( $school_id, $session_id ) {
		global $wpdb;

		return $wpdb->get_var(
			$wpdb->prepare( 'SELECT COALESCE(SUM(sft.amount), 0) as sum FROM ' . WLSM_STUDENT_FEES . ' as sft
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = sft.student_record_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
				WHERE cs.school_id = %d AND ss.ID = %d', $school_id, $session_id )
		);
	}

	public static function get_fees_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_FEES );
	}

	public static function fetch_fee_query( $school_id ) {
		$query = 'SELECT ft.ID, c.label, ft.label as fee_label, ft.amount, ft.period, ft.class_id FROM ' . WLSM_FEES . ' as ft
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = ft.class_id
		WHERE ft.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function fetch_fee_query_group_by() {
		$group_by = 'GROUP BY ft.ID';
		return $group_by;
	}

	public static function fetch_fee_query_count( $school_id ) {
		$query = 'SELECT COUNT(DISTINCT ft.ID) FROM ' . WLSM_FEES . ' as ft
		WHERE ft.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function get_fee( $school_id, $id ) {
		global $wpdb;
		$fee = $wpdb->get_row( $wpdb->prepare( 'SELECT ft.ID FROM ' . WLSM_FEES . ' as ft
		WHERE ft.school_id = %d AND ft.ID = %d', $school_id, $id ) );
		return $fee;
	}

	public static function fetch_fee( $school_id, $id ) {
		global $wpdb;
		$fee = $wpdb->get_row( $wpdb->prepare( 'SELECT ft.ID, ft.label, ft.amount, ft.period, ft.active_on_admission, ft.active_on_dashboard,  ft.class_id FROM ' . WLSM_FEES . ' as ft
		WHERE ft.school_id = %d AND ft.ID = %d', $school_id, $id ) );
		return $fee;
	}

	public static function fetch_fees( $school_id, $active_on_admission = true ) {
		global $wpdb;

		$where = '';
		if ( $active_on_admission ) {
			$where .= ' AND ft.active_on_admission = 1';
		}

		$fees = $wpdb->get_results( $wpdb->prepare('SELECT ft.ID, ft.label, ft.amount, ft.period, ft.period, ft.active_on_dashboard, ft.active_on_admission, ft.class_id  FROM ' . WLSM_FEES . ' as ft
		WHERE ft.school_id = %d' . $where, $school_id ) );
		return $fees;
	}

	public static function fetch_fees_by_class( $school_id, $class_id, $active_on_admission = true ) {
		global $wpdb;

		$where = '';
		if ( $active_on_admission ) {
			$where .= ' AND ft.assign_on_admission = 1';
		}

		$fees = $wpdb->get_results( $wpdb->prepare('SELECT ft.ID, ft.label, ft.amount, ft.period, ft.period, ft.active_on_dashboard, ft.active_on_admission, ft.class_id  FROM ' . WLSM_FEES . ' as ft
		WHERE ft.school_id = %d AND ft.class_id = %d', $school_id, $class_id ) );
		return $fees;
	}

	public static function fetch_fees_paid_dashboard($school_id, $active_on_dashboard = true)
	{
		global $wpdb;

		$where = '';
		if ($active_on_dashboard) {
			$where .= ' AND ft.active_on_dashboard = 1';
		}

		$fees = $wpdb->get_results($wpdb->prepare('SELECT ft.ID, ft.label, ft.amount, , ft.period FROM ' . WLSM_FEES . ' as ft
		WHERE ft.school_id = %d' . $where, $school_id));
		return $fees;
	}

	public static function fetch_student_assigned_fees( $school_id, $student_id ) {
		global $wpdb;
		$fees = $wpdb->get_results( $wpdb->prepare( 'SELECT sft.ID, sft.label, sft.amount, sft.period, sft.fee_order, sft.student_record_id FROM ' . WLSM_STUDENT_FEES . ' as sft
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = sft.student_record_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		WHERE cs.school_id = %d AND sft.student_record_id = %d ORDER BY sft.fee_order ASC', $school_id, $student_id ) );
		return $fees;
	}

	public static function fetch_student_fees( $school_id, $student_id ) {
		global $wpdb;
		$fees = $wpdb->get_results( $wpdb->prepare( 'SELECT sft.ID, sft.label, sft.amount, sft.period, sft.fee_order, sft.student_record_id FROM ' . WLSM_STUDENT_FEES . ' as sft
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = sft.student_record_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		WHERE cs.school_id = %d AND sft.student_record_id = %d ORDER BY sft.fee_order ASC', $school_id, $student_id ) );
		return $fees;
	}
	public static function fetch_student_fees_invoices( $school_id, $student_id ) {
		global $wpdb;
		$fees = $wpdb->get_results( $wpdb->prepare( 'SELECT i.ID, i.label, i.amount, i.student_record_id FROM ' . WLSM_INVOICES . ' as i
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id

		WHERE  sr.ID = ' . absint( $student_id )));

		return $fees;
	}

	public static function get_invoice_title_text( $invoice_title ) {
		if ( $invoice_title ) {
			return $invoice_title;
		}
		return '-';
	}

	public static function get_label_text( $label ) {
		if ( $label ) {
			return stripcslashes( $label );
		}
		return '';
	}

	public static function get_category_label_text( $label ) {
		if ( $label ) {
			return stripcslashes( $label );
		}
		return '-';
	}

	public static function get_partial_payments_allowed_text( $invoice_partial_payment ) {
		if ( $invoice_partial_payment ) {
			return esc_html__( 'Yes', 'school-management' );
		}
		return esc_html__( 'No', 'school-management' );
	}

	public static function get_fee_period_text( $period ) {
		if ( isset( WLSM_Helper::fee_period_list()[ $period ] ) ) {
			return WLSM_Helper::fee_period_list()[ $period ];
		}
		return '-';
	}
}
