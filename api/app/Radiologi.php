<?php 

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;
use PondokCoder\Penjamin as Penjamin;
use PondokCoder\Antrian as Antrian;
use PondokCoder\Pasien as Pasien;
use PondokCoder\Tindakan as Tindakan;

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
					return self::get_tindakan();
					break;

				case 'tindakan-detail':
					return self::get_tindakan_detail($parameter[2]);
					break;

				case 'antrian':
					return self::get_antrian();
					break;

				case 'get-order-detail':
					return self::get_radiologi_order_detail($parameter[2]);
					break;

				case 'get-radiologi-order':
					return self::get_radiologi_order($parameter[2]);
					break; 

				case 'get-data-pasien-antrian':
					return self::get_data_pasien_antrian($parameter[2]);
					break;

				case 'radiologi-order-detail-item':
					return self::get_radiologi_order_detail_item($parameter[2]);
					break;

				case 'get-radiologi-lampiran':
					return self::get_radiologi_lampiran($parameter[2]);
					break;

				case 'get_tindakan_for_dokter':
					return self::get_tindakan_for_dokter('test');
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
				return self::tambah_tindakan($parameter);
				break;

			case 'edit-tindakan':
				return self::edit_tindakan($parameter);
				break;

			case 'update-hasil-radiologi':
				return self::update_hasil_radiologi($parameter); 
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

	private function get_tindakan(){
		$data = self::$query
				->select('master_tindakan', array(
						'uid','nama', 'created_at','updated_at'
					)
				)
				->join('master_radiologi_tindakan', array(
						'jenis as uid_jenis'
					)
				)
				->on(array(
					array('master_radiologi_tindakan.uid_tindakan', '=', 'master_tindakan.uid'))
				)
				->where(array(
						'master_tindakan.deleted_at' => 'IS NULL'
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
			->select('master_tindakan', array(
				'uid','nama', 'created_at','updated_at'
				)
			)
			->join('master_radiologi_tindakan', array(
					'jenis'
				)
			)
			->on(array(
				array('master_radiologi_tindakan.uid_tindakan', '=', 'master_tindakan.uid'))
			)
			->where(array(
					'master_tindakan.deleted_at' => 'IS NULL',
					'AND',
					'master_tindakan.uid' => '= ?'
				),
				array($parameter)
			)
			->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;

			// $temp = self::get_tindakan_penjamin(array(
			// 	'departemen'=>__UIDRADIOLOGI__,
			// 	'tindakan'=>$value['uid']
			// ));
			//$data['response_data'][$key]['penjamin'] = $temp['response_data'];
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

	private function tambah_tindakan($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table'=>'master_tindakan',
			'check'=>$parameter['nama']
		));

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();

			$layanan = self::$query
				->insert('master_tindakan', array(
						"uid"=>$uid,
						"nama"=>$parameter['nama'],
						"kelompok"=>'RAD',
						"created_at"=>parent::format_date(),
						"updated_at"=>parent::format_date()
					)
				)
				->execute();

			if ($layanan['response_result'] > 0){
				$tindakan = self::$query
					->insert('master_radiologi_tindakan', array(
							'uid_tindakan'=>$uid,
							'jenis'=>$parameter['jenis'],
							'created_at'=>parent::format_date(),
							'updated_at'=>parent::format_date()
						)
					)
					->execute();

				// if ($tindakan['response_result'] > 0){
				// 	foreach ($dataObj['penjamin'] as $key => $value) {
				// 		$penjamin = self::$query
				// 				->insert('master_poli_tindakan_penjamin', array(
				// 						'harga'=>$value,
				// 						'uid_poli'=>__UIDRADIOLOGI__,
				// 						'uid_tindakan'=>$uid,
				// 						'uid_penjamin'=>$key,
				// 						'created_at'=>parent::format_date(),
				// 						'updated_at'=>parent::format_date()
				// 					)
				// 				)
				// 				->execute();
				// 	}
				// }

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
							"master_tindakan, master_radiologi_tindakan",
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
				"tindakan"=>$tindakan
			);

		return $result;
	}

	private function edit_tindakan($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_tindakan_detail($parameter['uid']);

		$layanan = self::$query
			->update('master_tindakan', array(
					"nama"=>$parameter['nama'],
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
		
		if ($layanan['response_result'] > 0){
			$tindakan = self::$query
				->update('master_radiologi_tindakan', array(
						"jenis"=>$parameter['jenis'],
						"updated_at"=>parent::format_date()		
					)
				)
				->where(array(
						'master_radiologi_tindakan.uid_tindakan' => '= ?',
						'AND',
						'master_radiologi_tindakan.deleted_at' => 'IS NULL'
					),array(
						$parameter['uid']
					)
				)
				->execute();

			// if ($tindakan['response_result'] > 0){
			// 	foreach ($dataObj['penjamin'] as $key => $value) {
			// 		$cek = self::get_tindakan_penjamin_detail(array(
			// 				'departemen'=>__UIDRADIOLOGI__,
			// 				'tindakan'=>$parameter['uid'],
			// 				'penjamin'=>$key
			// 			));

			// 		if ($cek['response_result'] > 0){
			// 			$penjamin = self::$query
			// 				->update('master_poli_tindakan_penjamin', array(
			// 						'harga'=>$value,
			// 						'updated_at'=>parent::format_date()
			// 					)
			// 				)
			// 				->where(array(
			// 						'master_poli_tindakan_penjamin.uid_poli' => '= ?',
			// 						'AND',
			// 						'master_poli_tindakan_penjamin.uid_tindakan' => '= ?',
			// 						'AND',
			// 						'master_poli_tindakan_penjamin.uid_penjamin' => '= ?'
			// 					),array(
			// 						__UIDRADIOLOGI__,
			// 						$parameter['uid'],
			// 						$key,
			// 					)
			// 				)
			// 				->execute();
			// 		} else {
			// 			$penjamin = self::$query
			// 				->insert('master_poli_tindakan_penjamin', array(
			// 						'harga'=>$value,
			// 						'uid_poli'=>__UIDRADIOLOGI__,
			// 						'uid_tindakan'=>$parameter['uid'],
			// 						'uid_penjamin'=>$key,
			// 						'created_at'=>parent::format_date(),
			// 						'updated_at'=>parent::format_date()
			// 					)
			// 				)
			// 				->execute();
			// 		}
			// 	}
			// }

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
						'master_tindakan, master_radiologi_tindakan',
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
				"tindakan"=>$tindakan
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
			if ($parameter[6] == 'master_tindakan'){
				$delete_child = self::$query
					->delete('master_radiologi_tindakan')
					->where(array(
							'master_radiologi_tindakan.uid_tindakan' => '= ?'
						), array(
							$parameter[7]	
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

		return $data;
	}

	/*------------------ ANTRIAN RADIOLOGI -------------------*/
	private function get_antrian(){
		$data = self::$query
				->select('radiologi_order', 
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
						array('radiologi_order.asesmen', '=', 'asesmen.uid'),
						array('asesmen.antrian','=','antrian.uid'),
						array('pasien.uid','=','antrian.pasien'),
						array('master_poli.uid','=','antrian.departemen'),
						array('pegawai.uid','=','antrian.dokter'),
						array('master_penjamin.uid','=','antrian.penjamin'),
						array('kunjungan.uid','=','antrian.kunjungan')
					)
				)
				->where(array(
						'radiologi_order.deleted_at' => 'IS NULL'
					)
				)
				->order(
					array(
						'radiologi_order.waktu_order' => 'DESC'
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

	private function get_radiologi_order_detail($parameter){
		$data = self::$query
			->select('radiologi_order_detail', array(
					'id',
					'radiologi_order as uid_radiologi_order',
					'tindakan as uid_tindakan',
					'penjamin as uid_penjamin',
					'keterangan',
					'kesimpulan',
					'gambar',
				)
			)
			->where(array(
					'radiologi_order_detail.radiologi_order' => '= ?',
					'AND',
					'radiologi_order_detail.deleted_at' => 'IS NULL'
				),array($parameter)
			)
			->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['tindakan'] = self::get_tindakan_detail($value['uid_tindakan'])['response_data'][0]['nama'];

			$penjamin = new Penjamin(self::$pdo);
			$data['response_data'][$key]['penjamin'] = $penjamin->get_penjamin_detail($value['uid_penjamin'])['response_data'][0]['nama'];

			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		
		return $data;
	}

	private function get_radiologi_order_detail_item($parameter){
		$data = self::$query
			->select('radiologi_order_detail', array(
					'id',
					'radiologi_order as uid_radiologi_order',
					'tindakan as uid_tindakan',
					'penjamin as uid_penjamin',
					'keterangan',
					'kesimpulan',
					'gambar',
				)
			)
			->where(array(
					'radiologi_order_detail.id' => '= ?',
					'AND',
					'radiologi_order_detail.deleted_at' => 'IS NULL'
				),array($parameter)
			)
			->execute();

		return $data;
	}

	private function get_radiologi_lampiran($parameter){
		$data = self::$query
			->select('radiologi_order_document', array(
					'id', 	
					'radiologi_order',
					'lampiran',
					'created_at'
				)
			)
			->where(array(
					'radiologi_order_document.radiologi_order' => '= ?',
					'AND',
					'radiologi_order_document.deleted_at' => 'IS NULL'
				), array($parameter)
			)
			->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['file_location'] = '../document/radiologi/' . $parameter . '/' . $value['lampiran'];
			$autonum++;
		}

		return $data;
	}

	private function update_hasil_radiologi($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$result = [];

		if (isset($parameter['tindakanID'])){
			$old = self::get_radiologi_order_detail_item($parameter['tindakanID']);

			$updateData = self::$query
				->update('radiologi_order_detail', array(
						'keterangan'=>$parameter['keteranganPeriksa'],
						'kesimpulan'=>$parameter['kesimpulanPeriksa'],
						'updated_at'=>parent::format_date()
					)
				)
				->where(array(
						'radiologi_order_detail.id' => '= ?',
						'AND',
						'radiologi_order_detail.deleted_at' => 'IS NULL'
					),array($parameter['tindakanID'])
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
							$parameter['tindakanID'],
							$UserData['data']->uid,
							'radiologi_order_detail',
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
			$result['order_detail'] = $updateData;
		}

		//create new 
		$folder_structure = '../document/radiologi/' . $parameter['uid_radiologi_order'];
		if (!is_dir($folder_structure)){

			if (!mkdir($folder_structure, 0777, true)) {
			    $result['dir_msg'] = 'Failed to create folders...';
			}
			//mkdir('../document/radiologi/' . $parameter['uid_radiologi_order'], 0755);
		} else {
			$result['dir_msg'] = 'Dir available...';
		}

		if(is_writeable($folder_structure)) {
			$result['response_upload'] = array();
			//$imageDatas = json_decode($_FILES['fileList'], true);

			//get maximum id
			$get_max = self::$query
				->select('radiologi_order_document', array(
						'id'
					)
				)
				->order(
					array(
						'radiologi_order_document.created_at' => 'DESC'
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
					$nama_lampiran = 'R_' . str_pad($max, 6, "0", STR_PAD_LEFT);

					if(move_uploaded_file($_FILES['fileList']['tmp_name'][$a], '../document/radiologi/' . $parameter['uid_radiologi_order'] . '/' . $nama_lampiran . '.pdf')) {
						array_push($result['response_upload'], 'Berhasil diupload');
						$lampiran = self::$query
							->insert('radiologi_order_document', array(
								'radiologi_order' => $parameter['uid_radiologi_order'],
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
					->select('radiologi_order_document', array(
							'lampiran'
						)
					)
					->where(array(
							'radiologi_order_document.id' => '= ?'
						), array($value)
					)
					->execute();

				if ($getLampiran['response_result'] > 0){
					$nama_lampiran_hapus = $getLampiran['response_data'][0]['lampiran'];

					$hapusLampiran = self::$query
						->delete('radiologi_order_document')
						->where(array(
								'radiologi_order_document.id' => '= ?'
							), array($value)
						)
						->execute();

					if ($hapusLampiran['response_result'] > 0){
						unlink('../document/radiologi/' . $parameter['uid_radiologi_order'] . '/' . $nama_lampiran_hapus);

						$result['response_delete_doc']['response_result'] = 1;
					}

					$result['response_delete_doc']['response_data'] = $hapusLampiran;
				}
			}
		}
		//return (is_writable($folder_structure));

		//return count($parameter['deletedDocList']);
		return $result;
	}

	/*-----------------------------------------------------------*/

	/*------------------- GET DATA PASIEN and ANTRIAN --------------------*/
	private function get_data_pasien_antrian($parameter){
		$get_uid_asesmen = self::$query
			->select('radiologi_order', array(
					'asesmen'
				)
			)
			->where(array(
					'radiologi_order.uid' => '= ?'
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
	/*-------------------------------------------------------*/

	/*------------------- GET TINDAKAN RADIOLOGI FOR DOKTER --------------------*/
	private function get_tindakan_for_dokter($penjamin){
		$dataTindakan = self::get_tindakan();

		$tindakan = new Tindakan(self::$pdo);
		$autonum = 1;
		foreach ($dataTindakan['response_data'] as $key => $value) {
			$dataTindakan['response_data'][$key]['autonum'] = $autonum;
			$dataTindakan['response_data'][$key]['id'] = $value['uid'];
			$dataTindakan['response_data'][$key]['text'] = $value['nama'];

			$autonum++;

			$harga = $tindakan->get_harga_tindakan($value['uid']);
			$dataTindakan['response_data'][$key]['harga'] = $harga['response_data'];
		}

		return $dataTindakan;
	}
	/*-------------------------------------------------------*/

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