<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Penjamin as Penjamin;
use PondokCoder\Utility as Utility;
use PondokCoder\Antrian as Antrian;
use PondokCoder\Pasien as Pasien;

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
				
				case 'antrian':					
					return self::get_antrian();		//-> get antrian labor_order
					break;
				
				case 'get-data-pasien-antrian':
					return self::get_data_pasien_antrian($parameter[2]);
					break;

				case 'laboratorium-order-detail-item':
					return self::get_laboratorium_order_detail_item($parameter[2]);
					break;
				
				case 'get-laboratorium-lampiran':
					return self::get_laboratorium_lampiran($parameter[2]);
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

				case 'add-order-lab':
					return self::add_order_lab($parameter);
					break;

				case 'update-hasil-lab':
					return self::update_hasil_lab($parameter);
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
				'id',
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


	/*==================== FUNCTION FOR PROCCESS LAB DATA =====================*/
	private function get_antrian(){
		$data = self::$query
				->select('lab_order', 
					array(
						'uid',
						'asesmen as uid_asesmen',
						'waktu_order'
					)
				)
				->join('asesmen', array(
						'antrian as uid_antrian'
					)
				)
				->join('antrian', array(
						'pasien as uid_pasien',
						'dokter as uid_dokter',
						'departemen as uid_poli',
						'penjamin as uid_penjamin',
						'waktu_masuk'
					)
				)
				->join('pasien', array(
						'nama as pasien',
						'no_rm'
					)
				)
				->join('master_poli', array(
						'nama as departemen'
					)
				)
				->join('pegawai', array(
						'nama as dokter'
					)
				)
				->join('master_penjamin', array(
						'nama as penjamin'
					)
				)
				->join('kunjungan', array(
						'pegawai as uid_resepsionis'
					)
				)
				->on(array(
						array('lab_order.asesmen', '=', 'asesmen.uid'),
						array('asesmen.antrian','=','antrian.uid'),
						array('pasien.uid','=','antrian.pasien'),
						array('master_poli.uid','=','antrian.departemen'),
						array('pegawai.uid','=','antrian.dokter'),
						array('master_penjamin.uid','=','antrian.penjamin'),
						array('kunjungan.uid','=','antrian.kunjungan')
					)
				)
				->where(array(
						'lab_order.deleted_at' => 'IS NULL'
					)
				)
				->order(
					array(
						'lab_order.waktu_order' => 'DESC'
					)
				)
				->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['waktu_order'] = date('d F Y', strtotime($value['waktu_order'])) . ' - [' . date('H:i', strtotime($value['waktu_order'])) . ']';
			$autonum++;
		}

		return $data;
	}

	/*------------------- GET DATA PASIEN and ANTRIAN --------------------*/
	private function get_data_pasien_antrian($parameter){
		$get_uid_asesmen = self::$query
			->select('lab_order', array(
					'asesmen'
				)
			)
			->where(
				array(
					'lab_order.uid' => '= ?'
				),
				array($parameter)
			)
			->execute();

		$result = "";
		if ($get_uid_asesmen['response_result'] > 0){
			$get_uid_antrian = self::$query
				->select('asesmen', array('antrian'))
				->where(array('asesmen.uid' => '= ?'), 
					array($get_uid_asesmen['response_data'][0]['asesmen']))
				->execute();

			$uid_antrian = $get_uid_antrian['response_data'][0]['antrian'];

			$antrian = new Antrian(self::$pdo);
			$result = $antrian->get_data_pasien_dan_antrian($uid_antrian);	//call function for get data antrian and 
																			//pasien in class antrian

		}
		
		return $result;
	}

	private function get_laboratorium_order_detail_item($parameter){
		$data = self::$query
			->select('lab_order_detail', array(
					'id',
					'lab_order',
					'tindakan'
				)
			)
			->where(array(
					'lab_order_detail.lab_order' => '= ?',
					'AND',
					'lab_order_detail.deleted_at' => 'IS NULL'
				), array($parameter)
			)
			->execute();

		foreach ($data['response_data'] as $key => $value){
			$data_lab = self::get_lab_detail_data_only($value['tindakan']);
			$data['response_data'][$key]['kode'] = $data_lab['response_data'][0]['kode'];
			$data['response_data'][$key]['nama'] = $data_lab['response_data'][0]['nama'];
			
			$data['response_data'][$key]['nilai_item'] = [];
			$data_nilai = self::get_laboratorium_order_nilai_item($value['tindakan']);
			$data['response_data'][$key]['nilai_item'] = $data_nilai['response_data'];
			
		}

		return $data;
	}

	public function get_lab_detail_data_only($parameter){
		$data_lab = self::$query
			->select('master_lab', array(
					'uid',
					'kode',
					'nama'
				)
			)
			->where(
				array('master_lab.uid' => '= ?')
				, array($parameter)
			)
			->execute();
		
			return $data_lab;
	}

	private function get_laboratorium_order_nilai_item($parameter){
		$data = self::$query
			->select('lab_order_nilai', array(
					'id',
					'lab_order as uid_lab_order',
					'tindakan as uid_tindakan',
					'id_lab_nilai',
					'nilai'
				)
			)
			->join('master_lab_nilai', 
				array(
					'keterangan',
					'nilai_min',
					'nilai_maks',
					'satuan'
				)
			)
			->on(array(
					array('master_lab_nilai.id', '=', 'lab_order_nilai.id_lab_nilai')
				)
			)
			->where(array(
					'lab_order_nilai.tindakan' => '= ?',
					'AND',
					'lab_order_nilai.deleted_at' => 'IS NULL'
				), array($parameter)
			)
			->execute();

		return $data;
	}

	public function get_laboratorium_order_nilai_per_item($lab_order, $tindakan, $id_nilai){
		$data = self::$query
			->select('lab_order_nilai', array(
					'id',
					'lab_order',
					'tindakan',
					'id_lab_nilai',
					'nilai'
				)
			)
			->where(array(
					'lab_order_nilai.lab_order' => '= ?',
					'AND',
					'lab_order_nilai.tindakan' => '= ?',
					'AND',
					'lab_order_nilai.id_lab_nilai' => '= ?',
					'AND',
					'lab_order_nilai.deleted_at' => 'IS NULL'
				), array(
					$lab_order,
					$tindakan,
					$id_nilai
				)
			)
			->execute();

		return $data;
	}

	private function get_laboratorium_lampiran($parameter){
		$data = self::$query
			->select('lab_order_document', array(
					'id', 	
					'lab_order',
					'lampiran',
					'created_at'
				)
			)
			->where(array(
					'lab_order_document.lab_order' => '= ?',
					'AND',
					'lab_order_document.deleted_at' => 'IS NULL'
				), array($parameter)
			)
			->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['file_location'] = '../document/laboratorium/' . $parameter . '/' . $value['lampiran'];
			$autonum++;
		}

		return $data;
	}

	private function add_order_lab($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$get_antrian = new Antrian(self::$pdo);
		$antrian = $get_antrian->get_antrian_detail('antrian', $parameter['uid_antrian']);
		
		$result = [];
		if ($antrian['response_result'] > 0){

			$data_antrian = $antrian['response_data'][0];		//get antrian data

			//get uid asesmmen based by antrian data
			$get_asesmen = self::$query->select('asesmen', array('uid'))
				->where(array(
						'asesmen.deleted_at' => 'IS NULL',
						'AND',
						'asesmen.poli' => '= ?',
						'AND',
						'asesmen.kunjungan' => '= ?',
						'AND',
						'asesmen.antrian' => '= ?',
						'AND',
						'asesmen.pasien' => '= ?',
						'AND',
						'asesmen.dokter' => '= ?'
					), array(
						$data_antrian['departemen'],
						$data_antrian['kunjungan'],
						$parameter['uid_antrian'],
						$data_antrian['pasien'],
						$data_antrian['dokter']
					)
				)
				->execute();
			
			$uidLabOrder = "";
			$statusOrder = "NEW";	//parameter to set status order, set "NEW" for default
			if ($get_asesmen['response_result'] > 0){
				$uidAsesmen = $get_asesmen['response_data'][0]['uid'];

				$checkLabOrder = self::$query
					->select('lab_order', array('uid'))
					->where(
						array(
							'lab_order.asesmen'		=>	'= ?',
							'AND',
							'lab_order.deleted_at'	=>	'IS NULL'
						)
						, array($uidAsesmen)
					)
					->execute();
				
				if ($checkLabOrder['response_result'] > 0){
					$statusOrder = "OLD";		//$statusOrder will set "OLD" if has ever added
					$uidLabOrder = $checkLabOrder['response_data'][0]['uid'];

					$result['old_lab_order'] = $checkLabOrder;
				}

			} else {
				//new asesmen
				$uidAsesmen = parent::gen_uuid();
				$MasterUID = $uidAsesmen;
				$asesmen_poli = self::$query
					->insert('asesmen', 
						array(
							'uid' => $uidAsesmen,
							'poli' => $data_antrian['departemen'],
							'kunjungan' => $data_antrian['kunjungan'],
							'antrian' => $parameter['uid_antrian'],
							'pasien' => $data_antrian['pasien'],
							'dokter' => $data_antrian['dokter'],
							'created_at' => parent::format_date(),
							'updated_at' => parent::format_date()
						)
					)
					->execute();
				
				if($asesmen_poli['response_result'] > 0) {

					$log = parent::log(array(
						'type'=>'activity',
						'column'=>array(
							'unique_target',
							'user_uid',
							'table_name',
							'action',
							'logged_at',
							'status',
							'login_id'
						),
						'value'=>array(
							$uidAsesmen,
							$UserData['data']->uid,
							'asesmen',
							'I',
							parent::format_date(),
							'N',
							$UserData['data']->log_id
						),
						'class'=>__CLASS__
					));
					
					$result['new_asesmen'] = $asesmen_poli;
				}
			}

			if ($statusOrder == "NEW"){

				$uidLabOrder = parent::gen_uuid();
				$labOrder = self::$query
					->insert('lab_order', 
						array(
							'uid'			=>	$uidLabOrder,
							'asesmen'		=>	$uidAsesmen,
							'waktu_order'	=>	parent::format_date(),
							'selesai'		=>	'false',
							'petugas'		=>	$UserData['data']->uid,
							'created_at'	=>	parent::format_date(),
							'updated_at'	=>	parent::format_date()
						)
					)
					->execute();
				
				if($labOrder['response_result'] > 0) {

					$log = parent::log(array(
						'type'=>'activity',
						'column'=>array(
							'unique_target',
							'user_uid',
							'table_name',
							'action',
							'logged_at',
							'status',
							'login_id'
						),
						'value'=>array(
							$uidLabOrder,
							$UserData['data']->uid,
							'lab_order',
							'I',
							parent::format_date(),
							'N',
							$UserData['data']->log_id
						),
						'class'=>__CLASS__
					));
					
					
				}

				$result['new_lab_order'] = $labOrder;
			}
			
			//check if uid labOrder has no empty and add tindakan
			if ($uidLabOrder != ""){

				/*	KETERANGAN
					format json listTindakan:
					listTindakan : { 
						uid_tindakan_1 : uid_penjamin_1,
						uid_tinadkan_2 : uid_penjamin_2 
					}
				*/
				foreach ($parameter['listTindakan'] as $keyTindakan => $valueTindakan) {
					$checkDetailLabor = self::$query
						->select('lab_order_detail', array('id'))
						->where(
							array(
								'lab_order_detail.lab_order'	=> '= ?',
								'AND',
								'lab_order_detail.tindakan'		=> '= ?',
								'AND',
								'lab_order_detail.deleted_at'	=> 'IS NULL'
							), array(
								$uidLabOrder,
								$keyTindakan
							)
						)
						->execute();
					
					if ($checkDetailLabor['response_result'] == 0){
						$addDetailLabor = self::$query
							->insert('lab_order_detail', 
								array(
									'lab_order'		=>	$uidLabOrder,
									'tindakan'		=>	$keyTindakan,
									'penjamin'		=>	$valueTindakan,
									'created_at'	=>	parent::format_date(),
									'updated_at'	=>	parent::format_date()	
								)
							)
							->execute();

						if ($addDetailLabor['response_result'] > 0){
							$log = parent::log(array(
								'type'=>'activity',
								'column'=>array(
									'unique_target',
									'user_uid',
									'table_name',
									'action',
									'logged_at',
									'status',
									'login_id'
								),
								'value'=>array(
									$uidLabOrder . "; ". $keyTindakan,
									$UserData['data']->uid,
									'lab_order_detail',
									'I',
									parent::format_date(),
									'N',
									$UserData['data']->log_id
								),
								'class'=>__CLASS__
							));
							
							$result['new_lab_detail'] = $addDetailLabor;
							$getNilaiTindakanLabor = self::get_lab_detail($keyTindakan);
	
							if ($getNilaiTindakanLabor['response_result'] > 0){
								$nilaiLabor = $getNilaiTindakanLabor['response_data'][0]['nilai'];
								
								foreach ($nilaiLabor as $keyNilai => $valueNilai) {
									
									$getAvailableNilai = self::$query
										->select('lab_order_nilai', array('id'))
										->where(
											array(
												'lab_order_nilai.lab_order' 	=>	'= ?',
												'AND',
												'lab_order_nilai.tindakan'		=>	'= ?',
												'AND',
												'lab_order_nilai.id_lab_nilai'	=>	'= ?',
												'AND',
												'lab_order_nilai.deleted_at'	=>	'IS NULL'
											), array(
												$uidLabOrder,
												$keyTindakan,
												$valueNilai['id']
											)
										)
										->execute();
									
									//check if nilai_lab never added
									if ($getAvailableNilai['response_result'] == 0){  
										$addNilaiLabor = self::$query
											->insert('lab_order_nilai', 
												array(
													'lab_order'		=>	$uidLabOrder,
													'tindakan'		=>	$keyTindakan,
													'id_lab_nilai'	=>	$valueNilai['id'],
													'created_at'	=>	parent::format_date(),
													'updated_at'	=>	parent::format_date()
												)
											)
											->execute();

										if ($addNilaiLabor['response_result'] > 0){
											$log = parent::log(array(
												'type'=>'activity',
												'column'=>array(
													'unique_target',
													'user_uid',
													'table_name',
													'action',
													'logged_at',
													'status',
													'login_id'
												),
												'value'=>array(
													$uidLabOrder . "; ". $keyTindakan,
													$UserData['data']->uid,
													'lab_order_nilai',
													'I',
													parent::format_date(),
													'N',
													$UserData['data']->log_id
												),
												'class'=>__CLASS__
											));

											$result['new_lab_nilai'] = $addNilaiLabor;
										}
									}
	
								}
	
							}
	
						}
					}

				}
				
			}
		}

		return $result;
	}

	private function update_hasil_lab($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$result = [];

		if (isset($parameter['uid_order'])){

			foreach($parameter['data_nilai'] as $key_tindakan => $value_tindakan) {

				foreach($value_tindakan as $key_nilai => $value_nilai){
					$old = self::get_laboratorium_order_nilai_per_item($parameter['uid_order'], $key_tindakan, $key_nilai);
					
					$updateData = self::$query
						->update('lab_order_nilai', array(
								'nilai'			=>	$value_nilai,
								'updated_at'	=>	parent::format_date()
							)
						)
						->where(array(
								'lab_order_nilai.lab_order' 	=> '= ?',
								'AND',
								'lab_order_nilai.tindakan' 		=> '= ?',
								'AND',
								'lab_order_nilai.id_lab_nilai'	=> '= ?',
								'AND',
								'lab_order_nilai.deleted_at'	=> 'IS NULL'
							), array(
								$parameter['uid_order'],
								$key_tindakan,
								$key_nilai
							)
						)
						->execute();

					if ($updateData['response_result'] > 0){
						$log = parent::log(
							array(
								'type'=>'activity',
								'column'=>array(
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
								'value'=>array(
									$parameter['uid_order'],
									$UserData['data']->uid,
									'lab_order_nilai',
									'U',
									json_encode($old),
									json_encode($value_tindakan),
									parent::format_date(),
									'N',
									$UserData['data']->log_id
								),
								'class'=>__CLASS__
							)
						);
					}

					$result['order_detail'] = $updateData;

				}

			}
			
		}

		//create new 
		$folder_structure = '../document/laboratorium/' . $parameter['uid_order'];
		if (!is_dir($folder_structure)){

			if (!mkdir($folder_structure, 0777, true)) {
			    $result['dir_msg'] = 'Failed to create folders...';
			}
			//mkdir('../document/laboratorium/' . $parameter['uid_radiologi_order'], 0755);
		} else {
			$result['dir_msg'] = 'Dir available...';
		}

		if(is_writeable($folder_structure)) {
			$result['response_upload'] = array();
			//$imageDatas = json_decode($_FILES['fileList'], true);

			//get maximum id
			$get_max = self::$query
				->select('lab_order_document', array(
						'id'
					)
				)
				->order(
					array(
						'lab_order_document.created_at' => 'DESC'
					)
				)
				->execute();

			$max = 0; 
			if ($get_max['response_result'] > 0){
				$max = $get_max['response_data'][0]['id'];
			}

			for ($a = 0; $a < count($_FILES['fileList']); $a++) {
				$max++;

				if(!empty($_FILES['fileList']['tmp_name'][$a])) {
					$nama_lampiran = 'L_' . str_pad($max, 6, "0", STR_PAD_LEFT);

					if(move_uploaded_file($_FILES['fileList']['tmp_name'][$a], '../document/laboratorium/' . $parameter['uid_order'] . '/' . $nama_lampiran . '.pdf')) {
						array_push($result['response_upload'], 'Berhasil diupload');
						$lampiran = self::$query
							->insert('lab_order_document', array(
								'lab_order' => $parameter['uid_order'],
								'lampiran' => $nama_lampiran . '.pdf',
								'created_at' => parent::format_date()
							))
							->execute();
						
						$result['response_upload']['response_result'] = 1;
					} else {
						array_push($result['response_upload'], 'Gagal diupload : ' . $_FILES['fileList']['tmp_name'][$a] . ' => ' . $set_code . '-' . $a . '.pdf');
					}
				}
			}
		} else {
			$result['response_upload']['response_message'] = 'Cant write';
			$result['response_upload']['response_result'] = 0;
		}

		if (count($parameter['deletedDocList']) > 0){
			foreach ($parameter['deletedDocList'] as $key => $value) {
				$getLampiran = self::$query
					->select('lab_order_document', array(
							'lampiran'
						)
					)
					->where(array(
							'lab_order_document.id' => '= ?'
						), array($value)
					)
					->execute();

				if ($getLampiran['response_result'] > 0){
					$nama_lampiran_hapus = $getLampiran['response_data'][0]['lampiran'];

					$hapusLampiran = self::$query
						->delete('lab_order_document')
						->where(array(
								'lab_order_document.id' => '= ?'
							), array($value)
						)
						->execute();

					if ($hapusLampiran['response_result'] > 0){
						unlink('../document/laboratorium/' . $parameter['uid_order'] . '/' . $nama_lampiran_hapus);

						$result['response_delete_doc']['response_result'] = 1;
					}

					$result['response_delete_doc']['response_data'] = $hapusLampiran;
				}
			}
		}
		return (is_writable($folder_structure));

		//return count($parameter['deletedDocList']);
		return $result;
	}

	/*===============================================*/
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