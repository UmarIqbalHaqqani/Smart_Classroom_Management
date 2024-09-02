<?php
/**
 * LoginPress_HideLogin_Main class
 *
 * @package LoginPress
 * @category Core
 * @author WPBrigade
 * @version 3.0.0
 */

if ( ! class_exists( 'LoginPress_HideLogin_Main' ) ) :


	/**
	 * LoginPress_HideLogin_Main class
	 */
	class LoginPress_HideLogin_Main {

		/** * * * * * * * * *
		 *
		 * @since  3.0.0
		 * @access private
		 * @var    bool
		 * * * * * * * * * * */
		private $wp_login_php;

		/**
		 * The Instance of Hide Login Class
		 *
		 * @var object
		 */
		protected static $instance = null;

		/**
		 * The LoginPress Hide Login Slug
		 *
		 * @var object
		 */
		private $slug;

		/**
		 * Class Constructor
		 *
		 * @since 1.0.0
		 * @version 3.0.0
		 */
		public function __construct() {

			$this->hooks();
		}

		/**
		 * Hook into actions and filters
		 *
		 * @since 1.0.0
		 */
		private function hooks() {

			add_action( 'admin_enqueue_scripts', array( $this, 'loginpress_hidelogin_admin_action_scripts' ) );
			add_filter( 'loginpress_settings_tab', array( $this, 'loginpress_hidelogin_tab' ), 10, 1 );
			add_filter( 'loginpress_settings_fields', array( $this, 'loginpress_hidelogin_settings_array' ), 10, 1 );
			add_filter( 'loginpress_hidelogin', array( $this, 'loginpress_hidelogin_callback' ), 10, 2 );
			// add_action( 'admin_init', array( $this, 'init_addon_updater' ), 0 );.

			$loginpress_hidelogin = get_option( 'loginpress_hidelogin' );
			$this->slug           = isset( $loginpress_hidelogin['rename_login_slug'] ) ? $loginpress_hidelogin['rename_login_slug'] : '';

			if ( ! empty( $this->slug ) ) {
				add_action( 'plugins_loaded', array( $this, 'loginpress_hidelogin_loaded' ), 30 );
				add_action( 'wp_loaded', array( $this, 'loginpress_hidelogin_wp_loaded' ) );
				add_filter( 'site_url', array( $this, 'site_url' ), 10, 4 );
				add_filter( 'network_site_url', array( $this, 'network_site_url' ), 10, 3 );
				add_filter( 'wp_redirect', array( $this, 'wp_redirect' ), 10, 2 );
				add_action( 'wp_ajax_reset_login_slug', array( $this, 'loginpress_hidelogin_reset_login_slug' ) );

				remove_action( 'template_redirect', 'wp_redirect_admin_locations', 1000 );
			}
		}

		/**
		 * LoginPress Addon updater
		 */
		public function init_addon_updater() {
			if ( class_exists( 'LoginPress_AddOn_Updater' ) ) {
				$updater = new LoginPress_AddOn_Updater( 2162, LOGINPRESS_HIDE_ROOT_FILE, $this->version );
			}
		}

		/**
		 * Reset login slug back to wp-login.php
		 *
		 * @version 3.0.0
		 * @access public
		 */
		public function loginpress_hidelogin_reset_login_slug() {

			check_ajax_referer( 'loginpress-reset-login-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}
			// Handle request then generate response using WP_Ajax_Response.
			$loginpress_hidelogin = get_option( 'loginpress_hidelogin' );
			$slug                 = isset( $loginpress_hidelogin['rename_login_slug'] ) ? $loginpress_hidelogin['rename_login_slug'] : '';

			$loginpress_hidelogin_new_val = array();

			foreach ( $loginpress_hidelogin as $key => $value ) {

				if ( 'rename_login_slug' === $key ) {
					$value = '';
				}
				$loginpress_hidelogin_new_val[ $key ] = $value;
			}

			update_site_option( 'loginpress_hidelogin', $loginpress_hidelogin_new_val );
			wp_die();
		}

		/**
		 * The loginpress_hidelogin_tab Setting tab for HideLogin.
		 *
		 * @param array $loginpress_tabs Tabs of free version.
		 * @return array $hidelogin_tab HideLogin tab.
		 */
		public function loginpress_hidelogin_tab( $loginpress_tabs ) {

			$_hidelogin_tab = array(
				array(
					'id'         => 'loginpress_hidelogin',
					'title'      => __( 'Hide Login', 'loginpress-pro' ),
					'sub-title'  => __( 'Hide your login page', 'loginpress' ),
					'desc'       => $this->tab_desc(),
					'video_link' => 'FSE2BH_biZg',
				),
			);
			$hidelogin_tab  = array_merge( $loginpress_tabs, $_hidelogin_tab );

			return $hidelogin_tab;
		}

		/**
		 * The loginpress_hidelogin_settings_array Setting Fields for HideLogin.
		 *
		 * @param array $setting_array Settings fields of free version.
		 * @return array $setting_array HideLogin settings fields after merging.
		 */
		public function loginpress_hidelogin_settings_array( $setting_array ) {

			$_hidelogin_settings = array(
				array(
					'name'              => 'rename_login_slug',
					'label'             => __( 'Rename Login Slug', 'loginpress-pro' ),
					'default'           => __( 'mylogin', 'loginpress-pro' ),
					'desc'              => $this->rename_login_slug_desc(),
					'type'              => 'hidelogin',
					'sanitize_callback' => array( $this, 'sanitize_login_slug' ),
				),
				array(
					'name'              => 'is_rename_send_email',
					'label'             => __( 'Send Email', 'loginpress-pro' ),
					'desc'              => $this->is_rename_send_email_desc(),
					'type'              => 'checkbox',
					'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
				),
				array(
					'name'              => 'rename_email_send_to',
					'label'             => __( 'Email Address', 'loginpress-pro' ),
					'default'           => get_option( 'admin_email' ),
					'desc'              => __( 'Use comma (,) to add more than 1 recipients.', 'loginpress-pro' ),
					'type'              => 'email',
					'multiple'          => true,
					'sanitize_callback' => array( $this, 'sanitize_email' ),
				),
			);
			$hidelogin_settings  = array( 'loginpress_hidelogin' => $_hidelogin_settings );
			return( array_merge( $hidelogin_settings, $setting_array ) );
		}

		/**
		 * Checkbox sanitization callback example.
		 *
		 * Sanitization callback for 'checkbox' type controls. This callback sanitizes `$checked`
		 * as a boolean value, either TRUE or FALSE.
		 *
		 * @param bool $checked Whether the checkbox is checked.
		 * @return bool Whether the checkbox is checked.
		 */
		public function sanitize_checkbox( $checked ) {

			// Boolean check.
			return ( ( isset( $checked ) && 'on' === $checked ) ? 'on' : 'off' );
		}

		/**
		 * Sanitize email address
		 *
		 * @param array $emails emails to be sanitized.
		 * @since 1.1.4
		 * @version 3.0.0
		 * @return $emails
		 */
		public function sanitize_email( $emails ) {

			$emails = explode( ',', $emails );

			foreach ( $emails as $email => $value ) {
				$emails[ $email ] = sanitize_email( $value );
			}

			$emails = implode( ',', $emails );

			return $emails;
		}


		/**
		 * Sanitize login url slug.
		 *
		 * Only alpha-numeric characters and dashes are allowed
		 * string will transformed to lower-case and spaces will
		 * remove.
		 *
		 * @param string $slug the slug which is chosen.
		 * @return $slug
		 */
		public function sanitize_login_slug( $slug ) {

			$slug = trim( $slug );
			$slug = preg_replace( '/[^A-Za-z0-9_\.-]/', '', $slug );
			$slug = strtolower( $slug );

			if ( 'wp-admin' === $slug ) {
				$slug = '';
			}

			return $slug;
		}

		/**
		 * Function hidelogin_use_slashes is used to check the trailing slashes
		 *
		 * @return permalink structure
		 */
		private function hidelogin_use_slashes() {

			return ( '/' === substr( get_option( 'permalink_structure' ), -1, 1 ) );
		}


		/**
		 * The hidelogin_user_trailing description
		 *
		 * @param string $string description.
		 * @return string Trailing slash or no trailing slash.
		 */
		private function hidelogin_user_trailing( $string ) {

			return $this->hidelogin_use_slashes() ? trailingslashit( $string ) : untrailingslashit( $string );
		}

		/**
		 * The Template Loader
		 *
		 * @return void
		 */
		private function wp_template_loader() {
			global $pagenow;

			$pagenow = 'index.php'; // @codingStandardsIgnoreLine.

			if ( ! defined( 'WP_USE_THEMES' ) ) {
				define( 'WP_USE_THEMES', true );
			}
			wp();
			if ( isset( $_SERVER['REQUEST_URI'] ) && $_SERVER['REQUEST_URI'] === $this->hidelogin_user_trailing( str_repeat( '-/', 10 ) ) ) {
				$_SERVER['REQUEST_URI'] = $this->hidelogin_user_trailing( '/wp-login-php/' );
			}
			require_once ABSPATH . WPINC . '/template-loader.php';
			die;
		}

		/**
		 * Create the new login slug
		 *
		 * @since 1.0.0
		 * @version 3.0.0
		 * @return string $slug The New Login Slug.
		 */
		private function new_login_slug() {

			$loginpress_hidelogin = get_option( 'loginpress_hidelogin' );
			$slug                 = isset( $loginpress_hidelogin['rename_login_slug'] ) ? $loginpress_hidelogin['rename_login_slug'] : 'mylogin';

			return $slug;
		}

		/**
		 * New Login URL
		 *
		 * @param string $scheme The Scheme.
		 * @return string $url New Login URL.
		 */
		public function new_login_url( $scheme = null ) {

			if ( get_option( 'permalink_structure' ) ) {

				return $this->hidelogin_user_trailing( home_url( '/', $scheme ) . $this->new_login_slug() );
			} else {

				return home_url( '/', $scheme ) . '?' . $this->new_login_slug();
			}
		}

		/**
		 * Main Instance
		 *
		 * @since 1.0.0
		 * @static
		 * @see loginpress_hidelogin_loader()
		 *
		 * @return object Main instance of the Class
		 */
		public static function instance() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * The tab_desc description of the tab 'loginpress settings'
		 *
		 * @since 1.0.0
		 * @version 3.0.0
		 * @return html $html The tab description.
		 */
		public function tab_desc() {

			$html = '<p>';

			if ( ! is_multisite() || is_super_admin() ) {

				/* translators: %1$s: LoginPress Hide-Login addon description in the settings. */
				$html .= sprintf( __( '%1$sThe Hide Login add-on for LoginPress lets you change the login page URL according to your preference. It will give a hard time to spammers who keep hitting your login page, preventing Brute force attacks.%2$s', 'loginpress-pro' ), '<p>', '</p>' );

			} elseif ( is_multisite() && is_super_admin() && is_plugin_active_for_network( LOGINPRESS_HIDE_PLUGIN_BASENAME ) ) {
				// Tab description if multi-site is enabled.
				/* translators: %s: network admin url */
				$html .= sprintf( __( 'To set a networkwide default, go to <a href="%s">Network Settings</a>.', 'loginpress-pro' ), network_admin_url( 'settings.php#whl-page-input' ) );
			}
			$html .= '</p>';

			return $html;
		}

		/**
		 * Displays a text field under the hidelogin tab.
		 *
		 * @param array $args settings field args.
		 * @param array $value settings field value.
		 * @since 1.0.0
		 * @version 3.0.0
		 * @return string html
		 */
		public function loginpress_hidelogin_callback( $args, $value ) {

			$html        = '';
			$size        = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$type        = isset( $args['type'] ) ? $args['type'] : 'text';
			$placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . $args['placeholder'] . '"';

			$html .= '<div class="loginpress-hidelogin-slug-wrapper">';
			// If permalink structure is defined.
			if ( get_option( 'permalink_structure' ) ) {

				$html .= '<p class="hide-login_site">' . trailingslashit( home_url() ) . '</p>' . sprintf( '<input type="%1$s" class="%2$s-text hidelogin-slug-input" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s/>', $type, $size, $args['section'], $args['id'], $value, $placeholder );
				$html .= '<input type="hidden" class="hidelogin_slug_hidden" value="' . trailingslashit( home_url() ) . $value . '">';

				// If permalink structure is not defined.
			} else {

				$html .= '<p class="hide-login_site">' . trailingslashit( home_url() ) . '?</p>' . sprintf( '<input type="%1$s" class="%2$s-text hidelogin-slug-input" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s/>', $type, $size, $args['section'], $args['id'], $value, $placeholder );
				$html .= '<input type="hidden" class="hidelogin_slug_hidden" value="' . trailingslashit( home_url() ) . '?' . $value . '">';

			}
			$html .= '<div class="copy-email-icon-wrapper hidelogin-copy-code"><span class="hidelogin-tooltip" id="hidelogin-tooltip">Copy to clipboard</span><svg class="hidelogin-copy-svg" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_27_294)"><path d="M1.62913 0H13.79C14.6567 0 15.3617 0.7051 15.3617 1.57176V5.26077H13.9269V1.57176C13.9269 1.49624 13.8655 1.43478 13.79 1.43478H1.62913C1.55361 1.43478 1.49216 1.49624 1.49216 1.57176V13.7326C1.49216 13.8082 1.55361 13.8696 1.62913 13.8696H5.20313V15.3044H1.62913C0.762474 15.3044 0.057373 14.5993 0.057373 13.7326V1.57176C0.0574209 0.7051 0.762474 0 1.62913 0Z" fill="#869AC1"></path><path d="M8.20978 6.69557H20.3706C21.2373 6.69557 21.9424 7.40067 21.9424 8.26737V20.4282C21.9423 21.2949 21.2373 22 20.3706 22H8.20973C7.34303 22 6.63793 21.2949 6.63793 20.4283V8.26737C6.63788 7.40067 7.34308 6.69557 8.20978 6.69557ZM8.20969 20.5652H20.3706C20.4461 20.5652 20.5076 20.5038 20.5076 20.4283V8.26737C20.5076 8.19181 20.4461 8.13035 20.3706 8.13035H8.20973C8.13417 8.13035 8.07271 8.19181 8.07271 8.26737V20.4283C8.07271 20.5038 8.13417 20.5652 8.20969 20.5652Z" fill="#869AC1"></path></g><defs><clipPath id="clip0_27_294"><rect width="22" height="22" fill="white" transform="matrix(-1 0 0 1 22 0)"></rect></clipPath></defs></svg><span class="hidelogin_slug_copied"></span></div>';
			$html .= '</div>';
			$html .= '<div class="loginpress-hidelogin-cta-wrapper">';
			$html .= '<input type="button" class="button loginpress-hidelogin-slug" value="' . esc_html__( 'Generate Slug (Randomly)', 'loginpress-pro' ) . '" id="loginpress_create_new_hidelogin_slug" />';

			// If Slug is not defined or empty.
			if ( '' !== $this->slug  ) {
				$html .= '<input type="button" class="button button-primary" value="' . esc_html__( 'Reset Login Slug', 'loginpress-pro' ) . '" id="loginpress_reset_login_slug" />';
			}
			$html .= '</div>';

			return $html;
		}

		/**
		 * The rename_login_slug_desc description of the field 'rename_login_slug'
		 *
		 * @since 1.0.0
		 * @return html $html The login slug description on Hide login settings page.
		 */
		public function rename_login_slug_desc() {

			global $pagenow;
			$loginpress_hidelogin = get_option( 'loginpress_hidelogin' );
			$slug                 = isset( $loginpress_hidelogin['rename_login_slug'] ) ? $loginpress_hidelogin['rename_login_slug'] : '';
			$check                = isset( $loginpress_hidelogin['is_rename_send_email'] ) ? $loginpress_hidelogin['is_rename_send_email'] : 'off';
			$html                 = '';

			if ( ! is_network_admin() && 'admin.php' === $pagenow && isset( $_GET['page'] ) && 'loginpress-settings' === $_GET['page'] && isset( $_GET['settings-updated'] ) && '' === $slug ) { // @codingStandardsIgnoreLine.

				$html .= sprintf( __( 'Your default login page: %1$s Bookmark this page!', 'loginpress-pro' ), '<strong><a href="' . home_url( '/wp-login.php' ) . '" target="_blank">' . home_url('/wp-login.php') . '</a></strong>.' );

			} else if ( '' === $slug ) {

				$html .= sprintf( __( 'Your default login page: %1$s Bookmark this page!', 'loginpress-pro' ), '<strong><a href="' . home_url( '/wp-login.php' ) . '" target="_blank">'.home_url( '/wp-login.php' ).'</a></strong>' );

			} elseif ( ! is_network_admin() && 'admin.php' === $pagenow && isset( $_GET['page'] ) && 'loginpress-settings' === $_GET['page'] && isset( $_GET['settings-updated'] ) ) { // @codingStandardsIgnoreLine.

				$html .= sprintf( __( 'Here is your login page now: %1$s Bookmark this page!', 'loginpress-pro' ), '<strong><a href="' . $this->new_login_url() . '" target="_blank">' . $this->new_login_url() . '</a></strong>' );
				if ( 'on' == $check ) {
					$this->loginpress_send_notify_email();
				}
				// If permalink structure is not defined.
			} else {

				$html .= __( 'Rename your wp-login.php', 'loginpress-pro' );

			}

			return $html;
		}

		/**
		 * The rename_email_send_to_default return email.
		 *
		 * @return mixed string|array $email The email/s.
		 * @since 1.0.0
		 */
		public function rename_email_send_to_default() {
			return get_option( 'admin_email' );
		}

		/**
		 * [loginpress_send_notify_email send email]
		 *
		 * @since 1.0.0
		 * @version 3.0.0
		 */
		public function loginpress_send_notify_email() {

			$loginpress_hidelogin = get_option( 'loginpress_hidelogin' );
			$slug                 = isset( $loginpress_hidelogin['rename_login_slug'] ) ? $loginpress_hidelogin['rename_login_slug'] : '';
			$email                = isset( $loginpress_hidelogin['rename_email_send_to'] ) ? $loginpress_hidelogin['rename_email_send_to'] : '';
			$headers              = array( 'Content-Type: text/html; charset=UTF-8' );

			if ( ! empty( $slug ) && ! empty( $email ) ) {

				$home_url = home_url( '/' );
				$message  = '';
				$message .= 'Email Notification from ' . $home_url . '<br />';
				$message .= 'Your New Login Slug is ' . $home_url . $slug . '<br />';
				$message .= 'Powered by LoginPress';

				/**
				 * Use filter `loginpress_hide_login_email_notification` for return the hide login email notification.
				 *
				 * @param string $message default email notification string.
				 * @param string $slug Newly created slug.
				 * @since 1.1.5
				 */
				$email_body = apply_filters( 'loginpress_hide_login_email_notification', $message, $slug );
				// Escape JS.
				$email_body = preg_replace( '/<script\b[^>]*>(.*?)<\/script>/is', '', $email_body );

				/**
				 * Use filter `loginpress_hide_login_email_subject` for return the hide login email subject.
				 *
				 * @param string default email subject string.
				 * @since 1.1.5
				 */
				$subject = apply_filters( 'loginpress_hide_login_email_subject', 'Rename wp-login.php by LoginPress' );
				// Escape JS.
				$subject = preg_replace( '/<script\b[^>]*>(.*?)<\/script>/is', '', $subject );

				wp_mail( trim( $email ), $subject, $email_body, $headers );
			}
		}

		/**
		 * The is_rename_send_email_desc description of the field 'is_rename_send_email'
		 *
		 * @since 1.0.0
		 * @return string $html The send email description.
		 */
		public function is_rename_send_email_desc() {

			$loginpress_hidelogin = get_option( 'loginpress_hidelogin' );
			$is_send_email        = isset( $loginpress_hidelogin['is_rename_send_email'] ) ? $loginpress_hidelogin['is_rename_send_email'] : '';
			$email_send           = isset( $loginpress_hidelogin['rename_email_send_to'] ) ? $loginpress_hidelogin['rename_email_send_to'] : '';

			$html = '';
			if ( 'off' !== $is_send_email && ! empty( $email_send ) ) {

				$html .= esc_html__( 'Email will be sent to the address defined below.', 'loginpress-pro' );

			} elseif ( 'off' !== $is_send_email && empty( $email_send ) ) {

				$html .= esc_html__( 'Email will be sent to the address(s) defined below.', 'loginpress-pro' );

			} else {

				$html .= esc_html__( 'Send email after changing the wp-login.php slug?', 'loginpress-pro' );

			}

			return $html;
		}

		/**
		 * The rename_email_send_to_desc description of the field 'rename_email_send_to'
		 *
		 * @since 1.0.0
		 * @return string Email sent or Write a email description.
		 */
		public function rename_email_send_to_desc() {

			$html                 = '';
			$loginpress_hidelogin = get_option( 'loginpress_hidelogin' );
			$email_send           = isset( $loginpress_hidelogin['rename_email_send_to'] ) ? $loginpress_hidelogin['rename_email_send_to'] : '';

			if ( '' !== $email_send ) {

				$html .= esc_html__( 'Email sent.', 'loginpress-pro' );

			} else {

				$html .= esc_html__( 'Write a Email	Address where send the New generated URL', 'loginpress-pro' );

			}

			return $html;
		}

		/**
		 * The loginpress_hidelogin_loaded For multi-site URL parse
		 *
		 * @version 3.0.0
		 */
		public function loginpress_hidelogin_loaded() {

			global $pagenow;
			$request = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

			// Check is multi-site setup.
			if ( ! is_multisite() && ( ! empty( $request ) && strpos( $request, 'wp-signup' ) !== false || strpos( $request, 'wp-activate' ) ) !== false ) {

				wp_die( esc_html__( 'This feature is not enabled.', 'loginpress-pro' ) );

			}

			// Set request variable.
			if ( isset( $_SERVER['REQUEST_URI'] ) ) {
				$request = wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
			}

			// If wp-login.php or admin page is accessed.
			if ( isset( $request['path'] ) && ( strpos( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'wp-login.php' ) !== false || untrailingslashit( $request['path'] ) === site_url( 'wp-login', 'relative' ) ) && ! is_admin() ) {

				$this->wp_login_php     = true;
				$_SERVER['REQUEST_URI'] = $this->hidelogin_user_trailing( '/' . str_repeat( '-/', 10 ) );

			} elseif ( isset( $request['path'] ) && untrailingslashit( $request['path'] ) === home_url( $this->new_login_slug(), 'relative' ) || ( ! get_option( 'permalink_structure' ) && isset( $_GET[ $this->new_login_slug() ] ) && empty( $_GET[ $this->new_login_slug() ] ) ) ) { // @codingStandardsIgnoreLine.

				$pagenow = 'wp-login.php'; // @codingStandardsIgnoreLine.

			}
		}

		/**
		 * The loginpress_hidelogin_wp_loaded For Site URL parse
		 *
		 * @version 3.0.0
		 */
		public function loginpress_hidelogin_wp_loaded() {

			global $pagenow;
			global $TRP_LANGUAGE; // @codingStandardsIgnoreLine.

			// limit wp-admin access.
			if ( is_admin() && ! is_user_logged_in() && ! defined( 'DOING_AJAX' ) && 'admin-post.php' !== $pagenow ) {
				// wp_die( __( 'Disabled Admin Access', 'loginpress-pro' ), 403 );
				// global $wp_query;
				// $wp_query->set_404();
				// status_header( 404 );
				// get_template_part( 404 );
				apply_filters( 'loginpress_hidelogin_wp_admin_redirect', wp_safe_redirect( get_site_url() . '/404' ) );
				exit();
			}

			$request = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) : '';
			$slug    = isset( $this->slug ) ? '/' . $this->slug . '/' : '';

			// if URL lang_code and TranslatePress is activated.
			if ( null !== $TRP_LANGUAGE ) { // @codingStandardsIgnoreLine.
				$language_code     = explode( '_', $TRP_LANGUAGE ); // @codingStandardsIgnoreLine.
				$additional_slug[] = isset( $this->slug ) ? '/' . $language_code[0] . '/' . $this->slug . '/' : '';
			} else {
				$additional_slug[] = isset( $this->slug ) ? '/' . $this->slug : '';
			}

			if ( 'wp-login.php' === $pagenow && $request['path'] !== $this->hidelogin_user_trailing( $request['path'] ) && get_option( 'permalink_structure' ) ) {

				wp_safe_redirect( $this->hidelogin_user_trailing( $this->new_login_url() ) . ( ! empty( $_SERVER['QUERY_STRING'] ) ? '?' . wp_unslash( $_SERVER['QUERY_STRING'] ) : '' ) );
				die;

			} elseif ( $this->wp_login_php ) {
				$get_referer = wp_get_referer();
				if ( $get_referer ) {

					$referer = wp_parse_url( $get_referer );
					if ( strpos( $get_referer, 'wp-activate.php' ) !== false && ( $referer ) && ! empty( $referer['query'] ) ) {

						parse_str( $referer['query'], $referer );

						if ( ! empty( $referer['key'] ) ) {
							$result = wpmu_activate_signup( $referer['key'] );
							if ( is_wp_error( $result ) && ( $result->get_error_code() === 'already_active' || $result->get_error_code() === 'blog_taken' ) ) {
								wp_safe_redirect( $this->new_login_url() . ( ! empty( $_SERVER['QUERY_STRING'] ) ? '?' . wp_unslash( $_SERVER['QUERY_STRING'] ) : '' ) );
								die;
							}
						}
					}
				}

				$this->wp_template_loader();
				/**
				 * If the request path matches with the additional slug.
				 */
			} elseif ( ( 'index.php' === $pagenow && strpos( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), $slug ) === true ) ||
			( 'index.php' === $pagenow && strpos( $request['path'], $additional_slug[0] ) === true ) ||
			( 'wp-login.php' === $pagenow ) ) {

				global $error, $interim_login, $action, $user_login;
				@require_once ABSPATH . 'wp-login.php'; // @codingStandardsIgnoreLine.
				die;
			} elseif ( ( 'index.php' === $pagenow && true === strpos( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), $slug ) ) ||
			( 'index.php' === $pagenow && strpos( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), $additional_slug[0] ) === true ) ||
			( 'wp-login.php' === $pagenow ) ) {

				global $error, $interim_login, $action, $user_login;
				@require_once ABSPATH . 'wp-login.php'; // @codingStandardsIgnoreLine.
				die;

			}
		}



		/**
		 * Retrieves the URL for the current site where WordPress application files
		 *
		 * @param url    $url The Url.
		 * @param string $path The Path.
		 * @param string $scheme The Scheme.
		 * @param string $blog_id Blog ID.
		 *
		 * @return url
		 */
		public function site_url( $url, $path, $scheme, $blog_id ) {

			return $this->loginpress_filter_login_page( $url, $scheme );
		}

		/**
		 * Retrieves the site URL for the current network
		 *
		 * @param url    $url The Url.
		 * @param string $path The Path.
		 * @param string $scheme The Scheme.
		 *
		 * @return url
		 */
		public function network_site_url( $url, $path, $scheme ) {

			return $this->loginpress_filter_login_page( $url, $scheme );
		}

		/**
		 * Redirect to the location
		 *
		 * @param url $location The path to redirect to.
		 * @param int $status Status code to use.
		 * @version 3.0.0
		 * @return url
		 */
		public function wp_redirect( $location, $status ) {

			return $this->loginpress_filter_login_page( $location );
		}

		/**
		 * Filter the Login Page
		 *
		 * @param url    $url The Url.
		 * @param string $scheme The Scheme.
		 * @version 3.0.0
		 * @return url
		 */
		public function loginpress_filter_login_page( $url, $scheme = null ) {

			if ( strpos( $url, 'wp-login.php' ) !== false ) {

				if ( is_ssl() ) {
					$scheme = 'https';
				}

				$args = explode( '?', $url );
				if ( isset( $args[1] ) ) {
					parse_str( $args[1], $args );
					$url = add_query_arg( $args, $this->new_login_url( $scheme ) );
				} else {
					$url = $this->new_login_url( $scheme );
				}
			}
			return $url;
		}


		/**
		 * Method to enqueue admin scripts
		 *
		 * @param string $hook the current admin page.
		 * @version 3.0.0
		 */
		public function loginpress_hidelogin_admin_action_scripts( $hook ) {

			if ( 'toplevel_page_loginpress-settings' === $hook ) {

				wp_register_style( 'loginpress-admin-hidelogin', plugins_url( 'assets/css/style.css', __DIR__ ), array(), LOGINPRESS_PRO_VERSION );
				wp_enqueue_style( 'loginpress-admin-hidelogin' );
			}

			wp_enqueue_script( 'loginpress-admin-hidelogin', plugins_url( 'assets/js/required-action.js', __DIR__ ), array( 'jquery' ), LOGINPRESS_PRO_VERSION, false );
			wp_localize_script(
				'loginpress-admin-hidelogin',
				'loginpress_hidelogin_local',
				array(
					'admin_url' => admin_url( 'admin.php?page=loginpress-settings' ),
					'security'  => wp_create_nonce( 'loginpress-reset-login-nonce' ),
				)
			);
		}
	}
endif;
