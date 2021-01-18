<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Laporan extends Utility
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

    public function __POST__($parameter = array())
    {
        switch ($parameter['request']) {
            case 'kunjungan_rawat_jalan':
                return self::kunjungan_rawat_jalan($parameter);
                break;
        }
    }

    private function kunjungan_rawat_jalan($parameter) {

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'kunjungan.deleted_at' => 'IS NULL',
                'AND',
                'kunjungan.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array(
                $parameter['from'], $parameter['to']
            );
        } else {
            $paramData = array(
                'kunjungan.deleted_at' => 'IS NULL',
                'AND',
                'kunjungan.created_at' => 'BETWEEN ? AND ?'
            );

            $paramValue = array(
                $parameter['from'], $parameter['to']
            );
        }

        if (intval($parameter['length']) < 0) {
            $data = self::$query->select('kunjungan', array(
                'uid',
                'waktu_masuk',
                'waktu_keluar'
            ))
                ->join('antrian', array(
                    'uid',
                    'pasien'
                ))

                ->join('pasien', array(
                    'nama'
                ))

                ->on(array(
                    array('antrian.kunjungan', '=', 'kunjungan.uid'),
                    array('antrian.pasien', '=', 'pasien.uid')
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('kunjungan', array(
                'uid',
                'waktu_masuk',
                'waktu_keluar'
            ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->join('antrian', array(
                    'uid',
                    'pasien'
                ))

                ->join('pasien', array(
                    'nama'
                ))

                ->on(array(
                    array('antrian.kunjungan', '=', 'kunjungan.uid'),
                    array('antrian.pasien', '=', 'pasien.uid')
                ))
                ->where($paramData, $paramValue)
                ->execute();
        }
        $dataResult = array();
        $data['response_draw'] = intval($parameter['draw']);
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {

            $value['autonum'] = $autonum;
            $autonum++;
        }

        $KunjunganTotal = self::$query->select('kunjungan', array(
            'uid'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['response_data'] = $dataResult;
        $data['recordsTotal'] = count($KunjunganTotal['response_data']);
        $data['recordsFiltered'] = count($dataResult);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;

    }
}
?>