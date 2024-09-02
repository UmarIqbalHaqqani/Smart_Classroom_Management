<?php
/**
 * LoginPress_Social_Login_Check
 *
 * @package LoginPress_Social_Login
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

if ( ! class_exists( 'LoginPress_Social_Login_Check' ) ) {

	/**
	 * LoginPress_Social_Login_Check
	 */
	class LoginPress_Social_Login_Check {
		/**
		 * Class constructor.
		 */
		public function __construct() {
			$this->set_redirect_to();
			$this->loginpress_check();
			$lp_twitter_oauth = get_option( 'loginpress_twitter_oauth' );

			if ( isset( $lp_twitter_oauth['oauth_token'] ) && isset( $_REQUEST['oauth_verifier'] ) ) {// @codingStandardsIgnoreLine.
				$this->on_twitter_login();
			}
		}

		/**
		 * Set Cookie for the `redirect_to` args
		 *
		 * @version 3.0.0
		 */
		public function set_redirect_to() {

			if ( isset( $_REQUEST['redirect_to'] ) ) {// @codingStandardsIgnoreLine.

				// 60 seconds ( 1 minute) * 20 = 20 minutes
				setcookie( 'lg_redirect_to', $_REQUEST['redirect_to'], time() + ( 60 * 20 ) ); // @codingStandardsIgnoreLine.
			}
		}
		/**
		 * Execute the specific Social login.
		 *
		 * @since 1.0.0
		 * @version 3.0.0
		 *
		 * @return void
		 */
		public function loginpress_check() {

			if ( isset( $_GET['lpsl_login_id'] ) ) { // @codingStandardsIgnoreLine.
				$exploder = explode( '_', $_GET['lpsl_login_id'] ); // @codingStandardsIgnoreLine.
				if ( 'facebook' === $exploder[0] ) {
					if ( version_compare( PHP_VERSION, '5.4.0', '<' ) ) {
						esc_html_e( 'The Facebook SDK requires PHP version 5.4 or higher. Please notify about this error to site admin.', 'loginpress-pro' );
						die();
					}
					$this->on_facebook_login();
				} elseif ( 'twitter' === $exploder[0] ) {
					$this->on_twitter_login();
				} elseif ( 'gplus' === $exploder[0] ) {
					$this->on_google_login();
				} elseif ( 'linkedin' === $exploder[0] ) {
					$this->on_linkedin_login();
				} elseif ( 'microsoft' === $exploder[0] ) {
					$this->on_microsoft_login();
				}
			}

			if ( isset( $_GET['state'] ) && strpos( $_GET['state'], 'lpsl_login=microsoft' ) !== false ) {
				$this->on_microsoft_login();
			}
		}

		/**
		 * Login with Microsoft Account.
		 *
		 * @version 3.0.0
		 * @return void
		 */
		public function on_microsoft_login() {
			require_once LOGINPRESS_SOCIAL_DIR_PATH . 'sdk/microsoft/vendor/autoload.php';
			include_once LOGINPRESS_SOCIAL_DIR_PATH . 'classes/loginpress-microsoft.php';
			include_once LOGINPRESS_SOCIAL_DIR_PATH . 'classes/loginpress-utilities.php';

			$loginpress_utilities = new LoginPress_Social_Utilities();
			$settings             = get_option( 'loginpress_social_logins' );
			$client_id            = $settings['microsoft_app_id'];
			$client_secret        = $settings['microsoft_app_secret'];
			$callback             = $settings['microsoft_redirect_uri'];
			$scopes               = array( 'User.Read', 'offline_access' );
			$tenant               = 'common';

			$microsoft_handler = new LoginPressMicrosoftLoginHandler( $tenant, $client_id, $client_secret, $callback, $scopes );

			if ( isset( $_GET['lpsl_login_id'] ) && $_GET['lpsl_login_id'] === 'microsoft_login' ) {
				$microsoft_handler->loginpress_microsoft_login();
			} else {
				$data                    = $microsoft_handler->loginpress_handle_returning_user();
				$id                      = $data->getId();
				$name                    = $data->getDisplayName();
				$result                  = new stdClass();
				$result->status          = 'SUCCESS';
				$result->deuid           = $id;
				$result->deutype         = 'microsoft';
				$result->first_name      = $data->getGivenName();
				$result->about           = '';
				$result->gender          = '';
				$result->url             = '';
				$result->last_name       = $data->getsurname();
				$result->email           = $data->getUserPrincipalName();
				$result->username        = ( '' !== $data->getGivenName() ) ? strtolower( $data->getGivenName() ) : $data['email'];
				$result->deuimage        = get_avatar_url( $result->email, array( 'size' => 150 ) );
				$is_microsoft_restricted = apply_filters( 'loginpress_social_login_microsoft_domains', false );

				if ( $is_microsoft_restricted && is_array( $is_microsoft_restricted ) ) {
					if ( ! $this->loginpress_is_eligible_social_domain( $data->getUserPrincipalName(), $is_microsoft_restricted ) ) {
						wp_safe_redirect(
							add_query_arg(
								array(
									'lp_social_error' => 'true',
								),
								wp_login_url()
							)
						);
						die();
					}
				}
				global $wpdb;
				$sha_verifier = sha1( $result->deutype . $result->deuid );
				$row          = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}loginpress_social_login_details` WHERE `provider_name` LIKE %s AND `identifier` LIKE %d AND `sha_verifier` LIKE %s", $result->deutype, $result->deuid, $sha_verifier ) ); // @codingStandardsIgnoreLine.

				$user_object = get_user_by( 'email', $data->getUserPrincipalName() );
				if ( ! $row ) {
					// check if there is already a user with the email address provided from social login already.
					if ( false !== $user_object ) {
						// user already there so log him in.
						$id  = $user_object->ID;
						$row = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}loginpress_social_login_details` WHERE `user_id` LIKE %d", $id ) ); // @codingStandardsIgnoreLine.

						if ( ! $row ) {
							$loginpress_utilities->link_user( $id, $result );
						}
						$loginpress_utilities->_home_url( $user_object, 'microsoft_login' );
						die();
					}

					$loginpress_utilities->register_user( $result->username, $result->email );
					$user_object = get_user_by( 'email', $result->email );
					$id          = $user_object->ID;
					$role        = get_option( 'default_role' );
					$loginpress_utilities->update_usermeta( $id, $result, $role );
					$loginpress_utilities->_home_url( $user_object );
					exit();
				} elseif ( ( $row[0]->provider_name === $result->deutype ) && ( $row[0]->identifier === $result->deuid ) ) {

						$user_object = get_user_by( 'email', $result->email );
						$id          = $user_object->ID;
						$loginpress_utilities->_home_url( $user_object, 'microsoft_login' );
						exit();
				} else {
					// user not found in our database.
					// need to handle an exception.
					echo esc_html__( 'user not found in our database', 'loginpress-pro' );
				}
			}
		}

		/**
		 * Login with LinkedIn Account.
		 * Fixed the LinkedIn authorization redirection loop issue.
		 *
		 * @version 3.0.0
		 * @return void
		 */
		public function on_linkedin_login() {

			$_settings     = get_option( 'loginpress_social_logins' );
			$client_id     = $_settings['linkedin_client_id'];      // LinkedIn client ID.
			$client_secret = $_settings['linkedin_client_secret']; // LinkedIn client secret.
			$redirect_url  = $_settings['linkedin_redirect_uri']; // Callback URL.

			if ( ! isset( $_GET['code'] ) ) { // @codingStandardsIgnoreLine.

				wp_redirect( "https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id={$client_id}&redirect_uri={$redirect_url}&state=987654321&scope=openid%20profile%20email" );
				exit();
			} else {

				$get_access_token = wp_remote_post(
					'https://www.linkedin.com/oauth/v2/accessToken',
					array(
						'body' => array(
							'grant_type'    => 'authorization_code',
							'code'          => $_GET['code'], // @codingStandardsIgnoreLine.
							'redirect_uri'  => $redirect_url,
							'client_id'     => $client_id,
							'client_secret' => $client_secret,
						),
					)
				);

				$_access_token = json_decode( $get_access_token['body'] )->access_token;

				if ( ! $_access_token ) {
					$user_login_url = apply_filters( 'login_redirect', admin_url(), site_url(), wp_signon() );
					wp_redirect( $user_login_url );
				}

				$get_user_details = wp_remote_get(
					'https://api.linkedin.com/v2/userinfo',
					array(
						'method' => 'GET', // @codingStandardsIgnoreLine.
						'timeout' => 15,
						'headers' => array( 'Authorization' => 'Bearer ' . $_access_token ),
					)
				);

				if ( ! is_wp_error( $get_user_details ) && isset( $get_user_details['response']['code'] ) && 200 === $get_user_details['response']['code'] ) {

					$light_detail_body = json_decode( wp_remote_retrieve_body( $get_user_details ) );
					$first_name        = isset( $light_detail_body->given_name ) ? $light_detail_body->given_name : '';
					$last_name         = isset( $light_detail_body->family_name ) ? $light_detail_body->family_name : '';
					$large_avatar      = isset( $light_detail_body->picture ) ? $light_detail_body->picture : '';
					$email_address     = isset( $light_detail_body->email ) ? $light_detail_body->email : '';
					$deuid             = isset( $light_detail_body->sub ) ? $light_detail_body->sub : '';
				}

				if ( empty( $email_address ) || empty( $deuid ) ) {
					echo esc_html__( 'user not found in our database', 'loginpress-pro' );
					exit;
				}
				$is_linkedin_restricted = apply_filters( 'loginpress_social_login_linkedin_domains', false );

				if ( $is_linkedin_restricted && is_array( $is_linkedin_restricted ) ) {
					if ( ! $this->loginpress_is_eligible_social_domain( $email_address, $is_linkedin_restricted ) ) {
						wp_safe_redirect(
							add_query_arg(
								array(
									'lp_social_error' => 'true',
								),
								wp_login_url()
							)
						);
						die();
					}
				}
				include_once LOGINPRESS_SOCIAL_DIR_PATH . 'classes/loginpress-utilities.php';

				$loginpress_utilities  = new LoginPress_Social_Utilities();
				$result                = new stdClass();
				$result->status        = 'SUCCESS';
				$result->deuid         = $deuid;
				$result->deutype       = 'linkedin';
				$result->first_name    = $first_name;
				$result->last_name     = $last_name;
				$result->email         = '' !== $email_address ? $email_address : $deuid . '@linkedin.com';
				$result->username      = strtolower( $first_name . '_' . $last_name );
				$result->gender        = 'N/A';
				$result->url           = '';
				$result->about         = ''; // LinkedIn doesn't return user about details.
				$result->deuimage      = $large_avatar;
				$result->error_message = '';

				global $wpdb;
				$sha_verifier = sha1( $result->deutype . $deuid );
				$identifier   = $deuid;
				$row          = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}loginpress_social_login_details` WHERE `provider_name` LIKE %s AND `identifier` LIKE %d AND `sha_verifier` LIKE %s", $result->deutype, $deuid, $sha_verifier ) ); // @codingStandardsIgnoreLine.

				$user_object = get_user_by( 'email', $result->email );
				if ( ! $row ) {
					// check if there is already a user with the email address provided from social login already.
					if ( false !== $user_object ) {
						// user already there so log him in.
						$id  = $user_object->ID;
						$row = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}loginpress_social_login_details` WHERE `user_id` LIKE %d", $id ) ); // @codingStandardsIgnoreLine.

						if ( ! $row ) {
							$loginpress_utilities->link_user( $id, $result );
						}
						$loginpress_utilities->_home_url( $user_object );
						die();
					}

					$loginpress_utilities->register_user( $result->username, $result->email );
					$user_object = get_user_by( 'email', $result->email );
					$id          = $user_object->ID;
					$role        = get_option( 'default_role' );
					$loginpress_utilities->update_usermeta( $id, $result, $role );
					$loginpress_utilities->_home_url( $user_object );
					exit();
				} elseif ( ( $row[0]->provider_name === $result->deutype ) && ( $row[0]->identifier === $deuid ) ) {

						$user_object = get_user_by( 'email', $result->email );
						$id          = $user_object->ID;
						$loginpress_utilities->_home_url( $user_object );

						exit();
				} else {
					// user not found in our database.
					// need to handle an exception.
					echo esc_html__( 'user not found in our database', 'loginpress-pro' );
				}
			}
		}

		/**
		 * Login with Google Account.
		 *
		 * @version 3.0.0
		 * @return void
		 */
		public function on_google_login() {

			$_settings                  = get_option( 'loginpress_social_logins' );
			$google_oauth_client_id     = $_settings['gplus_client_id']; // Google client ID.
			$google_oauth_client_secret = $_settings['gplus_client_secret']; // Google client secret.
			$google_oauth_redirect_uri  = $_settings['gplus_redirect_uri']; // Callback URL.
			$google_oauth_version       = 'v3';

			include_once LOGINPRESS_SOCIAL_DIR_PATH . 'classes/loginpress-utilities.php';

			$loginpress_utilities = new LoginPress_Social_Utilities();
			// If the captured code param exists and is valid.
			if ( isset( $_GET['code'] ) && ! empty( $_GET['code'] ) ) {
				// Execute HTTP request to retrieve the access token.
				$params        = array(
					'code'          => $_GET['code'],  // @codingStandardsIgnoreLine.
					'client_id'     => $google_oauth_client_id,
					'client_secret' => $google_oauth_client_secret,
					'redirect_uri'  => $google_oauth_redirect_uri,
					'grant_type'    => 'authorization_code',
				);
				$response      = wp_remote_post(
					'https://accounts.google.com/o/oauth2/token',
					array(
						'body' => $params,
					)
				);
				$response_body = wp_remote_retrieve_body( $response );
				$response      = json_decode( $response_body, true );

				// Make sure access token is valid.
				if ( isset( $response['access_token'] ) && ! empty( $response['access_token'] ) ) {
					// Execute HTTP request to retrieve the user info associated with the Google account.
					$response      = wp_remote_get(
						'https://www.googleapis.com/oauth2/' . $google_oauth_version . '/userinfo',
						array(
							'headers' => array( 'Authorization' => 'Bearer ' . $response['access_token'] ),
						)
					);
					$response_body = wp_remote_retrieve_body( $response );
					$profile       = json_decode( $response_body, true );

					// Make sure the profile data exists.
					if ( isset( $profile['email'] ) ) {
						$result               = new stdClass();
						$result->status       = 'SUCCESS';
						$result->deuid        = $profile['sub'];
						$result->deutype      = 'glpus';
						$result->first_name   = $profile['given_name'];
						$result->last_name    = '';
						$result->email        = $profile['email'];
						$result->username     = ( '' !== $profile['given_name'] ) ? strtolower( $profile['given_name'] ) : $profile['email'];
						$result->gender       = '';
						$result->url          = '';
						$result->about        = '';
						$result->deuimage     = $profile['picture'];
						$is_google_restricted = apply_filters( 'loginpress_social_login_google_domains', false );

						if ( $is_google_restricted && is_array( $is_google_restricted ) ) {
							if ( ! $this->loginpress_is_eligible_social_domain( $result->email, $is_google_restricted ) ) {
								wp_redirect(
									add_query_arg(
										array(
											'lp_social_error' => 'true',
										),
										wp_login_url()
									)
								);
								die();
							}
						}
						global $wpdb;
						$sha_verifier = sha1( $result->deutype . $result->deuid );
						$identifier   = $profile['sub'];
						$row          = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}loginpress_social_login_details` WHERE `provider_name` LIKE %s AND `identifier` LIKE %d AND `sha_verifier` LIKE %s", $result->deutype, $result->deuid, $sha_verifier ) ); // @codingStandardsIgnoreLine.

						$user_object = get_user_by( 'email', $profile['email'] );
						if ( ! $row ) {
							// check if there is already a user with the email address provided from social login already.
							if ( false !== $user_object ) {
								// user already there so log him in.
								$id  = $user_object->ID;
								$row = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}loginpress_social_login_details` WHERE `user_id` LIKE %d", $id ) ); // @codingStandardsIgnoreLine.

								if ( ! $row ) {
									$loginpress_utilities->link_user( $id, $result );
								}
								$loginpress_utilities->_home_url( $user_object, 'google_login' );
								die();
							}

							$loginpress_utilities->register_user( $result->username, $result->email );
							$user_object = get_user_by( 'email', $result->email );
							$id          = $user_object->ID;
							$role        = get_option( 'default_role' );
							$loginpress_utilities->update_usermeta( $id, $result, $role );
							$loginpress_utilities->_home_url( $user_object );
							exit();
						} elseif ( ( $row[0]->provider_name === $result->deutype ) && ( $row[0]->identifier === $result->deuid ) ) {

								$user_object = get_user_by( 'email', $result->email );
								$id          = $user_object->ID;
								$loginpress_utilities->_home_url( $user_object, 'google_login' );

								exit();
						} else {
							// user not found in our database.
							echo esc_html__( 'user not found in our database', 'loginpress-pro' );
						}
					} else {
						add_filter( 'authenticate', array( 'LoginPress_Social_Utilities', 'gplus_login_error' ), 40, 3 );
					}
				} else {
					add_filter( 'authenticate', array( 'LoginPress_Social_Utilities', 'gplus_login_error' ), 40, 3 );
				}
			} else {
				// Define params and redirect to Google Authentication page.
				$params = array(
					'response_type' => 'code',
					'client_id'     => $google_oauth_client_id,
					'redirect_uri'  => $google_oauth_redirect_uri,
					'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
					'access_type'   => 'offline',
					'prompt'        => 'consent',
				);
				wp_redirect( 'https://accounts.google.com/o/oauth2/auth?' . http_build_query( $params ) );
				exit;
			}
		}

		/**
		 * Login with Facebook Account.
		 *
		 * @version 3.0.0
		 */
		public function on_facebook_login() {

			include_once LOGINPRESS_SOCIAL_DIR_PATH . 'classes/loginpress-facebook.php';
			include_once LOGINPRESS_SOCIAL_DIR_PATH . 'classes/loginpress-utilities.php';
			$response_class         = new stdClass();
			$facebook_login         = new LoginPress_Facebook();
			$loginpress_utilities   = new LoginPress_Social_Utilities();
			$result                 = $facebook_login->facebook_login( $response_class );
			$is_facebook_restricted = apply_filters( 'loginpress_social_login_facebook_domains', false );

			if ( $is_facebook_restricted && is_array( $is_facebook_restricted ) ) {
				if ( ! $this->loginpress_is_eligible_social_domain( $result->email, $is_facebook_restricted ) ) {
					wp_safe_redirect(
						add_query_arg(
							array(
								'lp_social_error' => 'true',
							),
							wp_login_url()
						)
					);
					die();
				}
			}
			if ( isset( $result->status ) && 'SUCCESS' === $result->status ) {

				global $wpdb;
				$sha_verifier = sha1( $result->deutype . $result->deuid );
				$row          = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}loginpress_social_login_details` WHERE `provider_name` LIKE %s AND `identifier` LIKE %d AND `sha_verifier` LIKE %s", $result->deutype, $result->deuid, $sha_verifier ) ); // @codingStandardsIgnoreLine.
				$user_object  = get_user_by( 'email', $result->email );

				if ( ! isset( $row[0]->email ) && $result->email === $result->deuid . '@facebook.com' ) {
					$result->email = $result->email;

				} elseif ( $result->email === $result->deuid . '@facebook.com' ) {
					$result->email = $row[0]->email;
				}

				if ( ! $row ) {
					// check if there is already a user with the email address provided from social login already.
					if ( false !== $user_object ) {
						// user already there so log him in.
						$id  = $user_object->ID;
						$row = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}loginpress_social_login_details` WHERE `user_id` LIKE %d", $id ) ); // @codingStandardsIgnoreLine.
						if ( ! $row ) {
							$loginpress_utilities->link_user( $id, $result );
						}
						$loginpress_utilities->_home_url( $user_object );
						die();
					}
					$loginpress_utilities->register_user( $result->username, $result->email );
					$user_object = get_user_by( 'email', $result->email );
					$id          = $user_object->ID;
					$role        = get_option( 'default_role' );
					$loginpress_utilities->update_usermeta( $id, $result, $role );
					$loginpress_utilities->_home_url( $user_object );
					exit();
				} elseif ( ( $row[0]->provider_name === $result->deutype ) && ( $row[0]->identifier === $result->deuid ) ) {
					$user_object = get_user_by( 'email', $result->email );
					$id          = $user_object->ID;
					$loginpress_utilities->_home_url( $user_object );
					exit();
				} else {
					// user not found in our database.
					// need to handle an exception.
					echo esc_html__( 'user not found in our database', 'loginpress-pro' );
				}
			} else {
				if ( isset( $_REQUEST['error'] ) ) { // @codingStandardsIgnoreLine.

					$redirect_url = isset( $_REQUEST['redirect_to'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['redirect_to'] ) ) : site_url(); // @codingStandardsIgnoreLine.
					$loginpress_utilities->redirect( $redirect_url );
				}
				die();
			}
		}

		/**
		 * Login with Twitter Account.
		 *
		 * @version 3.0.0
		 * @return void
		 */
		public function on_twitter_login() {

			include_once LOGINPRESS_SOCIAL_DIR_PATH . 'classes/loginpress-twitter.php';
			include_once LOGINPRESS_SOCIAL_DIR_PATH . 'classes/loginpress-utilities.php';

			$response_class        = new stdClass();
			$twitter_login         = new LoginPress_Twitter();
			$loginpress_utilities  = new LoginPress_Social_Utilities();
			$result                = $twitter_login->twitter_login( $response_class );
			$is_twitter_restricted = apply_filters( 'loginpress_social_login_twitter_domains', false );

			if ( $is_twitter_restricted && is_array( $is_twitter_restricted ) ) {
				if ( ! $this->loginpress_is_eligible_social_domain( $result->email, $is_twitter_restricted ) ) {
					wp_safe_redirect(
						add_query_arg(
							array(
								'lp_social_error' => 'true',
							),
							wp_login_url()
						)
					);
					die();
				}
			}
			if ( isset( $result->status ) && 'SUCCESS' === $result->status ) {
				global $wpdb;
				$sha_verifier = sha1( $result->deutype . $result->deuid );
				$row          = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}loginpress_social_login_details` WHERE `provider_name` LIKE %s AND `identifier` LIKE %d AND `sha_verifier` LIKE %s", $result->deutype, $result->deuid, $sha_verifier ) ); // @codingStandardsIgnoreLine.

				if ( ! $row ) {
					// check if there is already a user with the email address provided from social login already.
					$user_object = get_user_by( 'email', $result->email );

					if ( false !== $user_object ) {
						// user already there so log him in.
						$id  = $user_object->ID;
						$row = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}loginpress_social_login_details` WHERE `user_id` LIKE %d", $id ) ); // @codingStandardsIgnoreLine.

						if ( ! $row ) {
							$loginpress_utilities->link_user( $id, $result );
						}
						$loginpress_utilities->_home_url( $user_object );
						die();
					}

					$loginpress_utilities->register_user( $result->username, $result->email );
					$user_object = get_user_by( 'email', $result->email );
					$id          = $user_object->ID;
					$role        = get_option( 'default_role' );
					$loginpress_utilities->update_usermeta( $id, $result, $role );
					$loginpress_utilities->_home_url( $user_object );
					exit();
				} elseif ( ( $row[0]->provider_name === $result->deutype ) && ( $row[0]->identifier === $result->deuid ) ) {

					$user_object = get_user_by( 'email', $result->email );
					$id          = $user_object->ID;
					$loginpress_utilities->_home_url( $user_object );
					exit();
				} else {
					// user not found in our database.
					// need to handle an exception.
					echo esc_html__( 'user not found in our database', 'loginpress-pro' );
				}
			} else {
				if ( isset( $_REQUEST['denied'] ) ) { // @codingStandardsIgnoreLine.
					$redirect_url = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : site_url(); // @codingStandardsIgnoreLine.
					$loginpress_utilities->redirect( $redirect_url );
				}
				die();
			}
		}

		/**
		 * Function loginpress_is_eligible_social_domain to check whether email is eligible or not.
		 *
		 * @param  mixed $email Full email address o user taken from social provider.
		 * @param  mixed $eligible_domains List of partial eligible domains.
		 *
		 * @return bool $found If string is found or not.
		 *
		 * @since 3.0.0
		 */
		public function loginpress_is_eligible_social_domain( $email, $eligible_domains ) {
			$found = false;

			foreach ( $eligible_domains as $partial ) {
				if ( strpos( $email, $partial ) !== false ) {
					$found = true;
					break;
				}
			}
			return $found;
		}
	}
}
$lpsl_login_check = new LoginPress_Social_Login_Check();
