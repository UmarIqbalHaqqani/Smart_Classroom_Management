<?php
defined('ABSPATH') || die();

require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/global.php';
?>
<div class="wlsm container-fluid">
	<?php require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/partials/header.php'; ?>
	<div class="row">
		<div class="col-md-12">
			<div class="text-center wlsm-section-heading-block">
				<span class="wlsm-section-heading">
				<i class="fa fa-birthday-cake" aria-hidden="true"></i>
					<?php esc_html_e('Students Birthdays', 'school-management'); ?>
					<i class="fa fa-birthday-cake" aria-hidden="true"></i>
				</span>

			</div>

			<div class="col-md-8">
				<form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="wlsm-fetch-student-birthdays-form" class="mb-3">
				<?php
				$nonce_action = 'get-student-birthdays';
				?>
				<?php $nonce = wp_create_nonce($nonce_action); ?>
				<input type="hidden" name="<?php echo esc_attr($nonce_action); ?>" value="<?php echo esc_attr($nonce); ?>">

				<input type="hidden" name="action" value="wlsm-fetch-student-birthdays">

				<div class="wlsm-search-students wlsm-search-keyword-students pt-2">
					<div class="row">
						<div class="col-md-8 mb-1">
							<div class="h6">
								<span class="text-secondary border-bottom">
									<?php esc_html_e('Select Date', 'school-management'); ?>
								</span>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-4">
							<label for="wlsm_search_field" class="wlsm-font-bold">
								<?php esc_html_e('Start Date', 'school-management'); ?>:
							</label>
							<input type="text" name="start_date" class="form-control wlsm_payment_date" id="wlsm_search_keyword" placeholder="<?php esc_attr_e('Enter Search Keyword', 'school-management'); ?>">
						</div>
						<div class="form-group col-md-4">
							<label for="wlsm_search_keyword" class="wlsm-font-bold">
								<?php esc_html_e('End Date', 'school-management'); ?>:
							</label>
							<input type="text" name="end_date" class="form-control wlsm_payment_date" id="wlsm_search_keyword" placeholder="<?php esc_attr_e('Enter Search Keyword', 'school-management'); ?>">
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-12">
						<button type="button" class="btn btn-sm btn-outline-primary" id="wlsm-fetch-student-birthdays-btn">
							<i class="fas fa-file-invoice"></i>&nbsp;
							<?php esc_html_e('Fetch Data', 'school-management'); ?>
						</button>
					</div>
				</div>
			</form>
			</div>

			<div class="wlsm-table-block">
				<table class="table table-hover table-bordered" id="wlsm-student-birthdays-table">
					<thead>
						<tr class="text-white bg-primary">
							<th scope="col"><?php esc_html_e('Admission Number', 'school-management'); ?></th>
							<th scope="col"><?php esc_html_e('Name', 'school-management'); ?></th>
							<th scope="col"><?php esc_html_e('Class', 'school-management'); ?></th>
							<th scope="col"><?php esc_html_e('Section', 'school-management'); ?></th>
							<th scope="col"><?php esc_html_e('Phone', 'school-management'); ?></th>
							<th scope="col"><?php esc_html_e('DOB', 'school-management'); ?></th>
							<th scope="col"><?php esc_html_e('Email', 'school-management'); ?></th>
							<!-- <th scope="col" class="text-nowrap"><?php esc_html_e('Action', 'school-management'); ?></th> -->
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>