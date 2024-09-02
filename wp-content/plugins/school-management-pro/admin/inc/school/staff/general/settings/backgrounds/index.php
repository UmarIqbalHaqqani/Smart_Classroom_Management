<?php
defined('ABSPATH') || die();

// Url settings.
$settings_background           = WLSM_M_Setting::get_settings_background($school_id);
$id_card_background     = $settings_background['id_card_background'];
$invoice_card_background  = $settings_background['invoice_card_background'];
$result_card_background = $settings_background['result_card_background'];
?>
<div class="tab-pane fade" id="wlsm-school-card-backgrounds" role="tabpanel" aria-labelledby="wlsm-school-card-backgrounds-tab">

	<div class="row">
		<div class="col-md-9">
			<form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="wlsm-save-school-card-backgrounds-settings-form">
				<?php
				$nonce_action = 'save-school-card-backgrounds-settings';
				$nonce        = wp_create_nonce($nonce_action);
				?>
				<input type="hidden" name="<?php echo esc_attr($nonce_action); ?>" value="<?php echo esc_attr($nonce); ?>">

				<input type="hidden" name="action" value="wlsm-save-school-card-backgrounds-settings">

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_id_card_background" class="wlsm-font-bold mt-1">
							<?php esc_html_e('Upload Id Card Background', 'school-management'); ?>:
						</label>
					</div>
					<div class="col-md-9">
						<div class="wlsm-school-logo-box">
							<div class="wlsm-school-logo-section">
								<div class="form-group">
									<div class="custom-file mb-3">
										<input type="file" class="custom-file-input" id="wlsm_id_card_background" name="id_card_background">
										<label class="custom-file-label" for="wlsm_id_card_background">
											<?php esc_html_e('Choose File', 'school-management'); ?>
										</label>
									</div>
								</div>

								<?php if (!empty($id_card_background)) { ?>
									<img src="<?php echo esc_url(wp_get_attachment_url($id_card_background)); ?>" class="img-responsive wlsm-school-logo">

									<div class="form-group">
										<input class="form-check-input mt-2" type="checkbox" name="remove_id_card_background" id="wlsm_id_card_background_remove" value="1">
										<label class="ml-4 mb-1 mt-1 form-check-label wlsm-font-bold text-danger" for="wlsm_id_card_background_remove">
											<?php esc_html_e('Remove Id Card Background?', 'school-management'); ?>
										</label>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_invoice_card_background" class="wlsm-font-bold mt-1">
							<?php esc_html_e('Upload Invoice Background', 'school-management'); ?>:
						</label>
					</div>
					<div class="col-md-9">
						<div class="wlsm-school-logo-box">
							<div class="wlsm-school-logo-section">
								<div class="form-group">
									<div class="custom-file mb-3">
										<input type="file" class="custom-file-input" id="wlsm_invoice_card_background" name="invoice_card_background">
										<label class="custom-file-label" for="wlsm_invoice_card_background">
											<?php esc_html_e('Choose File', 'school-management'); ?>
										</label>
									</div>
								</div>

								<?php if (!empty($invoice_card_background)) { ?>
									<img src="<?php echo esc_url(wp_get_attachment_url($invoice_card_background)); ?>" class="img-responsive wlsm-school-logo">

									<div class="form-group">
										<input class="form-check-input mt-2" type="checkbox" name="remove_invoice_card_background" id="wlsm_invoice_card_background_remove" value="1">
										<label class="ml-4 mb-1 mt-1 form-check-label wlsm-font-bold text-danger" for="wlsm_invoice_card_background_remove">
											<?php esc_html_e('Remove admit Background?', 'school-management'); ?>
										</label>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>

				<!-- <div class="row">
					<div class="col-md-3">
						<label for="wlsm_result_card_background" class="wlsm-font-bold mt-1">
							<?php esc_html_e('Upload Result Background', 'school-management'); ?>:
						</label>
					</div>
					<div class="col-md-9">
						<div class="wlsm-school-logo-box">
							<div class="wlsm-school-logo-section">
								<div class="form-group">
									<div class="custom-file mb-3">
										<input type="file" class="custom-file-input" id="wlsm_result_card_background" name="result_card_background">
										<label class="custom-file-label" for="wlsm_result_card_background">
											<?php esc_html_e('Choose File', 'school-management'); ?>
										</label>
									</div>
								</div>

								<?php if (!empty($result_card_background)) { ?>
									<img src="<?php echo esc_url(wp_get_attachment_url($result_card_background)); ?>" class="img-responsive wlsm-school-logo">

									<div class="form-group">
										<input class="form-check-input mt-2" type="checkbox" name="remove_result_card_background" id="wlsm_result_card_background_remove" value="1">
										<label class="ml-4 mb-1 mt-1 form-check-label wlsm-font-bold text-danger" for="wlsm_result_card_background_remove">
											<?php esc_html_e('Remove Result Background?', 'school-management'); ?>
										</label>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div> -->


				<div class="row mt-2">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn btn-primary" id="wlsm-save-school-card-backgrounds-settings-btn">
							<i class="fas fa-save"></i>&nbsp;
							<?php esc_html_e('Save', 'school-management'); ?>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

</div>
