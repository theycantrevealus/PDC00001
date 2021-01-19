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
        }
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