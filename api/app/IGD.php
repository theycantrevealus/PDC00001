<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
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
            default:
                return self::get_all($parameter);
        }
    }

    private function get_detail() {
        //
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
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

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