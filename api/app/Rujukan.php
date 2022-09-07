<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Penjamin as Penjamin;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Rujukan extends Utility
{
    static $pdo;
    static $query;

    protected static function getConn ()
    {
        return self::$pdo;
    }

    public function __construct ($connection)
    {
        self::$pdo = $connection;
        self::$query = new Query(self::$pdo);
    }

    public function __GET__ ($parameter = array())
    {
        switch ($parameter[1])
        {
            case 'detail':
                return self::get_detail($parameter[2]);
                break;
            default:
                return array();
        }
    }

    public function __DELETE__($parameter = array())
    {
        return self::delete($parameter);
    }

    public function __POST__ ($parameter = array())
    {
        switch ($parameter['request'])
        {
            case 'get_all':
                return self::get_all($parameter);
                break;
            case 'tambah_rujukan':
                return self::add_rujukan($parameter);
                break;
            case 'cari_rujukan':
                return self::cari_rujukan($parameter);
                break;
            default:
                return array();
        }
    }

    private function cari_rujukan($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $BPJS = new BPJS(self::$pdo);

        $SyncResp = array();

        if($parameter['sync_data'] === 'Y') {
            if(isset($parameter['cari']) && !empty($parameter['cari'])) {
                $Rujukan = $BPJS->getUrl2('/' . __BPJS_SERVICE_NAME__ . '/Rujukan/RS/List/Peserta/' . $parameter['cari']);
                if(intval($Rujukan['metaData']['code']) === 200) {
                    $data = $Rujukan['data']['rujukan'];
                    foreach ($data as $key => $value) {
                        $check = self::$query->select('bpjs_rujukan', array(
                            'uid'
                        ))
                            ->where(array(
                                'bpjs_rujukan.deleted_at' => 'IS NULL',
                                'AND',
                                'bpjs_rujukan.no_kunjungan' => '= ?'
                            ), array(
                                $value['noKunjungan']
                            ))
                            ->execute();
                        if(count($check['response_data']) > 0) {
                            $proceed = self::$query->update('bpjs_rujukan', array(
                                'asal_rujukan_kode' => (empty($value['provPerujuk']['kode']) ? '' : $value['provPerujuk']['kode']),
                                'asal_rujukan_nama' => (empty($value['provPerujuk']['nama']) ? '' : $value['provPerujuk']['nama']),
                                'diagnosa_kode' => (empty($value['diagnosa']['kode']) ? '' : $value['diagnosa']['kode']),
                                'diagnosa_nama' => (empty($value['diagnosa']['nama']) ? '' : $value['diagnosa']['nama']),
                                'poli_tujuan_kode' => (empty($value['poliRujukan']['kode']) ? '' : $value['poliRujukan']['kode']),
                                'poli_tujuan_nama' => (empty($value['poliRujukan']['nama']) ? '' : $value['poliRujukan']['nama']),
                                'tgl_rujukan' => (empty($value['tglKunjungan']) ? date('Y-m-d') : date('Y-m-d', strtotime($value['tglKunjungan']))),
                                /*'tujuan_rujukan_kode' => '',
                                'tujuan_rujukan_nama' => '',
                                'catatan' => '',*/
                                'keluhan' => (empty($value['keluhan']) ? '' : $value['keluhan']),
                                'pelayanan_kode' => (empty($value['pelayanan']['kode']) ? '' : $value['pelayanan']['kode']),
                                'pelayanan_nama' => (empty($value['pelayanan']['nama']) ? '' : $value['pelayanan']['nama']),
                                'peserta_cob_no_asuransi' => (empty($value['peserta']['cob']['noAsuransi']) ? '' : $value['peserta']['cob']['noAsuransi']),
                                'peserta_cob_nama_asuransi' => (empty($value['peserta']['cob']['nmAsuransi']) ? '' : $value['peserta']['cob']['nmAsuransi']),
                                'peserta_cob_tanggal_tat' => (empty($value['peserta']['cob']['tglTAT']) ? '' : $value['peserta']['cob']['tglTAT']),
                                'peserta_cob_tanggal_tmt' => (empty($value['peserta']['cob']['tglTMT']) ? '' : $value['peserta']['cob']['tglTMT']),
                                'peserta_hak_kelas_keterangan' => (empty($value['peserta']['hakKelas']['keterangan']) ? '' : $value['peserta']['hakKelas']['keterangan']),
                                'peserta_hak_kelas_kode' => (empty($value['peserta']['hakKelas']['kode']) ? '' : $value['peserta']['hakKelas']['kode']),
                                'peserta_informasi_dinsos' => (empty($value['peserta']['informasi']['dinsos']) ? '' : $value['peserta']['informasi']['dinsos']),
                                'peserta_informasi_no_sktm' => (empty($value['peserta']['informasi']['noSKTM']) ? '' : $value['peserta']['informasi']['noSKTM']),
                                'peserta_informasi_prolanis_prb' => (empty($value['peserta']['informasi']['prolanisPRB']) ? '' : $value['peserta']['informasi']['prolanisPRB']),
                                'peserta_jenis_peserta_keterangan' => (empty($value['peserta']['jenisPeserta']['keterangan']) ? '' : $value['peserta']['jenisPeserta']['keterangan']),
                                'peserta_jenis_peserta_kode' => (empty($value['peserta']['jenisPeserta']['kode']) ? '' : $value['peserta']['jenisPeserta']['kode']),
                                'peserta_mr_no' => (empty($value['peserta']['mr']['noMR']) ? '' : $value['peserta']['mr']['noMR']),
                                'peserta_mr_no_telp' => (empty($value['peserta']['mr']['noTelepon']) ? '' : $value['peserta']['mr']['noTelepon']),
                                'peserta_nama' => (empty($value['peserta']['nama']) ? '' : $value['peserta']['nama']),
                                'peserta_nik' => (empty($value['peserta']['nik']) ? '' : $value['peserta']['nik']),
                                'peserta_no_kartu' => (empty($value['peserta']['noKartu']) ? '' : $value['peserta']['noKartu']),
                                'peserta_mr_pisa' => (empty($value['peserta']['pisa']) ? '' : $value['peserta']['pisa']),
                                'peserta_mr_prov_umum_provider_kode' => (empty($value['peserta']['provUmum']['kdProvider']) ? '' : $value['peserta']['provUmum']['kdProvider']),
                                'peserta_mr_prov_umum_provider_nama' => (empty($value['peserta']['provUmum']['nmProvider']) ? '' : $value['peserta']['provUmum']['nmProvider']),
                                'peserta_sex' => (empty($value['peserta']['sex']) ? '' : $value['peserta']['sex']),
                                'peserta_status_peserta_keterangan' => (empty($value['peserta']['statusPeserta']['keterangan']) ? '' : $value['peserta']['statusPeserta']['keterangan']),
                                'peserta_status_peserta_kode' => (empty($value['peserta']['statusPeserta']['kode']) ? '' : $value['peserta']['statusPeserta']['kode']),
                                'peserta_tanggal_cetak_kartu' => (empty($value['peserta']['tglCetakKartu']) ? '' : $value['peserta']['tglCetakKartu']),
                                'peserta_tanggal_lahir' => (empty($value['peserta']['tglLahir']) ? '' : $value['peserta']['tglLahir']),
                                'peserta_tanggal_tat' => (empty($value['peserta']['tglTAT']) ? '' : $value['peserta']['tglTAT']),
                                'peserta_tanggal_tmt' => (empty($value['peserta']['tglTMT']) ? '' : $value['peserta']['tglTMT']),
                                'peserta_umur_pelayanan' => (empty($value['peserta']['umur']['umurSaatPelayanan']) ? '' : $value['peserta']['umur']['umurSaatPelayanan']),
                                'peserta_umur_sekarang' => (empty($value['peserta']['umur']['umurSekarang']) ? '' : $value['peserta']['umur']['umurSekarang']),
                                'updated_at' => parent::format_date()

                            ))
                                ->where(array(
                                    'bpjs_rujukan.deleted_at' => 'IS NULL',
                                    'AND',
                                    'bpjs_rujukan.uid' => '= ?'
                                ), array(
                                    $check['response_data'][0]['uid']
                                ))
                                ->execute();
                        } else {
                            $SEP = self::$query->select('bpjs_sep' , array(
                                'uid'
                            ))
                                ->where(array(
                                    'bpjs_sep.deleted_at' => 'IS NULL',
                                    'AND',
                                    'bpjs_sep.created_at::date' => '= date \'' . $value['tglKunjungan'] . '\''
                                ))
                                ->execute();

                            $RequestRujukan = self::$query->select('rujukan', array(
                                'uid'
                            ))
                                ->where(array(
                                    'rujukan.deleted_at' => 'IS NULL',
                                    'AND',
                                    'rujukan.created_at::date' => '= date(\'' . $value['tglKunjungan'] . '\')'
                                ))
                                ->execute();

                            $proceed = self::$query->insert('bpjs_rujukan', array(
                                'uid' => parent::gen_uuid(),
                                'pasien' => $parameter['pasien'],
                                'sep' => $SEP['response_data'][0]['uid'],
                                //'request_rujukan' => $RequestRujukan['response_data'][0]['uid'],
                                'pegawai' => $UserData['data']->uid,
                                'no_kunjungan' => $value['noKunjungan'],
                                'asal_rujukan_kode' => (empty($value['provPerujuk']['kode']) ? '' : $value['provPerujuk']['kode']),
                                'asal_rujukan_nama' => (empty($value['provPerujuk']['nama']) ? '' : $value['provPerujuk']['nama']),
                                'diagnosa_kode' => (empty($value['diagnosa']['kode']) ? '' : $value['diagnosa']['kode']),
                                'diagnosa_nama' => (empty($value['diagnosa']['nama']) ? '' : $value['diagnosa']['nama']),
                                'poli_tujuan_kode' => (empty($value['poliRujukan']['kode']) ? '' : $value['poliRujukan']['kode']),
                                'poli_tujuan_nama' => (empty($value['poliRujukan']['nama']) ? '' : $value['poliRujukan']['nama']),
                                'tgl_rujukan' => (empty($value['tglKunjungan']) ? date('Y-m-d') : date('Y-m-d', strtotime($value['tglKunjungan']))),
                                /*'tujuan_rujukan_kode' => '',
                                'tujuan_rujukan_nama' => '',
                                'catatan' => '',*/
                                'keluhan' => (empty($value['keluhan']) ? '' : $value['keluhan']),
                                'pelayanan_kode' => (empty($value['pelayanan']['kode']) ? '' : $value['pelayanan']['kode']),
                                'pelayanan_nama' => (empty($value['pelayanan']['nama']) ? '' : $value['pelayanan']['nama']),
                                'peserta_cob_no_asuransi' => (empty($value['peserta']['cob']['noAsuransi']) ? '' : $value['peserta']['cob']['noAsuransi']),
                                'peserta_cob_nama_asuransi' => (empty($value['peserta']['cob']['nmAsuransi']) ? '' : $value['peserta']['cob']['nmAsuransi']),
                                'peserta_cob_tanggal_tat' => (empty($value['peserta']['cob']['tglTAT']) ? '' : $value['peserta']['cob']['tglTAT']),
                                'peserta_cob_tanggal_tmt' => (empty($value['peserta']['cob']['tglTMT']) ? '' : $value['peserta']['cob']['tglTMT']),
                                'peserta_hak_kelas_keterangan' => (empty($value['peserta']['hakKelas']['keterangan']) ? '' : $value['peserta']['hakKelas']['keterangan']),
                                'peserta_hak_kelas_kode' => (empty($value['peserta']['hakKelas']['kode']) ? '' : $value['peserta']['hakKelas']['kode']),
                                'peserta_informasi_dinsos' => (empty($value['peserta']['informasi']['dinsos']) ? '' : $value['peserta']['informasi']['dinsos']),
                                'peserta_informasi_no_sktm' => (empty($value['peserta']['informasi']['noSKTM']) ? '' : $value['peserta']['informasi']['noSKTM']),
                                'peserta_informasi_prolanis_prb' => (empty($value['peserta']['informasi']['prolanisPRB']) ? '' : $value['peserta']['informasi']['prolanisPRB']),
                                'peserta_jenis_peserta_keterangan' => (empty($value['peserta']['jenisPeserta']['keterangan']) ? '' : $value['peserta']['jenisPeserta']['keterangan']),
                                'peserta_jenis_peserta_kode' => (empty($value['peserta']['jenisPeserta']['kode']) ? '' : $value['peserta']['jenisPeserta']['kode']),
                                'peserta_mr_no' => (empty($value['peserta']['mr']['noMR']) ? '' : $value['peserta']['mr']['noMR']),
                                'peserta_mr_no_telp' => (empty($value['peserta']['mr']['noTelepon']) ? '' : $value['peserta']['mr']['noTelepon']),
                                'peserta_nama' => (empty($value['peserta']['nama']) ? '' : $value['peserta']['nama']),
                                'peserta_nik' => (empty($value['peserta']['nik']) ? '' : $value['peserta']['nik']),
                                'peserta_no_kartu' => (empty($value['peserta']['noKartu']) ? '' : $value['peserta']['noKartu']),
                                'peserta_mr_pisa' => (empty($value['peserta']['pisa']) ? '' : $value['peserta']['pisa']),
                                'peserta_mr_prov_umum_provider_kode' => (empty($value['peserta']['provUmum']['kdProvider']) ? '' : $value['peserta']['provUmum']['kdProvider']),
                                'peserta_mr_prov_umum_provider_nama' => (empty($value['peserta']['provUmum']['nmProvider']) ? '' : $value['peserta']['provUmum']['nmProvider']),
                                'peserta_sex' => (empty($value['peserta']['sex']) ? '' : $value['peserta']['sex']),
                                'peserta_status_peserta_keterangan' => (empty($value['peserta']['statusPeserta']['keterangan']) ? '' : $value['peserta']['statusPeserta']['keterangan']),
                                'peserta_status_peserta_kode' => (empty($value['peserta']['statusPeserta']['kode']) ? '' : $value['peserta']['statusPeserta']['kode']),
                                'peserta_tanggal_cetak_kartu' => (empty($value['peserta']['tglCetakKartu']) ? '' : $value['peserta']['tglCetakKartu']),
                                'peserta_tanggal_lahir' => (empty($value['peserta']['tglLahir']) ? '' : $value['peserta']['tglLahir']),
                                'peserta_tanggal_tat' => (empty($value['peserta']['tglTAT']) ? '' : $value['peserta']['tglTAT']),
                                'peserta_tanggal_tmt' => (empty($value['peserta']['tglTMT']) ? '' : $value['peserta']['tglTMT']),
                                'peserta_umur_pelayanan' => (empty($value['peserta']['umur']['umurSaatPelayanan']) ? '' : $value['peserta']['umur']['umurSaatPelayanan']),
                                'peserta_umur_sekarang' => (empty($value['peserta']['umur']['umurSekarang']) ? '' : $value['peserta']['umur']['umurSekarang']),
                                'created_at' => parent::format_date(),
                                'updated_at' => parent::format_date()
                            ))
                                ->execute();
                        }

                        array_push($SyncResp, $proceed);
                    }
                }
            }
        }


        if(isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'bpjs_rujukan.deleted_at' => 'IS NULL',
                'AND',
                'bpjs_rujukan.tujuan_rujukan_kode' => 'IS NULL',
                'AND',
                'pasien.deleted_at' => 'IS NULL'
            );

            $paramValue = array();
        } else {
            $paramData = array(
                'bpjs_rujukan.deleted_at' => 'IS NULL',
                'AND',
                'bpjs_rujukan.tujuan_rujukan_kode' => 'IS NULL',
                'AND',
                'pasien.deleted_at' => 'IS NULL'
            );

            $paramValue = array();
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('bpjs_rujukan', array(
                'uid',
                'no_kunjungan',
                'no_rujukan',
                'asal_rujukan_kode',
                'asal_rujukan_nama',
                'diagnosa_kode',
                'diagnosa_nama',
                'poli_tujuan_kode',
                'poli_tujuan_nama',
                'tgl_rujukan',
                /*'tujuan_rujukan_kode' => '',
                'tujuan_rujukan_nama' => '',
                'catatan' => '',*/
                'keluhan',
                'pelayanan_kode',
                'pelayanan_nama',
                'peserta_cob_no_asuransi',
                'peserta_cob_nama_asuransi',
                'peserta_cob_tanggal_tat',
                'peserta_cob_tanggal_tmt',
                'peserta_hak_kelas_keterangan',
                'peserta_hak_kelas_kode',
                'peserta_informasi_dinsos',
                'peserta_informasi_no_sktm',
                'peserta_informasi_prolanis_prb',
                'peserta_jenis_peserta_keterangan',
                'peserta_jenis_peserta_kode',
                'peserta_mr_no',
                'peserta_mr_no_telp',
                'peserta_nama',
                'peserta_nik',
                'peserta_no_kartu',
                'peserta_mr_pisa',
                'peserta_mr_prov_umum_provider_kode',
                'peserta_mr_prov_umum_provider_nama',
                'peserta_sex',
                'peserta_status_peserta_keterangan',
                'peserta_status_peserta_kode',
                'peserta_tanggal_cetak_kartu',
                'peserta_tanggal_lahir',
                'peserta_tanggal_tat',
                'peserta_tanggal_tmt',
                'peserta_umur_pelayanan',
                'peserta_umur_sekarang',
                'pasien',
                'created_at',
                'updated_at'
            ))
                ->join('pasien', array(
                    'no_rm'
                ))
                ->on(array(
                    array('bpjs_rujukan.pasien', '=', 'pasien.uid')
                ))
                ->where($paramData, $paramValue)
                ->order(array(
                    'created_at' => 'DESC'
                ))
                ->execute();
        } else {
            $data = self::$query->select('bpjs_rujukan', array(
                'uid',
                'no_kunjungan',
                'no_rujukan',
                'asal_rujukan_kode',
                'asal_rujukan_nama',
                'diagnosa_kode',
                'diagnosa_nama',
                'poli_tujuan_kode',
                'poli_tujuan_nama',
                'tgl_rujukan',
                /*'tujuan_rujukan_kode' => '',
                'tujuan_rujukan_nama' => '',
                'catatan' => '',*/
                'keluhan',
                'pelayanan_kode',
                'pelayanan_nama',
                'peserta_cob_no_asuransi',
                'peserta_cob_nama_asuransi',
                'peserta_cob_tanggal_tat',
                'peserta_cob_tanggal_tmt',
                'peserta_hak_kelas_keterangan',
                'peserta_hak_kelas_kode',
                'peserta_informasi_dinsos',
                'peserta_informasi_no_sktm',
                'peserta_informasi_prolanis_prb',
                'peserta_jenis_peserta_keterangan',
                'peserta_jenis_peserta_kode',
                'peserta_mr_no',
                'peserta_mr_no_telp',
                'peserta_nama',
                'peserta_nik',
                'peserta_no_kartu',
                'peserta_mr_pisa',
                'peserta_mr_prov_umum_provider_kode',
                'peserta_mr_prov_umum_provider_nama',
                'peserta_sex',
                'peserta_status_peserta_keterangan',
                'peserta_status_peserta_kode',
                'peserta_tanggal_cetak_kartu',
                'peserta_tanggal_lahir',
                'peserta_tanggal_tat',
                'peserta_tanggal_tmt',
                'peserta_umur_pelayanan',
                'peserta_umur_sekarang',
                'pasien',
                'created_at',
                'updated_at'
            ))
                ->join('pasien', array(
                    'no_rm'
                ))
                ->on(array(
                    array('bpjs_rujukan.pasien', '=', 'pasien.uid')
                ))
                ->where($paramData, $paramValue)
                ->order(array(
                    'created_at' => 'DESC'
                ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }


        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        $Pasien = new Pasien(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $PasienDetail = $Pasien->get_pasien_detail('pasien', $value['pasien']);
            $data['response_data'][$key]['pasien'] = $PasienDetail['response_data'][0];

            $autonum++;
        }

        $itemTotal = self::$query->select('bpjs_rujukan', array(
            'uid'
        ))
            ->join('pasien', array(
                'no_rm'
            ))
            ->on(array(
                array('bpjs_rujukan.pasien', '=', 'pasien.uid')
            ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);
        $data['sync'] = $parameter['sync_data'];
        $data['bpjs_result'] = $Rujukan;
        $data['sync_result'] = $SyncResp;

        return $data;
    }

    private function get_all($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'rujukan.deleted_at' => 'IS NULL',
                'AND',
                'pasien.deleted_at' => 'IS NULL'
            );

            $paramValue = array();
        } else {
            $paramData = array(
                'rujukan.deleted_at' => 'IS NULL',
                'AND',
                'pasien.deleted_at' => 'IS NULL'
                /*'rujukan.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''*/
            );

            $paramValue = array();
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('rujukan', array(
                'uid',
                'poli',
                'dokter',
                'pasien',
                'penjamin',
                'keterangan',
                'status',
                'pegawai_rekam_medis',
                'nomor_rujuk',
                'antrian',
                'jenis_layanan',
                'tipe_rujukan',
                'created_at',
                'updated_at'
            ))
                ->join('pasien', array(
                    'nama'
                ))
                ->on(array(
                    array('rujukan.pasien', '=', 'pasien.uid')
                ))
                ->where($paramData, $paramValue)
                ->order(array(
                    'status' => 'DESC'
                ))
                ->execute();
        } else {
            $data = self::$query->select('rujukan', array(
                'uid',
                'poli',
                'dokter',
                'pasien',
                'penjamin',
                'keterangan',
                'status',
                'pegawai_rekam_medis',
                'nomor_rujuk',
                'antrian',
                'jenis_layanan',
                'tipe_rujukan',
                'created_at',
                'updated_at'
            ))
                ->join('pasien', array(
                    'nama'
                ))
                ->on(array(
                    array('rujukan.pasien', '=', 'pasien.uid')
                ))
                ->where($paramData, $paramValue)
                ->order(array(
                    'status' => 'DESC'
                ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        $Poli = new Poli(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);
        $Pasien = new Pasien(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);
        $Asesmen = new Asesmen(self::$pdo);

        foreach ($data['response_data'] as $key => $value) {


            $data['response_data'][$key]['autonum'] = $autonum;

            //Poli
            $PoliDetail = $Poli->get_poli_detail($value['poli']);
            $data['response_data'][$key]['poli'] = $PoliDetail['response_data'][0];

            //Penjamin
            $PenjaminDetail = $Penjamin->get_penjamin_detail($value['penjamin']);
            $data['response_data'][$key]['penjamin'] = $PenjaminDetail['response_data'][0];

            //Pasien
            $PasienDetail = $Pasien->get_pasien_detail('pasien', $value['pasien']);
            $data['response_data'][$key]['pasien'] = $PasienDetail['response_data'][0];

            //Dokter
            $DokterDetail = $Pegawai->get_detail($value['dokter']);
            $data['response_data'][$key]['dokter'] = $DokterDetail['response_data'][0];


            if(isset($value['pegawai_rekam_medis']))
            {
                //Pegawai
                $PegawaiDetail = $Pegawai->get_detail($value['pegawai_rekam_medis']);
                $data['response_data'][$key]['pegawai_rekam_medis'] = $PegawaiDetail['response_data'][0];
            }
            
            $AsesmenDetail = $Asesmen->get_asesmen_medis($value['antrian']);
            $data['response_data'][$key]['asesmen'] = $AsesmenDetail['response_data'][0];

            $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
            $data['response_data'][$key]['created_at_compare'] = date('Y-m-d', strtotime($value['created_at']));

            //Get Rujukan BPJS
            $BPJS = self::$query->select('bpjs_rujukan', array(
                'uid',
                'no_kunjungan',
                'no_rujukan'
            ))
                ->where(array(
                    'bpjs_rujukan.request_rujukan' => '= ?',
                    'AND',
                    'bpjs_rujukan.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();
            $data['response_data'][$key]['bpjs_rujukan'] = $BPJS;

            $autonum++;
        }

        $itemTotal = self::$query->select('rujukan', array(
            'uid'
        ))
            ->join('pasien', array(
                'nama'
            ))
            ->on(array(
                array('rujukan.pasien', '=', 'pasien.uid')
            ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($data['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function get_detail($parameter)
    {
        $data = self::$query->select('bpjs_rujukan', array(
            'uid',
            'pasien',
            'sep',
            'request_rujukan',
            'pegawai',
            'no_kunjungan',
            'no_rujukan',
            'asal_rujukan_kode',
            'asal_rujukan_nama',
            'diagnosa_kode',
            'diagnosa_nama',
            'poli_tujuan_kode',
            'poli_tujuan_nama',
            'tgl_rujukan',
            'catatan',
            'keluhan',
            'pelayanan_kode',
            'pelayanan_nama',
            'peserta_cob_no_asuransi',
            'peserta_cob_nama_asuransi',
            'peserta_cob_tanggal_tat',
            'peserta_cob_tanggal_tmt',
            'peserta_hak_kelas_keterangan',
            'peserta_hak_kelas_kode',
            'peserta_informasi_dinsos',
            'peserta_informasi_no_sktm',
            'peserta_informasi_prolanis_prb',
            'peserta_jenis_peserta_keterangan',
            'peserta_jenis_peserta_kode',
            'peserta_mr_no',
            'peserta_mr_no_telp',
            'peserta_nama',
            'peserta_nik',
            'peserta_no_kartu',
            'peserta_mr_pisa',
            'peserta_mr_prov_umum_provider_kode',
            'peserta_mr_prov_umum_provider_nama',
            'peserta_sex',
            'peserta_status_peserta_keterangan',
            'peserta_status_peserta_kode',
            'peserta_tanggal_cetak_kartu',
            'peserta_tanggal_lahir',
            'peserta_tanggal_tat',
            'peserta_tanggal_tmt',
            'peserta_umur_pelayanan',
            'peserta_umur_sekarang',
            'tipe_rujukan',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'bpjs_rujukan.deleted_at' => 'IS NULL',
                'AND',
                'bpjs_rujukan.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        return $data;
    }

    private function add_rujukan($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $uid = parent::gen_uuid();
        $worker = self::$query->insert('rujukan', array(
            'uid' => $uid,
            'antrian' => $parameter['antrian'],
            'poli' => $parameter['poli'],
            'dokter' => $UserData['data']->uid,
            'pasien' => $parameter['pasien'],
            'penjamin' => $parameter['penjamin'],
            'keterangan' => $parameter['keterangan'],
            'jenis_layanan' => $parameter['jenis'],
            'tipe_rujukan' => $parameter['tipe'],
            'status' => 'N',
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
                    'rujukan',
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



    private function delete($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if($parameter[6] === 'bpjs_rujukan') {
            if(intval($parameter[8]) > 0) {
                $worker = self::$query
                    ->delete($parameter[6])
                    ->where(array(
                        $parameter[6] . '.no_kunjungan' => '= ?'
                    ), array(
                        $parameter[7]
                    ))
                    ->execute();
                if ($worker['response_result'] > 0) {
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

                return array(
                    'bpjs' => array(),
                    'worker' => $worker
                );
            } else {
                $BPJS = new BPJS(self::$pdo);
                $parameterBuilder = array('request' => array(
                    't_rujukan' => array(
                        'noRujukan' => $parameter[7],
                        'user' => $UserData['data']->nama
                    )
                ));

                $DeleteRujukan = $BPJS->deleteUrl('/' . __BPJS_SERVICE_NAME__ . '/Rujukan/delete', $parameterBuilder);
                if(intval($DeleteRujukan['content']['metaData']['code']) === 200) {
                    $worker = self::$query
                        ->delete($parameter[6])
                        ->where(array(
                            $parameter[6] . '.no_rujukan' => '= ?'
                        ), array(
                            $parameter[7]
                        ))
                        ->execute();
                    if ($worker['response_result'] > 0) {
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
                }

                return array(
                    'bpjs' => $DeleteRujukan,
                    'worker' => $worker,
                    'param' => $parameterBuilder
                );
            }
        } else {
            $worker = self::$query
                ->delete($parameter[6])
                ->where(array(
                    $parameter[6] . '.uid' => '= ?'
                ), array(
                    $parameter[7]
                ))
                ->execute();
            if ($worker['response_result'] > 0) {
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
}

?>