<?php

/**
 * Addon Name: LoginPress - Limit Login Attempts
 * Description: LoginPress - Limit Login Attempts is the best for <code>wp-login</code> Login Attemps plugin by <a href="https://wpbrigade.com/">WPBrigade</a> which allows you to restrict user attempts.
 *
 * @package LoginPress
 * @category Core
 * @author WPBrigade
 * @since 3.0.0
 */

if ( ! class_exists( 'LoginPress_Limit_Login_Attempts' ) ) :

	/**
	 * LoginPress_Limit_Login_Attempts
	 */
	class LoginPress_Limit_Login_Attempts {


		/**
		 * Class constructor
		 */
		public function __construct() {

			$this->hooks();
			$this->define_constants();
		}

		/**
		 * Hook into actions and filters
		 *
		 * @since  3.0.0
		 */
		public function hooks() {

			add_action( 'plugins_loaded', array( $this, 'loginpress_limit_login_instance' ), 25 );
			add_action( 'wpmu_new_blog', array( $this, 'loginpress_limit_login_activation' ) );
		}

		/**
		 * Define LoginPress Limit Login Attempts Constants
		 *
		 * @since 3.0.0
		 */
		private function define_constants() {

			LoginPress_Pro_Init::define( 'LOGINPRESS_LIMIT_LOGIN_ROOT_PATH', dirname( __FILE__ ) );
			LoginPress_Pro_Init::define( 'LOGINPRESS_LIMIT_LOGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
			LoginPress_Pro_Init::define( 'LOGINPRESS_LIMIT_LOGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
			LoginPress_Pro_Init::define( 'LOGINPRESS_LIMIT_LOGIN_ROOT_FILE', __FILE__ );
		}

		/**
		 * Run LoginPress_limit_login_loader()
		 */
		public function loginpress_limit_login_instance() {

			if ( LoginPress_Pro::addon_wrapper( 'limit-login-attempts' ) ) {
				$this->loginpress_limit_login_loader();
			}
		}

		/**
		 * Returns the main instance of WP to prevent the need to use globals.
		 *
		 * @since  3.0.0
		 * @return object LoginPress_Limit_Login_Attempts_Main
		 */
		public function loginpress_limit_login_loader() {

			include_once LOGINPRESS_LIMIT_LOGIN_ROOT_PATH . '/classes/class-loginpress-limit-login-attempts.php';
			return LoginPress_Limit_Login_Attempts_Main::instance();
		}

		/**
		 * Run some custom tasks on plugin activation
		 *
		 * @param boolean $network_wide network_wide check.
		 * @since 3.0.0
		 */
		public function loginpress_limit_login_activation( $network_wide ) {

			if ( function_exists( 'is_multisite' ) && is_multisite() && $network_wide ) {

				global $wpdb;
				// Get this so we can switch back to it later.
				$current_blog = $wpdb->blogid;
				// Get all blogs in the network and activate plugin on each one.
				$blog_ids = $wpdb->get_col( $wpdb->prepare( 'SELECT blog_id FROM %s', $wpdb->blogs ) ); // @codingStandardsIgnoreLine.

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					$this->loginpress_limit_create_table();
				}
				switch_to_blog( $current_blog );
				return;
			} else {
				$this->loginpress_limit_create_table(); // normal activation.
			}

		}

		/**
		 * Create Db table on plugin activation.
		 *
		 * @since 3.0.0
		 */
		public function loginpress_limit_create_table() {

			global $wpdb;
			// create user details table.
			$table_name = "{$wpdb->prefix}loginpress_limit_login_details";

			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
				id int(11) NOT NULL AUTO_INCREMENT,
				ip varchar(255) NOT NULL,
				username varchar(255) NOT NULL,
				password varchar(255) NOT NULL,
				datentime varchar(255) NOT NULL,
				gateway varchar(255) NOT NULL,
				whitelist int(11) NOT NULL,
				blacklist int(11) NOT NULL,
				UNIQUE KEY id (id)
			) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );

			// Set default settings.
			if ( ! get_option( 'loginpress_limit_login_attempts' ) ) {

				update_option(
					'loginpress_limit_login_attempts',
					array(
						'attempts_allowed' => 4,
						'minutes_lockout'  => 20,
					)
				);
			}
		}
	}

endif;

new LoginPress_Limit_Login_Attempts();
