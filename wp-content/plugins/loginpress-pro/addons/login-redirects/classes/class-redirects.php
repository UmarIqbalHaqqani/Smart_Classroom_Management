<?php

/**
 * Main class for Login Redirects
 *
 * @package LoginPress Pro
 * @since 3.0.0
 */

if ( ! class_exists( 'LoginPress_Set_Login_Redirect' ) ) :

	/**
	 * Set's LoginPress Login Redirects.
	 */
	class LoginPress_Set_Login_Redirect {

		/** * * * * * * * *
		 * Class constructor
		 * * * * * * * * * */
		public function __construct() {

			add_filter( 'login_redirect', array( $this, 'loginpress_redirects_after_login' ), 10, 3 );
			add_action( 'clear_auth_cookie', array( $this, 'loginpress_redirects_after_logout' ), 10 );
			add_action( 'loginpress_redirect_autologin', array( $this, 'loginpress_autologin_redirects' ), 10, 1 );
		}

		/**
		 * Check if inner link provided.
		 *
		 * @param string $url URL of the site.
		 * @since 3.0.0
		 * @return bool
		 */
		public function is_inner_link( $url ) {

			$current_site = wp_parse_url( get_site_url() );
			$current_site = $current_site['host'];

			if ( false !== strpos( $url, $current_site ) ) {
				return true;
			}

			return false;
		}

		/**
		 * This function wraps around the main redirect function to determine whether or not to bypass the WordPress local URL limitation.
		 *
		 * @param string $redirect_to where to redirect.
		 * @param string $requested_redirect_to requested redirect.
		 * @param object $user user object.
		 * @return string
		 * @since  3.0.0
		 */
		public function loginpress_redirects_after_login( $redirect_to, $requested_redirect_to, $user ) {

			if ( apply_filters( 'prevent_loginpress_login_redirect', false ) ) {
				return;
			}

			if ( isset( $user->ID ) ) {
				$user_redirects_url = $this->loginpress_redirect_url( $user->ID, 'loginpress_login_redirects_url' );
				$role_redirects_url = get_option( 'loginpress_redirects_role' );

				if ( isset( $user->roles ) && is_array( $user->roles ) ) {

					if ( ! empty( $user_redirects_url ) ) { // check for specific user.

						if ( $this->is_inner_link( $user_redirects_url ) ) {
							return $user_redirects_url;
						}

						$this->loginpress_safe_redirects( $user->ID, $user->name, $user, $user_redirects_url );

					} elseif ( ! empty( $role_redirects_url ) ) { // check for specific role.

						foreach ( $role_redirects_url as $key => $value ) {
							$login_url = isset( $value['login'] ) && ! empty( $value['login'] ) ? $value['login'] : $redirect_to;
							if ( in_array( $key, $user->roles, true ) ) {

								if ( $this->is_inner_link( $login_url ) ) {
									return $login_url;
								}

								$this->loginpress_safe_redirects( $user->ID, $user->name, $user, $login_url );

							}
						}
					}
				} else {
					return $redirect_to;
				}
			}
			return $redirect_to;
		}

		/**
		 * Callback for clear_auth_cookie.
		 * Fire after user is logged out.
		 *
		 * @return null
		 * @since 3.0.0
		 */
		public function loginpress_redirects_after_logout() {

			// Prevent method from executing.
			if ( apply_filters( 'prevent_loginpress_logout_redirect', false ) ) {
				return;
			}

			$user_id = get_current_user_id();

			// Only execute for registered user.
			if ( 0 !== $user_id ) {
				$user_info = get_userdata( $user_id );
				$user_role = $user_info->roles;

				$role_redirects_url = get_option( 'loginpress_redirects_role' );
				$user_redirects_url = $this->loginpress_redirect_url( $user_id, 'loginpress_logout_redirects_url' );

				if ( isset( $user_redirects_url ) && ! empty( $user_redirects_url ) ) {
					wp_safe_redirect( $user_redirects_url );
					exit;
				} elseif ( ! empty( $role_redirects_url ) ) {
					foreach ( $role_redirects_url as $key => $value ) {
						$logout_url = isset( $value['logout'] ) && ! empty( $value['logout'] ) ? $value['logout'] : $redirect_to;

						if ( in_array( $key, $user_role, true ) ) {
							wp_safe_redirect( $logout_url );
							exit;
						}
					}
				}
			}
		}

		/**
		 * Set auth cookies for user and redirect on login.
		 *
		 * @param int    $user_id user ID.
		 * @param string $username username.
		 * @param obj    $user user object.
		 * @param string $redirect redirect string.
		 *
		 * @since 3.0.0
		 */
		private function loginpress_safe_redirects( $user_id, $username, $user, $redirect ) {

			wp_set_auth_cookie( $user_id, false );
			do_action( 'wp_login', $username, $user );
			wp_safe_redirect( $redirect );
			exit;
		}

		/**
		 * Compatible Login Redirects with Auto Login Add-On.
		 * Redirect a user to a custom URL for specific auto login link.
		 *
		 * @param object $user user object.
		 * @since 3.0.0
		 */
		public function loginpress_autologin_redirects( $user ) {

			$user_redirects_url = $this->loginpress_redirect_url( $user->ID, 'loginpress_login_redirects_url' );
			$role_redirects_url = get_option( 'loginpress_redirects_role' );

			if ( isset( $user->roles ) && is_array( $user->roles ) ) {

				if ( ! empty( $user_redirects_url ) ) { // check for specific user.
					$this->loginpress_safe_redirects( $user->ID, $user->name, $user, $user_redirects_url );
				} elseif ( ! empty( $role_redirects_url ) ) { // check for specific role.

					foreach ( $role_redirects_url as $key => $value ) {

						if ( in_array( $key, $user->roles, true ) ) {
							$this->loginpress_safe_redirects( $user->ID, $user->name, $user, $value['login'] );
						}
					}
				}
			}
		}

		/**
		 * Get user meta.
		 *
		 * @param int    $user_id ID of the user.
		 * @param string $option user meta key.
		 * @return string $redirect_url
		 * @since 3.0.0
		 */
		public function loginpress_redirect_url( $user_id, $option ) {

			if ( ! is_multisite() ) {
				$redirect_url = get_user_meta( $user_id, $option, true );
			} else {
				$redirect_url = get_user_option( $option, $user_id );
			}

			return $redirect_url;
		}
	}

endif;
new LoginPress_Set_Login_Redirect();
