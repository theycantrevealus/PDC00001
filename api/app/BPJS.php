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

	static $tStamp;
	static $signature;
	static $encodedSignature;
	static $ch;
	static $headers;
	
	protected static function getConn(){
		return self::$pdo;
	}

	public function __construct($connection) {
		self::$pdo = $connection;
		self::$query = new Query(self::$pdo);
		self::$kodePPK = __KODE_PPK__;
		self::$data_api = __DATA_API_STAGING__;
		self::$secretKey_api = __SECRET_KEY_STAGING_BPJS__;
		self::$base_url = __BASE_STAGING_BPJS__;


		date_default_timezone_set('UTC');
		self::$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		self::$signature = hash_hmac('sha256', self::$data_api ."&". self::$tStamp , self::$secretKey_api, true);
		self::$encodedSignature = base64_encode(self::$signature);

		self::$ch = curl_init();

		self::$headers = array(
			'X-cons-id: ' . self::$data_api . '',
			'X-timestamp: ' . self::$tStamp . '' ,
			'X-signature: ' . self::$encodedSignature . '',
			'Content-Type:application/json',     
		);

		curl_setopt(self::$ch, CURLOPT_HTTPHEADER, self::$headers);
		curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt(self::$ch, CURLOPT_TIMEOUT, 3);
		curl_setopt(self::$ch, CURLOPT_HTTPGET, 1);
		curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt(self::$ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	}

	public function __GET__($parameter = array()) {
		try {

			switch($parameter[1]) {
				case 'get_faskes':
					return self::get_faskes();
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

	private function get_faskes() {
		$hasil = array();
		for($a = 1; $a < 30; $a++) {
			//curl_setopt(self::$ch, CURLOPT_URL, self::$base_url . '/VClaim-rest/referensi/dokter/pelayanan/1/tglPelayanan/' . date('Y-m-d') . '/Spesialis/' . $a);
			curl_setopt(self::$ch, CURLOPT_URL, self::$base_url . '/VClaim-rest/referensi/spesialistik');
			$content = curl_exec(self::$ch);
			$err = curl_error(self::$ch);
			if (curl_errno(self::$ch)){
				$jlh = 1;
			}
			else{
				$jlh = 0;
			}
			

			$content = json_decode($content, TRUE);
			if(intval($content['metaData']['code']) != 201) {
				array_push($hasil, $content);
			}
		}
		return $hasil;
	}

	private function cek_peserta($parameter) {
		$no_bpjs = $parameter['no_bpjs'];
		$tglSEP = date("Y-m-d");
		
		curl_setopt(self::$ch, CURLOPT_URL, self::$base_url . '/VClaim-rest/Peserta/nokartu/' . $no_bpjs . '/tglSEP/' . $tglSEP . '');
		$content = curl_exec(self::$ch);
		$err = curl_error(self::$ch);
		
		if (curl_errno(self::$ch)){
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