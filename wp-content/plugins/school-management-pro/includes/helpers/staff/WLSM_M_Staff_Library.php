<?php
defined( 'ABSPATH' ) || die();

class WLSM_M_Staff_Library {
	public static function get_books_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_BOOKS );
	}

	public static function get_books_issued_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_BOOKS_ISSUED );
	}

	public static function get_library_cards_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_LIBRARY_CARDS );
	}

	public static function fetch_book_query($school_id) {
		$query = 'SELECT bk.ID, bk.title, bk.author, bk.subject, bk.rack_number, bk.book_number, bk.isbn_number, bk.price, bk.quantity FROM ' . WLSM_BOOKS . ' as bk WHERE bk.school_id = ' . absint($school_id);
		return $query;
	}

	public static function fetch_book_query_issued( $book_id) {
		$query = 'SELECT bki.quantity as book_issued, bki.book_id, bki.returned_at FROM ' . WLSM_BOOKS_ISSUED . ' as bki 
		WHERE  returned_at IS NULL AND bki.book_id = '.absint($book_id);
		return $query;
	}

	public static function query_issued($book_id) {
		global $wpdb;
		$issued = WLSM_M_Staff_Library::fetch_book_query_issued($book_id);
		$issued = $wpdb->get_results($issued);
		return count($issued);
	}
	

	public static function fetch_book_query_group_by() {
		$group_by = 'GROUP BY bk.ID';
		return $group_by;
	}

	public static function fetch_book_query_count( $school_id ) {
		$query = 'SELECT COUNT(DISTINCT bk.ID) FROM ' . WLSM_BOOKS . ' as bk WHERE bk.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function get_book( $school_id, $id ) {
		global $wpdb;
		$book = $wpdb->get_row( $wpdb->prepare( 'SELECT bk.ID, bk.quantity FROM ' . WLSM_BOOKS . ' as bk WHERE bk.school_id = %d AND bk.ID = %d', $school_id, $id ) );
		return $book;
	}

	public static function fetch_book( $school_id, $id ) {
		global $wpdb;
		$book = $wpdb->get_row( $wpdb->prepare( 'SELECT bk.ID, bk.title, bk.author, bk.subject, bk.description, bk.rack_number, bk.book_number, bk.isbn_number, bk.price, bk.quantity FROM ' . WLSM_BOOKS . ' as bk WHERE bk.school_id = %d AND bk.ID = %d', $school_id, $id ) );
		return $book;
	}

	public static function fetch_book_issued_query( $school_id, $session_id ) {
		$query = 'SELECT bki.ID, sr.name as student_name, sr.enrollment_number, c.label as class_label, se.label as section_label, bki.quantity as issued_quantity, bki.date_issued, bki.return_date, bki.returned_at, bk.title, bk.author, bk.subject, bk.rack_number, bk.book_number, bk.isbn_number, bk.price FROM ' . WLSM_BOOKS_ISSUED . ' as bki 
		JOIN ' . WLSM_BOOKS . ' as bk ON bk.ID = bki.book_id 
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = bki.student_record_id 
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id );
		return $query;
	}

	public static function fetch_book_issued_query_group_by() {
		$group_by = 'GROUP BY bki.ID';
		return $group_by;
	}

	public static function fetch_book_issued_query_count( $school_id, $session_id, $pending = false ) {
		$query = 'SELECT COUNT(DISTINCT bki.ID) FROM ' . WLSM_BOOKS_ISSUED . ' as bki 
		JOIN ' . WLSM_BOOKS . ' as bk ON bk.ID = bki.book_id 
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = bki.student_record_id 
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id );

		if ( $pending ) {
			$query .= ' AND bki.returned_at IS NULL';
		}
		return $query;
	}

	public static function get_book_issued( $school_id, $session_id, $id ) {
		global $wpdb;
		$book_issued = $wpdb->get_row( $wpdb->prepare( 'SELECT bki.ID, bki.returned_at FROM ' . WLSM_BOOKS_ISSUED . ' as bki 
			JOIN ' . WLSM_BOOKS . ' as bk ON bk.ID = bki.book_id 
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = bki.student_record_id 
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
			WHERE cs.school_id = %d AND ss.ID = %d AND bki.ID = %d', $school_id, $session_id, $id ) );
		return $book_issued;
	}

	public static function get_total_book_copies_issued( $school_id, $session_id, $book_id ) {
		global $wpdb;

		$total_book_copies_issued = $wpdb->get_var(
			$wpdb->prepare( 'SELECT COALESCE(SUM(bki.quantity), 1) as total FROM ' . WLSM_BOOKS_ISSUED . ' as bki 
				JOIN ' . WLSM_BOOKS . ' as bk ON bk.ID = bki.book_id 
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = bki.student_record_id 
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
				WHERE cs.school_id = %d AND ss.ID = %d AND bk.ID = %d AND bki.returned_at IS NULL GROUP BY bk.ID',
				$school_id, $session_id, $book_id )
		);

		return $total_book_copies_issued;
	}

	public static function fetch_library_card_query( $school_id, $session_id ) {
		$query = 'SELECT lc.ID, lc.card_number, lc.date_issued, sr.name as student_name, sr.enrollment_number, c.label as class_label, se.label as section_label FROM ' . WLSM_LIBRARY_CARDS . ' as lc 
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = lc.student_record_id 
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id );
		return $query;
	}

	public static function fetch_library_card_query_group_by() {
		$group_by = 'GROUP BY lc.ID';
		return $group_by;
	}

	public static function fetch_library_card_query_count( $school_id, $session_id ) {
		$query = 'SELECT COUNT(DISTINCT lc.ID) FROM ' . WLSM_LIBRARY_CARDS . ' as lc 
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = lc.student_record_id 
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id );
		return $query;
	}

	public static function get_library_card( $school_id, $session_id, $id ) {
		global $wpdb;
		$library_card = $wpdb->get_row( $wpdb->prepare( 'SELECT lc.ID, lc.card_number, lc.date_issued, lc.student_record_id FROM ' . WLSM_LIBRARY_CARDS . ' as lc 
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = lc.student_record_id 
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
		WHERE cs.school_id = %d AND ss.ID = %d AND lc.ID = %d', $school_id, $session_id, $id ) );
		return $library_card;
	}

	public static function get_student_library_card( $school_id, $session_id, $student_id ) {
		global $wpdb;
		$library_card = $wpdb->get_row( $wpdb->prepare( 'SELECT lc.ID, lc.card_number, lc.date_issued, lc.student_record_id FROM ' . WLSM_LIBRARY_CARDS . ' as lc 
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = lc.student_record_id 
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
		WHERE cs.school_id = %d AND ss.ID = %d AND sr.ID = %d', $school_id, $session_id, $student_id ) );
		return $library_card;
	}

	public static function get_book_title( $title ) {
		if ( $title ) {
			return stripcslashes( $title );
		}
		return '';
	}

	public static function get_book_author( $author ) {
		if ( $author ) {
			return stripcslashes( $author );
		}
		return '-';
	}

	public static function get_book_subject( $subject ) {
		if ( $subject ) {
			return stripcslashes( $subject );
		}
		return '-';
	}

	public static function get_book_rack_number( $rack_number ) {
		if ( $rack_number ) {
			return $rack_number;
		}
		return '-';
	}

	public static function get_book_number( $number ) {
		if ( $number ) {
			return $number;
		}
		return '-';
	}

	public static function get_book_isbn_number( $isbn_number ) {
		if ( $isbn_number ) {
			return $isbn_number;
		}
		return '-';
	}

	public static function get_book_issued_status_text( $returned_at ) {
		if ( $returned_at ) {
			return '<span class="wlsm-text-success text-success wlsm-font-bold">' . esc_html__( 'Returned' ,'school-management' ) . '</span>';
		}

		return '<span class="wlsm-text-primary text-primary wlsm-font-bold">' . esc_html__( 'Pending' ,'school-management' ) . '</span>';
	}

	public static function get_book_quantity( $quantity ) {
		$quantity = absint( $quantity );
		if ( $quantity ) {
			return $quantity;
		}
		return '-';
	}
}
