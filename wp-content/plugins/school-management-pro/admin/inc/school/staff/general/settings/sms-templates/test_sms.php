<?php
defined( 'ABSPATH' ) || die();
?>
<div class="text-right">
	<input type="text" class="wlsm-send-test-sms-to" placeholder="<?php esc_attr_e( 'Enter phone number', 'school-management' ); ?>">
	<button type="button" class="btn btn-sm btn-outline-primary wlsm-send-test-sms" data-template="<?php echo esc_attr( $sms_template ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce('send-test-sms') ); ?>">
		<?php esc_html_e( 'Send Test SMS', 'school-management' ); ?>
	</button>
</div>
