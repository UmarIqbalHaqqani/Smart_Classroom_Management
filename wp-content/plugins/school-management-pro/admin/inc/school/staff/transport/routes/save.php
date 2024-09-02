<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Transport.php';

$page_url = WLSM_M_Staff_Transport::get_routes_page_url();

$school_id = $current_school['id'];

$route = NULL;

$nonce_action = 'add-route';

$name = '';
$fare = '';
$period               = '';
$route_vehicles = array();

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id    = absint( $_GET['id'] );
	$route = WLSM_M_Staff_Transport::fetch_route( $school_id, $id );

	if ( $route ) {
		$nonce_action = 'edit-route-' . $route->ID;

		$name   = $route->name;
		$fare   = $route->fare;
		$period = $route->period;

		$route_vehicles = WLSM_M_Staff_Transport::fetch_route_vehicles( $school_id, $id );
	}
}

$vehicles = WLSM_M_Staff_Transport::fetch_vehicles( $school_id );

$fee_periods = WLSM_Helper::fee_period_list();

?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					if ( $route ) {
						printf(
							wp_kses(
								/* translators: %s: route name */
								__( 'Edit Transport Route: %s', 'school-management' ),
								array(
									'span' => array( 'class' => array() )
								)
							),
							esc_html( $name )
						);
					} else {
						esc_html_e( 'Add New Transport Route', 'school-management' );
					}
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-route"></i>&nbsp;
					<?php esc_html_e( 'View All', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-route-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-save-route">

			<?php if ( $route ) { ?>
			<input type="hidden" name="route_id" value="<?php echo esc_attr( $route->ID ); ?>">
			<?php } ?>

			<div class="wlsm-form-section">
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_name" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Route Name', 'school-management' ); ?>:
						</label>
						<input type="text" name="name" class="form-control" id="wlsm_name" placeholder="<?php esc_attr_e( 'Enter route name', 'school-management' ); ?>" value="<?php echo esc_attr( $name ); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_fare" class="wlsm-font-bold">
							<?php esc_html_e( 'Route Fare', 'school-management' ); ?>:
						</label>
						<input type="number" step="any" min="0" name="fare" class="form-control" id="wlsm_fare" placeholder="<?php esc_attr_e( 'Enter route fare', 'school-management' ); ?>" value="<?php echo esc_attr( ! empty( $fare ) ? WLSM_Config::sanitize_money( $fare ) : '' ); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_period" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Period', 'school-management'); ?>:
						</label>
						<select name="period" class="form-control selectpicker" id="wlsm_period" data-live-search="true">
							<?php foreach ($fee_periods as $key => $value) { ?>
								<option value="<?php echo esc_attr($key); ?>" <?php selected($key, $period, true); ?>>
									<?php echo esc_html($value); ?>
								</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_vehicles" class="wlsm-font-bold">
							<?php esc_html_e( 'Transport Vehicles', 'school-management' ); ?>:
						</label>
						<select multiple name="vehicles[]" class="form-control selectpicker" id="wlsm_vehicles" data-actions-box="true" data-none-selected-text="<?php esc_attr_e( 'Select', 'school-management' ); ?>">
							<?php foreach ( $vehicles as $vehicle ) { ?>
							<option <?php selected( in_array( $vehicle->ID, $route_vehicles ), true, true ); ?> value="<?php echo esc_attr( $vehicle->ID ); ?>">
								<?php echo esc_html( $vehicle->vehicle_number ); ?>
							</option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-save-route-btn">
						<?php
						if ( $route ) {
							?>
							<i class="fas fa-save"></i>&nbsp;
							<?php
							esc_html_e( 'Update Route', 'school-management' );
						} else {
							?>
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php
							esc_html_e( 'Add New Route', 'school-management' );
						}
						?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
