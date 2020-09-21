<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Penjamin as Penjamin;
use PondokCoder\Utility as Utility;



class Laboratorium extends Utility {
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
				case 'lokasi':
					return self::get_lokasi();
					break;
				case 'lokasi_detail':
					return self::get_lokasi_detail($parameter[2]);
					break;
				case 'spesimen':
					return self::get_spesimen();
					break;
				case 'spesimen_detail':
					return self::get_spesimen_detail($parameter[2]);
				 	break;
				 case 'lab':
					return self::get_lab();
				 	break;
				case 'lab_detail':
					return self::get_lab_detail($parameter[2]);
					break;
				default:
					return self::get_lab();
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function __POST__($parameter = array()) {
		try {

			switch($parameter['request']) {
				case 'add_kategori':
					return self::add_kategori($parameter);
					break;
				case 'edit_kategori':
					return self::edit_kategori($parameter);
					break;
				case 'add_spesimen':
					return self::add_spesimen($parameter);
					break;
				case 'edit_spesimen':
					return self::edit_spesimen($parameter);
					break;
				case 'add_lokasi':
					return self::add_lokasi($parameter);
					break;
				case 'edit_lokasi':
					return self::edit_lokasi($parameter);
					break;
				case 'add_lab':
					return self::add_lab($parameter);
					break;
				case 'edit_lab':
					return self::edit_lab($parameter);
					break;
			}	
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function __DELETE__($parameter = array()) {
		return self::delete($parameter);
	}

	private function get_kategori(){
		$data = self::$query->select('master_lab_kategori', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_lab_kategori.deleted_at' => 'IS NULL'
		), array())
		->execute();
		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	private function get_kategori_detail($parameter){
		$data = self::$query->select('master_lab_kategori', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_lab_kategori.deleted_at' => 'IS NULL',
			'AND',
			'master_lab_kategori.uid' => '= ?'
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

	private function get_spesimen(){
		$data = self::$query->select('master_lab_spesimen', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_lab_spesimen.deleted_at' => 'IS NULL'
		), array())
		->execute();
		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	private function get_spesimen_detail($parameter){
		$data = self::$query->select('master_lab_spesimen', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_lab_spesimen.deleted_at' => 'IS NULL',
			'AND',
			'master_lab_spesimen.uid' => '= ?'
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

	private function get_lokasi(){
		$data = self::$query->select('master_lab_lokasi', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_lab_lokasi.deleted_at' => 'IS NULL'
		), array())
		->execute();
		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	private function get_lokasi_detail($parameter){
		$data = self::$query->select('master_lab_lokasi', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_lab_lokasi.deleted_at' => 'IS NULL',
			'AND',
			'master_lab_lokasi.uid' => '= ?'
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

	private function get_lab(){
		$data = self::$query->select('master_lab', array(
			'uid',
			'kode',
			'nama',
			'spesimen',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_lab.deleted_at' => 'IS NULL'
		))
		->execute();
		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['spesimen'] = self::get_spesimen_detail($value['spesimen'])['response_data'][0];
			$autonum++;
		}
		return $data;
	}

	private function get_lab_detail($parameter){
		$data = self::$query->select('master_lab', array(
			'uid',
			'kode',
			'nama',
			'keterangan',
			'spesimen',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_lab.deleted_at' => 'IS NULL',
			'AND',
			'master_lab.uid' => '= ?'
		), array(
			$parameter
		))
		->execute();
		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['keterangan'] = (!isset($value['keterangan'])) ? '' : $value['keterangan'];
			$data['response_data'][$key]['spesimen'] = self::get_spesimen_detail($value['spesimen'])['response_data'][0];

			$KategoriLab = self::$query->select('master_lab_kategori_item', array(
				'kategori'
			))
			->where(array(
				'master_lab_kategori_item.deleted_at' => 'IS NULL',
				'AND',
				'master_lab_kategori_item.lab' => '= ?'
			), array(
				$value['uid']
			))
			->execute();
			foreach ($KategoriLab['response_data'] as $KategoriKey => $KategoriValue) {
				$KategoriLab['response_data'][$KategoriKey] = self::get_kategori_detail($KategoriValue['kategori'])['response_data'][0];
			}
			$data['response_data'][$key]['kategori'] = $KategoriLab['response_data'];
			//============================================================================
			$LokasiLab = self::$query->select('master_lab_lokasi_item', array(
				'lokasi'
			))
			->where(array(
				'master_lab_lokasi_item.deleted_at' => 'IS NULL',
				'AND',
				'master_lab_lokasi_item.lab' => '= ?'
			), array(
				$value['uid']
			))
			->execute();
			foreach ($LokasiLab['response_data'] as $LokasiKey => $LokasiValue) {
				$LokasiLab['response_data'][$LokasiKey] = self::get_lokasi_detail($LokasiValue['lokasi'])['response_data'][0];
			}
			$data['response_data'][$key]['lokasi'] = $LokasiLab['response_data'];
			//============================================================================
			$NilaiLab = self::$query->select('master_lab_nilai', array(
				'satuan',
				'nilai_min',
				'nilai_maks',
				'keterangan',
				'created_at',
				'updated_at'
			))
			->where(array(
				'master_lab_nilai.deleted_at' => 'IS NULL',
				'AND',
				'master_lab_nilai.lab' => '= ?'
			), array(
				$value['uid']
			))
			->execute();
			$data['response_data'][$key]['nilai'] = $NilaiLab['response_data'];
			//============================================================================
			$PenjaminLab = self::$query->select('master_poli_tindakan_penjamin', array(
				'uid_poli',
				'uid_penjamin',
				'harga',
				'created_at',
				'updated_at'
			))
			->where(array(
				'master_poli_tindakan_penjamin.deleted_at' => 'IS NULL',
				'AND',
				'master_poli_tindakan_penjamin.uid_tindakan' => '= ?'
			), array(
				$value['uid']
			))
			->execute();
			foreach ($PenjaminLab['response_data'] as $PenjaminKey => $PenjaminValue) {
				$Penjamin = new Penjamin(self::$pdo);
				$PenjaminLab['response_data'][$PenjaminKey]['harga'] = floatval($PenjaminValue['harga']);
				$PenjaminLab['response_data'][$PenjaminKey]['penjamin'] = $Penjamin::get_penjamin_detail($PenjaminValue['uid_penjamin'])['response_data'][0];
			}
			$data['response_data'][$key]['penjamin'] = $PenjaminLab['response_data'];
			//============================================================================
			$autonum++;
		}
		return $data;
	}


//=================================================================================
	private function add_kategori($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table' => 'master_lab_kategori',
			'check' => $parameter['nama']
		));
		if(count($check['response_data']) > 0) {
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();
			$worker = self::$query->insert('master_lab_kategori', array(
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
						'master_lab_kategori',
						'I',
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class' => __CLASS__
				));
			}
		}
	}

	private function edit_kategori($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_kategori_detail($parameter['uid']);

		$worker = self::$query
		->update('master_lab_kategori', array(
			'nama' => $parameter['nama'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_lab_kategori.deleted_at' => 'IS NULL',
			'AND',
			'master_lab_kategori.uid' => '= ?'
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
					'master_lab_kategori',
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

	private function add_spesimen($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table' => 'master_lab_spesimen',
			'check' => $parameter['nama']
		));
		if(count($check['response_data']) > 0) {
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();
			$worker = self::$query->insert('master_lab_spesimen', array(
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
						'master_lab_spesimen',
						'I',
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class' => __CLASS__
				));
			}
		}
	}

	private function edit_spesimen($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_spesimen_detail($parameter['uid']);

		$worker = self::$query
		->update('master_lab_spesimen', array(
			'nama' => $parameter['nama'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_lab_spesimen.deleted_at' => 'IS NULL',
			'AND',
			'master_lab_spesimen.uid' => '= ?'
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
					'master_lab_spesimen',
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

	private function add_lokasi($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table' => 'master_lab_lokasi',
			'check' => $parameter['nama']
		));
		if(count($check['response_data']) > 0) {
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();
			$worker = self::$query->insert('master_lab_lokasi', array(
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
						'master_lab_lokasi',
						'I',
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class' => __CLASS__
				));
			}
		}
	}

	private function edit_lokasi($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_lokasi_detail($parameter['uid']);

		$worker = self::$query
		->update('master_lab_lokasi', array(
			'nama' => $parameter['nama'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_lab_lokasi.deleted_at' => 'IS NULL',
			'AND',
			'master_lab_lokasi.uid' => '= ?'
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
					'master_lab_lokasi',
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

	private function add_lab($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$uid = parent::gen_uuid();
		$worker = self::$query->insert('master_lab', array(
			'uid' => $uid,
			'kode' => $parameter['kode'],
			'nama' => $parameter['nama'],
			'keterangan' => $parameter['keterangan'],
			'spesimen' => $parameter['spesimen'],
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
					'master_lab',
					'I',
					json_encode($parameter),
					parent::format_date(),
					'N',
					$UserData['data']->log_id
				),
				'class' => __CLASS__
			));

			//Kategori Item
			foreach ($parameter['kategori'] as $key => $value) {
				$worker = self::$query->insert('master_lab_kategori_item', array(
					'lab' => $uid,
					'kategori' => $value,
					'created_at' => parent::format_date(),
					'updated_at' => parent::format_date()
				))
				->execute();

				if($worker['response_result'] > 0) {
					//
				}
			}

			//Lokasi Item
			foreach ($parameter['lokasi'] as $key => $value) {
				$worker = self::$query->insert('master_lab_lokasi_item', array(
					'lab' => $uid,
					'lokasi' => $value,
					'created_at' => parent::format_date(),
					'updated_at' => parent::format_date()
				))
				->execute();

				if($worker['response_result'] > 0) {
					//
				}
			}

			//Nilai Item
			foreach ($parameter['nilai'] as $key => $value) {
				$worker = self::$query->insert('master_lab_nilai', array(
					'lab' => $uid,
					'satuan' => $value['satuan'],
					'nilai_maks' => $value['max'],
					'nilai_min' => $value['min'],
					'keterangan' => $value['keterangan'],
					'created_at' => parent::format_date(),
					'updated_at' => parent::format_date()
				))
				->execute();

				if($worker['response_result'] > 0) {
					//
				}
			}
			

			//New Tindakan
			$tindakan = self::$query->insert('master_tindakan', array(
				'uid' => $uid,
				'nama' => 'Laboratorium ' . $parameter['nama'],
				'created_at' => parent::format_date(),
				'updated_at' => parent::format_date()
			))
			->execute();

			if($tindakan['response_result'] > 0) {
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
						'master_tindakan',
						'I',
						json_encode($parameter),
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class' => __CLASS__
				));
			}

			//Penjamin Item
			/*foreach ($parameter['penjamin'] as $key => $value) {
				//Tindakan Harga
				$tindakan_harga = self::$query->insert('master_poli_tindakan_penjamin', array(
					'harga' => $value['harga'],
					'uid_penjamin' => $value['penjamin'],
					'uid_tindakan' => $uid,
					'uid_poli' => 'cd9f8f30-4236-2d8b-46d6-b561f9b2c5a3',//Laboratorium
					'created_at' => parent::format_date(),
					'updated_at' => parent::format_date()
				))
				->execute();

				if($tindakan_harga['response_result'] > 0) {
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
							'master_poli_tindakan_penjamin',
							'I',
							json_encode($parameter),
							parent::format_date(),
							'N',
							$UserData['data']->log_id
						),
						'class' => __CLASS__
					));
				}
			}*/
		}
	}

	private function edit_lab($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$uid = $parameter['uid'];
		$old_value = self::get_lab_detail($uid);
		$worker = self::$query->update('master_lab', array(
			'kode' => $parameter['kode'],
			'nama' => $parameter['nama'],
			'keterangan' => $parameter['keterangan'],
			'spesimen' => $parameter['spesimen'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_lab.uid' => '= ?',
			'AND',
			'master_lab.deleted_at' => 'IS NULL'
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
					'master_lab',
					'U',
					json_encode($old_value),
					json_encode($parameter),
					parent::format_date(),
					'N',
					$UserData['data']->log_id
				),
				'class' => __CLASS__
			));

			$oldKategoriItem = self::$query->select('master_lab_kategori_item', array(
				'id',
				'lab',
				'kategori'
			))
			->where(array(
				'master_lab_kategori_item.lab' => '= ?'
			), array(
				$uid
			))
			->execute();

			//Kategori Item
			$kategoriAdded = array();
			$queryList = array();
			foreach ($oldKategoriItem['response_data'] as $key => $value) {
				if(!in_array($value['kategori'], $kategoriAdded)) {
					array_push($kategoriAdded, $value['kategori']);
				}

				if(!in_array($value['kategori'], $parameter['kategori'])) {
					$delete_kategori_item = self::$query->update('master_lab_kategori_item', array(
						'deleted_at' => parent::format_date()
					))
					->where(array(
						'master_lab_kategori_item.id' => '= ?'
					), array(
						$value['id']
					))
					->execute();
				} else {
					$activate_kategori_item = self::$query->update('master_lab_kategori_item', array(
						'deleted_at' => ''
					))
					->where(array(
						'master_lab_kategori_item.id' => '= ?'
					), array(
						$value['id']
					))
					->execute();
					array_push($queryList, $activate_kategori_item);
				}
			}

			foreach ($parameter['kategori'] as $key => $value) {
				if(!in_array($value, $kategoriAdded)) {
					$newKategori = self::$query->insert('master_lab_kategori_item', array(
						'lab' => $uid,
						'kategori' => $value,
						'created_at' => parent::format_date(),
						'updated_at' => parent::format_date()
					))
					->execute();
				}
			}

			return $queryList;

			//Lokasi Item
			foreach ($parameter['lokasi'] as $key => $value) {
				$worker = self::$query->insert('master_lab_lokasi_item', array(
					'lab' => $uid,
					'lokasi' => $value,
					'created_at' => parent::format_date(),
					'updated_at' => parent::format_date()
				))
				->execute();

				if($worker['response_result'] > 0) {
					//
				}
			}

			//Nilai Item
			foreach ($parameter['nilai'] as $key => $value) {
				$worker = self::$query->insert('master_lab_nilai', array(
					'lab' => $uid,
					'satuan' => $value['satuan'],
					'nilai_maks' => $value['max'],
					'nilai_min' => $value['min'],
					'keterangan' => $value['keterangan'],
					'created_at' => parent::format_date(),
					'updated_at' => parent::format_date()
				))
				->execute();

				if($worker['response_result'] > 0) {
					//
				}
			}
			

			//New Tindakan
			$tindakan = self::$query->insert('master_tindakan', array(
				'uid' => $uid,
				'nama' => 'Laboratorium ' . $parameter['nama'],
				'created_at' => parent::format_date(),
				'updated_at' => parent::format_date()
			))
			->execute();

			if($tindakan['response_result'] > 0) {
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
						'master_tindakan',
						'I',
						json_encode($parameter),
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class' => __CLASS__
				));
			}

			//Penjamin Item
			/*foreach ($parameter['penjamin'] as $key => $value) {
				//Tindakan Harga
				$tindakan_harga = self::$query->insert('master_poli_tindakan_penjamin', array(
					'harga' => $value['harga'],
					'uid_penjamin' => $value['penjamin'],
					'uid_tindakan' => $uid,
					'uid_poli' => 'cd9f8f30-4236-2d8b-46d6-b561f9b2c5a3',//Laboratorium
					'created_at' => parent::format_date(),
					'updated_at' => parent::format_date()
				))
				->execute();

				if($tindakan_harga['response_result'] > 0) {
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
							'master_poli_tindakan_penjamin',
							'I',
							json_encode($parameter),
							parent::format_date(),
							'N',
							$UserData['data']->log_id
						),
						'class' => __CLASS__
					));
				}
			}*/
		}
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

			if($parameter[6] == 'master_lab') {
				//delete kategori item also
				$delete_kategori = self::$query->update('master_lab_kategori_item', array(
					'deleted_at' => parent::format_date()
				))
				->where(array(
					'master_lab_kategori_item.lab' => '= ?'
				), array(
					$parameter[7]
				))
				->execute();

				//delete lokasi item also
				$delete_lokasi = self::$query->update('master_lab_lokasi_item', array(
					'deleted_at' => parent::format_date()
				))
				->where(array(
					'master_lab_lokasi_item.lab' => '= ?'
				), array(
					$parameter[7]
				))
				->execute();

				//delete penjamin item also
				$delete_kategori = self::$query->update('master_poli_tindakan_penjamin', array(
					'deleted_at' => parent::format_date()
				))
				->where(array(
					'master_poli_tindakan_penjamin.uid_tindakan' => '= ?'
				), array(
					$parameter[7]
				))
				->execute();

				//delete nilai also
				$delete_kategori = self::$query->update('master_lab_nilai', array(
					'deleted_at' => parent::format_date()
				))
				->where(array(
					'master_lab_nilai.lab' => '= ?'
				), array(
					$parameter[7]
				))
				->execute();
			}
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