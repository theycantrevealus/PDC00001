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
                case 'detail_resep_lunas':
                    return self::detail_resep($parameter[2], 'L');
                    break;
                case 'lunas':
                    return self::get_resep('L');
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
                    return self::revisi_resep($parameter);
                    break;
                case 'verifikasi_resep':
                    return self::verifikasi_resep($parameter);
                    break;
                case 'get_resep_backend':
                    return self::get_resep_backend($parameter);
                    break;
                case 'get_resep_lunas_backend':
                    $parameter['status'] = 'L';
                    return self::get_resep_backend($parameter);
                    break;
                default:
                    return self::get_resep();
            }
        } catch (QueryException $e) {
            return 'Error => ' . $e;
        }
    }

    private function revisi_resep($parameter)
    {
        //
    }

    private function detail_resep($parameter, $status = 'N')
    {
        $data = self::$query->select('resep', array(
            'uid',
            'kunjungan',
            'antrian',
            'asesmen',
            'dokter',
            'pasien',
            'verifikator',
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

            //Departemen Info
            $Poli = new Poli(self::$pdo);
            $PoliInfo = $Poli::get_poli_detail($AntrianInfo['response_data'][0]['departemen']);
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

                    $InventoriBacth = $Inventori::get_item_batch($RDIValue['obat']);
                    $racikan_detail['response_data'][$RDIKey]['batch'] = $InventoriBacth['response_data'];
                }
                $racikan['response_data'][$RDKey]['detail'] = $racikan_detail['response_data'];
            }
            $data['response_data'][$key]['racikan'] = $racikan['response_data'];

            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }

        return $data;
    }

    private function get_resep_backend($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

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


        if ($parameter['length'] < 0) {
            $data = self::$query->select('resep', array(
                'uid',
                'kunjungan',
                'antrian',
                'asesmen',
                'dokter',
                'pasien',
                'verifikator',
                'total',
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
                'verifikator',
                'total',
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

        foreach ($data['response_data'] as $key => $value) {
            //Dokter Info
            $Pegawai = new Pegawai(self::$pdo);
            $PegawaiInfo = $Pegawai::get_detail($value['dokter']);
            $data['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

            //Get Antrian Detail
            $Antrian = new Antrian(self::$pdo);
            $AntrianInfo = $Antrian::get_antrian_detail('antrian', $value['antrian']);

            //Departemen Info
            $Poli = new Poli(self::$pdo);
            $PoliInfo = $Poli::get_poli_detail($AntrianInfo['response_data'][0]['departemen']);
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

            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }

        $itemTotal = self::$query->select('resep', array(
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

    private function get_resep($status = 'N')
    {
        $data = self::$query->select('resep', array(
            'uid',
            'kunjungan',
            'antrian',
            'asesmen',
            'dokter',
            'pasien',
            'verifikator',
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
            $PegawaiInfo = $Pegawai::get_detail($value['dokter']);
            $data['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

            //Get Antrian Detail
            $Antrian = new Antrian(self::$pdo);
            $AntrianInfo = $Antrian::get_antrian_detail('antrian', $value['antrian']);

            //Departemen Info
            $Poli = new Poli(self::$pdo);
            $PoliInfo = $Poli::get_poli_detail($AntrianInfo['response_data'][0]['departemen']);
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

            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }

        return $data;
    }

    private function verifikasi_resep($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);
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
            $NewInvoice = $Invoice::create_invoice($InvMasterParam);
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
                    'qty' => $value['jumlah']
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
                    'harga' => $value['harga_after_profit'],
                    'signa_qty' => $value['signa_qty'],
                    'signa_pakai' => $value['signa_pakai'],
                    'qty' => $value['jumlah'],
                    'satuan' => $InventoriDetail['response_data'][0]['satuan_terkecil'],
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date(),
                    'aturan_pakai' => '',
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
                $invo_detail['discount'] = 0;
                $invo_detail['discount_type'] = 'N';
                $invo_detail['keterangan'] = 'Biaya obat';
            }

            $AppendInvoice = $Invoice::append_invoice($invo_detail);
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
            $parameter['discount'] = 0;
            $parameter['discount_type'] = 'N';
            $parameter['keterangan'] = '';

            $AppendInvoice = $Invoice::append_invoice($parameter);
        }

        //Update resep master menjadi kasir
        $Resep = self::$query->update('resep', array(
            'status_resep' => 'K',
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
            'status' => 'K'
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