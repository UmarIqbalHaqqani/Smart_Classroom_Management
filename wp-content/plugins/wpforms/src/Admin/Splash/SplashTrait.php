<?php

namespace WPForms\Admin\Splash;

trait SplashTrait {

	/**
	 * Get splash data version.
	 *
	 * @since 1.8.7
	 *
	 * @return string Splash data version.
	 */
	private function get_splash_data_version(): string {

		return get_option( 'wpforms_splash_data_version', WPFORMS_VERSION );
	}

	/**
	 * Update splash data version.
	 *
	 * @since 1.8.7
	 *
	 * @param string $version Splash data version.
	 */
	private function update_splash_data_version( string $version ) {

		update_option( 'wpforms_splash_data_version', $version );
	}

	/**
	 * Get latest splash version.
	 *
	 * @since 1.8.7
	 *
	 * @return string Splash version.
	 */
	private function get_latest_splash_version(): string {

		return get_option( 'wpforms_splash_version', '' );
	}

	/**
	 * Update option with latest splash version.
	 *
	 * @since 1.8.7
	 */
	private function update_splash_version() {

		update_option( 'wpforms_splash_version', WPFORMS_VERSION );
	}

	/**
	 * Remove hide_welcome_block widget meta key for all users.
	 *
	 * @since 1.8.7
	 */
	private function remove_hide_welcome_block_widget_meta() {

		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->delete(
			$wpdb->usermeta,
			[
				'meta_key' => 'wpforms_dash_widget_hide_welcome_block', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			]
		);
	}

	/**
	 * Get default splash modal data.
	 *
	 * @since 1.8.7
	 *
	 * @return array Splash modal data.
	 */
	private function get_default_data(): array {

		return [
			'license' => wpforms_get_license_type(),
			'buttons' => [
				'get_started' => __( 'Get Started', 'wpforms-lite' ),
				'learn_more'  => __( 'Learn More', 'wpforms-lite' ),
			],
			'header'  => [
				'image'       => WPFORMS_PLUGIN_URL . 'assets/images/splash/sullie.svg',
				'title'       => __( 'What’s New in WPForms', 'wpforms-lite' ),
				'description' => __( 'Since you’ve been gone, we’ve added some great new features to help grow your business and generate more leads. Here are some highlights...', 'wpforms-lite' ),
			],
			'footer'  => [
				'title'       => __( 'Start Building Smarter WordPress Forms', 'wpforms-lite' ),
				'description' => __( 'Add advanced form fields and conditional logic, plus offer more payment options, manage entries, and connect to your favorite marketing tools – all when you purchase a premium plan.', 'wpforms-lite' ),
				'upgrade'     => [
					'text' => __( 'Upgrade to Pro Today', 'wpforms-lite' ),
					'url'  => wpforms_admin_upgrade_link( 'splash-modal', 'Upgrade to Pro Today' ),
				],
			],
		];
	}

	/**
	 * Prepare buttons.
	 *
	 * @since 1.8.7
	 *
	 * @param array $buttons Buttons.
	 *
	 * @return array Prepared buttons.
	 */
	private function prepare_buttons( array $buttons ): array {

		return array_map(
			function ( $button ) {
				return [
					'url'  => $this->prepare_url( $button['url'] ),
					'text' => $button['text'],
				];
			},
			$buttons
		);
	}

	/**
	 * Prepare URL.
	 *
	 * @since 1.8.7
	 *
	 * @param string $url URL.
	 *
	 * @return string Prepared URL.
	 */
	private function prepare_url( string $url ): string {

		$replace_tags = [
			'{admin_url}'   => admin_url(),
			'{license_key}' => wpforms_get_license_key(),
		];

		return str_replace( array_keys( $replace_tags ), array_values( $replace_tags ), $url );
	}

	/**
	 * Get block layout.
	 *
	 * @since 1.8.7
	 *
	 * @param array $image Image data.
	 *
	 * @return string Block layout.
	 */
	private function get_block_layout( array $image ): string {

		$image_type = $image['type'] ?? 'icon';

		switch ( $image_type ) {
			case 'icon':
				$layout = 'one-third-two-thirds';
				break;

			case 'illustration':
				$layout = 'fifty-fifty';
				break;

			default:
				$layout = 'full-width';
				break;
		}

		return $layout;
	}
}
