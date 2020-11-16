<?php

namespace PondokCoder;


use PondokCoder\Poli as Poli;
use PondokCoder\Inventori as Inventori;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class CPPT extends Utility {
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
					return self::get_cppt($parameter);
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}


	public function get_cppt($parameter) {

		//Komposisi parameter
		// 2 => uid pasien
		// 3 => dari (range tanggal)
		// 4 => sampai (range tanggal)
		
		// Jika range tidak ada maka akan diambil min 3 data terakhir

		//	List dari tabel antrian => karena jika sudah bayar maka masuk ke antrian (antrian poli)
		$UIDPasien = $parameter[2];

		if(isset($parameter[3]) && isset($parameter[4])) {//Filter Tanggal
			$antrian = self::$query->select('antrian', array(
				'uid',
				'departemen',
				'kunjungan',
				'pasien',
				'dokter',
				'penjamin',
				'created_at',
				'updated_at'
			))
                ->limit(intval($_GET['pageSize']))
                ->offset(intval($_GET['pageNumber']) - 1)
                ->where(array(
                    'antrian.pasien' => '= ?',
                    'AND',
                    'antrian.deleted_at' => 'IS NULL'
                ), array(
                    $UIDPasien
                ))
                ->order(array(
                    'created_at' => 'DESC'
                ))
                ->execute();
		} else { //Keseluruhan
			$antrian = self::$query->select('antrian', array(
				'uid',
				'departemen',
				'kunjungan',
				'pasien',
				'dokter',
				'penjamin',
				'created_at',
				'updated_at'
			))
                ->limit(intval($_GET['pageSize']))
                ->offset(intval($_GET['pageNumber']) - 1)
                ->where(array(
                    'antrian.pasien' => '= ?',
                    'AND',
                    'antrian.deleted_at' => 'IS NULL'
                ), array(
                    $UIDPasien
                ))
                ->order(array(
                    'created_at' => 'DESC'
                ))
                ->execute();
		}


        $antrianTotal = self::$query->select('antrian', array(
            'uid',
            'departemen',
            'kunjungan',
            'pasien',
            'dokter',
            'penjamin',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'antrian.pasien' => '= ?',
                'AND',
                'antrian.deleted_at' => 'IS NULL'
            ), array(
                $UIDPasien
            ))
            ->order(array(
                'created_at' => 'DESC'
            ))
            ->execute();







		foreach ($antrian['response_data'] as $key => $value) {
			//Asesmen Master
			$Asesmen = self::$query->select('asesmen', array(
				'uid',
				'poli',
				'antrian',
				'pasien',
				'dokter',
				'perawat'
			))

                ->where(array(
                    'asesmen.pasien' => '= ?',
                    'AND',
                    'asesmen.antrian' => '= ?',
                    'AND',
                    'asesmen.poli' => '= ?',
                    'AND',
                    'asesmen.deleted_at' => 'IS NULL'
                ), array(
                    $UIDPasien,
                    $value['uid'],
                    $value['departemen']
                ))
                ->execute();

			//Informasi Poli
			$Poli = new Poli(self::$pdo);
			$PoliDetail = $Poli::get_poli_detail($value['departemen'])['response_data'][0];


			//Ambil detail asesmen
			$AsesmenDetail = self::$query->select('asesmen_medis_' . $PoliDetail['poli_asesmen'], array(
				'uid',
				'keluhan_utama',
				'keluhan_tambahan',
				'diagnosa_kerja',
				'diagnosa_banding'
			))
			->where(array(
				'asesmen_medis_' . $PoliDetail['poli_asesmen'] . '.asesmen' => '= ?'
			), array(
				$Asesmen['response_data'][0]['uid']
			))
			->execute();

			$antrian['response_data'][$key]['asesmen'] = $Asesmen['response_data'][0];
			$antrian['response_data'][$key]['asesmen_detail'] = $AsesmenDetail['response_data'][0];
			$antrian['response_data'][$key]['poli_detail'] = $PoliDetail;






			//Tindakan Data
			$Tindakan = self::$query->select('asesmen_tindakan', array(
				'tindakan',
				'penjamin',
				'harga',
				'kelas'
			))
			->where(array(
				'asesmen_tindakan.antrian' => '= ?',
				'AND',
				'asesmen_tindakan.asesmen' => '= ?',
				'AND',
				'asesmen_tindakan.deleted_at' => 'IS NULL'
			), array(
				$value['uid'],
				$Asesmen['response_data'][0]['uid']
			))
			->execute();

			foreach ($Tindakan['response_data'] as $TindakanKey => $TindakanValue) {
				$TindakanInfo = new Tindakan(self::$pdo);
				$TindakanDetail = $TindakanInfo::get_tindakan_detail($TindakanValue['tindakan'])['response_data'][0];
				$Tindakan['response_data'][$TindakanKey]['tindakan'] = $TindakanDetail;
			}

			$antrian['response_data'][$key]['tindakan'] = $Tindakan['response_data'];



			//Resep
			$resep = self::$query->select('resep', array(
				'uid',
				'keterangan',
				'keterangan_racikan',
				'created_at',
				'updated_at'
			))
			->where(array(
				'resep.antrian' => '= ?',
				'AND',
				'resep.asesmen' => '= ?',
				'AND',
				'resep.deleted_at' => 'IS NULL'
			), array(
				$value['uid'],
				$Asesmen['response_data'][0]['uid']
			))
			->execute();

			foreach ($resep['response_data'] as $ResepKey => $ResepValue) {
				$ResepDetail = self::$query->select('resep_detail', array(
					'obat',
					'signa_qty',
					'signa_pakai',
					'qty',
					'satuan',
					'aturan_pakai',
					'keterangan'
				))
				->where(array(
					'resep_detail.resep' => '= ?',
					'AND',
					'resep_detail.deleted_at' => 'IS NULL'
				), array(
					$ResepValue['uid']
				))
				->execute();
				foreach ($ResepDetail['response_data'] as $ResepDetailKey => $ResepDetailValue) {
					$Inventori = new Inventori(self::$pdo);
					$InventoriInfo = $Inventori::get_item_detail($ResepDetailValue['obat']);
					$ResepDetail['response_data'][$ResepDetailKey]['obat'] = $InventoriInfo['response_data'][0];

					//Aturan Pakai Detail
					$AturanPakai = self::$query->select('terminologi_item', array(
						'id',
						'nama'
					))
					->where(array(
						'terminologi_item.id' => '= ?',
						'AND',
						'terminologi_item.deleted_at' => 'IS NULL'
					), array(
						$ResepDetailValue['aturan_pakai']
					))
					->execute();
					$ResepDetail['response_data'][$ResepDetailKey]['aturan_pakai'] = $AturanPakai['response_data'][0];
				}

				$resep['response_data'][$ResepKey]['detail'] = $ResepDetail['response_data'];
			}

			$antrian['response_data'][$key]['resep'] = $resep['response_data'];
            //$antrian['response_data'][$key]['size'] = $_GET;



			//Racikan
			$racikan = self::$query->select('racikan', array(
				'uid',
				'kode',
				'keterangan',
				'signa_qty',
				'signa_pakai',
				'qty',
				'aturan_pakai'
			))
			->where(array(
				'racikan.asesmen' => '= ?',
				'AND',
				'racikan.deleted_at' => 'IS NULL'
			), array(
				$Asesmen['response_data'][0]['uid']
			))
			->execute();

			foreach ($racikan['response_data'] as $RacikanKey => $RacikaValue) {
				//Aturan Pakai Detail
				$AturanPakai = self::$query->select('terminologi_item', array(
					'id',
					'nama'
				))
				->where(array(
					'terminologi_item.id' => '= ?',
					'AND',
					'terminologi_item.deleted_at' => 'IS NULL'
				), array(
					$RacikaValue['aturan_pakai']
				))
				->execute();
				$racikan['response_data'][$RacikanKey]['aturan_pakai'] = $AturanPakai['response_data'][0];

				//Racikan Detail
				$RacikanDetail = self::$query->select('racikan_detail', array(
					'obat',
					'pembulatan',
					'ratio',
					'takar_bulat',
					'takar_decimal',
					'kekuatan'
				))
				->where(array(
					'racikan_detail.racikan' => '= ?',
					'AND',
					'racikan_detail.asesmen' => '= ?',
					'AND',
					'racikan_detail.deleted_at' => 'IS NULL'
				), array(
					$RacikaValue['uid'],
					$Asesmen['response_data'][0]['uid']
				))
				->execute();

				foreach ($RacikanDetail['response_data'] as $RacikanDetailKey => $RacikanDetailValue) {
					$Inventori = new Inventori(self::$pdo);
					$InventoriInfo = $Inventori::get_item_detail($RacikanDetailValue['obat']);

					$RacikanDetail['response_data'][$RacikanDetailKey]['obat'] = $InventoriInfo['response_data'][0];
				}

				$racikan['response_data'][$RacikanKey]['racikan_detail'] = $RacikanDetail['response_data'];
			}
		}
		$antrian['response_total'] = count($antrianTotal['response_data']);
		return $antrian;
	}
}