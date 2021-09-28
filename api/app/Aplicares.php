<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\Authorization as Authorization;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\BPJS as BPJS;
use PondokCoder\Ruangan as Ruangan;
use Spipu\Html2Pdf\Tag\Html\B;

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
		self::$kodePPK = __KODE_PPK__;
		self::$data_api = __DATA_API_LIVE_APLICARES__;
		self::$secretKey_api = __SECRET_KEY_LIVE_APLICARES_BPJS__;
        /*self::$data_api = __DATA_API_LIVE__;
        self::$secretKey_api = __SECRET_KEY_LIVE_BPJS__;*/
		self::$base_url = __BASE_LIVE_BPJS_APLICARES__ . '/aplicaresws';
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
                    //return self::get_ruangan('master_unit_ruangan', $parameter[2]);
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
			/*$params = ['','ruangan-detail', $value['uid']];
			$get_ruangan = $ruangan->__GET__($params);*/
			$RuanganDetail = $ruangan->get_ruangan_detail('master_unit_ruangan', $value['uid']);
			$data['response_data'][$key]['uid_ruangan'] = $RuanganDetail['response_data'][0]['uid'];
			$data['response_data'][$key]['nama'] = $RuanganDetail['response_data'][0]['nama'];
			$data['response_data'][$key]['kode_ruangan'] = $RuanganDetail['response_data'][0]['kode_ruangan']; 
		}

		return $data;
	}

	private function get_kelas_kamar(){
		$url = "/aplicaresws/rest/ref/kelas";
		$BPJS = new BPJS(self::$pdo);
		$result = $BPJS->launchUrl($url, 2);

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

	private function get_ruangan_terdaftar_bpjs() {
		$url = "/aplicaresws/rest/bed/read/" . self::$kodePPK . "/1/100";
        $BPJS = new BPJS(self::$pdo);
		$result = $BPJS->launchUrl($url, 2);

		$error_count = 1;
		$error_message = array();

		$crossCheckData = array();

        $Lantai = new Lantai(self::$pdo);
        $kelas = new Terminologi(self::$pdo);

		foreach ($result['content']['response']['list'] as $key => $value) {
			$Ruangan = new Ruangan(self::$pdo);
			$KodeRuangan = $Ruangan->get_ruangan_detail_by_code($value['koderuang']);


            //Check Kelas
            $DetailKelas = self::$query->select('terminologi_item', array(
                'id'
            ))
                ->where(array(
                    'terminologi_item.nama' => '= ?',
                    'AND',
                    'terminologi_item.terminologi' => '= ?'
                ), array(
                    strval($value['kodekelas']), 14
                ))
                ->execute();

            if(count($DetailKelas['response_data']) > 0) {
                $targetKelas = $DetailKelas['response_data'][0]['id'];
            } else {
                $NewKelas = self::$query->insert('terminologi_item', array(
                    'nama' => strval($value['kodekelas']),
                    'terminologi' => 14,
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->returning('id')
                    ->execute();
                $targetKelas = $NewKelas['response_unique'];
            }


            if(!isset($KodeRuangan['uid'])) {

                //Tidak tercatat di sistem
                $Ruangan->tambah_ruangan('master_unit_ruangan', array(
                    'nama' => strval($value['kodekelas']),
                    'kode_ruangan' => strval($value['koderuang']),
                    'kelas' => $targetKelas,
                    'kapasitas' => (!is_null($value['kapasitas']) ? $value['kapasitas'] : 0),
                    'lantai' => $Lantai->get_lantai()['response_data'][0]['uid']
                ));

                $KodeRuangan = $Ruangan->get_ruangan_detail_by_code($value['koderuang']);

            }

			if(isset($KodeRuangan['uid'])) {

				$check = self::$query->select('aplicares_kamar_log', array(
					'ruangan'
				))
				->where(array(
					'aplicares_kamar_log.ruangan' => '= ?',
					'AND',
					'aplicares_kamar_log.deleted_at' => 'IS NULL'
				), array(
					$KodeRuangan['uid']
				))
				->execute();



				if(count($check['response_data']) == 0) {

					$logDataRuangan = self::$query->insert('aplicares_kamar_log', array(
						'ruangan' => $KodeRuangan['uid'],
						'kodekelas' => strval($value['kodekelas']),
						'kapasitas' => !is_null($value['kapasitas']) ? $value['kapasitas'] : 0,
						'tersedia' => !is_null($value['tersedia']) ? $value['tersedia'] : 0,
						'tersediapria' => !is_null($value['tersediapria']) ? $value['tersediapria'] : 0,
						'tersediawanita' => !is_null($value['tersediawanita']) ? $value['tersediawanita'] : 0,
						'tersediapriawanita' => !is_null($value['tersediapriawanita']) ? $value['tersediapriawanita'] : 0,
						'created_at' => parent::format_date(),
						'updated_at' => parent::format_date()
					))
					->execute();

					if($logDataRuangan['response_result'] > 0) {
						//$result['content']['response']['list'][$key]['uid_ruangan'] = $KodeRuangan['uid'];
						$value['uid_ruangan'] = $KodeRuangan['uid'];
						$value['nama'] = $KodeRuangan['nama'];
					} else {
						array_push($error_message, $logDataRuangan);
					}
				} else {
					//$result['content']['response']['list'][$key]['uid_ruangan'] = $check['response_data'][0]['ruangan'];
					$value['uid_ruangan'] = $KodeRuangan['uid'];
					$value['nama'] = $KodeRuangan['nama'];
				}

				$KelasDetail = self::$query->select('terminologi_item', array(
					'id',
					'nama'
				))
				->where(array(
					'terminologi_item.id' => '= ?',
					'AND',
					'terminologi_item.deleted_at' => 'IS NULL'
				), array(
					$KodeRuangan['kelas']
				))
				->execute();

				$KodeRuangan['kelas'] = $KelasDetail['response_data'][0];
				$value['detailRuangan'] = $KodeRuangan;

				array_push($crossCheckData, $value);
			}
		}
		return $crossCheckData;
        //return $result;
	}

	private function tambah_ruangan($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);

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
		$UserData = $Authorization->readBearerToken($parameter['access_token']);

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
			'local' => $ruangan,
			'rest' => $rest,
            'api' => $forApi
		);

		return $result;
	}

	private function hapus_ruangan($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);

		$dataRuangan = new Ruangan(self::$pdo);
		$params = ['','ruangan-detail', $parameter[6]];
		$get_ruangan = $dataRuangan->__GET__($params);
		$kodeRuangan = strval($get_ruangan['response_data'][0]['kode_ruangan']);

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

	/*public function launchUrl($extended_url){
		$url = self::$base_url . $extended_url;

		date_default_timezone_set('UTC');
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$signature = hash_hmac('sha256', self::$data_api ."&". $tStamp , self::$secretKey_api, true);
		$encodedSignature = base64_encode($signature);
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
	}*/

	private function get_header(){
		// Computes the timestamp
		date_default_timezone_set('UTC');
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));

        //$data_api = (($target_switch === 1) ? self::$data_api : __DATA_API_LIVE_APLICARES__);
        //$secretKey_api = (($target_switch === 1) ? self::$secretKey_api : __SECRET_KEY_LIVE_APLICARES_BPJS__);

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
	    $BPJS = new BPJS(self::$pdo);

		$url = self::$base_url . "/rest/bed/update/" . self::$kodePPK;
		$headers = self::get_header();

		$ch = curl_init();
		$dataJson = json_encode($parameter);

		//$result = $BPJS->postUrl($url, $parameter, 2);

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
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
        curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
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
			'id',
			'ruangan',
			'kodekelas'
		))
		->where(array(
			$parameter['table'] . '.deleted_at' => 'IS NULL',
			'AND',
			$parameter['table'] . '.ruangan' => '= ?'
		), array(
			$parameter['check']
		))
		->execute();
	}
}