<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;

class Icd extends Utility {
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
				case 'icd9':
					return self::get_icd('master_icd_9');
					break;

				case 'icd9-detail':
					return self::get_icd_detail('master_icd_9', $parameter[2]);
					break;


				case 'icd10':
					return self::get_icd('master_icd_10');
					break;

				case 'icd10-detail':
					return self::get_icd_detail('master_icd_10', $parameter[2]);
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
			case 'tambah_icd9':
				return self::tambah_icd('master_icd_9', $parameter);
				break;

			case 'edit_icd9':
				return self::edit_icd('master_icd_9', $parameter);
				break;

			case 'tambah_icd10':
				return self::tambah_icd('master_icd_10', $parameter);
				break;

			case 'edit_icd10':
				return self::edit_icd('master_icd_10', $parameter);
				break;

			case 'get_icd_10_back_end_dt':
				return self::get_icd_10_back_end_dt($parameter);
				break;

			case 'get_icd_9_back_end_dt':
				return self::get_icd_9_back_end_dt($parameter);
				break;

			default:
				# code...
				break;
		}
	}

	public function __DELETE__($parameter = array()){
		return self::delete_icd($parameter);
	}

    
    /*===============GET ICD================*/
    private function get_icd($table_name){
    	$data = self::$query
					->select($table_name, array(
						'id',
						'kode',
						'nama',
						'created_at',
						'updated_at'
						)
					)	
					->where(array(
							$table_name . '.deleted_at' => 'IS NULL'
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


    private function get_icd_10_back_end_dt($parameter) {
    	$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$columnTarget = array(
			'id',
			'kode',
			'nama',
			'created_at',
			'updated_at'
		);

		if(isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
			$paramData = array(
				'master_icd_10.deleted_at' => 'IS NULL',
				'AND',
				'(master_icd_10.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
				'OR',
				'master_icd_10.kode' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
			);

			$paramValue = array();
		} else {
			$paramData = array(
				'master_icd_10.deleted_at' => 'IS NULL'
			);

			$paramValue = array();
		}


		if($parameter['length'] < 0) {
			$data = self::$query->select('master_icd_10', $columnTarget)	
			->where($paramData, $paramValue)
			->order(array(
				$columnTarget[$parameter['order'][0]['column']] => $parameter['order'][0]['dir']
			))
			->execute();
		} else {
			$data = self::$query->select('master_icd_10', $columnTarget)	
			->where($paramData, $paramValue)
			->order(array(
				$columnTarget[$parameter['order'][0]['column']] => $parameter['order'][0]['dir']
			))
			->offset(intval($parameter['start']))
			->limit(intval($parameter['length']))
			->execute();
		}

		$data['response_draw'] = $parameter['draw'];

		$autonum = $parameter['start'] + 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		$dataTotal = self::$query->select('master_icd_10', array(
			'id',
			'kode',
			'nama',
			'created_at',
			'updated_at'
		))	
		->where($paramData, $paramValue)
		->execute();

		$data['recordsTotal'] = count($dataTotal['response_data']);
		$data['recordsFiltered'] = count($dataTotal['response_data']);
		$data['length'] = intval($parameter['length']);
		$data['start'] = intval($parameter['start']);
		$data['sort'] = $parameter;

		return $data;
    }





    private function get_icd_9_back_end_dt($parameter) {
    	$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$columnTarget = array(
			'id',
			'kode',
			'nama',
			'created_at',
			'updated_at'
		);

		if(isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
			$paramData = array(
				'master_icd_9.deleted_at' => 'IS NULL',
				'AND',
				'(master_icd_9.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
				'OR',
				'master_icd_9.kode' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
			);

			$paramValue = array();
		} else {
			$paramData = array(
				'master_icd_9.deleted_at' => 'IS NULL'
			);

			$paramValue = array();
		}


		if($parameter['length'] < 0) {
			$data = self::$query->select('master_icd_9', $columnTarget)	
			->where($paramData, $paramValue)
			->order(array(
				$columnTarget[$parameter['order'][0]['column']] => $parameter['order'][0]['dir']
			))
			->execute();
		} else {
			$data = self::$query->select('master_icd_9', $columnTarget)	
			->where($paramData, $paramValue)
			->order(array(
				$columnTarget[$parameter['order'][0]['column']] => $parameter['order'][0]['dir']
			))
			->offset(intval($parameter['start']))
			->limit(intval($parameter['length']))
			->execute();
		}

		$data['response_draw'] = $parameter['draw'];

		$autonum = $parameter['start'] + 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		$dataTotal = self::$query->select('master_icd_9', array(
			'id',
			'kode',
			'nama',
			'created_at',
			'updated_at'
		))	
		->where($paramData, $paramValue)
		->execute();

		$data['recordsTotal'] = count($dataTotal['response_data']);
		$data['recordsFiltered'] = count($dataTotal['response_data']);
		$data['length'] = intval($parameter['length']);
		$data['start'] = intval($parameter['start']);
		$data['sort'] = $parameter;

		return $data;
    }


    public function get_icd_detail($table_name, $parameter){
		$data = self::$query
				->select($table_name, array(
						'id',
						'kode',
						'nama',
						'created_at',
						'updated_at'					)
				)
				->where(array(
							$table_name . '.deleted_at' => 'IS NULL',
							'AND',
							$table_name . '.id' => '= ?'
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

	private function tambah_icd($table_name, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table'=>$table_name,
			'check'=>array($parameter['nama'],$parameter['kode'])
		));

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$icd = self::$query
						->insert($table_name, array(
								'kode'=>$parameter['kode'],
								'nama'=>$parameter['nama'],
								'created_at'=>parent::format_date(),
								'updated_at'=>parent::format_date()
								)
						)->execute();

			if ($icd['response_result'] > 0){
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

			return $icd;

		}
	}

	private function edit_icd($table_name, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_icd_detail($table_name, $parameter['uid']);

		$icd = self::$query
				->update($table_name, array(
						'kode'=>$parameter['kode'],
						'nama'=>$parameter['nama'],
						'updated_at'=>parent::format_date()
					)
				)
				->where(array(
					$table_name . '.deleted_at' => 'IS NULL',
					'AND',
					$table_name . '.id' => '= ?'
					),
					array(
						$parameter['id']
					)
				)
				->execute();

		if ($icd['response_result'] > 0){
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
						$table_name,
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

		return $icd;
	}

	private function delete_icd($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$icd = self::$query
			->delete($parameter[6])
			->where(array(
					$parameter[6] . '.id' => '= ?'
				), array(
					$parameter[7]	
				)
			)
			->execute();

		if ($icd['response_result'] > 0){
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

		return $icd;
	}


	private function duplicate_check($parameter) {
		return self::$query
		->select($parameter['table'], array(
			'id',
			'nama'
		))
		->where(array(
			$parameter['table'] . '.deleted_at' => 'IS NULL',
			'AND',
			'(' . $parameter['table'] . '.nama' => '= ?',
			'OR',
			$parameter['table'] . '.kode' => '= ?)' 
		), array(
			$parameter['check']['nama'],
			$parameter['check']['kode']
		))
		->execute();
	}


}