<?php
defined( 'ABSPATH' ) || die();

// Logs settings.
$settings_logs            = WLSM_M_Setting::get_settings_logs( $school_id );
$school_activity_logs     = $settings_logs['activity_logs'];
$school_delete_after_days = $settings_logs['delete_after_days'];
?>
<div class="tab-pane fade" id="wlsm-school-logs" role="tabpanel" aria-labelledby="wlsm-school-logs-tab">

	<div class="row">
		<div class="col-md-9">
			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-school-logs-settings-form">
				<?php
				$nonce_action = 'save-school-logs-settings';
				$nonce        = wp_create_nonce( $nonce_action );
				?>
				<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

				<input type="hidden" name="action" value="wlsm-save-school-logs-settings">

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_activity_logs" class="wlsm-font-bold"><?php esc_html_e( 'Enable Logging', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<div class="form-check form-check-inline">
								<input <?php checked( true, $school_activity_logs, true ); ?> class="form-check-input" type="radio" name="activity_logs" id="wlsm_activity_logs_1" value="1">
								<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_activity_logs_1">
									<?php esc_html_e( 'Yes', 'school-management' ); ?>
								</label>
							</div>
							<div class="form-check form-check-inline">
								<input <?php checked( false, $school_activity_logs, true ); ?> class="form-check-input" type="radio" name="activity_logs" id="wlsm_activity_logs_0" value="0">
								<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_activity_logs_0">
									<?php esc_html_e( 'No', 'school-management' ); ?>
								</label>
							</div>
							<p class="description">
								<?php esc_html_e( 'Keep track of student, parent, staff and admin login records.', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_delete_after_days" class="wlsm-font-bold"><?php esc_html_e( 'Number of days to keep the logs', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<input name="delete_after_days" type="number" id="wlsm_delete_after_days" value="<?php echo esc_attr( $school_delete_after_days ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Number of days to keep the logs', 'school-management' ); ?>">
							<p class="description">
								<?php esc_html_e( 'Number of days after which a log is cleared.', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>

				<div class="row mt-2">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn btn-primary" id="wlsm-save-school-logs-settings-btn">
							<i class="fas fa-save"></i>&nbsp;
							<?php esc_html_e( 'Save', 'school-management' ); ?>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

</div>
