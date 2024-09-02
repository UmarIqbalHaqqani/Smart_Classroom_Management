<?php
defined( 'ABSPATH' ) || die();

// SMS Student Admission settings.
$settings_sms_student_admission = WLSM_M_Setting::get_settings_sms_student_admission( $school_id );
$sms_student_admission_enable   = $settings_sms_student_admission['enable'];
$sms_student_admission_message  = $settings_sms_student_admission['message'];
$sms_student_template_id_admission= $settings_sms_student_admission['template_id'];

$sms_student_admission_placeholders = WLSM_SMS::student_admission_placeholders();
?>
<button type="button" class="mt-2 btn btn-block btn-primary" data-toggle="collapse" data-target="#wlsm_sms_student_admission_fields" aria-expanded="true" aria-controls="wlsm_sms_student_admission_fields">
	<?php esc_html_e( 'Student Admission SMS Template', 'school-management' ); ?>
</button>

<div class="collapse border border-top-0 border-primary p-3" id="wlsm_sms_student_admission_fields">

	<div class="wlsm_sms_template wlsm_sms_student_admission">
		<div class="row">
			<div class="col-md-3">
				<label for="wlsm_sms_student_admission_enable" class="wlsm-font-bold">
					<?php esc_html_e( 'Student Admission SMS', 'school-management' ); ?>:
				</label>
			</div>
			<div class="col-md-9">
				<div class="form-group">
					<label for="wlsm_sms_student_admission_enable" class="wlsm-font-bold">
						<input <?php checked( $sms_student_admission_enable, true, true ); ?> type="checkbox" name="sms_student_admission_enable" id="wlsm_sms_student_admission_enable" value="1">
						<?php esc_html_e( 'Enable', 'school-management' ); ?>
					</label>
				</div>
			</div>
		</div>
	</div>

	<div class="wlsm_sms_template wlsm_sms_student_admission mb-3">
		<div class="row">
			<div class="col-md-12">
				<span class="wlsm-font-bold text-dark"><?php esc_html_e( 'You can use the following variables:', 'school-management' ); ?></span>
				<div class="row">
					<?php foreach ( $sms_student_admission_placeholders as $key => $value ) { ?>
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

	<div class="wlsm_sms_template wlsm_sms_student_admission">
		<div class="row">
			<div class="col-md-3">
				<label for="wlsm_sms_student_admission_message" class="wlsm-font-bold"><?php esc_html_e( 'SMS Message', 'school-management' ); ?>:</label>
			</div>
			<div class="col-md-9">
				<div class="form-group">
					<textarea name="sms_student_admission_message" id="wlsm_sms_student_admission_message" class="form-control" rows="6" placeholder="<?php esc_attr_e( 'SMS Message', 'school-management' ); ?>"><?php echo esc_html( $sms_student_admission_message ); ?></textarea>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<label for="wlsm_sms_student_template_id_admission" class="wlsm-font-bold"><?php esc_html_e( 'Template ID', 'school-management' ); ?>:</label>
			</div>
			<div class="col-md-9">
				<div class="form-group">
					<textarea name="sms_student_template_id_admission" id="wlsm_sms_student_template_id_admission" class="form-control" rows="1" placeholder="<?php esc_attr_e( 'SMS Tepmplate ID', 'school-management' ); ?>"><?php echo esc_html( $sms_student_template_id_admission ); ?></textarea>
				</div>
				<label class="wlsm-font-bold"><?php esc_html_e( 'Note:  If you are using Template ID then use the templates text in message', 'school-management' ); ?></label>
			</div>
		</div>
	</div>

	<?php
	$sms_template = 'student_admission';
	require WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/sms-templates/test_sms.php';
	?>

</div>
