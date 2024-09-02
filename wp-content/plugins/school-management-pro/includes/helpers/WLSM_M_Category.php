<?php
defined('ABSPATH') || die();

class WLSM_M_Category
{
	public static function get_page_url()
	{
		return admin_url('admin.php?page=' . WLSM_MENU_CATEGORY);
	}

	public static function fetch_query()
	{
		$query = 'SELECT c.ID, c.label FROM ' . WLSM_CATEGORY . ' as c';
		return $query;
	}

	public static function fetch_query_group_by()
	{
		$group_by = 'GROUP BY c.ID';
		return $group_by;
	}

	public static function fetch_query_count()
	{
		$query = 'SELECT COUNT(c.ID) FROM ' . WLSM_CATEGORY . ' as c';
		return $query;
	}

	public static function get_Category($id)
	{
		global $wpdb;
		$class = $wpdb->get_row($wpdb->prepare('SELECT c.ID FROM ' . WLSM_CATEGORY . ' as c WHERE c.ID = %d', $id));
		return $class;
	}

	public static function fetch_Category($id)
	{
		global $wpdb;
		$class = $wpdb->get_row($wpdb->prepare('SELECT c.ID, c.label FROM ' . WLSM_CATEGORY . ' as c WHERE c.ID = %d', $id));
		return $class;
	}

	public static function fetch_Categories()
	{
		global $wpdb;
		$category = $wpdb->get_results($wpdb->prepare('SELECT c.ID, c.label FROM ' . WLSM_CATEGORY . ' as c'));
		return $category;
	}

	public static function get_label_text($label)
	{
		if ($label) {
			return stripcslashes($label);
		}
		return '';
	}
}
