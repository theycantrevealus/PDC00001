<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;

class Tindakan extends Utility {
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
				case 'tindakan':
					return self::get_tindakan();
					break;

				case 'tindakan-detail':
					return self::get_tindakan_detail($parameter[2]);
					break;

				case 'get-kelas':
					return self::get_kelas_tindakan();
					break;

				case 'get-harga-tindakan':
					return self::get_harga_tindakan($parameter[2]);
					break;

				default:
					return self::get_tindakan();
					break;
			}
		} catch (QueryException $e) {
			return 'Error => '. $e;
		}
	}

	public function __POST__($parameter = array()){
		switch ($parameter['request']) {
			case 'tambah_tindakan':
				return self::tambah_tindakan($parameter);
				break;

			case 'edit_tindakan':
				return self::edit_tindakan($parameter);
				break;

			default:
				# code...
				break;
		}
	}

	public function __DELETE__($parameter = array()){ 
		return self::delete_tindakan($parameter);
	}


	/*============GET TINDAKAN============*/
	public function get_tindakan(){
		$data = self::$query
					->select('master_tindakan', array(
						'uid',
						'nama',
						'created_at',
						'updated_at'
						)
					)	
					->where(array(
							'master_tindakan.deleted_at' => 'IS NULL'
						)
					)
					->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['harga'] = self::get_harga_join_kelas_by_tindakan($value['uid']);

			$autonum++;
		}

		return $data;
	}

	public function get_tindakan_detail($parameter){
		$data = self::$query
				->select('master_tindakan', array(
						'uid',
						'nama',
						'created_at',
						'updated_at'
					)
				)
				->where(array(
							'master_tindakan.deleted_at' => 'IS NULL',
							'AND',
							'master_tindakan.uid' => '= ?'
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

	/*-- mengambil nilai yang tidak sesuai dengan parameter --*/
	private function get_tindakan_notexist($parameter = array()){
		$count_param = count($parameter);
		
		if ($count_param > 0){

			$data = self::$query
					->select('master_tindakan', array(
							'uid',
							'nama',
							'created_at',
							'updated_at'
						)
					)
					->where(array(
								'master_tindakan.deleted_at' => 'IS NULL',
								'AND',
								'master_tindakan.uid' => 'NOT IN ?'
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
	}

	public function get_kelas_tindakan(){
		$data = self::$query
			->select('master_tindakan_kelas', array(
					'uid',
					'nama',
					'urutan'
				)
			)
			->where(array(
					'master_tindakan_kelas.deleted_at' => 'IS NULL'
			))
			->execute();

		return $data;
	}

	public function get_kelas_tindakan_detail($parameter){ //uid_kelas
		$data = self::$query
			->select('master_tindakan_kelas', array(
					'uid',
					'nama',
					'urutan'
				)
			)
			->where(array(
					'master_tindakan_kelas.deleted_at' => '= ?',
					'AND',
					'master_tindakan_kelas.deleted_at' => 'IS NULL'
				), array($parameter)
			)
			->execute();

		return $data;
	}

	public function get_harga_tindakan($parameter){		//uid_tindakan
		$data = self::$query
			->select('master_tindakan_kelas_harga', array(
					'id',
					'tindakan',
					'kelas',
					'harga'	
				)
			)
			->where(array(
					'master_tindakan_kelas_harga.tindakan' => '= ?',
					'AND',
					'master_tindakan_kelas_harga.deleted_at' => 'IS NULL'
				), array($parameter)
			)
			->execute();

		return $data;
	}

	public function get_harga_join_kelas_by_tindakan($parameter){ //uid_tindakan
		$result = [];

		$data = self::$query
			->select('master_tindakan_kelas_harga', array(
					'id',
					'kelas',
					'harga as harga_tindakan'
				)
			)
			->join('master_tindakan_kelas', array(
					'urutan',
					'nama as nama_kelas'
				)
			)
			->on(array(
					array('master_tindakan_kelas_harga.kelas','=', 'master_tindakan_kelas.uid')
				)
			)
			->where(array(
					'master_tindakan_kelas_harga.tindakan' => '= ?',
					'AND',
					'master_tindakan_kelas_harga.deleted_at' => 'IS NULL'
				), array($parameter)
			)
			->order(array('master_tindakan_kelas.urutan' => 'ASC'))
			->execute();

		foreach ($data['response_data'] as $key => $value) {
			$result[$value['urutan']] = $data['response_data'][$key];
		}

		return $result;
	}

	/*=====================- CRUD AREA -=======================*/

	private function tambah_tindakan($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table'=>'master_tindakan',
			'check'=>$parameter['nama']
		));

		$result = [];

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();
			$tindakan = self::$query
						->insert('master_tindakan', array(
								'uid'=>$uid,
								'nama'=>$parameter['nama'],
								'created_at'=>parent::format_date(),
								'updated_at'=>parent::format_date()
								)
						)->execute();

			if ($tindakan['response_result'] > 0){
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
								'master_tindakan',
								'I',
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class'=>__CLASS__
						)
					);

				if (count($parameter['harga']) > 0) {
					$harga_result = 0;

					foreach ($parameter['harga'] as $key => $value) {
						$harga = self::$query
							->insert('master_tindakan_kelas_harga', array(
									'tindakan' => $uid,
									'kelas' => $key,
									'harga' => $value,
									'created_at' => parent::format_date(),
									'updated_at' => parent::format_date()
								)
							)
							->execute();

						if ($harga['response_result'] > 0) {
							$harga_result += $harga['response_result'];
						}
					}
				}
			}
		}

		$result = ['tindakan' => $tindakan, 'harga' => $harga];

		return $result;
	}

	public function edit_tindakan($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$result = [];

		$old = self::get_tindakan_detail($parameter['uid']);

		$tindakan = self::$query
				->update('master_tindakan', array(
						'nama'=>$parameter['nama'],
						'updated_at'=>parent::format_date()
					)
				)
				->where(array(
					'master_tindakan.deleted_at' => 'IS NULL',
					'AND',
					'master_tindakan.uid' => '= ?'
					),
					array(
						$parameter['uid']
					)
				)
				->execute();

		if ($tindakan['response_result'] > 0){
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
						'master_tindakan',
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

			if (count($parameter['harga']) > 0){
				foreach ($parameter['harga'] as $key => $value) {
					$harga = self::$query
						->update('master_tindakan_kelas_harga', array(
								'harga' => $value,
								'updated_at' => parent::format_date()
							)
						)
						->where(array(
								'master_tindakan_kelas_harga.tindakan' => '= ?',
								'AND',
								'master_tindakan_kelas_harga.kelas' => '= ?',
								'AND',
								'master_tindakan_kelas_harga.deleted_at' => 'IS NULL' 
							), array(
								$parameter['uid'],
								$key
							)
						)
						->execute();
				}
			}
		}

		$result = ['tindakan' => $tindakan, 'harga' => $harga];

		return $result;
	}

	private function delete_tindakan($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$tindakan = self::$query
			->delete($parameter[6])
			->where(array(
					$parameter[6] . '.uid' => '= ?'
				), array(
					$parameter[7]	
				)
			)
			->execute();

		if ($tindakan['response_result'] > 0){
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
						$parameter[7],
						$UserData['data']->uid,
						$parameter[6],
						'D',
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class'=>__CLASS__
				)
			);

			$harga = self::$query
				->delete('master_tindakan_kelas_harga')
				->where(array(
						'master_tindakan_kelas_harga.tindakan' => '= ?',
						'AND',
						'master_tindakan_kelas_harga.deleted_at' => 'IS NULL'
					), array(
						$parameter[7]	
					)
				)
				->execute();
		}

		$result = ['tindakan' => $tindakan, 'harga' => $harga];

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