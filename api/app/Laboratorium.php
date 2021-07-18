<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Penjamin as Penjamin;
use PondokCoder\Utility as Utility;
use PondokCoder\Antrian as Antrian;
use PondokCoder\Pasien as Pasien;

class Laboratorium extends Utility {
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
				case 'kategori':
					return self::get_kategori();
					break;
				case 'kategori_detail':
					return self::get_kategori_detail($parameter[2]);
					break;
				case 'lokasi':
					return self::get_lokasi();
					break;
				case 'lokasi_detail':
					return self::get_lokasi_detail($parameter[2]);
					break;
				case 'spesimen':
					return self::get_spesimen();
					break;
				case 'spesimen_detail':
					return self::get_spesimen_detail($parameter[2]);
				 	break;
				 case 'lab':
					return self::get_lab();
				 	break;
				case 'lab_detail':
					return self::get_lab_detail($parameter[2]);
					break;
				
				case 'antrian':					
					return self::get_antrian();		//-> get antrian labor_order
					break;

                case 'get-data-pasien-antrian':
					return self::get_data_pasien_antrian($parameter[2]);
					break;

				case 'get-laboratorium-order-detail-item':
					return self::get_laboratorium_order_detail_item($parameter[2]);
				break;
				
				case 'get-laboratorium-lampiran':
					return self::get_laboratorium_lampiran($parameter[2]);
					break;
				
				case 'get_tindakan_for_dokter':
					return self::get_tindakan_for_dokter();
					break;
				
				case 'get-laboratorium-order':
					return self::get_laboratorium_order($parameter[2]);
					break;
				
				case 'get-laboratorium-order-detail':
					return self::get_laboratorium_order_detail($parameter[2]);
					break;

                case 'get-laboratorium-order-pack':
                    return self::get_lab_order_pack($parameter[2]);
                    break;

                case 'get_lab_nilai_detail':
                    return self::get_lab_nilai_detail($parameter);
                    break;

				default:
					return self::get_lab();
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function __POST__($parameter = array()) {
		try {
			switch($parameter['request']) {
				case 'add_kategori':
					return self::add_kategori($parameter);
					break;
				case 'edit_kategori':
					return self::edit_kategori($parameter);
					break;
				case 'add_spesimen':
					return self::add_spesimen($parameter);
					break;
				case 'edit_spesimen':
					return self::edit_spesimen($parameter);
					break;
				case 'add_lokasi':
					return self::add_lokasi($parameter);
					break;
				case 'edit_lokasi':
					return self::edit_lokasi($parameter);
					break;
				case 'add_lab':
					return self::add_lab($parameter);
					break;
				case 'edit_lab':
					return self::edit_lab($parameter);
					break;
                case 'get_lab_backend':
                    return self::get_lab_backend($parameter);
                    break;
				case 'new-order-lab':
					return self::new_order_lab($parameter);
					break;

				case 'edit-order-lab':
					return self::edit_order_lab($parameter);
					break;

				case 'update-hasil-lab':
					return self::update_hasil_lab($parameter);
					break;

                case 'get-antrian-backend':
                    return self::get_antrian_backend($parameter);
                    break;

                case 'charge_invoice_item':
                    return self::charge_invoice_item($parameter);
                    break;

                case 'verifikasi_hasil':
                    return self::verifikasi_hasil($parameter);
                    break;

                case 'toogle_status_item_lab':
                    return self::toogle_status_item_lab($parameter);
                    break;

                case 'get-laboratorium-backend':
                    return self::get_laboratorium_backend($parameter);
                    break;

                case 'laboratorium_import_fetch':
                    return self::laboratorium_import_fetch($parameter);
                    break;

                case 'laboratorium_import_fetch_nilai':
                    return self::laboratorium_import_fetch_nilai($parameter);
                    break;

                case 'proceed_import_laboratorium':
                    return self::proceed_import_laboratorium($parameter);
                    break;

                case 'proceed_import_laboratorium_nilai':
                    return self::proceed_import_laboratorium_nilai($parameter);
                    break;

                case 'verifikasi_item_lab':
                    return self::verifikasi_item_lab($parameter);
                    break;

                case 'update_naratif':
                    return self::update_naratif($parameter);
                    break;

                case 'orphaned_nilai_get_item':
                    return self::orphaned_nilai_get_item($parameter);
                    break;
			}	
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	private function orphaned_nilai_get_item($parameter) {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'master_lab.deleted_at' => 'IS NULL',
                'AND',
                '(master_lab.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'master_lab.kode' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
            );
            $paramValue = array();
        } else {
            $paramData = array(
                'master_lab.deleted_at' => 'IS NULL'
            );
            $paramValue = array();
        }

        if ($parameter['length'] < 0) {
            $data = self::$query->select('master_lab', array(
                'uid',
                'kode',
                'nama',
                'spesimen',
                'keterangan'
            ))
                ->where($paramData, $paramValue)
                ->order(array(
                    'kode' => 'ASC'
                ))
                ->execute();
        } else {
            $data = self::$query->select('master_lab', array(
                'uid',
                'kode',
                'nama',
                'spesimen',
                'keterangan'
            ))
                ->where($paramData, $paramValue)
                ->order(array(
                    'kode' => 'ASC'
                ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }



        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        $data_returned = array();
        foreach ($data['response_data'] as $key => $value) {
            //Detail Layanan
            $detail = self::$query->select('master_lab_nilai', array(
                'id',
                'satuan',
                'nilai_maks',
                'nilai_min',
                'status',
                'keterangan'
            ))
                ->where(array(
                    'master_lab_nilai.lab' => '= ?',
                    'AND',
                    'master_lab_nilai.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->order(array(
                    'id' => 'ASC'
                ))
                ->execute();
            $data['response_data'][$key]['detail'] = $detail['response_data'];
            if(count($detail['response_data']) == 0) {
                $data['response_data'][$key]['autonum'] = $autonum;
                array_push($data_returned, $data['response_data'][$key]);
                $autonum++;
            }
        }

        $itemTotal = count($data['response_data']);

        $data['recordsTotal'] = count($itemTotal);
        $data['response_data'] = $data_returned;
        $data['recordsFiltered'] = count($itemTotal);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

	private function update_naratif($parameter) {
	    $update = self::$query->update('master_lab', array(
	        'naratif' => $parameter['target_value']
        ))
            ->where(array(
                'master_lab.deleted_at' => 'IS NULL',
                'AND',
                'master_lab.uid' => '= ?'
            ), array(
                $parameter['uid']
            ))
            ->execute();
	    return $update;
    }

	public function get_lab_nilai_detail($parameter) {
	    $data = self::$query->select('master_lab_nilai', array(
	        'id',
            'keterangan',
            'nilai_min',
            'nilai_maks',
            'satuan'
        ))
            ->where(array(
                'master_lab_nilai.id' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
	    return $data;
    }

	private function verifikasi_item_lab($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $parent = self::$query->update('lab_order', array(
            'dr_penanggung_jawab' => $parameter['dpjp']
        ))
            ->where(array(
                'lab_order.uid' => '= ?',
                'AND',
                'lab_order.deleted_at' => 'IS NULL'
            ), array(
                $parameter['uid']
            ))
            ->execute();

        $worker = self::$query->update('lab_order_detail', array(
            'mitra' => $parameter['mitra'],
            'dpjp' => $parameter['dpjp'],
            'verifikator' => $UserData['data']->uid
        ))
            ->where(array(
                'lab_order_detail.lab_order' => '= ?',
                'AND',
                'lab_order_detail.deleted_at' => 'IS NULL',
                'AND',
                'lab_order_detail.tindakan' => '= ?'
            ), array(
                $parameter['uid'],
                $parameter['tindakan']
            ))
            ->execute();





        //Update antrian nomor dan charge invoice
        $AsesmenInfo = self::$query->select('asesmen', array(
            'poli',
            'kunjungan',
            'antrian',
            'pasien',
            'dokter'
        ))
            ->where(array(
                'asesmen.deleted_at' => 'IS NULL',
                'AND',
                'asesmen.uid' => '= ?'
            ), array(
                $parameter['asesmen']
            ))
            ->execute();

        $AntrianDetail = $AsesmenInfo['response_data'][0];
        $Antrian = new Antrian(self::$pdo);
        $AntrianData = $Antrian->get_antrian_detail('antrian', $AntrianDetail['antrian'])['response_data'][0];



        //Check Item Lab
        $checkMaster = self::$query->select('lab_order_detail', array(
            'id'
        ))
            ->where(array(
                'lab_order_detail.mitra' => 'IS NULL',
                'AND',
                'lab_order_detail.lab_order' => '= ?'
            ), array(
                $parameter['uid']
            ))
            ->execute();
        if(count($checkMaster['response_data']) > 0) {
            //
        } else {

            //Update master to P
            $master_order = self::$query->update('lab_order', array(
                'status' => ($AntrianData['penjamin'] === __UIDPENJAMINUMUM__) ? 'K' : 'P',
                'updated_at' => parent::format_date()
            ))
                ->where(array(
                    'lab_order.uid' => '= ?',
                    'AND',
                    'lab_order.deleted_at' => 'IS NULL'
                ), array(
                    $parameter['uid']
                ))
                ->execute();



            $antrian_nomor = self::$query->update('antrian_nomor', array(
                'status' => ($AntrianData['penjamin'] === __UIDPENJAMINUMUM__) ? 'K' : 'L'
            ))
                ->where(array(
                    'antrian_nomor.poli' => '= ?',
                    'AND',
                    'antrian_nomor.kunjungan' => '= ?',
                    'AND',
                    'antrian_nomor.dokter' => '= ?',
                    'AND',
                    'antrian_nomor.pasien' => '= ?'
                ), array(
                    $AntrianDetail['poli'],
                    $AntrianDetail['kunjungan'],
                    $AntrianDetail['dokter'],
                    $AntrianDetail['pasien']
                ))
                ->execute();


        }

        $invoice_master = self::$query->select('invoice', array(
            'uid'
        ))
            ->where(array(
                'invoice.kunjungan' => '= ?',
                'AND',
                'invoice.pasien' => '= ?'
            ), array(
                $AntrianDetail['kunjungan'],
                $AntrianDetail['pasien']
            ))
            ->execute();

        $invoice_detail = self::$query->update('invoice_detail', array(
            'status_bayar' => 'N',
            'harga' => $parameter['harga'],
            'subtotal' => $parameter['harga'],
            'mitra' => $parameter['mitra']
        ))
            ->where(array(
                'invoice_detail.invoice' => '= ?',
                'AND',
                'invoice_detail.item' => '= ?',
                'AND',
                'invoice_detail.deleted_at' => 'IS NULL'
            ), array(
                $invoice_master['response_data'][0]['uid'],
                $parameter['tindakan']
            ))
            ->execute();

        return $worker;
    }


	private function laboratorium_import_fetch($parameter) {
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

    private function laboratorium_import_fetch_nilai($parameter) {
        if (!empty($_FILES['csv_file']['name'])) {

            $file_data = fopen($_FILES['csv_file']['tmp_name'], 'r');
            $column = fgetcsv($file_data); //array_head
            $row_data = array();
            while ($row = fgetcsv($file_data)) {
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

    private function proceed_import_laboratorium_nilai($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $duplicate_row = array();
        $non_active = array();
        $success_proceed = 0;
        $proceed_data = array();


        foreach ($parameter['data_import'] as $key => $value) {
            $targettedKategori = '';

            //Check Kategori
            $checkKategoriLab = self::$query->select('master_lab_kategori', array(
                'uid'
            ))
                ->where(array(
                    'master_lab_kategori.nama' => '= ?'
                ), array(
                    $value['kategori']
                ))
                ->execute();
            if(count($checkKategoriLab['response_data']) > 0) {
                $targettedKategori = $checkKategoriLab['response_data'][0]['uid'];
                $proceed_kategori = self::$query->update('master_lab_kategori', array(
                    'deleted_at' => NULL
                ))
                    ->where(array(
                        'master_lab_kategori.uid' => '= ?'
                    ), array(
                        $targettedKategori
                    ))
                    ->execute();
            } else {
                $targettedKategori = parent::gen_uuid();
                $proceed_kategori = self::$query->insert('master_lab_kategori', array(
                    'uid' => $targettedKategori,
                    'nama' => ($value['kategori'] != '') ? $value['kategori'] : 'UNSPECIFIED',
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
            }



            $master_lab_kategori_item = self::$query->select('master_lab_kategori_item', array(
                'id',
                'lab'
            ))
                ->where(array(
                    'master_lab_kategori_item.kategori' => '= ?',
                    'AND',
                    'master_lab_kategori_item.deleted_at' => 'IS NULL'
                ), array(
                    $targettedKategori
                ))
                ->execute();
            foreach($master_lab_kategori_item['response_data'] as $KKey => $KValue) {
                $checkNilai = self::$query->select('master_lab_nilai', array(
                    'id'
                ))
                    ->where(array(
                        'master_lab_nilai.lab' => '= ?',
                        'AND',
                        'master_lab_nilai.keterangan' => '= ?'
                    ), array(
                        $KValue['lab'],
                        $value['parameter']
                    ))
                    ->execute();
                if(count($checkNilai['response_data']) > 0) {
                    $proceed_nilai = self::$query->update('master_lab_nilai', array(
                        'keterangan' => $value['parameter'],
                        'satuan' => $value['satuan'],
                        'nilai_min' => ($value['min'] != '') ? $value['min'] : '-',
                        'nilai_maks' => ($value['max'] != '') ? $value['max'] : '-',
                        'status' => 'A',
                        'deleted_at' => NULL
                    ))
                        ->where(array(
                            'master_lab_nilai.id' => '= ?'
                        ), array(
                            $checkNilai['response_data'][0]['id']
                        ))
                        ->execute();
                } else {
                    $proceed_nilai = self::$query->insert('master_lab_nilai', array(
                        'lab' => $KValue['lab'],
                        'keterangan' => $value['parameter'],
                        'satuan' => $value['satuan'],
                        'nilai_min' => ($value['min'] != '') ? $value['min'] : '-',
                        'nilai_maks' => ($value['max'] != '') ? $value['max'] : '-',
                        'status' => 'A',
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
            'success_proceed' => $success_proceed,
            'data' => $parameter['data_import'],
            'proceed' => $proceed_data
        );
    }

    private function proceed_import_laboratorium($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $duplicate_row = array();
        $non_active = array();
        $success_proceed = 0;
        $proceed_data = array();

        //Reset selected kategori lab
        $resetKategori = self::$query->update('master_lab_kategori_item', array(
            'deleted_at' => parent::format_date()
        ))
            ->execute();

        foreach ($parameter['data_import'] as $key => $value) {
            $targettedLab = '';
            $targettedMitra = '';
            $targettedTindakan = '';
            $targettedKategori = '';


            //Check Kategori
            $checkKategoriLab = self::$query->select('master_lab_kategori', array(
                'uid'
            ))
                ->where(array(
                    'master_lab_kategori.nama' => '= ?'
                ), array(
                    $value['kategori']
                ))
                ->execute();
            if(count($checkKategoriLab['response_data']) > 0) {
                $targettedKategori = $checkKategoriLab['response_data'][0]['uid'];
                $proceed_kategori = self::$query->update('master_lab_kategori', array(
                    'deleted_at' => NULL
                ))
                    ->where(array(
                        'master_lab_kategori.uid' => '= ?'
                    ), array(
                        $targettedKategori
                    ))
                    ->execute();
            } else {
                $targettedKategori = parent::gen_uuid();
                $proceed_kategori = self::$query->insert('master_lab_kategori', array(
                    'uid' => $targettedKategori,
                    'nama' => ($value['kategori'] != '') ? $value['kategori'] : 'UNSPECIFIED',
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
            }





            //Check Laboratorium & Tindakan
            $checkLab = self::$query->select('master_lab', array(
                'uid'
            ))
                ->where(array(
                    'master_lab.nama' => '= ?',
                    'AND',
                    'master_lab.kode' => '= ?'
                ), array(
                    $value['nama'],
                    $value['kode']
                ))
                ->execute();
            if(count($checkLab['response_data']) > 0) {
                $targettedLab = $checkLab['response_data'][0]['uid'];
                $proceed_lab = self::$query->update('master_lab', array(
                    'deleted_at' => NULL
                ))
                    ->where(array(
                        'master_lab.uid' => '= ?'
                    ), array(
                        $targettedLab
                    ))
                    ->execute();
            } else {
                $targettedLab = parent::gen_uuid();
                $proceed_lab = self::$query->insert('master_lab', array(
                    'uid' => $targettedLab,
                    'kode' => $value['kode'],
                    'nama' => $value['nama'],
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date(),
                    'keterangan' => (isset($value['keterangan'])) ? $value['keterangan'] : ''
                ))
                    ->execute();
            }

            //Check Selected Kategori
            $checkSelectedKategori = self::$query->select('master_lab_kategori_item', array(
                'id'
            ))
                ->where(array(
                    'master_lab_kategori_item.kategori' => '= ?',
                    'AND',
                    'master_lab_kategori_item.lab' => '= ?'
                ), array(
                    $targettedKategori,
                    $targettedLab
                ))
                ->execute();
            if(count($checkSelectedKategori['response_data']) > 0) {
                $proceedSelectedKategori = self::$query->update('master_lab_kategori_item', array(
                    'deleted_at' => NULL
                ))
                    ->where(array(
                        'master_lab_kategori_item.id' => '= ?'
                    ), array(
                        $checkSelectedKategori['response_data'][0]['id']
                    ))
                    ->execute();
            } else {
                $proceedSelectedKategori = self::$query->insert('master_lab_kategori_item', array(
                    'lab' => $targettedLab,
                    'kategori' => $targettedKategori,
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
            }

            array_push($proceed_data, $proceed_lab);

            //Check Mitra
            $checkMitra = self::$query->select('master_mitra', array(
                'uid'
            ))
                ->where(array(
                    'master_mitra.nama' => '= ?',
                    'AND',
                    'master_mitra.jenis' => '= ?'
                ), array(
                    $value['mitra'],
                    'LAB'
                ))
                ->execute();
            if(count($checkMitra['response_data']) > 0) {
                $targettedMitra = $checkMitra['response_data'][0]['uid'];
                $proceed_mitra = self::$query->update('master_mitra', array(
                    'deleted_at' => NULL
                ))
                    ->where(array(
                        'master_mitra.uid' => '= ?'
                    ), array(
                        $targettedMitra
                    ))
                    ->execute();
            } else {
                $targettedMitra = parent::gen_uuid();
                $proceed_mitra = self::$query->insert('master_mitra', array(
                    'uid' => $targettedMitra,
                    'nama' => $value['mitra'],
                    'jenis' => 'LAB',
                    'kontak' => '',
                    'alamat' => '',
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
            }

            if($proceed_mitra['response_result'] > 0) { //Jika tindakan berhasil diproses
                //Check Tindakan
                $checkTindakan = self::$query->select('master_tindakan', array(
                    'uid'
                ))
                    ->where(array(
                        'master_tindakan.uid' => '= ?',
                        'AND',
                        'master_tindakan.kelompok' => '= ?'
                    ), array(
                        $targettedLab,
                        'LAB'
                    ))
                    ->execute();
                if(count($checkTindakan['response_data']) > 0) {
                    $proceed_tindakan = self::$query->update('master_tindakan', array(
                        'deleted_at' => NULL
                    ))
                        ->where(array(
                            'master_tindakan.uid' => '= ?',
                            'AND',
                            'master_tindakan.kelompok' => '= ?'
                        ), array(
                            $targettedLab,
                            'LAB'
                        ))
                        ->execute();
                } else {
                    $proceed_tindakan = self::$query->insert('master_tindakan', array(
                        'uid' => $targettedLab,
                        'nama' => $value['nama'],
                        'kelompok' => 'LAB',
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                }




                //Loop Penjamin
                $getPenjamin = self::$query->select('master_penjamin', array(
                    'uid'
                ))
                    ->where(array(
                        'master_penjamin.deleted_at' => 'IS NULL'
                    ))
                    ->execute();
                foreach ($getPenjamin['response_data'] as $PKey => $PValue) { //Apply semua penjamin
                    //Data Kelas Lab
                    $kelasLab = self::$query->select('master_tindakan_kelas', array(
                        'uid'
                    ))
                        ->where(array(
                            'master_tindakan_kelas.jenis' => '= ?',
                            'AND',
                            'master_tindakan_kelas.deleted_at' => 'IS NULL'
                        ), array(
                            'LAB'
                        ))
                        ->execute();
                    foreach ($kelasLab['response_data'] as $KKey => $KValue) { //Apply semua kelas Labor
                        //Check kelas harga
                        $checkTarif = self::$query->select('master_tindakan_kelas_harga', array(
                            'id'
                        ))
                            ->where(array(
                                'master_tindakan_kelas_harga.tindakan' => '',
                                'AND',
                                'master_tindakan_kelas_harga.kelas' => '= ?',
                                'AND',
                                'master_tindakan_kelas_harga.penjamin' => ' = ?',
                                'AND',
                                'master_tindakan_kelas_harga.mitra' => '= ?'
                            ), array(
                                $targettedLab,
                                $KValue['uid'],
                                $PValue['uid'],
                                $targettedMitra
                            ))
                            ->execute();
                        if(count($checkTarif['response_data']) > 0) {
                            $proceed_tarif = self::$query->update('master_tindakan_kelas_harga', array(
                                'harga' => floatval($value['harga']),
                                'deleted_at' => NULL
                            ))
                                ->where(array(
                                    'master_tindakan_kelas_harga.id' => '= ?'
                                ), array(
                                    $checkTarif['response_data'][0]['id']
                                ))
                                ->execute();
                        } else {
                            $proceed_tarif = self::$query->insert('master_tindakan_kelas_harga', array(
                                'tindakan' => $targettedLab,
                                'kelas' => $KValue['uid'],
                                'penjamin' => $PValue['uid'],
                                'mitra' => $targettedMitra,
                                'harga' => floatval($value['harga']),
                                'created_at' => parent::format_date(),
                                'updated_at' => parent::format_date()
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
            'proceed' => $proceed_data
        );
    }


	private function get_lab_order_pack($parameter) {
        //Lab Order
        $lab = self::$query->select('lab_order', array(
            'uid',
            'asesmen',
            'dr_penanggung_jawab',
            'no_order',
            'status',
            'kesan',
            'anjuran',
            'created_at'
        ))
            ->where(array(
                'lab_order.uid' => '= ?',
                'AND',
                'lab_order.deleted_at' => 'IS NULL'
            ), array(
                $parameter
            ))
            ->execute();



        //Order Detail
        foreach($lab['response_data'] as $LabKey => $LabValue) {
            $Petugas = array();
            $PetugasChecker = array();
            $Pegawai = new Pegawai(self::$pdo);

            $detailLaborOrder = self::$query->select('lab_order_detail', array(
                'tindakan',
                'dpjp',
                'mitra',
                'verifikator',
                'keterangan'
            ))
                ->where(array(
                    'lab_order_detail.lab_order' => '= ?',
                    'AND',
                    'lab_order_detail.deleted_at' => 'IS NULL'
                ), array(
                    $LabValue['uid']
                ))
                ->execute();

            foreach ($detailLaborOrder['response_data'] as $LabDetailKey => $LabDetailValue) {
                $LabTindakan = self::get_lab_detail($LabDetailValue['tindakan']);
                $detailLaborOrder['response_data'][$LabDetailKey]['tindakan'] = $LabTindakan['response_data'][0];

                $nilaiLaborOrder = self::$query->select('lab_order_nilai', array(
                    'tindakan',
                    'id_lab_nilai',
                    'nilai',
                    'petugas'
                ))
                    ->where(array(
                        'lab_order_nilai.lab_order' => '= ?',
                        'AND',
                        'lab_order_nilai.tindakan' => '= ?',
                        'AND',
                        'lab_order_nilai.deleted_at' => 'IS NULL'
                    ), array(
                        $LabValue['uid'],
                        $LabDetailValue['tindakan']
                    ))
                    ->execute();
                foreach ($nilaiLaborOrder['response_data'] as $LabOrderDetailItemKey => $LabOrderDetailItemValue) {

                    if(!in_array($LabOrderDetailItemValue['petugas'], $PetugasChecker)) {
                        $PetugasDetail = $Pegawai->get_detail_pegawai($LabOrderDetailItemValue['petugas'])['response_data'][0];
                        array_push($Petugas, $PetugasDetail);
                        array_push($PetugasChecker, $LabOrderDetailItemValue['petugas']);
                    }


                    $LabItem = self::$query->select('master_lab_nilai', array(
                        'satuan',
                        'nilai_maks',
                        'nilai_min',
                        'keterangan'
                    ))
                        ->where(array(
                            'master_lab_nilai.id' => '= ?',
                            'AND',
                            'master_lab_nilai.deleted_at' => 'IS NULL'
                        ), array(
                            $LabOrderDetailItemValue['id_lab_nilai']
                        ))
                        ->execute();

                    $nilaiLaborOrder['response_data'][$LabOrderDetailItemKey]['lab_nilai'] = $LabItem['response_data'][0];
                }

                $detailLaborOrder['response_data'][$LabDetailKey]['hasil'] = $nilaiLaborOrder['response_data'];
            }

            $lab['response_data'][$LabKey]['detail'] = $detailLaborOrder['response_data'];
            $lab['response_data'][$LabKey]['petugas'] = $Petugas;
            $lab['response_data'][$LabKey]['parse_tanggal'] = date('d F Y', strtotime($LabValue['created_at']));
            $lab['response_data'][$LabKey]['dr_penanggung_jawab'] = $Pegawai->get_detail_pegawai($LabValue['dr_penanggung_jawab'])['response_data'][0];
        }

        return $lab;
    }

	private function toogle_status_item_lab($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $worker = self::$query->update('master_lab_nilai', array(
            'status' => $parameter['status']
        ))
            ->where(array(
                'master_lab_nilai.deleted_at' => 'IS NULL',
                'AND',
                'master_lab_nilai.id' => '= ?',
                'AND',
                'master_lab_nilai.lab' => '= ?'
            ), array(
                $parameter['id'],
                $parameter['uid']
            ))
            ->execute();
        return $worker;
    }

	private function get_laboratorium_backend($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'master_lab.deleted_at' => 'IS NULL',
                'AND',
                'master_lab.status' => '= ?',
                'AND',
                '(master_lab.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'master_lab.kode' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
            );
            $paramValue = array('P');
        } else {
            $paramData = array(
                'master_lab.deleted_at' => 'IS NULL',
                'AND',
                'master_lab.status' => '= ?'
            );
            $paramValue = array('P');
        }

        if ($parameter['length'] < 0) {
            $data = self::$query->select('master_lab', array(
                'uid',
                'kode',
                'nama',
                'spesimen',
                'keterangan'
            ))
                ->where($paramData, $paramValue)
                ->order(array(
                    'kode' => 'ASC'
                ))
                ->execute();
        } else {
            $data = self::$query->select('master_lab', array(
                'uid',
                'kode',
                'nama',
                'spesimen',
                'keterangan'
            ))
                ->where($paramData, $paramValue)
                ->order(array(
                    'kode' => 'ASC'
                ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }



        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            //Detail Layanan
            $detail = self::$query->select('master_lab_nilai', array(
                'id',
                'satuan',
                'nilai_maks',
                'nilai_min',
                'status',
                'keterangan'
            ))
                ->where(array(
                    'master_lab_nilai.lab' => '= ?',
                    'AND',
                    'master_lab_nilai.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->order(array(
                    'id' => 'ASC'
                ))
                ->execute();

            $data['response_data'][$key]['detail'] = $detail['response_data'];
            $autonum++;
        }

        $itemTotal = self::$query->select('master_lab', array(
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

	private function verifikasi_hasil($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $update = self::$query->update('lab_order', array(
            'status' => 'D'
        ))
            ->where(array(
                'lab_order.uid' => '= ?',
                'AND',
                'lab_order.deleted_at' => 'IS NULL'
            ), array(
                $parameter['uid']
            ))
            ->execute();

        if($update['response_result'] > 0) {
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
                        'lab_order',
                        'U',
                        'status',
                        json_encode($parameter),
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                )
            );
        }

        return $update;
    }

	public function __DELETE__($parameter = array()) {
		return self::delete($parameter);
	}

	public function charge_invoice_item($parameter) {
	    $charge_result = array();

	    //Update Status menjadi V
        $proceedLab = self::$query->update('lab_order', array(
            'status' => 'V'
        ))
            ->where(array(
                'lab_order.uid' => '= ?',
                'AND',
                'lab_order.asesmen' => '= ?',
                'AND',
                'lab_order.selesai' => '= ?',
                'AND',
                'lab_order.deleted_at' => 'IS NULL'
            ), array(
                $parameter['uid'],
                $parameter['asesmen'],
                'false'
            ))
            ->execute();;

	    //Ambil semua item untuk asesmen sekarang
        $LabOrder = self::$query->select('lab_order', array(
            'uid',
            'pasien'
        ))
            ->where(array(
                'lab_order.uid' => '= ?',
                'AND',
                'lab_order.asesmen' => '= ?',
                'AND',
                'lab_order.selesai' => '= ?',
                'AND',
                'lab_order.deleted_at' => 'IS NULL'
            ), array(
                $parameter['uid'],
                $parameter['asesmen'],
                'false'
            ))
            ->execute();

        $Invoice = new Invoice(self::$pdo);

        $InvoiceCheck = self::$query->select('invoice', array(
            'uid'
        ))
            ->where(array(
                'invoice.kunjungan' => '= ?',
                'AND',
                'invoice.deleted_at' => 'IS NULL'
            ), array(
                $parameter['kunjungan']
            ))
            ->execute();

        if (count($InvoiceCheck['response_data']) > 0) {
            $TargetInvoice = $InvoiceCheck['response_data'][0]['uid'];
        } else {
            $InvMasterParam = array(
                'kunjungan' => $parameter['kunjungan'],
                'pasien' => $parameter['pasien'],
                'keterangan' => 'Tagihan laboratorium'
            );
            $NewInvoice = $Invoice->create_invoice($InvMasterParam);
            $TargetInvoice = $NewInvoice['response_unique'];
        }

        foreach ($LabOrder['response_data'] as $key => $value) {
            //Get Detail
            $Detail = self::$query->select('lab_order_detail', array(
                'tindakan',
                'penjamin'
            ))
                ->where(array(
                    'lab_order_detail.lab_order' => '= ?',
                    'AND',
                    'lab_order_detail.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach ($Detail['response_data'] as $DKey => $DValue) {
                $HargaTindakan = self::$query->select('master_tindakan_kelas_harga', array(
                    'id',
                    'tindakan',
                    'kelas',
                    'penjamin',
                    'harga'
                ))
                    ->where(array(
                        'master_tindakan_kelas_harga.penjamin' => '= ?',
                        'AND',
                        'master_tindakan_kelas_harga.kelas' => '= ?',
                        'AND',
                        'master_tindakan_kelas_harga.tindakan' => '= ?',
                        'AND',
                        'master_tindakan_kelas_harga.deleted_at' => 'IS NULL'
                    ), array(
                        $DValue['penjamin'],
                        __UID_KELAS_GENERAL_LAB__,    //Fix 1 harga kelas GENERAL
                        $DValue['tindakan']
                    ))
                    ->execute();
                $HargaFinal = (count($HargaTindakan['response_data']) > 0) ? $HargaTindakan['response_data'][0]['harga'] : 0;

                $InvoiceDetail = $Invoice->append_invoice(array(
                    'invoice' => $TargetInvoice,
                    'item' => $DValue['tindakan'],
                    'item_origin' => 'master_tindakan',
                    'qty' => 1,
                    'harga' => $HargaFinal,
                    //'status_bayar' => ($DValue['penjamin'] == __UIDPENJAMINUMUM__) ? 'N' : 'Y', // Check Penjamin. Jika non umum maka langsung lunas
                    'status_bayar' => ($DValue['penjamin'] == __UIDPENJAMINUMUM__) ? 'V' : 'Y', // Check Penjamin. Jika non umum maka langsung lunas
                    'subtotal' => $HargaFinal,
                    'discount' => 0,
                    'discount_type' => 'N',
                    'pasien' => $value['pasien'],
                    'penjamin' => $DValue['penjamin'],
                    'billing_group' => 'laboratorium',
                    'keterangan' => 'Biaya Laboratorium',
                    'departemen' => $parameter['departemen']
                ));

                array_push($charge_result, $InvoiceDetail);
            }
        }
        return $charge_result;
    }

	private function get_kategori(){
		$data = self::$query->select('master_lab_kategori', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_lab_kategori.deleted_at' => 'IS NULL'
		), array())
		->execute();
		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	private function get_kategori_detail($parameter){
		$data = self::$query->select('master_lab_kategori', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_lab_kategori.deleted_at' => 'IS NULL',
			'AND',
			'master_lab_kategori.uid' => '= ?'
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

	private function get_spesimen(){
		$data = self::$query->select('master_lab_spesimen', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_lab_spesimen.deleted_at' => 'IS NULL'
		), array())
		->execute();
		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	private function get_spesimen_detail($parameter){
		$data = self::$query->select('master_lab_spesimen', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_lab_spesimen.deleted_at' => 'IS NULL',
			'AND',
			'master_lab_spesimen.uid' => '= ?'
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

	private function get_lokasi(){
		$data = self::$query->select('master_lab_lokasi', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_lab_lokasi.deleted_at' => 'IS NULL'
		), array())
		->execute();
		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	private function get_lokasi_detail($parameter){
		$data = self::$query->select('master_lab_lokasi', array(
			'uid',
			'nama',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_lab_lokasi.deleted_at' => 'IS NULL',
			'AND',
			'master_lab_lokasi.uid' => '= ?'
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

	private function get_lab_backend($parameter) {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'master_lab.deleted_at' => 'IS NULL',
                'AND',
                'master_lab.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array();
        } else {
            $paramData = array(
                'master_lab.deleted_at' => 'IS NULL'
            );

            $paramValue = array();
        }

        if ($parameter['length'] < 0) {
            $data = self::$query->select('master_lab', array(
                'uid',
                'kode',
                'nama',
                'spesimen',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('master_lab', array(
                'uid',
                'kode',
                'nama',
                'spesimen',
                'created_at',
                'updated_at'
            ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->where($paramData, $paramValue)
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        $mitra_list = new Mitra(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;

            $mitra_all_raw = $mitra_list->get_mitra()['response_data'];
            $mitra_all_parse = array();
            foreach($mitra_all_raw as $MAK => $MAV) {
                if($MAV['jenis'] === 'LAB') {
                    array_push($mitra_all_parse, $MAV['nama']);
                }
            }

            $data['response_data'][$key]['mitra_all'] = $mitra_all_parse;

            $mitra_data = $mitra_list->get_mitra_provider($value['uid']);
            $mitra_data_parse = $mitra_data['response_data'];
            $mitraInfo = array();
            $mitra_unik = array();
            $mitra_unik_nama = array();
            foreach ($mitra_data_parse as $MKey => $MValue) {
                $Mitra_detail = $mitra_list->get_mitra_detail($MValue['mitra']);
                if($Mitra_detail['response_data'][0]['jenis'] === 'LAB') {
                    $mitra_data_parse[$MKey]['mitra'] = $Mitra_detail['response_data'][0];
                    array_push($mitraInfo, $MValue);

                    if(!in_array($MValue['mitra'], $mitra_unik)) {
                        array_push($mitra_unik, $MValue['mitra']);
                        array_push($mitra_unik_nama, $Mitra_detail['response_data'][0]['nama']);
                    }
                }
            }
            $data['response_data'][$key]['mitra'] = $mitraInfo;
            $data['response_data'][$key]['mitra_nama'] = $mitra_unik_nama;
            $data['response_data'][$key]['spesimen'] = self::get_spesimen_detail($value['spesimen'])['response_data'][0];

            $autonum++;
        }

        $itemTotal = self::$query->select('master_lab', array(
            'uid'
        ))
            ->where(array(
                'master_lab.deleted_at' => 'IS NULL'
            ))
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

	private function get_lab(){
		$data = self::$query->select('master_lab', array(
			'uid',
			'kode',
			'nama',
			'spesimen',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_lab.deleted_at' => 'IS NULL'
		))
		->execute();
		$autonum = 1;
        $mitra_list = new Mitra(self::$pdo);
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;

			$mitra_data = $mitra_list->get_mitra_provider($value['uid']);
			$mitra_data_parse = $mitra_data['response_data'];
			$mitraInfo = array();
			foreach ($mitra_data_parse as $MKey => $MValue) {
			    $Mitra_detail = $mitra_list->get_mitra_detail($MValue['mitra']);
			    $mitra_data_parse[$MKey]['mitra'] = $Mitra_detail['response_data'][0];
			    array_push($mitraInfo, $MValue);
            }
            $data['response_data'][$key]['mitra'] = $mitraInfo;
			$data['response_data'][$key]['spesimen'] = self::get_spesimen_detail($value['spesimen'])['response_data'][0];
			$autonum++;
		}
		return $data;
	}

	public function get_lab_detail($parameter){
		$data = self::$query->select('master_lab', array(
			'uid',
			'kode',
			'nama',
			'naratif',
			'keterangan',
			'spesimen',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_lab.deleted_at' => 'IS NULL',
			'AND',
			'master_lab.uid' => '= ?'
		), array(
			$parameter
		))
		->execute();
		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['keterangan'] = (!isset($value['keterangan'])) ? '' : $value['keterangan'];
			$data['response_data'][$key]['spesimen'] = self::get_spesimen_detail($value['spesimen'])['response_data'][0];

			$KategoriLab = self::$query->select('master_lab_kategori_item', array(
				'kategori'
			))
			->where(array(
				'master_lab_kategori_item.deleted_at' => 'IS NULL',
				'AND',
				'master_lab_kategori_item.lab' => '= ?'
			), array(
				$value['uid']
			))
			->execute();
			foreach ($KategoriLab['response_data'] as $KategoriKey => $KategoriValue) {
				$KategoriLab['response_data'][$KategoriKey] = self::get_kategori_detail($KategoriValue['kategori'])['response_data'][0];
			}
			$data['response_data'][$key]['kategori'] = $KategoriLab['response_data'];
			//============================================================================
			$LokasiLab = self::$query->select('master_lab_lokasi_item', array(
				'lokasi'
			))
			->where(array(
				'master_lab_lokasi_item.deleted_at' => 'IS NULL',
				'AND',
				'master_lab_lokasi_item.lab' => '= ?'
			), array(
				$value['uid']
			))
			->execute();
			foreach ($LokasiLab['response_data'] as $LokasiKey => $LokasiValue) {
				$LokasiLab['response_data'][$LokasiKey] = self::get_lokasi_detail($LokasiValue['lokasi'])['response_data'][0];
			}
			$data['response_data'][$key]['lokasi'] = $LokasiLab['response_data'];
			//============================================================================
			$NilaiLab = self::$query->select('master_lab_nilai', array(
				'id',
				'satuan',
				'nilai_min',
				'nilai_maks',
				'keterangan',
				'created_at',
				'updated_at'
			))
			->where(array(
				'master_lab_nilai.deleted_at' => 'IS NULL',
				'AND',
				'master_lab_nilai.lab' => '= ?'
			), array(
				$value['uid']
			))
			->execute();
			$data['response_data'][$key]['nilai'] = $NilaiLab['response_data'];
			//============================================================================
			$PenjaminLab = self::$query->select('master_poli_tindakan_penjamin', array(
				'uid_poli',
				'uid_penjamin',
				'harga',
				'created_at',
				'updated_at'
			))
			->where(array(
				'master_poli_tindakan_penjamin.deleted_at' => 'IS NULL',
				'AND',
				'master_poli_tindakan_penjamin.uid_tindakan' => '= ?'
			), array(
				$value['uid']
			))
			->execute();
			foreach ($PenjaminLab['response_data'] as $PenjaminKey => $PenjaminValue) {
				$Penjamin = new Penjamin(self::$pdo);
				$PenjaminLab['response_data'][$PenjaminKey]['harga'] = floatval($PenjaminValue['harga']);
				$PenjaminLab['response_data'][$PenjaminKey]['penjamin'] = $Penjamin::get_penjamin_detail($PenjaminValue['uid_penjamin'])['response_data'][0];
			}
			$data['response_data'][$key]['penjamin'] = $PenjaminLab['response_data'];
			//============================================================================
			$autonum++;
		}
		return $data;
	}


//=================================================================================
	private function add_kategori($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table' => 'master_lab_kategori',
			'check' => $parameter['nama']
		));
		if(count($check['response_data']) > 0) {
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();
			$worker = self::$query->insert('master_lab_kategori', array(
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
						'master_lab_kategori',
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

	private function edit_kategori($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_kategori_detail($parameter['uid']);

		$worker = self::$query
		->update('master_lab_kategori', array(
			'nama' => $parameter['nama'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_lab_kategori.deleted_at' => 'IS NULL',
			'AND',
			'master_lab_kategori.uid' => '= ?'
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
					'master_lab_kategori',
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

	private function add_spesimen($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table' => 'master_lab_spesimen',
			'check' => $parameter['nama']
		));
		if(count($check['response_data']) > 0) {
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();
			$worker = self::$query->insert('master_lab_spesimen', array(
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
						'master_lab_spesimen',
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

	private function edit_spesimen($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_spesimen_detail($parameter['uid']);

		$worker = self::$query
		->update('master_lab_spesimen', array(
			'nama' => $parameter['nama'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_lab_spesimen.deleted_at' => 'IS NULL',
			'AND',
			'master_lab_spesimen.uid' => '= ?'
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
					'master_lab_spesimen',
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

	private function add_lokasi($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$check = self::duplicate_check(array(
			'table' => 'master_lab_lokasi',
			'check' => $parameter['nama']
		));
		if(count($check['response_data']) > 0) {
			$check['response_message'] = 'Duplicate data detected';
			$check['response_result'] = 0;
			unset($check['response_data']);
			return $check;
		} else {
			$uid = parent::gen_uuid();
			$worker = self::$query->insert('master_lab_lokasi', array(
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
						'master_lab_lokasi',
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

	private function edit_lokasi($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$old = self::get_lokasi_detail($parameter['uid']);

		$worker = self::$query
		->update('master_lab_lokasi', array(
			'nama' => $parameter['nama'],
			'updated_at' => parent::format_date()
		))
		->where(array(
			'master_lab_lokasi.deleted_at' => 'IS NULL',
			'AND',
			'master_lab_lokasi.uid' => '= ?'
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
					'master_lab_lokasi',
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

	private function add_lab($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$uid = parent::gen_uuid();
        if(isset($parameter['spesimen']) && !empty($parameter['spesimen'])) {
            $worker = self::$query->insert('master_lab', array(
                'uid' => $uid,
                'kode' => $parameter['kode'],
                'nama' => $parameter['nama'],
                'naratif' => $parameter['naratif'],
                'keterangan' => $parameter['keterangan'],
                'spesimen' => $parameter['spesimen'],
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();
        } else {
            $worker = self::$query->insert('master_lab', array(
                'uid' => $uid,
                'kode' => $parameter['kode'],
                'nama' => $parameter['nama'],
                'naratif' => $parameter['naratif'],
                'keterangan' => $parameter['keterangan'],
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();
        }


		if($worker['response_result'] > 0) {
			$log = parent::log(array(
				'type' => 'activity',
				'column' => array(
					'unique_target',
					'user_uid',
					'table_name',
					'action',
					'new_value',
					'logged_at',
					'status',
					'login_id'
				),
				'value' => array(
					$uid,
					$UserData['data']->uid,
					'master_lab',
					'I',
					json_encode($parameter),
					parent::format_date(),
					'N',
					$UserData['data']->log_id
				),
				'class' => __CLASS__
			));

			//Kategori Item
			foreach ($parameter['kategori'] as $key => $value) {
				$worker = self::$query->insert('master_lab_kategori_item', array(
					'lab' => $uid,
					'kategori' => $value,
					'created_at' => parent::format_date(),
					'updated_at' => parent::format_date()
				))
				->execute();

				if($worker['response_result'] > 0) {
					//
				}
			}

			//Lokasi Item
			/*foreach ($parameter['lokasi'] as $key => $value) {
				$worker = self::$query->insert('master_lab_lokasi_item', array(
					'lab' => $uid,
					'lokasi' => $value,
					'created_at' => parent::format_date(),
					'updated_at' => parent::format_date()
				))
				->execute();

				if($worker['response_result'] > 0) {
					//
				}
			}*/

			//Nilai Item
			foreach ($parameter['nilai'] as $key => $value) {
				$worker = self::$query->insert('master_lab_nilai', array(
					'lab' => $uid,
					'satuan' => $value['satuan'],
					'nilai_maks' => $value['max'],
					'nilai_min' => $value['min'],
					'keterangan' => $value['keterangan'],
					'created_at' => parent::format_date(),
					'updated_at' => parent::format_date()
				))
				->execute();

				if($worker['response_result'] > 0) {
					//
				}
			}
			

			//New Tindakan
			$tindakan = self::$query->insert('master_tindakan', array(
				'uid' => $uid,
				'nama' => 'Laboratorium ' . $parameter['nama'],
				'kelompok' => 'LAB',
				'created_at' => parent::format_date(),
				'updated_at' => parent::format_date()
			))
			->execute();

			if($tindakan['response_result'] > 0) {
				$log = parent::log(array(
					'type' => 'activity',
					'column' => array(
						'unique_target',
						'user_uid',
						'table_name',
						'action',
						'new_value',
						'logged_at',
						'status',
						'login_id'
					),
					'value' => array(
						$uid,
						$UserData['data']->uid,
						'master_tindakan',
						'I',
						json_encode($parameter),
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class' => __CLASS__
				));
			}

			//Penjamin Item
			/*foreach ($parameter['penjamin'] as $key => $value) {
				//Tindakan Harga
				$tindakan_harga = self::$query->insert('master_poli_tindakan_penjamin', array(
					'harga' => $value['harga'],
					'uid_penjamin' => $value['penjamin'],
					'uid_tindakan' => $uid,
					'uid_poli' => 'cd9f8f30-4236-2d8b-46d6-b561f9b2c5a3',//Laboratorium
					'created_at' => parent::format_date(),
					'updated_at' => parent::format_date()
				))
				->execute();

				if($tindakan_harga['response_result'] > 0) {
					$log = parent::log(array(
						'type' => 'activity',
						'column' => array(
							'unique_target',
							'user_uid',
							'table_name',
							'action',
							'new_value',
							'logged_at',
							'status',
							'login_id'
						),
						'value' => array(
							$uid,
							$UserData['data']->uid,
							'master_poli_tindakan_penjamin',
							'I',
							json_encode($parameter),
							parent::format_date(),
							'N',
							$UserData['data']->log_id
						),
						'class' => __CLASS__
					));
				}
			}*/
		}

		return $worker;
	}

	private function edit_lab($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$uid = $parameter['uid'];
		$old_value = self::get_lab_detail($uid);
		if(isset($parameter['spesimen']) && !empty($parameter['spesimen'])) {
            $worker = self::$query->update('master_lab', array(
                'kode' => $parameter['kode'],
                'nama' => $parameter['nama'],
                'naratif' => $parameter['naratif'],
                'keterangan' => $parameter['keterangan'],
                'spesimen' => $parameter['spesimen'],
                'updated_at' => parent::format_date()
            ))
                ->where(array(
                    'master_lab.uid' => '= ?',
                    'AND',
                    'master_lab.deleted_at' => 'IS NULL'
                ), array(
                    $uid
                ))
                ->execute();
        } else {
            $worker = self::$query->update('master_lab', array(
                'kode' => $parameter['kode'],
                'nama' => $parameter['nama'],
                'naratif' => $parameter['naratif'],
                'keterangan' => $parameter['keterangan'],
                'updated_at' => parent::format_date()
            ))
                ->where(array(
                    'master_lab.uid' => '= ?',
                    'AND',
                    'master_lab.deleted_at' => 'IS NULL'
                ), array(
                    $uid
                ))
                ->execute();
        }


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
					$uid,
					$UserData['data']->uid,
					'master_lab',
					'U',
					json_encode($old_value),
					json_encode($parameter),
					parent::format_date(),
					'N',
					$UserData['data']->log_id
				),
				'class' => __CLASS__
			));

			$oldKategoriItem = self::$query->select('master_lab_kategori_item', array(
				'id',
				'lab',
				'kategori'
			))
			->where(array(
				'master_lab_kategori_item.lab' => '= ?'
			), array(
				$uid
			))
			->execute();

			//Kategori Item
			$kategoriAdded = array();
			$queryList = array();
			foreach ($oldKategoriItem['response_data'] as $key => $value) {
				if(!in_array($value['kategori'], $kategoriAdded)) {
					array_push($kategoriAdded, $value['kategori']);
				}

				if(!in_array($value['kategori'], $parameter['kategori'])) {
					$delete_kategori_item = self::$query->update('master_lab_kategori_item', array(
						'deleted_at' => parent::format_date()
					))
					->where(array(
						'master_lab_kategori_item.id' => '= ?'
					), array(
						$value['id']
					))
					->execute();
				} else {
					$activate_kategori_item = self::$query->update('master_lab_kategori_item', array(
						'deleted_at' => NULL
					))
					->where(array(
						'master_lab_kategori_item.id' => '= ?'
					), array(
						$value['id']
					))
					->execute();
					array_push($queryList, $activate_kategori_item);
				}
			}

			foreach ($parameter['kategori'] as $key => $value) {
				if(!in_array($value, $kategoriAdded)) {
					$newKategori = self::$query->insert('master_lab_kategori_item', array(
						'lab' => $uid,
						'kategori' => $value,
						'created_at' => parent::format_date(),
						'updated_at' => parent::format_date()
					))
					->execute();
				}
			}

			

			//Lokasi Item
			/*foreach ($parameter['lokasi'] as $key => $value) {
				$worker = self::$query->insert('master_lab_lokasi_item', array(
					'lab' => $uid,
					'lokasi' => $value,
					'created_at' => parent::format_date(),
					'updated_at' => parent::format_date()
				))
				->execute();

				if($worker['response_result'] > 0) {
					//
				}
			}*/

			//Reset Lab Item
			$resetLabNilai = self::$query->update('master_lab_nilai', array(
				'deleted_at' => parent::format_date()
			))
			->where(array(
				'master_lab_nilai.lab' => '= ?'
			), array(
				$uid
			))
			->execute();

			//Nilai Item
			foreach ($parameter['nilai'] as $key => $value) {
				$worker = self::$query->insert('master_lab_nilai', array(
					'lab' => $uid,
					'satuan' => $value['satuan'],
					'nilai_maks' => $value['max'],
					'nilai_min' => $value['min'],
					'keterangan' => $value['keterangan'],
					'created_at' => parent::format_date(),
					'updated_at' => parent::format_date()
				))
				->returning('id')
				->execute();

				if($worker['response_result'] > 0) {
					$log = parent::log(array(
						'type' => 'activity',
						'column' => array(
							'unique_target',
							'user_uid',
							'table_name',
							'action',
							'new_value',
							'logged_at',
							'status',
							'login_id'
						),
						'value' => array(
							$worker['response_unique'],
							$UserData['data']->uid,
							'master_lab_nilai',
							'I',
							json_encode($value),
							parent::format_date(),
							'N',
							$UserData['data']->log_id
						),
						'class' => __CLASS__
					));
				}

				array_push($queryList, $worker);
			}
			

			$checkTindakan = self::$query->select('master_tindakan', array(
			    'uid'
            ))
                ->where(array(
                    'master_tindakan.uid' => '= ?',
                    'AND',
                    'master_tindakan.deleted_at' => 'IS NULL'
                ), array(
                    $uid
                ))
            ->execute();

			if(count($checkTindakan['response_data']) > 0)
            {
                $tindakan = self::$query->update('master_tindakan', array(
                    'nama' => 'Laboratorium ' . $parameter['nama'],
                    'updated_at' => parent::format_date(),
                    'kelompok' => 'LAB'
                ))
                    ->where(array(
                        'master_tindakan.uid' => '= ?',
                        'AND',
                        'master_tindakan.deleted_at' => 'IS NULL'
                    ), array(
                        $uid
                    ))
                    ->execute();
            } else {
                $tindakan = self::$query->insert('master_tindakan', array(
                    'uid' => $uid,
                    'nama' => 'Laboratorium ' . $parameter['nama'],
                    'kelompok' => 'LAB',
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
            }
			//New Tindakan


			if($tindakan['response_result'] > 0) {
				$log = parent::log(array(
					'type' => 'activity',
					'column' => array(
						'unique_target',
						'user_uid',
						'table_name',
						'action',
						'new_value',
						'logged_at',
						'status',
						'login_id'
					),
					'value' => array(
						$uid,
						$UserData['data']->uid,
						'master_tindakan',
						'I',
						json_encode($parameter),
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class' => __CLASS__
				));
			}

			//Penjamin Item
			/*foreach ($parameter['penjamin'] as $key => $value) {
				//Tindakan Harga
				$tindakan_harga = self::$query->insert('master_poli_tindakan_penjamin', array(
					'harga' => $value['harga'],
					'uid_penjamin' => $value['penjamin'],
					'uid_tindakan' => $uid,
					'uid_poli' => 'cd9f8f30-4236-2d8b-46d6-b561f9b2c5a3',//Laboratorium
					'created_at' => parent::format_date(),
					'updated_at' => parent::format_date()
				))
				->execute();

				if($tindakan_harga['response_result'] > 0) {
					$log = parent::log(array(
						'type' => 'activity',
						'column' => array(
							'unique_target',
							'user_uid',
							'table_name',
							'action',
							'new_value',
							'logged_at',
							'status',
							'login_id'
						),
						'value' => array(
							$uid,
							$UserData['data']->uid,
							'master_poli_tindakan_penjamin',
							'I',
							json_encode($parameter),
							parent::format_date(),
							'N',
							$UserData['data']->log_id
						),
						'class' => __CLASS__
					));
				}
			}*/
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

			if($parameter[6] == 'master_lab') {
				//delete kategori item also
				$delete_kategori = self::$query->update('master_lab_kategori_item', array(
					'deleted_at' => parent::format_date()
				))
				->where(array(
					'master_lab_kategori_item.lab' => '= ?'
				), array(
					$parameter[7]
				))
				->execute();

				//delete lokasi item also
				/*$delete_lokasi = self::$query->update('master_lab_lokasi_item', array(
					'deleted_at' => parent::format_date()
				))
				->where(array(
					'master_lab_lokasi_item.lab' => '= ?'
				), array(
					$parameter[7]
				))
				->execute();*/

				//delete penjamin item also
				$delete_kategori = self::$query->update('master_poli_tindakan_penjamin', array(
					'deleted_at' => parent::format_date()
				))
				->where(array(
					'master_poli_tindakan_penjamin.uid_tindakan' => '= ?'
				), array(
					$parameter[7]
				))
				->execute();

				//delete nilai also
				$delete_kategori = self::$query->update('master_lab_nilai', array(
					'deleted_at' => parent::format_date()
				))
				->where(array(
					'master_lab_nilai.lab' => '= ?'
				), array(
					$parameter[7]
				))
				->execute();

			} elseif ($parameter[6] == 'lab_order'){

				$orderDetail = self::$query
					->delete('lab_order_detail')
					->where(
						array(
							'lab_order_detail.lab_order' => '= ?'
						), array(
							$parameter[7]
						)
					)
					->execute();

				$orderNilai = self::$query
					->delete('lab_order_nilai')
					->where(
						array(
							'lab_order_nilai.lab_order' => '= ?'
						), array(
							$parameter[7]
						)
					)
					->execute();
			}
		}

		return $worker;
	}


	/*==================== FUNCTION FOR PROCCESS LAB DATA =====================*/
	private function get_antrian($parameter = 'P'){
		$data = self::$query
				->select('lab_order', 
					array(
						'uid',
						'asesmen as uid_asesmen',
						'waktu_order'
					)
				)
				->join('asesmen', array(
						'antrian as uid_antrian'
					)
				)
				->join('antrian', array(
						'pasien as uid_pasien',
						'dokter as uid_dokter',
						'departemen as uid_poli',
						'penjamin as uid_penjamin',
						'waktu_masuk'
					)
				)
				->join('pasien', array(
						'nama as pasien',
						'no_rm'
					)
				)
				->join('master_poli', array(
						'nama as departemen'
					)
				)
				->join('pegawai', array(
						'nama as dokter'
					)
				)
				->join('master_penjamin', array(
						'nama as penjamin'
					)
				)
				->join('kunjungan', array(
						'pegawai as uid_resepsionis'
					)
				)
				->on(array(
						array('lab_order.asesmen', '=', 'asesmen.uid'),
						array('asesmen.antrian','=','antrian.uid'),
						array('pasien.uid','=','antrian.pasien'),
						array('master_poli.uid','=','antrian.departemen'),
						array('pegawai.uid','=','antrian.dokter'),
						array('master_penjamin.uid','=','antrian.penjamin'),
						array('kunjungan.uid','=','antrian.kunjungan')
					)
				)
				->where(array(
						'lab_order.status'	=> '= ?',
						'AND',
						'lab_order.deleted_at' 		=> 'IS NULL'
					), array(
                        $parameter
					)
				)
				->order(
					array(
						'lab_order.waktu_order' => 'DESC'
					)
				)
				->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['waktu_order'] = date('d F Y', strtotime($value['waktu_order'])) . ' - [' . date('H:i', strtotime($value['waktu_order'])) . ']';
			$autonum++;
		}

		return $data;
	}

    private function get_antrian_backend($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if($UserData['data']->jabatan === __UIDDOKTER__) {
            if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                if($parameter['mode'] == 'history')
                {
                    $paramData = array(
                        'lab_order.dr_penanggung_jawab' => '= ?',
                        'AND',
                        'lab_order.deleted_at' => 'IS NULL',
                        'AND',
                        'lab_order.created_at' => 'BETWEEN ? AND ?',
                        'AND',
                        'lab_order.status' => '= ?'
                    );
                    $paramValue = array($UserData['data']->uid, $parameter['from'], $parameter['to'], $parameter['status']);
                }
                else
                {
                    $paramData = array(
                        'lab_order.dr_penanggung_jawab' => '= ?',
                        'AND',
                        'lab_order.deleted_at' => 'IS NULL',
                        'AND',
                        'lab_order.status' => '= ?'
                    );
                    $paramValue = array($UserData['data']->uid, $parameter['status']);
                }
            } else {
                if($parameter['mode'] == 'history')
                {
                    $paramData = array(
                        'lab_order.dr_penanggung_jawab' => '= ?',
                        'AND',
                        'lab_order.deleted_at' => 'IS NULL',
                        'AND',
                        '(lab_order.no_order' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                        'OR',
                        'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                        'OR',
                        'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                        'AND',
                        'lab_order.created_at' => 'BETWEEN ? AND ?',
                        'AND',
                        'lab_order.status' => '= ?'
                    );
                    $paramValue = array($UserData['data']->uid, $parameter['from'], $parameter['to'], $parameter['status']);
                }
                else {
                    $paramData = array(
                        'lab_order.dr_penanggung_jawab' => '= ?',
                        'AND',
                        'lab_order.deleted_at' => 'IS NULL',
                        'AND',
                        '(lab_order.no_order' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                        'OR',
                        'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                        'OR',
                        'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                        'AND',
                        'lab_order.status' => '= ?'
                    );
                    $paramValue = array($UserData['data']->uid, $parameter['status']);
                }
            }
        } else { //Jika Bukan Dokter
            if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                if($parameter['mode'] == 'history')
                {
                    $paramData = array(
                        'lab_order.deleted_at' => 'IS NULL',
                        'AND',
                        'lab_order.created_at' => 'BETWEEN ? AND ?',
                        'AND',
                        'lab_order.status' => '= ?'
                    );
                    $paramValue = array($parameter['from'], $parameter['to'], $parameter['status']);
                }
                else
                {
                    $paramData = array(
                        'lab_order.deleted_at' => 'IS NULL',
                        'AND',
                        'lab_order.status' => '= ?'
                    );
                    $paramValue = array($parameter['status']);
                }
            } else {
                if($parameter['mode'] == 'history')
                {
                    $paramData = array(
                        'lab_order.deleted_at' => 'IS NULL',
                        'AND',
                        '(lab_order.no_order' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                        'OR',
                        'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                        'OR',
                        'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                        'AND',
                        'lab_order.created_at' => 'BETWEEN ? AND ?',
                        'AND',
                        'lab_order.status' => '= ?'
                    );
                    $paramValue = array($parameter['from'], $parameter['to'], $parameter['status']);
                }
                else {
                    $paramData = array(
                        'lab_order.deleted_at' => 'IS NULL',
                        'AND',
                        '(lab_order.no_order' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                        'OR',
                        'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                        'OR',
                        'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                        'AND',
                        'lab_order.status' => '= ?'
                    );
                    $paramValue = array($parameter['status']);
                }
            }
        }

        if ($parameter['length'] < 0) {
            $data = self::$query
                ->select('lab_order',
                    array(
                        'uid',
                        'asesmen as uid_asesmen',
                        'waktu_order'
                    )
                )
                ->join('asesmen', array(
                        'antrian as uid_antrian'
                    )
                )
                ->join('antrian', array(
                        'pasien as uid_pasien',
                        'dokter as uid_dokter',
                        'departemen as uid_poli',
                        'penjamin as uid_penjamin',
                        'waktu_masuk'
                    )
                )
                ->join('pasien', array(
                        'nama as pasien',
                        'no_rm'
                    )
                )
                ->join('master_poli', array(
                        'nama as departemen'
                    )
                )
                ->join('pegawai', array(
                        'nama as dokter'
                    )
                )
                ->join('master_penjamin', array(
                        'nama as penjamin'
                    )
                )
                ->join('kunjungan', array(
                        'pegawai as uid_resepsionis'
                    )
                )
                ->on(array(
                        array('lab_order.asesmen', '=', 'asesmen.uid'),
                        array('asesmen.antrian','=','antrian.uid'),
                        array('pasien.uid','=','antrian.pasien'),
                        array('master_poli.uid','=','antrian.departemen'),
                        array('pegawai.uid','=','antrian.dokter'),
                        array('master_penjamin.uid','=','antrian.penjamin'),
                        array('kunjungan.uid','=','antrian.kunjungan')
                    )
                )
                ->where($paramData, $paramValue)
                ->order(
                    array(
                        'lab_order.waktu_order' => 'DESC'
                    )
                )
                ->execute();
        } else {
            $data = self::$query
                ->select('lab_order',
                    array(
                        'uid',
                        'asesmen as uid_asesmen',
                        'waktu_order'
                    )
                )
                ->join('asesmen', array(
                        'antrian as uid_antrian'
                    )
                )
                ->join('antrian', array(
                        'pasien as uid_pasien',
                        'dokter as uid_dokter',
                        'departemen as uid_poli',
                        'penjamin as uid_penjamin',
                        'waktu_masuk'
                    )
                )
                ->join('pasien', array(
                        'nama as pasien',
                        'no_rm'
                    )
                )
                ->join('master_poli', array(
                        'nama as departemen'
                    )
                )
                ->join('pegawai', array(
                        'nama as dokter'
                    )
                )
                ->join('master_penjamin', array(
                        'nama as penjamin'
                    )
                )
                ->join('kunjungan', array(
                        'pegawai as uid_resepsionis'
                    )
                )
                ->on(array(
                        array('lab_order.asesmen', '=', 'asesmen.uid'),
                        array('asesmen.antrian','=','antrian.uid'),
                        array('pasien.uid','=','antrian.pasien'),
                        array('master_poli.uid','=','antrian.departemen'),
                        array('pegawai.uid','=','antrian.dokter'),
                        array('master_penjamin.uid','=','antrian.penjamin'),
                        array('kunjungan.uid','=','antrian.kunjungan')
                    )
                )
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->order(
                    array(
                        'lab_order.waktu_order' => 'DESC'
                    )
                )
                ->execute();
        }



        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $hasNilai = false;
            //Check Nilai
            $checkNilai = self::$query->select('lab_order_nilai', array(
                'id', 'nilai'
            ))
                ->where(array(
                    'lab_order_nilai.lab_order' => '= ?'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach ($checkNilai['response_data'] as $NKey => $NValue) {
                if(!is_null($NValue['nilai'])) {
                    $hasNilai = true;
                } else {
                    if(!$hasNilai) {
                        $hasNilai = false;
                    }
                }
            }
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['has_nilai'] = $hasNilai;
            //$data['response_data'][$key]['tgl_ambil_sample_parse'] = date('d F Y', strtotime($value['tgl_ambil_sample']));
            $data['response_data'][$key]['waktu_order'] = date('d F Y', strtotime($value['waktu_order'])) . ' - [' . date('H:i', strtotime($value['waktu_order'])) . ']';

            //Check Detail

            $autonum++;
        }

        $itemTotal = self::$query->select('lab_order', array(
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

	/*------------------- GET DATA PASIEN and ANTRIAN --------------------*/
	private function get_data_pasien_antrian($parameter){
		$get_uid_asesmen = self::$query
			->select('lab_order', array(
					'asesmen',
                    'kesan',
                    'anjuran',
                    'tanggal_sampling'
				)
			)
			->where(
				array(
					'lab_order.uid' => '= ?'
				),
				array($parameter)
			)
			->execute();

		$result = "";
		if ($get_uid_asesmen['response_result'] > 0){
			$get_uid_antrian = self::$query
				->select('asesmen', array('antrian'))
				->where(array('asesmen.uid' => '= ?'), 
					array($get_uid_asesmen['response_data'][0]['asesmen']))
				->execute();

			$uid_antrian = $get_uid_antrian['response_data'][0]['antrian'];

			$antrian = new Antrian(self::$pdo);
			$result = $antrian->get_data_pasien_dan_antrian($uid_antrian);	//call function for get data antrian and 
																			//pasien in class antrian

		}
		$result['laboratorium'] = $get_uid_asesmen['response_data'][0];
		return $result;
	}



	private function get_laboratorium_order_detail_item($parameter){
		$data = self::$query
			->select('lab_order_detail', array(
					'id',
					'lab_order',
					'request_item',
					'tgl_ambil_sample',
					'tindakan',
                    'mitra'
				)
			)
			->where(array(
					'lab_order_detail.lab_order' => '= ?',
					'AND',
					'lab_order_detail.deleted_at' => 'IS NULL'
				), array(
				    $parameter
                )
			)
			->execute();
		$Mitra = new Mitra(self::$pdo);
		foreach ($data['response_data'] as $key => $value){
			$data_lab = self::get_lab_detail_data_only($value['tindakan']);
			$data['response_data'][$key]['kode'] = $data_lab['response_data'][0]['kode'];
			$data['response_data'][$key]['nama'] = $data_lab['response_data'][0]['nama'];
            $data['response_data'][$key]['naratif'] = $data_lab['response_data'][0]['naratif'];
            $data['response_data'][$key]['mitra'] = $Mitra->get_mitra_detail($value['mitra'])['response_data'][0];
			$data['response_data'][$key]['tgl_ambil_sample_parse'] = date('d F Y', strtotime($value['tgl_ambil_sample']));
            $data['response_data'][$key]['allow'] = ($value['tgl_ambil_sample'] <= date('Y-m-d')) ? true : false;
			
			$data['response_data'][$key]['nilai_item'] = [];
			$data_nilai = self::get_laboratorium_order_nilai_item($value['tindakan'], $value['lab_order']);
			$data['response_data'][$key]['nilai_item'] = $data_nilai['response_data'];
			
		}

		return $data;
	}

	public static function get_lab_detail_data_only($parameter){
		$data_lab = self::$query
			->select('master_lab', array(
					'uid',
					'kode',
					'nama',
                    'naratif'
				)
			)
			->where(
				array('master_lab.uid' => '= ?')
				, array($parameter)
			)
			->execute();
		
			return $data_lab;
	}

	private static function get_laboratorium_order_nilai_item($uid_tindakan, $uid_order){
		$data = self::$query
			->select('lab_order_nilai', array(
					'id',
					'lab_order as uid_lab_order',
					'tindakan as uid_tindakan',
					'id_lab_nilai',
					'nilai'
				)
			)
			->join('master_lab_nilai', 
				array(
					'keterangan',
					'nilai_min',
					'nilai_maks',
					'satuan'
				)
			)
			->on(array(
					array('master_lab_nilai.id', '=', 'lab_order_nilai.id_lab_nilai')
				)
			)
			->where(array(
					'lab_order_nilai.tindakan' => '= ?',
					'AND',
					'lab_order_nilai.lab_order' => '= ?',
					'AND',
					'lab_order_nilai.deleted_at' => 'IS NULL'
				), array(
					$uid_tindakan,
					$uid_order
				)
			)
			->execute();

		return $data;
	}

	public static function get_laboratorium_order_nilai_per_item($lab_order, $tindakan, $id_nilai){
		$data = self::$query
			->select('lab_order_nilai', array(
					'id',
					'lab_order',
					'tindakan',
					'id_lab_nilai',
					'nilai'
				)
			)
			->where(array(
					'lab_order_nilai.lab_order' => '= ?',
					'AND',
					'lab_order_nilai.tindakan' => '= ?',
					'AND',
					'lab_order_nilai.id_lab_nilai' => '= ?',
					'AND',
					'lab_order_nilai.deleted_at' => 'IS NULL'
				), array(
					$lab_order,
					$tindakan,
					$id_nilai
				)
			)
			->execute();

		return $data;
	}

	private function get_laboratorium_lampiran($parameter){
		$data = self::$query
			->select('lab_order_document', array(
					'id', 	
					'lab_order',
					'lampiran',
					'created_at'
				)
			)
			->where(array(
					'lab_order_document.lab_order' => '= ?',
					'AND',
					'lab_order_document.deleted_at' => 'IS NULL'
				), array($parameter)
			)
			->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['file_location'] = '../document/laboratorium/' . $parameter . '/' . $value['lampiran'];
			$autonum++;
		}

		return $data;
	}

	// private function add_order_lab($parameter){
	// 	$Authorization = new Authorization();
	// 	$UserData = $Authorization::readBearerToken($parameter['access_token']);

	// 	$get_antrian = new Antrian(self::$pdo);
	// 	$antrian = $get_antrian->get_antrian_detail('antrian', $parameter['uid_antrian']);
		
	// 	$result = [];
	// 	if ($antrian['response_result'] > 0){

	// 		$data_antrian = $antrian['response_data'][0];		//get antrian data

	// 		//get uid asesmmen based by antrian data
	// 		$get_asesmen = self::$query->select('asesmen', array('uid'))
	// 			->where(array(
	// 					'asesmen.deleted_at' => 'IS NULL',
	// 					'AND',
	// 					'asesmen.poli' => '= ?',
	// 					'AND',
	// 					'asesmen.kunjungan' => '= ?',
	// 					'AND',
	// 					'asesmen.antrian' => '= ?',
	// 					'AND',
	// 					'asesmen.pasien' => '= ?',
	// 					'AND',
	// 					'asesmen.dokter' => '= ?'
	// 				), array(
	// 					$data_antrian['departemen'],
	// 					$data_antrian['kunjungan'],
	// 					$parameter['uid_antrian'],
	// 					$data_antrian['pasien'],
	// 					$data_antrian['dokter']
	// 				)
	// 			)
	// 			->execute();
			
	// 		$uidLabOrder = "";
	// 		$statusOrder = "NEW";	//parameter to set status order, set "NEW" for default
	// 		if ($get_asesmen['response_result'] > 0){
	// 			$uidAsesmen = $get_asesmen['response_data'][0]['uid'];

	// 			$checkLabOrder = self::$query
	// 				->select('lab_order', array('uid'))
	// 				->where(
	// 					array(
	// 						'lab_order.asesmen'		=>	'= ?',
	// 						'AND',
	// 						'lab_order.deleted_at'	=>	'IS NULL'
	// 					)
	// 					, array($uidAsesmen)
	// 				)
	// 				->execute();
				
	// 			if ($checkLabOrder['response_result'] > 0){
	// 				$statusOrder = "OLD";		//$statusOrder will set "OLD" if has ever added
	// 				$uidLabOrder = $checkLabOrder['response_data'][0]['uid'];

	// 				$result['old_lab_order'] = $checkLabOrder;
	// 			}

	// 		} else {
	// 			//new asesmen
	// 			$uidAsesmen = parent::gen_uuid();
	// 			$MasterUID = $uidAsesmen;
	// 			$asesmen_poli = self::$query
	// 				->insert('asesmen', 
	// 					array(
	// 						'uid'			=> $uidAsesmen,
	// 						'poli'			=> $data_antrian['departemen'],
	// 						'kunjungan' 	=> $data_antrian['kunjungan'],
	// 						'antrian'		=> $parameter['uid_antrian'],
	// 						'pasien'		=> $data_antrian['pasien'],
	// 						'dokter'		=> $data_antrian['dokter'],
	// 						'created_at'	=> parent::format_date(),
	// 						'updated_at'	=> parent::format_date()
	// 					)
	// 				)
	// 				->execute();
				
	// 			if($asesmen_poli['response_result'] > 0) {

	// 				$log = parent::log(array(
	// 					'type'=>'activity',
	// 					'column'=>array(
	// 						'unique_target',
	// 						'user_uid',
	// 						'table_name',
	// 						'action',
	// 						'logged_at',
	// 						'status',
	// 						'login_id'
	// 					),
	// 					'value'=>array(
	// 						$uidAsesmen,
	// 						$UserData['data']->uid,
	// 						'asesmen',
	// 						'I',
	// 						parent::format_date(),
	// 						'N',
	// 						$UserData['data']->log_id
	// 					),
	// 					'class'=>__CLASS__
	// 				));
					
	// 				$result['new_asesmen'] = $asesmen_poli;
	// 			}
	// 		}

	// 		if ($statusOrder == "NEW"){
	// 			$tahun = date('Y');
	// 			$thn = substr($tahun,-2);

	// 			$no_order = "LO" . $thn;

	// 			$dataMax = self::$query
	// 				->select('lab_order', array(
	// 						'MAX(no_order) as no_order'	
	// 					)
	// 				)
	// 				->where(array(
	// 						'lab_order.deleted_at' => 'IS NULL'
	// 					)
	// 				)
	// 				->execute();
				
	// 			$no_order_before = substr($dataMax['response_data'][0]['no_order'],0,4);

	// 			if($no_order_before == $no_order){
	// 				$no_urut = (int) substr($dataMax['response_data'][0]['no_order'],4,6);
	// 				$no_urut++;
	// 				$no_order_new = $no_order_before.sprintf("%06s", $no_urut);
	// 			}
	// 			else{
	// 				$no_urut_baru = $no_order.sprintf("%06s",1);
	// 			}
					
	// 			$uidLabOrder = parent::gen_uuid();
	// 			$labOrder = self::$query
	// 				->insert('lab_order', 
	// 					array(
	// 						'uid'			=>	$uidLabOrder,
	// 						'asesmen'		=>	$uidAsesmen,
	// 						'waktu_order'	=>	parent::format_date(),
	// 						'selesai'		=>	'false',
	// 						'dr_pengirim'	=>	$UserData['data']->uid,
	// 						'created_at'	=>	parent::format_date(),
	// 						'updated_at'	=>	parent::format_date()
	// 					)
	// 				)
	// 				->execute();
				
	// 			if($labOrder['response_result'] > 0) {

	// 				$log = parent::log(array(
	// 					'type'=>'activity',
	// 					'column'=>array(
	// 						'unique_target',
	// 						'user_uid',
	// 						'table_name',
	// 						'action',
	// 						'logged_at',
	// 						'status',
	// 						'login_id'
	// 					),
	// 					'value'=>array(
	// 						$uidLabOrder,
	// 						$UserData['data']->uid,
	// 						'lab_order',
	// 						'I',
	// 						parent::format_date(),
	// 						'N',
	// 						$UserData['data']->log_id
	// 					),
	// 					'class'=>__CLASS__
	// 				));
					
					
	// 			}

	// 			$result['new_lab_order'] = $labOrder;
	// 		}
			
	// 		//check if uid labOrder has no empty and add tindakan
	// 		if ($uidLabOrder != ""){

	// 			/*	KETERANGAN
	// 				format json listTindakan:
	// 				listTindakan : { 
	// 					uid_tindakan_1 : uid_penjamin_1,
	// 					uid_tinadkan_2 : uid_penjamin_2 
	// 				}
	// 			*/
	// 			foreach ($parameter['listTindakan'] as $keyTindakan => $valueTindakan) {
	// 				$checkDetailLabor = self::$query
	// 					->select('lab_order_detail', array('id'))
	// 					->where(
	// 						array(
	// 							'lab_order_detail.lab_order'	=> '= ?',
	// 							'AND',
	// 							'lab_order_detail.tindakan'		=> '= ?',
	// 							'AND',
	// 							'lab_order_detail.deleted_at'	=> 'IS NULL'
	// 						), array(
	// 							$uidLabOrder,
	// 							$keyTindakan
	// 						)
	// 					)
	// 					->execute();
					
	// 				if ($checkDetailLabor['response_result'] == 0){
	// 					$addDetailLabor = self::$query
	// 						->insert('lab_order_detail', 
	// 							array(
	// 								'lab_order'		=>	$uidLabOrder,
	// 								'tindakan'		=>	$keyTindakan,
	// 								'penjamin'		=>	$valueTindakan,
	// 								'created_at'	=>	parent::format_date(),
	// 								'updated_at'	=>	parent::format_date()	
	// 							)
	// 						)
	// 						->execute();

	// 					if ($addDetailLabor['response_result'] > 0){
	// 						$log = parent::log(array(
	// 							'type'=>'activity',
	// 							'column'=>array(
	// 								'unique_target',
	// 								'user_uid',
	// 								'table_name',
	// 								'action',
	// 								'logged_at',
	// 								'status',
	// 								'login_id'
	// 							),
	// 							'value'=>array(
	// 								$uidLabOrder . "; ". $keyTindakan,
	// 								$UserData['data']->uid,
	// 								'lab_order_detail',
	// 								'I',
	// 								parent::format_date(),
	// 								'N',
	// 								$UserData['data']->log_id
	// 							),
	// 							'class'=>__CLASS__
	// 						));
							
	// 						$result['new_lab_detail'] = $addDetailLabor;
	// 						$getNilaiTindakanLabor = self::get_lab_detail($keyTindakan);
	
	// 						if ($getNilaiTindakanLabor['response_result'] > 0){
	// 							$nilaiLabor = $getNilaiTindakanLabor['response_data'][0]['nilai'];
								
	// 							foreach ($nilaiLabor as $keyNilai => $valueNilai) {
									
	// 								$getAvailableNilai = self::$query
	// 									->select('lab_order_nilai', array('id'))
	// 									->where(
	// 										array(
	// 											'lab_order_nilai.lab_order' 	=>	'= ?',
	// 											'AND',
	// 											'lab_order_nilai.tindakan'		=>	'= ?',
	// 											'AND',
	// 											'lab_order_nilai.id_lab_nilai'	=>	'= ?',
	// 											'AND',
	// 											'lab_order_nilai.deleted_at'	=>	'IS NULL'
	// 										), array(
	// 											$uidLabOrder,
	// 											$keyTindakan,
	// 											$valueNilai['id']
	// 										)
	// 									)
	// 									->execute();
									
	// 								//check if nilai_lab never added
	// 								if ($getAvailableNilai['response_result'] == 0){  
	// 									$addNilaiLabor = self::$query
	// 										->insert('lab_order_nilai', 
	// 											array(
	// 												'lab_order'		=>	$uidLabOrder,
	// 												'tindakan'		=>	$keyTindakan,
	// 												'id_lab_nilai'	=>	$valueNilai['id'],
	// 												'created_at'	=>	parent::format_date(),
	// 												'updated_at'	=>	parent::format_date()
	// 											)
	// 										)
	// 										->execute();

	// 									if ($addNilaiLabor['response_result'] > 0){
	// 										$log = parent::log(array(
	// 											'type'=>'activity',
	// 											'column'=>array(
	// 												'unique_target',
	// 												'user_uid',
	// 												'table_name',
	// 												'action',
	// 												'logged_at',
	// 												'status',
	// 												'login_id'
	// 											),
	// 											'value'=>array(
	// 												$uidLabOrder . "; ". $keyTindakan,
	// 												$UserData['data']->uid,
	// 												'lab_order_nilai',
	// 												'I',
	// 												parent::format_date(),
	// 												'N',
	// 												$UserData['data']->log_id
	// 											),
	// 											'class'=>__CLASS__
	// 										));

	// 										$result['new_lab_nilai'] = $addNilaiLabor;
	// 									}
	// 								}
	
	// 							}
	
	// 						}
	
	// 					}
	// 				}

	// 			}
				
	// 		}
	// 	}

	// 	return $result;
	// }

	private function load_order_lab($parameter){
		$data = self::$query
			->select('lab_order', array(
					'uid',
					'no_order',
					'dr_penanggung_jawab',
					'created_at',
					'updated_at'	
				)
			)
			->where(
				array(
					'lab_order.deleted_at' => 'IS NULL'
				)
			)
			->execute();
	}

	private function load_detail_order_lab($parameter){
		$data = "";
	}

	private function new_order_lab($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
		
		$get_antrian = new Antrian(self::$pdo);
		$antrian = $get_antrian->get_antrian_detail('antrian', $parameter['uid_antrian']);
		
		$result = [];
		$result['response_result'] = 0;
		if ($antrian['response_result'] > 0) {

			$data_antrian = $antrian['response_data'][0];		//get antrian data

			//get uid asesmmen based by antrian data
			$get_asesmen = self::$query->select('asesmen', array('uid'))
				->where(array(
						'asesmen.deleted_at' => 'IS NULL',
						'AND',
						'asesmen.poli' => '= ?',
						'AND',
						'asesmen.kunjungan' => '= ?',
						'AND',
						'asesmen.antrian' => '= ?',
						'AND',
						'asesmen.pasien' => '= ?',
						'AND',
						'asesmen.dokter' => '= ?'
					), array(
						$data_antrian['departemen'],
						$data_antrian['kunjungan'],
						$parameter['uid_antrian'],
						$data_antrian['pasien'],
						$data_antrian['dokter']
					)
				)
				->execute();
			
			if ($get_asesmen['response_result'] > 0){
				$uidAsesmen = $get_asesmen['response_data'][0]['uid'];
				$result['old_asesmen'] = $get_asesmen['response_data'][0];
        
			} else {
				//new asesmen
				$uidAsesmen = parent::gen_uuid();
				$MasterUID = $uidAsesmen;
				$asesmen_poli = self::$query
					->insert('asesmen', 
						array(
							'uid'			=> $uidAsesmen,
							'poli'			=> $data_antrian['departemen'],
							'kunjungan' 	=> $data_antrian['kunjungan'],
							'antrian'		=> $parameter['uid_antrian'],
							'pasien'		=> $data_antrian['pasien'],
							'dokter'		=> $data_antrian['dokter'],
							'created_at'	=> parent::format_date(),
							'updated_at'	=> parent::format_date()
						)
					)
					->execute();
				
				if($asesmen_poli['response_result'] > 0) {

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
							$uidAsesmen,
							$UserData['data']->uid,
							'asesmen',
							'I',
							parent::format_date(),
							'N',
							$UserData['data']->log_id
						),
						'class'=>__CLASS__
					));
					
					$result['new_asesmen'] = $asesmen_poli;
				}
			}

			if ($uidAsesmen != ""){
                $lastNumber = self::$query->select('lab_order', array(
                    'no_order'
                ))
                    ->where(array(
                        'EXTRACT(month FROM created_at)' => '= ?'
                    ), array(
                        intval(date('m'))
                    ))
                    ->execute();
					
				$uidLabOrder = parent::gen_uuid();
				$labOrder = self::$query
					->insert('lab_order', 
						array(
							'uid'					=>	$uidLabOrder,
							'asesmen'				=>	$uidAsesmen,
							'waktu_order'			=>	parent::format_date(),
							'selesai'				=>	'false',
							'dr_pengirim'			=>	$UserData['data']->uid,
							//'dr_penanggung_jawab'	=>	$parameter['dokterPJ'],
							'no_order'				=>	'LO/' . date('Y/m') . '/' . str_pad(strval(count($lastNumber['response_data']) + 1), 4, '0', STR_PAD_LEFT),
							//'status'				=>	'P', Revisi Verifikator
                            'status'				=>	'N', // Dulu V
							'pasien'				=>	$data_antrian['pasien'],
							'kunjungan'				=>	$data_antrian['kunjungan'],
							'created_at'			=>	parent::format_date(),
							'updated_at'			=>	parent::format_date()
						)
					)
					->execute();
				
				if($labOrder['response_result'] > 0) {
					$result['response_result'] += 1;
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
							$uidLabOrder,
							$UserData['data']->uid,
							'lab_order',
							'I',
							parent::format_date(),
							'N',
							$UserData['data']->log_id
						),
						'class'=>__CLASS__
					));


					
					$result['new_lab_order'] = $labOrder;
					$status_lunas = 'P';

					foreach ($parameter['listTindakan'] as $keyTindakan => $valueTindakan) {
                        $orderItemID = array();
                        foreach ($valueTindakan['item'] as $LabItemKey => $LabItemValue)
                        {
                            array_push($orderItemID, intval($LabItemValue['id']));
                        }


						$checkDetailLabor = self::$query
							->select('lab_order_detail', array('id'))
							->where(
								array(
									'lab_order_detail.lab_order'	=> '= ?',
									'AND',
									'lab_order_detail.tindakan'		=> '= ?',
									'AND',
									'lab_order_detail.deleted_at'	=> 'IS NULL'
								), array(
									$uidLabOrder,
									$keyTindakan
								)
							)
							->execute();
						
						if ($checkDetailLabor['response_result'] == 0) {
							if ($valueTindakan['penjamin'] == __UIDPENJAMINUMUM__){
								$status_lunas = 'K';
							}

							$addDetailLabor = self::$query
								->insert('lab_order_detail', 
									array(
										'lab_order'		=>	$uidLabOrder,
										'tindakan'		=>	$keyTindakan,
										'request_item'  =>  implode(',',$orderItemID),
										'tgl_ambil_sample' => (($valueTindakan['tgl_sample'] === '') ? parent::format_date() : $valueTindakan['tgl_sample']),
										'penjamin'		=>	$valueTindakan['penjamin'],
										'created_at'	=>	parent::format_date(),
										'updated_at'	=>	parent::format_date()
									)
								)
								->execute();

							if ($addDetailLabor['response_result'] > 0) {
								//Check Invoice
								$Invoice = new Invoice(self::$pdo);
								$InvoiceCheck = self::$query->select('invoice', array(
									'uid'
								))
									->where(array(
										'invoice.kunjungan' => '= ?',
										'AND',
										'invoice.deleted_at' => 'IS NULL'
									), array(
										$data_antrian['kunjungan']
									))
									->execute();
	
								if (count($InvoiceCheck['response_data']) > 0) {
									$TargetInvoice = $InvoiceCheck['response_data'][0]['uid'];
								} else {
									$InvMasterParam = array(
										'kunjungan' => $data_antrian['kunjungan'],
										'pasien' => $data_antrian['pasien'],
										'keterangan' => 'Tagihan laboratorium'
									);
									$NewInvoice = $Invoice->create_invoice($InvMasterParam);
									$TargetInvoice = $NewInvoice['response_unique'];
								}
	
	
								$HargaTindakan = self::$query->select('master_tindakan_kelas_harga', array(
									'id',
									'tindakan',
									'kelas',
									'penjamin',
									'harga'
								))
									->where(array(
										'master_tindakan_kelas_harga.penjamin' => '= ?',
										'AND',
										'master_tindakan_kelas_harga.kelas' => '= ?',
										'AND',
										'master_tindakan_kelas_harga.tindakan' => '= ?',
										'AND',
										'master_tindakan_kelas_harga.deleted_at' => 'IS NULL'
									), array(
										$valueTindakan['penjamin'],
										__UID_KELAS_GENERAL_LAB__,    //Fix 1 harga kelas GENERAL
										$keyTindakan
									))
									->execute();
								$HargaFinal = (count($HargaTindakan['response_data']) > 0) ? $HargaTindakan['response_data'][0]['harga'] : 0;

								if($parameter['charge_invoice'] === 'Y') {
                                    $InvoiceDetail = $Invoice->append_invoice(array(
                                        'invoice' => $TargetInvoice,
                                        'item' => $keyTindakan,
                                        'item_origin' => 'master_tindakan',
                                        'qty' => 1,
                                        'harga' => $HargaFinal,
                                        //'status_bayar' => ($valueTindakan['penjamin'] == __UIDPENJAMINUMUM__) ? 'N' : 'Y', // Check Penjamin. Jika non umum maka langsung lunas
                                        'status_bayar' => ($valueTindakan['penjamin'] == __UIDPENJAMINUMUM__) ? 'V' : 'Y', // Check Penjamin. Jika non umum maka langsung lunas
                                        'subtotal' => $HargaFinal,
                                        'discount' => 0,
                                        'discount_type' => 'N',
                                        'pasien' => $data_antrian['pasien'],
                                        'penjamin' => $valueTindakan['penjamin'],
                                        'billing_group' => 'laboratorium',
                                        'keterangan' => 'Biaya Laboratorium'
                                    ));
                                }

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
										$uidLabOrder . "; ". $keyTindakan,
										$UserData['data']->uid,
										'lab_order_detail',
										'I',
										parent::format_date(),
										'N',
										$UserData['data']->log_id
									),
									'class'=>__CLASS__
								));
								
								$result['new_lab_detail'] = $addDetailLabor;
								$getNilaiTindakanLabor = self::get_nilai_tindakan($keyTindakan);

								if ($getNilaiTindakanLabor['response_result'] > 0){
									
									foreach (
										$getNilaiTindakanLabor['response_data'] as $keyNilai => $valueNilai
									){
										if(in_array(intval($valueNilai['id']),$orderItemID))
                                        {
                                            $addNilaiLabor = self::$query
                                                ->insert('lab_order_nilai',
                                                    array(
                                                        'lab_order'		=>	$uidLabOrder,
                                                        'tindakan'		=>	$keyTindakan,
                                                        'id_lab_nilai'	=>	$valueNilai['id'],
                                                        'created_at'	=>	parent::format_date(),
                                                        'updated_at'	=>	parent::format_date()
                                                    )
                                                )
                                                ->execute();

                                            if ($addNilaiLabor['response_result'] > 0){
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
                                                        $uidLabOrder . "; ". $keyTindakan,
                                                        $UserData['data']->uid,
                                                        'lab_order_nilai',
                                                        'I',
                                                        parent::format_date(),
                                                        'N',
                                                        $UserData['data']->log_id
                                                    ),
                                                    'class'=>__CLASS__
                                                ));

                                                $result['new_lab_nilai'] = $addNilaiLabor;
                                            }
                                        }
									}
		
								}
							}

						}

					}
					
					//update status order
					$updateStatusOrder = self::$query
						->update('lab_order', array(
								//'status'	=>	$status_lunas
                                'status'	=>	'N' // Dulu V
							)
						)
						->where(
							array(
								'lab_order.uid'			=>	'= ?',
								'AND',
								'lab_order.deleted_at'	=>	'IS NULL'
							), array(
								$uidLabOrder
							)
						)
						->execute();


					//update status antrian
                    $antrian_status = self::$query->update('antrian_nomor', array(
                        //'status' => ($data_antrian['penjamin'] === __UIDPENJAMINUMUM__) ? 'K' : 'P'
                        'status' => 'P'
                    ))
                        ->where(array(
                            'antrian_nomor.kunjungan' => '= ?',
                            'AND',
                            'antrian_nomor.antrian' => '= ?',
                            'AND',
                            'antrian_nomor.pasien' => '= ?'
                        ), array(
                            $data_antrian['kunjungan'],
                            $parameter['uid_antrian'],
                            $data_antrian['pasien']
                        ))
                        ->execute();





                    //Charge Biaya Lab
                    $ChargeLab = self::charge_invoice_item(array(
                        'uid' => $uidLabOrder,
                        'asesmen' => $uidAsesmen,
                        'kunjungan' => $data_antrian['kunjungan'],
                        'pasien' => $data_antrian['pasien'],
                        'departemen' => $data_antrian['departemen']
                    ));
				}
			}
		}

		return $result;
	}

	private function edit_order_lab($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
		
		$uidLabOrder = $parameter['uid_lab_order'];
		$old_detail = self::get_laboratorium_order_detail($uidLabOrder);
		$old_order = self::get_col_detail_order_lab($uidLabOrder);

		$result = [];

		$updateOrder = self::$query
			->update('lab_order', array(
					'dr_penanggung_jawab'	=>	$parameter['dokterPJ']
				)
			)
			->where(
				array(
					'lab_order.uid'			=>	'= ?',
					'AND',
					'lab_order.deleted_at'	=>	'IS NULL'
				), array(
					$uidLabOrder
				)
			)
			->execute();

		if ($updateOrder['response_result'] > 0){
			$result['lab_order'] = $updateOrder;

			$log = parent::log(
				array(
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
						$uidLabOrder,
						$UserData['data']->uid,
						'lab_order; lab_order_detail',
						'U',
						'lab_order: {' . json_encode($old_order) . '}; lab_order_detail: {' . json_encode($old_detail) . '}',
						json_encode($parameter),
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class'=>__CLASS__
				)
			);

			//delete all item first
			$deleteAllDetail = self::$query
				->delete('lab_order_detail')
				->where(
					array(
						'lab_order_detail.lab_order'	=> '= ?'
					), array(
						$uidLabOrder
					)
				)
				->execute();
			
			//delete all item first
			$deleteAllNilai = self::$query
				->delete('lab_order_nilai')
				->where(
					array(
						'lab_order_nilai.lab_order'	=> '= ?'
					), array(
						$uidLabOrder
					)
				)
				->execute();

			foreach ($parameter['listTindakan'] as $keyTindakan => $valueTindakan) {
				$checkDetailLabor = self::$query
					->select('lab_order_detail', array('id'))
					->where(
						array(
							'lab_order_detail.lab_order'	=> '= ?',
							'AND',
							'lab_order_detail.tindakan'		=> '= ?',
							'AND',
							'lab_order_detail.penjamin'		=> '= ?',
						), array(
							$uidLabOrder,
							$keyTindakan,
							$valueTindakan['penjamin']
						)
					)
					->execute();
				
				if ($checkDetailLabor['response_result'] > 0) {
					//set back
					$updateDetail = self::$query
						->update('lab_order_detail', array(
								'deleted_at' => NULL
							)
						)
						->where(
							array(
								'lab_order_detail.lab_order'	=> '= ?',
								'AND',
								'lab_order_detail.tindakan'		=> '= ?',
								'AND',
								'lab_order_detail.penjamin'		=> '= ?'
							), array(
								$uidLabOrder,
								$keyTindakan,
								$valueTindakan['penjamin']
							)
						)
						->execute();

						//if ($updateDetail['response_result'] > 0){
							$updateNilai = self::$query
								->update('lab_order_nilai', array(
										'deleted_at' => NULL
									)
								)
								->where(
									array(
										'lab_order_nilai.lab_order'	=> '= ?',
										'AND',
										'lab_order_nilai.tindakan'		=> '= ?'
									), array(
										$uidLabOrder,
										$keyTindakan
									)
								)
								->execute();
						//}

				} else {

					$addDetailLabor = self::$query
						->insert('lab_order_detail', 
							array(
								'lab_order'		=>	$uidLabOrder,
								'tindakan'		=>	$keyTindakan,
								'penjamin'		=>	$valueTindakan['penjamin'],
                                'tgl_ambil_sample' => (($valueTindakan['tgl_sample'] === '') ? parent::format_date() : $valueTindakan['tgl_sample']),
								'created_at'	=>	parent::format_date(),
								'updated_at'	=>	parent::format_date()	
							)
						)
						->execute();

					if ($addDetailLabor['response_result'] > 0){
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
								$uidLabOrder . "; ". $keyTindakan,
								$UserData['data']->uid,
								'lab_order_detail',
								'I',
								parent::format_date(),
								'N',
								$UserData['data']->log_id
							),
							'class'=>__CLASS__
						));
						
						$result['new_lab_detail'] = $addDetailLabor;
						$getNilaiTindakanLabor = self::get_nilai_tindakan($keyTindakan);

						if ($getNilaiTindakanLabor['response_result'] > 0){
							
							foreach (
								$getNilaiTindakanLabor['response_data'] as $keyNilai => $valueNilai
							) {
								 
								$addNilaiLabor = self::$query
									->insert('lab_order_nilai', 
										array(
											'lab_order'		=>	$uidLabOrder,
											'tindakan'		=>	$keyTindakan,
											'id_lab_nilai'	=>	$valueNilai['id'],
											'created_at'	=>	parent::format_date(),
											'updated_at'	=>	parent::format_date()
										)
									)
									->execute();

								if ($addNilaiLabor['response_result'] > 0){
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
											$uidLabOrder . "; ". $keyTindakan,
											$UserData['data']->uid,
											'lab_order_nilai',
											'I',
											parent::format_date(),
											'N',
											$UserData['data']->log_id
										),
										'class'=>__CLASS__
									));

									$result['new_lab_nilai'] = $addNilaiLabor;
								}
								
							}

						}

					}
				}

			}

		}

		return $updateNilai;
	}

	private function update_hasil_lab($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization->readBearerToken($parameter['access_token']);
		$result = [];
		$dataNilai = json_decode($parameter['data_nilai']);

		if (isset($parameter['uid_order'])) {

		    foreach($dataNilai as $key_tindakan => $value_tindakan) {
                foreach($value_tindakan as $key_nilai => $value_nilai){
					$old = self::get_laboratorium_order_nilai_per_item($parameter['uid_order'], $key_tindakan, $key_nilai);

					$DetailRequest = self::$query->select('lab_order_detail', array(
					    'id',
					    'request_item'
                    ))
                        ->where(array(
                            'lab_order_detail.lab_order' => '= ?',
                            'AND',
                            'lab_order_detail.tindakan' => '= ?',
                            'AND',
                            'lab_order_detail.deleted_at' => 'IS NULL'
                        ), array(
                            $parameter['uid_order'],
                            $key_tindakan
                        ))
                        ->execute();

					$DetailRequestSplitter = explode(',', $DetailRequest['response_data'][0]['request_item']);
					if(in_array(strval($key_nilai), $DetailRequestSplitter))
                    {
                        $checkData = self::$query->select('lab_order_nilai', array(
                            'id'
                        ))
                            ->where(array(
                                'lab_order_nilai.lab_order' 	=> '= ?',
                                'AND',
                                'lab_order_nilai.tindakan' 		=> '= ?',
                                'AND',
                                'lab_order_nilai.id_lab_nilai'	=> '= ?',
                                'AND',
                                'lab_order_nilai.deleted_at'	=> 'IS NULL'
                            ), array(
                                $parameter['uid_order'],
                                $key_tindakan,
                                $key_nilai
                            ))
                            ->execute();
                        if(count($checkData['response_data']) > 0)
                        {
                            if(($UserData['data']->jabatan !== __UIDDOKTER__)) {
                                $workerData = self::$query->update('lab_order_nilai', array(
                                    'nilai' =>	$value_nilai,
                                    'petugas' => $UserData['data']->uid,
                                    'updated_at' =>	parent::format_date()
                                ))
                                    ->where(array(
                                        'lab_order_nilai.lab_order' 	=> '= ?',
                                        'AND',
                                        'lab_order_nilai.tindakan' 		=> '= ?',
                                        'AND',
                                        'lab_order_nilai.id_lab_nilai'	=> '= ?',
                                        'AND',
                                        'lab_order_nilai.deleted_at'	=> 'IS NULL'
                                    ), array(
                                        $parameter['uid_order'],
                                        $key_tindakan,
                                        $key_nilai
                                    ))
                                    ->execute();

                                if ($workerData['response_result'] > 0){
                                    $log = parent::log(
                                        array(
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
                                                $checkData['response_data'][0]['id'],
                                                $UserData['data']->uid,
                                                'lab_order_nilai',
                                                'U',
                                                json_encode($old),
                                                json_encode($value_tindakan),
                                                parent::format_date(),
                                                'N',
                                                $UserData['data']->log_id
                                            ),
                                            'class'=>__CLASS__
                                        )
                                    );
                                }
                            } else {
                                $workerData = self::$query->update('lab_order_nilai', array(
                                    'updated_at' =>	parent::format_date()
                                ))
                                    ->where(array(
                                        'lab_order_nilai.lab_order' 	=> '= ?',
                                        'AND',
                                        'lab_order_nilai.tindakan' 		=> '= ?',
                                        'AND',
                                        'lab_order_nilai.id_lab_nilai'	=> '= ?',
                                        'AND',
                                        'lab_order_nilai.deleted_at'	=> 'IS NULL'
                                    ), array(
                                        $parameter['uid_order'],
                                        $key_tindakan,
                                        $key_nilai
                                    ))
                                    ->execute();
                            }
                        } else {
                            if(($UserData['data']->jabatan !== __UIDDOKTER__)) {
                                $workerData = self::$query->insert('lab_order_nilai', array(
                                    'lab_order' => $parameter['uid_order'],
                                    'nilai' => $value_nilai,
                                    'tindakan' => $key_tindakan,
                                    'id_lab_nilai' => $key_nilai,
                                    'petugas' => $UserData['data']->uid,
                                    'created_at' => parent::format_date(),
                                    'updated_at' =>	parent::format_date()
                                ))
                                    ->returning('id')
                                    ->execute();

                                if ($workerData['response_result'] > 0){
                                    $log = parent::log(
                                        array(
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
                                                $workerData['response_unique'],
                                                $UserData['data']->uid,
                                                'lab_order_nilai',
                                                'I',
                                                parent::format_date(),
                                                'N',
                                                $UserData['data']->log_id
                                            ),
                                            'class'=>__CLASS__
                                        )
                                    );
                                }
                            }
                        }

                        $result['order_detail'] = $workerData;
                    } else
                    {
                        //Delete detail order
                        $result['order_detail'] = array();
                    }
				}
			}

		    if(isset($parameter['selesai'])) {
		        if($parameter['selesai'] == 'Y')
                {
                    $selesai = self::$query->update('lab_order', array(
                        'status' => 'D'
                    ))
                        ->where(array(
                            'lab_order.uid' => '= ?',
                            'AND',
                            'lab_order.deleted_at' => 'IS NULL'
                        ), array(
                            $parameter['uid_order']
                        ))
                        ->execute();
                }
            }

		    //Update Kesan dan Anjurang
            if($UserData['data']->jabatan === __UIDDOKTER__) {
                $selesai = self::$query->update('lab_order', array(
                    'kesan' => $parameter['kesan'],
                    'anjuran' => $parameter['anjuran']
                ))
                    ->where(array(
                        'lab_order.uid' => '= ?',
                        'AND',
                        'lab_order.deleted_at' => 'IS NULL'
                    ), array(
                        $parameter['uid_order']
                    ))
                    ->execute();
            } else {
                $selesai = self::$query->update('lab_order', array(
                    'tanggal_sampling' => $parameter['tanggal_sampling']
                ))
                    ->where(array(
                        'lab_order.uid' => '= ?',
                        'AND',
                        'lab_order.deleted_at' => 'IS NULL'
                    ), array(
                        $parameter['uid_order']
                    ))
                    ->execute();
            }
		}

		//create new 
		$folder_structure = '../document/laboratorium/' . $parameter['uid_order'];
		if (!is_dir($folder_structure)){
			if (!mkdir($folder_structure, 0777, true)) {
			    $result['dir_msg'] = 'Failed to create folders...';
			}
			//mkdir('../document/laboratorium/' . $parameter['uid_radiologi_order'], 0755);
		} else {
			$result['dir_msg'] = 'Dir available...';
		}

		if(is_writeable($folder_structure)) {
			$result['response_upload'] = array();
			//$imageDatas = json_decode($_FILES['fileList'], true);

			//get maximum id
			$get_max = self::$query
				->select('lab_order_document', array(
						'id'
					)
				)
				->order(
					array(
						'lab_order_document.created_at' => 'DESC'
					)
				)
				->execute();

			$max = 0; 
			if ($get_max['response_result'] > 0){
				$max = $get_max['response_data'][0]['id'];
			}

			for ($a = 0; $a < count($_FILES['fileList']); $a++) {
				$max++;

				if(!empty($_FILES['fileList']['tmp_name'][$a])) {
					$nama_lampiran = 'L_' . str_pad($max, 6, "0", STR_PAD_LEFT);

					if (
						move_uploaded_file($_FILES['fileList']['tmp_name'][$a], '../document/laboratorium/' . $parameter['uid_order'] . '/' . $nama_lampiran . '.pdf')
					) {
						array_push($result['response_upload'], 'Berhasil diupload');
						$lampiran = self::$query
							->insert('lab_order_document', array(
								'lab_order' => $parameter['uid_order'],
								'lampiran' => $nama_lampiran . '.pdf',
								'created_at' => parent::format_date()
							))
							->execute();
						
						$result['response_upload']['response_result'] = 1;
					} else {
						array_push($result['response_upload'], 'Gagal diupload : ' . $_FILES['fileList']['tmp_name'][$a] . ' => ' . $nama_lampiran . '-' . $a . '.pdf');
					}
				}
			}
		} else {
			$result['response_upload']['response_message'] = 'Cant write';
			$result['response_upload']['response_result'] = 0;
		}

		if (count($parameter['deletedDocList']) > 0){
			foreach ($parameter['deletedDocList'] as $key => $value) {
				$getLampiran = self::$query
					->select('lab_order_document', array(
							'lampiran'
						)
					)
					->where(array(
							'lab_order_document.id' => '= ?'
						), array($value)
					)
					->execute();

				if ($getLampiran['response_result'] > 0){
					$nama_lampiran_hapus = $getLampiran['response_data'][0]['lampiran'];

					$hapusLampiran = self::$query
						->delete('lab_order_document')
						->where(array(
								'lab_order_document.id' => '= ?'
							), array($value)
						)
						->execute();

					if ($hapusLampiran['response_result'] > 0){
						unlink('../document/laboratorium/' . $parameter['uid_order'] . '/' . $nama_lampiran_hapus);

						$result['response_delete_doc']['response_result'] = 1;
					}

					$result['response_delete_doc']['response_data'] = $hapusLampiran;
				}
			}
		}

		$result['writable_folder'] = is_writable($folder_structure);

		//return count($parameter['deletedDocList']);
		return $result;
	}

	
	private function get_tindakan_for_dokter(){
	// uncomment this function if master_lab has join to master_tindakan
	// 	$dataTindakan = self::get_tindakan();

	// 	$tindakan = new Tindakan(self::$pdo);
	// 	$autonum = 1;
	// 	foreach ($dataTindakan['response_data'] as $key => $value) {
	// 		$dataTindakan['response_data'][$key]['autonum'] = $autonum;
	// 		$dataTindakan['response_data'][$key]['id'] = $value['uid'];
	// 		$dataTindakan['response_data'][$key]['text'] = $value['nama'];

	// 		$autonum++;

	// 		$harga = $tindakan->get_harga_tindakan($value['uid']);
	// 		$dataTindakan['response_data'][$key]['harga'] = $harga['response_data'];
	// 	}

		$dataTindakan = self::$query
			->select('master_lab'
				, array(
					'uid',
					'kode',
					'nama',
					'spesimen',
					'keterangan',
					'created_at',
					'updated_at'
				)
			)
			->where(array(
			    'master_lab.deleted_at' => 'IS NULL',
                'AND',
                'master_lab.nama' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\''
            ))
			->execute();
		
		$tindakan = new Tindakan(self::$pdo);
		foreach ($dataTindakan['response_data'] as $key => $value){

		    //Master Lab Item
            $Nilai = self::$query->select('master_lab_nilai', array(
                'id',
                'lab',
                'satuan',
                'nilai_min',
                'nilai_maks',
                'keterangan',
                'status'
            ))
                ->where(array(
                    'master_lab_nilai.deleted_at' => 'IS NULL',
                    'AND',
                    'master_lab_nilai.lab' => '= ?'
                ), array(
                    $value['uid']
                ))
                ->execute();
            $dataTindakan['response_data'][$key]['detail'] = $Nilai['response_data'];
			$harga = $tindakan::get_harga_tindakan($value['uid']);
			$dataTindakan['response_data'][$key]['harga'] = $harga['response_data'];
		}
			
		return $dataTindakan;
	}

	private function get_tindakan(){
		$data = self::$query
				->select('master_tindakan', array(
						'uid','nama', 'created_at','updated_at'
					)
				)
				->join('master_lab', array(
						'kode as kode_lab',
						'spesimen as uid_spesimen'
					)
				)
				->on(array(
					array('master_lab.uid_tindakan', '=', 'master_tindakan.uid'))
				)
				->where(array(
						'master_tindakan.deleted_at' => 'IS NULL'
					)
				)
				->order(array('nama'=>'ASC'))
				->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;

			$jenis = self::get_spesimen_detail($value['uid_spesimen']);
			$data['response_data'][$key]['spesimen'] = $jenis['response_data'][0]['nama'];
		}
		return $data;
	}

	private function get_tindakan_detail($parameter){
		/*================ hapus jika master lab sudah join ke table tindakan ==============*/
		$data = self::$query
			->select('master_lab', array(
					'uid',
					'nama',
					'kode as kode_lab',
					'spesimen as uid_spesimen'
				)
			)
			->where(array(
					'master_lab.deleted_at'	=> 'IS NULL',
					'AND',
					'master_lab.uid'		=> '= ?'
				), array(
					$parameter
				)
			)
			->execute();
		
		/*================ batas hapus  ==============*/
		
		/*================ uncomment jika master lab sudah join ke table tindakan ==============*/
		// $data = self::$query
		// 	->select('master_tindakan', array(
		// 		'uid','nama', 'created_at','updated_at'
		// 		)
		// 	)
		// 	->join('master_lab', array(
		// 		'kode as kode_lab',
		// 		'spesimen as uid_spesimen'
		// 		)
		// 	)
		// 	->on(array(
		// 		array('master_lab.uid_tindakan', '=', 'master_tindakan.uid'))
		// 	)
		// 	->where(array(
		// 			'master_tindakan.deleted_at' => 'IS NULL',
		// 			'AND',
		// 			'master_tindakan.uid'		 => '= ?'
		// 		),
		// 		array($parameter)
		// 	)
		// 	->execute();

		//foreach ($data['response_data'] as $key => $value) {

			// $temp = self::get_tindakan_penjamin(array(
			// 	'departemen'=>__UIDRADIOLOGI__,
			// 	'tindakan'=>$value['uid']
			// ));
			//$data['response_data'][$key]['penjamin'] = $temp['response_data'];
		//}

		return $data;
	}

	private function get_laboratorium_order($parameter){	//uid_antrian
		$get_antrian = new Antrian(self::$pdo);
		$antrian = $get_antrian->get_antrian_detail('antrian', $parameter);
		$autonum = 1;
		$result = [];
		if ($antrian['response_result'] > 0){

			$data_antrian = $antrian['response_data'][0];		//get antrian data

			//get uid asesmmen based by antrian data
			$get_asesmen = self::$query->select('asesmen', array('uid'))
				->where(array(
						'asesmen.deleted_at' => 'IS NULL',
						'AND',
						'asesmen.poli' => '= ?',
						'AND',
						'asesmen.kunjungan' => '= ?',
						'AND',
						'asesmen.antrian' => '= ?',
						'AND',
						'asesmen.pasien' => '= ?',
						'AND',
						'asesmen.dokter' => '= ?'
					), array(
						$data_antrian['departemen'],
						$data_antrian['kunjungan'],
						$parameter,
						$data_antrian['pasien'],
						$data_antrian['dokter']
					)
				)
				->execute();
			
			if ($get_asesmen['response_result'] > 0){
				
				$dataOrder = self::$query
					->select('lab_order', 
						array(
							'uid',
							'asesmen',
							'waktu_order',
							'selesai',
							'dr_pengirim',
							'no_order',
                            'dr_penanggung_jawab as uid_dr_penanggung_jawab',
							'status as status_order'
						)
					)
					->where(
						array(
							'lab_order.asesmen' 	=> '= ?',
							'AND',
							'lab_order.deleted_at'	=> 'IS NULL'
						), array($get_asesmen['response_data'][0]['uid'])
					)
					->execute();
				
				$pegawai = new Pegawai(self::$pdo);

				foreach ($dataOrder['response_data'] as $key => $value) {
                    $dataOrder['response_data'][$key]['autonum'] = $autonum;
				    $detail_pegawai = $pegawai->get_detail_pegawai($value['uid_dr_penanggung_jawab']);

					$dataOrder['response_data'][$key]['nama_dr_penanggung_jawab'] = $detail_pegawai['response_data'][0]['nama'];

					/*$date_time = explode(" ", $dataOrder['response_data'][$key]['waktu_order']);
					//$date = parent::dateToIndoSlash($date_time[0]); Fungsi tolong diinfokan
                    $date = $date_time[0];*/

					$dataOrder['response_data'][$key]['waktu_order'] = date('d F Y', strtotime($value['waktu_order']));

                    $dataOrder['response_data'][$key]['detail'] = self::get_laboratorium_order_detail($value['uid'])['response_data'];

					/*$get_valueable_nilai = self::$query
						->select('lab_order_nilai', array('id','nilai'))
						->where(
							array('lab_order_nilai.nilai' => 'IS NOT NULL',
								'AND',
								'lab_order_nilai.lab_order'	=> '= ?'
							), array(
								$value['uid']
							) 
						)
						->execute();

					if ($get_valueable_nilai['response_result'] > 0 || $value['status_order'] == 'P'){
						$dataOrder['response_data'][$key]['editable'] = 'false';
					} else {
						$dataOrder['response_data'][$key]['editable'] = 'true';
					}*/
					$autonum ++;
				}

				return $dataOrder;
			} else {
                return array('response_data' => array());
            }
		} else {
            return array('response_data' => array());
        }
	}

	private static function get_laboratorium_order_detail($parameter) {
	    $Tindakan = new Tindakan(self::$pdo);
		$data = self::$query
			->select('lab_order_detail', array(
					'id',
					'lab_order as uid_lab_order',
					'tgl_ambil_sample',
					'request_item',
					'tindakan as uid_tindakan',
					'penjamin as uid_penjamin'
				)
			)
			->where(array(
					'lab_order_detail.lab_order'	=> '= ?',
					'AND',
					'lab_order_detail.deleted_at'	=> 'IS NULL'
				),array($parameter)
			)
			->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
		    $TindakanDetail = $Tindakan->get_tindakan_detail($value['uid_tindakan'])['response_data'][0];
			//$data['response_data'][$key]['tindakan'] = self::get_tindakan_detail($value['uid_tindakan'])['response_data'][0]['nama'];
            $data['response_data'][$key]['tindakan'] = $TindakanDetail['nama'];
            $data['response_data'][$key]['tindakan_detail'] = $TindakanDetail;

			$penjamin = new Penjamin(self::$pdo);
			$data['response_data'][$key]['penjamin'] = $penjamin->get_penjamin_detail($value['uid_penjamin'])['response_data'][0]['nama'];

			$data['response_data'][$key]['tgl_ambil_sample'] = date('d F Y', strtotime($value['tgl_ambil_sample']));

            $nilaiLab = array();
            $dataSplitNilai = explode(',', $value['request_item']);
            foreach ($dataSplitNilai as $dNLK => $dNLV) {
                $data_nilai = self::get_lab_nilai_detail($dNLV);
                array_push($nilaiLab, $data_nilai['response_data'][0]);
            }

            $data['response_data'][$key]['nilai_item'] = $nilaiLab;
		}
		
		return $data;
	}

	public function get_col_detail_order_lab($parameter){
		$data = self::$query
			->select('lab_order', array(
					'uid',
					'asesmen',
					'no_order',
					'dr_pengirim',
					'dr_penanggung_jawab',
					'created_at',
					'updated_at'
				)
			)
			->where(
				array(
					'lab_order.uid'			=> '= ?',
					'AND',
					'lab_order.deleted_at'	=> 'IS NULL'
				),
				array(
					$parameter['uid_lab_order']
				)
			)
			->execute();

		return $data;
	}

	private static function get_nilai_tindakan($parameter){
		$data = self::$query
			->select('master_lab_nilai', array(
					'id',
					'satuan',
					'nilai_maks',
					'nilai_min',
					'keterangan'
				)
			)
			->where(
				array(
					'master_lab_nilai.lab'			=>	'= ?',
					'AND',
					'master_lab_nilai.deleted_at'	=>	'IS NULL'
				), array(
					$parameter
				)
			)
			->execute();
		
		return $data;
	}

	/*===============================================*/
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