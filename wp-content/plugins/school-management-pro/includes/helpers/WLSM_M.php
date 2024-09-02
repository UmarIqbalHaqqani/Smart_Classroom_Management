<?php
defined( 'ABSPATH' ) || die();

class WLSM_M {
	public static function get_student( $user_id ) {
		global $wpdb;
		$student = $wpdb->get_row(
			$wpdb->prepare( 'SELECT sr.ID, sr.name as student_name, sr.email, sr.phone, sr.father_name, sr.admission_number, sr.route_vehicle_id, sr.enrollment_number, sr.photo_id, c.ID as class_id, c.label as class_label, se.class_school_id, se.ID as section_id, se.label as section_label, sr.roll_number, u.user_email as login_email, u.user_login as username, sr.session_id, ss.label as session_label, s.ID as school_id, s.label as school_name FROM ' . WLSM_STUDENT_RECORDS . ' as sr
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id
				JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id
				LEFT OUTER JOIN ' . WLSM_TRANSFERS . ' as tf ON tf.from_student_record = sr.ID
				WHERE sr.is_active = 1 AND tf.ID IS NULL AND sr.user_id = %d', $user_id )
		);

		return $student;
	}

	public static function get_student_profile( $user_id ) {
		global $wpdb;
		$student = $wpdb->get_row(
			$wpdb->prepare( 'SELECT sr.ID, sr.name as student_name, sr.photo_id, c.label as class_label, se.label as section_label, ss.label as session_label, s.label as school_name FROM ' . WLSM_STUDENT_RECORDS . ' as sr
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id
				LEFT OUTER JOIN ' . WLSM_TRANSFERS . ' as tf ON tf.from_student_record = sr.ID
				WHERE sr.is_active = 1 AND tf.ID IS NULL AND sr.user_id = %d', $user_id )
		);

		return $student;
	}

	public static function notices_per_page() {
		return 10;
	}

	public static function lesson_per_page() {
		return 6;
	}

	public static function lesson_query() {
		return 'SELECT l.ID, l.title, l.description, l.attachment, l.url, l.link_to, l.created_at, c.label as class, s.label as `subject`, cp.title as chapter FROM ' . WLSM_LECTURE . ' as l 
		JOIN ' . WLSM_CLASSES . ' as c ON l.class_id = c.ID
		LEFT OUTER JOIN ' . WLSM_SUBJECTS . ' as s ON s.ID = l.subject_id  
		LEFT OUTER JOIN ' . WLSM_CHAPTER . ' as cp ON cp.ID = l.chapter_id';
	}

	public static function notices_query() {
		return 'SELECT n.ID, n.title, n.description, n.attachment, n.url, n.link_to, n.is_active, n.created_at, COUNT(DISTINCT csn.ID) as classes_count, COUNT(DISTINCT csn2.ID) as other_classes_count FROM ' . WLSM_NOTICES . ' as n
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL_NOTICE . ' as csn ON csn.notice_id = n.ID AND (csn.student_school_id = %d)
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL_NOTICE . ' as csn2 ON csn2.notice_id = n.ID AND (csn2.student_school_id != %d)
		WHERE n.school_id = %d AND n.is_active = 1 GROUP BY n.ID HAVING (classes_count = 0 AND other_classes_count = 0) OR classes_count = 1';
	}

	public static function payments_per_page() {
		return 10;
	}

	public static function payments_query() {
		return 'SELECT sr.ID as student_id, sr.name as student_name, sr.admission_number, sr.phone, sr.father_name, sr.father_phone, p.ID, p.receipt_number, p.amount, p.payment_method, p.transaction_id, p.created_at, p.note, p.invoice_label, p.invoice_payable, p.invoice_id, i.label as invoice_title, c.label as class_label, se.label as section_label FROM ' . WLSM_PAYMENTS . ' as p
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = p.school_id
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = p.student_record_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
			LEFT OUTER JOIN ' . WLSM_INVOICES . ' as i ON i.ID = p.invoice_id
			WHERE sr.ID = %d GROUP BY p.ID';
	}

	public static function events_per_page() {
		return 10;
	}

	public static function events_query() {
		return 'SELECT ev.ID, ev.title, ev.event_date, ev.image_id, COUNT(sr.ID) as student_joined FROM ' . WLSM_EVENTS . ' as ev
		LEFT OUTER JOIN ' . WLSM_EVENT_RESPONSES . ' as evr ON evr.event_id = ev.ID
		LEFT OUTER JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON evr.student_record_id = sr.ID AND sr.ID = %d
		WHERE ev.school_id = %d AND ev.is_active = 1 GROUP BY ev.ID';
	}

	public static function books_issued_per_page() {
		return 10;
	}

	public static function books_issued_query() {
		return 'SELECT bki.ID, bki.quantity as issued_quantity, bki.date_issued, bki.return_date, bki.returned_at, bk.title, bk.author, bk.subject, bk.rack_number, bk.book_number, bk.isbn_number FROM ' . WLSM_BOOKS_ISSUED . ' as bki
		JOIN ' . WLSM_BOOKS . ' as bk ON bk.ID = bki.book_id
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = bki.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = %d AND ss.ID = %d AND sr.ID = %d GROUP BY bki.ID';
	}

	public static function study_materials_per_page() {
		return 10;
	}

	public static function study_materials_query() {
		return 'SELECT sm.ID, sm.label as title, sm.description, sm.downloadable, sm.attachments, sm.created_at FROM ' . WLSM_CLASS_SCHOOL_STUDY_MATERIAL . ' as cssm 
		JOIN ' . WLSM_STUDY_MATERIALS . ' as sm ON sm.ID = cssm.study_material_id 
		JOIN ' . WLSM_SUBJECTS . ' as wl ON wl.ID = cssm.study_material_subject_id
		LEFT OUTER JOIN ' . WLSM_SECTIONS . ' as ws ON ws.ID = cssm.study_material_section_id
		WHERE cssm.class_school_id = %d GROUP BY sm.ID';
	}

	public static function study_material_query() {
		return 'SELECT sm.ID, sm.label as title, sm.description, sm.downloadable, sm.url, sm.attachments, sm.created_at FROM ' . WLSM_CLASS_SCHOOL_STUDY_MATERIAL . ' as cssm JOIN ' . WLSM_STUDY_MATERIALS . ' as sm ON sm.ID = cssm.study_material_id WHERE cssm.class_school_id = %d AND sm.ID = %d';
	}

	public static function leaves_per_page() {
		return 10;
	}

	public static function leaves_query() {
		return 'SELECT lv.ID, lv.description, lv.start_date, lv.end_date, lv.is_approved, lv.approved_by, c.label as class_label, se.label as section_label, sr.enrollment_number, sr.name as student_name FROM ' . WLSM_LEAVES . ' as lv
		JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = lv.student_record_id
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = %d AND ss.ID = %d AND sr.ID = %d GROUP BY lv.ID';
	}

	public static function homeworks_per_page() {
		return 10;
	}

	public static function homeworks_query() {
		return 'SELECT hw.ID, hw.title, hw.description, hw.homework_date FROM ' . WLSM_HOMEWORK . ' as hw
					JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = hw.school_id
					JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = hw.session_id
					LEFT OUTER JOIN ' . WLSM_HOMEWORK_SECTION . ' as hwse ON hwse.homework_id = hw.ID
					LEFT OUTER JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = hwse.section_id
					LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
					LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
					WHERE s.ID = %d AND ss.ID = %d AND se.ID = %d GROUP BY hw.ID';
	}


	public static function homework_query() {
		return 'SELECT hw.ID, hw.title, hw.subject, hw.description, hw.downloadable, hw.attachments, hw.homework_date, c.ID as class_id, cs.ID as class_school_id FROM ' . WLSM_HOMEWORK . ' as hw
				JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = hw.school_id
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = hw.session_id
				LEFT OUTER JOIN ' . WLSM_HOMEWORK_SECTION . ' as hwse ON hwse.homework_id = hw.ID
				LEFT OUTER JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = hwse.section_id
				LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				WHERE s.ID = %d AND ss.ID = %d AND se.ID = %d AND hw.ID = %d';
	}
	
	public static function homework_query_submission() {
		return 'SELECT hw.ID, hw.title, hw.subject, hw.description, hs.student_id, hw.attachments, hw.homework_date, hs.created_at, hs.updated_at, c.ID as class_id, cs.ID as class_school_id FROM ' . WLSM_HOMEWORK . ' as hw
				JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = hw.school_id
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = hw.session_id
				JOIN ' . WLSM_HOMEWORK_SUBMISSION . ' as hs ON hw.ID = hs.submission_id
				LEFT OUTER JOIN ' . WLSM_HOMEWORK_SECTION . ' as hwse ON hwse.homework_id = hw.ID
				LEFT OUTER JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = hwse.section_id
				LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				WHERE s.ID = %d AND ss.ID = %d AND se.ID = %d AND hw.ID = %d AND hs.student_id = %d';
	}

	public static function meetings_per_page() {
		return 15;
	}
	public static function get_subject($id) {
		global $wpdb;
		return $wpdb->prepare('SELECT * FROM ' . WLSM_SUBJECTS . ' as sj 
		WHERE sj.ID = %d', $id);
	}

	public static function meetings_query() {
		return 'SELECT mt.ID, mt.host_id, mt.meeting_id, mt.recordable, mt.topic, mt.duration, mt.start_at, mt.type, mt.password, mt.join_url, sj.label as subject_name, se.ID as section_id, sj.code as subject_code, a.name as name, st.user_id FROM ' . WLSM_MEETINGS . ' as mt
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = mt.school_id
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = mt.class_school_id
		LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		LEFT OUTER JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = mt.section_id
		LEFT OUTER JOIN ' . WLSM_SUBJECTS . ' as sj ON sj.ID = mt.subject_id
		LEFT OUTER JOIN ' . WLSM_ADMINS . ' as a ON a.ID = mt.admin_id
		LEFT OUTER JOIN ' . WLSM_STAFF . ' as st ON st.ID = a.staff_id
		WHERE mt.school_id = %d AND mt.class_school_id = %d GROUP BY mt.ID';
	}

	
}
