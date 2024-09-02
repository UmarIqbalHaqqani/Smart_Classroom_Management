<?php
defined( 'ABSPATH' ) || die();

class WLSM_M_Staff_Lecture {


	public static function get_lecture_page_url() {
		 return admin_url( 'admin.php?page=' . WLSM_LECTURE );
	}

	public static function get_chapter_page_url() {
		 return admin_url( 'admin.php?page=' . WLSM_CHAPTER );
	}

	public static function fetch_lecture( $id ) {
		global $wpdb;
		$query = $wpdb->prepare(
			'SELECT l.ID, l.description, l.title, l.attachment, l.url, l.link_to, l.created_at, c.ID as class_id, c.label as class, s.label as `subject`, s.ID as subject_id, l.chapter_id  FROM ' . WLSM_LECTURE . ' as l
			JOIN ' . WLSM_CLASSES . ' as c ON l.class_id = c.ID
			JOIN ' . WLSM_SUBJECTS . ' as s ON s.ID = l.subject_id
			WHERE l.id =%s',
			$id
		);
		return $wpdb->get_row( $query );
	}

	public static function fetch_lecture_query() {
		$query = 'SELECT l.ID, l.title, l.attachment, l.url, l.link_to, l.created_at, c.label as class, s.label as `subject`, cp.title as chapter FROM ' . WLSM_LECTURE . ' as l
		JOIN ' . WLSM_CLASSES . ' as c ON l.class_id = c.ID
		LEFT OUTER JOIN ' . WLSM_SUBJECTS . ' as s ON s.ID = l.subject_id
		LEFT OUTER JOIN ' . WLSM_CHAPTER . ' as cp ON cp.ID = l.chapter_id';
		return $query;
	}

	public static function fetch_lecture_query_group_by() {
		 $group_by = 'GROUP BY l.ID';
		return $group_by;
	}

	public static function fetch_lecture_query_count() {
		$query = 'SELECT COUNT(DISTINCT l.ID) FROM ' . WLSM_LECTURE . ' as l';
		return $query;
	}


	public static function get_lecture( $id ) {
		global $wpdb;
		$lecture = $wpdb->get_row( $wpdb->prepare( 'SELECT v.ID FROM ' . WLSM_LECTURE . ' as v WHERE v.ID = %d', $id ) );
		return $lecture;
	}

	public static function fetch_chapter_query() {
		$query = 'SELECT l.ID, l.title, l.created_at, c.label as class, s.label as `subject`  FROM ' . WLSM_CHAPTER . ' as l
		JOIN ' . WLSM_CLASSES . ' as c ON l.class_id = c.ID
		JOIN ' . WLSM_SUBJECTS . ' as s ON s.ID = l.subject_id
		';
		return $query;
	}

	public static function fetch_chapter( $id ) {
		global $wpdb;
		$query = $wpdb->prepare(
			'SELECT l.ID, l.title, l.created_at, c.ID as class_id, c.label as class, s.ID as `subject_id`, s.label  FROM ' . WLSM_CHAPTER . ' as l
			JOIN ' . WLSM_CLASSES . ' as c ON l.class_id = c.ID
			JOIN ' . WLSM_SUBJECTS . ' as s ON s.ID = l.subject_id
			WHERE l.id =%s',
			$id
		);
		return $wpdb->get_row( $query );
	}

	public static function fetch_chapter_query_group_by() {
		$group_by = 'GROUP BY l.ID';
		return $group_by;
	}

	public static function fetch_chapter_query_count() {
		$query = 'SELECT COUNT(DISTINCT l.ID) FROM ' . WLSM_CHAPTER . ' as l';
		return $query;
	}

	public static function get_chapter( $id ) {
		global $wpdb;
		$chapter = $wpdb->get_row( $wpdb->prepare( 'SELECT v.ID FROM ' . WLSM_CHAPTER . ' as v WHERE v.ID = %d', $id ) );
		return $chapter;
	}
}
