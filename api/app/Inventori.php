<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\Authorization as Authorization;
use PondokCoder\QueryException as QueryException;
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
			default:
				return array();
				break;
		}
	}
//===========================================================================================KATEGORI
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

	private function get_satuan_detail($parameter) {
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

	private function get_gudang_detail($parameter) {
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
			'nama',
			'kategori',
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
			$data['response_data'][$key]['kategori'] = self::get_kategori_detail($value['kategori'])['response_data'][0];
			$data['response_data'][$key]['manufacture'] = self::get_manufacture_detail($value['manufacture'])['response_data'][0];
			$autonum++;
		}
		return $data;
	}

	private function get_item_detail($parameter) {
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

			//Prepare Image File
			$data['response_data'][$key]['image'] = file_exists('../assets/images/inventori/' . $value['uid'] . '.png');

			//GET Item Info
			$data['response_data'][$key]['kombinasi'] = self::get_kombinasi($value['uid']);
			$data['response_data'][$key]['konversi'] = self::get_konversi($value['uid']);
			$data['response_data'][$key]['varian'] = self::get_varian($value['uid']);
		}
		return $data;
	}

	private function tambah_item($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$error_count = 0;
		$uid = parent::gen_uuid();

		//Check Ketersediaan Segment / Partial Save
		if(isset($parameter['segment_informasi'])) {
			$check = self::duplicate_check(array(
				'table' => 'master_inv',
				'check' => $parameter['segment_informasi']['nama']
			));

			if(count($check['response_data']) > 0) {
				$check['response_message'] = 'Duplicate data detected';
				$check['response_result'] = 0;
				unset($check['response_data']);
				return $check;
			} else {
				$worker = self::$query->insert('master_inv', array(
					'uid' => $uid,
					'nama' => $parameter['segment_informasi']['nama'],
					'kode_barang' => $parameter['segment_informasi']['kode'],
					'kategori' => $parameter['segment_informasi']['kategori'],
					'manufacture' => $parameter['segment_informasi']['manufacture'],
					'keterangan' => $parameter['segment_informasi']['keterangan'],
					'satuan_terkecil' => $parameter['segment_informasi']['satuan_terkecil'],
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
							'master_inv',
							'I',
							parent::format_date(),
							'N',
							$UserData['data']->log_id
						),
						'class' => __CLASS__
					));
				} else {
					$error_count += 1;
				}

				if($parameter['save_mode'] == true) {
					$worker['response_uid'] = $uid;
				}
			}	
		}

		if(isset($parameter['segment_satuan'])) {

			//Save Data Konversi
			foreach ($parameter['segment_satuan']['populate_konversi'] as $key => $value) {
				//check if exist
				$check = self::$query->select('master_inv_satuan_konversi', array(
					'barang'
				))
				->where(array(
					'barang' => '= ?',
					'AND',
					'dari_satuan' => '= ?',
					'AND',
					'ke_satuan' => '= ?'
				), array(
					$uid,
					$value['getDari'],
					$value['getKe']
				))
				->execute();

				if(count($check['response_data']) > 0) {
					$worker = self::$query->update('master_inv_satuan_konversi', array(
						'rasio' => $value['getRasio']
					))
					->where(array(
						'dari_satuan' => '= ?',
						'AND',
						'ke_satuan' => '= ?'
					), array(
						$value['getDari'],
						$value['getKe']
					))
					->execute();

					if($worker['response_result'] > 0) {
						//
					} else {
						$error_count += 1;
					}
				} else {
					$worker = self::$query->insert('master_inv_satuan_konversi', array(
						'rasio' => $value['getRasio'],
						'dari_satuan' => $value['getDari'],
						'ke_satuan' => $value['getKe'],
						'barang' => $uid
					))
					->execute();

					if($worker['response_result'] > 0) {
						//
					} else {
						$error_count += 1;
					}
				}
			}

			//Save Data Varian
			foreach ($parameter['segment_satuan']['populate_varian'] as $key => $value) {
				//Key = Satuan
				//Value = array kemasan
				foreach ($value as $VarianKy => $VarianValue) {
					$check = self::$query->select('master_inv_satuan_varian', array(
						'nama'
					))
					->where(array(
						'master_inv_satuan_varian.barang' => '= ?',
						'AND',
						'master_inv_satuan_varian.satuan' => '= ?',
						'AND',
						'master_inv_satuan_varian.nama' => '= ?'
					), array(
						$uid, $key, $VarianValue
					))
					->execute();

					if($check['response_result'] > 0) {
						//update
					} else {
						//insert
						$worker = self::$query->insert('master_inv_satuan_varian', array(
							'barang' => $uid,
							'satuan' => $key,
							'nama' => $VarianValue,
							'created_at' => parent::format_date(),
							'updated_at' => parent::format_date()
						))
						->execute();
						if($worker['response_result'] > 0) {
							//log
						} else {
							$error_count += 1;
						}
					}
				}
			}
		}
		$worker['response_error'] = $error_count;
		return $worker;
	}

	private function edit_item($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$error_count = 0;

		//Check Ketersediaan Segment / Partial Save
		if(isset($parameter['segment_informasi'])) {
			$check = self::duplicate_check(array(
				'table' => 'master_inv',
				'check' => $parameter['segment_informasi']['nama']
			));

			if(count($check['response_data']) > 0) {
				$check['response_message'] = 'Duplicate data detected';
				$check['response_result'] = 0;
				unset($check['response_data']);
				return $check;
			} else {
				$worker = self::$query->update('master_inv', array(
					'nama' => $parameter['segment_informasi']['nama'],
					'kode_barang' => $parameter['segment_informasi']['kode'],
					'kategori' => $parameter['segment_informasi']['kategori'],
					'manufacture' => $parameter['segment_informasi']['manufacture'],
					'satuan_terkecil' => $parameter['segment_informasi']['satuan_terkecil'],
					'keterangan' => $parameter['segment_informasi']['keterangan'],
					'created_at' => parent::format_date(),
					'updated_at' => parent::format_date()
				))
				->where(array(
					'uid' => $parameter['uid']
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
							'master_inv',
							'I',
							parent::format_date(),
							'N',
							$UserData['data']->log_id
						),
						'class' => __CLASS__
					));
				} else {
					$error_count += 1;
				}

				if($parameter['save_mode'] == true) {
					$worker['response_uid'] = $uid;
				}
			}
		}

		if(isset($parameter['segment_satuan'])) {

			//Save Data Konversi
			foreach ($parameter['segment_satuan']['populate_konversi'] as $key => $value) {
				//check if exist
				$check = self::$query->select('master_inv_satuan_konversi', array(
					'barang'
				))
				->where(array(
					'barang' => '= ?',
					'AND',
					'dari_satuan' => '= ?',
					'AND',
					'ke_satuan' => '= ?'
				), array(
					$parameter['uid'],
					$value['getDari'],
					$value['getKe']
				))
				->execute();

				if(count($check['response_data']) > 0) {
					$worker = self::$query->update('master_inv_satuan_konversi', array(
						'rasio' => $value['getRasio']
					))
					->where(array(
						'dari_satuan' => '= ?',
						'AND',
						'ke_satuan' => '= ?'
					), array(
						$value['getDari'],
						$value['getKe']
					))
					->execute();

					if($worker['response_result'] > 0) {
						//
					} else {
						$error_count += 1;
					}
				} else {
					$worker = self::$query->insert('master_inv_satuan_konversi', array(
						'rasio' => $value['getRasio'],
						'dari_satuan' => $value['getDari'],
						'ke_satuan' => $value['getKe'],
						'barang' => $parameter['uid']
					))
					->execute();

					if($worker['response_result'] > 0) {
						//
					} else {
						$error_count += 1;
					}
				}
			}

			//Save Data Varian
			foreach ($parameter['segment_satuan']['populate_varian'] as $key => $value) {
				//Key = Satuan
				//Value = array kemasan
				foreach ($value as $VarianKy => $VarianValue) {
					$check = self::$query->select('master_inv_satuan_varian', array(
						'nama'
					))
					->where(array(
						'master_inv_satuan_varian.barang' => '= ?',
						'AND',
						'master_inv_satuan_varian.satuan' => '= ?',
						'AND',
						'master_inv_satuan_varian.nama' => '= ?'
					), array(
						$uid, $key, $VarianValue
					))
					->execute();

					if($check['response_result'] > 0) {
						//update
						$worker = self::$query->update('master_inv_satuan_varian', array(
							'nama' => $VarianValue,
							'updated_at' => parent::format_date()
						))
						->where(array(
							'master_inv_satuan_varian.id' => '= ?',
							'AND',
							'master_inv_satuan_varian.deleted_at' => 'IS NULL',
						), array(
							$check['response_data'][0]['id']
						))
						->execute();
						if($worker['response_result'] > 0) {
							//log
						} else {
							$error_count += 1;
						}
					} else {
						//insert
						$worker = self::$query->insert('master_inv_satuan_varian', array(
							'barang' => $uid,
							'satuan' => $key,
							'nama' => $VarianValue,
							'created_at' => parent::format_date(),
							'updated_at' => parent::format_date()
						))
						->execute();
						if($worker['response_result'] > 0) {
							//log
						} else {
							$error_count += 1;
						}
					}
				}
			}
		}
		$worker['response_error'] = $error_count;
		return $worker;
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