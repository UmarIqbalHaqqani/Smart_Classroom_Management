<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Setting.php';

// bbbsettings.
$settings_bbb            = WLSM_M_Setting::get_settings_bbb( $school_id );
$settings_bbb_api_url    = $settings_bbb['api_key'];
$settings_bbb_api_secret = $settings_bbb['api_secret'];

?>
<div class="tab-pane fade" id="wlsm-school-zoom" role="tabpanel" aria-labelledby="wlsm-school-zoom-tab">

	<div class="row">
		<div class="col-md-7">
			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-school-zoom-settings-form">
				<?php
				$nonce_action = 'save-school-zoom-settings';
				$nonce        = wp_create_nonce( $nonce_action );
				?>
				<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

				<input type="hidden" name="action" value="wlsm-save-school-zoom-settings">

				<div class="wlsm_zoom">
					<div class="row">
						<div class="col-md-4">
							<label for="wlsm_zoom_api_key" class="wlsm-font-bold"><?php esc_html_e( 'Bigbluebutton URl ', 'school-management' ); ?>:</label>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<input name="zoom_api_key" type="text" id="wlsm_zoom_api_key" value="<?php echo esc_attr( $settings_bbb_api_url ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Bigbluebutton URl  ', 'school-management' ); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_zoom">
					<div class="row">
						<div class="col-md-4">
							<label for="wlsm_zoom_api_secret" class="wlsm-font-bold"><?php esc_html_e( 'Bigbluebutton Secret', 'school-management' ); ?>:</label>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<input name="zoom_api_secret" type="text" id="wlsm_zoom_api_secret" value="<?php echo esc_attr( $settings_bbb_api_secret ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Bigbluebutton Secret ', 'school-management' ); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn btn-primary" id="wlsm-save-school-zoom-settings-btn">
							<i class="fas fa-save"></i>&nbsp;
							<?php esc_html_e( 'Save', 'school-management' ); ?>
						</button>
					</div>
				</div>
			</form>
		</div>

		<div class="col-md-5">
			<h5><?php esc_html_e( 'Accessing your Bigbluebutton URl  & Secret', 'school-management' ); ?></h5>
			<p>
				<?php
				esc_html_e(
					'The BigBlueButton API security model enables 3rd-party applications to make API calls (if they have the shared secret), but not allow other people (end users) to make API calls.

					The BigBlueButton API calls are almost all made server-to-server. If you installed the package bbb-demo on your BigBlueButton server, you get a set of API examples, written in Java Server Pages (JSP), that demonstrate how to use the BigBlueButton API. These demos run as a web application in tomcat7. The web application makes HTTPS requests to the BigBlueButton server’s API end point.

					You can retrieve your BigBlueButton API parameters (API endpoint and shared secret) using the command

					$ bbb-conf --secret',
					'school-management'
				);
				?>
				<a target="_blank" href="https://docs.bigbluebutton.org/admin/bbb-conf.html"><?php esc_html_e( 'Click here for more information', 'school-management' ); ?></a>
			</p>
		</div>
	</div>

</div>
