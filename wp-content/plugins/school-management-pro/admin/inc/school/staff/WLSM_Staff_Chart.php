<?php
defined( 'ABSPATH' ) || die();

class WLSM_Staff_Chart {
	public static function fetch_monthly_admissions() {
		$current_user = WLSM_M_Role::can( 'manage_admissions' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'monthly-admissions' ) ) {
				die();
			}

			$session_start_date = new DateTime( $current_user['session']['start_date'] );
			$session_end_date   = new DateTime( $current_user['session']['end_date'] );
			$session_start_date = $session_start_date->format('Y-m-d');
			$session_end_date   = $session_end_date->format('Y-m-d');

			$monthly_admissions = $wpdb->get_results(
				$wpdb->prepare( 'SELECT DATE_FORMAT(sr.admission_date, "%b %Y") as month_year, COUNT(sr.ID) as students_count FROM ' . WLSM_STUDENT_RECORDS . ' as sr 
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
				WHERE cs.school_id = %d AND ss.ID = %d AND sr.admission_date GROUP BY DATE_FORMAT(sr.admission_date, "%Y-%m")', $school_id, $session_id )
			);

			$data = array();

			foreach ( $monthly_admissions as $admission ) {
				array_push(
					$data,
					array(
						'x' => $admission->month_year,
						'y' => absint( $admission->students_count )
					)
				);
			}

			wp_send_json( json_encode( $data ) );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json( json_encode( array() ) );
		}
	}

	public static function fetch_monthly_payments() {
		$current_user = WLSM_M_Role::can( 'manage_invoices' );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'monthly-payments' ) ) {
				die();
			}

			$session_start_date = new DateTime( $current_user['session']['start_date'] );
			$session_end_date   = new DateTime( $current_user['session']['end_date'] );
			$session_start_date = $session_start_date->format('Y-m-d');
			$session_end_date   = $session_end_date->format('Y-m-d');

			$monthly_payments = $wpdb->get_results(
				$wpdb->prepare( 'SELECT DATE_FORMAT(p.created_at, "%b %Y") as month_year, COALESCE(SUM(p.amount), 0) as sum FROM ' . WLSM_PAYMENTS . ' as p 
				JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = p.school_id 
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = p.student_record_id 
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
				WHERE p.school_id = %d AND ss.ID = %d AND CAST(p.created_at AS DATE) BETWEEN %s AND %s GROUP BY DATE_FORMAT(p.created_at, "%Y-%m")', $school_id, $session_id, $session_start_date, $session_end_date )
			);

			$data = array();

			foreach ( $monthly_payments as $payment ) {
				array_push(
					$data,
					array(
						'x' => $payment->month_year,
						'y' => WLSM_Config::sanitize_money( $payment->sum )
					)
				);
			}

			wp_send_json( json_encode( $data ) );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json( json_encode( array() ) );
		}
	}

	public static function fetch_monthly_income_expense() {
		$current_user = WLSM_M_Role::can( array( 'manage_income', 'manage_expense' ) );

		if ( ! $current_user ) {
			die();
		}

		$school_id  = $current_user['school']['id'];
		$session_id = $current_user['session']['ID'];

		try {
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'monthly-income-expense' ) ) {
				die();
			}

			$session_start_date = new DateTime( $current_user['session']['start_date'] );
			$session_end_date   = new DateTime( $current_user['session']['end_date'] );
			$session_start_date = $session_start_date->format('Y-m-d');
			$session_end_date   = $session_end_date->format('Y-m-d');

			$monthly_income = $wpdb->get_results(
				$wpdb->prepare( 'SELECT DATE_FORMAT(im.income_date, "%b %Y") as month_year, COALESCE(SUM(im.amount), 0) as sum FROM ' . WLSM_INCOME . ' as im WHERE im.school_id = %d AND im.income_date BETWEEN %s AND %s GROUP BY DATE_FORMAT(im.income_date, "%Y-%m")', $school_id, $session_start_date, $session_end_date )
			);

			$monthly_expense = $wpdb->get_results(
				$wpdb->prepare( 'SELECT DATE_FORMAT(ep.expense_date, "%b %Y") as month_year, COALESCE(SUM(ep.amount), 0) as sum FROM ' . WLSM_EXPENSES . ' as ep WHERE ep.school_id = %d AND ep.expense_date BETWEEN %s AND %s GROUP BY DATE_FORMAT(ep.expense_date, "%Y-%m")', $school_id, $session_start_date, $session_end_date )
			);

			$income_months = array_map( function( $income ) {
				return $income->month_year;
			}, $monthly_income );

			$expense_months = array_map( function( $expense ) {
				return $expense->month_year;
			}, $monthly_expense );

			$months = array_unique( array_merge( $income_months, $expense_months ) );

			$sorted_months = array();
			foreach ( $months as $month ) {
				$date = DateTime::createFromFormat( 'j M Y', '01 ' . $month );
				$sorted_months[ $date->format('Ym') ] = $month;
			}

			ksort( $sorted_months, 1 );

			$income = array();
			foreach ( $monthly_income as $value ) {
				$income[ $value->month_year ] = WLSM_Config::sanitize_money( $value->sum );
			}

			$expense = array();
			foreach ( $monthly_expense as $value ) {
				$expense[ $value->month_year ] = WLSM_Config::sanitize_money( $value->sum );
			}

			$income_array  = array();
			$expense_array = array();
			foreach ( $sorted_months as $month ) {
				array_push(
					$income_array,
					array(
						'x' => $month,
						'y' => isset( $income[ $month ] ) ? WLSM_Config::sanitize_money( $income[ $month ] ) : WLSM_Config::sanitize_money( 0 )
					)
				);

				array_push(
					$expense_array,
					array(
						'x' => $month,
						'y' => isset( $expense[ $month ] ) ? WLSM_Config::sanitize_money( $expense[ $month ] ) : WLSM_Config::sanitize_money( 0 )
					)
				);
			}

			$data = array(
				'income'  => $income_array,
				'expense' => $expense_array
			);

			wp_send_json( json_encode( $data ) );

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}

			wp_send_json(
				json_encode(
					array(
						'income'  => array(),
						'expense' => array()
					)
				)
			);
		}
	}
}
