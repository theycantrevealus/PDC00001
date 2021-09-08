<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\Inventori as Inventori;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Unit extends Utility {
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
				case 'get_unit':
					return self::get_unit();
					break;
				case 'get_unit_detail':
					return self::get_unit_detail($parameter[2]);
					break;
                case 'get_unit_select2':
                    return self::get_unit_select2($parameter);
                    break;
				default:
					return self::get_unit();
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function __POST__($parameter = array()) {
		try {
			switch($parameter['request']) {
				case 'tambah_unit':
					return self::tambah_unit($parameter);
					break;
				case 'edit_unit':
					return self::edit_unit($parameter);
					break;
				default:
					return 'Unknown request';
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function get_unit() {
		$data = self::$query->select('master_unit', array(
			'uid',
			'nama',
			'kode',
			'gudang',
			'created_at',
			'updated_at'
		))
            ->join('master_inv_gudang', array(
                'nama as nama_gudang', 'status'
            ))
            ->on(array(
                array('master_unit.gudang', '=', 'master_inv_gudang.uid')
            ))
            ->where(array(
                'master_unit.deleted_at' => 'IS NULL'
            ), array())
            ->order(array(
                'created_at' => 'DESC'
            ))
            ->execute();

		$autonum = 1;
        $Inventori = new Inventori(self::$pdo);
		foreach ($data['response_data'] as $key => $value) {
			$InventoriDetail = $Inventori->get_gudang_detail($value['gudang'])['response_data'][0];
            $data['response_data'][$key]['gudang'] = $InventoriDetail;
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
		}

		return $data;
	}

	private function get_unit_select2($parameter){
        $Unit = self::$query->select('master_unit', array(
                'uid',
                'nama',
                'kode'
            )
        )
            ->join('master_inv_gudang', array(
                    'uid AS uid_gudang',
                    'nama AS nama_gudang'
                )
            )
            ->on(
                array(
                    array('master_unit.gudang', '=', 'master_inv_gudang.uid')
                )
            )
            ->where(
                array(
                    '((master_unit.nama' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\'',
                    'OR',
                    'master_unit.kode' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\')',
                    'OR',
                    'master_inv_gudang.nama' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\')',
                    'AND',
                    'master_unit.deleted_at' => 'IS NULL'
                ), array()
            )
            ->limit(10)
            ->execute();

        return $Unit;
    }

	public function get_unit_detail($parameter) {
		$data = self::$query->select('master_unit', array(
			'uid',
			'nama',
			'kode',
			'gudang',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_unit.uid' => '= ?',
			'AND',
			'master_unit.deleted_at' => 'IS NULL',
		), array(
			$parameter
		))
		->execute();
		return $data;
	}

	public function tambah_unit($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
		$uid = parent::gen_uuid();
		$worker = self::$query->insert('master_unit', array(
			'uid' => $uid,
			'nama' => $parameter['nama'],
			'kode' => $parameter['kode'],
			'gudang' => $parameter['gudang'],
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
					'master_unit',
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

	private function edit_unit($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$old = self::get_unit_detail($parameter['uid']);
		$worker = self::$query->update('master_unit', array(
			'nama' => $parameter['nama'],
			'kode' => $parameter['kode'],
			'gudang' => $parameter['gudang'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_unit.uid' => '= ?',
			'AND',
			'master_unit.deleted_at' => 'IS NULL'
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
					'master_unit',
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
}