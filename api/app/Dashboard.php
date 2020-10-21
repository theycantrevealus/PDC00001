<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;

class Dashboard extends Utility
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
                case 'get_jumlah_antrian_resepsionis':
                    return self::get_jumlah_antrian_resepsionis();
                break;

                case 'get_jumlah_pasien_sedang_berobat':
                    return self::get_jumlah_pasien_sedang_berobat();
                break;

                case 'get_jumlah_pasien_selesai_berobat':
                    return self::get_jumlah_pasien_selesai_berobat();
                break;

                default:
                    # code...
                    break;
            }
        } catch (QueryException $e) {
            return 'Error => ' . $e;
        }
    }

    private static function get_jumlah_antrian_resepsionis()
    {
        $tgl = self::get_date_now();

        $data = self::$query
            ->select('antrian_nomor', array(
                'nomor_urut'
            ))
            ->where(
                array(
                    'antrian_nomor.status'              => '= ?',
                    'AND',
                    'DATE(antrian_nomor.created_at)'    => '= ?'
                ), 
                array(
                    'N',
                    $tgl
                )
            )
            ->execute();

        return $data;
    }

    private static function get_jumlah_pasien_sedang_berobat()
    {
        $tgl = self::get_date_now();

        $data = self::$query
            ->select('antrian', array(
                'uid'
            ))
            ->where(
                array(
                    'DATE(antrian.waktu_masuk)' => '= ?'
                ), 
                array(
                    $tgl
                )
            )
            ->execute();

        return $data;
    }

    private static function get_jumlah_pasien_selesai_berobat()
    {
        $tgl = self::get_date_now();

        $data = self::$query
            ->select('antrian', array(
                'uid'
            ))
            ->where(
                array(
                    'antrian.waktu_keluar'          =>  'IS NOT NULL',
                    'AND',
                    'DATE(antrian.waktu_keluar)'    => '= ?'
                ), 
                array(
                    $tgl
                )
            )
            ->execute();

        return $data;
    }
    
    static function get_date_now()
    {
        $date_now = parent::format_date();
        $arr_tgl = explode(" ", $date_now);
        $tgl = $arr_tgl[0];

        return $tgl;
    }
}