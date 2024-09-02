<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
if ( check_ajax_referer( 'wppm_set_change_proj_start_date', '_ajax_nonce', false ) != 1 ) {
  wp_send_json_error( 'Unauthorised request!', 401 );
}

$proj_id  = isset($_POST['proj_id']) ? intval(sanitize_text_field($_POST['proj_id'])) : '' ;
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_project_permission('change_project_details',$proj_id))) {exit;}
$date =  isset($_POST['date']) ? (sanitize_text_field($_POST['date'])) : '' ;
$sdate = strtotime($date); 
$start_date = date('Y-m-d H:i:s', $sdate); 
$proj_data = $wppmfunction->get_project($proj_id);
if($start_date && $start_date != $proj_data['start_date'] ){
  $wppmfunction->change_project_start_date( $proj_id, $start_date);
}