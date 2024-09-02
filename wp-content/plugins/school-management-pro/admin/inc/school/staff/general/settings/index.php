<?php
defined('ABSPATH') || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Setting.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Email.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_SMS.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Session.php';

$school_id = $current_school['id'];

$session_id    = $current_session['ID'];
$session_label = $current_session['label'];

$default_session_id    = $current_session['default_session_id'];
$default_session_label = WLSM_M_Session::get_session_label($default_session_id);

// Currency.
$currency = WLSM_Config::currency($school_id);
?>
<div class="wlsm">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card col wlsm-page-heading-box">
					<h1 class="h3 text-center wlsm-page-heading">
						<i class="fas fa-cogs text-primary"></i>
						<?php esc_html_e('Settings', 'school-management'); ?>
					</h1>
				</div>
			</div>
		</div>

		<div class="row justify-content-md-center mt-3">
			<div class="col-md-12">
				<ul class="nav nav-pills mb-3 mt-1" id="wlsm-school-settings-tabs" role="tablist">
					<li class="nav-item">
						<a class="nav-link border border-primary active" id="wlsm-school-general-tab" data-toggle="tab" href="#wlsm-school-general" role="tab" aria-controls="wlsm-school-general" aria-selected="true">
							<?php esc_html_e('General', 'school-management'); ?>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a class="nav-link border border-primary" id="wlsm-school-email-carrier-tab" data-toggle="tab" href="#wlsm-school-email-carrier" role="tab" aria-controls="wlsm-school-email-carrier" aria-selected="true">
							<?php esc_html_e('Email Carrier', 'school-management'); ?>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a class="nav-link border border-primary" id="wlsm-school-email-templates-tab" data-toggle="tab" href="#wlsm-school-email-templates" role="tab" aria-controls="wlsm-school-email-templates" aria-selected="true">
							<?php esc_html_e('Email Templates', 'school-management'); ?>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a class="nav-link border border-primary" id="wlsm-school-sms-carrier-tab" data-toggle="tab" href="#wlsm-school-sms-carrier" role="tab" aria-controls="wlsm-school-sms-carrier" aria-selected="true">
							<?php esc_html_e('SMS Carrier', 'school-management'); ?>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a class="nav-link border border-primary" id="wlsm-school-sms-templates-tab" data-toggle="tab" href="#wlsm-school-sms-templates" role="tab" aria-controls="wlsm-school-sms-templates" aria-selected="true">
							<?php esc_html_e('SMS Templates', 'school-management'); ?>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a class="nav-link border border-primary" id="wlsm-school-payment-method-tab" data-toggle="tab" href="#wlsm-school-payment-method" role="tab" aria-controls="wlsm-school-payment-method" aria-selected="true">
							<?php esc_html_e('Payment Methods', 'school-management'); ?>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a class="nav-link border border-primary" id="wlsm-school-inquiry-tab" data-toggle="tab" href="#wlsm-school-inquiry" role="tab" aria-controls="wlsm-school-inquiry" aria-selected="true">
							<?php esc_html_e('Inquiry', 'school-management'); ?>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a class="nav-link border border-primary" id="wlsm-school-registration-tab" data-toggle="tab" href="#wlsm-school-registration" role="tab" aria-controls="wlsm-school-registration" aria-selected="true">
							<?php esc_html_e('Registration', 'school-management'); ?>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a class="nav-link border border-primary" id="wlsm-school-dashboard-tab" data-toggle="tab" href="#wlsm-school-dashboard" role="tab" aria-controls="wlsm-school-dashboard" aria-selected="true">
							<?php esc_html_e('Dashboard', 'school-management'); ?>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a class="nav-link border border-primary" id="wlsm-school-shortcodes-tab" data-toggle="tab" href="#wlsm-school-shortcodes" role="tab" aria-controls="wlsm-school-shortcodes" aria-selected="true">
							<?php esc_html_e('Shortcodes', 'school-management'); ?>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a class="nav-link border border-primary" id="wlsm-school-charts-tab" data-toggle="tab" href="#wlsm-school-charts" role="tab" aria-controls="wlsm-school-charts" aria-selected="true">
							<?php esc_html_e('Charts', 'school-management'); ?>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a class="nav-link border border-primary" id="wlsm-school-zoom-tab" data-toggle="tab" href="#wlsm-school-zoom" role="tab" aria-controls="wlsm-school-zoom" aria-selected="true">
							<?php esc_html_e('Live Classes', 'school-management'); ?>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a class="nav-link border border-primary" id="wlsm-school-url-tab" data-toggle="tab" href="#wlsm-school-url" role="tab" aria-controls="wlsm-school-url" aria-selected="true">
							<?php esc_html_e('QR Code URLs', 'school-management'); ?>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a class="nav-link border border-primary" id="wlsm-school-card-backgrounds-tab" data-toggle="tab" href="#wlsm-school-card-backgrounds" role="tab" aria-controls="wlsm-school-card-backgrounds" aria-selected="true">
							<?php esc_html_e('Cards Backgrounds', 'school-management'); ?>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a class="nav-link border border-primary" id="wlsm-school-logs-tab" data-toggle="tab" href="#wlsm-school-logs" role="tab" aria-controls="wlsm-school-logs" aria-selected="true">
							<?php esc_html_e('Logging', 'school-management'); ?>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a class="nav-link border border-primary" id="wlsm-school-lessons-tab" data-toggle="tab" href="#wlsm-school-lessons" role="tab" aria-controls="wlsm-school-lessons" aria-selected="true">
							<?php esc_html_e('Lessons', 'school-management'); ?>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="tab-content wlsm-school-settings" id="wlsm-tabs">
			<?php
			require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/general/index.php';
			require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/email-carrier/index.php';
			require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/email-templates/index.php';
			require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/sms-carrier/index.php';
			require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/sms-templates/index.php';
			require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/payment-methods/index.php';
			require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/inquiry/index.php';
			require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/registration/index.php';
			require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/dashboard/index.php';
			require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/shortcodes/index.php';
			require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/charts/index.php';
			require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/zoom/index.php';
			require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/logging/index.php';
			require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/qcode/index.php';
			require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/lessons/index.php';
			require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/backgrounds/index.php';
			?>
		</div>

	</div>
</div>