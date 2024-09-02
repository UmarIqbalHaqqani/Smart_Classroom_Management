<?php

if ( ! class_exists( 'LoginPress_Limit_Login_Attempts_Main' ) ) :

	/**
	 * Main Class.
	 */
	class LoginPress_Limit_Login_Attempts_Main {

		/**
		 * Variable for LoginPress Limit Login Attempts table name.
		 *
		 * @var string $llla_table Custom table name for Limit Login Attempts.
		 * @since 3.0.0
		 */
		protected $llla_table;

		/** * * * * * * * * *
		 *
		 * @since  3.0.0
		 * @access private
		 * @var    bool
		 * * * * * * * * * * */
		private $wp_login_php;

		/** * * * * * * * * * * * *
		 * Instance of this class.
		 *
		 * @since    3.0.0
		 * @var      object
		 * * * * * * * * * * * * * */
		protected static $llla_instance = null;

		/** * * * * * * * *
		 * Class constructor
		 * * * * * * * * * */
		public function __construct() {

			global $wpdb;
			$this->llla_table = $wpdb->prefix . 'loginpress_limit_login_details';
			$this->hooks();
			$this->includes();
		}

		/** * * * * * *
		 * Action hooks.
		 * * * * * * * */
		public function hooks() {

			// add_action( 'admin_init', array( $this, 'init_addon_updater' ), 0 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_filter( 'loginpress_settings_tab', array( $this, 'loginpress_limit_login_attempts_tab' ), 10, 1 );
			add_filter( 'loginpress_settings_fields', array( $this, 'loginpress_limit_login_attempts_settings_array' ), 10, 1 );
			add_action( 'loginpress_limit_login_attempts_log_script', array( $this, 'loginpress_limit_login_attempts_log_callback' ) );
			add_action( 'loginpress_limit_login_attempts_whitelist_script', array( $this, 'loginpress_limit_login_attempts_whitelist_callback' ) );
			add_action( 'loginpress_limit_login_attempts_blacklist_script', array( $this, 'loginpress_limit_login_attempts_blacklist_callback' ) );
		}

		/** * * * * * * * * * * * *
		 * LoginPress Addon updater
		 * * * * * * * * * * * * * */
		public function init_addon_updater() {

			if ( class_exists( 'LoginPress_AddOn_Updater' ) ) {

				$updater = new LoginPress_AddOn_Updater( 2328, LOGINPRESS_LIMIT_LOGIN_ROOT_FILE, $this->version );
			}
		}

		/** * * * * * *
		 * include files
		 *
		 * @since 3.0.0
		 * * * * * * * */
		public function includes() {

			include_once LOGINPRESS_LIMIT_LOGIN_DIR_PATH . 'classes/class-attempts.php';
			include_once LOGINPRESS_LIMIT_LOGIN_DIR_PATH . 'classes/class-ajax.php';

		}

		/**
		 * Load CSS and JS files at admin side on loginpress-settings page only.
		 *
		 * @param string $hook the Page ID.
		 * @return void
		 * @since  3.0.0
		 */
		public function admin_scripts( $hook ) {

			if ( 'toplevel_page_loginpress-settings' !== $hook ) {
				return;
			}

			wp_enqueue_style( 'loginpress_limit_login_stlye', LOGINPRESS_LIMIT_LOGIN_DIR_URL . 'assets/css/style.css', array(), LOGINPRESS_PRO_VERSION );
			wp_enqueue_style( 'loginpress_datatables_style', LOGINPRESS_PRO_DIR_URL . 'assets/css/jquery.dataTables.min.css', array(), LOGINPRESS_PRO_VERSION );
			wp_enqueue_script( 'loginpress_datatables_js', LOGINPRESS_PRO_DIR_URL . 'assets/js/jquery.dataTables.min.js', array( 'jquery' ), LOGINPRESS_PRO_VERSION, false );

			wp_enqueue_style( 'loginpress_data_tables_responsive', LOGINPRESS_PRO_DIR_URL . 'assets/css/rowReorder.dataTables.min.css', array(), LOGINPRESS_PRO_VERSION );

			wp_enqueue_script( 'loginpress_datatables_responsive_row', LOGINPRESS_PRO_DIR_URL . 'assets/js/dataTables.rowReorder.min.js', array( 'jquery' ), LOGINPRESS_PRO_VERSION, false );

			wp_enqueue_script( 'loginpress_limit_main_js', LOGINPRESS_LIMIT_LOGIN_DIR_URL . 'assets/js/main.js', array( 'loginpress_datatables_js' ), LOGINPRESS_PRO_VERSION, false );

			wp_localize_script(
				'loginpress_limit_main_js',
				'loginpress_llla',
				array(
					'manual_ip_cta' => wp_create_nonce( 'ip_add_remove' ),
					'user_nonce'    => wp_create_nonce( 'loginpress-user-llla-nonce' ),
					'bulk_nonce'    => wp_create_nonce( 'loginpress-llla-bulk-nonce' ),
					'translate'     => array(
						_x( 'Please select at least one item to perform this action on.', 'LLLA bulk action when no action is selected before submission', 'loginpress-pro' ),
						_x( 'Clear', 'The Clear button text from LoginPress Limit Login Attempt\'s Whitelist and Blacklist tabs, LLLA', 'loginpress-pro' ),
						_x( 'This IP', 'This String is a partial when an IP is whitelisted or blacklisted, LLLA', 'loginpress-pro' ),
						_x( 'is Whitelisted', 'This String is a partial when an IP is manually whitelisted, LLLA', 'loginpress-pro' ),
						_x( 'is Blacklisted', 'This String is a partial when an IP is manually blacklisted, LLLA', 'loginpress-pro' ),
						_x( ' Your IP format is not correct ', 'The IP not valid error message when Manual IP is given false, LLLA', 'loginpress-pro' ),
						_x( ' Please enter an IP address ', 'The IP is empty, LLLA', 'loginpress-pro' ),
						_x( ' You entered a reserved IP ', 'Entered Reserved IP, LLLA', 'loginpress-pro' ),
						_x( ' No Whitelist IP found ', 'Remove all IPs from whitelist, LLLA', 'loginpress-pro' ),
						_x( ' No Blacklist IP found ', 'Remove all IPs from blacklist LLLA', 'loginpress-pro' ),
						_x( ' No IP found ', 'Remove all IPs from attempts log, LLLA', 'loginpress-pro' ),
					),
				)
			);
		}


		/** * * * * * * * * * * * * * * * * * *
		 * Setting tab for Limit Login Attempts.
		 *
		 * @param  array $loginpress_tabs Rest of the settings tabs of LoginPress.
		 * @return array $limit_login_tab Limit Login Attempts tab.
		 * @since  3.0.0
		 * * * * * * * * * * * * * * * * * * * */
		public function loginpress_limit_login_attempts_tab( $loginpress_tabs ) {

			$_limit_login_tab = array(
				array(
					'id'         => 'loginpress_limit_login_attempts',
					'title'      => __( 'Limit Login Attempts', 'loginpress-pro' ),
					'sub-title'  => __( 'Limits for login attempts', 'loginpress' ),
					/* translators: * %s: HTML tags */
					'desc'       => $this->tab_desc(),
					'video_link' => '1-L14gHC8R0',
				),
			);

			$limit_login_tab = array_merge( $loginpress_tabs, $_limit_login_tab );

			return $limit_login_tab;
		}

		/**
		 * The tab_desc description of the tab 'loginpress settings'
		 *
		 * @since 1.0.0
		 * @version 3.0.0
		 * @return html $html The tab description.
		 */
		public function tab_desc() {

			$html = sprintf( __( '%1$sThe Limit Login Attempts add-on helps you easily keep track of how many times each user tries to log in and limits the number of attempts they can make. This way, your website is protected from brute force attacks, when hackers try lots of passwords to get in. %2$s', 'loginpress-pro' ), '<p>', '</p>' );

			$html .= sprintf( __( '%1$s%3$sSettings%4$s %5$sAttempt Details%4$s %6$sWhitelist%4$s %7$sBlacklist%4$s%2$s', 'loginpress-pro' ), '<div class="loginpress-limit-login-tab-wrapper">', '</div>', '<a href="#loginpress_limit_login_settings" class="loginpress-limit-login-tab loginpress-limit-login-active">', '</a>', '<a href="#loginpress_limit_logs" class="loginpress-limit-login-tab">', '<a href="#loginpress_limit_login_whitelist" class="loginpress-limit-login-tab">', '<a href="#loginpress_limit_login_blacklist" class="loginpress-limit-login-tab">' );

			return $html;
		}

		/** * * * * * * * * * * * * * * * * * * * *
		 * Setting Fields for Limit Login Attempts.
		 *
		 * @param array $setting_array Settings fields of free version.
		 * @return array Limit Login Attempts settings fields.
		 * @since  3.0.0
		 * * * * * * * * * * * * * * * * * * * * * */
		public function loginpress_limit_login_attempts_settings_array( $setting_array ) {

			$_limit_login_settings = array(
				array(
					'name'    => 'attempts_allowed',
					'label'   => __( 'Attempts Allowed', 'loginpress-pro' ),
					'desc'    => __( 'Allowed Attempts In Numbers (How Many)', 'loginpress-pro' ),
					'type'    => 'number',
					'min'     => 1,
					'default' => '4',
				),
				array(
					'name'    => 'minutes_lockout',
					'label'   => __( 'Lockout Minutes', 'loginpress-pro' ),
					'desc'    => __( 'Lockout Minutes In Numbers (How Many)', 'loginpress-pro' ),
					'type'    => 'number',
					'min'     => 1,
					'default' => '20',
				),
				array(
					'name'              => 'lockout_message',
					'label'             => __( 'Lockout Message', 'loginpress-pro' ),
					'desc'              => __( 'Message for user(s) after reaching maximum login attempts.', 'loginpress-pro' ),
					'type'              => 'text',
					'default'           => '',
					'sanitize_callback' => 'sanitize_text',
				),
				array(
					'name'              => 'ip_add_remove',
					'label'             => __( 'IP Address', 'loginpress-pro' ),
					'type'              => 'text',
					'callback'          => array( $this, 'loginpress_ip_add_remove_callback' ),
					'sanitize_callback' => 'sanitize_text',
				),
				array(
					'name'  => 'disable_xml_rpc_request',
					'label' => __( 'Disable XML RPC Request', 'loginpress-pro' ),
					'desc'  => __( 'The XMLRPC is a system that allows remote updates to WordPress from other applications.', 'loginpress-pro' ),
					'type'  => 'checkbox',
				),
				// array( // Future setting.
				// 'name'  => 'disable_xml_ping_back',
				// 'label' => __( 'Disable Ping Back', 'loginpress-pro' ),
				// 'desc'  => __( 'Disable xml rpc ping back request', 'loginpress-pro' ),
				// 'type'  => 'checkbox',
				// ),.
				array(
					'name'  => 'delete_data',
					'label' => __( 'Remove Record On Uninstall', 'loginpress-pro' ),
					'desc'  => __( 'This tool will remove all LoginPress - Limit Login Attempts record upon uninstall.', 'loginpress-pro' ),
					'type'  => 'checkbox',

				),
				// array(
				// 'name'              => 'lockout_increase',
				// 'label'             => __( 'Lockout Increase lockout time to ', 'loginpress-pro' ),
				// 'desc'              => __( 'Description.', 'loginpress-pro' ),
				// 'type'              => 'number',
				// 'min'               => 0,
				// 'default'           => '3',
				// ).
			);

			$limit_login_settings = array( 'loginpress_limit_login_attempts' => $_limit_login_settings );

			return( array_merge( $limit_login_settings, $setting_array ) );
		}

		/**
		 * Callback for blacklist tab.
		 *
		 * @since 3.0.0
		 */
		public function loginpress_limit_login_attempts_blacklist_callback() {

			global $wpdb;

			$myblacklist = $wpdb->get_results( $wpdb->prepare( 'SELECT DISTINCT ip,blacklist FROM %1s WHERE `blacklist` = %d ORDER BY `datentime` DESC LIMIT 50', $this->llla_table, 1 ) );  // @codingStandardsIgnoreLine.

			$html = '<div id="loginpress_limit_login_blacklist_wrapper2">
			<div class="loginpress-edit-black-popup-containers llla_remove_all_popup">
			<div class="loginpress-edit-overlay"></div><div class="loginpress-edit-popup loginpress-link-duration-popup loginpress-llla-popup">
				<div class="llla_popup_heading"><img src="' . LOGINPRESS_LIMIT_LOGIN_DIR_URL . 'assets/img/llla_confirm.svg"><h3>' . esc_html__( 'Are you sure to delete all the entries?', 'loginpress-pro' ) . '</h3></div>
				<div class="loginpress-llla-duration-buttons">
					<button class="button button-primary loginpress_confirm_remove_all_blacklist">' . esc_html__( 'Yes', 'loginpress-pro' ) . '</button>
					<button class="button button-primary limit-login-attempts-close-popup">' . esc_html__( 'No', 'loginpress-pro' ) . '</button>
				</div>
			</div>
			</div>
			<div class="row-per-page"><span>' . __( 'Show Entries', 'loginpress-pro' ) . '</span> <select id="loginpress_limit_login_blacklist_select" class="selectbox"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select>
			<div class="bulk_option_wrapper">
				<button id="loginpress_limit_bulk_blacklists_submit" class="button">Clear All</button>
			</div>
			</div>
			<table id="loginpress_limit_login_blacklist" class="display" cellspacing="0" width="100%">
			
				<thead>
					<tr>
						<th>' . __( 'IP', 'loginpress-pro' ) . '</th>
						<th>' . __( 'Action', 'loginpress-pro' ) . '</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>' . __( 'IP', 'loginpress-pro' ) . '</th>
						<th>' . __( 'Action', 'loginpress-pro' ) . '</th>
					</tr>
				</tfoot>
				<tbody>';
			if ( $myblacklist ) {

				foreach ( $myblacklist as $blacklist ) {
					$html .= '<tr>';
					$html .= '<td class="loginpress_limit_login_blacklist_ips" data-blacklist-ip="' . $blacklist->ip . '"><div class="lp-tbody-cell">' . $blacklist->ip . '</div></td>';
					$html .= '<td class="loginpress_limit_login_blacklist_actions"><div class="lp-tbody-cell"><button class="loginpress-blacklist-clear button button-primary" type="button" value="Clear" ></button></div></td>';
					$html .= '</tr>';
				}
			} else {
				$html .= ''; // <h2>Not Found</h2>.
			}
			$html .= '</tbody>
			</table></div>';
			echo $html;  // @codingStandardsIgnoreLine.
		}

		/** * * * * * * * * * * * * *
		 * Callback for Whitelist tab.
		 *
		 * @since 3.0.0
		 * * * * * * * * * * * * * * */
		public function loginpress_limit_login_attempts_whitelist_callback() {

			global $wpdb;
			$my_whitelist = $wpdb->get_results( $wpdb->prepare( 'SELECT DISTINCT ip,whitelist FROM %1s WHERE `whitelist` = %d ORDER BY `datentime` DESC LIMIT 50', $this->llla_table, 1 ) );  // @codingStandardsIgnoreLine.

			$html = '<div id="loginpress_limit_login_whitelist_wrapper2">
			<div class="loginpress-edit-white-popup-containers llla_remove_all_popup">
			<div class="loginpress-edit-overlay"></div><div class="loginpress-edit-popup loginpress-link-duration-popup loginpress-llla-popup">
				<div class="llla_popup_heading"><img src="' . LOGINPRESS_LIMIT_LOGIN_DIR_URL . 'assets/img/llla_confirm.svg"><h3>' . esc_html__( 'Are you sure to delete all the entries?', 'loginpress-pro' ) . '</h3></div>
				<div class="loginpress-llla-duration-buttons">
					<button class="button button-primary loginpress_confirm_remove_all_whitelist">' . esc_html__( 'Yes', 'loginpress-pro' ) . '</button>
					<button class="button button-primary limit-login-attempts-close-popup">' . esc_html__( 'No', 'loginpress-pro' ) . '</button>
				</div>
			</div>
			</div>
			<div class="row-per-page"><span>' . __( 'Show Entries', 'loginpress-pro' ) . '</span> <select id="loginpress_limit_login_whitelist_select" class="selectbox"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select>
			<div class="bulk_option_wrapper">
				<button id="loginpress_limit_bulk_whitelists_submit" class="button">Clear All</button>
			</div>
			</div><table id="loginpress_limit_login_whitelist" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>' . __( 'IP', 'loginpress-pro' ) . '</th>
						<th>' . __( 'Action', 'loginpress-pro' ) . '</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>' . __( 'IP', 'loginpress-pro' ) . '</th>
						<th>' . __( 'Action', 'loginpress-pro' ) . '</th>
					</tr>
				</tfoot>
				<tbody>';
			if ( $my_whitelist ) {

				foreach ( $my_whitelist as $whitelist ) {
					$html .= '<tr>';
					$html .= '<td class="loginpress_limit_login_whitelist_ips" data-whitelist-ip="' . $whitelist->ip . '"><div class="lp-tbody-cell">' . $whitelist->ip . '</div></td>';
					$html .= '<td class="loginpress_limit_login_whitelist_actions"><div class="lp-tbody-cell"><button class="loginpress-whitelist-clear button button-primary" type="button" value="Clear" /></button></div></td>';
					$html .= '</tr>';
				}
			} else {
				$html .= ''; // <h2>Not Found</h2>.
			}
				$html .= '</tbody>
			</table></div>';
			echo $html; // @codingStandardsIgnoreLine.
		}

		/** * * * * * * * * * * * * * * *
		 * Callback for Attempts log Tab.
		 *
		 * @since 3.0.0
		 * * * * * * * * * * * * * * * * */
		public function loginpress_limit_login_attempts_log_callback() {

			global $wpdb;
			// Get result from $this->llla_table where IP's aren't blaclisted or whitelisted.
			$my_result = $wpdb->get_results( $wpdb->prepare( "SELECT *, (whitelist+blacklist) as list FROM `{$this->llla_table}` HAVING list = %d ORDER BY `datentime` DESC LIMIT 50", 0 ) ); // @codingStandardsIgnoreLine.

			$html = '<div id="loginpress_limit_logs">
			<div class="loginpress_llla_loader_inner"><img src="' . esc_url( LOGINPRESS_DIR_URL . 'img/loginpress-sniper.gif' ) . '" /></div>
			<div class="loginpress_limit_login_log_def">
				<div class="loginpress_limit_login_log_definition"><span class="loginpress-attempts-unlock"></span><p>' . __( 'Unlock/Deletes certain IP address from the database.', 'loginpress-pro' ) . '</p></div>
				<div class="loginpress_limit_login_log_definition"><span class="loginpress-attempts-whitelist"></span><p>' . __( 'Move certain IP address to whitelist so Login Attempts are not applied on them.', 'loginpress-pro' ) . '</p></div>
				<div class="loginpress_limit_login_log_definition"><span class="loginpress-attempts-blacklist"></span><p>' . __( 'Move certain IP address to blacklist so a certain IP address couldn\'t access your login page.', 'loginpress-pro' ) . '</p></div>
			</div>
				<div class="bulk_option_wrapper">
					<div class="bulk_option">
						<select id="loginpress_limit_bulk_blacklist">
							<option value="">' . __( 'Bulk Action', 'loginpress-pro' ) . '</option>
							<option value="unlock">' . __( 'Unlock', 'loginpress-pro' ) . '</option>
							<option value="white_list">' . __( 'White List', 'loginpress-pro' ) . '</option>
							<option value="black_list">' . __( 'Black List', 'loginpress-pro' ) . '</option>
						</select>
						<button id="loginpress_limit_bulk_blacklist_submit" class="button">' . esc_html__( 'submit', 'loginpress-pro' ) . '</button>
					</div>
					<div class="bulk_clear">
						<button id="loginpress_limit_bulk_attempts_submit" class="button">' . esc_html__( 'Clear All', 'loginpress-pro' ) . '</button>
					</div>
				</div>
				<div class="loginpress-edit-attempts-popup-containers llla_remove_all_popup">
				<div class="loginpress-edit-overlay"></div><div class="loginpress-edit-popup loginpress-link-duration-popup loginpress-llla-popup">
				<div class="llla_popup_heading"><img src="' . LOGINPRESS_LIMIT_LOGIN_DIR_URL . 'assets/img/llla_confirm.svg"><h3>' . esc_html__( 'Are you sure to delete all the entries?', 'loginpress-pro' ) . '</h3></div>
				<div class="loginpress-llla-duration-buttons">
					<button class="button button-primary loginpress_confirm_remove_all_attempts">' . esc_html__( 'Yes', 'loginpress-pro' ) . '</button>
					<button class="button button-primary limit-login-attempts-close-popup">' . esc_html__( 'No', 'loginpress-pro' ) . '</button>
				</div>
			</div>
				</div>
				<div class="row-per-page"><span>' . __( 'Show Entries', 'loginpress-pro' ) . '</span> <select id="loginpress_limit_login_log_select" class="selectbox"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select></div>
				<table id="loginpress_limit_login_log" class="display nowrap" cellspacing="0" width="100%">
        			<thead>
						<tr>
							<th><input type="checkbox" name="select_all" value="1" class="lla-select-all"></th>
							<th data-priority="1">' . __( 'IP', 'loginpress-pro' ) . '</th>
							<th>' . __( 'Date & Time', 'loginpress-pro' ) . '</th>
							<th>' . __( 'Username', 'loginpress-pro' ) . '</th>
							<th>' . __( 'Password', 'loginpress-pro' ) . '</th>
							<th>' . __( 'Gateway', 'loginpress-pro' ) . '</th>
							<th data-priority="2">' . __( 'Action', 'loginpress-pro' ) . '</th>
						</tr>
          			</thead>
          			<tfoot>
						<tr>
							<th><input type="checkbox" name="select_all" value="1" class="lla-select-all"></th>
							<th>' . __( 'IP', 'loginpress-pro' ) . '</th>
							<th>' . __( 'Date & Time', 'loginpress-pro' ) . '</th>
							<th>' . __( 'Username', 'loginpress-pro' ) . '</th>
							<th>' . __( 'Password', 'loginpress-pro' ) . '</th>
							<th>' . __( 'Gateway', 'loginpress-pro' ) . '</th>
							<th>' . __( 'Action', 'loginpress-pro' ) . '</th>
						</tr>
					</tfoot>
        			<tbody>';
			if ( ! empty( $my_result ) ) {

				foreach ( $my_result as $result ) {
					$html .= '<tr id="loginpress_attempts_id_' . $result->id . '" data-login-attempt-user="' . $result->id . '" data-ip="' . $result->ip . '">';
					$html .= '<th></th><td class="lg_attempts_ip"><div class="lp-tbody-cell">' . $result->ip . '</div></td>';
					$html .= '<td class="loginpress_limit_login_log_dates"><div class="lp-tbody-cell">' . gmdate( 'm/d/Y H:i:s', (int) $result->datentime ) . '</div></td>';
					$html .= '<td class="loginpress_limit_login_log_usernames"><div class="lp-tbody-cell"><span class="attempts-sniper"><img src="' . esc_url( LOGINPRESS_DIR_URL . 'img/loginpress-sniper.gif' ) . '" /></span>' . $result->username . '</div></td>';
					$html .= '<td class="loginpress_limit_login_log_passwords"><div class="lp-tbody-cell">' . $result->password . '</div></td>';
					$html .= '<td class="loginpress_limit_login_log_gateways"><div class="lp-tbody-cell">' . $result->gateway . '</div></td>';
					$html .= '<td class="loginpress_limit_login_log_actions"><div class="lp-tbody-cell"> <div class="loginpress-attempts-unlock-wrapper"><input class="loginpress-attempts-unlock button button-primary" type="button" value="Unlock" /></div> <div class="loginpress-attempts-whitelist-wrapper"><input class="loginpress-attempts-whitelist button" type="button" value="Whitelist" /></div> <div class="loginpress-attempts-blacklist-wrapper"><input class="loginpress-attempts-blacklist button" type="button" value="Blacklist" /></div></div></td>';
					$html .= '</tr>';

				}
			} else {
				$html .= ''; // <h2>Not Found</h2>.
			}
			$html .= '</tbody>
      			</table>
			</div>';
			echo $html; // @codingStandardsIgnoreLine.
		}

		/** * * * * * * * * *
		 * Main Instance
		 *
		 * @since 3.0.0
		 * @static
		 * @return object Main instance of the Class
		 * * * * * * * * * * */
		public static function instance() {

			if ( is_null( self::$llla_instance ) ) {
				self::$llla_instance = new self();
			}
			return self::$llla_instance;
		}

		/**
		 * Ip add or remove setting callback.
		 *
		 * @since 3.0.0
		 * @param array $args argument of setting.
		 * @return void $html
		 */
		public function loginpress_ip_add_remove_callback( $args ) {

			$size        = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$type        = isset( $args['type'] ) ? $args['type'] : 'text';
			$placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . $args['placeholder'] . '"';

			$whitelist = __( 'WhiteList', 'loginpress-pro' );
			$blacklist = __( 'BlackList', 'loginpress-pro' );
			$spinner   = '<span class="lla-spinner"><img src="' . esc_url( LOGINPRESS_DIR_URL . 'img/loginpress-sniper.gif' ) . '" /></span>';

			$html  = '<input type="' . $type . '" class="' . $size . '-text" id="' . $args['section'] . '[' . $args['id'] . ']" name="' . $args['section'] . '[' . $args['id'] . ']" value="" ' . $placeholder . '/>';
			$html .= '<p class="description"><button class="button loginpress-attempts-whitelist add_white_list" data-action="white_list" type="button">' . $whitelist . '</button><button class="button loginpress-attempts-blacklist add_black_list" data-action="black_list" type="button">' . $blacklist . ' </button>' . $spinner . '</p>';

			echo $html; // @codingStandardsIgnoreLine.
		}
	}

endif;
