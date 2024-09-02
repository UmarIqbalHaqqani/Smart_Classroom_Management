<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Library.php';

class WLSM_Staff_Library {
	public static function fetch_books() {
		$current_user = WLSM_M_Role::can( 'manage_library' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		global $wpdb;

		$page_url = WLSM_M_Staff_Library::get_books_page_url();

		$query = WLSM_M_Staff_Library::fetch_book_query( $school_id );
		

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Library::fetch_book_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(bk.title LIKE "%' . $search_value . '%") OR ' .
				'(bk.author LIKE "%' . $search_value . '%") OR ' .
				'(bk.subject LIKE "%' . $search_value . '%") OR ' .
				'(bk.rack_number LIKE "%' . $search_value . '%") OR ' .
				'(bk.book_number LIKE "%' . $search_value . '%") OR ' .
				'(bk.isbn_number LIKE "%' . $search_value . '%")';

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'bk.title', 'bk.author', 'bk.subject', 'bk.rack_number', 'bk.book_number', 'bk.isbn_number', 'bk.price', 'bk.quantity' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY bk.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Library::fetch_book_query_count( $school_id );

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
		// $issued = WLSM_M_Staff_Library::fetch_book_query_issued( $school_id,  );
		
		if ( count( $filter_rows_limit ) ) {
			foreach ( $filter_rows_limit as $row ) {
				// Table columns.
				$book_issued_quantity = WLSM_M_Staff_Library::query_issued($row->ID);

				$data[] = array(
					esc_html( WLSM_M_Staff_Library::get_book_title( $row->title ) ),
					esc_html( WLSM_M_Staff_Library::get_book_author( $row->author ) ),
					esc_html( WLSM_M_Staff_Library::get_book_subject( $row->subject ) ),
					esc_html( WLSM_M_Staff_Library::get_book_rack_number( $row->rack_number ) ),
					esc_html( WLSM_M_Staff_Library::get_book_number( $row->book_number ) ),
					esc_html( WLSM_M_Staff_Library::get_book_isbn_number( $row->isbn_number ) ),
					esc_html( WLSM_Config::get_money_text( $row->price, $school_id  ) ),
					wp_kses_post('Total: ' . WLSM_M_Staff_Library::get_book_quantity($row->quantity) . "&nbsp; Issued: " . $book_issued_quantity),
					'<a class="btn btn-primary btn-sm" href="' . esc_url( $page_url . "&action=issue_book&id=" . $row->ID ) . '">' . esc_html__( 'Issue Book', 'school-management' ) . '</a>',
					'<a class="text-primary" href="' . esc_url( $page_url . "&action=save&id=" . $row->ID ) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-book" data-nonce="' . esc_attr( wp_create_nonce( 'delete-book-' . $row->ID ) ) . '" data-book="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Please Confirm!', 'school-management' ) . '" data-message-content="' . esc_attr__( 'This will delete the book.', 'school-management' ) . '" data-cancel="' . esc_attr__( 'Cancel', 'school-management' ) . '" data-submit="' . esc_attr__( 'Confirm', 'school-management' ) . '"><span class="dashicons dashicons-trash"></span></a>'
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data
		);

		echo json_encode( $output );
		die();
	}

	public static function save_book() {
		$current_user = WLSM_M_Role::can( 'manage_library' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$book_id = isset( $_POST['book_id'] ) ? absint( $_POST['book_id'] ) : 0;

			if ( $book_id ) {
				if ( ! wp_verify_nonce( $_POST[ 'edit-book-' . $book_id ], 'edit-book-' . $book_id ) ) {
					die();
				}
			} else {
				if ( ! wp_verify_nonce( $_POST['add-book'], 'add-book' ) ) {
					die();
				}
			}

			// Checks if book exists.
			if ( $book_id ) {
				$book = WLSM_M_Staff_Library::get_book( $school_id, $book_id );

				if ( ! $book ) {
					throw new Exception( esc_html__( 'Book not found.', 'school-management' ) );
				}
			}

			$title       = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
			$author      = isset( $_POST['author'] ) ? sanitize_text_field( $_POST['author'] ) : '';
			$subject     = isset( $_POST['subject'] ) ? sanitize_text_field( $_POST['subject'] ) : '';
			$description = isset( $_POST['description'] ) ? sanitize_text_field( $_POST['description'] ) : '';
			$rack_number = isset( $_POST['rack_number'] ) ? sanitize_text_field( $_POST['rack_number'] ) : '';
			$book_number = isset( $_POST['book_number'] ) ? sanitize_text_field( $_POST['book_number'] ) : '';
			$isbn_number = isset( $_POST['isbn_number'] ) ? sanitize_text_field( $_POST['isbn_number'] ) : '';
			$price       = isset( $_POST['price'] ) ? WLSM_Config::sanitize_money( $_POST['price'] ) : 0;
			$quantity    = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 0;

			// Start validation.
			$errors = array();

			if ( empty( $title ) ) {
				$errors['title'] = esc_html__( 'Please specify book title.', 'school-management' );
			}
			if ( strlen( $title ) > 100 ) {
				$errors['title'] = esc_html__( 'Maximum length cannot exceed 100 characters.', 'school-management' );
			}

			if ( strlen( $author ) > 60 ) {
				$errors['author'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
			}

			if ( strlen( $subject ) > 100 ) {
				$errors['subject'] = esc_html__( 'Maximum length cannot exceed 100 characters.', 'school-management' );
			}

			if ( strlen( $rack_number ) > 40 ) {
				$errors['rack_number'] = esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' );
			}

			if ( strlen( $book_number ) > 100 ) {
				$errors['book_number'] = esc_html__( 'Maximum length cannot exceed 100 characters.', 'school-management' );
			}

			if ( strlen( $isbn_number ) > 100 ) {
				$errors['isbn_number'] = esc_html__( 'Maximum length cannot exceed 100 characters.', 'school-management' );
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

				if ( $book_id ) {
					$message = esc_html__( 'Book updated successfully.', 'school-management' );
					$reset   = false;
				} else {
					$message = esc_html__( 'Book added successfully.', 'school-management' );
					$reset   = true;
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

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success( array( 'message' => $message, 'reset' => $reset ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function delete_book() {
		$current_user = WLSM_M_Role::can( 'manage_library' );

		if ( ! $current_user ) {
			die();
		}
		WLSM_Helper::check_demo();

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$book_id = isset( $_POST['book_id'] ) ? absint( $_POST['book_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-book-' . $book_id ], 'delete-book-' . $book_id ) ) {
				die();
			}

			// Checks if book exists.
			$book = WLSM_M_Staff_Library::get_book( $school_id, $book_id );

			if ( ! $book ) {
				throw new Exception( esc_html__( 'Book not found.', 'school-management' ) );
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

			$success = $wpdb->delete( WLSM_BOOKS, array( 'ID' => $book_id ) );
			$message = esc_html__( 'Book deleted successfully.', 'school-management' );

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

	public static function issue_book() {
		$current_user = WLSM_M_Role::can( 'manage_library' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$book_id = isset( $_POST['book_id'] ) ? absint( $_POST['book_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'issue-book-' . $book_id ], 'issue-book-' . $book_id ) ) {
				die();
			}

			// Checks if book exists.
			if ( $book_id ) {
				$book = WLSM_M_Staff_Library::get_book( $school_id, $book_id );

				if ( ! $book ) {
					throw new Exception( esc_html__( 'Book not found.', 'school-management' ) );
				}
			}

			$student_id  = isset( $_POST['student'] ) ? absint( $_POST['student'] ) : 0;
			$quantity    = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;
			$date_issued = isset( $_POST['date_issued'] ) ? DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $_POST['date_issued'] ) ) : NULL;
			$return_date = isset( $_POST['return_date'] ) ? DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $_POST['return_date'] ) ) : NULL;

			// Start validation.
			$errors = array();

			$total_book_copies_issued = absint( WLSM_M_Staff_Library::get_total_book_copies_issued( $school_id, $session_id, $book_id ) );

			if ( $book->quantity <= $total_book_copies_issued ) {
				wp_send_json_error(
					sprintf(
						/* translators: %d: total books issued with pending returns */
						__( 'Total %d of this books issued to students have pending returns which exceeds book quantity in the library.', 'school-management' ),
						esc_html( $total_book_copies_issued )
					)
				);
			}

			if ( empty( $student_id ) ) {
				$errors['student'] = esc_html__( 'Please select a student.', 'school-management' );
				wp_send_json_error( $errors );
			}

			if ( empty( $date_issued ) ) {
				$errors['date_issued'] = esc_html__( 'Please provide date issued.', 'school-management' );
			} else {
				$date_issued = $date_issued->format( 'Y-m-d' );
			}

			if ( ! empty( $return_date ) ) {
				$return_date = $return_date->format( 'Y-m-d' );
			} else {
				$return_date = NULL;
			}

			// Checks if student exists.
			$student = WLSM_M_Staff_General::get_student( $school_id, $session_id, $student_id, true, true );

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

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				// Book issued data.
				$data = array(
					'book_id'           => $book_id,
					'student_record_id' => $student_id,
					'quantity'          => $quantity,
					'date_issued'       => $date_issued,
					'return_date'       => $return_date
				);

				$data['created_at'] = current_time( 'Y-m-d H:i:s' );

				$success = $wpdb->insert( WLSM_BOOKS_ISSUED, $data );

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );

				$message = esc_html__( 'Book issued successfully.', 'school-management' );

				wp_send_json_success( array( 'message' => $message ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function fetch_books_issued() {
		$current_user = WLSM_M_Role::can( 'manage_library' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		global $wpdb;

		$page_url = WLSM_M_Staff_Library::get_books_issued_page_url();

		$query = WLSM_M_Staff_Library::fetch_book_issued_query( $school_id, $session_id );

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Library::fetch_book_issued_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(sr.name LIKE "%' . $search_value . '%") OR ' .
				'(sr.enrollment_number LIKE "%' . $search_value . '%") OR ' .
				'(c.label LIKE "%' . $search_value . '%") OR ' .
				'(se.label LIKE "%' . $search_value . '%") OR ' .
				'(bk.title LIKE "%' . $search_value . '%") OR ' .
				'(bk.author LIKE "%' . $search_value . '%") OR ' .
				'(bk.subject LIKE "%' . $search_value . '%") OR ' .
				'(bk.rack_number LIKE "%' . $search_value . '%") OR ' .
				'(bk.book_number LIKE "%' . $search_value . '%") OR ' .
				'(bk.isbn_number LIKE "%' . $search_value . '%")';

				$search_value_lowercase = strtolower( $search_value );
				if ( preg_match( '/^pend(|i|in|ing)$/', $search_value_lowercase ) ) {
					$condition .= ' OR (bki.returned_at IS NULL)';
				}

				$date_issued = DateTime::createFromFormat( WLSM_Config::date_format(), $search_value );

				if ( $date_issued ) {
					$format_date_issued = 'Y-m-d';
				} else {
					if ( 'd-m-Y' === WLSM_Config::date_format() ) {
						if ( ! $date_issued ) {
							$date_issued        = DateTime::createFromFormat( 'm-Y', $search_value );
							$format_date_issued = 'Y-m';
						}
					} elseif ( 'd/m/Y' === WLSM_Config::date_format() ) {
						if ( ! $date_issued ) {
							$date_issued        = DateTime::createFromFormat( 'm/Y', $search_value );
							$format_date_issued = 'Y-m';
						}
					} elseif ( 'Y-m-d' === WLSM_Config::date_format() ) {
						if ( ! $date_issued ) {
							$date_issued        = DateTime::createFromFormat( 'Y-m', $search_value );
							$format_date_issued = 'Y-m';
						}
					} elseif ( 'Y/m/d' === WLSM_Config::date_format() ) {
						if ( ! $date_issued ) {
							$date_issued        = DateTime::createFromFormat( 'Y/m', $search_value );
							$format_date_issued = 'Y-m';
						}
					}

					if ( ! $date_issued ) {
						$date_issued        = DateTime::createFromFormat( 'Y', $search_value );
						$format_date_issued = 'Y';
					}
				}

				if ( $date_issued && isset( $format_date_issued ) ) {
					$date_issued = $date_issued->format( $format_date_issued );
					$date_issued = ' OR (bki.date_issued LIKE "%' . $date_issued . '%")';

					$condition .= $date_issued;
				}

				$return_date = DateTime::createFromFormat( WLSM_Config::date_format(), $search_value );

				if ( $return_date ) {
					$format_return_date = 'Y-m-d';
				} else {
					if ( 'd-m-Y' === WLSM_Config::date_format() ) {
						if ( ! $return_date ) {
							$return_date        = DateTime::createFromFormat( 'm-Y', $search_value );
							$format_return_date = 'Y-m';
						}
					} elseif ( 'd/m/Y' === WLSM_Config::date_format() ) {
						if ( ! $return_date ) {
							$return_date        = DateTime::createFromFormat( 'm/Y', $search_value );
							$format_return_date = 'Y-m';
						}
					} elseif ( 'Y-m-d' === WLSM_Config::date_format() ) {
						if ( ! $return_date ) {
							$return_date        = DateTime::createFromFormat( 'Y-m', $search_value );
							$format_return_date = 'Y-m';
						}
					} elseif ( 'Y/m/d' === WLSM_Config::date_format() ) {
						if ( ! $return_date ) {
							$return_date        = DateTime::createFromFormat( 'Y/m', $search_value );
							$format_return_date = 'Y-m';
						}
					}

					if ( ! $return_date ) {
						$return_date        = DateTime::createFromFormat( 'Y', $search_value );
						$format_return_date = 'Y';
					}
				}

				if ( $return_date && isset( $format_return_date ) ) {
					$return_date = $return_date->format( $format_return_date );
					$return_date = ' OR (bki.return_date LIKE "%' . $return_date . '%")';

					$condition .= $return_date;
				}

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'bk.title', 'sr.name', 'sr.enrollment_number', 'c.label', 'se.label', 'bki.quantity', 'bki.date_issued', 'bki.return_date', 'bki.returned_at', 'bk.author', 'bk.subject', 'bk.rack_number', 'bk.book_number', 'bk.isbn_number', 'bk.price' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY bki.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Library::fetch_book_issued_query_count( $school_id, $session_id );

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
				if ( ! $row->returned_at ) {
					$returned_text = '<br><a class="btn btn-sm btn-success wlsm-font-bold wlsm-font-small wlsm-mark-book-as-returned" data-nonce="' . esc_attr( wp_create_nonce( 'mark-book-as-returned-' . $row->ID ) ) . '" data-book-issued="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Mark as Returned', 'school-management' ) . '" data-close="' . esc_attr__( 'Close', 'school-management' ) . '" data-message-content="' . esc_attr__( 'Are you sure to mark this book as returned from student.', 'school-management' ) . '" data-cancel="' . esc_attr__( 'Cancel', 'school-management' ) . '" data-submit="' . esc_attr__( 'Mark as Returned', 'school-management' ) . '">' . esc_html__( 'Return', 'school-management' ) . '</a>';
				} else {
					$returned_text = '<br><span class="text-secondary wlsm-font-small">' . esc_html( WLSM_Config::get_date_text( $row->returned_at ) ) . '</span>';
				}

				// Table columns.
				$data[] = array(
					esc_html( WLSM_M_Staff_Library::get_book_title( $row->title ) ),
					esc_html( WLSM_M_Staff_Class::get_name_text( $row->student_name ) ),
					esc_html( $row->enrollment_number ),
					esc_html( WLSM_M_Class::get_label_text( $row->class_label ) ),
					esc_html( WLSM_M_Staff_Class::get_section_label_text( $row->section_label ) ),
					esc_html( WLSM_M_Staff_Library::get_book_quantity( $row->issued_quantity ) ),
					esc_html( WLSM_Config::get_date_text( $row->date_issued ) ),
					esc_html( WLSM_Config::get_date_text( $row->return_date ) ),
					WLSM_M_Staff_Library::get_book_issued_status_text( $row->returned_at ) . $returned_text,
					esc_html( WLSM_M_Staff_Library::get_book_author( $row->author ) ),
					esc_html( WLSM_M_Staff_Library::get_book_subject( $row->subject ) ),
					esc_html( WLSM_M_Staff_Library::get_book_rack_number( $row->rack_number ) ),
					esc_html( WLSM_M_Staff_Library::get_book_number( $row->book_number ) ),
					esc_html( WLSM_M_Staff_Library::get_book_isbn_number( $row->isbn_number ) ),
					esc_html( WLSM_Config::get_money_text( $row->price, $school_id  ) ),
					'<a class="text-danger wlsm-delete-book-issued" data-nonce="' . esc_attr( wp_create_nonce( 'delete-book-issued-' . $row->ID ) ) . '" data-book-issued="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Please Confirm!', 'school-management' ) . '" data-message-content="' . esc_attr__( 'This will delete the record.', 'school-management' ) . '" data-cancel="' . esc_attr__( 'Cancel', 'school-management' ) . '" data-submit="' . esc_attr__( 'Confirm', 'school-management' ) . '"><span class="dashicons dashicons-trash"></span></a>'
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data
		);

		echo json_encode( $output );
		die();
	}

	public static function delete_book_issued() {
		$current_user = WLSM_M_Role::can( 'manage_library' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$book_issued_id = isset( $_POST['book_issued_id'] ) ? absint( $_POST['book_issued_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-book-issued-' . $book_issued_id ], 'delete-book-issued-' . $book_issued_id ) ) {
				die();
			}

			// Checks if book issued record exists.
			$book_issued = WLSM_M_Staff_Library::get_book_issued( $school_id, $session_id, $book_issued_id );

			if ( ! $book_issued ) {
				throw new Exception( esc_html__( 'Record not found.', 'school-management' ) );
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

			$success = $wpdb->delete( WLSM_BOOKS_ISSUED, array( 'ID' => $book_issued_id ) );
			$message = esc_html__( 'Record deleted successfully.', 'school-management' );

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

	public static function mark_book_as_returned() {
		$current_user = WLSM_M_Role::can( 'manage_library' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$book_issued_id = isset( $_POST['book_issued_id'] ) ? absint( $_POST['book_issued_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'mark-book-as-returned-' . $book_issued_id ], 'mark-book-as-returned-' . $book_issued_id ) ) {
				die();
			}

			// Checks if book issued record exists.
			$book_issued = WLSM_M_Staff_Library::get_book_issued( $school_id, $session_id, $book_issued_id );

			if ( ! $book_issued ) {
				throw new Exception( esc_html__( 'Record not found.', 'school-management' ) );
			}

			if ( $book_issued->returned_at ) {
				throw new Exception( esc_html__( 'Book was already marked as returned.', 'school-management' ) );
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

			$now = current_time( 'Y-m-d H:i:s' );

			$data = array(
				'returned_at' => $now,
				'updated_at'  => $now
			);

			$success = $wpdb->update(
				WLSM_BOOKS_ISSUED,
				$data,
				array( 'ID' => $book_issued_id )
			);

			$message = esc_html__( 'Book has been marked as returned.', 'school-management' );

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

	public static function fetch_library_cards() {
		$current_user = WLSM_M_Role::can( 'manage_library' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		global $wpdb;

		$page_url = WLSM_M_Staff_Library::get_library_cards_page_url();

		$query = WLSM_M_Staff_Library::fetch_library_card_query( $school_id, $session_id );

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Library::fetch_library_card_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(sr.name LIKE "%' . $search_value . '%") OR ' .
				'(sr.enrollment_number LIKE "%' . $search_value . '%") OR ' .
				'(c.label LIKE "%' . $search_value . '%") OR ' .
				'(se.label LIKE "%' . $search_value . '%") OR ' .
				'(lc.card_number LIKE "%' . $search_value . '%")';

				$date_issued = DateTime::createFromFormat( WLSM_Config::date_format(), $search_value );

				if ( $date_issued ) {
					$format_date_issued = 'Y-m-d';
				} else {
					if ( 'd-m-Y' === WLSM_Config::date_format() ) {
						if ( ! $date_issued ) {
							$date_issued        = DateTime::createFromFormat( 'm-Y', $search_value );
							$format_date_issued = 'Y-m';
						}
					} elseif ( 'd/m/Y' === WLSM_Config::date_format() ) {
						if ( ! $date_issued ) {
							$date_issued        = DateTime::createFromFormat( 'm/Y', $search_value );
							$format_date_issued = 'Y-m';
						}
					} elseif ( 'Y-m-d' === WLSM_Config::date_format() ) {
						if ( ! $date_issued ) {
							$date_issued        = DateTime::createFromFormat( 'Y-m', $search_value );
							$format_date_issued = 'Y-m';
						}
					} elseif ( 'Y/m/d' === WLSM_Config::date_format() ) {
						if ( ! $date_issued ) {
							$date_issued        = DateTime::createFromFormat( 'Y/m', $search_value );
							$format_date_issued = 'Y-m';
						}
					}

					if ( ! $date_issued ) {
						$date_issued        = DateTime::createFromFormat( 'Y', $search_value );
						$format_date_issued = 'Y';
					}
				}

				if ( $date_issued && isset( $format_date_issued ) ) {
					$date_issued = $date_issued->format( $format_date_issued );
					$date_issued = ' OR (lc.date_issued LIKE "%' . $date_issued . '%")';

					$condition .= $date_issued;
				}

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'lc.card_number', 'sr.name', 'sr.enrollment_number', 'c.label', 'se.label', 'lc.date_issued' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY lc.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Library::fetch_library_card_query_count( $school_id, $session_id );

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
					esc_html( $row->card_number ),
					esc_html( WLSM_M_Staff_Class::get_name_text( $row->student_name ) ),
					esc_html( $row->enrollment_number ),
					esc_html( WLSM_M_Class::get_label_text( $row->class_label ) ),
					esc_html( WLSM_M_Staff_Class::get_section_label_text( $row->section_label ) ),
					esc_html( WLSM_Config::get_date_text( $row->date_issued ) ),
					'<a class="text-success wlsm-print-library-card" data-nonce="' . esc_attr( wp_create_nonce( 'print-library-card-' . $row->ID ) ) . '" data-library-card="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Print Library Card', 'school-management' ) . '" data-close="' . esc_attr__( 'Close', 'school-management' ) . '"><i class="fas fa-print"></i></a>',
					'<a class="text-danger wlsm-delete-library-card" data-nonce="' . esc_attr( wp_create_nonce( 'delete-library-card-' . $row->ID ) ) . '" data-library-card="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Please Confirm!', 'school-management' ) . '" data-message-content="' . esc_attr__( 'This will delete the library card.', 'school-management' ) . '" data-cancel="' . esc_attr__( 'Cancel', 'school-management' ) . '" data-submit="' . esc_attr__( 'Confirm', 'school-management' ) . '"><span class="dashicons dashicons-trash"></span></a>'
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data
		);

		echo json_encode( $output );
		die();
	}

	public static function manage_library_cards() {
		$current_user = WLSM_M_Role::can( 'manage_library' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		if ( ! wp_verify_nonce( $_POST[ 'nonce' ], 'manage-library-cards' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$class_id   = isset( $_POST['class_id'] ) ? absint( $_POST['class_id'] ) : 0;
			$section_id = isset( $_POST['section_id'] ) ? absint( $_POST['section_id'] ) : 0;

			$date_issued = isset( $_POST['date_issued'] ) ? DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $_POST['date_issued'] ) ) : NULL;

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

			if ( empty( $date_issued ) ) {
				$errors['date_issued'] = esc_html__( 'Please specify date issued.', 'school-management' );
			} else {
				$date_issued = $date_issued->format( 'Y-m-d' );
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
					$students = WLSM_M_Staff_Class::get_class_students( $school_id, $session_id, $class_id );
				} else {
					// Get section students in current session.
					$students = WLSM_M_Staff_Class::get_section_students( $school_id, $session_id, $section_id );
				}

				$all_student_ids = array_map( function( $student ) {
					return $student->ID;
				}, $students );

				// Get saved library cards.
				$all_student_ids_count = count( $all_student_ids );

				$place_holders = array_fill( 0, $all_student_ids_count, '%s' );

				$all_student_ids_format = implode( ', ', $place_holders );

				$saved_library_cards = $wpdb->get_results( $wpdb->prepare( 'SELECT student_record_id FROM ' . WLSM_LIBRARY_CARDS . ' WHERE student_record_id IN (' . $all_student_ids_format . ')', $all_student_ids ), OBJECT_K );

				$students = array_filter( $students, function ( $student ) use ( $saved_library_cards ) {
					if ( isset( $saved_library_cards[ $student->ID ] ) ) {
						return false;
					}
					return true;
				});

				if ( count( $students ) ) {
				?>
				<div class="wlsm-students-library-cards-box">
					<div class="wlsm-students-library-cards">
						<input type="hidden" name="class_id_final" value="<?php echo esc_attr( $class_id ); ?>">
						<input type="hidden" name="section_id_final" value="<?php echo esc_attr( $section_id ); ?>">
						<input type="hidden" name="date_issued_final" value="<?php echo esc_attr( $_POST['date_issued'] ); ?>">

						<!-- Students library cards. -->
						<div class="wlsm-form-section">
							<div class="wlsm-form-sub-heading-small wlsm-font-bold">
								<span>
								<?php
								printf(
									wp_kses(
										/* translators: 1: class label, 2: section label */
										__( 'Library Cards - Class: <span class="text-secondary">%1$s</span> | Section: <span class="text-secondary">%2$s</span>', 'school-management' ),
										array( 'span' => array( 'class' => array() ) )
									),
									esc_html( WLSM_M_Class::get_label_text( $class_school->label ) ),
									esc_html( WLSM_M_Staff_Class::get_section_label_text( $section_label ) )
								);
								?>
								</span>
								<span class="float-md-right">
								<?php
								printf(
									wp_kses(
										/* translators: %s: date issued */
										__( 'Date Issued: <span class="text-dark wlsm-font-bold">%s</span>', 'school-management' ),
										array( 'span' => array( 'class' => array() ) )
									),
									esc_html( WLSM_Config::get_date_text( $date_issued ) )
								);
								?>
								</span>
							</div>
							<div class="table-responsive w-100">
								<table class="table table-bordered wlsm-students-library-cards-table">
									<thead>
										<tr class="bg-primary text-white">
											<th><input type="checkbox" name="select_all" id="wlsm-select-all" value="1"></th>
											<th><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?></th>
											<th><?php esc_html_e( 'Student Name', 'school-management' ); ?></th>
											<th><?php esc_html_e( 'Section', 'school-management' ); ?></th>
											<th><?php esc_html_e( 'Roll Number', 'school-management' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ( $students as $row ) {
										?>
										<tr>
											<td>
												<input type="checkbox" class="wlsm-select-single" name="student[<?php echo esc_attr( $row->ID ); ?>]" value="<?php echo esc_attr( $row->ID ); ?>">
											</td>
											<td>
												<?php echo esc_html( $row->enrollment_number ); ?>
											</td>
											<td>
												<?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $row->name ) ); ?>
											</td>
											<td>
												<?php echo esc_html( WLSM_M_Staff_Class::get_section_label_text( $row->section_label ) ); ?>
											</td>
											<td>
												<?php echo esc_html( WLSM_M_Staff_Class::get_roll_no_text( $row->roll_number ) ); ?>
											</td>
										</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>

						<div class="row mt-2 mb-2">
							<div class="col-md-12 text-center">
								<?php
								printf(
									wp_kses(
										/* translators: %s: date issued */
										__( 'Date Issued: <span class="wlsm-font-bold">%s</span>', 'school-management' ),
										array( 'span' => array( 'class' => array() ) )
									),
									esc_html( WLSM_Config::get_date_text( $date_issued ) )
								);
								?>
							</div>
						</div>

						<div class="row mt-2">
							<div class="col-md-12 text-center">
								<button type="submit" class="btn btn-sm btn-success" id="wlsm-issue-library-cards-btn" data-message-title="<?php esc_attr_e( 'Confirm!', 'school-management' ); ?>" data-message-content="<?php esc_attr_e( 'Are you sure to issue library cards for selected students?', 'school-management' ); ?>" data-submit="<?php esc_attr_e( 'Issue Library Cards', 'school-management' ); ?>" data-cancel="<?php esc_attr_e( 'Cancel', 'school-management' ); ?>">
									<i class="fas fa-id-card"></i>&nbsp;
									<?php esc_html_e( 'Issue Library Cards', 'school-management' ); ?>
								</button>
							</div>
						</div>
					</div>
				</div>
				<?php
				} else {
				?>
				<div class="alert alert-warning wlsm-font-bold">
					<i class="fas fa-exclamation-triangle"></i>
					<?php esc_html_e( 'There is no student without library card in this class or section.', 'school-management' ); ?>
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

	public static function issue_library_cards() {
		$current_user = WLSM_M_Role::can( 'manage_library' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		if ( ! wp_verify_nonce( $_POST[ 'issue-library-cards' ], 'issue-library-cards' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$class_id   = isset( $_POST['class_id_final'] ) ? absint( $_POST['class_id_final'] ) : 0;
			$section_id = isset( $_POST['section_id_final'] ) ? absint( $_POST['section_id_final'] ) : 0;

			$date_issued = isset( $_POST['date_issued_final'] ) ? DateTime::createFromFormat( WLSM_Config::date_format(), sanitize_text_field( $_POST['date_issued_final'] ) ) : NULL;

			// Start validation.
			if ( empty( $class_id ) ) {
				throw new Exception( esc_html__( 'Please select a class.', 'school-management' ) );
			} else {
				$class_school = WLSM_M_School::get_class_school( $class_id, $school_id );
				if ( ! $class_school ) {
					throw new Exception( esc_html__( 'Class not found.', 'school-management' ) );
				} else {
					$class_school_id = $class_school->ID;
					if ( ! empty( $section_id ) ) {
						$section = WLSM_M_Staff_Class::get_section( $school_id, $section_id, $class_school_id );
						if ( ! $class_school ) {
							throw new Exception( esc_html__( 'Section not found.', 'school-management' ) );
						}
					}
				}
			}

			if ( empty( $date_issued ) ) {
				throw new Exception( esc_html__( 'Please specify date.', 'school-management' ) );
			} else {
				$date_issued = $date_issued->format( 'Y-m-d' );
			}

			if ( ! $section_id ) {
				// Get class students in current session.
				$students = WLSM_M_Staff_Class::get_class_students( $school_id, $session_id, $class_id );
			} else {
				// Get section students in current session.
				$students = WLSM_M_Staff_Class::get_section_students( $school_id, $session_id, $section_id );
			}

			$all_student_ids = array_map( function( $student ) {
				return $student->ID;
			}, $students );

			// Get saved library cards.
			$all_student_ids_count = count( $all_student_ids );

			$place_holders = array_fill( 0, $all_student_ids_count, '%s' );

			$all_student_ids_format = implode( ', ', $place_holders );

			$saved_library_cards = $wpdb->get_results( $wpdb->prepare( 'SELECT student_record_id FROM ' . WLSM_LIBRARY_CARDS . ' WHERE student_record_id IN (' . $all_student_ids_format . ')', $all_student_ids ), OBJECT_K );

			$students = array_filter( $students, function ( $student ) use ( $saved_library_cards ) {
				if ( isset( $saved_library_cards[ $student->ID ] ) ) {
					return false;
				}
				return true;
			});

			$student_ids         = ( isset( $_POST['student'] ) && is_array( $_POST['student'] ) ) ? $_POST['student'] : array();
			$card_number_prefix  = isset( $_POST['card_number_prefix'] ) ? sanitize_text_field( $_POST['card_number_prefix'] ) : '';
			$staring_card_number = isset( $_POST['staring_card_number'] ) ? absint( $_POST['staring_card_number'] ) : 1;

			$student_ids_keys = array_keys( $student_ids );

			if ( empty( $staring_card_number ) ) {
				wp_send_json_error( esc_html__( 'Please provide starting library card number.', 'school-management' ) );
			}

			$first_card_number = $card_number_prefix . $staring_card_number;

			if ( strlen( $first_card_number ) > 47 ) {
				wp_send_json_error( esc_html__( 'Library card number length is too large.', 'school-management' ) );
			}

			if ( ! count( $student_ids ) ) {
				wp_send_json_error( esc_html__( 'Please select atleast one student.', 'school-management' ) );
			} else if ( ( array_intersect( $student_ids, $all_student_ids ) != $student_ids ) || ( $student_ids_keys != array_values( $student_ids ) ) ) {
				wp_send_json_error( esc_html__( 'Please select valid students.', 'school-management' ) );
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

			$library_card_data = array(
				'date_issued' => $date_issued,
			);

			foreach ( $student_ids_keys as $student_id ) {
				if ( isset( $students[ $student_id ] ) ) {
					$student = $students[ $student_id ];

					$library_card_data['student_record_id'] = $student_id;
					$library_card_data['card_number']       = $card_number_prefix . $staring_card_number;

					// Checks if library card number already exists in this session.
					$library_card_number_exists = $wpdb->get_row(
						$wpdb->prepare(
							'SELECT lc.ID FROM ' . WLSM_LIBRARY_CARDS . ' as lc 
							JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = lc.student_record_id 
							JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
							JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
							JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
							WHERE cs.school_id = %d AND ss.ID = %d AND lc.card_number = %s', $school_id, $session_id, $library_card_data['card_number']
						)
					);

					if ( $library_card_number_exists ) {
						throw new Exception(
							sprintf(
								/* translators: %s: library card number */
								esc_html__( 'Library card number %s already exists for this session.', 'school-management' ),
								$library_card_data['card_number']
							)
						);
					}

					// Checks if student library card already exists.
					$student_library_card_exists = $wpdb->get_row(
						$wpdb->prepare(
							'SELECT lc.ID FROM ' . WLSM_LIBRARY_CARDS . ' as lc 
							JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = lc.student_record_id 
							JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
							JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
							JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
							WHERE cs.school_id = %d AND ss.ID = %d AND sr.ID = %d', $school_id, $session_id, $student->ID
						)
					);

					if ( $student_library_card_exists ) {
						throw new Exception(
							sprintf(
								/* translators: %s: enrollment number */
								esc_html__( 'Student library card already exists for enrollment %s.', 'school-management' ),
								$student->enrollment_number
							)
						);
					}

					$library_card_data['created_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->insert( WLSM_LIBRARY_CARDS, $library_card_data );

					$staring_card_number++;
				} else {
					throw new Exception( esc_html__( 'Please select valid students.', 'school-management' ) );
				}
			}

			$wpdb->query( 'COMMIT;' );

			$message = esc_html__( 'Library cards issued successfully.', 'school-management' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function delete_library_card() {
		$current_user = WLSM_M_Role::can( 'manage_library' );

		if ( ! $current_user ) {
			die();
		}
		WLSM_Helper::check_demo();

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$library_card_id = isset( $_POST['library_card_id'] ) ? absint( $_POST['library_card_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-library-card-' . $library_card_id ], 'delete-library-card-' . $library_card_id ) ) {
				die();
			}

			// Checks if library card exists.
			$library_card = WLSM_M_Staff_Library::get_library_card( $school_id, $session_id, $library_card_id );

			if ( ! $library_card ) {
				throw new Exception( esc_html__( 'Library card not found.', 'school-management' ) );
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

			$success = $wpdb->delete( WLSM_LIBRARY_CARDS, array( 'ID' => $library_card_id ) );
			$message = esc_html__( 'Library card deleted successfully.', 'school-management' );

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

	public static function print_library_card() {
		$current_user = WLSM_M_Role::can( 'manage_library' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$library_card_id = isset( $_POST['library_card_id'] ) ? absint( $_POST['library_card_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'print-library-card-' . $library_card_id ], 'print-library-card-' . $library_card_id ) ) {
				die();
			}

			// Checks if library card exists.
			$library_card = WLSM_M_Staff_Library::get_library_card( $school_id, $session_id, $library_card_id );

			if ( ! $library_card ) {
				throw new Exception( esc_html__( 'Library card not found.', 'school-management' ) );
			}

			// Checks if student exists.
			$student = WLSM_M_Staff_General::fetch_student( $school_id, $session_id, $library_card->student_record_id );

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
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/library_card.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}

	public static function view_library_card() {
		$current_user = WLSM_M_Role::can( 'manage_library' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			ob_start();
			global $wpdb;

			$student_id = isset( $_POST['student_id'] ) ? absint( $_POST['student_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'view-library-card' ], 'view-library-card' ) ) {
				die();
			}

			// Checks if student library card exists.
			$library_card = WLSM_M_Staff_Library::get_student_library_card( $school_id, $session_id, $student_id );

			if ( ! $library_card ) {
				throw new Exception( esc_html__( 'Library card not found.', 'school-management' ) );
			}

			// Checks if student exists.
			$student = WLSM_M_Staff_General::fetch_student( $school_id, $session_id, $library_card->student_record_id );

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
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/library_card.php';
		$html = ob_get_clean();

		wp_send_json_success( array( 'html' => $html ) );
	}
}
