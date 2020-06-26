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
			if($parameter[1] == 'detail') {

				return self::get_detail();
				
			} else if($parameter[1] == 'duplicate_check') {
				return
					self::$query
						->select($parameter[2], array(
							'id',
							'nama'
						))

						->where(array(
							$parameter[2] . '.deleted_at' => 'IS NULL',
							'AND',
							$parameter[2] . '.nama' => '= ?'
						), array(
							$parameter[3]
						))

						->execute();

			} else if($parameter[1] == 'child') {
				return
					self::$query
						->select('terminologi_item', array(
							'id',
							'nama',
							'created_at',
							'updated_at'
						))

						->where(array(
							'terminologi_item.terminologi'=> '= ?',
							'AND',
							'deleted_at'=> 'IS NULL'
						), array(
							$parameter[2]
						))

						->execute();
			} else if($parameter[1] == 'child_detail') {
				return
					self::$query
						->select('terminologi_item', array(
							'id',
							'nama',
							'terminologi',
							'created_at',
							'updated_at'
						))

						->where(array(
							'terminologi_item.deleted_at' => 'IS NULL',
							'AND',
							'terminologi_item.id' => '= ?'
						), array(
							$parameter[2]
						))

						->execute();
			} else {
				return
					self::$query
							->select('terminologi', array(
								'id',
								'nama',
								'created_at',
								'updated_at'
							))

							->where(array(
								'terminologi.deleted_at' => 'IS NULL'
							))

							->execute();
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}



	public function __POST__($parameter = array()) {
		try{
			switch ($parameter['request']) {
				case 'tambah_terminologi':
					return self::tambah_terminologi($parameter);
					break;
				case 'edit_terminologi':
					return self::edit_terminologi($parameter);
					break;
				case 'tambah_terminologi_item':
					return self::tambah_terminologi_item($parameter);
					break;
				case 'edit_terminologi_item':
					return self::edit_terminologi_item($parameter);
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
		return self::delete_terminologi($parameter);
	}

	public function get_detail() {
		return
			self::$query
				->select('terminologi', array(
					'id',
					'nama',
					'created_at',
					'updated_at'
				))

				->where(array(
					'terminologi.deleted_at' => 'IS NULL',
					'AND',
					'terminologi.id' => '= ?'
				), array(
					$parameter[2]
				))

				->execute();
	}

	private function delete_terminologi($parameter) {
		return
			self::$query
				->delete($parameter[6])

				->where(array(
					$parameter[6] . '.id' => '= ?'
				), array(
					$parameter[7]
				))

				->execute();
	}


	private function tambah_terminologi($parameter) {
		//Duplicate Check
		$check = self::__GET__(array(
			__CLASS__, 'duplicate_check', 'terminologi', $parameter['nama']
		));
		if(count($check['response_data']) > 0) {
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			return
				self::$query
					->insert('terminologi', array(
						'nama' => $parameter['nama'],
						'created_at' => parent::format_date(),
						'updated_at' => parent::format_date()
					))

					->execute();
		}
	}

	private function edit_terminologi($parameter) {
		return
			self::$query
				->update('terminologi', array(
					'nama' => $parameter['nama'],
					'updated_at' => parent::format_date()
				))

				->where(array(
					'terminologi.deleted_at' => 'IS NULL',
					'AND',
					'terminologi.id' => '= ?'
				), array(
					$parameter['id']
				))

				->execute();
	}

	private function tambah_terminologi_item($parameter) {
		$check = self::__GET__(array(
			__CLASS__, 'duplicate_check', 'terminologi_item', $parameter['nama']
		));
		if(count($check['response_data']) > 0) {
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			return
				self::$query
					->insert('terminologi_item', array(
						'nama' => $parameter['nama'],
						'terminologi' => $parameter['terminologi'],
						'created_at' => parent::format_date(),
						'updated_at' => parent::format_date()
					))

					->execute();
		}
	}

	private function edit_terminologi_item($parameter) {
		return
			self::$query
				->update('terminologi_item', array(
					'nama' => $parameter['nama'],
					'terminologi' => $parameter['terminologi'],
					'updated_at' => parent::format_date()
				))

				->where(array(
					'terminologi_item.deleted_at' => 'IS NULL',
					'AND',
					'terminologi_item.id' => '= ?'
				), array(
					$parameter['id']
				))

				->execute();
	}
}