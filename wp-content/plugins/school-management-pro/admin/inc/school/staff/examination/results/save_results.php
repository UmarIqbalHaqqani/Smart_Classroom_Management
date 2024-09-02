<?php
defined('ABSPATH') || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Examination.php';

$page_url         = WLSM_M_Staff_Examination::get_exams_page_url();
$results_page_url = WLSM_M_Staff_Examination::get_exams_results_page_url();

$school_id  = $current_school['id'];
$session_id = $current_session['ID'];
$remark = '';
$teacher_remark = '';
$school_remark = '';

$admit_card = NULL;
if (isset($_GET['id']) && !empty($_GET['id'])) {
	$admit_card_id = absint($_GET['id']);

	$admit_card = WLSM_M_Staff_Examination::fetch_admit_card($school_id, $admit_card_id);
}

if ($admit_card) {
	$exam = WLSM_M_Staff_Examination::fetch_exam($school_id, $admit_card->exam_id);

	$exam_id     = $exam->ID;
	$exam_title  = $exam->exam_title;
	$exam_center = $exam->exam_center;
	$start_date  = $exam->start_date;
	$end_date    = $exam->end_date;


	$psychomotor = WLSM_Config::sanitize_psychomotor( $exam->psychomotor );

	$exam_papers  = WLSM_M_Staff_Examination::get_exam_papers_by_admit_card($school_id, $admit_card_id);
	$exam_results = WLSM_M_Staff_Examination::get_exam_results_by_admit_card($school_id, $admit_card_id);

	$nonce_action = 'save-exam-results-' . $admit_card_id;
} else {
	$exam = NULL;

	if (isset($_GET['exam_id']) && !empty($_GET['exam_id'])) {
		$exam_id = absint($_GET['exam_id']);

		$exam = WLSM_M_Staff_Examination::fetch_exam($school_id, $exam_id);
	}

	if (!$exam) {
		die;
	}

	$exam_id     = $exam->ID;
	$exam_title  = $exam->exam_title;
	$exam_center = $exam->exam_center;
	$start_date  = $exam->start_date;
	$end_date    = $exam->end_date;

	$psychomotor = WLSM_Config::sanitize_psychomotor( $exam->psychomotor );

	$exam_papers = WLSM_M_Staff_Examination::get_exam_papers_by_exam_id($school_id, $exam_id);

	$admit_cards_ids = WLSM_M_Staff_Examination::get_exam_results_admit_cards_ids($school_id, $exam_id);

	$admit_cards = WLSM_M_Staff_Examination::get_exam_admit_cards($school_id, $exam_id);

	$admit_cards = array_filter($admit_cards, function ($record) use ($admit_cards_ids) {
		if (!in_array($record->ID, $admit_cards_ids)) {
			return true;
		}
		return false;
	});

	$nonce_action = 'save-exam-results';
}
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					printf(
						wp_kses(
							/* translators: 1: exam title, 2: start date, 3: end date */
							__('Exam: %1$s (%2$s - %3$s)', 'school-management'),
							array(
								'span' => array('class' => array())
							)
						),
						esc_html(WLSM_M_Staff_Examination::get_exam_label_text($exam_title)),
						esc_html(WLSM_Config::get_date_text($start_date)),
						esc_html(WLSM_Config::get_date_text($end_date))
					);
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url($results_page_url . '&action=results&exam_id=' . $exam_id); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-table"></i>&nbsp;
					<?php esc_html_e('View Exam Results', 'school-management'); ?>
				</a>
			</span>
		</div>

		<form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="POST" id="wlsm-save-exam-results-form" enctype="multipart/form-data" >

			<?php $nonce = wp_create_nonce($nonce_action); ?>
			<input type="hidden" name="<?php echo esc_attr($nonce_action); ?>" value="<?php echo esc_attr($nonce); ?>">

			<input type="hidden" name="action" value="wlsm-save-exam-results">

			<?php if ($admit_card) { ?>
				<input type="hidden" name="admit_card_id" value="<?php echo esc_attr($admit_card_id); ?>">
			<?php } ?>

			<div class="wlsm-form-section">
				<div class="row">
					<?php if ($admit_card) { ?>
						<div class="col-md-6">
							<div class="wlsm-form-sub-heading wlsm-font-bold"><?php esc_html_e('Student Details', 'school-management'); ?></div>

							<ul class="wlsm-exam-student-details">
								<li>
									<span class="wlsm-font-bold"><?php esc_html_e('Student Name', 'school-management'); ?>:</span>
									<span><?php echo esc_html(WLSM_M_Staff_Class::get_name_text($admit_card->name)); ?></span>
								</li>
								<li>
									<span class="wlsm-font-bold"><?php esc_html_e('Exam Roll Number', 'school-management'); ?>:</span>
									<span><?php echo esc_html(WLSM_M_Staff_Class::get_roll_no_text($admit_card->roll_number)); ?></span>
								</li>
								<li>
									<span class="wlsm-font-bold"><?php esc_html_e('Enrollment Number', 'school-management'); ?>:</span>
									<span><?php echo esc_html(WLSM_M_Staff_Class::get_roll_no_text($admit_card->roll_number)); ?></span>
								</li>
								<li>
									<span class="pr-3">
										<span class="wlsm-font-bold"><?php esc_html_e('Class', 'school-management'); ?>:</span>
										<span><?php echo esc_html(WLSM_M_Class::get_label_text($admit_card->class_label)); ?></span>
									</span>
									<span class="pl-3">
										<span class="wlsm-font-bold"><?php esc_html_e('Section', 'school-management'); ?>:</span>
										<span><?php echo esc_html(WLSM_M_Class::get_label_text($admit_card->section_label)); ?></span>
									</span>
								</li>
							</ul>
						</div>
					<?php } else { ?>
						<div class="col-md-6">
							<div class="form-group">
								<label for="wlsm_student" class="wlsm-font-bold">
									<?php esc_html_e('Select Student', 'school-management'); ?>:
								</label>
								<select name="student_admit_card_id" class="form-control selectpicker wlsm_students_subjects" id="wlsm_students" data-nonce="<?php echo esc_attr(wp_create_nonce('get-students-subjects')); ?>" data-nonce-subjects="<?php echo esc_attr(wp_create_nonce('get-students-subjects')); ?>"  data-live-search="true" data-none-selected-text="<?php esc_attr_e('Select Student', 'school-management'); ?>">
									<option value=""></option>
									<?php
									foreach ($admit_cards  as $record) {
									?>
										<option value="<?php echo esc_attr($record->ID); ?>">
											<?php
											printf(
												/* translators: 1: Student name, 2: Exam roll number */
												_x('%1$s (%2$s)', 'Student when adding exam results', 'school-management'),
												esc_html(WLSM_M_Staff_Class::get_name_text($record->name)),
												esc_html(WLSM_M_Staff_Class::get_roll_no_text($record->roll_number))
											);
											?>
										</option>
									<?php
									}
									?>
								</select>
							</div>
						</div>
					<?php } ?>

					<div class="col-md-6">
						<div class="wlsm-form-sub-heading wlsm-font-bold"><?php esc_html_e('Exam Details', 'school-management'); ?></div>

						<ul class="wlsm-exam-details">
							<li>
								<span class="wlsm-font-bold"><?php esc_html_e('Exam Title', 'school-management'); ?>:</span>
								<span><?php echo esc_html(WLSM_M_Staff_Examination::get_exam_label_text($exam_title)); ?></span>
							</li>
							<li>
								<span class="wlsm-font-bold"><?php esc_html_e('Exam Center', 'school-management'); ?>:</span>
								<span><?php echo esc_html(WLSM_M_Staff_Examination::get_exam_center_text($exam_center)); ?></span>
							</li>
							<li>
								<span class="wlsm-font-bold"><?php esc_html_e('Start Date', 'school-management'); ?>:</span>
								<span><?php echo esc_html(WLSM_Config::get_date_text($start_date)); ?></span>
							</li>
							<li>
								<span class="wlsm-font-bold"><?php esc_html_e('End Date', 'school-management'); ?>:</span>
								<span><?php echo esc_html(WLSM_Config::get_date_text($end_date)); ?></span>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php
							if ($admit_card) {
								esc_html_e('Update Exam Results', 'school-management');
							} else {
								esc_html_e('Add Exam Results', 'school-management');
							}
							?>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive w-100">
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th><?php esc_html_e('Paper Code', 'school-management'); ?></th>
										<th><?php esc_html_e('Subject Name', 'school-management'); ?></th>
										<th><?php esc_html_e('Subject Type', 'school-management'); ?></th>
										<th><?php esc_html_e('Maximum Marks', 'school-management'); ?></th>
										<th><?php esc_html_e('Obtained Marks', 'school-management'); ?></th>
										<th><?php esc_html_e('Remark', 'school-management'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($exam_papers as $key => $exam_paper) {
										if ($admit_card && isset($exam_results[$exam_paper->ID])) {
											$exam_result    = $exam_results[$exam_paper->ID];
											$obtained_marks = $exam_result->obtained_marks;
											$remark = $exam_result->remark;
											$teacher_remark = $exam_result->teacher_remark;
											$school_remark = $exam_result->school_remark;
											$scale = unserialize($exam_result->scale);
										} else {
											$obtained_marks = '';
										}
									?>
										<tr>
											<td><?php echo esc_html($exam_paper->paper_code); ?></td>
											<td><?php echo esc_html(stripcslashes($exam_paper->subject_label)); ?></td>
											<td><?php echo esc_html(WLSM_Helper::get_subject_type_text(strtolower($exam_paper->subject_type))); ?></td>
											<td><?php echo esc_html($exam_paper->maximum_marks); ?></td>
											<td>
												<input type="number" step="any" min="0" name="obtained_marks[<?php echo esc_attr($exam_paper->ID); ?>]" class="form-control" value="<?php echo esc_attr($obtained_marks); ?>">
											</td>
											<td>
												<input type="text"  name="remark[<?php echo esc_attr($exam_paper->ID); ?>]" class="form-control" value="<?php echo esc_attr($remark); ?>">
											</td>
										</tr>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>

					<!-- Student Remarks  -->
					<div class="row">
						<div class="col">
						<label for="wlsm_teacher_remark"><strong><?php esc_html_e('Teacher Remark', 'school-management'); ?>
						</strong></label>
							<input type="text" name="teacher_remark"  class="form-control" placeholder="Enter Remark" value="<?php echo $teacher_remark;?>">
						</div>

						<div class="col">
						<label for="wlsm_teacher_remark"><strong><?php esc_html_e('School Remark', 'school-management'); ?></strong></label>
							<input type="text" name="school_remark"  class="form-control" placeholder="Enter Remark" value="<?php echo $school_remark;?>">
						</div>

						<div class="col">
							<label for="wlsm_attachment" class="wlsm-font-bold">
								<?php esc_html_e( 'Upload Answer key', 'school-management' ); ?>:
							</label>
							<?php foreach ($exam_papers as $exam_paper): ?>
								<?php if ($admit_card && isset($exam_results[$exam_paper->ID])): ?>
								<?php $exam_result    = $exam_results[$exam_paper->ID];
									$attachment = $exam_result->answer_key;
								?>
								<?php if ( ! empty( $attachment ) ) { ?>
								<br>
								<a target="_blank" href="<?php echo esc_url( ( $attachment ) ); ?>" class="text-primary wlsm-font-bold wlsm-id-proof"><?php esc_html_e( 'Download Answer key', 'school-management' ); ?></a>
								<?php } ?>
								<?php endif ?>
							<?php endforeach ?>

							<div class="custom-file mb-3">
								<input type="file" id="wlsm_attachment" name="attachment">
							</div>
						</div>
					</div>
									<br>
					<div class="row">
						<div class="col-md-12">
							<div class="wlsm-form-sub-heading wlsm-font-bold">
								<?php esc_html_e('Psychomotor  Analysis', 'school-management'); ?>
							</div>
						</div>
						<div class="table-responsive w-100 col-md-6">
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th><?php esc_html_e('Psychmotor', 'school-management'); ?></th>
										<th><?php esc_html_e('Scale', 'school-management'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($psychomotor['psych'] as $key => $value) { ?>
										<tr>
											<td><?php echo esc_html($value); ?></td>
											<td>
												<input type="number" step="any" min="0" name="scale[<?php echo esc_attr($key); ?>]" class="form-control" value="<?php  if (isset($scale)) {
													echo esc_attr($scale[$key]);
												}?>">
											</td>
										</tr>
										<?php
										}
									?>

								</tbody>
							</table>
						</div>

						<div class="table-responsive w-100 col-md-6">
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th><?php esc_html_e('Scale', 'school-management'); ?></th>
										<th><?php esc_html_e('Definition', 'school-management'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$number = 1;
									foreach ($psychomotor['def'] as $key => $value) { ?>
										<tr>
											<td><?php echo esc_html($number++); ?></td>
											<td><?php echo esc_html($value); ?> </td>
										</tr>
										<?php } ?>
								</tbody>
							</table>
						</div>
					</div>

						<div class="row mt-2">
							<div class="col-md-12 text-center">
								<button type="submit" class="btn btn-primary" id="wlsm-save-exam-results-btn">
									<?php
									if ($admit_card) {
									?>
										<i class="fas fa-save"></i>&nbsp;
									<?php
										esc_html_e('Update Exam Results', 'school-management');
									} else {
									?>
										<i class="fas fa-plus"></i>&nbsp;
									<?php
										esc_html_e('Add Exam Results', 'school-management');
									}
									?>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>

		</form>

	</div>
</div>
