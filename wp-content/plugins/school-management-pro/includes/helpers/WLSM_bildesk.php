<?php
if ( file_exists( WLSM_PLUGIN_DIR_PATH . '/includes/bildesk/vendor/autoload.php' ) ) {
	include WLSM_PLUGIN_DIR_PATH . '/includes/bildesk/vendor/autoload.php';
}

if ( $school_env == 'UAT' ) {
	$client = new io\billdesk\client\hmacsha256\BillDeskJWEHS256Client( 'https://pguat.billdesk.io', $school_client_id, $school_merchant_key );
} else {
	$client = new io\billdesk\client\hmacsha256\BillDeskJWEHS256Client( 'https://api.billdesk.com', $school_client_id, $school_merchant_key );
}
