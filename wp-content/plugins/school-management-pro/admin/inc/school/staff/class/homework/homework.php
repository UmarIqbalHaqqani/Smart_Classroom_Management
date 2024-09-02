<?php

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';

$page_url = WLSM_M_Staff_Class::get_homeworks_page_url();

if (isset($_GET['id']) && !empty($_GET['id'])) {
	$id = absint($_GET['id']);
}

?>
<div class="row">
	<div class="col-md-12">
		<div class="text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-book"></i>
				<?php esc_html_e('Homework Submissions', 'school-management'); ?>
			</span>
		</div>
		<div class="wlsm-table-block">
			<table class="table table-hover table-bordered" id="wlsm-homeworks-submission-table">
				<input id="homework_id" type="hidden" value="<?php echo esc_attr($id); ?>">
				<thead>
					<tr class="text-white bg-primary">
						<th scope="col"><?php esc_html_e('Student Name', 'school-management'); ?></th>
						<th scope="col"><?php esc_html_e('Roll Number', 'school-management'); ?></th>
						<th scope="col"><?php esc_html_e('Class', 'school-management'); ?></th>
						<th scope="col"><?php esc_html_e('Added', 'school-management'); ?></th>
						<th scope="col"><?php esc_html_e('Title', 'school-management'); ?></th>
						<th scope="col"><?php esc_html_e('Discription', 'school-management'); ?></th>
						<th scope="col"><?php esc_html_e('Submission', 'school-management'); ?></th>
						<th scope="col"><?php esc_html_e('Status', 'school-management'); ?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>