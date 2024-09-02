<?php
defined('ABSPATH') || die();

// SMS settings.
$settings_sms       = WLSM_M_Setting::get_settings_sms($school_id);
$school_sms_carrier = $settings_sms['carrier'];

// SMS carriers.
$sms_carriers = WLSM_SMS::sms_carriers();

// SMS Striker settings.
$settings_smsstriker         = WLSM_M_Setting::get_settings_smsstriker($school_id);
$school_smsstriker_username  = $settings_smsstriker['username'];
$school_smsstriker_password  = $settings_smsstriker['password'];
$school_smsstriker_sender_id = $settings_smsstriker['sender_id'];

$settings_whatsapp         = WLSM_M_Setting::get_settings_whatsapp($school_id);
$school_whatsapp_username  = $settings_whatsapp['username'];
$school_whatsapp_password  = $settings_whatsapp['password'];

// NEXTSMS settings.
$settings_nextsms         = WLSM_M_Setting::get_settings_nextsms($school_id);
$school_nextsms_username  = $settings_nextsms['username'];
$school_nextsms_password  = $settings_nextsms['password'];
$school_nextsms_sender_id = $settings_nextsms['sender_id'];

// SMS futuresol settings.
$settings_futuresol         = WLSM_M_Setting::get_settings_futuresol($school_id);
$school_futuresol_username  = $settings_futuresol['username'];
$school_futuresol_password  = $settings_futuresol['password'];
$school_futuresol_sender_id = $settings_futuresol['sender_id'];

// gateway sms settings.
$settings_gatewaysms         = WLSM_M_Setting::get_settings_gatewaysms($school_id);
$school_gatewaysms_username  = $settings_gatewaysms['username'];
$school_gatewaysms_password  = $settings_gatewaysms['password'];
$school_gatewaysms_sender_id = $settings_gatewaysms['sender_id'];
$school_gatewaysms_gwid = $settings_gatewaysms['gwid'];

// sms ir settings.
$settings_sms_ir           = WLSM_M_Setting::get_settings_sms_ir( $school_id );
$school_sms_ir_username    = $settings_sms_ir['username'];
$school_sms_ir_password    = $settings_sms_ir['password'];
$school_sms_ir_sender_id   = $settings_sms_ir['sender_id'];
$school_sms_ir_line_number = $settings_sms_ir['line_number'];


// bulksmsgateway sms settings.
$settings_bulksmsgateway         = WLSM_M_Setting::get_settings_bulksmsgateway($school_id);
$school_bulksmsgateway_username  = $settings_bulksmsgateway['username'];
$school_bulksmsgateway_password  = $settings_bulksmsgateway['password'];
$school_bulksmsgateway_sender_id = $settings_bulksmsgateway['sender_id'];
$school_bulksmsgateway_template_id = $settings_bulksmsgateway['template_id'];

// MsgClub settings.
$settings_msgclub                = WLSM_M_Setting::get_settings_msgclub($school_id);
$school_msgclub_auth_key         = $settings_msgclub['auth_key'];
$school_msgclub_sender_id        = $settings_msgclub['sender_id'];
$school_msgclub_route_id         = $settings_msgclub['route_id'];
$school_msgclub_sms_content_type = $settings_msgclub['sms_content_type'];
$school_msgclub_entityid         = $settings_msgclub['entityid'];
$school_msgclub_tmid             = $settings_msgclub['tmid'];

// Point SMS settings.
$settings_pointsms         = WLSM_M_Setting::get_settings_pointsms($school_id);
$school_pointsms_username  = $settings_pointsms['username'];
$school_pointsms_sender_id = $settings_pointsms['sender_id'];
$school_pointsms_channel   = $settings_pointsms['channel'];
$school_pointsms_route     = $settings_pointsms['route'];
$school_pointsms_peid      = $settings_pointsms['peid'];

// India Text settings.
$settings_indiatext         = WLSM_M_Setting::get_settings_indiatext($school_id);
$school_indiatext_username  = $settings_indiatext['username'];
$school_indiatext_sender_id = $settings_indiatext['sender_id'];
$school_indiatext_channel   = $settings_indiatext['channel'];
$school_indiatext_route     = $settings_indiatext['route'];

// vinuthan SMS settings.
$settings_vinuthansms = WLSM_M_Setting::get_settings_vinuthan($school_id);
$school_vinuthansms_username = $settings_vinuthansms['username'];
$school_vinuthansms_sender_id = $settings_vinuthansms['sender_id'];
$school_vinuthansms_channel = $settings_vinuthansms['channel'];
$school_vinuthansms_route = $settings_vinuthansms['route'];

// pob SMS settings.
$settings_pob         = WLSM_M_Setting::get_settings_pob($school_id);
$school_pob_username  = $settings_pob['username'];
$school_pob_password  = $settings_pob['password'];
$school_pob_sender_id = $settings_pob['sender_id'];


// Nexmo settings.
$settings_nexmo          = WLSM_M_Setting::get_settings_nexmo($school_id);
$school_nexmo_api_key    = $settings_nexmo['api_key'];
$school_nexmo_api_secret = $settings_nexmo['api_secret'];
$school_nexmo_from       = $settings_nexmo['from'];

// smartsms settings.
$settings_smartsms          = WLSM_M_Setting::get_settings_smartsms($school_id);
$school_smartsms_api_key    = $settings_smartsms['api_key'];
$school_smartsms_api_secret = $settings_smartsms['api_secret'];
$school_smartsms_from       = $settings_smartsms['from'];

// Twilio settings.
$settings_twilio     = WLSM_M_Setting::get_settings_twilio($school_id);
$school_twilio_sid   = $settings_twilio['sid'];
$school_twilio_token = $settings_twilio['token'];
$school_twilio_from  = $settings_twilio['from'];

// Msg91 settings.
$settings_msg91       = WLSM_M_Setting::get_settings_msg91($school_id);
$school_msg91_authkey = $settings_msg91['authkey'];
$school_msg91_route   = $settings_msg91['route'];
$school_msg91_sender  = $settings_msg91['sender'];
$school_msg91_country = $settings_msg91['country'];

// Textlocal settings.
$settings_textlocal       = WLSM_M_Setting::get_settings_textlocal($school_id);
$school_textlocal_api_key = $settings_textlocal['api_key'];
$school_textlocal_sender  = $settings_textlocal['sender'];

// Tecxsms settings.
$settings_tecxsms       = WLSM_M_Setting::get_settings_tecxsms($school_id);
$school_tecxsms_api_key = $settings_tecxsms['api_key'];
$school_tecxsms_sender  = $settings_tecxsms['sender'];

  // switchportlimited settings.
$settings_switchportlimited         = WLSM_M_Setting::get_settings_switchportlimited($school_id);
$school_switchportlimited_api_key   = $settings_switchportlimited['api_key'];
$school_switchportlimited_sender    = $settings_switchportlimited['sender'];
$school_switchportlimited_client_id = $settings_switchportlimited['client_id'];

// bdbsms settings.
$settings_bdbsms       = WLSM_M_Setting::get_settings_bdbsms($school_id);
$school_bdbsms_api_key = $settings_bdbsms['api_key'];

// kivalosolutions settings.
$settings_kivalosolutions       = WLSM_M_Setting::get_settings_kivalosolutions($school_id);

$school_kivalosolutions_api_key = $settings_kivalosolutions['api_key'];
$school_kivalosolutions_sender  = $settings_kivalosolutions['sender'];

// EBulkSMS settings.
$settings_ebulksms        = WLSM_M_Setting::get_settings_ebulksms($school_id);
$school_ebulksms_username = $settings_ebulksms['username'];
$school_ebulksms_api_key  = $settings_ebulksms['api_key'];
$school_ebulksms_sender   = $settings_ebulksms['sender'];

// sendpk settings.
$settings_sendpk        = WLSM_M_Setting::get_settings_sendpk($school_id);
$school_sendpk_api_key  = $settings_sendpk['api_key'];
$school_sendpk_sender   = $settings_sendpk['sender'];

// SMS logixsms settings.
$settings_logixsms         = WLSM_M_Setting::get_settings_logixsms($school_id);
$school_logixsms_username  = $settings_logixsms['username'];
$school_logixsms_password  = $settings_logixsms['password'];
$school_logixsms_sender_id = $settings_logixsms['sender_id'];
?>
<div class="tab-pane fade" id="wlsm-school-sms-carrier" role="tabpanel" aria-labelledby="wlsm-school-sms-carrier-tab">

	<div class="row">
		<div class="col-md-9">
			<form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="wlsm-save-school-sms-carrier-settings-form">
				<?php
				$nonce_action = 'save-school-sms-carrier-settings';
				$nonce        = wp_create_nonce($nonce_action);
				?>
				<input type="hidden" name="<?php echo esc_attr($nonce_action); ?>" value="<?php echo esc_attr($nonce); ?>">

				<input type="hidden" name="action" value="wlsm-save-school-sms-carrier-settings">

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_sms_carrier" class="wlsm-font-bold">
							<?php esc_html_e('SMS Carrier', 'school-management'); ?>:
						</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<select name="sms_carrier" id="wlsm_sms_carrier" class="form-control">
								<?php foreach ($sms_carriers as $key => $sms_carrier) { ?>
									<option <?php selected($key, $school_sms_carrier, true); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($sms_carrier); ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_smsstriker">
					<div class="row">
						<div class="col-md-3">
							<label class="wlsm-font-bold"><?php esc_html_e('SMS Package', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<a class="wlsm-font-bold" target="_blank" href="https://www.smsstriker.com">
									<?php esc_html_e('Click for SMS Package Features and Pricing', 'school-management'); ?>
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_smsstriker">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_smsstriker_sender_id" class="wlsm-font-bold"><?php esc_html_e('Sender ID', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="smsstriker_sender_id" type="text" id="wlsm_smsstriker_sender_id" value="<?php echo esc_attr($school_smsstriker_sender_id); ?>" class="form-control" placeholder="<?php esc_attr_e('SMSStriker Sender ID', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_smsstriker">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_smsstriker_username" class="wlsm-font-bold"><?php esc_html_e('Username', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="smsstriker_username" type="text" id="wlsm_smsstriker_username" value="<?php echo esc_attr($school_smsstriker_username); ?>" class="form-control" placeholder="<?php esc_attr_e('SMSStriker Username', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_smsstriker">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_smsstriker_password" class="wlsm-font-bold"><?php esc_html_e('Password', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="smsstriker_password" type="password" id="wlsm_smsstriker_password" class="form-control" placeholder="<?php esc_attr_e('SMSStriker Password', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_nextsms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_nextsms_sender_id" class="wlsm-font-bold"><?php esc_html_e('Sender ID', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="nextsms_sender_id" type="text" id="wlsm_nextsms_sender_id" value="<?php echo esc_attr($school_nextsms_sender_id); ?>" class="form-control" placeholder="<?php esc_attr_e('nextsms Sender ID', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_nextsms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_nextsms_username" class="wlsm-font-bold"><?php esc_html_e('Username', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="nextsms_username" type="text" id="wlsm_nextsms_username" value="<?php echo esc_attr($school_nextsms_username); ?>" class="form-control" placeholder="<?php esc_attr_e('nextsms Username', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_nextsms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_nextsms_password" class="wlsm-font-bold"><?php esc_html_e('Password', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="nextsms_password" type="password" id="wlsm_nextsms_password" class="form-control" placeholder="<?php esc_attr_e('nextsms Password', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

					<div class="wlsm_sms_carrier wlsm_whatsapp">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_whatsapp_username" class="wlsm-font-bold"><?php esc_html_e('Username', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="whatsapp_username" type="text" id="wlsm_whatsapp_username" value="<?php echo esc_attr($school_whatsapp_username); ?>" class="form-control" placeholder="<?php esc_attr_e('whatsapp Username', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_whatsapp">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_whatsapp_password" class="wlsm-font-bold"><?php esc_html_e('Password', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="whatsapp_password" type="password" id="wlsm_whatsapp_password" class="form-control" placeholder="<?php esc_attr_e('whatsapp Password', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>
			
				<div class="wlsm_sms_carrier wlsm_logixsms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_logixsms_sender_id" class="wlsm-font-bold"><?php esc_html_e('Sender ID', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="logixsms_sender_id" type="text" id="wlsm_logixsms_sender_id" value="<?php echo esc_attr($school_logixsms_sender_id); ?>" class="form-control" placeholder="<?php esc_attr_e('logixsms Sender ID', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_logixsms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_logixsms_username" class="wlsm-font-bold"><?php esc_html_e('Username', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="logixsms_username" type="text" id="wlsm_logixsms_username" value="<?php echo esc_attr($school_logixsms_username); ?>" class="form-control" placeholder="<?php esc_attr_e('logixsms Username', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_logixsms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_logixsms_password" class="wlsm-font-bold"><?php esc_html_e('Password', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="logixsms_password" type="password" id="wlsm_logixsms_password" class="form-control" placeholder="<?php esc_attr_e('logixsms Password', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_futuresol">
					<div class="row">
						<div class="col-md-3">
							<label class="wlsm-font-bold"><?php esc_html_e('SMS Package', 'school-management'); ?>:</label>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_futuresol">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_futuresol_sender_id" class="wlsm-font-bold"><?php esc_html_e('Sender ID', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="futuresol_sender_id" type="text" id="wlsm_futuresol_sender_id" value="<?php echo esc_attr($school_futuresol_sender_id); ?>" class="form-control" placeholder="<?php esc_attr_e('futuresol Sender ID', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_futuresol">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_futuresol_username" class="wlsm-font-bold"><?php esc_html_e('Username', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="futuresol_username" type="text" id="wlsm_futuresol_username" value="<?php echo esc_attr($school_futuresol_username); ?>" class="form-control" placeholder="<?php esc_attr_e('futuresol Username', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_futuresol">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_futuresol_password" class="wlsm-font-bold"><?php esc_html_e('Password', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="futuresol_password" type="password" id="wlsm_futuresol_password" class="form-control" placeholder="<?php esc_attr_e('futuresol Password', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_gatewaysms">
					<div class="row">
						<div class="col-md-3">
							<label class="wlsm-font-bold"><?php esc_html_e('SMS Package', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<a class="wlsm-font-bold" target="_blank" href="https://getwaysms.com">
									<?php esc_html_e('Click for SMS Package Features and Pricing', 'school-management'); ?>
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_gatewaysms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_gatewaysms_sender_id" class="wlsm-font-bold"><?php esc_html_e('Sender ID', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="gatewaysms_sender_id" type="text" id="wlsm_gatewaysms_sender_id" value="<?php echo esc_attr($school_gatewaysms_sender_id); ?>" class="form-control" placeholder="<?php esc_attr_e('gatewaysms Sender ID', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

                <div class="wlsm_sms_carrier wlsm_gatewaysms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_gatewaysms_gwid" class="wlsm-font-bold"><?php esc_html_e('Gwid ID', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="gatewaysms_gwid" type="text" id="wlsm_gatewaysms_gwid" value="<?php echo esc_attr($school_gatewaysms_gwid); ?>" class="form-control" placeholder="<?php esc_attr_e('gatewaysms gwid', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_gatewaysms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_gatewaysms_username" class="wlsm-font-bold"><?php esc_html_e('Username', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="gatewaysms_username" type="text" id="wlsm_gatewaysms_username" value="<?php echo esc_attr($school_gatewaysms_username); ?>" class="form-control" placeholder="<?php esc_attr_e('gatewaysms Username', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_gatewaysms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_gatewaysms_password" class="wlsm-font-bold"><?php esc_html_e('Password', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="gatewaysms_password" type="password" id="wlsm_gatewaysms_password" class="form-control" placeholder="<?php esc_attr_e('gatewaysms Password', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_sms_ir">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_sms_ir_sender_id" class="wlsm-font-bold"><?php esc_html_e('API ID', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="sms_ir_sender_id" type="text" id="wlsm_sms_ir_sender_id" value="<?php echo esc_attr($school_sms_ir_sender_id); ?>" class="form-control" placeholder="<?php esc_attr_e('sms_ir Sender ID', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

                <div class="wlsm_sms_carrier wlsm_sms_ir">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_sms_ir_line_number" class="wlsm-font-bold"><?php esc_html_e('Line_number ID', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="sms_ir_line_number" type="text" id="wlsm_sms_ir_line_number" value="<?php echo esc_attr($school_sms_ir_line_number); ?>" class="form-control" placeholder="<?php esc_attr_e('sms_ir line_number', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_sms_ir">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_sms_ir_username" class="wlsm-font-bold"><?php esc_html_e('Username', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="sms_ir_username" type="text" id="wlsm_sms_ir_username" value="<?php echo esc_attr($school_sms_ir_username); ?>" class="form-control" placeholder="<?php esc_attr_e('sms_ir Username', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_sms_ir">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_sms_ir_password" class="wlsm-font-bold"><?php esc_html_e('Password', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="sms_ir_password" type="password" id="wlsm_sms_ir_password" class="form-control" placeholder="<?php esc_attr_e('sms_ir Password', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_bulksmsgateway">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_bulksmsgateway_sender_id" class="wlsm-font-bold"><?php esc_html_e('Sender ID', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="bulksmsgateway_sender_id" type="text" id="wlsm_bulksmsgateway_sender_id" value="<?php echo esc_attr($school_bulksmsgateway_sender_id); ?>" class="form-control" placeholder="<?php esc_attr_e('bulksmsgateway Sender ID', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

                <div class="wlsm_sms_carrier wlsm_bulksmsgateway">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_bulksmsgateway_template_id" class="wlsm-font-bold"><?php esc_html_e('Template ID', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="bulksmsgateway_template_id" type="text" id="wlsm_bulksmsgateway_template_id" value="<?php echo esc_attr($school_bulksmsgateway_template_id); ?>" class="form-control" placeholder="<?php esc_attr_e('bulksmsgateway template id', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_bulksmsgateway">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_bulksmsgateway_username" class="wlsm-font-bold"><?php esc_html_e('Username', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="bulksmsgateway_username" type="text" id="wlsm_bulksmsgateway_username" value="<?php echo esc_attr($school_bulksmsgateway_username); ?>" class="form-control" placeholder="<?php esc_attr_e('bulksmsgateway Username', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_bulksmsgateway">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_bulksmsgateway_password" class="wlsm-font-bold"><?php esc_html_e('Password', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="bulksmsgateway_password" type="password" id="wlsm_bulksmsgateway_password" class="form-control" placeholder="<?php esc_attr_e('bulksmsgateway Password', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_msgclub">
					<div class="row">
						<div class="col-md-3">
							<label class="wlsm-font-bold"><?php esc_html_e('SMS Package', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<a class="wlsm-font-bold" target="_blank" href="https://intechnosoftware.com/bulk-sms-service/">
									<?php esc_html_e('Click for SMS Package Features and Pricing', 'school-management'); ?>
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_msgclub">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_msgclub_auth_key" class="wlsm-font-bold"><?php esc_html_e('Auth Key', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="msgclub_auth_key" type="text" id="wlsm_msgclub_auth_key" value="<?php echo esc_attr($school_msgclub_auth_key); ?>" class="form-control" placeholder="<?php esc_attr_e('Intechno Msg Auth Key', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_msgclub">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_msgclub_sender_id" class="wlsm-font-bold"><?php esc_html_e('Sender ID', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="msgclub_sender_id" type="text" id="wlsm_msgclub_sender_id" value="<?php echo esc_attr($school_msgclub_sender_id); ?>" class="form-control" placeholder="<?php esc_attr_e('Intechno Msg Sender ID', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_msgclub">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_msgclub_route_id" class="wlsm-font-bold"><?php esc_html_e('Route ID', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="msgclub_route_id" type="text" id="wlsm_msgclub_route_id" value="<?php echo esc_attr($school_msgclub_route_id); ?>" class="form-control" placeholder="<?php esc_attr_e('Intechno Msg Route ID', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_msgclub">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_msgclub_sms_content_type" class="wlsm-font-bold"><?php esc_html_e('SMS Content Type', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="msgclub_sms_content_type" type="text" id="wlsm_msgclub_sms_content_type" value="<?php echo esc_attr($school_msgclub_sms_content_type); ?>" class="form-control" placeholder="<?php esc_attr_e('Intechno Msg SMS Content Type', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_msgclub">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_msgclub_entityid" class="wlsm-font-bold"><?php esc_html_e('PEID or Entity id should be of 19 digit', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="msgclub_entityid" type="text" id="wlsm_msgclub_entityid" value="<?php echo esc_attr($school_msgclub_entityid); ?>" class="form-control" placeholder="<?php esc_attr_e('Intechno Msg SMS', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_msgclub">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_msgclub_tmid" class="wlsm-font-bold"><?php esc_html_e('Telemarketer id', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="msgclub_tmid" type="text" id="wlsm_msgclub_tmid" value="<?php echo esc_attr($school_msgclub_tmid); ?>" class="form-control" placeholder="<?php esc_attr_e('Intechno Msg SMS', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_pointsms">
					<div class="row">
						<div class="col-md-3">
							<label class="wlsm-font-bold"><?php esc_html_e('SMS Package', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<a class="wlsm-font-bold" target="_blank" href="https://intechnosoftware.com/bulk-sms-service/">
									<?php esc_html_e('Click for SMS Package Features and Pricing', 'school-management'); ?>
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_pointsms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_pointsms_sender_id" class="wlsm-font-bold"><?php esc_html_e('Sender ID', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="pointsms_sender_id" type="text" id="wlsm_pointsms_sender_id" value="<?php echo esc_attr($school_pointsms_sender_id); ?>" class="form-control" placeholder="<?php esc_attr_e('Intechno Point Sender ID', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_pointsms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_pointsms_username" class="wlsm-font-bold"><?php esc_html_e('Username', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="pointsms_username" type="text" id="wlsm_pointsms_username" value="<?php echo esc_attr($school_pointsms_username); ?>" class="form-control" placeholder="<?php esc_attr_e('Intechno Point Username', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_pointsms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_pointsms_password" class="wlsm-font-bold"><?php esc_html_e('Password', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="pointsms_password" type="password" id="wlsm_pointsms_password" class="form-control" placeholder="<?php esc_attr_e('Intechno Point Password', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_pointsms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_pointsms_channel" class="wlsm-font-bold"><?php esc_html_e('Channel', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="pointsms_channel" type="text" id="wlsm_pointsms_channel" value="<?php echo esc_attr($school_pointsms_channel); ?>" class="form-control" placeholder="<?php esc_attr_e('Intechno Point Channel: Trans or Promo', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_pointsms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_pointsms_route" class="wlsm-font-bold"><?php esc_html_e('Route', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="pointsms_route" type="text" id="wlsm_pointsms_route" value="<?php echo esc_attr($school_pointsms_route); ?>" class="form-control" placeholder="<?php esc_attr_e('Intechno Point Route', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_pointsms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_pointsms_peid" class="wlsm-font-bold"><?php esc_html_e('Peid', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="pointsms_peid" type="text" id="wlsm_pointsms_peid" value="<?php echo esc_attr($school_pointsms_peid); ?>" class="form-control" placeholder="<?php esc_attr_e('Intechno Point peid', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_indiatext">
					<div class="row">
						<div class="col-md-3">
							<label class="wlsm-font-bold"><?php esc_html_e('SMS Package', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<a class="wlsm-font-bold" target="_blank" href="http://sms.indiatext.in/">
									<?php esc_html_e('Click for SMS Package Features and Pricing', 'school-management'); ?>
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_indiatext">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_indiatext_sender_id" class="wlsm-font-bold"><?php esc_html_e('Sender ID', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="indiatext_sender_id" type="text" id="wlsm_indiatext_sender_id" value="<?php echo esc_attr($school_indiatext_sender_id); ?>" class="form-control" placeholder="<?php esc_attr_e('India Text Sender ID', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_indiatext">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_indiatext_username" class="wlsm-font-bold"><?php esc_html_e('Username', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="indiatext_username" type="text" id="wlsm_indiatext_username" value="<?php echo esc_attr($school_indiatext_username); ?>" class="form-control" placeholder="<?php esc_attr_e('India Text Username', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_indiatext">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_indiatext_password" class="wlsm-font-bold"><?php esc_html_e('Password', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="indiatext_password" type="password" id="wlsm_indiatext_password" class="form-control" placeholder="<?php esc_attr_e('India Text Password', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_indiatext">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_indiatext_channel" class="wlsm-font-bold"><?php esc_html_e('Channel', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="indiatext_channel" type="text" id="wlsm_indiatext_channel" value="<?php echo esc_attr($school_indiatext_channel); ?>" class="form-control" placeholder="<?php esc_attr_e('India Text Channel: Trans or Promo', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_indiatext">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_indiatext_route" class="wlsm-font-bold"><?php esc_html_e('Route', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="indiatext_route" type="text" id="wlsm_indiatext_route" value="<?php echo esc_attr($school_indiatext_route); ?>" class="form-control" placeholder="<?php esc_attr_e('India Text Route', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>


				<div class="wlsm_sms_carrier wlsm_vinuthansms">
					<div class="row">
						<div class="col-md-3">
							<label class="wlsm-font-bold"><?php esc_html_e('SMS Package', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<a class="wlsm-font-bold" target="_blank" href="http://sms.vinuthan.in/">
									<?php esc_html_e('Click for SMS Package Features and Pricing', 'school-management'); ?>
								</a>
							</div>
						</div>
					</div>
				</div>
				

				<div class="wlsm_sms_carrier wlsm_vinuthansms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_vinuthansms_sender_id" class="wlsm-font-bold"><?php esc_html_e('Sender ID', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="vinuthansms_sender_id" type="text" id="wlsm_vinuthansms_sender_id" value="<?php echo esc_attr($school_vinuthansms_sender_id); ?>" class="form-control" placeholder="<?php esc_attr_e('Vinuthan sms Sender ID', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_vinuthansms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_vinuthansms_username" class="wlsm-font-bold"><?php esc_html_e('Authkey', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="vinuthansms_username" type="text" id="wlsm_vinuthansms_username" value="<?php echo esc_attr($school_vinuthansms_username); ?>" class="form-control" placeholder="<?php esc_attr_e('Vinuthan sms Authkey', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>


				<div class="wlsm_sms_carrier wlsm_vinuthansms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_vinuthansms_channel" class="wlsm-font-bold"><?php esc_html_e('Type', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="vinuthansms_channel" type="text" id="wlsm_vinuthansms_channel" value="<?php echo esc_attr($school_vinuthansms_channel); ?>" class="form-control" placeholder="<?php esc_attr_e('Vinuthan sms Type', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_vinuthansms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_vinuthansms_route" class="wlsm-font-bold"><?php esc_html_e('Route', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="vinuthansms_route" type="text" id="wlsm_vinuthansms_route" value="<?php echo esc_attr($school_vinuthansms_route); ?>" class="form-control" placeholder="<?php esc_attr_e('Vinuthan sms Route', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>


				<div class="wlsm_sms_carrier wlsm_pob">
					<div class="row">
						<div class="col-md-3">
							<label class="wlsm-font-bold"><?php esc_html_e('SMS Package', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<a class="wlsm-font-bold" target="_blank" href="https://talk.pob.ng/">
									<?php esc_html_e('Click for SMS Package Features and Pricing', 'school-management'); ?>
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_pob">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_pob_username" class="wlsm-font-bold"><?php esc_html_e('Username', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="pob_username" type="text" id="wlsm_pob_username" value="<?php echo esc_attr($school_pob_username); ?>" class="form-control" placeholder="<?php esc_attr_e('Pob Talk Username', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_pob">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_pob_password" class="wlsm-font-bold"><?php esc_html_e('Password', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="pob_password" type="password" id="wlsm_pob_password" class="form-control" placeholder="<?php esc_attr_e('Pob Talk Password', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_pob">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_pob_sender_id" class="wlsm-font-bold"><?php esc_html_e('Sender ID', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="pob_sender_id" type="text" id="wlsm_pob_sender_id" value="<?php echo esc_attr($school_pob_sender_id); ?>" class="form-control" placeholder="<?php esc_attr_e('Pob Talk Sender ID', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>


				<div class="wlsm_sms_carrier wlsm_nexmo">
					<div class="row">
						<div class="col-md-3">
							<label class="wlsm-font-bold"><?php esc_html_e('SMS Package', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<a class="wlsm-font-bold" target="_blank" href="https://www.vonage.com/communications-apis/pricing/?icmp=l3nav_pricing_novalue">
									<?php esc_html_e('Click for SMS Package Features and Pricing', 'school-management'); ?>
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_nexmo">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_nexmo_api_key" class="wlsm-font-bold"><?php esc_html_e('API Key', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="nexmo_api_key" type="text" id="wlsm_nexmo_api_key" value="<?php echo esc_attr($school_nexmo_api_key); ?>" class="form-control" placeholder="<?php esc_attr_e('Nexmo API Key', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_nexmo">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_nexmo_api_secret" class="wlsm-font-bold"><?php esc_html_e('API Secret', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="nexmo_api_secret" type="text" id="wlsm_nexmo_api_secret" value="<?php echo esc_attr($school_nexmo_api_secret); ?>" class="form-control" placeholder="<?php esc_attr_e('Nexmo API Secret', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_nexmo">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_nexmo_from" class="wlsm-font-bold"><?php esc_html_e('From', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="nexmo_from" type="text" id="wlsm_nexmo_from" value="<?php echo esc_attr($school_nexmo_from); ?>" class="form-control" placeholder="<?php esc_attr_e('Nexmo From', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_smartsms">
				</div>

				<div class="wlsm_sms_carrier wlsm_smartsms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_smartsms_api_key" class="wlsm-font-bold"><?php esc_html_e('API Key', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="smartsms_api_key" type="text" id="wlsm_smartsms_api_key" value="<?php echo esc_attr($school_smartsms_api_key); ?>" class="form-control" placeholder="<?php esc_attr_e('smartsms API Key', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_smartsms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_smartsms_api_secret" class="wlsm-font-bold"><?php esc_html_e('API Secret', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="smartsms_api_secret" type="text" id="wlsm_smartsms_api_secret" value="<?php echo esc_attr($school_smartsms_api_secret); ?>" class="form-control" placeholder="<?php esc_attr_e('smartsms API Secret', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_smartsms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_smartsms_from" class="wlsm-font-bold"><?php esc_html_e('From', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="smartsms_from" type="text" id="wlsm_smartsms_from" value="<?php echo esc_attr($school_smartsms_from); ?>" class="form-control" placeholder="<?php esc_attr_e('smartsms From', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_twilio">
					<div class="row">
						<div class="col-md-3">
							<label class="wlsm-font-bold"><?php esc_html_e('SMS Package', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<a class="wlsm-font-bold" target="_blank" href="https://www.twilio.com/pricing">
									<?php esc_html_e('Click for SMS Package Features and Pricing', 'school-management'); ?>
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_twilio">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_twilio_sid" class="wlsm-font-bold"><?php esc_html_e('SID', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="twilio_sid" type="text" id="wlsm_twilio_sid" value="<?php echo esc_attr($school_twilio_sid); ?>" class="form-control" placeholder="<?php esc_attr_e('Twilio Account SID', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_twilio">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_twilio_token" class="wlsm-font-bold"><?php esc_html_e('Auth Token', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="twilio_token" type="text" id="wlsm_twilio_token" value="<?php echo esc_attr($school_twilio_token); ?>" class="form-control" placeholder="<?php esc_attr_e('Twilio Auth Token', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_twilio">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_twilio_from" class="wlsm-font-bold"><?php esc_html_e('From', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="twilio_from" type="text" id="wlsm_twilio_from" value="<?php echo esc_attr($school_twilio_from); ?>" class="form-control" placeholder="<?php esc_attr_e('A Twilio phone number you purchased at twilio.com/console', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_msg91">
					<div class="row">
						<div class="col-md-3">
							<label class="wlsm-font-bold"><?php esc_html_e('SMS Package', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<a class="wlsm-font-bold" target="_blank" href="https://msg91.com/pricing/">
									<?php esc_html_e('Click for SMS Package Features and Pricing', 'school-management'); ?>
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_msg91">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_msg91_authkey" class="wlsm-font-bold"><?php esc_html_e('Auth Key', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="msg91_authkey" type="text" id="wlsm_msg91_authkey" value="<?php echo esc_attr($school_msg91_authkey); ?>" class="form-control" placeholder="<?php esc_attr_e('Msg91 Auth Key', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_msg91">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_msg91_route" class="wlsm-font-bold"><?php esc_html_e('Route', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="msg91_route" type="text" id="wlsm_msg91_route" value="<?php echo esc_attr($school_msg91_route); ?>" class="form-control" placeholder="<?php esc_attr_e('Msg91 Route', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_msg91">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_msg91_sender" class="wlsm-font-bold"><?php esc_html_e('Sender', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="msg91_sender" type="text" id="wlsm_msg91_sender" value="<?php echo esc_attr($school_msg91_sender); ?>" class="form-control" placeholder="<?php esc_attr_e('Msg91 Sender', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_msg91">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_msg91_country" class="wlsm-font-bold"><?php esc_html_e('Country Code', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="msg91_country" type="text" id="wlsm_msg91_country" value="<?php echo esc_attr($school_msg91_country); ?>" class="form-control" placeholder="<?php esc_attr_e('Msg91 Country Code', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_textlocal">
					<div class="row">
						<div class="col-md-3">
							<label class="wlsm-font-bold"><?php esc_html_e('SMS Package', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<a class="wlsm-font-bold" target="_blank" href="https://www.textlocal.com/pricing/">
									<?php esc_html_e('Click for SMS Package Features and Pricing', 'school-management'); ?>
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_textlocal">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_textlocal_api_key" class="wlsm-font-bold"><?php esc_html_e('API Key', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="textlocal_api_key" type="text" id="wlsm_textlocal_api_key" value="<?php echo esc_attr($school_textlocal_api_key); ?>" class="form-control" placeholder="<?php esc_attr_e('Textlocal API Key', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_textlocal">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_textlocal_sender" class="wlsm-font-bold"><?php esc_html_e('Sender', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="textlocal_sender" type="text" id="wlsm_textlocal_sender" value="<?php echo esc_attr($school_textlocal_sender); ?>" class="form-control" placeholder="<?php esc_attr_e('Textlocal Sender', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_tecxsms">
					<div class="row">
						<div class="col-md-3">
							<label class="wlsm-font-bold"><?php esc_html_e('SMS Package', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<a class="wlsm-font-bold" target="_blank" href="https://www.tecxsms.com/pricing/">
									<?php esc_html_e('Click for SMS Package Features and Pricing', 'school-management'); ?>
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_tecxsms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_tecxsms_api_key" class="wlsm-font-bold"><?php esc_html_e('API Key', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="tecxsms_api_key" type="text" id="wlsm_tecxsms_api_key" value="<?php echo esc_attr($school_tecxsms_api_key); ?>" class="form-control" placeholder="<?php esc_attr_e('Tecxsms API Key', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_tecxsms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_tecxsms_sender" class="wlsm-font-bold"><?php esc_html_e('Sender', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="tecxsms_sender" type="text" id="wlsm_tecxsms_sender" value="<?php echo esc_attr($school_tecxsms_sender); ?>" class="form-control" placeholder="<?php esc_attr_e('Tecxsms Sender', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_switchportlimited">
					<div class="row">
						<div class="col-md-3">
							<label class="wlsm-font-bold"><?php esc_html_e('SMS Package', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<a class="wlsm-font-bold" target="_blank" href="https://sms.switchportlimited.com/">
									<?php esc_html_e('Click for SMS Package Features and Pricing', 'school-management'); ?>
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_switchportlimited">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_switchportlimited_api_key" class="wlsm-font-bold"><?php esc_html_e('API Key', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="switchportlimited_api_key" type="text" id="wlsm_switchportlimited_api_key" value="<?php echo esc_attr($school_switchportlimited_api_key); ?>" class="form-control" placeholder="<?php esc_attr_e('switchportlimited API Key', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_switchportlimited">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_switchportlimited_sender" class="wlsm-font-bold"><?php esc_html_e('Sender', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="switchportlimited_sender" type="text" id="wlsm_switchportlimited_sender" value="<?php echo esc_attr($school_switchportlimited_sender); ?>" class="form-control" placeholder="<?php esc_attr_e('switchportlimited Sender', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_switchportlimited">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_switchportlimited_client_id" class="wlsm-font-bold"><?php esc_html_e('Client_id', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="switchportlimited_client_id" type="text" id="wlsm_switchportlimited_client_id" value="<?php echo esc_attr($school_switchportlimited_client_id); ?>" class="form-control" placeholder="<?php esc_attr_e('switchportlimited Client_id', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				

				<div class="wlsm_sms_carrier wlsm_bdbsms">
					<!-- <div class="row">
						<div class="col-md-3">
							<label class="wlsm-font-bold"><?php esc_html_e('SMS Package', 'school-management'); ?>:</label>
						</div>
					</div> -->
				</div>

				<div class="wlsm_sms_carrier wlsm_bdbsms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_bdbsms_api_key" class="wlsm-font-bold"><?php esc_html_e('API Token', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="bdbsms_api_key" type="text" id="wlsm_bdbsms_api_key" value="<?php echo esc_attr($school_bdbsms_api_key); ?>" class="form-control" placeholder="<?php esc_attr_e('bdbsms Token', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_kivalosolutions">
					<div class="row">
						<div class="col-md-3">
							<label class="wlsm-font-bold"><?php esc_html_e('SMS Package', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<a class="wlsm-font-bold" target="_blank" href="http://sms.kivalosolutions.com/">
									<?php esc_html_e('Click for SMS Package Features and Pricing', 'school-management'); ?>
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_kivalosolutions">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_kivalosolutions_api_key" class="wlsm-font-bold"><?php esc_html_e('API Key', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="kivalosolutions_api_key" type="text" id="wlsm_kivalosolutions_api_key" value="<?php echo esc_attr($school_kivalosolutions_api_key); ?>" class="form-control" placeholder="<?php esc_attr_e('kivalosolutions API Key', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_kivalosolutions">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_kivalosolutions_sender" class="wlsm-font-bold"><?php esc_html_e('Sender ID', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="kivalosolutions_sender" type="text" id="wlsm_kivalosolutions_sender" value="<?php echo esc_attr($school_kivalosolutions_sender); ?>" class="form-control" placeholder="<?php esc_attr_e('kivalosolutions Sender', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_ebulksms">
					<div class="row">
						<div class="col-md-3">
							<label class="wlsm-font-bold"><?php esc_html_e('SMS Package', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<a class="wlsm-font-bold" target="_blank" href="https://www.ebulksms.com/pricing">
									<?php esc_html_e('Click for SMS Package Features and Pricing', 'school-management'); ?>
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_ebulksms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_ebulksms_username" class="wlsm-font-bold"><?php esc_html_e('Username', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="ebulksms_username" type="text" id="wlsm_ebulksms_username" value="<?php echo esc_attr($school_ebulksms_username); ?>" class="form-control" placeholder="<?php esc_attr_e('EBulkSMS Username', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_ebulksms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_ebulksms_api_key" class="wlsm-font-bold"><?php esc_html_e('API Key', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="ebulksms_api_key" type="text" id="wlsm_ebulksms_api_key" value="<?php echo esc_attr($school_ebulksms_api_key); ?>" class="form-control" placeholder="<?php esc_attr_e('EBulkSMS API Key', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_ebulksms">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_ebulksms_sender" class="wlsm-font-bold"><?php esc_html_e('Sender', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="ebulksms_sender" type="text" id="wlsm_ebulksms_sender" value="<?php echo esc_attr($school_ebulksms_sender); ?>" class="form-control" placeholder="<?php esc_attr_e('EBulkSMS Sender', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_sendpk">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_sendpk_api_key" class="wlsm-font-bold"><?php esc_html_e('API Key', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="sendpk_api_key" type="text" id="wlsm_sendpk_api_key" value="<?php echo esc_attr($school_sendpk_api_key); ?>" class="form-control" placeholder="<?php esc_attr_e('sendpk API Key', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="wlsm_sms_carrier wlsm_sendpk">
					<div class="row">
						<div class="col-md-3">
							<label for="wlsm_sendpk_sender" class="wlsm-font-bold"><?php esc_html_e('Sender', 'school-management'); ?>:</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<input name="sendpk_sender" type="text" id="wlsm_sendpk_sender" value="<?php echo esc_attr($school_sendpk_sender); ?>" class="form-control" placeholder="<?php esc_attr_e('sendpk Sender', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn btn-primary" id="wlsm-save-school-sms-carrier-settings-btn">
							<i class="fas fa-save"></i>&nbsp;
							<?php esc_html_e('Save', 'school-management'); ?>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

</div>
