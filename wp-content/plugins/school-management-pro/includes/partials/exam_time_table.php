<?php
defined( 'ABSPATH' ) || die();
global $wpdb;

// check if the user have permission to manage options.
    if ( ! current_user_can( 'manage_options' ) ) {

		// Checks if user is student.
		$user_id = get_current_user_id();
		$student = WLSM_M::get_student( $user_id );
		$student_id = $student->ID;
	} else {
		$student_id = $admit_card->student_id;
	}

	if ($student_id == null ) {
		$student_id = $admit_card->student_id;
	}

?>
<thead>
	<tr>
		<th class="text-nowrap"><?php esc_html_e( 'Subject', 'school-management' ); ?></th>
		<th class="text-nowrap"><?php esc_html_e( 'Paper Code', 'school-management' ); ?></th>
		<th class="text-nowrap"><?php esc_html_e( 'Date', 'school-management' ); ?></th>
		<th class="text-nowrap"><?php esc_html_e( 'Timing', 'school-management' ); ?></th>
		<?php if ( $exam->enable_room_numbers ) { ?>
		<th class="text-nowrap"><?php esc_html_e( 'Room No.', 'school-management' ); ?></th>
		<?php } ?>
	</tr>
</thead>
<tbody>
	<?php
	$exam_papers = WLSM_M_Staff_Examination::fetch_exam_papers_student( $school_id, $exam_id, $student_id );
	foreach ( $exam_papers as $key => $exam_paper ) {
		?>
	<tr>
		<td><?php echo esc_html( stripcslashes( $exam_paper->subject_label ) ); ?></td>
		<td><?php echo esc_html( $exam_paper->paper_code ); ?></td>
		<td><?php echo esc_html( WLSM_Config::get_date_text( $exam_paper->paper_date ) ); ?></td>
		<td><?php echo esc_html( WLSM_Config::get_time_text( $exam_paper->start_time ) ) . ' - ' . esc_html( WLSM_Config::get_time_text( $exam_paper->end_time ) ); ?></td>
		<?php if ( $exam->enable_room_numbers ) { ?>
		<td><?php echo esc_html( $exam_paper->room_number ); ?></td>
		<?php } ?>
	</tr>
		<?php
	}
	?>
</tbody>
<?php if ( ! empty( $exam->exam_center ) ) { ?>
<tfoot>
	<tr>
		<th><?php esc_html_e( 'Exam Center', 'school-management' ); ?></th>
		<td colspan="4"><?php echo esc_html( WLSM_M_Staff_Examination::get_exam_center_text( $exam->exam_center ) ); ?></td>
	</tr>
</tfoot>
<?php } ?>
