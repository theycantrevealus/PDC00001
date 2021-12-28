<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Penjamin as Penjamin;
use PondokCoder\Query as Query;
use PondokCoder\Pasien as Pasien;
use PondokCoder\Pegawai as Pegawai;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Fisioterapi extends Utility
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
        switch ($parameter[1])
        {
            case 'history_terapi':
                return self::get_terapi($parameter[2]);
                break;
            default:
                break;
        }
    }

    public function __POST__($parameter = array())
    {
        switch ($parameter['request'])
        {
            case 'history_terapi':
                return self::get_terapi($parameter);
                break;
            case 'tambah_terapi':
                return self::tambah_terapi($parameter);
                break;
            default:
                break;
        }
    }

    private function tambah_terapi($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);
        $uid = parent::gen_uuid();
        $worker = self::$query->insert('fis_terapi', array(
            'uid' => $uid,
            'tanggal' => parent::format_date(),
            'kunjungan' => $parameter['kunjungan'],
            'pasien' => $parameter['pasien'],
            'asesmen' => $parameter['asesmen'],
            'terapis' => $UserData['data']->uid,
            'program' => $parameter['program'],
            'dokter' => $parameter['dokter']['uid'],
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->execute();
        return $worker;
    }

    public function get_terapi($parameter)
    {
        if ($parameter['length'] < 0) {
            $data = self::$query->select('fis_terapi', array(
                'uid',
                'tanggal',
                'program',
                'pasien',
                'dokter',
                'terapis',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'fis_terapi.pasien' => '= ?',
                    'AND',
                    'fis_terapi.deleted_at' => 'IS NULL'
                ), array(
                    $parameter['pasien']
                ))
                ->execute();
        } else {
            $data = self::$query->select('fis_terapi', array(
                'uid',
                'tanggal',
                'program',
                'pasien',
                'dokter',
                'terapis',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'fis_terapi.pasien' => '= ?',
                    'AND',
                    'fis_terapi.deleted_at' => 'IS NULL'
                ), array(
                    $parameter['pasien']
                ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {


            $data['response_data'][$key]['autonum'] = $autonum;
            $Pasien = new Pasien(self::$pdo);
            $data['response_data'][$key]['pasien'] = $Pasien::get_pasien_detail('pasien', $value['pasien'])['response_data'][0];

            $Dokter = new Pegawai(self::$pdo);
            $data['response_data'][$key]['dokter'] = $Dokter::get_detail($value['dokter'])['response_data'][0];


            $Terapis = new Pegawai(self::$pdo);
            $data['response_data'][$key]['terapis'] = $Terapis::get_detail($value['terapis'])['response_data'][0];

            $autonum++;
        }

        $itemTotal = self::$query->select('fis_terapi', array(
            'uid'
        ))
            ->where(array(
                'fis_terapi.pasien' => '= ?',
                'AND',
                'fis_terapi.deleted_at' => 'IS NULL'
            ), array(
                $parameter['pasien']
            ))
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }
}