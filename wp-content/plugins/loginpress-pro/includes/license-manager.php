<?php
/**
 * HTML structure of License Manage page.
 *
 * @since 1.0.0
 * @version 3.0.0
 * @package LoginPress-Pro
 * @return HTML
 */

$loginpress_pro_license = '';
if ( 'valid' === self::get_registered_license_status() && null !== get_option( 'loginpress_pro_license_key' ) ) {
	$loginpress_pro_license = self::mask_license( get_option( 'loginpress_pro_license_key' ) );
}
if ( version_compare( LOGINPRESS_VERSION, '3.0.5', '>=' ) ) {
	echo LoginPress_Settings::loginpress_admin_page_header();
}
?>

<div class="wrap">
	<h2 class="loginpress-license-heading">
		<?php esc_html_e( 'Activate your License', 'loginpress-pro' ); ?>
	</h2>
	<div class="loginpress-admin-setting">
		<div class="loginpress-tabs-main">
			<span class="tabs-toggle"><?php esc_html_e( 'Menu', 'loginpress-pro' ); ?></span>
			<?php if ( version_compare( LOGINPRESS_VERSION, '3.0.5', '>=' ) ) : ?>
				<ul class="nav-tab-wrapper loginpress-tabs-wrapper">
					<li class="settings-tabs-list">
						<a href="<?php echo esc_url( site_url() . '/wp-admin/admin.php?page=loginpress-settings&tab=setting' ); ?>" class="nav-tab" id="loginpress_setting-tab">
							<?php echo esc_html_e( 'Settings', 'loginpress-pro' ); ?>
							<span><?php echo esc_html_e( 'Login Page Setting', 'loginpress-pro' ); ?></span>
						</a>
					</li>
					<?php
					if ( self::is_registered() ) {
						$addons_array = get_option( 'loginpress_pro_addons' );
						$addon_tabs   = array(
							'autologin'            => 'auto-login',
							'login_redirects'      => 'login-redirects',
							'limit_login_attempts' => 'limit-login-attempts',
							'hidelogin'            => 'hide-login',
							'social_logins'        => 'social-login',
						);
						if ( $addons_array ) {
							foreach ( $addons_array as $addon ) {
								$addon_tab = array_search( $addon['slug'], $addon_tabs, true );

								if ( $addon['is_active'] && $addon_tab ) {
									?>
									<li class="settings-tabs-list">
										<a href="<?php echo esc_url( site_url() . '/wp-admin/admin.php?page=loginpress-settings&tab=' . $addon_tab ); ?>" class="nav-tab" id='loginpress_<?php echo $addon_tab; ?>-tab'>
											<?php echo esc_html( $addon['title'] ); ?>
											<span><?php echo esc_html( $addon['short_desc'] ); ?></span>
										</a>
									</li>
									<?php
								}
							}
						}
					}
					?>

					<li class="settings-tabs-list">
						<a href="#loginpress_pro_license" class="nav-tab nav-tab-active" id="loginpress_pro_license-tab">
							<?php
								/* translators: License Manager Of LoginPress */
								echo sprintf( __( 'License Manager %1$sManage Your License Key%2$s', 'loginpress' ), '<span>', '</span>' );  // @codingStandardsIgnoreLine.
							?>
						</a>
					</li>
				</ul>
			<?php endif; ?>
		</div>
		<div class="metabox-holder loginpress-settings" id="loginpress_pro_license-tab">
			<div id="loginpress-license-settings" class="group" style="">
				<form method="post" action="options.php" class="loginpress-settings loginpress-license-settings">
					<?php settings_fields( 'loginpress_pro_license' ); ?>
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row" valign="top">
									<?php
									echo '<label for="loginpress_pro_license_key">' . esc_html__( 'License Key', 'loginpress-pro' ) . '</label>';
									?>
								</th>
								<td>
									<input id="loginpress_pro_license_key" placeholder="<?php esc_html_e( 'Enter your license key', 'loginpress-pro' ); ?>" name="loginpress_pro_license_key" type="text" class="regular-text" value="<?php echo esc_html( $loginpress_pro_license ); ?>" />
									<label class="description" for="loginpress_pro_license_key"><?php echo esc_html__( 'Validating license key is mandatory to use automatic updates and plugin support.', 'loginpress-pro' ); ?></label>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row" valign="top"></th>
								<td>
									<?php
									if ( self::is_registered() ) {
										wp_nonce_field( 'loginpress_pro_deactivate_license_nonce', 'loginpress_pro_deactivate_license_nonce' );
										?>
										<input type="submit" class="button-secondary" name="loginpress_pro_license_deactivate" value="<?php echo esc_html__( 'Deactivate License', 'loginpress-pro' ); ?>"/>
										<?php
									} else {
										wp_nonce_field( 'loginpress_pro_activate_license_nonce', 'loginpress_pro_activate_license_nonce' );
										?>
										<input type="submit" class="button-secondary" name="loginpress_pro_license_activate" value="<?php echo esc_html__( 'Activate License', 'loginpress-pro' ); ?>"/>
										<?php
									}
									?>
								</td>
							</tr>

							<tr>
								<th></th>
								<td class="loginpress-license-desc">
									<?php
									if ( self::is_registered() ) {

										$expiration_date = self::get_expiration_date();

										if ( 'lifetime' === $expiration_date ) {
											$license_desc = esc_html__( 'You have a lifetime licenses, it will never expire.', 'loginpress-pro' );
										} else {
											$license_desc = sprintf(
											/* Translators: Validity of key. */
												__( 'Your (%2$s) license key is valid until %1$s.', 'loginpress-pro' ),
												'<strong>' . date_i18n( get_option( 'date_format' ), strtotime( $expiration_date, current_time( 'timestamp' ) ) ) . '</strong>',
												self::get_license_type()
											);
										}

										if ( 'lifetime' !== $expiration_date ) {
											$license_tooltip_desc = sprintf(
											/* Translators: Automatic Renewal of key. */
												esc_html__( 'The license will automatically renew, if you have an active subscription to the LoginPress Pro - at %s', 'loginpress-pro' ),
												'<a href="https://wpbrigade.com/account/?utm_source=license-tab">WPBrigade.com</a>'
											);
										} else {
											$license_tooltip_desc = '';
										}

										if ( self::has_license_expired() ) {
											$license_desc = sprintf(
												/* Translators: Key Expired on. */
												esc_html__( 'Your license key expired on %s. Please input a valid non-expired license key. If you think, that this license has not yet expired (was renewed already), then please save the settings, so that the license will verify again and get the up-to-date expiration date.', 'loginpress-pro' ),
												'<strong>' . date_i18n( get_option( 'date_format' ), strtotime( $expiration_date, current_time( 'timestamp' ) ) ) . '</strong>'
											);
											$license_tooltip_title = '';
											$license_tooltip_desc  = '';
										}

										echo $license_desc . '<br /><i>' . $license_tooltip_desc . '</i>';
									} else {
										echo self::get_registered_license_status();
									}
									?>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
		</div>
	</div>
</div>
