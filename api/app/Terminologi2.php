<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Terminologi extends Utility {
	static $pdo;
	static $query;
	
	protected static function getConn(){
		return self::$pdo;
	}

	public function __construct($connection) {
		self::$pdo = $connection;
		self::$query = new Query(self::$pdo);
	}

	public function __GET__($parameter = array()) {
		try {
			switch($parameter[1]) {
				case 'terminologi':
					return self::get_terminologi('terminologi');
					break;

				case 'terminologi-detail':
					return self::get_terminologi_detail('terminologi', $parameter[2]);
					break;

				case 'terminologi-items':
					return self::get_terminologi_items('terminologi_item',$parameter[2]);
					break;

				case 'terminologi-items-detail':
					return self::get_terminologi_item_detail('terminologi_item',$parameter[2]);
					break;

				case 'terminologi-items-recursive':
					return self::get_terminologi_items_recursive('terminologi_item',$parameter[2]);
					break;

				default:
					# code...
					break;
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function __POST__($parameter = array()) {
		try{
			switch ($parameter['request']) {
				case 'tambah-terminologi':
					return self::tambah_terminologi('terminologi', $parameter);
					break;
				case 'edit-terminologi':
					return self::edit_terminologi('terminologi', $parameter);
					break;

				case 'tambah-terminologi-item':
					return self::tambah_terminologi_item('terminologi_item', $parameter);
					break;
				case 'edit-terminologi-item':
					return self::edit_terminologi_item('terminologi_item', $parameter);
					break;
				default:
					return 'Unknown Request';
					break;
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}	
	}

	public function __DELETE__($parameter = array()) {
		try{
			switch ($parameter[6]) {
				case 'terminologi':
					return self::delete_terminologi('terminologi', $parameter[7]);
					break;

				case 'terminologi-item':
					return self::delete_terminologi_item('terminologi_item', $parameter[7]);
					break;

				default:
					return 'Unknown Request';
					break;
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}	
		
	}

	/*=======================GET FUNCTION======================*/
	private function get_terminologi($table){
		$data = self::$query
					->select($table, array(
						'id',
						'nama',
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
		}

		return $data;
	}

	private function get_terminologi_detail($table, $parameter){
		$data = self::$query
					->select($table, array(
						'id',
						'nama',
						'created_at',
						'updated_at'
						)
					)	
					->where(array(
							$table . '.deleted_at' => 'IS NULL',
							'AND',
							$table . '.id' => '= ?'
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


	private function get_terminologi_item_detail($table, $parameter){
		$data = self::$query
					->select($table, array(
						'id',
						'nama',
						'terminologi',
						'term_parent',
						'created_at',
						'updated_at'
						)
					)	
					->where(array(
							$table . '.deleted_at' => 'IS NULL',
							'AND',
							$table . '.id' => '= ?'
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

	private function get_terminologi_items($table, $parameter){
		$data = self::$query
					->select($table, array(
						'id',
						'nama',
						'terminologi',
						'created_at',
						'updated_at'
						)
					)	
					->where(array(
							$table . '.deleted_at' => 'IS NULL',
							'AND',
							$table . '.terminologi' => '= ?'
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

	private function get_terminologi_items_recursive($table, $parameter){
		$data = self::$query
					->select($table, array(
						'id',
						'nama',
						'terminologi',
						'created_at',
						'updated_at'
						)
					)	
					->where(array(
							$table . '.deleted_at' => 'IS NULL',
							'AND',
							$table . '.term_parent' => '= ?'
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

	/*============================= CRUD ==============================*/

	private function tambah_terminologi($table, $parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$term_name = $parameter['dataObj']['term_name'];
		$term_usage = $parameter['dataObj']['term_usage'];

		$check = self::duplicate_check(array(
			'table'=>$table,
			'check'=>$term_name
		));

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$term = self::$query
						->insert($table, array(
							'nama'=>$term_name,
							'created_at'=>parent::format_date(),
							'updated_at'=>parent::format_date()
							)
						)
						->returning('id')
						->execute();

			$last_id = $term['response_unique'];

			if ($term_usage != ""){
				foreach ($term_usage as $key => $value) {
					$dataItems = array("nama"=>$value, "id_term"=>$last_id);
					self::tambah_terminologi_item('terminologi_item', $dataItems);
				}	
			}

			if ($term['response_result'] > 0){
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
								$last_id,
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

		return $term;
	}

	private function edit_terminologi($table, $parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_terminologi_detail('terminologi', $parameter['id']);

		$term = self::$query
				->update($table, array(
					'nama' => $parameter['nama'],
					'updated_at' => parent::format_date()
				))

				->where(array(
						$table . '.deleted_at' => 'IS NULL',
						'AND',
						$table . '.id' => '= ?'
					), array(
						$parameter['id']
					)
				)
				->execute();

		if ($term['response_result'] > 0){
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

		return $term;
	}

	private function tambah_terminologi_item($table, $parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table'=>$table,
			'check'=>$parameter['nama']
		));

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$usage = self::$query
						->insert($table, array(
								'nama'=>$parameter['nama'],
								'terminologi'=>$parameter['id_term'],
								'created_at'=>parent::format_date(),
								'updated_at'=>parent::format_date()
							)
						)
					->returning('id')
					->execute();

					$last_id = $usage['response_unique'];

					if ($usage['response_result'] > 0){
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
								$last_id,
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

			return $usage;
		}
	}

	private function edit_terminologi_item($table, $parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$term = self::$query
				->update($table, array(
					'nama' => $parameter['nama'],
					'terminologi' => $parameter['id_term'],
					'updated_at' => parent::format_date()
				))

				->where(array(
						$table . '.deleted_at' => 'IS NULL',
						'AND',
						$table . '.id' => '= ?'
					), array(
						$parameter['id']
					)
				)

				->execute();
		if ($usage['response_result'] > 0){
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
							$last_id,
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

		return $usage;
	}

	private function delete_terminologi($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$term = self::$query
			->delete($table)
			->where(array(
					$table . '.id' => '= ?'
				), array(
					$parameter
				)
			)
			->execute();

		if ($term['response_result'] > 0){
			$term = self::$query
					->delete('terminologi_item')
					->where(array(
							'terminologi_item.terminologi' => '= ?'
						), array(
							$parameter
						)
					)
					->execute();


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

		return $term;
	}

	private function delete_terminologi_item($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$term = self::$query
			->delete($table)
			->where(array(
					$table . '.id' => '= ?'
				), array(
					$parameter
				)
			)
			->execute();

		if ($term['response_result'] > 0){
			$term = self::$query
					->delete('terminologi_item')
					->where(array(
							'terminologi_item.terminologi' => '= ?'
						), array(
							$parameter[6]
						)
					)
					->execute();


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
						$parameter,
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

		return $term;
	}

	/*============= FUNCTION TAMBAHAN ============*/
	private function duplicate_check($parameter) {
		return self::$query
		->select($parameter['table'], array(
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