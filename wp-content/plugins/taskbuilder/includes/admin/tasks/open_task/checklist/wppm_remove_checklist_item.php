<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $current_user,$wpdb,$wppmfunction;
if ( check_ajax_referer( 'wppm_remove_checklist_item', '_ajax_nonce', false ) != 1 ) {
	wp_send_json_error( 'Unauthorised request!', 401 );
}
$checklist_id = isset($_POST) && isset($_POST['checklist_id']) ? intval(sanitize_text_field($_POST['checklist_id'])) : '';
$item_id = isset($_POST) && isset($_POST['item_id']) ? intval(sanitize_text_field($_POST['item_id'])) : '';
if (!$item_id) {exit;}

$wpdb->delete($wpdb->prefix.'wppm_checklist_items', array( 'id' => $item_id));