<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Transport.php';

WLSM_Helper::enqueue_datatable_assets();

$routes_page_url   = WLSM_M_Staff_Transport::get_routes_page_url();
$vehicles_page_url = WLSM_M_Staff_Transport::get_vehicles_page_url();

$school_id  = $current_school['id'];

$nonce_action = 'get-transport-report';

$classes  = WLSM_M_Staff_Class::fetch_classes( $school_id );
$vehicles = WLSM_M_Staff_Transport::fetch_vehicles( $school_id );
$routes   = WLSM_M_Staff_Transport::fetch_routes( $school_id );
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-2 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-map-marked-alt"></i>
				<?php esc_html_e( 'Students Transport Report', 'school-management' ); ?>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $routes_page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-route"></i>&nbsp;
					<?php echo esc_html( 'Transport Routes', 'school-management' ); ?>
				</a>&nbsp;
				<a href="<?php echo esc_url( $vehicles_page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-bus-alt"></i>&nbsp;
					<?php echo esc_html( 'Transport Vehicles', 'school-management' ); ?>
				</a>
			</span>
		</div>

		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-get-transport-report-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="<?php echo esc_attr( 'wlsm-get-transport-report' ); ?>">

			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Students Transport Report', 'school-management' ); ?>
							<br>
							<small class="text-dark">
								<em><?php esc_html_e( 'Select class, section, transport route and/or vehicle.', 'school-management' ); ?></em>
							</small>
						</div>
					</div>
				</div>

				<div class="form-row mt-2">
					<div class="form-group col-md-3">
						<label for="wlsm_class" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Class', 'school-management' ); ?>:
						</label>
						<select name="class_id" class="form-control selectpicker" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-class-sections' ) ); ?>" id="wlsm_class" data-live-search="true">
							<option value=""><?php esc_html_e( 'All Classes', 'school-management' ); ?></option>
							<?php foreach ( $classes as $class ) { ?>
							<option value="<?php echo esc_attr( $class->ID ); ?>">
								<?php echo esc_html( WLSM_M_Class::get_label_text( $class->label ) ); ?>
							</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label for="wlsm_section" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Section', 'school-management' ); ?>:
						</label>
						<select name="section_id" class="form-control selectpicker" id="wlsm_section" data-live-search="true" title="<?php esc_attr_e( 'All Sections', 'school-management' ); ?>" data-all-sections="1">
						</select>
					</div>
					<div class="form-group col-md-3">
						<label for="wlsm_route" class="wlsm-font-bold">
							<?php esc_html_e( 'Transport Route', 'school-management' ); ?>:
						</label>
						<select name="route_id" class="form-control selectpicker" id="wlsm_route" data-live-search="true">
							<option value=""><?php esc_html_e( 'All Routes', 'school-management' ); ?></option>
							<?php foreach ( $routes as $route ) { ?>
							<option value="<?php echo esc_attr( $route->ID ); ?>">
								<?php echo esc_html( $route->name ); ?>
							</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label for="wlsm_vehicle" class="wlsm-font-bold">
							<?php esc_html_e( 'Transport Vehicle', 'school-management' ); ?>:
						</label>
						<select name="vehicle_id" class="form-control selectpicker" id="wlsm_vehicle" data-live-search="true">
							<option value=""><?php esc_html_e( 'All Vehicles', 'school-management' ); ?></option>
							<?php foreach ( $vehicles as $vehicle ) { ?>
							<option value="<?php echo esc_attr( $vehicle->ID ); ?>">
								<?php echo esc_html( $vehicle->vehicle_number ); ?>
							</option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="button" class="btn btn-sm btn-primary" id="wlsm-get-transport-report-btn" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-transport-report' ) ); ?>">
						<?php esc_html_e( 'Get Students Transport Report', 'school-management' ); ?>
					</button>
				</div>
			</div>

			<div class="wlsm-students-transport mt-2"></div>

		</form>
	</div>
</div>
