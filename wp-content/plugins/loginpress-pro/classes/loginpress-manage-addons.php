<?php
/**
 * LoginPress_Manage_Addons
 *
 * @package LoginPress Pro
 */

if ( ! class_exists( 'LoginPress_Manage_Addons' ) ) :

	/**
	 * LoginPress_Manage_Addons
	 */
	class LoginPress_Manage_Addons {

		/**
		 * The array of addons
		 *
		 * @var array
		 */
		private $addons_array;

		/**
		 * Class constructor.
		 */
		public function __construct() {
			$this->addons_array = get_option( 'loginpress_pro_addons' );
			$this->enable_addons();
			$this->addon_activation();
		}

		/**
		 * Enable active addons.
		 */
		private function enable_addons() {
			if ( ! $this->addons_array ) {
				return;
			}
			$rooth_path = LOGINPRESS_PRO_ROOT_PATH;

			foreach ( $this->addons_array as $addon ) {
				$addon_slug = $addon['slug'];

				if ( ! $addon['is_free'] && $addon['is_active'] ) {
					include_once "$rooth_path/addons/$addon_slug/$addon_slug.php";
				}
			}
		}

		/**
		 * Addons activation hook.
		 */
		public function addon_activation() {
			add_action( 'loginpress_pro_addon_activation', array( $this, 'loginpress_pro_addon_activation_cb' ) );
		}

		/**
		 * The addon activation callback
		 *
		 * @param string $plugin_slug The plugin slug.
		 *
		 * @return void
		 */
		public function loginpress_pro_addon_activation_cb( $plugin_slug ) {
			switch ( $plugin_slug ) {
				case 'limit-login-attempts':
					$this->create_limit_login_attempts_table();
					break;

				case 'social-login':
					$this->create_social_login_table();
					break;

				default:
					break;
			}
		}

		/**
		 * Create table for limit login attempts.
		 */
		private function create_limit_login_attempts_table() {
			if ( function_exists( 'is_multisite' ) && is_multisite() ) {
				global $wpdb;

				$current_blog = $wpdb->blogid;
				$blog_ids = $wpdb->get_col( $wpdb->prepare( 'SELECT blog_id FROM %s', $wpdb->blogs ) ); // @codingStandardsIgnoreLine.

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					$this->limit_create_table_query();
				}

				switch_to_blog( $current_blog );

				return;
			} else {
				$this->limit_create_table_query();
			}
		}

		/**
		 * Limit login attempts addon table query.
		 */
		private function limit_create_table_query() {
			global $wpdb;

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

		/**
		 * Create table for social login.
		 */
		private function create_social_login_table() {
			if ( function_exists( 'is_multisite' ) && is_multisite() ) {
				global $wpdb;

				$current_blog = $wpdb->blogid;
				$blog_ids = $wpdb->get_col( $wpdb->prepare( 'SELECT blog_id FROM %s', $wpdb->blogs ) ); // @codingStandardsIgnoreLine.

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					$this->social_create_table_query();
				}

				switch_to_blog( $current_blog );

				return;
			} else {
				$this->social_create_table_query(); // Normal activation.
			}
		}

		/**
		 * Social logins addon table query.
		 */
		private function social_create_table_query() {
			global $wpdb;

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

	}

endif;
