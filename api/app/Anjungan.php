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
					return self::avail_loket();
					break;
				case 'check_job':
					return self::check_job();
					break;
				case 'all_loket':
					return self::all_loket();
					break;
				case 'loket_status':
					return self::loket_status();
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
				default:
					return self::get_anjungan();
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
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
		return $data;
	}

	private function check_job() {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$data = self::$query->select('antrian_nomor', array(
			'id',
			'nomor_urut',
			'loket'
		))
		->where(array(
			'antrian_nomor.status' => '= ?',
			'AND',
			'antrian_nomor.pegawai' => '= ?'
		), array(
			'D',
			$UserData['data']->uid
		))
		->execute();
		if(count($data['response_data']) > 0) {
			$data['response_queue'] = $data['response_data'][0]['nomor_urut'];
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
		

		//Sisa Antrian
		$sisa = self::$query->select('antrian_nomor', array(
			'nomor_urut'
		))
		->where(array(
			'antrian_nomor.status' => '= ?'
		), array(
			'N'
		))
		->execute();
		$data['response_standby'] = count($sisa['response_data']);
		
		return $data;
	}

	private function tambah_antrian($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$newUrut = self::$query->select('antrian_nomor', array(
			'id'
		))
		->where(array(
			'antrian_nomor.created_at' => '>= now()::date + interval \'1h\''
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
			$worker['response_antrian'] = count($newUrut['response_data']) + 1;

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
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
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
			'antrian_nomor.status' => '= ?'
		), array(
			'N'
		))
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
			$worker['response_queue'] = $data['response_data'][0]['nomor_urut'];
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
			'antrian_nomor.status' => '= ?'
		), array(
			'N'
		))
		->execute();
		$worker['response_standby'] = count($sisa['response_data']);
		return $worker;
	}

	private function get_anjungan() {
		$data = self::$query->select('master_anjungan', array(
			'uid',
			'kode_anjungan',
			'nomor_anjungan'
		))
		->where(array(
			'master_anjungan.deleted_at' => 'IS NULL'
		), array())
		->execute();
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['jenis'] = self::get_jenis_antrian_item($value['uid']);
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

	private function avail_loket() {
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
			'nama'
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
}