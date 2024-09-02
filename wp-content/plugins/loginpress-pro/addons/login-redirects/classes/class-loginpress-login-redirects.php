<?php


if ( ! class_exists( 'LoginPress_Login_Redirect_Main' ) ) :

	/**
	 * LoginPress Login Redirects Main class
	 */
	class LoginPress_Login_Redirect_Main {

		/**
		 * Instance of this class.
		 *
		 * @since 3.0.0
		 * @var object
		 */
		protected static $instance = null;

		/**
		 * Class COnstructor
		 */
		public function __construct() {

			$this->hooks();
			$this->includes();
		}

		/**
		 * Call action hooks.
		 */
		public function hooks() {

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_filter( 'loginpress_settings_tab', array( $this, 'loginpress_login_redirects_tab' ), 10, 1 );
			add_filter( 'loginpress_settings_fields', array( $this, 'loginpress_login_redirects_settings_array' ), 10, 1 );
			add_filter( 'loginpress_login_redirects', array( $this, 'loginpress_login_redirects_callback' ), 10, 1 );

			// add_action( 'admin_init',   array( $this, 'init_addon_updater' ), 0 );
			add_action( 'admin_footer', array( $this, 'loginpress_login_redirects_autocomplete_js' ) );
			add_action( 'wp_ajax_loginpress_login_redirects_update', array( $this, 'login_redirects_update_user_meta' ) );
			add_action( 'wp_ajax_loginpress_login_redirects_delete', array( $this, 'login_redirects_delete_user_meta' ) );
			add_action( 'wp_ajax_loginpress_login_redirects_role_update', array( $this, 'login_redirects_update_role' ) );
			add_action( 'wp_ajax_loginpress_login_redirects_role_delete', array( $this, 'login_redirects_delete_role' ) );
			add_action( 'loginpress_login_redirect_script', array( $this, 'login_redirect_script_html' ) );
		}

		/**
		 * LoginPress Addon updater
		 */
		public function init_addon_updater() {
			if ( class_exists( 'LoginPress_AddOn_Updater' ) ) {

				$updater = new LoginPress_AddOn_Updater( 2341, LOGINPRESS_REDIRECT_ROOT_FILE, $this->version );
			}
		}

		/**
		 * Includes include files
		 *
		 * @since 3.0.0
		 */
		public function includes() {

			include_once LOGINPRESS_REDIRECT_DIR_PATH . 'classes/class-redirects.php';
		}

		/**
		 * Load CSS and JS files at admin side on loginpress-settings page only.
		 *
		 * @param string $hook the Page ID.
		 * @since  3.0.0
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

			wp_enqueue_style( 'loginpress_login_redirect_stlye', LOGINPRESS_PRO_ADDONS_DIR . '/login-redirects/assets/css/style.css', array(), LOGINPRESS_PRO_VERSION );

			wp_enqueue_style( 'loginpress_datatables_style', LOGINPRESS_PRO_DIR_URL . 'assets/css/jquery.dataTables.min.css', array(), LOGINPRESS_PRO_VERSION );

			wp_enqueue_script( 'loginpress_datatables_js', LOGINPRESS_PRO_DIR_URL . 'assets/js/jquery.dataTables.min.js', array( 'jquery' ), LOGINPRESS_PRO_VERSION, false );

			wp_enqueue_script( 'loginpress_login_redirect_js', LOGINPRESS_PRO_ADDONS_DIR . '/login-redirects/assets/js/login-redirect.js', array( 'jquery', 'loginpress_datatables_js' ), LOGINPRESS_PRO_VERSION, false );
			wp_localize_script(
				'loginpress_login_redirect_js',
				'loginpress_redirect',
				array(
					'user_nonce' => wp_create_nonce( 'loginpress-user-redirects-nonce' ),
					'role_nonce' => wp_create_nonce( 'loginpress-role-redirects-nonce' ),
					'translate'  => array(
						_x( 'Search Username', 'The label Text of Login Redirect addon Username search field', 'loginpress-pro' ),
						_x( 'Search Username For Whom To Apply Redirects', 'LoginPress Redirects Description text for Specific Username tab\'s search', 'loginpress-pro' ),
						_x( 'Search Roles', 'The label Text of Login Redirect addon Roles search field', 'loginpress-pro' ),
						_x( 'Search Role For Whom To Apply Redirects', 'LoginPress Redirects Description text for Specific Roles tab\'s search', 'loginpress-pro' ),
						sprintf( _x( '%1$sSearch user\'s data from below the list%2$s', 'Search Label on Data tables', 'loginpress-pro' ), '<p class="description">', '</p>' ),
						_x( 'Enter keyword', 'The search keyword for the autologin users.', 'loginpress-pro' ),
					),
				)
			);
		}

		/**
		 * Setting tab for Login Redirects.
		 *
		 * @param  array $loginpress_tabs Rest of the settings tabs of LoginPress.
		 * @return array Login Redirects tab.
		 * @since 3.0.0
		 */
		public function loginpress_login_redirects_tab( $loginpress_tabs ) {

			$_login_redirects_tab = array(
				array(
					'id'         => 'loginpress_login_redirects',
					'title'      => __( 'Login Redirects', 'loginpress-pro' ),
					'sub-title'  => __( 'Automaticaly redirects the login', 'loginpress' ),
					/* translators: * %s: HTML tags */
					'desc'       => $this->tab_desc(),
					'video_link' => 'EYqt8-iegeQ',
				),
			);
			$login_redirects_tab  = array_merge( $loginpress_tabs, $_login_redirects_tab );
			return $login_redirects_tab;
		}

		/**
		 * The tab_desc description of the tab 'loginpress settings'
		 *
		 * @since 3.0.0
		 * @return html $html The tab description.
		 */
		public function tab_desc() {

			$html = sprintf( __( '%1$sWith the Login Redirects add-on, you can easily redirect users based on their roles and specific usernames. Additionally, you can use this add-on to restrict access to certain pages for subscribers, guests, or customers instead of the default wp-admin page.%2$s', 'loginpress-pro' ), '<p>', '</p>' );

			$html .= sprintf( __( '%1$s%3$sSpecific Users%4$s %5$sSpecific Roles%4$s%2$s ', 'loginpress-pro' ), '<div class="loginpress-redirects-tab-wrapper">', '</div>', '<a href="#loginpress_login_redirect_users" class="loginpress-redirects-tab loginpress-redirects-active">', '</a>', '<a href="#loginpress_login_redirect_roles" class="loginpress-redirects-tab">' );

			return $html;
		}
		/**
		 * Setting Fields for Login Redirects.
		 *
		 * @param  array $setting_array Settings fields of free version.
		 * @return array Login Redirects settings fields.
		 * @version 3.0.0
		 */
		public function loginpress_login_redirects_settings_array( $setting_array ) {

			$_login_redirects_settings = array(
				array(
					'name'  => 'login_redirects',
					'label' => __( 'Search Username', 'loginpress-pro' ),
					'desc'  => __( 'Search Username For Whom To Apply Redirects', 'loginpress-pro' ),
					'type'  => 'login_redirect',
				),
			);
			$login_redirects_settings  = array( 'loginpress_login_redirects' => $_login_redirects_settings );
			return( array_merge( $login_redirects_settings, $setting_array ) );
		}

		/**
		 * A callback function that will show a search field under Login Redirect tab.
		 *
		 * @param string $args argument.
		 * @since   3.0.0
		 * @return string $html
		 */
		public function loginpress_login_redirects_callback( $args ) {

			$html  = sprintf( '<input type="%1$s" name="%2$s" id="%2$s" value="" placeholder="%3$s" %4$s', 'text', 'loginpress_redirect_user_search', __( 'Type Username', 'loginpress-pro' ), '/>' );
			$html .= sprintf( '<input type="%1$s" name="%2$s" id="%2$s" value="" placeholder="%3$s" %4$s', 'text', 'loginpress_redirect_role_search', __( 'Type Role', 'loginpress-pro' ), '/>' );

			return $html;
		}

		/**
		 * A callback function that will show search result under the search field.
		 * Return should be a string $html.
		 *
		 * @since 3.0.0
		 */
		public function login_redirect_script_html() {
			/**
			 * Check to apply the script only on the LoginPress Settings page.
			 *
			 * @since 1.1.5
			 */
			if ( isset( $_GET['page'] ) && sanitize_text_field( $_GET['page'] ) !== 'loginpress-settings' ) {
				return;
			}

			$html = '<div class="row-per-page"><span>' . __( 'Show Entries', 'loginpress-pro' ) . '</span> <select id="loginpress_login_redirect_users_select" class="selectbox"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select></div><table id="loginpress_login_redirect_users" class="loginpress_login_redirect_users">
			<thead><tr>
			<th class="loginpress_user_id">' . esc_html__( 'User ID', 'loginpress-pro' ) . '</th>
			<th class="loginpress_log_userName">' . esc_html__( 'Username', 'loginpress-pro' ) . '</th>
			<th class="loginpress_log_email">' . esc_html__( 'Email', 'loginpress-pro' ) . '</th>
			<th class="loginpress_login_redirect">' . esc_html__( 'Login URL', 'loginpress-pro' ) . '</th>
			<th class="loginpress_logout_redirect">' . esc_html__( 'Logout URL', 'loginpress-pro' ) . '</th>
			<th class="loginpress_action">' . esc_html__( 'Action', 'loginpress-pro' ) . '</th>
			</tr></thead>';

			$args = array(
				'blog_id'    => $GLOBALS['blog_id'],
				'meta_query' => array( // @codingStandardsIgnoreLine.
					'relation' => 'OR',
					array( 'key' => 'loginpress_login_redirects_url' ),
					array( 'key' => 'loginpress_logout_redirects_url' ),
				),
			);

			$user_query = new WP_User_Query( $args );
			// get_results w.r.t 'meta_key' => 'loginpress_login_redirects_url' || 'loginpress_logout_redirects_url'.
			$redirect_user = $user_query->get_results();
			// Check for results.
			if ( ! empty( $redirect_user ) ) {
				// loop through each user.
				foreach ( $redirect_user as $user ) {
					// get all the user's data.
					$user_info = get_userdata( $user->ID );
					$html     .= '<tr id="loginpress_redirects_user_id_' . $user->ID . '" data-login-redirects-user="' . $user->ID . '"><td><div class="lp-tbody-cell">' . $user_info->ID . '</div></td><td class="loginpress_user_name"><div class="lp-tbody-cell">' . $user_info->user_login . '</div></td><td class="loginpress_login_redirects_email"><div class="lp-tbody-cell">' . $user_info->user_email . '</div></td><td class="loginpress_login_redirects_url"><div class="lp-tbody-cell"><span class="login-redirects-sniper"><img src="' . esc_url( LOGINPRESS_DIR_URL . 'img/loginpress-sniper.gif' ) . '" /></span><input type="text" value="' . get_user_meta( $user->ID, 'loginpress_login_redirects_url', true ) . '" id="loginpress_login_redirects_url"/></div></td><td class="loginpress_logout_redirects_url"><div class="lp-tbody-cell"><span class="login-redirects-sniper"><img src="' . esc_url( LOGINPRESS_DIR_URL . 'img/loginpress-sniper.gif' ) . '" /></span><input type="text" value="' . get_user_meta( $user->ID, 'loginpress_logout_redirects_url', true ) . '" id="loginpress_logout_redirects_url"/></div></td><td class="loginpress_login_redirects_actions"><div class="lp-tbody-cell"><button type="button" class="button loginpress-user-redirects-update"  value="' . esc_html__( 'Update', 'loginpress-pro' ) . '" ></button> <button type="button" class="button loginpress-user-redirects-delete" value="' . esc_html__( 'Delete', 'loginpress-pro' ) . '"  ></button></div></td></tr>';
				}
			} else {
				$html .= '';
			}

			$html .= '</table>';

			$html .= '<table id="loginpress_login_redirect_roles" class="loginpress_login_redirect_roles">
			<thead><tr>
				<th class="loginpress_user_id">' . esc_html__( 'No', 'loginpress-pro' ) . '</th>
				<th class="loginpress_log_userName">' . esc_html__( 'Role', 'loginpress-pro' ) . '</th>
				<th class="loginpress_login_redirect">' . esc_html__( 'Login URL', 'loginpress-pro' ) . '</th>
				<th class="loginpress_logout_redirect">' . esc_html__( 'Logout URL', 'loginpress-pro' ) . '</th>
				<th class="loginpress_action">' . esc_html__( 'Action', 'loginpress-pro' ) . '</th>
			</tr></thead>';

			$login_redirect_role = get_option( 'loginpress_redirects_role' );

			// Check for results.
			if ( ! empty( $login_redirect_role ) ) {
				// loop through each user.
				$no = 0;
				foreach ( $login_redirect_role as $role => $value ) {

					$html .= '<tr id="loginpress_redirects_role_' . $role . '" data-login-redirects-role="' . $role . '"><td class="loginpress_login_redirects_role sorting_1"><div class="lp-tbody-cell no-of"></div></td><td class="loginpress_user_name"><div class="lp-tbody-cell">' . $role . '</div></td><td class="loginpress_login_redirects_url"><div class="lp-tbody-cell"><span class="login-redirects-sniper"><img src="' . esc_url( LOGINPRESS_DIR_URL . 'img/loginpress-sniper.gif' ) . '" /></span><input type="text" value="' . $value['login'] . '" id="loginpress_login_redirects_url"/></div></td><td class="loginpress_logout_redirects_url"><div class="lp-tbody-cell"><span class="login-redirects-sniper"><img src="' . esc_url( LOGINPRESS_DIR_URL . 'img/loginpress-sniper.gif' ) . '" /></span><input type="text" value="' . $value['logout'] . '" id="loginpress_logout_redirects_url"/></div></td><td class="loginpress_login_redirects_actions"><div class="lp-tbody-cell"><button type="button" class="button loginpress-redirects-role-update" value="' . esc_html__( 'Update', 'loginpress-pro' ) . '" ></button> <button type="button" class="button loginpress-redirects-role-delete"  value="' . esc_html__( 'Delete', 'loginpress-pro' ) . '" ></button></div></td></tr>';
				}
			} else {
				$html .= '';
			}

			$html .= '</table>';

			echo $html; // @codingStandardsIgnoreLine.
		}

		/**
		 * Get the users list and Saved it in footer that will use for autocomplete in search.
		 *
		 * @since 3.0.0
		 */
		public function loginpress_login_redirects_autocomplete_js() {

			/**
			 * Check to apply the script only on the LoginPress Settings page.
			 *
			 * @since 1.1.5
			 */
			$current_screen = get_current_screen();

			if ( isset( $current_screen->base ) && ( 'toplevel_page_loginpress-settings' !== $current_screen->base ) ) {
				return;
			}

			$users = get_users(
				array(
					'fields' => array(
						'ID',
						'user_login',
						'user_email',
					),
				),
			);

			if ( $users ) : ?>
				<script type="text/javascript">
					var redirect_roles;
					var redirect_users; 
					jQuery(document).ready(function($){

						var posts = <?php echo wp_json_encode( array_values( $users ) ); ?>;
						posts.forEach(entry => {
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
						if ( jQuery( 'input[name="loginpress_redirect_user_search"]' ).length > 0 ) {
							jQuery( 'input[name="loginpress_redirect_user_search"]' ).autocomplete( {
								source: loginpressAutocompleteData,
								minLength: 1,
								search: function (event,ui) {
									window.pageIndex = 0;
								},
								select: function( event, ui ) {
									var id    = ui.item.ID;
									var name  = ui.item.label;
									var email = ui.item.user_email;
									if ( $( '#loginpress_redirects_user_id_' + id ).length == 0 ) {
										$('#loginpress_login_redirect_users .dataTables_empty').hide();
										var get_html = $('<tr id="loginpress_redirects_user_id_' + id + '" data-login-redirects-user="' + id + '"><td class="dtfc-fixed-left sorting_1" style="left: 0px; position: sticky;"><div class="lp-tbody-cell">' + id + '</td><td class="loginpress_user_name"><div class="lp-tbody-cell">' + name + '</div></td><td ><div class="lp-tbody-cell">' + email + '</div></td><td class="loginpress_login_redirects_url"><div class="lp-tbody-cell"><span class="login-redirects-sniper"><img src="<?php echo esc_attr( esc_url( LOGINPRESS_DIR_URL . 'img/loginpress-sniper.gif' ) ); ?>" /></span><input type="text" value="" id="loginpress_login_redirects_url"/><div></td><td class="loginpress_logout_redirects_url"><div class="lp-tbody-cell"><span class="login-redirects-sniper"><img src="<?php echo esc_attr( esc_url( LOGINPRESS_DIR_URL . 'img/loginpress-sniper.gif' ) ); ?>" /></span><input type="text" value="" id="loginpress_logout_redirects_url"/></div></td><td class="loginpress_login_redirects_actions"><div class="lp-tbody-cell"><input type="button" class="button loginpress-user-redirects-update" value="<?php echo esc_html__( 'Update', 'loginpress-pro' ); ?>" /> <input type="button" class="button loginpress-user-redirects-delete" /></div></td></tr>');

										// get_html.appendTo('#autologin_users');
										if ( $( '#loginpress_redirects_user_id_' + id ).length == 0 ) {
											// $('#loginpress_login_redirect_users').append( get_html );
											redirect_users.row.add(get_html[0]).draw();
											$( '#loginpress_redirects_user_id_'+id ).find('td:first-child').addClass('dtfc-fixed-left');
											$( '#loginpress_redirects_user_id_'+id ).find('td:last-child').addClass('dtfc-fixed-right');

										}

									} else {
										$( '#loginpress_redirects_user_id_'+id ).addClass('loginpress_user_highlighted');
										setTimeout(function(){
											$( '#loginpress_redirects_user_id_'+id ).removeClass('loginpress_user_highlighted');
										}, 3000 );
									}
								} // !select.
							});
						}
					});
				</script>
				<?php
			endif;

			global $wp_roles;

			$all_roles = $wp_roles->roles;
			foreach ( $all_roles as $k => $value ) {

				$role[ $k ]['role']  = esc_attr( $k );
				$role[ $k ]['label'] = translate_user_role( $value['name'] ); // returns localized name. v1.1.2.
			}
			?>
			<script type="text/javascript">
				jQuery(document).ready( function($) {

					var posts = <?php echo wp_json_encode( array_values( $role ) ); ?>;

					if ( jQuery( 'input[name="loginpress_redirect_role_search"]' ).length > 0 ) {
						jQuery( 'input[name="loginpress_redirect_role_search"]' ).autocomplete({

							source: posts,
							minLength: 1,
							select: function( event, ui ) {

								var name = ui.item.label;
								var role = ui.item.role;
								if ( $( '#loginpress_redirects_role_' + role ).length == 0 ) {

									$('#loginpress_login_redirect_roles .dataTables_empty').hide();
									var get_html = $('<tr id="loginpress_redirects_role_' + role + '" data-login-redirects-role="' + role + '"><td class="dtfc-fixed-left sorting_1" style="left: 0px; position: sticky;"><div class="lp-tbody-cell no-of"></div></td><td class="loginpress_user_name"><div class="lp-tbody-cell">' + name + '</div></td><td class="loginpress_login_redirects_url"><div class="lp-tbody-cell"><span class="login-redirects-sniper"><img src="<?php echo esc_attr( esc_url( LOGINPRESS_DIR_URL . 'img/loginpress-sniper.gif' ) ); ?>" /></span><input type="text" value="" id="loginpress_login_redirects_url"/></div></td><td class="loginpress_logout_redirects_url"><div class="lp-tbody-cell"><span class="login-redirects-sniper"><img src="<?php echo esc_attr( esc_url( LOGINPRESS_DIR_URL . 'img/loginpress-sniper.gif' ) ); ?>" /></span><input type="text" value="" id="loginpress_logout_redirects_url"/></div></td><td class="loginpress_login_redirects_actions"><div class="lp-tbody-cell"><input type="button" class="button loginpress-redirects-role-update" value="<?php echo esc_html__( 'Update', 'loginpress-pro' ); ?>" /> <input type="button" class="button loginpress-redirects-role-delete" value="<?php echo esc_html__( 'Delete', 'loginpress-pro' ); ?>" /></div></td></tr>');

									// get_html.appendTo('#autologin_users');
									if ( $('#loginpress_redirects_role_' + role ).length == 0 ) {
										redirect_roles.row.add(get_html[0]).draw();
										$( '#loginpress_redirects_role_'+role ).find('td:first-child').addClass('dtfc-fixed-left');
										$( '#loginpress_redirects_role_'+role ).find('td:last-child').addClass('dtfc-fixed-right');
									}

								} else {
									$( '#loginpress_redirects_role_' + role ).addClass( 'loginpress_user_highlighted' );
									setTimeout(function(){
										$( '#loginpress_redirects_role_' + role ).removeClass( 'loginpress_user_highlighted' );
									}, 3000 );
								}
							} // !select.
						});
					}
				});
			</script>
			<?php
		}


		/**
		 * Ajax function that update the user meta after creating autologin code
		 *
		 * @since 3.0.0
		 */
		public function login_redirects_update_user_meta() {

			check_ajax_referer( 'loginpress-user-redirects-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}
			if ( isset( $_POST['id'] ) && isset( $_POST['logout'] ) && isset( $_POST['login'] ) ) {
				$user_id           = esc_html( wp_unslash( intval( $_POST['id'] ) ) );
				$loginpress_logout = esc_url_raw( wp_unslash( $_POST['logout'] ) );
				$loginpress_login  = esc_url_raw( wp_unslash( $_POST['login'] ) );

				$this->loginpress_update_redirect_url( $user_id, 'loginpress_login_redirects_url', $loginpress_login );
				$this->loginpress_update_redirect_url( $user_id, 'loginpress_logout_redirects_url', $loginpress_logout );

				echo esc_url( $this->loginpress_get_redirect_url( $user_id, 'loginpress_login_redirects_url' ) );
				echo esc_url( $this->loginpress_get_redirect_url( $user_id, 'loginpress_logout_redirects_url' ) );
			}
			wp_die();
		}

		/**
		 * LoginPress_redirects_role.
		 *
		 * @version 3.0.0
		 */
		public function login_redirects_update_role() {

			check_ajax_referer( 'loginpress-role-redirects-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}
			if ( isset( $_POST['logout'] ) && isset( $_POST['login'] ) && isset( $_POST['role'] ) ) {
				$loginpress_logout = esc_url_raw( wp_unslash( $_POST['logout'] ) );
				$loginpress_login  = esc_url_raw( wp_unslash( $_POST['login'] ) );
				$role              = sanitize_text_field( wp_unslash( $_POST['role'] ) );
				$check_role        = get_option( 'loginpress_redirects_role' );
				$add_role          = array(
					$role => array(
						'login'  => $loginpress_login,
						'logout' => $loginpress_logout,
					),
				);

				if ( $check_role && ! in_array( $role, $check_role, true ) ) {
					$redirect_roles = array_merge( $check_role, $add_role );
				} else {
					$redirect_roles = $add_role;
				}

				update_option( 'loginpress_redirects_role', $redirect_roles, true );
			}
			wp_die();
		}

		/**
		 * Ajax function that delete the user meta after click on delete user redirect button
		 *
		 * @since 3.0.0
		 */
		public function login_redirects_delete_user_meta() {

			check_ajax_referer( 'loginpress-user-redirects-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}
			if ( isset( $_POST['id'] ) ) {
				$user_id = esc_html( intval( $_POST['id'] ) );

				$this->loginpress_delete_redirect_url( $user_id, 'loginpress_login_redirects_url' );
				$this->loginpress_delete_redirect_url( $user_id, 'loginpress_logout_redirects_url' );
				echo esc_html__( 'deleted', 'loginpress-pro' );
			}
			wp_die();
		}

		/**
		 * Delete Role/s.
		 *
		 * @since 1.0.0
		 * @version 3.0.0
		 *
		 * @return void
		 */
		public function login_redirects_delete_role() {

			check_ajax_referer( 'loginpress-role-redirects-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'No cheating, huh!' );
			}
			if ( isset( $_POST['role'] ) ) {
				$role       = sanitize_text_field( wp_unslash( $_POST['role'] ) );
				$check_role = get_option( 'loginpress_redirects_role' );

				if ( isset( $check_role[ $role ] ) ) {

					$check_role[ $role ] = null;
					$check_role          = array_filter( $check_role );

					update_option( 'loginpress_redirects_role', $check_role, true );
				}
			}
			wp_die();
		}

		/**
		 * Get user meta.
		 *
		 * @param int    $user_id ID of the user.
		 * @param string $option user meta key.
		 * @return string $redirect_url [URL]
		 * @since 3.0.0
		 */
		public function loginpress_get_redirect_url( $user_id, $option ) {

			if ( ! is_multisite() ) {
				$redirect_url = get_user_meta( $user_id, $option, true );
			} else {
				$redirect_url = get_user_option( $option, $user_id );
			}

			return $redirect_url;
		}

		/**
		 * Update user meta.
		 *
		 * @param int    $user_id ID of the user.
		 * @param string $option user meta key.
		 * @param string $value user meta value.
		 * @since 3.0.0
		 */
		public function loginpress_update_redirect_url( $user_id, $option, $value ) {

			if ( ! is_multisite() ) {
				update_user_meta( $user_id, $option, $value );
			} else {
				update_user_option( $user_id, $option, $value, true );
			}
		}

		/**
		 * Delete user meta.
		 *
		 * @param int    $user_id ID of the user.
		 * @param string $option user meta key.
		 * @since 3.0.0
		 */
		public function loginpress_delete_redirect_url( $user_id, $option ) {

			if ( ! is_multisite() ) {
				delete_user_meta( $user_id, $option );
			} else {
				delete_user_option( $user_id, $option, true );
			}
		}

		/**
		 * Main Instance
		 *
		 * @since 3.0.0
		 * @static
		 * @see loginpress_redirect_login_loader()
		 * @return object Main instance of the Class
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	}
endif;
