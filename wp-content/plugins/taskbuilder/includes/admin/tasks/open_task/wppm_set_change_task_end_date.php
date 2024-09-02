<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
if ( check_ajax_referer( 'wppm_set_change_task_end_date', '_ajax_nonce', false ) != 1 ) {
  wp_send_json_error( 'Unauthorised request!', 401 );
}
$task_id  = isset($_POST['task_id']) ? intval(sanitize_text_field($_POST['task_id'])) : '' ;
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_permission('change_task_details',$task_id))) {exit;}
$date =  isset($_POST['date']) ? (sanitize_text_field($_POST['date'])) : '' ;
$edate = strtotime($date); 
$end_date = date('Y-m-d H:i:s', $edate); 
$task_data = $wppmfunction->get_task($task_id);
if($end_date && $end_date != $task_data['end_date'] ){
  $wppmfunction->change_end_date( $task_id, $end_date);
}