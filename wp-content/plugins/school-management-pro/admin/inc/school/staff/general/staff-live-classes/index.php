<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';

$school_id = $current_school['id'];
?>

<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php esc_html_e( 'Live Classes Assigned', 'school-management' ); ?>
				</span>
			</span>
		</div>
		<div class="wlsm-table-block">
			<table class="table table-hover table-bordered" id="wlsm-staff-meetings-table">
				<thead>
					<tr class="text-white bg-primary">
						<th scope="col"><?php esc_html_e( 'Topic', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Host ID', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Duration (minutes)', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Meeting ID (Zoom)', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Start Class', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Start Date / Time', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Type', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Join URL', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Password', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Class', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Subject', 'school-management' ); ?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
