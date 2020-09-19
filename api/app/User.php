<?php

namespace PondokCoder;

use PondokCoder\Utility as Utility;
use PondokCoder\Mailer as Mailer;
use \Firebase\JWT\JWT;

class User extends Utility{
	static $pdo;
	protected static function getConn(){
		return self::$pdo;
	}

	public function __construct($connection) {
		self::$pdo = $connection;
	}

	public function __GET__($parameter = array()) {
		if($parameter[1] == 'detail') {
			$query = self::$pdo->prepare('SELECT * FROM member WHERE uid = ? AND deleted_at IS NULL');
			$query->execute(array($parameter[2]));
			if($query->rowCount() > 0) {
				$read = $query->fetchAll(\PDO::FETCH_ASSOC);
				$read[0]['nik'] = ($read[0]['nik'] == null) ? "" : $read[0]['nik'];
				$read[0]['nama'] = ($read[0]['nama'] == null) ? "" : $read[0]['nama'];
				$read[0]['no_handphone'] = ($read[0]['no_handphone'] == null) ? "" : $read[0]['no_handphone'];
				return $read;
			} else {
				return array();
			}
		} else if ($parameter[1] == 'activate') {
			$query = self::$pdo->prepare('SELECT * FROM member WHERE uid = ?');
			$query->execute(array($parameter[2]));
			$read = $query->fetchAll(\PDO::FETCH_ASSOC);
			if(intval($read[0]['google_confirm']) == 0) {
				$update = self::$pdo->prepare('UPDATE member SET google_confirm = 1 WHERE uid = ?');
				$update->execute(array($parameter[2]));
				if($update->rowCount() > 0) {
					/*$mutation = array(
						'__nama__' => $read[0]['nama'],
						'__email__' => $read[0]['email']
					);
					$body = file_get_contents("email_template/activate.phtml");
					foreach($mutation as $k => $v){
						$body = str_replace("{".strtoupper($k)."}", $v, $body);
					}
					echo $body;*/
					require 'email_template/activate.php';	
				} else {
					require 'email_template/exception.php';
				}
			} else {
				require 'email_template/exception.php';
			}
		} else if ($parameter[1] == 'decline') {
			$query = self::$pdo->prepare('SELECT * FROM member WHERE uid = ?');
			$query->execute(array($parameter[2]));
			$read = $query->fetchAll(\PDO::FETCH_ASSOC);
			if(intval($read[0]['google_confirm']) == 0) {
				$update = self::$pdo->prepare('UPDATE member SET deleted_at = NOW() WHERE uid = ?');
				$update->execute(array($parameter[2]));
				if($update->rowCount() > 0) {
					/*$body = file_get_contents("email_template/decline.phtml");
					echo $body;*/
					require 'email_template/decline.php';
				} else {
					require 'email_template/exception.php';
				}
			} else {
				require 'email_template/exception.php';
			}
		}
		
			
	}

	public function __POST__($parameter = array()) {
		$dataReturn = array();
		switch ($parameter['request']) {
			case 'login':
				$dataReturn = self::login($parameter);
				break;
			case 'google_login':
				$dataReturn = self::google_login($parameter);
				break;
			case 'register':
				$dataReturn = self::register($parameter);
				break;
			case 'update_profile':
				$dataReturn = self::update_profile($parameter);
				break;
			default:
				$dataReturn["response"] = 'Unknown Request';
				break;
		}
		return $dataReturn;
	}

	public function test(){
		return '123';
	}
	
	
	private function device_check($parameter = array()) {
	    
	    $checkToken = self::$pdo->prepare('SELECT * FROM member_device WHERE member_uid = ? AND member_devices = ?');
		$checkToken->execute(array(
			$parameter['uid'],
			$parameter['token']
		));

		if($checkToken->rowCount() < 1) {
			$newDevice = self::$pdo->prepare('INSERT INTO member_device (member_uid, member_devices) VALUES (?, ?)');
			$newDevice->execute(array(
				$parameter['uid'],
				$parameter['token']
			));
		}
		
	}

	private function google_login($parameter) {
	    $parameter['google_login'] = 1;
	    $parameter['password'] = date("Y-m-d/H:i:s");
	    $parameter['google_confirm'] = 1;
	    $parameter['kecamatan'] = 0;
	    $parameter['desa'] = 0;
	    
		$query = self::$pdo->prepare("SELECT * FROM member WHERE email = ? AND google_login = ? AND deleted_at IS NULL");
		$query->execute(array($parameter["email"], 1));
		if($query->rowCount() > 0) {
			$read = $query->fetchAll(\PDO::FETCH_ASSOC);

			self::device_check(array(
				"uid" => $read[0]['uid'],
				"token" => $parameter['token']
			));
			
			
			$iss = __HOSTNAME__;
			$iat = time();
			$nbf = $iat + 10;
			$exp = $iat + 3600;
			$aud = 'users_library';
			$user_arr_data = array(
				'uid' => $read[0]['uid'],
				'email' => $read[0]['email'],
			);
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




			if($read[0]['google_confirm'] > 0) {
				
				$read[0]['nik'] = ($read[0]['nik'] == null) ? "" : $read[0]['nik'];
				$read[0]['nama'] = ($read[0]['nama'] == null) ? "" : $read[0]['nama'];
				$read[0]['no_handphone'] = ($read[0]['no_handphone'] == null) ? "" : $read[0]['no_handphone'];
				
				$returnBuilder['response'] = 'logged in successfully.';
				$returnBuilder['response_data'] = $read[0];
				$returnBuilder['response_jwt'] = $jwt;
				$returnBuilder['response_code'] = 200;
				
				
			} else {
			    //Bukan akun gmail
			}
		} else {
		    $returnBuilder = self::register($parameter);
		}
		return $returnBuilder;
	}

	private function login($data) {
		$returnBuilder = array();
		$query = self::$pdo->prepare('SELECT * FROM member WHERE email = ?');
		$query->execute(array($data['email']));
		if($query->rowCount() > 0) {
			$read = $query->fetchAll(\PDO::FETCH_ASSOC);
			if($read[0]["google_confirm"] > 0) {
				//password hashing check
				if(password_verify($data['password'], $read[0]['password'])) {

					/*$checkToken = self::$pdo->prepare('SELECT * FROM member_device WHERE member_uid = ? AND member_devices = ?');
					$checkToken->execute(array(
						$read[0]['uid'],
						$data['token']
					));

					if($checkToken->rowCount() < 1) {
						echo $parameter['token'];
						$newDevice = self::$pdo->prepare('INSERT INTO member_device (member_uid, member_devices) VALUES (?, ?)');
						$newDevice->execute(array(
							$read[0]['uid'],
							$data['token']
						));
					}*/

					self::device_check(array(
						"uid" => $read[0]['uid'],
						"token" => $data['token']
					));

					/*$micro_date = microtime();
					$date_array = explode(" ",$micro_date);
					$date = date("Y-m-d H:i:s",$date_array[1]);
					
					$LOG_ID = parent::log(array(
						'connection' => $pdo,
						'type' => 'login',
						'column' => array('user_uid','login_meta','logged_at'),
						'value' => array($read[0]['uid'],'[' . $read[0]['uid'] . '][' . $read[0]['user_email'] . '] Success Logged In.',$date),
						'class' => 'User'
					));*/

					//Register JWT
					$iss = __HOSTNAME__;
					$iat = time();
					$nbf = $iat + 10;
					$exp = $iat + 3600;
					$aud = 'users_library';
					$user_arr_data = array(
						'uid' => $read[0]['uid'],
						'email' => $read[0]['email'],
						/*'log_id' => $LOG_ID*/
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

					/*$returnBuilder['response'] = 'login success';
					$returnBuilder['response_code'] = 200;
					$returnBuilder['response_data'] = array(
						'basic' => $read[0],
						'jwt' => $jwt
					);*/

					

					


					$returnBuilder['response'] = 'logged in successfully.';
					$read[0]['nik'] = ($read[0]['nik'] == null) ? "" : $read[0]['nik'];
					$read[0]['nama'] = ($read[0]['nama'] == null) ? "" : $read[0]['nama'];
					$read[0]['no_handphone'] = ($read[0]['no_handphone'] == null) ? "" : $read[0]['no_handphone'];
					$returnBuilder['response_data'] = $read[0];
					$returnBuilder['response_jwt'] = $jwt;
					$returnBuilder['response_code'] = 200;

				} else {
					

					$micro_date = microtime();
					$date_array = explode(" ",$micro_date);
					$date = date("Y-m-d H:i:s",$date_array[1]);

					/*parent::log(array(
						'type' => 'login',
						'column' => array('user_uid','login_meta','logged_at'),
						'value' => array($read[0]['uid'],'[' . $read[0]['uid'] . '][' . $read[0]['user_email'] . '] Failed to login. invalid credential',$date),
						'class' => 'User'
					));*/


					$returnBuilder['response'] = 'username / password invalid.';
					$returnBuilder['response_code'] = 403;


				}
			} else {
				$returnBuilder['response'] = 'Email belum terverifikasi';
				$returnBuilder['response_code'] = 200;
			}

		} else {
			/*$micro_date = microtime();
				$date_array = explode(" ",$micro_date);
				$date = date("Y-m-d H:i:s",$date_array[1]);

			parent::log(array(
				'connection' => $pdo,
				'type' => 'error',
				'column' => array('type','class','message','logged_at'),
				'value' => array('login','User','[' . $data['email'] . '] Failed trying to log in.',$date),
				'class' => 'User'
			));*/

			$returnBuilder['response'] = 'username / password invalid.';
			//$returnBuilder['response_code'] = 403;
		}
		return $returnBuilder;
	}





	private function update_profile($parameter) {
		$returnBuilder = array();
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken();


		//Check KTP
		$check = self::$pdo->prepare('SELECT * FROM member WHERE nik = ? AND deleted_at IS NULL');
		$check->execute(array(
			$parameter['nik']
		));

		if($check->rowCount() > 0) {
			$returnBuilder['response'] = 'KTP sudah dipakai';
			$returnBuilder['response_code'] = 0;
		} else {
			$query = self::$pdo->prepare('UPDATE member SET nama = ?, nik = ?, no_handphone = ?, id_desa = ?, id_kecamatan = ? WHERE uid = ? AND deleted_at IS NULL');
			$query->execute(array(
				$parameter['nama'],
				$parameter['nik'],
				$parameter['kontak'],
				$parameter['desa'],
				$parameter['kecamatan'],
				$UserData['data']->uid
			));

			if($query->rowCount() > 0) {
				$User = self::__GET__(array(
					'User',
					'detail',
					$UserData['data']->uid
				));

				$returnBuilder['response'] = 'Profile Berhasil diupdate';
				$returnBuilder['response_session'] = $User;
			} else {
				$returnBuilder['response'] = 'Gagal update';
				$returnBuilder['check'] = 'UPDATE member SET nama = "' . $parameter['nama'] . '", nik = "' . $parameter['nik'] . '", no_handphone = "' . $parameter['kontak'] . '", id_desa = "' . $parameter['desa'] . '", id_kecamatan = "' . $parameter['kecamatan'] . '" WHERE uid = "' . $UserData['data']->uid . '" AND deleted_at IS NULL';
			}
			$returnBuilder['response_code'] = $query->rowCount();
		}
		return $returnBuilder;
	}





	private function register($parameter) {
		$checkEmail = self::$pdo->prepare("SELECT * FROM member WHERE email = ? OR nik = ?");
		$checkEmail->execute(array($parameter['email'], $parameter['nik']));
		if($checkEmail->rowCount() > 0) {
			http_response_code(403);
			$returnBuilder['response_query'] = 0;
			$returnBuilder['response'] = 'Email atau NIK telah terdaftar';
		} else {
			http_response_code(200);
			$UID = Utility::gen_uuid();

			if(intval($parameter['google_login']) > 0) {
				$parameter['nik'] = '';
				$parameter['nama'] = '';
				$parameter['no_handphone'] = '';
			}


			$register = self::$pdo->prepare('INSERT INTO member (
				uid, nik, nama, email, password,
				no_handphone, created_at, updated_at, is_login,
				id_status, google_login, google_confirm, id_desa, id_kecamatan
			) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW(), ' . (($parameter['google_login'] == 1) ? '"Y"' : '"N"' ) . ', 2, ?, ' . $parameter['google_login'] . ', ?, ?)');
			$register->execute(array($UID, $parameter['nik'], $parameter['nama'], $parameter['email'], password_hash($parameter['password'], PASSWORD_DEFAULT, ['cost' => 11]), $parameter["no_handphone"], $parameter["google_login"], $parameter["desa"], $parameter["kecamatan"]));
			$returnBuilder['response_query'] = $register->rowCount();
			$returnBuilder['response_code'] = 200;
			$returnBuilder['response'] = 'Berhasil terdaftar. Silahkan login.';
			if($register->rowCount() > 0) {
				//SEND EMAIL
				
				self::device_check(array(
					"uid" => $UID,
					"token" => $parameter['token']
				));
				
				if(intval($parameter['google_login']) == 0) {
					$Mailer = new Mailer(
						array(
							'server' => 'mail.pondokcoder.com',
							'secure_type' => 'tls',
							'port' => 587,
							'username' => 'cs_sitanggap@pondokcoder.com',
							'password' => 'medandevelopergroup0192',
							'fromMail' => 'cs_sitanggap@pondokcoder.com',
							'fromName' => 'Sitanggap Lapor Desa',
							'replyMail' => 'cs_sitanggap@pondokcoder.com',
							'replyName' => 'Sitanggal Lapor Desa',
							'template' => 'email_template/index.phtml',
						),
						
						/*array(
							'server' => 'smtp.gmail.com',
							'secure_type' => 'ssl',
							'port' => 587,
							'username' => 'sitanggap.aceh.lapordesa@gmail.com',
							'password' => 'jixuglvldsoxihnz',
							'fromMail' => 'sitanggap.aceh.lapordesa@gmail.com',
							'fromName' => 'Sitanggap Lapor Desa',
							'replyMail' => 'sitanggap.aceh.lapordesa@gmail.com',
							'replyName' => 'Sitanggal Lapor Desa',
							'template' => 'email_template/index.phtml',
						),*/
						array(
							'__HOSTNAME__' => __HOSTNAME__,
							'__NAMA__' => $parameter['nama'],
							'__UID__' => $UID,
						),
						"Registrasi Member Sitanggap Lapor Desa",
						'
								<h4>Selamat Bergabung, ' . $parameter['nama'] . '!<h4>
								<span>Anda telah terdaftar di Sitanggap Lapor Desa</span>
								<br />
								<p>Silahkan klik link dibawah ini untuk mengaktifkan akun</p>
								<a href="' . __HOSTNAME__ . '/users/activate/' . $UID . '">
									' . __HOSTNAME__ . '/users/activate/' . $UID . '
								</a>
							',
						'Selamat Bergabung ' . $parameter['nama'] . '! Anda telah terdaftar di Sitanggap Lapor Desa. Silahkan akses link (' . __HOSTNAME__ . '/users/activate/' . $UID . ') untuk aktivasi akun.',
						array(
							$parameter['email'] => $parameter['nama'],
						)
					);
				} else {
					$iss = __HOSTNAME__;
					$iat = time();
					$nbf = $iat + 10;
					$exp = $iat + 3600;
					$aud = 'users_library';
					$user_arr_data = array(
						'uid' => $UID,
						'email' => $parameter['email'],
					);
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

					$returnBuilder['response'] = 'logged in successfully.';
					$returnBuilder['response_data'] = self::__GET__(array(
						'User',
						'detail',
						$UID
					));
					$returnBuilder['response_jwt'] = $jwt;
					$returnBuilder['response_code'] = 200;
				}
			}
		}
		return $returnBuilder;
	}

}