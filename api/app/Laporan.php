<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Laporan extends Utility
{
    static $pdo;
    static $query;

    protected static function getConn()
    {
        return self::$pdo;
    }

    public function __construct($connection)
    {
        self::$pdo = $connection;
        self::$query = new Query(self::$pdo);
    }

    public function __GET__($parameter = array()) {
        switch($parameter[1]) {
            case 'template_rekap':
                return self::template_rekap($parameter);
                break;
        }
    }

    public function __POST__($parameter = array())
    {
        switch ($parameter['request']) {
            case 'kunjungan_rawat_jalan':
                return self::kunjungan_rawat_jalan($parameter);
                break;
            case 'kunjungan_rawat_inap':
                return self::kunjungan_rawat_inap($parameter);
                break;
            case 'kunjungan_igd':
                return self::kunjungan_igd($parameter);
                break;
            case 'keuangan':
                return self::keuangan($parameter);
                break;
            case 'keuangan_billing_harian':
                return self::keuangan_billing_harian($parameter);
                break;
            case 'penyakit':
                return self::penyakit($parameter);
                break;
            case 'obat_penjamin':
                return self::obat_penjamin($parameter);
                break;
            case 'laboratorium':
                return self::report_laboratorium($parameter);
                break;
            case 'radiologi':
                return self::report_radiologi($parameter);
                break;
            case 'farmasi':
                return self::report_farmasi($parameter);
                break;
            case 'farmasi_item':
                return self::report_farmasi_item($parameter);
                break;
            case 'farmasi_resep':
                return self::report_farmasi_resep($parameter);
                break;
            case 'farmasi_mutasi':
                return self::report_farmasi_mutasi($parameter);
                break;
            case 'farmasi_stok_opname':
                return self::report_farmasi_stok_opname($parameter);
                break;
            case 'farmasi_retur':
                    return self::report_farmasi_retur($parameter);
                    break;
            case 'kamar_operasi':
                return self::report_kamar_operasi($parameter);
                break;
            case 'print_kunjungan_rawat_jalan':
                return self::print_kunjungan_rawat_jalan($parameter);
                break;
            case 'print_kunjungan_rawat_inap':
                return self::print_kunjungan_rawat_inap($parameter);
                break;
            case 'print_kunjungan_igd':
                return self::print_kunjungan_igd($parameter);
                break;
            case 'print_farmasi_item':
                return self::print_report_farmasi_item($parameter);
                break;
            case 'print_farmasi_resep':
                return self::print_report_farmasi_resep($parameter);
                break;
            case 'print_farmasi_mutasi':
                return self::print_report_farmasi_mutasi($parameter);
                break;
            case 'print_farmasi_stok_opname':
                return self::print_report_farmasi_stok_opname($parameter);
                break;
            case 'print_farmasi_retur':
                return self::print_report_farmasi_retur($parameter);
                break;
            case 'recalculate':
                return '';
                break;
    
        }
    }

    private function template_rekap($parameter) {
        $data = self::$query->select('laporan_rekap_pendapatan_template', array(
            'id', 'kategori', 'subkategori', 'identifier'
        ))
            ->where(array(
                'deleted_at' => 'IS NULL'
            ), array())
            ->execute();
        foreach($data['response_data'] as $key => $value) {
            $count = self::$query->select('laporan_rekap_pendapatan', array(
                'id', 'bulan', 'tahun', 'total'
            ))
                ->where(array(
                    'template_rekap' => '= ?',
                    'AND',
                    'bulan' => '= ?',
                    'AND',
                    'tahun' => '= ?'
                ), array($value['id'], $parameter[2], $parameter[3]))
                ->execute();
            $data['response_data'][$key]['total'] = floatval($count['response_data'][0]['total']);
        }
        return $data;
    }

    private function penyakit($parameter) {
        $ICDUnique = array();
        $ICDUnSorted = array();

        $Poli = new Poli(self::$pdo);
        $data = self::$query->select('asesmen', array(
            'uid',
            'poli',
            'antrian',
            'pasien'
        ))
            ->join('antrian', array(
                'departemen'
            ))
            ->on(array(
                array('asesmen.antrian', '=', 'antrian.uid')
            ))
            ->where(array(
                'asesmen.created_at' => 'BETWEEN ? AND ?',
            ), array(
                $parameter['from'], $parameter['to']
            ))
            ->execute();
        foreach ($data['response_data'] as $key => $value) {
            $PoliDetail = $Poli->get_poli_detail($value['departemen'])['response_data'][0];
            $AsesmenDokter = self::$query->select('asesmen_medis_' . $PoliDetail['poli_asesmen'], array(
                'icd10_kerja',
                'icd10_banding',
            ))
                ->where(array(
                    'asesmen_medis_' . $PoliDetail['poli_asesmen'] . '.antrian' => '= ?',
                    'AND',
                    'asesmen_medis_' . $PoliDetail['poli_asesmen'] . '.deleted_at' => 'IS NULL'
                ), array(
                    $value['antrian']
                ))
                ->execute();
            $icd10_kerja = explode(',', $AsesmenDokter['response_data'][0]['icd10_kerja']);
            $icd10_banding = explode(',', $AsesmenDokter['response_data'][0]['icd10_banding']);
            foreach ($icd10_kerja as $icdKey => $icdValue) {
                if(!is_nan($icdValue) && !empty($icdValue) && $icdValue !== '') {
                    $ICD10 = self::$query->select('master_icd_10', array(
                        'id', 'kode', 'nama'
                    ))
                        ->where(array(
                            'master_icd_10.id' => '= ?',
                            'AND',
                            'master_icd_10.deleted_at' => 'IS NULL'
                        ), array(
                            $icdValue
                        ))
                        ->execute();
                    if(count($ICD10['response_data']) > 0) {
                        if(!isset($ICDUnique[$ICD10['response_data'][0]['kode']]) && $ICDUnique[$ICD10['response_data'][0]['kode']] !== '') {
                            $ICDUnique[$ICD10['response_data'][0]['kode']] = array(
                                'detail' => $ICD10['response_data'][0],
                                'count' => 0
                            );
                        }

                        $ICDUnique[$ICD10['response_data'][0]['kode']]['count'] += 1;
                    }
                }
            }

            foreach ($icd10_banding as $icdKey => $icdValue) {
                if(!is_nan($icdValue) && !empty($icdValue) && $icdValue !== '') {
                    $ICD10 = self::$query->select('master_icd_10', array(
                        'id', 'kode', 'nama'
                    ))
                        ->where(array(
                            'master_icd_10.id' => '= ?',
                            'AND',
                            'master_icd_10.deleted_at' => 'IS NULL'
                        ), array(
                            $icdValue
                        ))
                        ->execute();

                    if(count($ICD10['response_data']) > 0) {
                        if(!isset($ICDUnique[$ICD10['response_data'][0]['kode']]) && $ICDUnique[$ICD10['response_data'][0]['kode']] !== '') {
                            $ICDUnique[$ICD10['response_data'][0]['kode']] = array(
                                'detail' => $ICD10['response_data'][0],
                                'count' => 0
                            );
                        }

                        $ICDUnique[$ICD10['response_data'][0]['kode']]['count'] += 1;
                    }
                }
            }
            $data['response_data'][$key]['icd10_kerja'] = $icd10_kerja;
            $data['response_data'][$key]['icd10_banding'] = $icd10_banding;
        }

        foreach ($ICDUnique as $key => $value) {
            array_push($ICDUnSorted, $value);
        }







        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $data_all = $ICDUnSorted;

            $filter = array();

            foreach ($data_all as $key => $value) {
                $checker_nama = stripos($value['detail']['nama'],$parameter['search']['value']);
                $checker_kode = stripos($value['detail']['kode'],$parameter['search']['value']);
                if(
                ($checker_nama >= 0 && $checker_nama !== false) ||
                ($checker_kode >= 0 && $checker_kode !== false)
                ) {
                    array_push($filter, $data_all[$key]);
                }
            }

            usort($filter, function($a, $b) {
                return $a['count'] <=> $b['count'];
            });
            $autonum = 1;
            foreach ($filter as $key => $value) {
                $filter[$key]['autonum'] = $autonum;
                $autonum++;
            }

            $prepare = array(
                'data' => array_slice($filter, intval($parameter['start']), intval($parameter['length'])),
                'recordsTotal' => count($filter),
                'recordsFiltered' => count($filter),
                'length' => intval($parameter['length']),
                'start' => intval($parameter['start']),
                'response_draw' => $parameter['draw']
            );
        } else {
            usort($ICDUnSorted, function($a, $b) {
                return $b['count'] <=> $a['count'];
            });

            $autonum = 1;
            foreach ($ICDUnSorted as $key => $value) {
                $ICDUnSorted[$key]['autonum'] = $autonum;
                $autonum++;
            }

            $prepare = array(
                'data' => array_slice($ICDUnSorted, intval($parameter['start']), intval($parameter['length'])),
                'recordsTotal' => count($ICDUnSorted),
                'recordsFiltered' => count($ICDUnSorted),
                'length' => intval($parameter['length']),
                'start' => intval($parameter['start']),
                'response_draw' => $parameter['draw']
            );
        }

        return $prepare;
    }

    private function obat_penjamin($parameter) {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'invoice_detail.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'invoice_detail.item_type' => '= ?',
                'AND',
                'master_inv.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array(
                $parameter['from'], $parameter['to'], 'master_inv'
            );
        } else {
            $paramData = array(
                'invoice_detail.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'invoice_detail.item_type' => '= ?'
            );

            $paramValue = array(
                $parameter['from'], $parameter['to'], 'master_inv'
            );
        }


        $data_populator = array();



        if (intval($parameter['length']) < 0) {
            $data = self::$query->select('invoice_detail', array(
                'item',
                'qty',
                'harga',
                'subtotal',
                'discount',
                'discount_type',
                'penjamin',
                'created_at'
            ))
                ->join('master_inv', array(
                    'nama',
                    'satuan_terkecil'
                ))
                ->join('master_inv_satuan', array(
                    'nama'
                ))
                ->on(array(
                    array('invoice_detail.item', '=', 'master_inv.uid'),
                    array('master_inv.satuan_terkecil', '=', 'master_inv_satuan.uid')
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('invoice_detail', array(
                'item',
                'qty',
                'harga',
                'subtotal',
                'discount',
                'discount_type',
                'penjamin',
                'created_at'
            ))
                ->join('master_inv', array(
                    'uid',
                    'nama',
                    'satuan_terkecil'
                ))
                ->join('master_inv_satuan', array(
                    'nama'
                ))
                ->on(array(
                    array('invoice_detail.item', '=', 'master_inv.uid'),
                    array('master_inv.satuan_terkecil', '=', 'master_inv_satuan.uid')
                ))
                /*->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))*/
                ->where($paramData, $paramValue)
                ->execute();
        }

        $data['response_draw'] = intval($parameter['draw']);
        $autonum = intval($parameter['start']) + 1;
        $Inventori = new Inventori(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);
        $dataPenjamin = $Penjamin->get_penjamin()['response_data'];

        foreach ($data['response_data'] as $key => $value) {
            /*if($value['penjamin'] === __UIDPENJAMINUMUM__) {
                //Ambil dari payment
            } else {

            }*/
            $data['response_data'][$key]['obat'] = $Inventori->get_item_info($value['item'])['response_data'][0];
            $data['response_data'][$key]['autonum'] = $autonum;
            if(!isset($data_populator[$value['item']])) {
                $data_populator[$value['item']] = array();
                if(!isset($data_populator[$value['item']][$value['penjamin']])) {
                    $data_populator[$value['item']][$value['penjamin']] = 0;
                }

                foreach ($dataPenjamin as $PenjaminKey => $PenjaminValue) {
                    if(!isset($data_populator[$value['item']][$PenjaminValue['uid']])) {
                        $data_populator[$value['item']][$PenjaminValue['uid']] = 0;
                    }
                }
            }

            $data_populator[$value['item']][$value['penjamin']] += $value['qty'];
        }





        $data_parse = array();

        foreach ($data_populator as $key => $value) {
            $total = 0;
            $rowParse = array(
                'autonum' => $autonum,
                'obat' => $Inventori->get_item_detail($key)['response_data'][0],
                'total' => 0
            );

            foreach ($value as $itemKey => $itemValue) {
                $rowParse[$itemKey] = $itemValue;
                $total+=$itemValue;
            }

            /*array_push($data_parse, array(
                'autonum' => $autonum,
                'obat' => $Inventori->get_item_detail($key)['response_data'][0],
                'penjamin' => $value
            ));*/
            $rowParse['total'] = '<h6 class=\'number_style\'>' . $total . '</h6>';
            array_push($data_parse, $rowParse);

            $autonum++;
        }

        $data['response_data'] = array_slice($data_parse, intval($parameter['start']), intval($parameter['length']));
        $data['recordsTotal'] = count($data_parse);
        $data['recordsFiltered'] = count($data_parse);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);
        return $data;
    }




    /** ======================================================================================
     *                                  - New Report Function - 
     *  ======================================================================================
     * by@devAg
     */ 

    private function report_laboratorium($parameter){
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if ($UserData['data']->jabatan === __UIDDOKTER__) {
            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                if ($parameter['mode'] == 'history') {
                $paramData = array(
                    // 'lab_order.dr_penanggung_jawab' => '= ?',
                    // 'AND',
                    'lab_order.deleted_at' => 'IS NULL',
                    'AND',
                    'lab_order.created_at' => 'BETWEEN ? AND ?',
                    'AND',
                    'lab_order.status' => '= ?'
                );
                //$paramValue = array($UserData['data']->uid, $parameter['from'], $parameter['to'], $parameter['status']);
                $paramValue = array($parameter['from'], $parameter['to'], $parameter['status']);
                } else {
                $paramData = array(
                    // 'lab_order.dr_penanggung_jawab' => '= ?',
                    // 'AND',
                    'lab_order.deleted_at' => 'IS NULL',
                    'AND',
                    'lab_order.status' => '= ?'
                );
                //$paramValue = array($UserData['data']->uid, $parameter['status']);
                $paramValue = array($parameter['status']);
                }
            } else {
                if ($parameter['mode'] == 'history') {
                $paramData = array(
                    // 'lab_order.dr_penanggung_jawab' => '= ?',
                    // 'AND',
                    'lab_order.deleted_at' => 'IS NULL',
                    'AND',
                    '(lab_order.no_order' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                    'AND',
                    'lab_order.created_at' => 'BETWEEN ? AND ?',
                    'AND',
                    'lab_order.status' => '= ?'
                );
                // $paramValue = array($UserData['data']->uid, $parameter['from'], $parameter['to'], $parameter['status']);
                $paramValue = array($parameter['from'], $parameter['to'], $parameter['status']);
                } else {
                $paramData = array(
                    // 'lab_order.dr_penanggung_jawab' => '= ?',
                    // 'AND',
                    'lab_order.deleted_at' => 'IS NULL',
                    'AND',
                    '(lab_order.no_order' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                    'AND',
                    'lab_order.status' => '= ?'
                );
                // $paramValue = array($UserData['data']->uid, $parameter['status']);
                $paramValue = array($parameter['status']);
                }
            }
        } else { //Jika Bukan Dokter
            if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                if ($parameter['mode'] == 'history') {
                $paramData = array(
                    'lab_order.deleted_at' => 'IS NULL',
                    'AND',
                    'lab_order.created_at' => 'BETWEEN ? AND ?',
                    'AND',
                    'lab_order.status' => '= ?'
                );
                $paramValue = array($parameter['from'], $parameter['to'], $parameter['status']);
                } else {
                $paramData = array(
                    'lab_order.deleted_at' => 'IS NULL',
                    'AND',
                    'lab_order.status' => '= ?'
                );
                $paramValue = array($parameter['status']);
                }
            } else {
                if ($parameter['mode'] == 'history') {
                $paramData = array(
                    'lab_order.deleted_at' => 'IS NULL',
                    'AND',
                    '(lab_order.no_order' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                    'AND',
                    'lab_order.created_at' => 'BETWEEN ? AND ?',
                    'AND',
                    'lab_order.status' => '= ?'
                );
                $paramValue = array($parameter['from'], $parameter['to'], $parameter['status']);
                } else {
                $paramData = array(
                    'lab_order.deleted_at' => 'IS NULL',
                    'AND',
                    '(lab_order.no_order' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                    'AND',
                    'lab_order.status' => '= ?'
                );
                $paramValue = array($parameter['status']);
                }
            }
        }

        if ($parameter['length'] < 0) {
        $data = self::$query
            ->select(
            'lab_order',
            array(
                'uid',
                'asesmen as uid_asesmen',
                'waktu_order',
                'no_order'
            )
            )
            ->join(
            'asesmen',
            array(
                'antrian as uid_antrian'
            )
            )
            ->join(
            'antrian',
            array(
                'pasien as uid_pasien',
                'dokter as uid_dokter',
                'departemen as uid_poli',
                'penjamin as uid_penjamin',
                'waktu_masuk'
            )
            )
            ->join(
            'pasien',
            array(
                'nama as pasien',
                'no_rm'
            )
            )
            ->join(
            'master_poli',
            array(
                'nama as departemen'
            )
            )
            ->join(
            'pegawai',
            array(
                'nama as dokter'
            )
            )
            ->join(
            'master_penjamin',
            array(
                'nama as penjamin'
            )
            )
            ->join(
            'kunjungan',
            array(
                'pegawai as uid_resepsionis'
            )
            )
            ->join(
            'lab_order_detail',
            array(
                'tindakan as uid_tindakan'
            )
            )
            ->join(
            'master_tindakan',
            array(
                'nama as nama_tindakan'
            )
            )
            ->on(
            array(
                array('lab_order.asesmen', '=', 'asesmen.uid'),
                array('asesmen.antrian', '=', 'antrian.uid'),
                array('pasien.uid', '=', 'antrian.pasien'),
                array('master_poli.uid', '=', 'antrian.departemen'),
                array('pegawai.uid', '=', 'antrian.dokter'),
                array('master_penjamin.uid', '=', 'antrian.penjamin'),
                array('kunjungan.uid', '=', 'antrian.kunjungan'),
                array('lab_order_detail.lab_order', '=', 'lab_order.uid'),
                array('master_tindakan.uid', '=', 'lab_order_detail.tindakan'),
            )
            )
            ->where($paramData, $paramValue)
            ->order(
            array(
                'lab_order.waktu_order' => 'ASC'
            )
            )
            ->execute();
        } else {
        $data = self::$query
            ->select(
            'lab_order',
            array(
                'uid',
                'asesmen as uid_asesmen',
                'waktu_order',
                'no_order'
            )
            )
            ->join(
            'asesmen',
            array(
                'antrian as uid_antrian'
            )
            )
            ->join(
            'antrian',
            array(
                'pasien as uid_pasien',
                'dokter as uid_dokter',
                'departemen as uid_poli',
                'penjamin as uid_penjamin',
                'waktu_masuk'
            )
            )
            ->join(
            'pasien',
            array(
                'nama as pasien',
                'no_rm'
            )
            )
            ->join(
            'master_poli',
            array(
                'nama as departemen'
            )
            )
            ->join(
            'pegawai',
            array(
                'nama as dokter'
            )
            )
            ->join(
            'master_penjamin',
            array(
                'nama as nama_penjamin'
            )
            )
            ->join(
            'kunjungan',
            array(
                'pegawai as uid_resepsionis'
            )
            )
            ->join(
            'lab_order_detail',
            array(
                'tindakan as uid_tindakan',
                'mitra',
                'penjamin',
            )
            )
            ->join(
            'master_tindakan',
            array(
                'nama as nama_tindakan'
            )
            )
            ->join(
            'master_mitra',
            array(
                'nama as nama_mitra'
            )
            )
            ->on(
            array(
                array('lab_order.asesmen', '=', 'asesmen.uid'),
                array('asesmen.antrian', '=', 'antrian.uid'),
                array('pasien.uid', '=', 'antrian.pasien'),
                array('master_poli.uid', '=', 'antrian.departemen'),
                array('pegawai.uid', '=', 'antrian.dokter'),
                array('master_penjamin.uid', '=', 'antrian.penjamin'),
                array('kunjungan.uid', '=', 'antrian.kunjungan'),
                array('lab_order_detail.lab_order', '=', 'lab_order.uid'),
                array('master_tindakan.uid', '=', 'lab_order_detail.tindakan'),
                array('master_mitra.uid', '=', 'lab_order_detail.mitra'),

            )
            )
            ->where($paramData, $paramValue)
            ->offset(intval($parameter['start']))
            ->limit(intval($parameter['length']))
            ->order(
            array(
                'lab_order.waktu_order' => 'ASC'
            )
            )
            ->execute();
        }



        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
        $hasNilai = false;
        //Check Nilai
        $checkNilai = self::$query->select('lab_order_nilai', array(
            'id', 'nilai'
        ))
            ->where(array(
            'lab_order_nilai.lab_order' => '= ?'
            ), array(
            $value['uid']
            ))
            ->execute();
        foreach ($checkNilai['response_data'] as $NKey => $NValue) {
            if (!is_null($NValue['nilai'])) {
            $hasNilai = true;
            } else {
            if (!$hasNilai) {
                $hasNilai = false;
            }
            }
        }
        $data['response_data'][$key]['autonum'] = $autonum;
        $data['response_data'][$key]['has_nilai'] = $hasNilai;
        //$data['response_data'][$key]['tgl_ambil_sample_parse'] = date('d F Y', strtotime($value['tgl_ambil_sample']));
        $data['response_data'][$key]['waktu_order'] = date('d F Y', strtotime($value['waktu_order'])) . ' - [' . date('H:i', strtotime($value['waktu_order'])) . ']';

        //Check Detail

        $autonum++;
        }

        $itemTotal = self::$query
        ->select(
            'lab_order',
            array(
            'uid',
            'asesmen as uid_asesmen',
            'waktu_order',
            'no_order'
            )
        )
        ->join(
            'asesmen',
            array(
            'antrian as uid_antrian'
            )
        )
        ->join(
            'antrian',
            array(
            'pasien as uid_pasien',
            'dokter as uid_dokter',
            'departemen as uid_poli',
            'penjamin as uid_penjamin',
            'waktu_masuk'
            )
        )
        ->join(
            'pasien',
            array(
            'nama as pasien',
            'no_rm'
            )
        )
        ->join(
            'master_poli',
            array(
            'nama as departemen'
            )
        )
        ->join(
            'pegawai',
            array(
            'nama as dokter'
            )
        )
        ->join(
            'master_penjamin',
            array(
            'nama as penjamin'
            )
        )
        ->join(
            'kunjungan',
            array(
            'pegawai as uid_resepsionis'
            )
        )
        ->on(
            array(
            array('lab_order.asesmen', '=', 'asesmen.uid'),
            array('asesmen.antrian', '=', 'antrian.uid'),
            array('pasien.uid', '=', 'antrian.pasien'),
            array('master_poli.uid', '=', 'antrian.departemen'),
            array('pegawai.uid', '=', 'antrian.dokter'),
            array('master_penjamin.uid', '=', 'antrian.penjamin'),
            array('kunjungan.uid', '=', 'antrian.kunjungan')
            )
        )
        ->where($paramData, $paramValue)
        ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($data['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);
        return $data;
    }

    // ====================================== Code End =======================================


    private function report_radiologi($parameter){
        if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'rad_order.deleted_at' => 'IS NULL',
                'AND',
                '(rad_order.no_order' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                'AND',
                'rad_order.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'rad_order.status' => '= ?'
            );
            $paramValue = array($parameter['from'], $parameter['to'], $parameter['status']);
            
        } else {
            $paramData = array(
                'rad_order.deleted_at' => 'IS NULL',
                'AND',  
                'rad_order.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'rad_order.status' => '= ?'
            );
            $paramValue = array($parameter['from'], $parameter['to'], $parameter['status']);
            
        }

        if ($parameter['length'] < 0) {
            $data = self::$query
                ->select(
                'rad_order',
                array(
                    'uid',
                    'asesmen as uid_asesmen',
                    'waktu_order',
                    'no_order'
                )
                )
                ->join(
                'asesmen',
                array(
                    'antrian as uid_antrian'
                )
                )
                ->join(
                'antrian',
                array(
                    'pasien as uid_pasien',
                    'dokter as uid_dokter',
                    'departemen as uid_poli',
                    'penjamin as uid_penjamin',
                    'waktu_masuk'
                )
                )
                ->join(
                'pasien',
                array(
                    'nama as pasien',
                    'no_rm'
                )
                )
                ->join(
                'master_poli',
                array(
                    'nama as departemen'
                )
                )
                ->join(
                'pegawai',
                array(
                    'nama as dokter'
                )
                )
                ->join(
                'master_penjamin',
                array(
                    'nama as penjamin'
                )
                )
                ->join(
                'kunjungan',
                array(
                    'pegawai as uid_resepsionis'
                )
                )
                ->join(
                'rad_order_detail',
                array(
                    'tindakan as uid_tindakan'
                )
                )
                ->join(
                'master_tindakan',
                array(
                    'nama as nama_tindakan'
                )
                )
                ->on(
                array(
                    array('rad_order.asesmen', '=', 'asesmen.uid'),
                    array('asesmen.antrian', '=', 'antrian.uid'),
                    array('pasien.uid', '=', 'antrian.pasien'),
                    array('master_poli.uid', '=', 'antrian.departemen'),
                    array('pegawai.uid', '=', 'antrian.dokter'),
                    array('master_penjamin.uid', '=', 'antrian.penjamin'),
                    array('kunjungan.uid', '=', 'antrian.kunjungan'),
                    array('rad_order_detail.radiologi_order', '=', 'rad_order.uid'),
                    array('master_tindakan.uid', '=', 'rad_order_detail.tindakan'),
                )
                )
                ->where($paramData, $paramValue)
                ->order(
                array(
                    'rad_order.waktu_order' => 'ASC'
                )
                )
                ->execute();
            } else {
            $data = self::$query
                ->select(
                'rad_order',
                array(
                    'uid',
                    'asesmen as uid_asesmen',
                    'waktu_order',
                    'no_order'
                )
                )
                ->join(
                'asesmen',
                array(
                    'antrian as uid_antrian'
                )
                )
                ->join(
                'antrian',
                array(
                    'pasien as uid_pasien',
                    'dokter as uid_dokter',
                    'departemen as uid_poli',
                    'penjamin as uid_penjamin',
                    'waktu_masuk'
                )
                )
                ->join(
                'pasien',
                array(
                    'nama as pasien',
                    'no_rm'
                )
                )
                ->join(
                'master_poli',
                array(
                    'nama as departemen'
                )
                )
                ->join(
                'pegawai',
                array(
                    'nama as dokter'
                )
                )
                ->join(
                'master_penjamin',
                array(
                    'nama as nama_penjamin'
                )
                )
                ->join(
                'kunjungan',
                array(
                    'pegawai as uid_resepsionis'
                )
                )
                ->join(
                'rad_order_detail',
                array(
                    'tindakan as uid_tindakan',
                    'mitra',
                    'penjamin',
                )
                )
                ->join(
                'master_tindakan',
                array(
                    'nama as nama_tindakan'
                )
                )
                ->join(
                'master_mitra',
                array(
                    'nama as nama_mitra'
                )
                )
                ->on(
                array(
                    array('rad_order.asesmen', '=', 'asesmen.uid'),
                    array('asesmen.antrian', '=', 'antrian.uid'),
                    array('pasien.uid', '=', 'antrian.pasien'),
                    array('master_poli.uid', '=', 'antrian.departemen'),
                    array('pegawai.uid', '=', 'antrian.dokter'),
                    array('master_penjamin.uid', '=', 'antrian.penjamin'),
                    array('kunjungan.uid', '=', 'antrian.kunjungan'),
                    array('rad_order_detail.radiologi_order', '=', 'rad_order.uid'),
                    array('master_tindakan.uid', '=', 'rad_order_detail.tindakan'),
                    array('master_mitra.uid', '=', 'rad_order_detail.mitra'),
    
                )
                )
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->order(
                array(
                    'rad_order.waktu_order' => 'ASC'
                )
                )
                ->execute();
            }

        

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['waktu_order'] = date('d F Y', strtotime($value['waktu_order'])) . ' - [' . date('H:i', strtotime($value['waktu_order'])) . ']';
    
            $autonum++;
        }
        $itemTotal = self::$query
        ->select(
            'rad_order',
            array(
            'uid',
            'asesmen as uid_asesmen',
            'waktu_order',
            'no_order'
            )
        )
        ->join(
            'asesmen',
            array(
            'antrian as uid_antrian'
            )
        )
        ->join(
            'antrian',
            array(
            'pasien as uid_pasien',
            'dokter as uid_dokter',
            'departemen as uid_poli',
            'penjamin as uid_penjamin',
            'waktu_masuk'
            )
        )
        ->join(
            'pasien',
            array(
            'nama as pasien',
            'no_rm'
            )
        )
        ->join(
            'master_poli',
            array(
            'nama as departemen'
            )
        )
        ->join(
            'pegawai',
            array(
            'nama as dokter'
            )
        )
        ->join(
            'master_penjamin',
            array(
            'nama as penjamin'
            )
        )
        ->join(
            'kunjungan',
            array(
            'pegawai as uid_resepsionis'
            )
        )
        ->on(
            array(
            array('rad_order.asesmen', '=', 'asesmen.uid'),
            array('asesmen.antrian', '=', 'antrian.uid'),
            array('pasien.uid', '=', 'antrian.pasien'),
            array('master_poli.uid', '=', 'antrian.departemen'),
            array('pegawai.uid', '=', 'antrian.dokter'),
            array('master_penjamin.uid', '=', 'antrian.penjamin'),
            array('kunjungan.uid', '=', 'antrian.kunjungan')
            )
        )
        ->where($paramData, $paramValue)
        ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($data['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);
        return $data;
    }


    private function report_farmasi($parameter){
        if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'invoice_detail.deleted_at' => 'IS NULL',
                'AND',
                '(pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                'AND',
                'invoice_detail.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'invoice_detail.billing_group' => '= ?'
            );
            $paramValue = array($parameter['from'], $parameter['to'], 'obat');
            
        } else {
            $paramData = array(
                'invoice_detail.deleted_at' => 'IS NULL',
                'AND',  
                'invoice_detail.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'invoice_detail.billing_group' => '= ?'
            );
            $paramValue = array($parameter['from'], $parameter['to'],'obat');
            
        }

        if ($parameter['length'] < 0) {
            $data = self::$query
                ->select(
                'invoice',
                array(
                    'uid',
                    'pasien',
                    'nomor_invoice'
                )
                )
                ->join(
                'invoice_detail',
                array(
                    'penjamin',
                    'billing_group',
                    'departemen',
                    'created_at',
                    'qty',
                    'subtotal'
                )
                )
                ->join(
                'invoice_payment',
                array(
                    'terbayar',
                    'sisa_bayar'
                )
                )
                ->join(
                'pasien',
                array(
                    'nama as pasien',
                    'no_rm'
                )
                )
                ->join(
                    'master_penjamin',
                    array(
                    'nama as penjamin'
                    )
                )
                ->join(
                'master_poli',
                array(
                    'nama as departemen'
                )
                )
                ->join(
                'master_inv',
                array(
                    'nama as item'
                )
                )
               ->on(
                array(
                    array('invoice_detail.invoice', '=', 'invoice.uid'),
                    array('invoice_payment.invoice', '=', 'invoice.uid'),
                    array('pasien.uid', '=', 'invoice.pasien'),
                    array('invoice_detail.penjamin', '=', 'master_penjamin.uid'),
                    array('invoice_detail.departemen', '=', 'master_poli.uid'),
                    array('invoice_detail.item', '=', 'master_inv.uid')
                )
                )
                ->where($paramData, $paramValue)
                ->execute();
            } else {
                $data = self::$query
                ->select(
                'invoice',
                array(
                    'uid',
                    'pasien',
                    'nomor_invoice'
                )
                )
                ->join(
                'invoice_detail',
                array(
                    'penjamin',
                    'billing_group',
                    'departemen',
                    'created_at',
                    'qty',
                    'subtotal'
                )
                )
                ->join(
                'invoice_payment',
                array(
                    'terbayar',
                    'sisa_bayar'
                )
                )
                ->join(
                'pasien',
                array(
                    'nama as pasien',
                    'no_rm'
                )
                )
                ->join(
                    'master_penjamin',
                    array(
                    'nama as penjamin'
                    )
                )
                ->join(
                'master_poli',
                array(
                    'nama as departemen'
                )
                )
                ->join(
                'master_inv',
                array(
                    'nama as item'
                )
                )
               ->on(
                array(
                    array('invoice_detail.invoice', '=', 'invoice.uid'),
                    array('invoice_payment.invoice', '=', 'invoice.uid'),
                    array('pasien.uid', '=', 'invoice.pasien'),
                    array('invoice_detail.penjamin', '=', 'master_penjamin.uid'),
                    array('invoice_detail.departemen', '=', 'master_poli.uid'),
                    array('invoice_detail.item', '=', 'master_inv.uid')
                )
                )
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
            }

        

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['created_at'] = date('d F Y', strtotime($value['created_at'])) . ' - [' . date('H:i', strtotime($value['created_at'])) . ']';
            $data['response_data'][$key]['tanggal'] = date('d F Y', strtotime($value['created_at']));
            $autonum++;
        }

        $itemTotal = self::$query
        ->select(
        'invoice',
        array(
            'uid',
            'pasien',
            'nomor_invoice'
        )
        )
        ->join(
        'invoice_detail',
        array(
            'penjamin',
            'billing_group',
            'departemen',
            'created_at',
            'qty',
            'subtotal'
        )
        )
        ->join(
        'invoice_payment',
        array(
            'terbayar',
            'sisa_bayar'
        )
        )
        ->join(
        'pasien',
        array(
            'nama as pasien',
            'no_rm'
        )
        )
        ->join(
            'master_penjamin',
            array(
            'nama as penjamin'
            )
        )
        ->join(
        'master_poli',
        array(
            'nama as departemen'
        )
        )
        ->join(
        'master_inv',
        array(
            'nama as item'
        )
        )
       ->on(
        array(
            array('invoice_detail.invoice', '=', 'invoice.uid'),
            array('invoice_payment.invoice', '=', 'invoice.uid'),
            array('pasien.uid', '=', 'invoice.pasien'),
            array('invoice_detail.penjamin', '=', 'master_penjamin.uid'),
            array('invoice_detail.departemen', '=', 'master_poli.uid'),
            array('invoice_detail.item', '=', 'master_inv.uid')
        )
        )
        ->where($paramData, $paramValue)
        ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);
        return $data;
    }


    private function report_farmasi_item($parameter){

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'master_inv.deleted_at' => 'IS NULL',
                'AND',
                'master_inv.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'AND',  
                'master_inv.kategori' => '= ?'
            );
            $paramValue = array(__UID_KATEGORI_OBAT);
        } else {
            $paramData = array(
                'master_inv.deleted_at' => 'IS NULL',
                'AND',  
                'master_inv.kategori' => '= ?'
            );
            $paramValue = array(__UID_KATEGORI_OBAT);
            
            
        }

        if ($parameter['length'] < 0) {
            $data = self::$query
                ->select(
                'master_inv',
                array(
                    'uid',
                    'nama',
                )
                )
                ->join(
                    'master_inv_satuan',
                    array(
                        'nama as nama_satuan'
                    )
                )
                ->on(
                    array(
                        array('master_inv.satuan_terkecil', '=', 'master_inv_satuan.uid'),
                    )
                )
                ->where($paramData, $paramValue)
                ->execute();
            } else {
                $data = self::$query
                ->select(
                'master_inv',
                array(
                    'uid',
                    'nama',
                )
                )
                ->join(
                    'master_inv_satuan',
                    array(
                        'nama as nama_satuan'
                    )
                )
                ->on(
                    array(
                        array('master_inv.satuan_terkecil', '=', 'master_inv_satuan.uid'),
                    )
                )
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
            }

        

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        //$KategoriObat = new Inventori(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            

            $KategoriObat = self::$query
                    ->select('master_inv_obat_kategori_item')
                    ->join(
                        'master_inv_obat_kategori',
                        array(
                            'nama as kategori'
                        )
                    )
                    ->on(
                        array(
                            array('master_inv_obat_kategori_item.kategori', '=', 'master_inv_obat_kategori.uid'),
                        )
                    )
                    ->where(
                        array(
                            'master_inv_obat_kategori_item.obat' => '= ?'
                        ),
                        array(
                            $value['uid']
                        ))
                    ->execute();

            //KATEGORI OBAT
            
            
            $arrKategoriObat = array();
            foreach($KategoriObat['response_data'] as $ItemObat){
                array_push($arrKategoriObat, $ItemObat['kategori']);
            }

            if(in_array('FORNAS', $arrKategoriObat)){
                $data['response_data'][$key]['kategori']['fornas'] = 'FORNAS';
            }else {
                $data['response_data'][$key]['kategori']['fornas'] = 'NON-FORNAS';
            }

            if(in_array("NARKOTIKA", $arrKategoriObat)){
                $data['response_data'][$key]['kategori']['narkotika'] = "NARKOTIKA";
            }else {
                $data['response_data'][$key]['kategori']['narkotika'] = "NON-NARKOTIKA";
            }

            if(in_array("PSIKOTROPIKA", $arrKategoriObat)){
                $data['response_data'][$key]['kategori']['psikotropika'] = "PSIKOTROPIKA";
            }else {
                $data['response_data'][$key]['kategori']['psikotropika'] = "NON-PSIKOTROPIKA";
            }

            if(in_array('GENERIK', $arrKategoriObat)){
                $data['response_data'][$key]['kategori']['generik'] = 'GENERIK';
            }else {
                $data['response_data'][$key]['kategori']['generik'] = 'NON-GENERIK';
            }
            
            if(in_array("ANTIBIOTIK", $arrKategoriObat)){
                $data['response_data'][$key]['kategori']['antibiotik'] = "ANTIBIOTIK";
            }else {
                $data['response_data'][$key]['kategori']['antibiotik'] = "NON-ANTIBIOTIK";
            }
            
            $data['response_data'][$key]['debug'] = $KategoriObat['response_data'];
            
            $autonum++;
        }

        $itemTotal = self::$query
                ->select(
                'master_inv',
                array(
                    'uid',
                    'nama',
                )
                )
                ->join(
                    'master_inv_satuan',
                    array(
                        'nama as nama_satuan'
                    )
                )
                ->on(
                    array(
                        array('master_inv.satuan_terkecil', '=', 'master_inv_satuan.uid'),
                    )
                )
                ->where($paramData, $paramValue)
                ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);
        return $data;
    }

    //PRINT 
    private function print_report_farmasi_item($parameter){

        $paramData = array(
            'master_inv.deleted_at' => 'IS NULL',
            'AND',  
            'master_inv.kategori' => '= ?'
        );
        $paramValue = array(__UID_KATEGORI_OBAT);
        

        $data = self::$query
            ->select(
            'master_inv',
            array(
                'uid',
                'nama',
            )
            )
            ->join(
                'master_inv_satuan',
                array(
                    'nama as nama_satuan'
                )
            )
            ->on(
                array(
                    array('master_inv.satuan_terkecil', '=', 'master_inv_satuan.uid'),
                )
            )
            ->where($paramData, $paramValue)
            ->execute();
           
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            

            $KategoriObat = self::$query
                    ->select('master_inv_obat_kategori_item')
                    ->join(
                        'master_inv_obat_kategori',
                        array(
                            'nama as kategori'
                        )
                    )
                    ->on(
                        array(
                            array('master_inv_obat_kategori_item.kategori', '=', 'master_inv_obat_kategori.uid'),
                        )
                    )
                    ->where(
                        array(
                            'master_inv_obat_kategori_item.obat' => '= ?'
                        ),
                        array(
                            $value['uid']
                        ))
                    ->execute();

            //KATEGORI OBAT
            
            
            $arrKategoriObat = array();
            foreach($KategoriObat['response_data'] as $ItemObat){
                array_push($arrKategoriObat, $ItemObat['kategori']);
            }

            if(in_array('FORNAS', $arrKategoriObat)){
                $data['response_data'][$key]['kategori']['fornas'] = 'FORNAS';
            }else {
                $data['response_data'][$key]['kategori']['fornas'] = 'NON-FORNAS';
            }

            if(in_array("NARKOTIKA", $arrKategoriObat)){
                $data['response_data'][$key]['kategori']['narkotika'] = "NARKOTIKA";
            }else {
                $data['response_data'][$key]['kategori']['narkotika'] = "NON-NARKOTIKA";
            }

            if(in_array("PSIKOTROPIKA", $arrKategoriObat)){
                $data['response_data'][$key]['kategori']['psikotropika'] = "PSIKOTROPIKA";
            }else {
                $data['response_data'][$key]['kategori']['psikotropika'] = "NON-PSIKOTROPIKA";
            }

            if(in_array('GENERIK', $arrKategoriObat)){
                $data['response_data'][$key]['kategori']['generik'] = 'GENERIK';
            }else {
                $data['response_data'][$key]['kategori']['generik'] = 'NON-GENERIK';
            }
            
            if(in_array("ANTIBIOTIK", $arrKategoriObat)){
                $data['response_data'][$key]['kategori']['antibiotik'] = "ANTIBIOTIK";
            }else {
                $data['response_data'][$key]['kategori']['antibiotik'] = "NON-ANTIBIOTIK";
            }
            
            $data['response_data'][$key]['debug'] = $KategoriObat['response_data'];
            
            $autonum++;
        }

        return $data;
    }

    public function report_farmasi_resep($parameter){
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'resep.deleted_at' => 'IS NULL',
                'AND',
                '(pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                'AND',
                'resep.created_at' => 'BETWEEN ? AND ?',
            );
            $paramValue = array($parameter['from'], $parameter['to']);
            
        } else {
            $paramData = array(
                'resep.deleted_at' => 'IS NULL',
                'AND',  
                'resep.created_at' => 'BETWEEN ? AND ?',
            );
            $paramValue = array($parameter['from'], $parameter['to']);
            
        }


        if ($parameter['length'] < 0) {
            $data = self::$query
                    ->select('resep',
                        array(
                            'uid',
                            'kode',
                            'kunjungan',
                            'antrian',
                            'asesmen',
                            'dokter',
                            'apoteker',
                            'pasien',
                            'total',
                            'alergi_obat',
                            'status_resep',
                            'keterangan',
                            'keterangan_racikan',
                            'created_at',
                            'updated_at',
                            'alasan_ubah'
                        )
                    )
                    ->join(
                        'pasien',
                        array(
                            'no_rm',
                            'nama as nama_pasien',
                        )
                    )
                    ->join(
                        'antrian',
                        array(
                            'penjamin',
                            'departemen'
                        )
                    )
                    ->join(
                        'master_penjamin',
                        array(
                            'nama as nama_penjamin'
                        )
                    )
                    ->join(
                        'master_poli',
                        array(
                            'nama as nama_departemen'
                        )
                    )
                    ->on(
                        array(
                            array('resep.pasien', '=', 'pasien.uid'),
                            array('resep.antrian', '=', 'antrian.uid'),
                            array('antrian.penjamin', '=', 'master_penjamin.uid'),
                            array('antrian.departemen', '=', 'master_poli.uid'),
                        )
                    )
                    ->where($paramData,$paramValue)
                    ->execute();
            } else {
                $data = self::$query
                        ->select('resep',
                            array(
                                'uid',
                                'kode',
                                'kunjungan',
                                'antrian',
                                'asesmen',
                                'dokter',
                                'apoteker',
                                'pasien',
                                'total',
                                'alergi_obat',
                                'status_resep',
                                'keterangan',
                                'keterangan_racikan',
                                'created_at',
                                'updated_at',
                                'alasan_ubah'
                            )
                        )
                        ->join(
                            'pasien',
                            array(
                                'no_rm',
                                'nama as nama_pasien',
                            )
                        )
                        ->join(
                            'antrian',
                            array(
                                'penjamin',
                                'departemen'
                            )
                        )
                        ->join(
                            'master_penjamin',
                            array(
                                'nama as nama_penjamin'
                            )
                        )
                        ->join(
                            'master_poli',
                            array(
                                'nama as nama_departemen'
                            )
                        )
                        ->on(
                            array(
                                array('resep.pasien', '=', 'pasien.uid'),
                                array('resep.antrian', '=', 'antrian.uid'),
                                array('antrian.penjamin', '=', 'master_penjamin.uid'),
                                array('antrian.departemen', '=', 'master_poli.uid'),
                            )
                        )
                        ->where($paramData,$paramValue)
                        ->offset(intval($parameter['start']))
                        ->limit(intval($parameter['length']))
                        ->execute();
            }

            $pegawai = new Pegawai(self::$pdo);
            $pasien = new Pasien(self::$pdo);
            $ruangan = new Ruangan(self::$pdo);
            $Penjamin = new Penjamin(self::$pdo);
            $KamarOperasi = new KamarOperasi(self::$pdo);
            $data['response_draw'] = $parameter['draw'];
            $autonum = intval($parameter['start']) + 1;
            foreach ($data['response_data'] as $key => $value) {
                $data['response_data'][$key]['autonum'] = $autonum;

                if($value['departemen'] === __POLI_INAP__){
                    //RANAP INFO
                    $RawatInap = self::$query->select('rawat_inap', array(
                        'dokter',
                        'kamar',
                        'bed'
                    ))
                        ->join('pegawai', array(
                            'nama as dokter'
                        ))
                        ->join('master_unit_ruangan', array(
                            'nama as kamar'
                        ))
                        ->join('master_unit_bed', array(
                            'nama as bed'
                        ))
                        ->on(array(
                            array('rawat_inap.dokter', '=', 'pegawai.uid'),
                            array('rawat_inap.kamar', '=', 'master_unit_ruangan.uid'),
                            array('rawat_inap.bed', '=', 'master_unit_bed.uid'),
                        ))
                        ->where(array(
                            'rawat_inap.kunjungan' => '= ?',
                            'AND',
                            // 'rawat_inap.dokter' => '= ?',
                            // 'AND',
                            'rawat_inap.pasien' => '= ?'
                        ), array(
                            $value['kunjungan'],
                            //$value['dokter'],
                            $value['pasien']
                        ))
                        ->execute();
                    $data['response_data'][$key]['rawat_inap'] = $RawatInap['response_data'][0];
                }

                //RESEP
               
               
                $Resep = self::$pdo->query("SELECT SUM(subtotal) as subtotal, SUM(qty) as qty FROM resep JOIN invoice ON resep.kunjungan = invoice.kunjungan JOIN invoice_detail ON invoice.uid =invoice_detail.invoice WHERE resep.uid = '".$value['uid']."' AND invoice_detail.billing_group = 'obat'")->fetchAll(\PDO::FETCH_ASSOC); 

                $Resep[0]['subtotal'] = number_format($Resep[0]['subtotal'], 2, ".", ",");
                
                $data['response_data'][$key]['total'] = $Resep[0];
               
                $autonum++;
            }

            $itemTotal = self::$query
                    ->select('resep',
                        array(
                            'uid',
                            'kode',
                            'kunjungan',
                            'antrian',
                            'asesmen',
                            'dokter',
                            'apoteker',
                            'pasien',
                            'total',
                            'alergi_obat',
                            'status_resep',
                            'keterangan',
                            'keterangan_racikan',
                            'created_at',
                            'updated_at',
                            'alasan_ubah'
                        )
                    )
                    ->join(
                        'pasien',
                        array(
                            'no_rm',
                            'nama as nama_pasien',
                        )
                    )
                    ->join(
                        'antrian',
                        array(
                            'penjamin',
                            'departemen'
                        )
                    )
                    ->join(
                        'master_penjamin',
                        array(
                            'nama as nama_penjamin'
                        )
                    )
                    ->join(
                        'master_poli',
                        array(
                            'nama as nama_departemen'
                        )
                    )
                    ->on(
                        array(
                            array('resep.pasien', '=', 'pasien.uid'),
                            array('resep.antrian', '=', 'antrian.uid'),
                            array('antrian.penjamin', '=', 'master_penjamin.uid'),
                            array('antrian.departemen', '=', 'master_poli.uid'),
                        )
                    )
                    ->where($paramData,$paramValue)
                    ->execute();

            $data['recordsTotal'] = count($itemTotal['response_data']);
            $data['recordsFiltered'] = count($itemTotal['response_data']);
            $data['length'] = intval($parameter['length']);
            $data['start'] = intval($parameter['start']);
            return $data;
    }

    //KEPERLUAN PRINT
    public function print_report_farmasi_resep($parameter){
       
        $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',  
            'resep.created_at' => 'BETWEEN ? AND ?',
        );
        $paramValue = array($parameter['from'], $parameter['to']);
    
        $data = self::$query
                ->select('resep',
                    array(
                        'uid',
                        'kode',
                        'kunjungan',
                        'antrian',
                        'asesmen',
                        'dokter',
                        'apoteker',
                        'pasien',
                        'total',
                        'alergi_obat',
                        'status_resep',
                        'keterangan',
                        'keterangan_racikan',
                        'created_at',
                        'updated_at',
                        'alasan_ubah'
                    )
                )
                ->join(
                    'pasien',
                    array(
                        'no_rm',
                        'nama as nama_pasien',
                    )
                )
                ->join(
                    'antrian',
                    array(
                        'penjamin',
                        'departemen'
                    )
                )
                ->join(
                    'master_penjamin',
                    array(
                        'nama as nama_penjamin'
                    )
                )
                ->join(
                    'master_poli',
                    array(
                        'nama as nama_departemen'
                    )
                )
                ->on(
                    array(
                        array('resep.pasien', '=', 'pasien.uid'),
                        array('resep.antrian', '=', 'antrian.uid'),
                        array('antrian.penjamin', '=', 'master_penjamin.uid'),
                        array('antrian.departemen', '=', 'master_poli.uid'),
                    )
                )
                ->where($paramData,$paramValue)
                ->execute();
          

            $autonum = intval($parameter['start']) + 1;
            foreach ($data['response_data'] as $key => $value) {
                $data['response_data'][$key]['autonum'] = $autonum;

                if($value['departemen'] === __POLI_INAP__){
                    //RANAP INFO
                    $RawatInap = self::$query->select('rawat_inap', array(
                        'dokter',
                        'kamar',
                        'bed'
                    ))
                        ->join('pegawai', array(
                            'nama as dokter'
                        ))
                        ->join('master_unit_ruangan', array(
                            'nama as kamar'
                        ))
                        ->join('master_unit_bed', array(
                            'nama as bed'
                        ))
                        ->on(array(
                            array('rawat_inap.dokter', '=', 'pegawai.uid'),
                            array('rawat_inap.kamar', '=', 'master_unit_ruangan.uid'),
                            array('rawat_inap.bed', '=', 'master_unit_bed.uid'),
                        ))
                        ->where(array(
                            'rawat_inap.kunjungan' => '= ?',
                            'AND',
                            // 'rawat_inap.dokter' => '= ?',
                            // 'AND',
                            'rawat_inap.pasien' => '= ?'
                        ), array(
                            $value['kunjungan'],
                            //$value['dokter'],
                            $value['pasien']
                        ))
                        ->execute();
                    $data['response_data'][$key]['rawat_inap'] = $RawatInap['response_data'][0];
                }

                //RESEP
               
               
                $Resep = self::$pdo->query("SELECT SUM(subtotal) as subtotal, SUM(qty) as qty FROM resep JOIN invoice ON resep.kunjungan = invoice.kunjungan JOIN invoice_detail ON invoice.uid =invoice_detail.invoice WHERE resep.uid = '".$value['uid']."' AND invoice_detail.billing_group = 'obat'")->fetchAll(\PDO::FETCH_ASSOC); 

                $Resep[0]['subtotal'] = number_format($Resep[0]['subtotal'], 2, ".", ",");
                
                $data['response_data'][$key]['total'] = $Resep[0];
               
                $autonum++;
            }

            return $data;
    }

    public function report_farmasi_mutasi($parameter){
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'resep.deleted_at' => 'IS NULL',
                'AND',
                'master_inv.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'AND',
                'resep.created_at' => 'BETWEEN ? AND ?',
            );
            $paramValue = array($parameter['from'], $parameter['to']);
            
        } else {
            $paramData = array(
                'inventori_mutasi.deleted_at' => 'IS NULL',
                'AND',
                'master_inv.kategori' => '= ?',
                'AND',  
                'inventori_mutasi.created_at' => 'BETWEEN ? AND ?',
            );
            $paramValue = array(__UID_KATEGORI_OBAT,$parameter['from'], $parameter['to']);
            
        }


        if ($parameter['length'] < 0) {

            $data = self::$query->select('inventori_mutasi', array(
                'tanggal',
                'dari',
                'ke',
                'pegawai',
                'kode'
              ))
                ->join('inventori_mutasi_detail',array(
                    'item',
                    'qty',
                    'keterangan',
                    'created_at',
                    'updated_at'
                    )
                )
                ->join('master_inv',array(
                    'nama',
                    'kategori',
                    )
                )
                ->join('inventori_batch',array(
                    'uid as uid_batch',
                    'batch',
                    'po',
                    'expired_date'
                ))
                ->join('inventori_po',array(
                    'tanggal_po',
                ))
                ->on(
                    array(
                        array('inventori_mutasi.uid', '=', 'inventori_mutasi_detail.mutasi'),
                        array('master_inv.uid', '=', 'inventori_mutasi_detail.item'),
                        array('inventori_mutasi_detail.batch', '=', 'inventori_batch.uid'),
                        array('inventori_batch.po', '=', 'inventori_po.uid'),
                    )
                )
                ->where($paramData,$paramValue)
                ->execute();

           
        } else {
            $data = self::$query->select('inventori_mutasi', array(
                    'tanggal',
                    'dari',
                    'ke',
                    'pegawai',
                    'kode'
                    ))
                ->join('inventori_mutasi_detail',array(
                    'item',
                    'qty',
                    'keterangan',
                    'created_at',
                    'updated_at'
                    )
                )
                ->join('master_inv',array(
                    'nama',
                    'kategori',
                    )
                )
            ->join('inventori_batch',array(
                'uid as uid_batch',
                'batch',
                'po',
                'expired_date'
            ))
            ->join('inventori_po',array(
                'tanggal_po',
            ))
            ->on(
                array(
                    array('inventori_mutasi.uid', '=', 'inventori_mutasi_detail.mutasi'),
                    array('master_inv.uid', '=', 'inventori_mutasi_detail.item'),
                    array('inventori_mutasi_detail.batch', '=', 'inventori_batch.uid'),
                    array('inventori_batch.po', '=', 'inventori_po.uid'),
                )
            )
                ->where($paramData,$paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

            $pegawai = new Pegawai(self::$pdo);
            $pasien = new Pasien(self::$pdo);
            $ruangan = new Ruangan(self::$pdo);
            $Penjamin = new Penjamin(self::$pdo);
            $Inventori = new Inventori(self::$pdo);
            $data['response_draw'] = $parameter['draw'];
            $autonum = intval($parameter['start']) + 1;
            foreach ($data['response_data'] as $key => $value) {
                $data['response_data'][$key]['autonum'] = $autonum;
                $data['response_data'][$key]['tanggal_po'] = date('Y', strtotime($value['tanggal_po']));
                $data['response_data'][$key]['expired_date'] = date('d F Y', strtotime($value['expired_date']));
                $data['response_data'][$key]['item_detail'] = $Inventori->get_item_detail($value['item'])['response_data'][0];

                $KategoriObat = self::$query
                    ->select('master_inv_obat_kategori_item')
                    ->join(
                        'master_inv_obat_kategori',
                        array(
                            'nama as kategori'
                        )
                    )
                    ->on(
                        array(
                            array('master_inv_obat_kategori_item.kategori', '=', 'master_inv_obat_kategori.uid'),
                        )
                    )
                    ->where(
                        array(
                            'master_inv_obat_kategori_item.obat' => '= ?'
                        ),
                        array(
                            $value['item']
                        ))
                    ->execute();

                //KATEGORI OBAT
                
                
                $arrKategoriObat = array();
                foreach($KategoriObat['response_data'] as $ItemObat){
                    array_push($arrKategoriObat, $ItemObat['kategori']);
                }

                if(in_array("NARKOTIKA", $arrKategoriObat)){
                    $data['response_data'][$key]['kategori_obat']['narkotika'] = "NARKOTIKA";
                }else {
                    $data['response_data'][$key]['kategori_obat']['narkotika'] = "NON-NARKOTIKA";
                }

       

                if(in_array('GENERIK', $arrKategoriObat)){
                    $data['response_data'][$key]['kategori_obat']['generik'] = 'GENERIK';
                }else {
                    $data['response_data'][$key]['kategori_obat']['generik'] = 'NON-GENERIK';
                }
    
                
                $data['response_data'][$key]['debug'] = $KategoriObat['response_data'];


                $autonum++;
            }

            $itemTotal =    self::$query->select('inventori_mutasi', array(
                    'tanggal',
                    'dari',
                    'ke',
                    'pegawai',
                    'kode'
              ))
              ->join('inventori_mutasi_detail',array(
                    'item',
                    'qty',
                    'keterangan',
                    'created_at',
                    'updated_at'
                    )
                )
                ->join('master_inv',array(
                    'nama',
                    'kategori',
                    )
                )
                ->join('inventori_batch',array(
                    'batch',
                    'po',
                    'expired_date'
                ))
                ->join('inventori_po',array(
                    'tanggal_po',
                ))
                ->on(
                    array(
                        array('inventori_mutasi.uid', '=', 'inventori_mutasi_detail.mutasi'),
                        array('master_inv.uid', '=', 'inventori_mutasi_detail.item'),
                        array('inventori_mutasi_detail.batch', '=', 'inventori_batch.uid'),
                        array('inventori_batch.po', '=', 'inventori_po.uid'),
                    )
                )
                ->where($paramData,$paramValue)
                ->execute();

            $data['recordsTotal'] = count($itemTotal['response_data']);
            $data['recordsFiltered'] = count($itemTotal['response_data']);
            $data['length'] = intval($parameter['length']);
            $data['start'] = intval($parameter['start']);
            return $data;
    }

    public function print_report_farmasi_mutasi($parameter){
        
        $paramData = array(
            'inventori_mutasi.deleted_at' => 'IS NULL',
            'AND',
            'master_inv.kategori' => '= ?',
            'AND',  
            'inventori_mutasi.created_at' => 'BETWEEN ? AND ?',
        );
        $paramValue = array(__UID_KATEGORI_OBAT,$parameter['from'], $parameter['to']);
        
    



        $data = self::$query->select('inventori_mutasi', array(
            'tanggal',
            'dari',
            'ke',
            'pegawai',
            'kode'
            ))
            ->join('inventori_mutasi_detail',array(
                'item',
                'qty',
                'keterangan',
                'created_at',
                'updated_at'
                )
            )
            ->join('master_inv',array(
                'nama',
                'kategori',
                )
            )
            ->join('inventori_batch',array(
                'uid as uid_batch',
                'batch',
                'po',
                'expired_date'
            ))
            ->join('inventori_po',array(
                'tanggal_po',
            ))
            ->on(
                array(
                    array('inventori_mutasi.uid', '=', 'inventori_mutasi_detail.mutasi'),
                    array('master_inv.uid', '=', 'inventori_mutasi_detail.item'),
                    array('inventori_mutasi_detail.batch', '=', 'inventori_batch.uid'),
                    array('inventori_batch.po', '=', 'inventori_po.uid'),
                )
            )
            ->where($paramData,$paramValue)
            ->execute();

       

            $pegawai = new Pegawai(self::$pdo);
            $pasien = new Pasien(self::$pdo);
            $ruangan = new Ruangan(self::$pdo);
            $Penjamin = new Penjamin(self::$pdo);
            $Inventori = new Inventori(self::$pdo);
            $data['response_draw'] = $parameter['draw'];
            $autonum = intval($parameter['start']) + 1;
            foreach ($data['response_data'] as $key => $value) {
                $data['response_data'][$key]['autonum'] = $autonum;
                $data['response_data'][$key]['tanggal_po'] = date('Y', strtotime($value['tanggal_po']));
                $data['response_data'][$key]['expired_date'] = date('d F Y', strtotime($value['expired_date']));
                $data['response_data'][$key]['item_detail'] = $Inventori->get_item_detail($value['item'])['response_data'][0];

                $KategoriObat = self::$query
                    ->select('master_inv_obat_kategori_item')
                    ->join(
                        'master_inv_obat_kategori',
                        array(
                            'nama as kategori'
                        )
                    )
                    ->on(
                        array(
                            array('master_inv_obat_kategori_item.kategori', '=', 'master_inv_obat_kategori.uid'),
                        )
                    )
                    ->where(
                        array(
                            'master_inv_obat_kategori_item.obat' => '= ?'
                        ),
                        array(
                            $value['item']
                        ))
                    ->execute();

                //KATEGORI OBAT
                
                
                $arrKategoriObat = array();
                foreach($KategoriObat['response_data'] as $ItemObat){
                    array_push($arrKategoriObat, $ItemObat['kategori']);
                }

                if(in_array("NARKOTIKA", $arrKategoriObat)){
                    $data['response_data'][$key]['kategori_obat']['narkotika'] = "NARKOTIKA";
                }else {
                    $data['response_data'][$key]['kategori_obat']['narkotika'] = "NON-NARKOTIKA";
                }

       

                if(in_array('GENERIK', $arrKategoriObat)){
                    $data['response_data'][$key]['kategori_obat']['generik'] = 'GENERIK';
                }else {
                    $data['response_data'][$key]['kategori_obat']['generik'] = 'NON-GENERIK';
                }
    
                
                $data['response_data'][$key]['debug'] = $KategoriObat['response_data'];


                $autonum++;
            }

            return $data;
    }

    public function report_kamar_operasi($parameter){
        if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'kamar_operasi_jadwal.deleted_at' => 'IS NULL',
                'AND',
                '(pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                'AND',
                'kamar_operasi_jadwal.created_at' => 'BETWEEN ? AND ?',
            );
            $paramValue = array($parameter['from'], $parameter['to']);
            
        } else {
            $paramData = array(
                'kamar_operasi_jadwal.deleted_at' => 'IS NULL',
                'AND',  
                'kamar_operasi_jadwal.created_at' => 'BETWEEN ? AND ?',
            );
            $paramValue = array($parameter['from'], $parameter['to']);
            
        }

        if ($parameter['length'] < 0) {
            $data = self::$query
                    ->select('kamar_operasi_jadwal',
                        array(
                            'uid',
                            'kunjungan',
                            'penjamin',
                            'pasien as uid_pasien',
                            'ruang_operasi as uid_ruang_operasi',
                            'tgl_operasi',
                            'jam_mulai',
                            'jam_selesai',
                            'jenis_operasi as uid_jenis_operasi',
                            'operasi',
                            'dokter as uid_dokter',
                            'status_pelaksanaan'
                        )
                    )
                    ->where($paramData,$paramValue)
                    ->execute();
            } else {
                $data = self::$query
                        ->select('kamar_operasi_jadwal',
                            array(
                                'uid',
                                'kunjungan',
                                'penjamin',
                                'pasien as uid_pasien',
                                'ruang_operasi as uid_ruang_operasi',
                                'tgl_operasi',
                                'jam_mulai',
                                'jam_selesai',
                                'jenis_operasi as uid_jenis_operasi',
                                'operasi',
                                'dokter as uid_dokter',
                                'status_pelaksanaan'
                            )
                        )
                        ->where($paramData,$paramValue)
                        ->offset(intval($parameter['start']))
                        ->limit(intval($parameter['length']))
                        ->execute();
            }

            $pegawai = new Pegawai(self::$pdo);
            $pasien = new Pasien(self::$pdo);
            $ruangan = new Ruangan(self::$pdo);
            $Penjamin = new Penjamin(self::$pdo);
            $KamarOperasi = new KamarOperasi(self::$pdo);
            $data['response_draw'] = $parameter['draw'];
            $autonum = intval($parameter['start']) + 1;
            foreach ($data['response_data'] as $key => $value) {
                $data['response_data'][$key]['autonum'] = $autonum;
                $detail_dokter = $pegawai->get_detail($value['uid_dokter']);
                $data['response_data'][$key]['dokter'] = 
                    ($detail_dokter['response_result'] > 0) ? $detail_dokter['response_data'][0]['nama'] : "-";
    
                    $jenis_operasi = $KamarOperasi->get_jenis_operasi_detail($value['uid_jenis_operasi']);
                    $data['response_data'][$key]['jenis_operasi'] = 
                        ($jenis_operasi['response_result'] > 0) ? $jenis_operasi['response_data'][0]['nama'] : "-";
                    
                $detail_pasien = $pasien->get_pasien_detail('pasien', $value['uid_pasien']);
                $data['response_data'][$key]['pasien'] = ($detail_pasien['response_result'] > 0) ? $detail_pasien['response_data'][0] : "-";
                $detail_ruangan = $ruangan->get_ruangan_detail('master_unit_ruangan', $value['uid_ruang_operasi']);
                $data['response_data'][$key]['ruangan'] = ($detail_ruangan['response_result'] > 0) ? $detail_ruangan['response_data'][0]['nama'] : "-";
                $data['response_data'][$key]['penjamin'] = $Penjamin->get_penjamin_detail($value['penjamin'])['response_data'][0];
                $data['response_data'][$key]['tgl_operasi_parsed'] = date('d F Y', strtotime($value['tgl_operasi']));
                $autonum++;
            }

            $itemTotal = self::$query
            ->select('kamar_operasi_jadwal',
                array(
                    'uid',
                    'kunjungan',
                    'penjamin',
                    'pasien as uid_pasien',
                    'ruang_operasi as uid_ruang_operasi',
                    'tgl_operasi',
                    'jam_mulai',
                    'jam_selesai',
                    'jenis_operasi as uid_jenis_operasi',
                    'operasi',
                    'dokter as uid_dokter',
                    'status_pelaksanaan'
                )
            )
            ->where($paramData,$paramValue)
            ->execute();

            $data['recordsTotal'] = count($itemTotal['response_data']);
            $data['recordsFiltered'] = count($itemTotal['response_data']);
            $data['length'] = intval($parameter['length']);
            $data['start'] = intval($parameter['start']);
            return $data;
    }

    public function report_farmasi_stok_opname($parameter){
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'inventori_stok_opname.deleted_at' => 'IS NULL',
                'AND',
                'master_inv.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                // 'AND',
                // 'inventori_stok_opname_detail.created_at' => 'BETWEEN ? AND ?',
            );
            $paramValue = array($parameter['from'], $parameter['to']);
            
        } else {
            $paramData = array(
                'inventori_stok_opname.deleted_at' => 'IS NULL',
                // 'AND',  
                // 'inventori_stok_opname_detail.created_at' => 'BETWEEN ? AND ?',
            );
            $paramValue = array();
            
        }


        if ($parameter['length'] < 0) {

            $data = self::$query->select('inventori_stok_opname_detail', array(
                
                'item',
                'batch',
                'qty_awal',
                'qty_akhir',
                'keterangan',
                'created_at',
                'updated_at'
              ))
                ->join('inventori_stok_opname',array(
                    'dari',
                    'sampai',
                    'pegawai',
                    'created_at',
                    'updated_at'
                    )
                )
                ->join('pegawai',array(
                    'nama as nama_pegawai',
                    )
                )
                ->join('master_inv',array(
                    'nama',
                    'kategori',
                    )
                )
                ->join('inventori_batch',array(
                    'uid as uid_batch',
                    'batch',
                    'po',
                    'expired_date'
                ))
                ->on(
                    array(
                        array('inventori_stok_opname.uid', '=', 'inventori_stok_opname_detail.opname'),
                        array('inventori_stok_opname.pegawai', '=', 'pegawai.uid'),
                        array('inventori_stok_opname_detail.item', '=', 'master_inv.uid'),
                        array('inventori_stok_opname_detail.batch', '=', 'inventori_batch.uid'),
                 
                    )
                )
                ->where($paramData,$paramValue)
                ->execute();

           
        } else {
            $data = self::$query->select('inventori_stok_opname_detail', array(
                'item',
                'batch',
                'qty_awal',
                'qty_akhir',
                'keterangan',
                'created_at',
                'updated_at'
              ))
                ->join('inventori_stok_opname',array(
                    'dari',
                    'sampai',
                    'pegawai',
                    'created_at',
                    'updated_at'
                    )
                )
                ->join('pegawai',array(
                    'nama as nama_pegawai',
                    )
                )
                ->join('master_inv',array(
                    'nama',
                    'kategori',
                    )
                )
                ->join('inventori_batch',array(
                    'uid as uid_batch',
                    'batch',
                    'po',
                    'expired_date'
                ))
                ->on(
                    array(
                        array('inventori_stok_opname.uid', '=', 'inventori_stok_opname_detail.opname'),
                        array('inventori_stok_opname.pegawai', '=', 'pegawai.uid'),
                        array('inventori_stok_opname_detail.item', '=', 'master_inv.uid'),
                        array('inventori_stok_opname_detail.batch', '=', 'inventori_batch.uid'),
                 
                    )
                )
                ->where($paramData,$paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

            $pegawai = new Pegawai(self::$pdo);
            $pasien = new Pasien(self::$pdo);
            $ruangan = new Ruangan(self::$pdo);
            $Penjamin = new Penjamin(self::$pdo);
            $Inventori = new Inventori(self::$pdo);
            $data['response_draw'] = $parameter['draw'];
            $autonum = intval($parameter['start']) + 1;
            foreach ($data['response_data'] as $key => $value) {
                $data['response_data'][$key]['autonum'] = $autonum;
                $data['response_data'][$key]['dari'] = date('Y', strtotime($value['dari']));
                $data['response_data'][$key]['sampai'] = date('Y', strtotime($value['sampai']));
                $data['response_data'][$key]['item_detail'] = $Inventori->get_item_detail($value['item'])['response_data'][0];

                $autonum++;
            }

            $itemTotal = self::$query->select('inventori_stok_opname_detail', array(      
                        'item',
                        'batch',
                        'qty_awal',
                        'qty_akhir',
                        'keterangan',
                        'created_at',
                        'updated_at'
                    ))
                        ->join('inventori_stok_opname',array(
                            'dari',
                            'sampai',
                            'pegawai',
                            'created_at',
                            'updated_at'
                            )
                        )
                        ->join('pegawai',array(
                            'nama as nama_pegawai',
                            )
                        )
                        ->join('master_inv',array(
                            'nama',
                            'kategori',
                            )
                        )
                        ->join('inventori_batch',array(
                            'uid as uid_batch',
                            'batch',
                            'po',
                            'expired_date'
                        ))
                        ->on(
                            array(
                                array('inventori_stok_opname.uid', '=', 'inventori_stok_opname_detail.opname'),
                                array('inventori_stok_opname.pegawai', '=', 'pegawai.uid'),
                                array('inventori_stok_opname_detail.item', '=', 'master_inv.uid'),
                                array('inventori_stok_opname_detail.batch', '=', 'inventori_batch.uid'),
                        
                            )
                        )
                        ->where($paramData,$paramValue)
                        ->execute();

            $data['recordsTotal'] = count($itemTotal['response_data']);
            $data['recordsFiltered'] = count($itemTotal['response_data']);
            $data['length'] = intval($parameter['length']);
            $data['start'] = intval($parameter['start']);
            return $data;
    }

    public function print_report_farmasi_stok_opname($parameter){
       
        $paramData = array(
            'inventori_stok_opname.deleted_at' => 'IS NULL',
            // 'AND',  
            // 'inventori_stok_opname_detail.created_at' => 'BETWEEN ? AND ?',
        );
        $paramValue = array();
    

        $data = self::$query->select('inventori_stok_opname_detail', array(
            
            'item',
            'batch',
            'qty_awal',
            'qty_akhir',
            'keterangan',
            'created_at',
            'updated_at'
            ))
            ->join('inventori_stok_opname',array(
                'dari',
                'sampai',
                'pegawai',
                'created_at',
                'updated_at'
                )
            )
            ->join('pegawai',array(
                'nama as nama_pegawai',
                )
            )
            ->join('master_inv',array(
                'nama',
                'kategori',
                )
            )
            ->join('inventori_batch',array(
                'uid as uid_batch',
                'batch',
                'po',
                'expired_date'
            ))
            ->on(
                array(
                    array('inventori_stok_opname.uid', '=', 'inventori_stok_opname_detail.opname'),
                    array('inventori_stok_opname.pegawai', '=', 'pegawai.uid'),
                    array('inventori_stok_opname_detail.item', '=', 'master_inv.uid'),
                    array('inventori_stok_opname_detail.batch', '=', 'inventori_batch.uid'),
                
                )
            )
            // ->offset(1)
            // ->limit(10)
            ->where($paramData,$paramValue)
            ->execute();

       

            $pegawai = new Pegawai(self::$pdo);
            $pasien = new Pasien(self::$pdo);
            $ruangan = new Ruangan(self::$pdo);
            $Penjamin = new Penjamin(self::$pdo);
            $Inventori = new Inventori(self::$pdo);
            $data['response_draw'] = $parameter['draw'];
            $autonum = intval($parameter['start']) + 1;
            foreach ($data['response_data'] as $key => $value) {
                $data['response_data'][$key]['autonum'] = $autonum;
                $data['response_data'][$key]['dari'] = date('Y', strtotime($value['dari']));
                $data['response_data'][$key]['sampai'] = date('Y', strtotime($value['sampai']));
                $data['response_data'][$key]['item_detail'] = $Inventori->get_item_detail($value['item'])['response_data'][0];

                $autonum++;
            }

           
            return $data;
    }

    public function report_farmasi_retur($parameter){
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'inventori_return_detail.deleted_at' => 'IS NULL',
                'AND',
                'master_inv.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'AND',
                'inventori_return_detail.created_at' => 'BETWEEN ? AND ?',
            );
            $paramValue = array($parameter['from'], $parameter['to']);
            
        } else {
            $paramData = array(
                'inventori_return_detail.deleted_at' => 'IS NULL',
                'AND',  
                'inventori_return_detail.created_at' => 'BETWEEN ? AND ?',
            );
            $paramValue = array($parameter['from'], $parameter['to']);
            
        }


        if ($parameter['length'] < 0) {

            $data = self::$query->select('inventori_return_detail', array(
                
                'barang',
                'batch',
                'qty',
                'created_at',
                'updated_at'
              ))
                ->join('inventori_return',array(
                    'kode',
                    'pegawai',
                    'supplier',
                    'created_at',
                    'updated_at'
                    )
                )
                ->join('pegawai',array(
                    'nama as nama_pegawai',
                    )
                )
                ->join('master_inv',array(
                    'nama',
                    'kategori',
                    )
                )
                ->join('inventori_batch',array(
                    'uid as uid_batch',
                    'batch',
                    'po',
                    'expired_date'
                ))
                ->join('master_supplier',array(
                    'nama as nama_supplier',
                ))
                ->on(
                    array(
                        array('inventori_return_detail.inventori_return', '=', 'inventori_return.uid'),
                        array('inventori_return.pegawai', '=', 'pegawai.uid'),
                        array('inventori_return_detail.barang', '=', 'master_inv.uid'),
                        array('inventori_return_detail.batch', '=', 'inventori_batch.uid'),
                        array('inventori_return.supplier', '=', 'master_supplier.uid'),
                    )
                )
                ->where($paramData,$paramValue)
                ->execute();

           
        } else {
            $data = self::$query->select('inventori_return_detail', array(
                
                'barang',
                'batch',
                'qty',
                'created_at',
                'updated_at'
              ))
                ->join('inventori_return',array(
                    'kode',
                    'pegawai',
                    'supplier',
                    'created_at',
                    'updated_at'
                    )
                )
                ->join('pegawai',array(
                    'nama as nama_pegawai',
                    )
                )
                ->join('master_inv',array(
                    'nama',
                    'kategori',
                    )
                )
                ->join('inventori_batch',array(
                    'uid as uid_batch',
                    'batch',
                    'po',
                    'expired_date'
                ))
                ->join('master_supplier',array(
                    'nama as nama_supplier',
                ))
                ->on(
                    array(
                        array('inventori_return_detail.inventori_return', '=', 'inventori_return.uid'),
                        array('inventori_return.pegawai', '=', 'pegawai.uid'),
                        array('inventori_return_detail.barang', '=', 'master_inv.uid'),
                        array('inventori_return_detail.batch', '=', 'inventori_batch.uid'),
                        array('inventori_return.supplier', '=', 'master_supplier.uid'),
                 
                    )
                )
                ->where($paramData,$paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

            $pegawai = new Pegawai(self::$pdo);
            $pasien = new Pasien(self::$pdo);
            $ruangan = new Ruangan(self::$pdo);
            $Penjamin = new Penjamin(self::$pdo);
            $Inventori = new Inventori(self::$pdo);
            $data['response_draw'] = $parameter['draw'];
            $autonum = intval($parameter['start']) + 1;
            foreach ($data['response_data'] as $key => $value) {
                $data['response_data'][$key]['autonum'] = $autonum;
                $data['response_data'][$key]['item_detail'] = $Inventori->get_item_detail($value['barang'])['response_data'][0];

                $autonum++;
            }

            $itemTotal = self::$query->select('inventori_return_detail', array(
                
                        'barang',
                        'batch',
                        'qty',
                        'created_at',
                        'updated_at'
                    ))
                        ->join('inventori_return',array(
                            'kode',
                            'pegawai',
                            'supplier',
                            'created_at',
                            'updated_at'
                            )
                        )
                        ->join('pegawai',array(
                            'nama as nama_pegawai',
                            )
                        )
                        ->join('master_inv',array(
                            'nama',
                            'kategori',
                            )
                        )
                        ->join('master_supplier',array(
                            'nama as nama_supplier',
                        ))
                        ->on(
                            array(
                                array('inventori_return_detail.inventori_return', '=', 'inventori_return.uid'),
                                array('inventori_return.pegawai', '=', 'pegawai.uid'),
                                array('inventori_return_detail.barang', '=', 'master_inv.uid'),
                                array('inventori_return.supplier', '=', 'master_supplier.uid'),
                 
                            )
                        )
                        ->where($paramData,$paramValue)
                        ->execute();

            $data['recordsTotal'] = count($itemTotal['response_data']);
            $data['recordsFiltered'] = count($itemTotal['response_data']);
            $data['length'] = intval($parameter['length']);
            $data['start'] = intval($parameter['start']);
            return $data;
    }

    public function print_report_farmasi_retur($parameter){
       
        $paramData = array(
            'inventori_return_detail.deleted_at' => 'IS NULL',
            'AND',  
            'inventori_return_detail.created_at' => 'BETWEEN ? AND ?',
        );
        $paramValue = array($parameter['from'], $parameter['to']);

        
        $data = self::$query->select('inventori_return_detail', array(
            
            'barang',
            'batch',
            'qty',
            'created_at',
            'updated_at'
            ))
            ->join('inventori_return',array(
                'kode',
                'pegawai',
                'supplier',
                'created_at',
                'updated_at'
                )
            )
            ->join('pegawai',array(
                'nama as nama_pegawai',
                )
            )
            ->join('master_inv',array(
                'nama',
                'kategori',
                )
            )
            ->join('inventori_batch',array(
                'uid as uid_batch',
                'batch',
                'po',
                'expired_date'
            ))
            ->join('master_supplier',array(
                'nama as nama_supplier',
            ))
            ->on(
                array(
                    array('inventori_return_detail.inventori_return', '=', 'inventori_return.uid'),
                    array('inventori_return.pegawai', '=', 'pegawai.uid'),
                    array('inventori_return_detail.barang', '=', 'master_inv.uid'),
                    array('inventori_return_detail.batch', '=', 'inventori_batch.uid'),
                    array('inventori_return.supplier', '=', 'master_supplier.uid'),
                
                )
            )
            ->where($paramData,$paramValue)
            ->execute();
        

            $pegawai = new Pegawai(self::$pdo);
            $pasien = new Pasien(self::$pdo);
            $ruangan = new Ruangan(self::$pdo);
            $Penjamin = new Penjamin(self::$pdo);
            $Inventori = new Inventori(self::$pdo);
            $data['response_draw'] = $parameter['draw'];
            $autonum = intval($parameter['start']) + 1;
            foreach ($data['response_data'] as $key => $value) {
                $data['response_data'][$key]['autonum'] = $autonum;
                $data['response_data'][$key]['item_detail'] = $Inventori->get_item_detail($value['barang'])['response_data'][0];

                $autonum++;
            }

            
            return $data;
    }


    private function keuangan($parameter) {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'invoice.deleted_at' => 'IS NULL',
                'AND',
                'invoice.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'antrian.penjamin' => '= ?',
                'AND',
                'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array(
                $parameter['from'], $parameter['to'], $parameter['penjamin']
            );
        } else {
            $paramData = array(
                'invoice.deleted_at' => 'IS NULL',
                'AND',
                'invoice.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'antrian.penjamin' => '= ?'
            );

            $paramValue = array(
                $parameter['from'], $parameter['to'], $parameter['penjamin']
            );
        }

        if (intval($parameter['length']) < 0) {
            $data = self::$query->select('invoice', array(
                'uid',
                'kunjungan',
                'pasien',
                'total_pre_discount',
                'discount',
                'discount_type',
                'total_after_discount',
                'keterangan',
                'created_at',
                'nomor_invoice'
            ))
                ->join('pasien', array(
                    'nama'
                ))

                ->join('antrian', array(
                    'uid as uid_antrian'
                ))

                ->on(array(
                    array('invoice.pasien', '=', 'pasien.uid'),
                    array('antrian.kunjungan', '=', 'invoice.kunjungan')
                ))
                ->order(array(
                    'invoice.created_at' => 'ASC'
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('invoice', array(
                'uid',
                'kunjungan',
                'pasien',
                'total_pre_discount',
                'discount',
                'discount_type',
                'total_after_discount',
                'keterangan',
                'created_at',
                'nomor_invoice'
            ))
                ->join('pasien', array(
                    'nama'
                ))


                ->join('antrian', array(
                    'uid as uid_antrian',
                    'penjamin'
                ))

                ->on(array(
                    array('invoice.pasien', '=', 'pasien.uid'),
                    array('antrian.kunjungan', '=', 'invoice.kunjungan')
                ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->order(array(
                    'invoice.created_at' => 'ASC'
                ))
                ->where($paramData, $paramValue)
                ->execute();
        }
        $dataResult = array();
        $data['response_draw'] = intval($parameter['draw']);
        $Pasien = new Pasien(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);
        $Invoice = new Invoice(self::$pdo);
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['pasien'] = $Pasien->get_pasien_detail('pasien', $value['pasien'])['response_data'][0];

            $data['response_data'][$key]['penjamin'] = $Penjamin->get_penjamin_detail($value['penjamin'])['response_data'][0];
            $Payment = self::$query->select('invoice_payment', array(
                'uid',
                'terbayar',
                'sisa_bayar',
                'metode_bayar',
                'tanggal_bayar',
                'nomor_kwitansi'
            ))
                ->where(array(
                    'invoice_payment.invoice' => '= ?',
                    'AND',
                    'invoice_payment.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();

            $data['response_data'][$key]['payment'] = $Payment['response_data'][0];

            $data['response_data'][$key]['created_at_parse'] = date('d F Y', strtotime($value['created_at']));

            $data['response_data'][$key]['autonum'] = $autonum;

            $autonum++;
        }

        $KunjunganTotal = self::$query->select('invoice', array(
            'uid'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($KunjunganTotal['response_data']);
        $data['recordsFiltered'] = count($dataResult);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function keuangan_billing_harian($parameter) {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'invoice.deleted_at' => 'IS NULL',
                'AND',
                'invoice.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'antrian.penjamin' => '= ?',
                'AND',
                'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array(
                $parameter['from'], $parameter['to'], $parameter['penjamin']
            );
        } else {
            $paramData = array(
                'invoice.deleted_at' => 'IS NULL',
                'AND',
                'invoice.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'antrian.penjamin' => '= ?'
            );

            $paramValue = array(
                $parameter['from'], $parameter['to'], $parameter['penjamin']
            );
        }

        if (intval($parameter['length']) < 0) {
            $data = self::$query->select('invoice', array(
                'uid',
                'kunjungan',
                'pasien',
                'total_pre_discount',
                'discount',
                'discount_type',
                'total_after_discount',
                'keterangan',
                'created_at',
                'nomor_invoice'
            ))
                ->join('pasien', array(
                    'nama'
                ))

                ->join('antrian', array(
                    'uid as uid_antrian',
                    'waktu_masuk',
                    'waktu_keluar'
                ))

                ->on(array(
                    array('invoice.pasien', '=', 'pasien.uid'),
                    array('antrian.kunjungan', '=', 'invoice.kunjungan')
                ))
                ->order(array(
                    'invoice.created_at' => 'ASC'
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('invoice', array(
                'uid',
                'kunjungan',
                'pasien',
                'total_pre_discount',
                'discount',
                'discount_type',
                'total_after_discount',
                'keterangan',
                'created_at',
                'nomor_invoice'
            ))
                ->join('pasien', array(
                    'nama'
                ))


                ->join('antrian', array(
                    'uid as uid_antrian',
                    'penjamin',
                    'waktu_masuk',
                    'waktu_keluar'
                ))

                ->on(array(
                    array('invoice.pasien', '=', 'pasien.uid'),
                    array('antrian.kunjungan', '=', 'invoice.kunjungan')
                ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->order(array(
                    'invoice.created_at' => 'ASC'
                ))
                ->where($paramData, $paramValue)
                ->execute();
        }
        $dataResult = array();
        $data['response_draw'] = intval($parameter['draw']);
        $Pasien = new Pasien(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);
        $Invoice = new Invoice(self::$pdo);
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['pasien'] = $Pasien->get_pasien_detail('pasien', $value['pasien'])['response_data'][0];

            $data['response_data'][$key]['penjamin'] = $Penjamin->get_penjamin_detail($value['penjamin'])['response_data'][0];
            $Payment = self::$query->select('invoice_payment', array(
                'uid',
                'terbayar',
                'sisa_bayar',
                'metode_bayar',
                'tanggal_bayar',
                'nomor_kwitansi'
            ))
                ->where(array(
                    'invoice_payment.invoice' => '= ?',
                    'AND',
                    'invoice_payment.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();

            $data['response_data'][$key]['payment'] = $Payment['response_data'][0];

            $data['response_data'][$key]['created_at_parse'] = date('d F Y', strtotime($value['created_at']));

            $data['response_data'][$key]['autonum'] = $autonum;

            $autonum++;
        }

        $KunjunganTotal = self::$query->select('invoice', array(
            'uid',
            'kunjungan',
            'pasien',
            'total_pre_discount',
            'discount',
            'discount_type',
            'total_after_discount',
            'keterangan',
            'created_at',
            'nomor_invoice'
        ))
            ->join('pasien', array(
                'nama'
            ))

            ->join('antrian', array(
                'uid as uid_antrian',
                'waktu_masuk',
                'waktu_keluar'
            ))

            ->on(array(
                array('invoice.pasien', '=', 'pasien.uid'),
                array('antrian.kunjungan', '=', 'invoice.kunjungan')
            ))
            ->order(array(
                'invoice.created_at' => 'ASC'
            ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($KunjunganTotal['response_data']);
        $data['recordsFiltered'] = count($KunjunganTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function kunjungan_rawat_jalan($parameter) {

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            
            $paramData = array(
                'antrian.deleted_at' => 'IS NULL',
                'AND',
                '(antrian.departemen' => '!= ?',
                'AND',
                'antrian.departemen' => '!= ?)',
                'AND',
                'antrian.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
    
            );
    
            $paramValue = array(
                __POLI_INAP__,__POLI_IGD__,$parameter['from'], $parameter['to']
            );
        } else {
            $paramData = array(
                'antrian.deleted_at' => 'IS NULL',
                'AND',
                '(antrian.departemen' => '!= ?',
                'AND',
                'antrian.departemen' => '!= ?)',
                'AND',
                'antrian.created_at' => 'BETWEEN ? AND ?'
    
            );
    
            $paramValue = array(
                __POLI_INAP__,__POLI_IGD__,$parameter['from'], $parameter['to']
            );
        }

        if (intval($parameter['length']) < 0) {
            $data = self::$query->select('antrian', array(
                'uid',
                'pasien',
                'departemen',
                'penjamin',
                'waktu_masuk',
                'waktu_keluar'
    
            ))
                ->join('pasien', array(
                    'nama'
                ))
    
                ->join('master_poli', array(
                    'nama as nama_departemen'
                ))
    
                ->on(array(
                    array('antrian.pasien', '=', 'pasien.uid'),
                    array('antrian.departemen', '=', 'master_poli.uid')
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('antrian', array(
                'uid',
                'pasien',
                'departemen',
                'penjamin',
                'waktu_masuk',
                'waktu_keluar'
    
            ))
                ->join('pasien', array(
                    'nama'
                ))
    
                ->join('master_poli', array(
                    'nama as nama_departemen'
                ))
    
                ->on(array(
                    array('antrian.pasien', '=', 'pasien.uid'),
                    array('antrian.departemen', '=', 'master_poli.uid')
                ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }
        $dataResult = array();
        $data['response_draw'] = intval($parameter['draw']);
        $Pasien = new Pasien(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['waktu_masuk'] = date('d F Y', strtotime($value['waktu_masuk']));
            $data['response_data'][$key]['waktu_keluar'] =!empty($value['waktu_keluar']) ?  date('d F Y', strtotime($value['waktu_keluar'])) : '-';

            $data['response_data'][$key]['pasien'] = $Pasien->get_pasien_detail('pasien', $value['pasien'])['response_data'][0];

            $data['response_data'][$key]['penjamin'] = $Penjamin->get_penjamin_detail($value['penjamin'])['response_data'][0];

            $data['response_data'][$key]['autonum'] = $autonum;

            $autonum++;
        }

        $KunjunganTotal = self::$query->select('kunjungan', array(
            'uid'
        )) ->join('antrian', array(
            'uid',
            'pasien',
            'penjamin',
        ))
        ->on(array(
            array('antrian.kunjungan', '=', 'kunjungan.uid'),
        ))
        ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($KunjunganTotal['response_data']);
        $data['recordsFiltered'] = count($KunjunganTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;

    }

    //GET DATA KEPERLUAN PRINT
    private function print_kunjungan_rawat_jalan($parameter) {
     
        $paramData = array(
            'antrian.deleted_at' => 'IS NULL',
            'AND',
            '(antrian.departemen' => '!= ?',
            'AND',
            'antrian.departemen' => '!= ?)',
            'AND',
            'antrian.created_at' => 'BETWEEN ? AND ?'

        );

        $paramValue = array(
            __POLI_INAP__,__POLI_IGD__,$parameter['from'], $parameter['to']
        );
        

        $data = self::$query->select('antrian', array(
            'uid',
            'pasien',
            'departemen',
            'penjamin',
            'waktu_masuk',
            'waktu_keluar'

        ))
            ->join('pasien', array(
                'nama'
            ))

            ->join('master_poli', array(
                'nama as nama_departemen'
            ))

            ->on(array(
                array('antrian.pasien', '=', 'pasien.uid'),
                array('antrian.departemen', '=', 'master_poli.uid')
            ))
            ->where($paramData, $paramValue)
            ->execute();
       

        $dataResult = array();
        $Pasien = new Pasien(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['waktu_masuk'] = date('d-m-Y', strtotime($value['waktu_masuk']));
            $data['response_data'][$key]['waktu_keluar'] =!empty($value['waktu_keluar']) ?  date('d-m-Y', strtotime($value['waktu_keluar'])) : '-';

            $data['response_data'][$key]['pasien'] = $Pasien->get_pasien_detail('pasien', $value['pasien'])['response_data'][0];

            $data['response_data'][$key]['penjamin'] = $Penjamin->get_penjamin_detail($value['penjamin'])['response_data'][0];

            $data['response_data'][$key]['autonum'] = $autonum;

            $autonum++;
        }

        return $data;

    }

    private function kunjungan_rawat_inap($parameter) {
       

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'rawat_inap.deleted_at' => 'IS NULL',
                'AND',
                'rawat_inap.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array(
              $parameter['from'], $parameter['to']
            );
        } else {
            $paramData = array(
                'rawat_inap.deleted_at' => 'IS NULL',
                'AND',
                'rawat_inap.created_at' => 'BETWEEN ? AND ?'
            );

            $paramValue = array(
              $parameter['from'], $parameter['to']
            );
        }

        if (intval($parameter['length']) < 0) {
            $data = self::$query->select('rawat_inap', array(
                'uid',
                'pasien',
                'penjamin',
                'kamar',
                'bed',
                'waktu_masuk',
                'waktu_keluar'
            ))
                ->join('pasien', array(
                    'nama'
                ))
                ->join('master_unit_ruangan', array(
                    'nama as nama_ruangan'
                ))
                ->join('master_unit_bed', array(
                    'nama as nama_bed'
                ))
    
                ->on(array(
                    array('rawat_inap.pasien', '=', 'pasien.uid'),
                    array('rawat_inap.kamar', '=', 'master_unit_ruangan'),
                    array('rawat_inap.bed', '=', 'master_unit_bed')
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('rawat_inap', array(
                'uid',
                'pasien',
                'penjamin',
                'kamar',
                'bed',
                'waktu_masuk',
                'waktu_keluar'
            ))
                ->join('pasien', array(
                    'nama'
                ))
                ->join('master_unit_ruangan', array(
                    'nama as nama_ruangan'
                ))
                ->join('master_unit_bed', array(
                    'nama as nama_bed'
                ))
                ->on(array(
                    array('rawat_inap.pasien', '=', 'pasien.uid'),
                    array('rawat_inap.kamar', '=', 'master_unit_ruangan.uid'),
                    array('rawat_inap.bed', '=', 'master_unit_bed.uid')
                ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }


        $dataResult = array();
        $data['response_draw'] = intval($parameter['draw']);
        $Pasien = new Pasien(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['waktu_masuk'] = date('d F Y', strtotime($value['waktu_masuk']));
            $data['response_data'][$key]['waktu_keluar'] =!empty($value['waktu_keluar']) ?  date('d F Y', strtotime($value['waktu_keluar'])) : '-';

            $data['response_data'][$key]['pasien'] = $Pasien->get_pasien_detail('pasien', $value['pasien'])['response_data'][0];

            $data['response_data'][$key]['penjamin'] = $Penjamin->get_penjamin_detail($value['penjamin'])['response_data'][0];

            $data['response_data'][$key]['autonum'] = $autonum;

            $autonum++;
        }

        $KunjunganTotal = self::$query->select('rawat_inap', array(
            'uid'
        ))
        ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($KunjunganTotal['response_data']);
        $data['recordsFiltered'] = count($KunjunganTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;

    }

    //KEPERLUAN PRINT RAWAT INAP
    private function print_kunjungan_rawat_inap($parameter) {
    
        $paramData = array(
            'rawat_inap.deleted_at' => 'IS NULL',
            'AND',
            'rawat_inap.created_at' => 'BETWEEN ? AND ?'
        );

        $paramValue = array(
            $parameter['from'], $parameter['to']
        );
    


        $data = self::$query->select('rawat_inap', array(
            'uid',
            'pasien',
            'penjamin',
            'kamar',
            'bed',
            'waktu_masuk',
            'waktu_keluar'
        ))
            ->join('pasien', array(
                'nama'
            ))
            ->join('master_unit_ruangan', array(
                'nama as nama_ruangan'
            ))
            ->join('master_unit_bed', array(
                'nama as nama_bed'
            ))
            ->on(array(
                array('rawat_inap.pasien', '=', 'pasien.uid'),
                array('rawat_inap.kamar', '=', 'master_unit_ruangan.uid'),
                array('rawat_inap.bed', '=', 'master_unit_bed.uid')
            ))
            ->where($paramData, $paramValue)
            ->execute();
    

        $dataResult = array();
        $data['response_draw'] = intval($parameter['draw']);
        $Pasien = new Pasien(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['waktu_masuk'] = date('d F Y', strtotime($value['waktu_masuk']));
            $data['response_data'][$key]['waktu_keluar'] =!empty($value['waktu_keluar']) ?  date('d F Y', strtotime($value['waktu_keluar'])) : '-';

            $data['response_data'][$key]['pasien'] = $Pasien->get_pasien_detail('pasien', $value['pasien'])['response_data'][0];

            $data['response_data'][$key]['penjamin'] = $Penjamin->get_penjamin_detail($value['penjamin'])['response_data'][0];

            $data['response_data'][$key]['autonum'] = $autonum;

            $autonum++;
        }

        return $data;

    }

    private function kunjungan_igd($parameter) {
       

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'rawat_inap.deleted_at' => 'IS NULL',
                'AND',
                'rawat_inap.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array(
              $parameter['from'], $parameter['to']
            );
        } else {
            $paramData = array(
                'igd.deleted_at' => 'IS NULL',
                'AND',
                'igd.created_at' => 'BETWEEN ? AND ?'
            );

            $paramValue = array(
              $parameter['from'], $parameter['to']
            );
        }

        if (intval($parameter['length']) < 0) {
            $data = self::$query->select('igd', array(
                'uid',
                'pasien',
                'penjamin',
                'waktu_masuk',
                'waktu_keluar'
            ))
                ->join('pasien', array(
                    'nama'
                ))

                ->on(array(
                    array('igd.pasien', '=', 'pasien.uid')
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('igd', array(
                'uid',
                'pasien',
                'penjamin',
                'waktu_masuk',
                'waktu_keluar'
            ))
                ->join('pasien', array(
                    'nama'
                ))

                ->on(array(
                    array('igd.pasien', '=', 'pasien.uid')
                ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }


        $dataResult = array();
        $data['response_draw'] = intval($parameter['draw']);
        $Pasien = new Pasien(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['waktu_masuk'] = date('d F Y', strtotime($value['waktu_masuk']));
            $data['response_data'][$key]['waktu_keluar'] =!empty($value['waktu_keluar']) ?  date('d F Y', strtotime($value['waktu_keluar'])) : '-';

            $data['response_data'][$key]['pasien'] = $Pasien->get_pasien_detail('pasien', $value['pasien'])['response_data'][0];

            $data['response_data'][$key]['penjamin'] = $Penjamin->get_penjamin_detail($value['penjamin'])['response_data'][0];

            $data['response_data'][$key]['autonum'] = $autonum;

            $autonum++;
        }

        $KunjunganTotal = self::$query->select('igd', array(
            'uid'
        ))
        ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($KunjunganTotal['response_data']);
        $data['recordsFiltered'] = count($KunjunganTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;

    }


    //KEPERLUAN PRINT
    private function print_kunjungan_igd($parameter) {
       

        
        $paramData = array(
            'igd.deleted_at' => 'IS NULL',
            'AND',
            'igd.created_at' => 'BETWEEN ? AND ?'
        );

        $paramValue = array(
            $parameter['from'], $parameter['to']
        );
    
        $data = self::$query->select('igd', array(
            'uid',
            'pasien',
            'penjamin',
            'waktu_masuk',
            'waktu_keluar'
        ))
            ->join('pasien', array(
                'nama'
            ))

            ->on(array(
                array('igd.pasien', '=', 'pasien.uid')
            ))
            ->where($paramData, $paramValue)
            ->execute();
        


        $dataResult = array();
        $data['response_draw'] = intval($parameter['draw']);
        $Pasien = new Pasien(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['waktu_masuk'] = date('d F Y', strtotime($value['waktu_masuk']));
            $data['response_data'][$key]['waktu_keluar'] =!empty($value['waktu_keluar']) ?  date('d F Y', strtotime($value['waktu_keluar'])) : '-';

            $data['response_data'][$key]['pasien'] = $Pasien->get_pasien_detail('pasien', $value['pasien'])['response_data'][0];

            $data['response_data'][$key]['penjamin'] = $Penjamin->get_penjamin_detail($value['penjamin'])['response_data'][0];

            $data['response_data'][$key]['autonum'] = $autonum;

            $autonum++;
        }

        return $data;

    }
}
?>