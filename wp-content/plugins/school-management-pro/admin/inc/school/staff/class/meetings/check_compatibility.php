<?php
defined( 'ABSPATH' ) || die();

WLSM_Helper::is_php_incompatible_for_meetings();

// Zoom settings for Staff user
$staff_role_permissions = array();
$user_id                = get_current_user_id();
if ( $user_id ) {
	$settings_zoom_api_key    = get_user_meta( $user_id, 'api_key', true );
	$settings_zoom_api_secret = get_user_meta( $user_id, 'api_secret', true );
}

if ( ! empty( $settings_zoom_api_key && $settings_zoom_api_secret ) ) {
	$zoom_error = false;
}

if ( WLSM_Helper::is_php_incompatible_for_meetings() ) {
	$zoom_error = true;
	?>
	
<div class="notice notice-error">
	<p><?php esc_html_e( 'This feature requires PHP version 7.1 or greater.', 'school-management' ); ?></p>
</div>
	<?php } if ( ! $settings_zoom_api_key || ! $settings_zoom_api_secret ) {
				$zoom_error = true;
	?>
<div class="notice notice-error">
	<p><?php esc_html_e( 'Please configure your Zoom API keys in " Profile > Zoom API Settings".', 'school-management' ); ?></p>
	<a href="<?php esc_attr_e( get_edit_profile_url() ); ?>">
	<?php esc_html_e( 'Link to your profile' ); ?>
	</a>
</div>
	<?php } if ( $zoom_error ) {
				die;
			 }

