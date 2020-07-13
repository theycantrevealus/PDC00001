<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Poli as Poli;
use PondokCoder\Penjamin as Penjamin;
use PondokCoder\Tindakan as Tindakan;
use PondokCoder\Utility as Utility;


class Asesmen extends Utility {
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
				case 'antrian-detail':
					return self::get_asesmen_medis($parameter[2]);
					break;
				default:
					return self::get_asesmen_medis($parameter[2]);
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function __POST__($parameter = array()) {
		try {
			switch($parameter['request']) {
				case 'update_asesmen_medis':
					return self::update_asesmen_medis($parameter);
					break;
				default:
					return 'Unknown request';
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	private function get_asesmen_medis($parameter) { //uid antrian
		//prepare antrian
		$antrian = self::$query->select('antrian', array(
			'uid',
			'kunjungan',
			'prioritas',
			'pasien',
			'departemen',
			'dokter',
			'penjamin'
		))
		->where(array(
			'antrian.uid' => '= ?',
			'AND',
			'antrian.deleted_at' => 'IS NULL'
		), array(
			$parameter
		))
		->execute();

		if(count($antrian['response_data']) > 0) {
			//Poli Info
			$Poli = new Poli(self::$pdo);
			$PoliDetail = $Poli::get_poli_detail($antrian['response_data'][0]['departemen'])['response_data'][0];

			$data = self::$query->select('asesmen_medis_' . $PoliDetail['poli_asesmen'], array(
				'uid',
				'kunjungan',
				'antrian',
				'pasien',
				'dokter',
				'keluhan_utama',
				'keluhan_tambahan',
				'tekanan_darah',
				'nadi',
				'pernafasan',
				'berat_badan',
				'tinggi_badan',
				'lingkar_lengan_atas',
				'pemeriksaan_fisik',
				'diagnosa_kerja',
				'diagnosa_banding',
				'planning',
				'asesmen',
				'suhu',
				'icd10_kerja',
				'icd10_banding',
				'created_at',
				'updated_at'
			))
			->where(array(
				'asesmen_medis_' . $PoliDetail['poli_asesmen'] . '.deleted_at' => 'IS NULL',
				'AND',
				'asesmen_medis_' . $PoliDetail['poli_asesmen'] . '.kunjungan' => '= ?',
				'AND',
				'asesmen_medis_' . $PoliDetail['poli_asesmen'] . '.antrian' => '= ?',
				'AND',
				'asesmen_medis_' . $PoliDetail['poli_asesmen'] . '.pasien' => '= ?',
				'AND',
				'asesmen_medis_' . $PoliDetail['poli_asesmen'] . '.dokter' => '= ?'
			), array(
				$antrian['response_data'][0]['kunjungan'],
				$antrian['response_data'][0]['uid'],
				$antrian['response_data'][0]['pasien'],
				$antrian['response_data'][0]['dokter']
			))
			->execute();
			if(count($data['response_data']) > 0) {
				$tindakan = self::$query->select('asesmen_tindakan', array(
					'tindakan'
				))
				->where(array(
					'asesmen_tindakan.deleted_at' => 'IS NULL',
					'AND',
					'asesmen_tindakan.kunjungan' => '= ?',
					'AND',
					'asesmen_tindakan.asesmen' => '= ?'
				), array(
					$antrian['response_data'][0]['kunjungan'],	
					$data['response_data'][0]['asesmen']
				))
				->execute();
				foreach ($tindakan['response_data'] as $key => $value) {
					$Tindakan = new Tindakan(self::$pdo);
					$tindakan['response_data'][$key] = $Tindakan::get_tindakan_detail($value['tindakan'])['response_data'][0];
				}
				$data['response_data'][0]['tindakan'] = $tindakan['response_data'];
				return $data;
			} else {
				return $antrian;
			}
		} else {
			return $antrian;
		}
	}

	private function update_asesmen_medis($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$MasterUID = '';
		//Prepare Poli
		$Poli = new Poli(self::$pdo);
		$PoliDetail = $Poli::get_poli_detail($parameter['poli'])['response_data'][0];

		//Check
		$check = self::$query->select('asesmen', array(
			'uid'
		))
		->where(array(
			'asesmen.deleted_at' => 'IS NULL',
			'AND',
			'asesmen.poli' => '= ?',
			'AND',
			'asesmen.kunjungan' => '= ?',
			'AND',
			'asesmen.antrian' => '= ?',
			'AND',
			'asesmen.pasien' => '= ?',
			'AND',
			'asesmen.dokter' => '= ?'
		), array(
			$parameter['poli'],
			$parameter['kunjungan'],
			$parameter['antrian'],
			$parameter['pasien'],
			$UserData['data']->uid
		))
		->execute();

		if(count($check['response_data']) > 0) {
			$MasterUID = $check['response_data'][0]['uid'];
			$returnResponse = array();

			//Poli Asesmen Check
			$poli_check = self::$query->select('asesmen_medis_' . $PoliDetail['poli_asesmen'], array(
				'uid'
			))
			->where(array(
				'asesmen_medis_' . $PoliDetail['poli_asesmen'] . '.deleted_at' => 'IS NULL',
				'AND',
				'asesmen_medis_' . $PoliDetail['poli_asesmen'] . '.asesmen' => '= ?'
			), array(
				$check['response_data'][0]['uid']
			))
			->execute();

			if(count($poli_check['response_data']) > 0) {
				//update
				$worker = self::$query->update('asesmen_medis_' . $PoliDetail['poli_asesmen'], array(
					'keluhan_utama' => $parameter['keluhan_utama'],
					'keluhan_tambahan' => $parameter['keluhan_tambahan'],
					'tekanan_darah' => floatval($parameter['tekanan_darah']),
					'nadi' => floatval($parameter['nadi']),
					'suhu' => floatval($parameter['suhu']),
					'pernafasan' => floatval($parameter['pernafasan']),
					'berat_badan' => floatval($parameter['berat_badan']),
					'tinggi_badan' => floatval($parameter['tinggi_badan']),
					'lingkar_lengan_atas' => floatval($parameter['lingkar_lengan_atas']),
					'pemeriksaan_fisik' => $parameter['pemeriksaan_fisik'],
					'icd10_kerja' => intval($parameter['icd10_kerja']),
					'diagnosa_kerja' => $parameter['diagnosa_kerja'],
					'icd10_banding' => intval($parameter['icd10_banding']),
					'diagnosa_banding' => $parameter['diagnosa_banding'],
					'planning' => $parameter['planning'],
					'updated_at' => parent::format_date()
				))
				->where(array(
					'asesmen_medis_' . $PoliDetail['poli_asesmen'] . '.deleted_at' => 'IS NULL',
					'AND',
					'asesmen_medis_' . $PoliDetail['poli_asesmen'] . '.uid' => '= ?',
					'AND',
					'asesmen_medis_' . $PoliDetail['poli_asesmen'] . '.kunjungan' => '= ?',
					'AND',
					'asesmen_medis_' . $PoliDetail['poli_asesmen'] . '.antrian' => '= ?',
					'AND',
					'asesmen_medis_' . $PoliDetail['poli_asesmen'] . '.pasien' => '= ?',
					'AND',
					'asesmen_medis_' . $PoliDetail['poli_asesmen'] . '.dokter' => '= ?',
					'AND',
					'asesmen_medis_' . $PoliDetail['poli_asesmen'] . '.asesmen' => '= ?'
				), array(
					$poli_check['response_data'][0]['uid'],
					$parameter['kunjungan'],
					$parameter['antrian'],
					$parameter['pasien'],
					$UserData['data']->uid,
					$check['response_data'][0]['uid']
				))
				->execute();

				if($worker['response_result'] > 0) {
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
							$check['response_data'][0]['uid'],
							$UserData['data']->uid,
							'asesmen',
							'U',
							parent::format_date(),
							'N',
							$UserData['data']->log_id
						),
						'class'=>__CLASS__
					));
				}
			} else {
				$worker = self::new_asesmen($parameter, $check['response_data'][0]['uid'], $PoliDetail['poli_asesmen']);
			}

			$returnResponse = $worker;
		} else {
			//new asesmen
			$NewAsesmen = parent::gen_uuid();
			$MasterUID = $NewAsesmen;
			$asesmen_poli = self::$query->insert('asesmen', array(
				'uid' => $NewAsesmen,
				'poli' => $parameter['poli'],
				'kunjungan' => $parameter['kunjungan'],
				'antrian' => $parameter['antrian'],
				'pasien' => $parameter['pasien'],
				'dokter' => $UserData['data']->uid,
				'created_at' => parent::format_date(),
				'updated_at' => parent::format_date()
			))
			->execute();
			if($asesmen_poli['response_result'] > 0) {

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
						$NewAsesmen,
						$UserData['data']->uid,
						'asesmen',
						'I',
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class'=>__CLASS__
				));

				$worker = self::new_asesmen($parameter, $NewAsesmen, $PoliDetail['poli_asesmen']);

				$returnResponse = $worker;
			} else {
				$returnResponse = $asesmen_poli;
			}
		}





		//Tindakan Management
		$returnResponse['tindakan_response'] = self::set_tindakan_asesment($parameter['tindakan'], $MasterUID);
		return $returnResponse;
	}

	private function set_tindakan_asesment($parameter, $MasterAsesmen) {
		$requested = array();
		foreach ($parameter as $key => $value) {
			if(!in_array($value['item'], $requested)) {
				array_push($requested, $value['item']);
			} else {
				array_push($requested, $value['item']);
			}
		}
		$returnResponse = array();
		$registered = array();

		$entry = self::$query->select('asesmen_tindakan', array(
			'uid',
			'tindakan'
		))
		->where(array(
			'asesmen_tindakan.asesmen' => '= ?'
		), array(
			$MasterAsesmen
		))
		->execute();

		foreach ($entry['response_data'] as $key => $value) {
			if(in_array($value['tindakan'], $requested)) {
				$activate = self::$query->update('asesmen_tindakan', array(
					'deleted_at' => NULL
				))
				->where(array(
					'asesmen_tindakan.asesmen' => '= ?',
					'AND',
					'asesmen_tindakan.tindakan' => '= ?'
				), array(
					$MasterAsesmen,
					$value['tindakan']
				))
				->execute();
				array_push($returnResponse, $activate);
			} else {
				$activate = self::$query->update('asesmen_tindakan', array(
					'deleted_at' => parent::format_date()
				))
				->where(array(
					'asesmen_tindakan.asesmen' => '= ?',
					'AND',
					'asesmen_tindakan.tindakan' => '= ?'
				), array(
					$MasterAsesmen,
					$value['tindakan']
				))
				->execute();
				array_push($returnResponse, $activate);
			}
			array_splice($requested, array_search($value['tindakan'], $requested), 1);
			array_splice($parameter, array_search($value['tindakan'], $requested), 1);
		}

		foreach ($parameter as $key => $value) {
			$Penjamin = self::$query
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
				'AND',
				'master_poli_tindakan_penjamin.uid_poli' => '= ?'
			), array(
				$value['item'],
				$value['penjamin'],
				$value['poli']
			))
			->execute();

			$new_asesmen_uid = parent::gen_uuid();
			$new_asesmen_tindakan = self::$query->insert('asesmen_tindakan', array(
				'uid' => $new_asesmen_uid,
				'kunjungan' => $value['kunjungan'],
				'antrian' => $value['antrian'],
				'asesmen' => $MasterAsesmen,
				'tindakan' => $value['item'],
				'penjamin' => $value['penjamin'],
				'harga' => $Penjamin['response_data'][0]['harga'],
				'created_at' => parent::format_date(),
				'updated_at' => parent::format_date()
			))
			->execute();
			array_push($returnResponse, $new_asesmen_tindakan);
		}

		return $returnResponse;
	}

	private function new_asesmen($parameter, $parent, $poli) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$NewAsesmenPoli = parent::gen_uuid();
		//insert
		$worker = self::$query->insert('asesmen_medis_' . $poli, array(
			'uid' => $NewAsesmenPoli,
			'asesmen' => $parent,
			'kunjungan' => $parameter['kunjungan'],
			'antrian' => $parameter['antrian'],
			'pasien' => $parameter['pasien'],
			'dokter' => $UserData['data']->uid,
			'keluhan_utama' => $parameter['keluhan_utama'],
			'keluhan_tambahan' => $parameter['keluhan_tambahan'],
			'tekanan_darah' => floatval($parameter['tekanan_darah']),
			'nadi' => floatval($parameter['nadi']),
			'suhu' => floatval($parameter['suhu']),
			'pernafasan' => floatval($parameter['pernafasan']),
			'berat_badan' => floatval($parameter['berat_badan']),
			'tinggi_badan' => floatval($parameter['tinggi_badan']),
			'lingkar_lengan_atas' => floatval($parameter['lingkar_lengan_atas']),
			'pemeriksaan_fisik' => $parameter['pemeriksaan_fisik'],
			'icd10_kerja' => intval($parameter['icd10_kerja']),
			'diagnosa_kerja' => $parameter['diagnosa_kerja'],
			'icd10_banding' => intval($parameter['icd10_banding']),
			'diagnosa_banding' => $parameter['diagnosa_banding'],
			'planning' => $parameter['planning'],
			'created_at' => parent::format_date(),
			'updated_at' => parent::format_date()
		))
		->execute();

		if($worker['response_result'] > 0) {
			$log = parent::log(array(
				'type'=>'activity',
				'column'=>array(
					'unique_target',
					'user_uid',
					'table_name',
					'action',
					'new_value',
					'logged_at',
					'status',
					'login_id'
				),
				'value'=>array(
					$parent,
					$UserData['data']->uid,
					'asesmen_medis_' . $PoliDetail['poli_asesmen'],
					'I',
					json_encode($parameter),
					parent::format_date(),
					'N',
					$UserData['data']->log_id
				),
				'class'=>__CLASS__
			));
		}
		return $worker;
	}
}