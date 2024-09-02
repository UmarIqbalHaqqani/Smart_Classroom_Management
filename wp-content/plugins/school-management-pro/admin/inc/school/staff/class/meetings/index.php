<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';

$page_url = WLSM_M_Staff_Class::get_meetings_page_url();

$school_id = $current_school['id'];

// require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/class/meetings/check_compatibility.php';
?>
<div class="row">
	<div class="col-md-12">
		<div class="text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-video"></i>
				<?php esc_html_e( 'Live Classes', 'school-management' ); ?>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url . '&action=save' ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-plus-square"></i>&nbsp;
					<?php echo esc_html( 'Add New Live Class', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<div class="wlsm-table-block">
			<table class="table table-hover table-bordered" id="wlsm-meetings-table">
				<thead>
					<tr class="text-white bg-primary">
						<th scope="col"><?php esc_html_e( 'Topic', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Host ID', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Duration (minutes)', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Meeting ID', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Start Class', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Start Date / Time', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Type', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Join URL', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Class', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Subject', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Teacher', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
