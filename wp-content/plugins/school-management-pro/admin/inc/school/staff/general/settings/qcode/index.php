<?php
defined( 'ABSPATH' ) || die();

// Url settings.
$settings_url           = WLSM_M_Setting::get_settings_certificate_qcode_url( $school_id );
$school_certificate_url = $settings_url['certificate_url'];
$school_result_url      = $settings_url['result_url'];
$school_admin_card_url  = $settings_url['admin_card_url'];
?>
<div class="tab-pane fade" id="wlsm-school-url" role="tabpanel" aria-labelledby="wlsm-school-url-tab">

	<div class="row">
		<div class="col-md-9">
			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-school-url-settings-form">
				<?php
				$nonce_action = 'save-school-url-settings';
				$nonce        = wp_create_nonce( $nonce_action );
				?>
				<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

				<input type="hidden" name="action" value="wlsm-save-school-url-settings">

					<div class="row">
						<div class="col-md-4">
							<label for="wlsm_certificate" class="wlsm-font-bold"><?php esc_html_e( 'Certificate URL ', 'school-management' ); ?>:</label>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<input name="certificate_url" type="text" id="wlsm_certificate" value="<?php echo esc_attr( $school_certificate_url ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Certificate URL  ', 'school-management' ); ?>">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<label for="wlsm_result" class="wlsm-font-bold"><?php esc_html_e( 'Result URL ', 'school-management' ); ?>:</label>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<input name="result_url" type="text" id="wlsm_result" value="<?php echo esc_attr( $school_result_url ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Result URL  ', 'school-management' ); ?>">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<label for="wlsm_admin_card" class="wlsm-font-bold"><?php esc_html_e( 'Admit card URL ', 'school-management' ); ?>:</label>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<input name="admin_card_url" type="text" id="wlsm_admin_card" value="<?php echo esc_attr( $school_admin_card_url ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Admit card URL  ', 'school-management' ); ?>">
							</div>
						</div>
					</div>

				<div class="row mt-2">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn btn-primary" id="wlsm-save-school-url-settings-btn">
							<i class="fas fa-save"></i>&nbsp;
							<?php esc_html_e( 'Save', 'school-management' ); ?>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

</div>
