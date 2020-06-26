<?php

namespace PondokCoder;

use PondokCoder\Utility as Utility;
use PondokCoder\Modul as Modul;
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
			return self::get_detail(array(
				'uid' => $parameter[2]
			));

		} else if($parameter[1] == 'akses') {

			//__HOST__/Pegawai/akses/{uid}
			return self::get_access(array(
				'uid' => $parameter[2]
			));

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
			default:
				return array();
				break;
		}
	}

	public function __DELETE__($parameter = array()) {
		$query = self::$pdo->prepare('UPDATE pegawai SET deleted_at = NOW() WHERE uid = ?');
		$query->execute(array($parameter));
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



				//Register JWT
				$iss = __HOSTNAME__;
				$iat = time();
				$nbf = $iat + 10;
				$exp = $iat + 30;
				$aud = 'users_library';
				$user_arr_data = array(
					'uid' => $read[0]['uid'],
					'email' => $read[0]['email'],
					'log_id' => $LOG_ID
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
				$_SESSION['email'] = $read[0]['email'];
				$_SESSION['nama'] = $read[0]['nama'];
				$_SESSION['password'] = $read[0]['password'];


				$responseBuilder['response_result'] = $query->rowCount();
				$responseBuilder['response_message'] = 'Login berhasil';
				$responseBuilder['response_token'] = $jwt;


				$responseBuilder['response_access'] = array();
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

				$_SESSION['akses'] = $responseBuilder['response_access'];


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
			$read[$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $read;
	}

	//DETAIL PEGAWAI
	public function get_detail($parameter) {
		return
			self::$query
				->select('pegawai', array(
					'uid',
					'email',
					'nama',
					'password',
					'created_at',
					'updated_at'
				))

				->where(array(
					'deleted_at' => 'IS NULL'
				))

				->execute();
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
					'pegawai_akses.uid_pegawai' => '= ?'
				), array(
					$parameter['uid']
				))

				->execute();
	}

	private function edit_pegawai($parameter){
		$responseBuilder = array();
		$query = self::$pdo->prepare('UPDATE pegawai SET nama = ?, updated_at = NOW() WHERE uid = ? AND deleted_at IS NULL');
		$query->execute(array($parameter['nama'], $parameter['uid']));
		$responseBuilder['response_result'] = $query->rowCount();
		if($query->rowCount() > 0) {
			$responseBuilder['response_message'] = 'Berhasil update';	
		} else {
			$responseBuilder['response_message'] = 'Gagal update';
		}
		return $responseBuilder;
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
}