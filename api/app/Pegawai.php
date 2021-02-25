<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Utility as Utility;
use PondokCoder\Modul as Modul;
use PondokCoder\Poli as Poli;
use PondokCoder\Unit as Unit;
use \Firebase\JWT\JWT;

class Pegawai extends Utility {
	static $pdo, $query;
	
	protected static function getConn(){
		return self::$pdo;
	}

	public function __construct($connection) {
		self::$pdo = $connection;
		self::$query = new Query(self::$pdo);
	}

	public function __GET__($parameter = array()) {
		if($parameter[1] == 'detail') {
			//__HOST__/Pegawai/detail/{uid}
			return self::get_detail($parameter[2]);

		} else if($parameter[1] == 'jabatan') {
			
			return self::get_jabatan();

		} else if($parameter[1] == 'jabatan_detail') {
			
			return self::get_jabatan_detail($parameter[2]);

		} else if($parameter[1] == 'akses') {

			//__HOST__/Pegawai/akses/{uid}
			return self::get_access(array(
				'uid' => $parameter[2]
			));

        } else if($parameter[1] == 'get_module') {

			return self::get_module($parameter[2]);

		} else if($parameter[1] == 'get_all_dokter') {

			return self::get_all_dokter();

        } else if($parameter[1] == 'get_all_dokter_select2') {

            return self::get_all_dokter_select2();

		} else {

			//__HOST__/Pegawai
			return self::get_all();

		}
	}

	public function __POST__($parameter = array()) {
		switch ($parameter['request']) {
			case 'login':
				return self::login($parameter);
				break;
			case 'tambah_pegawai':
				return self::tambah_pegawai($parameter);
				break;
			case 'edit_pegawai':
				return self::edit_pegawai($parameter);
				break;
			case 'update_access':
				return self::update_access($parameter);
				break;
			case 'tambah_jabatan':
				return self::tambah_jabatan($parameter);
				break;
			case 'edit_jabatan':
				return self::edit_jabatan($parameter);
				break;
			case 'update_pegawai_access':
				return self::update_pegawai_access($parameter);
				break;
            case 'proceed_import_pegawai':
                return self::proceed_import_pegawai($parameter);
                break;
            case 'master_pegawai_import_fetch':
                return self::master_pegawai_import_fetch($parameter);
                break;
			default:
				return array();
				break;
		}
	}

	/*public function __DELETE__($parameter = array()) {
		$query = self::$pdo->prepare('UPDATE pegawai SET deleted_at = NOW() WHERE uid = ?');
		$query->execute(array($parameter));
	}*/
	public function __DELETE__($parameter = array()) {
		return self::delete($parameter);
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

//=====================================================================================


	//LOGIN
	private function login($parameter) {
		$responseBuilder = array();
		$query = self::$pdo->prepare('SELECT * FROM pegawai WHERE deleted_at IS NULL AND email = ?');
		$query->execute(array($parameter['email']));
		
		if($query->rowCount() > 0) {
			$read = $query->fetchAll(\PDO::FETCH_ASSOC);
			if(password_verify($parameter['password'], $read[0]['password'])) {
				
				$log = parent::log(array(
					'type' => 'login',
					'column' => array('user_uid','login_meta','logged_at'),
					'value' => array($read[0]['uid'],'[' . $read[0]['uid'] . '][' . $read[0]['email'] . '] Success Logged In.', parent::format_date()),
					'class' => 'User'
				));

				$Unit = new Unit(self::$pdo);
				$Unit_Info = $Unit::get_unit_detail($read[0]['unit']);

				if(file_exists('../images/pegawai/' . $read[0]['uid'] . '.png')) {
					$profile_pic = '/images/pegawai/' . $read[0]['uid'] . '.png';
				} else {
					$profile_pic = '/client/template/assets/images/avatar/demi.png';
				}

				//Register JWT
				$iss = __HOSTNAME__;
				$iat = time();
				$nbf = $iat + 10;
				$exp = $iat + 30;
				$aud = 'users_library';
				$user_arr_data = array(
					'uid' => $read[0]['uid'],
					'pic' => $profile_pic,
					'unit' => $read[0]['unit'],
					'unit_name' => $Unit_Info['response_data'][0]['nama'],
					'unit_kode' => $Unit_Info['response_data'][0]['kode'],
					'gudang' => $Unit_Info['response_data'][0]['gudang'],
					'jabatan' => $read[0]['jabatan'],
					'email' => $read[0]['email'],
                    'nama' => $read[0]['nama'],
					'log_id' => $log
				);
				//$secret_key = bin2hex(random_bytes(32));
				$secret_key = file_get_contents('taknakal.pub');
				$payload_info = array(
					'iss' => $iss,
					'iat' => $iat,
					'nbf' => $nbf,
					'exp' => $exp,
					'aud' => $aud,
					'data' => $user_arr_data,
				);
				$jwt = JWT::encode($payload_info, $secret_key);
				
				$_SESSION['token'] = $jwt;
				$_SESSION['uid'] = $read[0]['uid'];
				$_SESSION['email'] = $read[0]['email'];
				$_SESSION['nama'] = $read[0]['nama'];
				$_SESSION['password'] = $read[0]['password'];
				$_SESSION['jabatan'] = self::get_jabatan_detail($read[0]['jabatan']);
				$_SESSION['unit'] = $Unit_Info['response_data'][0];
				$moduleSelectedMeta = self::get_module($read[0]['uid']);
				$_SESSION['akses_halaman'] = $moduleSelectedMeta['selected'];
				$_SESSION['akses_halaman_link'] = $moduleSelectedMeta['selected_link'];
				$_SESSION['akses_halaman_meta'] = $moduleSelectedMeta['selected_meta'];
				
				$_SESSION['profile_pic'] = $profile_pic;
				if($read[0]['jabatan'] == __UIDDOKTER__) {
					//Load Dokter Data
					$Poli = new Poli(self::$pdo);
					$PoliData = $Poli::get_poli_by_dokter($read[0]['uid']);
					$_SESSION['poli'] = $PoliData;
				}

                if($read[0]['jabatan'] == __UIDPERAWAT__) {
                    //Load Perawat Data
                    $Poli = new Poli(self::$pdo);
                    $PoliData = $Poli::get_poli_by_perawat($read[0]['uid']);
                    $_SESSION['poli'] = $PoliData;
                }

				$responseBuilder['response_result'] = $query->rowCount();
				$responseBuilder['response_message'] = 'Login berhasil';
				$responseBuilder['response_token'] = $jwt;

				$responseBuilder['response_access'] = array();
				/*$Modul = new Modul(self::$pdo);
				/*$responseBuilder['response_access'] = array();
				$Modul = new Modul(self::$pdo);
				$accessBuilder = self::get_access(array(
					'uid' => $read[0]['uid']
				));
				foreach ($accessBuilder as $key => $value) {
					$value['modul_name'] = $Modul::get_detail(array(
						'id' => $value['modul']
					));
					array_push($responseBuilder['response_access'], $value);
				}

				$_SESSION['akses'] = $responseBuilder['response_access'];*/


			} else {
				$responseBuilder['response_result'] = 0;
				$responseBuilder['response_message'] = 'Email / password salah';
			}
		} else {
			$responseBuilder['response_result'] = $query->rowCount();
			$responseBuilder['response_message'] = 'Email / password salah';
		}

		return $responseBuilder;
	}

	//SEMUA PEGAWAI
	public function get_all() {
		$query = self::$pdo->prepare('SELECT * FROM pegawai WHERE deleted_at IS NULL');
		$query->execute();
		$read = $query->fetchAll(\PDO::FETCH_ASSOC);
		$autonum = 1;
		foreach ($read as $key => $value) {
			if(file_exists('../images/pegawai/' . $value['uid'] . '.png')) {
				$profile_pic = '/images/pegawai/' . $value['uid'] . '.png';
			} else {
				$profile_pic = '/client/template/assets/images/avatar/demi.png';
			}
			$read[$key]['profile_pic'] = $profile_pic;

			$Jabatan = self::get_jabatan_detail($value['jabatan'])['response_data'][0];
            $read[$key]['jabatan'] = $Jabatan;

			$read[$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $read;
	}

	//DETAIL PEGAWAI
	public function get_detail($parameter) {
		$data = self::$query
		->select('pegawai', array(
			'uid',
			'email',
			'jabatan',
			'nama',
			'unit',
			'password',
			'created_at',
			'updated_at'
		))
		->where(array(
			'pegawai.deleted_at' => 'IS NULL',
			'AND',
			'pegawai.uid' => '= ?'
		), array(
			$parameter
		))
		->execute();

		$modulDataMeta = self::get_module($data['response_data'][0]['uid']);
		$data['response_module'] = $modulDataMeta['build'];
		if(file_exists('../images/pegawai/' . $data['response_data'][0]['uid'] . '.png')) {
			$profile_pic = '/images/pegawai/' . $data['response_data'][0]['uid'] . '.png';
		} else {
			$profile_pic = '/client/template/assets/images/avatar/demi.png';
		}
		$data['response_data'][0]['profile_pic'] = $profile_pic;
		$data['response_selected'] = $modulDataMeta['selected'];

		return $data;
	}

	//JABATAN DETAIL
	public function get_jabatan_detail($parameter) {
		$data = self::$query
		->select('pegawai_jabatan', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'pegawai_jabatan.deleted_at' => 'IS NULL',
			'AND',
			'pegawai_jabatan.uid' => '= ?'
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

	//JABATAN
	private function get_jabatan() {
		$data = self::$query->select('pegawai_jabatan', array(
			'uid', 'nama'
		))
		->order(array(
			'created_at' => 'asc'
		))
		->where(array(
			'pegawai_jabatan.deleted_at' => 'IS NULL'
		))
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	//JABATAN TAMBAH
	private function tambah_jabatan($parameter) {
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
			$worker = self::$query->insert('pegawai_jabatan', array(
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
						'pegawai_jabatan',
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
	//JABATAN EDIT
	private function edit_jabatan($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_jabatan_detail($parameter['uid']);

		$worker = self::$query
		->update('pegawai_jabatan', array(
			'nama' => $parameter['nama'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'pegawai_jabatan.deleted_at' => 'IS NULL',
			'AND',
			'pegawai_jabatan.uid' => '= ?'
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
					'pegawai_jabatan',
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

	//AKSES PEGAWAI
	public function get_access($parameter) {		

		return
			self::$query
				->select('pegawai_akses', array(
					'id',
					'akses',
					'status'
				))

				->where(array(
					'pegawai_akses.uid_pegawai' => '= ?',
					'AND',
					'pegawai_akses.deleted_at' => 'IS NULL'
				), array(
					$parameter['uid']
				))

				->execute();
	}

	public function get_module($parameter) {
		/*$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);*/

		//Load All Module
		$Module = new Modul(self::$pdo);
		$moduleData = $Module->get_all();


		//Module setter
		$setter = self::$query->select('pegawai_module', array(
			'id',
			'modul'
		))
		->where(array(
			'pegawai_module.uid_pegawai' => '= ?',
			'AND',
			'pegawai_module.deleted_at' => 'IS NULL'
		), array(
			$parameter
		))
		->execute();
		$settedModule = array();
		$settedModuleLink = array();
		$settedModuleMeta = array();
		foreach ($setter['response_data'] as $key => $value) {
			if(!in_array($value['modul'], $settedModule)) {
				array_push($settedModule, $value['modul']);
				array_push($settedModuleLink, self::get_module_detail($value['modul'])['response_data'][0]['identifier']);
				array_push($settedModuleMeta, $value);
			}
		}

		foreach ($moduleData as $key => $value) {
			if(in_array($value['id'], $settedModule)) {
				$moduleData[$key]['checked'] = true;
			} else {
				$moduleData[$key]['checked'] = false;
			}
		}

		return array(
			'build' => $moduleData,
			'selected' => $settedModule,
			'selected_link' => $settedModuleLink,
			'selected_meta' => $settedModuleMeta
		);
	}

	private function get_module_detail($parameter) {
		$data = self::$query->select('modul', array(
			'id',
			'nama',
			'identifier'
		))
		->where(array(
			'modul.id' => '= ?'
		), array(
			$parameter
		))
		->execute();
		return $data;
	}

	private function tambah_pegawai($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$check = self::duplicate_email(array(
			'table' => 'pegawai',
			'check' => $parameter['email']
		));
		if(count($check['response_data']) > 0) {
			$check['response_message'] = 'Duplicate email detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();
			$worker = self::$query->insert('pegawai', array(
				'uid' => $uid,
				'email' => $parameter['email'],
				'password' => password_hash('123456', PASSWORD_DEFAULT),
				'nama' => $parameter['nama'],
				'jabatan' => $parameter['jabatan'],
				'unit' => $parameter['unit'],
				'created_at' => parent::format_date(),
				'updated_at' => parent::format_date()
			))
			->execute();
			if($worker['response_result'] > 0) {

				$data = $parameter['image'];
				list($type, $data) = explode(';', $data);
				list(, $data)      = explode(',', $data);
				$data = base64_decode($data);
				if(!file_exists('../images/pegawai')) {
					mkdir('../images/pegawai');
				}

				file_put_contents('../images/pegawai/' . $uid . '.png', $data);

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
						'pegawai',
						'I',
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class' => __CLASS__
				));
			}
			$worker['response_unique'] = $uid;
			return $worker;
		}
	}
	private function edit_pegawai($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_detail($parameter['uid']);

		$worker = self::$query
		->update('pegawai', array(
			'email' => $parameter['email'],
			'nama' => $parameter['nama'],
			'jabatan' => $parameter['jabatan'],
			'unit' => $parameter['unit'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'pegawai.deleted_at' => 'IS NULL',
			'AND',
			'pegawai.uid' => '= ?'
		), array(
			$parameter['uid']
		))
		->execute();

		if($worker['response_result'] > 0) {
			unset($parameter['access_token']);

			$data = $parameter['image'];
			list($type, $data) = explode(';', $data);
			list(, $data)      = explode(',', $data);
			$data = base64_decode($data);
			if(!file_exists('../images/pegawai')) {
				mkdir('../images/pegawai');
			}

			file_put_contents('../images/pegawai/' . $parameter['uid'] . '.png', $data);

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
					'pegawai',
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

	private function update_pegawai_access($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		if($parameter['accessType'] == 'Y') {
			$check = self::$query
				->select('pegawai_module', array(
					'id'
				))

				->where(array(
					'pegawai_module.uid_pegawai' => '= ?',
					'AND',
					'pegawai_module.modul' => '= ?'
				), array(
					$parameter['uid'],
					$parameter['modul']
				))

				->execute();
			if(count($check['response_data']) > 0) {
				return
					self::$query
						->update('pegawai_module', array(
							'deleted_at' => NULL
						))

						->where(array(
							'pegawai_module.uid_pegawai' => '= ?',
							'AND',
							'pegawai_module.modul' => '= ?'
						), array(
							$parameter['uid'],
							$parameter['modul']
						))

						->execute();

			} else {
				return
					self::$query
						->insert('pegawai_module', array(
							'uid_pegawai' => $parameter['uid'],
							'modul' => $parameter['modul'],
							'logged_at' => parent::format_date(),
							'uid_admin' => $UserData['data']->uid
						))

						->execute();
			}
		} else {
			return
				self::$query
					->update('pegawai_module', array(
						'deleted_at' => parent::format_date()
					))

					->where(array(
						'pegawai_module.uid_pegawai' => '= ?',
						'AND',
						'pegawai_module.modul' => '= ?'
					), array(
						$parameter['uid'],
						$parameter['modul']
					))

					->execute();
		}
	}

	private function update_access($parameter) {
		$check = self::$query
			->select('pegawai_akses', array(
				'id'
			))

			->where(array(
				'pegawai_akses.uid_pegawai' => '= ?',
				'AND',
				'pegawai_akses.akses' => '= ?'
			), array(
				$parameter['uid'],
				$parameter['access']
			))

			->execute();
		if(count($check['response_data']) > 0) {
			return
				self::$query
					->update('pegawai_akses', array(
						'status' => $parameter['accessType']
					))

					->where(array(
						'pegawai_akses.uid_pegawai' => '= ?',
						'AND',
						'pegawai_akses.akses' => '= ?'
					), array(
						$parameter['uid'],
						$parameter['access']
					))

					->execute();

		} else {
			return
				self::$query
					->insert('pegawai_akses', array(
						'uid_pegawai' => $parameter['uid'],
						'akses' => $parameter['access'],
						'status' => $parameter['accessType']
					))

					->execute();
		}
	}

	private function get_all_dokter(){
		$Dokter = self::$query->select('pegawai', array(
					'uid',
					'nama AS nama_dokter'
				)
			)
			->join('pegawai_jabatan', array(
					'uid AS uid_jabatan',
					'nama AS nama_jabatan'
				)
			)
			->on(
				array(
					array('pegawai.jabatan', '=', 'pegawai_jabatan.uid')
				)
			)
			->where(
				array(
				'pegawai.deleted_at' => 'IS NULL',
				'AND',
				'pegawai_jabatan.nama' => '= ?'
				), array(
					'Dokter'
				)
			)
			->execute();
		
		return $Dokter;
	}

    private function master_pegawai_import_fetch($parameter)
    {
        if (!empty($_FILES['csv_file']['name'])) {
            $unique_name = array();

            $file_data = fopen($_FILES['csv_file']['tmp_name'], 'r');
            $column = fgetcsv($file_data); //array_head
            $row_data = array();
            while ($row = fgetcsv($file_data)) {
                if (!in_array($row[0], $unique_name)) {
                    array_push($unique_name, $row[0]);
                    $column_builder = array();
                    foreach ($column as $key => $value) {
                        $column_builder[$value] = $row[$key];
                    }
                    array_push($row_data, $column_builder);
                }
            }

            $build_col = array();
            foreach ($column as $key => $value) {
                array_push($build_col, array("data" => $value));
            }

            $output = array(
                'column' => $column,
                'row_data' => $row_data,
                'column_builder' => $build_col
            );
            return $output;
        }
    }

    private function proceed_import_pegawai($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $duplicate_row = array();
        $termi_item = array();
        $non_active = array();
        $success_proceed = 0;
        $proceed_data = array();
        $worker_poli_dokter = array();

        $resetPegawai = self::$query->update('pegawai', array(
            'deleted_at' => parent::format_date()
        ))
            ->where(array(
                'NOT pegawai.uid' => '= ?'
            ))
            ->execute();

        $hardResetDokter = self::$query->hard_delete('master_poli_dokter')
            ->execute();

        $hardResetPerawat = self::$query->hard_delete('master_poli_perawat')
            ->execute();

        //Reset Master Unit
        $resetUnit = self::$query->update('master_unit', array(
            'deleted_at' => parent::format_date()
        ))
            ->execute();

        foreach ($parameter['data_import'] as $key => $value) {
            $targettedPoli = '';
            $targettedPegawai = '';

            //Jabatan
            $targetUIDJabatan = '';

            $targetUIDGudang = '';
            $targetUIDUnit = '';

            $checkJabatan = self::$query->select('pegawai_jabatan', array(
                'uid',
                'nama'
            ))
                ->where(array(
                    'pegawai_jabatan.nama' => '= ?'
                ), array(
                    $value['jabatan']
                ))
                ->execute();

            if(count($checkJabatan['response_data']) > 0) {
                $targetUIDJabatan = $checkJabatan['response_data'][0]['uid'];
            } else {
                $targetUIDJabatan = parent::gen_uuid();
                $new_jabatan = self::$query->insert('pegawai_jabatan', array(
                    'uid' => $targetUIDJabatan,
                    'nama' => $value['jabatan'],
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
            }

            $checkGudang = self::$query->select('master_inv_gudang', array(
                'uid',
                'nama'
            ))
                ->where(array(
                    'master_inv_gudang.nama' => '= ?'
                ), array(
                    $value['unit']
                ))
                ->execute();

            if(count($checkGudang['response_data']) > 0) {
                $targetUIDGudang = $checkGudang['response_data'][0]['uid'];
            } else {
                $targetUIDGudang = parent::gen_uuid();
                $new_gudang = self::$query->insert('master_inv_gudang', array(
                    'uid' => $targetUIDGudang,
                    'nama' => $value['unit'],
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
            }



            //Generate Unit Baru
            $checkUnit = self::$query->select('master_unit', array(
                'uid',
                'nama'
            ))
                ->where(array(
                    'master_unit.nama' => '= ?',
                    'AND',
                    'master_unit.kode' => '= ?'
                ), array(
                    $value['unit'],
                    strtoupper($value['kode_unit'])
                ))
                ->execute();

            if(count($checkUnit['response_data']) > 0) {
                $targetUIDUnit = $checkUnit['response_data'][0]['uid'];
                $proceed_unit = self::$query->update('master_unit', array(
                    'kode' => strtoupper($value['kode_unit']),
                    'gudang' => $targetUIDGudang,
                    'updated_at' => parent::format_date(),
                    'deleted_at' => NULL
                ))
                    ->where(array(
                        'master_unit.uid' => '= ?'
                    ), array(
                        $targetUIDUnit
                    ))
                    ->execute();
            } else {
                $targetUIDUnit = parent::gen_uuid();
                $proceed_unit = self::$query->insert('master_unit', array(
                    'uid' => $targetUIDJabatan,
                    'nama' => $value['unit'],
                    'kode' => $value['kode_unit'],
                    'gudang' => $targetUIDGudang,
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
            }

            $checkPegawai = self::$query->select('pegawai', array(
                'uid',
                'nama',
                'email'
            ))
                ->where(array(
                    'pegawai.email' => '= ?',
                    'AND',
                    'pegawai.deleted_at' => 'IS NULL'
                ), array(
                    $value['email']
                ))
                ->execute();

            if($value['nama'] != '' && $value['email'] != '' && $targetUIDJabatan !== '') {
                if (count($checkPegawai['response_data']) > 0) {
                    //Update the employee data
                    $targettedPegawai = $checkPegawai['response_data'][0]['uid'];
                    $proceed_pegawai = self::$query->update('pegawai', array(
                        'unit' => $targetUIDUnit,
                        'jabatan' => $targetUIDJabatan,
                        'deleted_at' => NULL
                    ))
                        ->where(array(
                            'pegawai.uid' => '= ?'
                        ), array(
                            $targettedPegawai
                        ))
                        ->execute();
                } else {
                    //New Pegawai
                    $targettedPegawai = parent::gen_uuid();
                    $proceed_pegawai = self::$query->insert('pegawai', array(
                        'uid' => $targettedPegawai,
                        'email' => $value['email'],
                        'nama' => $value['nama'],
                        'password' => '$2y$10$xdwAR9rpYmSfzKOYyfJkcuOUkmxqI.tb03kdJpE41HbpiHndwEHDS',
                        'jabatan' => $targetUIDJabatan,
                        'unit' => $targetUIDUnit,
                        'kontak' => $value['kontak'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                }

                if ($proceed_pegawai['response_result'] > 0) {
                    $success_proceed += 1;
                }

                array_push($proceed_data, $proceed_pegawai);


                if($targetUIDJabatan == __UIDDOKTER__) {

                    if(strtoupper($value['poli_check']) === 1) {
                        $MultiPoli = explode(',', $value['unit']);
                        foreach ($MultiPoli as $MKey => $MValue) {
                            $nama_poli = '';
                            if(
                                trim(strtoupper(trim($MValue))) === 'THT' ||
                                trim(strtoupper(trim($MValue))) === 'IGD'
                            ) {
                                if(trim(strtoupper(trim($MValue))) === 'THT') {
                                    $nama_poli = 'Poliklinik THT';
                                }

                                if(trim(strtoupper(trim($MValue))) === 'IGD') {
                                    $nama_poli = 'IGD';
                                }
                            } else {
                                $nama_poli = 'Poliklinik ' . ucwords(strtolower(trim($MValue)));
                            }

                            if($nama_poli !== '') {

                                //Check Poli
                                $checkPoli = self::$query->select('master_poli', array(
                                    'uid'
                                ))
                                    ->where(array(
                                        'master_poli.nama' => '= ?',
                                        'AND',
                                        'master_poli.editable' => '= ?'
                                    ), array(
                                        $nama_poli,
                                        'TRUE'
                                    ))
                                    ->execute();

                                if (count($checkPoli['response_data']) > 0) {
                                    $targettedPoli = $checkPoli['response_data'][0]['uid'];
                                    $proceed_poli = self::$query->update('master_poli', array(
                                        'deleted_at' => NULL
                                    ))
                                        ->where(array(
                                            'master_poli.uid' => '= ?'
                                        ), array(
                                            $targettedPoli
                                        ))
                                        ->execute();
                                } else {
                                    $targettedPoli = parent::gen_uuid();
                                    $newPoli = self::$query->insert('master_poli', array(
                                        'uid' => $targettedPoli,
                                        'nama' => $nama_poli,
                                        'editable' => 'TRUE',
                                        'created_at' => parent::format_date(),
                                        'updated_at' => parent::format_date()
                                    ))
                                        ->execute();
                                }

                                //Set Dokter Poli
                                $checkPoliDokter = self::$query->select('master_poli_dokter', array())
                                    ->where(array(
                                        'master_poli_dokter.poli' => '= ?',
                                        'AND',
                                        'master_poli_dokter.dokter' => '= ?'
                                    ), array(
                                        $targettedPoli,
                                        $targettedPegawai
                                    ))
                                    ->execute();

                                if(count($checkPoliDokter['response_data']) > 0) {
                                    $workerDokterPoli = self::$query->update('master_poli_dokter', array(
                                        'deleted_at' => NULL
                                    ))
                                        ->where(array(
                                            'master_poli_dokter.poli' => '= ?',
                                            'AND',
                                            'master_poli_dokter.dokter' => '= ?'
                                        ), array(
                                            $targettedPoli,
                                            $targettedPegawai
                                        ))
                                        ->execute();
                                } else {
                                    $workerDokterPoli = self::$query->insert('master_poli_dokter', array(
                                        'poli' => $targettedPoli,
                                        'dokter' => $targettedPegawai,
                                        'created_at' => parent::format_date(),
                                        'updated_at' => parent::format_date()
                                    ))
                                        ->execute();
                                }
                                array_push($worker_poli_dokter, $workerDokterPoli);
                            }
                        }

                        //Update Akses
                        //36, 38, 102
                        $akses_module = array(36, 38, 102);
                        foreach($akses_module as $AKey => $AValue) {
                            //Check Module
                            $checkModule = self::$query->select('pegawai_module', array(
                                'id'
                            ))
                                ->where(array(
                                    'pegawai_module.uid_pegawai' => '= ?',
                                    'AND',
                                    'pegawai_module.modul' => '= ?'
                                ), array(
                                    $targettedPegawai,
                                    $AValue
                                ))
                                ->execute();
                            if(count($checkModule['response_data']) > 0) {
                                $proceed_module = self::$query->update('pegawai_module', array(
                                    'deleted_at' => NULL
                                ))
                                    ->where(array(
                                        'pegawai_module.uid_pegawai' => '= ?',
                                        'AND',
                                        'pegawai_module.modul' => '= ?'
                                    ), array(
                                        $targettedPegawai,
                                        $AValue
                                    ))
                                    ->execute();
                            } else {
                                $proceed_module = self::$query->insert('pegawai_module', array(
                                    'uid_pegawai' => $targettedPegawai,
                                    'modul' => $AValue,
                                    'uid_admin' => $UserData['data']->uid,
                                    'logged_at' => parent::format_date()
                                ))
                                    ->execute();
                            }
                        }
                    }

                } else if ($targetUIDJabatan === __UIDADMIN__) {
                    //Administrator
                    //
                } else if ($targetUIDJabatan === __UIDPERAWAT__) {
                    if(strtoupper($value['poli_check']) === 1) {
                        $MultiPoli = explode(',', $value['unit']);
                        foreach ($MultiPoli as $MKey => $MValue) {
                            $nama_poli = '';
                            if (
                                trim(strtoupper(trim($MValue))) === 'THT' ||
                                trim(strtoupper(trim($MValue))) === 'IGD'
                            ) {
                                if (trim(strtoupper(trim($MValue))) === 'THT') {
                                    $nama_poli = 'Poliklinik THT';
                                }

                                if (trim(strtoupper(trim($MValue))) === 'IGD') {
                                    $nama_poli = 'IGD';
                                }
                            } else {
                                $nama_poli = 'Poliklinik ' . ucwords(strtolower(trim($MValue)));
                            }

                            if ($nama_poli !== '') {
                                //Check Poli
                                $checkPoli = self::$query->select('master_poli', array(
                                    'uid'
                                ))
                                    ->where(array(
                                        'master_poli.nama' => '= ?',
                                        'AND',
                                        'master_poli.editable' => '= ?',
                                        'AND',
                                        'master_poli.deleted_at' => 'IS NULL',
                                    ), array(
                                        $nama_poli,
                                        'TRUE'
                                    ))
                                    ->execute();

                                if (count($checkPoli['response_data']) > 0) {
                                    $targettedPoli = $checkPoli['response_data'][0]['uid'];
                                } else {
                                    $targettedPoli = parent::gen_uuid();
                                    $newPoli = self::$query->insert('master_poli', array(
                                        'uid' => $targettedPoli,
                                        'nama' => $nama_poli,
                                        'editable' => 'TRUE',
                                        'created_at' => parent::format_date(),
                                        'updated_at' => parent::format_date()
                                    ))
                                        ->execute();
                                }

                                //Set Perawat Poli
                                $checkPoliDokter = self::$query->select('master_poli_perawat', array())
                                    ->where(array(
                                        'master_poli_perawat.poli' => '= ?',
                                        'AND',
                                        'master_poli_perawat.perawat' => '= ?'
                                    ), array(
                                        $targettedPoli,
                                        $targettedPegawai
                                    ))
                                    ->execute();

                                if(count($checkPoliDokter['response_data']) > 0) {
                                    $workerPerawatPoli = self::$query->update('master_poli_perawat', array(
                                        'deleted_at' => NULL
                                    ))
                                        ->where(array(
                                            'master_poli_perawat.poli' => '= ?',
                                            'AND',
                                            'master_poli_perawat.perawat' => '= ?'
                                        ), array(
                                            $targettedPoli,
                                            $targettedPegawai
                                        ))
                                        ->execute();
                                } else {
                                    $workerPerawatPoli = self::$query->insert('master_poli_perawat', array(
                                        'poli' => $targettedPoli,
                                        'perawat' => $targettedPegawai,
                                        'created_at' => parent::format_date(),
                                        'updated_at' => parent::format_date()
                                    ))
                                        ->execute();
                                }
                                array_push($worker_poli_perawat, $workerPerawatPoli);
                            }
                        }

                        //36, 54
                        $akses_module = array(36, 54);
                        foreach($akses_module as $AKey => $AValue) {
                            //Check Module
                            $checkModule = self::$query->select('pegawai_module', array(
                                'id'
                            ))
                                ->where(array(
                                    'pegawai_module.uid_pegawai' => '= ?',
                                    'AND',
                                    'pegawai_module.modul' => '= ?'
                                ), array(
                                    $targettedPegawai,
                                    $AValue
                                ))
                                ->execute();
                            if(count($checkModule['response_data']) > 0) {
                                $proceed_module = self::$query->update('pegawai_module', array(
                                    'deleted_at' => NULL
                                ))
                                    ->where(array(
                                        'pegawai_module.uid_pegawai' => '= ?',
                                        'AND',
                                        'pegawai_module.modul' => '= ?'
                                    ), array(
                                        $targettedPegawai,
                                        $AValue
                                    ))
                                    ->execute();
                            } else {
                                $proceed_module = self::$query->insert('pegawai_module', array(
                                    'uid_pegawai' => $targettedPegawai,
                                    'modul' => $AValue,
                                    'uid_admin' => $UserData['data']->uid,
                                    'logged_at' => parent::format_date()
                                ))
                                    ->execute();
                            }
                        }
                    }
                } else if ($targetUIDJabatan === __UIDPETUGASLAB__) {
                    //81
                    $akses_module = array(81);
                    foreach($akses_module as $AKey => $AValue) {
                        //Check Module
                        $checkModule = self::$query->select('pegawai_module', array(
                            'id'
                        ))
                            ->where(array(
                                'pegawai_module.uid_pegawai' => '= ?',
                                'AND',
                                'pegawai_module.modul' => '= ?'
                            ), array(
                                $targettedPegawai,
                                $AValue
                            ))
                            ->execute();
                        if(count($checkModule['response_data']) > 0) {
                            $proceed_module = self::$query->update('pegawai_module', array(
                                'deleted_at' => NULL
                            ))
                                ->where(array(
                                    'pegawai_module.uid_pegawai' => '= ?',
                                    'AND',
                                    'pegawai_module.modul' => '= ?'
                                ), array(
                                    $targettedPegawai,
                                    $AValue
                                ))
                                ->execute();
                        } else {
                            $proceed_module = self::$query->insert('pegawai_module', array(
                                'uid_pegawai' => $targettedPegawai,
                                'modul' => $AValue,
                                'uid_admin' => $UserData['data']->uid,
                                'logged_at' => parent::format_date()
                            ))
                                ->execute();
                        }
                    }
                } else if ($targetUIDJabatan === __UIDPETUGASRAD__) {
                    //69
                    $akses_module = array(69);
                    foreach($akses_module as $AKey => $AValue) {
                        //Check Module
                        $checkModule = self::$query->select('pegawai_module', array(
                            'id'
                        ))
                            ->where(array(
                                'pegawai_module.uid_pegawai' => '= ?',
                                'AND',
                                'pegawai_module.modul' => '= ?'
                            ), array(
                                $targettedPegawai,
                                $AValue
                            ))
                            ->execute();
                        if(count($checkModule['response_data']) > 0) {
                            $proceed_module = self::$query->update('pegawai_module', array(
                                'deleted_at' => NULL
                            ))
                                ->where(array(
                                    'pegawai_module.uid_pegawai' => '= ?',
                                    'AND',
                                    'pegawai_module.modul' => '= ?'
                                ), array(
                                    $targettedPegawai,
                                    $AValue
                                ))
                                ->execute();
                        } else {
                            $proceed_module = self::$query->insert('pegawai_module', array(
                                'uid_pegawai' => $targettedPegawai,
                                'modul' => $AValue,
                                'uid_admin' => $UserData['data']->uid,
                                'logged_at' => parent::format_date()
                            ))
                                ->execute();
                        }
                    }
                }



















            }
        }

        return array(
            'duplicate_row' => $duplicate_row,
            'non_active' => $non_active,
            'success_proceed' => $success_proceed,
            'data' => $parameter['data_import'],
            'proceed' => $proceed_data,
            'worker_dokter' => $worker_poli_dokter,
            'worker_perawat' => $worker_poli_perawat
        );
    }

    private function get_all_dokter_select2(){
        $Dokter = self::$query->select('pegawai', array(
                'uid',
                'nama AS nama_dokter'
            )
        )
            ->join('pegawai_jabatan', array(
                    'uid AS uid_jabatan',
                    'nama AS nama_jabatan'
                )
            )
            ->on(
                array(
                    array('pegawai.jabatan', '=', 'pegawai_jabatan.uid')
                )
            )
            ->where(
                array(
                    'pegawai.nama' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\'',
                    'AND',
                    'pegawai.deleted_at' => 'IS NULL',
                    'AND',
                    'pegawai_jabatan.nama' => '= ?'
                ), array(
                    'Dokter'
                )
            )
            ->limit(10)
            ->execute();

        return $Dokter;
    }



	public function get_detail_pegawai($parameter){
		$pegawai = self::$query->select('pegawai', array(
					'uid',
					'nama'
				)
			)
			->where(
				array(
					'pegawai.deleted_at' => 'IS NULL',
					'AND',
					'pegawai.uid' => '= ?'
				), array(
					$parameter
				)
			)
			->execute();
		
		return $pegawai;
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

	private function duplicate_email($parameter) {
		return self::$query
		->select($parameter['table'], array(
			'uid',
			'email'
		))
		->where(array(
			$parameter['table'] . '.deleted_at' => 'IS NULL',
			'AND',
			$parameter['table'] . '.email' => '= ?'
		), array(
			$parameter['check']
		))
		->execute();
	}
}