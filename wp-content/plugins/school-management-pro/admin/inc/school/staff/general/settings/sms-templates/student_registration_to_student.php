<?php
defined( 'ABSPATH' ) || die();

// SMS Student Registration to Student settings.
$settings_sms_student_registration_to_student = WLSM_M_Setting::get_settings_sms_student_registration_to_student( $school_id );
$sms_student_registration_to_student_enable   = $settings_sms_student_registration_to_student['enable'];
$sms_student_registration_to_student_message  = $settings_sms_student_registration_to_student['message'];
$sms_student_template_id_registration_to_student_message  = $settings_sms_student_registration_to_student['template_id'];

$sms_student_registration_to_student_placeholders = WLSM_SMS::student_registration_to_student_placeholders();
?>
<button type="button" class="mt-2 btn btn-block btn-primary" data-toggle="collapse" data-target="#wlsm_sms_student_registration_to_student_fields" aria-expanded="true" aria-controls="wlsm_sms_student_registration_to_student_fields">
	<?php esc_html_e( 'Student Registration to Student SMS Template', 'school-management' ); ?>
</button>

<div class="collapse border border-top-0 border-primary p-3" id="wlsm_sms_student_registration_to_student_fields">

	<div class="wlsm_sms_template wlsm_sms_student_registration_to_student">
		<div class="row">
			<div class="col-md-3">
				<label for="wlsm_sms_student_registration_to_student_enable" class="wlsm-font-bold">
					<?php esc_html_e( 'Student Registration to Student SMS', 'school-management' ); ?>:
				</label>
			</div>
			<div class="col-md-9">
				<div class="form-group">
					<label for="wlsm_sms_student_registration_to_student_enable" class="wlsm-font-bold">
						<input <?php checked( $sms_student_registration_to_student_enable, true, true ); ?> type="checkbox" name="sms_student_registration_to_student_enable" id="wlsm_sms_student_registration_to_student_enable" value="1">
						<?php esc_html_e( 'Enable', 'school-management' ); ?>
					</label>
				</div>
			</div>
		</div>
	</div>

	<div class="wlsm_sms_template wlsm_sms_student_registration_to_student mb-3">
		<div class="row">
			<div class="col-md-12">
				<span class="wlsm-font-bold text-dark"><?php esc_html_e( 'You can use the following variables:', 'school-management' ); ?></span>
				<div class="row">
					<?php foreach ( $sms_student_registration_to_student_placeholders as $key => $value ) { ?>
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

	<div class="wlsm_sms_template wlsm_sms_student_registration_to_student">
		<div class="row">
			<div class="col-md-3">
				<label for="wlsm_sms_student_registration_to_student_message" class="wlsm-font-bold"><?php esc_html_e( 'SMS Message', 'school-management' ); ?>:</label>
			</div>
			<div class="col-md-9">
				<div class="form-group">
					<textarea name="sms_student_registration_to_student_message" id="wlsm_sms_student_registration_to_student_message" class="form-control" rows="6" placeholder="<?php esc_attr_e( 'SMS Message', 'school-management' ); ?>"><?php echo esc_html( $sms_student_registration_to_student_message ); ?></textarea>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<label for="wlsm_sms_student_template_id_registration_to_student_message" class="wlsm-font-bold"><?php esc_html_e( 'Template ID', 'school-management' ); ?>:</label>
			</div>
			<div class="col-md-9">
				<div class="form-group">
					<textarea name="sms_student_template_id_registration_to_student_message" id="wlsm_sms_student_template_id_registration_to_student_message" class="form-control" rows="1" placeholder="<?php esc_attr_e( 'SMS Tepmplate ID', 'school-management' ); ?>"><?php echo esc_html( $sms_student_template_id_registration_to_student_message ); ?></textarea>
				</div>
			</div>
		</div>
	</div>

	<?php
	$sms_template = 'student_registration_to_student';
	require WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/sms-templates/test_sms.php';
	?>

</div>
