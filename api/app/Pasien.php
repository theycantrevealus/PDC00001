<?php

namespace PondokCoder;

use PondokCoder\Penjamin as Penjamin;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;
use PondokCoder\Terminologi as Terminologi;
use function Sodium\library_version_minor;

class Pasien extends Utility
{
    static $pdo;
    static $query;

    protected static function getConn()
    {
        return self::$pdo;
    }

    public function __construct($connection)
    {
        self::$pdo = $connection;
        self::$query = new Query(self::$pdo);
    }

    public function __GET__($parameter = array())
    {
        try {
            switch ($parameter[1]) {
                case 'format_rm':
                    return self::reformat_rm();
                    break;

                case 'pasien':
                    return self::get_pasien('pasien');
                    break;

                case 'pasien-detail':
                    return self::get_pasien_detail('pasien', $parameter[2]);
                    break;

                case 'pasien-info':
                    return self::get_pasien_info('pasien', $parameter[2]);
                    break;

                case 'cek-nik':
                    return self::cekNIK($parameter[2]);
                    break;

                case 'cek-no-rm':
                    return self::cekNoRM($parameter[2]);
                    break;

                case 'select2':
                    return self::get_pasien_select2($parameter);
                    break;

                case 'asesmen_resep_lupa':
                    return self::asesmen_resep_lupa($parameter);
                    break;

                default:
                    # code...
                    break;
            }
        } catch (QueryException $e) {
            return 'Error => ' . $e;
        }
    }

    public function __POST__($parameter = array())
    {
        switch ($parameter['request']) {
            case 'pulangkan_pasien':
                return self::pulangkan_pasien($parameter);
                break;

            case 'tambah-pasien':
                return self::tambah_pasien('pasien', $parameter);
                break;

            case 'edit-pasien':
                return self::edit_pasien('pasien', $parameter);
                break;

            case 'reformat_pasien':
                return self::reformat_pasien($parameter);
                break;

            case 'master_pasien_import_fetch':
                return self::master_pasien_import_fetch($parameter);
                break;

            case 'proceed_import_pasien':
                return self::proceed_import_pasien($parameter);
                break;

            case 'get_pasien_back_end':
                return self::get_pasien_back_end($parameter);
                break;

            case 'bpjs_sep_add_offline':
                return self::bpjs_sep_add_offline($parameter);
                break;

            case 'pasien_cppt':
                return self::pasien_cppt($parameter);
                break;

            default:
                # code...
                break;
        }
    }

    public function __DELETE__($parameter = array())
    {
        return self::delete_pasien('pasien', $parameter);
    }


    /*=======================GET FUNCTION======================*/
    private function pasien_cppt($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (!isset($parameter['pasien']) || empty($parameter['pasien'])) {
            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                $paramData = array(
                    'asesmen.deleted_at' => 'IS NULL',
                    'AND',
                    'pegawai.deleted_at' => 'IS NULL',
                    'AND',
                    'pasien.deleted_at' => 'IS NULL',
                    'AND',
                    'master_poli.deleted_at' => 'IS NULL'
                );

                $paramValue = array();
            } else {
                $paramData = array(
                    'asesmen.deleted_at' => 'IS NULL',
                    'AND',
                    'pegawai.deleted_at' => 'IS NULL',
                    'AND',
                    'pasien.deleted_at' => 'IS NULL',
                    'AND',
                    'master_poli.deleted_at' => 'IS NULL',
                );
                $paramValue = array();
            }
        } else {
            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                $paramData = array(
                    'asesmen.deleted_at' => 'IS NULL',
                    'AND',
                    'pegawai.deleted_at' => 'IS NULL',
                    'AND',
                    'pasien.deleted_at' => 'IS NULL',
                    'AND',
                    'master_poli.deleted_at' => 'IS NULL',
                    'AND',
                    'asesmen.pasien' => '= ?'
                );

                $paramValue = array($parameter['pasien']);
            } else {
                $paramData = array(
                    'asesmen.deleted_at' => 'IS NULL',
                    'AND',
                    'pegawai.deleted_at' => 'IS NULL',
                    'AND',
                    'pasien.deleted_at' => 'IS NULL',
                    'AND',
                    'master_poli.deleted_at' => 'IS NULL',
                    'AND',
                    'asesmen.pasien' => '= ?'
                );
                $paramValue = array($parameter['pasien']);
            }
        }

        if ($parameter['length'] < 0) {
            $data = self::$query->select('asesmen', array(
                'uid',
                'poli',
                'dokter',
                'perawat',
                'created_at'
            ))
                ->join('pegawai', array(
                    'nama'
                ))
                ->join('master_poli', array(
                    'nama as nama_poli',
                    'poli_asesmen'
                ))
                ->join('pasien', array(
                    'nama as nama_pasien',
                    'no_rm'
                ))
                ->on(array(
                    array('asesmen.dokter', '=', 'pegawai.uid'),
                    array('asesmen.poli', '=', 'master_poli.uid'),
                    array('asesmen.pasien', '=', 'pasien.uid')
                ))
                ->order(array(
                    'created_at' => 'DESC'
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('asesmen', array(
                'uid',
                'poli',
                'dokter',
                'perawat',
                'created_at'
            ))
                ->join('pegawai', array(
                    'nama as nama_dokter'
                ))
                ->join('master_poli', array(
                    'nama as nama_poli',
                    'poli_asesmen'
                ))
                ->join('pasien', array(
                    'nama as nama_pasien',
                    'no_rm'
                ))
                ->on(array(
                    array('asesmen.dokter', '=', 'pegawai.uid'),
                    array('asesmen.poli', '=', 'master_poli.uid'),
                    array('asesmen.pasien', '=', 'pasien.uid')
                ))
                ->order(array(
                    'created_at' => 'DESC'
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
            $data['response_data'][$key]['created_at_parsed'] = date('d F Y, H:i', strtotime($value['created_at']));
            //Load Asesmen Detail

            $asesmenDetailMedis = 'asesmen_medis_' . $value['poli_asesmen'];
            $asesmenDetailRawat = 'asesmen_rawat_' . $value['poli_asesmen'];

            $AsesmenDetailMedisData = self::$query->select($asesmenDetailMedis, array(
                '*'
            ))
                ->where(array(
                    $asesmenDetailMedis . '.asesmen' => '= ?'
                ), array(
                    $value['uid']
                ))
                ->execute();

            $AsesmenDetailRawatData = self::$query->select($asesmenDetailRawat, array(
                '*'
            ))
                ->where(array(
                    $asesmenDetailRawat . '.asesmen' => '= ?'
                ), array(
                    $value['uid']
                ))
                ->execute();
            $data['response_data'][$key]['medis'] = $AsesmenDetailMedisData['response_data'][0];
            $data['response_data'][$key]['rawat'] = $AsesmenDetailRawatData['response_data'][0];

            $autonum++;
        }

        $itemTotal = self::$query->select('asesmen', array(
            'uid'
        ))
            ->join('pegawai', array(
                'nama'
            ))
            ->join('master_poli', array(
                'nama as nama_poli'
            ))
            ->join('pasien', array(
                'nama as nama_pasien'
            ))
            ->on(array(
                array('asesmen.dokter', '=', 'pegawai.uid'),
                array('asesmen.poli', '=', 'master_poli.uid'),
                array('asesmen.pasien', '=', 'pasien.uid')
            ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function get_pasien($table)
    {
        $data = self::$query
            ->select(
                $table,
                array(
                    'uid',
                    'no_rm',
                    'nama',
                    'panggilan AS id_panggilan',
                    'tanggal_lahir',
                    'jenkel AS id_jenkel',
                    'created_at',
                    'updated_at'
                )
            )
            ->where(
                array(
                    $table . '.deleted_at' => 'IS NULL'
                )
            )
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
            $data['response_data'][$key]['tanggal_lahir'] = parent::dateToIndo($data['response_data'][$key]['tanggal_lahir']);
            $term = new Terminologi(self::$pdo);

            $value = $data['response_data'][$key]['id_panggilan'];
            $param = ['', 'terminologi-items-detail', $value];
            $get_panggilan = $term->__GET__($param);
            $data['response_data'][$key]['panggilan'] = $get_panggilan['response_data'][0]['nama'];


            $value = $data['response_data'][$key]['id_jenkel'];
            $param = ['', 'terminologi-items-detail', $value];
            $get_jenkel = $term->__GET__($param);
            $data['response_data'][$key]['jenkel'] = $get_jenkel['response_data'][0]['nama'];

            $tgl_daftar = date("Y-m-d", strtotime($data['response_data'][$key]['created_at']));
            $data['response_data'][$key]['tgl_daftar'] = parent::dateToIndo($tgl_daftar);
        }

        return $data;
    }

    public function get_pasien_info($table, $parameter)
    {
        $data = self::$query
            ->select(
                $table,
                array(
                    'uid',
                    'no_rm',
                    'nik',
                    'nama',
                    'panggilan',
                    'tanggal_lahir',
                    'tempat_lahir',
                    'jenkel',
                    'agama',
                    'suku',
                    'pendidikan',
                    'goldar',
                    'pekerjaan',
                    'nama_ayah',
                    'nama_ibu',
                    'status_pernikahan',
                    'nama_suami_istri',
                    //'status_suami_istri',
                    'alamat',
                    'alamat_rt',
                    'alamat_rw',
                    'alamat_provinsi',
                    'alamat_kabupaten',
                    'alamat_kecamatan',
                    'alamat_kelurahan',
                    'warganegara',
                    'no_telp',
                    'email',
                    'created_at',
                    'updated_at'
                )
            )
            ->where(
                array(
                    $table . '.deleted_at' => 'IS NULL',
                    'AND',
                    $table . '.uid' => '= ?'
                ),
                array(
                    $parameter
                )
            )
            ->execute();

        $autonum = 1;
        $Terminologi = new Terminologi(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            //Panggilan
            $TerminologiInfo = $Terminologi->get_terminologi_items_detail('terminologi_item', $value['panggilan']);
            $data['response_data'][$key]['panggilan_name'] = $TerminologiInfo['response_data'][0];


            //Jenkel
            $JenkelInfo = $Terminologi->get_terminologi_items_detail('terminologi_item', $value['jenkel']);
            $data['response_data'][$key]['jenkel_detail'] = $JenkelInfo['response_data'][0];

            //Agama
            $AgamaInfo = $Terminologi->get_terminologi_items_detail('terminologi_item', $value['agama']);
            $data['response_data'][$key]['agama_detail'] = $AgamaInfo['response_data'][0];

            $data['response_data'][$key]['tanggal_lahir_parsed'] = date('d F Y', strtotime($value['tanggal_lahir']));

            $data['response_data'][$key]['usia'] = date("Y") - date("Y", strtotime($value['tanggal_lahir']));
            $data['response_data'][$key]['periode'] = date('m/y', strtotime($value['created_at']));


            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }

        return $data;
    }

    public function get_pasien_detail($table, $parameter)
    {
        $data = self::$query
            ->select(
                $table,
                array(
                    'uid',
                    'no_rm',
                    'nik',
                    'nama',
                    'panggilan',
                    'tanggal_lahir',
                    'tempat_lahir',
                    'jenkel',
                    'agama',
                    'suku',
                    'pendidikan',
                    'goldar',
                    'pekerjaan',
                    'nama_ayah',
                    'nama_ibu',
                    'status_pernikahan',
                    'nama_suami_istri',
                    //'status_suami_istri',
                    'alamat',
                    'alamat_rt',
                    'alamat_rw',
                    'alamat_provinsi',
                    'alamat_kabupaten',
                    'alamat_kecamatan',
                    'alamat_kelurahan',
                    'warganegara',
                    'no_telp',
                    'email',
                    'created_at',
                    'updated_at'
                )
            )
            ->where(
                array(
                    $table . '.deleted_at' => 'IS NULL',
                    'AND',
                    $table . '.uid' => '= ?'
                ),
                array(
                    $parameter
                )
            )
            ->execute();

        $autonum = 1;
        $Terminologi = new Terminologi(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            //Panggilan
            $TerminologiInfo = $Terminologi->get_terminologi_items_detail('terminologi_item', $value['panggilan']);
            $data['response_data'][$key]['panggilan_name'] = $TerminologiInfo['response_data'][0];






            $KelurahanInfo = self::$query->select('master_wilayah_kelurahan', array(
                'nama'
            ))
                ->where(array(
                    'master_wilayah_kelurahan.id' => '= ?'
                ), array(
                    $value['alamat_kelurahan']
                ))
                ->execute();
            $data['response_data'][$key]['alamat_kelurahan_parse'] = $KelurahanInfo['response_data'][0]['nama'];

            $KecamatanInfo = self::$query->select('master_wilayah_kecamatan', array(
                'nama'
            ))
                ->where(array(
                    'master_wilayah_kecamatan.id' => '= ?'
                ), array(
                    $value['alamat_kecamatan']
                ))
                ->execute();
            $data['response_data'][$key]['alamat_kecamatan_parse'] = $KecamatanInfo['response_data'][0]['nama'];

            $KabupatenInfo = self::$query->select('master_wilayah_kabupaten', array(
                'nama'
            ))
                ->where(array(
                    'master_wilayah_kabupaten.id' => '= ?'
                ), array(
                    $value['alamat_kabupaten']
                ))
                ->execute();
            $data['response_data'][$key]['alamat_kabupaten_parse'] = $KabupatenInfo['response_data'][0]['nama'];

            $ProvinsiInfo = self::$query->select('master_wilayah_provinsi', array(
                'nama'
            ))
                ->where(array(
                    'master_wilayah_provinsi.id' => '= ?'
                ), array(
                    $value['alamat_provinsi']
                ))
                ->execute();
            $data['response_data'][$key]['alamat_provinsi_parse'] = $ProvinsiInfo['response_data'][0]['nama'];


            //Jenkel
            $JenkelInfo = $Terminologi->get_terminologi_items_detail('terminologi_item', $value['jenkel']);
            $data['response_data'][$key]['jenkel_detail'] = $JenkelInfo['response_data'][0];

            //Pekerjaan
            $PekerjaanInfo = $Terminologi->get_terminologi_items_detail('terminologi_item', $value['pekerjaan']);
            $data['response_data'][$key]['pekerjaan_detail'] = $PekerjaanInfo['response_data'][0];

            //Pendidikan
            $PendidikanInfo = $Terminologi->get_terminologi_items_detail('terminologi_item', $value['pendidikan']);
            $data['response_data'][$key]['pendidikan_detail'] = $PendidikanInfo['response_data'][0];

            //Agama
            $AgamaInfo = $Terminologi->get_terminologi_items_detail('terminologi_item', $value['agama']);
            $data['response_data'][$key]['agama_detail'] = $AgamaInfo['response_data'][0];

            //Suku
            $SukuInfo = $Terminologi->get_terminologi_items_detail('terminologi_item', $value['suku']);
            $data['response_data'][$key]['suku_detail'] = $SukuInfo['response_data'][0];

            //Pernikahan
            $PernikahanInfo = $Terminologi->get_terminologi_items_detail('terminologi_item', $value['status_pernikahan']);
            $data['response_data'][$key]['nikah_detail'] = $PernikahanInfo['response_data'][0];

            //Warga Negara
            $WNInfo = $Terminologi->get_terminologi_items_detail('terminologi_item', $value['warganegara']);
            $data['response_data'][$key]['wn_detail'] = $WNInfo['response_data'][0];

            $data['response_data'][$key]['tanggal_lahir_parsed'] = date('d F Y', strtotime($value['tanggal_lahir']));

            $data['response_data'][$key]['usia'] = date("Y") - date("Y", strtotime($value['tanggal_lahir']));
            $data['response_data'][$key]['periode'] = date('m/y', strtotime($value['created_at']));

            //Penjamin Pasien
            $Detail = self::$query->select('pasien_penjamin', array(
                'penjamin',
                'valid_awal',
                'valid_akhir',
                'rest_meta',
                'terdaftar'
            ))
                ->where(array(
                    'pasien_penjamin.pasien' => '= ?',
                    'AND',
                    'pasien_penjamin.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach ($Detail['response_data'] as $DKey => $DValue) {
                //Detail Penjamin
                $PenjaminDetail = $Penjamin->get_penjamin_detail($DValue['penjamin']);
                $Detail['response_data'][$DKey]['penjamin_detail'] = $PenjaminDetail['response_data'][0];

                $Detail['response_data'][$DKey]['valid_awal'] = date('d F Y', strtotime($DValue['valid_awal']));
                $Detail['response_data'][$DKey]['valid_akhir'] = date('d F Y', strtotime($DValue['valid_akhir']));
                $Detail['response_data'][$DKey]['terdaftar'] = date('d F Y', strtotime($DValue['terdaftar']));
            }
            $data['response_data'][$key]['history_penjamin'] = $Detail['response_data'];


            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }

        return $data;
    }

    /*==================== CRUD ====================*/

    private function pulangkan_pasien($parameter)
    {
        $Antrian = self::$query->select('antrian', array(
            'uid', 'departemen', 'waktu_masuk', 'waktu_keluar',
            'kunjungan'
        ))
            ->where(array(
                'antrian.waktu_masuk' => ' < \'' . $parameter['keluar'] . '\'',
                'AND',
                'antrian.waktu_keluar' => 'IS NULL'
            ), array())
            ->execute();
        foreach ($Antrian['response_data'] as $key => $value) {
            if (date('d', strtotime($value['waktu_masuk'])) !== date('d')) {
                if ($value['departemen'] !== __POLI_IGD__ && $value['departemen'] !== __POLI_INAP__) {
                    //Pulangkan
                    $UAntrian = self::$query->update('antrian', array(
                        'waktu_keluar' => parent::format_date()
                    ))
                        ->where(array(
                            'antrian.uid' => '= ?'
                        ), array(
                            $value['uid']
                        ))
                        ->execute();




                    //Kunjungan
                    $UKunjungan = self::$query->update('kunjungan', array())
                        ->where(array(
                            'kunjungan.uid' => '= ?'
                        ), array(
                            $value['kunjungan']
                        ))
                        ->execute();
                }
            }
        }
        return $Antrian['response_data'];
    }

    private function tambah_pasien($table, $parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $dataObj = $parameter['dataObj'];
        $allData = [];

        $check = self::duplicate_check(array(
            'table' => $table,
            'check' => $dataObj['no_rm']
        ));

        if (count($check['response_data']) > 0) {
            $check['response_message'] = 'Duplicate data detected';
            $check['response_result'] = 0;
            unset($check['response_data']);
            return $check;
        } else {
            $uid = parent::gen_uuid();

            $allData['uid'] = $uid;
            $allData['no_rm'] = str_replace('-', '', $allData['no_rm']);
            $allData['created_at'] = parent::format_date();
            $allData['updated_at'] = parent::format_date();

            foreach ($dataObj as $key => $value) {
                $allData[$key] = $value;
            }

            $pasien = self::$query
                ->insert($table, $allData)
                ->execute();

            if ($pasien['response_result'] > 0) {
                $log = parent::log(
                    array(
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

                $pasien['response_unique'] = $uid;
            }

            return $pasien;
        }
    }

    private function reformat_rm()
    {
        $data = self::$query->select('pasien', array(
            'uid', 'no_rm'
        ))
            ->execute();
        $formatted = array();
        $pattern = '/(-)/i';
        foreach ($data['response_data'] as $key => $value) {
            $newRM = preg_replace($pattern, '', $value['no_rm']);
            if ($newRM !== '') {
                $update = self::$query->update('pasien', array(
                    'no_rm' => $newRM
                ))
                    ->where(array(
                        'pasien.uid' => '= ?'
                    ), array(
                        $value['uid']
                    ))
                    ->execute();
                if ($update['response_result'] > 0) {
                    array_push($formatted, $value['uid'] . '  |   ' . $newRM);
                }
            }
        }
        return $formatted;
    }

    private function edit_pasien($table, $parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $dataObj = $parameter['dataObj'];
        $old = self::get_pasien_detail($table, $parameter['uid']);
        $allData = [];
        foreach ($dataObj as $key => $value) {
            $allData[$key] = $value;
        }
        $allData['updated_at'] = parent::format_date();
        $pasien = self::$query
            ->update($table, $allData)
            ->where(
                array(
                    $table . '.deleted_at' => 'IS NULL',
                    'AND',
                    $table . '.uid' => '= ?'
                ),
                array(
                    $parameter['uid']
                )
            )
            ->execute();

        if ($pasien['response_result'] > 0) {
            unset($parameter['access_token']);

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

        return $pasien;
    }

    private function delete_pasien($table, $parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $pasien = self::$query
            ->delete($table)
            ->where(
                array(
                    $table . '.uid' => '= ?'
                ),
                array(
                    $parameter[6]
                )
            )
            ->execute();

        if ($pasien['response_result'] > 0) {
            $log = parent::log(
                array(
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
                        $parameter[6],
                        $UserData['data']->uid,
                        $table,
                        'D',
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                )
            );
        }
        return $pasien;
    }

    /*private function get_table_col($table_name){
        $data = self::$query
                    ->select('INFORMATION_SCHEMA.COLUMNS', array(
                            'column_name'
                        )
                    )
                    ->where(array(
                            'table_name' => '= ?'
                        ),
                        array(
                            $table_name
                        )
                    )
                    ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }

        return $data;
    }*/

    public function cekNIK($parameter)
    {
        $data = self::$query
            ->select('pasien', array('uid', 'nik'))
            ->where(
                array(
                    'pasien.nik' => '= ?',
                    'AND',
                    'pasien.deleted_at' => 'IS NULL'
                ),
                array(
                    $parameter
                )
            )
            ->execute();

        $result = false;
        if ($data['response_result'] > 0) {
            $result = true;
        }

        return $data;
    }

    private function reformat_pasien($parameter)
    {
        $Data = self::$query->select('pasien', array(
            'uid', 'no_rm'
        ))
            ->where(array(
                'pasien.no_rm' => 'ILIKE ' . '\'%-%\'',
            ), array())
            ->execute();
        $failedData = array();
        foreach ($Data['response_data'] as $key => $value) {
            $U = self::$query->update('pasien', array(
                'no_rm' => str_replace('-', '', $value['no_rm'])
            ))
                ->where(array(
                    'pasien.uid' => '= ?',
                ), array(
                    $value['uid']
                ))
                ->execute();
            array_push($failedData, $U);
        }

        return $failedData;
    }

    private function master_pasien_import_fetch($parameter)
    {
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

    private function proceed_import_pasien($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $duplicate_row = array();
        $termi_item = array();
        $non_active = array();
        $success_proceed = 0;
        $proceed_data = array();
        $failedData = array();

        foreach ($parameter['data_import'] as $key => $value) {
            $check = self::$query->select('pasien', array(
                'uid'
            ))
                ->where(array(
                    'REPLACE(pasien.no_rm, \'-\', \'\')' => '= ?',
                    'AND',
                    'pasien.nik' => '= ?',
                ), array(
                    $value['no_rm'],
                    $value['nik']
                ))
                ->execute();

            //array_push($duplicate_row, $value);
            //Prepare Data
            $data_terminologi = array(
                'agama' => array(
                    'target' => 0,
                    'nama' => $value['agama'],
                    'id' => 5
                ),
                'pendidikan' => array(
                    'target' => 0,
                    'nama' => $value['pendidikan'],
                    'id' => 8
                ),
                'pekerjaan' => array(
                    'target' => 0,
                    'nama' => $value['pekerjaan'],
                    'id' => 9
                ),
                'status_pernikahan' => array(
                    'target' => 0,
                    'nama' => $value['status_pernikahan'],
                    'id' => 10
                ),
            );

            foreach ($data_terminologi as $TermiKey => $TermiValue) {
                $check_terminologi = self::$query->select('terminologi_item', array(
                    'id',
                    'nama',
                    'deleted_at'
                ))
                    ->where(array(
                        'terminologi_item.nama' => '= ?',
                        'AND',
                        'terminologi_item.terminologi' => '= ?'
                    ), array(
                        $TermiValue['nama'], $TermiValue['id']
                    ))
                    ->execute();
                if (count($check_terminologi['response_data']) > 0) {
                    if (!empty($check_terminologi['response_data'][0]['deleted_at'])) {
                        $update_status_delete = self::$query->update('terminologi_item', array(
                            'deleted_at' => NULL
                        ))
                            ->where(array(
                                'terminologi_item.id' => '= ?'
                            ), array(
                                $check_terminologi['response_data'][0]['id']
                            ))
                            ->execute();
                    }
                    $data_terminologi[$TermiKey]['target'] = $check_terminologi['response_data'][0]['id'];
                } else {
                    if ($TermiValue['nama'] != "") {
                        $new_terminologi = self::$query->insert('terminologi_item', array(
                            'nama' => $TermiValue['nama'],
                            'terminologi' => $TermiValue['id'],
                            'created_at' => parent::format_date(),
                            'updated_at' => parent::format_date()
                        ))
                            ->returning('id')
                            ->execute();
                        if ($new_terminologi['response_result'] > 0) {
                            $data_terminologi[$TermiKey]['target'] = $new_terminologi['response_unique'];
                        } else {
                            $data_terminologi[$TermiKey]['target'] = 0;
                        }
                        array_push($termi_item, $new_terminologi);
                    } else {
                        $data_terminologi[$TermiKey]['target'] = 0;
                    }
                }
            }

            //Prepare Jenis Kelamin
            $target_jenkel = 0;
            if ($value['jenkel'] == 'Laki-laki') {
                $target_jenkel = 2;
            } else if ($value['jenkel'] == 'Perempuan') {
                $target_jenkel = 3;
            }

            if (count($check['response_data']) > 0) {
                $updatePasien = self::$query->update('pasien', array(
                    'no_rm' => str_replace('-', '', $value['no_rm']),
                    'nik' => $value['nik'],
                    'nama' => $value['nama'],
                    'jenkel' => intval($target_jenkel),
                    'tempat_lahir' => (isset($value['tempat_lahir']) && !empty($value['tempat_lahir'])) ? $value['tempat_lahir'] : '-',
                    'tanggal_lahir' => $value['tanggal_lahir'],
                    'agama' => intval($data_terminologi['agama']['target']),
                    'status_pernikahan' => intval($data_terminologi['status_pernikahan']['target']),
                    'pendidikan' => intval($data_terminologi['pendidikan']['target']),
                    'pekerjaan' => intval($data_terminologi['pekerjaan']['target']),
                    'alamat' => (isset($value['alamat']) && !empty($value['alamat'])) ? $value['alamat'] : '-',
                    'kode_pos' => (isset($value['kode_pos']) && !empty($value['kode_pos'])) ? $value['kode_pos'] : '-',
                    'no_telp' => (isset($value['no_telp']) && !empty($value['no_telp'])) ? $value['no_telp'] : '-',
                    'email' => (isset($value['email']) && !empty($value['email'])) ? $value['email'] : '-',
                    'nama_ayah' => (isset($value['nama_ayah']) && !empty($value['nama_ayah'])) ? $value['nama_ayah'] : '-',
                    'nama_ibu' => (isset($value['nama_ibu']) && !empty($value['nama_ibu'])) ? $value['nama_ibu'] : '-',
                    'nama_suami_istri' => (isset($value['nama_suami_istri']) && !empty($value['nama_suami_istri'])) ? $value['nama_suami_istri'] : '-',
                    'deleted_at' => NULL
                ))
                    ->where(array(
                        'pasien.uid' => '= ?'
                    ), array(
                        $check['response_data'][0]['uid']
                    ))
                    ->execute();
            } else {
                //New Pasien
                if ($value['no_rm'] != '' && $value['nama'] != '') {
                    $new_pasien = self::$query->insert('pasien', array(
                        'no_rm' => str_replace('-', '', $value['no_rm']),
                        'nik' => $value['nik'],
                        'nama' => $value['nama'],
                        'jenkel' => intval($target_jenkel),
                        'tempat_lahir' => (isset($value['tempat_lahir']) && !empty($value['tempat_lahir'])) ? $value['tempat_lahir'] : '-',
                        'tanggal_lahir' => $value['tanggal_lahir'],
                        'agama' => intval($data_terminologi['agama']['target']),
                        'status_pernikahan' => intval($data_terminologi['status_pernikahan']['target']),
                        'pendidikan' => intval($data_terminologi['pendidikan']['target']),
                        'pekerjaan' => intval($data_terminologi['pekerjaan']['target']),
                        'alamat' => (isset($value['alamat']) && !empty($value['alamat'])) ? $value['alamat'] : '-',
                        'kode_pos' => (isset($value['kode_pos']) && !empty($value['kode_pos'])) ? $value['kode_pos'] : '-',
                        'no_telp' => (isset($value['no_telp']) && !empty($value['no_telp'])) ? $value['no_telp'] : '-',
                        'email' => (isset($value['email']) && !empty($value['email'])) ? $value['email'] : '-',
                        'nama_ayah' => (isset($value['nama_ayah']) && !empty($value['nama_ayah'])) ? $value['nama_ayah'] : '-',
                        'nama_ibu' => (isset($value['nama_ibu']) && !empty($value['nama_ibu'])) ? $value['nama_ibu'] : '-',
                        'nama_suami_istri' => (isset($value['nama_suami_istri']) && !empty($value['nama_suami_istri'])) ? $value['nama_suami_istri'] : '-',
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();

                    if ($new_pasien['response_result'] > 0) {
                        $success_proceed += 1;
                    } else {
                        //array_push($failedData, $new_pasien);
                        // array_push($failedData, array(
                        //     'no_rm' => $value['no_rm'],
                        //     'nik' => $value['nik'],
                        //     'nama' => $value['nama'],
                        //     'jenkel' => intval($target_jenkel),
                        //     'tempat_lahir' => (isset($value['tempat_lahir']) && !empty($value['tempat_lahir'])) ? $value['tempat_lahir'] : '-',
                        //     'tanggal_lahir' => $value['tanggal_lahir'],
                        //     'agama' => intval($data_terminologi['agama']['target']),
                        //     'status_pernikahan' => intval($data_terminologi['status_pernikahan']['target']),
                        //     'pendidikan' => intval($data_terminologi['pendidikan']['target']),
                        //     'pekerjaan' => intval($data_terminologi['pekerjaan']['target']),
                        //     'alamat' => (isset($value['alamat']) && !empty($value['alamat'])) ? $value['alamat'] : '-',
                        //     'kode_pos' => (isset($value['kode_pos']) && !empty($value['kode_pos'])) ? $value['kode_pos'] : '-',
                        //     'no_telp' => (isset($value['no_telp']) && !empty($value['no_telp'])) ? $value['no_telp'] : '-',
                        //     'email' => (isset($value['email']) && !empty($value['email'])) ? $value['email'] : '-',
                        //     'nama_ayah' => (isset($value['nama_ayah']) && !empty($value['nama_ayah'])) ? $value['nama_ayah'] : '-',
                        //     'nama_ibu' => (isset($value['nama_ibu']) && !empty($value['nama_ibu'])) ? $value['nama_ibu'] : '-',
                        //     'nama_suami_istri' => (isset($value['nama_suami_istri']) && !empty($value['nama_suami_istri'])) ? $value['nama_suami_istri'] : '-',
                        //     'created_at' => parent::format_date(),
                        //     'updated_at' => parent::format_date()
                        // ));
                    }

                    array_push($proceed_data, $new_pasien);
                }
            }
        }

        return array(
            'duplicate_row' => $duplicate_row,
            'non_active' => $non_active,
            'success_proceed' => $success_proceed,
            'data' => $parameter['data_import'],
            'proceed' => $proceed_data,
            'failed_data' => $failedData,
            'termin_item' => $termi_item,
            'meta_data' => $data_terminologi
        );
    }

    private function bpjs_sep_add_offline($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $uid = parent::gen_uuid();
        $BPJSAdd = self::$query->insert('bpjs_sep', array(
            'uid' => $uid,
            'antrian' => $parameter['antrian'],
            'pasien' => $parameter['pasien'],
            'pelayanan_jenis' => '0',
            'kelas_rawat' => '0',
            'asal_rujukan_nomor' => '',
            'diagnosa_kode' => '',
            'poli_tujuan' => '',
            'pegawai' => $UserData['data']->uid,
            'sep_no' => $parameter['sep'],
            'sep_tanggal' => parent::format_date(),
            'sep_selesai' => parent::format_date(),
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->execute();

        return $BPJSAdd;
    }

    private function get_pasien_back_end($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'pasien.deleted_at' => 'IS NULL',
                'AND',
                '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
            );

            $paramValue = array();
        } else {
            $paramData = array(
                'pasien.deleted_at' => 'IS NULL'
            );

            $paramValue = array();
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('pasien', array(
                'uid',
                'no_rm',
                'nama',
                'panggilan AS id_panggilan',
                'tanggal_lahir',
                'jenkel AS id_jenkel',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select(
                'pasien',
                array(
                    'uid',
                    'no_rm',
                    'nama',
                    'panggilan AS id_panggilan',
                    'tanggal_lahir',
                    'jenkel AS id_jenkel',
                    'created_at',
                    'updated_at'
                )
            )
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        $term = new Terminologi(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {

            if (strlen($value['no_rm']) < 6) {
                $updateRM = self::$query->update('pasien', array(
                    'no_rm' => str_pad($value['no_rm'], 6, "0", STR_PAD_LEFT)
                ))
                    ->where(array(
                        'pasien.uid' => '= ?'
                    ), array(
                        $value['uid']
                    ))
                    ->execute();
            }

            if (stripos(strtolower($value['no_rm']), '-') !== false) {
                $updateRM = self::$query->update('pasien', array(
                    'no_rm' => str_replace('-', '', $value['no_rm'])
                ))
                    ->where(array(
                        'pasien.uid' => '= ?'
                    ), array(
                        $value['uid']
                    ))
                    ->execute();
            }

            $data['response_data'][$key]['no_rm'] = str_replace('-', '', str_pad($value['no_rm'], 6, "0", STR_PAD_LEFT));

            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
            $data['response_data'][$key]['tanggal_lahir'] = date('d F Y', strtotime($data['response_data'][$key]['tanggal_lahir']));


            $value = $data['response_data'][$key]['id_panggilan'];
            $param = ['', 'terminologi-items-detail', $value];
            $get_panggilan = $term->__GET__($param);
            $data['response_data'][$key]['panggilan'] = $get_panggilan['response_data'][0]['nama'];


            $value = $data['response_data'][$key]['id_jenkel'];
            $param = ['', 'terminologi-items-detail', $value];
            $get_jenkel = $term->__GET__($param);
            $data['response_data'][$key]['jenkel'] = $get_jenkel['response_data'][0]['nama'];

            $tgl_daftar = date("Y-m-d", strtotime($data['response_data'][$key]['created_at']));
            $data['response_data'][$key]['tgl_daftar'] = date('d F Y', strtotime($tgl_daftar));
        }

        $itemTotal = self::$query->select('pasien', array(
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

    private function asesmen_resep_lupa($parameter)
    {
        $data = self::$query->select('asesmen', array(
            'uid', 'pasien', 'poli', 'kunjungan', 'antrian', 'created_at'
        ))
            ->join('pasien', array(
                'nama', 'no_rm'
            ))
            ->join('antrian', array(
                'penjamin'
            ))
            ->on(array(
                array('asesmen.pasien', '=', 'pasien.uid'),
                array('asesmen.antrian', '=', 'antrian.uid')
            ))
            ->where(array(
                // 'asesmen.created_at' => '>= now()::date + interval \'1h\'',
                // 'AND',
                '(pasien.nama' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\'',
                'OR',
                'pasien.no_rm' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\')'
            ), array())
            /*->order(array(
                'created_at' => 'DESC'
            ))
            ->limit(1)*/
            ->execute();

        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['created_at'] = date('d F Y', strtotime($value['created_at']));
        }
        return $data;
    }

    private function get_pasien_select2($parameter)
    {
        $data = self::$query->select('pasien', array(
            'uid',
            'no_rm',
            'nama',
            'panggilan AS id_panggilan',
            'tanggal_lahir',
            'jenkel AS id_jenkel',
            'alamat',
            'no_telp',
            'email',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'pasien.deleted_at' => 'IS NULL',
                'AND',
                '(pasien.nama' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\'',
                'OR',
                'pasien.no_rm' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\')'
            ))
            ->limit(50)
            ->execute();

        $autonum = 1;
        $Terminologi = new Terminologi(self::$pdo);

        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;

            $data['response_data'][$key]['id'] = $value['uid'];
            $JenkelInfo = $Terminologi->get_terminologi_items_detail('terminologi_item', $value['id_jenkel']);
            $data['response_data'][$key]['jenkel_detail'] = $JenkelInfo['response_data'][0];

            $data['response_data'][$key]['text'] = $value['no_rm'] . '-' . $value['nama'];



            $Detail = self::$query->select('pasien_penjamin', array(
                'penjamin',
                'valid_awal',
                'valid_akhir',
                'rest_meta',
                'terdaftar'
            ))
                ->where(array(
                    'pasien_penjamin.pasien' => '= ?',
                    'AND',
                    'pasien_penjamin.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach ($Detail['response_data'] as $DKey => $DValue) {
                //Detail Penjamin
                $Detail['response_data'][$DKey]['valid_awal'] = date('d F Y', strtotime($DValue['valid_awal']));
                $Detail['response_data'][$DKey]['valid_akhir'] = date('d F Y', strtotime($DValue['valid_akhir']));
                $Detail['response_data'][$DKey]['terdaftar'] = date('d F Y', strtotime($DValue['terdaftar']));
            }
            $data['response_data'][$key]['history_penjamin'] = $Detail['response_data'];



            $autonum++;
        }
        return $data;
    }

    public function cekNoRM($parameter)
    {
        $data = self::$query
            ->select('pasien', array('uid', 'no_rm'))
            ->where(
                array(
                    'pasien.no_rm' => '= ?',
                    'AND',
                    'pasien.deleted_at' => 'IS NULL'
                ),
                array(
                    $parameter
                )
            )
            ->execute();
        return $data;
    }

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

    /*============= GET PASIEN DATA FOR ALL CLASS USE =============*/
    public function get_data_pasien($parameter)
    {        //$parameter = uid pasien
        /*--------- GET NO RM --------------- */
        $pasien = new Pasien(self::$pdo);
        $param = ['', 'pasien-detail', $parameter];
        $get_pasien = $pasien->__GET__($param);

        $term = new Terminologi(self::$pdo);
        $value = $get_pasien['response_data'][0]['jenkel'];
        $param = ['', 'terminologi-items-detail', $value];
        $get_jenkel = $term->__GET__($param);

        $value = $get_pasien['response_data'][0]['panggilan'];
        $param = ['', 'terminologi-items-detail', $value];
        $get_panggilan = $term->__GET__($param);

        $result = array(
            'uid' => $get_pasien['response_data'][0]['uid'],
            'no_rm' => $get_pasien['response_data'][0]['no_rm'],
            'nama' => $get_pasien['response_data'][0]['nama'],
            'tanggal_lahir' => date('d F Y', strtotime($get_pasien['response_data'][0]['tanggal_lahir'])),
            'jenkel' => $get_jenkel['response_data'][0]['nama'],
            'id_jenkel' => $get_pasien['response_data'][0]['jenkel'],
            'panggilan' => $get_panggilan['response_data'][0]['nama']
        );

        return $result;
    }
    /*================================================================*/
}
