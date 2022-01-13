<?php

namespace PondokCoder;

use PondokCoder\Utility as Utility;

class Upload extends Utility {
	static $pdo;
	
	protected static function getConn(){
		return self::$pdo;
	}

	public function __construct($connection) {
		self::$pdo = $connection;
	}

	public function __GET__($parameter = array()) {
		$dataReturn = array();
		//
		return $dataReturn;
	}

	public function __POST__($parameter = array()) {
		$dataReturn = array();
		
		/*$file = $_FILES['upload']['name'];
		$dataReturn['file'] = $file;
		$dataReturn['tmp'] = $_FILES['upload']['tmp_name'];
		$dataReturn['result'] = move_uploaded_file($_FILES['file']['tmp_name'], "images/" . $file);*/

		$data = $parameter['upload'];

		$dataReturn['uploadResult'] = parent::saveBase64ImagePng($data, '../images/documentation/', $parameter['name']);

		return $dataReturn;
	}
}