<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Invoice as Invoice;
use PondokCoder\Penjamin as Penjamin;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Inap extends Utility
{
    static $pdo;
    static $query;

    protected static function getConn ()
    {
        return self::$pdo;
    }

    public function __construct ($connection)
    {
        self::$pdo = $connection;
        self::$query = new Query(self::$pdo);
    }

    public function __GET__ ($parameter = array())
    {
        switch ($parameter[1])
        {
            case 'detail_ns':
                return self::get_ns_detail($parameter[2]);
                break;
            case 'sedia_obat':
                return self::sedia_obat(array(
                    'resep' => $parameter[2],
                    'pasien' => $parameter[3],
                    'obat' => $parameter[4]
                ));
                break;
            case 'tagihan_pra_inap':
                return self::tagihan_pra_inap($parameter);
                break;
            default:
                return array();
                break;
        }
    }

    public function __DELETE__($parameter = array())
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if($parameter[6] === 'nurse_station') {
            $process = self::$query->delete('nurse_station')
                ->where(array(
                    'nurse_station.uid' => '= ?'
                ), array(
                    $parameter[7]
                ))
                ->execute();
            if($process['response_result'] > 0) {
                $Ranjang = self::$query->delete('nurse_station_ranjang')
                    ->where(array(
                        'nurse_station_ranjang.nurse_station' => '= ?'
                    ), array(
                        $parameter[7]
                    ))
                    ->execute();

                $Petugas = self::$query->delete('nurse_station_petugas')
                    ->where(array(
                        'nurse_station_petugas.nurse_station' => '= ?'
                    ), array(
                        $parameter[7]
                    ))
                    ->execute();
                    
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
                        $parameter[7],
                        $UserData['data']->uid,
                        'nurse_station',
                        'D',
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                ));
            }
            return $process;
        } else {
            return array();
        }
    }

    public function __POST__ ($parameter = array())
    {
        switch ($parameter['request'])
        {
            case 'tambah_inap':
                return self::tambah_inap($parameter);
                break;
            case 'update_inap':
                return self::edit_inap($parameter);
                break;
            case 'get_rawat_inap':
                return self::get_all($parameter);
                break;
            case 'tambah_asesmen':
                return self::tambah_asesmen($parameter);
                break;
            case 'pulangkan_pasien':
                return self::pulangkan_pasien($parameter);
                break;
            case 'get_nurse_station':
                return self::get_nurse_station($parameter);
                break;
            case 'tambah_nurse_station':
                return self::tambah_nurse_station($parameter);
                break;
            case 'edit_nurse_station':
                return self::edit_nurse_station($parameter);
                break;
            case 'tambah_riwayat_resep_inap':
                return self::tambah_riwayat_resep_inap($parameter);
                break;
            case 'riwayat_obat_inap':
                return self::riwayat_obat_inap($parameter);
                break;
            case 'kalkulasi_sisa_obat':
                return self::kalkulasi_sisa_obat($parameter);
                break;
            case 'kalkulasi_sisa_obat_2':
                return self::kalkulasi_sisa_obat_2($parameter);
                break;
            case 'konfirmasi_retur_obat':
                return self::konfirmasi_retur_obat($parameter);
                break;
            default:
                return self::get_all($parameter);
        }
    }

    private function tagihan_pra_inap($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $Tindakan = new Tindakan(self::$pdo);

        $data = self::$query->select('rawat_inap', array(
            'uid',
            'pasien',
            'waktu_masuk',
            'waktu_keluar',
            'kamar',
            'kunjungan',
            'bed',
            'keterangan',
            'dokter',
            'penjamin',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'rawat_inap.uid' => '= ?'
            ), array(
                $parameter[2]
            ))
            ->execute();
        foreach ($data['response_data'] as $key => $value) {
            $ApotekVerif = self::$query->select('resep', array(
                'status_resep', 'kode'
            ))
                ->where(array(
                    'resep.kunjungan' => '= ?',
                    'AND',
                    'resep.pasien' => '= ?'
                ), array(
                    $value['kunjungan'], $value['pasien']
                ))
                ->execute();
            foreach ($ApotekVerif['response_data'] as $ApKey => $ApValue) {
                $BiayaKasir = self::$query->select('invoice', array(
                    'uid'
                ))
                    ->join('invoice_detail', array(
                        'item', 'subtotal', 'status_bayar'
                    ))
                    ->on(array(
                        array('invoice_detail.invoice', '=', 'invoice.uid')
                    ))
                    ->where(array(
                        'invoice.kunjungan' => '= ?',
                        'AND',
                        'invoice_detail.billing_group' => '= ?'
                    ), array(
                        $value['kunjungan'],
                        'obat'
                    ))
                    ->execute();
                $ApotekVerif['response_data'][$ApKey]['biaya'] = $BiayaKasir['response_data'];
            }
            $data['response_data'][$key]['tagihan_apotek'] = $ApotekVerif['response_data'];





            //Laboratorium
            $LaborVerif = self::$query->select('lab_order', array(
                'uid', 'status', 'no_order'
            ))
                ->where(array(
                    'lab_order.kunjungan' => '= ?',
                    'AND',
                    'lab_order.pasien' => '= ?'
                ), array(
                    $value['kunjungan'], $value['pasien']
                ))
                ->execute();
            foreach ($LaborVerif['response_data'] as $LbKey => $LbValue) {
                $BiayaKasir = self::$query->select('invoice', array(
                    'uid'
                ))
                    ->join('invoice_detail', array(
                        'item', 'subtotal'
                    ))
                    ->on(array(
                        array('invoice_detail.invoice', '=', 'invoice.uid')
                    ))
                    ->where(array(
                        'invoice.kunjungan' => '= ?',
                        'AND',
                        'invoice_detail.billing_group' => '= ?'
                    ), array(
                        $value['kunjungan'],
                        'laboratorium'
                    ))
                    ->execute();
                $ExistLabor = self::$query->select('lab_order_detail', array(
                    'id'
                ))
                    ->where(array(
                        'lab_order_detail.lab_order' => '= ?',
                        'AND',
                        'lab_order_detail.deleted_at' => 'IS NULL'
                    ), array(
                        $LbValue['uid']
                    ))
                    ->execute();
                $LaborVerif['response_data'][$LbKey]['detail'] = $ExistLabor['response_data'];
                $LaborVerif['response_data'][$LbKey]['biaya'] = $BiayaKasir['response_data'];
            }
            $data['response_data'][$key]['tagihan_laboratorium'] = $LaborVerif['response_data'];

            //Radiologi
            $RadioVerif = self::$query->select('rad_order', array(
                'status', 'no_order'
            ))
                ->where(array(
                    'rad_order.kunjungan' => '= ?',
                    'AND',
                    'rad_order.pasien' => '= ?'
                ), array(
                    $value['kunjungan'], $value['pasien']
                ))
                ->execute();
            foreach ($RadioVerif['response_data'] as $RdKey => $RdValue) {
                $BiayaKasir = self::$query->select('invoice', array(
                    'uid'
                ))
                    ->join('invoice_detail', array(
                        'item', 'subtotal'
                    ))
                    ->on(array(
                        array('invoice_detail.invoice', '=', 'invoice.uid')
                    ))
                    ->where(array(
                        'invoice.kunjungan' => '= ?',
                        'AND',
                        'invoice_detail.billing_group' => '= ?'
                    ), array(
                        $value['kunjungan'],
                        'radiologi'
                    ))
                    ->execute();
                $ExistRadio = self::$query->select('rad_order_detail', array(
                    'id'
                ))
                    ->where(array(
                        'rad_order_detail.radiologi_order' => '= ?',
                        'AND',
                        'rad_order_detail.deleted_at' => 'IS NULL'
                    ), array(
                        $RdValue['uid']
                    ))
                    ->execute();
                $RadioVerif['response_data'][$RdKey]['detail'] = $ExistRadio['response_data'];
                $RadioVerif['response_data'][$RdKey]['biaya'] = $BiayaKasir['response_data'];
            }
            $data['response_data'][$key]['tagihan_radiologi'] = $RadioVerif['response_data'];


            $BiayaKasir = self::$query->select('invoice', array(
                'uid'
            ))
                ->join('invoice_detail', array(
                    'item', 'subtotal', 'status_bayar'
                ))
                ->on(array(
                    array('invoice_detail.invoice', '=', 'invoice.uid')
                ))
                ->where(array(
                    'invoice.kunjungan' => '= ?',
                    'AND',
                    'invoice_detail.billing_group' => '= ?'
                ), array(
                    $value['kunjungan'],
                    'administrasi'
                ))
                ->execute();
            foreach ($BiayaKasir['response_data'] as $AdmKey => $AdmValue) {
                $BiayaKasir['response_data'][$AdmKey]['item'] = $Tindakan->get_tindakan_info($AdmValue['item'])['response_data'][0];
            }
            $data['response_data'][$key]['administrasi'] = $BiayaKasir['response_data'];

            $BiayaKasir = self::$query->select('invoice', array(
                'uid'
            ))
                ->join('invoice_detail', array(
                    'item', 'subtotal', 'status_bayar'
                ))
                ->on(array(
                    array('invoice_detail.invoice', '=', 'invoice.uid')
                ))
                ->where(array(
                    'invoice.kunjungan' => '= ?',
                    'AND',
                    'invoice_detail.billing_group' => '= ?'
                ), array(
                    $value['kunjungan'],
                    'tindakan'
                ))
                ->execute();
            foreach ($BiayaKasir['response_data'] as $AdmKey => $AdmValue) {
                $BiayaKasir['response_data'][$AdmKey]['item'] = $Tindakan->get_tindakan_info($AdmValue['item'])['response_data'][0];
            }
            $data['response_data'][$key]['tindakan'] = $BiayaKasir['response_data'];
        }

        return $data;
    }

    private function sedia_obat($parameter) {
        $data = self::$query->select('rawat_inap_batch', array(
            'qty',
            'batch',
            'resep'
        ))
            ->where(array(
                'rawat_inap_batch.resep' => '= ?',
                'AND',
                'rawat_inap_batch.pasien' => '= ?',
                'AND',
                'rawat_inap_batch.obat' => '= ?'
            ), array(
                $parameter['resep'],
                $parameter['pasien'],
                $parameter['obat']
            ))
            ->execute();
        $Inventori = new Inventori(self::$pdo);
        $filtered = array();
        foreach ($data['response_data'] as $key => $value) {
            if(floatval($value['qty']) > 0 && $value['resep'] === $parameter['resep']) {
                $data['response_data'][$key]['batch'] = $Inventori->get_batch_detail($value['batch'])['response_data'][0];
                $data['response_data'][$key]['batch']['expired_date_parsed'] = date('d F Y', strtotime($data['response_data'][$key]['batch']['expired_date']));

                array_push($filtered, $data['response_data'][$key]);
            }
        }
        $data['response_data'] = $filtered;
        return $data;
    }

    public function get_ns_detail($parameter) {
        $allowManage = true;
        $Bed = new Bed(self::$pdo);
        $Ruangan = new Ruangan(self::$pdo);
        $data = self::$query->select('nurse_station', array(
            'uid',
            'kode',
            'nama',
            'unit',
            'created_at',
            'updated_at'
        ))
            ->join('master_unit', array(
                'uid as uid_unit',
                'nama as nama_unit',
                'kode as kode_unit',
                'gudang'
            ))
            ->join('master_inv_gudang', array(
                'uid as uid_gudang',
                'nama as nama_gudang'
            ))
            ->on(array(
                array('nurse_station.unit', '=', 'master_unit.uid'),
                array('master_unit.gudang', '=', 'master_inv_gudang.uid')
            ))
            ->where(array(
                'nurse_station.deleted_at' => 'IS NULL',
                'AND',
                'master_inv_gudang.deleted_at' => 'IS NULL',
                'AND',
                'nurse_station.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            //Get Ranjang
            $Ranjang = self::$query->select('nurse_station_ranjang', array(
                'ranjang'
            ))
                ->where(array(
                    'nurse_station_ranjang.deleted_at' => 'IS NULL',
                    'AND',
                    'nurse_station_ranjang.nurse_station' => '= ?'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach ($Ranjang['response_data'] as $RK => $RV) {
                //Check Ketersediaan Ranjang
                $CheckRanjang = self::$query->select('rawat_inap', array(
                    'pasien',
                    'dokter'
                ))
                    ->join('pasien', array(
                        'nama as nama_pasien'
                    ))
                    ->on(array(
                        array('rawat_inap.pasien', '=', 'pasien.uid')
                    ))
                    ->where(array(
                        'rawat_inap.deleted_at' => 'IS NULL',
                        'AND',
                        'rawat_inap.bed' => '= ?',
                        'AND',
                        'rawat_inap.nurse_station' => '= ?',
                        'AND',
                        'rawat_inap.waktu_keluar' => 'IS NULL'
                    ), array(
                        $RV['ranjang'],
                        $value['uid']
                    ))
                    ->execute();
                if(count($CheckRanjang['response_data']) > 0) {
                    if($allowManage) {
                        $allowManage = false;
                    }
                }
                $Ranjang['response_data'][$RK]['status'] = $CheckRanjang['response_data'][0];
                $Ranjang['response_data'][$RK]['detail'] = $Bed->get_bed_detail('master_unit_bed', $RV['ranjang'])['response_data'][0];
                $Ranjang['response_data'][$RK]['detail']['ruangan_detail'] = $Ruangan->get_ruangan_detail('master_unit_ruangan', $Ranjang['response_data'][$RK]['detail']['uid_ruangan'])['response_data'][0];

            }
            $data['response_data'][$key]['ranjang'] = $Ranjang['response_data'];

            //Get Petugas
            $Petugas = self::$query->select('nurse_station_petugas', array(
                'petugas'
            ))
                ->join('pegawai', array(
                    'nama as nama_petugas',
                    'jabatan'
                ))
                ->join('pegawai_jabatan', array(
                    'nama as nama_jabatan'
                ))
                ->on(array(
                    array('nurse_station_petugas.petugas', '=', 'pegawai.uid'),
                    array('pegawai.jabatan', '=', 'pegawai_jabatan.uid')
                ))
                ->where(array(
                    'nurse_station_petugas.deleted_at' => 'IS NULL',
                    'AND',
                    'nurse_station_petugas.nurse_station' => '= ?'
                ), array(
                    $value['uid']
                ))
                ->execute();
            $data['response_data'][$key]['petugas'] = $Petugas['response_data'];

            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }
        $data['allow_manage'] = $allowManage;
        return $data;
    }

    private function duplicate_check($parameter)
    {
        return self::$query
            ->select($parameter['table'], array(
                'uid',
                'nama'
            ))
            ->where(array(
                $parameter['table'] . '.deleted_at' => 'IS NULL',
                'AND',
                $parameter['table'] . '.nama' => '= ?'
            ), array(
                $parameter['check']
            ))
            ->execute();
    }

    private function konfirmasi_retur_obat($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $Inventori = new Inventori(self::$pdo);
        $parsedItem = array();

        $usedBatchInap = $parameter['item'];

        foreach ($usedBatchInap as $bKey => $bValue) {

            //Proses Stok
            $oldStok = self::$query->select('rawat_inap_batch', array(
                'gudang',
                'pasien',
                'resep',
                'obat',
                'qty',
                'batch'
            ))
                ->where(array(
                    'rawat_inap_batch.pasien' => '= ?',
                    'AND',
                    'rawat_inap_batch.batch' => '= ?',
                    'AND',
                    'rawat_inap_batch.obat' => '= ?',
                    'AND',
                    'rawat_inap_batch.gudang' => '= ?'
                ), array(
                    $parameter['pasien'],
                    $bValue['batch'],
                    $bValue['obat'],
                    $parameter['gudang']
                ))
                ->execute();
            if(count($oldStok['response_data']) > 0) {
                $updateStok = self::$query->update('rawat_inap_batch', array(
                    'qty' => floatval($oldStok['response_data'][0]['qty']) - floatval($bValue['aktual'])
                ))
                    ->where(array(
                        'rawat_inap_batch.pasien' => '= ?',
                        'AND',
                        'rawat_inap_batch.batch' => '= ?',
                        'AND',
                        'rawat_inap_batch.obat' => '= ?',
                        'AND',
                        'rawat_inap_batch.gudang' => '= ?'
                    ), array(
                        $parameter['pasien'],
                        $bValue['batch'],
                        $bValue['obat'],
                        $parameter['gudang']
                    ))
                    ->execute();
                if($updateStok['response_result'] > 0) {
                    if(!isset($parsedItem[$bValue['obat'] . '|' . $bValue['batch']])) {
                        $parsedItem[$bValue['obat'] . '|' . $bValue['batch']] = array(
                            'mutasi' => $bValue['aktual'],
                            'keterangan' => $bValue['keterangan']
                        );
                    }
                }
            }
        }

        // $Invoice = new Invoice(self::$pdo);
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

        $TargetInvoice = $InvoiceCheck['response_data'][0]['uid'];

        if(count($usedBatchInap) > 0) {
            //Dulu dimutasikan, sekarangn dianggurin aja dikipas kipas pake sampul majalah bobo
            $Mutasi = $Inventori->tambah_mutasi(array(
                'access_token' => $parameter['access_token'],
                'dari' => $parameter['gudang'],
                'status' => $parameter['status'],
                'ke' => __GUDANG_APOTEK__,
                'keterangan' => 'Retur Obat Inap. Pasien a.n. ' . $parameter['nama_pasien'] . ' : ' . $parameter['remark'],
                'inap' => true,
                'item' => $parsedItem
            ));

            if($Mutasi['response_result'] > 0) {

                $mutasi_uid = $Mutasi['response_unique'];
                foreach ($usedBatchInap as $bKey => $bValue) {
                    if(floatval($bValue['aktual']) > 0) {
                        $proceed_catat = self::$query->insert('rawat_inap_retur_obat', array(
                            'uid_ranap' => $parameter['uid'],
                            'mutasi' => $mutasi_uid,
                            'petugas' => $UserData['data']->uid,
                            'obat' => $bValue['obat'],
                            'batch' => $bValue['batch'],
                            'sisa' => $bValue['sisa'],
                            'aktual' => $bValue['aktual'],
                            'keterangan' => $bValue['keterangan'],
                            'logged_at' => parent::format_date()
                        ))
                            ->execute();

                        if($proceed_catat['response_result'] > 0) {
                            //Check apakah sudah dicharge atau belum
                            $InvDetail = self::$query->select('invoice_detail', array(
                                'qty', 'harga'
                            ))
                                ->where(array(
                                    'invoice_detail.invoice' => '= ?',
                                    'AND',
                                    'invoice_detail.item_type' => '= ?',
                                    'AND',
                                    'invoice_detail.item' => '= ?',
                                    'AND',
                                    'invoice_detail.status_bayar' => '= ?'
                                ), array(
                                    $TargetInvoice,
                                    'master_inv',
                                    $bValue['obat'],
                                    'N'
                                ))
                                ->execute();
                            if(count($InvDetail['response_data']) > 0) {
                                $invItemUpdate = self::$query->update('invoice_detail', array(
                                    'qty' => floatval($InvDetail['response_data'][0]['qty']) - floatval($bValue['aktual']),
                                    'subtotal' => (floatval($InvDetail['response_data'][0]['qty']) - floatval($bValue['aktual'])) * floatval($InvDetail['response_data'][0]['harga'])
                                ))
                                    ->where(array(
                                        'invoice_detail.invoice' => '= ?',
                                        'AND',
                                        'invoice_detail.item_type' => '= ?',
                                        'AND',
                                        'invoice_detail.item' => '= ?',
                                        'AND',
                                        'invoice_detail.status_bayar' => '= ?'
                                    ), array(
                                        $TargetInvoice,
                                        'master_inv',
                                        $bValue['obat'],
                                        'N'
                                    ))
                                    ->execute();
                            }
                        }
                    }
                }

                //Pulangkan Pasien
                $Kunjungan = self::$query->update('kunjungan', array(
                    'waktu_keluar' => parent::format_date()
                ))
                    ->where(array(
                        'kunjungan.uid' => '= ?',
                        'AND',
                        'kunjungan.deleted_at' => 'IS NULL'
                    ), array(
                        $parameter['kunjungan']
                    ))
                    ->execute();

                if($Kunjungan['response_result'] > 0) {
                    $Pulang = self::pulangkan_pasien(array(
                        'access_token' => $parameter['access_token'],
                        'uid' => $parameter['pasien'],
                        'jenis' => $parameter['jenis'],
                        'keterangan' => $parameter['keterangan']
                    ));
                }
            }
        } else { //Jika tidak ada mutasi
            foreach ($usedBatchInap as $bKey => $bValue) {
                if(floatval($bValue['aktual']) > 0) {
                    //Check apakah sudah dicharge atau belum
                    $InvDetail = self::$query->select('invoice_detail', array(
                        'qty', 'harga'
                    ))
                        ->where(array(
                            'invoice_detail.invoice' => '= ?',
                            'AND',
                            'invoice_detail.item_type' => '= ?',
                            'AND',
                            'invoice_detail.item' => '= ?',
                            'AND',
                            'invoice_detail.status_bayar' => '= ?'
                        ), array(
                            $TargetInvoice,
                            'master_inv',
                            $bValue['obat'],
                            'N'
                        ))
                        ->execute();
                    if(count($InvDetail['response_data']) > 0) {
                        $invItemUpdate = self::$query->update('invoice_detail', array(
                            'qty' => floatval($InvDetail['response_data'][0]['qty']) - floatval($bValue['aktual']),
                            'subtotal' => (floatval($InvDetail['response_data'][0]['qty']) - floatval($bValue['aktual'])) * floatval($InvDetail['response_data'][0]['harga'])
                        ))
                            ->where(array(
                                'invoice_detail.invoice' => '= ?',
                                'AND',
                                'invoice_detail.item_type' => '= ?',
                                'AND',
                                'invoice_detail.item' => '= ?',
                                'AND',
                                'invoice_detail.status_bayar' => '= ?'
                            ), array(
                                $TargetInvoice,
                                'master_inv',
                                $bValue['obat'],
                                'N'
                            ))
                            ->execute();
                    }
                }
            }

            //Pulangkan Pasien
            $Kunjungan = self::$query->update('kunjungan', array(
                'waktu_keluar' => parent::format_date()
            ))
                ->where(array(
                    'kunjungan.uid' => '= ?',
                    'AND',
                    'kunjungan.deleted_at' => 'IS NULL'
                ), array(
                    $parameter['kunjungan']
                ))
                ->execute();

            if($Kunjungan['response_result'] > 0) {
                $Pulang = self::pulangkan_pasien(array(
                    'access_token' => $parameter['access_token'],
                    'uid' => $parameter['pasien'],
                    'jenis' => $parameter['jenis'],
                    'keterangan' => $parameter['keterangan']
                ));
            }
        }

        return array(
            'kunjungan' => $Kunjungan,
            'pulang' => $Pulang
        );
    }

    private function kalkulasi_sisa_obat_2($parameter) {
        $Inventori = new Inventori(self::$pdo);
        $filteredResep = array();
        $data = self::$query->select('rawat_inap_batch', array(
            'qty',
            'batch',
            'obat',
            'resep'
        ))
            ->join('resep', array(
                'kode',
                'kunjungan'
            ))
            ->join('kunjungan', array(
                'waktu_keluar'
            ))
            ->on(array(
                array('rawat_inap_batch.resep', '=', 'resep.uid'),
                array('resep.kunjungan', '=', 'kunjungan.uid')
            ))
            ->where(array(
                'rawat_inap_batch.gudang' => '= ?',
                'AND',
                'rawat_inap_batch.pasien' => '= ?',
                /*'AND',
                'kunjungan.waktu_keluar' => 'IS NULL',*/
                'AND',
                'kunjungan.uid' => '= ?'
            ), array(
                $parameter['gudang'],
                $parameter['pasien'],
                $parameter['kunjungan']
            ))
            ->execute();
        foreach ($data['response_data'] as $key => $value) {
            $berikanLog = 0;
            //Check Pemberian Obat
            $berikan = self::$query->select('rawat_inap_riwayat_obat', array(
                'qty'
            ))
                ->where(array(
                    'rawat_inap_riwayat_obat.resep' => '= ?',
                    'AND',
                    'rawat_inap_riwayat_obat.obat' => '= ?',
                    'AND',
                    'rawat_inap_riwayat_obat.nurse_station' => '= ?'
                ), array(
                    $value['resep'],
                    $value['obat'],
                    $parameter['nurse_station']
                ))
                ->execute();
            foreach ($berikan['response_data'] as $RKey => $RValue) {
                $berikanLog += floatval($RValue['qty']);
            }

            $ResepDetail = self::$query->select('resep_change_log', array(
                'qty'
            ))
                ->where(array(
                    'resep_change_log.resep' => '= ?',
                    'AND',
                    'resep_change_log.item' => '= ?'
                ), array(
                    $value['resep'],
                    $value['obat']
                ))
                ->execute();
            $data['response_data'][$key]['resep'] = $ResepDetail['response_data'][0]['qty'];
            $data['response_data'][$key]['berikan'] = $berikanLog;
            $data['response_data'][$key]['detail'] = $Inventori->get_item_detail($value['obat'])['response_data'][0];
            $data['response_data'][$key]['batch'] = $Inventori->get_batch_detail($value['batch'])['response_data'][0];
        }

        return $data;
    }

    private function kalkulasi_sisa_obat($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $FilteredData = array();
        $Inventori = new Inventori(self::$pdo);
        //Dapatkan Resep Dokter
        $Resep = self::$query->select('resep', array(
            'uid',
            'asesmen',
            'antrian',
            'kunjungan',
            'keterangan',
            'keterangan_racikan'
        ))
            ->where(array(
                'resep.pasien' => '= ?',
                'AND',
                'resep.kunjungan' => '= ?',
                'AND',
                'resep.deleted_at' => 'IS NULL'
            ), array(
                $parameter['pasien'], $parameter['kunjungan']
            ))
            ->execute();

        $Unit = self::get_ns_detail($parameter['nurse_station'])['response_data'][0];

        $Antrian = new Antrian(self::$pdo);

        foreach ($Resep['response_data'] as $key => $value) {
            //Antrian Detail
            $AntrianDetail = $Antrian->get_antrian_detail('antrian', $value['antrian'])['response_data'][0];

            if(
                $AntrianDetail['departemen'] === __POLI_INAP__ &&
                is_null($AntrianDetail['kunjungan_detail']['waktu_keluar'])
            ) {
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
                        'resep_detail.deleted_at' => 'IS NULL',
                        'AND',
                        'resep_detail.resep' => '= ?'
                    ), array(
                        $value['uid']
                    ))
                    ->execute();
                foreach ($resepDetail['response_data'] as $resDKey => $resDValue) {
                    $resepDetail['response_data'][$resDKey]['obat'] = $Inventori->get_item_detail($resDValue['obat'])['response_data'][0];
                    //yang sudah diberikan
                    $TotalPemberian = array();
                    $Berikan = self::$query->select('inventori_stok_log', array(
                        'barang',
                        'batch',
                        'gudang',
                        'masuk',
                        'keluar',
                        'saldo'
                    ))
                        ->where(array(
                            'inventori_stok_log.barang' => '= ?',
                            'AND',
                            'inventori_stok_log.type' => '= ?',
                            'AND',
                            'inventori_stok_log.gudang' => '= ?'
                        ), array(
                            $resDValue['obat'],
                            __STATUS_BARANG_KELUAR_INAP__,
                            $Unit['uid_gudang']
                        ))
                        ->execute();
                    foreach ($Berikan['response_data'] as $BerKey => $BerValue) {
                        if(!isset($TotalPemberian[$BerValue['barang']])) {
                            $TotalPemberian[$BerValue['barang']] = array();
                        }

                        if(!isset($TotalPemberian[$BerValue['barang']][$BerValue['batch']])) {
                            $TotalPemberian[$BerValue['barang']][$BerValue['batch']] = array(
                                'keluar' => floatval($BerValue['keluar']),
                                'sisa' => 0
                            );
                        }
                    }

                    foreach ($TotalPemberian as $batchD => $batchVal) {
                        foreach ($batchVal as $bCountKey => $bCountVal) {
                            //Check Stok pada NS
                            $Stok = self::$query->select('inventori_stok', array(
                                'barang',
                                'batch',
                                'gudang',
                                'stok_terkini'
                            ))
                                ->where(array(
                                    'inventori_stok.gudang' => '= ?',
                                    'AND',
                                    'inventori_stok.batch' => '= ?',
                                    'AND',
                                    'inventori_stok.barang' => '= ?'
                                ), array(
                                    $Unit['uid_gudang'],
                                    $bCountKey,
                                    $batchD
                                ))
                                ->execute();

                            if(count($Stok['response_data']) > 0) {
                                foreach ($Stok['response_data'] as $StKey => $StValue) {
                                    $TotalPemberian[$StValue['barang']][$StValue['batch']]['sisa'] = floatval($resDValue['qty']) - floatval($TotalPemberian[$StValue['barang']][$StValue['batch']]['keluar']);
                                }
                            }
                        }
                    }

                    $resepDetail['response_data'][$resDKey]['terpakai'] = $TotalPemberian;
                    $resepDetail['response_data'][$resDKey]['unit'] = $Unit;
                }

                $Resep['response_data'][$key]['detail'] = $resepDetail['response_data'];






                $Racikan = self::$query->select('racikan', array(
                    'uid',
                    'asesmen',
                    'kode',
                    'qty'
                ))
                    ->join('asesmen', array(
                        'poli',
                        'pasien',
                        'kunjungan'
                    ))
                    ->join('kunjungan', array(
                        'waktu_keluar'
                    ))
                    ->on(array(
                        array('racikan.asesmen', '=', 'asesmen.uid'),
                        array('asesmen.kunjungan', '=', 'kunjungan.uid')
                    ))
                    ->where(array(
                        'asesmen.pasien' => '= ?',
                        'AND',
                        'asesmen.poli' => '= ?',
                        'AND',
                        'kunjungan.waktu_keluar' => 'IS NULL',
                        'AND',
                        'racikan.asesmen' => '= ?',
                        'AND',
                        'racikan.deleted_at' => 'IS NULL'
                    ), array(
                        $parameter['pasien'],
                        __POLI_INAP__,
                        $value['asesmen']
                    ))
                    ->execute();

                foreach ($Racikan['response_data'] as $RacKey => $RacValue) {
                    $TotalRacikan = 0;
                    //Check Pemberian
                    $Riwayat = self::$query->select('rawat_inap_riwayat_obat', array(
                        'qty'
                    ))
                        ->where(array(
                            'rawat_inap_riwayat_obat.resep' => '= ?',
                            'AND',
                            'rawat_inap_riwayat_obat.obat' => '= ?'
                        ), array(
                            $value['uid'],
                            $RacValue['kode']
                        ))
                        ->execute();
                    foreach ($Riwayat['response_data'] as $RRKey => $RRValue) {
                        $TotalRacikan += floatval($RRValue['qty']);
                    }

                    $Racikan['response_data'][$RacKey]['total_diberikan'] = $TotalRacikan;
                }

                $Resep['response_data'][$key]['racikan'] = $Racikan['response_data'];
                array_push($FilteredData, $Resep['response_data'][$key]);
            } else {
                array_push($FilteredData, is_null($AntrianDetail['kunjungan_detail']['waktu_keluar']));
            }
        }




        return $FilteredData;
    }

    private function riwayat_obat_inap($parameter) {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'resep.pasien' => '= ?',
                'AND',
                '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
            );

            $paramValue = array(
                $parameter['pasien']
            );
        } else {
            $paramData = array(
                'resep.pasien' => '= ?'
            );

            $paramValue = array(
                $parameter['pasien']
            );
        }

        if ($parameter['length'] < 0) {
            $data = self::$query->select('rawat_inap_riwayat_obat', array(
                'id',
                'petugas',
                'resep',
                'obat',
                'keterangan',
                'qty',
                'nurse_station',
                'logged_at'
            ))
                ->join('pegawai', array(
                    'nama as nama_petugas'
                ))
                ->join('resep', array(
                    'pasien'
                ))
                ->join('pasien', array(
                    'nama', 'no_rm'
                ))
                ->on(array(
                    array('rawat_inap_riwayat_obat.petugas', '=', 'pegawai.uid'),
                    array('rawat_inap_riwayat_obat.resep', '=', 'resep.uid'),
                    array('resep.pasien', '=', 'pasien.uid')
                ))
                ->order(array(
                    'logged_at' => 'DESC'
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('rawat_inap_riwayat_obat', array(
                'id',
                'petugas',
                'resep',
                'obat',
                'keterangan',
                'qty',
                'nurse_station',
                'logged_at'
            ))
                ->join('pegawai', array(
                    'nama as nama_petugas'
                ))
                ->join('resep', array(
                    'pasien'
                ))
                ->join('pasien', array(
                    'nama', 'no_rm'
                ))
                ->on(array(
                    array('rawat_inap_riwayat_obat.petugas', '=', 'pegawai.uid'),
                    array('rawat_inap_riwayat_obat.resep', '=', 'resep.uid'),
                    array('resep.pasien', '=', 'pasien.uid')
                ))
                ->order(array(
                    'logged_at' => 'DESC'
                ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->where($paramData, $paramValue)
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        $Inventori = new Inventori(self::$pdo);
        $Pasien = new Pasien(self::$pdo);

        $returnedData = array();
        foreach ($data['response_data'] as $key => $value) {
            //Resep
            $resep = self::$query->select('resep', array(
                'kode',
                'pasien'
            ))
                ->where(array(
                    'resep.uid' => '= ?'
                ), array(
                    $value['resep']
                ))
                ->execute();
            $value['resep_kode'] = $resep['response_data'][0]['kode'];
            $value['resep_pasien'] = $resep['response_data'][0]['pasien'];
            $value['resep_pasien_detail'] = $Pasien->get_pasien_detail('pasien', $value['resep_pasien'])['response_data'][0];
            //Master Inv
            $InventoriDetail = $Inventori->get_item_detail($value['obat']);
            $NamaObat = (count(($InventoriDetail['response_data'])) > 0) ? $InventoriDetail['response_data'][0]['nama'] : $value['obat'];
            $value['obat'] = $NamaObat;
            $value['logged_at'] = date('d F Y, H:i', strtotime($value['logged_at']));

            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                $checker = stripos($NamaObat, $parameter['search']['value']);
                if($checker >= 0 && $checker !== false) {
                    $value['autonum'] = $autonum;
                    array_push($returnedData, $value);
                    $autonum++;
                }
            } else {
                $value['autonum'] = $autonum;
                array_push($returnedData, $value);
                $autonum++;
            }

        }



        $itemTotal = self::$query->select('rawat_inap_riwayat_obat', array(
            'id'
        ))
            ->join('pegawai', array(
                'nama as nama_petugas'
            ))
            ->on(array(
                array('rawat_inap_riwayat_obat.petugas', '=', 'pegawai.uid')
            ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['response_data'] = $returnedData;
        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function tambah_riwayat_resep_inap($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $allowedNS = array();

        foreach ($UserData['data']->nurse_station as $key => $value) {
            if(!in_array($value->nurse_station, $allowedNS)) {
                array_push($allowedNS, $value->nurse_station);
            }
        }

        $proceed = array();

        $Inventori = new Inventori(self::$pdo);

        if(in_array($parameter['nurse_station'], $allowedNS)) {

            $ProceedBatch = array();

            foreach ($parameter['item'] as $key => $value) {
                $process = self::$query->insert('rawat_inap_riwayat_obat', array(
                    'petugas' => $UserData['data']->uid,
                    'resep' => $value['resep'],
                    'obat' => $value['obat'],
                    'qty' => $value['qty'],
                    'nurse_station' => $parameter['nurse_station'],
                    'keterangan' => $value['keterangan'],
                    'logged_at' => parent::format_date()
                ))
                    ->execute();
                if(
                    $value['charge_stock'] === "true" &&
                    $process['response_result'] > 0
                ) {
                    //Charge Stok
                    $NS = self::get_ns_detail($parameter['nurse_station'])['response_data'][0];

                    foreach ($value['batch'] as $BBSKey => $BBSValue) {
                        if(!isset($ProceedBatch[$BBSKey])) {
                            $ProceedBatch[$BBSKey] = array(
                                'barang' => $value['obat'],
                                'qty' => $BBSValue,
                                'resep' => $value['resep'],
                                'keterangan' => $value['keterangan']
                            );
                        }
                    }

                    /*$StokPre = self::$query->select('inventori_stok', array(
                        'batch',
                        'gudang',
                        'stok_terkini'
                    ))
                        ->where(array(
                            'inventori_stok.gudang' => '= ?',
                            'AND',
                            'inventori_stok.barang' => '= ?'
                        ), array(
                            $parameter['gudang'],
                            $value['obat']
                        ))
                        ->execute();

                    if(count($StokPre['response_data']) > 0) {
                        $usedBatch = array();
                        $kebutuhan = floatval($value['qty']);

                        //Sorting Stok
                        foreach ($StokPre['response_data'] as $batchKey => $batchValue) {
                            $StokPre['response_data'][$batchKey]['batch_detail'] = $Inventori->get_batch_detail($batchValue['batch']);
                        }

                        $original = $StokPre['response_data'];
                        usort($original, function($a, $b){
                            $t1 = strtotime($a['expired_sort']);
                            $t2 = strtotime($b['expired_sort']);
                            return $t1 - $t2;
                        });

                        $StokPre['response_data'] = $original;

                        foreach ($StokPre['response_data'] as $batchKey => $batchValue) {
                            if($kebutuhan > 0 && floatval($batchValue['stok_terkini']) > 0 && $batchValue['gudang'] === $parameter['gudang']) {
                                if(floatval($batchValue['stok_terkini']) < $kebutuhan) {
                                    if(!isset($usedBatch[$batchValue['batch']])) {
                                        $usedBatch[$batchValue['batch']] = array(
                                            'terpakai' => floatval($batchValue['stok_terkini']),
                                            'sisa' => $kebutuhan - floatval($batchValue['stok_terkini'])
                                        );
                                    }
                                    $kebutuhan -= floatval($batchValue['stok_terkini']);
                                } else {
                                    if(!isset($usedBatch[$batchValue['batch']])) {
                                        $usedBatch[$batchValue['batch']] = array(
                                            'terpakai' => $kebutuhan,
                                            'sisa' => floatval($batchValue['stok_terkini']) - $kebutuhan
                                        );
                                    }
                                    $kebutuhan = 0;
                                }
                            }
                        }

                        //potong stok
                        $stok_record = array();
                        $stok_record_log = array();
                        foreach ($usedBatch as $uBKey => $ubValue) {
                            $procStok = self::$query->update('inventori_stok', array(
                                'stok_terkini' => $ubValue['sisa']
                            ))
                                ->where(array(
                                    'inventori_stok.gudang' => '= ?',
                                    'AND',
                                    'inventori_stok.barang' => '= ?',
                                    'AND',
                                    'inventori_stok.batch' => '= ?'
                                ), array(
                                    $NS['uid_gudang'],
                                    $value['obat'],
                                    $uBKey
                                ))
                                ->execute();
                            if($procStok['response_result'] > 0) {
                                //Catat Stok log
                                $stok_log = self::$query->insert('inventori_stok_log', array(
                                    'barang' => $value['obat'],
                                    'batch' => $uBKey,
                                    'gudang' => $NS['uid_gudang'],
                                    'masuk' => 0,
                                    'keluar' => floatval($ubValue['terpakai']),
                                    'saldo' => floatval($ubValue['sisa']),
                                    'type' => __STATUS_BARANG_KELUAR_INAP__,
                                    'logged_at' => parent::format_date(),
                                    'jenis_transaksi' => 'resep',
                                    'uid_foreign' => $value['resep'],
                                    'keterangan' => 'Pemberian Obat Rawat Inap. ' . $value['keterangan']
                                ))
                                    ->execute();
                                if($stok_log['response_result'] > 0) {
                                    array_push($stok_record_log, $stok_log);

                                    $OldStokNS = self::$query->select('rawat_inap_batch', array(
                                        'id',
                                        'qty'
                                    ))
                                        ->where(array(
                                            'rawat_inap_batch.resep' => '= ?',
                                            'AND',
                                            'rawat_inap_batch.obat' => '= ?',
                                            'AND',
                                            'rawat_inap_batch.batch' => '= ?',
                                            'AND',
                                            'rawat_inap_batch.gudang' => '= ?'
                                        ), array(
                                            $value['resep'],
                                            $value['obat'],
                                            $uBKey,
                                            $NS['gudang']
                                        ))
                                        ->execute();
                                    if(count($OldStokNS['response_data']) > 0) {
                                        //Kurangi Stok NS
                                        $updateStokNS = self::$query->update('rawat_inap_batch', array(
                                            //'qty' => floatval($OldStokNS['response_data'][0]['qty']) - floatval($ubValue['terpakai'])
                                            'qty' => floatval($ubValue['sisa'])
                                        ))
                                            ->where(array(
                                                'rawat_inap_batch.id' => '= ?'
                                            ), array(
                                                $OldStokNS['response_data'][0]['id']
                                            ))
                                            ->execute();
                                        array_push($stok_record, $updateStokNS);
                                    }
                                }
                            }
                            //array_push($stok_record, $procStok);

                        }
                        $StokPre['stok_proc'] = $stok_record;
                        $StokPre['stok_proc_log'] = $stok_record_log;
                    } else {
                        $StokPre = 'failed';
                    }*/
                } else {
                    $process['failed'] = $value['charge_stock'];
                }

                $process['param'] = $value;
                /*$process['stock_pre'] = $StokPre;
                $process['batch'] = $usedBatch;*/

                array_push($proceed, $process);
            }

            $ProceedBatchProcess = array();

            foreach ($ProceedBatch as $key => $value) {
                $StokPre = self::$query->select('inventori_stok', array(
                    'batch',
                    'barang',
                    'gudang',
                    'stok_terkini'
                ))
                    ->where(array(
                        'inventori_stok.gudang' => '= ?',
                        'AND',
                        'inventori_stok.barang' => '= ?',
                        'AND',
                        'inventori_stok.batch' => '= ?'
                    ), array(
                        $parameter['gudang'],
                        $value['barang'],
                        $key
                    ))
                    ->execute();

                array_push($ProceedBatchProcess, $StokPre);

                if(count($StokPre['response_data']) > 0) {
                    //Update Stok
                    $procStok = self::$query->update('inventori_stok', array(
                        'stok_terkini' => floatval($StokPre['response_data'][0]['stok_terkini']) - floatval($value['qty'])
                    ))
                        ->where(array(
                            'inventori_stok.gudang' => '= ?',
                            'AND',
                            'inventori_stok.barang' => '= ?',
                            'AND',
                            'inventori_stok.batch' => '= ?'
                        ), array(
                            $parameter['gudang'],
                            $value['barang'],
                            $key
                        ))
                        ->execute();

                    if($procStok['response_result'] > 0) {
                        //Catat Stok log
                        $stok_log = self::$query->insert('inventori_stok_log', array(
                            'barang' => $value['barang'],
                            'batch' => $key,
                            'gudang' => $parameter['gudang'],
                            'masuk' => 0,
                            'keluar' => floatval($value['qty']),
                            'saldo' => (floatval($StokPre['response_data'][0]['stok_terkini']) - floatval($value['qty'])),
                            'type' => __STATUS_BARANG_KELUAR_INAP__,
                            'logged_at' => parent::format_date(),
                            'jenis_transaksi' => 'resep',
                            'uid_foreign' => $value['resep'],
                            'keterangan' => 'Pemberian Obat Rawat Inap. ' . $value['keterangan']
                        ))
                            ->execute();

                        if($stok_log['response_result'] > 0) {
                            $OldStokNS = self::$query->select('rawat_inap_batch', array(
                                'id',
                                'qty'
                            ))
                                ->where(array(
                                    'rawat_inap_batch.resep' => '= ?',
                                    'AND',
                                    'rawat_inap_batch.obat' => '= ?',
                                    'AND',
                                    'rawat_inap_batch.batch' => '= ?',
                                    'AND',
                                    'rawat_inap_batch.gudang' => '= ?'
                                ), array(
                                    $value['resep'],
                                    $value['barang'],
                                    $key,
                                    $parameter['gudang']
                                ))
                                ->execute();
                            if(count($OldStokNS['response_data']) > 0) {

                                //Qty Resep
                                $QtyResep = self::$query->select('resep_change_log', array(
                                    'qty'
                                ))
                                    ->where(array(
                                        'resep_change_log.item' => '= ?',
                                        'AND',
                                        'resep_change_log.resep' => '= ?'
                                    ), array(
                                        $value['barang'],
                                        $value['resep']
                                    ))
                                    ->execute();

                                $TotalPenggunaan = 0;

                                //History Sebelumnya
                                $HistoryResep = self::$query->select('rawat_inap_riwayat_obat', array(
                                    'qty'
                                ))
                                    ->where(array(
                                        'rawat_inap_riwayat_obat.resep' => '= ?',
                                        'AND',
                                        'rawat_inap_riwayat_obat.obat' => '= ?'
                                    ), array(
                                        $value['resep'],
                                        $value['barang']
                                    ))
                                    ->execute();
                                foreach ($HistoryResep['response_data'] as $KSLKey => $KSLValue) {
                                    $TotalPenggunaan += floatval($KSLValue['qty']);
                                }

                                //Kurangi Stok NS
                                $updateStokNS = self::$query->update('rawat_inap_batch', array(
                                    //'qty' => (floatval($StokPre['response_data'][0]['stok_terkini']) - floatval($value['qty']))
                                    'qty' => (floatval($QtyResep['response_data'][0]['qty']) - $TotalPenggunaan)
                                ))
                                    ->where(array(
                                        'rawat_inap_batch.id' => '= ?'
                                    ), array(
                                        $OldStokNS['response_data'][0]['id']
                                    ))
                                    ->execute();
                            }
                        }
                    }
                }
            }
            $proceed['batch'] = $ProceedBatchProcess;
        } else {
            $proceed = $allowedNS;
        }


        return $proceed;
    }

    private function edit_nurse_station($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $uid = $parameter['uid'];
        $old = self::get_ns_detail($uid);

        $process = self::$query->update('nurse_station', array(
            'nama' => $parameter['nama'],
            'kode' => $parameter['kode'],
            'unit' => $parameter['unit'],
            'updated_at' => parent::format_date()
        ))
            ->where(array(
                'nurse_station.deleted_at' => 'IS NULL',
                'AND',
                'nurse_station.uid' => '= ?'
            ), array(
                $uid
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
                    'old_value',
                    'new_value',
                    'logged_at',
                    'status',
                    'login_id'
                ),
                'value' => array(
                    $parameter['uid'],
                    $UserData['data']->uid,
                    'master_inv_obat_kategori',
                    'U',
                    json_encode($old['response_data'][0]),
                    json_encode($parameter),
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class' => __CLASS__
            ));

            //Hard Reset Detail Item
            //Petugas
            $deletePetugas = self::$query->hard_delete('nurse_station_ranjang')
                ->where(array(
                    'nurse_station_ranjang.nurse_station' => '= ?'
                ), array(
                    $uid
                ))
                ->execute();

            //Asuhan
            $deleteAsuhan = self::$query->hard_delete('nurse_station_petugas')
                ->where(array(
                    'nurse_station_petugas.nurse_station' => '= ?'
                ), array(
                    $uid
                ))
                ->execute();



            foreach ($parameter['petugas'] as $key => $value) {
                $entry_petugas = self::$query->insert('nurse_station_petugas', array(
                    'nurse_station' => $uid,
                    'petugas' => $value,
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
                if($entry_petugas['response_result'] > 0) {
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
                            $entry_petugas['response_unique'],
                            $UserData['data']->uid,
                            'nurse_station_petugas',
                            'I',
                            parent::format_date(),
                            'N',
                            $UserData['data']->log_id
                        ),
                        'class' => __CLASS__
                    ));
                }
            }

            foreach ($parameter['ranjang'] as $key => $value) {
                $entry_ranjang = self::$query->insert('nurse_station_ranjang', array(
                    'nurse_station' => $uid,
                    'ranjang' => $value,
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
                if($entry_ranjang['response_result'] > 0) {
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
                            $entry_ranjang['response_unique'],
                            $UserData['data']->uid,
                            'nurse_station_petugas',
                            'I',
                            parent::format_date(),
                            'N',
                            $UserData['data']->log_id
                        ),
                        'class' => __CLASS__
                    ));
                }
            }
        }
        return $process;
    }

    private function tambah_nurse_station($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $check = self::duplicate_check(array(
            'table' => 'nurse_station',
            'check' => $parameter['nama']
        ));
        if (count($check['response_data']) > 0) {
            $check['response_message'] = 'Duplicate data detected';
            $check['response_result'] = 0;
            unset($check['response_data']);
            return $check;
        } else {
            //Check Kode pada Stok Point
            /*$KodeCheck = self::$query->select('master_unit', array(
                'uid'
            ))
                ->where(array(
                    'master_unit.deleted_at' => 'IS NULL',
                    'AND',
                    'master_unit.kode' => '= ?'
                ), array(
                    $parameter['kode']
                ))
                ->execute();
            if(count($KodeCheck['response_data']) > 0) {
                $KodeCheck['response_message'] = 'Duplicate data detected';
                $KodeCheck['response_result'] = 0;
                unset($KodeCheck['response_data']);
                return $KodeCheck;
            } else {





            }*/

            $uid = parent::gen_uuid();
            $process = self::$query->insert('nurse_station', array(
                'uid' => $uid,
                'nama' => $parameter['nama'],
                'kode' => $parameter['kode'],
                'unit' => $parameter['unit'],
                'type' => 'INAP',
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
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
                        $uid,
                        $UserData['data']->uid,
                        'nurse_station',
                        'I',
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                ));

                //Process Ranjang dan Petugas

                foreach ($parameter['petugas'] as $key => $value) {
                    $entry_petugas = self::$query->insert('nurse_station_petugas', array(
                        'nurse_station' => $uid,
                        'petugas' => $value,
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                    if($entry_petugas['response_result'] > 0) {
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
                                $entry_petugas['response_unique'],
                                $UserData['data']->uid,
                                'nurse_station_petugas',
                                'I',
                                parent::format_date(),
                                'N',
                                $UserData['data']->log_id
                            ),
                            'class' => __CLASS__
                        ));
                    }
                }

                foreach ($parameter['ranjang'] as $key => $value) {
                    $entry_ranjang = self::$query->insert('nurse_station_ranjang', array(
                        'nurse_station' => $uid,
                        'ranjang' => $value,
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                    if($entry_ranjang['response_result'] > 0) {
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
                                $entry_ranjang['response_unique'],
                                $UserData['data']->uid,
                                'nurse_station_petugas',
                                'I',
                                parent::format_date(),
                                'N',
                                $UserData['data']->log_id
                            ),
                            'class' => __CLASS__
                        ));
                    }
                }

                //Create Gudang
                /*$Gudang = $Inventori->tambah_gudang(array(
                    'access_token' => $parameter['access_token'],
                    'nama' => 'Inventori ' . $parameter['nama']
                ));

                if($Gudang['response_result'] > 0) {
                    $UnitProcess = $Unit->tambah_unit(array(
                        'nama' => $parameter['nama'],
                        'kode' => $parameter['kode'],
                        'gudang' => $Gudang['response_unique']
                    ));
                }*/

            }
            return $process;
        }
    }

    private function get_nurse_station($parameter) {
        $UsedNS = array();
        $UsedBed = array();
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'nurse_station.deleted_at' => 'IS NULL',
                'AND',
                'nurse_station.type' => '= ?',
                'AND',
                '(nurse_station.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'nurse_station.kode' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
            );

            $paramValue = array('INAP');
        } else {
            $paramData = array(
                'nurse_station.deleted_at' => 'IS NULL',
                'AND',
                'nurse_station.type' => '= ?'
            );

            $paramValue = array('INAP');
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('nurse_station', array(
                'uid',
                'kode',
                'nama',
                'unit',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('nurse_station', array(
                'uid',
                'kode',
                'nama',
                'unit',
                'created_at',
                'updated_at'
            ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->where($paramData, $paramValue)
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        $Bed = new Bed(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            if(!isset($UsedNS[$value['uid']])) {
                $UsedNS[$value['uid']] = array();
            }

            //Get Ranjang
            $Ranjang = self::$query->select('nurse_station_ranjang', array(
                'ranjang'
            ))
                ->where(array(
                    'nurse_station_ranjang.deleted_at' => 'IS NULL',
                    'AND',
                    'nurse_station_ranjang.nurse_station' => '= ?'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach ($Ranjang['response_data'] as $RK => $RV) {
                if(!in_array($RV['ranjang'], $UsedNS[$value['uid']])) {
                    array_push($UsedNS[$value['uid']], $RV['ranjang']);
                }

                if(!in_array($RV['ranjang'], $UsedBed)) {
                    array_push($UsedBed, $RV['ranjang']);
                }

                //Check Ketersediaan Ranjang
                $CheckRanjang = self::$query->select('rawat_inap', array(
                    'pasien',
                    'dokter'
                ))
                    ->join('pasien', array(
                        'nama as nama_pasien'
                    ))
                    ->join('pegawai', array(
                        'nama as nama_dokter'
                    ))
                    ->on(array(
                        array('rawat_inap.pasien', '=', 'pasien.uid'),
                        array('rawat_inap.dokter', '=', 'pegawai.uid')
                    ))
                    ->where(array(
                        'rawat_inap.deleted_at' => 'IS NULL',
                        'AND',
                        'rawat_inap.bed' => '= ?',
                        'AND',
                        'rawat_inap.nurse_station' => '= ?',
                        'AND',
                        'rawat_inap.waktu_keluar' => 'IS NULL'
                    ), array(
                        $RV['ranjang'],
                        $value['uid']
                    ))
                    ->execute();

                $Ranjang['response_data'][$RK]['status'] = $CheckRanjang['response_data'][0];
                $Ranjang['response_data'][$RK]['allow_manage'] = (count($CheckRanjang['response_data']) > 0) ? false: true;
                $Ranjang['response_data'][$RK]['detail'] = $Bed->get_bed_detail('master_unit_bed', $RV['ranjang'])['response_data'][0];

            }
            $data['response_data'][$key]['ranjang'] = $Ranjang['response_data'];

            //Get Petugas
            $Petugas = self::$query->select('nurse_station_petugas', array(
                'petugas'
            ))
                ->join('pegawai', array(
                    'nama as nama_petugas'
                ))
                ->on(array(
                    array('nurse_station_petugas.petugas', '=', 'pegawai.uid')
                ))
                ->where(array(
                    'nurse_station_petugas.deleted_at' => 'IS NULL',
                    'AND',
                    'nurse_station_petugas.nurse_station' => '= ?'
                ), array(
                    $value['uid']
                ))
                ->execute();
            $data['response_data'][$key]['petugas'] = $Petugas['response_data'];

            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }



        $itemTotal = self::$query->select('nurse_station', array(
            'uid'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['usedNS'] = $UsedNS;
        $data['usedBed'] = $UsedBed;
        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function pulangkan_pasien($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $worker = self::$query->update('rawat_inap', array(
            'waktu_keluar' => parent::format_date(),
            'jenis_pulang' => $parameter['jenis'],
            'alasan_pulang' => $parameter['keterangan']
        ))
            ->where(array(
                'rawat_inap.pasien' => '= ?',
                'AND',
                'rawat_inap.deleted_at' => 'IS NULL'
            ), array(
                $parameter['uid']
            ))
            ->execute();

        return $worker;
    }

    private function get_all($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            if(isset($parameter['division'])) {
                $paramData = array(
                    'rawat_inap.deleted_at' => 'IS NULL',
                    'AND',
                    'rawat_inap.nurse_station' => 'IS NOT NULL',
                    'AND',
                    'rawat_inap.waktu_keluar' => 'IS NULL',
                    'AND',
                    '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
                );

                //$paramValue = array($UserData['data']->uid);
                $paramValue = array();
            } else {
                $paramData = array(
                    'rawat_inap.deleted_at' => 'IS NULL',
                    'AND',
                    /*'rawat_inap.dokter' => '= ?',
                    'AND',*/
                    'rawat_inap.waktu_keluar' => 'IS NULL',
                    'AND',
                    '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
                );

                //$paramValue = array($UserData['data']->uid);
                $paramValue = array();
            }
        } else {
            if(isset($parameter['division'])) {
                $paramData = array(
                    'rawat_inap.deleted_at' => 'IS NULL',
                    'AND',
                    'rawat_inap.nurse_station' => 'IS NOT NULL',
                    'AND',
                    /*'rawat_inap.dokter' => '= ?',
                    'AND',*/
                    'rawat_inap.waktu_keluar' => 'IS NULL'
                    /**/
                );

                //$paramValue = array($UserData['data']->uid);
                $paramValue = array();
            } else {
                $paramData = array(
                    'rawat_inap.deleted_at' => 'IS NULL',
                    'AND',
                    /*'rawat_inap.dokter' => '= ?',
                    'AND',*/
                    'rawat_inap.waktu_keluar' => 'IS NULL'
                    /**/
                );

                //$paramValue = array($UserData['data']->uid);
                $paramValue = array();
            }
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('rawat_inap', array(
                'uid',
                'pasien',
                'waktu_masuk',
                'waktu_keluar',
                'kamar',
                'kunjungan',
                'bed',
                'keterangan',
                'dokter',
                'asal',
                'penjamin',
                'created_at',
                'updated_at'
            ))
                ->join('pasien', array(
                    'nama'
                ))
                ->on(array(
                    array('rawat_inap.pasien', '=', 'pasien.uid')
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('rawat_inap', array(
                'uid',
                'pasien',
                'waktu_masuk',
                'waktu_keluar',
                'kamar',
                'kunjungan',
                'bed',
                'keterangan',
                'dokter',
                'asal',
                'penjamin',
                'created_at',
                'updated_at'
            ))
                ->join('pasien', array(
                    'nama'
                ))
                ->on(array(
                    array('rawat_inap.pasien', '=', 'pasien.uid')
                ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        $Pasien = new Pasien(self::$pdo);
        $Poli = new Poli(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);
        $Ruangan = new Ruangan(self::$pdo);
        $Bed = new Bed(self::$pdo);
        $Invoice = new Invoice(self::$pdo);
        $returnedData = array();
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;

            //Tagihan Lainnya
            $InvoiceItem = self::$query->select('invoice', array('uid'))
                ->where(array(
                    'invoice.kunjungan' => '= ?',
                    'AND',
                    'invoice.pasien' => '= ?'
                ), array(
                    $value['kunjungan'],
                    $value['pasien']
                ))
                ->execute();
            foreach ($InvoiceItem['response_data'] as $InvKey => $InvValue) {
                $InvoiceDetail = $Invoice->get_biaya_pasien_detail($InvValue['uid'])['response_data'][0];
                foreach ($InvoiceDetail as $InvPKey => $InvPValue) {
                    if($InvPKey !== 'uid') {
                        $InvoiceItem['response_data'][$InvKey][$InvPKey] = $InvPValue;
                    }
                }
            }

            $data['response_data'][$key]['invoice'] = $InvoiceItem['response_data'];

            //Check Administrasi Pending
            //Apotek
            $ApotekVerif = self::$query->select('resep', array(
                'status_resep'
            ))
                ->where(array(
                    'resep.kunjungan' => '= ?'
                ), array(
                    $value['kunjungan']
                ))
                ->execute();
            $data['response_data'][$key]['tagihan_apotek'] = $ApotekVerif['response_data'];

            //Laboratorium
            $LaborVerif = self::$query->select('lab_order', array(
                'status'
            ))
                ->where(array(
                    'lab_order.kunjungan' => '= ?'
                ), array(
                    $value['kunjungan']
                ))
                ->execute();
            foreach($LaborVerif['response_data'] as $LbKeey => $LbVallue) {
                $ExistLabor = self::$query->select('lab_order_detail', array(
                    'id'
                ))
                    ->where(array(
                        'lab_order_detail.lab_order' => '= ?',
                        'AND',
                        'lab_order_detail.deleted_at' => 'IS NULL'
                    ), array(
                        $LbVallue['uid']
                    ))
                    ->execute();
                $LaborVerif['response_data'][$LbKeey]['detail'] = $ExistLabor['response_data'];
            }
            $data['response_data'][$key]['tagihan_laboratorium'] = $LaborVerif['response_data'];

            //Radiologi
            $RadioVerif = self::$query->select('rad_order', array(
                'status'
            ))
                ->where(array(
                    'rad_order.kunjungan' => '= ?'
                ), array(
                    $value['kunjungan']
                ))
                ->execute();
            foreach($RadioVerif['response_data'] as $RdKeey => $RdVallue) {
                $ExistRadio = self::$query->select('lab_order_detail', array(
                    'id'
                ))
                    ->where(array(
                        'lab_order_detail.lab_order' => '= ?',
                        'AND',
                        'lab_order_detail.deleted_at' => 'IS NULL'
                    ), array(
                        $RdVallue['uid']
                    ))
                    ->execute();
                $RadioVerif['response_data'][$RdKeey]['detail'] = $ExistRadio['response_data'];
            }
            $data['response_data'][$key]['tagihan_radiologi'] = $RadioVerif['response_data'];

            //Pasien
            $PasienDetail = $Pasien->get_pasien_info('pasien', $value['pasien']);
            $data['response_data'][$key]['pasien'] = $PasienDetail['response_data'][0];
            $data['response_data'][$key]['pasien_raw'] = $PasienDetail;

            //Dokter
            $PegawaiDetail = $Pegawai->get_detail($value['dokter']);
            $data['response_data'][$key]['dokter'] = $PegawaiDetail['response_data'][0];

            $data['response_data'][$key]['asal'] = $Poli->get_poli_info($value['asal'])['response_data'][0];

            //Penjamin
            $PenjaminDetail = $Penjamin->get_penjamin_detail($value['penjamin']);
            $data['response_data'][$key]['penjamin'] = $PenjaminDetail['response_data'][0];

            //Ruangan
            $RuanganDetail = $Ruangan->get_ruangan_detail('master_unit_ruangan', $value['kamar']);
            $data['response_data'][$key]['kamar'] = $RuanganDetail['response_data'][0];

            //Nurse Station
            $NurseStation = self::$query->select('nurse_station_ranjang', array(
                'nurse_station'
            ))
                ->join('nurse_station', array(
                    'kode as kode_ns',
                    'nama as nama_ns'
                ))
                ->on(array(
                    array('nurse_station_ranjang.nurse_station', '=', 'nurse_station.uid')
                ))
                ->where(array(
                    'nurse_station_ranjang.ranjang' => '= ?',
                    'AND',
                    'nurse_station_ranjang.deleted_at' => 'IS NULL'
                ), array(
                    $value['bed']
                ))
                ->execute();
            $data['response_data'][$key]['nurse_station'] = $NurseStation['response_data'][0];

            //Bed
            $BedDetail = $Bed->get_bed_detail('master_unit_bed', $value['bed']);
            $data['response_data'][$key]['bed'] = $BedDetail['response_data'][0];

            $data['response_data'][$key]['waktu_masuk_tanggal'] = date('d F Y', strtotime($value['waktu_masuk']));
            $data['response_data'][$key]['waktu_masuk_jam'] = date('H:i', strtotime($value['waktu_masuk']));


            if(
                count($PasienDetail['response_data']) > 0 &&
                count($PenjaminDetail['response_data']) > 0
            ) {
                array_push($returnedData, $data['response_data'][$key]);
                $autonum++;
            }
        }

        $itemTotal = self::$query->select('rawat_inap', array(
            'uid'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['response_data'] = $returnedData;

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($data['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function edit_inap($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $allowSave = false;

        $NSLog = array();

        //Check Sebelum Save
        /*$Ranjang = self::$query->select('nurse_station_ranjang', array(
            'ranjang'
        ))
            ->where(array(
                'nurse_station_ranjang.deleted_at' => 'IS NULL',
                'AND',
                'nurse_station_ranjang.nurse_station' => '= ?'
            ), array(
                $parameter['uid']
            ))
            ->execute();
        foreach ($Ranjang['response_data'] as $RK => $RV) {

        }*/

        //Check Ketersediaan Ranjang
        $CheckRanjang = self::$query->select('rawat_inap', array(
            'pasien',
            'dokter'
        ))
            ->join('pasien', array(
                'nama as nama_pasien'
            ))
            ->join('pegawai', array(
                'nama as nama_dokter'
            ))
            ->on(array(
                array('rawat_inap.pasien', '=', 'pasien.uid'),
                array('rawat_inap.dokter', '=', 'pegawai.uid')
            ))
            ->where(array(
                'rawat_inap.deleted_at' => 'IS NULL',
                'AND',
                'rawat_inap.bed' => '= ?',
                'AND',
                'rawat_inap.waktu_keluar' => 'IS NULL'
            ), array(
                $parameter['bed']
            ))
            ->execute();
        if(count($CheckRanjang['response_data']) > 0) {
            $allowSave = false;
        } else {
            $allowSave = true;
        }

        if($allowSave) {
            $old = self::$query->select('rawat_inap', array(
                'uid', 'pasien', 'dokter', 'penjamin', 'kunjungan', 'waktu_masuk', 'waktu_keluar', 'kamar', 'bed', 'keterangan',
                'created_at', 'updated_at', 'deleted_at', 'jenis_pulang', 'alasan_pulang', 'petugas', 'nurse_station'
            ))
                ->where(array(
                    'rawat_inap.deleted_at' => 'IS NULL',
                    'AND',
                    'rawat_inap.uid' => '= ?'
                ), array(
                    $parameter['uid']
                ))
                ->execute();


            //Dapatkan Nurse Station
            $NurseStation = self::$query->select('nurse_station_ranjang', array(
                'nurse_station'
            ))
                ->order(array(
                    'created_at' => 'DESC'
                ))
                ->where(array(
                    'nurse_station_ranjang.ranjang' => '= ?',
                    'AND',
                    'nurse_station_ranjang.deleted_at' => 'IS NULL'
                ), array(
                    $parameter['bed']
                ))
                ->execute();


            $worker = self::$query->update('rawat_inap', array(
                'petugas' => $UserData['data']->uid,
                'kamar' => $parameter['kamar'],
                'bed' => $parameter['bed'],
                'keterangan' => $parameter['keterangan'],
                'nurse_station' => $NurseStation['response_data'][0]['nurse_station'],
                'updated_at' => parent::format_date(),
                'waktu_masuk' => parent::format_date()
            ))
                ->where(array(
                    'rawat_inap.deleted_at' => 'IS NULL',
                    'AND',
                    'rawat_inap.uid' => '= ?'
                ), array(
                    $parameter['uid']
                ))
                ->execute();

            if($worker['response_result'] > 0) {

                if($parameter['bed'] !== $old['response_data'][0]['bed']) {

                    //Check perpindahan nurse station untuk auto mutasi stok obat yang ada pada pasien bersangkutan
                    if($NurseStation['response_data'][0]['nurse_station'] !== $old['response_data'][0]['nurse_station']) {
                        $Inventori = new Inventori(self::$pdo);

                        $OldUnit = self::get_ns_detail($old['response_data'][0]['nurse_station'])['response_data'][0];
                        $NewUnit = self::get_ns_detail($NurseStation['response_data'][0]['nurse_station'])['response_data'][0];
                        $itemDetailMutasi = array();


                        //Ambil semua resep yang pernah dibuat
                        $Resep = self::$query->select('resep', array(
                            'uid'
                        ))
                            ->where(array(
                                'resep.kunjungan' => '= ?',
                                'AND',
                                'resep.deleted_at' => 'IS NULL'
                            ), array(
                                $parameter['kunjungan']
                            ))
                            ->execute();
                        foreach ($Resep['response_data'] as $RKey => $RValue) { //Detail Obat yang pernah dibuat
                            $ResepChange = self::$query->select('resep_change_log', array(
                                'item'
                            ))
                                ->where(array(
                                    'resep_change_log.resep' => '= ?'
                                ), array(
                                    $RValue['uid']
                                ))
                                ->execute();
                            foreach ($ResepChange['response_data'] as $RCKey => $RCValue) { //Check semua stok yang ada

                                $NSStok = self::$query->select('rawat_inap_batch', array(
                                    'obat',
                                    'qty',
                                    'batch'
                                ))
                                    ->where(array(
                                        'rawat_inap_batch.resep' => '= ?',
                                        'AND',
                                        'rawat_inap_batch.gudang' => '= ?',
                                        'AND',
                                        'rawat_inap_batch.pasien' => '= ?'
                                    ), array(
                                        $RValue['uid'],
                                        $OldUnit['gudang'],
                                        $old['response_data'][0]['pasien']
                                    ))
                                    ->execute();

                                foreach ($NSStok['response_data'] as $NSKey => $NSValue) {
                                    if(!isset($itemDetailMutasi[$NSValue['obat'] . '|' . $NSValue['batch']])) {
                                        $itemDetailMutasi[$NSValue['obat'] . '|' . $NSValue['batch']] = array(
                                            'mutasi' => floatval($NSValue['qty']),
                                            'keterangan' => 'Mutasi pindah rawat inap'
                                        );
                                    }
                                }

                            }
                        }



                        $Mutasi = $Inventori->tambah_mutasi(array(
                            'access_token' => $parameter['access_token'],
                            'dari' => $OldUnit['gudang'],
                            'ke' => $NewUnit['gudang'],
                            'keterangan' => 'Kebutuhan pindah Nurse Station Rawat Inap',
                            'inap' => true,
                            'item' => $itemDetailMutasi
                        ));

                        $NSLog['mutasi'] = $Mutasi;

                        if($Mutasi['response_result'] > 0) {

                            //Update batch rawat inap
                            foreach ($Resep['response_data'] as $RKey => $RValue) {
                                foreach ($itemDetailMutasi as $mutBatch => $mutValue) {
                                    if (floatval($mutValue['mutasi']) > 0) {
                                        $BarangBatch = explode('|', $mutBatch);

                                        $updateBatchInapLama = self::$query->update('rawat_inap_batch', array(
                                            'qty' => 0,
                                            'deleted_at' => parent::format_date(),
                                            'updated_at' => parent::format_date()
                                        ))
                                            ->where(array(
                                                'rawat_inap_batch.obat' => '= ?',
                                                'AND',
                                                'rawat_inap_batch.batch' => '= ?',
                                                'AND',
                                                'rawat_inap_batch.resep' => '= ?',
                                                'AND',
                                                'rawat_inap_batch.gudang' => '= ?',
                                                'AND',
                                                'rawat_inap_batch.pasien' => '= ?',
                                                'AND',
                                                'rawat_inap_batch.deleted_at' => 'IS NULL'
                                            ), array(
                                                $BarangBatch[0],
                                                $BarangBatch[1],
                                                $RValue['uid'],
                                                $OldUnit['gudang'],
                                                $old['response_data'][0]['pasien']
                                            ))
                                            ->execute();


                                        $updateBatchInapBaru = self::$query->insert('rawat_inap_batch', array(
                                            'gudang' => $NewUnit['gudang'],
                                            'pasien' => $old['response_data'][0]['pasien'],
                                            'resep' => $RValue['uid'],
                                            'obat' => $BarangBatch[0],
                                            'batch' => $BarangBatch[1],
                                            'qty' => floatval($mutValue['mutasi']),
                                            'created_at' => parent::format_date(),
                                            'updated_at' => parent::format_date()
                                        ))
                                            ->execute();
                                    }
                                }
                            }
                        }
                    }

                    //Charge Biaya Kamar
                    $Bed = new Bed(self::$pdo);
                    $BedInfo = $Bed->get_bed_detail('master_unit_bed', $parameter['bed']);

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
                            'keterangan' => 'Tagihan rawat inap'
                        );
                        $NewInvoice = $Invoice->create_invoice($InvMasterParam);
                        $TargetInvoice = $NewInvoice['response_unique'];
                    }

                    $InvoiceDetail = $Invoice->append_invoice(array(
                        'invoice' => $TargetInvoice,
                        'item' => $parameter['bed'],
                        'item_origin' => 'master_unit_bed',
                        'qty' => 1,
                        'harga' => $BedInfo['response_data'][0]['tarif'],
                        'status_bayar' => 'N',
                        'subtotal' => $BedInfo['response_data'][0]['tarif'],
                        'discount' => 0,
                        'discount_type' => 'N',
                        'pasien' => $parameter['pasien'],
                        'penjamin' => $parameter['penjamin'],
                        'billing_group' => 'tarif_kamar',
                        'keterangan' => 'Biaya Kamar Rawat Inap',
                        'departemen' => __POLI_INAP__
                    ));
                }

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
                        $parameter['uid'],
                        $UserData['data']->uid,
                        'rawat_inap',
                        'U',
                        json_encode($old['response_data'][0]),
                        json_encode($parameter),
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                ));
            }

            $worker['invoice'] = $InvoiceDetail;
            $worker['ns'] = $NSLog;
            return $worker;
        } else {
            return array(
                'response_result' => 0,
                'response_message' => 'Pelayanan Nurse station tidak kosong'
            );
        }
    }

    private function tambah_inap($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $uid = parent::gen_uuid();
        $worker = self::$query->insert('rawat_inap', array(
            'uid' => $uid,
            'pasien' => $parameter['pasien'],
            'dokter' => $parameter['dokter'],
            'penjamin' => $parameter['penjamin'],
            //'waktu_masuk' => date('Y-m-d', strtotime($parameter['waktu_masuk'])),
            'waktu_masuk' => date('Y-m-d'),
            //'kamar' => $parameter['kamar'],
            //'bed' => $parameter['bed'],
            'asal' => (($parameter['asal'] === 'igd') ? __POLI_IGD__ : $parameter['poli_asal']),
            'kunjungan' => $parameter['kunjungan'],
            'keterangan' => $parameter['keterangan'],
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->execute();

        if($worker['response_result'] > 0)
        {
            //Check asal
            if($parameter['asal'] === 'igd') {
                $updateIGD = self::$query->update('igd', array(
                    'jenis_pulang' => 'I',
                    'alasan_pulang' => $parameter['keterangan'],
                    'waktu_keluar' => parent::format_date()
                ))
                    ->where(array(
                        'igd.kunjungan' => '= ?',
                        'AND',
                        'igd.deleted_at' => 'IS NULL'
                    ), array(
                        $parameter['kunjungan']
                    ))
                    ->execute();
            }
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
                    $uid,
                    $UserData['data']->uid,
                    'rawat_inap',
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

    private function tambah_asesmen($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $Antrian = new Antrian(self::$pdo);
        $parameter['dataObj'] = array(
            'departemen' => $parameter['poli'],
            'pasien' => $parameter['pasien'],
            'penjamin' => $parameter['penjamin'],
            'prioritas' => 36,
            'dokter' => $UserData['data']->uid
        );
        $AntrianProses = $Antrian->tambah_antrian('antrian', $parameter, $parameter['kunjungan']);

        return $AntrianProses;
    }
}
?>