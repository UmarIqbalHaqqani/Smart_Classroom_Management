<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb,$wppmfunction;

if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}
if ( check_ajax_referer( 'wppm_reset_ap_modal_popup', '_ajax_nonce', false ) != 1 ) {
  wp_send_json_error( 'Unauthorised request!', 401 );
}
update_option(
    'wppm-ap-modal',
    array(
        'header-bg-color'   => '#ffffff',
        'header-text-color' => '#3c434a',
        'body-bg-color'     => '#fff',
        'body-label-color'  => '#3c434a',
        'body-text-color'   => '#555',
        'footer-bg-color'   => '#F6F6F6',
        'action-btn-bg-color' =>'#306EFF',
				'action-btn-text-color'=>'#fff',
    )
);
wp_die();
?>