<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Penjamin as Penjamin;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Rujukan extends Utility
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
        }
    }

    public function __POST__ ($parameter = array())
    {
        switch ($parameter['request'])
        {
            case 'get_all':
                return self::get_all($parameter);
                break;
            case 'tambah_rujukan':
                return self::add_rujukan($parameter);
                break;
            default:
                return array();
        }
    }

    private function get_all($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'rujukan.deleted_at' => 'IS NULL'
            );

            $paramValue = array();
        } else {
            $paramData = array(
                'rujukan.deleted_at' => 'IS NULL'
                /*'rujukan.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''*/
            );

            $paramValue = array();
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('rujukan', array(
                'uid',
                'poli',
                'dokter',
                'pasien',
                'penjamin',
                'keterangan',
                'status',
                'pegawai_rekam_medis',
                'nomor_rujuk',
                'antrian',
                'jenis_layanan',
                'tipe_rujukan',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->order(array(
                    'status' => 'DESC'
                ))
                ->execute();
        } else {
            $data = self::$query->select('rujukan', array(
                'uid',
                'poli',
                'dokter',
                'pasien',
                'penjamin',
                'keterangan',
                'status',
                'pegawai_rekam_medis',
                'nomor_rujuk',
                'antrian',
                'jenis_layanan',
                'tipe_rujukan',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->order(array(
                    'status' => 'DESC'
                ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {


            $data['response_data'][$key]['autonum'] = $autonum;

            //Poli
            $Poli = new Poli(self::$pdo);
            $PoliDetail = $Poli::get_poli_detail($value['poli']);
            $data['response_data'][$key]['poli'] = $PoliDetail['response_data'][0];

            //Penjamin
            $Penjamin = new Penjamin(self::$pdo);
            $PenjaminDetail = $Penjamin::get_penjamin_detail($value['penjamin']);
            $data['response_data'][$key]['penjamin'] = $PenjaminDetail['response_data'][0];

            //Pasien
            $Pasien = new Pasien(self::$pdo);
            $PasienDetail = $Pasien->get_pasien_detail('pasien', $value['pasien']);
            $data['response_data'][$key]['pasien'] = $PasienDetail['response_data'][0];

            //Dokter
            $Pegawai = new Pegawai(self::$pdo);
            $DokterDetail = $Pegawai::get_detail($value['dokter']);
            $data['response_data'][$key]['dokter'] = $DokterDetail['response_data'][0];


            if(isset($value['pegawai_rekam_medis']))
            {
                //Pegawai
                $PegawaiDetail = $Pegawai::get_detail($value['pegawai_rekam_medis']);
                $data['response_data'][$key]['pegawai_rekam_medis'] = $PegawaiDetail['response_data'][0];
            }

            $Asesmen = new Asesmen(self::$pdo);
            $AsesmenDetail = $Asesmen->get_asesmen_medis($value['antrian']);
            $data['response_data'][$key]['asesmen'] = $AsesmenDetail['response_data'][0];

            $autonum++;
        }

        $itemTotal = self::$query->select('rujukan', array(
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

    private function get_detail($parameter)
    {
        //
    }

    private function add_rujukan($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $uid = parent::gen_uuid();
        $worker = self::$query->insert('rujukan', array(
            'uid' => $uid,
            'antrian' => $parameter['antrian'],
            'poli' => $parameter['poli'],
            'dokter' => $UserData['data']->uid,
            'pasien' => $parameter['pasien'],
            'penjamin' => $parameter['penjamin'],
            'keterangan' => $parameter['keterangan'],
            'jenis_layanan' => $parameter['jenis'],
            'tipe_rujukan' => $parameter['tipe'],
            'status' => 'N',
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->execute();

        if($worker['response_result'] > 0) {
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
                    'rujukan',
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

?>