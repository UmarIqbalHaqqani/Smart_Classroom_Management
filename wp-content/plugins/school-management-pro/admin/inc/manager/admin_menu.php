<?php defined( 'ABSPATH' ) || die(); ?>
<div class="wrap license-container">
	<div class="top_head">
		<div class="column-3">
			<div class="logo-section">
				<img class="logo" src="<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/images/logo.png' ); ?>">
			</div>
		</div>
		<div class="column-9">
			<h1><?php esc_html_e( "Thank you for choosing School Management Plugin", 'school-management' ); ?>!</h1>
			<p class="license_info"><?php esc_html_e( "Please activate this plugin with a license key. If you don’t have a license yet, you can purchase it from ", 'school-management' ); ?>
				<a href="https://weblizar.com/members/signup/school-management" target="_blank"><?php esc_html_e( 'here', 'school-management' ); ?></a>
			</p>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="license-section">
		<div class="license-section-inner">
		<h2><?php esc_html_e( 'Let\'s get some work done!', 'school-management' ); ?> </h2>
		<p><?php esc_html_e( 'We have some useful links to get you started', 'school-management' ); ?>: </p>
		<?php
		$wlsm_lm   = WLSM_LM::get_instance();
		$validated = $wlsm_lm->is_valid();

		if ( isset( $_POST['key'] ) && ! empty( $_POST['key'] ) ) {
			$license_key = preg_replace( '/[^A-Za-z0-9-_]/', '', trim( $_POST['key'] ) );
			if( $wlsm_lm->validate( $license_key ) ) {
				$validated = true;
			}
		} else {
			$wlsm_lm->error_message = esc_html__( "Get Your License Key", 'school-management' ) . ' ' . '<a target="_blank" href="https://weblizar.com/members/softsale/license">' . esc_html__( "Click Here", 'school-management' ) . '</a>';
		} ?>
			<div class="column-6">
		<?php
		if( $validated ) {
			$key           = get_option( 'wlsm-key' );
			$first_letters = substr( $key, 0, 3 );
			$last_letters  = substr( $key, - 3 );
		?>
				<h2 class="license-message">
					<?php esc_html_e( 'License Key applied.', 'school-management' ); ?>
					<span><a href="<?php echo admin_url(); ?>"><?php esc_html_e( 'Click here to navigate to dashboard', 'school-management' ); ?></a></span>
				</h2>

				<div class="label">
					<label for="license_key"><?php esc_html_e( 'License Key', 'school-management' ); ?>:</label>
				</div>
				<div class="input-box">
					<input id="license_key" name="key" type="text" class="regular-text" value="<?php echo esc_attr( "{$first_letters}****************{$last_letters}" ); ?>" disabled>
				</div>
				<div class="Configuration_btn">
					<h2><?php esc_html_e( 'Congratulation! School Management Plugin is activated.', 'school-management' ); ?></h2>
					<div class="">

					<?php

					// API URL
					$api_url = "https://weblizar.com/updates/plugins/school-management.json";

					// Get the API response and decode the JSON data
					$response = json_decode(file_get_contents($api_url));

					// Check if the latest version is greater than the current version
					if (version_compare($response->version, WLSM_VERSION, ">")) {
						?>
						<a class="conf_btn" href="<?php echo esc_url( 'https://weblizar.com/members/login' ); ?>"><?php esc_html_e( 'Plugin Update Available', 'school-management' ); ?></a>
						<?php
					}
					?>

					</div>
				</div>
		<?php
		} else {
			if ( $wlsm_lm->error_message ) { ?>
				<h3 class="license-message"><?php echo wp_kses( $wlsm_lm->error_message, array( 'a' ) ); ?></h3>
			<?php
			} ?>
				<form method='post'>
					<div class="label">
						<label for="license_key"><?php esc_html_e( 'License Key', 'school-management' ); ?>:</label>
					</div>
					<div class="input-box">
						<input id="license_key" name="key" type="text" class="regular-text">
					</div>
					<input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Activate', 'school-management' ); ?>">
				</form>
		<?php
		} ?>
			</div>
			<div class="column-6">
				<ul class="weblizar-links">
					<li><h3><?php esc_html_e( 'Getting Started', 'school-management' ); ?></h3></li>
					<li><i class="dashicons dashicons-video-alt3"></i><a target="_blank" href="https://www.youtube.com/channel/UCFve0DTmWU4OTHXAtUOpQ7Q/playlists"><?php esc_html_e( 'Video Tutorial', 'school-management' ); ?></a></li>
					<li><i class="dashicons dashicons-portfolio"></i><a target="_blank" href="https://weblizar.com/plugins/"><?php esc_html_e( 'More Products', 'school-management' ); ?></a></li>
					<li><i class="dashicons dashicons-admin-generic"></i><a target="_blank" href="http://weblizar.com/"><?php esc_html_e( 'Help Center', 'school-management' ); ?></a></a></li>
				</ul>
				<ul class="weblizar-links">
					<li><h3><?php esc_html_e( 'Guides & Support', 'school-management' ); ?></h3></li>
					<li><i class="dashicons dashicons-welcome-view-site"></i><a target="_blank" href="http://demo.weblizar.com/school-management/"><?php esc_html_e( 'Demo', 'school-management' ); ?></a></li>
					<li><i class="dashicons dashicons-admin-users"></i><a target="_blank" href="https://weblizar.com/documentation/school-management/"><?php esc_html_e( 'Documentation guide', 'school-management' ); ?></a></li>
					<li><i class="dashicons dashicons-format-status"></i><a target="_blank" href="https://weblizar.com/support/"><?php esc_html_e( 'Support forum', 'school-management' ); ?></a></li>
				</ul>
				<div class="clearfix"></div>
				<div class="wlim-change-log">
					<div class="wlim-change-log-title-box">
						<div class="change-log-title"><a target="_blank" href="<?php echo esc_url( WLSM_PLUGIN_URL . 'changelog.txt' ); ?>"><?php echo esc_html_e( 'Change Log', 'school-management' ); ?></a></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
