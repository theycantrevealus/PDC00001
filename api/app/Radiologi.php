<?php 

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;

class Radiologi extends Utility {
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
				case 'jenis':
					return self::get_jenis_tindakan('master_radiologi_jenis');
					break;

				case 'penjamin':
					return self::get_tindakan_penjamin($parameter[2]);
					break;

				case 'tindakan':
					return self::get_tindakan('master_radiologi_jenis');
					break;

				case 'tindakan-detail':
					return self::get_tindakan_detail($parameter[2]);
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
			case 'tambah-jenis':
				return self::tambah_jenis_tindakan('master_radiologi_jenis', $parameter);
				break;

			case 'edit-jenis':
				return self::edit_jenis_tindakan('master_radiologi_jenis', $parameter);
				break;

			case 'tambah-tindakan':
				return self::tambah_tindakan('master_radiologi_tindakan', $parameter);
				break;

			case 'edit-tindakan':
				return self::edit_tindakan($parameter);
				break;

			default:
				# code...
				break;
		}
	}

	public function __DELETE__($parameter = array()) {
		switch ($parameter[6]) {
			case 'master_radiologi_tindakan':
				return self::delete_tindakan($parameter);
				break;

			default:
				return self::delete($parameter);
				break;
		}	
	}


	/*====================== GET FUNCTION =====================*/
	private function get_jenis_tindakan($table){
		$data = self::$query
					->select($table, 
						array(
							'uid',
							'nama',
							'created_at',
							'updated_at'
						)
					)
					->where(array(
							$table . '.deleted_at' => 'IS NULL'
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

	private function get_jenis_tindakan_detail($table, $parameter){
		$data = self::$query
					->select($table, 
						array(
							'uid',
							'nama',
							'created_at',
							'updated_at'
						)
					)
					->where(array(
							$table . '.deleted_at' => 'IS NULL',
							'AND',
							$table . '.uid' => '= ?'
						),
						array($parameter)
					)
					->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $data;
	}

	private function get_tindakan($parameter){
		$data = self::$query
				->select('master_radiologi_tindakan', array(
						'uid','nama','jenis as uid_jenis','created_at','updated_at'
					)
				)
				->where(array(
						'master_radiologi_tindakan.deleted_at' => 'IS NULL'
					)
				)
				->order(array('nama'=>'ASC'))
				->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;

			$jenis = self::get_jenis_tindakan_detail('master_radiologi_jenis', $value['uid_jenis']);
			$data['response_data'][$key]['jenis'] = $jenis['response_data'][0]['nama'];
		}
		return $data;
	}

	private function get_tindakan_detail($parameter){
		$data = self::$query
					->select('master_radiologi_tindakan', 
						array(
							'uid',
							'nama',
							'jenis',
							'created_at',
							'updated_at'
						)
					)
					->where(array(
							'master_radiologi_tindakan.deleted_at' => 'IS NULL',
							'AND',
							'master_radiologi_tindakan.uid' => '= ?'
						),
						array($parameter)
					)
					->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;

			$temp = self::get_tindakan_penjamin(array(
										'departemen'=>__UIDRADIOLOGI__,
										'tindakan'=>$value['uid']
									));
			

			$data['response_data'][$key]['penjamin'] = $temp['response_data'];
		}

		return $data;
	}

	private function get_tindakan_penjamin_detail($parameter){
		$data = self::$query
				->select('master_poli_tindakan_penjamin', 
					array(
						'id',
						'harga',
						'uid_poli',
						'uid_tindakan',
						'uid_penjamin',
						'created_at',
						'updated_at'
					)
				)
				->where(array(
						'master_poli_tindakan_penjamin.deleted_at' => 'IS NULL',
						'AND',
						'master_poli_tindakan_penjamin.uid_poli' => '= ?',
						'AND',
						'master_poli_tindakan_penjamin.uid_tindakan' => '= ?',
						'AND',
						'master_poli_tindakan_penjamin.uid_penjamin' => '= ?'
					),
					array(
						$parameter['departemen'],
						$parameter['tindakan'],
						$parameter['penjamin']
					)
				)
				->execute();

		return $data;
	}

	private function get_tindakan_penjamin($parameter){
		$data = self::$query
				->select('master_poli_tindakan_penjamin',
					array(
						'id',
						'harga',
						'uid_poli',
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
						'master_poli_tindakan_penjamin.deleted_at' => 'IS NULL'
					),array(
						$parameter['departemen'],
						$parameter['tindakan']
					)
				)
				->execute();

		return $data;
	}
	/*=========================================================*/




	/*====================== CRUD ========================*/
	private function tambah_jenis_tindakan($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table'=>$table,
			'check'=>$parameter['nama']
		));

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();
			$jenis = self::$query
						->insert($table, array(
							'uid'=>$uid,
							'nama'=>$parameter['nama'],
							'created_at'=>parent::format_date(),
							'updated_at'=>parent::format_date()
							)
						)
						->execute();

			if ($jenis['response_result'] > 0){
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
								$table,
								'I',
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class'=>__CLASS__
						)
					);
			}
			return $jenis;
		}
	}

	private function edit_jenis_tindakan($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_jenis_tindakan_detail('master_radiologi_jenis', $parameter['uid']);

		$jenis = self::$query
				->update($table, array(
						'nama'=>$parameter['nama'],
						'updated_at'=>parent::format_date()
					)
				)
				->where(array(
					$table . '.deleted_at' => 'IS NULL',
					'AND',
					$table . '.uid' => '= ?'
					),
					array(
						$parameter['uid']
					)
				)
				->execute();

		if ($jenis['response_result'] > 0){
			unset($parameter['access_token']);

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
						$parameter['uid'],
						$UserData['data']->uid,
						$table,
						'U',
						json_encode($old['response_data'][0]),
						json_encode($parameter),
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class'=>__CLASS__
				)
			);
		}

		return $jenis;
	}

	private function tambah_tindakan($table, $parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$dataObj = $parameter['dataObj'];

		$check = self::duplicate_check(array(
			'table'=>$table,
			'check'=>$dataObj['nama']
		));

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();

			$layanan = self::$query
					->insert($table, array(
							"uid"=>$uid,
							"nama"=>$dataObj['nama'],
							"jenis"=>$dataObj['jenis'],
							"created_at"=>parent::format_date(),
							"updated_at"=>parent::format_date()
						)
					)
					->execute();

			if ($layanan['response_result'] > 0){
				$tindakan = self::$query
						->insert('master_tindakan', array(
								'uid'=>$uid,
								'nama'=>"Radiologi " . $dataObj['nama'],
								'created_at'=>parent::format_date(),
								'updated_at'=>parent::format_date()
							)
						)
						->execute();

				if ($tindakan['response_result'] > 0){
					foreach ($dataObj['penjamin'] as $key => $value) {
						$penjamin = self::$query
								->insert('master_poli_tindakan_penjamin', array(
										'harga'=>$value,
										'uid_poli'=>__UIDRADIOLOGI__,
										'uid_tindakan'=>$uid,
										'uid_penjamin'=>$key,
										'created_at'=>parent::format_date(),
										'updated_at'=>parent::format_date()
									)
								)
								->execute();
					}
				}

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
							$table . ", master_tindakan, master_poli_tindakan_penjamin",
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

		$result = array(
				"layanan"=>$layanan,
				"tindakan"=>$tindakan,
				"penjamin"=>$penjamin
			);

		return $result;
	}

	private function edit_tindakan($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_tindakan_detail($parameter['uid']);
		$dataObj = $parameter['dataObj'];

		$layanan = self::$query
					->update('master_radiologi_tindakan', array(
							"nama"=>$dataObj['nama'],
							"jenis"=>$dataObj['jenis'],
							"updated_at"=>parent::format_date()		
						)
					)
					->where(array(
							'master_radiologi_tindakan.uid' => '= ?',
							'AND',
							'master_radiologi_tindakan.deleted_at' => 'IS NULL'
						),array(
							$parameter['uid']
						)
					)
					->execute();
		
		if ($layanan['response_result'] > 0){
			$tindakan = self::$query
					->update('master_tindakan', array(
							"nama"=>$dataObj['nama'],
							"updated_at"=>parent::format_date()		
						)
					)
					->where(array(
							'master_tindakan.uid' => '= ?',
							'AND',
							'master_tindakan.deleted_at' => 'IS NULL'
						),array(
							$parameter['uid']
						)
					)
					->execute();

			if ($tindakan['response_result'] > 0){
				foreach ($dataObj['penjamin'] as $key => $value) {
					$cek = self::get_tindakan_penjamin_detail(array(
							'departemen'=>__UIDRADIOLOGI__,
							'tindakan'=>$parameter['uid'],
							'penjamin'=>$key
						));

					if ($cek['response_result'] > 0){
						$penjamin = self::$query
							->update('master_poli_tindakan_penjamin', array(
									'harga'=>$value,
									'updated_at'=>parent::format_date()
								)
							)
							->where(array(
									'master_poli_tindakan_penjamin.uid_poli' => '= ?',
									'AND',
									'master_poli_tindakan_penjamin.uid_tindakan' => '= ?',
									'AND',
									'master_poli_tindakan_penjamin.uid_penjamin' => '= ?'
								),array(
									__UIDRADIOLOGI__,
									$parameter['uid'],
									$key,
								)
							)
							->execute();
					} else {
						$penjamin = self::$query
							->insert('master_poli_tindakan_penjamin', array(
									'harga'=>$value,
									'uid_poli'=>__UIDRADIOLOGI__,
									'uid_tindakan'=>$parameter['uid'],
									'uid_penjamin'=>$key,
									'created_at'=>parent::format_date(),
									'updated_at'=>parent::format_date()
								)
							)
							->execute();
					}
				}
			}

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
						$parameter['uid'],
						$UserData['data']->uid,
						$table,
						'U',
						json_encode($old),
						json_encode($parameter),
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class'=>__CLASS__
				)
			);
		}
		
		$result = array(
				"layanan"=>$layanan,
				"tindakan"=>$tindakan,
				"penjamin"=>$penjamin
			);

		return $result;
	}

	private function delete_tindakan($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$data = self::$query
				->delete($parameter[6])
				->where(array(
						$parameter[6] . '.uid' => '= ?'
					), array(
						$parameter[7]	
					)
				)
				->execute();

		if ($data['response_result'] > 0){
			$tindakan = self::$query
						->delete('master_tindakan')
						->where(array(
								'master_tindakan.uid' => '= ?'
							), array(
								$parameter[7]	
							)
						)
						->execute();

				if ($tindakan['response_result'] > 0){
					$penjamin = self::$query
								->delete('master_poli_tindakan_penjamin')
								->where(array(
										'master_poli_tindakan_penjamin.uid_poli' => '= ?',
										'AND',
										'master_poli_tindakan_penjamin.uid_tindakan' => '= ?'
									),array(
										__UIDRADIOLOGI__,
										$parameter['7']
									)
								)
								->execute();
				}

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
		}

		$result = array(
				"layanan"=>$data,
				"tindakan"=>$tindakan,
				"penjamin"=>$penjamin
			);

		return $result;
	}

	private function delete($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$data = self::$query
				->delete($parameter[6])
				->where(array(
						$parameter[6] . '.uid' => '= ?'
					), array(
						$parameter[7]	
					)
				)
				->execute();

		if ($data['response_result'] > 0){
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
		}

		return $data;
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