<?php
/**
 * Class for implement LMS basic function
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

interface LMSBase {

	/**
	 * Function to fetch LMS quizzes
	 *
	 * @since    1.0.0
	 */
	public function mo_procto_get_lms_posts();

	/**
	 * Function to check LMS quiz
	 *
	 * @since    1.0.0
	 */
	public function mo_procto_check_quiz();

}
