<?php
defined( 'ABSPATH' ) || die();

// Lessons settings.
$settings_lessons      = WLSM_M_Setting::get_settings_lessons( $school_id ); 
$student_login_required = $settings_lessons['student_login_required'];
?>
<div class="tab-pane fade" id="wlsm-school-lessons" role="tabpanel" aria-labelledby="wlsm-school-lessons-tab">

	<div class="row">
		<div class="col-md-9">
			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-school-lessons-settings-form">
				<?php
				$nonce_action = 'save-school-lessons-settings';
				$nonce        = wp_create_nonce( $nonce_action );
				?>
				<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

				<input type="hidden" name="action" value="wlsm-save-school-lessons-settings">

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_student_login_required" class="wlsm-font-bold"><?php esc_html_e( 'Enable Student Login', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<div class="form-check form-check-inline">
								<input <?php checked( true, $student_login_required, true ); ?> class="form-check-input" type="radio" name="student_login_required" id="wlsm_student_login_required_1" value="1">
								<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_student_login_required_1">
									<?php esc_html_e( 'Yes', 'school-management' ); ?>
								</label>
							</div>
							<div class="form-check form-check-inline">
								<input <?php checked( false, $student_login_required, true ); ?> class="form-check-input" type="radio" name="student_login_required" id="wlsm_student_login_required_0" value="0">
								<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_student_login_required_0">
									<?php esc_html_e( 'No', 'school-management' ); ?>
								</label>
							</div>
							<p class="description">
								<?php esc_html_e( 'Check if Student is logged in.', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>

				<div class="row mt-2">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn btn-primary" id="wlsm-save-school-lessons-settings-btn">
							<i class="fas fa-save"></i>&nbsp;
							<?php esc_html_e( 'Save', 'school-management' ); ?>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

</div>
