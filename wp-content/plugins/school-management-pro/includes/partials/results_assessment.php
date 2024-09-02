<?php
defined( 'ABSPATH' ) || die();
?>
<thead>
	<tr>
		<th><?php esc_html_e( 'Exam Title', 'school-management' ); ?></th>
		<th><?php esc_html_e( 'Exam Date', 'school-management' ); ?></th>
		<th><?php esc_html_e( 'Maximum Marks', 'school-management' ); ?></th>
		<th><?php esc_html_e( 'Obtained Marks', 'school-management' ); ?></th>
		<th><?php esc_html_e( 'Percentage', 'school-management' ); ?></th>
	</tr>
</thead>
<tbody>
	<?php
	$overall_maximum_marks  = 0;
	$overall_obtained_marks = 0;
	foreach ( $admit_cards as $admit_card ) {
		$exam_id       = $admit_card->exam_id;
		$exam_title    = $admit_card->exam_title;
		$start_date    = $admit_card->start_date;
		$end_date      = $admit_card->end_date;
		$admit_card_id = $admit_card->ID;

		$exam_papers  = WLSM_M_Staff_Examination::get_exam_papers_by_admit_card( $school_id, $admit_card_id );
		$exam_results = WLSM_M_Staff_Examination::get_exam_results_by_admit_card( $school_id, $admit_card_id );

		$total_maximum_marks  = 0;
		$total_obtained_marks = 0;

		foreach ( $exam_papers as $key => $exam_paper ) {
			if ( $admit_card && isset( $exam_results[ $exam_paper->ID ] ) ) {
				$exam_result    = $exam_results[ $exam_paper->ID ];
				$obtained_marks = $exam_result->obtained_marks;
			} else {
				$obtained_marks = '';
			}

			$percentage = WLSM_Config::sanitize_percentage( $exam_paper->maximum_marks, WLSM_Config::sanitize_marks( $obtained_marks ) );

			$total_maximum_marks  += $exam_paper->maximum_marks;
			$total_obtained_marks += WLSM_Config::sanitize_marks( $obtained_marks );
		}

		$total_percentage = WLSM_Config::sanitize_percentage( $total_maximum_marks, $total_obtained_marks );

		$overall_maximum_marks  += $total_maximum_marks;
		$overall_obtained_marks += WLSM_Config::sanitize_marks( $total_obtained_marks );
	?>
	<tr>
		<td><?php echo esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ); ?></td>
		<td><?php echo esc_html( WLSM_Config::get_date_text( $start_date ) ); ?></td>
		<td><?php echo esc_html( $total_maximum_marks ); ?></td>
		<td><?php echo esc_html( $total_obtained_marks ); ?></td>
		<td><?php echo esc_html( WLSM_Config::get_percentage_text( $total_maximum_marks, $total_obtained_marks ) ); ?></td>
	</tr>
	<?php
	}
	?>
	<tr>
		<th colspan="2"><?php esc_html_e( 'Grand Total', 'school-management' ); ?></th>
		<th><?php echo esc_html( $overall_maximum_marks ); ?></th>
		<th><?php echo esc_html( $overall_obtained_marks ); ?></th>
		<th><?php echo esc_html( WLSM_Config::get_percentage_text( $overall_maximum_marks, $overall_obtained_marks ) ); ?></th>
	</tr>
</tbody>
