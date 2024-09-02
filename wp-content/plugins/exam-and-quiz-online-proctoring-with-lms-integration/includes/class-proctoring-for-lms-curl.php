<?php
/**
 * Fired during feedback and support form submission
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
if ( ! class_exists( 'Proctoring_For_Lms_Curl' ) ) {

	/**
	 * Feedback form and support form submission
	 */
	class Proctoring_For_Lms_Curl {

		/**
		 * Function for support form submission
		 *
		 * @param string $q_email    email.
		 * @param string $q_phone    phone.
		 * @param string $query      query.
		 * @param string $subject    subject.
		 * @param string $lms        LMS name.
		 * @return string
		 */
		public function submit_contact_us( $q_email, $q_phone, $query, $subject, $lms ) {
			$current_user = wp_get_current_user();
			$url          = Proctoring_For_Lms_Constants::HOST_NAME . '/moas/rest/customer/contact-us';
			$query        = '[WordPress Proctoring For LMS: V:' . PROCTORING_FOR_LMS_VERSION . '] LMS: [' . $lms . '] ' . $query;
			$fields       = array(
				'firstName' => $current_user->user_firstname,
				'lastName'  => $current_user->user_lastname,
				'company'   => isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '',
				'email'     => $q_email,
				'ccEmail'   => 'securityteam@xecurify.com',
				'phone'     => $q_phone,
				'query'     => $query,
			);
			$field_string = wp_json_encode( $fields );
			$response     = self::call_api( $url, $field_string );
			return $response;
		}

		/**
		 * Function to make wp_remote_post call
		 *
		 * @param string $url            api url.
		 * @param string $json_string    json data.
		 * @param array  $headers        headers.
		 * @return string
		 */
		private static function call_api( $url, $json_string, $headers = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF-8',
			'Authorization' => 'Basic',
		) ) {

			$results = wp_remote_post(
				$url,
				array(
					'method'      => 'POST',
					'timeout'     => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => $headers,
					'body'        => $json_string,
					'cookies'     => array(),
				)
			);

			if ( isset( $results ) && 'Query submitted.' === $results['body'] ) {
				return true;
			} else {
				$result = json_decode( $results['body'], true );
				if ( isset( $result['status'] ) ) {
					if ( 'SUCCESS' === $result['status'] ) {
						return $results['body'];
					}
				}
			}
		}

		/**
		 * Function to Send feedback email
		 *
		 * @param string $email              email.
		 * @param string $phone              phone.
		 * @param string $message            message.
		 * @param string $feedback_option    option.
		 * @return string
		 */
		public function send_email_alert( $email, $phone, $message, $feedback_option ) {

			global $user;
			$url          = Proctoring_For_Lms_Constants::HOST_NAME . '/moas/api/notify/send';
			$customer_key = Proctoring_For_Lms_Constants::DEFAULT_CUSTOMER_KEY;
			$api_key      = Proctoring_For_Lms_Constants::DEFAULT_API_KEY;
			$from_email   = 'no-reply@xecurify.com';
			$user         = wp_get_current_user();
			$query        = '[WordPress Proctoring for LMS]: ' . $message;
			if ( 'mo_procto_skip_feedback' === $feedback_option ) {
				$subject = 'Deactivate [Feedback Skipped]: WP Proctoring for LMS';
			} elseif ( 'mo_procto_feedback' === $feedback_option ) {
				$subject = 'Feedback: WP Proctoring for LMS - ' . sanitize_email( $email );
			}
			$server_name = isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '';
			$content     = '<div >Hello, <br><br>First Name :' . sanitize_text_field( $user->user_firstname ) . '<br><br>Last  Name :' . sanitize_text_field( $user->user_lastname ) . '   <br><br>Company :<a href="' . $server_name . '" target="_blank" >' . $server_name . '</a><br><br>Phone Number :' . sanitize_text_field( $phone ) . '<br><br>Email :<a href="mailto:' . sanitize_email( $email ) . '" target="_blank">' . sanitize_text_field( $email ) . '</a><br><br>Query :' . $query . '</div>';

			$fields       = array(
				'customerKey' => $customer_key,
				'sendEmail'   => true,
				'email'       => array(
					'customerKey' => $customer_key,
					'fromEmail'   => $from_email,
					'bccEmail'    => $from_email,
					'fromName'    => 'Xecurify',
					'toEmail'     => 'securityteam@xecurify.com',
					'toName'      => 'securityteam@xecurify.com',
					'subject'     => $subject,
					'content'     => $content,
				),
			);
			$field_string = wp_json_encode( $fields );
			$auth_header  = self::create_auth_header( $customer_key, $api_key );
			$response     = self::mo_procto_remote_call( $url, $field_string, $auth_header );
			return $response;

		}

		/**
		 * Function to make remote post call
		 *
		 * @param string $url                  url.
		 * @param string $fields               fields.
		 * @param array  $http_header_array    headers.
		 * @return string
		 */
		public static function mo_procto_remote_call( $url, $fields, $http_header_array = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF-8',
			'Authorization' => 'Basic',
		) ) {

			if ( gettype( $fields ) !== 'string' ) {
				$fields = wp_json_encode( $fields );
			}
			$args     = array(
				'method'      => 'POST',
				'body'        => $fields,
				'timeout'     => '20',
				'redirection' => '5',
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => $http_header_array,
			);
			$response = self::wp_remote_post( $url, $args );
			return $response;
		}

		/**
		 * Function for wp_remote_post
		 *
		 * @param string $url     url.
		 * @param array  $args    array.
		 * @return string
		 */
		public static function wp_remote_post( $url, $args = array() ) {

			$response = wp_remote_post( $url, $args );

			if ( ! is_wp_error( $response ) ) {
				return $response['body'];
			} else {
				$message = 'Something went wrong';
				return wp_json_encode(
					array(
						'status'  => 'ERROR',
						'message' => $message,
					)
				);
			}
		}

		/**
		 * Function for adding headers
		 *
		 * @param string $customer_key  customer key.
		 * @param string $api_key       apikey.
		 * @return array
		 */
		public static function create_auth_header( $customer_key, $api_key ) {
			$current_timestamp_in_millis = round( microtime( true ) * 1000 );
			$current_timestamp_in_millis = number_format( $current_timestamp_in_millis, 0, '', '' );
			$string_to_hash              = $customer_key . $current_timestamp_in_millis . $api_key;
			$hash_value                  = hash( 'sha512', $string_to_hash );
			$headers                     = array(
				'Content-Type'  => 'application/json',
				'Customer-Key'  => $customer_key,
				'Timestamp'     => $current_timestamp_in_millis,
				'Authorization' => $hash_value,
			);

			return $headers;
		}
	}
}
