<?php
defined('ABSPATH') || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Config.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Session.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Helper.php';

$default_session_id     = get_option('wlsm_current_session');
$active_date_format     = WLSM_Config::date_format();
$gdpr_enable            = get_option('wlsm_gdpr_enable');
$google_link_show       = get_option('wlsm_google_link_show');
$library_menu           = get_option('wlsm_library_menu');
$hostel_menu            = get_option('wlsm_hostel_menu');
$lessons_menu           = get_option('wlsm_lessons_menu');
$transport_menu         = get_option('wlsm_transport_menu');
$examination_menu       = get_option('wlsm_examination_menu');
$gdpr_text_inquiry      = WLSM_Config::gdpr_text_inquiry();
$gdpr_text_registration = WLSM_Config::gdpr_text_registration();

$sessions         = WLSM_M_Session::fetch_sessions();
$date_formats     = WLSM_Helper::date_formats();

$delete_on_uninstall = get_option('wlsm_delete_on_uninstall');

add_filter('user_can_richedit', '__return_false', 50);
?>
<div class="wlsm">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="wlsm-main-header card col wlsm-page-heading-box">
					<h1 class="h3 text-center wlsm-page-heading">
						<i class="fas fa-cogs text-primary"></i>
						<?php esc_html_e('Settings', 'school-management'); ?>
					</h1>
				</div>
			</div>
		</div>

		<div class="row justify-content-md-center mt-3">
			<div class="col-md-12">
				<ul class="nav nav-pills mb-3 mt-1" id="wlsm-settings-tabs" role="tablist">
					<li class="nav-item">
						<a class="nav-link border border-primary active" id="wlsm-general-tab" data-toggle="tab" href="#wlsm-general" role="tab" aria-controls="wlsm-general" aria-selected="true">
							<?php esc_html_e('General', 'school-management'); ?>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a class="nav-link border border-primary" id="wlsm-shortcodes-tab" data-toggle="tab" href="#wlsm-shortcodes" role="tab" aria-controls="wlsm-shortcodes" aria-selected="true">
							<?php esc_html_e('Shortcodes', 'school-management'); ?>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a class="nav-link border border-primary" id="wlsm-reset-plugin-tab" data-toggle="tab" href="#wlsm-reset-plugin" role="tab" aria-controls="wlsm-reset-plugin" aria-selected="true">
							<?php esc_html_e('Reset Plugin', 'school-management'); ?>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a class="nav-link border border-primary" id="wlsm-uninstall-tab" data-toggle="tab" href="#wlsm-uninstall" role="tab" aria-controls="wlsm-uninstall" aria-selected="true">
							<?php esc_html_e('Uninstall', 'school-management'); ?>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a class="nav-link border border-primary" id="wlsm-changelog-tab" data-toggle="tab" href="#wlsm-changelog" role="tab" aria-controls="wlsm-changelog" aria-selected="true">
							<?php esc_html_e('Changelog', 'school-management'); ?>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="row">
			<div class="col-md-8">
				<div class="tab-content" id="wlsm-tabs">
					<div class="ml-1 tab-pane fade show active" id="wlsm-general" role="tabpanel" aria-labelledby="wlsm-general-tab">

						<form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="wlsm-save-general-settings-form">
							<?php
							$nonce_action = 'save-general-settings';
							$nonce        = wp_create_nonce($nonce_action);
							?>
							<input type="hidden" name="<?php echo esc_attr($nonce_action); ?>" value="<?php echo esc_attr($nonce); ?>">

							<input type="hidden" name="action" value="wlsm-save-general-settings">

							<div class="row">
								<div class="col-md-4">
									<label for="wlsm_active_session" class="wlsm-font-bold">
										<?php esc_html_e('Set Active Session', 'school-management'); ?>:
									</label>
								</div>
								<div class="col-md-8">
									<div class="form-group">
										<select name="active_session" id="wlsm_active_session" class="form-control">
											<option value=""></option>
											<?php foreach ($sessions as $session) { ?>
												<option <?php selected($session->ID, $default_session_id, true); ?> value="<?php echo esc_attr($session->ID); ?>"><?php echo esc_attr($session->label); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
									<label for="wlsm_date_format" class="wlsm-font-bold">
										<?php esc_html_e('Set Date Format', 'school-management'); ?>:
									</label>
								</div>
								<div class="col-md-8">
									<div class="form-group">
										<select name="date_format" id="wlsm_date_format" class="form-control">
											<?php foreach ($date_formats as $key => $date_format) { ?>
												<option <?php selected($key, $active_date_format, true); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($date_format); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
									<label for="wlsm_gdpr_enable" class="wlsm-font-bold">
										<?php esc_html_e('GDPR Compliance', 'school-management'); ?>:
									</label>
								</div>
								<div class="col-md-8">
									<div class="form-group">
										<input <?php checked($gdpr_enable, 1, true); ?> class="form-check-input mt-1" type="checkbox" name="gdpr_enable" id="wlsm_gdpr_enable" value="1">
										<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-secondary" for="wlsm_gdpr_enable">
											<?php esc_html_e('Enable GDPR Compliance for Forms', 'school-management'); ?>
										</label>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
									<label for="wlsm_google_link_show" class="wlsm-font-bold">
										<?php esc_html_e('Google Play link show', 'school-management'); ?>:
									</label>
								</div>
								<div class="col-md-8">
									<div class="form-group">
										<input <?php checked($google_link_show, 1, true); ?> class="form-check-input mt-1" type="checkbox" name="google_link_show" id="wlsm_google_link_show" value="1">
										<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-secondary" for="wlsm_google_link_show">
											<?php esc_html_e('Enable Google Play link show on dashboard', 'school-management'); ?>
										</label>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
									<label for="wlsm_library_menu" class="wlsm-font-bold">
										<?php esc_html_e('Library Menu show', 'school-management'); ?>:
									</label>
								</div>
								<div class="col-md-8">
									<div class="form-group">
										<input <?php checked($library_menu, 1, true); ?> class="form-check-input mt-1" type="checkbox" name="library_menu" id="wlsm_library_menu" value="1">
										<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-secondary" for="wlsm_library_menu">
											<?php esc_html_e('Disable library Menu show on dashboard', 'school-management'); ?>
										</label>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
									<label for="wlsm_examination_menu" class="wlsm-font-bold">
										<?php esc_html_e('Examination Menu show', 'school-management'); ?>:
									</label>
								</div>
								<div class="col-md-8">
									<div class="form-group">
										<input <?php checked($examination_menu, 1, true); ?> class="form-check-input mt-1" type="checkbox" name="examination_menu" id="wlsm_examination_menu" value="1">
										<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-secondary" for="wlsm_examination_menu">
											<?php esc_html_e('Disable examination Menu show on dashboard', 'school-management'); ?>
										</label>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
									<label for="wlsm_transport_menu" class="wlsm-font-bold">
										<?php esc_html_e('Transport Menu show', 'school-management'); ?>:
									</label>
								</div>
								<div class="col-md-8">
									<div class="form-group">
										<input <?php checked($transport_menu, 1, true); ?> class="form-check-input mt-1" type="checkbox" name="transport_menu" id="wlsm_transport_menu" value="1">
										<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-secondary" for="wlsm_transport_menu">
											<?php esc_html_e('Disable transport Menu show on dashboard', 'school-management'); ?>
										</label>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
									<label for="wlsm_hostel_menu" class="wlsm-font-bold">
										<?php esc_html_e('Hostel Menu show', 'school-management'); ?>:
									</label>
								</div>
								<div class="col-md-8">
									<div class="form-group">
										<input <?php checked($hostel_menu, 1, true); ?> class="form-check-input mt-1" type="checkbox" name="hostel_menu" id="wlsm_hostel_menu" value="1">
										<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-secondary" for="wlsm_hostel_menu">
											<?php esc_html_e('Disable hostel Menu show on dashboard', 'school-management'); ?>
										</label>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
									<label for="wlsm_lessons_menu" class="wlsm-font-bold">
										<?php esc_html_e('Lessons Menu show', 'school-management'); ?>:
									</label>
								</div>
								<div class="col-md-8">
									<div class="form-group">
										<input <?php checked($lessons_menu, 1, true); ?> class="form-check-input mt-1" type="checkbox" name="lessons_menu" id="wlsm_lessons_menu" value="1">
										<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-secondary" for="wlsm_lessons_menu">
											<?php esc_html_e('Disable lessons Menu show on dashboard', 'school-management'); ?>
										</label>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
									<label for="wlsm_gdpr_text_inquiry" class="wlsm-font-bold">
										<?php
										echo wp_kses(
											__('GDPR Compliance Text <br>for Inquiry Form', 'school-management'),
											array('br' => array())
										);
										?>:
									</label>
								</div>
								<div class="col-md-8">
									<div class="form-group">
										<?php
										$settings = array(
											'media_buttons' => false,
											'textarea_name' => 'gdpr_text_inquiry',
											'textarea_rows' => 5,
											'wpautop'       => true,
											'quicktags'     => array('buttons' => 'link')
										);
										wp_editor($gdpr_text_inquiry, 'wlsm_gdpr_text_inquiry', $settings);
										?>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
									<label for="wlsm_gdpr_text_registration" class="wlsm-font-bold">
										<?php
										echo wp_kses(
											__('GDPR Compliance Text <br>for Registration Form', 'school-management'),
											array('br' => array())
										);
										?>:
									</label>
								</div>
								<div class="col-md-8">
									<div class="form-group">
										<?php
										$settings = array(
											'media_buttons' => false,
											'textarea_name' => 'gdpr_text_registration',
											'textarea_rows' => 5,
											'wpautop'       => true,
											'quicktags'     => array('buttons' => 'link')
										);
										wp_editor($gdpr_text_registration, 'wlsm_gdpr_text_registration', $settings);
										?>
									</div>
								</div>
							</div>

							<div>
								<span class="float-md-right">
									<button type="submit" class="btn btn-primary" id="wlsm-save-general-settings-btn">
										<i class="fas fa-save"></i>&nbsp;
										<?php esc_html_e('Save', 'school-management'); ?>
									</button>
								</span>
							</div>
						</form>

					</div>
					<div class="tab-pane fade" id="wlsm-shortcodes" role="tabpanel" aria-labelledby="wlsm-shortcodes-tab">

						<li class="list-inline-item">
							<div class="alert alert-light">
								<?php esc_html_e('To display fees submission form on a page or post, use shortcode', 'school-management'); ?>:<br>
								<span id="wlsm_school_register_shortcode" class="wlsm-font-bold text-dark">[school_register]</span>
								<button id="wlsm_school_register_copy_btn" class="btn btn-outline-success btn-sm" type="button">
									<?php esc_html_e('Copy', 'school-management'); ?>
								</button>
							</div>
						</li>

						<ul class="list-group list-group-flush">
							<li class="list-inline-item">
								<div class="alert alert-light">
									<?php esc_html_e('To display fees submission form on a page or post, use shortcode', 'school-management'); ?>:<br>
									<span id="wlsm_school_management_fees_shortcode" class="wlsm-font-bold text-dark">[school_management_fees]</span>
									<button id="wlsm_school_management_fees_copy_btn" class="btn btn-outline-success btn-sm" type="button">
										<?php esc_html_e('Copy', 'school-management'); ?>
									</button>
								</div>
							</li>

							<li class="list-inline-item">
								<div class="alert alert-light">
									<?php esc_html_e('To display login form and student dashboard on a page or post, use shortcode', 'school-management'); ?>:<br>
									<span id="wlsm_school_management_account_shortcode" class="wlsm-font-bold text-dark">[school_management_account]</span>
									<button id="wlsm_school_management_account_copy_btn" class="btn btn-outline-success btn-sm" type="button">
										<?php esc_html_e('Copy', 'school-management'); ?>
									</button>
								</div>
							</li>

							<li class="list-inline-item">
								<div class="alert alert-light">
									<?php esc_html_e('To display admission inquiry form on a page or post, use shortcode', 'school-management'); ?>:<br>
									<span id="wlsm_school_management_inquiry_shortcode" class="wlsm-font-bold text-dark">[school_management_inquiry]</span>
									<button id="wlsm_school_management_inquiry_copy_btn" class="btn btn-outline-success btn-sm" type="button">
										<?php esc_html_e('Copy', 'school-management'); ?>
									</button>
								</div>
							</li>

							<li class="list-inline-item">
								<div class="alert alert-light">
									<?php esc_html_e('To display registration form on a page or post, use shortcode', 'school-management'); ?>:<br>
									<span id="wlsm_school_management_registration_shortcode" class="wlsm-font-bold text-dark">[school_management_registration]</span>
									<button id="wlsm_school_management_registration_copy_btn" class="btn btn-outline-success btn-sm" type="button">
										<?php esc_html_e('Copy', 'school-management'); ?>
									</button>
								</div>
							</li>

							<li class="list-inline-item">
								<div class="alert alert-light">
									<?php esc_html_e('To display staff registration form on a page or post, use shortcode', 'school-management'); ?>:<br>
									<span id="wlsm_school_management_staff_registration_shortcode" class="wlsm-font-bold text-dark">[school_management_staff_registration]</span>
									<button id="wlsm_school_management_staff_registration_copy_btn" class="btn btn-outline-success btn-sm" type="button">
										<?php esc_html_e('Copy', 'school-management'); ?>
									</button>
								</div>
							</li>

							<li class="list-inline-item">
								<div class="alert alert-light">
									<?php esc_html_e('To display exam time table form on a page or post, use shortcode', 'school-management'); ?>:<br>
									<span id="wlsm_school_management_exam_time_table_shortcode" class="wlsm-font-bold text-dark">[school_management_exam_time_table]</span>
									<button id="wlsm_school_management_exam_time_table_copy_btn" class="btn btn-outline-success btn-sm" type="button">
										<?php esc_html_e('Copy', 'school-management'); ?>
									</button>
								</div>
							</li>

							<li class="list-inline-item">
								<div class="alert alert-light">
									<?php esc_html_e('To display exam admit cards form on a page or post, use shortcode', 'school-management'); ?>:<br>
									<span id="wlsm_school_management_exam_admit_card_shortcode" class="wlsm-font-bold text-dark">[school_management_exam_admit_card]</span>
									<button id="wlsm_school_management_exam_admit_card_copy_btn" class="btn btn-outline-success btn-sm" type="button">
										<?php esc_html_e('Copy', 'school-management'); ?>
									</button>
								</div>
							</li>

							<li class="list-inline-item">
								<div class="alert alert-light">
									<?php esc_html_e('To display exam results form on a page or post, use shortcode', 'school-management'); ?>:<br>
									<span id="wlsm_school_management_exam_result_shortcode" class="wlsm-font-bold text-dark">[school_management_exam_result]</span>
									<button id="wlsm_school_management_exam_result_copy_btn" class="btn btn-outline-success btn-sm" type="button">
										<?php esc_html_e('Copy', 'school-management'); ?>
									</button>
								</div>
							</li>

							<li class="list-inline-item">
								<div class="alert alert-light">
									<?php esc_html_e('To display certificate form on a page or post, use shortcode', 'school-management'); ?>:<br>
									<span id="wlsm_school_management_certificate_shortcode" class="wlsm-font-bold text-dark">[school_management_certificate]</span>
									<button id="wlsm_school_management_certificate_copy_btn" class="btn btn-outline-success btn-sm" type="button">
										<?php esc_html_e('Copy', 'school-management'); ?>
									</button>
								</div>
							</li>

							<li class="list-inline-item">
								<div class="alert alert-light">
									<?php esc_html_e('To display invoice_history form on a page or post, use shortcode', 'school-management'); ?>:<br>
									<span id="wlsm_school_management_invoice_history_shortcode" class="wlsm-font-bold text-dark">[school_management_invoice_history]</span>
									<button id="wlsm_school_management_invoice_history_copy_btn" class="btn btn-outline-success btn-sm" type="button">
										<?php esc_html_e('Copy', 'school-management'); ?>
									</button>
								</div>
							</li>

							<li class="list-inline-item">
								<div class="alert alert-light">
									<?php esc_html_e('To display zoom form on a page or post, use shortcode', 'school-management'); ?>:<br>
									<span id="wlsm_zoom_redirect_shortcode" class="wlsm-font-bold text-dark">[school_management_zoom_redirect]</span>
									<button id="wlsm_zoom_redirect_copy_btn" class="btn btn-outline-success btn-sm" type="button">
										<?php esc_html_e('Copy', 'school-management'); ?>
									</button>
								</div>
							</li>
						</ul>

					</div>
					<div class="tab-pane fade" id="wlsm-reset-plugin" role="tabpanel" aria-labelledby="wlsm-reset-plugin-tab">

						<form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="wlsm-reset-plugin-form">
							<?php
							$nonce_action = 'reset-plugin';
							$nonce        = wp_create_nonce($nonce_action);
							?>
							<input type="hidden" name="<?php echo esc_attr($nonce_action); ?>" value="<?php echo esc_attr($nonce); ?>">

							<input type="hidden" name="action" value="wlsm-reset-plugin">

							<div class="mb-3 mt-1 alert alert-info">
								<?php esc_html_e('Here, you can reset the plugin to its initial state.', 'school-management'); ?>
							</div>

							<div class="ml-4 mb-2 mt-2 wlsm-font-bold">
								<?php esc_html_e('This will:', 'school-management'); ?>
							</div>

							<ul class="list-group list-group-flush text-dark">
								<li class="list-group-item">
									* <?php esc_html_e('Recreate all database tables.', 'school-management'); ?>
								</li>
								<li class="list-group-item">
									* <?php esc_html_e('Reset all settings.', 'school-management'); ?>
								</li>
							</ul>

							<div class="mt-3 text-right">
								<button type="button" class="btn btn-danger" id="wlsm-reset-plugin-btn" data-message-title="<?php esc_attr_e('Reset Plugin!', 'school-management'); ?>" data-message-content="<?php esc_attr_e('Are you sure to reset the plugin to its initial state?', 'school-management'); ?>" data-submit="<?php esc_attr_e('Reset', 'school-management'); ?>" data-cancel="<?php esc_attr_e('Cancel', 'school-management'); ?>">
									<i class="fas fa-redo"></i>&nbsp;
									<?php esc_html_e('Reset Plugin', 'school-management'); ?>
								</button>
							</div>
						</form>

					</div>
					<div class="ml-1 tab-pane fade" id="wlsm-uninstall" role="tabpanel" aria-labelledby="wlsm-uninstall-tab">

						<form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="wlsm-save-uninstall-settings-form">
							<?php
							$nonce_action = 'save-uninstall-settings';
							$nonce        = wp_create_nonce($nonce_action);
							?>
							<input type="hidden" name="<?php echo esc_attr($nonce_action); ?>" value="<?php echo esc_attr($nonce); ?>">

							<input type="hidden" name="action" value="wlsm-save-uninstall-settings">

							<div class="row">
								<div class="col-md-4">
									<label for="wlsm_delete_on_uninstall" class="wlsm-font-bold">
										<?php esc_html_e('Delete Data On Uninstall', 'school-management'); ?>:
									</label>
								</div>
								<div class="col-md-8">
									<div class="form-group">
										<input <?php checked($delete_on_uninstall, 1, true); ?> class="form-check-input mt-1" type="checkbox" name="delete_on_uninstall" id="wlsm_delete_on_uninstall" value="1">
										<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-secondary" for="wlsm_delete_on_uninstall">
											<?php esc_html_e('Delete database tables and settings when you delete the plugin?', 'school-management'); ?>
										</label>
									</div>
								</div>
							</div>

							<div>
								<span class="float-md-right">
									<button type="submit" class="btn btn-primary" id="wlsm-save-uninstall-settings-btn">
										<i class="fas fa-save"></i>&nbsp;
										<?php esc_html_e('Save', 'school-management'); ?>
									</button>
								</span>
							</div>
						</form>

					</div>
					<div class="tab-pane fade" id="wlsm-changelog" role="tabpanel" aria-labelledby="wlsm-changelog-tab">
						<h3> <?php esc_html_e('Changelog', 'school-management'); ?></h3>
						<hr>
						<?php
						function get_robots()
						{

							$robots_file = WLSM_PLUGIN_DIR_PATH . 'changelog.txt'; //The changelog file.

							if (file_exists($robots_file)) {
								return file_get_contents($robots_file);
							} else {
								$default_content = "User-agent: *\nDisallow:";
								file_put_contents($robots_file, $default_content);
								return $default_content;
							}
						}
						?>
						<h5>
							<pre>
						<?php echo get_robots(); ?>
						</pre>
						</h5>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>