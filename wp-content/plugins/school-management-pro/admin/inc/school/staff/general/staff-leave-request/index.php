<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';

$school_id = $current_school['id'];

$admin = WLSM_M_Role::get_user_admin( $school_id );

$multiple_days = false;
$nonce_action  = 'submit-staff-leave-request';
?>

<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php esc_html_e( 'Submit a Leave Request', 'school-management' ); ?>
				</span>
			</span>
		</div>

		<div class="row justify-content-md-center">
			<div class="col-md-10">
				<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-submit-staff-leave-request-form">

					<?php $nonce = wp_create_nonce( $nonce_action ); ?>
					<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

					<input type="hidden" name="action" value="wlsm-submit-staff-leave-request">

					<div class="wlsm-form-section">
						<div class="form-row">
							<div class="form-group col-md-4">
								<label class="wlsm-font-bold">
									<?php esc_html_e( 'Number of Days', 'school-management' ); ?>:
								</label>
								<br>
								<div class="form-check form-check-inline">
									<input <?php checked( $multiple_days, false, true ); ?> class="form-check-input" type="radio" name="multiple_days" id="wlsm_multiple_days_0" value="0">
									<label class="ml-1 form-check-label wlsm-font-bold" for="wlsm_multiple_days_0">
										<?php esc_html_e( 'Single Day' , 'school-management'); ?>
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input <?php checked( $multiple_days, true, true ); ?> class="form-check-input" type="radio" name="multiple_days" id="wlsm_multiple_days_1" value="1">
									<label class="ml-1 form-check-label wlsm-font-bold" for="wlsm_multiple_days_1">
										<?php esc_html_e( 'Multiple Days', 'school-management' ); ?>
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
									}; ?>">
							</div>

							<div class="form-group col-md-4 wlsm_leave_end_date">
								<label for="wlsm_leave_end_date" class="wlsm-font-bold">
									<?php esc_html_e( 'End Date:', 'school-management' ); ?>
								</label>
								<input type="text" name="end_date" class="form-control" id="wlsm_leave_end_date" placeholder="<?php esc_attr_e( 'End Date', 'school-management' ); ?>">
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="wlsm_description" class="wlsm-font-bold">
									<?php esc_html_e( 'Reason', 'school-management' ); ?>:
								</label>
								<textarea name="description" class="form-control" id="wlsm_description" placeholder="<?php esc_attr_e( 'Enter reason', 'school-management' ); ?>" cols="30" rows="4"></textarea>
							</div>
						</div>
					</div>

					<div class="row mt-2">
						<div class="col-md-12 text-center">
							<button data-confirm="<?php esc_attr_e( 'Confirm! Are you sure to submit the leave request?', 'school-management' ); ?>" type="submit" class="btn btn-primary" id="wlsm-submit-staff-leave-request-btn">
								<?php esc_html_e( 'Submit Leave Request', 'school-management' ); ?>
							</button>
						</div>
					</div>

				</form>
			</div>
		</div>

		<div class="row mt-4">
			<div class="col-md-12">
				<div class="mt-3 text-center wlsm-section-heading-block">
					<span class="wlsm-section-heading-box">
						<span class="wlsm-section-heading">
							<?php esc_html_e( 'Recent Leave Requests', 'school-management' ); ?>
						</span>
					</span>
				</div>
				<div class="wlsm-table-block">
					<table class="table table-hover table-bordered" id="wlsm-staff-leave-request-table">
						<thead>
							<tr class="text-white bg-primary">
								<th scope="col"><?php esc_html_e( 'Reason', 'school-management' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Leave Date', 'school-management' ); ?></th>
								<th scope="col" class="text-nowrap"><?php esc_html_e( 'Status', 'school-management' ); ?></th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
