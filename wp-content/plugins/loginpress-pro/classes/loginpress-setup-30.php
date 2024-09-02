<?php
/**
 * LoginPress Pro Setup 3.0
 *
 * @package LoginPress Pro
 */

if ( ! class_exists( 'LoginPress_Pro_Setup_30' ) ) :

	/**
	 * LoginPress_Pro_Setup_30
	 */
	class LoginPress_Pro_Setup_30 {

		/**
		 * Addon Slugs
		 *
		 * @var array
		 */
		private $loginpress_addons_slugs;

		/**
		 * Whether to show or hide the notice of setup.
		 *
		 * @var bool
		 */
		private $show_notice;

		/**
		 * Class Constructor
		 *
		 * @param bool $args Whether to show or hide the notice of setup.
		 */
		public function __construct( $show_notice ) {
			$this->construct_addons_array();
			if ( $show_notice ) {
				$this->hooks();
			}
			$this->show_notice = $show_notice;
		}

		/**
		 * Construct addon/plugins slug array.
		 *
		 * @since 3.0.0
		 */
		private function construct_addons_array() {
			$addons_slugs = array(
				'login-redirects',
				'login-widget',
				'social-login',
				'limit-login-attempts',
				'auto-login',
				'hide-login',
			);

			foreach ( $addons_slugs as $slug ) {
				$this->loginpress_addons_slugs[] = "loginpress-$slug/loginpress-$slug.php";
			}
		}

		/**
		 * Call wp action hooks.
		 *
		 * @since 3.0.0
		 */
		public function hooks() {

			if ( ( ! $this->is_all_addons_updated() ) || ( '3.0.5' > LOGINPRESS_VERSION ) ) {
				if ( version_compare( '3.0.5', LOGINPRESS_VERSION, '<=' ) && ! class_exists( 'LoginPress_Addons' ) ) {
					include LOGINPRESS_DIR_PATH . 'classes/class-loginpress-addons.php';
					$addons_manager = new LoginPress_Addons();
					$addons_manager->addons_array_construct();
				}
				add_action( 'admin_notices', array( $this, 'setup_notice' ) );
				add_action( 'admin_menu', array( $this, 'loginpress_pro_30_setup_page' ) );
			}

			// @TODO: Add proper condition to include these.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts_cb' ) );
			add_action( 'wp_ajax_loginpress_pro_update_addon_plugin', array( $this, 'loginpress_pro_update_addon_plugin' ) );
			add_action( 'wp_ajax_loginpress_free_update_plugin', array( $this, 'loginpress_free_update_plugin' ) );
		}

		/**
		 * Load admin setup scripts.
		 *
		 * @param int $page The current page.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function admin_enqueue_scripts_cb( $page ) {

			if ( 'loginpress_page_loginpress-setup-30' === $page ) {
				wp_enqueue_script( 'loginpress-setup-30', LOGINPRESS_PRO_DIR_URL . 'assets/js/loginpress-setup-30.js', array( 'jquery' ), LOGINPRESS_PRO_VERSION, true );
				wp_enqueue_style( 'loginpress-setup-30', LOGINPRESS_PRO_DIR_URL . 'assets/css/loginpress-setup-30.css', array(), LOGINPRESS_PRO_VERSION );

				wp_localize_script(
					'loginpress-setup-30',
					'loginpress_setup',
					array(
						'upgrade_free_security' => wp_create_nonce( 'upgrade-plugin_loginpress/loginpress.php' ),
						'update_addon_security' => wp_create_nonce( 'loginpress_update_addon_30' ),
					)
				);
			}
		}

		/**
		 * Activate LoginPress after update.
		 *
		 * @version 3.0.0
		 */
		public function loginpress_free_update_plugin() {

			check_ajax_referer( 'upgrade-plugin_loginpress/loginpress.php', 'security' );

			if ( ! current_user_can( 'update_plugins' ) ) {
				$status['errorMessage'] = __( 'Sorry, you are not allowed to update plugins for this site.' );
				wp_send_json_error( $status );
			}

			if ( empty( $_POST['plugin'] ) || empty( $_POST['slug'] ) ) {
				wp_send_json_error(
					array(
						'slug'         => '',
						'errorCode'    => 'no_plugin_specified',
						'errorMessage' => __( 'No plugin specified.' ),
					)
				);
			}

			$plugin = plugin_basename( sanitize_text_field( wp_unslash( $_POST['plugin'] ) ) );

			$status = array(
				'update'     => 'plugin',
				'slug'       => sanitize_key( wp_unslash( $_POST['slug'] ) ),
				'oldVersion' => '',
				'newVersion' => '',
			);

			$status['plugin'] = 'loginpress/loginpress.php';

			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

			wp_update_plugins();

			$skin     = new WP_Ajax_Upgrader_Skin();
			$upgrader = new Plugin_Upgrader( $skin );
			$result   = $upgrader->bulk_upgrade( array( $plugin ) );

			if ( false !== $result ) {

				activate_plugin( 'loginpress/loginpress.php' );
				$status['allUpdated']        = $this->is_all_addons_updated();
				$status['settings_redirect'] = admin_url() . 'admin.php?page=loginpress-settings';

				wp_send_json_success( $status );

			} elseif ( false === $result ) {
				global $wp_filesystem;

				$status['errorCode']    = 'unable_to_connect_to_filesystem';
				$status['errorMessage'] = __( 'Unable to connect to the filesystem. Please confirm your credentials.' );

				// Pass through the error from WP_Filesystem if one was raised.
				if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->has_errors() ) {
					$status['errorMessage'] = esc_html( $wp_filesystem->errors->get_error_message() );
				}

				wp_send_json_error( $status );
			}

			// An unhandled error occurred.
			$status['errorMessage'] = __( 'Plugin update failed.' );
			wp_send_json_error( $status );
			wp_die();
		}
		/**
		 * Ajax callback for update addons.
		 * Detail: Sends the required updates addons to ajax live update.
		 *
		 * @since 3.0.0
		 */
		public function loginpress_pro_update_addon_plugin() {

			check_ajax_referer( 'loginpress_update_addon_30', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}

			foreach ( $this->loginpress_addons_slugs as $loginpress_addon ) {

				if ( ! empty( $_POST['plugin_updated'] ) ) {
					$this->active_pro_addon( sanitize_text_field( wp_unslash( $_POST['plugin_updated'] ) ) );
				}

				if ( true === $this->is_addon_update_required( $loginpress_addon ) ) {

					if ( 'loginpress-auto-login/loginpress-auto-login.php' === $loginpress_addon ) {
						$this->loginpress_update_autologin_meta();
					}

					$loginpress_addon_response = array( $loginpress_addon, wp_create_nonce( 'upgrade-plugin_' . $loginpress_addon ) );
					wp_send_json( $loginpress_addon_response );
					break;
				}
			}

			echo true;
			wp_die();
		}

		/**
		 * Check if update is required for addon.
		 *
		 * @param string $loginpress_addon the LoginPress addon.
		 *
		 * @since 3.0.0
		 * @return bool
		 */
		public function is_addon_update_required( $loginpress_addon ) {
			$plugin_data = get_plugins();
			$return      = false;

			if ( ( isset( $plugin_data[ $loginpress_addon ] ) ) && is_plugin_active( $loginpress_addon ) ) {
				switch ( $loginpress_addon ) {
					case 'loginpress-login-redirects/loginpress-login-redirects.php':
						if ( $plugin_data[ $loginpress_addon ]['Version'] <= '3.0.0' ) {
							$return = true;
						}
						break;

					case 'loginpress-login-widget/loginpress-login-widget.php':
						if ( $plugin_data[ $loginpress_addon ]['Version'] <= '3.0.0' ) {
							$return = true;
						}
						break;

					case 'loginpress-auto-login/loginpress-auto-login.php':
						if ( $plugin_data[ $loginpress_addon ]['Version'] <= '3.0.0' ) {
							$return = true;
						}
						break;

					case 'loginpress-hide-login/loginpress-hide-login.php':
						if ( $plugin_data[ $loginpress_addon ]['Version'] <= '3.0.0' ) {
							$return = true;
						}
						break;

					case 'loginpress-limit-login-attempts/loginpress-limit-login-attempts.php':
						if ( $plugin_data[ $loginpress_addon ]['Version'] <= '4.0.0' ) {
							$return = true;
						}
						break;

					case 'loginpress-social-login/loginpress-social-login.php':
						if ( $plugin_data[ $loginpress_addon ]['Version'] <= '3.0.0' ) {
							$return = true;
						}
						break;
				}

				return $return;
			}

			return false;
		}

		/**
		 *  Update the addon in LoginPress Pro 3.0 Core and deactivates the addons as separate plugins.
		 *
		 * @param mixed $plugin_updated The addon slug to be updated.
		 *
		 * @since 3.0.0
		 * @return bool
		 */
		public function active_pro_addon( $plugin_updated ) {

			$addons      = get_option( 'loginpress_pro_addons' );
			$plugin_slug = $this->get_cleaned_slug( $plugin_updated );

			// Activate plugin in PRO addons array.
			foreach ( $addons as $addon ) {
				if ( $plugin_slug === $addon['slug'] ) {
					$addons[ $plugin_slug ]['is_active'] = true;
					break;
				}
			}

			if ( 'loginpress/loginpress.php' === $plugin_updated ) {
				// For core plugin (re-activate it after WP deactivates).
				activate_plugin( 'loginpress/loginpress.php' );
			} else {
				// For addons (normally deactivates by themselves).
				if ( is_plugin_active( $plugin_updated ) ) {
					deactivate_plugins( $plugin_updated );
				}
			}

			update_option( 'loginpress_pro_addons', $addons );

			return true;
		}

		/**
		 * Extract plugin base name from slug.
		 *
		 * @param string $loginpress_addon Single Addon.
		 *
		 * @since 3.0.0
		 * @return string $plugin_slug Plugin slug.
		 */
		public function get_cleaned_slug( $loginpress_addon ) {
			$plugin_slug = explode( '/', $loginpress_addon );
			$plugin_slug = explode( 'loginpress-', $plugin_slug[0] );
			$plugin_slug = $plugin_slug[1];

			return $plugin_slug;
		}

		/**
		 * Check if all addons are updated.
		 *
		 * @since 3.0.0
		 * @return bool
		 */
		public function is_all_addons_updated() {
			foreach ( $this->loginpress_addons_slugs as $loginpress_addon ) {
				if ( $this->is_addon_update_required( $loginpress_addon ) ) {
					return false;
				}
			}

			return true;
		}

		/**
		 * Display setup notice.
		 *
		 * @return HTML
		 * @since 3.0.0
		 */
		public function setup_notice() {
			if ( function_exists( 'get_current_screen' ) ) {
				$current_screen = get_current_screen();

				if ( 'loginpress_page_loginpress-setup-30' === $current_screen->id ) {
					return;
				}
				if ( LOGINPRESS_VERSION <= '3.0.5' && $this->show_notice ) {
					/* Translators: LoginPress 3.0 Setup Error message. */
					printf( __( '%1$s%3$sPlease complete the %5$ssetup%6$s for LoginPress 3.0%4$s%2$s', 'loginpress-pro' ), '<div class="' . 'notice notice-error' . '">', '</div>', '<p>', '</p>', '<a href="' . esc_url( admin_url() . 'admin.php?page=loginpress-setup-30' ) . '">', '</a>' );
				}
			}
		}

		/**
		 * Generate setup page for LoginPress Pro.
		 *
		 * @since 3.0.0
		 */
		public function loginpress_pro_30_setup_page() {
			add_submenu_page( 'loginpress-settings', __( 'Setup 3.0', 'loginpress' ), __( 'Setup 3.0', 'loginpress' ), 'manage_options', 'loginpress-setup-30', array( $this, 'loginpress_pro_setup_30' ) );
		}

		/**
		 * Render HTML for LoginPress Pro setup.
		 *
		 * @since 3.0.0
		 */
		public function loginpress_pro_setup_30() {
			?>
			<div class="loginpress-pro-setup-30-wrapper">
				<div class="heading-wrapper">
					<p class="loginpress-plugin-info-30"><?php esc_html_e( 'LoginPress PRO 3.0 Setup', 'loginpress-pro' ); ?></p>
				</div>
				<div class="steps-wrapper">

					<!-- first step wrapper -->
					<?php if ( version_compare( '3.0.5', LOGINPRESS_VERSION, '>' ) ) : ?>
						<div class="loginpress-pro-30-wrapper loginpress-pro-30-update-main-wrapper">
							<div class="loginpress-pro-30-inner-wrapper">
								<div class="loginpress-logo-pro-30">
									<img src="<?php echo esc_url( plugins_url( '../assets/img/loginpress-logo.png', __FILE__ ) ); ?>" alt="loginpress">
									<p><?php esc_html_e( 'Updating to LoginPress 3.0 is essential for the compatibility with the new optimized PRO version.', 'loginpress-pro' ); ?></p>
								</div>
								<button id="loginpress-pro-setup-30-update-free"><?php esc_html_e( 'Update Free Version', 'loginpress-pro' ); ?></button>
								<div id="loginpressUpdatingFree" class="loginpress-install updating" style="display:none;">
									<div class="loginpress-logo-container-30">
										<img src="<?php echo esc_url( plugins_url( 'assets/img/loginpress-logo2.png', LOGINPRESS_PRO_PLUGIN_BASENAME ) ); ?>" alt="loginpress">
										<svg class="circular-loader-30" viewBox="25 25 50 50">
											<circle class="loader-path-30" cx="50" cy="50" r="20" fill="none" stroke="#d8d8d8" stroke-width="1"></circle>
										</svg>
									</div>
									<p class="loginpress-pro-setup-30-loading"><?php esc_html_e( 'Updating LoginPress', 'loginpress-pro' ); ?></p>
								</div>
								<div id="loginpressUpdatedFree" class="loginpress-install updated-30" style="display:none;">
									<div class="circle-loader-30">
										<div class="checkmark draw"></div>
									</div>
									<p><?php esc_html_e( 'LoginPress Updated.', 'loginpress-pro' ); ?></p>
								</div>
							</div>
						</div>
						<?php
					endif;
					?>

					<!-- second step wrapper -->
					<?php
					$i               = 0;
					$display_element = ( version_compare( '3.0.5', LOGINPRESS_VERSION, '>' ) || true === $this->is_all_addons_updated() ) ? ' style="display:none !important;"' : '';
					?>
					<div class="loginpress-pro-30-wrapper loginpress-pro-30-update-addons-wrapper"<?php echo $display_element; ?> >
						<div class="loginpress-pro-30-inner-wrapper">
							<button id="loginpress-pro-setup-30-update-addons"><?php esc_html_e( 'Update Addons', 'loginpress-pro' ); ?></button>
							<table class="loginpress-pro-30-update-addons-list">
								<tr>
									<th>#</th>
									<th><?php esc_html_e( 'Plugin Name', 'loginpress-pro' ); ?></th>
									<th><?php esc_html_e( 'Progress', 'loginpress-pro' ); ?></th>
									<th class="status"><?php esc_html_e( 'Status', 'loginpress-pro' ); ?></th>
								</tr>
								<?php
								foreach ( $this->loginpress_addons_slugs as $loginpress_addon ) {
									if ( true === $this->is_addon_update_required( $loginpress_addon ) ) {
										$plugin_slug = $this->get_cleaned_slug( $loginpress_addon );
										$plugin_name = str_replace( '-', ' ', $plugin_slug );
										$i++;
										?>
										<tr id="<?php echo esc_attr( $plugin_slug ); ?>">
											<td><?php echo intval( $i ); ?></td>
											<td><?php echo esc_html( $plugin_name ); ?></td>
											<td class="progress"><?php esc_html_e( 'Update Available', 'loginpress-pro' ); ?></td>
											<td class="status"><span class="checkbox">&check;</span></td>
										</tr>
										<?php
									}
								}
								?>
							</table>
							<div class="loginpress-pro-30-all-update"><?php esc_html_e( 'All Addons activated', 'loginpress-pro' ); ?></div>
						</div>
					</div>

					<!-- third step wrapper -->
					<?php $display_element = ( true === $this->is_all_addons_updated() && version_compare( '3.0.5', LOGINPRESS_VERSION, '<' ) ) ? 'style="display: flex;"' : ''; ?>
					<div class="loginpress-pro-30-wrapper loginpress-pro-30-finish-wrapper" <?php echo $display_element; ?> >
						<div class="loginpress-pro-30-inner-wrapper">
							<h2><?php esc_html_e( 'Congratulations...', 'loginpress-pro' ); ?></h2>
							<p><?php esc_html_e( 'You have successfully upgraded to LoginPress 3.0 Enjoy the new optimized experience for LoginPress.', 'loginpress-pro' ); ?></p>
							<a href="<?php echo esc_url( get_admin_url() . 'admin.php?page=loginpress-addons' ); ?>" id="loginpress-pro-setup-30-finish"><?php esc_html_e( 'Finish', 'loginpress-pro' ); ?></a>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Creates the meta once and performs the gathering of previous code meta into array
		 *
		 * @return void
		 */
		public function loginpress_update_autologin_meta() {

			$user_query = new WP_User_Query(
				array(
					'meta_key' => 'loginpress_autologin_code',
				)
			);

			// Get_results w.r.t 'meta_key' => 'loginpress_autologin_code'.
			$autologin_user = $user_query->get_results();

			// Check for results.
			if ( ! empty( $autologin_user ) ) {
				// loop through each user.

				foreach ( $autologin_user as $user ) {

					$code           = get_user_meta( $user->ID, 'loginpress_autologin_code', true );
					$date           = gmdate( 'Y-m-d' );
					$default_expire = gmdate( 'Y-m-d', strtotime( "$date +7 day" ) ); // PHP:  yy-mm-dd.
					$expire         = apply_filters( 'loginpress_autologin_default_expiration', $default_expire );

					$replace_array = array(
						'state'    => sanitize_text_field( 'enable' ),
						'emails'   => sanitize_text_field( '' ),
						'code'     => sanitize_text_field( $code ),
						'expire'   => sanitize_text_field( 'unchecked' ),
						'duration' => sanitize_text_field( $expire ),
					);
					update_user_meta( $user->ID, 'loginpress_autologin_user', $replace_array );
					delete_user_meta( $user->ID, 'loginpress_autologin_code' );
				}
			}
		}
	}

endif;
