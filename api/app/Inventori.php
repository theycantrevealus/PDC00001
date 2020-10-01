<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\Authorization as Authorization;
use PondokCoder\QueryException as QueryException;
use PondokCoder\PO as PO;
use PondokCoder\Penjamin as Penjamin;
use PondokCoder\Pegawai as Pegawai;
use PondokCoder\Unit as Unit;
use PondokCoder\Utility as Utility;

class Inventori extends Utility {
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
				case 'kategori':
					return self::get_kategori();
					break;
				case 'kategori_detail':
					return self::get_kategori_detail($parameter[2]);
					break;
				case 'satuan':
					return self::get_satuan();
					break;
				case 'satuan_detail':
					return self::get_satuan_detail($parameter[2]);
					break;
				case 'gudang':
					return self::get_gudang();
					break;
				case 'gudang_detail':
					return self::get_gudang_detail($parameter[2]);
					break;
				case 'item_detail':
					return self::get_item_detail($parameter[2]);
					break;
				case 'manufacture':
					return self::get_manufacture();
					break;
				case 'manufacture_detail':
					return self::get_manufacture_detail($parameter[2]);
					break;
				case 'kategori_obat':
					return self::get_kategori_obat();
					break;
				case 'kategori_obat_detail':
					return self::get_kategori_obat_detail($parameter[2]);
					break;
				case 'kategori_per_obat':
					return self::get_kategori_obat_item_parsed($parameter[2]);
					break;
				case 'item_batch':
					return self::get_item_batch($parameter[2]);
					break;
				case 'get_amprah_detail':
					return self::get_amprah_detail($parameter[2]);
					break;
				case 'get_amprah_proses_detail':
					return self::get_amprah_proses_detail($parameter[2]);
					break;
				case 'get_stok':
					return self::get_stok();
					break;
				case 'get_stok_log':
					return self::get_stok_log();
					break;
				default:
					return self::get_item();
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function __DELETE__($parameter = array()) {
		return self::delete($parameter);
	}

	public function __POST__($parameter = array()) {
		switch ($parameter['request']) {
			case 'tambah_kategori':
				return self::tambah_kategori($parameter);
				break;
			case 'edit_kategori':
				return self::edit_kategori($parameter);
				break;
			case 'tambah_satuan':
				return self::tambah_satuan($parameter);
				break;
			case 'tambah_gudang':
				return self::tambah_gudang($parameter);
				break;
			case 'edit_gudang':
				return self::edit_gudang($parameter);
				break;
			case 'edit_satuan':
				return self::edit_satuan($parameter);
				break;
			case 'tambah_manufacture':
				return self::tambah_manufacture($parameter);
				break;
			case 'edit_manufacture':
				return self::edit_manufacture($parameter);
				break;
			case 'tambah_item':
				return self::tambah_item($parameter);
			case 'edit_item':
				return self::edit_item($parameter);
				break;
			case 'tambah_kategori_obat':
				return self::tambah_kategori_obat($parameter);
				break;
			case 'edit_kategori_obat':
				return self::edit_kategori_obat($parameter);
				break;
			case 'tambah_amprah':
				return self::tambah_amprah($parameter);
				break;
			case 'get_amprah_request':
				return self::get_amprah_request($parameter);
				break;
			case 'get_amprah_request_finish':
				return self::get_amprah_request($parameter, 'S');
				break;
			case 'proses_amprah':
				return self::proses_amprah($parameter);
				break;
			case 'tambah_stok_awal':
				return self::tambah_stok_awal($parameter);
				break;
			default:
				return array();
				break;
		}
	}
//===========================================================================================KATEGORI
	private function tambah_kategori_obat($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table' => 'master_inv_obat_kategori',
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
			->insert('master_inv_obat_kategori', array(
				'uid' => $uid,
				'nama' => $parameter['nama'],
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
						'master_inv_obat_kategori',
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
	}

	private function edit_kategori_obat($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_kategori_obat_detail($parameter['uid']);

		$worker = self::$query
		->update('master_inv_obat_kategori', array(
			'nama' => $parameter['nama'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_inv_obat_kategori.deleted_at' => 'IS NULL',
			'AND',
			'master_inv_obat_kategori.uid' => '= ?'
		), array(
			$parameter['uid']
		))
		->execute();

		if($worker['response_result'] > 0) {
			unset($parameter['access_token']);

			
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
					'master_inv_obat_kategori',
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

	private function tambah_kategori($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table' => 'master_inv_kategori',
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
			->insert('master_inv_kategori', array(
				'uid' => $uid,
				'nama' => $parameter['nama'],
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
						'master_inv_kategori',
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
	}

	private function edit_kategori($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_kategori_detail($parameter['uid']);

		$worker = self::$query
		->update('master_inv_kategori', array(
			'nama' => $parameter['nama'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_inv_kategori.deleted_at' => 'IS NULL',
			'AND',
			'master_inv_kategori.uid' => '= ?'
		), array(
			$parameter['uid']
		))
		->execute();

		if($worker['response_result'] > 0) {
			unset($parameter['access_token']);

			
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
					'master_inv_kategori',
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

	private function get_kategori() {
		$data = self::$query
		->select('master_inv_kategori', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_inv_kategori.deleted_at' => 'IS NULL'
		))
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	private function get_kategori_detail($parameter) {
		$data = self::$query
		->select('master_inv_kategori', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_inv_kategori.deleted_at' => 'IS NULL',
			'AND',
			'master_inv_kategori.uid' => '= ?'
		), array(
			$parameter
		))
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	private function get_kategori_obat() {
		$data = self::$query
		->select('master_inv_obat_kategori', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_inv_obat_kategori.deleted_at' => 'IS NULL'
		))
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	private function get_kategori_obat_detail($parameter) {
		$data = self::$query
		->select('master_inv_obat_kategori', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_inv_obat_kategori.deleted_at' => 'IS NULL',
			'AND',
			'master_inv_obat_kategori.uid' => '= ?'
		), array(
			$parameter
		))
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}
//===========================================================================================SATUAN
	private function get_satuan() {
		$data = self::$query
		->select('master_inv_satuan', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_inv_satuan.deleted_at' => 'IS NULL'
		))
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	public function get_satuan_detail($parameter) {
		$data = self::$query
		->select('master_inv_satuan', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_inv_satuan.deleted_at' => 'IS NULL',
			'AND',
			'master_inv_satuan.uid' => '= ?'
		), array(
			$parameter
		))
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	private function tambah_satuan($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table' => 'master_inv_satuan',
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
			->insert('master_inv_satuan', array(
				'uid' => $uid,
				'nama' => $parameter['nama'],
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
						'master_inv_satuan',
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
	}

	private function edit_satuan($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_satuan_detail($parameter['uid']);

		$worker = self::$query
		->update('master_inv_satuan', array(
			'nama' => $parameter['nama'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_inv_satuan.deleted_at' => 'IS NULL',
			'AND',
			'master_inv_satuan.uid' => '= ?'
		), array(
			$parameter['uid']
		))
		->execute();

		if($worker['response_result'] > 0) {
			unset($parameter['access_token']);

			
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
					'master_inv_satuan',
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
//===========================================================================================PENJAMIN
	private function get_penjamin($parameter) {
		$data = self::$query->select('master_inv_harga', array(
			'id',
			'barang',
			'penjamin',
			'profit',
			'profit_type'
		))
		->where(array(
			'master_inv_harga.deleted_at' => 'IS NULL',
			'AND',
			'master_inv_harga.barang' => '= ?'
		), array(
			$parameter
		))
		->execute();
		return $data['response_data'];
	}

	private function get_rak($parameter) {
		$data = self::$query->select('master_inv_gudang_rak', array(
			'id',
			'barang',
			'gudang',
			'rak'
		))
		->where(array(
			'master_inv_gudang_rak.deleted_at' => 'IS NULL',
			'AND',
			'master_inv_gudang_rak.barang' => '= ?'
		), array(
			$parameter
		))
		->execute();
		return $data['response_data'];
	}

	private function get_monitoring($parameter) {
		$data = self::$query->select('master_inv_monitoring', array(
			'id',
			'barang',
			'gudang',
			'min',
			'max'
		))
		->where(array(
			'master_inv_monitoring.deleted_at' => 'IS NULL',
			'AND',
			'master_inv_monitoring.barang' => '= ?'
		), array(
			$parameter
		))
		->execute();
		return $data['response_data'];
	}

	private function get_kategori_obat_item($parameter) {
		$data = self::$query->select('master_inv_obat_kategori_item', array(
			'id',
			'obat',
			'kategori'
		))
		->where(array(
			'master_inv_obat_kategori_item.deleted_at' => 'IS NULL',
			'AND',
			'master_inv_obat_kategori_item.obat' => '= ?'
		), array(
			$parameter
		))
		->execute();
		return $data['response_data'];
	}

	private function get_kategori_obat_item_parsed($parameter) {
		$data = self::$query->select('master_inv_obat_kategori_item', array(
			'id',
			'obat',
			'kategori'
		))
		->where(array(
			'master_inv_obat_kategori_item.deleted_at' => 'IS NULL',
			'AND',
			'master_inv_obat_kategori_item.obat' => '= ?'
		), array(
			$parameter
		))
		->execute();
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['kategori'] = self::get_kategori_obat_detail($value['kategori'])['response_data'][0];
		}
		return $data['response_data'];
	}
//===========================================================================================GUDANG
	private function get_gudang() {
		$data = self::$query
		->select('master_inv_gudang', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_inv_gudang.deleted_at' => 'IS NULL'
		))
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	public function get_gudang_detail($parameter) {
		$data = self::$query
		->select('master_inv_gudang', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_inv_gudang.deleted_at' => 'IS NULL',
			'AND',
			'master_inv_gudang.uid' => '= ?'
		), array(
			$parameter
		))
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	private function tambah_gudang($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table' => 'master_inv_gudang',
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
			->insert('master_inv_gudang', array(
				'uid' => $uid,
				'nama' => $parameter['nama'],
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
						'master_inv_gudang',
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
	}

	private function edit_gudang($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_gudang_detail($parameter['uid']);

		$worker = self::$query
		->update('master_inv_gudang', array(
			'nama' => $parameter['nama'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_inv_gudang.deleted_at' => 'IS NULL',
			'AND',
			'master_inv_gudang.uid' => '= ?'
		), array(
			$parameter['uid']
		))
		->execute();

		if($worker['response_result'] > 0) {
			unset($parameter['access_token']);

			
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
					'master_inv_gudang',
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
//===========================================================================================Manufacture
	private function get_manufacture() {
		$data = self::$query
		->select('master_manufacture', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_manufacture.deleted_at' => 'IS NULL'
		))
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	private function get_manufacture_detail($parameter) {
		$data = self::$query
		->select('master_manufacture', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_manufacture.deleted_at' => 'IS NULL',
			'AND',
			'master_manufacture.uid' => '= ?'
		), array(
			$parameter
		))
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	private function tambah_manufacture($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table' => 'master_manufacture',
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
			->insert('master_manufacture', array(
				'uid' => $uid,
				'nama' => $parameter['nama'],
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
						'master_manufacture',
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
	}

	private function edit_manufacture($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_manufacture_detail($parameter['uid']);

		$worker = self::$query
		->update('master_manufacture', array(
			'nama' => $parameter['nama'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_manufacture.deleted_at' => 'IS NULL',
			'AND',
			'master_manufacture.uid' => '= ?'
		), array(
			$parameter['uid']
		))
		->execute();

		if($worker['response_result'] > 0) {
			unset($parameter['access_token']);

			
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
					'master_manufacture',
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
//===========================================================================================ITEM DETAIL
	private function get_konversi($parameter) {
		$dataKonversi = self::$query
		->select('master_inv_satuan_konversi', array(
			'dari_satuan',
			'ke_satuan',
			'rasio'
		))
		->where(array(
			'master_inv_satuan_konversi.barang' => '= ?'
		), array(
			$parameter
		))
		->execute();
		return $dataKonversi['response_data'];
	}

	private function get_varian($parameter) { //array('barang'=>'', 'satuan' => '')
		$dataVarian = self::$query
		->select('master_inv_satuan_varian', array(
			'satuan',
			'nama'
		))
		->where(array(
			'master_inv_satuan_varian.barang' => '= ?'
		), array(
			$parameter
		))
		->execute();
		return $dataVarian['response_data'];
	}

	private function get_kombinasi($parameter) { //array('barang'=>'', 'satuan' => '')
		$dataVarian = self::$query
		->select('master_inv_kombinasi', array(
			'barang_kombinasi',
			'satuan',
			'varian',
			'qty'
		))
		->where(array(
			'master_inv_kombinasi.barang' => '= ?'
		), array(
			$parameter
		))
		->execute();
		return $dataVarian['response_data'];
	}
//===========================================================================================ITEM
	private function get_item() {
		$data = self::$query
		->select('master_inv', array(
			'uid',
			'kode_barang',
			'nama',
			'kategori',
			'satuan_terkecil',
			'manufacture',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_inv.deleted_at' => 'IS NULL'
		))
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {

			
			$data['response_data'][$key]['autonum'] = $autonum;
			$kategori_obat = self::get_kategori_obat_item($value['uid']);
			foreach ($kategori_obat as $KOKey => $KOValue) {
				$kategori_obat[$KOKey]['kategori'] = self::get_kategori_obat_detail($KOValue['kategori'])['response_data'][0]['nama'];
			}

			$data['response_data'][$key]['kategori_obat'] = $kategori_obat;
			$data['response_data'][$key]['satuan_terkecil'] = self::get_satuan_detail($value['satuan_terkecil'])['response_data'][0];
			$data['response_data'][$key]['kategori'] = self::get_kategori_detail($value['kategori'])['response_data'][0];
			$data['response_data'][$key]['manufacture'] = self::get_manufacture_detail($value['manufacture'])['response_data'][0];

			//Data Penjamin
			$PenjaminObat = new Penjamin(self::$pdo);
			$ListPenjaminObat = $PenjaminObat::get_penjamin_obat($value['uid'])['response_data'];
			foreach ($ListPenjaminObat as $PenjaminKey => $PenjaminValue) {
				$ListPenjaminObat[$PenjaminKey]['profit'] = floatval($PenjaminValue['profit']);
			}
			$data['response_data'][$key]['penjamin'] = $ListPenjaminObat;

			//Cek Ketersediaan Stok
			$TotalStock = 0;
			$InventoriStockPopulator = self::get_item_batch($value['uid']);
			if(count($InventoriStockPopulator['response_data']) > 0) {
				foreach ($InventoriStockPopulator['response_data'] as $TotalKey => $TotalValue) {
					$TotalStock += floatval($TotalValue['stok_terkini']);
				}
				$data['response_data'][$key]['stok'] = $TotalStock;
				$data['response_data'][$key]['batch'] = $InventoriStockPopulator['response_data'];
			} else {
				$data['response_data'][$key]['stok'] = 0;
			}
				
			$autonum++;
		}
		return $data;
	}

	private function get_item_batch($parameter) {
		$data = self::$query->select('inventori_stok', array(
			'batch',
			'barang',
			'gudang',
			'stok_terkini'
		))
		->where(array(
			'inventori_stok.barang' => '= ?'
		), array(
			$parameter	
		))
		->order(array(
			'gudang' => 'DESC'
		))
		->execute();
		foreach ($data['response_data'] as $key => $value) {
			$batch_info = self::get_batch_detail($value['batch'])['response_data'][0];

			if($batch_info['expired_date'] < date('Y-m-d')) { //Expired jangan dijual
				unset($data['response_data'][$key]);
			} else {
				//$data['response_data'][$key]['item_detail'] = self::get_item_detail($value['barang'])['response_data'][0];
				$data['response_data'][$key]['gudang'] = self::get_gudang_detail($value['gudang'])['response_data'][0];
				$data['response_data'][$key]['kode'] = self::get_batch_detail($value['batch'])['response_data'][0]['batch'];	
				$data['response_data'][$key]['expired'] = date('d F Y', strtotime($batch_info['expired_date']));
				$data['response_data'][$key]['stok_terkini'] = floatval($value['stok_terkini']);
				$data['response_data'][$key]['expired_sort'] = $batch_info['expired_date'];
				$data['response_data'][$key]['harga'] = $batch_info['harga'];
				$data['response_data'][$key]['profit'] = $batch_info['profit'];
			}
		}

		//Sort Batch before return
		$sorted = $data['response_data'];
		array_multisort($sorted, SORT_ASC, $data['response_data']);
		$data['response_data'] = $sorted;
		return $data;
	}

	private function get_batch_detail($parameter) {
		$data = self::$query->select('inventori_batch', array(
			'uid',
			'batch',
			'barang',
			'expired_date',
			'po',
			'do_master'
		))
		->where(array(
			'inventori_batch.uid' => ' = ?'
		), array(
			$parameter
		))
		->execute();
		foreach ($data['response_data'] as $key => $value) {
			//Get Harga dari PO
			if(isset($value['po'])) {
				$PO = new PO(self::$pdo);
				$Price = $PO::get_po_item_price(array(
					$value['po'],
					$value['barang']
				));

				$data['response_data'][$key]['harga'] = floatval($Price['response_data'][0]['harga']);

				//Tambahkan Keuntungan yang diinginkan dari master inventori
				$Profit = self::get_penjamin($value['barang']);
				$data['response_data'][$key]['profit'] = $Profit;
			} else {
				$data['response_data'][$key]['harga'] = 0;
			}
		}
		return $data;
	}

	public function get_item_detail($parameter) {
		$data = self::$query
		->select('master_inv', array(
			'uid',
			'nama',
			'kode_barang',
			'keterangan',
			'kategori',
			'manufacture',
			'satuan_terkecil',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_inv.deleted_at' => 'IS NULL',
			'AND',
			'master_inv.uid' => '= ?'
		), array(
			$parameter
		))
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;

			//Kategori Obat
			$data['response_data'][$key]['kategori_obat'] = self::get_kategori_obat_item($value['uid']);

			//Prepare Image File
			$data['response_data'][$key]['image'] = file_exists('../images/produk/' . $value['uid'] . '.png');

			//Konversi
			$data['response_data'][$key]['konversi'] = self::get_konversi($value['uid']);

			//Penjamin
			$data['response_data'][$key]['penjamin'] = self::get_penjamin($value['uid']);

			//Lokasi
			$data['response_data'][$key]['lokasi'] = self::get_rak($value['uid']);

			//Monitoring
			$data['response_data'][$key]['monitoring'] = self::get_monitoring($value['uid']);
		}
		return $data;
	}

	private function tambah_item($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$error_count = 0;

		//Parent Segment
		$check = self::duplicate_check(array(
			'table' => 'master_inv',
			'check' => $parameter['nama']
		));
		if(count($check['response_data']) > 0) {
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();
			$worker = self::$query->insert('master_inv', array(
				'uid' => $uid,
				'kode_barang' => $parameter['kode'],
				'nama' => $parameter['nama'],
				'kategori' => $parameter['kategori'],
				'manufacture' => $parameter['manufacture'],
				'satuan_terkecil' => $parameter['satuan_terkecil'],
				'keterangan' => $parameter['keterangan'],
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
						$parameter['uid'],
						$UserData['data']->uid,
						'master_inv',
						'I',
						json_encode($parameter),
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class' => __CLASS__
				));

				//Image Upload
				$data = $parameter['image'];
				list($type, $data) = explode(';', $data);
				list(, $data)      = explode(',', $data);
				$data = base64_decode($data);
				if(!file_exists('../images/produk')) {
					mkdir('../images/produk');
				}
				file_put_contents('../images/produk/' . $uid . '.png', $data);


				//Kategori Obat
				$oldKategoriObat = self::$query->select('master_inv_obat_kategori_item', array(
					'id',
					'kategori'
				))
				->where(array(
					'master_inv_obat_kategori_item.obat' => '= ?'
				), array(
					$uid
				))
				->execute();

				//Delete unused kategori
				foreach ($oldKategoriObat['response_data'] as $key => $value) {
					if(!in_array($value['kategori'], $parameter['listKategoriObat'])) {
						$deleteKategoriObat = self::$query->update('master_inv_obat_kategori_item', array(
							'deleted_at' => parent::format_date()
						))
						->where(array(
							'master_inv_obat_kategori_item.id' => '= ?'
						), array(
							$value['id']
						))
						->execute();
						if($deleteKategoriObat['response_result'] > 0) {
							//
						} else {
							$error_count++;
						}
					}
				}



				foreach ($parameter['listKategoriObat'] as $key => $value) {
					//Check existing
					$checkKategoriObat = self::$query->select('master_inv_obat_kategori_item', array(
						'id'
					))
					->where(array(
						'obat' => '= ?',
						'AND',
						'kategori' => '= ?'
					), array(
						$uid,
						$value
					))
					->execute();
					if(count($checkKategoriObat['response_data']) > 0) {
						$kategoriObat = self::$query->update('master_inv_obat_kategori_item', array(
							'deleted_at' => NULL,
							'updated_at' => parent::format_date()
						))
						->where(array(
							'master_inv_obat_kategori_item.id' => '= ?'
						), array(
							$checkKategoriObat['response_data'][0]['id']
						))
						->execute();
						if($kategoriObat['response_result'] > 0) {
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
									$parameter['uid'],
									$UserData['data']->uid,
									'master_inv_obat_kategori_item',
									'U',
									'activated',
									parent::format_date(),
									'N',
									$UserData['data']->log_id
								),
								'class' => __CLASS__
							));
						} else {
							$error_count++;
						}
					} else {
						$kategoriObat = self::$query->insert('master_inv_obat_kategori_item', array(
							'obat' => $uid,
							'kategori' => $value,
							'created_at' => parent::format_date(),
							'updated_at' => parent::format_date()
						))
						->execute();
						if($kategoriObat['response_result'] > 0) {
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
									$parameter['uid'],
									$UserData['data']->uid,
									'master_inv_obat_kategori_item',
									'I',
									json_encode($parameter['listKategoriObat']),
									parent::format_date(),
									'N',
									$UserData['data']->log_id
								),
								'class' => __CLASS__
							));
						} else {
							$error_count++;
						}
					}		
				}


				//Satuan Konversi
				foreach ($parameter['satuanKonversi'] as $key => $value) {
					$newKonversi = self::$query->insert('master_inv_satuan_konversi', array(
						'barang' => $uid,
						'dari_satuan' => $value['dari'],
						'rasio' => $value['rasio'],
						'ke_satuan' => $value['ke'],
						'created_at' => parent::format_date(),
						'updated_at' => parent::format_date()
					))
					->execute();
					if($newKonversi['response_result'] > 0) {
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
								$parameter['uid'],
								$UserData['data']->uid,
								'master_inv_satuan_konversi',
								'I',
								json_encode($parameter['satuanKonversi']),
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class' => __CLASS__
						));
					} else {
						$error_count++;
					}
				}

				//Penjamin
				foreach ($parameter['penjaminList'] as $key => $value) {
					$newPenjamin = self::$query->insert('master_inv_harga', array(
						'barang' => $uid,
						'penjamin' => $value['penjamin'],
						'profit' => $value['marginValue'],
						'profit_type' => $value['marginType'],
						'created_at' => parent::format_date(),
						'updated_at' => parent::format_date()
					))
					->execute();
					if(count($newPenjamin['response_result']) > 0) {
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
								$parameter['uid'],
								$UserData['data']->uid,
								'master_inv_harga',
								'I',
								json_encode($parameter['penjaminList']),
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class' => __CLASS__
						));
					} else {
						$error_count++;
					}
				}

				//Gudang Rak
				foreach ($parameter['gudangMeta'] as $key => $value) {
					$newGudang = self::$query->insert('master_inv_gudang_rak', array(
						'barang' => $uid,
						'gudang' => $value['gudang'],
						'rak' => $value['lokasi'],
						'created_at' => parent::format_date(),
						'updated_at' => parent::format_date()
					))
					->execute();
					if($newGudang['response_result'] > 0) {
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
								$parameter['uid'],
								$UserData['data']->uid,
								'master_inv_gudang_rak',
								'I',
								json_encode($parameter['gudangMeta']),
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class' => __CLASS__
						));
					} else {
						$error_count++;
					}
				}

				//Monitoring
				foreach ($parameter['monitoring'] as $key => $value) {
					$newMonitoring = self::$query->insert('master_inv_monitoring', array(
						'barang' => $uid,
						'gudang' => $value['gudang'],
						'min' => $value['min'],
						'max' => $value['max'],
						'created_at' => parent::format_date(),
						'updated_at' => parent::format_date()
					))
					->execute();
					if($newMonitoring['response_result'] > 0) {
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
								$parameter['uid'],
								$UserData['data']->uid,
								'master_inv_monitoring',
								'I',
								json_encode($parameter['monitoring']),
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class' => __CLASS__
						));
					} else {
						$error_count++;
					}
				}
			} else {
				$error_count ++;
			}
		}
		return $error_count;
	}











	private function edit_item($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$error_count = 0;
		$uid = $parameter['uid'];
		$old_value = self::get_item_detail($uid);
		$worker = self::$query->update('master_inv', array(
			'uid' => $uid,
			'kode_barang' => $parameter['kode'],
			'nama' => $parameter['nama'],
			'kategori' => $parameter['kategori'],
			'manufacture' => $parameter['manufacture'],
			'satuan_terkecil' => $parameter['satuan_terkecil'],
			'keterangan' => $parameter['keterangan'],
			'created_at' => parent::format_date(),
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_inv.deleted_at' => 'IS NULL',
			'AND',
			'master_inv.uid' => '= ?'
		), array(
			$uid
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
					$uid,
					$UserData['data']->uid,
					'master_inv',
					'U',
					json_encode($old),
					json_encode($parameter),
					parent::format_date(),
					'N',
					$UserData['data']->log_id
				),
				'class' => __CLASS__
			));

			//Image Upload
			$data = $parameter['image'];
			list($type, $data) = explode(';', $data);
			list(, $data)      = explode(',', $data);
			$data = base64_decode($data);
			if(!file_exists('../images/produk')) {
				mkdir('../images/produk');
			}

			file_put_contents('../images/produk/' . $uid . '.png', $data);

			//Kategori Obat
			$oldKategoriObat = self::$query->select('master_inv_obat_kategori_item', array(
				'id',
				'kategori'
			))
			->where(array(
				'master_inv_obat_kategori_item.obat' => '= ?'
			), array(
				$uid
			))
			->execute();

			//Delete unused kategori
			foreach ($oldKategoriObat['response_data'] as $key => $value) {
				if(!in_array($value['kategori'], $parameter['listKategoriObat'])) {
					$deleteKategoriObat = self::$query->update('master_inv_obat_kategori_item', array(
						'deleted_at' => parent::format_date()
					))
					->where(array(
						'master_inv_obat_kategori_item.obat' => '= ?',
						'AND',
						'master_inv_obat_kategori_item.kategori' => '= ?'
					), array(
						$uid,
						$value['kategori']
					))
					->execute();

					if($deleteKategoriObat['response_result'] > 0) {
						//
					} else {
						$error_count++;
					}
				}
			}



			foreach ($parameter['listKategoriObat'] as $key => $value) {
				//Check existing
				$checkKategoriObat = self::$query->select('master_inv_obat_kategori_item', array(
					'id'
				))
				->where(array(
					'obat' => '= ?',
					'AND',
					'kategori' => '= ?'
				), array(
					$uid,
					$value
				))
				->execute();
				if(count($checkKategoriObat['response_data']) > 0) {
					$kategoriObat = self::$query->update('master_inv_obat_kategori_item', array(
						'deleted_at' => NULL,
						'updated_at' => parent::format_date()
					))
					->where(array(
						'master_inv_obat_kategori_item.id' => '= ?'
					), array(
						$checkKategoriObat['response_data'][0]['id']
					))
					->execute();
					if($kategoriObat['response_result'] > 0) {
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
								$parameter['uid'],
								$UserData['data']->uid,
								'master_inv_obat_kategori_item',
								'U',
								'activated',
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class' => __CLASS__
						));
					} else {
						$error_count++;
					}
				} else {
					$kategoriObat = self::$query->insert('master_inv_obat_kategori_item', array(
						'obat' => $uid,
						'kategori' => $value,
						'created_at' => parent::format_date(),
						'updated_at' => parent::format_date()
					))
					->execute();
					if($kategoriObat['response_result'] > 0) {
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
								$parameter['uid'],
								$UserData['data']->uid,
								'master_inv_obat_kategori_item',
								'I',
								json_encode($parameter['listKategoriObat']),
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class' => __CLASS__
						));
					} else {
						$error_count++;
					}
				}		
			}


			//Satuan Konversi
			$resetSatuan = self::$query->update('master_inv_satuan_konversi', array(
				'deleted_at' => parent::format_date()
			))
			->where(array(
				'master_inv_satuan_konversi.barang' => '= ?'
			), array(
				$uid
			))
			->execute();
			$requestSatuanIDs = array();
			$oldSatuanMeta = array();
			$oldSatuanKonversi = self::$query->select('master_inv_satuan_konversi', array(
				'id',
				'barang',
				'dari_satuan',
				'ke_satuan'
			))
			->where(array(
				'master_inv_satuan_konversi.barang' => '= ?'
			), array(
				$uid
			))
			->execute();

			foreach ($oldSatuanKonversi['response_data'] as $key => $value) {
				if(!in_array($value['id'], $requestSatuanIDs)) {
					array_push($requestSatuanIDs, $value['id']);
					array_push($oldSatuanMeta, $value);
				}
			}

			foreach ($parameter['satuanKonversi'] as $key => $value) {
				if(isset($requestSatuanIDs[$key])) {
					$updateKonversi = self::$query->update('master_inv_satuan_konversi', array(
						'barang' => $uid,
						'dari_satuan' => $value['dari'],
						'rasio' => $value['rasio'],
						'ke_satuan' => $value['ke'],
						'updated_at' => parent::format_date(),
						'deleted_at' => NULL
					))
					->where(array(
						'master_inv_satuan_konversi.id' => '= ?'
					), array(
						$requestSatuanIDs[$key]
					))
					->execute();
					if($updateKonversi['response_result'] > 0) {
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
								'master_inv_satuan_konversi',
								'U',
								json_encode($oldSatuanMeta[$key]),
								json_encode($parameter['satuanKonversi']),
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class' => __CLASS__
						));
					} else {
						$error_count++;
					}
				} else {
					$newKonversi = self::$query->insert('master_inv_satuan_konversi', array(
						'barang' => $uid,
						'dari_satuan' => $value['dari'],
						'rasio' => $value['rasio'],
						'ke_satuan' => $value['ke'],
						'created_at' => parent::format_date(),
						'updated_at' => parent::format_date()
					))
					->execute();
					if($newKonversi['response_result'] > 0) {
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
								$parameter['uid'],
								$UserData['data']->uid,
								'master_inv_satuan_konversi',
								'I',
								json_encode($parameter['satuanKonversi']),
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class' => __CLASS__
						));
					} else {
						$error_count++;
					}
				}
			}








			//Penjamin
			$resetPenjamin = self::$query->update('master_inv_harga', array(
				'deleted_at' => parent::format_date()
			))
			->where(array(
				'master_inv_harga.barang' => '= ?'
			), array(
				$uid
			))
			->execute();

			$requestPenjaminIDs = array();
			$oldPenjaminMeta = array();
			$oldPenjaminKonversi = self::$query->select('master_inv_harga', array(
				'id',
				'barang',
				'penjamin'
			))
			->where(array(
				'master_inv_harga.barang' => '= ?'
			), array(
				$uid
			))
			->execute();

			foreach ($oldPenjaminKonversi['response_data'] as $key => $value) {
				if(!in_array($value['id'], $requestPenjaminIDs)) {
					array_push($requestPenjaminIDs, $value['id']);
					array_push($oldPenjaminMeta, $value);
				}
			}

			foreach ($parameter['penjaminList'] as $key => $value) {
				if(isset($requestPenjaminIDs[$key])) {
					$updatePenjamin = self::$query->update('master_inv_harga', array(
						'penjamin' => $value['penjamin'],
						'profit' => $value['marginValue'],
						'profit_type' => $value['marginType'],
						'updated_at' => parent::format_date(),
						'deleted_at' => NULL
					))
					->where(array(
						'master_inv_harga.id' => '= ?'
					), array(
						$requestPenjaminIDs[$key]
					))
					->execute();
					if($updatePenjamin['response_result'] > 0) {
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
								'master_inv_harga',
								'U',
								json_encode($oldPenjaminMeta[$key]),
								json_encode($parameter['penjaminList']),
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class' => __CLASS__
						));
					} else {
						$error_count++;
					}
				} else {
					$newPenjamin = self::$query->insert('master_inv_harga', array(
						'barang' => $uid,
						'penjamin' => $value['penjamin'],
						'profit' => $value['marginValue'],
						'profit_type' => $value['marginType'],
						'created_at' => parent::format_date(),
						'updated_at' => parent::format_date()
					))
					->execute();
					if($newPenjamin['response_result'] > 0) {
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
								$parameter['uid'],
								$UserData['data']->uid,
								'master_inv_harga',
								'I',
								json_encode($parameter['penjaminList']),
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class' => __CLASS__
						));
					} else {
						$error_count++;
					}
				}
			}

			//Gudang Rak
			$resetGudangRak = self::$query->update('master_inv_gudang_rak', array(
				'deleted_at' => parent::format_date()
			))
			->where(array(
				'master_inv_gudang_rak.barang' => '= ?'
			), array(
				$uid
			))
			->execute();

			$requestGudangRakIDs = array();
			$oldGudangRakMeta = array();
			$oldGudangRak = self::$query->select('master_inv_gudang_rak', array(
				'id',
				'barang',
				'gudang',
				'rak'
			))
			->where(array(
				'master_inv_gudang_rak.barang' => '= ?'
			), array(
				$uid
			))
			->execute();

			foreach ($oldGudangRak['response_data'] as $key => $value) {
				if(!in_array($value['id'], $requestGudangRakIDs)) {
					array_push($requestGudangRakIDs, $value['id']);
					array_push($oldGudangRakMeta, $value);
				}
			}

			foreach ($parameter['gudangMeta'] as $key => $value) {
				if(isset($requestGudangRakIDs[$key])) {
					$updateGudangRak = self::$query->update('master_inv_gudang_rak', array(
						'barang' => $uid,
						'gudang' => $value['gudang'],
						'rak' => $value['lokasi'],
						'updated_at' => parent::format_date(),
						'deleted_at' => NULL
					))
					->where(array(
						'master_inv_gudang_rak.id' => '= ?'
					), array(
						$requestGudangRakIDs[$key]
					))
					->execute();
					if($updateGudangRak['response_result'] > 0) {
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
								'master_inv_gudang_rak',
								'U',
								json_encode($oldGudangRakMeta[$key]),
								json_encode($parameter['gudangMeta']),
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class' => __CLASS__
						));
					} else {
						$error_count++;
					}
				} else {
					$newGudangRak = self::$query->insert('master_inv_gudang_rak', array(
						'barang' => $uid,
						'gudang' => $value['gudang'],
						'rak' => $value['lokasi'],
						'created_at' => parent::format_date(),
						'updated_at' => parent::format_date()
					))
					->execute();
					if($newGudangRak['response_result'] > 0) {
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
								$parameter['uid'],
								$UserData['data']->uid,
								'master_inv_gudang_rak',
								'I',
								json_encode($parameter['gudangMeta']),
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class' => __CLASS__
						));
					} else {
						$error_count++;
					}
				}
			}

			//Monitoring
			$resetMonitoring = self::$query->update('master_inv_monitoring', array(
				'deleted_at' => parent::format_date()
			))
			->where(array(
				'master_inv_monitoring.barang' => '= ?'
			), array(
				$uid
			))
			->execute();

			$requestMonitoringIDs = array();
			$oldMonitoringMeta = array();
			$oldMonitoring = self::$query->select('master_inv_monitoring', array(
				'id',
				'barang',
				'gudang',
				'min',
				'max'
			))
			->where(array(
				'master_inv_monitoring.barang' => '= ?'
			), array(
				$uid
			))
			->execute();

			foreach ($oldMonitoring['response_data'] as $key => $value) {
				if(!in_array($value['id'], $requestMonitoringIDs)) {
					array_push($requestMonitoringIDs, $value['id']);
					array_push($oldMonitoringMeta, $value);
				}
			}
			
			foreach ($parameter['monitoring'] as $key => $value) {
				if(isset($requestMonitoringIDs[$key])) {
					$updateMonitoring = self::$query->update('master_inv_monitoring', array(
						'barang' => $uid,
						'gudang' => $value['gudang'],
						'min' => $value['min'],
						'max' => $value['max'],
						'updated_at' => parent::format_date(),
						'deleted_at' => NULL
					))
					->where(array(
						'master_inv_monitoring.id' => '= ?'
					), array(
						$requestMonitoringIDs[$key]
					))
					->execute();
					if($updateMonitoring['response_result'] > 0) {
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
								'master_inv_monitoring',
								'U',
								json_encode($oldMonitoringMeta[$key]),
								json_encode($parameter['monitoring']),
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class' => __CLASS__
						));
					} else {
						$error_count++;
					}
				} else {
					$newMonitoring = self::$query->insert('master_inv_monitoring', array(
						'barang' => $uid,
						'gudang' => $value['gudang'],
						'min' => $value['min'],
						'max' => $value['max'],
						'created_at' => parent::format_date(),
						'updated_at' => parent::format_date()
					))
					->execute();
					if($newMonitoring['response_result'] > 0) {
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
								$parameter['uid'],
								$UserData['data']->uid,
								'master_inv_monitoring',
								'I',
								json_encode($parameter['monitoring']),
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class' => __CLASS__
						));
					} else {
						$error_count++;
					}
				}
			}
		} else {
			$error_count ++;
		}
		return $error_count;
	}

	private function tambah_amprah($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		//Get Last AI tahun ini
		$lastID = self::$query->select('inventori_amprah', array(
			'uid'
		))
		->where(array(
			'EXTRACT(year FROM inventori_amprah.created_at)' => '= ?'
		), array(
			date('Y')
		))
		->execute();

		/*$Unit = new Unit(self::$pdo);
		$UnitInfo = $Unit::get_unit_detail();*/

		$uid = parent::gen_uuid();
		$worker = self::$query->insert('inventori_amprah', array(
			'uid' => $uid,
			'kode_amprah' => str_pad(count($lastID['response_data']) + 1, 5, '0', STR_PAD_LEFT) . '/' . $UserData['data']->unit_kode . '/AMP/' . date('m') . '/' . date('Y'),
			'unit' => $UserData['data']->unit,
			'pegawai' => $UserData['data']->uid,
			'tanggal' => $parameter['tanggal'],
			'status' => 'N',
			'created_at' => parent::format_date(),
			'updated_at' => parent::format_date(),
			'keterangan' => $parameter['keterangan']
		))
		->execute();
		if($worker['response_result'] > 0) {
			$worker['amprah_detail'] = array();
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
					'inventori_amprah',
					'I',
					json_encode($parameter),
					parent::format_date(),
					'N',
					$UserData['data']->log_id
				),
				'class' => __CLASS__
			));

			foreach ($parameter['item'] as $key => $value) {
				//Amprah Detail
				$worker_detail = self::$query->insert('inventori_amprah_detail', array(
					'amprah' => $uid,
					'item' => $key,
					'jumlah' => floatval($value['qty']),
					'created_at' => parent::format_date(),
					'updated_at' => parent::format_date()
				))
				->returning('id')
				->execute();

				if($worker_detail['response_result'] > 0) {
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
							$worker_detail['response_unique'],
							$UserData['data']->uid,
							'inventori_amprah_detail',
							'I',
							json_encode($parameter['item']),
							parent::format_date(),
							'N',
							$UserData['data']->log_id
						),
						'class' => __CLASS__
					));
				}
				array_push($worker['amprah_detail'], $worker_detail);
			}
		}
		return $worker;
	}

	private function get_amprah_request($parameter, $status = 'P') {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		
		if(isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
			if($UserData['data']->unit == __UNIT_GUDANG__) {
				if($status == 'S') {
					$paramData = array(
						'inventori_amprah.deleted_at' => 'IS NULL',
						'AND',
						'inventori_amprah.tanggal' => 'BETWEEN ? AND ?',
						'AND',
						'inventori_amprah.status' => '= ?',
						'AND',
						'inventori_amprah.kode_amprah' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
					);

					$paramValue = array(
						$parameter['from'], $parameter['to'], 'S'
					);
				} else {
					$paramData = array(
						'inventori_amprah.deleted_at' => 'IS NULL',
						'AND',
						'inventori_amprah.tanggal' => 'BETWEEN ? AND ?',
						'AND',
						'NOT inventori_amprah.status' => '= ?',
						'AND',
						'inventori_amprah.kode_amprah' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
					);

					$paramValue = array(
						$parameter['from'], $parameter['to'], 'S'
					);
				}
			} else {
				if($status == 'S') {
					$paramData = array(
						'inventori_amprah.deleted_at' => 'IS NULL',
						'AND',
						'inventori_amprah.tanggal' => 'BETWEEN ? AND ?',
						'AND',
						'inventori_amprah.unit' => '= ?',
						'AND',
						'inventori_amprah.status' => '= ?',
						'AND',
						'inventori_amprah.kode_amprah' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
					);

					$paramValue = array(
						$parameter['from'], $parameter['to'], $UserData['data']->unit, 'S'
					);
				} else {
					$paramData = array(
						'inventori_amprah.deleted_at' => 'IS NULL',
						'AND',
						'inventori_amprah.tanggal' => 'BETWEEN ? AND ?',
						'AND',
						'inventori_amprah.unit' => '= ?',
						'AND',
						'NOT inventori_amprah.status' => '= ?',
						'AND',
						'inventori_amprah.kode_amprah' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
					);

					$paramValue = array(
						$parameter['from'], $parameter['to'], $UserData['data']->unit, 'S'
					);
				}
			}
		} else {
			if($UserData['data']->unit == __UNIT_GUDANG__) {
				if($status == 'S') {
					$paramData = array(
						'inventori_amprah.deleted_at' => 'IS NULL',
						'AND',
						'inventori_amprah.status' => '= ?',
						'AND',
						'inventori_amprah.tanggal' => 'BETWEEN ? AND ?'
					);

					$paramValue = array(
						'S', $parameter['from'], $parameter['to']
					);
				} else {
					$paramData = array(
						'inventori_amprah.deleted_at' => 'IS NULL',
						'AND',
						'NOT inventori_amprah.status' => '= ?',
						'AND',
						'inventori_amprah.tanggal' => 'BETWEEN ? AND ?'
					);

					$paramValue = array(
						'S', $parameter['from'], $parameter['to']
					);
				}
			} else {
				if($status == 'S') {
					$paramData = array(
						'inventori_amprah.deleted_at' => 'IS NULL',
						'AND',
						'inventori_amprah.unit' => '= ?',
						'AND',
						'inventori_amprah.status' => '= ?',
						'AND',
						'inventori_amprah.tanggal' => 'BETWEEN ? AND ?'
					);

					$paramValue = array(
						$UserData['data']->unit, 'S', $parameter['from'], $parameter['to']
					);
				} else {
					$paramData = array(
						'inventori_amprah.deleted_at' => 'IS NULL',
						'AND',
						'inventori_amprah.unit' => '= ?',
						'AND',
						'NOT inventori_amprah.status' => '= ?',
						'AND',
						'inventori_amprah.tanggal' => 'BETWEEN ? AND ?'
					);

					$paramValue = array(
						$UserData['data']->unit, 'S', $parameter['from'], $parameter['to']
					);
				}
			}
		}

		


		if($parameter['length'] < 0) {
			$data = self::$query->select('inventori_amprah', array(
				'uid',
				'unit',
				'kode_amprah',
				'pegawai',
				'diproses',
				'tanggal',
				'status',
				'created_at',
				'updated_at'
			))
			->where($paramData, $paramValue)
			->execute();
		} else {
			
			$data = self::$query->select('inventori_amprah', array(
				'uid',
				'unit',
				'kode_amprah',
				'pegawai',
				'diproses',
				'tanggal',
				'status',
				'created_at',
				'updated_at'
			))
			->where($paramData, $paramValue)
			->offset(intval($parameter['start']))
			->limit(intval($parameter['length']))
			->execute();
		}

		$data['response_draw'] = $parameter['draw'];

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['tanggal'] = date('d F Y', strtotime($value['tanggal']));

			$Pegawai = new Pegawai(self::$pdo);
			$data['response_data'][$key]['pegawai'] = $Pegawai::get_detail($value['pegawai'])['response_data'][0];
			$data['response_data'][$key]['diproses'] = ((empty($value['diproses'])) ? "-" : $Pegawai::get_detail($value['pegawai'])['response_data'][0]);


			if($value['status'] == 'N') {
				$statusParse = 'Baru';
			} else if($value['status'] == 'S') {
				$statusParse = 'Selesai';
			} else {
				$statusParse = 'Ditolak';
			}
			$data['response_data'][$key]['status_caption'] = $statusParse;
			$autonum++;
		}

		$dataTotal = self::$query->select('inventori_amprah', array(
			'uid',
			'unit',
			'kode_amprah',
			'pegawai',
			'diproses',
			'tanggal',
			'status',
			'created_at',
			'updated_at'
		))
		->where($paramData, $paramValue)
		->execute();

		$data['recordsTotal'] = count($dataTotal['response_data']);
		$data['recordsFiltered'] = count($dataTotal['response_data']);
		$data['length'] = intval($parameter['length']);
		$data['start'] = intval($parameter['start']);

		return $data;
	}

	public function get_amprah_detail($parameter) {
		$data = self::$query->select('inventori_amprah', array(
			'uid',
			'unit',
			'pegawai',
			'kode_amprah',
			'diproses',
			'keterangan',
			'tanggal',
			'created_at',
			'updated_at'
		))
		->where(array(
			'inventori_amprah.uid' => '= ?',
			'AND',
			'inventori_amprah.deleted_at' => 'IS NULL'
		), array(
			$parameter
		))
		->execute();
		if(count($data['response_data']) > 0) {
			$data['response_data'][0]['tanggal'] = date('d F Y', strtotime($data['response_data'][0]['tanggal']));
			$Pegawai = new Pegawai(self::$pdo);
			$data['response_data'][0]['pegawai_detail'] = $Pegawai::get_detail($data['response_data'][0]['pegawai'])['response_data'][0];
			$Unit = new Unit(self::$pdo);
			$data['response_data'][0]['pegawai_detail']['unit_detail'] = $Unit::get_unit_detail($data['response_data'][0]['pegawai_detail']['unit'])['response_data'][0];
			$data['response_data'][0]['diproses_detail'] = $Pegawai::get_detail($data['response_data'][0]['diproses'])['response_data'][0];

			$data_detail = self::$query->select('inventori_amprah_detail', array(
				'id',
				'amprah',
				'item',
				'jumlah',
				'created_at',
				'updated_at'
			))
			->where(array(
				'inventori_amprah_detail.deleted_at' => 'IS NULL',
				'AND',
				'inventori_amprah_detail.amprah' => '= ?'
			), array(
				$parameter
			))
			->execute();
			if(count($data_detail['response_data']) > 0) {
				$detail_amprah = array();
				foreach ($data_detail['response_data'] as $key => $value) {
					//Batch Gudang
					$value['batch'] = self::get_item_batch($value['item']);

					$ItemDetail = self::get_item_detail($value['item'])['response_data'][0];
					$ItemDetail['satuan_terkecil'] = self::get_satuan_detail($ItemDetail['satuan_terkecil'])['response_data'][0];
					$value['item'] = $ItemDetail;

					array_push($detail_amprah, $value);
				}
				$data['response_data'][0]['amprah_detail'] = $detail_amprah;
			}
		}
		return $data;
	}

	private function proses_amprah($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$uid = parent::gen_uuid();

		//Get Last AI tahun ini
		$lastID = self::$query->select('inventori_amprah_proses', array(
			'uid'
		))
		->where(array(
			'EXTRACT(year FROM inventori_amprah_proses.created_at)' => '= ?'
		), array(
			date('Y')
		))
		->execute();

		$worker = self::$query->insert('inventori_amprah_proses', array(
			'uid' => $uid,
			'kode' => str_pad(count($lastID['response_data']) + 1, 5, '0', STR_PAD_LEFT) . '/' . $UserData['data']->unit_kode . '/AMP-OUT/' . date('m') . '/' . date('Y'),
			'amprah' => $parameter['amprah'],
			'pegawai' => $UserData['data']->uid,
			'tanggal' => parent::format_date(),
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
					'inventori_amprah_proses',
					'I',
					json_encode($parameter),
					parent::format_date(),
					'N',
					$UserData['data']->log_id
				),
				'class' => __CLASS__
			));

			//Save Detail
			foreach ($parameter['data'] as $key => $value) {
				foreach ($value['batch'] as $BKey => $BValue) {
					$amprah_proses_detail = self::$query->insert('inventori_amprah_proses_detail', array(
						'amprah_proses' => $uid,
						'item' => $key,
						'batch' => $BValue['batch'],
						'qty' => $BValue['disetujui'],
						'keterangan' => $parameter['data'][$key]['keterangan'],
						'created_at' => parent::format_date(),
						'updated_at' => parent::format_date()
					))
					->returning('id')
					->execute();

					if($amprah_proses_detail['response_result'] > 0) {
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
								$amprah_proses_detail['response_unique'],
								$UserData['data']->uid,
								'inventori_amprah_proses_detail',
								'I',
								json_encode($parameter['data']['batch']),
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class' => __CLASS__
						));


						//Proses Kurang Stok
						$lastStockMinus = self::$query->select('inventori_stok', array(
							'stok_terkini'
						))
						->where(array(
							'inventori_stok.barang' => '= ?',
							'AND',
							'inventori_stok.batch' => '= ?',
							'AND',
							'inventori_stok.gudang' => '= ?'
						), array(
							$key, $BValue['batch'], __GUDANG_UTAMA__
						))
						->execute();
						$terkiniMinus = $lastStockMinus['response_data'][0]['stok_terkini'] - $BValue['disetujui'];
						$minus_stock = self::$query->update('inventori_stok', array(
							'stok_terkini' => $terkiniMinus
						))
						->where(array(
							'inventori_stok.barang' => '= ?',
							'AND',
							'inventori_stok.batch' => '= ?',
							'AND',
							'inventori_stok.gudang' => '= ?'
						), array(
							$key, $BValue['batch'], __GUDANG_UTAMA__
						))
						->execute();

						if($minus_stock['response_result'] > 0) {
							$stok_log = self::$query->insert('inventori_stok_log', array(
								'barang' => $key,
								'batch' => $BValue['batch'],
								'gudang' => __GUDANG_UTAMA__,
								'masuk' => 0,
								'keluar' => $BValue['disetujui'],
								'saldo' => $terkiniMinus,
								'type' => __STATUS_AMPRAH__
							))
							->execute();
						}

						//Proses Tambah Stok
						//Dapatkan stok point
						$Unit = new Unit(self::$pdo);
						$UnitDetail = $Unit::get_unit_detail($parameter['dari_unit']);

						$lastStockPlus = self::$query->select('inventori_stok', array(
							'stok_terkini'
						))
						->where(array(
							'inventori_stok.barang' => '= ?',
							'AND',
							'inventori_stok.batch' => '= ?',
							'AND',
							'inventori_stok.gudang' => '= ?'
						), array(
							$key, $BValue['batch'], $UnitDetail['response_data'][0]['gudang']
						))
						->execute();
						$terkiniPlus = $lastStockPlus['response_data'][0]['stok_terkini'] + $BValue['disetujui'];
						
						//Check Apakah stock point ada ?
						$check_stok_point = self::$query->select('inventori_stok', array(
							'id'
						))
						->where(array(
							'inventori_stok.barang' => '= ?',
							'AND',
							'inventori_stok.batch' => '= ?',
							'AND',
							'inventori_stok.gudang' => '= ?'
						), array(
							$key, $BValue['batch'], $UnitDetail['response_data'][0]['gudang']
						))
						->execute();
						if(count($check_stok_point['response_data']) > 0) {
							$plus_stock = self::$query->update('inventori_stok', array(
								'stok_terkini' => $terkiniPlus
							))
							->where(array(
								'inventori_stok.barang' => '= ?',
								'AND',
								'inventori_stok.batch' => '= ?',
								'AND',
								'inventori_stok.gudang' => '= ?'
							), array(
								$key, $BValue['batch'], $UnitDetail['response_data'][0]['gudang']
							))
							->execute();
						} else {
							$plus_stock = self::$query->insert('inventori_stok', array(
								'barang' => $key,
								'batch' => $BValue['batch'],
								'gudang' => $UnitDetail['response_data'][0]['gudang'],
								'stok_terkini' => $terkiniPlus
							))
							->execute();
						}

						if($plus_stock['response_result'] > 0) {
							$stok_log = self::$query->insert('inventori_stok_log', array(
								'barang' => $key,
								'batch' => $BValue['batch'],
								'gudang' => $UnitDetail['response_data'][0]['gudang'],
								'masuk' => $BValue['disetujui'],
								'keluar' => 0,
								'saldo' => $terkiniPlus,
								'type' => __STATUS_AMPRAH__
							))
							->execute();
						}




						if($minus_stock['response_result'] > 0 && $plus_stock['response_result'] > 0) {
							//Update Status amprah menjadi selesai
							$update_amprah = self::$query->update('inventori_amprah', array(
								'status' => 'S',
								'updated_at' => parent::format_date()
							))
							->where(array(
								'inventori_amprah.deleted_at' => 'IS NULL',
								'AND',
								'inventori_amprah.uid' => '= ?'
							), array(
								$parameter['amprah']
							))
							->execute();
						}
					}
				}
			}
		}

		return $worker;
	}

	private function get_amprah_proses_detail($parameter) {
		$data = self::$query->select('inventori_amprah_proses', array(
			'uid',
			'kode',
			'pegawai',
			'tanggal'
		))
		->where(array(
			'inventori_amprah_proses.deleted_at' => 'IS NULL',
			'AND',
			'inventori_amprah_proses.amprah' => '= ?'
		), array(
			$parameter
		))
		->execute();

		foreach ($data['response_data'] as $key => $value) {
			$Pegawai = new Pegawai(self::$pdo);
			$PegawaiDetail = $Pegawai::get_detail($value['pegawai']);
			$data['response_data'][$key]['pegawai'] = $PegawaiDetail['response_data'][0];
			$detail_proses = self::$query->select('inventori_amprah_proses_detail', array(
				'id',
				'item',
				'batch',
				'keterangan',
				'qty'
			))
			->where(array(
				'inventori_amprah_proses_detail.deleted_at' => 'IS NULL',
				'AND',
				'inventori_amprah_proses_detail.amprah_proses' => '= ?'
			), array(
				$value['uid']
			))
			->execute();
			$autonum = 1;
			foreach ($detail_proses['response_data'] as $DKey => $DValue) {
				$detail_proses['response_data'][$DKey]['autonum'] = $autonum;
				$autonum++;
			}
			$data['response_data'][$key]['detail'] = $detail_proses['response_data'];
		}

		return $data;
	}

	public function get_stok() {
		$data = self::$query->select('inventori_stok', array(
			'id',
			'barang',
			'batch',
			'gudang',
			'stok_terkini'
		))
		->execute();
		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['barang'] = self::get_item_detail($value['barang'])['response_data'][0];
			$data['response_data'][$key]['batch'] = self::get_batch_detail($value['batch'])['response_data'][0];
			$data['response_data'][$key]['batch']['expired_date'] = date('d F Y', strtotime($data['response_data'][$key]['batch']['expired_date']));
			
			$autonum++;
		}
		return $data;
	}

	public function get_stok_log() {
		$data = self::$query->select('inventori_stok_log', array(
			'id',
			'barang',
			'batch',
			'gudang',
			'masuk',
			'keluar',
			'saldo'
		))
		->where(array(
			'inventori_stok_log.type' => '= ?'
		), array(
			__STATUS_STOK_AWAL__
		))
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['barang'] = self::get_item_detail($value['barang'])['response_data'][0];
			$data['response_data'][$key]['batch'] = self::get_batch_detail($value['batch'])['response_data'][0];
			$data['response_data'][$key]['batch']['expired_date'] = date('d F Y', strtotime($data['response_data'][$key]['batch']['expired_date']));
			
			$autonum++;
		}
		return $data;	
	}

	private function tambah_stok_awal($parameter) {
		//Check ketersediaan batch
		$batchCheck = self::$query->select('inventori_batch', array(
			'uid'
		))
		->where(array(
			'inventori_batch.barang' => '= ?',
			'AND',
			'inventori_batch.batch' => '= ?'
		), array(
			$parameter['item'],
			$parameter['batch']
		))
		->execute();

		if(count($batchCheck['response_data']) > 0) {
			$targetBatch = $batchCheck['response_data'][0]['uid'];
			$batchEnabling = self::$query->update('inventori_batch', array(
				'deleted_at' => NULL
			))
			->where(array(
				'inventori_batch.uid' => '= ?'
			), array(
				$targetBatch
			))
			->execute();

		} else {
			$targetBatch = parent::gen_uuid();
			$batchNew = self::$query->insert('inventori_batch', array(
				'uid' => $targetBatch,
				'barang' => $parameter['item'],
				'batch' => strtoupper($parameter['batch']),
				'expired_date' => date('Y-m-d', strtotime($parameter['exp'])),
				'created_at' => parent::format_date(),
				'updated_at' => parent::format_date()
			))
			->execute();
		}

		$worker = self::$query->select('inventori_stok', array(
			'id',
			'stok_terkini'
		))
		->where(array(
			'inventori_stok.barang' => '= ?',
			'AND',
			'inventori_stok.batch' => '= ?',
			'AND',
			'inventori_stok.gudang' => '= ?'
		), array(
			$parameter['item'],
			$targetBatch,
			$parameter['gudang']
		))
		->execute();

		if(count($worker['response_data']) > 0) {
			$endTotal = ($worker['response_data'][0]['stok_terkini'] + $parameter['qty']);
			$proceedStokPoint = self::$query->update('inventori_stok', array(
				'stok_terkini' => $endTotal
			))
			->where(array(
				'inventori_stok.barang' => '= ?',
				'AND',
				'inventori_stok.batch' => '= ?',
				'AND',
				'inventori_stok.gudang' => '= ?'
			), array(
				$parameter['item'],
				$targetBatch,
				$parameter['gudang']
			))
			->execute();
		} else {
			$endTotal = $parameter['qty'];
			$proceedStokPoint = self::$query->insert('inventori_stok', array(
				'barang' => $parameter['item'],
				'batch' => $targetBatch,
				'gudang' => $parameter['gudang'],
				'stok_terkini' => $parameter['qty']
			))
			->execute();
		}

		if($proceedStokPoint['response_result'] > 0) {
			//Stok Log
			$stok_log_worker = self::$query->insert('inventori_stok_log', array(
				'barang' => $parameter['item'],
				'batch' => $targetBatch,
				'gudang' => $parameter['gudang'],
				'masuk' => $parameter['qty'],
				'keluar' => 0,
				'saldo' => $endTotal,
				'type' => __STATUS_STOK_AWAL__
			))
			->execute();
		}

		return $proceedStokPoint;
	}
//===========================================================================================DELETE
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
}