<?php

namespace PondokCoder;

use PondokCoder\Utility as Utility;

class Agama extends Utility {
	static $pdo;
	
	protected static function getConn(){
		return self::$pdo;
	}

	public function __construct($connection) {
		self::$pdo = $connection;
	}

	public function __GET__($parameter = array()) {


		if($parameter[1] == 'detail') {

			//__HOST__/Contoh/detail/{id}
			return self::get_detail(array(
				'uid' => $parameter[2]
			));

		} else {

			//__HOST__/Contoh
			return self::get_all();

		}

	}

	public function __POST__($parameter = array()) {
		switch ($parameter['request']) {
			case 'login':
				return self::login($parameter);
				break;
			case 'tambah_pegawai':
				break;
			case 'edit_pegawai':
				break;
			default:
				return array();
				break;
		}
	}
}