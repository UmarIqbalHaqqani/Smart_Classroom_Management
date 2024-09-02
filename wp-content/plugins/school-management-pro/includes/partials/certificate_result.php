<?php
$admit_card = WLSM_M_Staff_Examination::fetch_admit_card( $school_id, $admit_card_id );
$exam = WLSM_M_Staff_Examination::fetch_exam($school_id, $exam_id);

$exam_id            = $exam->ID;
$exam_title         = $exam->exam_title;
$exam_center        = $exam->exam_center;
$start_date         = $exam->start_date;
$end_date           = $exam->end_date;
$show_rank          = $exam->show_rank;
$show_remark        = $exam->show_remark;
$show_eremark       = $exam->show_eremark;
$psychomotor_enable = $exam->psychomotor_analysis;
$teacher_signature  = $exam->teacher_signature;

$enable_max_marks = $exam->enable_total_marks;
$enable_obtained  = $exam->results_obtained_marks;

$psychomotor = WLSM_Config::sanitize_psychomotor($exam->psychomotor);

$exam_papers  = WLSM_M_Staff_Examination::get_exam_papers_by_admit_card($school_id, $admit_card_id);
$exam_results = WLSM_M_Staff_Examination::get_exam_results_by_admit_card($school_id, $admit_card_id);

$grade_criteria = WLSM_Config::sanitize_grade_criteria( $exam->grade_criteria );

$enable_overall_grade = $grade_criteria['enable_overall_grade'];
$marks_grades         = $grade_criteria['marks_grades'];

$show_marks_grades = count($marks_grades);

$student_rank = WLSM_M_Staff_Examination::calculate_exam_ranks($school_id, $exam_id, array(), $admit_card_id);

$total_maximum_marks  = 0;
$total_obtained_marks = 0;

foreach ($exam_papers as $key => $exam_paper) {
	if ($admit_card && isset($exam_results[$exam_paper->ID])) {
		$exam_result    = $exam_results[$exam_paper->ID];
		$obtained_marks = $exam_result->obtained_marks;
	} else {
		$obtained_marks = '';
	}

	$percentage = WLSM_Config::sanitize_percentage($exam_paper->maximum_marks, WLSM_Config::sanitize_marks($obtained_marks));
	$teacher_remark = $exam_result->teacher_remark;
	$school_remark = $exam_result->school_remark;
	$p_scale = $exam_result->scale;

	$total_maximum_marks  += $exam_paper->maximum_marks;
	$total_obtained_marks += WLSM_Config::sanitize_marks($obtained_marks);
?>

<?php
}
$total_percentage = WLSM_Config::sanitize_percentage( $total_maximum_marks, $total_obtained_marks );
	$p_scale = unserialize($p_scale);

$student_percentage = WLSM_Config::get_percentage_text( $total_maximum_marks, $total_obtained_marks );