<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Poli as Poli;
use PondokCoder\Penjamin as Penjamin;
use PondokCoder\Tindakan as Tindakan;
use PondokCoder\Inventori as Inventori;
use PondokCoder\Utility as Utility;
use PondokCoder\Antrian as Antrian;
use PondokCoder\Invoice as Invoice;
use PondokCoder\Icd as Icd;


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

                case 'antrian-detail-record':
                    return self::get_asesmen_medis($parameter[2], true);
                    break;

				case 'asesmen-rawat-detail':
					return self::get_asesmen_rawat($parameter[2]);
					break;

				case 'antrian-asesmen-rawat':
					return self::get_antrian_asesmen_rawat($parameter);
					break;

				case 'antrian-asesmen-medis':
					return self::get_antrian_asesmen_medis($parameter);
					break;

				default:
					//return self::get_asesmen_medis($parameter[2]);
                    return 'Unknown Request';
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function __POST__($parameter = array()) {
		try {
			switch($parameter['request']) {
                case 'asesmen_progressing':
                    return self::order_asesmen($parameter);
                    break;

				case 'update_asesmen_medis':
					return self::update_asesmen_medis($parameter);
					break;

				case 'update_asesmen_rawat':
					return self::update_asesmen_rawat($parameter);
					break;

                case 'pasien_saya':
                    return self::pasien_saya($parameter);
                    break;

                case 'update_igd_infus':
                    return self::update_igd_infus($parameter);
                    break;

                case 'hapus_igd_infus':
                    return self::hapus_igd_infus($parameter);
                    break;

				default:
					return 'Unknown request';
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

    private function hapus_igd_infus($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $process = self::$query->update('asesmen_igd_infus', array(
            'deleted_at' => parent::format_date()
        ))
            ->where(array(
                'asesmen_igd_infus.asesmen' => '= ?',
                'AND',
                'asesmen_igd_infus.id' => '= ?'
            ), array(
                $parameter['asesmen'],
                $parameter['id']
            ))
            ->execute();
        if($process['response_result'] > 0) {
            $log = parent::log(array(
                'type' => 'activity',
                'column' => array(
                    'unique_target',
                    'user_uid',
                    'table_name',
                    'action',
                    'logged_at',
                    'status',
                    'login_id'
                ),
                'value' => array(
                    $parameter['id'],
                    $UserData['data']->uid,
                    'asesmen_igd_infus',
                    'D',
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class' => __CLASS__
            ));
        }

        return $process;
    }

	private function update_igd_infus($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);


        $parental_asesmen = self::update_asesmen_rawat($parameter);
        $process = self::$query->insert('asesmen_igd_infus', array(
            'asesmen' => $parental_asesmen['master_uid'],
            'pukul' => date('H:i:s'),
            'obat' => $parameter['obat'],
            'dosis' => $parameter['dosis'],
            'rute' => $parameter['rute'],
            'keputusan' => $parameter['libat'],
            'oleh' => $UserData['data']->uid,
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date(),
            'igd_type' => $parameter['target']
        ))
            ->execute();
        return $process;
    }

	private function order_asesmen($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $proceed = self::$query->insert('asesmen', array(
            'uid' => parent::gen_uuid(),
            'poli' => $parameter['poli'],
            'kunjungan' => $parameter['kunjungan'],
            'antrian' => $parameter['antrian'],
            'pasien' => $parameter['pasien'],
            'dokter' => $UserData['data']->uid,
            'perawat' => $parameter['perawat'],
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date(),
            'status' => 'N'
        ))
            ->execute();

	    return $proceed;
    }

	private function pasien_saya($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $Laboratorium = new Laboratorium(self::$pdo);
        $Pasien = new Pasien(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);
        $Poli = new Poli(self::$pdo);
        $Tindakan = new Tindakan(self::$pdo);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'asesmen.deleted_at' => 'IS NULL',
                'AND',
                'asesmen.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'asesmen.dokter' => '= ?',
                'AND',
                '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
            );

            $paramValue = array(
                date('Y-m-d', strtotime($parameter['from']) - 86400), date('Y-m-d', strtotime($parameter['to']) + 86400), $UserData['data']->uid
            );
        } else {
            $paramData = array(
                'asesmen.deleted_at' => 'IS NULL',
                'AND',
                'asesmen.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'asesmen.dokter' => '= ?'
            );

            $paramValue = array(
                date('Y-m-d', strtotime($parameter['from']) - 86400), date('Y-m-d', strtotime($parameter['to']) + 86400), $UserData['data']->uid
            );
        }

        if (intval($parameter['length']) > -1) {
            $data = self::$query->select('asesmen', array(
                'uid',
                'poli',
                'kunjungan',
                'pasien',
                'antrian',
                'dokter',
                'perawat',
                'status',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->join('pasien', array(
                    'nama',
                    'nik',
                    'no_rm'
                ))
                ->on(array(
                    array('asesmen.pasien', '=', 'pasien.uid')
                ))
                ->execute();
        } else {
            $data = self::$query->select('asesmen', array(
                'uid',
                'poli',
                'kunjungan',
                'pasien',
                'antrian',
                'dokter',
                'perawat',
                'status',
                'created_at',
                'updated_at'
            ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->where($paramData, $paramValue)
                ->join('pasien', array(
                    'nama',
                    'nik',
                    'no_rm'
                ))
                ->on(array(
                    array('asesmen.pasien', '=', 'pasien.uid')
                ))
                ->execute();
        }

        $data['response_draw'] = intval($parameter['draw']);

        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {

            //Pasien
            $PasienInfo = $Pasien->get_pasien_info('pasien', $value['pasien']);
            $data['response_data'][$key]['pasien'] = $PasienInfo['response_data'][0];

            //Poli
            $PoliInfo = $Poli->get_poli_info($value['poli']);
            $data['response_data'][$key]['poli'] = $PoliInfo['response_data'][0];

            //Lab Order
            $lab = self::$query->select('lab_order', array(
                'uid',
                'dr_penanggung_jawab',
                'tanggal_sampling',
                'no_order',
                'status',
                'kesan',
                'anjuran',
                'created_at'
            ))
                ->where(array(
                    'lab_order.asesmen' => '= ?',
                    'AND',
                    'lab_order.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();



            //Order Detail
            foreach($lab['response_data'] as $LabKey => $LabValue) {
                $Petugas = array();
                $PetugasChecker = array();

                $detailLabOrderDoc = self::$query->select('lab_order_document', array(
                    'lampiran'
                ))
                    ->where(array(
                        'lab_order_document.lab_order' => '= ?',
                        'AND',
                        'lab_order_document.deleted_at' => 'IS NULL'
                    ), array(
                        $LabValue['uid']
                    ))
                    ->execute();
                $lab['response_data'][$LabKey]['document'] = $detailLabOrderDoc['response_data'];

                $detailLaborOrder = self::$query->select('lab_order_detail', array(
                    'tindakan',
                    'keterangan',
                    'dpjp'
                ))
                    ->where(array(
                        'lab_order_detail.lab_order' => '= ?',
                        'AND',
                        'lab_order_detail.deleted_at' => 'IS NULL'
                    ), array(
                        $LabValue['uid']
                    ))
                    ->execute();

                foreach ($detailLaborOrder['response_data'] as $LabDetailKey => $LabDetailValue) {
                    $DPJP = $Pegawai->get_detail($LabDetailValue['dpjp']);
                    $detailLaborOrder['response_data'][$LabDetailKey]['dpjp_detail'] = $DPJP['response_data'][0];


                    $LabTindakan = $Laboratorium->get_lab_detail($LabDetailValue['tindakan']);
                    $detailLaborOrder['response_data'][$LabDetailKey]['tindakan'] = $LabTindakan['response_data'][0];

                    $nilaiLaborOrder = self::$query->select('lab_order_nilai', array(
                        'tindakan',
                        'id_lab_nilai',
                        'nilai',
                        'petugas'
                    ))
                        ->where(array(
                            'lab_order_nilai.lab_order' => '= ?',
                            'AND',
                            'lab_order_nilai.deleted_at' => 'IS NULL'
                        ), array(
                            $LabValue['uid']
                        ))
                        ->execute();
                    foreach ($nilaiLaborOrder['response_data'] as $LabOrderDetailItemKey => $LabOrderDetailItemValue) {

                        if(!in_array($LabOrderDetailItemValue['petugas'], $PetugasChecker)) {
                            $PetugasDetail = $Pegawai->get_detail_pegawai($LabOrderDetailItemValue['petugas'])['response_data'][0];
                            array_push($Petugas, $PetugasDetail);
                            array_push($PetugasChecker, $LabOrderDetailItemValue['petugas']);
                        }


                        $LabItem = self::$query->select('master_lab_nilai', array(
                            'satuan',
                            'nilai_maks',
                            'nilai_min',
                            'keterangan'
                        ))
                            ->where(array(
                                'master_lab_nilai.id' => '= ?'/*,
                                'AND',
                                'master_lab_nilai.deleted_at' => 'IS NULL'*/
                            ), array(
                                $LabOrderDetailItemValue['id_lab_nilai']
                            ))
                            ->execute();

                        $nilaiLaborOrder['response_data'][$LabOrderDetailItemKey]['lab_nilai'] = $LabItem['response_data'][0];
                    }

                    $detailLaborOrder['response_data'][$LabDetailKey]['hasil'] = $nilaiLaborOrder['response_data'];
                }

                $lab['response_data'][$LabKey]['detail'] = $detailLaborOrder['response_data'];
                $lab['response_data'][$LabKey]['petugas'] = $Petugas;
                $lab['response_data'][$LabKey]['parse_tanggal'] = date('d F Y', strtotime($LabValue['created_at']));
                $lab['response_data'][$LabKey]['dr_penanggung_jawab'] = $Pegawai->get_detail_pegawai($LabValue['dr_penanggung_jawab'])['response_data'][0];
            }

            $data['response_data'][$key]['lab_order'] = $lab['response_data'];

            //Rad Order
            $rad = self::$query->select('rad_order', array(
                'uid',
                'petugas',
                'dokter_radio',
                'no_order',
                'selesai',
                'created_at'
            ))
                ->where(array(
                    'rad_order.asesmen' => '= ?',
                    'AND',
                    'rad_order.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();


            foreach($rad['response_data'] as $RadKey => $RadValue) {

                $rad['response_data'][$RadKey]['petugas'] = $Pegawai->get_detail_pegawai($RadValue['petugas'])['response_data'][0];
                $rad['response_data'][$RadKey]['dokter_radio'] = $Pegawai->get_detail_pegawai($RadValue['dokter_radio'])['response_data'][0];
                $detailRadOrder = self::$query->select('rad_order_detail', array(
                    'tindakan',
                    'keterangan',
                    'kesimpulan'
                ))
                    ->where(array(
                        'rad_order_detail.radiologi_order' => '= ?',
                        'AND',
                        'rad_order_detail.deleted_at' => 'IS NULL'
                    ), array(
                        $RadValue['uid']
                    ))
                    ->execute();
                foreach ($detailRadOrder['response_data'] as $radItemKey => $radItemValue) {
                    $detailRadOrder['response_data'][$radItemKey]['tindakan'] = $Tindakan->get_tindakan_info($radItemValue['tindakan'])['response_data'][0];
                }
                $rad['response_data'][$RadKey]['detail'] = $detailRadOrder['response_data'];

                $rad['response_data'][$RadKey]['created_at'] = date('d F Y', strtotime($RadValue['created_at']));

                $detailRadOrderDoc = self::$query->select('rad_order_document', array(
                    'lampiran'
                ))
                    ->where(array(
                        'rad_order_document.radiologi_order' => '= ?',
                        'AND',
                        'rad_order_document.deleted_at' => 'IS NULL'
                    ), array(
                        $RadValue['uid']
                    ))
                    ->execute();
                $rad['response_data'][$RadKey]['document'] = $detailRadOrderDoc['response_data'];
            }

            $data['response_data'][$key]['rad_order'] = $rad['response_data'];


            $data['response_data'][$key]['tanggal_kunjungan'] = date('d F Y', strtotime($value['created_at']));
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }

        $PasienTotal = self::$query->select('asesmen', array(
            'uid'
        ))
            ->where($paramData, $paramValue)
            ->join('pasien', array(
                'uid',
                'nama',
                'nik',
                'no_rm'
            ))
            ->on(array(
                array('asesmen.pasien', '=', 'pasien.uid')
            ))
            ->execute();


        $data['recordsTotal'] = count($PasienTotal['response_data']);
        $data['recordsFiltered'] = count($data);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

	public function get_asesmen_medis($parameter, $isCPPT = false) { //uid antrian
        $ICD10 = new Icd(self::$pdo);
        $ICD9 = new Icd(self::$pdo);
        $Tindakan = new Tindakan(self::$pdo);
        $Inventori = new Inventori(self::$pdo);
        $Pasien = new Pasien(self::$pdo);
        $Poli = new Poli(self::$pdo);
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
        $AsesmenMaster = self::$query->select('asesmen', array(
            'uid',
            'status',
            'created_at'
        ))
            ->where(array(
                'asesmen.deleted_at' => 'IS NULL',
                'AND',
                'asesmen.kunjungan' => '= ?',
                'AND',
                'asesmen.antrian' => '= ?',
                'AND',
                'asesmen.pasien' => '= ?',
                'AND',
                'asesmen.dokter' => '= ?'
            ), array(
                $antrian['response_data'][0]['kunjungan'],
                $antrian['response_data'][0]['uid'],
                $antrian['response_data'][0]['pasien'],
                $antrian['response_data'][0]['dokter']
            ))
            ->execute();

		if(count($antrian['response_data']) > 0) {



			//Poli Info
			if($antrian['response_data'][0]['departemen'] === __POLI_INAP__) {
                $PoliDetail = array(
                    'uid' => __POLI_INAP__,
                    'nama' => 'Rawat Inap',
                    'poli_asesmen' => 'inap'
                );
            } else {
                $PoliDetail = $Poli->get_poli_detail($antrian['response_data'][0]['departemen'])['response_data'][0];
            }



			$Rawat = self::$query->select('asesmen_rawat_' . $PoliDetail['poli_asesmen'], array(
				'uid'
			))
			->where(array(
				'asesmen_rawat_' . $PoliDetail['poli_asesmen'] . '.antrian' => '= ?'
			), array(
				$antrian['response_data'][0]['uid']
			))
			->execute();


			if($antrian['response_data'][0]['departemen'] === __UIDFISIOTERAPI__)
            {
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
                    'icd9',
                    'anamnesa',
                    'tatalaksana',
                    'evaluasi',
                    'anjuran_bulan',
                    'anjuran_minggu',
                    'suspek_akibat_kerja',
                    'hasil',
                    'kesimpulan',
                    'rekomendasi',
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
            } else if($antrian['response_data'][0]['departemen'] === __POLI_IGD__) {
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

                    'gcs_e',
                    'gcs_v',
                    'gcs_m',
                    'gcs_tot',
                    'status_alergi',
                    'status_alergi_text',
                    'refleks_cahaya',
                    'pupil',
                    'refleks_cahaya',
                    'rr',
                    'gangguan_perilaku',
                    'gangguan_terganggu',
                    'skala_nyeri',
                    'lokasi',
                    'frekuensi',
                    'karakter_nyeri',
                    'karakter_nyeri_text',
                    'skor_nyeri',
                    'tipe_nyeri',
                    'ats_list',
                    'ats_skala',
                    'ekg',
                    'saved_lokalis_item',
                    'skala_rasa_sakit',

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
            } else if($antrian['response_data'][0]['departemen'] === __POLI_GIGI__) {
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
                    'odontogram',
                    'muka_simetris',
                    'tmj',
                    'bibir',
                    'lidah',
                    'mukosa',
                    'torus',
                    'gingiva',
                    'frenulum',
                    'kebersihan_mulut',
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
            } else if($antrian['response_data'][0]['departemen'] === __POLI_MATA__) {
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
                    'meta_resep',
                    'tujuan_resep',
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
            } else {
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
            }

			if(count($data['response_data']) > 0) {

				//Parse ICDData
                foreach ($data['response_data'] as $ICD10Key => $ICD10Value) {
					$ICD10KerjaRaw = explode(',', $ICD10Value['icd10_kerja']);
					$ICD10KerjaJoined = array();
					foreach ($ICD10KerjaRaw as $ICD10KRKey => $ICD10KRValue) {

						$parseICD10 = $ICD10->get_icd_detail('master_icd_10', $ICD10KRValue);
						if(count($parseICD10['response_data']) > 0) {
							array_push($ICD10KerjaJoined, array(
								'id' => $ICD10KRValue,
								'nama' => $parseICD10['response_data'][0]['kode'] . ' - ' .$parseICD10['response_data'][0]['nama']
							));
						}
					}
					$data['response_data'][$ICD10Key]['icd10_kerja'] = $ICD10KerjaJoined;
					

					$ICD10BandingRaw = explode(',', $ICD10Value['icd10_banding']);
					$ICD10BandingJoined = array();
					foreach ($ICD10BandingRaw as $ICD10BRKey => $ICD10BRValue) {
						$parseICD10 = $ICD10->get_icd_detail('master_icd_10', $ICD10BRValue);
						if(count($parseICD10['response_data']) > 0) {
							array_push($ICD10BandingJoined, array(
								'id' => $ICD10BRValue,
								'nama' => $parseICD10['response_data'][0]['kode'] . ' - ' .$parseICD10['response_data'][0]['nama']
							));
						}
					}
					$data['response_data'][$ICD10Key]['icd10_banding'] = $ICD10BandingJoined;

                    if($antrian['response_data'][0]['departemen'] === __UIDFISIOTERAPI__)
                    {
                        $ICD9Raw = explode(',', $ICD10Value['icd9']);
                        $ICD9Joined = array();
                        foreach ($ICD9Raw as $ICD9Key => $ICD9Value) {

                            $parseICD9 = $ICD9->get_icd_detail('master_icd_9', $ICD9Value);
                            if(count($parseICD9['response_data']) > 0) {
                                array_push($ICD9Joined, array(
                                    'id' => $ICD9Value,
                                    'nama' => $parseICD9['response_data'][0]['kode'] . ' - ' .$parseICD9['response_data'][0]['nama']
                                ));
                            }
                        }
                        $data['response_data'][$ICD10Key]['icd9'] = $ICD9Joined;
                    }
				}








				//Tindakan Detail
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

					$tindakan['response_data'][$key] = $Tindakan->get_tindakan_detail($value['tindakan'])['response_data'][0];
				}

				$data['response_data'][0]['tindakan'] = $tindakan['response_data'];













				//Resep Detail
                if($antrian['response_data'][0]['departemen'] === __POLI_INAP__) {
                    $resep = self::$query->select('resep', array(
                        'uid',
                        'alergi_obat',
                        'keterangan',
                        'keterangan_racikan'
                    ))
                        ->where(array(
                            'resep.deleted_at' => 'IS NULL',
                            'AND',
                            'resep.kunjungan' => '= ?',
                            'AND',
                            'resep.antrian' => '= ?',
                            'AND',
                            'resep.asesmen' => '= ?',
                            'AND',
                            'resep.dokter' => '= ?',
                            'AND',
                            'resep.pasien' => '= ?'
                        ), array(
                            $antrian['response_data'][0]['kunjungan'],
                            $antrian['response_data'][0]['uid'],
                            $data['response_data'][0]['asesmen'],
                            $data['response_data'][0]['dokter'],
                            $data['response_data'][0]['pasien']
                        ))
                        ->execute();
                } else {
                    $resep = self::$query->select('resep', array(
                        'uid',
                        'alergi_obat',
                        'keterangan',
                        'keterangan_racikan'
                    ))
                        ->where(array(
                            'resep.deleted_at' => 'IS NULL',
                            'AND',
                            'resep.kunjungan' => '= ?',
                            'AND',
                            'resep.antrian' => '= ?',
                            'AND',
                            'resep.asesmen' => '= ?',
                            'AND',
                            'resep.dokter' => '= ?',
                            'AND',
                            'resep.pasien' => '= ?',
                            'AND',
                            '(resep.status_resep' => '= ?',
                            'OR',
                            'resep.status_resep' => '= ?',
                            'OR',
                            ' resep.status_resep' => '= ?)',
                        ), array(
                            $antrian['response_data'][0]['kunjungan'],
                            $antrian['response_data'][0]['uid'],
                            $data['response_data'][0]['asesmen'],
                            $data['response_data'][0]['dokter'],
                            $data['response_data'][0]['pasien'],
                            ($isCPPT) ? 'L' : 'C',
                            ($isCPPT) ? 'P' : 'C',
                            ($isCPPT) ? 'S' : 'C'
                        ))
                        ->execute();
                }

				$racikanData = array();
				$racikanApotekData = array();
				foreach ($resep['response_data'] as $key => $value) {
					//GET Resep Detail
					$resepDetail = self::$query->select('resep_detail', array(
						'id',
						'resep',
						'obat',
						'harga',
						'signa_qty',
						'signa_pakai',
						'keterangan',
						'aturan_pakai',
						'qty',
						'satuan',
						'created_at',
						'updated_at'
					))
					->where(array(
						'resep_detail.resep' => '= ?'
					), array(
						$value['uid']
					))
					->execute();
					foreach ($resepDetail['response_data'] as $RDKey => $RDValue) {
					    $resepDetail['response_data'][$RDKey]['obat_detail'] = $Inventori->get_item_detail($RDValue['obat'])['response_data'][0];
                    }
					$resep['response_data'][$key]['resep_detail'] = $resepDetail['response_data'];

					//Racikan Detail
					$racikan = self::$query->select('racikan', array(
						'uid',
						'asesmen',
						//'resep',
						'kode',
						'keterangan',
						'aturan_pakai',
						'signa_qty',
						'signa_pakai',
						'qty',
						'total'
					))
					->where(array(
						'racikan.asesmen' => '= ?',
						'AND',
						'racikan.deleted_at' => 'IS NULL'
					), array(
						$data['response_data'][0]['asesmen']
					))
					->execute();

                    if($isCPPT) {
                        /*$RacikanApotekItem = self::$query->select('racikan_change_log')
                            ->where(array(
                                'racikan_change_log.racikan'
                            ), array())
                            ->execute();*/
                    }

					foreach ($racikan['response_data'] as $RacikanKey => $RacikanValue) {
						$RacikanDetailData = self::$query->select('racikan_detail', array(
							'asesmen',
							//'resep',
							'obat',
							'ratio',
							'pembulatan',
							'kekuatan',
							'takar_bulat',
							'takar_decimal',
							'harga',
							'racikan',
							'penjamin'
						))
						->where(array(
							'racikan_detail.racikan' => '= ?'
						), array(
							//$value['uid'],
							$RacikanValue['uid']
						))
						->execute();

						foreach ($RacikanDetailData['response_data'] as $RVIKey => $RVIValue) {
							$RacikanDetailData['response_data'][$RVIKey]['obat_detail'] = $Inventori->get_item_detail($RVIValue['obat'])['response_data'][0];
						}

						$RacikanValue['item'] = $RacikanDetailData['response_data'];

						array_push($racikanData, $RacikanValue);
					}
				}





				if($isCPPT) {
				    //List Resep dan Racikan oleh apotek
                    $dataResepApotek = self::$query->select('resep_change_log', array())
                        ->where(array(
                            'resep_change_log.resep' => '= ?',
                            'AND',
                            'resep_change_log.deleted_at' => 'IS NULL'
                        ), array(
                            $resep[0]['uid']
                        ))
                        ->execute();
                    foreach ($dataResepApotek['response_data'] as $dRApotekKey => $dRApotekValue) {
                        $dataResepApotek['response_data'][$dRApotekKey]['obat_detail'] = $Inventori->get_item_detail($dRApotekValue['item'])['response_data'][0];
                    }
                    $data['response_data'][0]['resep_apotek'] = $dataResepApotek['response_data'];
                }

                if(count($resep['response_data']) > 0) {
                    $resep_apotek = self::$query->select('resep_change_log', array(
                        'id',
                        'item',
                        'qty',
                        'signa_qty',
                        'signa_pakai',
                        'aturan_pakai',
                        'verifikator',
                        'keterangan'
                    ))
                        ->where(array(
                            'resep_change_log.resep' => '= ?',
                            'AND',
                            'resep_change_log.deleted_at' => 'IS NULL'
                        ), array(
                            $resep['response_data'][0]['uid']
                        ))
                        ->execute();

                    foreach ($resep_apotek['response_data'] as $dRApotekKey => $dRApotekValue) {
                        $resep_apotek['response_data'][$dRApotekKey]['obat_detail'] = $Inventori->get_item_detail($dRApotekValue['item'])['response_data'][0];
                    }
                    $data['response_data'][0]['resep'] = $resep['response_data'];
                    $data['response_data'][0]['resep_apotek'] = $resep_apotek['response_data'];
                } else {
                    $data['response_data'][0]['resep'] = array();
                    $data['response_data'][0]['resep_apotek'] = array();
                }

                if(count($racikan['response_data']) > 0) {
                    $racikan_apotek = self::$query->select('racikan_change_log', array(
                        'jumlah',
                        'keterangan',
                        'signa_qty',
                        'signa_pakai',
                        'aturan_pakai'
                    ))
                        ->where(array(
                            'racikan_change_log.racikan' => '= ?',
                            'AND',
                            'racikan_change_log.deleted_at' => 'IS NULL'
                        ), array(
                            $racikan['response_data'][0]['uid']
                        ))
                        ->execute();

                    $data['response_data'][0]['racikan'] = $racikanData;

                    foreach ($racikan_apotek['response_data'] as $racikanApotekKey => $racikanApotekValue) {
                        $racikan_apotek_detail = self::$query->select('racikan_detail_change_log', array(
                            'obat',
                            'kekuatan',
                            'jumlah'
                        ))
                            ->where(array(
                                'racikan_detail_change_log.racikan' => '= ?',
                                'AND',
                                'racikan_detail_change_log.deleted_at' => 'IS NULL'
                            ), array(
                                $racikan['response_data'][0]['uid']
                            ))
                            ->execute();
                        foreach ($racikan_apotek_detail['response_data'] as $dRaApotekKey => $dRaApotekValue) {
                            $racikan_apotek_detail['response_data'][$dRaApotekKey]['obat_detail'] = $Inventori->get_item_detail($dRaApotekValue['obat'])['response_data'][0];
                        }
                        $racikan_apotek['response_data'][$racikanApotekKey]['item'] = $racikan_apotek_detail['response_data'];
                    }
                    $data['response_data'][0]['racikan_apotek'] = $racikan_apotek['response_data'];
                } else {
                    $data['response_data'][0]['racikan'] = array();
                    $data['response_data'][0]['racikan_apotek'] = array();
                    $data['response_data'][0]['racikan_apotek_detail'] = array();
                }

				$data['response_data'][0]['asesmen_rawat'] = $Rawat['response_data'][0]['uid'];
                $data['response_data'][0]['status_asesmen'] = $AsesmenMaster['response_data'][0];
                $data['response_data'][0]['tanggal_parsed'] = date('d F Y', strtotime($AsesmenMaster['response_data'][0]['created_at']));

                $PasienDetail = $Pasien->get_pasien_detail('pasien', $data['response_data'][0]['pasien']);
                $data['response_data'][0]['pasien_detail'] = $PasienDetail['response_data'][0];
				return $data;
			} else {
				$Rawat = self::$query->select('asesmen_rawat_' . $PoliDetail['poli_asesmen'], array(
					'uid'
				))
				->where(array(
					'asesmen_rawat_' . $PoliDetail['poli_asesmen'] . '.antrian' => '= ?'
				), array(
					$antrian['response_data'][0]['uid']
				))
				->execute();
				$antrian['response_data'][0]['asesmen_rawat'] = $Rawat['response_data'][0]['uid'];
                $antrian['response_data'][0]['status_asesmen'] = $AsesmenMaster['response_data'][0];
				return $antrian;
			}
		} else {
            $antrian['response_data'][0]['status_asesmen'] = $AsesmenMaster['response_data'][0];
			return $antrian;
		}
	}

	private function update_asesmen_medis($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
		$MasterUID = '';
		//Prepare Poli
		$Poli = new Poli(self::$pdo);
		$PoliDetail = $Poli->get_poli_detail($parameter['poli'])['response_data'][0];

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
            if($parameter['poli'] === __POLI_INAP__) {
                $poli_check = self::$query->select('asesmen_medis_inap', array(
                    'uid'
                ))
                    ->where(array(
                        'asesmen_medis_inap.deleted_at' => 'IS NULL',
                        'AND',
                        'asesmen_medis_inap.asesmen' => '= ?'
                    ), array(
                        $check['response_data'][0]['uid']
                    ))
                    ->execute();
            } else {
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
            }


			if(count($poli_check['response_data']) > 0) {
                $selectedICD9 = array();
			    $selectedICD10Kerja = array();
				$selectedICD10Banding = array();

                foreach ($parameter['icd9'] as $ICD9BK => $ICD9BV) {
                    array_push($selectedICD9, $ICD9BV['id']);
                }

				foreach ($parameter['icd10_kerja'] as $ICD10KK => $ICD10KV) {
					array_push($selectedICD10Kerja, $ICD10KV['id']);
				}

				foreach ($parameter['icd10_banding'] as $ICD10BK => $ICD10BV) {
					array_push($selectedICD10Banding, $ICD10BV['id']);
				}




				//Kasus Spesial FisioTerapi. Memang beda sendiri dia. aut of de boks. Paten kaleee
                if($PoliDetail['uid'] === __UIDFISIOTERAPI__) {
                    $saveParam = array(
                        'keluhan_utama' => $parameter['keluhan_utama'],
                        'keluhan_tambahan' => $parameter['keluhan_tambahan'],
                        'tekanan_darah' => floatval($parameter['tekanan_darah']),
                        'nadi' => floatval($parameter['nadi']),
                        'suhu' => floatval($parameter['suhu']),
                        'pernafasan' => floatval($parameter['pernafasan']),
                        'berat_badan' => floatval($parameter['berat_badan']),
                        'tinggi_badan' => floatval($parameter['tinggi_badan']),
                        'lingkar_lengan_atas' => floatval($parameter['lingkar_lengan_atas']),
                        'icd9' => implode(',', $selectedICD9),
                        'pemeriksaan_fisik' => $parameter['pemeriksaan_fisik'],
                        //'icd10_kerja' => intval($parameter['icd10_kerja']),
                        'icd10_kerja' => implode(',', $selectedICD10Kerja),
                        'diagnosa_kerja' => $parameter['diagnosa_kerja'],
                        //'icd10_banding' => intval($parameter['icd10_banding']),
                        'icd10_banding' => implode(',', $selectedICD10Banding),
                        'diagnosa_banding' => $parameter['diagnosa_banding'],
                        'planning' => $parameter['planning'],
                        'anamnesa' => $parameter['anamnesa'],
                        'tatalaksana' => $parameter['tataLaksana'],
                        'evaluasi' => $parameter['evaluasi'],
                        'anjuran_bulan' => floatval($parameter['anjuranBulan']),
                        'anjuran_minggu' => floatval($parameter['anjuranMinggu']),
                        'suspek_akibat_kerja' => $parameter['suspek'],
                        'hasil' => $parameter['hasil'],
                        'kesimpulan' => $parameter['kesimpulan'],
                        'rekomendasi' => $parameter['rekomendasi'],
                        'updated_at' => parent::format_date()
                    );
                } else if($PoliDetail['uid'] === __POLI_IGD__) {
                    $saveParam = array(
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
                        //'icd10_kerja' => intval($parameter['icd10_kerja']),
                        'icd10_kerja' => implode(',', $selectedICD10Kerja),
                        'diagnosa_kerja' => $parameter['diagnosa_kerja'],
                        //'icd10_banding' => intval($parameter['icd10_banding']),
                        'icd10_banding' => implode(',', $selectedICD10Banding),
                        'diagnosa_banding' => $parameter['diagnosa_banding'],
                        'planning' => $parameter['planning'],

                        'gcs_e' => (!empty($parameter['gcs_e'])) ? $parameter['gcs_e'] : '',
                        'gcs_v' => (!empty($parameter['gcs_v'])) ? $parameter['gcs_v'] : '',
                        'gcs_m' => (!empty($parameter['gcs_m'])) ? $parameter['gcs_m'] : '',
                        'gcs_tot' => (!empty($parameter['gcs_tot'])) ? $parameter['gcs_tot'] : '',
                        'status_alergi' => (!empty($parameter['status_alergi'])) ? $parameter['status_alergi'] : '',
                        'status_alergi_text' => (!empty($parameter['status_alergi_text'])) ? $parameter['status_alergi_text'] : '',
                        'refleks_cahaya' => (!empty($parameter['refleks_cahaya'])) ? $parameter['refleks_cahaya'] : '',
                        'pupil' => (!empty($parameter['pupil'])) ? $parameter['pupil'] : '',
                        //'refleks_cahaya' => (!empty($parameter['refleks_cahaya'])) ? $parameter['refleks_cahaya'] : '',
                        'rr' => (!empty($parameter['rr'])) ? $parameter['rr'] : '',
                        'gangguan_perilaku' => (!empty($parameter['gangguan_perilaku'])) ? $parameter['gangguan_perilaku'] : '',
                        'gangguan_terganggu' => (!empty($parameter['gangguan_terganggu'])) ? $parameter['gangguan_terganggu'] : '',
                        'skala_nyeri' => (!empty($parameter['skala_nyeri'])) ? $parameter['skala_nyeri'] : '',
                        'lokasi' => (!empty($parameter['lokasi'])) ? $parameter['lokasi'] : '',
                        'frekuensi' => (!empty($parameter['frekuensi'])) ? $parameter['frekuensi'] : '',
                        'karakter_nyeri' => (!empty($parameter['karakter_nyeri'])) ? $parameter['karakter_nyeri'] : '',
                        'karakter_nyeri_text' => (!empty($parameter['karakter_nyeri_text'])) ? $parameter['karakter_nyeri_text'] : '',
                        'skor_nyeri' => (!empty($parameter['skor_nyeri'])) ? $parameter['skor_nyeri'] : '',
                        'tipe_nyeri' => (!empty($parameter['tipe_nyeri'])) ? $parameter['tipe_nyeri'] : '',
                        'ats_list' => (!empty($parameter['ats_list'])) ? json_encode($parameter['ats_list']) : '',
                        'ats_skala' => (!empty($parameter['ats_skala'])) ? $parameter['ats_skala'] : '',
                        'ekg' => (!empty($parameter['ekg'])) ? $parameter['ekg'] : '',
                        'saved_lokalis_item' => (!empty($parameter['savedLokalisItem'])) ? json_encode($parameter['savedLokalisItem']) : '',
                        'skala_rasa_sakit' => intval($parameter['skala_rasa_sakit']),

                        'updated_at' => parent::format_date()
                    );
                } else if($PoliDetail['uid'] === __POLI_GIGI__) {
                    $saveParam = array(
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
                        'icd10_kerja' => implode(',', $selectedICD10Kerja),
                        'diagnosa_kerja' => $parameter['diagnosa_kerja'],
                        'icd10_banding' => implode(',', $selectedICD10Banding),
                        'diagnosa_banding' => $parameter['diagnosa_banding'],
                        'planning' => $parameter['planning'],

                        'odontogram' => $parameter['odontogram'],
                        'muka_simetris' => $parameter['simetris'],
                        'tmj' => $parameter['sendi'],
                        'bibir' => $parameter['bibir'],
                        'lidah' => $parameter['lidah'],
                        'mukosa' => $parameter['mukosa'],
                        'torus' => $parameter['torus'],
                        'gingiva' => $parameter['gingiva'],
                        'frenulum' => $parameter['frenulum'],
                        'kebersihan_mulut' => $parameter['mulut_bersih'],

                        //'keterangan_mulut' => $parameter['keterangan_mulut'],
                        'keterangan_bibir' => $parameter['keterangan_bibir'],
                        'keterangan_lidah' => $parameter['keterangan_lidah'],
                        'keterangan_mukosa' => $parameter['keterangan_mukosa'],
                        'keterangan_torus' => $parameter['keterangan_torus'],
                        'keterangan_gingiva' => $parameter['keterangan_gingiva'],
                        'keterangan_frenulum' => $parameter['keterangan_frenulum'],

                        'updated_at' => parent::format_date()
                    );
                } else if($PoliDetail['uid'] === __POLI_MATA__) {
                    $saveParam = array(
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
                        'icd10_kerja' => implode(',', $selectedICD10Kerja),
                        'diagnosa_kerja' => $parameter['diagnosa_kerja'],
                        'icd10_banding' => implode(',', $selectedICD10Banding),
                        'diagnosa_banding' => $parameter['diagnosa_banding'],
                        'planning' => $parameter['planning'],
                        'meta_resep' => $parameter['mata_data'],
                        'tujuan_resep' => $parameter['tujuan_resep'],
                        'updated_at' => parent::format_date()
                    );
                } else {
                    $saveParam = array(
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
                        //'icd10_kerja' => intval($parameter['icd10_kerja']),
                        'icd10_kerja' => implode(',', $selectedICD10Kerja),
                        'diagnosa_kerja' => $parameter['diagnosa_kerja'],
                        //'icd10_banding' => intval($parameter['icd10_banding']),
                        'icd10_banding' => implode(',', $selectedICD10Banding),
                        'diagnosa_banding' => $parameter['diagnosa_banding'],
                        'planning' => $parameter['planning'],
                        'updated_at' => parent::format_date()
                    );
                }

                //Update
                if($parameter['poli'] === __POLI_INAP__) {
                    $worker = self::$query->update('asesmen_medis_inap', $saveParam)
                        ->where(array(
                            'asesmen_medis_inap.deleted_at' => 'IS NULL',
                            'AND',
                            'asesmen_medis_inap.uid' => '= ?',
                            'AND',
                            'asesmen_medis_inap.kunjungan' => '= ?',
                            'AND',
                            'asesmen_medis_inap.antrian' => '= ?',
                            'AND',
                            'asesmen_medis_inap.pasien' => '= ?',
                            'AND',
                            'asesmen_medis_inap.dokter' => '= ?',
                            'AND',
                            'asesmen_medis_inap.asesmen' => '= ?'
                        ), array(
                            $poli_check['response_data'][0]['uid'],
                            $parameter['kunjungan'],
                            $parameter['antrian'],
                            $parameter['pasien'],
                            $UserData['data']->uid,
                            $check['response_data'][0]['uid']
                        ))
                        ->execute();
                } else {
                    $worker = self::$query->update('asesmen_medis_' . $PoliDetail['poli_asesmen'], $saveParam)
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
                }



				if($worker['response_result'] > 0) { //Berhasil simpan asesmen
					//Update asesmen medis

                    if($PoliDetail['uid'] === __POLI_IGD__) {

                        //Pasti Selalu selesai
                        //Update

                    } else {
                        $updateAsesmen = self::$query->update('asesmen', array(
                            'status' => 'D'
                        ))
                            ->where(array(
                                'asesmen.uid' => '= ?'
                            ), array(
                                $MasterUID
                            ))
                            ->execute();

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
				}
			} else {
                if($parameter['poli'] === __POLI_INAP__) {
                    $worker = self::new_asesmen($parameter, $check['response_data'][0]['uid'], 'inap', $PoliDetail['uid']);
                } else {
                    $worker = self::new_asesmen($parameter, $check['response_data'][0]['uid'], $PoliDetail['poli_asesmen'], $PoliDetail['uid']);
                }
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
                if($parameter['poli'] === __POLI_INAP__) {
                    $worker = self::new_asesmen($parameter, $NewAsesmen, 'inap', $PoliDetail['uid']);
                } else {
                    $worker = self::new_asesmen($parameter, $NewAsesmen, $PoliDetail['poli_asesmen'], $PoliDetail['uid']);
                }


				$returnResponse = $worker;
			} else {
				$returnResponse = $asesmen_poli;
			}
		}


		//Tindakan Management
        $returnResponse['tindakan_response'] = self::set_tindakan_asesment($parameter, $MasterUID);

		//Resep dan Racikan
		$returnResponse['resep_response'] = self::set_resep_asesment($parameter, $MasterUID);

		if($parameter['poli'] !== __POLI_INAP__) {
		    //Pasien Keluar Poli
            if($parameter['charge_invoice'] === 'Y') {
                //Pasien Keluar Poli
                if($parameter['poli'] !== __POLI_IGD__) {
                    $keluar = self::$query->update('antrian', array(
                        'waktu_keluar' => parent::format_date()
                    ))
                        ->where(array(
                            'antrian.uid' => '= ?',
                            'AND',
                            'antrian.deleted_at' => 'IS NULL'
                        ), array(
                            $parameter['antrian']
                        ))
                        ->execute();
                }

                //Charge Invoice Tindakan Penunjang
                /*$Laboratorium = new Laboratorium(self::$pdo);
                $ChargeLab = $Laboratorium->charge_invoice_item(array(
                    'asesmen' => $MasterUID,
                    'kunjungan' => $parameter['kunjungan'],
                    'pasien' => $parameter['pasien'],
                    'departemen' => $parameter['poli']
                ));
                $returnResponse['lab_response'] = $ChargeLab;

                $Radiologi = new Radiologi(self::$pdo);
                $ChargeRad = $Radiologi->charge_invoice_item(array(
                    'asesmen' => $MasterUID,
                    'kunjungan' => $parameter['kunjungan'],
                    'pasien' => $parameter['pasien'],
                    'departemen' => $parameter['poli']
                ));*/

                if($parameter['penjamin'] === __UIDPENJAMINUMUM__) {
                    $antrian_nomor = self::$query->update('antrian_nomor', array(
                        'status' => 'K'
                    ))
                        ->where(array(
                            'antrian_nomor.pasien' => '= ?',
                            'AND',
                            'antrian_nomor.kunjungan' => '= ?',
                            'AND',
                            'antrian_nomor.poli' => '= ?',
                            'AND',
                            'antrian_nomor.poli.deleted_at' => 'IS NULL'
                        ), array(
                            $parameter['pasien'],
                            $parameter['kunjungan'],
                            $parameter['poli']
                        ))
                        ->execute();
                }

                //$returnResponse['rad_response'] = $ChargeRad;
            }
        } else {
		    //Todo: INAP SEGMENT
            if($parameter['charge_invoice'] === 'Y') {
                $keluar = self::$query->update('antrian', array(
                    'waktu_keluar' => parent::format_date()
                ))
                    ->where(array(
                        'antrian.uid' => '= ?',
                        'AND',
                        'antrian.deleted_at' => 'IS NULL'
                    ), array(
                        $parameter['antrian']
                    ))
                    ->execute();
                $Laboratorium = new Laboratorium(self::$pdo);
                $ChargeLab = $Laboratorium->charge_invoice_item(array(
                    'asesmen' => $MasterUID,
                    'kunjungan' => $parameter['kunjungan'],
                    'pasien' => $parameter['pasien']
                ));
                $returnResponse['lab_response'] = $ChargeLab;

                $Radiologi = new Radiologi(self::$pdo);
                $ChargeRad = $Radiologi->charge_invoice_item(array(
                    'asesmen' => $MasterUID,
                    'kunjungan' => $parameter['kunjungan'],
                    'pasien' => $parameter['pasien']
                ));
                $returnResponse['rad_response'] = $ChargeRad;
            }
        }

        //Check Radiologi
        $RadCheck = self::$query->select('rad_order', array(
            'uid', 'status'
        ))
            ->where(array(
                'rad_order.asesmen' => '= ?',
                'AND',
                'rad_order.deleted_at' => 'IS NULL'
            ), array(
                $MasterUID
            ))
            ->execute();
        $returnResponse['radiologi'] = $RadCheck;

        //Check Laboratorium
        $LabCheck = self::$query->select('lab_order', array(
            'uid', 'status'
        ))
            ->where(array(
                'lab_order.asesmen' => '= ?',
                'AND',
                'lab_order.deleted_at' => 'IS NULL'
            ), array(
                $MasterUID
            ))
            ->execute();
        $returnResponse['laboratorium'] = $LabCheck;

		return $returnResponse;
	}

	private function set_resep_asesment($parameter, $MasterAsesmen) {
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);

        //Check Invoice
        $Invoice = new Invoice(self::$pdo);
        $InvoiceCheck = self::$query->select('invoice', array(
            'uid'
        ))
            ->where(array(
                'invoice.kunjungan' => '= ?',
                'AND',
                'invoice.deleted_at' => 'IS NULL'
            ), array(
                $parameter['kunjungan']
            ))
            ->execute();

        if(count($InvoiceCheck['response_data']) > 0) {
            $TargetInvoice = $InvoiceCheck['response_data'][0]['uid'];
        } else {
            $InvMasterParam = array(
                'kunjungan' => $parameter['kunjungan'],
                'pasien' => $parameter['pasien'],
                'keterangan' => 'Tagihan tindakan perobatan'
            );
            $NewInvoice = $Invoice->create_invoice($InvMasterParam);
            $TargetInvoice = $NewInvoice['response_unique'];
        }
        

		$check = self::$query->select('resep', array(
			'uid',
            'kode'
		))
		->where(array(
			'resep.kunjungan' => '= ?',
			'AND',
			'resep.antrian' => '= ?',
			'AND',
			'resep.asesmen' => '= ?',
			'AND',
			'resep.dokter' => '= ?',
			'AND',
			'resep.pasien' => '= ?',
			'AND',
			'(resep.status_resep' => '= ?',
			'OR',
            'resep.status_resep' => '= ?)',
			'AND',
			'resep.deleted_at' => 'IS NULL'
		), array(
			$parameter['kunjungan'],
			$parameter['antrian'],
			$MasterAsesmen,
			$UserData['data']->uid,
			$parameter['pasien'],
            'N',
            'C'
		))
		->execute();
		
		if(count($check['response_data']) > 0) {
			$uid = $check['response_data'][0]['uid'];
			$Kode = $check['response_data'][0]['kode'];

			//Update resep master
			$resepUpdate = self::$query->update('resep', array(
			    'alergi_obat' => (isset($parameter['editorAlergiObat']) && !is_null($parameter['editorAlergiObat']) && !empty($parameter['editorAlergiObat'])) ? $parameter['editorAlergiObat'] : '',
                'status_resep' => ($parameter['charge_invoice'] === 'Y') ? 'N' : 'C',
				'keterangan' => $parameter['keteranganResep'],
				'keterangan_racikan' => $parameter['keteranganRacikan']
			))
			->where(array(
				'resep.uid' => '= ?',
				'AND',
				'resep.deleted_at' => 'IS NULL'
			), array(
				$uid
			))
			->execute();

			//Reset Resep Detail
			$resetResep = self::$query->update('resep_detail', array(
				'deleted_at' => parent::format_date()
			))
			->where(array(
				'resep_detail.resep' => '= ?'
			), array(
				$uid
			))
			->execute();

			//Update Detail Resep
			$used_obat = array();
			$old_resep_detail = array();
			$detail_check = self::$query->select('resep_detail', array(
				'id',
				'obat'
			))
			->where(array(
				'resep_detail.resep' => '= ?'
			), array(
				$uid
			))
			->execute();

			foreach ($detail_check['response_data'] as $key => $value) {
				if(!in_array($value['obat'], $used_obat)) {
					array_push($used_obat, $value['obat']);
					array_push($old_resep_detail, $value);
				}
			}

			$resepProcess = array();

			foreach ($parameter['resep'] as $key => $value) {
				//Prepare Data Obat
				$ObatDetail = new Inventori(self::$pdo);
				$ObatInfo = $ObatDetail->get_item_detail($value['obat'])['response_data'][0];

				if(in_array($value['obat'], $used_obat)) {
					$worker = self::$query->update('resep_detail', array(
						'signa_qty' => $value['signaKonsumsi'],
						'signa_pakai' => $value['signaTakar'],
						'qty' => $value['signaHari'],
						'aturan_pakai' => intval($value['aturanPakai']),
						'keterangan' => $value['keteranganPerObat'],
						'updated_at'=> parent::format_date(),
						'deleted_at' => NULL
					))
					->where(array(
						'resep_detail.resep' => '= ?',
						'AND',
						'resep_detail.obat' => '= ?'
					), array(
						$uid,
						$value['obat']
					))
					->execute();
				} else {
					$worker = self::$query->insert('resep_detail', array(
						'resep' => $uid,
						'obat' => $value['obat'],
						'harga' => 0,
						'signa_qty' => $value['signaKonsumsi'],
						'signa_pakai' => $value['signaTakar'],
						'qty' => $value['signaHari'],
						'satuan' => $ObatInfo['satuan_terkecil'],
						'aturan_pakai' => intval($value['aturanPakai']),
						'keterangan' => $value['keteranganPerObat'],
						'created_at' => parent::format_date(),
						'updated_at'=> parent::format_date()
					))
					->execute();
				}
				array_push($resepProcess, $worker);
			}




			//Reset Racikan
			$racikReset = self::$query->update('racikan', array(
				'deleted_at' => parent::format_date()
			))
			->where(array(
				'racikan.asesmen' => '= ?'
			), array(
				$MasterAsesmen
			))
			->execute();


			//Filter #1
			$racikanOld = self::$query->select('racikan', array(
				'uid'
			))
			->where(array(
				'racikan.asesmen' => '= ?'
                /*,'AND',
                'racikan.kode' => '= ?'*/
			), array(
				$MasterAsesmen
                //,$parameter['racikan'][$key]['nama']
			))
			->execute();

			$racikanError = array();

			foreach ($racikanOld['response_data'] as $key => $value) {
				$racikanUpdate = self::$query->update('racikan', array(
					'kode' => '['. $Kode . ']' . $parameter['racikan'][$key]['nama'],
					'aturan_pakai' => intval($parameter['racikan'][$key]['aturanPakai']),
					'keterangan' => $parameter['racikan'][$key]['keterangan'],
					'signa_qty' => $parameter['racikan'][$key]['signaKonsumsi'],
					'signa_pakai' => $parameter['racikan'][$key]['signaTakar'],
					'qty' => $parameter['racikan'][$key]['signaHari'],
					'deleted_at' => NULL
				))
				->where(array(
					'racikan.uid' => '= ?'
				), array(
					$value['uid']
				))
				->execute();
				if($racikanUpdate['response_result'] > 0) {
					//
				} else {
                    //array_push($racikanError, $racikanUpdate);
				}



                //Reset Racikan Detail
                /*$resetRacikanDetail = self::$query->update('racikan_detail', array(
                    'deleted_at' => parent::format_date()
                ))
                    ->where(array(
                        'racikan_detail.racikan' => '= ?',
                        'AND',
                        'racikan_detail.asesmen' => '= ?'
                    ), array(
                        $value['uid'],
                        $MasterAsesmen
                    ))
                    ->execute();*/

                $resetRacikanDetail = self::$query->hard_delete('racikan_detail')
                    ->where(array(
                        /*'racikan_detail.resep' => '= ?',
                        'AND',*/
                        'racikan_detail.racikan' => '= ?',
                        'AND',
                        'racikan_detail.asesmen' => '= ?'
                    ), array(
                        //$uid,
                        $value['uid'],
                        $MasterAsesmen
                    ))
                    ->execute();

                //Old Racikan Detail
                $checkRacikanDetail = self::$query->select('racikan_detail', array(
                    'id',
                    'obat'
                ))
                    ->where(array(
                        /*'racikan_detail.resep' => '= ?',
                        'AND',*/
                        'racikan_detail.racikan' => '= ?',
                        'AND',
                        'racikan_detail.asesmen' => '= ?'
                    ), array(
                        //$uid,
                        $value['uid'],
                        $MasterAsesmen
                    ))
                    ->execute();

                $oldRacikanDetail = array();
                $usedRacikanDetail = array();
                foreach ($checkRacikanDetail['response_data'] as $RDKey => $RDValue) {
                    if(!in_array($RDValue['obat'], $usedRacikanDetail)) {
                        array_push($usedRacikanDetail, $RDValue['obat']);
                        array_push($oldRacikanDetail, $RDValue);
                    }
                }

                foreach ($parameter['racikan'][$key]['item'] as $RDIKey => $RDIValue) {
                    if(in_array($RDIValue['obat'], $usedRacikanDetail)) {
                        $racikanDetailWorker = self::$query->update('racikan_detail', array(
                            'obat' => $RDIValue['obat'],
                            'ratio' => floatval($RDIValue['takaran']),
                            'kekuatan' => $RDIValue['kekuatan'],
                            'penjamin' => $parameter['penjamin'],
                            //'takar_bulat' => $RDIValue['takaranBulat'],
                            //'takar_decimal' => $RDIValue['takaranDecimalText'],
                            'pembulatan' => ceil($RDIValue['takaran']),
                            'deleted_at' => NULL
                        ))
                            ->where(array(
                                /*'racikan_detail.resep' => '= ?',
                                'AND',*/
                                'racikan_detail.racikan' => '= ?',
                                'AND',
                                'racikan_detail.asesmen' => '= ?',
                                'AND',
                                'racikan_detail.obat' => '= ?'
                            ), array(
                                //$uid,
                                $value['uid'],
                                $MasterAsesmen,
                                $RDIValue['obat']
                            ))
                            ->execute();
                    } else {
                        $racikanDetailWorker = self::$query->insert('racikan_detail', array(
                            'asesmen' => $MasterAsesmen,
                            //'resep' => $uid,
                            'obat' => $RDIValue['obat'],
                            'pembulatan' => ceil($RDIValue['takaran']),
                            'kekuatan' => $RDIValue['kekuatan'],
                            //'takar_bulat' => $RDIValue['takaranBulat'],
                            //'takar_decimal' => $RDIValue['takaranDecimalText'],
                            'harga' => 0,
                            'created_at' => parent::format_date(),
                            'updated_at' => parent::format_date(),
                            'racikan' => $value['uid'],
                            'ratio' => floatval($RDIValue['takaran'])
                        ))
                            ->execute();
                    }
                }

                //array_push($racikanError, $racikanDetailWorker);

                //Unset processed data from parameter
                unset($parameter['racikan'][$key]);
			}

			//UnProcessed Racikan
			foreach ($parameter['racikan'] as $key => $value) {
				$newRacikanUID = parent::gen_uuid();
				$newRacikan = self::$query->insert('racikan', array(
					'uid' => $newRacikanUID,
					'asesmen' => $MasterAsesmen,
					'kode' => '['. $Kode . ']' . $value['nama'],
					'total' => 0,
					'signa_qty' => $value['signaKonsumsi'],
                    'keterangan' => $value['keterangan'],
					'signa_pakai' => $value['signaTakar'],
					'aturan_pakai' => intval($value['aturanPakai']),
					'qty' => $value['signaHari'],
					'created_at' => parent::format_date(),
					'updated_at' => parent::format_date()
				))
				->execute();

				if($newRacikan['response_result'] > 0) {
					$racikanDetail = $parameter['racikan']['item'];
					foreach ($racikanDetail as $RDKey => $RDValue) {
						$detailRacikan = self::$query->insert('racikan_detail', array(
							'asesmen' => $MasterAsesmen,
							'racikan' => $newRacikanUID,
							//'resep' => $uid,
							'obat' => $RDValue['obat'],
							'ratio' => floatval($RDValue['takaran']),
							'pembulatan' => ceil($RDValue['takaran']),
							'kekuatan' => $RDValue['kekuatan'],
							//'takar_bulat' => $RDIValue['takaranBulat'],
							//'takar_decimal' => $RDIValue['takaranDecimalText'],
							'harga' => 0,
							'penjamin' => '',
							'created_at' => parent::format_date(),
							'updated_at' => parent::format_date(),
						))
						->execute();
						array_push($racikanError, $detailRacikan);
					}
				} else {
					array_push($racikanError, $newRacikan);
				}
			}
			return array('resep' => $resepProcess, 'racikan' => $racikanError);

		} else { //Jika Resep baru


		    if(count($parameter['resep']) > 0 || count($parameter['racikan']) > 0) {
                //New Resep
                $uid = parent::gen_uuid();

                $lastNumber = self::$query->select('resep', array(
                    'uid'
                ))
                    ->where(array(
                        'EXTRACT(month FROM created_at)' => '= ?'
                    ), array(
                        intval(date('m'))
                    ))
                    ->execute();
                $Kode = 'RSP-' . date('Y/m') . '-' . str_pad(strval(count($lastNumber['response_data']) + 1), 4, '0', STR_PAD_LEFT);

                $newResep = self::$query->insert('resep',array(
                    'uid' => $uid,
                    'kode' => $Kode,
                    'kunjungan' => $parameter['kunjungan'],
                    'antrian' => $parameter['antrian'],
                    'keterangan' => $parameter['keteranganResep'],
                    'keterangan_racikan' => $parameter['keteranganRacikan'],
                    'asesmen' => $MasterAsesmen,
                    'dokter' => $UserData['data']->uid,
                    'pasien' => $parameter['pasien'],
                    'alergi_obat' => (isset($parameter['editorAlergiObat']) && !is_null($parameter['editorAlergiObat']) && !empty($parameter['editorAlergiObat'])) ? $parameter['editorAlergiObat'] : '',
                    'total' => 0,
                    'status_resep' => ($parameter['charge_invoice'] === 'Y') ? 'N' : 'C',
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();

                if($newResep['response_result'] > 0) {
                    $resep_detail_error = array();
                    //SetDetail
                    foreach ($parameter['resep'] as $key => $value) {
                        $ObatDetail = new Inventori(self::$pdo);
                        $ObatInfo = $ObatDetail->get_item_detail($value['obat'])['response_data'][0];

                        $newResepDetail = self::$query->insert('resep_detail', array(
                            'resep' => $uid,
                            'obat' => $value['obat'],
                            'aturan_pakai' => intval($value['aturanPakai']),
                            'harga' => 0,
                            'signa_qty' => $value['signaKonsumsi'],
                            'signa_pakai' => $value['signaTakar'],
                            'qty' => $value['signaHari'],
                            'satuan' => $ObatInfo['satuan_terkecil'],
                            'created_at' => parent::format_date(),
                            'updated_at' => parent::format_date(),
                            'keterangan' => $value['keteranganPerObat']
                        ))
                            ->execute();
                        array_push($resep_detail_error, $newResepDetail);
                    }

                    foreach ($parameter['racikan'] as $key => $value) {
                        $uid_racikan = parent::gen_uuid();
                        $newRacikan = self::$query->insert('racikan', array(
                            'uid' => $uid_racikan,
                            'asesmen' => $MasterAsesmen,
                            //'resep' => $uid,
                            'kode' => '['. $Kode . ']' . $value['nama'],
                            'signa_qty' => $value['signaKonsumsi'],
                            'signa_pakai' => $value['signaTakar'],
                            'keterangan' => $value['keterangan'],
                            'aturan_pakai' => intval($value['aturanPakai']),
                            'qty' => $value['signaHari'],
                            'total' => 0,
                            'created_at' => parent::format_date(),
                            'updated_at' => parent::format_date()
                        ))
                            ->execute();

                        if($newRacikan['response_result'] > 0) {
                            /*$newResepDetail = self::$pdo->insert('resep_detail', array(
                                'resep' => $uid,
                                'obat' => $uid_racikan,
                                'aturan_pakai' => $value['aturanPakai'],
                                'harga' => 0,
                                'signa_qty' => $value['signaKonsumsi'],
                                'signa_pakai' => $value['signaTakar'],
                                'qty' => $value['signaHari'],
                                'satuan' => '',
                                'created_at' => parent::format_date(),
                                'updated_at' => parent::format_date()
                            ))
                            ->execute();*/

                            //Set Racikan Detail
                            foreach ($value['item'] as $RIKey => $RIValue) {
                                $newRacikanDetail = self::$query->insert('racikan_detail', array(
                                    'asesmen' => $MasterAsesmen,
                                    //'resep' => $uid_racikan,
                                    'obat' => $RIValue['obat'],
                                    'ratio' => floatval($RIValue['takaran']),
                                    'pembulatan' => ceil(floatval($RIValue['takaran'])),
                                    'kekuatan' => $RIValue['kekuatan'],
                                    //'takar_bulat' => $RIValue['takaranBulat'],
                                    //'takar_decimal' => $RIValue['takaranDecimalText'],
                                    'harga' => 0,
                                    'racikan' => $uid_racikan,
                                    'created_at' => parent::format_date(),
                                    'updated_at' => parent::format_date()
                                ))
                                    ->execute();
                            }
                        }
                    }
                }
            }
			return $newResep;
		}
	}

	private function set_tindakan_asesment($parameter, $MasterAsesmen)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $requested = array();
        foreach ($parameter['tindakan'] as $key => $value) {
            if (!in_array($value['item'], $requested)) {
                array_push($requested, $value['item']);
            }/* else {
                array_push($requested, $value['item']);
            }*/
        }
        $returnResponse = array();
        $registered = array();


        //Check Invoice
        $Invoice = new Invoice(self::$pdo);
        $InvoiceCheck = self::$query->select('invoice', array(
            'uid'
        ))
            ->where(array(
                'invoice.kunjungan' => '= ?',
                'AND',
                'invoice.deleted_at' => 'IS NULL'
            ), array(
                $parameter['kunjungan']
            ))
            ->execute();

        if (count($InvoiceCheck['response_data']) > 0) {
            $TargetInvoice = $InvoiceCheck['response_data'][0]['uid'];
        } else {
            $InvMasterParam = array(
                'kunjungan' => $parameter['kunjungan'],
                'pasien' => $parameter['pasien'],
                'keterangan' => 'Tagihan tindakan perobatan'
            );
            $NewInvoice = $Invoice->create_invoice($InvMasterParam);
            $TargetInvoice = $NewInvoice['response_unique'];
        }

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
            if (in_array($value['tindakan'], $requested)) {
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
            array_splice($parameter['tindakan'], array_search($value['tindakan'], $requested), 1);
        }




        foreach ($parameter['tindakan'] as $key => $value) {
            $Check = self::$query->select('asesmen_tindakan', array(
                'tindakan'
            ))
                ->where(array(
                    'asesmen_tindakan.kunjungan' => '= ?',
                    'AND',
                    'asesmen_tindakan.antrian' => '= ?',
                    'AND',
                    'asesmen_tindakan.tindakan' => '= ?'
                ), array(
                    $value['kunjungan'],
                    $value['antrian'],
                    $value['item']
                ))
                ->execute();
            $HargaTindakan = self::$query->select('master_tindakan_kelas_harga', array(
                'id',
                'tindakan',
                'kelas',
                'penjamin',
                'harga'
            ))
                ->where(array(
                    'master_tindakan_kelas_harga.penjamin' => '= ?',
                    'AND',
                    'master_tindakan_kelas_harga.kelas' => '= ?',
                    'AND',
                    'master_tindakan_kelas_harga.tindakan' => '= ?',
                    'AND',
                    'master_tindakan_kelas_harga.deleted_at' => 'IS NULL'
                ), array(
                    $parameter['penjamin'],
                    __UID_KELAS_GENERAL_RJ__,    //Fix 1 harga kelas GENERAL
                    $value['item']
                ))
                ->execute();
            $HargaFinal = (count($HargaTindakan['response_data']) > 0) ? $HargaTindakan['response_data'][0]['harga'] : 0;
            if(count($Check['response_data']) > 0) {
                $new_asesmen_tindakan = self::$query->update('asesmen_tindakan', array(
                    'harga' => $HargaFinal,
                    'updated_at' => parent::format_date()
                ))
                    ->where(array(
                        'asesmen_tindakan.kunjungan' => '= ?',
                        'AND',
                        'asesmen_tindakan.antrian' => '= ?',
                        'AND',
                        'asesmen_tindakan.tindakan' => '= ?'
                    ), array(
                        $value['kunjungan'],
                        $value['antrian'],
                        $value['item']
                    ))
                    ->execute();
            } else {
                $new_asesmen_tindakan = self::$query->insert('asesmen_tindakan', array(
                    'kunjungan' => $value['kunjungan'],
                    'antrian' => $value['antrian'],
                    'asesmen' => $MasterAsesmen,
                    'tindakan' => $value['item'],
                    'penjamin' => $parameter['penjamin'],
                    'kelas' => __UID_KELAS_GENERAL_RJ__,
                    'harga' => $HargaFinal,
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
            }




            if ($new_asesmen_tindakan['response_result'] > 0) {
                if ($parameter['charge_invoice'] === 'Y') {
                    $InvoiceDetail = $Invoice->append_invoice(array(
                        'invoice' => $TargetInvoice,
                        'item' => $value['item'],
                        'item_origin' => 'master_tindakan',
                        'qty' => 1,
                        'harga' => $HargaFinal,
                        'status_bayar' => 'N',
                        'subtotal' => $HargaFinal,
                        'discount' => 0,
                        'discount_type' => 'N',
                        'pasien' => $parameter['pasien'],
                        'penjamin' => $parameter['penjamin'],
                        'billing_group' => 'tindakan',
                        'keterangan' => 'Biaya Tindakan Perobatan',
                        'departemen' => $parameter['poli']
                    ));

                    array_push($returnResponse, $InvoiceDetail);
                }
            }
            array_push($returnResponse, $new_asesmen_tindakan);
        }


		$AsesmenInfo = self::$query->select('asesmen', array(
            'kunjungan',
            'antrian',
            'pasien'
        ))
            ->where(array(
                'asesmen.uid' => '= ?',
                'AND',
                'asesmen.deleted_at' => 'IS NULL'
            ), array(
                $MasterAsesmen
            ))
            ->execute();

		//Status Antrian


        if($parameter['charge_invoice'] === 'Y')  {
        //if($parameter['charge_tindakan']) {
            $antrian_status = self::$query->update('antrian_nomor', array(
                'status' => ($parameter['penjamin'] === __UIDPENJAMINUMUM__) ? 'K' : 'P'
            ))
                ->where(array(
                    'antrian_nomor.kunjungan' => '= ?',
                    'AND',
                    'antrian_nomor.antrian' => '= ?',
                    'AND',
                    'antrian_nomor.pasien' => '= ?'
                ), array(
                    $AsesmenInfo['response_data'][0]['kunjungan'],
                    $AsesmenInfo['response_data'][0]['antrian'],
                    $AsesmenInfo['response_data'][0]['pasien']
                ))
                ->execute();
        }

		return $returnResponse;
	}

	private function new_asesmen($parameter, $parent, $poli, $poli_uid) {
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
		$NewAsesmenPoli = parent::gen_uuid();

        if($poli_uid === __UIDFISIOTERAPI__)
        {
            $selectedICD9 = array();
            $selectedICD10Kerja = array();
            $selectedICD10Banding = array();

            foreach ($parameter['icd9'] as $ICD9BK => $ICD9BV) {
                array_push($selectedICD9, $ICD9BV['id']);
            }

            foreach ($parameter['icd10_kerja'] as $ICD10KK => $ICD10KV) {
                array_push($selectedICD10Kerja, $ICD10KV['id']);
            }

            foreach ($parameter['icd10_banding'] as $ICD10BK => $ICD10BV) {
                array_push($selectedICD10Banding, $ICD10BV['id']);
            }

            $saveParam = array(
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
                'icd9' => implode(',', $selectedICD9),
                'pemeriksaan_fisik' => $parameter['pemeriksaan_fisik'],
                'icd10_kerja' => implode(',', $selectedICD10Kerja),
                'diagnosa_kerja' => $parameter['diagnosa_kerja'],
                'icd10_banding' => implode(',', $selectedICD10Banding),
                'diagnosa_banding' => $parameter['diagnosa_banding'],
                'planning' => $parameter['planning'],

                'anamnesa' => $parameter['anamnesa'],
                'tatalaksana' => $parameter['tataLaksana'],
                'evaluasi' => $parameter['evaluasi'],
                'anjuran_bulan' => floatval($parameter['anjuranBulan']),
                'anjuran_minggu' => floatval($parameter['anjuranMinggu']),
                'suspek_akibat_kerja' => $parameter['suspek'],
                'hasil' => $parameter['hasil'],
                'kesimpulan' => $parameter['kesimpulan'],
                'rekomendasi' => $parameter['rekomendasi'],

                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            );
        } else if($poli_uid === __POLI_GIGI__) {
            $selectedICD10Kerja = array();
            $selectedICD10Banding = array();

            foreach ($parameter['icd10_kerja'] as $ICD10KK => $ICD10KV) {
                array_push($selectedICD10Kerja, $ICD10KV['id']);
            }

            foreach ($parameter['icd10_banding'] as $ICD10BK => $ICD10BV) {
                array_push($selectedICD10Banding, $ICD10BV['id']);
            }

            $saveParam = array(
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
                'icd10_kerja' => implode(',', $selectedICD10Kerja),
                'diagnosa_kerja' => $parameter['diagnosa_kerja'],
                'icd10_banding' => implode(',', $selectedICD10Banding),
                'diagnosa_banding' => $parameter['diagnosa_banding'],
                'planning' => $parameter['planning'],
                /*'anamnesa' => $parameter['anamnesa'],
                'tatalaksana' => $parameter['tataLaksana'],
                'evaluasi' => $parameter['evaluasi'],
                'anjuran_bulan' => floatval($parameter['anjuranBulan']),
                'anjuran_minggu' => floatval($parameter['anjuranMinggu']),
                'suspek_akibat_kerja' => $parameter['suspek'],
                'hasil' => $parameter['hasil'],
                'kesimpulan' => $parameter['kesimpulan'],
                'rekomendasi' => $parameter['rekomendasi'],*/
                'odontogram' => $parameter['odontogram'],
                'muka_simetris' => $parameter['simetris'],
                'tmj' => $parameter['sendi'],
                'bibir' => $parameter['bibir'],
                'lidah' => $parameter['lidah'],
                'mukosa' => $parameter['mukosa'],
                'torus' => $parameter['torus'],
                'gingiva' => $parameter['gingiva'],
                'frenulum' => $parameter['frenulum'],
                'kebersihan_mulut' => $parameter['mulut_bersih'],

                'keterangan_bibir' => $parameter['keterangan_bibir'],
                'keterangan_lidah' => $parameter['keterangan_lidah'],
                'keterangan_mukosa' => $parameter['keterangan_mukosa'],
                'keterangan_torus' => $parameter['keterangan_torus'],
                'keterangan_gingiva' => $parameter['keterangan_gingiva'],
                'keterangan_frenulum' => $parameter['keterangan_frenulum'],

                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            );

            /*$saveParam = array(
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
                'icd10_kerja' => implode(',', $selectedICD10Kerja),
                'diagnosa_kerja' => $parameter['diagnosa_kerja'],
                'icd10_banding' => implode(',', $selectedICD10Banding),
                'diagnosa_banding' => $parameter['diagnosa_banding'],
                'planning' => $parameter['planning'],

                'odontogram' => $parameter['odontogram'],
                'muka_simetris' => $parameter['simetris'],
                'tmj' => $parameter['sendi'],
                'bibir' => $parameter['bibir'],
                'lidah' => $parameter['lidah'],
                'mukosa' => $parameter['mukosa'],
                'torus' => $parameter['torus'],
                'gingiva' => $parameter['gingiva'],
                'frenulum' => $parameter['frenulum'],
                'kebersihan_mulut' => $parameter['mulut_bersih'],

                //'keterangan_mulut' => $parameter['keterangan_mulut'],
                'keterangan_bibir' => $parameter['keterangan_bibir'],
                'keterangan_lidah' => $parameter['keterangan_lidah'],
                'keterangan_mukosa' => $parameter['keterangan_mukosa'],
                'keterangan_torus' => $parameter['keterangan_torus'],
                'keterangan_gingiva' => $parameter['keterangan_gingiva'],
                'keterangan_frenulum' => $parameter['keterangan_frenulum'],

                'updated_at' => parent::format_date()
            );*/
        } else if($poli_uid === __POLI_MATA__) {
            $selectedICD10Kerja = array();
            $selectedICD10Banding = array();

            foreach ($parameter['icd10_kerja'] as $ICD10KK => $ICD10KV) {
                array_push($selectedICD10Kerja, $ICD10KV['id']);
            }

            foreach ($parameter['icd10_banding'] as $ICD10BK => $ICD10BV) {
                array_push($selectedICD10Banding, $ICD10BV['id']);
            }

            $saveParam = array(
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
                'icd10_kerja' => implode(',', $selectedICD10Kerja),
                'diagnosa_kerja' => $parameter['diagnosa_kerja'],
                'icd10_banding' => implode(',', $selectedICD10Banding),
                'diagnosa_banding' => $parameter['diagnosa_banding'],
                'meta_resep' => $parameter['mata_data'],
                'tujuan_resep' => $parameter['tujuan_resep'],
                'planning' => $parameter['planning'],
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            );
        } else {
            $selectedICD10Kerja = array();
            $selectedICD10Banding = array();

            foreach ($parameter['icd10_kerja'] as $ICD10KK => $ICD10KV) {
                array_push($selectedICD10Kerja, $ICD10KV['id']);
            }

            foreach ($parameter['icd10_banding'] as $ICD10BK => $ICD10BV) {
                array_push($selectedICD10Banding, $ICD10BV['id']);
            }

            $saveParam = array(
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
                'icd10_kerja' => implode(',', $selectedICD10Kerja),
                'diagnosa_kerja' => $parameter['diagnosa_kerja'],
                'icd10_banding' => implode(',', $selectedICD10Banding),
                'diagnosa_banding' => $parameter['diagnosa_banding'],
                'planning' => $parameter['planning'],
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            );
        }
		//insert
        if($poli_uid === __POLI_INAP__) {
            $worker = self::$query->insert('asesmen_medis_inap', $saveParam)
                ->execute();
        } else {
            $worker = self::$query->insert('asesmen_medis_' . $poli, $saveParam)
                ->execute();
        }


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
					'asesmen_medis_' . $poli,
					'I',
					json_encode($parameter),
					parent::format_date(),
					'N',
					$UserData['data']->log_id
				),
				'class'=>__CLASS__
			));
		}

		$worker['response_unique'] = $NewAsesmenPoli;
		return $worker;
	}


	/*--------------------------- ASESMEN RAWAT-------------------------------*/
	private function get_asesmen_rawat($parameter){		//uid antrian
		$antrian = self::get_pasien_asesmen_rawat($parameter);
		$antrian['asesmen_rawat'] = [];
		$Invetori = new Inventori(self::$pdo);
		$Pegawai = new Pegawai(self::$pdo);

		if(count($antrian) > 0) {
			//Poli Info
			$Poli = new Poli(self::$pdo);
			$PoliDetail = $Poli->get_poli_detail($antrian['antrian']['departemen'])['response_data'][0];

			$data = self::$query
				->select('asesmen_rawat_' . $PoliDetail['poli_asesmen'], array('*'))
				->where(array(
						'asesmen_rawat_' . $PoliDetail['poli_asesmen'] . '.deleted_at' => 'IS NULL',
						'AND',
						'asesmen_rawat_' . $PoliDetail['poli_asesmen'] . '.kunjungan' => '= ?',
						'AND',
						'asesmen_rawat_' . $PoliDetail['poli_asesmen'] . '.antrian' => '= ?',
						'AND',
						'asesmen_rawat_' . $PoliDetail['poli_asesmen'] . '.pasien' => '= ?'
					), array(
						$antrian['antrian']['kunjungan'],
						$antrian['antrian']['uid'],
						$antrian['antrian']['uid_pasien']
					)
				)
				->execute();
			if ($data['response_result'] > 0){
			    //Asesmen Kebidanan
                $bidan = self::$query->select('asesmen_kebidanan', array(
                    'tanggal_partus',
                    'tempat_partus',
                    'jenis_partus',
                    'penolong',
                    'nifas',
                    'jenkel_anak',
                    'keadaan_sekarang',
                    'keterangan',
                    'bb_anak',
                    'usia_kehamilan'
                ))
                    ->where(array(
                        'asesmen_kebidanan.asesmen' => '= ?',
                        'AND',
                        'asesmen_kebidanan.deleted_at' => 'IS NULL'
                    ), array(
                        $data['response_data'][0]['asesmen']
                    ))
                    ->execute();

                $antrian['asesmen_bidan'] = $bidan['response_data'];



                //Hanya untuk IGD
                if($antrian['antrian']['departemen'] === __POLI_IGD__) {
                    $infus = self::$query->select('asesmen_igd_infus', array(
                        'id',
                        'asesmen',
                        'pukul',
                        'obat',
                        'dosis',
                        'rute',
                        'keputusan',
                        'oleh',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                        'igd_type'
                    ))
                        ->where(array(
                            'asesmen_igd_infus.asesmen' => '= ?'
                        ), array(
                            $data['response_data'][0]['asesmen']
                        ))
                        ->order(array(
                            'created_at' => 'ASC'
                        ))
                        ->execute();
                    foreach ($infus['response_data'] as $infusKey => $infusValue) {
                        $infus['response_data'][$infusKey]['obat'] = $Invetori->get_item_detail($infusValue['obat'])['response_data'][0];
                        $infus['response_data'][$infusKey]['oleh'] = $Pegawai->get_detail_pegawai($infusValue['oleh'])['response_data'][0];
                        if(isset($infusValue['deleted_at'])) {
                            $LogInfo = self::$query->select('log_activity', array(
                                'id',
                                'user_uid'
                            ))
                                ->where(array(
                                    'log_activity.table_name' => '= ?',
                                    'AND',
                                    'log_activity.unique_target' => '= ?',
                                    'AND',
                                    'log_activity.action' => '= ?'
                                ), array(
                                    'asesmen_igd_infus',
                                    $infusValue['id'],
                                    'D'
                                ))
                                ->execute();
                            $infus['response_data'][$infusKey]['dihapus_oleh'] = $Pegawai->get_detail_pegawai($LogInfo['response_data'][0]['user_uid'])['response_data'][0]['nama'];
                        }
                    }
                    $antrian['asesmen_infus'] = $infus['response_data'];
                }

				$antrian['asesmen_rawat'] = $data['response_data'][0];
			}
		}

		return $antrian;
	}

	private function update_asesmen_rawat($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
		$MasterUID = '';
		//Prepare Poli
		$Poli = new Poli(self::$pdo);
		$PoliDetail = $Poli->get_poli_detail($parameter['dataAntrian']['departemen'])['response_data'][0];
        $DataPartus = $parameter['dataObj']['partus_list'];

        foreach ($parameter['dataObj'] as $dataKey => $dataValue) {
            if(!isset($dataKey) || $dataKey === 'undefined' || $dataKey == 'undefined') {
                unset($parameter['dataObj'][$dataKey]);
            }
        }
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
				'asesmen.pasien' => '= ?'
			), array(
				$parameter['dataAntrian']['departemen'],
				$parameter['dataAntrian']['kunjungan'],
				$parameter['dataAntrian']['uid'],
				$parameter['dataAntrian']['uid_pasien']
			))
			->execute();

		if(count($check['response_data']) > 0) {
			$MasterUID = $check['response_data'][0]['uid'];
			$returnResponse = array();

			//Poli Asesmen Rawat Check
			$poli_check = self::$query->select('asesmen_rawat_' . $PoliDetail['poli_asesmen'], array(
					'uid'
				))
				->where(array(
					'asesmen_rawat_' . $PoliDetail['poli_asesmen'] . '.deleted_at' => 'IS NULL',
					'AND',
					'asesmen_rawat_' . $PoliDetail['poli_asesmen'] . '.asesmen' => '= ?'
				), array(
					$check['response_data'][0]['uid']
				))
				->execute();

			if(count($poli_check['response_data']) > 0) {
				//update asesmen rawat --> sudah oke
				$parameter['dataObj']['updated_at'] = parent::format_date();
                unset($parameter['dataObj']['partus_list']);
				$rawat = self::$query
					->update('asesmen_rawat_' . $PoliDetail['poli_asesmen'], $parameter['dataObj'])
					->where(array(
							'asesmen_rawat_' . $PoliDetail['poli_asesmen'] . '.deleted_at' => 'IS NULL',
							'AND',
							'asesmen_rawat_' . $PoliDetail['poli_asesmen'] . '.uid' => '= ?',
							'AND',
							'asesmen_rawat_' . $PoliDetail['poli_asesmen'] . '.kunjungan' => '= ?',
							'AND',
							'asesmen_rawat_' . $PoliDetail['poli_asesmen'] . '.antrian' => '= ?',
							'AND',
							'asesmen_rawat_' . $PoliDetail['poli_asesmen'] . '.pasien' => '= ?',
							'AND',
							'asesmen_rawat_' . $PoliDetail['poli_asesmen'] . '.asesmen' => '= ?'
						), array(
							$poli_check['response_data'][0]['uid'],
							$parameter['dataAntrian']['kunjungan'],
							$parameter['dataAntrian']['uid'],
							$parameter['dataAntrian']['uid_pasien'],
							$MasterUID
						)
					)
					->execute();

				if($rawat['response_result'] > 0) {
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
							'asesmen_rawat_' . $PoliDetail['poli_asesmen'],
							'U',
							parent::format_date(),
							'N',
							$UserData['data']->log_id
						),
						'class'=>__CLASS__
					));

					$updateAsesmen = self::$query
						->update('asesmen', array('status' => 'D'))
						->where(array('asesmen.uid' => '= ?'), array($MasterUID))
						->execute();
				}
			} else {
				//new asesmen rawat --> sudah oke
                unset($parameter['dataObj']['partus_list']);
                $parameter['dataObj']['antrian'] = $parameter['dataAntrian']['uid'];
				$parameter['dataObj']['no_rm'] = $parameter['dataPasien']['no_rm'];
				$parameter['dataObj']['pasien'] = $parameter['dataAntrian']['uid_pasien'];
				$parameter['dataObj']['kunjungan'] = $parameter['dataAntrian']['kunjungan'];
				$parameter['dataObj']['departemen'] = $parameter['dataAntrian']['departemen'];


				$rawat = self::new_asesmen_rawat($parameter['dataObj'], $MasterUID, $PoliDetail['poli_asesmen'], $parameter);
			}

			$returnResponse = $rawat;
		} else {
			//new asesmen --> sudah oke
			$NewAsesmen = parent::gen_uuid();
			$MasterUID = $NewAsesmen;
			$asesmen_poli = self::$query->insert('asesmen', array(
				'uid' => $NewAsesmen,
				'poli' => $parameter['dataAntrian']['departemen'],
				'kunjungan' => $parameter['dataAntrian']['kunjungan'],
				'antrian' => $parameter['dataAntrian']['uid'],
				'pasien' => $parameter['dataAntrian']['uid_pasien'],
				'dokter' => $parameter['dataAntrian']['dokter'],
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

				$parameter['dataObj']['antrian'] = $parameter['dataAntrian']['uid'];
				$parameter['dataObj']['no_rm'] = $parameter['dataPasien']['no_rm'];
				$parameter['dataObj']['pasien'] = $parameter['dataAntrian']['uid_pasien'];
				$parameter['dataObj']['kunjungan'] = $parameter['dataAntrian']['kunjungan'];
				$parameter['dataObj']['departemen'] = $parameter['dataAntrian']['departemen'];

				$rawat = self::new_asesmen_rawat($parameter['dataObj'], $NewAsesmen, $PoliDetail['poli_asesmen'], $parameter);

				$returnResponse = ["asesmen"=>$rawat,"asesmen_rawat"=>$rawat];
			} else {
				$returnResponse = $asesmen_poli;
			}
		}

		$proceed_bidan_id = array();
		$proceed_bidan_result = array();
		//reset Partus
        $resetPartus = self::$query->update('asesmen_kebidanan', array(
            'deleted_at' => parent::format_date()
        ))
            ->where(array(
                'asesmen_kebidanan.asesmen' => '= ?'
            ), array(
                $MasterUID
            ))
            ->execute();
		foreach ($DataPartus as $partKey => $partValue) {
            //Asesmen Kebidanan
            $checkBidan = self::$query->select('asesmen_kebidanan', array(
                'id'
            ))
                ->where(array(
                    'asesmen_kebidanan.asesmen' => '= ?'
                ), array(
                    $MasterUID
                ))
                ->execute();
            if(count($checkBidan['response_data']) > 0 && !in_array($checkBidan['response_data'][0]['id'], $proceed_bidan_id)) {
                $proceed_bidan = self::$query->update('asesmen_kebidanan', array(
                    'tanggal_partus' => $partValue['tanggal'],
                    'usia_kehamilan' => $partValue['usia'],
                    'tempat_partus' => $partValue['tempat'],
                    'jenis_partus' => $partValue['jenis'],
                    'penolong' => $partValue['penolong'],
                    'nifas' => $partValue['nifas'],
                    'jenkel_anak' => $partValue['jenkel_anak'],
                    'bb_anak' => $partValue['bb_anak'],
                    'keadaan_sekarang' => $partValue['keadaan_sekarang'],
                    'keterangan' => $partValue['keterangan'],
                    'deleted_at' => NULL
                ))
                    ->where(array(
                        'asesmen_kebidanan.id' => '= ?'
                    ), array(
                        $checkBidan['response_data'][0]['id']
                    ))
                    ->execute();
                array_push($proceed_bidan_id, $checkBidan['response_data'][0]['id']);
            } else {
                $proceed_bidan = self::$query->insert('asesmen_kebidanan', array(
                    'asesmen' => $MasterUID,
                    'tanggal_partus' => $partValue['tanggal'],
                    'usia_kehamilan' => $partValue['usia'],
                    'tempat_partus' => $partValue['tempat'],
                    'jenis_partus' => $partValue['jenis'],
                    'penolong' => $partValue['penolong'],
                    'nifas' => $partValue['nifas'],
                    'jenkel_anak' => $partValue['jenkel_anak'],
                    'bb_anak' => $partValue['bb_anak'],
                    'keadaan_sekarang' => $partValue['keadaan_sekarang'],
                    'keterangan' => $partValue['keterangan'],
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
            }

            array_push($proceed_bidan_result, $proceed_bidan);
        }


        $returnResponse['bidan_partus'] = $proceed_bidan_result;
        $returnResponse['master_uid'] = $MasterUID;
		return $returnResponse;
	}

	private function get_antrian_asesmen_rawat($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
		$dataParsed = array();
		$listPoli = [];
		$getPoli = self::$query
			->select('master_poli_perawat', 
				array(
					'poli',
					'perawat'
				)
			)
			->where(array(
					'master_poli_perawat.perawat' => '= ?',
					'AND',
					'master_poli_perawat.deleted_at' => 'IS NULL'
				), array(
					$UserData['data']->uid
				)
			)
			->execute();

		foreach ($getPoli['response_data'] as $key => $value) {
			array_push($listPoli, $value);
		}

		$antrian = self::get_list_antrian($listPoli);

		$autonum = 1;
		$Pegawai = new Pegawai(self::$pdo);
        $Poli = new Poli(self::$pdo);
		foreach ($antrian as $key => $value) {

			$PoliDetail = $Poli->get_poli_detail($value['uid_poli'])['response_data'][0];

			$cek_asesment = self::cek_asesmen_rawat_detail($PoliDetail['poli_asesmen'], $value['uid']);
			$antrian[$key]['status_asesmen'] = false;
            $antrian[$key]['uid_kunjungan'] = $cek_asesment['response_data'][0]['uid_kunjungan'];
            $antrian[$key]['perawat'] = $Pegawai->get_detail($cek_asesment['response_data'][0]['perawat'])['response_data'][0];

			if ($cek_asesment['response_result'] > 0){
				$antrian[$key]['uid_asesmen_rawat'] = $cek_asesment['response_data'][0]['uid'];
				$antrian[$key]['status_asesmen'] = true; 
			}

			$antrian[$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $antrian;
	}

	private function get_list_antrian($parameter){
		$antrian = new Antrian(self::$pdo);

		$listPasien = [];
		foreach ($parameter as $Pkey => $value) {
			$antrianData = $antrian->get_antrian_by_poli($value['poli'])['response_data'];

			foreach ($antrianData as $key => $Pvalue) {
                if ($Pvalue['uid_penjamin'] === __UIDPENJAMINBPJS__) {
                    $SEP = self::$query->select('bpjs_sep', array(
                        'uid',
                        'sep_no'
                    ))
                        ->where(array(
                            'bpjs_sep.antrian' => '= ?',
                            'AND',
                            'bpjs_sep.deleted_at' => 'IS NULL'
                        ), array(
                            $Pvalue['uid']
                        ))
                        ->execute();
                    if (count($SEP['response_data']) > 0) {
                        array_push($listPasien, $Pvalue);
                    }
                } else {
                    array_push($listPasien, $Pvalue);
                }
			}
		}

		return $listPasien;
	}

	private function get_antrian_asesmen_medis($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);

        $Pasien = new Pasien(self::$pdo);
        $Poli = new Poli(self::$pdo);

		$antrianClass = new Antrian(self::$pdo);
		$antrian = $antrianClass->get_antrian_by_dokter($UserData['data']->uid, $parameter[2]);

		$antrianPasien = [];

		$autonum = 1;
		$returnData = array();
		foreach ($antrian['response_data'] as $key => $value) {
            if($value['uid_poli'] === __POLI_INAP__) {
                $PoliDetail = array(
                    'poli_asesmen' => 'inap'
                );
            } else {
                $PoliDetail = $Poli->get_poli_info($value['uid_poli'])['response_data'][0];
            }


            $antrian['response_data'][$key]['poli_detail'] = $PoliDetail;

            $cek_asesment = self::cek_asesmen_medis_detail($PoliDetail['poli_asesmen'], $value['uid']);
            $antrian['response_data'][$key]['status_asesmen'] = false;

            if ($cek_asesment['response_result'] > 0) {
                $antrian['response_data'][$key]['uid_asesmen_medis'] = $cek_asesment['response_data'][0]['uid'];
                $antrian['response_data'][$key]['asesmen_detail'] = $cek_asesment['response_data'][0];
                $antrian['response_data'][$key]['status_asesmen'] = true;
            }

            //Pasien Detail
            $PasienDetail = $Pasien->get_pasien_detail('pasien', $value['uid_pasien']);
            $antrian['response_data'][$key]['pasien_detail'] = $PasienDetail['response_data'][0];

            $antrian['response_data'][$key]['autonum'] = $autonum;
            $autonum++;

		    if($value['uid_penjamin'] !== __UIDPENJAMINUMUM__) {
                if($value['uid_penjamin'] === __UIDPENJAMINBPJS__) {
                    //Harus ada SEP
                    $checkSEP = self::$query->select('bpjs_sep', array(
                        'uid'
                    ))
                        ->where(array(
                            'bpjs_sep.antrian' => '= ?',
                            'AND',
                            'bpjs_sep.deleted_at' => 'IS NULL'
                        ), array(
                            $value['uid']
                        ))
                        ->execute();
                    if(count($checkSEP['response_data']) > 0) {
                        array_push($returnData, $antrian['response_data'][$key]);
                    }
                } else {
                    array_push($returnData, $antrian['response_data'][$key]);
                }
            } else {
                array_push($returnData, $antrian['response_data'][$key]);
            }



		}
		$antrian['response_data'] = $returnData;
		return $antrian;
	}

	/*private function get_list_antrian_medis($parameter){
		$antrian = new Antrian(self::$pdo);

		$listPasien = [];
		foreach ($parameter as $key => $value) {
			$antrianData = $antrian->get_antrian_by_dokter($value['poli'])['response_data'];

			foreach ($antrianData as $key => $value) {				
				array_push($listPasien, $value);
			}
		}

		return $listPasien;
	}*/

	private function cek_asesmen_rawat_detail($poli_prefix, $parameter){
		$data = self::$query
				->select('asesmen_rawat_' . $poli_prefix, array('uid','antrian','kunjungan as uid_kunjungan','perawat'))
				->where(array(
							'deleted_at' => 'IS NULL',
							'AND',
							'antrian' => '= ?'
					),
					array($parameter)
				)
				->execute();

		return $data;
	}

	private function cek_asesmen_medis_detail($poli_prefix, $parameter){
		$data = self::$query
				->select('asesmen_medis_' . $poli_prefix, array('uid','antrian', 'keluhan_utama'))
				->where(array(
							'deleted_at' => 'IS NULL',
							'AND',
							'antrian' => '= ?'
					),
					array($parameter)
				)
				->execute();

		return $data;
	}

	private function new_asesmen_rawat($dataAsesmen, $uid_asesmen, $poli, $parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
		$uid = parent::gen_uuid();

        $DataPartus = $dataAsesmen['partus_list'];




		unset($dataAsesmen['partus_list']);

		$dataAsesmen['uid'] = $uid;
		$dataAsesmen['asesmen'] = $uid_asesmen;
		$dataAsesmen['perawat'] = $UserData['data']->uid;
		$dataAsesmen['waktu_pengkajian'] = parent::format_date();

		$dataAsesmen['created_at'] = parent::format_date();
		$dataAsesmen['updated_at'] = parent::format_date();

		foreach ($dataAsesmen as $dataKey => $dataValue) {
		    if(strval($dataKey) === 'undefined') {
		        unset($dataAsesmen[$dataKey]);
            }
        }

		$rawat = self::$query
			->insert('asesmen_rawat_' . $poli, $dataAsesmen)
			->execute();

		if($rawat['response_result'] > 0) {
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
					$uid,
					$UserData['data']->uid,
					'asesmen_rawat_' . $poli,
					'I',
					json_encode($parameter),
					parent::format_date(),
					'N',
					$UserData['data']->log_id
				),
				'class'=>__CLASS__
			));

			$updateAsesmen = self::$query
				->update('asesmen', array('status' => 'D'))
				->where(array('asesmen.uid' => '= ?'), array($uid_asesmen))
				->execute();




            foreach ($DataPartus as $partKey => $partValue) {
                //Asesmen Kebidanan
                $checkBidan = self::$query->select('asesmen_kebidanan', array(
                    'id'
                ))
                    ->where(array(
                        'asesmen_kebidanan.asesmen' => '= ?'
                    ), array(
                        $uid_asesmen
                    ))
                    ->execute();
                if(count($checkBidan['response_data']) > 0 && !in_array($checkBidan['response_data'][0]['id'], $proceed_bidan_id)) {
                    $proceed_bidan = self::$query->update('asesmen_kebidanan', array(
                        'tanggal_partus' => $partValue['tanggal'],
                        'usia_kehamilan' => $partValue['usia'],
                        'tempat_partus' => $partValue['tempat'],
                        'jenis_partus' => $partValue['jenis'],
                        'penolong' => $partValue['penolong'],
                        'nifas' => $partValue['nifas'],
                        'jenkel_anak' => $partValue['jenkel_anak'],
                        'bb_anak' => $partValue['bb_anak'],
                        'keadaan_sekarang' => $partValue['keadaan_sekarang'],
                        'keterangan' => $partValue['keterangan'],
                        'deleted_at' => NULL
                    ))
                        ->where(array(
                            'asesmen_kebidanan.id' => '= ?'
                        ), array(
                            $checkBidan['response_data'][0]['id']
                        ))
                        ->execute();
                    array_push($proceed_bidan_id, $checkBidan['response_data'][0]['id']);
                } else {
                    $proceed_bidan = self::$query->insert('asesmen_kebidanan', array(
                        'asesmen' => $uid_asesmen,
                        'tanggal_partus' => $partValue['tanggal'],
                        'usia_kehamilan' => $partValue['usia'],
                        'tempat_partus' => $partValue['tempat'],
                        'jenis_partus' => $partValue['jenis'],
                        'penolong' => $partValue['penolong'],
                        'nifas' => $partValue['nifas'],
                        'jenkel_anak' => $partValue['jenkel_anak'],
                        'bb_anak' => $partValue['bb_anak'],
                        'keadaan_sekarang' => $partValue['keadaan_sekarang'],
                        'keterangan' => $partValue['keterangan'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                }

                array_push($proceed_bidan_result, $proceed_bidan);
            }
		}

		return $rawat;
	}

	//function for combine function for get antrian data and pasien data detail
	private function get_pasien_asesmen_rawat($params){	
		$dataAntrian = self::get_data_antrian_detail($params);
		$dataPasien = self::get_data_pasien($dataAntrian['uid_pasien']);

		$penjamin = new Penjamin(self::$pdo);
		$param = ['','penjamin-detail', $dataAntrian['penjamin']];
		$get_penjamin = $penjamin->__GET__($param);
        $dataAntrian['uid_penjamin'] = $dataAntrian['penjamin'];
		$dataAntrian['nama_penjamin'] = $get_penjamin['response_data'][0]['nama'];

		$poli = new Poli(self::$pdo);
		$param = ['','poli-detail', $dataAntrian['departemen']];
		$get_poli = $poli->__GET__($param);
		$dataAntrian['nama_departemen'] = $get_poli['response_data'][0]['nama'];

		$result = array(
					"antrian"=>$dataAntrian,
					"pasien"=>$dataPasien
				);

		return $result;
	}

	//function for get antrian detail data
	private function get_data_antrian_detail($parameter){ //$parameter = uid antrian
		/*-------- GET DATA ANTRIAN ----------*/
		$antrian = new Antrian(self::$pdo);
		$param = ['','antrian-detail', $parameter];
		$get_antrian = $antrian->__GET__($param);

		$get_kunjungan = $antrian->get_kunjungan_detail($get_antrian['response_data'][0]['kunjungan']);

		$result = array(
					"uid"=>$get_antrian['response_data'][0]['uid'],
					"kunjungan"=>$get_antrian['response_data'][0]['kunjungan'],
					"uid_pasien"=>$get_antrian['response_data'][0]['pasien'],
					"departemen"=>$get_antrian['response_data'][0]['departemen'],
					"penjamin"=>$get_antrian['response_data'][0]['penjamin'],
					"dokter"=>$get_antrian['response_data'][0]['dokter'],
					"waktu_masuk"=>$get_antrian['response_data'][0]['waktu_masuk'],
					'pj_pasien'=>$get_kunjungan['response_data'][0]['pj_pasien'],
					'info_didapat_dari'=>$get_kunjungan['response_data'][0]['info_didapat_dari'],
				);

		return $result;
	}

	//function for get pasien detail data
	private function get_data_pasien($parameter) {		//$parameter = uid pasien
		/*--------- GET NO RM --------------- */
		$pasien = new Pasien(self::$pdo);
		$param = ['','pasien-detail', $parameter];
		$get_pasien = $pasien->__GET__($param);

		$term = new Terminologi(self::$pdo);
		$value = $get_pasien['response_data'][0]['jenkel'];
		$param = ['','terminologi-items-detail',$value];
		$get_jenkel = $term->__GET__($param);

		$value = $get_pasien['response_data'][0]['panggilan'];
		$param = ['','terminologi-items-detail',$value];
		$get_panggilan = $term->__GET__($param);

		$result = array(
            'uid'=>$get_pasien['response_data'][0]['uid'],
            'no_rm'=>$get_pasien['response_data'][0]['no_rm'],
            'nama'=>$get_pasien['response_data'][0]['nama'],
            'tanggal_lahir'=>date('d F Y', strtotime($get_pasien['response_data'][0]['tanggal_lahir'])),
            'jenkel'=>$get_jenkel['response_data'][0]['nama'],
            'usia'=>$get_pasien['response_data'][0]['usia'],
            'alamat'=>$get_pasien['response_data'][0]['alamat'],
            'no_telp'=>$get_pasien['response_data'][0]['kontak'],
            'id_jenkel'=>$get_pasien['response_data'][0]['jenkel'],
            'panggilan'=>$get_panggilan['response_data'][0]['nama']
        );

		return $result;
	}
}