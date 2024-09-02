<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $current_user,$wpdb;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

if ( check_ajax_referer( 'wppm_set_edit_category', '_ajax_nonce', false ) != 1 ) {
	wp_send_json_error( 'Unauthorised request!', 401 );
}

$cat_id = isset($_POST) && isset($_POST['cat_id']) ? intval(sanitize_text_field($_POST['cat_id'])) : 0;
if (!$cat_id) {exit;}

$cat_name = isset($_POST) && isset($_POST['cat_name']) ? sanitize_text_field($_POST['cat_name']) : '';
if (!$cat_name) {exit;}

$values= array(
  'name'=>$cat_name
);
$wpdb->update($wpdb->prefix.'wppm_project_categories',$values,array('id'=>($cat_id))); 
echo '{ "sucess_status":"1","messege":"Success" }';
?>