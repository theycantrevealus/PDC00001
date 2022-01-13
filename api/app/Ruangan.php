<?php 

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;
use PondokCoder\Lantai as Lantai;
use PondokCoder\Terminologi as Terminologi;

class Ruangan extends Utility {
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
				case 'ruangan':
					return self::get_ruangan('master_unit_ruangan');
					break;

				case 'ruangan-detail':
					return self::get_ruangan_detail('master_unit_ruangan', $parameter[2]);
					break;

				case 'ruangan-lantai':
					return self::get_ruangan_lantai('master_unit_ruangan', $parameter[2]);
					break;

				default:
                    return self::get_ruangan('master_unit_ruangan');
					break;
			}
		} catch (QueryException $e) {
			return 'Error => '. $e;
		}
	}

	public function __POST__($parameter = array()) {
		switch ($parameter['request']) {
			case 'tambah_ruangan':
				return self::tambah_ruangan('master_unit_ruangan', $parameter);
				break;

			case 'edit_ruangan':
				return self::edit_ruangan('master_unit_ruangan', $parameter);
				break;

			case 'multiple_request':
				return self::tambah_edit_multiple_ruangan('master_unit_ruangan', $parameter);
				break;
			default:
				# code...
				break;
		}
	}

	public function __DELETE__($parameter = array()) {
		return self::delete_ruangan('master_unit_ruangan', $parameter);
	}


	/*====================== GET FUNCTION =====================*/
	public function get_ruangan_detail_by_code($parameter) { //Untuk cek kode ruangan dari BPJS
		$data = self::$query
					->select('master_unit_ruangan', 
						array(
							'uid',
							'nama',
							'kode_ruangan',
							'kelas',
							'kapasitas',
							'lantai',
							'created_at',
							'updated_at'
						)
					)
					->where(array(
							'master_unit_ruangan.deleted_at' => 'IS NULL',
							'AND',
							'master_unit_ruangan.kode_ruangan' => '= ?'
						),
						array($parameter)
					)
					->execute();

		return $data['response_data'][0];
	}

	private function get_ruangan($table){
		$data = self::$query
					->select($table, 
						array(
							'uid',
							'nama',
							'kode_ruangan',
							'kelas as id_kelas',
							'kapasitas',
							'lantai as uid_lantai',
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
        $kelas = new Terminologi(self::$pdo);
        $lantai = new Lantai(self::$pdo);
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;

			$arr = ['','lantai-detail', $value['uid_lantai']];

			$get_lantai = $lantai::__GET__($arr);

			$lantai_res = $get_lantai['response_data'][0];
			$data['response_data'][$key]['lantai'] = $lantai_res['nama']; 


			$param = ['','terminologi-items-detail',$value['id_kelas']];
			$get_kelas = $kelas::__GET__($param);
			$data['response_data'][$key]['kelas'] = $get_kelas['response_data'][0]['nama']; 
		}	

		return $data;
	}

	public function get_ruangan_detail($table, $parameter){
		$data = self::$query
					->select($table, 
						array(
							'uid',
							'nama',
							'kode_ruangan',
							'kelas',
							'kapasitas',
							'lantai',
							'created_at',
							'updated_at'
						)
					)
					->where(array(
							$table . '.deleted_at' => 'IS NULL',
							'AND',
							$table . '.uid' => '= ?'
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

	private function get_ruangan_lantai($table, $parameter){
		$data = self::$query
					->select($table, 
						array(
							'uid',
							'nama',
							'lantai',
							'created_at',
							'updated_at'
						)
					)
					->where(array(
							$table . '.deleted_at' => 'IS NULL',
							'AND',
							$table . '.lantai' => '= ?'
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



	/*=========================================================*/


	/*====================== CRUD ========================*/
	private function tambah_edit_multiple_ruangan($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);

		$uid_lantai = $parameter['dataObj']['uid_lantai'];
		$add_ruangan = property_exists($parameter['dataObj'], 'add') ? $parameter['dataObj']['add'] : "";
		$update_ruangan = property_exists($parameter['dataObj'], 'update') ? $parameter['dataObj']['update'] : "";
		$delete_ruangan = property_exists($parameter['dataObj'], 'delete') ? $parameter['dataObj']['delete'] : "";

		if ($delete_ruangan != ""){
			foreach ($delete_ruangan as $value) {
				$ruangan = self::$query
					->delete($table)
					->where(array(
							$table . '.uid' => '= ?'
						), array(
							$value	
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
								$value,
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
			}
		}

		if ($update_ruangan != ""){
			foreach ($update_ruangan as $key => $value) {
				$old = self::get_ruangan_detail($key);

				$ruangan = self::$query
						->update($table, array(
								'nama'=>$value,
								'created_at'=>parent::format_date(),
								'updated_at'=>parent::format_date()
								)
						)
						->where(array(
								$table . 'deleted_at' => 'IS NULL',
								$table . '.uid' => '= ?'
							),array(
								$key
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
									'old_value',
									'new_value',
									'logged_at',
									'status',
									'login_id'
								),
								'value'=>array(
									$key,
									$UserData['data']->uid,
									$table,
									'U',
									json_encode($old['response_data'][0]),
									json_encode($value),
									parent::format_date(),
									'N',
									$UserData['data']->log_id
								),
								'class'=>__CLASS__
							)
						);
				}
			}
		}

		if ($add_ruangan != ""){
			foreach ($add_ruangan as $key => $value) {
				$check = self::duplicate_check(array(
					'table'=>$table,
					'check'=>$value
				));

				if (count($check['response_data']) > 0){
					$check['response_message'] = 'Duplicate data detected';
					$check['response_result'] = 0;
					unset($check['response_data']);
					return $check;
				} else {
					$uid = parent::gen_uuid();
					$ruangan = self::$query
							->insert($table, array(
								'uid'=>$uid,
								'nama'=>$value,
								'uid_lantai'=>$uid_lantai,
								'created_at'=>parent::format_date(),
								'updated_at'=>parent::format_date()
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
				}
			}
		}

		return $ruangan;
	}

	public function tambah_ruangan($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table'=>$table,
			'check'=>$parameter['kode_ruangan']
		));

		if (count($check['response_data']) > 0) {
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();
			$ruangan = self::$query
						->insert($table, array(
							'uid'=>$uid,
							'nama'=>$parameter['nama'],
							'kode_ruangan'=>$parameter['kode_ruangan'],
							'kelas'=>$parameter['kelas'],
							'kapasitas'=>$parameter['kapasitas'],
							'lantai'=>$parameter['lantai'],
							'created_at'=>parent::format_date(),
							'updated_at'=>parent::format_date()
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

			return $ruangan;

		}
	}

	private function edit_ruangan($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);

		$old = self::get_ruangan_detail('master_unit_ruangan', $parameter['uid']);

		$ruangan = self::$query
				->update($table, array(
						'nama'=>$parameter['nama'],
						'kode_ruangan'=>$parameter['kode_ruangan'],
						'kelas'=>$parameter['kelas'],
						'kapasitas'=>$parameter['kapasitas'],
						'lantai'=>$parameter['lantai'],
						'updated_at'=>parent::format_date()
					)
				)
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

		if ($ruangan['response_result'] > 0){
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

		return $ruangan;
	}

	private function delete_ruangan($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$ruangan = self::$query
			->delete($table)
			->where(array(
					$table . '.uid' => '= ?'
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
		}

		return $ruangan;
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