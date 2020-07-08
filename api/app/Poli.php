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

				case 'poli-view-detail':
					//return self::get_poli_tindakan_view_detail($parameter[2]);
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
				return self::tambah_poli('master_poli', $parameter);
				break;

			case 'edit_poli':
				return self::edit_poli('master_poli', $parameter);
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

			$tindakan = self::get_poli_tindakan($parameter);
			$data['response_data'][$key]['tindakan'] = $tindakan['response_data'];
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

	private function get_spesifik_poli_tindakan($uid_poli, $uid_tindakan, $uid_penjamin){
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
							'master_poli_tindakan_penjamin.uid_poli' => '= ?',
							'AND',
							'master_poli_tindakan_penjamin.uid_tindakan' => '= ?',
							'AND',
							'master_poli_tindakan_penjamin.uid_penjamin' => '= ?'
						),
						array(
							$uid_poli,
							$uid_tindakan,
							$uid_penjamin
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

	private function get_poli_tindakan_view_detail($parameter){
		$data = self::$query
				->select('master_poli_tindakan_penjamin', array(
						'harga',
						'uid_penjamin',
						'created_at',
						'updated_at'
					)
				)
				->join('master_poli', array(
						'nama AS poli'
					)
				)
				->join('master_tindakan', array(
						'nama AS tindakan'
					)
				)
				->on(array(
						array('master_poli_tindakan_penjamin.uid_poli', '=', 'master_poli.uid'),
						array('master_poli_tindakan_penjamin.uid_tindakan', '=', 'master_tindakan.uid')
					)
				)
				->where(array(
						'master_poli_tindakan_penjamin.deleted_at' => 'IS NULL',
						'AND',
						'master_poli_tindakan_penjamin.uid_poli' => '= ?'
					),
					array(
						$parameter
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

				if ($tindakanData != ""){
					foreach ($tindakanData as $key => $values) {
						$uid_tindakan = $key;

						foreach ($values as $key => $value) {
							$uid_penjamin = $key;

							$tindakan = self::$query
								->insert('master_poli_tindakan_penjamin', array(
											'harga'=>$value,
											'uid_poli'=>$uid_poli,
											'uid_tindakan'=>$uid_tindakan,
											'uid_penjamin'=>$uid_penjamin,
											'created_at'=>parent::format_date(),
											'updated_at'=>parent::format_date()
										)
									)
								->execute();

						}

					}
				}

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
			}
		}

		return $poli;
	}


	private function edit_poli($table_name, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$uid_poli = $parameter['dataObject']['uid'];
		$nama = $parameter['dataObject']['nama'];
		$tindakanData = $parameter['dataObject']['tindakan'];

		$old = self::get_poli_detail($uid_poli);
		$old_tindakan = self::get_poli_tindakan($uid_poli);
		$old_data['nama'] = $old['response_data'][0]['nama'];
		$old_data['tindakan'] = $old_tindakan['response_data'];

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

		if ($tindakanData != ""){

			/*=== First, set delete for all tindakan in poli ===*/
			self::$query
					->delete('master_poli_tindakan_penjamin')
					->where(array(
							'master_poli_tindakan_penjamin.deleted_at' => 'IS NULL',
							'AND',
							'master_poli_tindakan_penjamin.uid_poli' => '= ?'
						),array(
							$uid_poli
						)
					)
					->execute();


			foreach ($tindakanData as $key => $values) {
				$uid_tindakan = $key;

				foreach ($values as $key => $value) {
					$uid_penjamin = $key;
					$harga = $value;

					$tindakan = self::$query
										->update('master_poli_tindakan_penjamin', array(
												'harga'=>$harga,
												'updated_at'=>parent::format_date(),
												'deleted_at'=>NULL
											)
										)
										->where(array(
											'master_poli_tindakan_penjamin.uid_poli' => '= ?',
											'AND',
											'master_poli_tindakan_penjamin.uid_tindakan' => '= ?',
											'AND',
											'master_poli_tindakan_penjamin.uid_penjamin' => '= ?'
											),
											array(
												$uid_poli,
												$uid_tindakan,
												$uid_penjamin
											)
										)
										->execute();	

					/*=== Second, try to find the row data if it recorded before ===*/
					$cek_tindakan = self::get_spesifik_poli_tindakan($uid_poli, $uid_tindakan, $uid_penjamin);
					$resp_data = $cek_tindakan['response_data'];
					

					/*=== Check data has recorded before or not ===*/
					if (intval($cek_tindakan['response_result']) > 0){

						/*=== If row data is has set before, update harga and set null delete ==*/
						/*foreach ($resp_data as $key => $items) {

							if ($uid_penjamin == $items['uid']){

								$tindakan = self::$query
										->update('master_poli_tindakan_penjamin', array(
												'harga'=>$harga,
												'updated_at'=>parent::format_date(),
												'deleted_at'=>NULL
											)
										)
										->where(array(
											'master_poli_tindakan_penjamin.uid_poli' => '= ?',
											'AND',
											'master_poli_tindakan_penjamin.uid_tindakan' => '= ?',
											'AND',
											'master_poli_tindakan_penjamin.uid_penjamin' => '= ?'
											),
											array(
												$uid_poli,
												$uid_tindakan,
												$uid_penjamin
											)
										)
										->execute();
							}

						}*/
					
					} else {

						/*=== If row data is not available, insert new row ===*/
						$tindakan = self::$query
							->insert('master_poli_tindakan_penjamin', array(
									'harga'=>$value,
									'uid_poli'=>$uid_poli,
									'uid_tindakan'=>$uid_tindakan,
									'uid_penjamin'=>$uid_penjamin,
									'created_at'=>parent::format_date(),
									'updated_at'=>parent::format_date()
								)
							)
							->execute();
					}
				
				}
			}
		}
		

		if ($poli['response_result'] > 0){
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
							$uid_poli,
							$UserData['data']->uid,
							$table_name,
							'U',
							json_encode($old_data),
							json_encode($parameter['dataObject']),
							parent::format_date(),
							'N',
							$UserData['data']->log_id
						),
						'class'=>__CLASS__
					)
				);
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