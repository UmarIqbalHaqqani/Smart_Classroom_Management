<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user,$wpdb;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}
if ( check_ajax_referer( 'wppm_delete_task_status', '_ajax_nonce', false ) != 1 ) {
    wp_send_json_error( 'Unauthorised request!', 401 );
}

$status_id = isset($_POST) && isset($_POST['status_id']) ? intval(sanitize_text_field($_POST['status_id'])) : 0;
if (!$status_id) {exit;}

$wpdb->delete($wpdb->prefix.'wppm_task_statuses', array( 'id' => $status_id));
