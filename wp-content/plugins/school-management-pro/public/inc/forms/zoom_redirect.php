<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Class.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/vendor/autoload.php';
if ( isset( $attr['session_id'] ) ) {
	$session_id = absint( $attr['session_id'] );
} else {
	$session_id = get_option( 'wlsm_current_session' );
}

$user_id  = get_current_user_id();
$staff_id = WLSM_M_Staff_General::get_staff_id_from_user( $user_id );
$staff_id = $staff_id->user_id;
/** If user id exists */
if ( $staff_id ) {
	$settings_zoom_api_key    = get_user_meta( $staff_id, 'api_key', true );
	$settings_zoom_api_secret = get_user_meta( $staff_id, 'api_secret', true );
	$settings_zoom_api_url    = get_user_meta( $staff_id, 'redirect_url', true );
}

$client = new GuzzleHttp\Client( array( 'base_uri' => 'https://zoom.us' ) );

if ( isset( $_GET['code'] ) ) {
	$response = $client->request(
		'POST',
		'/oauth/token',
		array(
			'headers'     => array(
				'Authorization' => 'Basic ' . base64_encode( $settings_zoom_api_key . ':' . $settings_zoom_api_secret ),
			),
			'form_params' => array(
				'grant_type'   => 'authorization_code',
				'code'         => $_GET['code'],
				'redirect_uri' => $settings_zoom_api_url,
			),
		)
	);
	$token    = json_decode( $response->getBody()->getContents(), true );
	update_user_meta( $staff_id, 'token', $token );
}
?>

<?php if ( isset( $_GET['code'] ) ) : ?>
	<?php esc_html_e( 'Successfully Updated Access token', 'school-management' ); ?>.
<?php endif ?>
<?php
return ob_get_clean();
