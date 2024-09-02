<?php
defined('ABSPATH') || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';

global $wpdb;

// $page_url = WLSM_M_Staff_Class::get_subjects types_page_url();

$school_id = $current_school['id'];

$nonce_action = 'add-subject-type';

$subject_type_id = NULL;

$label = '';


if (isset($_GET['subject_type_id']) && !empty($_GET['subject_type_id'])) {
	$subject_type_id = absint($_GET['subject_type_id']);
	$section    = WLSM_M_Staff_Class::fetch_section($school_id, $subject_type_id, $class->ID);

	if ($section) {
		$nonce_action = 'edit-subject-type-' . $section->ID;

		$subject_type_id = $section->ID;

		$label = $section->label;
	}
}
?>
<div class="row justify-content-md-center">
	<div class="col-md-12">
		<div class="card col">


			<div class="row">

				<div class="col-md-7">
					<h2 class="h4 border-bottom pb-2">
						<i class="fas fa-layer-group text-primary"></i>
						<?php esc_html_e(' Subjects types', 'school-management'); ?>
					</h2>
					<table class="table table-hover table-bordered" id="wlsm-class-subject-types-table" data-school="<?php echo esc_attr($school_id); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce('class-subject-types')); ?>">
						<thead>
							<tr class="text-white bg-primary">
								<th scope="col"><?php esc_html_e('ID', 'school-management'); ?></th>
								<th scope="col"><?php esc_html_e('Subject Type', 'school-management'); ?></th>
								<th scope="col" class="text-nowrap"><?php esc_html_e('Action', 'school-management'); ?></th>
							</tr>
						</thead>
					</table>
				</div>

				<div class="col-md-5">
					<div class="wlsm-page-heading-box">
						<h2 class="h4 border-bottom pb-2 wlsm-page-heading">

							<i class="fas fa-plus-square text-primary"></i>
							<?php esc_html_e('Add New Subject Type', 'school-management'); ?>

						</h2>
					</div>
					<form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="wlsm-save-subject-type-form">

						<?php $nonce = wp_create_nonce($nonce_action); ?>
						<input type="hidden" name="<?php echo esc_attr($nonce_action); ?>" value="<?php echo esc_attr($nonce); ?>">

						<input type="hidden" name="action" value="wlsm-save-subject-type">

						<input type="hidden" name="class_id" value="<?php echo esc_attr($school_id); ?>">

						<div class="form-group">
							<label for="wlsm_subject_type_label" class="font-weight-bold"><?php esc_html_e('Subject Type', 'school-management'); ?>:</label>
							<input type="text" name="label" class="form-control" id="wlsm_subject_type_label" placeholder="<?php esc_attr_e('Enter subject Type', 'school-management'); ?>" value="<?php echo esc_attr(($label)); ?>">
						</div>

						<div>
							<span class="float-md-right">
								<button type="submit" class="btn btn-sm btn-primary" id="wlsm-save-subject-type-btn">
									<i class="fas fa-plus-square"></i>&nbsp;
									<?php esc_html_e('Add New Subject Type', 'school-management'); ?>
								</button>
							</span>
						</div>

					</form>
				</div>
			</div>

		</div>
	</div>
</div>