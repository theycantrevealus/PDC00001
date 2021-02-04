<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;
use PondokCoder\Pegawai as Pegawai;
use PondokCoder\Pasien as Pasien;
use PondokCoder\Ruangan as Ruangan;

class AntrianOnline extends Utility {
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
				case 'cek_pasien':
                    return self::cek_pasien($parameter[2]);
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

			// case 'edit_penjamin':
			// 	return self::edit_penjamin($parameter);
			// 	break;

			case 'tambah_kunjungan':
                return self::tambah_kunjungan($parameter);
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
    
    public static function cek_pasien($parameter) // noktp_pasien
    {
        $data = self::$query
            ->select('pasien', array(
                'uid'
            ))
            ->where(
                array(
                    'pasien.nik'        => '= ?',
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


	private static function tambah_kunjungan($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);
        $uid = parent::gen_uuid();

        //Tentukan tindakan untuk poli bersangkutan
        $PoliTindakan = new Poli(self::$pdo);
        $PoliTindakanInfo = $PoliTindakan::get_poli_detail($parameter['dataObj']['departemen'])['response_data'][0];

		$antrian_nomor = self::tambah_antrian_nomor();

        if ($antrian_nomor['response_unique'] != "" && $antrian_nomor['response_unique'] != null)
        {

            $id_antrian_nomor = $antrian_nomor['response_unique'];

            $kunjungan = self::$query->insert('kunjungan', array(
                'uid' => $uid,
                'waktu_masuk' => parent::format_date(),
                'pj_pasien' => $parameter['dataObj']['pj_pasien'],
                'info_didapat_dari' => $parameter['dataObj']['info_didapat_dari'],
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
                        'kunjungan',
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
                        $antrianKunjungan = self::$query->update('antrian_nomor', array(
                            'status' => 'K',
                            'kunjungan' => $uid,
                            'poli' => $parameter['dataObj']['departemen'],
                            'pasien' => $parameter['dataObj']['pasien'],
                            'penjamin' => $parameter['dataObj']['penjamin'],
                            'dokter' => $parameter['dataObj']['dokter']
                        ))
                            ->where(array(
                                'antrian_nomor.id' => '= ?',
                                'AND',
                                'antrian_nomor.status' => '= ?'
                            ), array(
                                $id_antrian_nomor,
                                'N'
                            ))
                            ->execute();
                        $Pasien = new Pasien(self::$pdo);
                        $PasienDetail = $Pasien::get_pasien_detail('pasien', $parameter['dataObj']['pasien']);
                        $antrianKunjungan['response_data'][0]['pasien_detail'] = $PasienDetail['response_data'][0];
                        $antrianKunjungan['response_notif'] = 'K';


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
                                    $parameter['dataObj']['pasien']
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
                                    'pasien' => $parameter['dataObj']['pasien'],
                                    'penjamin' => $parameter['dataObj']['penjamin'],
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
                                'pasien' => $parameter['dataObj']['pasien'],
                                'penjamin' => $parameter['dataObj']['penjamin'],
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
                                            'pasien' => $parameter['dataObj']['pasien'],
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
                                        'pasien' => $parameter['dataObj']['pasien'],
                                        'penjamin' => $parameter['dataObj']['penjamin'],
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
                                    'pasien' => $parameter['dataObj']['pasien'],
                                    'penjamin' => $parameter['dataObj']['penjamin'],
                                    'keterangan' => 'Biaya konsultasi'
                                ));
                            } else {
                                //
                            }
                        }

                        $antrianKunjungan['response_invoice'] = $Invoice;

                        return $antrianKunjungan;
                    } else {
                        return $HargaKartu;
                    }

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
                        'pasien' => $parameter['dataObj']['pasien'],
                        'penjamin' => $parameter['dataObj']['penjamin'],
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
                            $parameter['dataObj']['pasien']
                        ))
                        ->execute();

                    if (count($checkStatusPasien['response_data']) > 0) { //Pasien sudah pernah terdaftar
                        $antrianKunjungan = self::$query->update('antrian_nomor', array(
                            'status' => 'P',
                            'kunjungan' => $uid,
                            'poli' => $parameter['dataObj']['departemen'],
                            'pasien' => $parameter['dataObj']['pasien'],
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
                        $PasienDetail = $Pasien::get_pasien_detail('pasien', $parameter['dataObj']['pasien']);
                        $antrianKunjungan['response_data'][0]['pasien_detail'] = $PasienDetail['response_data'][0];

                        if ($antrianKunjungan['response_result'] > 0) {
                            unset($parameter['dataObj']['pasien']);
                            $antrian['response_notif'] = 'P';
                            $antrian = self::tambah_antrian('antrian', $parameter, $uid);
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
                            'pasien' => $parameter['dataObj']['pasien'],
                            'penjamin' => $parameter['dataObj']['penjamin'],
                            'keterangan' => 'Biaya kartu pasien baru'
                        ));


                        $antrianKunjungan = self::$query->update('antrian_nomor', array(
                            'status' => 'K',
                            'kunjungan' => $uid,
                            'poli' => $parameter['dataObj']['departemen'],
                            'pasien' => $parameter['dataObj']['pasien'],
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
                        $PasienDetail = $Pasien::get_pasien_detail('pasien', $parameter['dataObj']['pasien']);
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

	public function tambah_antrian($table, $parameter, $uid_kunjungan)
    {
        
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

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
            $allData[$key] = $value;
        }

        $antrian = self::$query
            ->insert($table, $allData)
            ->execute();

        if ($antrian['response_result'] > 0) {
            $updateNomorAntrian = self::$query->update('antrian_nomor', array(
                'antrian' => $uid
            ))
                ->where(array(
                    'antrian_nomor.pasien' => '= ?',
                    'AND',
                    'antrian_nomor.poli' => '= ?',
                    'AND',
                    'antrian_nomor.dokter' => '= ?',
                    'AND',
                    'antrian_nomor.penjamin' => '= ?'
                ), array(
                        $allData['pasien'],
                        $allData['departemen'],
                        $allData['dokter'],
                        $allData['penjamin']
                    )
                )
                ->execute();

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
    
    
	private static function get_anjungan_online() {
		$data = self::$query->select('antrian_jenis', array(
			'uid',
			'nama',
			'kode',
			'allow_jalur',
			'created_at',
			'updated_at'
		))
		->where(array(
			'antrian_jenis.uid'	=> '= ?',
			'AND',
			'antrian_jenis.deleted_at' => 'IS NULL'
		), array(
			__UIDANTRIANONLINE__
		))
		->execute();
		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}


	private static function tambah_antrian_nomor() 
	{
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$detail_antrian_jenis = self::get_jenis_detail(__UIDANTRIANONLINE__);
		$newUrut = self::$query->select('antrian_nomor', array(
			'id'
		))
		->where(array(
            'DATE(antrian_nomor.created_at)' => '= ?',
			'AND',
			'antrian_nomor.jenis_antrian' => '= ?'
		), array(
		    date('Y-m-d'),
			__UIDANTRIANONLINE__
        ))
		->execute();

        $get_loket = self::$query
            ->select('master_loket', array(
                'uid'
            ))
            ->where(array(
                    'master_loket.user_active'  => '= ?',
                    'AND',
                    'master_loket.deleted_at'   => 'IS NULL'
                ), array(
                    $UserData['data']->uid
            ))
            ->execute();

        if ($get_loket['response_result'] > 0)
        {
            $loket = $get_loket['response_data'][0]['uid'];

            $worker = self::$query->insert('antrian_nomor', array(
                'pegawai'       => $UserData['data']->uid,
                'nomor_urut' 	=> count($newUrut['response_data']) + 1,
                'loket'         => $loket,
                'jenis_antrian' => __UIDANTRIANONLINE__,
                'created_at' 	=> parent::format_date(),
                'status' 		=> 'N'
            ))
            ->returning('id')
            ->execute();
            
            if($worker['response_result'] > 0) {
                //Get Kode Jalur Antrian
                $worker['response_antrian'] = $detail_antrian_jenis[0]['kode'] . '-' . strval((count($newUrut['response_data']) + 1));
    
                //Add notify
                $notification = self::$query->insert('notification', array(
                    'sender' => $UserData['data']->uid,
                    'receiver_type' => 'group',
                    'receiver' => __UID_PENDAFTARAN__,
                    'protocols' => 'anjungan_kunjungan_baru',
                    'notify_content' => 'Antrian baru dari Reservasi Online',
                    'type' => 'warning',
                    'created_at' => parent::format_date(),
                    'status' => 'N'
                ))
                ->execute();
            }

		    return $worker;
        
        } else {
            return "Tambah antrian nomor gagal";
        }		
		
	}


	private static function get_jenis_detail($parameter) {
		$data = self::$query->select('antrian_jenis', array(
			'uid',
			'nama',
			'kode'
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