<?php
/**
 * Log in process
 *
 * When the admin is accessed with the wp_auto_login query arg,
 * check to see if the current user is logged in.
 * If not, set the current user to the defined account (username and password)
 *
 * @package loginPress
 */

if ( ! class_exists( 'LoginPress_Set_User' ) ) :
	/**
	 * LoginPress Set User Class
	 *
	 * @version 3.0.0
	 */
	class LoginPress_Set_User {

		/**
		 * LoginPress_Set_User Class constructor
		 *
		 * @version 3.0.0
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'login_user_with_autologin_code' ) );
			add_filter( 'wp_login_errors', array( $this, 'loginpress_autologin_custom_error' ) );
		}

		/**
		 * Set current user session
		 *
		 * @since 1.0.0
		 * @version 3.0.0
		 */
		public function login_user_with_autologin_code() {

			global $wpdb;

			// Check if LoginPress code is specified - if there is one the work begins.
			if ( isset( $_GET['loginpress_code'] ) ) {  // @codingStandardsIgnoreLine.

				$loginpress_code = isset( $_GET['loginpress_code'] ) ? preg_replace( '/[^a-zA-Z0-9]+/', '', sanitize_text_field( wp_unslash( $_GET['loginpress_code'] ) ) ) : ''; // @codingStandardsIgnoreLine.

				if ( ! empty( $loginpress_code ) ) { // Check if not empty
					// Get part left of ? of the request URI for reassembling the target url later.
					$sub_uri = array();
					if ( isset( $_SERVER['REQUEST_URI'] ) && preg_match( '/^([^\?]+)\?/', sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), $sub_uri ) === 1 ) {
						$page_redirect = $sub_uri[1];

						// $loginpress_code has been heavily cleaned before
						$user_ids = array();

						// WP_User_Query arguments.
						$args       = array(
							'blog_id'    => $GLOBALS['blog_id'],
							'order'      => 'ASC',
							'orderby'    => 'display_name',
							'meta_query' => array( // @codingStandardsIgnoreLine.
								'relation' => 'AND',
								array(
									'key'     => 'loginpress_autologin_user',
									'value'   => wp_json_encode( $loginpress_code ),
									'compare' => 'LIKE',
								),
							),
						);
						$user_query = new WP_User_Query( $args );

						// User Loop.
						if ( ! empty( $user_query->get_results() ) ) {
							foreach ( $user_query->get_results() as $user ) {

								$user_ids[] = $user->ID;
							}
						} else {
							wp_safe_redirect( home_url( 'wp-login.php?loginpress_error=invalid_login_code' ) );
							exit;
						}

						// Double login codes? should never autologin.
						if ( count( $user_ids ) > 1 ) {
							wp_die( esc_html__( 'Please login normally - this is a statistic bug and prevents you from using login links securely!', 'loginpress-pro' ) );
						}

						// Only login if there is only ONE possible user.
						if ( 1 === count( $user_ids ) ) {
							$current_user_id    = $user_ids[0];
							$user_meta          = get_user_meta( $current_user_id, 'loginpress_autologin_user', true );
							$user_meta_status   = get_user_meta( $current_user_id, 'loginpress_user_verification', true );
							$loginpress_options = get_option( 'loginpress_setting' );

							if ( ( isset( $user_meta_status ) && 'inactive' === $user_meta_status ) && ( isset( $loginpress_options['enable_user_verification'] ) && 'on' === $loginpress_options['enable_user_verification'] ) ) {
								wp_logout();
								wp_safe_redirect( home_url( 'wp-login.php?loginpress_error=user_inactive' ) );
								exit;
							} elseif ( 'disable' === $user_meta['state'] ) {
									wp_logout();
									wp_safe_redirect( home_url( 'wp-login.php?loginpress_error=user_disabled' ) );
									exit;
							} elseif ( 'unchecked' === $user_meta['expire'] ) {
								$expiration = $user_meta['duration'];

								if ( gmdate( 'Y-m-d' ) > $expiration ) {
									wp_logout();
									wp_safe_redirect( home_url( 'wp-login.php?loginpress_error=expired' ) );
									exit;
								}
							}
							$user_to_login = get_user_by( 'id', (int) $current_user_id );

							// Check if user exists.
							if ( $user_to_login ) {

								wp_set_auth_cookie( $user_to_login->ID, false );
								do_action( 'wp_login', $user_to_login->name, $user_to_login );

								// Create redirect URL without LoginPress code.
								$get_query = $this->loginpress_get_variable();
								if ( class_exists( 'LoginPress_Set_Login_Redirect' ) && do_action( 'loginpress_redirect_autologin', $user_to_login ) ) {
									do_action( 'loginpress_redirect_autologin', $user_to_login );
								} else {
									if ( isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ) {
										$ssl = 'https://';
									} else {
										$ssl = 'http://';
									}
									if ( isset( $_SERVER['HTTP_HOST'] ) ) {
										wp_safe_redirect( $ssl . wp_unslash( $_SERVER['HTTP_HOST'] ) . $page_redirect . $get_query );
										exit;
									}
								}
								exit;
							}
						}
					}
				}
			}
		}

		/**
		 * Add custom Error Message.
		 *
		 * @param array $errors The login error messages.
		 *
		 * @return array $error The Login errors with custom error message.
		 */
		public function loginpress_autologin_custom_error( $errors ) {

			$error = isset( $_GET['loginpress_error'] ) ? sanitize_text_field( wp_unslash( $_GET['loginpress_error'] ) ) : ''; // @codingStandardsIgnoreLine.

			if ( 'user_inactive' === $error ) {
				$error = apply_filters( 'loginpress_autologin_inactive_error', __( 'User Verification Is Required', 'loginpiress-pro' ) );
				$errors->add( 'access', sanitize_text_field( $error ) ); // @codingStandardsIgnoreLine.

			} elseif ( 'user_disabled' === $error ) {
				$error = apply_filters( 'loginpress_autologin_disable_error', __( 'User is disabled by Admin', 'loginpiress-pro' ) );
				$errors->add( 'access', sanitize_text_field( $error ) ); // @codingStandardsIgnoreLine.

			} elseif ( 'expired' === $error ) {
				$error = apply_filters( 'loginpress_autologin_expired_error', __( 'This Auto login link is Expired', 'loginpiress-pro' ) );
				$errors->add( 'access', sanitize_text_field( $error ) ); // @codingStandardsIgnoreLine.

			} elseif ( 'invalid_login_code' === $error ) {
				$error = apply_filters( 'loginpress_autologin_invalid_login_code', __( 'This code is invalid', 'loginpiress-pro' ) );
				$errors->add( 'access', sanitize_text_field( $error ) ); // @codingStandardsIgnoreLine.

			}
			return $errors;
		}

		/**
		 * [loginpress_get_variable Generates string of the GET variable including '?' separator from the URL.]
		 *
		 * @return [string]
		 */
		public function loginpress_get_variable() {

			$request = $_GET; // @codingStandardsIgnoreLine.
			unset( $request['loginpress_code'] );
			$get_string = $this->loginpress_join_get_variable( $request );
			if ( strlen( $get_string ) > 0 ) {
				$get_string = '?' . $get_string;
			}
			return $get_string;
		}

		/**
		 * [loginpress_join_get_variable Convert a GET variable array into GET-request parameter list].
		 *
		 * @param array $request  The request.
		 * @return string Returns a string of GET variable.
		 */
		public function loginpress_join_get_variable( $request ) {

			$keys        = array_keys( (array) $request );
			$assignments = array();
			foreach ( $keys as $key ) {
				$assignments[] = "$key=$request[$key]";
			}
			return implode( '&', $assignments );
		}
	}

endif;
new LoginPress_Set_User();
