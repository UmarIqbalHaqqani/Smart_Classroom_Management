<?php
defined( 'ABSPATH' ) || die();
?>
<div class="wlsm-st-attendance-section table-responsive w-100 wlsm-w-100">
	<table class="wlsm-st-attendance-table table table-hover table-bordered wlsm-w-100 wlsm-text-left">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Month', 'school-management' ); ?></td>
				<th><?php esc_html_e( 'Total Attendance', 'school-management' ); ?></td>
				<th><?php esc_html_e( 'Total Present', 'school-management' ); ?></td>
				<th><?php esc_html_e( 'Total Absent', 'school-management' ); ?></td>
				<th><?php esc_html_e( 'Total Late', 'school-management' ); ?></td>
				<th><?php esc_html_e( 'Total Holidays', 'school-management' ); ?></td>
			</tr>
		</thead>
		<tbody>
			<?php

			$total_attendance = 0;
			$total_present    = 0;
			$total_absent     = 0;
			$total_days       = 0;
			$total_late       = 0;
			$total_holiday      = 0;
			foreach ( $attendance as $monthly ) {
				$month = new DateTime();
				$month->setDate( $monthly->year, $monthly->month, 1 );
				$total_attendance += $monthly->total_attendance;
				$total_days       += $monthly->total_days;
				$total_present    += $monthly->total_present;
				$total_absent     += $monthly->total_absent;
				$total_late       += $monthly->total_late;
				$total_holiday       += $monthly->total_holiday;
				?>
			<tr>
				<td><?php echo esc_html( $month->format( 'F Y' ) ); ?></td>
				<!-- <td><?php echo esc_html( $monthly->total_days ); ?></td> -->
				<td><?php echo esc_html( $monthly->total_days ); ?></td>
				<td><?php echo esc_html( $monthly->total_present ); ?></td>
				<td><?php echo esc_html( $monthly->total_absent ); ?></td>
				<td><?php echo esc_html( $monthly->total_late ); ?></td>
				<td><?php echo esc_html( $monthly->total_holiday ); ?></td>
			</tr>
				<?php
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<th><?php esc_html_e( 'Overall', 'school-management' ); ?></td>
				<th><?php echo esc_html( $total_days ); ?></td>
				<th><?php echo esc_html( $total_present ); ?></td>
				<th><?php echo esc_html( $total_absent ); ?></td>
				<th><?php echo esc_html( $total_late ); ?></td>
				<th><?php echo esc_html( $total_holiday ); ?></td>
			</tr>
		</tfoot>
	</table>
</div>


<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-view-attendance-form">
	<?php $nonce_action = 'view-attendance-student'; ?>
	<?php $nonce = wp_create_nonce( $nonce_action ); ?>
	<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">
	<input type="hidden" name="action" value="<?php echo esc_attr( 'wlsm-view-attendance' ); ?>">
	<input type="hidden" id="wlsm_class" name="class_id" value="<?php echo esc_attr( $student->class_id); ?>">
	<input type="hidden" id="wlsm_section" name="section_id" value="<?php echo esc_attr( $student->section_id ); ?>">
	<input type="hidden" id="wlsm_school_id" name="school_id" value="<?php echo esc_attr( $school_id ); ?>">
	<input type="hidden" id="wlsm_session_id" name="session_id" value="<?php echo esc_attr( $student->session_id ); ?>">
	<input type="hidden" id="wlsm_student_id" name="student_id" value="<?php echo esc_attr( $student->ID ); ?>">

	<div class="form-group col-md-4">
		<label for="wlsm_attendance_year_month" class="wlsm-font-bold">
			<span class="wlsm-important">*</span> <?php esc_html_e( 'Month', 'school-management' ); ?>:
		</label>
		<input type="text" name="year_month" class="form-control" id="wlsm_attendance_year_month" placeholder="<?php esc_attr_e( 'Month', 'school-management' ); ?>">
	</div>
	<br>
	<?php
	$class_id = $student->class_id;
	$subjects = WLSM_M_Staff_Class::get_class_subjects($school_id, $class_id);
	?>
	<div class="form-group col-md-4">
		<label for="wlsm_subject" class="wlsm-font-bold">
			<?php esc_html_e( 'Subject', 'school-management' ); ?>:
		</label>
		<select name="subject_id" class="form-control selectpicker" id="wlsm_subject" data-live-search="true" title="<?php esc_attr_e( 'All Subjects', 'school-management' ); ?>">
			<option value=""><?php esc_html_e( 'All Subject', 'school-management' ); ?></option>
			<?php foreach ($subjects as $subject): ?>
				<option value="<?php echo esc_attr($subject->ID); ?>"><?php esc_html_e( $subject->label, 'school-management' ); ?></option>
			<?php endforeach ?>
		</select>
	</div>

	<br>

	<button type="button" class="btn btn-sm btn-primary" id="wlsm-view-attendance-btn" data-nonce="<?php echo esc_attr( wp_create_nonce( 'view-attendance-student' ) ); ?>">
		<?php esc_html_e( 'View Attendance', 'school-management' ); ?>
	</button>

	<div class="wlsm-students-attendance mt-2"></div>

</form>
