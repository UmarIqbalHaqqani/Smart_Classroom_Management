<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Config.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Helper.php';

class WLSM_Menu {

	// Create menu pages.
	public static function create_menu() {
		if ( WLSM_Helper::lm_valid() ) {
			$school_management = add_menu_page( esc_html__( 'School Management', 'school-management' ), esc_html__( 'School Management', 'school-management' ), WLSM_ADMIN_CAPABILITY, WLSM_MENU_SM, array( 'WLSM_Menu', 'school_management' ), 'dashicons-welcome-learn-more', 27 );
			add_action( 'admin_print_styles-' . $school_management, array( 'WLSM_Menu', 'menu_page_assets' ) );

			$dashboard_submenu = add_submenu_page( WLSM_MENU_SM, esc_html__( 'Dashboard', 'school-management' ), esc_html__( 'Dashboard', 'school-management' ), WLSM_ADMIN_CAPABILITY, WLSM_MENU_SM, array( 'WLSM_Menu', 'school_management' ) );
			add_action( 'admin_print_styles-' . $dashboard_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

			$schools_submenu = add_submenu_page( WLSM_MENU_SM, esc_html__( 'Schools', 'school-management' ), esc_html__( 'Schools', 'school-management' ), WLSM_ADMIN_CAPABILITY, WLSM_MENU_SCHOOLS, array( 'WLSM_Menu', 'schools' ) );
			add_action( 'admin_print_styles-' . $schools_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

			$classes_submenu = add_submenu_page( WLSM_MENU_SM, esc_html__( 'Classes', 'school-management' ), esc_html__( 'Classes', 'school-management' ), WLSM_ADMIN_CAPABILITY, WLSM_MENU_CLASSES, array( 'WLSM_Menu', 'classes' ) );
			add_action( 'admin_print_styles-' . $classes_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

			$category_submenu = add_submenu_page( WLSM_MENU_SM, esc_html__( 'Category', 'school-management' ), esc_html__( 'Category', 'school-management' ), WLSM_ADMIN_CAPABILITY, WLSM_MENU_CATEGORY, array( 'WLSM_Menu', 'category' ) );
			add_action( 'admin_print_styles-' . $category_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

			$sessions_submenu = add_submenu_page( WLSM_MENU_SM, esc_html__( 'Sessions', 'school-management' ), esc_html__( 'Sessions', 'school-management' ), WLSM_ADMIN_CAPABILITY, WLSM_MENU_SESSIONS, array( 'WLSM_Menu', 'sessions' ) );
			add_action( 'admin_print_styles-' . $sessions_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

			$settings_submenu = add_submenu_page( WLSM_MENU_SM, esc_html__( 'Settings', 'school-management' ), esc_html__( 'Settings', 'school-management' ), WLSM_ADMIN_CAPABILITY, WLSM_MENU_SETTINGS, array( 'WLSM_Menu', 'settings' ) );
			add_action( 'admin_print_styles-' . $settings_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

			$school_management_submenu = add_submenu_page( WLSM_MENU_SM, esc_html__( 'License', 'school-management' ), esc_html__( 'License', 'school-management' ), WLSM_ADMIN_CAPABILITY, 'school-management-license', array( 'WLSM_Menu', 'admin_menu' ) );
			add_action( 'admin_print_styles-' . $school_management_submenu, array( 'WLSM_Menu', 'admin_menu_assets' ) );

			require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Role.php';

			$user_info = WLSM_M_Role::get_user_info();

			if ( ! current_user_can( WLSM_ADMIN_CAPABILITY ) && count( $user_info['schools_assigned'] ) > 1 ) {
				$schools_assigned = add_menu_page( esc_html__( 'School Management', 'school-management' ), esc_html__( 'School Management', 'school-management' ), 'read', WLSM_MENU_SCHOOLS_ASSIGNED, array( 'WLSM_Menu', 'schools_assigned' ), 'dashicons-welcome-learn-more', 27 );
				add_action( 'admin_print_styles-' . $schools_assigned, array( 'WLSM_Menu', 'menu_page_assets' ) );

				$schools_assigned_submenu = add_submenu_page( WLSM_MENU_SCHOOLS_ASSIGNED, esc_html__( 'Dashboard', 'school-management' ), esc_html__( 'Dashboard', 'school-management' ), 'read', WLSM_MENU_SCHOOLS_ASSIGNED, array( 'WLSM_Menu', 'schools_assigned' ) );
				add_action( 'admin_print_styles-' . $schools_assigned_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
			}

			if ( $current_school = $user_info['current_school'] ) {
				if ( $current_school['is_active'] === '1' ) {
					$role = $current_school['role'];

					if ( in_array( $role, array_keys( WLSM_M_Role::get_roles() ) ) ) {

						$permissions = $current_school['permissions'];

						// School - Menu.
						$school_staff_school_menu = add_menu_page( esc_html__( 'School', 'school-management' ), esc_html__( 'SM School', 'school-management' ), 'read', WLSM_MENU_STAFF_SCHOOL, array( 'WLSM_Menu', 'school_staff_dashboard' ), 'dashicons-building', 31 );
						add_action( 'admin_print_styles-' . $school_staff_school_menu, array( 'WLSM_Menu', 'menu_page_assets' ) );

						// School - Dashboard.
						$school_staff_dashboard_submenu = add_submenu_page( WLSM_MENU_STAFF_SCHOOL, esc_html__( 'Dashboard', 'school-management' ), esc_html__( 'Dashboard', 'school-management' ), 'read', WLSM_MENU_STAFF_SCHOOL, array( 'WLSM_Menu', 'school_staff_dashboard' ) );
						add_action( 'admin_print_styles-' . $school_staff_dashboard_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

						// School - Inquiries.
						if ( WLSM_M_Role::check_permission( array( 'manage_inquiries' ), $permissions ) ) {
							$school_staff_inquiries_submenu = add_submenu_page( WLSM_MENU_STAFF_SCHOOL, esc_html__( 'Inquiries', 'school-management' ), esc_html__( 'Inquiries', 'school-management' ), 'read', WLSM_MENU_STAFF_INQUIRIES, array( 'WLSM_Menu', 'school_staff_inquiries' ) );
							add_action( 'admin_print_styles-' . $school_staff_inquiries_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
						}

						// School - Settings.
						if ( WLSM_M_Role::check_permission( array( 'manage_settings' ), $permissions ) ) {
							$school_staff_settings_submenu = add_submenu_page( WLSM_MENU_STAFF_SCHOOL, esc_html__( 'Settings', 'school-management' ), esc_html__( 'Settings', 'school-management' ), 'read', WLSM_MENU_STAFF_SETTINGS, array( 'WLSM_Menu', 'school_staff_settings' ) );
							add_action( 'admin_print_styles-' . $school_staff_settings_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
						}

						// School - Logs.
						if ( WLSM_M_Role::check_permission( array( 'manage_logs' ), $permissions ) ) {
							$school_staff_logs_submenu = add_submenu_page( WLSM_MENU_STAFF_SCHOOL, esc_html__( 'Logs', 'school-management' ), esc_html__( 'Logs', 'school-management' ), 'read', WLSM_MENU_STAFF_LOGS, array( 'WLSM_Menu', 'school_staff_logs' ) );
							add_action( 'admin_print_styles-' . $school_staff_logs_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
						}

						if ( WLSM_M_Role::get_employee_key() === $role ) {
							// School - Staff Leave Request.
							$school_staff_leave_request_submenu = add_submenu_page( WLSM_MENU_STAFF_SCHOOL, esc_html__( 'Leave Request', 'school-management' ), esc_html__( 'Leave Request', 'school-management' ), 'read', WLSM_MENU_STAFF_LEAVE_REQUEST, array( 'WLSM_Menu', 'school_staff_leave_request' ) );
							add_action( 'admin_print_styles-' . $school_staff_leave_request_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

							// School - Staff Live Classes.
							$school_staff_assigned_meetings_submenu = add_submenu_page( WLSM_MENU_STAFF_SCHOOL, esc_html__( 'Live Classes', 'school-management' ), esc_html__( 'Live Classes', 'school-management' ), 'read', WLSM_MENU_STAFF_ASSIGNED_MEETINGS, array( 'WLSM_Menu', 'school_staff_assigned_meetings' ) );
							add_action( 'admin_print_styles-' . $school_staff_assigned_meetings_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
						}

						// Academic - Group.
						if ( WLSM_M_Role::check_permission( array( 'manage_classes', 'manage_subjects', 'manage_timetable', 'view_timetable', 'manage_attendance', 'manage_study_materials', 'manage_homework', 'manage_notices', 'manage_student_leaves', 'manage_events', 'manage_live_classes' ), $permissions ) ) {

							$school_staff_group_academic_menu = add_menu_page( esc_html__( 'Academic', 'school-management' ), esc_html__( 'SM Academic', 'school-management' ), 'read', WLSM_MENU_STAFF_ACADEMIC, array( 'WLSM_Menu', 'school_staff_group_academic' ), 'dashicons-welcome-learn-more', 31 );
							add_action( 'admin_print_styles-' . $school_staff_group_academic_menu, array( 'WLSM_Menu', 'menu_page_assets' ) );

							$school_staff_group_academic_menu = add_submenu_page( WLSM_MENU_STAFF_ACADEMIC, esc_html__( 'Academic', 'school-management' ), esc_html__( 'Dashboard', 'school-management' ), 'read', WLSM_MENU_STAFF_ACADEMIC, array( 'WLSM_Menu', 'school_staff_group_academic' ) );
							add_action( 'admin_print_styles-' . $school_staff_group_academic_menu, array( 'WLSM_Menu', 'menu_page_assets' ) );

							if ( WLSM_M_Role::check_permission( array( 'manage_classes' ), $permissions ) ) {
								// Class - Classes & Sections.
								$school_staff_classes_submenu = add_submenu_page( WLSM_MENU_STAFF_ACADEMIC, esc_html__( 'Sections', 'school-management' ), esc_html__( 'Manage Class Sections', 'school-management' ), 'read', WLSM_MENU_STAFF_CLASSES, array( 'WLSM_Menu', 'school_staff_classes' ) );
								add_action( 'admin_print_styles-' . $school_staff_classes_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							$school_staff_medium_submenu = add_submenu_page( WLSM_MENU_STAFF_ACADEMIC, esc_html__( 'Medium', 'school-management' ), esc_html__( 'Manage Medium', 'school-management' ), 'read', WLSM_MENU_STAFF_MEDIUM, array( 'WLSM_Menu', 'school_staff_medium' ) );
								add_action( 'admin_print_styles-' . $school_staff_medium_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

							$school_staff_student_type_submenu = add_submenu_page( WLSM_MENU_STAFF_ACADEMIC, esc_html__( 'Student type', 'school-management' ), esc_html__( 'Manage Student type', 'school-management' ), 'read', WLSM_MENU_STAFF_STUDENT_TYPE, array( 'WLSM_Menu', 'school_staff_student_type' ) );
							add_action( 'admin_print_styles-' . $school_staff_student_type_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

							if ( WLSM_M_Role::check_permission( array( 'manage_subjects' ), $permissions ) ) {
								// Class - Subjects.
								$school_staff_subjects_submenu = add_submenu_page( WLSM_MENU_STAFF_ACADEMIC, esc_html__( 'Subjects', 'school-management' ), esc_html__( 'Subjects', 'school-management' ), 'read', WLSM_MENU_STAFF_SUBJECTS, array( 'WLSM_Menu', 'school_staff_subjects' ) );
								add_action( 'admin_print_styles-' . $school_staff_subjects_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_timetable', 'view_timetable' ), $permissions ) ) {
								// Class - Timetable.
								$school_staff_timetable_submenu = add_submenu_page( WLSM_MENU_STAFF_ACADEMIC, esc_html__( 'Class Timetable', 'school-management' ), esc_html__( 'Class Timetable', 'school-management' ), 'read', WLSM_MENU_STAFF_TIMETABLE, array( 'WLSM_Menu', 'school_staff_timetable' ) );
								add_action( 'admin_print_styles-' . $school_staff_timetable_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( ! current_user_can( 'administrator' ) ) {
								if ( WLSM_M_Role::check_permission( array( 'manage_timetable', 'view_timetable' ), $permissions ) ) {
									// Class - Timetable.
									$school_staff_timetable_submenu = add_submenu_page( WLSM_MENU_STAFF_ACADEMIC, esc_html__( 'Staff Time Table', 'school-management' ), esc_html__( 'Staff Time Table', 'school-management' ), 'read', WLSM_MENU_STAFF_MEMBER_TIMETABLE, array( 'WLSM_Menu', 'school_staff_member_timetable' ) );
									add_action( 'admin_print_styles-' . $school_staff_timetable_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
								}
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_attendance' ), $permissions ) ) {
								// Class - Attendance.
								$school_staff_attendance_submenu = add_submenu_page( WLSM_MENU_STAFF_ACADEMIC, esc_html__( 'Attendance', 'school-management' ), esc_html__( 'Attendance', 'school-management' ), 'read', WLSM_MENU_STAFF_ATTENDANCE, array( 'WLSM_Menu', 'school_staff_attendance' ) );
								add_action( 'admin_print_styles-' . $school_staff_attendance_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_student_leaves' ), $permissions ) ) {
								// Class - Student Leaves.
								$school_staff_manage_student_leaves_submenu = add_submenu_page( WLSM_MENU_STAFF_ACADEMIC, esc_html__( 'Student Leaves', 'school-management' ), esc_html__( 'Student Leaves', 'school-management' ), 'read', WLSM_MENU_STAFF_STUDENT_LEAVES, array( 'WLSM_Menu', 'school_staff_student_leaves' ) );
								add_action( 'admin_print_styles-' . $school_staff_manage_student_leaves_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_study_materials' ), $permissions ) ) {
								// Class - Study Materials.
								$school_staff_study_materials_submenu = add_submenu_page( WLSM_MENU_STAFF_ACADEMIC, esc_html__( 'Study Materials', 'school-management' ), esc_html__( 'Study Materials', 'school-management' ), 'read', WLSM_MENU_STAFF_STUDY_MATERIALS, array( 'WLSM_Menu', 'school_staff_study_materials' ) );
								add_action( 'admin_print_styles-' . $school_staff_study_materials_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_homework' ), $permissions ) ) {
								// Class - Homework.
								$school_staff_homework_submenu = add_submenu_page( WLSM_MENU_STAFF_ACADEMIC, esc_html__( 'Home Work', 'school-management' ), esc_html__( 'Home Work', 'school-management' ), 'read', WLSM_MENU_STAFF_HOMEWORK, array( 'WLSM_Menu', 'school_staff_homework' ) );
								add_action( 'admin_print_styles-' . $school_staff_homework_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_notices' ), $permissions ) ) {
								// Class - Notices.
								$school_staff_notices_submenu = add_submenu_page( WLSM_MENU_STAFF_ACADEMIC, esc_html__( 'Noticeboard', 'school-management' ), esc_html__( 'Noticeboard', 'school-management' ), 'read', WLSM_MENU_STAFF_NOTICES, array( 'WLSM_Menu', 'school_staff_notices' ) );
								add_action( 'admin_print_styles-' . $school_staff_notices_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_events' ), $permissions ) ) {
								// Class - Events.
								$school_staff_events_submenu = add_submenu_page( WLSM_MENU_STAFF_ACADEMIC, esc_html__( 'Events', 'school-management' ), esc_html__( 'Events', 'school-management' ), 'read', WLSM_MENU_STAFF_EVENTS, array( 'WLSM_Menu', 'school_staff_events' ) );
								add_action( 'admin_print_styles-' . $school_staff_events_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_live_classes' ), $permissions ) ) {
								// Class - Meetings.
								$school_staff_meetings_submenu = add_submenu_page( WLSM_MENU_STAFF_ACADEMIC, esc_html__( 'Live Classes', 'school-management' ), esc_html__( 'Live Classes', 'school-management' ), 'read', WLSM_MENU_STAFF_MEETINGS, array( 'WLSM_Menu', 'school_staff_meetings' ) );
								add_action( 'admin_print_styles-' . $school_staff_meetings_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_live_classes' ), $permissions ) ) {
								// Class - Ratting.
								$school_staff_ratting_submenu = add_submenu_page( WLSM_MENU_STAFF_ACADEMIC, esc_html__( 'Staff Ratting', 'school-management' ), esc_html__( 'Staff Ratting', 'school-management' ), 'read', WLSM_MENU_STAFF_RATTING, array( 'WLSM_Menu', 'school_staff_ratting' ) );
								add_action( 'admin_print_styles-' . $school_staff_ratting_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_students' ), $permissions ) ) {
								// Class - Ratting.
								$school_student_birthdays_submenu = add_submenu_page( WLSM_MENU_STAFF_ACADEMIC, esc_html__( 'Student Birthdays', 'school-management' ), esc_html__( 'Student Birthdays', 'school-management' ), 'read', WLSM_MENU_STUDENT_BIRTHDAYS, array( 'WLSM_Menu', 'school_student_birthdays' ) );
								add_action( 'admin_print_styles-' . $school_student_birthdays_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}
						}

						// Student - Group.
						if ( WLSM_M_Role::check_permission( array( 'manage_admissions', 'manage_students', 'manage_promote', 'manage_transfer_student', 'manage_certificates', 'send_notifications' ), $permissions ) ) {

							$school_staff_group_student_menu = add_menu_page( esc_html__( 'Student', 'school-management' ), esc_html__( 'SM Student', 'school-management' ), 'read', WLSM_MENU_STAFF_STUDENT, array( 'WLSM_Menu', 'school_staff_group_student' ), 'dashicons-groups', 32 );
							add_action( 'admin_print_styles-' . $school_staff_group_student_menu, array( 'WLSM_Menu', 'menu_page_assets' ) );

							$school_staff_group_student_submenu = add_submenu_page( WLSM_MENU_STAFF_STUDENT, esc_html__( 'Student', 'school-management' ), esc_html__( 'Dashboard', 'school-management' ), 'read', WLSM_MENU_STAFF_STUDENT, array( 'WLSM_Menu', 'school_staff_group_student' ) );
							add_action( 'admin_print_styles-' . $school_staff_group_student_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

							if ( WLSM_M_Role::check_permission( array( 'manage_admissions' ), $permissions ) ) {
								// School - Admissions.
								$school_staff_admissions_submenu = add_submenu_page( WLSM_MENU_STAFF_STUDENT, esc_html__( 'Admission', 'school-management' ), esc_html__( 'Admission', 'school-management' ), 'read', WLSM_MENU_STAFF_ADMISSIONS, array( 'WLSM_Menu', 'school_staff_admissions' ) );
								add_action( 'admin_print_styles-' . $school_staff_admissions_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_students' ), $permissions ) ) {
								// General - Students.
								$school_staff_students_submenu = add_submenu_page( WLSM_MENU_STAFF_STUDENT, esc_html__( 'Students', 'school-management' ), esc_html__( 'Students', 'school-management' ), 'read', WLSM_MENU_STAFF_STUDENTS, array( 'WLSM_Menu', 'school_staff_students' ) );
								add_action( 'admin_print_styles-' . $school_staff_students_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_students' ), $permissions ) ) {
								// School - ID Cards.
								$school_staff_id_cards_submenu = add_submenu_page( WLSM_MENU_STAFF_STUDENT, esc_html__( 'ID Cards', 'school-management' ), esc_html__( 'ID Cards', 'school-management' ), 'read', WLSM_MENU_STAFF_ID_CARDS, array( 'WLSM_Menu', 'school_staff_id_cards' ) );
								add_action( 'admin_print_styles-' . $school_staff_id_cards_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_promote' ), $permissions ) ) {
								// School - Promote.
								$school_staff_promote_submenu = add_submenu_page( WLSM_MENU_STAFF_STUDENT, esc_html__( 'Promote', 'school-management' ), esc_html__( 'Promote', 'school-management' ), 'read', WLSM_MENU_STAFF_PROMOTE, array( 'WLSM_Menu', 'school_staff_promote' ) );
								add_action( 'admin_print_styles-' . $school_staff_promote_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_transfer_student' ), $permissions ) ) {
								// School - Transfer Student.
								$school_staff_transfer_student_submenu = add_submenu_page( WLSM_MENU_STAFF_STUDENT, esc_html__( 'Transfer Student', 'school-management' ), esc_html__( 'Transfer Student', 'school-management' ), 'read', WLSM_MENU_STAFF_TRANSFER_STUDENT, array( 'WLSM_Menu', 'school_staff_transfer_student' ) );
								add_action( 'admin_print_styles-' . $school_staff_transfer_student_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_certificates' ), $permissions ) ) {
								// School - Manage Certificates.
								$school_staff_manage_certificates_submenu = add_submenu_page( WLSM_MENU_STAFF_STUDENT, esc_html__( 'Certificates', 'school-management' ), esc_html__( 'Certificates', 'school-management' ), 'read', WLSM_MENU_STAFF_CERTIFICATES, array( 'WLSM_Menu', 'school_staff_manage_certificates' ) );
								add_action( 'admin_print_styles-' . $school_staff_manage_certificates_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'send_notifications' ), $permissions ) ) {
								// School - Send Notifications.
								$school_staff_send_notifications_submenu = add_submenu_page( WLSM_MENU_STAFF_STUDENT, esc_html__( 'Notifications', 'school-management' ), esc_html__( 'Notifications', 'school-management' ), 'read', WLSM_MENU_STAFF_NOTIFICATIONS, array( 'WLSM_Menu', 'school_staff_send_notifications' ) );
								add_action( 'admin_print_styles-' . $school_staff_send_notifications_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}
						}

						// Administrator - Group.
						if ( WLSM_M_Role::check_permission( array( 'manage_admins', 'manage_roles', 'manage_employees', 'manage_staff_attendance', 'manage_staff_leaves' ), $permissions ) ) {

							$school_staff_group_administrator_menu = add_menu_page( esc_html__( 'Administrator', 'school-management' ), esc_html__( 'SM Administrator', 'school-management' ), 'read', WLSM_MENU_STAFF_ADMINISTRATOR, array( 'WLSM_Menu', 'school_staff_group_administrator' ), 'dashicons-businessman', 33 );
							add_action( 'admin_print_styles-' . $school_staff_group_administrator_menu, array( 'WLSM_Menu', 'menu_page_assets' ) );

							$school_staff_group_administrator_submenu = add_submenu_page( WLSM_MENU_STAFF_ADMINISTRATOR, esc_html__( 'Administrator', 'school-management' ), esc_html__( 'Dashboard', 'school-management' ), 'read', WLSM_MENU_STAFF_ADMINISTRATOR, array( 'WLSM_Menu', 'school_staff_group_administrator' ) );
							add_action( 'admin_print_styles-' . $school_staff_group_administrator_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

							if ( WLSM_M_Role::check_permission( array( 'manage_admins' ), $permissions ) ) {
								// School - Admins.
								$school_staff_admins_submenu = add_submenu_page( WLSM_MENU_STAFF_ADMINISTRATOR, esc_html__( 'Admins', 'school-management' ), esc_html__( 'Admins', 'school-management' ), 'read', WLSM_MENU_STAFF_ADMINS, array( 'WLSM_Menu', 'school_staff_admins' ) );
								add_action( 'admin_print_styles-' . $school_staff_admins_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_roles' ), $permissions ) ) {
								// School - Roles.
								$school_staff_roles_submenu = add_submenu_page( WLSM_MENU_STAFF_ADMINISTRATOR, esc_html__( 'Roles', 'school-management' ), esc_html__( 'Roles', 'school-management' ), 'read', WLSM_MENU_STAFF_ROLES, array( 'WLSM_Menu', 'school_staff_roles' ) );
								add_action( 'admin_print_styles-' . $school_staff_roles_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_employees' ), $permissions ) ) {
								// School - Employees.
								$school_staff_employees_submenu = add_submenu_page( WLSM_MENU_STAFF_ADMINISTRATOR, esc_html__( 'Staff List', 'school-management' ), esc_html__( 'Staff List', 'school-management' ), 'read', WLSM_MENU_STAFF_EMPLOYEES, array( 'WLSM_Menu', 'school_staff_employees' ) );
								add_action( 'admin_print_styles-' . $school_staff_employees_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_staff_attendance' ), $permissions ) ) {
								// School - Employees.
								$school_staff_employees_attendance_submenu = add_submenu_page( WLSM_MENU_STAFF_ADMINISTRATOR, esc_html__( 'Staff Attendance', 'school-management' ), esc_html__( 'Staff Attendance', 'school-management' ), 'read', WLSM_MENU_STAFF_EMPLOYEES_ATTENDANCE, array( 'WLSM_Menu', 'school_staff_employees_attendance' ) );
								add_action( 'admin_print_styles-' . $school_staff_employees_attendance_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_staff_leaves' ), $permissions ) ) {
								// School - Staff Leaves.
								$school_staff_manage_staff_leaves_submenu = add_submenu_page( WLSM_MENU_STAFF_ADMINISTRATOR, esc_html__( 'Staff Leaves', 'school-management' ), esc_html__( 'Staff Leaves', 'school-management' ), 'read', WLSM_MENU_STAFF_EMPLOYEE_LEAVES, array( 'WLSM_Menu', 'school_staff_employee_leaves' ) );
								add_action( 'admin_print_styles-' . $school_staff_manage_staff_leaves_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}
						}

						if (!get_option('wlsm_examination_menu')) {
							// Examination - Group.
							if ( WLSM_M_Role::check_permission( array( 'manage_exams' ), $permissions ) ) {

								$school_staff_group_examination_menu = add_menu_page( esc_html__( 'Examination', 'school-management' ), esc_html__( 'SM Examination', 'school-management' ), 'read', WLSM_MENU_STAFF_EXAMINATION, array( 'WLSM_Menu', 'school_staff_group_examination' ), 'dashicons-clock', 33 );
								add_action( 'admin_print_styles-' . $school_staff_group_examination_menu, array( 'WLSM_Menu', 'menu_page_assets' ) );

								$school_staff_group_examination_submenu = add_submenu_page( WLSM_MENU_STAFF_EXAMINATION, esc_html__( 'Examination', 'school-management' ), esc_html__( 'Dashboard', 'school-management' ), 'read', WLSM_MENU_STAFF_EXAMINATION, array( 'WLSM_Menu', 'school_staff_group_examination' ) );
								add_action( 'admin_print_styles-' . $school_staff_group_examination_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

								if ( WLSM_M_Role::check_permission( array( 'manage_exams' ), $permissions ) ) {
									// Examination - Exams.
									$school_staff_exams_submenu = add_submenu_page( WLSM_MENU_STAFF_EXAMINATION, esc_html__( 'Exams', 'school-management' ), esc_html__( 'Manage Exams', 'school-management' ), 'read', WLSM_MENU_STAFF_EXAMS, array( 'WLSM_Menu', 'school_staff_exams' ) );
									add_action( 'admin_print_styles-' . $school_staff_exams_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

									$school_staff_exams_submenu = add_submenu_page( WLSM_MENU_STAFF_EXAMINATION, esc_html__( 'Exams', 'school-management' ), esc_html__( 'Manage Groups', 'school-management' ), 'read', WLSM_MENU_EXAMS_GROUP, array( 'WLSM_Menu', 'school_exams_group' ) );
									add_action( 'admin_print_styles-' . $school_staff_exams_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

									// Examination - Admit Cards.
									$school_staff_exam_admit_cards_submenu = add_submenu_page( WLSM_MENU_STAFF_EXAMINATION, esc_html__( 'Admit Cards', 'school-management' ), esc_html__( 'Admit Cards', 'school-management' ), 'read', WLSM_MENU_STAFF_EXAM_ADMIT_CARDS, array( 'WLSM_Menu', 'school_staff_exam_admit_cards' ) );
									add_action( 'admin_print_styles-' . $school_staff_exam_admit_cards_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

									// Examination - Admit Cards Print.
									$school_staff_exam_admit_cards_submenu = add_submenu_page( WLSM_MENU_STAFF_EXAMINATION, esc_html__( 'Admit Cards Print', 'school-management' ), esc_html__( 'Admit Cards Bulk Print', 'school-management' ), 'read', WLSM_MENU_STAFF_EXAM_ADMIT_CARDS_BULK_PRINT, array( 'WLSM_Menu', 'school_staff_exam_admit_cards_bulk_print' ) );
									add_action( 'admin_print_styles-' . $school_staff_exam_admit_cards_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

									// Examination - Exam Results.
									$school_staff_exam_results_submenu = add_submenu_page( WLSM_MENU_STAFF_EXAMINATION, esc_html__( 'Exam Results', 'school-management' ), esc_html__( 'Exam Results', 'school-management' ), 'read', WLSM_MENU_STAFF_EXAM_RESULTS, array( 'WLSM_Menu', 'school_staff_exam_results' ) );
									add_action( 'admin_print_styles-' . $school_staff_exam_results_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

									// Examination - Exam Results Bulk Print.
									$school_staff_exam_results_bulk_print_submenu = add_submenu_page( WLSM_MENU_STAFF_EXAMINATION, esc_html__( 'Bulk Print Results', 'school-management' ), esc_html__( 'Bulk Print Results', 'school-management' ), 'read', WLSM_MENU_STAFF_EXAM_RESULTS_BULK_PRINT, array( 'WLSM_Menu', 'school_staff_exam_results_bulk_print' ) );
									add_action( 'admin_print_styles-' . $school_staff_exam_results_bulk_print_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

									// Examination - Exam Assessment.
									$school_staff_exam_assessment_submenu = add_submenu_page( WLSM_MENU_STAFF_EXAMINATION, esc_html__( 'Results Assessment', 'school-management' ), esc_html__( 'Results Assessment', 'school-management' ), 'read', WLSM_MENU_STAFF_EXAM_ASSESSMENT, array( 'WLSM_Menu', 'school_staff_exam_assessment' ) );
									add_action( 'admin_print_styles-' . $school_staff_exam_assessment_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

									// Examination - Exam Assessment.
									$school_staff_academic_report_submenu = add_submenu_page( WLSM_MENU_STAFF_EXAMINATION, esc_html__( 'Academic Report', 'school-management' ), esc_html__( 'Academic Report', 'school-management' ), 'read', WLSM_MENU_STAFF_ACADEMIC_REPORT, array( 'WLSM_Menu', 'school_staff_exam_academic_report' ) );
									add_action( 'admin_print_styles-' . $school_staff_academic_report_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
								}
							}
						}

						// Accounting - Group.
						if ( WLSM_M_Role::check_permission( array( 'manage_expenses', 'manage_income', 'manage_invoices', 'manage_fees' ), $permissions ) ) {

							$school_staff_group_accounting_menu = add_menu_page( esc_html__( 'Accounting', 'school-management' ), esc_html__( 'SM Accounting', 'school-management' ), 'read', WLSM_MENU_STAFF_ACCOUNTING, array( 'WLSM_Menu', 'school_staff_group_accounting' ), 'dashicons-media-spreadsheet', 33 );
							add_action( 'admin_print_styles-' . $school_staff_group_accounting_menu, array( 'WLSM_Menu', 'menu_page_assets' ) );

							$school_staff_group_accounting_submenu = add_submenu_page( WLSM_MENU_STAFF_ACCOUNTING, esc_html__( 'Accounting', 'school-management' ), esc_html__( 'Dashboard', 'school-management' ), 'read', WLSM_MENU_STAFF_ACCOUNTING, array( 'WLSM_Menu', 'school_staff_group_accounting' ) );
							add_action( 'admin_print_styles-' . $school_staff_group_accounting_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

							if ( WLSM_M_Role::check_permission( array( 'manage_income' ), $permissions ) ) {
								// Accountant - Income.
								$school_staff_income_submenu = add_submenu_page( WLSM_MENU_STAFF_ACCOUNTING, esc_html__( 'Income', 'school-management' ), esc_html__( 'Income', 'school-management' ), 'read', WLSM_MENU_STAFF_INCOME, array( 'WLSM_Menu', 'school_staff_income' ) );
								add_action( 'admin_print_styles-' . $school_staff_income_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_expenses' ), $permissions ) ) {
								// Accountant - Expenses.
								$school_staff_expenses_submenu = add_submenu_page( WLSM_MENU_STAFF_ACCOUNTING, esc_html__( 'Expenses', 'school-management' ), esc_html__( 'Expenses', 'school-management' ), 'read', WLSM_MENU_STAFF_EXPENSES, array( 'WLSM_Menu', 'school_staff_expenses' ) );
								add_action( 'admin_print_styles-' . $school_staff_expenses_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_invoices' ), $permissions ) ) {
								// Accountant - Invoices.
								$school_staff_invoices_submenu = add_submenu_page( WLSM_MENU_STAFF_ACCOUNTING, esc_html__( 'Fee Invoices', 'school-management' ), esc_html__( 'Fee Invoices', 'school-management' ), 'read', WLSM_MENU_STAFF_INVOICES, array( 'WLSM_Menu', 'school_staff_invoices' ) );
								add_action( 'admin_print_styles-' . $school_staff_invoices_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_fees' ), $permissions ) ) {
								// Accountant - Fee Types.
								$school_staff_fee_types_submenu = add_submenu_page( WLSM_MENU_STAFF_ACCOUNTING, esc_html__( 'Fee Types', 'school-management' ), esc_html__( 'Fee Types', 'school-management' ), 'read', WLSM_MENU_STAFF_FEES, array( 'WLSM_Menu', 'school_staff_fees' ) );
								add_action( 'admin_print_styles-' . $school_staff_fee_types_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}

							if ( WLSM_M_Role::check_permission( array( 'manage_invoices' ), $permissions ) ) {
								// Accountant - Invoices.
								$school_staff_invoices_submenu = add_submenu_page( WLSM_MENU_STAFF_ACCOUNTING, esc_html__( 'Invoices Print', 'school-management' ), esc_html__( 'Bulk Invoices Print', 'school-management' ), 'read', WLSM_MENU_STAFF_INVOICES_PRINT, array( 'WLSM_Menu', 'school_staff_invoices_print' ) );
								add_action( 'admin_print_styles-' . $school_staff_invoices_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}
							if ( WLSM_M_Role::check_permission( array( 'manage_invoices' ), $permissions ) ) {
								// Accountant - Invoices_report.
								$school_staff_invoices_report_submenu = add_submenu_page( WLSM_MENU_STAFF_ACCOUNTING, esc_html__( 'Invoices_report Print', 'school-management' ), esc_html__( 'Invoices Report', 'school-management' ), 'read', WLSM_MENU_STAFF_INVOICES_REPORT_PRINT, array( 'WLSM_Menu', 'school_staff_invoices_report_print' ) );
								add_action( 'admin_print_styles-' . $school_staff_invoices_report_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}
						}

						// Library - Group.
						if ( !get_option( 'wlsm_library_menu' ) ) {
							if ( WLSM_M_Role::check_permission( array( 'manage_library' ), $permissions ) ) {

								$school_staff_group_library_menu = add_menu_page( esc_html__( 'Library', 'school-management' ), esc_html__( 'SM Library', 'school-management' ), 'read', WLSM_MENU_STAFF_LIBRARY, array( 'WLSM_Menu', 'school_staff_group_library' ), 'dashicons-book', 34 );
								add_action( 'admin_print_styles-' . $school_staff_group_library_menu, array( 'WLSM_Menu', 'menu_page_assets' ) );

								$school_staff_group_library_submenu = add_submenu_page( WLSM_MENU_STAFF_LIBRARY, esc_html__( 'Library', 'school-management' ), esc_html__( 'Dashboard', 'school-management' ), 'read', WLSM_MENU_STAFF_LIBRARY, array( 'WLSM_Menu', 'school_staff_group_library' ) );
								add_action( 'admin_print_styles-' . $school_staff_group_library_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

								// Library - Books.
								$school_staff_books_submenu = add_submenu_page( WLSM_MENU_STAFF_LIBRARY, esc_html__( 'All Books', 'school-management' ), esc_html__( 'All Books', 'school-management' ), 'read', WLSM_MENU_STAFF_BOOKS, array( 'WLSM_Menu', 'school_staff_books' ) );
								add_action( 'admin_print_styles-' . $school_staff_books_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

								// Library - Books Issued.
								$school_staff_books_issued_submenu = add_submenu_page( WLSM_MENU_STAFF_LIBRARY, esc_html__( 'Books Issued', 'school-management' ), esc_html__( 'Books Issued', 'school-management' ), 'read', WLSM_MENU_STAFF_BOOKS_ISSUED, array( 'WLSM_Menu', 'school_staff_books_issued' ) );
								add_action( 'admin_print_styles-' . $school_staff_books_issued_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

								// Library - Cards.
								$school_staff_library_cards_submenu = add_submenu_page( WLSM_MENU_STAFF_LIBRARY, esc_html__( 'Library Cards', 'school-management' ), esc_html__( 'Library Cards', 'school-management' ), 'read', WLSM_MENU_STAFF_LIBRARY_CARDS, array( 'WLSM_Menu', 'school_staff_library_cards' ) );
								add_action( 'admin_print_styles-' . $school_staff_library_cards_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}
						}

						if (!get_option('wlsm_transport_menu')) {
							// Transport - Group.
							if ( WLSM_M_Role::check_permission( array( 'manage_transport' ), $permissions ) ) {

								$school_staff_group_transport_menu = add_menu_page( esc_html__( 'Transport', 'school-management' ), esc_html__( 'SM Transport', 'school-management' ), 'read', WLSM_MENU_STAFF_TRANSPORT, array( 'WLSM_Menu', 'school_staff_group_transport' ), 'dashicons-location-alt', 34 );
								add_action( 'admin_print_styles-' . $school_staff_group_transport_menu, array( 'WLSM_Menu', 'menu_page_assets' ) );

								$school_staff_group_transport_submenu = add_submenu_page( WLSM_MENU_STAFF_TRANSPORT, esc_html__( 'Transport', 'school-management' ), esc_html__( 'Dashboard', 'school-management' ), 'read', WLSM_MENU_STAFF_TRANSPORT, array( 'WLSM_Menu', 'school_staff_group_transport' ) );
								add_action( 'admin_print_styles-' . $school_staff_group_transport_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

								// Transport - Vehicles.
								$school_staff_vehicles_submenu = add_submenu_page( WLSM_MENU_STAFF_TRANSPORT, esc_html__( 'Vehicles', 'school-management' ), esc_html__( 'Vehicles', 'school-management' ), 'read', WLSM_MENU_STAFF_VEHICLES, array( 'WLSM_Menu', 'school_staff_vehicles' ) );
								add_action( 'admin_print_styles-' . $school_staff_vehicles_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

								// Transport - Routes.
								$school_staff_routes_submenu = add_submenu_page( WLSM_MENU_STAFF_TRANSPORT, esc_html__( 'Routes', 'school-management' ), esc_html__( 'Routes', 'school-management' ), 'read', WLSM_MENU_STAFF_ROUTES, array( 'WLSM_Menu', 'school_staff_routes' ) );
								add_action( 'admin_print_styles-' . $school_staff_routes_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

								// Transport - Report.
								$school_staff_transport_report_submenu = add_submenu_page( WLSM_MENU_STAFF_TRANSPORT, esc_html__( 'Transport Report', 'school-management' ), esc_html__( 'Report', 'school-management' ), 'read', WLSM_MENU_STAFF_TRANSPORT_REPORT, array( 'WLSM_Menu', 'school_staff_transport_report' ) );
								add_action( 'admin_print_styles-' . $school_staff_transport_report_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}
						}

						if (!get_option('wlsm_hostel_menu')) {
							// Hostel - Group.
							if ( WLSM_M_Role::check_permission( array( 'manage_hostel' ), $permissions ) ) {

								$school_staff_group_hostel_menu = add_menu_page( esc_html__( 'SM Hostel', 'school-management' ), esc_html__( 'SM Hostel', 'school-management' ), 'read', WLSM_MENU_STAFF_HOSTEL, array( 'WLSM_Menu', 'school_staff_group_hostel' ), 'dashicons-admin-home', 34 );
								add_action( 'admin_print_styles-' . $school_staff_group_hostel_menu, array( 'WLSM_Menu', 'menu_page_assets' ) );

								// Hostel -
								$school_staff_hostel_submenu = add_submenu_page( WLSM_MENU_STAFF_HOSTEL, esc_html__( 'Hostels', 'school-management' ), esc_html__( 'Hostels', 'school-management' ), 'read', WLSM_MENU_STAFF_HOSTELS, array( 'WLSM_Menu', 'school_staff_hostel' ) );
								add_action( 'admin_print_styles-' . $school_staff_hostel_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );

								// Room -
								$school_staff_room_submenu = add_submenu_page( WLSM_MENU_STAFF_HOSTEL, esc_html__( 'Rooms', 'school-management' ), esc_html__( 'Rooms', 'school-management' ), 'read', WLSM_MENU_STAFF_ROOMS, array( 'WLSM_Menu', 'school_staff_room' ) );
								add_action( 'admin_print_styles-' . $school_staff_room_submenu, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}
						}

						if (!get_option('wlsm_lessons_menu')) {
							// Lessons

							if ( WLSM_M_Role::check_permission( array( 'manage_lessons' ), $permissions ) ) {
							$school_lectures = add_menu_page( esc_html__( 'Lessons', 'school-management' ), esc_html__( 'SM Lessons', 'school-management' ), 'read', WLSM_LECTURE, array( 'WLSM_Menu', 'school_lecture' ), 'dashicons-share-alt', 34 );
							add_action( 'admin_print_styles-' . $school_lectures, array( 'WLSM_Menu', 'menu_page_assets' ) );

							$school_staff = add_submenu_page( WLSM_LECTURE, esc_html__( 'Lessons', 'school-management' ), esc_html__( 'Lessons', 'school-management' ), 'read', WLSM_LECTURE, array( 'WLSM_Menu', 'school_lecture' ) );
							add_action( 'admin_print_styles-' . $school_staff, array( 'WLSM_Menu', 'menu_page_assets' ) );

							$chapter = add_submenu_page( WLSM_LECTURE, esc_html__( 'Chapter', 'school-management' ), esc_html__( 'Chapter', 'school-management' ), 'read', WLSM_CHAPTER, array( 'WLSM_Menu', 'school_chapter' ) );
							add_action( 'admin_print_styles-' . $chapter, array( 'WLSM_Menu', 'menu_page_assets' ) );
							}
						}

						if ( WLSM_M_Role::check_permission( array( 'manage_activities' ), $permissions ) ) {
							$school_activities = add_menu_page( esc_html__( 'Activities', 'school-management' ), esc_html__( 'SM Activities', 'school-management' ), 'read', WLSM_ACTIVITIES, array( 'WLSM_Menu', 'school_activities' ), 'dashicons-buddicons-activity', 34 );
							add_action( 'admin_print_styles-' . $school_activities, array( 'WLSM_Menu', 'menu_page_assets' ) );

							$school_staff = add_submenu_page( WLSM_ACTIVITIES, esc_html__( 'Activities', 'school-management' ), esc_html__( 'Activities', 'school-management' ), 'read', WLSM_ACTIVITIES, array( 'WLSM_Menu', 'school_activities' ) );
							add_action( 'admin_print_styles-' . $school_staff, array( 'WLSM_Menu', 'menu_page_assets' ) );
						}
					}
				}
			}
		} else {
			$school_management_menu = add_menu_page( esc_html__( 'School Management', 'school-management' ), esc_html__( 'School Management', 'school-management' ), WLSM_ADMIN_CAPABILITY, 'school-management-license', array( 'WLSM_Menu', 'admin_menu' ), 'dashicons-welcome-learn-more', 27 );
			add_action( 'admin_print_styles-' . $school_management_menu, array( 'WLSM_Menu', 'admin_menu_assets' ) );

			$school_management_submenu = add_submenu_page( 'school-management-license', esc_html__( 'License', 'school-management' ), esc_html__( 'License', 'school-management' ), WLSM_ADMIN_CAPABILITY, 'school-management-license', array( 'WLSM_Menu', 'admin_menu' ) );
			add_action( 'admin_print_styles-' . $school_management_submenu, array( 'WLSM_Menu', 'admin_menu_assets' ) );
		}
	}

	// Manager dashboard.
	public static function school_management() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/dashboard/route.php';
	}

	// Manager schools.
	public static function schools() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/schools/route.php';
	}

	// Manager classes.
	public static function classes() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/classes/route.php';
	}

	// Manager category.
	public static function category() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/category/route.php';
	}

	// Manager sessions.
	public static function sessions() {
		 require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/sessions/route.php';
	}

	// Manager settings.
	public static function settings() {
		 require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/settings/index.php';
	}

	public static function admin_menu() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/admin_menu.php';
	}

	public static function admin_menu_assets() {
		wp_enqueue_style( 'wlsm_lc', WLSM_PLUGIN_URL . 'assets/css/admin_menu.css' );
	}

	// Schools assigned.
	public static function schools_assigned() {
		 require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/schools-assigned.php';
	}

	// School - Dashboard.
	public static function school_staff_dashboard() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/dashboard/route.php';
	}

	// School - Inquiries.
	public static function school_staff_inquiries() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/inquiries/route.php';
	}

	// School - Admissions.
	public static function school_staff_admissions() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/admissions/route.php';
	}

	// Hostel - Group.
	public static function school_staff_group_hostel() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/groups/hostel.php';
	}

	public static function school_staff_hostel() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/hostel/hostels/route.php';
	}

	public static function school_staff_room() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/hostel/rooms/route.php';
	}


	// School - Students.
	public static function school_staff_students() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/students/route.php';
	}

	// School - ID Cards.
	public static function school_staff_id_cards() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/id-cards/route.php';
	}

	// School - Admins.
	public static function school_staff_admins() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/admins/route.php';
	}

	// School - Roles.
	public static function school_staff_roles() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/roles/route.php';
	}

	// School - Employees.
	public static function school_staff_employees() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/employees/route.php';
	}

	// School - Staff Attendance.
	public static function school_staff_employees_attendance() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/staff-attendance/route.php';
	}

	// School - Staff Leaves.
	public static function school_staff_employee_leaves() {
		 require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/staff-leaves/route.php';
	}

	// School - Promote.
	public static function school_staff_promote() {
		 require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/promote/route.php';
	}

	// School - Transfer Student.
	public static function school_staff_transfer_student() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/transfer-student/route.php';
	}

	// School - Manage Certificates.
	public static function school_staff_manage_certificates() {
		 require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/certificates/route.php';
	}

	// School - Send Notifications.
	public static function school_staff_send_notifications() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/notifications/route.php';
	}

	// School - Settings.
	public static function school_staff_settings() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/route.php';
	}

	// School - Logs.
	public static function school_staff_logs() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/logs/route.php';
	}

	// School - Staff Leave Request.
	public static function school_staff_leave_request() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/staff-leave-request/route.php';
	}

	// School - Staff Assigned Meetings.
	public static function school_staff_assigned_meetings() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/staff-live-classes/route.php';
	}

	// Class - Classes & Sections.
	public static function school_staff_classes() {
		 require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/class/classes/route.php';
	}

	public static function school_staff_medium() {
		 require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/class/medium/medium.php';
	}

	public static function school_staff_student_type() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/class/student_type/student_type.php';
   }

	// Class - Subjects.
	public static function school_staff_subjects() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/class/subjects/route.php';
	}

	// Class - Timetable.
	public static function school_staff_timetable() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/class/routines/route.php';
	}

	// Staff Timetable
	public static function school_staff_member_timetable() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/class/staff-timetable/route.php';
	}

	// Class - Attendance.
	public static function school_staff_attendance() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/class/attendance/route.php';
	}

	// Class - Student Leaves.
	public static function school_staff_student_leaves() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/class/student-leaves/route.php';
	}

	// Class - Student Activities.
	public static function school_activities() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/class/activities/route.php';
	}

	// Class - Study Materials.
	public static function school_staff_study_materials() {
		 require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/class/study-materials/route.php';
	}

	// Class - Homework.
	public static function school_staff_homework() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/class/homework/route.php';
	}

	// Class - Notices.
	public static function school_staff_notices() {
		 require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/class/notices/route.php';
	}

	// Class - Events.
	public static function school_staff_events() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/class/events/route.php';
	}

	// Class - Meetings.
	public static function school_staff_meetings() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/class/meetings/route.php';
	}

	// Class - Ratting.
	public static function school_staff_ratting() {
		 require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/class/ratting/route.php';
	}

	public static function school_student_birthdays() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/student-birthdays/index.php';
   }

	// Examination - Exams.
	public static function school_staff_exams() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/examination/exams/route.php';
	}

	public static function school_exams_group() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/examination/exams_group/route.php';
	}

	// Examination - Admit Cards.
	public static function school_staff_exam_admit_cards() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/examination/admit-cards/route.php';
	}

	// Examination - Admit Cards Print.
	public static function school_staff_exam_admit_cards_bulk_print() {
		 require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/examination/admit-cards-bulk-print/route.php';
	}

	// Examination - Bulk Print Results.
	public static function school_staff_exam_results_bulk_print() {
		 require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/examination/bulk-print-results/route.php';
	}

	// Examination - Results.
	public static function school_staff_exam_results() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/examination/results/route.php';
	}

	// Examination - Assessment.
	public static function school_staff_exam_assessment() {
		 require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/examination/assessment/route.php';
	}

	// Examination - Academic_report.
	public static function school_staff_exam_academic_report() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/examination/academic_report/route.php';
   }

	// Accountant - Income.
	public static function school_staff_income() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/accountant/income/route.php';
	}

	// Accountant - Expenses.
	public static function school_staff_expenses() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/accountant/expenses/route.php';
	}

	// Accountant - Invoices.
	public static function school_staff_invoices() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/accountant/invoices/route.php';
	}

	// Accountant - Invoices.
	public static function school_staff_invoices_print() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/accountant/invoices-print/route.php';
	}

	public static function school_staff_invoices_report_print() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/accountant/report/route.php';
	}

	// Accountant - Fee Types.
	public static function school_staff_fees() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/accountant/fees/route.php';
	}

	// Library - Books.
	public static function school_staff_books() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/library/books/route.php';
	}

	// Library - Books Issued.
	public static function school_staff_books_issued() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/library/books-issued/route.php';
	}

	// Library - Library Cards.
	public static function school_staff_library_cards() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/library/cards/route.php';
	}

	// Transport - Vehicles.
	public static function school_staff_vehicles() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/transport/vehicles/route.php';
	}

	// Transport - Routes.
	public static function school_staff_routes() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/transport/routes/route.php';
	}

	// Transport - Report.
	public static function school_staff_transport_report() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/transport/report/route.php';
	}

	// Academic - Group.
	public static function school_staff_group_academic() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/groups/academic.php';
	}

	// Student - Group.
	public static function school_staff_group_student() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/groups/student.php';
	}

	// Administrator - Group.
	public static function school_staff_group_administrator() {
		 require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/groups/administrator.php';
	}

	// Examination - Group.
	public static function school_staff_group_examination() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/groups/examination.php';
	}

	// Accounting - Group.
	public static function school_staff_group_accounting() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/groups/accounting.php';
	}

	// Library - Group.
	public static function school_staff_group_library() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/groups/library.php';
	}

	// Transport - Group.
	public static function school_staff_group_transport() {
		 require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/groups/transport.php';
	}

	// Lecture
	public static function school_lecture() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/lectures/route.php';
	}

	public static function school_chapter() {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/chapter/route.php';
	}

	public static function menu_page_assets() {
		 wp_enqueue_style( 'bootstrap', WLSM_PLUGIN_URL . 'assets/css/bootstrap.min.css' );
		wp_enqueue_style( 'jquery-confirm', WLSM_PLUGIN_URL . 'assets/css/jquery-confirm.min.css' );
		wp_enqueue_style( 'toastr', WLSM_PLUGIN_URL . 'assets/css/toastr.min.css' );
		wp_enqueue_style( 'font-awesome-free', WLSM_PLUGIN_URL . 'assets/css/all.min.css' );
		wp_enqueue_style( 'zebra-datepicker', WLSM_PLUGIN_URL . 'assets/css/zebra_datepicker.min.css' );
		wp_enqueue_style( 'bootstrap-select', WLSM_PLUGIN_URL . 'assets/css/bootstrap-select.min.css' );

		wp_enqueue_style( 'dataTables-bootstrap4', WLSM_PLUGIN_URL . 'assets/css/datatable/dataTables.bootstrap4.min.css' );
		wp_enqueue_style( 'responsive-bootstrap4', WLSM_PLUGIN_URL . 'assets/css/datatable/responsive.bootstrap4.min.css' );
		wp_enqueue_style( 'jquery-dataTables', WLSM_PLUGIN_URL . 'assets/css/datatable/jquery.dataTables.min.css' );
		wp_enqueue_style( 'buttons-bootstrap4', WLSM_PLUGIN_URL . 'assets/css/datatable/buttons.bootstrap4.min.css' );

		wp_enqueue_style( 'wlsm-print-preview', WLSM_PLUGIN_URL . 'assets/css/print/wlsm-preview.css', array(), '5.1', 'all' );
		wp_enqueue_style( 'wlsm-admin', WLSM_PLUGIN_URL . 'assets/css/wlsm-admin.css', array(), '5.1', 'all' );
		wp_enqueue_style( 'wlsm-school-header', WLSM_PLUGIN_URL . 'assets/css/wlsm-school-header.css', array(), '5.1', 'all' );

		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_script( 'jquery-ui-sortable' );

		wp_enqueue_script( 'alpine', WLSM_PLUGIN_URL . 'assets/js/alpine.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'popper', WLSM_PLUGIN_URL . 'assets/js/popper.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'bootstrap', WLSM_PLUGIN_URL . 'assets/js/bootstrap.min.js', array( 'popper' ), true, true );
		wp_enqueue_script( 'chartjs', WLSM_PLUGIN_URL . 'assets/js/chart.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'jquery-confirm', WLSM_PLUGIN_URL . 'assets/js/jquery-confirm.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'toastr', WLSM_PLUGIN_URL . 'assets/js/toastr.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'zebra-datepicker', WLSM_PLUGIN_URL . 'assets/js/zebra_datepicker.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'bootstrap-select', WLSM_PLUGIN_URL . 'assets/js/bootstrap-select.min.js', array( 'bootstrap' ), true, true );

		wp_enqueue_script( 'jquery-dataTables', WLSM_PLUGIN_URL . 'assets/js/datatable/jquery.dataTables.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'dataTables-bootstrap4', WLSM_PLUGIN_URL . 'assets/js/datatable/dataTables.bootstrap4.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'dataTables-responsive', WLSM_PLUGIN_URL . 'assets/js/datatable/dataTables.responsive.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'responsive-bootstrap4', WLSM_PLUGIN_URL . 'assets/js/datatable/responsive.bootstrap4.min.js', array( 'jquery' ), true, true );

		wp_enqueue_script( 'wlsm-admin', WLSM_PLUGIN_URL . 'assets/js/wlsm-admin.js', array( 'jquery', 'jquery-form' ), '5.1', true );
		wp_localize_script( 'wlsm-admin', 'wlsmsecurity', wp_create_nonce( 'wlsm-security' ) );
		wp_localize_script( 'wlsm-admin', 'wlsmatformat', WLSM_Config::at_format() );
		wp_localize_script( 'wlsm-admin', 'wlsmdateformat', WLSM_Config::date_format() );
		wp_localize_script( 'wlsm-admin', 'wlsmadminurl', admin_url() );
		wp_localize_script( 'wlsm-admin', 'wlsmloadingtext', esc_html__( 'Loading...', 'school-management' ) );
	}
}
