<?php
namespace PondokCoder;
use \Firebase\JWT\JWT;
class Authorization {
	public static $Bearer;

	public function getSerialNumber($parameter) {
        $url = 'https://pondokcoder.com/serial';
        date_default_timezone_set('UTC');
        $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
        $signature = hash_hmac('sha256', self::$data_api ."&". $tStamp , self::$secretKey_api, true);
        $encodedSignature = base64_encode($signature);

        //Get OS Type
        $OS = PHP_OS;
        $serial_code = '';
        switch ($OS) {
            case 'Linux':
                $serial_code = shell_exec('udevadm info --query=all --name=/dev/sda | grep ID_SERIAL');
                break;
        }

        return $OS . ' >> ' . $serial_code . '>>';

        /*$headers = array(
            'harddisk: ' . self::$data_api . ' ',
            'X-timestamp: ' . $tStamp . ' ',
            'X-signature: ' .$encodedSignature,
            'Content-Type: Application/x-www-form-urlencoded',
            'Accept: Application/JSON'
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameter));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');


        $content = curl_exec($ch);
        $err = curl_error($ch);

        $result = json_decode($content, true);
        $return_value = array("content"=>$result, "error"=>$err);

        return $return_value;*/
    }

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

	public function readBearerToken($token = '') {
		$parameter = self::getBearerToken($_SERVER);
		$key = file_get_contents('taknakal.pub');
		JWT::$leeway = 720000;
		$decoded = JWT::decode($parameter, $key, array('HS256'));	
		$decoded_array = (array) $decoded;
		return $decoded_array;
	}
}
?>