<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Pegawai as Pegawai;
use PondokCoder\Utility as Utility;
//53110873-e6ef-a4bd-c193-d977c101e2d2 anjungan 1
//8ebec07c-181b-3c27-6cdf-eb3d6b1a7a0a umum
//
//888ee3df-1b8f-aec0-9c3f-bc00ea17e87d anjungan 2
//cd66b8d9-3a80-4d87-3f72-5685e3d3123a bpjs

class Anjungan extends Utility {
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
				case 'avail_loket':
					return self::avail_loket($parameter);
					break;
				case 'check_job':
					return self::check_job($parameter[2]);
					break;
				case 'all_loket':
					return self::all_loket();
					break;
				case 'loket_status':
					return self::loket_status();
					break;
				case 'anjungan_jenis':
					return self::anjungan_jenis();
					break;
				case 'get_anjungan_detail':
					return self::get_anjungan_detail($parameter[2]);
					break;
                case 'terlewat':
                    return self::get_terlewat($parameter);
                    break;
				default:
					return self::get_anjungan();
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function __POST__($parameter = array()) {
		try {
			switch($parameter['request']) {
				case 'tambah_antrian':
					return self::tambah_antrian($parameter);
					break;
				case 'selesai_antrian':
					return self::selesai_antrian($parameter);
					break;
				case 'ambil_antrian':
					return self::ambil_antrian($parameter);
					break;
				case 'next_antrian':
					return self::next_antrian($parameter);
					break;
				case 'get_terbilang':
					return self::get_terbilang($parameter);
					break;
				case 'master_tambah_loket':
					return self::master_tambah_loket($parameter);
					break;
				case 'master_edit_loket':
					return self::master_edit_loket($parameter);
					break;
				case 'master_tambah_jenis_antrian':
					return self::master_tambah_jenis_antrian($parameter);
					break;
				case 'master_edit_jenis_antrian':
					return self::master_edit_jenis_antrian($parameter);
					break;
				case 'master_tambah_mesin_anjungan':
					return self::master_tambah_mesin_anjungan($parameter);
					break;
				case 'master_edit_mesin_anjungan':
					return self::master_edit_mesin_anjungan($parameter);
					break;
				default:
					return self::get_anjungan();
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function __DELETE__($parameter = array()) {
		return self::delete($parameter);
	}

	private function master_tambah_loket($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
		$uid = parent::gen_uuid();
		$Proc = self::$query->insert('master_loket', array(
			'uid' => $uid,
			'nama_loket' => $parameter['nama'],
			'created_at' => parent::format_date(),
			'updated_at' => parent::format_date()
		))
			->execute();

		if($Proc['response_result'] > 0) {
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
					'antrian_jenis',
					'I',
					parent::format_date(),
					'N',
					$UserData['data']->log_id
				),
				'class' => __CLASS__
			));
		}

		return $Proc;
	}

	private function master_edit_loket($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);

		$old = self::$query->select('master_loket', array(
			'uid', 'nama_loket', 'created_at', 'updated_at', 'deleted_at', 'user_active'
		))
			->where(array(
				'master_loket.deleted_at' => 'IS NULL',
				'AND',
				'master_loket.uid' => '= ?'
			), array(
				$parameter['uid']
			))
			->execute();

		$Proc = self::$query->update('master_loket', array(
			'nama_loket' => $parameter['nama'],
			'updated_at' => parent::format_date()
		))
			->where(array(
				'master_loket.deleted_at' => 'IS NULL',
				'AND',
				'master_loket.uid' => '= ?'
			), array(
				$parameter['uid']
			))
			->execute();

		if($Proc['response_result'] > 0) {
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
					'antrian_jenis',
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
	}

	private function get_terbilang($parameter) {
		return parent::terbilang($parameter['nomor_urut']);
	}

	private function loket_status() {
		$loketDefine = self::all_loket();

		$data = self::$query->select('antrian_nomor', array(
			'loket',
			'nomor_urut'
		))
		->where(array(
			'(antrian_nomor.status' => '= ?',
			'OR',
			'antrian_nomor.status' => '= ?)'
		), array(
			'D',
			'C'
		))
		->execute();
		$antrianMeta = array();
		foreach ($loketDefine['response_data'] as $key => $value) {
			$antrianMeta[$value['uid']] = 0;
		}

		foreach ($data['response_data'] as $key => $value) {
			$antrianMeta[$value['loket']] = array(
				parent::terbilang($value['nomor_urut']) => $value['nomor_urut']
			);
		}

		return $antrianMeta;		
	}

	private function anjungan_jenis() {
		$data = self::$query->select('antrian_jenis', array(
			'uid',
			'nama',
			'kode',
			'allow_jalur',
			'created_at',
			'updated_at'
		))
		->where(array(
			'antrian_jenis.deleted_at' => 'IS NULL'
		), array(
		))
		->execute();
		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	private function all_loket() {
		$data = self::$query->select('master_loket', array(
			'uid',
			'nama_loket'
		))
		->where(array(
			'master_loket.deleted_at' => 'IS NULL'
		))
		->order(array(
			'nama_loket' => 'ASC'
		))
		->execute();
		$autonum = 1;
		foreach($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	private function check_job($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);

		$data = self::$query->select('antrian_nomor', array(
			'id',
			'nomor_urut',
			'loket',
			'jenis_antrian'
		))
		->where(array(
			'(antrian_nomor.status' => '= ?',
			'OR',
			'antrian_nomor.status' => '= ?)',
			'AND',
			'antrian_nomor.pegawai' => '= ?',
			'AND',
			'DATE(antrian_nomor.created_at)' => '= ?'
		), array(
			'D', 'K',
			$UserData['data']->uid,
			date('Y-m-d')
		))
		->order(array(
			'created_at' => 'DESC'
		))
		->execute();
		if(count($data['response_data']) > 0) {
			//Get Kode Anjungan
			$AnjunganKode = self::get_jenis_detail($data['response_data'][0]['jenis_antrian']);
			$data['response_queue'] = (empty($AnjunganKode[0]['kode'])) ? "0" : $AnjunganKode[0]['kode'] . '-' . $data['response_data'][0]['nomor_urut'];
			$data['response_queue_id'] = $data['response_data'][0]['id'];
			foreach ($data['response_data'] as $key => $value) {
				$data['response_data'][$key]['loket'] = self::get_loket_detail($value['loket'])['response_data'][0];
			}
		}
		//Get Used Loket
		$loket = self::$query->select('master_loket', array(
			'uid'
		))
		->where(array(
			'master_loket.deleted_at' => 'IS NULL',
			'AND',
			'master_loket.user_active' => '= ?'
		), array(
			$UserData['data']->uid
		))
		->execute();

		$data['response_used'] = (count($loket['response_data']) > 0) ? $loket['response_data'][0]['uid'] : "";
		

		//Jalur panggilan loket
		$get_jalur = self::$query->select('antrian_jenis', array(
			'uid',
			'allow_jalur'
		))
		->where(array(
			'antrian_jenis.deleted_at' => 'IS NULL'
		), array(
			//$from_loket
		))
		->execute();


		//Sisa Antrian
		$sisa = self::$query->select('antrian_nomor', array(
			'nomor_urut',
			'jenis_antrian'
		))
		->where(array(
			'antrian_nomor.status' => '= ?',
			'AND',
            'DATE(antrian_nomor.created_at)' => '= ?'
		), array(
			'N',
            date('Y-m-d')
		))
		->execute();
		$data['response_standby'] = count($sisa['response_data']);
		
		return $data;
	}

	private function tambah_antrian($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
		$detail_antrian_jenis = self::get_jenis_detail($parameter['jenis']);
		$newUrut = self::$query->select('antrian_nomor', array(
			'id'
		))
		->where(array(
            'DATE(antrian_nomor.created_at)' => '= ?',
			'AND',
			'antrian_nomor.jenis_antrian' => '= ?'
		), array(
		    date('Y-m-d'),
			$parameter['jenis']
		))
		->execute();

		$worker = self::$query->insert('antrian_nomor', array(
			'nomor_urut' => count($newUrut['response_data']) + 1,
			'anjungan' => $parameter['anjungan'],
			'jenis_antrian' => $parameter['jenis'],
			'created_at' => parent::format_date(),
			'status' => 'N'
		))
		->execute();
		if($worker['response_result'] > 0) {
			//Get Kode Jalur Antrian
			$worker['response_antrian'] = $detail_antrian_jenis[0]['kode'] . '-' . strval((count($newUrut['response_data']) + 1));

			//Add notify
			$notification = self::$query->insert('notification', array(
				'sender' => $UserData['data']->uid,
				'receiver_type' => 'group',
				'receiver' => __UID_PENDAFTARAN__,
				'protocols' => 'anjungan_kunjungan_baru',
				'notify_content' => 'Antrian baru dari anjungan',
				'type' => 'warning',
				'created_at' => parent::format_date(),
				'status' => 'N'
			))
			->execute();
		}
		
		return $worker;
	}

	private function next_antrian($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
		$allowed_jenis = array();
		$allowed_item = array(date('Y-m-d'), 'N');
		//Loket Job
		//Get Jalur
		$Jalur = self::anjungan_jenis();
		foreach ($Jalur['response_data'] as $key => $value) {
			$loketS = explode(',', $value['allow_jalur']);
			if(in_array($parameter['loket'], $loketS)) {
				if(!in_array($value['uid'], $allowed_item)) {
					array_push($allowed_item, $value['uid']);
					array_push($allowed_jenis, ' antrian_nomor.jenis_antrian = ? ');
				}
			}
		}

		//unset($allowed_jenis[count($allowed_jenis) - 1]);
		$allowed_jenis_parsed = implode('OR', $allowed_jenis);

		//Get stand by queue
		$data = self::$query->select('antrian_nomor', array(
			'id',
			'nomor_urut',
			'anjungan',
			'jenis_antrian'
		))
		->where(array(
			'antrian_nomor.loket' => 'IS NULL',
			'AND',
			'antrian_nomor.pegawai' => 'IS NULL',
			'AND',
			'antrian_nomor.kunjungan' => 'IS NULL',
			'AND',
			'antrian_nomor.antrian' => 'IS NULL',
			'AND',
			'antrian_nomor.pasien' => 'IS NULL',
			'AND',
			'antrian_nomor.poli' => 'IS NULL',
            'AND',
            'DATE(antrian_nomor.created_at)' => '= ?',
			'AND',
			'antrian_nomor.status' => '= ? AND (' . implode('OR', $allowed_jenis) . ')'
		), $allowed_item)
		->order(array(
			'created_at' => 'ASC'
		))
		->limit(1)
		->execute();
		if(isset($parameter['currentQueue'])) {
			//delete current data
			$cancel_antrian = self::$query->update('antrian_nomor', array(
				'status' => 'C'
			))
			->where(array(
				'antrian_nomor.pegawai' => '= ?',
				'AND',
				'antrian_nomor.status' => '= ?',
				'AND',
				'antrian_nomor.id' => '= ?'
			), array(
				$UserData['data']->uid,
				'D',
				$parameter['currentQueue']
			))
			->execute();
		}
		if(count($data['response_data']) > 0) {
			$AnjunganKode = self::get_jenis_detail($data['response_data'][0]['jenis_antrian']);
			$worker = self::$query->update('antrian_nomor', array(
				'loket' => $parameter['loket'],
				'pegawai' => $UserData['data']->uid,
				'status' => 'D'
			))
			->where(array(
				'id' => '= ?'
			), array(
				$data['response_data'][0]['id']
			))
			->execute();

			$worker['response_queue_id'] = $data['response_data'][0]['id'];
			$worker['response_queue'] = $AnjunganKode[0]['kode'] . '-' . $data['response_data'][0]['nomor_urut'];
		} else {
			$worker = $data;
			/*$data = self::$query->select('antrian_nomor', array(
				'id',
				'nomor_urut',
				'anjungan',
				'jenis_antrian'
			))
			->where(array(
				'antrian_nomor.status' => '= ?',
				'AND',
				'antrian_nomor.created_at' => '>= now()::date + interval \'1h\'',
				'AND',
				'antrian_nomor.id' => '> ' . (isset($parameter['currentQueue']) ? $parameter['currentQueue'] : 0)
			), array(
				'C'
			))
			->order(array(
				'created_at' => 'ASC'
			))
			->limit(1)
			->execute();
			$data['response_queue_id'] = $data['response_data'][0]['id'];
			$data['response_queue'] = $data['response_data'][0]['nomor_urut'];*/
		}

		//Sisa Antrian
		$sisa = self::$query->select('antrian_nomor', array(
			'nomor_urut'
		))
		->where(array(
			'antrian_nomor.status' => '= ?',
            'AND',
            'DATE(antrian_nomor.created_at)' => '= ?'
		), array(
			'N',
            date('Y-m-d')
		))
		->execute();
		$worker['response_standby'] = count($sisa['response_data']);
		return $worker;
	}

	private function get_anjungan() {
		$data = self::$query->select('master_anjungan', array(
			'uid',
			'kode_anjungan'
		))
		->where(array(
			'master_anjungan.deleted_at' => 'IS NULL'
		), array())
		->execute();
		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['jenis'] = self::get_jenis_antrian_item($value['uid']);
			$autonum++;
		}
		return $data;
	}

	public function get_loket_detail($parameter) {
		$data = self::$query->select('master_loket', array(
			'uid',
			'nama_loket'
		))
		->where(array(
			'master_loket.deleted_at' => 'IS NULL',
			'AND',
			'master_loket.uid' => '= ?'
		), array(
			$parameter
		))
		->execute();
		return $data;
	}

	private function avail_loket($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$data = self::$query->select('master_loket', array(
			'uid',
			'nama_loket'
		))
		->where(array(
			'master_loket.deleted_at' => 'IS NULL',
			'AND',
			'(master_loket.user_active' => 'IS NULL',
			'OR',
			'master_loket.user_active' => '= ?)'
		), array(
			$UserData['data']->uid
		))
		->order(array(
			'nama_loket' => 'ASC'
		))
		->execute();
		return $data;
	}

	private function get_jenis_antrian_item($parameter) {
		$data = self::$query->select('antrian_jenis_item', array(
			'jenis'
		))
		->where(array(
			'antrian_jenis_item.anjungan' => '= ?',
			'AND',
			'antrian_jenis_item.deleted_at' => 'IS NULL'
		), array(
			$parameter
		))
		->execute();
		
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key] = self::get_jenis_detail($value['jenis'])[0];
		}

		return $data['response_data'];
	}

	private function ambil_antrian($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		//Check Jika sudah diambil
		$data = self::$query->select('master_loket', array(
			'uid',
			'user_active'
		))
		->where(array(
			'master_loket.deleted_at' => 'IS NULL',
			'AND',
			'master_loket.uid' => '= ?'
		), array(
			$parameter['loket']
		))
		->execute();
		if($data['response_data'][0]['user_active'] == "") {
			$worker = self::$query->update('master_loket', array(
				'user_active' => $UserData['data']->uid
			))
			->where(array(
				'master_loket.deleted_at' => 'IS NULL',
				'AND',
				'master_loket.uid' => '= ?'
			), array(
				$parameter['loket']
			))
			->execute();
			return $worker;
		} else {
			$data['response_result'] = 0;
			$Pegawai = new Pegawai(self::$pdo);
			$data['response_loket_user'] = $data['response_data'][0]['user_active'];
			$data['response_loket'] = $Pegawai::get_detail($data['response_data'][0]['user_active'])['response_data'][0]['nama'];
			return $data;
		}
	}

	private function selesai_antrian($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$worker = self::$query->update('master_loket', array(
			'user_active' => NULL
		))
		->where(array(
			'master_loket.user_active' => '= ?',
			'AND',
			'master_loket.deleted_at' => 'IS NULL'
		), array(
			$UserData['data']->uid
		))
		->execute();
		if($worker['response_result'] > 0) {
			$update_antrian = self::$query->update('antrian_nomor', array(
				'status' => 'N',
				'pegawai' => NULL,
				'loket' => NULL
			))
			->where(array(
				'antrian_nomor.pegawai' => '= ?',
				'AND',
				'antrian_nomor.status' => '= ?'
			), array(
				$UserData['data']->uid,
				'D'
			))
			->execute();
			return $update_antrian;
		} else {
			return $worker;
		}
	}

	private function get_jenis_detail($parameter) {
		$data = self::$query->select('antrian_jenis', array(
			'uid',
			'nama',
			'kode'
		))
		->where(array(
			'antrian_jenis.uid' => '= ?',
			'AND',
			'antrian_jenis.deleted_at' => 'IS NULL'
		), array(
			$parameter
		))
		->execute();
		return $data['response_data'];	
	}

	private function master_tambah_jenis_antrian($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$uid = parent::gen_uuid();
		$worker = self::$query->insert('antrian_jenis', array(
			'uid' => $uid,
			'kode' => $parameter['kode'],
			'nama' => $parameter['nama'],
			'allow_jalur' => implode(',', $parameter['allow_jalur']),
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
					'antrian_jenis',
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

	private function master_edit_jenis_antrian($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$old = self::get_jenis_detail($parameter['uid']);
		$uid = parent::gen_uuid();
		$worker = self::$query->update('antrian_jenis', array(
			'kode' => $parameter['kode'],
			'nama' => $parameter['nama'],
			'allow_jalur' => implode(',', $parameter['allow_jalur']),
			'updated_at' => parent::format_date()
		))
		->where(array(
			'antrian_jenis.deleted_at' => 'IS NULL',
			'AND',
			'antrian_jenis.uid' => '= ?'
		), array(
			$parameter['uid']
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
					$parameter['uid'],
					$UserData['data']->uid,
					'antrian_jenis',
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

	private function get_terlewat($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $data = self::$query->select('antrian_nomor', array(
            'id',
            'nomor_urut',
            'loket',
            'jenis_antrian'
        ))
            ->where(array(
                '(antrian_nomor.status' => '= ?',
				'OR',
				'antrian_nomor.status' => '= ?)',
                'AND',
                'antrian_nomor.pegawai' => '= ?',
                'AND',
                'DATE(antrian_nomor.created_at)' => '= ?'
            ), array(
                'C', 'K',
                $UserData['data']->uid,
                date('Y-m-d')
            ))
            ->order(array(
                'created_at' => 'DESC'
            ))
            ->execute();
        if(count($data['response_data']) > 0) {
            //Get Kode Anjungan
            foreach ($data['response_data'] as $key => $value) {
                $AnjunganKode = self::get_jenis_detail($value['jenis_antrian']);
                $data['response_data'][$key]['response_queue'] = (empty($AnjunganKode[0]['kode'])) ? "0" : $AnjunganKode[0]['kode'] . '-' . $value['nomor_urut'];
                $data['response_data'][$key]['response_queue_id'] = $value['id'];

                $data['response_data'][$key]['loket'] = self::get_loket_detail($value['loket'])['response_data'][0];
            }
        }

        return $data;
    }

	public function get_anjungan_detail($parameter) {
		$data = self::$query->select('master_anjungan', array(
			'uid',
			'kode_anjungan'
		))
		->where(array(
			'master_anjungan.deleted_at' => 'IS NULL',
			'AND',
			'master_anjungan.uid' => '= ?'
		), array(
			$parameter
		))
		->execute();
		return $data;
	}

	private function master_tambah_mesin_anjungan($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);

		$uid = parent::gen_uuid();
		$worker = self::$query->insert('master_anjungan', array(
			'uid' => $uid,
			'kode_anjungan' => $parameter['nama'],
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
					'master_anjungan',
					'I',
					parent::format_date(),
					'N',
					$UserData['data']->log_id
				),
				'class' => __CLASS__
			));

			//Save Jenis Mesin
			foreach ($parameter['jenis_mesin'] as $key => $value) {
				$JMAntrian = self::$query->insert('antrian_jenis_item', array(
					'anjungan' => $uid,
					'jenis' => $value,
					'created_at' => parent::format_date(),
					'updated_at' => parent::format_date()
				))
				->returning('id')
				->execute();
				if($JMAntrian['response_result'] > 0) {
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
							$JMAntrian['response_unique'],
							$UserData['data']->uid,
							'antrian_jenis_item',
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
		return $worker;
	}

	private function master_edit_mesin_anjungan($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$old = self::get_anjungan_detail($parameter['uid']);
		//$uid = parent::gen_uuid();
		$worker = self::$query->update('master_anjungan', array(
			'kode_anjungan' => $parameter['nama'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_anjungan.deleted_at' => 'IS NULL',
			'AND',
			'master_anjungan.uid' => '= ?'
		), array(
			$parameter['uid']
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
					$parameter['uid'],
					$UserData['data']->uid,
					'master_anjungan',
					'U',
					json_encode($old['response_data'][0]),
					json_encode($parameter),
					parent::format_date(),
					'N',
					$UserData['data']->log_id
				),
				'class' => __CLASS__
			));



			//Reset Status
			$JMAntrianWorker = self::$query->update('antrian_jenis_item', array(
				'deleted_at' => parent::format_date()
			))
			->where(array(
				'antrian_jenis_item.anjungan' => '= ?'
			), array(
				$parameter['uid']
			))
			->execute();

			$oldAntrian = self::get_jenis_antrian_item($parameter['uid']);
			//Save Jenis Mesin
			foreach ($parameter['jenis_mesin'] as $key => $value) {
				$JMAntrianChecker = self::$query->select('antrian_jenis_item', array(
					'id'
				))
				->where(array(
					'antrian_jenis_item.anjungan' => '= ?',
					'AND',
					'antrian_jenis_item.jenis' => '= ?'
				), array(
					$parameter['uid'],
					$value
				))
				->execute();

				if(count($JMAntrianChecker['response_data']) > 0) {
					$JMAntrianWorker = self::$query->update('antrian_jenis_item', array(
						'anjungan' => $parameter['uid'],
						'jenis' => $value,
						'updated_at' => parent::format_date(),
						'deleted_at' => NULL
					))
					->where(array(
						'antrian_jenis_item.id' => '= ?'
					), array(
						$JMAntrianChecker['response_data'][0]['id']
					))
					->execute();
				} else {
					$JMAntrianWorker = self::$query->insert('antrian_jenis_item', array(
						'anjungan' => $parameter['uid'],
						'jenis' => $value,
						'created_at' => parent::format_date(),
						'updated_at' => parent::format_date()
					))
					->returning('id')
					->execute();
				}

					
				if($JMAntrianWorker['response_result'] > 0) {
					if(count($JMAntrianChecker['response_data']) > 0) {
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
								$JMAntrianChecker['response_data'][0]['id'],
								$UserData['data']->uid,
								'antrian_jenis_item',
								'U',
								json_encode($oldAntrian['response_data'][0]),
								json_encode($parameter['jenis_mesin']),
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class' => __CLASS__
						));
					} else {
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
								$JMAntrianWorker['response_unique'],
								$UserData['data']->uid,
								'antrian_jenis_item',
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
		}
		return $worker;
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
}