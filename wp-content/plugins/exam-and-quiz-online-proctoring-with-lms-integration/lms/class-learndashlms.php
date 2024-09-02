<?php
/**
 * Class for Learndash LMS Integration
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
 * This class defines Learndash LMS basic function
 */
class LearnDashLMS implements LMSBase {
	/**
	 * Function to fetch Learndash LMS quizzes
	 *
	 * @since    1.0.0
	 */
	public function mo_procto_get_lms_posts() {
		$all_quizzes = get_posts(
			array(
				'post_type'   => 'sfwd-quiz',
				'numberposts' => -1,
			)
		);
		return $all_quizzes;
	}
	/**
	 * Function to check Learndash LMS quiz
	 *
	 * @since    1.0.0
	 */
	public function mo_procto_check_quiz() {
		global $wp;
			$url     = home_url( $wp->request );
			$var     = explode( '/', $url );
			$post    = get_post( get_the_id() );
			$quiz_id = $post->ID;
			$quiz    = get_post( $quiz_id );
		if ( $quiz && 'sfwd-quiz' === $quiz->post_type ) {
			return $quiz_id;
		}
			return 0;
	}
}
