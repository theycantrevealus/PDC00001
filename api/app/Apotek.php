<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Antrian as Antrian;
use PondokCoder\Poli as Poli;
use PondokCoder\Inventori as Inventori;
use PondokCoder\Utility as Utility;


class Apotek extends Utility {
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
				case 'detail_resep':
					return self::detail_resep($parameter[2]);
				default:
					return self::get_resep();
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	private function get_resep($status = 'N') {
		$data = self::$query->select('resep', array(
			'uid',
			'kunjungan',
			'antrian',
			'asesmen',
			'dokter',
			'pasien',
			'verifikator',
			'total',
			'created_at',
			'updated_at'
		))
		->where(array(
			'resep.deleted_at' => 'IS NULL',
			'AND',
			'resep.status_resep' => '= ?'
		), array(
			$status
		))
		->execute();
		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			//Dokter Info
			$Pegawai = new Pegawai(self::$pdo);
			$PegawaiInfo = $Pegawai::get_detail($value['dokter']);
			$data['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

			//Get Antrian Detail
			$Antrian = new Antrian(self::$pdo);
			$AntrianInfo = $Antrian::get_antrian_detail('antrian', $value['antrian']);

			//Departemen Info
			$Poli = new Poli(self::$pdo);
			$PoliInfo = $Poli::get_poli_detail($AntrianInfo['response_data'][0]['departemen']);
			$AntrianInfo['response_data'][0]['departemen'] = $PoliInfo['response_data'][0];
			$data['response_data'][$key]['antrian'] = $AntrianInfo['response_data'][0];

			//Get resep detail
			$resep_detail = self::$query->select('resep_detail', array(
				'id',
				'resep',
				'obat',
				'harga',
				'signa_qty',
				'signa_pakai',
				'qty',
				'satuan',
				'created_at',
				'updated_at'
			))
			->where(array(
				'resep_detail.resep' => '= ?',
				'AND',
				'resep_detail.deleted_at' => 'IS NULL'
			), array(
				$value['uid']
			))
			->execute();
			foreach ($resep_detail['response_data'] as $ResKey => $ResValue) {
				$Inventori = new Inventori(self::$pdo);
				$InventoriInfo = $Inventori::get_item_detail($ResValue['obat']);
				$resep_detail['response_data'][$ResKey]['detail'] = $InventoriInfo['response_data'][0];
			}
			$data['response_data'][$key]['detail'] = $resep_detail['response_data'];


			//Racikan Item
			$racikan = self::$query->select('racikan', array(
				'uid',
				'asesmen',
				'resep',
				'kode',
				'total',
				'keterangan',
				'signa_qty',
				'signa_pakai',
				'qty',
				'created_at',
				'updated_at'
			))
			->where(array(
				'racikan.resep' => '= ?',
				'AND',
				'racikan.deleted_at' => 'IS NULL'
			), array(
				$value['uid']
			))
			->execute();
			foreach ($racikan['response_data'] as $RDKey => $RDValue) {
				$racikan_detail = self::$query->select('racikan_detail', array(
					'id',
					'asesmen',
					'resep',
					'obat',
					'ratio',
					'pembulatan',
					'satuan',
					'harga',
					'racikan',
					'penjamin',
					'created_at',
					'updated_at'
				))
				->where(array(
					'racikan_detail.deleted_at' => 'IS NULL',
					'AND',
					'racikan_detail.resep' => '= ?',
					'AND',
					'racikan_detail.racikan' => '= ?'
				), array(
					$value['uid'],
					$RDValue['uid']
				))
				->execute();
				foreach ($racikan_detail['response_data'] as $RDIKey => $RDIValue) {
					$Inventori = new Inventori(self::$pdo);
					$InventoriInfo = $Inventori::get_item_detail($RDIValue['obat']);

					$racikan_detail['response_data'][$RDIKey]['detail'] = $InventoriInfo['response_data'][0];
				}
				$racikan['response_data'][$RDKey]['detail'] = $racikan_detail['response_data'];
			}
			$data['response_data'][$key]['racikan'] = $racikan['response_data'];

			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $data;
	}
}