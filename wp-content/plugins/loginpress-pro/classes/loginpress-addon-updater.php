<?php
/**
 * Addon Updater
 *
 * @package LoginPress Pro
 */

/**
 * LoginPress_AddOn_Updater
 */
class LoginPress_AddOn_Updater {
	/**
	 * APU url
	 *
	 * @var string
	 */
	private $api_url = '';

	/**
	 * API Data
	 *
	 * @var array
	 */
	private $api_data = array();

	/**
	 * Addon ID
	 *
	 * @var string
	 */
	private $addon_id = '';

	/**
	 * Name of the addon
	 *
	 * @var string
	 */
	private $name = '';

	/**
	 * Slug of the addon
	 *
	 * @var string
	 */
	private $slug = '';

	/**
	 * Version of the addon
	 *
	 * @var string
	 */
	private $version = '';

	/**
	 * Class constructor.
	 *
	 * @uses plugin_basename()
	 * @uses hook()
	 *
	 * @param string $_addon_id The URL pointing to the custom API endpoint..
	 * @param string $_plugin_file Path to the plugin file.
	 * @param array  $_version Optional data to send with API calls.
	 *
	 * @return void
	 */
	public function __construct( $_addon_id, $_plugin_file, $_version ) {

		$this->api_url  = 'https://wpbrigade.com/';
		$this->addon_id = $_addon_id;
		$this->name     = plugin_basename( $_plugin_file );
		$this->slug     = basename( $_plugin_file, '.php' );
		$this->version  = $_version;

		// Set up hooks.
		$this->hook();
	}
	/**
	 * Set up WordPress filters to hook into WP's update process.
	 *
	 * @uses add_filter()
	 *
	 * @return void
	 */
	private function hook() {

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );
		add_filter( 'plugins_api', array( $this, 'plugins_api_filter' ), 10, 3 );
		add_action( 'after_plugin_row_' . $this->name, array( $this, 'show_update_notification' ), 10, 2 );
		add_action( 'admin_init', array( $this, 'show_changelog' ) );
	}

	/**
	 * Check for Updates at the defined API endpoint and modify the update array.
	 *
	 * This function dives into the update api just when WordPress creates its update array,
	 * then adds a custom API call and injects the custom plugin data retrieved from the API.
	 * It is reassembled from parts of the native WordPress plugin update code.
	 * See wp-includes/update.php line 121 for the original wp_update_plugins() function.
	 *
	 * @uses api_request()
	 *
	 * @param array $_transient_data Update array build by WordPress.
	 * @return array Modified update array with custom plugin data.
	 */
	public function check_update( $_transient_data ) {

		global $pagenow;
		if ( 'plugins.php' === $pagenow && is_multisite() ) {
			return $_transient_data;
		}
		if ( ! is_object( $_transient_data ) ) {
			$_transient_data = new stdClass();
		}
		if ( empty( $_transient_data->response ) || empty( $_transient_data->response[ $this->name ] ) ) {
			$api_response = $this->api_request( 'plugin_latest_version', array( 'slug' => $this->slug ) );
			if ( false !== $api_response && is_object( $api_response ) && isset( $api_response->new_version ) ) {
				if ( version_compare( $this->version, $api_response->new_version, '<' ) ) {
					$_transient_data->response[ $this->name ] = $api_response;
				}
			}
			$_transient_data->last_checked           = time();
			$_transient_data->checked[ $this->name ] = $this->version;
		}

		return $_transient_data;
	}

	/**
	 * Updates information on the "View version x.x details" page with custom data.
	 *
	 * @uses api_request()
	 *
	 * @param mixed  $_data API Data.
	 * @param string $_action Action to perform.
	 * @param object $_args Arguments of API filtering.
	 *
	 * @return object $_data API Data
	 */
	public function plugins_api_filter( $_data, $_action = '', $_args = null ) {

		if ( ( 'plugin_information' !== $_action ) || ! isset( $_args->slug ) || ( $_args->slug !== $this->slug ) ) {
			return $_data;
		}
		$to_send      = array( 'slug' => $this->slug );
		$api_response = $this->api_request( 'plugin_information', $to_send );
		if ( false !== $api_response ) {
			$_data = $api_response;
		}
		return $_data;
	}

	/**
	 * Show update notification row -- needed for multisite or subsites, because WP won't tell you otherwise!
	 *
	 * @param string $file The File.
	 * @param string $plugin The Plugin.
	 *
	 * @return void
	 */
	public function show_update_notification( $file, $plugin ) {

		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}
		if ( ! is_multisite() || is_network_admin() ) {
			return;
		}
		if ( $this->name !== $file ) {
			return;
		}

		// Remove our filter on the site transient.
		remove_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ), 10 );
		$update_cache = get_site_transient( 'update_plugins' );
		if ( ! is_object( $update_cache ) || empty( $update_cache->response ) || empty( $update_cache->response[ $this->name ] ) ) {
			$cache_key    = md5( 'loginpress_plugin_' . sanitize_key( $this->name ) . '_version_info' );
			$version_info = get_transient( $cache_key );
			if ( false === $version_info ) {
				$version_info = $this->api_request( 'plugin_latest_version', array( 'slug' => $this->slug ) );
				set_transient( $cache_key, $version_info, HOUR_IN_SECONDS );
			}
			if ( ! is_object( $version_info ) ) {
				return;
			}
			if ( version_compare( $this->version, $version_info->new_version, '<' ) ) {
				$update_cache->response[ $this->name ] = $version_info;
			}
			$update_cache->last_checked           = time();
			$update_cache->checked[ $this->name ] = $this->version;
			set_site_transient( 'update_plugins', $update_cache );
		} else {
			$version_info = $update_cache->response[ $this->name ];
		}
		if ( ! empty( $update_cache->response[ $this->name ] ) && version_compare( $this->version, $update_cache->response[ $this->name ]->new_version, '<' ) ) {
			// build a plugin list row, with update notification.
			$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );
			echo '<tr class="plugin-update-tr" id="' . esc_html( $this->slug ) . '-update" data-slug="' . esc_html( $this->slug ) . '" data-plugin="' . esc_html( $this->slug ) . '/' . esc_html( $this->name ) . '">';
			echo '<td colspan="3" class="plugin-update colspanchange">';
			echo '<div class="update-message notice inline notice-warning notice-alt"><p>';
			$changelog_link = self_admin_url( 'index.php?loginpress_action=view_plugin_changelog&plugin=' . $this->name . '&slug=' . $this->slug . '&addon_id=' . $this->addon_id . '&TB_iframe=true&width=772&height=911' );
			if ( empty( $version_info->download_link ) ) {
				printf(
					/* Translators: New version string */
					esc_html__( 'There is a new version of %1$s available. <a target="_blank" class="thickbox" href="%2$s">View version %3$s details</a>.', 'loginpress-pro' ),
					esc_html( $version_info->name ),
					esc_url( $changelog_link ),
					esc_html( $version_info->new_version )
				);
			} else {
				printf(
					/* Translators: New version string */
					esc_html__( 'There is a new version of %1$s available. <a target="_blank" class="thickbox" href="%2$s">View version %3$s details</a> or <a href="%4$s">update now</a>.', 'loginpress-pro' ),
					esc_html( $version_info->name ),
					esc_url( $changelog_link ),
					esc_html( $version_info->new_version ),
					esc_url( wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $this->name, 'upgrade-plugin_' . $this->name ) )
				);
			}
			echo '</p></div></td></tr>';
		}
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );
	}
	/**
	 * Calls the API and, if successful, returns the object delivered by the API.
	 *
	 * @uses get_bloginfo()
	 * @uses wp_remote_post()
	 * @uses is_wp_error()
	 *
	 * @param string $_action The requested action.
	 * @param array  $_data Parameters for the API action.
	 *
	 * @return false||object
	 */
	private function api_request( $_action, $_data ) {

		global $wp_version;
		$data = $_data;

		$data['license'] = get_option( 'loginpress_pro_license_key' );

		if ( empty( $data['license'] ) ) {
			return;
		}
		if ( empty( $data['addon_id'] ) ) {
			$data['addon_id'] = $this->addon_id;
		}
		if ( empty( $data['addon_id'] ) ) {
			return;
		}
		$api_params = array(
			'loginpress_action' => 'get_version',
			'license'           => $data['license'],
			'id'                => $data['addon_id'],
			'slug'              => $data['slug'],
			'url'               => home_url(),
		);
		$request    = wp_remote_post(
			$this->api_url,
			array(
				'timeout' => 15,
				'body'    => $api_params,
			)
		);

		if ( ! is_wp_error( $request ) ) {
			$request = json_decode( wp_remote_retrieve_body( $request ) );
			if ( $request && isset( $request->sections ) ) {
				$request->sections = maybe_unserialize( $request->sections );
			}
			return $request;
		} else {
			return false;
		}
	}

	/**
	 * Show the Changelog
	 *
	 * @return void
	 */
	public function show_changelog() {

		if ( empty( $_REQUEST['loginpress_action'] ) || 'view_plugin_changelog' != $_REQUEST['loginpress_action'] ) {  // @codingStandardsIgnoreLine.
			return;
		}
		if ( empty( $_REQUEST['plugin'] ) ) { // @codingStandardsIgnoreLine.
			return;
		}
		if ( empty( $_REQUEST['slug'] ) ) { // @codingStandardsIgnoreLine.
			return;
		}
		if ( ! current_user_can( 'update_plugins' ) ) {
			wp_die( esc_html__( 'You do not have permission to install plugin updates', 'loginpress-pro' ), esc_html__( 'Error', 'loginpress-pro' ), array( 'response' => 403 ) );
		}
		$response = $this->api_request(
			'plugin_latest_version',
			array(
				'slug'     => $_REQUEST['slug'], // @codingStandardsIgnoreLine.
				'addon_id' => $_REQUEST['addon_id'], // @codingStandardsIgnoreLine.
			)
		);
		if ( $response && isset( $response->sections['changelog'] ) ) {
			echo '<div style="background:#fff;padding:10px;height:100%;">' . $response->sections['changelog'] . '</div>'; // @codingStandardsIgnoreLine.
		}
		exit;
	}
}
