<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://miniorange.com
 * @since      1.0.0
 *
 * @package    exam-and-quiz-online-proctoring-with-lms-integration
 * @subpackage exam-and-quiz-online-proctoring-with-lms-integration/public
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Proctoring_For_Lms_Public' ) ) {

	/**
	 * The public-facing functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the public-facing stylesheet and JavaScript.
	 *
	 * @package    exam-and-quiz-online-proctoring-with-lms-integration
	 * @subpackage exam-and-quiz-online-proctoring-with-lms-integration/public
	 * @author     miniOrange <info@xecurify.com>
	 */
	class Proctoring_For_Lms_Public {

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
		 * @param    string $plugin_name     The name of the plugin.
		 * @param    string $version         The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

		}


		/**
		 * This function handles live stream.js and tabswitch ajax calls.
		 *
		 * @return void
		 */
		public function mo_procto_action() {

			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'mo-procto-action-nonce' ) ) {
				wp_send_json( 'invalid nonce' );
			}

			if ( isset( $_POST['is_tab_switch'] ) ) {
				$quiz_id = isset( $_POST['quiz_id'] ) ? sanitize_text_field( wp_unslash( $_POST['quiz_id'] ) ) : '';

				$key = 'tab_switch_' . $quiz_id;

				$user_id = wp_get_current_user()->ID;

				$count = get_user_meta( $user_id, $key, $single = true );
				$count = empty( $count ) ? 1 : $count + 1;
				update_user_meta( $user_id, $key, $count );
				wp_send_json_success();
			}

			if ( isset( $_POST['live_stream'] ) ) {

				$quiz_id = isset( $_POST['quiz_id'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['quiz_id'] ) ) : '';

				$key     = 'mo_procto_live_stream_id_' . $quiz_id;
				$user_id = wp_get_current_user()->ID;

				$stream_id = isset( $_POST['stream_id'] ) ? sanitize_text_field( wp_unslash( $_POST['stream_id'] ) ) : '';
				update_user_meta( $user_id, $key, $stream_id );
				wp_send_json( 'Saved' );
			}
		}

		/**
		 * Session limit handler.
		 *
		 * @param object $user        user object.
		 * @param string $username    username.
		 * @param string $password    password.
		 * @return string
		 */
		public function mo_procto_check_session_limit( $user, $username, $password ) {

			if ( get_site_option( 'mo_procto_restrict_session' ) && ! is_wp_error( $user ) ) {

				$session_allowed = get_site_option( 'mo_procto_restrict_session' );
				$session_details = WP_Session_Tokens::get_instance( $user->ID );
				$session_count   = count( $session_details->get_all() );

				if ( $session_count >= (int) $session_allowed ) {
					if ( get_site_option( 'mo_procto_max_limit_action' ) ) {
						$session_details->destroy_all();
					} else {
						return new WP_Error( 'authentication_failed', __( '<strong>ERROR</strong>: You have reached the maximum session limit. Please logout from other devices.' ) );
					}
				}
			}
			return $user;
		}


		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {
			$current_lms = new LMSFactory();
			$current_lms = $current_lms->selectedlms( get_site_option( 'mo_procto_select_lms' ) );
			if ( is_user_logged_in() && $current_lms && $current_lms->mo_procto_check_quiz() ) {
				if ( get_site_option( 'mo_procto_restrict_tab_switch' ) ) {
					wp_enqueue_style( 'mo_procto_restrict_tab_css', plugin_dir_url( __FILE__ ) . 'css/proctoring-for-lms-public.min.css', array(), $this->version, 'all' );
				}
			}

		}

		/**
		 * Register the JavaScript for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {

			$current_lms = new LMSFactory();
			$current_lms = $current_lms->selectedlms( get_site_option( 'mo_procto_select_lms' ) );
			if ( $current_lms ) {
				$quiz_id = $current_lms->mo_procto_check_quiz();
			} else {
				$quiz_id = null;
			}

			if ( is_user_logged_in() && $quiz_id ) {

				include plugin_dir_path( __FILE__ ) . 'partials' . DIRECTORY_SEPARATOR . 'mo-procto-start-exam.php';
				wp_enqueue_script( 'mo_procto_start_exam_script', plugin_dir_url( __FILE__ ) . 'js/mo-procto-start-exam.min.js', array( 'jquery' ), $this->version, false );
				wp_enqueue_script( 'mo_procto_peerjs_script', plugin_dir_url( __FILE__ ) . 'js/peerjs.min.js', array( 'jquery' ), $this->version, false );
				wp_enqueue_script( 'mo_procto_live_video_script', plugin_dir_url( __FILE__ ) . 'js/mo-procto-live-video.min.js', array( 'jquery' ), $this->version, false );
				wp_localize_script(
					'mo_procto_live_video_script',
					'mo_procto_ajax_object',
					array(
						'mo_procto_ajax_url' => admin_url( 'admin-ajax.php' ),
						'nonce'              => wp_create_nonce( 'mo-procto-action-nonce' ),

					)
				);

				if ( get_site_option( 'mo_procto_restrict_tab_switch' ) ) {

					wp_enqueue_script( 'mo_procto_admin_settings_script', plugin_dir_url( __FILE__ ) . 'js/mo_procto_restrict_page.min.js', array( 'jquery' ), $this->version, false );
					wp_localize_script(
						'mo_procto_admin_settings_script',
						'mo_procto_ajax_object',
						array(
							'mo_procto_ajax_url'     => admin_url( 'admin-ajax.php' ),
							'nonce'                  => wp_create_nonce( 'mo-procto-action-nonce' ),
							'quiz_id_LD'             => $quiz_id,
							'mo_procto_selected_lms' => get_site_option( 'mo_procto_select_lms' ),
						)
					);

				}
				if ( get_site_option( 'mo_procto_disable_mouse_right_click' ) ) {
					wp_enqueue_script( 'mo_procto_restrict_right_button_script', plugin_dir_url( __FILE__ ) . 'js/mo_procto_restrict_right_button.min.js', array( 'jquery' ), $this->version, false );
				}

				if ( get_site_option( 'mo_procto_restrict_inspect_browser' ) ) {
					wp_enqueue_script( 'mo_procto_restrict_inspect_script', plugin_dir_url( __FILE__ ) . 'js/mo_procto_restrict_inspect.min.js', array( 'jquery' ), $this->version, false );
				}
			}
		}
	}
}
