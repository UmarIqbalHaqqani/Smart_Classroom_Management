<?php
defined( 'ABSPATH' ) || die();

$action = '';
if ( isset( $_GET['action'] ) && ! empty( $_GET['action'] ) ) {
	$action = sanitize_text_field( $_GET['action'] );
}

if ( 'save' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/schools/save.php';
} else if ( 'classes' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/schools/classes.php';
} else if ( 'admins' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/schools/admins.php';
} else if ( 'edit_admin' === $action ) {
	require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/schools/edit_admin.php';
} else {
	require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/schools/index.php';
}
