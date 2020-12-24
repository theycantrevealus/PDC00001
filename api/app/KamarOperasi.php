<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;
use PondokCoder\Pegawai as Pegawai;
use PondokCoder\Pasien as Pasien;
use PondokCoder\Ruangan as Ruangan;

class KamarOperasi extends Utility {
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
				case 'jenis_operasi':
                    return self::get_jenis_operasi();
                break;

                case 'jadwal_operasi':
                    return self::get_jadwal_operasi();
                break;

                case 'get_pasien':
                    return self::get_pasien($parameter[2]);
                break;

                case 'get_jadwal_pasien_detail': 
                    return self::get_jadwal_pasien_detail($parameter[2]);
                break;

				default:
					# code...
					break;
			}
		} catch (QueryException $e) {
			return 'Error => '. $e;
		}
	}

	public function __POST__($parameter = array()){
		switch ($parameter['request']) {
            case 'add_jenis_operasi':
                return self::add_jenis_operasi($parameter);
            break;

            case 'edit_jenis_operasi':
                return self::edit_jenis_operasi($parameter);
            break;

			case 'add_jadwal_operasi':
				return self::add_jadwal_operasi($parameter);
            break;

            case 'edit_jadwal_operasi':
				return self::edit_jadwal_operasi($parameter);
            break;

			// case 'edit_penjamin':
			// 	return self::edit_penjamin($parameter);
			// 	break;

            case 'proses_jadwal_operasi':
                return self::proses_jadwal_operasi($parameter);
            break;

            case 'selesai_jadwal_operasi':
                return self::selesai_jadwal_operasi($parameter);
            break;

			default:
				# code...
				break;
		}
	}

	public function __DELETE__($parameter = array()){
		return self::delete($parameter);
	}


    /*======================= START GET FUNCTION ======================*/
    public static function get_jenis_operasi()
    {
        $data = self::$query
            ->select('kamar_operasi_jenis_operasi', 
                array(
                    'uid',
                    'nama',
                    'keterangan'
                )
            )
            ->where(
                array('kamar_operasi_jenis_operasi.deleted_at' => 'IS NULL')
            )
            ->execute();

        return $data;
    }

    public static function get_jenis_operasi_detail($parameter)
    {
        $data = self::$query
            ->select('kamar_operasi_jenis_operasi', 
                array(
                    'uid',
                    'nama',
                    'keterangan'
                )
            )
            ->where(
                array(
                    'kamar_operasi_jenis_operasi.deleted_at'    => 'IS NULL',
                    'AND',
                    'kamar_operasi_jenis_operasi.uid'           => '= ?'
                ),
                array(
                    $parameter
                )
            )
            ->execute();

        return $data;
    }

    public static function get_pasien($parameter)
    {
        $cek = self::$query
            ->select('kamar_operasi_jadwal', array(
                'pasien' 
            ))
            ->where(
                array(
                    'kamar_operasi_jadwal.deleted_at'           => "IS NULL",
                    'AND',
                    'kamar_operasi_jadwal.status_pelaksanaan'   => "!= 'D'"
                )
            )
            ->execute();

        if ($cek['response_result'] > 0)
        {
            $params = "(";
            $loop = 0;
            foreach ($cek['response_data'] as $key => $value) {
                //array_push($init_params, $value['pasien']);
                
                $params = $params . "'" . $value['pasien'] . "'";

                $loop++;
                if ($loop < $cek['response_result']){
                    $params = $params . ', ';
                } else {
                    $params = $params . ")";
                }
            }
            
            //$params = implode(', ', $init_params);

            $data = self::$query
                ->select('pasien', 
                    array(
                        'uid',
                        'no_rm',
                        'nik',
                        'nama'
                    )
                )
                ->where(
                    array(
                        'pasien.deleted_at' => 'IS NULL',
                        'AND',
                        'pasien.uid'    => 'NOT IN '. $params,
                        'AND',
                        'pasien.nik'    => 'LIKE \'%' . $parameter . '%\'',
                        'OR',
                        'pasien.no_rm'  => 'LIKE \'%' . $parameter . '%\'',
                        'OR',
                        'LOWER(pasien.nama)'   => 'LIKE LOWER(\'%' . $parameter . '%\')'
                    )
                )
                ->execute();

        } else {
            
            $data = self::$query
                ->select('pasien', 
                    array(
                        'uid',
                        'no_rm',
                        'nik',
                        'nama'
                    )
                )
                ->where(
                    array(
                        'pasien.deleted_at' => 'IS NULL',
                        'AND',
                        'pasien.nik'    => 'LIKE \'%' . $parameter . '%\'',
                        'OR',
                        'pasien.no_rm'  => 'LIKE \'%' . $parameter . '%\'',
                        'OR',
                        'LOWER(pasien.nama)'   => 'LIKE LOWER(\'%' . $parameter . '%\')'
                    )
                )
                ->execute();
        }

        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['id'] = $value['uid'];
            $data['response_data'][$key]['text'] = $value['nama'];
        }

        return $data;
    }

    
    public static function get_jadwal_operasi()
    {
        $data = self::$query
            ->select('kamar_operasi_jadwal',
                array(
                    'uid',
                    'pasien as uid_pasien',
                    'ruang_operasi as uid_ruang_operasi',
                    'tgl_operasi',
                    'jam_mulai',
                    'jam_selesai',
                    'jenis_operasi as uid_jenis_operasi',
                    'operasi',
                    'dokter as uid_dokter',
                    'status_pelaksanaan'
                )
            )
            ->where(
                array('kamar_operasi_jadwal.deleted_at' => 'IS NULL')
            )
            ->execute();
        
        $pegawai = new Pegawai(self::$pdo);
        $pasien = new Pasien(self::$pdo);
        $ruangan = new Ruangan(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['tgl_operasi'] = parent::dateToIndoSlash($value['tgl_operasi']);

            $jenis_operasi = self::get_jenis_operasi_detail($value['uid_jenis_operasi']);
            $data['response_data'][$key]['jenis_operasi'] = 
                ($jenis_operasi['response_result'] > 0) ? $jenis_operasi['response_data'][0]['nama'] : "-";

            $detail_dokter = $pegawai->get_detail($value['uid_dokter']);
            $data['response_data'][$key]['dokter'] = 
                ($detail_dokter['response_result'] > 0) ? $detail_dokter['response_data'][0]['nama'] : "-";

            $detail_pasien = $pasien->get_pasien_detail('pasien', $value['uid_pasien']);
            $data['response_data'][$key]['pasien'] = 
                ($detail_pasien['response_result'] > 0) ? $detail_pasien['response_data'][0]['nama'] : "-";

            $detail_ruangan = $ruangan->get_ruangan_detail('master_unit_ruangan', $value['uid_ruang_operasi']);
            $data['response_data'][$key]['ruangan'] = 
                ($detail_ruangan['response_result'] > 0) ? $detail_ruangan['response_data'][0]['nama'] : "-";
        }

        return $data;
    }

    public static function get_jadwal_operasi_detail($parameter)
    {
        $data = self::$query
            ->select('kamar_operasi_jadwal',
                array(
                    'uid',
                    'pasien',
                    'ruang_operasi',
                    'tgl_operasi',
                    'jam_mulai',
                    'jam_selesai',
                    'jenis_operasi',
                    'operasi',
                    'dokter',
                    'status_pelaksanaan'
                )
            )
            ->where(
                array(
                    'kamar_operasi_jadwal.deleted_at'   => 'IS NULL',
                    'AND',
                    'kamar_operasi_jadwal.uid'          =>  '= ?'
                ),
                array(
                    $parameter
                )
            )
            ->execute();
        
        return $data;
    }

    public static function get_jadwal_pasien_detail($parameter)    //uid_jadwal
    {
        $jadwal = self::get_jadwal_operasi_detail($parameter);
        $pasien = new Pasien(self::$pdo);

        foreach ($jadwal['response_data'] as $key => $value) {
            $data_pasien = $pasien->get_pasien_detail('pasien', $value['pasien']);

            $jadwal['response_data'][$key]['pasien'] = 
                ($data_pasien['response_result'] > 0) ? $data_pasien['response_data'][0] : "-";
        }


        return $jadwal;
    }

	/*======================= END GET FUNCTION ======================*/


    /*======================= START POST FUNCTION ======================*/
    public static function add_jenis_operasi($parameter)
    {
        $Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table'=>'kamar_operasi_jenis_operasi',
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
                ->insert('kamar_operasi_jenis_operasi', array(
                        'uid'       =>$uid,
                        'nama'      =>$parameter['nama'],
                        'keterangan'=>$parameter['keterangan'],
                        'created_at'=>parent::format_date(),
                        'updated_at'=>parent::format_date()
                        )
                )
                ->returning('uid')
                ->execute();

			if ($jenis['response_result'] > 0) {
				parent::log(array(
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
                        'kamar_operasi_jenis_operasi',
                        'I',
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class'=>__CLASS__
                ));
			}

			return $jenis;
		}

    }

    public static function edit_jenis_operasi($parameter)
    {
        $Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_jenis_operasi_detail($parameter['uid']);

		$jenis = self::$query
				->update('kamar_operasi_jenis_operasi', array(
                        'nama'      =>$parameter['nama'],
                        'keterangan'=>$parameter['keterangan'],
						'updated_at'=>parent::format_date()
					)
				)
				->where(array(
					'kamar_operasi_jenis_operasi.deleted_at' => 'IS NULL',
					'AND',
					'kamar_operasi_jenis_operasi.uid'        => '= ?'
					),
					array(
						$parameter['uid']
					)
				)
				->execute();

		if ($jenis['response_result'] > 0){
			unset($parameter['access_token']);

			parent::log(array(
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
                    'kamar_operasi_jenis_operasi',
                    'U',
                    json_encode($old['response_data'][0]),
                    json_encode($parameter),
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class'=>__CLASS__
            ));
		}

		return $jenis;
    }


    private static function add_jadwal_operasi($parameter)
    {
        $Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
        
        $uid = parent::gen_uuid();
        $jadwal = self::$query
            ->insert('kamar_operasi_jadwal', array(
                    'uid'               => $uid,
                    'pasien'            => $parameter['pasien'],
                    'ruang_operasi'     => $parameter['ruang_operasi'],
                    'tgl_operasi'       => $parameter['tgl_operasi'],
                    'jam_mulai'         => $parameter['jam_mulai'],
                    'jam_selesai'       => $parameter['jam_selesai'],
                    'jenis_operasi'     => $parameter['jenis_operasi'],
                    'operasi'           => $parameter['operasi'],
                    'dokter'            => $parameter['dokter'],
                    'status_pelaksanaan'=> 'N',
                    'created_at'        => parent::format_date(),
                    'updated_at'        => parent::format_date()
                    )
            )
            ->returning('uid')
            ->execute();

        if ($jadwal['response_result'] > 0) {
            parent::log(array(
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
                    'kamar_operasi_jadwal',
                    'I',
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class'=>__CLASS__
            ));
        }

        return $jadwal;
    }

    private static function edit_jadwal_operasi($parameter)
    {
        $Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_jadwal_operasi_detail($parameter['uid']);

		$jadwal = self::$query
            ->update('kamar_operasi_jadwal', array(
                    'ruang_operasi'     => $parameter['ruang_operasi'],
                    'tgl_operasi'       => $parameter['tgl_operasi'],
                    'jam_mulai'         => $parameter['jam_mulai'],
                    'jam_selesai'       => $parameter['jam_selesai'],
                    'jenis_operasi'     => $parameter['jenis_operasi'],
                    'operasi'           => $parameter['operasi'],
                    'dokter'            => $parameter['dokter'],
                    'updated_at'        => parent::format_date()
                )
            )
            ->where(array(
                'kamar_operasi_jadwal.deleted_at' => 'IS NULL',
                'AND',
                'kamar_operasi_jadwal.uid'        => '= ?'
                ),
                array(
                    $parameter['uid']
                )
            )
            ->execute();

		if ($jadwal['response_result'] > 0){
			unset($parameter['access_token']);

			parent::log(array(
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
                    'kamar_operasi_jadwal',
                    'U',
                    json_encode($old['response_data'][0]),
                    json_encode($parameter),
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class'=>__CLASS__
            ));
		}

		return $jadwal;
    }


    private static function proses_jadwal_operasi($parameter)
    {   
		$Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);
        
        $old = self::get_jadwal_operasi_detail($parameter['uid']);

        $proses = self::$query
            ->update('kamar_operasi_jadwal', array(
                'status_pelaksanaan' => 'P',
                'updated_at'         => parent::format_date() 
            ))
            ->where(
                array(
                    'kamar_operasi_jadwal.uid' => '= ?',
                    'AND',
                    'kamar_operasi_jadwal.deleted_at' => 'IS NULL'
                ), array(
                    $parameter['uid']
                )
            )
            ->execute();
        
        
        if ($proses['response_result'] > 0){
            unset($parameter['access_token']);

            parent::log(array(
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
                    'kamar_operasi_jadwal',
                    'U',
                    json_encode($old['response_data'][0]),
                    json_encode($parameter),
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class'=>__CLASS__
            ));
        }
        
        return $proses;
    }

    private static function selesai_jadwal_operasi($parameter)  //uid_jadwal
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);
        
        $old = self::get_jadwal_operasi_detail($parameter['uid']);

        $selesai = self::$query
            ->update('kamar_operasi_jadwal', array(
                'status_pelaksanaan' => 'D',
                'updated_at'         => parent::format_date() 
            ))
            ->where(
                array(
                    'kamar_operasi_jadwal.uid' => '= ?',
                    'AND',
                    'kamar_operasi_jadwal.deleted_at' => 'IS NULL'
                ), array(
                    $parameter['uid']
                )
            )
            ->execute();
        
        
        if ($proses['response_result'] > 0){
            unset($parameter['access_token']);

            parent::log(array(
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
                    'kamar_operasi_jadwal',
                    'U',
                    json_encode($old['response_data'][0]),
                    json_encode($parameter),
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class'=>__CLASS__
            ));
        }
        
        return $selesai;
    }


    private static function delete($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$delete = self::$query
			->delete($parameter[6])
			->where(array(
					$parameter[6] . '.uid' => '= ?'
				), array(
					$parameter[7]	
				)
			)
			->execute();

		if ($delete['response_result'] > 0){
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
						$parameter[6],
						$UserData['data']->uid,
						$parameter[7],
						'D',
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class'=>__CLASS__
				)
			);
		}

		return $delete;
	}
     
	/*======================= END POST FUNCTION ======================*/

	private static function duplicate_check($parameter) {
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