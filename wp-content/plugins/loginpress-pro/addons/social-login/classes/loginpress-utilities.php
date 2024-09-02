<?php
/**
 * LoginPress_Social_Utilities
 *
 * @package LoginPress Social Login
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

if ( ! class_exists( 'LoginPress_Social_Utilities' ) ) {

	/**
	 * LoginPress_Social_Utilities
	 */
	class LoginPress_Social_Utilities {

		/**
		 * Loginpress_site_url
		 *
		 * @version 3.0.0
		 * @return site URL
		 */
		public function loginpress_site_url() {
			return site_url();
		}

		/**
		 * Loginpress_callback_url
		 *
		 * @version 3.0.0
		 * @return callback URL
		 */
		public function loginpress_callback_url() {

			$url = wp_login_url();
			if ( strpos( $url, '?' ) === false ) {
				$url .= '?';
			} else {
				$url .= '&';
			}
			return $url;
		}

		/**
		 * Set header location.
		 *
		 * @param url $redirect Redirect URL.
		 * @version 3.0.0
		 *
		 * @return void
		 */
		public function redirect( $redirect ) {
			if ( headers_sent() ) {
				// Use JavaScript to redirect if content has been previously sent.
				echo '<script language="JavaScript" type="text/javascript">window.location=\'';
				echo $redirect; // @codingStandardsIgnoreLine.
				echo '\';</script>';
			} else { // Default Header Redirect.
				header( 'Location: ' . $redirect );
			}
			exit;
		}

		/**
		 * Function to access the protected object properties.
		 *
		 * @param object $object the object of graph.
		 * @param string $property the property of graph.
		 *
		 * @return object $object Value.
		 */
		public function loginpress_fetch_graph_user( $object, $property ) {

			// Using ReflectionClass that repots information about class.
			$reflection = new ReflectionClass( $object );
			// Gets a ReflectionProperty for a class property.
			$get_property = $reflection->getProperty( $property );
			// Set method accessibility.
			$get_property->setAccessible( true );
			// Return the property value w.r.t object.
			return $get_property->getValue( $object );
		}

		/**
		 * Function to insert the user data into plugin's custom table.
		 *
		 * @param int    $user_id ID of the user.
		 * @param object $object the object.
		 *
		 * @return void
		 */
		public static function link_user( $user_id, $object ) {
			global $wpdb;
			$sha_verifier = sha1( $object->deutype . $object->deuid );
			$table_name   = "{$wpdb->prefix}loginpress_social_login_details";
			$first_name   = sanitize_text_field( $object->first_name );
			$last_name    = sanitize_text_field( $object->last_name );
			$profile_url  = sanitize_text_field( $object->url );
			$photo_url    = sanitize_text_field( $object->deuimage );
			$display_name = sanitize_text_field( $object->first_name . ' ' . $object->last_name );
			$description  = sanitize_text_field( $object->about );

			$submit_array = array(
				'user_id'       => $user_id,
				'provider_name' => $object->deutype,
				'identifier'    => $object->deuid,
				'sha_verifier'  => $sha_verifier,
				'email'         => $object->email,
				'first_name'    => $first_name,
				'last_name'     => $last_name,
				'profile_url'   => $profile_url,
				'photo_url'     => $photo_url,
				'display_name'  => $display_name,
				'description'   => $description,
				'gender'        => $object->gender,
			);

			$wpdb->insert( $table_name, $submit_array ); // @codingStandardsIgnoreLine.
			if ( ! $object ) {
				echo esc_html( 'Data insertion failed' );
			}
		}

		/**
		 * Redirect user after successfully login.
		 *
		 * @param object $user the object of user.
		 * @param string $social_channel The social channel being used.
		 *
		 * @version 3.0.0
		 */
		public function _home_url( $user, $social_channel = '' ) {

			$user_id = $user->ID;
			if ( ! $this->set_cookies( $user_id ) ) {
				return false;
			}

			if ( isset( $_COOKIE['lg_redirect_to'] ) ) {
				$redirect = wp_unslash( $_COOKIE['lg_redirect_to'] ); // @codingStandardsIgnoreLine.
				setcookie( 'lg_redirect_to', '', time() - 3600 );
			} elseif ( ! wp_get_referer() ) {
				$redirect = site_url();
			} elseif ( ! strpos( wp_get_referer(), 'wp-login.php' ) ) {
				$redirect = wp_get_referer();
			} else {
				$redirect = admin_url();
			}

			if ( class_exists( 'LoginPress_Set_Login_Redirect' ) && do_action( 'loginpress_redirect_autologin', $user ) ) {
				$user_login_url = do_action( 'loginpress_redirect_autologin', $user );
			} else {
				$user_login_url = apply_filters( 'login_redirect', $redirect, site_url(), wp_signon() );
			}

			/**
			 * Login filter for social logins
			 *
			 * @version 3.0.0
			 */
			$login_filter = apply_filters( 'loginpress_social_login_redirect', false );

			if ( ! empty( $social_channel ) && is_array( $login_filter ) ) {
				switch ( $social_channel ) {
					case 'google_login':
						$social_redirect = $login_filter['google_login'];
						break;

					case 'facebook_login':
						$social_redirect = $login_filter['facebook_login'];
						break;

					case 'twitter_login':
						$social_redirect = $login_filter['twitter_login'];
						break;

					case 'linkedin_login':
						$social_redirect = $login_filter['linkedin_login'];
						break;
					case 'microsoft_login':
						$social_redirect = $login_filter['microsoft_login'];
						break;
				}

				wp_safe_redirect( esc_url( $social_redirect ) );
				exit();
			}

			wp_safe_redirect( $user_login_url );
			exit();
		}

		/**
		 * Set the cookies for a user ( Remember me )
		 *
		 * @param int  $user_id The User ID.
		 * @param bool $remember The option.
		 * @version 3.0.0
		 *
		 * @return bool
		 */
		public function set_cookies( $user_id = 0, $remember = true ) {
			if ( ! function_exists( 'wp_set_auth_cookie' ) ) {
				return false;
			}
			if ( ! $user_id ) {
				return false;
			}
			$user = get_user_by( 'id', (int) $user_id );
			wp_clear_auth_cookie();
			wp_set_auth_cookie( $user_id, $remember );
			wp_set_current_user( $user_id );
			do_action( 'wp_login', $user->user_login, $user );
			return true;
		}

		/**
		 * Register the User
		 *
		 * @param string $user_name The username.
		 * @param email  $user_email The email.
		 *
		 * @return int User ID.
		 */
		public function register_user( $user_name, $user_email ) {
			$can_social_register = (bool) apply_filters( 'loginpress_social_login_before_register', true );
			$can_register        = get_option( 'users_can_register' );
			if ( $can_register && $can_social_register ) {
				$username        = self::get_username( $user_name );
				$random_password = wp_generate_password( 12, true, false );
				$user_id         = wp_create_user( $username, $random_password, $user_email );

				return $user_id;
			}

			return false;
		}

		/**
		 * Get the username based on user ID
		 *
		 * @param int $user_login The user object.
		 *
		 * @return int $user_login The user ID.
		 */
		public static function get_username( $user_login ) {

			if ( username_exists( $user_login ) ) :

				$i       = 1;
				$user_ID = $user_login;

				do {
					$user_ID = $user_login . '_' . ( $i++ );
				} while ( username_exists( $user_ID ) );

				$user_login = $user_ID;
			endif;

			return $user_login;
		}

		/**
		 * Update the user meta data
		 *
		 * @param int    $user_id The User ID.
		 * @param object $object The USer Object.
		 * @param string $role The Role of the User.
		 *
		 * @return void
		 */
		public static function update_usermeta( $user_id, $object, $role ) {

			$meta_key = array( 'email', 'first_name', 'last_name', 'deuid', 'deutype', 'deuimage', 'description', 'sex' );
			$_object  = array( $object->email, $object->first_name, $object->last_name, $object->deuid, $object->deutype, $object->deuimage, $object->about, $object->gender );

			$i = 0;
			while ( $i < 8 ) :
				update_user_meta( $user_id, $meta_key[ $i ], $_object[ $i ] );
				++$i;
			endwhile;

			wp_update_user(
				array(
					'ID'           => $user_id,
					'display_name' => $object->first_name . ' ' . $object->last_name,
					'role'         => $role,
					'user_url'     => $object->url,
				)
			);

			self::link_user( $user_id, $object );
		}


		/**
		 * Show GPlus error/s
		 *
		 * @param int    $user The User ID.
		 * @param string $username The Username.
		 * @param string $password The Password.
		 *
		 * @return WP_ERROR $error the Error.
		 */
		public static function gplus_login_error( $user, $username, $password ) {
			$error = new WP_Error();
			/* Translators: The Error Message. */
			$error->add( 'gplus_login', sprintf( __( '%1$sERROR%2$s: Invalid `Client ID` or `Client Secret` combination?', 'loginpress-pro' ), '<strong>', '</strong>' ) );
			return $error;
		}
	}
}
