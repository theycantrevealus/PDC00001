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

    public function __POST__($parameter = array())
    {
        switch ($parameter['request']) {
            case 'kunjungan_rawat_jalan':
                return self::kunjungan_rawat_jalan($parameter);
                break;
            case 'keuangan':
                return self::keuangan($parameter);
                break;
            case 'penyakit':
                return self::penyakit($parameter);
                break;
            case 'obat_penjamin':
                return self::obat_penjamin($parameter);
                break;
        }
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

    private function kunjungan_rawat_jalan($parameter) {

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'kunjungan.deleted_at' => 'IS NULL',
                'AND',
                'kunjungan.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array(
                $parameter['from'], $parameter['to']
            );
        } else {
            $paramData = array(
                'kunjungan.deleted_at' => 'IS NULL',
                'AND',
                'kunjungan.created_at' => 'BETWEEN ? AND ?'
            );

            $paramValue = array(
                $parameter['from'], $parameter['to']
            );
        }

        if (intval($parameter['length']) < 0) {
            $data = self::$query->select('kunjungan', array(
                'uid',
                'waktu_masuk',
                'waktu_keluar'
            ))
                ->join('antrian', array(
                    'uid',
                    'pasien',
                    'penjamin'
                ))

                ->join('pasien', array(
                    'nama'
                ))

                ->on(array(
                    array('antrian.kunjungan', '=', 'kunjungan.uid'),
                    array('antrian.pasien', '=', 'pasien.uid')
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('kunjungan', array(
                'uid',
                'waktu_masuk',
                'waktu_keluar'
            ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->join('antrian', array(
                    'uid',
                    'pasien',
                    'penjamin'
                ))

                ->join('pasien', array(
                    'nama'
                ))

                ->on(array(
                    array('antrian.kunjungan', '=', 'kunjungan.uid'),
                    array('antrian.pasien', '=', 'pasien.uid')
                ))
                ->where($paramData, $paramValue)
                ->execute();
        }
        $dataResult = array();
        $data['response_draw'] = intval($parameter['draw']);
        $Pasien = new Pasien(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['waktu_masuk'] = date('d F Y', strtotime($value['waktu_masuk']));
            $data['response_data'][$key]['waktu_keluar'] = date('d F Y', strtotime($value['waktu_keluar']));

            $data['response_data'][$key]['pasien'] = $Pasien->get_pasien_detail('pasien', $value['pasien'])['response_data'][0];

            $data['response_data'][$key]['penjamin'] = $Penjamin->get_penjamin_detail($value['penjamin'])['response_data'][0];

            $data['response_data'][$key]['autonum'] = $autonum;

            $autonum++;
        }

        $KunjunganTotal = self::$query->select('kunjungan', array(
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
}
?>