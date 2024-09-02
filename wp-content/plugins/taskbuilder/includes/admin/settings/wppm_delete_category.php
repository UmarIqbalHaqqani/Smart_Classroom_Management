<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user,$wpdb;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}
if ( check_ajax_referer( 'wppm_delete_category', '_ajax_nonce', false ) != 1 ) {
    wp_send_json_error( 'Unauthorised request!', 401 );
}
$cat_id = isset($_POST) && isset($_POST['cat_id']) ? intval(sanitize_text_field($_POST['cat_id'])) : 0;
if (!$cat_id) {exit;}

$wpdb->delete($wpdb->prefix.'wppm_project_categories', array( 'id' => $cat_id));


