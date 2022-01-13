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

                case 'get_poli_select2':
                    return self::get_poli_select2($parameter);
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
			case 'sync_poli_dokter_data':
				return self::sync_poli_dokter_data($parameter);
				break;
            case 'get_poli_backend':
                return self::get_poli_backend($parameter);
                break;

			case 'tambah_poli':
				return self::tambah_poli($parameter);
				break;

			case 'edit_poli':
				return self::edit_poli($parameter);
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

			case 'get_poli_tindakan_back_end':
				return self::get_poli_tindakan_back_end($parameter);
				break;

			case 'add_poli_tindakan':
				return self::add_poli_tindakan($parameter);
				break;

			case 'add_dokter_tindakan':
				return self::add_dokter_tindakan($parameter);
				break;

			case 'add_perawat_tindakan':
				return self::add_perawat_tindakan($parameter);
				break;

			case 'delete_poli_tindakan':
				return self::delete_poli_tindakan($parameter);
				break;

			case 'get_poli_dokter_back_end':
				return self::get_poli_dokter_back_end($parameter);
				break;

			case 'get_poli_perawat_back_end':
				return self::get_poli_perawat_back_end($parameter);
				break;

			case 'delete_poli_dokter':
				return self::delete_poli_dokter($parameter);
				break;

			case 'delete_poli_perawat':
				return self::delete_poli_perawat($parameter);
				break;

			case 'get_kunjungan_per_layanan':
				return self::get_kunjungan_per_layanan($parameter);
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
    private function get_poli_select2($parameter) {
        $data = self::$query->select('master_poli', array(
                'uid',
                'nama',
                'tindakan_konsultasi',
                'kode_bpjs',
                'nama_bpjs',
                'created_at',
                'updated_at'
            ))
            ->order(array(
                'master_poli.created_at' => 'ASC'
            ))
            ->where(array(
                'master_inv.deleted_at' => 'IS NULL',
                'AND',
                '(master_inv.kode_barang' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\'',
                'OR',
                'master_inv.nama' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\')'
            ))
            ->limit(10)
            ->execute();
        return $data['response_data'];
    }

	private function sync_poli_dokter_data($parameter) {
		$delRes = array();
		$PoliDokter = self::$query->select('master_poli_dokter', array(
			'id', 'poli'
		))
			->execute();
		foreach($PoliDokter['response_data'] as $key => $value) {
			$Poli = self::$query->select('master_poli', array(
				'uid', 'deleted_at'
			))
				->where(array(
					'master_poli.uid' => '= ?'
				), array(
					$value['poli']
				))
				->execute();
			if(count($Poli['response_data']) > 0) {
				if(isset($Poli['response_data'][0]['deleted_at']) && !empty($Poli['response_data'][0]['deleted_at'])) {
					$proc = self::$query->hard_delete('master_poli_dokter')
						->where(array(
							'master_poli_dokter.id' => '= ?'
						), array(
							$value['id']
						))
						->execute();	
				}
			} else {
				$proc = self::$query->hard_delete('master_poli_dokter')
					->where(array(
						'master_poli_dokter.id' => '= ?'
					), array(
						$value['id']
					))
					->execute();
			}

			if($proc['response_result'] > 0) {
				array_push($delRes, $proc);
			}
		}
		return $delRes;
	}

    private function get_poli_backend($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'master_poli.editable' => '= ?',
                'AND',
                'master_poli.deleted_at' => 'IS NULL',
                'AND',
                'master_poli.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array('TRUE');
        } else {
            $paramData = array(
                'master_poli.editable' => '= ?',
                'AND',
                'master_poli.deleted_at' => 'IS NULL'
            );

            $paramValue = array('TRUE');
        }

        if ($parameter['length'] < 0) {
            $data = self::$query->select('master_poli', array(
                'uid',
                'nama',
                'tindakan_konsultasi',
                'kode_bpjs',
                'nama_bpjs',
                'created_at',
                'updated_at'
            ))
                ->order(array(
                    'master_poli.created_at' => 'ASC'
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('master_poli', array(
                'uid',
                'nama',
                'tindakan_konsultasi',
                'kode_bpjs',
                'nama_bpjs',
                'created_at',
                'updated_at'
            ))
                ->order(array(
                    'master_poli.created_at' => 'ASC'
                ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->where($paramData, $paramValue)
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $Tindakan = new Tindakan(self::$pdo);
            $TindakanDetail = $Tindakan->get_tindakan_detail($value['tindakan_konsultasi']);
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['tindakan_konsultasi'] = $TindakanDetail['response_data'][0]['nama'];
            $autonum++;
        }


        $itemTotal = self::$query->select('master_poli', array(
            'id'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

	public function get_poli() {
		$data = self::$query->select('master_poli', array(
			'uid',
			'nama',
			'tindakan_konsultasi',
			'kode_bpjs',
            'nama_bpjs',
			'created_at',
			'updated_at'
		))
		->order(array(
			'master_poli.created_at' => 'ASC'
		))
		->where(array(
			'master_poli.deleted_at' => 'IS NULL'
		))
		->execute();

		$autonum = 1;
        $Tindakan = new Tindakan(self::$pdo);
		foreach ($data['response_data'] as $key => $value) {
			$TindakanDetail = $Tindakan->get_tindakan_detail($value['tindakan_konsultasi']);
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['tindakan_konsultasi'] = $TindakanDetail['response_data'][0]['nama'];
			$autonum++;
		}

		return $data;
	}

	public function get_poli_editable(){
		$data = self::$query
					->select('master_poli', array(
						'uid',
						'nama',
						'editable',
						'created_at',
						'updated_at'
						)
					)	
					->where(array(
							'master_poli.deleted_at' => 'IS NULL',
							/*'AND',
							'master_poli.editable' => '= TRUE'*/
						),array()
					)
            ->order(array(
                'nama' => 'ASC'
            ))
					->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $data;
	}

	public function get_poli_info($parameter) {
        $data = self::$query->select('master_poli', array(
            'uid',
            'nama',
            'tindakan_konsultasi',
            'poli_asesmen',
            'kode_bpjs',
            'nama_bpjs',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'master_poli.deleted_at' => 'IS NULL',
                'AND',
                'master_poli.uid' => '= ?'
            ), array($parameter))
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
        }
        return $data;
    }

	public function get_poli_detail($parameter) {
		$data = self::$query->select('master_poli', array(
            'uid',
            'nama',
            'tindakan_konsultasi',
            'poli_asesmen',
            'kode_bpjs',
            'nama_bpjs',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'master_poli.deleted_at' => 'IS NULL',
                'AND',
                'master_poli.uid' => '= ?'
            ), array($parameter))
            ->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;

			$data['response_data'][$key]['tindakan'] = self::get_poli_tindakan($parameter)['response_data'];
			$data['response_data'][$key]['dokter'] = self::get_set_dokter($parameter)['response_data'];
			$data['response_data'][$key]['perawat'] = self::get_set_perawat($parameter)['response_data'];
		}

		return $data;
	}

	private function get_poli_tindakan($parameter) {
		$data = self::$query->select('master_poli_tindakan', array(
			'id',
			'uid_poli',
			'uid_tindakan',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_poli_tindakan.deleted_at' => 'IS NULL',
			'AND',
			'master_poli_tindakan.uid_poli' => '= ?'
		), array($parameter))
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$Tindakan = new Tindakan(self::$pdo);
			$data['response_data'][$key]['tindakan'] = $Tindakan::get_tindakan_detail($value['uid_tindakan'])['response_data'][0];
			$autonum++;
		}

		/*$targetKelas = (!isset($parameter['kelas'])) ? __UID_KELAS_GENERAL_RJ__ : $parameter['kelas'];
		
		$data = self::$query->select('master_tindakan_kelas_harga', array(
			'id',
			'tindakan as uid_tindakan',
			'kelas',
			'harga',
			'penjamin as uid_penjamin',
			'created_at',
			'deleted_at'
		))
		->where(array(
			'master_tindakan_kelas_harga.deleted_at' => 'IS NULL'
		), array(

		))
		->execute();
		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$Penjamin = new Penjamin(self::$pdo);
			$Tindakan = new Tindakan(self::$pdo);
			$data['response_data'][$key]['tindakan'] = $Tindakan::get_tindakan_detail($value['uid_tindakan'])['response_data'][0];
			$data['response_data'][$key]['penjamin'] = $Penjamin::get_penjamin_detail($value['uid_penjamin'])['response_data'][0];
			$autonum++;
		}*/
		return $data;
	}

	private function delete_poli_tindakan($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$poli = self::$query->delete('master_poli_tindakan')
		->where(array(
			'master_poli_tindakan.uid_tindakan' => '= ?',
			'AND',
			'master_poli_tindakan.uid_poli' => '= ?'
		), array(
			$parameter['tindakan'],
			$parameter['poli']
		))
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
						$parameter['tindakan'] . '|' . $parameter['poli'],
						$UserData['data']->uid,
						'master_poli_tindakan',
						'D',
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

	private function delete_poli_dokter($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$poli = self::$query->delete('master_poli_dokter')
		->where(array(
			'master_poli_dokter.dokter' => '= ?',
			'AND',
			'master_poli_dokter.poli' => '= ?'
		), array(
			$parameter['dokter'],
			$parameter['poli']
		))
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
						$parameter['dokter'] . '|' . $parameter['poli'],
						$UserData['data']->uid,
						'master_poli_dokter',
						'D',
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

	private function delete_poli_perawat($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$poli = self::$query->delete('master_poli_perawat')
		->where(array(
			'master_poli_perawat.perawat' => '= ?',
			'AND',
			'master_poli_perawat.poli' => '= ?'
		), array(
			$parameter['perawat'],
			$parameter['poli']
		))
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
						$parameter['perawat'] . '|' . $parameter['poli'],
						$UserData['data']->uid,
						'master_poli_perawat',
						'D',
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

	private function add_poli_tindakan($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		//Check Tindakan
		$check = self::$query->select('master_poli_tindakan', array(
			'id',
			'uid_poli',
			'uid_tindakan'
		))
		->where(array(
			'master_poli_tindakan.uid_tindakan' => '= ?',
			'AND',
			'master_poli_tindakan.uid_poli' => '= ?'
		), array(
			$parameter['tindakan'],
			$parameter['poli']
		))
		->execute();

		if(count($check['response_data']) > 0) {
			$worker = self::$query->update('master_poli_tindakan', array(
				'deleted_at' => NULL
			))
			->where(array(
				'master_poli_tindakan.uid_tindakan' => '= ?',
				'AND',
				'master_poli_tindakan.uid_poli' => '= ?'
			), array(
				$parameter['tindakan'],
				$parameter['poli']
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
						$check['response_data'][0]['id'],
						$UserData['data']->uid,
						'master_poli_tindakan',
						'U',
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class'=>__CLASS__
				));
			}
		} else {
			$worker = self::$query->insert('master_poli_tindakan', array(
				'uid_poli' => $parameter['poli'],
				'uid_tindakan' => $parameter['tindakan'],
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
						'master_poli_tindakan',
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

	private function add_dokter_tindakan($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		//Check Tindakan
		$check = self::$query->select('master_poli_dokter', array(
			'id',
			'poli',
			'dokter'
		))
		->where(array(
			'master_poli_dokter.dokter' => '= ?',
			'AND',
			'master_poli_dokter.poli' => '= ?'
		), array(
			$parameter['dokter'],
			$parameter['poli']
		))
		->execute();

		if(count($check['response_data']) > 0) {
			$worker = self::$query->update('master_poli_dokter', array(
				'deleted_at' => NULL
			))
			->where(array(
				'master_poli_dokter.dokter' => '= ?',
				'AND',
				'master_poli_dokter.poli' => '= ?'
			), array(
				$parameter['dokter'],
				$parameter['poli']
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
						$check['response_data'][0]['id'],
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

	private function add_perawat_tindakan($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		//Check Tindakan
		$check = self::$query->select('master_poli_perawat', array(
			'id',
			'poli',
			'perawat'
		))
		->where(array(
			'master_poli_perawat.perawat' => '= ?',
			'AND',
			'master_poli_perawat.poli' => '= ?'
		), array(
			$parameter['perawat'],
			$parameter['poli']
		))
		->execute();

		if(count($check['response_data']) > 0) {
			$worker = self::$query->update('master_poli_perawat', array(
				'deleted_at' => NULL
			))
			->where(array(
				'master_poli_perawat.perawat' => '= ?',
				'AND',
				'master_poli_perawat.poli' => '= ?'
			), array(
				$parameter['perawat'],
				$parameter['poli']
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
						$check['response_data'][0]['id'],
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

	private function get_poli_tindakan_back_end($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$columnTarget = array(
			'id',
			'uid_poli',
			'uid_tindakan',
			'created_at',
			'updated_at'
		);

		$columnTargetSetter = array(
			'id',
			'nama_tindakan'
		);

		if(isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
			$paramData = array(
				'master_poli_tindakan.deleted_at' => 'IS NULL',
				'AND',
				'master_poli_tindakan.uid_poli' => '= ?',
				'AND',
				'master_tindakan.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
			);

			$paramValue = array($parameter['poli']);
		} else {
			$paramData = array(
				'master_poli_tindakan.deleted_at' => 'IS NULL',
				'AND',
				'master_poli_tindakan.uid_poli' => '= ?'
			);

			$paramValue = array($parameter['poli']);
		}


		if($parameter['length'] < 0) {
			$data = self::$query->select('master_poli_tindakan', $columnTarget)
                ->order(array(
                    $columnTargetSetter[$parameter['order'][0]['column']] => $parameter['order'][0]['dir']
                ))
                ->where($paramData, $paramValue)
			    ->execute();
		} else {
            $data = self::$query->select('master_poli_tindakan', $columnTarget)
                ->where($paramData, $paramValue)
                ->order(array(
                    $columnTargetSetter[$parameter['order'][0]['column']] => $parameter['order'][0]['dir']
                ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
		}

		$data['response_draw'] = $parameter['draw'];

		$autonum = $parameter['start'] + 1;
		$Tindakan = new Tindakan(self::$pdo);
		foreach ($data['response_data'] as $key => $value) {
		    $TindakanDetail = $Tindakan->get_tindakan_detail($value['uid_tindakan'])['response_data'][0];
            $data['response_data'][$key]['nama_tindakan'] = $TindakanDetail['nama'];
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		$dataTotal = self::$query->select('master_poli_tindakan', $columnTarget)	
		->where($paramData, $paramValue)
		->execute();

		$data['recordsTotal'] = count($dataTotal['response_data']);
		$data['recordsFiltered'] = count($dataTotal['response_data']);
		$data['length'] = intval($parameter['length']);
		$data['start'] = intval($parameter['start']);
		$data['sort'] = $parameter;

		return $data;
	}

	public function get_poli_dokter_back_end($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$columnTarget = array(
			'id',
			'poli',
			'dokter',
			'created_at',
			'updated_at'
		);

		$columnTargetSetter = array(
			'id',
			'nama_dokter'
		);

		if(isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
			$paramData = array(
				'master_poli_dokter.deleted_at' => 'IS NULL',
				'AND',
				'master_poli_dokter.poli' => '= ?',
				'AND',
				'pegawai.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
			);

			$paramValue = array($parameter['poli']);
		} else {
			$paramData = array(
				'master_poli_dokter.deleted_at' => 'IS NULL',
				'AND',
				'master_poli_dokter.poli' => '= ?'
			);

			$paramValue = array($parameter['poli']);
		}


		if($parameter['length'] < 0) {
			$data = self::$query->select('master_poli_dokter', $columnTarget)
			->join('pegawai', array(
				'nama as nama_dokter'
			))
			->on(array(
				array(
					'master_poli_dokter.dokter' => 'pegawai.uid'
				)
			))
			->where($paramData, $paramValue)
			->order(array(
				$columnTargetSetter[$parameter['order'][0]['column']] => $parameter['order'][0]['dir']
			))
			->execute();
		} else {
			$data = self::$query->select('master_poli_dokter', $columnTarget)	
			->join('pegawai', array(
				'nama as nama_dokter'
			))
			->on(array(
				array('master_poli_dokter.dokter', '=', 'pegawai.uid')
			))
			->where($paramData, $paramValue)
			->order(array(
				$columnTargetSetter[$parameter['order'][0]['column']] => $parameter['order'][0]['dir']
			))
			->offset(intval($parameter['start']))
			->limit(intval($parameter['length']))
			->execute();
		}

		$data['response_draw'] = $parameter['draw'];

		$autonum = $parameter['start'] + 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		$dataTotal = self::$query->select('master_poli_dokter', $columnTarget)	
		->where(array(
			'master_poli_dokter.deleted_at' => 'IS NULL',
			'AND',
			'master_poli_dokter.poli' => '= ?'
		), array(
			$parameter['poli']
		))
		->execute();

		$data['recordsTotal'] = count($dataTotal['response_data']);
		$data['recordsFiltered'] = count($dataTotal['response_data']);
		$data['length'] = intval($parameter['length']);
		$data['start'] = intval($parameter['start']);
		$data['sort'] = $parameter;

		return $data;
	}

	private function get_poli_perawat_back_end($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$columnTarget = array(
			'id',
			'poli',
			'perawat',
			'created_at',
			'updated_at'
		);

		$columnTargetSetter = array(
			'id',
			'nama_perawat'
		);

		if(isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
			$paramData = array(
				'master_poli_perawat.deleted_at' => 'IS NULL',
				'AND',
				'master_poli_perawat.poli' => '= ?',
				'AND',
				'pegawai.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
			);

			$paramValue = array($parameter['poli']);
		} else {
			$paramData = array(
				'master_poli_perawat.deleted_at' => 'IS NULL',
				'AND',
				'master_poli_perawat.poli' => '= ?'
			);

			$paramValue = array($parameter['poli']);
		}


		if($parameter['length'] < 0) {
			$data = self::$query->select('master_poli_perawat', $columnTarget)
			->join('pegawai', array(
				'nama as nama_perawat'
			))
			->on(array(
				array(
					'master_poli_perawat.perawat' => 'pegawai.uid'
				)
			))
			->where($paramData, $paramValue)
			->order(array(
				$columnTargetSetter[$parameter['order'][0]['column']] => $parameter['order'][0]['dir']
			))
			->execute();
		} else {
			$data = self::$query->select('master_poli_perawat', $columnTarget)	
			->join('pegawai', array(
				'nama as nama_perawat'
			))
			->on(array(
				array('master_poli_perawat.perawat', '=', 'pegawai.uid')
			))
			->where($paramData, $paramValue)
			->order(array(
				$columnTargetSetter[$parameter['order'][0]['column']] => $parameter['order'][0]['dir']
			))
			->offset(intval($parameter['start']))
			->limit(intval($parameter['length']))
			->execute();
		}

		$data['response_draw'] = $parameter['draw'];

		$autonum = $parameter['start'] + 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		$dataTotal = self::$query->select('master_poli_perawat', $columnTarget)	
		->where(array(
			'master_poli_perawat.deleted_at' => 'IS NULL',
			'AND',
			'master_poli_perawat.poli' => '= ?'
		), array(
			$parameter['poli']
		))
		->execute();

		$data['recordsTotal'] = count($dataTotal['response_data']);
		$data['recordsFiltered'] = count($dataTotal['response_data']);
		$data['length'] = intval($parameter['length']);
		$data['start'] = intval($parameter['start']);
		$data['sort'] = $parameter;

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

    public function get_poli_by_perawat($parameter) {
        $CheckPoli = self::$query->select('master_poli_perawat', array(
            'perawat',
            'poli'
        ))
            ->where(array(
                'deleted_at' => 'IS NULL',
                'AND',
                'perawat' => '= ?'
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
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
		
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

		if(count($readPerawat['response_data']) > 0) {
			$worker = self::$query->update('master_poli_perawat', array(
				'updated_at' => parent::format_date(),
				'deleted_at' => NULL
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
                        $readPerawat['response_data'][0]['id'],
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
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
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
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
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
				'deleted_at' => NULL
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

	private function tambah_poli($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table' => 'master_poli',
			'check' => $parameter['nama']
		));

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$poli = self::$query->insert('master_poli', array(
				'uid' => parent::gen_uuid(),
				'nama' => $parameter['nama'],
				'tindakan_konsultasi' => $parameter['tindakan_konsultasi'],
				'created_at' => parent::format_date(),
				'updated_at' => parent::format_date()
			))
			->returning('uid')
			->execute();
			return $poli;
		}
	}


	private function edit_poli($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);

		
		$old = self::get_poli_detail($parameter['uid']);
		
		$poli = self::$query->update('master_poli', array(
			'nama' => $parameter['nama'],
			'tindakan_konsultasi' => $parameter['tindakan_konsultasi'],
			'kode_bpjs' => $parameter['integrasi_bpjs_poli_kode'],
            'nama_bpjs' => $parameter['integrasi_bpjs_poli_nama'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_poli.deleted_at' => 'IS NULL',
			'AND',
			'master_poli.uid' => '= ?'
		), array(
			$parameter['uid']
		))
		->execute();

		/*if(isset($tindakanData) && $tindakanData != "") {
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
					
		}*/


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
						$parameter['uid'],
						$UserData['data']->uid,
						'master_poli',
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

	private function get_kunjungan_per_layanan($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		
		if(isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
			$paramData = array(
				'master_poli.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
				'AND',
				'master_poli.deleted_at' => 'IS NULL'
			);

			$paramValue = array();
		} else {
			$paramData = array(
				'master_poli.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
				'AND',
				'master_poli.deleted_at' => 'IS NULL'
			);

			$paramValue = array();
		}

		


		if($parameter['length'] < 0) {
			$data = self::$query->select('master_poli', array(
				'uid',
				'nama',
				'created_at',
				'updated_at'
			))
			/*->join('poli', array(
				'nama as nama_poli'
			))
			->on(array(
				array('antrian.departemen', '=', 'poli.uid')
			))*/
			->where($paramData, $paramValue)
			->execute();
		} else {
			$data = self::$query->select('master_poli', array(
				'uid',
				'nama',
				'created_at',
				'updated_at'
			))
			->where($paramData, $paramValue)
			->offset(intval($parameter['start']))
			->limit(intval($parameter['length']))
			->execute();
		}

		$data['response_draw'] = $parameter['draw'];


		//Get Count Pelayanan Total
		$antrianTotal = self::$query->select('antrian', array(
			'uid'
		))
		->where(array(
			'antrian.deleted_at' => 'IS NULL'
		))
		->execute();


		$autonum = intval($parameter['start']) + 1;
		foreach ($data['response_data'] as $key => $value) {
			//Get Count Pelayanan
			$antrian = self::$query->select('antrian', array(
				'uid'
			))
			->where(array(
				'antrian.deleted_at' => 'IS NULL',
				'AND',
				'antrian.departemen' => '= ?'
			), array(
				$value['uid']
			))
			->execute();

			$data['response_data'][$key]['jumlah_pelayanan'] = count($antrian['response_data']);
			$data['response_data'][$key]['jumlah_pelayanan_total'] = count($antrianTotal['response_data']);
			$data['response_data'][$key]['percentage'] = (count($antrian['response_data']) / count($antrianTotal['response_data']) * 100);
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		$dataTotal = self::$query->select('master_poli', array(
			'uid',
			'nama'
		))
		->where($paramData, $paramValue)
		->execute();

		$data['recordsTotal'] = count($dataTotal['response_data']);
		$data['recordsFiltered'] = count($dataTotal['response_data']);
		$data['length'] = intval($parameter['length']);
		$data['start'] = intval($parameter['start']);

		return $data;
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