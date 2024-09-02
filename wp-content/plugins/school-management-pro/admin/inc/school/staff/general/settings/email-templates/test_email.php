<?php
defined( 'ABSPATH' ) || die();
?>
<div class="text-right">
	<input type="email" class="wlsm-send-test-email-to" placeholder="<?php esc_attr_e( 'Enter email', 'school-management' ); ?>">
	<button type="button" class="btn btn-sm btn-outline-primary wlsm-send-test-email" data-template="<?php echo esc_attr( $email_template ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce('send-test-email') ); ?>">
		<?php esc_html_e( 'Send Test Email', 'school-management' ); ?>
	</button>
</div>
