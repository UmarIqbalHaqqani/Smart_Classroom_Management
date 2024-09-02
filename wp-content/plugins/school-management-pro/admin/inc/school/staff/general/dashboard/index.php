<?php
defined( 'ABSPATH' ) || die();

global $wpdb;

$school_id  = $current_school['id'];
$session_id = $current_session['ID'];

$schools_page_url  = WLSM_M_School::get_page_url();
$students_page_url = WLSM_M_Staff_General::get_students_page_url();
$invoices_page_url = WLSM_M_Staff_Accountant::get_invoices_page_url();

$session_start_date = new DateTime( $current_session['start_date'] );
$session_end_date   = new DateTime( $current_session['end_date'] );
$session_start_date = $session_start_date->format('Y-m-d');
$session_end_date   = $session_end_date->format('Y-m-d');

if ( WLSM_M_Role::check_permission( array( 'manage_classes' ), $current_school['permissions'] ) ) {
	// Total Classes.
	$total_classes_count  = $wpdb->get_var( WLSM_M_Staff_Class::fetch_classes_query_count( $school_id ) );

	// Total Sections.
	$total_sections_count = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(DISTINCT se.ID) FROM ' . WLSM_SECTIONS . ' as se JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = cs.school_id WHERE cs.school_id = %d', $school_id ) );
}

if ( WLSM_M_Role::check_permission( array( 'manage_students' ), $current_school['permissions'] ) ) {
	// Total Students.
	$total_students_count = $wpdb->get_var(
		$wpdb->prepare( 'SELECT COUNT(DISTINCT sr.ID) FROM ' . WLSM_STUDENT_RECORDS . ' as sr
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			WHERE ss.ID = %d AND cs.school_id = %d', $session_id, $school_id )
	);

	// Students Active.
	$active_students_count = $wpdb->get_var(
		$wpdb->prepare( 'SELECT COUNT(DISTINCT sr.ID) FROM ' . WLSM_STUDENT_RECORDS . ' as sr
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			WHERE ss.ID = %d AND cs.school_id = %d AND sr.is_active = 1', $session_id, $school_id )
	);

	// Students Active.
	$inactive_students_count = $wpdb->get_var(
		$wpdb->prepare( 'SELECT COUNT(DISTINCT sr.ID) FROM ' . WLSM_STUDENT_RECORDS . ' as sr
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			WHERE ss.ID = %d AND cs.school_id = %d AND sr.is_active = 0', $session_id, $school_id )
	);

	// Students promoted.
	$promoted_students_count = $wpdb->get_var(
		$wpdb->prepare( 'SELECT COUNT(DISTINCT sp.ID) FROM ' . WLSM_PROMOTIONS . ' as sp
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = sp.from_student_record
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			WHERE sr.session_id = %d AND cs.school_id = %d AND sr.is_active = 0', $session_id, $school_id )
	);
}

if ( WLSM_M_Role::check_permission( array( 'manage_admins' ), $current_school['permissions'] ) ) {
	// Total Admins.
	$total_admins_count  = $wpdb->get_var( WLSM_M_Staff_General::fetch_staff_query_count( $school_id, WLSM_M_Role::get_admin_key() ) );
}

if ( WLSM_M_Role::check_permission( array( 'manage_roles' ), $current_school['permissions'] ) ) {
	// Total Roles.
	$total_roles_count = $wpdb->get_var( WLSM_M_Staff_General::fetch_role_query_count( $school_id ) );
}

if ( WLSM_M_Role::check_permission( array( 'manage_employees' ), $current_school['permissions'] ) ) {
	// Total Staff.
	$total_staff_count = $wpdb->get_var(
		$wpdb->prepare( 'SELECT COUNT(DISTINCT a.ID) FROM ' . WLSM_ADMINS . ' as a
			JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
			WHERE sf.role = "%s" AND sf.school_id = %d', WLSM_M_Role::get_employee_key(), $school_id )
	);

	// Staff Active.
	$active_staff_count = $wpdb->get_var(
		$wpdb->prepare( 'SELECT COUNT(DISTINCT a.ID) FROM ' . WLSM_ADMINS . ' as a
			JOIN ' . WLSM_STAFF . ' as sf ON sf.ID = a.staff_id
			WHERE sf.role = "%s" AND sf.school_id = %d AND a.is_active = 1', WLSM_M_Role::get_employee_key(), $school_id )
	);
}

if ( WLSM_M_Role::check_permission( array( 'manage_admissions' ), $current_school['permissions'] ) ) {
	// Last 15 Admissions
	$admissions = $wpdb->get_results(
		$wpdb->prepare( 'SELECT sr.ID, sr.name as student_name, sr.enrollment_number, sr.admission_number, sr.admission_date, c.label as class_label, se.label as section_label FROM ' . WLSM_STUDENT_RECORDS . ' as sr
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE cs.school_id = %d AND ss.ID = %d GROUP BY sr.ID ORDER BY sr.admission_date DESC LIMIT 15', $school_id, $session_id )
	);
}

if ( WLSM_M_Role::check_permission( array( 'manage_invoices' ), $current_school['permissions'] ) ) {
	// Total Invoices.
	$total_invoices_count = $wpdb->get_var(
		$wpdb->prepare( 'SELECT COUNT(DISTINCT i.ID) FROM ' . WLSM_INVOICES . ' as i
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			WHERE cs.school_id = %d AND ss.ID = %d', $school_id, $session_id )
	);

	// Paid Invoices.
	$invoices_paid_count = $wpdb->get_var(
		$wpdb->prepare( 'SELECT COUNT(DISTINCT i.ID) FROM ' . WLSM_INVOICES . ' as i
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			WHERE cs.school_id = %d AND ss.ID = %d AND i.status = "%s"', $school_id, $session_id, WLSM_M_Invoice::get_paid_key() )
	);

	// Unpaid Invoices.
	$invoices_unpaid_count = $wpdb->get_var(
		$wpdb->prepare( 'SELECT COUNT(DISTINCT i.ID) FROM ' . WLSM_INVOICES . ' as i
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			WHERE cs.school_id = %d AND ss.ID = %d AND i.status = "%s"', $school_id, $session_id, WLSM_M_Invoice::get_unpaid_key() )
	);

	// Invoices Partially Paid.
	$invoices_partially_paid_count = $wpdb->get_var(
		$wpdb->prepare( 'SELECT COUNT(DISTINCT i.ID) FROM ' . WLSM_INVOICES . ' as i
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = i.student_record_id
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			WHERE cs.school_id = %d AND ss.ID = %d AND i.status = "%s"', $school_id, $session_id, WLSM_M_Invoice::get_partially_paid_key() )
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

if ( WLSM_M_Role::check_permission( array( 'stats_payments' ), $current_school['permissions'] ) ) {
	// Total Payments.
	$total_payments_count = $wpdb->get_var( WLSM_M_Staff_Accountant::fetch_payments_query_count( $school_id, $session_id ) );

	// Total Payment Received.
	$total_payment_received = WLSM_M_Staff_Accountant::get_total_payments_received( $school_id, $session_id );
}

if ( WLSM_M_Role::check_permission( array( 'stats_amount_fees_structure' ), $current_school['permissions'] ) ) {
	// Total Fees Structure Amount.
	$total_fees_structure_amount = WLSM_M_Staff_Accountant::get_total_fees_structure_amount( $school_id, $session_id );
}

if ( WLSM_M_Role::check_permission( array( 'stats_expense' ), $current_school['permissions'] ) ) {
	// Total Expenses - Sum.
	$total_expenses_sum = $wpdb->get_var( $wpdb->prepare( 'SELECT COALESCE(SUM(ep.amount), 0) as sum FROM ' . WLSM_EXPENSES . ' as ep WHERE ep.school_id = %d AND ep.expense_date BETWEEN %s AND %s', $school_id, $session_start_date, $session_end_date ) );
}

if ( WLSM_M_Role::check_permission( array( 'stats_income' ), $current_school['permissions'] ) ) {
	// Total Income - Sum.
	$total_income_sum = $wpdb->get_var( $wpdb->prepare( 'SELECT COALESCE(SUM(im.amount), 0) as sum FROM ' . WLSM_INCOME . ' as im WHERE im.school_id = %d AND im.income_date BETWEEN %s AND %s', $school_id, $session_start_date, $session_end_date ) );
}

if ( WLSM_M_Role::check_permission( array( 'manage_exams' ), $current_school['permissions'] ) ) {
	// Total Exams with Published Timetables.
	$total_exams_with_published_timetables = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(ex.ID) FROM ' . WLSM_EXAMS . ' as ex WHERE ex.school_id = %d AND ex.time_table_published = 1', $school_id ) );

	// Total Exams with Published Admit Cards.
	$total_exams_with_published_admit_cards = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(ex.ID) FROM ' . WLSM_EXAMS . ' as ex WHERE ex.school_id = %d AND ex.admit_cards_published = 1', $school_id ) );

	// Total Exams with Published Results.
	$total_exams_with_published_results = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(ex.ID) FROM ' . WLSM_EXAMS . ' as ex WHERE ex.school_id = %d AND ex.results_published = 1', $school_id ) );
}

if ( WLSM_M_Role::check_permission( array( 'manage_library' ), $current_school['permissions'] ) ) {
	// Total Books.
	$total_books = $wpdb->get_var( WLSM_M_Staff_Library::fetch_book_query_count( $school_id ) );

	// Total Library Cards.
	$total_library_cards = $wpdb->get_var( WLSM_M_Staff_Library::fetch_library_card_query_count( $school_id, $session_id ) );

	// Total Books Issued.
	$total_books_issued = $wpdb->get_var( WLSM_M_Staff_Library::fetch_book_issued_query_count( $school_id, $session_id ) );

	// Total Books Return Pending.
	$total_books_return_pending = $wpdb->get_var( WLSM_M_Staff_Library::fetch_book_issued_query_count( $school_id, $session_id, true ) );
}

// get total girls number from student table.
$total_girls = $wpdb->get_var(
	$wpdb->prepare( 'SELECT COUNT(DISTINCT sr.ID) FROM ' . WLSM_STUDENT_RECORDS . ' as sr
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		WHERE ss.ID = %d AND cs.school_id = %d AND sr.gender = %s', $session_id, $school_id, 'female' )
);

$total_boys = $wpdb->get_var(
	$wpdb->prepare( 'SELECT COUNT(DISTINCT sr.ID) FROM ' . WLSM_STUDENT_RECORDS . ' as sr
		JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
		JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
		JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
		WHERE ss.ID = %d AND cs.school_id = %d AND sr.gender = %s', $session_id, $school_id, 'male' )
);

if ( WLSM_M_Role::check_permission( array( 'manage_inquiries' ), $current_school['permissions'] ) ) {
	// Total Inquiries.
	$total_inquiries_count = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(iq.ID) FROM ' . WLSM_INQUIRIES . ' as iq
		WHERE iq.school_id = %d', $school_id ) );

	// Inquiries Active.
	$active_inquiries_count = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(iq.ID) FROM ' . WLSM_INQUIRIES . ' as iq
		WHERE iq.school_id = %d AND iq.is_active = 1', $school_id ) );

	// Last 10 Active Inquiries
	$active_inquiries = $wpdb->get_results(
		$wpdb->prepare( 'SELECT iq.ID, iq.name, iq.phone, iq.email, iq.message, iq.created_at, iq.next_follow_up, c.label as class_label FROM ' . WLSM_INQUIRIES . ' as iq
		JOIN ' . WLSM_SCHOOLS . ' as s ON s.ID = iq.school_id
		LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = iq.class_school_id
		LEFT OUTER JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
		WHERE iq.school_id = %d AND iq.is_active = 1 GROUP BY iq.ID ORDER BY iq.created_at DESC LIMIT 10', $school_id )
	);
}

if ( WLSM_M_Role::check_permission( array( 'manage_transfer_student' ), $current_school['permissions'] ) ) {
	// Students Transferred to Other School.
	$students_transferred_to_count = $wpdb->get_var(
		$wpdb->prepare( 'SELECT COUNT(DISTINCT sr.ID) FROM ' . WLSM_TRANSFERS . ' as tf
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = tf.from_student_record
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			WHERE cs.school_id = %d AND ss.ID = %d', $school_id, $session_id )
	);

	// Students Transferred to this School.
	$students_transferred_from_count = $wpdb->get_var(
		$wpdb->prepare( 'SELECT COUNT(DISTINCT sr.ID) FROM ' . WLSM_TRANSFERS . ' as tf
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = tf.to_student_record
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
			WHERE cs.school_id = %d AND ss.ID = %d', $school_id, $session_id )
	);
}

if ( WLSM_M_Role::check_permission( array( 'manage_admissions', 'manage_invoices', 'manage_income', 'manage_expense' ), $current_school['permissions'] ) ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/dashboard/charts.php';

	// Chart settings.
	$settings_charts       = WLSM_M_Setting::get_settings_charts( $school_id );
	$settings_chart_types  = $settings_charts['chart_types'];
	$settings_chart_enable = $settings_charts['chart_enable'];
}
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-tachometer-alt"></i>
				<?php esc_html_e( 'School Dashboard', 'school-management' ); ?>
			</span>
			<?php if ( current_user_can( WLSM_ADMIN_CAPABILITY ) ) { ?>
			<span class="float-md-right">
				<a class="btn btn-sm btn-outline-light" href="<?php echo esc_url( $schools_page_url . '&action=classes&id=' . $school_id ); ?>">
					<i class="fas fa-layer-group"></i>&nbsp;
					<?php esc_html_e( 'Assign Classes', 'school-management' ); ?>
				</a>&nbsp;
				<a class="btn btn-sm btn-outline-light" href="<?php echo esc_url( $schools_page_url . '&action=admins&id=' . $school_id ); ?>">
					<i class="fas fa-user-shield"></i>&nbsp;
					<?php esc_html_e( 'Assign Admins', 'school-management' ); ?>
				</a>
			</span>
			<?php } ?>
		</div>
	</div>
</div>

<?php
if ( WLSM_M_Role::check_permission( array( 'manage_admissions', 'manage_invoices' ), $current_school['permissions'] ) ) {
?>
<div class="row">
	<?php if ( WLSM_M_Role::check_permission( array( 'manage_admissions' ), $current_school['permissions'] ) && ( $settings_chart_enable['monthly_admissions'] ) ) { ?>
	<div class="col-md-6">
		<?php require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/dashboard/charts/monthly_admissions.php'; ?>
	</div>
	<?php } ?>

	<?php if ( WLSM_M_Role::check_permission( array( 'manage_invoices' ), $current_school['permissions'] ) && ( $settings_chart_enable['monthly_payments'] ) ) { ?>
	<div class="col-md-6">
		<?php require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/dashboard/charts/monthly_payments.php'; ?>
	</div>
	<?php } ?>
</div>
<?php
}
?>

<div class="row">
	<div class="col-md-12">
		<?php require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/dashboard/stats.php'; ?>
	</div>
</div>

<?php
if ( WLSM_M_Role::check_permission( array( 'manage_income', 'manage_expense' ), $current_school['permissions'] ) && ( $settings_chart_enable['monthly_income_expense'] ) ) {
?>
<div class="row">
	<div class="col-md-12">
		<?php require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/dashboard/charts/monthly_income_expense.php'; ?>
	</div>
</div>
<?php
}

if ( WLSM_M_Role::check_permission( array( 'manage_inquiries' ), $current_school['permissions'] ) ) { ?>
<div class="row">
	<div class="col-md-12">
		<div class="wlsm-stats-heading-block">
			<div class="wlsm-stats-heading">
				<?php esc_html_e( 'Last 10 Active Inquiries', 'school-management' ); ?>
			</div>
		</div>
		<table class="table wlsm-stats-table wlsm-stats-active-inquiries-table">
			<thead class="bg-primary text-white">
				<tr>
					<th><?php esc_html_e( 'Class', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Name', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Phone', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Email', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Message', 'school-management' ); ?></th>
					<th class="text-nowrap"><?php esc_html_e( 'Date', 'school-management' ); ?></th>
					<th class="text-nowrap"><?php esc_html_e( 'Follow Up Date', 'school-management' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $active_inquiries as $row ) { ?>
				<tr>
					<td><?php echo esc_html( WLSM_M_Class::get_label_text( $row->class_label ) ); ?></td>
					<td><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $row->name ) ); ?></td>
					<td><?php echo esc_html( WLSM_M_Staff_Class::get_phone_text( $row->phone ) ); ?></td>
					<td><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $row->email ) ); ?></td>
					<td>
					<?php
					if ( $row->message ) {
						echo esc_html( WLSM_Config::limit_string( $row->message, 50 ) );
						echo '<a class="text-primary wlsm-view-inquiry-message" data-nonce="' . esc_attr( wp_create_nonce( 'view-inquiry-message-' . $row->ID ) ) . '" data-inquiry="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Inquiry Message', 'school-management' ) . '" data-close="' . esc_attr__( 'Close', 'school-management' ) . '"><span class="dashicons dashicons-search"></span></a>';
					} else {
						echo '-';
					}
					?>
					</td>
					<td class="text-nowrap"><?php echo esc_html( WLSM_Config::get_date_text( $row->created_at ) ); ?></td>
					<td class="text-nowrap"><?php echo esc_html( $row->next_follow_up ? WLSM_Config::get_date_text( $row->next_follow_up ) : '-' ); ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<?php
}
?>

<?php if ( WLSM_M_Role::check_permission( array( 'manage_admissions' ), $current_school['permissions'] ) ) { ?>
<div class="row">
	<div class="col-md-12">
		<div class="wlsm-stats-heading-block">
			<div class="wlsm-stats-heading">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Last 15 Admissions <small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ) )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
		<table class="table wlsm-stats-table wlsm-stats-admission-table">
			<thead class="bg-primary text-white">
				<tr>
					<th><?php esc_html_e( 'Student Name', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Class', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Section', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Admission Number', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Admission Date', 'school-management' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $admissions as $row ) { ?>
				<tr>
					<td><a href="<?php echo esc_url( $students_page_url . "&action=save&id=" . $row->ID ); ?>" class="wlsm-link"><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $row->student_name ) ); ?></a></td>
					<td><?php echo esc_html( $row->enrollment_number ); ?></td>
					<td><?php echo esc_html( WLSM_M_Class::get_label_text( $row->class_label ) ); ?></td>
					<td><?php echo esc_html( WLSM_M_Staff_Class::get_section_label_text( $row->section_label ) ); ?></td>
					<td><?php echo esc_html( WLSM_M_Staff_Class::get_admission_no_text( $row->admission_number ) ); ?></td>
					<td><?php echo esc_html( WLSM_Config::get_date_text( $row->admission_date ) ); ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<?php } ?>

<?php if ( WLSM_M_Role::check_permission( array( 'manage_invoices' ), $current_school['permissions'] ) ) { ?>
<!-- <div class="row">
	<div class="col-md-12">
		<div class="wlsm-stats-heading-block">
			<div class="wlsm-stats-heading">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Last 15 Payments <small class="text-secondary"> - Session: %s</small>', 'school-management' ),
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
</div> -->
<?php }
