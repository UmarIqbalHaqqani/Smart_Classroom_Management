<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://miniorange.com
 * @since      1.0.0
 *
 * @package    exam-and-quiz-online-proctoring-with-lms-integration
 * @subpackage exam-and-quiz-online-proctoring-with-lms-integration/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Proctoring_For_Lms' ) ) {

	/**
	 * The core plugin class.
	 *
	 * This is used to define internationalization, admin-specific hooks, and
	 * public-facing site hooks.
	 *
	 * Also maintains the unique identifier of this plugin as well as the current
	 * version of the plugin.
	 *
	 * @since      1.0.0
	 * @package    exam-and-quiz-online-proctoring-with-lms-integration
	 * @subpackage exam-and-quiz-online-proctoring-with-lms-integration/includes
	 * @author     miniOrange <info@xecurify.com>
	 */
	class Proctoring_For_Lms {

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      Proctoring_For_Lms_Loader    $loader    Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
		 */
		protected $plugin_name;

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $version    The current version of the plugin.
		 */
		protected $version;

		/**
		 * Define the core functionality of the plugin.
		 *
		 * Set the plugin name and the plugin version that can be used throughout the plugin.
		 * Load the dependencies, define the locale, and set the hooks for the admin area and
		 * the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {
			if ( defined( 'PROCTORING_FOR_LMS_VERSION' ) ) {
				$this->version = PROCTORING_FOR_LMS_VERSION;
			} else {
				$this->version = '2.1.0';
			}
			$this->plugin_name = 'exam-and-quiz-online-proctoring-with-lms-integration';

			$this->load_dependencies();
			$this->set_locale();
			$this->define_admin_hooks();
			$this->define_public_hooks();
			$this->manage_plugin_migration();
		}

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 *
		 * - Proctoring_For_Lms_Loader. Orchestrates the hooks of the plugin.
		 * - Proctoring_For_Lms_I18n. Defines internationalization functionality.
		 * - Proctoring_For_Lms_Admin. Defines all hooks for the admin area.
		 * - Proctoring_For_Lms_Public. Defines all hooks for the public side of the site.
		 *
		 * Create an instance of the loader which will be used to register the hooks
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			/**
			 * The class responsible for orchestrating the actions and filters of the
			 * core plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes' . DIRECTORY_SEPARATOR . 'class-proctoring-for-lms-loader.php';

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes' . DIRECTORY_SEPARATOR . 'class-proctoring-for-lms-i18n.php';

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin' . DIRECTORY_SEPARATOR . 'class-proctoring-for-lms-admin.php';

			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public' . DIRECTORY_SEPARATOR . 'class-proctoring-for-lms-public.php';

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes' . DIRECTORY_SEPARATOR . 'class-proctoring-for-lms-curl.php';

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes' . DIRECTORY_SEPARATOR . 'class-proctoring-for-lms-constants.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lms' . DIRECTORY_SEPARATOR . 'class-lmsfactory.php';

			$this->loader = new Proctoring_For_Lms_Loader();

		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the Proctoring_For_Lms_I18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function set_locale() {

			$plugin_i18n = new Proctoring_For_Lms_I18n();

			$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

		}

		/**
		 * Register all of the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_admin_hooks() {

			$plugin_admin = new Proctoring_For_Lms_Admin( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'admin_menu', $plugin_admin, 'mo_procto_widget_menu' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

			$this->loader->add_action( 'wp_ajax_mo_procto_support', $plugin_admin, 'mo_procto_support' );
			$this->loader->add_action( 'wp_ajax_mo_procto_view_report', $plugin_admin, 'mo_procto_view_report' );
			$this->loader->add_action( 'admin_footer', $plugin_admin, 'mo_procto_feedback_request' );
			$this->loader->add_action( 'admin_init', $plugin_admin, 'mo_procto_feedback_actions' );

			$this->loader->add_action( 'procto_show_message', $plugin_admin, 'mo_procto_show_message', 1, 2 );

		}

		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_public_hooks() {

			$plugin_public = new Proctoring_For_Lms_Public( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
			$this->loader->add_action( 'wp_ajax_mo_procto_action', $plugin_public, 'mo_procto_action' );
			$this->loader->add_filter( 'authenticate', $plugin_public, 'mo_procto_check_session_limit', 30, 3 );
		}

		/**
		 * Update current version of the plugin in the Database and migrating options
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function manage_plugin_migration() {

			$current_version = get_option( 'mo_procto_current_plugin_version' );
			if ( empty( $current_version ) || version_compare( $current_version, $this->version ) < 0 ) {
				$option_mapping = array(
					'mo_procto_select_lms_option'   => 'mo_procto_select_lms',
					'mo_restrict_tab_switch'        => 'mo_procto_restrict_tab_switch',
					'mo_disable_mouse_right_button' => 'mo_procto_disable_mouse_right_click',
					'mo_restrict_inspect_browser'   => 'mo_procto_restrict_inspect_browser',
				);

				foreach ( $option_mapping as $old_option => $new_option ) {
					if ( ! empty( get_site_option( $old_option ) ) ) {
						$old_value = get_site_option( $old_option );
						update_site_option( $new_option, $old_value );
						delete_site_option( $old_option );
					}
				}
			}
			update_option( 'mo_procto_current_plugin_version', $this->version );
		}

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 *
		 * @since    1.0.0
		 */
		public function run() {
			$this->loader->run();
		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @since     1.0.0
		 * @return    string    The name of the plugin.
		 */
		public function get_plugin_name() {
			return $this->plugin_name;
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @since     1.0.0
		 * @return    Proctoring_For_Lms_Loader    Orchestrates the hooks of the plugin.
		 */
		public function get_loader() {
			return $this->loader;
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @since     1.0.0
		 * @return    string    The version number of the plugin.
		 */
		public function get_version() {
			return $this->version;
		}

	}
}
