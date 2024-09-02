<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Role.php';

global $wpdb;

$page_url = WLSM_M_Staff_Class::get_events_page_url();

$school_id = $current_school['id'];

$event = NULL;

$nonce_action = 'add-event';

$title       = '';
$description = '';
$event_date  = '';
$image_id    = '';
$is_active   = 1;

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id    = absint( $_GET['id'] );
	$event = WLSM_M_Staff_Class::fetch_event( $school_id, $id );

	if ( $event ) {
		$nonce_action = 'edit-event-' . $event->ID;

		$title       = $event->title;
		$description = $event->description;
		$event_date  = $event->event_date;
		$image_id    = $event->image_id;
		$is_active   = $event->is_active;
	}
}
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					if ( $event ) {
						esc_html_e( 'Edit Event', 'school-management' );
					} else {
						esc_html_e( 'Add New Event', 'school-management' );
					}
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-calendar-alt"></i>&nbsp;
					<?php esc_html_e( 'View All', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-event-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-save-event">

			<?php if ( $event ) { ?>
			<input type="hidden" name="event_id" value="<?php echo esc_attr( $event->ID ); ?>">
			<?php } ?>

			<div class="wlsm-form-section">
				<div class="form-row">
					<div class="form-group col-md-8">
						<label for="wlsm_title" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Event Title', 'school-management' ); ?>:
						</label>
						<input type="text" name="title" class="form-control" id="wlsm_title" placeholder="<?php esc_attr_e( 'Enter event title', 'school-management' ); ?>" value="<?php echo esc_attr( stripcslashes( $title ) ); ?>">
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_event_date" class="wlsm-font-bold">
							<?php esc_html_e( 'Event Date', 'school-management' ); ?>:
						</label>
						<input type="text" name="event_date" class="form-control" id="wlsm_event_date" placeholder="<?php esc_attr_e( 'Enter event date', 'school-management' ); ?>" value="<?php echo esc_attr( WLSM_Config::get_date_text( $event_date ) ); ?>">
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-12">
						<label for="wlsm_description" class="wlsm-font-bold">
							<?php esc_html_e( 'Event Description', 'school-management' ); ?>:
						</label>
						<?php
						$settings = array(
							'media_buttons' => current_user_can( 'upload_files' ) ? true : false,
							'textarea_name' => 'description',
							'textarea_rows' => 10,
							'wpautop'       => false,
						);
						wp_editor( stripslashes( $description ), 'wlsm_description', $settings );
						?>
					</div>
				</div>

				<?php if ( ! empty ( $image_id ) ) { ?>
				<div class="form-row mb-3 mt-3">
					<div class="col-md-12 pb-3 border-bottom">
						<img src="<?php echo esc_url( wp_get_attachment_url( $image_id ) ); ?>" class="img-responsive wlsm-image">
					</div>
				</div>
				<?php } ?>

				<div class="form-row justify-content-md-between">
					<div class="form-group col-md-4">
						<label for="wlsm_image" class="wlsm-font-bold">
							<?php
							if ( ! empty ( $image_id ) ) {
								esc_html_e( 'Change Event Image', 'school-management' );
							} else {
								esc_html_e( 'Upload Event Image', 'school-management' );
							}
							?>
						</label>
						<div class="custom-file mb-3">
							<input type="file" class="custom-file-input" id="wlsm_image" name="image">
							<label class="custom-file-label" for="wlsm_image">
								<?php esc_html_e( 'Choose Image', 'school-management' ); ?>
							</label>
						</div>
					</div>

					<div class="form-group col-md-6">
						<label for="wlsm_status" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Status', 'school-management' ); ?>:
						</label>
						<br>
						<div class="form-check form-check-inline">
							<input <?php checked( 1, $is_active, true ); ?> class="form-check-input" type="radio" name="is_active" id="wlsm_status_active" value="1">
							<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_status_active">
								<?php echo esc_html( WLSM_M_Staff_Class::get_active_text() ); ?>
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input <?php checked( 0, $is_active, true ); ?> class="form-check-input" type="radio" name="is_active" id="wlsm_status_inactive" value="0">
							<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_status_inactive">
								<?php echo esc_html( WLSM_M_Staff_Class::get_inactive_text() ); ?>
							</label>
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-save-event-btn">
						<?php
						if ( $event ) {
							?>
							<i class="fas fa-save"></i>&nbsp;
							<?php
							esc_html_e( 'Update Event', 'school-management' );
						} else {
							?>
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php
							esc_html_e( 'Add New Event', 'school-management' );
						}
						?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
