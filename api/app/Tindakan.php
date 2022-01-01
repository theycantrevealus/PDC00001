<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Poli as Poli;
use PondokCoder\Authorization as Authorization;

class Tindakan extends Utility {
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
				case 'tindakan':
					return self::get_tindakan();
					break;

				case 'tindakan-detail':
					return self::get_tindakan_detail($parameter[2]);
					break;
				case 'kelas':
					return self::tindakan_kelas($parameter);
					break;
				case 'get-kelas':
					return self::get_kelas_tindakan();
					break;
				case 'get-harga-per-kelas':
					return self::get_harga_per_tindakan($parameter);
					break;
				case 'get-harga-tindakan':
					return self::get_harga_tindakan($parameter[2]);
					break;

				case 'rawat-jalan':
					return self::get_tindakan_rawat_jalan();
					break;

				case 'rawat-inap':
					return self::get_tindakan_rawat_inap();

				default:
					return self::get_tindakan();
					break;
			}
		} catch (QueryException $e) {
			return 'Error => '. $e;
		}
	}

	public function __POST__($parameter = array()){
		switch ($parameter['request']) {
			case 'tambah_tindakan_rawat_inap':
				return self::tambah_tindakan_rawat_inap($parameter);
				break;

			/*case 'tambah_tindakan_master':
				return self::tambah_master_tindakan($parameter);
				break;

			case 'edit_tindakan_master':
				return self::edit_master_tindakan($parameter);
				break;*/

			case 'edit_tindakan_rawat_inap':
				return self::edit_tindakan_rawat_inap($parameter);
				break;

			case 'tambah_tindakan_rawat_jalan':
				return self::tambah_tindakan_rawat_jalan($parameter);
				break;

			case 'edit_tindakan_rawat_jalan':
				return self::edit_tindakan_rawat_jalan($parameter);
				break;

			case 'tambah_tindakan':
				return self::tambah_tindakan($parameter);
				break;
			case 'edit_tindakan':
				return self::edit_tindakan($parameter);
				break;

			case 'update_position':
				return self::update_position($parameter);
				break;

			case 'update_tindakan_kelas_harga':
				return self::update_tindakan_kelas_harga($parameter);
				break;

			case 'tambah_master_tindakan':
				return self::tambah_master_tindakan($parameter);
				break;

            case 'get_tindakan_backend':
                return self::get_tindakan_backend($parameter);
                break;

            case 'tindakan_import_fetch':
                return self::tindakan_import_fetch($parameter);
                break;

            case 'proceed_import_tindakan':
                return self::proceed_import_tindakan($parameter);
                break;

            case 'get_harga_per_tindakan_backend':
                return self::get_harga_per_tindakan_backend($parameter);
                break;

			default:
				# code...
				break;
		}
	}

	public function __DELETE__($parameter = array()){ 
		return self::delete_tindakan($parameter);
	}

	private function tindakan_import_fetch($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        if (!empty($_FILES['csv_file']['name'])) {
            $unique_name = array();

            $file_data = fopen($_FILES['csv_file']['tmp_name'], 'r');
            $column = fgetcsv($file_data); //array_head
            $row_data = array();
            while ($row = fgetcsv($file_data)) {
                /*if (!in_array($row[0], $unique_name)) {
                    array_push($unique_name, $row[0]);
                    $column_builder = array();
                    foreach ($column as $key => $value) {
                        $column_builder[$value] = $row[$key];
                    }
                    array_push($row_data, $column_builder);
                }*/

                $column_builder = array();
                foreach ($column as $key => $value) {
                    $column_builder[$value] = $row[$key];
                }
                array_push($row_data, $column_builder);
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


    private function proceed_import_tindakan($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $duplicate_row = array();
        $termi_item = array();
        $non_active = array();
        $success_proceed = 0;
		$failed_proceed = array();
        $proceed_data = array();
        $Penjamin = new Penjamin(self::$pdo);

        //Reset Tindakan
        $reset_tindakan = self::$query->update('master_tindakan', array(
            'deleted_at' => parent::format_date()
        ))
            ->where(array(
                'master_tindakan.kelompok' => '= ?'
            ), array(
                'RJ'
            ))
            ->execute();

        //Reset Poli
        $reset_poli = self::$query->update('master_poli', array(
            'deleted_at' => parent::format_date()
        ))
            ->execute();

        //Reset Harga Tindakan Poli
        $reset_tindakan_poli = self::$query->update('master_tindakan_kelas_harga', array(
            'deleted_at' => parent::format_date()
        ))
            ->execute();

		//Reset Tindakan Poli
		$resetPoliTindakan = self::$query->hard_delete('master_poli_tindakan')
			->execute();

        foreach ($parameter['data_import'] as $key => $value) {
            $targettedJenis = '';
            $targettedTindakan = '';
            $targettedPoli = '';


            //Check Jenis
            $checkJenis = self::$query->select('master_tindakan_jenis', array(
                'uid'
            ))
                ->where(array(
                    'master_tindakan_jenis.nama' => '= ?'
                ), array(
                    ucwords(strtolower($value['jenis']))
                ))
                ->execute()
            ;
            if(count($checkJenis['response_data']) > 0) {
                $targettedJenis = $checkJenis['response_data'][0]['uid'];
                $proceed_jenis = self::$query->update('master_tindakan_jenis', array(
                    'updated_at' => parent::format_date(),
                    'deleted_at' => NULL
                ))
                    ->where(array(
                        'master_tindakan_jenis.uid' => '= ?'
                    ), array(
                        $targettedJenis
                    ))
                    ->execute();
            } else {
                if($value['jenis'] != '') {
                    $targettedJenis = parent::gen_uuid();
                    $proceed_jenis = self::$query->insert('master_tindakan_jenis', array(
                        'uid' => $targettedJenis,
                        'nama' => ucwords(strtolower($value['jenis'])),
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                }
            }


            //Check Tindakan
            $checkTindakan = self::$query->select('master_tindakan', array(
                'uid'
            ))
                ->where(array(
                    'master_tindakan.nama' => '= ?'
                ), array(
                    trim($value['tindakan'])
                ))
                ->execute();
            if(count($checkTindakan['response_data']) > 0) {
                $targettedTindakan = $checkTindakan['response_data'][0]['uid'];
                $proceed_tindakan = self::$query->update('master_tindakan', array(
                    'updated_at' => parent::format_date(),
                    'deleted_at' => NULL
                ))
                    ->where(array(
                        'master_tindakan.uid' => '= ?'
                    ), array(
                        $targettedTindakan
                    ))
                    ->execute();
            } else {
                if(!empty($value['tindakan'])) {
                    $targettedTindakan = parent::gen_uuid();
					if(!empty($targettedJenis)) {
						$proceed_tindakan = self::$query->insert('master_tindakan', array(
							'uid' => $targettedTindakan,
							'nama' => trim($value['tindakan']),
							'kelompok' => trim(strtoupper($value['kelompok'])),
							'jenis' => $targettedJenis,
							'created_at' => parent::format_date(),
							'updated_at' => parent::format_date()
						))
							->execute();	
					} else {
						$proceed_tindakan = self::$query->insert('master_tindakan', array(
							'uid' => $targettedTindakan,
							'nama' => trim($value['tindakan']),
							'kelompok' => trim(strtoupper($value['kelompok'])),
							'created_at' => parent::format_date(),
							'updated_at' => parent::format_date()
						))
							->execute();
					}
                }
            }

			if($proceed_tindakan['response_result'] > 0) {
				$success_proceed += 1;
			} else {
				array_push($failed_proceed, $proceed_tindakan);
			}




            //All Penjamin (1 Harga)
            $DataPenjamin = $Penjamin->get_penjamin()['response_data'];

            foreach ($DataPenjamin as $PKey => $PValue) {
                //Harga
                $targettedKelas = '';
                if($value['kelompok'] === 'RJ') {
                    $targettedKelas = __UID_KELAS_GENERAL_RJ__;
                } else if($value['kelompok'] === 'RAD') {
                    $targettedKelas = __UID_KELAS_GENERAL_RAD__;
                } else if($value['kelompok'] === 'LAB') {
                    $targettedKelas = __UID_KELAS_GENERAL_LAB__;
                }

                $checkHarga = self::$query->select('master_tindakan_kelas_harga', array(
                    'id'
                ))
                    ->where(array(
                        'master_tindakan_kelas_harga.tindakan' => '= ?',
                        'AND',
                        'master_tindakan_kelas_harga.kelas' => '= ?',
                        'AND',
                        'master_tindakan_kelas_harga.penjamin' => '= ?'
                    ), array(
                        $targettedTindakan,
                        $targettedKelas,
                        $PValue['uid']
                    ))
                    ->execute();
                if(count($checkHarga['response_data']) > 0) {
                    //Update Tarif
                    $workerTarif = self::$query->update('master_tindakan_kelas_harga', array(
                        'harga' => floatval($value['tarif']),
                        'updated_at' => parent::format_date(),
                        'deleted_at' => NULL
                    ))
                        ->where(array(
                            'master_tindakan_kelas_harga.penjamin' => '= ?',
                            'AND',
                            'master_tindakan_kelas_harga.tindakan' => '= ?',
                            'AND',
                            'master_tindakan_kelas_harga.kelas' => '= ?'
                        ), array(
                            $PValue['uid'],
                            $targettedTindakan,
                            $targettedKelas
                        ))
                        ->execute();
                } else {
                    //New Tarif
                    $workerTarif = self::$query->insert('master_tindakan_kelas_harga', array(
                        'tindakan' => $targettedTindakan,
                        'kelas' => $targettedKelas,
                        'harga' => floatval($value['tarif']),
                        'penjamin' => $PValue['uid'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                }
            }


			$checkPoliTindakan = self::$query->select('master_poli_tindakan', array(
				'id'
			))
				->where(array(
					'master_poli_tindakan.uid_poli' => '= ?',
					'AND',
					'master_poli_tindakan.uid_tindakan' => '= ?'
				), array(
					$targettedPoli,
					$targettedTindakan
				))
				->execute();

            //Check Poliklinik
            //Kasus khusus
            //Fisioterapi
            $nama_poli = '';
            if(
                trim(strtoupper($value['poliklinik'])) === 'THT' ||
                trim(strtoupper($value['poliklinik'])) === 'IGD'
            ) {
                if(trim(strtoupper($value['poliklinik'])) === 'THT') {
                    $nama_poli = 'Poliklinik THT';
                }

                if(trim(strtoupper($value['poliklinik'])) === 'IGD') {
                    $nama_poli = 'IGD';
                }
            } else {
                $nama_poli = 'Poliklinik ' . ucwords(strtolower($value['poliklinik']));
            }

            if($nama_poli !== '') {
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
                    ->execute()
                ;
                if(count($checkPoli['response_data']) > 0) {
                    $targettedPoli = $checkPoli['response_data'][0]['uid'];
                    if(strtoupper($value['jenis']) === 'KONSULTASI') {
                        $proceed_poli = self::$query->update('master_poli', array(
                            'tindakan_konsultasi' => $targettedTindakan,
                            'deleted_at' => NULL
                        ))
                            ->where(array(
                                'master_poli.uid' => '= ?'
                            ), array(
                                $targettedPoli
                            ))
                            ->execute();
                    } else {
                        $proceed_poli = self::$query->update('master_poli', array(
                            'deleted_at' => NULL
                        ))
                            ->where(array(
                                'master_poli.uid' => '= ?'
                            ), array(
                                $targettedPoli
                            ))
                            ->execute();
                    }
                } else {
                    $targettedPoli = parent::gen_uuid();
                    $proceed_poli = self::$query->insert('master_poli', array(
                        'uid' => $targettedPoli,
                        'nama' => $nama_poli,
                        'editable' => 'TRUE',
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                }


                //Set setting tindakan per poli
                //Check Switch Item
                $checkPoliTindakan = self::$query->select('master_poli_tindakan', array(
                    'id'
                ))
                    ->where(array(
                        'master_poli_tindakan.uid_poli' => '= ?',
                        'AND',
                        'master_poli_tindakan.uid_tindakan' => '= ?'
                    ), array(
                        $targettedPoli,
                        $targettedTindakan
                    ))
                    ->execute();

                if(count($checkPoliTindakan['response_data']) > 0) {
                    $workerSettingTindakan = self::$query->update('master_poli_tindakan', array(
                        'deleted_at' => NULL
                    ))
                        ->where(array(
                            'master_poli_tindakan.uid_poli' => '= ?',
                            'AND',
                            'master_poli_tindakan.uid_tindakan' => '= ?'
                        ), array(
                            $targettedPoli,
                            $targettedTindakan
                        ))
                        ->execute();
                } else {
                    $workerSettingTindakan = self::$query->insert('master_poli_tindakan', array(
                        'uid_poli' => $targettedPoli,
                        'uid_tindakan' => $targettedTindakan,
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                }
            }
        }

        return array(
            'duplicate_row' => $duplicate_row,
            'non_active' => $non_active,
			'failed_proceed' => $failed_proceed,
            'success_proceed' => $success_proceed,
            'data' => $parameter['data_import'],
            'proceed' => $proceed_data
        );
    }

	private function get_tindakan_backend($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'master_tindakan.deleted_at' => 'IS NULL',
                'AND',
                'master_tindakan.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array();
        } else {
            $paramData = array(
                'master_tindakan.deleted_at' => 'IS NULL',
            );

            $paramValue = array();
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('master_tindakan', array(
                'uid',
                'nama',
                'kelompok',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('master_tindakan', array(
                'uid',
                'nama',
                'kelompok',
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
        $Poli = new Poli(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            //get Poli
            $poliList = array();
            $tindakan_poli = self::$query->select('master_poli_tindakan', array(
                'uid_poli'
            ))
                ->where(array(
                    'master_poli_tindakan.uid_tindakan' => '= ?',
                    'AND',
                    'master_poli_tindakan.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach ($tindakan_poli['response_data'] as $PKey => $PValue) {
                $PoliDetail = $Poli->get_poli_info($PValue['uid_poli'])['response_data'][0];
                array_push($poliList, $PoliDetail);
            }

            if($value['kelompok'] === 'LAB') {
                $target_jenis = 'Laboratorium';
            } else if($value['kelompok'] === 'RAD') {
                $target_jenis = 'Radiologi';
            } else {
                $target_jenis = $poliList;
            }

            $data['response_data'][$key]['poli_list'] = $target_jenis;
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }

        $itemTotal = self::$query->select('master_tindakan', array(
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


	/*============GET TINDAKAN============*/
	public function get_tindakan(){
		$data = self::$query
		->select('master_tindakan', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))	
		->where(array(
			'master_tindakan.deleted_at' => 'IS NULL'
		))
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;

			$autonum++;
		}

		return $data;
	}

	public function get_tindakan_rawat_inap(){
		$data = self::$query
					->select('master_tindakan', array(
						'uid',
						'nama',
						'created_at',
						'updated_at'
						)
					)	
					->where(array(
							'master_tindakan.kelompok' => '= ?',
							'AND',
							'master_tindakan.deleted_at' => 'IS NULL'
						), array('RI')
					)
					->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['harga'] = self::get_harga_join_kelas_by_tindakan($value['uid']);

			$autonum++;
		}

		return $data;
	}

	/*public function tambah_master_tindakan($parameter) {
		$uid = parent::gen_uuid();
		$check = self::duplicate_check(array(
			'table' => 'master_tindakan',
			'check' => $parameter['nama']
		));

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {

			return self::$query->insert('master_tindakan', array(
				'uid' => $uid,
				'nama' => $parameter['nama'],
				'created_at' => parent::format_date(),
				'updated_at' => parent::format_date()
			))
			->execute();
		}
	}*/

	public function edit_master_tindakan($parameter) {
		return self::$query->update('master_tindakan', array(
			'nama' => $parameter['nama'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_tindakan.uid' => '= ?',
			'AND',
			'master_tindakan.deleted_at' => 'IS NULL'
		), array(
			$parameter['uid']
		))
		->execute();
	}

	public function tindakan_kelas($parameter) {
		$data = self::$query->select('master_tindakan_kelas', array(
			'uid',
			'nama',
			'created_at',
			'updated_at',
			'urutan'
		))
		->where(array(
			'master_tindakan_kelas.deleted_at' => 'IS NULL',
			'AND',
			'master_tindakan_kelas.jenis' => '= ?'
		), array(
			$parameter[2]
		))
		->order(array(
			'master_tindakan_kelas.urutan' => 'ASC'
		))
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	public function get_tindakan_rawat_jalan(){
		$data = self::$query
					->select('master_tindakan', array(
						'uid',
						'nama',
						'kelompok',
						'created_at',
						'updated_at'
						)
					)	
					->where(array(
							'master_tindakan.kelompok' => '= ?',
							'AND',
							'master_tindakan.deleted_at' => 'IS NULL'
						), array('RJ')
					)
					->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['harga'] = self::get_harga_rawat_jalan($value['uid'])['response_data'][0]['harga'];

			$autonum++;
		}

		return $data;
	}

	private function get_harga_per_tindakan_backend($parameter) {
        $kelas = self::$query->select('master_tindakan_kelas', array(
            'uid',
            'nama',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'master_tindakan_kelas.jenis' => '= ?',
                'AND',
                'master_tindakan_kelas.deleted_at' => 'IS NULL'
            ), array(
                $parameter['jenis']
            ))
            ->order(array(
                'master_tindakan_kelas.created_at' => 'ASC'
            ))
            ->execute();

        $returnData = array();
        $autonum = 1;

        $itemTotalAll = 0;
		$PoliParse = new Poli(self::$pdo);
        foreach ($kelas['response_data'] as $key => $value) {

            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                $paramData = array(
                    'master_tindakan_kelas_harga.deleted_at' => 'IS NULL',
                    'AND',
                    'master_tindakan_kelas_harga.kelas' => '= ?',
                    'AND',
                    'master_tindakan_kelas_harga.penjamin' => '= ?'
                );

                $paramValue = array(
                    $value['uid'],
                    $parameter['penjamin']
                );
            } else {
                $paramData = array(
                    'master_tindakan_kelas_harga.deleted_at' => 'IS NULL',
                    'AND',
                    'master_tindakan_kelas_harga.kelas' => '= ?',
                    'AND',
                    'master_tindakan_kelas_harga.penjamin' => '= ?'
                );

                $paramValue = array(
                    $value['uid'],
                    $parameter['penjamin']
                );
            }

            if ($parameter['length'] < 0) {
                $data = self::$query->select('master_tindakan_kelas_harga', array(
                    'id',
                    'tindakan',
                    'kelas',
                    'penjamin',
                    'mitra',
                    'harga',
                    'created_at',
                    'updated_at'
                ))
                    ->where($paramData, $paramValue)
                    ->order(array(
                        'master_tindakan_kelas_harga.created_at' => 'ASC'
                    ))
                    ->execute();
            } else {
                $data = self::$query->select('master_tindakan_kelas_harga', array(
                    'id',
                    'tindakan',
                    'kelas',
                    'penjamin',
                    'mitra',
                    'harga',
                    'created_at',
                    'updated_at'
                ))
                    ->where($paramData, $paramValue)
                    ->offset(intval($parameter['start']))
                    ->limit(intval($parameter['length']))
                    ->order(array(
                        'master_tindakan_kelas_harga.created_at' => 'ASC'
                    ))
                    ->execute();
            }

            $data['response_draw'] = intval($parameter['draw']);
            $autonum = intval($parameter['start']) + 1;

            if(count($data['response_data']) > 0) {
                foreach ($data['response_data'] as $TKKey => $TKValue) {
                    $TKValue['autonum'] = $autonum;

                    $Tindakan = self::get_tindakan_detail($TKValue['tindakan']);
                    $TKValue['tindakan_detail'] = $Tindakan['response_data'][0];

                    //Kelas Detail
                    $KelasDetail = self::get_kelas_tindakan_detail($TKValue['kelas']);
                    $TKValue['kelas'] = $KelasDetail['response_data'][0];

                    //Poli
                    $Poli = self::$query->select('master_poli_tindakan', array(
                        'uid_poli'
                    ))
                        ->where(array(
                            'master_poli_tindakan.uid_tindakan' => '= ?',
                            'AND',
                            'master_poli_tindakan.deleted_at' => 'IS NULL'
                        ), array(
                            $TKValue['tindakan']
                        ))
                        ->execute();
                    foreach($Poli['response_data'] as $PKey => $PValue) {
                        $Poli['response_data'][$PKey]['detail'] = $PoliParse->get_poli_info($PValue['uid_poli'])['response_data'][0];
                    }
                    $TKValue['poli'] = $Poli['response_data'];


                    $TKValue['harga'] = floatval($TKValue['harga']);
                    if(count($Tindakan['response_data']) > 0) {
                        array_push($returnData, $TKValue);
                    } else {
                        array_push($returnData, $TKValue);
                    }

                    $autonum++;
                }

                $itemTotal = self::$query->select('master_tindakan_kelas_harga', array(
                    'id'
                ))
                    ->where($paramData, $paramValue)
                    ->execute();

                $itemTotalAll += count($itemTotal['response_data']);
            }
        }

        $data['recordsTotal'] = $itemTotalAll;
        $data['recordsFiltered'] = $itemTotalAll;
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);
        $data['response_data'] = $returnData;


        return $data;
    }

	private function get_harga_per_tindakan($parameter) {
		$kelas = self::$query->select('master_tindakan_kelas', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
            ->join('master_tindakan_kelas_harga', array(
                'id',
                'tindakan',
                'kelas',
                'penjamin',
                'mitra',
                'harga'
            ))
            ->on(array(
                array('master_tindakan_kelas_harga.kelas', '=', 'master_tindakan_kelas.uid')
            ))
            ->where(array(
                'master_tindakan_kelas.jenis' => '= ?',
                'AND',
                'master_tindakan_kelas.deleted_at' => 'IS NULL',
                'AND',
                'master_tindakan_kelas_harga.penjamin' => '= ?',
                'AND',
                'master_tindakan_kelas_harga.tindakan' => '= ?'
            ), array(
                $parameter[2], $parameter[3], $parameter[4]
            ))
            ->order(array(
                'master_tindakan_kelas.created_at' => 'ASC'
            ))
            ->execute();

		$returnData = array();
		$autonum = 1;
		foreach ($kelas['response_data'] as $key => $value) {

            $TKValue['autonum'] = $autonum;

            $Tindakan = self::get_tindakan_info($value['tindakan']);
            $TKValue['tindakan_detail'] = $Tindakan['response_data'][0];

            //Kelas Details
            $KelasDetail = self::get_kelas_tindakan_detail($value['kelas']);
            $TKValue['kelas'] = $KelasDetail['response_data'][0];


            $TKValue['harga'] = floatval($value['harga']);
            if(count($Tindakan['response_data']) > 0) {
                array_push($returnData, $value);
            } else {
                array_push($returnData, $value);
            }

            $autonum++;
            /*$data = self::$query->select('master_tindakan_kelas_harga', array(
                'id',
                'tindakan',
                'kelas',
                'penjamin',
                'mitra',
                'harga',
                'created_at',
                'updated_at'
            ))
            ->where(array(
                'master_tindakan_kelas_harga.deleted_at' => 'IS NULL',
                'AND',
                'master_tindakan_kelas_harga.kelas' => '= ?',
                'AND',
                'master_tindakan_kelas_harga.penjamin' => '= ?'
            ), array(
                $value['uid'],
                $parameter[3]
            ))
            ->order(array(
                'master_tindakan_kelas_harga.created_at' => 'ASC'
            ))
            ->execute();
            //array_push($returnData, count($data['response_data']));
            if(count($data['response_data']) > 0) {
                foreach ($data['response_data'] as $TKKey => $TKValue) {
                    $TKValue['autonum'] = $autonum;

					$Tindakan = self::get_tindakan_detail($TKValue['tindakan']);
					$TKValue['tindakan_detail'] = $Tindakan['response_data'][0];

					//Kelas Detail
					$KelasDetail = self::get_kelas_tindakan_detail($TKValue['kelas']);
					$TKValue['kelas'] = $KelasDetail['response_data'][0];


					$TKValue['harga'] = floatval($TKValue['harga']);
					if(count($Tindakan['response_data']) > 0) {
						array_push($returnData, $TKValue);
					} else {
                        array_push($returnData, $TKValue);
                    }

					$autonum++;
                }
            }*/
		}

		return $returnData;
	}


    public function get_tindakan_info($parameter){
        /*$data = self::$query
            ->select('master_tindakan', array(
                'uid',
                'nama',
                'kelompok',
                'created_at',
                'updated_at'
            ))
            ->join('master_tindakan_kelas_harga', array('harga'))
            ->on(array(
                array('master_tindakan_kelas_harga.tindakan', '=', 'master_tindakan.uid')
            ))
            ->order(array(
                'master_tindakan_kelas_harga.harga' => 'ASC'
            ))
            ->where(array(
                'master_tindakan.uid' => '= ?',
                'AND',
                'master_tindakan_kelas_harga.deleted_at' => 'IS NULL',
                'AND',
                'master_tindakan.deleted_at' => 'IS NULL',
            ),
                array($parameter)
            )
            ->execute();*/
        $data = self::$query
            ->select('master_tindakan', array(
                'uid',
                'nama',
                'kelompok',
                'created_at',
                'updated_at'
            ))
            ->where(array(
                'master_tindakan.uid' => '= ?',
                'AND',
                'master_tindakan.deleted_at' => 'IS NULL',
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

	public function get_tindakan_detail($parameter) {
		$data = self::$query
				->select('master_tindakan', array(
						'uid',
						'nama',
						'kelompok',
						'created_at',
						'updated_at'
					)
				)
				->where(array(
                        'master_tindakan.deleted_at' => 'IS NULL',
                        'AND',
                        'master_tindakan.uid' => '= ?'
                    ),
                    array($parameter))
                ->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {

		    /*if($value['kelompok'] === 'LAB') {

            } else if($value['kelompok'] === 'RAD') {

            } else {

            }*/

            $harga = self::$query->select('master_tindakan_kelas_harga', array(
                'harga'
            ))
                ->order(array(
                    'harga' => 'ASC'
                ))
                ->where(array(
                    'master_tindakan_kelas_harga.tindakan' => '= ?',
                    'AND',
                    'master_tindakan_kelas_harga.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();
            $harga_list = array();
            foreach ($harga['response_data'] as $HKey => $HValue) {
                array_push($harga_list, $HValue['harga']);
            }
            $data['response_data'][$key]['harga_minimum'] = floatval($harga_list[0]);
            $data['response_data'][$key]['harga_maksimum'] = floatval($harga_list[count($harga_list) - 1]);

			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $data;
	}

	/*-- mengambil nilai yang tidak sesuai dengan parameter --*/
	private function get_tindakan_notexist($parameter = array()){
		$count_param = count($parameter);
		
		if ($count_param > 0){

			$data = self::$query
					->select('master_tindakan', array(
							'uid',
							'nama',
							'created_at',
							'updated_at'
						)
					)
					->where(array(
								'master_tindakan.deleted_at' => 'IS NULL',
								'AND',
								'master_tindakan.uid' => 'NOT IN ?'
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
	}

	private function tambah_tindakan($parameter) {
		//
		$uid = parent::gen_uuid();
		$data = self::$query->insert('master_tindakan_kelas', array(
			'uid' => $uid,
			'nama' => $parameter['nama'],
			'jenis' => strtoupper($parameter['jenis']),
			'urutan' => 0,
			'created_at' => parent::format_date(),
			'updated_at' => parent::format_date()
		))
		->execute();
		return $data;
	}

	private function edit_tindakan($parameter) {
		$data = self::$query->update('master_tindakan_kelas', array(
			'nama' => $parameter['nama'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_tindakan_kelas.uid' => '= ?',
			'AND',
			'master_tindakan_kelas.jenis' => '= ?',
			'AND',
			'master_tindakan_kelas.deleted_at' => 'IS NULL',
		), array(
			$parameter['uid'],
			strtoupper($parameter['jenis'])
		))
		->execute();
		return $data;
	}

	private function update_position($parameter) {
		$successCounter = 0;
		foreach ($parameter['data'] as $key => $value) {
			$data = self::$query->update('master_tindakan_kelas', array(
				'urutan' => $value['position']
			))
			->where(array(
				'master_tindakan_kelas.uid' => '= ?',
				'AND',
				'master_tindakan_kelas.deleted_at' => 'IS NULL',
				'AND',
				'master_tindakan_kelas.jenis' => '= ?'
			), array(
				$value['uid'],
				$parameter['jenis']
			))
			->execute();
			$successCounter += $data['response_result'];
		}
		return (($successCounter >= count($parameter['data'])) ? 1 : 0);
	}

	private function transfer_kelas_tindakan($parameter) {
		//
	}

	public function get_kelas_tindakan(){
		$data = self::$query
			->select('master_tindakan_kelas', array(
					'uid',
					'nama',
					'urutan'
				)
			)
			->where(array(
					'master_tindakan_kelas.deleted_at' => 'IS NULL'
			))
			->execute();

		return $data;
	}

	public function get_kelas_tindakan_detail($parameter){ //uid_kelas
		$data = self::$query
			->select('master_tindakan_kelas', array(
					'uid',
					'nama',
					'urutan'
				)
			)
			->where(array(
					'master_tindakan_kelas.uid' => '= ?',
					'AND',
					'master_tindakan_kelas.deleted_at' => 'IS NULL'
				), array($parameter)
			)
			->execute();

		return $data;
	}

	public function get_harga_tindakan($parameter){		//uid_tindakan
		$data = self::$query
			->select('master_tindakan_kelas_harga', array(
					'id',
					'tindakan',
					'kelas',
					'harga',
					'penjamin'
				)
			)
			->where(array(
					'master_tindakan_kelas_harga.tindakan' => '= ?',
					'AND',
					'master_tindakan_kelas_harga.deleted_at' => 'IS NULL'
				), array($parameter)
			)
			->execute();

		return $data;
	}

	public function get_harga_rawat_jalan($parameter){
		$data = self::$query
			->select('master_tindakan_kelas_harga', array(
					'id',
					'harga',
					'updated_at'
				)
			)
			->where(array(
					'master_tindakan_kelas_harga.tindakan' => '= ?',
					'AND',
					'master_tindakan_kelas_harga.deleted_at' => 'IS NULL'
				), array($parameter)
			)
			->execute();

		return $data;
	}

	public function get_harga_join_kelas_by_tindakan($parameter){ //uid_tindakan
		$result = [];

		$data = self::$query
			->select('master_tindakan_kelas_harga', array(
					'id',
					'kelas',
					'harga as harga_tindakan'
				)
			)
			->join('master_tindakan_kelas', array(
					'urutan',
					'nama as nama_kelas'
				)
			)
			->on(array(
					array('master_tindakan_kelas_harga.kelas','=', 'master_tindakan_kelas.uid')
				)
			)
			->where(array(
					'master_tindakan_kelas_harga.tindakan' => '= ?',
					'AND',
					'master_tindakan_kelas_harga.deleted_at' => 'IS NULL'
				), array($parameter)
			)
			->order(array('master_tindakan_kelas.urutan' => 'ASC'))
			->execute();

		foreach ($data['response_data'] as $key => $value) {
			$result[$value['urutan']] = $data['response_data'][$key];
		}

		return $result;
	}

	/*=====================- CRUD AREA -=======================*/
	/*=====================- MASTER -=======================*/


	private function tambah_master_tindakan($parameter) {
		$check = self::duplicate_check(array(
			'table' => 'master_tindakan',
			'check' => $parameter['nama']
		));

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();
			$worker = self::$query->insert('master_tindakan', array(
				'uid' => $uid,
				'nama' => $parameter['nama'],
				'created_at' => parent::format_date(),
				'updated_at' => parent::format_date()
			))
			->returning("uid")
			->execute();

			return $worker;
		}
	}





	/*=====================- MASTER -=======================*/
	private function tambah_tindakan_rawat_jalan($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table'=>'master_tindakan',
			'check'=>$parameter['nama']
		));

		$result = [];

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();
			$tindakan = self::$query
						->insert('master_tindakan', array(
								'uid' => $uid,
								'nama' => $parameter['nama'],
								'kelompok' => 'RJ',
								'created_at' => parent::format_date(),
								'updated_at' => parent::format_date()
								)
						)
						->execute();

			if ($tindakan['response_result'] > 0){
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
								$uid,
								$UserData['data']->uid,
								'master_tindakan',
								'I',
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class'=>__CLASS__
						)
					);

				//insert harga
				$harga = self::$query
					->insert('master_tindakan_kelas_harga', array(
							'tindakan' => $uid,
							'harga' => $parameter['harga'],
							'created_at' => parent::format_date(),
							'updated_at' => parent::format_date()
						)
					)
					->execute();
			}
		}

		$result = ['tindakan' => $tindakan, 'harga' => $harga];

		return $result;
	}

	private function edit_tindakan_rawat_jalan($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
		$result = [];

		$old = self::get_tindakan_detail($parameter['uid']);

		$tindakan = self::$query
				->update('master_tindakan', array(
						'nama' => $parameter['nama'],
						'updated_at' => parent::format_date()
					)
				)
				->where(array(
					'master_tindakan.deleted_at' => 'IS NULL',
					'AND',
					'master_tindakan.uid' => '= ?'
					),
					array(
						$parameter['uid']
					)
				)
				->execute();

		if ($tindakan['response_result'] > 0){
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
						$parameter['uid'],
						$UserData['data']->uid,
						'master_tindakan',
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

			$harga = self::$query
				->update('master_tindakan_kelas_harga', array(
						'harga' => $parameter['harga'],
						'updated_at' => parent::format_date()
					)
				)
				->where(array(
						'master_tindakan_kelas_harga.tindakan' => '= ?',
						'AND',
						'master_tindakan_kelas_harga.deleted_at' => 'IS NULL' 
					), array(
						$parameter['uid']
					)
				)
				->execute();
		}

		$result = ['tindakan' => $tindakan, 'harga' => $harga];

		return $result;
	}


	private function tambah_tindakan_rawat_inap($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table'=>'master_tindakan',
			'check'=>$parameter['nama']
		));

		$result = [];

		if (count($check['response_data']) > 0){
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();
			$tindakan = self::$query
						->insert('master_tindakan', array(
								'uid' => $uid,
								'nama' => $parameter['nama'],
								'kelompok' => 'RI',
								'created_at' => parent::format_date(),
								'updated_at' => parent::format_date()
							)
						)->execute();

			if ($tindakan['response_result'] > 0){
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
								$uid,
								$UserData['data']->uid,
								'master_tindakan',
								'I',
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class'=>__CLASS__
						)
					);

				if (count($parameter['harga']) > 0) {
					$harga_result = 0;

					foreach ($parameter['harga'] as $key => $value) {
						$harga = self::$query
							->insert('master_tindakan_kelas_harga', array(
									'tindakan' => $uid,
									'kelas' => $key,
									'harga' => $value,
									'created_at' => parent::format_date(),
									'updated_at' => parent::format_date()
								)
							)
							->execute();

						if ($harga['response_result'] > 0) {
							$harga_result += $harga['response_result'];
						}
					}
				}
			}
		}

		$result = ['tindakan' => $tindakan, 'harga' => $harga];

		return $result;
	}

	private function update_tindakan_kelas_harga($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$workerList = array();
		//Kerjakan Bos
		foreach ($parameter['data'] as $key => $value) {	//List Penjamin
			foreach ($value as $TKey => $TValue) { //List Tindakan
				foreach ($TValue as $Kkey => $KValue) { //List Kelas
					//key = uid penjamin
					//TKey = uid tindakan
					//Kkey = uid kelas
					//KValue = harga
					
					//Check
					$check = self::$query->select('master_tindakan_kelas_harga', array(
						'id'
					))
					->where(array(
						'master_tindakan_kelas_harga.tindakan' => '= ?',
						'AND',
						'master_tindakan_kelas_harga.kelas' => '= ?',
						'AND',
						'master_tindakan_kelas_harga.penjamin' => '= ?'
					), array(
						$TKey, $Kkey, $key
					))
					->execute();
					if(floatval($KValue) > 0) {
						if(count($check['response_data']) > 0) {
							$worker = self::$query->update('master_tindakan_kelas_harga', array(
								'deleted_at' => NULL,
								'harga' => $KValue
							))
							->where(array(
								'master_tindakan_kelas_harga.id' => '= ?',
								'AND',
								'master_tindakan_kelas_harga.tindakan' => '= ?',
								'AND',
								'master_tindakan_kelas_harga.kelas' => '= ?',
								'AND',
								'master_tindakan_kelas_harga.penjamin' => '= ?'
							), array(
								$check['response_data'][0]['id'], $TKey, $Kkey, $key
							))
							->execute();
						} else {
							$worker = self::$query->insert('master_tindakan_kelas_harga', array(
								'tindakan' => $TKey,
								'kelas' => $Kkey,
								'harga' => floatval($KValue),
								'penjamin' => $key,
								'created_at' => parent::format_date(),
								'updated_at' => parent::format_date()
							))
							->execute();
						}
						array_push($workerList, $worker);
					}
				}
			}
		}
		return $workerList;
	}

	private function edit_tindakan_rawat_inap($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
		$result = [];

		$old = self::get_tindakan_detail($parameter['uid']);

		$tindakan = self::$query
				->update('master_tindakan', array(
						'nama'=>$parameter['nama'],
						'updated_at'=>parent::format_date()
					)
				)
				->where(array(
					'master_tindakan.deleted_at' => 'IS NULL',
					'AND',
					'master_tindakan.uid' => '= ?'
					),
					array(
						$parameter['uid']
					)
				)
				->execute();

		if ($tindakan['response_result'] > 0){
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
						$parameter['uid'],
						$UserData['data']->uid,
						'master_tindakan',
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

			if (count($parameter['harga']) > 0){
				foreach ($parameter['harga'] as $key => $value) {
					$harga = self::$query
						->update('master_tindakan_kelas_harga', array(
								'harga' => $value,
								'updated_at' => parent::format_date()
							)
						)
						->where(array(
								'master_tindakan_kelas_harga.tindakan' => '= ?',
								'AND',
								'master_tindakan_kelas_harga.kelas' => '= ?',
								'AND',
								'master_tindakan_kelas_harga.deleted_at' => 'IS NULL' 
							), array(
								$parameter['uid'],
								$key
							)
						)
						->execute();
				}
			}
		}

		$result = ['tindakan' => $tindakan, 'harga' => $harga];

		return $result;
	}

	private function delete_tindakan($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		if($parameter[6] == 'master_tindakan_kelas_harga') {
			$tindakan = self::$query
			->delete($parameter[6])
			->where(array(
					$parameter[6] . '.tindakan' => '= ?',
					'AND',
					$parameter[6] . '.penjamin' => '= ?'
				), array(
					$parameter[7],
					$parameter[8]
				)
			)
			->execute();	
		} else if($parameter[5] == 'master_tindakan_kelas_harga') {
			$tindakan = self::$query
			->delete($parameter[5])
			->where(array(
					$parameter[5] . '.tindakan' => '= ?',
					'AND',
					$parameter[5] . '.penjamin' => '= ?'
				), array(
					$parameter[6],
					$parameter[7]
				)
			)
			->execute();	
		} else {
			$tindakan = self::$query
			->delete($parameter[6])
			->where(array(
					$parameter[6] . '.uid' => '= ?'
				), array(
					$parameter[7]	
				)
			)
			->execute();
		}

		if ($tindakan['response_result'] > 0){
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

		return $tindakan;
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