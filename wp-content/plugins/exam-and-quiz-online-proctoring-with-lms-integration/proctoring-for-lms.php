<?php
/**
 * The plugin main file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://miniorange.com
 * @since             1.0.0
 * @package           Proctoring_For_Lms
 *
 * @wordpress-plugin
 * Plugin Name:       ProctoPress - Exam and Quiz Proctoring for LMS
 * Description:       WP Proctoring tool can maintain academic integrity, prevent and deter cheating as well as help and support students to complete their virtual online examination successfully.
 * Version:           2.1.0
 * Author:            miniOrange
 * Author URI:        https://miniorange.com
 * License:           MIT/Expat
 * License URI:       https://docs.miniorange.com/mit-license
 * Text Domain:       exam-and-quiz-online-proctoring-with-lms-integration
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PROCTORING_FOR_LMS_VERSION', '2.1.0' );
define( 'MO_TEST_MODE_PROCTORING', false );

require_once ABSPATH . 'wp-admin/includes/plugin.php';


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes' . DIRECTORY_SEPARATOR . 'class-proctoring-for-lms.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_proctoring_for_lms() {

	$plugin = new Proctoring_For_Lms();
	$plugin->run();

}
run_proctoring_for_lms();
