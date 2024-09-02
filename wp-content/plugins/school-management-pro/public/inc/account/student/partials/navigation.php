<?php
defined( 'ABSPATH' ) || die();
$settings_dashboard                     = WLSM_M_Setting::get_settings_dashboard($school_id);
$school_invoice          = $settings_dashboard['school_invoice'];
$school_payment_history  = $settings_dashboard['school_payment_history'];
$school_study_material   = $settings_dashboard['school_study_material'];
$school_home_work        = $settings_dashboard['school_home_work'];
$school_noticeboard      = $settings_dashboard['school_noticeboard'];
$school_events           = $settings_dashboard['school_events'];
$school_class_time_table = $settings_dashboard['school_class_time_table'];
$school_live_classes     = $settings_dashboard['school_live_classes'];
$school_books_issues     = $settings_dashboard['school_books_issues'];
$school_exam_time_table  = $settings_dashboard['school_exam_time_table'];
$school_admit_card       = $settings_dashboard['school_admit_card'];
$school_exam_result      = $settings_dashboard['school_exam_result'];
$school_certificate      = $settings_dashboard['school_certificate'];
$school_attendance       = $settings_dashboard['school_attendance'];
$school_leave_request    = $settings_dashboard['school_leave_request'];





?>
<input class="wlsm-menu-btn" type="checkbox" id="wlsm-menu-btn">
<label class="wlsm-menu-label" for="wlsm-menu-btn"><span class="wlsm-menu-icon"></span></label>
<ul class="wlsm-navigation-links">
	<li>
		<a class="wlsm-navigation-link<?php if ( '' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array(), $current_page_url ) ); ?>"><?php esc_html_e( 'Dashboard', 'school-management' ); ?></a>
	</li>
	<?php if ($school_invoice): ?>
	<li>
		<a class="wlsm-navigation-link<?php if ( 'fee-invoices' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'fee-invoices' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Fee Invoices', 'school-management' ); ?></a>
	</li>
	<?php endif ?>

	<?php if ($school_invoice): ?>
	<li>
		<a class="wlsm-navigation-link<?php if ( 'fee-structure' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'fee-structure' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Fee Structure', 'school-management' ); ?></a>
	</li>
	<?php endif ?>

	<?php if ($school_payment_history): ?>
		<li>
		<a class="wlsm-navigation-link<?php if ( 'payment-history' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'payment-history' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Payment History', 'school-management' ); ?></a>
	</li>
	<?php endif ?>

	<?php if ($school_study_material): ?>
		<li>
		<a class="wlsm-navigation-link<?php if ( 'study-materials' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'study-materials' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Study Materials', 'school-management' ); ?></a>
	</li>
	<?php endif ?>

	<?php if ($school_home_work): ?>
		<li>
		<a class="wlsm-navigation-link<?php if ( 'homework' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'homework' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Home Work', 'school-management' ); ?></a>
	</li>
	<?php endif ?>
	<?php if ($school_noticeboard): ?>
		<li>
		<a class="wlsm-navigation-link<?php if ( 'noticeboard' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'noticeboard' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Noticeboard', 'school-management' ); ?></a>
	</li>
	<?php endif ?>

	<?php if ($school_events): ?>
	<li>
			<a class="wlsm-navigation-link<?php if ( 'events' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'events' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Events', 'school-management' ); ?></a>
		</li>
	<?php endif ?>
<?php if ($school_class_time_table): ?>
	<li>
		<a class="wlsm-navigation-link<?php if ( 'class-time-table' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'class-time-table' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Class Time Table', 'school-management' ); ?></a>
	</li>
<?php endif ?>

	<?php if ($school_live_classes): ?>
		<li>
		<a class="wlsm-navigation-link<?php if ( 'live-classes' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'live-classes' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Live Classes', 'school-management' ); ?></a>
	</li>
	<?php endif ?>

<?php if ($school_books_issues): ?>
	<li>
		<a class="wlsm-navigation-link<?php if ( 'books-issued' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'books-issued' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Books Issued', 'school-management' ); ?></a>
	</li>
<?php endif ?>
<?php if ($school_exam_time_table): ?>
<li>
		<a class="wlsm-navigation-link<?php if ( 'exams-time-table' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'exams-time-table' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Exam Time Table', 'school-management' ); ?></a>
	</li>
<?php endif ?>
<?php if ($school_admit_card): ?>
	<li>
		<a class="wlsm-navigation-link<?php if ( 'exam-admit-card' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'exam-admit-card' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Admit Card', 'school-management' ); ?></a>
	</li>
<?php endif ?>

<?php if ($school_exam_result): ?>
		<li>
		<a class="wlsm-navigation-link<?php if ( 'exam-results' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'exam-results' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Exam Results', 'school-management' ); ?></a>
	</li>
<?php endif ?>

<?php if ($school_certificate): ?>
		<li>
		<a class="wlsm-navigation-link<?php if ( 'certificates' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'certificates' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Certificates', 'school-management' ); ?></a>
	</li>
<?php endif ?>

<?php if ($school_attendance): ?>
	<li>
		<a class="wlsm-navigation-link<?php if ( 'attendance' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'attendance' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Attendance', 'school-management' ); ?></a>
	</li>
<?php endif ?>

<?php if ($school_leave_request): ?>
		<li>
		<a class="wlsm-navigation-link<?php if ( 'leave-request' === $action ) { echo ' active'; } ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'leave-request' ), $current_page_url ) ); ?>"><?php esc_html_e( 'Leave Request', 'school-management' ); ?></a>
	</li>

<?php endif ?>
</ul>
