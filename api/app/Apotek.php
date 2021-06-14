<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Antrian as Antrian;
use PondokCoder\Poli as Poli;
use PondokCoder\Inventori as Inventori;
use PondokCoder\Invoice as Invoice;
use PondokCoder\Utility as Utility;


class Apotek extends Utility
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
                case 'detail_resep':
                    return self::detail_resep($parameter[2]);
                    break;
                case 'detail_resep_lunas':
                    return self::detail_resep($parameter[2], 'L');
                    break;
                case 'detail_resep_verifikator':
                    return self::detail_resep_verifikator($parameter[2]);
                    break;
                case 'detail_resep_2':
                    return self::detail_resep_2($parameter[2]);
                    break;
                case 'lunas':
                    return self::get_resep('L');
                    break;
                case 'selesai':
                    return self::get_resep('D');
                    break;
                case 'panggil':
                    return self::get_resep('P');
                    break;
                case 'serah':
                    return self::get_resep('S');
                    break;
                default:
                    return self::get_resep();
            }
        } catch (QueryException $e) {
            return 'Error => ' . $e;
        }
    }

    public function __POST__($parameter = array())
    {
        try {
            switch ($parameter['request']) {
                case 'revisi_resep':
                    //return self::revisi_resep($parameter);
                    return array();
                    break;
                case 'verifikasi_resep':
                    return self::verifikasi_resep($parameter);
                    break;
                case 'verifikasi_resep_2':
                    return self::verifikasi_resep_2($parameter);
                    break;
                case 'get_resep_backend':
                    return self::get_resep_backend($parameter);
                    break;
                case 'get_resep_lunas_backend':
                    $parameter['status'] = 'L';
                    return self::get_resep_backend($parameter);
                    break;
                case 'resep_inap':
                    return self::resep_inap($parameter);
                    break;

                case 'get_resep_selesai_backend':
                    /*$parameter['status'] = 'D';
                    $selesai = self::get_resep_backend($parameter);

                    $parameter['status'] = 'P';
                    $panggil = self::get_resep_backend($parameter);

                    $parameter['status'] = 'S';
                    $terima = self::get_resep_backend($parameter);

                    $recordsTotal = $selesai['recordsTotal'] + $panggil['recordsTotal'] + $terima['recordsTotal'];
                    $recordsFiltered = $selesai['recordsFiltered'] + $panggil['recordsFiltered'] + $terima['recordsFiltered'];

                    $allData = array_merge(array_merge($terima['response_data'], $panggil['response_data']), $selesai['response_data']);
                    $autonum = 1;
                    foreach ($allData as $key => $value) {
                        $allData[$key]['autonum'] = $autonum;
                        $autonum++;
                    }

                    $terima['response_data'] = $allData;
                    $terima['recordsFiltered'] = $recordsFiltered;
                    $terima['recordsTotal'] = $recordsTotal;*/



                    return self::get_resep_serah_backend($parameter);

                    break;
                case 'get_resep_igd':
                    $parameter['status'] = 'L';
                    return self::get_resep_backend($parameter);
                    break;
                case 'proses_resep':
                    return self::proses_resep($parameter);
                    break;
                case 'panggil_antrian_selesai':
                    return self::panggil_antrian_selesai($parameter);
                    break;
                case 'serah_antrian_selesai':
                    return self::serah_antrian_selesai($parameter);
                    break;
                case 'detail_resep_verifikator_post':
                    return self::detail_resep_verifikator_post($parameter);
                    break;
                default:
                    return self::get_resep();
            }
        } catch (QueryException $e) {
            return 'Error => ' . $e;
        }
    }

    private function panggil_antrian_selesai($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);
        $worker = self::$query->update('resep', array(
            'status_resep' => 'P',
            'waktu_panggil' => parent::format_date(),
            'dipanggil_oleh' => $UserData['data']->uid
        ))
            ->where(array(
                'resep.uid' => '= ?',
                'AND',
                'resep.deleted_at' => 'IS NULL'
            ), array(
                $parameter['uid']
            ))
            ->execute();
        return $worker;
    }

    private function serah_antrian_selesai($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);
        $worker = self::$query->update('resep', array(
            'status_resep' => 'S',
            'waktu_terima' => parent::format_date(),
            'diserahkan_oleh' => $UserData['data']->uid
        ))
            ->where(array(
                'resep.uid' => '= ?',
                'AND',
                'resep.deleted_at' => 'IS NULL'
            ), array(
                $parameter['uid']
            ))
            ->execute();
        return $worker;
    }

    private function revisi_resep($parameter)
    {
        //
    }

    private function proses_resep($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);







        $usedBatch = array();
        $usedBatchInap = array();
        $rawBatch = array();
        $Inventori = new Inventori(self::$pdo);

        //Potong Stok
        $resepItem = self::$query->select('resep_detail', array(
            'obat',
            'qty',
            'penjamin'
        ))
            ->where(array(
                'resep_detail.resep' => '= ?',
                'AND',
                'resep_detail.deleted_at' => 'IS NULL'
            ), array(
                $parameter['resep']
            ))
            ->execute();
        foreach ($resepItem['response_data'] as $key => $value)
        {
            //Potong Batch terdekat
            $InventoriBatch = $Inventori->get_item_batch($value['obat']);

            $kebutuhan = floatval($value['qty']);

            //Batch terpotong
            foreach ($InventoriBatch['response_data'] as $bKey => $bValue)
            {
                if($bValue['gudang']['uid'] === $UserData['data']->gudang) //Ambil gudang dari user yang sedang login
                {
                    if($kebutuhan > $bValue['stok_terkini'])
                    {
                        if($parameter['departemen'] === __POLI_INAP__) {
                            //Racikan tidak usah charge stok karena stok dianggap habis diproses
                            array_push($usedBatchInap, array(
                                'batch' => $bValue['batch'],
                                'barang' => $value['obat'],
                                'gudang' => $bValue['gudang']['uid'],
                                'qty' => $bValue['stok_terkini']
                            ));
                        }
                        $kebutuhan -= $bValue['stok_terkini'];
                        array_push($usedBatch, array(
                            'batch' => $bValue['batch'],
                            'barang' => $value['obat'],
                            'gudang' => $bValue['gudang']['uid'],
                            'qty' => $bValue['stok_terkini']
                        ));
                    } else {
                        if($parameter['departemen'] === __POLI_INAP__) {
                            //Racikan tidak usah charge stok karena stok dianggap habis diproses
                            array_push($usedBatchInap, array(
                                'batch' => $bValue['batch'],
                                'barang' => $value['obat'],
                                'gudang' => $bValue['gudang']['uid'],
                                'qty' => $kebutuhan
                            ));
                        }
                        array_push($usedBatch, array(
                            'batch' => $bValue['batch'],
                            'barang' => $value['obat'],
                            'gudang' => $bValue['gudang']['uid'],
                            'qty' => $kebutuhan
                        ));
                    }
                }
            }
        }

        $racikan = self::$query->select('racikan', array(
            'uid',
            'qty'
        ))
            ->where(array(
                'racikan.asesmen' => '= ?',
                'AND',
                'racikan.status' => '= ?',
                'AND',
                'racikan.deleted_at' => 'IS NULL'
            ), array(
                $parameter['asesmen'],
                'L'
            ))
            ->execute();
        foreach ($racikan['response_data'] as $rKey => $rValue)
        {
            $racikanItem = self::$query->select('racikan_detail', array(
                'obat'
            ))
                ->where(array(
                    'racikan_detail.racikan' => '= ?',
                    'AND',
                    'racikan_detail.asesmen' => '= ?',
                    'AND',
                    'racikan_detail.deleted_at' => 'IS NULL'
                ), array(
                    $rValue['uid'],
                    $parameter['asesmen']
                ))
                ->execute();

            foreach ($racikanItem['response_data'] as $rIKey => $rIValue)
            {
                //Potong Batch terdekat
                $InventoriBatch = $Inventori->get_item_batch($rIValue['obat']);

                $kebutuhan = floatval($rValue['qty']);

                //Batch terpotong
                foreach ($InventoriBatch['response_data'] as $bKey => $bValue)
                {
                    if($bValue['gudang']['uid'] === $UserData['data']->gudang) //Ambil gudang dari user yang sedang login
                    {
                        if($kebutuhan > $bValue['stok_terkini'])
                        {
                            $kebutuhan -= $bValue['stok_terkini'];
                            if($bValue['stok_terkini'] > 0) {
                                if($bValue['stok_terkini'] > 0)
                                {
                                    array_push($usedBatch, array(
                                        'batch' => $bValue['batch'],
                                        'barang' => $rIValue['obat'],
                                        'gudang' => $bValue['gudang']['uid'],
                                        'qty' => $bValue['stok_terkini']
                                    ));
                                }
                            }
                        } else {
                            if($kebutuhan > 0) {
                                if($kebutuhan > 0)
                                {
                                    array_push($usedBatch, array(
                                        'batch' => $bValue['batch'],
                                        'barang' => $rIValue['obat'],
                                        'gudang' => $bValue['gudang']['uid'],
                                        'qty' => $kebutuhan
                                    ));
                                }
                            }
                        }
                    }
                }
            }
        }
        $itemMutasi = array();
        if($parameter['departemen'] === __POLI_INAP__) {
            //Todo: Mutasikan stok ke nurse station terkait

            if(count($usedBatchInap) > 0) {
                foreach ($usedBatchInap as $bKey => $bValue) {
                    if(!isset($itemMutasi[$bValue['barang'] . '|' . $bValue['batch']])) {
                        $itemMutasi[$bValue['barang'] . '|' . $bValue['batch']] = array(
                            'mutasi' => $bValue['qty'],
                            'keterangan' => 'Mutasi kebutuhan rawat inap'
                        );
                    }
                }

                //Ambil informasi nurse station dan gudang tujuan dari rawat inap
                $RawatInap = self::$query->select('rawat_inap', array(
                    'nurse_station',
                    'pasien',
                    'dokter'
                ))
                    ->join('nurse_station', array(
                        'kode as kode_ns',
                        'nama as nama_ns',
                        'unit'
                    ))
                    ->join('master_unit', array(
                        'kode as kode_unit',
                        'nama as nama_unit',
                        'gudang'
                    ))
                    ->on(array(
                        array('rawat_inap.nurse_station', '=', 'nurse_station.uid'),
                        array('nurse_station.unit', '=', 'master_unit.uid')
                    ))
                    ->where(array(
                        'rawat_inap.kunjungan' => '= ?',
                        'AND',
                        'rawat_inap.dokter' => '= ?',
                        'AND',
                        'rawat_inap.penjamin' => '= ?',
                        'AND',
                        'rawat_inap.deleted_at' => 'IS NULL',
                        'AND',
                        'nurse_station.deleted_at' => 'IS NULL'
                    ), array(
                        $parameter['antrian']['kunjungan'],
                        $parameter['antrian']['dokter'],
                        $parameter['antrian']['penjamin']
                    ))
                    ->execute();

                $Mutasi = $Inventori->tambah_mutasi(array(
                    'access_token' => $parameter['access_token'],
                    'dari' => $UserData['data']->gudang,
                    'ke' => $RawatInap['response_data'][0]['gudang'],
                    'keterangan' => 'Kebutuhan Resep Rawat Inap',
                    'inap' => true,
                    'item' => $itemMutasi
                ));

                if($Mutasi['response_result'] > 0) {

                    //Update batch rawat inap
                    foreach ($itemMutasi as $mutBatch => $mutValue) {
                        if(floatval($mutValue['mutasi']) > 0) {
                            $BarangBatch = explode('|', $mutBatch);
                            $inapBatch = self::$query->select('rawat_inap_batch', array(
                                'id'
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
                                    'rawat_inap_batch.deleted_at' => 'IS NULL'
                                ), array(
                                    $BarangBatch[0],
                                    $BarangBatch[1],
                                    $parameter['resep'],
                                    $RawatInap['response_data'][0]['gudang']
                                ))
                                ->execute();
                            if(count($inapBatch['response_data']) > 0) {
                                $updateBatchInap = self::$query->update('rawat_inap_batch', array(
                                    'qty' => floatval($inapBatch['response_data'][0]['qty']) + floatval($mutValue['mutasi']),
                                    'updated_at' => parent::format_date()
                                ))
                                    ->where(array(
                                        'rawat_inap_batch.id' => '= ?'
                                    ), array(
                                        $inapBatch['response_data'][0]['id']
                                    ))
                                    ->execute();
                            } else {
                                $updateBatchInap = self::$query->insert('rawat_inap_batch', array(
                                    'gudang' => $RawatInap['response_data'][0]['gudang'],
                                    'pasien' => $RawatInap['response_data'][0]['pasien'],
                                    'resep' => $parameter['resep'],
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

                    $updateResep = self::$query->update('resep', array(
                        'status_resep' => 'D'
                    ))
                        ->where(array(
                            'resep.uid' => '= ?'
                        ), array(
                            $parameter['resep']
                        ))
                        ->execute();

                    //Update Racikan
                    $updateRacikan = self::$query->update('racikan', array(
                        'status' => 'D'
                    ))
                        ->where(array(
                            'racikan.asesmen' => '= ?',
                            'AND',
                            'racikan.status' => '= ?',
                            'AND',
                            'racikan.deleted_at' => 'IS NULL'
                        ), array(
                            $parameter['asesmen'],
                            'L'
                        ))
                        ->execute();
                }
            }
        } else {
            //Jika bukan Rawat Inap potong seperti biasa
            $updateResult = 0;
            $updateProgress = array();

            foreach ($usedBatch as $bKey => $bValue)
            {
                //Stok Sebelum Update
                $getStok = self::$query->select('inventori_stok', array(
                    'id',
                    'gudang',
                    'barang',
                    'stok_terkini'
                ))
                    ->where(array(
                        'inventori_stok.gudang' => '= ?',
                        'AND',
                        'inventori_stok.barang' => '= ?'
                    ), array(
                        $bValue['gudang'],
                        $bValue['barang']
                    ))
                    ->execute();


                //Potong Stok
                if(
                    floatval($bValue['qty']) > 0 &&
                    floatval($getStok['response_data'][0]['stok_terkini']) > floatval($bValue['qty'])
                )
                {
                    $updateStok = self::$query->update('inventori_stok', array(
                        'stok_terkini' => (floatval($getStok['response_data'][0]['stok_terkini']) - floatval($bValue['qty']))
                    ))
                        ->where(array(
                            'inventori_stok.gudang' => '= ?',
                            'AND',
                            'inventori_stok.barang' => '= ?',
                            'AND',
                            'inventori_stok.batch' => '= ?'
                        ), array(
                            $bValue['gudang'],
                            $bValue['barang'],
                            $bValue['batch']
                        ))
                        ->execute();
                    if($updateStok['response_result'] > 0)
                    {
                        //Log Stok
                        $stokLog = self::$query->insert('inventori_stok_log', array(
                            'barang' => $bValue['barang'],
                            'batch'=> $bValue['batch'],
                            'gudang' => $bValue['gudang'],
                            'masuk' => 0,
                            'keluar' => floatval($bValue['qty']),
                            'saldo' => (floatval($getStok['response_data'][0]['stok_terkini']) - floatval($bValue['qty'])),
                            'type' => __STATUS_BARANG_KELUAR__,
                            'jenis_transaksi' => 'resep',
                            'uid_foreign' => $parameter['resep'],
                            'keterangan' => ''
                        ))
                            ->execute();
                        $updateResult += $stokLog['response_result'];
                    }
                    array_push($updateProgress, $updateStok);
                }
            }

            if($updateResult == count($usedBatch)) {
                //Update Resep
                $updateResep = self::$query->update('resep', array(
                    'status_resep' => 'D'
                ))
                    ->where(array(
                        'resep.uid' => '= ?'
                    ), array(
                        $parameter['resep']
                    ))
                    ->execute();

                //Update Racikan
                $updateRacikan = self::$query->update('racikan', array(
                    'status' => 'D'
                ))
                    ->where(array(
                        'racikan.asesmen' => '= ?',
                        'AND',
                        'racikan.status' => '= ?',
                        'AND',
                        'racikan.deleted_at' => 'IS NULL'
                    ), array(
                        $parameter['asesmen'],
                        'L'
                    ))
                    ->execute();
            } else {
                //Update Resep
                $updateResep = self::$query->update('resep', array(
                    'status_resep' => 'D'
                ))
                    ->where(array(
                        'resep.uid' => '= ?'
                    ), array(
                        $parameter['resep']
                    ))
                    ->execute();

                //Update Racikan
                $updateRacikan = self::$query->update('racikan', array(
                    'status' => 'D'
                ))
                    ->where(array(
                        'racikan.asesmen' => '= ?',
                        'AND',
                        'racikan.status' => '= ?',
                        'AND',
                        'racikan.deleted_at' => 'IS NULL'
                    ), array(
                        $parameter['asesmen'],
                        'L'
                    ))
                    ->execute();
            }
        }

        return array(
            'result' => $usedBatch,
            'resep' => $resepItem,
            'racikan' => $racikan,
            'raw_batch' => $rawBatch,
            'stok_progress' => $updateProgress,
            'informasi_inap' => $RawatInap,
            'mutasi' => $Mutasi,
            'batch' => $usedBatch,
            'parse_mutas' => $itemMutasi,
            'stok_result' => ($updateResult == count($usedBatch)) ? 1 : 0
        );
    }

    private function detail_resep_2($parameter) {
        $dataResponse = array(
            'detail' => array(),
            'resep' => array(),
            'racikan' => array()
        );

        $unique_racikan = array();


        //Resep Detail
        $resep = self::$query->select('resep', array(
            'uid',
            'asesmen',
            'antrian',
            'keterangan',
            'keterangan_racikan'
        ))
            ->where(array(
                'resep.deleted_at' => 'IS NULL',
                'AND',
                'resep.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();

        $AntrianDetail = self::$query->select('antrian', array(
                'uid',
                'kunjungan',
                'penjamin',
                'pasien',
                'dokter',
                'departemen'
            ))
            ->where(array(
                'antrian.uid' => '= ?',
                'AND',
                'antrian.deleted_at' => 'IS NULL'
            ), array(
                $resep['response_data'][0]['antrian']
            ))
            ->execute();

        foreach ($AntrianDetail['response_data'] as $AKey => $AValue) {
            $Pasien = new Pasien(self::$pdo);
            $AntrianDetail['response_data'][$AKey]['pasien'] = $Pasien->get_pasien_detail('pasien', $AValue['pasien'])['response_data'][0];

            $Penjamin = new Penjamin(self::$pdo);
            $AntrianDetail['response_data'][$AKey]['penjamin'] = $Penjamin->get_penjamin_detail($AValue['penjamin'])['response_data'][0];

            $Poli = new Poli(self::$pdo);
            $AntrianDetail['response_data'][$AKey]['departemen'] = $Poli->get_poli_detail($AValue['departemen'])['response_data'][0];

            $Dokter = new Pegawai(self::$pdo);
            $AntrianDetail['response_data'][$AKey]['dokter'] = $Dokter->get_detail_pegawai($AValue['dokter'])['response_data'][0];
        }

        $dataResponse['detail'] = $AntrianDetail['response_data'][0];

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
                    'resep_detail.deleted_at' => 'IS NULL',
                    'AND',
                    'resep_detail.resep' => '= ?'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach ($resepDetail['response_data'] as $RDKey => $RDValue) {
                $Inventori = new Inventori(self::$pdo);
                $resepDetail['response_data'][$RDKey]['obat_detail'] = $Inventori->get_item_detail($RDValue['obat'])['response_data'][0];
            }
            $dataResponse['resep'] = $resepDetail['response_data'];

            //Racikan Detail
            $racikan = self::$query->select('racikan', array(
                'uid',
                'asesmen',
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
                    $value['asesmen']
                ))
                ->execute();

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
                        'racikan_detail.deleted_at' => 'IS NULL',
                        /*'AND',
                        'racikan_detail.resep' => '= ?',*/
                        'AND',
                        'racikan_detail.racikan' => '= ?'
                    ), array(
                        //$value['uid'],
                        $RacikanValue['uid']
                    ))
                    ->execute();

                foreach ($RacikanDetailData['response_data'] as $RVIKey => $RVIValue) {
                    $InventoriObat = new Inventori(self::$pdo);
                    $RacikanDetailData['response_data'][$RVIKey]['obat_detail'] = $InventoriObat::get_item_detail($RVIValue['obat'])['response_data'][0];
                }

                $RacikanValue['item'] = $RacikanDetailData['response_data'];
                array_push($dataResponse['racikan'], $RacikanValue);
            }
        }

        return array($dataResponse);
    }

    private function detail_resep($parameter, $status = 'N')
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $data = self::$query->select('resep', array(
            'uid',
            'kunjungan',
            'antrian',
            'asesmen',
            'dokter',
            'pasien',
            'total',
            'keterangan',
            'keterangan_racikan',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'resep.uid' => '= ?',
                'AND',
                'resep.deleted_at' => 'IS NULL',
                'AND',
                'resep.status_resep' => '= ?'
            ), array(
                $parameter,
                $status
            ))
            ->execute();
        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            //Dokter Info
            $Pegawai = new Pegawai(self::$pdo);
            $PegawaiInfo = $Pegawai::get_detail($value['dokter']);
            $data['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

            $VerifikatorInfo = $Pegawai::get_detail($value['verifikator']);
            $data['response_data'][$key]['verifikator'] = $VerifikatorInfo['response_data'][0];

            //Get Antrian Detail
            $Antrian = new Antrian(self::$pdo);
            $AntrianInfo = $Antrian::get_antrian_detail('antrian', $value['antrian']);
            $data['response_data'][$key]['antrian'] = $AntrianInfo['response_data'][0];

            //Departemen Info
            $Poli = new Poli(self::$pdo);
            $PoliInfo = $Poli::get_poli_detail($AntrianInfo['response_data'][0]['departemen']);
            $AntrianInfo['response_data'][0]['departemen'] = $PoliInfo['response_data'][0];


            //Get resep detail
            $resep_detail = self::$query->select('resep_detail', array(
                'id',
                'resep',
                'obat',
                'harga',
                'signa_qty',
                'signa_pakai',
                'qty',
                'satuan',
                'keterangan',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'resep_detail.resep' => '= ?',
                    'AND',
                    'resep_detail.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach ($resep_detail['response_data'] as $ResKey => $ResValue) {
                $Inventori = new Inventori(self::$pdo);
                $InventoriInfo = $Inventori::get_item_detail($ResValue['obat']);
                $resep_detail['response_data'][$ResKey]['detail'] = $InventoriInfo['response_data'][0];

                //Batch Info
                $Inventori = new Inventori(self::$pdo);
                $InventoriBatch = $Inventori::get_item_batch($ResValue['obat']);
                $resep_detail['response_data'][$ResKey]['batch'] = $InventoriBatch['response_data'];
                $total_sedia = 0;
                foreach ($InventoriBatch['response_data'] as $bKey => $bValue)
                {
                    if($bValue['gudang']['uid'] === $UserData['data']->gudang)
                    {
                        $total_sedia += $bValue['stok_terkini'];
                    }
                }
                $resep_detail['response_data'][$ResKey]['sedia'] = $total_sedia;
            }
            $data['response_data'][$key]['detail'] = $resep_detail['response_data'];


            //Racikan Item
            $racikan = self::$query->select('racikan', array(
                'uid',
                'asesmen',
                //'resep',
                'kode',
                'total',
                'keterangan',
                'signa_qty',
                'signa_pakai',
                'qty',
                'keterangan',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'racikan.asesmen' => '= ?',
                    'AND',
                    'racikan.status' => '= ?',
                    'AND',
                    'racikan.deleted_at' => 'IS NULL'
                ), array(
                    $value['asesmen'],
                    $status
                ))
                ->execute();
            foreach ($racikan['response_data'] as $RDKey => $RDValue) {
                $racikan_detail = self::$query->select('racikan_detail', array(
                    'id',
                    'asesmen',
                    //'resep',
                    'obat',
                    'ratio',
                    'pembulatan',
                    'kekuatan',
                    'harga',
                    'racikan',
                    'takar_bulat',
                    'takar_decimal',
                    'penjamin',
                    'created_at',
                    'updated_at'
                ))
                    ->where(array(
                        'racikan_detail.deleted_at' => 'IS NULL',
                        /*'AND',
                        'racikan_detail.resep' => '= ?',*/
                        'AND',
                        'racikan_detail.racikan' => '= ?'
                    ), array(
                        //$value['uid'],
                        $RDValue['uid']
                    ))
                    ->execute();
                foreach ($racikan_detail['response_data'] as $RDIKey => $RDIValue) {
                    $Inventori = new Inventori(self::$pdo);
                    $InventoriInfo = $Inventori::get_item_detail($RDIValue['obat']);
                    $racikan_detail['response_data'][$RDIKey]['detail'] = $InventoriInfo['response_data'][0];

                    $InventoriBacthRacikan = $Inventori::get_item_batch($RDIValue['obat']);
                    $racikan_detail['response_data'][$RDIKey]['batch'] = $InventoriBacthRacikan['response_data'];

                    $total_sedia_racikan = 0;
                    foreach ($InventoriBacthRacikan['response_data'] as $bRKey => $bRValue)
                    {
                        if($bRValue['gudang']['uid'] === $UserData['data']->gudang)
                        {
                            $total_sedia_racikan += floatval($bRValue['stok_terkini']);
                        }
                    }
                    $racikan_detail['response_data'][$RDIKey]['sedia'] = $total_sedia_racikan;
                }
                $racikan['response_data'][$RDKey]['detail'] = $racikan_detail['response_data'];
            }
            $data['response_data'][$key]['racikan'] = $racikan['response_data'];

            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }

        return $data;
    }

    private function detail_resep_verifikator_post($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $Inventori = new Inventori(self::$pdo);
        $Poli = new Poli(self::$pdo);
        $Antrian = new Antrian(self::$pdo);
        $Pasien = new Pasien(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);
        $Unit = new Inap(self::$pdo);

        $UnitDetail = $Unit->get_ns_detail($parameter['nurse_station'])['response_data'][0];

        $resep_dokter = self::$query->select('resep', array(
            'uid',
            'kunjungan',
            'antrian',
            'asesmen',
            'dokter',
            'pasien',
            'total',
            'status_resep',
            'keterangan',
            'keterangan_racikan',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'resep.uid' => '= ?',
                'AND',
                'resep.deleted_at' => 'IS NULL'
            ), array(
                $parameter['uid']
            ))
            ->execute();

        foreach($resep_dokter['response_data'] as $key => $value) {
            //Dokter Info
            $PegawaiInfo = $Pegawai->get_detail($value['dokter']);
            $resep_dokter['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

            //Pasien Info
            $PasienInfo = $Pasien->get_pasien_detail('pasien', $value['pasien']);
            $resep_dokter['response_data'][$key]['pasien'] = $PasienInfo['response_data'][0];

            //Get Antrian Detail
            $AntrianInfo = $Antrian->get_antrian_detail('antrian', $value['antrian']);
            $resep_dokter['response_data'][$key]['antrian'] = $AntrianInfo['response_data'][0];

            //Departemen Info
            if($AntrianInfo['response_data'][0]['departemen'] === __POLI_INAP__) {
                $AntrianInfo['response_data'][0]['departemen'] = array(
                    'uid' => __POLI_INAP__,
                    'nama' => 'Rawat Inap'
                );
            } else {
                $PoliInfo = $Poli->get_poli_detail($AntrianInfo['response_data'][0]['departemen']);
                $AntrianInfo['response_data'][0]['departemen'] = $PoliInfo['response_data'][0];
            }




            $resep_verifikator = self::$query->select('resep_change_log', array(
                'item',
                'keterangan',
                'qty',
                'aturan_pakai',
                'signa_qty',
                'signa_pakai',
                'aturan_pakai'
            ))
                ->where(array(
                    'resep_change_log.resep' => '= ?',
                    'AND',
                    'resep_change_log.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach ($resep_verifikator['response_data'] as $ResKey => $ResValue) {
                //Check Ketersediaan Obat pada NS
                $NSInap = self::$query->select('rawat_inap_batch', array(
                    'qty'
                ))
                    ->where(array(
                        'rawat_inap_batch.gudang' => '= ?',
                        'AND',
                        'rawat_inap_batch.obat' => '= ?',
                        'AND',
                        'rawat_inap_batch.resep' => '= ?'
                    ), array(
                        $UnitDetail['gudang'],
                        $ResValue['item'],
                        $value['uid']
                    ))
                    ->execute();
                $resep_verifikator['response_data'][$ResKey]['stok_ns'] = $NSInap['response_data'];

                $InventoriInfo = $Inventori->get_item_detail($ResValue['item']);
                $resep_verifikator['response_data'][$ResKey]['detail'] = $InventoriInfo['response_data'][0];

                //Batch Info
                $InventoriBatch = $Inventori->get_item_batch($ResValue['item']);
                $resep_verifikator['response_data'][$ResKey]['batch'] = $InventoriBatch['response_data'];
                $total_sedia = 0;
                foreach ($InventoriBatch['response_data'] as $bKey => $bValue)
                {
                    if($bValue['gudang']['uid'] === $UserData['data']->gudang)
                    {
                        $total_sedia += $bValue['stok_terkini'];
                    }
                }
                $resep_verifikator['response_data'][$ResKey]['sedia'] = $total_sedia;

                $aturan_pakai = self::$query->select('terminologi_item', array(
                    'id',
                    'nama'
                ))
                    ->where(array(
                        'terminologi_item.id' => '= ?',
                        'AND',
                        'terminologi_item.deleted_at' => 'IS NULL'
                    ), array(
                        $ResValue['aturan_pakai']
                    ))
                    ->execute();


                $resep_verifikator['response_data'][$ResKey]['aturan_pakai'] = $aturan_pakai['response_data'][0];
            }

            $resep_dokter['response_data'][$key]['detail'] = $resep_verifikator['response_data'];



            //Racikan Item
            $racikan = self::$query->select('racikan', array(
                'uid',
                'asesmen',
                'kode',
                'total',
                'keterangan',
                'signa_qty',
                'signa_pakai',
                'qty',
                'keterangan',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'racikan.asesmen' => '= ?',
                    'AND',
                    'racikan.deleted_at' => 'IS NULL'
                ), array(
                    $value['asesmen']
                ))
                ->execute();
            foreach ($racikan['response_data'] as $RDKey => $RDValue) {
                //Stok Racikan NS
                $history_racikan = self::$query->select('rawat_inap_riwayat_obat', array(
                    'qty'
                ))
                    ->where(array(
                        'rawat_inap_riwayat_obat.obat' => '= ?',
                        'AND',
                        'rawat_inap_riwayat_obat.resep' => '= ?'
                    ), array(
                        $RDValue['kode'],
                        $value['uid']
                    ))
                    ->execute();
                $racikan['response_data'][$RDKey]['ns_qty'] = $history_racikan['response_data'];

                $racikan_verifikator = self::$query->select('racikan_change_log', array(
                    'jumlah',
                    'signa_qty',
                    'signa_pakai',
                    'aturan_pakai',
                    'keterangan'
                ))
                    ->where(array(
                        'racikan_change_log.racikan' => '= ?',
                        'AND',
                        'racikan_change_log.deleted_at' => 'IS NULL'
                    ), array(
                        $RDValue['uid']
                    ))
                    ->execute();



                $racikan_detail = self::$query->select('racikan_detail_change_log', array(
                    'id',
                    'obat',
                    'kekuatan',
                    'created_at',
                    'updated_at'
                ))
                    ->where(array(
                        'racikan_detail_change_log.deleted_at' => 'IS NULL',
                        'AND',
                        'racikan_detail_change_log.racikan' => '= ?'
                    ), array(
                        $RDValue['uid']
                    ))
                    ->execute();
                foreach ($racikan_detail['response_data'] as $RDIKey => $RDIValue) {
                    $InventoriInfo = $Inventori->get_item_detail($RDIValue['obat']);
                    $racikan_detail['response_data'][$RDIKey]['detail'] = $InventoriInfo['response_data'][0];

                    $InventoriBacthRacikan = $Inventori->get_item_batch($RDIValue['obat']);
                    $racikan_detail['response_data'][$RDIKey]['batch'] = $InventoriBacthRacikan['response_data'];

                    $total_sedia_racikan = 0;
                    foreach ($InventoriBacthRacikan['response_data'] as $bRKey => $bRValue)
                    {
                        if($bRValue['gudang']['uid'] === $UserData['data']->gudang)
                        {
                            $total_sedia_racikan += floatval($bRValue['stok_terkini']);
                        }
                    }
                    $racikan_detail['response_data'][$RDIKey]['sedia'] = $total_sedia_racikan;
                }
                $racikan['response_data'][$RDKey]['detail'] = $racikan_detail['response_data'];
            }
            $resep_dokter['response_data'][$key]['racikan'] = $racikan['response_data'];
        }


        return $resep_dokter;
    }


    private function detail_resep_verifikator($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $Inventori = new Inventori(self::$pdo);
        $Poli = new Poli(self::$pdo);
        $Antrian = new Antrian(self::$pdo);
        $Pasien = new Pasien(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);


        $resep_dokter = self::$query->select('resep', array(
            'uid',
            'kunjungan',
            'antrian',
            'asesmen',
            'dokter',
            'pasien',
            'total',
            'status_resep',
            'keterangan',
            'keterangan_racikan',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'resep.uid' => '= ?',
                'AND',
                'resep.deleted_at' => 'IS NULL'
            ), array(
                $parameter
            ))
            ->execute();

        foreach($resep_dokter['response_data'] as $key => $value) {
            //Dokter Info
            $PegawaiInfo = $Pegawai->get_detail($value['dokter']);
            $resep_dokter['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

            //Pasien Info
            $PasienInfo = $Pasien->get_pasien_detail('pasien', $value['pasien']);
            $resep_dokter['response_data'][$key]['pasien'] = $PasienInfo['response_data'][0];

            //Get Antrian Detail
            $AntrianInfo = $Antrian->get_antrian_detail('antrian', $value['antrian']);
            $resep_dokter['response_data'][$key]['antrian'] = $AntrianInfo['response_data'][0];

            //Departemen Info
            if($AntrianInfo['response_data'][0]['departemen'] === __POLI_INAP__) {
                $AntrianInfo['response_data'][0]['departemen'] = array(
                    'uid' => __POLI_INAP__,
                    'nama' => 'Rawat Inap'
                );
            } else {
                $PoliInfo = $Poli->get_poli_detail($AntrianInfo['response_data'][0]['departemen']);
                $AntrianInfo['response_data'][0]['departemen'] = $PoliInfo['response_data'][0];
            }




            $resep_verifikator = self::$query->select('resep_change_log', array(
                'item',
                'keterangan',
                'qty',
                'aturan_pakai',
                'signa_qty',
                'signa_pakai',
                'aturan_pakai'
            ))
                ->where(array(
                    'resep_change_log.resep' => '= ?',
                    'AND',
                    'resep_change_log.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach ($resep_verifikator['response_data'] as $ResKey => $ResValue) {
                $InventoriInfo = $Inventori->get_item_detail($ResValue['item']);
                $resep_verifikator['response_data'][$ResKey]['detail'] = $InventoriInfo['response_data'][0];

                //Batch Info
                $InventoriBatch = $Inventori->get_item_batch($ResValue['item']);
                $resep_verifikator['response_data'][$ResKey]['batch'] = $InventoriBatch['response_data'];
                $total_sedia = 0;
                foreach ($InventoriBatch['response_data'] as $bKey => $bValue)
                {
                    if($bValue['gudang']['uid'] === $UserData['data']->gudang)
                    {
                        $total_sedia += $bValue['stok_terkini'];
                    }
                }
                $resep_verifikator['response_data'][$ResKey]['sedia'] = $total_sedia;

                $aturan_pakai = self::$query->select('terminologi_item', array(
                    'id',
                    'nama'
                ))
                    ->where(array(
                        'terminologi_item.id' => '= ?',
                        'AND',
                        'terminologi_item.deleted_at' => 'IS NULL'
                    ), array(
                        $ResValue['aturan_pakai']
                    ))
                    ->execute();


                $resep_verifikator['response_data'][$ResKey]['aturan_pakai'] = $aturan_pakai['response_data'][0];
            }

            $resep_dokter['response_data'][$key]['detail'] = $resep_verifikator['response_data'];



            //Racikan Item
            $racikan = self::$query->select('racikan', array(
                'uid',
                'asesmen',
                'kode',
                'total',
                'keterangan',
                'signa_qty',
                'signa_pakai',
                'qty',
                'keterangan',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'racikan.asesmen' => '= ?',
                    'AND',
                    'racikan.deleted_at' => 'IS NULL'
                ), array(
                    $value['asesmen']
                ))
                ->execute();
            foreach ($racikan['response_data'] as $RDKey => $RDValue) {

                $racikan_verifikator = self::$query->select('racikan_change_log', array(
                    'jumlah',
                    'signa_qty',
                    'signa_pakai',
                    'aturan_pakai',
                    'keterangan'
                ))
                    ->where(array(
                        'racikan_change_log.racikan' => '= ?',
                        'AND',
                        'racikan_change_log.deleted_at' => 'IS NULL'
                    ), array(
                        $RDValue['uid']
                    ))
                    ->execute();



                $racikan_detail = self::$query->select('racikan_detail_change_log', array(
                    'id',
                    'obat',
                    'kekuatan',
                    'created_at',
                    'updated_at'
                ))
                    ->where(array(
                        'racikan_detail_change_log.deleted_at' => 'IS NULL',
                        'AND',
                        'racikan_detail_change_log.racikan' => '= ?'
                    ), array(
                        $RDValue['uid']
                    ))
                    ->execute();
                foreach ($racikan_detail['response_data'] as $RDIKey => $RDIValue) {
                    $InventoriInfo = $Inventori->get_item_detail($RDIValue['obat']);
                    $racikan_detail['response_data'][$RDIKey]['detail'] = $InventoriInfo['response_data'][0];

                    $InventoriBacthRacikan = $Inventori->get_item_batch($RDIValue['obat']);
                    $racikan_detail['response_data'][$RDIKey]['batch'] = $InventoriBacthRacikan['response_data'];

                    $total_sedia_racikan = 0;
                    foreach ($InventoriBacthRacikan['response_data'] as $bRKey => $bRValue)
                    {
                        if($bRValue['gudang']['uid'] === $UserData['data']->gudang)
                        {
                            $total_sedia_racikan += floatval($bRValue['stok_terkini']);
                        }
                    }
                    $racikan_detail['response_data'][$RDIKey]['sedia'] = $total_sedia_racikan;
                }
                $racikan['response_data'][$RDKey]['detail'] = $racikan_detail['response_data'];
            }
            $resep_dokter['response_data'][$key]['racikan'] = $racikan['response_data'];
        }


        return $resep_dokter;
    }

    private function get_resep_serah_backend($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'resep.deleted_at' => 'IS NULL',
                'AND',
                '((resep.status_resep' => '= ?',
                'OR',
                'resep.status_resep' => '= ?)',
                'OR',
                '(resep.status_resep' => '= ?))'
            );

            $paramValue = array('D', 'P', 'S');
        } else {
            $paramData = array(
                'resep.deleted_at' => 'IS NULL',
                'AND',
                '((resep.status_resep' => '= ?',
                'OR',
                'resep.status_resep' => '= ?)',
                'OR',
                '(resep.status_resep' => '= ?))'
                //'resep.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array('D', 'P', 'S');
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('resep', array(
                'uid',
                'kunjungan',
                'antrian',
                'asesmen',
                'dokter',
                'pasien',
                'total',
                'status_resep',
                'created_at',
                'updated_at'
            ))
                ->order(array(
                    'updated_at' => 'DESC'
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('resep', array(
                'uid',
                'kunjungan',
                'antrian',
                'asesmen',
                'dokter',
                'pasien',
                'total',
                'status_resep',
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
        $autonum = intval($parameter['start']) + 1;

        foreach ($data['response_data'] as $key => $value) {
            //Dokter Info
            $Pegawai = new Pegawai(self::$pdo);
            $PegawaiInfo = $Pegawai->get_detail($value['dokter']);
            $data['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

            //Get Antrian Detail
            $Antrian = new Antrian(self::$pdo);
            $AntrianInfo = $Antrian->get_antrian_detail('antrian', $value['antrian']);

            //Departemen Info
            $Poli = new Poli(self::$pdo);
            $PoliInfo = $Poli->get_poli_detail($AntrianInfo['response_data'][0]['departemen']);
            $AntrianInfo['response_data'][0]['departemen'] = $PoliInfo['response_data'][0];
            $data['response_data'][$key]['antrian'] = $AntrianInfo['response_data'][0];

            //Get resep detail
            $resep_detail = self::$query->select('resep_detail', array(
                'id',
                'resep',
                'obat',
                'harga',
                'signa_qty',
                'signa_pakai',
                'qty',
                'satuan',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'resep_detail.resep' => '= ?',
                    'AND',
                    'resep_detail.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach ($resep_detail['response_data'] as $ResKey => $ResValue) {
                //Batch Info
                $Inventori = new Inventori(self::$pdo);
                $InventoriBatch = $Inventori::get_item_batch($ResValue['obat']);
                $resep_detail['response_data'][$ResKey]['batch'] = $InventoriBatch['response_data'];

                $Inventori = new Inventori(self::$pdo);
                $InventoriInfo = $Inventori::get_item_detail($ResValue['obat']);
                $resep_detail['response_data'][$ResKey]['detail'] = $InventoriInfo['response_data'][0];
            }
            $data['response_data'][$key]['detail'] = $resep_detail['response_data'];


            //Racikan Item
            $racikan = self::$query->select('racikan', array(
                'uid',
                'asesmen',
                //'resep',
                'kode',
                'total',
                'keterangan',
                'signa_qty',
                'signa_pakai',
                'qty',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'racikan.asesmen' => '= ?',
                    'AND',
                    'racikan.status' => '= ?',
                    'AND',
                    'racikan.deleted_at' => 'IS NULL'
                ), array(
                    $value['asesmen'],
                    'N'
                ))
                ->execute();
            foreach ($racikan['response_data'] as $RDKey => $RDValue) {
                $racikan_detail = self::$query->select('racikan_detail', array(
                    'id',
                    'asesmen',
                    //'resep',
                    'obat',
                    'ratio',
                    'pembulatan',
                    'harga',
                    'racikan',
                    'takar_bulat',
                    'takar_decimal',
                    'penjamin',
                    'created_at',
                    'updated_at'
                ))
                    ->where(array(
                        'racikan_detail.deleted_at' => 'IS NULL',
                        /*'AND',
                        'racikan_detail.resep' => '= ?',*/
                        'AND',
                        'racikan_detail.racikan' => '= ?'
                    ), array(
                        //$value['uid'],
                        $RDValue['uid']
                    ))
                    ->execute();
                foreach ($racikan_detail['response_data'] as $RDIKey => $RDIValue) {
                    $Inventori = new Inventori(self::$pdo);
                    $InventoriInfo = $Inventori::get_item_detail($RDIValue['obat']);

                    $racikan_detail['response_data'][$RDIKey]['detail'] = $InventoriInfo['response_data'][0];
                }
                $racikan['response_data'][$RDKey]['detail'] = $racikan_detail['response_data'];
            }
            $data['response_data'][$key]['racikan'] = $racikan['response_data'];
            $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }

        $itemTotal = self::$query->select('resep', array(
            'uid'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($data['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function resep_inap($parameter) {
        $Unit = new Inap(self::$pdo);
        $UnitDetail = $Unit->get_ns_detail($parameter['nurse_station'])['response_data'][0];
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'resep.deleted_at' => 'IS NULL',
                'AND',
                'resep.pasien' => '= ?',
                'AND',
                'resep.kunjungan' => '= ?',
            );

            $paramValue = array($parameter['pasien'], $parameter['kunjungan']);
        } else {
            $paramData = array(
                'resep.deleted_at' => 'IS NULL',
                'AND',
                'resep.pasien' => '= ?',
                'AND',
                'resep.kunjungan' => '= ?',
            );

            $paramValue = array($parameter['pasien'], $parameter['kunjungan']);
        }



        if ($parameter['length'] < 0) {
            $data = self::$query->select('resep', array(
                'uid',
                'kunjungan',
                'antrian',
                'asesmen',
                'dokter',
                'pasien',
                'total',
                'status_resep',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('resep', array(
                'uid',
                'kunjungan',
                'antrian',
                'asesmen',
                'dokter',
                'pasien',
                'total',
                'status_resep',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        $Inventori = new Inventori(self::$pdo);
        $Antrian = new Antrian(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);

        foreach ($data['response_data'] as $key => $value) {
            $AntrianDetail = $Antrian->get_antrian_detail('antrian', $value['antrian']);
            $data['response_data'][$key]['antrian_detail'] = $AntrianDetail['response_data'][0];

            $PegawaiDetail = $Pegawai->get_detail($value['dokter']);
            $data['response_data'][$key]['dokter_detail'] = $PegawaiDetail['response_data'][0];

            //Get resep detail
            $resep_detail = self::$query->select('resep_detail', array(
                'id',
                'resep',
                'obat',
                'harga',
                'signa_qty',
                'signa_pakai',
                'qty',
                'satuan',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'resep_detail.resep' => '= ?',
                    'AND',
                    'resep_detail.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach ($resep_detail['response_data'] as $ResKey => $ResValue) {
                //Batch Info
                $InventoriBatch = $Inventori->get_item_batch($ResValue['obat']);
                $resep_detail['response_data'][$ResKey]['batch'] = $InventoriBatch['response_data'];

                $InventoriInfo = $Inventori->get_item_detail($ResValue['obat']);
                $resep_detail['response_data'][$ResKey]['detail'] = $InventoriInfo['response_data'][0];

                //Check Ketersediaan Obat pada NS
                $NSInap = self::$query->select('rawat_inap_batch', array(
                    'qty'
                ))
                    ->where(array(
                        'rawat_inap_batch.gudang' => '= ?',
                        'AND',
                        'rawat_inap_batch.obat' => '= ?',
                        'AND',
                        'rawat_inap_batch.resep' => '= ?'
                    ), array(
                        $UnitDetail['uid_gudang'],
                        $ResValue['obat'],
                        $value['uid']
                    ))
                    ->execute();
                $resep_detail['response_data'][$ResKey]['stok_ns'] = $NSInap['response_data'];
            }
            $data['response_data'][$key]['detail'] = $resep_detail['response_data'];


            //Racikan Item
            $racikan = self::$query->select('racikan', array(
                'uid',
                'asesmen',
                //'resep',
                'kode',
                'total',
                'keterangan',
                'signa_qty',
                'signa_pakai',
                'qty',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'racikan.asesmen' => '= ?',
                    'AND',
                    'racikan.deleted_at' => 'IS NULL'
                ), array(
                    $value['asesmen']
                ))
                ->execute();
            foreach ($racikan['response_data'] as $RDKey => $RDValue) {

                //Stok Racikan NS
                $history_racikan = self::$query->select('rawat_inap_riwayat_obat', array(
                    'qty'
                ))
                    ->where(array(
                        'rawat_inap_riwayat_obat.obat' => '= ?',
                        'AND',
                        'rawat_inap_riwayat_obat.resep' => '= ?'
                    ), array(
                        $RDValue['kode'],
                        $value['uid']
                    ))
                    ->execute();
                $racikan['response_data'][$RDKey]['ns_qty'] = $history_racikan['response_data'];
                $racikan_detail = self::$query->select('racikan_detail', array(
                    'id',
                    'asesmen',
                    //'resep',
                    'obat',
                    'ratio',
                    'pembulatan',
                    'harga',
                    'racikan',
                    'takar_bulat',
                    'takar_decimal',
                    'penjamin',
                    'created_at',
                    'updated_at'
                ))
                    ->where(array(
                        'racikan_detail.deleted_at' => 'IS NULL',
                        /*'AND',
                        'racikan_detail.resep' => '= ?',*/
                        'AND',
                        'racikan_detail.racikan' => '= ?'
                    ), array(
                        //$value['uid'],
                        $RDValue['uid']
                    ))
                    ->execute();
                foreach ($racikan_detail['response_data'] as $RDIKey => $RDIValue) {
                    $InventoriInfo = $Inventori->get_item_detail($RDIValue['obat']);

                    $racikan_detail['response_data'][$RDIKey]['detail'] = $InventoriInfo['response_data'][0];
                }
                $racikan['response_data'][$RDKey]['detail'] = $racikan_detail['response_data'];
            }
            $data['response_data'][$key]['racikan'] = $racikan['response_data'];
            $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }

        $itemTotal = self::$query->select('resep', array(
            'uid'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($data['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function get_resep_backend($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if(isset($parameter['request_type'])) {
            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                $paramData = array(
                    'resep.deleted_at' => 'IS NULL',
                    'AND',
                    '((resep.status_resep' => '= ?',
                    'OR',
                    'resep.status_resep' => '= ?)',
                    'OR',
                    '(resep.status_resep' => '= ?))'
                );

                $paramValue = array('V', 'K', 'D');
            } else {
                $paramData = array(
                    'resep.deleted_at' => 'IS NULL',
                    'AND',
                    '((resep.status_resep' => '= ?',
                    'OR',
                    'resep.status_resep' => '= ?)',
                    'OR',
                    '(resep.status_resep' => '= ?))'
                );

                $paramValue = array('V', 'K', 'D');
            }
        } else {
            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                $paramData = array(
                    'resep.deleted_at' => 'IS NULL',
                    'AND',
                    'resep.status_resep' => '= ?'
                );

                $paramValue = array((isset($parameter['status']) ? $parameter['status'] : 'N'));
            } else {
                $paramData = array(
                    'resep.deleted_at' => 'IS NULL',
                    'AND',
                    'resep.status_resep' => '= ?'
                    //'resep.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
                );

                $paramValue = array((isset($parameter['status']) ? $parameter['status'] : 'N'));
            }
        }


        if(isset($parameter['request_type'])) {
            if ($parameter['length'] < 0) {
                $data = self::$query->select('resep', array(
                    'uid',
                    'kunjungan',
                    'antrian',
                    'asesmen',
                    'dokter',
                    'pasien',
                    'total',
                    'status_resep',
                    'created_at',
                    'updated_at'
                ))
                    ->where($paramData, $paramValue)
                    ->execute();
            } else {
                $data = self::$query->select('resep', array(
                    'uid',
                    'kunjungan',
                    'antrian',
                    'asesmen',
                    'dokter',
                    'pasien',
                    'total',
                    'status_resep',
                    'created_at',
                    'updated_at'
                ))
                    ->where($paramData, $paramValue)
                    ->offset(intval($parameter['start']))
                    ->limit(intval($parameter['length']))
                    ->execute();
            }
        } else {
            if ($parameter['length'] < 0) {
                $data = self::$query->select('resep', array(
                    'uid',
                    'kunjungan',
                    'antrian',
                    'asesmen',
                    'dokter',
                    'pasien',
                    'total',
                    'status_resep',
                    'created_at',
                    'updated_at'
                ))
                    ->where($paramData, $paramValue)
                    ->execute();
            } else {
                $data = self::$query->select('resep', array(
                    'uid',
                    'kunjungan',
                    'antrian',
                    'asesmen',
                    'dokter',
                    'pasien',
                    'total',
                    'status_resep',
                    'created_at',
                    'updated_at'
                ))
                    ->where($paramData, $paramValue)
                    ->offset(intval($parameter['start']))
                    ->limit(intval($parameter['length']))
                    ->execute();
            }
        }




        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;

        foreach ($data['response_data'] as $key => $value) {
            //Dokter Info
            $Pegawai = new Pegawai(self::$pdo);
            $PegawaiInfo = $Pegawai->get_detail($value['dokter']);
            $data['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

            //Get Antrian Detail
            $Antrian = new Antrian(self::$pdo);
            $AntrianInfo = $Antrian->get_antrian_detail('antrian', $value['antrian']);

            //Departemen Info
            if($AntrianInfo['response_data'][0]['departemen'] === __POLI_INAP__) {
                $AntrianInfo['response_data'][0]['departemen'] = array(
                    'uid' => __POLI_INAP__,
                    'nama' => 'Rawat Inap'
                );

                //NS Info
                $NS = self::$query->select('rawat_inap', array(
                    'nurse_station'
                ))
                    ->join('nurse_station', array(
                        'kode as kode_ns', 'nama as nama_ns'
                    ))
                    ->on(array(
                        array('rawat_inap.nurse_station', '=', 'nurse_station.uid')
                    ))
                    ->where(array(
                        'rawat_inap.kunjungan' => '= ?',
                        'AND',
                        'rawat_inap.dokter' => '= ?',
                        'AND',
                        'rawat_inap.pasien' => '= ?'
                    ), array(
                        $AntrianInfo['response_data'][0]['kunjungan'],
                        $value['dokter'],
                        $value['pasien']
                    ))
                    ->execute();
                $AntrianInfo['response_data'][0]['ns_detail'] = $NS['response_data'][0];
            } else {
                $Poli = new Poli(self::$pdo);
                $PoliInfo = $Poli->get_poli_detail($AntrianInfo['response_data'][0]['departemen']);
                $AntrianInfo['response_data'][0]['departemen'] = $PoliInfo['response_data'][0];
            }

            $data['response_data'][$key]['antrian'] = $AntrianInfo['response_data'][0];

            //Get resep detail
            $resep_detail = self::$query->select('resep_detail', array(
                'id',
                'resep',
                'obat',
                'harga',
                'signa_qty',
                'signa_pakai',
                'qty',
                'satuan',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'resep_detail.resep' => '= ?',
                    'AND',
                    'resep_detail.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach ($resep_detail['response_data'] as $ResKey => $ResValue) {
                //Batch Info
                $Inventori = new Inventori(self::$pdo);
                $InventoriBatch = $Inventori->get_item_batch($ResValue['obat']);
                $resep_detail['response_data'][$ResKey]['batch'] = $InventoriBatch['response_data'];

                $Inventori = new Inventori(self::$pdo);
                $InventoriInfo = $Inventori->get_item_detail($ResValue['obat']);
                $resep_detail['response_data'][$ResKey]['detail'] = $InventoriInfo['response_data'][0];
            }
            $data['response_data'][$key]['detail'] = $resep_detail['response_data'];


            //Racikan Item
            $racikan = self::$query->select('racikan', array(
                'uid',
                'asesmen',
                //'resep',
                'kode',
                'total',
                'keterangan',
                'signa_qty',
                'signa_pakai',
                'qty',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'racikan.asesmen' => '= ?',
                    'AND',
                    'racikan.status' => '= ?',
                    'AND',
                    'racikan.deleted_at' => 'IS NULL'
                ), array(
                    $value['asesmen'],
                    'N'
                ))
                ->execute();
            foreach ($racikan['response_data'] as $RDKey => $RDValue) {
                $racikan_detail = self::$query->select('racikan_detail', array(
                    'id',
                    'asesmen',
                    //'resep',
                    'obat',
                    'ratio',
                    'pembulatan',
                    'harga',
                    'racikan',
                    'takar_bulat',
                    'takar_decimal',
                    'penjamin',
                    'created_at',
                    'updated_at'
                ))
                    ->where(array(
                        'racikan_detail.deleted_at' => 'IS NULL',
                        /*'AND',
                        'racikan_detail.resep' => '= ?',*/
                        'AND',
                        'racikan_detail.racikan' => '= ?'
                    ), array(
                        //$value['uid'],
                        $RDValue['uid']
                    ))
                    ->execute();
                foreach ($racikan_detail['response_data'] as $RDIKey => $RDIValue) {
                    $Inventori = new Inventori(self::$pdo);
                    $InventoriInfo = $Inventori->get_item_detail($RDIValue['obat']);

                    $racikan_detail['response_data'][$RDIKey]['detail'] = $InventoriInfo['response_data'][0];
                }
                $racikan['response_data'][$RDKey]['detail'] = $racikan_detail['response_data'];
            }
            $data['response_data'][$key]['racikan'] = $racikan['response_data'];
            $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }

        $itemTotal = self::$query->select('resep', array(
            'uid'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($data['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function get_resep($status = 'N')
    {
        $data = self::$query->select('resep', array(
            'uid',
            'kunjungan',
            'antrian',
            'asesmen',
            'dokter',
            'pasien',
            'status_resep',
            'total',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'resep.deleted_at' => 'IS NULL',
                'AND',
                'resep.status_resep' => '= ?'
            ), array(
                $status
            ))
            ->execute();
        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            //Dokter Info
            $Pegawai = new Pegawai(self::$pdo);
            $PegawaiInfo = $Pegawai->get_detail($value['dokter']);
            $data['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

            //Get Antrian Detail
            $Antrian = new Antrian(self::$pdo);
            $AntrianInfo = $Antrian->get_antrian_detail('antrian', $value['antrian']);

            //Departemen Info
            $Poli = new Poli(self::$pdo);
            $PoliInfo = $Poli->get_poli_detail($AntrianInfo['response_data'][0]['departemen']);
            $AntrianInfo['response_data'][0]['departemen'] = $PoliInfo['response_data'][0];
            $data['response_data'][$key]['antrian'] = $AntrianInfo['response_data'][0];

            //Get resep detail
            $resep_detail = self::$query->select('resep_detail', array(
                'id',
                'resep',
                'obat',
                'harga',
                'signa_qty',
                'signa_pakai',
                'qty',
                'satuan',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'resep_detail.resep' => '= ?',
                    'AND',
                    'resep_detail.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach ($resep_detail['response_data'] as $ResKey => $ResValue) {
                $Inventori = new Inventori(self::$pdo);
                $InventoriInfo = $Inventori->get_item_detail($ResValue['obat']);
                $resep_detail['response_data'][$ResKey]['detail'] = $InventoriInfo['response_data'][0];
            }
            $data['response_data'][$key]['detail'] = $resep_detail['response_data'];


            //Racikan Item
            $racikan = self::$query->select('racikan', array(
                'uid',
                'asesmen',
                //'resep',
                'kode',
                'total',
                'keterangan',
                'signa_qty',
                'signa_pakai',
                'qty',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'racikan.asesmen' => '= ?',
                    'AND',
                    'racikan.status' => '= ?',
                    'AND',
                    'racikan.deleted_at' => 'IS NULL'
                ), array(
                    $value['asesmen'],
                    'N'
                ))
                ->execute();
            foreach ($racikan['response_data'] as $RDKey => $RDValue) {
                $racikan_detail = self::$query->select('racikan_detail', array(
                    'id',
                    'asesmen',
                    //'resep',
                    'obat',
                    'ratio',
                    'pembulatan',
                    'harga',
                    'racikan',
                    'takar_bulat',
                    'takar_decimal',
                    'penjamin',
                    'created_at',
                    'updated_at'
                ))
                    ->where(array(
                        'racikan_detail.deleted_at' => 'IS NULL',
                        /*'AND',
                        'racikan_detail.resep' => '= ?',*/
                        'AND',
                        'racikan_detail.racikan' => '= ?'
                    ), array(
                        //$value['uid'],
                        $RDValue['uid']
                    ))
                    ->execute();
                foreach ($racikan_detail['response_data'] as $RDIKey => $RDIValue) {
                    $Inventori = new Inventori(self::$pdo);
                    $InventoriInfo = $Inventori->get_item_detail($RDIValue['obat']);

                    $racikan_detail['response_data'][$RDIKey]['detail'] = $InventoriInfo['response_data'][0];
                }
                $racikan['response_data'][$RDKey]['detail'] = $racikan_detail['response_data'];
            }
            $data['response_data'][$key]['racikan'] = $racikan['response_data'];
            $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }

        return $data;
    }

    private function verifikasi_resep_2($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $resepChangedRecord = array();
        $racikanChangedRecord = array();
        $invoice_detail = array();

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
            ->order(array(
                'created_at' => 'DESC'
            ))
            ->execute();

        if (count($InvoiceCheck['response_data']) > 0) {
            $TargetInvoice = $InvoiceCheck['response_data'][0]['uid'];
        } else {
            $InvMasterParam['keterangan'] = '';
            $NewInvoice = $Invoice->create_invoice($InvMasterParam);
            $TargetInvoice = $NewInvoice['response_unique'];
        }

        foreach ($parameter['resep'] as $key => $value) {
            $resepChange = self::$query->insert('resep_change_log', array(
                'resep' => $parameter['uid'],
                'verifikator' => $UserData['data']->uid,
                'item' => $value['obat'],
                'qty' => floatval($value['jumlah']),
                'aturan_pakai' => intval($value['aturan_pakai']),
                'signa_qty' => floatval($value['signa_qty']),
                'signa_pakai' => floatval($value['signa_pakai']),
                'keterangan' => $value['keterangan'],
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date(),
            ))
                ->execute();
            array_push($resepChangedRecord, $resepChange);

            $AppendInvoice = $Invoice->append_invoice(array(
                'invoice' => $TargetInvoice,
                'item' => $value['obat'],
                'item_origin' => 'master_inv',
                'qty' => floatval($value['jumlah']),
                'harga' => floatval($value['harga']),
                'status_bayar' => ($parameter['penjamin'] === __UIDPENJAMINUMUM__) ? 'N' : 'Y',
                'subtotal' => floatval($value['harga']) * floatval($value['jumlah']),
                'discount' => 0,
                'discount_type' => 'N',
                'pasien' => $parameter['pasien'],
                'penjamin' => $parameter['penjamin'],
                'billing_group' => 'obat',
                'keterangan' => 'Biaya resep obat',
                'departemen' => $parameter['departemen']
            ));
            array_push($invoice_detail, $AppendInvoice);

        }

        foreach ($parameter['racikan'] as $key => $value) {
            $racikanChange = self::$query->insert('racikan_change_log', array(
                'racikan' => $value['racikan_uid'],
                'jumlah' => $value['jumlah'],
                'signa_qty' => $value['signa_qty'],
                'signa_pakai' => $value['signa_pakai'],
                'keterangan' => $value['keterangan'],
                'aturan_pakai' => $value['aturan_pakai'],
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();

            foreach ($value['racikan_komposisi'] as $KKey => $KValue) {
                $racikanDetailChange = self::$query->insert('racikan_detail_change_log', array(
                    'racikan' => $value['racikan_uid'],
                    'obat' => $KValue['obat'],
                    'jumlah' => $KValue['jumlah'],
                    'kekuatan' => $KValue['kekuatan'],
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();

                $AppendInvoice = $Invoice->append_invoice(array(
                    'invoice' => $TargetInvoice,
                    'item' => $KValue['obat'],
                    'item_origin' => 'master_inv',
                    //'qty' => floatval($value['jumlah']),
                    'qty' => floatval($KValue['jumlah']),
                    'harga' => floatval($KValue['harga']),
                    'status_bayar' => ($parameter['penjamin'] === __UIDPENJAMINUMUM__) ? 'N' : 'Y',
                    'subtotal' => floatval($KValue['harga']) * floatval($value['jumlah']),
                    'discount' => 0,
                    'discount_type' => 'N',
                    'pasien' => $parameter['pasien'],
                    'billing_group' => 'obat',
                    'penjamin' => $parameter['penjamin'],
                    'keterangan' => 'Biaya racikan obat',
                    'departemen' => $parameter['departemen']
                ));
                array_push($invoice_detail, $AppendInvoice);
            }

            array_push($racikanChangedRecord, $racikanChange);
        }




        $UpdateStatusResep = self::$query->update('resep', array(
            'status_resep' => ($parameter['penjamin'] === __UIDPENJAMINUMUM__) ? 'K' : 'L'
        ))
            ->where(array(
                'resep.uid' => '= ?',
                'AND',
                'resep.deleted_at' => 'IS NULL'
            ), array(
                $parameter['uid']
            ))
            ->execute();

        //Update Antrian
        $AntrianNomor = self::$query->update('antrian_nomor', array(
            'status' => ($parameter['penjamin'] === __UIDPENJAMINUMUM__) ? 'K' : 'A'
        ))
            ->where(array(
                'antrian_nomor.kunjungan' => '= ?',
                'AND',
                'antrian_nomor.pasien' => '= ?',
            ), array(
                $parameter['kunjungan'],
                $parameter['pasien']
            ))
            ->execute();

        return array(
            'resep'=>  $resepChangedRecord,
            'racikan'=>  $racikanChangedRecord,
            'antrian' => $AntrianNomor,
            'invoice' => $TargetInvoice,
            'invoice_detail' => $invoice_detail
        );
    }

    private function verifikasi_resep($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $Invoice = new Invoice(self::$pdo);

        $checkerObatBiasa = array(); //Buat check uid obat lama
        $obatBiasaMeta = array();    //Buat Meta Data Obat Lama

        //Tagihan Resep Biasa
        //Check tagihan terakhir kunjungan
        $InvMasterParam = $parameter;

        $InvoiceCheck = self::$query->select('invoice', array(
            'uid'
        ))
            ->where(array(
                'invoice.kunjungan' => '= ?',
                'AND',
                'invoice.deleted_at' => 'IS NULL'
            ), array(
                $InvMasterParam['kunjungan']
            ))
            ->order(array(
                'created_at' => 'DESC'
            ))
            ->execute();


        if (count($InvoiceCheck['response_data']) > 0) {
            $TargetInvoice = $InvoiceCheck['response_data'][0]['uid'];
        } else {
            $InvMasterParam['keterangan'] = '';
            $NewInvoice = $Invoice->create_invoice($InvMasterParam);
            $TargetInvoice = $NewInvoice['response_unique'];
        }
















        //===============================Proses
        //Reset Resep Detail
        $deleteAllResep = self::$query->update('resep_detail', array(
            'deleted_at' => parent::format_date()
        ))
            ->where(array(
                'resep_detail.resep' => '= ?'
            ), array(
                $parameter['resep']
            ))
            ->execute();


        $old_resep_detail = self::$query->select('resep_detail', array(
            'id',
            'resep',
            'obat',
            'harga',
            'signa_qty',
            'signa_pakai',
            'qty',
            'satuan',
            'status',
            'penjamin'
        ))
            ->where(array(
                'resep_detail.resep' => '= ?'
            ), array(
                $parameter['resep']
            ))
            ->execute();

        //Assign old data
        foreach ($old_resep_detail['response_data'] as $key => $value) {
            if (!in_array($value['obat'], $checkerObatBiasa)) {
                if ($value['obat'] != null) {
                    array_push($checkerObatBiasa, $value['obat']);
                    $obatBiasaMeta[$value['obat']]['id'] = $value['id'];
                    $obatBiasaMeta[$value['obat']]['qty'] = $value['qty'];
                    $obatBiasaMeta[$value['obat']]['signa_qty'] = $value['signa_qty'];
                    $obatBiasaMeta[$value['obat']]['signa_pakai'] = $value['signa_pakai'];
                }
            }
        }

        /*
         * $obatBiasaMeta, uniqueUID = $checkerObatBiasa
         *
         * key = uid obat
         *
         * {
         *      id, qty, signa_qty, qty_pakai
         * }
         * */

        $obatChanged = array();
        $obatFix = array();
        $updateStatus = '';

        foreach ($parameter['detail'] as $key => $value) {
            //Cek apakah ada perubahan
            if (in_array($value['obat'], $checkerObatBiasa)) {
                //check signa dan jumlah
                if (
                    floatval($value['signa_qty']) != floatval($obatBiasaMeta[$value['obat']]['signa_qty']) ||
                    floatval($value['signa_pakai']) != floatval($obatBiasaMeta[$value['obat']]['signa_pakai']) ||
                    floatval($value['jumlah']) != floatval($obatBiasaMeta[$value['obat']]['qty'])
                ) {
                    if (!isset($obatChanged[$value['obat']])) {
                        $obatChanged[$value['obat']] = array();
                    }
                    $obatChanged[$value['obat']]['qty'] = $value['jumlah'];
                    $obatChanged[$value['obat']]['signa_qty'] = $value['signa_qty'];
                    $obatChanged[$value['obat']]['signa_pakai'] = $value['signa_pakai'];
                } else {
                    if (!isset($obatFix[$value['obat']])) {
                        $obatFix[$value['obat']] = array();
                    }
                    $obatFix[$value['obat']]['qty'] = $value['jumlah'];
                    $obatFix[$value['obat']]['signa_qty'] = $value['signa_qty'];
                    $obatFix[$value['obat']]['signa_pakai'] = $value['signa_pakai'];
                }
                $updateResep = self::update_resep(array(
                    'resep' => $parameter['resep'],
                    'id' => $obatBiasaMeta[$value['obat']]['id'],
                    'obat' => $value['obat'],
                    'signa_qty' => $value['signa_qty'],
                    'signa_pakai' => $value['signa_pakai'],
                    'harga' => $value['harga_after_profit'],
                    'qty' => $value['jumlah'],
                    'deleted_at' => NULL
                ));
            } else {
                //Tetap catat yang lama
                /*if (!isset($obatFix[$value['obat']])) {
                    $obatFix[$value['obat']] = array();
                }

                $obatFix[$value['obat']]['qty'] = $value['jumlah'];
                $obatFix[$value['obat']]['signa_qty'] = $value['signa_qty'];
                $obatFix[$value['obat']]['signa_pakai'] = $value['signa_pakai'];*/

                //Uda pasti beda (ada tambahan), masukkan ke resep detail
                if (!isset($obatChanged[$value['obat']])) {
                    $obatChanged[$value['obat']] = array();
                }

                $obatChanged[$value['obat']]['qty'] = $value['jumlah'];
                $obatChanged[$value['obat']]['signa_qty'] = $value['signa_qty'];
                $obatChanged[$value['obat']]['signa_pakai'] = $value['signa_pakai'];

                //Get Satuan
                $Inventori = new Inventori(self::$pdo);
                $InventoriDetail = $Inventori::get_item_detail($value['obat']);

                $worker = self::$query->insert('resep_detail', array(
                    'resep' => $parameter['resep'],
                    'obat' => $value['obat'],
                    'harga' => floatval($value['harga_after_profit']),
                    'signa_qty' => $value['signa_qty'],
                    'signa_pakai' => $value['signa_pakai'],
                    'qty' => $value['jumlah'],
                    'satuan' => $InventoriDetail['response_data'][0]['satuan_terkecil'],
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date(),
                    'aturan_pakai' => 0,
                    'status' => 'K'
                ))
                    ->execute();
            }


            //Jika ada tambah permintaan revisi pada dokter bersangkutan
            //Tidak mungkin menunggu dokter karena kadang dokter sudah pulang atau sudah tidak ditempat
            //Dapat menunggu proses verifikasi besok karena dokter sudah dikabari melalui telepon
            //Masukkan ke dalam tagihan langsung


            //Auto amprah jika batch dari gudang
            if ($value['batch'] == __GUDANG_APOTEK__) {
                //Update status resep detail menjadi proses
                $update_detail = self::$query->update('resep_detail', array(
                    'status' => 'A'
                ))
                    ->where(array(
                        'resep_detail.id' => '= ?',
                        'AND',
                        'resep_detail.resep' => '= ?',
                        'AND',
                        'resep_detail.deleted_at' => 'IS NULL'
                    ), array(
                        $obatBiasaMeta[$value['obat']]['id'],
                        $parameter['resep']
                    ))
                    ->execute();
            } else {
                //Update status resep detail menjadi proses
                $update_detail = self::$query->update('resep_detail', array(
                    'status' => 'K'
                ))
                    ->where(array(
                        'resep_detail.id' => '= ?',
                        'AND',
                        'resep_detail.resep' => '= ?',
                        'AND',
                        'resep_detail.deleted_at' => 'IS NULL'
                    ), array(
                        $obatBiasaMeta[$value['obat']]['id'],
                        $parameter['resep']
                    ))
                    ->execute();
            }

            //Assign invoice item
            //cek Pelunasan penjamin non umum. Status auto bayar jika non umum
            $invo_detail = $parameter;
            //Cek penjamin utama pasien
            if ($parameter['penjamin'] == __UIDPENJAMINUMUM__) {
                $invo_detail['invoice'] = $TargetInvoice;
                $invo_detail['item'] = $value['obat'];
                $invo_detail['item_origin'] = 'master_inv';
                $invo_detail['qty'] = $value['jumlah'];
                $invo_detail['harga'] = $value['harga_after_profit'];
                $invo_detail['status_bayar'] = 'N';
                $invo_detail['subtotal'] = $value['harga_after_profit'] * $value['jumlah'];
                $invo_detail['discount'] = 0;
                $invo_detail['discount_type'] = 'N';
                $invo_detail['keterangan'] = 'Biaya obat';
            } else {
                $invo_detail['invoice'] = $TargetInvoice;
                $invo_detail['item'] = $value['obat'];
                $invo_detail['item_origin'] = 'master_inv';
                $invo_detail['qty'] = $value['jumlah'];
                $invo_detail['harga'] = $value['harga_after_profit'];
                $invo_detail['status_bayar'] = 'Y';
                $invo_detail['subtotal'] = $value['harga_after_profit'] * $value['jumlah'];
                $invo_detail['billing_group'] = 'obat';
                $invo_detail['discount'] = 0;
                $invo_detail['discount_type'] = 'N';
                $invo_detail['keterangan'] = 'Biaya obat';
            }

            $AppendInvoice = $Invoice->append_invoice($invo_detail);
        } // End Loop Resep Biasa


        //Racikan manager

        $checkerObatRacikan = array(); //Buat check uid obat lama
        $racikanChange = array();
        $oldRacikan = array();

        foreach ($parameter['racikan'] as $key => $value) {
            $old_racikan = self::$query->select('racikan', array(
                'uid',
                'kode',
                'signa_qty',
                'signa_pakai',
                'qty'
            ))
                ->where(array(
                    'racikan.uid' => '= ?',
                    'AND',
                    'racikan.asesmen' => '= ?',
                    'AND',
                    'racikan.status' => '= ?',
                    'AND',
                    'racikan.deleted_at' => 'IS NULL'
                ), array(
                    $value['group_racikan'],
                    $parameter['asesmen'],
                    'N'
                ))
                ->execute();
            $old_racikan_data = $old_racikan['response_data'][0];

            $old_racikan_detail = self::$query->select('racikan_detail', array(
                'id',
                'asesmen',
                'obat',
                'pembulatan',
                'harga',
                'takar_bulat'
            ))
                ->where(array(
                    'racikan_detail.deleted_at' => 'IS NULL',
                    'AND',
                    'racikan_detail.racikan' => '= ?',
                    'AND',
                    'racikan_detail.asesmen' => '= ?',
                    'AND',
                    'racikan_detail.obat' => '= ?'
                ), array(
                    $value['group_racikan'],
                    $parameter['asesmen'],
                    $value['obat']
                ))
                ->execute();
            if (count($old_racikan_detail['response_data']) > 0) {
                $racikanCheck = $old_racikan_detail['response_data'][0];
                if (
                    $value['total'] != $old_racikan_data['qty'] * $racikanCheck['pembulatan']
                ) {
                    if (!isset($racikanChange[$value['group_racikan']])) {
                        $racikanChange[$value['group_racikan']] = array();
                    }

                    if (!in_array($value['obat'], $racikanChange[$value['group_racikan']])) {
                        array_push($racikanChange[$value['group_racikan']], array(
                            'racikan' => $value['group_racikan'],
                            'obat' => $value['obat'],
                            'batch' => $value['batch'],
                            'harga' => $value['harga'],
                            'jumlah' => $value['jumlah']
                        ));
                    }
                } else {
                    if (!isset($checkerObatRacikan[$value['group_racikan']])) {
                        $checkerObatRacikan[$value['group_racikan']] = array();
                    }

                    if (!in_array($value['obat'], $checkerObatRacikan[$value['group_racikan']])) {
                        array_push($checkerObatRacikan[$value['group_racikan']], array(
                            'obat' => $value['obat'],
                            'batch' => $value['batch'],
                            'harga' => $value['harga'],
                            'jumlah' => $value['jumlah']
                        ));
                    }
                }
            } else {
                if (!isset($racikanChange[$value['group_racikan']])) {
                    $racikanChange[$value['group_racikan']] = array();
                }

                if (!in_array($value['obat'], $racikanChange[$value['group_racikan']])) {
                    array_push($racikanChange[$value['group_racikan']], array(
                        'racikan' => $value['group_racikan'],
                        'obat' => $value['obat'],
                        'batch' => $value['batch'],
                        'harga' => $value['harga'],
                        'jumlah' => $value['jumlah']
                    ));
                }
            }

            $updateRacikan = self::update_racikan(array(
                'racikan' => $parameter['group_racikan'],
                'asesmen' => $parameter['asesmen'],
                'qty' => $value['jumlah'],
                'obat' => $value['obat'],
                'takar_bulat' => $value['bulat'],
                'takar_decimal' => $value['decimal'],
                'pembulatan' => $value['pembulatan'],
                'ratio' => $value['ratio'],
                'harga' => $value['harga'],
                'signa_qty' => $value['signa_qty'],
                'signa_pakai' => $value['signa_pakai']
            ));

            $old_racikan_data['response_data'][0]['detail'] = $old_racikan_detail['response_data'];

            //Tagihan Racikan
            $parameter['invoice'] = $TargetInvoice;
            $parameter['item'] = $value['obat'];
            $parameter['item_origin'] = 'master_inv';
            $parameter['qty'] = $value['jumlah'];
            $parameter['harga'] = $value['harga'];
            $parameter['status_bayar'] = 'N';
            $parameter['subtotal'] = $value['harga'] * $value['jumlah'];
            $parameter['billing_group'] = 'obat';
            $parameter['discount'] = 0;
            $parameter['discount_type'] = 'N';
            $parameter['keterangan'] = '';

            $AppendInvoice = $Invoice::append_invoice($parameter);
        }



        //TODO: Buka status tagihan untuk BPJS (apotek, lab, radio)

        //Update resep master menjadi kasir
        $Resep = self::$query->update('resep', array(
            'status_resep' => ($parameter['penjamin'] === __UIDPENJAMINUMUM__) ? 'K' : 'L',
            'verifikator' => $UserData['data']->uid
        ))
            ->where(array(
                'resep.uid' => '= ?',
                'AND',
                'resep.deleted_at' => 'IS NULL'
            ), array(
                $parameter['resep']
            ))
            ->execute();
        $Racikan = self::$query->update('racikan', array(
            'status' => ($parameter['penjamin'] === __UIDPENJAMINUMUM__) ? 'K' : 'L'
        ))
            ->where(array(
                'racikan.asesmen' => '= ?'
            ), array(
                $parameter['asesmen']
            ))
            ->execute();

        $resepChangedLog = array();
        $racikanChangedLog = array();

        //Save perubahan resep biasa
        foreach($obatChanged as $key => $value) {
            $resepChangeSave = self::$query->insert('resep_change_log', array(
                'resep' => $parameter['resep'],
                'verifikator' => $UserData['data']->uid,
                'dokter' => $parameter['dokter'],
                'status' => 'N',
                'keterangan' => '',
                'item' => $key,
                'qty' => floatval($value['qty']),
                'signa_qty' => floatval($value['signa_qty']),
                'signa_pakai' => floatval($value['signa_pakai']),
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();
            array_push($resepChangedLog, $resepChangeSave);
        }

        foreach($racikanChange as $key => $value) {
            foreach ($value as $KKey => $KValue) {
                $racikanChangeSave = self::$query->insert('racikan_change_log', array(
                    'racikan' => $KValue['racikan'],
                    'obat' => $KValue['obat'],
                    'qty' => floatval($KValue['jumlah']),
                    'status' => 'N',
                    'keterangan' => ''
                ))
                    ->execute();
                array_push($racikanChangedLog, $racikanChangeSave);
            }
        }

        $Resep['response_change_racikan'] = $racikanChange;
        $Resep['response_changed_resep'] = $obatChanged;
        $Resep['change_resep_result'] = $resepChangedLog;
        $Resep['change_racikan_result'] = $racikanChangedLog;
        $Resep['racikan_update'] = $Racikan;

        //Update status pembayaran pasien

        $AntrianNomor = self::$query->update('antrian_nomor', array(
            'status' => ($parameter['penjamin'] === __UIDPENJAMINUMUM__) ? 'K' : 'D'
        ))
            ->where(array(
                'antrian_nomor.kunjungan' => '= ?',
                'AND',
                'antrian_nomor.pasien' => '= ?',
            ), array(
                $parameter['kunjungan'],
                $parameter['pasien']
            ))
            ->execute();

        return $Resep;
    }

    private function update_resep($parameter)
    {
        $checkerResep = self::$query->select('resep_detail', array(
            'id'
        ))
            ->where(array(
                'resep_detail.id' => '= ?',
                'AND',
                'resep_detail.resep' => '= ?'
            ), array(
                $parameter['id'],
                $parameter['resep']
            ))
            ->execute();
        if (count($checkerResep['response_data']) > 0) {
            $worker = self::$query->update('resep_detail', array(
                'obat' => $parameter['obat'],
                'harga' => $parameter['harga'],
                'signa_qty' => $parameter['signa_qty'],
                'signa_pakai' => $parameter['signa_pakai'],
                'qty' => $parameter['qty'],
                'deleted_at' => NULL
            ))
                ->where(array(
                    'resep_detail.id' => '= ?',
                    'AND',
                    'resep_detail.resep' => '= ?'
                ), array(
                    $parameter['id'],
                    $parameter['resep']
                ))
                ->execute();
        } else {
            //Get Satuan
            $Inventori = new Inventori(self::$pdo);
            $InventoriDetail = $Inventori::get_item_detail($parameter['obat']);

            $worker = self::$query->insert('resep_detail', array(
                'resep' => $parameter['resep'],
                'obat' => $parameter['obat'],
                'harga' => $parameter['harga'],
                'signa_qty' => $parameter['signa_qty'],
                'signa_pakai' => $parameter['signa_pakai'],
                'qty' => $parameter['qty'],
                'satuan' => $InventoriDetail['response_data'][0]['satuan_terkecil'],
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date(),
                'aturan_pakai' => '',
                'status' => 'K'
            ))
                ->execute();
        }
        return $worker;
    }

    private function update_racikan($parameter)
    {
        //Update Racikan
        /*$racikan = self::$query->update('racikan', array(
            'qty' => $parameter['qty']
        ))
        ->where(array(
            'racikan.uid' => '= ?',
            'AND',
            'racikan.asesmen' => '= ?',
            'AND',
            'racikan.deleted_at' => 'IS NULL'
        ), array(
            $parameter['racikan'],
            $parameter['asesmen']
        ))
        ->execute();

        if($racikan['response_result'] > 0) {
            $racikan_detail = self::$query->update('racikan_detail', array(
                //
            ))
            ->where(array(
                'racikan_detail.id' => '= ?'
            ), array())
            ->execute();
        }*/
        //Update Racikan Detail
    }
}