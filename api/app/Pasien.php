<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;
use PondokCoder\Terminologi as Terminologi;

class Pasien extends Utility {
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
				case 'pasien':
					return self::get_pasien('pasien');
					break;

				case 'pasien-detail':
					return self::get_pasien_detail('pasien', $parameter[2]);
					break;

				case 'cek-nik':
					return self::cekNIK($parameter[2]);
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
			case 'tambah-pasien':
				return self::tambah_pasien('pasien', $parameter);
				break;

			case 'edit-pasien':
				return self::edit_pasien('pasien', $parameter);
				break;

			default:
				# code...
				break;
		}
	}

	public function __DELETE__($parameter = array()){
		return self::delete_pasien('pasien', $parameter);
	}


	/*=======================GET FUNCTION======================*/
	private function get_pasien($table){
		$data = self::$query
					->select($table, array(
						'uid',
						'no_rm',
						'nama',
						'panggilan AS id_panggilan',
						'tanggal_lahir',
						'jenkel AS id_jenkel',
						'created_at',
						'updated_at'
						)
					)	
					->where(array(
							$table . '.deleted_at' => 'IS NULL'
						)
					)
					->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
			$data['response_data'][$key]['tanggal_lahir'] = parent::dateToIndo($data['response_data'][$key]['tanggal_lahir']);
			$term = new Terminologi(self::$pdo);

			$value = $data['response_data'][$key]['id_panggilan'];
			$param = ['','terminologi-items-detail',$value];
			$get_panggilan = $term->__GET__($param);
			$data['response_data'][$key]['panggilan'] = $get_panggilan['response_data'][0]['nama'];


			$value = $data['response_data'][$key]['id_jenkel'];
			$param = ['','terminologi-items-detail',$value];
			$get_jenkel = $term->__GET__($param);
			$data['response_data'][$key]['jenkel'] = $get_jenkel['response_data'][0]['nama'];

			$tgl_daftar = date("Y-m-d", strtotime($data['response_data'][$key]['created_at']));
			$data['response_data'][$key]['tgl_daftar'] = parent::dateToIndo($tgl_daftar);
		}

		return $data;
	}

	public function get_pasien_detail($table, $parameter){
		$data = self::$query
					->select($table, array(
						'uid',
						'no_rm',
						'nik',
						'nama',
						'panggilan',
						'tanggal_lahir',
						'tempat_lahir',
						'jenkel',
						'agama',
						'suku',
						'pendidikan',
						'goldar',
						'pekerjaan',
						'nama_ayah',
						'nama_ibu',
						'nama_suami_istri',
						'status_suami_istri',
						'alamat',
						'alamat_rt',
						'alamat_rw',
						'alamat_provinsi',
						'alamat_kabupaten',
						'alamat_kecamatan',
						'alamat_kelurahan',
						'warganegara',
						'no_telp',
						'created_at',
						'updated_at'
						)
					)	
					->where(array(
							$table . '.deleted_at' => 'IS NULL',
							'AND',
							$table . '.uid' => '= ?'
						),
						array(
							$parameter
						)
					)
					->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			//Panggilan
			$Terminologi = new Terminologi(self::$pdo);
			$TerminologiInfo = $Terminologi::get_terminologi_items_detail('terminologi_item', $value['panggilan']);
			$data['response_data'][$key]['panggilan_name'] = $TerminologiInfo['response_data'][0];
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $data;
	}

	/*==================== CRUD ====================*/

	private function tambah_pasien($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$dataObj = $parameter['dataObj'];
		$allData = [];

		$check = self::duplicate_check(array(
			'table'=>$table,
			'check'=>$dataObj['nama']
		));

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();

			$allData['uid'] = $uid;
			$allData['created_at'] = parent::format_date();
			$allData['updated_at'] = parent::format_date();

			foreach ($dataObj as $key => $value) {
				$allData[$key] = $value;
			}

			$pasien = self::$query
						->insert($table, $allData)
						->execute();

			if ($pasien['response_result'] > 0){
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

			return $pasien;

		}
	}

	private function edit_pasien($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$dataObj = $parameter['dataObj'];
		$old = self::get_pasien_detail($table, $parameter['uid']);
		$allData = [];
		foreach ($dataObj as $key => $value) {
			$allData[$key] = $value;
		}
		$allData['updated_at'] = parent::format_date();
		$pasien = self::$query
				->update($table, $allData)
				->where(array(
					$table . '.deleted_at' => 'IS NULL',
					'AND',
					$table . '.uid' => '= ?'
					),
					array(
						$parameter['uid']
					)
				)
				->execute();

		if ($pasien['response_result'] > 0){
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

	private function delete_pasien($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$pasien = self::$query
			->delete($table)
			->where(array(
					$table . '.uid' => '= ?'
				), array(
					$parameter[6]	
				)
			)
			->execute();

		if ($pasien['response_result'] > 0){
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
		}
		return $pasien;
	}

	/*private function get_table_col($table_name){
		$data = self::$query
					->select('INFORMATION_SCHEMA.COLUMNS', array(
							'column_name'
						)
					)
					->where(array(
							'table_name' => '= ?'
						),
						array(
							$table_name
						)
					)
					->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $data;
	}*/

	public function cekNIK($parameter){
		$data = self::$query
			->select('pasien', array('uid', 'nik'))
			->where(
				array(
					'pasien.nik' => '= ?',
					'AND',
					'pasien.deleted_at' => 'IS NULL'
				),
				array(
					$parameter
				))
			->execute();

		$result = false;
		if ($data['response_result'] > 0){
			$result = true;
		}

		return $result;
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
			$parameter['table'] . '.nama' => '= ?'
		), array(
			$parameter['check']
		))
		->execute();
	}
}