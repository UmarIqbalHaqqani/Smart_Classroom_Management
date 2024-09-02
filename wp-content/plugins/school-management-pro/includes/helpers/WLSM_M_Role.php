<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Config.php';

class WLSM_M_Role {
	private static $admin    = 'admin';
	private static $employee = 'employee';

	public static function get_user_info( $user_id = '' ) {
		if ( $data = wp_cache_get( 'wlsm_user_info' ) ) {
			return $data;
		}

		global $wpdb;

		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$current_school_id = get_user_meta( $user_id, 'wlsm_school_id', true );

		$schools = array();

		$staff_in_school = false;

		$staff = $wpdb->get_results(
			$wpdb->prepare( 'SELECT sf.role, sf.permissions, sf.school_id, s.label as school_name, a.section_id, s.is_active FROM ' . WLSM_STAFF . ' as sf
			JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = sf.school_id
			LEFT OUTER JOIN ' . WLSM_ADMINS . ' as a ON a.staff_id = sf.ID
			WHERE sf.user_id = %d', $user_id )
		);

		if ( count( $staff ) ) {
			foreach ( $staff as $user ) {
				if ( $user->school_id === $current_school_id ) {
					$staff_in_school = true;

					$school_id   = $user->school_id;
					$role        = $user->role;
					$permissions = $user->permissions ? unserialize( $user->permissions ) : array();
					$school_name = $user->school_name;
					$section_id  = $user->section_id;
					$is_active  = $user->is_active;
				}

				array_push( $schools, array( 'id' => $user->school_id, 'name' => $user->school_name ) );
			}
		}

		$data = array(
			'schools_assigned' => $schools,
		);

		if ( $staff_in_school ) {
			if ( self::get_admin_key() == $role ) {
				$permissions = array_keys( self::get_permissions() );
			}

			$data['current_school'] = array(
				'id'          => $school_id,
				'role'        => $role,
				'permissions' => $permissions,
				'name'        => $school_name,
				'is_active'   => $is_active,
				'section_id'  => $section_id
			);
		} else {
			$data['current_school'] = false;

			if ( 1 === count( $staff ) ) {
				update_user_meta( $user_id, 'wlsm_school_id', $staff[0]->school_id );
			}
		}

		wp_cache_add( 'wlsm_user_info', $data );

		return $data;
	}

	// Restrict staff to section.
	public static function restrict_to_section( $current_school ) {
		$role       = $current_school['role'];
		$section_id = $current_school['section_id'];

		$restrict_to_section = false;
		if ( self::get_employee_key() === $role ) {
			$restrict_to_section = $section_id;
		}

		return $restrict_to_section;
	}

	// Get if user is staff.
	public static function get_user_admin( $school_id, $user_id = '' ) {
		global $wpdb;

		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$admin = $wpdb->get_row(
			$wpdb->prepare( 'SELECT a.ID FROM ' . WLSM_ADMINS . ' as a
			JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
			WHERE sf.school_id = %d AND sf.user_id = %d', $school_id, $user_id )
		);

		return $admin;
	}

	public static function get_roles() {
		return array(
			self::$admin    => esc_html__( 'Admin', 'school-management' ),
			self::$employee => esc_html__( 'Staff', 'school-management' ),
		);
	}

	public static function get_staff_roles( $school_id ) {
		global $wpdb;

		$staff_roles = $wpdb->get_results( $wpdb->prepare( 'SELECT r.ID, r.name FROM ' . WLSM_ROLES . ' as r
		WHERE r.school_id = %d', $school_id ), OBJECT_K );

		return $staff_roles;
	}

	public static function get_role_text( $role ) {
		if ( array_key_exists( $role, self::get_roles() ) ) {
			return self::get_roles()[ $role ];
		}

		return '';
	}

	public static function get_admin_key() {
		return self::$admin;
	}

	public static function get_employee_key() {
		return self::$employee;
	}

	public static function get_permissions() {
		return array(
			'manage_inquiries'            => esc_html__( 'Manage Inquiries', 'school-management' ),
			'manage_admissions'           => esc_html__( 'Manage Admissions', 'school-management' ),
			'manage_students'             => esc_html__( 'Manage Students', 'school-management' ),
			'delete_students'             => esc_html__( 'Delete Students', 'school-management' ),
			'manage_admins'               => esc_html__( 'Add/Remove Admins', 'school-management' ),
			'manage_roles'                => esc_html__( 'Manage Roles', 'school-management' ),
			'manage_employees'            => esc_html__( 'Add/Remove Staff', 'school-management' ),
			'manage_promote'              => esc_html__( 'Student Promotion', 'school-management' ),
			'manage_transfer_student'     => esc_html__( 'Transfer Student', 'school-management' ),
			'manage_certificates'          => esc_html__( 'Manage Certificates', 'school-management' ),
			'manage_classes'              => esc_html__( 'Manage Classes & Sections', 'school-management' ),
			'delete_sections'             => esc_html__( 'Delete Class Sections', 'school-management' ),
			'manage_subjects'             => esc_html__( 'Manage Subjects', 'school-management' ),
			'manage_timetable'            => esc_html__( 'Manage Timetable', 'school-management' ),
			'view_timetable'              => esc_html__( 'View Timetable', 'school-management' ),
			'manage_attendance'           => esc_html__( 'Manage Student Attendance', 'school-management' ),
			'manage_staff_attendance'     => esc_html__( 'Manage Staff Attendance', 'school-management' ),
			'manage_student_leaves'       => esc_html__( 'Manage Student Leaves', 'school-management' ),
			'manage_staff_leaves'         => esc_html__( 'Manage Staff Leaves', 'school-management' ),
			'manage_study_materials'      => esc_html__( 'Manage Study Materials', 'school-management' ),
			'manage_homework'             => esc_html__( 'Manage Homework', 'school-management' ),
			'manage_live_classes'         => esc_html__( 'Manage Live Classes', 'school-management' ),
			'manage_library'              => esc_html__( 'Manage Library', 'school-management' ),
			'manage_transport'            => esc_html__( 'Manage Transport', 'school-management' ),
			'manage_notices'              => esc_html__( 'Manage Noticeboard', 'school-management' ),
			'manage_events'               => esc_html__( 'Manage Events', 'school-management' ),
			'manage_exams'                => esc_html__( 'Manage Exams', 'school-management' ),
			'manage_expenses'             => esc_html__( 'Manage Expenses', 'school-management' ),
			'manage_income'               => esc_html__( 'Manage Income', 'school-management' ),
			'manage_invoices'             => esc_html__( 'Manage Invoices', 'school-management' ),
			'delete_invoices'             => esc_html__( 'Delete Invoices', 'school-management' ),
			'edit_invoices'               => esc_html__( 'Edit Invoices', 'school-management' ),
			'delete_payments'             => esc_html__( 'Delete Payments', 'school-management' ),
			'stats_payments'              => esc_html__( 'View Stats - Payments', 'school-management' ),
			'stats_amount_fees_structure' => esc_html__( 'View Stats - Amount By Fees Structure', 'school-management' ),
			'stats_expense'               => esc_html__( 'View Stats - Expense', 'school-management' ),
			'stats_income'                => esc_html__( 'View Stats - Income', 'school-management' ),
			'manage_fees'                 => esc_html__( 'Manage Fee Types', 'school-management' ),
			'send_notifications'           => esc_html__( 'Send Notifications', 'school-management' ),
			'manage_settings'             => esc_html__( 'Manage Settings', 'school-management' ),
			'manage_logs'                 => esc_html__( 'Manage Logs', 'school-management' ),
			'manage_hostel'               => esc_html__( 'Manage Hostel', 'school-management' ),
			'manage_activities'           => esc_html__( 'Manage Activities', 'school-management' ),
			'manage_lessons'               => esc_html__( 'Manage Lessons', 'school-management' ),
			'assigned_class'              => esc_html__( 'Only Manage Assigned Class', 'school-management' ),
		);
	}

	public static function check_permission( $permissions_to_check, $user_permissions ) {
		return ! empty ( array_intersect( $permissions_to_check, $user_permissions ) );
	}

	public static function get_role_permissions( $role, $permissions ) {
		$permissions_keys = array_keys( self::get_permissions() );

		if ( self::get_admin_key() == $role ) {
			$permissions = $permissions_keys;
		} else {
			if ( is_serialized( $permissions ) ) {
				$permissions = unserialize( $permissions );
			}
			return array_intersect( $permissions, $permissions_keys );
		}

		return $permissions;
	}

	public static function can( $permission ) {
		$user_info      = self::get_user_info();
		$current_school = $user_info['current_school'];

		if ( ! $current_school ) {
			return false;
		}

		$role = $current_school['role'];
		if ( in_array( $role, array_keys( self::get_roles() ) ) ) {
			$permissions = $current_school['permissions'];
			if ( ! is_array( $permission ) ) {
				$permission = array( $permission );
			}
			if ( self::check_permission( $permission, $permissions ) ) {
				$current_session = WLSM_Config::current_session();
				return array(
					'school'  => $current_school,
					'session' => $current_session,
				);
			}
		}

		return false;
	}

	public static function get_permission_text( $permission ) {
		if ( isset( self::get_permissions()[ $permission ] ) ) {
			return self::get_permissions()[ $permission ];
		}

		return '';
	}

	public static function get_admin_text() {
		return self::get_roles()[ self::$admin ];
	}

	public static function get_employee_text() {
		return self::get_roles()[ self::$employee ];
	}
}
