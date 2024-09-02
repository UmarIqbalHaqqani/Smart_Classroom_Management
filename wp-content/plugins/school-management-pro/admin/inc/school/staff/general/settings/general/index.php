<?php
defined( 'ABSPATH' ) || die();


$active_currency        = WLSM_Config::currency($school_id);
$currency_symbols = WLSM_Helper::currency_symbols();
// General settings.
$settings_general                   = WLSM_M_Setting::get_settings_general( $school_id );
$school_logo                        = $settings_general['school_logo'];
$school_signature                   = $settings_general['school_signature'];
$school_student_logout_redirect_url = $settings_general['student_logout_redirect_url'];
$school_app_url                     = $settings_general['school_app_url'];
$school_hide_transport              = $settings_general['hide_transport'];
$school_hide_library                = $settings_general['hide_library'];
$school_invoice_copies              = $settings_general['invoice_copies'];
$school_invoice_auto                = $settings_general['invoice_auto'];
$school_assign_fee_on_promotion     = $settings_general['fee_on_promotion'];
$school_generate_invoices_promotion = $settings_general['invoices_on_promotion'];
$school_generate_invoices_history   = $settings_general['invoices_history'];
?>
<div class="tab-pane fade show active" id="wlsm-school-general" role="tabpanel" aria-labelledby="wlsm-school-general-tab">
	<div class="row">
		<div class="col-md-9">
			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-school-general-settings-form">
				<?php
				$nonce_action = 'save-school-general-settings';
				$nonce        = wp_create_nonce( $nonce_action );
				?>
				<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

				<input type="hidden" name="action" value="wlsm-save-school-general-settings">

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_currency" class="wlsm-font-bold">
							<?php esc_html_e('Set Currency', 'school-management'); ?>:
						</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<select name="currency" id="wlsm_currency" class="form-control">
								<?php foreach ($currency_symbols as $key => $currency_symbol) { ?>
									<option <?php selected($key, $active_currency, true); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($key); ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_currency" class="wlsm-font-bold">
							<?php esc_html_e('Invoice Copy', 'school-management'); ?>:
						</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<div class="form-check form-check-inline">
								<input <?php checked( true, $school_invoice_copies, true ); ?> class="form-check-input" type="radio" name="invoice_copies" id="wlsm_invoice_copies_1" value="1">
								<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_invoice_copies_1">
									<?php esc_html_e( 'Yes', 'school-management' ); ?>
								</label>
							</div>
							<div class="form-check form-check-inline">
								<input <?php checked( false, $school_invoice_copies, true ); ?> class="form-check-input" type="radio" name="invoice_copies" id="wlsm_invoice_copies_0" value="0">
								<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_invoice_copies_0">
									<?php esc_html_e( 'No', 'school-management' ); ?>
								</label>
							</div>
							<p class="description">
								
								<?php esc_html_e( 'Admin will be able to print school and student copies of invoices', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_currency" class="wlsm-font-bold">
							<?php esc_html_e('Assign Fee Types on Promotion', 'school-management'); ?>:
						</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<div class="form-check form-check-inline">
								<input <?php checked( true, $school_assign_fee_on_promotion, true ); ?> class="form-check-input" type="radio" name="assign_fee_on_promotion" id="wlsm_assign_fee_on_promotion_1" value="1">
								<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_assign_fee_on_promotion_1">
									<?php esc_html_e( 'Yes', 'school-management' ); ?>
								</label>
							</div>
							<div class="form-check form-check-inline">
								<input <?php checked( false, $school_assign_fee_on_promotion, true ); ?> class="form-check-input" type="radio" name="assign_fee_on_promotion" id="wlsm_assign_fee_on_promotion_0" value="0">
								<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_assign_fee_on_promotion_0">
									<?php esc_html_e( 'No', 'school-management' ); ?>
								</label>
							</div>
							<p class="description">
								
								<?php esc_html_e( 'Fee Types will be assigned when you promote student class according to promotion class fee types', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_currency" class="wlsm-font-bold">
							<?php esc_html_e('Generate Invoices on Promotion', 'school-management'); ?>:
						</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<div class="form-check form-check-inline">
								<input <?php checked( true, $school_generate_invoices_promotion, true ); ?> class="form-check-input" type="radio" name="generate_invoices_promotion" id="wlsm_generate_invoices_promotion_1" value="1">
								<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_generate_invoices_promotion_1">
									<?php esc_html_e( 'Yes', 'school-management' ); ?>
								</label>
							</div>
							<div class="form-check form-check-inline">
								<input <?php checked( false, $school_generate_invoices_promotion, true ); ?> class="form-check-input" type="radio" name="generate_invoices_promotion" id="wlsm_generate_invoices_promotion_0" value="0">
								<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_generate_invoices_promotion_0">
									<?php esc_html_e( 'No', 'school-management' ); ?>
								</label>
							</div>
							<p class="description">
								
								<?php esc_html_e( 'Invoices will be Generated according to promoted class fee types', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_currency" class="wlsm-font-bold">
							<?php esc_html_e('Generate Invoices Payment History', 'school-management'); ?>:
						</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<div class="form-check form-check-inline">
								<input <?php checked( true, $school_generate_invoices_history, true ); ?> class="form-check-input" type="radio" name="generate_invoices_history" id="wlsm_generate_invoices_history_1" value="1">
								<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_generate_invoices_history_1">
									<?php esc_html_e( 'Yes', 'school-management' ); ?>
								</label>
							</div>
							<div class="form-check form-check-inline">
								<input <?php checked( false, $school_generate_invoices_history, true ); ?> class="form-check-input" type="radio" name="generate_invoices_history" id="wlsm_generate_invoices_history_0" value="0">
								<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_generate_invoices_history_0">
									<?php esc_html_e( 'No', 'school-management' ); ?>
								</label>
							</div>
							<p class="description">
								
								<?php esc_html_e( 'Invoices will also show payments history', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_currency" class="wlsm-font-bold">
							<?php esc_html_e('Auto Generate Invoice', 'school-management'); ?>:
						</label>
					</div>
					<div class="col-md-9">
					<div class="form-group">
							<div class="form-check form-check-inline">
								<input <?php checked( true, $school_invoice_auto, true ); ?> class="form-check-input" type="radio" name="invoice_auto" id="wlsm_invoice_auto_1" value="1">
								<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_invoice_auto_1">
									<?php esc_html_e( 'Yes', 'school-management' ); ?>
								</label>
							</div>
							<div class="form-check form-check-inline">
								<input <?php checked( false, $school_invoice_auto, true ); ?> class="form-check-input" type="radio" name="invoice_auto" id="wlsm_invoice_auto_0" value="0">
								<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_invoice_auto_0">
									<?php esc_html_e( 'No', 'school-management' ); ?>
								</label>
							</div>
							<!-- <button type="button" class="btn btn-sm btn-primary wlsm-invoice-auto-generate" data-nonce="<?php echo esc_attr(wp_create_nonce('invoice-auto-generate')); ?>" data-school_id="<?php echo $school_id; ?>" data-close="<?php echo esc_attr__('Close', 'school-management'); ?>"><i class="fas fa-print"></i> <?php esc_html_e('Test', 'school-management'); ?></button> -->
							<p class="description">
								<strong class="text-success"><?php esc_html_e( 'New', 'school-management' ); ?> -</strong>
								<?php esc_html_e( 'Monthly invoices will be auto generated', 'school-management' ); ?>
								<p class="description text-danger">
								<?php esc_html_e( 'Currently works with only one school auto invoices setting can be active at a time. (Make sure you have Active school and active session selected and settings are saved. )', 'school-management' ); ?>
							</p>
							</p>
						</div>
						
					</div>
				</div>


				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_school_logo" class="wlsm-font-bold mt-1">
							<?php esc_html_e( 'Upload School Logo', 'school-management' ); ?>:
						</label>
					</div>
					<div class="col-md-9">
						<div class="wlsm-school-logo-box">
							<div class="wlsm-school-logo-section">
								<div class="form-group">
									<div class="custom-file mb-3">
										<input type="file" class="custom-file-input" id="wlsm_school_logo" name="school_logo">
										<label class="custom-file-label" for="wlsm_school_logo">
											<?php esc_html_e( 'Choose File', 'school-management' ); ?>
										</label>
									</div>
								</div>

								<?php if ( ! empty ( $school_logo ) ) { ?>
								<img src="<?php echo esc_url( wp_get_attachment_url( $school_logo ) ); ?>" class="img-responsive wlsm-school-logo">

								<div class="form-group">
									<input class="form-check-input mt-2" type="checkbox" name="remove_school_logo" id="wlsm_school_remove_logo" value="1">
									<label class="ml-4 mb-1 mt-1 form-check-label wlsm-font-bold text-danger" for="wlsm_school_remove_logo">
										<?php esc_html_e( 'Remove School Logo?', 'school-management' ); ?>
									</label>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_school_signature" class="wlsm-font-bold mt-1">
							<?php esc_html_e( 'Upload School signature', 'school-management' ); ?>:
						</label>
					</div>
					<div class="col-md-9">
						<div class="wlsm-school-signature-box">
							<div class="wlsm-school-signature-section">
								<div class="form-group">
									<div class="custom-file mb-3">
										<input type="file" class="custom-file-input" id="wlsm_school_signature" name="school_signature">
										<label class="custom-file-label" for="wlsm_school_signature">
											<?php esc_html_e( 'Choose File', 'school-management' ); ?>
										</label>
									</div>
								</div>

								<?php if ( ! empty ( $school_signature ) ) { ?>
								<img src="<?php echo esc_url( wp_get_attachment_url( $school_signature ) ); ?>" class="img-responsive wlsm-school-signature">

								<div class="form-group">
									<input class="form-check-input mt-2" type="checkbox" name="remove_school_signature" id="wlsm_school_remove_signature" value="1">
									<label class="ml-4 mb-1 mt-1 form-check-label wlsm-font-bold text-danger" for="wlsm_school_remove_signature">
										<?php esc_html_e( 'Remove School signature', 'school-management' ); ?>
									</label>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_student_logout_redirect_url" class="wlsm-font-bold"><?php esc_html_e( 'Redirect URL after Logout', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<input name="student_logout_redirect_url" type="text" id="wlsm_student_logout_redirect_url" value="<?php echo esc_attr( $school_student_logout_redirect_url ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Redirect URL after Logout', 'school-management' ); ?>">
							<p class="description">
								<?php esc_html_e( 'Enter URL where to redirect the student after logout. Leave blank for same page URL.', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_app_url" class="wlsm-font-bold"><?php esc_html_e( 'Google Application URL', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<input name="app_url" type="text" id="wlsm_app_url" value="<?php echo esc_attr( $school_app_url ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Google Application URL', 'school-management' ); ?>">
							<p class="description">
								<?php esc_html_e( 'Enter URL for your google app.', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>

				

				<div class="row mt-2">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn btn-primary" id="wlsm-save-school-general-settings-btn">
							<i class="fas fa-save"></i>&nbsp;
							<?php esc_html_e( 'Save', 'school-management' ); ?>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

</div>
