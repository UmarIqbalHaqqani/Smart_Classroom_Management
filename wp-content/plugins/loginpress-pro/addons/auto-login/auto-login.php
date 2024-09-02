<?php
/**
 * Addon Name: LoginPress - Auto Login
 * Description: LoginPress - Auto Login is the best Login plugin by <a href="https://wpbrigade.com/">WPBrigade</a> which allows you to login without Username and Password.
 *
 * @package loginPress
 * @category Core
 * @author WPBrigade
 * @version 3.0.0
 */

if ( ! class_exists( 'LoginPress_AutoLogin' ) ) :
	/**
	 * LoginPress AutoLogin Class
	 *
	 * @since 3.0.0
	 */
	final class LoginPress_AutoLogin {

		/**
		 * Class constructor
		 *
		 * @since 3.0.0
		 */
		public function __construct() {

			if ( LoginPress_Pro::addon_wrapper( 'auto-login' ) ) {
				$this->hooks();
				$this->define_constants();
				$this->includes();
			}
		}

		/**
		 * Hook into actions and filters
		 *
		 * @since 1.0.0
		 * @version 3.0.0
		 */
		public function hooks() {

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_filter( 'loginpress_settings_tab', array( $this, 'loginpress_autologin_tab' ), 10, 1 );
			add_filter( 'loginpress_settings_fields', array( $this, 'loginpress_autologin_settings_array' ), 10, 1 );
			add_filter( 'loginpress_autologin', array( $this, 'loginpress_autologin_callback' ), 10, 1 );
			// add_action( 'admin_init', array( $this, 'init_addon_updater' ), 0 );.
			add_action( 'admin_footer', array( $this, 'loginpress_autologin_autocomplete_js' ) );
			add_action( 'wp_ajax_loginpress_autologin', array( $this, 'autologin_update_user_meta' ) );
			add_action( 'wp_ajax_loginpress_autologin_emailuser', array( $this, 'loginpress_autologin_emailuser' ) );
			add_action( 'wp_ajax_loginpress_autologin_delete', array( $this, 'autologin_delete_user_meta' ) );
			add_action( 'wp_ajax_loginpress_change_autologin_state', array( $this, 'loginpress_change_autologin_state' ) );
			add_action( 'wp_ajax_loginpress_update_duration', array( $this, 'loginpress_update_duration' ) );
			add_action( 'wp_ajax_loginpress_update_email', array( $this, 'loginpress_update_email' ) );
			add_action( 'wp_ajax_loginpress_populate_popup_duration', array( $this, 'loginpress_populate_popup_duration' ) );
			add_action( 'wp_ajax_loginpress_populate_popup_email', array( $this, 'loginpress_populate_popup_email' ) );
			add_action( 'loginpress_autologin_script', array( $this, 'autologin_script_html' ) );
		}

		/**
		 * Includes include files
		 *
		 * @since 1.0.0
		 * @version 3.0.0
		 */
		public function includes() {

			include_once LOGINPRESS_AUTOLOGIN_DIR_PATH . 'classes/class-user-login.php';
		}

		/**
		 * Define LoginPress AutoLogin Constants
		 *
		 * @since 1.0.0
		 */
		private function define_constants() {
			LoginPress_Pro_Init::define( 'LOGINPRESS_AUTOLOGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
		}

		/**
		 * LoginPress Addon updater
		 *
		 * @version 3.0.0
		 */
		public function init_addon_updater() {
			if ( class_exists( 'LoginPress_AddOn_Updater' ) ) {
				$updater = new LoginPress_AddOn_Updater( 2324, __FILE__, $this->version );
			}
		}

		/**
		 * Load CSS and JS files at admin side on loginpress-settings page only.
		 *
		 * @param string $hook the Page ID.
		 * @since 1.0.0
		 * @version 3.0.0
		 *
		 * @return void
		 */
		public function admin_scripts( $hook ) {

			if ( 'toplevel_page_loginpress-settings' !== $hook ) {
				return;
			}

			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-autocomplete' );

			wp_enqueue_style( 'loginpress_autologin_stlye', plugins_url( 'assets/css/style.css', __FILE__ ), array(), LOGINPRESS_PRO_VERSION );
			wp_enqueue_style( 'loginpress_datatables_style', LOGINPRESS_PRO_DIR_URL . 'assets/css/jquery.dataTables.min.css', array(), LOGINPRESS_PRO_VERSION );
			wp_enqueue_script( 'loginpress_datatables_js', LOGINPRESS_PRO_DIR_URL . 'assets/js/jquery.dataTables.min.js', array( 'jquery' ), LOGINPRESS_PRO_VERSION, false );

			wp_enqueue_style( 'loginpress_data_tables_responsive_autologin', LOGINPRESS_PRO_DIR_URL . 'assets/css/rowReorder.dataTables.min.css', array(), LOGINPRESS_PRO_VERSION );
			wp_enqueue_style( 'loginpress_data_tables_fixedColumns_order', LOGINPRESS_PRO_DIR_URL . 'assets/css/fixedColumns.dataTables.min.css', array(), LOGINPRESS_PRO_VERSION );
			wp_enqueue_script( 'loginpress_data_tables_responsive_autologin_row', LOGINPRESS_PRO_DIR_URL . 'assets/js/dataTables.rowReorder.min.js', array( 'jquery' ), LOGINPRESS_PRO_VERSION, false );
			wp_enqueue_script( 'loginpress_data_tables_js_fixedColumns', LOGINPRESS_PRO_DIR_URL . 'assets/js/dataTables.fixedColumns.min.js', array( 'jquery' ), LOGINPRESS_PRO_VERSION, false );

			wp_enqueue_script( 'loginpress_autologin_js', plugins_url( 'assets/js/autologin.js', __FILE__ ), array( 'jquery', 'loginpress_datatables_js' ), LOGINPRESS_PRO_VERSION, false );

			wp_localize_script(
				'loginpress_autologin_js',
				'loginpress_autologin',
				array(
					'loginpress_autologin_nonce' => wp_create_nonce( 'loginpress-user-autologin-nonce' ),
					'loginpress_autologin_popup' => wp_create_nonce( 'loginpress-autologin-popup-nonce' ),
					'translate'                  => array(
						_x( 'Enabled', 'Enable from the burger box selection of Autologin', 'loginpress-pro' ),
						_x( 'Disabled', 'Disabled from the burger box selection Autologin', 'loginpress-pro' ),
						_x( 'Lifetime', 'Lifetime from the burger box selection Autologin', 'loginpress-pro' ),
						_x( 'New Link Created', 'New Link Created LoginPress Autologin', 'loginpress-pro' ),
						_x( 'Day', 'Remaining time partial string in Autologin', 'loginpress-pro' ),
						_x( 'Days', 'Remaining time partial string in Autologin', 'loginpress-pro' ),
						_x( 'Left', 'Remaining time partial string in Autologin', 'loginpress-pro' ),
						_x( 'Last', 'Remaining time partial string in Autologin', 'loginpress-pro' ),
						/* Translators: %1$s The paragraph tag for the autologin search bar. */
						sprintf( _x( '%1$sSearch user\'s data from below the list%2$s', 'Search Label on Data tables', 'loginpress-pro' ), '<p class="description">', '</p>' ),
						_x( 'Enter keyword', 'The search keyword for the autologin users.', 'loginpress-pro' ),

					),
				)
			);
		}

		/**
		 * Adding a tab for AutoLogin at LoginPress Settings Page.
		 *
		 * @param  array $loginpress_tabs Rest of the settings tabs of LoginPress.
		 * @return array $loginpress_pro_templates AutoLogin tab.
		 * @since  1.0.0
		 * @version 3.0.0
		 */
		public function loginpress_autologin_tab( $loginpress_tabs ) {

			$autologin_tab = array(
				array(
					'id'         => 'loginpress_autologin',
					'title'      => __( 'Auto Login', 'loginpress-pro' ),
					'sub-title'  => __( 'No More Manual Login', 'loginpress' ),
					/* Translators: %1$s The line break tag. */
					'desc'       => sprintf( __( '%1$sThe Auto Login add-on for LoginPress allows administrators to generate unique URLs for specific users who do not need to enter a password to access the site.%2$s', 'loginpress-pro' ), '<p>', '</p>' ),
					'video_link' => 'M2M3G2TB9Dk',
				),
			);

			$loginpress_pro_templates = array_merge( $loginpress_tabs, $autologin_tab );

			return $loginpress_pro_templates;
		}

		/**
		 * Array of the Setting Fields for AutoLogin.
		 *
		 * @param array $setting_array Settings fields of free version.
		 *
		 * @since  1.0.0
		 * @return array AutoLogin settings fields
		 */
		public function loginpress_autologin_settings_array( $setting_array ) {

			$_autologin_settings = array(
				array(
					'name'  => 'loginpress_autologin',
					'label' => __( 'Search Username', 'loginpress-pro' ),
					'desc'  => __( 'Username for making a login magic link for a specific user.', 'loginpress-pro' ),
					'type'  => 'autologin',
				),
			);
			$_autologin_settings = array(
				'loginpress_autologin' => $_autologin_settings,
			);

			return( array_merge( $_autologin_settings, $setting_array ) );
		}

		/**
		 * A callback function that will show a search field under AutoLogin tab.
		 *
		 * @param array $args The functions arguments.
		 *
		 * @since   1.0.0
		 * @version 3.0.0
		 *
		 * @return string $html
		 */
		public function loginpress_autologin_callback( $args ) {

			$html = '<input type="text" name="loginpress_autologin_search" id="loginpress_autologin_search" value="" placeholder="' . esc_html__( ' Type Username...', 'loginpress-pro' ) . '" />';

			return $html;
		}

		/**
		 * A callback function that will show search result under the search field.
		 *
		 * @since   1.0.0
		 * @version 3.0.0
		 *
		 * @return void
		 */
		public function autologin_script_html() {
			/**
			 * Check to apply the script only on the LoginPress Settings page.
			 *
			 * @since 1.0.9
			 */
			if ( isset( $_GET['page'] ) && sanitize_text_field( wp_unslash( $_GET['page'] ) ) !== 'loginpress-settings' ) {
				return;
			}
			$html = apply_filters( 'loginpress_auto_login_after_description', '' );

			$loginpress_popup = '<div class="loginpress-edit-popup-container" style="display: none;" data-for="NULL"></div>';

			$html .= $loginpress_popup . '<div class="row-per-page"><span>' . __( 'Show Entries', 'loginpress-pro' ) . '</span> <select id="loginpress_autologin_users_select" class="selectbox"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select></div>
			<table id="loginpress_autologin_users" class="loginpress_autologin_users">
			<thead><tr>
			<th class="loginpress_user_id">' . esc_html__( 'User ID', 'loginpress-pro' ) . '</th>
			<th class="loginpress_log_userName">' . esc_html__( 'Username', 'loginpress-pro' ) . '</th>
			<th class="loginpress_log_email">' . esc_html__( 'Email', 'loginpress-pro' ) . '</th>
			<th class="loginpress_log_url">' . esc_html__( 'Auto login URL', 'loginpress-pro' ) . '</th>
			<th class="loginpress_log_status">' . esc_html__( 'Status', 'loginpress-pro' ) . '</th>
			<th class="loginpress_action">' . esc_html__( 'Action', 'loginpress-pro' ) . '</th>
			</tr></thead>';
			$html .= '<tfoot><tr>
			<th class="loginpress_user_id">' . esc_html__( 'User ID', 'loginpress-pro' ) . '</th>
			<th class="loginpress_log_userName">' . esc_html__( 'Username', 'loginpress-pro' ) . '</th>
			<th class="loginpress_log_email">' . esc_html__( 'Email', 'loginpress-pro' ) . '</th>
			<th class="loginpress_log_url">' . esc_html__( 'Auto login URL', 'loginpress-pro' ) . '</th>
			<th class="loginpress_log_status">' . esc_html__( 'Status', 'loginpress-pro' ) . '</th>
			<th class="loginpress_action">' . esc_html__( 'Action', 'loginpress-pro' ) . '</th>
			</tr></tfoot><tbody><span class="autologin-sniper"></span>';

			$user_query = new WP_User_Query(
				array(
					'meta_key' => 'loginpress_autologin_user',  // @codingStandardsIgnoreLine.
				)
			);

			// get_results w.r.t 'meta_key' => 'loginpress_autologin_code'.
			$autologin_user = $user_query->get_results();
			// Check for results.
			if ( ! empty( $autologin_user ) ) {
				// loop through each user.
				foreach ( $autologin_user as $user ) {
					// get all the user's data.
					$user_info          = get_userdata( $user->ID ); // Get User Information.
					$user_meta          = get_user_meta( $user->ID, 'loginpress_autologin_user', true ); // get autologin user meta.
					$link_state         = 'enable' === $user_meta['state'] ? 'disable' : 'enable'; // Get User Link state.
					$is_disabled        = 'enable' === $user_meta['state'] ? '' : 'autologin-disabled'; // Is link disbaled?.
					$disable_field      = 'enable' === $user_meta['state'] ? '' : 'disabled'; // Disable Field.
					$expired            = ''; // Is expired .
					$not_approved       = ''; // Is not approved.
					$disabled           = $link_state; // Link state.
					$expired_class      = ''; // Expired Class.
					$is_expire          = ( 'unchecked' === $user_meta['expire'] ) ? '' : 'checked'; // Lifetime or not.
					$cog_disabled       = 'enable' === $link_state ? 'disabled' : ''; // State Disabling.
					$user_meta_status   = get_user_meta( $user->ID, 'loginpress_user_verification', true ); // Get user verification.
					$loginpress_options = get_option( 'loginpress_setting' ); // LoginPress settings.
					$is_expired         = ''; // Has expired.

					if ( empty( $is_expire ) ) {
						$expiration     = ( ! empty( $user_meta['duration'] ) ) ? $user_meta['duration'] : gmdate( 'Y-m-d' );
						$now            = time(); // or your date as well.
						$your_date      = strtotime( $expiration );
						$date_diff      = $your_date - $now;
						$remaining_days = intval( ceil( $date_diff / ( 60 * 60 * 24 ) ) );

						if ( isset( $loginpress_options['enable_user_verification'] ) && 'on' === $loginpress_options['enable_user_verification'] && 'inactive' === $user_meta_status ) {
							$expired       = '<span class="loginpress-autologin-remain-notice loginpress-autologin-remain-notice-red">' . __( 'User Not Approved', 'loginpress-pro' ) . '</span>';
							$not_approved  = 'disabled';
							$expired_class = 'autologin-expired';
							$is_disabled   = 'disabled';
							$disable_field = 'disabled';

						} elseif ( gmdate( 'Y-m-d' ) <= $expiration && ( isset( $user_meta['state'] ) && 'enable' === $user_meta['state'] ) ) {
							/* translators: Days Left. */
							$remain  = 1 === $remaining_days ? sprintf( __( '%1$s Day Left', 'loginpress-pro' ), $remaining_days ) : sprintf( __( '%1$s Days Left', 'loginpress-pro' ), $remaining_days );
							$expired = '<span class="loginpress-autologin-remain-notice">' . $remain . '</span>';
							// If last day is left, gmdate() puts -0 for the last day.
							if ( -0 === $remaining_days ) {

								$expired = '<span class="loginpress-autologin-remain-notice loginpress-autologin-remain-notice-last-day">' . __( 'Last Day', 'loginpress-pro' ) . '</span>';
							}
						} elseif ( 'enable' !== $user_meta['state'] && ( '' === $user_meta_status || 'active' === $user_meta_status ) ) { // If Link is not disabled and user is verified.
							$expired = '<span class="loginpress-autologin-remain-notice loginpress-autologin-remain-notice-red">' . __( 'Disabled', 'loginpress-pro' ) . '</span>';

						} elseif ( gmdate( 'Y-m-d' ) > $expiration ) {
							$expired       = '<span class="loginpress-autologin-remain-notice-red">' . __( 'Link Expired', 'loginpress-pro' ) . '</span>';
							$expired_class = 'autologin-expired';
							$is_expired    = 'disabled';
						}
					} elseif ( 'enable' !== $user_meta['state'] && ( '' === $user_meta_status || 'active' === $user_meta_status ) ) { // If Link is not disabled and user is verified.
							$expired = '<span class="loginpress-autologin-remain-notice loginpress-autologin-remain-notice-red">' . __( 'Disabled', 'loginpress-pro' ) . '</span>';

					} elseif ( 'inactive' !== $user_meta_status ) {
						$expired = '<span class="loginpress-autologin-remain-notice">' . __( 'Lifetime', 'loginpress-pro' ) . '</span>';

					} elseif ( isset( $loginpress_options['enable_user_verification'] ) && 'on' === $loginpress_options['enable_user_verification'] && 'inactive' === $user_meta_status ) {
						$expired       = '<span class="loginpress-autologin-remain-notice loginpress-autologin-remain-notice-red">' . __( 'User Not Approved', 'loginpress-pro' ) . '</span>';
						$not_approved  = 'disabled';
						$expired_class = 'autologin-expired';
						$is_disabled   = 'disabled';
						$disable_field = 'disabled';

					}
					$html .= '<tr class="' . $expired_class . ' ' . $is_disabled . '" id="loginpress_user_id_' . $user->ID . '" data-autologin="' . $user->ID . '"><td><div class="lp-tbody-cell">' . $user_info->ID . '</div></td><td class="loginpress_user_name"><div class="lp-tbody-cell">' . $user_info->user_login . '</div></td><td class="loginpress_user_email"><div class="lp-tbody-cell">' . $user_info->user_email . '</div></td><td class="loginpress_autologin_code"><div class="lp-tbody-cell"><span class="autologin-sniper"><img src="' . esc_url( LOGINPRESS_DIR_URL . 'img/loginpress-sniper.gif' ) . '" /></span>
					<input type="text" class="loginpress-autologin-code" dir="rtl" value="' . home_url() . '/?loginpress_code=' . $user_meta['code'] . '" readonly>					
					<div class="copy-email-icon-wrapper autologin-copy-code">
						<svg class="autologin-copy-svg" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
						<g clip-path="url(#clip0_27_294)">
						<path d="M1.62913 0H13.79C14.6567 0 15.3617 0.7051 15.3617 1.57176V5.26077H13.9269V1.57176C13.9269 1.49624 13.8655 1.43478 13.79 1.43478H1.62913C1.55361 1.43478 1.49216 1.49624 1.49216 1.57176V13.7326C1.49216 13.8082 1.55361 13.8696 1.62913 13.8696H5.20313V15.3044H1.62913C0.762474 15.3044 0.057373 14.5993 0.057373 13.7326V1.57176C0.0574209 0.7051 0.762474 0 1.62913 0Z" fill="#869AC1"/>
						<path d="M8.20978 6.69557H20.3706C21.2373 6.69557 21.9424 7.40067 21.9424 8.26737V20.4282C21.9423 21.2949 21.2373 22 20.3706 22H8.20973C7.34303 22 6.63793 21.2949 6.63793 20.4283V8.26737C6.63788 7.40067 7.34308 6.69557 8.20978 6.69557ZM8.20969 20.5652H20.3706C20.4461 20.5652 20.5076 20.5038 20.5076 20.4283V8.26737C20.5076 8.19181 20.4461 8.13035 20.3706 8.13035H8.20973C8.13417 8.13035 8.07271 8.19181 8.07271 8.26737V20.4283C8.07271 20.5038 8.13417 20.5652 8.20969 20.5652Z" fill="#869AC1"/>
						</g>
						<defs>
						<clipPath id="clip0_27_294">
						<rect width="22" height="22" fill="white" transform="matrix(-1 0 0 1 22 0)"/>
						</clipPath>
						</defs>
						</svg>
					</div>
					<span class="loginpress-autologin-email-sent" >Email Sent </span>
					<span class"loginpress-autologin-remain-notice" data-attr="data-dayleft">' . $expired . ' </span></div></td>
					<td class="loginpress_user_status" data-attr="data-dayleft"><div class="lp-tbody-cell">' . $expired . '</div></td>
					<td class="loginpress_autologin_actions"><div class="lp-tbody-cell">
					<button type="button" class="button loginpress-del-link" value="' . esc_html__( 'Delete', 'loginpress-pro' ) . '" id="loginpress_delete_link" /></button>
					<div class="loginpress-action-list-menu-wrapper">
					<div class="loginpress-action-menu-burger-wrapper"><span class="loginpress-action-menu-burger-open-icon dashicons dashicons-menu-alt2"></span>
					<span class="loginpress-action-menu-burger-close-icon dashicons dashicons-no-alt"></span></div>
					<ul class="action-menu-list">
					<li><input type="button" class="button loginpress-new-link" value="' . esc_html__( 'New Link', 'loginpress-pro' ) . '" id="loginpress_create_new_link" ' . $disable_field . '/></li>
					<li><input type="button" class="button loginpress-autologin-duration" value="' . esc_html__( 'Link Duration', 'loginpress-pro' ) . '" ' . $disable_field . '/></li>
					<li><input type="button" class="button loginpress-autologin-state ' . $link_state . '" data-state="' . $link_state . '" value="' . esc_html( $disabled ) . '"' . $not_approved . ' ' . $is_expired . ' /></li>
					<li><input type="button" class="loginpress-autologin-email-settings" value="' . esc_html__( 'Email To Multiple', 'loginpress-pro' ) . '" ' . $cog_disabled . ' ' . $is_expired . ' ' . $disable_field . ' "></span></li>
					<li><input type="button" class="button loginpress-autologin-email" value="' . esc_html__( 'Email User', 'loginpress-pro' ) . '" ' . $disable_field . ' ' . $is_expired . ' /></li>
					</ul> 
					</div> 
					</div></td></tr>';
				}
			}

			$html .= '</tbody></table>';

			echo $html; // @codingStandardsIgnoreLine.
		}


		/**
		 * Get the users list and Saved it in footer that will use for autocomplete in search.
		 *
		 * @since 1.0.0
		 * @version 3.0.0
		 */
		public function loginpress_autologin_autocomplete_js() {

			/**
			 * Check to apply the script only on the LoginPress Settings page.
			 *
			 * @since 1.0.9
			 */
			$current_screen = get_current_screen();
			if ( isset( $current_screen->base ) && ( 'toplevel_page_loginpress-settings' !== $current_screen->base ) ) {
				return;
			}
			$loginpress_setting = get_option( 'loginpress_setting' );
			if ( isset( $loginpress_setting['enable_user_verification'] ) && 'on' === $loginpress_setting['enable_user_verification'] ) {
				$args  = array(
					'meta_query' => array(  // @codingStandardsIgnoreLine.
						'relation' => 'OR',
						array(
							'key'     => 'loginpress_user_verification',
							'compare' => 'NOT EXISTS',
						),
						array(
							'key'     => 'loginpress_user_verification',
							'value'   => 'inactive',
							'compare' => '!=',
						),
					),
				);
				$users = get_users( $args );
			} else {
				$users = get_users(
					array(
						'fields' => array(
							'ID',
							'user_login',
							'user_email',
						),
					),
				);
			}

			if ( $users ) :

				?>
				<script type="text/javascript">
					var autologin_table;

					jQuery(document).ready(function($){
						$('#loginpress_autologin .form-table input[type="text"]').on('keydown', function (evt) {
							if (evt.keyCode == 13) {
								evt.preventDefault();
							}
						});
						// Generate random string.
						function loginpress_create_new_link() {
							var autoLoginString = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

							var result = "";
							while ( result.length < 30 ) {
								result += autoLoginString.charAt( Math.floor( Math.random() * autoLoginString.length ) );
							}

							return result;
						}
						function loginpress_expiration() {
							<?php
							$date           = gmdate( 'Y-m-d' );
							$default_expire = gmdate( 'Y-m-d', strtotime( "$date +7 day" ) ); // PHP Date style:  yy-mm-dd.
							$expire         = apply_filters( 'loginpress_autologin_default_expiration', $default_expire );
							$now            = time(); // or your date as well.
							$your_date      = strtotime( $expire );
							$date_diff      = $your_date - $now;
							$remaining_days = round( $date_diff / ( 60 * 60 * 24 ) );
							?>
							var result      = <?php echo intval( $remaining_days ); ?>;
							return result;
						}

						var posts = <?php echo wp_json_encode( array_values( $users ) ); ?>;
						posts.map(entry => {
							entry.label = entry.user_login;
							delete entry.user_login;
						});
						function loginpressAutocompleteData(request, response) {
							const term = request.term.toLowerCase();
							const filteredEntries = posts.filter(entry => entry.label.toLowerCase().includes(term));
							const pageSize = 20;
							const start = window.pageIndex * pageSize;
							const end = start + pageSize;
							response(filteredEntries.slice(start, end));
						}
						if ( $( 'input[name="loginpress_autologin_search"]' ).length > 0 ) {
							var _nonce = loginpress_autologin.loginpress_autologin_nonce;
							$( 'input[name="loginpress_autologin_search"]' ).autocomplete({
								source: loginpressAutocompleteData,
								minLength: 1,
								search: function (event,ui) {
									window.pageIndex = 0;
								},
								select: function(event, ui) {
									var id = ui.item.ID;
									var code = loginpress_create_new_link();
									var expiration = loginpress_expiration();
									if ( $( '#loginpress_user_id_' + id ).length == 0 ) {
										$.ajax({
											url: ajaxurl,
											type: 'POST',
											data: 'code=' + code + '&id=' + id + '&action=loginpress_autologin' + '&security=' + _nonce,
											success: function( response ) {
												$('#loginpress_autologin_users .dataTables_empty').hide();
												var get_html = $('<tr id="loginpress_user_id_'+id+'" data-autologin="'+id+'"><td><div class="lp-tbody-cell">'+id+'</div></td><td class="loginpress_user_name"><div class="lp-tbody-cell">'+ui.item.label+'</div></td><td><div class="lp-tbody-cell">'+ui.item.user_email+'</div></td><td class="loginpress_autologin_code"><div class="lp-tbody-cell"><span class="autologin-sniper"><img src="<?php echo esc_url( LOGINPRESS_DIR_URL . 'img/loginpress-sniper.gif' ); ?>" /></span> <input type="text" class="loginpress-autologin-code" dir="rtl" value="' + response + '"><div class="copy-email-icon-wrapper autologin-copy-code"><svg class="autologin-copy-svg" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_27_294)"><path d="M1.62913 0H13.79C14.6567 0 15.3617 0.7051 15.3617 1.57176V5.26077H13.9269V1.57176C13.9269 1.49624 13.8655 1.43478 13.79 1.43478H1.62913C1.55361 1.43478 1.49216 1.49624 1.49216 1.57176V13.7326C1.49216 13.8082 1.55361 13.8696 1.62913 13.8696H5.20313V15.3044H1.62913C0.762474 15.3044 0.057373 14.5993 0.057373 13.7326V1.57176C0.0574209 0.7051 0.762474 0 1.62913 0Z" fill="#869AC1"/><path d="M8.20978 6.69557H20.3706C21.2373 6.69557 21.9424 7.40067 21.9424 8.26737V20.4282C21.9423 21.2949 21.2373 22 20.3706 22H8.20973C7.34303 22 6.63793 21.2949 6.63793 20.4283V8.26737C6.63788 7.40067 7.34308 6.69557 8.20978 6.69557ZM8.20969 20.5652H20.3706C20.4461 20.5652 20.5076 20.5038 20.5076 20.4283V8.26737C20.5076 8.19181 20.4461 8.13035 20.3706 8.13035H8.20973C8.13417 8.13035 8.07271 8.19181 8.07271 8.26737V20.4283C8.07271 20.5038 8.13417 20.5652 8.20969 20.5652Z" fill="#869AC1"/></g><defs><clipPath id="clip0_27_294"><rect width="22" height="22" fill="white" transform="matrix(-1 0 0 1 22 0)"/></clipPath></defs></svg></div><span class="loginpress-autologin-email-sent">Email Sent</span><span class="loginpress-autologin-create-notice" data-attr="data-dayleft"> '+ expiration +'<?php esc_html_e( ' Days Left', 'loginpress-pro' ); ?> </span></div></td><td class="loginpress_user_status" data-attr="data-dayleft"><div class="lp-tbody-cell"><span class="loginpress-autologin-remain-notice"> '+ expiration +'<?php esc_html_e( ' Days Left', 'loginpress-pro' ); ?></span></div></td><td class="loginpress_autologin_actions"><div class="lp-tbody-cell"><button type="button" class="button loginpress-del-link" value=" <?php esc_html_e( 'Delete', 'loginpress-pro' ); ?>" id="loginpress_delete_link" /></button><div class="loginpress-action-list-menu-wrapper"><div class="loginpress-action-menu-burger-wrapper"><span class="loginpress-action-menu-burger-open-icon dashicons dashicons-menu-alt2"></span><span class="loginpress-action-menu-burger-close-icon dashicons dashicons-no-alt"></span></div><ul class="action-menu-list"><li><input type="button" class="button loginpress-new-link" value="<?php esc_html_e( 'New Link', 'loginpress-pro' ); ?>" id="loginpress_create_new_link"/></li><li><input type="button" class="button loginpress-autologin-duration" value="<?php esc_html_e( 'Link Duration', 'loginpress-pro' ); ?>" /> </li><li><input type="button" class="button loginpress-autologin-state disable" data-state="disable" value="disable" /> </li><li><input type="button" value=" <?php esc_html_e( 'Email To Multiple', 'loginpress-pro' ); ?>"class="loginpress-autologin-email-settings"> <li><input type="button" class="button loginpress-autologin-email" value=" <?php esc_html_e( 'Email User', 'loginpress-pro' ); ?>"></li></span></ul> </div> <div></td></tr>');

												if ( $('#loginpress_user_id_' + id + '').length == 0 ) {
													// $('#loginpress_autologin_users').append( get_html );
													autologin_table.row.add(get_html[0]).draw();
													let uid = parseInt(id);
													$('#loginpress_user_id_' + id + '').find('td:first-child').addClass('dtfc-fixed-left');
													$('#loginpress_user_id_' + id + '').find('td:last-child').addClass('dtfc-fixed-right');
													$(document).on('click','.loginpress-action-menu-burger-wrapper', function ( uid ) {
														uid.stopPropagation();
														$('#loginpress_autologin_users').attr('data-open','parent-'+ $(this).parent().toggleClass("menu-active").closest('tr').nextAll().length);
														$(this).parent().toggleClass("menu-active").closest('tr').siblings().find('.loginpress-action-list-menu-wrapper').removeClass('menu-active');
														$(this).closest('.loginpress_autologin_actions ').toggleClass("sticky-active").closest('tr').siblings().find('.loginpress_autologin_actions ').removeClass('sticky-active');
														// 
														
														$(this).closest('tr').addClass("list-active").siblings().removeClass('list-active');
													});
												}
											}  // !success.
										}); // !ajax.
									} else {
										$( '.toplevel_page_loginpress-settings .dataTable tbody #loginpress_user_id_' + id ).addClass('loginpress_user_highlighted');
										setTimeout( function() {
											$( '#loginpress_user_id_' + id ).removeClass('loginpress_user_highlighted');
										}, 3000 );
									}
								} // !select.
							});
						}
					});
					</script>
				<?php
			endif;
		}

		/**
		 * Ajax function that update the user meta after creating autologin code
		 *
		 * @since   1.0.0
		 * @version 3.0.0
		 */
		public function autologin_update_user_meta() {

			check_ajax_referer( 'loginpress-user-autologin-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}
			if ( ! isset( $_POST['code'] ) || ! isset( $_POST['id'] ) ) {
				return;
			} else {
				$loginpress_code = sanitize_text_field( wp_unslash( $_POST['code'] ) );
				$user_id         = sanitize_text_field( intval( $_POST['id'] ) );
			}
			$date           = gmdate( 'Y-m-d' );
			$default_date   = gmdate( 'Y-m-d', strtotime( "$date +7 day" ) ); // PHP:  yy-mm-dd.
			$default_expire = apply_filters( 'loginpress_autologin_default_expiration', $default_date );
			$meta           = get_user_meta( $user_id, 'loginpress_autologin_user', true );
			$emails         = isset( $meta['emails'] ) && ! empty( $meta['emails'] ) ? $meta['emails'] : '';
			$expire         = isset( $meta['expire'] ) && ! empty( $meta['expire'] ) ? $meta['expire'] : 'unchecked';

			$update_elements = array(
				'state'    => sanitize_text_field( 'enable' ),
				'emails'   => sanitize_text_field( $emails ),
				'code'     => sanitize_text_field( $loginpress_code ),
				'expire'   => sanitize_text_field( $expire ),
				'duration' => sanitize_text_field( $default_expire ),
			);

			update_user_meta( $user_id, 'loginpress_autologin_user', $update_elements );
			$meta = get_user_meta( $user_id, 'loginpress_autologin_user', true );
			echo esc_url( home_url() . '/?loginpress_code=' . $meta['code'] );
			wp_die();
		}

		/**
		 * Ajax function that emails user for autologin.
		 *
		 * @since   1.0.0
		 * @version 3.0.0
		 */
		public function loginpress_autologin_emailuser() {

			check_ajax_referer( 'loginpress-user-autologin-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}

			$user_id = isset( $_POST['id'] ) ? esc_html( intval( $_POST['id'] ) ) : '';
			if ( empty( $user_id ) && ( ! isset( $_POST['code'] ) || empty( $_POST['code'] ) ) ) {
				return;
			}

			$user   = get_userdata( $user_id );
			$meta   = get_user_meta( $user_id, 'loginpress_autologin_user', true );
			$emails = $user->user_email;
			$code   = home_url() . '/?loginpress_code=' . $meta['code'];

			$blog_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			$user_name = isset( $user->first_name ) && ! empty( $user->first_name ) ? $user->first_name : $user->display_name;

			$allowed_html = array(
				'a'      => array(
					'href'  => array(),
					'title' => array(),
				),
				'br'     => array(),
				'em'     => array(),
				'strong' => array(),
				'i'      => array(),
			);

			/* Translators: The Autologin multi-user email */
			$message  = esc_html__( 'Following is the Auto Login link details for the Blog ', 'loginpress-pro' );
			$message .= ucwords( $blog_name ) . "\n\n";
			$message .= esc_html__( 'User Name: ', 'loginpress-pro' ) . ucwords( $user_name ) . "\n\n";
			$message .= esc_html__( 'User Role: ', 'loginpress-pro' ) . ucwords( implode( ', ', $user->roles ) ) . "\n\n";
			$message .= esc_html__( 'Autologin Link: ', 'loginpress-pro' );
			$message .= $code;
			$mail     = wp_mail(
				$emails,
				/* translators: Blog name. */
				esc_html( apply_filters( 'loginpress_autologin_email_subject', sprintf( esc_html_x( '[%s] Auto Login Link', 'Blogname', 'loginpress-pro' ), $blog_name ) ) ),
				wp_kses( apply_filters( 'loginpress_autologin_email_msg', $message, $blog_name, $user_name, $code ), $allowed_html )
			);

			wp_die();
		}

		/**
		 * [function that emails to multiple users for autologin].
		 *
		 * @param int $user_id The ID of the user.
		 * @since   1.0.0
		 * @version 3.0.0
		 *
		 * @return void
		 */
		public function loginpress_autologin_multiusers_email( $user_id ) {
			// User will have to login in order to perform this task and should have the "manage_options" rights as well.
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}

			$user   = get_userdata( $user_id );
			$meta   = get_user_meta( $user_id, 'loginpress_autologin_user', true );
			$emails = $user->user_email;

			if ( ! empty( $meta['emails'] ) ) {
				$emails   = explode( ',', $meta['emails'] );
				$emails[] = $user->user_email;
			}

			$meta = get_user_meta( $user_id, 'loginpress_autologin_user', true );
			$code = home_url() . '/?loginpress_code=' . $meta['code'];

			$blog_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			$user_name = isset( $user->first_name ) && ! empty( $user->first_name ) ? $user->first_name : $user->display_name;

			$allowed_html = array(
				'a'      => array(
					'href'  => array(),
					'title' => array(),
				),
				'br'     => array(),
				'em'     => array(),
				'strong' => array(),
				'i'      => array(),
			);

			/* Translators: The Autologin multi-user email */
			$message  = esc_html__( 'Following is the Auto Login link details for the Blog ', 'loginpress-pro' );
			$message .= ucwords( $blog_name ) . "\n\n";
			$message .= esc_html__( 'User Name: ', 'loginpress-pro' ) . ucwords( $user_name ) . "\n\n";
			$message .= esc_html__( 'User Role: ', 'loginpress-pro' ) . ucwords( implode( ', ', $user->roles ) ) . "\n\n";
			$message .= esc_html__( 'Autologin Link: ', 'loginpress-pro' );
			$message .= $code;
			$mail     = wp_mail(
				$emails,
				/* translators: Blog name. */
				esc_html( apply_filters( 'loginpress_autologin_email_subject', sprintf( esc_html_x( '[%s] Auto Login Link', 'Blogname', 'loginpress-pro' ), $blog_name ) ) ),
				wp_kses( apply_filters( 'loginpress_autologin_email_msg', $message, $blog_name, $user_name, $code ), $allowed_html )
			);

			wp_die();
		}

		/**
		 * Ajax function that delete the user meta after click on delete user autologin button
		 *
		 * @since   1.0.0
		 * @version 3.0.0
		 */
		public function autologin_delete_user_meta() {

			check_ajax_referer( 'loginpress-user-autologin-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}
			if ( ! isset( $_POST['id'] ) ) {
				return;
			}
			$user_id = esc_html( intval( $_POST['id'] ) );
			delete_user_meta( $user_id, 'loginpress_autologin_user' );

			echo esc_html__( 'deleted', 'loginpress-pro' );
		}

		/**
		 * Change the State of the autologin
		 *
		 * @version 3.0.0
		 * @return void
		 */
		public function loginpress_change_autologin_state() {

			check_ajax_referer( 'loginpress-user-autologin-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}
			if ( ! isset( $_POST['state'] ) || ! isset( $_POST['id'] ) ) {
				return;
			}

			$user_id = esc_html( intval( $_POST['id'] ) );
			$state   = esc_attr( filter_var( wp_unslash( $_POST['state'] ), FILTER_SANITIZE_STRING ) );
			$meta    = get_user_meta( $user_id, 'loginpress_autologin_user', true );

			if ( 'disable' === $state ) {
				update_user_meta(
					$user_id,
					'loginpress_autologin_user',
					array_merge(
						$meta,
						array(
							'state' => 'disable',
						)
					)
				);

			} else {
				update_user_meta(
					$user_id,
					'loginpress_autologin_user',
					array_merge(
						$meta,
						array(
							'state' => 'enable',
						)
					)
				);

			}

			wp_die();
		}

		/**
		 * Ajax callback populate popup data for expire duration.
		 *
		 * @since   1.0.0
		 * @version 3.0.0
		 */
		public function loginpress_populate_popup_duration() {

			check_ajax_referer( 'loginpress-user-autologin-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}
			if ( ! isset( $_POST['id'] ) ) {
				return;
			}
			$user_id    = esc_html( intval( $_POST['id'] ) );
			$meta       = get_user_meta( $user_id, 'loginpress_autologin_user', true );
			$is_expire  = isset( $meta['expire'] ) && ( 'unchecked' === $meta['expire'] ) ? '' : 'checked';
			$expiration = isset( $meta['duration'] ) && ( ! empty( $meta['duration'] ) ) ? sanitize_text_field( $meta['duration'] ) : gmdate( 'Y-m-d' );

			$display_date = empty( $is_expire ) ? 'block' : 'none';

			$return = '
			<div class="loginpress-edit-popup loginpress-link-duration-popup">
				<div class="autologin-popup-fields autologin-expire-date-container" style="display:' . $display_date . '">
					<label for="autologin-expire-date">' . esc_html__( 'Expiration Date', 'loginpress-pro' ) . '</label>
					<p class="autologin-expire-date_desc">' . esc_html__( 'This Auto Login link will expire on ', 'loginpress-pro' ) . $expiration . '</p>
					<input type="date" id="autologin-expire-date" class="autologin-expire-date" min="' . gmdate( 'Y-m-d' ) . '" value="' . $expiration . '" max="2050-01-01" />
				</div>
				<div class="autologin-popup-fields">
					<label for="autologin-never-expire">' . esc_html__( 'Never Expire', 'loginpress-pro' ) . '</label>
					<input id="autologin-never-expire" class="autologin-never-expire" type="checkbox" value="void" ' . $is_expire . ' />
				</div>
				<div class="loginpress-auto-login-duration-buttons">
					<button class="button button-primary autologin-close-popup">' . esc_html__( 'Close', 'loginpress-pro' ) . '</button>
					<button class="button button-primary autologin-save-duration">' . esc_html__( 'Done', 'loginpress-pro' ) . '</button>
				</div>
			</div>';

			echo $return; // @codingStandardsIgnoreLine.
			wp_die();
		}

		/**
		 * Ajax callbakc populate popup data for expire duration.
		 *
		 * @since   1.0.0
		 * @version 3.0.0
		 */
		public function loginpress_populate_popup_email() {

			check_ajax_referer( 'loginpress-user-autologin-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}
			if ( ! isset( $_POST['id'] ) ) {
				return;
			}
			$user_id = esc_html( intval( $_POST['id'] ) );
			$meta    = get_user_meta( $user_id, 'loginpress_autologin_user', true );

			$return = '
			<div class="loginpress-edit-popup loginpress-multiple-users-popup">
				<div class="autologin-popup-fields">
					<label for="autologin-emails">' . esc_html__( ' Email Addresses', 'loginpress-pro' ) . ' </label>
					<p class="autologin-multi-emails-desc">' . esc_html__( 'Send Auto Login To Multiple Users', 'loginpress-pro' ) . '</p>
					<input type="text" id="autologin-emails" value="' . $meta['emails'] . '" placeholder="' . esc_html__( 'Email Address with Commas', 'loginpress-pro' ) . '">
					<p class="autologin-multi-emails-desc">' . esc_html__( 'Use comma ( , ) to add more than 1 recipients.', 'loginpress-pro' ) . '</p>
					<p class="autologin_emails_sent">' . esc_html__( 'An Email Has Been Sent', 'loginpress-pro' ) . '</p>
					<p class="loginpress_valid_email">' . esc_html__( 'One of your email is not valid', 'loginpress-pro' ) . ' </p>
					<p class="loginpress_empty_email">' . esc_html__( 'Kindly Enter Recipient Email', 'loginpress-pro' ) . ' </p>
				</div>
				<div class="loginpress-auto-login-duration-buttons">
					<button class="button button-primary autologin-close-popup">' . esc_html__( 'Close', 'loginpress-pro' ) . '</button>
					<button class="button button-primary autologin-save-emails">' . esc_html__( 'Done', 'loginpress-pro' ) . '</button>
				</div>
			</div>';

			echo $return; // @codingStandardsIgnoreLine.
			wp_die();
		}

		/**
		 * Ajax callback update expire duration.
		 *
		 * @since   1.0.0
		 * @version 3.0.0
		 */
		public function loginpress_update_duration() {

			check_ajax_referer( 'loginpress-autologin-popup-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}
			if ( ! isset( $_POST['id'] ) || ! isset( $_POST['never_expire'] ) || ! isset( $_POST['expire_duration'] ) ) {
				return;
			}
			$user_id         = esc_html( intval( $_POST['id'] ) );
			$never_expire    = sanitize_text_field( wp_unslash( $_POST['never_expire'] ) );
			$expire_duration = sanitize_text_field( wp_unslash( $_POST['expire_duration'] ) );
			$meta            = get_user_meta( $user_id, 'loginpress_autologin_user', true );

			update_user_meta(
				$user_id,
				'loginpress_autologin_user',
				array_merge(
					$meta,
					array(
						'expire'   => $never_expire,
						'duration' => $expire_duration,
					)
				)
			);

			esc_html( $expire_duration );

			wp_die();
		}

		/**
		 * Ajax callback update user emails.
		 *
		 * @since   1.0.0
		 * @version 3.0.0
		 */
		public function loginpress_update_email() {

			check_ajax_referer( 'loginpress-autologin-popup-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}
			if ( ! empty( $_POST['id'] ) && ! empty( $_POST['emails'] ) ) {
				$user_id = intval( $_POST['id'] );
				$emails  = sanitize_text_field( wp_unslash( $_POST['emails'] ) );
				$meta    = get_user_meta( $user_id, 'loginpress_autologin_user', true );

				update_user_meta(
					$user_id,
					'loginpress_autologin_user',
					array_merge(
						$meta,
						array(
							'emails' => $emails,
						)
					)
				);

				$this->loginpress_autologin_multiusers_email( $user_id );
			}
			wp_die();
		}
	}
endif;

new LoginPress_AutoLogin();
