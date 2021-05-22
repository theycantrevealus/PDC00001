<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Contoh2 extends Utility
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
        switch($parameter['request']) {
            case 'uji':
                return self::testing($parameter);
                break;
        }
    }

    public function __GET__($parameter = array())
    {
        switch($parameter[1]) {
            case 'uji':
                return self::testing();
                break;
        }
    }


    private function testing($parameter) {
        $data = self::$query->select('pegawai', array(
            'uid',
            'nama'
        ))
            ->where(array(
                'pegawai.deleted_at' => 'IS NULL',
                'AND',
                'pegawai.email' => '= ?'
            ), array(
                $parameter['nama']
            ))
            ->execute();
        return $data;
    }
}