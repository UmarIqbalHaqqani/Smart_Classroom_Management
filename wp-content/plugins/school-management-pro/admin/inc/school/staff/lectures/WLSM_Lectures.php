<?php
defined( 'ABSPATH' ) || die();
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Lecture.php';
class WLSM_Lecture {

	public static function fetch_lecture() {
		// $current_user = WLSM_M_Role::can('manage_notices');

		// if (!$current_user) {
		// 	die();
		// }

		global $wpdb;

		$page_url = WLSM_M_Staff_Lecture::get_lecture_page_url();

		$query = WLSM_M_Staff_Lecture::fetch_lecture_query();

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Lecture::fetch_lecture_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if (isset($_POST['search']['value'])) {
			$search_value = sanitize_text_field($_POST['search']['value']);
			if ('' !== $search_value) {
				$condition .= '' .
					'(l.title LIKE "%' . $search_value . '%") OR ' .
					'(l.link_to LIKE "%' . $search_value . '%") OR ' .

				$search_value_lowercase = strtolower($search_value);
				if (preg_match('/^none$/', $search_value_lowercase)) {
					$link_to = '';
				}

				if (isset($link_to)) {
					$condition .= ' OR (l.link_to = "' . $link_to . '")';
				}

				if (preg_match('/^inac(|t|ti|tiv|tive)$/', $search_value_lowercase)) {
					$is_active = 0;
				} else if (preg_match('/^acti(|v|ve)$/', $search_value_lowercase)) {
					$is_active = 1;
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
					$created_at = ' OR (l.created_at LIKE "%' . $created_at . '%")';

					$condition .= $created_at;
				}

				$query_filter .= (' HAVING ' . $condition);
			}
		}

		// Ordering.
		$columns = array('l.title', 'l.link_to');
		if (isset($_POST['order']) && isset($columns[$_POST['order']['0']['column']])) {
			$order_by  = sanitize_text_field($columns[$_POST['order']['0']['column']]);
			$order_dir = sanitize_text_field($_POST['order']['0']['dir']);

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY l.ID DESC';
		}

		// Limiting.
		$limit = '';
		if (-1 != $_POST['length']) {
			$start  = absint($_POST['start']);
			$length = absint($_POST['length']);

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Lecture::fetch_lecture_query_count();

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
				$link_to = $row->link_to;

				if ('url' === $link_to) {
					$link_to = '<a target="_blank" href="' . esc_url($row->url) . '">' . esc_html(WLSM_M_Staff_Class::get_link_to_text($link_to)) . '</a>';
				} else if ('attachment' === $link_to) {
					$link_to = esc_html(WLSM_M_Staff_Class::get_link_to_text($link_to));
					if (!empty($row->attachment)) {
						$attachment = $row->attachment;
						$link_to .= '<br><a target="_blank" href="' . esc_url(wp_get_attachment_url($attachment)) . '"><i class="fas fa-search"></i></a>';
					}
				} else {
					$link_to = esc_html(WLSM_M_Staff_Class::get_none_text());
				}

				// Table columns.
				$data[] = array(
					// esc_html(WLSM_Config::limit_string(WLSM_M_Staff_Class::get_name_text($row->ID))),
					esc_html(WLSM_Config::limit_string(WLSM_M_Staff_Class::get_name_text($row->title))),
					// $link_to,
					// esc_html(WLSM_M_Staff_Class::get_status_text($row->is_active)),
					esc_html($row->class),
					esc_html($row->subject),
					esc_html($row->chapter),
					esc_html(WLSM_Config::get_date_text($row->created_at)),
					// esc_html(WLSM_M_Staff_Class::get_name_text($row->username)),
					'<a class="text-primary" href="' . esc_url($page_url . "&action=save&id=" . $row->ID) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-lecture" data-nonce="' . esc_attr(wp_create_nonce('delete-lecture-' . $row->ID)) . '" data-lecture="' . esc_attr($row->ID) . '" href="#" data-message-title="' . esc_attr__('Please Confirm!', 'school-management') . '" data-message-content="' . esc_attr__('This will delete the lecture.', 'school-management') . '" data-cancel="' . esc_attr__('Cancel', 'school-management') . '" data-submit="' . esc_attr__('Confirm', 'school-management') . '"><span class="dashicons dashicons-trash"></span></a>'
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

	public static function save_lecture() {
		// $current_user = WLSM_M_Role::can( 'manage_transport' );

		// if ( ! $current_user ) {
		// 	die();
		// }

		try {
			ob_start();
			global $wpdb;

			$lecture_id = isset( $_POST['lecture_id'] ) ? absint( $_POST['lecture_id'] ) : 0;

			if ( $lecture_id ) {
				if ( ! wp_verify_nonce( $_POST[ 'edit-lecture-' . $lecture_id ], 'edit-lecture-' . $lecture_id ) ) {
					die();
				}
			} else {
				if ( ! wp_verify_nonce( $_POST['add-lecture'], 'add-lecture' ) ) {
					die();
				}
			}

			// Checks if lecture exists.
			if ( $lecture_id ) {
				$lecture = WLSM_M_Staff_Lecture::get_lecture( $lecture_id );

				if ( ! $lecture ) {
					throw new Exception( esc_html__( 'Lecture not found.', 'school-management' ) );
				}
			}

			$title       = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
			$class_id    = isset($_POST['classes']) ? sanitize_text_field($_POST['classes']) : '';
			$chapter_id    = isset($_POST['chapter']) ? sanitize_text_field($_POST['chapter']) : '';
			$description = isset($_POST['description_body']) ? wp_kses_post($_POST['description_body']) : '';
			$link_to     = isset($_POST['link_to']) ? sanitize_text_field($_POST['link_to']) : '';
			$attachment  = (isset($_FILES['attachment']) && is_array($_FILES['attachment'])) ? $_FILES['attachment'] : NULL;
			$url         = isset($_POST['url']) ? esc_url_raw($_POST['url']) : '';
			// $classes     = (isset($_POST['classes']) && is_array($_POST['classes'])) ? $_POST['classes'] : array();

			$subject     = isset( $_POST['subject'] ) ? sanitize_text_field( $_POST['subject'] ) : '';

			// Start validation.
			$errors = array();

			if (empty($title)) {
				$errors['title'] = esc_html__('Please provide lecture title.', 'school-management');
			}

			if (!in_array($link_to, array('url', 'attachment'))) {
				$link_to = '';
			}

			if ('attachment' === $link_to) {
				if (isset($attachment['tmp_name']) && !empty($attachment['tmp_name'])) {
					if (!WLSM_Helper::is_valid_file($attachment, 'attachment')) {
						$errors['attachment'] = esc_html__('This file type is not allowed.', 'school-management');
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
				$wpdb->query( 'BEGIN;' );

				if ( $lecture_id ) {
					$message = esc_html__( 'Lecture updated successfully.', 'school-management' );
					$reset   = false;
				} else {
					$message = esc_html__( 'Lecture added successfully.', 'school-management' );
					$reset   = true;
				}

				// Lecture data.
				$data = array(
					'title'       => $title,
					'class_id'    => $class_id,
					'chapter_id'  => $chapter_id,
					'subject_id'  => $subject,
					'description' => $description,
					'link_to'     => $link_to,
					'url'         => $url,
					'attachment'  => $attachment,
				);

				if (!empty($attachment)) {
					$attachment = media_handle_upload('attachment', 0);
					if (is_wp_error($attachment)) {
						throw new Exception($attachment->get_error_message());
					}
					$data['attachment'] = $attachment;

					if ($lecture_id && $lecture->attachment) {
						$attachment_id_to_delete = $lecture->attachment;
					}
				}

				if ( $lecture_id ) {
					$data['updated_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->update( WLSM_LECTURE, $data, array( 'ID' => $lecture_id) );
				} else {
					$data['created_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->insert( WLSM_LECTURE, $data );
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

	public static function delete_lecture() {
		// $current_user = WLSM_M_Role::can( 'manage_transport' );

		// if ( ! $current_user ) {
		// 	die();
		// }
		WLSM_Helper::check_demo();

		// $school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$lecture_id = isset( $_POST['lecture_id'] ) ? absint( $_POST['lecture_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-lecture-' . $lecture_id ], 'delete-lecture-' . $lecture_id ) ) {
				die();
			}

			// Checks if lecture exists.
			$lecture = WLSM_M_Staff_Lecture::get_lecture( $lecture_id );

			if ( ! $lecture ) {
				throw new Exception( esc_html__( 'Transport lecture not found.', 'school-management' ) );
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

			$success = $wpdb->delete( WLSM_LECTURE, array( 'ID' => $lecture_id ) );
			$message = esc_html__( 'lecture deleted successfully.', 'school-management' );

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

	public static function save_chapter() {
		try {
			ob_start();
			global $wpdb;

			$chapter_id = isset( $_POST['chapter_id'] ) ? absint( $_POST['chapter_id'] ) : 0;

			if ( $chapter_id ) {
				if ( ! wp_verify_nonce( $_POST[ 'edit-chapter-' . $chapter_id ], 'edit-chapter-' . $chapter_id ) ) {
					die();
				}
			} else {
				if ( ! wp_verify_nonce( $_POST['add-chapter'], 'add-chapter' ) ) {
					die();
				}
			}

			// Checks if chapter exists.
			if ( $chapter_id ) {
				$chapter = WLSM_M_Staff_Lecture::get_chapter( $chapter_id );

				if ( ! $chapter ) {
					throw new Exception( esc_html__( 'Chapter not found.', 'school-management' ) );
				}
			}

			$title       = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
			$class_id    = isset($_POST['classes']) ? sanitize_text_field($_POST['classes']) : '';
			$subject_id    = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : '';

			// Start validation.
			$errors = array();

			if (empty($title)) {
				$errors['title'] = esc_html__('Please provide chapter title.', 'school-management');
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

				if ( $chapter_id ) {
					$message = esc_html__( 'Chapter updated successfully.', 'school-management' );
					$reset   = false;
				} else {
					$message = esc_html__( 'Chapter added successfully.', 'school-management' );
					$reset   = true;
				}

				// Chapter data.
				$data = array(
					'title'       => $title,
					'class_id'    => $class_id,
					'subject_id'  => $subject_id,
				);

				if (!empty($attachment)) {
					$attachment = media_handle_upload('attachment', 0);
					if (is_wp_error($attachment)) {
						throw new Exception($attachment->get_error_message());
					}
					$data['attachment'] = $attachment;

					if ($chapter_id && $chapter->attachment) {
						$attachment_id_to_delete = $chapter->attachment;
					}
				}

				if ( $chapter_id ) {
					$data['updated_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->update( WLSM_CHAPTER, $data, array( 'ID' => $chapter_id) );
				} else {
					$data['created_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->insert( WLSM_CHAPTER, $data );
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

	public static function fetch_chapter() {

		global $wpdb;

		$page_url = WLSM_M_Staff_Lecture::get_chapter_page_url();

		$query = WLSM_M_Staff_Lecture::fetch_chapter_query();

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Lecture::fetch_chapter_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if (isset($_POST['search']['value'])) {
			$search_value = sanitize_text_field($_POST['search']['value']);
			if ('' !== $search_value) {
				$condition .= '' .
					'(l.title LIKE "%' . $search_value . '%") OR ' .
					'(l.link_to LIKE "%' . $search_value . '%") OR ' .

				$search_value_lowercase = strtolower($search_value);
				if (preg_match('/^none$/', $search_value_lowercase)) {
					$link_to = '';
				}

				if (isset($link_to)) {
					$condition .= ' OR (l.link_to = "' . $link_to . '")';
				}

				if (preg_match('/^inac(|t|ti|tiv|tive)$/', $search_value_lowercase)) {
					$is_active = 0;
				} else if (preg_match('/^acti(|v|ve)$/', $search_value_lowercase)) {
					$is_active = 1;
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
					$created_at = ' OR (l.created_at LIKE "%' . $created_at . '%")';

					$condition .= $created_at;
				}

				$query_filter .= (' HAVING ' . $condition);
			}
		}

		// Ordering.
		$columns = array('l.title');
		if (isset($_POST['order']) && isset($columns[$_POST['order']['0']['column']])) {
			$order_by  = sanitize_text_field($columns[$_POST['order']['0']['column']]);
			$order_dir = sanitize_text_field($_POST['order']['0']['dir']);

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY l.ID DESC';
		}

		// Limiting.
		$limit = '';
		if (-1 != $_POST['length']) {
			$start  = absint($_POST['start']);
			$length = absint($_POST['length']);

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Lecture::fetch_chapter_query_count();

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
					
					esc_html(WLSM_Config::limit_string(WLSM_M_Staff_Class::get_name_text($row->title))),
					esc_html($row->subject),
					esc_html(WLSM_Config::get_date_text($row->created_at)),
					'<a class="text-primary" href="' . esc_url($page_url . "&action=save&id=" . $row->ID) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-chapter" data-nonce="' . esc_attr(wp_create_nonce('delete-chapter-' . $row->ID)) . '" data-chapter="' . esc_attr($row->ID) . '" href="#" data-message-title="' . esc_attr__('Please Confirm!', 'school-management') . '" data-message-content="' . esc_attr__('This will delete the chapter.', 'school-management') . '" data-cancel="' . esc_attr__('Cancel', 'school-management') . '" data-submit="' . esc_attr__('Confirm', 'school-management') . '"><span class="dashicons dashicons-trash"></span></a>'
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

	public static function delete_chapter() {
		try {
			ob_start();
			global $wpdb;

			$chapter_id = isset( $_POST['chapter_id'] ) ? absint( $_POST['chapter_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-chapter-' . $chapter_id ], 'delete-chapter-' . $chapter_id ) ) {
				die();
			}

			// Checks if chapter exists.
			$chapter = WLSM_M_Staff_Lecture::get_chapter( $chapter_id );

			if ( ! $chapter ) {
				throw new Exception( esc_html__( 'Transport chapter not found.', 'school-management' ) );
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

			$success = $wpdb->delete( WLSM_CHAPTER, array( 'ID' => $chapter_id ) );
			$message = esc_html__( 'chapter deleted successfully.', 'school-management' );

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

}
