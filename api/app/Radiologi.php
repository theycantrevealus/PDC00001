<?php

namespace PondokCoder;

use PondokCoder\Invoice as Invoice;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Tindakan as Tindakan;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;
use PondokCoder\Penjamin as Penjamin;
use PondokCoder\Antrian as Antrian;
use PondokCoder\Pasien as Pasien;

class Radiologi extends Utility
{
    static $pdo;
    static $query;

    public function __construct($connection)
    {
        self::$pdo = $connection;
        self::$query = new Query(self::$pdo);
    }

    protected static function getConn()
    {
        return self::$pdo;
    }

    public function __GET__($parameter = array())
    {
        try {
            switch ($parameter[1]) {
                case 'jenis':
                    return self::get_jenis_tindakan('master_radiologi_jenis');
                    break;
                case 'penjamin':
                    return self::get_tindakan_penjamin($parameter[2]);
                    break;
                case 'tindakan':
                    return self::get_tindakan();
                    break;
                case 'tindakan-detail':
                    return self::get_tindakan_detail($parameter[2]);
                    break;
                case 'antrian':
                    return self::get_antrian();
                    break;
                case 'verifikasi':
                    return self::get_antrian('V');
                    break;
                case 'get-order-detail':
                    return self::get_radiologi_order_detail($parameter[2]);
                    break;
                case 'get-radiologi-order':
                    return self::get_radiologi_order($parameter[2]);
                    break;
                case 'get-radiologi-order-dokter':
                    return self::get_radiologi_order_dokter($parameter[2]);
                    break;
                case 'get-data-pasien-antrian':
                    return self::get_data_pasien_antrian($parameter[2]);
                    break;
                case 'radiologi-order-detail-item':
                    return self::get_radiologi_order_detail_item($parameter[2]);
                    break;
                case 'get-radiologi-lampiran':
                    return self::get_radiologi_lampiran($parameter[2]);
                    break;
                case 'get_tindakan_for_dokter':
                    return self::get_tindakan_for_dokter();
                    break;
                default:
                    # code...
                    break;
            }
        } catch (QueryException $e) {
            return 'Error => ' . $e;
        }
    }

    private function radiologi_import_fetch($parameter) {
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

    private function proceed_import_radiologi($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $duplicate_row = array();
        $non_active = array();
        $success_proceed = 0;
        $proceed_data = array();
        $failed_data = array();
        $action_list_tindakan = array();

        //Reset Radiologi
        $resetRadiologi = self::$query->update('master_radiologi_tindakan', array(
            'deleted_at' => parent::format_date()
        ))
            ->execute();


        foreach ($parameter['data_import'] as $key => $value) {
            $targettedKategori = '';
            $targettedMitra = '';
            $targettedTindakan = '';
            $targettedRadiologi = '';

            //Check Kategori
            $checkKategoriRad = self::$query->select('master_radiologi_jenis', array(
                'uid'
            ))
                ->where(array(
                    'master_radiologi_jenis.nama' => '= ?'
                ), array(
                    trim(strtoupper($value['kategori']))
                ))
                ->execute();
            if(count($checkKategoriRad['response_data']) > 0) {
                $targettedKategori = $checkKategoriRad['response_data'][0]['uid'];
                $proceed_kategori = self::$query->update('master_radiologi_jenis', array(
                    'deleted_at' => NULL
                ))
                    ->where(array(
                        'master_radiologi_jenis.uid' => '= ?'
                    ), array(
                        $targettedKategori
                    ))
                    ->execute();
            } else {
                $targettedKategori = parent::gen_uuid();
                $proceed_kategori = self::$query->insert('master_radiologi_jenis', array(
                    'uid' => $targettedKategori,
                    'nama' => ($value['kategori'] != '') ? (strtoupper(trim($value['kategori']))) : 'UNSPECIFIED',
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
            }




            //Check Mitra
            $checkMitra = self::$query->select('master_mitra', array(
                'uid'
            ))
                ->where(array(
                    'master_mitra.nama' => '= ?'
                ), array(
                    $value['mitra']
                ))
                ->execute();
            if(count($checkMitra['response_data']) > 0) {
                $targettedMitra = $checkMitra['response_data'][0]['uid'];
                $proceed_mitra = self::$query->update('master_mitra', array(
                    'jenis' => 'RAD',
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
                    'jenis' => 'RAD',
                    'kontak' => '',
                    'alamat' => '',
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
            }



            //Check Tindakan
            $checkTindakan = self::$query->select('master_tindakan', array(
                'uid', 'nama'
            ))
                ->where(array(
                    'master_tindakan.nama' => '= ?',
                    'AND',
                    'master_tindakan.kelompok' => '= ?'
                ), array(
                    trim($value['nama']),
                    'RAD'
                ))
                ->execute();

            if(count($checkTindakan['response_data']) > 0) {
                array_push($action_list_tindakan, $checkTindakan['response_data'][0]['nama']);
                $targettedTindakan = $checkTindakan['response_data'][0]['uid'];
                $proceed_tindakan = self::$query->update('master_tindakan', array(
                    'deleted_at' => NULL
                ))
                    ->where(array(
                        'master_tindakan.uid' => '= ?'
                    ), array(
                        $targettedTindakan
                    ))
                    ->execute();
                //array_push($action_list_tindakan, $proceed_tindakan);
            } else {
                $targettedTindakan = parent::gen_uuid();
                $proceed_tindakan = self::$query->insert('master_tindakan', array(
                    'uid' => $targettedTindakan,
                    'nama' => addslashes(trim($value['nama'])),
                    'kelompok' => 'RAD',
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
                //array_push($action_list_tindakan, $proceed_tindakan);
            }

            if($proceed_tindakan['response_result'] > 0) {

                //Sync ke radiologi
                $checkRadiologi = self::$query->select('master_radiologi_tindakan', array(
                    'id'
                ))
                    ->where(array(
                        'master_radiologi_tindakan.uid_tindakan' => '= ?'
                    ), array(
                        $targettedTindakan
                    ))
                    ->execute();
                if(count($checkRadiologi['response_data']) > 0) {
                    $targettedRadiologi = $checkRadiologi['response_data'][0]['id'];
                    $proceed_radiologi = self::$query->update('master_radiologi_tindakan', array(
                        'jenis' => $targettedKategori,
                        'updated_at' => parent::format_date(),
                        'deleted_at' => NULL
                    ))
                        ->where(array(
                            'master_radiologi_tindakan.id' => '= ?'
                        ), array(
                            $targettedRadiologi
                        ))
                        ->execute();
                } else {

                    $proceed_radiologi = self::$query->insert('master_radiologi_tindakan', array(
                        'jenis' => $targettedKategori,
                        'uid_tindakan' => $targettedTindakan,
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->returning('id')
                        ->execute();
                    $targettedRadiologi = $proceed_radiologi['response_unique'];
                }

                if($proceed_radiologi['response_result'] > 0) {
                    //Manajer Tarif
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
                                'RAD'
                            ))
                            ->execute();
                        foreach ($kelasLab['response_data'] as $KKey => $KValue) {


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
                                    $targettedTindakan,
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
                                    'tindakan' => $targettedTindakan,
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
            } else {
                $value['query'] = $proceed_tindakan;
                array_push($failed_data, $value);
            }
        }

        return array(
            'all_data' => count($parameter['data_import']),
            'duplicate_row' => $duplicate_row,
            'non_active' => $non_active,
            'action_list' => $action_list_tindakan,
            'success_proceed' => $success_proceed,
            'data' => $parameter['data_import'],
            'proceed' => $proceed_data,
            'failed_data' => $failed_data
        );
    }

    private function get_jenis_tindakan($table)
    {
        $data = self::$query
            ->select($table,
                array(
                    'uid',
                    'nama',
                    'created_at',
                    'updated_at'
                )
            )
            ->where(array(
                    $table . '.deleted_at' => 'IS NULL'
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

    private function get_tindakan_penjamin($parameter)
    {
        $data = self::$query
            ->select('master_poli_tindakan_penjamin',
                array(
                    'id',
                    'harga',
                    'uid_poli',
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
                'master_poli_tindakan_penjamin.deleted_at' => 'IS NULL'
            ), array(
                    $parameter['departemen'],
                    $parameter['tindakan']
                )
            )
            ->execute();

        return $data;
    }

    /*====================== GET FUNCTION =====================*/

    private function get_tindakan()
    {
        $data = self::$query
            ->select('master_tindakan', array(
                    'uid', 'nama', 'created_at', 'updated_at'
                )
            )
            ->join('master_radiologi_tindakan', array(
                    'jenis as uid_jenis'
                )
            )
            ->on(array(
                    array('master_radiologi_tindakan.uid_tindakan', '=', 'master_tindakan.uid'))
            )
            ->where(array(
                    'master_tindakan.deleted_at' => 'IS NULL'
                )
            )
            ->order(array('nama' => 'ASC'))
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;

            $jenis = self::get_jenis_tindakan_detail('master_radiologi_jenis', $value['uid_jenis']);
            $data['response_data'][$key]['jenis'] = $jenis['response_data'][0]['nama'];
        }
        return $data;
    }

    private function get_jenis_tindakan_detail($table, $parameter)
    {
        $data = self::$query
            ->select($table,
                array(
                    'uid',
                    'nama',
                    'created_at',
                    'updated_at'
                )
            )
            ->where(array(
                $table . '.deleted_at' => 'IS NULL',
                'AND',
                $table . '.uid' => '= ?'
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

    private function get_tindakan_detail($parameter)
    {
        $data = self::$query
            ->select('master_tindakan', array(
                    'uid', 'nama', 'created_at', 'updated_at'
                )
            )
            ->join('master_radiologi_tindakan', array(
                    'jenis'
                )
            )
            ->on(array(
                    array('master_radiologi_tindakan.uid_tindakan', '=', 'master_tindakan.uid'))
            )
            ->where(array(
                'master_tindakan.deleted_at' => 'IS NULL',
                'AND',
                'master_tindakan.uid' => '= ?'
            ),
                array($parameter)
            )
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;

            // $temp = self::get_tindakan_penjamin(array(
            // 	'departemen'=>__UIDRADIOLOGI__,
            // 	'tindakan'=>$value['uid']
            // ));
            //$data['response_data'][$key]['penjamin'] = $temp['response_data'];
        }

        return $data;
    }

    private function get_antrian($status = 'P')
    {
        $data = self::$query
            ->select('rad_order', array(
                'uid',
                'asesmen as uid_asesmen',
                'waktu_order'))
            ->join('asesmen', array(
                'antrian as uid_antrian'
            ))
            ->join('antrian', array(
                'pasien as uid_pasien',
                'dokter as uid_dokter',
                'departemen as uid_poli',
                'penjamin as uid_penjamin',
                'waktu_masuk'
            ))
            ->join('pasien', array(
                'nama as pasien',
                'no_rm'
            ))
            ->join('master_poli', array(
                'nama as departemen'
            ))
            ->join('pegawai', array(
                'nama as dokter'
            ))
            ->join('master_penjamin', array(
                'nama as penjamin'
            ))
            ->join('kunjungan', array(
                    'pegawai as uid_resepsionis'
                )
            )
            ->on(array(
                    array('rad_order.asesmen', '=', 'asesmen.uid'),
                    array('asesmen.antrian', '=', 'antrian.uid'),
                    array('pasien.uid', '=', 'antrian.pasien'),
                    array('master_poli.uid', '=', 'antrian.departemen'),
                    array('pegawai.uid', '=', 'antrian.dokter'),
                    array('master_penjamin.uid', '=', 'antrian.penjamin'),
                    array('kunjungan.uid', '=', 'antrian.kunjungan')
                )
            )
            ->where(array(
                'rad_order.status'  => '= ?',
                'AND',
                'rad_order.deleted_at' => 'IS NULL',
                'AND',
                'antrian.departemen' => '!= ?'
            ), array(
                $status, __POLI_INAP__
            ))
            ->order(
                array(
                    'rad_order.waktu_order' => 'ASC'
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

    private function get_radiologi_order_detail($parameter)
    {
        $data = self::$query
            ->select('rad_order_detail', array(
                    'id',
                    'radiologi_order as uid_radiologi_order',
                    'tindakan as uid_tindakan',
                    'penjamin as uid_penjamin',
                    'mitra',
                    'keterangan',
                    'kesimpulan',
                    'gambar',
                    'invoice',
                    'created_at',
                    'updated_at'
                )
            )
            ->where(array(
                'rad_order_detail.radiologi_order' => '= ?',
                'AND',
                'rad_order_detail.deleted_at' => 'IS NULL'
            ), array($parameter)
            )
            ->execute();

        $autonum = 1;
        $Mitra = new Mitra(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['tindakan'] = self::get_tindakan_detail($value['uid_tindakan'])['response_data'][0]['nama'];

            $penjamin = new Penjamin(self::$pdo);
            $data['response_data'][$key]['penjamin'] = $penjamin->get_penjamin_detail($value['uid_penjamin'])['response_data'][0]['nama'];

            $data['response_data'][$key]['waktu_order'] = date('d F Y', strtotime($value['created_at']));
            $data['response_data'][$key]['jam_order'] = date('H:i', strtotime($value['created_at']));

            $data['response_data'][$key]['mitra'] = $Mitra->get_mitra_detail($value['mitra'])['response_data'][0];

            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }

        return $data;
    }

    private function get_radiologi_order_dokter($parameter) {
        $get_antrian = new Antrian(self::$pdo);
        $antrian = $get_antrian->get_antrian_detail('antrian', $parameter);
        $data_antrian = $antrian['response_data'][0];
        $Pegawai = new Pegawai(self::$pdo);
        $Tindakan = new Tindakan(self::$pdo);
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
                    $data_antrian['dokter']['uid']
                )
            )
            ->execute();

        if ($get_asesmen['response_result'] > 0) {
            $data_order = self::$query->select('rad_order', array(
                'uid', 'asesmen', 'waktu_order', 'created_at', 'petugas', 'no_order'
            ))
                ->where(array(
                    'rad_order.asesmen' => '= ?',
                    'AND',
                    'rad_order.deleted_at' => 'IS NULL'
                ), array(
                    $get_asesmen['response_data'][0]['uid']
                ))
                ->execute();
            $data = $data_order['response_data'];
            $autonum = 1;
            foreach ($data as $key => $value) {
                $order_detail = self::$query->select('rad_order_detail', array(
                    'tindakan'
                ))
                    ->where(array(
                        'rad_order_detail.radiologi_order' => '= ?',
                        'AND',
                        'rad_order_detail.deleted_at' => 'IS NULL'
                    ), array(
                        $value['uid']
                    ))
                    ->execute();
                foreach ($order_detail['response_data'] as $DKey => $DValue) {
                    $order_detail['response_data'][$DKey]['tindakan'] = $Tindakan->get_tindakan_detail($DValue['tindakan'])['response_data'][0];
                }

                $data[$key]['detail'] = $order_detail['response_data'];

                $data[$key]['pegawai'] = $Pegawai->get_detail($value['petugas'])['response_data'][0];
                $data[$key]['autonum'] = $autonum;
                $autonum++;
            }
        }

        return $data;
    }

    private function get_radiologi_order($parameter)
    {    //uid_antrian
        $get_antrian = new Antrian(self::$pdo);
        $antrian = $get_antrian->get_antrian_detail('antrian', $parameter);

        $dataRadiologiOrder = [];

        $result = [];
        if ($antrian['response_result'] > 0) {

            $data_antrian = $antrian['response_data'][0];        //get antrian data

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

            if ($get_asesmen['response_result'] > 0) {

                $dataOrder = self::$query
                    ->select('rad_order',
                        array(
                            'uid',
                            'asesmen',
                            'waktu_order',
                            'selesai',
                            'petugas'
                        )
                    )
                    ->where(
                        array(
                            'rad_order.asesmen' => '= ?',
                            'AND',
                            'rad_order.deleted_at' => 'IS NULL'
                        ), array($get_asesmen['response_data'][0]['uid'])
                    )
                    ->execute();

                if ($dataOrder['response_result'] > 0) {
                    $dataRadiologiOrder["order_data"] = $dataOrder['response_data'][0];

                    $dataDetailOrder = self::get_radiologi_order_detail($dataOrder['response_data'][0]['uid']);
                    if ($dataDetailOrder['response_result'] > 0) {
                        $dataRadiologiOrder["detail_order"] = $dataDetailOrder['response_data'];
                    }

                }
            }
        }

        return $dataRadiologiOrder;
    }
    /*=========================================================*/


    /*====================== CRUD ========================*/

    private function get_data_pasien_antrian($parameter)
    {
        $get_uid_asesmen = self::$query
            ->select('rad_order', array(
                    'asesmen'
                )
            )
            ->where(array(
                'rad_order.uid' => '= ?'
            ),
                array($parameter)
            )
            ->execute();

        $result = "";
        if ($get_uid_asesmen['response_result'] > 0) {
            $get_uid_antrian = self::$query
                ->select('asesmen', array('antrian'))
                ->where(array('asesmen.uid' => '= ?'),
                    array($get_uid_asesmen['response_data'][0]['asesmen']))
                ->execute();

            $uid_antrian = $get_uid_antrian['response_data'][0]['antrian'];

            $antrian = new Antrian(self::$pdo);
            $result = $antrian->get_data_pasien_dan_antrian($uid_antrian);    //call function for get data antrian and
            //pasien in class antrian

        }

        return $result;
    }

    private function get_radiologi_order_detail_item($parameter)
    {
        $data = self::$query
            ->select('rad_order_detail', array(
                    'id',
                    'radiologi_order as uid_radiologi_order',
                    'tindakan as uid_tindakan',
                    'penjamin as uid_penjamin',
                    'keterangan',
                    'kesimpulan',
                    'gambar',
                )
            )
            ->where(array(
                'rad_order_detail.id' => '= ?',
                'AND',
                'rad_order_detail.deleted_at' => 'IS NULL'
            ), array($parameter)
            )
            ->execute();

        return $data;
    }

    private function get_radiologi_lampiran($parameter)
    {
        $data = self::$query
            ->select('rad_order_document', array(
                    'id',
                    'radiologi_order',
                    'lampiran',
                    'created_at'
                )
            )
            ->where(array(
                'rad_order_document.radiologi_order' => '= ?',
                'AND',
                'rad_order_document.deleted_at' => 'IS NULL'
            ), array($parameter)
            )
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['file_location'] = '../document/radiologi/' . $parameter . '/' . $value['lampiran'];
            $data['response_data'][$key]['file_check'] = file_exists('../document/radiologi/' . $parameter . '/' . $value['lampiran']);
            $autonum++;
        }

        return $data;
    }

    private function get_tindakan_for_dokter()
    {
        $dataTindakan = self::$query
            ->select('master_tindakan'
                , array(
                    'uid',
                    'nama',
                    'created_at',
                    'updated_at'
                )
            )
            ->where(array(
                'master_tindakan.deleted_at' => 'IS NULL',
                'AND',
                'master_tindakan.kelompok' => '= ?',
                'AND',
                'master_tindakan.nama' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\''
            ), array(
                'RAD'
            ))
            ->execute();

        $tindakan = new Tindakan(self::$pdo);
        foreach ($dataTindakan['response_data'] as $key => $value){

            $harga = $tindakan->get_harga_tindakan($value['uid']);
            $dataTindakan['response_data'][$key]['harga'] = $harga['response_data'];

            $harga_range = $tindakan->get_tindakan_info($value['uid']);
            $dataTindakan['response_data'][$key]['harga_range'] = $harga_range['response_data'];
        }

        return $dataTindakan;
        /*$dataTindakan = self::get_tindakan();

        $tindakan = new Tindakan(self::$pdo);
        $autonum = 1;
        foreach ($dataTindakan['response_data'] as $key => $value) {
            $dataTindakan['response_data'][$key]['autonum'] = $autonum;
            $dataTindakan['response_data'][$key]['id'] = $value['uid'];
            $dataTindakan['response_data'][$key]['text'] = $value['nama'];

            $autonum++;

            $harga = $tindakan->get_harga_tindakan($value['uid']);
            $dataTindakan['response_data'][$key]['harga'] = $harga['response_data'];
        }

        return $dataTindakan;*/
    }

    public function __POST__($parameter = array())
    {
        switch ($parameter['request']) {
            case 'tambah-jenis':
                return self::tambah_jenis_tindakan('master_radiologi_jenis', $parameter);
                break;
            case 'edit-jenis':
                return self::edit_jenis_tindakan('master_radiologi_jenis', $parameter);
                break;
            case 'tambah-tindakan':
                return self::tambah_tindakan($parameter);
                break;
            case 'edit-tindakan':
                return self::edit_tindakan($parameter);
                break;
            case 'add-order-radiologi':
                return self::add_order_radiologi2($parameter);
                break;
            case 'update-hasil-radiologi':
                return self::update_hasil_radiologi($parameter);
                break;
            case 'charge_invoice_item':
                return self::charge_invoice_item($parameter);
                break;
            case 'verifikasi_hasil':
                return self::verifikasi_hasil($parameter);
                break;
            case 'radiologi_import_fetch':
                return self::radiologi_import_fetch($parameter);
                break;
            case 'proceed_import_radiologi':
                return self::proceed_import_radiologi($parameter);
                break;
            case 'verifikasi_item_rad':
                return self::verifikasi_item_rad($parameter);
                break;
            case 'riwayat_radiologi':
                return self::riwayat_radiologi($parameter);
                break;
            default:
                # code...
                break;
        }
    }

    private function riwayat_radiologi($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'rad_order.deleted_at' => 'IS NULL',
                'AND',
                'rad_order.waktu_order' => 'BETWEEN ? AND ?',
                'AND',
                'rad_order.status' => '= ?',
                'AND',
                '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
            );

            $paramValue = array(
                $parameter['from'], $parameter['to'], 'D'
            );
        } else {
            $paramData = array(
                'rad_order.deleted_at' => 'IS NULL',
                'AND',
                'rad_order.waktu_order' => 'BETWEEN ? AND ?',
                'AND',
                'rad_order.status' => '= ?'
            );

            $paramValue = array(
                $parameter['from'], $parameter['to'], 'D'
            );
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('rad_order', array(
                'uid',
                'asesmen',
                'waktu_order',
                'petugas',
                'dokter_radio',
                'pasien',
                'status',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->join('pasien', array(
                    'nama as nama_pasien', 'no_rm'
                ))
                ->on(array(
                    array('rad_order.pasien', '=', 'pasien.uid')
                ))
                ->execute();
        } else {

            $data = self::$query->select('rad_order', array(
                'uid',
                'asesmen',
                'waktu_order',
                'petugas',
                'dokter_radio',
                'pasien',
                'status',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->join('pasien', array(
                    'nama as nama_pasien', 'no_rm'
                ))
                ->on(array(
                    array('rad_order.pasien', '=', 'pasien.uid')
                ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        $Pegawai = new Pegawai(self::$pdo);
        $Poli = new Poli(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['tanggal'] = date('d F Y', strtotime($value['waktu_order']));
            $AsesmenInfo = self::$query->select('asesmen', array(
                'kunjungan',
                'antrian',
                'pasien',
                'poli',
                'dokter'
            ))
                ->where(array(
                    'asesmen.uid' => '= ?',
                    'AND',
                    'asesmen.deleted_at' => 'IS NULL'
                ), array(
                    $value['asesmen']
                ))
                ->execute();
            foreach ($AsesmenInfo['response_data'] as $AsKey => $AsValue) {
                $AsesmenInfo['response_data'][$AsKey]['poli'] = $Poli->get_poli_info($AsValue['poli'])['response_data'][0];
                $AsesmenInfo['response_data'][$AsKey]['dokter'] = $Pegawai->get_info($AsValue['dokter'])['response_data'][0];
            }

            $data['response_data'][$key]['asesmen'] = $AsesmenInfo['response_data'][0];
            $data['response_data'][$key]['petugas'] = $Pegawai->get_info($value['petugas'])['response_data'][0];
            $data['response_data'][$key]['dokter_radio'] = $Pegawai->get_info($value['dokter_radio'])['response_data'][0];

            $autonum++;
        }

        $dataTotal = self::$query->select('rad_order', array(
            'uid',
        ))
            ->where($paramData, $paramValue)
            ->join('pasien', array(
                'nama', 'no_rm'
            ))
            ->on(array(
                array('rad_order.pasien', '=', 'pasien.uid')
            ))
            ->execute();

        $data['recordsTotal'] = count($dataTotal['response_data']);
        $data['recordsFiltered'] = count($dataTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function verifikasi_item_rad($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $processResult = array();

        //Simpan petugas
        $UpdateRadio = self::$query->update('rad_order', array(
            'petugas' => $UserData['data']->uid
        ))
            ->where(array(
                'rad_order.uid' => '= ?',
                'AND',
                'rad_order.deleted_at' => 'IS NULL'
            ), array(
                $parameter['uid']
            ))
            ->execute();

        foreach ($parameter['data_set'] as $key => $value) {
            $worker = self::$query->update('rad_order_detail', array(
                'mitra' => $value['mitra'],
                'verifikator' => $UserData['data']->uid
            ))
                ->where(array(
                    'rad_order_detail.radiologi_order' => '= ?',
                    'AND',
                    'rad_order_detail.deleted_at' => 'IS NULL',
                    'AND',
                    'rad_order_detail.tindakan' => '= ?'
                ), array (
                    $value['uid'],
                    $value['tindakan']
                ))
                ->execute();

            array_push($processResult, $worker);

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
                    $value['asesmen']
                ))
                ->execute();

            $AntrianDetail = $AsesmenInfo['response_data'][0];
            $Antrian = new Antrian(self::$pdo);
            $AntrianData = $Antrian->get_antrian_detail('antrian', $AntrianDetail['antrian'])['response_data'][0];


            //Check Item Lab
            $checkMaster = self::$query->select('rad_order_detail', array(
                'id'
            ))
                ->where(array(
                    'rad_order_detail.mitra' => 'IS NULL',
                    'AND',
                    'rad_order_detail.radiologi_order' => '= ?'
                ), array(
                    $value['uid']
                ))
                ->execute();
            if(count($checkMaster['response_data']) > 0) {
                //
            } else {

                //Update master to P
                $master_order = self::$query->update('rad_order', array(
                    'status' => ($AntrianData['penjamin'] === __UIDPENJAMINUMUM__) ? 'K' : 'P', //Ke kasir bayar
                    'updated_at' => parent::format_date()
                ))
                    ->where(array(
                        'rad_order.uid' => '= ?',
                        'AND',
                        'rad_order.deleted_at' => 'IS NULL'
                    ), array(
                        $value['uid']
                    ))
                    ->execute();



                $antrian_nomor = self::$query->update('antrian_nomor', array(
                    'status' => ($AntrianData['penjamin'] === __UIDPENJAMINUMUM__) ? 'K' : 'R'
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

            $Invoice = new Invoice(self::$pdo);

            $InvoiceCheck = self::$query->select('invoice', array(
                'uid',
                'total_after_discount'
            ))
                ->where(array(
                    'invoice.kunjungan' => '= ?',
                    'AND',
                    'invoice.deleted_at' => 'IS NULL'
                ), array(
                    $AntrianDetail['kunjungan']
                ))
                ->execute();

            if (count($InvoiceCheck['response_data']) > 0) {
                $TargetInvoice = $InvoiceCheck['response_data'][0]['uid'];
                $totalInvoice = $InvoiceCheck['response_data'][0]['total_after_discount'];
            } else {
                $InvMasterParam = array(
                    'kunjungan' => $AntrianDetail['kunjungan'],
                    'pasien' => $AntrianDetail['pasien'],
                    'keterangan' => 'Tagihan radiologi'
                );
                $NewInvoice = $Invoice->create_invoice($InvMasterParam);
                $TargetInvoice = $NewInvoice['response_unique'];
                $totalInvoice = 0;
            }

            $invoice_detail = self::$query->update('invoice_detail', array(
                'status_bayar' => 'N',
                'harga' => $value['harga'],
                'subtotal' => $value['harga'],
                'mitra' => $value['mitra']
            ))
                ->where(array(
                    'invoice_detail.invoice' => '= ?',
                    'AND',
                    'invoice_detail.item' => '= ?',
                    'AND',
                    'invoice_detail.deleted_at' => 'IS NULL'
                ), array(
                    $TargetInvoice,
                    $value['tindakan']
                ))
                ->execute();

            //Update Invoice Master
            $InvoiceMasterUpdate = self::$query->update('invoice', array(
                'total_pre_discount' => $totalInvoice + floatval($value['harga']),
                'total_after_discount' => $totalInvoice + floatval($value['harga'])
            ))
                ->where(array(
                    'invoice.uid' => '= ?',
                    'AND',
                    'invoice.deleted_at' => 'IS NULL'
                ), array(
                    $TargetInvoice
                ))
                ->execute();








        }

        return $processResult;
    }

    private function verifikasi_hasil($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $update = self::$query->update('rad_order', array(
            //'petugas' => $UserData['data']->uid,
            'dokter_radio' => $UserData['data']->uid,
            'selesai' => 'true',
            'status' => 'D'
        ))
            ->where(array(
                'rad_order.uid' => '= ?',
                'AND',
                'rad_order.deleted_at' => 'IS NULL'
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
                        'rad_order',
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

    public function charge_invoice_item($parameter) {
        $charge_result = array();

        //Update status
        $proceed = self::$query->update('rad_order', array(
            'status' => 'V'
        ))
            ->where(array(
                'rad_order.kunjungan' => '= ?',
                'AND',
                'rad_order.asesmen' => '= ?',
                'AND',
                'rad_order.selesai' => '= ?',
                'AND',
                'rad_order.deleted_at' => 'IS NULL'
            ), array(
                $parameter['kunjungan'],
                $parameter['asesmen'],

                'false'
            ))
            ->execute();

        //Ambil semua item untuk asesmen sekarang
        $RadOrder = self::$query->select('rad_order', array(
            'uid',
            'pasien'
        ))
            ->where(array(
                'rad_order.asesmen' => '= ?',
                'AND',
                'rad_order.selesai' => '= ?',
                'AND',
                'rad_order.deleted_at' => 'IS NULL'
            ), array(
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

        foreach ($RadOrder['response_data'] as $key => $value) {
            //Get Detail
            $Detail = self::$query->select('rad_order_detail', array(
                'tindakan',
                'penjamin'
            ))
                ->where(array(
                    'rad_order_detail.radiologi_order' => '= ?',
                    'AND',
                    'rad_order_detail.deleted_at' => 'IS NULL'
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
                    'status_bayar' => ($DValue['penjamin'] == __UIDPENJAMINUMUM__) ? 'V' : 'Y', // Check Penjamin. Jika non umum maka langsung lunas
                    'subtotal' => $HargaFinal,
                    'discount' => 0,
                    'discount_type' => 'N',
                    'pasien' => $value['pasien'],
                    'penjamin' => $DValue['penjamin'],
                    'billing_group' => 'radiologi',
                    'keterangan' => 'Biaya Radiologi',
                    'departemen' => $parameter['departemen']
                ));

                array_push($charge_result, $InvoiceDetail);
            }
        }
        return $charge_result;
    }

    private function tambah_jenis_tindakan($table, $parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $check = self::duplicate_check(array(
            'table' => $table,
            'check' => $parameter['nama']
        ));

        if (count($check['response_data']) > 0) {
            $check['response_message'] = 'Duplicate data detected';
            $check['response_result'] = 0;
            unset($check['response_data']);
            return $check;
        } else {
            $uid = parent::gen_uuid();
            $jenis = self::$query
                ->insert($table, array(
                        'uid' => $uid,
                        'nama' => $parameter['nama'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    )
                )
                ->execute();

            if ($jenis['response_result'] > 0) {
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
                            $table,
                            'I',
                            parent::format_date(),
                            'N',
                            $UserData['data']->log_id
                        ),
                        'class' => __CLASS__
                    )
                );
            }
            return $jenis;
        }
    }

    /*------------------ ANTRIAN RADIOLOGI -------------------*/

    private function duplicate_check($parameter)
    {
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

    private function edit_jenis_tindakan($table, $parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $old = self::get_jenis_tindakan_detail('master_radiologi_jenis', $parameter['uid']);

        $jenis = self::$query
            ->update($table, array(
                    'nama' => $parameter['nama'],
                    'updated_at' => parent::format_date()
                )
            )
            ->where(array(
                $table . '.deleted_at' => 'IS NULL',
                'AND',
                $table . '.uid' => '= ?'
            ),
                array(
                    $parameter['uid']
                )
            )
            ->execute();

        if ($jenis['response_result'] > 0) {
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
                        $table,
                        'U',
                        json_encode($old['response_data'][0]),
                        json_encode($parameter),
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                )
            );
        }

        return $jenis;
    }

    private function tambah_tindakan($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $check = self::duplicate_check(array(
            'table' => 'master_tindakan',
            'check' => $parameter['nama']
        ));

        if (count($check['response_data']) > 0) {
            $check['response_message'] = 'Duplicate data detected';
            $check['response_result'] = 0;
            unset($check['response_data']);
            return $check;
        } else {
            $uid = parent::gen_uuid();

            $layanan = self::$query
                ->insert('master_tindakan', array(
                        "uid" => $uid,
                        "nama" => $parameter['nama'],
                        "kelompok" => 'RAD',
                        "created_at" => parent::format_date(),
                        "updated_at" => parent::format_date()
                    )
                )
                ->execute();

            if ($layanan['response_result'] > 0) {
                $tindakan = self::$query
                    ->insert('master_radiologi_tindakan', array(
                            'uid_tindakan' => $uid,
                            'jenis' => $parameter['jenis'],
                            'created_at' => parent::format_date(),
                            'updated_at' => parent::format_date()
                        )
                    )
                    ->execute();

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
                            "master_tindakan, master_radiologi_tindakan",
                            'I',
                            parent::format_date(),
                            'N',
                            $UserData['data']->log_id
                        ),
                        'class' => __CLASS__
                    )
                );
            }
        }

        $result = array(
            "layanan" => $layanan,
            "tindakan" => $tindakan
        );

        return $result;
    }

    private function edit_tindakan($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $old = self::get_tindakan_detail($parameter['uid']);

        $layanan = self::$query
            ->update('master_tindakan', array(
                    "nama" => $parameter['nama'],
                    "updated_at" => parent::format_date()
                )
            )
            ->where(array(
                'master_tindakan.uid' => '= ?',
                'AND',
                'master_tindakan.deleted_at' => 'IS NULL'
            ), array(
                    $parameter['uid']
                )
            )
            ->execute();

        if ($layanan['response_result'] > 0) {
            $tindakan = self::$query
                ->update('master_radiologi_tindakan', array(
                        "jenis" => $parameter['jenis'],
                        "updated_at" => parent::format_date()
                    )
                )
                ->where(array(
                    'master_radiologi_tindakan.uid_tindakan' => '= ?',
                    'AND',
                    'master_radiologi_tindakan.deleted_at' => 'IS NULL'
                ), array(
                        $parameter['uid']
                    )
                )
                ->execute();

            // if ($tindakan['response_result'] > 0){
            // 	foreach ($dataObj['penjamin'] as $key => $value) {
            // 		$cek = self::get_tindakan_penjamin_detail(array(
            // 				'departemen'=>__UIDRADIOLOGI__,
            // 				'tindakan'=>$parameter['uid'],
            // 				'penjamin'=>$key
            // 			));

            // 		if ($cek['response_result'] > 0){
            // 			$penjamin = self::$query
            // 				->update('master_poli_tindakan_penjamin', array(
            // 						'harga'=>$value,
            // 						'updated_at'=>parent::format_date()
            // 					)
            // 				)
            // 				->where(array(
            // 						'master_poli_tindakan_penjamin.uid_poli' => '= ?',
            // 						'AND',
            // 						'master_poli_tindakan_penjamin.uid_tindakan' => '= ?',
            // 						'AND',
            // 						'master_poli_tindakan_penjamin.uid_penjamin' => '= ?'
            // 					),array(
            // 						__UIDRADIOLOGI__,
            // 						$parameter['uid'],
            // 						$key,
            // 					)
            // 				)
            // 				->execute();
            // 		} else {
            // 			$penjamin = self::$query
            // 				->insert('master_poli_tindakan_penjamin', array(
            // 						'harga'=>$value,
            // 						'uid_poli'=>__UIDRADIOLOGI__,
            // 						'uid_tindakan'=>$parameter['uid'],
            // 						'uid_penjamin'=>$key,
            // 						'created_at'=>parent::format_date(),
            // 						'updated_at'=>parent::format_date()
            // 					)
            // 				)
            // 				->execute();
            // 		}
            // 	}
            // }

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
                        'master_tindakan, master_radiologi_tindakan',
                        'U',
                        json_encode($old),
                        json_encode($parameter),
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                )
            );
        }

        $result = array(
            "layanan" => $layanan,
            "tindakan" => $tindakan
        );

        return $result;
    }

    private function add_order_radiologi2($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        //GET Last Invoice
        $lastNumber = self::$query->select('rad_order', array(
            'no_order'
        ))
            ->where(array(
                'EXTRACT(month FROM created_at)' => '= ?'
            ), array(
                intval(date('m'))
            ))
            ->execute();

        $get_antrian = new Antrian(self::$pdo);
        $antrian = $get_antrian->get_antrian_detail('antrian', $parameter['uid_antrian']);
        $data_antrian = $antrian['response_data'][0];
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
                    $data_antrian['dokter']['uid']
                )
            )
            ->execute();
        if (count($get_asesmen['response_data']) > 0) {
            $uidAsesmen = $get_asesmen['response_data'][0]['uid'];
        } else {
            $uidAsesmen = parent::gen_uuid();
            $asesmen_poli = self::$query
                ->insert('asesmen',
                    array(
                        'uid' => $uidAsesmen,
                        'poli' => $data_antrian['departemen'],
                        'kunjungan' => $data_antrian['kunjungan'],
                        'antrian' => $parameter['uid_antrian'],
                        'pasien' => $data_antrian['pasien'],
                        'dokter' => $data_antrian['dokter'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    )
                )
                ->execute();

            if ($asesmen_poli['response_result'] > 0) {

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
                        $uidAsesmen,
                        $UserData['data']->uid,
                        'asesmen',
                        'I',
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                ));

                $result['new_asesmen'] = $asesmen_poli;
            }
        }


        $uidRadioOrder = parent::gen_uuid();
        //New Order
        $data = self::$query->insert('rad_order', array(
            'uid' => $uidRadioOrder,
            'asesmen' => $uidAsesmen,
            'status' => 'V', //Langsung masuk verifikator
            'pasien' => $data_antrian['pasien'],
            'kunjungan' => $data_antrian['kunjungan'],
            'no_order' => 'RO/' . date('Y/m') . '/' . str_pad(strval(count($lastNumber['response_data']) + 1), 4, '0', STR_PAD_LEFT),
            'selesai' => 'false',
            'waktu_order' => parent::format_date(),
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->execute();


        if($data['response_result'] > 0) {
            $Invoice = new Invoice(self::$pdo);
            $invoice_charge = array();
            foreach ($parameter['listTindakan'] as $keyTindakan => $valueTindakan) {
                $checkDetailRadiologi = self::$query
                    ->select('rad_order_detail', array('id'))
                    ->where(
                        array(
                            'rad_order_detail.radiologi_order' => '= ?',
                            'AND',
                            'rad_order_detail.tindakan' => '= ?',
                            'AND',
                            'rad_order_detail.deleted_at' => 'IS NULL'
                        ), array(
                            $uidRadioOrder,
                            $keyTindakan
                        )
                    )
                    ->execute();

                if ($checkDetailRadiologi['response_result'] == 0) {


                    $addDetailRadiologi = self::$query
                        ->insert('rad_order_detail',
                            array(
                                'radiologi_order' => $uidRadioOrder,
                                'tindakan' => $keyTindakan,
                                'penjamin' => $valueTindakan,
                                'created_at' => parent::format_date(),
                                'updated_at' => parent::format_date()
                            )
                        )
                        ->execute();

                    if ($addDetailRadiologi['response_result'] > 0) {


                        //Check Invoice

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
                                'keterangan' => 'Tagihan radiologi'
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
                                $valueTindakan,
                                __UID_KELAS_GENERAL_RAD__,    //Fix 1 harga kelas GENERAL
                                $keyTindakan
                            ))
                            ->execute();
                        $HargaFinal = (count($HargaTindakan['response_data']) > 0) ? $HargaTindakan['response_data'][0]['harga'] : 0;

                        $InvoiceDetail = $Invoice->append_invoice(array(
                            'invoice' => $TargetInvoice,
                            'item' => $keyTindakan,
                            'item_origin' => 'master_tindakan',
                            'qty' => 1,
                            'harga' => $HargaFinal,
                            'status_bayar' => ($valueTindakan === __UIDPENJAMINUMUM__) ? 'V' : 'Y', // Check Penjamin. Jika non umum maka langsung lunas
                            'subtotal' => $HargaFinal,
                            'discount' => 0,
                            'discount_type' => 'N',
                            'pasien' => $data_antrian['pasien'],
                            'penjamin' => $valueTindakan,
                            'billing_group' => 'radiologi',
                            'keterangan' => 'Biaya Radiologi',
                            'departemen' => $data_antrian['departemen']
                        ));

                        array_push($invoice_charge, $InvoiceDetail);

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
                                $uidRadioOrder . "; " . $keyTindakan,
                                $UserData['data']->uid,
                                'rad_order_detail',
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
        $data['charge'] = $invoice_charge;
        return $data;
    }

    private function add_order_radiologi($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        //GET Last Invoice
        $lastNumber = self::$query->select('rad_order', array(
            'no_order'
        ))
            ->where(array(
                'EXTRACT(month FROM created_at)' => '= ?'
            ), array(
                intval(date('m'))
            ))
            ->execute();

        $get_antrian = new Antrian(self::$pdo);
        $antrian = $get_antrian->get_antrian_detail('antrian', $parameter['uid_antrian']);

        $result = [];
        if ($antrian['response_result'] > 0) {

            $data_antrian = $antrian['response_data'][0];        //get antrian data

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

            $uidRadiologiOrder = "";
            $statusOrder = "NEW";    //parameter to set status order, set "NEW" for default
            if ($get_asesmen['response_result'] > 0) {
                $uidAsesmen = $get_asesmen['response_data'][0]['uid'];

                $checkRadiologiOrder = self::$query
                    ->select('rad_order', array('uid'))
                    ->where(
                        array(
                            'rad_order.asesmen' => '= ?',
                            'AND',
                            'rad_order.deleted_at' => 'IS NULL'
                        )
                        , array($uidAsesmen)
                    )
                    ->execute();

                if ($checkRadiologiOrder['response_result'] > 0) {
                    $statusOrder = "OLD";        //$statusOrder will set "OLD" if has ever added
                    $uidRadiologiOrder = $checkRadiologiOrder['response_data'][0]['uid'];

                    $result['old_radiologi_order'] = $checkRadiologiOrder;

                    if (count($parameter['listTindakanDihapus']) > 0) {

                        foreach ($parameter['listTindakanDihapus'] as $key => $value) {
                            $hapusTindakanTerpilih = self::$query
                                ->delete('rad_order_detail')
                                ->where(array(
                                    'rad_order_detail.radiologi_order' => '= ?',
                                    'AND',
                                    'rad_order_detail.tindakan' => '= ?',
                                    'AND',
                                    'rad_order_detail.keterangan' => 'IS NULL',
                                    'AND',
                                    'rad_order_detail.kesimpulan' => 'IS NULL',
                                    'AND',
                                    'rad_order_detail.deleted_at' => 'IS NULL'
                                ), array(
                                        $uidRadiologiOrder,
                                        $value
                                    )
                                )
                                ->execute();

                            if ($hapusTindakanTerpilih['response_result'] > 0) {
                                $result['delete_tindakan_order'] = $hapusTindakanTerpilih;

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
                                            'asesmen: ' . $uidAsesmen . "; tindakan: " . $value,
                                            $UserData['data']->uid,
                                            'rad_order_detail',
                                            'D',
                                            parent::format_date(),
                                            'N',
                                            $UserData['data']->log_id
                                        ),
                                        'class' => __CLASS__
                                    )
                                );
                            }

                        }

                    }
                }

            } else {
                //new asesmen
                $uidAsesmen = parent::gen_uuid();
                //$MasterUID = $uidAsesmen; variabel mubajir??
                $asesmen_poli = self::$query
                    ->insert('asesmen',
                        array(
                            'uid' => $uidAsesmen,
                            'poli' => $data_antrian['departemen'],
                            'kunjungan' => $data_antrian['kunjungan'],
                            'antrian' => $parameter['uid_antrian'],
                            'pasien' => $data_antrian['pasien'],
                            'dokter' => $data_antrian['dokter'],
                            'created_at' => parent::format_date(),
                            'updated_at' => parent::format_date()
                        )
                    )
                    ->execute();

                if ($asesmen_poli['response_result'] > 0) {

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
                            $uidAsesmen,
                            $UserData['data']->uid,
                            'asesmen',
                            'I',
                            parent::format_date(),
                            'N',
                            $UserData['data']->log_id
                        ),
                        'class' => __CLASS__
                    ));

                    $result['new_asesmen'] = $asesmen_poli;
                }
            }

            if ($statusOrder == "NEW") {
                $uidRadiologiOrder = "";

                if (count($parameter['listTindakan']) > 0) {
                    //Cek Penjamin dulu. Jika non umum langsung lunas. gitulah kira-kira
                    /*if ($data_antrian['penjamin'] == __UIDPENJAMINUMUM__) {
                        $status_lunas = 'V';
                    } else {
                        $status_lunas = 'P';
                    }*/

                    $status_lunas = 'N'; //Dulu V

                    $uidRadiologiOrder = parent::gen_uuid();
                    $radiologiOrder = self::$query
                        ->insert('rad_order',
                            array(
                                'uid' => $uidRadiologiOrder,
                                'asesmen' => $uidAsesmen,
                                'waktu_order' => parent::format_date(),
                                'no_order' => 'RO/' . date('Y/m') . '/' . str_pad(strval(count($lastNumber['response_data']) + 1), 4, '0', STR_PAD_LEFT),
                                'selesai' => 'false',
                                'status' => $status_lunas,
                                'pasien' => $data_antrian['pasien'],
                                'kunjungan' => $data_antrian['kunjungan'],
                                'petugas' => $UserData['data']->uid,
                                'created_at' => parent::format_date(),
                                'updated_at' => parent::format_date()
                            )
                        )
                        ->execute();

                    if ($radiologiOrder['response_result'] > 0) {

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
                                $uidRadiologiOrder,
                                $UserData['data']->uid,
                                'rad_order',
                                'I',
                                parent::format_date(),
                                'N',
                                $UserData['data']->log_id
                            ),
                            'class' => __CLASS__
                        ));

                    }

                    $result['new_radiologi_order'] = $radiologiOrder;
                } else {

                    $result['response_message'] = 'Nothing in action';
                }

            }

            //check if uid labOrder has no empty and add tindakan
            if ($uidRadiologiOrder != "") {
                /*	KETERANGAN
                    format json listTindakan:
                    listTindakan : {
                        uid_tindakan_1 : uid_penjamin_1,
                        uid_tinadkan_2 : uid_penjamin_2
                    }
                */
                $DetailResult = array();
                $InvoiceResult = array();
                foreach ($parameter['listTindakan'] as $keyTindakan => $valueTindakan) {
                    $checkDetailRadiologi = self::$query
                        ->select('rad_order_detail', array('id'))
                        ->where(
                            array(
                                'rad_order_detail.radiologi_order' => '= ?',
                                'AND',
                                'rad_order_detail.tindakan' => '= ?',
                                'AND',
                                'rad_order_detail.deleted_at' => 'IS NULL'
                            ), array(
                                $uidRadiologiOrder,
                                $keyTindakan
                            )
                        )
                        ->execute();

                    if ($checkDetailRadiologi['response_result'] == 0) {


                        $addDetailRadiologi = self::$query
                            ->insert('rad_order_detail',
                                array(
                                    'radiologi_order' => $uidRadiologiOrder,
                                    'tindakan' => $keyTindakan,
                                    'penjamin' => $valueTindakan,
                                    'created_at' => parent::format_date(),
                                    'updated_at' => parent::format_date()
                                )
                            )
                            ->execute();

                        if ($addDetailRadiologi['response_result'] > 0) {


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
                                    'keterangan' => 'Tagihan radiologi'
                                );
                                $NewInvoice = $Invoice::create_invoice($InvMasterParam);
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
                                    $valueTindakan,
                                    __UID_KELAS_GENERAL_RAD__,    //Fix 1 harga kelas GENERAL
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
                                    'status_bayar' => ($valueTindakan == __UIDPENJAMINUMUM__) ? 'V' : 'Y', // Check Penjamin. Jika non umum maka langsung lunas
                                    'subtotal' => $HargaFinal,
                                    'discount' => 0,
                                    'discount_type' => 'N',
                                    'pasien' => $data_antrian['pasien'],
                                    'penjamin' => $valueTindakan,
                                    'billing_group' => 'radiologi',
                                    'keterangan' => 'Biaya Radiologi'
                                ));
                            }

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
                                    $uidRadiologiOrder . "; " . $keyTindakan,
                                    $UserData['data']->uid,
                                    'rad_order_detail',
                                    'I',
                                    parent::format_date(),
                                    'N',
                                    $UserData['data']->log_id
                                ),
                                'class' => __CLASS__
                            ));
                            array_push($DetailResult, $addDetailRadiologi);
                            array_push($InvoiceResult, $InvoiceDetail);
                        }
                    }
                }

                //update status antrian
                $antrian_status = self::$query->update('antrian_nomor', array(
                    'status' => ($data_antrian['penjamin'] === __UIDPENJAMINUMUM__) ? 'V' : 'P'
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

                $result['new_radiologi_detail'] = $DetailResult;
                $result['invoice_detail'] = $InvoiceResult;
            }
        }
        return $result;
    }

    private function update_hasil_radiologi($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $result = array(
            'dir_msg' => '',
            'order_detail' => array(),
            'response_upload' => array(
                'response_message' => '',
                'response_result' => ''
            ),
            'response_delete_doc' => array(
                'response_message' => '',
                'response_result' => ''
            )
        );

        if (isset($parameter['tindakanID'])) {
            //$old = self::get_radiologi_order_detail_item($parameter['tindakanID']);

            $detail_data = json_decode($parameter['detail'], true);
            foreach ($detail_data as $key => $value) {

                $tindakan_iden = explode('_', $key);
                $updateData = self::$query
                    ->update('rad_order_detail', array(
                        'keterangan' => $value['keterangan'],
                        'kesimpulan' => $value['kesimpulan'],
                        'updated_at' => parent::format_date()
                    ))
                    ->where(array(
                        'rad_order_detail.radiologi_order' => '= ?',
                        'AND',
                        'rad_order_detail.id' => '= ?',
                        'AND',
                        'rad_order_detail.deleted_at' => 'IS NULL'
                    ), array(
                        $parameter['uid_radiologi_order'],
                        $tindakan_iden[count($tindakan_iden) - 1]
                    ))
                    ->execute();
                array_push($result['order_detail'], $updateData);
            }

            //$result['order_detail'] = json_decode($parameter['detail']);

            /*$updateData = self::$query
                ->update('rad_order_detail', array(
                        'keterangan' => $parameter['keteranganPeriksa'],
                        'kesimpulan' => $parameter['kesimpulanPeriksa'],
                        'updated_at' => parent::format_date()
                    )
                )
                ->where(array(
                    'rad_order_detail.id' => '= ?',
                    'AND',
                    'rad_order_detail.deleted_at' => 'IS NULL'
                ), array($parameter['tindakanID'])
                )
                ->execute();

            if ($updateData['response_result'] > 0) {
                $log = parent::log(
                    array(
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
                            $parameter['tindakanID'],
                            $UserData['data']->uid,
                            'rad_order_detail',
                            'U',
                            json_encode($old),
                            json_encode($parameter),
                            parent::format_date(),
                            'N',
                            $UserData['data']->log_id
                        ),
                        'class' => __CLASS__
                    )
                );
            }
            $result['order_detail'] = $updateData;*/
        }

        //create new
        $folder_structure = '../document/radiologi/' . $parameter['uid_radiologi_order'];
        if (!is_dir($folder_structure)) {

            if (!mkdir($folder_structure, 0777, true)) {
                $result['dir_msg'] = 'Failed to create folders...';
            }
            //mkdir('../document/radiologi/' . $parameter['uid_radiologi_order'], 0755);
        } else {
            $result['dir_msg'] = 'Dir available...';
        }

        if (is_writeable($folder_structure)) {
            $result['response_upload'] = array();
            //$imageDatas = json_decode($_FILES['fileList'], true);

            //get maximum id
            $get_max = self::$query
                ->select('rad_order_document', array(
                        'id'
                    )
                )
                ->order(
                    array(
                        'rad_order_document.created_at' => 'DESC'
                    )
                )
                ->execute();

            $max = 0;
            if ($get_max['response_result'] > 0) {
                $max = $get_max['response_data'][0]['id'];
            }

            for ($a = 0; $a < count($_FILES['fileList']); $a++) {
                $max++;

                if (!empty($_FILES['fileList']['tmp_name'][$a])) {
                    $nama_lampiran = 'R_' . str_pad($max, 6, "0", STR_PAD_LEFT);

                    if (move_uploaded_file($_FILES['fileList']['tmp_name'][$a], '../document/radiologi/' . $parameter['uid_radiologi_order'] . '/' . $nama_lampiran . '.pdf')) {
                        array_push($result['response_upload'], 'Berhasil diupload');
                        $lampiran = self::$query
                            ->insert('rad_order_document', array(
                                'radiologi_order' => $parameter['uid_radiologi_order'],
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

        if (count($parameter['deletedDocList']) > 0) {
            foreach ($parameter['deletedDocList'] as $key => $value) {
                $getLampiran = self::$query
                    ->select('rad_order_document', array(
                            'lampiran'
                        )
                    )
                    ->where(array(
                        'rad_order_document.id' => '= ?'
                    ), array($value)
                    )
                    ->execute();

                if ($getLampiran['response_result'] > 0) {
                    $nama_lampiran_hapus = $getLampiran['response_data'][0]['lampiran'];

                    $hapusLampiran = self::$query
                        ->delete('rad_order_document')
                        ->where(array(
                            'rad_order_document.id' => '= ?'
                        ), array($value)
                        )
                        ->execute();

                    if ($hapusLampiran['response_result'] > 0) {
                        unlink('../document/radiologi/' . $parameter['uid_radiologi_order'] . '/' . $nama_lampiran_hapus);

                        $result['response_delete_doc']['response_result'] = 1;
                    }

                    $result['response_delete_doc']['response_data'] = $hapusLampiran;
                }
            }
        }
        //return (is_writable($folder_structure));

        //return count($parameter['deletedDocList']);
        return $result;
    }

    public function __DELETE__($parameter = array())
    {
        switch ($parameter[6]) {
            case 'master_radiologi_tindakan':
                return self::delete_tindakan($parameter);
                break;
            default:
                return self::delete($parameter);
                break;
        }
    }

    /*-----------------------------------------------------------*/

    /*------------------- GET DATA PASIEN and ANTRIAN --------------------*/

    private function delete_tindakan($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $data = self::$query
            ->delete($parameter[6])
            ->where(array(
                $parameter[6] . '.uid' => '= ?'
            ), array(
                    $parameter[7]
                )
            )
            ->execute();

        if ($data['response_result'] > 0) {
            $tindakan = self::$query
                ->delete('master_tindakan')
                ->where(array(
                    'master_tindakan.uid' => '= ?'
                ), array(
                        $parameter[7]
                    )
                )
                ->execute();

            if ($tindakan['response_result'] > 0) {
                $penjamin = self::$query
                    ->delete('master_poli_tindakan_penjamin')
                    ->where(array(
                        'master_poli_tindakan_penjamin.uid_poli' => '= ?',
                        'AND',
                        'master_poli_tindakan_penjamin.uid_tindakan' => '= ?'
                    ), array(
                            __UIDRADIOLOGI__,
                            $parameter['7']
                        )
                    )
                    ->execute();
            }

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
                )
            );
        }

        $result = array(
            "layanan" => $data,
            "tindakan" => $tindakan,
            "penjamin" => $penjamin
        );

        return $result;
    }
    /*-------------------------------------------------------*/

    /*------------------- GET TINDAKAN RADIOLOGI FOR DOKTER --------------------*/

    private function delete($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $data = self::$query
            ->delete($parameter[6])
            ->where(array(
                $parameter[6] . '.uid' => '= ?'
            ), array(
                    $parameter[7]
                )
            )
            ->execute();

        if ($data['response_result'] > 0) {
            if ($parameter[6] == 'master_tindakan') {
                $delete_child = self::$query
                    ->delete('master_radiologi_tindakan')
                    ->where(array(
                        'master_radiologi_tindakan.uid_tindakan' => '= ?'
                    ), array(
                            $parameter[7]
                        )
                    )
                    ->execute();
            }

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
                )
            );
        }

        return $data;
    }

    /*-------------------------------------------------------*/

    private function get_tindakan_penjamin_detail($parameter)
    {
        $data = self::$query
            ->select('master_poli_tindakan_penjamin',
                array(
                    'id',
                    'harga',
                    'uid_poli',
                    'uid_tindakan',
                    'uid_penjamin',
                    'created_at',
                    'updated_at'
                )
            )
            ->where(array(
                'master_poli_tindakan_penjamin.deleted_at' => 'IS NULL',
                'AND',
                'master_poli_tindakan_penjamin.uid_poli' => '= ?',
                'AND',
                'master_poli_tindakan_penjamin.uid_tindakan' => '= ?',
                'AND',
                'master_poli_tindakan_penjamin.uid_penjamin' => '= ?'
            ),
                array(
                    $parameter['departemen'],
                    $parameter['tindakan'],
                    $parameter['penjamin']
                )
            )
            ->execute();

        return $data;
    }
}