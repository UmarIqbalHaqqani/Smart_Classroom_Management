<?php
/**
 * Class for constants
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
if ( ! class_exists( 'Proctoring_For_Lms_Constants' ) ) {

	/**
	 * This class defines constants
	 */
	class Proctoring_For_Lms_Constants {
		const DEFAULT_CUSTOMER_KEY = '16555';
		const DEFAULT_API_KEY      = 'fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq';
		const HOST_NAME            = 'https://login.xecurify.com';

	}
}
new Proctoring_For_Lms_Constants();
