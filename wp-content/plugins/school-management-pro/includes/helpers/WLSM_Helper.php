<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/WLSM_LM.php';

class WLSM_Helper {
	public static function currency_symbols() {
		return array(
			'AED' => '&#1583;.&#1573;',
			'AFN' => '&#65;&#102;',
			'ALL' => '&#76;&#101;&#107;',
			'ANG' => '&#402;',
			'AOA' => '&#75;&#122;',
			'ARS' => '&#36;',
			'AUD' => '&#36;',
			'AWG' => '&#402;',
			'AZN' => '&#1084;&#1072;&#1085;',
			'BAM' => '&#75;&#77;',
			'BBD' => '&#36;',
			'BDT' => '&#2547;',
			'BGN' => '&#1083;&#1074;',
			'BHD' => '.&#1583;.&#1576;',
			'BIF' => '&#70;&#66;&#117;',
			'BMD' => '&#36;',
			'BND' => '&#36;',
			'BOB' => '&#36;&#98;',
			'BRL' => '&#82;&#36;',
			'BSD' => '&#36;',
			'BTN' => '&#78;&#117;&#46;',
			'BWP' => '&#80;',
			'BYR' => '&#112;&#46;',
			'BZD' => '&#66;&#90;&#36;',
			'CAD' => '&#36;',
			'CDF' => '&#70;&#67;',
			'CHF' => '&#67;&#72;&#70;',
			'CLP' => '&#36;',
			'CNY' => '&#165;',
			'COP' => '&#36;',
			'CRC' => '&#8353;',
			'CUP' => '&#8396;',
			'CVE' => '&#36;',
			'CZK' => '&#75;&#269;',
			'DJF' => '&#70;&#100;&#106;',
			'DKK' => '&#107;&#114;',
			'DOP' => '&#82;&#68;&#36;',
			'DZD' => '&#1583;&#1580;',
			'EGP' => '&#163;',
			'ETB' => '&#66;&#114;',
			'EUR' => '&#8364;',
			'FJD' => '&#36;',
			'FKP' => '&#163;',
			'GBP' => '&#163;',
			'GEL' => '&#4314;',
			'GHS' => '&#162;',
			'GIP' => '&#163;',
			'GMD' => '&#68;',
			'GNF' => '&#70;&#71;',
			'GTQ' => '&#81;',
			'GYD' => '&#36;',
			'HKD' => '&#36;',
			'HNL' => '&#76;',
			'HRK' => '&#107;&#110;',
			'HTG' => '&#71;',
			'HUF' => '&#70;&#116;',
			'IDR' => '&#82;&#112;',
			'ILS' => '&#8362;',
			'INR' => '&#8377;',
			'IQD' => '&#1593;.&#1583;',
			'IRR' => '&#65020;',
			'ISK' => '&#107;&#114;',
			'JEP' => '&#163;',
			'JMD' => '&#74;&#36;',
			'JOD' => '&#74;&#68;',
			'JPY' => '&#165;',
			'KES' => '&#75;&#83;&#104;',
			'KGS' => '&#1083;&#1074;',
			'KHR' => '&#6107;',
			'KMF' => '&#67;&#70;',
			'KPW' => '&#8361;',
			'KRW' => '&#8361;',
			'KWD' => '&#1583;.&#1603;',
			'KYD' => '&#36;',
			'KZT' => '&#1083;&#1074;',
			'LAK' => '&#8365;',
			'LBP' => '&#163;',
			'LKR' => '&#8360;',
			'LRD' => '&#36;',
			'LSL' => '&#76;',
			'LTL' => '&#76;&#116;',
			'LVL' => '&#76;&#115;',
			'LYD' => '&#1604;.&#1583;',
			'MAD' => '&#1583;.&#1605;.',
			'MDL' => '&#76;',
			'MGA' => '&#65;&#114;',
			'MKD' => '&#1076;&#1077;&#1085;',
			'MMK' => '&#75;',
			'MNT' => '&#8366;',
			'MOP' => '&#77;&#79;&#80;&#36;',
			'MRO' => '&#85;&#77;',
			'MUR' => '&#8360;',
			'MVR' => '.&#1923;',
			'MWK' => '&#77;&#75;',
			'MXN' => '&#36;',
			'MYR' => '&#82;&#77;',
			'MZN' => '&#77;&#84;',
			'NAD' => '&#36;',
			'NGN' => '&#8358;',
			'NIO' => '&#67;&#36;',
			'NOK' => '&#107;&#114;',
			'NPR' => '&#8360;',
			'NZD' => '&#36;',
			'OMR' => '&#65020;',
			'PAB' => '&#66;&#47;&#46;',
			'PEN' => '&#83;&#47;&#46;',
			'PGK' => '&#75;',
			'PHP' => '&#8369;',
			'PKR' => '&#8360;',
			'PLN' => '&#122;&#322;',
			'PYG' => '&#71;&#115;',
			'QAR' => '&#65020;',
			'RON' => '&#108;&#101;&#105;',
			'RSD' => '&#1044;&#1080;&#1085;&#46;',
			'RUB' => '&#1088;&#1091;&#1073;',
			'RWF' => '&#x52;&#x57;&#x46;',
			'SAR' => '&#65020;',
			'SBD' => '&#36;',
			'SCR' => '&#8360;',
			'SDG' => '&#163;',
			'SEK' => '&#107;&#114;',
			'SGD' => '&#36;',
			'SHP' => '&#163;',
			'SLL' => '&#76;&#101;',
			'SOS' => '&#83;',
			'SRD' => '&#36;',
			'STD' => '&#68;&#98;',
			'SVC' => '&#36;',
			'SYP' => '&#163;',
			'SZL' => '&#76;',
			'THB' => '&#3647;',
			'TJS' => '&#84;&#74;&#83;',
			'TMT' => '&#109;',
			'TND' => '&#1583;.&#1578;',
			'TOP' => '&#84;&#36;',
			'TRY' => '&#8356;',
			'TTD' => '&#36;',
			'TWD' => '&#78;&#84;&#36;',
			'TZS' => '',
			'UAH' => '&#8372;',
			'UGX' => '&#85;&#83;&#104;',
			'USD' => '&#36;',
			'UYU' => '&#36;&#85;',
			'UZS' => '&#1083;&#1074;',
			'VEF' => '&#66;&#115;',
			'VND' => '&#8363;',
			'VUV' => '&#86;&#84;',
			'WST' => '&#87;&#83;&#36;',
			'XAF' => '&#70;&#67;&#70;&#65;',
			'XCD' => '&#36;',
			'XDR' => '',
			'XOF' => '',
			'XPF' => '&#70;',
			'YER' => '&#65020;',
			'ZAR' => '&#82;',
			'ZMK' => '&#90;&#75;',
			'ZWL' => '&#90;&#36;'
		);
	}

	public static function hostel_type_list() {
		return array(
			'girls' => esc_html__( 'Girls', 'school-management' ),
			'boys'  => esc_html__( 'Boys', 'school-management' ),
			'co-ed' => esc_html__( 'Co-Ed', 'school-management' ),
		);
	}


	public static function date_formats() {
		return array(
			'd-m-Y' => 'dd-mm-yyyy',
			'd/m/Y' => 'dd/mm/yyyy',
			'Y-m-d' => 'yyyy-mm-dd',
			'Y/m/d' => 'yyyy/mm/dd',
			'm-d-Y' => 'mm-dd-yyyy',
			'm/d/Y' => 'mm/dd/yyyy',
		);
	}

	public static function gender_list() {
		return array(
			'male'   => esc_html__( 'Male', 'school-management' ),
			'female' => esc_html__( 'Female', 'school-management' ),
			'other'  => esc_html__( 'Other', 'school-management' ),
		);
	}

	public static function survey_list() {
		return array(
			'google'    => esc_html__( 'Google', 'school-management' ),
			'facebook'  => esc_html__( 'Facebook', 'school-management' ),
			'instagram' => esc_html__( 'Instagram', 'school-management' ),
			'friends'   => esc_html__( 'Friends & Family', 'school-management' ),
			'banner'    => esc_html__( 'Banner', 'school-management' ),
			'flyer'     => esc_html__( 'Flyer', 'school-management' ),
			'other'     => esc_html__( 'Other', 'school-management' ),
		);
	}

	public static function student_type($school_id) {
		global $wpdb;

    	$student_types = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . WLSM_STUDENT_TYPE . ' WHERE school_id = %d', $school_id));

   		return $student_types;
	}

	public static function medium_list() {
		global $wpdb;
		$mediums = $wpdb->get_results('SELECT * FROM '.WLSM_MEDIUM.'');

		$medium_list = array();
		foreach ($mediums as $medium) {
			$medium_list[$medium->ID] = ucfirst($medium->label);
		}
		return $medium_list;
	}

	public static function blood_group_list() {
		return array(
			'O+'  => esc_html__( 'O+', 'school-management' ),
			'A+'  => esc_html__( 'A+', 'school-management' ),
			'B+'  => esc_html__( 'B+', 'school-management' ),
			'AB+' => esc_html__( 'AB+', 'school-management' ),
			'O-'  => esc_html__( 'O-', 'school-management' ),
			'A-'  => esc_html__( 'A-', 'school-management' ),
			'B-'  => esc_html__( 'B-', 'school-management' ),
			'AB-' => esc_html__( 'AB-', 'school-management' ),
		);
	}

	public static function search_field_list() {
		return array(
			'admission_number'  => esc_html__( 'Admission Number', 'school-management' ),
			'name'              => esc_html__( 'Name', 'school-management' ),
			'phone'             => esc_html__( 'Phone', 'school-management' ),
			'email'             => esc_html__( 'Email', 'school-management' ),
			'address'           => esc_html__( 'Address', 'school-management' ),
			'city'              => esc_html__( 'City', 'school-management' ),
			'state'             => esc_html__( 'State', 'school-management' ),
			'country'           => esc_html__( 'Country', 'school-management' ),
			'father_name'       => esc_html__( 'Father\'s Name', 'school-management' ),
			'father_phone'      => esc_html__( 'Father\'s Phone', 'school-management' ),
			'login_email'       => esc_html__( 'Login Email', 'school-management' ),
			'username'          => esc_html__( 'Login Username', 'school-management' ),
			'admission_date'    => esc_html__( 'Admission Date', 'school-management' ),
			'enrollment_number' => esc_html__( 'Enrollment Number', 'school-management' ),
		);
	}

	public static function invoice_search_field_list() {
		return array(
			'invoice_number'    => esc_html__( 'Invoice Number', 'school-management' ),
			'invoice_title'     => esc_html__( 'Invoice Title', 'school-management' ),
			'date_issued'       => esc_html__( 'Date Issued', 'school-management' ),
			'due_date'          => esc_html__( 'Due Date', 'school-management' ),
			'status'            => esc_html__( 'Status (Paid, Unpaid, Partially Paid)', 'school-management' ),
			'name'              => esc_html__( 'Student Name', 'school-management' ),
			'admission_number'  => esc_html__( 'Admission Number', 'school-management' ),
			'enrollment_number' => esc_html__( 'Enrollment Number', 'school-management' ),
			'phone'             => esc_html__( 'Phone', 'school-management' ),
			'email'             => esc_html__( 'Email', 'school-management' ),
			'father_name'       => esc_html__( 'Father\'s Name', 'school-management' ),
			'father_phone'      => esc_html__( 'Father\'s Phone', 'school-management' ),
		);
	}

	public static function attendance_status() {
		return array(
			''  => esc_html__( 'Undefined', 'school-management' ),
			'p' => esc_html__( 'Present', 'school-management' ),
			'a' => esc_html__( 'Absent', 'school-management' ),
			'h' => esc_html__( 'Holiday', 'school-management' ),
			'l' => esc_html__( 'Late', 'school-management' ),
		);
	}

	public static function subject_type_list() {
		return array(
			'theory'     => esc_html__( 'Theory', 'school-management' ),
			'practical'  => esc_html__( 'Practical', 'school-management' ),
			'subjective' => esc_html__( 'Subjective', 'school-management' ),
			'objective'  => esc_html__( 'Objective', 'school-management' ),
		);
	}

	public static function get_subject_type_text( $key ) {
		if ( isset( self::subject_type_list()[ $key ] ) ) {
			return self::subject_type_list()[ $key ];
		}

		return '';
	}

	// if subject $key is numeric, then it gets subject type text from subject type list if not then it returns subject type key.
	public static function get_subject_type( $key ) {
		if ( is_numeric( $key ) ) {
			$subject_types = self::subject_type_list();
			if ( isset( $subject_types[ $key ] ) ) {
				return $subject_types[ $key ];
			}
		}
		return $key;
	}

	public static function meeting_host() {
		return 'me';
	}

	public static function generate_random_code( $length = 10 ) {
		$default_code = bin2hex( random_bytes( $length / 2 ) );
		return $default_code;
	}

	public static function get_meeting_type( $key, $empty = '-' ) {
		$meeting_types = self::meeting_types();
		if ( isset( $meeting_types[ $key ] ) ) {
			return $meeting_types[ $key ];
		}
		return '-';
	}

	public static function meeting_types() {
		return array(
			8 => esc_html__( 'Recurring Class with fixed time', 'school-management' ),
			2 => esc_html__( 'Scheduled Class', 'school-management' ),
		);
	}

	public static function meeting_recurrence_types() {
		return array(
			'1' => esc_html__( 'Daily', 'school-management' ),
			'2' => esc_html__( 'Weekly', 'school-management' ),
			'3' => esc_html__( 'Monthly', 'school-management' ),
		);
	}

	public static function meeting_approval_types() {
		return array(
			'0' => esc_html__( 'Automatically approve.', 'school-management' ),
			'1' => esc_html__( 'Manually approve.', 'school-management' ),
			'2' => esc_html__( 'No registration required.', 'school-management' ),
		);
	}

	public static function meeting_registration_types() {
		return array(
			'1' => esc_html__( 'Attendees register once and can attend any of the occurrences.', 'school-management' ),
			'2' => esc_html__( 'Attendees need to register for each occurrence to attend.', 'school-management' ),
			'3' => esc_html__( 'Attendees register once and can choose one or more occurrences to attend.', 'school-management' ),
		);
	}

	public static function meeting_weekly_days() {
		return array(
			'1' => esc_html__( 'Sunday', 'school-management' ),
			'2' => esc_html__( 'Monday', 'school-management' ),
			'3' => esc_html__( 'Tuesday', 'school-management' ),
			'4' => esc_html__( 'Wednesday', 'school-management' ),
			'5' => esc_html__( 'Thursday', 'school-management' ),
			'6' => esc_html__( 'Friday', 'school-management' ),
			'7' => esc_html__( 'Saturday', 'school-management' ),
		);
	}

	public static function days_list( $day = '' ) {
		$base_date = 4;

		if ( is_numeric( $day ) ) {
			$base_date += $day;

			$format_base_date = str_pad( $base_date, 2, '0', STR_PAD_LEFT );

			return date_i18n( 'l', strtotime( $format_base_date . '-01-1970' ) );
		}

		$weekdays = array();

		for ( $i = 1; $i <= 7; $i++ ) {
			$base_date++;

			$format_base_date = str_pad( $base_date, 2, '0', STR_PAD_LEFT );

			$weekdays[ $i ] = date_i18n( 'l', strtotime( $format_base_date . '-01-1970' ) );
		}

		return $weekdays;
	}

	public static function fee_period_list() {
		return array(
			'one-time'     => esc_html__( 'One Time', 'school-management' ),
			'monthly'      => esc_html__( 'Monthly', 'school-management' ),
			'quarterly'    => esc_html__( 'Quarterly (3 Months)', 'school-management' ),
			'quadrimester' => esc_html__( 'Quadrimester (4 Months)', 'school-management' ),
			'half-yearly'  => esc_html__( 'Half Yearly (6 Months)', 'school-management' ),
			'annually'     => esc_html__( 'Annually (12 Months)', 'school-management' ),
		);
	}

	public static function due_date_period() {
		return array(
			'daily'    => esc_html__( 'Daily', 'school-management' ),
			'monthly'  => esc_html__( 'Monthly', 'school-management' ),
			'annually' => esc_html__( 'Annually', 'school-management' ),
		);
	}

	public static function get_certificate_property( $key ) {
		if ( array_key_exists( $key, self::certificate_properties() ) ) {
			return self::certificate_properties()[ $key ];
		}
		return '';
	}

	public static function certificate_properties() {
		return array(
			'left'        => esc_html__( 'Position X', 'school-management' ),
			'top'         => esc_html__( 'Position Y', 'school-management' ),
			'font-weight' => esc_html__( 'Font Weight', 'school-management' ),
			'font-size'   => esc_html__( 'Font Size', 'school-management' ),
			'width'       => esc_html__( 'Width', 'school-management' ),
			'height'      => esc_html__( 'Height', 'school-management' ),
		);
	}

	public static function get_certificate_field_label( $key ) {
		if ( array_key_exists( $key, self::certificate_field_labels() ) ) {
			return self::certificate_field_labels()[ $key ];
		}
		return '';
	}

	public static function certificate_field_labels() {
		return array(
			'name'               => esc_html__( 'Name', 'school-management' ),
			'certificate-number' => esc_html__( 'Certificate Number', 'school-management' ),
			'certificate-title'  => esc_html__( 'Certificate Title', 'school-management' ),
			'photo'              => esc_html__( 'Photo', 'school-management' ),
			'qcode'              => esc_html__( 'QR Code', 'school-management' ),
			'enrollment-number'  => esc_html__( 'Enrollment Number', 'school-management' ),
			'admission-number'   => esc_html__( 'Admission Number', 'school-management' ),
			'roll-number'        => esc_html__( 'Roll Number', 'school-management' ),
			'session-label'      => esc_html__( 'Session Label', 'school-management' ),
			'session-start-date' => esc_html__( 'Session Start Date', 'school-management' ),
			'session-end-date'   => esc_html__( 'Session End Date', 'school-management' ),
			'session-start-year' => esc_html__( 'Session Start Year', 'school-management' ),
			'session-end-year'   => esc_html__( 'Session End Year', 'school-management' ),
			'class'              => esc_html__( 'Class', 'school-management' ),
			'section'            => esc_html__( 'Section', 'school-management' ),
			'dob'                => esc_html__( 'Date of Birth', 'school-management' ),
			'caste'              => esc_html__( 'Caste', 'school-management' ),
			'blood-group'        => esc_html__( 'Blood Group', 'school-management' ),
			'father-name'        => esc_html__( 'Father\'s Name', 'school-management' ),
			'mother-name'        => esc_html__( 'Mother\'s Name', 'school-management' ),
			'class-teacher'      => esc_html__( 'Class Teacher', 'school-management' ),
			'school-name'        => esc_html__( 'School Name', 'school-management' ),
			'school-phone'       => esc_html__( 'School Phone', 'school-management' ),
			'school-email'       => esc_html__( 'School Email', 'school-management' ),
			'school-address'     => esc_html__( 'School Address', 'school-management' ),
			'school-logo'        => esc_html__( 'School Logo', 'school-management' ),
			'total-max-mark'     => esc_html__( 'Total Max Marks', 'school-management' ),
			'total-obtained-mark'=> esc_html__( 'Total Obtained Marks', 'school-management' ),
			'rank'               => esc_html__( 'Rank', 'school-management' ),
			'percentage'              => esc_html__( 'Percentage', 'school-management' ),
		);
	}

	public static function get_certificate_field_type( $key ) {
		if ( array_key_exists( $key, self::certificate_field_types() ) ) {
			return self::certificate_field_types()[ $key ];
		}
		return 'text';
	}

	public static function certificate_field_types() {
		return array(
			'left'        => 'number',
			'top'         => 'number',
			'font-weight' => 'number',
			'font-size'   => 'number',
			'width'       => 'number',
			'height'      => 'number'
		);
	}

	public static function get_certificate_place_holder( $key, $school_id = '' ) {
		if ( array_key_exists( $key, self::certificate_place_holders( $school_id ) ) ) {
			return self::certificate_place_holders( $school_id )[ $key ];
		}
		return '';
	}

	public static function certificate_place_holders( $school_id = '' ) {
		$school_name         = '';
		$school_phone        = '';
		$school_email        = '';
		$school_address      = '';
		$school_logo_url     = '';
		$total_max_mark      = '';
		$total_obtained_mark = '';
		$rank                = '';
		$percentage               = '';

		if ( $school_id ) {
			$school         = WLSM_M_School::fetch_school( $school_id );
			$school_name    = esc_html( WLSM_M_School::get_label_text( $school->label ) );
			$school_phone   = esc_html( WLSM_M_School::get_phone_text( $school->phone ) );
			$school_email   = esc_html( WLSM_M_School::get_email_text( $school->email ) );
			$school_address = esc_html( WLSM_M_School::get_address_text( $school->address ) );

			$settings_general = WLSM_M_Setting::get_settings_general( $school_id );
			$school_logo      = $settings_general['school_logo'];
			if ( ! empty ( $school_logo ) ) {
				$school_logo_url = esc_url( wp_get_attachment_url( $school_logo ) );
			}
		}

		return array(
			'name'                => '[STUDENT_NAME]',
			'certificate-number'  => '[CERTIFICATE_NO]',
			'certificate-title'   => '[CERTIFICATE_TITLE]',
			'photo'               => WLSM_PLUGIN_URL . 'assets/images/student.jpg',
			'qcode'               => WLSM_PLUGIN_URL . 'assets/images/qcode.png',
			'enrollment-number'   => '[ENROLLMENT_NO]',
			'admission-number'    => '[ADMISSION_NO]',
			'roll-number'         => '[ROLL_NO]',
			'session-label'       => '[SESSION_LABEL]',
			'session-start-date'  => '[START_DATE]',
			'session-end-date'    => '[END_DATE]',
			'session-start-year'  => '[START_YEAR]',
			'session-end-year'    => '[END_YEAR]',
			'class'               => '[CLASS]',
			'section'             => '[SECTION]',
			'dob'                 => '[DATE_OF_BIRTH]',
			'caste'               => '[CASTE]',
			'blood-group'         => '[BLOOD_GROUP]',
			'father-name'         => '[FATHER_NAME]',
			'mother-name'         => '[MOTHER_NAME]',
			'class-teacher'       => '[CLASS_TEACHER]',
			'school-name'         => $school_name,
			'school-phone'        => $school_phone,
			'school-email'        => $school_email,
			'school-address'      => $school_address,
			'school-logo'         => $school_logo_url,
			'total-max-mark'      => '[TOTAL_MAX_MARKS]',
			'total-obtained-mark' => '[TOTAL_OBTAINED_MARKS]',
			'rank'                => '[RANK]',
			'percentage'               => '[PERCENTAGE]',
		);
	}

	public static function get_certificate_place_holder_type( $key ) {
		if ( array_key_exists( $key, self::certificate_place_holder_types() ) ) {
			return self::certificate_place_holder_types()[ $key ];
		}
		return 'text';
	}

	public static function certificate_place_holder_types() {
		return array(
			'name'                => 'text',
			'certificate-number'  => 'text',
			'certificate-title'   => 'text',
			'photo'               => 'image',
			'qcode'               => 'image',
			'enrollment-number'   => 'text',
			'admission-number'    => 'text',
			'roll-number'         => 'text',
			'session-label'       => 'text',
			'session-start-date'  => 'text',
			'session-end-date'    => 'text',
			'session-start-year'  => 'text',
			'session-end-year'    => 'text',
			'class'               => 'text',
			'section'             => 'text',
			'dob'                 => 'text',
			'caste'               => 'text',
			'blood-group'         => 'text',
			'father-name'         => 'text',
			'mother-name'         => 'text',
			'class-teacher'       => 'text',
			'school-name'         => 'text',
			'school-phone'        => 'text',
			'school-email'        => 'text',
			'school-address'      => 'text',
			'school-logo'         => 'image',
			'total-max-mark'      => 'text',
			'total-obtained-mark' => 'text',
			'rank'                => 'text',
			'percentage'          => 'text',
		);
	}

	public static function charts() {
		return array(
			'monthly_admissions'     => esc_html__( 'Monthly Admissions', 'school-management' ),
			'monthly_payments'       => esc_html__( 'Monthly Payments', 'school-management' ),
			'monthly_income_expense' => esc_html__( 'Monthly Income / Expense', 'school-management' )
		);
	}

	public static function chart_types() {
		return array(
			'line',
			'bar',
			'radar',
			'pie',
			'doughnut',
			'polarArea'
		);
	}

	public static function default_chart_types() {
		return array(
			'monthly_admissions'     => 'bar',
			'monthly_payments'       => 'bar',
			'monthly_income_expense' => 'bar'
		);
	}

	public static function get_certificate_dynamic_fields() {
		return array(
			'name' => array(
				'enable' => 1,
				'props'  => array(
					'left' => array(
						'value' => '187',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '544',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '18',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'certificate-number' => array(
				'enable' => 1,
				'props'  => array(
					'left' => array(
						'value' => '58',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '24',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '14',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'certificate-title' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '190',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '24',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '20',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'photo' => array(
				'enable' => 1,
				'props'  => array(
					'left' => array(
						'value' => '460',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '319',
						'unit'  => 'pt'
					),
					'width' => array(
						'value' => '98',
						'unit'  => 'pt'
					),
					'height' => array(
						'value' => '135',
						'unit'  => 'pt'
					)
				),
				'type' => 'image'
			),
			'qcode' => array(
				'enable' => 1,
				'props'  => array(
					'left' => array(
						'value' => '325',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '319',
						'unit'  => 'pt'
					),
					'width' => array(
						'value' => '98',
						'unit'  => 'pt'
					),
					'height' => array(
						'value' => '135',
						'unit'  => 'pt'
					)
				),
				'type' => 'image'
			),
			// 'enrollment-number' => array(
			// 	'enable' => 1,
			// 	'props'  => array(
			// 		'left' => array(
			// 			'value' => '119',
			// 			'unit'  => 'pt'
			// 		),
			// 		'top' => array(
			// 			'value' => '355',
			// 			'unit'  => 'pt'
			// 		),
			// 		'font-weight' => array(
			// 			'value' => '600',
			// 			'unit'  => ''
			// 		),
			// 		'font-size' => array(
			// 			'value' => '14',
			// 			'unit'  => 'pt'
			// 		)
			// 	),
			// 	'type' => 'text'
			// ),
			'admission-number' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '150',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '150',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '16',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'roll-number' => array(
				'enable' => 1,
				'props'  => array(
					'left' => array(
						'value' => '82',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '394',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '14',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'session-label' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '165',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '643',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '16',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'session-start-date' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '297',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '643',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '16',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'session-end-date' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '412',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '643',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '16',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'session-start-year' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '375',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '275',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '16',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'session-end-year' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '375',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '275',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '16',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'class' => array(
				'enable' => 1,
				'props'  => array(
					'left' => array(
						'value' => '363',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '594',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '16',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'section' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '300',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '300',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '16',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'dob' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '165',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '643',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '16',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'caste' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '187',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '544',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '18',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'blood-group' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '187',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '544',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '18',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'father-name' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '187',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '544',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '18',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'mother-name' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '187',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '544',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '18',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'class-teacher' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '187',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '544',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '18',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'school-name' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '165',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '643',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '16',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'school-phone' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '165',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '643',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '12',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'school-email' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '165',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '643',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '12',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'school-address' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '165',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '643',
						'unit'  => 'pt'
					),
					'font-weight' => array(
						'value' => '600',
						'unit'  => ''
					),
					'font-size' => array(
						'value' => '12',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'school-logo' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '150',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '50',
						'unit'  => 'pt'
					),
					'width' => array(
						'value' => '90',
						'unit'  => 'pt'
					),
					'height' => array(
						'value' => '90',
						'unit'  => 'pt'
					)
				),
				'type' => 'image'
			),
			'total-max-mark' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '150',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '50',
						'unit'  => 'pt'
					),
					'width' => array(
						'value' => '90',
						'unit'  => 'pt'
					),
					'height' => array(
						'value' => '90',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'total-obtained-mark' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '150',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '50',
						'unit'  => 'pt'
					),
					'width' => array(
						'value' => '90',
						'unit'  => 'pt'
					),
					'height' => array(
						'value' => '90',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'rank' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '150',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '50',
						'unit'  => 'pt'
					),
					'width' => array(
						'value' => '90',
						'unit'  => 'pt'
					),
					'height' => array(
						'value' => '90',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			),
			'percentage' => array(
				'enable' => 0,
				'props'  => array(
					'left' => array(
						'value' => '150',
						'unit'  => 'pt'
					),
					'top' => array(
						'value' => '50',
						'unit'  => 'pt'
					),
					'width' => array(
						'value' => '90',
						'unit'  => 'pt'
					),
					'height' => array(
						'value' => '90',
						'unit'  => 'pt'
					)
				),
				'type' => 'text'
			)
		);
	}

	public static function get_image_mime() {
		return array('image/jpg', 'image/jpeg', 'image/png');
	}

	public static function get_csv_mime() {
		return array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
	}

	public static function get_attachment_mime() {
		return array('image/jpg', 'image/jpeg', 'image/png', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/x-rar-compressed', 'application/octet-stream', 'application/zip', 'application/octet-stream', 'application/x-zip-compressed', 'multipart/x-zip', 'video/x-flv', 'video/mp4', 'application/x-mpegURL', 'video/MP2T', 'video/3gpp', 'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv');
	}

	public static function is_valid_file( $file, $type = 'attachment' ) {
		$get_mime = 'get_' . $type . '_mime';

		if ( extension_loaded( 'fileinfo' ) ) {
			$finfo = finfo_open( FILEINFO_MIME_TYPE );
			$mime  = finfo_file( $finfo, $file['tmp_name'] );
			finfo_close( $finfo );

		} else {
			$mime = $file['type'];
		}

		if ( ! in_array( $mime, self::$get_mime() ) ) {
			return false;
		}

		return true;
	}

	public static function calculate_grade( $marks_grades, $percentage ) {
		$percentage = absint( $percentage );
		foreach ( $marks_grades as $mark_grade ) {
			if ( $mark_grade['min'] <= $percentage && $percentage <= $mark_grade['max'] ) {
				return $mark_grade['grade'];
			}
		}

		return '';
	}

	public static function inquiry_success_message_placeholders() {
		return array(
			'[NAME]'  => esc_html__( 'Inquisitor Name', 'school-management' ),
			'[PHONE]' => esc_html__( 'Inquisitor Phone', 'school-management' ),
			'[EMAIL]' => esc_html__( 'Inquisitor Email', 'school-management' ),
			'[CLASS]' => esc_html__( 'Inquisitor Class', 'school-management' )
		);
	}

	public static function registration_success_message_placeholders() {
		return array(
			'[NAME]'  => esc_html__( 'Student Name', 'school-management' ),
			'[PHONE]' => esc_html__( 'Phone', 'school-management' ),
			'[EMAIL]' => esc_html__( 'Email', 'school-management' ),
			'[CLASS]' => esc_html__( 'Class', 'school-management' )
		);
	}

	public static function enqueue_datatable_assets() {
		wp_enqueue_style( 'jquery-dataTables', WLSM_PLUGIN_URL . 'assets/css/datatable/jquery.dataTables.min.css' );
		wp_enqueue_style( 'buttons-bootstrap4', WLSM_PLUGIN_URL . 'assets/css/datatable/buttons.bootstrap4.min.css' );

		wp_enqueue_script( 'dataTables-buttons', WLSM_PLUGIN_URL . 'assets/js/datatable/dataTables.buttons.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'buttons-bootstrap4', WLSM_PLUGIN_URL . 'assets/js/datatable/buttons.bootstrap4.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'print', WLSM_PLUGIN_URL . 'assets/js/datatable/buttons.print.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'pdf', WLSM_PLUGIN_URL . 'assets/js/datatable/pdfmake.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'colVis', WLSM_PLUGIN_URL . 'assets/js/datatable/buttons.colVis.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'jszip', WLSM_PLUGIN_URL . 'assets/js/datatable/jszip.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'vfs-fonts', WLSM_PLUGIN_URL . 'assets/js/datatable/vfs_fonts.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'buttons-html5', WLSM_PLUGIN_URL . 'assets/js/datatable/buttons.html5.min.js', array( 'jszip' ), true, true );
	}

	public static function check_buffer( $show_buffer_error = true ) {
		$buffer = ob_get_clean();
		if ( ! empty( $buffer ) ) {
			if ( $show_buffer_error ) {
				throw new Exception( $buffer );
			}

			throw new Exception( esc_html__( 'Unexpected error occurred!', 'school-management' ) );
		}
	}

	public static function is_php_incompatible_for_meetings( $version = '7.1.0' ) {
		return ( ! ( version_compare( PHP_VERSION, $version ) >= 0 ) );
	}

	public static function check_demo() {
		if ( WLSM_DEMO_MODE ) {
			wp_send_json_error( 'This action is disabled in demo.' );
		}
	}

	public static function lm_valid() {
		return true;
		$wlsm_lm     = WLSM_LM::get_instance();
		$wlsm_lm_val = $wlsm_lm->is_valid();
		if ( isset( $wlsm_lm_val ) && $wlsm_lm_val ) {
			return true;
		}
		return false;
	}

	public static function get_activity_fees($activity_id) {
		global $wpdb;
		$query = $wpdb->prepare('SELECT * FROM '.WLSM_ACTIVITIES.' WHERE ID = %d', $activity_id);
		$result = $wpdb->get_row($query, ARRAY_A);
		return $result;
	}
}
