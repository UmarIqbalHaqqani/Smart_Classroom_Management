<?php
defined('ABSPATH') || die();

require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/partials/navigation.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M.php';

$student_id = $student->ID;

$nonce_action  = 'submit-student-homework';

$school_id  = $student->school_id;

global $wpdb;

$query = WLSM_M_Staff_Class::fetch_homework_query($school_id, $session_id);

$query_filter = $wpdb->get_results($query);

$section_id = $student->section_id;

$homeworks_per_page = WLSM_M::homeworks_per_page();

$homeworks_query = WLSM_M::homeworks_query();

$homeworks_total = $wpdb->get_var($wpdb->prepare("SELECT COUNT(1) FROM ({$homeworks_query}) AS combined_table", $school_id, $session_id, $section_id));

$homeworks_page = isset($_GET['homeworks_page']) ? absint($_GET['homeworks_page']) : 1;

$homeworks_page_offset = ($homeworks_page * $homeworks_per_page) - $homeworks_per_page;

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id    = absint( $_GET['id'] );

	$homeworks = $wpdb->get_results($wpdb->prepare('SELECT hs.ID, hw.title, hs.student_id, hs.created_at, hs.description FROM ' .WLSM_HOMEWORK_SUBMISSION.' as hs
	JOIN ' . WLSM_HOMEWORK . ' as hw ON hw.ID = hs.submission_id
	WHERE hs.ID = '.$id.' ORDER BY hs.ID DESC'));
} else {
	$homeworks = $wpdb->get_results($wpdb->prepare($homeworks_query . ' ORDER BY hw.homework_date DESC LIMIT %d, %d', $school_id, $session_id, $section_id, $homeworks_page_offset, $homeworks_per_page));
}
?>
<div class="wlsm-content-area wlsm-section-leave-request wlsm-student-leave-request">
	<div class="wlsm-st-main-title">
		<span>
			<?php esc_html_e('Submit Homework & Assignment', 'school-management'); ?>
		</span>
	</div>

	<div class="wlsm-st-leave-request-section">
		<form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="wlsm-submit-student-homework-submission-form">

			<?php $nonce = wp_create_nonce($nonce_action); ?>
			<input type="hidden" name="<?php echo esc_attr($nonce_action); ?>" value="<?php echo esc_attr($nonce); ?>">

			<input type="hidden" name="action" value="wlsm-p-st-submit-studennt-homework-submission">

			<div class="row">
				<div class="wlsm-col-12 wlsm-form-group">
					<label for="wlsm_submission_subject" class="wlsm-form-label wlsm-font-bold">
						<?php esc_html_e('Submission Subject:', 'school-management'); ?>
					</label>
					<br>
					<!-- homework Get from database -->
					<select name="submission_id" class="form-control selectpicker" data-live-search="true" title="<?php esc_attr_e('Select', 'school-management'); ?>">
						<option value=""><?php esc_html_e('Select', 'school-management'); ?></option>
						<?php foreach ($homeworks as $key => $homework) { ?>
							<option value="<?php echo $homework->ID; ?>" <?php if ($homework->ID) { echo "selected";} ?>><?php echo $homework->title; ?></option>
						<?php } ?>
					</select>

				</div>
				<div class="wlsm-col-6 wlsm-form-group">
					<label for="wlsm_homework_file" class="wlsm-form-label wlsm-font-bold">
						<?php esc_html_e('Upload File (Doc, Docx And PDF):', 'school-management'); ?>
					</label>
					<br>
					<input type="file" name="attachments" class="wlsm-font-control">
				</div>
			</div>

			<div class="wlsm-form-group">
				<label for="wlsm_description" class="wlsm-form-label wlsm-font-bold">
					<span class="wlsm-text-danger">*</span> <?php esc_html_e('Description Or Additional Comment', 'school-management'); ?>:
				</label>
				<br>
				<?php foreach ($homeworks as $key => $homework) { ?>
				<textarea required name="description" class="wlsm-font-control" id="wlsm_description" placeholder="<?php esc_attr_e('Enter here', 'school-management'); ?>" cols="30" rows="4" > <?php if ( $homework->description) { echo "$homework->description"; } ?></textarea>

				<?php  } ?>
			</div>
			<div class="wlsm-border-top wlsm-pt-2 wlsm-mt-1">
				<button data-confirm="<?php esc_attr_e('Confirm! Are you sure to submit the leave request?', 'school-management'); ?>" class="button wlsm-btn btn btn-primary" type="submit" id="wlsm-submit-student-homework-submission-btn">
					<?php esc_html_e('Submit', 'school-management'); ?>
				</button>
			</div>
		</form>
	</div>
</div>
