<?php

namespace PondokCoder;

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
		$moduleData = $Module::get_all();


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