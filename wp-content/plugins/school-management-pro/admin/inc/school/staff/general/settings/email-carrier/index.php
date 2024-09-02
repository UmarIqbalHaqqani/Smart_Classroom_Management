<?php
defined('ABSPATH') || die();

// Email settings.
$settings_email       = WLSM_M_Setting::get_settings_email($school_id);
$school_email_carrier = $settings_email['carrier'];

// Email carriers.
$email_carriers = WLSM_Email::email_carriers();

// WP_Mail settings.
$settings_wp_mail          = WLSM_M_Setting::get_settings_wp_mail($school_id);
$school_wp_mail_from_name  = $settings_wp_mail['from_name'];
$school_wp_mail_from_email = $settings_wp_mail['from_email'];

// SMTP settings.
$settings_smtp          = WLSM_M_Setting::get_settings_smtp($school_id);
$school_smtp_from_name  = $settings_smtp['from_name'];
$school_smtp_host       = $settings_smtp['host'];
$school_smtp_username   = $settings_smtp['username'];
$school_smtp_password   = $settings_smtp['password'];
$school_smtp_encryption = $settings_smtp['encryption'];
$school_smtp_port       = $settings_smtp['port'];
?>
<div class="tab-pane fade" id="wlsm-school-email-carrier" role="tabpanel" aria-labelledby="wlsm-school-email-carrier-tab">

	<div class="row">
		<div class="col-md-7">
			<form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="wlsm-save-school-email-carrier-settings-form">
				<?php
				$nonce_action = 'save-school-email-carrier-settings';
				$nonce        = wp_create_nonce($nonce_action);
				?>
				<input type="hidden" name="<?php echo esc_attr($nonce_action); ?>" value="<?php echo esc_attr($nonce); ?>">

				<input type="hidden" name="action" value="wlsm-save-school-email-carrier-settings">

				<div class="row">
					<div class="col-md-4">
						<label for="wlsm_email_carrier" class="wlsm-font-bold">
							<?php esc_html_e('Email Carrier', 'school-management'); ?>:
						</label>
					</div>
					<div class="col-md-8">
						<div class="form-group">
							<select name="email_carrier" id="wlsm_email_carrier" class="form-control">
								<?php foreach ($email_carriers as $key => $email_carrier) { ?>
									<option <?php selected($key, $school_email_carrier, true); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($email_carrier); ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>

				<div class="wlsm_email_carrier wlsm_wp_mail">
					<div class="row">
						<div class="col-md-4">
							<label for="wlsm_wp_mail_from_name" class="wlsm-font-bold"><?php esc_html_e('From Name', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<input name="wp_mail_from_name" type="text" id="wlsm_wp_mail_from_name" value="<?php echo esc_attr($school_wp_mail_from_name); ?>" class="form-control" placeholder="<?php esc_attr_e('From Name', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_email_carrier wlsm_wp_mail">
					<div class="row">
						<div class="col-md-4">
							<label for="wlsm_wp_mail_from_email" class="wlsm-font-bold"><?php esc_html_e('From Email', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<input name="wp_mail_from_email" type="email" id="wlsm_wp_mail_from_email" value="<?php echo esc_attr($school_wp_mail_from_email); ?>" class="form-control" placeholder="<?php esc_attr_e('From Email', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_email_carrier wlsm_smtp">
					<div class="row">
						<div class="col-md-4">
							<label for="wlsm_smtp_from_name" class="wlsm-font-bold"><?php esc_html_e('From Name', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<input name="smtp_from_name" type="text" id="wlsm_smtp_from_name" value="<?php echo esc_attr($school_smtp_from_name); ?>" class="form-control" placeholder="<?php esc_attr_e('From Name', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_email_carrier wlsm_smtp">
					<div class="row">
						<div class="col-md-4">
							<label for="wlsm_smtp_host" class="wlsm-font-bold"><?php esc_html_e('SMTP Host', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<input name="smtp_host" type="text" id="wlsm_smtp_host" value="<?php echo esc_attr($school_smtp_host); ?>" class="form-control" placeholder="<?php esc_attr_e('SMTP Host', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_email_carrier wlsm_smtp">
					<div class="row">
						<div class="col-md-4">
							<label for="wlsm_smtp_username" class="wlsm-font-bold"><?php esc_html_e('SMTP Username', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<input name="smtp_username" type="text" id="wlsm_smtp_username" value="<?php echo esc_attr($school_smtp_username); ?>" class="form-control" placeholder="<?php esc_attr_e('SMTP Username', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_email_carrier wlsm_smtp">
					<div class="row">
						<div class="col-md-4">
							<label for="wlsm_smtp_password" class="wlsm-font-bold"><?php esc_html_e('SMTP Password', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<input name="smtp_password" type="password" id="wlsm_smtp_password" class="form-control" placeholder="<?php esc_attr_e('SMTP Password', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_email_carrier wlsm_smtp">
					<div class="row">
						<div class="col-md-4">
							<label for="wlsm_smtp_encryption" class="wlsm-font-bold"><?php esc_html_e('SMTP Encryption', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<input name="smtp_encryption" type="text" id="wlsm_smtp_encryption" value="<?php echo esc_attr($school_smtp_encryption); ?>" class="form-control" placeholder="<?php esc_attr_e('SMTP Encryption', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_email_carrier wlsm_smtp">
					<div class="row">
						<div class="col-md-4">
							<label for="wlsm_smtp_port" class="wlsm-font-bold"><?php esc_html_e('SMTP Port', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<input name="smtp_port" type="text" id="wlsm_smtp_port" value="<?php echo esc_attr($school_smtp_port); ?>" class="form-control" placeholder="<?php esc_attr_e('SMTP Port', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn btn-primary" id="wlsm-save-school-email-carrier-settings-btn">
							<i class="fas fa-save"></i>&nbsp;
							<?php esc_html_e('Save', 'school-management'); ?>
						</button>
					</div>
				</div>
			</form>
		</div>

		<div class="col-md-5">
			<ul class="list-group list-group-flush">
				<li class="list-group-item"><?php esc_html_e('You can either use SMTP or WP_Mail as email carrier.', 'school-management'); ?></li>
				<li class="list-group-item">
					<span class="text-danger wlsm-font-bold"><?php esc_html_e('From May 30, 2022, Google no longer supports the use of third-party apps or devices which ask you to sign in to your Google Account using only your username and password', 'school-management'); ?></span>
					<span>To use Gmail Set email carrier to wp mail and install this wp mail smtp plugin and setup for gmail.</span>
					<a target="_blank" href="https://wordpress.org/plugins/wp-mail-smtp/" class="text-primary">WP Mail SMTP by WPForms</a><br>
				</li>
				<li class="list-group-item"><?php esc_html_e('For example, to use Gmail as SMTP provider, you need to set SMTP host, encryption, port number, username and password.', 'school-management'); ?>
				</li>
				
				<li class="list-group-item">
					<span class="wlsm-font-bold"><?php esc_html_e('Host:', 'school-management'); ?></span>
					<span>smtp.gmail.com</span>
				</li>
				<li class="list-group-item">
					<span class="wlsm-font-bold"><?php esc_html_e('Username:', 'school-management'); ?></span>
					<span><?php esc_html_e('Your Gmail account email.', 'school-management'); ?></span>
				</li>
				<li class="list-group-item">
					<span class="wlsm-font-bold"><?php esc_html_e('Password:', 'school-management'); ?></span>
					<span><?php esc_html_e('Your Gmail account password.', 'school-management'); ?></span>
				</li>
				<li class="list-group-item">
					<span class="wlsm-font-bold"><?php esc_html_e('Encryption:', 'school-management'); ?></span>
					<span>tls</span>
				</li>
				<li class="list-group-item">
					<span class="wlsm-font-bold"><?php esc_html_e('Port:', 'school-management'); ?></span>
					<span>587</span>
				</li>
				
			</ul>
		</div>
	</div>

</div>