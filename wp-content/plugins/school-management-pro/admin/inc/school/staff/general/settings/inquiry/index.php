<?php
defined( 'ABSPATH' ) || die();

// Inquiry settings.
$settings_inquiry               = WLSM_M_Setting::get_settings_inquiry( $school_id );
$school_inquiry_form_title      = $settings_inquiry['form_title'];
$school_inquiry_phone_required  = $settings_inquiry['phone_required'];
$school_inquiry_email_required  = $settings_inquiry['email_required'];
$school_inquiry_admin_phone     = $settings_inquiry['admin_phone'];
$school_inquiry_admin_email     = $settings_inquiry['admin_email'];
$school_inquiry_success_message = $settings_inquiry['success_message'];
$school_inquiry_redirect_url    = $settings_inquiry['inquiry_redirect_url'];

$school_inquiry_success_placeholders = WLSM_Helper::inquiry_success_message_placeholders();
?>
<div class="tab-pane fade" id="wlsm-school-inquiry" role="tabpanel" aria-labelledby="wlsm-school-inquiry-tab">

	<div class="row">
		<div class="col-md-9">
			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-school-inquiry-settings-form">
				<?php
				$nonce_action = 'save-school-inquiry-settings';
				$nonce        = wp_create_nonce( $nonce_action );
				?>
				<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

				<input type="hidden" name="action" value="wlsm-save-school-inquiry-settings">

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_inquiry_form_title" class="wlsm-font-bold"><?php esc_html_e( 'Inquiry Form Title', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<input name="inquiry_form_title" type="text" id="wlsm_inquiry_form_title" value="<?php echo esc_attr( $school_inquiry_form_title ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Inquiry form title', 'school-management' ); ?>">
							<p class="description">
								<?php esc_html_e( 'Works only when school_id is specified in the inquiry shortcode.', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_inquiry_phone_required" class="wlsm-font-bold"><?php esc_html_e( 'Mandatory Phone Field', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<div class="form-check form-check-inline">
								<input <?php checked( true, $school_inquiry_phone_required, true ); ?> class="form-check-input" type="radio" name="inquiry_phone_required" id="wlsm_inquiry_phone_required_1" value="1">
								<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_inquiry_phone_required_1">
									<?php esc_html_e( 'Yes', 'school-management' ); ?>
								</label>
							</div>
							<div class="form-check form-check-inline">
								<input <?php checked( false, $school_inquiry_phone_required, true ); ?> class="form-check-input" type="radio" name="inquiry_phone_required" id="wlsm_inquiry_phone_required_0" value="0">
								<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_inquiry_phone_required_0">
									<?php esc_html_e( 'No', 'school-management' ); ?>
								</label>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_inquiry_email_required" class="wlsm-font-bold"><?php esc_html_e( 'Mandatory Email Field', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<div class="form-check form-check-inline">
								<input <?php checked( true, $school_inquiry_email_required, true ); ?> class="form-check-input" type="radio" name="inquiry_email_required" id="wlsm_inquiry_email_required_1" value="1">
								<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_inquiry_email_required_1">
									<?php esc_html_e( 'Yes', 'school-management' ); ?>
								</label>
							</div>
							<div class="form-check form-check-inline">
								<input <?php checked( false, $school_inquiry_email_required, true ); ?> class="form-check-input" type="radio" name="inquiry_email_required" id="wlsm_inquiry_email_required_0" value="0">
								<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_inquiry_email_required_0">
									<?php esc_html_e( 'No', 'school-management' ); ?>
								</label>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_inquiry_admin_phone" class="wlsm-font-bold"><?php esc_html_e( 'Admin Phone Number', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<input name="inquiry_admin_phone" type="text" id="wlsm_inquiry_admin_phone" value="<?php echo esc_attr( $school_inquiry_admin_phone ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Admin phone number to receive inquiry notification', 'school-management' ); ?>">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_inquiry_admin_email" class="wlsm-font-bold"><?php esc_html_e( 'Admin Email Address', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<input name="inquiry_admin_email" type="email" id="wlsm_inquiry_admin_email" value="<?php echo esc_attr( $school_inquiry_admin_email ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Admin email address to receive inquiry notification', 'school-management' ); ?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_redirect_url" class="wlsm-font-bold"><?php esc_html_e('Redirect URL', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<input name="redirect_url" type="text" id="wlsm_redirect_url" value="<?php echo esc_attr($school_inquiry_redirect_url); ?>" class="form-control" placeholder="<?php esc_attr_e('Redirect URL', 'school-management'); ?>">
							<p class="description">
								<?php esc_html_e('Enter URL where to redirect the student after inquiry.', 'school-management'); ?>
							</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_inquiry_success_message" class="wlsm-font-bold"><?php esc_html_e( 'Success Message', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="mb-1">
							<span class="wlsm-font-bold text-dark"><?php esc_html_e( 'You can use the following variables:', 'school-management' ); ?></span>
							<div class="d-flex">
								<?php foreach ( $school_inquiry_success_placeholders as $key => $value ) { ?>
								<div class="col-sm-6 col-md-3 pb-1 pt-1 border">
									<span class="wlsm-font-bold text-secondary"><?php echo esc_html( $value ); ?></span>
									<br>
									<span><?php echo esc_html( $key ); ?></span>
								</div>
								<?php } ?>
							</div>
						</div>

						<div class="form-group">
							<textarea name="inquiry_success_message" id="wlsm_inquiry_success_message" class="form-control" rows="6" placeholder="<?php esc_attr_e( 'Success Message', 'school-management' ); ?>"><?php echo esc_html( $school_inquiry_success_message ); ?></textarea>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn btn-primary" id="wlsm-save-school-inquiry-settings-btn">
							<i class="fas fa-save"></i>&nbsp;
							<?php esc_html_e( 'Save', 'school-management' ); ?>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

</div>
