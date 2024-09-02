<?php
defined( 'ABSPATH' ) || die();

// Email Student Registration to Student settings.
$settings_email_student_registration_to_student = WLSM_M_Setting::get_settings_email_student_registration_to_student( $school_id );
$email_student_registration_to_student_enable   = $settings_email_student_registration_to_student['enable'];
$email_student_registration_to_student_subject  = $settings_email_student_registration_to_student['subject'];
$email_student_registration_to_student_body     = $settings_email_student_registration_to_student['body'];

$email_student_registration_to_student_placeholders = WLSM_Email::student_registration_to_student_placeholders();
?>
<button type="button" class="mt-2 btn btn-block btn-primary" data-toggle="collapse" data-target="#wlsm_email_student_registration_to_student_fields" aria-expanded="true" aria-controls="wlsm_email_student_registration_to_student_fields">
	<?php esc_html_e( 'Student Registration to Student Email Template', 'school-management' ); ?>
</button>

<div class="collapse border border-top-0 border-primary p-3" id="wlsm_email_student_registration_to_student_fields">

	<div class="wlsm_email_template wlsm_email_student_registration_to_student">
		<div class="row">
			<div class="col-md-3">
				<label for="wlsm_email_student_registration_to_student_enable" class="wlsm-font-bold">
					<?php esc_html_e( 'Student Registration to Student Email', 'school-management' ); ?>:
				</label>
			</div>
			<div class="col-md-9">
				<div class="form-group">
					<label for="wlsm_email_student_registration_to_student_enable" class="wlsm-font-bold">
						<input <?php checked( $email_student_registration_to_student_enable, true, true ); ?> type="checkbox" name="email_student_registration_to_student_enable" id="wlsm_email_student_registration_to_student_enable" value="1">
						<?php esc_html_e( 'Enable', 'school-management' ); ?>
					</label>
				</div>
			</div>
		</div>
	</div>

	<div class="wlsm_email_template wlsm_email_student_registration_to_student mb-3">
		<div class="row">
			<div class="col-md-12">
				<span class="wlsm-font-bold text-dark"><?php esc_html_e( 'You can use the following variables:', 'school-management' ); ?></span>
				<div class="row">
					<?php foreach ( $email_student_registration_to_student_placeholders as $key => $value ) { ?>
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

	<div class="wlsm_email_template wlsm_email_student_registration_to_student">
		<div class="row">
			<div class="col-md-3">
				<label for="wlsm_email_student_registration_to_student_subject" class="wlsm-font-bold"><?php esc_html_e( 'Email Subject', 'school-management' ); ?>:</label>
			</div>
			<div class="col-md-9">
				<div class="form-group">
					<input name="email_student_registration_to_student_subject" type="text" id="wlsm_email_student_registration_to_student_subject" value="<?php echo esc_attr( $email_student_registration_to_student_subject ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Email Subject', 'school-management' ); ?>">
				</div>
			</div>
		</div>
	</div>

	<div class="wlsm_email_template wlsm_email_student_registration_to_student">
		<div class="row">
			<div class="col-md-3">
				<label for="wlsm_email_student_registration_to_student_body" class="wlsm-font-bold"><?php esc_html_e( 'Email Body', 'school-management' ); ?>:</label>
			</div>
			<div class="col-md-9">
				<div class="form-group">
					<?php
					$settings = array(
						'media_buttons' => false,
						'textarea_name' => 'email_student_registration_to_student_body',
						'textarea_rows' => 10,
						'wpautop'       => false,
					);
					wp_editor( wp_kses_post( stripslashes( $email_student_registration_to_student_body ) ), 'wlsm_email_student_registration_to_student_body', $settings );
					?>
				</div>
			</div>
		</div>
	</div>

	<?php
	$email_template = 'student_registration_to_student';
	require WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/email-templates/test_email.php';
	?>

</div>
