<?php
defined('ABSPATH') || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_General.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Accountant.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Invoice.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Notify.php';

class WLSM_Staff_Accountant {
	public static function get_invoices() {
		$current_user = WLSM_M_Role::can('manage_invoices');

		if (!$current_user) {
			die();
		}

		$current_school = $current_user['school'];

		$can_delete_invoices = WLSM_M_Role::check_permission(array('delete_invoices'), $current_school['permissions']);
		$can_edit_invoices = WLSM_M_Role::check_permission(array('edit_invoices'), $current_school['permissions']);

		$school_id     = $current_user['school']['id'];
		$session_id    = $current_user['session']['ID'];
		$session_label = $current_user['session']['label'];

		if (!wp_verify_nonce($_POST['get-invoices'], 'get-invoices')) {
			die();
		}

		$from_table = isset($_POST['from_table']) ? (bool) ($_POST['from_table']) : 0;

		$output = array(
			'draw'            => 1,
			'recordsTotal'    => 0,
			'recordsFiltered' => 0,
			'data'            => array(),
		);

		$search_students_by = isset($_POST['search_students_by']) ? sanitize_text_field($_POST['search_students_by']) : '';

		$search_field   = isset($_POST['search_field']) ? sanitize_text_field($_POST['search_field']) : '';
		$search_keyword = isset($_POST['search_keyword']) ? sanitize_text_field($_POST['search_keyword']) : '';

		$class_id   = isset($_POST['class_id']) ? absint($_POST['class_id']) : 0;
		$section_id = isset($_POST['section_id']) ? absint($_POST['section_id']) : 0;
		$status     = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';

		try {
			ob_start();
			global $wpdb;

			// Start validation.
			$errors = array();

			if (!in_array($search_students_by, array('search_by_keyword', 'search_by_class', 'search_by_date'))) {
				throw new Exception(esc_html__('Please specify search criteria.', 'school-management'));
			}

			if ('search_by_keyword' === $search_students_by) {
				if (!empty($search_field) && empty($search_keyword)) {
					$errors['search_keyword'] = esc_html__('Please enter search keyword.', 'school-management');
				} else if (!empty($search_keyword) && empty($search_field)) {
					$errors['search_field'] = esc_html__('Please specify search field.', 'school-management');
				}

				$filter = array(
					'search_field'   => $search_field,
					'search_keyword' => $search_keyword,
				);
			} else if ('search_by_date' === $search_students_by) {
				$start_date = isset($_POST['start_date']) ? DateTime::createFromFormat(WLSM_Config::date_format(), sanitize_text_field($_POST['start_date'])) : NULL;
				$end_date = isset($_POST['end_date']) ? DateTime::createFromFormat(WLSM_Config::date_format(), sanitize_text_field($_POST['end_date'])) : NULL;

				if ($start_date) {
					$start_date = $start_date->format('Y-m-d');
				}
				if ($end_date) {
					$end_date = $end_date->format('Y-m-d');
				}

				if (empty($start_date)) {
					$errors['start_date'] = esc_html__('Please select date.', 'school-management');
				}
				if (empty($end_date)) {
					$errors['end_date'] = esc_html__('Please select date.', 'school-management');
				}

				$filter = array(
					'start_date' => $start_date,
					'end_date'   => $end_date,
				);
			} else {
				if (empty($class_id)) {
					$errors['class_id'] = esc_html__('Please select a class.', 'school-management');
				}

				$filter = array(
					'class_id'   => $class_id,
					'section_id' => $section_id,
					'status' => $status,
				);
			}
		} catch (Exception $exception) {
			if ($from_table) {
				echo json_encode($output);
				die();
			}
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		if (count($errors) < 1) {
			if (!$from_table) {
				wp_send_json_success();
			}
			try {
				$filter['search_by'] = $search_students_by;

				$page_url = WLSM_M_Staff_Accountant::get_invoices_page_url();

				$query = WLSM_M_Staff_Accountant::fetch_invoices_query($school_id, $session_id, $filter);

				$query_filter = $query;

				// Grouping.
				$group_by = ' ' . WLSM_M_Staff_Accountant::fetch_invoices_query_group_by();

				$query        .= $group_by;
				$query_filter .= $group_by;

				// Searching.
				$condition = '';
				if (isset($_POST['search']['value'])) {
					$search_value = sanitize_text_field($_POST['search']['value']);
					if ('' !== $search_value) {
						$condition .= '' .
							'(i.invoice_number LIKE "%' . $search_value . '%") OR ' .
							'(i.label LIKE "%' . $search_value . '%") OR ' .
							'(sr.name LIKE "%' . $search_value . '%") OR ' .
							'(sr.admission_number LIKE "%' . $search_value . '%") OR ' .
							'(sr.enrollment_number LIKE "%' . $search_value . '%") OR ' .
							'(sr.phone LIKE "%' . $search_value . '%") OR ' .
							'(c.label LIKE "%' . $search_value . '%") OR ' .
							'(se.label LIKE "%' . $search_value . '%")';

						$search_value_lowercase = strtolower($search_value);
						if (preg_match('/^paid$/', $search_value_lowercase)) {
							$status = WLSM_M_Invoice::get_paid_key();
						} else if (preg_match('/^unpa(|i|id)$/', $search_value_lowercase)) {
							$status = WLSM_M_Invoice::get_unpaid_key();
						} else if (preg_match('/^partially(| p| pa| pai| paid)$/', $search_value_lowercase)) {
							$status = WLSM_M_Invoice::get_partially_paid_key();
						}

						if (isset($status)) {
							$condition .= ' OR (i.status = "' . $status . '")';
						}

						$date_issued = DateTime::createFromFormat(WLSM_Config::date_format(), $search_value);

						if ($date_issued) {
							$format_date_issued = 'Y-m-d';
						} else {
							if ('d-m-Y' === WLSM_Config::date_format()) {
								if (!$date_issued) {
									$date_issued        = DateTime::createFromFormat('m-Y', $search_value);
									$format_date_issued = 'Y-m';
								}
							} else if ('d/m/Y' === WLSM_Config::date_format()) {
								if (!$date_issued) {
									$date_issued        = DateTime::createFromFormat('m/Y', $search_value);
									$format_date_issued = 'Y-m';
								}
							} else if ('Y-m-d' === WLSM_Config::date_format()) {
								if (!$date_issued) {
									$date_issued        = DateTime::createFromFormat('Y-m', $search_value);
									$format_date_issued = 'Y-m';
								}
							} else if ('Y/m/d' === WLSM_Config::date_format()) {
								if (!$date_issued) {
									$date_issued        = DateTime::createFromFormat('Y/m', $search_value);
									$format_date_issued = 'Y-m';
								}
							}

							if (!$date_issued) {
								$date_issued        = DateTime::createFromFormat('Y', $search_value);
								$format_date_issued = 'Y';
							}
						}

						if ($date_issued && isset($format_date_issued)) {
							$date_issued = $date_issued->format($format_date_issued);
							$date_issued = ' OR (i.date_issued LIKE "%' . $date_issued . '%")';

							$condition .= $date_issued;
						}

						$due_date = DateTime::createFromFormat(WLSM_Config::date_format(), $search_value);

						if ($due_date) {
							$format_due_date = 'Y-m-d';
						} else {
							if ('d-m-Y' === WLSM_Config::date_format()) {
								if (!$due_date) {
									$due_date        = DateTime::createFromFormat('m-Y', $search_value);
									$format_due_date = 'Y-m';
								}
							} else if ('d/m/Y' === WLSM_Config::date_format()) {
								if (!$due_date) {
									$due_date        = DateTime::createFromFormat('m/Y', $search_value);
									$format_due_date = 'Y-m';
								}
							} else if ('Y-m-d' === WLSM_Config::date_format()) {
								if (!$due_date) {
									$due_date        = DateTime::createFromFormat('Y-m', $search_value);
									$format_due_date = 'Y-m';
								}
							} else if ('Y/m/d' === WLSM_Config::date_format()) {
								if (!$due_date) {
									$due_date        = DateTime::createFromFormat('Y/m', $search_value);
									$format_due_date = 'Y-m';
								}
							}

							if (!$due_date) {
								$due_date        = DateTime::createFromFormat('Y', $search_value);
								$format_due_date = 'Y';
							}
						}

						if ($due_date && isset($format_due_date)) {
							$due_date = $due_date->format($format_due_date);
							$due_date = ' OR (i.due_date LIKE "%' . $due_date . '%")';

							$condition .= $due_date;
						}

						$query_filter .= (' HAVING ' . $condition);
					}
				}

				// Ordering.
				$columns = array('sr.name', 'sr.admission_number', 'i.invoice_number', 'i.label', 'payable', 'paid', 'due', 'i.status', 'i.date_issued', 'i.due_date', 'sr.phone', 'c.label', 'se.label', 'sr.enrollment_number');
				if (isset($_POST['order']) && isset($columns[$_POST['order']['0']['column']])) {
					$order_by  = sanitize_text_field($columns[$_POST['order']['0']['column']]);
					$order_dir = sanitize_text_field($_POST['order']['0']['dir']);

					$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
				} else {
					$query_filter .= ' ORDER BY i.ID DESC';
				}

				// Limiting.
				$limit = '';
				if (-1 != $_POST['length']) {
					$start  = absint($_POST['start']);
					$length = absint($_POST['length']);

					$limit  = ' LIMIT ' . $start . ', ' . $length;
				}

				// Total query.
				$rows_query = WLSM_M_Staff_Accountant::fetch_invoices_query_count($school_id, $session_id, $filter);

				// Total rows count.
				$total_rows_count = $wpdb->get_var($rows_query);

				// Filtered rows count.
				if ($condition) {
					$filter_rows_count = $wpdb->get_var($rows_query . ' AND (' . $condition . ')');
				} else {
					$filter_rows_count = $total_rows_count;
				}

				// Filtered limit rows.
				$filter_rows_limit = $wpdb->get_results($query_filter . $limit);

				$data = array();
				if (count($filter_rows_limit)) {
					foreach ($filter_rows_limit as $row) {
						$due_amount = max(0, $row->payable - $row->paid);
						$collect_payment = (WLSM_M_Invoice::get_paid_key() !== $row->status) ? '<br><a href="' . esc_url($page_url . '&action=collect_payment&id=' . $row->ID . '#wlsm-fee-invoice-status') . '" class="btn wlsm-btn-xs btn-success">' . esc_html__('Collect Payment', 'school-management') . '</a>' : '';


						$edit = ($can_edit_invoices) && $row->status !== 'paid' ? '&nbsp;&nbsp; <a class="text-primary" href="' . esc_url($page_url . "&action=save&id=" . $row->ID) . '"><span class="dashicons dashicons-edit"></span></a>' : '';

						if (current_user_can('administrator')) {
							$edit = ($can_edit_invoices)  ? '&nbsp;&nbsp; <a class="text-primary" href="' . esc_url($page_url . "&action=save&id=" . $row->ID) . '"><span class="dashicons dashicons-edit"></span></a>' : '';
						} else {
							$edit = ($can_edit_invoices) && $row->status !== 'paid' ? '&nbsp;&nbsp; <a class="text-primary" href="' . esc_url($page_url . "&action=save&id=" . $row->ID) . '"><span class="dashicons dashicons-edit"></span></a>' : '';
						}

						// Table columns.
						$data[] = array(
							'<input type="checkbox" class="wlsm-select-single wlsm-bulk-invoices" name="bulk_data[]" value="' . esc_attr($row->ID) . '">',
							esc_html(WLSM_M_Staff_Class::get_name_text($row->student_name)),
							esc_html(WLSM_M_Staff_Class::get_name_text($row->father_name)),
							esc_html(WLSM_M_Staff_Class::get_admission_no_text($row->admission_number)),
							esc_html($row->invoice_number),
							esc_html(WLSM_M_Staff_Accountant::get_invoice_title_text($row->invoice_title)),
							esc_html(WLSM_Config::get_money_text($row->payable, $school_id)),
							esc_html(WLSM_Config::get_money_text($row->paid, $school_id)),
							'<span class="wlsm-font-bold">' . esc_html(WLSM_Config::get_money_text($due_amount, $school_id)) . '</span>',
							wp_kses(
								WLSM_M_Invoice::get_status_text($row->status),
								array('span' => array('class' => array()))
							) . $collect_payment,
							esc_html(WLSM_Config::get_date_text($row->date_issued)),
							esc_html(WLSM_Config::get_date_text($row->due_date)),
							esc_html(WLSM_M_Staff_Class::get_phone_text($row->phone)),
							esc_html(WLSM_M_Class::get_label_text($row->class_label)),
							esc_html(WLSM_M_Staff_Class::get_section_label_text($row->section_label)),
							esc_html($row->enrollment_number),
							'<a class="text-success wlsm-print-invoice" data-nonce="' . esc_attr(wp_create_nonce('print-invoice-' . $row->ID)) . '" data-invoice="' . esc_attr($row->ID) . '" href="#" data-message-title="' . esc_attr__('Print Invoice', 'school-management') . '" data-close="' . esc_attr__('Close', 'school-management') . '"><i class="fas fa-print"></i></a>'.$edit . ($can_delete_invoices ? ('&nbsp;&nbsp;
							<a class="text-danger wlsm-delete-invoice" data-nonce="' . esc_attr(wp_create_nonce('delete-invoice-' . $row->ID)) . '" data-invoice="' . esc_attr($row->ID) . '" href="#" data-message-title="' . esc_attr__('Please Confirm!', 'school-management') . '" data-message-content="' . sprintf(esc_attr__('This will delete the invoice along with payment detail if invoice is paid', 'school-management'), esc_html(WLSM_M_Session::get_label_text($session_label))) . '" data-cancel="' . esc_attr__('Cancel', 'school-management') . '" data-submit="' . esc_attr__('Confirm', 'school-management') . '"><span class="dashicons dashicons-trash"></span></a>') : '')
						);
					}
				}

				$output = array(
					'draw'            => intval($_POST['draw']),
					'recordsTotal'    => $total_rows_count,
					'recordsFiltered' => $filter_rows_count,
					'data'            => $data,
					'export'          => array(
						'nonce'  => wp_create_nonce('export-staff-invoices-table'),
						'action' => 'wlsm-export-staff-invoices-table',
						'filter' => json_encode(
							array(
								'search_students_by' => $search_students_by,
								'search_field'       => $search_field,
								'search_keyword'     => $search_keyword,
								'class_id'           => $class_id,
								'section_id'         => $section_id,
								'status'         => $status,
							)
						)
					)
				);

				echo json_encode($output);
				die();
			} catch (Exception $exception) {
				if ($from_table) {
					echo json_encode($output);
					die();
				}
				wp_send_json_error($exception->getMessage());
			}
		}

		if ($from_table) {
			echo json_encode($output);
			die();
		}
		wp_send_json_error($errors);
	}

	public static function get_invoices_report_total() {
		$current_user = WLSM_M_Role::can('manage_invoices');

		if (!$current_user) {
			die();
		}

		$current_school = $current_user['school'];
		$school_id     = $current_user['school']['id'];
		$session_id    = $current_user['session']['ID'];
		$session_label = $current_user['session']['label'];

		// if (!wp_verify_nonce($_POST['nonce'], 'wlsm-get-fees-total')) {
		// 	die();
		// }

		$class_id   = isset($_POST['class_id']) ? absint($_POST['class_id']) : 0;
		$section_id = isset($_POST['section_id']) ? absint($_POST['section_id']) : 0;

		try {
			ob_start();
			global $wpdb;

			// get total fees paid and pending.
			$fees_total = WLSM_M_Staff_Accountant::get_invoices_report_total($school_id, $session_id, $class_id, $section_id);
			$fees = ['total_pending'=> $fees_total->due, 'total_paid'=> $fees_total->paid ];

			wp_send_json($fees);
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json(array());
		}

	}

	public static function get_invoices_report() {
		$current_user = WLSM_M_Role::can('manage_invoices');


		if (!$current_user) {
			die();
		}

		$current_school = $current_user['school'];

		$can_delete_invoices = WLSM_M_Role::check_permission(array('delete_invoices'), $current_school['permissions']);

		$school_id     = $current_user['school']['id'];
		$session_id    = $current_user['session']['ID'];
		$session_label = $current_user['session']['label'];

		if (!wp_verify_nonce($_POST['get-invoices-report'], 'get-invoices-report')) {
			die();
		}

		$from_table = isset($_POST['from_table']) ? (bool) ($_POST['from_table']) : 0;

		$output = array(
			'draw'            => 1,
			'recordsTotal'    => 0,
			'recordsFiltered' => 0,
			'data'            => array(),
		);

		$class_id   = isset($_POST['class_id']) ? absint($_POST['class_id']) : 0;
		$section_id = isset($_POST['section_id']) ? absint($_POST['section_id']) : 0;
		$status     = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';

		try {
			ob_start();
			global $wpdb;

			// Start validation.
			$errors = array();

			$filter = array(
				'class_id'   => $class_id,
				'section_id' => $section_id,
				'status'     => $status,
			);

		} catch (Exception $exception) {
			if ($from_table) {
				echo json_encode($output);
				die();
			}
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		if (count($errors) < 1) {
			if (!$from_table) {
				wp_send_json_success();
			}
			try {
				$filter['search_by'] = 'search_by_class';

				$page_url = WLSM_M_Staff_Accountant::get_invoices_page_url();

				$query = WLSM_M_Staff_Accountant::fetch_invoices_report($school_id, $session_id, $filter);

				$query_filter = $query;

				// Grouping.
				$group_by = ' ' . WLSM_M_Staff_Accountant::fetch_invoices_report_query_group_by();

				$query        .= $group_by;
				$query_filter .= $group_by;

				// Searching.
				$condition = '';
				if (isset($_POST['search']['value'])) {
					$search_value = sanitize_text_field($_POST['search']['value']);
					if ('' !== $search_value) {
						$condition .= '' .
							'(sr.name LIKE "%' . $search_value . '%") OR ' .
							'(sr.admission_number LIKE "%' . $search_value . '%") OR ' .
							'(sr.enrollment_number LIKE "%' . $search_value . '%") OR ' .
							'(sr.phone LIKE "%' . $search_value . '%") OR ' .
							'(c.label LIKE "%' . $search_value . '%") OR ' .
							'(se.label LIKE "%' . $search_value . '%")';

						$search_value_lowercase = strtolower($search_value);
						if (preg_match('/^paid$/', $search_value_lowercase)) {
							$status = WLSM_M_Invoice::get_paid_key();
						} else if (preg_match('/^unpa(|i|id)$/', $search_value_lowercase)) {
							$status = WLSM_M_Invoice::get_unpaid_key();
						} else if (preg_match('/^partially(| p| pa| pai| paid)$/', $search_value_lowercase)) {
							$status = WLSM_M_Invoice::get_partially_paid_key();
						}

						if (isset($status)) {
							$condition .= ' OR (i.status = "' . $status . '")';
						}

						$date_issued = DateTime::createFromFormat(WLSM_Config::date_format(), $search_value);

						if ($date_issued) {
							$format_date_issued = 'Y-m-d';
						} else {
							if ('d-m-Y' === WLSM_Config::date_format()) {
								if (!$date_issued) {
									$date_issued        = DateTime::createFromFormat('m-Y', $search_value);
									$format_date_issued = 'Y-m';
								}
							} else if ('d/m/Y' === WLSM_Config::date_format()) {
								if (!$date_issued) {
									$date_issued        = DateTime::createFromFormat('m/Y', $search_value);
									$format_date_issued = 'Y-m';
								}
							} else if ('Y-m-d' === WLSM_Config::date_format()) {
								if (!$date_issued) {
									$date_issued        = DateTime::createFromFormat('Y-m', $search_value);
									$format_date_issued = 'Y-m';
								}
							} else if ('Y/m/d' === WLSM_Config::date_format()) {
								if (!$date_issued) {
									$date_issued        = DateTime::createFromFormat('Y/m', $search_value);
									$format_date_issued = 'Y-m';
								}
							}

							if (!$date_issued) {
								$date_issued        = DateTime::createFromFormat('Y', $search_value);
								$format_date_issued = 'Y';
							}
						}

						if ($date_issued && isset($format_date_issued)) {
							$date_issued = $date_issued->format($format_date_issued);
							$date_issued = ' OR (i.date_issued LIKE "%' . $date_issued . '%")';

							$condition .= $date_issued;
						}

						$due_date = DateTime::createFromFormat(WLSM_Config::date_format(), $search_value);

						if ($due_date) {
							$format_due_date = 'Y-m-d';
						} else {
							if ('d-m-Y' === WLSM_Config::date_format()) {
								if (!$due_date) {
									$due_date        = DateTime::createFromFormat('m-Y', $search_value);
									$format_due_date = 'Y-m';
								}
							} else if ('d/m/Y' === WLSM_Config::date_format()) {
								if (!$due_date) {
									$due_date        = DateTime::createFromFormat('m/Y', $search_value);
									$format_due_date = 'Y-m';
								}
							} else if ('Y-m-d' === WLSM_Config::date_format()) {
								if (!$due_date) {
									$due_date        = DateTime::createFromFormat('Y-m', $search_value);
									$format_due_date = 'Y-m';
								}
							} else if ('Y/m/d' === WLSM_Config::date_format()) {
								if (!$due_date) {
									$due_date        = DateTime::createFromFormat('Y/m', $search_value);
									$format_due_date = 'Y-m';
								}
							}

							if (!$due_date) {
								$due_date        = DateTime::createFromFormat('Y', $search_value);
								$format_due_date = 'Y';
							}
						}

						if ($due_date && isset($format_due_date)) {
							$due_date = $due_date->format($format_due_date);
							$due_date = ' OR (i.due_date LIKE "%' . $due_date . '%")';

							$condition .= $due_date;
						}

						$query_filter .= (' HAVING ' . $condition);
					}
				}

				// Ordering.
				$columns = array('sr.name', 'sr.admission_number', 'i.invoice_number', 'i.label', 'payable', 'paid', 'due', 'i.status', 'i.date_issued', 'i.due_date', 'sr.phone', 'c.label', 'se.label', 'sr.enrollment_number');
				if (isset($_POST['order']) && isset($columns[$_POST['order']['0']['column']])) {
					$order_by  = sanitize_text_field($columns[$_POST['order']['0']['column']]);
					$order_dir = sanitize_text_field($_POST['order']['0']['dir']);

					$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
				} else {
					$query_filter .= ' ORDER BY sr.ID DESC';
				}

				// Limiting.
				$limit = '';
				if (-1 != $_POST['length']) {
					$start  = absint($_POST['start']);
					$length = absint($_POST['length']);

					$limit  = ' LIMIT ' . $start . ', ' . $length;
				}

				// Total query.
				$rows_query = WLSM_M_Staff_Accountant::fetch_invoices_query_count($school_id, $session_id, $filter);

				// Total rows count.
				$total_rows_count = $wpdb->get_var($rows_query);

				// Filtered rows count.
				if ($condition) {
					$filter_rows_count = $wpdb->get_var($rows_query . ' AND (' . $condition . ')');
				} else {
					$filter_rows_count = $total_rows_count;
				}

				// Filtered limit rows.
				$filter_rows_limit = $wpdb->get_results($query_filter . $limit);

				$data = array();
				if (count($filter_rows_limit)) {
					foreach ($filter_rows_limit as $row) {
						$due = $row->payable - $row->paid;
							if($due>0){
								$due_amount = $due;
							}else {
								$due_amount = 0;
							}
						if (WLSM_M_Invoice::get_paid_key() !== $row->status) {
							$collect_payment = '<br><a href="' . esc_url($page_url . '&action=collect_payment&id=' . $row->ID . '#wlsm-fee-invoice-status') . '" class="btn wlsm-btn-xs btn-success">' . esc_html__('Collect Payment', 'school-management') . '</a>';
						} else {
							$collect_payment = '';
						}

						// Table columns.
						$data[] = array(
							esc_html(WLSM_M_Staff_Class::get_name_text($row->student_name)),
							esc_html($row->enrollment_number),
							esc_html(WLSM_M_Staff_Class::get_name_text($row->father_name)),
							esc_html(WLSM_M_Staff_Class::get_admission_no_text($row->admission_number)),
							esc_html(WLSM_Config::get_money_text($row->payable, $school_id)),
							esc_html(WLSM_Config::get_money_text($row->paid, $school_id)),
							'<span class="wlsm-font-bold">' . esc_html(WLSM_Config::get_money_text($due_amount, $school_id)) . '</span>',
							esc_html(WLSM_M_Staff_Class::get_phone_text($row->phone)),
							esc_html(WLSM_M_Class::get_label_text($row->class_label)),
							esc_html(WLSM_M_Staff_Class::get_section_label_text($row->section_label)),
							''
						);
					}
				}

				$output = array(
					'draw'            => intval($_POST['draw']),
					'recordsTotal'    => $total_rows_count,
					'recordsFiltered' => $filter_rows_count,
					'data'            => $data,
					'export'          => array(
						'nonce'  => wp_create_nonce('export-staff-invoices-table'),
						'action' => 'wlsm-export-staff-invoices-table',
						'filter' => json_encode(
							array(
								'class_id'           => $class_id,
								'section_id'         => $section_id,
								'status'             => $status,
							)
						)
					)
				);

				echo json_encode($output);
				die();
			} catch (Exception $exception) {
				if ($from_table) {
					echo json_encode($output);
					die();
				}
				wp_send_json_error($exception->getMessage());
			}
		}

		if ($from_table) {
			echo json_encode($output);
			die();
		}
		wp_send_json_error($errors);
	}

	public static function save_invoice() {
		$current_user = WLSM_M_Role::can('manage_invoices');

		if (!$current_user) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$invoice_id = isset($_POST['invoice_id']) ? absint($_POST['invoice_id']) : 0;

			if ($invoice_id) {
				if (!wp_verify_nonce($_POST['edit-invoice-' . $invoice_id], 'edit-invoice-' . $invoice_id)) {
					die();
				}
			} else {
				if (!wp_verify_nonce($_POST['add-invoice'], 'add-invoice')) {
					die();
				}
			}

			// Checks if invoice exists.
			if ($invoice_id) {
				$invoice = WLSM_M_Staff_Accountant::fetch_invoice($school_id, $session_id, $invoice_id);

				if (!$invoice) {
					throw new Exception(esc_html__('Invoice not found.', 'school-management'));
				}

				if (!current_user_can('administrator') && $invoice->status === WLSM_M_Invoice::get_paid_key()) {
					throw new Exception(esc_html__('Unable to update because (Invoice Is Paid)', 'school-management'));
				}
			}

			$invoice_title       = isset($_POST['invoice_label']) ? sanitize_text_field($_POST['invoice_label']) : '';
			$invoice_description = isset($_POST['invoice_description']) ? sanitize_text_field($_POST['invoice_description']) : '';
			$invoice_amount      = isset($_POST['invoice_amount']) ? WLSM_Config::sanitize_money($_POST['invoice_amount']) : 0;
			$invoice_discount    = isset($_POST['invoice_discount']) ? WLSM_Config::sanitize_money($_POST['invoice_discount']) : 0;
			$invoice_amount_total    = isset($_POST['invoice_amount_total']) ? WLSM_Config::sanitize_money($_POST['invoice_amount_total']) : 0;
			$invoice_date_issued = isset($_POST['invoice_date_issued']) ? DateTime::createFromFormat(WLSM_Config::date_format(), sanitize_text_field($_POST['invoice_date_issued'])) : NULL;
			$invoice_due_date    = isset($_POST['invoice_due_date']) ? DateTime::createFromFormat(WLSM_Config::date_format(), sanitize_text_field($_POST['invoice_due_date'])) : NULL;
			$partial_payment     = isset($_POST['partial_payment']) ? (bool) $_POST['partial_payment'] : 0;

			$due_date_amount = isset($_POST['due_date_amount']) ? WLSM_Config::sanitize_money($_POST['due_date_amount']) : 0;
			$due_date_period = isset($_POST['due_date_period']) ? sanitize_text_field($_POST['due_date_period']) : '';


			// Fees.
			$fee_id     = (isset($_POST['fee_id']) && is_array($_POST['fee_id'])) ? $_POST['fee_id'] : array();
			$fee_label  = (isset($_POST['fee_label']) && is_array($_POST['fee_label'])) ? $_POST['fee_label'] : array();
			$fee_period = (isset($_POST['fee_period']) && is_array($_POST['fee_period'])) ? $_POST['fee_period'] : array();
			$fee_amount = (isset($_POST['fee_amount']) && is_array($_POST['fee_amount'])) ? $_POST['fee_amount'] : array();

			if (!$invoice_id) {
				$invoice_type = isset($_POST['invoice_type']) ? sanitize_text_field($_POST['invoice_type']) : '';
			}

			// Start validation.
			$errors = array();

			if (empty($invoice_title)) {
				$errors['invoice_label'] = esc_html__('Please provide invoice title.', 'school-management');
			} else {
				if (strlen($invoice_title) > 50) {
					$errors['invoice_label'] = esc_html__('Maximum length cannot exceed 100 characters.', 'school-management');
				}
			}

			if ($partial_payment && $due_date_amount) {
				$errors['due_date_amount'] = esc_html__('You can not have Due Date Amount in Partial Payment.', 'school-management');
			}

			if ($invoice_date_issued > $invoice_due_date) {
				$errors['invoice_due_date'] = esc_html__('Invoice due date must be greater than issued date.', 'school-management');
			}

			if (empty($invoice_date_issued)) {
				$errors['invoice_date_issued'] = esc_html__('Please provide date issued.', 'school-management');
			} else {
				$invoice_date_issued = $invoice_date_issued->format('Y-m-d');
			}

			if (empty($invoice_due_date)) {
				$invoice_due_date = NULL;
			} else {
				$invoice_due_date = $invoice_due_date->format('Y-m-d');
			}

			if (!$invoice_id) {
				if (!in_array($invoice_type, array('single_invoice', 'bulk_invoice', 'single_invoice_fee_type'))) {
					throw new Exception(esc_html__('Please select either single invoice or bulk invoice option.', 'school-management'));
				}

				if ('single_invoice' === $invoice_type) {
					$student_id = isset($_POST['student']) ? absint($_POST['student']) : 0;

					$collect_invoice_payment = isset($_POST['collect_invoice_payment']) ? (bool) $_POST['collect_invoice_payment'] : 0;

					if (empty($student_id)) {
						$errors['student'] = esc_html__('Please select a student.', 'school-management');
						wp_send_json_error($errors);
					}

					// Checks if student exists.
					$student = WLSM_M_Staff_General::get_student($school_id, $session_id, $student_id, true, true);

					if (!$student) {
						throw new Exception(esc_html__('Student not found.', 'school-management'));
					}

					if ($collect_invoice_payment) {
						$payment_amount = isset($_POST['payment_amount']) ? WLSM_Config::sanitize_money($_POST['payment_amount']) : 0;
						$payment_method = isset($_POST['payment_method']) ? sanitize_text_field($_POST['payment_method']) : '';
						$transaction_id = isset($_POST['transaction_id']) ? sanitize_text_field($_POST['transaction_id']) : '';
						$payment_note   = isset($_POST['payment_note']) ? sanitize_text_field($_POST['payment_note']) : '';

						$due = WLSM_M_Invoice::get_due_amount(
							array(
								'total'    => $invoice_amount,
								'discount' => $invoice_discount,
							)
						);

						$errors = self::validate_invoice_payment($errors, $partial_payment, $due, $payment_amount, $payment_method);
					}
				} else if ('single_invoice_fee_type' === $invoice_type) {
					$student_id = isset($_POST['student']) ? absint($_POST['student']) : 0;

					$collect_invoice_payment = isset($_POST['collect_invoice_payment']) ? (bool) $_POST['collect_invoice_payment'] : 0;

					if (empty($student_id)) {
						$errors['student'] = esc_html__('Please select a student.', 'school-management');
						wp_send_json_error($errors);
					}

					// Checks if student exists.
					$student = WLSM_M_Staff_General::get_student($school_id, $session_id, $student_id, true, true);

					if (!$student) {
						throw new Exception(esc_html__('Student not found.', 'school-management'));
					}

					// Student fees.
					if (count($fee_label)) {
						if (1 !== count(array_unique(array(count($fee_label), count($fee_period), count($fee_amount))))) {
							wp_send_json_error(esc_html__('Invalid fees.', 'school-management'));
						} elseif (count($fee_label) !== count(array_unique($fee_label))) {
							wp_send_json_error(esc_html__('Fee type must be different.', 'school-management'));
						} else {
							foreach ($fee_label as $key => $value) {
								$fee_id    [$id]  = sanitize_text_field($fee_id[$id]);
								$fee_label [$key] = sanitize_text_field($fee_label[$key]);
								$fee_period[$key] = sanitize_text_field($fee_period[$key]);
								$fee_amount[$key] = WLSM_Config::sanitize_money($fee_amount[$key]);

								if (empty($fee_label[$key])) {
									wp_send_json_error(esc_html__('Please specify fee type.', 'school-management'));
								} elseif (strlen($fee_label[$key]) > 100) {
									wp_send_json_error(esc_html__('Maximum length cannot exceed 100 characters.', 'school-management'));
								}

								if (!in_array($fee_period[$key], array_keys(WLSM_Helper::fee_period_list()))) {
									wp_send_json_error(esc_html__('Please specify fee period.', 'school-management'));
								}

								if ($fee_amount[$key] < 0) {
									$fee_amount[$key] = 0;
								}
							}
						}
					}

					if ($collect_invoice_payment) {
						$payment_amount = isset($_POST['payment_amount']) ? WLSM_Config::sanitize_money($_POST['payment_amount']) : 0;
						$payment_method = isset($_POST['payment_method']) ? sanitize_text_field($_POST['payment_method']) : '';
						$transaction_id = isset($_POST['transaction_id']) ? sanitize_text_field($_POST['transaction_id']) : '';
						$payment_note   = isset($_POST['payment_note']) ? sanitize_text_field($_POST['payment_note']) : '';

						$due = WLSM_M_Invoice::get_due_amount(
							array(
								'total'    => $invoice_amount,
								'discount' => $invoice_discount,
							)
						);

						$errors = self::validate_invoice_payment($errors, $partial_payment, $due, $payment_amount, $payment_method);
					}
				} else {
					$student_ids = (isset($_POST['student']) && is_array($_POST['student'])) ? $_POST['student'] : array();

					if (!count($student_ids)) {
						$errors['student[]'] = esc_html__('Please select students.', 'school-management');
					}

					// Checks if students exists.
					$students_count = WLSM_M_Staff_General::get_students_count($school_id, $session_id, $student_ids, true, true);

					if ($students_count != count($student_ids)) {
						throw new Exception(esc_html__('Student(s) not found.', 'school-management'));
					}
				}
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		if (count($errors) < 1) {
			try {
				$wpdb->query('BEGIN;');

				// Invoice data.
				$invoice_data = array(
					'label'           => $invoice_title,
					'description'     => $invoice_description,
					'amount'          => $invoice_amount,
					'invoice_amount_total'            => $invoice_amount_total,
					'discount'        => $invoice_discount,
					'date_issued'     => $invoice_date_issued,
					'due_date'        => $invoice_due_date,
					'partial_payment' => $partial_payment,
					'due_date_amount' => $due_date_amount,
					'due_date_period' => $due_date_period,
				);

				// Checks if update or insert.
				if ($invoice_id) {
					$message = esc_html__('Invoice updated successfully.', 'school-management');
					$reset   = false;

					$invoice_data['updated_at'] = current_time('Y-m-d H:i:s');

					$success = $wpdb->update(WLSM_INVOICES, $invoice_data, array('ID' => $invoice_id));

					$buffer = ob_get_clean();
					if (!empty($buffer)) {
						throw new Exception($buffer);
					}

					WLSM_M_Staff_Accountant::refresh_invoice_status($invoice_id);
				} else {
					$message = esc_html__('Invoice added successfully.', 'school-management');
					$reset   = true;

					if ('bulk_invoice' === $invoice_type) {
						$bulk_invoice_ids = array();
						foreach ($student_ids as $student_id) {
							$invoice_number = WLSM_M_Invoice::get_invoice_number($school_id);

							$invoice_data['invoice_number']    = $invoice_number;
							$invoice_data['student_record_id'] = $student_id;

							$invoice_data['added_by'] = get_current_user_id();

							$invoice_data['created_at'] = current_time('Y-m-d H:i:s');

							$success = $wpdb->insert(WLSM_INVOICES, $invoice_data);

							$bulk_invoice_id = $wpdb->insert_id;
							array_push($bulk_invoice_ids, $bulk_invoice_id);

							$buffer = ob_get_clean();
							if (!empty($buffer)) {
								throw new Exception($buffer);
							}
						}
					} else if (('single_invoice' === $invoice_type)) {
						$invoice_number = WLSM_M_Invoice::get_invoice_number($school_id);

						$invoice_data['invoice_number']    = $invoice_number;
						$invoice_data['student_record_id'] = $student_id;

						$invoice_data['added_by'] = get_current_user_id();

						$invoice_data['created_at'] = current_time('Y-m-d H:i:s');

						$success = $wpdb->insert(WLSM_INVOICES, $invoice_data);

						$single_invoice_id = $wpdb->insert_id;

						if ($collect_invoice_payment) {
							$invoice_id = $wpdb->insert_id;

							$receipt_number = WLSM_M_Invoice::get_receipt_number($school_id);

							?><?php
							// Payment data.
							$payment_data = array(
								'receipt_number'    => $receipt_number,
								'amount'            => $payment_amount,
								'payment_method'    => $payment_method,
								'transaction_id'    => $transaction_id,
								'note'              => $payment_note,
								'invoice_label'     => $invoice_title,
								'invoice_payable'   => $due,
								'student_record_id' => $student_id,
								'invoice_id'        => $invoice_id,
								'school_id'         => $school_id,
							);

							$payment_data['added_by'] = get_current_user_id();

							$payment_data['created_at'] = current_time('Y-m-d H:i:s');

							$success = $wpdb->insert(WLSM_PAYMENTS, $payment_data);

							$new_payment_id = $wpdb->insert_id;

							$buffer = ob_get_clean();
							if (!empty($buffer)) {
								throw new Exception($buffer);
							}

							WLSM_M_Staff_Accountant::refresh_invoice_status($invoice_id);
						}
					} else if (('single_invoice_fee_type' === $invoice_type)) {
						// Fees.
					$place_holders_fee_labels = array();
					$list_data = array();
					$fee_order = 10;
					foreach ($fee_label as $key => $value) {
						array_push($place_holders_fee_labels, '%s');
						$fee_order++;

						// Student fee data.
						$student_fee_data = array(
							'id'        => $fee_id[$key],
							'amount'    => $fee_amount[$key],
							'period'    => $fee_period[$key],
							'label'     => $fee_label[$key],
							'fee_order' => $fee_order,
						);
							// Invoice data.
							$fee_data = array(
								'label'           => $student_fee_data['label'],
								'period'          => $student_fee_data['period'],
								'amount'          => $student_fee_data['amount'],
								'partial_payment' => 0,
							);

							array_push($list_data, $fee_data );
						}

						$list_data_fee = serialize($list_data);

						$invoice_data['fee_list'] = $list_data_fee;
						$invoice_number = WLSM_M_Invoice::get_invoice_number($school_id);
						$invoice_data['invoice_number']    = $invoice_number;
						$invoice_data['student_record_id'] = $student_id;
						$invoice_data['added_by'] = get_current_user_id();
						$invoice_data['created_at'] = current_time('Y-m-d H:i:s');

						// Invoice data.
						$success = $wpdb->insert(WLSM_INVOICES, $invoice_data);
						$single_invoice_id = $wpdb->insert_id;


					}
				}

				$buffer = ob_get_clean();
				if (!empty($buffer)) {
					throw new Exception($buffer);
				}

				if (false === $success) {
					throw new Exception($wpdb->last_error);
				}

				$wpdb->query('COMMIT;');

				if (isset($bulk_invoice_ids) && count($bulk_invoice_ids) > 0) {
					foreach ($bulk_invoice_ids as $bulk_invoice_id) {
						// Notify for invoice generated.
						$data = array(
							'school_id'  => $school_id,
							'session_id' => $session_id,
							'invoice_id' => $bulk_invoice_id,
						);

						wp_schedule_single_event(time() + 30, 'wlsm_notify_for_invoice_generated', $data);
						wp_schedule_single_event(time() + 30, 'wlsm_notify_for_invoice_generated_to_parent', $data);
					}
				} else if (isset($single_invoice_id)) {
					// Notify for invoice generated.
					$data = array(
						'school_id'  => $school_id,
						'session_id' => $session_id,
						'invoice_id' => $single_invoice_id,
					);

					wp_schedule_single_event(time() + 30, 'wlsm_notify_for_invoice_generated', $data);
					wp_schedule_single_event(time() + 30, 'wlsm_notify_for_invoice_generated_to_parent', $data);
				}

				if (isset($new_payment_id)) {
					// Notify for offline fee submission.
					$data = array(
						'school_id'  => $school_id,
						'session_id' => $session_id,
						'payment_id' => $new_payment_id,
					);

					wp_schedule_single_event(time() + 30, 'wlsm_notify_for_offline_fee_submission', $data);
					wp_schedule_single_event(time() + 30, 'wlsm_notify_for_offline_fee_submission_to_parent', $data);
				}

				wp_send_json_success(array('message' => $message, 'reset' => $reset));
			} catch (Exception $exception) {
				$wpdb->query('ROLLBACK;');
				wp_send_json_error($exception->getMessage());
			}
		}
		wp_send_json_error($errors);
	}

	public static function delete_invoice() {
		$current_user = WLSM_M_Role::can('delete_invoices');

		if (!$current_user) {
			die();
		}
		WLSM_Helper::check_demo();

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$invoice_id = isset($_POST['invoice_id']) ? absint($_POST['invoice_id']) : 0;

			if (!wp_verify_nonce($_POST['delete-invoice-' . $invoice_id], 'delete-invoice-' . $invoice_id)) {
				die();
			}

			// Checks if invoice exists.
			$invoice = WLSM_M_Staff_Accountant::get_invoice($school_id, $session_id, $invoice_id);

			if (!$invoice) {
				throw new Exception(esc_html__('Invoice not found.', 'school-management'));
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		try {
			$wpdb->query('BEGIN;');

			$success = $wpdb->delete(WLSM_INVOICES, array('ID' => $invoice_id));
			$success = $wpdb->delete(WLSM_PAYMENTS, array('ID' => $invoice->payment_id));
			$message = esc_html__('Invoice deleted successfully.', 'school-management');

			$exception = ob_get_clean();
			if (!empty($exception)) {
				throw new Exception($exception);
			}

			if (false === $success) {
				throw new Exception($wpdb->last_error);
			}

			$wpdb->query('COMMIT;');

			wp_send_json_success(array('message' => $message));
		} catch (Exception $exception) {
			$wpdb->query('ROLLBACK;');
			wp_send_json_error($exception->getMessage());
		}
	}

	public static function print_invoice() {
		$current_user = WLSM_M_Role::can('manage_invoices');

		if (!$current_user) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$invoice_id = isset($_POST['invoice_id']) ? absint($_POST['invoice_id']) : 0;

			if (!wp_verify_nonce($_POST['print-invoice-' . $invoice_id], 'print-invoice-' . $invoice_id)) {
				die();
			}

			// Checks if invoice exists.
			$invoice = WLSM_M_Staff_Accountant::fetch_invoice($school_id, $session_id, $invoice_id);

			if (!$invoice) {
				throw new Exception(esc_html__('Invoice not found.', 'school-management'));
			}

			$payments = WLSM_M_Staff_Accountant::get_invoice_payments($invoice_id);
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		ob_start();
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/invoice.php';

		$html = ob_get_clean();

		wp_send_json_success(array('html' => $html));
	}

	public static function print_bulk_invoices() {

		$current_user = WLSM_M_Role::can('manage_invoices');

		if (!$current_user) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		$class_id   = isset($_POST['class_id']) ? absint($_POST['class_id']) : 0;
		$section_id = isset($_POST['section_id']) ? absint($_POST['section_id']) : 0;
		$is_paid    = isset($_POST['paid']) ? sanitize_text_field($_POST['paid']) : null;

		try {
			ob_start();
			global $wpdb;

			// Start validation.
			$errors = array();

			if (empty($class_id)) {
				$errors['class_id'] = esc_html__('Please select a class.', 'school-management');
			} else {
				// Checks if class exists in the school.
				$class_school = WLSM_M_Staff_Class::get_class($school_id, $class_id);

				if (!$class_school) {
					$errors['class_id'] = esc_html__('Class not found.', 'school-management');
				} else {
					$class_school_id = $class_school->ID;

					if ($section_id) {
						$section = WLSM_M_Staff_Class::fetch_section($school_id, $section_id, $class_school_id);
						if (!$section) {
							$errors['section_id'] = esc_html__('Section not found.', 'school-management');
						} else {
							$section_label = WLSM_M_Staff_Class::get_section_label_text($section->label);
						}
					} else {
						$section_label = esc_html__('All', 'school-management');
					}

					$class       = WLSM_M_Class::fetch_class($class_id);
					$class_label = WLSM_M_Class::get_label_text($class->label);
				}
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		if (count($errors) < 1) {

			$query = WLSM_M_Staff_Accountant::fetch_bulk_invoices($school_id, $session_id, $class_id);

			if ($section_id) {
				$query .= " AND sr.section_id = $section_id";
			}

			if($is_paid == 'unpaid'){
				$query .= " AND i.status = 'unpaid'";
			} elseif ( $is_paid == 'partially_paid') {
				$query .= " AND i.status = 'partially_paid'";
			} else {
				$query .= " AND i.status = 'paid'";
			}

			$invoices = $wpdb->get_results($query);
			ob_start();

			if (!empty($invoices) ) {
				require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/bulk_invoices.php';
			}

			$html = ob_get_clean();

			if (empty($invoices) ) {
				$json = json_encode(array(
					'message_title' => esc_html__('No Invoices Found.', 'school-management'),
				));
			} else {
				$json = json_encode(array(
					'message_title' => esc_html__('Print Invoices Cards', 'school-management'),
				));
			}

			wp_send_json_success(array('html' => $html, 'json' => $json));
		}

		wp_send_json_error($errors);
	}

	public static function print_invoice_fee_structure() {
		$current_user = WLSM_M_Role::can('manage_invoices');

		if (!$current_user) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$student_id = isset($_POST['student_id']) ? absint($_POST['student_id']) : 0;

			if (!wp_verify_nonce($_POST['print-invoice-fee-structure'], 'print-invoice-fee-structure')) {
				die();
			}

			// Checks if student exists.
			$student = WLSM_M_Staff_General::fetch_student($school_id, $session_id, $student_id);

			if (!$student) {
				throw new Exception(esc_html__('Student not found.', 'school-management'));
			}

			$fee_structure = WLSM_M_Staff_Accountant::fetch_student_assigned_fees($school_id, $student_id);


			$fees     = WLSM_M_Staff_Accountant::fetch_student_fees($school_id, $student_id);
			$invoices = WLSM_M_Staff_Accountant::get_student_invoices($student_id);
			$payments = WLSM_M_Staff_Accountant::get_student_payments($student_id);
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		ob_start();
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/fee_structure.php';
		require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/student_invoices.php';
		require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/student_payments.php';
		$html = ob_get_clean();

		wp_send_json_success(array('html' => $html));
	}

	public static function invoice_fee_auto_generate() {
		// Get the current active school and session id
		$auto_invoice_generate= unserialize(get_option('auto_invoice_generate'));

		$school_id  = $auto_invoice_generate['school_id'];
		$session_id = $auto_invoice_generate['session_id'];

		if ($school_id) {
			$settings_general = WLSM_M_Setting::get_settings_general( $school_id );
			$school_invoice_auto                = $settings_general['invoice_auto'];
		}

		try {
			ob_start();
			global $wpdb;
			$period = 'monthly';
			if ($school_invoice_auto === true) {
				$invoices = WLSM_M_Staff_General::fetch_invoices($school_id, $session_id, $period);
			}

		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		try {
			$wpdb->query('BEGIN;');

			foreach ($invoices as $invoice) {
				// check if session end date is greater then current date
				if (current_time('Y-m-d') < $invoice->end_date) {

					$invoice_data = array(
						'description'          => 'Auto generated monthly invoice',
						'label'                => $invoice->label,
						'amount'               => $invoice->amount,
						'invoice_amount_total' => $invoice->amount,
						'date_issued'          => current_time('Y-m-d H:i:s'),
					);

					$invoice_number = WLSM_M_Invoice::get_invoice_number($school_id);

					$invoice_data['invoice_number']    = $invoice_number;
					$invoice_data['student_record_id'] = $invoice->student_record_id;

					$invoice_data['added_by'] = null;

					$invoice_data['created_at'] = current_time('Y-m-d H:i:s');
				}
				$success = $wpdb->insert(WLSM_INVOICES, $invoice_data);
			}
			$message = esc_html__('Invoice created successfully.', 'school-management');

			$exception = ob_get_clean();
			if (!empty($exception)) {
				throw new Exception($exception);
			}

			if (false === $success) {
				throw new Exception($wpdb->last_error);
			}

			$wpdb->query('COMMIT;');

			wp_send_json_success(array('message' => $message));
		} catch (Exception $exception) {
			$wpdb->query('ROLLBACK;');
			wp_send_json_error($exception->getMessage());
		}
	}

	public static function wlsm_three_month() {
		// Get the current active school and session id
		$auto_invoice_generate= unserialize(get_option('auto_invoice_generate'));

		$school_id  = $auto_invoice_generate['school_id'];
		$session_id = $auto_invoice_generate['session_id'];
		if ($school_id) {
			$settings_general = WLSM_M_Setting::get_settings_general( $school_id );
			$school_invoice_auto                = $settings_general['invoice_auto'];
		}

		try {
			ob_start();
			global $wpdb;
			$period = 'quarterly';
			if ($school_invoice_auto === true) {
				$invoices = WLSM_M_Staff_General::fetch_invoices($school_id, $session_id, $period);
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		try {
			$wpdb->query('BEGIN;');

			foreach ($invoices as $invoice) {
				// check if session end date is greater then current date
				if (current_time('Y-m-d') < $invoice->end_date) {

					$invoice_data = array(
						'description'          => 'Auto generated quarterly invoice',
						'label'                => $invoice->label,
						'amount'               => $invoice->amount,
						'invoice_amount_total' => $invoice->amount,
						'date_issued'          => current_time('Y-m-d H:i:s'),
					);

					$invoice_number = WLSM_M_Invoice::get_invoice_number($school_id);

					$invoice_data['invoice_number']    = $invoice_number;
					$invoice_data['student_record_id'] = $invoice->student_record_id;

					$invoice_data['added_by'] = null;

					$invoice_data['created_at'] = current_time('Y-m-d H:i:s');
				}
				$success = $wpdb->insert(WLSM_INVOICES, $invoice_data);
			}
			$message = esc_html__('Invoice created successfully.', 'school-management');

			$exception = ob_get_clean();
			if (!empty($exception)) {
				throw new Exception($exception);
			}

			if (false === $success) {
				throw new Exception($wpdb->last_error);
			}

			$wpdb->query('COMMIT;');

			wp_send_json_success(array('message' => $message));
		} catch (Exception $exception) {
			$wpdb->query('ROLLBACK;');
			wp_send_json_error($exception->getMessage());
		}
	}

	public static function wlsm_half_yearly() {
		// Get the current active school and session id
		$auto_invoice_generate= unserialize(get_option('auto_invoice_generate'));

		$school_id  = $auto_invoice_generate['school_id'];
		$session_id = $auto_invoice_generate['session_id'];
		if ($school_id) {
			$settings_general = WLSM_M_Setting::get_settings_general( $school_id );
			$school_invoice_auto                = $settings_general['invoice_auto'];
		}

		try {
			ob_start();
			global $wpdb;
			$period = 'half-yearly';
			if ($school_invoice_auto === true) {
				$invoices = WLSM_M_Staff_General::fetch_invoices($school_id, $session_id, $period);
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		try {
			$wpdb->query('BEGIN;');

			foreach ($invoices as $invoice) {
				// check if session end date is greater then current date
				if (current_time('Y-m-d') < $invoice->end_date) {

					$invoice_data = array(
						'description'          => 'Auto generated half-yearly invoice',
						'label'                => $invoice->label,
						'amount'               => $invoice->amount,
						'invoice_amount_total' => $invoice->amount,
						'date_issued'          => current_time('Y-m-d H:i:s'),
					);

					$invoice_number = WLSM_M_Invoice::get_invoice_number($school_id);

					$invoice_data['invoice_number']    = $invoice_number;
					$invoice_data['student_record_id'] = $invoice->student_record_id;

					$invoice_data['added_by'] = null;

					$invoice_data['created_at'] = current_time('Y-m-d H:i:s');
				}
				$success = $wpdb->insert(WLSM_INVOICES, $invoice_data);
			}
			$message = esc_html__('Invoice created successfully.', 'school-management');

			$exception = ob_get_clean();
			if (!empty($exception)) {
				throw new Exception($exception);
			}

			if (false === $success) {
				throw new Exception($wpdb->last_error);
			}

			$wpdb->query('COMMIT;');

			wp_send_json_success(array('message' => $message));
		} catch (Exception $exception) {
			$wpdb->query('ROLLBACK;');
			wp_send_json_error($exception->getMessage());
		}
	}

	public static function wlsm_annually() {
		// Get the current active school and session id
		$auto_invoice_generate= unserialize(get_option('auto_invoice_generate'));

		$school_id  = $auto_invoice_generate['school_id'];
		$session_id = $auto_invoice_generate['session_id'];
		if ($school_id) {
			$settings_general = WLSM_M_Setting::get_settings_general( $school_id );
			$school_invoice_auto                = $settings_general['invoice_auto'];
		}

		try {
			ob_start();
			global $wpdb;
			$period = 'annually';
			if ($school_invoice_auto === true) {
				$invoices = WLSM_M_Staff_General::fetch_invoices($school_id, $session_id, $period);
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		try {
			$wpdb->query('BEGIN;');

			foreach ($invoices as $invoice) {
				// check if session end date is greater then current date
				if (current_time('Y-m-d') < $invoice->end_date) {

					$invoice_data = array(
						'description'          => 'Auto generated annually invoice',
						'label'                => $invoice->label,
						'amount'               => $invoice->amount,
						'invoice_amount_total' => $invoice->amount,
						'date_issued'          => current_time('Y-m-d H:i:s'),
					);

					$invoice_number = WLSM_M_Invoice::get_invoice_number($school_id);

					$invoice_data['invoice_number']    = $invoice_number;
					$invoice_data['student_record_id'] = $invoice->student_record_id;

					$invoice_data['added_by'] = null;

					$invoice_data['created_at'] = current_time('Y-m-d H:i:s');
				}
				$success = $wpdb->insert(WLSM_INVOICES, $invoice_data);
			}
			$message = esc_html__('Invoice created successfully.', 'school-management');

			$exception = ob_get_clean();
			if (!empty($exception)) {
				throw new Exception($exception);
			}

			if (false === $success) {
				throw new Exception($wpdb->last_error);
			}

			$wpdb->query('COMMIT;');

			wp_send_json_success(array('message' => $message));
		} catch (Exception $exception) {
			$wpdb->query('ROLLBACK;');
			wp_send_json_error($exception->getMessage());
		}
	}

	public static function fetch_invoice_payments() {
		$current_user = WLSM_M_Role::can('manage_invoices');

		if (!$current_user) {
			die();
		}

		$current_school = $current_user['school'];

		$can_delete_payments = WLSM_M_Role::check_permission(array('delete_payments'), $current_school['permissions']);

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		global $wpdb;

		$invoice_id = isset($_POST['invoice']) ? absint($_POST['invoice']) : 0;

		if (!wp_verify_nonce($_POST['invoice-payments-' . $invoice_id], 'invoice-payments-' . $invoice_id)) {
			die();
		}

		$query = WLSM_M_Staff_Accountant::fetch_invoice_payments_query($school_id, $session_id, $invoice_id);

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
					'(p.note LIKE "%' . $search_value . '%")';

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
		$columns = array('p.receipt_number', 'p.amount', 'p.payment_method', 'p.transaction_id', 'p.created_at', 'p.note');
		if (isset($_POST['order']) && isset($columns[$_POST['order']['0']['column']])) {
			$order_by  = sanitize_text_field($columns[$_POST['order']['0']['column']]);
			$order_dir = sanitize_text_field($_POST['order']['0']['dir']);

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY p.ID DESC';
		}

		// Limiting.
		$limit = '';
		if (-1 != $_POST['length']) {
			$start  = absint($_POST['start']);
			$length = absint($_POST['length']);

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Accountant::fetch_invoice_payments_query_count($school_id, $session_id, $invoice_id);

		// Total rows count.
		$total_rows_count = $wpdb->get_var($rows_query);

		// Filtered rows count.
		if ($condition) {
			$filter_rows_count = $wpdb->get_var($rows_query . ' AND (' . $condition . ')');
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results($query_filter . $limit);

		$data = array();
		if (count($filter_rows_limit)) {
			foreach ($filter_rows_limit as $row) {
				if ($row->note) {
					$view_note = '<a class="text-primary wlsm-view-payment-note" data-nonce="' . esc_attr(wp_create_nonce('view-payment-note-' . $row->ID)) . '" data-payment="' . esc_attr($row->ID) . '" href="#" data-message-title="' . esc_attr__('Payment Note', 'school-management') . '" data-close="' . esc_attr__('Close', 'school-management') . '"><span class="dashicons dashicons-search"></span></a>';
				} else {
					$view_note = '-';
				}

				// Table columns.
				$columns = array(
					esc_html(WLSM_M_Invoice::get_receipt_number_text($row->receipt_number)),
					esc_html(WLSM_Config::get_money_text($row->amount, $school_id)),
					esc_html(WLSM_M_Invoice::get_payment_method_text($row->payment_method)),
					esc_html(WLSM_M_Invoice::get_transaction_id_text($row->transaction_id)),
					esc_html(WLSM_Config::get_date_text($row->created_at)),
					$view_note,
				);

				$columns[] = '<a class="text-success wlsm-print-invoice-payment" data-nonce="' . esc_attr(wp_create_nonce('print-invoice-payment-' . $row->ID)) . '" data-invoice-payment="' . esc_attr($row->ID) . '" href="#" data-message-title="' . esc_attr__('Print Payment Receipt', 'school-management') . '" data-close="' . esc_attr__('Close', 'school-management') . '"><i class="fas fa-print"></i></a>';

				if ($can_delete_payments) {
					$columns[] = '<a class="text-danger wlsm-delete-invoice-payment" data-nonce="' . esc_attr(wp_create_nonce('delete-payment-' . $row->ID)) . '" data-invoice="' . esc_attr($invoice_id) . '" data-payment="' . esc_attr($row->ID) . '" href="#" data-message-title="' . esc_attr__('Please Confirm!', 'school-management') . '" data-message-content="' . esc_attr__('This will delete the payment from invoice.', 'school-management') . '" data-cancel="' . esc_attr__('Cancel', 'school-management') . '" data-submit="' . esc_attr__('Confirm', 'school-management') . '"><span class="dashicons dashicons-trash"></span></a>';
				}

				$data[] = $columns;
			}
		}

		$output = array(
			'draw'            => intval($_POST['draw']),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);

		echo json_encode($output);
		die;
	}

	public static function collect_invoice_payment() {
		$current_user = WLSM_M_Role::can('manage_invoices');

		if (!$current_user) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		$invoice_id = isset($_POST['invoice_id']) ? absint($_POST['invoice_id']) : 0;

		if (!wp_verify_nonce($_POST['collect-invoice-payment-' . $invoice_id], 'collect-invoice-payment-' . $invoice_id)) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			// Checks if invoice exists.
			$invoice = WLSM_M_Staff_Accountant::fetch_invoice($school_id, $session_id, $invoice_id);

			if (!$invoice) {
				throw new Exception(esc_html__('Invoice not found.', 'school-management'));
			}

			$invoice_id = $invoice->ID;

			$partial_payment = $invoice->partial_payment;

			$payment_amount = isset($_POST['payment_amount']) ? WLSM_Config::sanitize_money($_POST['payment_amount']) : 0;
			$payment_method = isset($_POST['payment_method']) ? sanitize_text_field($_POST['payment_method']) : '';
			$transaction_id = isset($_POST['transaction_id']) ? sanitize_text_field($_POST['transaction_id']) : '';
			$payment_note   = isset($_POST['payment_note']) ? sanitize_text_field($_POST['payment_note']) : '';
			$payment_date   = isset($_POST['payment_date']) ? DateTime::createFromFormat(WLSM_Config::date_format(), sanitize_text_field($_POST['payment_date'])) : NULL;

			// Start validation.
			$errors = array();

			if (strlen($payment_method) > 50) {
				$errors['payment_method'] = esc_html__('Maximum length cannot exceed 50 characters.', 'school-management');
			}

			if (empty($payment_date)) {
				$errors['payment_date'] = esc_html__('Please specify payment date.', 'school-management');
			} else {
				$payment_date = $payment_date->format('Y-m-d');
			}

			$due = $invoice->payable - $invoice->paid;

			$errors = self::validate_invoice_payment($errors, $partial_payment, $due, $payment_amount, $payment_method);
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		if (count($errors) < 1) {
			try {
				$wpdb->query('BEGIN;');

				$message = esc_html__('Payment added successfully.', 'school-management');
				$reset   = true;

				$receipt_number = WLSM_M_Invoice::get_receipt_number($school_id);

				// Payment data.
				$payment_data = array(
					'receipt_number'    => $receipt_number,
					'amount'            => $payment_amount,
					'transaction_id'    => $transaction_id,
					'payment_method'    => $payment_method,
					'note'              => $payment_note,
					'invoice_label'     => $invoice->invoice_title,
					'invoice_payable'   => $invoice->payable,
					'student_record_id' => $invoice->student_id,
					'invoice_id'        => $invoice_id,
					'school_id'         => $school_id,
					'created_at'        => $payment_date,
				);

				$payment_data['added_by'] = get_current_user_id();

				$success = $wpdb->insert(WLSM_PAYMENTS, $payment_data);

				$new_payment_id = $wpdb->insert_id;

				$buffer = ob_get_clean();
				if (!empty($buffer)) {
					throw new Exception($buffer);
				}

				if (false === $success) {
					throw new Exception($wpdb->last_error);
				}

				$invoice_status = WLSM_M_Staff_Accountant::refresh_invoice_status($invoice_id);

				if (WLSM_M_Invoice::get_paid_key() === $invoice_status && ($invoice_status !== $invoice->status)) {
					$reload = true;
				} else {
					$reload = false;
				}

				$wpdb->query('COMMIT;');


				if (isset($new_payment_id)) {
					// Notify for offline fee submission.
					$data = array(
						'school_id'  => $school_id,
						'session_id' => $session_id,
						'payment_id' => $new_payment_id,
					);

					wp_schedule_single_event(time() + 30, 'wlsm_notify_for_offline_fee_submission', $data);
					wp_schedule_single_event(time() + 30, 'wlsm_notify_for_offline_fee_submission_to_parent', $data);
				}

				wp_send_json_success(array('message' => $message, 'reset' => $reset, 'reload' => $reload));
			} catch (Exception $exception) {
				$wpdb->query('ROLLBACK;');
				wp_send_json_error($exception->getMessage());
			}
		}
		wp_send_json_error($errors);
	}

	public static function delete_invoice_payment() {
		$current_user = WLSM_M_Role::can('delete_payments');

		if (!$current_user) {
			die();
		}
		WLSM_Helper::check_demo();

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$payment_id = isset($_POST['payment_id']) ? absint($_POST['payment_id']) : 0;

			if (!wp_verify_nonce($_POST['delete-payment-' . $payment_id], 'delete-payment-' . $payment_id)) {
				die();
			}

			$invoice_id = isset($_POST['invoice_id']) ? absint($_POST['invoice_id']) : 0;

			// Checks if invoice exists.
			$invoice = WLSM_M_Staff_Accountant::get_invoice($school_id, $session_id, $invoice_id);

			if (!$invoice) {
				throw new Exception(esc_html__('Invoice not found.', 'school-management'));
			}

			$invoice_id = $invoice->ID;

			// Checks if payment exists.
			$payment = WLSM_M_Staff_Accountant::get_invoice_payment($invoice_id, $payment_id);

			if (!$payment) {
				throw new Exception(esc_html__('Payment not found.', 'school-management'));
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		try {
			$wpdb->query('BEGIN;');

			$success = $wpdb->delete(WLSM_PAYMENTS, array('ID' => $payment_id));
			$message = esc_html__('Payment deleted successfully.', 'school-management');

			$exception = ob_get_clean();
			if (!empty($exception)) {
				throw new Exception($exception);
			}

			if (false === $success) {
				throw new Exception($wpdb->last_error);
			}

			$invoice_status = WLSM_M_Staff_Accountant::refresh_invoice_status($invoice_id);

			if (WLSM_M_Invoice::get_paid_key() === $invoice->status && ($invoice_status !== $invoice->status)) {
				$reload = true;
			} else {
				$reload = false;
			}

			$wpdb->query('COMMIT;');

			wp_send_json_success(array('message' => $message, 'reload' => $reload));
		} catch (Exception $exception) {
			$wpdb->query('ROLLBACK;');
			wp_send_json_error($exception->getMessage());
		}
	}

	public static function validate_invoice_payment($errors, $partial_payment, $due, $payment_amount, $payment_method) {

		if (strlen($payment_method) > 50) {
			$errors['payment_method'] = esc_html__('Maximum length cannot exceed 50 characters.', 'school-management');
		}

		if (!in_array($payment_method, array_keys(WLSM_M_Invoice::collect_payment_methods()))) {
			$errors['payment_method'] = esc_html__('Please select a valid payment method.', 'school-management');
		}

		return $errors;
	}

	public static function fetch_pending_payments() {
		$current_user = WLSM_M_Role::can('manage_invoices');

		if (!$current_user) {
			die();
		}

		$current_school = $current_user['school'];

		$can_delete_payments = WLSM_M_Role::check_permission(array('delete_payments'), $current_school['permissions']);

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		global $wpdb;

		$page_url = WLSM_M_Staff_Accountant::get_invoices_page_url();

		$query = WLSM_M_Staff_Accountant::fetch_pending_payments_query($school_id, $session_id);

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
			$query_filter .= ' ORDER BY p.ID DESC';
		}

		// Limiting.
		$limit = '';
		if (-1 != $_POST['length']) {
			$start  = absint($_POST['start']);
			$length = absint($_POST['length']);

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Accountant::fetch_pending_payments_query_count($school_id, $session_id);

		// Total rows count.
		$total_rows_count = $wpdb->get_var($rows_query);

		// Filtered rows count.
		if ($condition) {
			$filter_rows_count = $wpdb->get_var($rows_query . ' AND (' . $condition . ')');
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results($query_filter . $limit);

		$data = array();
		if (count($filter_rows_limit)) {
			foreach ($filter_rows_limit as $row) {
				if ($row->invoice_id) {
					$invoice_title = '<a target="_blank" href="' . esc_url($page_url . '&action=save&id=' . $row->invoice_id) . '">' . esc_html(WLSM_M_Staff_Accountant::get_invoice_title_text($row->invoice_title)) . '</a>';
				} else {
					$invoice_title = '<span class="text-danger">' . esc_html__('Deleted', 'school-management') . '<br><span class="text-secondary">' . esc_html(WLSM_M_Staff_Accountant::get_invoice_title_text($row->invoice_label)) . '<br><small>' . esc_html(WLSM_Config::get_money_text($row->invoice_payable, $school_id))  . ' ' . esc_html__('Payable', 'school-management') . '</small></span></span>';
				}

				if (!empty($row->attachment)) {
					$attachment_url = '<a target="_blank" href="' . esc_url(wp_get_attachment_url($row->attachment)) . '"><i class="fas fa-search"></i></a>';
				} else {
					$attachment_url = '-';
				}

				$approve_button = '<a class="btn btn-sm btn-outline-success wlsm-font-bold wlsm-font-small wlsm-approve-pending-payment" data-nonce="' . esc_attr(wp_create_nonce('approve-pending-payment-' . $row->ID)) . '" data-payment="' . esc_attr($row->ID) . '" href="#" data-message-title="' . esc_attr__('Approve Payment', 'school-management') . '" data-close="' . esc_attr__('Close', 'school-management') . '" data-message-content="' . esc_attr__('Are you sure to mark this payment as approved?', 'school-management') . '" data-cancel="' . esc_attr__('Cancel', 'school-management') . '" data-submit="' . esc_attr__('Approve Payment', 'school-management') . '">' . esc_html__('Approve', 'school-management') . '</a>';

				// Table columns.
				$columns = array(
					esc_html(WLSM_M_Invoice::get_receipt_number_text($row->receipt_number)),
					esc_html(WLSM_Config::get_money_text($row->amount, $school_id)),
					esc_html(WLSM_M_Invoice::get_payment_method_text($row->payment_method)),
					esc_html(WLSM_M_Invoice::get_transaction_id_text($row->transaction_id)),
					$attachment_url,
					esc_html(WLSM_Config::get_date_text($row->created_at)),
					$invoice_title,
					$approve_button,
					esc_html(WLSM_M_Staff_Class::get_name_text($row->student_name)),
					esc_html(WLSM_M_Staff_Class::get_admission_no_text($row->admission_number)),
					esc_html(WLSM_M_Class::get_label_text($row->class_label)),
					esc_html(WLSM_M_Staff_Class::get_section_label_text($row->section_label)),
					esc_html($row->enrollment_number),
					esc_html(WLSM_M_Staff_Class::get_phone_text($row->phone)),
					esc_html(WLSM_M_Staff_Class::get_name_text($row->father_name)),
					esc_html(WLSM_M_Staff_Class::get_phone_text($row->father_phone))
				);

				if ($can_delete_payments) {
					$columns[] = '<a class="text-danger wlsm-delete-pending-payment" data-nonce="' . esc_attr(wp_create_nonce('delete-payment-' . $row->ID)) . '" data-payment="' . esc_attr($row->ID) . '" href="#" data-message-title="' . esc_attr__('Please Confirm!', 'school-management') . '" data-message-content="' . esc_attr__('This will delete the payment.', 'school-management') . '" data-cancel="' . esc_attr__('Cancel', 'school-management') . '" data-submit="' . esc_attr__('Confirm', 'school-management') . '"><span class="dashicons dashicons-trash"></span></a>';
				}

				$data[] = $columns;
			}
		}

		$output = array(
			'draw'            => intval($_POST['draw']),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);

		echo json_encode($output);
		die;
	}

	public static function delete_pending_payment() {
		$current_user = WLSM_M_Role::can('delete_payments');

		if (!$current_user) {
			die();
		}
		WLSM_Helper::check_demo();

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$payment_id = isset($_POST['payment_id']) ? absint($_POST['payment_id']) : 0;

			if (!wp_verify_nonce($_POST['delete-payment-' . $payment_id], 'delete-payment-' . $payment_id)) {
				die();
			}

			// Checks if payment exists.
			$payment = WLSM_M_Staff_Accountant::get_pending_payment($school_id, $session_id, $payment_id);

			if (!$payment) {
				throw new Exception(esc_html__('Pending payment not found.', 'school-management'));
			}

			$invoice_id = $payment->invoice_id;
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		try {
			$wpdb->query('BEGIN;');

			$success = $wpdb->delete(WLSM_PENDING_PAYMENTS, array('ID' => $payment_id));
			$message = esc_html__('Pending payment deleted successfully.', 'school-management');

			$exception = ob_get_clean();
			if (!empty($exception)) {
				throw new Exception($exception);
			}

			if (false === $success) {
				throw new Exception($wpdb->last_error);
			}

			$wpdb->query('COMMIT;');

			wp_send_json_success(array('message' => $message));
		} catch (Exception $exception) {
			$wpdb->query('ROLLBACK;');
			wp_send_json_error($exception->getMessage());
		}
	}

	public static function approve_pending_payment() {
		$current_user = WLSM_M_Role::can('manage_invoices');

		if (!$current_user) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$pending_payment_id = isset($_POST['payment_id']) ? absint($_POST['payment_id']) : 0;

			if (!wp_verify_nonce($_POST['approve-pending-payment-' . $pending_payment_id], 'approve-pending-payment-' . $pending_payment_id)) {
				die();
			}

			// Checks if pending payment exists.
			$pending_payment = WLSM_M_Staff_Accountant::fetch_pending_payment($school_id, $session_id, $pending_payment_id);

			if (!$pending_payment) {
				throw new Exception(esc_html__('Pending payment not found.', 'school-management'));
			}

			$payment_amount = $pending_payment->amount;
			$invoice_id     = $pending_payment->invoice_id;

			if ($invoice_id) {
				// Checks if invoice exists.
				$invoice = WLSM_M_Staff_Accountant::fetch_invoice($school_id, $session_id, $invoice_id);

				if ($invoice) {
					$due = $invoice->payable - $invoice->paid;
					if ($payment_amount > $due) {
						throw new Exception(
							sprintf(
								/* translators: %s: payable amount */
								__('Amount cannot exceed invoice payable amount: %s', 'school-management'),
								WLSM_Config::get_money_text($due, $school_id)
							)
						);
					}
				}
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		try {
			$wpdb->query('BEGIN;');

			$now = current_time('Y-m-d H:i:s');

			$receipt_number    = $pending_payment->receipt_number;
			$transaction_id    = $pending_payment->transaction_id;
			$payment_method    = $pending_payment->payment_method;
			$attachment        = $pending_payment->attachment;
			$created_at        = $pending_payment->created_at;
			$payment_note      = $pending_payment->note;
			$invoice_label     = $pending_payment->invoice_label;
			$invoice_payable   = $pending_payment->invoice_payable;
			$student_record_id = $pending_payment->student_record_id;

			// Payment data.
			$payment_data = array(
				'receipt_number'    => $receipt_number,
				'amount'            => $payment_amount,
				'transaction_id'    => $transaction_id,
				'attachment'        => $attachment,
				'payment_method'    => $payment_method,
				'note'              => $payment_note,
				'invoice_label'     => $invoice_label,
				'invoice_payable'   => $invoice_payable,
				'student_record_id' => $student_record_id,
				'invoice_id'        => $invoice_id,
				'school_id'         => $school_id,
				'created_at'        => $created_at,
			);

			$payment_data['added_by'] = get_current_user_id();

			$payment_data['updated_at'] = current_time('Y-m-d H:i:s');

			$success = $wpdb->insert(WLSM_PAYMENTS, $payment_data);

			$new_payment_id = $wpdb->insert_id;

			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				throw new Exception($buffer);
			}

			if (false === $success) {
				throw new Exception($wpdb->last_error);
			}

			$invoice_status = WLSM_M_Staff_Accountant::refresh_invoice_status($invoice_id);

			$success = $wpdb->delete(WLSM_PENDING_PAYMENTS, array('ID' => $pending_payment_id));

			$exception = ob_get_clean();
			if (!empty($exception)) {
				throw new Exception($exception);
			}

			if (false === $success) {
				throw new Exception($wpdb->last_error);
			}

			if (isset($new_payment_id)) {
				// Notify for offline fee submission.
				$data = array(
					'school_id'  => $school_id,
					'session_id' => $session_id,
					'payment_id' => $new_payment_id,
				);

				wp_schedule_single_event(time() + 30, 'wlsm_notify_for_offline_fee_submission', $data);
				wp_schedule_single_event(time() + 30, 'wlsm_notify_for_offline_fee_submission_to_parent', $data);
			}

			$message = esc_html__('Payment has been approved.', 'school-management');

			$exception = ob_get_clean();
			if (!empty($exception)) {
				throw new Exception($exception);
			}

			if (false === $success) {
				throw new Exception($wpdb->last_error);
			}

			$wpdb->query('COMMIT;');

			wp_send_json_success(array('message' => $message));
		} catch (Exception $exception) {
			$wpdb->query('ROLLBACK;');
			wp_send_json_error($exception->getMessage());
		}
	}

	public static function fetch_payments() {
		$current_user = WLSM_M_Role::can('manage_invoices');

		if (!$current_user) {
			die();
		}

		$current_school = $current_user['school'];

		$can_delete_payments = WLSM_M_Role::check_permission(array('delete_payments'), $current_school['permissions']);

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		$from_table = isset($_POST['from_table']) ? (bool) ($_POST['from_table']) : 0;

		$output = array(
			'draw'            => 1,
			'recordsTotal'    => 0,
			'recordsFiltered' => 0,
			'data'            => array(),
		);

		$start_date = isset($_POST['start_date']) ? DateTime::createFromFormat(WLSM_Config::date_format(), sanitize_text_field($_POST['start_date'])) : NULL;
		$end_date = isset($_POST['end_date']) ? DateTime::createFromFormat(WLSM_Config::date_format(), sanitize_text_field($_POST['end_date'])) : NULL;

		if ($start_date) {
			$start_date = $start_date->format('Y-m-d');
		}
		if ($end_date) {
			$end_date = $end_date->format('Y-m-d');
		}
		global $wpdb;
		if ($start_date && $end_date) {
			$total = $wpdb->get_var($wpdb->prepare('SELECT COALESCE(SUM(we.amount), 0) as sum FROM ' . WLSM_PAYMENTS . ' as we WHERE we.school_id ='.$school_id.' AND we.created_at BETWEEN ' . "'$start_date'" . ' AND ' . "'$end_date'"));
		}

		$page_url = WLSM_M_Staff_Accountant::get_invoices_page_url();

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
			$filter_rows_limit = $wpdb->get_results($query_filter);
		} else {
			$filter_rows_limit = $wpdb->get_results($query_filter . $limit);
		}

		$data = array();
		if (count($filter_rows_limit)) {
			foreach ($filter_rows_limit as $row) {
				if ($row->invoice_id) {
					$invoice_title = '<a target="_blank" href="' . esc_url($page_url . '&action=save&id=' . $row->invoice_id) . '">' . esc_html(WLSM_M_Staff_Accountant::get_invoice_title_text($row->invoice_title)) . '</a>';
				} else {
					$invoice_title = '<span class="text-danger">' . esc_html__('Deleted', 'school-management') . '<br><span class="text-secondary">' . esc_html(WLSM_M_Staff_Accountant::get_invoice_title_text($row->invoice_label)) . '<br><small>' . esc_html(WLSM_Config::get_money_text($row->invoice_payable, $school_id))  . ' ' . esc_html__('Payable', 'school-management') . '</small></span></span>';
				}

				if ($row->note) {
					$view_note = '<a class="text-primary wlsm-view-payment-note" data-nonce="' . esc_attr(wp_create_nonce('view-payment-note-' . $row->ID)) . '" data-payment="' . esc_attr($row->ID) . '" href="#" data-message-title="' . esc_attr__('Payment Note', 'school-management') . '" data-close="' . esc_attr__('Close', 'school-management') . '"><span class="dashicons dashicons-search"></span></a>';
				} else {
					$view_note = '-';
				}

				// Table columns.
				$columns = array(
					esc_html(WLSM_M_Invoice::get_receipt_number_text($row->receipt_number)),
					esc_html(WLSM_Config::get_money_text($row->amount, $school_id)),
					esc_html(WLSM_M_Invoice::get_payment_method_text($row->payment_method)),
					esc_html(WLSM_M_Invoice::get_transaction_id_text($row->transaction_id)),
					esc_html(WLSM_Config::get_date_text($row->created_at)),
					$view_note,
					$invoice_title,
					esc_html(WLSM_M_Staff_Class::get_name_text($row->student_name)),
					esc_html(WLSM_M_Staff_Class::get_admission_no_text($row->admission_number)),
					esc_html(WLSM_M_Class::get_label_text($row->class_label)),
					esc_html(WLSM_M_Staff_Class::get_section_label_text($row->section_label)),
					esc_html($row->enrollment_number),
					esc_html(WLSM_M_Staff_Class::get_phone_text($row->phone)),
					esc_html(WLSM_M_Staff_Class::get_name_text($row->father_name)),
					esc_html(WLSM_M_Staff_Class::get_phone_text($row->father_phone)),
					'<a class="text-success wlsm-print-invoice-payment" data-nonce="' . esc_attr(wp_create_nonce('print-invoice-payment-' . $row->ID)) . '" data-invoice-payment="' . esc_attr($row->ID) . '" href="#" data-message-title="' . esc_attr__('Print Payment Receipt', 'school-management') . '" data-close="' . esc_attr__('Close', 'school-management') . '"><i class="fas fa-print"></i></a>'
				);

				if ($can_delete_payments) {
					$columns[] = '<a class="text-danger wlsm-delete-payment" data-nonce="' . esc_attr(wp_create_nonce('delete-payment-' . $row->ID)) . '" data-payment="' . esc_attr($row->ID) . '" href="#" data-message-title="' . esc_attr__('Please Confirm!', 'school-management') . '" data-message-content="' . esc_attr__('This will delete the payment.', 'school-management') . '" data-cancel="' . esc_attr__('Cancel', 'school-management') . '" data-submit="' . esc_attr__('Confirm', 'school-management') . '"><span class="dashicons dashicons-trash"></span></a>';
				}

				$data[] = $columns;
			}
		}

		$output = array(
			'draw'            => intval($_POST['draw']),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
			'export'          => array(
				'nonce'  => wp_create_nonce('export-staff-payments-table'),
				'action' => 'wlsm-export-staff-payments-table',
				'filter' => json_encode(
					array(
						'start_date' => $start_date,
						'end_date'   => $end_date,
					)
				)
					),
			'total' => $total
		);

		echo json_encode($output);
		die;
	}

	public static function delete_payment() {
		$current_user = WLSM_M_Role::can('delete_payments');

		if (!$current_user) {
			die();
		}

		WLSM_Helper::check_demo();

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$payment_id = isset($_POST['payment_id']) ? absint($_POST['payment_id']) : 0;

			if (!wp_verify_nonce($_POST['delete-payment-' . $payment_id], 'delete-payment-' . $payment_id)) {
				die();
			}

			// Checks if payment exists.
			$payment = WLSM_M_Staff_Accountant::get_payment($school_id, $session_id, $payment_id);

			if (!$payment) {
				throw new Exception(esc_html__('Payment not found.', 'school-management'));
			}

			$invoice_id = $payment->invoice_id;
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		try {
			$wpdb->query('BEGIN;');

			$success = $wpdb->delete(WLSM_PAYMENTS, array('ID' => $payment_id));
			$message = esc_html__('Payment deleted successfully.', 'school-management');

			$exception = ob_get_clean();
			if (!empty($exception)) {
				throw new Exception($exception);
			}

			if (false === $success) {
				throw new Exception($wpdb->last_error);
			}

			if ($invoice_id) {
				$invoice_status = WLSM_M_Staff_Accountant::refresh_invoice_status($invoice_id);
			}

			$wpdb->query('COMMIT;');

			wp_send_json_success(array('message' => $message));
		} catch (Exception $exception) {
			$wpdb->query('ROLLBACK;');
			wp_send_json_error($exception->getMessage());
		}
	}

	public static function view_payment_note() {
		$current_user = WLSM_M_Role::can('manage_invoices');

		if (!$current_user) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$payment_id = isset($_POST['payment_id']) ? absint($_POST['payment_id']) : 0;

			if (!wp_verify_nonce($_POST['view-payment-note-' . $payment_id], 'view-payment-note-' . $payment_id)) {
				die();
			}

			// Checks if payment exists.
			$payment = WLSM_M_Staff_Accountant::get_payment_note($school_id, $session_id, $payment_id);

			if (!$payment) {
				throw new Exception(esc_html__('Payment not found.', 'school-management'));
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		wp_send_json_success(esc_html(WLSM_Config::get_note_text($payment->note)));
	}

	public static function print_payment() {
		$current_user = WLSM_M_Role::can('manage_invoices');

		if (!$current_user) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$payment_id = isset($_POST['payment_id']) ? absint($_POST['payment_id']) : 0;

			if (!wp_verify_nonce($_POST['print-invoice-payment-' . $payment_id], 'print-invoice-payment-' . $payment_id)) {
				die();
			}

			// Checks if payment exists.
			$payment = WLSM_M_Staff_Accountant::fetch_payment($school_id, $session_id, $payment_id);

			if (!$payment) {
				throw new Exception(esc_html__('Payment not found.', 'school-management'));
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		ob_start();
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/payment.php';
		$html = ob_get_clean();

		wp_send_json_success(array('html' => $html));
	}

	public static function fetch_expense_categories() {
		$current_user = WLSM_M_Role::can('manage_expenses');

		if (!$current_user) {
			die();
		}

		$school_id = $current_user['school']['id'];

		global $wpdb;

		$page_url = WLSM_M_Staff_Accountant::get_expenses_page_url();

		$query = WLSM_M_Staff_Accountant::fetch_expense_category_query($school_id);

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Accountant::fetch_expense_category_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if (isset($_POST['search']['value'])) {
			$search_value = sanitize_text_field($_POST['search']['value']);
			if ('' !== $search_value) {
				$condition .= '' .
					'(ec.label LIKE "%' . $search_value . '%")';

				$query_filter .= (' HAVING ' . $condition);
			}
		}

		// Ordering.
		$columns = array('ec.label');
		if (isset($_POST['order']) && isset($columns[$_POST['order']['0']['column']])) {
			$order_by  = sanitize_text_field($columns[$_POST['order']['0']['column']]);
			$order_dir = sanitize_text_field($_POST['order']['0']['dir']);

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY ec.ID DESC';
		}

		// Limiting.
		$limit = '';
		if (-1 != $_POST['length']) {
			$start  = absint($_POST['start']);
			$length = absint($_POST['length']);

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Accountant::fetch_expense_category_query_count($school_id);

		// Total rows count.
		$total_rows_count = $wpdb->get_var($rows_query);

		// Filtered rows count.
		if ($condition) {
			$filter_rows_count = $wpdb->get_var($rows_query . ' AND (' . $condition . ')');
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results($query_filter . $limit);

		$data = array();

		if (count($filter_rows_limit)) {
			foreach ($filter_rows_limit as $row) {
				// Table columns.
				$data[] = array(
					esc_html(WLSM_M_Staff_Accountant::get_label_text($row->label)),
					'<a class="text-primary" href="' . esc_url($page_url . "&action=category&id=" . $row->ID) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-expense-category" data-nonce="' . esc_attr(wp_create_nonce('delete-expense-category-' . $row->ID)) . '" data-expense-category="' . esc_attr($row->ID) . '" href="#" data-message-title="' . esc_attr__('Please Confirm!', 'school-management') . '" data-message-content="' . esc_attr__('This will delete the expense category.', 'school-management') . '" data-cancel="' . esc_attr__('Cancel', 'school-management') . '" data-submit="' . esc_attr__('Confirm', 'school-management') . '"><span class="dashicons dashicons-trash"></span></a>'
				);
			}
		}

		$output = array(
			'draw'            => intval($_POST['draw']),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);

		echo json_encode($output);
		die();
	}

	public static function save_expense_category() {
		$current_user = WLSM_M_Role::can('manage_expenses');

		if (!$current_user) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$expense_category_id = isset($_POST['expense_category_id']) ? absint($_POST['expense_category_id']) : 0;

			if ($expense_category_id) {
				if (!wp_verify_nonce($_POST['edit-expense-category-' . $expense_category_id], 'edit-expense-category-' . $expense_category_id)) {
					die();
				}
			} else {
				if (!wp_verify_nonce($_POST['add-expense-category'], 'add-expense-category')) {
					die();
				}
			}

			// Checks if expense category exists.
			if ($expense_category_id) {
				$expense_category = WLSM_M_Staff_Accountant::get_expense_category($school_id, $expense_category_id);

				if (!$expense_category) {
					throw new Exception(esc_html__('Expense category not found.', 'school-management'));
				}
			}

			$label = isset($_POST['label']) ? sanitize_text_field($_POST['label']) : '';

			// Start validation.
			$errors = array();

			if (empty($label)) {
				$errors['label'] = esc_html__('Please specify expense category.', 'school-management');
			}
			if (strlen($label) > 100) {
				$errors['label'] = esc_html__('Maximum length cannot exceed 100 characters.', 'school-management');
			}

			// Checks if expense category already exists with this label.
			if ($expense_category_id) {
				$expense_category_exist = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) as count FROM ' . WLSM_EXPENSE_CATEGORIES . ' as ec WHERE ec.label = %s AND ec.school_id = %d AND ec.ID != %d', $label, $school_id, $expense_category_id));
			} else {
				$expense_category_exist = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) as count FROM ' . WLSM_EXPENSE_CATEGORIES . ' as ec WHERE ec.label = %s AND ec.school_id = %d', $label, $school_id));
			}

			if ($expense_category_exist) {
				$errors['label'] = esc_html__('Expense category already exists with this label.', 'school-management');
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		if (count($errors) < 1) {
			try {
				$wpdb->query('BEGIN;');

				if ($expense_category_id) {
					$message = esc_html__('Expense category updated successfully.', 'school-management');
					$reset   = false;
				} else {
					$message = esc_html__('Expense category added successfully.', 'school-management');
					$reset   = true;
				}

				// Expense category data.
				$data = array(
					'label' => $label,
				);

				if ($expense_category_id) {
					$data['updated_at'] = current_time('Y-m-d H:i:s');

					$success = $wpdb->update(WLSM_EXPENSE_CATEGORIES, $data, array('ID' => $expense_category_id, 'school_id' => $school_id));
				} else {
					$data['created_at'] = current_time('Y-m-d H:i:s');

					$data['school_id'] = $school_id;

					$success = $wpdb->insert(WLSM_EXPENSE_CATEGORIES, $data);
				}

				$buffer = ob_get_clean();
				if (!empty($buffer)) {
					throw new Exception($buffer);
				}

				if (false === $success) {
					throw new Exception($wpdb->last_error);
				}

				$wpdb->query('COMMIT;');

				wp_send_json_success(array('message' => $message, 'reset' => $reset));
			} catch (Exception $exception) {
				$wpdb->query('ROLLBACK;');
				wp_send_json_error($exception->getMessage());
			}
		}
		wp_send_json_error($errors);
	}

	public static function delete_expense_category() {
		$current_user = WLSM_M_Role::can('manage_expenses');

		if (!$current_user) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$expense_category_id = isset($_POST['expense_category_id']) ? absint($_POST['expense_category_id']) : 0;

			if (!wp_verify_nonce($_POST['delete-expense-category-' . $expense_category_id], 'delete-expense-category-' . $expense_category_id)) {
				die();
			}

			// Checks if expense category exists.
			$expense_category = WLSM_M_Staff_Accountant::get_expense_category($school_id, $expense_category_id);

			if (!$expense_category) {
				throw new Exception(esc_html__('Expense category not found.', 'school-management'));
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		try {
			$wpdb->query('BEGIN;');

			$success = $wpdb->delete(WLSM_EXPENSE_CATEGORIES, array('ID' => $expense_category_id));
			$message = esc_html__('Expense category deleted successfully.', 'school-management');

			$exception = ob_get_clean();
			if (!empty($exception)) {
				throw new Exception($exception);
			}

			if (false === $success) {
				throw new Exception($wpdb->last_error);
			}

			$wpdb->query('COMMIT;');

			wp_send_json_success(array('message' => $message));
		} catch (Exception $exception) {
			$wpdb->query('ROLLBACK;');
			wp_send_json_error($exception->getMessage());
		}
	}

	public static function fetch_income_categories() {
		$current_user = WLSM_M_Role::can('manage_income');

		if (!$current_user) {
			die();
		}

		$school_id = $current_user['school']['id'];

		global $wpdb;

		$page_url = WLSM_M_Staff_Accountant::get_income_page_url();

		$query = WLSM_M_Staff_Accountant::fetch_income_category_query($school_id);

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Accountant::fetch_income_category_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if (isset($_POST['search']['value'])) {
			$search_value = sanitize_text_field($_POST['search']['value']);
			if ('' !== $search_value) {
				$condition .= '' .
					'(ic.label LIKE "%' . $search_value . '%")';

				$query_filter .= (' HAVING ' . $condition);
			}
		}

		// Ordering.
		$columns = array('ic.label');
		if (isset($_POST['order']) && isset($columns[$_POST['order']['0']['column']])) {
			$order_by  = sanitize_text_field($columns[$_POST['order']['0']['column']]);
			$order_dir = sanitize_text_field($_POST['order']['0']['dir']);

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY ic.ID DESC';
		}

		// Limiting.
		$limit = '';
		if (-1 != $_POST['length']) {
			$start  = absint($_POST['start']);
			$length = absint($_POST['length']);

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Accountant::fetch_income_category_query_count($school_id);

		// Total rows count.
		$total_rows_count = $wpdb->get_var($rows_query);

		// Filtered rows count.
		if ($condition) {
			$filter_rows_count = $wpdb->get_var($rows_query . ' AND (' . $condition . ')');
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results($query_filter . $limit);

		$data = array();

		if (count($filter_rows_limit)) {
			foreach ($filter_rows_limit as $row) {
				// Table columns.
				$data[] = array(
					esc_html(WLSM_M_Staff_Accountant::get_label_text($row->label)),
					'<a class="text-primary" href="' . esc_url($page_url . "&action=category&id=" . $row->ID) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-income-category" data-nonce="' . esc_attr(wp_create_nonce('delete-income-category-' . $row->ID)) . '" data-income-category="' . esc_attr($row->ID) . '" href="#" data-message-title="' . esc_attr__('Please Confirm!', 'school-management') . '" data-message-content="' . esc_attr__('This will delete the income category.', 'school-management') . '" data-cancel="' . esc_attr__('Cancel', 'school-management') . '" data-submit="' . esc_attr__('Confirm', 'school-management') . '"><span class="dashicons dashicons-trash"></span></a>'
				);
			}
		}

		$output = array(
			'draw'            => intval($_POST['draw']),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);

		echo json_encode($output);
		die();
	}

	public static function save_income_category() {
		$current_user = WLSM_M_Role::can('manage_income');

		if (!$current_user) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$income_category_id = isset($_POST['income_category_id']) ? absint($_POST['income_category_id']) : 0;

			if ($income_category_id) {
				if (!wp_verify_nonce($_POST['edit-income-category-' . $income_category_id], 'edit-income-category-' . $income_category_id)) {
					die();
				}
			} else {
				if (!wp_verify_nonce($_POST['add-income-category'], 'add-income-category')) {
					die();
				}
			}

			// Checks if income category exists.
			if ($income_category_id) {
				$income_category = WLSM_M_Staff_Accountant::get_income_category($school_id, $income_category_id);

				if (!$income_category) {
					throw new Exception(esc_html__('Income category not found.', 'school-management'));
				}
			}

			$label = isset($_POST['label']) ? sanitize_text_field($_POST['label']) : '';

			// Start validation.
			$errors = array();

			if (empty($label)) {
				$errors['label'] = esc_html__('Please specify income category.', 'school-management');
			}
			if (strlen($label) > 100) {
				$errors['label'] = esc_html__('Maximum length cannot exceed 100 characters.', 'school-management');
			}

			// Checks if income category already exists with this label.
			if ($income_category_id) {
				$income_category_exist = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) as count FROM ' . WLSM_INCOME_CATEGORIES . ' as ic WHERE ic.label = %s AND ic.school_id = %d AND ic.ID != %d', $label, $school_id, $income_category_id));
			} else {
				$income_category_exist = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) as count FROM ' . WLSM_INCOME_CATEGORIES . ' as ic WHERE ic.label = %s AND ic.school_id = %d', $label, $school_id));
			}

			if ($income_category_exist) {
				$errors['label'] = esc_html__('Income category already exists with this label.', 'school-management');
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		if (count($errors) < 1) {
			try {
				$wpdb->query('BEGIN;');

				if ($income_category_id) {
					$message = esc_html__('Income category updated successfully.', 'school-management');
					$reset   = false;
				} else {
					$message = esc_html__('Income category added successfully.', 'school-management');
					$reset   = true;
				}

				// Income category data.
				$data = array(
					'label' => $label,
				);

				if ($income_category_id) {
					$data['updated_at'] = current_time('Y-m-d H:i:s');

					$success = $wpdb->update(WLSM_INCOME_CATEGORIES, $data, array('ID' => $income_category_id, 'school_id' => $school_id));
				} else {
					$data['created_at'] = current_time('Y-m-d H:i:s');

					$data['school_id'] = $school_id;

					$success = $wpdb->insert(WLSM_INCOME_CATEGORIES, $data);
				}

				$buffer = ob_get_clean();
				if (!empty($buffer)) {
					throw new Exception($buffer);
				}

				if (false === $success) {
					throw new Exception($wpdb->last_error);
				}

				$wpdb->query('COMMIT;');

				wp_send_json_success(array('message' => $message, 'reset' => $reset));
			} catch (Exception $exception) {
				$wpdb->query('ROLLBACK;');
				wp_send_json_error($exception->getMessage());
			}
		}
		wp_send_json_error($errors);
	}

	public static function delete_income_category() {
		$current_user = WLSM_M_Role::can('manage_income');

		if (!$current_user) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$income_category_id = isset($_POST['income_category_id']) ? absint($_POST['income_category_id']) : 0;

			if (!wp_verify_nonce($_POST['delete-income-category-' . $income_category_id], 'delete-income-category-' . $income_category_id)) {
				die();
			}

			// Checks if income category exists.
			$income_category = WLSM_M_Staff_Accountant::get_income_category($school_id, $income_category_id);

			if (!$income_category) {
				throw new Exception(esc_html__('Income category not found.', 'school-management'));
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		try {
			$wpdb->query('BEGIN;');

			$success = $wpdb->delete(WLSM_INCOME_CATEGORIES, array('ID' => $income_category_id));
			$message = esc_html__('Income category deleted successfully.', 'school-management');

			$exception = ob_get_clean();
			if (!empty($exception)) {
				throw new Exception($exception);
			}

			if (false === $success) {
				throw new Exception($wpdb->last_error);
			}

			$wpdb->query('COMMIT;');

			wp_send_json_success(array('message' => $message));
		} catch (Exception $exception) {
			$wpdb->query('ROLLBACK;');
			wp_send_json_error($exception->getMessage());
		}
	}

	public static function fetch_expenses() {
		$current_user = WLSM_M_Role::can('manage_expenses');

		if (!$current_user) {
			die();
		}

		$school_id = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		$session_start_date = $current_user['session']['start_date'];
		$session_end_date =  $current_user['session']['end_date'];

		$start_date = !empty($_POST['start_date']) ? DateTime::createFromFormat(WLSM_Config::date_format(), sanitize_text_field($_POST['start_date'])) : $session_start_date;
		$end_date = !empty($_POST['end_date']) ? DateTime::createFromFormat(WLSM_Config::date_format(), sanitize_text_field($_POST['end_date'])) : $session_end_date;
		$from_table = isset($_POST['from_table']) ? (bool) ($_POST['from_table']) : 0;

		$output = array(
			'draw'            => 1,
			'recordsTotal'    => 0,
			'recordsFiltered' => 0,
			'data'            => array(),
		);
		if (!empty($_POST['start_date'])) {
			$start_date = $start_date->format('Y-m-d');
		}
		if (!empty($_POST['end_date'])) {
			$end_date = $end_date->format('Y-m-d');
		}

		global $wpdb;

		$page_url = WLSM_M_Staff_Accountant::get_expenses_page_url();

		$query = WLSM_M_Staff_Accountant::fetch_expense_query($school_id, $start_date, $end_date, $session_start_date, $session_end_date);

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Accountant::fetch_expense_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if (isset($_POST['search']['value'])) {
			$search_value = sanitize_text_field($_POST['search']['value']);
			if ('' !== $search_value) {
				$condition .= '' .
					'(ep.label LIKE "%' . $search_value . '%") OR ' .
					'(ep.invoice_number LIKE "%' . $search_value . '%") OR ' .
					'(ep.amount LIKE "%' . $search_value . '%") OR ' .
					'(ep.note LIKE "%' . $search_value . '%") OR ' .
					'(ec.label LIKE "%' . $search_value . '%")';

				$expense_date = DateTime::createFromFormat(WLSM_Config::date_format(), $search_value);

				if ($expense_date) {
					$format_expense_date = 'Y-m-d';
				} else {
					if ('d-m-Y' === WLSM_Config::date_format()) {
						if (!$expense_date) {
							$expense_date        = DateTime::createFromFormat('m-Y', $search_value);
							$format_expense_date = 'Y-m';
						}
					} else if ('d/m/Y' === WLSM_Config::date_format()) {
						if (!$expense_date) {
							$expense_date        = DateTime::createFromFormat('m/Y', $search_value);
							$format_expense_date = 'Y-m';
						}
					} else if ('Y-m-d' === WLSM_Config::date_format()) {
						if (!$expense_date) {
							$expense_date        = DateTime::createFromFormat('Y-m', $search_value);
							$format_expense_date = 'Y-m';
						}
					} else if ('Y/m/d' === WLSM_Config::date_format()) {
						if (!$expense_date) {
							$expense_date        = DateTime::createFromFormat('Y/m', $search_value);
							$format_expense_date = 'Y-m';
						}
					}

					if (!$expense_date) {
						$expense_date        = DateTime::createFromFormat('Y', $search_value);
						$format_expense_date = 'Y';
					}
				}

				if ($expense_date && isset($format_expense_date)) {
					$expense_date = $expense_date->format($format_expense_date);
					$expense_date = ' OR (ep.expense_date LIKE "%' . $expense_date . '%")';

					$condition .= $expense_date;
				}

				$query_filter .= (' HAVING ' . $condition);
			}
		}

		// Ordering.
		$columns = array('ep.label', 'ec.label', 'ep.amount', 'ep.invoice_number', 'ep.invoice_date', 'ep.note');
		if (isset($_POST['order']) && isset($columns[$_POST['order']['0']['column']])) {
			$order_by  = sanitize_text_field($columns[$_POST['order']['0']['column']]);
			$order_dir = sanitize_text_field($_POST['order']['0']['dir']);

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY ep.ID DESC';
		}

		// Limiting.
		$limit = '';
		if (-1 != $_POST['length']) {
			$start  = absint($_POST['start']);
			$length = absint($_POST['length']);

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Accountant::fetch_expense_query_count($school_id, $session_id,  $start_date, $end_date);

		$expense_total = $wpdb->get_var($wpdb->prepare('SELECT COALESCE(SUM(we.amount), 0) as sum FROM ' . WLSM_EXPENSES . ' as we WHERE we.school_id ='.$school_id.' AND we.expense_date BETWEEN ' . "'$start_date'" . ' AND ' . "'$end_date'"));

		// Total rows count.
		$total_rows_count = $wpdb->get_var($rows_query);

		// Filtered rows count.
		if ($condition) {
			$filter_rows_count = $wpdb->get_var($rows_query . ' AND (' . $condition . ')');
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results($query_filter . $limit);

		$data = array();

		if (count($filter_rows_limit)) {
			foreach ($filter_rows_limit as $row) {
				if ($row->note) {
					$view_note = '<a class="text-primary wlsm-view-expense-note" data-nonce="' . esc_attr(wp_create_nonce('view-expense-note-' . $row->ID)) . '" data-expense="' . esc_attr($row->ID) . '" href="#" data-message-title="' . esc_attr__('Expense Note', 'school-management') . '" data-close="' . esc_attr__('Close', 'school-management') . '"><span class="dashicons dashicons-search"></span></a>';
				} else {
					$view_note = '-';
				}

				// Table columns.
				$data[] = array(
					esc_html(WLSM_M_Staff_Accountant::get_label_text($row->label)),
					esc_html(WLSM_M_Staff_Accountant::get_category_label_text($row->expense_category)),
					esc_html(WLSM_Config::get_money_text($row->amount, $school_id)),
					esc_html($row->invoice_number),
					esc_html(WLSM_Config::get_date_text($row->expense_date)),
					$view_note,
					'<a class="text-primary" href="' . esc_url($page_url . "&action=save&id=" . $row->ID) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-expense" data-nonce="' . esc_attr(wp_create_nonce('delete-expense-' . $row->ID)) . '" data-expense="' . esc_attr($row->ID) . '" href="#" data-message-title="' . esc_attr__('Please Confirm!', 'school-management') . '" data-message-content="' . esc_attr__('This will delete the expense.', 'school-management') . '" data-cancel="' . esc_attr__('Cancel', 'school-management') . '" data-submit="' . esc_attr__('Confirm', 'school-management') . '"><span class="dashicons dashicons-trash"></span></a>'
				);
			}
		}

		$output = array(
			'draw'            => intval($_POST['draw']),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
			'export'          => array(
				'nonce'  => wp_create_nonce('export-staff-expenses-table'),
				'action' => 'wlsm-export-staff-expenses-table',
				'filter' => ''
			),
			'total'           => $expense_total,
		);

		echo json_encode($output);
		die();
	}

	public static function fetch_student_birthdays() {
		$current_user = WLSM_M_Role::can('manage_students');

		if (!$current_user) {
			die();
		}

		$school_id = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		$start_date = !empty($_POST['start_date']) ? DateTime::createFromFormat(WLSM_Config::date_format(), sanitize_text_field($_POST['start_date'])) : null;
		$end_date = !empty($_POST['end_date']) ? DateTime::createFromFormat(WLSM_Config::date_format(), sanitize_text_field($_POST['end_date'])) : null;
		$from_table = isset($_POST['from_table']) ? (bool) ($_POST['from_table']) : 0;

		$output = array(
			'draw'            => 1,
			'recordsTotal'    => 0,
			'recordsFiltered' => 0,
			'data'            => array(),
		);
		if (!empty($_POST['start_date'])) {
			$start_date = $start_date->format('Y-m-d');
		}
		if (!empty($_POST['end_date'])) {
			$end_date = $end_date->format('Y-m-d');
		}

		global $wpdb;

		$page_url = WLSM_M_Staff_Accountant::get_expenses_page_url();

		$query = WLSM_M_Staff_General::fetch_students_birthdays_query($school_id, $session_id, $start_date, $end_date);

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_General::fetch_students_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if (isset($_POST['search']['value'])) {
			$search_value = sanitize_text_field($_POST['search']['value']);
			if ('' !== $search_value) {
				$condition .= '' .
					'(sr.name LIKE "%' . $search_value . '%") OR ' .
					'(sr.admission_number LIKE "%' . $search_value . '%") OR ' .
					'(sr.enrollment_number LIKE "%' . $search_value . '%") OR ' .
					'(sr.phone LIKE "%' . $search_value . '%") OR ' .
					'(sr.email LIKE "%' . $search_value . '%") OR ' .
					'(sr.father_name LIKE "%' . $search_value . '%") OR ' .
					'(sr.father_phone LIKE "%' . $search_value . '%") OR ' .
					'(u.user_email LIKE "%' . $search_value . '%") OR ' .
					'(u.user_login LIKE "%' . $search_value . '%") OR ' .
					'(c.label LIKE "%' . $search_value . '%") OR ' .
					'(se.label LIKE "%' . $search_value . '%") OR ' .
					'(sr.roll_number LIKE "%' . $search_value . '%")';

				$search_value_lowercase = strtolower($search_value);
				if (preg_match('/^inac(|t|ti|tiv|tive)$/', $search_value_lowercase)) {
					$is_active = 0;
				} elseif (preg_match('/^acti(|v|ve)$/', $search_value_lowercase)) {
					$is_active = 1;
				}
				if (isset($is_active)) {
					$condition .= ' OR (sr.is_active = ' . $is_active . ')';
				}

				$admission_date = DateTime::createFromFormat(WLSM_Config::date_format(), $search_value);

				if ($admission_date) {
					$format_admission_date = 'Y-m-d';
				} else {
					if ('d-m-Y' === WLSM_Config::date_format()) {
						if (!$admission_date) {
							$admission_date        = DateTime::createFromFormat('m-Y', $search_value);
							$format_admission_date = 'Y-m';
						}
					} elseif ('d/m/Y' === WLSM_Config::date_format()) {
						if (!$admission_date) {
							$admission_date        = DateTime::createFromFormat('m/Y', $search_value);
							$format_admission_date = 'Y-m';
						}
					} elseif ('Y-m-d' === WLSM_Config::date_format()) {
						if (!$admission_date) {
							$admission_date        = DateTime::createFromFormat('Y-m', $search_value);
							$format_admission_date = 'Y-m';
						}
					} elseif ('Y/m/d' === WLSM_Config::date_format()) {
						if (!$admission_date) {
							$admission_date        = DateTime::createFromFormat('Y/m', $search_value);
							$format_admission_date = 'Y-m';
						}
					}

					if (!$admission_date) {
						$admission_date        = DateTime::createFromFormat('Y', $search_value);
						$format_admission_date = 'Y';
					}
				}

				if ($admission_date && isset($format_admission_date)) {
					$admission_date = $admission_date->format($format_admission_date);
					$admission_date = ' OR (sr.admission_date LIKE "%' . $admission_date . '%")';

					$condition .= $admission_date;
				}

				$query_filter .= (' HAVING ' . $condition);
			}
		}

		// Ordering.
		$columns = array('sr.name', 'sr.name', 'sr.admission_number', 'sr.student_type', 'sr.phone', 'sr.email', 'c.label', 'se.label', 'sr.roll_number', 'sr.father_name', 'sr.father_phone', 'u.user_email', 'u.user_login', 'sr.admission_date', 'sr.enrollment_number', 'sr.is_active', 'sr.from_front');
		if (isset($_POST['order']) && isset($columns[$_POST['order']['0']['column']])) {
			$order_by  = sanitize_text_field($columns[$_POST['order']['0']['column']]);
			$order_dir = sanitize_text_field($_POST['order']['0']['dir']);

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY sr.ID DESC';
		}

		// Limiting.
		$limit = '';
		if (-1 != $_POST['length']) {
			$start  = absint($_POST['start']);
			$length = absint($_POST['length']);

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		// $rows_query = WLSM_M_Staff_Accountant::fetch_expense_query_count($school_id, $session_id,  $start_date, $end_date);
		$rows_query = WLSM_M_Staff_General::fetch_students_birthdays_count($school_id, $session_id);

		$expense_total = $wpdb->get_var($wpdb->prepare('SELECT COALESCE(SUM(we.amount), 0) as sum FROM ' . WLSM_EXPENSES . ' as we WHERE we.school_id ='.$school_id.' AND we.expense_date BETWEEN ' . "'$start_date'" . ' AND ' . "'$end_date'"));

		// Total rows count.
		$total_rows_count = $wpdb->get_var($rows_query);

		// Filtered rows count.
		if ($condition) {
			$filter_rows_count = $wpdb->get_var($rows_query . ' AND (' . $condition . ')');
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results($query_filter . $limit);

		$data = array();

		if (count($filter_rows_limit)) {
			foreach ($filter_rows_limit as $row) {
				// Table columns.
				$data[] = array(
					esc_html($row->admission_number),
					esc_html($row->student_name),
					esc_html($row->class_label),
					esc_html($row->section_label),
					esc_html($row->phone),
					esc_html($row->dob),
					esc_html($row->email),

				);
			}
		}

		$output = array(
			'draw'            => intval($_POST['draw']),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
			'export'          => array(
				'nonce'  => wp_create_nonce('export-staff-expenses-table'),
				'action' => 'wlsm-export-staff-expenses-table',
				'filter' => ''
			),
			'total'           => $expense_total,
		);

		echo json_encode($output);
		die();
	}

	public static function save_expense() {
		$current_user = WLSM_M_Role::can('manage_expenses');

		if (!$current_user) {
			die();
		}

		$school_id = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$expense_id = isset($_POST['expense_id']) ? absint($_POST['expense_id']) : 0;

			if ($expense_id) {
				if (!wp_verify_nonce($_POST['edit-expense-' . $expense_id], 'edit-expense-' . $expense_id)) {
					die();
				}
			} else {
				if (!wp_verify_nonce($_POST['add-expense'], 'add-expense')) {
					die();
				}
			}

			// Checks if expense exists.
			if ($expense_id) {
				$expense = WLSM_M_Staff_Accountant::get_expense($school_id, $expense_id);

				if (!$expense) {
					throw new Exception(esc_html__('Expense not found.', 'school-management'));
				}
			}

			$label          = isset($_POST['label']) ? sanitize_text_field($_POST['label']) : '';
			$category_id    = isset($_POST['category_id']) ? absint($_POST['category_id']) : 0;
			$amount         = isset($_POST['amount']) ? WLSM_Config::sanitize_money($_POST['amount']) : 0;
			$invoice_number = isset($_POST['invoice_number']) ? sanitize_text_field($_POST['invoice_number']) : '';
			$expense_date   = isset($_POST['expense_date']) ? DateTime::createFromFormat(WLSM_Config::date_format(), sanitize_text_field($_POST['expense_date'])) : NULL;
			$note           = isset($_POST['note']) ? sanitize_text_field($_POST['note']) : '';

			$attachment        = (isset($_FILES['attachment']) && is_array($_FILES['attachment'])) ? $_FILES['attachment'] : NULL;

			// Start validation.
			$errors = array();

			if (isset($attachment['tmp_name']) && !empty($attachment['tmp_name'])) {
				if (!WLSM_Helper::is_valid_file($attachment, 'attachment')) {
					$errors['attachment'] = esc_html__('File type is not supported.', 'school-management');
				}
			}

			if (empty($label)) {
				$errors['label'] = esc_html__('Please specify expense title.', 'school-management');
			}
			if (strlen($label) > 100) {
				$errors['label'] = esc_html__('Maximum length cannot exceed 100 characters.', 'school-management');
			}

			if (empty($category_id)) {
				$category_id = NULL;
			} else {
				$category = WLSM_M_Staff_Accountant::get_expense_category($school_id, $category_id);
				if (!$category) {
					$errors['category_id'] = esc_html__('Please select a valid category.', 'school-management');
				}
			}

			if ($amount <= 0) {
				$errors['amount'] = esc_html__('Please specify a valid amount.', 'school-management');
			}

			if (strlen($invoice_number) > 80) {
				$errors['invoice_number'] = esc_html__('Maximum length cannot exceed 80 characters.', 'school-management');
			}

			if (empty($expense_date)) {
				$errors['expense_date'] = esc_html__('Please provide expense date.', 'school-management');
			} else {
				$expense_date = $expense_date->format('Y-m-d');
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		if (count($errors) < 1) {
			try {
				$wpdb->query('BEGIN;');

				if ($expense_id) {
					$message = esc_html__('Expense updated successfully.', 'school-management');
					$reset   = false;
				} else {
					$message = esc_html__('Expense added successfully.', 'school-management');
					$reset   = true;
				}

				// Expense data.
				$data = array(
					'label'               => $label,
					'expense_category_id' => $category_id,
					'amount'              => $amount,
					'invoice_number'      => $invoice_number,
					'amount'              => $amount,
					'expense_date'        => $expense_date,
					'note'                => $note,
					'session_id'          => $session_id,
				);

				if ($expense_id) {
					$data['updated_at'] = current_time('Y-m-d H:i:s');

					$expense = WLSM_M_Staff_Accountant::fetch_expense( $school_id, $expense_id );

					if (!empty($attachment)) {
						$attachment = media_handle_upload('attachment', 0);
						if (is_wp_error($attachment)) {
							throw new Exception($attachment->get_error_message());
						}
						$data['attachment'] = $attachment;
					} else {
						$data['attachment'] = $expense->attachment;
					}

					$success = $wpdb->update(WLSM_EXPENSES, $data, array('ID' => $expense_id, 'school_id' => $school_id));
				} else {
					$data['added_by'] = get_current_user_id();

					$data['created_at'] = current_time('Y-m-d H:i:s');

					$data['school_id'] = $school_id;

					if (!empty($attachment)) {
						$attachment = media_handle_upload('attachment', 0);
						if (is_wp_error($attachment)) {
							throw new Exception($attachment->get_error_message());
						}
						$data['attachment'] = $attachment;
					}

					$success = $wpdb->insert(WLSM_EXPENSES, $data);
				}

				$buffer = ob_get_clean();
				if (!empty($buffer)) {
					throw new Exception($buffer);
				}

				if (false === $success) {
					throw new Exception($wpdb->last_error);
				}

				$wpdb->query('COMMIT;');

				wp_send_json_success(array('message' => $message, 'reset' => $reset));
			} catch (Exception $exception) {
				$wpdb->query('ROLLBACK;');
				wp_send_json_error($exception->getMessage());
			}
		}
		wp_send_json_error($errors);
	}

	public static function delete_expense() {
		$current_user = WLSM_M_Role::can('manage_expenses');

		if (!$current_user) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$expense_id = isset($_POST['expense_id']) ? absint($_POST['expense_id']) : 0;

			if (!wp_verify_nonce($_POST['delete-expense-' . $expense_id], 'delete-expense-' . $expense_id)) {
				die();
			}

			// Checks if expense exists.
			$expense = WLSM_M_Staff_Accountant::get_expense($school_id, $expense_id);

			if (!$expense) {
				throw new Exception(esc_html__('Expense not found.', 'school-management'));
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		try {
			$wpdb->query('BEGIN;');

			$success = $wpdb->delete(WLSM_EXPENSES, array('ID' => $expense_id));
			$message = esc_html__('Expense deleted successfully.', 'school-management');

			$exception = ob_get_clean();
			if (!empty($exception)) {
				throw new Exception($exception);
			}

			if (false === $success) {
				throw new Exception($wpdb->last_error);
			}

			$wpdb->query('COMMIT;');

			wp_send_json_success(array('message' => $message));
		} catch (Exception $exception) {
			$wpdb->query('ROLLBACK;');
			wp_send_json_error($exception->getMessage());
		}
	}

	public static function view_expense_note() {
		$current_user = WLSM_M_Role::can('manage_expenses');

		if (!$current_user) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$expense_id = isset($_POST['expense_id']) ? absint($_POST['expense_id']) : 0;

			if (!wp_verify_nonce($_POST['view-expense-note-' . $expense_id], 'view-expense-note-' . $expense_id)) {
				die();
			}

			// Checks if expense exists.
			$expense = WLSM_M_Staff_Accountant::get_expense_note($school_id, $expense_id);

			if (!$expense) {
				throw new Exception(esc_html__('Expense not found.', 'school-management'));
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		wp_send_json_success(esc_html(WLSM_Config::get_note_text($expense->note)));
	}

	public static function fetch_income() {
		$current_user = WLSM_M_Role::can('manage_income');

		if (!$current_user) {
			die();
		}

		$school_id = $current_user['school']['id'];

		$start_date = isset($_POST['start_date']) ? DateTime::createFromFormat(WLSM_Config::date_format(), sanitize_text_field($_POST['start_date'])) : NULL;
		$end_date = isset($_POST['end_date']) ? DateTime::createFromFormat(WLSM_Config::date_format(), sanitize_text_field($_POST['end_date'])) : NULL;

		$from_table = isset($_POST['from_table']) ? (bool) ($_POST['from_table']) : 0;

		$output = array(
			'draw'            => 1,
			'recordsTotal'    => 0,
			'recordsFiltered' => 0,
			'data'            => array(),
		);
		if ($start_date) {
			$start_date = $start_date->format('Y-m-d');
		}
		if ($end_date) {
			$end_date = $end_date->format('Y-m-d');
		}

		global $wpdb;

		$page_url = WLSM_M_Staff_Accountant::get_income_page_url();

		$query = WLSM_M_Staff_Accountant::fetch_income_query($school_id, $start_date, $end_date);

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Accountant::fetch_income_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if (isset($_POST['search']['value'])) {
			$search_value = sanitize_text_field($_POST['search']['value']);
			if ('' !== $search_value) {
				$condition .= '' .
					'(im.label LIKE "%' . $search_value . '%") OR ' .
					'(im.invoice_number LIKE "%' . $search_value . '%") OR ' .
					'(im.amount LIKE "%' . $search_value . '%") OR ' .
					'(im.note LIKE "%' . $search_value . '%") OR ' .
					'(ic.label LIKE "%' . $search_value . '%")';

				$income_date = DateTime::createFromFormat(WLSM_Config::date_format(), $search_value);

				if ($income_date) {
					$format_income_date = 'Y-m-d';
				} else {
					if ('d-m-Y' === WLSM_Config::date_format()) {
						if (!$income_date) {
							$income_date        = DateTime::createFromFormat('m-Y', $search_value);
							$format_income_date = 'Y-m';
						}
					} else if ('d/m/Y' === WLSM_Config::date_format()) {
						if (!$income_date) {
							$income_date        = DateTime::createFromFormat('m/Y', $search_value);
							$format_income_date = 'Y-m';
						}
					} else if ('Y-m-d' === WLSM_Config::date_format()) {
						if (!$income_date) {
							$income_date        = DateTime::createFromFormat('Y-m', $search_value);
							$format_income_date = 'Y-m';
						}
					} else if ('Y/m/d' === WLSM_Config::date_format()) {
						if (!$income_date) {
							$income_date        = DateTime::createFromFormat('Y/m', $search_value);
							$format_income_date = 'Y-m';
						}
					}

					if (!$income_date) {
						$income_date        = DateTime::createFromFormat('Y', $search_value);
						$format_income_date = 'Y';
					}
				}

				if ($income_date && isset($format_income_date)) {
					$income_date = $income_date->format($format_income_date);
					$income_date = ' OR (im.income_date LIKE "%' . $income_date . '%")';

					$condition .= $income_date;
				}

				$query_filter .= (' HAVING ' . $condition);
			}
		}

		// Ordering.
		$columns = array('im.label', 'im.label', 'im.amount', 'im.invoice_number', 'im.invoice_date', 'im.note');
		if (isset($_POST['order']) && isset($columns[$_POST['order']['0']['column']])) {
			$order_by  = sanitize_text_field($columns[$_POST['order']['0']['column']]);
			$order_dir = sanitize_text_field($_POST['order']['0']['dir']);

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY im.ID DESC';
		}

		// Limiting.
		$limit = '';
		if (-1 != $_POST['length']) {
			$start  = absint($_POST['start']);
			$length = absint($_POST['length']);

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Accountant::fetch_income_query_count($school_id);

		// Total rows count.
		$total_rows_count = $wpdb->get_var($rows_query);

		// Filtered rows count.
		if ($condition) {
			$filter_rows_count = $wpdb->get_var($rows_query . ' AND (' . $condition . ')');
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results($query_filter . $limit);

		if ($date_from && $date_to) {
			$filter_rows_limit = $wpdb->get_results($query_filter);
		}

		$data = array();

		if (count($filter_rows_limit)) {
			foreach ($filter_rows_limit as $row) {
				if ($row->note) {
					$view_note = '<a class="text-primary wlsm-view-income-note" data-nonce="' . esc_attr(wp_create_nonce('view-income-note-' . $row->ID)) . '" data-income="' . esc_attr($row->ID) . '" href="#" data-message-title="' . esc_attr__('Income Note', 'school-management') . '" data-close="' . esc_attr__('Close', 'school-management') . '"><span class="dashicons dashicons-search"></span></a>';
				} else {
					$view_note = '-';
				}

				// Table columns.
				$data[] = array(
					esc_html(WLSM_M_Staff_Accountant::get_label_text($row->label)),
					esc_html(WLSM_M_Staff_Accountant::get_category_label_text($row->income_category)),
					esc_html(WLSM_Config::get_money_text($row->amount, $school_id)),
					esc_html($row->invoice_number),
					esc_html(WLSM_Config::get_date_text($row->income_date)),
					$view_note,
					'<a class="text-primary" href="' . esc_url($page_url . "&action=save&id=" . $row->ID) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-income" data-nonce="' . esc_attr(wp_create_nonce('delete-income-' . $row->ID)) . '" data-income="' . esc_attr($row->ID) . '" href="#" data-message-title="' . esc_attr__('Please Confirm!', 'school-management') . '" data-message-content="' . esc_attr__('This will delete the income.', 'school-management') . '" data-cancel="' . esc_attr__('Cancel', 'school-management') . '" data-submit="' . esc_attr__('Confirm', 'school-management') . '"><span class="dashicons dashicons-trash"></span></a>'
				);
			}
		}

		$output = array(
			'draw'            => intval($_POST['draw']),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
			'export'          => array(
				'nonce'  => wp_create_nonce('export-staff-income-table'),
				'action' => 'wlsm-export-staff-income-table',
				'filter' => ''
			)
		);

		echo json_encode($output);
		die();
	}

	public static function save_income() {
		$current_user = WLSM_M_Role::can('manage_income');

		if (!$current_user) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$income_id = isset($_POST['income_id']) ? absint($_POST['income_id']) : 0;

			if ($income_id) {
				if (!wp_verify_nonce($_POST['edit-income-' . $income_id], 'edit-income-' . $income_id)) {
					die();
				}
			} else {
				if (!wp_verify_nonce($_POST['add-income'], 'add-income')) {
					die();
				}
			}

			// Checks if income exists.
			if ($income_id) {
				$income = WLSM_M_Staff_Accountant::get_income($school_id, $income_id);

				if (!$income) {
					throw new Exception(esc_html__('Income not found.', 'school-management'));
				}
			}

			$label          = isset($_POST['label']) ? sanitize_text_field($_POST['label']) : '';
			$category_id    = isset($_POST['category_id']) ? absint($_POST['category_id']) : 0;
			$amount         = isset($_POST['amount']) ? WLSM_Config::sanitize_money($_POST['amount']) : 0;
			$invoice_number = isset($_POST['invoice_number']) ? sanitize_text_field($_POST['invoice_number']) : '';
			$income_date   = isset($_POST['income_date']) ? DateTime::createFromFormat(WLSM_Config::date_format(), sanitize_text_field($_POST['income_date'])) : NULL;
			$note           = isset($_POST['note']) ? sanitize_text_field($_POST['note']) : '';
			$attachment        = (isset($_FILES['attachment']) && is_array($_FILES['attachment'])) ? $_FILES['attachment'] : NULL;

			// Start validation.
			$errors = array();

			if (isset($attachment['tmp_name']) && !empty($attachment['tmp_name'])) {
				if (!WLSM_Helper::is_valid_file($attachment, 'attachment')) {
					$errors['attachment'] = esc_html__('File type is not supported.', 'school-management');
				}
			}

			if (empty($label)) {
				$errors['label'] = esc_html__('Please specify income title.', 'school-management');
			}
			if (strlen($label) > 100) {
				$errors['label'] = esc_html__('Maximum length cannot exceed 100 characters.', 'school-management');
			}

			if (empty($category_id)) {
				$category_id = NULL;
			} else {
				$category = WLSM_M_Staff_Accountant::get_income_category($school_id, $category_id);
				if (!$category) {
					$errors['category_id'] = esc_html__('Please select a valid category.', 'school-management');
				}
			}

			if ($amount <= 0) {
				$errors['amount'] = esc_html__('Please specify a valid amount.', 'school-management');
			}

			if (strlen($invoice_number) > 80) {
				$errors['invoice_number'] = esc_html__('Maximum length cannot exceed 80 characters.', 'school-management');
			}

			if (empty($income_date)) {
				$errors['income_date'] = esc_html__('Please provide income date.', 'school-management');
			} else {
				$income_date = $income_date->format('Y-m-d');
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		if (count($errors) < 1) {
			try {
				$wpdb->query('BEGIN;');

				if ($income_id) {
					$message = esc_html__('Income updated successfully.', 'school-management');
					$reset   = false;
				} else {
					$message = esc_html__('Income added successfully.', 'school-management');
					$reset   = true;
				}

				// Income data.
				$data = array(
					'label'              => $label,
					'income_category_id' => $category_id,
					'amount'             => $amount,
					'invoice_number'     => $invoice_number,
					'amount'             => $amount,
					'income_date'        => $income_date,
					'note'               => $note,
				);

				if ($income_id) {
					$income = WLSM_M_Staff_Accountant::fetch_income( $school_id, $income_id );

					if (!empty($attachment)) {
						$attachment = media_handle_upload('attachment', 0);
						if (is_wp_error($attachment)) {
							throw new Exception($attachment->get_error_message());
						}
						$data['attachment'] = $attachment;
					} else {
						$data['attachment'] = $income->attachment;
					}

					$data['updated_at'] = current_time('Y-m-d H:i:s');
					$success = $wpdb->update(WLSM_INCOME, $data, array('ID' => $income_id, 'school_id' => $school_id));
				} else {
					$data['added_by'] = get_current_user_id();

					$data['created_at'] = current_time('Y-m-d H:i:s');

					$data['school_id'] = $school_id;

					if (!empty($attachment)) {
						$attachment = media_handle_upload('attachment', 0);
						if (is_wp_error($attachment)) {
							throw new Exception($attachment->get_error_message());
						}
						$data['attachment'] = $attachment;
					}

					$success = $wpdb->insert(WLSM_INCOME, $data);
				}

				$buffer = ob_get_clean();
				if (!empty($buffer)) {
					throw new Exception($buffer);
				}

				if (false === $success) {
					throw new Exception($wpdb->last_error);
				}

				$wpdb->query('COMMIT;');

				wp_send_json_success(array('message' => $message, 'reset' => $reset));
			} catch (Exception $exception) {
				$wpdb->query('ROLLBACK;');
				wp_send_json_error($exception->getMessage());
			}
		}
		wp_send_json_error($errors);
	}

	public static function delete_income() {
		$current_user = WLSM_M_Role::can('manage_income');

		if (!$current_user) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$income_id = isset($_POST['income_id']) ? absint($_POST['income_id']) : 0;

			if (!wp_verify_nonce($_POST['delete-income-' . $income_id], 'delete-income-' . $income_id)) {
				die();
			}

			// Checks if income exists.
			$income = WLSM_M_Staff_Accountant::get_income($school_id, $income_id);

			if (!$income) {
				throw new Exception(esc_html__('Income not found.', 'school-management'));
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		try {
			$wpdb->query('BEGIN;');

			$success = $wpdb->delete(WLSM_INCOME, array('ID' => $income_id));
			$message = esc_html__('Income deleted successfully.', 'school-management');

			$exception = ob_get_clean();
			if (!empty($exception)) {
				throw new Exception($exception);
			}

			if (false === $success) {
				throw new Exception($wpdb->last_error);
			}

			$wpdb->query('COMMIT;');

			wp_send_json_success(array('message' => $message));
		} catch (Exception $exception) {
			$wpdb->query('ROLLBACK;');
			wp_send_json_error($exception->getMessage());
		}
	}

	public static function view_income_note() {
		$current_user = WLSM_M_Role::can('manage_income');

		if (!$current_user) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$income_id = isset($_POST['income_id']) ? absint($_POST['income_id']) : 0;

			if (!wp_verify_nonce($_POST['view-income-note-' . $income_id], 'view-income-note-' . $income_id)) {
				die();
			}

			// Checks if income exists.
			$income = WLSM_M_Staff_Accountant::get_income_note($school_id, $income_id);

			if (!$income) {
				throw new Exception(esc_html__('Income not found.', 'school-management'));
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		wp_send_json_success(esc_html(WLSM_Config::get_note_text($income->note)));
	}

	public static function fetch_fees() {
		$current_user = WLSM_M_Role::can('manage_fees');

		if (!$current_user) {
			die();
		}

		$school_id = $current_user['school']['id'];

		global $wpdb;

		$page_url = WLSM_M_Staff_Accountant::get_fees_page_url();

		$query = WLSM_M_Staff_Accountant::fetch_fee_query($school_id);

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Accountant::fetch_fee_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if (isset($_POST['search']['value'])) {
			$search_value = sanitize_text_field($_POST['search']['value']);
			if ('' !== $search_value) {
				$condition .= '' .
					'(ft.label LIKE "%' . $search_value . '%") OR ' .
					'(ft.amount LIKE "%' . $search_value . '%") OR ' .
					'(ft.period LIKE "%' . $search_value . '%")';

				$query_filter .= (' HAVING ' . $condition);
			}
		}

		// Ordering.
		$columns = array('ft.label', 'ft.amount', 'ft.period');
		if (isset($_POST['order']) && isset($columns[$_POST['order']['0']['column']])) {
			$order_by  = sanitize_text_field($columns[$_POST['order']['0']['column']]);
			$order_dir = sanitize_text_field($_POST['order']['0']['dir']);

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY ft.ID DESC';
		}

		// Limiting.
		$limit = '';
		if (-1 != $_POST['length']) {
			$start  = absint($_POST['start']);
			$length = absint($_POST['length']);

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Accountant::fetch_fee_query_count($school_id);

		// Total rows count.
		$total_rows_count = $wpdb->get_var($rows_query);

		// Filtered rows count.
		if ($condition) {
			$filter_rows_count = $wpdb->get_var($rows_query . ' AND (' . $condition . ')');
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results($query_filter . $limit);

		$data = array();

		if (count($filter_rows_limit)) {
			foreach ($filter_rows_limit as $row) {
				// Table columns.
				$data[] = array(
					esc_html(WLSM_M_Staff_Accountant::get_label_text($row->fee_label)),
					esc_html($row->label),
					esc_html(WLSM_Config::get_money_text($row->amount, $school_id)),
					esc_html(WLSM_M_Staff_Accountant::get_fee_period_text($row->period)),
					'<a class="text-primary" href="' . esc_url($page_url . "&action=save&id=" . $row->ID) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-fee" data-nonce="' . esc_attr(wp_create_nonce('delete-fee-' . $row->ID)) . '" data-fee="' . esc_attr($row->ID) . '" href="#" data-message-title="' . esc_attr__('Please Confirm!', 'school-management') . '" data-message-content="' . esc_attr__('This will delete the fee type.', 'school-management') . '" data-cancel="' . esc_attr__('Cancel', 'school-management') . '" data-submit="' . esc_attr__('Confirm', 'school-management') . '"><span class="dashicons dashicons-trash"></span></a>'
				);
			}
		}

		$output = array(
			'draw'            => intval($_POST['draw']),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);

		echo json_encode($output);
		die();
	}

	public static function save_fee() {
		$current_user = WLSM_M_Role::can('manage_fees');

		if (!$current_user) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$fee_id = isset($_POST['fee_id']) ? absint($_POST['fee_id']) : 0;

			if ($fee_id) {
				if (!wp_verify_nonce($_POST['edit-fee-' . $fee_id], 'edit-fee-' . $fee_id)) {
					die();
				}
			} else {
				if (!wp_verify_nonce($_POST['add-fee'], 'add-fee')) {
					die();
				}
			}

			// Checks if fee exists.
			if ($fee_id) {
				$fee = WLSM_M_Staff_Accountant::get_fee($school_id, $fee_id);

				if (!$fee) {
					throw new Exception(esc_html__('Fee not found.', 'school-management'));
				}
			}

			$label               = isset($_POST['label']) ? sanitize_text_field($_POST['label']) : '';
			$amount              = isset($_POST['amount']) ? WLSM_Config::sanitize_money($_POST['amount']) : 0;
			$period              = isset($_POST['period']) ? sanitize_text_field($_POST['period']) : '';
			$class_id            = (isset($_POST['class_id']) && is_array($_POST['class_id'])) ? $_POST['class_id'] : array();
			$active_on_admission = isset($_POST['active_on_admission']) ? (bool) ($_POST['active_on_admission']) : 0;
			$active_on_dashboard = isset($_POST['active_on_dashboard']) ? (bool) ($_POST['active_on_dashboard']) : 0;

			// Start validation.
			$errors = array();

			if (empty($label)) {
				$errors['label'] = esc_html__('Please specify fee type.', 'school-management');
			}
			if (strlen($label) > 100) {
				$errors['label'] = esc_html__('Maximum length cannot exceed 100 characters.', 'school-management');
			}

			if ($amount < 0) {
				$amount = 0;
			}

			if (!in_array($period, array_keys(WLSM_Helper::fee_period_list()))) {
				$errors['period'] = esc_html__('Please specify fee period.', 'school-management');
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		if (count($errors) < 1) {
			try {
				$wpdb->query('BEGIN;');

				if ($fee_id) {
					$message = esc_html__('Fee type updated successfully.', 'school-management');
					$reset   = false;
				} else {
					$message = esc_html__('Fee type added successfully.', 'school-management');
					$reset   = true;
				}

				// Fee type data.
				$data = array(
					'label'                => $label,
					'amount'               => $amount,
					'period'               => $period,
					'active_on_admission'  => $active_on_admission,
					'active_on_dashboard'  => $active_on_dashboard,
				);

				if ($fee_id) {
					$data['updated_at'] = current_time('Y-m-d H:i:s');

					$success = $wpdb->update(WLSM_FEES, $data, array('ID' => $fee_id, 'school_id' => $school_id));
				} else {
					foreach ($class_id as $id) {
					$data['class_id']   = $id;

					$data['created_at'] = current_time('Y-m-d H:i:s');

					$data['school_id']  = $school_id;

					$success = $wpdb->insert(WLSM_FEES, $data);
					}
				}

				$buffer = ob_get_clean();
				if (!empty($buffer)) {
					throw new Exception($buffer);
				}

				if (false === $success) {
					throw new Exception($wpdb->last_error);
				}

				$wpdb->query('COMMIT;');

				wp_send_json_success(array('message' => $message, 'reset' => $reset));
			} catch (Exception $exception) {
				$wpdb->query('ROLLBACK;');
				wp_send_json_error($exception->getMessage());
			}
		}
		wp_send_json_error($errors);
	}

	public static function delete_fee() {
		$current_user = WLSM_M_Role::can('manage_fees');

		if (!$current_user) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$fee_id = isset($_POST['fee_id']) ? absint($_POST['fee_id']) : 0;

			if (!wp_verify_nonce($_POST['delete-fee-' . $fee_id], 'delete-fee-' . $fee_id)) {
				die();
			}

			// Checks if fee exists.
			$fee = WLSM_M_Staff_Accountant::get_fee($school_id, $fee_id);

			if (!$fee) {
				throw new Exception(esc_html__('Fee type not found.', 'school-management'));
			}
		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		try {
			$wpdb->query('BEGIN;');

			$success = $wpdb->delete(WLSM_FEES, array('ID' => $fee_id));
			$message = esc_html__('Fee type deleted successfully.', 'school-management');

			$exception = ob_get_clean();
			if (!empty($exception)) {
				throw new Exception($exception);
			}

			if (false === $success) {
				throw new Exception($wpdb->last_error);
			}

			$wpdb->query('COMMIT;');

			wp_send_json_success(array('message' => $message));
		} catch (Exception $exception) {
			$wpdb->query('ROLLBACK;');
			wp_send_json_error($exception->getMessage());
		}
	}
}
