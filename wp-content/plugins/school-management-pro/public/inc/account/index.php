<?php
defined( 'ABSPATH' ) || die();

global $wp;

$current_page_url = home_url( add_query_arg( array(), $wp->request ) );
if ( ! is_user_logged_in() ) {
	$login_form_args = array(
		'redirect'       => $current_page_url, 
		'form_id'        => 'wlsm-login-form',
		'id_username'    => 'wlsm-login-username',
		'id_password'    => 'wlsm-login-password',
		'id_remember'    => 'wlsm-login-remember',
		'id_submit'      => 'wlsm-login-submit',
		'value_username' => '',
	);
	wp_login_form( $login_form_args );
	?>
	<a target="_blank" href="<?php echo esc_url( wp_lostpassword_url( $current_page_url ) ); ?>">
		<?php esc_html_e( 'Lost your password?', 'school-management' ); ?>
	</a>
	<?php
} else {
	global $wpdb;

	$user_id = get_current_user_id();

	// Checks if user is student.
	$student = WLSM_M::get_student( $user_id );

	$logout_redirect_url = $current_page_url;
	if ( $student ) {
		$school_id                          = $student->school_id;
		$settings_general                   = WLSM_M_Setting::get_settings_general( $school_id );
		$school_student_logout_redirect_url = $settings_general['student_logout_redirect_url'];
		if ( ! empty( $school_student_logout_redirect_url ) ) {
			$logout_redirect_url = $school_student_logout_redirect_url;
		}
	}
	$session_id = $student ? $student->session_id : 0;
	$logout_url = wp_logout_url( $logout_redirect_url );
	$current_user = wp_get_current_user();

	?>
	<div class="wlsm-logged-in-info">
		<span class="wlsm-logged-in-text"><?php echo esc_html(ucwords($current_user->user_login)) ?><?php esc_html_e( ' ', 'school-management' ); ?></span>
		<a class="wlsm-logout-link" href="<?php echo esc_url( $logout_url ); ?>">
			<?php esc_html_e( 'Logout', 'school-management' ); ?>
		</a>
		<br>
		<br>
	<?php 
	$current_session = WLSM_Config::current_session();
	?>
	</div>
	<?php
	if ( $student ) {
		$school_id  = $student->school_id;
		$session_id = $student->session_id;

		$class_school_id = $student->class_school_id;
		require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/route.php';
	} else {
		require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Parent.php';

		$unique_student_ids = WLSM_M_Parent::get_parent_student_ids( $user_id );

		if ( count( $unique_student_ids ) ) {
			require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/parent/route.php';
		} else {
			require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/no_record.php';
		}
	}
}
