<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb,$wppmfunction;

if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

if ( check_ajax_referer( 'wppm_set_ap_task_list', '_ajax_nonce', false ) != 1 ) {
    wp_send_json_error( 'Unauthorised request!', 401 );
}

$list_header_button_background_color = isset( $_POST['list-header-button-background-color'] ) ? sanitize_text_field( wp_unslash( $_POST['list-header-button-background-color'] ) ) : '';
if ( ! $list_header_button_background_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$list_header_button_hover_color = isset( $_POST['list-header-button-hover-color'] ) ? sanitize_text_field( wp_unslash( $_POST['list-header-button-hover-color'] ) ) : '';
if ( ! $list_header_button_hover_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$list_header_button_text_color = isset( $_POST['list-header-button-text-color'] ) ? sanitize_text_field( wp_unslash( $_POST['list-header-button-text-color'] ) ) : '';
if ( ! $list_header_button_text_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$list_header_bg_color = isset( $_POST['list-header-background-color'] ) ? sanitize_text_field( wp_unslash( $_POST['list-header-background-color'] ) ) : '';
if ( ! $list_header_bg_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$list_header_text_color = isset( $_POST['list-header-text-color'] ) ? sanitize_text_field( wp_unslash( $_POST['list-header-text-color'] ) ) : '';
if ( ! $list_header_text_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$list_item_odd_bg_color = isset( $_POST['list-item-odd-background-color'] ) ? sanitize_text_field( wp_unslash( $_POST['list-item-odd-background-color'] ) ) : '';
if ( ! $list_item_odd_bg_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$list_item_odd_text_color = isset( $_POST['list-item-odd-text-color'] ) ? sanitize_text_field( wp_unslash( $_POST['list-item-odd-text-color'] ) ) : '';
if ( ! $list_item_odd_text_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$list_item_even_bg_color = isset( $_POST['list-item-even-background-color'] ) ? sanitize_text_field( wp_unslash( $_POST['list-item-even-background-color'] ) ) : '';
if ( ! $list_item_even_bg_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$list_item_even_text_color = isset( $_POST['list-item-even-text-color'] ) ? sanitize_text_field( wp_unslash( $_POST['list-item-even-text-color'] ) ) : '';
if ( ! $list_item_even_text_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$list_item_hover_bg_color = isset( $_POST['list-item-hover-background-color'] ) ? sanitize_text_field( wp_unslash( $_POST['list-item-hover-background-color'] ) ) : '';
if ( ! $list_item_hover_bg_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$list_item_hover_text_color = isset( $_POST['list-item-hover-text-color'] ) ? sanitize_text_field( wp_unslash( $_POST['list-item-hover-text-color'] ) ) : '';
if ( ! $list_item_hover_text_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

update_option(
    'wppm-ap-task-list',
    array(
        'list-header-button-background-color'=>$list_header_button_background_color,
        'list-header-button-hover-color'   =>$list_header_button_hover_color,
        'list-header-button-text-color'    =>$list_header_button_text_color,
        'list-header-background-color'     => $list_header_bg_color,
        'list-header-text-color'           => $list_header_text_color,
        'list-item-odd-background-color'   => $list_item_odd_bg_color,
        'list-item-odd-text-color'         => $list_item_odd_text_color,
        'list-item-even-background-color'  => $list_item_even_bg_color,
        'list-item-even-text-color'        => $list_item_even_text_color,
        'list-item-hover-background-color' => $list_item_hover_bg_color,
        'list-item-hover-text-color'       => $list_item_hover_text_color,
    )
);
do_action('wppm_set_ap_task_list_settings');

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','taskbuilder').'" }';