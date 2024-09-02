<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_School.php';

$page_url = WLSM_M_School::get_page_url();
?>

<div class="wlsm container-fluid">
	<div class="row">
		<div class="wlsm-main-header card col">
			<div class="card-header">
				<h1 class="h3 text-center">
					<i class="fas fa-school text-primary"></i>
					<?php esc_html_e( 'Schools', 'school-management' ); ?>
				</h1>
				<div class="float-md-right">
					<a href="<?php echo esc_url( $page_url . '&action=save' ); ?>" class="btn btn-sm btn-primary">
						<i class="fas fa-plus-square"></i>&nbsp;
						<?php echo esc_html( 'Add New School', 'school-management' ); ?>
					</a>
				</div>
			</div>
			<div class="">
				<table class="table table-hover table-bordered" id="wlsm-schools-table">
					<thead>
						<tr class="text-white bg-primary">
							<th scope="col"><?php esc_html_e( 'School Name', 'school-management' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Phone', 'school-management' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Email', 'school-management' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Address', 'school-management' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Number of Classes', 'school-management' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Admins', 'school-management' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Status', 'school-management' ); ?></th>
							<th scope="col" class="text-nowrap"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
