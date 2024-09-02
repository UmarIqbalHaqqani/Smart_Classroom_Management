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

<!-- Print exam results. -->
<div class="wlsm-container d-flex mb-2">
	<div class="col-md-12 wlsm-text-center">
		<br>
		<button type="button" class="<?php echo esc_attr( $print_button_classes ); ?>" id="wlsm-print-exam-results-btn" data-styles='["<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/bootstrap.min.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/wlsm-school-header.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/print/wlsm-exam-results.css' ); ?>"]' data-title="<?php
		printf(
			wp_kses(
				/* translators: 1: exam title, 2: start date, 3: end date */
				__( 'Exam: %1$s (%2$s - %3$s)', 'school-management' ),
				array(
					'span' => array( 'class' => array() )
				)
			),
			esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ),
			esc_html( WLSM_Config::get_date_text( $start_date ) ),
			esc_html( WLSM_Config::get_date_text( $end_date ) ) );
		?>"><?php esc_html_e( 'Print Exam Results', 'school-management' ); ?>
		</button>
	</div>
</div>

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
				<div class="col-9">
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
							$qr_code_url            = $school_result_url.'?exam_roll_number='.WLSM_M_Staff_Class::get_roll_no_text( $admit_card->roll_number ).'&id='.$admit_card->exam_id;
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
						<span><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $admit_card->name ) ); ?></span>
					</li>
					<?php if ($school_enrollment_number): ?>
						<li>
							<span class="wlsm-font-bold"><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( WLSM_M_Staff_Class::get_roll_no_text( $admit_card->enrollment_number ) ); ?></span>
						</li>
					<?php endif ?>
					<?php if ($school_admission_number): ?>
						<li>
							<span class="wlsm-font-bold"><?php esc_html_e( 'Admission Number', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( WLSM_M_Staff_Class::get_roll_no_text( $admit_card->admission_number ) ); ?></span>
						</li>
					<?php endif ?>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e( 'Session', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Session::get_label_text( $admit_card->session_label ) ); ?></span>
					</li>
					<li>
						<span class="wlsm-pr-3 pr-3">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Class', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( WLSM_M_Class::get_label_text( $admit_card->class_label ) ); ?></span>
						</span>
						<span class="wlsm-pl-3 pl-3">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Section', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( WLSM_M_Class::get_label_text( $admit_card->section_label ) ); ?></span>
						</span>
					</li>
					<li>
						<span class="wlsm-pr-3 pr-3">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Exam Roll Number', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( WLSM_M_Staff_Class::get_roll_no_text( $admit_card->roll_number ) ); ?></span>
						</span>
						<span class="wlsm-pr-3 pr-3">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Father\'s Name', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( $admit_card->father_name  ); ?></span>
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
						<?php require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/exam_results.php'; ?>
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

		<div class="row">
			<?php if ($school_signature): ?>
			<div class="col">
				<div class="text-left">
					<?php if ( ! empty( $school_signature ) ) { ?>
						<img src="<?php echo esc_url( wp_get_attachment_url( $school_signature ) ); ?>" class="" width="50%" >
					<?php } ?>
					<br>
					<span><?php esc_html_e( 'Principal Signature', 'school-management' ); ?></span>
				</div>
			</div>
			<?php endif ?>


			<?php if ($teacher_signature): ?>
				<div class="col">
					<div class="text-right">
						<?php if ( ! empty( $teacher_signature ) ) { ?>
							<img src="<?php echo esc_url( wp_get_attachment_url( $teacher_signature ) ); ?>" class="" width="50%" >
						<?php } ?>
						<br>
						<span><?php esc_html_e( 'Class Teacher Signature', 'school-management' ); ?></span>
					</div>
				</div>
			<?php endif ?>

		</div>

	</div>
</div>
