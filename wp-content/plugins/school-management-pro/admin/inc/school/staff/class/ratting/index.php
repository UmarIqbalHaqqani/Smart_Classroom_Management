<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';

$page_url = WLSM_M_Staff_Class::get_meetings_page_url();

$school_id = $current_school['id'];
?>
<div class="row">
	<div class="col-md-12">
		<div class="text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-video"></i>
				<?php esc_html_e( 'Staff Ratting', 'school-management' ); ?>
			</span>
		</div>
		<div class="wlsm-table-block">
			<table class="table table-hover table-bordered" id="wlsm-ratting-table">
				<thead>
					<tr class="text-white bg-primary">
						<th scope="col"><?php esc_html_e( 'Class', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Subject', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Teacher', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Student Feedback', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Average Ratting', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( '', 'school-management' ); ?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
