<?php
defined( 'ABSPATH' ) || die();

$page_url = WLSM_M_Staff_General::get_notifications_page_url();

$school_id  = $current_school['id'];

$nonce_action = 'send-notification';

$classes = WLSM_M_Staff_Class::fetch_classes( $school_id );

$email_custom_message_placeholders = WLSM_Email::custom_message_placeholders();
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php esc_html_e( 'Send Notifications', 'school-management' ); ?>
				</span>
			</span>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-send-notification-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-send-notification">

			<div class="wlsm-form-section">
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_class" class="wlsm-font-bold">
							<?php esc_html_e( 'Class', 'school-management' ); ?>:
						</label>
						<select name="class_id" class="form-control selectpicker" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-class-sections' ) ); ?>" id="wlsm_class" data-live-search="true">
							<option value=""><?php esc_html_e( 'Select Class', 'school-management' ); ?></option>
							<?php foreach ( $classes as $class ) { ?>
							<option value="<?php echo esc_attr( $class->ID ); ?>">
								<?php echo esc_html( WLSM_M_Class::get_label_text( $class->label ) ); ?>
							</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_section" class="wlsm-font-bold">
							<?php esc_html_e( 'Section', 'school-management' ); ?>:
						</label>
						<select name="section_id" class="form-control selectpicker wlsm_section" id="wlsm_section" data-live-search="true" title="<?php esc_attr_e( 'All Sections', 'school-management' ); ?>" data-all-sections="1" data-fetch-students="1" data-skip-transferred="0" data-only-active="0" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-section-students' ) ); ?>">
						</select>
					</div>
					<div class="form-group col-md-4 wlsm-student-select-block">
						<label for="wlsm_student" class="wlsm-font-bold">
							<?php esc_html_e( 'Students', 'school-management' ); ?>:
						</label>
						<select name="student[]" class="form-control selectpicker" id="wlsm_student" multiple data-live-search="true" data-actions-box="true" data-none-selected-text="<?php esc_attr_e( 'Select Students', 'school-management' ); ?>">
						</select>
					</div>
				</div>
			</div>

			<div class="row my-2">
				<div class="col-md-12">
					<span class="wlsm-font-bold text-dark"><?php esc_html_e( 'You can use the following variables:', 'school-management' ); ?></span>
					<div class="row">
						<?php foreach ( $email_custom_message_placeholders as $key => $value ) { ?>
						<div class="col-sm-6 col-md-3 pb-1 pt-1 border">
							<span class="wlsm-font-bold text-secondary"><?php echo esc_html( $value ); ?></span>
							<br>
							<span><?php echo esc_html( $key ); ?></span>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>

			<div class="wlsm-form-section bg-primary text-white mt-2">
				<div class="form-row mt-3">
					<div class="form-group col-md-12">
						<div class="form-check form-check-inline">
							<input checked class="form-check-input" type="checkbox" name="send_email" id="wlsm_send_email_notification" value="1">
							<label class="ml-1 form-check-label wlsm-font-bold" for="wlsm_send_email_notification">
								<?php esc_html_e( 'Send Email Notfication', 'school-management' ); ?>
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="checkbox" name="send_sms" id="wlsm_send_sms_notification" value="1">
							<label class="ml-1 form-check-label wlsm-font-bold" for="wlsm_send_sms_notification">
								<?php esc_html_e( 'Send SMS Notfication', 'school-management' ); ?>
							</label>
						</div>
					</div>
				</div>
			</div>

			<div class="wlsm-form-section wlsm-send-email bg-primary text-white mt-2">
				<div class="form-row mt-3">
					<div class="form-group col-md-12 mb-3">
						<label for="wlsm_email_body" class="wlsm-font-bold"><?php esc_html_e( 'Email Subject', 'school-management' ); ?>:</label>
						<input name="email_subject" type="text" id="wlsm_email_subject" class="form-control" placeholder="<?php esc_attr_e( 'Email Subject', 'school-management' ); ?>">
					</div>

					<div class="form-group col-md-12 mt-3">
						<label for="wlsm_email_body" class="wlsm-font-bold"><?php esc_html_e( 'Email Body', 'school-management' ); ?>:</label>
						<?php
						$settings = array(
							'media_buttons' => false,
							'textarea_name' => 'email_body',
							'textarea_rows' => 10,
							'wpautop'       => false,
						);
						wp_editor( '', 'wlsm_email_body', $settings );
						?>
					</div>
				</div>
			</div>

			<div class="wlsm-form-section wlsm-send-sms bg-primary text-white mt-2">
			<div class="form-group col-md-12 mb-3">
						<label for="wlsm_sms_template_id" class="wlsm-font-bold"><?php esc_html_e( 'Template Id ( For Indian User Only )', 'school-management' ); ?>:</label>
						<input name="template_id" type="text" id="wlsm_template_id" class="form-control" placeholder="<?php esc_attr_e( 'Template Id', 'school-management' ); ?>">
					</div>
				<div class="form-row mt-3">
					<div class="form-group col-md-12 mb-3">
						<label for="wlsm_sms_message" class="wlsm-font-bold"><?php esc_html_e( 'SMS Message', 'school-management' ); ?>:</label>
						<textarea name="sms_message" id="wlsm_sms_message" class="form-control" rows="6" placeholder="<?php esc_attr_e( 'SMS Message', 'school-management' ); ?>"></textarea>
					</div>
				</div>

				<div class="form-row mt-3">
					<div class="form-group col-md-12 mb-3">
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="to_parent_phone" id="wlsm_to_student" value="0" checked>
							<label class="ml-1 form-check-label wlsm-font-bold" for="wlsm_to_student">
								<?php esc_html_e( 'To Student Phone Number' ); ?>
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="to_parent_phone" id="wlsm_to_parent" value="1">
							<label class="ml-1 form-check-label wlsm-font-bold" for="wlsm_to_parent">
								<?php esc_html_e( 'To Parent Phone Number' ); ?>
							</label>
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-send-notification-btn">
						<i class="fas fa-paper-plane"></i>&nbsp;
						<?php esc_html_e( 'Send Notification', 'school-management' ); ?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
