<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Unit extends Utility {
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
				case 'get_unit':
					return self::get_unit();
					break;
				case 'get_unit_detail':
					return self::get_unit_detail($parameter[2]);
					break;
				default:
					return 'Unknown request';
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function __POST__($parameter = array()) {
		try {
			switch($parameter['request']) {
				case 'tambah_unit':
					return self::tambah_unit();
					break;
				case 'edit_unit':
					return self::edit_unit();
					break;
				default:
					return 'Unknown request';
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function get_unit() {
		//
	}

	public function get_unit_detail($parameter) {
		$data = self::$query->select('master_unit', array(
			'uid',
			'nama',
			'kode',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_unit.uid' => '= ?',
			'AND',
			'master_unit.deleted_at' => 'IS NULL',
		), array(
			$parameter
		))
		->execute();
		return $data;
	}

	private function tambah_unit($parameter) {
		//
	}

	private function edit_unit($parameter) {
		//
	}
}