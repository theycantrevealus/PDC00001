<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Supplier extends Utility {
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
					return self::get_detail($parameter[2]);
					break;

                case 'get_supplier_select2':
                    return self::get_supplier_select2();
                    break;

				default:
					return self::get_all();
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function __POST__($parameter = array()) {
		switch ($parameter['request']) {
			case 'tambah_supplier':
				return self::tambah_supplier($parameter);
				break;
			case 'edit_supplier':
				return self::edit_supplier($parameter);
				break;
			default:
				# code...
				break;
		}
	}

	public function __DELETE__($parameter = array()) {
		return self::delete($parameter);
	}

	private function get_supplier_select2() {
        $data = self::$query
            ->select('master_supplier', array(
                'uid',
                'nama',
                'email',
                'alamat',
                'kontak',
                'created_at',
                'updated_at'
            ))
            ->where(array(
                'master_supplier.deleted_at' => 'IS NULL',
                'AND',
                'master_supplier.nama' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\''
            ))
            ->execute();
        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['nama'] = strtoupper($value['nama']);
            $data['response_data'][$key]['autonum'] = $autonum;

            $relasi_data = self::$query->select('inventori_po', array(
                'uid',
                'nomor_po'
            ))
                ->where(array(
                    'inventori_po.deleted_at' => 'IS NULL',
                    'AND',
                    'inventori_po.supplier' => '= ?'
                ), array(
                    $value['uid']
                ))
                ->execute();

            $data['response_data'][$key]['relasi_po'] = $relasi_data['response_data'];

            $autonum++;
        }
        return $data;
    }

	private function get_all() {
		$data = self::$query
		->select('master_supplier', array(
			'uid',
			'nama',
			'email',
			'alamat',
			'kontak',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_supplier.deleted_at' => 'IS NULL'
		))
		->execute();
		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['nama'] = strtoupper($value['nama']);
			$data['response_data'][$key]['autonum'] = $autonum;

			$relasi_data = self::$query->select('inventori_po', array(
				'uid',
				'nomor_po'
			))
			->where(array(
				'inventori_po.deleted_at' => 'IS NULL',
				'AND',
				'inventori_po.supplier' => '= ?'
			), array(
				$value['uid']
			))
			->execute();

			$data['response_data'][$key]['relasi_po'] = $relasi_data['response_data'];

			$autonum++;
		}
		return $data;
	}

	public function get_detail($parameter) {
		$data = self::$query
		->select('master_supplier', array(
			'uid',
			'nama',
			'email',
			'kontak',
			'alamat',
            'supplier_type',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_supplier.deleted_at' => 'IS NULL',
			'AND',
			'master_supplier.uid' => '= ?'
		), array(
			$parameter
		))
		->execute();
		return $data['response_data'][0];
	}
	
	private function edit_supplier($parameter) {
		$old = self::get_detail($parameter['uid']);
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
		$worker = self::$query
		->update('master_supplier', array(
			'nama' => strtoupper($parameter['nama']),
			'email' => $parameter['email'],
			'kontak' => $parameter['kontak'],
            'supplier_type' => $parameter['jenis'],
			'alamat' => $parameter['alamat'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_supplier.deleted_at' => 'IS NULL',
			'AND',
			'master_supplier.uid' => '= ?'
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
					'master_supplier',
					'U',
					json_encode($old),
					json_encode($parameter),
					parent::format_date(),
					'N',
					$UserData['data']->log_id
				),
				'class' => __CLASS__
			));
		}
	}

	private function tambah_supplier($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table' => 'master_supplier',
			'check' => $parameter['nama']
		));

		if(count($check['response_data']) > 0) {
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();
			$worker = self::$query
			->insert('master_supplier', array(
				'uid' => $uid,
				'nama' => strtoupper($parameter['nama']),
				'email' => $parameter['email'],
				'kontak' => $parameter['kontak'],
				'alamat' => $parameter['alamat'],
                'supplier_type' => $parameter['jenis'],
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
						'new_value',
						'logged_at',
						'status',
						'login_id'
					),
					'value' => array(
						$uid,
						$UserData['data']->uid,
						'master_supplier',
						'I',
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


	private function duplicate_check($parameter) {
		return self::$query
		->select($parameter['table'], array(
			'uid',
			'nama'
		))
		->where(array(
			$parameter['table'] . '.deleted_at' => 'IS NULL',
			'AND',
			$parameter['table'] . '.nama' => '= ?'
		), array(
			$parameter['check']
		))
		->execute();
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