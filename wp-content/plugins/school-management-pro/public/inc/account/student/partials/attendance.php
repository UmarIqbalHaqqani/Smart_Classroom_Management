<?php
defined('ABSPATH') || die();

$attendance = WLSM_M_Staff_General::get_student_attendance_report($student->ID);
?>
<div class="wlsm-content-area wlsm-section-attendance wlsm-student-attendance">
	<div class="wlsm-st-main-title">
		<span>
			<?php esc_html_e('Attendance Report', 'school-management'); ?>
		</span>
	</div>

	<?php require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/attendance_report.php'; ?>
</div>