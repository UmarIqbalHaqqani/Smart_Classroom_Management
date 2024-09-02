<?php
defined( 'ABSPATH' ) || die();

class WLSM_Log {
	const LOGIN    = 'login';
	const ACTIVITY = 'activity';
	const ERROR    = 'error';

	public static function save( $school_id, $key, $value, $group = '' ) {
		// Logs settings.
		$settings_logs        = WLSM_M_Setting::get_settings_logs( $school_id );
		$school_activity_logs = $settings_logs['activity_logs'];

		if ( $key !== self::ERROR && ! $school_activity_logs ) {
			return;
		}

		global $wpdb;

		$data = array(
			'school_id'  => $school_id,
			'log_key'    => $key,
			'log_value'  => $value,
			'log_group'  => $group,
			'created_at' => current_time( 'Y-m-d H:i:s' )
		);

		$wpdb->insert( WLSM_LOGS, $data );
	}

	public static function logs() {
		return array(
			self::LOGIN    => esc_html__( 'Login', 'school-management' ),
			self::ACTIVITY => esc_html__( 'Activity', 'school-management' ),
			self::ERROR    => esc_html__( 'Error', 'school-management' ),
		);
	}

	public static function login_record( $user_login ) {
		global $wpdb;

		$user = get_user_by( 'login', $user_login );

		if ( ! $user ) {
			return;
		}

		$user_id  = $user->ID;
		$username = $user_login;

		try {
			ob_start();

			// Checks if user is student.
			$student = $wpdb->get_row(
				$wpdb->prepare( 'SELECT sr.ID, sr.name as student_name, c.label as class_label, se.label as section_label, u.user_login as username, s.ID as school_id, ss.label as session_label FROM ' . WLSM_STUDENT_RECORDS . ' as sr 
					JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
					JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
					JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
					JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
					JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id 
					JOIN ' . WLSM_USERS . ' as u ON u.ID = sr.user_id 
					LEFT OUTER JOIN ' . WLSM_TRANSFERS . ' as tf ON tf.from_student_record = sr.ID 
					WHERE sr.is_active = 1 AND tf.ID IS NULL AND sr.user_id = %d', $user_id )
			);

			if ( $student ) {
				// User is student.
				$school_id  = $student->school_id;
				$username   = $student->username;

				$log_value = sprintf(
					wp_kses(
						/* translators: 1: session label, 2: student name, 3: class, 4: section, 5: username */
						__( '<span class="wlsm-font-bold">Student</span> of <span class="wlsm-font-bold">session</span> %1$s with <span class="wlsm-font-bold">name:</span> %2$s, <span class="wlsm-font-bold">class:</span> %3$s, <span class="wlsm-font-bold">section:</span> %4$s and <span class="wlsm-font-bold">username:</span> %5$s logged in.', 'school-management' ),
						array( 'span' => array( 'class' => array() ) )
					),
					sanitize_text_field( WLSM_M_Session::get_label_text( $student->session_label ) ),
					sanitize_text_field( WLSM_M_Staff_Class::get_name_text( $student->student_name ) ),
					sanitize_text_field( WLSM_M_Class::get_label_text( $student->class_label ) ),
					sanitize_text_field( WLSM_M_Class::get_label_text( $student->section_label ) ),
					sanitize_text_field( $username )
				);

				self::save( $school_id, self::LOGIN, $log_value, 'student' );

			} else {
				require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Parent.php';

				$unique_student_ids = WLSM_M_Parent::get_parent_student_ids( $user_id );

				if ( count( $unique_student_ids ) ) {
					// User is parent.
					$students = WLSM_M_Parent::fetch_students( $unique_student_ids );

					$school_ids = array();
					foreach ( $students as $student ) {
						array_push( $school_ids, $student->school_id );
					}

					$school_ids = array_unique( $school_ids );

					foreach ( $school_ids as $school_id ) {
						$log_value = sprintf(
							wp_kses(
								/* translators: %s: username */
								__( '<span class="wlsm-font-bold">Parent</span> with <span class="wlsm-font-bold">username:</span> %s logged in.', 'school-management' ),
								array( 'span' => array( 'class' => array() ) )
							),
							sanitize_text_field( $username )
						);

						self::save( $school_id, self::LOGIN, $log_value, 'parent' );
					}
				}
			}

			// Check if user is staff.
			$user_info = WLSM_M_Role::get_user_info( $user_id );

			if ( $current_school = $user_info['current_school'] ) {
				$school_id = $current_school['id'];
				$role      = $current_school['role'];
				$roles     = WLSM_M_Role::get_roles();
				if ( in_array( $role, array_keys( $roles ) ) ) {
					$role_label = sanitize_text_field( $roles[ $role ] );

					// User is admin or staff.
					$log_value = sprintf(
						wp_kses(
							/* translators: 1: Admin or Staff, 2: username */
							__( '<span class="wlsm-font-bold">%1$s</span> with <span class="wlsm-font-bold">username:</span> %2$s logged in.', 'school-management' ),
							array( 'span' => array( 'class' => array() ) )
						),
						$role_label,
						sanitize_text_field( $username )
					);

					self::save( $school_id, self::LOGIN, $log_value, strtolower( $role_label ) );
				}
			}

			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				throw new Exception( $buffer );
			}

		} catch ( Exception $e ) {
			$log_value = $e->getMessage();

			self::save( NULL, self::ERROR, $log_value );
		}
	}

	public static function get_logs_page_url() {
		return admin_url( 'admin.php?page=' . WLSM_LOGS );
	}

	public static function fetch_log_query( $school_id ) {
		$query = 'SELECT lg.ID, lg.log_key, lg.log_value, lg.log_group, lg.created_at FROM ' . WLSM_LOGS . ' as lg 
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = lg.school_id 
		WHERE lg.school_id = ' . absint( $school_id ) . ' AND lg.log_key != "' . self::ERROR . '"';
		return $query;
	}

	public static function fetch_log_query_group_by() {
		$group_by = 'GROUP BY lg.ID';
		return $group_by;
	}

	public static function fetch_log_query_count( $school_id ) {
		$query = 'SELECT COUNT(DISTINCT lg.ID) FROM ' . WLSM_LOGS . ' as lg 
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = lg.school_id 
		WHERE lg.school_id = ' . absint( $school_id ) . ' AND lg.log_key != "' . self::ERROR . '"';
		return $query;
	}

	public static function delete_old_logs() {
		global $wpdb;

		// Delete all error logs after 20 days.
		$error_logs = $wpdb->get_results(
			$wpdb->prepare( 'SELECT lg.ID FROM ' . WLSM_LOGS . ' as lg WHERE lg.log_key = %s AND DATE(lg.created_at) <= NOW() - INTERVAL 20 DAY', self::ERROR )
		);

		if ( count( $error_logs ) ) {
			foreach ( $error_logs as $error_log ) {
				$wpdb->delete( WLSM_LOGS, array( 'ID' => $error_log->ID ) );
			}
		}

		// Get all logs except error logs.
		$school_logs = $wpdb->get_results(
			$wpdb->prepare( 'SELECT DISTINCT lg.school_id FROM ' . WLSM_LOGS . ' as lg WHERE lg.log_key != %s', self::ERROR )
		);

		if ( count( $school_logs ) ) {
			foreach ( $school_logs as $school_log ) {
				$school_id = $school_log->school_id;

				$settings_logs            = WLSM_M_Setting::get_settings_logs( $school_id );
				$school_delete_after_days = $settings_logs['delete_after_days'];

				// Delete all logs except error logs after number of days as set by school.
				$logs = $wpdb->get_results(
					$wpdb->prepare( 'SELECT lg.ID FROM ' . WLSM_LOGS . ' as lg WHERE lg.school_id = %s AND lg.log_key != %s AND DATE(lg.created_at) <= NOW() - INTERVAL %d DAY', $school_id, self::ERROR, $school_delete_after_days )
				);

				if ( count( $logs ) ) {
					foreach ( $logs as $log ) {
						$wpdb->delete( WLSM_LOGS, array( 'ID' => $log->ID ) );
					}
				}
			}
		}
	}
}
