<?php
/**
 * LoginPress_Facebook
 *
 * @package LoginPress Social Login
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

if ( ! class_exists( 'LoginPress_Facebook' ) ) {

	/**
	 * LoginPress_Facebook
	 */
	class LoginPress_Facebook {

		/**
		 * Facebook_login
		 */
		public function facebook_login() {
			include_once LOGINPRESS_SOCIAL_DIR_PATH . 'classes/loginpress-utilities.php';
			$loginpress_utilities = new LoginPress_Social_Utilities();

			$request            = $_REQUEST; // @codingStandardsIgnoreLine.
			$site               = $loginpress_utilities->loginpress_site_url();
			$call_back_url      = $loginpress_utilities->loginpress_callback_url();
			$response           = new stdClass();
			$lp_fb_user_details = new stdClass();
			$exploder           = explode( '_', $_GET['lpsl_login_id'] ); // @codingStandardsIgnoreLine.
			$action             = $exploder[1];
			$width              = 150;
			$height             = 150;
			$_social_logins     = get_option( 'loginpress_social_logins' );

			$config = array(
				'app_id'                  => $_social_logins['facebook_app_id'],
				'app_secret'              => $_social_logins['facebook_app_secret'],
				'default_graph_version'   => 'v2.9',
				'persistent_data_handler' => 'session',
			);

			include LOGINPRESS_SOCIAL_DIR_PATH . 'sdk/facebook/autoload.php';

			$fb       = new Facebook\Facebook( $config );
			$callback = $call_back_url . 'lpsl_login_id=facebook_check';

			if ( 'login' === $action ) {
				// Well looks like we are a fresh dude, login to Facebook!
				$helper      = $fb->getRedirectLoginHelper();
				$permissions = array( 'email', 'public_profile' );
				$login_url   = $helper->getLoginUrl( $callback, $permissions );
				$loginpress_utilities->redirect( $login_url );
			} else {

				if ( isset( $_REQUEST['error'] ) ) { // @codingStandardsIgnoreLine.
					$response->status        = 'ERROR';
					$response->error_code    = 2;
					$response->error_message = 'INVALID AUTHORIZATION';
					return $response;
				}

				if ( isset( $_REQUEST['code'] ) ) { // @codingStandardsIgnoreLine.
					$helper = $fb->getRedirectLoginHelper();
					// Trick below will avoid "Cross-site request forgery validation failed. Required param "state" missing." from Facebook.
					$_SESSION['FBRLH_state'] = isset( $_REQUEST['state'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['state'] ) ) : ''; // @codingStandardsIgnoreLine.
					try {
						$access_token = $helper->getAccessToken();
					} catch ( Facebook\Exceptions\FacebookResponseException $e ) {
						// When Graph returns an error.
						echo esc_html( $e->getMessage() );
						exit;
					} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
						// When validation fails or other local issues.
						echo esc_html( $e->getMessage() );
						exit;
					}

					if ( isset( $access_token ) ) {
						// Logged in!
						$_SESSION['facebook_access_token'] = (string) $access_token;
						$fb->setDefaultAccessToken( $access_token );

						try {
							$response  = $fb->get( '/me?fields=email,name, first_name, last_name, gender, link, about, birthday, education, hometown, languages, location, website' );
							$user_node = $response->getGraphUser();
						} catch ( Facebook\Exceptions\FacebookResponseException $e ) {
							// When Graph returns an error.
							echo esc_html( $e->getMessage() );
							exit;
						} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
							// When validation fails or other local issues.
							echo esc_html( $e->getMessage() );
							exit;
						}
						// get the user profile details.
						$user_profile = $loginpress_utilities->loginpress_fetch_graph_user( $user_node, 'items' );

						if ( null !== $user_profile ) {

							$lp_fb_user_details->status     = 'SUCCESS';
							$lp_fb_user_details->deuid      = $user_profile['id'];
							$lp_fb_user_details->deutype    = 'facebook';
							$lp_fb_user_details->first_name = $user_profile['first_name'];
							$lp_fb_user_details->last_name  = $user_profile['last_name'];

							if ( isset( $user_profile['email'] ) || '' !== $user_profile['email'] ) {

								$user_email = $user_profile['email'];
							} else {

								$user_email = $user_profile['id'] . '@facebook.com';
							}
							$lp_fb_user_details->email    = $user_email;
							$lp_fb_user_details->username = ( '' !== $user_profile['first_name'] ) ? strtolower( $user_profile['first_name'] ) : $user_email;
							$lp_fb_user_details->gender   = isset( $user_profile['gender'] ) ? $user_profile['gender'] : 'N/A';
							$lp_fb_user_details->url      = isset( $user_profile['link'] ) ? $user_profile['link'] : '';
							$lp_fb_user_details->about    = ''; // facebook doesn't return user about details.
							$lp_fb_user_details->deuimage = isset( $user_profile['profile_pic'] ) ? $user_profile['profile_pic'] : 'https://www.gravatar.com/avatar/' . md5( $user_profile['email'] ) . '?s=96'; // Facebook Profile picture.

							$lp_fb_user_details->error_message = '';
						} else {

							$lp_fb_user_details->status        = 'ERROR';
							$lp_fb_user_details->error_code    = 2;
							$lp_fb_user_details->error_message = 'INVALID AUTHORIZATION';
						}
					}
				} else {
					// Well looks like we are a fresh dude, login to Facebook!
					$helper      = $fb->getRedirectLoginHelper();
					$permissions = array( 'email', 'public_profile' ); // optional.
					$login_url   = $helper->getLoginUrl( $callback, $permissions );
					$loginpress_utilities->redirect( $login_url );
				}
			}

			return $lp_fb_user_details;
		}
	}
}
