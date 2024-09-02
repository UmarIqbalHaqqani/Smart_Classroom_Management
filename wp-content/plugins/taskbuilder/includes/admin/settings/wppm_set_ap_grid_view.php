<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb,$wppmfunction;

if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

if ( check_ajax_referer( 'wppm_set_ap_grid_view', '_ajax_nonce', false ) != 1 ) {
    wp_send_json_error( 'Unauthorised request!', 401 );
}

$grid_background_color = isset( $_POST['grid-background-color'] ) ? sanitize_text_field( wp_unslash( $_POST['grid-background-color'] ) ) : '';
if ( ! $grid_background_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$grid_header_text_color = isset( $_POST['grid-header-text-color'] ) ? sanitize_text_field( wp_unslash( $_POST['grid-header-text-color'] ) ) : '';
if ( ! $grid_header_text_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$menu_button_bg_color = isset( $_POST['menu-button-bg-color'] ) ? sanitize_text_field( wp_unslash( $_POST['menu-button-bg-color'] ) ) : '';
if ( ! $menu_button_bg_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$menu_button_hover_color = isset( $_POST['menu-button-hover-color'] ) ? sanitize_text_field( wp_unslash( $_POST['menu-button-hover-color'] ) ) : '';
if ( ! $menu_button_hover_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

$menu_button_text_color = isset( $_POST['menu-button-text-color'] ) ? sanitize_text_field( wp_unslash( $_POST['menu-button-text-color'] ) ) : '';
if ( ! $menu_button_text_color ) {
    wp_send_json_error( 'Bad request', 400 );
}

update_option(
    'wppm-ap-grid-view',
    array(
        'grid-background-color'  => $grid_background_color,
        'grid-header-text-color' => $grid_header_text_color ,
        'menu-button-bg-color'   => $menu_button_bg_color,
        'menu-button-hover-color'=> $menu_button_hover_color,
        'menu-button-text-color' => $menu_button_text_color
    )
);
do_action('wppm_set_ap_grid_view_setting');

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','taskbuilder').'" }';