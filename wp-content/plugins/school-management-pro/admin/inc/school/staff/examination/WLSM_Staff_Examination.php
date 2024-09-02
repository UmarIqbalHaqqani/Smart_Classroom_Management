<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_School.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Role.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Config.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Helper.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Examination.php';

class WLSM_Staff_Examination {
	public static function fetch_exams() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		global $wpdb;

		$page_url             = WLSM_M_Staff_Examination::get_exams_page_url();
		$admit_cards_page_url = WLSM_M_Staff_Examination::get_exams_admit_cards_page_url();
		$results_page_url     = WLSM_M_Staff_Examination::get_exams_results_page_url();

		$query = WLSM_M_Staff_Examination::fetch_exam_query( $school_id );

		$current_user = WLSM_M_Role::can( 'assigned_class' );
		if ($current_user) {
			$current_school = $current_user['school'];
			$restrict_to_section = WLSM_M_Role::restrict_to_section($current_school);

			if ($restrict_to_section) {
				$restrict_to_section_detail = WLSM_M_Staff_Class::get_section_by_id($restrict_to_section);
				$class_id = $restrict_to_section_detail->class_id;
				$query = WLSM_M_Staff_Examination::fetch_exam_query_by_class_id( $school_id, $class_id );
			}
		}
		$start_date = $current_user['session']['start_date'];
		$end_date   = $current_user['session']['end_date'];
		$query_filter = $query . ' AND `start_date` BETWEEN DATE("'.$start_date.'") AND DATE("'.$end_date.'")';

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Examination::fetch_exam_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(ex.label LIKE "%' . $search_value . '%") OR ' .
				'(ex.exam_center LIKE "%' . $search_value . '%")';

				$search_value_lowercase = strtolower( $search_value );
				if ( preg_match( '/^inac(|t|ti|tiv|tive)$/', $search_value_lowercase ) ) {
					$is_active = 0;
				} elseif ( preg_match( '/^acti(|v|ve)$/', $search_value_lowercase ) ) {
					$is_active = 1;
				}
				if ( isset( $is_active ) ) {
					$condition .= ' OR (ex.is_active = ' . $is_active . ')';
				}

				$start_date = DateTime::createFromFormat( WLSM_Config::date_format(), $search_value );

				if ( $start_date ) {
					$format_start_date = 'Y-m-d';
				} else {
					if ( 'd-m-Y' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'm-Y', $search_value );
							$format_start_date = 'Y-m';
						}
					} elseif ( 'd/m/Y' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'm/Y', $search_value );
							$format_start_date = 'Y-m';
						}
					} elseif ( 'Y-m-d' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'Y-m', $search_value );
							$format_start_date = 'Y-m';
						}
					} elseif ( 'Y/m/d' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'Y/m', $search_value );
							$format_start_date = 'Y-m';
						}
					}

					if ( ! $start_date ) {
						$start_date        = DateTime::createFromFormat( 'Y', $search_value );
						$format_start_date = 'Y';
					}
				}

				if ( $start_date && isset( $format_start_date ) ) {
					$start_date = $start_date->format( $format_start_date );
					$start_date = ' OR (ex.start_date LIKE "%' . $start_date . '%")';

					$condition .= $start_date;
				}

				$end_date = DateTime::createFromFormat( WLSM_Config::date_format(), $search_value );

				if ( $end_date ) {
					$format_end_date = 'Y-m-d';
				} else {
					if ( 'd-m-Y' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'm-Y', $search_value );
							$format_end_date = 'Y-m';
						}
					} elseif ( 'd/m/Y' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'm/Y', $search_value );
							$format_end_date = 'Y-m';
						}
					} elseif ( 'Y-m-d' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'Y-m', $search_value );
							$format_end_date = 'Y-m';
						}
					} elseif ( 'Y/m/d' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'Y/m', $search_value );
							$format_end_date = 'Y-m';
						}
					}

					if ( ! $end_date ) {
						$end_date        = DateTime::createFromFormat( 'Y', $search_value );
						$format_end_date = 'Y';
					}
				}

				if ( $end_date && isset( $format_end_date ) ) {
					$end_date = $end_date->format( $format_end_date );
					$end_date = ' OR (ex.end_date LIKE "%' . $end_date . '%")';

					$condition .= $end_date;
				}

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'ex.label', 'class_names', 'ex.exam_center', 'ex.start_date', 'ex.end_date', 'ex.time_table_published', 'ex.admit_cards_published', 'ex.results_published' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY ex.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Examination::fetch_exam_query_count( $school_id );

		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' AND (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results( $query_filter . $limit );

		$data = array();

		if ( count( $filter_rows_limit ) ) {
			foreach ( $filter_rows_limit as $row ) {
				$end_date = WLSM_Config::get_date_text( $row->end_date );
				if ( ! $end_date ) {
					$end_date = '-';
				}

				$view_time_table = '<a class="btn wlsm-btn-xs btn-outline-success wlsm-font-bold wlsm-view-exam-time-table-btn" data-nonce="' . esc_attr( wp_create_nonce( 'view-exam-time-table-' . $row->ID ) ) . '" data-exam-time-table="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Exam Time Table', 'school-management' ) . '" data-close="' . esc_attr__( 'Close', 'school-management' ) . '"><i class="fas fa-search"></i> ' . esc_html__( 'View', 'school-management' ) . '</a>';

				$view_admit_cards = '<a class="btn wlsm-btn-xs btn-outline-primary wlsm-font-bold" href="' . esc_url( $admit_cards_page_url . "&action=admit_cards&exam_id=" . $row->ID ) . '"><i class="fas fa-search"></i> ' . esc_html__( 'View', 'school-management' ) . '</a>';

				$view_results_published = '<a class="btn wlsm-btn-xs btn-outline-dark wlsm-font-bold" href="' . esc_url( $results_page_url . "&action=results&exam_id=" . $row->ID ) . '"><i class="fas fa-search"></i> ' . esc_html__( 'View', 'school-management' ) . '</a>';

				// Table columns.
				$data[] = array(
					esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $row->exam_title ) ),
					wp_kses( stripcslashes( $row->class_names ), array( 'br' => array(), 'strong' => array() ) ),
					esc_html( WLSM_M_Staff_Examination::get_exam_center_text( $row->exam_center ) ),
					esc_html( WLSM_Config::get_date_text( $row->start_date ) ),
					esc_html( $end_date ),
					esc_html( WLSM_M_Staff_Examination::get_publish_status_text( $row->time_table_published ) ) . '<br>' . $view_time_table,
					esc_html( WLSM_M_Staff_Examination::get_publish_status_text( $row->admit_cards_published ) ) . '<br>' . $view_admit_cards,
					esc_html( WLSM_M_Staff_Examination::get_publish_status_text( $row->results_published ) ) . '<br>' . $view_results_published,
					esc_html( WLSM_M_Staff_Examination::get_status_text( $row->is_active ) ),
					'<a class="text-primary" href="' . esc_url( $page_url . "&action=save&id=" . $row->ID ) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-exam" data-nonce="' . esc_attr( wp_create_nonce( 'delete-exam-' . $row->ID ) ) . '" data-exam="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Please Confirm!', 'school-management' ) . '" data-message-content="' . esc_attr__( 'This will delete the exam. All associated data with this exam will be lost.', 'school-management' ) . '" data-cancel="' . esc_attr__( 'Cancel', 'school-management' ) . '" data-submit="' . esc_attr__( 'Confirm', 'school-management' ) . '"><span class="dashicons dashicons-trash"></span></a>'
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);

		echo json_encode( $output );
		die();
	}

	public static function fetch_academic_report() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		global $wpdb;
		$page_url             = WLSM_M_Staff_Examination::get_academic_report_page_url();
		$admit_cards_page_url = WLSM_M_Staff_Examination::get_exams_admit_cards_page_url();
		$results_page_url     = WLSM_M_Staff_Examination::get_exams_results_page_url();

		$query = WLSM_M_Staff_Examination::fetch_academic_report_query( $school_id );

		$current_user = WLSM_M_Role::can( 'assigned_class' );

		$query_filter = $query;
		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Examination::fetch_academic_report_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(ar.label LIKE "%' . $search_value . '%") OR ';

				$search_value_lowercase = strtolower( $search_value );
				if ( preg_match( '/^inac(|t|ti|tiv|tive)$/', $search_value_lowercase ) ) {
					$is_active = 0;
				} elseif ( preg_match( '/^acti(|v|ve)$/', $search_value_lowercase ) ) {
					$is_active = 1;
				}
				if ( isset( $is_active ) ) {
					$condition .= ' OR (ar.is_active = ' . $is_active . ')';
				}

				$start_date = DateTime::createFromFormat( WLSM_Config::date_format(), $search_value );

				if ( $start_date ) {
					$format_start_date = 'Y-m-d';
				} else {
					if ( 'd-m-Y' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'm-Y', $search_value );
							$format_start_date = 'Y-m';
						}
					} elseif ( 'd/m/Y' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'm/Y', $search_value );
							$format_start_date = 'Y-m';
						}
					} elseif ( 'Y-m-d' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'Y-m', $search_value );
							$format_start_date = 'Y-m';
						}
					} elseif ( 'Y/m/d' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'Y/m', $search_value );
							$format_start_date = 'Y-m';
						}
					}

					if ( ! $start_date ) {
						$start_date        = DateTime::createFromFormat( 'Y', $search_value );
						$format_start_date = 'Y';
					}
				}

				if ( $start_date && isset( $format_start_date ) ) {
					$start_date = $start_date->format( $format_start_date );
					$start_date = ' OR (ar.start_date LIKE "%' . $start_date . '%")';

					$condition .= $start_date;
				}

				$end_date = DateTime::createFromFormat( WLSM_Config::date_format(), $search_value );

				if ( $end_date ) {
					$format_end_date = 'Y-m-d';
				} else {
					if ( 'd-m-Y' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'm-Y', $search_value );
							$format_end_date = 'Y-m';
						}
					} elseif ( 'd/m/Y' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'm/Y', $search_value );
							$format_end_date = 'Y-m';
						}
					} elseif ( 'Y-m-d' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'Y-m', $search_value );
							$format_end_date = 'Y-m';
						}
					} elseif ( 'Y/m/d' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'Y/m', $search_value );
							$format_end_date = 'Y-m';
						}
					}

					if ( ! $end_date ) {
						$end_date        = DateTime::createFromFormat( 'Y', $search_value );
						$format_end_date = 'Y';
					}
				}

				if ( $end_date && isset( $format_end_date ) ) {
					$end_date = $end_date->format( $format_end_date );
					$end_date = ' OR (ar.end_date LIKE "%' . $end_date . '%")';

					$condition .= $end_date;
				}

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'ar.label' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY ar.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Examination::fetch_academic_report_query_count( $school_id );

		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' AND (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results( $query_filter . $limit );

		$data = array();

		if ( count( $filter_rows_limit ) ) {
			foreach ( $filter_rows_limit as $row ) {

				$view_time_table = '<a class="btn wlsm-btn-xs btn-outline-success wlsm-font-bold wlsm-view-exam-time-table-btn" data-nonce="' . esc_attr( wp_create_nonce( 'view-exam-time-table-' . $row->ID ) ) . '" data-exam-time-table="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Exam Time Table', 'school-management' ) . '" data-close="' . esc_attr__( 'Close', 'school-management' ) . '"><i class="fas fa-search"></i> ' . esc_html__( 'View', 'school-management' ) . '</a>';

				$view_admit_cards = '<a class="btn wlsm-btn-xs btn-outline-primary wlsm-font-bold" href="' . esc_url( $admit_cards_page_url . "&action=admit_cards&exam_id=" . $row->ID ) . '"><i class="fas fa-search"></i> ' . esc_html__( 'View', 'school-management' ) . '</a>';

				$view_results_published = '<a class="btn wlsm-btn-xs btn-outline-dark wlsm-font-bold" href="' . esc_url( $results_page_url . "&action=results&exam_id=" . $row->ID ) . '"><i class="fas fa-search"></i> ' . esc_html__( 'View', 'school-management' ) . '</a>';

				$exams = json_decode($row->exam_ids);
				$exams =WLSM_M_Staff_Examination::get_exam_labels($exams);
				// var_dump($exams); die;
				// Table columns.
				$data[] = array(
					esc_html($row->report_label ),
					esc_html( $row->class_label ),
					esc_html( $exams ),
					esc_html( $row->group_label ),
					'<a class="text-primary" href="' . esc_url( $page_url . "&action=save&id=" . $row->ID ) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-academic-report" data-nonce="' . esc_attr( wp_create_nonce( 'delete-academic-report-' . $row->ID ) ) . '" data-academic-report="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Please Confirm!', 'school-management' ) . '" data-message-content="' . esc_attr__( 'This will delete the academic report. All associated data with this academic report will be lost.', 'school-management' ) . '" data-cancel="' . esc_attr__( 'Cancel', 'school-management' ) . '" data-submit="' . esc_attr__( 'Confirm', 'school-management' ) . '"><span class="dashicons dashicons-trash"></span></a>
					<a class="text-primary" href="' . esc_url( $page_url . "&action=reports&id=" . $row->ID ) . '"><span class="dashicons dashicons-welcome-view-site"></span></a>'
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);

		echo json_encode( $output );
		die();
	}

	public static function fetch_exams_group() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		global $wpdb;

		$page_url             = WLSM_M_Staff_Examination::get_exams_group_url();

		$query = WLSM_M_Staff_Examination::fetch_exam_query_group( $school_id );

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Examination::fetch_exam_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(ex.label LIKE "%' . $search_value . '%") OR ' .
				'(ex.exam_center LIKE "%' . $search_value . '%")';

				$search_value_lowercase = strtolower( $search_value );
				if ( preg_match( '/^inac(|t|ti|tiv|tive)$/', $search_value_lowercase ) ) {
					$is_active = 0;
				} elseif ( preg_match( '/^acti(|v|ve)$/', $search_value_lowercase ) ) {
					$is_active = 1;
				}
				if ( isset( $is_active ) ) {
					$condition .= ' OR (ex.is_active = ' . $is_active . ')';
				}

				$start_date = DateTime::createFromFormat( WLSM_Config::date_format(), $search_value );

				if ( $start_date ) {
					$format_start_date = 'Y-m-d';
				} else {
					if ( 'd-m-Y' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'm-Y', $search_value );
							$format_start_date = 'Y-m';
						}
					} elseif ( 'd/m/Y' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'm/Y', $search_value );
							$format_start_date = 'Y-m';
						}
					} elseif ( 'Y-m-d' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'Y-m', $search_value );
							$format_start_date = 'Y-m';
						}
					} elseif ( 'Y/m/d' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'Y/m', $search_value );
							$format_start_date = 'Y-m';
						}
					}

					if ( ! $start_date ) {
						$start_date        = DateTime::createFromFormat( 'Y', $search_value );
						$format_start_date = 'Y';
					}
				}

				if ( $start_date && isset( $format_start_date ) ) {
					$start_date = $start_date->format( $format_start_date );
					$start_date = ' OR (ex.start_date LIKE "%' . $start_date . '%")';

					$condition .= $start_date;
				}

				$end_date = DateTime::createFromFormat( WLSM_Config::date_format(), $search_value );

				if ( $end_date ) {
					$format_end_date = 'Y-m-d';
				} else {
					if ( 'd-m-Y' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'm-Y', $search_value );
							$format_end_date = 'Y-m';
						}
					} elseif ( 'd/m/Y' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'm/Y', $search_value );
							$format_end_date = 'Y-m';
						}
					} elseif ( 'Y-m-d' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'Y-m', $search_value );
							$format_end_date = 'Y-m';
						}
					} elseif ( 'Y/m/d' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'Y/m', $search_value );
							$format_end_date = 'Y-m';
						}
					}

					if ( ! $end_date ) {
						$end_date        = DateTime::createFromFormat( 'Y', $search_value );
						$format_end_date = 'Y';
					}
				}

				if ( $end_date && isset( $format_end_date ) ) {
					$end_date = $end_date->format( $format_end_date );
					$end_date = ' OR (ex.end_date LIKE "%' . $end_date . '%")';

					$condition .= $end_date;
				}

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'ex.label', 'class_names', 'ex.exam_center', 'ex.start_date', 'ex.end_date', 'ex.time_table_published', 'ex.admit_cards_published', 'ex.results_published' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY ex.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Examination::fetch_exam_group_query_count( $school_id );

		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' AND (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results( $query_filter . $limit );

		$data = array();

		if ( count( $filter_rows_limit ) ) {
			foreach ( $filter_rows_limit as $row ) {

				// Table columns.
				$data[] = array(
					esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $row->label ) ),
					esc_html( WLSM_M_Staff_Examination::get_status_text( $row->is_active ) ),
					'<a class="text-primary" href="' . esc_url( $page_url . "&action=save&id=" . $row->ID ) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-exam-group" data-nonce="' . esc_attr( wp_create_nonce( 'delete-exam-group' . $row->ID ) ) . '" data-exam="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Please Confirm!', 'school-management' ) . '" data-message-content="' . esc_attr__( 'This will delete the exam group. All associated data with this exam will be lost.', 'school-management' ) . '" data-cancel="' . esc_attr__( 'Cancel', 'school-management' ) . '" data-submit="' . esc_attr__( 'Confirm', 'school-management' ) . '"><span class="dashicons dashicons-trash"></span></a>'
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);

		echo json_encode( $output );
		die();
	}

	public static function fetch_exams_admit_cards() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		global $wpdb;

		$page_url = WLSM_M_Staff_Examination::get_exams_admit_cards_page_url();

		$query = WLSM_M_Staff_Examination::fetch_exam_query( $school_id );

		$current_user = WLSM_M_Role::can( 'assigned_class' );
		if ($current_user) {
			$current_school = $current_user['school'];
			$restrict_to_section = WLSM_M_Role::restrict_to_section($current_school);

			if ($restrict_to_section) {
				$restrict_to_section_detail = WLSM_M_Staff_Class::get_section_by_id($restrict_to_section);
				$class_id = $restrict_to_section_detail->class_id;
				$query = WLSM_M_Staff_Examination::fetch_exam_query_by_class_id( $school_id, $class_id );
			}
		}

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Examination::fetch_exam_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(ex.label LIKE "%' . $search_value . '%") OR ' .
				'(ex.exam_center LIKE "%' . $search_value . '%")';

				$start_date = DateTime::createFromFormat( WLSM_Config::date_format(), $search_value );

				if ( $start_date ) {
					$format_start_date = 'Y-m-d';
				} else {
					if ( 'd-m-Y' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'm-Y', $search_value );
							$format_start_date = 'Y-m';
						}
					} elseif ( 'd/m/Y' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'm/Y', $search_value );
							$format_start_date = 'Y-m';
						}
					} elseif ( 'Y-m-d' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'Y-m', $search_value );
							$format_start_date = 'Y-m';
						}
					} elseif ( 'Y/m/d' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'Y/m', $search_value );
							$format_start_date = 'Y-m';
						}
					}

					if ( ! $start_date ) {
						$start_date        = DateTime::createFromFormat( 'Y', $search_value );
						$format_start_date = 'Y';
					}
				}

				if ( $start_date && isset( $format_start_date ) ) {
					$start_date = $start_date->format( $format_start_date );
					$start_date = ' OR (ex.start_date LIKE "%' . $start_date . '%")';

					$condition .= $start_date;
				}

				$end_date = DateTime::createFromFormat( WLSM_Config::date_format(), $search_value );

				if ( $end_date ) {
					$format_end_date = 'Y-m-d';
				} else {
					if ( 'd-m-Y' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'm-Y', $search_value );
							$format_end_date = 'Y-m';
						}
					} elseif ( 'd/m/Y' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'm/Y', $search_value );
							$format_end_date = 'Y-m';
						}
					} elseif ( 'Y-m-d' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'Y-m', $search_value );
							$format_end_date = 'Y-m';
						}
					} elseif ( 'Y/m/d' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'Y/m', $search_value );
							$format_end_date = 'Y-m';
						}
					}

					if ( ! $end_date ) {
						$end_date        = DateTime::createFromFormat( 'Y', $search_value );
						$format_end_date = 'Y';
					}
				}

				if ( $end_date && isset( $format_end_date ) ) {
					$end_date = $end_date->format( $format_end_date );
					$end_date = ' OR (ex.end_date LIKE "%' . $end_date . '%")';

					$condition .= $end_date;
				}

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'ex.label', 'class_names', 'ex.exam_center', 'ex.start_date', 'ex.end_date' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY ex.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Examination::fetch_exam_query_count( $school_id );

		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' AND (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results( $query_filter . $limit );

		$data = array();

		if ( count( $filter_rows_limit ) ) {
			foreach ( $filter_rows_limit as $row ) {
				$end_date = WLSM_Config::get_date_text( $row->end_date );
				if ( ! $end_date ) {
					$end_date = '-';
				}

				$generate_admit_cards = '<a class="btn btn-sm btn-success wlsm-font-bold" href="' . esc_url( $page_url . "&action=generate_admit_cards&exam_id=" . $row->ID ) . '"><i class="fas fa-plus"></i> ' . esc_html__( 'Generate', 'school-management' ) . '</a>';

				$view_admit_cards = '<a class="btn btn-sm btn-primary wlsm-font-bold" href="' . esc_url( $page_url . "&action=admit_cards&exam_id=" . $row->ID ) . '"><i class="fas fa-search"></i> ' . esc_html__( 'View', 'school-management' ) . '</a>';

				// Table columns.
				$data[] = array(
					esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $row->exam_title ) ),
					wp_kses( stripcslashes( $row->class_names ), array( 'br' => array(), 'strong' => array() ) ),
					esc_html( WLSM_M_Staff_Examination::get_exam_center_text( $row->exam_center ) ),
					esc_html( WLSM_Config::get_date_text( $row->start_date ) ),
					esc_html( $end_date ),
					$generate_admit_cards,
					$view_admit_cards
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);

		echo json_encode( $output );
		die();
	}

	public static function fetch_exams_results() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		global $wpdb;

		$page_url = WLSM_M_Staff_Examination::get_exams_results_page_url();

		$query = WLSM_M_Staff_Examination::fetch_exam_query( $school_id );

		$current_user = WLSM_M_Role::can( 'assigned_class' );
		if ($current_user) {
			$current_school = $current_user['school'];
			$restrict_to_section = WLSM_M_Role::restrict_to_section($current_school);

			if ($restrict_to_section) {
				$restrict_to_section_detail = WLSM_M_Staff_Class::get_section_by_id($restrict_to_section);
				$class_id = $restrict_to_section_detail->class_id;
				$query = WLSM_M_Staff_Examination::fetch_exam_query_by_class_id( $school_id, $class_id );
			}
		}

		$start_date = $current_user['session']['start_date'];
		$end_date   = $current_user['session']['end_date'];
		$query_filter = $query . ' AND `start_date` BETWEEN DATE("'.$start_date.'") AND DATE("'.$end_date.'")';

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Examination::fetch_exam_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(ex.label LIKE "%' . $search_value . '%") OR ' .
				'(ex.exam_center LIKE "%' . $search_value . '%")';

				$start_date = DateTime::createFromFormat( WLSM_Config::date_format(), $search_value );

				if ( $start_date ) {
					$format_start_date = 'Y-m-d';
				} else {
					if ( 'd-m-Y' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'm-Y', $search_value );
							$format_start_date = 'Y-m';
						}
					} elseif ( 'd/m/Y' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'm/Y', $search_value );
							$format_start_date = 'Y-m';
						}
					} elseif ( 'Y-m-d' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'Y-m', $search_value );
							$format_start_date = 'Y-m';
						}
					} elseif ( 'Y/m/d' === WLSM_Config::date_format() ) {
						if ( ! $start_date ) {
							$start_date        = DateTime::createFromFormat( 'Y/m', $search_value );
							$format_start_date = 'Y-m';
						}
					}

					if ( ! $start_date ) {
						$start_date        = DateTime::createFromFormat( 'Y', $search_value );
						$format_start_date = 'Y';
					}
				}

				if ( $start_date && isset( $format_start_date ) ) {
					$start_date = $start_date->format( $format_start_date );
					$start_date = ' OR (ex.start_date LIKE "%' . $start_date . '%")';

					$condition .= $start_date;
				}

				$end_date = DateTime::createFromFormat( WLSM_Config::date_format(), $search_value );

				if ( $end_date ) {
					$format_end_date = 'Y-m-d';
				} else {
					if ( 'd-m-Y' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'm-Y', $search_value );
							$format_end_date = 'Y-m';
						}
					} elseif ( 'd/m/Y' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'm/Y', $search_value );
							$format_end_date = 'Y-m';
						}
					} elseif ( 'Y-m-d' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'Y-m', $search_value );
							$format_end_date = 'Y-m';
						}
					} elseif ( 'Y/m/d' === WLSM_Config::date_format() ) {
						if ( ! $end_date ) {
							$end_date        = DateTime::createFromFormat( 'Y/m', $search_value );
							$format_end_date = 'Y-m';
						}
					}

					if ( ! $end_date ) {
						$end_date        = DateTime::createFromFormat( 'Y', $search_value );
						$format_end_date = 'Y';
					}
				}

				if ( $end_date && isset( $format_end_date ) ) {
					$end_date = $end_date->format( $format_end_date );
					$end_date = ' OR (ex.end_date LIKE "%' . $end_date . '%")';

					$condition .= $end_date;
				}

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'ex.label', 'class_names', 'ex.exam_center', 'ex.start_date', 'ex.end_date' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY ex.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Examination::fetch_exam_query_count( $school_id );

		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' AND (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results( $query_filter . $limit );

		$data = array();

		if ( count( $filter_rows_limit ) ) {
			foreach ( $filter_rows_limit as $row ) {
				$end_date = WLSM_Config::get_date_text( $row->end_date );
				if ( ! $end_date ) {
					$end_date = '-';
				}

				$add_results = '<a class="btn btn-sm btn-success wlsm-font-bold" href="' . esc_url( $page_url . "&action=save_results&exam_id=" . $row->ID ) . '"><i class="fas fa-plus"></i> ' . esc_html__( 'Add Results', 'school-management' ) . '</a>';

				$view_results = '<a class="btn btn-sm btn-primary wlsm-font-bold" href="' . esc_url( $page_url . "&action=results&exam_id=" . $row->ID ) . '"><i class="fas fa-search"></i> ' . esc_html__( 'View', 'school-management' ) . '</a>';

				// Table columns.
				$data[] = array(
					esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $row->exam_title ) ),
					wp_kses( stripcslashes( $row->class_names ), array( 'br' => array(), 'strong' => array() ) ),
					esc_html( WLSM_M_Staff_Examination::get_exam_center_text( $row->exam_center ) ),
					esc_html( WLSM_Config::get_date_text( $row->start_date ) ),
					esc_html( $end_date ),
					$add_results,
					$view_results,
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);

		echo json_encode( $output );
		die();
	}

	public static function save_academic_report() {
		$current_user = WLSM_M_Role::can('manage_exams');

		if (!$current_user) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$report_id = isset($_POST['report_id']) ? absint($_POST['report_id']) : 0;

			if ($report_id) {
				if (!wp_verify_nonce($_POST['edit-academic-report' . $report_id], 'edit-academic-report' . $report_id)) {
					die();
				}
			} else {
				if (!wp_verify_nonce($_POST['add-academic-report'], 'add-academic-report')) {
					die();
				}
			}

			// Report.
			$report_title = isset($_POST['label']) ? sanitize_text_field($_POST['label']) : '';
			$class_id     = isset($_POST['class_id']) ? sanitize_text_field($_POST['class_id']) : '';
			$exam_group   = isset($_POST['exam_group']) ? sanitize_text_field($_POST['exam_group']) : '';
			$exams        = isset( $_POST['exams'] ) && is_array( $_POST['exams'] ) ? $_POST['exams'] : array();

			// Start validation.
			$errors = array();

			// Report.
			if (empty($report_title)) {
				$errors['label'] = esc_html__('Please provide report title.', 'school-management');
			} else {
				if (strlen($report_title) > 191) {
					$errors['label'] = esc_html__('Maximum length cannot exceed 191 characters.', 'school-management');
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

				if ($report_id) {
					$message = esc_html__('Academic Report updated successfully.', 'school-management');
					$reset = false;
				} else {
					$message = esc_html__('Academic Report added successfully.', 'school-management');
					$reset = true;
				}

				$exam_ids = json_encode($exams);

				// Report data.
				$report_data = array(
					'label'      => $report_title,
					'class_id'   => $class_id,
					'exam_group' => $exam_group,
					'school_id'  => $school_id,
					'exams'      => $exam_ids,
				);

				if ($report_id) {
					$report_data['updated_at'] = current_time('Y-m-d H:i:s');

					$success = $wpdb->update(WLSM_ACADEMIC_REPORTS, $report_data, array('ID' => $report_id, 'school_id' => $school_id));

					$is_insert = false;
				} else {
					$report_data['created_at'] = current_time('Y-m-d H:i:s');

					$report_data['school_id'] = $school_id;

					$success = $wpdb->insert(WLSM_ACADEMIC_REPORTS, $report_data);

					$report_id = $wpdb->insert_id;

					$is_insert = true;
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


	public static function save_exam() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$exam_id = isset( $_POST['exam_id'] ) ? absint( $_POST['exam_id'] ) : 0;

			if ( $exam_id ) {
				if ( ! wp_verify_nonce( $_POST[ 'edit-exam-' . $exam_id ], 'edit-exam-' . $exam_id ) ) {
					die();
				}
			} else {
				if ( ! wp_verify_nonce( $_POST['add-exam'], 'add-exam' ) ) {
					die();
				}
			}

			// Checks if exam exists.
			if ( $exam_id ) {
				$exam = WLSM_M_Staff_Examination::get_exam( $school_id, $exam_id );

				if ( ! $exam ) {
					throw new Exception( esc_html__( 'Exam not found.', 'school-management' ) );
				}
			}

			// Exam.
			$exam_title  = isset( $_POST['label'] ) ? sanitize_text_field( $_POST['label'] ) : '';
			$exam_center = isset( $_POST['exam_center'] ) ? sanitize_text_field( $_POST['exam_center'] ) : '';
			$exam_group  = isset( $_POST['exam_group'] ) ? sanitize_text_field( $_POST['exam_group'] ) : '';
			$start_date  = isset( $_POST['start_date'] ) ? DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $_POST['start_date'] ) ) : NULL;
			$end_date    = isset( $_POST['end_date'] ) ? DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $_POST['end_date'] ) ) : NULL;
			$classes     = ( isset( $_POST['classes'] ) && is_array( $_POST['classes'] ) ) ? $_POST['classes'] : array();

			$enable_room_numbers   = isset( $_POST['enable_room_numbers'] ) ? (bool) $_POST['enable_room_numbers'] : 0;

			$enable_total_marks     = isset( $_POST['enable_total_marks'] ) ?  intval($_POST['enable_total_marks']) : 0;
			$results_obtained_marks = isset( $_POST['results_obtained_marks'] ) ? intval($_POST['results_obtained_marks']) : 0;

			$admit_cards_published = isset( $_POST['admit_cards_published'] ) ? (bool) $_POST['admit_cards_published'] : 0;
			$time_table_published  = isset( $_POST['time_table_published'] ) ? (bool) $_POST['time_table_published'] : 0;
			$results_published     = isset( $_POST['results_published'] ) ? (bool) $_POST['results_published'] : 0;
			$show_in_assessment    = isset( $_POST['show_in_assessment'] ) ? (bool) $_POST['show_in_assessment'] : 1;
			$is_active             = isset( $_POST['is_active'] ) ? (bool) $_POST['is_active'] : 1;

			$show_rank    = isset( $_POST['show_rank'] ) ? (bool) $_POST['show_rank'] : 1;
			$show_remark  = isset( $_POST['show_remark'] ) ? (bool) $_POST['show_remark'] : 1;
			$show_eremark = isset( $_POST['show_eremark'] ) ? (bool) $_POST['show_eremark'] : 1;

			$psychomotor_analysis    = isset( $_POST['psychomotor_analysis'] ) ? (bool) $_POST['psychomotor_analysis'] : 1;

			$teacher_signature = (isset($_FILES['teacher_signature']) && is_array($_FILES['teacher_signature'])) ? $_FILES['teacher_signature'] : NULL;

			// Exam papers.
			$paper_id      = ( isset( $_POST['paper_id'] ) && is_array( $_POST['paper_id'] ) ) ? $_POST['paper_id'] : array();
			$subject_id    = ( isset( $_POST['subject_id'] ) && is_array( $_POST['subject_id'] ) ) ? $_POST['subject_id'] : array();
			$subject_label = ( isset( $_POST['subject_label'] ) && is_array( $_POST['subject_label'] ) ) ? $_POST['subject_label'] : array();
			$subject_type  = ( isset( $_POST['subject_type'] ) && is_array( $_POST['subject_type'] ) ) ? $_POST['subject_type'] : array();
			$maximum_marks = ( isset( $_POST['maximum_marks'] ) && is_array( $_POST['maximum_marks'] ) ) ? $_POST['maximum_marks'] : array();
			$paper_code    = ( isset( $_POST['paper_code'] ) && is_array( $_POST['paper_code'] ) ) ? $_POST['paper_code'] : array();
			$paper_date    = ( isset( $_POST['paper_date'] ) && is_array( $_POST['paper_date'] ) ) ? $_POST['paper_date'] : array();
			$start_time    = ( isset( $_POST['start_time'] ) && is_array( $_POST['start_time'] ) ) ? $_POST['start_time'] : array();
			$end_time      = ( isset( $_POST['end_time'] ) && is_array( $_POST['end_time'] ) ) ? $_POST['end_time'] : array();
			$room_number   = ( isset( $_POST['room_number'] ) && is_array( $_POST['room_number'] ) ) ? $_POST['room_number'] : array();

			// Enable overall grade.
			$enable_overall_grade = isset( $_POST['enable_overall_grade'] ) ? (bool) $_POST['enable_overall_grade'] : false;

			// Grade criteria.
			$gc_min   = ( isset( $_POST['grade_criteria']['min'] ) && is_array( $_POST['grade_criteria']['min'] ) ) ? $_POST['grade_criteria']['min'] : array();
			$gc_max   = ( isset( $_POST['grade_criteria']['max'] ) && is_array( $_POST['grade_criteria']['max'] ) ) ? $_POST['grade_criteria']['max'] : array();
			$gc_grade = ( isset( $_POST['grade_criteria']['grade'] ) && is_array( $_POST['grade_criteria']['grade'] ) ) ? $_POST['grade_criteria']['grade'] : array();

			// psychomotor
			$psych = ( isset( $_POST['psych']) && is_array( $_POST['psych']) ) ? $_POST['psych']: array();
			$scale = ( isset( $_POST['scale']) && is_array( $_POST['scale']) ) ? $_POST['scale']: array();
			$def   = ( isset( $_POST['def']) && is_array( $_POST['def']) ) ? $_POST['def']: array();

			// Start validation.
			$errors = array();

			if (isset($teacher_signature['tmp_name']) && !empty($teacher_signature['tmp_name'])) {
				if (!WLSM_Helper::is_valid_file($teacher_signature, 'image')) {
					$errors['teacher_signature'] = esc_html__('This file type is not allowed.', 'school-management');
				}
			}

			$current_user = WLSM_M_Role::can( 'assigned_class' );
			// Exam.
			if ( empty( $exam_title ) ) {
				$errors['label'] = esc_html__( 'Please provide exam title.', 'school-management' );
			} else {
				if ( strlen( $exam_title ) > 191 ) {
					$errors['label'] = esc_html__( 'Maximum length cannot exceed 191 characters.', 'school-management' );
				}
			}

			if ( ! empty( $exam_group ) && strlen( $exam_group ) > 60 ) {
				$errors['exam_group'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
			}

			if ( empty( $start_date ) ) {
				$errors['start_date'] = esc_html__( 'Please provide start date of exam.', 'school-management' );
			} else {
				$start_date = $start_date->format( 'Y-m-d' );
			}

			if ( empty( $end_date ) ) {
				$end_date = NULL;
			} else {
				$end_date = $end_date->format( 'Y-m-d' );

				if ( ! empty( $end_date ) && $start_date > $end_date ) {
					$errors['start_date'] = esc_html__( 'Exam start date must be lower than end date.', 'school-management' );
				}
			}

			$class_schools = array();

			if ( count( $classes ) ) {
				foreach ( $classes as $class_id ) {
					$class_school = WLSM_M_Staff_Class::get_class( $school_id, $class_id );
					if ( ! $class_school ) {
						$errors['classes[]'] = esc_html__( 'Class not found.', 'school-management' );
						wp_send_json_error( $errors );
					} else {
						$class_school_id = $class_school->ID;
						array_push( $class_schools, $class_school_id );
					}
				}

				$class_schools = array_unique( $class_schools );
			}

			if ($current_user) {
				$current_school = $current_user['school'];
				$restrict_to_section = WLSM_M_Role::restrict_to_section($current_school);

				if ($restrict_to_section) {
					$restrict_to_section_detail = WLSM_M_Staff_Class::get_section_by_id($restrict_to_section);

					if (($restrict_to_section_detail->class_id )!== $class_id) {
						$errors['classes[]'] = esc_html__("You don't have permission to save for this class.", 'school-management');
					}
				}
			}

			if ( ! count( $class_schools ) ) {
				$errors['classes[]'] = esc_html__( 'Please select at least one class.', 'school-management' );
			}

			if ( count( $errors ) < 1 ) {
				// Exam papers.
				if ( ! count( $paper_code ) ) {
					wp_send_json_error( esc_html__( 'Please add at least one exam paper.', 'school-management' ) );
				} else {
					if ( 1 !== count( array_unique( array( count( $subject_label ), count( $subject_type ), count( $maximum_marks ), count( $paper_code ), count( $paper_date ), count( $start_time ), count( $end_time ), count( $room_number ) ) ) ) ) {
						wp_send_json_error( esc_html__( 'Invalid exam paper.', 'school-management' ) );
					} elseif ( count( $paper_code ) !== count( array_unique( $paper_code ) ) ) {
						wp_send_json_error( esc_html__( 'Paper codes must be different.', 'school-management' ) );
					} else {
						foreach ( $paper_code as $key => $value ) {
							$subject_id[ $key ]    = sanitize_text_field( $subject_id[ $key ] );
							$subject_label[ $key ] = sanitize_text_field( $subject_label[ $key ] );
							$subject_type[ $key ]  = sanitize_text_field( $subject_type[ $key ] );
							$maximum_marks[ $key ] = absint( $maximum_marks[ $key ] );
							$paper_code[ $key ]    = sanitize_text_field( $value );
							$paper_date[ $key ]    = DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $paper_date[ $key ] ) );
							$start_time[ $key ]    = DateTime::createFromFormat( WLSM_Config::get_default_time_format(), sanitize_text_field( $start_time[ $key ] ) );
							$end_time[ $key ]      = DateTime::createFromFormat( WLSM_Config::get_default_time_format(), sanitize_text_field( $end_time[ $key ] ) );
							$room_number[ $key ]   = sanitize_text_field( $room_number[ $key ] );

							if ( empty( $subject_label[ $key ] ) ) {
								wp_send_json_error( esc_html__( 'Please provide subject name.', 'school-management' ) );
							} elseif ( strlen( $subject_label[ $key ] ) > 100 ) {
								wp_send_json_error( esc_html__( 'Maximum length cannot exceed 100 characters.', 'school-management' ) );
							}


							if ( empty( $paper_code[ $key ] ) ) {
								wp_send_json_error( esc_html__( 'Please provide paper code.', 'school-management' ) );
							} elseif ( strlen( $paper_code[ $key ] ) > 40 ) {
								wp_send_json_error( esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' ) );
							}

							if ( $maximum_marks[ $key ] < 1 ) {
								wp_send_json_error( esc_html__( 'Maximum marks must be a positive integer.', 'school-management' ) );
							} elseif ( $maximum_marks[ $key ] > 9999 ) {
								wp_send_json_error( esc_html__( 'Maximum marks must be lower than 10000.', 'school-management' ) );
							}

							if ( ! empty( $room_number[ $key ] ) && ( strlen( $room_number[ $key ] ) > 40 ) ) {
								wp_send_json_error( esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' ) );
							}

							if ( empty( $paper_date[ $key ] ) ) {
								wp_send_json_error( esc_html__( 'Please provide exam paper date.', 'school-management' ) );
							} else {
								$exam_paper_date    = $paper_date[ $key ];
								$paper_date[ $key ] = $exam_paper_date->format( 'Y-m-d' );
							}

							if ( empty( $start_time[ $key ] ) ) {
								$start_time[ $key ] = NULL;
							} else {
								$exam_start_time    = $start_time[ $key ];
								$start_time[ $key ] = $exam_start_time->format( 'H:i:s' );
							}

							if ( empty( $end_time[ $key ] ) ) {
								$end_time[ $key ] = NULL;
							} else {
								$exam_end_time    = $end_time[ $key ];
								$end_time[ $key ] = $exam_end_time->format( 'H:i:s' );
							}
						}
					}
				}
			}

			// Grade criteria.
			if ( 1 !== count( array_unique( array( count( $gc_grade ), count( $gc_min ), count( $gc_max ) ) ) ) ) {
				wp_send_json_error( esc_html__( 'Invalid grade criteria.', 'school-management' ) );
			} else {
				$i      = 0;
				$length = count( $gc_grade );

				$marks_grades = array();
				foreach ( $gc_grade as $key => $grade ) {
					$min = absint( $gc_min[ $key ] );
					$max = absint( $gc_max[ $key ] );

					if ( empty( $grade ) ) {
						wp_send_json_error( esc_html__( 'Please specify grade.', 'school-management' ) );
					}

					if( $min > $max ) {
						wp_send_json_error(
							sprintf(
								/* translators: 1: minimum percentage, 2: maximum percentage */
								__( 'Minimum percentage %1$s must be greater than maximum percentage %2$s.', 'school-management' ), $min, $max
							)
						);
					}

					$last_max = isset( $gc_max[ $key - 1 ] ) ? absint( $gc_max[ $key - 1 ] ) : 0;

					if ( $last_max > 0 && ( ( $last_max + 1 ) !== $min ) ) {
						wp_send_json_error(
							sprintf(
								/* translators: 1: minimum percentage, 2: last maximum percentage, 3: correct value of minimum percentage */
								__( 'Minimum percentage %1$s must be greater than last maximum percentage %2$s by 1%% (which is %3$s).', 'school-management' ), $min, $last_max, $last_max + 1
							)
						);
					}

					if ( 0 === $i ) {
						if ( 0 !== $min ) {
							wp_send_json_error( esc_html__( 'First minimum percentage must be 0 for grade criteria.', 'school-management' ) );
						}
					} elseif ( ( $length - 1 ) === $i ) {
						if ( 100 !== $max ) {
							wp_send_json_error( esc_html__( 'Last maximum percentage must be 100 for grade criteria.', 'school-management' ) );
						}
					}

					$i++;

					array_push(
						$marks_grades,
						array(
							'min'   => $min,
							'max'   => $max,
							'grade' => sanitize_text_field( $grade ),
						)
					);
				}
			}

			$grade_criteria = serialize(
				array(
					'enable_overall_grade' => $enable_overall_grade,
					'marks_grades'         => $marks_grades
				)
			);

			// Psychmotor

			$psychomotor = serialize(
				array(
					'psych' => $psych,
					'scale' => $scale,
					'def'   => $def,
					)
				);


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

				if ( $exam_id ) {
					$message = esc_html__( 'Exam updated successfully.', 'school-management' );
					$reset   = false;
				} else {
					$message = esc_html__( 'Exam added successfully.', 'school-management' );
					$reset   = true;
				}

				// Exam data.
				$exam_data = array(
					'label'                  => $exam_title,
					'exam_center'            => $exam_center,
					'exam_group'             => $exam_group,
					'start_date'             => $start_date,
					'end_date'               => $end_date,
					'grade_criteria'         => $grade_criteria,
					'psychomotor'            => $psychomotor,
					'enable_room_numbers'    => $enable_room_numbers,
					'enable_total_marks'     => $enable_total_marks,
					'results_obtained_marks' => $results_obtained_marks,
					'results_published'      => $results_published,
					'admit_cards_published'  => $admit_cards_published,
					'time_table_published'   => $time_table_published,
					'show_in_assessment'     => $show_in_assessment,
					'is_active'              => $is_active,
					'show_rank'              => $show_rank,
					'show_remark'            => $show_remark,
					'show_eremark'           => $show_eremark,
					'psychomotor_analysis'   => $psychomotor_analysis,
				);

				if (!empty($teacher_signature)) {
					$teacher_signature = media_handle_upload('teacher_signature', 0);
					if (is_wp_error($teacher_signature)) {
						throw new Exception($teacher_signature->get_error_message());
					}
					$exam_data['teacher_signature'] = $teacher_signature;
				}

				if ( $exam_id ) {
					$exam_data['updated_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->update( WLSM_EXAMS, $exam_data, array( 'ID' => $exam_id, 'school_id' => $school_id ) );

					$is_insert = false;
				} else {
					$exam_data['created_at'] = current_time( 'Y-m-d H:i:s' );

					$exam_data['school_id'] = $school_id;

					$success = $wpdb->insert( WLSM_EXAMS, $exam_data );

					$exam_id = $wpdb->insert_id;

					$is_insert = true;
				}

				// Classes.
				if ( count( $class_schools ) > 0 ) {
					$values                      = array();
					$place_holders               = array();
					$place_holders_class_schools = array();
					foreach ( $class_schools as $class_school_id ) {
						array_push( $values, $class_school_id, $exam_id );
						array_push( $place_holders, '(%d, %d)' );
						array_push( $place_holders_class_schools, '%d' );
					}

					// Insert class_school_exam records.
					$sql     = 'INSERT IGNORE INTO ' . WLSM_CLASS_SCHOOL_EXAM . '(class_school_id, exam_id) VALUES ';
					$sql     .= implode( ', ', $place_holders );
					$success = $wpdb->query( $wpdb->prepare( "$sql ", $values ) );

					// Delete class_school_exam records not in array.
					$sql     = 'DELETE FROM ' . WLSM_CLASS_SCHOOL_EXAM . ' WHERE exam_id = %d AND class_school_id NOT IN (' . implode( ', ', $place_holders_class_schools ) . ')';
					array_unshift( $class_schools , $exam_id );
					$success = $wpdb->query( $wpdb->prepare( $sql, $class_schools ) );
				} else {
					// Delete class_school_exam records for exam.
					$success = $wpdb->delete( WLSM_CLASS_SCHOOL_EXAM, array( 'exam_id' => $exam_id ) );
				}

				// Exam papers.
				$place_holders_paper_codes = array();

				$paper_order = 10;
				foreach ( $paper_code as $key => $value ) {
					array_push( $place_holders_paper_codes, '%s' );
					$paper_order++;

					// Exam paper data.
					$exam_paper_data = array(
						'subject_id'    => $subject_id[ $key ],
						'subject_label' => $subject_label[ $key ],
						'subject_type'  => $subject_type[ $key ],
						'maximum_marks' => $maximum_marks[ $key ],
						'paper_date'    => $paper_date[ $key ],
						'start_time'    => $start_time[ $key ],
						'end_time'      => $end_time[ $key ],
						'room_number'   => $room_number[ $key ],
						'paper_order'   => $paper_order,
					);

					if ( $is_insert ) {
						// Exam paper does not exist, insert exam paper.
						$exam_paper_data['paper_code'] = $value;
						$exam_paper_data['exam_id']    = $exam_id;

						$exam_paper_data['created_at'] = current_time( 'Y-m-d H:i:s' );

						$success = $wpdb->insert( WLSM_EXAM_PAPERS, $exam_paper_data );
					} else {
						// Check if exam paper exists for this paper code.
						$exam_paper_exist = $wpdb->get_row( $wpdb->prepare( 'SELECT ep.ID FROM ' . WLSM_EXAM_PAPERS . ' as ep WHERE ep.exam_id = %d AND ep.paper_code = %s', $exam_id, $value ) );

						if ( $exam_paper_exist ) {
							// Exam paper exists, update exam paper.
							$exam_paper_data['updated_at'] = current_time( 'Y-m-d H:i:s' );

							$success = $wpdb->update( WLSM_EXAM_PAPERS, $exam_paper_data, array( 'ID' => $exam_paper_exist->ID, 'exam_id' => $exam_id ) );
						} else {
							// Exam paper does not exist, insert exam paper.
							$exam_paper_data['paper_code'] = $value;
							$exam_paper_data['exam_id']    = $exam_id;

							$exam_paper_data['created_at'] = current_time( 'Y-m-d H:i:s' );

							$success = $wpdb->insert( WLSM_EXAM_PAPERS, $exam_paper_data );
						}
					}
				}

				if ( ! $is_insert ) {
					// Delete exam papers not in paper_code array.
					$exam_id_paper_codes = array_merge( array( $exam_id ), array_values( $paper_code ) );

					$success = $wpdb->query( $wpdb->prepare( 'DELETE FROM ' . WLSM_EXAM_PAPERS . ' WHERE exam_id = %d AND paper_code NOT IN (' . implode( ', ', $place_holders_paper_codes ) . ')', $exam_id_paper_codes ) );
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success( array( 'message' => $message, 'reset' => $reset ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function save_exam_group() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$exam_id = isset( $_POST['exam_id'] ) ? absint( $_POST['exam_id'] ) : 0;

			if ( $exam_id ) {
				if ( ! wp_verify_nonce( $_POST[ 'edit-exam-group' . $exam_id ], 'edit-exam-group' . $exam_id ) ) {
					die();
				}
			} else {
				if ( ! wp_verify_nonce( $_POST['add-exam-group'], 'add-exam-group' ) ) {
					die();
				}
			}

			// Exam.
			$exam_title = isset( $_POST['label'] ) ? sanitize_text_field( $_POST['label'] ) : '';
			$is_active  = isset( $_POST['is_active'] ) ? (bool) $_POST['is_active'] : 1;

			// Start validation.
			$errors = array();

			// Exam.
			if ( empty( $exam_title ) ) {
				$errors['label'] = esc_html__( 'Please provide exam title.', 'school-management' );
			} else {
				if ( strlen( $exam_title ) > 191 ) {
					$errors['label'] = esc_html__( 'Maximum length cannot exceed 191 characters.', 'school-management' );
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

				if ( $exam_id ) {
					$message = esc_html__( 'Exam Group updated successfully.', 'school-management' );
					$reset   = false;
				} else {
					$message = esc_html__( 'Exam Group added successfully.', 'school-management' );
					$reset   = true;
				}

				// Exam data.
				$exam_data = array(
					'label'                 => $exam_title,
					'is_active'             => $is_active,
					'school_id'             => $school_id,
				);

				if ( $exam_id ) {
					$exam_data['updated_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->update( WLSM_EXAMS_GROUP, $exam_data, array( 'ID' => $exam_id, 'school_id' => $school_id ) );

					$is_insert = false;
				} else {
					$exam_data['created_at'] = current_time( 'Y-m-d H:i:s' );

					$exam_data['school_id'] = $school_id;

					$success = $wpdb->insert( WLSM_EXAMS_GROUP, $exam_data );

					$exam_id = $wpdb->insert_id;

					$is_insert = true;
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success( array( 'message' => $message, 'reset' => $reset ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function delete_exams_group() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}
		WLSM_Helper::check_demo();

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$exam_id = isset( $_POST['exam_id'] ) ? absint( $_POST['exam_id'] ) : 0;


			// Checks if exam exists.
			$exam = WLSM_M_Staff_Examination::fetch_exams_group( $school_id, $exam_id );

			if ( ! $exam ) {
				throw new Exception( esc_html__( 'Exam not found.', 'school-management' ) );
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

		try {
			$wpdb->query( 'BEGIN;' );

			$success = $wpdb->delete( WLSM_EXAMS_GROUP, array( 'ID' => $exam_id ) );
			$message = esc_html__( 'Exam group deleted successfully.', 'school-management' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function delete_academic_report() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}
		WLSM_Helper::check_demo();

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$academic_report_id = isset( $_POST['academic_report_id'] ) ? absint( $_POST['academic_report_id'] ) : 0;


			// Checks if academic_report exists.
			$academic_report = WLSM_M_Staff_Examination::get_academic_report( $school_id, $academic_report_id );

			if ( ! $academic_report ) {
				throw new Exception( esc_html__( 'Exam not found.', 'school-management' ) );
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

		try {
			$wpdb->query( 'BEGIN;' );

			$success = $wpdb->delete( WLSM_ACADEMIC_REPORTS, array( 'ID' => $academic_report_id ) );
			$message = esc_html__( 'Report deleted successfully.', 'school-management' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function delete_exam() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}
		WLSM_Helper::check_demo();

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$exam_id = isset( $_POST['exam_id'] ) ? absint( $_POST['exam_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-exam-' . $exam_id ], 'delete-exam-' . $exam_id ) ) {
				die();
			}

			// Checks if exam exists.
			$exam = WLSM_M_Staff_Examination::get_exam( $school_id, $exam_id );

			if ( ! $exam ) {
				throw new Exception( esc_html__( 'Exam not found.', 'school-management' ) );
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

		try {
			$wpdb->query( 'BEGIN;' );

			$success = $wpdb->delete( WLSM_EXAMS, array( 'ID' => $exam_id ) );
			$message = esc_html__( 'Exam deleted successfully.', 'school-management' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function view_exam_time_table() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$exam_id = isset( $_POST['exam_id'] ) ? absint( $_POST['exam_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'view-exam-time-table-' . $exam_id ], 'view-exam-time-table-' . $exam_id ) ) {
				die();
			}

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
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/exam_time_table.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	public static function generate_admit_cards() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$exam_id = isset( $_POST['exam_id'] ) ? absint( $_POST['exam_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'generate-admit-cards-' . $exam_id ], 'generate-admit-cards-' . $exam_id ) ) {
				die();
			}

			// Checks if exam exists.
			$exam = WLSM_M_Staff_Examination::get_exam( $school_id, $exam_id );

			if ( ! $exam ) {
				throw new Exception( esc_html__( 'Exam not found.', 'school-management' ) );
			}

			$exam_classes = WLSM_M_Staff_Examination::fetch_exam_classes( $school_id, $exam_id );

			$admit_cards = WLSM_M_Staff_Examination::get_exam_admit_cards( $school_id, $exam_id );

			$students = WLSM_M_Staff_Class::fetch_active_students_of_classes( $school_id, $session_id, $exam_classes );

			$admit_card_student_ids = array_map( function( $admit_card ) {
				return $admit_card->student_id;
			}, $admit_cards );

			// Classes students without admit cards.
			$students = array_filter( $students, function( $student ) use ( $admit_card_student_ids ) {
				return ( ! in_array( $student->ID, $admit_card_student_ids ) );
			} );

			// Classes student ids without admit cards.
			$classes_student_ids = array_map( function( $student ) {
				return $student->ID;
			}, $students );

			$student_ids         = ( isset( $_POST['student'] ) && is_array( $_POST['student'] ) ) ? $_POST['student'] : array();
			$roll_number_prefix  = isset( $_POST['roll_number_prefix'] ) ? sanitize_text_field( $_POST['roll_number_prefix'] ) : '';
			$staring_roll_number = isset( $_POST['staring_roll_number'] ) ? absint( $_POST['staring_roll_number'] ) : 1;

			// Start validation.
			$errors = array();

			if ( empty( $staring_roll_number ) ) {
				$errors['staring_roll_number'] = esc_html__( 'Please provide starting roll number.', 'school-management' );
			}

			$first_roll_number = $roll_number_prefix . $staring_roll_number;

			if ( strlen( $first_roll_number ) > 37 ) {
				$errors['staring_roll_number'] = esc_html__( 'Roll number length is too large.', 'school-management' );
			}

			if ( ! count( $student_ids ) ) {
				throw new Exception( esc_html__( 'Please select students.', 'school-management' ) );
			} else {
				if ( array_intersect( $student_ids, $classes_student_ids ) != $student_ids ) {
					throw new Exception( esc_html__( 'Invalid selection of students.', 'school-management' ) );
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

				$admit_card_data = array(
					'exam_id' => $exam_id,
				);

				foreach ( $student_ids as $student_id ) {
					$admit_card_data['student_record_id'] = $student_id;
					$admit_card_data['roll_number']       = $roll_number_prefix . $staring_roll_number;

					// Checks if admit card exists with this roll number for the exam.
					$admit_card_exists = $wpdb->get_row(
						$wpdb->prepare( 'SELECT ac.ID FROM ' . WLSM_ADMIT_CARDS . ' as ac WHERE ac.exam_id = %d AND ac.roll_number = %s', $exam_id, $admit_card_data['roll_number'] )
					);

					if ( $admit_card_exists ) {
						throw new Exception(
							sprintf(
								/* translators: %s: exam roll number */
								esc_html__( 'Roll number %s already exists.', 'school-management' ),
								$admit_card_data['roll_number']
							)
						);
					}

					$admit_card_data['created_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->insert( WLSM_ADMIT_CARDS, $admit_card_data );

					$staring_roll_number++;
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$message = esc_html__( 'Admit cards generated successfully.', 'school-management' );
				$reset   = false;

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success( array( 'message' => $message, 'reset' => $reset ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function delete_admit_card() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}
		WLSM_Helper::check_demo();

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$admit_card_id = isset( $_POST['admit_card_id'] ) ? absint( $_POST['admit_card_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-exam-admit-card-' . $admit_card_id ], 'delete-exam-admit-card-' . $admit_card_id ) ) {
				die();
			}

			// Checks if admit card exists.
			$admit_card = WLSM_M_Staff_Examination::get_admit_card( $school_id, $admit_card_id );

			if ( ! $admit_card ) {
				throw new Exception( esc_html__( 'Admit card not found.', 'school-management' ) );
			}

			// Get exam results for admit card.
			$exam_results_ids = WLSM_M_Staff_Examination::get_exam_results_ids_by_admit_card( $school_id, $admit_card_id );

			if ( count( $exam_results_ids ) ) {
				throw new Exception( esc_html__( 'Exam results exist for this admit card.', 'school-management' ) );
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

		try {
			$wpdb->query( 'BEGIN;' );

			$success = $wpdb->delete( WLSM_ADMIT_CARDS, array( 'ID' => $admit_card_id ) );
			$message = esc_html__( 'Admit card deleted successfully.', 'school-management' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function save_admit_card() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$admit_card_id = isset( $_POST['admit_card_id'] ) ? absint( $_POST['admit_card_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'edit-exam-admit-card-' . $admit_card_id ], 'edit-exam-admit-card-' . $admit_card_id ) ) {
				die();
			}

			// Checks if admit card exists.
			$admit_card = WLSM_M_Staff_Examination::get_admit_card( $school_id, $admit_card_id );

			if ( ! $admit_card ) {
				throw new Exception( esc_html__( 'Admit card not found.', 'school-management' ) );
			}

			$exam_id = $admit_card->exam_id;

			$roll_number = isset( $_POST['roll_number'] ) ? sanitize_text_field( $_POST['roll_number'] ) : '';

			// Start validation.
			$errors = array();

			if ( empty( $roll_number ) ) {
				throw new Exception( esc_html__( 'Please provide roll number.', 'school-management' ) );
			} else {
				if ( strlen( $roll_number ) > 40 ) {
					throw new Exception( esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' ) );
				}
			}

			// Checks if admit card exists with this roll number for the exam except this.
			$admit_card_exists = $wpdb->get_row(
				$wpdb->prepare( 'SELECT ac.ID FROM ' . WLSM_ADMIT_CARDS . ' as ac WHERE ac.exam_id = %d AND ac.roll_number = %s AND ac.ID != %d', $exam_id, $roll_number, $admit_card_id )
			);

			if ( $admit_card_exists ) {
				throw new Exception(
					sprintf(
						/* translators: %s: exam roll number */
						esc_html__( 'Roll number %s already exists.', 'school-management' ),
						$roll_number
					)
				);
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

				$message = esc_html__( 'Admit card updated successfully.', 'school-management' );

				// Admit card data.
				$admit_card_data = array(
					'roll_number' => $roll_number,
					'updated_at'  => current_time( 'Y-m-d H:i:s' ),
				);

				$success = $wpdb->update( WLSM_ADMIT_CARDS, $admit_card_data, array( 'ID' => $admit_card_id ) );

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success( array( 'message' => $message ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function print_exam_admit_card() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$admit_card_id = isset( $_POST['admit_card_id'] ) ? absint( $_POST['admit_card_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'print-exam-admit-card-' . $admit_card_id ], 'print-exam-admit-card-' . $admit_card_id ) ) {
				die();
			}

			// Checks if admit card exists.
			$admit_card = WLSM_M_Staff_Examination::fetch_admit_card( $school_id, $admit_card_id );
			$student_id = $admit_card->student_id;

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
			$exam_papers  = WLSM_M_Staff_Examination::fetch_exam_papers_student( $school_id, $exam_id, $student_id );

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
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/exam_admit_card.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	public static function fetch_exam_admit_cards() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		if ( ! wp_verify_nonce( $_REQUEST[ 'security' ], 'wlsm-security' ) ) {
			die();
		}

		$exam_id = isset( $_REQUEST['exam_id'] ) ? absint( $_REQUEST['exam_id'] ) : 0;

		$exam = WLSM_M_Staff_Examination::fetch_exam( $school_id, $exam_id );

		if ( ! $exam ) {
			die;
		}

		$output['data'] = array();

		$admit_cards = WLSM_M_Staff_Examination::get_exam_admit_cards( $school_id, $exam_id );

		foreach ( $admit_cards as $row ) {
			ob_start();
			?>
			<a href="#" class="text-primary wlsm-edit-exam-admit-card" data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit-exam-admit-card-' . $row->ID ) ); ?>" data-exam-admit-card="<?php echo esc_attr( $row->ID ); ?>" data-roll-number="<?php echo esc_attr( WLSM_M_Staff_Class::get_roll_no_text( $row->roll_number ) ); ?>" data-roll-number-label="<?php esc_attr_e( 'Roll Number', 'school-management' ); ?>" data-roll-number-placeholder="<?php esc_attr_e( 'Enter exam roll number', 'school-management' ); ?>" data-roll-number-empty-message="<?php esc_attr_e( 'Please enter exam roll number.', 'school-management' ); ?>" data-message-title="<?php esc_attr_e( 'Edit Exam Roll Number', 'school-management' ); ?>" data-cancel="<?php esc_attr_e( 'Cancel', 'school-management' ); ?>" data-save="<?php esc_attr_e( 'Save', 'school-management' ); ?>" data-error="<?php esc_attr_e( 'Error', 'school-management' ); ?>"><span class="dashicons dashicons-edit"></span></a>
			<?php
			$edit_admit_card = ob_get_clean();

			ob_start();
			?>
			<a class="text-success wlsm-print-exam-admit-card" data-nonce="<?php echo esc_attr( wp_create_nonce( 'print-exam-admit-card-' . $row->ID ) ); ?>" data-exam-admit-card="<?php echo esc_attr( $row->ID ); ?>" href="#" data-message-title="<?php esc_attr_e( 'Print Exam Admit Card', 'school-management' ); ?>" data-close="<?php esc_attr_e( 'Close', 'school-management' ); ?>"><i class="fas fa-print"></i></a>
			<?php
			$print_admit_card = ob_get_clean();

			ob_start();
			?>
			<a class="text-danger wlsm-delete-exam-admit-card" data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete-exam-admit-card-' . $row->ID ) ); ?>" data-exam-admit-card="<?php echo esc_attr( $row->ID ); ?>" href="#" data-message-title="<?php esc_attr_e( 'Please Confirm!', 'school-management' ); ?>" data-message-content="<?php esc_attr_e( 'This will delete this admit card.', 'school-management' ); ?>" data-cancel="<?php esc_attr_e( 'Cancel', 'school-management' ); ?>" data-submit="<?php esc_attr_e( 'Confirm', 'school-management' ); ?>"><span class="dashicons dashicons-trash"></span></a>
			<?php
			$admit_card_action = ob_get_clean();

			$output['data'][] = array(
				esc_html( WLSM_M_Staff_Class::get_name_text( $row->name ) ),
				esc_html( WLSM_M_Staff_Class::get_roll_no_text( $row->roll_number ) ) . $edit_admit_card,
				esc_html( WLSM_M_Class::get_label_text( $row->class_label ) ),
				esc_html( WLSM_M_Staff_Class::get_section_label_text( $row->section_label ) ),
				esc_html( $row->enrollment_number ),
				esc_html( WLSM_M_Staff_Class::get_phone_text( $row->phone ) ),
				esc_html( WLSM_M_Staff_Class::get_name_text( $row->email ) ),
				$print_admit_card,
				$admit_card_action
			);
		}

		echo json_encode( $output );
		die();
	}

	public static function fetch_exam_results() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		global $wpdb;

		$page_url         = WLSM_M_Staff_Examination::get_exams_page_url();
		$results_page_url = WLSM_M_Staff_Examination::get_exams_results_page_url();

		if ( ! wp_verify_nonce( $_REQUEST[ 'security' ], 'wlsm-security' ) ) {
			die();
		}

		$exam_id = isset( $_REQUEST['exam_id'] ) ? absint( $_REQUEST['exam_id'] ) : 0;

		$exam = WLSM_M_Staff_Examination::fetch_exam( $school_id, $exam_id );

		if ( ! $exam ) {
			die;
		}

		$output['data'] = array();

		$exam_results = WLSM_M_Staff_Examination::get_exam_results( $school_id, $exam_id, 'DESC' );

		$student_ranks = WLSM_M_Staff_Examination::calculate_exam_ranks( '', '', $exam_results );

		foreach ( $exam_results as $row ) {
			$percentage = WLSM_Config::get_percentage_text( $row->total_marks, $row->obtained_marks );

			if ( isset( $student_ranks[ $row->ID ] ) ) {
				$rank = $student_ranks[ $row->ID ];
			} else {
				$rank = '-';
			}

			ob_start();
			?>
			<a class="text-success wlsm-print-exam-results" data-nonce="<?php echo esc_attr( wp_create_nonce( 'print-exam-results-' . $row->ID ) ); ?>" data-exam-results="<?php echo esc_attr( $row->ID ); ?>" href="#" data-message-title="<?php esc_attr_e( 'Print Exam Results', 'school-management' ); ?>" data-close="' . esc_attr__( 'Close', 'school-management' ) . '"><i class="fas fa-print"></i></a>&nbsp;
			<a class="text-primary" href="<?php echo esc_url( $results_page_url . "&action=save_results&id=" . $row->ID ); ?>"><span class="dashicons dashicons-edit"></span></a>&nbsp;
			<a class="text-danger wlsm-delete-exam-results" data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete-exam-results-' . $row->ID ) ); ?>" data-exam-admit-card="<?php echo esc_attr( $row->ID ); ?>" href="#" data-message-title="<?php esc_attr_e( 'Please Confirm!', 'school-management' ); ?>" data-message-content="<?php esc_attr_e( 'This will delete exam results for this student.', 'school-management' ); ?>" data-cancel="<?php esc_attr_e( 'Cancel', 'school-management' ); ?>" data-submit="<?php esc_attr_e( 'Confirm', 'school-management' ); ?>"><span class="dashicons dashicons-trash"></span></a>
			<?php
			$exam_results_action = ob_get_clean();

			$output['data'][] = array(
				esc_html( WLSM_M_Staff_Class::get_name_text( $row->name ) ),
				esc_html( WLSM_M_Staff_Class::get_roll_no_text( $row->roll_number ) ),
				esc_html( WLSM_M_Class::get_label_text( $row->class_label ) ),
				esc_html( WLSM_M_Staff_Class::get_section_label_text( $row->section_label ) ),
				esc_html( $percentage ),
				esc_html( $rank ),
				esc_html( $row->enrollment_number ),
				$exam_results_action
			);
		}

		echo json_encode( $output );
		die();
	}

	public static function save_exam_results() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$admit_card_id = isset( $_POST['admit_card_id'] ) ? absint( $_POST['admit_card_id'] ) : 0;

			$obtained_marks = ( isset( $_POST['obtained_marks'] ) && is_array( $_POST['obtained_marks'] ) ) ? $_POST['obtained_marks'] : array();
			$remark = ( isset( $_POST['remark'] ) && is_array( $_POST['remark'] ) ) ? $_POST['remark'] : array();
			$scale = ( isset( $_POST['scale'] ) && is_array( $_POST['scale'] ) ) ? $_POST['scale'] : array();

			$teacher_remark = isset( $_POST['teacher_remark'] ) ? sanitize_text_field( $_POST['teacher_remark'] ) : '';
			$school_remark  = isset( $_POST['school_remark'] ) ? sanitize_text_field( $_POST['school_remark'] ) : '';

			$attachment = (isset($_FILES['attachment']) && is_array($_FILES['attachment'])) ? $_FILES['attachment'] : NULL;

			// Start validation.
			$errors = array();

			if (isset($attachment['tmp_name']) && !empty($attachment['tmp_name'])) {
				if (!WLSM_Helper::is_valid_file($attachment, 'attachment')) {
					$errors['attachment'] = esc_html__('This file type is not allowed.', 'school-management');
				}
			}

			if ( $admit_card_id ) {
				if ( ! wp_verify_nonce( $_POST[ 'save-exam-results-' . $admit_card_id ], 'save-exam-results-' . $admit_card_id ) ) {
					die();
				}

				// Checks if admit card exists.
				$admit_card = WLSM_M_Staff_Examination::fetch_admit_card( $school_id, $admit_card_id );

				if ( ! $admit_card ) {
					throw new Exception( esc_html__( 'Admit card not found.', 'school-management' ) );
				}

				$message = esc_html__( 'Exam results updated successfully.', 'school-management' );
				$reload  = false;

			} else {
				if ( ! wp_verify_nonce( $_POST[ 'save-exam-results' ], 'save-exam-results' ) ) {
					die();
				}

				$admit_card_id = isset( $_POST['student_admit_card_id'] ) ? absint( $_POST['student_admit_card_id'] ) : 0;

				$admit_card = WLSM_M_Staff_Examination::fetch_admit_card( $school_id, $admit_card_id );

				if ( ! $admit_card ) {
					$errors['student_admit_card_id'] = esc_html__( 'Please select student.', 'school-management' );
					wp_send_json_error( $errors );
				}

				$message = esc_html__( 'Exam results added successfully.', 'school-management' );
				$reload  = true;
			}

			$exam_id = $admit_card->exam_id;

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

		$scale = serialize($scale);

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				foreach ( $exam_papers as $exam_paper ) {
					if ( isset( $obtained_marks[ $exam_paper->ID ] ) ) {

						$marks_obtained = WLSM_Config::sanitize_marks( $obtained_marks[ $exam_paper->ID ] );

						if ( $exam_paper->maximum_marks < $marks_obtained ) {
							throw new Exception( esc_html__( 'Obtained marks can\'t be greater than maximum marks.', 'school-management' ) );
						}

						if ( isset( $exam_results[ $exam_paper->ID ] ) ) {
							// If result exists, update.
							$exam_result = $exam_results[ $exam_paper->ID ];

							// $attachment = media_handle_upload('attachment', 0);
							// if (is_wp_error($attachment)) {
							// 	throw new Exception($attachment->get_error_message());
							// }

							$allowed_mimes = array(
								'jpg|jpeg|jpe'	=> 'image/jpeg',
								'gif'			=> 'image/gif',
								'png'			=> 'image/png',
								'pdf'			=> 'application/pdf',
							);

							$attachment = wp_handle_sideload( $_FILES['attachment'], array(
									'test_form' => false,
									'mimes'		=> $allowed_mimes,
									'unique_filename_callback' => 'some_string'.rand(2, 4),
								)
							);

							$exam_result_data = array(
								'obtained_marks' => $marks_obtained,
								'remark'         => $remark[$exam_paper->ID],
								'scale'          => $scale,
								'teacher_remark' => $teacher_remark,
								'school_remark'  => $school_remark,
								'answer_key'     => $attachment['url'],
								'updated_at'     => current_time( 'Y-m-d H:i:s' )
							);

							$success = $wpdb->update( WLSM_EXAM_RESULTS, $exam_result_data, array( 'ID' => $exam_result->ID ) );

						} else {
							// If result do not exist, insert.

							$allowed_mimes = array(
								'jpg|jpeg|jpe'	=> 'image/jpeg',
								'gif'			=> 'image/gif',
								'png'			=> 'image/png',
								'pdf'			=> 'application/pdf',
							);

							$attachment = wp_handle_sideload( $_FILES['attachment'], array(
									'test_form' => false,
									'mimes'		=> $allowed_mimes,
									'unique_filename_callback' => 'some_string'.rand(2, 4),
								)
							);
							$exam_result_data = array(
								'obtained_marks' => $marks_obtained,
								'teacher_remark' => $teacher_remark,
								'school_remark'  => $school_remark,
								'scale'          => $scale,
								'scale'          => $scale,
								'remark'         => $remark[$exam_paper->ID],
								'exam_paper_id'  => $exam_paper->ID,
								'answer_key'     => $attachment['url'],
								'admit_card_id'  => $admit_card_id
							);

							$exam_result_data['created_at'] = current_time( 'Y-m-d H:i:s' );

							$success = $wpdb->insert( WLSM_EXAM_RESULTS, $exam_result_data );
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

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success( array( 'message' => $message, 'reload' => $reload ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function delete_exam_results() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}
		WLSM_Helper::check_demo();

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$admit_card_id = isset( $_POST['admit_card_id'] ) ? absint( $_POST['admit_card_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-exam-results-' . $admit_card_id ], 'delete-exam-results-' . $admit_card_id ) ) {
				die();
			}

			// Checks if admit card exists.
			$admit_card = WLSM_M_Staff_Examination::get_admit_card( $school_id, $admit_card_id );

			if ( ! $admit_card ) {
				throw new Exception( esc_html__( 'Admit card not found.', 'school-management' ) );
			}

			// Get exam results for admit card.
			$exam_results_ids = WLSM_M_Staff_Examination::get_exam_results_ids_by_admit_card( $school_id, $admit_card_id );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		try {
			$wpdb->query( 'BEGIN;' );

			$exam_results_ids_count = count( $exam_results_ids );

			$place_holders = array_fill( 0, $exam_results_ids_count, '%d' );

			$exam_results_ids_format = implode( ', ', $place_holders );

			$success = $wpdb->query( $wpdb->prepare( 'DELETE FROM ' . WLSM_EXAM_RESULTS . ' WHERE ID IN( ' . $exam_results_ids_format . ') ', $exam_results_ids ) );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			$message = esc_html__( 'Exam results deleted successfully.', 'school-management' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function print_exam_results() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$admit_card_id = isset( $_POST['admit_card_id'] ) ? absint( $_POST['admit_card_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'print-exam-results-' . $admit_card_id ], 'print-exam-results-' . $admit_card_id ) ) {
				die();
			}

			// Checks if admit card exists.
			$admit_card = WLSM_M_Staff_Examination::fetch_admit_card( $school_id, $admit_card_id );

			if ( ! $admit_card ) {
				throw new Exception( esc_html__( 'Admit card not found.', 'school-management' ) );
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
			$teacher_signature = $exam->teacher_signature;

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
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/exam_results.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	public static function bulk_print_result(){
		$current_user = WLSM_M_Role::can('manage_exams');

		if (!$current_user) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];
		$session_label = $current_user['session']['label'];

		if (!wp_verify_nonce($_POST['print-result'], 'print-result')) {
			die();
		}

		$class_id    = isset($_POST['class_id']) ? absint($_POST['class_id']) : 0;
		$section_id  = isset($_POST['section_id']) ? absint($_POST['section_id']) : 0;
		$exam_id     = isset($_POST['exam']) ? absint($_POST['exam']) : 0;

		try {
			ob_start();
			global $wpdb;

			// Start validation.
			$errors = array();

			if ( empty( $exam_id )) {
				$errors[ 'exam' ] = esc_html__( 'Please select a exam.', 'school-management' );
			}

			if ( empty( $class_id )) {
				$errors['class_id'] = esc_html__('Please select a class.', 'school-management');
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
			$results = WLSM_M_Staff_Examination::get_exam_results( $school_id, $exam_id, $section_id );
			$exam = WLSM_M_Staff_Examination::fetch_exam( $school_id, $exam_id );

			if ( ! $exam ) {
				throw new Exception( esc_html__( 'Exam not found.', 'school-management' ) );
			}

			// $exam_classes = WLSM_M_Staff_Examination::fetch_exam_classes_label( $school_id, $exam_id );
			// $exam_papers  = WLSM_M_Staff_Examination::fetch_exam_papers( $school_id, $exam_id );
			$exam = WLSM_M_Staff_Examination::fetch_exam( $school_id, $exam_id );
			$psychomotor =  WLSM_Config::sanitize_psychomotor( $exam->psychomotor );

			$bulk_print = true;
			ob_start();
			require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/bulk-results.php';
			$html = ob_get_clean();

			$json = json_encode(array(
				'message_title' => esc_html__('Print Result', 'school-management'),
			));

			wp_send_json_success(array('html' => $html, 'json' => $json));
		}

		wp_send_json_error($errors);
	}

	public static function get_results_assessment() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		$session_label = $current_user['session']['label'];

		if ( ! wp_verify_nonce( $_POST[ 'nonce' ], 'get-results-assessment' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$class_id   = isset( $_POST['class_id'] ) ? absint( $_POST['class_id'] ) : 0;
			$section_id = isset( $_POST['section_id'] ) ? absint( $_POST['section_id'] ) : 0;

			// Start validation.
			$errors = array();

			if ( empty( $class_id ) ) {
				$errors['class_id'] = esc_html__( 'Please select a class.', 'school-management' );
			} else {
				$class_school = WLSM_M_School::get_class_school( $class_id, $school_id );
				if ( ! $class_school ) {
					$errors['class_id'] = esc_html__( 'Class not found.', 'school-management' );
				} else {
					$class_school_id = $class_school->ID;
					if ( ! empty( $section_id ) ) {
						$section = WLSM_M_Staff_Class::fetch_section( $school_id, $section_id, $class_school_id );
						if ( ! $section ) {
							$errors['section_id'] = esc_html__( 'Section not found.', 'school-management' );
						} else {
							$section_label = $section->label;
						}
					} else {
						$section_label = esc_html__( 'All Sections', 'school-management' );
					}
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
				ob_start();

				if ( ! $section_id ) {
					// Get class students in current session.
					$students = WLSM_M_Staff_Class::get_class_students( $school_id, $session_id, $class_id, false );
				} else {
					// Get section students in current session.
					$students = WLSM_M_Staff_Class::get_section_students( $school_id, $session_id, $section_id, false );
				}

				if ( count( $students ) ) {
				?>
				<input type="hidden" name="class_id_final" value="<?php echo esc_attr( $class_id ); ?>">
				<input type="hidden" name="section_id_final" value="<?php echo esc_attr( $section_id ); ?>">

				<!-- Students results assessment. -->
				<div class="wlsm-form-section">
					<div class="row">
						<div class="col-md-12">
							<div class="wlsm-form-sub-heading-small wlsm-font-bold">
								<span>
								<?php
								printf(
									wp_kses(
										/* translators: 1: class label, 2: section label */
										__( 'Exam Results Assessment - Class: <span class="text-secondary">%1$s</span> | Section: <span class="text-secondary">%2$s</span>', 'school-management' ),
										array( 'span' => array( 'class' => array() ) )
									),
									esc_html( WLSM_M_Class::get_label_text( $class_school->label ) ),
									esc_html( WLSM_M_Staff_Class::get_section_label_text( $section_label ) )
								);
								?>
								</span>
								<a class="wlsm-get-result-bulk btn btn-primary mb-2" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-result-subject-wise-bulk') ); ?>" data-class-id="<?php echo esc_attr( $class_id ); ?>"  data-section-id="<?php echo esc_attr( $section_id ); ?>" href="#" data-message-title="<?php echo esc_attr__( 'Subject-wise Results', 'school-management' ); ?>" data-close="<?php echo esc_attr__( 'Close', 'school-management' ); ?>"><span class="dashicons dashicons-printer"> </span><?php esc_html_e( ' Bulk Print', 'school-management' ); ?></a>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive w-100">
								<table class="table table-bordered wlsm-students-results-assessment-table">
									<thead>
										<tr class="bg-primary text-white">
											<th><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?></th>
											<th><?php esc_html_e( 'Student Name', 'school-management' ); ?></th>
											<th><?php esc_html_e( 'Section', 'school-management' ); ?></th>
											<th><?php esc_html_e( 'Roll Number', 'school-management' ); ?></th>
											<th><?php esc_html_e( 'Phone Number', 'school-management' ); ?></th>
											<th><?php esc_html_e( 'Father\'s Name', 'school-management' ); ?></th>
											<th><?php esc_html_e( 'Father\'s Phone', 'school-management' ); ?></th>
											<th>
												<?php esc_html_e( 'Overall Result', 'school-management' ); ?>&nbsp;
											</th>
											<th>
												<?php esc_html_e( 'Subject-wise Result', 'school-management' ); ?>&nbsp;
											</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ( $students as $row ) {
										?>
										<tr>
											<td>
												<?php echo esc_html( $row->enrollment_number ); ?>
											</td>
											<td>
												<input type="hidden" name="student[<?php echo esc_attr( $row->ID ); ?>]" value="<?php echo esc_attr( $row->ID ); ?>">
												<?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $row->name ) ); ?>
											</td>
											<td>
												<?php echo esc_html( WLSM_M_Staff_Class::get_section_label_text( $row->section_label ) ); ?>
											</td>
											<td>
												<?php echo esc_html( WLSM_M_Staff_Class::get_roll_no_text( $row->roll_number ) ); ?>
											</td>
											<td>
												<?php echo esc_html( WLSM_M_Staff_Class::get_phone_text( $row->phone ) ); ?>
											</td>
											<td>
												<?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $row->father_name ) ); ?>
											</td>
											<td>
												<?php echo esc_html( WLSM_M_Staff_Class::get_phone_text( $row->father_phone ) ); ?>
											</td>
											<td>
												<a class="text-primary wlsm-get-result-assessment" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-result-assessment-' . $row->ID ) ); ?>" data-student="<?php echo esc_attr( $row->ID ); ?>" href="#" data-message-title="<?php echo esc_attr__( 'Overall Results Assessment', 'school-management' ); ?>" data-close="<?php echo esc_attr__( 'Close', 'school-management' ); ?>"><span class="dashicons dashicons-search"></span></a>
											</td>
											<td>
												<a class="text-primary wlsm-get-result-subject-wise" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-result-subject-wise-' . $row->ID ) ); ?>" data-student="<?php echo esc_attr( $row->ID ); ?>" href="#" data-message-title="<?php echo esc_attr__( 'Subject-wise Results', 'school-management' ); ?>" data-close="<?php echo esc_attr__( 'Close', 'school-management' ); ?>"><span class="dashicons dashicons-search"></span></a>
											</td>
										</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<div class="row mt-2 mb-2">
					<div class="col-md-12 text-center">
						<?php
						printf(
							wp_kses(
								/* translators: %s: session */
								__( 'Session: <span class="wlsm-font-bold">%s</span>', 'school-management' ),
								array( 'span' => array( 'class' => array() ) )
							),
							esc_html( WLSM_M_Session::get_label_text( $session_label ) )
						);
						?>
					</div>
				</div>
				<?php
				} else {
				?>
				<div class="alert alert-warning wlsm-font-bold">
					<i class="fas fa-exclamation-triangle"></i>
					<?php esc_html_e( 'There is no student in this class or section.', 'school-management' ); ?>
				</div>
				<?php
				}
				$html = ob_get_clean();

				wp_send_json_success( array( 'html' => $html ) );

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
		wp_send_json_error( $errors );
	}

	public static function get_result_assessment() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		$session_label = $current_user['session']['label'];

		try {
			ob_start();
			global $wpdb;

			$student_id = isset( $_POST['student_id'] ) ? absint( $_POST['student_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'get-result-assessment-' . $student_id ], 'get-result-assessment-' . $student_id ) ) {
				die();
			}

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
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/result_assessment.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	public static function get_result_subject_wise() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		$session_label = $current_user['session']['label'];

		try {
			ob_start();
			global $wpdb;

			$student_id = isset( $_POST['student_id'] ) ? absint( $_POST['student_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'get-result-subject-wise-' . $student_id ], 'get-result-subject-wise-' . $student_id ) ) {
				die();
			}

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

	public static function get_academic_report() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		$session_label = $current_user['session']['label'];

		try {
			ob_start();
			global $wpdb;

			$student_id = isset( $_POST['student_id'] ) ? absint( $_POST['student_id'] ) : 0;
			$report_id  = isset( $_POST['report_id'] ) ? absint( $_POST['report_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'get-academic-report-' . $student_id ], 'get-academic-report-' . $student_id ) ) {
				die();
			}

			// Checks if student exists.
			$student = WLSM_M_Staff_General::fetch_student( $school_id, $session_id, $student_id );

			if ( ! $student ) {
				throw new Exception( esc_html__( 'Student not found.', 'school-management' ) );
			}

			// $academic_report = true;
			$academic_report = WLSM_M_Staff_Examination::get_academic_report( $school_id, $report_id );
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

	public static function get_result_bulk() {
		$current_user = WLSM_M_Role::can( 'manage_exams' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		$session_label = $current_user['session']['label'];

		try {
			ob_start();
			global $wpdb;

			$class_id = isset( $_POST['class-id'] ) ? absint( $_POST['class-id'] ) : 0;
			$section_id = isset( $_POST['section-id'] ) ? absint( $_POST['section-id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST['get-result-subject-wise-bulk'], 'get-result-subject-wise-bulk') ) {
				die();
			}
			// Checks if student exists.
			$students = WLSM_M_Staff_General::fetch_students( $school_id, $session_id, $class_id, $section_id );

			if ( ! $students ) {
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
		require WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/bulk-results-subjects-wise.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}
}
