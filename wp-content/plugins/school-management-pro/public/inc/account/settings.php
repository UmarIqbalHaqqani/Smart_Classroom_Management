<?php
defined( 'ABSPATH' ) || die();

$user             = wp_get_current_user();
$account_email    = $user->user_email;
$account_username = $user->user_login;

$nonce_action = 'save-account-settings';
?>
<div class="wlsm-content-area wlsm-section-settings">
	<div class="wlsm-st-main-title">
		<span>
		<?php esc_html_e( 'Account Settings', 'school-management' ); ?>
		</span>
	</div>

	<div class="wlsm-st-account-settings-section">
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-settings-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-p-save-account-settings">

			<div class="wlsm-form-group wlsm-row wlsm-mb-2">
				<div class="wlsm-col-12">
					<label class="wlsm-form-label wlsm-font-bold">
						<?php esc_html_e( 'Username', 'school-management' ); ?>:
					</label>
					<span><?php echo esc_html( $account_username ); ?></span>
				</div>
			</div>

			<div class="wlsm-form-group wlsm-row wlsm-mb-2">
				<div class="wlsm-col-4">
					<label for="wlsm_account_email" class="wlsm-form-label wlsm-font-bold">
						<span class="wlsm-text-danger">*</span> <?php esc_html_e( 'Email Address', 'school-management' ); ?>:
					</label>
				</div>
				<div class="wlsm-col-8">
					<input type="email" name="email" class="wlsm-form-control" id="wlsm_account_email" value="<?php echo esc_attr( $account_email ); ?>">
				</div>
			</div>

			<div class="wlsm-form-group wlsm-row wlsm-mb-2">
				<div class="wlsm-col-4">
					<label for="wlsm_account_password" class="wlsm-form-label wlsm-font-bold">
						<span class="wlsm-text-danger">*</span> <?php esc_html_e( 'Password', 'school-management' ); ?>:
					</label>
				</div>
				<div class="wlsm-col-8">
					<input type="password" name="password" class="wlsm-form-control" id="wlsm_account_password">
				</div>
			</div>

			<div class="wlsm-form-group wlsm-row wlsm-mb-2">
				<div class="wlsm-col-4">
					<label for="wlsm_account_password_confirm" class="wlsm-form-label wlsm-font-bold">
						<span class="wlsm-text-danger">*</span> <?php esc_html_e( 'Confirm Password', 'school-management' ); ?>:
					</label>
				</div>
				<div class="wlsm-col-8">
					<input type="password" name="password_confirm" class="wlsm-form-control" id="wlsm_account_password_confirm">
				</div>
			</div>

			<div class="wlsm-border-top wlsm-pt-2 wlsm-mt-1">
				<button class="button wlsm-btn btn btn-primary" type="submit" id="wlsm-save-settings-btn">
					<?php esc_html_e( 'Save Settings', 'school-management' ); ?>
				</button>
			</div>

		</form>
	</div>
</div>
