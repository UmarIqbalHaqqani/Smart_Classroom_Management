<?php
defined( 'ABSPATH' ) || die();

$students = WLSM_M_Parent::fetch_students( $unique_student_ids );
$school_id  = $students[0]->school_id;
$settings_dashboard             = WLSM_M_Setting::get_settings_dashboard($school_id);
$school_parent_id_card          = $settings_dashboard['parent_id_card'];
$school_parent_fee_invoice      = $settings_dashboard['parent_fee_invoice'];
$school_parent_payement_history = $settings_dashboard['parent_payement_history'];
$school_parent_noticeboard      = $settings_dashboard['parent_noticeboard'];
$school_parent_class_time_table = $settings_dashboard['parent_class_time_table'];
$school_parent_exam_results     = $settings_dashboard['parent_exam_results'];
$school_parent_attendance       = $settings_dashboard['parent_attendance'];

?>
<hr>
<div class="wlsm-parent-students">
<?php
foreach ( $students as $student ) {
	?>
	<div class="wlsm-parent-student-section">
		<?php
		require WLSM_PLUGIN_DIR_PATH . 'public/inc/account/parent/partials/student_detail.php';
		?>
		<ul class="wlsm-parent-student-links">
			<?php if ($school_parent_id_card): ?>
				<li>
				<a class="wlsm-pr-print-id-card" data-id-card="<?php echo esc_attr( $student->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'pr-print-id-card-' . $student->ID ) ); ?>" href="#" data-message-title="<?php echo esc_attr__( 'Print ID Card', 'school-management' ); ?>"><?php esc_html_e( 'ID Card', 'school-management' ); ?></a>
			</li>
			<?php endif ?>
			<?php if ($school_parent_fee_invoice): ?>
				<li>
				<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'fee-invoices', 'student_id' => $student->ID ), $current_page_url ) ); ?>"><?php esc_html_e( 'Fee Invoices', 'school-management' ); ?></a>
			</li>
			<?php endif ?>
			<?php if ($school_parent_fee_invoice): ?>
				<li>
				<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'fee-structure', 'student_id' => $student->ID ), $current_page_url ) ); ?>"><?php esc_html_e( 'Fee Structure', 'school-management' ); ?></a>
			</li>
			<?php endif ?>
			<?php if ($school_parent_payement_history): ?>
				<li>
				<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'payment-history', 'student_id' => $student->ID ), $current_page_url ) ); ?>"><?php esc_html_e( 'Payment History', 'school-management' ); ?></a>
			</li>
			<?php endif ?>
			<?php if ($school_parent_noticeboard): ?>
				<li>
				<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'noticeboard', 'student_id' => $student->ID ), $current_page_url ) ); ?>"><?php esc_html_e( 'Noticeboard', 'school-management' ); ?></a>
			</li>
			<?php endif ?>
			<?php if ($school_parent_class_time_table): ?>
				<li>
				<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'class-time-table', 'student_id' => $student->ID ), $current_page_url ) ); ?>"><?php esc_html_e( 'Class Time Table', 'school-management' ); ?></a>
			</li>
			<?php endif ?>
			<?php if ($school_parent_exam_results): ?>
					<li>
				<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'exam-results', 'student_id' => $student->ID ), $current_page_url ) ); ?>"><?php esc_html_e( 'Exam Results', 'school-management' ); ?></a>
			</li>
			<?php endif ?>
			<?php if ($school_parent_attendance): ?>
				<li>
				<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'attendance', 'student_id' => $student->ID ), $current_page_url ) ); ?>"><?php esc_html_e( 'Attendance Report', 'school-management' ); ?></a>
			</li>
			<?php endif ?>

		</ul>
	</div>
	<hr>
	<?php
}
?>
</div>
