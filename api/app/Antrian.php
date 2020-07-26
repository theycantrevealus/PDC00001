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
					return self::cari_pasien($parameter[2]);
					break;

				case 'pasien-detail':
					return self::pasien_detail($parameter[2]);
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
					->order(
						array(
							$table . '.waktu_masuk' => 'DESC'
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
						'dokter',
						'waktu_masuk'
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
		//More Info
		foreach ($data['response_data'] as $key => $value) {
			$Pasien = new Pasien(self::$pdo);
			$PasienData = $Pasien::get_pasien_detail('pasien', $value['pasien']);

			$Terminologi = new Terminologi(self::$pdo);
			$Penjamin = new Penjamin(self::$pdo);

			//Format Tanggal Lahir
			$PasienData['response_data'][0]['tanggal_lahir'] = date('d F Y', strtotime($PasienData['response_data'][0]['tanggal_lahir']));
			
			//Terminologi Jenis Kelamin
			$TerminologiJenkel = $Terminologi::get_terminologi_items_detail('terminologi_item', $PasienData['response_data'][0]['jenkel']);
			$PasienData['response_data'][0]['jenkel_nama'] = $TerminologiJenkel['response_data'][0]['nama'];

			$data['response_data'][$key]['pasien_info'] = $PasienData['response_data'][0];


			//Penjamin
			$data['response_data'][$key]['penjamin_data'] = $Penjamin::get_penjamin_detail($value['penjamin'])['response_data'][0];
		}
		return $data;
	}

	public function cari_pasien($params){
		$parameter = strtoupper($params);

		$data = self::$query
				->select('pasien', array(
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
						'pasien.nik' => 'LIKE \'%'. $parameter . '%\'',
						'OR',
						'pasien.no_rm' => 'LIKE \'%'. $parameter . '%\'',
						'OR',
						'pasien.nama' => 'LIKE \'%'. $parameter . '%\'',
						'AND',
						'pasien.deleted_at' => 'IS NULL'
					),
					array()
				)
				->order(array(
						'pasien.created_at' => 'ASC'
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

	public function pasien_detail($parameter){
		$pasien = new Pasien(self::$pdo);
		$dataPasien = null;

		$get_pasien = $pasien->get_pasien_detail('pasien', $parameter);
		if ($get_pasien['response_data'] != ""){
			$dataPasien = $get_pasien['response_data'][0];

			$term = new Terminologi(self::$pdo);
			$param_arr = ['','terminologi-items-detail', $dataPasien['jenkel']];
			$get_jenkel = $term->__GET__($param_arr);
			$dataPasien['nama_jenkel'] = $get_jenkel['response_data'][0]['nama'];
		}
		
		return $dataPasien;
	}

	private function tambah_kunjungan($table, $parameter) {
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

				//Update antrian kunjungan
				if($parameter['dataObj']['penjamin'] == __UIDPENJAMINUMUM__) {
					$antrianKunjungan = self::$query->update('antrian_nomor', array(
						'status' => 'K',
						'kunjungan' => $uid,
						'poli' => $parameter['dataObj']['departemen'],
						'pasien' => $parameter['dataObj']['currentPasien'],
						'penjamin' => $parameter['dataObj']['penjamin'],
						'dokter' => $parameter['dataObj']['dokter']
					))
					->where(array(
						'antrian_nomor.id' => '= ?',
						'AND',
						'antrian_nomor.status' => '= ?'
					), array(
						$parameter['dataObj']['currentAntrianID'],
						'D'
					))
					->execute();
					return $antrianKunjungan;
				} else {
					$checkStatusPasien = self::$query->select('antrian', array(
						'uid'
					))
					->where(array(
						'antrian.pasien' => '= ?',
						'AND',
						'antrian.deleted_at' => 'IS NULL'
					),array(
						$parameter['dataObj']['currentPasien']
					))
					->execute();


					if(count($checkStatusPasien['response_data']) > 0) {
						$antrianKunjungan = self::$query->update('antrian_nomor', array(
							'status' => 'P',
							'kunjungan' => $uid,
							'poli' => $parameter['dataObj']['departemen'],
							'pasien' => $parameter['dataObj']['currentPasien'],
							'penjamin' => $parameter['dataObj']['penjamin'],
							'dokter' => $parameter['dataObj']['dokter']
						))
						->where(array(
							'antrian_nomor.id' => '= ?',
							'AND',
							'antrian_nomor.status' => '= ?'
						), array(
							$parameter['dataObj']['currentAntrianID'],
							'D'
						))
						->execute();

						if($antrianKunjungan['response_result'] > 0) {
							unset($parameter['dataObj']['currentPasien']);
							$antrian = self::tambah_antrian('antrian', $parameter, $uid);
							return $antrian;
						} else {
							return $antrian;
						}
					} else {
						$antrianKunjungan = self::$query->update('antrian_nomor', array(
							'status' => 'K',
							'kunjungan' => $uid,
							'poli' => $parameter['dataObj']['departemen'],
							'pasien' => $parameter['dataObj']['currentPasien'],
							'penjamin' => $parameter['dataObj']['penjamin'],
							'dokter' => $parameter['dataObj']['dokter']
						))
						->where(array(
							'antrian_nomor.id' => '= ?',
							'AND',
							'antrian_nomor.status' => '= ?'
						), array(
							$parameter['dataObj']['currentAntrianID'],
							'D'
						))
						->execute();
						return $antrianKunjungan;
					}
				}
			}
		
		/*
		------------------=----- FOR TESTING ------------------------
		$antrian = self::tambah_antrian('antrian', $parameter, $uid); 
		return $antrian;
		*/
		
		//return $kunjungan;
	}

	private function tambah_antrian($table, $parameter, $uid_kunjungan){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$AntrianID = $parameter['dataObj']['currentAntrianID'];
		unset($parameter['dataObj']['currentAntrianID']);
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
			$updateNomorAntrian = self::$query->update('antrian_nomor', array(
				'antrian' => $uid
				))
				->where(array(
						'antrian_nomor.pasien' => '= ?',
						'AND',
						'antrian_nomor.poli' => '= ?',
						'AND',
						'antrian_nomor.dokter' => '= ?',
						'AND',
						'antrian_nomor.penjamin' => '= ?'
					),array(
						$allData['pasien'],
						$allData['departemen'],
						$allData['dokter'],
						$allData['penjamin']
					)
				)
				->execute();

			if($updateNomorAntrian['response_result'] > 0) {
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
				));
				return $antrian;

			} else {
				return $updateNomorAntrian;
			}
		} else {
			return $antrian;
		}
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