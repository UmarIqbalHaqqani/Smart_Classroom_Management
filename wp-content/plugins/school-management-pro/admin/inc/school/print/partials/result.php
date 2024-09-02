<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Setting.php';

if ( isset( $from_front ) ) {
	$print_button_classes = 'button btn-sm btn-success';
} else {
	$print_button_classes = 'btn btn-sm btn-success';
}

$grade_criteria = WLSM_Config::sanitize_grade_criteria( $exam->grade_criteria );

$enable_overall_grade = $grade_criteria['enable_overall_grade'];
$marks_grades         = $grade_criteria['marks_grades'];

$settings_dashboard                     = WLSM_M_Setting::get_settings_dashboard($school_id);
$school_enrollment_number = $settings_dashboard['school_enrollment_number'];
$school_admission_number  = $settings_dashboard['school_admission_number'];

$settings_url            = WLSM_M_Setting::get_settings_certificate_qcode_url( $school_id );
$school_result_url      = $settings_url['result_url'];
?>

<!-- Print exam results section. -->
<div class="wlsm-container wlsm" id="wlsm-print-exam-results">
	<div class="wlsm-print-exam-results-container">

		<?php

		// school header section. ---------------------------------------
		$school           = WLSM_M_School::fetch_school( $school_id );
		$settings_general = WLSM_M_Setting::get_settings_general( $school_id );
		$school_logo      = $settings_general['school_logo'];
		$school_signature = $settings_general['school_signature'];

		?>

		<!-- School header -->
		<div class="container-fluid">
			<div class="row wlsm-school-header justify-content-center">
				<div class="col-3 text-right">
					<?php if ( ! empty ( $school_logo ) ) { ?>
					<img src="<?php echo esc_url( wp_get_attachment_url( $school_logo ) ); ?>" class="wlsm-print-school-logo">
					<?php } ?>
				</div>
				<div class="col-6">
					<div class="wlsm-print-school-label">
						<?php echo esc_html( WLSM_M_School::get_label_text( $school->label ) ); ?>
					</div>
					<div class="wlsm-print-school-contact">
						<?php if ( $school->phone ) { ?>
						<span class="wlsm-print-school-phone">
							<span class="wlsm-font-bold">
								<?php esc_html_e( 'Phone:', 'school-management' ); ?>
							</span>
							<span><?php echo esc_html( WLSM_M_School::get_label_text( $school->phone ) ); ?></span>
						</span>
						<?php } ?>
						<?php if ( $school->email ) { ?>
						<span class="wlsm-print-school-email">
							<span class="wlsm-font-bold">
								| <?php esc_html_e( 'Email:', 'school-management' ); ?>
							</span>
							<span><?php echo esc_html( WLSM_M_School::get_phone_text( $school->email ) ); ?></span>
						</span>
						<br>
						<?php } ?>
						<?php if ( $school->address ) { ?>
						<span class="wlsm-print-school-address">
							<span class="wlsm-font-bold">
								<?php esc_html_e( 'Address:', 'school-management' ); ?>
							</span>
							<span><?php echo esc_html( WLSM_M_School::get_email_text( $school->address ) ); ?></span>
						</span>
						<?php } ?>
					</div>
				</div>
				<div class="col-3 text-right">
					<?php if ( ! empty ( $school_result_url ) ) { ?>
						<?php
							$qr_code_url            = $school_result_url.'?exam_roll_number='.WLSM_M_Staff_Class::get_roll_no_text( $result->roll_number ).'&id='.$result->exam_id;
							$field_output 	  = esc_url('https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . urlencode($qr_code_url) . '&choe=UTF-8');
							?>
							<?php if ($school_result_url): ?>
								<img src="<?php echo esc_url( $field_output); ?>" width="120px">
							<?php endif ?>
					<?php } ?>
				</div>
			</div>
		</div>

		<div class="wlsm-heading wlsm-exam-results-heading h5 wlsm-text-center">
		<div class="wlsm-exam-results-table-heading">
					<?php
					printf(
						wp_kses(
							/* translators: 1: exam title, 2: start date, 3: end date */
							__( '<span class="wlsm-font-bold">Exam: </span> %1$s (%2$s - %3$s)', 'school-management' ),
							array(
								'span' => array( 'class' => array() )
							)
						),
						esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ),
						esc_html( WLSM_Config::get_date_text( $start_date ) ),
						esc_html( WLSM_Config::get_date_text( $end_date ) )
					);
					?>
				</div>
		</div>

		<div class="row wlsm-student-details">
			<div class="col-md-12">
				<ul class="wlsm-list-group">
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e( 'Student Name', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $result->name ) ); ?></span>
					</li>
					<?php if ($school_enrollment_number): ?>
						<li>
							<span class="wlsm-font-bold"><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( WLSM_M_Staff_Class::get_roll_no_text( $result->enrollment_number ) ); ?></span>
						</li>
					<?php endif ?>
					<?php if ($school_admission_number): ?>
						<li>
							<span class="wlsm-font-bold"><?php esc_html_e( 'Admission Number', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( WLSM_M_Staff_Class::get_roll_no_text( $result->admission_number ) ); ?></span>
						</li>
					<?php endif ?>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e( 'Session', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Session::get_label_text( $result->session_label ) ); ?></span>
					</li>
					<li>
						<span class="wlsm-pr-3 pr-3">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Class', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( WLSM_M_Class::get_label_text( $result->class_label ) ); ?></span>
						</span>
						<span class="wlsm-pl-3 pl-3">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Section', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( WLSM_M_Class::get_label_text( $result->section_label ) ); ?></span>
						</span>
					</li>
					<li>
						<span class="wlsm-pr-3 pr-3">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Exam Roll Number', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( WLSM_M_Staff_Class::get_roll_no_text( $result->roll_number ) ); ?></span>
						</span>
						<span class="wlsm-pr-3 pr-3">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Father\'s Name', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( $result->father_name  ); ?></span>
						</span>
					</li>
				</ul>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="wlsm-exam-results-table-heading">
					<?php
					printf(
						wp_kses(
							/* translators: 1: exam title, 2: start date, 3: end date */
							__( '<span class="wlsm-font-bold">Exam Result:</span> %1$s (%2$s - %3$s)', 'school-management' ),
							array(
								'span' => array( 'class' => array() )
							)
						),
						esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ),
						esc_html( WLSM_Config::get_date_text( $start_date ) ),
						esc_html( WLSM_Config::get_date_text( $end_date ) )
					);
					?>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive w-100">
					<table class="table table-bordered wlsm-view-exam-results-table">
						<?php
					$show_marks_grades = count( $marks_grades );

					$student_rank = WLSM_M_Staff_Examination::calculate_exam_ranks( $school_id, $exam_id, array(), $result->admit_card_id );
					?>
					<thead>
						<tr>
							<th><?php esc_html_e( 'Paper Code', 'school-management' ); ?></th>
							<th><?php esc_html_e( 'Subject Name', 'school-management' ); ?></th>
							<th><?php esc_html_e( 'Subject Type', 'school-management' ); ?></th>
							<th><?php esc_html_e( 'Maximum Marks', 'school-management' ); ?></th>
							<th><?php esc_html_e( 'Obtained Marks', 'school-management' ); ?></th>
							<?php if ( $show_marks_grades ) { ?>
							<th><?php esc_html_e( 'Grade', 'school-management' ); ?></th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						$total_maximum_marks  = 0;
						$total_obtained_marks = 0;

						foreach ( $exam_papers as $key => $exam_paper ) {
							if ( $result && isset( $exam_results[ $exam_paper->ID ] ) ) {
								$exam_result    = $exam_results[ $exam_paper->ID ];
								$obtained_marks = $exam_result->obtained_marks;

							} else {
								$obtained_marks = '';
							}

							$percentage = WLSM_Config::sanitize_percentage( $exam_paper->maximum_marks, WLSM_Config::sanitize_marks( $obtained_marks ) );

							$teacher_remark = $exam_result->teacher_remark;
							$school_remark = $exam_result->school_remark;
							$p_scale = $exam_result->scale;

							$total_maximum_marks  += $exam_paper->maximum_marks;
							$total_obtained_marks += WLSM_Config::sanitize_marks( $obtained_marks );
						?>
						<tr>
							<td><?php echo esc_html( $exam_paper->paper_code ); ?></td>
							<td><?php echo esc_html( stripcslashes( $exam_paper->subject_label ) ); ?></td>
							<td><?php echo esc_html( WLSM_Helper::get_subject_type_text( $exam_paper->subject_type ) ); ?></td>
							<td><?php echo esc_html( $exam_paper->maximum_marks ); ?></td>
							<td><?php echo esc_html( $obtained_marks ); ?></td>
							<?php if ( $show_marks_grades ) { ?>
							<td><?php echo esc_html( WLSM_Helper::calculate_grade( $marks_grades, $percentage ) ); ?></td>
							<?php } ?>
						</tr>
						<?php
						}

						$total_percentage = WLSM_Config::sanitize_percentage( $total_maximum_marks, $total_obtained_marks );
						$p_scale = unserialize($p_scale);
						?>
						<tr>
							<th colspan="3"><?php esc_html_e( 'Total', 'school-management' ); ?></th>
							<th><?php echo esc_html( $total_maximum_marks ); ?></th>
							<th><?php echo esc_html( $total_obtained_marks ); ?></th>
							<?php if ( $show_marks_grades ) { ?>
							<th></th>
							<?php } ?>
						</tr>
						<tr>
							<th colspan="4"><?php esc_html_e( 'Percentage', 'school-management' ); ?></th>
							<th><?php echo esc_html( WLSM_Config::get_percentage_text( $total_maximum_marks, $total_obtained_marks ) ); ?></th>
							<?php if ( $show_marks_grades ) { ?>
							<th>
							<?php
							if ( $enable_overall_grade ) {
								echo esc_html( WLSM_Helper::calculate_grade( $marks_grades, $total_percentage ) );
							}
							?>
							</th>
							<?php } ?>
						</tr>
						<?php if ($show_rank=== '1'){ ?>
						<tr>
							<th colspan="4"><?php esc_html_e( 'Rank', 'school-management' ); ?></th>
							<th colspan="<?php echo esc_html( $show_marks_grades ? '2' : '1' ); ?>"><?php echo esc_html( $student_rank ); ?></th>
						</tr>
						<?php }?>

						<?php if ($show_eremark=== '1'){ ?>
						<tr>
							<td colspan="3"><strong><?php esc_html_e( 'Teacher Remark :', 'school-management' ); ?></strong>  <?php  echo $teacher_remark; ?></td>
							<td colspan="3"><strong><?php esc_html_e( 'School Remark :', 'school-management' ); ?></strong>  <?php  echo $school_remark; ?></td>
						</tr>
						<?php }?>

						<?php if ($psychomotor_enable === '1'): ?>

						<table class="table table-bordered wlsm-view-exam-results-table">
							<thead>
								<tr>
								<th colspan="10"> <?php esc_html_e( 'Psychomotor Analysis', 'school-management' ); ?></th>
								</tr>

							</thead>

							<tbody>
							<tr>
									<?php foreach ($psychomotor['psych'] as $key => $value): ?>
									<td><?php echo $value; ?></td>
									<?php endforeach ?>
								</tr>
							<tr>
								<?php foreach ($p_scale as $value): ?>
								<td> <?php echo $value; ?> </td>
								<?php endforeach ?>
							</tr>
							</tbody>
						</table>

						<?php endif ?>
					</tbody>
					</table>
					<?php if ($psychomotor_enable === '1'): ?>
					<table class="table table-bordered wlsm-view-exam-results-table">
					<thead>
						<tr>
						<th scope="col"><?php esc_html_e( 'Scale', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Definition', 'school-management' ); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php  $s = 1; ?>
					<?php foreach ($psychomotor['def'] as $key => $value): ?>
						<tr>
							<th scope="row"><?php echo $s++ ; ?></th>
							<td><?php echo $value; ?></td>
						</tr>
					<?php endforeach ?>

					</tbody>
					</table>
					<?php endif ?>
				</div>
			</div>
		</div>

	</div>
</div>
