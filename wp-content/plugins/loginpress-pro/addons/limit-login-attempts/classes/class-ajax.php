<?php
if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
* Handling all the AJAX calls in LoginPress - Limit Login Attempts.
*
* @since 3.0.0
* @class LoginPress_Attempts_AJAX
*/

if ( ! class_exists( 'LoginPress_Attempts_AJAX' ) ) :

	/**
	 * This class handle the Ajax request for Limit Login Attempts Add-On.
	 *
	 * @since 3.0.0
	 */
	class LoginPress_Attempts_AJAX {

		/**
		 * Variable for LoginPress Limit Login Attempts table name.
		 *
		 * @var string
		 * @since 3.0.0
		 */
		protected $llla_table;

		/**
		 * Class constructor
		 */
		public function __construct() {

			global $wpdb;
			$this->llla_table = $wpdb->prefix . 'loginpress_limit_login_details';
			$this->init();
		}

		/**
		 * Initialize the callbacks.
		 */
		public function init() {

			$ajax_calls = array(
				'attempts_whitelist'  => false,
				'attempts_blacklist'  => false,
				'attempts_unlock'     => false,
				'whitelist_clear'     => false,
				'blacklist_clear'     => false,
				'white_black_list_ip' => false,
				'white_list_records'  => false,
				'black_list_records'  => false,
				'attempts_bulk'       => false,
				'clear_all_attempts'  => false,
				'clear_all_blacklist' => false,
				'clear_all_whitelist' => false,
			);

			foreach ( $ajax_calls as $ajax_call => $no_priv ) {

				add_action( 'wp_ajax_loginpress_' . $ajax_call, array( $this, $ajax_call ) );

				if ( $no_priv ) {
					add_action( 'wp_ajax_nopriv_loginpress_' . $ajax_call, array( $this, $ajax_call ) );
				}
			}
		}

		/**
		 * Update Attempts status with respect to the Bulk Action.
		 * Will return the response in JSON format.
		 *
		 * @since 3.0.0
		 */
		public function attempts_bulk() {

			check_ajax_referer( 'loginpress-llla-bulk-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}

			$bulk_action = isset( $_POST['bulk_action'] ) && ! empty( $_POST['bulk_action'] ) ? sanitize_text_field( wp_unslash( $_POST['bulk_action'] ) ) : '';
			$bulk_ips    = isset( $_POST['bulk_ips'] ) && is_array( $_POST['bulk_ips'] ) ? map_deep( wp_unslash( $_POST['bulk_ips'] ), 'sanitize_text_field' ) : array();

			$action = sanitize_text_field( $bulk_action );
			$ips    = array_unique( $bulk_ips );

			switch ( $action ) {
				case 'unlock':
					foreach ( $ips as $ip ) :
						$this->llla_delete_meta( $ip );
					endforeach;
					break;
				case 'white_list':
					foreach ( $ips as $ip ) :
						$this->llla_update_meta( 'whitelist', $ip );
					endforeach;
					break;
				case 'black_list':
					foreach ( $ips as $ip ) :
						$this->llla_update_meta( 'blacklist', $ip );
					endforeach;
					break;
			}

			wp_send_json_success(
				array(
					'message'     => __( 'IP status updated.', 'loginpress-pro' ),
					'updated_ips' => $ips,
				)
			);
			wp_die();
		}

		/**
		 * Delete/Clear all whitelisted IPs.
		 * Will return the response in JSON format.
		 *
		 * @since 3.0.0
		 */
		public function clear_all_whitelist() {

			check_ajax_referer( 'loginpress-llla-bulk-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}

			$bulk_action = isset( $_POST['bulk_action'] ) && ! empty( $_POST['bulk_action'] ) ? sanitize_text_field( wp_unslash( $_POST['bulk_action'] ) ) : '';
			$bulk_ips    = isset( $_POST['bulk_ips'] ) && is_array( $_POST['bulk_ips'] ) ? map_deep( wp_unslash( $_POST['bulk_ips'] ), 'sanitize_text_field' ) : array();

			$action = sanitize_text_field( $bulk_action );
			$ips    = array_unique( $bulk_ips );

			if ( $ips ) {
				foreach ( $ips as $ip ) :
					$this->llla_delete_meta( $ip );
				endforeach;
			}

			wp_send_json_success(
				array(
					'message'     => __( 'IP status updated.', 'loginpress-pro' ),
					'updated_ips' => $ips,
				)
			);
			wp_die();
		}

		/**
		 * Delete/Clear all blacklisted IPs.
		 * Will return the response in JSON format.
		 *
		 * @since 3.0.0
		 */
		public function clear_all_blacklist() {

			check_ajax_referer( 'loginpress-llla-bulk-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}

			$bulk_action = isset( $_POST['bulk_action'] ) && ! empty( $_POST['bulk_action'] ) ? sanitize_text_field( wp_unslash( $_POST['bulk_action'] ) ) : '';
			$bulk_ips    = isset( $_POST['bulk_ips'] ) && is_array( $_POST['bulk_ips'] ) ? map_deep( wp_unslash( $_POST['bulk_ips'] ), 'sanitize_text_field' ) : array();

			$action = sanitize_text_field( $bulk_action );
			$ips    = array_unique( $bulk_ips );

			foreach ( $ips as $ip ) :
				$this->llla_delete_meta( $ip );
			endforeach;

			wp_send_json_success(
				array(
					'message'     => __( 'IP status updated.', 'loginpress-pro' ),
					'updated_ips' => $ips,
				)
			);
			wp_die();
		}

		/**
		 * Delete/Clear all attempt IPs.
		 * Will return the response in JSON format.
		 *
		 * @since 3.0.0
		 */
		public function clear_all_attempts() {

			check_ajax_referer( 'loginpress-llla-bulk-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}

			global $wpdb;
			$wpdb->query( $wpdb->prepare( "DELETE FROM `{$this->llla_table}` WHERE `blacklist` = 0 AND `whitelist` = 0" ) ); // @codingStandardsIgnoreLine.

			wp_send_json_success(
				array(
					'message' => __( 'IP status updated.', 'loginpress-pro' ),
				)
			);
			wp_die();
		}
		/**
		 * Move Attempted IP into Whitelist.
		 *
		 * @return void
		 * @since  3.0.0
		 */
		public function attempts_whitelist() {

			check_ajax_referer( 'loginpress-user-llla-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}

			$id = isset( $_POST['id'] ) && ! empty( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '';
			$ip = isset( $_POST['ip'] ) && ! empty( $_POST['ip'] ) ? filter_var( wp_unslash( $_POST['ip'] ), FILTER_VALIDATE_IP ) : '';

			$this->llla_update_meta( 'whitelist', $ip );
			wp_die();
		}

		/**
		 * Move Attempted IP into Blacklist.
		 *
		 * @return void
		 * @since  3.0.0
		 */
		public function attempts_blacklist() {

			check_ajax_referer( 'loginpress-user-llla-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}

			$id = isset( $_POST['id'] ) && ! empty( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '';
			$ip = isset( $_POST['ip'] ) && ! empty( $_POST['ip'] ) ? filter_var( wp_unslash( $_POST['ip'] ), FILTER_VALIDATE_IP ) : '';

			$this->llla_update_meta( 'blacklist', $ip );
			wp_die();
		}

		/**
		 * Remove Attempted IP from DataBase record.
		 *
		 * @since  3.0.0
		 */
		public function attempts_unlock() {

			check_ajax_referer( 'loginpress-user-llla-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}

			$id = isset( $_POST['id'] ) && ! empty( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '';
			$ip = isset( $_POST['ip'] ) && ! empty( $_POST['ip'] ) ? filter_var( wp_unslash( $_POST['ip'] ), FILTER_VALIDATE_IP ) : '';

			$this->llla_delete_meta( $ip );
			wp_die();
		}

		/**
		 * Remove Whitelisted IP from DataBase record.
		 *
		 * @since  3.0.0
		 */
		public function whitelist_clear() {

			check_ajax_referer( 'loginpress-user-llla-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}

			$ip = isset( $_POST['ip'] ) && ! empty( $_POST['ip'] ) ? filter_var( wp_unslash( $_POST['ip'] ), FILTER_VALIDATE_IP ) : '';

			$this->llla_delete_meta( $ip );
			echo esc_html__( 'Whitelist User Deleted', 'loginpress-pro' );
			wp_die();
		}

		/**
		 * Remove Blacklisted IP from DataBase record.
		 *
		 * @since  3.0.0
		 */
		public function blacklist_clear() {

			check_ajax_referer( 'loginpress-user-llla-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}

			$ip = isset( $_POST['ip'] ) && ! empty( $_POST['ip'] ) ? filter_var( wp_unslash( $_POST['ip'] ), FILTER_VALIDATE_IP ) : '';

			$this->llla_delete_meta( $ip );
			echo esc_html__( 'Blacklist User is Deleted', 'loginpress-pro' );
			wp_die();
		}

		/**
		 * Black list or White list an IP address Manually through settings page.
		 *
		 * @since  3.0.0
		 * @return void
		 */
		public function white_black_list_ip() {

			check_ajax_referer( 'ip_add_remove', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}

			global $wpdb;
			$current_time = current_time( 'timestamp' ); // @codingStandardsIgnoreLine.

			$ip     = isset( $_POST['ip'] ) && ! empty( $_POST['ip'] ) ? filter_var( wp_unslash( $_POST['ip'] ), FILTER_VALIDATE_IP ) : '';
			$action = isset( $_POST['ip_action'] ) && ! empty( $_POST['ip_action'] ) ? sanitize_text_field( wp_unslash( $_POST['ip_action'] ) ) : '';

			if ( empty( $ip ) ) {
				wp_send_json_error( array( 'message' => __( 'IP is required field.', 'loginpress-pro' ) ) );
			}

			if ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
				wp_send_json_error( array( 'message' => __( 'Your IP format is not correct.', 'loginpress-pro' ) ) );
			}

			$exist_record = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $this->llla_table WHERE ip = '%s' limit %d", $ip, 1 ) ); // @codingStandardsIgnoreLine.

			if ( 'white_list' === $action ) {

				if ( count( $exist_record ) && '1' !== $exist_record[0]->whitelist ) {

					$wpdb->query( $wpdb->prepare( "UPDATE `{$this->llla_table}` SET `whitelist` = '1' , `blacklist` = '0', `gateway` = 'Manually' WHERE ip = %s", $ip ) ); // @codingStandardsIgnoreLine.
					wp_send_json_success(
						array(
							'message' => __( 'IP Address already exist, Successfully moved from blacklist to whitelist.', 'loginpress-pro' ),
							'action'  => 'move_black_to_white',
						)
					);
				}

				if ( count( $exist_record ) < 1 ) {
					$wpdb->query( $wpdb->prepare( "INSERT INTO {$this->llla_table} (ip,whitelist,datentime,gateway) values (%s,%s,%s,%s)", $ip, '1', $current_time, 'Manually' ) ); // @codingStandardsIgnoreLine.
					wp_send_json_success(
						array(
							'message' => __( 'Successfully IP Address added in whitelist.', 'loginpress-pro' ),
							'action'  => 'new_whitelist',
						)
					);
				}

				wp_send_json_success(
					array(
						'message' => __( 'IP Address already exists in whitelist.', 'loginpress-pro' ),
						'action'  => 'already_whitelist',
					)
				);

			}
			if ( 'black_list' === $action ) {

				if ( count( $exist_record ) && '1' !== $exist_record[0]->blacklist ) {

					$wpdb->query( $wpdb->prepare( "UPDATE `{$this->llla_table}` SET `whitelist` = '0' , `blacklist` = '1', `gateway` = 'Manually' WHERE ip = %s", $ip ) ); // @codingStandardsIgnoreLine.
					wp_send_json_success(
						array(
							'message' => __( 'IP Address already exist, Successfully moved from whitelist to blacklist.', 'loginpress-pro' ),
							'action'  => 'move_white_to_black',
						)
					);
				}

				if ( count( $exist_record ) < 1 ) {
					$wpdb->query( $wpdb->prepare( "INSERT INTO {$this->llla_table} (ip,blacklist,datentime,gateway) values (%s,%s,%s,%s)", $ip, '1', $current_time, 'Manually' ) ); // @codingStandardsIgnoreLine.
					wp_send_json_success(
						array(
							'message' => __( 'Successfully IP Address added in blacklist.', 'loginpress-pro' ),
							'action'  => 'new_blacklist',
						)
					);
				}

				wp_send_json_success(
					array(
						'message' => __( 'IP Address already exists in blacklist.', 'loginpress-pro' ),
						'action'  => 'already_blacklist',
					)
				);

			}
			wp_die();
		}
		/**
		 * Get whitelist records.
		 *
		 * @return void
		 */
		public function white_list_records() {

			check_ajax_referer( 'ip_add_remove', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}

			global $wpdb;
			$mywhitelist = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT ip,whitelist FROM {$this->llla_table} WHERE `whitelist` = %d", 1 ) ); // @codingStandardsIgnoreLine.
			$html        = '';
			if ( $mywhitelist ) {

				wp_create_nonce( 'loginpress-user-llla-nonce' );
				foreach ( $mywhitelist as $whitelist ) {

					$html .= '<tr>';
					$html .= '<td class="loginpress_limit_login_whitelist_ips" data-whitelist-ip="' . $whitelist->ip . '"><div class="lp-tbody-cell">' . $whitelist->ip . '</div></td>';
					$html .= '<td class="loginpress_limit_login_whitelist_actions"><div class="lp-tbody-cell"><button class="loginpress-whitelist-clear button button-primary" type="button" value="Clear" ></button></div></td>';
					$html .= '</tr>';
				}
				wp_send_json_success( array( 'tbody' => $html ) );
			} else {
				wp_send_json_error( array( 'message' => 'record not found' ) );
			}
		}

		/**
		 * Get blacklist records.
		 *
		 * @return void
		 */
		public function black_list_records() {

			check_ajax_referer( 'ip_add_remove', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}

			global $wpdb;

			$myblacklist = $wpdb->get_results( $wpdb->prepare( 'SELECT DISTINCT ip,blacklist FROM %1s WHERE `blacklist` = %d', $this->llla_table, 1 ) ); // @codingStandardsIgnoreLine.

			if ( $myblacklist ) {

				$html = '';
				foreach ( $myblacklist as $blacklist ) {
					$html .= '<tr>';
					$html .= '<td class="loginpress_limit_login_blacklist_ips" data-blacklist-ip="' . $blacklist->ip . '"><div class="lp-tbody-cell">' . $blacklist->ip . '</div></td>';
					$html .= '<td class="loginpress_limit_login_blacklist_actions"><div class="lp-tbody-cell"><button class="loginpress-blacklist-clear button button-primary" type="button" value="Clear" ></button></div></td>';
					$html .= '</tr>';
				}
				wp_send_json_success( array( 'tbody' => $html ) );
			} else {
				wp_send_json_error( array( 'message' => __( 'record not found', 'loginpress-pro' ) ) );
			}
		}

		/**
		 * Update query.
		 *
		 * @param string $column Name of the column.
		 * @param string $ip IP Address.
		 * @since  3.0.0
		 */
		public function llla_update_meta( $column, $ip ) {

			if ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) :
				return __( 'Your IP format is not correct.', 'loginpress-pro' );
			endif;

			global $wpdb;
			$wpdb->query( $wpdb->prepare( "UPDATE `{$this->llla_table}` SET `{$column}` = '1' WHERE `ip` = %s", $ip ) ); // @codingStandardsIgnoreLine.
		}

		/**
		 * Delete query.
		 *
		 * @param string $ip IP Address.
		 * @since 3.0.0
		 */
		public function llla_delete_meta( $ip ) {

			if ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) :
				return __( 'Your IP format is not correct.', 'loginpress-pro' );
			endif;

			global $wpdb;
			$wpdb->query( $wpdb->prepare( "DELETE FROM `{$this->llla_table}` WHERE `ip` = %s", $ip ) ); // @codingStandardsIgnoreLine.
		}
	}

endif;
new LoginPress_Attempts_AJAX();
