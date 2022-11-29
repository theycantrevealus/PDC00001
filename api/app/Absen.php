<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Absen extends Utility
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
            case 'update_absen':
                return self::update_absen($parameter);
                break;
            case 'get_absen':
                return self::get_absen($parameter);
                break;
        }
    }

    function get_absen($parameter) {
        
        $paramData = array(
            'absen.deleted_at' => 'IS NULL',
            'AND',
            'absen.tanggal' => 'BETWEEN ? AND ?',
        );

        $paramValue = array(
            $parameter['from'], $parameter['to']
        );

        if (intval($parameter['length']) < 0) {
            $data = self::$query->select('absen', array(
                'id',
                'tanggal',
                'jam_masuk',
                'jam_keluar',
                'created_at',
            ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('absen', array(
                'id',
                'tanggal',
                'jam_masuk',
                'jam_keluar',
                'created_at',
            ))              
                ->limit(intval($parameter['length']))
                ->order(array(
                    'absen.created_at' => 'DESC'
                ))
                ->where($paramData, $paramValue)
                ->execute();
        }

        $dataResult = array();
        $data['response_draw'] = intval($parameter['draw']);
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {

            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }

        $dataCount = self::$query->select('absen', array(
            'id'
        ))
            ->where($paramData, $paramValue)
            ->execute();


        $data['recordsTotal'] = count($dataCount['response_data']);
        $data['recordsFiltered'] = count($data['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    function update_absen($parameter){
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        //return $UserData['data']->uid;
        $Absen = self::$query->select('absen', array(
            'id',
            'pegawai',
            'tanggal',
            'jam_masuk',
            'jam_keluar'
          ))
            ->where(array(
              'absen.tanggal' => '= ?',
              'AND',
              'absen.deleted_at' => 'IS NULL'
            ), array(
              date('Y-m-d')
            ))
            ->execute();
        
        $Tambah_Absen = array();

        if(count($Absen['response_data']) > 0 ) {

            if($parameter['absen'] === 'masuk'){
                $Tambah_Absen = self::$query->update('absen', array(
                    'jam_masuk' => date('H:i:s'),
                    'updated_at' => parent::format_date()
                ))
                    ->where(array(
                        'absen.tanggal' => '= ?',
                        'AND',
                        'absen.deleted_at' => 'IS NULL'
                    ), array(
                        date('Y-m-d')
                    ))
                    ->execute(); 
            }else if(
                $parameter['absen'] === 'keluar' &&
                $Absen['response_data'][0]['jam_keluar'] === null &&
                $Absen['response_data'][0]['jam_masuk'] !== null
            ) {
                $Tambah_Absen = self::$query->update('absen', array(
                    'jam_keluar' => date('H:i:s'),
                    'updated_at' => parent::format_date()
                ))
                    ->where(array(
                        'absen.tanggal' => '= ?',
                        'AND',
                        'absen.deleted_at' => 'IS NULL'
                    ), array(
                        date('Y-m-d')
                    ))
                    ->execute(); 
            }

        } else {
            $Tambah_Absen = self::$query->insert('absen', array(
                'pegawai' => $UserData['data']->uid,
                'tanggal' => date('Y-m-d'),
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();
        }

        return $Tambah_Absen;
    
    }
}