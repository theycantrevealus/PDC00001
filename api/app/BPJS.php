<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class BPJS extends Utility {
	static $pdo;
	static $query;

	static $kodePPK;
	static $data_api;
	static $secretKey_api;
	static $base_url;
	
	protected static function getConn(){
		return self::$pdo;
	}

	public function __construct($connection) {
		self::$pdo = $connection;
		self::$query = new Query(self::$pdo);

		self::$kodePPK = "0069R035";
		/*self::$data_api = base64_decode("MTUxNzQ=");*/
		
		//LIVE
		//self::$data_api = 32435;
		
		//STAGING
		self::$data_api = 15174;
		/*self::$secretKey_api = base64_decode("NWJDRjJCNEY4Mw==");*/
		//LIVE//self::$secretKey_api = '2pAB5273E9';
		
		//STAGING VCLAIM
		self::$secretKey_api = '5bCF2B4F83';

		//STAGING APPLICARES
		//self::$secretKey_api = '5bCF2B4F8';
		//
		/*self::$base_url = "https://dvlp.bpjs-kesehatan.go.id:8888/aplicaresws";*/
		//self::$base_url = "http://api.bpjs-kesehatan.go.id/aplicaresws";
	}

	public function __GET__($parameter = array()) {
		try {

			switch($parameter[1]) {
				case 'select':
					break;
				default:
					return 'Unknown request';
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function __POST__($parameter = array()) {
		try {

			switch($parameter['request']) {
				case 'cek_peserta':
					return self::cek_peserta($parameter);
					break;
				default:
					return 'Unknown request';
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	private function cek_peserta($parameter) {
		$no_bpjs = $parameter['no_bpjs'];
		date_default_timezone_set('UTC');
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		// Computes the signature by hashing the salt with the secret key as the key
		$signature = hash_hmac('sha256', self::$data_api ."&". $tStamp , self::$secretKey_api, true);
		// base64 encode…
		$encodedSignature = base64_encode($signature);
		
		$tglSEP = date("Y-m-d");
		$ch = curl_init();
		$headers = array(
			'X-cons-id: '.self::$data_api .'',
			'X-timestamp: '.$tStamp.'' ,
			'X-signature: '.$encodedSignature.'',
			'Content-Type:application/json',     
		);
		
		curl_setopt($ch, CURLOPT_URL,"https://dvlp.bpjs-kesehatan.go.id/VClaim-rest/Peserta/nokartu/".$no_bpjs."/tglSEP/".$tglSEP."");
	  
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$content = curl_exec($ch);
		$err = curl_error($ch);
		
		//echo $err;
		if (curl_errno($ch)){
			$jlh=1;
		}
		else{
			$jlh=0;
		}
		
		$content = json_decode($content, TRUE);
		$content['response']['peserta']['tglLahir'] = date('d F Y', strtotime($content['response']['peserta']['tglLahir']));
		$content['response']['peserta']['tglCetakKartu'] = date('d F Y', strtotime($content['response']['peserta']['tglCetakKartu']));
		return $content;
	}
}
?>