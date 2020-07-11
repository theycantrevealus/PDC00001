<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;
use PondokCoder\Terminologi as Terminologi;
use PondokCoder\Pasien as Pasien;
use PondokCoder\Pegawai as Pegawai;

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
					return self::get_list_antrian('antrian');
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

				case 'cek-status-antrian':
					return self::cekStatusAntrian($parameter[2]);
					break;

				/*case 'ambil-antrian-poli':
					return self::ambilNomorAntrianPoli($parameter[2]);
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
			case 'tambah-kunjungan':
				return self::tambah_kunjungan('kunjungan', $parameter);
				break;

			default:
				# code...
				break;
		}
	}	


	/*=================== GET ANTRIAN ====================*/
	private function get_list_antrian($table){
		$data = self::$query
					->select($table, 
						array(
							'uid',
							'pasien as uid_pasien',
							'dokter as uid_dokter',
							'departemen as uid_poli',
							'penjamin as uid_penjamin',
							'waktu_masuk'
						)
					)
					->join('pasien', array(
							'nama as pasien',
							'no_rm'
						)
					)
					->join('master_poli', array(
							'nama as departemen'
						)
					)
					->join('pegawai', array(
							'nama as dokter'
						)
					)
					->join('master_penjamin', array(
							'nama as penjamin'
						)
					)
					->join('kunjungan', array(
							'pegawai as uid_resepsionis'
						)
					)
					->on(array(
							array('pasien.uid','=', $table . '.pasien'),
							array('master_poli.uid','=', $table . '.departemen'),
							array('pegawai.uid','=', $table . '.dokter'),
							array('master_penjamin.uid','=', $table . '.penjamin'),
							array('kunjungan.uid','=', $table . '.kunjungan')
						)
					)
					->where(array(
							$table . '.waktu_keluar' => 'IS NULL',
							'AND',
							$table . '.deleted_at' => 'IS NULL'
						)
					)
					->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;

			$pegawai = new Pegawai(self::$pdo);
			$get_pegawai = $pegawai->get_detail($data['response_data'][$key]['uid_resepsionis']);
			$data['response_data'][$key]['user_resepsionis'] = $get_pegawai['response_data'][0]['nama'];
		}

		return $data;
	}

	public function get_antrian_detail($table, $params){
		$data = self::$query
				->select($table, array(
						'uid',
						'pasien',
						'kunjungan',
						'departemen',
						'penjamin',
						'penjamin'
					)
				)
				->where(array(
						$table . '.deleted_at' => 'IS NULL',
						'AND',
						$table . '.uid' => '= ?'
					),
					array($params)
				)
				->execute();

		return $data;
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
						'jenkel AS id_jenkel',
						'panggilan AS id_panggilan'
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

			$data['response_data'][$key]['berobat'] = self::cekStatusAntrian($value['uid']);

			$term = new Terminologi(self::$pdo);

			$param = ['','terminologi-items-detail', $value['id_panggilan']];
			$get_panggilan = $term->__GET__($param);
			$data['response_data'][$key]['panggilan'] = $get_panggilan['response_data'][0]['nama'];

			$param = ['','terminologi-items-detail', $value['id_jenkel']];
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

	private function tambah_kunjungan($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$uid = parent::gen_uuid();

		$kunjungan = self::$query
					->insert($table,
						array(
							'uid'=>$uid,
							'waktu_masuk'=>parent::format_date(),
							'pegawai'=>$UserData['data']->uid,
							'created_at'=>parent::format_date(),
							'updated_at'=>parent::format_date()
						)
					)
					->execute();

		if ($kunjungan['response_result'] > 0) {
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
								$table,
								'I',
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class'=>__CLASS__
						)
					);

				$antrian = self::tambah_antrian('antrian', $parameter, $uid); 
			}
		
		/*
		------------------=----- FOR TESTING ------------------------
		$antrian = self::tambah_antrian('antrian', $parameter, $uid); 
		return $antrian;
		*/
		
		return $kunjungan;
	}

	private function tambah_antrian($table, $parameter, $uid_kunjungan){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$uid = parent::gen_uuid();
		$no_antrian = self::ambilNomorAntrianPoli($parameter['dataObj']['departemen']); 

		$allData = [];
		$allData['uid'] = $uid;
		$allData['no_antrian'] = $no_antrian;
		$allData['kunjungan'] = $uid_kunjungan;
		$allData['waktu_masuk'] = parent::format_date();
		$allData['created_at'] = parent::format_date();
		$allData['updated_at'] = parent::format_date();

		/*=========== MATCHING VALUE WITH KEY, BECAUSE KEY NAME SAME AS FIELD NAME AT TABLE =========*/
		foreach ($parameter['dataObj'] as $key => $value) {
			$allData[$key] = $value;
		}

		$antrian = self::$query
					->insert($table, $allData)
					->execute();

		if ($antrian['response_result'] > 0) {
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
		
		return $antrian;
	}

	private function cekStatusAntrian($uid_pasien){
		$status_berobat = false;

		$data = self::$query
					->select('antrian', array(
							'uid',
							'pasien',
							'waktu_keluar'
						)
					)
					->where(array(
							'antrian.waktu_keluar' => 'IS NULL',
							'AND',
							'antrian.pasien' => '= ?'
						),
						array(
							$uid_pasien
						)
					)
					->execute();

		if (count($data['response_data']) > 0) {
			$status_berobat = true;
		}

		return $status_berobat;
	}

	private function ambilNomorAntrianPoli($poli){
		$waktu = date("Y-m-d", strtotime(parent::format_date()));

		$data = self::$query
					->select('antrian', array('no_antrian'))
					->where(array(
							'antrian.deleted_at' => 'IS NULL',
							'AND',
							'antrian.departemen' => '= ?',
							'AND',
							'DATE(antrian.waktu_masuk)' => '= ?'
						),array(
							$poli,
							$waktu
						)
					)
					->order(array('no_antrian' => 'DESC'))
					->limit(1)
					->execute();


		$nomor = 1;
		if ($data['response_result'] > 0){
			$nomor = intval($data['response_data'][0]['no_antrian']) + 1;
		}

		return $nomor;
	}
}