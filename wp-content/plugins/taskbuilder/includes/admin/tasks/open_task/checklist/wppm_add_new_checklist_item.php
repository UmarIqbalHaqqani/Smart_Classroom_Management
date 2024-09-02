<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $current_user,$wpdb,$wppmfunction;
if ( check_ajax_referer( 'wppm_add_new_checklist_item', '_ajax_nonce', false ) != 1 ) {
	wp_send_json_error( 'Unauthorised request!', 401 );
}
$checklist_id = isset($_POST) && isset($_POST['checklist_id']) ? intval(sanitize_text_field($_POST['checklist_id'])) : '';
if (!$checklist_id) {exit;}
$members = '0';

$checklist_item_name = isset($_POST) && isset($_POST['label']) ? sanitize_text_field($_POST['label']) : '';
if (!$checklist_item_name) {exit;}

$values = array(
    'checklist_id'=>$checklist_id,
    'item_name'=>$checklist_item_name,
    'checked'=>0,
    'members'=>$members,
    'due_date'=>date('Y-m-d H:i:s')
);
$wpdb->insert($wpdb->prefix .'wppm_checklist_items', $values);