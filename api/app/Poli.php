<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;
use PondokCoder\Penjamin as Penjamin;
use PondokCoder\Tindakan as Tindakan;
use PondokCoder\Pegawai as Pegawai;

class Poli extends Utility {
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
				case 'poli':
					return self::get_poli();
					break;

				case 'poli-available':
					return self::get_poli_editable();
					break;

				case 'poli-detail':
					return self::get_poli_detail($parameter[2]);
					break;

				case 'poli-view-detail':
					//return self::get_poli_tindakan_view_detail($parameter[2]);
					break;
				case 'poli-avail-dokter':
					return self::get_avail_dokter($parameter[2]);
					break;

				case 'poli-set-dokter':
					return self::get_set_dokter($parameter[2]);
					break;
				
				case 'get_poli_tindakan':
					return self::get_poli_tindakan($parameter[2]);
					break;

				case 'poli-avail-perawat':
					return self::get_avail_perawat($parameter[2]);
					break;

				case 'poli-set-perawat':
					return self::get_set_perawat($parameter[2]);
					break;

				default:
					# code...
					break;
			}
		} catch (QueryException $e) {
			return 'Error => '. $e;
		}
	}

	public function __POST__($parameter = array()) {
		switch ($parameter['request']) {
			case 'tambah_poli':
				return self::tambah_poli('master_poli', $parameter);
				break;

			case 'edit_poli':
				return self::edit_poli('master_poli', $parameter);
				break;

			case 'poli_dokter':
				return self::poli_dokter($parameter);
				break;

			case 'poli_dokter_buang':
				return self::poli_dokter_buang($parameter);
				break;

			case 'poli_perawat':
				return self::poli_perawat($parameter);
				break;

			case 'poli_perawat_buang':
				return self::poli_perawat_buang($parameter);
				break;

			default:
				# code...
				break;
		}
	}

	public function __DELETE__($parameter = array()){
		return self::delete_poli($parameter);
	}

	/*=============== GET POLI ================*/
	private function get_poli(){
		$data = self::$query
					->select('master_poli', array(
						'uid',
						'nama',
						'created_at',
						'updated_at'
						)
					)	
					->where(array(
							'master_poli.deleted_at' => 'IS NULL'
						)
					)
					->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $data;
	}

	public function get_poli_editable(){
		$data = self::$query
					->select('master_poli', array(
						'uid',
						'nama',
						'created_at',
						'updated_at'
						)
					)	
					->where(array(
							'master_poli.deleted_at' => 'IS NULL',
							'AND',
							'master_poli.editable' => '= TRUE'
						),array()
					)
					->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $data;
	}

	public function get_poli_detail($parameter) {
		$data = self::$query
				->select('master_poli', array(
						'uid',
						'nama',
						'poli_asesmen',
						'created_at',
						'updated_at'
					)
				)
				->where(array(
							'master_poli.deleted_at' => 'IS NULL',
							'AND',
							'master_poli.uid' => '= ?'
						),
						array($parameter)
					)
					->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;

			$tindakan = self::get_poli_tindakan($parameter);
			$data['response_data'][$key]['tindakan'] = $tindakan['response_data'];
		}

		return $data;
	}

	private function get_poli_tindakan($parameter) {
		$data = self::$query
				->select('master_poli_tindakan_penjamin', array(
						'id',
						'harga',
						'uid_tindakan',
						'uid_penjamin',
						'created_at',
						'updated_at'
					)
				)
				->where(array(
							'master_poli_tindakan_penjamin.deleted_at' => 'IS NULL',
							'AND',
							'master_poli_tindakan_penjamin.uid_poli' => '= ?'
						),
						array($parameter)
				)
				->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$Penjamin = new Penjamin(self::$pdo);
			$Tindakan = new Tindakan(self::$pdo);
			$data['response_data'][$key]['tindakan'] = $Tindakan::get_tindakan_detail($value['uid_tindakan'])['response_data'][0];
			$data['response_data'][$key]['penjamin'] = $Penjamin::get_penjamin_detail($value['uid_penjamin'])['response_data'][0];
			$autonum++;
		}

		return $data;
	}

	private function get_spesifik_poli_tindakan($uid_poli, $uid_tindakan, $uid_penjamin){
		$data = self::$query
				->select('master_poli_tindakan_penjamin', array(
						'id',
						'harga',
						'uid_tindakan',
						'uid_penjamin',
						'created_at',
						'updated_at'
					)
				)
				->where(array(
							'master_poli_tindakan_penjamin.uid_poli' => '= ?',
							'AND',
							'master_poli_tindakan_penjamin.uid_tindakan' => '= ?',
							'AND',
							'master_poli_tindakan_penjamin.uid_penjamin' => '= ?'
						),
						array(
							$uid_poli,
							$uid_tindakan,
							$uid_penjamin
						)
					)
					->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $data;
	}

	private function get_poli_tindakan_view_detail($parameter){
		$data = self::$query
				->select('master_poli_tindakan_penjamin', array(
						'harga',
						'uid_penjamin',
						'created_at',
						'updated_at'
					)
				)
				->join('master_poli', array(
						'nama AS poli'
					)
				)
				->join('master_tindakan', array(
						'nama AS tindakan'
					)
				)
				->on(array(
						array('master_poli_tindakan_penjamin.uid_poli', '=', 'master_poli.uid'),
						array('master_poli_tindakan_penjamin.uid_tindakan', '=', 'master_tindakan.uid')
					)
				)
				->where(array(
						'master_poli_tindakan_penjamin.deleted_at' => 'IS NULL',
						'AND',
						'master_poli_tindakan_penjamin.uid_poli' => '= ?'
					),
					array(
						$parameter
					)
				)
				->execute();




		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $data;
	}

	private function get_avail_dokter($parameter) { //parameter = uid poli
		$Dokter = self::$query->select('pegawai', array(
			'uid',
			'nama AS nama_dokter'
		))
		->join('pegawai_jabatan', array(
			'uid AS uid_jabatan',
			'nama AS nama_jabatan'
		))
		->on(array(
			array('pegawai.jabatan', '=', 'pegawai_jabatan.uid')
		))
		->where(array(
			'pegawai.deleted_at' => 'IS NULL',
			'AND',
			'pegawai_jabatan.nama' => '= ?'
		), array(
			'Dokter'
		))
		->execute();

		$filterDokter = array();
		$CheckPoli = self::$query->select('master_poli_dokter', array(
			'dokter'
		))
		->where(array(
			'deleted_at' => 'IS NULL',
			'AND',
			'poli' => '= ?'
		), array(
			$parameter
		))
		->execute();

		foreach ($CheckPoli['response_data'] as $key => $value) {
			if(!in_array($value['dokter'], $filterDokter)) {
				array_push($filterDokter, $value['dokter']);
			}
		}

		foreach ($Dokter['response_data'] as $key => $value) {
			if(in_array($value['uid'], $filterDokter)) {
				unset($Dokter['response_data'][$key]);
			}
		}

		return $Dokter;
	}

	private function get_set_dokter($parameter) {
		$CheckPoli = self::$query->select('master_poli_dokter', array(
			'dokter'
		))
		->where(array(
			'deleted_at' => 'IS NULL',
			'AND',
			'poli' => '= ?'
		), array(
			$parameter
		))
		->execute();

		foreach ($CheckPoli['response_data'] as $key => $value) {
			$Pegawai = new Pegawai(self::$pdo);
			$NamaDokter = $Pegawai::get_detail($value['dokter']);
			$CheckPoli['response_data'][$key]['nama'] = $NamaDokter['response_data'][0]['nama'];
		}

		return $CheckPoli;
	}
  
	public function get_poli_by_dokter($parameter) {
		$CheckPoli = self::$query->select('master_poli_dokter', array(
			'dokter',
			'poli'
		))
		->where(array(
			'deleted_at' => 'IS NULL',
			'AND',
			'dokter' => '= ?'
		), array(
			$parameter
		))
		->execute();

		foreach ($CheckPoli['response_data'] as $key => $value) {
			$CheckPoli['response_data'][$key]['poli'] = self::get_poli_detail($value['poli']);
		}

		return $CheckPoli;
	}
	

	/*====================== PERAWAT ======================*/

	private function get_avail_perawat($parameter) { //parameter = uid poli
		$Perawat = self::$query->select('pegawai', array(
			'uid',
			'nama AS nama_perawat'
		))
		->join('pegawai_jabatan', array(
			'uid AS uid_jabatan',
			'nama AS nama_jabatan'
		))
		->on(array(
			array('pegawai.jabatan', '=', 'pegawai_jabatan.uid')
		))
		->where(array(
			'pegawai.deleted_at' => 'IS NULL',
			'AND',
			'pegawai_jabatan.nama' => '= ?'
		), array(
			'Perawat'
		))
		->execute();

		$filterPerawat = array();
		$CheckPoli = self::$query->select('master_poli_perawat', array(
			'perawat'
		))
		->where(array(
			'deleted_at' => 'IS NULL',
			'AND',
			'poli' => '= ?'
		), array(
			$parameter
		))
		->execute();

		foreach ($CheckPoli['response_data'] as $key => $value) {
			if(!in_array($value['perawat'], $filterPerawat)) {
				array_push($filterPerawat, $value['perawat']);
			}
		}

		foreach ($Perawat['response_data'] as $key => $value) {
			if(in_array($value['uid'], $filterPerawat)) {
				unset($Perawat['response_data'][$key]);
			}
		}

		return $Perawat;
	}

	private function get_set_perawat($parameter) {
		$CheckPoli = self::$query->select('master_poli_perawat', array(
			'perawat'
		))
		->where(array(
			'deleted_at' => 'IS NULL',
			'AND',
			'poli' => '= ?'
		), array(
			$parameter
		))
		->execute();

		foreach ($CheckPoli['response_data'] as $key => $value) {
			$Pegawai = new Pegawai(self::$pdo);
			$NamaPerawat = $Pegawai::get_detail($value['perawat']);
			$CheckPoli['response_data'][$key]['nama'] = $NamaPerawat['response_data'][0]['nama'];
		}

		return $CheckPoli;
	}

	private function poli_perawat($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		
		$readPerawat = self::$query->select('master_poli_perawat', array(
			'poli',
			'perawat'
		))
		->where(array(
			'poli' => '= ?',
			'AND',
			'perawat' => '= ?'
		), array(
			$parameter['poli'],
			$parameter['perawat']
		))
		->execute();

		if(count($readDokter['response_data']) > 0) {
			$worker = self::$query->update('master_poli_perawat', array(
				'updated_at' => parent::format_date(),
				'deleted_at' => ''
			))
			->where(array(
				'poli' => '= ?',
				'AND',
				'perawat' => '= ?'
			), array(
				$parameter['poli'],
				$parameter['perawat']
			))
			->execute();

			if($worker['response_result'] > 0) {
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
						$readDokter['response_data'][0]['id'],
						$UserData['data']->uid,
						'master_poli_perawat',
						'U',
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class'=>__CLASS__
				));
			}
		} else {
			$worker = self::$query->insert('master_poli_perawat', array(
				'poli' => $parameter['poli'],
				'perawat' => $parameter['perawat'],
				'pegawai' => $UserData['data']->uid,
				'created_at' => parent::format_date(),
				'updated_at' => parent::format_date()
			))
			->returning('id')
			->execute();

			if($worker['response_result'] > 0) {
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
						$worker['response_unique'],
						$UserData['data']->uid,
						'master_poli_perawat',
						'I',
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class'=>__CLASS__
				));
			}
		}
		return $worker;
	}

	private function poli_perawat_buang($parameter){
		$worker = self::$query->update('master_poli_perawat', array(
			'updated_at' => parent::format_date(),
			'deleted_at' => parent::format_date()
		))
		->where(array(
			'poli' => '= ?',
			'AND',
			'perawat' => '= ?'
		), array(
			$parameter['poli'],
			$parameter['perawat']
		))
		->returning('id')
		->execute();

		if($worker['response_result'] > 0) {
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
					$worker['response_unique'],
					$UserData['data']->uid,
					'master_poli_perawat',
					'U',
					parent::format_date(),
					'N',
					$UserData['data']->log_id
				),
				'class'=>__CLASS__
			));
		}
		return $worker;
	}

	/*====================================================*/

	/*====================== CRUD ========================*/

	private function poli_dokter_buang($parameter){
		$worker = self::$query->update('master_poli_dokter', array(
			'updated_at' => parent::format_date(),
			'deleted_at' => parent::format_date()
		))
		->where(array(
			'poli' => '= ?',
			'AND',
			'dokter' => '= ?'
		), array(
			$parameter['poli'],
			$parameter['dokter']
		))
		->returning('id')
		->execute();

		if($worker['response_result'] > 0) {
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
					$worker['response_unique'],
					$UserData['data']->uid,
					'master_poli_dokter',
					'U',
					parent::format_date(),
					'N',
					$UserData['data']->log_id
				),
				'class'=>__CLASS__
			));
		}
		return $worker;
	}

	private function poli_dokter($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		
		$readDokter = self::$query->select('master_poli_dokter', array(
			'poli',
			'dokter'
		))
		->where(array(
			'poli' => '= ?',
			'AND',
			'dokter' => '= ?'
		), array(
			$parameter['poli'],
			$parameter['dokter']
		))
		->execute();

		if(count($readDokter['response_data']) > 0) {
			$worker = self::$query->update('master_poli_dokter', array(
				'updated_at' => parent::format_date(),
				'deleted_at' => ''
			))
			->where(array(
				'poli' => '= ?',
				'AND',
				'dokter' => '= ?'
			), array(
				$parameter['poli'],
				$parameter['dokter']
			))
			->execute();

			if($worker['response_result'] > 0) {
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
						$readDokter['response_data'][0]['id'],
						$UserData['data']->uid,
						'master_poli_dokter',
						'U',
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class'=>__CLASS__
				));
			}
		} else {
			$worker = self::$query->insert('master_poli_dokter', array(
				'poli' => $parameter['poli'],
				'dokter' => $parameter['dokter'],
				'pegawai' => $UserData['data']->uid,
				'created_at' => parent::format_date(),
				'updated_at' => parent::format_date()
			))
			->returning('id')
			->execute();

			if($worker['response_result'] > 0) {
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
						$worker['response_unique'],
						$UserData['data']->uid,
						'master_poli_dokter',
						'I',
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class'=>__CLASS__
				));
			}
		}
		return $worker;
	}

	private function tambah_poli($table_name, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		//$objData = $parameter['dataObject'];
		$nama = $parameter['dataObject']['nama'];
		$tindakanData = $parameter['dataObject']['tindakan'];

		$check = self::duplicate_check(array(
			'table'=>$table_name,
			'check'=>$nama
		));

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid_poli = parent::gen_uuid();
			$poli = self::$query
						->insert($table_name, array(
								'uid'=>$uid_poli,
								'nama'=>$nama,
								'created_at'=>parent::format_date(),
								'updated_at'=>parent::format_date(),
								'editable'=>TRUE
								)
						)->execute();

				if ($tindakanData != ""){
					foreach ($tindakanData as $key => $values) {
						$uid_tindakan = $key;

						foreach ($values as $key => $value) {
							$uid_penjamin = $key;

							$tindakan = self::$query
								->insert('master_poli_tindakan_penjamin', array(
											'harga'=>$value,
											'uid_poli'=>$uid_poli,
											'uid_tindakan'=>$uid_tindakan,
											'uid_penjamin'=>$uid_penjamin,
											'created_at'=>parent::format_date(),
											'updated_at'=>parent::format_date()
										)
									)
								->execute();

						}

					}
				}

			if ($poli['response_result'] > 0){
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
								$uid,
								$UserData['data']->uid,
								$table_name,
								'I',
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class'=>__CLASS__
						)
					);
			}
		}

		return $poli;
	}


	private function edit_poli($table_name, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$uid_poli = $parameter['dataObject']['uid'];
		$nama = $parameter['dataObject']['nama'];
		$tindakanData = $parameter['dataObject']['tindakan'];

		$old = self::get_poli_detail($uid_poli);
		$old_tindakan = self::get_poli_tindakan($uid_poli);
		$old_data['nama'] = $old['response_data'][0]['nama'];
		$old_data['tindakan'] = $old_tindakan['response_data'];

		$poli = self::$query
					->update($table_name, array(
						'nama'=>$nama,
						'updated_at'=>parent::format_date()
						)
					)
					->where(array(
							$table_name . '.deleted_at' => 'IS NULL',
							'AND',
							$table_name . '.uid' => '= ?'
						),
						array(
							$uid_poli
						)
					)
					->execute();

		if(isset($tindakanData) && $tindakanData != "") {
			//Reset All Price
			/*$reset = self::$query->update('master_poli_tindakan_penjamin', array(
				'deleted_at' => parent::format_date()
			))
			->where(array(
				'master_poli_tindakan_penjamin.deleted_at' => 'IS NULL',
				'AND',
				'master_poli_tindakan_penjamin.uid_poli' => '= ?',
				'AND',
				'master_poli_tindakan_penjamin.uid_tindakan' => '= ?',
				'AND',
				'master_poli_tindakan_penjamin.uid_penjamin' => '= ?'
			), array(
				$uid_poli,
				$key,
				$Tkey
			))
			->execute();*/
			
			foreach ($tindakanData as $key => $value) {
				foreach ($value as $Tkey => $Tvalue) {
					$check = self::$query->select('master_poli_tindakan_penjamin', array(
						'id'
					))
					->where(array(
						'master_poli_tindakan_penjamin.deleted_at' => 'IS NULL',
						'AND',
						'master_poli_tindakan_penjamin.uid_poli' => '= ?',
						'AND',
						'master_poli_tindakan_penjamin.uid_tindakan' => '= ?',
						'AND',
						'master_poli_tindakan_penjamin.uid_penjamin' => '= ?'
					), array(
						$uid_poli,
						$key,
						$Tkey
					))
					->execute();
					if(count($check['response_data']) > 0) {
						$worker = self::$query->update('master_poli_tindakan_penjamin', array(
							'harga' => $Tvalue,
							'updated_at' => parent::format_date()
						))
						->where(array(
							'master_poli_tindakan_penjamin.deleted_at' => 'IS NULL',
							'AND',
							'master_poli_tindakan_penjamin.uid_poli' => '= ?',
							'AND',
							'master_poli_tindakan_penjamin.uid_tindakan' => '= ?',
							'AND',
							'master_poli_tindakan_penjamin.uid_penjamin' => '= ?'	
						), array(
							$uid_poli,
							$key,
							$Tkey
						))
						->execute();

						if($worker['response_result'] > 0) {
							$log = parent::log(array(
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
									$check['response_data'][0]['id'],
									$UserData['data']->uid,
									$table_name,
									'U',
									json_encode($check['response_data'][0]),
									json_encode($tindakanData),
									parent::format_date(),
									'N',
									$UserData['data']->log_id
								),
								'class'=>__CLASS__
							));
						}
					} else {
						$worker = self::$query->insert('master_poli_tindakan_penjamin', array(
							'uid_poli' => $uid_poli,
							'uid_tindakan' => $key,
							'uid_penjamin' => $Tkey,
							'harga' => $Tvalue,
							'created_at' => parent::format_date(),
							'updated_at' => parent::format_date()
						))
						->returning('id')
						->execute();

						if($worker['response_result'] > 0) {
							$log = parent::log(array(
								'type'=>'activity',
								'column'=>array(
									'unique_target',
									'user_uid',
									'table_name',
									'action',
									'new_value',
									'logged_at',
									'status',
									'login_id'
								),
								'value'=>array(
									$worker['response_unique'],
									$UserData['data']->uid,
									$table_name,
									'I',
									json_encode($tindakanData),
									parent::format_date(),
									'N',
									$UserData['data']->log_id
								),
								'class'=>__CLASS__
							));
						}
					}
				}
			}
					
		}


		if ($poli['response_result'] > 0){
			$log = parent::log(array(
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
						$uid_poli,
						$UserData['data']->uid,
						$table_name,
						'U',
						json_encode($old_data),
						json_encode($parameter['dataObject']),
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class'=>__CLASS__
				)
			);
		}

		return $poli;
	}



	private function delete_poli($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$poli = self::$query
			->delete($parameter[6])
			->where(array(
					$parameter[6] . '.uid' => '= ?'
				), array(
					$parameter[7]	
				)
			)
			->execute();

		if ($poli['response_result'] > 0){
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
						$parameter[7],
						$UserData['data']->uid,
						$parameter[6],
						'D',
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class'=>__CLASS__
				)
			);

			/*================ DELETE HARGA TINDAKAN =================*/
			$tindakan = self::$query
					->delete('master_poli_tindakan_penjamin')
					->where(array(
							 'master_poli_tindakan_penjamin.uid_poli' => '= ?'
						), array(
							$parameter[7]
						)
					)
					->execute();
			
		}

		return $poli;
	}


	/*============= FUNCTION TAMBAHAN ============*/
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