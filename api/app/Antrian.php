<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;
use PondokCoder\Terminologi as Terminologi;
use PondokCoder\Pasien as Pasien;

class Antrian extends Utility {
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
				case 'antrian':
					return self::get_antrian('antrian');
					break;

				case 'antrian-detail':
					return self::get_antrian_detail('antrian', $parameter[2]);
					break;

				case 'cari-pasien':
					return self::cari_pasien('pasien', $parameter[2]);
					break;

				case 'pasien-detail':
					return self::pasien_detail('pasien', $parameter[2]);
					break;

				default:
					# code...
					break;
			}
		} catch (QueryException $e) {
			return 'Error => '. $e;
		}
	}

	public function cari_pasien($table, $params){
		$parameter = strtoupper($params);

		$data = self::$query
				->select($table, array(
						'uid',
						'no_rm',
						'nik',
						'nama',
						'tanggal_lahir',
						'jenkel AS id_jenkel'
					)
				)
				->where(array(
						$table . '.nik' => 'LIKE \'%'. $parameter . '%\'',
						'OR',
						$table . '.no_rm' => 'LIKE \'%'. $parameter . '%\'',
						'OR',
						$table . '.nama' => 'LIKE \'%'. $parameter . '%\'',
						'AND',
						$table . '.deleted_at' => 'IS NULL'
					),
					array()
				)
				->order(array(
						$table . '.created_at' => 'ASC'
					)
				)
				->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;

			$term = new Terminologi(self::$pdo);

			$value = $data['response_data'][$key]['id_jenkel'];
			$param = ['','terminologi-items-detail',$value];
			$get_jenkel = $term->__GET__($param);
			$data['response_data'][$key]['jenkel'] = $get_jenkel['response_data'][0]['nama'];
		}

		return $data;
	}

	public function pasien_detail($table, $params){
		$pasien = new Pasien(self::$pdo);
		$dataPasien = null;

		$param = ['','pasien-detail',$params];
		$get_pasien = $pasien->__GET__($param);
		if ($get_pasien['response_data'] != ""){
			$dataPasien = $get_pasien['response_data'][0];

			$term = new Terminologi(self::$pdo);
			$param_arr = ['','terminologi-items-detail', $dataPasien['jenkel']];
			$get_jenkel = $term->__GET__($param_arr);
			$dataPasien['nama_jenkel'] = $get_jenkel['response_data'][0]['nama'];
		}
		
		return $dataPasien;
	}
}