<?php
/**
 * This code is executed when the plugin is uninstalled.
 *
 * @link       https://miniorange.com
 * @since      1.0.0
 *
 * @package    exam-and-quiz-online-proctoring-with-lms-integration
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
delete_site_option( 'mo_procto_activated_time' );
delete_site_option( 'mo_procto_select_lms' );
delete_site_option( 'mo_procto_max_limit_action' );
delete_site_option( 'mo_procto_restrict_inspect_browser' );
delete_site_option( 'mo_procto_disable_mouse_right_click' );
delete_site_option( 'mo_procto_restrict_session' );
delete_site_option( 'mo_procto_restrict_tab_switch' );



global $wpdb;
		$prefix = 'mo_procto_live_stream_id_';
		$users  = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT user_id FROM $wpdb->usermeta WHERE meta_key LIKE %s",
				$wpdb->esc_like( $prefix ) . '%'
			)
		);

		if ( ! empty( $users ) ) {
			foreach ( $users as $user_id ) {
				$user_meta_keys = $wpdb->get_col(
					$wpdb->prepare(
						"SELECT meta_key FROM $wpdb->usermeta WHERE user_id = %d AND meta_key LIKE %s",
						$user_id,
						$wpdb->esc_like( $prefix ) . '%'
					)
				);

				foreach ( $user_meta_keys as $meta_key ) {
					delete_user_meta( $user_id, $meta_key );
				}
			}
		}

