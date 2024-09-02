<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://miniorange.com
 * @since      1.0.0
 *
 * @package    exam-and-quiz-online-proctoring-with-lms-integration
 * @subpackage exam-and-quiz-online-proctoring-with-lms-integration/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Proctoring_For_Lms_Admin' ) ) {
	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the admin-specific stylesheet and JavaScript.
	 *
	 * @package    exam-and-quiz-online-proctoring-with-lms-integration
	 * @subpackage exam-and-quiz-online-proctoring-with-lms-integration/admin
	 * @author     miniOrange <info@xecurify.com>
	 */
	class Proctoring_For_Lms_Admin {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $plugin_name    The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $version    The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param      string $plugin_name       The name of this plugin.
		 * @param      string $version    The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;
		}

		/**
		 * Adds submenu.
		 *
		 * @return void
		 */
		public function mo_procto_widget_menu() {
			$iconurl = plugin_dir_url( __FILE__ ) . 'images/miniorange_icon.png';
			add_menu_page( 'miniOrange Proctoring', 'miniOrange Proctoring', 'administrator', 'mo_procto_lms', array( $this, 'mo_procto' ), $iconurl );
		}

		/**
		 * This function will save settings
		 *
		 * @return void
		 */
		public function mo_procto() {
			if ( isset( $_POST ) ) {
				if ( ! empty( $_POST ) && check_admin_referer( 'mo-procto-save-settings-nonce' ) ) {
					// select LMS.
					if ( isset( $_POST['mo_procto_select_lms'] ) ) {
						update_site_option( 'mo_procto_select_lms', sanitize_text_field( wp_unslash( $_POST['mo_procto_select_lms'] ) ) );
					}

					// tab.
					if ( isset( $_POST['restrict_tab_switch'] ) ) {
						update_site_option( 'mo_procto_restrict_tab_switch', 1 );
					} else {
						update_site_option( 'mo_procto_restrict_tab_switch', 0 );
					}

					// session.
					if ( isset( $_POST['mo_procto_restrict_session'] ) ) {
						$max_sessions = 1;
						if ( isset( $_POST['mo_procto_max_restrict_session'] ) ) {
							$max_sessions = max( $max_sessions, (int) sanitize_text_field( wp_unslash( $_POST['mo_procto_max_restrict_session'] ) ) );
						}
						if ( isset( $_POST['mo_procto_max_session_action'] ) ) {
							update_site_option( 'mo_procto_max_limit_action', sanitize_text_field( wp_unslash( $_POST['mo_procto_max_session_action'] ) ) );
						}
						update_site_option( 'mo_procto_restrict_session', $max_sessions );
					} else {
						update_site_option( 'mo_procto_restrict_session', 0 );
					}

					// other.
					isset( $_POST['restrict_inspect_browser'] ) ? update_site_option( 'mo_procto_restrict_inspect_browser', 1 ) : update_site_option( 'mo_procto_restrict_inspect_browser', 0 );
					isset( $_POST['disable_mouse_right_button'] ) ? update_site_option( 'mo_procto_disable_mouse_right_click', 1 ) : update_site_option( 'mo_procto_disable_mouse_right_click', 0 );

					do_action( 'procto_show_message', 'Settings saved.', 'SUCCESS' );
				}
			}

			include plugin_dir_path( __FILE__ ) . 'partials' . DIRECTORY_SEPARATOR . 'mo-procto-dashboard.php';
		}
		/**
		 * This function will view reports
		 *
		 * @return void
		 */
		public function mo_procto_view_report() {

			if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'mo-procto-ajax-nonce' ) ) {
				if ( isset( $_POST['live_stream'] ) ) {

					$quiz_id = isset( $_POST['quiz_id'] ) ? sanitize_text_field( wp_unslash( $_POST['quiz_id'] ) ) : '';

					$users = get_users(
						array(
							'meta_key' => 'mo_procto_live_stream_id_' . $quiz_id, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Ignoring warning regarding slow database query.
						)
					);
					$key   = 'mo_procto_live_stream_id_' . $quiz_id;
					$data  = array();
					foreach ( $users as $user ) {
						$data [ $user->ID ]['id']   = $user->$key;
						$data [ $user->ID ]['name'] = $user->data->user_nicename;

					}
					wp_send_json( $data );

				}
			} else {
				wp_send_json( 'Something went wrong.' );
			}
		}
		/**
		 * This function handles support form submission
		 */
		public function mo_procto_support() {
			if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'mo-procto-save-settings-nonce' ) ) {
				$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
				$query   = isset( $_POST['query'] ) ? sanitize_text_field( wp_unslash( $_POST['query'] ) ) : '';
				$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
				$lms     = isset( $_POST['lms'] ) ? sanitize_text_field( wp_unslash( $_POST['lms'] ) ) : '';
				$subject = 'Query for Proctoring for LMS Plugin - ' . $email;
				$config  = isset( $_POST['enableconfig'] ) ? sanitize_text_field( wp_unslash( $_POST['enableconfig'] ) ) : '';

				if ( 'true' === $config ) {
					$query .= $this->mo_procto_send_configuration();
				}

				if ( '' !== $email && '' !== $query ) {
					$curl_obj = new Proctoring_For_Lms_Curl();

					$submitted = json_decode( $curl_obj->submit_contact_us( $email, $phone, $query, $subject, $lms ), true );

					if ( json_last_error() === JSON_ERROR_NONE && $submitted ) {
						wp_send_json( 'success' );
					} else {
						wp_send_json( 'error' );
					}
				} else {
					wp_send_json( 'error' );
				}
			}
		}

		/**
		 * This function handles feedback form submission
		 *
		 * @return void
		 */
		public function mo_procto_feedback_request() {
			if ( isset( $_SERVER['PHP_SELF'] ) && 'plugins.php' !== basename( sanitize_text_field( wp_unslash( $_SERVER['PHP_SELF'] ) ) ) ) {
				return;
			}

			$email = get_option( 'admin_email' );
			if ( empty( $email ) ) {
				$user  = wp_get_current_user();
				$email = $user->user_email;
			}

			wp_enqueue_style( 'wp-pointer' );
			wp_enqueue_script( 'wp-pointer' );
			wp_enqueue_script( 'utils' );

			wp_enqueue_style( 'mo_procto_feedback_css', plugin_dir_url( __FILE__ ) . 'css/mo-procto-feedback.min.css', array(), $this->version, 'all' );

			include plugin_dir_path( __FILE__ ) . 'partials' . DIRECTORY_SEPARATOR . 'mo-procto-feedback.php';
		}

		/**
		 * This function triggers on Feedback action
		 */
		public function mo_procto_feedback_actions() {
			if ( ! empty( $_POST ) && current_user_can( 'manage_options' ) && isset( $_POST['option'] ) && isset( $_POST['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'mo-procto-feedback-nonce' ) ) {
				switch ( sanitize_text_field( wp_unslash( $_POST['option'] ) ) ) {
					case 'mo_procto_skip_feedback':
					case 'mo_procto_feedback':
						$this->mo_procto_handle_feedback( $_POST );
						break;
				}
			}
		}

		/**
		 * This function handles Feedback form data
		 *
		 * @param array $postdata    It contains feedback form data.
		 * @return void
		 */
		public function mo_procto_handle_feedback( $postdata ) {
			if ( MO_TEST_MODE_PROCTORING ) {
				deactivate_plugins( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'proctoring-for-lms.php' );
				return;
			}

			$user            = wp_get_current_user();
			$feedback_option = sanitize_text_field( $postdata['option'] );
			$message         = 'Plugin Deactivated';

			if ( 'mo_procto_skip_feedback' === $feedback_option ) {
				deactivate_plugins( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'proctoring-for-lms.php' );
				wp_safe_redirect( admin_url( 'plugins.php' ) );
				return;
			}

			$deactivation_reason = isset( $postdata['mo_proctoring_feedback'] ) ? sanitize_text_field( $postdata['mo_proctoring_feedback'] ) : 'NA';

			if ( 'other' === $deactivation_reason || 'specific_feature' === $deactivation_reason ) {
				$deactivate_reason_message = '[' . $deactivation_reason . ']-' . sanitize_text_field( $postdata['mo_procto_query_feedback'] );
			} else {
				$deactivate_reason_message = $deactivation_reason;
			}

			$activation_date = get_site_option( 'mo_procto_activated_time' );
			$current_date    = time();
			$diff            = $activation_date - $current_date;
			$days            = ( false === $activation_date ) ? 'NA' : abs( round( $diff / 86400 ) );
			$reply_required  = '';
			if ( isset( $postdata['get_reply'] ) ) {
				$reply_required = htmlspecialchars( sanitize_text_field( $postdata['get_reply'] ) );
			}
			if ( empty( $reply_required ) ) {
				$message .= '[Reply: yes  ';
			} else {
				$message .= ' &nbsp; [Reply:<b style="color:red";>' . " don't reply  " . '</b> ';
			}

			$message .= ' D:' . esc_html( $days );

			$message .= '    Feedback : ' . esc_html( $deactivate_reason_message ) . '';

			$send_config = htmlspecialchars( sanitize_text_field( $postdata['get_config'] ) );

			if ( ! empty( $send_config ) ) {
				$message .= $this->mo_procto_send_configuration();
			}

			$email = isset( $postdata['query_mail'] ) ? sanitize_email( $postdata['query_mail'] ) : '';
			if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				$email = get_site_option( 'email' );
				if ( empty( $email ) ) {
					$email = $user->user_email;
				}
			}
			$phone = get_option( 'mo_procto_admin_phone' );
			if ( ! $phone ) {
				$phone = '';
			}
			$feedback_reasons = new Proctoring_For_Lms_Curl();
			if ( ! is_null( $feedback_reasons ) ) {
				if ( ! in_array( 'curl', get_loaded_extensions(), true ) ) {
					deactivate_plugins( dirname( dirname( __FILE__ ) ) . '\\proctoring-for-lms.php' );
					wp_safe_redirect( 'plugins.php' );
					exit();
				} else {
					$submitted = json_decode( $feedback_reasons->send_email_alert( $email, $phone, $message, $feedback_option ), true );
					if ( json_last_error() === JSON_ERROR_NONE ) {
						if ( is_array( $submitted ) && array_key_exists( 'status', $submitted ) && 'ERROR' === $submitted['status'] ) {
							do_action( 'procto_show_message', $submitted['message'], 'ERROR' );
						} elseif ( false === $submitted ) {
								do_action( 'procto_show_message', 'Error while submitting the query.', 'ERROR' );
						}
					}
					deactivate_plugins( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'proctoring-for-lms.php' );
					do_action( 'procto_show_message', 'Thank you for the feedback.', 'SUCCESS' );
					wp_safe_redirect( admin_url( 'plugins.php' ) );
					exit;
				}
			}
		}

		/**
		 * Function to prompt messages
		 *
		 * @param string $content   error message.
		 * @param string $type      error type.
		 * @return void
		 */
		public function mo_procto_show_message( $content, $type ) {

			if ( 'NOTICE' === $type ) {
				echo '<div class="is-dismissible notice notice-warning"> <p>' . esc_html( $content ) . '</p> </div>';
			}
			if ( 'ERROR' === $type ) {
				echo '<div class="notice notice-error is-dismissible"> <p>' . esc_html( $content ) . '</p> </div>';
			}
			if ( 'SUCCESS' === $type ) {
				echo '<br><div class="notice notice-success is-dismissible"> <p>' . esc_html( $content ) . '</p> </div><br>';
			}
		}

		/**
		 * Sends the plugin configurations.
		 *
		 * @return string
		 */
		public function mo_procto_send_configuration() {
			$space                = '<span>&nbsp;&nbsp;&nbsp;</span>';
			$specific_plugins     = array(
				'SFWD_LMS'                            => 'LearnDash',
				'STM_LMS_Nav_Menu_Item_Custom_Fields' => 'MasterStudy',
			);
			$plugin_configuration = '<br><br><I>Plugin Configuration :-</I>' . $space . ( is_multisite() ? 'Multisite : Yes' : 'Single-site : Yes' );
			foreach ( $specific_plugins as $class_name => $plugin_name ) {
				if ( class_exists( $class_name ) ) {
					$plugin_configuration = $plugin_configuration . $space . 'Installed LMS :<b>' . $plugin_name . '</b>';
				}
			}
			$result                = count_users();
			$selected_lms          = $space . 'LMS:' . get_site_option( 'mo_procto_select_lms' );
			$session_restriction   = $space . 'Session-Res:' . get_site_option( 'mo_procto_restrict_session' );
			$restrict_tab_switch   = $space . 'Tab-Sw:' . get_site_option( 'mo_procto_restrict_tab_switch' );
			$mouse_right_click     = $space . 'Mouse-Clk:' . get_site_option( 'mo_procto_disable_mouse_right_click' );
			$inspect_browser       = $space . 'Inspect-Brow:' . get_site_option( 'mo_procto_restrict_inspect_browser' );
			$plugin_configuration .= $space . 'Total users: [' . $result['total_users'] . ']' . $space . $selected_lms . $space . $session_restriction . $space . $restrict_tab_switch . $space . $mouse_right_click . $space . $inspect_browser;

			$plugin_configuration .= $space . 'PHP_version : ' . phpversion();

			return $plugin_configuration;
		}

		/**
		 * Register the stylesheets for the admin-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles( $page ) {
			if ( strcasecmp( $page, 'toplevel_page_mo_procto_lms' ) !== 0 ) {
				return;
			}
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/proctoring-for-lms-admin.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'mo_procto_phone', plugin_dir_url( __FILE__ ) . 'css/mo-procto-phone.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'mo_procto_font', 'https://fonts.googleapis.com/css?family=Poppins', array(), $this->version, 'all' );
			wp_enqueue_style( 'mo_procto_bootstrap_css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'mo_procto_bootstrap_icons_css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.icon.min.css', array(), $this->version, 'all' );
		}

		/**
		 * Register the scripts for the admin-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {
			wp_enqueue_script( 'mo_procto_bootstrap_js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'mo-procto-admin-peerjs-script', plugin_dir_url( __FILE__ ) . 'js/peerjs.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/proctoring-for-lms-admin.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'mo-procto-phone-js', plugin_dir_url( __FILE__ ) . 'js/mo-procto-phone.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'mo-procto-ajax-handler', plugin_dir_url( __FILE__ ) . 'js/mo-procto-ajax-handler.min.js', array( 'jquery' ), $this->version, false );
			wp_localize_script(
				'mo-procto-ajax-handler',
				'mo_procto_ajax_object',
				array(
					'mo_procto_ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'              => wp_create_nonce( 'mo-procto-ajax-nonce' ),
				)
			);
		}
	}
}
