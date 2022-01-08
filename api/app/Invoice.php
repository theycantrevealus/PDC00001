<?php

namespace PondokCoder;

use DateInterval;
use DatePeriod;
use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Pasien as Pasien;
use PondokCoder\Pegawai as Pegawai;
use PondokCoder\Penjamin as Penjamin;
use PondokCoder\Anjungan as Anjungan;
use PondokCoder\Poli as Poli;
use PondokCoder\Utility as Utility;

class Invoice extends Utility
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

    public function __GET__($parameter = array())
    {
        try {
            switch ($parameter[1]) {
                case 'detail':
                    return self::get_biaya_pasien_detail($parameter[2]);
                    break;
                case 'payment':
                    return self::get_payment($parameter[2]);
                    break;
                case 'kwitansi':
                    return self::get_kwitansi($parameter);
                    break;
                case 'biaya_pasien_total':
                    return self::biaya_pasien_total($parameter[2]);
                    break;
                default:
                    return self::get_biaya_pasien();
            }
        } catch (QueryException $e) {
            return 'Error => ' . $e;
        }
    }

    public function __POST__($parameter = array())
    {
        try {
            switch ($parameter['request']) {
                case 'proses_bayar':
                    return self::proses_bayar($parameter);
                    break;
                case 'retur_biaya':
                    return self::retur_biaya($parameter);
                    break;
                case 'kwitansi_data':
                    return self::get_kwitansi($parameter);
                    break;
                case 'biaya_pasien':
                    return self::get_biaya_pasien_back_end($parameter);
                    break;
                case 'lunaskan_kartu_pasien_lama':
                    return self::lunaskan_kartu_pasien_lama($parameter);
                    break;
                default:
                    return self::get_biaya_pasien();
            }
        } catch (QueryException $e) {
            return 'Error => ' . $e;
        }
    }

    private function biaya_pasien_total($parameter) {
        $data = self::$query->select('invoice', array(
            'uid',
            'nomor_invoice',
            'kunjungan',
            'pasien',
            'total_pre_discount',
            'discount',
            'discount_type',
            'total_after_discount',
            'keterangan',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'invoice.deleted_at' => 'IS NULL',
                'AND',
                'invoice.pasien' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        $Inventori = new Inventori(self::$pdo);
        $Bed = new Bed(self::$pdo);
        $Tindakan = new Tindakan(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $InvoiceItem = self::$query->select('invoice_detail', array(
                'id',
                'invoice',
                'item',
                'item_type',
                'qty',
                'harga',
                'status_bayar',
                'subtotal',
                'departemen',
                'keterangan',
                'billing_group'
            ))
                ->where(array(
                    'invoice_detail.invoice' => '= ?',
                    'AND',
                    'invoice_detail.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach ($InvoiceItem['response_data'] as $itemKey => $itemValue) {
                if($itemValue['item_type'] === 'master_tindakan') {
                    $InvoiceItem['response_data'][$itemKey]['item'] = $Tindakan->get_tindakan_detail($itemValue['item'])['response_data'][0];
                } else if($itemValue['item_type'] === 'master_unit_bed') {
                    $InvoiceItem['response_data'][$itemKey]['item'] = $Bed->get_bed_detail($itemValue['item_type'], $itemValue['item'])['response_data'][0];
                } else if($itemValue['item_type'] === 'master_inv') {
                    $InvoiceItem['response_data'][$itemKey]['item'] = $Inventori->get_item_detail($itemValue['item'])['response_data'][0];
                } else {
                    $InvoiceItem['response_data'][$itemKey]['item'] = array(
                        'uid' => $itemValue,
                        'nama' => 'Tidak diketahui'
                    );
                }
            }
            $data['response_data'][$key]['detail'] = $InvoiceItem['response_data'];
        }
        return $data;
    }

    private function get_kwitansi($parameter)
    {
        /*if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'invoice_payment.deleted_at' => 'IS NULL',
                'AND',
                'invoice_payment.tanggal_bayar' => 'BETWEEN ? AND ?',
                'AND',
                '(invoice_payment.nomor_kwitansi' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
            );

            $paramValue = array(
                $parameter['from'], $parameter['to']
            );
        } else {
            $paramData = array(
                'invoice_payment.deleted_at' => 'IS NULL',
                'AND',
                'invoice_payment.tanggal_bayar' => 'BETWEEN ? AND ?'
            );

            $paramValue = array(
                $parameter['from'], $parameter['to']
            );
        }*/

        if($parameter['jenis'] === '*') {
            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                $paramData = array(
                    'invoice_payment.deleted_at' => 'IS NULL',
                    'AND',
                    'invoice_payment.tanggal_bayar' => 'BETWEEN ? AND ?',
                    'AND',
                    '(invoice_payment.nomor_kwitansi' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
                );

                $paramValue = array(
                    $parameter['from'], $parameter['to']
                );
            } else {
                $paramData = array(
                    'invoice_payment.deleted_at' => 'IS NULL',
                    'AND',
                    'invoice_payment.tanggal_bayar' => 'BETWEEN ? AND ?'
                );

                $paramValue = array(
                    $parameter['from'], $parameter['to']
                );
            }
        } else if($parameter['jenis'] === 'RAJAL') {
            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                $paramData = array(
                    'invoice_payment.deleted_at' => 'IS NULL',
                    'AND',
                    'invoice_payment.tanggal_bayar' => 'BETWEEN ? AND ?',
                    'AND',
                    '(antrian.departemen' => '!= ?',
                    'AND',
                    'antrian.departemen' => '!= ?)',
                    'AND',
                    '(invoice_payment.nomor_kwitansi' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
                );

                $paramValue = array(
                    $parameter['from'], $parameter['to'], __POLI_IGD__, __POLI_INAP__
                );
            } else {
                $paramData = array(
                    'invoice_payment.deleted_at' => 'IS NULL',
                    'AND',
                    'invoice_payment.tanggal_bayar' => 'BETWEEN ? AND ?',
                    'AND',
                    '(antrian.departemen' => '!= ?',
                    'AND',
                    'antrian.departemen' => '!= ?)'
                );

                $paramValue = array(
                    $parameter['from'], $parameter['to'], __POLI_IGD__, __POLI_INAP__
                );
            }
        } else if($parameter['jenis'] === 'RANAP') {
            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                $paramData = array(
                    'invoice_payment.deleted_at' => 'IS NULL',
                    'AND',
                    'invoice_payment.tanggal_bayar' => 'BETWEEN ? AND ?',
                    'AND',
                    '(invoice_payment.nomor_kwitansi' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                    'AND',
                    'antrian.departemen' => '= ?'
                );

                $paramValue = array(
                    $parameter['from'], $parameter['to'], __POLI_INAP__
                );
            } else {
                $paramData = array(
                    'invoice_payment.deleted_at' => 'IS NULL',
                    'AND',
                    'invoice_payment.tanggal_bayar' => 'BETWEEN ? AND ?',
                    'AND',
                    'antrian.departemen' => '= ?'
                );

                $paramValue = array(
                    $parameter['from'], $parameter['to'], __POLI_INAP__
                );
            }
        } else if($parameter['jenis'] === 'IGD') {
            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                $paramData = array(
                    'invoice_payment.deleted_at' => 'IS NULL',
                    'AND',
                    'invoice_payment.tanggal_bayar' => 'BETWEEN ? AND ?',
                    'AND',
                    '(invoice_payment.nomor_kwitansi' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                    'AND',
                    'antrian.departemen' => '= ?'
                );

                $paramValue = array(
                    $parameter['from'], $parameter['to'], __POLI_IGD__
                );
            } else {
                $paramData = array(
                    'invoice_payment.deleted_at' => 'IS NULL',
                    'AND',
                    'invoice_payment.tanggal_bayar' => 'BETWEEN ? AND ?',
                    'AND',
                    'antrian.departemen' => '= ?'
                );

                $paramValue = array(
                    $parameter['from'], $parameter['to'], __POLI_IGD__
                );
            }
        }

        if ($parameter['length'] < 0) {
            $payment = self::$query->select('invoice_payment', array(
                'uid',
                'nomor_kwitansi',
                'pasien',
                'invoice',
                'pegawai',
                'terbayar',
                'sisa_bayar',
                'keterangan',
                'metode_bayar',
                'tanggal_bayar'
            ))
                ->order(array(
                    $parameter['column_set'][$parameter['order'][0]['column']] => $parameter['order'][0]['dir']
                ))
                ->join('invoice', array(
                    'kunjungan'
                ))
                ->join('antrian', array(
                    'departemen'
                ))
                ->join('pasien', array(
                    'nama as nama_pasien'
                ))
                ->on(array(
                    array('invoice_payment.invoice', '=', 'invoice.uid'),
                    array('invoice.kunjungan', '=', 'antrian.kunjungan'),
                    array('invoice_payment.pasien', '=', 'pasien.uid')
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $payment = self::$query->select('invoice_payment', array(
                'uid',
                'nomor_kwitansi',
                'pasien',
                'invoice',
                'pegawai',
                'terbayar',
                'sisa_bayar',
                'keterangan',
                'metode_bayar',
                'tanggal_bayar'
            ))
                ->order(array(
                    $parameter['column_set'][$parameter['order'][0]['column']] => $parameter['order'][0]['dir']
                ))
                ->join('invoice', array(
                    'kunjungan'
                ))
                ->join('antrian', array(
                    'departemen'
                ))
                ->join('pasien', array(
                    'nama as nama_pasien'
                ))
                ->on(array(
                    array('invoice_payment.invoice', '=', 'invoice.uid'),
                    array('invoice.kunjungan', '=', 'antrian.kunjungan'),
                    array('invoice_payment.pasien', '=', 'pasien.uid')
                ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $payment['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        $Pegawai = new Pegawai(self::$pdo);
        $Pasien = new Pasien(self::$pdo);

        foreach ($payment['response_data'] as $key => $value) {
            if (!isset($value['pasien']) || !isset($value['pegawai'])) {
                unset($payment['response_data'][$key]);
            } else {

                $PaymentStatus = self::get_payment($value['uid']);
                $payment['response_data'][$key]['status'] = $PaymentStatus['response_data'][0];

                $PegawaiInfo = $Pegawai->get_detail($value['pegawai']);
                $payment['response_data'][$key]['pegawai'] = $PegawaiInfo['response_data'][0];


                $PasienInfo = $Pasien->get_pasien_detail('pasien', $value['pasien']);
                $payment['response_data'][$key]['pasien'] = $PasienInfo['response_data'][0];

                $payment['response_data'][$key]['terbayar'] = number_format($value['terbayar'], 2, '.', ',');
                $payment['response_data'][$key]['record_terbayar'] = self::get_payment($value['uid'])['response_data'];

                $Detail = self::$query->select('invoice_payment_detail', array(
                    'penjamin', 'status', 'subtotal'
                ))
                    ->join('master_penjamin', array(
                        'nama as nama_penjamin'
                    ))
                    ->on(array(
                        array('invoice_payment_detail.penjamin', '=', 'master_penjamin.uid')
                    ))
                    ->where(array(
                        'invoice_payment_detail.invoice_payment' => '= ?',
                        'AND',
                        'invoice_payment_detail.deleted_at' => 'IS NULL'
                    ), array(
                        $value['uid']
                    ))
                    ->execute();
                $payment['response_data'][$key]['detail_item'] = $Detail['response_data'];

                $payment['response_data'][$key]['autonum'] = $autonum;
                $autonum++;
            }
        }

        $paymentTotal = self::$query->select('invoice_payment', array(
            'uid',
            'nomor_kwitansi',
            'pasien',
            'invoice',
            'pegawai',
            'terbayar',
            'sisa_bayar',
            'keterangan',
            'metode_bayar',
            'tanggal_bayar'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $payment['recordsTotal'] = count($paymentTotal['response_data']);
        $payment['recordsFiltered'] = count($paymentTotal['response_data']);
        $payment['length'] = intval($parameter['length']);
        $payment['start'] = intval($parameter['start']);

        return $payment;
    }

    private function lunaskan_kartu_pasien_lama($parameter) {
        return array();
    }

    private function get_biaya_pasien_back_end($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            if(isset($parameter['filter_poli'])) {
                if($parameter['filter_poli'] === 'rajal') {
                    $paramData = array(
                        'invoice.deleted_at' => 'IS NULL',
                        'AND',
                        'invoice.created_at' => 'BETWEEN ? AND ?',
                        'AND',
                        '(invoice.nomor_invoice' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                        'OR',
                        'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                        'OR',
                        'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                        'AND',
                        '(antrian_nomor.poli' => '!= ?',
                        'AND',
                        'antrian_nomor.poli' => '!= ?)'
                    );

                    $paramValue = array(
                        $parameter['from'], $parameter['to'], __POLI_INAP__, __POLI_IGD__
                    );
                } else if($parameter['filter_poli'] === 'ranap') {
                    $paramData = array(
                        'invoice.deleted_at' => 'IS NULL',
                        'AND',
                        'invoice.created_at' => 'BETWEEN ? AND ?',
                        'AND',
                        '(invoice.nomor_invoice' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                        'OR',
                        'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                        'OR',
                        'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                        /*'AND',
                        'antrian_nomor.poli' => '= ?'*/
                    );

                    $paramValue = array(
                        //$parameter['from'], $parameter['to'], __POLI_INAP__
                        $parameter['from'], $parameter['to']
                    );
                }
            } else {
                $paramData = array(
                    'invoice.deleted_at' => 'IS NULL',
                    'AND',
                    'invoice.created_at' => 'BETWEEN ? AND ?',
                    'AND',
                    '(invoice.nomor_invoice' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                    /*'AND',
                    'antrian_nomor.poli' => '= ?'*/
                );

                $paramValue = array(
                    //$parameter['from'], $parameter['to'], __POLI_IGD__
                    $parameter['from'], $parameter['to']
                );
            }
        } else {
            if(isset($parameter['filter_poli'])) {
                if($parameter['filter_poli'] === 'rajal') {
                    $paramData = array(
                        'invoice.deleted_at' => 'IS NULL',
                        'AND',
                        'invoice.created_at' => 'BETWEEN ? AND ?',
                        'AND',
                        '(antrian_nomor.poli' => '!= ?',
                        'AND',
                        'antrian_nomor.poli' => '!= ?)'
                    );

                    $paramValue = array(
                        date('Y-m-d', strtotime($parameter['from'] . ' -1 day')), date('Y-m-d', strtotime($parameter['to'] . ' +1 day')), __POLI_INAP__, __POLI_IGD__
                    );
                } else if($parameter['filter_poli'] === 'ranap') {
                    $paramData = array(
                        'invoice.deleted_at' => 'IS NULL',
                        'AND',
                        'invoice.created_at' => 'BETWEEN ? AND ?',
                        /*'AND',
                        'antrian_nomor.poli' => '= ?'*/
                    );

                    $paramValue = array(
                        //date('Y-m-d', strtotime($parameter['from'] . ' -1 day')), date('Y-m-d', strtotime($parameter['to'] . ' +1 day')), __POLI_INAP__
                        date('Y-m-d', strtotime($parameter['from'] . ' -1 day')), date('Y-m-d', strtotime($parameter['to'] . ' +1 day'))
                    );
                } else {
                    $paramData = array(
                        'invoice.deleted_at' => 'IS NULL',
                        'AND',
                        'invoice.created_at' => 'BETWEEN ? AND ?',
                        /*'AND',
                        'antrian_nomor.poli' => '= ?'*/
                    );

                    $paramValue = array(
                        //date('Y-m-d', strtotime($parameter['from'] . ' -1 day')), date('Y-m-d', strtotime($parameter['to'] . ' +1 day')), __POLI_IGD__
                        date('Y-m-d', strtotime($parameter['from'] . ' -1 day')), date('Y-m-d', strtotime($parameter['to'] . ' +1 day'))
                    );
                }
            } else {
                $paramData = array(
                    'invoice.deleted_at' => 'IS NULL',
                    'AND',
                    'invoice.created_at' => 'BETWEEN ? AND ?'
                );

                $paramValue = array(
                    date('Y-m-d', strtotime($parameter['from'] . ' -1 day')), date('Y-m-d', strtotime($parameter['to'] . ' +1 day'))
                );
            }
        }

        if (intval($parameter['length']) < 0) {
            $data = self::$query->select('invoice', array(
                'uid',
                'nomor_invoice',
                'kunjungan',
                'pasien',
                'total_pre_discount',
                'discount',
                'discount_type',
                'total_after_discount',
                'keterangan',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->join('antrian_nomor', array(
                    'id', 'poli'
                ))
                ->join('pasien', array(
                    'nama'
                ))
                ->on(array(
                    array('invoice.kunjungan', '=', 'antrian_nomor.kunjungan'),
                    array('invoice.pasien', '=', 'pasien.uid')
                ))
                ->execute();
        } else {
            $data = self::$query->select('invoice', array(
                'uid',
                'nomor_invoice',
                'kunjungan',
                'pasien',
                'total_pre_discount',
                'discount',
                'discount_type',
                'total_after_discount',
                'keterangan',
                'created_at',
                'updated_at'
            ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->where($paramData, $paramValue)
                ->join('antrian_nomor', array(
                    'id', 'poli', 'penjamin'
                ))
                ->join('pasien', array(
                    'nama'
                ))
                ->on(array(
                    array('invoice.kunjungan', '=', 'antrian_nomor.kunjungan'),
                    array('invoice.pasien', '=', 'pasien.uid')
                ))
                ->execute();
        }

        $dataResult = array();
        $data['response_draw'] = intval($parameter['draw']);
        $autonum = intval($parameter['start']) + 1;
        $Poli = new Poli(self::$pdo);
        $Pasien = new Pasien(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);
        $Anjungan = new Anjungan(self::$pdo);
        $Bed = new Bed(self::$pdo);
        $PoliListIdentifier = array();
        $PoliList = array();
        $total_split = 0;

        $InvDetailRanap = array();
        $TunggakanRanap = array();
        $RekapBiayaKamar = array();

        if($parameter['filter_poli'] === 'ranap') {
            $Ranap = self::$query->select('rawat_inap', array(
                'uid',
                'bed',
                'petugas',
                'pasien',
                'kunjungan',
                'waktu_masuk'
            ))
                ->where(array(
                    'rawat_inap.waktu_keluar' => 'IS NULL'
                ), array())
                ->execute();

            foreach ($Ranap['response_data'] as $key => $value) {
                //Dapatkan awal tagihan
                $INPAwal = self::$query->select('rawat_inap_biaya_kamar', array(
                    'logged_at',
                    'rawat_inap',
                    'petugas',
                    'bed',
                    'harga'
                ))
                    ->order(array(
                        'logged_at' => 'DESC'
                    ))
                    ->limit(1)
                    ->where(array(
                        'rawat_inap_biaya_kamar.status' => '= ?',
                        'AND',
                        'rawat_inap_biaya_kamar.rawat_inap' => '= ?',
                        'AND',
                        'rawat_inap_biaya_kamar.bed' => '= ?'
                    ), array(
                        'Y', $value['uid'], $value['bed']
                    ))
                    ->execute();

                $Awal = (count($INPAwal['response_data']) > 0) ? date('Y-m-d', strtotime($INPAwal['response_data'][0]['logged_at'] . ' +1 day')) : date('Y-m-d', strtotime($value['waktu_masuk']));

                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod(new \DateTime($Awal), $interval, ((new \DateTime(date('Y-m-d')) >= new \DateTime($Awal)) ? new \DateTime(date('Y-m-d', strtotime('+1 day'))) : new \DateTime(date('Y-m-d'))));

                foreach ($period as $dt) {
                    $INP = self::$query->select('rawat_inap_biaya_kamar', array(
                        'logged_at',
                        'rawat_inap',
                        'petugas',
                        'bed',
                        'harga'
                    ))
                        ->order(array(
                            'logged_at' => 'DESC'
                        ))
                        ->limit(1)
                        ->where(array(
                            'rawat_inap_biaya_kamar.rawat_inap' => '= ?',
                            'AND',
                            'rawat_inap_biaya_kamar.bed' => '= ?',
                            'AND',
                            'rawat_inap_biaya_kamar.logged_at::date' => '= date \'' . $dt->format('Y-m-d') . '\''
                        ), array(
                            $value['uid'], $value['bed']
                        ))
                        ->execute();
                    array_push($TunggakanRanap, $INP);
                    if(count($INP['response_data']) < 1) {

                        $InvDetail = self::$query->select('invoice', array(
                            'uid'
                        ))
                            ->where(array(
                                'invoice.pasien' => '= ?',
                                'AND',
                                'invoice.kunjungan' => '= ?'
                            ), array(
                                $value['pasien'],
                                $value['kunjungan']
                            ))
                            ->execute();

                        $InvDetailItem = self::$query->select('invoice_detail', array(
                            'id', 'penjamin'
                        ))
                            ->where(array(
                                'invoice_detail.invoice' => '= ?'
                            ), array(
                                $InvDetail['response_data'][0]['uid']
                            ))
                            ->limit(1)
                            ->execute();

                        $EntryLog = self::$query->insert('rawat_inap_biaya_kamar', array(
                            'logged_at' => $dt->format('Y-m-d'),
                            'rawat_inap' => $value['uid'],
                            'petugas' => (!isset($value['petugas'])) ? $UserData['data']->uid : $value['petugas'],
                            'bed' => $value['bed'],
                            'invoice' => $InvDetail['response_data'][0]['uid'],
                            'harga' => $Bed->get_bed_detail('master_unit_bed', $value['bed'])['response_data'][0]['tarif'],
                            'penjamin' => $InvDetailItem['response_data'][0]['penjamin'],
                            'status' => 'N'
                        ))
                            ->execute();
                        array_push($InvDetailRanap, $EntryLog);
                    }
                }





                $TotalKamar = array();
                $INP = self::$query->select('rawat_inap_biaya_kamar', array(
                    'id',
                    'logged_at',
                    'rawat_inap',
                    'petugas',
                    'bed',
                    'invoice',
                    'harga',
                    'penjamin'
                ))
                    ->order(array(
                        'logged_at' => 'ASC'
                    ))
                    ->join('rawat_inap', array(
                        'uid',
                        'pasien',
                        'kunjungan'
                    ))
                    ->on(array(
                        array('rawat_inap_biaya_kamar.rawat_inap', '=', 'rawat_inap.uid')
                    ))
                    ->where(array(
                        'rawat_inap_biaya_kamar.status' => '= ?',
                        'AND',
                        'rawat_inap_biaya_kamar.rawat_inap' => '= ?'
                    ), array(
                        'N', $value['uid']
                    ))
                    ->execute();
                foreach ($INP['response_data'] as $RIKey => $RIValue) {
                    if(!isset($TotalKamar[$RIValue['bed']])) {
                        $TotalKamar[$RIValue['bed']] = array(
                            'total' => 0,
                            'detail' => array(),
                            'tanggal_charge' => array()
                        );
                    }

                    $TotalKamar[$RIValue['bed']]['total'] += floatval($RIValue['harga']);
                    $TotalKamar[$RIValue['bed']]['detail'] = $RIValue;
                    array_push($TotalKamar[$RIValue['bed']]['tanggal_charge'], date('Y-m-d', strtotime($RIValue['logged_at'])));
                }
            }


            foreach ($TotalKamar as $key => $value) {
                $InvoiceItem = self::$query->select('invoice_detail', array(
                    'id',
                    'invoice',
                    'status_bayar',
                    'subtotal',
                    'departemen',
                    'keterangan',
                    'billing_group'
                ))
                    ->where(array(
                        'invoice_detail.invoice' => '= ?',
                        'AND',
                        'invoice_detail.deleted_at' => 'IS NULL',
                        'AND',
                        'invoice_detail.billing_group' => '= ?',
                        'AND',
                        'invoice_detail.status_bayar' => '= ?',
                        'AND',
                        'invoice_detail.item_type' => '= ?',
                        'AND',
                        'invoice_detail.item' => '= ?'
                    ), array(
                        $value['detail']['invoice'], 'tarif_kamar', 'N', 'master_unit_bed', $key
                    ))
                    ->execute();
                if(count($InvoiceItem['response_data']) > 0) {
                    $update_inv_item = self::$query->update('invoice_detail', array(
                        'harga' => floatval($value['detail']['harga']),
                        'subtotal' => $value['total'],
                        'keterangan' => 'Biaya Kamar Rawat Inap <br />' . implode('<br />', $value['tanggal_charge'])
                    ))
                        ->where(array(
                            'invoice_detail.id' => '= ?',
                            'AND',
                            'invoice_detail.invoice' => '= ?',
                            'AND',
                            'invoice_detail.deleted_at' => 'IS NULL',
                            'AND',
                            'invoice_detail.billing_group' => '= ?',
                            'AND',
                            'invoice_detail.status_bayar' => '= ?',
                            'AND',
                            'invoice_detail.item_type' => '= ?'
                        ), array(
                            $InvoiceItem['response_data'][0]['id'], $value['detail']['invoice'], 'tarif_kamar', 'N', 'master_unit_bed'
                        ))
                        ->execute();
                } else {
                    $update_inv_item = self::$query->insert('invoice_detail', array(
                        'invoice' => $value['detail']['invoice'],
                        'item' => $key,
                        'item_type' => 'master_unit_bed',
                        'qty' => 1,
                        'harga' => floatval($value['harga']),
                        'subtotal' => floatval($value['harga']),
                        'discount' => 0,
                        'discount_type' => 'N',
                        'keterangan' => 'Biaya Kamar Rawat Inap <br />' . implode('<br />', $value['tanggal_charge']),
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date(),
                        'status_bayar' => 'N',
                        'penjamin' => $value['detail']['penjamin'],
                        'pasien' => $value['detail']['pasien'],
                        'billing_group' => 'tarif_kamar',
                        'departemen' => __POLI_INAP__
                    ))
                        ->execute();
                }
            }
        }















        foreach ($data['response_data'] as $key => $value) {//Antrian Info
            $AntrianKunjungan = self::$query->select('antrian_nomor', array(
                'id',
                'nomor_urut',
                'loket',
                'pegawai',
                'kunjungan',
                'antrian',
                'pasien',
                'poli',
                'status',
                'anjungan',
                'jenis_antrian',
                'dokter',
                'penjamin'
            ))
                ->where(array(
                    'antrian_nomor.kunjungan' => '= ?',
                    'AND',
                    '(antrian_nomor.status' => '= ?',
                    'OR',
                    'antrian_nomor.status' => '= ?)'
                ), array(
                    $value['kunjungan'],
                    'K', 'V'
                ))
                ->execute();

            $PasienInfo = $Pasien->get_pasien_info('pasien', $value['pasien']);
            $value['pasien'] = $PasienInfo['response_data'][0];

            $value['created_at_parse'] = date('d F Y', strtotime($value['created_at']));

            $statusLunas = false;
            $departemen_terkait = array();


            //Detail Pembayaran
            $InvoiceDetail = self::$query->select('invoice_detail', array(
                'status_bayar',
                'subtotal',
                'departemen',
                'billing_group',
                'penjamin'
            ))
                ->join('master_penjamin', array(
                    'nama as nama_penjamin'
                ))
                ->on(array(
                    array('invoice_detail.penjamin', '=', 'master_penjamin.uid')
                ))
                ->where(array(
                    'invoice_detail.invoice' => '= ?',
                    'AND',
                    'invoice_detail.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();
            $IDautonum = 1;
            foreach ($InvoiceDetail['response_data'] as $IDKey => $IDValue) {
                if ($IDValue['status_bayar'] == 'Y') {
                    $statusLunas = true;
                } else {
                    $statusLunas = false;
                    break;
                }
            }

            foreach ($InvoiceDetail['response_data'] as $IDKey => $IDValue) {
                if(!in_array($IDValue['departemen'], $departemen_terkait)) {
                    array_push($departemen_terkait, $IDValue['departemen']);
                }
            }

            $value['lunas'] = $statusLunas;
            $value['departemen_terkait'] = $departemen_terkait;
            $value['invoice_detail'] = $InvoiceDetail['response_data'];

            $value['autonum'] = $autonum;
            $autonum++;

            if (count($AntrianKunjungan['response_data']) > 0) {
                foreach ($AntrianKunjungan['response_data'] as $AKKey => $AKValue) {
                    //Info Poliklinik
                    $PoliInfo = $Poli->get_poli_info($AKValue['poli']);


                    //Antrian Poli Populator
                    $PoliPopulator = self::$query->select('antrian', array(
                        'departemen',
                        'dokter'
                    ))
                        ->where(array(
                            'antrian.kunjungan' => '= ?',
                            'AND',
                            'antrian.deleted_at' => 'IS NULL'
                        ), array(
                            $AKValue['kunjungan']
                        ))
                        ->execute();
                    foreach ($PoliPopulator['response_data'] as $PoliPopKey => $PoliPopValue) {
                        $PoliPopulator['response_data'][$PoliPopKey]['dokter'] = $Pegawai->get_detail($PoliPopValue['dokter'])['response_data'][0];
                        $PoliPopulator['response_data'][$PoliPopKey]['poli'] = $Poli->get_poli_info($PoliPopValue['departemen'])['response_data'][0];
                    }
                    $AntrianKunjungan['response_data'][$AKKey]['poli_list'] = $PoliPopulator['response_data'];

                    $AntrianKunjungan['response_data'][$AKKey]['poli'] = $PoliInfo['response_data'][0];

                    //Info Pegawai
                    $PegawaiInfo = $Pegawai->get_info($AKValue['pegawai']);
                    $AntrianKunjungan['response_data'][$AKKey]['pegawai'] = $PegawaiInfo['response_data'][0];

                    //Info Loket
                    $AnjunganInfo = $Anjungan->get_loket_detail($AKValue['loket']);
                    $AntrianKunjungan['response_data'][$AKKey]['loket'] = $AnjunganInfo['response_data'][0];
                }
                $value['antrian_kunjungan'] = $AntrianKunjungan['response_data'][0];
                //$value['poli_list'] = $PoliList;

                array_push($dataResult, $value);
            } else {
                if($value['poli'] === __POLI_IGD__) {

                    $value['antrian_kunjungan'] = array(
                        'poli' => $Poli->get_poli_info(__POLI_IGD__)['response_data'][0],
                        'pegawai' => $Pegawai->get_info($UserData['data']->uid)['response_data'][0],
                        'penjamin' => $value['penjamin']
                    );
                    array_push($dataResult, $value);
                }
            }
        }

        $InvoiceTotal = self::$query->select('invoice', array(
            'uid'
        ))
            ->where($paramData, $paramValue)
            ->execute();
        $data['response_data'] = $dataResult;
        $data['recordsTotal'] = count($InvoiceTotal['response_data']);
        $data['recordsFiltered'] = count($dataResult);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);
        //$data['ranap'] = $Ranap;
        //$data['tunggakan'] = $TunggakanRanap;
        //$data['log_ranap'] = $InvDetailRanap;
        return $data;
    }

    public function get_payment($parameter)
    {
        $Pegawai = new Pegawai(self::$pdo);
        $Pasien = new Pasien(self::$pdo);
        $payment = self::$query->select('invoice_payment', array(
            'uid',
            'nomor_kwitansi',
            'pasien',
            'invoice',
            'pegawai',
            'terbayar',
            'sisa_bayar',
            'keterangan',
            'metode_bayar',
            'tanggal_bayar'
        ))
            ->where(array(
                'invoice_payment.deleted_at' => 'IS NULL',
                'AND',
                'invoice_payment.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();

        $InvoiceData = self::$query->select('invoice', array(
            'kunjungan',
            'pasien'
        ))
            ->where(array(
                'invoice.uid' => '= ?',
                'AND',
                'invoice.deleted_at' => 'IS NULL'
            ), array(
                $payment['response_data'][0]['invoice']
            ))
            ->execute();

        $Antrian = self::$query->select('antrian', array(
            'departemen'
        ))
            ->join('master_poli', array(
                'nama'
            ))
            ->on(array(
                array('antrian.departemen', '=', 'master_poli.uid')
            ))
            ->where(array(
                'antrian.kunjungan' => '= ?'
            ), array(
                $InvoiceData['response_data'][0]['kunjungan']
            ))
            ->execute();

        foreach ($payment['response_data'] as $key => $value) {
            //get payment detail
            $payment_detail = self::$query->select('invoice_payment_detail', array(
                'id',
                'invoice_payment',
                'item',
                'item_type',
                'qty',
                'harga',
                'subtotal',
                'discount',
                'discount_type',
                'billing_group',
                'keterangan',
                'status'
            ))
                ->where(array(
                    'invoice_payment_detail.deleted_at' => 'IS NULL',
                    'AND',
                    'invoice_payment_detail.invoice_payment' => '= ?'
                ), array(
                    $parameter
                ))
                ->execute();
            foreach ($payment_detail['response_data'] as $PDKey => $PDValue) {
                $Item = self::$query->select($PDValue['item_type'], array(
                    'nama'
                ))
                    ->where(array(
                        $PDValue['item_type'] . '.uid' => '= ?'
                    ), array(
                        $PDValue['item']
                    ))
                    ->execute();
                $payment_detail['response_data'][$PDKey]['item_uid'] = $PDValue['item'];
                $payment_detail['response_data'][$PDKey]['item'] = $Item['response_data'][0]['nama'];
                $payment_detail['response_data'][$PDKey]['qty'] = floatval($PDValue['qty']);
                $payment_detail['response_data'][$PDKey]['harga'] = floatval($PDValue['harga']);
                $payment_detail['response_data'][$PDKey]['subtotal'] = floatval($PDValue['subtotal']);
                $payment_detail['response_data'][$PDKey]['discount'] = floatval($PDValue['discount']);

                $allowReturn = false;
                if(
                    $PDValue['item'] === __UID_KARTU__
                ) {
                    $allowReturn = false;
                } else {
                    $KonsulLib = array();
                    //Get All Konsul Item Poli
                    $PoliKonsul = self::$query->select('master_poli', array(
                        'uid', 'tindakan_konsultasi', 'nama'
                    ))
                        ->where(array(
                            'master_poli.deleted_at' => 'IS NULL'
                        ))
                        ->execute();
                    foreach ($PoliKonsul['response_data'] as $PolKey => $PolValue) {
                        array_push($KonsulLib, $PolValue['tindakan_konsultasi']);
                    }

                    /*if(
                        $PDValue['item'] === __UIDKONSULDOKTER__ ||
                        $PDValue['item'] === __UIDKONSULDOKTER_GIGI__ ||
                        $PDValue['item'] === __UIDKONSULDOKTER_SPESIALIS__
                    ) {*/
                    if(in_array($PDValue['item'], $KonsulLib)) {
                        $AsesmenCheck = self::$query->select('asesmen', array(
                            'uid'
                        ))
                            ->where(array(
                                'asesmen.kunjungan' => '= ?',
                                'AND',
                                'asesmen.pasien' => '= ?'
                            ), array(
                                $InvoiceData['response_data'][0]['kunjungan'],
                                $InvoiceData['response_data'][0]['pasien']
                            ))
                            ->execute();
                        $payment_detail['response_data'][$PDKey]['asesmen_check'] = $AsesmenCheck;
                        if(count($AsesmenCheck['response_data']) > 0) {
                            $allowReturn = false;
                        } else {
                            $allowReturn = true;
                        }
                    } else {
                        $allowReturn = false;
                    }
                }
                $payment_detail['response_data'][$PDKey]['allow_retur'] = $allowReturn;
            }
            $payment['response_data'][$key]['detail'] = $payment_detail['response_data'];

            //Info Pegawai
            $PegawaiInfo = $Pegawai->get_detail($value['pegawai']);
            $payment['response_data'][$key]['pegawai'] = $PegawaiInfo['response_data'][0];
            $payment['response_data'][$key]['tanggal_bayar'] = date("d F Y", strtotime($value['tanggal_bayar']));
            $payment['response_data'][$key]['terbayar'] = floatval($value['terbayar']);
            $payment['response_data'][$key]['sisa_bayar'] = floatval($value['sisa_bayar']);
            $payment['response_data'][$key]['antrian'] = $Antrian['response_data'];
            $PasienInfo = $Pasien->get_pasien_detail('pasien', $value['pasien']);
            $payment['response_data'][$key]['pasien'] = $PasienInfo['response_data'][0];
        }

        return $payment;
    }

    private function proses_bayar($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $KunjunganUID = $parameter['kunjungan'];

        $newPaymentUID = parent::gen_uuid();
        $allowAntrian = false;
        $totalPayment = 0;

        $goto_apotek = false;
        $goto_poli = false;
        $goto_lab = false;
        $goto_rad = false;
        $KonsulListItem = array();
        $KonsulRequest = array();
        $Antrian = new Antrian(self::$pdo);
        $antrian_item = array();

        //List semua biaya konsultasi dari setting poli
        $poliKonsulPrice = self::$query->select('master_poli', array(
            'uid',
            'tindakan_konsultasi'
        ))
            ->where(array(
                'master_poli.deleted_at' => 'IS NULL'
            ))
            ->execute();

        foreach ($poliKonsulPrice['response_data'] as $PKKey => $PKValue) {
            if (!in_array($PKValue['tindakan_konsultasi'], $KonsulListItem)) {
                array_push($KonsulListItem, $PKValue['tindakan_konsultasi']);
            }
        }


        $ResepMaster = self::$query->select('resep', array(
            'uid',
            'asesmen'
        ))
            ->where(array(
                'resep.kunjungan' => '= ?',
                'AND',
                'resep.pasien' => '= ?',
                'AND',
                'resep.status_resep' => '= ?',
                'AND',
                'resep.deleted_at' => 'IS NULL',
            ), array(
                $parameter['kunjungan'],
                $parameter['pasien'],
                'K'
            ))
            ->execute();

        $RacikanMaster = self::$query->select('racikan', array(
            'uid',
            'asesmen'
        ))
            ->where(array(
                'racikan.asesmen' => '= ?',
                'AND',
                'racikan.deleted_at' => 'IS NULL',
            ), array(
                $ResepMaster['response_data'][0]['asesmen']
            ))
            ->execute();

        foreach ($parameter['invoice_item'] as $key => $value) { //Update status bayar pada invoice item
            $getPaymentDetail = self::$query->select('invoice_detail', array(
                'invoice',
                'item',
                'item_type',
                'qty',
                'harga',
                'subtotal',
                'discount',
                'discount_type',
                'keterangan',
                'subtotal',
                'status_bayar',
                'billing_group',
                'pasien'
            ))
                ->where(array(
                    'invoice_detail.deleted_at' => 'IS NULL',
                    'AND',
                    'invoice_detail.id' => '= ?',
                    'AND',
                    'invoice_detail.invoice' => '= ?'
                ), array(
                    $value,
                    $parameter['invoice']
                ))
                ->execute();

            //Payment Detail
            $paymentDetail = self::$query->insert('invoice_payment_detail', array(
                'invoice_payment' => $newPaymentUID,
                'item' => $getPaymentDetail['response_data'][0]['item'],
                'item_type' => $getPaymentDetail['response_data'][0]['item_type'],
                'qty' => $getPaymentDetail['response_data'][0]['qty'],
                'harga' => $getPaymentDetail['response_data'][0]['harga'],
                'subtotal' => $getPaymentDetail['response_data'][0]['subtotal'],
                'discount' => $getPaymentDetail['response_data'][0]['discount'],
                'discount_type' => $getPaymentDetail['response_data'][0]['discount_type'],
                'keterangan' => $getPaymentDetail['response_data'][0]['keterangan'],
                'billing_group' => $getPaymentDetail['response_data'][0]['billing_group'],
                'penjamin' => $parameter['penjamin'],
                'pasien' => $parameter['pasien'],
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();

            if ($paymentDetail['response_result'] > 0) { //Berhasil Bayar

                //Jika Resep maka ubah status jadi lunas agar apotek bisa proses obat
                if (
                    // count($ResepMaster['response_data']) > 0 &&
                    $getPaymentDetail['response_data'][0]['item_type'] == 'master_inv'
                ) { //Obat

                    foreach ($ResepMaster['response_data'] as $RMKey => $RMValue) {
                        $updateResep = self::$query->update('resep_detail', array(
                            'status' => 'L'
                        ))
                            ->where(array(
                                'resep_detail.obat' => '= ?',
                                'AND',
                                'resep_detail.resep' => '= ?',
                                'AND',
                                'resep_detail.deleted_at' => 'IS NULL'
                            ), array(
                                $getPaymentDetail['response_data'][0]['item'],
                                $RMValue['uid']
                            ))
                            ->execute();
                        foreach ($RacikanMaster['response_data'] as $RcMKey => $RcMValue) {
                            $updateRacikan = self::$query->update('racikan_detail', array(
                                'status' => 'L'
                            ))
                                ->where(array(
                                    'racikan_detail.racikan' => '= ?',
                                    'AND',
                                    'racikan_detail.asesmen' => '= ?',
                                    'AND',
                                    'racikan_detail.deleted_at' => 'IS NULL'
                                ), array(
                                    $RcMValue['uid'],
                                    $RMValue['asesmen']
                                ))
                                ->execute();
                        }
                    }

                    $goto_apotek = true;
                }

                if (
                    $getPaymentDetail['response_data'][0]['item_type'] == 'master_tindakan'
                ) { //Tindakan Check LAB / RAD
                    $TindakanInfo = self::$query->select('master_tindakan', array(
                        'uid',
                        'kelompok'
                    ))
                        ->where(array(
                            'master_tindakan.uid' => '= ?',
                            'AND',
                            'master_tindakan.deleted_at' => 'IS NULL'
                        ), array(
                            $getPaymentDetail['response_data'][0]['item']
                        ))
                        ->execute();
                    if(
                        $TindakanInfo['response_data'][0]['kelompok'] === 'LAB' ||
                        $TindakanInfo['response_data'][0]['kelompok'] === 'RAD'
                    ) {
                        $chargedItemTindakan = array();
                        $chargedOrder = array();
                        //Check Terbayar
                        $checkTindakan = self::$query->select(strtolower($TindakanInfo['response_data'][0]['kelompok']) . '_order', array(
                            'uid'
                        ))
                            ->where(array(
                                strtolower($TindakanInfo['response_data'][0]['kelompok']) . '_order.kunjungan' => '= ?',
                                'AND',
                                strtolower($TindakanInfo['response_data'][0]['kelompok']) . '_order.pasien' => '= ?',
                                'AND',
                                strtolower($TindakanInfo['response_data'][0]['kelompok']) . '_order.deleted_at' => 'IS NULL'
                            ), array(
                                $parameter['kunjungan'],
                                $parameter['pasien'],
                            ))
                            ->execute();
                        foreach ($checkTindakan['response_data'] as $chTKey => $chTValue) {
                            $getDetailTindakan = self::$query->select(strtolower($TindakanInfo['response_data'][0]['kelompok']) . '_order_detail', array(
                                'id',
                                'tindakan'
                            ))
                                ->where(array(
                                    (($TindakanInfo['response_data'][0]['kelompok'] === 'LAB') ? strtolower($TindakanInfo['response_data'][0]['kelompok']) . '_order_detail.lab_order' : strtolower($TindakanInfo['response_data'][0]['kelompok']) . '_order_detail.radiologi_order') => '= ?',
                                    'AND',
                                    strtolower($TindakanInfo['response_data'][0]['kelompok']) . '_order_detail.tindakan' => '= ?',
                                    'AND',
                                    strtolower($TindakanInfo['response_data'][0]['kelompok']) . '_order_detail.deleted_at' => 'IS NULL'
                                ), array(
                                    $chTValue['uid'],
                                    $getPaymentDetail['response_data'][0]['item']
                                ))
                                ->execute();

                            if(count($getDetailTindakan['response_data']) > 0) {
                                //Update Invoice in Order Detail
                                $updateInvoice = self::$query->update(strtolower($TindakanInfo['response_data'][0]['kelompok']) . '_order_detail', array(
                                    'invoice' => $parameter['invoice']
                                ))
                                    ->where(array(
                                        (($TindakanInfo['response_data'][0]['kelompok'] === 'LAB') ? strtolower($TindakanInfo['response_data'][0]['kelompok']) . '_order_detail.lab_order' : strtolower($TindakanInfo['response_data'][0]['kelompok']) . '_order_detail.radiologi_order') => '= ?',
                                        'AND',
                                        strtolower($TindakanInfo['response_data'][0]['kelompok']) . '_order_detail.tindakan' => '= ?',
                                        'AND',
                                        strtolower($TindakanInfo['response_data'][0]['kelompok']) . '_order_detail.id' => '= ?',
                                        'AND',
                                        strtolower($TindakanInfo['response_data'][0]['kelompok']) . '_order_detail.deleted_at' => 'IS NULL'
                                    ), array(
                                        $chTValue['uid'],
                                        $getPaymentDetail['response_data'][0]['item'],
                                        $getDetailTindakan['response_data'][0]['id']
                                    ))
                                    ->execute();
                                if($updateInvoice['response_result'] > 0) {
                                    if(!in_array($chTValue['uid'], $chargedItemTindakan)) {
                                        array_push($chargedItemTindakan, $chTValue['uid']);
                                    }

                                    if(!isset($chargedOrder[$chTValue['uid']])) {
                                        $chargedOrder[$chTValue['uid']] = array(
                                            'type' => $TindakanInfo['response_data'][0]['kelompok']
                                        );
                                    }
                                }
                            }
                        }


                        foreach ($chargedOrder as $chgKey => $chgValue) {
                            $updateTindakan = self::$query->update(strtolower($chgValue['type']) . '_order', array(
                                'status' => 'P'
                            ))
                                ->where(array(
                                    strtolower($chgValue['type']) . '_order.kunjungan' => '= ?',
                                    'AND',
                                    strtolower($chgValue['type']) . '_order.pasien' => '= ?',
                                    'AND',
                                    strtolower($chgValue['type']) . '_order.deleted_at' => 'IS NULL',
                                    'AND',
                                    strtolower($chgValue['type']) . '_order.uid' => '= ?',
                                ), array(
                                    $parameter['kunjungan'],
                                    $parameter['pasien'],
                                    $chgKey
                                ))
                                ->execute();
                        }

                        if($TindakanInfo['response_data'][0]['kelompok'] === 'LAB') {
                            $goto_lab = true;
                        }

                        if($TindakanInfo['response_data'][0]['kelompok'] === 'RAD') {
                            $goto_rad = true;
                        }
                    }
                }

                if (
                    $getPaymentDetail['response_data'][0]['item_type'] == 'master_unit_bed'
                ) { //Khusus Rawat Inap
                    $HutangKamar = self::$query->update('rawat_inap_biaya_kamar', array(
                        'status' => 'Y'
                    ))
                        ->where(array(
                            'rawat_inap_biaya_kamar.invoice' => '= ?',
                            'AND',
                            'rawat_inap_biaya_kamar.bed' => '= ?',
                            'AND',
                            'rawat_inap_biaya_kamar.penjamin' => '= ?'
                        ), array(
                            $getPaymentDetail['response_data'][0]['invoice'],
                            $getPaymentDetail['response_data'][0]['item'],
                            $parameter['penjamin']
                        ))
                        ->execute();
                }


                $totalPayment += floatval($getPaymentDetail['response_data'][0]['subtotal']);

                $updateInvoiceDetail = self::$query->update('invoice_detail', array(
                    'status_bayar' => 'Y',
                    'updated_at' => parent::format_date()
                ))
                    ->where(array(
                        'invoice_detail.deleted_at' => 'IS NULL',
                        'AND',
                        'invoice_detail.id' => '= ?',
                        'AND',
                        'invoice_detail.invoice' => '= ?'
                    ), array(
                        $value,
                        $parameter['invoice']
                    ))
                    ->execute();
                if ($updateInvoiceDetail['response_result'] > 0) {
                    $newPaymentData = $getPaymentDetail['response_data'][0];
                    $newPaymentData['status_bayar'] = 'Y';

                    $log = parent::log(array(
                        'type' => 'activity',
                        'column' => array(
                            'unique_target',
                            'user_uid',
                            'table_name',
                            'action',
                            'old_value',
                            'new_value',
                            'logged_at',
                            'status',
                            'login_id'
                        ),
                        'value' => array(
                            $value,
                            $UserData['data']->uid,
                            'invoice_detail',
                            'U',
                            json_encode($getPaymentDetail['response_data'][0]),
                            json_encode($newPaymentData),
                            parent::format_date(),
                            'N',
                            $UserData['data']->log_id
                        ),
                        'class' => __CLASS__
                    ));
                }
            }
        }

        if ($parameter['penjamin'] == __UIDPENJAMINUMUM__) {
            foreach ($parameter['invoice_item'] as $key => $value) {

                //Check Pembayaran Kartu dan Konsultasi
                $getPaymentResult = self::$query->select('invoice_detail', array(
                    'item',
                    'item_type',
                    'qty',
                    'harga',
                    'subtotal',
                    'discount',
                    'discount_type',
                    'keterangan',
                    'subtotal',
                    'penjamin',
                    'status_bayar',
                    'departemen',
                    'billing_group'
                ))
                    ->where(array(
                        'invoice_detail.deleted_at' => 'IS NULL',
                        'AND',
                        'invoice_detail.id' => '= ?',
                        'AND',
                        'invoice_detail.invoice' => '= ?'
                    ), array(
                        $value,
                        $parameter['invoice']
                    ))
                    ->execute();

                foreach ($getPaymentResult['response_data'] as $itemKonsulKey => $ItemKonsulValue) {
                    if (
                        $getPaymentResult['response_data'][$itemKonsulKey]['item'] !== __UID_KARTU__ &&
                        in_array($getPaymentResult['response_data'][$itemKonsulKey]['item'], $KonsulListItem) &&
                        $getPaymentResult['response_data'][$itemKonsulKey]['item_type'] == 'master_tindakan' &&
                        $getPaymentResult['response_data'][$itemKonsulKey]['billing_group'] == 'tindakan'
                    ) {
                        //Daftarkan poli selain poli awal
                        if(!in_array($getPaymentResult['response_data'][$itemKonsulKey]['departemen'], $KonsulRequest)) {
                            array_push($KonsulRequest, $getPaymentResult['response_data'][$itemKonsulKey]['departemen']);
                        }
                    }
                }

                if(count($KonsulRequest) > 0) { //Ada konsultasi lain
                    $allowAntrian = true;
                } else {
                    foreach ($getPaymentResult['response_data'] as $itemKonsulKey => $ItemKonsulValue) {
                        if ($getPaymentResult['response_data'][$itemKonsulKey]['item'] !== __UID_KARTU__) {
                            if (
                                in_array($getPaymentResult['response_data'][$itemKonsulKey]['item'], $KonsulListItem) &&
                                $getPaymentResult['response_data'][$itemKonsulKey]['item_type'] == 'master_tindakan' &&
                                $getPaymentResult['response_data'][$itemKonsulKey]['status_bayar'] == 'Y'
                            ) {
                                $allowAntrian = true;
                            } else {
                                $allowAntrian = false;
                                break;
                            }
                        }
                    }
                }

            }
        } else {
            $allowAntrian = true;
        }

        $checkStatusPasien = self::$query->select('antrian', array(
            'uid'
        ))
            ->where(array(
                'antrian.pasien' => '= ?',
                'AND',
                'antrian.deleted_at' => 'IS NULL'
            ), array(
                $parameter['pasien']
            ))
            ->execute();




        if ($allowAntrian) {
            if (count($checkStatusPasien['response_data']) > 0) {
                $allowAntrian = true;
            } else {
                $checkPayment = self::$query->select('invoice_payment_detail', array(
                    'id'
                ))
                    ->where(array(
                        'invoice_payment_detail.pasien' => '= ?',
                        'AND',
                        'invoice_payment_detail.item' => '= ?',
                        'AND',
                        'invoice_payment_detail.item_type' => '= ?',
                        'AND',
                        'invoice_payment_detail.deleted_at' => 'IS NULL'
                    ), array(
                        $parameter['pasien'],
                        __UID_KARTU__,
                        'master_tindakan'
                    ))
                    ->execute();

                if (count($checkPayment['response_data']) > 0) {
                    $allowAntrian = true;
                } else {
                    $allowAntrian = false;
                }
            }
        }

        //Update Invoice Discount and Total First
        //$parameter['discount']
        //$parameter['discount_type']

        if (count($checkStatusPasien['response_data']) > 0) {
            foreach ($ResepMaster['response_data'] as $RMKey => $RMValue) {
                $UpdateResepMaster = self::$query->update('resep', array(
                    'status_resep' => 'L',
                    'updated_at' => parent::format_date()
                ))
                    ->where(array(
                        'resep.kunjungan' => '= ?',
                        'AND',
                        'resep.pasien' => '= ?',
                        'AND',
                        'resep.uid' => '= ?',
                        'AND',
                        'resep.deleted_at' => 'IS NULL'
                    ), array(
                        $KunjunganUID,
                        $parameter['pasien'],
                        $RMValue['uid']
                    ))
                    ->execute();
                $UpdateRacikanMaster = self::$query->update('racikan', array(
                    'status' => 'L',
                    'updated_at' => parent::format_date()
                ))
                    ->where(array(
                        'racikan.asesmen' => '= ?',
                        'AND',
                        'racikan.deleted_at' => 'IS NULL'
                    ), array(
                        $RMValue['asesmen']
                    ))
                    ->execute();
            }
        }

        //Invoice before payment
        $InvoicePre = self::$query->select('invoice', array(
            'total_after_discount'
        ))
            ->where(array(
                'invoice.deleted_at' => 'IS NULL',
                'AND',
                'invoice.uid' => '= ?'
            ), array(
                $parameter['invoice']
            ))
            ->execute();


        if ($totalPayment > 0) {
            //Last Payment
            $paymentCount = self::$query->select('invoice_payment', array(
                'uid'
            ))
                ->where(array(
                    'EXTRACT(month FROM created_at)' => '= ?'
                ), array(
                    intval(date('m'))
                ))
                ->execute();

            $nomor_kwitansi = 'PBP/' . date('Y/m') . '/' . str_pad(strval(count($paymentCount['response_data']) + 1), 5, '0', STR_PAD_LEFT);
            $worker = self::$query->insert('invoice_payment', array(
                'uid' => $newPaymentUID,
                'invoice' => $parameter['invoice'],
                'nomor_kwitansi' => $nomor_kwitansi,
                'pasien' => $parameter['pasien'],
                'pegawai' => $UserData['data']->uid,
                'terbayar' => $totalPayment,
                'sisa_bayar' => (floatval($InvoicePre['response_data'][0]['total_after_discount']) - $totalPayment),
                'keterangan' => $parameter['keterangan'],
                'metode_bayar' => $parameter['metode'],
                'tanggal_bayar' => (isset($parameter['tanggal'])) ? $parameter['tanggal'] : date("Y-m-d"),
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();

            if ($worker['response_result'] > 0) {
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
                        $newPaymentUID,
                        $UserData['data']->uid,
                        'invoice_payment',
                        'I',
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                ));

                if ($allowAntrian) {
                    $KunjunganData = self::$query->select('antrian_nomor', array(
                        'dokter',
                        'prioritas'
                    ))
                        ->where(array(
                            'antrian_nomor.pasien' => '= ?',
                            'AND',
                            'antrian_nomor.kunjungan' => '= ?'
                        ), array(
                            $parameter['pasien'],
                            $parameter['kunjungan']
                        ))
                        ->execute();

                    if(count($KonsulRequest) > 0) {
                        foreach ($KonsulRequest as $KonsulKey => $KonsulValue) {
                            $parameter['dataObj'] = array(
                                'departemen' => $KonsulValue, //Ubah poli dulu sesuai dengan tagihan konsul
                                'pasien' => $parameter['pasien'],
                                'penjamin' => $parameter['penjamin'],
                                'prioritas' => $KunjunganData['response_data'][0]['prioritas'],
                                'dokter' => $KunjunganData['response_data'][0]['dokter']
                            );
                            $AntrianProses = $Antrian->tambah_antrian('antrian', $parameter, $parameter['kunjungan']);
                            array_push($antrian_item, $AntrianProses);
                        }
                    } else {
                        //Pembayaran Kartu Non Umum Segera masukkan pada antrian poliklinik

                        if($parameter['poli'] !== __POLI_IGD__) {

                            $parameter['dataObj'] = array(
                                'departemen' => $parameter['poli'], //Ubah poli dulu sesuai dengan tagihan konsul
                                'pasien' => $parameter['pasien'],
                                'penjamin' => $parameter['penjamin'],
                                'prioritas' => $KunjunganData['response_data'][0]['prioritas'],
                                'dokter' => $KunjunganData['response_data'][0]['dokter']
                            );
                            $AntrianProses = $Antrian->tambah_antrian('antrian', $parameter, $parameter['kunjungan']);
                            array_push($antrian_item, $AntrianProses);
                        }
                    }
                }
            }

            $notifier_target = array();
            if($allowAntrian) {
                array_push($notifier_target, array(
                    'target' => __UIDPERAWAT__,
                    'message' => 'Antrian poli baru',
                    'protocol' => 'antrian_poli_baru'
                ));
                $worker['response_message'] = 'Silahkan arahkan pasien menuju antrian poli';
            }
            if($goto_lab) {
                array_push($notifier_target, array(
                    'target' => __UIDPETUGASLAB__,
                    'message' => 'Permintaan pemeriksaan laboratorium',
                    'protocol' => 'antrian_laboratorium_baru'
                ));
                $worker['response_message'] = 'Silahkan arahkan pasien menuju laboratorium';
            }
            if($goto_rad) {
                array_push($notifier_target, array(
                    'target' => __UIDPETUGASRAD__,
                    'message' => 'Permintaan pemeriksaan radiologi',
                    'protocol' => 'antrian_radiologi_baru'
                ));
                $worker['response_message'] = 'Silahkan arahkan pasien menuju radiologi';
            }
            if($goto_rad && $goto_lab) {
                array_push($notifier_target, array(
                    'target' => __UIDPETUGASLAB__,
                    'message' => 'Permintaan pemeriksaan laboratorium',
                    'protocol' => 'antrian_laboratorium_baru'
                ));
                array_push($notifier_target, array(
                    'target' => __UIDPETUGASRAD__,
                    'message' => 'Permintaan pemeriksaan radiologi',
                    'protocol' => 'antrian_radiologi_baru'
                ));
                $worker['response_message'] = 'Silahkan arahkan pasien menuju radiologi lalu laboratorium';
            }
            if($goto_apotek) {
                array_push($notifier_target, array(
                    'target' => __UIDAPOTEKER__,
                    'message' => 'Antrian apotek baru',
                    'protocol' => 'antrian_apotek_baru'
                ));
                $worker['response_message'] = 'Silahkan arahkan pasien menuju apotek';
            } else {
                $worker['response_message'] = '';
            }
            $worker['parameter_list'] = $parameter;
            $worker['antrian_item'] = $antrian_item;
            $worker['konsul_request'] = $KonsulRequest;
            $worker['response_notifier'] = $notifier_target;
            return $worker;
        } else {
            return $allowAntrian;
        }

    }

    private function retur_biaya($parameter)
    {
        $PaymentUID = parent::gen_uuid();

        //Get Invoice
        $InvoicePayment = self::$query->select('invoice_payment', array(
            'uid',
            'invoice'
        ))
            ->where(array(
                'invoice_payment.invoice' => '= ?',
                'AND',
                'invoice_payment.uid' => '= ?'
            ), array(
                $parameter['invoice'],
                $parameter['payment']
            ))
            ->execute();
        if (count($InvoicePayment['response_data']) > 0) {
            $InvoiceData = self::$query->select('invoice', array(
                'kunjungan',
                'pasien'
            ))
                ->where(array(
                    'invoice.uid' => '= ?',
                    'AND',
                    'invoice.deleted_at' => 'IS NULL'
                ), array(
                    $InvoicePayment['response_data'][0]['invoice']
                ))
                ->execute();

            if(count($InvoiceData['response_data']) > 0) {
                $detailUpdate = array();
                foreach ($parameter['item'] as $key => $value) {
                    if($value !== __UID_KARTU__) { //Kartu tidak bisa return
                        $worker = self::$query->update('invoice_payment_detail', array(
                            'status' => 'R'
                        ))
                            ->where(array(
                                'invoice_payment_detail.invoice_payment' => '= ?',
                                'AND',
                                'invoice_payment_detail.item' => '= ?'
                            ), array(
                                $InvoicePayment['response_data'][0]['uid'],
                                $value
                            ))
                            ->execute();

                        $KonsulLib = array();
                        //Get All Konsul Item Poli
                        $PoliKonsul = self::$query->select('master_poli', array(
                            'uid', 'tindakan_konsultasi'
                        ))
                            ->where(array(
                                'master_poli.deleted_at' => 'IS NULL'
                            ))
                            ->execute();
                        foreach ($PoliKonsul['response_data'] as $PolKey => $PolValue) {
                            array_push($KonsulLib, $PolValue['tindakan_konsultasi']);
                        }

                        /*if(
                            $value === __UIDKONSULDOKTER__ ||
                            $value === __UIDKONSULDOKTER_GIGI__ ||
                            $value === __UIDKONSULDOKTER_SPESIALIS__
                        ) {*/

                        if(in_array($value, $KonsulLib)) {
                            //Check status asesmen. jika sudah asesmen tidak bisa return lagi
                            $AsesmenCheck = self::$query->select('asesmen', array(
                                'uid'
                            ))
                                ->where(array(
                                    'asesmen.kunjungan' => '= ?',
                                    'AND',
                                    'asesmen.pasien' => '= ?',
                                    'AND',
                                    'asesmen.deleted_at' => 'IS NULL',
                                    'AND',
                                    'DATE(asesmen.created_at)' => '= ?'
                                ), array(
                                    $InvoiceData['response_data'][0]['kunjungan'],
                                    $InvoiceData['response_data'][0]['pasien'],
                                    date('Y-m-d')
                                ))
                                ->execute();

                            if(count($AsesmenCheck['response_data']) > 0) {
                                //Sudah di asesmen, tidak bisa retur lagi
                                $worker['message'] = 'Sudah di asesmen, tidak bisa retur lagi';
                            } else {
                                //Cancel antrian
                                $updateKunjungan = self::$query->update('kunjungan', array(
                                    'waktu_keluar' => parent::format_date()
                                ))
                                    ->where(array(
                                        'kunjungan.uid' => '= ?',
                                        'AND',
                                        'kunjungan.deleted_at' => 'IS NULL'
                                    ), array(
                                        $InvoiceData['response_data'][0]['kunjungan']
                                    ))
                                    ->execute();

                                $updateAntrian = self::$query->update('antrian', array(
                                    'waktu_keluar' => parent::format_date(),
                                    'deleted_at' => parent::format_date()
                                ))
                                    ->where(array(
                                        'antrian.kunjungan' => '= ?',
                                        'AND',
                                        'antrian.pasien' => '= ?',
                                        'AND',
                                        'antrian.deleted_at' => 'IS NULL'
                                    ), array(
                                        $InvoiceData['response_data'][0]['kunjungan'],
                                        $InvoiceData['response_data'][0]['pasien']
                                    ))
                                    ->execute();

                                $worker['message'] = $updateAntrian;
                            }
                        } else {
                            $worker['message'] = 'Ada Kesalahan';
                        }
                        array_push($detailUpdate, $worker);
                    }
                }
                return $detailUpdate;
            } else {
                return $InvoiceData;
            }
        }
    }

    private function get_biaya_pasien()
    {
        $Pegawai = new Pegawai(self::$pdo);
        $data = self::$query->select('invoice', array(
            'uid',
            'nomor_invoice',
            'kunjungan',
            'pasien',
            'total_pre_discount',
            'discount',
            'discount_type',
            'total_after_discount',
            'keterangan',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'invoice.deleted_at' => 'IS NULL'
            ), array())
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {

            $Pasien = new Pasien(self::$pdo);
            $PasienInfo = $Pasien->get_pasien_detail('pasien', $value['pasien']);
            $data['response_data'][$key]['pasien'] = $PasienInfo['response_data'][0];


            $statusLunas = false;
            //Detail Pembayaran
            $InvoiceDetail = self::$query->select('invoice_detail', array(
                'status_bayar'
            ))
                ->where(array(
                    'invoice_detail.invoice' => '= ?',
                    'AND',
                    'invoice_detail.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();
            $IDautonum = 1;
            foreach ($InvoiceDetail['response_data'] as $IDKey => $IDValue) {
                if ($IDValue['status_bayar'] == 'Y') {
                    $statusLunas = true;
                } else {
                    $statusLunas = false;
                    break;
                }
            }

            $data['response_data'][$key]['lunas'] = $statusLunas;

            //Antrian Info
            $AntrianKunjungan = self::$query->select('antrian_nomor', array(
                'id',
                'nomor_urut',
                'loket',
                'pegawai',
                'kunjungan',
                'antrian',
                'pasien',
                'poli',
                'status',
                'anjungan',
                'jenis_antrian',
                'dokter',
                'penjamin'
            ))
                ->where(array(
                    'antrian_nomor.kunjungan' => '= ?',
                    'AND',
                    'antrian_nomor.status' => '= ?'
                ), array(
                    $value['kunjungan'],
                    'K'
                ))
                ->execute();
            foreach ($AntrianKunjungan['response_data'] as $AKKey => $AKValue) {
                //Info Poliklinik
                $Poli = new Poli(self::$pdo);
                $PoliInfo = $Poli->get_poli_detail($AKValue['poli']);
                $AntrianKunjungan['response_data'][$AKKey]['poli'] = $PoliInfo['response_data'][0];

                //Info Pegawai

                $PegawaiInfo = $Pegawai->get_detail($AKValue['pegawai']);
                $AntrianKunjungan['response_data'][$AKKey]['pegawai'] = $PegawaiInfo['response_data'][0];

                //Info Loket
                $Anjungan = new Anjungan(self::$pdo);
                $AnjunganInfo = $Anjungan::get_loket_detail($AKValue['loket']);
                $AntrianKunjungan['response_data'][$AKKey]['loket'] = $AnjunganInfo['response_data'][0];
            }

            $data['response_data'][$key]['antrian_kunjungan'] = $AntrianKunjungan['response_data'][0];

            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }
        return $data;
    }


    public function get_biaya_pasien_detail($parameter)
    {

        $data = self::$query->select('invoice', array(
            'uid',
            'nomor_invoice',
            'kunjungan',
            'pasien',
            'total_pre_discount',
            'discount',
            'discount_type',
            'total_after_discount',
            'keterangan',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'invoice.deleted_at' => 'IS NULL',
                'AND',
                'invoice.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();

        $autonum = 1;
        $Poli = new Poli(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);
        $Anjungan = new Anjungan(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);
        $Pasien = new Pasien(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $PasienInfo = $Pasien->get_pasien_detail('pasien', $value['pasien']);
            $data['response_data'][$key]['pasien'] = $PasienInfo['response_data'][0];

            //Antrian Info
            $AntrianKunjungan = self::$query->select('antrian_nomor', array(
                'id',
                'nomor_urut',
                'loket',
                'pegawai',
                'kunjungan',
                'antrian',
                'pasien',
                'poli',
                'status',
                'anjungan',
                'jenis_antrian',
                'dokter',
                'penjamin'
            ))
                ->where(array(
                    'antrian_nomor.kunjungan' => '= ?',
                    'AND',
                    'antrian_nomor.status' => '= ?'
                ), array(
                    $value['kunjungan'],
                    'K'
                ))
                ->execute();

            //Asesmen Information
            $Asesmen = self::$query->select('asesmen', array(
                'status'
            ))
                ->where(array(
                    'asesmen.kunjungan' => '= ?'
                ), array(
                    $value['kunjungan']
                ))
                ->execute();

            foreach ($AntrianKunjungan['response_data'] as $AKKey => $AKValue) {
                //Info Poliklinik
                $PoliInfo = $Poli->get_poli_info($AKValue['poli']);
                $AntrianKunjungan['response_data'][$AKKey]['poli'] = $PoliInfo['response_data'][0];

                //Info Pegawai
                $PegawaiInfo = $Pegawai->get_info($AKValue['pegawai']);
                $AntrianKunjungan['response_data'][$AKKey]['pegawai'] = $PegawaiInfo['response_data'][0];

                //Info Loket
                $AnjunganInfo = $Anjungan->get_loket_detail($AKValue['loket']);
                $AntrianKunjungan['response_data'][$AKKey]['loket'] = $AnjunganInfo['response_data'][0];
            }
            $data['response_data'][$key]['antrian_kunjungan'] = $AntrianKunjungan['response_data'][0];

            //Detail Pembayaran
            $InvoiceDetail = self::$query->select('invoice_detail', array(
                'id',
                'invoice',
                'item',
                'item_type',
                'qty',
                'status_bayar',
                'harga',
                'subtotal',
                'discount',
                'discount_type',
                'penjamin',
                'billing_group',
                'keterangan',
                'created_at',
                'updated_at',
                'departemen'
            ))
                ->where(array(
                    'invoice_detail.invoice' => '= ?',
                    'AND',
                    'invoice_detail.deleted_at' => 'IS NULL'
                ), array(
                    $parameter
                ))
                ->execute();
            $IDautonum = 1;
            foreach ($InvoiceDetail['response_data'] as $IDKey => $IDValue) {
                //Item parse
                $Item = self::$query->select($IDValue['item_type'], array(
                    'uid',
                    'nama'
                ))
                    ->where(array(
                        $IDValue['item_type'] . '.uid' => '= ?'
                    ), array(
                        $IDValue['item']
                    ))
                    ->execute();


                $PenjaminInfo = $Penjamin->get_penjamin_detail($IDValue['penjamin']);
                $InvoiceDetail['response_data'][$IDKey]['penjamin'] = $PenjaminInfo['response_data'][0];
                $InvoiceDetailPoliInfo = $Poli->get_poli_info($IDValue['departemen']);
                $InvoiceDetail['response_data'][$IDKey]['departemen_info'] = count($InvoiceDetailPoliInfo['response_data']) > 0 ? $InvoiceDetailPoliInfo['response_data'][0] : array(
                    'uid' => $IDValue['departemen'],
                    'nama' => ($IDValue['departemen'] === __POLI_INAP__) ? 'Rawat Inap' : 'IGD'
                );
                $InvoiceDetail['response_data'][$IDKey]['item'] = $Item['response_data'][0];
                $InvoiceDetail['response_data'][$IDKey]['item']['allow_retur'] = ($IDValue['item'] == __UID_KARTU__) ? false : true;
                $InvoiceDetail['response_data'][$IDKey]['status_berobat'] = $Asesmen['response_data'][0];
                $InvoiceDetail['response_data'][$IDKey]['qty'] = floatval($IDValue['qty']);
                $InvoiceDetail['response_data'][$IDKey]['harga'] = floatval($IDValue['harga']);
                $InvoiceDetail['response_data'][$IDKey]['discount'] = floatval($IDValue['discount']);
                $InvoiceDetail['response_data'][$IDKey]['subtotal'] = floatval($IDValue['subtotal']);

                $InvoiceDetail['response_data'][$IDKey]['autonum'] = $IDautonum;
                $IDautonum++;
            }
            $data['response_data'][$key]['invoice_detail'] = $InvoiceDetail['response_data'];


            //History payment
            $history = self::$query->select('invoice_payment', array(
                'uid',
                'nomor_kwitansi',
                'invoice',
                'pegawai',
                'pasien',
                'terbayar',
                'sisa_bayar',
                'keterangan',
                'metode_bayar',
                'tanggal_bayar'
            ))
                ->where(array(
                    'invoice_payment.deleted_at' => 'IS NULL',
                    'AND',
                    'invoice_payment.pasien' => '= ?'
                ), array(
                    $value['pasien']
                ))
                ->execute();
            $Hautonum = 1;
            foreach ($history['response_data'] as $HKey => $HValue) {
                $PegawaiInfo = $Pegawai->get_info($AKValue['pegawai']);

                $history['response_data'][$HKey]['tanggal_bayar'] = date('d F Y', strtotime($HValue['tanggal_bayar']));
                $history['response_data'][$HKey]['pegawai'] = $PegawaiInfo['response_data'][0];
                $history['response_data'][$HKey]['autonum'] = $Hautonum;

                $Hautonum++;
            }
            $data['response_data'][$key]['history'] = $history['response_data'];

            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }
        return $data;
    }

    public function create_invoice($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        //GET Last Invoice
        $lastNumber = self::$query->select('invoice', array(
            'nomor_invoice'
        ))
            ->where(array(
                'EXTRACT(month FROM created_at)' => '= ?'
            ), array(
                intval(date('m'))
            ))
            ->execute();

        $InvoiceUID = parent::gen_uuid();
        $Invoice = self::$query->insert('invoice', array(
            'uid' => $InvoiceUID,
            'nomor_invoice' => 'INV/' . date('Y/m') . '/' . str_pad(strval(count($lastNumber['response_data']) + 1), 4, '0', STR_PAD_LEFT),
            'kunjungan' => $parameter['kunjungan'],
            'pasien' => $parameter['pasien'],
            'keterangan' => $parameter['keterangan'],
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->execute();

        if ($Invoice['response_result'] > 0) {
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
                    $InvoiceUID,
                    $UserData['data']->uid,
                    'invoice',
                    'I',
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class' => __CLASS__
            ));
            $Invoice['response_unique'] = $InvoiceUID;
        }

        return $Invoice;
    }

    public function append_invoice($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $Invoice = self::$query->insert('invoice_detail', array(
            'invoice' => $parameter['invoice'],
            'item' => $parameter['item'],
            'item_type' => $parameter['item_origin'],
            'qty' => $parameter['qty'],
            'harga' => $parameter['harga'],
            'status_bayar' => isset($parameter['status_bayar']) ? $parameter['status_bayar'] : 'N',
            'subtotal' => $parameter['subtotal'],
            'discount' => $parameter['discount'],
            'discount_type' => $parameter['discount_type'],
            'pasien' => $parameter['pasien'],
            'penjamin' => $parameter['penjamin'],
            'billing_group' => $parameter['billing_group'],
            'keterangan' => $parameter['keterangan'],
            'document' => (isset($parameter['document'])) ? $parameter['document'] : '-',
            'departemen' => $parameter['departemen'],
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->returning('id')
            ->execute();
        if ($Invoice['response_result'] > 0) {
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
                    $Invoice['response_unique'],
                    $UserData['data']->uid,
                    'invoice_detail',
                    'I',
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class' => __CLASS__
            ));


            //Calculate Invoice Master Total
            $masterInvoice = self::$query->select('invoice', array(
                'total_pre_discount',
                'discount',
                'discount_type',
                'total_after_discount'
            ))
                ->where(array(
                    'invoice.deleted_at' => 'IS NULL',
                    'AND',
                    'invoice.uid' => '= ?'
                ), array(
                    $parameter['invoice']
                ))
                ->execute();
            $total_after_discount = 0;
            $total_pre_discount = $masterInvoice['response_data'][0]['total_pre_discount'] + $parameter['subtotal'];
            if ($masterInvoice['response_data'][0]['discount_type'] == 'P') {
                $total_after_discount = $total_pre_discount - ($masterInvoice['response_data'][0]['discount'] / 100 * $total_pre_discount);
            } else if ($masterInvoice['response_data'][0]['discount_type'] == 'A') {
                $total_after_discount = $total_pre_discount - $masterInvoice['response_data'][0]['discount'];
            } else {
                $total_after_discount = $total_pre_discount;
            }

            $updateInvoice = self::$query->update('invoice', array(
                'total_pre_discount' => $total_pre_discount,
                'total_after_discount' => $total_after_discount,
                'updated_at' => parent::format_date()
            ))
                ->where(array(
                    'invoice.deleted_at' => 'IS NULL',
                    'AND',
                    'invoice.uid' => '= ?'
                ), array(
                    $parameter['invoice']
                ))
                ->execute();

            if ($updateInvoice['response_result'] > 0) {
                $log = parent::log(array(
                    'type' => 'activity',
                    'column' => array(
                        'unique_target',
                        'user_uid',
                        'table_name',
                        'action',
                        'logged_at',
                        'old_value',
                        'new_value',
                        'status',
                        'login_id'
                    ),
                    'value' => array(
                        $Invoice['response_unique'],
                        $UserData['data']->uid,
                        'invoice_detail',
                        'U',
                        parent::format_date(),
                        json_encode($masterInvoice['response_data'][0]),
                        json_encode($parameter),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                ));
            }
        }

        return $Invoice;
    }

    public function get_harga_tindakan($parameter)
    {
        /*$harga = self::$query->select('master_poli_tindakan_penjamin', array(
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
            'master_poli_tindakan_penjamin.uid_poli' => '= ?',
            'AND',
            'master_poli_tindakan_penjamin.uid_tindakan' => '= ?',
            'AND',
            'master_poli_tindakan_penjamin.uid_penjamin' => '= ?'),
        array(
            $parameter['poli'],
            $parameter['tindakan'],
            $parameter['penjamin']
        ))
        ->execute();*/

        return self::$query->select('master_tindakan_kelas_harga', array(
            'id',
            'tindakan',
            'penjamin',
            'kelas',
            'harga',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'master_tindakan_kelas_harga.deleted_at' => 'IS NULL',
                'AND',
                'master_tindakan_kelas_harga.tindakan' => '= ?',
                'AND',
                'master_tindakan_kelas_harga.penjamin' => '= ?',
                'AND',
                'master_tindakan_kelas_harga.kelas' => '= ?'
            ), array(
                $parameter['tindakan'],
                $parameter['penjamin'],
                $parameter['kelas']
            ))
            ->execute();;
    }
}