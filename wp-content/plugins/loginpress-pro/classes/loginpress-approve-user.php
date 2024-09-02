<?php
/**
 * LoginPress approve user functionality
 *
 * @package LoginPress Pro
 */

if ( ! class_exists( 'LoginPress_Approve_user' ) ) :

	/**
	 * Adds the active/inactive functionality for users
	 *
	 * @uses Names meta key [LoginPress_Approve_User ].
	 */
	class LoginPress_Approve_User {

		/**
		 * Users flagged as unapproved.
		 *
		 * @since 3.0.0
		 * @var array $unapproved_users
		 */
		protected $unapproved_users = array();

		/**
		 * The meta key that holds the user active state
		 *
		 * @var string
		 */
		private $loginpress_user_verification = 'loginpress_user_verification';

		/**
		 * Class Constructor
		 */
		public function __construct() {

			if ( is_admin() ) {
				$args = array(
					'meta_key'   => 'loginpress_user_verification', // @codingStandardsIgnoreLine.
					'meta_value' => 'inactive', // @codingStandardsIgnoreLine.
				);

				$users = get_users( $args );
				foreach ( $users as $user => $user_obj ) {
					$this->unapproved_users[] = $user_obj;
				}
			}
			$this->hooks();

		}

		/**
		 * Hooks for active users functionality.
		 *
		 * @since 3.0.0
		 */
		public function hooks() {

			add_action( 'user_row_actions', array( $this, 'loginpress_user_row_actions' ), 10, 2 );
			add_action( 'ms_user_row_actions', array( $this, 'loginpress_user_row_actions' ), 10, 2 );
			add_action( 'wp_authenticate_user', array( $this, 'wp_authenticate_user' ), 10, 1 );
			add_action( 'admin_action_loginpress_inactive_user', array( $this, 'admin_action_loginpress_inactive_user' ) );
			add_action( 'admin_action_loginpress_active_user', array( $this, 'admin_action_loginpress_active_user' ) );
			add_action( 'wp_login_errors', array( $this, 'wp_login_errors' ) );
			add_action( 'login_enqueue_scripts', array( $this, 'hideloginform' ) );
			add_filter( 'bulk_actions-users', array( $this, 'loginpress_user_bulk_actions' ) );

			add_filter( 'pre_get_users', array( $this, 'filter_users' ) );
			add_filter( 'user_register', array( $this, 'set_user_meta_on_registeration' ) );
			add_action( 'register_new_user', array( $this, 'set_user_meta_on_registeration' ), 0 );
			// add_action( 'user_new_form', array( $this, 'set_user_meta_on_registeration' ), 0 );.

			add_filter( 'views_users', array( $this, 'views_users_shortcuts' ) );
			add_filter( 'manage_users_columns', array( $this, 'users_columns' ) );
			add_filter( 'manage_users_custom_column', array( $this, 'user_table_row' ), 10, 3 );

			add_action( 'loginpress_active_user', array( $this, 'loginpress_active_user' ) ); // Refactor.
			add_action( 'delete_user', array( $this, 'delete_user' ) ); // Refactor.
			add_action( 'admin_menu', array( $this, 'admin_menu' ) ); // Decision.
			add_action( 'admin_notices', array( $this, 'approve_users_pending' ) );
			add_filter( 'handle_bulk_actions-users', array( $this, 'loginpress_handle_bulk_actions__users' ), 10, 3 );
		}

		/**
		 * The Bulk action list on Users.php.
		 *
		 * @param array $actions The bulk actions.
		 */
		public function loginpress_user_bulk_actions( $actions ) {
			$custom_actions = array(
				'activate'   => __( 'Activate', 'loginpress-pro' ),
				'deactivate' => __( 'Deactivate', 'loginpress-pro' ),
			);
			$custom_actions = array_merge( $actions, $custom_actions );
			return $custom_actions;
		}

		/**
		 * Handle the Bulk actiions of users.php
		 *
		 * @param URL                $redirect_to The bulk actions.
		 * @param string             $action The bulk action.
		 * @param mixed|string|array $user_ids The user ids.
		 */
		public function loginpress_handle_bulk_actions__users( $redirect_to, $action, $user_ids ) {
			if ( 'activate' === $action ) {
				foreach ( $user_ids as $user_id ) {
					$this->active( $user_id );
				}
			} elseif ( 'deactivate' === $action ) {
				foreach ( $user_ids as $user_id ) {
					$this->inactive( $user_id );
				}
			}
		}

		/**
		 * Update admin notice on approve/rejected user.
		 *
		 * @since 3.0.0
		 */
		public function approve_users_pending() {

			if ( ! current_user_can( 'delete_users' ) || ! current_user_can( 'create_users' ) || ! function_exists( 'get_current_screen' ) ) {
				return;
			}

			$current_screen  = get_current_screen();
			$approvals_count = count( $this->unapproved_users );

			if ( 'users.php' === $current_screen->parent_file && $approvals_count > '0' ) {
				?>
				<div class="notice notice-error">
					<p>
						<?php
							/* Translators: User pending approval from LoginPress */
							echo sprintf( esc_html__( '%1$s  Users\' registration is pending for your approval', 'loginpress-pro' ), $approvals_count );
						?>
					</p>
				</div>
				<?php
			}

			if ( get_transient( 'loginpress_au_rejected' ) ) {
				?>
				<div class="notice notice-error">
					<p>
						<?php
							/* Translators: User Approval from LoginPress */
							esc_html_e( 'User\'s registration approval rejected.', 'loginpress-pro' );
						?>
					</p>
				</div>
				<?php
				delete_transient( 'loginpress_au_rejected' );
			}

			if ( get_transient( 'loginpress_au_accepted' ) ) {
				?>
				<div class="notice updated">
					<p>
						<?php
							/* Translators: User Approval from LoginPress */
							esc_html_e( 'Users\' registration approval accepted.', 'loginpress-pro' );
						?>
					</p>
				</div>
				<?php
				delete_transient( 'loginpress_au_accepted' );
			}
		}

		/**
		 * Filter shortcut links on user edit screen.
		 *
		 * @param array $links Default shortcut links.
		 * @since 3.0.0
		 */
		public function views_users_shortcuts( $links ) {

			$inactive_users = get_users(
				array(
					'meta_query' => array( // @codingStandardsIgnoreLine.
						array(
							'key'     => $this->loginpress_user_verification,
							'value'   => 'inactive',
							'compare' => 'LIKE',
						),
					),
				)
			);

			$inactive_users_count = count( $inactive_users );

			if ( isset( $_GET['user-state'] ) && 'loginpress_inactive_users' === $_GET['user-state'] ) { // @codingStandardsIgnoreLine.

				// On the user-state page, remove the current class from all and add in inactive users.
				$links['all'] = str_replace( ' class="current"', '', $links['all'] );

				/* Translators: User Approval from LoginPress */
				$links['inactive'] = sprintf( esc_html__( '%1$sInactive Users %2$s%3$s%4$s', 'loginpress-pro' ), '<a class="current" href="users.php?user-state=loginpress_inactive_users">', '<span class="count">(', intval( $inactive_users_count ), ')</span></a>' );

			} else {
				/* Translators: User Approval from LoginPress */
				$links['inactive'] = sprintf( esc_html__( '%1$sInactive Users %2$s%3$s%4$s', 'loginpress-pro' ), '<a href="users.php?user-state=loginpress_inactive_users">', '<span class="count">(', intval( $inactive_users_count ), ')</span></a>' );

			}

			return $links;
		}

		/**
		 * Filter users on edit screen for inactive users.
		 *
		 * @param WP_User_Query $query Default user query.
		 * @since 3.0.0
		 */
		public function filter_users( $query ) {

			global $pagenow;

			if ( is_admin() && 'users.php' === $pagenow && isset( $_GET['user-state'] ) && 'loginpress_inactive_users' === $_GET['user-state'] ) { // @codingStandardsIgnoreLine.
				$meta_query = array(
					array(
						'key'     => $this->loginpress_user_verification,
						'value'   => 'inactive',
						'compare' => 'LIKE',
					),
				);
				$query->set( 'meta_query', $meta_query );
			}
		}

		/**
		 * Add the user's row actions to the existing ones.
		 *
		 * @param array  $actions  User action links.
		 * @param object $user_obj User object.
		 *
		 * @return array
		 */
		public function loginpress_user_row_actions( $actions, $user_obj ) {

			if ( get_current_user_id() !== $user_obj->ID && current_user_can( 'edit_user', $user_obj->ID ) ) {

				$site_id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0; // @codingStandardsIgnoreLine.
				$url     = 'site-users-network' === get_current_screen()->id ? add_query_arg( array( 'id' => $site_id ), 'site-users.php' ) : 'users.php';

				$user_state = get_user_meta( $user_obj->ID, $this->loginpress_user_verification, true );

				if ( 'inactive' === $user_state ) {
					$nonce_url = wp_nonce_url(
						add_query_arg(
							array(
								'user'   => $user_obj->ID,
								'action' => 'loginpress_active_user',
							),
							$url
						),
						'loginpress-active-users'
					);

					$actions['loginpress-approve'] = sprintf( '<a class="submitapprove" href="%1$s">%2$s</a>', esc_url( $nonce_url ), esc_html__( 'Active', 'loginpress-pro' ) );
				} else {
					$nonce_url = wp_nonce_url(
						add_query_arg(
							array(
								'user'   => $user_obj->ID,
								'action' => 'loginpress_inactive_user',
							),
							$url
						),
						'loginpress-inactive-users'
					);

					$actions['loginpress-unapprove'] = sprintf( '<a class="submitunapprove" href="%1$s">%2$s</a>', esc_url( $nonce_url ), esc_html__( 'Inactive', 'loginpress-pro' ) );
				}
			}
			return $actions;
		}

		/**
		 * Updates user_meta to active the user.
		 *
		 * @param mixed|array|int $user_id The User ID.
		 * @return void
		 */
		public function active( $user_id = false ) {

			if ( $user_id ) {

				$user_id = isset( $user_id ) && ! empty( $user_id ) ? $user_id : esc_html( $_GET['user'] ); // @codingStandardsIgnoreLine.
				update_user_meta( $user_id, $this->loginpress_user_verification, 'active' );
				set_transient( 'loginpress_au_accepted', true, 5 );
				$this->loginpress_active_user( $user_id );
			}

		}

		/**
		 * Updates user_meta to inactive the user.
		 *
		 * @param mixed|array|int $user_id The User ID.
		 * @return void
		 */
		public function inactive( $user_id = false ) {

			if ( $user_id ) {

				$user_id = isset( $user_id ) && ! empty( $user_id ) ? $user_id : esc_html( $_GET['user'] ); // @codingStandardsIgnoreLine.
				update_user_meta( $user_id, $this->loginpress_user_verification, 'inactive' );
				$this->delete_user( $user_id );
				set_transient( 'loginpress_au_rejected', true, 5 );
			}

		}

		/**
		 * Active the user.
		 *
		 * @return void
		 */
		public function admin_action_loginpress_active_user() {
			$user_id = isset( $_GET['user'] ) && ! empty( $_GET['user'] ) ? intval( $_GET['user'] ) : false;
			check_admin_referer( 'loginpress-active-users' );
			$this->active( $user_id );
			return;
		}

		/**
		 * Inactive the user.
		 *
		 * @return void
		 */
		public function admin_action_loginpress_inactive_user() {

			$user_id = isset( $_GET['user'] ) && ! empty( $_GET['user'] ) ? intval( $_GET['user'] ) : false;
			check_admin_referer( 'loginpress-inactive-users' );
			$this->inactive( $user_id );
			return;
		}

		/**
		 * Add users page column row.
		 *
		 * @param string $output Custom column output.
		 * @param string $column_name Column name.
		 * @param int    $user_id ID of the currently-listed user.
		 *
		 * @since 3.0.0
		 */
		public function user_table_row( $output, $column_name, $user_id ) {

			$user_state = get_user_meta( $user_id, $this->loginpress_user_verification, true );

			if ( 'inactive' === $user_state ) {
				$user_state = __( 'In-active', 'loginpress' );
			} else {
				$user_state = __( 'Active', 'loginpress' );
			}

			switch ( $column_name ) {
				case 'loginpress_user_state':
					return $user_state;
				default:
			}
			return $output;
		}

		/**
		 * Sends the rejection email.
		 *
		 * @param  int $user_id User ID.
		 * @since  3.0.0
		 * @return void
		 */
		public function delete_user( $user_id ) {

			if ( apply_filters( 'loginpress_send_unapprove_email', true ) ) {

				$user     = new WP_User( $user_id );
				$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

				// Send mail.
				wp_mail(
					$user->user_email,
					/* translators: Blog name. */
					apply_filters( 'loginpress_unapprove_email_subject', sprintf( esc_html_x( '[%s] Registration unapproved', 'Blogname', 'loginpress-pro' ), $blogname ) ),
					$this->populate_message( apply_filters( 'loginpress_unapprove_email', $this->default_unapprove_email() ), $user )
				);
			}
			$paged = isset( $_GET['paged'] ) && ! empty( $_GET['paged'] ) ? '?paged=' . $_GET['paged'] : '?paged=' . 1;  // @codingStandardsIgnoreLine.
			wp_safe_redirect( admin_url( '/users.php' . $paged ) );
		}

		/**
		 * Returns the default approve email message.
		 *
		 * @access protected
		 * @since 3.0.0
		 * @return string
		 */
		protected function default_approve_email() {

			/* translators: Username Greetings for Approve mail. */
			$options  = sprintf( __( 'Hi %1$s', 'loginpress-pro' ), 'USERNAME' );
			$options .= PHP_EOL;

			/* translators: Message for Approve mail. */
			$options .= sprintf( __( 'Your registration for %1$s is now approved,', 'loginpress-pro' ), 'BLOG_TITLE' );
			$options .= PHP_EOL;

			/* translators: Message for Approve mail. */
			$options .= sprintf( __( 'You can log in, using your username and password that you created when registering for our website, at the following URL: %1$s', 'loginpress-pro' ), 'LOGINLINK' );
			$options .= PHP_EOL;

			return $options;
		}

		/**
		 * Returns the default unapprove email message.
		 *
		 * @access protected
		 * @since 3.0.0
		 * @return string
		 */
		protected function default_unapprove_email() {

			/* translators: Username Greetings for Approve mail. */
			$options = sprintf( __( 'Hi %1$s, %2$s', 'loginpress-pro' ), 'USERNAME', PHP_EOL );
			/* translators: Username Greetings for Approve mail. */
			$options .= sprintf( __( 'Your request for BLOG_TITLE registration is denied. %1$s', 'loginpress-pro' ), PHP_EOL );

			return $options;
		}

		/**
		 * Set User meta on registration
		 *
		 * @param int $user_id The User ID.
		 *
		 * @return int User ID
		 */
		public function set_user_meta_on_registeration( $user_id ) {

			update_user_meta( $user_id, $this->loginpress_user_verification, 'inactive' );
			return $user_id;
		}

		/**
		 * Enhances the User menu item to enable counter of unapproved users.
		 *
		 * @since  3.0.0
		 * @return void
		 */
		public function admin_menu() {

			if ( current_user_can( 'list_users' ) && version_compare( get_bloginfo( 'version' ), '3.2', '>=' ) ) {

				global $menu;
				foreach ( $menu as $key => $menu_item ) {
					if ( array_search( 'users.php', $menu_item, true ) ) {

						$unapproved_count = count( $this->unapproved_users );
						$menu[ $key ][0] .= " <span class='loginpress-menu-user-count update-plugins count-{$unapproved_count}'><span class='plugin-count'>{$unapproved_count}</span></span>"; // @codingStandardsIgnoreLine.
						break;
					}
				}
			}
		}

		/**
		 * Checks whether the user is approved. Throws error if not.
		 *
		 * @param  WP_User|WP_Error $user User object.
		 * @return WP_User|WP_Error
		 * @since  3.0.0
		 */
		public function wp_authenticate_user( $user ) {

			$user_approve = false;
			$user_state   = get_user_meta( $user->ID, $this->loginpress_user_verification, true );

			if ( 'inactive' === $user_state ) {
				$user_approve = true;
			}

			if ( ! is_wp_error( $user ) && $user_approve && get_bloginfo( 'admin_email' ) !== $user->user_email ) {
				$user = new WP_Error( 'loginpress_unapprove_error', wp_kses_post( apply_filters( 'loginpress_unapproved_error_message', __( '<strong>ERROR:</strong> Your account has to be confirmed by an administrator before you can login.', 'loginpress-pro' ) ) ) );
			}

			return $user;
		}

		/**
		 * Add new column on users edit screen.
		 *
		 * @param string $column user state.
		 * @since 3.0.0
		 *
		 * @return string $column user state
		 */
		public function users_columns( $column ) {

			$column['loginpress_user_state'] = esc_html__( 'User Status', 'loginpress-pro' );
			return $column;
		}

		/**
		 * Filters the login page errors.
		 *
		 * @param  WP_Error $errors WP Error object.
		 * @return WP_Error
		 * @since  3.0.0
		 */
		public function wp_login_errors( $errors ) {

			if ( in_array( 'registered', $errors->get_error_codes(), true ) ) {
				/* Translators: Email Sent notification. */
				$message = apply_filters( 'loginpress_approval_info_msg', sprintf( __( 'Registration successful.%1$s%1$s An email has been sent to the site administrator. The administrator will review the information that has been submitted and either approved or deny your request. You will receive an email with instructions on what you will need to do next.%1$s Thanks for your patience.', 'loginpress-pro' ), '</br>' ) );

				$errors->remove( 'registered' );
				$errors->add( 'registered', $message, 'message' );
			}

			return $errors;
		}

		/**
		 * Hide the login form for focusing on email notification.
		 *
		 * @since 3.0.0
		 */
		public function hideloginform() {

			if ( isset( $_GET['checkemail'] ) && 'registered' === $_GET['checkemail'] ) { // @codingStandardsIgnoreLine.
				echo '<style>#loginform, #nav { display:none !important; }</style>';
			}
		}

		/**
		 * Sends the approval email.
		 *
		 * @param  int $user_id User ID.
		 * @since  3.0.0
		 * @return void
		 */
		public function loginpress_active_user( $user_id ) {

			wp_new_user_notification( $user_id, null, 'user' );

			// Check user meta if mail has been sent already.
			if ( apply_filters( 'loginpress_send_approve_email', true ) ) {

				$user     = new WP_User( $user_id );
				$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

				// Send mail.
				$sent = wp_mail(
					$user->user_email,
					/* translators: Blog name. */
					apply_filters( 'loginpress_approve_email_subject', sprintf( esc_html_x( '[%s] Registration approved', 'Blogname', 'loginpress-pro' ), $blogname ) ),
					$this->populate_message( apply_filters( 'loginpress_approve_email', $this->default_approve_email() ), $user )
				);

			}
			$paged = isset( $_GET['paged'] ) && ! empty( $_GET['paged'] ) ? '?paged=' . $_GET['paged'] : '?paged=' . 1;  // @codingStandardsIgnoreLine.

			wp_safe_redirect( admin_url( '/users.php' . $paged ) );
		}


		/**
		 * Replaces all the placeholders with their content.
		 *
		 * @param  string  $message Email body.
		 * @param  WP_User $user    User object.
		 * @since  3.0.0
		 *
		 * @return string
		 */
		protected function populate_message( $message, $user ) {

			$placeholders = array(
				'BLOG_TITLE' => wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ),
				'BLOG_URL'   => home_url(),
				'LOGINLINK'  => wp_login_url(),
				'USERNAME'   => $user->user_nicename,
			);

			if ( is_multisite() ) {
				$placeholders['SITE_NAME'] = $GLOBALS['current_site']->site_name;
			}

			/**
			 * Filters the placeholders in approve/unapprove emails.
			*
			* @since 3.0.0
			*
			* @param array   $placeholders Key => Value pair of placeholders and the value they're replaced with.
			* @param string  $message      Message that will have its placeholders replaced. Note: This will not change the message.
			*                              Use `option_wp-approve-user` to filter message bodies.
			* @param WP_User $user         WP_User object of the user being approved/unapproved.
			*/
			$placeholders = apply_filters( 'loginpress_approve_user_placeholders', $placeholders, $message, $user );

			foreach ( $placeholders as $placeholder => $replacement ) {
				$message = str_replace( $placeholder, $replacement, $message );
			}

			return $message;
		}
	}

endif;