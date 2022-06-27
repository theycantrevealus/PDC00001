<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\Authorization as Authorization;
use PondokCoder\QueryException as QueryException;
use PondokCoder\PO as PO;
use PondokCoder\Penjamin as Penjamin;
use PondokCoder\Pegawai as Pegawai;
use PondokCoder\Pasien as Pasien;
use PondokCoder\Unit as Unit;
use PondokCoder\Utility as Utility;

class Inventori extends Utility
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
                case 'lend_detail':
                    return self::lend_detail($parameter[2]);
                    break;
                case 'kategori':
                    return self::get_kategori();
                    break;
                case 'kategori_detail':
                    return self::get_kategori_detail($parameter[2]);
                    break;
                case 'satuan':
                    return self::get_satuan();
                    break;
                case 'return_detail':
                    return self::get_return_detail($parameter[2]);
                    break;
                case 'satuan_detail':
                    return self::get_satuan_detail($parameter[2]);
                    break;
                case 'gudang':
                    return self::get_gudang();
                    break;
                case 'gudang_detail':
                    return self::get_gudang_detail($parameter[2]);
                    break;
                case 'item_detail':
                    return self::get_item_detail($parameter[2]);
                    break;
                case 'kartu_stok':

                    $ItemDetail = self::get_item_detail($parameter[2]);
                    $ItemStokLog = self::get_item_stok_log($parameter[2], $parameter[3], $parameter[4], $parameter[5]);
                    $ItemDetail['response_data'][0]['log'] = $ItemStokLog['response_data'];
                    return $ItemDetail;

                    break;
                case 'manufacture':
                    return self::get_manufacture();
                    break;
                case 'manufacture_detail':
                    return self::get_manufacture_detail($parameter[2]);
                    break;
                case 'kategori_obat':
                    return self::get_kategori_obat();
                    break;
                case 'kategori_obat_detail':
                    return self::get_kategori_obat_detail($parameter[2]);
                    break;
                case 'kategori_per_obat':
                    return self::get_kategori_obat_item_parsed($parameter[2]);
                    break;
                case 'item_batch':
                    return self::get_item_batch($parameter[2]);
                    break;
                case 'get_amprah_detail':
                    return self::get_amprah_detail($parameter[2]);
                    break;
                case 'get_amprah_proses_detail':
                    return self::get_amprah_proses_detail($parameter[2]);
                    break;
                case 'get_stok':
                    return self::get_stok();
                    break;
                case 'get_stok_log':
                    return self::get_stok_log();
                    break;
                case 'get_opname_detail':
                    return self::get_opname_detail($parameter[2]);
                    break;
                case 'get_item_select2':
                    return self::get_item_select2($parameter);
                    break;
                case 'get_mutasi_item':
                    return self::get_mutasi_item($parameter[2]);
                    break;
                case 'post_opname_strategy_load':
                    return self::post_opname_strategy_load($parameter);
                    break;
                case 'check_temp_transact':
                    return self::check_temp_transact($parameter);
                    break;
                default:
                    return self::get_item_select2($parameter);
            }
        } catch (QueryException $e) {
            return 'Error => ' . $e;
        }
    }

    public function __DELETE__($parameter = array())
    {
        return self::delete($parameter);
    }

    public function __POST__($parameter = array())
    {
        switch ($parameter['request']) {
            case 'manual_add_batch':
                return self::manual_add_batch($parameter);
                break;
            case 'finish_temp_stock':
                return self::finish_temp_stock($parameter);
                break;
            case 'auto_so_prog':
                return self::auto_so_prog($parameter);
                break;
            case 'hapus_pinjam_keluar':
                return self::hapus_pinjam_keluar($parameter);
                break;
            case 'approve_pinjam_keluar':
                return self::approve_pinjam_keluar($parameter);
                break;
            case 'lend_data':
                return self::lend_data($parameter);
                break;
            case 'pinjam_keluar':
                return self::pinjam_keluar($parameter);
                break;
            case 'pinjam_keluar_edit':
                return self::pinjam_keluar_edit($parameter);
                break;
            case 'pinjam_approve':
                return self::pinjam_approve($parameter);
                break;
            case 'approve_permintaan_amprah':
                return self::approve_permintaan_amprah($parameter);
                break;
            case 'retur_po':
                return self::retur_po($parameter);
                break;
            case 'tambah_kategori':
                return self::tambah_kategori($parameter);
                break;
            case 'edit_kategori':
                return self::edit_kategori($parameter);
                break;
            case 'tambah_satuan':
                return self::tambah_satuan($parameter);
                break;
            case 'tambah_gudang':
                return self::tambah_gudang($parameter);
                break;
            case 'edit_gudang':
                return self::edit_gudang($parameter);
                break;
            case 'edit_satuan':
                return self::edit_satuan($parameter);
                break;
            case 'tambah_manufacture':
                return self::tambah_manufacture($parameter);
                break;
            case 'edit_manufacture':
                return self::edit_manufacture($parameter);
                break;
            case 'tambah_item':
                return self::tambah_item($parameter);
                break;
            case 'edit_item':
                return self::edit_item($parameter);
                break;
            case 'tambah_kategori_obat':
                return self::tambah_kategori_obat($parameter);
                break;
            case 'edit_kategori_obat':
                return self::edit_kategori_obat($parameter);
                break;
            case 'tambah_amprah':
                return self::tambah_amprah($parameter);
                break;
            case 'get_amprah_request':
                return self::get_amprah_request($parameter);
                break;
            case 'get_amprah_request_finish':
                return self::get_amprah_request($parameter, 'S');
                break;
            case 'proses_amprah':
                return self::proses_amprah($parameter);
                break;
            case 'tambah_stok_awal':
                return self::tambah_stok_awal($parameter);
                break;
            case 'get_stok_gudang':
                return self::get_stok_gudang($parameter);
                break;
            case 'get_stok_gudang_opname':
                return self::get_stok_gudang_opname($parameter);
                break;
            case 'get_opname_history':
                return self::get_opname_history($parameter);
                break;
            case 'tambah_opname':
                return self::tambah_opname($parameter);
                break;
            case 'get_opname_detail_item':
                return self::get_opname_detail_item($parameter);
                break;
            case 'tambah_mutasi':
                return self::tambah_mutasi($parameter);
                break;
            case 'get_mutasi_request':
                return self::get_mutasi_request($parameter);
                break;
            case 'master_inv_import_fetch':
                return self::master_inv_import_fetch($parameter);
                break;
            case 'proceed_import_master_inv':
                return self::proceed_import_master_inv($parameter);
                break;
            case 'get_item_back_end':
                return self::get_item_back_end($parameter);
                break;
            case 'get_item_select2':
                return self::get_item_select2($parameter);
                break;
            case 'satu_harga_profit':
                return self::satu_harga_profit($parameter);
                break;
            case 'get_stok_back_end':
                return self::get_stok_back_end($parameter);
                break;
            case 'stok_import_fetch':
                return self::stok_import_fetch($parameter);
                break;
            case 'stok_import_fetch_auto_so':
                return self::stok_import_fetch_auto_so($parameter);
                break;
            case 'proceed_import_stok':
                return self::proceed_import_stok($parameter);
                break;
            case 'proceed_sync_harga':
                return self::proceed_sync_harga($parameter);
                break;
            case 'get_stok_log_backend':
                return self::get_stok_log_backend($parameter);
                break;

            case 'proses_mutasi':
                return self::proses_mutasi($parameter);
                break;

            case 'get_stok_batch_unit':
                return self::get_stok_batch_unit($parameter);
                break;

            case 'reset_stok_log':
                return self::reset_stok_log();
                break;

            case 'opname_warehouse':
                return self::opname_warehouse($parameter);
                break;

            case 'post_opname_warehouse':
                return self::post_opname_warehouse($parameter);
                break;

            case 'post_opname_strategy':
                return self::post_opname_strategy($parameter);
                break;
                
            case 'get_temp_transact':
                return self::get_temp_transact($parameter);
                break;

            case 'stok_monitoring':
                return self::stok_monitoring($parameter);
                break;

            case 'stok_activity':
                return self::stok_activity($parameter);
                break;

            case 'get_return_entry':
                return self::get_return_entry($parameter);
                break;

            case 'data_populate_export_stok':
                return self::data_populate_export_stok($parameter);
                break;

            case 'export_current_gudang_stok':
                return self::export_current_gudang_stok($parameter);
                break;

            default:
                return $parameter;
                break;
        }
    }

    private function manual_add_batch($parameter) {
        $check = self::$query->select('inventori_batch', array(
            'uid'
        ))
            ->where(array(
                'inventori_batch.barang' => '= ?',
                'AND',
                'inventori_batch.batch' => '= ?',
                'AND',
                'inventori_batch.expired_date' => '= ?',
                'AND',
                'inventori_batch.deleted_at' => 'IS NULL'
            ), array(
                $parameter['uid_barang'],
                $parameter['kode_batch'],
                date('Y-m-d', strtotime($parameter['ed']))
            ))
            ->execute();
        if(count($check['response_data']) > 0) {
            $targetBatch = $check['response_data'][0]['uid'];
            $proc = self::$query->update('inventori_batch', array(
                'deleted_at' => NULL,
                'updated_at' => parent::format_date()
            ))
                ->where(array(
                    'inventori_batch.uid' => '= ?'
                ), array(
                    $targetBatch
                ))
                ->execute();
        } else {
            $targetBatch = parent::gen_uuid();
            $proc = self::$query->insert('inventori_batch', array(
                'uid' => $targetBatch,
                'barang' => $parameter['uid_barang'],
                'batch' => $parameter['kode_batch'],
                'expired_date' => date('Y-m-d', strtotime($parameter['ed'])),
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();
        }
        if($proc['response_result'] > 0) {
            //Tambahkan ke inventori_stok
            $checkStok = self::$query->select('inventori_stok', array(
                'id'
            ))
                ->where(array(
                    'inventori_stok.batch' => '= ?',
                    'AND',
                    'inventori_stok.barang' => '= ?',
                    'AND',
                    'inventori_stok.gudang' => '= ?'
                ), array(
                    $targetBatch,
                    $parameter['uid_barang'],
                    $parameter['uid_gudang']
                ))
                ->execute();
            if(count($checkStok['response_data']) > 0) {
                //
            } else {
                $procStok = self::$query->insert('inventori_stok', array(
                    'barang' => $parameter['uid_barang'],
                    'batch' => $targetBatch,
                    'gudang' => $parameter['uid_gudang'],
                    'stok_terkini' => 0
                ))
                    ->execute();
            }
        }
        return $proc;
    }

    private function finish_temp_stock($parameter) {
        //Jika resep maka update status jadi D = Done
        // Jika mutasi pastikan gudang tujuan adalah IGD
    }


    private function auto_so_prog($parameter) {
        $DataSet = $parameter['dataSet'];

        $DataSet['nama'] = str_replace('^', '"', $DataSet['nama']);

        $dataFailed = array();
        $batchFailed = array();
        $result = 0;
        //Insert Auto SO
        $SOMaster = self::$query->select('inventori_stok_opname', array(
            'uid'
        ))
            ->where(array(
                'inventori_stok_opname.gudang' => '= ?',
                'AND',
                'inventori_stok_opname.status' => '= ?'
            ), array(
                $parameter['gudang'], 'P'
            ))
            ->execute();
        foreach($SOMaster['response_data'] as $SOK => $SOV) {
            //Check Item
            $Item = self::$query->select('master_inv', array(
                'uid'
            ))
                ->where(array(
                    'master_inv.deleted_at' => 'IS NULL',
                    'AND',
                    'master_inv.nama' => '= ?'
                ), array(
                    $DataSet['nama']
                ))
                ->execute();
            if(count($Item['response_data']) > 0) {
                $BatchTarget = '';
                $TotalAllCurrent = 0;

                //Check Batch Exist
                $BatchCheck = self::$query->select('inventori_batch', array(
                    'uid'
                ))
                    ->where(array(
                        'inventori_batch.batch' => '= ?',
                        'AND',
                        'inventori_batch.barang' => '= ?',
                        'AND',
                        'inventori_batch.expired_date' => '= ?',
                        'AND',
                        'inventori_batch.deleted_at' => 'IS NULL'
                    ), array(
                        $DataSet['batch'],
                        $Item['response_data'][0]['uid'],
                        date('Y-m-d', strtotime($DataSet['kedaluarsa']))
                    ))
                    ->execute();
                if(count($BatchCheck['response_data']) > 0) {
                    //Current Batch
                    $BatchTarget = $BatchCheck['response_data'][0]['uid'];
                    $UPDATEBATCH = self::$query->update('inventori_batch', array(
                        'updated_at' => parent::format_date(),
                        'deleted_at' => null
                    ))
                        ->where(array(
                            'inventori_batch.uid' => '= ?',
                            'AND',
                            'inventori_batch.deleted_at' => 'IS NULL'
                        ), array(
                            $BatchCheck['response_data'][0]['uid']
                        ))
                        ->execute();
                } else {
                    $BatchTarget = parent::gen_uuid();
                    //Create New Batch
                    $UPDATEBATCH = self::$query->insert('inventori_batch', array(
                        'uid' => $BatchTarget,
                        'barang' => $Item['response_data'][0]['uid'],
                        'batch' => $DataSet['batch'],
                        'expired_date' => date('Y-m-d', strtotime($DataSet['kedaluarsa'])),
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                }

                if($UPDATEBATCH['response_result'] > 0) {
                        
                } else {
                    array_push($batchFailed, $UPDATEBATCH);
                }


                $SODetailCheck = self::$query->select('inventori_stok_opname_detail', array(
                    'id', 'qty_akhir'
                ))
                    ->where(array(
                        'inventori_stok_opname_detail.item' => '= ?',
                        'AND',
                        'inventori_stok_opname_detail.batch' => '= ?',
                        'AND'
                    ), array(
                        $Item['response_data'][0]['uid'],
                        $BatchTarget
                    ))
                    ->execute();
                if(count($SODetailCheck['response_data']) > 0) {
                    //Accumulate Qty
                    $updateOpDet = self::$query->update('inventori_stok_opname_detail', array(
                        'updated_at' => parent::format_date(),
                        'qty_akhir' => floatval($SODetailCheck['response_data'][0]['qty_akhir']) + floatval($DataSet['stok'])
                    ))
                        ->where(array(
                            'inventori_stok_opname_detail.id' => '= ?'
                        ), array(
                            $SODetailCheck['response_data'][0]['id']
                        ))
                        ->execute();
                    $TotalAllCurrent = floatval($SODetailCheck['response_data'][0]['qty_akhir']) + floatval($DataSet['stok']);
                } else {
                    //New Qty
                    $updateOpDet = self::$query->insert('inventori_stok_opname_detail', array(
                        'opname' => $SOV['uid'],
                        'item' => $Item['response_data'][0]['uid'],
                        'batch' => $BatchTarget,
                        'qty_awal' => 0,
                        'qty_akhir' => floatval($DataSet['stok']),
                        'keterangan' => 'SO Auto',
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                    $TotalAllCurrent = floatval($DataSet['stok']);
                }

                if($updateOpDet['response_result'] > 0) {

                    $TotalForOpname = $TotalAllCurrent;
                    

                    //TODO : 1. Kalkulasi semua barang dengan batch ini dan lakukan kalkulasi otomatis
                    $LogBefore = self::$query->select('inventori_stok_log', array(
                        'id', 'masuk', 'keluar', 'saldo', 'jenis_transaksi'
                    ))
                        ->where(array(
                            'inventori_stok_log.barang' => '= ?',
                            'AND',
                            'inventori_stok_log.batch' => '= ?',
                            'AND',
                            'inventori_stok_log.gudang' => '= ?',
                            'AND',
                            'inventori_stok_log.logged_at' => '>= ?',
                            'AND',
                            'inventori_stok_log.type' => '!= ?'
                        ), array(
                            $Item['response_data'][0]['uid'],
                            $BatchTarget,
                            $parameter['gudang'],
                            date('Y-m-d H:i:s', strtotime($parameter['date_limit_opname'])),
                            __STATUS_OPNAME__
                        ))
                        ->order(array(
                            'logged_at' => 'ASC'
                        ))
                        ->execute();

                    foreach($LogBefore['response_data'] as $LOGBK =>$LOGBV) {
                        $BMasuk = floatval($LOGBV['masuk']);
                        $BKeluar = floatval($LOGBV['keluar']);
                        $BSaldo = floatval($LOGBV['saldo']);

                        if($BMasuk == 0 && $BKeluar > 0) { //Potong
                            $TotalAllCurrent -= floatval($BKeluar);
                        } else if($BMasuk > 0 && $BKeluar == 0) { //Tambah
                            $TotalAllCurrent += floatval($BMasuk);
                        } else {
                            $TotalAllCurrent = $BSaldo;
                        }

                        $UpSaldoLog = self::$query->update('inventori_stok_log', array(
                            'saldo' => $TotalAllCurrent
                        ))
                            ->where(array(
                                'inventori_stok_log.id' => '= ?'
                            ), array(
                                $LOGBV['id']
                            ))
                            ->execute();
                    }

                    //TODO : 2. Catat ke dalam inventori stok log sebagai opname. KODE : __STATUS_OPNAME__
                    $OpnameLOG = self::$query->insert('inventori_stok_log', array(
                        'barang' => $Item['response_data'][0]['uid'],
                        'batch' => $BatchTarget,
                        'gudang' => $parameter['gudang'],
                        'masuk' => $TotalForOpname,
                        'keluar' => 0,
                        'saldo' => $TotalForOpname,
                        'logged_at' => date('Y-m-d H:i:s', strtotime($parameter['date_limit_opname']) - 60),    // Minus 1 Minute (Agar tidak kena recalculate)
                        'type' => __STATUS_OPNAME__
                    ))
                        ->execute();

                    //TODO : 3. Catatkan hasil kalkulasi ke stok
                    //Check Stok
                    $LOGCheck = self::$query->select('inventori_stok', array(
                        'id'
                    ))
                        ->where(array(
                            'inventori_stok.barang' => '= ?',
                            'AND',
                            'inventori_stok.batch' => '= ?',
                            'AND',
                            'inventori_stok.gudang' => '= ?'
                        ), array(
                            $Item['response_data'][0]['uid'],
                            $BatchTarget,
                            $parameter['gudang']
                        ))
                        ->execute();
                    if(count($LOGCheck['response_data']) > 0) {
                        //Update STOK
                        $UPDATELOGPOSTOPNAME = self::$query->update('inventori_stok', array(
                            'stok_terkini' => $TotalAllCurrent
                        ))
                            ->where(array(
                                'inventori_stok.id' => '= ?'
                            ), array(
                                $LOGCheck['response_data'][0]['id']
                            ))
                            ->execute();
                    } else {
                        //Insert STOK
                        $UPDATELOGPOSTOPNAME = self::$query->insert('inventori_stok', array(
                            'barang' => $Item['response_data'][0]['uid'],
                            'batch' => $BatchTarget,
                            'gudang' => $parameter['gudang'],
                            'stok_terkini' => $TotalAllCurrent
                        ))
                            ->execute();
                    }

                    
                    if($UPDATELOGPOSTOPNAME['response_result'] > 0) {
                        
                    } else {
                        array_push($dataFailed, $DataSet);
                        array_push($batchFailed, $UPDATELOGPOSTOPNAME);
                    }
                    $result = $UPDATELOGPOSTOPNAME['response_result'];
                    
                } else {
                    //Opname Detail Not Set
                    $result = 0;
                    array_push($batchFailed, $updateOpDet);
                    array_push($dataFailed, $DataSet);
                }
            } else {
                //Item Not Exist
                $result = 0;
                array_push($batchFailed, 'Not Set');
                array_push($dataFailed, $DataSet);
            }
        }

        return array(
            'result' => $result,
            'failed' => $dataFailed,
            'batch_failed' => $batchFailed
        );
    }

    private function stok_import_fetch_auto_so($parameter)
    {
        if (!empty($_FILES['csv_file']['name'])) {
            $unique_name = array();

            $file_data = fopen($_FILES['csv_file']['tmp_name'], 'r');
            $column = fgetcsv($file_data); //array_head
            $row_data = array();
            while ($row = fgetcsv($file_data, null, ',', '"', '')) {
                $column_builder = array();
                foreach ($column as $key => $value) {
                    $column_builder[$value] = (isset($row[$key]) && !empty($row[$key])) ? strval($row[$key]) : "BELUM SET";
                }

                $tanggal_formatter = explode('-', str_replace('/','-',$column_builder['kedaluarsa']));
                $column_builder['kedaluarsa'] = date('Y-m-d', strtotime($tanggal_formatter[2] . '-' . $tanggal_formatter[1] . '-' . $tanggal_formatter[0]));
                $column_builder['stok'] = floatval($column_builder['stok']);
                array_push($row_data, $column_builder);
            }

            $build_col = array();
            foreach ($column as $key => $value) {
                array_push($build_col, array("data" => $value));
            }

            $output = array(
                'column' => $column,
                'row_data' => $row_data,
                'column_builder' => $build_col,
                'unique_name' => $unique_name
            );
            return $output;
        }
    }







    private function stok_import_fetch($parameter)
    {
        if (!empty($_FILES['csv_file']['name'])) {
            $unique_name = array();

            $file_data = fopen($_FILES['csv_file']['tmp_name'], 'r');
            $column = fgetcsv($file_data); //array_head
            $row_data = array();
            while ($row = fgetcsv($file_data)) {
                // if (!in_array($row[0], $unique_name)) {
                //     array_push($unique_name, $row[0]);
                //     $column_builder = array();
                //     foreach ($column as $key => $value) {
                //         $column_builder[$value] = (isset($row[$key]) && !empty($row[$key])) ? strval($row[$key]) : "BELUM SET";
                //     }
                //     array_push($row_data, $column_builder);
                // }
                $column_builder = array();
                foreach ($column as $key => $value) {
                    $column_builder[$value] = (isset($row[$key]) && !empty($row[$key])) ? strval($row[$key]) : "BELUM SET";
                }
                array_push($row_data, $column_builder);
            }

            $build_col = array();
            foreach ($column as $key => $value) {
                array_push($build_col, array("data" => $value));
            }

            $output = array(
                'column' => $column,
                'row_data' => $row_data,
                'column_builder' => $build_col,
                'unique_name' => $unique_name
            );
            return $output;
        }
    }

    private function proceed_sync_harga($parameter) {
        if (!empty($_FILES['csv_file']['name'])) {
            $unique_name = array();
            $affectedPODetail = array();
            $failedPODetail = array();

            $harga_tertinggi = array();

            $file_data = fopen($_FILES['csv_file']['tmp_name'], 'r');
            $column = fgetcsv($file_data); //array_head
            $row_data = array();
            while ($row = fgetcsv($file_data)) {
                $column_builder = array();
                foreach ($column as $key => $value) {
                    $column_builder[$value] = (isset($row[$key]) && !empty($row[$key])) ? strval($row[$key]) : "BELUM SET";
                }
                array_push($row_data, $column_builder);
            }

            $build_col = array();
            foreach ($column as $key => $value) {
                array_push($build_col, array("data" => $value));
            }

            foreach($row_data as $key => $value) {
                if(!empty($value['nama'])) {
                    $checkObat = self::$query->select('master_inv', array(
                        'uid',
                        'nama'
                    ))
                        ->where(array(
                            'master_inv.nama' => '= ?',
                            'AND',
                            'master_inv.deleted_at' => 'IS NULL'
                        ), array(
                            strtoupper(trim($value['nama']))
                        ))
                        ->execute();
                    if(count($checkObat['response_data']) > 0) {


                        if(!isset($harga_tertinggi[$checkObat['response_data'][0]['uid']])) {
                            $harga_tertinggi[$checkObat['response_data'][0]['uid']] = 0;
                        }

                        if(floatval($harga_tertinggi[$checkObat['response_data'][0]['uid']]) < floatval($value['harga'])) {
                            $harga_tertinggi[$checkObat['response_data'][0]['uid']] = floatval($value['harga']);
                        }
                    }
                }
            }

            foreach($row_data as $key => $value) {
                if(!empty($value['nama'])) {
                    $checkObat = self::$query->select('master_inv', array(
                        'uid',
                        'nama'
                    ))
                        ->where(array(
                            'master_inv.nama' => '= ?',
                            'AND',
                            'master_inv.deleted_at' => 'IS NULL'
                        ), array(
                            strtoupper(trim($value['nama']))
                        ))
                        ->execute();
                    if(count($checkObat['response_data']) > 0) {
                        $getPODetail = self::$query->select('inventori_po_detail', array(
                            'id', 'harga', 'qty'
                            ))
                            ->where(array(
                                'inventori_po_detail.barang' => '= ?'
                            ), array(
                                $checkObat['response_data'][0]['uid']
                            ))
                            ->execute();
                        if(count($getPODetail['response_data']) > 0) {
                            foreach($getPODetail['response_data'] as $POK => $POV) {
                                $updatePODetail = self::$query->update('inventori_po_detail', array(
                                    'harga' => floatval($harga_tertinggi[$checkObat['response_data'][0]['uid']]),
                                    'subtotal' => (floatval($POV['qty']) * floatval($harga_tertinggi[$checkObat['response_data'][0]['uid']]))
                                ))
                                    ->where(array(
                                        'inventori_po_detail.barang' => '= ?',
                                        'AND',
                                        'inventori_po_detail.id' => '= ?'
                                    ), array(
                                        $checkObat['response_data'][0]['uid'],
                                        $POV['id']
                                    ))
                                    ->execute();
                                if($updatePODetail['response_result'] > 0) {
                                    array_push($affectedPODetail, $updatePODetail);
                                } else {
                                    array_push($failedPODetail, $updatePODetail);
                                }
                            }
                        }
                    }
                }
            }

            $output = array(
                'affectedPODetail' => $affectedPODetail,
                'failedPODetail' => $failedPODetail,
                'column' => $column,
                'row_data' => $row_data,
                'column_builder' => $build_col,
                'unique_name' => $unique_name
            );
            return $output;
        }
    }

    private function proceed_import_stok($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);
        $PO = parent::gen_uuid();
        $PODetailResult = array();

        $duplicate_row = array();
        $non_active = array();
        $success_proceed = 0;
        $failed_data = array();
        $proceed_data = array();
        $all_data = array();

        $total_all = 0;



        foreach ($parameter['data_import'] as $key => $value) {

            $targettedObat = '';
            $targettedKategori = '';
            $targettedSupplier = '';
            $targettedSatuan = '';
            $targettedBatch = '';


            if($value['kategori'] != '') {
                $checkKategori = self::$query->select('master_inv_kategori', array(
                    'uid',
                    'nama'
                ))
                    ->where(array(
                        'master_inv_kategori.nama' => '= ?'
                    ), array(
                        $value['kategori']
                    ))
                    ->execute();
                if(count($checkKategori['response_data']) > 0) {
                    $targettedKategori = $checkKategori['response_data'][0]['uid'];
                } else {
                    $targettedKategori = parent::gen_uuid();
                    $kategoriBaru = self::$query->insert('master_inv_kategori', array(
                        'uid' => $targettedKategori,
                        'nama' => $value['kategori'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                }
            }

            if($value['supplier'] != '') {
                $checkSupplier = self::$query->select('master_supplier', array(
                    'uid'
                ))
                    ->where(array(
                        'master_supplier.nama' => '= ?'
                    ), array(
                        $value['supplier']
                    ))
                    ->execute();
                if(count($checkSupplier['response_data']) > 0) {
                    $targettedSupplier = $checkSupplier['response_data'][0]['uid'];
                } else {
                    $targettedSupplier = parent::gen_uuid();
                    $new_supplier = self::$query->insert('master_supplier', array(
                        'uid' => $targettedSupplier,
                        'nama' => $value['supplier'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                }
            }

            if($value['satuan'] != '') {
                //Satuan Obat
                $checkSatuan = self::$query->select('master_inv_satuan', array(
                    'uid',
                    'nama'
                ))
                    ->where(array(
                        'master_inv_satuan.nama' => '= ?'
                    ), array(
                        $value['satuan']
                    ))
                    ->execute();

                if(count($checkSatuan['response_data']) > 0) {
                    $targettedSatuan = $checkSatuan['response_data'][0]['uid'];
                } else {
                    $targettedSatuan = parent::gen_uuid();
                    $new_satuan = self::$query->insert('master_inv_satuan', array(
                        'uid' => $targettedSatuan,
                        'nama' => $value['satuan'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                }
            }

            if(!empty($value['nama'])) {
                $checkObat = self::$query->select('master_inv', array(
                    'uid',
                    'nama'
                ))
                    ->where(array(
                        'master_inv.nama' => '= ?',
                        'AND',
                        'master_inv.deleted_at' => 'IS NULL'
                    ), array(
                        strtoupper(trim(strval($value['nama'])))
                    ))
                    ->execute();
                if(count($checkObat['response_data']) > 0) {
                    $targettedObat = $checkObat['response_data'][0]['uid'];

                    //Update Info Obat
                    $updateObat = self::$query->update('master_inv', array(
                        'kategori' => $targettedKategori,
                        'satuan_terkecil' => $targettedSatuan,
                        'keterangan' => $value['nama_rko'],
                        'updated_at' => parent::format_date()
                    ))
                        ->where(array(
                            'master_inv.uid' => '= ?',
                            'AND',
                            'master_inv.deleted_at' => ' IS NULL'
                        ), array(
                            $targettedObat
                        ))
                        ->execute();
                } else {
                    //New Inventori
                    if(!empty($value['nama'])) {
                        $targettedObat = parent::gen_uuid();
                        $new_obat = self::$query->insert('master_inv', array(
                            'uid' => $targettedObat,
                            'nama' => strtoupper(trim(addslashes($value['nama']))),
                            'kategori' => $targettedKategori,
                            'keterangan' => $value['nama_rko'],
                            'satuan_terkecil' => $targettedSatuan,
                            'created_at' => parent::format_date(),
                            'updated_at' => parent::format_date()
                        ))
                            ->execute();

                        if($new_obat['response_result'] > 0) {
                            //$success_proceed += 1;
                        }

                        if($new_obat['response_result'] > 0) {
                            array_push($proceed_data, $new_obat);
                        } else {
                            $value['process'] = $new_obat;
                            array_push($failed_data, $value);
                        }
                    }
                }
            }



            if($targettedKategori === __UID_KATEGORI_OBAT) {
                $checkItem = array();

                if($value['generik'] === 'GENERIK') {
                    array_push($checkItem, __UID_GENERIK__);
                }

                if($value['antibiotik'] === 'ANTIBIOTIK') {
                    array_push($checkItem, __UID_ANTIBIOTIK__);
                }

                if($value['narkotika'] === 'NARKOTIKA') {
                    array_push($checkItem, __UID_NARKOTIKA__);
                }

                if($value['psikotropika'] === 'PSIKOTROPIKA') {
                    array_push($checkItem, __UID_PSIKOTROPIKA__);
                }
            }

            if($value['batch'] != '') {
                $checkBatch = self::$query->select('inventori_batch', array(
                    'uid'
                ))
                    ->where(array(
                        'inventori_batch.batch' => '= ?',
                        'AND',
                        'inventori_batch.barang' => '= ?',
                        'AND',
                        'inventori_batch.deleted_at' => 'IS NULL'
                    ), array(
                        $value['batch'],
                        $targettedObat
                    ))
                    ->execute();

                if(count($checkBatch['response_data']) > 0) {
                    $targettedBatch = $checkBatch['response_data'][0]['uid'];
                } else {
                    if(parent::validateDate($value['kedaluarsa'])) {
                        $targettedBatch = parent::gen_uuid();
                        $newBatch = self::$query->insert('inventori_batch', array(
                            'uid' => $targettedBatch,
                            'barang' => $targettedObat,
                            'po' => $PO,
                            'batch' => $value['batch'],
                            'expired_date' => date('Y-m-d', strtotime($value['kedaluarsa'])),
                            'created_at' => parent::format_date(),
                            'updated_at' => parent::format_date()
                        ))
                            ->execute();
                    }
                }
            }



            $total_all += floatval($value['harga']);

            //PO Detail
            $PODetail = self::$query->insert('inventori_po_detail', array(
                'po' => $PO,
                'barang' => $targettedObat,
                'qty' => floatval($value['stok']),
                'satuan' => $targettedSatuan,
                'harga' => floatval($value['harga']),
                'disc' => 0,
                'disc_type' => 'N',
                'subtotal' => (floatval($value['stok']) * floatval($value['harga'])),
                'keterangan' => 'AUTO PO [STOK AWAL - ' . $UserData['data']->nama . ']',
                'status' => 'L'/*,
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()*/
            ))
                ->execute();

            array_push($PODetailResult, $PODetail);

            if(floatval($value['stok']) > 0 || $parameter['gudang'] === __GUDANG_UTAMA__) {
                //Gudang utama perlu stok kosong
                //Di apotek perlu stok yang ada saja



                //Import Stok Obat
                //TODO : Sementara saja untuk harga Obat
                $StokAwal = self::$query->insert('inventori_stok', array(
                    'barang' => $targettedObat,
                    'batch' => $targettedBatch,
                    'gudang' => $parameter['gudang'],
                    'stok_terkini' => floatval($value['stok'])
                ))
                    ->execute();

                if($StokAwal['response_result'] > 0) {
                    $StokLog = self::$query->insert('inventori_stok_log', array(
                        'barang' => $targettedObat,
                        'batch' => $targettedBatch,
                        'gudang' => $parameter['gudang'],
                        'masuk' => floatval($value['stok']),
                        'keluar' => 0,
                        'saldo' => floatval($value['stok']),
                        'type' => __STATUS_STOK_AWAL__,
                        'logged_at' => parent::format_date(),
                        'jenis_transaksi' => 'inventori_batch',
                        'uid_foreign' => $targettedBatch,
                        'keterangan' => 'Stok Awal. Auto PO'
                    ))
                        ->execute();
                    if($StokLog['response_result'] > 0) {
                        $success_proceed += 1;   
                    }
                } else {
                    $value['process'] = $StokAwal;
                    array_push($failed_data, $value);
                }

                array_push($proceed_data, $StokAwal);
            }
        }


        //Auto Purchase List

        $Purchase = self::$query->insert('inventori_po', array(
            'uid' => $PO,
            'pegawai' => $UserData['data']->uid,
            'total' => floatval($total_all),
            'disc' => 0,
            'disc_type' => 'N',
            'total_after_disc' => $total_all,
            'keterangan' => 'AUTO [TIDAK ADA SUPPLIER] - AUTO HARGA JUAL',
            'status' => 'A',
            'nomor_po' => 'STOK_AWAL',
            'tanggal_po' => parent::format_date(),
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->execute();

        return array(
            'duplicate_row' => $duplicate_row,
            'non_active' => $non_active,
            'success_proceed' => $success_proceed,
            'failed_data' => $failed_data,
            'data' => $all_data,
            'po' => $Purchase,
            'po_detail' => $PODetailResult,
            'proceed' => $proceed_data
        );
    }

    private function pinjam_approve($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
    }

    private function lend_detail($parameter) {
        $data = self::$query->select('inventori_lend', array(
            'uid', 'kode', 'diajukan', 'disetujui', 'penerima', 'keterangan', 'created_at', 'updated_at'
        ))
            ->where(array(
                'deleted_at' => 'IS NULL',
                'AND',
                'uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();

        $Supplier = new Supplier(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);
        $Inventori = new Inventori(self::$pdo);

        foreach($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['penerima'] = $Supplier->get_detail($value['penerima']);
            $data['response_data'][$key]['diajukan'] = $Pegawai->get_detail($value['diajukan'])['response_data'][0];
            $data['response_data'][$key]['disetujui'] = (isset($value['disetujui'])) ? $Pegawai->get_detail($value['disetujui'])['response_data'][0] : '-';
            $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
            $Detail = self::$query->select('inventori_lend_detail', array(
                'item', 'batch', 'qty'
            ))
                ->where(array(
                    'lend' => '= ?',
                    'AND',
                    'deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach($Detail['response_data'] as $DKey => $DValue) {
                $Detail['response_data'][$DKey]['item'] = $Inventori->get_item_detail($DValue['item'])['response_data'][0];
                $Detail['response_data'][$DKey]['batch'] = $Inventori->get_batch_detail($DValue['batch'])['response_data'][0];
            }
            $data['response_data'][$key]['detail'] = $Detail['response_data'];
        }
        return $data;
    }

    private  function approve_pinjam_keluar($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $DetAo = self::lend_detail($parameter['uid'])['response_data'][0];

        $Inventori = new Inventori(self::$pdo);
        
        $process = self::$query->update('inventori_lend', array(
            'disetujui' => $UserData['data']->uid
        ))
            ->where(array(
                'inventori_lend.uid' => '= ?'
            ), array(
                $parameter['uid']
            ))
            ->execute();

        $updateProgress = array();

        if($process['response_result'] > 0) {
            $Detail = '';

            $Detail = self::$query->select('inventori_lend_detail', array(
                'item', 'batch', 'qty'
            ))
                ->where(array(
                    'lend' => '= ?',
                    'AND',
                    'deleted_at' => 'IS NULL'
                ), array(
                    $parameter['uid']
                ))
                ->execute();
            foreach($Detail['response_data'] as $DKey => $DValue) {
                $getStok = self::$query->select('inventori_stok', array(
                    'id',
                    'gudang',
                    'barang',
                    'stok_terkini'
                ))
                    ->where(array(
                        'inventori_stok.gudang' => '= ?',
                        'AND',
                        'inventori_stok.barang' => '= ?',
                        'AND',
                        'inventori_stok.batch' => '= ?'
                    ), array(
                        __GUDANG_UTAMA__,
                        $DValue['item'],
                        $DValue['batch']
                    ))
                    ->execute();


                //Potong Stok
                if(
                    floatval($DValue['qty']) > 0 &&
                    floatval($getStok['response_data'][0]['stok_terkini']) >= floatval($DValue['qty'])
                ) {
                    $CheckGudangStatus = $Inventori->get_gudang_detail(__GUDANG_UTAMA__)['response_data'][0];

                    if($CheckGudangStatus['status'] === 'A') {
                        $updateStok = self::$query->update('inventori_stok', array(
                            'stok_terkini' => (floatval($getStok['response_data'][0]['stok_terkini']) - floatval($DValue['qty']))
                        ))
                            ->where(array(
                                'inventori_stok.gudang' => '= ?',
                                'AND',
                                'inventori_stok.barang' => '= ?',
                                'AND',
                                'inventori_stok.batch' => '= ?'
                            ), array(
                                __GUDANG_UTAMA__,
                                $DValue['item'],
                                $DValue['batch']
                            ))
                            ->execute();
                        if($updateStok['response_result'] > 0)
                        {
                            //Log Stok
                            $stokLog = self::$query->insert('inventori_stok_log', array(
                                'barang' => $DValue['item'],
                                'batch'=> $DValue['batch'],
                                'gudang' => __GUDANG_UTAMA__,
                                'masuk' => 0,
                                'keluar' => floatval($DValue['qty']),
                                'saldo' => (floatval($getStok['response_data'][0]['stok_terkini']) - floatval($DValue['qty'])),
                                'type' => __STATUS_BARANG_KELUAR__,
                                'jenis_transaksi' => 'pinjam_keluar',
                                'uid_foreign' => $parameter['uid'],
                                'keterangan' => 'Peminjaman Obat ' . $DetAo['penerima']['nama']
                            ))
                                ->execute();
                        }
                        array_push($updateProgress, $updateStok);
                    }
                } else {
                    array_push($updateProgress, $getStok);
                }
            }
        }

        return $process;
    }

    private function hapus_pinjam_keluar($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $delete = self::$query->delete('inventori_lend')
            ->where(array(
                'uid' => '= ?'
            ), array(
                $parameter['uid']
            ))
            ->execute();
        return $delete;
    }

    private function lend_data($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            if(isset($parameter['hist'])) {
                $paramData = array(
                    'kode' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\'',
                    'AND',
                    'deleted_at' => 'IS NULL',
                    'AND',
                    'disetujui' => 'IS NOT NULL'
                );
                $paramValue = array();
            } else {
                $paramData = array(
                    'kode' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\'',
                    'AND',
                    'deleted_at' => 'IS NULL',
                    'AND',
                    'disetujui' => 'IS NULL'
                );
                $paramValue = array();
            }
        } else {
            if(isset($parameter['hist'])) {
                $paramData = array(
                    'deleted_at' => 'IS NULL',
                    'AND',
                    'disetujui' => 'IS NOT NULL'
                );
                $paramValue = array();
            } else {
                $paramData = array(
                    'deleted_at' => 'IS NULL',
                    'AND',
                    'disetujui' => 'IS NULL'
                );
                $paramValue = array();
            }
        }

        if (intval($parameter['length']) < 0) {
            $data = self::$query->select('inventori_lend', array(
                'uid', 'kode', 'diajukan', 'disetujui', 'penerima', 'keterangan', 'created_at', 'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('inventori_lend', array(
                'uid', 'kode', 'diajukan', 'disetujui', 'penerima', 'keterangan', 'created_at', 'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $dataResult = array();
        $data['response_draw'] = intval($parameter['draw']);
        $autonum = intval($parameter['start']) + 1;

        $Supplier = new Supplier(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);

        foreach($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['penerima'] = $Supplier->get_detail($value['penerima']);
            $data['response_data'][$key]['diajukan'] = $Pegawai->get_detail($value['diajukan'])['response_data'][0];
            $data['response_data'][$key]['disetujui'] = (isset($value['disetujui'])) ? $Pegawai->get_detail($value['disetujui'])['response_data'][0] : '-';
            $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
            $data['response_data'][$key]['autonum'] = $autonum;

            $autonum++;
        }

        $itemTotal = self::$query->select('inventori_lend', array(
            'uid', 'kode', 'diajukan', 'penerima', 'keterangan', 'created_at', 'updated_at'
        ))
            ->where($paramData, $paramValue)
            ->execute();


        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function pinjam_keluar_edit($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $process = self::$query->update('inventori_lend', array(
            'penerima' => $parameter['tujuan'],
            'keterangan' => $parameter['keterangan'],
            'updated_at' => parent::format_date()
        ))
            ->where(array(
                'inventori_lend.uid' => '= ?'
            ), array(
                $parameter['uid']
            ))
            ->execute();


        if($process['response_result'] > 0) {
            $hard = self::$query->hard_delete('inventori_lend_detail')
                ->where(array(
                    'lend' => '= ?'
                ), array(
                    $parameter['uid']
                ))
                ->execute();

            foreach($parameter['item_list'] as $key => $value) {
                $LendItem = self::$query->insert('inventori_lend_detail', array(
                    'lend' => $parameter['uid'],
                    'item' => $value['item'],
                    'batch' => $value['batch'],
                    'qty' => $value['qty'],
                    'qty_approve' => 0,
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
            }

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
                    $parameter['uid'],
                    $UserData['data']->uid,
                    'inventori_lend',
                    'I',
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class' => __CLASS__
            ));
        }

        return $process;

    }

    private function pinjam_keluar($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $uid = parent::gen_uuid();

        $lastNumber = self::$query->select('inventori_lend', array(
            'uid'
        ))
            ->where(array(
                'EXTRACT(month FROM created_at)' => '= ?'
            ), array(
                intval(date('m'))
            ))
            ->execute();

        $Kode = 'PJM-' . date('Y/m') . '-' . str_pad(strval(count($lastNumber['response_data']) + 1), 4, '0', STR_PAD_LEFT);

        $process = self::$query->insert('inventori_lend', array(
            'uid' => $uid,
            'kode' => $Kode,
            'diajukan' => $UserData['data']->uid,
            'penerima' => $parameter['tujuan'],
            'keterangan' => $parameter['keterangan'],
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()

        ))
            ->execute();
            
        if($process['response_result'] > 0) {

            foreach($parameter['item_list'] as $key => $value) {
                $LendItem = self::$query->insert('inventori_lend_detail', array(
                    'lend' => $uid,
                    'item' => $value['item'],
                    'batch' => $value['batch'],
                    'qty' => $value['qty'],
                    'qty_approve' => 0,
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
            }

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
                    'inventori_lend',
                    'I',
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class' => __CLASS__
            ));
        }

        return $process;
    }



    private function approve_permintaan_amprah($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $UpdateAmprahStatus = self::$query->update('inventori_amprah', array(
            'status' => 'B',
            'approved_by' => $UserData['data']->uid,
            'updated_at' => parent::format_date()
        ))
            ->where(array(
                'inventori_amprah.uid' => '= ?'
            ), array(
                $parameter['uid']
            ))
            ->execute();
        return $UpdateAmprahStatus;
    }




    private function retur_po($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $LastRetur = self::$query->select('inventori_return', array(
            'uid'
        ))
            ->where(array(
                'EXTRACT(year FROM inventori_return.created_at)' => '= ?'
            ), array(
                date('Y')
            ))
            ->execute();

        $ReturnUID = parent::gen_uuid();

        $MonStok = array();
        $MonUpStok = array();
        $MonStokLog = array();

        $InventoriReturn = self::$query->insert('inventori_return', array(
            'uid' => $ReturnUID,
            'kode' => str_pad(count($LastRetur['response_data']) + 1, 5, '0', STR_PAD_LEFT) . '/RET/' . date('m') . '/' . date('Y'),
            'pegawai' => $UserData['data']->uid,
            'dokumen' => $parameter['po'],
            'keterangan' => $parameter['keterangan'],
            'supplier' => $parameter['supplier'],
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->execute();

        if($InventoriReturn['response_result'] > 0) {
            foreach ($parameter['items'] as $key => $value) {
                //Kurangi  Stok
                //Get Current Stok
                $Old = self::$query->select('inventori_stok', array(
                    'stok_terkini'
                ))
                    ->where(array(
                        'inventori_stok.barang' => '= ?',
                        'AND',
                        'inventori_stok.gudang' => '= ?',
                        'AND',
                        'inventori_stok.batch' => '= ?'
                    ), array(
                        $value['item'], __GUDANG_UTAMA__, $value['batch']
                    ))
                    ->execute();
                array_push($MonStok, $Old);
                if(count($Old['response_data']) > 0) {
                    if(floatval($Old['response_data'][0]['stok_terkini']) > 0) {
                        $saldo = floatval($Old['response_data'][0]['stok_terkini']) - floatval($value['qty']);
                        $Stok = self::$query->update('inventori_stok', array(
                            'stok_terkini' => $saldo
                        ))
                            ->where(array(
                                'inventori_stok.barang' => '= ?',
                                'AND',
                                'inventori_stok.gudang' => '= ?',
                                'AND',
                                'inventori_stok.batch' => '= ?'
                            ), array(
                                $value['item'], __GUDANG_UTAMA__, $value['batch']
                            ))
                            ->execute();
                        array_push($MonUpStok, $Stok);
                        if($Stok['response_result'] > 0) {
                            $StokLog = self::$query->insert('inventori_stok_log', array(
                                'barang' => $value['item'],
                                'batch' => $value['batch'],
                                'gudang' => __GUDANG_UTAMA__,
                                'masuk' => 0,
                                'keluar' => floatval($value['qty']),
                                'saldo' => $saldo,
                                'type' => __STATUS_BARANG_RETUR__,
                                'logged_at' => parent::format_date(),
                                'jenis_transaksi' => 'inventori_return',
                                'uid_foreign' => $ReturnUID,
                                'keterangan' => 'Pengembalian Barang. ' . $parameter['keterangan']
                            ))
                                ->execute();

                            array_push($MonStokLog, $StokLog);

                            if($StokLog['response_result'] > 0) {
                                //Insert Into Inventori Return Detail
                                $InventoriReturnDetail = self::$query->insert('inventori_return_detail', array(
                                    'barang' => $value['item'],
                                    'batch' => $value['batch'],
                                    'qty' => floatval($value['qty']),
                                    'inventori_return' => $ReturnUID,
                                    'created_at' => parent::format_date(),
                                    'updated_at' => parent::format_date()
                                ))
                                    ->execute();
                            }
                        }
                    }
                }

            }
        }

        $InventoriReturn['mon_stok'] = $MonStok;
        $InventoriReturn['mon_stok_up'] = $MonUpStok;
        $InventoriReturn['mon_stok_log'] = $MonStokLog;
        
        return $InventoriReturn;
    }




















//===========================================================================================KATEGORI
    private function tambah_kategori_obat($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $check = self::duplicate_check(array(
            'table' => 'master_inv_obat_kategori',
            'check' => $parameter['nama']
        ));
        if (count($check['response_data']) > 0) {
            $check['response_message'] = 'Duplicate data detected';
            $check['response_result'] = 0;
            unset($check['response_data']);
            return $check;
        } else {
            $uid = parent::gen_uuid();
            $worker = self::$query
                ->insert('master_inv_obat_kategori', array(
                    'uid' => $uid,
                    'nama' => $parameter['nama'],
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
                        $uid,
                        $UserData['data']->uid,
                        'master_inv_obat_kategori',
                        'I',
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                ));
            }
            return $worker;
        }
    }

    private function edit_kategori_obat($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $old = self::get_kategori_obat_detail($parameter['uid']);

        $worker = self::$query
            ->update('master_inv_obat_kategori', array(
                'nama' => $parameter['nama'],
                'updated_at' => parent::format_date()
            ))
            ->where(array(
                'master_inv_obat_kategori.deleted_at' => 'IS NULL',
                'AND',
                'master_inv_obat_kategori.uid' => '= ?'
            ), array(
                $parameter['uid']
            ))
            ->execute();

        if ($worker['response_result'] > 0) {
            unset($parameter['access_token']);


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
        }

        return $worker;
    }

    private function tambah_kategori($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $check = self::duplicate_check(array(
            'table' => 'master_inv_kategori',
            'check' => $parameter['nama']
        ));
        if (count($check['response_data']) > 0) {
            $check['response_message'] = 'Duplicate data detected';
            $check['response_result'] = 0;
            unset($check['response_data']);
            return $check;
        } else {
            $uid = parent::gen_uuid();
            $worker = self::$query
                ->insert('master_inv_kategori', array(
                    'uid' => $uid,
                    'nama' => $parameter['nama'],
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
                        $uid,
                        $UserData['data']->uid,
                        'master_inv_kategori',
                        'I',
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                ));
            }
            return $worker;
        }
    }

    private function edit_kategori($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $old = self::get_kategori_detail($parameter['uid']);

        $worker = self::$query
            ->update('master_inv_kategori', array(
                'nama' => $parameter['nama'],
                'updated_at' => parent::format_date()
            ))
            ->where(array(
                'master_inv_kategori.deleted_at' => 'IS NULL',
                'AND',
                'master_inv_kategori.uid' => '= ?'
            ), array(
                $parameter['uid']
            ))
            ->execute();

        if ($worker['response_result'] > 0) {
            unset($parameter['access_token']);


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
                    'master_inv_kategori',
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

        return $worker;
    }

    private function get_kategori()
    {
        $data = self::$query
            ->select('master_inv_kategori', array(
                'uid',
                'nama',
                'created_at',
                'updated_at'
            ))
            ->where(array(
                'master_inv_kategori.deleted_at' => 'IS NULL'
            ))
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }
        return $data;
    }

    private function get_kategori_detail($parameter)
    {
        $data = self::$query
            ->select('master_inv_kategori', array(
                'uid',
                'nama',
                'created_at',
                'updated_at'
            ))
            ->where(array(
                'master_inv_kategori.deleted_at' => 'IS NULL',
                'AND',
                'master_inv_kategori.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }
        return $data;
    }

    private function get_kategori_obat()
    {
        $data = self::$query
            ->select('master_inv_obat_kategori', array(
                'uid',
                'nama',
                'created_at',
                'updated_at'
            ))
            ->where(array(
                'master_inv_obat_kategori.deleted_at' => 'IS NULL'
            ))
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }
        return $data;
    }

    private function get_kategori_obat_detail($parameter)
    {
        $data = self::$query
            ->select('master_inv_obat_kategori', array(
                'uid',
                'nama',
                'created_at',
                'updated_at'
            ))
            ->where(array(
                'master_inv_obat_kategori.deleted_at' => 'IS NULL',
                'AND',
                'master_inv_obat_kategori.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }
        return $data;
    }

//===========================================================================================SATUAN
    public function get_satuan() {
        $data = self::$query ->select('master_inv_satuan', array(
                'uid',
                'nama',
                'created_at',
                'updated_at'
            ))
            ->where(array(
                'master_inv_satuan.deleted_at' => 'IS NULL'
            ))
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }
        return $data;
    }

    public function get_satuan_detail($parameter)
    {
        $data = self::$query
            ->select('master_inv_satuan', array(
                'uid',
                'nama',
                'created_at',
                'updated_at'
            ))
            ->where(array(
                'master_inv_satuan.deleted_at' => 'IS NULL',
                'AND',
                'master_inv_satuan.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }
        return $data;
    }

    private function tambah_satuan($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $check = self::duplicate_check(array(
            'table' => 'master_inv_satuan',
            'check' => $parameter['nama']
        ));
        if (count($check['response_data']) > 0) {
            $check['response_message'] = 'Duplicate data detected';
            $check['response_result'] = 0;
            unset($check['response_data']);
            return $check;
        } else {
            $uid = parent::gen_uuid();
            $worker = self::$query
                ->insert('master_inv_satuan', array(
                    'uid' => $uid,
                    'nama' => $parameter['nama'],
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
                        $uid,
                        $UserData['data']->uid,
                        'master_inv_satuan',
                        'I',
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                ));
            }
            return $worker;
        }
    }

    private function edit_satuan($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $old = self::get_satuan_detail($parameter['uid']);

        $worker = self::$query
            ->update('master_inv_satuan', array(
                'nama' => $parameter['nama'],
                'updated_at' => parent::format_date()
            ))
            ->where(array(
                'master_inv_satuan.deleted_at' => 'IS NULL',
                'AND',
                'master_inv_satuan.uid' => '= ?'
            ), array(
                $parameter['uid']
            ))
            ->execute();

        if ($worker['response_result'] > 0) {
            unset($parameter['access_token']);


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
                    'master_inv_satuan',
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

        return $worker;
    }

//===========================================================================================PENJAMIN
    private function get_penjamin($parameter)
    {
        $data = self::$query->select('master_inv_harga', array(
            'id',
            'barang',
            'penjamin',
            'profit',
            'profit_type'
        ))
            ->where(array(
                'master_inv_harga.deleted_at' => 'IS NULL',
                'AND',
                'master_inv_harga.barang' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        return $data['response_data'];
    }

    private function get_rak($parameter)
    {
        $data = self::$query->select('master_inv_gudang_rak', array(
            'id',
            'barang',
            'gudang',
            'rak'
        ))
            ->where(array(
                'master_inv_gudang_rak.deleted_at' => 'IS NULL',
                'AND',
                'master_inv_gudang_rak.barang' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        return $data['response_data'];
    }

    private function get_monitoring($parameter)
    {
        $data = self::$query->select('master_inv_monitoring', array(
            'id',
            'barang',
            'gudang',
            'min',
            'max'
        ))
            ->where(array(
                'master_inv_monitoring.deleted_at' => 'IS NULL',
                'AND',
                'master_inv_monitoring.barang' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        return $data['response_data'];
    }

    private function get_kategori_obat_item($parameter)
    {
        $data = self::$query->select('master_inv_obat_kategori_item', array(
            'id',
            'obat',
            'kategori'
        ))
            ->where(array(
                'master_inv_obat_kategori_item.deleted_at' => 'IS NULL',
                'AND',
                'master_inv_obat_kategori_item.obat' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        return $data['response_data'];
    }

    private function get_kategori_obat_item_parsed($parameter)
    {
        $data = self::$query->select('master_inv_obat_kategori_item', array(
            'id',
            'obat',
            'kategori'
        ))
            ->where(array(
                'master_inv_obat_kategori_item.deleted_at' => 'IS NULL',
                'AND',
                'master_inv_obat_kategori_item.obat' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['kategori'] = self::get_kategori_obat_detail($value['kategori'])['response_data'][0];
        }
        return $data['response_data'];
    }

//===========================================================================================GUDANG
    private function get_gudang()
    {
        $data = self::$query
            ->select('master_inv_gudang', array(
                'uid',
                'nama',
                'status',
                'opname_rule',
                'created_at',
                'updated_at'
            ))
            ->where(array(
                'master_inv_gudang.deleted_at' => 'IS NULL'
            ))
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }
        return $data;
    }

    public function get_gudang_detail($parameter)
    {
        $data = self::$query
            ->select('master_inv_gudang', array(
                'uid',
                'nama',
                'status',
                'created_at',
                'updated_at'
            ))
            ->where(array(
                'master_inv_gudang.deleted_at' => 'IS NULL',
                'AND',
                'master_inv_gudang.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }
        return $data;
    }

    public function tambah_gudang($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $check = self::duplicate_check(array(
            'table' => 'master_inv_gudang',
            'check' => $parameter['nama']
        ));
        if (count($check['response_data']) > 0) {
            $check['response_message'] = 'Duplicate data detected';
            $check['response_result'] = 0;
            unset($check['response_data']);
            return $check;
        } else {
            $uid = parent::gen_uuid();
            $worker = self::$query
                ->insert('master_inv_gudang', array(
                    'uid' => $uid,
                    'nama' => $parameter['nama'],
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
                        $uid,
                        $UserData['data']->uid,
                        'master_inv_gudang',
                        'I',
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                ));
            }
            $worker['response_unique'] = $uid;
            return $worker;
        }
    }

    private function edit_gudang($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $old = self::get_gudang_detail($parameter['uid']);

        $worker = self::$query
            ->update('master_inv_gudang', array(
                'nama' => $parameter['nama'],
                'updated_at' => parent::format_date()
            ))
            ->where(array(
                'master_inv_gudang.deleted_at' => 'IS NULL',
                'AND',
                'master_inv_gudang.uid' => '= ?'
            ), array(
                $parameter['uid']
            ))
            ->execute();

        if ($worker['response_result'] > 0) {
            unset($parameter['access_token']);


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
                    'master_inv_gudang',
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

        return $worker;
    }

//===========================================================================================Manufacture
    private function get_manufacture()
    {
        $data = self::$query
            ->select('master_manufacture', array(
                'uid',
                'nama',
                'created_at',
                'updated_at'
            ))
            ->where(array(
                'master_manufacture.deleted_at' => 'IS NULL'
            ))
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }
        return $data;
    }

    private function get_manufacture_detail($parameter)
    {
        $data = self::$query
            ->select('master_manufacture', array(
                'uid',
                'nama',
                'created_at',
                'updated_at'
            ))
            ->where(array(
                'master_manufacture.deleted_at' => 'IS NULL',
                'AND',
                'master_manufacture.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }
        return $data;
    }

    private function tambah_manufacture($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $check = self::duplicate_check(array(
            'table' => 'master_manufacture',
            'check' => $parameter['nama']
        ));
        if (count($check['response_data']) > 0) {
            $check['response_message'] = 'Duplicate data detected';
            $check['response_result'] = 0;
            unset($check['response_data']);
            return $check;
        } else {
            $uid = parent::gen_uuid();
            $worker = self::$query
                ->insert('master_manufacture', array(
                    'uid' => $uid,
                    'nama' => $parameter['nama'],
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
                        $uid,
                        $UserData['data']->uid,
                        'master_manufacture',
                        'I',
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                ));
            }
            return $worker;
        }
    }

    private function edit_manufacture($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $old = self::get_manufacture_detail($parameter['uid']);

        $worker = self::$query
            ->update('master_manufacture', array(
                'nama' => $parameter['nama'],
                'updated_at' => parent::format_date()
            ))
            ->where(array(
                'master_manufacture.deleted_at' => 'IS NULL',
                'AND',
                'master_manufacture.uid' => '= ?'
            ), array(
                $parameter['uid']
            ))
            ->execute();

        if ($worker['response_result'] > 0) {
            unset($parameter['access_token']);


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
                    'master_manufacture',
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

        return $worker;
    }

//===========================================================================================ITEM DETAIL
    private function get_konversi($parameter)
    {
        $dataKonversi = self::$query
            ->select('master_inv_satuan_konversi', array(
                'dari_satuan',
                'ke_satuan',
                'rasio'
            ))
            ->where(array(
                'master_inv_satuan_konversi.barang' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        return $dataKonversi['response_data'];
    }

    private function get_varian($parameter)
    { //array('barang'=>'', 'satuan' => '')
        $dataVarian = self::$query
            ->select('master_inv_satuan_varian', array(
                'satuan',
                'nama'
            ))
            ->where(array(
                'master_inv_satuan_varian.barang' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        return $dataVarian['response_data'];
    }

    private function get_kombinasi($parameter)
    { //array('barang'=>'', 'satuan' => '')
        $dataVarian = self::$query
            ->select('master_inv_kombinasi', array(
                'barang_kombinasi',
                'satuan',
                'varian',
                'qty'
            ))
            ->where(array(
                'master_inv_kombinasi.barang' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        return $dataVarian['response_data'];
    }

//===========================================================================================ITEM

    private function check_temp_transact($parameter) {
        $Data = self::$query->select('inventori_temp_stok', array(
            'id', 'transact_table', 'transact_iden', 'gudang_asal', 'gudang_tujuan', 'barang', 'batch', 'qty'
        ))
            ->where(array(
                'inventori_temp_stok.status' => '= ?'
            ), array(
                'P'
            ))
            ->execute();
        return $Data;
    }

    private function post_opname_strategy_load($parameter) {
        //Check Gudang Status
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $Amprah = array();
        $PotongLangsung = array();

        if($UserData['data']->gudang === __GUDANG_UTAMA__) {
            $Data = self::$query->select('inventori_temp_stok', array(
                'id', 'transact_table', 'transact_iden', 'gudang_asal', 'gudang_tujuan', 'barang', 'batch', 'qty'
            ))
                ->where(array(
                    'inventori_temp_stok.status' => '= ?'
                ), array(
                    'P'
                ))
                ->execute();
            foreach ($Data['response_data'] as $key => $value) {

                if($value['gudang_asal'] === __GUDANG_UTAMA__) {
                    if(!isset($Amprah[$value['barang']])) {
                        $Amprah[$value['barang']] = array(
                            'total' => 0,
                            'info' => self::get_item_detail($value['barang'])['response_data'][0],
                            'detail' => array()
                        );
                    }
                    array_push($Amprah[$value['barang']]['detail'], array(
                        'qty' => $value['qty'],
                        'transact_table' => $value['transact_table'],
                        'transact_iden' => $value['transact_iden'],
                        'batch' => self::get_batch_detail($value['batch'])['response_data'][0]
                    ));
                    $Amprah[$value['barang']]['total'] += $value['qty'];
                } else if(!isset($value['gudang_tujuan'])) {
                    if(!isset($PotongLangsung[$value['barang']])) {
                        $PotongLangsung[$value['barang']] = array(
                            'total' => 0,
                            'info' => self::get_item_detail($value['barang'])['response_data'][0],
                            'detail' => array()
                        );
                    }
                    array_push($PotongLangsung[$value['barang']]['detail'], array(
                        'qty' => $value['qty'],
                        'transact_table' => $value['transact_table'],
                        'transact_iden' => $value['transact_iden'],
                        'batch' => self::get_batch_detail($value['batch'])['response_data'][0]
                    ));
                    $PotongLangsung[$value['barang']]['total'] += $value['qty'];
                } else {
                    //Todo : Selain amprah dan potong langsung
                }
            }
        } else {
            $Data = self::$query->select('inventori_temp_stok', array(
                'id', 'transact_table', 'transact_iden', 'gudang_asal', 'gudang_tujuan', 'barang', 'batch', 'qty'
            ))
                ->where(array(
                    'inventori_temp_stok.status' => '= ?',
                    'AND',
                    '(inventori_temp_stok.gudang_asal' => '= ?',
                    'OR',
                    'inventori_temp_stok.gudang_tujuan' => '= ?)'
                ), array(
                    'P', $UserData['data']->gudang, $UserData['data']->gudang
                ))
                ->execute();
            foreach ($Data['response_data'] as $key => $value) {
                //Todo: Recalculate Strategy

                if($value['gudang_asal'] === __GUDANG_UTAMA__ && $value['gudang_tujuan'] === $UserData['data']->gudang) {
                    if(!isset($Amprah[$value['barang']])) {
                        $Amprah[$value['barang']] = array(
                            'total' => 0,
                            'info' => self::get_item_detail($value['barang'])['response_data'][0],
                            'detail' => array()
                        );
                    }
                    array_push($Amprah[$value['barang']]['detail'], array(
                        'qty' => $value['qty'],
                        'transact_table' => $value['transact_table'],
                        'transact_iden' => $value['transact_iden'],
                        'batch' => self::get_batch_detail($value['batch'])['response_data'][0]
                    ));
                    $Amprah[$value['barang']]['total'] += $value['qty'];
                } else if(!isset($value['gudang_tujuan']) && $value['gudang_asal'] === $UserData['data']->gudang) {
                    if(!isset($Amprah[$value['barang']])) {
                        $PotongLangsung[$value['barang']] = array(
                            'total' => 0,
                            'info' => self::get_item_detail($value['barang'])['response_data'][0],
                            'detail' => array()
                        );
                    }
                    array_push($PotongLangsung[$value['barang']]['detail'], array(
                        'qty' => $value['qty'],
                        'transact_table' => $value['transact_table'],
                        'transact_iden' => $value['transact_iden'],
                        'batch' => self::get_batch_detail($value['batch'])['response_data'][0]
                    ));
                    $PotongLangsung[$value['barang']]['total'] += $value['qty'];
                } else {
                    //Todo : Selain amprah dan potong langsung
                }
            }
        }

        return array(
            'ungroup' => $Data['response_data'],
            'amprah' => $Amprah,
            'potong' => $PotongLangsung
        );
    }

    private function get_item_select2($parameter) {
        $dupCheck = array();
        $dataResult = array();
        $data = self::$query
            ->select('master_inv', array(
                'uid',
                'kode_barang',
                'nama',
                'kategori',
                'satuan_terkecil',
                'manufacture',
                'created_at',
                'updated_at'
            ))
            ->join('master_inv_obat_kandungan', array(
                'id', 'kandungan'
            ))
            ->on(array(
                array('master_inv.uid', '=', 'master_inv_obat_kandungan.uid_obat')
            ))
            ->where(array(
                'master_inv.deleted_at' => 'IS NULL',
                'AND',
                '(master_inv.kode_barang' => 'ILIKE ' . '\'%' . (trim(strtoupper($_GET['search']))) . '%\'',
                'OR',
                '(master_inv_obat_kandungan.kandungan' => 'ILIKE ' . '\'%' . (trim(strtoupper($_GET['search']))) . '%\')',
                'OR',
                'master_inv.nama' => 'ILIKE ' . '\'%' . (trim(strtoupper($_GET['search']))) . '%\')'
            ))
            ->limit(10)
            ->execute();

        $autonum = 1;
        $PenjaminObat = new Penjamin(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $kandunganList = array();
            $Kandungan = self::$query->select('master_inv_obat_kandungan', array(
                'kandungan'
            ))
                ->where(array(
                    'master_inv_obat_kandungan.uid_obat' => '= ?',
                    'AND',
                    'master_inv_obat_kandungan.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach($Kandungan['response_data'] as $kKK => $kKV) {
                array_push($kandunganList, $kKV['kandungan']);
            }
            $data['response_data'][$key]['nama'] = $value['nama'] . ' [' . implode(', ', $kandunganList) . ']';
            
            $kategori_obat = self::get_kategori_obat_item($value['uid']);
            foreach ($kategori_obat as $KOKey => $KOValue) {
                $KategoriCaption = self::get_kategori_obat_detail($KOValue['kategori'])['response_data'][0]['nama'];
                if(!empty($KategoriCaption)) {
                    $kategori_obat[$KOKey]['kategori'] = $KategoriCaption;
                }
            }

            $data['response_data'][$key]['kategori_obat'] = $kategori_obat;
            $data['response_data'][$key]['satuan_terkecil'] = self::get_satuan_detail($value['satuan_terkecil'])['response_data'][0];
            $data['response_data'][$key]['kategori'] = self::get_kategori_detail($value['kategori'])['response_data'][0];
            $data['response_data'][$key]['manufacture'] = self::get_manufacture_detail($value['manufacture'])['response_data'][0];

            //Data Penjamin
            $ListPenjaminObat = $PenjaminObat->get_penjamin_obat($value['uid'], $_GET['penjamin'])['response_data'];
            foreach ($ListPenjaminObat as $PenjaminKey => $PenjaminValue) {
                $ListPenjaminObat[$PenjaminKey]['profit'] = floatval($PenjaminValue['profit']);
            }
            $data['response_data'][$key]['penjamin'] = $ListPenjaminObat;

            //Cek Ketersediaan Stok
            $TotalStock = 0;
            
            $InventoriStockPopulator = self::get_item_batch($value['uid']);
            if (count($InventoriStockPopulator['response_data']) > 0) {
                foreach ($InventoriStockPopulator['response_data'] as $TotalKey => $TotalValue) {
                    $TotalStock += floatval($TotalValue['stok_terkini']);
                }
                $data['response_data'][$key]['stok'] = $TotalStock;
                $data['response_data'][$key]['batch'] = $InventoriStockPopulator['response_data'];
                $data['response_data'][$key]['harga'] = 0;
            } else {
                $data['response_data'][$key]['stok'] = 0;
            }

            if(!isset($dupCheck[$value['uid']])) {
                $dupCheck[$value['uid']] = 1;
                array_push($dataResult, $data['response_data'][$key]);
                $autonum++;
            }


        }
        $data['response_data'] = $dataResult;
        return $data;
    }


    private function get_item()
    {
        $data = self::$query
            ->select('master_inv', array(
                'uid',
                'kode_barang',
                'nama',
                'kategori',
                'satuan_terkecil',
                'manufacture',
                'created_at',
                'updated_at'
            ))
            ->where(array(
                'master_inv.deleted_at' => 'IS NULL'
            ))
            ->execute();

        $autonum = 1;
        $PenjaminObat = new Penjamin(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $kategori_obat = self::get_kategori_obat_item($value['uid']);
            foreach ($kategori_obat as $KOKey => $KOValue) {
                $KategoriCaption = self::get_kategori_obat_detail($KOValue['kategori'])['response_data'][0]['nama'];
                if(!empty($KategoriCaption)) {
                    $kategori_obat[$KOKey]['kategori'] = $KategoriCaption;
                }
            }

            $data['response_data'][$key]['kategori_obat'] = $kategori_obat;
            $data['response_data'][$key]['satuan_terkecil'] = self::get_satuan_detail($value['satuan_terkecil'])['response_data'][0];
            $data['response_data'][$key]['kategori'] = self::get_kategori_detail($value['kategori'])['response_data'][0];
            $data['response_data'][$key]['manufacture'] = self::get_manufacture_detail($value['manufacture'])['response_data'][0];

            //Data Penjamin
            $ListPenjaminObat = $PenjaminObat::get_penjamin_obat($value['uid'])['response_data'];
            foreach ($ListPenjaminObat as $PenjaminKey => $PenjaminValue) {
                $ListPenjaminObat[$PenjaminKey]['profit'] = floatval($PenjaminValue['profit']);
            }
            $data['response_data'][$key]['penjamin'] = $ListPenjaminObat;

            //Cek Ketersediaan Stok
            $TotalStock = 0;
            $InventoriStockPopulator = self::get_item_batch($value['uid']);
            if (count($InventoriStockPopulator['response_data']) > 0) {
                foreach ($InventoriStockPopulator['response_data'] as $TotalKey => $TotalValue) {
                    $TotalStock += floatval($TotalValue['stok_terkini']);
                }
                $data['response_data'][$key]['stok'] = $TotalStock;
                $data['response_data'][$key]['batch'] = $InventoriStockPopulator['response_data'];
            } else {
                $data['response_data'][$key]['stok'] = 0;
            }

            $autonum++;
        }
        return $data;
    }

    public function get_item_batch($parameter, $target = '') {
        $filteredData = array();
        if(!empty($target)) {
            $data = self::$query->select('inventori_stok', array(
                'batch',
                'barang',
                'gudang',
                'stok_terkini'
            ))
                ->where(array(
                    'inventori_stok.barang' => '= ?',
                    'AND',
                    'inventori_stok.gudang' => '= ?'
                ), array(
                    $parameter, $target
                ))
                ->order(array(
                    'gudang' => 'DESC'
                ))
                ->execute();
        } else {
            $data = self::$query->select('inventori_stok', array(
                'batch',
                'barang',
                'gudang',
                'stok_terkini'
            ))
                ->where(array(
                    'inventori_stok.barang' => '= ?'
                ), array(
                    $parameter
                ))
                ->order(array(
                    'gudang' => 'DESC'
                ))
                ->execute();
        }
        
        foreach ($data['response_data'] as $key => $value) {
            $batch_info = self::get_batch_detail($value['batch'])['response_data'][0];

            if (
                strtotime($batch_info['expired_date']) < strtotime(date('Y-m-d')) &&
                floatval($value['stok_terkini']) > 0
            ) { //Expired jangan dijual
                unset($data['response_data'][$key]);
            } else {
                //$data['response_data'][$key]['item_detail'] = self::get_item_detail($value['barang'])['response_data'][0];
                $data['response_data'][$key]['gudang'] = self::get_gudang_detail($value['gudang'])['response_data'][0];
                //$data['response_data'][$key]['kode'] = self::get_batch_detail($value['batch'])['response_data'][0]['batch'];
                $data['response_data'][$key]['kode'] = $batch_info['batch'];
                $data['response_data'][$key]['expired'] = date('d F Y', strtotime($batch_info['expired_date']));
                $data['response_data'][$key]['stok_terkini'] = floatval($value['stok_terkini']);
                $data['response_data'][$key]['expired_sort'] = $batch_info['expired_date'];
                $data['response_data'][$key]['harga'] = $batch_info['harga'];
                $data['response_data'][$key]['profit'] = $batch_info['profit'];

                array_push($filteredData, $data['response_data'][$key]);
            }
        }

        //Sort Batch before return
        $original = $filteredData;
        /*$sort = array();
        foreach ($original as $key => $part) {
            $sort[$key] = strtotime($part['expired_sort']);
        }
        array_multisort($sort, SORT_ASC, $original);
        $data['response_data'] = $original;*/
        usort($original, function($a, $b){
            $t1 = strtotime($a['expired_sort']);
            $t2 = strtotime($b['expired_sort']);
            return $t1 - $t2;
        });

        $data['response_data'] = $original;
        return $data;
    }

    public function get_batch_info($parameter) {
        $data = self::$query->select('inventori_batch', array(
            'uid',
            'batch',
            'barang',
            'expired_date',
            'po',
            'do_master'
        ))
            ->where(array(
                'inventori_batch.uid' => ' = ?'
            ), array(
                $parameter
            ))
            ->execute();
        return $data;
    }

    public function get_batch_detail($parameter)
    {
        $data = self::$query->select('inventori_batch', array(
            'uid',
            'batch',
            'barang',
            'expired_date',
            'po',
            'do_master'
        ))
            ->where(array(
                'inventori_batch.uid' => ' = ?'
            ), array(
                $parameter
            ))
            ->execute();
        $PO = new PO(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            //Get Harga dari PO
            if (isset($value['po'])) {
                $Price = $PO->get_po_item_price(array(
                    'po' => $value['po'],
                    'barang' => $value['barang']
                ));

                $data['response_data'][$key]['harga'] = floatval($Price['response_data'][0]['harga']);

                $data['response_data'][$key]['expired_date_parsed'] = date('d F Y', strtotime($value['expired_date']));

                //Tambahkan Keuntungan yang diinginkan dari master inventori
                $Profit = self::get_penjamin($value['barang']);
                $data['response_data'][$key]['profit'] = $Profit;
            } else {
                $data['response_data'][$key]['harga'] = 0;
            }
        }

        return $data;
    }


    /**
     * @param uid $parameter Barang
     * @param uid $gudang Gudang
     * @return array Data is response_data
     */
    public function  get_item_stok_log($parameter, $gudang, $dari = "", $sampai = "") {
        if($dari !== "" && $sampai !== "") {
            $data = self::$query->select('inventori_stok_log', array(
                'id',
                'masuk',
                'keluar',
                'saldo',
                'batch',
                'type',
                'logged_at',
                'jenis_transaksi',
                'uid_foreign',
                'keterangan'
            ))
                ->where(array(
                    'inventori_stok_log.gudang' => '= ?',
                    'AND',
                    'inventori_stok_log.barang' => '= ?',
                    'AND',
                    'inventori_stok_log.logged_at' => 'BETWEEN ? AND ?'
                ), array(
                    $gudang,
                    $parameter,
                    date('Y-m-d', strtotime($dari . '-1 days')),
                    date('Y-m-d', strtotime($sampai . '+1 days'))
                ))
                ->order(array(
                    'logged_at' => 'ASC'
                ))
                ->execute();
        } else {
            $data = self::$query->select('inventori_stok_log', array(
                'id',
                'masuk',
                'keluar',
                'saldo',
                'batch',
                'type',
                'logged_at',
                'jenis_transaksi',
                'uid_foreign',
                'keterangan'
            ))
                ->where(array(
                    'inventori_stok_log.gudang' => '= ?',
                    'AND',
                    'inventori_stok_log.barang' => '= ?'
                ), array(
                    $gudang,
                    $parameter
                ))
                ->order(array(
                    'logged_at' => 'ASC'
                ))
                ->execute();
        }

        $DO = new DeliveryOrder(self::$pdo);
        $Pasien = new Pasien(self::$pdo);

        foreach ($data['response_data'] as $key => $value) {
            //Terminologi Item
            $Termi = self::$query->select('terminologi_item', array(
                'id',
                'nama'
            ))
                ->where(array(
                    'terminologi_item.id' => '= ?',
                    'AND',
                    'terminologi_item.deleted_at' => 'IS NULL'
                ), array(
                    $value['type']
                ))
                ->execute();
            $data['response_data'][$key]['type'] = $Termi['response_data'][0];

            $data['response_data'][$key]['batch'] = self::get_batch_detail($value['batch'])['response_data'][0];

            $data['response_data'][$key]['logged_at'] = date('d M Y', strtotime($value['logged_at'])) . '&nbsp;&nbsp;<strong class="text-info number_style">' . date('H:i', strtotime($value['logged_at']) . '</strong>');

            if($value['uid_foreign'] !== null && $value['uid_foreign'] !== '')
            {
                //Get Foreign dokumen
                if($value['jenis_transaksi'] === 'resep') {
                    //get resep
                    $Resep = self::$query->select('resep', array(
                        'pasien'
                    ))
                        ->where(array(
                            'resep.deleted_at' => 'IS NULL',
                            'AND',
                            'resep.uid' => '= ?'
                        ), array(
                            $value['uid_foreign']
                        ))
                        ->execute();
                    //get pasien

                    $PasienInfo = $Pasien->get_pasien_detail('pasien', $Resep['response_data'][0]['pasien']);

                    $data['response_data'][$key]['dokumen'] = 'Resep Asesmen ' . $PasienInfo['response_data'][0]['nama'];
                } elseif ($value['jenis_transaksi'] === 'inventori_do') {
                    $DODetail = $DO->get_do_info($value['uid_foreign'])['response_data'][0];
                    $data['response_data'][$key]['dokumen'] = $DODetail['no_do'];
                } elseif ($value['jenis_transaksi'] === 'inventori_mutasi') {
                    $Mutasi = self::get_mutasi_detail($value['uid_foreign'])['response_data'][0];
                    $data['response_data'][$key]['dokumen'] = $Mutasi['kode'];
                } elseif ($value['jenis_transaksi'] === 'inventori_amprah_proses') {
                    $Amprah = self::get_amprah_proses_detail_uid($value['uid_foreign'])['response_data'][0];
                    $data['response_data'][$key]['dokumen'] = $Amprah['kode'];
                } else {
                    $data['response_data'][$key]['dokumen'] = '-';
                }
            } else {
                $data['response_data'][$key]['dokumen'] = '-';
            }
        }

        return $data;
    }

    public function get_item_info($parameter) {
        $data = self::$query
            ->select('master_inv', array(
                'uid',
                'nama',
                'kode_barang',
                'keterangan',
                'kategori',
                'manufacture',
                'satuan_terkecil',
                'created_at',
                'updated_at'
            ))
            ->where(array(
                'master_inv.deleted_at' => 'IS NULL',
                'AND',
                'master_inv.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        foreach ($data['response_data'] as $key => $value) {
            //Text
            //$data['response_data'][$key]['id'] = $value['uid'];
            //$data['response_data'][$key]['text'] = $value['nama'];

            //Kategori Obat
            //$data['response_data'][$key]['kategori_obat'] = self::get_kategori_obat_item($value['uid']);

            //Prepare Image File
            //$data['response_data'][$key]['image'] = file_exists('../images/produk/' . $value['uid'] . '.png');

            //Konversi
            //$data['response_data'][$key]['konversi'] = self::get_konversi($value['uid']);

            //Penjamin
            //$data['response_data'][$key]['penjamin'] = self::get_penjamin($value['uid']);

            //Lokasi
            //$data['response_data'][$key]['lokasi'] = self::get_rak($value['uid']);

            //Monitoring
            //$data['response_data'][$key]['monitoring'] = self::get_monitoring($value['uid']);

            //Satuan Terkecil
            $data['response_data'][$key]['satuan_terkecil_info'] = self::get_satuan_detail($value['satuan_terkecil'])['response_data'][0];

            //Kandungan
            //$data['response_data'][$key]['kandungan'] = self::get_kandungan($value['uid'])['response_data'];
        }
        return $data;
    }

    /**
     * @param $parameter Require item uid
     * @return array
     */
    public function get_item_detail($parameter)
    {
        $data = self::$query
            ->select('master_inv', array(
                'uid',
                'nama',
                'kode_barang',
                'keterangan',
                'kategori',
                'manufacture',
                'satuan_terkecil',
                'created_at',
                'updated_at'
            ))
            ->where(array(
                'master_inv.deleted_at' => 'IS NULL',
                'AND',
                'master_inv.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;

            //Text
            $data['response_data'][$key]['id'] = $value['uid'];
            $data['response_data'][$key]['text'] = $value['nama'];

            //Kategori Obat
            $data['response_data'][$key]['kategori_obat'] = self::get_kategori_obat_item($value['uid']);

            //Prepare Image File
            $data['response_data'][$key]['image'] = file_exists('../images/produk/' . $value['uid'] . '.png');

            //Konversi
            $data['response_data'][$key]['konversi'] = self::get_konversi($value['uid']);

            //Penjamin
            $data['response_data'][$key]['penjamin'] = self::get_penjamin($value['uid']);

            //Lokasi
            $data['response_data'][$key]['lokasi'] = self::get_rak($value['uid']);

            //Monitoring
            $data['response_data'][$key]['monitoring'] = self::get_monitoring($value['uid']);

            //Satuan Terkecil
            $data['response_data'][$key]['satuan_terkecil_info'] = self::get_satuan_detail($value['satuan_terkecil'])['response_data'][0];

            //Kandungan
            $data['response_data'][$key]['kandungan'] = self::get_kandungan($value['uid'])['response_data'];
        }
        return $data;
    }

    public function get_kandungan($parameter) {
        $data = self::$query->select('master_inv_obat_kandungan', array(
            'id', 'kandungan', 'keterangan'
        ))
            ->where(array(
                'master_inv_obat_kandungan.uid_obat' => '= ?',
                'AND',
                'master_inv_obat_kandungan.deleted_at' => 'IS NULL'
            ), array(
                $parameter
            ))
            ->execute();

        return $data;
    }

    private function tambah_item($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $error_count = 0;

        //Parent Segment
        $check = self::duplicate_check(array(
            'table' => 'master_inv',
            'check' => $parameter['nama']
        ));
        if (count($check['response_data']) > 0) {
            $check['response_message'] = 'Duplicate data detected';
            $check['response_result'] = 0;
            unset($check['response_data']);
            return $check;
        } else {
            $uid = parent::gen_uuid();
            $worker = self::$query->insert('master_inv', array(
                'uid' => $uid,
                'kode_barang' => $parameter['kode'],
                'nama' => $parameter['nama'],
                'kategori' => $parameter['kategori'],
                'manufacture' => $parameter['manufacture'],
                'satuan_terkecil' => $parameter['satuan_terkecil'],
                'keterangan' => $parameter['keterangan'],
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
                        'new_value',
                        'logged_at',
                        'status',
                        'login_id'
                    ),
                    'value' => array(
                        $parameter['uid'],
                        $UserData['data']->uid,
                        'master_inv',
                        'I',
                        json_encode($parameter),
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                ));

                //Image Upload
                $data = $parameter['image'];
                list($type, $data) = explode(';', $data);
                list(, $data) = explode(',', $data);
                $data = base64_decode($data);
                if (!file_exists('../images/produk')) {
                    mkdir('../images/produk');
                }
                file_put_contents('../images/produk/' . $uid . '.png', $data);


                //Kategori Obat
                $oldKategoriObat = self::$query->select('master_inv_obat_kategori_item', array(
                    'id',
                    'kategori'
                ))
                    ->where(array(
                        'master_inv_obat_kategori_item.obat' => '= ?'
                    ), array(
                        $uid
                    ))
                    ->execute();

                //Delete unused kategori
                foreach ($oldKategoriObat['response_data'] as $key => $value) {
                    if (!in_array($value['kategori'], $parameter['listKategoriObat'])) {
                        $deleteKategoriObat = self::$query->update('master_inv_obat_kategori_item', array(
                            'deleted_at' => parent::format_date()
                        ))
                            ->where(array(
                                'master_inv_obat_kategori_item.id' => '= ?'
                            ), array(
                                $value['id']
                            ))
                            ->execute();
                        if ($deleteKategoriObat['response_result'] > 0) {
                            //
                        } else {
                            $error_count++;
                        }
                    }
                }


                foreach ($parameter['listKategoriObat'] as $key => $value) {
                    //Check existing
                    $checkKategoriObat = self::$query->select('master_inv_obat_kategori_item', array(
                        'id'
                    ))
                        ->where(array(
                            'obat' => '= ?',
                            'AND',
                            'kategori' => '= ?'
                        ), array(
                            $uid,
                            $value
                        ))
                        ->execute();
                    if (count($checkKategoriObat['response_data']) > 0) {
                        $kategoriObat = self::$query->update('master_inv_obat_kategori_item', array(
                            'deleted_at' => NULL,
                            'updated_at' => parent::format_date()
                        ))
                            ->where(array(
                                'master_inv_obat_kategori_item.id' => '= ?'
                            ), array(
                                $checkKategoriObat['response_data'][0]['id']
                            ))
                            ->execute();
                        if ($kategoriObat['response_result'] > 0) {
                            $log = parent::log(array(
                                'type' => 'activity',
                                'column' => array(
                                    'unique_target',
                                    'user_uid',
                                    'table_name',
                                    'action',
                                    'new_value',
                                    'logged_at',
                                    'status',
                                    'login_id'
                                ),
                                'value' => array(
                                    $parameter['uid'],
                                    $UserData['data']->uid,
                                    'master_inv_obat_kategori_item',
                                    'U',
                                    'activated',
                                    parent::format_date(),
                                    'N',
                                    $UserData['data']->log_id
                                ),
                                'class' => __CLASS__
                            ));
                        } else {
                            $error_count++;
                        }
                    } else {
                        $kategoriObat = self::$query->insert('master_inv_obat_kategori_item', array(
                            'obat' => $uid,
                            'kategori' => $value,
                            'created_at' => parent::format_date(),
                            'updated_at' => parent::format_date()
                        ))
                            ->execute();
                        if ($kategoriObat['response_result'] > 0) {
                            $log = parent::log(array(
                                'type' => 'activity',
                                'column' => array(
                                    'unique_target',
                                    'user_uid',
                                    'table_name',
                                    'action',
                                    'new_value',
                                    'logged_at',
                                    'status',
                                    'login_id'
                                ),
                                'value' => array(
                                    $parameter['uid'],
                                    $UserData['data']->uid,
                                    'master_inv_obat_kategori_item',
                                    'I',
                                    json_encode($parameter['listKategoriObat']),
                                    parent::format_date(),
                                    'N',
                                    $UserData['data']->log_id
                                ),
                                'class' => __CLASS__
                            ));
                        } else {
                            $error_count++;
                        }
                    }
                }


                //Satuan Konversi
                foreach ($parameter['satuanKonversi'] as $key => $value) {
                    $newKonversi = self::$query->insert('master_inv_satuan_konversi', array(
                        'barang' => $uid,
                        'dari_satuan' => $value['dari'],
                        'rasio' => $value['rasio'],
                        'ke_satuan' => $value['ke'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                    if ($newKonversi['response_result'] > 0) {
                        $log = parent::log(array(
                            'type' => 'activity',
                            'column' => array(
                                'unique_target',
                                'user_uid',
                                'table_name',
                                'action',
                                'new_value',
                                'logged_at',
                                'status',
                                'login_id'
                            ),
                            'value' => array(
                                $parameter['uid'],
                                $UserData['data']->uid,
                                'master_inv_satuan_konversi',
                                'I',
                                json_encode($parameter['satuanKonversi']),
                                parent::format_date(),
                                'N',
                                $UserData['data']->log_id
                            ),
                            'class' => __CLASS__
                        ));
                    } else {
                        $error_count++;
                    }
                }

                //Penjamin
                foreach ($parameter['penjaminList'] as $key => $value) {
                    $newPenjamin = self::$query->insert('master_inv_harga', array(
                        'barang' => $uid,
                        'penjamin' => $value['penjamin'],
                        'profit' => $value['marginValue'],
                        'profit_type' => $value['marginType'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                    if (count($newPenjamin['response_result']) > 0) {
                        $log = parent::log(array(
                            'type' => 'activity',
                            'column' => array(
                                'unique_target',
                                'user_uid',
                                'table_name',
                                'action',
                                'new_value',
                                'logged_at',
                                'status',
                                'login_id'
                            ),
                            'value' => array(
                                $parameter['uid'],
                                $UserData['data']->uid,
                                'master_inv_harga',
                                'I',
                                json_encode($parameter['penjaminList']),
                                parent::format_date(),
                                'N',
                                $UserData['data']->log_id
                            ),
                            'class' => __CLASS__
                        ));
                    } else {
                        $error_count++;
                    }
                }

                //Gudang Rak
                foreach ($parameter['gudangMeta'] as $key => $value) {
                    $newGudang = self::$query->insert('master_inv_gudang_rak', array(
                        'barang' => $uid,
                        'gudang' => $value['gudang'],
                        'rak' => $value['lokasi'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                    if ($newGudang['response_result'] > 0) {
                        $log = parent::log(array(
                            'type' => 'activity',
                            'column' => array(
                                'unique_target',
                                'user_uid',
                                'table_name',
                                'action',
                                'new_value',
                                'logged_at',
                                'status',
                                'login_id'
                            ),
                            'value' => array(
                                $parameter['uid'],
                                $UserData['data']->uid,
                                'master_inv_gudang_rak',
                                'I',
                                json_encode($parameter['gudangMeta']),
                                parent::format_date(),
                                'N',
                                $UserData['data']->log_id
                            ),
                            'class' => __CLASS__
                        ));
                    } else {
                        $error_count++;
                    }
                }

                //Monitoring
                foreach ($parameter['monitoring'] as $key => $value) {
                    $newMonitoring = self::$query->insert('master_inv_monitoring', array(
                        'barang' => $uid,
                        'gudang' => $value['gudang'],
                        'min' => $value['min'],
                        'max' => $value['max'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                    if ($newMonitoring['response_result'] > 0) {
                        $log = parent::log(array(
                            'type' => 'activity',
                            'column' => array(
                                'unique_target',
                                'user_uid',
                                'table_name',
                                'action',
                                'new_value',
                                'logged_at',
                                'status',
                                'login_id'
                            ),
                            'value' => array(
                                $parameter['uid'],
                                $UserData['data']->uid,
                                'master_inv_monitoring',
                                'I',
                                json_encode($parameter['monitoring']),
                                parent::format_date(),
                                'N',
                                $UserData['data']->log_id
                            ),
                            'class' => __CLASS__
                        ));
                    } else {
                        $error_count++;
                    }
                }



                foreach ($parameter['kandungan'] as $KandKey => $KandValue) {
                    $kandungan_worker = self::$query->insert('master_inv_obat_kandungan', array(
                        'uid_obat' => $uid,
                        'kandungan' => $KandValue['kandungan'],
                        'keterangan' => $KandValue['keterangan'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                }

            } else {
                $error_count++;
            }
        }
        return $error_count;
    }


    private function edit_item($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $error_count = 0;
        $uid = $parameter['uid'];
        $old = self::get_item_detail($uid);
        $worker = self::$query->update('master_inv', array(
            'uid' => $uid,
            'kode_barang' => $parameter['kode'],
            'nama' => $parameter['nama'],
            'kategori' => $parameter['kategori'],
            'manufacture' => $parameter['manufacture'],
            'satuan_terkecil' => $parameter['satuan_terkecil'],
            'keterangan' => $parameter['keterangan'],
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->where(array(
                'master_inv.deleted_at' => 'IS NULL',
                'AND',
                'master_inv.uid' => '= ?'
            ), array(
                $uid
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
                    'old_value',
                    'new_value',
                    'logged_at',
                    'status',
                    'login_id'
                ),
                'value' => array(
                    $uid,
                    $UserData['data']->uid,
                    'master_inv',
                    'U',
                    json_encode($old),
                    json_encode($parameter),
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class' => __CLASS__
            ));

            //Image Upload
            $data = $parameter['image'];
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
            if (!file_exists('../images/produk')) {
                mkdir('../images/produk');
            }

            file_put_contents('../images/produk/' . $uid . '.png', $data);

            //Kategori Obat
            $oldKategoriObat = self::$query->select('master_inv_obat_kategori_item', array(
                'id',
                'kategori'
            ))
                ->where(array(
                    'master_inv_obat_kategori_item.obat' => '= ?'
                ), array(
                    $uid
                ))
                ->execute();

            //Delete unused kategori
            foreach ($oldKategoriObat['response_data'] as $key => $value) {
                if (!in_array($value['kategori'], $parameter['listKategoriObat'])) {
                    $deleteKategoriObat = self::$query->update('master_inv_obat_kategori_item', array(
                        'deleted_at' => parent::format_date()
                    ))
                        ->where(array(
                            'master_inv_obat_kategori_item.obat' => '= ?',
                            'AND',
                            'master_inv_obat_kategori_item.kategori' => '= ?'
                        ), array(
                            $uid,
                            $value['kategori']
                        ))
                        ->execute();

                    if ($deleteKategoriObat['response_result'] > 0) {
                        //
                    } else {
                        $error_count++;
                    }
                }
            }


            foreach ($parameter['listKategoriObat'] as $key => $value) {
                //Check existing
                $checkKategoriObat = self::$query->select('master_inv_obat_kategori_item', array(
                    'id'
                ))
                    ->where(array(
                        'obat' => '= ?',
                        'AND',
                        'kategori' => '= ?'
                    ), array(
                        $uid,
                        $value
                    ))
                    ->execute();
                if (count($checkKategoriObat['response_data']) > 0) {
                    $kategoriObat = self::$query->update('master_inv_obat_kategori_item', array(
                        'deleted_at' => NULL,
                        'updated_at' => parent::format_date()
                    ))
                        ->where(array(
                            'master_inv_obat_kategori_item.id' => '= ?'
                        ), array(
                            $checkKategoriObat['response_data'][0]['id']
                        ))
                        ->execute();
                    if ($kategoriObat['response_result'] > 0) {
                        $log = parent::log(array(
                            'type' => 'activity',
                            'column' => array(
                                'unique_target',
                                'user_uid',
                                'table_name',
                                'action',
                                'new_value',
                                'logged_at',
                                'status',
                                'login_id'
                            ),
                            'value' => array(
                                $parameter['uid'],
                                $UserData['data']->uid,
                                'master_inv_obat_kategori_item',
                                'U',
                                'activated',
                                parent::format_date(),
                                'N',
                                $UserData['data']->log_id
                            ),
                            'class' => __CLASS__
                        ));
                    } else {
                        $error_count++;
                    }
                } else {
                    $kategoriObat = self::$query->insert('master_inv_obat_kategori_item', array(
                        'obat' => $uid,
                        'kategori' => $value,
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                    if ($kategoriObat['response_result'] > 0) {
                        $log = parent::log(array(
                            'type' => 'activity',
                            'column' => array(
                                'unique_target',
                                'user_uid',
                                'table_name',
                                'action',
                                'new_value',
                                'logged_at',
                                'status',
                                'login_id'
                            ),
                            'value' => array(
                                $parameter['uid'],
                                $UserData['data']->uid,
                                'master_inv_obat_kategori_item',
                                'I',
                                json_encode($parameter['listKategoriObat']),
                                parent::format_date(),
                                'N',
                                $UserData['data']->log_id
                            ),
                            'class' => __CLASS__
                        ));
                    } else {
                        $error_count++;
                    }
                }
            }


            //Satuan Konversi
            $resetSatuan = self::$query->update('master_inv_satuan_konversi', array(
                'deleted_at' => parent::format_date()
            ))
                ->where(array(
                    'master_inv_satuan_konversi.barang' => '= ?'
                ), array(
                    $uid
                ))
                ->execute();
            $requestSatuanIDs = array();
            $oldSatuanMeta = array();
            $oldSatuanKonversi = self::$query->select('master_inv_satuan_konversi', array(
                'id',
                'barang',
                'dari_satuan',
                'ke_satuan'
            ))
                ->where(array(
                    'master_inv_satuan_konversi.barang' => '= ?'
                ), array(
                    $uid
                ))
                ->execute();

            foreach ($oldSatuanKonversi['response_data'] as $key => $value) {
                if (!in_array($value['id'], $requestSatuanIDs)) {
                    array_push($requestSatuanIDs, $value['id']);
                    array_push($oldSatuanMeta, $value);
                }
            }

            foreach ($parameter['satuanKonversi'] as $key => $value) {
                if (isset($requestSatuanIDs[$key])) {
                    $updateKonversi = self::$query->update('master_inv_satuan_konversi', array(
                        'barang' => $uid,
                        'dari_satuan' => $value['dari'],
                        'rasio' => $value['rasio'],
                        'ke_satuan' => $value['ke'],
                        'updated_at' => parent::format_date(),
                        'deleted_at' => NULL
                    ))
                        ->where(array(
                            'master_inv_satuan_konversi.id' => '= ?'
                        ), array(
                            $requestSatuanIDs[$key]
                        ))
                        ->execute();
                    if ($updateKonversi['response_result'] > 0) {
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
                                'master_inv_satuan_konversi',
                                'U',
                                json_encode($oldSatuanMeta[$key]),
                                json_encode($parameter['satuanKonversi']),
                                parent::format_date(),
                                'N',
                                $UserData['data']->log_id
                            ),
                            'class' => __CLASS__
                        ));
                    } else {
                        $error_count++;
                    }
                } else {
                    $newKonversi = self::$query->insert('master_inv_satuan_konversi', array(
                        'barang' => $uid,
                        'dari_satuan' => $value['dari'],
                        'rasio' => $value['rasio'],
                        'ke_satuan' => $value['ke'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                    if ($newKonversi['response_result'] > 0) {
                        $log = parent::log(array(
                            'type' => 'activity',
                            'column' => array(
                                'unique_target',
                                'user_uid',
                                'table_name',
                                'action',
                                'new_value',
                                'logged_at',
                                'status',
                                'login_id'
                            ),
                            'value' => array(
                                $parameter['uid'],
                                $UserData['data']->uid,
                                'master_inv_satuan_konversi',
                                'I',
                                json_encode($parameter['satuanKonversi']),
                                parent::format_date(),
                                'N',
                                $UserData['data']->log_id
                            ),
                            'class' => __CLASS__
                        ));
                    } else {
                        $error_count++;
                    }
                }
            }


            //Penjamin
            $resetPenjamin = self::$query->update('master_inv_harga', array(
                'deleted_at' => parent::format_date()
            ))
                ->where(array(
                    'master_inv_harga.barang' => '= ?'
                ), array(
                    $uid
                ))
                ->execute();

            $requestPenjaminIDs = array();
            $oldPenjaminMeta = array();
            $oldPenjaminKonversi = self::$query->select('master_inv_harga', array(
                'id',
                'barang',
                'penjamin'
            ))
                ->where(array(
                    'master_inv_harga.barang' => '= ?'
                ), array(
                    $uid
                ))
                ->execute();

            foreach ($oldPenjaminKonversi['response_data'] as $key => $value) {
                if (!in_array($value['id'], $requestPenjaminIDs)) {
                    array_push($requestPenjaminIDs, $value['id']);
                    array_push($oldPenjaminMeta, $value);
                }
            }

            foreach ($parameter['penjaminList'] as $key => $value) {
                if (isset($requestPenjaminIDs[$key])) {
                    $updatePenjamin = self::$query->update('master_inv_harga', array(
                        'penjamin' => $value['penjamin'],
                        'profit' => $value['marginValue'],
                        'profit_type' => $value['marginType'],
                        'updated_at' => parent::format_date(),
                        'deleted_at' => NULL
                    ))
                        ->where(array(
                            'master_inv_harga.id' => '= ?'
                        ), array(
                            $requestPenjaminIDs[$key]
                        ))
                        ->execute();
                    if ($updatePenjamin['response_result'] > 0) {
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
                                'master_inv_harga',
                                'U',
                                json_encode($oldPenjaminMeta[$key]),
                                json_encode($parameter['penjaminList']),
                                parent::format_date(),
                                'N',
                                $UserData['data']->log_id
                            ),
                            'class' => __CLASS__
                        ));
                    } else {
                        $error_count++;
                    }
                } else {
                    $newPenjamin = self::$query->insert('master_inv_harga', array(
                        'barang' => $uid,
                        'penjamin' => $value['penjamin'],
                        'profit' => $value['marginValue'],
                        'profit_type' => $value['marginType'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                    if ($newPenjamin['response_result'] > 0) {
                        $log = parent::log(array(
                            'type' => 'activity',
                            'column' => array(
                                'unique_target',
                                'user_uid',
                                'table_name',
                                'action',
                                'new_value',
                                'logged_at',
                                'status',
                                'login_id'
                            ),
                            'value' => array(
                                $parameter['uid'],
                                $UserData['data']->uid,
                                'master_inv_harga',
                                'I',
                                json_encode($parameter['penjaminList']),
                                parent::format_date(),
                                'N',
                                $UserData['data']->log_id
                            ),
                            'class' => __CLASS__
                        ));
                    } else {
                        $error_count++;
                    }
                }
            }

            //Gudang Rak
            $resetGudangRak = self::$query->update('master_inv_gudang_rak', array(
                'deleted_at' => parent::format_date()
            ))
                ->where(array(
                    'master_inv_gudang_rak.barang' => '= ?'
                ), array(
                    $uid
                ))
                ->execute();

            $requestGudangRakIDs = array();
            $oldGudangRakMeta = array();
            $oldGudangRak = self::$query->select('master_inv_gudang_rak', array(
                'id',
                'barang',
                'gudang',
                'rak'
            ))
                ->where(array(
                    'master_inv_gudang_rak.barang' => '= ?'
                ), array(
                    $uid
                ))
                ->execute();

            foreach ($oldGudangRak['response_data'] as $key => $value) {
                if (!in_array($value['id'], $requestGudangRakIDs)) {
                    array_push($requestGudangRakIDs, $value['id']);
                    array_push($oldGudangRakMeta, $value);
                }
            }

            foreach ($parameter['gudangMeta'] as $key => $value) {
                if (isset($requestGudangRakIDs[$key])) {
                    $updateGudangRak = self::$query->update('master_inv_gudang_rak', array(
                        'barang' => $uid,
                        'gudang' => $value['gudang'],
                        'rak' => $value['lokasi'],
                        'updated_at' => parent::format_date(),
                        'deleted_at' => NULL
                    ))
                        ->where(array(
                            'master_inv_gudang_rak.id' => '= ?'
                        ), array(
                            $requestGudangRakIDs[$key]
                        ))
                        ->execute();
                    if ($updateGudangRak['response_result'] > 0) {
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
                                'master_inv_gudang_rak',
                                'U',
                                json_encode($oldGudangRakMeta[$key]),
                                json_encode($parameter['gudangMeta']),
                                parent::format_date(),
                                'N',
                                $UserData['data']->log_id
                            ),
                            'class' => __CLASS__
                        ));
                    } else {
                        $error_count++;
                    }
                } else {
                    $newGudangRak = self::$query->insert('master_inv_gudang_rak', array(
                        'barang' => $uid,
                        'gudang' => $value['gudang'],
                        'rak' => $value['lokasi'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                    if ($newGudangRak['response_result'] > 0) {
                        $log = parent::log(array(
                            'type' => 'activity',
                            'column' => array(
                                'unique_target',
                                'user_uid',
                                'table_name',
                                'action',
                                'new_value',
                                'logged_at',
                                'status',
                                'login_id'
                            ),
                            'value' => array(
                                $parameter['uid'],
                                $UserData['data']->uid,
                                'master_inv_gudang_rak',
                                'I',
                                json_encode($parameter['gudangMeta']),
                                parent::format_date(),
                                'N',
                                $UserData['data']->log_id
                            ),
                            'class' => __CLASS__
                        ));
                    } else {
                        $error_count++;
                    }
                }
            }

            //Monitoring
            $resetMonitoring = self::$query->update('master_inv_monitoring', array(
                'deleted_at' => parent::format_date()
            ))
                ->where(array(
                    'master_inv_monitoring.barang' => '= ?'
                ), array(
                    $uid
                ))
                ->execute();

            $requestMonitoringIDs = array();
            $oldMonitoringMeta = array();
            $oldMonitoring = self::$query->select('master_inv_monitoring', array(
                'id',
                'barang',
                'gudang',
                'min',
                'max'
            ))
                ->where(array(
                    'master_inv_monitoring.barang' => '= ?'
                ), array(
                    $uid
                ))
                ->execute();

            foreach ($oldMonitoring['response_data'] as $key => $value) {
                if (!in_array($value['id'], $requestMonitoringIDs)) {
                    array_push($requestMonitoringIDs, $value['id']);
                    array_push($oldMonitoringMeta, $value);
                }
            }

            foreach ($parameter['monitoring'] as $key => $value) {
                if (isset($requestMonitoringIDs[$key])) {
                    $updateMonitoring = self::$query->update('master_inv_monitoring', array(
                        'barang' => $uid,
                        'gudang' => $value['gudang'],
                        'min' => $value['min'],
                        'max' => $value['max'],
                        'updated_at' => parent::format_date(),
                        'deleted_at' => NULL
                    ))
                        ->where(array(
                            'master_inv_monitoring.id' => '= ?'
                        ), array(
                            $requestMonitoringIDs[$key]
                        ))
                        ->execute();
                    if ($updateMonitoring['response_result'] > 0) {
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
                                'master_inv_monitoring',
                                'U',
                                json_encode($oldMonitoringMeta[$key]),
                                json_encode($parameter['monitoring']),
                                parent::format_date(),
                                'N',
                                $UserData['data']->log_id
                            ),
                            'class' => __CLASS__
                        ));
                    } else {
                        $error_count++;
                    }
                } else {
                    $newMonitoring = self::$query->insert('master_inv_monitoring', array(
                        'barang' => $uid,
                        'gudang' => $value['gudang'],
                        'min' => $value['min'],
                        'max' => $value['max'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                    if ($newMonitoring['response_result'] > 0) {
                        $log = parent::log(array(
                            'type' => 'activity',
                            'column' => array(
                                'unique_target',
                                'user_uid',
                                'table_name',
                                'action',
                                'new_value',
                                'logged_at',
                                'status',
                                'login_id'
                            ),
                            'value' => array(
                                $parameter['uid'],
                                $UserData['data']->uid,
                                'master_inv_monitoring',
                                'I',
                                json_encode($parameter['monitoring']),
                                parent::format_date(),
                                'N',
                                $UserData['data']->log_id
                            ),
                            'class' => __CLASS__
                        ));
                    } else {
                        $error_count++;
                    }
                }
            }



            //Kandungan

            //hard_delete
            $old_kandungan = self::$query->hard_delete('master_inv_obat_kandungan')
                ->where(array(
                    'master_inv_obat_kandungan.uid_obat' => '= ?'
                ), array(
                    $uid
                ))
                ->execute();

            foreach ($parameter['kandungan'] as $KandKey => $KandValue) {
                $kandungan_worker = self::$query->insert('master_inv_obat_kandungan', array(
                    'uid_obat' => $uid,
                    'kandungan' => $KandValue['kandungan'],
                    'keterangan' => $KandValue['keterangan'],
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
            }

        } else {
            $error_count++;
        }
        return $error_count;
    }

    private function tambah_amprah($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        //Get Last AI tahun ini
        $lastID = self::$query->select('inventori_amprah', array(
            'uid'
        ))
            ->where(array(
                'EXTRACT(year FROM inventori_amprah.created_at)' => '= ?'
            ), array(
                date('Y')
            ))
            ->execute();

        /*$Unit = new Unit(self::$pdo);
		$UnitInfo = $Unit::get_unit_detail();*/

        $uid = parent::gen_uuid();
        $worker = self::$query->insert('inventori_amprah', array(
            'uid' => $uid,
            'kode_amprah' => str_pad(count($lastID['response_data']) + 1, 5, '0', STR_PAD_LEFT) . '/' . $UserData['data']->unit_kode . '/AMP/' . date('m') . '/' . date('Y'),
            'unit' => $UserData['data']->unit,
            'pegawai' => $UserData['data']->uid,
            //'tanggal' => $parameter['tanggal'],
            'tanggal' => parent::format_date(),
            //'status' => ($UserData['data']->jabatan === __UIDAPOTEKER__) ? 'A' : 'N',
            'status' => ($UserData['data']->gudang === __GUDANG_APOTEK__) ? 'A' : 'N',
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date(),
            'keterangan' => $parameter['keterangan']
        ))
            ->execute();
        if ($worker['response_result'] > 0) {
            $worker['amprah_detail'] = array();
            $log = parent::log(array(
                'type' => 'activity',
                'column' => array(
                    'unique_target',
                    'user_uid',
                    'table_name',
                    'action',
                    'new_value',
                    'logged_at',
                    'status',
                    'login_id'
                ),
                'value' => array(
                    $uid,
                    $UserData['data']->uid,
                    'inventori_amprah',
                    'I',
                    json_encode($parameter),
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class' => __CLASS__
            ));

            foreach ($parameter['item'] as $key => $value) {
                //Amprah Detail
                $worker_detail = self::$query->insert('inventori_amprah_detail', array(
                    'amprah' => $uid,
                    'item' => $key,
                    'jumlah' => floatval($value['qty']),
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->returning('id')
                    ->execute();

                if ($worker_detail['response_result'] > 0) {
                    $log = parent::log(array(
                        'type' => 'activity',
                        'column' => array(
                            'unique_target',
                            'user_uid',
                            'table_name',
                            'action',
                            'new_value',
                            'logged_at',
                            'status',
                            'login_id'
                        ),
                        'value' => array(
                            $worker_detail['response_unique'],
                            $UserData['data']->uid,
                            'inventori_amprah_detail',
                            'I',
                            json_encode($parameter['item']),
                            parent::format_date(),
                            'N',
                            $UserData['data']->log_id
                        ),
                        'class' => __CLASS__
                    ));
                }
                array_push($worker['amprah_detail'], $worker_detail);
            }
        }
        return $worker;
    }

    private function get_amprah_request($parameter, $status = 'P')
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            if ($UserData['data']->unit == __UNIT_GUDANG__) {
                if ($status == 'S') {
                    $paramData = array(
                        'inventori_amprah.deleted_at' => 'IS NULL',
                        'AND',
                        'inventori_amprah.tanggal' => 'BETWEEN ? AND ?',
                        'AND',
                        'inventori_amprah.status' => '= ?',
                        'AND',
                        'inventori_amprah.kode_amprah' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
                    );

                    $paramValue = array(
                        $parameter['from'], $parameter['to'], 'S'
                    );
                } else {
                    $paramData = array(
                        'inventori_amprah.deleted_at' => 'IS NULL',
                        'AND',
                        'inventori_amprah.tanggal' => 'BETWEEN ? AND ?',
                        'AND',
                        '(NOT inventori_amprah.status' => '= ?',
                        'AND',
                        'NOT inventori_amprah.status' => '= ?)',
                        'AND',
                        'inventori_amprah.kode_amprah' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
                    );

                    $paramValue = array(
                        $parameter['from'], $parameter['to'], 'S', 'A'
                    );
                }
            } else {
                if ($status == 'S') {
                    $paramData = array(
                        'inventori_amprah.deleted_at' => 'IS NULL',
                        'AND',
                        'inventori_amprah.tanggal' => 'BETWEEN ? AND ?',
                        'AND',
                        'inventori_amprah.unit' => '= ?',
                        'AND',
                        'inventori_amprah.status' => '= ?',
                        'AND',
                        'inventori_amprah.kode_amprah' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
                    );

                    $paramValue = array(
                        $parameter['from'], $parameter['to'], $UserData['data']->unit, 'S'
                    );
                } else {
                    $paramData = array(
                        'inventori_amprah.deleted_at' => 'IS NULL',
                        'AND',
                        'inventori_amprah.tanggal' => 'BETWEEN ? AND ?',
                        'AND',
                        'inventori_amprah.unit' => '= ?',
                        'AND',
                        'NOT inventori_amprah.status' => '= ?',
                        'AND',
                        'inventori_amprah.kode_amprah' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
                    );

                    $paramValue = array(
                        $parameter['from'], $parameter['to'], $UserData['data']->unit, 'S'
                    );
                }
            }
        } else {
            if ($UserData['data']->unit == __UNIT_GUDANG__) {
                if ($status == 'S') {
                    $paramData = array(
                        'inventori_amprah.deleted_at' => 'IS NULL',
                        'AND',
                        'inventori_amprah.status' => '= ?',
                        'AND',
                        'inventori_amprah.tanggal' => 'BETWEEN ? AND ?'
                    );

                    $paramValue = array(
                        'S', $parameter['from'], $parameter['to']
                    );
                } else {
                    $paramData = array(
                        'inventori_amprah.deleted_at' => 'IS NULL',
                        'AND',
                        '(NOT inventori_amprah.status' => '= ?',
                        'AND',
                        'NOT inventori_amprah.status' => '= ?)',
                        'AND',
                        'inventori_amprah.tanggal' => 'BETWEEN ? AND ?'
                    );

                    $paramValue = array(
                        'S', 'A', $parameter['from'], $parameter['to']
                    );
                }
            } else {
                if ($status == 'S') {
                    $paramData = array(
                        'inventori_amprah.deleted_at' => 'IS NULL',
                        'AND',
                        'inventori_amprah.unit' => '= ?',
                        'AND',
                        'inventori_amprah.status' => '= ?',
                        'AND',
                        'inventori_amprah.tanggal' => 'BETWEEN ? AND ?'
                    );

                    $paramValue = array(
                        $UserData['data']->unit, 'S', $parameter['from'], $parameter['to']
                    );
                } else {
                    $paramData = array(
                        'inventori_amprah.deleted_at' => 'IS NULL',
                        'AND',
                        'inventori_amprah.unit' => '= ?',
                        'AND',
                        'NOT inventori_amprah.status' => '= ?',
                        'AND',
                        'inventori_amprah.tanggal' => 'BETWEEN ? AND ?'
                    );

                    $paramValue = array(
                        $UserData['data']->unit, 'S', $parameter['from'], $parameter['to']
                    );
                }
            }
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('inventori_amprah', array(
                'uid',
                'unit',
                'kode_amprah',
                'pegawai',
                'diproses',
                'tanggal',
                'status',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {

            $data = self::$query->select('inventori_amprah', array(
                'uid',
                'unit',
                'kode_amprah',
                'pegawai',
                'diproses',
                'tanggal',
                'status',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['tanggal'] = date('d F Y', strtotime($value['tanggal']));

            $Pegawai = new Pegawai(self::$pdo);
            $data['response_data'][$key]['pegawai'] = $Pegawai::get_detail($value['pegawai'])['response_data'][0];
            $data['response_data'][$key]['diproses'] = ((empty($value['diproses'])) ? "-" : $Pegawai::get_detail($value['pegawai'])['response_data'][0]);


            if ($value['status'] == 'N') {
                $statusParse = 'Baru';
            } else if ($value['status'] == 'S') {
                $statusParse = 'Selesai';
            } else if ($value['status'] == 'A') {
                $statusParse = 'Butuh Approval';
            } else if ($value['status'] == 'B') {
                $statusParse = 'Sudah Approve';
            } else {
                $statusParse = 'Ditolak';
            }
            $data['response_data'][$key]['status_caption'] = $statusParse;
            $autonum++;
        }

        $dataTotal = self::$query->select('inventori_amprah', array(
            'uid',
            'unit',
            'kode_amprah',
            'pegawai',
            'diproses',
            'tanggal',
            'status',
            'created_at',
            'updated_at'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($dataTotal['response_data']);
        $data['recordsFiltered'] = count($dataTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    public function get_amprah_detail($parameter)
    {
        $data = self::$query->select('inventori_amprah', array(
            'uid',
            'unit',
            'pegawai',
            'kode_amprah',
            'diproses',
            'keterangan',
            'tanggal',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'inventori_amprah.uid' => '= ?',
                'AND',
                'inventori_amprah.deleted_at' => 'IS NULL'
            ), array(
                $parameter
            ))
            ->execute();
        if (count($data['response_data']) > 0) {
            $data['response_data'][0]['tanggal'] = date('d F Y  [H:i]', strtotime($data['response_data'][0]['created_at']));
            $Pegawai = new Pegawai(self::$pdo);
            $data['response_data'][0]['pegawai_detail'] = $Pegawai->get_detail($data['response_data'][0]['pegawai'])['response_data'][0];
            $Unit = new Unit(self::$pdo);
            $data['response_data'][0]['pegawai_detail']['unit_detail'] = $Unit->get_unit_detail($data['response_data'][0]['pegawai_detail']['unit'])['response_data'][0];
            $data['response_data'][0]['diproses_detail'] = $Pegawai->get_detail($data['response_data'][0]['diproses'])['response_data'][0];

            $data_detail = self::$query->select('inventori_amprah_detail', array(
                'id',
                'amprah',
                'item',
                'jumlah',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'inventori_amprah_detail.deleted_at' => 'IS NULL',
                    'AND',
                    'inventori_amprah_detail.amprah' => '= ?'
                ), array(
                    $parameter
                ))
                ->execute();
            if (count($data_detail['response_data']) > 0) {
                $detail_amprah = array();
                foreach ($data_detail['response_data'] as $key => $value) {
                    //Batch Gudang
                    $value['batch'] = self::get_item_batch($value['item']);

                    $ItemDetail = self::get_item_detail($value['item'])['response_data'][0];
                    $ItemDetail['satuan_terkecil'] = self::get_satuan_detail($ItemDetail['satuan_terkecil'])['response_data'][0];
                    $value['item'] = $ItemDetail;

                    array_push($detail_amprah, $value);
                }
                $data['response_data'][0]['amprah_detail'] = $detail_amprah;
            }
        }
        return $data;
    }

    private function proses_amprah($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $uid = parent::gen_uuid();
        $Unit = new Unit(self::$pdo);

        //Check apakah sudah pernah di proses atau belum
        $checkAmprahProses = self::$query->select('inventori_amprah_proses', array(
            'uid'
        ))
            ->where(array(
                'inventori_amprah_proses.amprah' => '= ?'
            ), array(
                $parameter['amprah']
            ))
            ->execute();
            
        if(count($checkAmprahProses['response_data']) <= 0) {
            //Get Last AI tahun ini
            $lastID = self::$query->select('inventori_amprah_proses', array(
                'uid'
            ))
                ->where(array(
                    'EXTRACT(year FROM inventori_amprah_proses.created_at)' => '= ?'
                ), array(
                    date('Y')
                ))
                ->execute();

            $kodeAmprah = str_pad(count($lastID['response_data']) + 1, 5, '0', STR_PAD_LEFT) . '/' . $UserData['data']->unit_kode . '/AMP-OUT/' . date('m') . '/' . date('Y');

            $worker = self::$query->insert('inventori_amprah_proses', array(
                'uid' => $uid,
                'kode' => $kodeAmprah,
                'amprah' => $parameter['amprah'],
                'pegawai' => $UserData['data']->uid,
                'tanggal' => parent::format_date(),
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
                        'new_value',
                        'logged_at',
                        'status',
                        'login_id'
                    ),
                    'value' => array(
                        $uid,
                        $UserData['data']->uid,
                        'inventori_amprah_proses',
                        'I',
                        json_encode($parameter),
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                ));

                //Save Detail
                foreach ($parameter['data'] as $key => $value) {
                    foreach ($value['batch'] as $BKey => $BValue) {
                        if(floatval($BValue['disetujui']) > 0) { //Yg 0 ngapain catat bambang
                            $amprah_proses_detail = self::$query->insert('inventori_amprah_proses_detail', array(
                                'amprah_proses' => $uid,
                                'item' => $key,
                                'batch' => $BValue['batch'],
                                'qty' => $BValue['disetujui'],
                                'keterangan' => $parameter['data'][$key]['keterangan'],
                                'created_at' => parent::format_date(),
                                'updated_at' => parent::format_date()
                            ))
                                ->returning('id')
                                ->execute();

                            if ($amprah_proses_detail['response_result'] > 0) {
                                $log = parent::log(array(
                                    'type' => 'activity',
                                    'column' => array(
                                        'unique_target',
                                        'user_uid',
                                        'table_name',
                                        'action',
                                        'new_value',
                                        'logged_at',
                                        'status',
                                        'login_id'
                                    ),
                                    'value' => array(
                                        $amprah_proses_detail['response_unique'],
                                        $UserData['data']->uid,
                                        'inventori_amprah_proses_detail',
                                        'I',
                                        json_encode($parameter['data']['batch']),
                                        parent::format_date(),
                                        'N',
                                        $UserData['data']->log_id
                                    ),
                                    'class' => __CLASS__
                                ));


                                //Proses Kurang Stok
                                $lastStockMinus = self::$query->select('inventori_stok', array(
                                    'stok_terkini'
                                ))
                                    ->where(array(
                                        'inventori_stok.barang' => '= ?',
                                        'AND',
                                        'inventori_stok.batch' => '= ?',
                                        'AND',
                                        'inventori_stok.gudang' => '= ?'
                                    ), array(
                                        $key, $BValue['batch'], __GUDANG_UTAMA__
                                    ))
                                    ->execute();
                                $terkiniMinus = $lastStockMinus['response_data'][0]['stok_terkini'] - $BValue['disetujui'];
                                $minus_stock = self::$query->update('inventori_stok', array(
                                    'stok_terkini' => $terkiniMinus
                                ))
                                    ->where(array(
                                        'inventori_stok.barang' => '= ?',
                                        'AND',
                                        'inventori_stok.batch' => '= ?',
                                        'AND',
                                        'inventori_stok.gudang' => '= ?'
                                    ), array(
                                        $key, $BValue['batch'], __GUDANG_UTAMA__
                                    ))
                                    ->execute();

                                if ($minus_stock['response_result'] > 0) {
                                    $stok_log = self::$query->insert('inventori_stok_log', array(
                                        'barang' => $key,
                                        'batch' => $BValue['batch'],
                                        'gudang' => __GUDANG_UTAMA__,
                                        'masuk' => 0,
                                        'keluar' => $BValue['disetujui'],
                                        'saldo' => $terkiniMinus,
                                        'type' => __STATUS_AMPRAH__,
                                        'jenis_transaksi' => 'inventori_amprah_proses',
                                        'uid_foreign' => $uid,
                                        'keterangan' => 'Barang keluar karena proses amprah [' . $kodeAmprah . ']'
                                    ))
                                        ->execute();
                                }

                                //Proses Tambah Stok
                                //Dapatkan stok point

                                $UnitDetail = $Unit->get_unit_detail($parameter['dari_unit']);

                                $lastStockPlus = self::$query->select('inventori_stok', array(
                                    'stok_terkini'
                                ))
                                    ->where(array(
                                        'inventori_stok.barang' => '= ?',
                                        'AND',
                                        'inventori_stok.batch' => '= ?',
                                        'AND',
                                        'inventori_stok.gudang' => '= ?'
                                    ), array(
                                        $key, $BValue['batch'], $UnitDetail['response_data'][0]['gudang']
                                    ))
                                    ->execute();
                                $terkiniPlus = $lastStockPlus['response_data'][0]['stok_terkini'] + $BValue['disetujui'];

                                //Check Apakah stock point ada ?
                                $check_stok_point = self::$query->select('inventori_stok', array(
                                    'id'
                                ))
                                    ->where(array(
                                        'inventori_stok.barang' => '= ?',
                                        'AND',
                                        'inventori_stok.batch' => '= ?',
                                        'AND',
                                        'inventori_stok.gudang' => '= ?'
                                    ), array(
                                        $key, $BValue['batch'], $UnitDetail['response_data'][0]['gudang']
                                    ))
                                    ->execute();
                                if (count($check_stok_point['response_data']) > 0) {
                                    $plus_stock = self::$query->update('inventori_stok', array(
                                        'stok_terkini' => $terkiniPlus
                                    ))
                                        ->where(array(
                                            'inventori_stok.barang' => '= ?',
                                            'AND',
                                            'inventori_stok.batch' => '= ?',
                                            'AND',
                                            'inventori_stok.gudang' => '= ?'
                                        ), array(
                                            $key, $BValue['batch'], $UnitDetail['response_data'][0]['gudang']
                                        ))
                                        ->execute();
                                } else {
                                    $plus_stock = self::$query->insert('inventori_stok', array(
                                        'barang' => $key,
                                        'batch' => $BValue['batch'],
                                        'gudang' => $UnitDetail['response_data'][0]['gudang'],
                                        'stok_terkini' => $terkiniPlus
                                    ))
                                        ->execute();
                                }

                                if ($plus_stock['response_result'] > 0) {
                                    $stok_log = self::$query->insert('inventori_stok_log', array(
                                        'barang' => $key,
                                        'batch' => $BValue['batch'],
                                        'gudang' => $UnitDetail['response_data'][0]['gudang'],
                                        'masuk' => $BValue['disetujui'],
                                        'keluar' => 0,
                                        'saldo' => $terkiniPlus,
                                        'type' => __STATUS_AMPRAH__,
                                        'jenis_transaksi' => 'inventori_amprah_proses',
                                        'uid_foreign' => $uid,
                                        'keterangan' => 'Barang masuk karena proses amprah [' . $kodeAmprah . ']'
                                    ))
                                        ->execute();
                                }


                                if ($minus_stock['response_result'] > 0 && $plus_stock['response_result'] > 0) {
                                    //Update Status amprah menjadi selesai
                                    $update_amprah = self::$query->update('inventori_amprah', array(
                                        'status' => 'S',
                                        'updated_at' => parent::format_date()
                                    ))
                                        ->where(array(
                                            'inventori_amprah.deleted_at' => 'IS NULL',
                                            'AND',
                                            'inventori_amprah.uid' => '= ?'
                                        ), array(
                                            $parameter['amprah']
                                        ))
                                        ->execute();
                                }
                            }
                        }
                    }
                }
            }
        }

        return $worker;
    }


    private function get_amprah_proses_detail_uid($parameter) {
        $data = self::$query->select('inventori_amprah_proses', array(
            'uid',
            'kode',
            'amprah',
            'pegawai',
            'tanggal',
            'created_at'
        ))
            ->where(array(
                'inventori_amprah_proses.deleted_at' => 'IS NULL',
                'AND',
                'inventori_amprah_proses.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();

        //$Inventori = new Inventori(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);

        foreach ($data['response_data'] as $key => $value) {
            $amprah_detail = self::get_amprah_detail($value['amprah']);
            $data['response_data'][$key]['amprah'] = $amprah_detail['response_data'][0];
            $data['response_data'][$key]['tanggal'] = date('d F Y [H:i]', strtotime($value['created_at']));
            $PegawaiDetail = $Pegawai->get_detail($value['pegawai']);
            $data['response_data'][$key]['pegawai'] = $PegawaiDetail['response_data'][0];
            $detail_proses = self::$query->select('inventori_amprah_proses_detail', array(
                'id',
                'item',
                'batch',
                'keterangan',
                'qty'
            ))
                ->where(array(
                    'inventori_amprah_proses_detail.deleted_at' => 'IS NULL',
                    'AND',
                    'inventori_amprah_proses_detail.amprah_proses' => '= ?'
                ), array(
                    $value['uid']
                ))
                ->execute();
            $autonum = 1;
            foreach ($detail_proses['response_data'] as $DKey => $DValue) {
                $detail_proses['response_data'][$DKey]['item'] = self::get_item_detail($DValue['item'])['response_data'][0];
                $detail_proses['response_data'][$DKey]['batch'] = self::get_batch_detail($DValue['batch'])['response_data'][0];
                $detail_proses['response_data'][$DKey]['autonum'] = $autonum;
                $autonum++;
            }
            $data['response_data'][$key]['detail'] = $detail_proses['response_data'];
        }

        return $data;
    }


    private function get_amprah_proses_detail($parameter) {
        $data = self::$query->select('inventori_amprah_proses', array(
            'uid',
            'kode',
            'amprah',
            'pegawai',
            'tanggal',
            'created_at'
        ))
            ->where(array(
                'inventori_amprah_proses.deleted_at' => 'IS NULL',
                'AND',
                'inventori_amprah_proses.amprah' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();

        //$Inventori = new Inventori(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);

        foreach ($data['response_data'] as $key => $value) {
            $amprah_detail = self::get_amprah_detail($value['amprah']);
            $data['response_data'][$key]['amprah'] = $amprah_detail['response_data'][0];
            $data['response_data'][$key]['tanggal'] = date('d F Y [H:i]', strtotime($value['created_at']));
            $PegawaiDetail = $Pegawai->get_detail($value['pegawai']);
            $data['response_data'][$key]['pegawai'] = $PegawaiDetail['response_data'][0];
            $detail_proses = self::$query->select('inventori_amprah_proses_detail', array(
                'id',
                'item',
                'batch',
                'keterangan',
                'qty'
            ))
                ->where(array(
                    'inventori_amprah_proses_detail.deleted_at' => 'IS NULL',
                    'AND',
                    'inventori_amprah_proses_detail.amprah_proses' => '= ?'
                ), array(
                    $value['uid']
                ))
                ->execute();
            $autonum = 1;
            foreach ($detail_proses['response_data'] as $DKey => $DValue) {
                $detail_proses['response_data'][$DKey]['item'] = self::get_item_detail($DValue['item'])['response_data'][0];
                $detail_proses['response_data'][$DKey]['batch'] = self::get_batch_detail($DValue['batch'])['response_data'][0];
                $detail_proses['response_data'][$DKey]['autonum'] = $autonum;
                $autonum++;
            }
            $data['response_data'][$key]['detail'] = $detail_proses['response_data'];
        }

        return $data;
    }

    public function get_stok()
    {
        $data = self::$query->select('inventori_stok', array(
            'id',
            'barang',
            'batch',
            'gudang',
            'stok_terkini'
        ))
            ->execute();
        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['barang'] = self::get_item_detail($value['barang'])['response_data'][0];
            $data['response_data'][$key]['batch'] = self::get_batch_detail($value['batch'])['response_data'][0];
            $data['response_data'][$key]['batch']['expired_date'] = date('d F Y', strtotime($data['response_data'][$key]['batch']['expired_date']));

            $autonum++;
        }
        return $data;
    }

    private function get_return_detail($parameter) {
        $Pegawai = new Pegawai(self::$pdo);
        $Supplier = new Supplier(self::$pdo);

        $data = self::$query->select('inventori_return', array(
            'uid',
            'kode',
            'pegawai',
            'dokumen',
            'keterangan',
            'supplier',
            'created_at'
        ))
            ->order(array(
                'inventori_return.created_at' => 'DESC'
            ))
            ->where(array(
                'inventori_return.deleted_at' => 'IS NULL',
                'AND',
                'inventori_return.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();

        foreach ($data['response_data'] as $key => $value) {
            $detail = self::$query->select('inventori_return_detail', array(
                'barang', 'batch', 'qty'
            ))
                ->where(array(
                    'inventori_return_detail.inventori_return' => '= ?'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach ($detail['response_data'] as $DKey => $DValue) {
                $detail['response_data'][$DKey]['barang'] = self::get_item_detail($DValue['barang'])['response_data'][0];
                $detail['response_data'][$DKey]['batch'] = self::get_batch_detail($DValue['batch'])['response_data'][0];
            }
            $data['response_data'][$key]['pegawai'] = $Pegawai->get_info($value['pegawai'])['response_data'][0];
            $data['response_data'][$key]['supplier'] = $Supplier->get_detail($value['supplier']);
            $data['response_data'][$key]['detail'] = $detail['response_data'];
            $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
        }

        return $data;
    }

    private function export_current_gudang_stok($parameter) {

        $populateData = array();

        $paramData = array(
            'inventori_stok.gudang' => '= ?'
        );

        $paramValue = array($parameter['gudang']);

        $data = self::$query-> select('inventori_stok', array(
            'barang', 'batch', 'stok_terkini'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        foreach($data['response_data'] as $key => $value) {
            if(!isset($populateData[$value['barang']])) {
                $populateData[$value['barang']] = array();
            }

            if(!isset($populateData[$value['barang']][$value['batch']])) {
                $populateData[$value['barang']][$value['batch']] = array(
                    'ed' => '',
                    'jlh' => 0,
                    'dup' => 0
                );
            }

            if(floatval($populateData[$value['barang']][$value['batch']]['jlh']) > 0) {
                $populateData[$value['barang']][$value['batch']]['dup'] += 1;
            }

            $populateData[$value['barang']][$value['batch']]['jlh'] += floatval($value['stok_terkini']);
            $populateData[$value['barang']][$value['batch']]['ed'] = date('Y-m-d', strtotime(str_replace('"', '', self::get_batch_info($value['batch'])['response_data'][0]['expired_date'])));


            $data['response_data'][$key]['barang'] = str_replace('"', '', self::get_item_info($value['barang'])['response_data'][0]['nama']);
            $data['response_data'][$key]['batch'] = str_replace('"', '', self::get_batch_info($value['batch'])['response_data'][0]['batch']);
            $data['response_data'][$key]['ed'] = date('Y-m-d', strtotime(str_replace('"', '', self::get_batch_info($value['batch'])['response_data'][0]['expired_date'])));
        }

        $freshData = array();

        foreach($populateData as $key => $value) {
            foreach($value as $dBK => $dBV) {
                array_push($freshData, array(
                    'barang' => str_replace('"', '', self::get_item_info($key)['response_data'][0]['nama']),
                    'batch' => str_replace('"', '', self::get_batch_info($dBK)['response_data'][0]['batch']),
                    'ed' => $dBV['ed'],
                    'stok_terkini' => $dBV['jlh'],
                    'dup' => $dBV['dup']
                ));
            }
        }

        // $dataSet = '"No","Batch", "Item", "Saldo"';
        // foreach($data['response_data'] as $key => $value) {
        //     $dataSet .= '"' . $value['autonum'] . '", "' . $value['batch'] . '", "' . $value['barang'] . '", "' . $value['stok_terkini'] . '"\n';
        // }

        //return $data['response_data'];
        return $freshData;
    }

    private function data_populate_export_stok($parameter) {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'inventori_stok.gudang' => '= ?',
                'AND',
                'master_inv.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array($parameter['gudang']);
        } else {
            $paramData = array(
                'inventori_stok.gudang' => '= ?'
            );

            $paramValue = array($parameter['gudang']);
        }

        if ($parameter['length'] < 0) {
            $data = self::$query-> select('inventori_stok', array(
                'batch', 'stok_terkini'
            ))
                ->join('master_inv', array(
                    'nama as barang'
                ))
                ->on(array(
                    array('inventori_stok.barang', '=', 'master_inv.uid')
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query-> select('inventori_stok', array(
                'batch', 'stok_terkini'
            ))
                ->join('master_inv', array(
                    'nama as barang'
                ))
                ->on(array(
                    array('inventori_stok.barang', '=', 'master_inv.uid')
                ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;

        foreach($data['response_data'] as $key => $value) {
            //$data['response_data'][$key]['barang'] = str_replace('"', '', self::get_item_info($value['barang'])['response_data'][0]['nama']);
            $data['response_data'][$key]['batch'] = str_replace('"', '', self::get_batch_info($value['batch'])['response_data'][0]['batch']);
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['stok_terkini'] = floatval($value['stok_terkini']);
            $autonum++;
        }

        $itemTotal = self::$query->select('inventori_stok', array(
            'barang'
        ))
            ->where(array(
                'inventori_stok.gudang' => '= ?',
            ), array(
                $parameter['gudang']
            ))
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function get_return_entry($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $Supplier = new Supplier(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'inventori_return.kode' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'AND',
                'inventori_return.deleted_at' => 'IS NULL'
            );

            $paramValue = array();
        } else {
            $paramData = array(
                'inventori_return.deleted_at' => 'IS NULL'
            );

            $paramValue = array();
        }

        if ($parameter['length'] < 0) {
            $data = self::$query->select('inventori_return', array(
                'uid',
                'kode',
                'pegawai',
                'dokumen',
                'keterangan',
                'supplier',
                'created_at'
            ))
                ->order(array(
                    'inventori_return.created_at' => 'DESC'
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('inventori_return', array(
                'uid',
                'kode',
                'pegawai',
                'dokumen',
                'keterangan',
                'supplier',
                'created_at'
            ))
                ->order(array(
                    'inventori_return.created_at' => 'DESC'
                ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;

            $data['response_data'][$key]['supplier'] = $Supplier->get_detail($value['supplier']);
            $data['response_data'][$key]['pegawai'] = $Pegawai->get_info($value['pegawai'])['response_data'][0];
            $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));

            $autonum++;
        }

        $itemTotal = self::$query->select('inventori_return', array(
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

    private function stok_activity($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);


        $begin = new \DateTime($parameter['from']);
        $end = new \DateTime($parameter['to']);

        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);

        $dataSet = array(
            array(
                'backgroundColor' => array('rgba(63, 198, 0, 1)'),
                'borderColor' => array('rgba(120, 255, 58, 1)'),
                'label' => 'Stok Masuk',
                'fill' => false,
                'cubicInterpolationMode' => 'monotone',
                'tension' => 0.4,
                'data' => array()
            ),
            array(
                'backgroundColor' => array('rgba(239, 243, 0, 1)'),
                'borderColor' => array('rgba(255, 206, 86, 1)'),
                'label' => 'Stok Keluar',
                'fill' => false,
                'cubicInterpolationMode' => 'monotone',
                'tension' => 0.4,
                'data' => array()
            ),
            array(
                'backgroundColor' => array('rgba(0, 141, 250, 1)'),
                'borderColor' => array('rgba(0, 118, 210, 1)'),
                'label' => 'Saldo',
                'fill' => false,
                'cubicInterpolationMode' => 'monotone',
                'tension' => 0.4,
                'data' => array()
            ),
        );

        $labels = array();

        $dataQuery = array();

        foreach ($period as $dt) {
            array_push($labels, $dt->format('d-m-Y'));

            $dataIn = 0;
            $dataOut = 0;
            $dataSaldo = 0;


            $data = self::$query->select('inventori_stok_log', array(
                'barang', 'batch', 'gudang', 'masuk', 'keluar', 'saldo', 'type'
            ))
                ->where(array(
                    'inventori_stok_log.gudang' => '= ?',
                    'AND',
                    'inventori_stok_log.barang' => '= ?',
                    'AND',
                    'inventori_stok_log.logged_at::date' => '= date \'' . $dt->format('Y-m-d') . '\''
                ), array(
                    $UserData['data']->gudang,
                    $parameter['item']
                ))
                ->execute();

            array_push($dataQuery, $data);


            foreach ($data['response_data'] as $key => $value) {
                $dataIn += floatval($value['masuk']);
                $dataOut += floatval($value['keluar']);
                $dataSaldo += floatval($value['saldo']);
            }

            array_push($dataSet[0]['data'], $dataIn);
            array_push($dataSet[1]['data'], $dataOut);
            array_push($dataSet[2]['data'], $dataSaldo);
        }

        return array(
            'query' => $dataQuery,
            'labels' => $labels,
            'datasets' => $dataSet
        );
    }

    private function stok_monitoring($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'master_inv.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'AND',
                'master_inv_monitoring.deleted_at' => 'IS NULL',
                'AND',
                'master_inv_monitoring.gudang' => '= ?'
            );

            $paramValue = array($parameter['gudang']);
        } else {
            $paramData = array(
                'master_inv_monitoring.deleted_at' => 'IS NULL',
                'AND',
                'master_inv_monitoring.gudang' => '= ?'
            );

            $paramValue = array($parameter['gudang']);
        }

        if ($parameter['length'] < 0) {
            $data = self::$query->select('master_inv_monitoring', array(
                'barang',
                'gudang',
                'min',
                'max'
            ))
                ->join('master_inv', array(
                    'nama', 'kode_barang'
                ))
                ->on(array(
                    array('master_inv_monitoring.barang', '=', 'master_inv.uid')
                ))
                ->order(array(
                    'master_inv.nama' => 'ASC'
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('master_inv_monitoring', array(
                'barang',
                'gudang',
                'min',
                'max'
            ))
                ->order(array(
                    'master_inv.nama' => 'ASC'
                ))
                ->join('master_inv', array(
                    'nama', 'kode_barang'
                ))
                ->on(array(
                    array('master_inv_monitoring.barang', '=', 'master_inv.uid')
                ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $TotalCount = 0;
            $Counter = self::$query->select('inventori_stok', array(
                'stok_terkini'
            ))
                ->where(array(
                    'inventori_stok.barang' => '= ?'
                ), array(
                    $value['barang']
                ))
                ->execute();
            foreach ($Counter['response_data'] as $CKey => $CValue) {
                $TotalCount += $CValue['stok_terkini'];
            }
            $data['response_data'][$key]['total'] = $TotalCount;
            $autonum++;
        }

        $itemTotal = self::$query->select('master_inv_monitoring', array(
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
    
    private function get_temp_transact($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            if($UserData['data']->gudang === __GUDANG_UTAMA__) {
                $paramData = array(
                    'master_inv.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'AND',
                    'inventori_temp_stok.status' => '= ?'
                );

                $paramValue = array('P');
            } else {
                $paramData = array(
                    'master_inv.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'AND',
                    '(inventori_temp_stok.gudang_asal' => '= ?',
                    'OR',
                    'inventori_temp_stok.gudang_tujuan' => '= ?)',
                    'AND',
                    'inventori_temp_stok.status' => '= ?'
                );

                $paramValue = array($UserData['data']->gudang, $UserData['data']->gudang, 'P');
            }
        } else {
            if($UserData['data']->gudang === __GUDANG_UTAMA__) {
                $paramData = array(
                    'inventori_temp_stok.status' => '= ?'
                );

                $paramValue = array('P');
            } else {
                $paramData = array(
                    '(inventori_temp_stok.gudang_asal' => '= ?',
                    'OR',
                    'inventori_temp_stok.gudang_tujuan' => '= ?)',
                    'AND',
                    'inventori_temp_stok.status' => '= ?'
                );

                $paramValue = array($UserData['data']->gudang, $UserData['data']->gudang, 'P');
            }
        }

        if ($parameter['length'] < 0) {
            $data = self::$query->select('inventori_temp_stok', array(
                'transact_table',
                'transact_iden',
                'gudang_asal',
                'gudang_tujuan',
                'barang',
                'batch',
                'qty',
                'status',
                'remark',
                'logged_at'
            ))
                ->join('master_inv', array('nama'))
                ->on(array(
                    array('inventori_temp_stok.barang', '=', 'master_inv.uid')
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('inventori_temp_stok', array(
                'transact_table',
                'transact_iden',
                'gudang_asal',
                'gudang_tujuan',
                'barang',
                'batch',
                'qty',
                'status',
                'remark',
                'logged_at'
            ))
                ->join('master_inv', array('nama'))
                ->on(array(
                    array('inventori_temp_stok.barang', '=', 'master_inv.uid')
                ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['gudang_asal'] = self::get_gudang_detail($value['gudang_asal'])['response_data'][0];
            $data['response_data'][$key]['gudang_tujuan'] = self::get_gudang_detail($value['gudang_tujuan'])['response_data'][0];
            $data['response_data'][$key]['item'] = self::get_item_detail($value['barang'])['response_data'][0];
            $autonum++;
        }

        $itemTotal = self::$query->select('inventori_temp_stok', array(
            'id'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function post_opname_strategy($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $Pegawai = new Pegawai(self::$pdo);

        $forAmprah = array();
        $forMutasi = array();
        $forCut = array();

        $forAmprahProcess = array();
        $forMutasiProcess = array();
        $forCutProcess = array();

        $TempStrategy = self::$query->select('inventori_temp_stok', array(
            'id', 'transact_table', 'transact_iden', 'gudang_asal', 'gudang_tujuan', 'barang', 'batch', 'qty', 'remark', 'pegawai'
        ))
            ->where(array(
                'inventori_temp_stok.status' => '= ?',
                'AND',
                'inventori_temp_stok.pasca_opname' => 'IS NULL'
            ), array(
                'P'
            ))
            ->execute();

        //Todo: Check Jika stok berubah pasca opname approve
        /*foreach ($TempStrategy['response_data'] as $key => $value) {
            //Akktual Stok
            $postOpnameStok = self::$query->select('inventori_stok', array(
                'stok_terkini'
            ))
                ->where(array(
                    'inventori_stok.gudang' => '= ?',
                    'AND',
                    'inventori_stok.barang' => '= ?',
                    'AND',
                    'inventori_stok.batch' => '= ?'
                ), array(
                    $value['gudang_asal'], $value['barang'], $value['batch']
                ))
                ->execute();
            if()
        }*/

        foreach ($TempStrategy['response_data'] as $key => $value) {
            if(!isset($value['gudang_tujuan'])) {
                //Potong Stok Sendiri
                if(!isset($forCut[$value['gudang_asal']])) {
                    $forCut[$value['gudang_asal']] = array(
                        'item' => array(),
                        'pegawai' => $Pegawai->get_detail($value['pegawai'])['response_data'][0]
                    );
                }

                array_push($forCut[$value['gudang_asal']]['item'], $value);
            } else {
                if($value['gudang_asal'] === __GUDANG_UTAMA__) {
                    // Amprah
                    if(!isset($forAmprah[$value['gudang_tujuan']])) {
                        $forAmprah[$value['gudang_tujuan']] = array(
                            'item' => array(),
                            'pegawai' => $Pegawai->get_detail($value['pegawai'])['response_data'][0]
                        );
                    }

                    array_push($forAmprah[$value['gudang_tujuan']]['item'], $value);
                } else {
                    // Mutasi
                    if(!isset($forMutasi[$value['gudang_tujuan']])) {
                        $forMutasi[$value['gudang_tujuan']] = array(
                            'item' => array(),
                            'pegawai' => $Pegawai->get_detail($value['pegawai'])['response_data'][0]
                        );
                    }

                    array_push($forMutasi[$value['gudang_tujuan']]['item'], $value);
                }
            }
        }



        //Grouper Amprah
        foreach ($forAmprah as $amprahGud => $amprahDetail) {
            $uidAmprah = parent::gen_uuid();
            $lastIDAmprah = self::$query->select('inventori_amprah', array(
                'uid'
            ))
                ->where(array(
                    'EXTRACT(year FROM inventori_amprah.created_at)' => '= ?'
                ), array(
                    date('Y')
                ))
                ->execute();
            $amprahAuto = self::$query->insert('inventori_amprah', array(
                'uid' => $uidAmprah,
                'kode_amprah' => str_pad(count($lastIDAmprah['response_data']) + 1, 5, '0', STR_PAD_LEFT) . '/' . $amprahDetail['pegawai']['unit']['kode'] . '/AMP/' . date('m') . '/' . date('Y'),
                'unit' => $amprahDetail['pegawai']['unit'],
                'pegawai' => $amprahDetail['pegawai']['uid'],
                'tanggal' => parent::format_date(),
                'status' => 'S', //Auto Selesai
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date(),
                'keterangan' => 'Amprah AUTO karena kekurangan stok pada saat masa opname.'
            ))
                ->execute();

            if ($amprahAuto['response_result'] > 0) {
                $amprahAuto['amprah_detail'] = array();
                $log = parent::log(array(
                    'type' => 'activity',
                    'column' => array(
                        'unique_target',
                        'user_uid',
                        'table_name',
                        'action',
                        'new_value',
                        'logged_at',
                        'status',
                        'login_id'
                    ),
                    'value' => array(
                        $uidAmprah,
                        $amprahDetail['pegawai']['uid'],
                        'inventori_amprah',
                        'I',
                        json_encode($parameter),
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                ));

                //Amprah Proses
                $AmprahProsesUID = parent::gen_uuid();
                $lastAmprahProsesID = self::$query->select('inventori_amprah_proses', array(
                    'uid'
                ))
                    ->where(array(
                        'EXTRACT(year FROM inventori_amprah_proses.created_at)' => '= ?'
                    ), array(
                        date('Y')
                    ))
                    ->execute();

                $AmprahProses = self::$query->insert('inventori_amprah_proses', array(
                    'uid' => $AmprahProsesUID,
                    'kode' => str_pad(count($lastAmprahProsesID['response_data']) + 1, 5, '0', STR_PAD_LEFT) . '/' . $UserData['data']->unit_kode . '/AMP-OUT/' . date('m') . '/' . date('Y'),
                    'amprah' => $uidAmprah,
                    'tanggal' => parent::format_date(),
                    'pegawai' => $UserData['data']->uid,
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();


                foreach ($amprahDetail['item'] as $AmpDkey => $AmpDvalue) {
                    //Amprah Detail
                    $amprahAutoDetail = self::$query->insert('inventori_amprah_detail', array(
                        'amprah' => $uidAmprah,
                        'item' => $AmpDvalue['barang'],
                        'jumlah' => floatval($AmpDvalue['qty']),
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->returning('id')
                        ->execute();

                    if ($amprahAutoDetail['response_result'] > 0) {
                        $log = parent::log(array(
                            'type' => 'activity',
                            'column' => array(
                                'unique_target',
                                'user_uid',
                                'table_name',
                                'action',
                                'new_value',
                                'logged_at',
                                'status',
                                'login_id'
                            ),
                            'value' => array(
                                $amprahAutoDetail['response_unique'],
                                $amprahDetail['pegawai']['uid'],
                                'inventori_amprah_detail',
                                'I',
                                json_encode($parameter['item']),
                                parent::format_date(),
                                'N',
                                $UserData['data']->log_id
                            ),
                            'class' => __CLASS__
                        ));

                        if($AmprahProses['response_result'] > 0) {
                            $amprahAutoDetailProses = self::$query->insert('inventori_amprah_proses_detail', array(
                                'amprah_proses' => $AmprahProsesUID,
                                'item' => $AmpDvalue['barang'],
                                'batch' => $AmpDvalue['batch'],
                                'qty' => floatval($AmpDvalue['qty']),
                                'keterangan' => 'Amprah AUTO karena kekurangan stok pada saat masa opname.',
                                'created_at' => parent::format_date(),
                                'updated_at' => parent::format_date(),
                            ))
                                ->execute();

                            if($amprahAutoDetailProses['response_result'] > 0) {
                                //Stok Log
                                $OldStok = self::$query->select('inventori_stok', array(
                                    'stok_terkini'
                                ))
                                    ->where(array(
                                        'inventori_stok.gudang' => '= ?',
                                        'AND',
                                        'inventori_stok.barang' => '= ?',
                                        'AND',
                                        'inventori_stok.batch' => '= ?'
                                    ), array(
                                        __GUDANG_UTAMA__, $AmpDvalue['barang'], $AmpDvalue['batch']
                                    ))
                                    ->execute();

                                //UpdateStok
                                $OldStokUpdate = self::$query->update('inventori_stok', array(
                                    'stok_terkini' => floatval($OldStok['response_data'][0]['stok_terkini']) - floatval($AmpDvalue['qty'])
                                ))
                                    ->where(array(
                                        'inventori_stok.gudang' => '= ?',
                                        'AND',
                                        'inventori_stok.barang' => '= ?',
                                        'AND',
                                        'inventori_stok.batch' => '= ?'
                                    ), array(
                                        __GUDANG_UTAMA__, $AmpDvalue['barang'], $AmpDvalue['batch']
                                    ))
                                    ->execute();

                                if($OldStokUpdate['response_result'] > 0) {
                                    $OldLog = self::$query->insert('inventori_stok_log', array(
                                        'barang' => $AmpDvalue['barang'],
                                        'batch' => $AmpDvalue['batch'],
                                        'gudang' => __GUDANG_UTAMA__,
                                        'masuk' => 0,
                                        'keluar' => floatval($AmpDvalue['qty']),
                                        'saldo' => floatval($OldStok['response_data'][0]['stok_terkini']) - floatval($AmpDvalue['qty']),
                                        'type' => __AMPRAH_OPNAME_OUT__,
                                        'jenis_transaksi' => $AmpDvalue['transact_table'],
                                        'uid_foreign' => $AmpDvalue['transact_iden'],
                                        'keterangan' => 'Auto Amprah Pasca Opname'
                                    ))
                                        ->execute();
                                }

                                $DestStok = self::$query->select('inventori_stok', array(
                                    'stok_terkini'
                                ))
                                    ->where(array(
                                        'inventori_stok.gudang' => '= ?',
                                        'AND',
                                        'inventori_stok.barang' => '= ?',
                                        'AND',
                                        'inventori_stok.batch' => '= ?'
                                    ), array(
                                        $AmpDvalue['gudang_tujuan'], $AmpDvalue['barang'], $AmpDvalue['batch']
                                    ))
                                    ->execute();

                                $DestStokUpdate = self::$query->update('inventori_stok', array(
                                    'stok_terkini' => floatval($DestStok['response_data'][0]['stok_terkini']) + floatval($AmpDvalue['qty'])
                                ))
                                    ->where(array(
                                        'inventori_stok.gudang' => '= ?',
                                        'AND',
                                        'inventori_stok.barang' => '= ?',
                                        'AND',
                                        'inventori_stok.batch' => '= ?'
                                    ), array(
                                        $AmpDvalue['gudang_tujuan'], $AmpDvalue['barang'], $AmpDvalue['batch']
                                    ))
                                    ->execute();

                                if($DestStokUpdate['response_result'] > 0) {
                                    $NewLog = self::$query->insert('inventori_stok_log', array(
                                        'barang' => $AmpDvalue['barang'],
                                        'batch' => $AmpDvalue['batch'],
                                        'gudang' => $AmpDvalue['gudang_tujuan'],
                                        'masuk' => floatval($AmpDvalue['qty']),
                                        'keluar' => 0,
                                        'saldo' => floatval($DestStok['response_data'][0]['stok_terkini']) + floatval($AmpDvalue['qty']),
                                        'type' => __AMPRAH_OPNAME_IN__,
                                        'jenis_transaksi' => $AmpDvalue['transact_table'],
                                        'uid_foreign' => $AmpDvalue['transact_iden'],
                                        'keterangan' => 'Auto Amprah Pasca Opname'
                                    ))
                                        ->execute();

                                    $DestStokPostAmprahProcess = self::$query->select('inventori_stok', array(
                                        'stok_terkini'
                                    ))
                                        ->where(array(
                                            'inventori_stok.gudang' => '= ?',
                                            'AND',
                                            'inventori_stok.barang' => '= ?',
                                            'AND',
                                            'inventori_stok.batch' => '= ?'
                                        ), array(
                                            $AmpDvalue['gudang_tujuan'], $AmpDvalue['barang'], $AmpDvalue['batch']
                                        ))
                                        ->execute();

                                    //Potong Stok tujuan untuk peminjaman barang gudang
                                    $DestStokUpdatePostAmprah = self::$query->update('inventori_stok', array(
                                        'stok_terkini' => floatval($DestStokPostAmprahProcess['response_data'][0]['stok_terkini']) - floatval($AmpDvalue['qty'])
                                    ))
                                        ->where(array(
                                            'inventori_stok.gudang' => '= ?',
                                            'AND',
                                            'inventori_stok.barang' => '= ?',
                                            'AND',
                                            'inventori_stok.batch' => '= ?'
                                        ), array(
                                            $AmpDvalue['gudang_tujuan'], $AmpDvalue['barang'], $AmpDvalue['batch']
                                        ))
                                        ->execute();
                                    if($DestStokUpdatePostAmprah['response_result'] > 0) {
                                        $NewPostAmprahLog = self::$query->insert('inventori_stok_log', array(
                                            'barang' => $AmpDvalue['barang'],
                                            'batch' => $AmpDvalue['batch'],
                                            'gudang' => $AmpDvalue['gudang_tujuan'],
                                            'masuk' => 0,
                                            'keluar' => floatval($AmpDvalue['qty']),
                                            'saldo' => floatval($DestStokPostAmprahProcess['response_data'][0]['stok_terkini']) - floatval($AmpDvalue['qty']),
                                            'type' => __STATUS_BARANG_KELUAR_OPNAME__,
                                            'jenis_transaksi' => $AmpDvalue['transact_table'],
                                            'uid_foreign' => $AmpDvalue['transact_iden'],
                                            'keterangan' => 'Auto Amprah Pasca Opname'
                                        ))
                                            ->execute();
                                    }
                                }

                                array_push($forAmprahProcess, array(
                                    'new' => $NewLog,
                                    'old' => $OldLog
                                ));

                                if($OldLog['response_result'] > 0 && $NewLog['response_result'] > 0) {
                                    $TempFinish = self::$query->update('inventori_temp_stok', array(
                                        'status' => 'D'
                                    ))
                                        ->where(array(
                                            'inventori_temp_stok.id' => '= ?',
                                            'AND',
                                            'inventori_temp_stok.status' => '= ?'
                                        ), array(
                                            $AmpDvalue['id'], 'P'
                                        ))
                                        ->execute();
                                }
                            }
                        }
                    }
                }
            }
        }


        //Grouper Mutasi


        //Grouper Cut Single
        foreach ($forCut as $cutGud => $cutDetail) {

            foreach ($cutDetail['item'] as $cutDkey => $cutDvalue) {
                //Check Stok Pasca
                $OldStok = self::$query->select('inventori_stok', array(
                    'stok_terkini'
                ))
                    ->where(array(
                        'inventori_stok.gudang' => '= ?',
                        'AND',
                        'inventori_stok.barang' => '= ?',
                        'AND',
                        'inventori_stok.batch' => '= ?'
                    ), array(
                        $cutDvalue['gudang_asal'], $cutDvalue['barang'], $cutDvalue['batch']
                    ))
                    ->execute();

                $OldStokUpdate = self::$query->update('inventori_stok', array(
                    'stok_terkini' => floatval($OldStok['response_data'][0]['stok_terkini']) - floatval($cutDvalue['qty'])
                ))
                    ->where(array(
                        'inventori_stok.gudang' => '= ?',
                        'AND',
                        'inventori_stok.barang' => '= ?',
                        'AND',
                        'inventori_stok.batch' => '= ?'
                    ), array(
                        $cutDvalue['gudang_asal'], $cutDvalue['barang'], $cutDvalue['batch']
                    ))
                    ->execute();

                array_push($forCutProcess, $OldStokUpdate);

                if($OldStokUpdate['response_result'] > 0) {
                    $Log = self::$query->insert('inventori_stok_log', array(
                        'barang' => $cutDvalue['barang'],
                        'batch' => $cutDvalue['batch'],
                        'gudang' => $cutDvalue['gudang_asal'],
                        'masuk' => 0,
                        'keluar' => $cutDvalue['qty'],
                        'saldo' => floatval($OldStok['response_data'][0]['stok_terkini']) - floatval($cutDvalue['qty']),
                        'type' => __STATUS_BARANG_KELUAR_OPNAME__,
                        'jenis_transaksi' => $cutDvalue['transact_table'],
                        'uid_foreign' => $cutDvalue['transact_iden'],
                        'keterangan' => 'Auto Stok Pasca Opname'
                    ))
                        ->execute();

                    if($Log['response_result'] > 0) {
                        //Update Finish Auto
                        $TempFinish = self::$query->update('inventori_temp_stok', array(
                            'status' => 'D'
                        ))
                            ->where(array(
                                'inventori_temp_stok.id' => '= ?',
                                'AND',
                                'inventori_temp_stok.status' => '= ?'
                            ), array(
                                $cutDvalue['id'], 'P'
                            ))
                            ->execute();
                    }
                }
            }
        }

        //After Amprah dan Mutasi


        //Aktifkan Gudang Kembali
        $GudangActivate = self::$query
            ->update('master_inv_gudang', array(
                'status' => 'A'
            ))
            ->where(array(
                'master_inv_gudang.deleted_at' => 'IS NULL'
            ), array())
            ->execute();

        $DataOpname = self::$query->select('inventori_stok_opname', array(
            'uid', 'gudang'
        ))
            ->where(array(
                'inventori_stok_opname.deleted_at' => 'IS NULL',
                'AND',
                'inventori_stok_opname.status' => '= ?'
            ), array(
                'A'
            ))
            ->execute();
        foreach ($DataOpname['response_data'] as $OpKey => $OpValue) {
            $OpDetail = self::$query->select('inventori_stok_opname_detail', array(
                'id', 'item', 'batch', 'qty_awal', 'qty_akhir', 'keterangan'
            ))
                ->where(array(
                    'inventori_stok_opname_detail.opname' => '= ?'
                ), array(
                    $OpValue['uid']
                ))
                ->execute();
            foreach ($OpDetail['response_data'] as $OpDetailKey => $OpDetailValue) {
                /*$CurrentSaldo = self::$query->select('inventori_stok', array(
                    'stok_terkini'
                ))
                    ->where(array(
                        'inventori_stok.gudang' => '= ?',
                        'AND',
                        'inventori_stok.barang' => '= ?',
                        'AND',
                        'inventori_stok.batch' => '= ?'
                    ), array(
                        $OpValue['gudang'], $OpDetailValue['item'], $OpDetailValue['batch']
                    ))
                    ->execute();*/

                /*
                 *
                 * Ini dulu ada. Tapi dinonaktifkan karena masuk log opname di awal approve data opname. Lagian Raja Api Ozai nyerang tiba"
                 *
                 * if(floatval($OpDetailValue['qty_awal']) > floatval($OpDetailValue['qty_akhir'])) {
                    $LogOpname = self::$query->insert('inventori_stok_log', array(
                        'barang' => $OpDetailValue['item'],
                        'batch' => $OpDetailValue['batch'],
                        'gudang' => $OpValue['gudang'],
                        'masuk' => 0,
                        'keluar' => (floatval($OpDetailValue['qty_awal']) - floatval($OpDetailValue['qty_akhir'])),
                        'saldo' => floatval($OpDetailValue['qty_akhir']),
                        'type' => __STATUS_OPNAME__,
                        'jenis_transaksi' => 'inventori_stok_opname',
                        'uid_foreign' => $OpValue['uid'],
                        'keterangan' => 'Stok Opname Selesai'
                    ))
                        ->execute();
                } elseif (floatval($OpDetailValue['qty_awal']) < floatval($OpDetailValue['qty_akhir'])) {
                    $LogOpname = self::$query->insert('inventori_stok_log', array(
                        'barang' => $OpDetailValue['item'],
                        'batch' => $OpDetailValue['batch'],
                        'gudang' => $OpValue['gudang'],
                        'masuk' => (floatval($OpDetailValue['qty_akhir']) - floatval($OpDetailValue['qty_awal'])),
                        'keluar' => 0,
                        'saldo' => floatval($OpDetailValue['qty_akhir']),
                        'type' => __STATUS_OPNAME__,
                        'jenis_transaksi' => 'inventori_stok_opname',
                        'uid_foreign' => $OpValue['uid'],
                        'keterangan' => 'Stok Opname Selesai'
                    ))
                        ->execute();
                } else {
                    $LogOpname = self::$query->insert('inventori_stok_log', array(
                        'barang' => $OpDetailValue['item'],
                        'batch' => $OpDetailValue['batch'],
                        'gudang' => $OpValue['gudang'],
                        'masuk' => 0,
                        'keluar' => 0,
                        'saldo' => floatval($OpDetailValue['qty_akhir']),
                        'type' => __STATUS_OPNAME__,
                        'jenis_transaksi' => 'inventori_stok_opname',
                        'uid_foreign' => $OpValue['uid'],
                        'keterangan' => 'Stok Opname Selesai'
                    ))
                        ->execute();
                }*/

            }
        }

        //Tutup Semua Opname
        $CloseOpname = self::$query->update('inventori_stok_opname', array(
            'status' => 'C'
        ))
            ->where(array(
                'inventori_stok_opname.status' => '= ?'
            ), array(
                'A'
            ))
            ->execute();

        return array(
            'forCut' => $forCut,
            'forAmprah' => $forAmprah,
            'forMutasi' => $forMutasi,
        );
    }

    private function post_opname_warehouse($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);


        $LatestOpname = self::$query->select('inventori_stok_opname', array(
            'uid', 'gudang'
        ))
            ->where(array(
                'inventori_stok_opname.deleted_at' => 'IS NULL',
                'AND',
                'inventori_stok_opname.status' => '= ?',
                'AND',
                'inventori_stok_opname.gudang' => '= ?'
            ), array(
                'P', $UserData['data']->gudang
            ))
            ->order(array(
                'created_at' => 'DESC'
            ))
            ->limit(1)
            ->execute();

        if($UserData['data']->gudang === __GUDANG_UTAMA__) {
            if(count($LatestOpname['response_data']) > 0) {
                $UpdateOpname = self::$query->update('inventori_stok_opname', array(
                    'status' => 'D'
                ))
                    ->where(array(
                        'inventori_stok_opname.deleted_at' => 'IS NULL',
                        'AND',
                        'inventori_stok_opname.uid' => '= ?',
                        'AND',
                        'inventori_stok_opname.status' => '= ?',
                        'AND',
                        'inventori_stok_opname.gudang' => '= ?'
                    ), array(
                        $LatestOpname['response_data'][0]['uid'], 'P', $UserData['data']->gudang
                    ))
                    ->execute();

                if($UpdateOpname['response_result'] > 0) {
                    //Update Stok

                    $OpnameDetail = self::$query->select('inventori_stok_opname_detail', array(
                        'item',
                        'batch',
                        'qty_awal',
                        'qty_akhir',
                        'keterangan'
                    ))
                        ->where(array(
                            'inventori_stok_opname_detail.opname' => '= ?'
                        ), array(
                            $LatestOpname['response_data'][0]['uid']
                        ))
                        ->execute();

                    foreach ($OpnameDetail as $OptStokKey => $OptStokValue) {
                        $updateStok = self::$query->update('inventori_stok', array(
                            'stok_terkini' => floatval($OptStokValue['qty_akhir'])
                        ))
                            ->where(array(
                                'inventori_stok.barang' => '= ?',
                                'AND',
                                'inventori_stok.batch' => '= ?',
                                'AND',
                                'inventori_stok.gudang' => '= ?'
                            ), array(
                                $OptStokValue['item'], $OptStokValue['batch'], $LatestOpname['response_data'][0]['gudang']
                            ))
                            ->execute();

                        if ($updateStok['response_result'] > 0) {
                            if (floatval($OptStokValue['qty_akhir']) > floatval($OptStokValue['qty_awal'])) {
                                $stok_log = self::$query->insert('inventori_stok_log', array(
                                    'barang' => $OptStokValue['item'],
                                    'batch' => $OptStokValue['batch'],
                                    'gudang' => $LatestOpname['response_data'][0]['gudang'],
                                    'masuk' => floatval($OptStokValue['qty_akhir']) - floatval($OptStokValue['qty_awal']),
                                    'keluar' => 0,
                                    'saldo' => floatval($OptStokValue['qty_akhir']),
                                    'type' => __STATUS_OPNAME__
                                ))
                                    ->execute();
                            } else {
                                $stok_log = self::$query->insert('inventori_stok_log', array(
                                    'barang' => $OptStokValue['item'],
                                    'batch' => $OptStokValue['batch'],
                                    'gudang' => $LatestOpname['response_data'][0]['gudang'],
                                    'masuk' => 0,
                                    'keluar' => floatval($OptStokValue['qty_akhir']) - floatval($OptStokValue['qty_awal']),
                                    'saldo' => floatval($OptStokValue['qty_akhir']),
                                    'type' => __STATUS_OPNAME__
                                ))
                                    ->execute();
                            }

                            /*//Recalculate Temporary Transact. Kasus : Jika pelayanan dulu baru opname
                            $CheckTempStokGud = self::$query->select('inventori_temp_stok', array(
                                'id', 'transact_table', 'transact_iden', 'gudang_asal', 'gudang_tujuan', 'barang', 'batch', 'qty', 'remark'
                            ))
                                ->where(array(
                                    'inventori_temp_stok.gudang_asal' => '= ?',
                                    'AND',
                                    'inventori_temp_stok.status' => '= ?'
                                ), array(
                                    $LatestOpname['response_data'][0]['gudang'], 'P'
                                ))
                                ->execute();
                            //Sini*/
                        }
                    }
                }
            }

            //Check jika masih ada gudang yang belum selesai opname
            $checkProgressStat = self::$query->select('inventori_stok_opname', array(
                'uid', 'gudang'
            ))
                ->where(array(
                    'inventori_stok_opname.status' => '= ?',
                    'AND',
                    'inventori_stok_opname.gudang' => '!= ?'
                ), array(
                    'P', __GUDANG_UTAMA__
                ))
                ->execute();

            if(count($checkProgressStat['response_data']) > 0) {
                foreach ($checkProgressStat['response_data'] as $pendGudKey => $pendGudValue) {
                    $checkProgressStat['response_data'][$pendGudKey]['gudang'] = self::get_gudang_detail($pendGudValue['gudang'])['response_data'][0];
                }
                return array(
                    'response_result' => 0,
                    'response_message' => 'Masih ada gudang yang belum selesai opname',
                    'gudang_progress' => $checkProgressStat['response_data']
                );
            } else {
                $UpdateAllOpname = self::$query->update('inventori_stok_opname', array(
                    'status' => 'A'
                ))
                    ->where(array(
                        'inventori_stok_opname.deleted_at' => 'IS NULL',
                        'AND',
                        'inventori_stok_opname.status' => '= ?'
                    ), array(
                        'D'
                    ))
                    ->execute();

                if($UpdateAllOpname['response_result'] > 0) {


                    //Todo: Log Opname Semua gudang yang melakukan opname
                    $DataOpname = self::$query->select('inventori_stok_opname', array(
                        'uid', 'gudang'
                    ))
                        ->where(array(
                            'inventori_stok_opname.deleted_at' => 'IS NULL',
                            'AND',
                            'inventori_stok_opname.status' => '= ?'
                        ), array(
                            'A'
                        ))
                        ->execute();
                    foreach ($DataOpname['response_data'] as $OpKey => $OpValue) {
                        $OpDetail = self::$query->select('inventori_stok_opname_detail', array(
                            'id', 'item', 'batch', 'qty_awal', 'qty_akhir', 'keterangan'
                        ))
                            ->where(array(
                                'inventori_stok_opname_detail.opname' => '= ?'
                            ), array(
                                $OpValue['uid']
                            ))
                            ->execute();
                        foreach ($OpDetail['response_data'] as $OpDetailKey => $OpDetailValue) {
                            $updateStokAllGudang = self::$query->update('inventori_stok', array(
                                'stok_terkini' => floatval($OpDetailValue['qty_akhir'])
                            ))
                                ->where(array(
                                    'inventori_stok.barang' => '= ?',
                                    'AND',
                                    'inventori_stok.batch' => '= ?',
                                    'AND',
                                    'inventori_stok.gudang' => '= ?'
                                ), array(
                                    $OpDetailValue['item'], $OpDetailValue['batch'], $OpValue['gudang']
                                ))
                                ->execute();
                            if($updateStokAllGudang['response_result'] > 0) {
                                if(floatval($OpDetailValue['qty_awal']) > floatval($OpDetailValue['qty_akhir'])) {
                                    $LogOpname = self::$query->insert('inventori_stok_log', array(
                                        'barang' => $OpDetailValue['item'],
                                        'batch' => $OpDetailValue['batch'],
                                        'gudang' => $OpValue['gudang'],
                                        'masuk' => 0,
                                        'keluar' => (floatval($OpDetailValue['qty_awal']) - floatval($OpDetailValue['qty_akhir'])),
                                        'saldo' => floatval($OpDetailValue['qty_akhir']),
                                        'type' => __STATUS_OPNAME__,
                                        'jenis_transaksi' => 'inventori_stok_opname',
                                        'uid_foreign' => $OpValue['uid'],
                                        'keterangan' => 'Stok Opname Approved Data. ' . $OpDetailValue['keterangan']
                                    ))
                                        ->execute();
                                } elseif (floatval($OpDetailValue['qty_awal']) < floatval($OpDetailValue['qty_akhir'])) {
                                    $LogOpname = self::$query->insert('inventori_stok_log', array(
                                        'barang' => $OpDetailValue['item'],
                                        'batch' => $OpDetailValue['batch'],
                                        'gudang' => $OpValue['gudang'],
                                        'masuk' => (floatval($OpDetailValue['qty_akhir']) - floatval($OpDetailValue['qty_awal'])),
                                        'keluar' => 0,
                                        'saldo' => floatval($OpDetailValue['qty_akhir']),
                                        'type' => __STATUS_OPNAME__,
                                        'jenis_transaksi' => 'inventori_stok_opname',
                                        'uid_foreign' => $OpValue['uid'],
                                        'keterangan' => 'Stok Opname Approved Data. ' . $OpDetailValue['keterangan']
                                    ))
                                        ->execute();
                                } else {
                                    $LogOpname = self::$query->insert('inventori_stok_log', array(
                                        'barang' => $OpDetailValue['item'],
                                        'batch' => $OpDetailValue['batch'],
                                        'gudang' => $OpValue['gudang'],
                                        'masuk' => 0,
                                        'keluar' => 0,
                                        'saldo' => floatval($OpDetailValue['qty_akhir']),
                                        'type' => __STATUS_OPNAME__,
                                        'jenis_transaksi' => 'inventori_stok_opname',
                                        'uid_foreign' => $OpValue['uid'],
                                        'keterangan' => 'Stok Opname Approved Data. ' . $OpDetailValue['keterangan']
                                    ))
                                        ->execute();
                                }
                            }




                            //Sini Rupanya
                        }
                    }



                    /*$worker = self::$query
                        ->update('master_inv_gudang', array(
                            'status' => 'A'
                        ))
                        ->where(array(
                            'master_inv_gudang.deleted_at' => 'IS NULL'
                        ), array())
                        ->execute();
                    $UpdateAllOpname['gudang_status'] = $worker;*/
                    return $UpdateAllOpname;
                } else {
                    return array(
                        'response_result' => -1
                    );
                }
            }
        } else {
            $CheckRebase = array();
            if(count($LatestOpname['response_data']) > 0) {
                $CurrentUIDOpname = $LatestOpname['response_data'][0]['uid'];
                $UpdateOpname = self::$query->update('inventori_stok_opname', array(
                    'status' => 'D'
                ))
                    ->where(array(
                        'inventori_stok_opname.deleted_at' => 'IS NULL',
                        'AND',
                        'inventori_stok_opname.uid' => '= ?',
                        'AND',
                        'inventori_stok_opname.status' => '= ?',
                        'AND',
                        'inventori_stok_opname.gudang' => '= ?'
                    ), array(
                        $LatestOpname['response_data'][0]['uid'], 'P', $UserData['data']->gudang
                    ))
                    ->execute();

                //Rebase All Temp
                $CheckTempStokGud = self::$query->select('inventori_temp_stok', array(
                    'id', 'transact_table', 'transact_iden', 'gudang_asal', 'gudang_tujuan', 'barang', 'batch', 'qty', 'remark', 'pegawai'
                ))
                    ->where(array(
                        'inventori_temp_stok.status' => '= ?'
                    ), array(
                        'P'
                    ))
                    ->execute();
                foreach ($CheckTempStokGud['response_data'] as $key => $value) {
                    $CheckPascaOpname = self::$query->select('inventori_stok_opname_detail', array(
                        'qty_awal', 'qty_akhir'
                    ))
                        ->where(array(
                            'inventori_stok_opname_detail.item' => '= ?',
                            'AND',
                            'inventori_stok_opname_detail.batch' => '= ?',
                            'AND',
                            'inventori_stok_opname_detail.opname' => '= ?'
                        ), array(
                            $value['barang'],
                            $value['batch'],
                            $CurrentUIDOpname
                        ))
                        ->execute();

                    /*$CheckPascaOpname = self::$query->select('inventori_stok', array(
                        'id',
                        'stok_terkini'
                    ))
                        ->where(array(
                            'inventori_stok.barang' => '= ?',
                            'AND',
                            'inventori_stok.batch' => '= ?',
                            'AND',
                            'inventori_stok.gudang' => '= ?'
                        ), array(
                            $value['barang'],
                            $value['batch'],
                            $value['gudang_asal']
                        ))
                        ->execute();*/

                    if(count($CheckPascaOpname['response_data']) > 0) {
                        $currentCheckData = $CheckPascaOpname['response_data'][0];

                        if(floatval($currentCheckData['qty_akhir']) < floatval($value['qty'])) {

                            $SelisihKebutuhan = floatval($value['qty']) - floatval($currentCheckData['qty_akhir']);
                            $kebutuhanKekurangan = $SelisihKebutuhan;


                            //Pecah Permintaan dengan amprah
                            $UpdateCurrentTransact = self::$query->update('inventori_temp_stok', array(
                                'qty' => floatval($currentCheckData['qty_akhir'])
                            ))
                                ->where(array(
                                    'inventori_temp_stok.id' => '= ?'
                                ), array(
                                    $value['id']
                                ))
                                ->execute();

                            //Add Transact Temporary
                            $AvailResult = array();
                            $AvailAmprahUtama = self::get_item_batch($value['barang']);
                            foreach ($AvailAmprahUtama['response_data'] as $AvaKey => $AvaValue) {
                                if($AvaValue['gudang']['uid'] === __GUDANG_UTAMA__) {
                                    if($kebutuhanKekurangan >= $AvaValue['stok_terkini']) {
                                        if($AvaValue['stok_terkini'] > 0) {
                                            array_push($AvailResult, array(
                                                'batch' => $AvaValue['batch'],
                                                'barang' => $AvaValue['barang'],
                                                'gudang' => $AvaValue['gudang']['uid'],
                                                'qty' => $AvaValue['qty_akhir']
                                            ));
                                            $kebutuhanKekurangan -= floatval($AvaValue['stok_terkini']);
                                        }
                                    } else {
                                        if($AvaValue['stok_terkini'] > 0) {
                                            array_push($AvailResult, array(
                                                'batch' => $AvaValue['batch'],
                                                'barang' => $AvaValue['barang'],
                                                'gudang' => $AvaValue['gudang']['uid'],
                                                'qty' => $kebutuhanKekurangan
                                            ));
                                            $kebutuhanKekurangan = 0;
                                        }
                                    }
                                }
                            }

                            foreach ($AvailResult as $AvailKey => $AvailValue) {
                                $InsertCurrentTransact = self::$query->insert('inventori_temp_stok', array(
                                    'transact_table' => $value['transact_table'],
                                    'transact_iden' => $value['transact_iden'],
                                    'gudang_asal' => __GUDANG_UTAMA__,
                                    'gudang_tujuan' => $value['gudang_asal'],
                                    'barang' => $AvailValue['barang'],
                                    'batch' => $AvailValue['batch'],
                                    'qty' => $AvailValue['qty'],
                                    'status' => 'P',
                                    'remark' => $value['remark'],
                                    'logged_at' => parent::format_date(),
                                    'pegawai' => $value['pegawai']
                                ))
                                    ->execute();
                            }
                        }
                    }
                }

                return $UpdateOpname;
            } else {
                return array(
                    'response_result' => -1
                );
            }
        }
    }

    private function opname_warehouse($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        //Semua transaksi temporary harus diselesaikan
        $TempCheck = self::$query->select('inventori_temp_stok', array(
            'id', 'transact_table', 'transact_iden', 'gudang_asal', 'gudang_tujuan', 'barang', 'batch', 'qty', 'remark'
        ))
            ->where(array(
                'inventori_temp_stok.status' => '= ?'
            ), array(
                'P'
            ))
            ->execute();

        if(count($TempCheck['response_data']) === 0) {
            $worker = self::$query
                ->update('master_inv_gudang', array(
                    'status' => 'O'
                ))
                ->where(array(
                    'master_inv_gudang.deleted_at' => 'IS NULL'
                ), array())
                /*->where(array(
                    'master_inv_gudang.deleted_at' => 'IS NULL',
                    'AND',
                    'master_inv_gudang.uid' => '= ?'
                ), array(
                    $UserData['data']->gudang
                ))*/
                ->execute();

            $worker['temp_stok'] = $TempCheck['response_data'];
            return $worker;
        } else {
            return array(
                'reponse_result' => 0
            );
        }
    }

    private function reset_stok_log() {
        //Drop all stok log
        $Delete = self::$query->hard_delete('inventori_stok_log')
            ->execute();
        if($Delete['response_result'] > 0) {
            //Get all stok
            $AllStok = self::$query->select('inventori_stok', array(
                'barang', 'batch', 'gudang', 'stok_terkini'
            ))
                ->execute();
            foreach ($AllStok['response_data'] as $key => $value) {
                //First Log
                $Log = self::$query->insert('inventori_stok_log', array(
                    'barang' => $value['barang'],
                    'batch' => $value['batch'],
                    'gudang' => $value['gudang'],
                    'masuk' => $value['stok_terkini'],
                    'keluar' => 0,
                    'saldo' => $value['stok_terkini'],
                    'logged_at' => parent::format_date(),
                    'keterangan' => 'Stok terkini'
                ))
                    ->execute();
            }
        }
        return $Delete;
    }

    private function get_stok_batch_unit($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'inventori_stok.barang' => '= ?',
                'AND',
                'inventori_stok.gudang' => '= ?',
                'AND',
                'inventori_batch.batch' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array($parameter['barang'], $parameter['gudang']);
        } else {
            $paramData = array(
                'inventori_stok.barang' => '= ?',
                'AND',
                'inventori_stok.gudang' => '= ?'
            );

            $paramValue = array($parameter['barang'], $parameter['gudang']);
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('inventori_stok', array(
                'id',
                'barang',
                'batch',
                'gudang',
                'stok_terkini'
            ))
                ->join('inventori_batch', array(
                    'batch',
                    'expired_date'
                ))
                ->on(array(
                    array(
                        'inventori_stok.batch', '=', 'inventori_batch.uid'
                    )
                ))
                ->order(array(
                    'inventori_batch.expired_date' => 'ASC'
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('inventori_stok', array(
                'id',
                'barang',
                'batch',
                'gudang',
                'stok_terkini'
            ))
                ->join('inventori_batch', array(
                    'batch',
                    'expired_date'
                ))
                ->on(array(
                    array(
                        'inventori_stok.batch', '=', 'inventori_batch.uid'
                    )
                ))
                ->order(array(
                    'inventori_batch.expired_date' => 'ASC'
                ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['barang'] = self::get_item_detail($value['barang'])['response_data'][0];
            $data['response_data'][$key]['expired_date'] = date('d F Y', strtotime($value['expired_date']));
            //$data['response_data'][$key]['batch']['expired_date'] = date('d F Y', strtotime($data['response_data'][$key]['batch']['expired_date']));

            $autonum++;
        }

        $itemTotal = self::$query->select('inventori_stok', array(
            'id'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;


    }

    private function get_stok_log_backend($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'inventori_stok_log.type' => '= ?',
                'AND',
                'inventori_stok_log.gudang' => '= ?',
                'AND',
                'master_inv.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array(__STATUS_STOK_AWAL__, $parameter['gudang']);
        } else {
            $paramData = array(
                'inventori_stok_log.type' => '= ?',
                'AND',
                'inventori_stok_log.gudang' => '= ?'
            );

            $paramValue = array(__STATUS_STOK_AWAL__, $parameter['gudang']);
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('inventori_stok_log', array(
                'id',
                'barang',
                'batch',
                'gudang',
                'masuk',
                'keluar',
                'saldo'
            ))
                ->join('master_inv', array(
                    'nama'
                ))
                ->on(array(
                    array('inventori_stok_log.barang', '=' , 'master_inv.uid')
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('inventori_stok_log', array(
                'id',
                'barang',
                'batch',
                'gudang',
                'masuk',
                'keluar',
                'saldo'
            ))
                ->join('master_inv', array(
                    'nama'
                ))
                ->on(array(
                    array('inventori_stok_log.barang', '=' , 'master_inv.uid')
                ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['barang'] = self::get_item_detail($value['barang'])['response_data'][0];
            $data['response_data'][$key]['batch'] = self::get_batch_detail($value['batch'])['response_data'][0];
            $data['response_data'][$key]['batch']['expired_date'] = date('d F Y', strtotime($data['response_data'][$key]['batch']['expired_date']));

            $autonum++;
        }

        $itemTotal = self::$query->select('inventori_stok_log', array(
            'id'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    public function get_stok_log()
    {
        $data = self::$query->select('inventori_stok_log', array(
            'id',
            'barang',
            'batch',
            'gudang',
            'masuk',
            'keluar',
            'saldo'
        ))
            ->where(array(
                'inventori_stok_log.type' => '= ?'
            ), array(
                __STATUS_STOK_AWAL__
            ))
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['barang'] = self::get_item_detail($value['barang'])['response_data'][0];
            $data['response_data'][$key]['batch'] = self::get_batch_detail($value['batch'])['response_data'][0];
            $data['response_data'][$key]['batch']['expired_date'] = date('d F Y', strtotime($data['response_data'][$key]['batch']['expired_date']));

            $autonum++;
        }
        return $data;
    }

    private function tambah_stok_awal($parameter)
    {
        //Check ketersediaan batch
        $batchCheck = self::$query->select('inventori_batch', array(
            'uid'
        ))
            ->where(array(
                'inventori_batch.barang' => '= ?',
                'AND',
                'inventori_batch.batch' => '= ?'
            ), array(
                $parameter['item'],
                $parameter['batch']
            ))
            ->execute();

        if (count($batchCheck['response_data']) > 0) {
            $targetBatch = $batchCheck['response_data'][0]['uid'];
            $batchEnabling = self::$query->update('inventori_batch', array(
                'deleted_at' => NULL
            ))
                ->where(array(
                    'inventori_batch.uid' => '= ?'
                ), array(
                    $targetBatch
                ))
                ->execute();

        } else {
            $targetBatch = parent::gen_uuid();
            $batchNew = self::$query->insert('inventori_batch', array(
                'uid' => $targetBatch,
                'barang' => $parameter['item'],
                'batch' => strtoupper($parameter['batch']),
                'expired_date' => date('Y-m-d', strtotime($parameter['exp'])),
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();
        }

        $worker = self::$query->select('inventori_stok', array(
            'id',
            'stok_terkini'
        ))
            ->where(array(
                'inventori_stok.barang' => '= ?',
                'AND',
                'inventori_stok.batch' => '= ?',
                'AND',
                'inventori_stok.gudang' => '= ?'
            ), array(
                $parameter['item'],
                $targetBatch,
                $parameter['gudang']
            ))
            ->execute();

        if (count($worker['response_data']) > 0) {
            $endTotal = ($worker['response_data'][0]['stok_terkini'] + $parameter['qty']);
            $proceedStokPoint = self::$query->update('inventori_stok', array(
                'stok_terkini' => $endTotal
            ))
                ->where(array(
                    'inventori_stok.barang' => '= ?',
                    'AND',
                    'inventori_stok.batch' => '= ?',
                    'AND',
                    'inventori_stok.gudang' => '= ?'
                ), array(
                    $parameter['item'],
                    $targetBatch,
                    $parameter['gudang']
                ))
                ->execute();
        } else {
            $endTotal = $parameter['qty'];
            $proceedStokPoint = self::$query->insert('inventori_stok', array(
                'barang' => $parameter['item'],
                'batch' => $targetBatch,
                'gudang' => $parameter['gudang'],
                'stok_terkini' => $parameter['qty']
            ))
                ->execute();
        }

        if ($proceedStokPoint['response_result'] > 0) {
            //Stok Log
            $stok_log_worker = self::$query->insert('inventori_stok_log', array(
                'barang' => $parameter['item'],
                'batch' => $targetBatch,
                'gudang' => $parameter['gudang'],
                'masuk' => $parameter['qty'],
                'keluar' => 0,
                'saldo' => $endTotal,
                'type' => __STATUS_STOK_AWAL__
            ))
                ->execute();
        }

        return $proceedStokPoint;
    }

    private function get_stok_gudang($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'inventori_stok.gudang' => '= ?',
                'AND',
                'master_inv.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array(
                //$UserData['data']->gudang
                $parameter['gudang']
            );
        } else {
            $paramData = array(
                'inventori_stok.gudang' => '= ?'
            );

            $paramValue = array(
                $parameter['gudang']
                //$UserData['data']->gudang
            );
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('inventori_stok', array(
                'id',
                'barang',
                'batch',
                'gudang',
                'stok_terkini'
            ))
                ->join('master_inv', array(
                    'uid as uid_item',
                    'nama',
                    'satuan_terkecil'
                ))
                ->on(array(
                    array('inventori_stok.barang', '=', 'master_inv.uid')
                ))
                ->where($paramData, $paramValue)
                ->order(array(
                    'id' => 'ASC'
                ))
                ->execute();
        } else {
            $data = self::$query->select('inventori_stok', array(
                'id',
                'barang',
                'batch',
                'gudang',
                'stok_terkini'
            ))
                ->join('master_inv', array(
                    'uid',
                    'nama',
                    'satuan_terkecil'
                ))
                ->on(array(
                    array('inventori_stok.barang', '=', 'master_inv.uid')
                ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->order(array(
                    'id' => 'ASC'
                ))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $obatIdentifier = array();
        //Current Active Opname
        $CheckOpnameActive = self::$query->select('inventori_stok_opname', array(
            'uid', 'dari', 'sampai', 'pegawai', 'keterangan'
        ))
            ->where(array(
                'inventori_stok_opname.status' => '= ?',
                'AND',
                'inventori_stok_opname.gudang' => '= ?',
                'AND',
                'inventori_stok_opname.deleted_at' => 'IS NULL'
            ), array(
                'P', $UserData['data']->gudang
            ))
            ->order(array(
                'created_at' => 'DESC'
            ))
            ->limit(1)
            ->execute();
        if(count($CheckOpnameActive['response_data']) > 0) {
            $OPDetail = self::$query->select('inventori_stok_opname_detail', array(
                'id',
                'item',
                'batch',
                'qty_awal',
                'qty_akhir',
                'keterangan'
            ))
                ->where(array(
                    'inventori_stok_opname_detail.opname' => '= ?',
                    'AND',
                    'inventori_stok_opname_detail.deleted_at' => 'IS NULL'
                ), array(
                    $CheckOpnameActive['response_data'][0]['uid']
                ))
                ->execute();

            foreach ($OPDetail['response_data'] as $OPKey => $OPValue) {
                if(!isset($obatIdentifier[$OPValue['item']])) {
                    $obatIdentifier[$OPValue['item']] = array();
                    if(!isset($obatIdentifier[$OPValue['item']][$OPValue['batch']])) {
                        $obatIdentifier[$OPValue['item']][$OPValue['batch']] = array(
                            'qty' => 0,
                            'remark' => $OPValue['keterangan']
                        );
                    }
                }

                $obatIdentifier[$OPValue['item']][$OPValue['batch']]['qty'] = $OPValue['qty_akhir'];
            }
        }

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['satuan_terkecil'] = self::get_satuan_detail($value['satuan_terkecil'])['response_data'][0];
            $data['response_data'][$key]['old_value'] = $obatIdentifier[$value['uid']][$value['batch']]['qty'];
            $data['response_data'][$key]['keterangan'] = $obatIdentifier[$value['uid']][$value['batch']]['remark'];
            $data['response_data'][$key]['batch'] = self::get_batch_detail($value['batch'])['response_data'][0];
            $data['response_data'][$key]['batch']['expired_date'] = date('d F Y', strtotime($data['response_data'][$key]['batch']['expired_date']));
            $autonum++;
        }

        $dataTotal = self::$query->select('inventori_stok', array(
            'id',
            'barang',
            'batch',
            'gudang',
            'stok_terkini'
        ))
            ->join('master_inv', array(
                'uid',
                'nama'
            ))
            ->on(array(
                array('inventori_stok.barang', '=', 'master_inv.uid')
            ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($dataTotal['response_data']);
        $data['recordsFiltered'] = count($dataTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);
        $data['opname'] = $CheckOpnameActive['response_data'];
        $data['opname_iden'] = $obatIdentifier;
        $data['opname_detail'] = $OPDetail['response_data'][0]['detail'];
        $data['keterangan'] = $CheckOpnameActive['response_data'][0]['keterangan'];

        return $data;
    }




    private function get_stok_gudang_opname($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'inventori_stok.gudang' => '= ?',
                'AND',
                'master_inv.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array(
                //$UserData['data']->gudang
                $parameter['gudang']
            );
        } else {
            $paramData = array(
                'inventori_stok.gudang' => '= ?'
            );

            $paramValue = array(
                $parameter['gudang']
                //$UserData['data']->gudang
            );
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('inventori_stok', array(
                'id',
                'barang',
                'batch',
                'gudang',
                'stok_terkini'
            ))
                ->join('master_inv', array(
                    'uid as uid_item',
                    'nama',
                    'satuan_terkecil'
                ))
                ->on(array(
                    array('inventori_stok.barang', '=', 'master_inv.uid')
                ))
                ->where($paramData, $paramValue)
                ->order(array(
                    'id' => 'ASC'
                ))
                ->execute();
        } else {
            $data = self::$query->select('inventori_stok', array(
                'id',
                'barang',
                'batch',
                'gudang',
                'stok_terkini'
            ))
                ->join('master_inv', array(
                    'uid',
                    'nama',
                    'satuan_terkecil'
                ))
                ->on(array(
                    array('inventori_stok.barang', '=', 'master_inv.uid')
                ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->order(array(
                    'id' => 'ASC'
                ))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $obatIdentifier = array();
        //Current Active Opname
        $CheckOpnameActive = self::$query->select('inventori_stok_opname', array(
            'uid', 'dari', 'sampai', 'pegawai', 'keterangan'
        ))
            ->where(array(
                'inventori_stok_opname.status' => '= ?',
                'AND',
                'inventori_stok_opname.gudang' => '= ?',
                'AND',
                'inventori_stok_opname.deleted_at' => 'IS NULL'
            ), array(
                'P', $UserData['data']->gudang
            ))
            ->order(array(
                'created_at' => 'DESC'
            ))
            ->limit(1)
            ->execute();
        if(count($CheckOpnameActive['response_data']) > 0) {
            $OPDetail = self::$query->select('inventori_stok_opname_detail', array(
                'id',
                'item',
                'batch',
                'qty_awal',
                'qty_akhir',
                'supervisi',
                'keterangan'
            ))
                ->where(array(
                    'inventori_stok_opname_detail.opname' => '= ?',
                    'AND',
                    'inventori_stok_opname_detail.deleted_at' => 'IS NULL'
                ), array(
                    $CheckOpnameActive['response_data'][0]['uid']
                ))
                ->execute();

            foreach ($OPDetail['response_data'] as $OPKey => $OPValue) {
                if(!isset($obatIdentifier[$OPValue['item']])) {
                    $obatIdentifier[$OPValue['item']] = array();
                    if(!isset($obatIdentifier[$OPValue['item']][$OPValue['batch']])) {
                        $obatIdentifier[$OPValue['item']][$OPValue['batch']] = array(
                            'qty' => 0,
                            'remark' => $OPValue['keterangan'],
                            'supervisi' => $OPValue['supervisi']
                        );
                    }
                }

                $obatIdentifier[$OPValue['item']][$OPValue['batch']]['qty'] = $OPValue['qty_akhir'];
                $obatIdentifier[$OPValue['item']][$OPValue['batch']]['remark'] = $OPValue['keterangan'];
                $obatIdentifier[$OPValue['item']][$OPValue['batch']]['supervisi'] = $OPValue['supervisi'];
            }
        }

        $autonum = 1;
        $Pegawai = new Pegawai(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['satuan_terkecil'] = self::get_satuan_detail($value['satuan_terkecil'])['response_data'][0];
            $data['response_data'][$key]['old_value'] = $obatIdentifier[$value['uid']][$value['batch']]['qty'];
            $data['response_data'][$key]['keterangan'] = $obatIdentifier[$value['uid']][$value['batch']]['remark'];
            $data['response_data'][$key]['supervisi'] = $obatIdentifier[$value['uid']][$value['batch']]['supervisi'];
            $data['response_data'][$key]['supervisi_detail'] = $Pegawai->get_info($obatIdentifier[$value['uid']][$value['batch']]['supervisi'])['response_data'][0];
            $data['response_data'][$key]['batch'] = self::get_batch_detail($value['batch'])['response_data'][0];
            $data['response_data'][$key]['batch']['expired_date'] = date('d F Y', strtotime($data['response_data'][$key]['batch']['expired_date']));
            $autonum++;
        }

        $dataTotal = self::$query->select('inventori_stok', array(
            'id',
            'barang',
            'batch',
            'gudang',
            'stok_terkini'
        ))
            ->join('master_inv', array(
                'uid',
                'nama'
            ))
            ->on(array(
                array('inventori_stok.barang', '=', 'master_inv.uid')
            ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($dataTotal['response_data']);
        $data['recordsFiltered'] = count($dataTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);
        $data['opname'] = $CheckOpnameActive['response_data'];
        $data['opname_iden'] = $obatIdentifier;
        $data['opname_detail'] = $OPDetail['response_data'][0]['detail'];
        $data['keterangan'] = $CheckOpnameActive['response_data'][0]['keterangan'];

        return $data;
    }

    private function get_opname_history($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            if($UserData['data']->gudang === __GUDANG_UTAMA__) {
                $paramData = array(
                    'inventori_stok_opname.kode' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
                );

                $paramValue = array();
            } else {
                $paramData = array(
                    'inventori_stok_opname.gudang' => '= ?',
                    'AND',
                    'inventori_stok_opname.kode' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
                );

                $paramValue = array(
                    $UserData['data']->gudang
                );
            }
        } else {
            if($UserData['data']->gudang === __GUDANG_UTAMA__) {
                $paramData = array();

                $paramValue = array();
            } else {
                $paramData = array(
                    'inventori_stok_opname.gudang' => '= ?',
                );

                $paramValue = array(
                    $UserData['data']->gudang
                );
            }
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('inventori_stok_opname', array(
                'uid',
                'kode',
                'dari',
                'sampai',
                'pegawai',
                'gudang',
                'keterangan',
                'status',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('inventori_stok_opname', array(
                'uid',
                'kode',
                'dari',
                'sampai',
                'pegawai',
                'gudang',
                'keterangan',
                'status',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];

        $autonum = 1;
        $Pegawai = new Pegawai(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $PegawaiDetail = $Pegawai->get_detail($value['pegawai'])['response_data'][0];
            $data['response_data'][$key]['pegawai'] = $PegawaiDetail;

            $data['response_data'][$key]['gudang_detail'] = self::get_gudang_detail($value['gudang'])['response_data'][0];

            $data['response_data'][$key]['dari'] = date('d F Y', strtotime($value['dari']));
            $data['response_data'][$key]['sampai'] = date('d F Y', strtotime($value['sampai']));

            $data['response_data'][$key]['created_at'] = date('d F Y / H:i:s', strtotime($value['created_at']));
            $autonum++;
        }

        $dataTotal = self::$query->select('inventori_stok_opname', array(
            'uid',
            'kode',
            'dari',
            'sampai',
            'pegawai',
            'gudang',
            'keterangan',
            'created_at',
            'updated_at'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($dataTotal['response_data']);
        $data['recordsFiltered'] = count($dataTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function tambah_opname($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        //If progress
        $CheckLastOpname = self::$query->select('inventori_stok_opname', array(
            'uid', 'status'
        ))
            ->where(array(
                'inventori_stok_opname.deleted_at' => 'IS NULL',
                'AND',
                'inventori_stok_opname.gudang' => '= ?'
            ), array(
                $UserData['data']->gudang
            ))
            ->order(array(
                'created_at' => 'DESC'
            ))
            ->limit(1)
            ->execute();

        if(count($CheckLastOpname['response_data']) > 0) {
            if($CheckLastOpname['response_data'][0]['status'] === 'P') {
                // Bisa Edit
                //Reset Old Data
                // $deleteChild = self::$query->hard_delete('inventori_stok_opname_detail')
                //     ->where(array(
                //         'inventori_stok_opname_detail.opname' => '= ?'
                //     ), array(
                //         $CheckLastOpname['response_data'][0]['uid']
                //     ))
                //     ->execute();

                $worker = self::$query->update('inventori_stok_opname', array(
                    'dari' => date('Y-m-d', strtotime($parameter['dari'])),
                    'sampai' => date('Y-m-d', strtotime($parameter['sampai'])),
                    'keterangan' => $parameter['keterangan'],
                    'updated_at' => parent::format_date()
                ))
                    ->where(array(
                        'inventori_stok_opname.uid' => '= ?'
                    ), array(
                        $CheckLastOpname['response_data'][0]['uid']
                    ))
                    ->execute();

                if ($worker['response_result'] > 0) {
                    foreach ($parameter['item'] as $key => $value) {
                        $itemBatch = explode('_', $key);

                        //Check existing
                        $CheckOpnameItem = self::$query->select('inventori_stok_opname_detail', array(
                            'id', 'qty_akhir', 'keterangan', 'supervisi'
                        ))
                            ->where(array(
                                'inventori_stok_opname_detail.deleted_at' => 'IS NULL',
                                'AND',
                                'inventori_stok_opname_detail.opname' => '= ?',
                                'AND',
                                'inventori_stok_opname_detail.item' => '= ?',
                                'AND',
                                'inventori_stok_opname_detail.batch' => '= ?'
                            ), array(
                                $CheckLastOpname['response_data'][0]['uid'],
                                $itemBatch[0],
                                $value['batch']
                            ))
                            ->execute();
                        if(count($CheckOpnameItem['response_data']) > 0) {
                            if($CheckOpnameItem['response_data'][0]['supervisi'] === $UserData['data']->uid) {
                                $opname_detail = self::$query->update('inventori_stok_opname_detail', array(
                                    'qty_akhir' => $value['nilai'],
                                    'supervisi' => $UserData['data']->uid,
                                    'keterangan' => $value['keterangan'],
                                    'updated_at' => parent::format_date()
                                ))
                                    ->where(array(
                                        'inventori_stok_opname_detail.id' => '= ?',
                                        'AND',
                                        'inventori_stok_opname_detail.deleted_at' => 'IS NULL',
                                        'AND',
                                        'inventori_stok_opname_detail.opname' => '= ?',
                                        'AND',
                                        'inventori_stok_opname_detail.item' => '= ?',
                                        'AND',
                                        'inventori_stok_opname_detail.batch' => '= ?'
                                    ), array(
                                        $CheckOpnameItem['response_data'][0]['id'],
                                        $CheckLastOpname['response_data'][0]['uid'],
                                        $itemBatch[0],
                                        $value['batch']
                                    ))
                                    ->execute();
                            }
                        } else {
                            $opname_detail = self::$query->insert('inventori_stok_opname_detail', array(
                                'opname' => $CheckLastOpname['response_data'][0]['uid'],
                                'item' => $itemBatch[0],
                                'batch' => $value['batch'],
                                'qty_awal' => $value['qty_awal'],
                                'qty_akhir' => $value['nilai'],
                                'keterangan' => $value['keterangan'],
                                'supervisi' => ($value['signed'] > 0) ? $UserData['data']->uid : NULL,
                                'created_at' => parent::format_date(),
                                'updated_at' => parent::format_date()
                            ))
                                ->execute();
                        }
                    }

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
                            $CheckLastOpname['response_data'][0]['uid'],
                            $UserData['data']->uid,
                            'inventori_stok_opname',
                            'I',
                            parent::format_date(),
                            'N',
                            $UserData['data']->log_id
                        ),
                        'class' => __CLASS__
                    ));
                }

                return $worker;
            } else if($CheckLastOpname['response_data'][0]['status'] === 'D') {
                // Sudah selesai dari gudang terkini. Tidak bisa edit
                return 'Sudah selesai dari gudang terkini. Tidak bisa edit';
            }  else if($CheckLastOpname['response_data'][0]['status'] === 'A') {
                // Sudah di kunci gudang farmasi
                return 'Sudah di kunci gudang farmasi';
            } else if($CheckLastOpname['response_data'][0]['status'] === 'C') {
                // Sudah tutup masa opname

                //Get Last AI tahun ini
                $lastID = self::$query->select('inventori_stok_opname', array(
                    'uid'
                ))
                    ->where(array(
                        'EXTRACT(year FROM inventori_stok_opname.created_at)' => '= ?'
                    ), array(
                        date('Y')
                    ))
                    ->execute();
                $uid = parent::gen_uuid();
                $worker = self::$query->insert('inventori_stok_opname', array(
                    'uid' => $uid,
                    'kode' => str_pad(count($lastID['response_data']) + 1, 5, '0', STR_PAD_LEFT) . '/' . $UserData['data']->unit_kode . '/OPN/' . date('m') . '/' . date('Y'),
                    'dari' => date('Y-m-d', strtotime($parameter['dari'])),
                    'sampai' => date('Y-m-d', strtotime($parameter['sampai'])),
                    'pegawai' => $UserData['data']->uid,
                    'gudang' => $UserData['data']->gudang,
                    'status' => 'P',
                    'keterangan' => $parameter['keterangan'],
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();

                if ($worker['response_result'] > 0) {
                    foreach ($parameter['item'] as $key => $value) {
                        $opname_detail = self::$query->insert('inventori_stok_opname_detail', array(
                            'opname' => $uid,
                            'item' => $key,
                            'batch' => $value['batch'],
                            'qty_awal' => $value['qty_awal'],
                            'qty_akhir' => $value['nilai'],
                            'keterangan' => $value['keterangan'],
                            'supervisi' => ($value['signed'] > 0) ? $UserData['data']->uid : NULL,
                            'created_at' => parent::format_date(),
                            'updated_at' => parent::format_date()
                        ))
                            ->execute();

                        /*Jalankan ini
                         * if ($opname_detail['response_result'] >= 0) {
                            //Update Stok
                            $updateStok = self::$query->update('inventori_stok', array(
                                'stok_terkini' => floatval($value['nilai'])
                            ))
                                ->where(array(
                                    'inventori_stok.barang' => '= ?',
                                    'AND',
                                    'inventori_stok.batch' => '= ?',
                                    'AND',
                                    'inventori_stok.gudang' => '= ?'
                                ), array(
                                    $key, $value['batch'], $UserData['data']->gudang
                                ))
                                ->execute();
                            if ($updateStok['response_result'] > 0) {
                                if (floatval($value['nilai']) > floatval($value['qty_awal'])) {
                                    $stok_log = self::$query->insert('inventori_stok_log', array(
                                        'barang' => $key,
                                        'batch' => $value['batch'],
                                        'gudang' => $UserData['data']->gudang,
                                        'masuk' => floatval($value['qty_awal']) - floatval($value['nilai']),
                                        'keluar' => 0,
                                        'saldo' => floatval($value['nilai']),
                                        'type' => __STATUS_OPNAME__
                                    ))
                                        ->execute();
                                } else {
                                    $stok_log = self::$query->insert('inventori_stok_log', array(
                                        'barang' => $key,
                                        'batch' => $value['batch'],
                                        'gudang' => $UserData['data']->gudang,
                                        'masuk' => 0,
                                        'keluar' => floatval($value['nilai']) - floatval($value['qty_awal']),
                                        'saldo' => floatval($value['nilai']),
                                        'type' => __STATUS_OPNAME__
                                    ))
                                        ->execute();
                                }
                            }
                        }*/
                    }

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
                            'inventori_stok_opname',
                            'I',
                            parent::format_date(),
                            'N',
                            $UserData['data']->log_id
                        ),
                        'class' => __CLASS__
                    ));

                    //Proses Temp

                    //  1. Ambil transaksi resep dan racikan terlebih dahulu
                    /*$TempResep = self::proceed_temporary(array(
                        'target' => 'resep',
                        'opname' => $uid,
                    ));

                    $TempRacikan = self::proceed_temporary(array(
                        'target' => 'racikan',
                        'opname' => $uid,
                    ));*/
                }

                return $worker;
            }
        } else{
            $lastID = self::$query->select('inventori_stok_opname', array(
                'uid'
            ))
                ->where(array(
                    'EXTRACT(year FROM inventori_stok_opname.created_at)' => '= ?'
                ), array(
                    date('Y')
                ))
                ->execute();
            $uid = parent::gen_uuid();
            $worker = self::$query->insert('inventori_stok_opname', array(
                'uid' => $uid,
                'kode' => str_pad(count($lastID['response_data']) + 1, 5, '0', STR_PAD_LEFT) . '/' . $UserData['data']->unit_kode . '/OPN/' . date('m') . '/' . date('Y'),
                'dari' => date('Y-m-d', strtotime($parameter['dari'])),
                'sampai' => date('Y-m-d', strtotime($parameter['sampai'])),
                'pegawai' => $UserData['data']->uid,
                'gudang' => $UserData['data']->gudang,
                'status' => 'P',
                'keterangan' => $parameter['keterangan'],
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();

            if ($worker['response_result'] > 0) {
                foreach ($parameter['item'] as $key => $value) {
                    $opname_detail = self::$query->insert('inventori_stok_opname_detail', array(
                        'opname' => $uid,
                        'item' => $key,
                        'batch' => $value['batch'],
                        'qty_awal' => $value['qty_awal'],
                        'qty_akhir' => $value['nilai'],
                        'supervisi' => ($value['signed'] > 0) ? $UserData['data']->uid : NULL,
                        'keterangan' => $value['keterangan'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                }

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
                        'inventori_stok_opname',
                        'I',
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                ));
            }
            return $worker;
        }
    }

    public function proceed_temporary($parameter) {
        $CompletedStock = array();
        $OutStock = array();
        $TempData = self::$query->select('inventori_temp_stok', array(
            'id', 'transact_table', 'transact_iden', 'gudang_asal', 'gudang_tujuan', 'barang', 'batch', 'qty', 'remark'
        ))
            ->where(array(
                'inventori_temp_stok.transact_table' => '= ?',
                'AND',
                'inventori_temp_stok.pasca_opname' => 'IS NULL',
                'AND',
                'inventori_temp_stok.status' => '= ?'
            ), array(
                $parameter['target'], 'P'
            ))
            ->execute();
        foreach ($TempData['response_data'] as $TempKey => $TempValue) {
            //Check Ketersediaan pada gudang terkait
        }
        return $TempData;
    }

    private function get_opname_detail($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);


        if($UserData['data']->gudang === __GUDANG_UTAMA__) {
            $data = self::$query->select('inventori_stok_opname', array(
                'uid',
                'kode',
                'dari',
                'sampai',
                'pegawai',
                'created_at',
                'updated_at',
                'gudang',
                'keterangan'
            ))
                ->where(array(
                    'inventori_stok_opname.deleted_at' => 'IS NULL',
                    'AND',
                    'inventori_stok_opname.uid' => '= ?'
                ), array(
                    $parameter
                ))
                ->execute();
        } else {
            $data = self::$query->select('inventori_stok_opname', array(
                'uid',
                'kode',
                'dari',
                'sampai',
                'pegawai',
                'created_at',
                'updated_at',
                'gudang',
                'keterangan'
            ))
                ->where(array(
                    'inventori_stok_opname.deleted_at' => 'IS NULL',
                    'AND',
                    'inventori_stok_opname.uid' => '= ?',
                    'AND',
                    'inventori_stok_opname.gudang' => '= ?'
                ), array(
                    $parameter,
                    $UserData['data']->gudang
                ))
                ->execute();   
        }
        $Pegawai = new Pegawai(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['dari'] = date('d F Y', strtotime($value['dari']));
            $data['response_data'][$key]['sampai'] = date('d F Y', strtotime($value['sampai']));

            $PegawaiDetail = $Pegawai->get_info($value['pegawai'])['response_data'][0];
            $data['response_data'][$key]['pegawai'] = $PegawaiDetail;

            $OpnameDetail = self::$query->select('inventori_stok_opname_detail', array(
                'id',
                'item',
                'batch',
                'qty_awal',
                'qty_akhir',
                'keterangan'
            ))
                ->where(array(
                    'inventori_stok_opname_detail.opname' => '= ?',
                    'AND',
                    'inventori_stok_opname_detail.deleted_at' => 'IS NULL'
                ), array(
                    $parameter
                ))
                ->execute();
            $autonum = 1;
            foreach ($OpnameDetail['response_data'] as $OKey => $OValue) {
                $OpnameDetail['response_data'][$OKey]['autonum'] = $autonum;
                $OpnameDetail['response_data'][$OKey]['item'] = self::get_item_info($OValue['item'])['response_data'][0];
                $OpnameDetail['response_data'][$OKey]['batch'] = self::get_batch_info($OValue['batch'])['response_data'][0];
                $autonum++;
            }
            $data['response_data'][$key]['detail'] = $OpnameDetail['response_data'];
        }

        return $data;
    }

    private function get_opname_detail_item($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'inventori_stok_opname_detail.opname' => '= ?',
                'AND',
                'master_inv.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'AND',
                'inventori_stok_opname_detail.deleted_at' => 'IS NULL'
            );

            $paramValue = array(
                $parameter['uid']
            );
        } else {
            $paramData = array(
                'inventori_stok_opname_detail.opname' => '= ?',
                'AND',
                'inventori_stok_opname_detail.deleted_at' => 'IS NULL'
            );

            $paramValue = array(
                $parameter['uid']
            );
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('inventori_stok_opname_detail', array(
                'id',
                'item',
                'batch',
                'qty_awal',
                'qty_akhir',
                'keterangan'
            ))
                ->join('master_inv', array(
                    'uid',
                    'nama'
                ))
                ->on(array(
                    array('inventori_stok_opname_detail.item', '=', 'master_inv.uid')
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('inventori_stok_opname_detail', array(
                'id',
                'item',
                'batch',
                'qty_awal',
                'qty_akhir',
                'keterangan'
            ))
                ->join('master_inv', array(
                    'uid as uid_barang',
                    'nama as nama_barang'
                ))
                ->on(array(
                    array('inventori_stok_opname_detail.item', '=', 'master_inv.uid')
                ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['batch'] = self::get_batch_detail($value['batch'])['response_data'][0];
            $autonum++;
        }

        $dataTotal = self::$query->select('inventori_stok_opname_detail', array(
            'id',
            'item',
            'batch',
            'qty_awal',
            'qty_akhir',
            'keterangan'
        ))
            ->join('master_inv', array(
                'uid',
                'nama'
            ))
            ->on(array(
                array('inventori_stok_opname_detail.item', '=', 'master_inv.uid')
            ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($dataTotal['response_data']);
        $data['recordsFiltered'] = count($dataTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function proses_mutasi($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $data = self::$query->update('inventori_mutasi', array(
            'status' => $parameter['status'],
            'diproses_oleh' => $UserData['data']->uid,
            'updated_at' => parent::format_date()
        ))
            ->where(array(
                'inventori_mutasi.uid' => '= ?',
                'AND',
                'inventori_mutasi.deleted_at' => 'IS NULL'
            ), array(
                $parameter['uid']
            ))
            ->execute();

        if($data['response_result'] > 0) {
            if($parameter['status'] === 'R') {
                $target = self::$query->select('inventori_mutasi', array(
                    'dari', 'ke'
                ))
                    ->where(array(
                        'inventori_mutasi.uid' => '= ?'
                    ), array(
                        $parameter['uid']
                    ))
                    ->execute();
                foreach ($target['response_data'] as $tarKey => $tarValue) {
                    $CheckUnitAsal = self::$query->select('master_unit', array(
                        'uid'
                    ))
                        ->join('nurse_station', array(
                            'kode'
                        ))
                        ->on(array(
                            array('master_unit.uid', '=', 'nurse_station.unit')
                        ))
                        ->where(array(
                            'master_unit.gudang' => '= ?'
                        ), array(
                            $tarValue['dari']
                        ))
                        ->execute();

                    $CheckUnitTujuan = self::$query->select('master_unit', array(
                        'uid'
                    ))
                        ->join('nurse_station', array(
                            'kode'
                        ))
                        ->on(array(
                            array('master_unit.uid', '=', 'nurse_station.unit')
                        ))
                        ->where(array(
                            'master_unit.gudang' => '= ?'
                        ), array(
                            $tarValue['ke']
                        ))
                        ->execute();


                    $mutasi_detail = self::$query->select('inventori_mutasi_detail', array(
                        'item', 'batch', 'qty', 'keterangan'
                    ))
                        ->where(array(
                            'inventori_mutasi_detail.mutasi' => '= ?',
                            'AND',
                            'inventori_mutasi_detail.deleted_at' => 'IS NULL'
                        ), array(
                            $parameter['uid']
                        ))
                        ->execute();

                    foreach($mutasi_detail['response_data'] as $MutKey => $MutValue) {



                        $stok_dari_old = self::$query->select('inventori_stok', array(
                            'stok_terkini'
                        ))
                            ->where(array(
                                'inventori_stok.barang' => '= ?',
                                'AND',
                                'inventori_stok.batch' => '= ?',
                                'AND',
                                'inventori_stok.gudang' => '= ?'
                            ), array(
                                $MutValue['item'],
                                $MutValue['batch'],
                                $tarValue['dari']
                            ))
                            ->execute();

                        $update_stok_old_dari = self::$query->update('inventori_stok', array(
                            'stok_terkini' => floatval($stok_dari_old['response_data'][0]['stok_terkini']) - floatval($MutValue['qty'])
                        ))
                            ->where(array(
                                'inventori_stok.barang' => '= ?',
                                'AND',
                                'inventori_stok.batch' => '= ?',
                                'AND',
                                'inventori_stok.gudang' => '= ?'
                            ), array(
                                $MutValue['item'],
                                $MutValue['batch'],
                                $tarValue['dari']
                            ))
                            ->execute();

                        if ($update_stok_old_dari['response_result'] > 0) {

                            //Update Temp Status SINI
                            $TempStatus = self::$query->update('inventori_temp_stok', array(
                                'status' => 'D'
                            ))
                                ->where(array(
                                    'inventori_temp_stok.transact_table' => '= ?',
                                    'AND',
                                    'inventori_temp_stok.transact_iden' => '= ?',
                                    'AND',
                                    'inventori_temp_stok.barang' => '= ?',
                                    'AND',
                                    'inventori_temp_stok.batch' => '= ?'
                                ), array(
                                    'inventori_mutasi', $parameter['uid'], $MutValue['item'], $MutValue['batch']
                                ))
                                ->execute();
                            if($TempStatus['response_result'] > 0) {
                                //Update Stok Log Dari
                                $update_dari_log = self::$query->insert('inventori_stok_log', array(
                                    'barang' => $MutValue['item'],
                                    'batch' => $MutValue['batch'],
                                    'uid_foreign' => $parameter['uid'],
                                    'jenis_transaksi' => 'inventori_mutasi',
                                    'gudang' => $tarValue['dari'],
                                    'masuk' => 0,
                                    'keluar' => floatval($MutValue['qty']),
                                    'saldo' => floatval($stok_dari_old['response_data'][0]['stok_terkini']) - floatval($MutValue['qty']),
                                    'type' => (count($CheckUnitAsal['response_data']) > 0 || count($CheckUnitTujuan['response_data']) > 0) ? __STATUS_BARANG_KELUAR_INAP__ : __STATUS_MUTASI_STOK__,
                                    'keterangan' => $MutValue['keterangan']
                                ))
                                    ->execute();
                            }
                        }







                        //Update Stok Tujuan
                        $stok_ke_old = self::$query->select('inventori_stok', array(
                            'stok_terkini'
                        ))
                            ->where(array(
                                'inventori_stok.barang' => '= ?',
                                'AND',
                                'inventori_stok.batch' => '= ?',
                                'AND',
                                'inventori_stok.gudang' => '= ?'
                            ), array(
                                $MutValue['item'],
                                $MutValue['batch'],
                                $tarValue['ke']
                            ))
                            ->execute();

                        if (count($stok_ke_old['response_data']) > 0) {
                            $update_stok_old_ke = self::$query->update('inventori_stok', array(
                                'stok_terkini' => floatval($stok_ke_old['response_data'][0]['stok_terkini']) + floatval($MutValue['qty'])
                            ))
                                ->where(array(
                                    'inventori_stok.barang' => '= ?',
                                    'AND',
                                    'inventori_stok.batch' => '= ?',
                                    'AND',
                                    'inventori_stok.gudang' => '= ?'
                                ), array(
                                    $MutValue['item'],
                                    $MutValue['batch'],
                                    $tarValue['ke']
                                ))
                                ->execute();
                        } else {
                            $update_stok_old_ke = self::$query->insert('inventori_stok', array(
                                'stok_terkini' => floatval($MutValue['qty']),
                                'barang' => $MutValue['item'],
                                'batch' => $MutValue['batch'],
                                'gudang' => $tarValue['ke']
                            ))
                                ->execute();
                        }

                        if ($update_stok_old_ke['response_result'] > 0) {
                            //Update Stok Log Ke
                            $update_ke_log = self::$query->insert('inventori_stok_log', array(
                                'barang' => $MutValue['item'],
                                'batch' => $MutValue['batch'],
                                'uid_foreign' => $parameter['uid'],
                                'jenis_transaksi' => 'inventori_mutasi',
                                'gudang' => $tarValue['ke'],
                                'masuk' => floatval($MutValue['qty']),
                                'keluar' => 0,
                                'saldo' => floatval($stok_ke_old['response_data'][0]['stok_terkini']) + floatval($MutValue['qty']),
                                'type' => (count($CheckUnitAsal['response_data']) > 0 || count($CheckUnitTujuan['response_data']) > 0) ? __STATUS_BARANG_MASUK_INAP__ : __STATUS_MUTASI_STOK__,
                                'keterangan' => $MutValue['keterangan']
                            ))
                                ->execute();
                        }


                        //Checker Batch
                        $CheckBatchInap = self::$query->select('rawat_inap_batch', array(
                            'id'
                        ))->where(array(
                            'rawat_inap_batch.obat' => '= ?',
                            'AND',
                            'rawat_inap_batch.batch' => '= ?',
                            'AND',
                            'rawat_inap_batch.mutasi' => '= ?'
                        ), array(
                            $MutValue['item'],
                            $MutValue['batch'],
                            $parameter['uid']
                        ))
                            ->execute();
                        if(count($CheckBatchInap['response_data']) > 0) {
                            $CheckNS = self::$query->update('rawat_inap_batch', array(
                                'status' => 'Y'
                            ))
                                ->where(array(
                                    'rawat_inap_batch.obat' => '= ?',
                                    'AND',
                                    'rawat_inap_batch.batch' => '= ?',
                                    'AND',
                                    'rawat_inap_batch.mutasi' => '= ?'
                                ), array(
                                    $MutValue['item'],
                                    $MutValue['batch'],
                                    $parameter['uid']
                                ))
                                ->execute();
                        }



                        $CheckBatchIGD = self::$query->select('igd_batch', array(
                            'id'
                        ))->where(array(
                            'igd_batch.obat' => '= ?',
                            'AND',
                            'igd_batch.batch' => '= ?',
                            'AND',
                            'igd_batch.mutasi' => '= ?'
                        ), array(
                            $MutValue['item'],
                            $MutValue['batch'],
                            $parameter['uid']
                        ))
                            ->execute();
                        if(count($CheckBatchIGD['response_data']) > 0) {
                            $CheckNS = self::$query->update('igd_batch', array(
                                'status' => 'Y'
                            ))
                                ->where(array(
                                    'igd_batch.obat' => '= ?',
                                    'AND',
                                    'igd_batch.batch' => '= ?',
                                    'AND',
                                    'igd_batch.mutasi' => '= ?'
                                ), array(
                                    $MutValue['item'],
                                    $MutValue['batch'],
                                    $parameter['uid']
                                ))
                                ->execute();
                        }




                    }

                }
            }
        }

        return $data;
    }

    public function virtual_stok($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        //Check Status Gudang
        $Asal = self::get_gudang_detail($parameter['gudang_asal'])['response_data'][0];
        $Tujuan = self::get_gudang_detail($parameter['gudang_tujuan'])['response_data'][0];
        $StockIn = 0;
        $StockOut = 0;


        /*$stok_dari_old = self::$query->select('inventori_stok', array(
            'stok_terkini'
        ))
            ->where(array(
                'inventori_stok.barang' => '= ?',
                'AND',
                'inventori_stok.batch' => '= ?',
                'AND',
                'inventori_stok.gudang' => '= ?'
            ), array(
                $parameter['barang'],
                $parameter['batch'],
                $parameter['gudang_asal']
            ))
            ->execute();

        $update_stok_old_dari = self::$query->update('inventori_stok', array(
            'stok_terkini' => floatval($stok_dari_old['response_data'][0]['stok_terkini']) - floatval($parameter['qty'])
        ))
            ->where(array(
                'inventori_stok.barang' => '= ?',
                'AND',
                'inventori_stok.batch' => '= ?',
                'AND',
                'inventori_stok.gudang' => '= ?'
            ), array(
                $parameter['barang'],
                $parameter['batch'],
                $parameter['gudang_asal']
            ))
            ->execute();

        if ($update_stok_old_dari['response_result'] > 0) {
            //Update Stok Log Dari
            $update_dari_log = self::$query->insert('inventori_stok_log', array(
                'barang' => $parameter['barang'],
                'batch' => $parameter['batch'],
                'uid_foreign' => $parameter['transact_iden'],
                'jenis_transaksi' => $parameter['transact_table'],
                'gudang' => $parameter['gudang_asal'],
                'masuk' => 0,
                'keluar' => floatval($parameter['qty']),
                'saldo' => floatval($stok_dari_old['response_data'][0]['stok_terkini']) - floatval($parameter['qty']),
                'type' => __STATUS_TEMPORARY_STOK_OUT__,
                'keterangan' => $parameter['remark']
            ))
                ->returning('id')
                ->execute();
            $StokOut = $update_dari_log['response_unique'];
        }

        if((isset($parameter['gudang_tujuan']))) {
            $stok_ke_old = self::$query->select('inventori_stok', array(
                'stok_terkini'
            ))
                ->where(array(
                    'inventori_stok.barang' => '= ?',
                    'AND',
                    'inventori_stok.batch' => '= ?',
                    'AND',
                    'inventori_stok.gudang' => '= ?'
                ), array(
                    $parameter['barang'],
                    $parameter['batch'],
                    $parameter['gudang_tujuan']
                ))
                ->execute();

            if (count($stok_ke_old['response_data']) > 0) {
                $update_stok_old_ke = self::$query->update('inventori_stok', array(
                    'stok_terkini' => floatval($stok_ke_old['response_data'][0]['stok_terkini']) + floatval($parameter['qty'])
                ))
                    ->where(array(
                        'inventori_stok.barang' => '= ?',
                        'AND',
                        'inventori_stok.batch' => '= ?',
                        'AND',
                        'inventori_stok.gudang' => '= ?'
                    ), array(
                        $parameter['barang'],
                        $parameter['batch'],
                        $parameter['gudang_tujuan']
                    ))
                    ->execute();
            } else {
                $update_stok_old_ke = self::$query->insert('inventori_stok', array(
                    'stok_terkini' => floatval($parameter['qty']),
                    'barang' => $parameter['barang'],
                    'batch' => $parameter['batch'],
                    'gudang' => $parameter['gudang_tujuan']
                ))
                    ->execute();
            }

            if ($update_stok_old_ke['response_result'] > 0) {
                //Update Stok Log Ke
                $update_ke_log = self::$query->insert('inventori_stok_log', array(
                    'barang' => $parameter['barang'],
                    'batch' => $parameter['batch'],
                    'uid_foreign' => $parameter['transact_iden'],
                    'jenis_transaksi' => $parameter['transact_table'],
                    'gudang' => $parameter['gudang_tujuan'],
                    'masuk' => floatval($parameter['qty']),
                    'keluar' => 0,
                    'saldo' => floatval($stok_ke_old['response_data'][0]['stok_terkini']) + floatval($parameter['qty']),
                    'type' => __STATUS_TEMPORARY_STOK_IN__,
                    'keterangan' => $parameter['remark']
                ))
                    ->execute();
                $StockIn = $update_ke_log['response_unique'];
            }
        }*/

        if(isset($parameter['gudang_tujuan'])) {
            $worker = self::$query->insert('inventori_temp_stok', array(
                'transact_table' => $parameter['transact_table'],
                'transact_iden' => $parameter['transact_iden'],
                'gudang_asal' => $parameter['gudang_asal'],
                'gudang_tujuan' => $parameter['gudang_tujuan'],
                'barang' => $parameter['barang'],
                'batch' => $parameter['batch'],
                'qty' => $parameter['qty'],
                'status' => 'P',
                'remark' => $parameter['remark'],
                'logged_at' => parent::format_date(),
                'pegawai' => $UserData['data']->uid,
                'stok_log_in' => $StockIn,
                'stok_log_out' => $StockOut
            ))
                ->execute();
        } else {
            $worker = self::$query->insert('inventori_temp_stok', array(
                'transact_table' => $parameter['transact_table'],
                'transact_iden' => $parameter['transact_iden'],
                'gudang_asal' => $parameter['gudang_asal'],
                'barang' => $parameter['barang'],
                'batch' => $parameter['batch'],
                'qty' => $parameter['qty'],
                'status' => 'P',
                'remark' => $parameter['remark'],
                'logged_at' => parent::format_date(),
                'pegawai' => $UserData['data']->uid,
                'stok_log_in' => $StockIn,
                'stok_log_out' => $StockOut
            ))
                ->execute();
        }
        return $worker;
    }

    public function tambah_mutasi($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $mutasiDetailRecorded = array();

        //Get Last AI tahun ini
        $lastID = self::$query->select('inventori_mutasi', array(
            'uid'
        ))
            ->where(array(
                'EXTRACT(year FROM inventori_mutasi.created_at)' => '= ?'
            ), array(
                date('Y')
            ))
            ->execute();
        $uid = parent::gen_uuid();
        $worker = self::$query->insert('inventori_mutasi', array(
            'uid' => $uid,
            'kode' => str_pad(count($lastID['response_data']) + 1, 5, '0', STR_PAD_LEFT) . '/' . $UserData['data']->unit_kode . '/MUT/' . date('m') . '/' . date('Y'),
            'tanggal' => parent::format_date(),
            'dari' => $parameter['dari'],
            'ke' => $parameter['ke'],
            'keterangan' => $parameter['keterangan'],
            'pegawai' => $UserData['data']->uid,
            'mut_resep_pasien' => (isset($parameter['mut_resep_pasien'])) ? $parameter['mut_resep_pasien'] : '-',
            'status' => (isset($parameter['status']) && !empty($parameter['status'])) ? $parameter['status'] : 'N',
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
                    $uid,
                    $UserData['data']->uid,
                    'inventori_mutasi',
                    'I',
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class' => __CLASS__
            ));

            foreach ($parameter['item'] as $key => $value) {
                if (floatval($value['mutasi']) > 0) {
                    $ItemUIDBatch = explode('|', $key);

                    $mutasi_detail = self::$query->insert('inventori_mutasi_detail', array(
                        'mutasi' => $uid,
                        'item' => $ItemUIDBatch[0],
                        'batch' => $ItemUIDBatch[1],
                        'qty' => $value['mutasi'],
                        'keterangan' => $value['keterangan'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->returning('id')
                        ->execute();

                    array_push($mutasiDetailRecorded, $mutasi_detail);


                    if ($mutasi_detail['response_result'] > 0) {
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
                                $mutasi_detail['response_unique'],
                                $UserData['data']->uid,
                                'inventori_mutasi_detail',
                                'I',
                                parent::format_date(),
                                'N',
                                $UserData['data']->log_id
                            ),
                            'class' => __CLASS__
                        ));

                        //Update Stok dan Stok Log
                        //Recent Stok
                        //Update Stok Asal

                        //Temporary Stok
                        $TempStock = self::$query->insert('inventori_temp_stok', array(
                            'transact_table' => 'inventori_mutasi',
                            'transact_iden' => $uid,
                            'gudang_asal' => $parameter['dari'],
                            'gudang_tujuan' => $parameter['ke'],
                            'barang' => $ItemUIDBatch[0],
                            'batch' => $ItemUIDBatch[1],
                            'qty' => $value['mutasi'],
                            'status' => 'P',
                            'remark' => $parameter['keterangan'],
                            'logged_at' => parent::format_date(),
                            'pegawai' => $UserData['data']->uid
                        ))
                            ->execute();



                        if(isset($parameter['apotek_order'])) {
                            $stok_dari_old = self::$query->select('inventori_stok', array(
                                'stok_terkini'
                            ))
                                ->where(array(
                                    'inventori_stok.barang' => '= ?',
                                    'AND',
                                    'inventori_stok.batch' => '= ?',
                                    'AND',
                                    'inventori_stok.gudang' => '= ?'
                                ), array(
                                    $ItemUIDBatch[0],
                                    $ItemUIDBatch[1],
                                    $parameter['dari']
                                ))
                                ->execute();

                            $update_stok_old_dari = self::$query->update('inventori_stok', array(
                                'stok_terkini' => floatval($stok_dari_old['response_data'][0]['stok_terkini']) - floatval($value['mutasi'])
                            ))
                                ->where(array(
                                    'inventori_stok.barang' => '= ?',
                                    'AND',
                                    'inventori_stok.batch' => '= ?',
                                    'AND',
                                    'inventori_stok.gudang' => '= ?'
                                ), array(
                                    $ItemUIDBatch[0],
                                    $ItemUIDBatch[1],
                                    $parameter['dari']
                                ))
                                ->execute();

                            if ($update_stok_old_dari['response_result'] > 0) {
                                //Update Stok Log Dari
                                $update_dari_log = self::$query->insert('inventori_stok_log', array(
                                    'barang' => $ItemUIDBatch[0],
                                    'batch' => $ItemUIDBatch[1],
                                    'uid_foreign' => $uid,
                                    'jenis_transaksi' => 'inventori_mutasi',
                                    'gudang' => $parameter['dari'],
                                    'masuk' => 0,
                                    'keluar' => floatval($value['mutasi']),
                                    'saldo' => floatval($stok_dari_old['response_data'][0]['stok_terkini']) - floatval($value['mutasi']),
                                    'type' => (isset($parameter['special_code_out'])) ? $parameter['special_code'] : __STATUS_MUTASI_STOK__,
                                    'keterangan' => $parameter['keterangan']
                                ))
                                    ->execute();
                            }







                            //Update Stok Tujuan
                            $stok_ke_old = self::$query->select('inventori_stok', array(
                                'stok_terkini'
                            ))
                                ->where(array(
                                    'inventori_stok.barang' => '= ?',
                                    'AND',
                                    'inventori_stok.batch' => '= ?',
                                    'AND',
                                    'inventori_stok.gudang' => '= ?'
                                ), array(
                                    $ItemUIDBatch[0],
                                    $ItemUIDBatch[1],
                                    $parameter['ke']
                                ))
                                ->execute();

                            if (count($stok_ke_old['response_data']) > 0) {
                                $update_stok_old_ke = self::$query->update('inventori_stok', array(
                                    'stok_terkini' => floatval($stok_ke_old['response_data'][0]['stok_terkini']) + floatval($value['mutasi'])
                                ))
                                    ->where(array(
                                        'inventori_stok.barang' => '= ?',
                                        'AND',
                                        'inventori_stok.batch' => '= ?',
                                        'AND',
                                        'inventori_stok.gudang' => '= ?'
                                    ), array(
                                        $ItemUIDBatch[0],
                                        $ItemUIDBatch[1],
                                        $parameter['ke']
                                    ))
                                    ->execute();
                            } else {
                                $update_stok_old_ke = self::$query->insert('inventori_stok', array(
                                    'stok_terkini' => floatval($value['mutasi']),
                                    'barang' => $ItemUIDBatch[0],
                                    'batch' => $ItemUIDBatch[1],
                                    'gudang' => $parameter['ke']
                                ))
                                    ->execute();
                            }

                            if ($update_stok_old_ke['response_result'] > 0) {
                                //Update Stok Log Ke
                                $update_ke_log = self::$query->insert('inventori_stok_log', array(
                                    'barang' => $ItemUIDBatch[0],
                                    'batch' => $ItemUIDBatch[1],
                                    'uid_foreign' => $uid,
                                    'jenis_transaksi' => 'inventori_mutasi',
                                    'gudang' => $parameter['ke'],
                                    'masuk' => floatval($value['mutasi']),
                                    'keluar' => 0,
                                    'saldo' => floatval($stok_ke_old['response_data'][0]['stok_terkini']) + floatval($value['mutasi']),
                                    'type' => (isset($parameter['special_code_in'])) ? $parameter['special_code_in'] : __STATUS_MUTASI_STOK__,
                                    'keterangan' => $parameter['keterangan']
                                ))
                                    ->execute();
                            }
                        }
                        /**/
                    }
                } else {
                    array_push($mutasiDetailRecorded, $value[$key]['mutasi']);
                }
            }
        }
        $worker['parameter_detail'] = $parameter['item'];
        $worker['response_unique'] = $uid;
        $worker['detail'] = $mutasiDetailRecorded;
        return $worker;
    }

    private function master_inv_import_fetch($parameter)
    {
        if (!empty($_FILES['csv_file']['name'])) {
            $unique_name = array();

            $file_data = fopen($_FILES['csv_file']['tmp_name'], 'r');
            $column = fgetcsv($file_data); //array_head
            $row_data = array();
            while ($row = fgetcsv($file_data)) {
                if (!in_array($row[0], $unique_name)) {
                    array_push($unique_name, $row[0]);
                    $column_builder = array();
                    foreach ($column as $key => $value) {
                        $column_builder[$value] = $row[$key];
                    }
                    array_push($row_data, $column_builder);
                }
            }

            $build_col = array();
            foreach ($column as $key => $value) {
                array_push($build_col, array("data" => $value));
            }

            $output = array(
                'column' => $column,
                'row_data' => $row_data,
                'column_builder' => $build_col
            );
            return $output;
        }
    }

    private function proceed_import_master_inv($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $duplicate_row = array();
        $non_active = array();
        $failed_data = array();
        $success_proceed = 0;
        $proceed_data = array();

        // $hapusUlangInv = self::$query->delete('master_inv')
        //     ->execute();

        $kandungan_resep = self::$query->delete('master_inv_obat_kandungan')
                ->execute();

        foreach ($parameter['data_import'] as $key => $value) {

            //Check Ketersediaan Satuan
            $satuan_check = self::$query->select('master_inv_satuan', array(
                'uid',
                'deleted_at'
            ))
                ->where(array(
                    'master_inv_satuan.nama' => '= ?'
                ), array(
                    strtoupper($value['satuan'])
                ))
                ->limit(1)
                ->execute();
            if(count($satuan_check['response_data']) > 0) {
                $targetSatuan = $satuan_check['response_data'][0]['uid'];
                foreach($satuan_check['response_data'] as $SatuanKey => $SatuanValue) {
                    if($SatuanValue['deleted_at'] != '') {
                        //Aktifkan kembali satuan
                        $activateSatuan = self::$query->update('master_inv_satuan', array(
                            'deleted_at' => NULL
                        ))
                            ->where(array(
                                'master_inv_satuan.uid' => '= ?'
                            ), array(
                                $SatuanValue['uid']
                            ))
                            ->execute();
                        if($activateSatuan['response_result'] > 0) {
                            $log = parent::log(array(
                                'type' => 'activity',
                                'column' => array(
                                    'unique_target',
                                    'user_uid',
                                    'table_name',
                                    'action',
                                    'new_value',
                                    'logged_at',
                                    'status',
                                    'login_id'
                                ),
                                'value' => array(
                                    $SatuanValue['uid'],
                                    $UserData['data']->uid,
                                    'master_inv_satuan',
                                    'U',
                                    'aktifkan kembali',
                                    parent::format_date(),
                                    'N',
                                    $UserData['data']->log_id
                                ),
                                'class' => __CLASS__
                            ));
                        }
                    }
                }

            } else {
                $targetSatuan = parent::gen_uuid();
                // Satuan Baru
                $new_satuan = self::$query->insert('master_inv_satuan', array(
                    'uid' => $targetSatuan,
                    'nama' => strtoupper($value['satuan']),
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
                if($new_satuan['response_result'] > 0) {
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
                            $targetSatuan,
                            $UserData['data']->uid,
                            'master_inv_satuan',
                            'I',
                            parent::format_date(),
                            'N',
                            $UserData['data']->log_id
                        ),
                        'class' => __CLASS__
                    ));
                }
            }

            //Check Ketersediaan Kategori
            $kategori_check = self::$query->select('master_inv_kategori', array(
                'uid',
                'deleted_at'
            ))
                ->where(array(
                    'master_inv_kategori.nama' => '= ?'
                ), array(
                    strtoupper($value['kategori'])
                ))
                ->limit(1)
                ->execute();
            if(count($kategori_check['response_data']) > 0) {
                $targetKategori = $kategori_check['response_data'][0]['uid'];
                foreach($kategori_check['response_data'] as $KategoriKey => $KategoriValue) {
                    if($KategoriValue['deleted_at'] != '') {
                        //Aktifkan kembali kategori
                        $activateKategori = self::$query->update('master_inv_kategori', array(
                            'deleted_at' => NULL
                        ))
                            ->where(array(
                                'master_inv_kategori.uid' => '= ?'
                            ), array(
                                $KategoriValue['uid']
                            ))
                            ->execute();
                        if($activateKategori['response_result'] > 0) {
                            $log = parent::log(array(
                                'type' => 'activity',
                                'column' => array(
                                    'unique_target',
                                    'user_uid',
                                    'table_name',
                                    'action',
                                    'new_value',
                                    'logged_at',
                                    'status',
                                    'login_id'
                                ),
                                'value' => array(
                                    $KategoriValue['uid'],
                                    $UserData['data']->uid,
                                    'master_inv_kategori',
                                    'U',
                                    'aktifkan kembali',
                                    parent::format_date(),
                                    'N',
                                    $UserData['data']->log_id
                                ),
                                'class' => __CLASS__
                            ));
                        }
                    }
                }
            } else {
                $targetKategori = parent::gen_uuid();
                // Kategori Baru
                $new_kategori = self::$query->insert('master_inv_kategori', array(
                    'uid' => $targetKategori,
                    'nama' => strtoupper($value['kategori']),
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
                if($new_kategori['response_result'] > 0) {
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
                            $targetKategori,
                            $UserData['data']->uid,
                            'master_inv_kategori',
                            'I',
                            parent::format_date(),
                            'N',
                            $UserData['data']->log_id
                        ),
                        'class' => __CLASS__
                    ));
                }
            }














            //Check duplicate
            $check = self::$query->select('master_inv', array(
                'uid',
                'nama',
                'deleted_at'
            ))
                ->where(array(
                    'master_inv.nama' => 'ILIKE ' . '\'' . strtoupper(trim($parameter['search']['value'])) . '%\''
                ), array(
                    $value['nama']
                ))
                ->execute();
            if (count($check['response_data']) > 0) {
                foreach ($check['response_data'] as $CheckKey => $CheckValue) {
                    $ReActivate = self::$query->update('master_inv', array(
                        'updated_at' => parent::format_date(),
                        'deleted_at' => NULL
                    ))
                        ->where(array(
                            'master_inv.uid' => '= ?'
                        ), array(
                            $CheckValue['uid']
                        ))
                        ->execute();

                    $kandungan_worker = self::$query->insert('master_inv_obat_kandungan', array(
                            'uid_obat' => $CheckValue['uid'],
                            'kandungan' => $value['nama_generik'],
                            'keterangan' => $value['nama_generik'],
                            'created_at' => parent::format_date(),
                            'updated_at' => parent::format_date()
                        ))
                            ->execute();
                        
                    if(!empty($CheckValue['deleted_at'])) {
                        array_push($non_active, $CheckValue);
                    } else {
                        array_push($duplicate_row, $CheckValue);
                    }
                }
            } else { //Item Unik
                


                $newItemUID = parent::gen_uuid();
                $newItem = self::$query->insert('master_inv', array(
                    'uid' => $newItemUID,
                    'nama' => strtoupper(trim($value['nama'])),
                    'kategori' => $targetKategori,
                    'satuan_terkecil' => $targetSatuan,
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
                if($newItem['response_result'] > 0) {
                    array_push($proceed_data, $newItem);
                } else {
                    $value['process'] = $newItem;
                    array_push($failed_data, $value);
                }

                if($newItem['response_result'] > 0) {
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
                            $newItemUID,
                            $UserData['data']->uid,
                            'master_inv',
                            'I',
                            parent::format_date(),
                            'N',
                            $UserData['data']->log_id
                        ),
                        'class' => __CLASS__
                    ));

                    //New Kandungan Obat
                    $kandungan_worker = self::$query->insert('master_inv_obat_kandungan', array(
                        'uid_obat' => $newItemUID,
                        'kandungan' => $value['nama_generik'],
                        'keterangan' => $value['nama_generik'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();

                    $success_proceed += 1;

                    if($parameter['super'] == 'farmasi') {
                        $kategori_obat = array();
                        if (substr($value['antibiotik'], 0, 4) !== 'NON-' || substr($value['antibiotik'], 0, 1) !== 'N') {
                            array_push($kategori_obat, __UID_ANTIBIOTIK__);
                        }

                        if (substr($value['fornas'], 0, 4) !== 'NON-' || substr($value['fornas'], 0, 1) !== 'N') {
                            array_push($kategori_obat, __UID_FORNAS__);
                        }

                        if (substr($value['narkotika'], 0, 4) !== 'NON-' || substr($value['narkotika'], 0, 1) !== 'N') {
                            array_push($kategori_obat, __UID_NARKOTIKA__);
                        }

                        if (substr($value['psikotropika'], 0, 4) !== 'NON-' || substr($value['psikotropika'], 0, 1) !== 'N') {
                            array_push($kategori_obat, __UID_PSIKOTROPIKA__);
                        }

                        if ($value['generik'] !== '') {
                            array_push($kategori_obat, __UID_GENERIK__);
                        }

                        foreach ($kategori_obat as $KatObatKey => $KatObatValue) {
                            $proses_kategori_obat = self::$query->insert('master_inv_obat_kategori_item', array(
                                'obat' => $newItemUID,
                                'kategori' => $KatObatValue,
                                'created_at' => parent::format_date(),
                                'updated_at' => parent::format_date()
                            ))
                                ->execute();
                        }
                    }
                }
            }
        }

        return array(
            'duplicate_row' => $duplicate_row,
            'non_active' => $non_active,
            'success_proceed' => $success_proceed,
            'failed_data' => $failed_data,
            'data' => $parameter['data_import'],
            'proceed' => $proceed_data
        );
    }


    private function get_item_back_end($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'master_inv.deleted_at' => 'IS NULL',
                'AND',
                'master_inv.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array();
        } else {
            $paramData = array(
                'master_inv.deleted_at' => 'IS NULL'
            );

            $paramValue = array();
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('master_inv', array(
                'uid',
                'kode_barang',
                'nama',
                'kategori',
                'satuan_terkecil',
                'manufacture',
                'created_at',
                'updated_at'
            ))
                ->order(array(
                    'updated_at' => 'DESC'
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('master_inv', array(
                'uid',
                'kode_barang',
                'nama',
                'kategori',
                'satuan_terkecil',
                'manufacture',
                'created_at',
                'updated_at'
            ))
                ->order(array(
                    'updated_at' => 'DESC'
                ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $PenjaminObat = new Penjamin(self::$pdo);
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {


            $data['response_data'][$key]['autonum'] = $autonum;
            if(file_exists('../images/produk/' . $value['uid'] . '.png')) {
                $data['response_data'][$key]['image'] = 'images/produk/' . $value['uid'] . '.png?d=' . date('H:i:s');
            } else {
                $data['response_data'][$key]['image'] = 'images/product.png';
            }

            $kategori_obat = self::get_kategori_obat_item($value['uid']);
            foreach ($kategori_obat as $KOKey => $KOValue) {
                $kategori_obat[$KOKey]['kategori'] = self::get_kategori_obat_detail($KOValue['kategori'])['response_data'][0]['nama'];
            }

            $data['response_data'][$key]['kategori_obat'] = $kategori_obat;
            $data['response_data'][$key]['satuan_terkecil'] = self::get_satuan_detail($value['satuan_terkecil'])['response_data'][0];
            $data['response_data'][$key]['kategori'] = self::get_kategori_detail($value['kategori'])['response_data'][0];
            $data['response_data'][$key]['manufacture'] = self::get_manufacture_detail($value['manufacture'])['response_data'][0];

            //Data Penjamin
            $ListPenjaminObat = $PenjaminObat->get_penjamin_obat($value['uid'])['response_data'];
            foreach ($ListPenjaminObat as $PenjaminKey => $PenjaminValue) {
                $ListPenjaminObat[$PenjaminKey]['profit'] = floatval($PenjaminValue['profit']);
            }
            $data['response_data'][$key]['penjamin'] = $ListPenjaminObat;

            //Cek Ketersediaan Stok
            $TotalStock = 0;
            $InventoriStockPopulator = self::get_item_batch($value['uid']);
            if (count($InventoriStockPopulator['response_data']) > 0) {
                foreach ($InventoriStockPopulator['response_data'] as $TotalKey => $TotalValue) {
                    $TotalStock += floatval($TotalValue['stok_terkini']);
                }
                $data['response_data'][$key]['stok'] = $TotalStock;
                $data['response_data'][$key]['batch'] = $InventoriStockPopulator['response_data'];
            } else {
                $data['response_data'][$key]['stok'] = 0;
            }

            $autonum++;
        }

        $itemTotal = self::$query->select('master_inv', array(
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

    private function get_stok_back_end($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'inventori_stok.gudang' => '= ?',
                'AND',
                'master_inv.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array($UserData['data']->gudang);
        } else {
            $paramData = array(
                'inventori_stok.gudang' => '= ?'
            );

            $paramValue = array($UserData['data']->gudang);
        }

        $data = array(
            'response_data' => array()
        );

        if ($parameter['length'] < 0) {
            $qString = '';
            $query = self::$pdo->prepare('SELECT DISTINCT(barang) as barang, master_inv.nama FROM inventori_stok JOIN master_inv ON inventori_stok.barang = master_inv.uid WHERE inventori_stok.gudang = ? AND master_inv.nama ILIKE \'%' . $parameter['search']['value'] . '%\' ORDER BY master_inv.nama ASC');
            $query->execute(array($UserData['data']->gudang));
            $data['response_data'] = $query->fetchAll(\PDO::FETCH_ASSOC);
            // $data = self::$query->select('inventori_stok', array(
            //     'id',
            //     'barang',
            //     'batch',
            //     'gudang',
            //     'stok_terkini'
            // ))
            //     ->join('master_inv', array(
            //         'nama'
            //     ))
            //     ->on(array(
            //         array('inventori_stok.barang', '=', 'master_inv.uid')
            //     ))
            //     ->where($paramData, $paramValue)
            //     ->execute();
        } else {
            $query = self::$pdo->prepare('SELECT DISTINCT(barang) as barang, master_inv.nama FROM inventori_stok JOIN master_inv ON inventori_stok.barang = master_inv.uid WHERE inventori_stok.gudang = ? AND master_inv.nama ILIKE \'%' . $parameter['search']['value'] . '%\' ORDER BY master_inv.nama ASC OFFSET ' . intval($parameter['start']) . ' LIMIT ' . intval($parameter['length']));
            $query->execute(array($UserData['data']->gudang));
            $data['response_data'] = $query->fetchAll(\PDO::FETCH_ASSOC);
            // $data = self::$query->select('inventori_stok', array(
            //     'id',
            //     'barang',
            //     'batch',
            //     'gudang',
            //     'stok_terkini'
            // ))
            //     ->join('master_inv', array(
            //         'nama'
            //     ))
            //     ->on(array(
            //         array('inventori_stok.barang', '=', 'master_inv.uid')
            //     ))
            //     ->offset(intval($parameter['start']))
            //     ->limit(intval($parameter['length']))
            //     ->where($paramData, $paramValue)
            //     ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        $PenjaminObat = new Penjamin(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;

            $StokTerkini = 0;
            $CountStok = self::$query->select('inventori_stok', array(
                'stok_terkini'
            ))
                ->where(array(
                    'inventori_stok.barang' => '= ?',
                    'AND',
                    'inventori_stok.gudang' => '= ?'
                ), array(
                    $value['barang'],
                    $UserData['data']->gudang
                ))
                ->execute();
            foreach($CountStok['response_data'] as $SKey => $SValue) {
                $StokTerkini += floatval($SValue['stok_terkini']);
            }

            $data['response_data'][$key]['stok_terkini'] = $StokTerkini;
            
            $ItemDetail = self::get_item_info($value['barang'])['response_data'][0];
            //$ItemDetail = self::get_item_detail($value['barang'])['response_data'][0];
            $data['response_data'][$key]['detail'] = $ItemDetail;

            if(file_exists('../images/produk/' . $value['barang'] . '.png')) {
                $data['response_data'][$key]['image'] = 'images/produk/' . $value['barang'] . '.png';
            } else {
                $data['response_data'][$key]['image'] = 'images/product.png';
            }

            $kategori_obat = self::get_kategori_obat_item($value['barang']);
            foreach ($kategori_obat as $KOKey => $KOValue) {
                $kategori_obat[$KOKey]['kategori'] = self::get_kategori_obat_detail($KOValue['kategori'])['response_data'][0]['nama'];
            }

            $data['response_data'][$key]['kategori_obat'] = $kategori_obat;
            $data['response_data'][$key]['satuan_terkecil'] = self::get_satuan_detail($value['satuan_terkecil'])['response_data'][0];
            $data['response_data'][$key]['kategori'] = self::get_kategori_detail($value['kategori'])['response_data'][0];
            $data['response_data'][$key]['manufacture'] = self::get_manufacture_detail($value['manufacture'])['response_data'][0];

            //Data Penjamin
            $ListPenjaminObat = $PenjaminObat->get_penjamin_obat($value['barang'])['response_data'];
            foreach ($ListPenjaminObat as $PenjaminKey => $PenjaminValue) {
                $ListPenjaminObat[$PenjaminKey]['profit'] = floatval($PenjaminValue['profit']);
            }
            $data['response_data'][$key]['penjamin'] = $ListPenjaminObat;

            //Cek Ketersediaan Stok
            // $TotalStock = 0;
            // $InventoriStockPopulator = self::get_item_batch($value['barang']);
            // if (count($InventoriStockPopulator['response_data']) > 0) {
            //     foreach ($InventoriStockPopulator['response_data'] as $TotalKey => $TotalValue) {
            //         if($TotalValue['gudang'] === $UserData['data']->gudang) {
            //             //Sini
            //             $TotalStock += floatval($TotalValue['stok_terkini']);
            //         }
            //     }
            //     //$data['response_data'][$key]['stok'] = $TotalStock;
            //     $data['response_data'][$key]['stok'] = $value['stok_terkini'];
            //     $data['response_data'][$key]['batch'] = $InventoriStockPopulator['response_data'];
            // } else {
            //     $data['response_data'][$key]['stok'] = 0;
            // }
            // $data['response_data'][$key]['batch_info'] = $InventoriStockPopulator;

            //Data reserved
            $tempIn = 0;
            $tempOut = 0;
            $Reserved = self::$query->select('inventori_temp_stok', array(
                'qty', 'gudang_asal', 'gudang_tujuan'
            ))
                ->where(array(
                    '(inventori_temp_stok.gudang_asal' => '= ?',
                    'OR',
                    'inventori_temp_stok.gudang_tujuan' => '= ?)',
                    'AND',
                    'inventori_temp_stok.barang' => '= ?',
                    'AND',
                    'inventori_temp_stok.batch' => '= ?',
                    'AND',
                    'inventori_temp_stok.status' => '= ?'
                ), array(
                    $value['gudang'], $value['gudang'], $value['barang'], $value['batch'], 'P'
                ))
                ->execute();
            foreach ($Reserved['response_data'] as $TmpKey => $TmpValue) {
                if($TmpValue['gudang_asal'] === $value['gudang']) {
                    $tempIn += $TmpValue['qty'];
                } else if($TmpValue['gudang_tujuan'] === $value['gudang']) {
                    $tempOut += $TmpValue['qty'];
                }
            }
            $data['response_data'][$key]['in'] = $tempIn;
            $data['response_data'][$key]['out'] = $tempOut;
            $data['response_data'][$key]['reserved'] = $Reserved['response_data'];

            $autonum++;
        }

        $totalItem = self::$pdo->prepare('SELECT DISTINCT(barang) as barang, master_inv.nama FROM inventori_stok JOIN master_inv ON inventori_stok.barang = master_inv.uid WHERE inventori_stok.gudang = ?');
        $totalItem->execute(array($UserData['data']->gudang));
        $countTotal = $totalItem->fetchAll(\PDO::FETCH_ASSOC);

        $itemTotal = self::$query->select('inventori_stok', array(
            'id'
        ))
            ->join('master_inv', array(
                'nama'
            ))
            ->on(array(
                array('inventori_stok.barang', '=', 'master_inv.uid')
            ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($countTotal);
        $data['recordsFiltered'] = count($countTotal);
        $data['length'] = intval($parameter['length']);
        $data['gudang_saya'] = $UserData['data']->gudang;
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function satu_harga_profit($parameter) {

        $Penjamin = new Penjamin(self::$pdo);
        $PenjaminData = $Penjamin::get_penjamin();
        $proceedData = array();
        $worker = self::$query->select('master_inv', array(
            'uid'
        ))
            ->where(array(
                'master_inv.deleted_at' => 'IS NULL'
            ))
            ->execute();

        foreach ($worker['response_data'] as $key => $value)
        {
            foreach ($PenjaminData['response_data'] as $PKey => $PValue)
            {
                //Check
                $Check = self::$query->select('master_inv_harga', array(
                    'id'
                ))
                    ->where(array(
                        'master_inv_harga.barang' => '= ?',
                        'AND',
                        'master_inv_harga.penjamin' => '= ?',
                        'AND',
                        'master_inv_harga.deleted_at' => 'IS NULL'
                    ), array(
                        $value['uid'],
                        $PValue['uid']
                    ))
                    ->execute();

                if(count($Check['response_data']) > 0)
                {
                    $proceedDataAction = self::$query->update('master_inv_harga', array(
                        'profit' => floatval($parameter['profit']),
                        'profit_type' => $parameter['profit_type']
                    ))
                        ->where(array(
                            'master_inv_harga.barang' => '= ?',
                            'AND',
                            'master_inv_harga.penjamin' => '= ?',
                            'AND',
                            'master_inv_harga.deleted_at' => 'IS NULL'
                        ), array(
                            $value['uid'],
                            $PValue['uid']
                        ))
                        ->execute();
                } else
                {
                    $proceedDataAction = self::$query->insert('master_inv_harga', array(
                        'barang' => $value['uid'],
                        'penjamin' => $PValue['uid'],
                        'profit' => floatval($parameter['profit']),
                        'profit_type' => $parameter['profit_type'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                }

                array_push($proceedData, $proceedDataAction);
            }
        }

        return $proceedData;
    }

    private function get_mutasi_detail($parameter) {
        $data = self::$query->select('inventori_mutasi', array(
            'tanggal',
            'dari',
            'ke',
            'pegawai',
            'kode'
        ))
            ->where(array(
                'inventori_mutasi.deleted_at' => 'IS NULL',
                'AND',
                'inventori_mutasi.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['detail'] = self::get_mutasi_item($parameter);
        }
        return $data;

    }


    private function get_mutasi_item($parameter) {
        $data = self::$query->select('inventori_mutasi_detail', array(
            'item',
            'batch',
            'qty',
            'keterangan',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'inventori_mutasi_detail.mutasi' => '= ?',
                'AND',
                'inventori_mutasi_detail.deleted_at' => 'IS NULL'
            ), array(
                $parameter
            ))
            ->execute();
        foreach ($data['response_data'] as $key => $value) {
            //Item Detail
            $Item = self::get_item_detail($value['item']);
            $data['response_data'][$key]['item'] = $Item['response_data'][0];

            //Batch Detail
            $Batch = self::get_batch_detail($value['batch']);
            $data['response_data'][$key]['batch'] = $Batch['response_data'][0];


        }

        return $data;
    }


    private function get_mutasi_request($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        /*$Unit = new Unit(self::$pdo);
        $UnitCheck = $Unit->get_unit_detail($UserData['data']->unit)['response_data'][0];*/

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'inventori_mutasi.deleted_at' => 'IS NULL',
                'AND',
                '(inventori_mutasi.dari' => '= ?',
                'OR',
                'inventori_mutasi.ke' => '= ?)',
                'AND',
                '(pegawai.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'inventori_mutasi.kode' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
            );

            $paramValue = array($UserData['data']->gudang, $UserData['data']->gudang);
        } else {
            $paramData = array(
                'inventori_mutasi.deleted_at' => 'IS NULL',
                'AND',
                '(inventori_mutasi.dari' => '= ?',
                'OR',
                'inventori_mutasi.ke' => '= ?)'
            );

            $paramValue = array($UserData['data']->gudang, $UserData['data']->gudang);
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('inventori_mutasi', array(
                'uid',
                'kode',
                'tanggal',
                'dari',
                'ke',
                'pegawai',
                'keterangan',
                'status',
                'diproses_oleh',
                'mut_resep_pasien',
                'created_at',
                'updated_at'
            ))
                ->join('pegawai', array(
                    'nama',
                    'unit'
                ))
                ->on(array(
                    array('inventori_mutasi.pegawai', '=', 'pegawai.uid')
                ))
                ->where($paramData, $paramValue)
                ->order(array(
                    'inventori_mutasi.updated_at' => 'DESC'
                ))
                ->execute();
        } else {
            $data = self::$query->select('inventori_mutasi', array(
                'uid',
                'kode',
                'tanggal',
                'dari',
                'ke',
                'pegawai',
                'keterangan',
                'status',
                'diproses_oleh',
                'mut_resep_pasien',
                'created_at',
                'updated_at'
            ))
                ->join('pegawai', array(
                    'nama',
                    'unit'
                ))
                ->on(array(
                    array('inventori_mutasi.pegawai', '=', 'pegawai.uid')
                ))
                ->where($paramData, $paramValue)
                ->order(array(
                    'inventori_mutasi.updated_at' => 'DESC'
                ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $allData = array();
        $autonum = intval($parameter['start']) + 1;
        $Unit = new Unit(self::$pdo);
        $UnitCheck = $Unit->get_unit_detail($UserData['data']->unit)['response_data'][0];
        foreach ($data['response_data'] as $key => $value) {

            //Filter Unit yang sama
            if($value['dari'] === $UnitCheck['gudang'] || $value['ke'] === $UnitCheck['gudang'] || isset($parameter['inap'])) {
                $data['response_data'][$key]['autonum'] = $autonum;

                $data['response_data'][$key]['tanggal'] = date('d F Y', strtotime($value['tanggal']));

                //Gudang
                $dari = self::get_gudang_detail($value['dari']);
                $data['response_data'][$key]['dari'] = $dari['response_data'][0];

                $ke = self::get_gudang_detail($value['ke']);
                $data['response_data'][$key]['ke'] = $ke['response_data'][0];

                //Pegawai
                $pegawai = new Pegawai(self::$pdo);
                $data['response_data'][$key]['pegawai'] = $pegawai->get_detail($value['pegawai'])['response_data'][0];

                array_push($allData, $data['response_data'][$key]);
                $autonum++;
            }
        }

        $data['response_data'] = $allData;

        $itemTotal = self::$query->select('inventori_mutasi', array(
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

//===========================================================================================DELETE
    private function delete($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $worker = self::$query
            ->delete($parameter[6])
            ->where(array(
                $parameter[6] . '.uid' => '= ?'
            ), array(
                $parameter[7]
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
                    $parameter[7],
                    $UserData['data']->uid,
                    $parameter[6],
                    'D',
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class' => __CLASS__
            ));
        }
        return $worker;
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
}