<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
if ( check_ajax_referer( 'wppm_set_change_task_start_date', '_ajax_nonce', false ) != 1 ) {
  wp_send_json_error( 'Unauthorised request!', 401 );
}
$task_id  = isset($_POST['task_id']) ? intval(sanitize_text_field($_POST['task_id'])) : '' ;
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_permission('change_task_details',$task_id))) {exit;}
$date =  isset($_POST['date']) ? (sanitize_text_field($_POST['date'])) : '' ;
$sdate = strtotime($date); 
$start_date = date('Y-m-d H:i:s', $sdate); 
$task_data = $wppmfunction->get_task($task_id);
if($start_date && $start_date != $task_data['start_date'] ){
  $wppmfunction->change_start_date( $task_id, $start_date);
}