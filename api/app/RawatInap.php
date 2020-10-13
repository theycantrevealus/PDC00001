<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;
use PondokCoder\Asesmen as Asesmen;

class RawatInap extends Utility {
	static $pdo;
	static $query;

	protected static function getConn(){
		return self::$pdo;
	}

	public function __construct($connection){
		self::$pdo = $connection;
		self::$query = new Query(self::$pdo);
	}

	public function __GET__($parameter = array()) {
		try {
			switch($parameter[1]) {
				case 1:
					break;


				default:
					# code...
					break;
			}
		} catch (QueryException $e) {
			return 'Error => '. $e;
		}
	}

	public function __POST__($parameter = array()){
		switch ($parameter['request']) {
			case 1:
				//return self::tambah_penjamin($parameter);
				break;

			default:
				# code...
				break;
		}
	}

	public function __DELETE__($parameter = array()){
		return self::delete($parameter);
	}

    private function pindah_ke_rawat_inap($parameter){
        

    }

    private function load_antrian_dokter(){
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);
        
        $data = self::$query
            ->select('rawat_inap', 
                array(
                    'waktu_masuk',
                    'cara_masuk',
                    'pasien',
                    'dokter_pj',
                    'asesmen',
                    'kunjungan',
                    'penjamin',
                    'ruangan',
                    'created_at',
                    'updated_at'
                )
            )
            ->where(
                array(
                    'rawat_inap.dokter_pj'  => '= ?',
                    'AND',
                    'rawat_inap.deleted_at' => 'IS NULL'
                )
                , array(
                    $UserData['data']->uid
                )
            )
            ->execute();

        return $data;
    }

    public function get_asesmen_perawat($parameter){    //uid_antrian

        $asesmen = new Asesmen(self::$pdo);
        $data_antrian = $asesmen->get_data_antrian_detail($parameter);
        if ($data_antrian['response_result'] > 0) {
            $data_asesmen = $asesmen->get_data_pasien($data_antrian['response_data']);
        }

    }
}