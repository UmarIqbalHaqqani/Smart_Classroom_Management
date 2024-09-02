<?php
defined( 'ABSPATH' ) || die();

$action = '';
if ( isset( $_GET['action'] ) && ! empty( $_GET['action'] ) ) {
	$action = sanitize_text_field( $_GET['action'] );
}

require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/dashboard/index.php';
