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
            case 'detail':
                return self::get_detail($parameter[2]);
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
            $uid = parent::gen_uuid();
            $process = self::$query->insert('nurse_station', array(
                'uid' => $uid,
                'nama' => $parameter['nama'],
                'kode' => $parameter['kode'],
                'unit' => $parameter['unit'],
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
            }
            return $process;
        }
    }

    private function get_nurse_station($parameter) {
        //Todo: Master Nurse Station
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'nurse_station.deleted_at' => 'IS NULL',
                'AND',
                '(nurse_station.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'nurse_station.kode' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
            );

            $paramValue = array();
        } else {
            $paramData = array(
                'nurse_station.deleted_at' => 'IS NULL'
            );

            $paramValue = array();
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
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }



        $itemTotal = self::$query->select('nurse_station', array(
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
            $paramData = array(
                'rawat_inap.deleted_at' => 'IS NULL',
                'AND',
                /*'rawat_inap.dokter' => '= ?',
                'AND',*/
                'rawat_inap.waktu_keluar' => 'IS NULL'
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
                /*'AND',
                'master_inv.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''*/
            );

            //$paramValue = array($UserData['data']->uid);
            $paramValue = array();
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
                'penjamin',
                'created_at',
                'updated_at'
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
                'penjamin',
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
            $data['response_data'][$key]['autonum'] = $autonum;

            //Pasien
            $Pasien = new Pasien(self::$pdo);
            $PasienDetail = $Pasien::get_pasien_detail('pasien', $value['pasien']);
            $data['response_data'][$key]['pasien'] = $PasienDetail['response_data'][0];

            //Dokter
            $Pegawai = new Pegawai(self::$pdo);
            $PegawaiDetail = $Pegawai->get_detail($value['dokter']);
            $data['response_data'][$key]['dokter'] = $PegawaiDetail['response_data'][0];

            //Penjamin
            $Penjamin = new Penjamin(self::$pdo);
            $PenjaminDetail = $Penjamin->get_penjamin_detail($value['penjamin']);
            $data['response_data'][$key]['penjamin'] = $PenjaminDetail['response_data'][0];

            //Ruangan
            $Ruangan = new Ruangan(self::$pdo);
            $RuanganDetail = $Ruangan->get_ruangan_detail('master_unit_ruangan', $value['kamar']);
            $data['response_data'][$key]['kamar'] = $RuanganDetail['response_data'][0];

            //Bed
            $Bed = new Bed(self::$pdo);
            $BedDetail = $Bed->get_bed_detail('master_unit_bed', $value['bed']);
            $data['response_data'][$key]['bed'] = $BedDetail['response_data'][0];

            $data['response_data'][$key]['waktu_masuk_tanggal'] = date('d F Y', strtotime($value['waktu_masuk']));
            $data['response_data'][$key]['waktu_masuk_jam'] = date('H:i', strtotime($value['waktu_masuk']));


            $autonum++;
        }

        $itemTotal = self::$query->select('rawat_inap', array(
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

    private function edit_inap($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $old = self::$query->select('rawat_inap', array(
            'uid', 'pasien', 'dokter', 'penjamin', 'kunjungan', 'waktu_masuk', 'waktu_keluar', 'kamar', 'bed', 'keterangan',
            'created_at', 'updated_at', 'deleted_at', 'jenis_pulang', 'alasan_pulang'
        ))
            ->where(array(
                'rawat_inap.deleted_at' => 'IS NULL',
                'AND',
                'rawat_inap.uid' => '= ?'
            ), array(
                $parameter['uid']
            ))
            ->execute();
        $worker = self::$query->update('rawat_inap', array(
            'kamar' => $parameter['kamar'],
            'bed' => $parameter['bed'],
            'keterangan' => $parameter['keterangan'],
            'updated_at' => parent::format_date()
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
                        'keterangan' => 'Tagihan tindakan perobatan'
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
        return $worker;
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