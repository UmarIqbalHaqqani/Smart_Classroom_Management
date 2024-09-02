<?php
defined('ABSPATH') || die();

global $wpdb;

$page_url             = WLSM_M_Staff_Examination::get_exams_page_url();
$admit_cards_page_url = WLSM_M_Staff_Examination::get_exams_admit_cards_page_url();

$school_id = $current_school['id'];

$exam = NULL;

$nonce_action = 'add-exam';

$exam_title  = '';
$exam_center = '';
$start_date  = '';
$end_date    = '';
$exam_group  = array();
$is_active   = 1;
$show_rank   = 1;
$show_remark = 1;
$show_eremark = 1;
$psychomotor_analysis = 1;

$enable_room_numbers    = 0;
$enable_total_marks     = 1;
$results_obtained_marks = 1;
$results_published      = 0;
$admit_cards_published  = 0;
$time_table_published   = 0;
$show_in_assessment     = 1;
$grade_criteria = WLSM_Config::get_default_grade_criteria();

$exam_classes = array();
$exam_papers  = array();

$admit_cards_count = 0;

$exam_groups = WLSM_M_Staff_Examination::fetch_exams_groups($school_id);
if (isset($_GET['id']) && !empty($_GET['id'])) {
	$id   = absint($_GET['id']);
	$exam = WLSM_M_Staff_Examination::fetch_exam($school_id, $id);

	if ($exam) {
		$nonce_action = 'edit-exam-' . $exam->ID;
		$exam_group = array();
		$exam_title  = $exam->exam_title;
		$exam_center = $exam->exam_center;
		$start_date  = $exam->start_date;
		$end_date    = $exam->end_date;
		$exam_group  = explode(' ', $exam->exam_group);
		$is_active   = $exam->is_active;

		$enable_room_numbers   = $exam->enable_room_numbers;
		$results_published     = $exam->results_published;
		$admit_cards_published = $exam->admit_cards_published;
		$time_table_published  = $exam->time_table_published;
		$show_in_assessment    = $exam->show_in_assessment;

		$enable_total_marks    = $exam->enable_total_marks;
		$results_obtained_marks = $exam->results_obtained_marks;

		$show_rank   = $exam->show_rank;
		$show_remark = $exam->show_remark;
		$show_eremark = $exam->show_eremark;
		$psychomotor_analysis = $exam->psychomotor_analysis;

		$grade_criteria = WLSM_Config::sanitize_grade_criteria($exam->grade_criteria);
		$psychomotor = WLSM_Config::sanitize_psychomotor($exam->psychomotor);

		$exam_classes = WLSM_M_Staff_Examination::fetch_exam_classes($school_id, $id);
		$exam_papers  = WLSM_M_Staff_Examination::fetch_exam_papers($school_id, $id);

		$admit_cards_count = WLSM_M_Staff_Examination::exam_admit_cards_count($school_id, $id);
	}
}

$classes = WLSM_M_Staff_Class::fetch_classes($school_id);

$subject_types = WLSM_Helper::subject_type_list();
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					if ($exam) {
						printf(
							wp_kses(
								/* translators: 1: exam title, 2: start date, 3: end date */
								__('Edit Exam: %1$s (%2$s - %3$s)', 'school-management'),
								array(
									'span' => array('class' => array())
								)
							),
							esc_html(WLSM_M_Staff_Examination::get_exam_label_text($exam_title)),
							esc_html(WLSM_Config::get_date_text($start_date)),
							esc_html(WLSM_Config::get_date_text($end_date))
						);
					} else {
						esc_html_e('Add New Exam', 'school-management');
					}
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url($page_url); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-clock"></i>&nbsp;
					<?php esc_html_e('View All', 'school-management'); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="wlsm-save-exam-form">

			<?php $nonce = wp_create_nonce($nonce_action); ?>
			<input type="hidden" name="<?php echo esc_attr($nonce_action); ?>" value="<?php echo esc_attr($nonce); ?>">

			<input type="hidden" name="action" value="wlsm-save-exam">

			<?php if ($exam) { ?>
				<input type="hidden" name="exam_id" value="<?php echo esc_attr($exam->ID); ?>">
			<?php } ?>

			<!-- Exam Detail -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e('Exam Detail', 'school-management'); ?>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="wlsm_exam_title" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Exam Title', 'school-management'); ?>:
						</label>
						<input type="text" name="label" class="form-control" id="wlsm_exam_title" placeholder="<?php esc_attr_e('Enter exam title', 'school-management'); ?>" value="<?php echo esc_attr(stripslashes($exam_title)); ?>">
					</div>
					<div class="form-group col-md-6">
						<label for="wlsm_exam_center" class="wlsm-font-bold">
							<?php esc_html_e('Exam Center', 'school-management'); ?>:
						</label>
						<input type="text" name="exam_center" class="form-control" id="wlsm_exam_center" placeholder="<?php esc_attr_e('Enter exam center', 'school-management'); ?>" value="<?php echo esc_attr(stripslashes($exam_center)); ?>">
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="wlsm_start_date" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Start Date', 'school-management'); ?>:
						</label>
						<input type="text" name="start_date" class="form-control" id="wlsm_start_date" placeholder="<?php esc_attr_e('Enter start date', 'school-management'); ?>" value="<?php echo esc_attr(WLSM_Config::get_date_text($start_date)); ?>">
					</div>
					<div class="form-group col-md-6">
						<label for="wlsm_end_date" class="wlsm-font-bold">
							<?php esc_html_e('End Date', 'school-management'); ?>:
						</label>
						<input type="text" name="end_date" class="form-control" id="wlsm_end_date" placeholder="<?php esc_attr_e('End end date', 'school-management'); ?>" value="<?php echo esc_attr(WLSM_Config::get_date_text($end_date)); ?>">
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="wlsm_classes" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Class', 'school-management'); ?>:
						</label>
						<select name="classes[]" class="form-control selectpicker" id="wlsm_class_exam" data-actions-box="true" data-none-selected-text="<?php esc_attr_e('Select', 'school-management'); ?>">
							<option value=""><?php esc_attr_e('Select Class', 'school-management'); ?></option>
							<?php foreach ($classes as $class) { ?>
								<option <?php selected(in_array($class->ID, $exam_classes), true, true); ?> value="<?php echo esc_attr($class->ID); ?>">
									<?php echo esc_html(WLSM_M_Class::get_label_text($class->label)); ?>
								</option>
							<?php } ?>
						</select>
						<p><?php esc_html_e('You can assign create this exam for single class.', 'school-management'); ?></p>
					</div>
					<div class="form-group col-md-6">
						<label for="wlsm_exam_group" class="wlsm-font-bold">
							<?php esc_html_e('Exam Group', 'school-management'); ?>:
						</label>
						<select name="exam_group" class="form-control selectpicker" id="wlsm_exam_group" data-actions-box="true" data-none-selected-text="<?php esc_attr_e('Select', 'school-management'); ?>">
							<?php foreach ($exam_groups as $group) { ?>
								<option <?php selected(in_array($group->ID, $exam_group), true, true); ?> value="<?php echo esc_attr($group->ID); ?>">
									<?php echo esc_html(WLSM_M_Class::get_label_text($group->label)); ?>
								</option>
							<?php } ?>
						</select>

						<p><?php esc_html_e('You can group exams. Make sure it has the exact same name as in other exam with same group (Example: "1st Semester", "2nd Semester" etc.). Also, this will show up in subject-wise exam results.', 'school-management'); ?></p>
					</div>
				</div>
			</div>

			<!-- Exam Papers Detail -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold pb-0">
							<?php esc_html_e('Exam Papers', 'school-management'); ?>
						</div>
					</div>
				</div>

				<div class="wlsm-exam-papers-box" data-subject-name="<?php esc_attr_e('Subject Name', 'school-management'); ?>" data-subject-name-placeholder="<?php esc_attr_e('Enter subject name', 'school-management'); ?>" data-room-number="<?php esc_attr_e('Room Number', 'school-management'); ?>" data-room-number-placeholder="<?php esc_attr_e('Enter room number', 'school-management'); ?>" data-subject-type="<?php esc_attr_e('Subject Type', 'school-management'); ?>" data-maximum-marks="<?php esc_attr_e('Maximum Marks', 'school-management'); ?>" data-maximum-marks-placeholder="<?php esc_attr_e('Enter maximum marks', 'school-management'); ?>" data-paper-code="<?php esc_attr_e('Paper Code / Subject Code', 'school-management'); ?>" data-paper-code-placeholder="<?php esc_attr_e('Exam paper code', 'school-management'); ?>" data-paper-date="<?php esc_attr_e('Paper Date', 'school-management'); ?>" data-paper-date-placeholder="<?php esc_attr_e('Exam paper date', 'school-management'); ?>" data-start-time="<?php esc_attr_e('Start Time', 'school-management'); ?>" data-start-time-placeholder="<?php esc_attr_e('Exam paper start time', 'school-management'); ?>" data-end-time="<?php esc_attr_e('End Time', 'school-management'); ?>" data-end-time-placeholder="<?php esc_attr_e('Exam paper end time', 'school-management'); ?>" data-subject-types="<?php echo esc_attr(json_encode($subject_types)); ?>">

					<?php
					if (count($exam_papers)) {
						foreach ($exam_papers as $key => $exam_paper) {
							$index = $key + 1;
					?>
							<div class="wlsm-exam-paper-box card col" data-exam-paper="<?php echo esc_attr($index); ?>">
								<button type="button" class="btn btn-sm btn-danger wlsm-remove-exam-paper-btn"><i class="fas fa-times"></i></button>

								<input type="hidden" name="paper_id[]" value="<?php echo esc_attr($exam_paper->ID); ?>">
								<input type="hidden" name="subject_id[]" value="<?php echo esc_attr($exam_paper->subject_id); ?>">

								<div class="form-row">
									<div class="form-group col-sm-6 col-md-4">
										<label for="wlsm_subject_label_<?php echo esc_attr($index); ?>" class="wlsm-font-bold">
											<?php esc_html_e('Subject Name', 'school-management'); ?>:
										</label>
										<input type="text" name="subject_label[]" class="form-control" id="wlsm_subject_label_<?php echo esc_attr($index); ?>" placeholder="<?php esc_attr_e('Enter subject name', 'school-management'); ?>" value="<?php echo esc_attr(stripcslashes($exam_paper->subject_label)); ?>">
									</div>
									<div class="form-group col-sm-6 col-md-3">
										<label for="wlsm_subject_type_<?php echo esc_attr($index); ?>" class="wlsm-font-bold">
											<?php esc_html_e('Subject Type', 'school-management'); ?>:
										</label>
										<select name="subject_type[]" class="form-control selectpicker wlsm_subject_type_selectpicker" id="wlsm_subject_type_<?php echo esc_attr($index); ?>">
											<?php foreach ($subject_types as $key => $value) { ?>
												<option <?php selected(strtolower($exam_paper->subject_type), $key, true); ?> value="<?php echo esc_attr($key); ?>">
													<?php echo esc_html($value); ?>
												</option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group col-sm-6 col-md-2">
										<label for="wlsm_maximum_marks_<?php echo esc_attr($index); ?>" class="wlsm-font-bold">
											<?php esc_html_e('Maximum Marks', 'school-management'); ?>:
										</label>
										<input type="number" step="1" min="1" name="maximum_marks[]" class="form-control" id="wlsm_maximum_marks_<?php echo esc_attr($index); ?>" placeholder="<?php esc_attr_e('Enter maximum marks', 'school-management'); ?>" value="<?php echo esc_attr($exam_paper->maximum_marks); ?>">
									</div>
									<div class="form-group col-sm-6 col-md-3">
										<label for="wlsm_paper_code_<?php echo esc_attr($index); ?>" class="wlsm-font-bold">
											<?php esc_html_e('Paper Code / Subject Code', 'school-management'); ?>:
										</label>
										<input type="text" name="paper_code[]" class="form-control" id="wlsm_paper_code_<?php echo esc_attr($index); ?>" placeholder="<?php esc_attr_e('Exam paper code', 'school-management'); ?>" value="<?php echo esc_attr($exam_paper->paper_code); ?>">
									</div>
									<div class="form-group col-sm-6 col-md-3">
										<label for="wlsm_paper_date_<?php echo esc_attr($index); ?>" class="wlsm-font-bold">
											<?php esc_html_e('Paper Date', 'school-management'); ?>:
										</label>
										<input type="text" name="paper_date[]" class="form-control wlsm_paper_date" id="wlsm_paper_date_<?php echo esc_attr($index); ?>" placeholder="<?php esc_attr_e('Exam paper date', 'school-management'); ?>" value="<?php echo esc_attr(WLSM_Config::get_date_text($exam_paper->paper_date)); ?>">
									</div>
									<div class="form-group col-sm-6 col-md-3">
										<label for="wlsm_start_time_<?php echo esc_attr($index); ?>" class="wlsm-font-bold">
											<?php esc_html_e('Start Time', 'school-management'); ?>:
										</label>
										<input type="text" name="start_time[]" class="form-control wlsm_paper_time" id="wlsm_start_time_<?php echo esc_attr($index); ?>" placeholder="<?php esc_attr_e('Exam paper start time', 'school-management'); ?>" value="<?php echo esc_attr(WLSM_Config::get_time_text($exam_paper->start_time)); ?>">
									</div>
									<div class="form-group col-sm-6 col-md-3">
										<label for="wlsm_end_time_<?php echo esc_attr($index); ?>" class="wlsm-font-bold">
											<?php esc_html_e('End Time', 'school-management'); ?>:
										</label>
										<input type="text" name="end_time[]" class="form-control wlsm_paper_time" id="wlsm_end_time_<?php echo esc_attr($index); ?>" placeholder="<?php esc_attr_e('Exam paper end time', 'school-management'); ?>" value="<?php echo esc_attr(WLSM_Config::get_time_text($exam_paper->end_time)); ?>">
									</div>
									<div class="form-group col-sm-6 col-md-3">
										<label for="wlsm_room_number_<?php echo esc_attr($index); ?>" class="wlsm-font-bold">
											<?php esc_html_e('Room Number', 'school-management'); ?>:
										</label>
										<input type="text" name="room_number[]" class="form-control" id="wlsm_room_number_<?php echo esc_attr($index); ?>" placeholder="<?php esc_attr_e('Exam room number', 'school-management'); ?>" value="<?php echo esc_attr($exam_paper->room_number); ?>">
									</div>
								</div>
							</div>
						<?php
						}
					} else {
						?>
						<div class="wlsm-exam-paper-box card col" data-exam-paper="1">
							<button type="button" class="btn btn-sm btn-danger wlsm-remove-exam-paper-btn"><i class="fas fa-times"></i></button>

							<div class="form-row">
								<div class="form-group col-sm-6 col-md-4">
									<label for="wlsm_subject_label" class="wlsm-font-bold">
										<?php esc_html_e('Subject Name', 'school-management'); ?>:
									</label>
									<input type="text" name="subject_label[]" class="form-control" id="wlsm_subject_label" placeholder="<?php esc_attr_e('Enter subject name', 'school-management'); ?>" value="">
								</div>
								<div class="form-group col-sm-6 col-md-3">
									<label for="wlsm_subject_type" class="wlsm-font-bold">
										<?php esc_html_e('Subject Type', 'school-management'); ?>:
									</label>
									<select name="subject_type[]" class="form-control selectpicker wlsm_subject_type_selectpicker" id="wlsm_subject_type">
										<?php foreach ($subject_types as $key => $value) { ?>
											<option value="<?php echo esc_attr($key); ?>">
												<?php echo esc_html($value); ?>
											</option>
										<?php } ?>
									</select>
								</div>
								<div class="form-group col-sm-6 col-md-2">
									<label for="wlsm_maximum_marks" class="wlsm-font-bold">
										<?php esc_html_e('Maximum Marks', 'school-management'); ?>:
									</label>
									<input type="number" step="1" min="1" name="maximum_marks[]" class="form-control" id="wlsm_maximum_marks" placeholder="<?php esc_attr_e('Enter maximum marks', 'school-management'); ?>" value="">
								</div>
								<div class="form-group col-sm-6 col-md-3">
									<label for="wlsm_paper_code" class="wlsm-font-bold">
										<?php esc_html_e('Paper Code / Subject Code', 'school-management'); ?>:
									</label>
									<input type="text" name="paper_code[]" class="form-control" id="wlsm_paper_code" placeholder="<?php esc_attr_e('Exam paper code', 'school-management'); ?>" value="">
								</div>
								<div class="form-group col-sm-6 col-md-3">
									<label for="wlsm_paper_date" class="wlsm-font-bold">
										<?php esc_html_e('Paper Date', 'school-management'); ?>:
									</label>
									<input type="text" name="paper_date[]" class="form-control wlsm_paper_date" id="wlsm_paper_date" placeholder="<?php esc_attr_e('Exam paper date', 'school-management'); ?>" value="">
								</div>
								<div class="form-group col-sm-6 col-md-3">
									<label for="wlsm_start_time" class="wlsm-font-bold">
										<?php esc_html_e('Start Time', 'school-management'); ?>:
									</label>
									<input type="text" name="start_time[]" class="form-control wlsm_paper_time" id="wlsm_start_time" placeholder="<?php esc_attr_e('Exam paper start time', 'school-management'); ?>" value="">
								</div>
								<div class="form-group col-sm-6 col-md-3">
									<label for="wlsm_end_time" class="wlsm-font-bold">
										<?php esc_html_e('End Time', 'school-management'); ?>:
									</label>
									<input type="text" name="end_time[]" class="form-control wlsm_paper_time" id="wlsm_end_time" placeholder="<?php esc_attr_e('Exam paper end time', 'school-management'); ?>" value="">
								</div>
								<div class="form-group col-sm-6 col-md-3">
									<label for="wlsm_room_number" class="wlsm-font-bold">
										<?php esc_html_e('Room Number', 'school-management'); ?>:
									</label>
									<input type="text" name="room_number[]" class="form-control" id="wlsm_room_number" placeholder="<?php esc_attr_e('Exam room number', 'school-management'); ?>" value="">
								</div>
							</div>
						</div>
					<?php
					}
					?>
				</div>

				<div class="form-row mt-3">
					<div class="col-md-12 text-center">
						<button type="button" class="btn btn-sm btn-outline-primary wlsm-add-exam-paper-btn">
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php esc_html_e('Add More', 'school-management'); ?>
						</button>
					</div>
				</div>
			</div>

			<!-- Grade Criteria -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-8">
						<div class="row">
							<div class="col-md-12">
								<div class="wlsm-form-sub-heading wlsm-font-bold">
									<?php esc_html_e('Grade Criteria', 'school-management'); ?>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive table-responsive-sm">
									<table class="table table-sm table-bordered table-striped w-100 wlsm-grade-criteria">
										<thead>
											<tr class="wlsm-font-bold">
												<td><?php esc_html_e('Percentage >=', 'school-management'); ?></td>
												<td><?php esc_html_e('Percentage <=', 'school-management'); ?></td>
												<td><?php esc_html_e('Assign Grade', 'school-management'); ?></td>
												<td><?php esc_html_e('Action', 'school-management'); ?></td>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($grade_criteria['marks_grades'] as $mark_grade) { ?>
												<tr>
													<td><input type="number" step="1" min="0" name="grade_criteria[min][]" value="<?php echo esc_attr($mark_grade['min']); ?>"></td>
													<td><input type="number" step="1" min="1" name="grade_criteria[max][]" value="<?php echo esc_attr($mark_grade['max']); ?>"></td>
													<td><input type="text" name="grade_criteria[grade][]" value="<?php echo esc_attr($mark_grade['grade']); ?>"></td>
													<td><span class="wlsm-grade-criteria-remove text-danger dashicons dashicons-no"></span></td>
												</tr>
											<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<th class="border-0"></th>
												<th class="border-0"></th>
												<th class="border-0"></th>
												<th><span class="wlsm-grade-criteria-add text-primary dashicons dashicons-plus"></span></th>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="row">
							<div class="col-md-12">
								<div class="wlsm-form-sub-heading wlsm-font-bold">
									<?php esc_html_e('Overall Grade', 'school-management'); ?>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="form-check form-check-inline">
										<input <?php checked($grade_criteria['enable_overall_grade'], true, true); ?> class="form-check-input" type="radio" name="enable_overall_grade" id="wlsm_enable_overall_grade_1" value="1">
										<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_enable_overall_grade_1">
											<?php esc_html_e('Enable', 'school-management'); ?>
										</label>
									</div>
									<div class="form-check form-check-inline">
										<input <?php checked($grade_criteria['enable_overall_grade'], false, true); ?> class="form-check-input" type="radio" name="enable_overall_grade" id="wlsm_enable_overall_grade_0" value="0">
										<label class="ml-1 form-check-label text-secondary font-weight-bold" for="wlsm_enable_overall_grade_0">
											<?php esc_html_e('Disable', 'school-management'); ?>
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- psych monitor -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-6">

						<div class="col-md-12">
							<div class="wlsm-form-sub-heading wlsm-font-bold">
								<?php esc_html_e('Psych Monitor', 'school-management'); ?>
							</div>
						</div>

						<div class="col-md-12">
							<div class="table-responsive table-responsive">
								<table class="table table-sm table-bordered table-striped wlsm-psych-criteria">
									<thead>
										<tr class="wlsm-font-bold">
											<td><?php esc_html_e('Psychmotor', 'school-management'); ?></td>
											<td><?php esc_html_e('Action', 'school-management'); ?></td>
										</tr>
									</thead>
									<tbody>
										<?php
										if (!empty($psychomotor) && is_array($psychomotor)) {
											foreach ($psychomotor['psych'] as $key => $value) :
										?>
												<tr>
													<td><input type="text" name="psych[]" value="<?php echo $value; ?>" placeholder="Example: Attitude"></td>
													<td><span class="wlsm-psych-criteria-remove text-danger dashicons dashicons-no"></span></td>
												</tr>
											<?php endforeach ?>
										<?php } ?>

									</tbody>
									<tfoot>
										<tr>
											<th class="border-0"></th>
											<th><span class="wlsm-psych-criteria-add text-primary dashicons dashicons-plus"></span></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
					<div class="col-md-6">

						<div class="col-md-12">
							<div class="wlsm-form-sub-heading wlsm-font-bold">
								<?php esc_html_e('Psych Scale', 'school-management'); ?>
							</div>
						</div>

						<div class="col-md-12">
							<div class="table-responsive table-responsive">
								<table class="table table-sm table-bordered table-striped wlsm-psych-scale">
									<thead>
										<tr class="wlsm-font-bold">
											<td><?php esc_html_e('Scale Number', 'school-management'); ?></td>
											<td><?php esc_html_e('Definition', 'school-management'); ?></td>
											<td><?php esc_html_e('Action', 'school-management'); ?></td>
										</tr>
									</thead>
									<tbody>
										<?php

										if (!empty($psychomotor) && is_array($psychomotor)) {
											$scale = 1;
											foreach ($psychomotor['def'] as $value) : ?>
												<tr>
													<td><input type="number" name="scale[]" value="<?php echo esc_attr($scale++); ?>" placeholder="Example : 1"></td>
													<td><input type="text" name="def[]" value="<?php echo esc_attr($value); ?>" placeholder="Example : Good"></td>
													<td><span class="wlsm-psych-scale-remove text-danger dashicons dashicons-no"></span></td>
												</tr>
											<?php endforeach ?>
										<?php } ?>

									</tbody>
									<tfoot>
										<tr>
											<th class="border-0"></th>
											<th class="border-0"></th>
											<th><span class="wlsm-psych-scale-add text-primary dashicons dashicons-plus"></span></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php if ($exam) { ?>
				<!-- Actions -->
				<div class="wlsm-form-section">
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<div class="wlsm-form-sub-heading wlsm-font-bold">
										<?php esc_html_e('Actions', 'school-management'); ?>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<ul class="list-group list-group-flush">
										<li class="list-group-item wlsm-font-large">
											<span class="wlsm-font-bold">
												<?php esc_html_e('Total Admit Cards', 'school-management'); ?>:
											</span>
											<span>
												<?php
												echo esc_html($admit_cards_count);
												if ($admit_cards_count > 0) {
												?>
													<small class="text-primary ml-1 wlsm-font-bold">
														<a href="<?php echo esc_url($admit_cards_page_url . '&action=admit_cards&exam_id=' . $id); ?>"><?php esc_html_e('View All', 'school-management'); ?></a>
													</small>
												<?php
												}
												?>
											</span>
										</li>
										<li class="list-group-item">
											<?php if ($admit_cards_count > 0) { ?>
												<a class="btn btn-sm btn-outline-primary" href="<?php echo esc_url($admit_cards_page_url . '&action=admit_cards&exam_id=' . $id); ?>">
													<i class="fas fa-id-card"></i>&nbsp;
													<?php esc_html_e('Manage Admit Cards', 'school-management'); ?>
												</a>
											<?php } else { ?>
												<a class="btn btn-sm btn-outline-primary" href="<?php echo esc_url($admit_cards_page_url . '&action=generate_admit_cards&exam_id=' . $id); ?>">
													<i class="fas fa-id-card"></i>&nbsp;
													<?php esc_html_e('Generate Admit Cards', 'school-management'); ?>
												</a>
											<?php } ?>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>

			<!-- Admit Cards & Time Table -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-6">
								<div class="wlsm-form-sub-heading wlsm-font-bold">
									<?php esc_html_e('Admit Cards', 'school-management'); ?>
								</div>
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-12">
								<div class="form-check form-check-inline">
									<input <?php checked(1, $admit_cards_published, true); ?> class="form-check-input" type="radio" name="admit_cards_published" id="wlsm_admit_cards_published" value="1">
									<label class="ml-1 form-check-label text-success font-weight-bold" for="wlsm_admit_cards_published">
										<?php echo esc_html(WLSM_M_Staff_Examination::get_published_text()); ?>
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input <?php checked(0, $admit_cards_published, true); ?> class="form-check-input" type="radio" name="admit_cards_published" id="wlsm_admit_cards_unpublished" value="0">
									<label class="ml-1 form-check-label text-secondary font-weight-bold" for="wlsm_admit_cards_unpublished">
										<?php echo esc_html(WLSM_M_Staff_Examination::get_unpublished_text()); ?>
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div class="wlsm-form-sub-heading wlsm-font-bold">
									<?php esc_html_e('Time Table', 'school-management'); ?>
								</div>
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-12">
								<div class="form-check form-check-inline">
									<input <?php checked(1, $time_table_published, true); ?> class="form-check-input" type="radio" name="time_table_published" id="wlsm_time_table_published" value="1">
									<label class="ml-1 form-check-label text-success font-weight-bold" for="wlsm_time_table_published">
										<?php echo esc_html(WLSM_M_Staff_Examination::get_published_text()); ?>
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input <?php checked(0, $time_table_published, true); ?> class="form-check-input" type="radio" name="time_table_published" id="wlsm_time_table_unpublished" value="0">
									<label class="ml-1 form-check-label text-secondary font-weight-bold" for="wlsm_time_table_unpublished">
										<?php echo esc_html(WLSM_M_Staff_Examination::get_unpublished_text()); ?>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Room Numbers & Exam Results -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div class="wlsm-form-sub-heading wlsm-font-bold">
									<?php esc_html_e('Room Numbers', 'school-management'); ?>
								</div>
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-12">
								<div class="form-check form-check-inline">
									<input <?php checked(1, $enable_room_numbers, true); ?> class="form-check-input" type="radio" name="enable_room_numbers" id="wlsm_room_numbers_enabled" value="1">
									<label class="ml-1 form-check-label text-success font-weight-bold" for="wlsm_room_numbers_enabled">
										<?php echo esc_html(WLSM_M_Staff_Examination::get_enabled_text()); ?>
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input <?php checked(0, $enable_room_numbers, true); ?> class="form-check-input" type="radio" name="enable_room_numbers" id="wlsm_room_numbers_disabled" value="0">
									<label class="ml-1 form-check-label text-secondary font-weight-bold" for="wlsm_room_numbers_disabled">
										<?php echo esc_html(WLSM_M_Staff_Examination::get_disabled_text()); ?>
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div class="wlsm-form-sub-heading wlsm-font-bold">
									<?php esc_html_e('Exam Results', 'school-management'); ?>
								</div>
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-12">
								<div class="form-check form-check-inline">
									<input <?php checked(1, $results_published, true); ?> class="form-check-input" type="radio" name="results_published" id="wlsm_results_published" value="1">
									<label class="ml-1 form-check-label text-success font-weight-bold" for="wlsm_results_published">
										<?php echo esc_html(WLSM_M_Staff_Examination::get_published_text()); ?>
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input <?php checked(0, $results_published, true); ?> class="form-check-input" type="radio" name="results_published" id="wlsm_results_unpublished" value="0">
									<label class="ml-1 form-check-label text-secondary font-weight-bold" for="wlsm_results_unpublished">
										<?php echo esc_html(WLSM_M_Staff_Examination::get_unpublished_text()); ?>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- total marks -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div class="wlsm-form-sub-heading wlsm-font-bold">
									<?php esc_html_e('Total Marks', 'school-management'); ?>
								</div>
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-12">
								<div class="form-check form-check-inline">
									<input <?php checked(1, $enable_total_marks, true); ?> class="form-check-input" type="radio" name="enable_total_marks" id="wlsm_total_marks_enabled" value="1">
									<label class="ml-1 form-check-label text-success font-weight-bold" for="wlsm_total_marks_enabled">
										<?php echo esc_html(WLSM_M_Staff_Examination::get_enabled_text()); ?>
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input <?php checked(0, $enable_total_marks, true); ?> class="form-check-input" type="radio" name="enable_total_marks" id="wlsm_total_marks_disabled" value="0">
									<label class="ml-1 form-check-label text-secondary font-weight-bold" for="wlsm_total_marks_disabled">
										<?php echo esc_html(WLSM_M_Staff_Examination::get_disabled_text()); ?>
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div class="wlsm-form-sub-heading wlsm-font-bold">
									<?php esc_html_e('Total obtained marks', 'school-management'); ?>
								</div>
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-12">
								<div class="form-check form-check-inline">
									<input <?php checked(1, $results_obtained_marks, true); ?> class="form-check-input" type="radio" name="results_obtained_marks" id="wlsm_enable_results_obtained_marks" value="1">
									<label class="ml-1 form-check-label text-success font-weight-bold" for="wlsm_enable_results_obtained_marks">
										<?php echo esc_html(WLSM_M_Staff_Examination::get_enabled_text()); ?>
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input <?php checked(0, $results_obtained_marks, true); ?> class="form-check-input" type="radio" name="results_obtained_marks" id="wlsm_disable_results_obtained_marks" value="0">
									<label class="ml-1 form-check-label text-secondary font-weight-bold" for="wlsm_disable_results_obtained_marks">
										<?php echo esc_html(WLSM_M_Staff_Examination::get_disabled_text()); ?>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Show in assessment -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div class="wlsm-form-sub-heading wlsm-font-bold">
									<?php esc_html_e('Show in Overall Results Assessment', 'school-management'); ?>
								</div>
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-12">
								<div class="form-check form-check-inline">
									<input <?php checked(1, $show_in_assessment, true); ?> class="form-check-input" type="radio" name="show_in_assessment" id="wlsm_show_in_assessment_1" value="1">
									<label class="ml-1 form-check-label text-success font-weight-bold" for="wlsm_show_in_assessment_1">
										<?php esc_html_e('Yes', 'school-management'); ?>
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input <?php checked(0, $show_in_assessment, true); ?> class="form-check-input" type="radio" name="show_in_assessment" id="wlsm_show_in_assessment_0" value="0">
									<label class="ml-1 form-check-label text-secondary font-weight-bold" for="wlsm_show_in_assessment_0">
										<?php esc_html_e('No', 'school-management'); ?>
									</label>
								</div>
							</div>
						</div>
					</div>

					<!-- Show Rank in exam results -->
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div class="wlsm-form-sub-heading wlsm-font-bold">
									<?php esc_html_e('Show Ranks In Results', 'school-management'); ?>
								</div>
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-12">
								<div class="form-check form-check-inline">
									<input <?php checked(1, $show_rank, true); ?> class="form-check-input" type="radio" name="show_rank" id="wlsm_show_rank" value="1">
									<label class="ml-1 form-check-label text-success font-weight-bold" for="wlsm_show_rank">
										<?php esc_html_e('Yes', 'school-management'); ?>
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input <?php checked(0, $show_rank, true); ?> class="form-check-input" type="radio" name="show_rank" id="wlsm_results_unpublished" value="0">
									<label class="ml-1 form-check-label text-secondary font-weight-bold" for="wlsm_results_unpublished">
										<?php esc_html_e('No', 'school-management'); ?>
									</label>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>

			<!-- Show in Remark -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div class="wlsm-form-sub-heading wlsm-font-bold">
									<?php esc_html_e('Show Subjects Remarks', 'school-management'); ?>
								</div>
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-12">
								<div class="form-check form-check-inline">
									<input <?php checked(1, $show_remark, true); ?> class="form-check-input" type="radio" name="show_remark" id="wlsm_show_remark" value="1">
									<label class="ml-1 form-check-label text-success font-weight-bold" for="wlsm_show_remark">
										<?php esc_html_e('Yes', 'school-management'); ?>
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input <?php checked(0, $show_remark, true); ?> class="form-check-input" type="radio" name="show_remark" id="wlsm_results_unpublished" value="0">
									<label class="ml-1 form-check-label text-secondary font-weight-bold" for="wlsm_results_unpublished">
										<?php esc_html_e('No', 'school-management'); ?>
									</label>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div class="wlsm-form-sub-heading wlsm-font-bold">
									<?php esc_html_e('Show Remarks ( School,Teacher )', 'school-management'); ?>
								</div>
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-12">
								<div class="form-check form-check-inline">
									<input <?php checked(1, $show_eremark, true); ?> class="form-check-input" type="radio" name="show_eremark" id="wlsm_show_eremark" value="1">
									<label class="ml-1 form-check-label text-success font-weight-bold" for="wlsm_show_eremark">
										<?php esc_html_e('Yes', 'school-management'); ?>
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input <?php checked(0, $show_eremark, true); ?> class="form-check-input" type="radio" name="show_eremark" id="wlsm_results_unpublished" value="0">
									<label class="ml-1 form-check-label text-secondary font-weight-bold" for="wlsm_results_unpublished">
										<?php esc_html_e('No', 'school-management'); ?>
									</label>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div class="wlsm-form-sub-heading wlsm-font-bold">
									<?php esc_html_e('Psychomotor Analysis', 'school-management'); ?>
								</div>
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-12">
								<div class="form-check form-check-inline">
									<input <?php checked(1, $psychomotor_analysis, true); ?> class="form-check-input" type="radio" name="psychomotor_analysis" id="wlsm_psychomotor_analysis" value="1">
									<label class="ml-1 form-check-label text-success font-weight-bold" for="wlsm_psychomotor_analysis">
										<?php esc_html_e('Yes', 'school-management'); ?>
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input <?php checked(0, $psychomotor_analysis, true); ?> class="form-check-input" type="radio" name="psychomotor_analysis" id="wlsm_results_unpublished" value="0">
									<label class="ml-1 form-check-label text-secondary font-weight-bold" for="wlsm_results_unpublished">
										<?php esc_html_e('No', 'school-management'); ?>
									</label>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-row">
							<div class="col-md-6">
								<label for="wlsm_teacher_signature" class="wlsm-font-bold">
									<?php esc_html_e('Class Teacher Signature', 'school-management'); ?>:
								</label>
								<div class="custom-file mb-3">
									<input type="file" id="wlsm_teacher_signature" name="teacher_signature">
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>

			<?php if ($exam) { ?>
				<!-- Shortcodes -->
				<div class="wlsm-form-section">
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<div class="wlsm-form-sub-heading wlsm-font-bold">
										<?php esc_html_e('Exam Time Table', 'school-management'); ?>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">
									<ul class="list-group list-group-flush">
										<li class="list-inline-item">
											<div class="alert alert-light">
												<?php esc_html_e('To display exam time table on a page or post, use shortcode', 'school-management'); ?>:<br>
												<span id="wlsm_school_management_exam_time_table_shortcode" class="wlsm-font-bold text-dark">[school_management_exam_time_table school_id="<?php echo esc_html($school_id); ?>" exam_id="<?php echo esc_html($exam->ID); ?>"]</span>
												<button id="wlsm_school_management_exam_time_table_copy_btn" class="btn btn-outline-success btn-sm" type="button">
													<?php esc_html_e('Copy', 'school-management'); ?>
												</button>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<div class="wlsm-form-sub-heading wlsm-font-bold">
										<?php esc_html_e('Admit Cards', 'school-management'); ?>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">
									<ul class="list-group list-group-flush">
										<li class="list-inline-item">
											<div class="alert alert-light">
												<?php esc_html_e('To display exam admit cards form on a page or post, use shortcode', 'school-management'); ?>:<br>
												<span id="wlsm_school_management_exam_admit_card_shortcode" class="wlsm-font-bold text-dark">[school_management_exam_admit_card school_id="<?php echo esc_html($school_id); ?>" exam_id="<?php echo esc_html($exam->ID); ?>"]</span>
												<button id="wlsm_school_management_exam_admit_card_copy_btn" class="btn btn-outline-success btn-sm" type="button">
													<?php esc_html_e('Copy', 'school-management'); ?>
												</button>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<div class="wlsm-form-sub-heading wlsm-font-bold">
										<?php esc_html_e('Exam Results', 'school-management'); ?>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">
									<ul class="list-group list-group-flush">
										<li class="list-inline-item">
											<div class="alert alert-light">
												<?php esc_html_e('To display exam results form on a page or post, use shortcode', 'school-management'); ?>:<br>
												<span id="wlsm_school_management_exam_result_shortcode" class="wlsm-font-bold text-dark">[school_management_exam_result school_id="<?php echo esc_html($school_id); ?>" exam_id="<?php echo esc_html($exam->ID); ?>"]</span>
												<button id="wlsm_school_management_exam_result_copy_btn" class="btn btn-outline-success btn-sm" type="button">
													<?php esc_html_e('Copy', 'school-management'); ?>
												</button>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>

			<!-- Exam Status -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e('Exam Status', 'school-management'); ?>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-12">
						<div class="form-check form-check-inline">
							<input <?php checked(1, $is_active, true); ?> class="form-check-input" type="radio" name="is_active" id="wlsm_status_active" value="1">
							<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_status_active">
								<?php echo esc_html(WLSM_M_Staff_Examination::get_active_text()); ?>
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input <?php checked(0, $is_active, true); ?> class="form-check-input" type="radio" name="is_active" id="wlsm_status_inactive" value="0">
							<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_status_inactive">
								<?php echo esc_html(WLSM_M_Staff_Examination::get_inactive_text()); ?>
							</label>
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-save-exam-btn">
						<?php
						if ($exam) {
						?>
							<i class="fas fa-save"></i>&nbsp;
						<?php
							esc_html_e('Update Exam', 'school-management');
						} else {
						?>
							<i class="fas fa-plus-square"></i>&nbsp;
						<?php
							esc_html_e('Add New Exam', 'school-management');
						}
						?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>