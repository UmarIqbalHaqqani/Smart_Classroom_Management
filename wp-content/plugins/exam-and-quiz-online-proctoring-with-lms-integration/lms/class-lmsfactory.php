<?php
/**
 * Class for get selected LMS Class
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

require_once 'class-learndashlms.php';
require_once 'class-masterstudylms.php';
/**
 * This class defines LMS factory initialization
 */
class LMSFactory {
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $current_lms    The current LMS selected by the user.
	 */

	protected $current_lms;

	/**
	 * This function return the intialize the class based on LMS selected by the user while configuring the plugin
	 *
	 * @return object
	 * @since    1.0.0
	 * @param string $lms_type        selected LMS.
	 */
	public function selectedlms( $lms_type ) {
		if ( strcasecmp( $lms_type, 'learn_dash' ) === 0 ) {
			$this->current_lms = new LearnDashLMS();
		} elseif ( strcasecmp( $lms_type, 'master_study' ) === 0 ) {
			$this->current_lms = new MasterStudyLMS();
		}
		return $this->current_lms;
	}
}
