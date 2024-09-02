<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb,$wppmfunction;

if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

if ( check_ajax_referer( 'wppm_reset_ap_grid_view', 'wppm_reset_grid_ajax_nonce', false ) != 1 ) {
  wp_send_json_error( 'Unauthorised request!', 401 );
}

update_option(
  'wppm-ap-grid-view',
  array(
    'menu-button-bg-color'		=>'#0052CC',
    'menu-button-hover-color'	=>'#0065ff',
    'menu-button-text-color'	=>'#fff',
    'grid-background-color'     => '#fff',
    'grid-header-text-color'    => '#2C3E50'
  )
);
wp_die();
?>