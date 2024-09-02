<?php

namespace Elementor;


use ElementsKit\Firebase\JWT\JWT;
use ElementsKit_Lite\Libs\Framework\Attr;

class ElementsKit_Widget_Zoom_Handler extends \ElementsKit_Lite\Core\Handler_Widget {

	public static $token_url = 'https://zoom.us/oauth/token/';
	public static $api_url = 'https://api.zoom.us/v2/';


	static function get_name() {
		return 'elementskit-zoom';
	}


	static function get_title() {
		return esc_html__('Zoom', 'elementskit');
	}


	static function get_icon() {
		return ' ekit-widget-icon eicon-button';
	}


	static function get_categories() {
		return ['elementskit'];
	}

	static function get_keywords() {
		return ['ekit', 'zoom', 'meeting', 'online meeting', 'virtual meeting', 'zoom event'];
	}

	static function get_dir() {
		return \ElementsKit::widget_dir() . 'zoom/';
	}


	static function get_url() {
		return \ElementsKit::widget_url() . 'zoom/';
	}

	public function register_api() {

		new \ElementsKit\Widgets\Zoom\Zoom_Api();
	}


	static function get_data() {
		$data = Attr::instance()->utils->get_option('user_data', []);

		$account_id = (isset($data['zoom']) && !empty($data['zoom']['account_id'])) ? $data['zoom']['account_id'] : '';
		$client_id = (isset($data['zoom']) && !empty($data['zoom']['client_id'])) ? $data['zoom']['client_id'] : '';
		$client_secret = (isset($data['zoom']) && !empty($data['zoom']['client_secret'])) ? $data['zoom']['client_secret'] : '';

		return [
			'account_id'    => $account_id,
			'client_id'    => $client_id,
			'client_secret' => $client_secret,
		];
	}

	/**
	 * Call api function
	 *
	 * @param $called_function
	 * @param $data
	 * @param $request
	 *s
	 * @return bool|string
	 */
	private static function make_api_request($url, $method, $headers, $body) {
		$args = [
			'method' => $method,
			'headers' => $headers,
			'body' => $body,
		];

		$response = wp_remote_request($url, $args);

		if (is_wp_error($response)) {
			return $response->get_error_message();
		}

		$body = wp_remote_retrieve_body($response);

		return json_decode($body, true);
	}

	/**
	 * Generate token
	 */
	private static function generate_token() {
		$config = self::get_data();

		$client_id_secret = $config['client_id'] . ':' . $config['client_secret'];
		$encoded_client_id_secret = base64_encode($client_id_secret);

		$headers = array(
			'Content-Type' => 'application/x-www-form-urlencoded',
			'Authorization' => 'Basic ' . $encoded_client_id_secret,
		);

		$body = 'grant_type=account_credentials&account_id=' . $config['account_id'];

		$response = self::make_api_request(self::$token_url, 'POST', $headers, $body);

		return $response && isset($response['access_token']) ? $response['access_token'] : null;
	}

	public static function get_timezone() {
		$zones_array = array(
			"Pacific/Midway"                 => "(GMT-11:00) Midway Island, Samoa ",
			"Pacific/Pago_Pago"              => "(GMT-11:00) Pago Pago ",
			"Pacific/Honolulu"               => "(GMT-10:00) Hawaii ",
			"America/Anchorage"              => "(GMT-8:00) Alaska ",
			"America/Vancouver"              => "(GMT-7:00) Vancouver ",
			"America/Los_Angeles"            => "(GMT-7:00) Pacific Time (US and Canada) ",
			"America/Tijuana"                => "(GMT-7:00) Tijuana ",
			"America/Phoenix"                => "(GMT-7:00) Arizona ",
			"America/Edmonton"               => "(GMT-6:00) Edmonton ",
			"America/Denver"                 => "(GMT-6:00) Mountain Time (US and Canada) ",
			"America/Mazatlan"               => "(GMT-6:00) Mazatlan ",
			"America/Regina"                 => "(GMT-6:00) Saskatchewan ",
			"America/Guatemala"              => "(GMT-6:00) Guatemala ",
			"America/El_Salvador"            => "(GMT-6:00) El Salvador ",
			"America/Managua"                => "(GMT-6:00) Managua ",
			"America/Costa_Rica"             => "(GMT-6:00) Costa Rica ",
			"America/Tegucigalpa"            => "(GMT-6:00) Tegucigalpa ",
			"America/Winnipeg"               => "(GMT-5:00) Winnipeg ",
			"America/Chicago"                => "(GMT-5:00) Central Time (US and Canada) ",
			"America/Mexico_City"            => "(GMT-5:00) Mexico City ",
			"America/Panama"                 => "(GMT-5:00) Panama ",
			"America/Bogota"                 => "(GMT-5:00) Bogota ",
			"America/Lima"                   => "(GMT-5:00) Lima ",
			"America/Caracas"                => "(GMT-4:30) Caracas ",
			"America/Montreal"               => "(GMT-4:00) Montreal ",
			"America/New_York"               => "(GMT-4:00) Eastern Time (US and Canada) ",
			"America/Indianapolis"           => "(GMT-4:00) Indiana (East) ",
			"America/Puerto_Rico"            => "(GMT-4:00) Puerto Rico ",
			"America/Santiago"               => "(GMT-4:00) Santiago ",
			"America/Halifax"                => "(GMT-3:00) Halifax ",
			"America/Montevideo"             => "(GMT-3:00) Montevideo ",
			"America/Araguaina"              => "(GMT-3:00) Brasilia ",
			"America/Argentina/Buenos_Aires" => "(GMT-3:00) Buenos Aires, Georgetown ",
			"America/Sao_Paulo"              => "(GMT-3:00) Sao Paulo ",
			"Canada/Atlantic"                => "(GMT-3:00) Atlantic Time (Canada) ",
			"America/St_Johns"               => "(GMT-2:30) Newfoundland and Labrador ",
			"America/Godthab"                => "(GMT-2:00) Greenland ",
			"Atlantic/Cape_Verde"            => "(GMT-1:00) Cape Verde Islands ",
			"Atlantic/Azores"                => "(GMT+0:00) Azores ",
			"UTC"                            => "(GMT+0:00) Universal Time UTC ",
			"Etc/Greenwich"                  => "(GMT+0:00) Greenwich Mean Time ",
			"Atlantic/Reykjavik"             => "(GMT+0:00) Reykjavik ",
			"Africa/Nouakchott"              => "(GMT+0:00) Nouakchott ",
			"Europe/Dublin"                  => "(GMT+1:00) Dublin ",
			"Europe/London"                  => "(GMT+1:00) London ",
			"Europe/Lisbon"                  => "(GMT+1:00) Lisbon ",
			"Africa/Casablanca"              => "(GMT+1:00) Casablanca ",
			"Africa/Bangui"                  => "(GMT+1:00) West Central Africa ",
			"Africa/Algiers"                 => "(GMT+1:00) Algiers ",
			"Africa/Tunis"                   => "(GMT+1:00) Tunis ",
			"Europe/Belgrade"                => "(GMT+2:00) Belgrade, Bratislava, Ljubljana ",
			"CET"                            => "(GMT+2:00) Sarajevo, Skopje, Zagreb ",
			"Europe/Oslo"                    => "(GMT+2:00) Oslo ",
			"Europe/Copenhagen"              => "(GMT+2:00) Copenhagen ",
			"Europe/Brussels"                => "(GMT+2:00) Brussels ",
			"Europe/Berlin"                  => "(GMT+2:00) Amsterdam, Berlin, Rome, Stockholm, Vienna ",
			"Europe/Amsterdam"               => "(GMT+2:00) Amsterdam ",
			"Europe/Rome"                    => "(GMT+2:00) Rome ",
			"Europe/Stockholm"               => "(GMT+2:00) Stockholm ",
			"Europe/Vienna"                  => "(GMT+2:00) Vienna ",
			"Europe/Luxembourg"              => "(GMT+2:00) Luxembourg ",
			"Europe/Paris"                   => "(GMT+2:00) Paris ",
			"Europe/Zurich"                  => "(GMT+2:00) Zurich ",
			"Europe/Madrid"                  => "(GMT+2:00) Madrid ",
			"Africa/Harare"                  => "(GMT+2:00) Harare, Pretoria ",
			"Europe/Warsaw"                  => "(GMT+2:00) Warsaw ",
			"Europe/Prague"                  => "(GMT+2:00) Prague Bratislava ",
			"Europe/Budapest"                => "(GMT+2:00) Budapest ",
			"Africa/Tripoli"                 => "(GMT+2:00) Tripoli ",
			"Africa/Cairo"                   => "(GMT+2:00) Cairo ",
			"Africa/Johannesburg"            => "(GMT+2:00) Johannesburg ",
			"Europe/Helsinki"                => "(GMT+3:00) Helsinki ",
			"Africa/Nairobi"                 => "(GMT+3:00) Nairobi ",
			"Europe/Sofia"                   => "(GMT+3:00) Sofia ",
			"Europe/Istanbul"                => "(GMT+3:00) Istanbul ",
			"Europe/Athens"                  => "(GMT+3:00) Athens ",
			"Europe/Bucharest"               => "(GMT+3:00) Bucharest ",
			"Asia/Nicosia"                   => "(GMT+3:00) Nicosia ",
			"Asia/Beirut"                    => "(GMT+3:00) Beirut ",
			"Asia/Damascus"                  => "(GMT+3:00) Damascus ",
			"Asia/Jerusalem"                 => "(GMT+3:00) Jerusalem ",
			"Asia/Amman"                     => "(GMT+3:00) Amman ",
			"Europe/Moscow"                  => "(GMT+3:00) Moscow ",
			"Asia/Baghdad"                   => "(GMT+3:00) Baghdad ",
			"Asia/Kuwait"                    => "(GMT+3:00) Kuwait ",
			"Asia/Riyadh"                    => "(GMT+3:00) Riyadh ",
			"Asia/Bahrain"                   => "(GMT+3:00) Bahrain ",
			"Asia/Qatar"                     => "(GMT+3:00) Qatar ",
			"Asia/Aden"                      => "(GMT+3:00) Aden ",
			"Africa/Khartoum"                => "(GMT+3:00) Khartoum ",
			"Africa/Djibouti"                => "(GMT+3:00) Djibouti ",
			"Africa/Mogadishu"               => "(GMT+3:00) Mogadishu ",
			"Europe/Kiev"                    => "(GMT+3:00) Kiev ",
			"Asia/Dubai"                     => "(GMT+4:00) Dubai ",
			"Asia/Muscat"                    => "(GMT+4:00) Muscat ",
			"Asia/Tehran"                    => "(GMT+4:30) Tehran ",
			"Asia/Kabul"                     => "(GMT+4:30) Kabul ",
			"Asia/Baku"                      => "(GMT+5:00) Baku, Tbilisi, Yerevan ",
			"Asia/Yekaterinburg"             => "(GMT+5:00) Yekaterinburg ",
			"Asia/Tashkent"                  => "(GMT+5:00) Islamabad, Karachi, Tashkent ",
			"Asia/Calcutta"                  => "(GMT+5:30) India ",
			"Asia/Kolkata"                   => "(GMT+5:30) Mumbai, Kolkata, New Delhi ",
			"Asia/Kathmandu"                 => "(GMT+5:45) Kathmandu ",
			"Asia/Novosibirsk"               => "(GMT+6:00) Novosibirsk ",
			"Asia/Almaty"                    => "(GMT+6:00) Almaty ",
			"Asia/Dacca"                     => "(GMT+6:00) Dacca ",
			"Asia/Dhaka"                     => "(GMT+6:00) Astana, Dhaka ",
			"Asia/Krasnoyarsk"               => "(GMT+7:00) Krasnoyarsk ",
			"Asia/Bangkok"                   => "(GMT+7:00) Bangkok ",
			"Asia/Saigon"                    => "(GMT+7:00) Vietnam ",
			"Asia/Jakarta"                   => "(GMT+7:00) Jakarta ",
			"Asia/Irkutsk"                   => "(GMT+8:00) Irkutsk, Ulaanbaatar ",
			"Asia/Shanghai"                  => "(GMT+8:00) Beijing, Shanghai ",
			"Asia/Hong_Kong"                 => "(GMT+8:00) Hong Kong ",
			"Asia/Taipei"                    => "(GMT+8:00) Taipei ",
			"Asia/Kuala_Lumpur"              => "(GMT+8:00) Kuala Lumpur ",
			"Asia/Singapore"                 => "(GMT+8:00) Singapore ",
			"Australia/Perth"                => "(GMT+8:00) Perth ",
			"Asia/Yakutsk"                   => "(GMT+9:00) Yakutsk ",
			"Asia/Seoul"                     => "(GMT+9:00) Seoul ",
			"Asia/Tokyo"                     => "(GMT+9:00) Osaka, Sapporo, Tokyo ",
			"Australia/Darwin"               => "(GMT+9:30) Darwin ",
			"Australia/Adelaide"             => "(GMT+9:30) Adelaide ",
			"Asia/Vladivostok"               => "(GMT+10:00) Vladivostok ",
			"Pacific/Port_Moresby"           => "(GMT+10:00) Guam, Port Moresby ",
			"Australia/Brisbane"             => "(GMT+10:00) Brisbane ",
			"Australia/Sydney"               => "(GMT+10:00) Canberra, Melbourne, Sydney ",
			"Australia/Hobart"               => "(GMT+10:00) Hobart ",
			"Asia/Magadan"                   => "(GMT+10:00) Magadan ",
			"SST"                            => "(GMT+11:00) Solomon Islands ",
			"Pacific/Noumea"                 => "(GMT+11:00) New Caledonia ",
			"Asia/Kamchatka"                 => "(GMT+12:00) Kamchatka ",
			"Pacific/Fiji"                   => "(GMT+12:00) Fiji Islands, Marshall Islands ",
			"Pacific/Auckland"               => "(GMT+12:00) Auckland, Wellington",
		);

		return $zones_array;
	}

	/**
	 *
	 *
	 * @return array
	 */
	public static function get_hosts() {
		$ok = '_ekit_elm_widget_zoom_hosts';

		$access_token = self::generate_token();

		if ($access_token) {
			$headers = array(
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $access_token,
			);

			$response = self::make_api_request(self::$api_url . '/users', 'GET', $headers, ['page_size' => 300]);

			if ($response && isset($response['users'])) {
				$output = [];
				if(!empty($response['users'])) {
					$users = $response['users'];
					foreach($users as $user) {
						$output[$user['id']] = "{$user['first_name']} {$user['last_name']}";
					}
				}
		
				if(empty($output)) {
					$msg  = __('Nothing returned from zoom!', 'elementskit');
					$flag = false;
				} else {
					update_option($ok, $output);
		
					$msg  = __('Successfully connected and cached.', 'elementskit');
					$flag = true;
				}
		
		
				return [
					'success' => $flag,
					'message' => $msg,
					'fetched' => $output,
				];
			}
		}

		return [
			'success' => false,
			'message' => 'Failed to obtain hosts list.',
			'fetched' => '',
		];
	}

	public static function get_cached_hosts() {

		$ok = '_ekit_elm_widget_zoom_hosts';

		$data = get_option($ok, []);

		if(empty($data)) {

			self::get_hosts();
		}

		return get_option($ok, []);
	}

	public static function create_meeting($request_data) {
		$request_data['start_time'] = gmdate("Y-m-d\TH:i:s", strtotime($request_data['start_time']));

		if(empty($request_data['meeting_id'])) {
			$user_id = $request_data['user_id'];
			$endpoint     = 'users/' . $user_id . '/meetings';
			$method    = 'POST';

			unset($request_data['user_id']);
		} else {
			$meeting_id = $request_data['meeting_id'];
			$endpoint        = 'meetings/' . $meeting_id . '/meetings';
			$method       = 'PATCH';
		}

		$flag = false;
		$response = [];
		$access_token = self::generate_token();
		if ($access_token) {
			$headers = array(
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $access_token,
			);

			$body = $request_data;

			$response = self::make_api_request(self::$api_url . $endpoint, $method, $headers, json_encode($body));

			if (!empty($response) && isset($response['id'])) {
				$flag = true;
				$msg = sprintf(__('Meeting created successfully. Meeting ID: %s', 'elementskit'), $response['id']);
				$response = json_encode($response, false);
			} elseif ($response && isset($response['message'])) {
				$msg = sprintf(esc_html__('Error: %s - %s', 'elementskit'), $response['code'], $response['message']);
			}

			return [
				'success' => $flag,
				'message' => !empty($msg) ? $msg : esc_html__('Some thing went wrong.', 'elementskit'),
				'fetched' => $response,
				'debug'   => $request_data,
			];
		}

		return [
			'success' => false,
			'message' => esc_html__('Failed to obtain access token.', 'elementskit'),
			'fetched' => '',
		];
	}
}