<?php
/**
 * Class for Masterstudy LMS Integration
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

require_once 'lmsbase.php';
/**
 * This class defines implementation of masterstudy LMS function
 */
class MasterStudyLMS implements LMSBase {

	/**
	 * Function to fetch Masterstudy LMS quizzes
	 *
	 * @since    1.0.0
	 */
	public function mo_procto_get_lms_posts() {
		$all_quizzes = get_posts(
			array(
				'post_type'   => 'stm-quizzes',
				'numberposts' => -1,
			)
		);
		return $all_quizzes;
	}

	/**
	 * Function to check Masterstudy LMS quiz
	 *
	 * @since    1.0.0
	 */
	public function mo_procto_check_quiz() {
		global $wp;
			$url         = home_url( $wp->request );
			$var         = explode( '/', $url );
			$var_id      = end( $var );
			$course_name = $var[ count( $var ) - 2 ];
			$quiz        = get_post( $var_id );
		if ( $quiz && 'stm-quizzes' === $quiz->post_type ) {
			return true;
		}
			return 0;
	}
}
