<?php
defined( 'ABSPATH' ) || die();

class WLSM_M_Staff_General {
	public static function fetch_class_sections( $class_school_id ) {
		global $wpdb;
		$sections = $wpdb->get_results( $wpdb->prepare( 'SELECT se.ID, se.label FROM ' . WLSM_SECTIONS . ' as se
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		WHERE se.class_school_id = %d', $class_school_id ) );
		return $sections;
	}

	public static function fetch_class_activity( $class_id ) {
		global $wpdb;
		$activity = $wpdb->get_results( $wpdb->prepare( 'SELECT se.ID, se.title, se.fees FROM ' . WLSM_ACTIVITIES . ' as se
		WHERE se.class_id = %d AND se.is_approved = 1', $class_id ) );
		return $activity;
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

	public static function fetch_section_students( $session_id, $section_id, $skip_transferred = false, $only_active = true ) {
		global $wpdb;

		if ( $only_active ) {
			$where = ' AND sr.is_active = 1';
		} else {
			$where = '';
		}

		if ( ! $skip_transferred ) {
			$students = $wpdb->get_results( $wpdb->prepare( 'SELECT sr.ID, sr.name, sr.enrollment_number FROM ' . WLSM_STUDENT_RECORDS . ' as sr
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			WHERE sr.session_id = %d AND sr.section_id = %d' . $where, $session_id, $section_id ) );
		} else {
			$students = $wpdb->get_results( $wpdb->prepare( 'SELECT sr.ID, sr.name, sr.enrollment_number FROM ' . WLSM_STUDENT_RECORDS . ' as sr
			LEFT OUTER JOIN ' . WLSM_TRANSFERS . ' as tf ON tf.from_student_record = sr.ID
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			WHERE sr.session_id = %d AND sr.section_id = %d' . $where . ' AND tf.ID IS NULL GROUP BY sr.ID', $session_id, $section_id ) );
		}

		return $students;
	}

	public static function fetch_sections_student_ids( $session_id, $section_ids, $skip_transferred = false, $only_active = true ) {
		global $wpdb;

		if ( $only_active ) {
			$where = ' AND sr.is_active = 1';
		} else {
			$where = '';
		}

		$values        = array( $session_id );
		$place_holders = array();

		foreach ( $section_ids as $section_id ) {
			array_push( $values, $section_id );
			array_push( $place_holders, '%d' );
		}

		if ( ! $skip_transferred ) {
			$student_ids = $wpdb->get_col( $wpdb->prepare( 'SELECT sr.ID FROM ' . WLSM_STUDENT_RECORDS . ' as sr
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			WHERE sr.session_id = %d AND sr.section_id IN(' . implode( ', ', $place_holders ) . ')' . $where, $values ) );
		} else {
			$student_ids = $wpdb->get_col( $wpdb->prepare( 'SELECT sr.ID FROM ' . WLSM_STUDENT_RECORDS . ' as sr
			LEFT OUTER JOIN ' . WLSM_TRANSFERS . ' as tf ON tf.from_student_record = sr.ID
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			WHERE sr.session_id = %d AND sr.section_id IN(' . implode( ', ', $place_holders ) . ') AND tf.ID IS NULL GROUP BY sr.ID', $values ) );
		}

		return $student_ids;
	}

	public static function fetch_class_students( $session_id, $class_school_id, $skip_transferred = false, $only_active = true ) {
		global $wpdb;

		if ( $only_active ) {
			$where = ' AND sr.is_active = 1';
		} else {
			$where = '';
		}

		if ( ! $skip_transferred ) {
			$students = $wpdb->get_results( $wpdb->prepare( 'SELECT sr.ID, sr.name, sr.enrollment_number FROM ' . WLSM_STUDENT_RECORDS . ' as sr
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			WHERE sr.session_id = %d AND se.class_school_id = %d' . $where, $session_id, $class_school_id ) );
		} else {
			$students = $wpdb->get_results( $wpdb->prepare( 'SELECT sr.ID, sr.name, sr.enrollment_number FROM ' . WLSM_STUDENT_RECORDS . ' as sr
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			LEFT OUTER JOIN ' . WLSM_TRANSFERS . ' as tf ON tf.from_student_record = sr.ID
			WHERE sr.session_id = %d AND se.class_school_id = %d' . $where . ' AND tf.ID IS NULL GROUP BY sr.ID', $session_id, $class_school_id ) );
		}

		return $students;
	}

	public static function fetch_school_classes( $school_id ) {
		global $wpdb;
		$classes = $wpdb->get_results( $wpdb->prepare( 'SELECT c.ID, c.label FROM ' . WLSM_CLASS_SCHOOL . ' as cs JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id AND cs.school_id = %d ORDER BY c.ID ASC', $school_id ) );
		return $classes;
	}

	public static function get_admitted_student_id( $school_id, $session_id, $admission_number, $skip_id = NULL ) {
		global $wpdb;

		if ( $skip_id ) {
			$skip_id = ' AND sr.ID != ' . absint( $skip_id );
		}

		$student = $wpdb->get_row( $wpdb->prepare( 'SELECT sr.ID FROM ' . WLSM_STUDENT_RECORDS . ' as sr
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		WHERE cs.school_id = %d AND sr.session_id = %d AND sr.admission_number = %s' . $skip_id, $school_id, $session_id, $admission_number ) );
		return $student;
	}

	public static function get_student_with_roll_number( $school_id, $session_id, $section_id, $roll_number, $skip_id = NULL ) {
		global $wpdb;

		if ( $skip_id ) {
			$skip_id = ' AND sr.ID != ' . absint( $skip_id );
		}

		$student = $wpdb->get_row( $wpdb->prepare( 'SELECT sr.ID FROM ' . WLSM_STUDENT_RECORDS . ' as sr
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		WHERE cs.school_id = %d AND sr.session_id = %d AND sr.section_id = %d AND sr.roll_number = %s' . $skip_id . ' GROUP BY sr.ID', $school_id, $session_id, $section_id, $roll_number ) );
		return $student;
	}

	public static function get_student_enrollment_number( $student_id ) {
		global $wpdb;

		return $wpdb->get_var(
			$wpdb->prepare( 'SELECT sr.enrollment_number FROM ' . WLSM_STUDENT_RECORDS . ' as sr WHERE sr.ID = %d', $student_id )
		);
	}

	public static function get_admissions_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_ADMISSIONS );
	}

	public static function get_students_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_STUDENTS );
	}

	public static function fetch_students_query( $school_id, $session_id, $filter, $restrict_to_section = false ) {
		require WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/partials/fetch_students_query.php';

		if ( $restrict_to_section ) {
			$section_where = ' AND sr.section_id = ' . absint( $restrict_to_section );
		} else {
			$section_where = '';
		}

		$query = 'SELECT sr.ID, sr.name as student_name, sr.phone, sr.student_type, sr.photo_id, sr.email, sr.address, sr.city, sr.state, sr.country, sr.gender, sr.dob, sr.religion, sr.caste, sr.blood_group, sr.father_occupation, sr.mother_name, sr.mother_phone, sr.mother_occupation, sr.father_name, sr.father_phone, sr.admission_number, sr.enrollment_number, sr.admission_date, sr.from_front, sr.gdpr_agreed, c.label as class_label, se.label as section_label, sr.roll_number, sr.is_active, u.user_email as login_email, u.user_login as username FROM ' . WLSM_STUDENT_RECORDS . ' as sr
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id ) . $where . $section_where;
		return $query;
	}

	public static function fetch_students_birthdays_query($school_id, $session_id, $start_date = null, $end_date = null) {
		// If start_date and end_date are not provided, use the current date.
		if (!$start_date) {
			$start_date = date('Y-m-d');
		}

		if (!$end_date) {
			$end_date = date('Y-m-d');
		}

		// Build the SQL query to fetch students with birthdays between start_date and end_date.
		$query = 'SELECT sr.ID, sr.name as student_name, sr.phone, sr.student_type, sr.photo_id, sr.email, sr.address, sr.city, sr.state, sr.country, sr.gender, sr.dob, sr.religion, sr.caste, sr.blood_group, sr.father_occupation, sr.mother_name, sr.mother_phone, sr.mother_occupation, sr.father_name, sr.father_phone, sr.admission_number, sr.enrollment_number, sr.admission_date, sr.from_front, sr.gdpr_agreed, c.label as class_label, se.label as section_label, sr.roll_number, sr.is_active, u.user_email as login_email, u.user_login as username FROM ' . WLSM_STUDENT_RECORDS . ' as sr
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id
		WHERE cs.school_id = ' . absint($school_id) . ' AND ss.ID = ' . absint($session_id) . ' AND DATE_FORMAT(sr.dob, "%m-%d") BETWEEN DATE_FORMAT("' . esc_sql($start_date) . '", "%m-%d") AND DATE_FORMAT("' . esc_sql($end_date) . '", "%m-%d")';

		return $query;
	}

	public static function fetch_students_query_bulk_admit_cards( $school_id, $session_id, $exam_id, $filter, $restrict_to_section = false ) {
		require WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/partials/fetch_students_query.php';

		if ( $restrict_to_section ) {
			$section_where = ' AND sr.section_id = ' . absint( $restrict_to_section );
		} else {
			$section_where = '';
		}

		$query = 'SELECT sr.ID, sr.name as student_name, sr.phone, sr.photo_id, sr.email, sr.address, sr.city, sr.state, sr.country, sr.gender, sr.dob, sr.religion, sr.caste, sr.blood_group, sr.father_occupation, sr.mother_name, sr.mother_phone, sr.mother_occupation, sr.father_name, sr.father_phone, sr.admission_number, sr.enrollment_number, sr.admission_date, sr.from_front, sr.gdpr_agreed, c.label as class_label, se.label as section_label, sr.roll_number, sr.is_active, u.user_email as login_email, u.user_login as username FROM ' . WLSM_STUDENT_RECORDS . ' as sr
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id ) . $where . $section_where;
		return $query;
	}

	public static function fetch_students_query_group_by() {
		$group_by = 'GROUP BY sr.ID';
		return $group_by;
	}

	public static function fetch_students_query_count( $school_id, $session_id, $filter, $restrict_to_section = false ) {
		require WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/partials/fetch_students_query.php';

		if ( $restrict_to_section ) {
			$section_where = ' AND sr.section_id = ' . absint( $restrict_to_section );
		} else {
			$section_where = '';
		}

		$query = 'SELECT COUNT(DISTINCT sr.ID) FROM ' . WLSM_STUDENT_RECORDS . ' as sr
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id ) . $where . $section_where;
		return $query;
	}

	public static function fetch_students_birthdays_count( $school_id, $session_id ) {
		$query = 'SELECT COUNT(DISTINCT sr.ID) FROM ' . WLSM_STUDENT_RECORDS . ' as sr
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id );
		return $query;
	}

	public static function get_student( $school_id, $session_id, $id, $skip_transferred = false, $only_active = false, $restrict_to_section = false ) {
		global $wpdb;

		if ( $only_active ) {
			$where = ' AND sr.is_active = 1';
		} else {
			$where = '';
		}

		if ( $restrict_to_section ) {
			$section_where = ' AND sr.section_id = ' . absint( $restrict_to_section );
		} else {
			$section_where = '';
		}

		if ( ! $skip_transferred ) {
			$student = $wpdb->get_row( $wpdb->prepare( 'SELECT sr.ID, sr.photo_id, sr.id_proof, sr.parent_id_proof, sr.user_id, sr.parent_user_id, se.class_school_id, cs.class_id FROM ' . WLSM_STUDENT_RECORDS . ' as sr JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id WHERE cs.school_id = %d AND sr.session_id = %d AND sr.ID = %d' . $where . $section_where, $school_id, $session_id, $id ) );
		} else {
			$student = $wpdb->get_row( $wpdb->prepare( 'SELECT sr.ID, sr.photo_id, sr.user_id, sr.parent_user_id, se.class_school_id, cs.class_id FROM ' . WLSM_STUDENT_RECORDS . ' as sr JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id LEFT OUTER JOIN ' . WLSM_TRANSFERS . ' as tf ON tf.from_student_record = sr.ID WHERE cs.school_id = %d AND sr.session_id = %d AND sr.ID = %d' . $where . $section_where . ' AND tf.ID IS NULL', $school_id, $session_id, $id ) );
		}

		return $student;
	}

	public static function fetch_student( $school_id, $session_id, $id, $restrict_to_section = false ) {
		global $wpdb;

		if ( $restrict_to_section ) {
			$section_where = ' AND sr.section_id = ' . absint( $restrict_to_section );
		} else {
			$section_where = '';
		}

		$student = $wpdb->get_row( $wpdb->prepare( 'SELECT sr.ID, sr.name as student_name, sr.gender, sr.phone, sr.email, sr.address, sr.city, sr.state, sr.country, sr.religion, sr.caste, sr.blood_group, sr.dob, sr.father_name, sr.student_type, sr.father_phone, sr.father_occupation, sr.mother_name, sr.mother_phone, sr.mother_occupation, sr.admission_number, sr.room_id, sr.enrollment_number, sr.admission_date, sr.photo_id, sr.id_number, sr.id_proof, sr.parent_id_proof, sr.note, sr.route_vehicle_id, sr.medium, sr.parent_user_id, sr.activities, c.ID as class_id, c.label as class_label, se.ID as section_id, se.label as section_label, se.class_school_id, sr.roll_number, sr.is_active, ss.label as session_label, ss.start_date, ss.end_date, u.user_email as login_email, u.user_login as username, tf.from_student_record, tf.to_school, sr.session_id FROM ' . WLSM_STUDENT_RECORDS . ' as sr
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id
		LEFT OUTER JOIN ' . WLSM_TRANSFERS . ' as tf ON tf.from_student_record = sr.ID
		WHERE cs.school_id = %d AND ss.ID = %d AND sr.ID = %d' . $section_where, $school_id, $session_id, $id ) );
		return $student;
	}

	public static function fetch_students( $school_id, $session_id, $class_id, $restrict_to_section = false ) {
		global $wpdb;

		if ( $restrict_to_section ) {
			$section_where = ' AND sr.section_id = ' . absint( $restrict_to_section );
		} else {
			$section_where = '';
		}

		$student = $wpdb->get_results( $wpdb->prepare( 'SELECT sr.ID, sr.name as student_name, sr.gender, sr.phone, sr.email, sr.address, sr.city, sr.state, sr.country, sr.religion, sr.caste, sr.blood_group, sr.dob, sr.father_name, sr.student_type, sr.father_phone, sr.father_occupation, sr.mother_name, sr.mother_phone, sr.mother_occupation, sr.admission_number, sr.room_id, sr.enrollment_number, sr.admission_date, sr.photo_id, sr.id_number, sr.id_proof, sr.parent_id_proof, sr.note, sr.route_vehicle_id, sr.parent_user_id, c.ID as class_id, c.label as class_label, se.ID as section_id, se.label as section_label, se.class_school_id, sr.roll_number, sr.is_active, ss.label as session_label, ss.start_date, ss.end_date, u.user_email as login_email, u.user_login as username, tf.from_student_record, tf.to_school FROM ' . WLSM_STUDENT_RECORDS . ' as sr
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id
		LEFT OUTER JOIN ' . WLSM_TRANSFERS . ' as tf ON tf.from_student_record = sr.ID
		WHERE cs.school_id = %d AND ss.ID = %d AND c.ID = %d' . $section_where, $school_id, $session_id, $class_id ) );
		return $student;
	}

	public static function fetch_subjects($student_id){
		global $wpdb;

		$subjects = $wpdb->get_results($wpdb->prepare('SELECT sub.ID as ID, sub.label FROM '. WLSM_STUDENTS_SUBJECTS.'  as ss
		JOIN ' . WLSM_STUDENT_RECORDS. ' as sr ON sr.ID = ss.student_id
		JOIN ' . WLSM_SUBJECTS. ' as sub ON sub.ID = ss.subject_id
		WHERE ss.student_id = %s', $student_id ));
		return $subjects;
	}

	public static function fetch_invoices($school_id, $session_id, $period){
		global $wpdb;

		$invoices = $wpdb->get_results($wpdb->prepare('SELECT sr.ID as student_record_id, ss.end_date, sf.period, sf.label, sf.amount   FROM '. WLSM_STUDENT_FEES.'  as sf
		JOIN ' . WLSM_STUDENT_RECORDS. ' as sr ON sr.ID = sf.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		WHERE cs.school_id = %d AND sr.session_id = %d AND sf.period = %s', $school_id, $session_id, $period ));
		return $invoices;
	}

	public static function get_student_record( $school_id, $id ) {
		global $wpdb;
		$student = $wpdb->get_row( $wpdb->prepare( 'SELECT sr.ID, sr.name as student_name, sr.enrollment_number, sr.roll_number, c.label as class_label, se.ID as section_id, se.label as section_label, ss.label as session_label, u.user_email as login_email, u.user_login as username, tf.from_student_record, tf.to_school FROM ' . WLSM_STUDENT_RECORDS . ' as sr
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id
		LEFT OUTER JOIN ' . WLSM_TRANSFERS . ' as tf ON tf.from_student_record = sr.ID
		WHERE cs.school_id = %d AND sr.ID = %d', $school_id, $id ) );
		return $student;
	}

	public static function get_students_count( $school_id, $session_id, $ids, $skip_transferred = false, $only_active = false ) {
		global $wpdb;

		if ( $only_active ) {
			$where = ' AND sr.is_active = 1';
		} else {
			$where = '';
		}

		$ids_count = count( $ids );

		$place_holders = array_fill( 0, $ids_count, '%s' );

		$ids_format = implode( ', ', $place_holders );

		$prepare = array_merge( array( $school_id, $session_id ), $ids );

		if ( ! $skip_transferred ) {
			$students_count = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(DISTINCT sr.ID) FROM ' . WLSM_STUDENT_RECORDS . ' as sr JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id WHERE cs.school_id = %d AND sr.session_id = %d AND sr.ID IN (' . $ids_format . ')' . $where, $prepare ) );
		} else {
			$students_count = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(DISTINCT sr.ID) FROM ' . WLSM_STUDENT_RECORDS . ' as sr JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id LEFT OUTER JOIN ' . WLSM_TRANSFERS . ' as tf ON tf.from_student_record = sr.ID WHERE cs.school_id = %d AND sr.session_id = %d AND sr.ID IN (' . $ids_format . ')' . $where . ' AND tf.ID IS NULL', $prepare ) );
		}

		return $students_count;
	}

	public static function get_admins_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_ADMINS );
	}

	public static function get_employees_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_EMPLOYEES );
	}

	public static function fetch_staff_query( $school_id, $role ) {
		$query = 'SELECT a.ID, a.name, a.phone, a.email, a.salary, a.designation, a.joining_date, a.assigned_by_manager, a.is_active, r.name as role_name, u.user_email as login_email, u.user_login as username FROM ' . WLSM_ADMINS . ' as a
		JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
		LEFT OUTER JOIN ' . WLSM_ROLES . ' as r ON r.ID = a.role_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
		WHERE sf.role = "' . sanitize_text_field( $role ) . '" AND sf.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function fetch_staff_query_group_by() {
		$group_by = 'GROUP BY a.ID';
		return $group_by;
	}

	public static function fetch_staff_query_count( $school_id, $role ) {
		$query = 'SELECT COUNT(DISTINCT a.ID) FROM ' . WLSM_ADMINS . ' as a
		JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
		LEFT OUTER JOIN ' . WLSM_ROLES . ' as r ON r.ID = a.role_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
		WHERE sf.role = "' . sanitize_text_field( $role ) . '" AND sf.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function get_staff( $school_id, $role, $id ) {
		global $wpdb;
		$staff = $wpdb->get_row( $wpdb->prepare( 'SELECT a.ID, a.staff_id, sf.user_id, a.assigned_by_manager FROM ' . WLSM_ADMINS . ' as a
		JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
		WHERE sf.role = "%s" AND sf.school_id = %d AND a.ID = %d', $role, $school_id, $id ) );
		return $staff;
	}

	public static function get_staff_id($id ) {
		global $wpdb;
		$staff = $wpdb->get_row( $wpdb->prepare( 'SELECT a.ID, a.staff_id, sf.user_id FROM ' . WLSM_ADMINS . ' as a
		JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
		WHERE a.ID = %d', $id ) );
		return $staff;
	}

	public static function get_staff_id_from_user($user_id ) {
		global $wpdb;
		$staff = $wpdb->get_row( $wpdb->prepare( 'SELECT a.ID, a.staff_id, sf.user_id FROM ' . WLSM_ADMINS . ' as a
		JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
		WHERE u.ID = %d', $user_id ) );
		return $staff;
	}

	public static function get_active_admin( $school_id, $id ) {
		global $wpdb;
		$staff = $wpdb->get_row( $wpdb->prepare( 'SELECT a.ID, a.staff_id, sf.user_id, a.assigned_by_manager FROM ' . WLSM_ADMINS . ' as a
		JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
		WHERE sf.school_id = %d AND a.is_active = 1 AND a.ID = %d', $school_id, $id ) );
		return $staff;
	}

	public static function fetch_staff( $school_id, $role, $id ) {
		global $wpdb;
		$staff = $wpdb->get_row( $wpdb->prepare( 'SELECT a.ID, a.name, a.gender, a.dob, a.phone, a.email, a.address, a.salary, a.designation, a.qualification, a.note, a.joining_date, a.role_id, a.assigned_by_manager, a.is_active, sf.role, sf.permissions, r.name as role_name, u.user_email as login_email, u.user_login as username, a.section_id, a.vehicle_id, cs.ID as class_school_id, cs.class_id FROM ' . WLSM_ADMINS . ' as a
		JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
		LEFT OUTER JOIN ' . WLSM_ROLES . ' as r ON r.ID = a.role_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
		LEFT OUTER JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = a.section_id
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		WHERE sf.role = "%s" AND sf.school_id = %d AND a.ID = %d', $role, $school_id, $id ) );
		return $staff;
	}

	public static function get_staff_attendance_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_EMPLOYEES_ATTENDANCE );
	}

	public static function get_roles_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_ROLES );
	}

	public static function fetch_role_query( $school_id ) {
		$query = 'SELECT r.ID, r.name FROM ' . WLSM_ROLES . ' as r
		WHERE r.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function fetch_role_query_group_by() {
		$group_by = 'GROUP BY r.ID';
		return $group_by;
	}

	public static function fetch_role_query_count( $school_id ) {
		$query = 'SELECT COUNT(r.ID) FROM ' . WLSM_ROLES . ' as r
		WHERE r.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function get_role( $school_id, $id ) {
		global $wpdb;
		$role = $wpdb->get_row( $wpdb->prepare( 'SELECT r.ID FROM ' . WLSM_ROLES . ' as r
		WHERE r.school_id = %d AND r.ID = %d', $school_id, $id ) );
		return $role;
	}

	public static function fetch_role( $school_id, $id ) {
		global $wpdb;
		$role = $wpdb->get_row( $wpdb->prepare( 'SELECT r.ID, r.name, r.permissions FROM ' . WLSM_ROLES . ' as r
		WHERE r.school_id = %d AND r.ID = %d', $school_id, $id ) );
		return $role;
	}

	public static function get_certificates_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_CERTIFICATES );
	}

	public static function get_school_certificates( $school_id ) {
		global $wpdb;
		$certificates = $wpdb->get_results(
			$wpdb->prepare( 'SELECT cf.ID, cf.label FROM ' . WLSM_CERTIFICATES . ' as cf WHERE cf.school_id = %d', $school_id )
		);
		return $certificates;
	}

	public static function fetch_certificate_query( $school_id, $session_id ) {
		$query = 'SELECT cf.ID, cf.label, COUNT(sr.ID) as students_count FROM ' . WLSM_CERTIFICATES . ' as cf
		LEFT OUTER JOIN ' . WLSM_CERTIFICATE_STUDENT . ' as cfsr ON cfsr.certificate_id = cf.ID
		LEFT OUTER JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON cfsr.student_record_id = sr.ID AND sr.session_id = ' . absint( $session_id ) . '
		WHERE cf.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function fetch_certificate_query_group_by() {
		$group_by = 'GROUP BY cf.ID';
		return $group_by;
	}

	public static function fetch_certificate_query_count( $school_id, $session_id ) {
		$query = 'SELECT COUNT(DISTINCT cf.ID) FROM ' . WLSM_CERTIFICATES . ' as cf
		LEFT OUTER JOIN ' . WLSM_CERTIFICATE_STUDENT . ' as cfsr ON cfsr.certificate_id = cf.ID
		LEFT OUTER JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON cfsr.student_record_id = sr.ID AND sr.session_id = ' . absint( $session_id ) . '
		WHERE cf.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function get_certificate( $school_id, $id ) {
		global $wpdb;
		$certificate = $wpdb->get_row( $wpdb->prepare( 'SELECT cf.ID, cf.image_id FROM ' . WLSM_CERTIFICATES . ' as cf
		WHERE cf.school_id = %d AND cf.ID = %d', $school_id, $id ) );
		return $certificate;
	}

	public static function fetch_certificate( $school_id, $id ) {
		global $wpdb;
		$certificate = $wpdb->get_row( $wpdb->prepare( 'SELECT cf.ID, cf.label, cf.image_id, cf.fields FROM ' . WLSM_CERTIFICATES . ' as cf
		WHERE cf.school_id = %d AND cf.ID = %d', $school_id, $id ) );
		return $certificate;
	}

	public static function get_certificate_students( $school_id, $session_id, $certificate_id ) {
		global $wpdb;
		$students = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT cfsr.ID, cfsr.certificate_number, sr.ID as student_id, sr.enrollment_number, sr.roll_number, sr.name as student_name, sr.phone, c.label as class_label, se.label as section_label FROM ' . WLSM_CERTIFICATE_STUDENT . ' as cfsr
				JOIN ' . WLSM_CERTIFICATES . ' as cf ON cf.ID = cfsr.certificate_id
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = cfsr.student_record_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				WHERE cs.school_id = %d AND sr.session_id = %d AND cf.ID = %d GROUP BY cfsr.ID', $school_id, $session_id, $certificate_id
			)
		);
		return $students;
	}

	public static function get_certificate_distributed( $school_id, $session_id, $certificate_student_id ) {
		global $wpdb;
		$certificate_distributed = $wpdb->get_row(
			$wpdb->prepare( 'SELECT cfsr.ID FROM ' . WLSM_CERTIFICATE_STUDENT . ' as cfsr
				JOIN ' . WLSM_CERTIFICATES . ' as cf ON cf.ID = cfsr.certificate_id
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = cfsr.student_record_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				WHERE cs.school_id = %d AND sr.session_id = %d AND cfsr.ID = %d', $school_id, $session_id, $certificate_student_id
			)
		);
		return $certificate_distributed;
	}

	public static function fetch_certificate_distributed( $school_id, $session_id, $certificate_student_id ) {
		global $wpdb;
		$certificate_distributed = $wpdb->get_row(
			$wpdb->prepare( 'SELECT cfsr.ID, cfsr.certificate_id, cfsr.student_record_id as student_id, cfsr.certificate_number, cfsr.date_issued, cf.label, ss.ID as session_id, ss.label as session_label, ss.start_date as session_start_date, ss.end_date as session_end_date, cf.exam_id FROM ' . WLSM_CERTIFICATE_STUDENT . ' as cfsr
				JOIN ' . WLSM_CERTIFICATES . ' as cf ON cf.ID = cfsr.certificate_id
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = cfsr.student_record_id
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				WHERE cs.school_id = %d AND sr.session_id = %d AND cfsr.ID = %d', $school_id, $session_id, $certificate_student_id
			)
		);
		return $certificate_distributed;
	}

	public static function fetch_certificates_distributed_query( $school_id, $session_id, $certificate_id ) {
		$query = 'SELECT cfsr.ID, cfsr.certificate_number, cfsr.date_issued, sr.ID as student_id, sr.enrollment_number, sr.roll_number, sr.name as student_name, sr.phone, c.label as class_label, se.label as section_label FROM ' . WLSM_CERTIFICATE_STUDENT . ' as cfsr
		JOIN ' . WLSM_CERTIFICATES . ' as cf ON cf.ID = cfsr.certificate_id
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = cfsr.student_record_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND sr.session_id = ' . absint( $session_id ) . ' AND cf.ID = ' . absint( $certificate_id );
		return $query;
	}

	public static function fetch_certificates_distributed_query_group_by() {
		$group_by = 'GROUP BY cfsr.ID';
		return $group_by;
	}

	public static function fetch_certificates_distributed_query_count( $school_id, $session_id, $certificate_id ) {
		$query = 'SELECT COUNT(DISTINCT cfsr.ID) FROM ' . WLSM_CERTIFICATE_STUDENT . ' as cfsr
		JOIN ' . WLSM_CERTIFICATES . ' as cf ON cf.ID = cfsr.certificate_id
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = cfsr.student_record_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND sr.session_id = ' . absint( $session_id ) . ' AND cf.ID = ' . absint( $certificate_id );
		return $query;
	}

	public static function get_student_certificates( $student_id ) {
		global $wpdb;
		$certificates_distributed = $wpdb->get_results(
			$wpdb->prepare( 'SELECT cfsr.ID, cfsr.certificate_id, cfsr.certificate_number, cfsr.date_issued, cf.label, ss.label as session_label, ss.start_date as session_start_date, ss.end_date as session_end_date FROM ' . WLSM_CERTIFICATE_STUDENT . ' as cfsr
				JOIN ' . WLSM_CERTIFICATES . ' as cf ON cf.ID = cfsr.certificate_id
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = cfsr.student_record_id
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				WHERE cfsr.student_record_id = %d GROUP BY cfsr.ID', $student_id )
		);
		return $certificates_distributed;
	}

	public static function get_certificate_number( $school_id ) {
		global $wpdb;

		$last_certificate_count = $wpdb->get_var(
			$wpdb->prepare( 'SELECT last_certificate_count FROM ' . WLSM_SCHOOLS . ' as s WHERE s.ID = %d', $school_id )
		);

		$new_certificate_count = absint( $last_certificate_count ) + 1;

		$data = array(
			'last_certificate_count' => $new_certificate_count,
		);

		// Certificate number formatting.
		$certificate_number = $new_certificate_count;

		$success = $wpdb->update( WLSM_SCHOOLS, $data, array( 'ID' => $school_id ) );

		$buffer = ob_get_clean();
		if ( ! empty( $buffer ) ) {
			throw new Exception( $buffer );
		}

		if ( false === $success ) {
			throw new Exception( $wpdb->last_error );
		}

		return $certificate_number;
	}

	public static function fetch_inquiry_query( $school_id ) {
		$query = 'SELECT iq.ID, iq.name, iq.phone, iq.email, iq.message, iq.note, iq.created_at, iq.next_follow_up, iq.is_active, iq.gdpr_agreed, c.label as class_label FROM ' . WLSM_INQUIRIES . ' as iq
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = iq.school_id
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = iq.class_school_id
		LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE iq.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function fetch_inquiry_query_group_by() {
		$group_by = 'GROUP BY iq.ID';
		return $group_by;
	}

	public static function fetch_inquiry_query_count( $school_id ) {
		$query = 'SELECT COUNT(DISTINCT iq.ID) FROM ' . WLSM_INQUIRIES . ' as iq
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = iq.school_id
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = iq.class_school_id
		LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE iq.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function get_inquiry( $school_id, $id ) {
		global $wpdb;
		$inquiry = $wpdb->get_row( $wpdb->prepare( 'SELECT iq.ID FROM ' . WLSM_INQUIRIES . ' as iq
		WHERE iq.school_id = %d AND iq.ID = %d', $school_id, $id ) );
		return $inquiry;
	}

	public static function get_inquiry_email( $email ) {
		global $wpdb;
		$inquiry = $wpdb->get_row( $wpdb->prepare( 'SELECT iq.ID FROM ' . WLSM_INQUIRIES . ' as iq
		WHERE iq.email = %s', $email) );
		return $inquiry;
	}

	public static function fetch_inquiry( $school_id, $id ) {
		global $wpdb;
		$inquiry = $wpdb->get_row( $wpdb->prepare( 'SELECT iq.ID, iq.name, iq.phone, iq.email, iq.reference, iq.message, iq.note, iq.next_follow_up, iq.is_active, c.ID as class_id, iq.section_id, iq.class_school_id FROM ' . WLSM_INQUIRIES . ' as iq
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = iq.school_id
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = iq.class_school_id
		LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE iq.school_id = %d AND iq.ID = %d', $school_id, $id ) );
		return $inquiry;
	}

	public static function get_inquiry_message( $school_id, $id ) {
		global $wpdb;
		$inquiry = $wpdb->get_row( $wpdb->prepare( 'SELECT iq.ID, iq.message FROM ' . WLSM_INQUIRIES . ' as iq WHERE iq.school_id = %d AND iq.ID = %d', $school_id, $id ) );
		return $inquiry;
	}

	public static function get_class_school( $school_id, $class_id ) {
		global $wpdb;
		$class_school = $wpdb->get_row( $wpdb->prepare( 'SELECT cs.ID, cs.default_section_id, c.label FROM ' . WLSM_CLASS_SCHOOL . ' as cs JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id WHERE cs.school_id = %d AND cs.class_id = %d', $school_id, $class_id ) );
		return $class_school;
	}

	public static function get_class_students( $school_id, $session_id, $class_id ) {
		global $wpdb;
		$students = $wpdb->get_results( $wpdb->prepare( 'SELECT sr.ID, sr.name, sr.phone, sr.roll_number, sr.enrollment_number, se.label as section_label FROM ' . WLSM_STUDENT_RECORDS . ' as sr
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			LEFT OUTER JOIN ' . WLSM_PROMOTIONS . ' as pm ON pm.from_student_record = sr.ID
			WHERE cs.school_id = %d AND ss.ID = %d AND cs.class_id = %d AND sr.is_active = 1 AND pm.ID IS NULL GROUP BY sr.ID ORDER BY sr.name', $school_id, $session_id, $class_id ) );
		return $students;
	}

	public static function get_class_students_data( $school_id, $session_id, $class_id ) {
		global $wpdb;
		$students = $wpdb->get_results( $wpdb->prepare( 'SELECT sr.* FROM ' . WLSM_STUDENT_RECORDS . ' as sr
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			LEFT OUTER JOIN ' . WLSM_PROMOTIONS . ' as pm ON pm.from_student_record = sr.ID
			WHERE cs.school_id = %d AND ss.ID = %d AND cs.class_id = %d AND sr.is_active = 1 AND pm.ID IS NULL GROUP BY sr.ID ORDER BY sr.name', $school_id, $session_id, $class_id ), OBJECT_K );
		return $students;
	}

	public static function get_student_to_transfer( $school_id, $session_id, $student_id ) {
		global $wpdb;
		$student = $wpdb->get_row( $wpdb->prepare( 'SELECT sr.* FROM ' . WLSM_STUDENT_RECORDS . ' as sr
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			LEFT OUTER JOIN ' . WLSM_TRANSFERS . ' as tf ON tf.from_student_record = sr.ID
			WHERE cs.school_id = %d AND ss.ID = %d AND sr.ID = %d AND tf.ID IS NULL GROUP BY sr.ID ORDER BY sr.name', $school_id, $session_id, $student_id ) );
		return $student;
	}

	public static function is_next_session( $current_session_id, $new_session_id ) {
		global $wpdb;

		$current_session = $wpdb->get_row( $wpdb->prepare( 'SELECT ss.ID, ss.end_date FROM ' . WLSM_SESSIONS . ' as ss
			WHERE ID = %d', $current_session_id ) );

		$new_session = $wpdb->get_row( $wpdb->prepare( 'SELECT ss.ID, ss.start_date FROM ' . WLSM_SESSIONS . ' as ss
			WHERE ID = %d', $new_session_id ) );

		if ( $current_session->end_date > $new_session->start_date ) {
			return false;
		}

		return true;
	}

	public static function get_enrollment_number( $school_id ) {
		global $wpdb;

		$school = WLSM_M_School::get_school( $school_id );

		$prefix  = $school->enrollment_prefix;
		$base    = absint( $school->enrollment_base );
		$padding = absint( $school->enrollment_padding );

		$enrollment_settings = WLSM_Config::default_enrollment_settings();
		if ( ! $prefix ) {
			$prefix = $enrollment_settings['prefix'];
		}
		if ( ! $base ) {
			$base = $enrollment_settings['base'];
		}
		if ( ! $padding ) {
			$padding = $enrollment_settings['padding'];
		}

		$last_enrollment_count = $wpdb->get_var(
			$wpdb->prepare( 'SELECT last_enrollment_count FROM ' . WLSM_SCHOOLS . ' as s WHERE s.ID = %d', $school_id )
		);

		$new_enrollment_count = absint( $last_enrollment_count ) + 1;

		$data = array(
			'last_enrollment_count' => $new_enrollment_count,
		);

		// Enrollment number formatting.
		$enrollment_number = $prefix . str_pad( $new_enrollment_count + $base, $padding, '0', STR_PAD_LEFT );

		$success = $wpdb->update( WLSM_SCHOOLS, $data, array( 'ID' => $school_id ) );

		$buffer = ob_get_clean();
		if ( ! empty( $buffer ) ) {
			throw new Exception( $buffer );
		}

		if ( false === $success ) {
			throw new Exception( $wpdb->last_error );
		}

		return $enrollment_number;
	}

	public static function get_admission_number( $school_id, $session_id ) {
		global $wpdb;

		$school = WLSM_M_School::get_school( $school_id );

		$prefix  = $school->admission_prefix;
		$base    = absint( $school->admission_base );
		$padding = absint( $school->admission_padding );

		$admission_settings = WLSM_Config::default_admission_settings();
		if ( ! $prefix ) {
			$prefix = $admission_settings['prefix'];
		}
		if ( ! $base ) {
			$base = $admission_settings['base'];
		}
		if ( ! $padding ) {
			$padding = $admission_settings['padding'];
		}

		$last_admission_count = $wpdb->get_var(
			$wpdb->prepare( 'SELECT last_admission_count FROM ' . WLSM_SCHOOLS . ' as s WHERE s.ID = %d', $school_id )
		);

		$new_admission_count = absint( $last_admission_count ) + 1;

		$data = array(
			'last_admission_count' => $new_admission_count,
		);

		// Admission number formatting.
		$admission_number = $prefix . str_pad( $new_admission_count + $base, $padding, '0', STR_PAD_LEFT );

		$success = $wpdb->update( WLSM_SCHOOLS, $data, array( 'ID' => $school_id ) );

		$buffer = ob_get_clean();
		if ( ! empty( $buffer ) ) {
			throw new Exception( $buffer );
		}

		if ( false === $success ) {
			throw new Exception( $wpdb->last_error );
		}

		// Checks if admission number already exists for this session.
		$student_exists = WLSM_M_Staff_General::get_admitted_student_id( $school_id, $session_id, $admission_number );
		if ( $student_exists ) {
			return self::get_admission_number( $school_id, $session_id );
		} else {
			return $admission_number;
		}
	}

	public static function get_roll_number( $school_id, $session_id, $class_id, $roll_number = '' ) {
		global $wpdb;

		// If roll number is not passed, then get last non-empty roll number in the class for this session.
		if ( ! $roll_number ) {
			$roll_number = $wpdb->get_var( $wpdb->prepare( 'SELECT sr.roll_number FROM ' . WLSM_STUDENT_RECORDS . ' as sr
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				WHERE cs.school_id = %d AND sr.session_id = %d AND cs.class_id = %d AND sr.roll_number IS NOT NULL AND sr.roll_number != "" AND sr.roll_number REGEXP "^[0-9]+$" GROUP BY sr.ID ORDER BY sr.ID DESC LIMIT 1', $school_id, $session_id, $class_id ) );

			// If roll number is not found in database, set it to 0.
			if ( ! $roll_number ) {
				$roll_number = 0;
			}
		}

		// Increment the roll number by 1.
		$roll_number = absint( $roll_number ) + 1;

		// Checks if roll number already exists in the class for this session.
		$student_exists = self::get_student_with_roll_number( $school_id, $session_id, $class_id, $roll_number );
		if ( $student_exists ) {
			// If roll number already exists, then recall the method.
			return self::get_student_with_roll_number( $school_id, $session_id, $class_id, $roll_number );
		} else {
			return $roll_number;
		}
	}

	public static function get_transfer_student_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_TRANSFER_STUDENT );
	}

	public static function get_transfer_to_schools( $skip_school_id ) {
		global $wpdb;
		$schools = $wpdb->get_results( $wpdb->prepare( 'SELECT s.ID, s.label FROM ' . WLSM_SCHOOLS . ' as s WHERE s.ID != %d AND s.is_active = 1', $skip_school_id ) );
		return $schools;
	}

	public static function fetch_transferred_to_query( $school_id, $session_id ) {
		$query = 'SELECT sr.ID, sr.name as student_name, sr.phone, sr.email, sr.father_name, sr.father_phone, sr.admission_number, sr.enrollment_number, sr.is_active, sr.admission_date, c.label as class_label, se.label as section_label, sr.roll_number, tf.to_school, tf.note, tf.created_at as transfer_date FROM ' . WLSM_TRANSFERS . ' as tf
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = tf.from_student_record
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id );
		return $query;
	}

	public static function fetch_transferred_to_query_group_by() {
		$group_by = 'GROUP BY sr.ID';
		return $group_by;
	}

	public static function fetch_transferred_to_query_count( $school_id, $session_id ) {
		$query = 'SELECT COUNT(DISTINCT sr.ID) FROM ' . WLSM_TRANSFERS . ' as tf
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = tf.from_student_record
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id );
		return $query;
	}

	public static function get_transferred_to( $school_id, $session_id, $student_id ) {
		global $wpdb;
		$student = $wpdb->get_row( $wpdb->prepare( 'SELECT sr.ID, tf.to_student_record, tf.ID as transfer_id FROM ' . WLSM_TRANSFERS . ' as tf
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = tf.from_student_record
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		WHERE cs.school_id = %d AND ss.ID = %d AND tf.from_student_record = %d GROUP BY sr.ID', $school_id, $session_id, $student_id ) );
		return $student;
	}

	public static function get_transferred_to_school_note( $school_id, $session_id, $student_id ) {
		global $wpdb;
		$student = $wpdb->get_row( $wpdb->prepare( 'SELECT tf.note FROM ' . WLSM_TRANSFERS . ' as tf
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = tf.from_student_record
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		WHERE cs.school_id = %d AND ss.ID = %d AND tf.from_student_record = %d GROUP BY sr.ID', $school_id, $session_id, $student_id ) );
		return $student;
	}

	public static function fetch_transferred_from_query( $school_id, $session_id ) {
		$query = 'SELECT sr.ID, sr.name as student_name, sr.phone, sr.email, sr.father_name, sr.father_phone, sr.admission_number, sr.enrollment_number, sr.is_active, sr.admission_date, c.label as class_label, se.label as section_label, sr.roll_number, tf.to_school, tf.note, tf.created_at as transfer_date FROM ' . WLSM_TRANSFERS . ' as tf
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = tf.to_student_record
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id );
		return $query;
	}

	public static function fetch_transferred_from_query_group_by() {
		$group_by = 'GROUP BY sr.ID';
		return $group_by;
	}

	public static function fetch_transferred_from_query_count( $school_id, $session_id ) {
		$query = 'SELECT COUNT(DISTINCT sr.ID) FROM ' . WLSM_TRANSFERS . ' as tf
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = tf.to_student_record
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = ' . absint( $school_id ) . ' AND ss.ID = ' . absint( $session_id );
		return $query;
	}

	public static function get_transferred_from( $school_id, $session_id, $student_id ) {
		global $wpdb;
		$student = $wpdb->get_row(
			$wpdb->prepare( 'SELECT sr.ID, tf.from_student_record, tf.ID as transfer_id FROM ' . WLSM_TRANSFERS . ' as tf
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = tf.to_student_record
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				WHERE cs.school_id = %d AND ss.ID = %d AND tf.to_student_record = %d GROUP BY sr.ID', $school_id, $session_id, $student_id )
		);
		return $student;
	}

	public static function get_transferred_from_school_note( $school_id, $session_id, $student_id ) {
		global $wpdb;
		$student = $wpdb->get_row(
			$wpdb->prepare( 'SELECT tf.note FROM ' . WLSM_TRANSFERS . ' as tf
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = tf.to_student_record
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				WHERE cs.school_id = %d AND ss.ID = %d AND tf.to_student_record = %d GROUP BY sr.ID', $school_id, $session_id, $student_id )
		);
		return $student;
	}

	public static function get_not_transferred_active_student( $student_id ) {
		global $wpdb;
		$student = $wpdb->get_row(
			$wpdb->prepare( 'SELECT sr.ID, cs.school_id, sr.name as student_name, sr.father_name, sr.enrollment_number, c.label as class_label, se.label as section_label, sr.roll_number FROM ' . WLSM_STUDENT_RECORDS . ' as sr
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				LEFT OUTER JOIN ' . WLSM_TRANSFERS . ' as tf ON tf.from_student_record = sr.ID
				WHERE sr.is_active = 1 AND tf.ID IS NULL AND sr.ID = %d', $student_id )
		);
		return $student;
	}

	public static function get_not_transferred_active_student_pending( $student_id ) {
		global $wpdb;
		$student = $wpdb->get_row(
			$wpdb->prepare( 'SELECT sr.ID, cs.school_id, sr.name as student_name, sr.father_name, sr.enrollment_number, c.label as class_label, se.label as section_label, sr.roll_number FROM ' . WLSM_STUDENT_RECORDS . ' as sr
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				LEFT OUTER JOIN ' . WLSM_TRANSFERS . ' as tf ON tf.from_student_record = sr.ID
				WHERE tf.ID IS NULL AND sr.ID = %d', $student_id )
		);
		return $student;
	}


	public static function get_inquiries_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_INQUIRIES );
	}

	public static function get_student_attendance_report( $student_id ) {
		global $wpdb;
		$attendance = $wpdb->get_results(
			$wpdb->prepare( 'SELECT Year(attendance_date) as year, Month(attendance_date) as month, COUNT(DISTINCT DATE(attendance_date)) AS total_days, COUNT(student_record_id) as total_attendance, COALESCE(SUM(if(status = "a", 1, 0)), 0) as total_absent, COALESCE(SUM(if(status = "p", 1, 0)), 0) as total_present, attendance_date, COALESCE(SUM(if(status = "l", 1, 0)), 0) as total_late , COALESCE(SUM(if(status = "h", 1, 0)), 0) as total_holiday    FROM ' . WLSM_ATTENDANCE . ' WHERE student_record_id = %d AND status IN("a", "p", "l", "h") GROUP BY Year(attendance_date), Month(attendance_date) ORDER BY year DESC, month DESC', $student_id )
		);
		return $attendance;
	}

	public static function get_student_attendance_stats( $student_id ) {
		$attendance = self::get_student_attendance_report( $student_id );

		$total_attendance = 0;
		$total_present    = 0;
		$total_absent     = 0;
		$total_days       = 0;
		foreach ( $attendance as $monthly ) {
			$month = new DateTime();
			$month->setDate( $monthly->year, $monthly->month, 1 );
			$total_attendance += $monthly->total_attendance;
			$total_days       += $monthly->total_days;
			$total_present    += $monthly->total_present;
			$total_absent     += $monthly->total_absent;
		}

		return array(
			'total_attendance' => $total_attendance,
			'total_present'    => $total_present,
			'total_absent'     => $total_absent,
			'percentage_value' => WLSM_Config::sanitize_percentage( $total_days, $total_present, 1 ),
			'percentage_text'  => WLSM_Config::get_percentage_text( $total_days, $total_present, 1 )
		);
	}

	public static function get_staff_attendance_report( $admin_id ) {
		global $wpdb;
		$attendance = $wpdb->get_results(
			$wpdb->prepare( 'SELECT Year(attendance_date) as year, Month(attendance_date) as month, COUNT(admin_id) as total_attendance, COALESCE(SUM(if(status = "a", 1, 0)), 0) as total_absent, COALESCE(SUM(if(status = "p", 1, 0)), 0) as total_present, attendance_date FROM ' . WLSM_STAFF_ATTENDANCE . ' WHERE admin_id = %d AND status IN("a", "p") GROUP BY Year(attendance_date), Month(attendance_date) ORDER BY year DESC, month DESC', $admin_id )
		);
		return $attendance;
	}

	public static function get_notifications_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_NOTIFICATIONS );
	}

	public static function get_staff_leaves_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_EMPLOYEE_LEAVES );
	}

	public static function fetch_staff_leave_query( $school_id, $admin_id = '' ) {
		$query = 'SELECT lv.ID, lv.description, lv.start_date, lv.end_date, lv.is_approved, lv.approved_by, a.name, a.phone, u.user_login as username FROM ' . WLSM_LEAVES . ' as lv
		JOIN ' . WLSM_ADMINS . ' as a ON a.ID = lv.admin_id
		JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
		WHERE lv.school_id = ' . absint( $school_id ) . ' AND a.is_active = 1';

		if ( $admin_id ) {
			$query .= ' AND a.ID = ' . absint( $admin_id );
		}

		return $query;
	}

	public static function fetch_staff_leave_query_group_by() {
		$group_by = 'GROUP BY lv.ID';
		return $group_by;
	}

	public static function fetch_staff_leave_query_count( $school_id, $admin_id = '' ) {
		$query = 'SELECT COUNT(DISTINCT lv.ID) FROM ' . WLSM_LEAVES . ' as lv
		JOIN ' . WLSM_ADMINS . ' as a ON a.ID = lv.admin_id
		JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
		WHERE lv.school_id = ' . absint( $school_id ) . ' AND a.is_active = 1';

		if ( $admin_id ) {
			$query .= ' AND a.ID = ' . absint( $admin_id );
		}

		return $query;
	}

	public static function get_staff_leave( $school_id, $id ) {
		global $wpdb;
		$staff_leave = $wpdb->get_row( $wpdb->prepare( 'SELECT lv.ID, lv.approved_by FROM ' . WLSM_LEAVES . ' as lv
		JOIN ' . WLSM_ADMINS . ' as a ON a.ID = lv.admin_id
		JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
		WHERE lv.school_id = %d AND a.is_active = 1 AND lv.ID = %d', $school_id, $id ) );
		return $staff_leave;
	}

	public static function fetch_staff_leave( $school_id, $id ) {
		global $wpdb;
		$staff_leave = $wpdb->get_row( $wpdb->prepare( 'SELECT lv.ID, lv.description, lv.start_date, lv.end_date, lv.is_approved, lv.approved_by, lv.admin_id, a.name, a.phone, u.user_login, a.ID as admin_id FROM ' . WLSM_LEAVES . ' as lv
		JOIN ' . WLSM_ADMINS . ' as a ON a.ID = lv.admin_id
		JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
		WHERE lv.school_id = %d AND a.is_active = 1 AND lv.ID = %d', $school_id, $id ) );
		return $staff_leave;
	}

	public static function get_staff_leave_request_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_LEAVE_REQUEST );
	}

	public static function get_gender_text( $gender ) {
		if ( isset( WLSM_Helper::gender_list()[ $gender ] ) ) {
			return WLSM_Helper::gender_list()[ $gender ];
		}
		return '';
	}

	public static function get_blood_group_text( $blood_group ) {
		if ( isset( WLSM_Helper::blood_group_list()[ $blood_group ] ) ) {
			return WLSM_Helper::blood_group_list()[ $blood_group ];
		}
		return '';
	}

	public static function get_inquiry_status_text( $is_active ) {
		if ( $is_active ) {
			return self::get_inquiry_active_text();
		}
		return self::get_inquiry_inactive_text();
	}

	public static function get_inquiry_active_text() {
		return __( 'Active', 'school-management' );
	}

	public static function get_inquiry_inactive_text() {
		return __( 'Inactive', 'school-management' );
	}

	public static function get_gdpr_text( $gdpr_agreed ) {
		if ( $gdpr_agreed ) {
			return __( 'Yes', 'school-management' );
		}
		return __( 'No', 'school-management' );
	}
}
