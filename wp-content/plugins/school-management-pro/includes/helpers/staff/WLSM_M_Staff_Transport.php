<?php
defined( 'ABSPATH' ) || die();

class WLSM_M_Staff_Transport {
	public static function get_vehicles_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_VEHICLES );
	}

	public static function get_routes_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_ROUTES );
	}

	public static function fetch_vehicle_query( $school_id ) {
		$query = 'SELECT v.ID, v.vehicle_number, v.vehicle_model, v.driver_name, v.driver_phone FROM ' . WLSM_VEHICLES . ' as v WHERE v.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function fetch_vehicle_query_group_by() {
		$group_by = 'GROUP BY v.ID';
		return $group_by;
	}

	public static function fetch_vehicle_query_count( $school_id ) {
		$query = 'SELECT COUNT(DISTINCT v.ID) FROM ' . WLSM_VEHICLES . ' as v WHERE v.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function get_vehicle( $school_id, $id ) {
		global $wpdb;
		$vehicle = $wpdb->get_row( $wpdb->prepare( 'SELECT v.ID FROM ' . WLSM_VEHICLES . ' as v WHERE v.school_id = %d AND v.ID = %d', $school_id, $id ) );
		return $vehicle;
	}

	public static function fetch_vehicle( $school_id, $id ) {
		global $wpdb;
		$vehicle = $wpdb->get_row( $wpdb->prepare( 'SELECT v.ID, v.vehicle_number, v.vehicle_model, v.driver_name, v.driver_phone, v.note FROM ' . WLSM_VEHICLES . ' as v WHERE v.school_id = %d AND v.ID = %d', $school_id, $id ) );
		return $vehicle;
	}

	public static function fetch_vehicles( $school_id ) {
		global $wpdb;
		$vehicles = $wpdb->get_results( $wpdb->prepare( 'SELECT v.ID, v.vehicle_number FROM ' . WLSM_VEHICLES . ' as v WHERE v.school_id = %d', $school_id ) );
		return $vehicles;
	}

	public static function get_vehicle_incharge( $school_id, $vehicle_id ) {
		global $wpdb;
		$admins = $wpdb->get_results(
			$wpdb->prepare( 'SELECT a.ID, a.name, a.phone, u.user_login as username, sf.role FROM ' . WLSM_ADMINS . ' as a
			JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
			JOIN ' . WLSM_VEHICLES . ' as v ON v.ID = a.vehicle_id
			LEFT OUTER JOIN ' . WLSM_USERS . ' as u ON u.ID = sf.user_id
			WHERE sf.school_id = %d AND v.ID = %d', $school_id, $vehicle_id )
		);
		return $admins;
	}

	public static function fetch_route_query( $school_id ) {
		$query = 'SELECT ro.ID, ro.name, ro.fare, COUNT(DISTINCT rov.ID) as vehicles_count FROM ' . WLSM_ROUTES . ' as ro
		LEFT OUTER JOIN ' . WLSM_ROUTE_VEHICLE . ' as rov ON rov.route_id = ro.ID
		WHERE ro.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function fetch_route_query_group_by() {
		$group_by = 'GROUP BY ro.ID';
		return $group_by;
	}

	public static function fetch_route_query_count( $school_id ) {
		$query = 'SELECT COUNT(DISTINCT ro.ID) FROM ' . WLSM_ROUTES . ' as ro WHERE ro.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function get_route( $school_id, $id ) {
		global $wpdb;
		$route = $wpdb->get_row( $wpdb->prepare( 'SELECT ro.ID FROM ' . WLSM_ROUTES . ' as ro WHERE ro.school_id = %d AND ro.ID = %d', $school_id, $id ) );
		return $route;
	}

	public static function fetch_route( $school_id, $id ) {
		global $wpdb;
		$route = $wpdb->get_row( $wpdb->prepare( 'SELECT ro.ID, ro.name, ro.fare, ro.period FROM ' . WLSM_ROUTES . ' as ro WHERE ro.school_id = %d AND ro.ID = %d', $school_id, $id ) );
		return $route;
	}

	public static function fetch_routes( $school_id ) {
		global $wpdb;
		$routes = $wpdb->get_results( $wpdb->prepare( 'SELECT ro.ID, ro.name, ro.fare FROM ' . WLSM_ROUTES . ' as ro WHERE ro.school_id = %d', $school_id ) );
		return $routes;
	}

	public static function fetch_route_vehicles( $school_id, $route_id ) {
		global $wpdb;
		$vehicles = $wpdb->get_col( $wpdb->prepare( 'SELECT DISTINCT v.ID FROM ' . WLSM_ROUTE_VEHICLE . ' as rov
			JOIN ' . WLSM_ROUTES . ' as ro ON ro.ID = rov.route_id
			JOIN ' . WLSM_VEHICLES . ' as v ON v.ID = rov.vehicle_id
			WHERE ro.school_id = %d AND ro.ID = %d ORDER BY v.vehicle_number ASC', $school_id, $route_id ) );
		return $vehicles;
	}

	public static function fetch_routes_vehicles( $school_id ) {
		global $wpdb;
		$routes_vehicles = $wpdb->get_results( $wpdb->prepare( 'SELECT rov.ID, ro.ID as route_id, ro.name as route_name, v.ID as vehicle_id, v.vehicle_number FROM ' . WLSM_ROUTE_VEHICLE . ' as rov
			JOIN ' . WLSM_ROUTES . ' as ro ON ro.ID = rov.route_id
			JOIN ' . WLSM_VEHICLES . ' as v ON v.ID = rov.vehicle_id
			WHERE ro.school_id = %d GROUP BY rov.ID', $school_id ) );
		return $routes_vehicles;
	}
	public static function fetch_hostel_rooms( ) {
		global $wpdb;
		$hostel_rooms = $wpdb->get_results( $wpdb->prepare( 'SELECT ro.ID, ro.room_name, h.hostel_type FROM ' . WLSM_ROOMS . ' as ro
			JOIN ' . WLSM_HOSTELS . ' as h ON ro.hostel_id = h.ID' ) );
		return $hostel_rooms;
	}

	public static function get_route_vehicle( $school_id, $route_vehicle_id ) {
		global $wpdb;
		$route_vehicle = $wpdb->get_row( $wpdb->prepare( 'SELECT rov.ID, ro.fare, ro.period  FROM ' . WLSM_ROUTE_VEHICLE . ' as rov
			JOIN ' . WLSM_ROUTES . ' as ro ON ro.ID = rov.route_id
			JOIN ' . WLSM_VEHICLES . ' as v ON v.ID = rov.vehicle_id
			WHERE ro.school_id = %d AND rov.ID = %d', $school_id, $route_vehicle_id ) );
		return $route_vehicle;
	}

	// Hostel

	public static function get_room( $school_id, $id ) {
		global $wpdb;
		$room = $wpdb->get_row( $wpdb->prepare( 'SELECT h.ID FROM ' . WLSM_ROOMS . ' as h
		LEFT OUTER JOIN ' . WLSM_HOSTELS . ' as wh ON h.hostel_id = wh.ID
		WHERE wh.school_id = %d AND h.ID = %d', $school_id, $id ) );
		return $room;
	}

	public static function get_hostels_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_HOSTELS );
	}

	public static function get_lecture_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_LECTURE );
	}

	public static function get_chapter_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_CHAPTER );
	}


	public static function get_rooms_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_ROOMS );
	}

	public static function fetch_room_query( $school_id ) {
		$query = 'SELECT h.ID, h.room_name, h.hostel_id, h.number_of_beds, h.note, wh.hostel_name FROM ' . WLSM_ROOMS . ' as h
		LEFT OUTER JOIN ' . WLSM_HOSTELS . ' as wh ON h.hostel_id = wh.ID
		WHERE wh.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function fetch_hostel_query( $school_id ) {
		$query = 'SELECT h.ID, h.hostel_name, h.hostel_type, h.hostel_address, h.hostel_intake, COUNT(r.ID) as room_count FROM ' . WLSM_HOSTELS . ' as h
		LEFT JOIN ' . WLSM_ROOMS . ' as r ON h.ID = r.hostel_id
		WHERE h.school_id = ' . absint( $school_id ) . '';
		return $query;
	}

	public static function fetch_hostel_query_group_by() {
		$group_by = 'GROUP BY h.ID';
		return $group_by;
	}

	public static function fetch_hostel_query_count( $school_id ) {
		$query = 'SELECT COUNT(DISTINCT v.ID) FROM ' . WLSM_HOSTELS . ' as v WHERE v.school_id = ' . absint( $school_id );
		return $query;
	}

	public static function get_hostel( $school_id, $id ) {
		global $wpdb;
		$hostel = $wpdb->get_row( $wpdb->prepare( 'SELECT h.ID FROM ' . WLSM_HOSTELS . ' as h WHERE h.school_id = %d AND h.ID = %d', $school_id, $id ) );
		return $hostel;
	}

	public static function fetch_hostel( $school_id, $id ) {
		global $wpdb;
		$hostel = $wpdb->get_row( $wpdb->prepare( 'SELECT h.ID, h.hostel_name, h.hostel_type, h.hostel_address, h.hostel_intake, h.fees FROM ' . WLSM_HOSTELS . ' as h WHERE h.school_id = %d AND h.ID = %d', $school_id, $id ) );
		return $hostel;
	}

	public static function fetch_room( $school_id, $id ) {
		global $wpdb;
		$room = $wpdb->get_row( $wpdb->prepare( 'SELECT h.ID, h.room_name, h.hostel_id, h.number_of_beds, h.note, wh.hostel_name FROM ' . WLSM_ROOMS . ' as h
		LEFT OUTER JOIN ' . WLSM_HOSTELS . ' as wh ON h.hostel_id = wh.ID
		WHERE wh.school_id = %d AND h.ID = %d', $school_id, $id ) );
		return $room;
	}

	public static function fetch_rooms( $school_id, $room_id ) {
		global $wpdb;
		$vehicles = $wpdb->get_col( $wpdb->prepare( 'SELECT DISTINCT wh.ID FROM ' . WLSM_ROOMS . ' as rov
		JOIN ' . WLSM_HOSTELS . ' as wh ON rov.hostel_id = wh.ID
		WHERE wh.school_id = %d AND rov.ID = %d', $school_id, $room_id ) );
		return $vehicles;
	}

	public static function fetch_hostels( $school_id ) {
		global $wpdb;
		$hostels = $wpdb->get_results( $wpdb->prepare( 'SELECT h.ID, h.hostel_name, h.hostel_type, h.hostel_address, h.hostel_intake FROM ' . WLSM_HOSTELS . ' as h WHERE h.school_id = %d', $school_id ) );
		return $hostels;
	}

}
