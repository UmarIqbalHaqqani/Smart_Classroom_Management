<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Config.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Export.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Role.php';

class WLSM_Staff_Export {
	public static function export_staff_students_table() {
		$current_user = WLSM_M_Role::can( 'manage_students' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		$current_school = $current_user['school'];

		$restrict_to_section = WLSM_M_Role::restrict_to_section( $current_school );

		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'export-staff-students-table' ) ) {
				die();
			}

			$filter = json_decode( stripcslashes( $_POST['filter'] ) );

			$search_students_by = '';

			$search_field   = '';
			$search_keyword = '';

			$class_id   = '';
			$section_id = '';

			if ( $filter ) {
				$search_students_by = isset( $filter->search_students_by ) ? sanitize_text_field( $filter->search_students_by ) : '';

				$search_field   = isset( $filter->search_field ) ? sanitize_text_field( $filter->search_field ) : '';
				$search_keyword = isset( $filter->search_keyword ) ? sanitize_text_field( $filter->search_keyword ) : '';

				$class_id   = isset( $filter->class_id ) ? absint( $filter->class_id ) : 0;
				$section_id = isset( $filter->section_id ) ? absint( $filter->section_id ) : 0;
			}

			if ( ! in_array( $search_students_by, array( 'search_by_keyword', 'search_by_class' ) ) ) {
				throw new Exception( esc_html__( 'Please specify search criteria.', 'school-management' ) );
			}

			if ( 'search_by_keyword' === $search_students_by ) {
				if ( ! empty( $search_field ) && empty( $search_keyword ) ) {
					throw new Exception( esc_html__( 'Please enter search keyword.', 'school-management' ) );
				} else if ( ! empty( $search_keyword ) && empty( $search_field ) ) {
					throw new Exception( esc_html__( 'Please specify search field.', 'school-management' ) );
				}

				$filter = array(
					'search_field'   => $search_field,
					'search_keyword' => $search_keyword,
				);

			} else {
				if ( empty( $class_id ) ) {
					throw new Exception( esc_html__( 'Please select a class.', 'school-management' ) );
				}

				$filter = array(
					'class_id'   => $class_id,
					'section_id' => $section_id,
				);
			}

			$filter['search_by'] = $search_students_by;

			$query = WLSM_M_Staff_General::fetch_students_query( $school_id, $session_id, $filter, $restrict_to_section );

			// Grouping.
			$group_by = ' ' . WLSM_M_Staff_General::fetch_students_query_group_by();
			$query   .= $group_by;

			// Ordering.
			$query .= ' ORDER BY sr.ID DESC';

			// Data.
			$data = $wpdb->get_results( $query );

			$fields = array(
				esc_html__( 'Student Name', 'school-management' ),
				esc_html__( 'Admission Number', 'school-management' ),
				esc_html__( 'Admission Date', 'school-management' ),
				esc_html__( 'Class', 'school-management' ),
				esc_html__( 'Section', 'school-management' ),
				esc_html__( 'Roll Number', 'school-management' ),
				esc_html__( 'Gender', 'school-management' ),
				esc_html__( 'Date of Birth', 'school-management' ),
				esc_html__( 'Phone', 'school-management' ),
				esc_html__( 'Email', 'school-management' ),
				esc_html__( 'Address', 'school-management' ),
				esc_html__( 'Religion', 'school-management' ),
				esc_html__( 'Caste', 'school-management' ),
				esc_html__( 'Blood Group', 'school-management' ),
				esc_html__( 'Father\'s Name', 'school-management' ),
				esc_html__( 'Father\'s Phone', 'school-management' ),
				esc_html__( 'Father\'s Occupation', 'school-management' ),
				esc_html__( 'Mother\'s Name', 'school-management' ),
				esc_html__( 'Mother\'s Phone', 'school-management' ),
				esc_html__( 'Mother\'s Occupation', 'school-management' ),
				esc_html__( 'Status', 'school-management' ),
				esc_html__( 'Enrollment Number', 'school-management' ),
				esc_html__( 'Login Email', 'school-management' ),
				esc_html__( 'Login Username', 'school-management' ),
			);

			$filename  = 'students_' . date( WLSM_Config::date_format() ) . '.csv';
			$delimiter = ',';

			$f = fopen( 'php://memory', 'w' );

			fputcsv( $f, $fields, $delimiter );

			foreach ( $data as $row ) {
				$record = array(
					esc_html( WLSM_M_Staff_Class::get_name_text( $row->student_name ) ),
					esc_html( WLSM_M_Staff_Class::get_admission_no_text( $row->admission_number ) ),
					esc_html( WLSM_Config::get_date_text( $row->admission_date ) ),
					esc_html( WLSM_M_Class::get_label_text( $row->class_label ) ),
					esc_html( WLSM_M_Staff_Class::get_section_label_text( $row->section_label ) ),
					esc_html( $row->roll_number ),
					esc_html( $row->gender ),
					esc_html( WLSM_Config::get_date_text( $row->dob ) ),
					esc_html( $row->phone ),
					esc_html( stripslashes( $row->email ) ),
					esc_html( stripslashes( $row->address ) ),
					esc_html( stripslashes( $row->religion ) ),
					esc_html( stripslashes( $row->caste ) ),
					esc_html( $row->blood_group ),
					esc_html( stripslashes( $row->father_name ) ),
					esc_html( $row->father_phone ),
					esc_html( $row->father_occupation ),
					esc_html( stripslashes( $row->mother_name ) ),
					esc_html( $row->mother_phone ),
					esc_html( $row->mother_occupation ),
					esc_html( $row->is_active ),
					esc_html( $row->enrollment_number ),
					esc_html( $row->login_email ),
					esc_html( $row->username )
				);

				fputcsv( $f, $record, $delimiter );
			}

			WLSM_Export::export_and_close_csv_file( $f, $filename );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}
	}

	public static function export_staff_inquiries_table() {
		$current_user = WLSM_M_Role::can( 'manage_inquiries' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'export-staff-inquiries-table' ) ) {
				die();
			}

			$query = WLSM_M_Staff_General::fetch_inquiry_query( $school_id );

			// Grouping.
			$group_by = ' ' . WLSM_M_Staff_General::fetch_inquiry_query_group_by();
			$query   .= $group_by;

			// Ordering.
			$query .= ' ORDER BY iq.ID DESC';

			// Data.
			$data = $wpdb->get_results( $query );

			$fields = array(
				esc_html__( 'Class', 'school-management' ),
				esc_html__( 'Name', 'school-management' ),
				esc_html__( 'Phone', 'school-management' ),
				esc_html__( 'Email', 'school-management' ),
				esc_html__( 'Message', 'school-management' ),
				esc_html__( 'Date', 'school-management' ),
				esc_html__( 'Follow Up Date', 'school-management' ),
				esc_html__( 'Note', 'school-management' ),
				esc_html__( 'Status', 'school-management' ),
			);

			$filename  = 'inquiries_' . date( WLSM_Config::date_format() ) . '.csv';
			$delimiter = ',';

			$f = fopen( 'php://memory', 'w' );

			fputcsv( $f, $fields, $delimiter );

			foreach ( $data as $row ) {
				$record = array(
					esc_html( WLSM_M_Class::get_label_text( $row->class_label ) ),
					esc_html( WLSM_M_Staff_Class::get_name_text( $row->name ) ),
					esc_html( WLSM_M_Staff_Class::get_phone_text( $row->phone ) ),
					esc_html( WLSM_M_Staff_Class::get_name_text( $row->email ) ),
					esc_html( stripcslashes( $row->message ) ),
					esc_html( WLSM_Config::get_date_text( $row->created_at ) ),
					esc_html( $row->next_follow_up ? WLSM_Config::get_date_text( $row->next_follow_up ) : '-' ),
					esc_html( WLSM_Config::get_note_text( $row->note ) ),
					esc_html( WLSM_M_Staff_General::get_inquiry_status_text( $row->is_active ) ),
				);

				fputcsv( $f, $record, $delimiter );
			}

			WLSM_Export::export_and_close_csv_file( $f, $filename );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}
	}

	public static function export_staff_invoices_table() {
		$current_user = WLSM_M_Role::can( 'manage_invoices' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'export-staff-invoices-table' ) ) {
				die();
			}

			$filter = json_decode( stripcslashes( $_POST['filter'] ) );

			$search_students_by = '';

			$search_field   = '';
			$search_keyword = '';

			$class_id   = '';
			$section_id = '';

			if ( $filter ) {
				$search_students_by = isset( $filter->search_students_by ) ? sanitize_text_field( $filter->search_students_by ) : '';

				$search_field   = isset( $filter->search_field ) ? sanitize_text_field( $filter->search_field ) : '';
				$search_keyword = isset( $filter->search_keyword ) ? sanitize_text_field( $filter->search_keyword ) : '';

				$class_id   = isset( $filter->class_id ) ? absint( $filter->class_id ) : 0;
				$section_id = isset( $filter->section_id ) ? absint( $filter->section_id ) : 0;
			}

			if ( ! in_array( $search_students_by, array( 'search_by_keyword', 'search_by_class' ) ) ) {
				throw new Exception( esc_html__( 'Please specify search criteria.', 'school-management' ) );
			}

			if ( 'search_by_keyword' === $search_students_by ) {
				if ( ! empty( $search_field ) && empty( $search_keyword ) ) {
					throw new Exception( esc_html__( 'Please enter search keyword.', 'school-management' ) );
				} else if ( ! empty( $search_keyword ) && empty( $search_field ) ) {
					throw new Exception( esc_html__( 'Please specify search field.', 'school-management' ) );
				}

				$filter = array(
					'search_field'   => $search_field,
					'search_keyword' => $search_keyword,
				);

			} else {
				if ( empty( $class_id ) ) {
					throw new Exception( esc_html__( 'Please select a class.', 'school-management' ) );
				}

				$filter = array(
					'class_id'   => $class_id,
					'section_id' => $section_id,
				);
			}

			$filter['search_by'] = $search_students_by;

			$query = WLSM_M_Staff_Accountant::fetch_invoices_query( $school_id, $session_id, $filter );

			// Grouping.
			$group_by = ' ' . WLSM_M_Staff_Accountant::fetch_invoices_query_group_by();
			$query   .= $group_by;

			// Ordering.
			$query .= ' ORDER BY i.ID DESC';

			// Data.
			$data = $wpdb->get_results( $query );

			$fields = array(
				esc_html__( 'Student Name', 'school-management' ),
				esc_html__( 'Admission Number', 'school-management' ),
				esc_html__( 'Invoice Number', 'school-management' ),
				esc_html__( 'Invoice Title', 'school-management' ),
				esc_html__( 'Payable', 'school-management' ),
				esc_html__( 'Paid', 'school-management' ),
				esc_html__( 'Due', 'school-management' ),
				esc_html__( 'Status', 'school-management' ),
				esc_html__( 'Date Issued', 'school-management' ),
				esc_html__( 'Due Date', 'school-management' ),
				esc_html__( 'Phone', 'school-management' ),
				esc_html__( 'Class', 'school-management' ),
				esc_html__( 'Section', 'school-management' ),
				esc_html__( 'Enrollment Number', 'school-management' )
			);

			$filename  = 'fee_invoices_' . date( WLSM_Config::date_format() ) . '.csv';
			$delimiter = ',';

			$f = fopen( 'php://memory', 'w' );

			fputcsv( $f, $fields, $delimiter );

			foreach ( $data as $row ) {
				$due = $row->payable - $row->paid;

				$record = array(
					esc_html( WLSM_M_Staff_Class::get_name_text( $row->student_name ) ),
					esc_html( WLSM_M_Staff_Class::get_admission_no_text( $row->admission_number ) ),
					esc_html( $row->invoice_number ),
					esc_html( WLSM_M_Staff_Accountant::get_invoice_title_text( $row->invoice_title ) ),
					esc_html( WLSM_Config::sanitize_money( $row->payable ) ),
					esc_html( WLSM_Config::sanitize_money( $row->paid ) ),
					esc_html( WLSM_Config::sanitize_money( $due ) ),
					esc_html( strip_tags( WLSM_M_Invoice::get_status_text( $row->status ) ) ),
					esc_html( WLSM_Config::get_date_text( $row->date_issued ) ),
					esc_html( WLSM_Config::get_date_text( $row->due_date ) ),
					esc_html( WLSM_M_Staff_Class::get_phone_text( $row->phone ) ),
					esc_html( WLSM_M_Class::get_label_text( $row->class_label ) ),
					esc_html( WLSM_M_Staff_Class::get_section_label_text( $row->section_label ) ),
					esc_html( $row->enrollment_number )
				);

				fputcsv( $f, $record, $delimiter );
			}

			WLSM_Export::export_and_close_csv_file( $f, $filename );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}
	}

	public static function export_staff_payments_table() {
		$current_user = WLSM_M_Role::can( 'manage_invoices' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'export-staff-payments-table' ) ) {
				die();
			}

			$filter = json_decode( stripcslashes( $_POST['filter'] ) );

			$start_date = '';
			$end_date   = '';

			if ( $filter ) {
				$start_date = isset( $filter->start_date ) ? sanitize_text_field( $filter->start_date ) : '';
				$end_date   = isset( $filter->end_date ) ? sanitize_text_field( $filter->end_date ) : '';
			}

			if (  empty( $start_date ) ) {
				throw new Exception( esc_html__( 'Please specify search date.', 'school-management' ) );
			}

			if ( !empty( $start_date ) ) {
				$filter = array(
					'start_date' => $start_date,
					'end_date'   => $end_date,
				);
			}

			$query = WLSM_M_Staff_Accountant::fetch_payments_query($school_id, $session_id, $start_date, $end_date);

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Accountant::fetch_payments_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if (isset($_POST['search']['value'])) {
			$search_value = sanitize_text_field($_POST['search']['value']);
			if ('' !== $search_value) {
				$condition .= '' .
					'(p.receipt_number LIKE "%' . $search_value . '%") OR ' .
					'(p.amount LIKE "%' . $search_value . '%") OR ' .
					'(p.transaction_id LIKE "%' . $search_value . '%") OR ' .
					'(p.note LIKE "%' . $search_value . '%") OR ' .
					'(sr.name LIKE "%' . $search_value . '%") OR ' .
					'(sr.admission_number LIKE "%' . $search_value . '%") OR ' .
					'(sr.father_name LIKE "%' . $search_value . '%") OR ' .
					'(sr.father_phone LIKE "%' . $search_value . '%") OR ' .
					'(sr.enrollment_number LIKE "%' . $search_value . '%") OR ' .
					'(i.label LIKE "%' . $search_value . '%") OR ' .
					'(c.label LIKE "%' . $search_value . '%") OR ' .
					'(se.label LIKE "%' . $search_value . '%")';

				$payment_method = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $search_value));
				if (isset($payment_method)) {
					$condition .= ' OR (p.payment_method LIKE "%' . $payment_method . '%")';
				}

				$created_at = DateTime::createFromFormat(WLSM_Config::date_format(), $search_value);

				if ($created_at) {
					$format_created_at = 'Y-m-d';
				} else {
					if ('d-m-Y' === WLSM_Config::date_format()) {
						if (!$created_at) {
							$created_at        = DateTime::createFromFormat('m-Y', $search_value);
							$format_created_at = 'Y-m';
						}
					} else if ('d/m/Y' === WLSM_Config::date_format()) {
						if (!$created_at) {
							$created_at        = DateTime::createFromFormat('m/Y', $search_value);
							$format_created_at = 'Y-m';
						}
					} else if ('Y-m-d' === WLSM_Config::date_format()) {
						if (!$created_at) {
							$created_at        = DateTime::createFromFormat('Y-m', $search_value);
							$format_created_at = 'Y-m';
						}
					} else if ('Y/m/d' === WLSM_Config::date_format()) {
						if (!$created_at) {
							$created_at        = DateTime::createFromFormat('Y/m', $search_value);
							$format_created_at = 'Y-m';
						}
					}

					if (!$created_at) {
						$created_at        = DateTime::createFromFormat('Y', $search_value);
						$format_created_at = 'Y';
					}
				}

				if ($created_at && isset($format_created_at)) {
					$created_at = $created_at->format($format_created_at);
					$created_at = ' OR (p.created_at LIKE "%' . $created_at . '%")';

					$condition .= $created_at;
				}

				$query_filter .= (' HAVING ' . $condition);
			}
		}

		// Ordering.
		$columns = array('p.receipt_number', 'p.amount', 'p.payment_method', 'p.transaction_id', 'p.created_at', 'p.note', 'i.label', 'sr.name', 'sr.admission_number', 'c.label', 'se.label', 'sr.enrollment_number', 'sr.phone', 'sr.father_name', 'sr.father_phone');
		if (isset($_POST['order']) && isset($columns[$_POST['order']['0']['column']])) {
			$order_by  = sanitize_text_field($columns[$_POST['order']['0']['column']]);
			$order_dir = sanitize_text_field($_POST['order']['0']['dir']);

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY p.ID';
		}

		// Limiting.
		$limit = '';
		if (-1 != $_POST['length']) {
			$start  = absint($_POST['start']);
			$length = absint($_POST['length']);

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Accountant::fetch_payments_query_count($school_id, $session_id);

		// Total rows count.
		$total_rows_count = $wpdb->get_var($rows_query);

		// Filtered rows count.
		if ($condition) {
			$filter_rows_count = $wpdb->get_var($rows_query . ' AND (' . $condition . ')');
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		if (!empty($start_date)) {
			$data = $wpdb->get_results($query_filter);
		} else {
			$data = $wpdb->get_results($query_filter . $limit);
		}

			$fields = array(
				esc_html__( 'Receipt Number', 'school-management' ),
				esc_html__( 'Amount', 'school-management' ),
				esc_html__( 'Payment Method', 'school-management' ),
				esc_html__( 'Transaction ID', 'school-management' ),
				esc_html__( 'Date', 'school-management' ),
				esc_html__( 'Invoice', 'school-management' ),
				esc_html__( 'Student Name', 'school-management' ),
				esc_html__( 'Admission Number', 'school-management' ),
				esc_html__( 'Class', 'school-management' ),
				esc_html__( 'Section', 'school-management' ),
				esc_html__( 'Enrollment Number', 'school-management' ),
				esc_html__( 'Phone', 'school-management' ),
				esc_html__( 'father Name', 'school-management' ),
				esc_html__( 'father Phone', 'school-management' ),
			);

			$filename  = 'fee_payments_' . date( WLSM_Config::date_format() ) . '.csv';
			$delimiter = ',';

			$f = fopen( 'php://memory', 'w' );

			fputcsv( $f, $fields, $delimiter );

			foreach ( $data as $row ) {
				$due = $row->payable - $row->paid;

				$record = array(
					esc_html(WLSM_M_Invoice::get_receipt_number_text($row->receipt_number)),
					esc_html(($row->amount)),
					esc_html(WLSM_M_Invoice::get_payment_method_text($row->payment_method)),
					esc_html(WLSM_M_Invoice::get_transaction_id_text($row->transaction_id)),
					esc_html(WLSM_Config::get_date_text($row->created_at)),
					esc_html(WLSM_M_Staff_Class::get_name_text($row->invoice_title)),
					esc_html(WLSM_M_Staff_Class::get_name_text($row->student_name)),
					esc_html(WLSM_M_Staff_Class::get_admission_no_text($row->admission_number)),
					esc_html(WLSM_M_Class::get_label_text($row->class_label)),
					esc_html(WLSM_M_Staff_Class::get_section_label_text($row->section_label)),
					esc_html($row->enrollment_number),
					esc_html(WLSM_M_Staff_Class::get_phone_text($row->phone)),
					esc_html(WLSM_M_Staff_Class::get_name_text($row->father_name)),
					esc_html(WLSM_M_Staff_Class::get_phone_text($row->father_phone)),
				);

				fputcsv( $f, $record, $delimiter );
			}

			WLSM_Export::export_and_close_csv_file( $f, $filename );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}
	}

	public static function export_staff_expenses_table() {
		$current_user = WLSM_M_Role::can( 'manage_expenses' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];
		$current_session = $current_user['session'];

		$session_start_date = new DateTime($current_session['start_date']);
		$session_end_date   = new DateTime($current_session['end_date']);
		$session_start_date = $session_start_date->format('Y-m-d');
		$session_end_date   = $session_end_date->format('Y-m-d');

		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'export-staff-expenses-table' ) ) {
				die();
			}
			$query = WLSM_M_Staff_Accountant::fetch_expense_query( $school_id, $start_date, $end_date, $session_start_date,  $session_end_date );

			// Grouping.
			$group_by = ' ' . WLSM_M_Staff_Accountant::fetch_expense_query_group_by();
			$query   .= $group_by;

			// Ordering.
			$query .= ' ORDER BY ep.ID DESC';

			// Data.
			$data = $wpdb->get_results( $query );

			$fields = array(
				esc_html__( 'Title', 'school-management' ),
				esc_html__( 'Category', 'school-management' ),
				esc_html__( 'Amount', 'school-management' ),
				esc_html__( 'Invoice Number', 'school-management' ),
				esc_html__( 'Date', 'school-management' ),
				esc_html__( 'Note', 'school-management' ),
			);

			$filename  = 'expenses_' . date( WLSM_Config::date_format() ) . '.csv';
			$delimiter = ',';

			$f = fopen( 'php://memory', 'w' );

			fputcsv( $f, $fields, $delimiter );

			foreach ( $data as $row ) {
				$record = array(
					esc_html( WLSM_M_Staff_Accountant::get_label_text( $row->label ) ),
					esc_html( WLSM_M_Staff_Accountant::get_category_label_text( $row->expense_category ) ),
					esc_html( WLSM_Config::sanitize_money( $row->amount ) ),
					esc_html( $row->invoice_number ),
					esc_html( WLSM_Config::get_date_text( $row->expense_date ) ),
					esc_html( WLSM_Config::get_note_text( $row->note ) )
				);

				fputcsv( $f, $record, $delimiter );
			}

			WLSM_Export::export_and_close_csv_file( $f, $filename );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}
	}

	public static function export_staff_income_table() {
		$current_user = WLSM_M_Role::can( 'manage_income' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'export-staff-income-table' ) ) {
				die();
			}

			$query = WLSM_M_Staff_Accountant::fetch_income_query( $school_id, $start_date = NULL, $end_date = NULL );

			// Grouping.
			$group_by = ' ' . WLSM_M_Staff_Accountant::fetch_income_query_group_by();
			$query   .= $group_by;

			// Ordering.
			$query .= ' ORDER BY im.ID DESC';

			// Data.
			$data = $wpdb->get_results( $query );

			$fields = array(
				esc_html__( 'Title', 'school-management' ),
				esc_html__( 'Category', 'school-management' ),
				esc_html__( 'Amount', 'school-management' ),
				esc_html__( 'Invoice Number', 'school-management' ),
				esc_html__( 'Date', 'school-management' ),
				esc_html__( 'Note', 'school-management' ),
			);

			$filename  = 'income_' . date( WLSM_Config::date_format() ) . '.csv';
			$delimiter = ',';

			$f = fopen( 'php://memory', 'w' );

			fputcsv( $f, $fields, $delimiter );

			foreach ( $data as $row ) {
				$record = array(
					esc_html( WLSM_M_Staff_Accountant::get_label_text( $row->label ) ),
					esc_html( WLSM_M_Staff_Accountant::get_category_label_text( $row->income_category ) ),
					esc_html( WLSM_Config::sanitize_money( $row->amount ) ),
					esc_html( $row->invoice_number ),
					esc_html( WLSM_Config::get_date_text( $row->income_date ) ),
					esc_html( WLSM_Config::get_note_text( $row->note ) )
				);

				fputcsv( $f, $record, $delimiter );
			}

			WLSM_Export::export_and_close_csv_file( $f, $filename );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}
	}

	public static function student_sample_csv_export() {
		$current_user = WLSM_M_Role::can( 'manage_admissions' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'student-sample-csv-export' ) ) {
				die();
			}

			$class_label   = '';
			$section_label = '';

			$class_id   = isset( $_POST['class_id'] ) ? absint( $_POST['class_id'] ) : 0;
			$section_id = isset( $_POST['section_id'] ) ? absint( $_POST['section_id'] ) : 0;

			// Checks if class exists in the school.
			$class_school = WLSM_M_Staff_Class::fetch_class( $school_id, $class_id );

			if ( $class_school ) {
				$class_school_id = $class_school->ID;

				$class_label = WLSM_M_Class::get_label_text( $class_school->label );

				if ( ! $section_id ) {
					$section_id = $class_school->default_section_id;
				}

				// Checks if section exists.
				$section = WLSM_M_Staff_Class::fetch_section( $school_id, $section_id, $class_school_id );

				if ( $section ) {
					$section_label = WLSM_M_Staff_Class::get_section_label_text( $section->label );
				}
			}

			$fields = array(
				esc_html__( 'Student Name', 'school-management' ),
				esc_html__( 'Admission Number', 'school-management' ),
				esc_html__( 'Admission Date', 'school-management' ),
				esc_html__( 'Class', 'school-management' ),
				esc_html__( 'Section', 'school-management' ),
				esc_html__( 'Roll Number', 'school-management' ),
				esc_html__( 'Gender', 'school-management' ),
				esc_html__( 'Date of Birth', 'school-management' ),
				esc_html__( 'Phone', 'school-management' ),
				esc_html__( 'Email', 'school-management' ),
				esc_html__( 'Address', 'school-management' ),
				esc_html__( 'Religion', 'school-management' ),
				esc_html__( 'Caste', 'school-management' ),
				esc_html__( 'Blood Group', 'school-management' ),
				esc_html__( 'Father\'s Name', 'school-management' ),
				esc_html__( 'Father\'s Phone', 'school-management' ),
				esc_html__( 'Father\'s Occupation', 'school-management' ),
				esc_html__( 'Mother\'s Name', 'school-management' ),
				esc_html__( 'Mother\'s Phone', 'school-management' ),
				esc_html__( 'Mother\'s Occupation', 'school-management' ),
				esc_html__( 'Status - Active (1), Inactive (0)', 'school-management' ),
				esc_html__( 'Username', 'school-management' ),
				esc_html__( 'Password', 'school-management' ),
				esc_html__( 'Confirm Password', 'school-management' ),
				esc_html__( 'Parent Email', 'school-management' ),
				esc_html__( 'Parent username', 'school-management' ),
				esc_html__( 'Password', 'school-management' ),
			);

			$filename  = 'bulk_students_sample.csv';
			$delimiter = ',';

			$f = fopen( 'php://memory', 'w' );

			fputcsv( $f, $fields, $delimiter );
			$date_format = get_option('wlsm_date_format');
			$sample_record = array(
				'Andrew Smith',
				'100001',
				esc_html( WLSM_Config::get_date_text( date($date_format ) ) ),
				esc_html( $class_label ),
				esc_html( $section_label ),
				'1',
				'male',
				esc_html( WLSM_Config::get_date_text( '2010-05-01' ) ),
				'9999999999',
				'andrew_smith@gmail.com',
				'101, Sample Address',
				'',
				'',
				'B+',
				'',
				'',
				'',
				'',
				'',
				'',
				esc_html( 1 )
			);

			fputcsv( $f, $sample_record, $delimiter );

			WLSM_Export::export_and_close_csv_file( $f, $filename );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}
	}

	public static function books_sample_csv_export() {
		$current_user = WLSM_M_Role::can( 'manage_library' );

		if ( ! $current_user ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'books-sample-csv-export' ) ) {
				die();
			}

			$fields = array(
				esc_html__( 'Book Title', 'school-management' ),
				esc_html__( 'Book Author', 'school-management' ),
				esc_html__( 'Subject', 'school-management' ),
				esc_html__( 'Price', 'school-management' ),
				esc_html__( 'Quantity', 'school-management' ),
				esc_html__( 'Description', 'school-management' ),
				esc_html__( 'Rack Number', 'school-management' ),
				esc_html__( 'Book Number', 'school-management' ),
				esc_html__( 'IBSN Number', 'school-management' ),
			);

			$filename  = 'bulk_books_sample.csv';
			$delimiter = ',';

			$f = fopen( 'php://memory', 'w' );

			fputcsv( $f, $fields, $delimiter );
			$date_format = get_option('wlsm_date_format');
			$sample_record = array(
				'Data Smart',
				'Foreman, John',
				'data science',
				'356',
				'20.00',
				"Data Science gets thrown around in the press like it's magic. Major retailers are predicting everything from when their customers are pregnant to when they want a new pair of Chuck Taylors.",
				"01",
				"B01",
				"B00F1"
			);

			fputcsv( $f, $sample_record, $delimiter );

			WLSM_Export::export_and_close_csv_file( $f, $filename );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}
	}

	public static function attendance_csv_export() {
		$current_user = WLSM_M_Role::can('manage_attendance');
		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'attendance-csv-export' ) ) {
				die();
			}

			if (!$current_user) {
				die();
			}

			$school_id  = $current_user['school']['id'];
			$session_id = $current_user['session']['ID'];

			$class_id   = isset($_POST['class_id']) ? absint($_POST['class_id']) : 0;
			$section_id = isset($_POST['section_id']) ? absint($_POST['section_id']) : 0;

			if (!$section_id) {
				// Get class students in current session.
				$students = WLSM_M_Staff_Class::get_class_students($school_id, $session_id, $class_id);
			} else {
				// Get section students in current session.
				$students = WLSM_M_Staff_Class::get_section_students($school_id, $session_id, $section_id);
			}

			$date_format = get_option('wlsm_date_format');
			$fields = array(
				esc_html__( 'Student Name', 'school-management' ),
				esc_html__( 'Roll Number', 'school-management' ),
				esc_html__( 'Student ID', 'school-management' ),
				esc_html__( 'Date', 'school-management' ),
				esc_html__( 'Status', 'school-management' ),

			);

			$filename  = 'bulk_staff_sample.csv';
			$delimiter = ',';

			$f = fopen( 'php://memory', 'w' );

			fputcsv( $f, $fields, $delimiter );
			$date_format = get_option('wlsm_date_format');

			foreach ($students as $student) {
				$sample_record = array(
					$student->name,
					$student->roll_number,
					$student->ID,
					date( WLSM_Config::date_format()),
				);
				fputcsv( $f, $sample_record, $delimiter );
			}

			WLSM_Export::export_and_close_csv_file( $f, $filename );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}
	}

	public static function staff_sample_csv_export() {
		$current_user = WLSM_M_Role::can( 'manage_library' );

		if ( ! $current_user ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'staff-sample-csv-export' ) ) {
				die();
			}

			$fields = array(
				esc_html__( 'Name', 'school-management' ),
				esc_html__( 'Gender', 'school-management' ),
				esc_html__( 'Date Of Birth', 'school-management' ),
				esc_html__( 'Address', 'school-management' ),
				esc_html__( 'Phone', 'school-management' ),
				esc_html__( 'Email', 'school-management' ),
				esc_html__( 'Joining Date', 'school-management' ),
				esc_html__( 'Role', 'school-management' ),
				esc_html__( 'Description', 'school-management' ),
				esc_html__( 'Salary', 'school-management' ),
				esc_html__( 'Designation', 'school-management' ),
				esc_html__( 'Class', 'school-management' ),
				esc_html__( 'Section', 'school-management' ),
				esc_html__( 'Active', 'school-management' ),
			);

			$filename  = 'bulk_staff_sample.csv';
			$delimiter = ',';

			$f = fopen( 'php://memory', 'w' );

			fputcsv( $f, $fields, $delimiter );
			$date_format = get_option('wlsm_date_format');
			$sample_record = array(
				'jhon doe',
				'male',
				esc_html( WLSM_Config::get_date_text( '2021-05-30' ) ),
				'Test Address',
				'9999999999',
				"example@mail.com",
				esc_html( WLSM_Config::get_date_text( '2021-05-01' ) ),
				"Teacher",
				"Test description",
				"20,000",
				"Selection",
				"10th",
				"A",
				"1",
			);

			fputcsv( $f, $sample_record, $delimiter );

			WLSM_Export::export_and_close_csv_file( $f, $filename );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}
	}

	public static function exam_results_csv_export() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'exam-results-csv-export' ) ) {
				die();
			}

			$exam_id = isset( $_POST['exam_id'] ) ? absint( $_POST['exam_id'] ) : 0;

			$exam = WLSM_M_Staff_Examination::fetch_exam( $school_id, $exam_id );

			if ( ! $exam ) {
				die;
			}

			$exam_papers = WLSM_M_Staff_Examination::get_exam_papers_by_exam_id( $school_id, $exam_id );
			$admit_cards = WLSM_M_Staff_Examination::get_exam_admit_cards( $school_id, $exam_id );

			$first_row_columns = array(
				esc_html__( 'Roll Number', 'school-management' ),
				esc_html__( 'Student Name', 'school-management' ),
				esc_html__( 'Class', 'school-management' ),
				esc_html__( 'Section', 'school-management' ),
			);

			foreach ( $exam_papers as $key => $exam_paper ) {
				$exam_paper_column = sprintf(
					/* translators: 1: paper code, 2. subject name, 3. maximum marks */
					esc_html__( '%1$s - %2$s (Max: %3$s)', 'school-management' ),
					esc_html( $exam_paper->paper_code ),
					esc_html( stripcslashes( $exam_paper->subject_label ) ),
					esc_html( $exam_paper->maximum_marks )
				);

				array_push( $first_row_columns, $exam_paper_column );

				$exam_remark_column = sprintf(
					/* translators: 1: subject name, 2. remark */
					esc_html__( '%1$s - %2$s', 'school-management' ),
					esc_html( stripcslashes( $exam_paper->subject_label ) ),
					esc_html( 'Remark' ),
				);

				array_push( $first_row_columns, $exam_remark_column );
			}

			$filename  = 'exam_results.csv';
			$delimiter = ',';

			$f = fopen( 'php://memory', 'w' );

			fputcsv( $f, $first_row_columns, $delimiter );

			foreach ( $admit_cards as $record ) {
				$admit_card_id = $record->ID;

				$student_admit_card = array(
					esc_html( WLSM_M_Staff_Class::get_roll_no_text( $record->roll_number ) ),
					esc_html( WLSM_M_Staff_Class::get_name_text( $record->name ) ),
					esc_html( WLSM_M_Class::get_label_text( $record->class_label ) ),
					esc_html( WLSM_M_Staff_Class::get_section_label_text( $record->section_label ) )
				);

				$exam_results = WLSM_M_Staff_Examination::get_exam_results_by_admit_card( $school_id, $admit_card_id );

				foreach ( $exam_papers as $key => $exam_paper ) {
					if ( isset( $exam_results[ $exam_paper->ID ] ) ) {
						$exam_result    = $exam_results[ $exam_paper->ID ];
						$obtained_marks = $exam_result->obtained_marks;
					} else {
						$obtained_marks = '';
					}

					array_push( $student_admit_card, esc_html( $obtained_marks ) );

					if ( isset( $exam_results[ $exam_paper->ID ] ) ) {
						$exam_result    = $exam_results[ $exam_paper->ID ];
						$remark = $exam_result->remark;
					} else {
						$remark = '';
					}

					array_push( $student_admit_card, esc_html( $remark ) );
				}

				fputcsv( $f, $student_admit_card, $delimiter );
			}

			WLSM_Export::export_and_close_csv_file( $f, $filename );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}
	}

	public static function export_staff_event_participants_table() {
		$current_user = WLSM_M_Role::can( 'manage_events' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		$event_id = isset( $_POST['event'] ) ? absint( $_POST['event'] ) : 0;

		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'export-staff-event-participants-table' ) ) {
				die();
			}

			$event = WLSM_M_Staff_Class::fetch_event( $school_id, $event_id );

			if ( ! $event ) {
				throw new Exception( esc_html__( 'Event not found.', 'school-management' ) );
			}

			$query = WLSM_M_Staff_Class::fetch_event_participants_query( $school_id, $session_id, $event_id );

			// Grouping.
			$group_by = ' ' . WLSM_M_Staff_Class::fetch_event_participants_query_group_by();
			$query   .= $group_by;

			// Ordering.
			$query .= ' ORDER BY evr.ID DESC';

			// Data.
			$data = $wpdb->get_results( $query );

			$fields = array(
				esc_html__( 'Student Name', 'school-management' ),
				esc_html__( 'Class', 'school-management' ),
				esc_html__( 'Section', 'school-management' ),
				esc_html__( 'Roll Number', 'school-management' ),
				esc_html__( 'Phone', 'school-management' ),
				esc_html__( 'Enrollment Number', 'school-management' )
			);

			$filename  = sanitize_title( sanitize_title( $event->title, '', 'save' ), '', 'query' ) . '_' . date( WLSM_Config::date_format() ) . '.csv';
			$delimiter = ',';

			$f = fopen( 'php://memory', 'w' );

			fputcsv( $f, $fields, $delimiter );

			foreach ( $data as $row ) {
				$record = array(
					esc_html( WLSM_M_Staff_Class::get_name_text( $row->student_name ) ),
					esc_html( WLSM_M_Class::get_label_text( $row->class_label ) ),
					esc_html( WLSM_M_Staff_Class::get_section_label_text( $row->section_label ) ),
					esc_html( $row->roll_number ),
					esc_html( WLSM_M_Staff_Class::get_phone_text( $row->phone ) ),
					esc_html( $row->enrollment_number )
				);

				fputcsv( $f, $record, $delimiter );
			}

			WLSM_Export::export_and_close_csv_file( $f, $filename );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}
	}
}
