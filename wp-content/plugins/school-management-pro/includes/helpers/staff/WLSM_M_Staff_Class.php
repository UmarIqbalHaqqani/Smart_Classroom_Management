<?php
defined('ABSPATH') || die();

class WLSM_M_Staff_Class
{
	public static function get_sections_page_url()
	{
		return admin_url('admin.php?page=' . WLSM_MENU_STAFF_CLASSES);
	}

	public static function get_subject_type_page_url()
	{
		return admin_url('admin.php?page=' . WLSM_MENU_STAFF_CLASSES);
	}

	public static function get_exams($school_id, $class_id) {
		global $wpdb;

		$exams = $wpdb->get_results($wpdb->prepare(
			"SELECT ex.ID, ex.label
			FROM " . WLSM_CLASS_SCHOOL_EXAM . " as csex
			JOIN " . WLSM_EXAMS . " as ex ON ex.ID = csex.exam_id
			JOIN " . WLSM_SCHOOLS . " as s ON s.ID = ex.school_id
			JOIN " . WLSM_CLASS_SCHOOL . " as cs ON cs.ID = csex.class_school_id
			WHERE s.ID = %d AND cs.class_id = %d
			ORDER BY csex.ID ASC",
			$school_id,
			$class_id
		));

		return $exams;
	}

	public static function fetch_sbuject_type_query (){

		$query = 'SELECT ss.ID, ss.label FROM ' . WLSM_SUBJECT_TYPES . ' as ss';
		return $query;
	}

	public static function fetch_sbuject_type_query_group_by (){

		$query = 'GROUP BY ss.ID';
		return $query;
	}
	public static function fetch_subject_type_query_count(){
		$query = 'SELECT COUNT(DISTINCT ss.ID) FROM ' . WLSM_SUBJECT_TYPES . ' as ss';
		return $query;
	}

	public static function fetch_classes_query($school_id, $session_id)
	{
		$query = 'SELECT c.ID, c.label, COUNT(DISTINCT se.ID) as sections_count, COUNT(DISTINCT sr.ID) as students_count FROM ' . WLSM_CLASS_SCHOOL . ' as cs
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_SECTIONS . ' as se ON se.class_school_id = cs.ID
		LEFT OUTER JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.section_id = se.ID AND sr.session_id = ' . absint($session_id) . '
		WHERE cs.school_id = ' . absint($school_id);
		return $query;
	}

	public static function fetch_classes_query_group_by()
	{
		$group_by = 'GROUP BY c.ID';
		return $group_by;
	}

	public static function fetch_classes_query_count($school_id)
	{
		$query = 'SELECT COUNT(DISTINCT c.ID) FROM ' . WLSM_CLASS_SCHOOL . ' as cs JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id WHERE cs.school_id =' . absint($school_id);
		return $query;
	}

	public static function get_class($school_id, $class_id) {
		global $wpdb;
		$class = $wpdb->get_row($wpdb->prepare('SELECT cs.ID, cs.default_section_id FROM ' . WLSM_CLASS_SCHOOL . ' as cs JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id WHERE cs.class_id = %d AND cs.school_id = %d', $class_id, $school_id));
		return $class;
	}

	public static function get_class_students_count( $class_id) {
		global $wpdb;
		$class = $wpdb->get_var($wpdb->prepare('SELECT COUNT(DISTINCT sr.ID) as students_count FROM ' . WLSM_STUDENT_RECORDS . ' as sr
		JOIN '. WLSM_SECTIONS .' as s ON s.ID = sr.section_id
		JOIN '. WLSM_CLASS_SCHOOL .' as cs ON cs.ID = s.class_school_id
		WHERE cs.class_id = %d', $class_id));
		return $class;
	}

	public static function get_student( $school_id, $student_id) {
		global $wpdb;
		$student = $wpdb->get_row(
			$wpdb->prepare( 'SELECT sr.ID, sr.session_id, s.ID as school_id, cs.ID as class_school_id, sr.section_id FROM ' . WLSM_STUDENT_RECORDS . ' as sr
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id
			WHERE s.ID= %d AND sr.ID = %d', $school_id, $student_id )
		);
		return $student;
	}

	public static function get_class_with_label($school_id, $label)
	{
		global $wpdb;
		$class = $wpdb->get_row($wpdb->prepare('SELECT cs.ID, cs.class_id, cs.default_section_id FROM ' . WLSM_CLASS_SCHOOL . ' as cs JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id WHERE c.label = %s AND cs.school_id = %d', $label, $school_id));
		return $class;
	}

	public static function get_staff_role_id($school_id, $label)
	{
		global $wpdb;
		$result = $wpdb->get_row($wpdb->prepare('SELECT rs.ID FROM ' . WLSM_ROLES . ' as rs WHERE rs.name = %s AND rs.school_id = %d', $label, $school_id));
		return $result;
	}

	public static function fetch_class($school_id, $class_id)
	{
		global $wpdb;
		$class = $wpdb->get_row($wpdb->prepare('SELECT cs.ID, c.ID as class_id, c.label, cs.default_section_id FROM ' . WLSM_CLASS_SCHOOL . ' as cs JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id WHERE cs.class_id = %d AND cs.school_id = %d', $class_id, $school_id));
		return $class;
	}

	public static function get_class_teachers($school_id, $class_id)
	{
		global $wpdb;
		$teachers = $wpdb->get_results(
			$wpdb->prepare('SELECT a.ID, a.name, a.phone, u.user_login as username, se.label as section_label, sf.role FROM ' . WLSM_ADMINS . ' as a
			JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = a.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
			WHERE sf.school_id = %d AND cs.class_id = %d', $school_id, $class_id)
		);
		return $teachers;
	}

	public static function get_section_teachers($school_id, $section_id, $only_one = false)
	{
		global $wpdb;
		$query = 'SELECT a.ID, a.name, a.phone, u.user_login as username, sf.role FROM ' . WLSM_ADMINS . ' as a
			JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = a.section_id
			LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
			WHERE sf.school_id = %d AND se.ID = %d';

		if ($only_one) {
			$query .= ' LIMIT 1';
			return $wpdb->get_row(
				$wpdb->prepare($query, $school_id, $section_id)
			);
		} else {
			return $wpdb->get_results(
				$wpdb->prepare($query, $school_id, $section_id)
			);
		}
	}

	public static function fetch_sections_query($school_id, $session_id, $class_school_id)
	{
		$query = 'SELECT se.ID, se.label, cs.class_id as class_id, cs.default_section_id, COUNT(sr.ID) as students_count FROM ' . WLSM_SECTIONS . ' as se
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id
		LEFT OUTER JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.section_id = se.ID AND sr.session_id = ' . absint($session_id) . '
		WHERE cs.school_id = ' . absint($school_id) . ' AND se.class_school_id = ' . absint($class_school_id);
		return $query;
	}

	public static function fetch_sections_query_group_by()
	{
		$group_by = 'GROUP BY se.ID';
		return $group_by;
	}

	public static function fetch_sections_query_count($school_id, $class_school_id)
	{
		$query = 'SELECT COUNT(DISTINCT se.ID) FROM ' . WLSM_SECTIONS . ' as se JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id WHERE cs.school_id = ' . absint($school_id) . ' AND se.class_school_id = ' . absint($class_school_id);
		return $query;
	}

	public static function get_section($school_id, $id, $class_school_id) {
		global $wpdb;
		$section = $wpdb->get_row($wpdb->prepare('SELECT se.ID FROM ' . WLSM_SECTIONS . ' as se JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.school_id = %d AND se.ID = %d AND se.class_school_id = %d', $school_id, $id, $class_school_id));
		return $section;
	}
	public static function get_subjects($school_id, $id, $class_school_id) {
		global $wpdb;
		$section = $wpdb->get_row($wpdb->prepare('SELECT ss.ID, ss.label FROM ' . WLSM_SECTIONS . ' as se
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON
		JOIN ' . WLSM_SUBJECTS . ' as ss ON cs.ID = ss.class_school_id

		cs.school_id = %d AND se.ID = %d AND se.class_school_id = %d', $school_id, $id, $class_school_id));
		return $section;
	}
	

	public static function fetch_student_type_query_count($school_id)
	{
		$query = 'SELECT COUNT(DISTINCT me.ID) FROM ' . WLSM_STUDENT_TYPE . ' as me JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = me.school_id WHERE me.school_id = ' . absint($school_id);
		return $query;
	}

	public static function fetch_student_type_query_group_by()
	{
		$group_by = 'GROUP BY me.ID';
		return $group_by;
	}

	public static function fetch_student_type_query($school_id)	{
		$query = 'SELECT me.ID, me.label FROM ' . WLSM_STUDENT_TYPE . ' as me
		WHERE me.school_id = ' . absint($school_id);
		return $query;
	}

	public static function get_student_type($school_id, $id) {
		global $wpdb;
		$section = $wpdb->get_row($wpdb->prepare('SELECT me.ID FROM ' . WLSM_STUDENT_TYPE . ' as me WHERE me.school_id = %d AND me.ID = %d', $school_id, $id));
		return $section;
	}

	public static function get_student_type_page_url()
	{
		return admin_url('admin.php?page=' . WLSM_MENU_STAFF_STUDENT_TYPE);
	}

	public static function fetch_medium_query_count($school_id)
	{
		$query = 'SELECT COUNT(DISTINCT me.ID) FROM ' . WLSM_MEDIUM . ' as me JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = me.school_id WHERE me.school_id = ' . absint($school_id);
		return $query;
	}

	public static function fetch_medium_query_group_by()
	{
		$group_by = 'GROUP BY me.ID';
		return $group_by;
	}

	public static function fetch_medium_query($school_id)	{
		$query = 'SELECT me.ID, me.label FROM ' . WLSM_MEDIUM . ' as me
		WHERE me.school_id = ' . absint($school_id);
		return $query;
	}

	public static function get_medium($school_id, $id) {
		global $wpdb;
		$section = $wpdb->get_row($wpdb->prepare('SELECT me.ID FROM ' . WLSM_MEDIUM . ' as me WHERE me.school_id = %d AND me.ID = %d', $school_id, $id));
		return $section;
	}

	public static function get_subject_type_by_id( $id) {
		global $wpdb;
		$section = $wpdb->get_row($wpdb->prepare('SELECT me.ID FROM ' . WLSM_SUBJECT_TYPES . ' as me WHERE me.ID = %d', $id));
		return $section;
	}

	public static function get_section_with_label($school_id, $label, $class_school_id)
	{
		global $wpdb;
		$section = $wpdb->get_row($wpdb->prepare('SELECT se.ID FROM ' . WLSM_SECTIONS . ' as se JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.school_id = %d AND se.label = %s AND se.class_school_id = %d', $school_id, $label, $class_school_id));
		return $section;
	}

	public static function fetch_section($school_id, $id, $class_school_id)
	{
		global $wpdb;
		$section = $wpdb->get_row($wpdb->prepare('SELECT se.ID, se.label FROM ' . WLSM_SECTIONS . ' as se JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.school_id = %d AND se.ID = %d AND se.class_school_id = %d', $school_id, $id, $class_school_id));
		return $section;
	}

	public static function get_school_section($school_id, $section_id)
	{
		global $wpdb;
		$section = $wpdb->get_row(
			$wpdb->prepare('SELECT se.ID, se.label, c.label as class_label FROM ' . WLSM_SECTIONS . ' as se
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			WHERE cs.school_id = %d AND se.ID = %d', $school_id, $section_id)
		);
		return $section;
	}

	public static function get_section_by_id($section_id)
	{
		global $wpdb;
		$section = $wpdb->get_row(
			$wpdb->prepare('SELECT se.label as section_label, c.label as class_label, c.ID as class_id FROM ' . WLSM_SECTIONS . ' as se
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			WHERE se.ID = %d', $section_id)
		);
		return $section;
	}

	public static function fetch_classes($school_id)
	{
		global $wpdb;
		$classes = $wpdb->get_results($wpdb->prepare('SELECT DISTINCT(c.ID), c.label FROM ' . WLSM_CLASS_SCHOOL . ' as cs JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id WHERE cs.school_id = %d ORDER BY c.ID ASC', $school_id));
		return $classes;
	}

	public static function fetch_classes_ids($school_id)
	{
		global $wpdb;
		$classes_ids = $wpdb->get_col($wpdb->prepare('SELECT DISTINCT(c.ID) FROM ' . WLSM_CLASS_SCHOOL . ' as cs JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id WHERE cs.school_id = %d ORDER BY c.ID ASC', $school_id));
		return $classes_ids;
	}

	public static function fetch_sections($class_school_id)
	{
		global $wpdb;
		$sections = $wpdb->get_results($wpdb->prepare('SELECT se.ID, se.label FROM ' . WLSM_SECTIONS . ' as se WHERE se.class_school_id = %d', $class_school_id));
		return $sections;
	}

	public static function get_attendance_page_url()
	{
		return admin_url('admin.php?page=' . WLSM_MENU_STAFF_ATTENDANCE);
	}

	public static function get_class_students($school_id, $session_id, $class_id, $only_active = true)
	{
		global $wpdb;

		if ($only_active) {
			$where = ' AND sr.is_active = 1';
		} else {
			$where = '';
		}

		$students = $wpdb->get_results($wpdb->prepare('SELECT sr.ID, sr.name, sr.enrollment_number, sr.roll_number, sr.phone, sr.father_name, sr.father_phone, se.label as section_label FROM ' . WLSM_STUDENT_RECORDS . ' as sr
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			WHERE cs.school_id = %d AND ss.ID = %d AND cs.class_id = %d' . $where . ' GROUP BY sr.ID ORDER BY CAST(sr.roll_number AS UNSIGNED), sr.name ASC', $school_id, $session_id, $class_id), OBJECT_K);

		return $students;
	}

	public static function get_student_status($student_id)
	{
		global $wpdb;
		$status = ($wpdb->prepare('SELECT COUNT(*) as count FROM ' . WLSM_ATTENDANCE . ' as wa
		WHERE wa.student_record_id = %d ', $student_id));
		return $status;
	}

	public static function get_section_students($school_id, $session_id, $section_id, $only_active = true)
	{
		global $wpdb;

		if ($only_active) {
			$where = ' AND sr.is_active = 1';
		} else {
			$where = '';
		}

		$students = $wpdb->get_results($wpdb->prepare('SELECT sr.ID, sr.name, sr.enrollment_number, sr.roll_number, sr.phone, sr.father_name, sr.father_phone, se.label as section_label FROM ' . WLSM_STUDENT_RECORDS . ' as sr
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			WHERE cs.school_id = %d AND ss.ID = %d AND se.ID = %d' . $where . ' GROUP BY sr.ID ORDER BY sr.roll_number ASC, sr.name ASC', $school_id, $session_id, $section_id), OBJECT_K);
		return $students;
	}

	public static function get_section_student($school_id, $session_id, $section_id, $student_id)
	{
		global $wpdb;

		if ($only_active) {
			$where = ' AND sr.is_active = 1';
		} else {
			$where = '';
		}

		$students = $wpdb->get_results($wpdb->prepare('SELECT sr.ID, sr.name, sr.enrollment_number, sr.roll_number, sr.phone, sr.father_name, sr.father_phone, se.label as section_label FROM ' . WLSM_STUDENT_RECORDS . ' as sr
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			WHERE cs.school_id = %d AND ss.ID = %d AND se.ID = %d AND sr.ID = %d' . $where . ' GROUP BY sr.ID ORDER BY sr.roll_number ASC, sr.name ASC', $school_id, $session_id, $section_id, $student_id), OBJECT_K);
		return $students;
	}


	public static function get_notices_page_url()
	{
		return admin_url('admin.php?page=' . WLSM_MENU_STAFF_NOTICES);
	}

	public static function fetch_notice_query($school_id)
	{
		$query = 'SELECT n.ID, n.title, n.attachment, n.url, n.link_to, n.is_active, n.created_at, u.user_login as username FROM ' . WLSM_NOTICES . ' as n LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = n.added_by WHERE n.school_id = ' . absint($school_id);
		return $query;
	}

	public static function fetch_notice_query_group_by()
	{
		$group_by = 'GROUP BY n.ID';
		return $group_by;
	}

	public static function fetch_notice_query_count($school_id)
	{
		$query = 'SELECT COUNT(DISTINCT n.ID) FROM ' . WLSM_NOTICES . ' as n LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = n.added_by WHERE n.school_id = ' . absint($school_id);
		return $query;
	}

	public static function get_notice($school_id, $id)
	{
		global $wpdb;
		$notice = $wpdb->get_row($wpdb->prepare('SELECT n.ID, n.attachment FROM ' . WLSM_NOTICES . ' as n WHERE n.school_id = %d AND n.ID = %d', $school_id, $id));
		return $notice;
	}

	public static function fetch_notice($school_id, $id)
	{
		global $wpdb;
		$notice = $wpdb->get_row($wpdb->prepare('SELECT n.ID, n.title, n.description, n.attachment, n.url, n.link_to, n.is_active FROM ' . WLSM_NOTICES . ' as n
		WHERE n.school_id = %d AND n.ID = %d', $school_id, $id));
		return $notice;
	}

	public static function fetch_notice_classes($school_id, $notice_id)
	{
		global $wpdb;
		$classes = $wpdb->get_col($wpdb->prepare('SELECT DISTINCT c.ID FROM ' . WLSM_CLASS_SCHOOL_NOTICE . ' as csn
			JOIN ' . WLSM_NOTICES . ' as n ON n.ID = csn.notice_id
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = n.school_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = csn.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON cs.class_id = c.ID
			WHERE s.ID = %d AND csn.notice_id = %d ORDER BY csn.ID ASC', $school_id, $notice_id));
		return $classes;
	}

	public static function get_school_notices($school_id, $limit = '', $class_school_id = '')
	{
		global $wpdb;
		$sql = 'SELECT n.ID, n.title, n.attachment, n.url, n.link_to, n.is_active, n.created_at, COUNT(DISTINCT csn.ID) as classes_count, COUNT(DISTINCT csn2.ID) as other_classes_count FROM ' . WLSM_NOTICES . ' as n LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL_NOTICE . ' as csn ON csn.notice_id = n.ID AND (csn.student_school_id = %d) LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL_NOTICE . ' as csn2 ON csn2.notice_id = n.ID AND (csn2.student_school_id != %d) WHERE n.school_id = %d AND n.is_active = 1 GROUP BY n.ID HAVING (classes_count = 0 AND other_classes_count = 0) OR classes_count = 1 ORDER BY n.ID DESC';
		if ($limit) {
			$sql .= (' LIMIT ' . absint($limit));
		}
		$notices = $wpdb->get_results($wpdb->prepare($sql, $class_school_id, $class_school_id, $school_id));
		return $notices;
	}

	public static function get_events_page_url()
	{
		return admin_url('admin.php?page=' . WLSM_MENU_STAFF_EVENTS);
	}

	public static function fetch_event_query($school_id, $session_id)
	{
		$query = 'SELECT ev.ID, ev.title, ev.event_date, ev.is_active, COUNT(sr.ID) as students_count FROM ' . WLSM_EVENTS . ' as ev
		LEFT OUTER JOIN ' . WLSM_EVENT_RESPONSES . ' as evr ON evr.event_id = ev.ID
		LEFT OUTER JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON evr.student_record_id = sr.ID AND sr.session_id = ' . absint($session_id) . '
		WHERE ev.school_id = ' . absint($school_id);
		return $query;
	}

	public static function fetch_ratting_query() {
		$query = 'SELECT r.ID, r.message, r.ratting, r.live_class_id, COUNT(sr.ID) as students_count, c.label as class_label, c.ID as class_id, s.label as subject_label, a.`name` as teacher FROM ' . WLSM_RATTING . ' as r
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = r.student_id
		JOIN ' . WLSM_MEETINGS . ' as m ON m.ID = r.live_class_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = m.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		JOIN ' . WLSM_SUBJECTS . ' as s ON s.ID = m.subject_id
		JOIN ' . WLSM_ADMINS . ' as a ON a.ID = m.admin_id
		';
		return $query;
	}

	public static function fetch_ratting_count_query($live_class_id) {
		global $wpdb;
		$query = $wpdb->get_results("SELECT ratting, COUNT(*) as count FROM " . WLSM_RATTING . " as r WHERE live_class_id = $live_class_id GROUP BY ratting");
		return $query;
	}

	public static function fetch_ratting_query_count() {
		$query = 'SELECT COUNT(DISTINCT m.ID) FROM ' . WLSM_RATTING . ' as r
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = r.student_id
		JOIN ' . WLSM_MEETINGS . ' as m ON m.ID = r.live_class_id
		';
		return $query;
	}

	public static function fetch_ratting_query_group_by() {
		$group_by = 'GROUP BY m.ID';
		return $group_by;
	}

	public static function fetch_event_query_group_by() {
		$group_by = 'GROUP BY ev.ID';
		return $group_by;
	}

	public static function fetch_event_query_count($school_id, $session_id)
	{
		$query = 'SELECT COUNT(DISTINCT ev.ID) FROM ' . WLSM_EVENTS . ' as ev
		LEFT OUTER JOIN ' . WLSM_EVENT_RESPONSES . ' as evr ON evr.event_id = ev.ID
		LEFT OUTER JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON evr.student_record_id = sr.ID AND sr.session_id = ' . absint($session_id) . '
		WHERE ev.school_id = ' . absint($school_id);
		return $query;
	}

	public static function get_event($school_id, $id)
	{
		global $wpdb;
		$event = $wpdb->get_row($wpdb->prepare('SELECT ev.ID, ev.image_id FROM ' . WLSM_EVENTS . ' as ev WHERE ev.school_id = %d AND ev.ID = %d', $school_id, $id));
		return $event;
	}

	public static function fetch_event($school_id, $id)
	{
		global $wpdb;
		$event = $wpdb->get_row($wpdb->prepare('SELECT ev.ID, ev.title, ev.event_date, ev.description, ev.image_id, ev.is_active FROM ' . WLSM_EVENTS . ' as ev WHERE ev.school_id = %d AND ev.ID = %d', $school_id, $id));
		return $event;
	}

	public static function fetch_active_event($school_id, $event_id, $student_id)
	{
		global $wpdb;
		$event = $wpdb->get_row(
			$wpdb->prepare('SELECT ev.ID, ev.title, ev.event_date, ev.description, ev.image_id, COUNT(sr.ID) as student_joined, evr.ID as event_response_id FROM ' . WLSM_EVENTS . ' as ev
			LEFT OUTER JOIN ' . WLSM_EVENT_RESPONSES . ' as evr ON evr.event_id = ev.ID
			LEFT OUTER JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON evr.student_record_id = sr.ID AND sr.ID = %d
			WHERE ev.school_id = %d AND ev.is_active = 1 AND ev.ID = %d GROUP BY ev.ID', $student_id, $school_id, $event_id)
		);
		return $event;
	}

	public static function fetch_event_participants_query($school_id, $session_id, $event_id)
	{
		$query = 'SELECT evr.ID, sr.ID as student_id, sr.enrollment_number, sr.roll_number, sr.name as student_name, sr.phone, c.label as class_label, se.label as section_label FROM ' . WLSM_EVENT_RESPONSES . ' as evr
		JOIN ' . WLSM_EVENTS . ' as ev ON ev.ID = evr.event_id
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = evr.student_record_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = ' . absint($school_id) . ' AND sr.session_id = ' . absint($session_id) . ' AND ev.ID = ' . absint($event_id);
		return $query;
	}

	public static function fetch_event_participants_query_group_by()
	{
		$group_by = 'GROUP BY evr.ID';
		return $group_by;
	}

	public static function fetch_event_participants_query_count($school_id, $session_id, $event_id)
	{
		$query = 'SELECT COUNT(DISTINCT evr.ID) FROM ' . WLSM_EVENT_RESPONSES . ' as evr
		JOIN ' . WLSM_EVENTS . ' as ev ON ev.ID = evr.event_id
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = evr.student_record_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = ' . absint($school_id) . ' AND sr.session_id = ' . absint($session_id) . ' AND ev.ID = ' . absint($event_id);
		return $query;
	}

	public static function get_event_participants($school_id, $session_id, $event_id)
	{
		global $wpdb;
		$event_participants = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT evr.ID, sr.ID as student_id, sr.enrollment_number, sr.roll_number, sr.name as student_name, sr.phone, c.label as class_label, se.label as section_label FROM ' . WLSM_EVENT_RESPONSES . ' as evr
				JOIN ' . WLSM_EVENTS . ' as ev ON ev.ID = evr.event_id
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = evr.student_record_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				WHERE cs.school_id = %d AND sr.session_id = %d AND ev.ID = %d GROUP BY evr.ID',
				$school_id,
				$session_id,
				$event_id
			)
		);
		return $event_participants;
	}

	public static function get_event_participant($school_id, $session_id, $event_participant_id)
	{
		global $wpdb;
		$event_participant = $wpdb->get_row(
			$wpdb->prepare('SELECT evr.ID FROM ' . WLSM_EVENT_RESPONSES . ' as evr
				JOIN ' . WLSM_EVENTS . ' as ev ON ev.ID = evr.event_id
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = evr.student_record_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				WHERE cs.school_id = %d AND sr.session_id = %d AND evr.ID = %d', $school_id, $session_id, $event_participant_id)
		);
		return $event_participant;
	}

	public static function get_subjects_page_url()
	{
		return admin_url('admin.php?page=' . WLSM_MENU_STAFF_SUBJECTS);
	}

	public static function fetch_subject_type(){
		global $wpdb;
		$subject_types = $wpdb->get_results('SELECT * FROM ' . WLSM_SUBJECT_TYPES . ' ORDER BY label ASC');
		$types_array = array();
		foreach ($subject_types as $type) {
			$types_array[] = $type->label;
		}
		return $types_array;
	}

	public static function fetch_subject_query($school_id)
	{
		$query = 'SELECT sj.ID, sj.label as subject_name, sj.code, sj.type, c.label as class_label, COUNT(DISTINCT asj.ID) as admins_count FROM ' . WLSM_SUBJECTS . ' as sj
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_ADMIN_SUBJECT . ' as asj ON asj.subject_id = sj.ID
		WHERE cs.school_id = ' . absint($school_id);
		return $query;
	}

	public static function fetch_subject_query_by_class_id($school_id, $class_id) {
		global $wpdb;
		$query = 'SELECT sj.ID, sj.label as subject_name FROM ' . WLSM_SUBJECTS . ' as sj
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_ADMIN_SUBJECT . ' as asj ON asj.subject_id = sj.ID
		WHERE cs.school_id = ' . absint($school_id) .' AND cs.class_id =  '. $class_id;
		$subjects = $wpdb->get_results($query);
		return $subjects;
	}

	public static function fetch_subject_query_group_by()
	{
		$group_by = 'GROUP BY sj.ID';
		return $group_by;
	}

	public static function fetch_subject_query_count($school_id)
	{
		$query = 'SELECT COUNT(DISTINCT sj.ID) FROM ' . WLSM_SUBJECTS . ' as sj
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = ' . absint($school_id);
		return $query;
	}

	public static function get_subject($school_id, $id)
	{
		global $wpdb;
		$subject = $wpdb->get_row($wpdb->prepare('SELECT sj.ID FROM ' . WLSM_SUBJECTS . ' as sj
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id
			WHERE cs.school_id = %d AND sj.ID = %d', $school_id, $id));
		return $subject;
	}

	public static function fetch_subject($school_id, $id)
	{
		global $wpdb;
		$subject = $wpdb->get_row($wpdb->prepare('SELECT sj.ID, sj.label as subject_name, sj.code, sj.type, cs.class_id, c.label as class_label FROM ' . WLSM_SUBJECTS . ' as sj
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			WHERE cs.school_id = %d AND sj.ID = %d', $school_id, $id));
		return $subject;
	}

	public static function fetch_subject_admins_query($school_id, $subject_id)
	{
		$query = 'SELECT a.ID, a.name, a.phone, a.is_active, u.user_login as username FROM ' . WLSM_ADMIN_SUBJECT . ' as asj
		JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = asj.subject_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id
		JOIN ' . WLSM_ADMINS . ' as a ON a.ID = asj.admin_id
		JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
		WHERE sf.school_id = ' . absint($school_id) . ' AND sj.ID = ' . absint($subject_id);
		return $query;
	}

	public static function fetch_subject_admins_query_count($school_id, $subject_id)
	{
		$query = 'SELECT COUNT(DISTINCT a.ID) FROM ' . WLSM_ADMIN_SUBJECT . ' as asj
		JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = asj.subject_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id
		JOIN ' . WLSM_ADMINS . ' as a ON a.ID = asj.admin_id
		JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
		WHERE sf.school_id = ' . absint($school_id) . ' AND sj.ID = ' . absint($subject_id);
		return $query;
	}

	public static function fetch_subject_admins($school_id, $subject_id)
	{
		global $wpdb;
		$admins = $wpdb->get_results($wpdb->prepare('SELECT a.ID, a.name, a.phone, a.is_active, u.user_login as username FROM ' . WLSM_ADMIN_SUBJECT . ' as asj
		JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = asj.subject_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id
		JOIN ' . WLSM_ADMINS . ' as a ON a.ID = asj.admin_id
		JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
		WHERE sf.school_id = %d AND sj.ID = %d', $school_id, $subject_id));
		return $admins;
	}

	public static function get_subject_admins($school_id, $subject_id)
	{
		global $wpdb;
		$admins = $wpdb->get_results($wpdb->prepare('SELECT a.ID, a.name as label, a.phone FROM ' . WLSM_ADMINS . ' as a
			JOIN ' . WLSM_ADMIN_SUBJECT . ' asj ON asj.admin_id = a.ID
			JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = asj.subject_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id
			JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
			WHERE sf.school_id = %d AND a.is_active = 1 AND sj.ID = %d GROUP BY a.ID', $school_id, $subject_id));
		return $admins;
	}

	public static function get_subject_students($student_id) {
		global $wpdb;
			$admins = $wpdb->get_results($wpdb->prepare('SELECT ep.ID, ep.subject_label, ep.subject_type, ep.paper_code, ep.maximum_marks FROM ' . WLSM_ADMIT_CARDS . ' as ac
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
			JOIN ' . WLSM_STUDENTS_SUBJECTS . ' as ssj ON ssj.student_id = sr.ID
			JOIN ' . WLSM_SUBJECTS . ' as s ON s.ID = ssj.subject_id
			JOIN ' . WLSM_EXAM_PAPERS . ' as ep ON ep.paper_code = s.code
			WHERE sr.is_active = 1 AND ac.ID = %d',$student_id));
		return $admins;
	}

	public static function get_admin_subject($school_id, $subject_id, $admin_id)
	{
		global $wpdb;
		$admin = $wpdb->get_row($wpdb->prepare('SELECT asj.ID FROM ' . WLSM_ADMIN_SUBJECT . ' as asj
		JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = asj.subject_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id
		JOIN ' . WLSM_ADMINS . ' as a ON a.ID = asj.admin_id
		JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
		WHERE sf.school_id = %d AND sj.ID = %d AND a.ID = %d', $school_id, $subject_id, $admin_id));
		return $admin;
	}

	public static function get_class_subjects($school_id, $class_id)
	{
		global $wpdb;
		$subjects = $wpdb->get_results($wpdb->prepare('SELECT sj.ID, sj.label, sj.code, sj.type FROM ' . WLSM_SUBJECTS . ' as sj
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id
		WHERE cs.school_id = %d AND cs.class_id = %d', $school_id, $class_id));
		return $subjects;
	}

	public static function get_class_subjects_students($school_id, $class_id, $student_id)
	{
		global $wpdb;
		$subjects = $wpdb->get_results($wpdb->prepare('SELECT sj.ID, sj.label, sj.code, sj.type FROM ' . WLSM_SUBJECTS . ' as sj
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id
			JOIN ' . WLSM_STUDENTS_SUBJECTS . ' as ss ON ss.subject_id = sj.ID
			WHERE cs.school_id = %d AND cs.class_id = %d AND ss.student_id = %d', $school_id, $class_id, $student_id));
		return $subjects;
	}

	public static function get_chapters( $subject_id) {
		global $wpdb;
		$subjects = $wpdb->get_results($wpdb->prepare('SELECT cp.ID, cp.title, cp.subject_id FROM ' . WLSM_CHAPTER . ' as cp
		JOIN ' . WLSM_SUBJECTS . ' as sb ON sb.ID = cp.subject_id
		WHERE cp.subject_id = %d', $subject_id));
		return $subjects;
	}

	public static function get_lessons( $class_id, $subject_id) {
		global $wpdb;
		$subjects = $wpdb->get_results($wpdb->prepare('SELECT l.ID, l.title, l.attachment, l.url, l.link_to, l.created_at, c.label as class, s.label as `subject`, cp.title as chapter FROM ' . WLSM_LECTURE . ' as l
		JOIN ' . WLSM_CLASSES . ' as c ON l.class_id = c.ID
		LEFT OUTER JOIN ' . WLSM_SUBJECTS . ' as s ON s.ID = l.subject_id
		LEFT OUTER JOIN ' . WLSM_CHAPTER . ' as cp ON cp.ID = l.chapter_id
		WHERE l.class_id = %d AND l.subject_id=%d', $class_id, $subject_id));
		return $subjects;
	}

	public static function get_lessons_wit_chapter_id( $class_id, $subject_id, $chapter_id) {
		global $wpdb;
		$subjects = $wpdb->get_results($wpdb->prepare('SELECT l.ID, l.title, l.attachment, l.url, l.link_to, l.created_at, c.label as class, s.label as `subject`, cp.title as chapter FROM ' . WLSM_LECTURE . ' as l
		JOIN ' . WLSM_CLASSES . ' as c ON l.class_id = c.ID
		LEFT OUTER JOIN ' . WLSM_SUBJECTS . ' as s ON s.ID = l.subject_id
		LEFT OUTER JOIN ' . WLSM_CHAPTER . ' as cp ON cp.ID = l.chapter_id
		WHERE l.subject_id = %d AND l.class_id = %d AND l.chapter_id=%d', $subject_id, $class_id, $chapter_id));
		return $subjects;
	}

	public static function get_class_subject($school_id, $class_id, $subject_id)
	{
		global $wpdb;
		$subject = $wpdb->get_results($wpdb->prepare('SELECT sj.ID, sj.label FROM ' . WLSM_SUBJECTS . ' as sj
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id
		WHERE cs.school_id = %d AND cs.class_id = %d AND sj.ID = %d', $school_id, $class_id, $subject_id));
		return $subject;
	}

	public static function get_keyword_active_admins($school_id, $keyword)
	{
		global $wpdb;
		$admins = $wpdb->get_results($wpdb->prepare('SELECT a.ID, a.name as label, a.phone, u.user_login as username FROM ' . WLSM_ADMINS . ' as a
			JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
			LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
			WHERE sf.school_id = %d AND a.is_active = 1 AND a.name LIKE "%%%s%%" GROUP BY a.ID', $school_id, $wpdb->esc_like($keyword)));
		return $admins;
	}

	public static function get_active_admins_ids_in_school($school_id, $admin_ids)
	{
		global $wpdb;

		$values        = array($school_id);
		$place_holders = array();

		foreach ($admin_ids as $admin_id) {
			array_push($values, $admin_id);
			array_push($place_holders, '%d');
		}

		$admin_ids = $wpdb->get_col($wpdb->prepare('SELECT a.ID FROM ' . WLSM_ADMINS . ' as a
			JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
			LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
			WHERE sf.school_id = %d AND a.is_active = 1 AND a.ID IN(' . implode(', ', $place_holders) . ')', $values));

		return $admin_ids;
	}

	public static function get_active_staff($school_id, $role = 'employee')
	{
		global $wpdb;
		$admins = $wpdb->get_results($wpdb->prepare('SELECT a.ID, a.name, a.phone, a.designation, sf.role, r.name as role_name, u.user_login as username FROM ' . WLSM_ADMINS . ' as a
			JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
			LEFT OUTER JOIN ' . WLSM_ROLES . ' as r ON r.ID = a.role_id
			LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
			WHERE sf.school_id = %d AND sf.role = %s AND a.is_active = 1 GROUP BY a.ID ORDER BY a.name ASC', $school_id, $role), OBJECT_K);
		return $admins;
	}

	public static function get_study_materials_page_url()
	{
		return admin_url('admin.php?page=' . WLSM_MENU_STAFF_STUDY_MATERIALS);
	}

	public static function fetch_study_material_query($school_id) {
		$query = 'SELECT sm.ID, sm.label as title, sm.description, c.label as class_label, ss.label as subject_label, sm.attachments, sm.created_at, u.user_login as username FROM ' . WLSM_STUDY_MATERIALS . ' as sm
		JOIN ' . WLSM_CLASS_SCHOOL_STUDY_MATERIAL . ' as ssm ON ssm.study_material_id = sm.ID
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = sm.school_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = ssm.class_school_id
		LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_SUBJECTS . ' as ss ON s.ID = ssm.study_material_subject_id

		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sm.added_by
		WHERE s.ID = ' . absint($school_id);
		return $query;
	}

	public static function fetch_study_material_query_by_class_id($school_id, $class_id) {
		$query = 'SELECT sm.ID, sm.label as title, sm.description, c.label as class_label, ss.label as subject_label, sm.attachments, sm.created_at, u.user_login as username FROM ' . WLSM_STUDY_MATERIALS . ' as sm
		JOIN ' . WLSM_CLASS_SCHOOL_STUDY_MATERIAL . ' as ssm ON ssm.study_material_id = sm.ID
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = sm.school_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = ssm.class_school_id
		LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_SUBJECTS . ' as ss ON s.ID = ssm.study_material_subject_id

		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sm.added_by
		WHERE s.ID = ' . absint($school_id).' AND c.ID = '.absint($class_id);
		return $query;
	}

	public static function fetch_study_material_query_group_by()
	{
		$group_by = 'GROUP BY sm.ID';
		return $group_by;
	}

	public static function fetch_study_material_query_count($school_id)
	{
		$query = 'SELECT COUNT(DISTINCT sm.ID) FROM ' . WLSM_STUDY_MATERIALS . ' as sm
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = sm.school_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sm.added_by
		WHERE s.ID = ' . absint($school_id);
		return $query;
	}

	public static function get_study_material($school_id, $id)
	{
		global $wpdb;
		$study_material = $wpdb->get_row($wpdb->prepare('SELECT sm.ID, sm.attachments FROM ' . WLSM_STUDY_MATERIALS . ' as sm
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = sm.school_id
			WHERE s.ID = %d AND sm.ID = %d', $school_id, $id));
		return $study_material;
	}

	public static function fetch_study_material($school_id, $id)
	{
		global $wpdb;
		$study_material = $wpdb->get_row($wpdb->prepare('SELECT sm.ID, sm.label as title, c.label as class_label, ss.ID as subject_id, ssm.study_material_section_id, wl.label as section_label, c.ID as class_id,  ss.label as subject_label, sm.description, sm.downloadable, sm.url, sm.attachments FROM ' . WLSM_STUDY_MATERIALS . ' as sm
			JOIN ' . WLSM_CLASS_SCHOOL_STUDY_MATERIAL . ' as ssm ON ssm.study_material_id = sm.ID
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = sm.school_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = ssm.class_school_id
			LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			LEFT OUTER JOIN ' . WLSM_SUBJECTS . ' as ss ON s.ID = ssm.study_material_subject_id
			LEFT OUTER JOIN ' . WLSM_SECTIONS . ' as wl ON wl.ID = ssm.study_material_section_id
			WHERE s.ID = %d AND sm.ID = %d', $school_id, $id));
		return $study_material;
	}

	public static function fetch_study_material_classes($school_id, $study_material_id)
	{
		global $wpdb;
		$classes = $wpdb->get_col($wpdb->prepare('SELECT DISTINCT c.ID FROM ' . WLSM_CLASS_SCHOOL_STUDY_MATERIAL . ' as cssm
			JOIN ' . WLSM_STUDY_MATERIALS . ' as sm ON sm.ID = cssm.study_material_id
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = sm.school_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = cssm.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON cs.class_id = c.ID
			WHERE s.ID = %d AND cssm.study_material_id = %d ORDER BY cssm.ID ASC', $school_id, $study_material_id));
		return $classes;
	}

	public static function get_meetings_page_url()
	{
		return admin_url('admin.php?page=' . WLSM_MENU_STAFF_MEETINGS);
	}

	public static function fetch_meeting_query($school_id)
	{
		$query = 'SELECT mt.ID, mt.host_id, mt.meeting_id, mt.moderator_code, mt.password, mt.recordable, mt.topic, mt.duration, mt.start_at, mt.type, mt.start_url, mt.join_url, c.id as class_id, sj.label as subject_name, sj.code as subject_code, c.label as class_label, a.name as name FROM ' . WLSM_MEETINGS . ' as mt
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = mt.school_id
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = mt.class_school_id
		LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = mt.subject_id
		LEFT OUTER JOIN ' . WLSM_ADMINS . ' as a ON a.ID = mt.admin_id
		LEFT OUTER JOIN ' . WLSM_STAFF . ' as sf ON a.staff_id = sf.ID
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
		WHERE mt.school_id = ' . absint($school_id);
		return $query;
	}

	public static function fetch_meeting_query_group_by()
	{
		$group_by = 'GROUP BY mt.ID';
		return $group_by;
	}

	public static function fetch_meeting_query_count($school_id)
	{
		$query = 'SELECT COUNT(DISTINCT mt.ID) FROM ' . WLSM_MEETINGS . ' as mt
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = mt.school_id
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = mt.class_school_id
		LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = mt.subject_id
		LEFT OUTER JOIN ' . WLSM_ADMINS . ' as a ON a.ID = mt.admin_id
		LEFT OUTER JOIN ' . WLSM_STAFF . ' as sf ON a.staff_id = sf.ID
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
		WHERE mt.school_id = ' . absint($school_id);
		return $query;
	}

	public static function get_meeting($school_id, $id)
	{
		global $wpdb;
		$meeting = $wpdb->get_row($wpdb->prepare('SELECT mt.ID, mt.meeting_id FROM ' . WLSM_MEETINGS . ' as mt
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = mt.school_id
			LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = mt.class_school_id
			LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			LEFT OUTER JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = mt.subject_id
			LEFT OUTER JOIN ' . WLSM_ADMINS . ' as a ON a.ID = mt.admin_id
			LEFT OUTER JOIN ' . WLSM_STAFF . ' as sf ON a.staff_id = sf.ID
			LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
			WHERE mt.school_id = %d AND mt.ID = %d', $school_id, $id));
		return $meeting;
	}

	public static function fetch_meeting($school_id, $id)
	{
		global $wpdb;
		$meeting = $wpdb->get_row($wpdb->prepare('SELECT mt.ID, mt.host, mt.meeting_id, mt.topic, mt.agenda, mt.type, mt.duration, mt.start_at, mt.start_url, mt.join_url, mt.recurrence_type, mt.recordable, mt.repeat_interval, mt.weekly_days, mt.monthly_day, mt.end_times, mt.end_at, mt.registration_type, mt.approval_type, mt.password, mt.join_before_host, mt.host_video, mt.class_type, mt.participant_video, mt.mute_upon_entry, sj.ID as subject_id, wl.ID as section_id, wl.label as section_label, sj.label as subject_name, sj.code, c.ID as class_id, c.label as class_label, a.ID as admin_id, a.name as name FROM ' . WLSM_MEETINGS . ' as mt
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = mt.school_id
			LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = mt.class_school_id
			LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			LEFT OUTER JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = mt.subject_id
			LEFT OUTER JOIN ' . WLSM_SECTIONS . ' as wl ON wl.ID = mt.section_id
			LEFT OUTER JOIN ' . WLSM_ADMINS . ' as a ON a.ID = mt.admin_id
			LEFT OUTER JOIN ' . WLSM_STAFF . ' as sf ON a.staff_id = sf.ID
			LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
			WHERE mt.school_id = %d AND mt.ID = %d', $school_id, $id));
		return $meeting;
	}

	public static function fetch_staff_meeting_query($school_id, $admin_id = '')
	{
		$query = 'SELECT mt.ID, mt.host_id, mt.meeting_id, mt.topic, mt.duration, mt.recordable, mt.password, mt.moderator_code, mt.start_at, mt.start_url, mt.type, mt.join_url, sj.label as subject_name, sj.code as subject_code, c.label as class_label FROM ' . WLSM_MEETINGS . ' as mt
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = mt.school_id
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = mt.class_school_id
		LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = mt.subject_id
		LEFT OUTER JOIN ' . WLSM_ADMINS . ' as a ON a.ID = mt.admin_id
		LEFT OUTER JOIN ' . WLSM_STAFF . ' as sf ON a.staff_id = sf.ID
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
		WHERE mt.school_id = ' . absint($school_id);

		if ($admin_id) {
			$query .= ' AND a.ID = ' . absint($admin_id);
		}

		return $query;
	}

	public static function fetch_staff_meeting_query_group_by()
	{
		$group_by = 'GROUP BY mt.ID';
		return $group_by;
	}

	public static function fetch_staff_meeting_query_count($school_id, $admin_id = '')
	{
		$query = 'SELECT COUNT(DISTINCT mt.ID) FROM ' . WLSM_MEETINGS . ' as mt
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = mt.school_id
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = mt.class_school_id
		LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = mt.subject_id
		LEFT OUTER JOIN ' . WLSM_ADMINS . ' as a ON a.ID = mt.admin_id
		LEFT OUTER JOIN ' . WLSM_STAFF . ' as sf ON a.staff_id = sf.ID
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
		WHERE mt.school_id = ' . absint($school_id);

		if ($admin_id) {
			$query .= ' AND a.ID = ' . absint($admin_id);
		}

		return $query;
	}

	public static function get_homeworks_page_url()
	{
		return admin_url('admin.php?page=' . WLSM_MENU_STAFF_HOMEWORK);
	}
	public static function get_homeworks_submisson_page_url()
	{
		return admin_url();
	}

	public static function get_homeworks_submisson_page()
	{
		 global $wp;
		 return home_url( $wp->request );
	}


	public static function get_homeworks_submission_page_url()
	{
		return admin_url('admin.php?page=sm-staff-study-homework&action=' . WLSM_MENU_STAFF_HOMEWORK_SUBMISSION);
	}

	public static function fetch_homework_query($school_id, $session_id)
	{
		$query = 'SELECT hw.ID, hw.title, hw.description, hw.homework_date, c.label as class_label, u.user_login as username FROM ' . WLSM_HOMEWORK . ' as hw
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = hw.school_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = hw.session_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = hw.added_by
		LEFT OUTER JOIN ' . WLSM_HOMEWORK_SECTION . ' as hwse ON hwse.homework_id = hw.ID
		LEFT OUTER JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = hwse.section_id
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE s.ID = ' . absint($school_id) . ' AND ss.ID = ' . absint($session_id);
		return $query;
	}

	public static function fetch_homework_submission_query($school_id, $session_id, $homework_id)
	{
		$query = 'SELECT hs.ID, hs.description, hs.submission_id, hs.attachments, hs.created_at, hw.title, sr.roll_number, sr.name, c.label as class_label FROM ' . WLSM_HOMEWORK_SUBMISSION . ' as hs
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = hs.school_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = hs.session_id
		JOIN ' . WLSM_HOMEWORK . ' as hw ON hw.ID = hs.submission_id
		LEFT OUTER JOIN ' . WLSM_HOMEWORK_SECTION . ' as hwse ON hwse.homework_id = hw.ID
		LEFT OUTER JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = hwse.section_id
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = student_id
		WHERE   s.ID = ' . absint($school_id) . ' AND hs.session_id=' . absint($session_id). ' AND hs.submission_id=' . absint($homework_id);
		return $query;
	}

	public static function fetch_homework_query_group_by()
	{
		$group_by = 'GROUP BY hw.ID';
		return $group_by;
	}

	public static function fetch_homework_submission_query_group_by()
	{
		$group_by = 'GROUP BY hs.ID';
		return $group_by;
	}

	public static function fetch_homework_query_count($school_id, $session_id)
	{
		$query = 'SELECT COUNT(DISTINCT hw.ID) FROM ' . WLSM_HOMEWORK . ' as hw
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = hw.school_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = hw.session_id
		LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = hw.added_by
		WHERE s.ID = ' . absint($school_id) . ' AND ss.ID = ' . absint($session_id);
		return $query;
	}
	public static function fetch_homework_submission_query_count($school_id, $session_id, $homework_id)
	{
		$query = 'SELECT COUNT(DISTINCT hs.ID) FROM ' . WLSM_HOMEWORK_SUBMISSION . ' as hs
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = hs.school_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = hs.session_id
		LEFT OUTER JOIN ' . WLSM_STUDENT_RECORDS . ' as SR ON SR.ID = hs.student_id
		WHERE s.ID = ' . absint($school_id) . ' AND ss.ID = ' . absint($session_id) . ' AND hs.submission_id=' . absint($homework_id);
		return $query;
	}

	public static function get_homework($school_id, $session_id, $id)
	{
		global $wpdb;
		$homework = $wpdb->get_row($wpdb->prepare('SELECT hw.ID FROM ' . WLSM_HOMEWORK . ' as hw
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = hw.school_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = hw.session_id
			WHERE s.ID = %d AND ss.ID = %d AND hw.ID = %d', $school_id, $session_id, $id));
		return $homework;
	}

	public static function fetch_homework($school_id, $session_id, $id)
	{
		global $wpdb;
		$homework = $wpdb->get_row($wpdb->prepare('SELECT hw.ID, hw.title, hw.subject, hw.description, hw.downloadable, hw.attachments, hw.homework_date, c.ID as class_id, cs.ID as class_school_id FROM ' . WLSM_HOMEWORK . ' as hw
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = hw.school_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = hw.session_id
			LEFT OUTER JOIN ' . WLSM_HOMEWORK_SECTION . ' as hwse ON hwse.homework_id = hw.ID
			LEFT OUTER JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = hwse.section_id
			LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			WHERE s.ID = %d AND ss.ID = %d AND hw.ID = %d', $school_id, $session_id, $id));
		return $homework;
	}

	public static function fetch_homework_sections($school_id, $session_id, $homework_id)
	{
		global $wpdb;
		$sections = $wpdb->get_col($wpdb->prepare('SELECT DISTINCT se.ID FROM ' . WLSM_HOMEWORK_SECTION . ' as hwse
			JOIN ' . WLSM_HOMEWORK . ' as hw ON hw.ID = hwse.homework_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = hwse.section_id
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = hw.school_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = hw.session_id
			WHERE s.ID = %d AND ss.ID = %d AND hwse.homework_id = %d ORDER BY hwse.ID ASC', $school_id, $session_id, $homework_id));
		return $sections;
	}

	public static function fetch_active_students_of_classes($school_id, $session_id, $class_ids)
	{
		global $wpdb;

		$values        = array($school_id, $session_id);
		$place_holders = array();

		foreach ($class_ids as $class_id) {
			array_push($values, $class_id);
			array_push($place_holders, '%d');
		}

		$students = $wpdb->get_results($wpdb->prepare('SELECT sr.ID, sr.enrollment_number, sr.name, sr.phone, sr.email, c.label as class_label, se.label as section_label FROM ' . WLSM_STUDENT_RECORDS . ' as sr
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			LEFT OUTER JOIN ' . WLSM_TRANSFERS . ' as tf ON tf.from_student_record = sr.ID
			WHERE cs.school_id = %d AND sr.session_id = %d AND sr.is_active = 1 AND c.ID IN(' . implode(', ', $place_holders) . ') AND tf.ID IS NULL GROUP BY sr.ID ORDER BY c.label, se.label', $values));
		return $students;
	}

	public static function get_timetable_page_url()
	{
		return admin_url('admin.php?page=' . WLSM_MENU_STAFF_TIMETABLE);
	}

	public static function fetch_timetable_query($school_id)
	{
		$query = 'SELECT se.ID, se.label as section_label, c.label as class_label FROM ' . WLSM_ROUTINES . ' as rt
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = rt.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = ' . absint($school_id);
		return $query;
	}

	public static function fetch_staff_timetable_query($school_id, $user_id)
	{
		$query = 'SELECT se.ID, se.label as section_label, c.label as class_label, rt.`day`, rt.`start_time`, rt.`end_time`, rt.`room_number`, wss.`label` FROM ' . WLSM_ROUTINES . ' as rt
		JOIN ' . WLSM_ADMINS . ' as wa ON wa.ID = rt.admin_id
		JOIN ' . WLSM_STAFF . ' as ws ON ws.ID = wa.staff_id
		JOIN ' . WLSM_USERS . ' as wu ON wu.ID = ws.user_id
		JOIN ' . WLSM_SUBJECTS . ' as wss ON wss.ID = rt.subject_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = rt.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = ' . absint($school_id).' AND wu.ID = '. $user_id ;
		return $query;
	}

	public static function fetch_timetable_query_group_by()
	{
		$group_by = 'GROUP BY se.ID';
		return $group_by;
	}

	public static function fetch_by_class_id($school_id, $class_id){
		$query = 'SELECT se.ID, se.label as section_label, c.label as class_label FROM ' . WLSM_ROUTINES . ' as rt
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = rt.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = ' . absint($school_id).' AND c.ID = '.absint($class_id);
		return $query;
	}

	public static function fetch_timetable_query_count($school_id)
	{
		$query = 'SELECT COUNT(DISTINCT se.ID) FROM ' . WLSM_ROUTINES . ' as rt
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = rt.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = ' . absint($school_id);
		return $query;
	}

	public static function fetch_timetable_query_count_staff($school_id, $user_id)
	{
		$query = 'SELECT COUNT(DISTINCT rt.ID) FROM ' . WLSM_ROUTINES . ' as rt
		JOIN ' . WLSM_ADMINS . ' as wa ON wa.ID = rt.admin_id
		JOIN ' . WLSM_STAFF . ' as ws ON ws.ID = wa.staff_id
		JOIN ' . WLSM_USERS . ' as wu ON wu.ID = ws.user_id
		JOIN ' . WLSM_SUBJECTS . ' as wss ON wss.ID = rt.subject_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = rt.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = ' . absint($school_id).' AND wu.ID = '. $user_id ;
		return $query;
	}

	public static function get_section_routine_ids($school_id, $section_id)
	{
		global $wpdb;
		$routine_ids = $wpdb->get_col($wpdb->prepare('SELECT rt.ID FROM ' . WLSM_ROUTINES . ' as rt
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = rt.section_id
			JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = rt.subject_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			WHERE cs.school_id = %d AND se.ID = %d', $school_id, $section_id));
		return $routine_ids;
	}

	public static function get_section_routines_by_day($school_id, $section_id, $day)
	{
		global $wpdb;
		$routines = $wpdb->get_results($wpdb->prepare('SELECT rt.ID, rt.start_time, rt.end_time, rt.day, rt.room_number, sj.label as subject_label, sj.code as subject_code, a.name as teacher_name FROM ' . WLSM_ROUTINES . ' as rt
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = rt.section_id
			JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = rt.subject_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			LEFT OUTER JOIN ' . WLSM_ADMINS . ' as a ON a.ID = rt.admin_id
			WHERE cs.school_id = %d AND se.ID = %d AND rt.day = %d ORDER BY rt.start_time', $school_id, $section_id, $day));
		return $routines;
	}

	public static function get_routine($school_id, $id)
	{
		global $wpdb;
		$routine = $wpdb->get_row($wpdb->prepare('SELECT rt.ID FROM ' . WLSM_ROUTINES . ' as rt
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = rt.section_id
			JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = rt.subject_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			WHERE cs.school_id = %d AND rt.ID = %d', $school_id, $id));
		return $routine;
	}

	public static function fetch_routine($school_id, $id)
	{
		global $wpdb;
		$routine = $wpdb->get_row($wpdb->prepare('SELECT rt.ID, rt.start_time, rt.end_time, rt.day, rt.room_number, rt.subject_id, rt.section_id, c.ID as class_id, c.label as class_label, se.label as section_label, rt.admin_id FROM ' . WLSM_ROUTINES . ' as rt
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = rt.section_id
			JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = rt.subject_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			LEFT OUTER JOIN ' . WLSM_ADMINS . ' as a ON a.ID = rt.admin_id
			WHERE cs.school_id = %d AND rt.ID = %d', $school_id, $id));
		return $routine;
	}

	public static function get_student_leaves_page_url()
	{
		return admin_url('admin.php?page=' . WLSM_MENU_STAFF_STUDENT_LEAVES);
	}

	public static function fetch_student_leave_query($school_id, $session_id)
	{
		$query = 'SELECT lv.ID, lv.description, lv.start_date, lv.end_date, lv.is_approved, lv.approved_by, c.label as class_label, se.label as section_label, sr.enrollment_number, sr.name as student_name FROM ' . WLSM_LEAVES . ' as lv
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = lv.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = ' . absint($school_id) . ' AND ss.ID = ' . absint($session_id);
		return $query;
	}

	public static function fetch_student_leave_query_group_by()
	{
		$group_by = 'GROUP BY lv.ID';
		return $group_by;
	}

	public static function fetch_student_leave_query_count($school_id, $session_id)
	{
		$query = 'SELECT COUNT(DISTINCT lv.ID) FROM ' . WLSM_LEAVES . ' as lv
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = lv.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = ' . absint($school_id) . ' AND ss.ID = ' . absint($session_id);
		return $query;
	}

	public static function get_student_leave($school_id, $session_id, $id)
	{
		global $wpdb;
		$student_leave = $wpdb->get_row($wpdb->prepare('SELECT lv.ID, lv.approved_by FROM ' . WLSM_LEAVES . ' as lv
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = lv.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		WHERE cs.school_id = %d AND ss.ID = %d AND lv.ID = %d', $school_id, $session_id, $id));
		return $student_leave;
	}

	public static function fetch_student_leave($school_id, $session_id, $id)
	{
		global $wpdb;
		$student_leave = $wpdb->get_row($wpdb->prepare('SELECT lv.ID, lv.description, lv.start_date, lv.end_date, lv.is_approved, lv.approved_by, sr.name as student_name, sr.enrollment_number, sr.ID as student_id FROM ' . WLSM_LEAVES . ' as lv
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = lv.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		WHERE cs.school_id = %d AND ss.ID = %d AND lv.ID = %d', $school_id, $session_id, $id));
		return $student_leave;
	}

	public static function get_section_label_text($label)
	{
		if ($label) {
			return stripcslashes($label);
		}
		return '';
	}

	public static function get_subject_label_text($label)
	{
		if ($label) {
			return stripcslashes($label);
		}
		return '';
	}

	public static function get_subject_code_text($code)
	{
		if ($code) {
			return $code;
		}
		return '-';
	}

	public static function get_status_text($is_active)
	{
		if ($is_active) {
			return self::get_active_text();
		}
		return self::get_inactive_text();
	}

	public static function get_active_text()
	{
		return __('Active', 'school-management');
	}

	public static function get_inactive_text()
	{
		return __('Inactive', 'school-management');
	}

	public static function get_from_front_text($from_front)
	{
		if ($from_front) {
			return __('Yes', 'school-management');
		}
		return __('No', 'school-management');
	}

	public static function get_link_to_text($link_to)
	{
		if ('attachment' === $link_to) {
			return self::get_attachment_text();
		} else if ('url' === $link_to) {
			return self::get_url_text();
		}
		return self::get_none_text();
	}

	public static function get_none_text()
	{
		return __('None', 'school-management');
	}

	public static function get_attachment_text()
	{
		return __('Attachment', 'school-management');
	}

	public static function get_url_text()
	{
		return __('URL', 'school-management');
	}

	public static function subject_type_list() {
		return array(
			'theory'     => esc_html__( 'Theory', 'school-management' ),
			'practical'  => esc_html__( 'Practical', 'school-management' ),
			'subjective' => esc_html__( 'Subjective', 'school-management' ),
			'objective'  => esc_html__( 'Objective', 'school-management' ),
		);
	}
	public static function get_subject_type( $key ) {
		if ( is_numeric( $key ) ) {
			$subject_types = self::subject_type_list();
			if ( isset( $subject_types[ $key ] ) ) {
				return $subject_types[ $key ];
			}
		}
		return $key;
	}

	public static function get_subject_type_text($subject_type)
	{
		$subject_types = WLSM_M_Staff_Class::fetch_subject_type();
		if (isset($subject_types[$subject_type])) {
			return $subject_types[$subject_type];
		}
		return '-';
	}

	public static function get_name_text($name)
	{
		if ($name) {
			return stripcslashes($name);
		}
		return '-';
	}

	public static function get_homework_submission_link($name)
	{
		if ( ! empty ($name ) ) {
			$file_name = basename ( get_attached_file($name ) );
			$file_name = wp_get_attachment_url($name);
			return $file_name;
		}
		return '-';
	}

	public static function get_submission_text($arg)
	{
		if (!empty($arg) ) {
			$arg = "Done";
			return $arg;
		}
		return '-';
	}

	public static function get_phone_text($phone)
	{
		if ($phone) {
			return $phone;
		}
		return '-';
	}

	public static function get_email_text($email)
	{
		if ($email) {
			return $email;
		}
		return '-';
	}

	public static function get_username_text($username)
	{
		if ($username) {
			return $username;
		}
		return '-';
	}

	public static function get_admission_no_text($admission_number)
	{
		if ($admission_number) {
			return $admission_number;
		}
		return '-';
	}

	public static function get_roll_no_text($roll_number)
	{
		if ($roll_number) {
			return $roll_number;
		}
		return '-';
	}

	public static function get_designation_text($designation)
	{
		if ($designation) {
			return $designation;
		}
		return '-';
	}

	public static function get_certificate_label_text($label)
	{
		if ($label) {
			return $label;
		}
		return '';
	}

	public static function get_default_section_text()
	{
		return esc_html__('Default', 'school-management');
	}

	public static function get_leave_approval_text($is_approved, $color = false)
	{
		if ($is_approved) {
			if ($color) {
				return '<span class="wlsm-font-bold wlsm-text-primary text-primary">' . self::get_approved_text() . '</span>';
			} else {
				return self::get_approved_text();
			}
		}

		if ($color) {
			return '<span class="wlsm-font-bold wlsm-text-danger text-danger">' . self::get_unapproved_text() . '</span>';
		} else {
			return self::get_unapproved_text();
		}
	}

	public static function get_approved_text()
	{
		return esc_html__('Approved', 'school-management');
	}

	public static function get_unapproved_text()
	{
		return esc_html__('Unapproved', 'school-management');
	}

	public static function get_activity_approval_text($status)	{
		if ($status == 0) {
			return esc_html__('Inactive', 'school-management');
		} else {
			return esc_html__('Active', 'school-management');
		}
	}

	public static function get_student_activity_page_url()
	{
		return admin_url('admin.php?page=' . WLSM_ACTIVITIES);
	}

	public static function fetch_student_activity_query($school_id)
	{
		$query = 'SELECT av.ID, av.class_id, av.description,av.is_approved, av.title, av.fees, c.label FROM ' . WLSM_ACTIVITIES . ' as av JOIN ' . WLSM_CLASSES . ' as c ON c.ID = av.class_id WHERE av.school_id = ' . absint($school_id);
		return $query;
	}

	public static function fetch_student_activity_query_group_by()
	{
		$group_by = 'GROUP BY av.ID';
		return $group_by;
	}

	public static function fetch_student_activity_query_count($school_id, $session_id)
	{
		$query = 'SELECT COUNT(DISTINCT av.ID) FROM ' . WLSM_ACTIVITIES . ' as av
		WHERE av.school_id = ' . absint($school_id);
		return $query;
	}

	public static function fetch_student_activity($id)	{
		global $wpdb;
		$data = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . WLSM_ACTIVITIES . ' as av
		WHERE av.ID = %d', $id));
		return $data;
	}
}
