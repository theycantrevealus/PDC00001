<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Mock extends Utility
{
    static $pdo;
    static $query;

    protected static function getConn()
    {
        return self::$pdo;
    }

    public function __construct($connection) {
        self::$pdo = $connection;
        self::$query = new Query(self::$pdo);
    }

    public function __POST__($parameter = array()) {
        switch($parameter['request']) {
            case 'asesmen':
                return self::mock_asesmen($parameter);
                break;
        }
    }

    private function mock_asesmen($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $Poli = new Poli(self::$pdo);
        $PoliList = $Poli->get_poli()['response_data'];
        $__mock_poli_max = count($PoliList) - 1;


        for($a = 1; $a < intval($parameter['data_length']); $a++) {

            $__mock_poli_id = rand(0, $__mock_poli_max);

            $uid = parent::gen_uuid();
            $data = self::$query->insert('asesmen', array(
                'uid' => $uid,
                'poli' => $PoliList[$__mock_poli_id]['uid'],
                'kunjungan',
                'antrian',
                'pasien',
                'dokter',
                'perawat',
                'status',
                'created_at'
            ))
                ->execute();
        }

    }
}
?>