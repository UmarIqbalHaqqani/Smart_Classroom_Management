<?php
defined( 'ABSPATH' ) || die();

$page_url = WLSM_M_Staff_General::get_staff_attendance_page_url();

$school_id = $current_school['id'];

$nonce_action = 'take-staff-attendance';
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-2 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-calendar-alt"></i>
				<?php esc_html_e( 'View Staff Attendance', 'school-management' ); ?>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url . '&action=save' ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-clock"></i>&nbsp;
					<?php echo esc_html( 'Take Staff Attendance', 'school-management' ); ?>
				</a>
			</span>
		</div>

		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-view-staff-attendance-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="<?php echo esc_attr( 'wlsm-view-staff-attendance' ); ?>">

			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold text-center">
							<?php esc_html_e( 'Staff Attendance', 'school-management' ); ?>
							<br>
							<small class="text-dark">
								<em><?php esc_html_e( 'Select month.', 'school-management' ); ?></em>
							</small>
						</div>
					</div>
				</div>

				<div class="form-row mt-2 justify-content-md-center">
					<div class="form-group col-md-4">
						<label for="wlsm_attendance_year_month" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Month', 'school-management' ); ?>:
						</label>
						<input type="text" name="year_month" class="form-control" id="wlsm_attendance_year_month" placeholder="<?php esc_attr_e( 'Month', 'school-management' ); ?>">
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="button" class="btn btn-sm btn-primary" id="wlsm-view-staff-attendance-btn" data-nonce="<?php echo esc_attr( wp_create_nonce( 'view-staff-attendance' ) ); ?>">
						<?php esc_html_e( 'View Staff Attendance', 'school-management' ); ?>
					</button>
				</div>
			</div>

			<div class="wlsm-staff-attendance mt-2"></div>

		</form>
	</div>
</div>
