<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb,$wppmfunction;

if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

if ( check_ajax_referer( 'wppm_set_ap_modal_popup', '_ajax_nonce', false ) != 1 ) {
    wp_send_json_error( 'Unauthorised request!', 401 );
}

$header_bg_color = isset( $_POST['header-bg-color'] ) ? sanitize_text_field( wp_unslash( $_POST['header-bg-color'] ) ) : '';
if ( ! $header_bg_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$header_text_color = isset( $_POST['header-text-color'] ) ? sanitize_text_field( wp_unslash( $_POST['header-text-color'] ) ) : '';
if ( ! $header_text_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$body_bg_color = isset( $_POST['body-bg-color'] ) ? sanitize_text_field( wp_unslash( $_POST['body-bg-color'] ) ) : '';
if ( ! $body_bg_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$body_label_color = isset( $_POST['body-label-color'] ) ? sanitize_text_field( wp_unslash( $_POST['body-label-color'] ) ) : '';
if ( ! $body_label_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$body_text_color = isset( $_POST['body-text-color'] ) ? sanitize_text_field( wp_unslash( $_POST['body-text-color'] ) ) : '';
if ( ! $body_text_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$footer_bg_color = isset( $_POST['footer-bg-color'] ) ? sanitize_text_field( wp_unslash( $_POST['footer-bg-color'] ) ) : '';
if ( ! $footer_bg_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$action_btn_bg_color = isset( $_POST['action-btn-bg-color'] ) ? sanitize_text_field( wp_unslash( $_POST['action-btn-bg-color'] ) ) : '';
if ( ! $action_btn_bg_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$action_btn_text_color = isset( $_POST['action-btn-text-color'] ) ? sanitize_text_field( wp_unslash( $_POST['action-btn-text-color'] ) ) : '';
if ( ! $action_btn_bg_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

update_option(
    'wppm-ap-modal',
    array(
        'header-bg-color'   => $header_bg_color,
        'header-text-color' => $header_text_color ,
        'body-bg-color'     => $body_bg_color ,
        'body-label-color'  => $body_label_color,
        'body-text-color'   => $body_text_color,
        'footer-bg-color'   => $footer_bg_color,
        'action-btn-bg-color'=>$action_btn_bg_color,
        'action-btn-text-color'=>$action_btn_text_color
    )
);
do_action('wppm_set_ap_modal_setting');

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','taskbuilder').'" }';