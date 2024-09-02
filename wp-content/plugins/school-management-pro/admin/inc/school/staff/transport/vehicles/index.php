<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Transport.php';

$vehicles_page_url = WLSM_M_Staff_Transport::get_vehicles_page_url();
?>

<div class="row">
	<div class="col-md-12">
		<div class="text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-bus-alt"></i>
				<?php esc_html_e( 'Vehicles', 'school-management' ); ?>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $vehicles_page_url . '&action=save' ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-plus-square"></i>&nbsp;
					<?php echo esc_html( 'Add New Vehicle', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<div class="wlsm-table-block">
			<table class="table table-hover table-bordered" id="wlsm-vehicles-table">
				<thead>
					<tr class="text-white bg-primary">
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Vehicle Number', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Vehicle Model', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Driver Name', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Driver Phone', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'In-charge', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
