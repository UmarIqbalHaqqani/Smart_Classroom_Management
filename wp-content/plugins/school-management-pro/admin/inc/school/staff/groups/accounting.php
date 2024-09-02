<?php
defined('ABSPATH') || die();

require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/global.php';

$page_url_invoices = admin_url('admin.php?page=' . WLSM_MENU_STAFF_INVOICES);
$page_url_fees     = admin_url('admin.php?page=' . WLSM_MENU_STAFF_FEES);
$page_url_expenses = admin_url('admin.php?page=' . WLSM_MENU_STAFF_EXPENSES);
$page_url_income   = admin_url('admin.php?page=' . WLSM_MENU_STAFF_INCOME);

		global $wpdb;

		$school_id  = $current_school['id'];
		$session_id = $current_session['ID'];

		$schools_page_url  = WLSM_M_School::get_page_url();
		$students_page_url = WLSM_M_Staff_General::get_students_page_url();
		$invoices_page_url = WLSM_M_Staff_Accountant::get_invoices_page_url();

		$session_start_date = new DateTime($current_session['start_date']);
		$session_end_date   = new DateTime($current_session['end_date']);
		$session_start_date = $session_start_date->format('Y-m-d');
		$session_end_date   = $session_end_date->format('Y-m-d');

		if (WLSM_M_Role::check_permission(array('manage_classes'), $current_school['permissions'])) {
			// Total Classes.
			$total_classes_count  = $wpdb->get_var(WLSM_M_Staff_Class::fetch_classes_query_count($school_id));

			// Total Sections.
			$total_sections_count = $wpdb->get_var($wpdb->prepare('SELECT COUNT(DISTINCT se.ID) FROM ' . WLSM_SECTIONS . ' as se JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id WHERE cs.school_id = %d', $school_id));
		}

		if (WLSM_M_Role::check_permission(array('manage_students'), $current_school['permissions'])) {
			// Total Students.
			$total_students_count = $wpdb->get_var(
				$wpdb->prepare('SELECT COUNT(DISTINCT sr.ID) FROM ' . WLSM_STUDENT_RECORDS . ' as sr 
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
			WHERE ss.ID = %d AND cs.school_id = %d', $session_id, $school_id)
			);

			// Students Active.
			$active_students_count = $wpdb->get_var(
				$wpdb->prepare('SELECT COUNT(DISTINCT sr.ID) FROM ' . WLSM_STUDENT_RECORDS . ' as sr 
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
			WHERE ss.ID = %d AND cs.school_id = %d AND sr.is_active = 1', $session_id, $school_id)
			);
		}

		if (WLSM_M_Role::check_permission(array('manage_admins'), $current_school['permissions'])) {
			// Total Admins.
			$total_admins_count  = $wpdb->get_var(WLSM_M_Staff_General::fetch_staff_query_count($school_id, WLSM_M_Role::get_admin_key()));
		}

		if (WLSM_M_Role::check_permission(array('manage_roles'), $current_school['permissions'])) {
			// Total Roles.
			$total_roles_count = $wpdb->get_var(WLSM_M_Staff_General::fetch_role_query_count($school_id));
		}

		if (WLSM_M_Role::check_permission(array('manage_employees'), $current_school['permissions'])) {
			// Total Staff.
			$total_staff_count = $wpdb->get_var(
				$wpdb->prepare('SELECT COUNT(DISTINCT a.ID) FROM ' . WLSM_ADMINS . ' as a 
			JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id 
			WHERE sf.role = "%s" AND sf.school_id = %d', WLSM_M_Role::get_employee_key(), $school_id)
			);

			// Staff Active.
			$active_staff_count = $wpdb->get_var(
				$wpdb->prepare('SELECT COUNT(DISTINCT a.ID) FROM ' . WLSM_ADMINS . ' as a 
			JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id 
			WHERE sf.role = "%s" AND sf.school_id = %d AND a.is_active = 1', WLSM_M_Role::get_employee_key(), $school_id)
			);
		}

		if (WLSM_M_Role::check_permission(array('manage_admissions'), $current_school['permissions'])) {
			// Last 15 Admissions
			$admissions = $wpdb->get_results(
				$wpdb->prepare('SELECT sr.ID, sr.name as student_name, sr.enrollment_number, sr.admission_number, sr.admission_date, c.label as class_label, se.label as section_label FROM ' . WLSM_STUDENT_RECORDS . ' as sr 
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
		WHERE cs.school_id = %d AND ss.ID = %d GROUP BY sr.ID ORDER BY sr.admission_date DESC LIMIT 15', $school_id, $session_id)
			);
		}

		if (WLSM_M_Role::check_permission(array('manage_invoices'), $current_school['permissions'])) {
			// Total Invoices.
			$total_invoices_count = $wpdb->get_var(
				$wpdb->prepare('SELECT COUNT(DISTINCT i.ID) FROM ' . WLSM_INVOICES . ' as i 
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id 
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
			WHERE cs.school_id = %d AND ss.ID = %d', $school_id, $session_id)
			);

			// Paid Invoices.
			$invoices_paid_count = $wpdb->get_var(
				$wpdb->prepare('SELECT COUNT(DISTINCT i.ID) FROM ' . WLSM_INVOICES . ' as i 
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id 
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
			WHERE cs.school_id = %d AND ss.ID = %d AND i.status = "%s"', $school_id, $session_id, WLSM_M_Invoice::get_paid_key())
			);

			// Unpaid Invoices.
			$invoices_unpaid_count = $wpdb->get_var(
				$wpdb->prepare('SELECT COUNT(DISTINCT i.ID) FROM ' . WLSM_INVOICES . ' as i 
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id 
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
			WHERE cs.school_id = %d AND ss.ID = %d AND i.status = "%s"', $school_id, $session_id, WLSM_M_Invoice::get_unpaid_key())
			);

			// Invoices Partially Paid.
			$invoices_partially_paid_count = $wpdb->get_var(
				$wpdb->prepare('SELECT COUNT(DISTINCT i.ID) FROM ' . WLSM_INVOICES . ' as i 
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id 
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
			WHERE cs.school_id = %d AND ss.ID = %d AND i.status = "%s"', $school_id, $session_id, WLSM_M_Invoice::get_partially_paid_key())
			);
		}

		// Invoices pending amount.
		$invoices_pending_amount = $wpdb->get_col(
			$wpdb->prepare( 'SELECT ((i.amount) - COALESCE(SUM(p.amount), 0)) as due FROM ' . WLSM_INVOICES . ' as i 
				JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id 
				JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
				JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
				JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
				JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
				LEFT OUTER JOIN ' . WLSM_PAYMENTS . ' as p ON p.invoice_id = i.ID 
				WHERE cs.school_id = %d AND ss.ID = %d AND (i.status = "%s" OR i.status = "%s") GROUP BY i.ID', $school_id, $session_id, WLSM_M_Invoice::get_unpaid_key(), WLSM_M_Invoice::get_partially_paid_key() )
		);

		$invoices_pending_amount = array_sum( $invoices_pending_amount );

		if (WLSM_M_Role::check_permission(array('stats_payments'), $current_school['permissions'])) {
			// Total Payments.
			$total_payments_count = $wpdb->get_var(WLSM_M_Staff_Accountant::fetch_payments_query_count($school_id, $session_id));

			// Total Payment Received.
			$total_payment_received = WLSM_M_Staff_Accountant::get_total_payments_received($school_id, $session_id);
		}

		if (WLSM_M_Role::check_permission(array('stats_amount_fees_structure'), $current_school['permissions'])) {
			// Total Fees Structure Amount.
			$total_fees_structure_amount = WLSM_M_Staff_Accountant::get_total_fees_structure_amount($school_id, $session_id);
		}

		if (WLSM_M_Role::check_permission(array('stats_expense'), $current_school['permissions'])) {
			// Total Expenses - Sum.
			$total_expenses_sum = $wpdb->get_var($wpdb->prepare('SELECT COALESCE(SUM(ep.amount), 0) as sum FROM ' . WLSM_EXPENSES . ' as ep WHERE ep.school_id = %d AND ep.expense_date BETWEEN %s AND %s', $school_id, $session_start_date, $session_end_date));
		}

		if (WLSM_M_Role::check_permission(array('stats_income'), $current_school['permissions'])) {
			// Total Income - Sum.
			$total_income_sum = $wpdb->get_var($wpdb->prepare('SELECT COALESCE(SUM(im.amount), 0) as sum FROM ' . WLSM_INCOME . ' as im WHERE im.school_id = %d AND im.income_date BETWEEN %s AND %s', $school_id, $session_start_date, $session_end_date));
		}

		$date_now        = date('Y-m-d');
		if (WLSM_M_Role::check_permission(array('stats_income'), $current_school['permissions'])) {
			// Total Income - Sum.
			$total_dailypaymentstotal_sum = $wpdb->get_var($wpdb->prepare('SELECT COALESCE(SUM(im.amount), 0) as sum FROM ' . WLSM_PAYMENTS . ' as im 
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = im.student_record_id 
			WHERE im.school_id = %d AND sr.session_id=%d AND im.created_at BETWEEN %s AND %s', $school_id, $session_id, $date_now, $date_now));
		}

		if (WLSM_M_Role::check_permission(array('stats_income'), $current_school['permissions'])) {
			// Total Income - Sum.
			$total_dailyexpencestotal_sum = $wpdb->get_var($wpdb->prepare('SELECT COALESCE(SUM(we.amount), 0) as sum FROM ' . WLSM_EXPENSES . ' as we WHERE we.school_id = %d AND we.expense_date = %s', $school_id, $date_now));
		}

		if (WLSM_M_Role::check_permission(array('manage_exams'), $current_school['permissions'])) {
			// Total Exams with Published Timetables.
			$total_exams_with_published_timetables = $wpdb->get_var($wpdb->prepare('SELECT COUNT(ex.ID) FROM ' . WLSM_EXAMS . ' as ex WHERE ex.school_id = %d AND ex.time_table_published = 1', $school_id));

			// Total Exams with Published Admit Cards.
			$total_exams_with_published_admit_cards = $wpdb->get_var($wpdb->prepare('SELECT COUNT(ex.ID) FROM ' . WLSM_EXAMS . ' as ex WHERE ex.school_id = %d AND ex.admit_cards_published = 1', $school_id));

			// Total Exams with Published Results.
			$total_exams_with_published_results = $wpdb->get_var($wpdb->prepare('SELECT COUNT(ex.ID) FROM ' . WLSM_EXAMS . ' as ex WHERE ex.school_id = %d AND ex.results_published = 1', $school_id));
		}

		if (WLSM_M_Role::check_permission(array('manage_library'), $current_school['permissions'])) {
			// Total Books.
			$total_books = $wpdb->get_var(WLSM_M_Staff_Library::fetch_book_query_count($school_id));

			// Total Library Cards.
			$total_library_cards = $wpdb->get_var(WLSM_M_Staff_Library::fetch_library_card_query_count($school_id, $session_id));

			// Total Books Issued.
			$total_books_issued = $wpdb->get_var(WLSM_M_Staff_Library::fetch_book_issued_query_count($school_id, $session_id));

			// Total Books Return Pending.
			$total_books_return_pending = $wpdb->get_var(WLSM_M_Staff_Library::fetch_book_issued_query_count($school_id, $session_id, true));
		}

		if (WLSM_M_Role::check_permission(array('manage_inquiries'), $current_school['permissions'])) {
			// Total Inquiries.
			$total_inquiries_count = $wpdb->get_var($wpdb->prepare('SELECT COUNT(iq.ID) FROM ' . WLSM_INQUIRIES . ' as iq 
		WHERE iq.school_id = %d', $school_id));

			// Inquiries Active.
			$active_inquiries_count = $wpdb->get_var($wpdb->prepare('SELECT COUNT(iq.ID) FROM ' . WLSM_INQUIRIES . ' as iq 
		WHERE iq.school_id = %d AND iq.is_active = 1', $school_id));

			// Last 10 Active Inquiries
			$active_inquiries = $wpdb->get_results(
				$wpdb->prepare('SELECT iq.ID, iq.name, iq.phone, iq.email, iq.message, iq.created_at, iq.next_follow_up, c.label as class_label FROM ' . WLSM_INQUIRIES . ' as iq 
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = iq.school_id 
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = iq.class_school_id 
		LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
		WHERE iq.school_id = %d AND iq.is_active = 1 GROUP BY iq.ID ORDER BY iq.created_at DESC LIMIT 10', $school_id)
			);
		}

		if (WLSM_M_Role::check_permission(array('manage_transfer_student'), $current_school['permissions'])) {
			// Students Transferred to Other School.
			$students_transferred_to_count = $wpdb->get_var(
				$wpdb->prepare('SELECT COUNT(DISTINCT sr.ID) FROM ' . WLSM_TRANSFERS . ' as tf 
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = tf.from_student_record 
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
			WHERE cs.school_id = %d AND ss.ID = %d', $school_id, $session_id)
			);

			// Students Transferred to this School.
			$students_transferred_from_count = $wpdb->get_var(
				$wpdb->prepare('SELECT COUNT(DISTINCT sr.ID) FROM ' . WLSM_TRANSFERS . ' as tf 
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = tf.to_student_record 
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
			WHERE cs.school_id = %d AND ss.ID = %d', $school_id, $session_id)
			);
		}

		if (WLSM_M_Role::check_permission(array('manage_admissions', 'manage_invoices', 'manage_income', 'manage_expense'), $current_school['permissions'])) {
			require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/dashboard/charts.php';

			// Chart settings.
			$settings_charts       = WLSM_M_Setting::get_settings_charts($school_id);
			$settings_chart_types  = $settings_charts['chart_types'];
			$settings_chart_enable = $settings_charts['chart_enable'];
		}
?>
<div class="wlsm container-fluid">
	<?php
	require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/partials/header.php';
	?>

	<div class="row">
		<div class="col-md-12">
			<div class="text-center wlsm-section-heading-block">
				<span class="wlsm-section-heading">
					<i class="fas fa-file-invoice"></i>
					<?php esc_html_e('Accounting', 'school-management'); ?>
				</span>
			</div>
		</div>
	</div>

	<div class="row mt-1 wlsm-stats-blocks">
		<?php if (WLSM_M_Role::check_permission(array('manage_invoices'), $current_school['permissions'])) { ?>
			<div class="col-md-4 col-lg-3">
				<div class="wlsm-stats-block">
					<i class="fas fa-file-invoice wlsm-stats-icon"></i>
					<div class="wlsm-stats-counter"><?php echo esc_html($total_invoices_count); ?></div>
					<div class="wlsm-stats-label">
						<?php
						printf(
							wp_kses(
								/* translators: %s: session label */
								__('Total Invoices <br><small class="text-secondary"> - Session: %s</small>', 'school-management'),
								array('small' => array('class' => array()), 'br' => array())
							),
							esc_html(WLSM_M_Session::get_label_text($current_session['label']))
						);
						?>
					</div>
				</div>
			</div>

			<div class="col-md-4 col-lg-3">
				<div class="wlsm-stats-block">
					<i class="fas fa-file-invoice wlsm-stats-icon"></i>
					<div class="wlsm-stats-counter"><?php echo esc_html($invoices_paid_count); ?></div>
					<div class="wlsm-stats-label">
						<?php
						printf(
							wp_kses(
								/* translators: %s: session label */
								__('Paid Invoices <br><small class="text-secondary"> - Session: %s</small>', 'school-management'),
								array('small' => array('class' => array()), 'br' => array())
							),
							esc_html(WLSM_M_Session::get_label_text($current_session['label']))
						);
						?>
					</div>
				</div>
			</div>

			<div class="col-md-4 col-lg-3">
				<div class="wlsm-stats-block">
					<i class="fas fa-file-invoice wlsm-stats-icon"></i>
					<div class="wlsm-stats-counter"><?php echo esc_html($invoices_unpaid_count); ?></div>
					<div class="wlsm-stats-label">
						<?php
						printf(
							wp_kses(
								/* translators: %s: session label */
								__('Unpaid Invoices <br><small class="text-secondary"> - Session: %s</small>', 'school-management'),
								array('small' => array('class' => array()), 'br' => array())
							),
							esc_html(WLSM_M_Session::get_label_text($current_session['label']))
						);
						?>
					</div>
				</div>
			</div>

			<div class="col-md-4 col-lg-3">
				<div class="wlsm-stats-block">
					<i class="fas fa-file-invoice wlsm-stats-icon"></i>
					<div class="wlsm-stats-counter"><?php echo esc_html($invoices_partially_paid_count); ?></div>
					<div class="wlsm-stats-label">
						<?php
						printf(
							wp_kses(
								/* translators: %s: session label */
								__('Partially Paid Invoices <br><small class="text-secondary"> - Session: %s</small>', 'school-management'),
								array('small' => array('class' => array()), 'br' => array())
							),
							esc_html(WLSM_M_Session::get_label_text($current_session['label']))
						);
						?>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php if (WLSM_M_Role::check_permission(array('stats_payments'), $current_school['permissions'])) { ?>
			<div class="col-md-4 col-lg-3">
				<div class="wlsm-stats-block">
					<i class="fas fa-file-invoice wlsm-stats-icon"></i>
					<div class="wlsm-stats-counter"><?php echo esc_html($total_payments_count); ?></div>
					<div class="wlsm-stats-label">
						<?php
						printf(
							wp_kses(
								/* translators: %s: session label */
								__('Total Payments <br><small class="text-secondary"> - Session: %s</small>', 'school-management'),
								array('small' => array('class' => array()), 'br' => array())
							),
							esc_html(WLSM_M_Session::get_label_text($current_session['label']))
						);
						?>
					</div>
				</div>
			</div>

			<div class="col-md-4 col-lg-3">
				<div class="wlsm-stats-block">
					<i class="fas fa-dollar-sign wlsm-stats-icon"></i>
					<div class="wlsm-stats-counter"><?php echo esc_html(WLSM_Config::get_money_text($total_payment_received, $school_id )); ?></div>
					<div class="wlsm-stats-label">
						<?php
						printf(
							wp_kses(
								/* translators: %s: session label */
								__('Payment Received <br><small class="text-secondary"> - Session: %s</small>', 'school-management'),
								array('small' => array('class' => array()), 'br' => array())
							),
							esc_html(WLSM_M_Session::get_label_text($current_session['label']))
						);
						?>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php if (WLSM_M_Role::check_permission(array('stats_amount_fees_structure'), $current_school['permissions'])) { ?>
			<div class="col-md-4 col-lg-3">
				<div class="wlsm-stats-block">
					<i class="fas fa-dollar-sign wlsm-stats-icon"></i>
					<div class="wlsm-stats-counter"><?php echo esc_html( WLSM_Config::get_money_text( $invoices_pending_amount, $school_id  ) ); ?></div>
					<div class="wlsm-stats-label">
						<?php
						printf(
							wp_kses(
								/* translators: %s: session label */
								__('Amount Pending<br><small class="text-secondary"> - Session: %s</small>', 'school-management'),
								array('small' => array('class' => array()), 'br' => array())
							),
							esc_html(WLSM_M_Session::get_label_text($current_session['label']))
						);
						?>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php if (WLSM_M_Role::check_permission(array('stats_expense'), $current_school['permissions'])) { ?>
			<div class="col-md-4 col-lg-3">
				<div class="wlsm-stats-block">
					<i class="fas fa-dollar-sign wlsm-stats-icon"></i>
					<div class="wlsm-stats-counter"><?php echo esc_html(WLSM_Config::get_money_text($total_expenses_sum,  $school_id )); ?></div>
					<div class="wlsm-stats-label">
						<?php
						printf(
							wp_kses(
								/* translators: %s: session label */
								__('Expense <br><small class="text-secondary"> - Session: %s</small>', 'school-management'),
								array('small' => array('class' => array()), 'br' => array())
							),
							esc_html(WLSM_M_Session::get_label_text($current_session['label']))
						);
						?>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php if (WLSM_M_Role::check_permission(array('stats_income'), $current_school['permissions'])) { ?>
			<div class="col-md-4 col-lg-3">
				<div class="wlsm-stats-block">
					<i class="fas fa-dollar-sign wlsm-stats-icon"></i>
					<div class="wlsm-stats-counter"><?php echo esc_html(WLSM_Config::get_money_text($total_income_sum,  $school_id )); ?></div>
					<div class="wlsm-stats-label">
						<?php
						printf(
							wp_kses(
								/* translators: %s: session label */
								__('Income <br><small class="text-secondary"> - Session: %s</small>', 'school-management'),
								array('small' => array('class' => array()), 'br' => array())
							),
							esc_html(WLSM_M_Session::get_label_text($current_session['label']))
						);
						?>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php if (WLSM_M_Role::check_permission(array('stats_income'), $current_school['permissions'])) { ?>
			<div class="col-md-4 col-lg-3">
				<div class="wlsm-stats-block">
					<i class="fas fa-dollar-sign wlsm-stats-icon"></i>
					<div class="wlsm-stats-counter"><?php echo esc_html(WLSM_Config::get_money_text($total_dailypaymentstotal_sum,  $school_id )); ?></div>
					<div class="wlsm-stats-label">
						<?php
						printf(
							wp_kses(
								/* translators: %s: session label */
								__('Daily Payments Total (Today)<br><small class="text-secondary"> - Session: %s</small>', 'school-management'),
								array('small' => array('class' => array()), 'br' => array())
							),
							esc_html(WLSM_M_Session::get_label_text($current_session['label']))
						);
						?>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php if (WLSM_M_Role::check_permission(array('stats_income'), $current_school['permissions'])) { ?>
			<div class="col-md-4 col-lg-3">
				<div class="wlsm-stats-block">
					<i class="fas fa-dollar-sign wlsm-stats-icon"></i>
					<div class="wlsm-stats-counter"><?php echo esc_html(WLSM_Config::get_money_text($total_dailyexpencestotal_sum,  $school_id )); ?></div>
					<div class="wlsm-stats-label">
						<?php
						printf(
							wp_kses(
								/* translators: %s: session label */
								__('Daily Expense Total <br><small class="text-secondary"> - Session: %s</small>', 'school-management'),
								array('small' => array('class' => array()), 'br' => array())
							),
							esc_html(WLSM_M_Session::get_label_text($current_session['label']))
						);
						?>
					</div>
				</div>
			</div>
		<?php } ?>


		<?php 
		// Get current user session id
		$current_user = WLSM_M_Role::can('manage_invoices');
		$current_session_id = $current_user['session']['ID'];
		// get Previous session label
		$previous_session = WLSM_M_Session::get_pre_session($current_session_id);
		$previous_session_label = ($previous_session) ? $previous_session->label : 'Not Exists' ;

		// get Previous session pending amount total
		$previous_session_id = ($previous_session) ? $previous_session->ID : null ;
		if ($previous_session_id) {
			$previous_session_invoices_pending_amount = $wpdb->get_col(
				$wpdb->prepare( 'SELECT ((i.amount) - COALESCE(SUM(p.amount), 0)) as due FROM ' . WLSM_INVOICES . ' as i 
					JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id 
					JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
					JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
					JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
					JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
					LEFT OUTER JOIN ' . WLSM_PAYMENTS . ' as p ON p.invoice_id = i.ID 
					WHERE cs.school_id = %d AND ss.ID = %d AND (i.status = "%s" OR i.status = "%s") GROUP BY i.ID', $school_id, $previous_session_id, WLSM_M_Invoice::get_unpaid_key(), WLSM_M_Invoice::get_partially_paid_key() )
			);
	
			$previous_session_pending_invoice = array_sum( $previous_session_invoices_pending_amount );
		} else {
			$previous_session_pending_invoice = 0;
		}

		?>
		<div class="col-md-4 col-lg-3">
				<div class="wlsm-stats-block">
					<i class="fas fa-dollar-sign wlsm-stats-icon"></i>
					<div class="wlsm-stats-counter"><?php echo esc_html(WLSM_Config::get_money_text($previous_session_pending_invoice,  $school_id )); ?></div>
					<div class="wlsm-stats-label">
						<?php
						printf(
							wp_kses(
								/* translators: %s: session label */
								__('Previous Year Total Pending <br><small class="text-secondary"> - Session: %s</small>', 'school-management'),
								array('small' => array('class' => array()), 'br' => array())
							),
							esc_html(WLSM_M_Session::get_label_text($previous_session_label))
						);
						?>
					</div>
				</div>
			</div>
	</div>

	<div class="row mt-3 mb-3">
		<?php if (WLSM_M_Role::check_permission(array('manage_invoices'), $current_school['permissions'])) { ?>
			<div class="col-md-4 col-sm-6">
				<div class="wlsm-group">
					<span class="wlsm-group-title"><?php esc_html_e('Fee Invoices', 'school-management'); ?></span>
					<div class="wlsm-group-actions">
						<a href="<?php echo esc_url($page_url_invoices); ?>" class="btn btn-sm btn-primary">
							<?php esc_html_e('Fee Invoices', 'school-management'); ?>
						</a>
						<a href="<?php echo esc_url($page_url_invoices . '&action=save'); ?>" class="btn btn-sm btn-outline-primary">
							<?php esc_html_e('Add New Fee Invoice', 'school-management'); ?>
						</a>
						<a href="<?php echo esc_url($page_url_invoices . '&action=payment_history'); ?>" class="btn btn-sm btn-outline-primary">
							<?php esc_html_e('Payment History', 'school-management'); ?>
						</a>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php if (WLSM_M_Role::check_permission(array('manage_expenses'), $current_school['permissions'])) { ?>
			<div class="col-md-4 col-sm-6">
				<div class="wlsm-group">
					<span class="wlsm-group-title"><?php esc_html_e('Expense', 'school-management'); ?></span>
					<div class="wlsm-group-actions">
						<a href="<?php echo esc_url($page_url_expenses); ?>" class="btn btn-sm btn-primary">
							<?php esc_html_e('View Expense', 'school-management'); ?>
						</a>
						<a href="<?php echo esc_url($page_url_expenses . '&action=save'); ?>" class="btn btn-sm btn-outline-primary">
							<?php esc_html_e('Add New Expense', 'school-management'); ?>
						</a>
						<a href="<?php echo esc_url($page_url_expenses . '&action=category'); ?>" class="btn btn-sm btn-outline-primary">
							<?php esc_html_e('Expense Category', 'school-management'); ?>
						</a>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php if (WLSM_M_Role::check_permission(array('manage_income'), $current_school['permissions'])) { ?>
			<div class="col-md-4 col-sm-6">
				<div class="wlsm-group">
					<span class="wlsm-group-title"><?php esc_html_e('Income', 'school-management'); ?></span>
					<div class="wlsm-group-actions">
						<a href="<?php echo esc_url($page_url_income); ?>" class="btn btn-sm btn-primary">
							<?php esc_html_e('View Income', 'school-management'); ?>
						</a>
						<a href="<?php echo esc_url($page_url_income . '&action=save'); ?>" class="btn btn-sm btn-outline-primary">
							<?php esc_html_e('Add New Income', 'school-management'); ?>
						</a>
						<a href="<?php echo esc_url($page_url_income . '&action=category'); ?>" class="btn btn-sm btn-outline-primary">
							<?php esc_html_e('Income Category', 'school-management'); ?>
						</a>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php if (WLSM_M_Role::check_permission(array('manage_fees'), $current_school['permissions'])) { ?>
			<div class="col-md-4 col-sm-6">
				<div class="wlsm-group">
					<span class="wlsm-group-title"><?php esc_html_e('Fee Types', 'school-management'); ?></span>
					<div class="wlsm-group-actions">
						<a href="<?php echo esc_url($page_url_fees); ?>" class="btn btn-sm btn-primary">
							<?php esc_html_e('View Fee Types', 'school-management'); ?>
						</a>
						<a href="<?php echo esc_url($page_url_fees . '&action=save'); ?>" class="btn btn-sm btn-outline-primary">
							<?php esc_html_e('Add New Fee Type', 'school-management'); ?>
						</a>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>

<?php if ( WLSM_M_Role::check_permission( array( 'manage_invoices' ), $current_school['permissions'] ) ) { ?>
<div class="row">
	<div class="col-md-12">
		<div class="wlsm-stats-heading-block">
			<div class="wlsm-stats-heading">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Daily Payment History <small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ) )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);

				$can_delete_payments = WLSM_M_Role::check_permission( array( 'delete_payments' ), $current_school['permissions'] );
				?>
			</div>
		</div>
		<table class="table wlsm-stats-table wlsm-stats-payment-table">
			<thead class="bg-primary text-white">
				<tr>
					<th><?php esc_html_e( 'Receipt Number', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Amount', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Payment Method', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Transaction ID', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Date', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Invoice', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Student Name', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Admission Number', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Class', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Section', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Phone', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'father Name', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'father Phone', 'school-management' ); ?></th>
					<?php if ( $can_delete_payments ) { ?>
					<th class="text-nowrap"><?php esc_html_e( 'Delete', 'school-management' ); ?></th>
					<?php } ?>
				</tr>
			</thead>
		</table>
	</div>
</div>
<?php }?>
</div>
