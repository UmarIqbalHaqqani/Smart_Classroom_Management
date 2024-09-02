<?php

/**
 * Uninstall LoginPress Pro.
 *
 * @package loginpress
 * @author WPBrigade
 * @since 3.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

/**
 * Run special routine on uninstall
 *
 * @since 3.0.0
 */
function loginpress_pro_uninstall() {
	if ( function_exists( 'is_multisite' ) && is_multisite() ) {
		global $wpdb;

		// Get this so we can switch back to it later.
		$current_blog = $wpdb->blogid;
		// Get all blogs in the network and delete table for each blog.
		$blog_ids = $wpdb->get_col( $wpdb->prepare( 'SELECT blog_id FROM %s', $wpdb->blogs ) );

		foreach ( $blog_ids as $blog_id ) {
			switch_to_blog( $blog_id );
			$lsl_settings           = get_option( 'loginpress_social_logins' );
			$llla_settings          = get_option( 'loginpress_limit_login_attempts' );
			$lsl_delete_user_table  = isset( $lsl_settings['delete_user_data'] ) ? $lsl_settings['delete_user_data'] : '';
			$llla_delete_user_table = isset( $llla_settings['delete_data'] ) ? $llla_settings['delete_data'] : '';

			loginpress_delete_user_meta( 'general' );

			if ( 'on' === $lsl_delete_user_table ) {
				drop_loginpress_pro_social_login_details_table();  // User table for blog ID.
			}

			if ( 'on' === $llla_delete_user_table ) {
				drop_loginpress_pro_limit_login_attempts_details_table(); // delete table for blog id.
			}
		}
		switch_to_blog( $current_blog );
		return;

	} else {
		$lsl_settings           = get_option( 'loginpress_social_logins' );
		$llla_settings          = get_option( 'loginpress_limit_login_attempts' );
		$lsl_delete_user_table  = isset( $lsl_settings['delete_user_data'] ) ? $lsl_settings['delete_user_data'] : '';
		$llla_delete_user_table = isset( $llla_settings['delete_data'] ) ? $llla_settings['delete_data'] : '';

		if ( 'on' === $lsl_delete_user_table ) {
			drop_loginpress_pro_social_login_details_table(); // normal deactivaton  delete table.
			loginpress_delete_user_meta( 'social-login' );
		}

		if ( 'on' === $llla_delete_user_table ) {
			drop_loginpress_pro_limit_login_attempts_details_table(); // normal deactivaton delete table.
		}

		loginpress_delete_user_meta( 'general' );
	}
}

loginpress_pro_uninstall();

/**
 * Delete Social Login table.
 *
 * @since 3.0.0
 */
function drop_loginpress_pro_social_login_details_table() {
	delete_option( 'loginpress_social_logins' );

	global $wpdb;
	// table name.
	$table_name = "{$wpdb->prefix}loginpress_social_login_details";
	// drop table if exist.
	$wpdb->query( $wpdb->prepare( 'DROP TABLE IF EXISTS %s', $table_name ) );
}

/**
 * Delete Limit Login table entries from Database
 *
 * @since 3.0.0
 */
function drop_loginpress_pro_limit_login_attempts_details_table() {
	delete_option( 'loginpress_limit_login_attempts' );

	global $wpdb;
	// table name.
	$table_name = "{$wpdb->prefix}loginpress_limit_login_details";
	// drop table if exist.
	$wpdb->query( $wpdb->prepare( 'DROP TABLE IF EXISTS %s', $table_name ) );
}

/**
 * Alter the option back to default
 *
 * @version 2.6.0
 */
function loginpress_alter_options() {
	delete_option( 'loginpress_hidelogin' );
	delete_option( 'loginpress_redirects_role' );
	delete_option( 'loginpress_pc' );

	$loginpress_setting = get_option( 'loginpress_setting' );

	unset( $loginpress_setting['login_order'] );
	unset( $loginpress_setting['force_login'] );
	unset( $loginpress_setting['enable_user_verification'] );
	unset( $loginpress_setting['enable_repatcha'] );
	unset( $loginpress_setting['captcha_enable'] );
	unset( $loginpress_setting['captcha_language'] );
	unset( $loginpress_setting['secret_key'] );
	unset( $loginpress_setting['recaptcha_type'] );
	unset( $loginpress_setting['good_score'] );
	unset( $loginpress_setting['recaptcha_error_message'] );

	update_option( 'loginpress_setting', $loginpress_setting );
}

/**
 * Delete Meta Data of the LoginPress.
 *
 * @param string $addon addon slug.
 */
function loginpress_delete_user_meta( $addon ) {

	$users = get_users();

	$social_meta = array(
		'email',
		'first_name',
		'last_name',
		'deuid',
		'deutype',
		'deuimage',
		'description',
		'sex',
	);

	$redirect_meta = array(
		'loginpress_login_redirects_url',
		'loginpress_logout_redirects_url',
	);

	$meta_key = array(
		'loginpress_autologin_user',
		'loginpress_user_verification',
	);

	$general_meta = array_merge( $redirect_meta, $meta_key );

	switch ( $addon ) {
		case 'social-login':
			foreach ( $users as $user ) {
				foreach ( $social_meta as $meta ) {
					delete_user_meta( $user->ID, $meta );
				}
			}
			break;

		case 'general':
			foreach ( $users as $user ) {
				foreach ( $general_meta as $meta ) {
					delete_user_meta( $user->ID, $meta );
				}
			}
			break;
	}
}

