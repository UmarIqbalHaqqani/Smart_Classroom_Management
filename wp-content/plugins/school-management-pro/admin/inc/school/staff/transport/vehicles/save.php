<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Transport.php';

$page_url = WLSM_M_Staff_Transport::get_vehicles_page_url();

$school_id = $current_school['id'];

$vehicle = NULL;

$nonce_action = 'add-vehicle';

$vehicle_number = '';
$vehicle_model  = '';
$driver_name    = '';
$driver_phone   = '';
$note           = '';

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id      = absint( $_GET['id'] );
	$vehicle = WLSM_M_Staff_Transport::fetch_vehicle( $school_id, $id );

	if ( $vehicle ) {
		$nonce_action = 'edit-vehicle-' . $vehicle->ID;

		$vehicle_number = $vehicle->vehicle_number;
		$vehicle_model  = $vehicle->vehicle_model;
		$driver_name    = $vehicle->driver_name;
		$driver_phone   = $vehicle->driver_phone;
		$note           = $vehicle->note;
	}
}
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					if ( $vehicle ) {
						printf(
							wp_kses(
								/* translators: %s: vehicle number */
								__( 'Edit Vehicle: %s', 'school-management' ),
								array(
									'span' => array( 'class' => array() )
								)
							),
							esc_html( $vehicle_number )
						);
					} else {
						esc_html_e( 'Add New Vehicle', 'school-management' );
					}
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-bus-alt"></i>&nbsp;
					<?php esc_html_e( 'View All', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-vehicle-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-save-vehicle">

			<?php if ( $vehicle ) { ?>
			<input type="hidden" name="vehicle_id" value="<?php echo esc_attr( $vehicle->ID ); ?>">
			<?php } ?>

			<div class="wlsm-form-section">
				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="wlsm_vehicle_number" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Vehicle Number', 'school-management' ); ?>:
						</label>
						<input type="text" name="vehicle_number" class="form-control" id="wlsm_vehicle_number" placeholder="<?php esc_attr_e( 'Enter vehicle number', 'school-management' ); ?>" value="<?php echo esc_attr( $vehicle_number ); ?>">
					</div>
					<div class="form-group col-md-6">
						<label for="wlsm_vehicle_model" class="wlsm-font-bold">
							<?php esc_html_e( 'Vehicle Model', 'school-management' ); ?>:
						</label>
						<input type="text" name="vehicle_model" class="form-control" id="wlsm_vehicle_model" placeholder="<?php esc_attr_e( 'Enter vehicle model', 'school-management' ); ?>" value="<?php echo esc_attr( $vehicle_model ); ?>">
					</div>
				</div>

				<div class="form-row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="wlsm_driver_name" class="wlsm-font-bold">
								<?php esc_html_e( 'Driver Name', 'school-management' ); ?>:
							</label>
							<input type="text" name="driver_name" class="form-control" id="wlsm_driver_name" placeholder="<?php esc_attr_e( 'Enter driver name', 'school-management' ); ?>" value="<?php echo esc_attr( stripcslashes( $driver_name ) ); ?>">
						</div>
						<div class="form-group">
							<label for="wlsm_driver_phone" class="wlsm-font-bold">
								<?php esc_html_e( 'Driver Phone', 'school-management' ); ?>:
							</label>
							<input type="text" name="driver_phone" class="form-control" id="wlsm_driver_phone" placeholder="<?php esc_attr_e( 'Enter driver phone number', 'school-management' ); ?>" value="<?php echo esc_attr( $driver_phone ); ?>">
						</div>
					</div>
					<div class="form-group col-md-6">
						<label for="wlsm_note" class="wlsm-font-bold">
							<?php esc_html_e( 'Note', 'school-management' ); ?>:
						</label>
						<textarea name="note" class="form-control" id="wlsm_note" cols="30" rows="4" placeholder="<?php esc_attr_e( 'Enter note', 'school-management' ); ?>"><?php echo esc_html( $note ); ?></textarea>
					</div>

				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-save-vehicle-btn">
						<?php
						if ( $vehicle ) {
							?>
							<i class="fas fa-save"></i>&nbsp;
							<?php
							esc_html_e( 'Update Vehicle', 'school-management' );
						} else {
							?>
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php
							esc_html_e( 'Add New Vehicle', 'school-management' );
						}
						?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
