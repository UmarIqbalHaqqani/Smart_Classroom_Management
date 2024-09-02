<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb,$wppmfunction;

if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}
if ( check_ajax_referer( 'wppm_reset_ap_settings', '_ajax_nonce', false ) != 1 ) {
  wp_send_json_error( 'Unauthorised request!', 401 );
}
update_option(
    'wppm-ap-settings',
    array(
        'tab-background-color'     => '#0052CC',
        'tab-text-color'    => '#fff',
        'add-new-button-bg-color'	=>'#0052CC',
        'add-new-button-text-color' =>'#fff',
        'add-new-button-hover-color'=>'#0065ff',
        'save-changes-button-bg-color'=>'#306EFF',
        'save-changes-button-text-color'=>'#fff'
    )
);
wp_die();
?>