<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;
use PondokCoder\Terminologi as Terminologi;
use PondokCoder\Invoice as Invoice;
use PondokCoder\Pasien as Pasien;
use PondokCoder\Pegawai as Pegawai;
use PondokCoder\Penjamin as Penjamin;
use PondokCoder\Poli as Poli;

class Antrian extends Utility
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

    public function __POST__($parameter = array())
    {
        switch ($parameter['request']) {
            case 'get_list_antrian_backend':
                return self::get_list_antrian_backend($parameter);
                break;
            case 'tambah-kunjungan':
                return self::tambah_kunjungan('kunjungan', $parameter);
                break;
            case 'ubah_dokter_antrian':
                return self::ubah_dokter_antrian($parameter);
                break;
            case 'pulangkan_pasien':
                return self::pulangkan_pasien($parameter);
                break;
            case 'igd':
                return self::antrian_igd($parameter);
                break;
            default:
                # code...
                break;
        }
    }

    private function antrian_igd($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'igd.deleted_at' => 'IS NULL',
                'AND',
                'igd.waktu_keluar' => 'IS NULL',
                'AND',
                '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
            );

            $paramValue = array();
        } else {
            $paramData = array(
                'igd.deleted_at' => 'IS NULL',
                'AND',
                'igd.waktu_keluar' => 'IS NULL'
            );

            $paramValue = array();
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('igd', array(
                'uid',
                'pasien',
                'dokter',
                'penjamin',
                'kunjungan',
                'waktu_masuk',
                'waktu_keluar',
                'kamar',
                'bed',
                'keterangan',
                'jenis_pulang',
                'alasan_pulang',
                'pegawai_daftar',
                'created_at',
                'updated_at'
            ))
                ->join('pasien', array(
                    'nama as nama_pasien'
                ))
                ->on(array(
                    array('igd.pasien', '=' , 'pasien.uid')
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('igd', array(
                'uid',
                'pasien',
                'dokter',
                'penjamin',
                'kunjungan',
                'waktu_masuk',
                'waktu_keluar',
                'kamar',
                'bed',
                'keterangan',
                'jenis_pulang',
                'alasan_pulang',
                'pegawai_daftar',
                'created_at',
                'updated_at'
            ))
                ->join('pasien', array(
                    'nama as nama_pasien',
                    'no_rm'
                ))
                ->on(array(
                    array('igd.pasien', '=' , 'pasien.uid')
                ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $Pegawai = new Pegawai(self::$pdo);
        $Penjamin = new Penjamin(self::$pdo);

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;

            $data['response_data'][$key]['waktu_masuk'] = date('d F Y . H:i', strtotime($value['waktu_masuk']));
            $data['response_data'][$key]['dokter'] = $Pegawai::get_detail_pegawai($value['dokter'])['response_data'][0]['nama'];
            $data['response_data'][$key]['penjamin'] = $Penjamin->get_penjamin_detail($value['penjamin'])['response_data'][0]['nama'];
            $data['response_data'][$key]['user_resepsionis'] = $Pegawai::get_detail_pegawai($value['pegawai_daftar'])['response_data'][0]['nama'];
        }

        $itemTotal = self::$query->select('igd', array(
            'uid'
        ))
            ->join('pasien', array(
                'nama as nama_pasien',
                'no_rm'
            ))
            ->on(array(
                array('igd.pasien', '=' , 'pasien.uid')
            ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function pulangkan_pasien($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        //Keluar Antrian
        $update_antrian = self::$query->update('antrian', array(
            'waktu_keluar' => parent::format_date()
        ))
            ->where(array(
                'antrian.uid' => '= ?',
                'AND',
                'antrian.deleted_at' => 'IS NULL'
            ), array(
                $parameter['uid']
            ))
            ->execute();

        $antrian = self::$query->select('antrian', array(
            'kunjungan'
        ))
            ->where(array(
                'antrian.uid' => '= ?',
                'AND',
                'antrian.deleted_at' => 'IS NULL'
            ), array(
                $parameter['uid']
            ))
            ->execute();

        $worker = self::$query->update('kunjungan', array(
            'waktu_keluar' => parent::format_date()
        ))
            ->where(array(
                'kunjungan.uid' => '= ?',
                'AND',
                'kunjungan.deleted_at' => 'IS NULL'
            ), array(
                $antrian['response_data'][0]['kunjungan']
            ))
            ->execute();

        return $worker;
    }

    private function tambah_igd($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        $worker = '';
    }

    private function tambah_kunjungan($table, $parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        if(isset($parameter['dataObj']['konsul'])) { //Kunjungan karena konsul


            $PoliTindakan = new Poli(self::$pdo);
            $PoliTindakanInfo = $PoliTindakan::get_poli_detail($parameter['dataObj']['departemen'])['response_data'][0];
            $SInvoice = new Invoice(self::$pdo);

            if ($parameter['dataObj']['penjamin'] == __UIDPENJAMINUMUM__) { // Jika umum

                //Invoice Manager
                $InvoiceCheck = self::$query->select('invoice', array( //Check Invoice Master jika sudah ada
                    'uid'
                ))
                    ->where(array(
                        'invoice.deleted_at' => 'IS NULL',
                        'AND',
                        'invoice.kunjungan' => '= ?'
                    ), array(
                        $parameter['dataObj']['kunjungan']
                    ))
                    ->execute();


                if (count($InvoiceCheck['response_data']) > 0) { //Sudah Ada Invoice Master

                    $InvoiceUID = $InvoiceCheck['response_data'][0]['uid'];

                } else { //Belum ada Invoice Master

                    $Invoice = $SInvoice::create_invoice(array(
                        'kunjungan' => $parameter['dataObj']['kunjungan'],
                        'pasien' => $parameter['dataObj']['pasien'],
                        'keterangan' => 'Kunjungan Penjamin BPJS'
                    ));

                    $InvoiceUID = $Invoice['response_unique'];

                }

                //Simpan tagihan penjamin

                $HargaTindakan = $SInvoice::get_harga_tindakan(array(
                    'poli' => $parameter['dataObj']['departemen'],
                    'kelas' => __UID_KELAS_GENERAL_RJ__,
                    'tindakan' => $PoliTindakanInfo['tindakan_konsultasi'],
                    'penjamin' => $parameter['dataObj']['penjamin']
                ));

                $Invoice = $SInvoice::append_invoice(array(
                    'invoice' => $InvoiceUID,
                    'item' => $PoliTindakanInfo['tindakan_konsultasi'],
                    'item_origin' => 'master_tindakan',
                    'qty' => 1,
                    'harga' => floatval($HargaTindakan['response_data'][0]['harga']),
                    'subtotal' => floatval($HargaTindakan['response_data'][0]['harga']),
                    'status_bayar' => 'N', //Umum Bayar dulu
                    'discount' => 0,
                    'discount_type' => 'N',
                    'pasien' => $parameter['dataObj']['currentPasien'],
                    'penjamin' => $parameter['dataObj']['penjamin'],
                    'billing_group' => 'tindakan',
                    'keterangan' => 'Biaya konsultasi'
                ));
                //$antrian = self::tambah_antrian('antrian', $parameter, $parameter['dataObj']['kunjungan']);
                $updateNomorAntrian = self::$query->update('antrian_nomor', array(
                    'status' => 'K',
                    'prioritas' => $parameter['dataObj']['prioritas'],
                    'poli' => $parameter['dataObj']['departemen'],
                    'dokter' => $parameter['dataObj']['dokter'],
                ))
                    ->where(array(
                        'antrian_nomor.pasien' => '= ?',
                        'AND',
                        'antrian_nomor.penjamin' => '= ?'
                    ), array(
                            $parameter['dataObj']['currentPasien'],
                            $parameter['dataObj']['penjamin']
                        )
                    )
                    ->execute();

                unset($parameter['dataObj']['currentPasien']);
                $antrian['response_notif'] = 'K';
                return $antrian;

            } else { // Jika selain umum

                //Invoice Manager
                $InvoiceCheck = self::$query->select('invoice', array( //Check Invoice Master jika sudah ada
                    'uid'
                ))
                    ->where(array(
                        'invoice.deleted_at' => 'IS NULL',
                        'AND',
                        'invoice.kunjungan' => '= ?'
                    ), array(
                        $parameter['dataObj']['kunjungan']
                    ))
                    ->execute();


                if (count($InvoiceCheck['response_data']) > 0) { //Sudah Ada Invoice Master

                    $InvoiceUID = $InvoiceCheck['response_data'][0]['uid'];

                } else { //Belum ada Invoice Master

                    $Invoice = $SInvoice::create_invoice(array(
                        'kunjungan' => $parameter['dataObj']['kunjungan'],
                        'pasien' => $parameter['dataObj']['pasien'],
                        'keterangan' => 'Kunjungan Penjamin BPJS'
                    ));

                    $InvoiceUID = $Invoice['response_unique'];

                }

                //Simpan tagihan penjamin

                $HargaTindakan = $SInvoice::get_harga_tindakan(array(
                    'poli' => $parameter['dataObj']['departemen'],
                    'kelas' => __UID_KELAS_GENERAL_RJ__,
                    'tindakan' => $PoliTindakanInfo['tindakan_konsultasi'],
                    'penjamin' => $parameter['dataObj']['penjamin']
                ));

                $Invoice = $SInvoice::append_invoice(array(
                    'invoice' => $InvoiceUID,
                    'item' => $PoliTindakanInfo['tindakan_konsultasi'],
                    'item_origin' => 'master_tindakan',
                    'qty' => 1,
                    'harga' => floatval($HargaTindakan['response_data'][0]['harga']),
                    'subtotal' => floatval($HargaTindakan['response_data'][0]['harga']),
                    'status_bayar' => 'Y', //Karena penjamin selain ini otomatis status menjadi terbayar
                    'discount' => 0,
                    'discount_type' => 'N',
                    'pasien' => $parameter['dataObj']['currentPasien'],
                    'penjamin' => $parameter['dataObj']['penjamin'],
                    'billing_group' => 'tindakan',
                    'keterangan' => 'Biaya konsultasi'
                ));

                unset($parameter['dataObj']['currentPasien']);
                unset($parameter['dataObj']['bangsal']);

                /*//Keluar dari poli
                $keluar = self::$query->update('antrian', array(
                    'waktu_keluar' => parent::format_date()
                ))
                    ->where(array(
                        'antrian.uid' => '= ?',
                        'AND',
                        'antrian.deleted_at' => 'IS NULL'
                    ), array(
                        $parameter['dataObj']['antrian']
                    ))
                    ->execute();*/

                $antrian = self::tambah_antrian('antrian', $parameter, $parameter['dataObj']['kunjungan']);
                $antrian['response_notif'] = 'P';
                return $antrian;

            }





        } else { //Kunjungan Pertama


            $uid = parent::gen_uuid();
            //Tentukan tindakan untuk poli bersangkutan
            $PoliTindakan = new Poli(self::$pdo);
            $PoliTindakanInfo = $PoliTindakan::get_poli_detail($parameter['dataObj']['departemen'])['response_data'][0];

            $kunjungan = self::$query->insert($table, array(
                'uid' => $uid,
                'waktu_masuk' => parent::format_date(),
                'pj_pasien' => $parameter['dataObj']['pj_pasien'],
                'info_didapat_dari' => $parameter['dataObj']['info_didapat_dari'],
                'cara_datang' => intval($parameter['dataObj']['cara_datang']),
                'keterangan_cara_datang' => $parameter['dataObj']['keterangan_cara_datang'],
                'pegawai' => $UserData['data']->uid,
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))->execute();



            if ($kunjungan['response_result'] > 0) {
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
                    ), 'value' => array(
                        $uid,
                        $UserData['data']->uid,
                        $table,
                        'I',
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ), 'class' => __CLASS__));




                $SInvoice = new Invoice(self::$pdo);
                $HargaKartu = $SInvoice::get_harga_tindakan(array(
                    'poli' => $parameter['dataObj']['departemen'],
                    'kelas' => __UID_KELAS_GENERAL_RJ__,
                    'tindakan' => __UID_KARTU__,
                    'penjamin' => $parameter['dataObj']['penjamin']
                ));

                //Update antrian kunjungan
                if ($parameter['dataObj']['penjamin'] == __UIDPENJAMINUMUM__) { // Jika umum
                    if (count($HargaKartu['response_data']) > 0 && floatval($HargaKartu['response_data'][0]['harga']) > 0) {
                        $Pasien = new Pasien(self::$pdo);
                        $PasienDetail = $Pasien::get_pasien_detail('pasien', $parameter['dataObj']['currentPasien']);



                        if($parameter['dataObj']['departemen'] === __POLI_IGD__) {

                            //KHUSUS AUTO ANTRIAN
                            $antrianKunjungan = self::$query->insert('antrian_nomor', array(
                                'nomor_urut' => 'IGD',
                                'pegawai' => $UserData['data']->uid,
                                'kunjungan' => $uid,
                                'prioritas' => $parameter['dataObj']['prioritas'],
                                'pasien' => $parameter['dataObj']['currentPasien'],
                                'dokter' => $parameter['dataObj']['dokter'],
                                'penjamin' => $parameter['dataObj']['penjamin'],
                                'poli' => __POLI_IGD__,
                                'status' => 'K',
                                'created_at' => parent::format_date(),
                                'jenis_antrian' => __ANTRIAN_KHUSUS__
                            ))
                                ->execute();



                            //Auto IGD
                            $IGD = parent::gen_uuid();
                            //todo: IGD kerjakan coy
                            $igd_log = self::$query->insert('igd', array(
                                'uid' => $IGD,
                                'pasien' => $parameter['dataObj']['currentPasien'],
                                'dokter' => $parameter['dataObj']['dokter'],
                                'penjamin' => $parameter['dataObj']['penjamin'],
                                'kunjungan' => $uid,
                                'waktu_masuk' => parent::format_date(),
                                'kamar' => __KAMAR_IGD__,
                                'bed' => $parameter['dataObj']['bangsal'],
                                'pegawai_daftar' => $UserData['data']->uid,
                                'keterangan' => '',
                                'created_at' => parent::format_date(),
                                'updated_at' => parent::format_date()
                            ))
                                ->execute();




                            $antrianKunjungan['response_data'][0]['pasien_detail'] = $PasienDetail['response_data'][0];
                            $antrianKunjungan['response_notif'] = 'K';

                            //Auto Tambah ke Antrian Poli
                            $parameter['dataObj']['currentAntrianID'] = 0;
                            $currentPasien = $parameter['dataObj']['currentPasien'];
                            unset($parameter['dataObj']['currentPasien']);
                            unset($parameter['dataObj']['bangsal']);
                            $AntrianProses = self::tambah_antrian('antrian', $parameter, $uid);
                            $antrianKunjungan['response_poli'] = $AntrianProses;

                            /*$no_antrian = self::ambilNomorAntrianPoli($parameter['dataObj']['departemen']);
                            $antrian = self::$query
                                ->insert('antrian', array(
                                        'uid' => $uid,
                                        'no_antrian' => $no_antrian,
                                        'kunjungan' => $parameter['kunjungan'],
                                        'prioritas' => 36,
                                        'pasien' => $parameter['dataObj']['currentPasien'],
                                        'departemen' => $parameter['dataObj']['departemen'],
                                        'dokter' => $parameter['dataObj']['dokter'],
                                        'penjamin' => $parameter['penjamin'],
                                        'waktu_masuk' => parent::format_date(),
                                        'created_at' => parent::format_date(),
                                        'updated_at' => parent::format_date()
                                    )
                                )
                                ->execute();*/

                            $parameter['dataObj']['currentPasien'] = $currentPasien;
                        } else {
                            $antrianKunjungan = self::$query->update('antrian_nomor', array(
                                'status' => 'K',
                                'kunjungan' => $uid,
                                'prioritas' => $parameter['dataObj']['prioritas'],
                                'poli' => $parameter['dataObj']['departemen'],
                                'pasien' => $parameter['dataObj']['currentPasien'],
                                'penjamin' => $parameter['dataObj']['penjamin'],
                                'dokter' => $parameter['dataObj']['dokter']
                            ))
                                ->where(array(
                                    'antrian_nomor.id' => '= ?',
                                    'AND',
                                    'antrian_nomor.status' => '= ?'
                                ), array(
                                    $parameter['dataObj']['currentAntrianID'],
                                    'D'
                                ))
                                ->execute();
                            $antrianKunjungan['response_data'][0]['pasien_detail'] = $PasienDetail['response_data'][0];
                            $antrianKunjungan['response_notif'] = 'K';
                        }




                        //Invoice Manager
                        $InvoiceCheck = self::$query->select('invoice', array( //Check Invoice Master jika sudah ada
                            'uid'
                        ))
                            ->where(array(
                                'invoice.deleted_at' => 'IS NULL',
                                'AND',
                                'invoice.kunjungan' => '= ?'
                            ), array(
                                $uid
                            ))
                            ->execute();


                        if (count($InvoiceCheck['response_data']) > 0) { //Sudah Ada Invoice Master
                            $checkBiayaKartu = self::$query->select('antrian', array( //New Detail. Rekap tagihan
                                'uid'
                            ))
                                ->where(array(
                                    'antrian.pasien' => '= ?'
                                ), array(
                                    $parameter['dataObj']['currentPasien']
                                ))
                                ->execute();

                            if (count($checkBiayaKartu['response_data']) <= 0) { //Biaya Kartu
                                $Invoice = $SInvoice::append_invoice(array(
                                    'invoice' => $InvoiceCheck['response_data'][0]['uid'],
                                    'item' => __UID_KARTU__,
                                    'item_origin' => 'master_tindakan',
                                    'qty' => 1,
                                    'harga' => floatval($HargaKartu['response_data'][0]['harga']),
                                    'subtotal' => floatval($HargaKartu['response_data'][0]['harga']),
                                    'discount' => 0,
                                    'discount_type' => 'N',
                                    'pasien' => $parameter['dataObj']['currentPasien'],
                                    'penjamin' => $parameter['dataObj']['penjamin'],
                                    'billing_group' => 'administrasi',
                                    'keterangan' => 'Biaya kartu pasien baru'
                                ));
                            }

                            $HargaTindakan = $SInvoice::get_harga_tindakan(array(
                                'poli' => $parameter['dataObj']['departemen'],
                                'kelas' => __UID_KELAS_GENERAL_RJ__,
                                'tindakan' => $PoliTindakanInfo['tindakan_konsultasi'],
                                'penjamin' => $parameter['dataObj']['penjamin']
                            ));

                            //print_r($HargaTindakan['response_data']);

                            $Invoice = $SInvoice::append_invoice(array(
                                'invoice' => $InvoiceCheck['response_data'][0]['uid'],
                                'item' => $PoliTindakanInfo['tindakan_konsultasi'],
                                'item_origin' => 'master_tindakan',
                                'qty' => 1,
                                'harga' => floatval($HargaTindakan['response_data'][0]['harga']),
                                'subtotal' => floatval($HargaTindakan['response_data'][0]['harga']),
                                'discount' => 0,
                                'discount_type' => 'N',
                                'pasien' => $parameter['dataObj']['currentPasien'],
                                'penjamin' => $parameter['dataObj']['penjamin'],
                                'billing_group' => 'tindakan',
                                'keterangan' => 'Biaya konsultasi'
                            ));
                        } else { //Belum ada invoice master umum
                            $Invoice = $SInvoice::create_invoice(array(
                                'kunjungan' => $uid,
                                'pasien' => $parameter['dataObj']['pasien'],
                                'keterangan' => ''
                            ));

                            if (isset($Invoice['response_unique']) && $Invoice['response_result'] > 0) {
                                $NewInvoiceUID = $Invoice['response_unique'];
                                $checkBiayaKartu = self::$query->select('antrian', array(
                                    'uid'
                                ))
                                    ->where(array(
                                        'antrian.pasien' => '= ?'
                                    ), array(
                                        $parameter['dataObj']['pasien']
                                    ))
                                    ->execute();

                                if (count($checkBiayaKartu['response_data']) == 0) { //Biaya Kartu
                                    /*if(isset($HargaKartu['response_data']) && count($HargaKartu['response_data']) > 0) {
                                        $Invoice = $SInvoice::append_invoice(array(
                                            'invoice' => $NewInvoiceUID,
                                            'item' => __UID_KARTU__,
                                            'item_origin' => 'master_tindakan',
                                            'qty' => 1,
                                            'harga' => floatval($HargaKartu['response_data'][0]['harga']),
                                            'subtotal' => floatval($HargaKartu['response_data'][0]['harga']),
                                            'discount' => 0,
                                            'discount_type' => 'N',
                                            'pasien' => $parameter['dataObj']['currentPasien'],
                                            'penjamin' => $parameter['dataObj']['penjamin'],
                                            'keterangan' => 'Biaya kartu pasien baru'
                                        ));
                                    }*/
                                    $Invoice = $SInvoice::append_invoice(array(
                                        'invoice' => $NewInvoiceUID,
                                        'item' => __UID_KARTU__,
                                        'item_origin' => 'master_tindakan',
                                        'qty' => 1,
                                        'harga' => floatval($HargaKartu['response_data'][0]['harga']),
                                        'subtotal' => floatval($HargaKartu['response_data'][0]['harga']),
                                        'discount' => 0,
                                        'discount_type' => 'N',
                                        'pasien' => $parameter['dataObj']['currentPasien'],
                                        'penjamin' => $parameter['dataObj']['penjamin'],
                                        'billing_group' => 'administrasi',
                                        'keterangan' => 'Biaya kartu pasien baru'
                                    ));
                                }

                                $HargaTindakan = $SInvoice::get_harga_tindakan(array(
                                    'poli' => $parameter['dataObj']['departemen'],
                                    'kelas' => __UID_KELAS_GENERAL_RJ__,
                                    'tindakan' => $PoliTindakanInfo['tindakan_konsultasi'],
                                    'penjamin' => $parameter['dataObj']['penjamin']
                                ));

                                $Invoice = $SInvoice::append_invoice(array(
                                    'invoice' => $NewInvoiceUID,
                                    'item' => $PoliTindakanInfo['tindakan_konsultasi'],
                                    'item_origin' => 'master_tindakan',
                                    'qty' => 1,
                                    'harga' => floatval($HargaTindakan['response_data'][0]['harga']),
                                    'subtotal' => floatval($HargaTindakan['response_data'][0]['harga']),
                                    'discount' => 0,
                                    'discount_type' => 'N',
                                    'pasien' => $parameter['dataObj']['currentPasien'],
                                    'penjamin' => $parameter['dataObj']['penjamin'],
                                    'billing_group' => 'tindakan',
                                    'keterangan' => 'Biaya konsultasi'
                                ));
                            } else {
                                //
                            }
                        }
                        $antrianKunjungan['response_harga'] = $HargaTindakan;
                        $antrianKunjungan['response_invoice'] = $Invoice;

                        return $antrianKunjungan;
                    } else {
                        return $HargaKartu;
                    }

                } else { // Jika selain umum



                    //Simpan Data Non Umum

                    $CheckNonUmum = self::$query->select('pasien_penjamin', array(
                        'id'
                    ))
                        ->where(array(
                            'pasien_penjamin.penjamin' => '= ?',
                            'AND',
                            'pasien_penjamin.pasien' => '= ?'
                        ), array(
                            $parameter['dataObj']['penjamin'],
                            $parameter['dataObj']['pasien']
                        ))
                        ->execute();

                    if($parameter['dataObj']['penjamin'] === __UIDPENJAMINBPJS__) { //META BPJS
                        if(count($CheckNonUmum['response_data']) > 0) {
                            $RekamPenjamin = self::$query->update('pasien_penjamin', array(
                                'valid_awal' => $parameter['dataObj']['valid_start'],
                                'valid_akhir' => $parameter['dataObj']['valid_end'],
                                'rest_meta' => $parameter['dataObj']['penjaminMeta'],
                                'terdaftar' => parent::format_date(),
                                'updated_at' => parent::format_date(),
                                'deleted_at' => NULL
                            ))
                                ->where(array(
                                    'pasien_penjamin.penjamin' => '= ?',
                                    'AND',
                                    'pasien_penjamin.pasien' => '= ?'
                                ), array(
                                    $parameter['dataObj']['penjamin'],
                                    $parameter['dataObj']['pasien']
                                ))
                                ->execute();
                        } else {
                            $RekamPenjamin = self::$query->insert('pasien_penjamin', array(
                                'penjamin' => $parameter['dataObj']['penjamin'],
                                'pasien' => $parameter['dataObj']['pasien'],
                                'valid_awal' => $parameter['dataObj']['valid_start'],
                                'valid_akhir' => $parameter['dataObj']['valid_end'],
                                'rest_meta' => $parameter['dataObj']['penjaminMeta'],
                                'terdaftar' => parent::format_date(),
                                'created_at' => parent::format_date(),
                                'updated_at' => parent::format_date()
                            ))
                                ->execute();
                        }
                    } else {
                        //TODO: Meta Penjamin Lainnya
                    }




                    //Invoice Manager
                    $InvoiceCheck = self::$query->select('invoice', array( //Check Invoice Master jika sudah ada
                        'uid'
                    ))
                        ->where(array(
                            'invoice.deleted_at' => 'IS NULL',
                            'AND',
                            'invoice.kunjungan' => '= ?'
                        ), array(
                            $uid
                        ))
                        ->execute();


                    if (count($InvoiceCheck['response_data']) > 0) { //Sudah Ada Invoice Master

                        $InvoiceUID = $InvoiceCheck['response_data'][0]['uid'];

                    } else { //Belum ada Invoice Master

                        $Invoice = $SInvoice::create_invoice(array(
                            'kunjungan' => $uid,
                            'pasien' => $parameter['dataObj']['pasien'],
                            'keterangan' => 'Kunjungan Penjamin BPJS'
                        ));

                        $InvoiceUID = $Invoice['response_unique'];

                    }

                    //Simpan tagihan penjamin

                    $HargaTindakan = $SInvoice::get_harga_tindakan(array(
                        'poli' => $parameter['dataObj']['departemen'],
                        'kelas' => __UID_KELAS_GENERAL_RJ__,
                        'tindakan' => $PoliTindakanInfo['tindakan_konsultasi'],
                        'penjamin' => $parameter['dataObj']['penjamin']
                    ));

                    $Invoice = $SInvoice::append_invoice(array(
                        'invoice' => $InvoiceUID,
                        'item' => $PoliTindakanInfo['tindakan_konsultasi'],
                        'item_origin' => 'master_tindakan',
                        'qty' => 1,
                        'harga' => floatval($HargaTindakan['response_data'][0]['harga']),
                        'subtotal' => floatval($HargaTindakan['response_data'][0]['harga']),
                        'status_bayar' => 'Y', //Karena penjamin selain ini otomatis status menjadi terbayar
                        'discount' => 0,
                        'discount_type' => 'N',
                        'pasien' => $parameter['dataObj']['currentPasien'],
                        'penjamin' => $parameter['dataObj']['penjamin'],
                        'billing_group' => 'tindakan',
                        'keterangan' => 'Biaya konsultasi'
                    ));


                    //Cek Pasien Baru?
                    $checkStatusPasien = self::$query->select('antrian', array(
                        'uid'
                    ))
                        ->where(array(
                            'antrian.pasien' => '= ?',
                            'AND',
                            'antrian.deleted_at' => 'IS NULL'
                        ), array(
                            $parameter['dataObj']['currentPasien']
                        ))
                        ->execute();

                    if (count($checkStatusPasien['response_data']) > 0) { //Pasien sudah pernah terdaftar
                        $antrianKunjungan = self::$query->update('antrian_nomor', array(
                            'status' => 'P',
                            'kunjungan' => $uid,
                            'prioritas' => $parameter['dataObj']['prioritas'],
                            'poli' => $parameter['dataObj']['departemen'],
                            'pasien' => $parameter['dataObj']['currentPasien'],
                            'penjamin' => $parameter['dataObj']['penjamin'],
                            'dokter' => $parameter['dataObj']['dokter']
                        ))
                            ->where(array(
                                'antrian_nomor.id' => '= ?',
                                'AND',
                                'antrian_nomor.status' => '= ?'
                            ), array(
                                $parameter['dataObj']['currentAntrianID'],
                                'D'
                            ))
                            ->execute();
                        $Pasien = new Pasien(self::$pdo);
                        $PasienDetail = $Pasien::get_pasien_detail('pasien', $parameter['dataObj']['currentPasien']);
                        $antrianKunjungan['response_data'][0]['pasien_detail'] = $PasienDetail['response_data'][0];

                        if ($antrianKunjungan['response_result'] > 0) {
                            unset($parameter['dataObj']['currentPasien']);
                            unset($parameter['dataObj']['valid_start']);
                            unset($parameter['dataObj']['valid_end']);
                            unset($parameter['dataObj']['penjaminMeta']);
                            unset($parameter['dataObj']['currentPasien']);
                            unset($parameter['dataObj']['bangsal']);
                            $antrian = self::tambah_antrian('antrian', $parameter, $uid);
                            $antrian['response_notif'] = 'P';
                            return $antrian;
                        } else {
                            $antrianKunjungan['response_notif'] = 'P';
                            return $antrianKunjungan;
                        }


                    } else {


                        //Dikenakan Biaya Kartu Jika Pasien Baru
                        $Invoice = $SInvoice::append_invoice(array(
                            'invoice' => $InvoiceUID,
                            'item' => __UID_KARTU__,
                            'item_origin' => 'master_tindakan',
                            'qty' => 1,
                            'harga' => floatval($HargaKartu['response_data'][0]['harga']),
                            'subtotal' => floatval($HargaKartu['response_data'][0]['harga']),
                            'discount' => 0,
                            'discount_type' => 'N',
                            'pasien' => $parameter['dataObj']['currentPasien'],
                            'penjamin' => $parameter['dataObj']['penjamin'],
                            'billing_group' => 'administrasi',
                            'keterangan' => 'Biaya kartu pasien baru'
                        ));


                        $antrianKunjungan = self::$query->update('antrian_nomor', array(
                            'status' => 'K',
                            'kunjungan' => $uid,
                            'prioritas' => $parameter['dataObj']['prioritas'],
                            'poli' => $parameter['dataObj']['departemen'],
                            'pasien' => $parameter['dataObj']['currentPasien'],
                            'penjamin' => $parameter['dataObj']['penjamin'],
                            'dokter' => $parameter['dataObj']['dokter']
                        ))
                            ->where(array(
                                'antrian_nomor.id' => '= ?',
                                'AND',
                                'antrian_nomor.status' => '= ?'
                            ), array(
                                $parameter['dataObj']['currentAntrianID'],
                                'D'
                            ))
                            ->execute();

                        $Pasien = new Pasien(self::$pdo);
                        $PasienDetail = $Pasien::get_pasien_detail('pasien', $parameter['dataObj']['currentPasien']);
                        $antrianKunjungan['response_data'][0]['pasien_detail'] = $PasienDetail['response_data'][0];
                        $antrianKunjungan['response_data'][0]['response_invoice'] = 'asd';
                        $antrianKunjungan['response_notif'] = 'K';
                        return $antrianKunjungan;
                    }


                    //Biaya Non Umum

                }
            } else {
                return array("No Data");
            }
        }
    }


    /*=================== GET ANTRIAN ====================*/

    public function tambah_antrian($table, $parameter, $uid_kunjungan)
    {
        /*dataObj Key
            kunjungan,
            poli,
            dokter,
        */
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        /*$AntrianID = $parameter['dataObj']['currentAntrianID'];*/
        unset($parameter['dataObj']['currentAntrianID']);
        $uid = parent::gen_uuid();
        $no_antrian = self::ambilNomorAntrianPoli($parameter['dataObj']['departemen']);

        $allData = [];
        $allData['uid'] = $uid;
        $allData['no_antrian'] = $no_antrian;
        $allData['kunjungan'] = $uid_kunjungan;
        $allData['waktu_masuk'] = parent::format_date();
        $allData['created_at'] = parent::format_date();
        $allData['updated_at'] = parent::format_date();

        /*=========== MATCHING VALUE WITH KEY, BECAUSE KEY NAME SAME AS FIELD NAME AT TABLE =========*/
        foreach ($parameter['dataObj'] as $key => $value) {
            if($key !== 'konsul') {
                $allData[$key] = $value;
            }

            if($key === 'cara_datang') {
                $allData[$key] = intval($value);
            }
        }

        $antrian = self::$query
            ->insert($table, $allData)
            ->execute();

        if ($antrian['response_result'] > 0) {
            if(isset($parameter['dataObj']['konsul'])) {
                $updateNomorAntrian = self::$query->update('antrian_nomor', array(
                    'antrian' => $uid,
                    'status' => 'K',
                    'poli' => $allData['departemen'],
                    'dokter' => $allData['dokter'],
                ))
                    ->where(array(
                        'antrian_nomor.pasien' => '= ?',
                        'AND',
                        'antrian_nomor.penjamin' => '= ?'
                    ), array(
                            $allData['pasien'],
                            $allData['penjamin']
                        )
                    )
                    ->execute();
            } else {
                $updateNomorAntrian = self::$query->update('antrian_nomor', array(
                    'antrian' => $uid,
                    'status' => 'P'
                ))
                    ->where(array(
                        'antrian_nomor.pasien' => '= ?',
                        'AND',
                        'antrian_nomor.poli' => '= ?',
                        'AND',
                        'antrian_nomor.dokter' => '= ?',
                        'AND',
                        'antrian_nomor.penjamin' => '= ?',
                        'AND',
                        'antrian_nomor.status' => '= ?'
                    ), array(
                            $allData['pasien'],
                            $allData['departemen'],
                            $allData['dokter'],
                            $allData['penjamin'],
                            'N'
                        )
                    )
                    ->execute();
            }

            if ($updateNomorAntrian['response_result'] > 0) {
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
                ));
                return $antrian;

            } else {
                return $updateNomorAntrian;
            }
        } else {
            return $antrian;
        }
    }

    public function ambilNomorAntrianPoli($poli)
    {
        $waktu = date("Y-m-d", strtotime(parent::format_date()));

        $data = self::$query
            ->select('antrian', array('no_antrian'))
            ->where(array(
                'antrian.deleted_at' => 'IS NULL',
                'AND',
                'antrian.departemen' => '= ?',
                'AND',
                'DATE(antrian.waktu_masuk)' => '= ?'
            ), array(
                    $poli,
                    $waktu
                )
            )
            ->order(array('no_antrian' => 'DESC'))
            ->limit(1)
            ->execute();


        $nomor = 1;
        if ($data['response_result'] > 0) {
            $nomor = intval($data['response_data'][0]['no_antrian']) + 1;
        }

        return $nomor;
    }

    private function ubah_dokter_antrian($parameter)
    {
        return self::$query->update('antrian', array(
            'dokter' => $parameter['dokter']
        ))
            ->where(array(
                'antrian.deleted_at' => 'IS NULL',
                'AND',
                'antrian.uid' => '= ?'
            ), array(
                $parameter['uid']
            ))
            ->execute();
    }

    public function get_antrian_by_dokter($parameter, $condition = '')
    {
        if($condition === 'igd') {
            $paramKey = array(
                'antrian.waktu_keluar' => 'IS NULL',
                'AND',
                'antrian.deleted_at' => 'IS NULL'
            );

            $parameterValue = array();
        } else {
            $paramKey = array(
                'antrian.waktu_keluar' => 'IS NULL',
                'AND',
                'antrian.deleted_at' => 'IS NULL',
                'AND',
                'antrian.dokter' => '= ?'
            );

            $parameterValue = array($parameter);
        }

        $data = self::$query->select('antrian', array(
            'uid',
            'pasien as uid_pasien',
            'dokter as uid_dokter',
            'departemen as uid_poli',
            'penjamin as uid_penjamin',
            'waktu_masuk',
            'prioritas'
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
                'uid as uid_kunjungan',
                'pegawai as uid_resepsionis'
            ))
            ->on(array(
                /*array('pasien.uid','=', 'antrian.pasien'),
                array('master_poli.uid','=', 'antrian.departemen'),
                array('pegawai.uid','=', 'antrian.dokter'),
                array('master_penjamin.uid','=', 'antrian.penjamin'),
                array('kunjungan.uid','=', 'antrian.kunjungan')*/
                array('antrian.pasien', '=', 'pasien.uid'),
                array('antrian.departemen', '=', 'master_poli.uid'),
                array('antrian.dokter', '=', 'pegawai.uid'),
                array('antrian.penjamin', '=', 'master_penjamin.uid'),
                array('antrian.kunjungan', '=', 'kunjungan.uid')
            ))
            ->where($paramKey, $parameterValue)
            ->order(array(
                'antrian.prioritas' => 'DESC',
                'antrian.waktu_masuk' => 'DESC'
            ))
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['waktu_masuk'] = date('d F Y', strtotime($value['waktu_masuk'])) . ' - [' . date('H:i', strtotime($value['waktu_masuk'])) . ']';
            $autonum++;

            $pegawai = new Pegawai(self::$pdo);
            $get_pegawai = $pegawai->get_detail($data['response_data'][$key]['uid_resepsionis']);
            $data['response_data'][$key]['user_resepsionis'] = $get_pegawai['response_data'][0]['nama'];
        }

        return $data;
    }

    public function get_kunjungan_detail($parameter)
    {
        $data = self::$query
            ->select('kunjungan', array(
                    'uid',
                    'waktu_masuk',
                    'pj_pasien',
                    'info_didapat_dari'
                )
            )
            ->where(array(
                'kunjungan.uid' => '= ?',
                'AND',
                'kunjungan.deleted_at' => 'IS NULL'
            ), array($parameter)
            )
            ->execute();

        return $data;
    }

    public function get_data_pasien_dan_antrian($parameter)
    {    //$parameter = uid_antrian
        $dataAntrian = self::get_data_antrian_detail($parameter);

        $pasien = new Pasien(self::$pdo);
        $dataPasien = $pasien->get_data_pasien($dataAntrian['uid_pasien']);

        $result = ['antrian' => $dataAntrian, 'pasien' => $dataPasien];

        return $result;
    }

    public function get_data_antrian_detail($parameter)
    { //$parameter = uid antrian
        /*-------- GET DATA ANTRIAN ----------*/
        $antrian = new Antrian(self::$pdo);
        $param = ['', 'antrian-detail', $parameter];
        $get_antrian = $antrian->__GET__($param);
        $result = array(
            "uid" => $get_antrian['response_data'][0]['uid'],
            "kunjungan" => $get_antrian['response_data'][0]['kunjungan'],
            "uid_pasien" => $get_antrian['response_data'][0]['pasien'],
            "departemen" => $get_antrian['response_data'][0]['departemen'],
            "penjamin" => $get_antrian['response_data'][0]['penjamin'],
            "dokter" => $get_antrian['response_data'][0]['dokter'],
            "waktu_masuk" => $get_antrian['response_data'][0]['waktu_masuk']
        );

        return $result;
    }

    public function __GET__($parameter = array())
    {
        try {
            switch ($parameter[1]) {
                case 'antrian':
                    return self::get_list_antrian('antrian');
                    break;

                case 'igd_old':
                    return self::get_list_antrian('antrian', __POLI_IGD__);
                    break;

                case 'rawat_inap':
                    //return self::get_list_antrian('antrian', __POLI_INAP__);
                    return self::get_list_antrian_inap($parameter);
                    break;

                case 'antrian-detail':
                    return self::get_antrian_detail('antrian', $parameter[2]);
                    break;

                case 'cari-pasien':
                    return self::cari_pasien($parameter[2]);
                    break;

                case 'pasien-detail':
                    return self::pasien_detail($parameter[2]);
                    break;

                case 'cek-status-antrian':
                    return self::cekStatusAntrian($parameter[2]);
                    break;

                case 'get-antrian-by-poli':
                    return self::get_antrian_by_poli($parameter[2]);
                    break;

                /*case 'ambil-antrian-poli':
                    return self::ambilNomorAntrianPoli($parameter[2]);
                    break;*/

                default:
                    # code...
                    break;
            }
        } catch (QueryException $e) {
            return 'Error => ' . $e;
        }
    }

    private function get_list_antrian_backend($parameter) {
        if($parameter['poli'] === 'all') {
            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                $paramData = array(
                    '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                    'AND',
                    'antrian.deleted_at' => 'IS NULL',
                    'AND',
                    'kunjungan.waktu_keluar' => 'IS NULL'
                );
                $paramValue = array();
            } else {
                $paramData = array(
                    'antrian.deleted_at' => 'IS NULL',
                    'AND',
                    'kunjungan.waktu_keluar' => 'IS NULL'
                );
                $paramValue = array();
            }
        } else {
            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                $paramData = array(
                    '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                    'AND',
                    'antrian.deleted_at' => 'IS NULL',
                    'AND',
                    'kunjungan.waktu_keluar' => 'IS NULL',
                    'AND',
                    'master_poli.uid' => '= ?'
                );
                $paramValue = array(
                    $parameter['poli']
                );
            } else {
                $paramData = array(
                    'antrian.deleted_at' => 'IS NULL',
                    'AND',
                    'kunjungan.waktu_keluar' => 'IS NULL',
                    'AND',
                    'master_poli.uid' => '= ?'
                );
                $paramValue = array(
                    $parameter['poli']
                );
            }
        }


        if (intval($parameter['length']) < 0) {
            $data = self::$query
                ->select('antrian',
                    array(
                        'uid',
                        'pasien as uid_pasien',
                        'dokter as uid_dokter',
                        'departemen as uid_poli',
                        'penjamin as uid_penjamin',
                        'waktu_masuk',
                        'waktu_keluar'
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
                        array('pasien.uid', '=', 'antrian.pasien'),
                        array('master_poli.uid', '=', 'antrian.departemen'),
                        array('pegawai.uid', '=', 'antrian.dokter'),
                        array('master_penjamin.uid', '=', 'antrian.penjamin'),
                        array('kunjungan.uid', '=', 'antrian.kunjungan')
                    )
                )
                ->where($paramData, $paramValue)
                ->order(array(
                    'antrian.waktu_masuk' => 'DESC'
                ))
                ->execute();
        } else {
            $data = self::$query
                ->select('antrian',
                    array(
                        'uid',
                        'pasien as uid_pasien',
                        'dokter as uid_dokter',
                        'departemen as uid_poli',
                        'penjamin as uid_penjamin',
                        'waktu_masuk',
                        'waktu_keluar'
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
                        array('pasien.uid', '=', 'antrian.pasien'),
                        array('master_poli.uid', '=', 'antrian.departemen'),
                        array('pegawai.uid', '=', 'antrian.dokter'),
                        array('master_penjamin.uid', '=', 'antrian.penjamin'),
                        array('kunjungan.uid', '=', 'antrian.kunjungan')
                    )
                )
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->order(array(
                    'antrian.waktu_masuk' => 'DESC'
                ))
                ->execute();
        }

        $data['response_draw'] = intval($parameter['draw']);
        $autonum = intval($parameter['start']) + 1;
        $pegawai = new Pegawai(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;


            $data['response_data'][$key]['waktu_masuk'] = date('d F Y', strtotime($value['waktu_masuk'])) . ' - [' . date('H:i', strtotime($value['waktu_masuk'])) . ']';

            $get_pegawai = $pegawai->get_detail($data['response_data'][$key]['uid_resepsionis']);
            $data['response_data'][$key]['user_resepsionis'] = $get_pegawai['response_data'][0]['nama'];

            //Harga
            $harga = self::$query->select('master_poli_tindakan_penjamin', array(
                'id',
                'harga',
                'uid_poli',
                'uid_tindakan',
                'uid_penjamin',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'master_poli_tindakan_penjamin.deleted_at' => 'IS NULL',
                    'AND',
                    'master_poli_tindakan_penjamin.uid_poli' => '= ?',
                    'AND',
                    'master_poli_tindakan_penjamin.uid_tindakan' => '= ?',
                    'AND',
                    'master_poli_tindakan_penjamin.uid_penjamin' => '= ?'),
                    array(
                        $value['uid_poli'],
                        __UIDKONSULDOKTER__,
                        __UIDPENJAMINUMUM__))
                ->execute();

            if ($value['uid_penjamin'] == __UIDPENJAMINBPJS__) {

                $SEP = self::$query->select('bpjs_sep', array(
                    'uid',
                    'sep_no'
                ))
                    ->where(array(
                        'bpjs_sep.antrian' => '= ?',
                        'AND',
                        'bpjs_sep.deleted_at' => 'IS NULL'
                    ), array(
                        $value['uid']
                    ))
                    ->execute();
                if (count($SEP['response_data']) > 0) {
                    $data['response_data'][$key]['sep'] = $SEP['response_data'][0]['sep_no'];
                } else {
                    $data['response_data'][$key]['sep'] = $SEP;
                }
            }


            $data['response_data'][$key]['harga'] = $harga['response_data'][0];


            $autonum++;
        }

        $AntrianTotal = self::$query->select('antrian', array(
            'uid'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($AntrianTotal['response_data']);
        $data['recordsFiltered'] = count($data['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function get_list_antrian_inap($parameter) {
        $data = self::$query->select('rawat_inap', array(
            'uid',
            'pasien',
            'dokter',
            'penjamin',
            'kunjungan',
            'waktu_masuk',
            'waktu_keluar',
            'kamar',
            'bed',
            'keterangan',
            'jenis_pulang',
            'alasan_pulang'
        ))
            ->join('pasien', array(
                    'nama as pasien',
                    'no_rm'
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
                    array('pasien.uid', '=', 'rawat_inap.pasien'),
                    array('pegawai.uid', '=', 'rawat_inap.dokter'),
                    array('master_penjamin.uid', '=', 'rawat_inap.penjamin'),
                    array('kunjungan.uid', '=', 'rawat_inap.kunjungan')
                )
            )
            ->where(array(
                'rawat_inap.deleted_at' => 'IS NULL'
            ), array())
            ->execute();
        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }
        return $data;
    }

    private function get_list_antrian($table, $target = '')
    {
        if($target !== '') {

            $data = self::$query
                ->select($table,
                    array(
                        'uid',
                        'pasien as uid_pasien',
                        'dokter as uid_dokter',
                        'departemen as uid_poli',
                        'penjamin as uid_penjamin',
                        'waktu_masuk',
                        'waktu_keluar',
                        'created_at'
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
                        array('pasien.uid', '=', $table . '.pasien'),
                        array('master_poli.uid', '=', $table . '.departemen'),
                        array('pegawai.uid', '=', $table . '.dokter'),
                        array('master_penjamin.uid', '=', $table . '.penjamin'),
                        array('kunjungan.uid', '=', $table . '.kunjungan')
                    )
                )
                ->where(array(
                        $table . '.departemen' => '= ?',
                        'AND',
                        /*$table . '.waktu_keluar' => 'IS NULL',
                        'AND',*/

                        $table . '.deleted_at' => 'IS NULL',
                        'AND',
                        'kunjungan.waktu_keluar' => 'IS NULL'
                    ), array(
                        $target
                    )
                )
                ->order(
                    array(
                        $table . '.waktu_masuk' => 'DESC'
                    )
                )
                ->execute();
        } else {
            $data = self::$query
                ->select($table,
                    array(
                        'uid',
                        'pasien as uid_pasien',
                        'dokter as uid_dokter',
                        'departemen as uid_poli',
                        'penjamin as uid_penjamin',
                        'waktu_masuk',
                        'waktu_keluar'
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
                        array('pasien.uid', '=', $table . '.pasien'),
                        array('master_poli.uid', '=', $table . '.departemen'),
                        array('pegawai.uid', '=', $table . '.dokter'),
                        array('master_penjamin.uid', '=', $table . '.penjamin'),
                        array('kunjungan.uid', '=', $table . '.kunjungan')
                    )
                )
                ->where(array(
                        /*$table . '.waktu_keluar' => 'IS NULL',
                        'AND',*/
                        $table . '.deleted_at' => 'IS NULL',
                        'AND',
                        'kunjungan.waktu_keluar' => 'IS NULL'
                    )
                )
                ->order(
                    array(
                        $table . '.waktu_masuk' => 'DESC'
                    )
                )
                ->execute();
        }

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['waktu_masuk'] = date('d F Y', strtotime($value['waktu_masuk'])) . ' - [' . date('H:i', strtotime($value['waktu_masuk'])) . ']';
            $autonum++;

            $pegawai = new Pegawai(self::$pdo);
            $get_pegawai = $pegawai->get_detail($data['response_data'][$key]['uid_resepsionis']);
            $data['response_data'][$key]['user_resepsionis'] = $get_pegawai['response_data'][0]['nama'];

            //Harga
            $harga = self::$query->select('master_poli_tindakan_penjamin', array(
                'id',
                'harga',
                'uid_poli',
                'uid_tindakan',
                'uid_penjamin',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'master_poli_tindakan_penjamin.deleted_at' => 'IS NULL',
                    'AND',
                    'master_poli_tindakan_penjamin.uid_poli' => '= ?',
                    'AND',
                    'master_poli_tindakan_penjamin.uid_tindakan' => '= ?',
                    'AND',
                    'master_poli_tindakan_penjamin.uid_penjamin' => '= ?'),
                    array(
                        $value['uid_poli'],
                        __UIDKONSULDOKTER__,
                        __UIDPENJAMINUMUM__))
                ->execute();


            //Cek Penjamin. Jika BPJS maka cek status SEP pada lokal
            if ($value['uid_penjamin'] == __UIDPENJAMINBPJS__) {
                //Cek tabel SEP
                /*$SEP = self::$query->select('penjamin_sep', array(
                    'id',
                    'bpjs_no_sep'
                ))
                    ->where(array(
                        'penjamin_sep.pasien' => '= ?',
                        'AND',
                        'penjamin_sep.created_at' => '>= now()::date',
                        'AND',
                        'penjamin_sep.deleted_at' => 'IS NULL'
                    ), array(
                        $value['uid_pasien']
                    ))
                    ->execute();

                if (count($SEP['response_data']) == 0) {
                    $data['response_data'][$key]['sep'] = 0;
                } else {
                    $data['response_data'][$key]['sep'] = $SEP['response_data'][0]['bpjs_no_sep'];
                }*/

                $SEP = self::$query->select('bpjs_sep', array(
                    'uid',
                    'sep_no'
                ))
                    ->where(array(
                        'bpjs_sep.antrian' => '= ?',
                        'AND',
                        'bpjs_sep.deleted_at' => 'IS NULL'
                    ), array(
                        $value['uid']
                    ))
                    ->execute();
                if (count($SEP['response_data']) > 0) {
                    $data['response_data'][$key]['sep'] = $SEP['response_data'][0]['sep_no'];
                } else {
                    $data['response_data'][$key]['sep'] = $SEP;
                }
            }


            $data['response_data'][$key]['harga'] = $harga['response_data'][0];
        }

        return $data;
    }

    public function get_antrian_detail($table, $params)
    {
        $data = self::$query
            ->select($table, array(
                    'uid',
                    'pasien',
                    'kunjungan',
                    'departemen',
                    'penjamin',
                    'dokter',
                    'waktu_masuk',
                    'waktu_keluar',
                    'prioritas'
                )
            )
            ->where(array(
                $table . '.deleted_at' => 'IS NULL',
                'AND',
                $table . '.uid' => '= ?'
            ),
                array($params)
            )
            ->execute();
        //More Info
        foreach ($data['response_data'] as $key => $value) {
            $Kunjungan = self::$query->select('kunjungan', array(
                'uid',
                'waktu_masuk',
                'waktu_masuk',
                'pegawai',
                'pj_pasien',
                'info_didapat_dari'
            ))
                ->where(array(
                    'kunjungan.uid' => '= ?',
                    'AND',
                    'kunjungan.deleted_at' => 'IS NULL'
                ), array(
                    $value['kunjungan']
                ))
                ->execute();
            $data['response_data'][$key]['kunjungan_detail'] = $Kunjungan['response_data'][0];

            $Pasien = new Pasien(self::$pdo);
            $PasienData = $Pasien::get_pasien_detail('pasien', $value['pasien']);

            $Terminologi = new Terminologi(self::$pdo);
            $Penjamin = new Penjamin(self::$pdo);

            $Poli = new Poli(self::$pdo);
            $PoliData = $Poli::get_poli_detail($value['departemen']);
            $data['response_data'][$key]['poli_info'] = $PoliData['response_data'][0];

            $PasienData['response_data'][0]['tanggal_lahir'] = date('d F Y', strtotime($PasienData['response_data'][0]['tanggal_lahir']));

            //Terminologi Jenis Kelamin
            $TerminologiJenkel = $Terminologi::get_terminologi_items_detail('terminologi_item', $PasienData['response_data'][0]['jenkel']);
            $PasienData['response_data'][0]['jenkel_nama'] = $TerminologiJenkel['response_data'][0]['nama'];

            $data['response_data'][$key]['pasien_info'] = $PasienData['response_data'][0];


            //Penjamin
            $data['response_data'][$key]['penjamin_data'] = $Penjamin::get_penjamin_detail($value['penjamin'])['response_data'][0];
        }
        return $data;
    }

    public function cari_pasien($params)
    {
        $parameter = strtoupper($params);

        $data = self::$query
            ->select('pasien', array(
                    'uid',
                    'no_rm',
                    'nik',
                    'nama',
                    'tanggal_lahir',
                    'jenkel AS id_jenkel',
                    'panggilan AS id_panggilan'
                )
            )
            ->where(array(
                'pasien.nik' => 'LIKE \'%' . $parameter . '%\'',
                'OR',
                'pasien.no_rm' => 'LIKE \'%' . $parameter . '%\'',
                'OR',
                'pasien.nama' => 'LIKE \'%' . $parameter . '%\'',
                'AND',
                'pasien.deleted_at' => 'IS NULL'
            ),
                array()
            )
            ->order(array(
                    'pasien.created_at' => 'ASC'
                )
            )
            ->limit(10)
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;

            $data['response_data'][$key]['berobat'] = self::cekStatusAntrian($value['uid']);

            $term = new Terminologi(self::$pdo);

            $param = ['', 'terminologi-items-detail', $value['id_panggilan']];
            $get_panggilan = $term->__GET__($param);
            $data['response_data'][$key]['panggilan'] = $get_panggilan['response_data'][0]['nama'];

            $param = ['', 'terminologi-items-detail', $value['id_jenkel']];
            $get_jenkel = $term->__GET__($param);
            $data['response_data'][$key]['jenkel'] = $get_jenkel['response_data'][0]['nama'];
        }

        return $data;
    }

    /*================= GET DATA ANTRIAN FOR ALL CLASS USE ==============*/

    private function cekStatusAntrian($uid_pasien)
    {
        $status_berobat = false;

        $data = self::$query
            ->select('antrian', array(
                    'uid',
                    'pasien',
                    'waktu_keluar'
                )
            )
            ->where(array(
                'antrian.waktu_keluar' => 'IS NULL',
                'AND',
                'antrian.pasien' => '= ?'
            ),
                array(
                    $uid_pasien
                )
            )
            ->execute();

        if (count($data['response_data']) > 0) {
            $status_berobat = true;
        }

        return $status_berobat;
    }
    /*====================================================================*/


    /*================= GET DATA ANTRIAN DAN PASIEN FOR ALL CLASS USE ==============*/

    public function pasien_detail($parameter)
    {
        $pasien = new Pasien(self::$pdo);
        $dataPasien = array();

        $get_pasien = $pasien->get_pasien_detail('pasien', $parameter);
        if (count($get_pasien['response_data']) > 0) {
            $dataPasien = $get_pasien['response_data'][0];

            $term = new Terminologi(self::$pdo);
            $param_arr = ['', 'terminologi-items-detail', $dataPasien['jenkel']];
            $get_jenkel = $term->__GET__($param_arr);
            $dataPasien['nama_jenkel'] = $get_jenkel['response_data'][0]['nama'];
        }

        return $dataPasien;
    }

    /*====================================================================*/

    public function get_antrian_by_poli($parameter)
    {
        $data = self::$query
            ->select('antrian',
                array(
                    'uid',
                    'prioritas',
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
                    array('pasien.uid', '=', 'antrian.pasien'),
                    array('master_poli.uid', '=', 'antrian.departemen'),
                    array('pegawai.uid', '=', 'antrian.dokter'),
                    array('master_penjamin.uid', '=', 'antrian.penjamin'),
                    array('kunjungan.uid', '=', 'antrian.kunjungan')
                )
            )
            ->order(array(
                'prioritas' => 'DESC'
            ))
            ->where(array(
                'antrian.waktu_keluar' => 'IS NULL',
                'AND',
                'antrian.deleted_at' => 'IS NULL',
                'AND',
                'antrian.departemen' => '= ?'
            ), array(
                    $parameter
                )
            )
            ->order(
                array(
                    'antrian.waktu_masuk' => 'DESC'
                )
            )
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['waktu_masuk'] = date('d F Y', strtotime($value['waktu_masuk'])) . ' - [' . date('H:i', strtotime($value['waktu_masuk'])) . ']';
            $autonum++;

            $pegawai = new Pegawai(self::$pdo);
            $get_pegawai = $pegawai->get_detail($data['response_data'][$key]['uid_resepsionis']);
            $data['response_data'][$key]['user_resepsionis'] = $get_pegawai['response_data'][0]['nama'];

            $prioritas = self::$query->select('terminologi_item', array(
                'id',
                'nama'
            ))
                ->where(array(
                    'terminologi_item.deleted_at' => 'IS NULL',
                    'AND',
                    'terminologi_item.id' => '= ?'
                ), array(
                    $value['prioritas']
                ))
                ->execute();
            $data['response_data'][$key]['prioritas'] = $prioritas['response_data'][0];
        }

        return $data;
    }
}