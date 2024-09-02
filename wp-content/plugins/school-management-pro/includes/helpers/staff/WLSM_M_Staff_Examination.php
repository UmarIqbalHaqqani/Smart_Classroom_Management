<?php
defined( 'ABSPATH' ) || die();

class WLSM_M_Staff_Examination {
	public static function get_exams_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_EXAMS );
	}

	public static function get_academic_report_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_ACADEMIC_REPORT );
	}

	public static function get_exams_group_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_EXAMS_GROUP );
	}

	public static function get_exams_admit_cards_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_EXAM_ADMIT_CARDS );
	}

	public static function get_exams_results_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_EXAM_RESULTS );
	}

	public static function fetch_exam_query( $school_id ) {
		$query = 'SELECT ex.ID, ex.label as exam_title, ex.exam_center, ex.start_date, ex.end_date, ex.is_active, ex.admit_cards_published, ex.time_table_published, ex.results_published, GROUP_CONCAT(DISTINCT CONCAT(\'<strong>-</strong> \', c.label) ORDER BY c.ID SEPARATOR \'<br>\') as class_names FROM ' . WLSM_EXAMS . ' as ex
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL_EXAM . ' as csex ON csex.exam_id = ex.ID
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = csex.class_school_id
		LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE ex.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function fetch_exams_admit( $school_id ) {
		$query = 'SELECT ex.ID, ex.label as exam_title, ex.exam_center, ex.start_date, ex.end_date, ex.is_active, ex.admit_cards_published, ex.time_table_published, ex.results_published FROM ' . WLSM_EXAMS . ' as ex
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL_EXAM . ' as csex ON csex.exam_id = ex.ID
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = csex.class_school_id
		LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE ex.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function fetch_exam_query_by_class_id( $school_id, $class_id ) {
		$query = 'SELECT ex.ID, ex.label as exam_title, ex.exam_center, ex.start_date, ex.end_date, ex.is_active, ex.admit_cards_published, ex.time_table_published, ex.results_published, GROUP_CONCAT(DISTINCT CONCAT(\'<strong>-</strong> \', c.label) ORDER BY c.ID SEPARATOR \'<br>\') as class_names FROM ' . WLSM_EXAMS . ' as ex
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL_EXAM . ' as csex ON csex.exam_id = ex.ID
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = csex.class_school_id
		LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE ex.school_id = ' . absint( $school_id ).' AND c.ID = '.$class_id;
		return $query;
	}

	public static function fetch_exam_query_group( $school_id ) {
		$query = 'SELECT ex.ID, ex.label, ex.is_active FROM ' . WLSM_EXAMS_GROUP . ' as ex
		WHERE ex.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function fetch_exam_query_group_by() {
		$group_by = 'GROUP BY ex.ID';
		return $group_by;
	}

	public static function fetch_academic_report_query_group_by() {
		$group_by = 'GROUP BY ar.ID';
		return $group_by;
	}

	public static function fetch_academic_report_query_count( $school_id ) {
		$query = 'SELECT COUNT(DISTINCT ar.ID) FROM ' . WLSM_ACADEMIC_REPORTS . ' as ar
		WHERE ar.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function get_exam_labels($exam_ids) {
		// Assume $wpdb is the global WordPress database object
		global $wpdb;

		// Sanitize the array of exam IDs
		$sanitized_exam_ids = array_map('absint', $exam_ids);

		// Convert the sanitized exam IDs array into a comma-separated string
		$exam_ids_string = implode(',', $sanitized_exam_ids);

		// Prepare the SQL query
		$query = $wpdb->prepare(
			"SELECT label FROM " . WLSM_EXAMS . " WHERE ID IN ({$exam_ids_string})"
		);

		// Retrieve the exam labels from the database
		$exam_labels = $wpdb->get_col($query);

		// Return the exam labels as a comma-separated string
		return implode(', ', $exam_labels);
	}


	public static function fetch_exam_query_count( $school_id ) {
		$query = 'SELECT COUNT(DISTINCT ex.ID) FROM ' . WLSM_EXAMS . ' as ex
		WHERE ex.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function fetch_exam_group_query_count( $school_id ) {
		$query = 'SELECT COUNT(DISTINCT ex.ID) FROM ' . WLSM_EXAMS_GROUP . ' as ex
		WHERE ex.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function get_exam( $school_id, $id ) {
		global $wpdb;
		$exam = $wpdb->get_row( $wpdb->prepare( 'SELECT ex.ID, ex.label as exam_title FROM ' . WLSM_EXAMS . ' as ex
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = ex.school_id
		WHERE ex.school_id = %d AND ex.ID = %d', $school_id, $id ) );
		return $exam;
	}

	public static function fetch_exam( $school_id, $id ) {
		global $wpdb;
		$exam = $wpdb->get_row( $wpdb->prepare( 'SELECT ex.ID, ex.label as exam_title, ex.show_rank, ex.show_remark, ex.show_eremark, ex.psychomotor_analysis, ex.exam_center, ex.start_date, ex.end_date, ex.grade_criteria, ex.psychomotor, ex.is_active, ex.enable_room_numbers, ex.exam_group, ex.admit_cards_published, ex.time_table_published, ex.results_published, ex.show_in_assessment, ex.enable_total_marks, ex.results_obtained_marks, ex.teacher_signature FROM ' . WLSM_EXAMS . ' as ex
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = ex.school_id
		WHERE ex.school_id = %d AND ex.ID = %d', $school_id, $id ) );
		return $exam;
	}

	public static function get_academic_report($school_id, $id) {
		// Assume $wpdb is the global WordPress database object
		global $wpdb;

		// Prepare the SQL query
		$query = $wpdb->prepare(
			"SELECT * FROM " . WLSM_ACADEMIC_REPORTS . " WHERE school_id = %d AND ID = %d",
			$school_id,
			$id
		);

		// Retrieve the academic report from the database
		$report = $wpdb->get_row($query);

		// Return the academic report
		return $report;
	}


	public static function fetch_exams( $school_id, $exam_id ) {
		global $wpdb;
		$exam = $wpdb->get_row( $wpdb->prepare( 'SELECT ex.ID, ex.label as exam_title, ex.show_rank, ex.show_remark, ex.show_eremark, ex.psychomotor_analysis, ex.exam_center, ex.start_date, ex.end_date, ex.grade_criteria, ex.psychomotor, ex.is_active, ex.enable_room_numbers, ex.exam_group, ex.admit_cards_published, ex.time_table_published, ex.results_published, ex.show_in_assessment FROM ' . WLSM_EXAMS . ' as ex
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = ex.school_id
		WHERE ex.school_id = %d AND ex.ID = %d', $school_id, $exam_id ) );
		return $exam;
	}

	public static function fetch_exams_group( $school_id, $id ) {
		global $wpdb;
		$exam = $wpdb->get_row( $wpdb->prepare( 'SELECT eg.ID, eg.label  FROM ' . WLSM_EXAMS_GROUP . ' as eg
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = eg.school_id
		WHERE eg.school_id = %d AND eg.ID = %d', $school_id, $id ) );
		return $exam;
	}

	public static function fetch_exams_groups( $school_id ) {
		global $wpdb;
		$exam = $wpdb->get_results( $wpdb->prepare( 'SELECT eg.ID, eg.label  FROM ' . WLSM_EXAMS_GROUP . ' as eg
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = eg.school_id
		WHERE eg.school_id = %d', $school_id ) );
		return $exam;
	}

	public static function fetch_exam_classes( $school_id, $exam_id ) {
		global $wpdb;
		$classes = $wpdb->get_col( $wpdb->prepare( 'SELECT DISTINCT c.ID FROM ' . WLSM_CLASS_SCHOOL_EXAM . ' as csex
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = csex.exam_id
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = ex.school_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = csex.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON cs.class_id = c.ID
			WHERE s.ID = %d AND csex.exam_id = %d ORDER BY csex.ID ASC', $school_id, $exam_id ) );
		return $classes;
	}

	public static function fetch_exam_classes_label( $school_id, $exam_id ) {
		global $wpdb;
		$classes = $wpdb->get_results( $wpdb->prepare( 'SELECT DISTINCT c.ID, c.label FROM ' . WLSM_CLASS_SCHOOL_EXAM . ' as csex
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = csex.exam_id
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = ex.school_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = csex.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON cs.class_id = c.ID
			WHERE s.ID = %d AND csex.exam_id = %d ORDER BY csex.ID ASC', $school_id, $exam_id ) );
		return $classes;
	}

	public static function fetch_exam_papers( $school_id, $exam_id ) {
		global $wpdb;
		$papers = $wpdb->get_results( $wpdb->prepare( 'SELECT DISTINCT ep.ID, ep.subject_label, ep.subject_type, ep.paper_code, ep.paper_order, ep.paper_date, ep.start_time, ep.end_time, ep.room_number, ep.maximum_marks, ep.subject_id FROM ' . WLSM_EXAM_PAPERS . ' as ep
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ep.exam_id
			WHERE ex.school_id = %d AND ep.exam_id = %d ORDER BY ep.paper_order ASC', $school_id, $exam_id ) );
		return $papers;
	}

	public static function fetch_exam_papers_student($school_id, $exam_id, $student_id) {
		global $wpdb;
		$papers = $wpdb->get_results($wpdb->prepare('SELECT ep.ID, ep.subject_label, ep.subject_type, ep.paper_code, ep.paper_order, ep.paper_date, ep.start_time, ep.end_time, ep.room_number, ep.maximum_marks, ep.exam_id FROM ' . WLSM_EXAM_PAPERS . ' AS ep
			JOIN ' . WLSM_EXAMS . ' AS ex ON ex.ID = ep.exam_id
			JOIN ' . WLSM_STUDENTS_SUBJECTS . ' AS ss ON ss.subject_id = ep.subject_id AND ss.student_id = %d
			WHERE ex.school_id = %d AND ep.exam_id = %d
			ORDER BY ep.paper_order ASC', $student_id, $school_id, $exam_id));
		return $papers;
	}


	public static function get_class_school_exams_time_table( $school_id, $class_school_id ) {
		global $wpdb;
		$exams = $wpdb->get_results( $wpdb->prepare( 'SELECT DISTINCT ex.ID, ex.label as exam_title, ex.start_date, ex.end_date FROM ' . WLSM_EXAMS . ' as ex
		JOIN ' . WLSM_CLASS_SCHOOL_EXAM . ' as csex ON csex.exam_id = ex.ID
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = ex.school_id
		WHERE ex.school_id = %d AND csex.class_school_id = %d AND ex.time_table_published = 1 AND ex.is_active = 1 ORDER BY ex.start_date DESC, ex.ID DESC', $school_id, $class_school_id ) );
		return $exams;
	}

	public static function get_class_school_exam_time_table( $school_id, $class_school_id, $id ) {
		global $wpdb;
		$exam = $wpdb->get_row( $wpdb->prepare( 'SELECT ex.ID, ex.label as exam_title, ex.exam_center, ex.start_date, ex.end_date, ex.enable_room_numbers FROM ' . WLSM_EXAMS . ' as ex
		JOIN ' . WLSM_CLASS_SCHOOL_EXAM . ' as csex ON csex.exam_id = ex.ID
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = ex.school_id
		WHERE ex.school_id = %d AND csex.class_school_id = %d AND ex.ID = %d AND ex.time_table_published = 1 AND ex.is_active = 1', $school_id, $class_school_id, $id ) );
		return $exam;
	}

	public static function get_school_published_exams_time_table( $school_id ) {
		global $wpdb;
		$exams = $wpdb->get_results( $wpdb->prepare( 'SELECT ex.ID, ex.label as exam_title, ex.start_date, ex.end_date FROM ' . WLSM_EXAMS . ' as ex
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = ex.school_id
		WHERE ex.school_id = %d AND ex.time_table_published = 1 AND ex.is_active = 1', $school_id ) );
		return $exams;
	}

	public static function get_school_published_exam_time_table( $school_id, $exam_id ) {
		global $wpdb;
		$exam = $wpdb->get_row( $wpdb->prepare( 'SELECT ex.ID, ex.label as exam_title, ex.start_date, ex.end_date, ex.enable_room_numbers FROM ' . WLSM_EXAMS . ' as ex
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = ex.school_id
		WHERE ex.school_id = %d AND ex.time_table_published = 1 AND ex.is_active = 1 AND ex.ID = %d', $school_id, $exam_id ) );
		return $exam;
	}

	public static function get_school_published_exams_admit_card( $school_id ) {
		global $wpdb;
		$exams = $wpdb->get_results( $wpdb->prepare( 'SELECT ex.ID, ex.label as exam_title, ex.start_date, ex.end_date FROM ' . WLSM_EXAMS . ' as ex
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = ex.school_id
		WHERE ex.school_id = %d AND ex.is_active = 1 AND ex.admit_cards_published = 1', $school_id ) );
		return $exams;
	}

	public static function get_school_published_exams_result( $school_id ) {
		global $wpdb;
		$exams = $wpdb->get_results( $wpdb->prepare( 'SELECT ex.ID, ex.label as exam_title, ex.start_date, ex.end_date FROM ' . WLSM_EXAMS . ' as ex
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = ex.school_id
		WHERE ex.school_id = %d AND ex.is_active = 1 AND ex.results_published = 1', $school_id ) );
		return $exams;
	}

	public static function get_school_published_exam_admit_card( $school_id, $exam_id ) {
		global $wpdb;
		$exam = $wpdb->get_row( $wpdb->prepare( 'SELECT ex.ID, ex.label as exam_title, ex.show_rank, ex.show_remark, ex.show_eremark, ex.psychomotor_analysis, ex.exam_center, ex.start_date, ex.end_date, ex.grade_criteria, ex.psychomotor, ex.is_active, ex.enable_room_numbers, ex.exam_group, ex.admit_cards_published, ex.time_table_published, ex.results_published, ex.show_in_assessment FROM ' . WLSM_EXAMS . ' as ex
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = ex.school_id
		WHERE ex.school_id = %d AND ex.ID = %d', $school_id, $exam_id ) );
		return $exam;
	}

	public static function exam_admit_cards_count( $school_id, $exam_id ) {
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(DISTINCT ac.ID) FROM ' . WLSM_ADMIT_CARDS . ' as ac
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ac.exam_id
			WHERE ex.school_id = %d AND ac.exam_id = %d', $school_id, $exam_id ) );
	}

	public static function get_exam_admit_cards( $school_id, $exam_id ) {
		global $wpdb;
		$admit_cards = $wpdb->get_results( $wpdb->prepare( 'SELECT ac.ID, ac.exam_id,  ac.roll_number,  ac.student_record_id as student_id, sr.name, sr.phone, sr.enrollment_number, sr.photo_id, sr.email, c.label as class_label, se.label as section_label, ss.label as session_label FROM ' . WLSM_ADMIT_CARDS . ' as ac
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ac.exam_id
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			WHERE ex.school_id = %d AND ac.exam_id = %d GROUP BY ac.student_record_id ORDER BY ac.roll_number, sr.name', $school_id, $exam_id ) );
		return $admit_cards;
	}

	public static function get_exam_admit_cards_for_bulk_print( $school_id, $exam_id, $class_id, $section_id = 0 ) {
		global $wpdb;
		$admit_cards = $wpdb->get_results( $wpdb->prepare( 'SELECT ac.ID, ac.exam_id,  ac.roll_number,  ac.student_record_id as student_id, sr.name, sr.phone, sr.enrollment_number, sr.photo_id, sr.email, c.label as class_label, se.label as section_label, ss.label as session_label FROM ' . WLSM_ADMIT_CARDS . ' as ac
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ac.exam_id
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			WHERE ex.school_id = %d AND ac.exam_id = %d AND cs.class_id = %d GROUP BY ac.student_record_id ORDER BY ac.roll_number, sr.name', $school_id, $exam_id, $class_id ) );

		if ($section_id !== 0) {
			$admit_cards = $wpdb->get_results( $wpdb->prepare( 'SELECT ac.ID, ac.exam_id,  ac.roll_number,  ac.student_record_id as student_id, sr.name, sr.phone, sr.enrollment_number, sr.photo_id, sr.email, c.label as class_label, se.label as section_label, ss.label as session_label FROM ' . WLSM_ADMIT_CARDS . ' as ac
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ac.exam_id
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			WHERE ex.school_id = %d AND ac.exam_id = %d AND cs.class_id = %d AND sr.section_id = %d GROUP BY ac.student_record_id ORDER BY ac.roll_number, sr.name', $school_id, $exam_id, $class_id, $section_id ) );
		} else {

		}
		return $admit_cards;
	}

	public static function fetch_academic_report_query($school_id) {
		global $wpdb;

		$query = $wpdb->prepare(
			"SELECT ar.ID, ar.label AS report_label, c.label AS class_label, ar.exams AS exam_ids, eg.label AS group_label
			FROM " . WLSM_ACADEMIC_REPORTS . " AS ar
			INNER JOIN " . WLSM_CLASSES . " AS c ON ar.class_id = c.ID
			INNER JOIN " . WLSM_EXAMS_GROUP . " AS eg ON ar.exam_group = eg.ID
			WHERE ar.school_id = %d",
			$school_id
		);

		return $query;
	}


	public static function fetch_admit_card( $school_id, $id ) {
		global $wpdb;
		$admit_card = $wpdb->get_row( $wpdb->prepare( 'SELECT ac.ID, ac.exam_id, ac.roll_number, ac.student_record_id as student_id, sr.name, sr.phone, sr.enrollment_number, sr.admission_number, sr.photo_id, sr.email, c.label as class_label, se.label as section_label, ss.label as session_label, sr.father_name FROM ' . WLSM_ADMIT_CARDS . ' as ac
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ac.exam_id
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			WHERE ex.school_id = %d AND ac.ID = %d', $school_id, $id ) );
		return $admit_card;
	}

	public static function get_admit_card( $school_id, $id ) {
		global $wpdb;
		$admit_card = $wpdb->get_row( $wpdb->prepare( 'SELECT ac.ID, ac.exam_id FROM ' . WLSM_ADMIT_CARDS . ' as ac
		JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ac.exam_id
		WHERE ex.school_id = %d AND ac.ID = %d', $school_id, $id ) );
		return $admit_card;
	}

	public static function get_student_admit_cards( $school_id, $student_id ) {
		global $wpdb;
		$admit_cards = $wpdb->get_results( $wpdb->prepare( 'SELECT ac.ID, ac.exam_id, ex.label as exam_title, ex.start_date, ex.end_date FROM ' . WLSM_ADMIT_CARDS . ' as ac
		JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ac.exam_id
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
		JOIN ' . WLSM_CLASS_SCHOOL_EXAM . ' as csex ON csex.exam_id = ex.ID
		WHERE ex.school_id = %d AND sr.ID = %d AND ex.admit_cards_published = 1 AND ex.is_active = 1 GROUP BY ac.ID ORDER BY ex.start_date DESC, ex.ID DESC', $school_id, $student_id ) );
		return $admit_cards;
	}

	public static function fetch_student_admit_card( $school_id, $student_id, $id ) {
		global $wpdb;
		$admit_card = $wpdb->get_row( $wpdb->prepare( 'SELECT ac.ID, ac.exam_id, ac.roll_number, ac.student_record_id as student_id, sr.name, sr.phone, sr.enrollment_number, sr.admission_number, sr.photo_id, sr.email, c.label as class_label, se.label as section_label, ss.label as session_label FROM ' . WLSM_ADMIT_CARDS . ' as ac
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ac.exam_id
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			WHERE ex.school_id = %d AND sr.ID = %d AND ac.ID = %d AND ex.admit_cards_published = 1 AND ex.is_active = 1', $school_id, $student_id, $id ) );
		return $admit_card;
	}

	public static function get_student_exam_results_assessment( $school_id, $student_id ) {
		global $wpdb;
		$exam_results = $wpdb->get_results( $wpdb->prepare( 'SELECT ac.ID, ex.label as exam_title, ex.ID as exam_id, ex.start_date, ex.end_date FROM ' . WLSM_EXAM_RESULTS . ' as er
			JOIN ' . WLSM_EXAM_PAPERS . ' as ep ON ep.ID = er.exam_paper_id
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ep.exam_id
			JOIN ' . WLSM_ADMIT_CARDS . ' as ac ON ac.ID = er.admit_card_id
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			WHERE ex.school_id = %d AND sr.ID = %d AND ex.is_active=1 AND ex.show_in_assessment = 1 GROUP BY ac.ID ORDER BY ex.start_date ASC', $school_id, $student_id ) );
		return $exam_results;
	}

	public static function get_class_school_exams( $school_id, $class_school_id ) {
		global $wpdb;
		$exams = $wpdb->get_results( $wpdb->prepare( 'SELECT DISTINCT ex.ID, ex.label as exam_title, ex.show_rank, ex.show_remark, ex.psychomotor_analysis, ex.psychomotor, ex.start_date, ex.end_date, ex.results_obtained_marks , ex.enable_total_marks, ex.teacher_signature FROM ' . WLSM_EXAMS . ' as ex
		JOIN ' . WLSM_CLASS_SCHOOL_EXAM . ' as csex ON csex.exam_id = ex.ID
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = ex.school_id
		WHERE ex.school_id = %d AND ex.is_active=1 AND csex.class_school_id = %d ORDER BY ex.start_date ASC', $school_id, $class_school_id ) );
		return $exams;
	}

	public static function get_exam_group_exams( $school_id, $class_school_id, $exam_group ) {
		global $wpdb;
		$exams =  $wpdb->get_results( $wpdb->prepare( 'SELECT DISTINCT ex.ID, ex.label as exam_title, ex.start_date, ex.end_date FROM ' . WLSM_EXAMS . ' as ex
		JOIN ' . WLSM_CLASS_SCHOOL_EXAM . ' as csex ON csex.exam_id = ex.ID
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = ex.school_id
		JOIN ' . WLSM_EXAMS_GROUP . ' as eg ON eg.ID = ex.exam_group
		WHERE ex.school_id = %d AND csex.class_school_id = %d AND eg.label = %s ORDER BY ex.start_date ASC', $school_id, $class_school_id, $exam_group ) );
		return $exams;
	}

	public static function get_class_school_exam_groups( $school_id, $class_school_id ) {
		global $wpdb;
		$exam_groups = $wpdb->get_col( $wpdb->prepare( 'SELECT DISTINCT ex.exam_group FROM ' . WLSM_EXAMS . ' as ex
		JOIN ' . WLSM_CLASS_SCHOOL_EXAM . ' as csex ON csex.exam_id = ex.ID
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = ex.school_id
		WHERE ex.school_id = %d AND csex.class_school_id = %d ORDER BY ex.start_date ASC', $school_id, $class_school_id ) );
		return $exam_groups;
	}

	public static function get_class_school_exams_academic_report($school_id, $class_school_id, $academic_report_id) {
		global $wpdb;
		$exams = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT DISTINCT ex.ID, ex.label AS exam_title, ex.show_rank, ex.show_remark, ex.psychomotor_analysis, ex.psychomotor, ex.start_date, ex.end_date, ex.results_obtained_marks, ex.enable_total_marks, ex.teacher_signature
				FROM " . WLSM_EXAMS . " AS ex
				JOIN " . WLSM_CLASS_SCHOOL_EXAM . " AS csex ON csex.exam_id = ex.ID
				JOIN " . WLSM_SCHOOLS . " AS s ON s.ID = ex.school_id
				JOIN " . WLSM_ACADEMIC_REPORTS . " AS ar ON ar.ID = %d
				WHERE ex.school_id = %d AND ex.is_active = 1 AND csex.class_school_id = %d
					AND JSON_CONTAINS(ar.exams, JSON_QUOTE(CAST(ex.ID AS CHAR)), '$')
				ORDER BY ex.start_date ASC",
				$academic_report_id,
				$school_id,
				$class_school_id
			)
		);
		return $exams;
	}


	public static function get_class_school_exam_groups_assessment( $school_id, $class_school_id ) {
		global $wpdb;
		$exam_groups = $wpdb->get_col( $wpdb->prepare( 'SELECT DISTINCT  eg.label FROM ' . WLSM_EXAMS . ' as ex
		JOIN ' . WLSM_CLASS_SCHOOL_EXAM . ' as csex ON csex.exam_id = ex.ID
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = ex.school_id
		JOIN ' . WLSM_EXAMS_GROUP . ' as eg ON eg.ID = ex.exam_group
		WHERE ex.school_id = %d AND csex.class_school_id = %d ORDER BY ex.start_date ASC', $school_id, $class_school_id ) );
		return $exam_groups;
	}

	public static function exam_without_group( $school_id, $class_school_id ) {
		global $wpdb;
		$exam_without_group = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(DISTINCT ex.ID) FROM ' . WLSM_EXAMS . ' as ex
		JOIN ' . WLSM_CLASS_SCHOOL_EXAM . ' as csex ON csex.exam_id = ex.ID
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = ex.school_id
		WHERE ex.school_id = %d AND csex.class_school_id = %d AND (ex.exam_group = "" OR ex.exam_group IS NULL)', $school_id, $class_school_id ) );
		return $exam_without_group;
	}

	public static function get_exam_result_by_subject_code( $school_id, $exam_id, $student_id, $subject_code ) {
		global $wpdb;
		$exam_result = $wpdb->get_row( $wpdb->prepare( 'SELECT ep.maximum_marks, er.obtained_marks, er.remark, er.scale FROM ' . WLSM_EXAM_RESULTS . ' as er
			JOIN ' . WLSM_EXAM_PAPERS . ' as ep ON ep.ID = er.exam_paper_id
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ep.exam_id
			JOIN ' . WLSM_ADMIT_CARDS . ' as ac ON ac.ID = er.admit_card_id
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			WHERE ex.school_id = %d AND ep.exam_id = %d AND sr.ID = %d AND ep.paper_code = %s', $school_id, $exam_id, $student_id, $subject_code ) );
		return $exam_result;
	}

	public static function get_exam_results_total_by_student_id( $school_id, $exam_id, $student_id ) {
		global $wpdb;
		$exam_result = $wpdb->get_row( $wpdb->prepare( 'SELECT COALESCE(SUM(ep.maximum_marks), 0) as total_marks, COALESCE(SUM(er.obtained_marks), 0) as obtained_marks FROM ' . WLSM_EXAM_RESULTS . ' as er
			JOIN ' . WLSM_EXAM_PAPERS . ' as ep ON ep.ID = er.exam_paper_id
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ep.exam_id
			JOIN ' . WLSM_ADMIT_CARDS . ' as ac ON ac.ID = er.admit_card_id
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			WHERE ex.school_id = %d AND ep.exam_id = %d AND sr.ID = %d', $school_id, $exam_id, $student_id ) );
		return $exam_result;
	}

	public static function get_student_published_exam_results( $school_id, $student_id ) {
		global $wpdb;
		$exam_results = $wpdb->get_results( $wpdb->prepare( 'SELECT ac.ID, er.answer_key, ex.label as exam_title, ex.start_date, ex.end_date FROM ' . WLSM_EXAM_RESULTS . ' as er
			JOIN ' . WLSM_EXAM_PAPERS . ' as ep ON ep.ID = er.exam_paper_id
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ep.exam_id
			JOIN ' . WLSM_ADMIT_CARDS . ' as ac ON ac.ID = er.admit_card_id
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			WHERE ex.school_id = %d AND sr.ID = %d AND ex.results_published = 1 GROUP BY ac.ID ORDER BY ac.ID DESC', $school_id, $student_id ) );
		return $exam_results;
	}

	public static function get_student_published_exam_result( $school_id, $student_id, $admit_card_id ) {
		global $wpdb;
		$exam_result = $wpdb->get_row( $wpdb->prepare( 'SELECT DISTINCT ac.ID, ex.ID as exam_id, ex.label as exam_title, ac.roll_number, ac.student_record_id as student_id, sr.name, sr.father_name, sr.phone, sr.enrollment_number, sr.admission_number, sr.photo_id, sr.email, c.label as class_label, se.label as section_label, ss.label as session_label FROM ' . WLSM_EXAM_RESULTS . ' as er
			JOIN ' . WLSM_EXAM_PAPERS . ' as ep ON ep.ID = er.exam_paper_id
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ep.exam_id
			JOIN ' . WLSM_ADMIT_CARDS . ' as ac ON ac.ID = er.admit_card_id
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			WHERE ex.school_id = %d AND sr.ID = %d AND ex.results_published = 1 AND ac.ID = %d', $school_id, $student_id, $admit_card_id ) );
		return $exam_result;
	}

	public static function get_exam_results( $school_id, $exam_id, $order = 'ASC' ) {
		global $wpdb;
		$exam_results = $wpdb->get_results( $wpdb->prepare( 'SELECT ac.ID, ac.ID as admit_card_id, ep.exam_id, ac.roll_number, c.label as class_label, se.label as section_label, sr.name, sr.enrollment_number, sr.admission_number, COALESCE(SUM(ep.maximum_marks), 0) as total_marks, COALESCE(SUM(er.obtained_marks), 0) as obtained_marks FROM ' . WLSM_EXAM_RESULTS . ' as er
			JOIN ' . WLSM_EXAM_PAPERS . ' as ep ON ep.ID = er.exam_paper_id
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ep.exam_id
			JOIN ' . WLSM_ADMIT_CARDS . ' as ac ON ac.ID = er.admit_card_id
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			WHERE ex.school_id = %d AND ep.exam_id = %d GROUP BY ac.ID', $school_id, $exam_id ) );
		return $exam_results;
	}

	public static function get_exam_results_admit_cards_ids( $school_id, $exam_id ) {
		global $wpdb;
		$admit_cards_ids = $wpdb->get_col( $wpdb->prepare( 'SELECT ac.ID FROM ' . WLSM_EXAM_RESULTS . ' as er
			JOIN ' . WLSM_EXAM_PAPERS . ' as ep ON ep.ID = er.exam_paper_id
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ep.exam_id
			JOIN ' . WLSM_ADMIT_CARDS . ' as ac ON ac.ID = er.admit_card_id
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			WHERE ex.school_id = %d AND ep.exam_id = %d GROUP BY ac.ID ORDER BY ac.roll_number, sr.name', $school_id, $exam_id ) );
		return $admit_cards_ids;
	}

	public static function get_exam_results_ids_by_admit_card( $school_id, $admit_card_id ) {
		global $wpdb;
		$exam_results = $wpdb->get_col( $wpdb->prepare( 'SELECT er.ID FROM ' . WLSM_EXAM_RESULTS . ' as er
			JOIN ' . WLSM_ADMIT_CARDS . ' as ac ON ac.ID = er.admit_card_id
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			WHERE cs.school_id = %d AND er.admit_card_id = %d GROUP BY er.ID', $school_id, $admit_card_id ) );
		return $exam_results;
	}

	public static function get_exam_results_by_admit_card( $school_id, $admit_card_id ) {
		global $wpdb;
		$exam_results = $wpdb->get_results( $wpdb->prepare( 'SELECT er.exam_paper_id, er.ID, er.obtained_marks, er.remark, er.teacher_remark, er.school_remark, er.scale, er.answer_key  FROM ' . WLSM_EXAM_RESULTS . ' as er
			JOIN ' . WLSM_ADMIT_CARDS . ' as ac ON ac.ID = er.admit_card_id
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			WHERE cs.school_id = %d AND er.admit_card_id = %d GROUP BY er.exam_paper_id', $school_id, $admit_card_id ), OBJECT_K );
		return $exam_results;
	}

	public static function get_exam_papers_by_admit_card( $school_id, $admit_card_id ) {
		global $wpdb;
		$exam_papers = $wpdb->get_results( $wpdb->prepare( 'SELECT ep.ID, ep.subject_label, ep.subject_type, ep.paper_code, ep.maximum_marks FROM ' . WLSM_EXAM_PAPERS . ' as ep
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ep.exam_id
			JOIN ' . WLSM_ADMIT_CARDS . ' as ac ON ac.exam_id = ex.ID
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_STUDENTS_SUBJECTS . ' AS ss ON ss.subject_id = ep.subject_id AND ss.student_id = sr.ID
			WHERE cs.school_id = %d AND ac.ID = %d GROUP BY ep.ID ORDER BY ep.paper_order ASC', $school_id, $admit_card_id ) );
		return $exam_papers;
	}

	public static function get_exam_papers_by_exam_id( $school_id, $exam_id ) {
		global $wpdb;
		$exam_papers = $wpdb->get_results( $wpdb->prepare( 'SELECT ep.ID, ep.subject_label, ep.subject_type, ep.paper_code, ep.maximum_marks FROM ' . WLSM_EXAM_PAPERS . ' as ep
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ep.exam_id
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = ex.school_id
			WHERE ex.school_id = %d AND ex.ID = %d GROUP BY ep.ID ORDER BY ep.paper_order ASC', $school_id, $exam_id ) );
		return $exam_papers;
	}

	public static function get_admit_card_by_exam_roll_number( $school_id, $exam_id, $exam_roll_number ) {
		global $wpdb;
		$admit_card = $wpdb->get_row( $wpdb->prepare( 'SELECT ac.ID, ac.exam_id, ac.roll_number, ac.student_record_id as student_id, sr.name, sr.phone, sr.enrollment_number, sr.admission_number, sr.photo_id, sr.email, c.label as class_label, se.label as section_label, ss.label as session_label FROM ' . WLSM_ADMIT_CARDS . ' as ac
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ac.exam_id
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			WHERE ex.school_id = %d AND ac.exam_id = %d AND ac.roll_number = %s AND ex.admit_cards_published = 1 AND ex.is_active = 1', $school_id, $exam_id, $exam_roll_number ) );
		return $admit_card;
	}

	public static function get_admit_card_by_exam_student( $school_id, $exam_id, $student_id ) {
		global $wpdb;
		$admit_card = $wpdb->get_row( $wpdb->prepare( 'SELECT ac.ID FROM ' . WLSM_ADMIT_CARDS . ' as ac
			JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ac.exam_id
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			WHERE ex.school_id = %d AND ac.exam_id = %d AND sr.ID = %d', $school_id, $exam_id, $student_id ) );
		return $admit_card;
	}

	public static function calculate_exam_ranks( $school_id, $exam_id, $exam_results = array(), $admit_card_id = '' ) {
		if ( $school_id && $exam_id ) {
			$exam_results = WLSM_M_Staff_Examination::get_exam_results( $school_id, $exam_id, 'DESC' );
		}

		$student_ranks       = array();
		$student_percentages = array();
		foreach ( $exam_results as $row ) {
			$student_percentages[ $row->ID ] = WLSM_Config::sanitize_percentage( $row->total_marks, $row->obtained_marks );
		}

		arsort( $student_percentages );

		$i = 0;
		$same_value = 0;
		foreach ( $student_percentages as $key => $value ) {
			if ($same_value != $value) {
				$same_value = $value;
				$i++;
				$student_ranks[ $key ] = $i;
			}else{
				$student_ranks[ $key ] = $i;
			}

		}
		if ( $admit_card_id ) {
			return isset( $student_ranks[ $admit_card_id ] ) ? $student_ranks[ $admit_card_id ] : '-';
		}

		return $student_ranks;
	}

	public static function get_exam_label_text( $label ) {
		if ( $label ) {
			return stripcslashes( $label );
		}
		return '';
	}

	public static function get_exam_center_text( $center ) {
		if ( $center ) {
			return stripcslashes( $center );
		}
		return '-';
	}

	public static function get_status_text( $is_active ) {
		if ( $is_active ) {
			return self::get_active_text();
		}
		return self::get_inactive_text();
	}

	public static function get_active_text() {
		return __( 'Active', 'school-management' );
	}

	public static function get_inactive_text() {
		return __( 'Inactive', 'school-management' );
	}

	public static function get_publish_status_text( $is_published ) {
		if ( $is_published ) {
			return self::get_published_text();
		}
		return self::get_unpublished_text();
	}

	public static function get_published_text() {
		return __( 'Published', 'school-management' );
	}

	public static function get_unpublished_text() {
		return __( 'Unpublished', 'school-management' );
	}

	public static function get_enabled_text() {
		return __( 'Enabled', 'school-management' );
	}

	public static function get_disabled_text() {
		return __( 'Disabled', 'school-management' );
	}
}
