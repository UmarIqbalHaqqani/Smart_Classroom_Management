<?php
defined('ABSPATH') || die();
?>
<!-- Print exam admit card section. -->
<div class="wlsm-container wlsm wlsm-form-section" id="">
	<div class="wlsm-print-exam-admit-card-container">

		<?php require WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/partials/school_header.php'; ?>

		<div class="wlsm-heading wlsm-admit-card-heading h5 wlsm-text-center">
			<span><?php esc_html_e('STUDENT ADMIT CARD', 'school-management'); ?></span>
		</div>

		<div class="row wlsm-student-details">
			<div class="col-9 wlsm-student-details-right">
				<ul class="wlsm-list-group">
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e('Student Name', 'school-management'); ?>:</span>
						<span><?php echo esc_html(WLSM_M_Staff_Class::get_name_text($name)); ?></span>
					</li>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e('Enrollment Number', 'school-management'); ?>:</span>
						<span><?php echo esc_html($enrollment_number); ?></span>
					</li>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e('Session', 'school-management'); ?>:</span>
						<span><?php echo esc_html(WLSM_M_Session::get_label_text($session_label)); ?></span>
					</li>
					<li>
						<span class="wlsm-pr-3 pr-3">
							<span class="wlsm-font-bold"><?php esc_html_e('Class', 'school-management'); ?>:</span>
							<span><?php echo esc_html(WLSM_M_Class::get_label_text($class_label)); ?></span>
						</span>
						<span class="wlsm-pl-3 pl-3">
							<span class="wlsm-font-bold"><?php esc_html_e('Section', 'school-management'); ?>:</span>
							<span><?php echo esc_html(WLSM_M_Class::get_label_text($section_label)); ?></span>
						</span>
					</li>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e('Exam Roll Number', 'school-management'); ?>:</span>
						<span><?php echo esc_html(WLSM_M_Staff_Class::get_roll_no_text($roll_number)); ?></span>
					</li>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e('Phone', 'school-management'); ?>:</span>
						<span><?php echo esc_html(WLSM_M_Staff_Class::get_phone_text($phone)); ?></span>
					</li>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e('Email', 'school-management'); ?>:</span>
						<span><?php echo esc_html(WLSM_M_Staff_Class::get_name_text($email)); ?></span>
					</li>
				</ul>
			</div>

			<div class="col-3 wlsm-student-details-left">
				<div class="wlsm-student-photo-box">
					<?php if (!empty($photo_id)) { ?>
						<img src="<?php echo esc_url(wp_get_attachment_url($photo_id)); ?>" class="wlsm-student-photo">
					<?php } ?>
				</div>
			</div>


			<div class="col-9 mx-auto">
			</div>
			<div class="col-3 wlsm-student-detail-left text-right">
				<span class="wlsm-font-bold text-right"><?php esc_html_e('Authorized By', 'school-management'); ?>:</span>
				<div class="wlsm-student-detail-photo-box float-right">
					<?php if (!empty($school_signature)) { ?>
						<img width="50%" src="<?php echo esc_url(wp_get_attachment_url($school_signature)); ?>" class="wlsm-student-detail-photo">
					<?php } ?>
				</div>
			</div>

		</div>


		<div class="row">
			<div class="col-12">
				<span>
					<?php
					printf(
						wp_kses(
							/* translators: 1: exam title, 2: start date, 3: end date */
							__('<span class="wlsm-font-bold">Exam:</span> <span class="text-dark">%1$s (%2$s - %3$s)</span>', 'school-management'),
							array('span' => array('class' => array()))
						),
						esc_html(WLSM_M_Staff_Examination::get_exam_label_text($exam_title)),
						esc_html(WLSM_Config::get_date_text($start_date)),
						esc_html(WLSM_Config::get_date_text($end_date))
					);
					?>
				</span>
				<span class="float-md-right">
					<?php
					printf(
						wp_kses(
							/* translators: %s: exam classes */
							__('<span class="wlsm-font-bold">Class:</span> %s</span>', 'school-management'),
							array('span' => array('class' => array()))
						),
						esc_html($class_names)
					);
					?>
				</span>
			</div>
		</div>
		<div class="table-responsive w-100">
			<table class="table table-bordered wlsm-view-exam-time-table">
				<?php require WLSM_PLUGIN_DIR_PATH . 'includes/partials/exam_time_table.php'; ?>
			</table>
		</div>

	</div>
</div>