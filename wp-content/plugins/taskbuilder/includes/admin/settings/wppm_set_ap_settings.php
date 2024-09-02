<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb,$wppmfunction;

if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

if ( check_ajax_referer( 'wppm_set_ap_settings', '_ajax_nonce', false ) != 1 ) {
    wp_send_json_error( 'Unauthorised request!', 401 );
}

$tab_background_color = isset( $_POST['tab-background-color'] ) ? sanitize_text_field( wp_unslash( $_POST['tab-background-color'] ) ) : '';
if ( ! $tab_background_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$tab_text_color = isset( $_POST['tab-text-color'] ) ? sanitize_text_field( wp_unslash( $_POST['tab-text-color'] ) ) : '';
if ( ! $tab_text_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$add_new_button_bg_color = isset( $_POST['add-new-button-bg-color'] ) ? sanitize_text_field( wp_unslash( $_POST['add-new-button-bg-color'] ) ) : '';
if ( ! $add_new_button_bg_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$add_new_button_text_color = isset( $_POST['add-new-button-text-color'] ) ? sanitize_text_field( wp_unslash( $_POST['add-new-button-text-color'] ) ) : '';
if ( ! $add_new_button_text_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$add_new_button_hover_color = isset( $_POST['add-new-button-hover-color'] ) ? sanitize_text_field( wp_unslash( $_POST['add-new-button-hover-color'] ) ) : '';
if ( ! $add_new_button_hover_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$save_changes_button_bg_color = isset( $_POST['save-changes-button-bg-color'] ) ? sanitize_text_field( wp_unslash( $_POST['save-changes-button-bg-color'] ) ) : '';
if ( ! $save_changes_button_bg_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$save_changes_button_text_color = isset( $_POST['save-changes-button-text-color'] ) ? sanitize_text_field( wp_unslash( $_POST['save-changes-button-text-color'] ) ) : '';
if ( ! $save_changes_button_text_color ) {
    wp_send_json_error( 'Bad request', 400 );
}
update_option(
    'wppm-ap-settings',
    array(
        'tab-background-color'  => $tab_background_color,
        'tab-text-color' => $tab_text_color ,
        'add-new-button-bg-color'=>$add_new_button_bg_color,
        'add-new-button-text-color'=>$add_new_button_text_color,
        'add-new-button-hover-color'=>$add_new_button_hover_color,
        'save-changes-button-bg-color'=>$save_changes_button_bg_color,
        'save-changes-button-text-color'=>$save_changes_button_text_color
    )
);
do_action('wppm_set_ap_settings');

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','taskbuilder').'" }';