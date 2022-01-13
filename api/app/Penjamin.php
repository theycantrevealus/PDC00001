<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;

class Penjamin extends Utility {
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
				case 'penjamin':
					return self::get_penjamin();
					break;

				case 'penjamin-detail':
					return self::get_penjamin_detail($parameter[2]);
					break;

				case 'get_penjamin_obat':
					return self::get_penjamin_obat($parameter[2]);
					break;

				/*case 'get_penjamin_tindakan':
					return self::get_penjamin_tindakan($parameter[2]);
					break;*/

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
			case 'tambah_penjamin':
				return self::tambah_penjamin($parameter);
				break;

			case 'edit_penjamin':
				return self::edit_penjamin($parameter);
				break;

			case 'sync_profit_obat':
				return self::sync_profit_obat($parameter);
				break;

			default:
				# code...
				break;
		}
	}

	public function __DELETE__($parameter = array()){
		return self::delete_penjamin($parameter);
	}


	/*=======================GET FUNCTION======================*/
	public function get_penjamin(){
		$data = self::$query
					->select('master_penjamin', array(
						'uid',
						'nama',
						'created_at',
						'updated_at'
						)
					)	
					->where(array(
							'master_penjamin.deleted_at' => 'IS NULL'
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

	public function get_penjamin_detail($parameter){
		$data = self::$query
				->select('master_penjamin', array(
						'uid',
						'nama',
						'created_at',
						'updated_at'
,					)
				)
				->where(array(
							'master_penjamin.deleted_at' => 'IS NULL',
							'AND',
							'master_penjamin.uid' => '= ?'
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

	public function get_penjamin_tindakan($parameter) {
		$data = self::$query
		->select('master_poli_tindakan_penjamin', array(
			'id',
			'harga',
			'uid_poli',
			'uid_tindakan',
			'uid_penjamin',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_poli_tindakan_penjamin.deleted_at' => 'IS NULL',
			'AND',
			'master_poli_tindakan_penjamin.uid_tindakan' => '= ?',
			'AND',
			'master_poli_tindakan_penjamin.uid_penjamin' => '= ?',
			'ANd',
			'master_poli_tindakan_penjamin.uid_poli' => '= ?',
		), array(
			$parameter['tindakan'],
			$parameter['penjamin'],
			$parameter['poli']
		))
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['penjamin'] = self::get_penjamin_detail($value['penjamin'])['response_data'][0];
			$autonum++;
		}

		return $data;
	}
	
	public function get_penjamin_obat($parameter, $target_penjamin = '') {
		if(isset($target_penjamin) && !empty($target_penjamin)) {
			$data = self::$query
				->select('master_inv_harga', array(
					'barang',
					'penjamin',
					'profit',
					'profit_type',
					'created_at',
					'updated_at'
				))
				->where(array(
					'master_inv_harga.penjamin' => '= ?',
					'AND',
					'master_inv_harga.deleted_at' => 'IS NULL',
					'AND',
					'master_inv_harga.barang' => '= ?',
					'AND',
					'master_inv_harga.profit' => '> 0',
				), array(
					$target_penjamin, $parameter
				))
				->execute();
		} else {
			$data = self::$query
			->select('master_inv_harga', array(
				'barang',
				'penjamin',
				'profit',
				'profit_type',
				'created_at',
				'updated_at'
			))
			->where(array(
				'master_inv_harga.deleted_at' => 'IS NULL',
				'AND',
				'master_inv_harga.barang' => '= ?',
				'AND',
				'master_inv_harga.profit' => '> 0',
			), array(
				$parameter
			))
			->execute();
		}

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['penjamin'] = self::get_penjamin_detail($value['penjamin'])['response_data'][0];
			$autonum++;
		}

		return $data;
	}


	/*====================== CRUD ========================*/

	private function tambah_penjamin($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table'=>'master_penjamin',
			'check'=>$parameter['nama']
		));

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();
			$penjamin = self::$query
						->insert('master_penjamin', array(
								'uid'=>$uid,
								'nama'=>$parameter['nama'],
								'created_at'=>parent::format_date(),
								'updated_at'=>parent::format_date()
								)
						)
						->returning('uid')
						->execute();

			if ($penjamin['response_result'] > 0) {
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
								'master_penjamin',
								'I',
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class'=>__CLASS__
						)
					);
			}

			return $penjamin;

		}
	}

	private function sync_profit_obat($parameter) {
		$reset = self::$query->delete('master_inv_harga')
			->execute();

		$proceed = array();

		$Penjamin = self::$query->select('master_penjamin', array(
			'uid'
		))
			->where(array(
				'master_penjamin.deleted_at' => 'IS NULL'
			), array())
			->execute();
		foreach($Penjamin['response_data'] as $key => $value) {
			$Item = self::$query->select('master_inv', array(
				'uid'
			))
				->where(array(
					'master_inv.deleted_at' => 'IS NULL'
				), array())
				->execute();
			foreach($Item['response_data'] as $IKey => $IValue) {
				$profitCheck = self::$query->select('master_inv_harga', array(
					'id'
				))
					->where(array(
						'master_inv_harga.barang' => '= ?',
						'AND',
						'master_inv_harga.penjamin' => '= ?'
					), array(
						$IValue['uid'],
						$value['uid']
					))
					->execute();

				if(count($profitCheck['response_data']) > 0) {
					$process_prof = self::$query->update('master_inv_harga', array(
						'profit' => '25',
						'profit_type' => 'P',
						'deleted_at' => NULL
					))
						->where(array(
							'master_inv_harga.id' => '= ?'
						), array(
							$profitCheck['response_data'][0]['id']
						))
						->execute();
				} else {
					$process_prof = self::$query->insert('master_inv_harga', array(
						'barang' => $IValue['uid'],
						'penjamin' => $value['uid'],
						'profit' => '25',
						'profit_type' => 'P',
						'created_at' => parent::format_date(),
						'updated_at' => parent::format_date()
					))
						->execute();
				}

				
				if($process_prof['response_result'] <= 0) {
					array_push($proceed, $process_prof);	
				}
			}
		}

		return $proceed;
	}

	private function edit_penjamin($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);

		$old = self::get_penjamin_detail($parameter['uid']);

		$penjamin = self::$query
				->update('master_penjamin', array(
						'nama'=>$parameter['nama'],
						'updated_at'=>parent::format_date()
					)
				)
				->where(array(
					'master_penjamin.deleted_at' => 'IS NULL',
					'AND',
					'master_penjamin.uid' => '= ?'
					),
					array(
						$parameter['uid']
					)
				)
				->execute();

		if ($penjamin['response_result'] > 0){
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
						'master_penjamin',
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

		return $penjamin;
	}

	private function delete_penjamin($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$penjamin = self::$query
			->delete($parameter[6])
			->where(array(
					$parameter[6] . '.uid' => '= ?'
				), array(
					$parameter[7]	
				)
			)
			->execute();

		if ($penjamin['response_result'] > 0){
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
		}

		return $penjamin;
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