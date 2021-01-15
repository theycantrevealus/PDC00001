<?php
namespace PondokCoder;
use \Firebase\JWT\JWT;
class Authorization {
	public static $Bearer;
	public function getAuthorizationHeader($parameter) {
		$headers = null;
		if (isset($parameter['Authorization']) || isset($parameter['x-token'])) {
			$headers = trim($parameter["Authorization"]);
		} else if (isset($parameter['HTTP_AUTHORIZATION'])) {
			$headers = trim($parameter["HTTP_AUTHORIZATION"]);
		} elseif (function_exists('apache_request_headers')) {
			$requestHeaders = apache_request_headers();
			$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
			if (isset($requestHeaders['Authorization'])) {
				$headers = trim($requestHeaders['Authorization']);
			}
		}
		return $headers;
	}

	public function getBearerToken($parameter) {
		$headers = self::getAuthorizationHeader($parameter);
		$getBearer = explode("Bearer", $headers);
		self::$Bearer = (count($getBearer) > 1) ? $getBearer[1] : $getBearer[0];
		if (!empty($headers)) {
		    if(count($getBearer) > 1) {
                if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                    return $matches[1];
                }
            } else {
		        return $getBearer[0];
            }
		}
		return (empty($getBearer)) ? null : $getBearer;
	}

	public function readBearerToken() {
		$parameter = self::getBearerToken($_SERVER);
		$key = file_get_contents('taknakal.pub');
		JWT::$leeway = 720000;
		$decoded = JWT::decode($parameter, $key, array('HS256'));	
		$decoded_array = (array) $decoded;
		return $decoded_array;
	}
}
?>