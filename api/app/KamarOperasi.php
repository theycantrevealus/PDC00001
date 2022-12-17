<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;
use PondokCoder\Pegawai as Pegawai;
use PondokCoder\Pasien as Pasien;
use PondokCoder\Invoice as Invoice;
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

                case 'get_paket_detail':
                    return self::get_paket_detail($parameter[2]);
                    break;

                case 'get_paket_list_name':
                    return self::get_paket_list_name($parameter);
                    break;

                case 'asesmen_operasi':
                    return self::get_asesmen($parameter);
                    break;

                case 'get_asesmen_detail':
                    return self::get_asesmen_detail($parameter[2]);
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

            case 'add_laporan_bedah':
                return self::add_laporan_bedah($parameter);
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

            case 'tambah_asesmen':
                return self::tambah_asesmen($parameter);
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

            case 'paket_obat_list':
                return self::paket_obat_list($parameter);
                break;

            case 'tambah_paket':
                return self::tambah_paket($parameter);
                break;

            case 'edit_paket':
                return self::edit_paket($parameter);
                break;

			default:
				# code...
				break;
		}
	}

	public function __DELETE__($parameter = array()){
		return self::delete($parameter);
	}

    private function get_paket_list_name($parameter) {
        $data = self::$query->select('kamar_operasi_paket_obat', array(
            'uid',
            'nama',
            'remark',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'kamar_operasi_paket_obat.deleted_at' => 'IS NULL'
            ), array())
            ->execute();
        foreach ($data['response_data'] as $key => $value) {
            //$data['response_data'][$key]['detail'] = self::get_varian_obat($value['uid'])['response_data'];
        }

        return $data;
    }

    private function get_paket_detail($parameter) {
        $data = self::$query->select('kamar_operasi_paket_obat', array(
            'uid',
            'nama',
            'remark',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'kamar_operasi_paket_obat.deleted_at' => 'IS NULL',
                'AND',
                'kamar_operasi_paket_obat.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        $Inventori = new Inventori(self::$pdo);

        foreach ($data['response_data'] as $key => $value) {
            $Detail = self::get_varian_obat($value['uid'])['response_data'];


            foreach ($Detail as $DKey => $DValue) {
                $Harga = 0;
                //Check Depo OK
                $TotalStock = 0;
                $InventoriStockPopulator = $Inventori->get_item_batch($DValue['obat']['uid']);
                if (count($InventoriStockPopulator['response_data']) > 0) {
                    foreach ($InventoriStockPopulator['response_data'] as $TotalKey => $TotalValue) {
                        if($TotalValue['gudang']['uid'] === __GUDANG_DEPO_OK__) {
                            $TotalStock += floatval($TotalValue['stok_terkini']);
                            $Harga += floatval($TotalValue['harga']);
                        }
                    }
                    $Detail[$DKey]['harga'] = $Harga;
                    $Detail[$DKey]['stok'] = $TotalStock;
                } else {
                    $Detail[$DKey]['stok'] = 0;
                }
            }

            $data['response_data'][$key]['detail'] = $Detail;
        }

        return $data;
    }

    private function tambah_paket($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $uid = parent::gen_uuid();
        $worker = self::$query->insert('kamar_operasi_paket_obat', array(
            'uid' => $uid,
            'nama' => $parameter['nama'],
            'remark' => $parameter['remark'],
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->execute();

        if($worker['response_result'] > 0) {
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
                    'kamar_operasi_paket_obat',
                    'I',
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class'=>__CLASS__
            ));

            //Detail
            foreach ($parameter['item'] as $key => $value) {
                $detail = self::$query->insert('kamar_operasi_paket_obat_detail', array(
                    'paket' => $uid,
                    'obat' => $value['obat'],
                    'qty' => floatval($value['qty']),
                    'remark' => $value['remark'],
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->returning('id')
                    ->execute();
                if($detail['response_result'] > 0) {
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
                            $detail['response_unique'],
                            $UserData['data']->uid,
                            'kamar_operasi_paket_obat_detail',
                            'I',
                            parent::format_date(),
                            'N',
                            $UserData['data']->log_id
                        ),
                        'class'=>__CLASS__
                    ));
                }
            }
        }

        return $worker;
    }


    private function edit_paket($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $old = self::get_paket_detail($parameter['uid']);

        $worker = self::$query->update('kamar_operasi_paket_obat', array(
            'nama' => $parameter['nama'],
            'remark' => $parameter['remark'],
            'updated_at' => parent::format_date()
        ))
            ->where(array(
                'kamar_operasi_paket_obat.uid' => '= ?',
                'AND',
                'kamar_operasi_paket_obat.deleted_at' => 'IS NULL'
            ), array(
                $parameter['uid']
            ))
            ->execute();

        if($worker['response_result'] > 0) {
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
                    'kamar_operasi_paket_obat',
                    'U',
                    json_encode($old['response_data'][0]),
                    json_encode($parameter),
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class'=>__CLASS__
            ));

            $HarDelete = self::$query->hard_delete('kamar_operasi_paket_obat_detail')
                ->where(array(
                    'kamar_operasi_paket_obat_detail.paket' => '= ?'
                ), array(
                    $parameter['uid']
                ))
                ->execute();

            //Detail
            foreach ($parameter['item'] as $key => $value) {
                $detail = self::$query->insert('kamar_operasi_paket_obat_detail', array(
                    'paket' => $parameter['uid'],
                    'obat' => $value['obat'],
                    'qty' => floatval($value['qty']),
                    'remark' => $value['remark'],
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->returning('id')
                    ->execute();
                if($detail['response_result'] > 0) {
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
                            $detail['response_unique'],
                            $UserData['data']->uid,
                            'kamar_operasi_paket_obat_detail',
                            'I',
                            parent::format_date(),
                            'N',
                            $UserData['data']->log_id
                        ),
                        'class'=>__CLASS__
                    ));
                }
            }
        }

        return $worker;
    }

    private function get_varian_obat($parameter) {
        $Inventori = new Inventori(self::$pdo);
        $data = self::$query->select('kamar_operasi_paket_obat_detail', array(
            'id', 'paket', 'obat', 'qty', 'remark'
        ))
            ->where(array(
                'kamar_operasi_paket_obat_detail.deleted_at' => 'IS NULL',
                'AND',
                'kamar_operasi_paket_obat_detail.paket' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['obat'] = $Inventori->get_item_detail($value['obat'])['response_data'][0];
        }
        return $data;
    }

    private function paket_obat_list($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'kamar_operasi_paket_obat.deleted_at' => 'IS NULL',
                'AND',
                'kamar_operasi_paket_obat.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array();
        } else {
            $paramData = array(
                'kamar_operasi_paket_obat.deleted_at' => 'IS NULL'
            );

            $paramValue = array();
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('kamar_operasi_paket_obat', array(
                'uid',
                'nama',
                'remark',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('kamar_operasi_paket_obat', array(
                'uid',
                'nama',
                'remark',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['detail'] = self::get_varian_obat($value['uid'])['response_data'];
            $autonum++;
        }

        $itemTotal = self::$query->select('kamar_operasi_paket_obat', array(
            'uid'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
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

            $data = self::$query->select('kunjungan', array(
                'uid as uid_kunjungan'
            ))
                ->join('antrian', array(
                    'uid as uid_antrian',
                    'pasien'
                ))
                ->join('pasien', array(
                    'uid',
                    'no_rm',
                    'nik',
                    'nama'
                ))
                ->on(array(
                    array('antrian.kunjungan', '=', 'kunjungan.uid'),
                    array('antrian.pasien', '=', 'pasien.uid')
                ))
                ->where(array(
                    'kunjungan.waktu_keluar' => 'IS NULL',
                    'AND',
                    'antrian.waktu_keluar' => 'IS NULL',
                    'AND',
                    '(pasien.deleted_at' => 'IS NULL',
                    'AND',
                    'pasien.uid'    => 'NOT IN '. $params,
                    'AND',
                    'pasien.nik'    => 'LIKE \'%' . $parameter . '%\'',
                    'OR',
                    'pasien.no_rm'  => 'LIKE \'%' . $parameter . '%\'',
                    'OR',
                    'LOWER(pasien.nama)'   => 'LIKE LOWER(\'%' . $parameter . '%\'))'
                ), array())
                ->execute();

            /*$data = self::$query
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
                ->execute();*/

        } else {

            $data = self::$query->select('kunjungan', array(
                'uid as uid_kunjungan'
            ))
                ->join('antrian', array(
                    'uid as uid_antrian',
                    'pasien'
                ))
                ->join('pasien', array(
                    'uid',
                    'no_rm',
                    'nik',
                    'nama'
                ))
                ->on(array(
                    array('antrian.kunjungan', '=', 'kunjungan.uid'),
                    array('antrian.pasien', '=', 'pasien.uid')
                ))
                ->where(array(
                    'kunjungan.waktu_keluar' => 'IS NULL',
                    'AND',
                    'antrian.waktu_keluar' => 'IS NULL',
                    'AND',
                    '(pasien.deleted_at' => 'IS NULL',
                    'AND',
                    'pasien.nik'    => 'LIKE \'%' . $parameter . '%\'',
                    'OR',
                    'pasien.no_rm'  => 'LIKE \'%' . $parameter . '%\'',
                    'OR',
                    'LOWER(pasien.nama)'   => 'LIKE LOWER(\'%' . $parameter . '%\'))'
                ), array())
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
                    'kunjungan',
                    'penjamin',
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
            $data['response_data'][$key]['kunjungan'] = $value['kunjungan'];
            $data['response_data'][$key]['penjamin'] = $value['penjamin'];

            //CHECK Asesmen 
            $asesmen = self::$query
            ->select('asesmen_medis_operasi',
                array(
                    'uid',
                )
            )
            ->where(
                array('asesmen_medis_operasi.jadwal' => '= ?'),
                array($value['uid'])
            )
            ->execute();

            $data['response_data'][$key]['asesmen'] = $asesmen['response_data'][0];

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

            $data['response_data'][$key]['tgl_operasi_parsed'] = date('d F Y', strtotime($value['tgl_operasi']));
        }

        return $data;
    }

    public static function get_jadwal_operasi_detail($parameter) {
        $Inventori = new Inventori(self::$pdo);
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
                    'paket_obat',
                    'penjamin',
                    'kunjungan',
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

        $paketSelected = self::$query->select('kamar_operasi_obat', array(
            'obat', 'batch', 'qty_rencana', 'remark'
        ))
            ->where(array(
                'kamar_operasi_obat.operasi' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();

        foreach ($paketSelected['response_data'] as $pktK => $pktV) {
            $paketSelected['response_data'][$pktK]['obat'] = $Inventori->get_item_detail($pktV['obat'])['response_data'][0];
        }

        $data['response_data'][0]['paket'] = $paketSelected['response_data'];

        return $data;
    }

    public static function get_jadwal_pasien_detail($parameter)    //uid_jadwal
    {
        $jadwal = self::get_jadwal_operasi_detail($parameter);
        $pasien = new Pasien(self::$pdo);
        $pegawai = new Pegawai(self::$pdo);
        $ruangan = new Ruangan(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);

        foreach ($jadwal['response_data'] as $key => $value) {
            $data_pasien = $pasien->get_pasien_info('pasien', $value['pasien']);
            $jadwal['response_data'][$key]['dokter_detail'] = $pegawai->get_detail($value['dokter'])['response_data'][0];
            $jadwal['response_data'][$key]['jenis_operasi_detail'] = self::get_jenis_operasi_detail($value['jenis_operasi'])['response_data'][0];
            $jadwal['response_data'][$key]['ruang_operasi_detail'] = $ruangan->get_ruangan_detail('master_unit_ruangan', $value['ruang_operasi'])['response_data'][0];
            $jadwal['response_data'][$key]['tgl_operasi_parsed'] = date('d F Y', strtotime($value['tgl_operasi']));
            $jadwal['response_data'][$key]['pasien'] = ($data_pasien['response_result'] > 0) ? $data_pasien['response_data'][0] : "-";
            $jadwal['response_data'][$key]['penjamin'] =  $Penjamin->get_penjamin_detail($value['penjamin'])['response_data'][0]['nama'];

        }


        return $jadwal;
    }

	/*======================= END GET FUNCTION ======================*/


    /*======================= START POST FUNCTION ======================*/
    public static function add_jenis_operasi($parameter)
    {
        $Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);

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

    public static function edit_jenis_operasi($parameter) {
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
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
        
        $uid = parent::gen_uuid();
        $jadwal = self::$query
            ->insert('kamar_operasi_jadwal', array(
                    'uid'               => $uid,
                    'pasien'            => $parameter['pasien'],
                    'penjamin'          => $parameter['penjamin'],
                    'kunjungan'          => $parameter['kunjungan'],
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
		$UserData = $Authorization->readBearerToken($parameter['access_token']);

		$old = self::get_jadwal_operasi_detail($parameter['uid']);
        $Inventori = new Inventori(self::$pdo);

        if(!isset($parameter['paket_obat'])) {
            $jadwal = self::$query
                ->update('kamar_operasi_jadwal', array(
                        'ruang_operasi'     => $parameter['ruang_operasi'],
                        'tgl_operasi'       => $parameter['tgl_operasi'],
                        'jam_mulai'         => $parameter['jam_mulai'],
                        'jam_selesai'       => $parameter['jam_selesai'],
                        'jenis_operasi'     => $parameter['jenis_operasi'],
                        'operasi'           => $parameter['operasi'],
                        'dokter'            => $parameter['dokter'],
                        'penjamin'          => $parameter['penjamin'],
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
        } else {
            $jadwal = self::$query
                ->update('kamar_operasi_jadwal', array(
                        'ruang_operasi'     => $parameter['ruang_operasi'],
                        'tgl_operasi'       => $parameter['tgl_operasi'],
                        'jam_mulai'         => $parameter['jam_mulai'],
                        'jam_selesai'       => $parameter['jam_selesai'],
                        'jenis_operasi'     => $parameter['jenis_operasi'],
                        'operasi'           => $parameter['operasi'],
                        'dokter'            => $parameter['dokter'],
                        'penjamin'          => $parameter['penjamin'],
                        'updated_at'        => parent::format_date(),
                        'paket_obat'        => $parameter['paket_obat']
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
        }

        $detailObatResponse = array();
        $usedBatch = array();
        $rawBatch = array();

		if ($jadwal['response_result'] > 0) {
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

            //Reset Old Data
            $resetObat = self::$query->hard_delete('kamar_operasi_obat')
                ->where(array(
                    'kamar_operasi_obat.operasi' => '= ?',
                    'AND',
                    'kamar_operasi_obat.deleted_at' => 'IS NULL'
                ), array(
                    $parameter['uid']
                ))
                ->execute();

            //Paket Obat

            foreach ($parameter['item'] as $itemKey => $itemValue) {
                //Get depo ok batch
                $kebutuhanKekurangan = floatval($itemValue['qty']);
                $batchData = $Inventori->get_item_batch($itemValue['obat']);
                array_push($rawBatch, $batchData['response_data']);
                foreach ($batchData['response_data'] as $bKey => $bValue) {

                    if($bValue['gudang']['uid'] === __GUDANG_DEPO_OK__) {
                        if($kebutuhanKekurangan >= $bValue['stok_terkini']) {
                            array_push($usedBatch, array(
                                'batch' => $bValue['batch'],
                                'gudang' => $bValue['gudang']['uid'],
                                'barang' => $itemValue['obat'],
                                'remark' => $itemValue['remark'],
                                'qty' => floatval($bValue['stok_terkini'])
                            ));
                            $kebutuhanKekurangan -= floatval($bValue['stok_terkini']);
                        } else {
                            array_push($usedBatch, array(
                                'batch' => $bValue['batch'],
                                'gudang' => $bValue['gudang']['uid'],
                                'barang' => $itemValue['obat'],
                                'remark' => $itemValue['remark'],
                                'qty' => $kebutuhanKekurangan
                            ));
                            $kebutuhanKekurangan = 0;
                        }
                    }
                }
            }

            foreach ($usedBatch as $uBKey => $uBValue) {
                $obat = self::$query->insert('kamar_operasi_obat', array(
                    'operasi' => $parameter['uid'],
                    'obat' => $uBValue['barang'],
                    'batch' => $uBValue['batch'],
                    'remark' => $uBValue['remark'],
                    'qty_rencana' => $uBValue['qty'],
                    'qty_terpakai' => $uBValue['qty'],
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();

                array_push($detailObatResponse, $obat);
            }
		}

        $jadwal['batch'] = $usedBatch;
        $jadwal['raw'] = $rawBatch;
        $jadwal['requested'] = $parameter['item'];
        $jadwal['detail'] = $detailObatResponse;

		return $jadwal;
    }

    private static function proses_jadwal_operasi($parameter) {
		$Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        
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

    private static function selesai_jadwal_operasi($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $Inventori = new Inventori(self::$pdo);
        $SInvoice = new Invoice(self::$pdo);
        $old = self::get_jadwal_operasi_detail($parameter['uid']);
        $InvoiceCheck = self::$query->select('invoice', array( //Check Invoice Master jika sudah ada
            'uid'
        ))
            ->where(array(
                'invoice.deleted_at' => 'IS NULL',
                'AND',
                'invoice.kunjungan' => '= ?'
            ), array(
                $old['response_data'][0]['kunjungan'],
            ))
            ->execute();

        if (count($InvoiceCheck['response_data']) > 0) { //Sudah Ada Invoice Master

            $InvoiceUID = $InvoiceCheck['response_data'][0]['uid'];

        } else { //Belum ada Invoice Master

            $Invoice = $SInvoice->create_invoice(array(
                'access_token' => $parameter['access_token'],
                'kunjungan' => $old['response_data'][0]['kunjungan'],
                'pasien' => $old['response_data'][0]['pasien'],
                'keterangan' => 'Kunjungan Operasi'
            ));

            $InvoiceUID = $Invoice['response_unique'];
        }
        


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
        
        
        if ($selesai['response_result'] > 0){
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

            $rawBatch = array();
            $usedBatch = array();

            foreach ($parameter['item'] as $itemKey=> $itemValue) {
                //Get depo ok batch
                $kebutuhanKekurangan = floatval($itemValue['qty']);
                $batchData = $Inventori->get_item_batch($itemValue['obat']);
                array_push($rawBatch, $batchData['response_data']);
                foreach ($batchData['response_data'] as $bKey => $bValue) {

                    if($bValue['gudang']['uid'] === __GUDANG_DEPO_OK__) {
                        if($kebutuhanKekurangan >= $bValue['stok_terkini']) {
                            array_push($usedBatch, array(
                                'batch' => $bValue['batch'],
                                'gudang' => $bValue['gudang']['uid'],
                                'barang' => $itemValue['obat'],
                                'remark' => $itemValue['remark'],
                                'qty' => floatval($bValue['stok_terkini'])
                            ));
                            $kebutuhanKekurangan -= floatval($bValue['stok_terkini']);
                        } else {
                            array_push($usedBatch, array(
                                'batch' => $bValue['batch'],
                                'gudang' => $bValue['gudang']['uid'],
                                'barang' => $itemValue['obat'],
                                'remark' => $itemValue['remark'],
                                'qty' => $kebutuhanKekurangan
                            ));
                            $kebutuhanKekurangan = 0;
                        }
                    }
                }
            }

            foreach ($usedBatch as $uBKey => $uBValue) {
                $obat = self::$query->insert('kamar_operasi_obat_aktual', array(
                    'operasi' => $parameter['uid'],
                    'obat' => $uBValue['barang'],
                    'batch' => $uBValue['batch'],
                    'remark' => $uBValue['remark'],
                    'qty' => $uBValue['qty'],
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
                
                if($obat['response_result'] > 0) {
                    $inventoriStok = self::$query->select('inventori_stok', array(
                        'stok_terkini'
                    ))
                        ->where(array(
                            'inventori_stok.gudang' => '= ?',
                            'AND',
                            'inventori_stok.barang' => '= ?',
                            'AND',
                            'inventori_stok.batch' => '= ?'
                        ), array(
                            __GUDANG_DEPO_OK__,
                            $uBValue['barang'],
                            $uBValue['batch']
                        ))
                        ->execute();

                    $StokAffect = self::$query->update('inventori_stok', array(
                        'stok_terkini' => (floatval($inventoriStok['response_data'][0]['stok_terkini']) - floatval($uBValue['qty']))
                    ))
                        ->where(array(
                            'inventori_stok.gudang' => '= ?',
                            'AND',
                            'inventori_stok.barang' => '= ?',
                            'AND',
                            'inventori_stok.batch' => '= ?'
                        ), array(
                            __GUDANG_DEPO_OK__,
                            $uBValue['barang'],
                            $uBValue['batch']
                        ))
                        ->execute();

                    if($StokAffect['response_result'] > 0) {
                        $stokLog = self::$query->insert('inventori_stok_log', array(
                            'barang' => $uBValue['barang'],
                            'batch' => $uBValue['batch'],
                            'gudang' => __GUDANG_DEPO_OK__,
                            'masuk' => 0,
                            'keluar' => floatval($uBValue['qty']),
                            'saldo' => (floatval($inventoriStok['response_data'][0]['stok_terkini']) - floatval($uBValue['qty'])),
                            'type' => __STATUS_BARANG_KELUAR__,
                            'logged_at' => parent::format_date(),
                            'jenis_transaksi' => 'kamar_operasi_jadwal',
                            'uid_foreign' => $parameter['uid'],
                            'keterangan' => 'Penggunaan Obat/BHP Operasi'
                        ))
                            ->execute();


                        //Charge Biaya Obat Operasi
                        $BatchInfo = $Inventori->get_batch_detail($uBValue['batch'])['response_data'][0];

                        $Profit = self::$query->select('master_inv_harga', array(
                            'id',
                            'barang',
                            'penjamin',
                            'profit',
                            'profit_type'
                        ))
                            ->where(array(
                                'master_inv_harga.deleted_at' => 'IS NULL',
                                'AND',
                                'master_inv_harga.barang' => '= ?',
                                'AND',
                                'master_inv_harga.penjamin' => '= ?'
                            ), array(
                                $uBValue['barang'], $parameter['penjamin']
                            ))
                            ->execute();
                        if(count($Profit['response_data']) > 0) {
                            if($Profit['response_data'][0]['profit_type'] === 'P') {
                                $finalHarga = floatval($BatchInfo['harga']) + (floatval($BatchInfo['harga']) * floatval($Profit['response_data'][0]['profit']) / 100);
                            } else if($Profit['response_data'][0]['profit_type'] === 'P') {
                                $finalHarga = floatval($BatchInfo['harga']) + floatval($Profit['response_data'][0]['profit']);
                            } else {
                                $finalHarga = floatval($BatchInfo['harga']);
                            }
                        } else {
                            $finalHarga = floatval($BatchInfo['harga']);
                        }

                        $Invoice = $SInvoice->append_invoice(array(
                            'invoice' => $InvoiceUID,
                            'item' => $uBValue['barang'],
                            'item_origin' => 'master_inv',
                            'qty' => floatval($uBValue['qty']),
                            'harga' => $finalHarga,
                            'subtotal' => (floatval($uBValue['qty']) * $finalHarga),
                            'status_bayar' => 'N',
                            'discount' => 0,
                            'discount_type' => 'N',
                            'pasien' => $old['response_data'][0]['pasien'],
                            'penjamin' => $old['response_data'][0]['penjamin'],
                            'billing_group' => 'obat',
                            'keterangan' => 'Obat/BHP Operasi',
                            'departemen' => __POLI_OPERASI__
                        ));

                    }
                }
            }

        }
        
        return $selesai;
    }


    private static function delete($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);

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

    private static function get_asesmen($parameter)
    {
        $data = self::$query
        ->select('asesmen_medis_operasi',
            array(
                'uid',
                'kunjungan',
                'pasien' ,
                'dokter',
                'jadwal',
                'operator',
                'asisten',
                'instrumen',
                'macam_pembedahan' ,
                'urgensi' ,
                'luka_operasi',
                'diagnosa_pra_bedah',
                'tindakan_bedah',
                'diagnosa_pasca_bedah',
                'ahli_bius',
                'cara_bius' ,
                'posisi_pasien',
                'no_implant' ,
                'mulai',
                'selesai',
                'lama_jam',
                'lama_menit' ,
                'ok' ,
                'laporan_pembedahan' ,
                'komplikasi' ,
                'perdarahan' ,
                'jaringan_patologi' ,
                'asal_jaringan',
                'operator_1' ,
                'ket_operator_1' ,
                'operator_2' ,
                'ket_operator_2',
                'dokter_anestesi',
                'ket_dokter_anestesi',
                'dokter_anak',
                'ket_dokter_anak',
                'penata_anestesi' ,
                'ket_penata_anestesi' ,
                'perawat_ok_1' ,
                'ket_perawat_ok_1' ,
                'perawat_ok_2' ,
                'ket_perawat_ok_2' ,
                'perawat_ok_3',
                'ket_perawat_ok_3' ,
                'perawat_ok_4' ,
                'ket_perawat_ok_4' ,
                'created_at',
                'updated_at',
            )
        )
        ->where(
            array('asesmen_medis_operasi.deleted_at' => 'IS NULL')
        )
        ->execute();
    
        $pegawai = new Pegawai(self::$pdo);
        $pasien = new Pasien(self::$pdo);
        $ruangan = new Ruangan(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['kunjungan'] = $value['kunjungan'];
            

            $data['response_data'][$key]['jadwal'] = self::get_jadwal_pasien_detail($value['jadwal'])['response_data'][0];

            $detail_dokter = $pegawai->get_detail($value['dokter']);
            $data['response_data'][$key]['dokter'] = 
                ($detail_dokter['response_result'] > 0) ? $detail_dokter['response_data'][0]['nama'] : "-";

            $detail_pasien = $pasien->get_pasien_detail('pasien', $value['pasien']);
            $data['response_data'][$key]['pasien'] = 
                ($detail_pasien['response_result'] > 0) ? $detail_pasien['response_data'][0]['nama'] : "-";

        }

        return $data;
    }

    private static function get_asesmen_detail($parameter)
    {
        $data = self::$query
        ->select('asesmen_medis_operasi',
            array(
                'uid',
                'kunjungan',
                'pasien' ,
                'dokter',
                'jadwal',
                'operator',
                'asisten',
                'instrumen',
                'macam_pembedahan' ,
                'urgensi' ,
                'luka_operasi',
                'diagnosa_pra_bedah',
                'tindakan_bedah',
                'diagnosa_pasca_bedah',
                'ahli_bius',
                'cara_bius' ,
                'posisi_pasien',
                'no_implant' ,
                'mulai',
                'selesai',
                'lama_jam',
                'lama_menit' ,
                'ok' ,
                'laporan_pembedahan' ,
                'komplikasi' ,
                'perdarahan' ,
                'jaringan_patologi' ,
                'asal_jaringan',
                'operator_1' ,
                'ket_operator_1' ,
                'operator_2' ,
                'ket_operator_2',
                'dokter_anestesi',
                'ket_dokter_anestesi',
                'dokter_anak',
                'ket_dokter_anak',
                'penata_anestesi' ,
                'ket_penata_anestesi' ,
                'perawat_ok_1' ,
                'ket_perawat_ok_1' ,
                'perawat_ok_2' ,
                'ket_perawat_ok_2' ,
                'perawat_ok_3',
                'ket_perawat_ok_3' ,
                'perawat_ok_4' ,
                'ket_perawat_ok_4' ,
                'created_at',
                'updated_at',
            )
        )
        ->where(
            array(
                'asesmen_medis_operasi.deleted_at' => 'IS NULL',
                'AND',
                'asesmen_medis_operasi.uid' => '= ?'
            ),
            array($parameter)
        )
        ->execute();
    
        $pegawai = new Pegawai(self::$pdo);
        $pasien = new Pasien(self::$pdo);
        $ruangan = new Ruangan(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['kunjungan'] = $value['kunjungan'];
            

            $data['response_data'][$key]['jadwal'] = self::get_jadwal_pasien_detail($value['jadwal'])['response_data'][0];

            $detail_dokter = $pegawai->get_detail($value['dokter']);
            $data['response_data'][$key]['dokter'] = 
                ($detail_dokter['response_result'] > 0) ? $detail_dokter['response_data'][0]['nama'] : "-";
            
            $detail_dokter_anestesi = $pegawai->get_detail($value['dokter_anestesi']);
            $data['response_data'][$key]['dokter_anestesi'] = 
                ($detail_dokter_anestesi['response_result'] > 0) ? $detail_dokter_anestesi['response_data'][0]['nama'] : "-";
            
            $detail_dokter_anak = $pegawai->get_detail($value['dokter_anak']);
            $data['response_data'][$key]['dokter_anak'] = 
                ($detail_dokter_anak['response_result'] > 0) ? $detail_dokter_anak['response_data'][0]['nama'] : "-";

            $perawat_ok_1 = $pegawai->get_detail($value['perawat_ok_1']);
            $data['response_data'][$key]['perawat_ok_1'] = ($perawat_ok_1['response_result'] > 0) ? $perawat_ok_1['response_data'][0]['nama'] : "-";
    
            $perawat_ok_2 = $pegawai->get_detail($value['perawat_ok_2']);
            $data['response_data'][$key]['perawat_ok_2'] = ($perawat_ok_2['response_result'] > 0) ? $perawat_ok_2['response_data'][0]['nama'] : "-";
    
            $perawat_ok_3 = $pegawai->get_detail($value['perawat_ok_3']);
            $data['response_data'][$key]['perawat_ok_3'] = ($perawat_ok_3['response_result'] > 0) ? $perawat_ok_3['response_data'][0]['nama'] : "-";
    
            $perawat_ok_4 = $pegawai->get_detail($value['perawat_ok_4']);
            $data['response_data'][$key]['perawat_ok_4'] = ($perawat_ok_4['response_result'] > 0) ? $perawat_ok_4['response_data'][0]['nama'] : "-";
    

            $detail_pasien = $pasien->get_pasien_detail('pasien', $value['pasien']);
            $data['response_data'][$key]['pasien'] = 
                ($detail_pasien['response_result'] > 0) ? $detail_pasien['response_data'][0] : "-";

        }

        return $data;
    }

    private static function add_laporan_bedah($parameter)
    {
        $Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
        
        $uid = parent::gen_uuid();
        $asesmen = self::$query->insert('asesmen_medis_operasi', array(
                    'uid' => $uid,
                    'kunjungan' => $parameter['kunjungan'],
                    'pasien' => $parameter['pasien'],
                    'dokter' => $parameter['dokter'],
                    'jadwal' => $parameter['jadwal'],
                    'operator'=> $parameter['operator'],
                    'asisten'=> $parameter['asisten'],
                    'instrumen' => $parameter['instrumen'],
                    'macam_pembedahan' => $parameter['macam_pembedahan'],
                    'urgensi'  => $parameter['urgensi'],
                    'luka_operasi'  => $parameter['luka_operasi'],
                    'diagnosa_pra_bedah' => $parameter['diagnosa_pra_bedah'],
                    'tindakan_bedah' => $parameter['tindakan_bedah'],
                    'diagnosa_pasca_bedah' =>$parameter['diagnosa_pasca_bedah'],
                    'ahli_bius' =>  $parameter['ahli_bius'],
                    'cara_bius' =>  $parameter['cara_bius'],
                    'posisi_pasien'  =>  $parameter['posisi_pasien'],
                    'no_implant'  =>  $parameter['no_implant'],
                    'mulai'  =>  $parameter['mulai'],
                    'selesai'  =>  $parameter['selesai'],
                    'lama_jam' =>  $parameter['lama_jam'],
                    'lama_menit' =>  $parameter['lama_menit'],
                    'ok' =>  $parameter['ok'],
                    'laporan_pembedahan' =>  $parameter['laporan_pembedahan'],
                    'komplikasi' =>  $parameter['komplikasi'],
                    'perdarahan' => $parameter['perdarahan'],
                    'jaringan_patologi' =>  $parameter['jaringan_patologi'],
                    'asal_jaringan' =>  $parameter['asal_jaringan'],
                    'operator_1' =>  $parameter['operator_1'],
                    'ket_operator_1' =>  $parameter['ket_operator_1'],
                    'operator_2' =>  $parameter['operator_1'],
                    'ket_operator_2' =>  $parameter['ket_operator_2'],
                    'dokter_anestesi' =>  $parameter['dokter_anestesi'],
                    'ket_dokter_anestesi' =>  $parameter['ket_dokter_anestesi'],
                    'dokter_anak' =>  $parameter['dokter_anak'],
                    'ket_dokter_anak' =>  $parameter['ket_dokter_anak'],
                    'penata_anestesi' =>  $parameter['penata_anestesi'],
                    'ket_penata_anestesi' =>  $parameter['ket_penata_anestesi'],
                    'perawat_ok_1' =>  $parameter['perawat_ok_1'],
                    'ket_perawat_ok_1' =>  $parameter['ket_perawat_ok_1'],
                    'perawat_ok_2' =>  $parameter['perawat_ok_2'],
                    'ket_perawat_ok_2' =>  $parameter['ket_perawat_ok_2'],
                    'perawat_ok_3' =>  $parameter['perawat_ok_3'],
                    'ket_perawat_ok_3' =>  $parameter['ket_perawat_ok_3'],
                    'perawat_ok_4' =>  $parameter['perawat_ok_4'],
                    'ket_perawat_ok_4' =>  $parameter['ket_perawat_ok_4'],
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                    )
            )
            ->execute();


        if ($asesmen['response_result'] > 0) {
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
                    'asesmen_medis_operasi',
                    'I',
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class'=>__CLASS__
            ));
        }

        return $asesmen;
    }

    private function tambah_asesmen($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $parameter['poli'] = "77cc0eea-fd15-44be-b3b3-e2ebab39c21a";
        $Antrian = new Antrian(self::$pdo);
        $parameter['dataObj'] = array(
            'departemen' => $parameter['poli'],
            'pasien' => $parameter['pasien'],
            'penjamin' => $parameter['penjamin'],
            'prioritas' => 36,
            'dokter' => $parameter['dokter']
        );
        $AntrianProses = $Antrian->tambah_antrian('antrian', $parameter, $parameter['kunjungan']);

        return $AntrianProses;
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