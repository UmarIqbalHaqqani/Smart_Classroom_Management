<?php
/**
 * Addon Name: LoginPress - Login Widget
 * Description: LoginPress - Login widget is the best Login plugin by <a href="https://wpbrigade.com/">WPBrigade</a> which allows you to login from front end.
 *
 * @package loginPress
 * @category Core
 * @author WPBrigade
 * @version 3.0.0
 */

if ( ! class_exists( 'LoginPress_Login_Widget' ) ) :

	/**
	 * LoginPress_Login_Widget
	 */
	final class LoginPress_Login_Widget {

		/**
		 * Class constructor
		 *
		 * @since 1.0.0
		 * @version 3.0.0
		 */
		public function __construct() {

			if ( LoginPress_Pro::addon_wrapper( 'login-widget' ) ) {
				$this->hooks();
				$this->define_constants();
			}
		}

		/**
		 * Hook into actions and filters.
		 *
		 * @since 3.0.0
		 */
		public function hooks() {

			add_action( 'init', array( $this, 'social_login' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'widget_script' ) );
			add_action( 'widgets_init', array( $this, 'register_widget' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			// add_action( 'admin_init', array( $this, 'init_addon_updater' ), 0 );
			add_action( 'wp_ajax_loginpress_widget_login_process', array( $this, 'loginpress_widget_ajax' ) );
			add_action( 'wp_ajax_nopriv_loginpress_widget_login_process', array( $this, 'loginpress_widget_ajax' ) );
		}

		/**
		 * Compatibility of LoginPress - Social Login with Widget Login
		 *
		 * @since 3.0.0
		 * @return string $html Social login Buttons
		 */
		public function loginpress_social_login() {

			$redirect_to = isset( $_REQUEST['redirect_to'] ) ? wp_unslash( $_REQUEST['redirect_to'] ) : ''; // @codingStandardsIgnoreLine.
			$encoded_url = rawurlencode( $redirect_to );

			$settings = get_option( 'loginpress_social_logins' );

			$html  = '';
			$html .= "<div class='social-networks block'>";
			$html .= "<span class='social-sep'><span>" . __( 'or', 'loginpress-pro' ) . '</span></span>';

			if ( isset( $settings['gplus'] ) && 'on' === $settings['gplus'] && ! empty( $settings['gplus_client_id'] ) && ! empty( $settings['gplus_client_secret'] ) ) :
				$html .= '<a href="' . wp_login_url() . '?lpsl_login_id=gplus_login';
				if ( $encoded_url ) {
					$html .= '&state=' . base64_encode( 'redirect_to=' . $encoded_url ); // @codingStandardsIgnoreLine.
				}
				$html .= '" title="' . __( 'Login with Google', 'loginpress-pro' ) . '">';
				$html .= '<div class="lpsl-icon-block icon-google-plus clearfix">';
				$html .= '<span class="lpsl-login-text">' . __( 'Login with Google', 'loginpress-pro' ) . '</span>';
				$html .= '<div class="lpsl-icon-block icon-google-plus clearfix">
				<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 48 48" class="abcRioButtonSvg lpsl-google-svg"><g><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path><path fill="none" d="M0 0h48v48H0z"></path></g></svg>
				</div>';
				$html .= '</div>';
				$html .= '</a>';
			endif;

			if ( isset( $settings['facebook'] ) && 'on' === $settings['facebook'] && ! empty( $settings['facebook_app_id'] ) && ! empty( $settings['facebook_app_secret'] ) ) :
				$html .= '<a href="' . wp_login_url() . '?lpsl_login_id=facebook_login';
				if ( $encoded_url ) {
					$html .= '&state=' . base64_encode( 'redirect_to=' . $encoded_url ); // @codingStandardsIgnoreLine.
				}
				$html .= '" title="' . __( 'Login with Facebook', 'loginpress-pro' ) . '">';
				$html .= '<div class="lpsl-icon-block icon-facebook clearfix">';
				$html .= '<span class="lpsl-login-text">' . __( 'Login with Facebook', 'loginpress-pro' ) . '</span>';
				$html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="#43609c" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>';
				$html .= '</div>';
				$html .= '</a>';
			endif;

			if ( isset( $settings['twitter'] ) && 'on' === $settings['twitter'] && ! empty( $settings['twitter_oauth_token'] ) && ! empty( $settings['twitter_token_secret'] ) ) :
				$html .= '<a href="' . wp_login_url() . '?lpsl_login_id=twitter_login';
				if ( $encoded_url ) {
					$html .= '&state=' . base64_encode( 'redirect_to=' . $encoded_url ); // @codingStandardsIgnoreLine.
				}
				$html .= '" title="' . __( 'Login with Twitter', 'loginpress-pro' ) . '">';
				$html .= '<div class="lpsl-icon-block icon-twitter clearfix">';
				$html .= '<span class="lpsl-login-text">' . __( 'Login with Twitter', 'loginpress-pro' ) . '</span>';
				$html .= '<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 30 30" width="30px" height="30px"><path d="M26.37,26l-8.795-12.822l0.015,0.012L25.52,4h-2.65l-6.46,7.48L11.28,4H4.33l8.211,11.971L12.54,15.97L3.88,26h2.65 l7.182-8.322L19.42,26H26.37z M10.23,6l12.34,18h-2.1L8.12,6H10.23z"/></svg>';
				$html .= '</div>';
				$html .= '</a>';
			endif;

			if ( isset( $settings['linkedin'] ) && 'on' === $settings['linkedin'] && ! empty( $settings['linkedin_client_id'] ) && ! empty( $settings['linkedin_client_secret'] ) ) :
				$html .= '<a href="' . wp_login_url() . '?lpsl_login_id=linkedin_login';
				if ( $encoded_url ) {
					$html .= '&state=' . base64_encode( 'redirect_to=' . $encoded_url ); // @codingStandardsIgnoreLine.
				}
				$html .= '" title="' . __( 'Login with LinkedIn', 'loginpress-pro' ) . '">';
				$html .= '<div class="lpsl-icon-block icon-linkdin clearfix">';
				$html .= '<span class="lpsl-login-text">' . __( 'Login with LinkedIn', 'loginpress-pro' ) . '</span>';
				$html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="#0076b4" d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>';
				$html .= '</div>';
				$html .= '</a>';
			endif;
			if ( isset( $settings['microsoft'] ) && 'on' === $settings['microsoft'] && ! empty( $settings['microsoft_app_id'] ) && ! empty( $settings['microsoft_app_secret'] ) ) :
				$html .= '<a href="' . wp_login_url() . '?lpsl_login_id=microsoft_login';
				if ( $encoded_url ) {
					$html .= '&state=' . base64_encode( 'redirect_to=' . $encoded_url ); // @codingStandardsIgnoreLine.
				}

				$html .= '" title="' . esc_html__( 'Login with Microsoft', 'loginpress-pro' ) . '">';
				$html .= '<div class="lpsl-icon-block icon-microsoft clearfix">';
				$html .= '<span class="lpsl-login-text">' . esc_html__( 'Login with Microsoft', 'loginpress-pro' ) . '</span>';
				$html .= '<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" width="48px" height="48px"><path fill="#ff5722" d="M6 6H22V22H6z" transform="rotate(-180 14 14)"/><path fill="#4caf50" d="M26 6H42V22H26z" transform="rotate(-180 34 14)"/><path fill="#ffc107" d="M26 26H42V42H26z" transform="rotate(-180 34 34)"/><path fill="#03a9f4" d="M6 26H22V42H6z" transform="rotate(-180 14 34)"/></svg>';
				$html .= '</div>';
				$html .= '</a>';
			endif;

			return $html;
		}

		/**
		 * LoginPress Addon updater
		 */
		public function init_addon_updater() {

			if ( class_exists( 'LoginPress_AddOn_Updater' ) ) {
				$updater = new LoginPress_AddOn_Updater( 2333, __FILE__, $this->version );
			}
		}

		/**
		 * Add social logins
		 *
		 * @version 3.0.0
		 */
		public function social_login() {

			if ( class_exists( 'LoginPress_Social' ) && true === apply_filters( 'loginpress_social_widget', true ) ) {

				if ( method_exists( 'LoginPress_Social', 'check_social_api_status' ) && true === LoginPress_Social::check_social_api_status() ) {
					add_filter( 'login_form_bottom', array( $this, 'loginpress_social_login' ), 1 );
				}
			}
		}

		/**
		 * Widget_script function.
		 *
		 * @access public
		 * @since 3.0.0
		 * @return void
		 */
		public function widget_script() {

			// Enqueue LoginPress Widget JS.
			wp_enqueue_script( 'loginpress-login-widget-script', plugins_url( 'assets/js/script.js', __FILE__ ), array( 'jquery' ), LOGINPRESS_PRO_VERSION, false );

			// Enqueue Styles.
			wp_enqueue_style( 'loginpress-login-widget-style', plugins_url( 'assets/css/style.css', __FILE__ ), '', LOGINPRESS_PRO_VERSION );

			if ( class_exists( 'LoginPress_Social' ) ) {
				wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
				wp_enqueue_style( 'loginpress-social-login', plugins_url( 'social-login/assets/css/login.css', __DIR__ ), array(), LOGINPRESS_PRO_VERSION );
			}

			$loginpress_widget_option  = get_option( 'widget_loginpress-login-widget' );
			$_loginpress_widget_option = isset( $loginpress_widget_option ) ? $loginpress_widget_option : false;
			if ( $_loginpress_widget_option ) {
				$error_bg_color = isset( $loginpress_widget_option[2]['error_bg_color'] ) ? $loginpress_widget_option[2]['error_bg_color'] : '#fbb1b7';

				$error_text_color = isset( $loginpress_widget_option[2]['error_text_color'] ) ? $loginpress_widget_option[2]['error_text_color'] : '#ae121e';

				$_loginpress_widget_error_bg_clr = "
                .loginpress-login-widget .loginpress_widget_error{
                  background-color: {$error_bg_color};
                  color: {$error_text_color};
                }";
				wp_add_inline_style( 'loginpress-login-widget-style', $_loginpress_widget_error_bg_clr );
			}

			$loginpress_key = get_option( 'loginpress_customization' ) ?: array(); //@codingStandardsIgnoreLine.

			/* Translators: For Invalid username. */
			$invalid_usrname = array_key_exists( 'incorrect_username', $loginpress_key ) && ! empty( $loginpress_key['incorrect_username'] ) ? $loginpress_key['incorrect_username'] : sprintf( __( '%1$sError:%2$s Invalid Username.', 'loginpress-pro' ), '<strong>', '</strong>' );

			/* Translators: For Invalid password. */
			$invalid_pasword = array_key_exists( 'incorrect_password', $loginpress_key ) && ! empty( $loginpress_key['incorrect_password'] ) ? $loginpress_key['incorrect_password'] : sprintf( __( '%1$sError:%2$s Invalid Password.', 'loginpress-pro' ), '<strong>', '</strong>' );

			/* Translators: If username field is empty. */
			$empty_username = array_key_exists( 'empty_username', $loginpress_key ) && ! empty( $loginpress_key['empty_username'] ) ? $loginpress_key['empty_username'] : sprintf( __( '%1$sError:%2$s The username field is empty.', 'loginpress-pro' ), '<strong>', '</strong>' );

			/* Translators: For empty password. */
			$empty_password = array_key_exists( 'empty_password', $loginpress_key ) && ! empty( $loginpress_key['empty_password'] ) ? $loginpress_key['empty_password'] : sprintf( __( '%1$sError:%2$s The password field is empty.', 'loginpress-pro' ), '<strong>', '</strong>' );

			/* Translators: For invalid email. */
			$invalid_email = array_key_exists( 'invalid_email', $loginpress_key ) && ! empty( $loginpress_key['invalid_email'] ) ? $loginpress_key['invalid_email'] : sprintf( __( '%1$sError:%2$s The email address isn\'t correct..', 'loginpress-pro' ), '<strong>', '</strong>' );

			// Pass variables.
			$loginpress_widget_params = array(
				'ajaxurl'          => admin_url( 'admin-ajax.php' ),
				'force_ssl_admin'  => force_ssl_admin() ? 1 : 0,
				'is_ssl'           => is_ssl() ? 1 : 0,
				'empty_username'   => $empty_username,
				'empty_password'   => $empty_password,
				'invalid_username' => $invalid_usrname,
				'invalid_password' => $invalid_pasword,
				'invalid_email'    => $invalid_email,
				'lp_widget_nonce'  => wp_create_nonce( 'loginpress_login_widget_security' ),
			);

			wp_localize_script( 'loginpress-login-widget-script', 'loginpress_widget_params', $loginpress_widget_params );

		}

		/**
		 * Register LoginPress Widget
		 *
		 * @since 3.0.0
		 */
		public function register_widget() {
			include_once LOGINPRESS_WIDGET_DIR_PATH . 'classes/class-loginpress-widget.php';
			register_widget( 'LoginPress_Widget' );
		}

		/**
		 * Define LoginPress AutoLogin Constants
		 *
		 * @version 3.0.0
		 */
		private function define_constants() {
			LoginPress_Pro_Init::define( 'LOGINPRESS_WIDGET_DIR_PATH', plugin_dir_path( __FILE__ ) );
		}

		/**
		 * Load JS or CSS files at admin side and enqueue them
		 *
		 * @param int $hook the page ID.
		 * @since 3.0.0
		 */
		public function admin_scripts( $hook ) {

			wp_enqueue_style( 'loginpress_widget_style', plugins_url( 'assets/css/style.css', __FILE__ ), array(), LOGINPRESS_PRO_VERSION );
			wp_enqueue_script( 'loginpress_widget_js', plugins_url( 'assets/js/script.js', __FILE__ ), array( 'jquery' ), LOGINPRESS_PRO_VERSION, false );
		}

		/**
		 * Retrieve the redirect URL w.r.t Login Redirect Add-On.
		 *
		 * @param int    $user_id User ID.
		 * @param string $option meta key name.
		 * @since 3.0.0
		 *
		 * @return string $redirect_url meta value of the user w.r.t key name.
		 */
		private function loginpress_redirect_url( $user_id, $option ) {
			if ( ! is_multisite() ) {
				$redirect_url = get_user_meta( $user_id, $option, true );
			} else {
				$redirect_url = get_user_option( $option, $user_id );
			}
			return $redirect_url;
		}

		/**
		 * LoginPress_widget_ajax function.
		 *
		 * @access public
		 * @since 3.0.0
		 * @return void
		 */
		public function loginpress_widget_ajax() {

			check_ajax_referer( 'loginpress_login_widget_security', 'nonce' );

			$data                  = array();
			$data['user_login']    = isset( $_POST['user_login'] ) ? sanitize_text_field( wp_unslash( $_POST['user_login'] ) ) : '';
			$data['user_password'] = isset( $_POST['user_password'] ) ? sanitize_text_field( wp_unslash( $_POST['user_password'] ) ) : '';
			$data['remember']      = isset( $_POST['remember'] ) ? sanitize_text_field( wp_unslash( $_POST['remember'] ) ) : '';
			$redirect_to           = isset( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : '';
			$secure_cookie         = null;

			// If the user wants ssl but the session is not ssl, force a secure cookie.
			if ( ! force_ssl_admin() ) {
				$user = is_email( $data['user_login'] ) ? get_user_by( 'email', $data['user_login'] ) : get_user_by( 'login', sanitize_user( $data['user_login'] ) );

				if ( $user && get_user_option( 'use_ssl', $user->ID ) ) {
					$secure_cookie = true;
					force_ssl_admin( true );
				}
			}

			if ( force_ssl_admin() ) {
				$secure_cookie = true;
			}

			if ( is_null( $secure_cookie ) && force_ssl_admin() ) {
				$secure_cookie = false;
			}

			// Login.
			$user = wp_signon( $data, $secure_cookie );

			// Redirect filter.
			if ( $secure_cookie && strstr( $redirect_to, 'wp-admin' ) ) {
				$redirect_to = str_replace( 'http:', 'https:', $redirect_to );
			}

			/**
			 * Filter login url if Login Redirect addon used. Add separate Login Widget Redirect compatibility.
			 *
			 * @since 1.0.5
			 */
			if ( class_exists( 'LoginPress_Login_Redirect_Main' ) && apply_filters( 'prevent_loginpress_login_widget_redirect', true ) ) {
				$logged_user_id     = $user->data->ID;
				$redirect_to        = $this->loginpress_redirect_url( $logged_user_id, 'loginpress_login_redirects_url' );
				$role_redirects_url = get_option( 'loginpress_redirects_role' );

				if ( empty( $redirect_to ) && ! empty( $role_redirects_url ) ) {
					foreach ( $role_redirects_url as $key => $value ) {
						if ( ! empty( $key ) && ! empty( $user ) ) {
							if ( in_array( $key, $user->roles ) ) {
							$redirect_to = $value['login'];
							}
						}
					}
				}
			}

			$response = array();

			if ( ! is_wp_error( $user ) ) {

				$response['success']  = 1;
				$response['redirect'] = $redirect_to;
			} else {

				$response['success'] = 0;
				if ( $user->errors ) {

					foreach ( $user->errors as $key => $error ) {

						$response[ $key ] = $error[0];
						break;
					}
				} else {

					$response['error'] = __( 'Please enter your username and password to login.', 'loginpress-pro' );
				}
			}

			echo wp_json_encode( $response );

			wp_die();
		}
	}
endif;

new LoginPress_Login_Widget();
