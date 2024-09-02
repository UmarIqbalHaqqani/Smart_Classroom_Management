<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/global.php';

$action = '';
if ( isset( $_GET['action'] ) && ! empty( $_GET['action'] ) ) {
	$action = sanitize_text_field( $_GET['action'] );
}

$report_id = '';
if ( isset( $_GET['report_id'] ) && ! empty( $_GET['report_id'] ) ) {
	$report_id = absint( $_GET['report_id'] );
}
?>
<div class="wlsm container-fluid">
	<?php
	$disallow_session_change = true;

	require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/partials/header.php';

	if ( 'save' === $action ) {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/examination/academic_report/save.php';
	} elseif ('reports' === $action) {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/examination/academic_report/reports.php';
	} else {
		require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/examination/academic_report/index.php';
	}
	?>
</div>
