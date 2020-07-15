<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\Authorization as Authorization;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Ruangan as Ruangan;

class Aplicares extends Utility {
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
		self::$data_api = base64_decode("MTUxNzQ=");
		self::$secretKey_api = base64_decode("NWJDRjJCNEY4Mw==");
		self::$base_url = "https://dvlp.bpjs-kesehatan.go.id:8888/aplicaresws";
	}

	public function __GET__($parameter = array()) {
		try {
			switch($parameter[1]) {
				case 'get-kelas-kamar':
					return self::get_kelas_kamar();
					break;

				case 'get-ruangan':
					return self::get_ruangan('master_unit_ruangan', $parameter[2]);
					break;

				case 'get-ruangan-detail':
					return self::get_ruangan_log_detail('master_unit_ruangan', $parameter[2]);
					break;

				case 'get-ruangan-terdaftar':
					return self::get_ruangan_terdaftar('aplicares_kamar_log', $parameter[2]);
					break;

				case 'get-ruangan-terdaftar-bpjs':
					return self::get_ruangan_terdaftar_bpjs();
					break;

				case 'get-header':
					return self::get_header();
					break;

				case 'post-ruangan':
					return self::post_ruangan();
					break;
				
				default:
					break;
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function __POST__($parameter = array()){
		switch ($parameter['request']) {
			case 'tambah-ruangan':
				return self::tambah_ruangan('aplicares_kamar_log', $parameter);
				break;

			case 'edit-ruangan':
				return self::edit_ruangan('aplicares_kamar_log', $parameter);
				break;

			default:
				# code...
				break;
		}
	}

	public function __DELETE__($parameter = array()){
		return self::hapus_ruangan('aplicares_kamar_log', $parameter);
	}

	private function get_ruangan_terdaftar($table, $parameter){
		$data = self::$query
				->select($table, array(
						'ruangan as uid',
						'kodekelas',
						'kapasitas',
						'tersedia',
						'tersediapria',
						'tersediawanita',
						'tersediapriawanita',
						'created_at',
						'updated_at'
					)
				)
				->where(array(
						$table . '.deleted_at' => 'IS NULL'
					),array()
				)
				->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;

			$ruangan = new Ruangan(self::$pdo);
			$params = ['','ruangan-detail', $value['uid']];
			$get_ruangan = $ruangan->__GET__($params);
			$data['response_data'][$key]['ruangan'] = $get_ruangan['response_data'][0]['nama'];
			$data['response_data'][$key]['nama'] = $get_ruangan['response_data'][0]['nama'];
			$data['response_data'][$key]['kode_ruangan'] = $get_ruangan['response_data'][0]['kode_ruangan']; 
		}

		return $data;
	}

	private function get_kelas_kamar(){
		$url = "/rest/ref/kelas";
		$result = self::launchUrl($url);

		return $result['content']['response']['list'];
	}


	private function get_ruangan($table, $parameter){
		$data = self::$query
				->select($table, array(
						'uid',
						'kode_ruangan',
						'nama'
					)
				)
				->where(array(
						/*'lower(' . $table . '.nama)' => 'LIKE \'%' . $parameter . '%\'',
						'OR',
						'lower(' . $table . '.kode_ruangan)' => 'LIKE \'%' . $parameter . '%\'',
						'AND',*/
						'uid' => 'NOT IN (SELECT ruangan FROM aplicares_kamar_log WHERE deleted_at IS NULL)'
					),array()
				)
				->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $data;
	}

	private function get_ruangan_log_detail($table, $parameter){
		$data = self::$query
				->select($table, array(
						'ruangan',
						'kodekelas'
					)
				)
				->where(
					array(
						$table . '.deleted_at' => 'IS NULL',
						'AND',
						$table . '.ruangan' => '= ?'
					),
					array($parameter)
				)
				->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $data;
	}

	private function get_ruangan_terdaftar_bpjs(){
		$url = "/rest/bed/read/" . self::$kodePPK . "/1/10";
		$result = self::launchUrl($url);

		return $result;
	}

	private function tambah_ruangan($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$dataObj = $forApi = $parameter['dataObj'];
		$allData = $result = [];

		$dataRuangan = new Ruangan(self::$pdo);
		$params = ['','ruangan-detail', $parameter['uid_ruangan']];
		$get_ruangan = $dataRuangan->__GET__($params);
		$namaRuangan = $get_ruangan['response_data'][0]['nama'];
		$kodeRuangan = $get_ruangan['response_data'][0]['kode_ruangan'];

		foreach ($dataObj as $key => $value) {
			$allData[$key] = $value;
		}

		$check = self::duplicate_check(array(
			'table'=>$table,
			'check'=>$parameter['uid_ruangan']
		));

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {

			$allData['ruangan'] = $parameter['uid_ruangan'];
			$allData['created_at'] = parent::format_date();
			$allData['updated_at'] = parent::format_date();

			$ruangan = self::$query
						->insert($table, $allData)
						->execute();

			if ($ruangan['response_result'] > 0){
					$log = parent::log(array(
								'type'=>'activity',
								'column'=>array(
									'unique_target',
									'user_uid',
									'table_name',
									'action',
									'logged_at',
									'status',
									'login_id'
								),
								'value'=>array(
									$parameter['uid_ruangan'],
									$UserData['data']->uid,
									$table,
									'I',
									parent::format_date(),
									'N',
									$UserData['data']->log_id
								),
								'class'=>__CLASS__
							)
						);
				}

			$forApi['namaruang'] = $namaRuangan;
			$forApi['koderuang'] = $kodeRuangan;

			$rest = self::post_ruangan($forApi);

			$result = array(
				"local"=>$ruangan,
				"rest"=>$rest
			);
		}

		return $result;
	}



	private function edit_ruangan($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$dataObj = $forApi = $parameter['dataObj'];
		$allData = $result = [];

		$dataRuangan = new Ruangan(self::$pdo);
		$params = ['','ruangan-detail', $parameter['uid_ruangan']];
		$get_ruangan = $dataRuangan->__GET__($params);
		$namaRuangan = $get_ruangan['response_data'][0]['nama'];
		$kodeRuangan = $get_ruangan['response_data'][0]['kode_ruangan'];

		
		foreach ($dataObj as $key => $value) {
			$allData[$key] = $value;
		}

		$allData['updated_at'] = parent::format_date();

		$ruangan = self::$query
					->update($table, $allData)
					->where(
						array(
							$table . '.ruangan' => '= ?',
							'AND',
							$table . '.deleted_at' => 'IS NULL'
						),
						array($parameter['uid_ruangan'])
					)
					->execute();

		if ($ruangan['response_result'] > 0){
			$log = parent::log(array(
					'type'=>'activity',
					'column'=>array(
						'unique_target',
						'user_uid',
						'table_name',
						'action',
						'old_value',
						'new_value',
						'logged_at',
						'status',
						'login_id'
					),
					'value'=>array(
						$parameter['uid_ruangan'],
						$UserData['data']->uid,
						$table,
						'U',
						json_encode($old['response_data'][0]),
						json_encode($parameter),
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class'=>__CLASS__
				)
			);
		}

		$forApi['namaruang'] = $namaRuangan;
		$forApi['koderuang'] = $kodeRuangan;

		$rest = self::update_ruangan($forApi);

		$result = array(
			"local"=>$ruangan,
			"rest"=>$rest
		);

		return $result;
	}

	private function hapus_ruangan($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$dataRuangan = new Ruangan(self::$pdo);
		$params = ['','ruangan-detail', $parameter[6]];
		$get_ruangan = $dataRuangan->__GET__($params);
		$kodeRuangan = $get_ruangan['response_data'][0]['kode_ruangan'];

		$ruangan_log = self::get_ruangan_log_detail('aplicares_kamar_log', $parameter[6]);
		$kodeKelas = $ruangan_log['response_data'][0]['kodekelas'];

		$dataDelete = array(
			"kodekelas"=>$kodeKelas,
			"koderuang"=>$kodeRuangan
		);
		
		$ruangan = self::$query
			->delete($table)
			->where(array(
					$table . '.ruangan' => '= ?'
				), array(
					$parameter[6]
				)
			)
			->execute();

		if ($ruangan['response_result'] > 0){
			$log = parent::log(array(
					'type'=>'activity',
					'column'=>array(
						'unique_target',
						'user_uid',
						'table_name',
						'action',
						'logged_at',
						'status',
						'login_id'
					),
					'value'=>array(
						$parameter[6],
						$UserData['data']->uid,
						$table,
						'D',
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class'=>__CLASS__
				)
			);

			$data = array(
						"kodekelas"=>$kodeKelas,
						"koderuang"=>$kodeRuangan
					);

			$rest = self::delete_ruangan($dataDelete);

			$result = array(
				"local"=>$ruangan,
				"rest"=>$rest
			);
		}

		return $result;
	}

	private function launchUrl($extended_url){
		$url = self::$base_url . $extended_url;

		// Computes the timestamp
		date_default_timezone_set('UTC');
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));

		// Computes the signature by hashing the salt with the secret key as the key
		$signature = hash_hmac('sha256', self::$data_api ."&". $tStamp , self::$secretKey_api, true);

		// base64 encode…
		$encodedSignature = base64_encode($signature);

		// urlencode…
		// $encodedSignature = urlencode($encodedSignature);
		$headers = array(
			"X-cons-id: " . self::$data_api ." ",
			"X-timestamp: " .$tStamp ." ",
			"X-signature: " .$encodedSignature,
			"Content-Type: application/json; charset=utf-8"
		);


		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		$content = curl_exec($ch);
		$err = curl_error($ch);

		$result = json_decode($content, true);
		$return_value = array("content"=>$result, "error"=>$err);

		return $return_value;
	}

	private function get_header(){
		// Computes the timestamp
		date_default_timezone_set('UTC');
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));

		// Computes the signature by hashing the salt with the secret key as the key
		$signature = hash_hmac('sha256', self::$data_api ."&". $tStamp , self::$secretKey_api, true);

		// base64 encode…
		$encodedSignature = base64_encode($signature);

		// urlencode..
		$headers = array(
			"X-cons-id: " . self::$data_api ." ",
			"X-timestamp: " .$tStamp ." ",
			"X-signature: " .$encodedSignature,
			"Content-Type: Application/JSON",
			"Accept: Application/JSON"
		);

		return $headers;
	}

	private function post_ruangan($parameter){
		$url = self::$base_url . "/rest/bed/create/" . self::$kodePPK;
		$headers = self::get_header();

		$ch = curl_init();
		$dataJson = json_encode($parameter);

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		$server_output = curl_exec($ch);
		$err = curl_error($ch);
		$result = array(
			"result"=>$server_output,
			"error"=>$err
		);

		curl_close($ch);

		return $result;
	}

	private function update_ruangan($parameter){
		$url = self::$base_url . "/rest/bed/update/" . self::$kodePPK;
		$headers = self::get_header();

		$ch = curl_init();
		$dataJson = json_encode($parameter);

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		$server_output = curl_exec($ch);
		$err = curl_error($ch);
		$result = array(
			"result"=>$server_output,
			"error"=>$err
		);

		curl_close($ch);

		return $result;
	}

	private function delete_ruangan($parameter){
		$url = self::$base_url . "/rest/bed/delete/" . self::$kodePPK;
		$headers = self::get_header();

		$ch = curl_init();
		$dataJson = json_encode($parameter);

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		$server_output = curl_exec($ch);
		$err = curl_error($ch);
		$result = array(
			"result"=>$server_output,
			"error"=>$err
		);

		curl_close($ch);

		return $arr_data;
	}

	private function objectToList($obj, $key_obj, $value_obj){
		$count = count($obj);

		if ($count > 0) {
			$list = array();
			$key = 0;

			for ($i = 0; $i < $count; $i++){
				$list[$i]['id'] = $obj[$i][$key_obj];
				$list[$i]['text'] = $obj[$i][$value_obj]; 
			}

			/*foreach ($row as $keys => $value) {
				
				$key++;
			}*/
		} else {
			$list = null;
		}

		return json_encode($list);
	}


	private function duplicate_check($parameter) {
		return self::$query
		->select($parameter['table'], array(
			'uid',
			'nama'
		))
		->where(array(
			$parameter['table'] . '.deleted_at' => 'IS NULL',
			'AND',
			$parameter['table'] . '.uid' => '= ?'
		), array(
			$parameter['check']
		))
		->execute();
	}
}