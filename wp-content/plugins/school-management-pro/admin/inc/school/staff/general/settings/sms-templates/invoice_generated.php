<?php
defined( 'ABSPATH' ) || die();

// SMS Invoice Generated settings.
$settings_sms_invoice_generated = WLSM_M_Setting::get_settings_sms_invoice_generated( $school_id );
$sms_invoice_generated_enable   = $settings_sms_invoice_generated['enable'];
$sms_invoice_generated_message  = $settings_sms_invoice_generated['message'];
$sms_student_template_id_invoice_generate  = $settings_sms_invoice_generated['template_id'];

$sms_invoice_generated_placeholders = WLSM_SMS::invoice_generated_placeholders();
?>
<button type="button" class="mt-2 btn btn-block btn-primary" data-toggle="collapse" data-target="#wlsm_sms_invoice_generated_fields" aria-expanded="true" aria-controls="wlsm_sms_invoice_generated_fields">
	<?php esc_html_e( 'Invoice Generated SMS Template', 'school-management' ); ?>
</button>

<div class="collapse border border-top-0 border-primary p-3" id="wlsm_sms_invoice_generated_fields">

	<div class="wlsm_sms_template wlsm_sms_invoice_generated">
		<div class="row">
			<div class="col-md-3">
				<label for="wlsm_sms_invoice_generated_enable" class="wlsm-font-bold">
					<?php esc_html_e( 'Invoice Generated SMS', 'school-management' ); ?>:
				</label>
			</div>
			<div class="col-md-9">
				<div class="form-group">
					<label for="wlsm_sms_invoice_generated_enable" class="wlsm-font-bold">
						<input <?php checked( $sms_invoice_generated_enable, true, true ); ?> type="checkbox" name="sms_invoice_generated_enable" id="wlsm_sms_invoice_generated_enable" value="1">
						<?php esc_html_e( 'Enable', 'school-management' ); ?>
					</label>
				</div>
			</div>
		</div>
	</div>

	<div class="wlsm_sms_template wlsm_sms_invoice_generated mb-3">
		<div class="row">
			<div class="col-md-12">
				<span class="wlsm-font-bold text-dark"><?php esc_html_e( 'You can use the following variables:', 'school-management' ); ?></span>
				<div class="row">
					<?php foreach ( $sms_invoice_generated_placeholders as $key => $value ) { ?>
					<div class="col-sm-6 col-md-3 pb-1 pt-1 border">
						<span class="wlsm-font-bold text-secondary"><?php echo esc_html( $value ); ?></span>
						<br>
						<span><?php echo esc_html( $key ); ?></span>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

	<div class="wlsm_sms_template wlsm_sms_invoice_generated">
		<div class="row">
			<div class="col-md-3">
				<label for="wlsm_sms_invoice_generated_message" class="wlsm-font-bold"><?php esc_html_e( 'SMS Message', 'school-management' ); ?>:</label>
			</div>
			<div class="col-md-9">
				<div class="form-group">
					<textarea name="sms_invoice_generated_message" id="wlsm_sms_invoice_generated_message" class="form-control" rows="6" placeholder="<?php esc_attr_e( 'SMS Message', 'school-management' ); ?>"><?php echo esc_html( $sms_invoice_generated_message ); ?></textarea>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<label for="wlsm_sms_student_template_id_invoice_generate" class="wlsm-font-bold"><?php esc_html_e( 'Template ID', 'school-management' ); ?>:</label>
			</div>
			<div class="col-md-9">
				<div class="form-group">
					<textarea name="sms_student_template_id_invoice_generate" id="wlsm_sms_student_template_id_invoice_generate" class="form-control" rows="1" placeholder="<?php esc_attr_e( 'SMS Tepmplate ID', 'school-management' ); ?>"><?php echo esc_html( $sms_student_template_id_invoice_generate ); ?></textarea>
				</div>
			</div>
		</div>
	</div>

	<?php
	$sms_template = 'invoice_generated';
	require WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/sms-templates/test_sms.php';
	?>

</div>
