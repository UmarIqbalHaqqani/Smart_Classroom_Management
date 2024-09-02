<?php
defined('ABSPATH') || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Setting.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Config.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/textlocal.class.php';

class WLSM_SMS {
	public static function sms_carriers() {
		return array(
			'textlocal'       => esc_html__('Textlocal ( International )', 'school-management'),
			// 'nexmo'           => esc_html__('Nexmo ( International )', 'school-management'),
			'twilio'          => esc_html__('Twilio ( International)', 'school-management'),
			'msgclub'         => esc_html__('Intechno Msg ( Weblizar india)', 'school-management'),
			'pointsms'        => esc_html__('Intechno Point ( Weblizar india)', 'school-management'),
			'msg91'           => esc_html__('Msg91 (India)', 'school-management'),
			'smsstriker'      => esc_html__('SMS Striker ( India )', 'school-management'),
			'indiatext'       => esc_html__('India Text ( India )', 'school-management'),
			'gatewaysms'      => esc_html__('Gatewaysms ( India )', 'school-management'),
			'bulksmsgateway'  => esc_html__('Bulksmsgateway ( India )', 'school-management'),
			'ebulksms'        => esc_html__('EBulkSMS ( Nigeria )', 'school-management'),
			'pob'             => esc_html__('Pob Talk ( Nigeria )', 'school-management'),
			'kivalosolutions' => esc_html__('Kivalosolutions SMS ( Ghana)', 'school-management'),
			'sendpk'          => esc_html__('SendPK ( Pakistan )', 'school-management'),
			'smartsms'        => esc_html__('Smartsms(Pakistan)', 'school-management'),
			'futuresol'       => esc_html__('SMS Portal Futuresol ( Pakistan )', 'school-management'),
			'vinuthan'        => esc_html__('Vinuthan', 'school-management'),
			'logixsms'        => esc_html__('Logixsms', 'school-management'),
			'bdbsms'          => esc_html__('bdbulksms', 'school-management'),
			'nextsms'         => esc_html__('nextsms', 'school-management'),
			'whatsapp'        => esc_html__('Whatsapp API (Intechno)', 'school-management'),
			'sms_ir'          => esc_html__('SMS IR  (iran)', 'school-management'),
			'tecxsms'         => esc_html__('tecxsms (ghana)', 'school-management'),
			'switchportlimited'      => esc_html__('switchportlimited (nigeria)', 'school-management'),
		);
	}

	public static function send_sms($school_id, $to, $message, $template_id , $sms_for = '', $placeholders = array()) {
		if (!empty($sms_for) && count($placeholders)) {
			if ('student_admission' === $sms_for) {
				$available_placeholders = array_keys(self::student_admission_placeholders());
			} elseif ('invoice_generated' === $sms_for) {
				$available_placeholders = array_keys(self::invoice_generated_placeholders());
			} elseif ('online_fee_submission' === $sms_for) {
				$available_placeholders = array_keys(self::online_fee_submission_placeholders());
			} elseif ('offline_fee_submission' === $sms_for) {
				$available_placeholders = array_keys(self::offline_fee_submission_placeholders());
			} elseif ('student_admission_to_parent' === $sms_for) {
				$available_placeholders = array_keys(self::student_admission_to_parent_placeholders());
			} elseif ('invoice_generated_to_parent' === $sms_for) {
				$available_placeholders = array_keys(self::invoice_generated_to_parent_placeholders());
			} elseif ('online_fee_submission_to_parent' === $sms_for) {
				$available_placeholders = array_keys(self::online_fee_submission_to_parent_placeholders());
			} elseif ('offline_fee_submission_to_parent' === $sms_for) {
				$available_placeholders = array_keys(self::offline_fee_submission_to_parent_placeholders());
			} elseif ('absent_student' === $sms_for) {
				$available_placeholders = array_keys(self::absent_student_placeholders());
			} elseif ('custom_message' === $sms_for) {
				$available_placeholders = array_keys(self::custom_message_placeholders());
			} elseif ('inquiry_received_to_inquisitor' === $sms_for) {
				$available_placeholders = array_keys(self::inquiry_received_to_inquisitor_placeholders());
			} elseif ('inquiry_received_to_admin' === $sms_for) {
				$available_placeholders = array_keys(self::inquiry_received_to_admin_placeholders());
			} elseif ('student_registration_to_student' === $sms_for) {
				$available_placeholders = array_keys(self::student_registration_to_student_placeholders());
			} elseif ('student_registration_to_admin' === $sms_for) {
				$available_placeholders = array_keys(self::student_registration_to_admin_placeholders());
			}

			if (isset($available_placeholders)) {
				foreach ($placeholders as $key => $value) {
					if (in_array($key, $available_placeholders)) {
						$message = str_replace($key, $value, $message);
					}
				}
			}
		}

		$settings_sms = WLSM_M_Setting::get_settings_sms($school_id);
		$sms_carrier  = $settings_sms['carrier'];

		if ('smsstriker' === $sms_carrier) {
			return self::smsstriker($school_id, $message, $to);
		} elseif ('msgclub' === $sms_carrier) {
			return self::msgclub($school_id, $message, $template_id, $to);
		}elseif ('nextsms' === $sms_carrier) {
			return self::nextsms($school_id, $message, $to);
		}elseif ('whatsapp' === $sms_carrier) {
			return self::whatsapp($school_id, $message, $to);
		} elseif ('gatewaysms' === $sms_carrier) {
			return self::gatewaysms($school_id, $message, $to);
		} elseif ('logixsms' === $sms_carrier) {
			return self::logixsms($school_id, $message, $to, $template_id);
		} elseif ('futuresol' === $sms_carrier) {
			return self::futuresol($school_id, $message, $to);
		} elseif ('bulksmsgateway' === $sms_carrier) {
			return self::bulksmsgateway($school_id, $message, $to);
		} elseif ('pointsms' === $sms_carrier) {
			return self::pointsms($school_id, $message, $template_id, $to);
		} elseif ('indiatext' === $sms_carrier) {
			return self::indiatext($school_id, $message, $to);
		} elseif ('nexmo' === $sms_carrier) {
			return self::nexmo($school_id, $message, $to);
		} elseif ('twilio' === $sms_carrier) {
			return self::twilio($school_id, $message, $to);
		} elseif ('msg91' === $sms_carrier) {
			return self::msg91($school_id, $message, $to, $template_id);
		} elseif ('textlocal' === $sms_carrier) {
			return self::textlocal($school_id, $message, $to);
		}elseif ('tecxsms' === $sms_carrier) {
			return self::tecxsms($school_id, $message, $to);
		}elseif ('switchportlimited' === $sms_carrier) {
			return self::switchportlimited($school_id, $message, $to);
		} elseif ('kivalosolutions' === $sms_carrier) {
			return self::kivalosolutions($school_id, $message, $to);
		} elseif ('ebulksms' === $sms_carrier) {
			return self::ebulksms($school_id, $message, $to);
		} elseif ('pob' === $sms_carrier) {
			return self::pob($school_id, $message, $to);
		} elseif ('vinuthan' === $sms_carrier) {
			return self::vinuthan($school_id, $message, $to);
		}elseif ('sendpk' === $sms_carrier) {
			return self::sendpk($school_id, $message, $to);
		}elseif ('bdbsms' === $sms_carrier) {
			return self::bdbsms($school_id, $message, $to);
		}elseif ('smartsms' === $sms_carrier) {
			return self::smartsms($school_id, $message, $to);
		}elseif ('sms_ir' === $sms_carrier) {
			return self::sms_ir($school_id, $message, $to);
		}
	}

	public static function smsstriker($school_id, $message, $numbers) {
		try {
			$smsstriker = WLSM_M_Setting::get_settings_smsstriker($school_id);
			$username   = $smsstriker['username'];
			$password   = $smsstriker['password'];
			$sender_id  = $smsstriker['sender_id'];

			if (is_array($numbers)) {
				foreach ($numbers as $key => $number) {
					if ((12 == strlen($number)) && ('91' == substr($number, 0, 2))) {
						$numbers[$key] = substr($number, 2, 10);
					} elseif ((13 == strlen($number)) && ('+91' == substr($number, 0, 3))) {
						$numbers[$key] = substr($number, 3, 10);
					} elseif ((11 == strlen($number)) && ('0' == substr($number, 0, 1))) {
						$numbers[$key] = substr($number, 3, 10);
					}
				}
				$number = implode(', ', $numbers);
			} else {
				if ((12 == strlen($numbers)) && ('91' == substr($numbers, 0, 2))) {
					$number = substr($numbers, 2, 10);
				} elseif ((13 == strlen($numbers)) && ('+91' == substr($numbers, 0, 3))) {
					$number = substr($numbers, 3, 10);
				} elseif ((11 == strlen($numbers)) && ('0' == substr($numbers, 0, 1))) {
					$number = substr($numbers, 1, 10);
				} else {
					$number = $numbers;
				}
			}

			if (!($username && $password && $sender_id)) {
				return false;
			}

			// $data = array(
			// 	"username"  => $username,
			// 	"password"  => $password,
			// 	"to"        => $number,
			// 	"from"      => $sender_id,
			// 	"msg"       => $message,
			// 	"type"      => 1,
			// 	"dnd_check" => 0,
			// );

			$response = wp_remote_post("https://www.smsstriker.com/API/sms.php?username=$username&password=$password&from=$sender_id&to=$number&msg=$message&type=1");
			$result   = wp_remote_retrieve_body($response);

			if ($result) {
				return true;
			}
		} catch (Exception $e) {
		}

		return false;
	}

	public static function nextsms($school_id, $message, $numbers) {
		try {
			$nextsms = WLSM_M_Setting::get_settings_nextsms($school_id);
			$username   = $nextsms['username'];
			$password   = $nextsms['password'];
			$sender_id  = $nextsms['sender_id'];

			// if (!($username && $password && $sender_id)) {
			// 	return false;
			// }

			$data = array(
				"username"  => $username,
				"password"  => $password,
				"to"        => $numbers,
				"from"      => $sender_id,
				"text"      => $message,
			);
			

			$response = wp_remote_get("https://messaging-service.co.tz/link/sms/v1/text/single?username=$username&password=$password&from=$sender_id&to=$numbers&text=$message");
			
			$result   = wp_remote_retrieve_body($response);

			
			if ($result) {
				return $result;
			}
		} catch (Exception $e) {
		}

		return false;
	}

	public static function whatsapp($school_id, $message, $numbers) {
		try {
			$whatsapp = WLSM_M_Setting::get_settings_whatsapp($school_id);
			$username   = $whatsapp['username'];
			$password   = $whatsapp['password'];

			// if (!($username && $password && $sender_id)) {
			// 	return false;
			// }

			$data = array(
				"username"  => $username,
				"password"  => $password,
				"to"        => $numbers,
				"text"      => $message,
			);
			
			$response = wp_remote_get("http://wa.azmobia.com/api/http-api.php?username=$username&password=$password&route=2&number=$numbers&message=$message");
			
			$result   = wp_remote_retrieve_body($response);
			if ($result) {
				return $result;
			}
		} catch (Exception $e) {
		}

		return false;
	}

	public static function logixsms($school_id, $message, $numbers, $template_id) {
		try {
			$logixsms = WLSM_M_Setting::get_settings_logixsms($school_id);
			$username   = $logixsms['username'];
			$password   = $logixsms['password'];
			$sender_id  = $logixsms['sender_id'];

			if (is_array($numbers)) {
				foreach ($numbers as $key => $number) {
					if ((12 == strlen($number)) && ('91' == substr($number, 0, 2))) {
						$numbers[$key] = substr($number, 2, 10);
					} elseif ((13 == strlen($number)) && ('+91' == substr($number, 0, 3))) {
						$numbers[$key] = substr($number, 3, 10);
					} elseif ((11 == strlen($number)) && ('0' == substr($number, 0, 1))) {
						$numbers[$key] = substr($number, 3, 10);
					}
				}
				$number = implode(', ', $numbers);
			} else {
				if ((12 == strlen($numbers)) && ('91' == substr($numbers, 0, 2))) {
					$number = substr($numbers, 2, 10);
				} elseif ((13 == strlen($numbers)) && ('+91' == substr($numbers, 0, 3))) {
					$number = substr($numbers, 3, 10);
				} elseif ((11 == strlen($numbers)) && ('0' == substr($numbers, 0, 1))) {
					$number = substr($numbers, 1, 10);
				} else {
					$number = $numbers;
				}
			}

			if (!($username && $password && $sender_id)) {
				return false;
			}

			$response = wp_remote_post("http://logixsms.in/api/swsend.asp?username=$username&password=$password&sender=$sender_id&sendto=$numbers&templateID=$template_id&message=$message");
			$result   = wp_remote_retrieve_body($response);

			if ($result) {
				return true;
			}
		} catch (Exception $e) {
		}

		return false;
	}

	public static function futuresol($school_id, $message, $numbers) {
		try {
			$futuresol = WLSM_M_Setting::get_settings_futuresol($school_id);
			$username   = $futuresol['username'];
			$password   = $futuresol['password'];
			$sender_id  = $futuresol['sender_id'];

			if (is_array($numbers)) {
				foreach ($numbers as $key => $number) {
					if ((12 == strlen($number)) && ('' == substr($number, 0, 2))) {
						$numbers[$key] = substr($number, 2, 10);
					} elseif ((13 == strlen($number)) && ('' == substr($number, 0, 3))) {
						$numbers[$key] = substr($number, 3, 10);
					} elseif ((11 == strlen($number)) && ('' == substr($number, 0, 1))) {
						$numbers[$key] = substr($number, 3, 10);
					}
				}
				$number = implode(', ', $numbers);
			} else {
				if ((12 == strlen($numbers)) && ('' == substr($numbers, 0, 2))) {
					$number = substr($numbers, 2, 10);
				} elseif ((13 == strlen($numbers)) && ('' == substr($numbers, 0, 3))) {
					$number = substr($numbers, 3, 10);
				} elseif ((11 == strlen($numbers)) && ('' == substr($numbers, 0, 1))) {
					$number = substr($numbers, 1, 10);
				} else {
					$number = $numbers;
				}
			}

			if (!($username && $password && $sender_id)) {
				return false;
			}

			$response = wp_remote_post("http://smsportal.futuresol.net/web_distributor/api/sms.php?username=$username&password=$password&sender=$sender_id&mobile=$number&message=$message");
			$result   = wp_remote_retrieve_body($response);
			if ($result) {
				return $result;
			}
		} catch (Exception $e) {
		}

		return false;
	}

	public static function gatewaysms($school_id, $message, $numbers) {
		try {
			$gatewaysms = WLSM_M_Setting::get_settings_gatewaysms($school_id);
			$username  = $gatewaysms['username'];
			$password  = $gatewaysms['password'];
			$sender_id = $gatewaysms['sender_id'];
			$gwid      = $gatewaysms['gwid'];

			if (is_array($numbers)) {
				foreach ($numbers as $key => $number) {
					if ((12 == strlen($number)) && ('91' == substr($number, 0, 2))) {
						$numbers[$key] = substr($number, 2, 10);
					} elseif ((13 == strlen($number)) && ('+91' == substr($number, 0, 3))) {
						$numbers[$key] = substr($number, 3, 10);
					} elseif ((11 == strlen($number)) && ('0' == substr($number, 0, 1))) {
						$numbers[$key] = substr($number, 3, 10);
					}
				}
				$number = implode(', ', $numbers);
			} else {
				if ((12 == strlen($numbers)) && ('91' == substr($numbers, 0, 2))) {
					$number = substr($numbers, 2, 10);
				} elseif ((13 == strlen($numbers)) && ('+91' == substr($numbers, 0, 3))) {
					$number = substr($numbers, 3, 10);
				} elseif ((11 == strlen($numbers)) && ('0' == substr($numbers, 0, 1))) {
					$number = substr($numbers, 1, 10);
				} else {
					$number = $numbers;
				}
			}

			if (!($username && $password && $sender_id)) {
				return false;
			}

			$data = '';

			$response = wp_remote_post("https://getwaysms.com/vendorsms/pushsms.aspx?user=$username&password=$password&msisdn=$number&sid=$sender_id&msg=$message&fl=0&gwid=$gwid", $data);
			$result   = wp_remote_retrieve_body($response);

			if ($result) {
				return true;
			}
		} catch (Exception $e) {
		}

		return false;
	}

	public static function sms_ir($school_id, $message, $numbers) {
		try {
			$sms_ir = WLSM_M_Setting::get_settings_sms_ir($school_id);
			$username  = $sms_ir['username'];
			$password  = $sms_ir['password'];
			$sender_id = $sms_ir['sender_id'];
			$line_number      = $sms_ir['line_number'];

			if (is_array($numbers)) {
				foreach ($numbers as $key => $number) {
					if ((12 == strlen($number)) && ('91' == substr($number, 0, 2))) {
						$numbers[$key] = substr($number, 2, 10);
					} elseif ((13 == strlen($number)) && ('+91' == substr($number, 0, 3))) {
						$numbers[$key] = substr($number, 3, 10);
					} elseif ((11 == strlen($number)) && ('0' == substr($number, 0, 1))) {
						$numbers[$key] = substr($number, 3, 10);
					}
				}
				$number = implode(', ', $numbers);
			} else {
				if ((12 == strlen($numbers)) && ('91' == substr($numbers, 0, 2))) {
					$number = substr($numbers, 2, 10);
				} elseif ((13 == strlen($numbers)) && ('+91' == substr($numbers, 0, 3))) {
					$number = substr($numbers, 3, 10);
				} elseif ((11 == strlen($numbers)) && ('0' == substr($numbers, 0, 1))) {
					$number = substr($numbers, 1, 10);
				} else {
					$number = $numbers;
				}
			}

			if (!($username && $password && $sender_id)) {
				return false;
			}

			$response = wp_remote_get("https://api.sms.ir/v1/send?username=$username&password=$password&line=$line_number&mobile=$number&text='$message'");
			$result   = wp_remote_retrieve_body($response);

			if ($result) {
				return true;
			}
		} catch (Exception $e) {
		}

		return false;
	}

	public static function bulksmsgateway($school_id, $message, $numbers) {
		try {
			$bulksmsgateway = WLSM_M_Setting::get_settings_bulksmsgateway($school_id);
			$username  = $bulksmsgateway['username'];
			$password  = $bulksmsgateway['password'];
			$sender_id = $bulksmsgateway['sender_id'];
			$template_id      = $bulksmsgateway['template_id'];

			if (is_array($numbers)) {
				foreach ($numbers as $key => $number) {
					if ((12 == strlen($number)) && ('91' == substr($number, 0, 2))) {
						$numbers[$key] = substr($number, 2, 10);
					} elseif ((13 == strlen($number)) && ('+91' == substr($number, 0, 3))) {
						$numbers[$key] = substr($number, 3, 10);
					} elseif ((11 == strlen($number)) && ('0' == substr($number, 0, 1))) {
						$numbers[$key] = substr($number, 3, 10);
					}
				}
				$number = implode(', ', $numbers);
			} else {
				if ((12 == strlen($numbers)) && ('91' == substr($numbers, 0, 2))) {
					$number = substr($numbers, 2, 10);
				} elseif ((13 == strlen($numbers)) && ('+91' == substr($numbers, 0, 3))) {
					$number = substr($numbers, 3, 10);
				} elseif ((11 == strlen($numbers)) && ('0' == substr($numbers, 0, 1))) {
					$number = substr($numbers, 1, 10);
				} else {
					$number = $numbers;
				}
			}

			if (!($username && $password && $sender_id)) {
				return false;
			}

			$data = '';

			$response = wp_remote_post(" http://api.bulksmsgateway.in/sendmessage.php?user=$username&password=$password&mobile=$number&sender=$sender_id&message=$message&type=3&template_id=$template_id ", $data);
		
			$result   = wp_remote_retrieve_body($response);
			
			if ($result) {
				return true;
			}
		} catch (Exception $e) {
		}

		return false;
	}

	public static function msgclub($school_id, $message, $template_id, $numbers) {
		try {
			$msgclub          = WLSM_M_Setting::get_settings_msgclub($school_id);
			$auth_key         = $msgclub['auth_key'];
			$sender_id        = $msgclub['sender_id'];
			$route_id         = $msgclub['route_id'];
			$sms_content_type = $msgclub['sms_content_type'];
			$entityid         = $msgclub['entityid'];
			$tmid             = $msgclub['tmid'];

			if (is_array($numbers)) {
				$number = implode(', ', $numbers);
			} else {
				$number = $numbers;
			}

			if (!($auth_key && $sender_id)) {
				return false;
			}

			$url = add_query_arg(
				array(
					'AUTH_KEY'       => $auth_key,
					'message'        => urlencode($message),
					'senderId'       => $sender_id,
					'routeId'        => $route_id,
					'mobileNos'      => $number,
					'smsContentType' => $sms_content_type,
					'entityid'       => $entityid,
					'tmid'           => $tmid,
					'templateid'     => $template_id,
				),
				'http://167.114.117.218/rest/services/sendSMS/sendGroupSms'
			);
			
			$response = wp_remote_get($url);
			$result   = wp_remote_retrieve_body($response);

			if ($result) {
				return true;
			}
		} catch (Exception $e) {
		}

		return false;
	}

	public static function pointsms($school_id, $message, $template_id, $numbers) {
		try {
			$pointsms  = WLSM_M_Setting::get_settings_pointsms($school_id);
			$username  = $pointsms['username'];
			$password  = $pointsms['password'];
			$sender_id = $pointsms['sender_id'];
			$channel   = $pointsms['channel'];
			$route     = $pointsms['route'];
			$peid      = $pointsms['peid'];

			if (is_array($numbers)) {
				foreach ($numbers as $key => $number) {
					if ((12 == strlen($number)) && ('91' == substr($number, 0, 2))) {
						$numbers[$key] = substr($number, 2, 10);
					} elseif ((13 == strlen($number)) && ('+91' == substr($number, 0, 3))) {
						$numbers[$key] = substr($number, 3, 10);
					} elseif ((11 == strlen($number)) && ('0' == substr($number, 0, 1))) {
						$numbers[$key] = substr($number, 3, 10);
					}
				}
				$number = implode(',', $numbers);
			} else {
				if ((12 == strlen($numbers)) && ('91' == substr($numbers, 0, 2))) {
					$number = substr($numbers, 2, 10);
				} elseif ((13 == strlen($numbers)) && ('+91' == substr($numbers, 0, 3))) {
					$number = substr($numbers, 3, 10);
				} elseif ((11 == strlen($numbers)) && ('0' == substr($numbers, 0, 1))) {
					$number = substr($numbers, 1, 10);
				} else {
					$number = $numbers;
				}
			}

			if (!($username && $password && $sender_id)) {
				return false;
			}

			$url = add_query_arg(
				array(
					"user"          => $username,
					"password"      => $password,
					"number"        => $number,
					"senderid"      => $sender_id,
					"channel"       => $channel,
					"DCS"           => '08',
					"flashsms"      => 0,
					"text"          => $message,
					"route"         => $route,
					// "peid"          => $peid,
					// "dlttemplateid" => $template_id,
				),
				'http://smslogin.pcexpert.in/api/mt/SendSMS'
			);

			if ($template_id && $peid) {
				$url = add_query_arg(
					array(
						"user"          => $username,
						"password"      => $password,
						"number"        => $number,
						"senderid"      => $sender_id,
						"channel"       => $channel,
						"DCS"           => '08',
						"flashsms"      => 0,
						"text"          => $message,
						"route"         => $route,
						"peid"          => $peid,
						"dlttemplateid" => $template_id,
					),
					'http://smslogin.pcexpert.in/api/mt/SendSMS'
				);
			}

			$response = wp_remote_get($url);
			
			$result   = wp_remote_retrieve_body($response);
			$result = json_decode($result);
			
			if ($result) {
				return $result->ErrorMessage;
			}
		} catch (Exception $e) {
		}

		return false;
	}

	public static function indiatext($school_id, $message, $numbers) {
		try {
			$indiatext  = WLSM_M_Setting::get_settings_indiatext($school_id);
			$username  = $indiatext['username'];
			$password  = $indiatext['password'];
			$sender_id = $indiatext['sender_id'];
			$channel   = $indiatext['channel'];
			$route     = $indiatext['route'];
			$peid      = $indiatext['peid'];

			if (is_array($numbers)) {
				foreach ($numbers as $key => $number) {
					if ((12 == strlen($number)) && ('91' == substr($number, 0, 2))) {
						$numbers[$key] = substr($number, 2, 10);
					} elseif ((13 == strlen($number)) && ('+91' == substr($number, 0, 3))) {
						$numbers[$key] = substr($number, 3, 10);
					} elseif ((11 == strlen($number)) && ('0' == substr($number, 0, 1))) {
						$numbers[$key] = substr($number, 3, 10);
					}
				}
				$number = implode(',', $numbers);
			} else {
				if ((12 == strlen($numbers)) && ('91' == substr($numbers, 0, 2))) {
					$number = substr($numbers, 2, 10);
				} elseif ((13 == strlen($numbers)) && ('+91' == substr($numbers, 0, 3))) {
					$number = substr($numbers, 3, 10);
				} elseif ((11 == strlen($numbers)) && ('0' == substr($numbers, 0, 1))) {
					$number = substr($numbers, 1, 10);
				} else {
					$number = $numbers;
				}
			}

			if (!($username && $password && $sender_id)) {
				return false;
			}

			$url = add_query_arg(
				array(
					"user"     => $username,
					"password" => $password,
					"senderid" => $sender_id,
					"channel"  => $channel,
					"DCS"      => 0,
					"flashsms" => 0,
					"number"   => $number,
					"text"     => $message,
					"route"    => $route,
				),
				'http://sms.indiatext.in/api/mt/SendSMS'
			);
			

			$response = wp_remote_get($url);
			$result   = wp_remote_retrieve_body($response);
			
			if ($result) {
				return true;
			}
		} catch (Exception $e) {
		}

		return false;
	}

	public static function vinuthan($school_id, $message, $numbers) {
		try {
			$vinuthan = WLSM_M_Setting::get_settings_vinuthan($school_id);
			$username = $vinuthan['username'];
			$password = $vinuthan['password'];
			$sender_id = $vinuthan['sender_id'];
			$channel = $vinuthan['channel'];
			$route = $vinuthan['route'];

			if (is_array($numbers)) {
				foreach ($numbers as $key => $number) {
					if ((12 == strlen($number)) && ('91' == substr($number, 0, 2))) {
						$numbers[$key] = substr($number, 2, 10);
					} elseif ((13 == strlen($number)) && ('+91' == substr($number, 0, 3))) {
						$numbers[$key] = substr($number, 3, 10);
					} elseif ((11 == strlen($number)) && ('0' == substr($number, 0, 1))) {
						$numbers[$key] = substr($number, 3, 10);
					}
				}
				$number = implode(',', $numbers);
			} else {
				if ((12 == strlen($numbers)) && ('91' == substr($numbers, 0, 2))) {
					$number = substr($numbers, 2, 10);
				} elseif ((13 == strlen($numbers)) && ('+91' == substr($numbers, 0, 3))) {
					$number = substr($numbers, 3, 10);
				} elseif ((11 == strlen($numbers)) && ('0' == substr($numbers, 0, 1))) {
					$number = substr($numbers, 1, 10);
				} else {
					$number = $numbers;
				}
			}

			if (!($username && $password && $sender_id)) {
				return false;
			}

			$url = add_query_arg(
				array(
					"authkey" => $username,
					"mobiles" => $number,
					"sender" => $sender_id,
					"type" => $channel,
					"message" => $message,
					"route" => $route,
				),
				'http://sms.vinuthan.in/api/sendhttp.php'
			);

			$response = wp_remote_get($url);
			$result = wp_remote_retrieve_body($response);
			if ($result) {
				return true;
			}
		} catch (Exception $e) {
		}

		return false;
	}
	public static function pob($school_id, $message, $numbers) {
		try {
			$pob  = WLSM_M_Setting::get_settings_pob($school_id);
			$username  = $pob['username'];
			$password  = $pob['password'];
			$sender_id = $pob['sender_id'];


			if (is_array($numbers)) {
				foreach ($numbers as $key => $number) {
					if ((12 == strlen($number)) && ('91' == substr($number, 0, 2))) {
						$numbers[$key] = substr($number, 2, 10);
					} elseif ((13 == strlen($number)) && ('+91' == substr($number, 0, 3))) {
						$numbers[$key] = substr($number, 3, 10);
					} elseif ((11 == strlen($number)) && ('0' == substr($number, 0, 1))) {
						$numbers[$key] = substr($number, 3, 10);
					}
				}
				$number = implode(',', $numbers);
			} else {
				if ((12 == strlen($numbers)) && ('91' == substr($numbers, 0, 2))) {
					$number = substr($numbers, 2, 10);
				} elseif ((13 == strlen($numbers)) && ('+91' == substr($numbers, 0, 3))) {
					$number = substr($numbers, 3, 10);
				} elseif ((11 == strlen($numbers)) && ('0' == substr($numbers, 0, 1))) {
					$number = substr($numbers, 1, 10);
				} else {
					$number = $numbers;
				}
			}

			if (!($username && $password && $sender_id)) {
				return false;
			}

			$url = add_query_arg(
				array(
					"username"     => $username,
					"password" => $password,
					"message"     => $message,
					"sender" => $sender_id,
					"mobiles"   => $number,
				),
				'http://sms.pob.ng/api/?'
			);
			$response = wp_remote_get($url);
			$result   = wp_remote_retrieve_body($response);
			if ($result) {
				return true;
			}
		} catch (Exception $e) {
		}

		return false;
	}

	public static function nexmo($school_id, $message, $numbers) {
		require_once WLSM_PLUGIN_DIR_PATH . 'includes/vendor/autoload.php';

		try {
			$nexmo      = WLSM_M_Setting::get_settings_nexmo($school_id);
			$api_key    = $nexmo['api_key'];
			$api_secret = $nexmo['api_secret'];
			$from       = $nexmo['from'];

			if (!($api_key && $api_secret && $from)) {
				return false;
			}

			$basic  = new \Nexmo\Client\Credentials\Basic($api_key, $api_secret);
			$client = new \Nexmo\Client($basic);

			$response = array();
			if (is_array($numbers)) {
				foreach ($numbers as $number) {
					$status = $client->message()->send(
						array(
							'to'   => $number,
							'from' => $from,
							'text' => $message
						)
					);
					array_push($response, $status->getResponseData());
				}
			} else {
				$status = $client->message()->send(
					array(
						'to'   => $numbers,
						'from' => $from,
						'text' => $message
					)
				);

				array_push($response, $status->getResponseData());
			}

			if (count($response) > 0) {
				return true;
			}
		} catch (Exception $e) {
		}

		return false;
	}

	public static function twilio($school_id, $message, $numbers) {
		require_once WLSM_PLUGIN_DIR_PATH . 'includes/vendor/autoload.php';

		try {
			$twilio = WLSM_M_Setting::get_settings_twilio($school_id);
			$sid    = $twilio['sid'];
			$token  = $twilio['token'];
			$from   = $twilio['from'];

			if (!($sid && $token && $from)) {
				return false;
			}

			$client = new \Twilio\Rest\Client($sid, $token);

			$response = array();
			if (is_array($numbers)) {
				foreach ($numbers as $number) {
					$status = $client->messages->create(
						$number,
						array(
							'from' => $from,
							'body' => $message
						)
					);
					array_push($response, $status);
				}
			} else {
				$status = $client->messages->create(
					$numbers,
					array(
						'from' => $from,
						'body' => $message
					)
				);

				array_push($response, $status);
			}

			if (count($response) > 0) {
				return true;
			}
		} catch (Exception $e) {
		}

		return false;
	}

	public static function msg91($school_id, $message, $numbers, $template_id = null) {
		try {
			$msg91   = WLSM_M_Setting::get_settings_msg91($school_id);
			$authkey = $msg91['authkey'];
			$route   = $msg91['route'];
			$sender  = $msg91['sender'];
			$country = $msg91['country'];

			if (is_array($numbers)) {
				$number = implode(', ', $numbers);
			} else {
				$number = $numbers;
			}

			if (!($authkey && $sender)) {
				return false;
			}

			$url = add_query_arg(
				array(
					'mobiles'     => $number,
					'authkey'     => $authkey,
					'route'       => $route,
					'sender'      => $sender,
					'message'     => urlencode($message),
					'country'     => $country,
					'DLT_TE_ID' => $template_id,
				),
				'https://api.msg91.com/api/sendhttp.php'
			);

			$response = wp_remote_get($url);
			$result   = wp_remote_retrieve_body($response);
			if ($result) {
				return $result;
			}
		} catch (Exception $e) {
		}

		return false;
	}

	public static function textlocal($school_id, $message, $numbers) {
		try {
			$textlocal = WLSM_M_Setting::get_settings_textlocal($school_id);
			$api_key   = $textlocal['api_key'];
			$sender    = $textlocal['sender'];

			if (is_array($numbers)) {
				$numbers = implode(',', $numbers);
			}

			if (!($api_key && $sender)) {
				return false;
			}

			$data = array(
				"apikey"  => $api_key,
				"numbers" => $numbers,
				"sender"  => urlencode($sender),
				"message" => urlencode($message),
			);
			$textlocal = new Textlocal(false, false, $api_key );
			$numbers = array($numbers);
			$sender = $sender ;
			$message = $message;

			try {
				$result = $textlocal->sendSms($numbers, $message, $sender);
				return ($result);
			} catch (Exception $e) {
				die('Error: ' . $e->getMessage());
			}
		} catch (Exception $e) {
		}
		return false;
	}

	public static function bdbsms($school_id, $message, $numbers) {
		try {
			$bdbsms = WLSM_M_Setting::get_settings_bdbsms($school_id);
			$api_key   = $bdbsms['api_key'];

			if (is_array($numbers)) {
				$numbers = implode(',', $numbers);
			}

			$data = array(
				"token"  => $api_key,
				"to" => $numbers,
				"message" => urlencode($message),
			);

			$response = wp_remote_post("http://api.greenweb.com.bd/api.php?token=$api_key&to=$numbers&message=".urlencode($message));
			$result   = wp_remote_retrieve_body($response);

			if ($result) {
				return $result;
			}
		} catch (Exception $e) {
		}

		return false;
	}

	public static function tecxsms($school_id, $message, $numbers) {
		try {
			$tecxsms = WLSM_M_Setting::get_settings_tecxsms($school_id);
			$api_key   = $tecxsms['api_key'];
			$sender   = $tecxsms['sender'];

			if (is_array($numbers)) {
				$numbers = implode(',', $numbers);
			}

			$data = array(
				"token"  => $api_key,
				"to" => $numbers,
				"message" => urlencode($message),
			);

			$response = wp_remote_get("https://app.tecxsms.com/sms/api?action=send-sms&api_key=$api_key&to=$numbers&from=$sender&sms=$message");
			
			$result   = wp_remote_retrieve_body($response);

			if ($result) {
				return $result;
			}
		} catch (Exception $e) {
		}

		return false;
	}

	public static function switchportlimited($school_id, $message, $numbers) {
		try {
			$switchportlimited = WLSM_M_Setting::get_settings_switchportlimited($school_id);
			$api_key           = $switchportlimited['api_key'];
			$sender            = $switchportlimited['sender'];
			$client_id         = $switchportlimited['client_id'];

			if (is_array($numbers)) {
				$numbers = implode(',', $numbers);
			}

			$url  = 'https://api.onfonmedia.co.ke/v1/sms/SendBulkSMS';
			$data = array(
						"SenderId"          => "$sender",
						"IsUnicode"         => true,
						"IsFlash"           => true,
						"ScheduleDateTime"  => "",
						"MessageParameters" => array(
							array(
								"Number" => "$numbers",
								"Text" => "$message"
							)
						),
						"ApiKey" => "$api_key",
						"ClientId" => "$client_id"
					);


					$response = wp_remote_post($url, array(
						'method' => 'POST',
						'headers' => array('Content-Type' => 'application/json'),
						'body' => json_encode($data),
					));
					if (is_wp_error($response)) {
						$error_message = $response->get_error_message();
						echo "Something went wrong: $error_message";
					} else {
						
						$result = json_decode(wp_remote_retrieve_body($response), true);
						if ($result && isset($result['Data'][0]['MessageErrorDescription']) && $result['Data'][0]['MessageErrorDescription'] === 'Success') {
							return 'success';
						} else {
							return 'Failed';
						}
					}
		} catch (Exception $e) {
		}

		return false;
	}

	public static function kivalosolutions($school_id, $message, $numbers) {
		try {
			$kivalosolutions = WLSM_M_Setting::get_settings_kivalosolutions($school_id);
			$api_key   = $kivalosolutions['api_key'];
			$sender    = $kivalosolutions['sender'];

			if (is_array($numbers)) {
				$numbers = implode(',', $numbers);
			}

			if (!($api_key && $sender)) {
				return false;
			}

			$data = array(
				"api_key"  => $api_key,
				"to" => $numbers,
				"from"  => urlencode($sender),
				"sms" => urlencode($message),
			);

			$url = ( "http://sms.kivalosolutions.com/sms/api?action=send-sms&api_key=$api_key&to=$numbers&from=$sender&sms=$message" );
			
			$response = wp_remote_get($url);
			$result   = wp_remote_retrieve_body($response);
			
			if ($result) {
				return $result;
			}
		} catch (Exception $result) {
		}

		return false;
	}

	public static function ebulksms($school_id, $message, $numbers) {
		try {
			$ebulksms = WLSM_M_Setting::get_settings_ebulksms($school_id);
			$username = $ebulksms['username'];
			$api_key  = $ebulksms['api_key'];
			$sender   = $ebulksms['sender'];
			$flash    = 0;

			if (!is_array($numbers)) {
				$numbers = array($numbers);
			}

			if (!($username && $api_key && $sender)) {
				return false;
			}

			$gsm = array();

			$country_code = '234';

			foreach ($numbers as $number) {
				$mobilenumber = trim($number);
				if ('0' === substr($mobilenumber, 0, 1)) {
					$mobilenumber = $country_code . substr($mobilenumber, 1);
				} elseif ('+' === substr($mobilenumber, 0, 1)) {
					$mobilenumber = substr($mobilenumber, 1);
				}

				$generated_id = uniqid('int_', false);
				$generated_id = substr($generated_id, 0, 30);
				$gsm['gsm'][] = array('msidn' => $mobilenumber, 'msgid' => $generated_id);
			}

			$message = array(
				'sender'      => $sender,
				'messagetext' => $message,
				'flash'       => "{$flash}",
			);

			$request = array(
				'SMS' => array(
					'auth' => array(
						'username' => $username,
						'apikey'   => $api_key
					),
					'message'    => $message,
					'recipients' => $gsm
				)
			);

			$json_data = json_encode($request);

			if (is_array($json_data)) {
				$json_data = http_build_query($json_data, '', '&');
			}

			$response = wp_remote_post(
				'http://api.ebulksms.com:8080/sendsms.json',
				array(
					'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
					'body'    => $json_data,
					'method'  => 'POST',
				)
			);
			$result = wp_remote_retrieve_body($response);

			if ($result) {
				return $result;
			}
		} catch (Exception $e) {
		}

		return false;
	}

	public static function sendpk($school_id, $message, $numbers) {
		try {
			$sendpk = WLSM_M_Setting::get_settings_sendpk($school_id);
			$api_key   = $sendpk['api_key'];
			$sender    = $sendpk['sender'];

			if (is_array($numbers)) {
				$numbers = implode(',', $numbers);
			}

			if (!($api_key && $sender)) {
				return false;
			}

			$data = array(
				"apikey"  => $api_key,
				"numbers" => $numbers,
				"sender"  => urlencode($sender),
				"message" => urlencode($message),
			);

			$response = wp_remote_post("https://sendpk.com/api/sms.php?&api_key=$api_key&sender=$sender&mobile=$numbers&message=$message");
			$result   = wp_remote_retrieve_body($response);
			
			if ($result) {
				return $result;
			}
		} catch (Exception $result) {
		}

		return false;
	}

	public static function smartsms($school_id, $message, $numbers) {
		try {
			$smartsms = WLSM_M_Setting::get_settings_smartsms($school_id);
			$api_key    = $smartsms['api_key'];
			$api_secret = $smartsms['api_secret'];
			$from       = $smartsms['from'];

			if (is_array($numbers)) {
				$numbers = implode(',', $numbers);
			}



			// if (!($api_key && $sender)) {
			// 	return false;
			// }

			$data = array(
				"apikey"  => $api_key,
				"numbers" => $numbers,
				"sender"  => urlencode($sender),
				"message" => urlencode($message),
			);

			$response = wp_remote_post("https://smartsms.pk/plain?api_token=$api_key&api_secret=$api_secret&to=$numbers&from=$from&message=$message");
			$result   = wp_remote_retrieve_body($response);
			if ($result) {
				return $result;
			}
		} catch (Exception $result) {
		}

		return false;
	}

	public static function student_homework_placeholders() {
		return array(
			'[STUDENT_NAME]'      => esc_html__('Student Name', 'school-management'),
			'[CLASS]'             => esc_html__('Class', 'school-management'),
			'[SECTION]'           => esc_html__('Section', 'school-management'),
			'[ROLL_NUMBER]'       => esc_html__('Roll Number', 'school-management'),
			'[ENROLLMENT_NUMBER]' => esc_html__('Enrollment Number', 'school-management'),
			'[ADMISSION_NUMBER]'  => esc_html__('Admission Number', 'school-management'),
			'[SCHOOL_NAME]'       => esc_html__('School Name', 'school-management'),
		);
	}

	public static function student_admission_placeholders() {
		return array(
			'[STUDENT_NAME]'      => esc_html__('Student Name', 'school-management'),
			'[CLASS]'             => esc_html__('Class', 'school-management'),
			'[SECTION]'           => esc_html__('Section', 'school-management'),
			'[ROLL_NUMBER]'       => esc_html__('Roll Number', 'school-management'),
			'[ENROLLMENT_NUMBER]' => esc_html__('Enrollment Number', 'school-management'),
			'[ADMISSION_NUMBER]'  => esc_html__('Admission Number', 'school-management'),
			'[LOGIN_USERNAME]'    => esc_html__('Login Username', 'school-management'),
			'[LOGIN_EMAIL]'       => esc_html__('Login Email Number', 'school-management'),
			'[LOGIN_PASSWORD]'    => esc_html__('Login Password', 'school-management'),
			'[SCHOOL_NAME]'       => esc_html__('School Name', 'school-management'),
		);
	}

	public static function student_registration_to_student_placeholders() {
		return array(
			'[STUDENT_NAME]'      => esc_html__('Student Name', 'school-management'),
			'[CLASS]'             => esc_html__('Class', 'school-management'),
			'[SECTION]'           => esc_html__('Section', 'school-management'),
			'[ROLL_NUMBER]'       => esc_html__('Roll Number', 'school-management'),
			'[ENROLLMENT_NUMBER]' => esc_html__('Enrollment Number', 'school-management'),
			'[ADMISSION_NUMBER]'  => esc_html__('Admission Number', 'school-management'),
			'[LOGIN_USERNAME]'    => esc_html__('Login Username', 'school-management'),
			'[LOGIN_EMAIL]'       => esc_html__('Login Email Number', 'school-management'),
			'[LOGIN_PASSWORD]'    => esc_html__('Login Password', 'school-management'),
			'[SCHOOL_NAME]'       => esc_html__('School Name', 'school-management'),
		);
	}

	public static function student_registration_to_admin_placeholders() {
		return array(
			'[STUDENT_NAME]'      => esc_html__('Student Name', 'school-management'),
			'[CLASS]'             => esc_html__('Class', 'school-management'),
			'[SECTION]'           => esc_html__('Section', 'school-management'),
			'[ROLL_NUMBER]'       => esc_html__('Roll Number', 'school-management'),
			'[ENROLLMENT_NUMBER]' => esc_html__('Enrollment Number', 'school-management'),
			'[ADMISSION_NUMBER]'  => esc_html__('Admission Number', 'school-management'),
			'[LOGIN_USERNAME]'    => esc_html__('Login Username', 'school-management'),
			'[LOGIN_EMAIL]'       => esc_html__('Login Email Number', 'school-management'),
			'[LOGIN_PASSWORD]'    => esc_html__('Login Password', 'school-management'),
			'[SCHOOL_NAME]'       => esc_html__('School Name', 'school-management'),
		);
	}

	public static function invoice_generated_placeholders() {
		return array(
			'[INVOICE_TITLE]'       => esc_html__('Invoice Title', 'school-management'),
			'[INVOICE_NUMBER]'      => esc_html__('Invoice Number', 'school-management'),
			'[INVOICE_PAYABLE]'     => esc_html__('Invoice Payable', 'school-management'),
			'[INVOICE_DATE_ISSUED]' => esc_html__('Invoice Date Issued', 'school-management'),
			'[INVOICE_DUE_DATE]'    => esc_html__('Invoice Due Date', 'school-management'),
			'[STUDENT_NAME]'        => esc_html__('Student Name', 'school-management'),
			'[CLASS]'               => esc_html__('Class', 'school-management'),
			'[SECTION]'             => esc_html__('Section', 'school-management'),
			'[ROLL_NUMBER]'         => esc_html__('Roll Number', 'school-management'),
			'[ENROLLMENT_NUMBER]'   => esc_html__('Enrollment Number', 'school-management'),
			'[ADMISSION_NUMBER]'    => esc_html__('Admission Number', 'school-management'),
			'[SCHOOL_NAME]'         => esc_html__('School Name', 'school-management'),
		);
	}

	public static function online_fee_submission_placeholders() {
		return array(
			'[INVOICE_TITLE]'       => esc_html__('Invoice Title', 'school-management'),
			'[RECEIPT_NUMBER]'      => esc_html__('Receipt Number', 'school-management'),
			'[AMOUNT]'              => esc_html__('AMOUNT', 'school-management'),
			'[PAYMENT_METHOD]'      => esc_html__('Payment Method', 'school-management'),
			'[DATE]'                => esc_html__('Date', 'school-management'),
			'[STUDENT_NAME]'        => esc_html__('Student Name', 'school-management'),
			'[CLASS]'               => esc_html__('Class', 'school-management'),
			'[SECTION]'             => esc_html__('Section', 'school-management'),
			'[ROLL_NUMBER]'         => esc_html__('Roll Number', 'school-management'),
			'[ENROLLMENT_NUMBER]'   => esc_html__('Enrollment Number', 'school-management'),
			'[ADMISSION_NUMBER]'    => esc_html__('Admission Number', 'school-management'),
			'[SCHOOL_NAME]'         => esc_html__('School Name', 'school-management'),
		);
	}

	public static function offline_fee_submission_placeholders() {
		return array(
			'[INVOICE_TITLE]'       => esc_html__('Invoice Title', 'school-management'),
			'[RECEIPT_NUMBER]'      => esc_html__('Receipt Number', 'school-management'),
			'[AMOUNT]'              => esc_html__('AMOUNT', 'school-management'),
			'[PAYMENT_METHOD]'      => esc_html__('Payment Method', 'school-management'),
			'[DATE]'                => esc_html__('Date', 'school-management'),
			'[STUDENT_NAME]'        => esc_html__('Student Name', 'school-management'),
			'[CLASS]'               => esc_html__('Class', 'school-management'),
			'[SECTION]'             => esc_html__('Section', 'school-management'),
			'[ROLL_NUMBER]'         => esc_html__('Roll Number', 'school-management'),
			'[ENROLLMENT_NUMBER]'   => esc_html__('Enrollment Number', 'school-management'),
			'[ADMISSION_NUMBER]'    => esc_html__('Admission Number', 'school-management'),
			'[SCHOOL_NAME]'         => esc_html__('School Name', 'school-management'),
		);
	}

	public static function student_admission_to_parent_placeholders() {
		return array(
			'[STUDENT_NAME]'      => esc_html__('Student Name', 'school-management'),
			'[CLASS]'             => esc_html__('Class', 'school-management'),
			'[SECTION]'           => esc_html__('Section', 'school-management'),
			'[ROLL_NUMBER]'       => esc_html__('Roll Number', 'school-management'),
			'[ENROLLMENT_NUMBER]' => esc_html__('Enrollment Number', 'school-management'),
			'[ADMISSION_NUMBER]'  => esc_html__('Admission Number', 'school-management'),
			'[LOGIN_USERNAME]'    => esc_html__('Login Username', 'school-management'),
			'[LOGIN_EMAIL]'       => esc_html__('Login Email Number', 'school-management'),
			'[LOGIN_PASSWORD]'    => esc_html__('Login Password', 'school-management'),
			'[SCHOOL_NAME]'       => esc_html__('School Name', 'school-management'),
		);
	}

	public static function invoice_generated_to_parent_placeholders() {
		return array(
			'[INVOICE_TITLE]'       => esc_html__('Invoice Title', 'school-management'),
			'[INVOICE_NUMBER]'      => esc_html__('Invoice Number', 'school-management'),
			'[INVOICE_PAYABLE]'     => esc_html__('Invoice Payable', 'school-management'),
			'[INVOICE_DATE_ISSUED]' => esc_html__('Invoice Date Issued', 'school-management'),
			'[INVOICE_DUE_DATE]'    => esc_html__('Invoice Due Date', 'school-management'),
			'[STUDENT_NAME]'        => esc_html__('Student Name', 'school-management'),
			'[CLASS]'               => esc_html__('Class', 'school-management'),
			'[SECTION]'             => esc_html__('Section', 'school-management'),
			'[ROLL_NUMBER]'         => esc_html__('Roll Number', 'school-management'),
			'[ENROLLMENT_NUMBER]'   => esc_html__('Enrollment Number', 'school-management'),
			'[ADMISSION_NUMBER]'    => esc_html__('Admission Number', 'school-management'),
			'[SCHOOL_NAME]'         => esc_html__('School Name', 'school-management'),
		);
	}

	public static function online_fee_submission_to_parent_placeholders() {
		return array(
			'[INVOICE_TITLE]'       => esc_html__('Invoice Title', 'school-management'),
			'[RECEIPT_NUMBER]'      => esc_html__('Receipt Number', 'school-management'),
			'[AMOUNT]'              => esc_html__('AMOUNT', 'school-management'),
			'[PAYMENT_METHOD]'      => esc_html__('Payment Method', 'school-management'),
			'[DATE]'                => esc_html__('Date', 'school-management'),
			'[STUDENT_NAME]'        => esc_html__('Student Name', 'school-management'),
			'[CLASS]'               => esc_html__('Class', 'school-management'),
			'[SECTION]'             => esc_html__('Section', 'school-management'),
			'[ROLL_NUMBER]'         => esc_html__('Roll Number', 'school-management'),
			'[ENROLLMENT_NUMBER]'   => esc_html__('Enrollment Number', 'school-management'),
			'[ADMISSION_NUMBER]'    => esc_html__('Admission Number', 'school-management'),
			'[SCHOOL_NAME]'         => esc_html__('School Name', 'school-management'),
		);
	}

	public static function offline_fee_submission_to_parent_placeholders() {
		return array(
			'[INVOICE_TITLE]'       => esc_html__('Invoice Title', 'school-management'),
			'[RECEIPT_NUMBER]'      => esc_html__('Receipt Number', 'school-management'),
			'[AMOUNT]'              => esc_html__('AMOUNT', 'school-management'),
			'[PAYMENT_METHOD]'      => esc_html__('Payment Method', 'school-management'),
			'[DATE]'                => esc_html__('Date', 'school-management'),
			'[STUDENT_NAME]'        => esc_html__('Student Name', 'school-management'),
			'[CLASS]'               => esc_html__('Class', 'school-management'),
			'[SECTION]'             => esc_html__('Section', 'school-management'),
			'[ROLL_NUMBER]'         => esc_html__('Roll Number', 'school-management'),
			'[ENROLLMENT_NUMBER]'   => esc_html__('Enrollment Number', 'school-management'),
			'[ADMISSION_NUMBER]'    => esc_html__('Admission Number', 'school-management'),
			'[SCHOOL_NAME]'         => esc_html__('School Name', 'school-management'),
		);
	}

	public static function absent_student_placeholders() {
		return array(
			'[ATTENDANCE_DATE]'   => esc_html__('Attendance Date', 'school-management'),
			'[STUDENT_NAME]'      => esc_html__('Student Name', 'school-management'),
			'[CLASS]'             => esc_html__('Class', 'school-management'),
			'[SECTION]'           => esc_html__('Section', 'school-management'),
			'[ROLL_NUMBER]'       => esc_html__('Roll Number', 'school-management'),
			'[ENROLLMENT_NUMBER]' => esc_html__('Enrollment Number', 'school-management'),
			'[ADMISSION_NUMBER]'  => esc_html__('Admission Number', 'school-management'),
			'[SCHOOL_NAME]'       => esc_html__('School Name', 'school-management'),
		);
	}

	public static function inquiry_received_to_inquisitor_placeholders() {
		return array(
			'[NAME]'  => esc_html__('Inquisitor Name', 'school-management'),
			'[PHONE]' => esc_html__('Inquisitor Phone', 'school-management'),
			'[EMAIL]' => esc_html__('Inquisitor Email', 'school-management'),
			'[CLASS]' => esc_html__('Inquisitor Class', 'school-management')
		);
	}

	public static function inquiry_received_to_admin_placeholders() {
		return array(
			'[NAME]'  => esc_html__('Inquisitor Name', 'school-management'),
			'[PHONE]' => esc_html__('Inquisitor Phone', 'school-management'),
			'[EMAIL]' => esc_html__('Inquisitor Email', 'school-management'),
			'[CLASS]' => esc_html__('Inquisitor Class', 'school-management')
		);
	}

	public static function custom_message_placeholders() {
		return array(
			'[STUDENT_NAME]'      => esc_html__('Student Name', 'school-management'),
			'[CLASS]'             => esc_html__('Class', 'school-management'),
			'[SECTION]'           => esc_html__('Section', 'school-management'),
			'[ROLL_NUMBER]'       => esc_html__('Roll Number', 'school-management'),
			'[ENROLLMENT_NUMBER]' => esc_html__('Enrollment Number', 'school-management'),
			'[ADMISSION_NUMBER]'  => esc_html__('Admission Number', 'school-management'),
			'[LOGIN_USERNAME]'    => esc_html__('Login Username', 'school-management'),
			'[LOGIN_EMAIL]'       => esc_html__('Login Email Number', 'school-management'),
			'[SCHOOL_NAME]'       => esc_html__('School Name', 'school-management'),
		);
	}
}
