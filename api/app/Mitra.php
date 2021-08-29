<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Mitra extends Utility {
	static $pdo;
	static $query;
	
	protected static function getConn(){
		return self::$pdo;
	}

	public function __construct($connection) {
		self::$pdo = $connection;
		self::$query = new Query(self::$pdo);
	}

	public function __GET__($parameter = array()) {
		try {

			switch($parameter[1]) {
				case 'detail':
					return self::get_mitra_detail($parameter[2]);
					break;
                case 'mitra_item':
                    return self::mitra_item($parameter);
                    break;

				default:
					return self::get_mitra($parameter);
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function __POST__($parameter = array()) {
		try {
			switch($parameter['request']) {
				case 'tambah_mitra':
					return self::tambah_mitra($parameter);
					break;
				case 'edit_mitra':
					return self::edit_mitra($parameter);
					break;
                case 'check_target':
                    return self::check_target($parameter);
                    break;
				default:
					return self::get_mitra($parameter);
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	private function check_target($parameter) {
	    $Asesmen = self::$query->select('asesmen', array(
	        'kunjungan',
            'pasien'
        ))
            ->where(array(
                'asesmen.uid' => '= ?',
                'AND',
                'asesmen.deleted_at' => 'IS NULL'
            ), array(
                $parameter['asesmen']
            ))
            ->execute();
	    if(count($Asesmen['response_data']) > 0) {
            $Antrian = self::$query->select('antrian', array(
                'penjamin'
            ))
                ->where(array(
                    'antrian.kunjungan' => '= ?',
                    'AND',
                    'antrian.pasien' => '= ?'
                ), array(
                    $Asesmen['response_data'][0]['kunjungan'],
                    $Asesmen['response_data'][0]['pasien']
                ))
                ->execute();
            if(count($Antrian['response_data']) > 0) {
                $data = self::$query->select('master_tindakan_kelas_harga', array(
                    'harga'
                ))
                    ->where(array(
                        'master_tindakan_kelas_harga.penjamin' => '= ?',
                        'AND',
                        'master_tindakan_kelas_harga.mitra' => '= ?',
                        'AND',
                        'master_tindakan_kelas_harga.tindakan' => '= ?'
                    ), array(
                        $Antrian['response_data'][0]['penjamin'],
                        $parameter['mitra'],
                        $parameter['tindakan']
                    ))
                    ->execute();
                return $data;
            } else {
                return $Antrian;
            }
        } else {
            return $Asesmen;
        }
    }

	private function mitra_item($parameter) {
	    $data = self::$query->select('master_mitra', array(
	        'uid',
            'nama',
            'jenis',
            'kontak',
            'alamat'
        ))
            ->where(array(
                'master_mitra.jenis' => '= ?',
                'AND',
                'master_mitra.deleted_at' => 'IS NULL'
            ), array(
                $parameter[2]
            ))
            ->execute();
	    foreach ($data['response_data'] as $key => $value) {
	        $Harga = self::$query->select('master_tindakan_kelas_harga', array(
	            'id',
                'tindakan',
                'kelas',
                'harga',
                'penjamin'
            ))
                ->where(array(
                    'master_tindakan_kelas_harga.mitra' => '= ?',
                    'AND',
                    'master_tindakan_kelas_harga.tindakan' => '= ?',
                    'AND',
                    'master_tindakan_kelas_harga.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid'],
                    $parameter[3]
                ))
                ->execute();
	        $data['response_data'][$key]['harga'] = $Harga['response_data'];
        }

	    return $data;
    }

	public function __DELETE__($parameter = array()) {
		return self::delete($parameter);
	}

	public function get_mitra_provider($parameter) {
	    $data = self::$query->select('master_tindakan_kelas_harga', array(
	        'id',
            'kelas',
            'harga',
            'mitra'
        ))
            ->where(array(
                'master_tindakan_kelas_harga.tindakan' => '= ?',
                'AND',
                'master_tindakan_kelas_harga.deleted_at' => 'IS NULL'
            ), array(
                $parameter
            ))
            ->execute();
	    return $data;
    }

	public function get_mitra($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        if($UserData['data']->jabatan === __UIDPETUGASGUDANGFARMASI__) {
            $data = self::$query->select('master_mitra', array(
                'uid',
                'nama',
                'jenis',
                'kontak',
                'alamat',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'master_mitra.deleted_at' => 'IS NULL',
                    'AND',
                    'master_mitra.jenis' => '= ?'
                ), array(
                    'FAR'
                ))
                ->execute();
        } else {
            $data = self::$query->select('master_mitra', array(
                'uid',
                'nama',
                'jenis',
                'kontak',
                'alamat',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'master_mitra.deleted_at' => 'IS NULL',
                    'AND',
                    'master_mitra.jenis' => '!= ?'
                ), array(
                    'FAR'
                ))
                ->execute();
        }

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $data;
	}

	private function tambah_mitra($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
		$uid = parent::gen_uuid();
		$worker = self::$query->insert('master_mitra', array(
			'uid'=> $uid,
			'nama' => $parameter['nama'],
			'jenis' => $parameter['jenis'],
			'kontak' => $parameter['kontak'],
			'alamat' => $parameter['alamat'],
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
					'master_mitra',
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


	public function get_mitra_detail($parameter) {
		$data = self::$query->select('master_mitra', array(
			'nama',
			'jenis',
			'kontak',
			'alamat',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_mitra.deleted_at' => 'IS NULL',
			'AND',
			'master_mitra.uid' => '= ?'
		), array(
			$parameter
		))
		->execute();

		return $data;
	}


	private function edit_mitra($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
		$old = self::get_mitra_detail($parameter['uid']);
		$worker = self::$query->update('master_mitra', array(
			'nama' => $parameter['nama'],
			'jenis' => $parameter['jenis'],
			'kontak' => $parameter['kontak'],
			'alamat' => $parameter['alamat'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_mitra.deleted_at' => 'IS NULL',
			'AND',
			'master_mitra.uid' => '= ?'
		), array(
			$parameter['uid']
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
					'old_value',
					'new_value',
					'logged_at',
					'status',
					'login_id'
				),
				'value' => array(
					$parameter['uid'],
					$UserData['data']->uid,
					'master_mitra',
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

		return $worker;
	}


	private function delete($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$worker = self::$query
		->delete($parameter[6])
		->where(array(
			$parameter[6] . '.uid' => '= ?'
		), array(
			$parameter[7]
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
					$parameter[7],
					$UserData['data']->uid,
					$parameter[6],
					'D',
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