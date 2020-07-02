<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;
use PondokCoder\Penjamin as Penjamin;
use PondokCoder\Tindakan as Tindakan;

class Poli extends Utility {
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
				case 'poli':
					return self::get_poli();
					break;

				case 'poli-detail':
					return self::get_poli_detail($parameter[2]);
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
			case 'tambah_poli':
				self::tambah_poli('master_poli', $parameter);
				break;

			case 'edit_poli':
				//self::edit_poli($parameter);
				break;

			default:
				# code...
				break;
		}
	}

	public function __DELETE__($parameter = array()){
		return self::delete_poli($parameter);
	}

	/*=============== GET POLI ================*/
	private function get_poli(){
		$data = self::$query
					->select('master_poli', array(
						'uid',
						'nama',
						'created_at',
						'updated_at'
						)
					)	
					->where(array(
							'master_poli.deleted_at' => 'IS NULL'
						)
					)
					->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $data;
	}

	private function get_poli_detail($parameter){
		$data = self::$query
				->select('master_poli', array(
						'uid',
						'nama',
						'created_at',
						'updated_at'
					)
				)
				->where(array(
							'master_poli.deleted_at' => 'IS NULL',
							'AND',
							'master_poli.uid' => '= ?'
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

	private function get_poli_tindakan($parameter){
		$data = self::$query
				->select('master_poli_tindakan_penjamin', array(
						'id',
						'harga',
						'uid_tindakan',
						'uid_penjamin',
						'created_at',
						'updated_at'
					)
				)
				->where(array(
							'master_poli_tindakan_penjamin.deleted_at' => 'IS NULL',
							'AND',
							'master_poli_tindakan_penjamin.uid_poli' => '= ?'
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

	

	/*====================== CRUD ========================*/

	private function tambah_poli($table_name, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		//$objData = $parameter['dataObject'];
		$nama = $parameter['dataObject']['nama'];
		$tindakanData = $parameter['dataObject']['tindakan'];

		$check = self::duplicate_check(array(
			'table'=>$table_name,
			'check'=>$nama
		));

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid_poli = parent::gen_uuid();
			$poli = self::$query
						->insert($table_name, array(
								'uid'=>$uid_poli,
								'nama'=>$nama,
								'created_at'=>parent::format_date(),
								'updated_at'=>parent::format_date()
								)
						)->execute();

			if ($poli['response_result'] > 0){
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
								$table_name,
								'I',
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class'=>__CLASS__
						)
					);

				//$tindakanData = $parameter['tindakan'];

				foreach ($tindakanData as $key => $values) {
					$uid_tindakan = $key;

					foreach ($values as $key => $value) {
						$tindakan = self::$query
							->insert('master_poli_tindakan_penjamin', array(
										'harga'=>$value,
										'uid_poli'=>$uid_poli,
										'uid_tindakan'=>$uid_tindakan,
										'uid_penjamin'=>$key,
										'created_at'=>parent::format_date(),
										'updated_at'=>parent::format_date()
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
											$uid,
											$UserData['data']->uid,
											$table_name,
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

		}

		return $poli;
	}


	private function edit_poli($table_name, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_poli_detail($table_name, $parameter['uid']);

		$uid_poli = $parameter['dataObject']['uid'];
		$nama = $parameter['dataObject']['nama'];
		$tindakanData = $parameter['dataObject']['tindakan'];

		$poli = self::$query
					->update($table_name, array(
						'nama'=>$nama,
						'updated_at'=>parent::format_date()
						)
					)
					->where(array(
						$table_name . '.deleted_at' => 'IS NULL',
						'AND',
						$table_name . '.uid' => '= ?'
						),
						array(
							$uid_poli
						)
					)
					->execute();

		if ($poli['response_result'] > 0){
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
							$table_name,
							'I',
							parent::format_date(),
							'N',
							$UserData['data']->log_id
						),
						'class'=>__CLASS__
					)
				);


			foreach ($tindakanData as $key => $values) {
				$uid_tindakan = $key;

				foreach ($values as $key => $value) {
					$tindakan = self::$query
						->update('master_poli_tindakan_penjamin', array(
								'harga'=>$value,
								'updated_at'=>parent::format_date()
							)
						)
						->where(array(
							'master_poli_tindakan_penjamin.deleted_at' => 'IS NULL',
							'AND',
							'master_poli_tindakan_penjamin.uid_poli' => '= ?',
							'AND',
							'master_poli_tindakan_penjamin.uid_tindakan' => '= ?',
							'AND',
							'master_poli_tindakan_penjamin.uid_penjamin' => '= ?'
							),
							array(
								$uid_poli,
								$uid_tindakan,
								$key
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
										$uid,
										$UserData['data']->uid,
										'master_poli_tindakan_penjamin.uid_tindakan',
										'U',
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

		return $poli;
	}


	private function delete_poli($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$poli = self::$query
			->delete($parameter[6])
			->where(array(
					$parameter[6] . '.uid' => '= ?'
				), array(
					$parameter[7]	
				)
			)
			->execute();

		if ($poli['response_result'] > 0){
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

			/*================ DELETE HARGA TINDAKAN =================*/
			$tindakan = self::$query
						->delete('master_poli_tindakan_penjamin')
						->where(array(
								 'master_poli_tindakan_penjamin.uid_poli' => '= ?'
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
							'master_poli_tindakan_penjamin',
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

		return $poli;
	}


	/*============= FUNCTION TAMBAHAN ============*/
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