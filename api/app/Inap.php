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
            default:
                return self::get_all($parameter);
        }
    }

    private function get_ns_detail($parameter) {
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
                'kode as kode_unit'
            ))
            ->on(array(
                array('nurse_station.unit', '=', 'master_unit.uid')
            ))
            ->where(array(
                'nurse_station.deleted_at' => 'IS NULL',
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

                //Todo: Auto Gudang dan Stok Point
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
        $Pasien = new Pasien(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);
        $Ruangan = new Ruangan(self::$pdo);
        $Bed = new Bed(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;

            //Pasien
            $PasienDetail = $Pasien->get_pasien_detail('pasien', $value['pasien']);
            $data['response_data'][$key]['pasien'] = $PasienDetail['response_data'][0];

            //Dokter
            $PegawaiDetail = $Pegawai->get_detail($value['dokter']);
            $data['response_data'][$key]['dokter'] = $PegawaiDetail['response_data'][0];

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
                ->order(array(
                    'nurse_station_ranjang.created_at' => 'DESC'
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
        $allowSave = false;
        //Check Sebelum Save
        $Ranjang = self::$query->select('nurse_station_ranjang', array(
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
                    $parameter['uid']
                ))
                ->execute();
            if(count($CheckRanjang['response_data']) > 0) {
                $allowSave = false;
                break;
            } else {
                $allowSave = true;
            }
        }

        if($allowSave) {
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
                'kamar' => $parameter['kamar'],
                'bed' => $parameter['bed'],
                'keterangan' => $parameter['keterangan'],
                'nurse_station' => $NurseStation['response_data'][0]['nurse_station'],
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