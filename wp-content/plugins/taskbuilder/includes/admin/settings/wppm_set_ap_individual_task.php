<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb,$wppmfunction;

if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

if ( check_ajax_referer( 'wppm_set_ap_individual_task', '_ajax_nonce', false ) != 1 ) {
    wp_send_json_error( 'Unauthorised request!', 401 );
}

$comment_primary_color = isset( $_POST['comment-primary-color'] ) ? sanitize_text_field( wp_unslash( $_POST['comment-primary-color'] ) ) : '';
if ( ! $comment_primary_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$comment_secondary_color = isset( $_POST['comment-secondary-color'] ) ? sanitize_text_field( wp_unslash( $_POST['comment-secondary-color'] ) ) : '';
if ( ! $comment_secondary_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$comment_date_color = isset( $_POST['comment-date-color'] ) ? sanitize_text_field( wp_unslash( $_POST['comment-date-color'] ) ) : '';
if ( ! $comment_date_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$comment_date_hover_color = isset( $_POST['comment-date-hover-color'] ) ? sanitize_text_field( wp_unslash( $_POST['comment-date-hover-color'] ) ) : '';
if ( ! $comment_date_hover_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$comment_send_btn_bg_color = isset( $_POST['comment-send-btn-bg-color']) ? sanitize_text_field( wp_unslash( $_POST['comment-send-btn-bg-color'] ) ) : '';
if ( ! $comment_send_btn_bg_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$comment_send_btn_color = isset( $_POST['comment-send-btn-color']) ? sanitize_text_field( wp_unslash( $_POST['comment-send-btn-color'] ) ) : '';
if ( ! $comment_send_btn_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$widget_header_bg_color = isset( $_POST['widget-header-bg-color'] ) ? sanitize_text_field( wp_unslash( $_POST['widget-header-bg-color'] ) ) : '';
if ( ! $widget_header_bg_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$widget_header_text_color = isset( $_POST['widget-header-text-color'] ) ? sanitize_text_field( wp_unslash( $_POST['widget-header-text-color'] ) ) : '';
if ( ! $widget_header_text_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$widget_body_bg_color = isset( $_POST['widget-body-bg-color'] ) ? sanitize_text_field( wp_unslash( $_POST['widget-body-bg-color'] ) ) : '';
if ( ! $widget_body_bg_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$widget_body_label_color = isset( $_POST['widget-body-label-color'] ) ? sanitize_text_field( wp_unslash( $_POST['widget-body-label-color'] ) ) : '';
if ( ! $widget_body_label_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$widget_body_text_color = isset( $_POST['widget-body-text-color'] ) ? sanitize_text_field( wp_unslash( $_POST['widget-body-text-color'] ) ) : '';
if ( ! $widget_body_text_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

update_option(
    'wppm-ap-individual-task',
    array(
        'comment-primary-color'     => $comment_primary_color,
        'comment-secondary-color'   => $comment_secondary_color,
        'comment-date-color'        => $comment_date_color,
        'comment-date-hover-color'  => $comment_date_hover_color,
        'comment-send-btn-bg-color' => $comment_send_btn_bg_color,
        'comment-send-btn-color'    => $comment_send_btn_color,
        'widget-header-bg-color'    => $widget_header_bg_color,
        'widget-header-text-color'  => $widget_header_text_color,
        'widget-body-bg-color'      => $widget_body_bg_color,
        'widget-body-label-color'   => $widget_body_label_color,
        'widget-body-text-color'    => $widget_body_text_color
    )
);
do_action('wppm_set_ap_individual_task_settings');

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','taskbuilder').'" }';