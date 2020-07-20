<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Poli as Poli;
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
				case 'select':
					//
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
			return $data;
		} else {
			return $antrian;
		}
	}

	private function update_asesmen_medis($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

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

			return $worker;
		} else {
			//new asesmen
			$NewAsesmen = parent::gen_uuid();
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

				return $worker;
			} else {
				return $asesmen_poli;
			}
		}
	}

	private function set_tindakan_asesment($parameter) {
		foreach ($parameter['item'] as $key => $value) {
			//Check
			$check = self::$query->select('asesmen_tindakan', array(
				'uid'
			))
			->where(array(
				'asesmen_tindakan.deleted_at' => 'IS NULL',
				'AND',
				'asesmen_tindakan.kunjungan' => '= ?',
				'AND',
				'asesmen_tindakan.antrian' => '= ?',
				'AND',
				'asesmen_tindakan.tindakan' => '= ?',
				'AND',
				'asesmen_tindakan.penjamin' => '= ?'
			), array(
				$value['kunjungan'],
				$value['antrian'],
				$value['tindakan'],
				$value['penjamin']
			))
			->execute();
			if(count($check['response_data']) > 0) {
				//update
				$worker = self::$query->update('asesmen_tindakan', array(
					'harga' => $value['harga']
				))
				->where(array(
					'asesmen_tindakan.deleted_at' => 'IS NULL',
					'AND',
					'asesmen_tindakan.kunjungan' => '= ?',
					'AND',
					'asesmen_tindakan.antrian' => '= ?',
					'AND',
					'asesmen_tindakan.tindakan' => '= ?',
					'AND',
					'asesmen_tindakan.penjamin' => '= ?'
				), array(
					$value['kunjungan'],
					$value['antrian'],
					$value['tindakan'],
					$value['penjamin']
				))
				->execute();
				if($worker['response_result'] > 0) {
					//
				}
			} else {
				//insert
				$worker
			}
		}
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
					'logged_at',
					'status',
					'login_id'
				),
				'value'=>array(
					$parent,
					$UserData['data']->uid,
					'asesmen_medis_' . $PoliDetail['poli_asesmen'],
					'I',
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