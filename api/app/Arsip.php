<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;

class Arsip extends Utility {
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
				case 'kategori':
					return self::get_kategoriarsip();
					break;
				case 'subkategori':
					return self::get_subkategoriarsip();
					break;
				case 'subkategori-detail':
					return self::get_subkategoriarsipkategori($parameter[2]);
					break;
				case 'arsip':
					return self::get_arsip();
					break;
				case 'berkas':
					return self::get_berkas();
					break;
				/*case 'get_arsip_tindakan':
					return self::get_arsip_tindakan($parameter[2]);
					break;*/

				default:
					return $parameter;
					break;
			}
		} catch (QueryException $e) {
			return 'Error => '. $e;
		}
	}

	public function __POST__($parameter = array()){
		switch ($parameter['request']) {
            case 'hapus_berkas':
                return self::hapus_berkas($parameter);
                break;
			case 'add_kategori':
				return self::tambah_kategori($parameter);
				break;

			case 'edit_kategori':
				return self::edit_kategori($parameter);
				break;
			
			case 'add_subkategori':
				return self::tambah_subkategori($parameter);
				break;

			case 'edit_subkategori':
				return self::edit_subkategori($parameter);
				break;

			case 'add_berkas':
				return self::tambah_berkas($parameter);
				break;

			case 'edit_berkas':
				return self::edit_berkas($parameter);
				break;

			default:
				return $parameter;
				break;
		}
	}

	public function __DELETE__($parameter = array()){
		//return "ok";
		return self::delete($parameter);
	}

	private function hapus_berkas($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);
        //return $parameter;

        $arsip = self::$query
            ->update('arsip_berkas', array(
                    'deleted_at'=>parent::format_date()
                )
            )
            ->where(array(
                'arsip_berkas.uid' => '= ?'
            ),
            array(
                $parameter['uid']
            ))
            ->execute();

        if ($arsip['response_result'] > 0){
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
                        $parameter[['uid']],
                        $UserData['data']->uid,
                        'arsip_berkas',
                        'D',
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class'=>__CLASS__
                )
            );
        }

        return $arsip;
    }


	/*=======================GET FUNCTION======================*/
	public function get_kategoriarsip() {
		$data = self::$query
					->select('arsip_kategori', array(
						'id',
						'nama'
						)
					)	
					->where(array(
							'deleted_at' => 'IS NULL'
						)
					)
					->order(array('nama' => 'ASC'))
					->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $data;
	}

	public function get_subkategoriarsip() {
		$data = self::$query
					->select('arsip_subkategori', array(
						'id',
						'nama'
						))

					->join('arsip_kategori',array(
						'nama AS nama_kategori',
						'id AS id_kategori'
					))

					->on(array(
						array('arsip_subkategori.id_kategori','=','arsip_kategori.id')
					))

					->where(array(
							'arsip_subkategori.deleted_at' => 'IS NULL'
						)
					)
					->order(array('arsip_subkategori.nama' => 'ASC'))
					->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $data;
	}

	public function get_subkategoriarsipkategori($parameter){
		$data = self::$query
					->select('arsip_subkategori', array(
						'id',
						'nama'
						))
					->where(array(
							'arsip_subkategori.deleted_at' => 'IS NULL',
							'AND',
							'arsip_subkategori.id_kategori' => '= ?'
					),
						array(
							$parameter
						)
					)
					->order(array('arsip_subkategori.nama' => 'ASC'))
					->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $data;
	}

	public function get_berkas(){
		$data = self::$query
			->select('arsip_berkas',array(
				'uid',
				'created_at',
				'nama',
				'berkas',
				'keterangan',
                'lokasi_simpan'
			))
			->join('arsip_kategori',array(
				'id AS id_kategori',
				'nama AS nama_kategori'
			))
			
			->join('arsip_subkategori', array(
				'nama AS nama_subkategori'
				))
			->on(array(
				array('arsip_berkas.id_kategori','=','arsip_kategori.id'),
				array('arsip_berkas.id_subkategori','=','arsip_subkategori.id')
			))
			->where(array(
					'arsip_berkas.deleted_at' => 'IS NULL'
				)
			)
			->order(array('arsip_berkas.nama' => 'ASC'))
			->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['created_at'] = date("d F Y", strtotime($value['created_at']));
			$autonum++;
		}

		return $data;
	}

	public function get_arsip(){
		$data = self::$query
					->select('arsip_berkas', array(
						'uid',
						'nama',
                        'berkas',
                        'lokasi_simpan',
                        'berkas',
                        'keterangan',
                        'created_at',
                        'id_kategori',
                        'id_subkategori'
                    ))
					->where(array(
					    'arsip_berkas.deleted_at' => 'IS NULL'
                    ))
					->order(array('nama' => 'ASC'))
					->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
		    $Kategori = self::get_kategori_detail($value['id_kategori'])['response_data'][0];
            $data['response_data'][$key]['nama_kategori'] = $Kategori;
		    $SubKategori = self::get_subkategoriarsipkategori($value['id_subkategori'])['response_data'][0];
            $data['response_data'][$key]['nama_subkategori'] = $SubKategori ;
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $data;
	}

	public function get_kategori_detail($parameter){
		$data = self::$query
				->select('arsip_kategori', array(
						'id',
						'nama',
						'created_at',
						'updated_at'
					)
				)
				->where(array(
							'deleted_at' => 'IS NULL',
							'AND',
							'id' => '= ?'
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


	/*====================== CRUD ========================*/

	private function tambah_kategori($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table'=>'arsip_kategori',
			'check'=>$parameter['nama']
		));

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$arsip = self::$query
						->insert('arsip_kategori', array(
								'nama'=>$parameter['nama'],
								'created_at'=>parent::format_date()
								)
						)
						->returning('id')
						->execute();

			if ($arsip['response_result'] > 0) {
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
								$arsip['response_unique'],
								$UserData['data']->uid,
								'arsip_kategori',
								'I',
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class'=>__CLASS__
						)
					);
			}

			return $arsip;

		}
	}

	private function edit_kategori($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_kategori_detail($parameter['id']);

		$arsip = self::$query
				->update('arsip_kategori', array(
						'nama'=>$parameter['nama'],
						'updated_at'=>parent::format_date()
					)
				)
				->where(array(
					'arsip_kategori.deleted_at' => 'IS NULL',
					'AND',
					'arsip_kategori.id' => '= ?'
					),
					array(
						$parameter['id']
					)
				)
				->execute();

		if ($arsip['response_result'] > 0){
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
						$parameter['id'],
						$UserData['data']->uid,
						'arsip_kategori',
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

		return $arsip;
	}

	private function tambah_subkategori($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table'=>'arsip_kategori',
			'check'=>$parameter['nama']
		));

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$arsip = self::$query
						->insert('arsip_subkategori', array(
								'nama'=>$parameter['nama'],
								'id_kategori'=>$parameter['kategori'],
								'created_at'=>parent::format_date()
								)
						)
						->returning('id')
						->execute();

			if ($arsip['response_result'] > 0) {
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
								$arsip['response_unique'],
								$UserData['data']->uid,
								'arsip_subkategori',
								'I',
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class'=>__CLASS__
						)
					);
			}

			return $arsip;

		}
	}

	private function edit_subkategori($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_kategori_detail($parameter['id']);

		$arsip = self::$query
				->update('arsip_subkategori', array(
						'nama'=>$parameter['nama'],
						'id_kategori'=>$parameter['kategori'],
						'updated_at'=>parent::format_date()
					)
				)
				->where(array(
					'arsip_subkategori.deleted_at' => 'IS NULL',
					'AND',
					'arsip_subkategori.id' => '= ?'
					),
					array(
						$parameter['id']
					)
				)
				->execute();

		if ($arsip['response_result'] > 0){
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
						$parameter['id'],
						$UserData['data']->uid,
						'arsip_subkategori',
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

		return $arsip;
	}

	private function tambah_berkas($parameter) {

		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table'=>'arsip_kategori',
			'check'=>$parameter['nama']
		));

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();


			$extension = pathinfo($_FILES['fupload']['name'], PATHINFO_EXTENSION);
			if(!is_dir('../document/arsip')) {
			    mkdir('../document/arsip');
            }
			if(move_uploaded_file($_FILES['fupload']['tmp_name'], "../document/arsip/" . $uid . '.' . $extension)) {
				$arsip = self::$query
				->insert(
						'arsip_berkas', array(
						'uid' => $uid,
						'nama' => $parameter['nama'],
						'id_kategori' => $parameter['kategori'],
						'id_subkategori' => $parameter['subkategori'],
						'keterangan' => $parameter['keterangan'],
						'lokasi_simpan' => $parameter['lokasi'],
						'berkas' => $uid . '.' . $extension,
						'created_at' => parent::format_date(),
						'updated_at' => parent::format_date()
					)
				)
				->execute();

				if ($arsip['response_result'] > 0) {
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
									$arsip['response_unique'],
									$UserData['data']->uid,
									'arsip_subkategori',
									'I',
									parent::format_date(),
									'N',
									$UserData['data']->log_id
								),
								'class'=>__CLASS__
							)
						);
				}
				return $arsip;
			} else {
				return "Failed upload file " . $uid . '.' . $extension;
			}
		}
	}

	private function edit_berkas($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_kategori_detail($parameter['id']);

		$arsip = self::$query
				->update('arsip_subkategori', array(
						'nama'=>$parameter['nama'],
						'id_kategori'=>$parameter['kategori'],
						'updated_at'=>parent::format_date()
					)
				)
				->where(array(
					'arsip_subkategori.deleted_at' => 'IS NULL',
					'AND',
					'arsip_subkategori.id' => '= ?'
					),
					array(
						$parameter['id']
					)
				)
				->execute();

		if ($arsip['response_result'] > 0){
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
						$parameter['id'],
						$UserData['data']->uid,
						'arsip_subkategori',
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

		return $arsip;
	}

	private function delete($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		//return $parameter;
		
		$arsip = self::$query
		->update($parameter[6], array(
				'deleted_at'=>parent::format_date()
			)
		)
		->where(array(
			$parameter['6'].'.id' => '= ?'
			),
			array(
				$parameter[7]
			)
		)
		->execute();

		if ($arsip['response_result'] > 0){
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
		
		return $arsip;
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