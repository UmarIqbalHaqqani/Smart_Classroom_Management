<?php
defined('ABSPATH') || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/constants.php';

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Helper.php';

class WLSM_Database
{
	public static function activation()
	{
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$wpdb->query("ALTER TABLE " . WLSM_USERS . " ENGINE = InnoDB");
		$wpdb->query("ALTER TABLE " . WLSM_POSTS . " ENGINE = InnoDB");

		/* Create schools table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_SCHOOLS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				label varchar(191) DEFAULT NULL,
				phone varchar(255) DEFAULT NULL,
				email varchar(255) DEFAULT NULL,
				address text DEFAULT NULL,
				is_active tinyint(1) NOT NULL DEFAULT '1',
				last_enrollment_count bigint(20) NOT NULL DEFAULT '0',
				last_invoice_count bigint(20) NOT NULL DEFAULT '0',
				last_payment_count bigint(20) NOT NULL DEFAULT '0',
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (label)
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Add description columns if not exists to schools table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_SCHOOLS . "' AND COLUMN_NAME = 'description'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_SCHOOLS . " ADD description text DEFAULT NULL");
		}

		/* Add description columns if not exists to schools table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_SCHOOLS . "' AND COLUMN_NAME = 'category_id'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_SCHOOLS . " ADD category_id bigint(20) NOT NULL DEFAULT '0'");
		}

		/* Add last_admission_count, admission_prefix, admission_base, admission_padding columns if not exists to schools table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_SCHOOLS . "' AND COLUMN_NAME = 'last_admission_count'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_SCHOOLS . " ADD last_admission_count bigint(20) NOT NULL DEFAULT '0'");
			$wpdb->query("ALTER TABLE " . WLSM_SCHOOLS . " ADD admission_prefix varchar(15) DEFAULT ''");
			$wpdb->query("ALTER TABLE " . WLSM_SCHOOLS . " ADD admission_base int(11) UNSIGNED DEFAULT '0'");
			$wpdb->query("ALTER TABLE " . WLSM_SCHOOLS . " ADD admission_padding smallint(4) UNSIGNED DEFAULT '6'");
		}

		/* Create settings table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_SETTINGS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				setting_key varchar(191) DEFAULT NULL,
				setting_value text DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (school_id, setting_key),
				INDEX (school_id),
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create classes table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_CLASSES . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				label varchar(191) DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (label)
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create category table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_CATEGORY . " (
			ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			label varchar(191) DEFAULT NULL,
			created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at timestamp NULL DEFAULT NULL,
			PRIMARY KEY (ID),
			UNIQUE (label)
			) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		// Insert default category if there is no class.
		$category_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . WLSM_CATEGORY);
		if (!$category_count) {
			self::insert_default_category();
		}

		// Insert default school if there is no school.
		$schools_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . WLSM_SCHOOLS);
		if (!$schools_count) {
			$default_school_id = self::insert_default_school();
		}

		// Insert default classes if there is no class.
		$classes_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . WLSM_CLASSES);
		if (!$classes_count) {
			self::insert_default_classes();
		}

		/* Create class_school table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_CLASS_SCHOOL . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				class_id bigint(20) UNSIGNED DEFAULT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				default_section_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (class_id, school_id),
				INDEX (class_id),
				INDEX (school_id),
				FOREIGN KEY (class_id) REFERENCES " . WLSM_CLASSES . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create sessions table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_SESSIONS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				label varchar(191) DEFAULT NULL,
				start_date date NULL DEFAULT NULL,
				end_date date NULL DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (label, start_date, end_date)
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		$session_id     = NULL;
		$sessions_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . WLSM_SESSIONS);
		if (!$sessions_count) {
			/* Insert Default Session */
			$session_years = 1;
			$current_session_exists = false;
			for ($i = 0; $i <= $session_years; $i++) {
				$current_year = absint(date('Y')) + $i;
				$next_year    = $current_year + 1;
				$start_date   = $current_year . '-4-1';
				$end_date     = $next_year . '-3-31';

				$data = array(
					'label'      => $current_year . '-' . $next_year,
					'start_date' => $start_date,
					'end_date'   => $end_date,
				);

				$data['created_at'] = current_time('Y-m-d H:i:s');

				$wpdb->insert(WLSM_SESSIONS, $data);

				if (!$current_session_exists) {
					$session_id = $wpdb->insert_id;

					$current_session_exists = true;
				}
			}
		}

		/* Create inquiries table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_INQUIRIES . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				name varchar(60) DEFAULT NULL,
				phone varchar(40) DEFAULT NULL,
				email varchar(60) DEFAULT NULL,
				message text DEFAULT NULL,
				note text DEFAULT NULL,
				next_follow_up date NULL DEFAULT NULL,
				is_active tinyint(1) NOT NULL DEFAULT '1',
				class_school_id bigint(20) UNSIGNED DEFAULT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (class_school_id),
				INDEX (school_id),
				FOREIGN KEY (class_school_id) REFERENCES " . WLSM_CLASS_SCHOOL . " (ID) ON DELETE SET NULL,
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Add gdpr_agreed column if not exists to inquiries table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_INQUIRIES . "' AND COLUMN_NAME = 'gdpr_agreed'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_INQUIRIES . " ADD gdpr_agreed tinyint(1) NOT NULL DEFAULT '0'");
		}

		/* Add section_id column if not exists to inquiries table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_INQUIRIES . "' AND COLUMN_NAME = 'section_id'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_INQUIRIES . " ADD section_id tinyint(1) NOT NULL DEFAULT '0'");
		}

		/* Add reference column if not exists to inquiries table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_INQUIRIES . "' AND COLUMN_NAME = 'reference'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_INQUIRIES . " ADD reference varchar(60) DEFAULT NULL");
		}

		/* Create roles table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_ROLES . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				name varchar(60) NOT NULL,
				permissions text NOT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (name, school_id),
				INDEX (school_id),
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create staff table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_STAFF . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				role varchar(40) NOT NULL,
				permissions text NOT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				user_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (school_id, user_id),
				INDEX (school_id),
				INDEX (user_id),
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (user_id) REFERENCES " . WLSM_USERS . " (ID) ON DELETE SET NULL
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create admins table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_ADMINS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				name varchar(60) DEFAULT NULL,
				gender varchar(10) DEFAULT NULL,
				dob date NULL DEFAULT NULL,
				phone varchar(40) DEFAULT NULL,
				email varchar(60) DEFAULT NULL,
				address text DEFAULT NULL,
				salary decimal(12,2) UNSIGNED DEFAULT NULL,
				designation varchar(80) DEFAULT NULL,
				joining_date date NULL DEFAULT NULL,
				role_id bigint(20) UNSIGNED DEFAULT NULL,
				staff_id bigint(20) UNSIGNED DEFAULT NULL,
				assigned_by_manager tinyint(1) NOT NULL DEFAULT '0',
				is_active tinyint(1) NOT NULL DEFAULT '1',
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (staff_id),
				FOREIGN KEY (role_id) REFERENCES " . WLSM_ROLES . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (staff_id) REFERENCES " . WLSM_STAFF . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Add note column if not exists to admins table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_ADMINS . "' AND COLUMN_NAME = 'note'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_ADMINS . " ADD note text DEFAULT NULL");
		}

		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_ADMINS . "' AND COLUMN_NAME = 'qualification'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_ADMINS . " ADD qualification text DEFAULT NULL");
		}

		/* Create sections table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_SECTIONS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				label varchar(191) DEFAULT NULL,
				class_school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (label, class_school_id),
				INDEX (class_school_id),
				FOREIGN KEY (class_school_id) REFERENCES " . WLSM_CLASS_SCHOOL . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);


		/* Create medium table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_MEDIUM . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				label varchar(191) DEFAULT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create student_type table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_STUDENT_TYPE . " (
			ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			label varchar(191) DEFAULT NULL,
			school_id bigint(20) UNSIGNED DEFAULT NULL,
			created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at timestamp NULL DEFAULT NULL,
			PRIMARY KEY (ID),
			FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
			) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		// Insert default student_type if there is no student_type. english, hindi.
		$student_type_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . WLSM_STUDENT_TYPE);
		if (!$student_type_count) {
			self::insert_default_student_type();
		}

		// Insert default medium if there is no medium. english, hindi.
		$medium_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . WLSM_MEDIUM);
		if (!$medium_count) {
			self::insert_default_medium();
		}

		/* Create subject_types table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_SUBJECT_TYPES . " (
			ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			label varchar(191) DEFAULT NULL,
			created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at timestamp NULL DEFAULT NULL,
			PRIMARY KEY (ID),
			UNIQUE (label)
			) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		// Insert default subject types if there is no subject type.
		$subject_types_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . WLSM_SUBJECT_TYPES);
		if (!$subject_types_count) {
			self::insert_default_subject_types();
		}

		/* Create student_records table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_STUDENT_RECORDS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				enrollment_number varchar(60) DEFAULT NULL,
				admission_number varchar(60) DEFAULT NULL,
				name varchar(255) DEFAULT NULL,
				gender varchar(10) DEFAULT NULL,
				dob date NULL DEFAULT NULL,
				phone varchar(40) DEFAULT NULL,
				email varchar(60) DEFAULT NULL,
				address text DEFAULT NULL,
				admission_date date NULL DEFAULT NULL,
				religion varchar(40) DEFAULT NULL,
				caste varchar(40) DEFAULT NULL,
				blood_group varchar(5) DEFAULT NULL,
				father_name varchar(60) DEFAULT NULL,
				mother_name varchar(60) DEFAULT NULL,
				father_phone varchar(40) DEFAULT NULL,
				mother_phone varchar(40) DEFAULT NULL,
				father_occupation varchar(60) DEFAULT NULL,
				mother_occupation varchar(60) DEFAULT NULL,
				roll_number varchar(30) DEFAULT NULL,
				photo_id bigint(20) UNSIGNED DEFAULT NULL,
				section_id bigint(20) UNSIGNED DEFAULT NULL,
				session_id bigint(20) UNSIGNED DEFAULT NULL,
				user_id bigint(20) UNSIGNED DEFAULT NULL,
				is_active tinyint(1) NOT NULL DEFAULT '1',
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (user_id),
				INDEX (section_id),
				INDEX (session_id),
				INDEX (user_id),
				FOREIGN KEY (section_id) REFERENCES " . WLSM_SECTIONS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (session_id) REFERENCES " . WLSM_SESSIONS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (user_id) REFERENCES " . WLSM_USERS . " (ID) ON DELETE SET NULL,
				FOREIGN KEY (photo_id) REFERENCES " . WLSM_POSTS . " (ID) ON DELETE SET NULL
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Add parent_user_id column if not exists to student_records table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_STUDENT_RECORDS . "' AND COLUMN_NAME = 'parent_user_id'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD parent_user_id bigint(20) UNSIGNED DEFAULT NULL AFTER user_id");
			$wpdb->query("CREATE INDEX parent_user_id ON " . WLSM_STUDENT_RECORDS . " (parent_user_id)");
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD FOREIGN KEY (parent_user_id) REFERENCES " . WLSM_USERS . " (ID) ON DELETE SET NULL");
		}

		/* Add city, state, country columns if not exists to student_records table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_STUDENT_RECORDS . "' AND COLUMN_NAME = 'city'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD city varchar(60) DEFAULT NULL");
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD state varchar(60) DEFAULT NULL");
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD country varchar(60) DEFAULT NULL");
		}

		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_STUDENT_RECORDS . "' AND COLUMN_NAME = 'medium'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD medium varchar(60) DEFAULT NULL");
		}

		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_STUDENT_RECORDS . "' AND COLUMN_NAME = 'activities'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD activities text DEFAULT NULL");
		}

		/* Add city, state, country columns if not exists to student_records table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_STUDENT_RECORDS . "' AND COLUMN_NAME = 'student_type'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD student_type varchar(60) DEFAULT NULL");
		}

		/* Add id_number, id_proof, parent_id_proof, note columns if not exists to student_records table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_STUDENT_RECORDS . "' AND COLUMN_NAME = 'id_number'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD id_number text DEFAULT NULL");
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD id_proof bigint(20) UNSIGNED DEFAULT NULL");
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD parent_id_proof bigint(20) UNSIGNED DEFAULT NULL");
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD FOREIGN KEY (id_proof) REFERENCES " . WLSM_POSTS . " (ID) ON DELETE SET NULL");
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD FOREIGN KEY (parent_id_proof) REFERENCES " . WLSM_POSTS . " (ID) ON DELETE SET NULL");
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD note text DEFAULT NULL");
		}

		/* Add gdpr_agreed, from_front columns if not exists to student_records table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_STUDENT_RECORDS . "' AND COLUMN_NAME = 'gdpr_agreed'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD gdpr_agreed tinyint(1) NOT NULL DEFAULT '0'");
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD from_front tinyint(1) NOT NULL DEFAULT '0'");
		}

		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_STUDENT_RECORDS . "' AND COLUMN_NAME = 'survey'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD survey varchar(60) DEFAULT NULL");
		}

		/* Create promotions table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_PROMOTIONS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				from_student_record bigint(20) UNSIGNED DEFAULT NULL,
				to_student_record bigint(20) UNSIGNED DEFAULT NULL,
				note varchar(255) DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (from_student_record),
				INDEX (to_student_record),
				FOREIGN KEY (from_student_record) REFERENCES " . WLSM_STUDENT_RECORDS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (to_student_record) REFERENCES " . WLSM_STUDENT_RECORDS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create transfers table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_TRANSFERS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				from_student_record bigint(20) UNSIGNED DEFAULT NULL,
				to_student_record bigint(20) UNSIGNED DEFAULT NULL,
				to_school varchar(255) DEFAULT NULL,
				note varchar(255) DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (from_student_record),
				INDEX (to_student_record),
				FOREIGN KEY (from_student_record) REFERENCES " . WLSM_STUDENT_RECORDS . " (ID) ON DELETE SET NULL,
				FOREIGN KEY (to_student_record) REFERENCES " . WLSM_STUDENT_RECORDS . " (ID) ON DELETE SET NULL
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create certificates table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_CERTIFICATES . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				label varchar(191) DEFAULT NULL,
				fields text DEFAULT NULL,
				image_id bigint(20) UNSIGNED DEFAULT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (school_id),
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (image_id) REFERENCES " . WLSM_POSTS . " (ID) ON DELETE SET NULL
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_CERTIFICATES . "' AND COLUMN_NAME = 'exam_id'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_CERTIFICATES . " ADD exam_id bigint(20) UNSIGNED DEFAULT NULL");
		}

		/* Create certificate_student table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_CERTIFICATE_STUDENT . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				certificate_number varchar(60) DEFAULT NULL,
				date_issued date NULL DEFAULT NULL,
				certificate_id bigint(20) UNSIGNED DEFAULT NULL,
				student_record_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (certificate_id),
				INDEX (student_record_id),
				FOREIGN KEY (certificate_id) REFERENCES " . WLSM_CERTIFICATES . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (student_record_id) REFERENCES " . WLSM_STUDENT_RECORDS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Add last_certificate_count column if not exists to schools table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_SCHOOLS . "' AND COLUMN_NAME = 'last_certificate_count'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_SCHOOLS . " ADD last_certificate_count bigint(20) NOT NULL DEFAULT '0'");
		}

		/* Create invoices table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_INVOICES . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				invoice_number varchar(60) DEFAULT NULL,
				label varchar(255) DEFAULT NULL,
				description varchar(255) DEFAULT NULL,
				amount decimal(12,2) UNSIGNED DEFAULT '0.00',
				discount decimal(12,2) UNSIGNED DEFAULT '0.00',
				date_issued date NULL DEFAULT NULL,
				due_date date NULL DEFAULT NULL,
				partial_payment tinyint(1) NOT NULL DEFAULT '0',
				status varchar(15) DEFAULT 'unpaid',
				student_record_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (student_record_id),
				FOREIGN KEY (student_record_id) REFERENCES " . WLSM_STUDENT_RECORDS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Add show_on_dashboard column if not exists to invoice table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_INVOICES . "' AND COLUMN_NAME = 'show_on_dashboard'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_INVOICES . " ADD show_on_dashboard tinyint(1) NOT NULL DEFAULT '0'");
		}

		/* Add fee_list columns if not exists to WLSM_INVOICES table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_INVOICES . "' AND COLUMN_NAME = 'fee_list'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_INVOICES . " ADD fee_list text DEFAULT NULL");
		}

		/* Add invoice_amount_total columns if not exists to WLSM_INVOICES table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_INVOICES . "' AND COLUMN_NAME = 'invoice_amount_total'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_INVOICES . " ADD invoice_amount_total decimal(12,2) UNSIGNED DEFAULT '0.00'");
		}

		/* Add due_date_amount, due_date_period columns if not exists to WLSM_INVOICES table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_INVOICES . "' AND COLUMN_NAME = 'due_date_amount'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_INVOICES . " ADD due_date_amount text DEFAULT NULL");
			$wpdb->query("ALTER TABLE " . WLSM_INVOICES . " ADD due_date_period text DEFAULT NULL");
		}

		/* Create payments table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_PAYMENTS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				receipt_number varchar(60) DEFAULT NULL,
				amount decimal(12,2) UNSIGNED DEFAULT '0.00',
				payment_method varchar(50) DEFAULT NULL,
				transaction_id varchar(80) DEFAULT NULL,
				note text DEFAULT NULL,
				invoice_label varchar(100) DEFAULT NULL,
				invoice_payable decimal(12,2) UNSIGNED DEFAULT '0.00',
				invoice_id bigint(20) UNSIGNED DEFAULT NULL,
				student_record_id bigint(20) UNSIGNED DEFAULT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (invoice_id),
				INDEX (student_record_id),
				INDEX (school_id),
				FOREIGN KEY (invoice_id) REFERENCES " . WLSM_INVOICES . " (ID) ON DELETE SET NULL,
				FOREIGN KEY (student_record_id) REFERENCES " . WLSM_STUDENT_RECORDS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Add attachment column if not exists to payments table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_PAYMENTS . "' AND COLUMN_NAME = 'attachment'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_PAYMENTS . " ADD attachment bigint(20) UNSIGNED DEFAULT NULL");
			$wpdb->query("ALTER TABLE " . WLSM_PAYMENTS . " ADD FOREIGN KEY (attachment) REFERENCES " . WLSM_POSTS . " (ID) ON DELETE SET NULL");
		}

		/* Create pending_payments table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_PENDING_PAYMENTS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				receipt_number varchar(60) DEFAULT NULL,
				amount decimal(12,2) UNSIGNED DEFAULT '0.00',
				payment_method varchar(50) DEFAULT NULL,
				transaction_id varchar(80) DEFAULT NULL,
				attachment bigint(20) UNSIGNED DEFAULT NULL,
				note text DEFAULT NULL,
				invoice_label varchar(100) DEFAULT NULL,
				invoice_payable decimal(12,2) UNSIGNED DEFAULT '0.00',
				invoice_id bigint(20) UNSIGNED DEFAULT NULL,
				student_record_id bigint(20) UNSIGNED DEFAULT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (invoice_id),
				INDEX (student_record_id),
				INDEX (school_id),
				FOREIGN KEY (attachment) REFERENCES " . WLSM_POSTS . " (ID) ON DELETE SET NULL,
				FOREIGN KEY (invoice_id) REFERENCES " . WLSM_INVOICES . " (ID) ON DELETE SET NULL,
				FOREIGN KEY (student_record_id) REFERENCES " . WLSM_STUDENT_RECORDS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create expense_categories table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_EXPENSE_CATEGORIES . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				label varchar(100) DEFAULT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (school_id),
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create expenses table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_EXPENSES . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				label varchar(100) DEFAULT NULL,
				invoice_number varchar(80) DEFAULT NULL,
				amount decimal(12,2) UNSIGNED DEFAULT '0.00',
				expense_date date NULL DEFAULT NULL,
				note text DEFAULT NULL,
				expense_category_id bigint(20) UNSIGNED DEFAULT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (expense_category_id),
				INDEX (school_id),
				FOREIGN KEY (expense_category_id) REFERENCES " . WLSM_EXPENSE_CATEGORIES . " (ID) ON DELETE SET NULL,
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_EXPENSES . "' AND COLUMN_NAME = 'attachment'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_EXPENSES . " ADD attachment bigint(20) UNSIGNED DEFAULT NULL");
		}

		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_EXPENSES . "' AND COLUMN_NAME = 'session_id'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_EXPENSES . " ADD session_id bigint(20) UNSIGNED DEFAULT NULL");
		}

		/* Create income_categories table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_INCOME_CATEGORIES . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				label varchar(100) DEFAULT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (school_id),
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create income table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_INCOME . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				label varchar(100) DEFAULT NULL,
				invoice_number varchar(80) DEFAULT NULL,
				amount decimal(12,2) UNSIGNED DEFAULT '0.00',
				income_date date NULL DEFAULT NULL,
				note text DEFAULT NULL,
				income_category_id bigint(20) UNSIGNED DEFAULT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (income_category_id),
				INDEX (school_id),
				FOREIGN KEY (income_category_id) REFERENCES " . WLSM_INCOME_CATEGORIES . " (ID) ON DELETE SET NULL,
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_INCOME . "' AND COLUMN_NAME = 'attachment'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_INCOME . " ADD attachment bigint(20) UNSIGNED DEFAULT NULL");
		}

		/* Create attendance table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_ATTENDANCE . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				attendance_date date NOT NULL,
				status varchar(2) DEFAULT NULL,
				student_record_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (student_record_id),
				FOREIGN KEY (student_record_id) REFERENCES " . WLSM_STUDENT_RECORDS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_ATTENDANCE . "' AND COLUMN_NAME = 'reason'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_ATTENDANCE . " ADD reason text DEFAULT NULL");
		}


		/* Add subject_id column if not exists to exams table */
		// $row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS
		// WHERE CONSTRAINT_NAME ='student_record_id'");
		// if (empty($row)) {
		// 	$wpdb->query("ALTER TABLE " . WLSM_ATTENDANCE . " DROP CONSTRAINT student_record_id");
		// }

		/* Add subject_id column if not exists to exams table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_ATTENDANCE . "' AND COLUMN_NAME = 'subject_id'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_ATTENDANCE . " ADD subject_id bigint(20) UNSIGNED DEFAULT NULL");
		}

		// /* Remove UNIQUE attendance_date column if exists to attendance table */
		// $row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_ATTENDANCE . "' AND COLUMN_NAME = 'attendance_date'");
		// if (!empty($row)) {
		// 	$wpdb->query("ALTER TABLE " . WLSM_ATTENDANCE . " DROP INDEX attendance_date");
		// }

		/* Create staff_attendance table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_STAFF_ATTENDANCE . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				attendance_date date NOT NULL,
				status varchar(2) DEFAULT NULL,
				admin_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (attendance_date, admin_id),
				INDEX (admin_id),
				FOREIGN KEY (admin_id) REFERENCES " . WLSM_ADMINS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_STAFF_ATTENDANCE . "' AND COLUMN_NAME = 'reason'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_STAFF_ATTENDANCE . " ADD reason text DEFAULT NULL");
		}


		/* Create subjects table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_SUBJECTS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				label varchar(100) DEFAULT NULL,
				code varchar(40) DEFAULT NULL,
				type varchar(40) DEFAULT NULL,
				class_school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (label, class_school_id),
				INDEX (code, class_school_id),
				INDEX (class_school_id),
				FOREIGN KEY (class_school_id) REFERENCES " . WLSM_CLASS_SCHOOL . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create subjects table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_STUDENTS_SUBJECTS . " (
			ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			student_id bigint(20) UNSIGNED DEFAULT NULL,
			subject_id bigint(20) DEFAULT NULL,
			PRIMARY KEY (ID)
			) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		$subject_student = $wpdb->get_var('SELECT COUNT(*) FROM ' . WLSM_STUDENTS_SUBJECTS);
		if (!$subject_student) {
			$default_school_id = self::insert_default_subjects();
		}

		/* Create exams table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_EXAMS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				label varchar(191) DEFAULT NULL,
				exam_center varchar(255) DEFAULT NULL,
				grade_criteria text DEFAULT NULL,
				start_date date NULL DEFAULT NULL,
				end_date date NULL DEFAULT NULL,
				enable_room_numbers tinyint(1) NOT NULL DEFAULT '0',
				results_published tinyint(1) NOT NULL DEFAULT '0',
				admit_cards_published tinyint(1) NOT NULL DEFAULT '0',
				time_table_published tinyint(1) NOT NULL DEFAULT '0',
				is_active tinyint(1) NOT NULL DEFAULT '1',
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (school_id),
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Add exam_group column if not exists to exams table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_EXAMS . "' AND COLUMN_NAME = 'psychomotor_analysis'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_EXAMS . " ADD exam_group varchar(60) DEFAULT NULL");
			$wpdb->query("ALTER TABLE " . WLSM_EXAMS . " ADD psychomotor_analysis tinyint(1) NOT NULL DEFAULT '1'");
		}

		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_EXAMS . "' AND COLUMN_NAME = 'psychomotor_analysis'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_EXAMS . " ADD exam_group varchar(60) DEFAULT NULL");
			$wpdb->query("ALTER TABLE " . WLSM_EXAMS . " ADD psychomotor_analysis tinyint(1) NOT NULL DEFAULT '1'");
		}

		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_EXAMS . "' AND COLUMN_NAME = 'enable_total_marks'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_EXAMS . " ADD enable_total_marks varchar(60) DEFAULT NULL");
			$wpdb->query("ALTER TABLE " . WLSM_EXAMS . " ADD results_obtained_marks varchar(60) DEFAULT NULL");
		}

		/* Add psychomotor column if not exists to exams table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_EXAMS . "' AND COLUMN_NAME = 'psychomotor'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_EXAMS . " ADD psychomotor text DEFAULT NULL");
		}

		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_EXAMS . "' AND COLUMN_NAME = 'teacher_signature'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_EXAMS . " ADD teacher_signature text DEFAULT NULL");
		}

		/* Add show_in_assessment column if not exists to exams table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_EXAMS . "' AND COLUMN_NAME = 'show_in_assessment'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_EXAMS . " ADD show_in_assessment tinyint(1) NOT NULL DEFAULT '1'");
		}

		// exam_group table
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_EXAMS_GROUP . " (
			ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			label varchar(191) DEFAULT NULL,
			is_active tinyint(1) NOT NULL DEFAULT '1',
			school_id bigint(20) UNSIGNED DEFAULT NULL,
			created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at timestamp NULL DEFAULT NULL,
			PRIMARY KEY (ID),
			INDEX (school_id),
			FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
			) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

//		Show ranks
		/* Add show_rank, show_remark column if not exists to exams table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_EXAMS . "' AND COLUMN_NAME = 'show_rank'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_EXAMS . " ADD show_rank tinyint(1) NOT NULL DEFAULT '1'");
			$wpdb->query("ALTER TABLE " . WLSM_EXAMS . " ADD show_remark tinyint(1) NOT NULL DEFAULT '1'");
			$wpdb->query("ALTER TABLE " . WLSM_EXAMS . " ADD show_eremark tinyint(1) NOT NULL DEFAULT '1'");
		}

		/* Create class_school_exam table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_CLASS_SCHOOL_EXAM . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				class_school_id bigint(20) UNSIGNED DEFAULT NULL,
				exam_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (class_school_id, exam_id),
				INDEX (class_school_id),
				INDEX (exam_id),
				FOREIGN KEY (class_school_id) REFERENCES " . WLSM_CLASS_SCHOOL . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (exam_id) REFERENCES " . WLSM_EXAMS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create exam_papers table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_EXAM_PAPERS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				subject_label varchar(100) DEFAULT NULL,
				subject_type varchar(40) DEFAULT NULL,
				paper_code varchar(40) DEFAULT NULL,
				paper_date date NULL DEFAULT NULL,
				paper_order smallint(4) UNSIGNED DEFAULT '10',
				start_time time DEFAULT NULL,
				end_time time DEFAULT NULL,
				room_number varchar(40) DEFAULT NULL,
				maximum_marks smallint(4) UNSIGNED DEFAULT NULL,
				exam_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (paper_code, exam_id),
				INDEX (exam_id),
				FOREIGN KEY (exam_id) REFERENCES " . WLSM_EXAMS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_EXAM_PAPERS . "' AND COLUMN_NAME = 'subject_id'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_EXAM_PAPERS . " ADD subject_id bigint(20) UNSIGNED DEFAULT NULL");
		}

		/* Create admit_cards table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_ADMIT_CARDS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				roll_number varchar(40) DEFAULT NULL,
				exam_id bigint(20) UNSIGNED DEFAULT NULL,
				student_record_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (exam_id, roll_number),
				UNIQUE (exam_id, student_record_id),
				INDEX (exam_id),
				INDEX (student_record_id),
				FOREIGN KEY (exam_id) REFERENCES " . WLSM_EXAMS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (student_record_id) REFERENCES " . WLSM_STUDENT_RECORDS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create exam_results table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_EXAM_RESULTS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				obtained_marks smallint(4) UNSIGNED DEFAULT NULL,
				exam_paper_id bigint(20) UNSIGNED DEFAULT NULL,
				admit_card_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (exam_paper_id, admit_card_id),
				INDEX (exam_paper_id),
				INDEX (admit_card_id),
				FOREIGN KEY (exam_paper_id) REFERENCES " . WLSM_EXAM_PAPERS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (admit_card_id) REFERENCES " . WLSM_ADMIT_CARDS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		$wpdb->query("ALTER TABLE " . WLSM_EXAM_RESULTS . " MODIFY obtained_marks text DEFAULT NULL");

		/* Add remark column if not exists to fees table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_EXAM_RESULTS . "' AND COLUMN_NAME = 'remark'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_EXAM_RESULTS . " ADD remark text DEFAULT NULL");
		}

		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_EXAM_RESULTS . "' AND COLUMN_NAME = 'answer_key'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_EXAM_RESULTS . " ADD answer_key text DEFAULT NULL");
		}

		/* Add scale column if not exists to fees table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_EXAM_RESULTS . "' AND COLUMN_NAME = 'scale'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_EXAM_RESULTS . " ADD scale text DEFAULT NULL");
		}

		/* Add teacher_remark, school_remark  column if not exists to fees table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_EXAM_RESULTS . "' AND COLUMN_NAME = 'teacher_remark'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_EXAM_RESULTS . " ADD teacher_remark text DEFAULT NULL");
			$wpdb->query("ALTER TABLE " . WLSM_EXAM_RESULTS . " ADD school_remark text DEFAULT NULL");
		}

		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_ACADEMIC_REPORTS . " (
			`ID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			`label` VARCHAR(191) NOT NULL,
			`class_id` INT(11) UNSIGNED NOT NULL,
			`exam_group` VARCHAR(191) NOT NULL,
			`exams` TEXT NOT NULL,
			`is_active` TINYINT(1) NOT NULL DEFAULT '1',
			`school_id` INT(11) UNSIGNED NOT NULL,
			`created_at` DATETIME NOT NULL,
			`updated_at` DATETIME NOT NULL,
			PRIMARY KEY (`ID`)
		    ) ENGINE=InnoDB " . $charset_collate;
		  dbDelta($sql);

		/* Create notices table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_NOTICES . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				title text DEFAULT NULL,
				attachment bigint(20) UNSIGNED DEFAULT NULL,
				url text DEFAULT NULL,
				link_to varchar(15) DEFAULT NULL,
				is_active tinyint(1) NOT NULL DEFAULT '1',
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				added_by bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (school_id),
				INDEX (added_by),
				FOREIGN KEY (attachment) REFERENCES " . WLSM_POSTS . " (ID) ON DELETE SET NULL,
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (added_by) REFERENCES " . WLSM_USERS . " (ID) ON DELETE SET NULL
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Add description column if not exists to fees table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_NOTICES . "' AND COLUMN_NAME = 'description'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_NOTICES . " ADD description text DEFAULT NULL");
		}

		/* Create class_school_notice table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_CLASS_SCHOOL_NOTICE . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				class_school_id bigint(20) UNSIGNED DEFAULT NULL,
				notice_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (class_school_id, notice_id),
				INDEX (class_school_id),
				INDEX (notice_id),
				FOREIGN KEY (class_school_id) REFERENCES " . WLSM_CLASS_SCHOOL . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (notice_id) REFERENCES " . WLSM_NOTICES . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);


		/* Add remark column if not exists to fees table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_CLASS_SCHOOL_NOTICE . "' AND COLUMN_NAME = 'class_school_id'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_CLASS_SCHOOL_NOTICE . " ADD student_school_id bigint(20) UNSIGNED DEFAULT NULL");
		}
		/* Add student_school_id column if not exists to fees table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_CLASS_SCHOOL_NOTICE . "' AND COLUMN_NAME = 'student_school_id'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_CLASS_SCHOOL_NOTICE . " ADD student_school_id bigint(20) UNSIGNED DEFAULT NULL");
		}

			/* Create HOSTELS table */
			$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_HOSTELS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				hostel_name varchar(200) DEFAULT NULL,
				hostel_type varchar(200) DEFAULT NULL,
				hostel_address varchar(200) DEFAULT NULL,
				hostel_intake varchar(200) DEFAULT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (school_id),
				FOREIGN KEY (school_id) REFERENCES " . WLSM_HOSTELS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

			/* Add fees column if not exists to student_records table */
			$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_HOSTELS . "' AND COLUMN_NAME = 'fees'");
			if (empty($row)) {
				$wpdb->query("ALTER TABLE " . WLSM_HOSTELS . " ADD fees varchar(200) DEFAULT NULL");
			}

			/* Create hostel_room table */
			$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_ROOMS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				hostel_id bigint(20) UNSIGNED DEFAULT NULL,
				room_name varchar(200) DEFAULT NULL,
				number_of_beds varchar(200) DEFAULT NULL,
				note text DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				FOREIGN KEY (hostel_id) REFERENCES " . WLSM_HOSTELS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create study_materials table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_STUDY_MATERIALS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				label varchar(100) DEFAULT NULL,
				description text DEFAULT NULL,
				attachments text DEFAULT NULL,
				added_by bigint(20) UNSIGNED DEFAULT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				FOREIGN KEY (added_by) REFERENCES " . WLSM_USERS . " (ID) ON DELETE SET NULL,
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Add url column if not exists to fees table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_STUDY_MATERIALS . "' AND COLUMN_NAME = 'url'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_STUDY_MATERIALS . " ADD url text DEFAULT NULL");
		}

		/* Add downloadable column if not exists to fees table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_STUDY_MATERIALS . "' AND COLUMN_NAME = 'downloadable'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_STUDY_MATERIALS . " ADD downloadable bigint(20) UNSIGNED DEFAULT NULL");
		}

		/* Create class_school_study_material table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_CLASS_SCHOOL_STUDY_MATERIAL . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				class_school_id bigint(20) UNSIGNED DEFAULT NULL,
				study_material_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (class_school_id, study_material_id),
				INDEX (class_school_id),
				INDEX (study_material_id),
				FOREIGN KEY (class_school_id) REFERENCES " . WLSM_CLASS_SCHOOL . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (study_material_id) REFERENCES " . WLSM_STUDY_MATERIALS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Add study_material_section_id, study_material_subject_id column if not exists to fees table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_CLASS_SCHOOL_STUDY_MATERIAL . "' AND COLUMN_NAME = 'study_material_section_id'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_CLASS_SCHOOL_STUDY_MATERIAL . " ADD study_material_section_id bigint(20) UNSIGNED DEFAULT NULL");
			$wpdb->query("ALTER TABLE " . WLSM_CLASS_SCHOOL_STUDY_MATERIAL . " ADD study_material_subject_id bigint(20) UNSIGNED DEFAULT NULL");
		}

		/* Create homework table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_HOMEWORK . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				title varchar(255) DEFAULT NULL,
				description text DEFAULT NULL,
				attachments text DEFAULT NULL,
				homework_date date NULL DEFAULT NULL,
				added_by bigint(20) UNSIGNED DEFAULT NULL,
				session_id bigint(20) UNSIGNED DEFAULT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (school_id),
				FOREIGN KEY (added_by) REFERENCES " . WLSM_USERS . " (ID) ON DELETE SET NULL,
				FOREIGN KEY (session_id) REFERENCES " . WLSM_SESSIONS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Add downloadable column if not exists to WLSM_HOMEWORK table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_HOMEWORK . "' AND COLUMN_NAME = 'downloadable'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_HOMEWORK . " ADD downloadable text DEFAULT NULL");
		}

		/* Add url column if not exists to fees table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_HOMEWORK . "' AND COLUMN_NAME = 'attachments'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_HOMEWORK . " ADD attachments text DEFAULT NULL");
		}
		/* Add subject column if not exists to fees table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_HOMEWORK . "' AND COLUMN_NAME = 'subject'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_HOMEWORK . " ADD subject varchar(255) DEFAULT NULL");
		}

		/* Create homework_section table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_HOMEWORK_SECTION . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				homework_id bigint(20) UNSIGNED DEFAULT NULL,
				section_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (homework_id, section_id),
				INDEX (homework_id),
				INDEX (section_id),
				FOREIGN KEY (homework_id) REFERENCES " . WLSM_HOMEWORK . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (section_id) REFERENCES " . WLSM_SECTIONS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);


		/* Create homework_submission table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_HOMEWORK_SUBMISSION . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				description text DEFAULT NULL,
				attachments text DEFAULT NULL,
				student_id bigint(20) UNSIGNED DEFAULT NULL,
				session_id bigint(20) UNSIGNED DEFAULT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				submission_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (school_id),
				FOREIGN KEY (student_id)     REFERENCES " . WLSM_STUDENT_RECORDS  . " (ID) ON DELETE SET NULL,
				FOREIGN KEY (session_id)     REFERENCES " . WLSM_SESSIONS         . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (submission_id)     REFERENCES " . WLSM_HOMEWORK      . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (school_id)      REFERENCES " . WLSM_SCHOOLS          . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create admin_subject table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_ADMIN_SUBJECT . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				admin_id bigint(20) UNSIGNED DEFAULT NULL,
				subject_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (admin_id, subject_id),
				INDEX (admin_id),
				INDEX (subject_id),
				FOREIGN KEY (admin_id) REFERENCES " . WLSM_ADMINS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (subject_id) REFERENCES " . WLSM_SUBJECTS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create fees table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_FEES . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				label varchar(100) DEFAULT NULL,
				amount decimal(12,2) UNSIGNED DEFAULT '0.00',
				period varchar(30) DEFAULT 'one-time',
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (school_id),
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Add active_on_admission column if not exists to fees table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_FEES . "' AND COLUMN_NAME = 'active_on_admission'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_FEES . " ADD active_on_admission tinyint(1) NOT NULL DEFAULT '1'");
		}
		/* Add active_on_dashboard column if not exists to fees table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_FEES . "' AND COLUMN_NAME = 'active_on_dashboard'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_FEES . " ADD active_on_dashboard tinyint(1) NOT NULL DEFAULT '0'");
		}

	// 	/* Create WLSM_STUDENT_ASSIGNED_FEES table */
	// 	$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_STUDENT_ASSIGNED_FEES . " (
	// 		ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	// 		student_record_id bigint(20) UNSIGNED DEFAULT NULL,
	// 		fee_type_id bigint(20) UNSIGNED DEFAULT NULL,
	// 		PRIMARY KEY (ID),
	// 		FOREIGN KEY (student_record_id) REFERENCES " . WLSM_STUDENT_RECORDS . " (ID) ON DELETE CASCADE
	// 		) ENGINE=InnoDB " . $charset_collate;
	// dbDelta($sql);

		/* Create student_fees table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_STUDENT_FEES . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				student_record_id bigint(20) UNSIGNED DEFAULT NULL,
				label varchar(100) DEFAULT NULL,
				amount decimal(12,2) UNSIGNED DEFAULT '0.00',
				period varchar(30) DEFAULT 'one-time',
				fee_order smallint(4) UNSIGNED DEFAULT '10',
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (student_record_id),
				FOREIGN KEY (student_record_id) REFERENCES " . WLSM_STUDENT_RECORDS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Add class_id column if not exists to fees table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_FEES . "' AND COLUMN_NAME = 'class_id'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_FEES . " ADD class_id varchar(60) NULL DEFAULT NULL");
		}

		/* Create routines table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_ROUTINES . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				start_time time DEFAULT NULL,
				end_time time DEFAULT NULL,
				room_number varchar(40) DEFAULT NULL,
				day tinyint(1) DEFAULT NULL,
				subject_id bigint(20) UNSIGNED DEFAULT NULL,
				section_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (subject_id),
				INDEX (section_id),
				FOREIGN KEY (subject_id) REFERENCES " . WLSM_SUBJECTS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (section_id) REFERENCES " . WLSM_SECTIONS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Add admin_id column if not exists to routines table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_ROUTINES . "' AND COLUMN_NAME = 'admin_id'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_ROUTINES . " ADD admin_id bigint(20) UNSIGNED DEFAULT NULL");
			$wpdb->query("CREATE INDEX admin_id ON " . WLSM_ROUTINES . " (admin_id)");
			$wpdb->query("ALTER TABLE " . WLSM_ROUTINES . " ADD FOREIGN KEY (admin_id) REFERENCES " . WLSM_ADMINS . " (ID) ON DELETE SET NULL");
		}

		/* Add enrollment_prefix, enrollment_base, enrollment_padding column if not exists to schools table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_SCHOOLS . "' AND COLUMN_NAME = 'enrollment_prefix'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_SCHOOLS . " ADD enrollment_prefix varchar(15) DEFAULT ''");
			$wpdb->query("ALTER TABLE " . WLSM_SCHOOLS . " ADD enrollment_base int(11) UNSIGNED DEFAULT '0'");
			$wpdb->query("ALTER TABLE " . WLSM_SCHOOLS . " ADD enrollment_padding smallint(4) UNSIGNED DEFAULT '6'");
		}

		/* Create books table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_BOOKS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				title varchar(100) DEFAULT NULL,
				author varchar(60) DEFAULT NULL,
				subject varchar(100) DEFAULT NULL,
				description text DEFAULT NULL,
				rack_number varchar(40) DEFAULT NULL,
				book_number varchar(100) DEFAULT NULL,
				isbn_number varchar(100) DEFAULT NULL,
				price decimal(12,2) UNSIGNED DEFAULT NULL,
				quantity smallint(4) UNSIGNED DEFAULT '0',
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (school_id),
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create books_issued table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_BOOKS_ISSUED . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				book_id bigint(20) UNSIGNED DEFAULT NULL,
				student_record_id bigint(20) UNSIGNED DEFAULT NULL,
				quantity smallint(4) UNSIGNED DEFAULT '1',
				date_issued date NULL DEFAULT NULL,
				return_date date NULL DEFAULT NULL,
				returned_at timestamp NULL DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (book_id),
				INDEX (student_record_id),
				FOREIGN KEY (book_id) REFERENCES " . WLSM_BOOKS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (student_record_id) REFERENCES " . WLSM_STUDENT_RECORDS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create library_cards table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_LIBRARY_CARDS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				card_number varchar(60) DEFAULT NULL,
				date_issued date NULL DEFAULT NULL,
				student_record_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (student_record_id),
				INDEX (student_record_id),
				FOREIGN KEY (student_record_id) REFERENCES " . WLSM_STUDENT_RECORDS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create vehicles table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_VEHICLES . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				vehicle_number varchar(60) DEFAULT NULL,
				vehicle_model varchar(60) DEFAULT NULL,
				driver_name varchar(60) DEFAULT NULL,
				driver_phone varchar(40) DEFAULT NULL,
				note text DEFAULT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (school_id),
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create routes table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_ROUTES . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				name varchar(100) DEFAULT NULL,
				period varchar(100) DEFAULT NULL,
				fare decimal(12,2) UNSIGNED DEFAULT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (school_id),
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_ROUTES . "' AND COLUMN_NAME = 'period'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_ROUTES . " ADD period varchar(200) DEFAULT NULL");
		}

		/* Create route_vehicle table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_ROUTE_VEHICLE . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				route_id bigint(20) UNSIGNED DEFAULT NULL,
				vehicle_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (route_id, vehicle_id),
				INDEX (route_id),
				INDEX (vehicle_id),
				FOREIGN KEY (vehicle_id) REFERENCES " . WLSM_VEHICLES . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (route_id) REFERENCES " . WLSM_ROUTES . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Add route_vehicle_id column if not exists to student_records table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_STUDENT_RECORDS . "' AND COLUMN_NAME = 'route_vehicle_id'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD route_vehicle_id bigint(20) UNSIGNED DEFAULT NULL");
			$wpdb->query("CREATE INDEX route_vehicle_id ON " . WLSM_STUDENT_RECORDS . " (route_vehicle_id)");
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD FOREIGN KEY (route_vehicle_id) REFERENCES " . WLSM_ROUTE_VEHICLE . " (ID) ON DELETE SET NULL");
		}

		/* Add room_id column if not exists to student_records table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_STUDENT_RECORDS . "' AND COLUMN_NAME = 'room_id'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD room_id bigint(20) UNSIGNED DEFAULT NULL");

		}

		/* Create logs table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_LOGS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				log_key text NOT NULL,
				log_value text NOT NULL,
				log_group text NOT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (ID),
				INDEX (school_id),
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Add added_by column if not exists to student_records, invoices, payments, attendance, staff_attendance, expenses, income tables */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_STUDENT_RECORDS . "' AND COLUMN_NAME = 'added_by'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD added_by bigint(20) UNSIGNED DEFAULT NULL");
			$wpdb->query("CREATE INDEX added_by ON " . WLSM_STUDENT_RECORDS . " (added_by)");
			$wpdb->query("ALTER TABLE " . WLSM_STUDENT_RECORDS . " ADD FOREIGN KEY (added_by) REFERENCES " . WLSM_USERS . " (ID) ON DELETE SET NULL");

			$wpdb->query("ALTER TABLE " . WLSM_INVOICES . " ADD added_by bigint(20) UNSIGNED DEFAULT NULL");
			$wpdb->query("CREATE INDEX added_by ON " . WLSM_INVOICES . " (added_by)");
			$wpdb->query("ALTER TABLE " . WLSM_INVOICES . " ADD FOREIGN KEY (added_by) REFERENCES " . WLSM_USERS . " (ID) ON DELETE SET NULL");

			$wpdb->query("ALTER TABLE " . WLSM_PAYMENTS . " ADD added_by bigint(20) UNSIGNED DEFAULT NULL");
			$wpdb->query("CREATE INDEX added_by ON " . WLSM_PAYMENTS . " (added_by)");
			$wpdb->query("ALTER TABLE " . WLSM_PAYMENTS . " ADD FOREIGN KEY (added_by) REFERENCES " . WLSM_USERS . " (ID) ON DELETE SET NULL");

			$wpdb->query("ALTER TABLE " . WLSM_ATTENDANCE . " ADD added_by bigint(20) UNSIGNED DEFAULT NULL");
			$wpdb->query("CREATE INDEX added_by ON " . WLSM_ATTENDANCE . " (added_by)");
			$wpdb->query("ALTER TABLE " . WLSM_ATTENDANCE . " ADD FOREIGN KEY (added_by) REFERENCES " . WLSM_USERS . " (ID) ON DELETE SET NULL");

			$wpdb->query("ALTER TABLE " . WLSM_STAFF_ATTENDANCE . " ADD added_by bigint(20) UNSIGNED DEFAULT NULL");
			$wpdb->query("CREATE INDEX added_by ON " . WLSM_STAFF_ATTENDANCE . " (added_by)");
			$wpdb->query("ALTER TABLE " . WLSM_STAFF_ATTENDANCE . " ADD FOREIGN KEY (added_by) REFERENCES " . WLSM_USERS . " (ID) ON DELETE SET NULL");

			$wpdb->query("ALTER TABLE " . WLSM_EXPENSES . " ADD added_by bigint(20) UNSIGNED DEFAULT NULL");
			$wpdb->query("CREATE INDEX added_by ON " . WLSM_EXPENSES . " (added_by)");
			$wpdb->query("ALTER TABLE " . WLSM_EXPENSES . " ADD FOREIGN KEY (added_by) REFERENCES " . WLSM_USERS . " (ID) ON DELETE SET NULL");

			$wpdb->query("ALTER TABLE " . WLSM_INCOME . " ADD added_by bigint(20) UNSIGNED DEFAULT NULL");
			$wpdb->query("CREATE INDEX added_by ON " . WLSM_INCOME . " (added_by)");
			$wpdb->query("ALTER TABLE " . WLSM_INCOME . " ADD FOREIGN KEY (added_by) REFERENCES " . WLSM_USERS . " (ID) ON DELETE SET NULL");
		}

		/* Add section_id, vehicle_id columns if not exists to admins table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_ADMINS . "' AND COLUMN_NAME = 'section_id'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_ADMINS . " ADD section_id bigint(20) UNSIGNED DEFAULT NULL");
			$wpdb->query("CREATE INDEX section_id ON " . WLSM_ADMINS . " (section_id)");
			$wpdb->query("ALTER TABLE " . WLSM_ADMINS . " ADD FOREIGN KEY (section_id) REFERENCES " . WLSM_SECTIONS . " (ID) ON DELETE SET NULL");

			$wpdb->query("ALTER TABLE " . WLSM_ADMINS . " ADD vehicle_id bigint(20) UNSIGNED DEFAULT NULL");
			$wpdb->query("CREATE INDEX vehicle_id ON " . WLSM_ADMINS . " (vehicle_id)");
			$wpdb->query("ALTER TABLE " . WLSM_ADMINS . " ADD FOREIGN KEY (vehicle_id) REFERENCES " . WLSM_VEHICLES . " (ID) ON DELETE SET NULL");
		}

		/* Create leaves table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_LEAVES . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				description text DEFAULT NULL,
				start_date date NULL DEFAULT NULL,
				end_date date NULL DEFAULT NULL,
				is_approved tinyint(1) NOT NULL DEFAULT '0',
				student_record_id bigint(20) UNSIGNED DEFAULT NULL,
				admin_id bigint(20) UNSIGNED DEFAULT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				approved_by bigint(20) UNSIGNED DEFAULT NULL,
				added_by bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (student_record_id),
				INDEX (admin_id),
				INDEX (school_id),
				INDEX (added_by),
				FOREIGN KEY (student_record_id) REFERENCES " . WLSM_STUDENT_RECORDS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (admin_id) REFERENCES " . WLSM_ADMINS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (approved_by) REFERENCES " . WLSM_USERS . " (ID) ON DELETE SET NULL,
				FOREIGN KEY (added_by) REFERENCES " . WLSM_USERS . " (ID) ON DELETE SET NULL
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

			/* Create activities table */
			$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_ACTIVITIES . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				title text DEFAULT NULL,
				fees text DEFAULT NULL,
				description text DEFAULT NULL,
				is_approved tinyint(1) NOT NULL DEFAULT '0',
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				class_id bigint(20) UNSIGNED DEFAULT NULL,
				added_by bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (school_id),
				INDEX (added_by),
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (added_by) REFERENCES " . WLSM_USERS . " (ID) ON DELETE SET NULL
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create events table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_EVENTS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				title text DEFAULT NULL,
				description text DEFAULT NULL,
				image_id bigint(20) UNSIGNED DEFAULT NULL,
				event_date date NULL DEFAULT NULL,
				is_active tinyint(1) NOT NULL DEFAULT '1',
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				added_by bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (school_id),
				INDEX (added_by),
				FOREIGN KEY (image_id) REFERENCES " . WLSM_POSTS . " (ID) ON DELETE SET NULL,
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (added_by) REFERENCES " . WLSM_USERS . " (ID) ON DELETE SET NULL
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create event_responses table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_EVENT_RESPONSES . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				event_id bigint(20) UNSIGNED DEFAULT NULL,
				student_record_id bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				UNIQUE (event_id, student_record_id),
				INDEX (event_id),
				INDEX (student_record_id),
				FOREIGN KEY (event_id) REFERENCES " . WLSM_EVENTS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (student_record_id) REFERENCES " . WLSM_STUDENT_RECORDS . " (ID) ON DELETE CASCADE
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Chapter Table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_CHAPTER . " (
			ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			title varchar(191) DEFAULT NULL,
			class_id bigint(20) UNSIGNED DEFAULT NULL,
			subject_id bigint(20) UNSIGNED DEFAULT NULL,
			created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at timestamp NULL DEFAULT NULL,
			PRIMARY KEY (ID)
			) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_LECTURE . " (
			ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			title varchar(191) DEFAULT NULL,
			description text DEFAULT NULL,
			attachment text DEFAULT NULL,
			link_to text DEFAULT NULL,
			url text DEFAULT NULL,
			class_id bigint(20) DEFAULT NULL,
			chapter_id bigint(20) DEFAULT NULL,
			section_id bigint(20) DEFAULT NULL,
			subject_id bigint(20) DEFAULT NULL,
			created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at timestamp NULL DEFAULT NULL,
			PRIMARY KEY (ID)
			) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/** Ratting */

		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_RATTING . " (
			ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			`message` text DEFAULT NULL,
			student_id bigint(20) UNSIGNED DEFAULT NULL,
			ratting varchar(200) DEFAULT NULL,
			added_by varchar(200) DEFAULT NULL,
			created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at timestamp NULL DEFAULT NULL,
			PRIMARY KEY (ID)
			) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);

		/* Create meetings table */
		$sql = "CREATE TABLE IF NOT EXISTS " . WLSM_MEETINGS . " (
				ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				host varchar(191) DEFAULT NULL,
				host_id text DEFAULT NULL,
				alternative_hosts text DEFAULT NULL,
				meeting_id varchar(191) DEFAULT NULL,
				topic text DEFAULT NULL,
				agenda text DEFAULT NULL,
				duration smallint(4) UNSIGNED DEFAULT NULL,
				start_at timestamp NULL DEFAULT NULL,
				type smallint(6) UNSIGNED DEFAULT NULL,
				recurrence_type smallint(6) UNSIGNED DEFAULT NULL,
				repeat_interval smallint(6) UNSIGNED DEFAULT NULL,
				weekly_days varchar(255) DEFAULT NULL,
				monthly_day smallint(4) UNSIGNED DEFAULT NULL,
				end_times smallint(6) UNSIGNED DEFAULT NULL,
				end_at timestamp NULL DEFAULT NULL,
				approval_type smallint(6) UNSIGNED DEFAULT NULL,
				registration_type smallint(6) UNSIGNED DEFAULT NULL,
				password varchar(255) DEFAULT NULL,
				join_before_host tinyint(1) NOT NULL DEFAULT '1',
				host_video tinyint(1) NOT NULL DEFAULT '0',
				participant_video tinyint(1) NOT NULL DEFAULT '0',
				mute_upon_entry tinyint(1) NOT NULL DEFAULT '0',
				start_url text DEFAULT NULL,
				join_url text DEFAULT NULL,
				class_school_id bigint(20) UNSIGNED DEFAULT NULL,
				admin_id bigint(20) UNSIGNED DEFAULT NULL,
				subject_id bigint(20) UNSIGNED DEFAULT NULL,
				school_id bigint(20) UNSIGNED DEFAULT NULL,
				added_by bigint(20) UNSIGNED DEFAULT NULL,
				created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at timestamp NULL DEFAULT NULL,
				PRIMARY KEY (ID),
				INDEX (class_school_id),
				INDEX (admin_id),
				INDEX (subject_id),
				INDEX (school_id),
				INDEX (added_by),
				FOREIGN KEY (class_school_id) REFERENCES " . WLSM_CLASS_SCHOOL . " (ID) ON DELETE SET NULL,
				FOREIGN KEY (admin_id) REFERENCES " . WLSM_ADMINS . " (ID) ON DELETE SET NULL,
				FOREIGN KEY (subject_id) REFERENCES " . WLSM_SUBJECTS . " (ID) ON DELETE SET NULL,
				FOREIGN KEY (school_id) REFERENCES " . WLSM_SCHOOLS . " (ID) ON DELETE CASCADE,
				FOREIGN KEY (added_by) REFERENCES " . WLSM_USERS . " (ID) ON DELETE SET NULL
				) ENGINE=InnoDB " . $charset_collate;
		dbDelta($sql);



		/* Add moderator_code column if not exists to student_records table */
		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME = '" . WLSM_MEETINGS . "' AND COLUMN_NAME = 'moderator_code'");
		if (empty($row)) {
			$wpdb->query("ALTER TABLE " . WLSM_MEETINGS . " ADD moderator_code varchar(200) DEFAULT NULL");
			$wpdb->query("ALTER TABLE " . WLSM_MEETINGS . " ADD recordable tinyint(1) NOT NULL DEFAULT '0'");
			$wpdb->query("ALTER TABLE " . WLSM_MEETINGS . " ADD class_type varchar(90) DEFAULT NULL");
			$wpdb->query("ALTER TABLE " . WLSM_MEETINGS . " ADD section_id smallint(6) UNSIGNED DEFAULT NULL");
		}

		self::set_default_options($session_id);

		// Set default school for super admin.
		if (isset($default_school_id)) {
			$user_id = get_current_user_id();

			// Data to update or insert.
			$data = array(
				'school_id' => $default_school_id,
				'user_id'   => $user_id,
				'role'      => WLSM_M_Role::get_admin_key(),
			);

			$data['created_at'] = current_time('Y-m-d H:i:s');

			$wpdb->insert(WLSM_STAFF, $data);

			update_user_meta($user_id, 'wlsm_school_id', $default_school_id);
		}
	}

	public static function deactivation()
	{
		delete_option('wlsm-key');
		delete_option('wlsm-valid');
		delete_option('wlsm-code');
		delete_option('wlsm-cache');
		delete_option('wlsm-updation-detail');
	}

	public static function uninstall()
	{
		delete_option('wlsm-key');
		// delete_option('wlsm-valid');
		delete_option('wlsm-cache');
		delete_option('wlsm-updation-detail');
		if (get_option('wlsm_delete_on_uninstall')) {
			// Drop all tables and delete options.
			self::remove_data();
		}
	}

	private static function insert_default_school()
	{
		global $wpdb;

		$default_school_data = array(
			'label' => esc_html__('Default School', 'school-management'),
		);

		$default_school_data['created_at'] = current_time('Y-m-d H:i:s');

		$wpdb->insert(WLSM_SCHOOLS, $default_school_data);

		$default_school_id = $wpdb->insert_id;

		return $default_school_id;
	}

	private static function insert_default_subjects() {
		global $wpdb;

		// Get all students
		$students = $wpdb->get_results("SELECT ID FROM " . WLSM_STUDENT_RECORDS);

		foreach ($students as $student) {
			$student_id = $student->ID;
			$subjects = self::get_student_class_subjects($student_id);

			// Assign subjects to the student
			if (!empty($subjects)) {
				foreach ($subjects as $subject) {
					$subject_id = $subject->ID;

					// Insert the student-subject relationship into WLSM_STUDENTS_SUBJECTS table
					$wpdb->insert( WLSM_STUDENTS_SUBJECTS, array(
							'student_id' => $student_id,
							'subject_id' => $subject_id
						),
						array('%d', '%d')
					);
				}
			}
		}
	}

	public static function get_student_class_subjects($student_id) {
		global $wpdb;

		// Get the class ID of the student ID
		$class_id = $wpdb->get_var($wpdb->prepare('SELECT cs.class_id FROM ' . WLSM_STUDENT_RECORDS . ' as sr
		JOIN '. WLSM_SECTIONS .' as ss ON sr.section_id = ss.ID
		JOIN '. WLSM_CLASS_SCHOOL .' as cs ON cs.ID = ss.class_school_id
		WHERE sr.ID = %d', $student_id));

		// Get all subjects for the class
		$subjects = $wpdb->get_results($wpdb->prepare('SELECT sj.ID, sj.label, sj.code, sj.type FROM ' . WLSM_SUBJECTS . ' as sj
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = sj.class_school_id
			WHERE cs.class_id = %d', $class_id));

		return $subjects;
	}

	public static function insert_default_subject_types(){
		global $wpdb;
		$wpdb->insert(WLSM_SUBJECT_TYPES, array('label' => 'Theory'));
		$wpdb->insert(WLSM_SUBJECT_TYPES, array('label' => 'Practical'));
		$wpdb->insert(WLSM_SUBJECT_TYPES, array('label' => 'Subjective'));
		$wpdb->insert(WLSM_SUBJECT_TYPES, array('label' => 'Objective'));
	}

	private static function insert_default_classes()
	{
		global $wpdb;

		$sql = "INSERT INTO `" . WLSM_CLASSES . "` (`label`) VALUES ('1st'),('2nd'),('3rd'),('4th'),('5th'),('6th'),('7th'),('8th'),('9th'),('10th'),('11th'),('12th');";
		$wpdb->query($sql);
	}

	private static function insert_default_category(){
		global $wpdb;
		$wpdb->insert(WLSM_CATEGORY, array('label' => 'central'));
		$wpdb->insert(WLSM_CATEGORY, array('label' => 'state'));
		$wpdb->insert(WLSM_CATEGORY, array('label' => 'private'));
	}

	public static function insert_default_medium(){
		global $wpdb;
		$wpdb->insert(WLSM_MEDIUM, array('label' => 'English', 'school_id' => 1));
		$wpdb->insert(WLSM_MEDIUM, array('label' => 'Hindi', 'school_id' => 1));
	}

	public static function insert_default_student_type(){
		global $wpdb;
		$wpdb->insert(WLSM_STUDENT_TYPE, array('label' => 'Regular'));
		$wpdb->insert(WLSM_STUDENT_TYPE, array('label' => 'Private'));
		$wpdb->insert(WLSM_STUDENT_TYPE, array('label' => 'Other'));
	}

	public static function set_default_options($session_id = NULL)
	{
		$current_session = get_option('wlsm_current_session');
		if (!$current_session && $session_id) {
			add_option('wlsm_current_session', $session_id);
		}

		$currency = get_option('wlsm_currency');
		if (!$currency) {
			add_option('wlsm_currency', WLSM_Config::get_default_currency());
		}

		$date_format = get_option('wlsm_date_format');
		if (!$date_format) {
			add_option('wlsm_date_format', WLSM_Config::get_default_date_format());
		}
	}

	public static function remove_data()
	{
		global $wpdb;

		$wpdb->query('SET FOREIGN_KEY_CHECKS=0');
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_MEETINGS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_EVENT_RESPONSES);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_EVENTS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_LEAVES);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_LOGS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_LIBRARY_CARDS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_BOOKS_ISSUED);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_BOOKS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_ROUTINES);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_STUDENT_FEES);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_FEES);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_ADMIN_SUBJECT);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_HOMEWORK_SECTION);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_HOMEWORK);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_CLASS_SCHOOL_STUDY_MATERIAL);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_STUDY_MATERIALS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_CLASS_SCHOOL_NOTICE);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_NOTICES);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_EXAM_RESULTS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_ADMIT_CARDS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_EXAM_PAPERS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_CLASS_SCHOOL_EXAM);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_EXAMS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_SUBJECTS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_STAFF_ATTENDANCE);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_ATTENDANCE);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_INCOME);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_INCOME_CATEGORIES);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_EXPENSES);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_EXPENSE_CATEGORIES);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_PENDING_PAYMENTS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_PAYMENTS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_INVOICES);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_CERTIFICATE_STUDENT);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_CERTIFICATES);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_TRANSFERS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_PROMOTIONS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_STUDENT_RECORDS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_ADMINS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_ROUTE_VEHICLE);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_ROUTES);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_VEHICLES);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_SECTIONS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_STAFF);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_ROLES);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_INQUIRIES);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_SESSIONS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_CLASS_SCHOOL);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_CLASSES);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_SETTINGS);
		$wpdb->query('DROP TABLE IF EXISTS ' . WLSM_SCHOOLS);
		$wpdb->query('SET FOREIGN_KEY_CHECKS=1');

		delete_metadata('user', 0, 'wlsm_school_id', '', true);
		delete_metadata('user', 0, 'wlsm_current_session', '', true);

		delete_option('wlsm_current_session');
		delete_option('wlsm_date_format');
		delete_option('wlsm_currency');
		delete_option('wlsm_gdpr_enable');
		delete_option('wlsm_gdpr_text_inquiry');
		delete_option('wlsm_gdpr_text_registration');

		delete_option('wlsm_delete_on_uninstall');
	}
}
