<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;
use PondokCoder\Terminologi as Terminologi;
use PondokCoder\Pasien as Pasien;
use PondokCoder\Antrian as Antrian;
use PondokCoder\Pegawai as Pegawai;
use PondokCoder\Penjamin as Penjamin;

class AssesmenRawatJalan extends Utility {
	static $pdo;
	static $query;

	protected static function getConn(){
		return self::$pdo;
	}

	public function __construct($connection){
		self::$pdo = $connection;
		self::$query = new Query(self::$pdo);
	}

	public function __GET__($parameter = array()) {
		try {
			switch($parameter[1]) {
				case 'antrian-asesmen':
					return self::get_antrian_asesmen();
					break;

				case 'assesmen-detail':
					return self::get_assesmen_detail('assesmen_rawat_umum', $parameter[2]);
					break;

				case 'pasien-detail':
					return self::get_pasien($parameter[2]);
					break;

				case 'list-antrian':
					return self::get_list_antrian();
					break;

				default:
					# code...
					break;
			}
		} catch (QueryException $e) {
			return 'Error => '. $e;
		}
	}

	public function __POST__($parameter = array()){ 
		switch ($parameter['request']) {
			case 'tambah-assesmen':
				return self::tambah_assesmen('assesmen_rawat_umum', $parameter);
				break;

			case 'edit-assesmen':
				return self::edit_assesmen('assesmen_rawat_umum', $parameter);
				break;

			default:
				# code...
				break;
		}
	}


	private function get_antrian_asesmen(){
		$antrian = self::get_list_antrian();

		$autonum = 1;
		foreach ($antrian as $key => $value) {
			$cek_assesment = self::cek_assesmen_detail('asesmen_rawat_umum', $value['uid']);
			$antrian[$key]['uid_assesmen'] = "";
			$antrian[$key]['status_assesmen'] = false;

			if ($cek_assesment['response_result'] > 0){
				$antrian[$key]['uid_assesmen_rawat_umum'] = $cek_assesment['response_data'][0]['uid'];
				$antrian[$key]['status_assesmen'] = true; 
			}

			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $antrian;
	}

	private function cek_assesmen_detail($table, $parameter){
		$data = self::$query
				->select($table, array('uid','antrian'))
				->where(array(
							$table . '.deleted_at' => 'IS NULL',
							'AND',
							$table . '.antrian' => '= ?'
					),
					array($parameter)
				)
				->execute();

		return $data;
	}

	private function get_assesmen_detail($table, $parameter){
		$data = self::$query
				->select($table, array('*'))
				->where(array(
						$table . '.deleted_at' => 'IS NULL',
						'AND',
						$table . '.uid' => '= ?'
					),
					array($parameter)
				)
				->execute();

		return $data;
	}

	private function get_pasien($params){
		$dataAntrian = self::get_data_antrian_detail($params);
		$dataPasien = self::get_data_pasien($dataAntrian['uid_pasien']);

		$penjamin = new Penjamin(self::$pdo);
		$param = ['','penjamin-detail', $dataAntrian['penjamin']];
		$get_penjamin = $penjamin->__GET__($param);
		$dataAntrian['nama_penjamin'] = $get_penjamin['response_data'][0]['nama'];

		$poli = new Poli(self::$pdo);
		$param = ['','poli-detail', $dataAntrian['departemen']];
		$get_poli = $poli->__GET__($param);
		$dataAntrian['nama_departemen'] = $get_poli['response_data'][0]['nama'];

		$result = array(
					"antrian"=>$dataAntrian,
					"pasien"=>$dataPasien
				);

		return $result;
	}

	private function tambah_assesmen($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$dataObj = $parameter['dataObj'];
		$allData = [];

		$dataAntrian = self::get_data_antrian_detail($parameter['uid_antrian']);
		$dataPasien = self::get_data_pasien($dataAntrian['uid_pasien']);

		$uid = parent::gen_uuid();

		foreach ($dataObj as $key => $value) {
			$allData[$key] = $value;
		}

		/*$allData['uid'] = $uid;
		$allData['antrian'] = $parameter['uid_antrian'];
		$allData['no_rm'] = $dataPasien['no_rm'];
		$allData['pasien'] = $dataPasien['uid'];
		$allData['perawat2'] = $UserData['data']->uid;
		$allData['waktu_pengkajian'] = parent::format_date();
		$allData['departemen'] = $dataAntrian['departemen'];
		$allData['created_at'] = parent::format_date();
		$allData['updated_at'] = parent::format_date();

		$assesmen = self::$query
					->insert($table, $allData)
					->execute();

		if ($assesmen['response_result'] > 0) {
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
								$uid,
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
		
		return $assesmen;*/
		return $dataObj;
	}

	private function edit_assesmen($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$dataObj = $parameter['dataObj'];
		$old = self::get_assesmen_detail($table, $parameter['uid_assesmen']);
		$allData = [];
		foreach ($dataObj as $key => $value) {
			$allData[$key] = $value;
		}

		$allData['updated_at'] = parent::format_date();

		$assesmen = self::$query
				->update($table, $allData)
				->where(array(
					$table . '.deleted_at' => 'IS NULL',
					'AND',
					$table . '.uid' => '= ?'
					),
					array(
						$parameter['uid_assesmen']
					)
				)
				->execute();

		if ($assesmen['response_result'] > 0){
			unset($parameter['access_token']);

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
						$parameter['uid'],
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

		return $pasien;
	}


	private function get_data_antrian_detail($parameter){
		/*-------- GET DATA ANTRIAN ----------*/
		$antrian = new Antrian(self::$pdo);
		$param = ['','antrian-detail', $parameter];
		$get_antrian = $antrian->__GET__($param);
		$result = array(
					"uid_pasien"=>$get_antrian['response_data'][0]['pasien'],
					"departemen"=>$get_antrian['response_data'][0]['departemen'],
					"penjamin"=>$get_antrian['response_data'][0]['penjamin'],
					"dokter"=>$get_antrian['response_data'][0]['dokter'],
					"waktu_masuk"=>$get_antrian['response_data'][0]['waktu_masuk']
				);

		return $result;
	}

	private function get_data_pasien($parameter){
		/*--------- GET NO RM --------------- */
		$pasien = new Pasien(self::$pdo);
		$param = ['','pasien-detail', $parameter];
		$get_pasien = $pasien->__GET__($param);

		$term = new Terminologi(self::$pdo);
		$value = $get_pasien['response_data'][0]['jenkel'];
		$param = ['','terminologi-items-detail',$value];
		$get_jenkel = $term->__GET__($param);

		$value = $get_pasien['response_data'][0]['panggilan'];
		$param = ['','terminologi-items-detail',$value];
		$get_panggilan = $term->__GET__($param);

		$result = array(
					'uid'=>$get_pasien['response_data'][0]['uid'],
					'no_rm'=>$get_pasien['response_data'][0]['no_rm'],
					'nama'=>$get_pasien['response_data'][0]['nama'],
					'tanggal_lahir'=>$get_pasien['response_data'][0]['tanggal_lahir'],
					'jenkel'=>$get_jenkel['response_data'][0]['nama'],
					'id_jenkel'=>$get_pasien['response_data'][0]['jenkel'],
					'panggilan'=>$get_panggilan['response_data'][0]['nama']
				);

		return $result;
	}

	private function get_list_antrian(){
		$antrian = new Antrian(self::$pdo);
		$param = ['','antrian'];
		$get_antrian = $antrian->__GET__($param);

		return $get_antrian['response_data'];
	}

}