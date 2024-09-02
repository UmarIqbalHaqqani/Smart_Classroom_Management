<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb,$wppmfunction;

if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}
if ( check_ajax_referer( 'wppm_reset_ap_individual_task', '_ajax_nonce', false ) != 1 ) {
	wp_send_json_error( 'Unauthorised request!', 401 );
}
update_option(
  'wppm-ap-individual-task',
  array(
    'comment-primary-color'      => '#000000',
    'comment-secondary-color'    => '#4e4e4e',
    'comment-date-color'         => '#a8aeb5',
    'comment-date-hover-color'   => '#000000',
    'comment-send-btn-bg-color'  => '#5067c5',
    'comment-send-btn-color'  =>	'#ffffff',
    'widget-header-bg-color'   => '#ffffff',
    'widget-header-text-color' => '#2C3E50',
    'widget-body-bg-color'     => '#ffffff',
    'widget-body-label-color'  => '#9c9c9c',
    'widget-body-text-color'   => '#2C3E50',
  )
);
wp_die();
?>