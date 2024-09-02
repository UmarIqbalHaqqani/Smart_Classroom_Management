<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Transport.php';

$page_url = WLSM_M_Staff_Transport::get_rooms_page_url();

$school_id = $current_school['id'];

$room         = NULL;
$stop          = '';
$compose_room = '';
$nonce_action = 'add-room';
$note = '';

$name = '';
$bed_number = '';

$hostel_id = array();

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id    = absint( $_GET['id'] );
	$room = WLSM_M_Staff_Transport::fetch_room( $school_id, $id );
	if ( $room ) {
		$nonce_action = 'edit-room-' . $room->ID;

		$name = $room->room_name;
		$bed_number = $room->number_of_beds;
		$note = $room->note;
		
	

		$hostel_id = WLSM_M_Staff_Transport::fetch_rooms( $school_id, $id );
	}
}

$hostels = WLSM_M_Staff_Transport::fetch_hostels( $school_id );
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					if ( $room ) {
						printf(
							wp_kses(
								/* translators: %s: room name */
								__( 'Edit room: %s', 'school-management' ),
								array(
									'span' => array( 'class' => array() )
								)
							),
							esc_html( $name )
						);
					} else {
						esc_html_e( 'Add New Room', 'school-management' );
					}
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
				<i class="fas fa-bed"></i>
					<?php esc_html_e( 'View All', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-room-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-save-room">

			<?php if ( $room ) { ?>
			<input type="hidden" name="room_id" value="<?php echo esc_attr( $room->ID ); ?>">
			<?php } ?>

			<div class="wlsm-form-section">
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_name" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Room Number', 'school-management' ); ?>:
						</label>
						<input type="text" name="name" class="form-control" id="wlsm_name" placeholder="<?php esc_attr_e( 'Enter room number', 'school-management' ); ?>" value="<?php echo esc_attr( $name ); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_bed_number" class="wlsm-font-bold">
							<?php esc_html_e( 'Number of bed', 'school-management' ); ?>:
						</label>
						<input type="number" step="any" min="0" name="bed_number" class="form-control" id="wlsm_bed_number" placeholder="<?php esc_attr_e( 'Enter ', 'school-management' ); ?>" value="<?php echo esc_attr( ! empty( $bed_number ) ? WLSM_Config::sanitize_money( $bed_number ) : '' ); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_hostel" class="wlsm-font-bold">
							<?php esc_html_e( 'Hostel', 'school-management' ); ?>:
						</label>
						<select name="hostel" class="form-control selectpicker" id="wlsm_hostel" data-actions-box="true" data-none-selected-text="<?php esc_attr_e( 'Select', 'school-management' ); ?>">
							<?php foreach ( $hostels as $hostel ) { ?>
							<option <?php selected( in_array( $hostel->ID, $hostel_id ), true, true ); ?> value="<?php echo esc_attr( $hostel->ID ); ?>">
								<?php echo esc_html( $hostel->hostel_name ); ?>
							</option>
							<?php } ?>
						</select>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_note" class="wlsm-font-bold">
							 <?php esc_html_e( 'Note', 'school-management' ); ?>:
						</label>
						<textarea name="note" id="" cols="" rows="3" class="form-control" id="wlsm_note" placeholder="<?php esc_attr_e( 'Enter Note', 'school-management' ); ?>"><?php echo esc_attr( $note ); ?></textarea>
					</div>
				</div>
				
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-save-room-btn">
						<?php
						if ( $room ) {
							?>
							<i class="fas fa-save"></i>&nbsp;
							<?php
							esc_html_e( 'Update Room', 'school-management' );
						} else {
							?>
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php
							esc_html_e( 'Add New Room', 'school-management' );
						}
						?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
