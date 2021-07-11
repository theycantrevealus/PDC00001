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

    public function __POST__($parameter = array()) {
        try {

            switch($parameter['request']) {
                case 'group_tanggal':
                    return self::group_tanggal($parameter);
                    break;
                default:
                    return self::get_cppt($parameter);
            }
        } catch (QueryException $e) {
            return 'Error => ' . $e;
        }
    }




	public function get_cppt_single($parameter) {
	    //
    }

    public function group_tanggal($parameter) {
	    $GroupTanggal = array();
	    $Antrian = self::$query->select('antrian', array(
	        'uid',
	        'kunjungan',
	        'departemen',
            'dokter',
            'waktu_masuk'
        ))
            ->where(array(
                'antrian.waktu_keluar' => 'IS NOT NULL',
                'AND',
                'antrian.deleted_at' => 'IS NULL',
                'AND',
                'antrian.pasien' => '= ?',
                'AND',
                'antrian.waktu_masuk' => 'BETWEEN ? AND ?'
            ), array(
                $parameter['pasien'], $parameter['from'], $parameter['to']
            ))
            ->order(array(
                'created_at' => 'DESC'
            ))
            ->execute();
	    $Poli = new Poli(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);
	    $ICD10 = new Icd(self::$pdo);
        $Inventori = new Inventori(self::$pdo);
        $Tindakan = new Tindakan(self::$pdo);
	    foreach ($Antrian['response_data'] as $key => $value) {
	        if($value['uid'] !== $parameter['current']) {
                $GrouperName = date('Y-m-d', strtotime($value['waktu_masuk']));
                $GrouperChild = date('H:i:s', strtotime($value['waktu_masuk']));
                if(!isset($GroupTanggal[$GrouperName])) {
                    $GroupTanggal[$GrouperName] = array(
                        'data' => array(),
                        'parsed' => parent::dateToIndo(date('Y-m-d', strtotime($value['waktu_masuk'])))
                    );
                }

                if(!isset($GroupTanggal[$GrouperName]['data'][$GrouperChild])) {
                    $GroupTanggal[$GrouperName]['data'][$GrouperChild] = array(
                        'data' => array(),
                        'parsed' => date('H:i', strtotime($value['waktu_masuk']))
                    );
                }


                //Prepare Data
                $Departemen = $Poli->get_poli_info($value['departemen'])['response_data'][0];
                $Antrian['response_data'][$key]['departemen'] = $Departemen;

                $Dokter = $Pegawai->get_detail($value['dokter'])['response_data'][0];
                $Antrian['response_data'][$key]['dokter'] = $Dokter;

                $Asesmen = self::$query->select('asesmen', array(
                    'uid'
                ))
                    ->join('asesmen_medis_' . $Departemen['poli_asesmen'], array(
                        'keluhan_utama',
                        'keluhan_tambahan',
                        'pemeriksaan_fisik',
                        'diagnosa_kerja',
                        'diagnosa_banding',
                        'icd10_kerja',
                        'icd10_banding',
                        'planning'
                    ))
                    ->on(array(
                        array('asesmen_medis_' . $Departemen['poli_asesmen'] . '.asesmen', '=', 'asesmen.uid')
                    ))
                    ->where(array(
                        'asesmen.antrian' => '= ?',
                        'AND',
                        'asesmen.kunjungan' => '= ?',
                        'AND',
                        'asesmen.deleted_at' => 'IS NULL'
                    ), array(
                        $value['uid'], $value['kunjungan']
                    ))
                    ->execute();

                $parseICD10Kerja = array();
                $ICD10Kerja = explode(',', $Asesmen['response_data'][0]['icd10_kerja']);
                foreach ($ICD10Kerja as $ICD10KerjaKey => $ICD10KerjaValue) {
                    array_push($parseICD10Kerja, $ICD10->get_icd_detail('master_icd_10', $ICD10KerjaValue)['response_data'][0]);
                }
                $Asesmen['response_data'][0]['icd10_kerja'] = $parseICD10Kerja;


                $parseICD10Banding = array();
                $ICD10Banding = explode(',', $Asesmen['response_data'][0]['icd10_banding']);
                foreach ($ICD10Banding as $ICD10BandingKey => $ICD10BandingValue) {
                    array_push($parseICD10Banding, $ICD10->get_icd_detail('master_icd_10', $ICD10BandingValue)['response_data'][0]);
                }
                $Asesmen['response_data'][0]['icd10_banding'] = $parseICD10Banding;


                //Tindakan
                $TindakanList = self::$query->select('asesmen_tindakan', array(
                    'tindakan'
                ))
                    ->where(array(
                        'asesmen_tindakan.asesmen' => '= ?',
                        'AND',
                        'asesmen_tindakan.kunjungan' => '= ?',
                        'AND',
                        'asesmen_tindakan.antrian' => '= ?'
                    ), array(
                        $Asesmen['response_data'][0]['uid'],
                        $value['kunjungan'],
                        $value['uid']
                    ))
                    ->execute();
                foreach ($TindakanList['response_data'] as $TindKey => $TindValue) {
                    $TindakanList['response_data'][$TindKey]['tindakan'] = $Tindakan->get_tindakan_info($TindValue['tindakan'])['response_data'][0];
                }
                $Asesmen['response_data'][0]['tindakan'] = $TindakanList['response_data'];

                //Resep
                $Resep = self::$query->select('resep', array(
                    'uid',
                    'status_resep',
                    'keterangan',
                    'keterangan_racikan',
                    'apoteker',
                    'alergi_obat',
                    'kode'
                ))
                    ->where(array(
                        'resep.kunjungan' => '= ?',
                        'AND',
                        'resep.antrian' => '= ?',
                        'AND',
                        'resep.deleted_at' => 'iS NULL'
                    ), array(
                        $value['kunjungan'],
                        $value['uid']
                    ))
                    ->execute();
                foreach ($Resep['response_data'] as $RespKey => $RespValue) {
                    //GetDetail
                    $DetailResep = self::$query->select('resep_detail', array(
                        'obat',
                        'signa_qty',
                        'signa_pakai',
                        'qty',
                        'aturan_pakai',
                        'keterangan'
                    ))
                        ->where(array(
                            'resep_detail.resep' => '= ?',
                            'AND',
                            'resep_detail.deleted_at' => 'IS NULL'
                        ), array(
                            $RespValue['uid']
                        ))
                        ->execute();
                    foreach ($DetailResep['response_data'] as $DetailRespKey => $DetailRespValue) {
                        $DetailResep['response_data'][$DetailRespKey]['obat'] = $Inventori->get_item_detail($DetailRespValue['obat'])['response_data'][0];
                    }
                    $Resep['response_data'][$RespKey]['detail'] = $DetailResep['response_data'];


                    //Change
                    $DetailResepApotek = self::$query->select('resep_change_log', array(
                        'item',
                        'signa_qty',
                        'signa_pakai',
                        'aturan_pakai',
                        'keterangan',
                        'qty',
                        'verifikator'
                    ))
                        ->where(array(
                            'resep_change_log.resep' => '= ?',
                            'AND',
                            'resep_change_log.deleted_at' => 'IS NULL'
                        ), array(
                            $RespValue['uid']
                        ))
                        ->execute();
                    foreach ($DetailResepApotek['response_data'] as $DetailRespApotekKey => $DetailRespApotekValue) {
                        $DetailResepApotek['response_data'][$DetailRespApotekKey]['item'] = $Inventori->get_item_detail($DetailRespApotekValue['item'])['response_data'][0];
                    }
                    $Resep['response_data'][$RespKey]['detail_apotek'] = $DetailResepApotek['response_data'];
                }
                $Asesmen['response_data'][0]['resep'] = $Resep['response_data'];



                //Racikan
                $Racikan = self::$query->select('racikan', array(
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
                foreach ($Racikan['response_data'] as $RacKey => $RacValue) {
                    $DetailRacikan = self::$query->select('racikan_detail', array(
                        'obat',
                        'kekuatan'
                    ))
                        ->where(array(
                            'racikan_detail.racikan' => '= ?',
                            'AND',
                            'racikan_detail.deleted_at' => 'IS NULL'
                        ), array(
                            $RacValue['uid']
                        ))
                        ->execute();
                    foreach ($DetailRacikan['response_data'] as $RacDetailKey => $RacDetailValue) {
                        $DetailRacikan['response_data'][$RacDetailKey]['obat'] = $Inventori->get_item_detail($RacDetailValue['obat'])['response_data'][0];
                    }
                    $Racikan['response_data'][$RacKey]['detail'] = $DetailRacikan['response_data'];

                    //Racikan Apotek
                    $RacikanApotek = self::$query->select('racikan_change_log', array(
                        'jumlah',
                        'signa_qty',
                        'signa_pakai',
                        'aturan_pakai'
                    ))
                        ->where(array(
                            'racikan_change_log.racikan' => '= ?',
                            'AND',
                            'racikan_change_log.deleted_at' => 'IS NULL'
                        ), array(
                            $RacValue['uid']
                        ))
                        ->execute();
                    foreach ($RacikanApotek['response_data'] as $RacApotekKey => $RacApotekValue) {
                        $DetailRacikanApotek = self::$query->select('racikan_detail_change_log', array(
                            'obat', 'kekuatan', 'jumlah'
                        ))
                            ->where(array(
                                'racikan_detail_change_log.racikan' => '= ?',
                                'AND',
                                'racikan_detail_change_log.deleted_at' => 'IS NULL'
                            ), array(
                                $RacValue['uid']
                            ))
                            ->execute();
                        foreach ($DetailRacikanApotek['response_data'] as $DetailRacikanApotekKey => $DetailRacikanApotekValue) {
                            $DetailRacikanApotek['response_data'][$DetailRacikanApotekKey]['obat'] = $Inventori->get_item_detail($DetailRacikanApotekValue['obat'])['response_data'][0];
                        }
                        $RacikanApotek['response_data'][$RacApotekKey]['detail'] = $DetailRacikanApotek['response_data'];
                    }

                    $Racikan['response_data'][$RacKey]['racikan_apotek'] = $RacikanApotek['response_data'];
                }
                $Asesmen['response_data'][0]['racikan'] = $Racikan['response_data'];



                $Antrian['response_data'][$key]['asesmen'] = $Asesmen['response_data'][0];

                array_push($GroupTanggal[$GrouperName]['data'][$GrouperChild]['data'], $Antrian['response_data'][$key]);

            }
        }

	    return $GroupTanggal;
    }


	public function get_cppt($parameter) {

		//Komposisi parameter
		// 2 => uid pasien
		// 3 => dari (range tanggal)
		// 4 => sampai (range tanggal)
		
		// Jika range tidak ada maka akan diambil min 3 data terakhir

		//	List dari tabel antrian => karena jika sudah bayar maka masuk ke antrian (antrian poli)
        $CurrentAntrian = ($parameter[2] === 'all') ? parent::gen_uuid() : $parameter[2];
        $UIDPasien = $parameter[3];


		if(isset($parameter[4]) && isset($parameter[5])) {//Filter Tanggal
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
                    'antrian.deleted_at' => 'IS NULL',
                    'AND',
                    'NOT antrian.uid' => '= ?'
                ), array(
                    $UIDPasien, $CurrentAntrian
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
                    'antrian.deleted_at' => 'IS NULL',
                    'AND',
                    'NOT antrian.uid' => '= ?'
                ), array(
                    $UIDPasien, $CurrentAntrian
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
                'antrian.deleted_at' => 'IS NULL',
                'AND',
                'NOT antrian.uid' => '= ?'
            ), array(
                $UIDPasien, $CurrentAntrian
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
				'perawat2'
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
				'diagnosa_banding',
                'planning',
                'pemeriksaan_fisik'
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