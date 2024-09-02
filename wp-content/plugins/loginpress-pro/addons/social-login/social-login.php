<?php
/**
 * Addon Name: LoginPress - Social Login
 * Description: This is a premium add-on of LoginPress WordPress plugin by <a href="https://wpbrigade.com/">WPBrigade</a> which allows you to login using social media accounts    like Facebook, Twitter and Google/G+ etc
 *
 * @package loginpress
 * @category Core
 * @author WPBrigade
 * @version 3.0.0
 */

if ( ! class_exists( 'LoginPress_Social' ) ) :

	/**
	 * LoginPress_Social
	 */
	final class LoginPress_Social {

		/**
		 * Is short code used.
		 *
		 * @var bool
		 */
		private $is_shortcode = false;


		/**
		 * The plugin instance
		 *
		 * @var object
		 */
		protected static $instance = null;

		/**
		 * Class constructor
		 */
		public function __construct() {

			if ( LoginPress_Pro::addon_wrapper( 'social-login' ) ) {
				$this->settings = get_option( 'loginpress_social_logins' );
				$this->define_constants();
				$this->hooks();
			}
		}

		/**
		 * The settings array
		 *
		 * @var array
		 */
		public $settings;

		/**
		 * Define LoginPress Constants
		 *
		 * @version 3.0.0
		 */
		private function define_constants() {

			LoginPress_Pro_Init::define( 'LOGINPRESS_SOCIAL_DIR_PATH', plugin_dir_path( __FILE__ ) );
		}

		/**
		 * Hook into actions and filters
		 *
		 * @version 3.0.0
		 */
		private function hooks() {

			$enable   = isset( $this->settings['enable_social_login_links'] ) ? $this->settings['enable_social_login_links'] : '';
			$login    = isset( $enable['login'] ) ? 'login' : '';
			$register = isset( $enable['register'] ) ? 'register' : '';

			if ( 'login' === $login ) {
				add_action( 'login_form', array( $this, 'loginpress_social_login' ) );
			}
			if ( 'register' === $register ) {
				add_action( 'register_form', array( $this, 'loginpress_social_login' ) );
			}
			add_action( 'init', array( $this, 'session_init' ) );
			// add_action( 'admin_init', array( $this, 'init_addon_updater' ), 0 );.
			add_filter( 'loginpress_settings_tab', array( $this, 'settings_tab' ), 15 );
			add_filter( 'loginpress_settings_fields', array( $this, 'settings_field' ), 10 );
			add_action( 'loginpress_social_login_help_tab_script', array( $this, 'loginpress_social_login_help_tab_callback' ) );
			add_action( 'delete_user', array( $this, 'delete_user_row' ) );
			add_filter( 'login_message', array( $this, 'loginpress_social_login_register_error' ), 100, 1 );
			add_action( 'admin_enqueue_scripts', array( $this, 'loginpress_social_login_admin_action_scripts' ) );
			add_action( 'login_enqueue_scripts', array( $this, 'load_login_assets' ) );
			add_action( 'login_footer', array( $this, 'login_page_custom_footer' ) );
			add_filter( 'get_avatar', array( $this, 'insert_avatar' ), 1, 5 );

			add_shortcode( 'loginpress_social_login', array( $this, 'loginpress_social_login_shortcode' ) );
		}

		/**
		 * Add social avatar to user profile.
		 *
		 * @param mixed  $avatar The Avatar.
		 * @param mixed  $id_or_email The ID or Email of user.
		 * @param int    $size The size of the avatar.
		 * @param string $default Default Avatar.
		 * @param bool   $alt Alternative.
		 *
		 * @return url $avatar the Avatar.
		 */
		public function insert_avatar( $avatar, $id_or_email, $size = 96, $default = '', $alt = false ) {
			global $wpdb;
			$user = false;
			$id   = 0;

			if ( is_numeric( $id_or_email ) ) {

				$id   = (int) $id_or_email;
				$user = get_user_by( 'id', $id );

			} elseif ( is_object( $id_or_email ) ) {

				if ( ! empty( $id_or_email->user_id ) ) {
					$id   = (int) $id_or_email->user_id;
					$user = get_user_by( 'id', $id );
				}
			} else {
				$user = get_user_by( 'email', $id_or_email );
			}

			if ( $user && is_object( $user ) ) {
				$table_name = "{$wpdb->prefix}loginpress_social_login_details";
				$avatar_url = $wpdb->get_results( $wpdb->prepare( "SELECT photo_url FROM `$table_name` WHERE user_id = %d", $id ) ); // @codingStandardsIgnoreLine.

				if ( $avatar_url ) {
					$avatar_url = $avatar_url[0]->photo_url;
					$avatar     = preg_replace( '/src=("|\').*?("|\')/i', 'src=\'' . $avatar_url . '\'', $avatar );
					$avatar     = preg_replace( '/srcset=("|\').*?("|\')/i', 'srcset=\'' . $avatar_url . '\'', $avatar );
				}
			}

			return $avatar;
		}

		/**
		 * LoginPress Addon updater
		 *
		 * @version 3.0.0
		 */
		public function init_addon_updater() {
			if ( class_exists( 'LoginPress_AddOn_Updater' ) ) {
				$updater = new LoginPress_AddOn_Updater( 2335, __FILE__, $this->version );
			}
		}


		/**
		 * Add the settings fields for the Social Login.
		 *
		 * @param array $setting_array The social login setting array.
		 *
		 * @return array An array of setting's fields and their corresponding attributes.
		 */
		public function settings_field( $setting_array ) {

			$_new_tabs = array(
				array(
					'name'  => 'facebook',
					'label' => __( 'Facebook Login', 'loginpress-pro' ),
					'desc'  => __( 'Enable Facebook Login', 'loginpress-pro' ),
					'type'  => 'checkbox',
				),
				array(
					'name'  => 'facebook_app_id',
					'label' => __( 'Facebook App ID', 'loginpress-pro' ),
					'desc'  => sprintf( __( 'Enter your Facebook App ID.', 'loginpress-pro' ), '<a href="https://wpbrigade.com/">', '</a>' ),
					'type'  => 'text',
				),
				array(
					'name'  => 'facebook_app_secret',
					'label' => __( 'Facebook App Secret Key', 'loginpress-pro' ),
					'desc'  => sprintf( __( 'Enter your Facebook App Secret Key.', 'loginpress-pro' ), '<a href="https://wpbrigade.com/">', '</a>' ),
					'type'  => 'text',
				),
				array(
					'name'  => 'twitter',
					'label' => __( 'Twitter Login', 'loginpress-pro' ),
					'desc'  => __( 'Enable Twitter Login', 'loginpress-pro' ),
					'type'  => 'checkbox',
				),
				array(
					'name'  => 'twitter_oauth_token',
					'label' => __( 'Twitter API key', 'loginpress-pro' ),
					'desc'  => sprintf( __( 'Enter Your Consumer API key.', 'loginpress-pro' ), '<a href="https://wpbrigade.com/">', '</a>' ),
					'type'  => 'text',
				),
				array(
					'name'  => 'twitter_token_secret',
					'label' => __( 'Twitter API secret key', 'loginpress-pro' ),
					'desc'  => sprintf( __( 'Enter Your Consumer API secret key.', 'loginpress-pro' ), '<a href="https://wpbrigade.com/">', '</a>' ),
					'type'  => 'text',
				),
				array(
					'name'  => 'twitter_callback_url',
					'label' => __( 'Twitter Callback URL', 'loginpress-pro' ),
					/* Translators: The Twitter callback URl */
					'desc'  => sprintf( __( 'Enter Your Callback URL %1$s', 'loginpress-pro' ), wp_login_url() ),
					'type'  => 'text',
				),
				array(
					'name'  => 'gplus',
					'label' => __( 'Google Login', 'loginpress-pro' ),
					'desc'  => __( 'Enable Google Login', 'loginpress-pro' ),
					'type'  => 'checkbox',
				),
				array(
					'name'  => 'gplus_client_id',
					'label' => __( 'Client ID', 'loginpress-pro' ),
					'desc'  => sprintf( __( 'Enter Your Client ID.', 'loginpress-pro' ), '<a href="https://wpbrigade.com/">', '</a>' ),
					'type'  => 'text',
				),
				array(
					'name'  => 'gplus_client_secret',
					'label' => __( 'Client Secret', 'loginpress-pro' ),
					'desc'  => sprintf( __( 'Enter Your Client Secret.', 'loginpress-pro' ), '<a href="https://wpbrigade.com/">', '</a>' ),
					'type'  => 'text',
				),
				array(
					'name'  => 'gplus_redirect_uri',
					'label' => __( 'Redirect URI', 'loginpress-pro' ),
					/* Translators: The Google callback URl */
					'desc'  => sprintf( __( 'Enter Your Redirect URI: %1$s?lpsl_login_id=gplus_login', 'loginpress-pro' ), wp_login_url() ),
					'type'  => 'text',
				),
				array(
					'name'  => 'linkedin',
					'label' => __( 'LinkedIn Login', 'loginpress-pro' ),
					'desc'  => __( 'Enable LinkedIn Login', 'loginpress-pro' ),
					'type'  => 'checkbox',
				),
				array(
					'name'  => 'linkedin_client_id',
					'label' => __( 'Client ID', 'loginpress-pro' ),
					'desc'  => sprintf( __( 'Enter Your Client ID.', 'loginpress-pro' ), '<a href="https://wpbrigade.com/">', '</a>' ),
					'type'  => 'text',
				),
				array(
					'name'  => 'linkedin_client_secret',
					'label' => __( 'Client Secret', 'loginpress-pro' ),
					'desc'  => sprintf( __( 'Enter Your Client Secret.', 'loginpress-pro' ), '<a href="https://wpbrigade.com/">', '</a>' ),
					'type'  => 'text',
				),
				array(
					'name'  => 'linkedin_redirect_uri',
					'label' => __( 'Redirect URI', 'loginpress-pro' ),
					/* Translators: The Google callback URl */
					'desc'  => sprintf( __( 'Enter Your Redirect URI: %1$s?lpsl_login_id=linkedin_login', 'loginpress-pro' ), wp_login_url() ),
					'type'  => 'text',
				),
				array(
					'name'  => 'microsoft',
					'label' => __( 'Microsoft Login', 'loginpress-pro' ),
					'desc'  => __( 'Enable Microsoft Login', 'loginpress-pro' ),
					'type'  => 'checkbox',
				),
				array(
					'name'  => 'microsoft_app_id',
					'label' => __( 'Client ID', 'loginpress-pro' ),
					'desc'  => sprintf( __( 'Enter Your Client ID.', 'loginpress-pro' ), '<a href="https://wpbrigade.com/">', '</a>' ),
					'type'  => 'text',
				),
				array(
					'name'  => 'microsoft_app_secret',
					'label' => __( 'Client Secret', 'loginpress-pro' ),
					'desc'  => sprintf( __( 'Enter Your Client Secret.', 'loginpress-pro' ), '<a href="https://wpbrigade.com/">', '</a>' ),
					'type'  => 'text',
				),
				array(
					'name'  => 'microsoft_redirect_uri',
					'label' => __( 'Redirect URI', 'loginpress-pro' ),
					/* Translators: The Microsoft callback URl */
					'desc'  => sprintf( __( 'Enter Your Redirect URI: %1$s', 'loginpress-pro' ), wp_login_url() ),
					'type'  => 'text',
				),
				array(
					'name'    => 'enable_social_login_links',
					'label'   => __( 'Enable Social Login on', 'loginpress-pro' ),
					'desc'    => __( 'Enable Social Login on Login and Register form', 'loginpress-pro' ),
					'type'    => 'multicheck',
					'options' => array(
						'login'    => 'Login Form',
						'register' => 'Register Form',
					),
				),
				array(
					'name'  => 'delete_user_data',
					'label' => __( 'Remove Record On Uninstall', 'loginpress-pro' ),
					'desc'  => __( 'To Remove All LoginPress - Social Logins Record Upon Uninstall.', 'loginpress-pro' ),
					'type'  => 'checkbox',
				),
			);

			$_new_tabs = array( 'loginpress_social_logins' => $_new_tabs );
			return( array_merge( $_new_tabs, $setting_array ) );
		}

		/**
		 * Social Login Admin scripts
		 *
		 * @param int $hook The page ID.
		 *
		 * @version 3.0.0
		 * @return void
		 */
		public function loginpress_social_login_admin_action_scripts( $hook ) {
			if ( 'toplevel_page_loginpress-settings' === $hook ) {
				wp_enqueue_style( 'loginpress-admin-social-login', plugins_url( 'assets/css/style.css', __FILE__ ), array(), LOGINPRESS_PRO_VERSION );
				wp_enqueue_script( 'loginpress-admin-social-login', plugins_url( 'assets/js/main.js', __FILE__ ), false, LOGINPRESS_PRO_VERSION, false );
			}
		}

		/**
		 * Social Login Settings tab's
		 *
		 * @param array $loginpress_tabs The social login addon tabs.
		 *
		 * @return array The Social login setting tabs and their attributes.
		 */
		public function settings_tab( $loginpress_tabs ) {
			$new_tab = array(
				array(
					'id'         => 'loginpress_social_logins',
					'title'      => __( 'Social Login', 'loginpress' ),
					'sub-title'  => __( 'Third Party login access', 'loginpress' ),
					/* Translators: The Social login tabs */
					'desc'       => $this->tab_desc(),
					'video_link' => '45S3i9PJhLA',
				),
			);
			return array_merge( $loginpress_tabs, $new_tab );
		}

		/**
		 * The tab_desc description of the tab 'loginpress settings'
		 *
		 * @since 3.0.0
		 * @return html $html The tab description.
		 */
		public function tab_desc() {

			$html = sprintf( __( '%1$sSocial Login from the LoginPress add-on allows your users to log in and register using their Facebook, Google, and Twitter accounts. By integrating these social media platforms into your login system, you can eliminate spam and bot registrations effectively.%2$s', 'loginpress-pro' ), '<p>', '</p>' );

			$html .= sprintf( __( '%1$s%3$sSettings%4$s %5$sHelp%4$s%2$s', 'loginpress-pro' ), '<div class="loginpress-social-login-tab-wrapper">', '</div>', '<a href="#loginpress_social_login_settings" class="loginpress-social-login-tab loginpress-social-login-active">', '</a>', '<a href="#loginpress_social_login_help" class="loginpress-social-login-tab">' );

			return $html;
		}
		/**
		 * Callback for help tab documentation.
		 *
		 * @version 3.0.0
		 */
		public function loginpress_social_login_help_tab_callback() {

			if ( ! class_exists( 'LoginPress_Promotion_tabs' ) ) {
				include LOGINPRESS_DIR_PATH . 'classes/class-loginpress-promotion.php';
			}
			$video_html = new LoginPress_Promotion_tabs();

			$html  = '<div id="loginpress_social_login_help" class="display">';
			$html .= '<div class="loginpress-social-accordions">';
			$html .= '<a href="#loginpress-facebook-login" class="loginpress-accordions">Facebook Login <span class="dashicons dashicons-arrow-down loginpress-arrow"></span></a>';
			$html .= '<div class="loginpress-social-tabs" id="loginpress-facebook-login">
			<h2>Let\'s integrate Facebook login with LoginPress Social Login.</h2>
			<p>Following are the steps to Create an app on Facebook to use Facebook Login in a web application.</p>
			<h4>Step 1:</h4>
			<ul>
				<li>1.1 Go to <a href="https://developers.facebook.com/" target="_blank">Facebook Developers</a> section and login to your Facebook account, if you are not logged in already. This should not be your business account.</li>
				<li>Log in with your Facebook credentials if you are not logged in.</li>
			</ul>
			<h4>Step 2:</h4>
			<ul>
				<li>2.1 If you are here (at Facebook Developer section) first time, You will be required to “Create a Facebook for Developers account”, if you dont have one</li>
				<li>&nbsp;&nbsp;&nbsp;&nbsp;2.1.1 Click “My Apps” button.</li>
				<li>&nbsp;&nbsp;&nbsp;&nbsp;2.1.2 Click “Create App” button.</li>
				<li>&nbsp;&nbsp;&nbsp;&nbsp;2.1.3 Select “Build Connected Experiences” option and click Continue.</li>
				<li>&nbsp;&nbsp;&nbsp;&nbsp;2.1.4 Fill out the form. <b>( Display Name, Contact Email )</b> and click on “Create App”.</li>
				<li>&nbsp;&nbsp;&nbsp;&nbsp;2.1.5 Add a product to your App. In our case it\'s “Facebook Login”. Click on “Set Up” button under "Facebook Login".</li>
				<li>&nbsp;&nbsp;&nbsp;&nbsp;2.2.6 Select the platform for this app: Here we use "web".</li>
				<li>&nbsp;&nbsp;&nbsp;&nbsp;2.2.7 Enter your web URL <strong>' . esc_html( site_url() ) . '</strong> and save the settings.</li>
			</ul>
			<h4>Step 3:</h4>
			<ul>
				<li>3.1 On Facebook for Developer\'s page, Go to <strong>Settings &gt; Basic</strong> from the left side menu of Facebook.</li>
				<li>3.2 Fill out the required fields and click "Save"
					<li>&nbsp;&nbsp;&nbsp;&nbsp;3.2.1 <strong>Contact Email</strong></li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;3.2.2 <strong>App Domain URL</strong></li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;3.2.3 <strong>Privacy Policy URL</strong> </li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;3.2.4 <strong>Data Deletion Instructions URL</strong></li>
				<li>3.3 Then select the category and press confirm button.</li>
				<li>3.4 Here you will find the App ID and App Secret.</li>
				<li>3.5 Copy that App ID & Secret ID and use it in LoginPress Social Login\'s settings.</li>
				<li>3.6 Save Plugin\'s settings.</li>
			</ul>
			<h4>Step 4:</h4>
			<ul>
				<li>4.1 On Facebook for Developer\'s page, Go to <strong>Facebook Login &gt; Settings</strong> from left side menu.</li>
				<li>4.2 Add valid OAuth redirect URIs here:
					<li>&nbsp;&nbsp;&nbsp;&nbsp;4.2.1 <strong>' . esc_html( wp_login_url() . '?lpsl_login_id=facebook_check' ) . '</strong></li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;4.2.2 <strong>' . esc_html( site_url() . '/admin.php?lpsl_login_id=facebook_check' ) . '</strong></li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;4.2.3 Click on the "Save changes" button. (If you receive a blank page after you pressed the "Save changes" button, kindly refresh the page.)</li>
				</li>
				<li>4.3 On the left side, click on the "<b>App settings</b>" tab, then click "Basic".
					<li>&nbsp;&nbsp;&nbsp;&nbsp;4.3.1 Enter your domain name to the "App Domains" field, probably: ' . site_url() . '</li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;4.3.2 Fill up the "Privacy Policy URL" field. Provide a publicly available and easily accessible privacy policy that explains what data you are collecting and how you will use that data.</li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;4.3.3 At "User Data Deletion", choose the "Data Deletion Instructions URL" option, and enter the URL of your page* with the instructions on how users can delete their accounts on your site.</li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;4.3.4 To comply with GDPR, you should already offer possibility to delete accounts on your site, either by the user or by the admin.</br>
					If each user has an option to delete the account: the URL should point to a guide showing the way users can delete their accounts.</li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;4.3.5 Select a "Category", an "App Icon". (Optional)</li>
					<li>&nbsp;&nbsp;&nbsp;&nbsp;4.3.6 Press the "Save changes" button.</li>
				</li>
			</ul>
			<h4>Step 5:</h4>
			<ul>
				<li>5.1 By default, your application only has Standard Access for the "public_profile" and "email" permissions, which means that only you can log in with it. To get Advanced Access you will need to go trough the <b>Business Verification</b>, that you can start on the "Verification" tab on the left side.</li>
				<li>5.2 Currently your app is in Development Mode which also means that people outside of your business can not use it. Once your verification is completed, click on the "Go live" tab and publish your app by clicking on the "Go live" button at the bottom right corner. Before you press it, it is recommended to check the steps listed on the "Go live" page, if you configured everything properly.</li>
				<li>5.3 After everything is done, click on the "App settings" tab, then click "Basic".</li>
				<li>5.4 At the top of the page you can find your "App ID" and you can see your "App secret" if you click on the Show button. These will be needed in plugin’s settings</li>
			<ul>

			</ul>';
			$html .= $video_html->_addon_video( 'Helping video for Facebook Authentication', '45S3i9PJhLA' ) . '</div></div>';
			$html .= '<div class="loginpress-social-accordions">';
			$html .= '<a href="#loginpress-facebook-login" class="loginpress-accordions">Twitter Login <span class="dashicons dashicons-arrow-down loginpress-arrow"></span></a>';
			$html .= '<div class="loginpress-social-tabs" id="loginpress-twitter-login">
			<h2>Let\'s integrate Twitter login with LoginPress Social Login.</h2>
			<p>Following are the steps to create an app on Twitter to use Twitter Login in a web application.</p>
			<h4>Step 1:</h4>
			<ul>
				<li>1.1 You must register your website with Twitter at <a href="https://developer.twitter.com/en/apps" target="_blank">https://developer.twitter.com/en/apps</a>.</li>
				<li>1.2 Click on “Create an App” Button and fill out the required informational fields.</li>
				<li>&nbsp;&nbsp;1.2.1 Website URL: <strong>' . esc_html( site_url() ) . '</strong></li>
				<li>&nbsp;&nbsp;1.2.2 Callback URL: <strong>' . esc_html( wp_login_url() ) . '</strong></li>
				<li>1.3 Click on "Create" button.</li>
				<li>1.4 After that, a popup will appear for “Review Developer Terms”. Read the terms and click on create button.</li>
			</ul>
			<h4>Step 2:</h4>
			<ul>
				<li>2.1 Go to “Keys and tokens” tab.</li>
				<li>2.2 Click on Regenerate to get new Keys.</li>
				<li>2.3 A prompt will appear to verify the regeneration of keys, Click Yes, regenerate.</li>
				<li>2.4 Copy these API Key and API Key Secret and use it in plugin settings.</li>
				<li>2.5 Choose the "<b>Read</b>" option at "<b>App permission</b>". If you want to get the email address as well, then don’t forget to enable the "<b>Request email from users</b>" option. In this case you also need to fill the "<b>Terms of service</b>" and the "<b>Privacy policy</b>" fields with the corresponding URLs!</li>
				<li>2.5 Save the settings and enjoy.</li>
			</ul>';
			$html .= $video_html->_addon_video( 'Helping video for Twitter Authentication', '9-JZFistVpM' ) . '</div></div>';
			$html .= '<div class="loginpress-social-accordions">';
			$html .= '<a href="#loginpress-facebook-login" class="loginpress-accordions">Google Login <span class="dashicons dashicons-arrow-down loginpress-arrow"></span></a>';
			$html .= '<div class="loginpress-social-tabs" id="loginpress-gplus-login">
			<h2>Let\'s integrate Google login with LoginPress Social Login.</h2>
			<p>Following are the steps to Create an app on Google to use Google Login in a web application.</p>
			<h4>Step 1:</h4>
			<ul>
			<li>1.1 You must register your website with Google APIs at <a href="https://console.developers.google.com/" target="_blank">https://console.developers.google.com/</a>.</li>
			<li>1.2 Click on <b>New Project</b> button and fill out the required informational field. <b>(Project Name and Location).</b></li>
				<li>&nbsp;&nbsp;1.2.1 If you have more then 1 project in Google APIs, please confirm your project from top left dropdown project list.</li>
				<li>1.3 Click on “OAuth consent screen” from the left side menu.</li>
				<li>&nbsp;&nbsp;1.3.1. For User Type choose “External”.</li>
				<li>&nbsp;&nbsp;1.3.2. Fill out the required informational fields. (Application Name, App domain links and Authorized domains).</li>
				<li>&nbsp;&nbsp;1.3.3. Your Site URL is <strong>' . esc_html( site_url() ) . '</strong></li>
				<li>&nbsp;&nbsp;1.3.4. For Scopes section leave everything as it is and click “Save and Continue” </li>
				<li>&nbsp;&nbsp;1.3.5. For Test Users section leave everything be and click “Save and Continue” </li>
			<li>1.4 Click Back to Dashboard.</li>
			</ul>
			<h4>Step 2:</h4>
			<ul>
				<li>2.1 Go to the Credentials page from left side-bar.</li>
				<li>2.2 Please select “Create Credentials” and select “OAuth client ID” from the dropdown.</li>
				<li>2.3 Select the Application type here. In our case it\'s “Web application”.</li>
				<li>2.4 Fill out the required informational fields (Name of your Application & Authorized redirect URIs) save the settings.</li>
				<li>&nbsp;&nbsp;2.4.1 Authorized redirect URIs: <strong>' . esc_html( wp_login_url() . '?lpsl_login_id=gplus_login' ) . '</strong></li>
			</ul>
			<h4>Step 3:</h4>
			<ul>
				<li>3.1 After saving the settings, a popup will appear with “OAuth Client Created” heading. Copy the <b>Client ID</b> and <b>Client Secret</b> from here and use it in our plugin setting.</li>
				<li>3.2 Save the settings and enjoy.</li>
			</ul>';
			$html .= $video_html->_addon_video( 'Helping video for Google Authentication', 'EReYVYmdyeY' ) . '</div></div>';
			$html .= '<div class="loginpress-social-accordions">';
			$html .= '<a href="#loginpress-facebook-login" class="loginpress-accordions">LinkedIn Login <span class="dashicons dashicons-arrow-down loginpress-arrow"></span></a>';
			$html .= '<div class="loginpress-social-tabs" id="loginpress-linkedin-login">
			<h2>Let\'s integrate LinkedIn login with LoginPress Social Login.</h2>
			<p>Following are the steps to create an app on Linkedin to use Signin with LinkedIn using LoginPress.</p>
			<ol>
				<li>You must register your website with LinkedIn at <a href="https://developer.linkedin.com/" target="_blank">https://developer.linkedin.com/</a></li>
				<li>Click on <a href="https://www.linkedin.com/developers/apps/new" target="_blank">My Apps</a> to Create a LinkedIn Application and fill out the required informational fields on the form.</li>
				<li>Read and agree the "API Terms of Use" then click the "Create App" button!
				You will end up in the products area. If you aren\'t already there click on the "Products" tab.</li>
				<li>Find "Sign In with LinkedIn using "<b>OpenID Connect</b>" and click "<b>Request access</b>".</li>
				<li>A modal will appear where you need to tick the "I have read and agree to these terms" checkbox and finally press the "<b>Request access</b>" button.
				Click on the "<b>Auth</b>" tab.</li>
				<li>After submitting the form, Check out the Auth tab in your newly created App. Auth tab will have Redirect URLs and Credentials.</li>
				<li>Copy this <strong>' . esc_html( wp_login_url() . '?lpsl_login_id=linkedin_login' ) . '</strong> link and paste in Authorized Redirect URLs.</li>
				<li>Copy that Client ID &amp; Client Secret from Auth Tab and paste it in plugin settings.</li>
				<li>Save the settings of Social Login.</li>
				<li>Logout from WordPress and checkout the login page again to see the LinkedIn Sign In in effect.</li>
			</ol>';
			$html .= $video_html->_addon_video( 'Helping video for LinkedIn Authentication', 'HHmG4pZ7atM' ) . '</div></div>';
			$html .= '<div class="loginpress-social-accordions">';
			$html .= '<a href="#loginpress-microsoft-login" class="loginpress-accordions">Microsoft Login <span class="dashicons dashicons-arrow-down loginpress-arrow"></span></a>';
			$html .= '<div class="loginpress-social-tabs" id="loginpress-gplus-login">
			<h2>Let\'s integrate Microsoft login with LoginPress Social Login.</h2>
			<p>Following are the steps to Create an app on Microsoft to use Microsoft Login in a web application.</p>
			<h4>Step 1:</h4>
			<ul>
				<li>1. Navigate to <a href="https://portal.azure.com/" target="_blank">https://portal.azure.com/</a></li>
				<li>2. Log in with your Microsoft Azure credentials if you are not logged in or create a new account.</li>
				<li>3. Click on the Search bar and search for “App registrations”.</li>
				<li>4. Click on “New registration”.</li>
				<li>5. Fill the “Name” field with your App Name.</li>
				<li>6. Select an option at Supported account types.</li>
				<li>7. <b>Important:</b> On our Settings tab, you will need to select the Audience (Users with a Microsoft work or school account in any organization’s Azure AD tenant (for example, multi-tenant).</li>
				<li>8. At the “Redirect URI (optional)” field, select the “Web” option as a platform.</li>
				<li>9. Add this URL <b>' . site_url( '/wp-login.php' ) . '</b>.</li>
			</ul>
			<h4>Step 2:</h4>
			<ul>
				<li>2.1 Create your App with the “Register” button.</li>
				<li>2.2 You land on the “Overview” page.</li>
				<li>2.3 Copy the “Application (client) ID”, this will be the Application (client) ID in the plugin settings.</li>
				<li>2.4 Click on the link named “Add a certificate or secret” next to the Client credentials label.</li>
				<li>2.5 Click on “New client secret”.</li>
				<li>2.6 Fill the “Description” field.</li>
				<li>2.7 Set the expiration date at the “Expires” field.</li>
				<li>2.8 Then create your Client Secret with the “Add” button.</li>
				<li>2.9 Copy the “Value”, this will be the Client secret in the plugin settings.</li>
			</ul>
			<ul>
			<h4>Step 3:</h4>
				<li>3.1 Save the settings of Social Login.</li>
				<li>3.2 Logout from WordPress and checkout the login page again to see the Microsoft Sign In in effect.</li>
			<ul>';
			$html .= '</div></div>';

			$html .= '</div>';
			echo $html; // @codingStandardsIgnoreLine.
		}


		/**
		 * Main Instance
		 *
		 * @version 3.0.0
		 * @static
		 * @see loginPress_social_loader()
		 * @return Main instance
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}


		/**
		 * Starts the session with the call of init hook.
		 *
		 * @version 3.0.0
		 */
		public function session_init() {
			if ( isset( $_GET['lpsl_login_id'] ) ) { // @codingStandardsIgnoreLine.
				if ( ! session_id() && ! headers_sent() ) {
					session_start();
				}
			}

			include_once LOGINPRESS_SOCIAL_DIR_PATH . 'classes/loginpress-social-check.php';
		}

		/** Check to see if the current page is the login/register page.
		 *
		 * @version 3.0.0
		 * @return bool
		 */
		public function is_login_page() {
			$total_pages          = array( 'wp-login.php', 'wp-register.php' );
			$translate_press_page = array( 'index.php' );

			// If TranslatePress plugin is activated add span tag on login page for "OR".
			if ( is_plugin_active( 'translatepress-multilingual/index.php' ) ) {
				$total_pages = array_merge( $total_pages, $translate_press_page );
			}
			return in_array( $GLOBALS['pagenow'], $total_pages, true );
		}

		/**
		 * Social login shortcode callback.
		 *
		 * @param array $atts attributes of shortcode.
		 * @version 3.0.0
		 */
		public function loginpress_social_login_shortcode( $atts ) {

			$atts = shortcode_atts(
				array(
					'disable_google'     => 'false',
					'disable_facebook'   => 'false',
					'disable_twitter'    => 'false',
					'disable_linkedin'   => 'false',
					'disable_microsoft'  => 'false',
					'display'            => 'row',
					'social_redirect_to' => 'true',
				),
				$atts
			);

			$this->is_shortcode = true;

			ob_start();
			if ( ! is_user_logged_in() ) {
				?>
				<div class="loginpress-sl-shortcode-wrapper">
					<?php $this->loginpress_social_login( $atts ); ?> 
				</div>
				<?php
			}
			return ob_get_clean();
		}

		/**
		 * HTML structure for social login buttons.
		 *
		 * @param array $atts attributes of shortcode.
		 * @version 3.0.0
		 */
		public function loginpress_social_login( $atts ) {

			if ( ! self::check_social_api_status() ) {
				return;
			}

			if ( is_user_logged_in() ) {
				return;
			}
			// Enqueue Styles for short-code.
			wp_enqueue_style( 'loginpress-social-login', plugins_url( 'assets/css/login.css', __FILE__ ), array(), LOGINPRESS_PRO_VERSION );
			$redirect_to = isset( $_REQUEST['redirect_to'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['redirect_to'] ) ) : ''; // @codingStandardsIgnoreLine.
			$encoded_url = '';

			if ( ! empty( $atts['social_redirect_to'] ) && 'true' === $atts['social_redirect_to'] ) {
				$is_redirect = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
				$encoded_url = 'true' === $atts['social_redirect_to'] ? site_url() . $is_redirect : site_url();
				$redirect_to = rawurlencode( $encoded_url );
			}

			$encoded_url   = rawurlencode( $redirect_to );
			$display_style = ( isset( $atts['display'] ) && 'column' === $atts['display'] ) ? 'block loginpress-social-display-col' : 'block';
			?>

			<div class='social-networks <?php echo esc_attr( $display_style ); ?>'>

				<?php
				if ( $this->is_login_page() ) :
					?>
					<?php $separator_text = apply_filters( 'loginpress_social_login_separator', __( 'or', 'loginpress-pro' ) ); ?>
					<span class="social-sep"><span><?php echo esc_html__( $separator_text ); ?></span></span>
					<?php
				endif;

				do {
					if ( true === $this->is_shortcode && 'true' === $atts['disable_google'] ) {
						break;
					}

					if ( isset( $this->settings['gplus'] ) && 'on' === $this->settings['gplus'] && ! empty( $this->settings['gplus_client_id'] ) && ! empty( $this->settings['gplus_client_secret'] ) ) :
						$encoded_url = isset( $encoded_url ) ? '&state=' . base64_encode( 'redirect_to=' . $encoded_url ) . '&redirect_to=' . $redirect_to : ''; // @codingStandardsIgnoreLine.
						?>

						<a href="<?php echo esc_url_raw( wp_login_url() . '?lpsl_login_id=gplus_login' . $encoded_url ); ?>" title="<?php esc_html_e( 'Login with Google', 'loginpress-pro' ); ?>" rel="nofollow">
							<div class="lpsl-icon-block icon-google-plus clearfix">
								<span class="lpsl-login-text"><?php esc_html_e( 'Login with Google', 'loginpress-pro' ); ?></span>
								<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 48 48" class="abcRioButtonSvg lpsl-google-svg"><g><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path><path fill="none" d="M0 0h48v48H0z"></path></g></svg>
							</div>
						</a> 
						<?php
					endif;
				} while ( 0 );

				do {
					if ( true === $this->is_shortcode && 'true' === $atts['disable_facebook'] ) {
						break;
					}

					if ( isset( $this->settings['facebook'] ) && 'on' === $this->settings['facebook'] && ! empty( $this->settings['facebook_app_id'] ) && ! empty( $this->settings['facebook_app_secret'] ) ) :
						$encoded_url = isset( $encoded_url ) ? '&state=' . base64_encode( 'redirect_to=' . $encoded_url ) . '&redirect_to=' . $redirect_to : ''; // @codingStandardsIgnoreLine.
						?>

						<a href="<?php echo esc_url_raw( wp_login_url() . '?lpsl_login_id=facebook_login' . $encoded_url ); ?>" title="<?php esc_html_e( 'Login with Facebook', 'loginpress-pro' ); ?>" rel="nofollow">
							<div class="lpsl-icon-block icon-facebook clearfix">
								<span class="lpsl-login-text"><?php esc_html_e( 'Login with Facebook', 'loginpress-pro' ); ?></span>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="#43609c" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>
							</div>
						</a> 
						<?php
					endif;
				} while ( 0 );

				do {
					if ( true === $this->is_shortcode && 'true' === $atts['disable_twitter'] ) {
						break;
					}

					if ( isset( $this->settings['twitter'] ) && 'on' === $this->settings['twitter'] && ! empty( $this->settings['twitter_oauth_token'] ) && ! empty( $this->settings['twitter_token_secret'] ) ) :
						$encoded_url = isset( $encoded_url ) ? '&state=' . base64_encode( 'redirect_to=' . $encoded_url ) . '&redirect_to=' . $redirect_to : ''; // @codingStandardsIgnoreLine.
						?>

						<a href="<?php echo esc_url_raw( wp_login_url() . '?lpsl_login_id=twitter_login' . $encoded_url ); ?>" title="<?php esc_html_e( 'Login with Twitter', 'loginpress-pro' ); ?>" rel="nofollow">
							<div class="lpsl-icon-block icon-twitter clearfix">

								<span class="lpsl-login-text"><?php esc_html_e( 'Login with Twitter', 'loginpress-pro' ); ?></span>
								<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 30 30" width="30px" height="30px"><path d="M26.37,26l-8.795-12.822l0.015,0.012L25.52,4h-2.65l-6.46,7.48L11.28,4H4.33l8.211,11.971L12.54,15.97L3.88,26h2.65 l7.182-8.322L19.42,26H26.37z M10.23,6l12.34,18h-2.1L8.12,6H10.23z"/></svg>
							</div>
						</a> 
						<?php
					endif;
				} while ( 0 );

				do {
					if ( true === $this->is_shortcode && 'true' === $atts['disable_linkedin'] ) {
						break;
					}

					if ( isset( $this->settings['linkedin'] ) && 'on' === $this->settings['linkedin'] && ! empty( $this->settings['linkedin_client_id'] ) && ! empty( $this->settings['linkedin_client_secret'] ) ) :
						$encoded_url = isset( $encoded_url ) ? '&state=' . base64_encode( 'redirect_to=' . $encoded_url ) . '&redirect_to=' . $redirect_to : ''; // @codingStandardsIgnoreLine.
						?>

						<a href="<?php echo esc_url_raw( wp_login_url() . '?lpsl_login_id=linkedin_login' . $encoded_url ); ?>" title="<?php esc_html_e( 'Login with LinkedIn', 'loginpress-pro' ); ?>" rel="nofollow">
							<div class="lpsl-icon-block icon-linkdin clearfix">

								<span class="lpsl-login-text"><?php esc_html_e( 'Login with LinkedIn', 'loginpress-pro' ); ?></span>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="#0076b4" d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>
							</div>
						</a>

						<?php
					endif;
				} while ( 0 );
				do {
					if ( true === $this->is_shortcode && 'true' === $atts['disable_microsoft'] ) {
						break;
					}
					if ( isset( $this->settings['microsoft'] ) && 'on' === $this->settings['microsoft'] && ! empty( $this->settings['microsoft_app_id'] ) && ! empty( $this->settings['microsoft_app_secret'] ) ) :
						$encoded_url = isset( $encoded_url ) ? '&state=' . base64_encode( 'redirect_to=' . $encoded_url ) . '&redirect_to=' . $redirect_to : ''; // @codingStandardsIgnoreLine.
						?>

						<a href="<?php echo esc_url_raw( wp_login_url() . '?lpsl_login_id=microsoft_login' . $encoded_url ); ?>" title="<?php esc_html_e( 'Login with Microsoft', 'loginpress-pro' ); ?>" rel="nofollow">
							<div class="lpsl-icon-block icon-microsoft clearfix">
								<span class="lpsl-login-text"><?php esc_html_e( 'Login with Microsoft', 'loginpress-pro' ); ?></span>
								<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" width="48px" height="48px"><path fill="#ff5722" d="M6 6H22V22H6z" transform="rotate(-180 14 14)"/><path fill="#4caf50" d="M26 6H42V22H26z" transform="rotate(-180 34 14)"/><path fill="#ffc107" d="M26 26H42V42H26z" transform="rotate(-180 34 34)"/><path fill="#03a9f4" d="M6 26H22V42H6z" transform="rotate(-180 14 34)"/></svg>
							</div>
						</a> 
						<?php
					endif;
				} while ( 0 );
				?>
			</div>
			<?php
		}

		/**
		 * Check Social Media Status from settings API.
		 *
		 * @version 3.0.0
		 * @return boolean
		 */
		public static function check_social_api_status() {
			$options = get_option( 'loginpress_social_logins' );

			if ( ( ( isset( $options['gplus'] ) && 'on' === $options['gplus'] ) && ( ! empty( $options['gplus_client_id'] ) && ! empty( $options['gplus_client_secret'] ) ) )
			|| ( ( isset( $options['facebook'] ) && 'on' === $options['facebook'] ) && ( ! empty( $options['facebook_app_id'] ) && ! empty( $options['facebook_app_secret'] ) ) )
			|| ( ( isset( $options['twitter'] ) && 'on' === $options['twitter'] ) && ( ! empty( $options['twitter_oauth_token'] ) && ! empty( $options['twitter_token_secret'] ) ) )
			|| ( ( isset( $options['microsoft'] ) && 'on' === $options['microsoft'] ) && ( ! empty( $options['microsoft_app_id'] ) && ! empty( $options['microsoft_app_secret'] ) ) )
			|| ( ( isset( $options['linkedin'] ) && 'on' === $options['linkedin'] ) && ( ! empty( $options['linkedin_client_id'] ) && ! empty( $options['linkedin_client_secret'] ) ) ) ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Include Social LoginPress script in footer.
		 *
		 * @version 3.0.0
		 */
		public function login_page_custom_footer() {

			if ( ! self::check_social_api_status() ) {
				return;
			}

			include LOGINPRESS_SOCIAL_DIR_PATH . 'assets/js/script-login.php';
		}

		/**
		 * Delete user row form the table.
		 *
		 * @param int $user_id The user ID.
		 *
		 * @return void
		 */
		public function delete_user_row( $user_id ) {
			global $wpdb;

			$sql = "DELETE FROM `{$wpdb->prefix}loginpress_social_login_details` WHERE `user_id` = '$user_id'";
			$wpdb->query( $sql );
		}


		/**
		 * Plugin activation for check multi site activation
		 *
		 * @param array $network_wide all networks.
		 *
		 * @return void
		 */
		public static function loginpress_social_activation( $network_wide ) {
			if ( function_exists( 'is_multisite' ) && is_multisite() && $network_wide ) {
				global $wpdb;
				// Get this so we can switch back to it later.
				$current_blog = $wpdb->blogid;
				// Get all blogs in the network and activate plugin on each one.
				$blog_ids = $wpdb->get_col( $wpdb->prepare( "SELECT blog_id FROM %s", $wpdb->blogs ) ); // @codingStandardsIgnoreLine.
				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::loginpress_social_create_table();
				}
				switch_to_blog( $current_blog );
				return;
			} else {
				self::loginpress_social_create_table();
			}
		}

		/**
		 * Create DB table on plugin activation.
		 *
		 * @version 3.0.0
		 * @version 1.0.5
		 */
		public static function loginpress_social_create_table() {

			global $wpdb;
			// Create user details table.
			$table_name = "{$wpdb->prefix}loginpress_social_login_details";

			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
				id int(11) NOT NULL AUTO_INCREMENT,
				user_id int(11) NOT NULL,
				provider_name varchar(50) NOT NULL,
				identifier varchar(255) NOT NULL,
				sha_verifier varchar(255) NOT NULL,
				email varchar(255) NOT NULL,
				email_verified varchar(255) NOT NULL,
				first_name varchar(150) NOT NULL,
				last_name varchar(150) NOT NULL,
				profile_url varchar(255) NOT NULL,
				website_url varchar(255) NOT NULL,
				photo_url varchar(255) NOT NULL,
				display_name varchar(150) NOT NULL,
				description varchar(255) NOT NULL,
				gender varchar(10) NOT NULL,
				language varchar(20) NOT NULL,
				age varchar(10) NOT NULL,
				birthday int(11) NOT NULL,
				birthmonth int(11) NOT NULL,
				birthyear int(11) NOT NULL,
				phone varchar(75) NOT NULL,
				address varchar(255) NOT NULL,
				country varchar(75) NOT NULL,
				region varchar(50) NOT NULL,
				city varchar(50) NOT NULL,
				zip varchar(25) NOT NULL,
				UNIQUE KEY id (id),
				KEY user_id (user_id),
				KEY provider_name (provider_name)
			) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
		}

		/**
		 * Load assets on login screen.
		 *
		 * @version 3.0.0
		 */
		public function load_login_assets() {

			wp_enqueue_style( 'loginpress-social-login', plugins_url( 'assets/css/login.css', __FILE__ ), array(), LOGINPRESS_PRO_VERSION );
		}

		/**
		 * The Custom error message of
		 *
		 * @param string $message The error message when user registrations are failed.
		 * @return string $message Modified error message when user registrations are failed.
		 */
		public function loginpress_social_login_register_error( $message ) {
			if ( isset( $_GET['lp_social_error'] ) ) {
				/* translators: Error message for social login. */
				$default_message = sprintf( __( '%1$sERROR%2$s: This Social Provider Only Support Certain Domains Only.', 'loginpress-pro' ), '<strong>', '</strong>' );
				$message         = apply_filters( 'loginpress_change_sl_restrict_message', $default_message );
				return '<p id="login_error" class="notice notice-error">' . wp_kses_post( $message ) . '</p>';
			}
			return $message;
		}
	}

endif;


if ( ! function_exists( 'loginpress_social_loader' ) ) {

	/**
	 * Returns the main instance of WP to prevent the need to use globals.
	 *
	 * @version 3.0.0
	 * @return LoginPress instance of Social Login class
	 */
	function loginpress_social_loader() {
		return LoginPress_Social::instance();
	}
}

add_action( 'plugins_loaded', 'loginpress_sl_instance', 25 );

/**
 * Check if LoginPress Pro is install and active.
 *
 * @version 3.0.0
 */
function loginpress_sl_instance() {

	// Call the function.
	loginpress_social_loader();
}

