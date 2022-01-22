<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Invoice as Invoice;
use PondokCoder\Penjamin as Penjamin;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class IGD extends Utility
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
                //Delete Ranjang NS dan Petugas
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
                //Pasien Aktif
                $IGDNS = self::$query->delete('igd')
                        ->where(array(
                            'igd.nurse_station' => '= ?'
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

    public function __GET__ ($parameter = array())
    {
        switch ($parameter[1])
        {
            case 'detail':
                return self::get_detail($parameter[2]);
                break;
            case 'sedia_obat':
                return self::sedia_obat(array(
                    'resep' => $parameter[2],
                    'pasien' => $parameter[3],
                    'obat' => $parameter[4]
                ));
                break;
                break;
            default:
                return array();
                break;
        }
    }

    public function __POST__ ($parameter = array())
    {
        switch ($parameter['request'])
        {
            case 'tambah_igd':
                return self::tambah_igd($parameter);
                break;
            case 'get_igd':
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
            case 'riwayat_obat_igd':
                return self::riwayat_obat_igd($parameter);
                break;
            case 'tambah_riwayat_resep_igd':
                return self::tambah_riwayat_resep_igd($parameter);
                break;
            case 'tambah_nurse_station':
                return self::tambah_nurse_station($parameter);
                break;
            case 'edit_nurse_station':
                return self::edit_nurse_station($parameter);
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

    private function get_detail() {
        //
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

    private function edit_nurse_station($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $ranjangProc = array();

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
                array_push($ranjangProc, $entry_ranjang);
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
        $process['ranjang'] = $ranjangProc;
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
                'type' => 'IGD',
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

    private function sedia_obat($parameter) {
        $data = self::$query->select('igd_batch', array(
            'qty',
            'batch',
            'resep'
        ))
            ->where(array(
                'igd_batch.resep' => '= ?',
                'AND',
                'igd_batch.pasien' => '= ?',
                'AND',
                'igd_batch.obat' => '= ?'
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

    private function konfirmasi_retur_obat($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $Inventori = new Inventori(self::$pdo);
        $parsedItem = array();

        $usedBatchInap = $parameter['item'];

        foreach ($usedBatchInap as $bKey => $bValue) {

            //Proses Stok
            $oldStok = self::$query->select('igd_batch', array(
                'gudang',
                'pasien',
                'resep',
                'obat',
                'qty',
                'batch'
            ))
                ->where(array(
                    'igd_batch.pasien' => '= ?',
                    'AND',
                    'igd_batch.batch' => '= ?',
                    'AND',
                    'igd_batch.obat' => '= ?',
                    'AND',
                    'igd_batch.gudang' => '= ?'
                ), array(
                    $parameter['pasien'],
                    $bValue['batch'],
                    $bValue['obat'],
                    $parameter['gudang']
                ))
                ->execute();
            if(count($oldStok['response_data']) > 0) {
                $updateStok = self::$query->update('igd_batch', array(
                    'qty' => floatval($oldStok['response_data'][0]['qty']) - floatval($bValue['aktual'])
                ))
                    ->where(array(
                        'igd_batch.pasien' => '= ?',
                        'AND',
                        'igd_batch.batch' => '= ?',
                        'AND',
                        'igd_batch.obat' => '= ?',
                        'AND',
                        'igd_batch.gudang' => '= ?'
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

        //Dulu dimutasikan, sekarangn dianggurin aja dikipas kipas pake sampul majalah bobo
        if(count($parameter['item']) > 0) {
            $Mutasi = $Inventori->tambah_mutasi(array(
                'access_token' => $parameter['access_token'],
                'dari' => $parameter['gudang'],
                'status' => $parameter['status'],
                'ke' => __GUDANG_APOTEK__,
                'keterangan' => 'Retur Obat IGD. Pasien a.n. ' . $parameter['nama_pasien'] . ' : ' . $parameter['remark'],
                'special_code_out' => __STATUS_BARANG_KELUAR_INAP__,
                'special_code_in' => __STATUS_BARANG_MASUK_INAP__,
                'item' => $parsedItem
            ));
        }


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

        $TargetInvoice = $InvoiceCheck['response_data'][0]['uid'];

        if($Mutasi['response_result'] > 0) {

            $mutasi_uid = $Mutasi['response_unique'];
            foreach ($usedBatchInap as $bKey => $bValue) {
                if(floatval($bValue['aktual']) > 0) {
                    $proceed_catat = self::$query->insert('igd_retur_obat', array(
                        'uid_igd' => $parameter['uid'],
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

        return array(
            'kunjungan' => $Kunjungan,
            'pulang' => $Pulang,
            'invoice' => $InvoiceCheck
        );
    }

    private function kalkulasi_sisa_obat_2($parameter) {
        $Inventori = new Inventori(self::$pdo);
        $filteredResep = array();
        $data = self::$query->select('igd_batch', array(
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
                array('igd_batch.resep', '=', 'resep.uid'),
                array('resep.kunjungan', '=', 'kunjungan.uid')
            ))
            ->where(array(
                'igd_batch.gudang' => '= ?',
                'AND',
                'igd_batch.pasien' => '= ?',
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
            $berikan = self::$query->select('igd_riwayat_obat', array(
                'qty'
            ))
                ->where(array(
                    'igd_riwayat_obat.resep' => '= ?',
                    'AND',
                    'igd_riwayat_obat.obat' => '= ?',
                    'AND',
                    'igd_riwayat_obat.nurse_station' => '= ?'
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

    private function tambah_riwayat_resep_igd($parameter) {
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
                $process = self::$query->insert('igd_riwayat_obat', array(
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
                            'keterangan' => 'Pemberian Obat IGD. ' . $value['keterangan']
                        ))
                            ->execute();

                        if($stok_log['response_result'] > 0) {
                            $OldStokNS = self::$query->select('igd_batch', array(
                                'id',
                                'qty'
                            ))
                                ->where(array(
                                    'igd_batch.resep' => '= ?',
                                    'AND',
                                    'igd_batch.obat' => '= ?',
                                    'AND',
                                    'igd_batch.batch' => '= ?',
                                    'AND',
                                    'igd_batch.gudang' => '= ?'
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
                                $HistoryResep = self::$query->select('igd_riwayat_obat', array(
                                    'qty'
                                ))
                                    ->where(array(
                                        'igd_riwayat_obat.resep' => '= ?',
                                        'AND',
                                        'igd_riwayat_obat.obat' => '= ?'
                                    ), array(
                                        $value['resep'],
                                        $value['barang']
                                    ))
                                    ->execute();
                                foreach ($HistoryResep['response_data'] as $KSLKey => $KSLValue) {
                                    $TotalPenggunaan += floatval($KSLValue['qty']);
                                }

                                //Kurangi Stok NS
                                $updateStokNS = self::$query->update('igd_batch', array(
                                    'qty' => (floatval($QtyResep['response_data'][0]['qty']) - $TotalPenggunaan)
                                ))
                                    ->where(array(
                                        'igd_batch.id' => '= ?'
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

    private function riwayat_obat_igd($parameter) {
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
            $data = self::$query->select('igd_riwayat_obat', array(
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
                    array('igd_riwayat_obat.petugas', '=', 'pegawai.uid'),
                    array('igd_riwayat_obat.resep', '=', 'resep.uid'),
                    array('resep.pasien', '=', 'pasien.uid')
                ))
                ->order(array(
                    'logged_at' => 'DESC'
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('igd_riwayat_obat', array(
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
                    array('igd_riwayat_obat.petugas', '=', 'pegawai.uid'),
                    array('igd_riwayat_obat.resep', '=', 'resep.uid'),
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



        $itemTotal = self::$query->select('igd_riwayat_obat', array(
            'id'
        ))
            ->join('pegawai', array(
                'nama as nama_petugas'
            ))
            ->on(array(
                array('igd_riwayat_obat.petugas', '=', 'pegawai.uid')
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
                $CheckRanjang = self::$query->select('igd', array(
                    'pasien',
                    'dokter'
                ))
                    ->join('pasien', array(
                        'nama as nama_pasien'
                    ))
                    ->on(array(
                        array('igd.pasien', '=', 'pasien.uid')
                    ))
                    ->where(array(
                        'igd.deleted_at' => 'IS NULL',
                        'AND',
                        'igd.bed' => '= ?',
                        'AND',
                        'igd.nurse_station' => '= ?',
                        'AND',
                        'igd.waktu_keluar' => 'IS NULL'
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

            $paramValue = array('IGD');
        } else {
            $paramData = array(
                'nurse_station.deleted_at' => 'IS NULL',
                'AND',
                'nurse_station.type' => '= ?'
            );

            $paramValue = array('IGD');
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
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $worker = self::$query->update('igd', array(
            'waktu_keluar' => parent::format_date(),
            'jenis_pulang' => $parameter['jenis'],
            'alasan_pulang' => $parameter['keterangan']
        ))
            ->where(array(
                'igd.pasien' => '= ?',
                'AND',
                'igd.deleted_at' => 'IS NULL'
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
            $paramData = array(
                'igd.deleted_at' => 'IS NULL',
                /*'AND',
                'igd.dokter' => '= ?',*/
                'AND',
                'igd.waktu_keluar' => 'IS NULL',
                'AND',
                '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
            );

            //$paramValue = array($UserData['data']->uid);
            $paramValue = array();
        } else {
            $paramData = array(
                'igd.deleted_at' => 'IS NULL',
                /*'AND',
                'igd.dokter' => '= ?',*/
                'AND',
                'igd.waktu_keluar' => 'IS NULL'
                /*'AND',
                'master_inv.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''*/
            );

            //$paramValue = array($UserData['data']->uid);
            $paramValue = array();
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('igd', array(
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
                'nurse_station',
                'created_at',
                'updated_at'
            ))
                ->join('pasien', array(
                    'uid as uid_pasien',
                    'nama as nama_pasien',
                    'no_rm'
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
                'waktu_masuk',
                'waktu_keluar',
                'kamar',
                'kunjungan',
                'bed',
                'keterangan',
                'dokter',
                'penjamin',
                'nurse_station',
                'created_at',
                'updated_at'
            ))
                ->join('pasien', array(
                    'uid as uid_pasien',
                    'nama as nama_pasien',
                    'no_rm'
                ))
                ->on(array(
                    array('igd.pasien', '=', 'pasien.uid')
                ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $Pasien = new Pasien(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);
        $Ruangan = new Ruangan(self::$pdo);
        $Bed = new Bed(self::$pdo);
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;

            //Pasien
            
            $PasienDetail = $Pasien::get_pasien_detail('pasien', $value['pasien']);
            $data['response_data'][$key]['pasien'] = $PasienDetail['response_data'][0];

            //Dokter
            
            $PegawaiDetail = $Pegawai->get_detail($value['dokter']);
            $data['response_data'][$key]['dokter'] = $PegawaiDetail['response_data'][0];

            //Penjamin
            
            $PenjaminDetail = $Penjamin->get_penjamin_detail($value['penjamin']);
            $data['response_data'][$key]['penjamin'] = $PenjaminDetail['response_data'][0];

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
                    'nurse_station_ranjang.deleted_at' => 'IS NULL',
                    'AND',
                    'nurse_station.uid' => '= ?'
                ), array(
                    $value['bed'], $value['nurse_station']
                ))
                ->execute();
            $data['response_data'][$key]['nurse_station'] = $NurseStation['response_data'][0];

            //Ruangan
            
            $RuanganDetail = $Ruangan->get_ruangan_detail('master_unit_ruangan', $value['kamar']);
            $data['response_data'][$key]['kamar'] = $RuanganDetail['response_data'][0];

            //Bed
            
            $BedDetail = $Bed->get_bed_detail('master_unit_bed', $value['bed']);
            $data['response_data'][$key]['bed'] = $BedDetail['response_data'][0];

            $data['response_data'][$key]['waktu_masuk_tanggal'] = date('d F Y', strtotime($value['waktu_masuk']));
            $data['response_data'][$key]['waktu_masuk_jam'] = date('H:i', strtotime($value['waktu_masuk']));


            $autonum++;
        }

        $itemTotal = self::$query->select('igd', array(
            'uid'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function tambah_igd($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);
        $uid = parent::gen_uuid();
        $worker = self::$query->insert('igd', array(
            'uid' => $uid,
            'pasien' => $parameter['pasien'],
            'dokter' => $parameter['dokter'],
            'penjamin' => $parameter['penjamin'],
            'waktu_masuk' => date('Y-m-d', strtotime($parameter['waktu_masuk'])),
            'kamar' => $parameter['kamar'],
            'bed' => $parameter['bed'],
            'kunjungan' => $parameter['kunjungan'],
            'keterangan' => $parameter['keterangan'],
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->execute();

        if($worker['response_result'] > 0)
        {
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
                    'igd',
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
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $Antrian = new Antrian(self::$pdo);
        $parameter['dataObj'] = array(
            'departemen' => $parameter['poli'],
            'pasien' => $parameter['pasien'],
            'penjamin' => $parameter['penjamin'],
            'prioritas' => 36,
            'dokter' => $UserData['data']->uid
        );
        $AntrianProses = $Antrian::tambah_antrian('antrian', $parameter, $parameter['kunjungan']);

        return $AntrianProses;
    }
}
?>