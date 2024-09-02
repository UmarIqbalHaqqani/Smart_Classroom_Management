<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Transport.php';

class WLSM_Staff_Transport {
	public static function fetch_vehicles() {
		$current_user = WLSM_M_Role::can( 'manage_transport' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		global $wpdb;

		$page_url = WLSM_M_Staff_Transport::get_vehicles_page_url();

		$query = WLSM_M_Staff_Transport::fetch_vehicle_query( $school_id );

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Transport::fetch_vehicle_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(v.vehicle_number LIKE "%' . $search_value . '%") OR ' .
				'(v.vehicle_model LIKE "%' . $search_value . '%") OR ' .
				'(v.driver_name LIKE "%' . $search_value . '%") OR ' .
				'(v.driver_phone LIKE "%' . $search_value . '%")';

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'v.vehicle_number', 'v.vehicle_model', 'v.driver_name', 'v.driver_phone' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY v.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Transport::fetch_vehicle_query_count( $school_id );

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

				$vehicle_id = $row->ID;

				// Get vehicle incharge.
				$admins = WLSM_M_Staff_Transport::get_vehicle_incharge( $school_id, $vehicle_id );

				if ( count( $admins ) ) {
					$vehicle_incharge = '';
					foreach ( $admins as $admin ) {
						if ( WLSM_M_Role::get_admin_key() === $admin->role ) {
							$staff_page_url = WLSM_M_Staff_General::get_admins_page_url();
						} else {
							$staff_page_url = WLSM_M_Staff_General::get_employees_page_url();
						}

						$vehicle_incharge .= '- <span class="wlsm-font-bold"><a class="text-dark" target="_blank" href="' . esc_url( $staff_page_url . '&action=save&id=' . $admin->ID ) . '">' . esc_html( stripcslashes( $admin->name ) );

						if ( $admin->phone ) {
							$vehicle_incharge .= ' (' . esc_html( $admin->phone ) . ')';
						}

						if ( $admin->username ) {
							$vehicle_incharge .= ' (' . esc_html( $admin->username ) . ')';
						}

						$vehicle_incharge .= '</a></span><br>';
					}
				} else {
					$vehicle_incharge = '-';
				}

				// Table columns.
				$data[] = array(
					esc_html( $row->vehicle_number ),
					esc_html( $row->vehicle_model ),
					esc_html( WLSM_M_Staff_Class::get_name_text( $row->driver_name ) ),
					esc_html( WLSM_M_Staff_Class::get_phone_text( $row->driver_phone ) ),
					$vehicle_incharge,
					'<a class="text-primary" href="' . esc_url( $page_url . "&action=save&id=" . $row->ID ) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-vehicle" data-nonce="' . esc_attr( wp_create_nonce( 'delete-vehicle-' . $row->ID ) ) . '" data-vehicle="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Please Confirm!', 'school-management' ) . '" data-message-content="' . esc_attr__( 'This will delete the vehicle.', 'school-management' ) . '" data-cancel="' . esc_attr__( 'Cancel', 'school-management' ) . '" data-submit="' . esc_attr__( 'Confirm', 'school-management' ) . '"><span class="dashicons dashicons-trash"></span></a>'
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

	public static function save_vehicle() {
		$current_user = WLSM_M_Role::can( 'manage_transport' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$vehicle_id = isset( $_POST['vehicle_id'] ) ? absint( $_POST['vehicle_id'] ) : 0;

			if ( $vehicle_id ) {
				if ( ! wp_verify_nonce( $_POST[ 'edit-vehicle-' . $vehicle_id ], 'edit-vehicle-' . $vehicle_id ) ) {
					die();
				}
			} else {
				if ( ! wp_verify_nonce( $_POST['add-vehicle'], 'add-vehicle' ) ) {
					die();
				}
			}

			// Checks if vehicle exists.
			if ( $vehicle_id ) {
				$vehicle = WLSM_M_Staff_Transport::get_vehicle( $school_id, $vehicle_id );

				if ( ! $vehicle ) {
					throw new Exception( esc_html__( 'Vehicle not found.', 'school-management' ) );
				}
			}

			$vehicle_number = isset( $_POST['vehicle_number'] ) ? sanitize_text_field( $_POST['vehicle_number'] ) : '';
			$vehicle_model  = isset( $_POST['vehicle_model'] ) ? sanitize_text_field( $_POST['vehicle_model'] ) : '';
			$driver_name    = isset( $_POST['driver_name'] ) ? sanitize_text_field( $_POST['driver_name'] ) : '';
			$driver_phone   = isset( $_POST['driver_phone'] ) ? sanitize_text_field( $_POST['driver_phone'] ) : '';
			$note           = isset( $_POST['note'] ) ? sanitize_text_field( $_POST['note'] ) : '';

			// Start validation.
			$errors = array();

			if ( empty( $vehicle_number ) ) {
				$errors['vehicle_number'] = esc_html__( 'Please specify vehicle number.', 'school-management' );
			} elseif ( strlen( $vehicle_number ) > 60 ) {
				$errors['vehicle_number'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
			}

			if ( strlen( $vehicle_model ) > 60 ) {
				$errors['vehicle_model'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
			}

			if ( strlen( $driver_name ) > 60 ) {
				$errors['driver_name'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
			}

			if ( strlen( $driver_phone ) > 40 ) {
				$errors['driver_phone'] = esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' );
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

				if ( $vehicle_id ) {
					$message = esc_html__( 'Vehicle updated successfully.', 'school-management' );
					$reset   = false;
				} else {
					$message = esc_html__( 'Vehicle added successfully.', 'school-management' );
					$reset   = true;
				}

				// Vehicle data.
				$data = array(
					'vehicle_number' => $vehicle_number,
					'vehicle_model'  => $vehicle_model,
					'driver_name'    => $driver_name,
					'driver_phone'   => $driver_phone,
					'note'           => $note,
				);

				if ( $vehicle_id ) {
					$data['updated_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->update( WLSM_VEHICLES, $data, array( 'ID' => $vehicle_id, 'school_id' => $school_id ) );
				} else {
					$data['created_at'] = current_time( 'Y-m-d H:i:s' );

					$data['school_id'] = $school_id;

					$success = $wpdb->insert( WLSM_VEHICLES, $data );
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

	public static function delete_vehicle() {
		$current_user = WLSM_M_Role::can( 'manage_transport' );

		if ( ! $current_user ) {
			die();
		}
		WLSM_Helper::check_demo();

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$vehicle_id = isset( $_POST['vehicle_id'] ) ? absint( $_POST['vehicle_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-vehicle-' . $vehicle_id ], 'delete-vehicle-' . $vehicle_id ) ) {
				die();
			}

			// Checks if vehicle exists.
			$vehicle = WLSM_M_Staff_Transport::get_vehicle( $school_id, $vehicle_id );

			if ( ! $vehicle ) {
				throw new Exception( esc_html__( 'Transport vehicle not found.', 'school-management' ) );
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

			$success = $wpdb->delete( WLSM_VEHICLES, array( 'ID' => $vehicle_id ) );
			$message = esc_html__( 'Transport vehicle deleted successfully.', 'school-management' );

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

	public static function fetch_routes() {
		$current_user = WLSM_M_Role::can( 'manage_transport' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		global $wpdb;

		$page_url = WLSM_M_Staff_Transport::get_routes_page_url();

		$query = WLSM_M_Staff_Transport::fetch_route_query( $school_id );

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Transport::fetch_route_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(ro.name LIKE "%' . $search_value . '%") OR ' .
				'(ro.fare LIKE "%' . $search_value . '%")';

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'ro.name', 'ro.fare', 'vehicles_count' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY ro.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Transport::fetch_route_query_count( $school_id );

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
					esc_html( $row->name ),
					esc_html( WLSM_Config::get_money_text( $row->fare, $school_id  ) ),
					absint( $row->vehicles_count ),
					'<a class="text-primary" href="' . esc_url( $page_url . "&action=save&id=" . $row->ID ) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-route" data-nonce="' . esc_attr( wp_create_nonce( 'delete-route-' . $row->ID ) ) . '" data-route="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Please Confirm!', 'school-management' ) . '" data-message-content="' . esc_attr__( 'This will delete the transport route.', 'school-management' ) . '" data-cancel="' . esc_attr__( 'Cancel', 'school-management' ) . '" data-submit="' . esc_attr__( 'Confirm', 'school-management' ) . '"><span class="dashicons dashicons-trash"></span></a>'
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

	public static function save_route() {
		$current_user = WLSM_M_Role::can( 'manage_transport' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$route_id = isset( $_POST['route_id'] ) ? absint( $_POST['route_id'] ) : 0;

			if ( $route_id ) {
				if ( ! wp_verify_nonce( $_POST[ 'edit-route-' . $route_id ], 'edit-route-' . $route_id ) ) {
					die();
				}
			} else {
				if ( ! wp_verify_nonce( $_POST['add-route'], 'add-route' ) ) {
					die();
				}
			}

			// Checks if route exists.
			if ( $route_id ) {
				$route = WLSM_M_Staff_Transport::get_route( $school_id, $route_id );

				if ( ! $route ) {
					throw new Exception( esc_html__( 'Route not found.', 'school-management' ) );
				}
			}

			$name     = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
			$fare     = isset( $_POST['fare'] ) ? WLSM_Config::sanitize_money( $_POST['fare'] ) : 0;
			$period     = isset( $_POST['period'] ) ? sanitize_text_field( $_POST['period'] ) : '';
			$vehicles = ( isset( $_POST['vehicles'] ) && is_array( $_POST['vehicles'] ) ) ? $_POST['vehicles'] : array();

			// Start validation.
			$errors = array();

			if ( empty( $name ) ) {
				$errors['name'] = esc_html__( 'Please specify route name.', 'school-management' );
			} elseif ( strlen( $name ) > 100 ) {
				$errors['name'] = esc_html__( 'Maximum length cannot exceed 100 characters.', 'school-management' );
			}

			if ( count( $vehicles ) ) {
				foreach ( $vehicles as $vehicle_id ) {
					$vehicle = WLSM_M_Staff_Transport::get_vehicle( $school_id, $vehicle_id );
					if ( ! $vehicle ) {
						$errors['vehicles[]'] = esc_html__( 'Vehicle not found.', 'school-management' );
						wp_send_json_error( $errors );
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

				if ( $route_id ) {
					$message = esc_html__( 'Transport route updated successfully.', 'school-management' );
					$reset   = false;
				} else {
					$message = esc_html__( 'Transport route added successfully.', 'school-management' );
					$reset   = true;
				}

				// Route data.
				$data = array(
					'name' => $name,
					'fare' => $fare,
					'period' => $period,
				);

				if ( $route_id ) {
					$data['updated_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->update( WLSM_ROUTES, $data, array( 'ID' => $route_id, 'school_id' => $school_id ) );
				} else {
					$data['created_at'] = current_time( 'Y-m-d H:i:s' );

					$data['school_id'] = $school_id;

					$success = $wpdb->insert( WLSM_ROUTES, $data );

					$route_id = $wpdb->insert_id;
				}

				if ( $route_id ) {
					if ( count( $vehicles ) > 0 ) {
						$values                 = array();
						$place_holders          = array();
						$place_holders_vehicles = array();
						foreach ( $vehicles as $vehicle_id ) {
							array_push( $values, $vehicle_id, $route_id );
							array_push( $place_holders, '(%d, %d)' );
							array_push( $place_holders_vehicles, '%d' );
						}

						// Insert route_vehicle records.
						$sql     = 'INSERT IGNORE INTO ' . WLSM_ROUTE_VEHICLE . '(vehicle_id, route_id) VALUES ';
						$sql     .= implode( ', ', $place_holders );
						$success = $wpdb->query( $wpdb->prepare( "$sql ", $values ) );

						// Delete route_vehicle records not in array.
						$sql     = 'DELETE FROM ' . WLSM_ROUTE_VEHICLE . ' WHERE route_id = %d AND vehicle_id NOT IN (' . implode( ', ', $place_holders_vehicles ) . ')';
						array_unshift( $vehicles , $route_id );
						$success = $wpdb->query( $wpdb->prepare( "$sql ", $vehicles ) );
					} else {
						// Delete route_vehicle records for route.
						$success = $wpdb->delete( WLSM_ROUTE_VEHICLE, array( 'route_id' => $route_id ) );
					}
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

	public static function delete_route() {
		$current_user = WLSM_M_Role::can( 'manage_transport' );

		if ( ! $current_user ) {
			die();
		}
		WLSM_Helper::check_demo();

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$route_id = isset( $_POST['route_id'] ) ? absint( $_POST['route_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-route-' . $route_id ], 'delete-route-' . $route_id ) ) {
				die();
			}

			// Checks if route exists.
			$route = WLSM_M_Staff_Transport::get_route( $school_id, $route_id );

			if ( ! $route ) {
				throw new Exception( esc_html__( 'Transport route not found.', 'school-management' ) );
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

			$success = $wpdb->delete( WLSM_ROUTES, array( 'ID' => $route_id ) );
			$message = esc_html__( 'Transport route deleted successfully.', 'school-management' );

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

	public static function get_transport_report() {
		$current_user = WLSM_M_Role::can( 'manage_transport' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		if ( ! wp_verify_nonce( $_POST[ 'nonce' ], 'get-transport-report' ) ) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$class_id   = isset( $_POST['class_id'] ) ? absint( $_POST['class_id'] ) : 0;
			$section_id = isset( $_POST['section_id'] ) ? absint( $_POST['section_id'] ) : 0;

			$route_id   = isset( $_POST['route_id'] ) ? absint( $_POST['route_id'] ) : 0;
			$vehicle_id = isset( $_POST['vehicle_id'] ) ? absint( $_POST['vehicle_id'] ) : 0;

			// Start validation.
			$errors = array();

			if ( ! empty( $class_id ) ) {
				$class_school = WLSM_M_School::get_class_school( $class_id, $school_id );
				if ( ! $class_school ) {
					$errors['class_id'] = esc_html__( 'Class not found.', 'school-management' );
				} else {
					$class_school_id = $class_school->ID;
					if ( ! empty( $section_id ) ) {
						$section = WLSM_M_Staff_Class::fetch_section( $school_id, $section_id, $class_school_id );
						if ( ! $section ) {
							$errors['section_id'] = esc_html__( 'Section not found.', 'school-management' );
						}
					}
				}
			}

			if ( ! empty( $route_id ) ) {
				$route = WLSM_M_Staff_Transport::get_route( $school_id, $route_id );
				if ( ! $route ) {
					$errors['route_id'] = esc_html__( 'Transport route not found.', 'school-management' );
				}
			}

			if ( ! empty( $vehicle_id ) ) {
				$vehicle = WLSM_M_Staff_Transport::get_vehicle( $school_id, $vehicle_id );
				if ( ! $vehicle ) {
					$errors['vehicle_id'] = esc_html__( 'Transport vehicle not found.', 'school-management' );
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
				ob_start();

				$query = 'SELECT sr.ID, sr.name as student_name, sr.enrollment_number, sr.roll_number, sr.father_name, sr.father_phone, c.label as class_label, se.label as section_label, ro.name as route_name, v.vehicle_number FROM ' . WLSM_ROUTE_VEHICLE . ' as rov
				JOIN ' . WLSM_ROUTES . ' as ro ON ro.ID = rov.route_id
				JOIN ' . WLSM_VEHICLES . ' as v ON v.ID = rov.vehicle_id
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.route_vehicle_id = rov.ID
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
				WHERE cs.school_id = %d AND sr.session_id = %d';

				$params = array( $school_id, $session_id );

				if ( $class_id ) {
					array_push( $params, $class_id );
					$query .= ' AND c.ID = %d';

					if ( $section_id ) {
						array_push( $params, $section_id );
						$query .= ' AND se.ID = %d';
					}
				}

				if ( $route_id ) {
					array_push( $params, $route_id );
					$query .= ' AND rov.route_id = %d';
				}

				if ( $vehicle_id ) {
					array_push( $params, $vehicle_id );
					$query .= ' AND rov.vehicle_id = %d';
				}

				$query .= ' GROUP BY sr.ID ORDER BY c.label, se.label, sr.roll_number';

				$students = $wpdb->get_results( $wpdb->prepare( $query, $params ) );

				if ( count( $students ) ) {
					?>
					<table class="table table-hover table-bordered" id="wlsm-students-transport-table">
						<thead>
							<tr class="text-white bg-primary">
								<th scope="col" class="text-nowrap"><?php esc_html_e( 'Route Name', 'school-management' ); ?></th>
								<th scope="col" class="text-nowrap"><?php esc_html_e( 'Vehicle Number', 'school-management' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Student Name', 'school-management' ); ?></th>
								<th scope="col" class="text-nowrap"><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Class', 'school-management' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Section', 'school-management' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Roll Number', 'school-management' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Father\'s Name', 'school-management' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Father\'s Phone', 'school-management' ); ?></th>
								<th scope="col"><?php esc_html_e( 'ID Card', 'school-management' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $students as $row ) { ?>
							<tr>
								<td><?php echo esc_html( $row->route_name ); ?></td>
								<td><?php echo esc_html( $row->vehicle_number ); ?></td>
								<td><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $row->student_name ) ); ?></td>
								<td><?php echo esc_html( $row->enrollment_number ); ?></td>
								<td><?php echo esc_html( WLSM_M_Class::get_label_text( $row->class_label ) ); ?></td>
								<td><?php echo esc_html( WLSM_M_Staff_Class::get_section_label_text( $row->section_label ) ); ?></td>
								<td><?php echo esc_html( WLSM_M_Staff_Class::get_roll_no_text( $row->roll_number ) ); ?></td>
								<td><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $row->father_name ) ); ?></td>
								<td><?php echo esc_html( WLSM_M_Staff_Class::get_phone_text( $row->father_phone ) ); ?></td>
								<td>
									<a class="text-success wlsm-print-id-card" data-nonce="<?php echo esc_attr( wp_create_nonce( 'print-id-card-' . $row->ID ) ); ?>" data-id-card="<?php echo esc_attr( $row->ID ); ?>" href="#" data-message-title="<?php esc_attr_e( 'Print ID Card', 'school-management' ); ?>" data-close="<?php esc_attr_e( 'Close', 'school-management' ); ?>"><i class="fas fa-print"></i></a>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php
				} else {
				?>
				<div class="alert alert-warning wlsm-font-bold">
					<i class="fas fa-exclamation-triangle"></i>
					<?php esc_html_e( 'No student found.', 'school-management' ); ?>
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

	public static function fetch_hostels() {
		$current_user = WLSM_M_Role::can( 'manage_hostel' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		global $wpdb;

		$page_url = WLSM_M_Staff_Transport::get_hostels_page_url();

		$query = WLSM_M_Staff_Transport::fetch_hostel_query( $school_id );

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Transport::fetch_hostel_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(h.hostel_name LIKE "%' . $search_value . '%") OR ' .
				'(h.hostel_type LIKE "%' . $search_value . '%") OR ' .
				'(h.hostel_address LIKE "%' . $search_value . '%") OR ' .
				'(h.hostel_intake LIKE "%' . $search_value . '%")';

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'h.hostel_name', 'h.hostel_type', 'h.hostel_address', 'h.hostel_intake' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY h.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Transport::fetch_hostel_query_count( $school_id );

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

				$hostel_id = $row->ID;

				// Get vehicle incharge.
				$admins = WLSM_M_Staff_Transport::get_vehicle_incharge( $school_id, $hostel_id );

				if ( count( $admins ) ) {
					$hostel_incharge = '';
					foreach ( $admins as $admin ) {
						if ( WLSM_M_Role::get_admin_key() === $admin->role ) {
							$staff_page_url = WLSM_M_Staff_General::get_admins_page_url();
						} else {
							$staff_page_url = WLSM_M_Staff_General::get_employees_page_url();
						}

						$hostel_incharge .= '- <span class="wlsm-font-bold"><a class="text-dark" target="_blank" href="' . esc_url( $staff_page_url . '&action=save&id=' . $admin->ID ) . '">' . esc_html( stripcslashes( $admin->name ) );

						if ( $admin->phone ) {
							$hostel_incharge .= ' (' . esc_html( $admin->phone ) . ')';
						}

						if ( $admin->username ) {
							$hostel_incharge .= ' (' . esc_html( $admin->username ) . ')';
						}

						$hostel_incharge .= '</a></span><br>';
					}
				} else {
					$hostel_incharge = '-';
				}

				// Table columns.
				$data[] = array(
					esc_html( $row->ID ),
					esc_html( $row->hostel_name ),
					esc_html( $row->hostel_type ),
					esc_html( $row->room_count ),
					esc_html( ( $row->hostel_address ) ),
					esc_html( ( $row->hostel_intake ) ),
					'<a class="text-primary" href="' . esc_url( $page_url . "&action=save&id=" . $row->ID ) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-hostel" data-nonce="' . esc_attr( wp_create_nonce( 'delete-hostel-' . $row->ID ) ) . '" data-hostel="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Please Confirm!', 'school-management' ) . '" data-message-content="' . esc_attr__( 'This will delete the hostel.', 'school-management' ) . '" data-cancel="' . esc_attr__( 'Cancel', 'school-management' ) . '" data-submit="' . esc_attr__( 'Confirm', 'school-management' ) . '"><span class="dashicons dashicons-trash"></span></a>'
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

	public static function fetch_rooms() {
		$current_user = WLSM_M_Role::can( 'manage_hostel' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		global $wpdb;

		$page_url = WLSM_M_Staff_Transport::get_rooms_page_url();

		$query = WLSM_M_Staff_Transport::fetch_room_query( $school_id );

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Staff_Transport::fetch_hostel_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(h.room_name LIKE "%' . $search_value . '%") OR ' .
				'(h.number_of_bed LIKE "%' . $search_value . '%") OR ' .
				'(h.hostel LIKE "%' . $search_value . '%") OR ' .
				'(h.note LIKE "%' . $search_value . '%")';

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'h.room_name', 'h.number_of_bed', 'h.hostel', 'h.note' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY h.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Staff_Transport::fetch_hostel_query_count( $school_id );

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

				$room_id = $row->ID;

				// Get vehicle incharge.
				$admins = WLSM_M_Staff_Transport::get_vehicle_incharge( $school_id, $room_id );

				if ( count( $admins ) ) {
					$hostel_incharge = '';
					foreach ( $admins as $admin ) {
						if ( WLSM_M_Role::get_admin_key() === $admin->role ) {
							$staff_page_url = WLSM_M_Staff_General::get_admins_page_url();
						} else {
							$staff_page_url = WLSM_M_Staff_General::get_employees_page_url();
						}

						$hostel_incharge .= '- <span class="wlsm-font-bold"><a class="text-dark" target="_blank" href="' . esc_url( $staff_page_url . '&action=save&id=' . $admin->ID ) . '">' . esc_html( stripcslashes( $admin->name ) );

						if ( $admin->phone ) {
							$hostel_incharge .= ' (' . esc_html( $admin->phone ) . ')';
						}

						if ( $admin->username ) {
							$hostel_incharge .= ' (' . esc_html( $admin->username ) . ')';
						}

						$hostel_incharge .= '</a></span><br>';
					}
				} else {
					$hostel_incharge = '-';
				}

				// Table columns.
				$data[] = array(
					esc_html( $row->ID ),
					esc_html( $row->room_name ),
					esc_html( $row->number_of_beds ),
					// esc_html( ( $row->hostel_name ) ),
					// esc_html( ( $row->note ) ),
					'<a class="text-primary" href="' . esc_url( $page_url . "&action=save&id=" . $row->ID ) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-room" data-nonce="' . esc_attr( wp_create_nonce( 'delete-room-' . $row->ID ) ) . '" data-room="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Please Confirm!', 'school-management' ) . '" data-message-content="' . esc_attr__( 'This will delete the room.', 'school-management' ) . '" data-cancel="' . esc_attr__( 'Cancel', 'school-management' ) . '" data-submit="' . esc_attr__( 'Confirm', 'school-management' ) . '"><span class="dashicons dashicons-trash"></span></a>'
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

	public static function save_room() {

		$current_user = WLSM_M_Role::can( 'manage_hostel' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$room_id = isset( $_POST['room_id'] ) ? absint( $_POST['room_id'] ) : 0;

			if ( $room_id ) {
				if ( ! wp_verify_nonce( $_POST[ 'edit-room-' . $room_id ], 'edit-room-' . $room_id ) ) {
					die();
				}
			} else {
				if ( ! wp_verify_nonce( $_POST['add-room'], 'add-room' ) ) {
					die();
				}
			}

			// Checks if room exists.
			if ( $room_id ) {
				$room = WLSM_M_Staff_Transport::get_room( $school_id, $room_id );

				if ( ! $room ) {
					throw new Exception( esc_html__( 'room not found.', 'school-management' ) );
				}
			}

			$room_name       = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
			$number_of_rooms = isset( $_POST['bed_number'] ) ? sanitize_text_field( $_POST['bed_number'] ) : '';
			$hostel          = isset( $_POST['hostel'] ) ? sanitize_text_field( $_POST['hostel'] ) : '';
			$note            = isset( $_POST['note'] ) ? sanitize_text_field( $_POST['note'] ) : '';

			// Start validation.
			$errors = array();

			if ( empty( $room_name ) ) {
				$errors['room_name'] = esc_html__( 'Please specify room name.', 'school-management' );
			} elseif ( strlen( $room_name ) > 60 ) {
				$errors['room_name'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
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

				if ( $room_id ) {
					$message = esc_html__( 'Room updated successfully.', 'school-management' );
					$reset   = false;
				} else {
					$message = esc_html__( 'Room added successfully.', 'school-management' );
					$reset   = true;
				}

				// Room data.
				$data = array(
					'room_name'      => $room_name,
					'number_of_beds' => $number_of_rooms,
					'hostel_id'      => $hostel,
					'note'           => $note,
				);

				if ( $room_id ) {
					$data['updated_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->update( WLSM_ROOMS, $data, array( 'ID' => $room_id ) );
				} else {
					$data['created_at'] = current_time( 'Y-m-d H:i:s' );

					// $data['school_id'] = $school_id;

					$success = $wpdb->insert( WLSM_ROOMS, $data );
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				if (isset($wlsm_pan_card_id_to_delete)) {
					wp_delete_attachment($wlsm_pan_card_id_to_delete, true);
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

	public static function save_hostel() {

		$current_user = WLSM_M_Role::can( 'manage_hostel' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$hostel_id = isset( $_POST['hostel_id'] ) ? absint( $_POST['hostel_id'] ) : 0;

			if ( $hostel_id ) {
				if ( ! wp_verify_nonce( $_POST[ 'edit-hostel-' . $hostel_id ], 'edit-hostel-' . $hostel_id ) ) {
					die();
				}
			} else {
				if ( ! wp_verify_nonce( $_POST['add-hostel'], 'add-hostel' ) ) {
					die();
				}
			}

			// Checks if hostel exists.
			if ( $hostel_id ) {
				$hostel = WLSM_M_Staff_Transport::get_hostel( $school_id, $hostel_id );

				if ( ! $hostel ) {
					throw new Exception( esc_html__( 'hostel not found.', 'school-management' ) );
				}
			}

			$hostel_name    = isset( $_POST['hostel_name'] ) ? sanitize_text_field( $_POST['hostel_name'] ) : '';
			$hostel_type    = isset( $_POST['hostel_type'] ) ? sanitize_text_field( $_POST['hostel_type'] ) : '';
			$hostel_address = isset( $_POST['address'] ) ? sanitize_text_field( $_POST['address'] ) : '';
			$intake         = isset( $_POST['intake'] ) ? sanitize_text_field( $_POST['intake'] ) : '';
			$fees         = isset( $_POST['fees'] ) ? sanitize_text_field( $_POST['fees'] ) : '';

			// Start validation.
			$errors = array();

			if ( empty( $hostel_name ) ) {
				$errors['hostel_name'] = esc_html__( 'Please specify hostel name.', 'school-management' );
			} elseif ( strlen( $hostel_name ) > 60 ) {
				$errors['hostel_name'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
			}

			if ( strlen( $hostel_type ) > 60 ) {
				$errors['hostel_type'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
			}

			if ( strlen( $hostel_address ) > 60 ) {
				$errors['hostel_address'] = esc_html__( 'Maximum length cannot exceed 60 characters.', 'school-management' );
			}

			if ( strlen( $intake ) > 40 ) {
				$errors['intake'] = esc_html__( 'Maximum length cannot exceed 40 characters.', 'school-management' );
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

				if ( $hostel_id ) {
					$message = esc_html__( 'Hostel updated successfully.', 'school-management' );
					$reset   = false;
				} else {
					$message = esc_html__( 'Hostel added successfully.', 'school-management' );
					$reset   = true;
				}

				// Hostel data.
				$data = array(
					'hostel_name'    => $hostel_name,
					'hostel_type'    => $hostel_type,
					'hostel_address' => $hostel_address,
					'hostel_intake'  => $intake,
					'fees'    => $fees,
				);

				if ( $hostel_id ) {
					$data['updated_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->update( WLSM_HOSTELS, $data, array( 'ID' => $hostel_id, 'school_id' => $school_id ) );
				} else {
					$data['created_at'] = current_time( 'Y-m-d H:i:s' );

					$data['school_id'] = $school_id;

					$success = $wpdb->insert( WLSM_HOSTELS, $data );
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				if (isset($wlsm_pan_card_id_to_delete)) {
					wp_delete_attachment($wlsm_pan_card_id_to_delete, true);
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

	public static function delete_hostel() {
		$current_user = WLSM_M_Role::can( 'manage_hostel' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$hostel_id = isset( $_POST['hostel_id'] ) ? absint( $_POST['hostel_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-hostel-' . $hostel_id ], 'delete-hostel-' . $hostel_id ) ) {
				die();
			}

			// Checks if hostel exists.
			$hostel = WLSM_M_Staff_Transport::get_hostel( $school_id, $hostel_id );

			if ( ! $hostel ) {
				throw new Exception( esc_html__( 'Hostel not found.', 'school-management' ) );
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

			$success = $wpdb->delete( WLSM_HOSTELS, array( 'ID' => $hostel_id ) );
			$message = esc_html__( 'Hostel deleted successfully.', 'school-management' );

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

	public static function delete_room() {
		$current_user = WLSM_M_Role::can( 'manage_hostel' );

		if ( ! $current_user ) {
			die();
		}

		$school_id = $current_user['school']['id'];

		try {
			ob_start();
			global $wpdb;

			$room_id = isset( $_POST['room_id'] ) ? absint( $_POST['room_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-room-' . $room_id ], 'delete-room-' . $room_id ) ) {
				die();
			}

			// Checks if room exists.
			$room = WLSM_M_Staff_Transport::get_room( $school_id, $room_id );

			if ( ! $room ) {
				throw new Exception( esc_html__( 'room not found.', 'school-management' ) );
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

			$success = $wpdb->delete( WLSM_ROOMS, array( 'ID' => $room_id ) );
			$message = esc_html__( 'room deleted successfully.', 'school-management' );

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
