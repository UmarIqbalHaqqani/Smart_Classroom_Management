<?php
defined( 'ABSPATH' ) || die();

global $wpdb;

$page_url = WLSM_M_Staff_General::get_staff_leaves_page_url();

$school_id = $current_school['id'];

$session_id = $current_session['ID'];

$staff_leave = NULL;

$nonce_action = 'add-staff-leave';

$description = '';
$start_date  = '';
$end_date    = '';
$is_approved = 0;

$multiple_days = true;

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id          = absint( $_GET['id'] );
	$staff_leave = WLSM_M_Staff_General::fetch_staff_leave( $school_id, $id );

	if ( $staff_leave ) {
		$nonce_action = 'edit-staff-leave-' . $staff_leave->ID;

		$description = $staff_leave->description;
		$start_date  = $staff_leave->start_date;
		$end_date    = $staff_leave->end_date;
		$is_approved = $staff_leave->is_approved;

		if ( ! $end_date ) {
			$multiple_days = false;
		}
	}
}
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					if ( $staff_leave ) {
						/* translators: 1: staff name, 2: staff phone */
						printf( esc_html__( 'Edit Staff Leave: %1$s (Phone - %2$s)', 'school-management' ), esc_html( WLSM_M_Staff_Class::get_name_text( $staff_leave->name ) ), esc_html( WLSM_M_Staff_Class::get_phone_text( $staff_leave->phone ) ) );
					} else {
						esc_html_e( 'Add Staff Leave', 'school-management' );
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
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-staff-leave-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-save-staff-leave">

			<?php if ( $staff_leave ) { ?>
			<input type="hidden" name="staff_leave_id" value="<?php echo esc_attr( $staff_leave->ID ); ?>">
			<input type="hidden" name="staff" value="<?php echo esc_attr( $staff_leave->admin_id ); ?>">
			<?php } ?>

			<div class="wlsm-form-section">
				<?php if ( ! $staff_leave ) { ?>
				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="wlsm_single_admin_search" class="font-weight-bold"><?php esc_html_e( 'Search Staff', 'school-management' ); ?>:</label>
						<input type="text" name="keyword" class="form-control" id="wlsm_single_admin_search" placeholder="<?php esc_attr_e( 'Type 1 or more characters... then click to select', 'school-management' ); ?>" autocomplete="off">

						<div class="wlsm_single_admin"></div>
					</div>
				</div>
				<?php } ?>

				<div class="form-row">
					<div class="form-group col-md-4">
						<label class="wlsm-font-bold">
							<?php esc_html_e( 'Number of Leave Days', 'school-management' ); ?>:
						</label>
						<br>
						<div class="form-check form-check-inline">
							<input <?php checked( $multiple_days, false, true ); ?> class="form-check-input" type="radio" name="multiple_days" id="wlsm_multiple_days_0" value="0">
							<label class="ml-1 form-check-label wlsm-font-bold" for="wlsm_multiple_days_0">
								<?php esc_html_e( 'Single Day' ); ?>
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input <?php checked( $multiple_days, true, true ); ?> class="form-check-input" type="radio" name="multiple_days" id="wlsm_multiple_days_1" value="1">
							<label class="ml-1 form-check-label wlsm-font-bold" for="wlsm_multiple_days_1">
								<?php esc_html_e( 'Multiple Days' ); ?>
							</label>
						</div>
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_leave_start_date" class="wlsm-font-bold">
							<?php
							if ( $multiple_days ) {
								esc_html_e( 'Start Date:', 'school-management' );
							} else {
								esc_html_e( 'Leave Date:', 'school-management' );
							}
							?>
						</label>
						<input data-single="<?php esc_attr_e( 'Leave Date:', 'school-management' ); ?>" data-multiple="<?php esc_attr_e( 'Start Date:', 'school-management' ); ?>" type="text" name="start_date" class="form-control" id="wlsm_leave_start_date" placeholder="<?php
							if ( $multiple_days ) {
								esc_attr_e( 'Start Date', 'school-management' );
							} else {
								esc_attr_e( 'Leave Date', 'school-management' );
							}; ?>" value="<?php echo esc_attr( WLSM_Config::get_date_text( $start_date ) ); ?>">
					</div>

					<div class="form-group col-md-4 wlsm_leave_end_date">
						<label for="wlsm_leave_end_date" class="wlsm-font-bold">
							<?php esc_html_e( 'End Date:', 'school-management' ); ?>
						</label>
						<input type="text" name="end_date" class="form-control" id="wlsm_leave_end_date" placeholder="<?php esc_attr_e( 'End Date', 'school-management' ); ?>" value="<?php echo esc_attr( WLSM_Config::get_date_text( $end_date ) ); ?>">
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-8">
						<label for="wlsm_description" class="wlsm-font-bold">
							<?php esc_html_e( 'Reason', 'school-management' ); ?>:
						</label>
						<textarea name="description" class="form-control" id="wlsm_description" placeholder="<?php esc_attr_e( 'Enter reason', 'school-management' ); ?>" cols="30" rows="5"><?php echo esc_html( stripslashes( $description ) ); ?></textarea>
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_is_approved" class="wlsm-font-bold mt-4">
							<?php esc_html_e( 'Status', 'school-management' ); ?>:
						</label>
						<br>
						<div class="form-check form-check-inline">
							<input <?php checked( 1, $is_approved, true ); ?> class="form-check-input" type="radio" name="is_approved" id="wlsm_is_approved_1" value="1">
							<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_is_approved_1">
								<?php echo esc_html( WLSM_M_Staff_Class::get_approved_text() ); ?>
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input <?php checked( 0, $is_approved, true ); ?> class="form-check-input" type="radio" name="is_approved" id="wlsm_is_approved_0" value="0">
							<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_is_approved_0">
								<?php echo esc_html( WLSM_M_Staff_Class::get_unapproved_text() ); ?>
							</label>
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-save-staff-leave-btn">
						<?php
						if ( $staff_leave ) {
							?>
							<i class="fas fa-save"></i>&nbsp;
							<?php
							esc_html_e( 'Update Staff Leave', 'school-management' );
						} else {
							?>
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php
							esc_html_e( 'Add Staff Leave', 'school-management' );
						}
						?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
