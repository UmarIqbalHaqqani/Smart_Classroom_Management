<?php
/**
 * Twitter Login
 *
 * @package LoginPress Social Login
 */

use Abraham\TwitterOAuth\TwitterOAuth;
defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

if ( ! class_exists( 'LoginPress_Twitter' ) ) {

	/**
	 * LoginPress_Twitter
	 */
	class LoginPress_Twitter {
		/**
		 * Twitter Login.
		 *
		 * @return array $response The Twitter login response.
		 */
		public function twitter_login() {

			include_once LOGINPRESS_SOCIAL_DIR_PATH . 'classes/loginpress-utilities.php';
			require LOGINPRESS_SOCIAL_DIR_PATH . 'sdk/twitter/autoload.php';
			$loginpress_utilities = new LoginPress_Social_Utilities();

			$request      = $_REQUEST; // @codingStandardsIgnoreLine.
			$site         = $loginpress_utilities->loginpress_site_url();
			$callback_url = $loginpress_utilities->loginpress_callback_url();
			$response     = new stdClass();

			$lp_twitter_oauth = get_option( 'loginpress_twitter_oauth' );
			$login_settings   = get_option( 'loginpress_social_logins' );

			if ( isset( $_REQUEST['oauth_verifier'], $_REQUEST['oauth_token'] ) && $_REQUEST['oauth_token'] == $lp_twitter_oauth['oauth_token'] ) { // @codingStandardsIgnoreLine.
				$request_token                       = array();
				$request_token['oauth_token']        = $lp_twitter_oauth['oauth_token'];
				$request_token['oauth_token_secret'] = $lp_twitter_oauth['oauth_token_secret'];

				$connection   = new TwitterOAuth( $login_settings['twitter_oauth_token'], $login_settings['twitter_token_secret'], $request_token['oauth_token'], $request_token['oauth_token_secret'] );
				$access_token = $connection->oauth( 'oauth/access_token', array( 'oauth_verifier' => $_REQUEST['oauth_verifier'] ) ); // @codingStandardsIgnoreLine.

				update_option( 'loginpress_twitter_access', $access_token );
			}

			if ( ! isset( $request['oauth_token'] ) && ! isset( $request['oauth_verifier'] ) ) {
				// Get identity from user and redirect browser to OpenID Server.
				if ( ! isset( $request['oauth_token'] ) || '' === $request['oauth_token'] ) {
					$twitter_obj = new TwitterOAuth( $login_settings['twitter_oauth_token'], $login_settings['twitter_token_secret'] );

					try {
						$request_token = $twitter_obj->oauth( 'oauth/request_token', array( 'oauth_verifier' => $login_settings['twitter_callback_url'] ) );
					} catch ( Exception $e ) {
						echo esc_html( $e );
					}

					$_SESSION['oauth_token']        = $request_token['oauth_token'];
					$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

					$session_array = array(
						'oauth_token'        => $_SESSION['oauth_token'],
						'oauth_token_secret' => $_SESSION['oauth_token_secret'],
					);
					update_option( 'loginpress_twitter_oauth', $session_array );

					$url = $twitter_obj->url( 'oauth/authorize', array( 'oauth_token' => $request_token['oauth_token'] ) );
					/* If last connection failed don't display authorization link. */
					if ( $url ) :
						try {
							$loginpress_utilities->redirect( $url );
						} catch ( Exception $e ) {
							$response->status        = 'ERROR';
							$response->error_code    = 2;
							$response->error_message = 'Could not get AuthorizeUrl.';
						}
					endif;
				} else {
					$response->status        = 'ERROR';
					$response->error_code    = 2;
					$response->error_message = 'INVALID AUTHORIZATION';
				}
			} elseif ( isset( $request['oauth_token'] ) && isset( $request['oauth_verifier'] ) ) {
				/* Create TwitterAuth object with app key/secret and token key/secret from default phase */
				$access_token = get_option( 'loginpress_twitter_access' );
				// $twitter_obj = new TwitterOAuth( 'uz8VOy2P7xNNexJRqvnhdtYl1', 'edFTzF16znmVuEnvqnxKp2jAnk42p0vp5OSCYDYuAdXSiNOXIX', $access_token['oauth_token'], $access_token['oauth_token_secret'] );
				$twitter_obj = new TwitterOAuth( $login_settings['twitter_oauth_token'], $login_settings['twitter_token_secret'], $access_token['oauth_token'], $access_token['oauth_token_secret'] );
				/* Remove no longer needed request tokens */
				$params = array(
					'include_email'    => 'true',
					'include_entities' => 'true',
					'skip_status'      => 'true',
				);

				$user_profile = $twitter_obj->get( 'account/verify_credentials', $params );

				/* Request access twitterObj from twitter */
				$response->status        = 'SUCCESS';
				$response->deuid         = $user_profile->id;
				$response->deutype       = 'twitter';
				$response->name          = explode( ' ', $user_profile->name, 2 );
				$response->first_name    = $response->name[0];
				$response->last_name     = ( isset( $response->name[1] ) ) ? $response->name[1] : '';
				$response->deuimage      = $user_profile->profile_image_url_https;
				$response->email         = isset( $user_profile->email ) ? $user_profile->email : $user_profile->screen_name . '@twitter.com';
				$response->username      = ( '' !== $user_profile->screen_name ) ? strtolower( $user_profile->screen_name ) : $user_email;
				$response->url           = $user_profile->url;
				$response->about         = isset( $user_profile->description ) ? $user_profile->description : '';
				$response->gender        = isset( $user_profile->gender ) ? $user_profile->gender : 'N/A';
				$response->location      = $user_profile->location;
				$response->error_message = '';
			} else { // User Canceled your Request.
				$response->status        = 'ERROR';
				$response->error_code    = 1;
				$response->error_message = 'USER CANCELED REQUEST';
			}
			return $response;
		}
	}
}
