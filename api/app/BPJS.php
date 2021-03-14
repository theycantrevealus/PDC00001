<?php

namespace PondokCoder;

use DateInterval;
use DatePeriod;
use DateTime;
use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\Pasien as Pasien;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class BPJS extends Utility {
	static $pdo;
	static $query;

	static $kodePPK;
	static $data_api;
	static $secretKey_api;
	static $base_url;

	static $tStamp;
	static $signature;
	static $encodedSignature;
	static $ch;
	static $headers;
	
	protected static function getConn(){
		return self::$pdo;
	}

	public function __construct($connection) {
		self::$pdo = $connection;
		self::$query = new Query(self::$pdo);
		self::$kodePPK = __KODE_PPK__;
		self::$data_api = __DATA_API_LIVE__;
		self::$secretKey_api = __SECRET_KEY_LIVE_BPJS__;
		self::$base_url = __BASE_LIVE_BPJS__;
        //self::$base_url = __BASE_STAGING_BPJS__;


		date_default_timezone_set('UTC');
		self::$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		self::$signature = hash_hmac('sha256', self::$data_api ."&". self::$tStamp , self::$secretKey_api, true);
		self::$encodedSignature = base64_encode(self::$signature);

		self::$ch = curl_init();

		self::$headers = array(
			'X-cons-id: ' . self::$data_api . '',
			'X-timestamp: ' . self::$tStamp . '' ,
			'X-signature: ' . self::$encodedSignature . '',
			'Content-Type:application/json',     
		);

		curl_setopt(self::$ch, CURLOPT_HTTPHEADER, self::$headers);
		curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt(self::$ch, CURLOPT_TIMEOUT, 3);
		curl_setopt(self::$ch, CURLOPT_HTTPGET, 1);
		curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt(self::$ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt(self::$ch, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
	}

	public function  __DELETE__($parameter = array()) {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        switch ($parameter[6]) {
            case 'SEP':
                return self::hapus_sep($parameter);
                break;
            default:
                return $parameter;
                break;
        }
    }

	public function __GET__($parameter = array()) {
		try {

			switch($parameter[1]) {
				case 'get_faskes':
					return self::get_faskes();
                case 'get_diagnosa':
                    return self::get_diagnosa();
					break;
                case 'get_poli':
                    return self::get_poli();
                    break;
                case 'get_poli_detail':
                    return self::get_poli_detail($parameter[2]);
                    break;
                case 'get_provinsi':
                    return self::get_provinsi();
                    break;
                case 'get_kabupaten':
                    return self::get_kabupaten($parameter[2]);
                    break;
                case 'get_kecamatan':
                    return self::get_kecamatan($parameter[2]);
                    break;
                case 'get_spesialistik':
                    return self::get_spesialistik();
                    break;
                case 'get_sep_select2':
                    return self::get_sep_select2($parameter);
                    break;
                case 'get_dpjp':
                    return self::get_dpjp($parameter);
                    break;
                case 'get_faskes_select2':
                    return self::get_faskes_select2($parameter);
                    break;
                case 'get_rujukan_list':
                    return self::get_rujukan_list($parameter[2]);
                    break;
                case 'get_kelas_rawat_select2':
                    return self::get_kelas_rawat_select2();
                    break;
                case 'get_sep_detail':
                    return self::get_sep_detail($parameter[2]);
                    break;
                case 'info_bpjs':
                    return self::info_bpjs($parameter[2]);
                    break;
                case 'get_sep_list':
                    return self::get_sep_list($parameter);
                    break;
				default:
					return 'Unknown request';
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function __POST__($parameter = array()) {
		try {
			switch($parameter['request']) {
				case 'cek_peserta':
					return self::cek_peserta($parameter);
					break;
                case 'sep_baru':
                    return self::sep_baru($parameter);
                    break;
                case 'sep_update':
                    return self::sep_update($parameter);
                    break;
                case 'sep':
                    return self::get_sep($parameter);
                    break;
                case 'get_sep_log':
                    return self::get_sep_log($parameter);
                    break;
                case 'get_sep_log_untrack':
                    return self::get_sep_log_untrack($parameter);
                    break;
                case 'get_history_sep_local':
                    return self::get_history_sep_local($parameter);
                    break;
                case 'hapus_sep':
                    return self::hapus_sep($parameter);
                    break;
                case 'rujukan_baru':
                    return self::rujukan_baru($parameter);
                    break;
				default:
					return 'Unknown request';
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

    public function info_bpjs($parameter) {
	    $Pasien = new Pasien(self::$pdo);
	    $data = $Pasien->get_pasien_detail('pasien', $parameter);
	    return $data;
    }

    private function get_poli_detail($parameter) {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/referensi/poli/' . $parameter);
        $data = array();
        foreach($content['content']['response']['poli'] as $key => $value) {
            if($value['kode'] == $parameter) {
                array_push($data, $value);
            }
        }
        return $data;
    }

    private function get_poli() {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/referensi/poli/' . $_GET['search']);
        return $content;
    }

	private function get_diagnosa() {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/referensi/diagnosa/' . $_GET['search']);
        return $content;
    }

    private function get_provinsi() {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/referensi/propinsi');
        return $content;
    }

    private function get_kabupaten($parameter) {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/referensi/kabupaten/propinsi/' . $parameter);
        return $content;
    }

    private function get_kecamatan($parameter) {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/referensi/kecamatan/kabupaten/' . $parameter);
        return $content;
    }

    private function get_spesialistik() {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/referensi/spesialistik');
        return $content;
    }

    private function get_dpjp($parameter) {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/referensi/dokter/pelayanan/' . $parameter[2] . '/tglPelayanan/' . date('Y-m-d') . '/Spesialis/' . $parameter[3]);
        return $content;
    }

    private function get_faskes_select2($parameter) {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/referensi/faskes/' . $_GET['search'] . '/' . $parameter[2]);
        return $content;
    }

    private function  get_faskes_info($parameter) {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/referensi/faskes/' . $parameter['kode'] . '/' . $parameter['type']);
        return $content;
    }

    private function get_kelas_rawat_select2() {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/referensi/kelasrawat');
        return $content;
    }

    private function get_sep($parameter) {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/SEP/' . $parameter['kartu']);
        return $content;
    }

    private function hapus_sep($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);
        $parameterBuilder = array('request' => array(
            't_sep' => array(
                'noSep' => $parameter[7],
                'user' => $UserData['data']->nama
            )
        ));

        $deleteAct = self::deleteUrl('/' . __BPJS_SERVICE_NAME__ . '/SEP/Delete', $parameterBuilder);
        if(intval($deleteAct['content']['metaData']['code']) === 200 || intval($deleteAct['content']['metaData']['code']) === 201) {
            //Update SEP
            $DeleteSEP = self::$query->delete('bpjs_sep')
                ->where(array(
                    'bpjs_sep.sep_no' => '= ?'
                ), array(
                    $parameter[7]
                ))
                ->execute();


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
                        'bpjs_sep',
                        'D',
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class'=>__CLASS__
                )
            );
        }

        return array(
            'bpjs' => $deleteAct,
            'delete_act' => $DeleteSEP
        );
    }

    private function get_local_sep($parameter) {
	    $data = self::$query->select('bpjs_sep', array(
	        'uid',
            'pelayanan_jenis',
            'kelas_rawat',
            'asal_rujukan_jenis',
            'asal_rujukan_tanggal',
            'asal_rujukan_nomor',
            'asal_rujukan_ppk',
            'catatan',
            'diagnosa_kode',
            'diagnosa_nama',
            'poli_tujuan',
            'poli_eksekutif',
            'pasien_cob',
            'pasien_katarak',
            'laka_lantas',
            'laka_lantas_penjamin',
            'laka_lantas_tanggal',
            'laka_lantas_keterangan',
            'laka_lantas_suplesi',
            'laka_lantas_suplesi_sep',
            'laka_lantas_provinsi',
            'laka_lantas_kabupaten',
            'laka_lantas_kecamatan',
            'skdp_no_surat',
            'skdp_dpjp',
            'no_telp',
            'pegawai',
            'sep_no',
            'sep_tanggal',
            'sep_dinsos',
            'sep_prolanis',
            'sep_sktm',
            'asal_rujukan_nama',
            'pasien',
            'antrian',
            'created_at',
            'updated_at',
            'deleted_at'
        ))
            ->where(array(
                'bpjs_sep.pasien' => '= ?',
                'AND',
                'bpjs_sep.sep_no' => 'ILIKE ' . '\'%' . $parameter['no_sep'] . '%\'',
                'AND',
                'bpjs_sep.deleted_at' => 'IS NULL'
            ), array(
                $parameter['pasien']
            ))
            ->execute();
	    return $data;
    }

    private function get_history_sep($parameter) {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/Monitoring/Kunjungan/Tanggal/' . $parameter['tanggal'] . '/JnsPelayanan/' . $parameter['jenis']);
        return $content;
    }

    private function get_history_pelayanan($parameter) {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/Monitoring/HistoriPelayanan/NoKartu/' . $parameter['kartu'] . '/tglAwal/' . $parameter['dari'] . '/tglAkhir/' . $parameter['sampai']);
        return $content;
    }

    private function get_sep_detail_dup ($parameter) {
        $data = self::$query->select('bpjs_sep', array(
            'uid',
            'pelayanan_jenis',
            'kelas_rawat',
            'asal_rujukan_jenis',
            'asal_rujukan_tanggal',
            'asal_rujukan_nomor',
            'asal_rujukan_ppk',
            'asal_rujukan_nama',
            'catatan',
            'pasien',
            'antrian',
            'diagnosa_kode',
            'diagnosa_nama',
            'poli_tujuan',
            'poli_eksekutif',
            'pasien_cob',
            'pasien_katarak',
            'laka_lantas',
            'laka_lantas_penjamin',
            'laka_lantas_tanggal',
            'laka_lantas_keterangan',
            'laka_lantas_suplesi',
            'laka_lantas_suplesi_sep',
            'laka_lantas_provinsi',
            'laka_lantas_kabupaten',
            'laka_lantas_kecamatan',
            'skdp_no_surat',
            'skdp_dpjp',
            'no_telp',
            'pegawai',
            'sep_no',
            'sep_tanggal',
            'sep_dinsos',
            'sep_prolanis',
            'sep_sktm',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'bpjs_sep.sep_no' => '= ?',
                'AND',
                'bpjs_sep.deleted_at' => 'IS NULL'
            ), array(
                $parameter
            ))
            ->execute();

        foreach ($data['response_data'] as $key => $value) {
            $Antrian = new Antrian(self::$pdo);

            $poli_detail = self::get_poli_detail($value['poli_tujuan']);
            $data['response_data'][$key]['poli_tujuan_detail'] = $poli_detail[0];

            $AntrianDetail = $Antrian->get_antrian_detail('antrian', $value['antrian']);
            $data['response_data'][$key]['antrian_detail'] = $AntrianDetail['response_data'][0];
        }

        return $data;
    }

    private function get_history_sep_local($parameter) {

        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);


        $begin = new DateTime($parameter['dari']);
        $end = new DateTime($parameter['sampai']);

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        $data_sync_record = array();
        foreach ($period as $dt) {
            $sync_sep = self::get_history_sep(array(
                'tanggal' => $dt->format("Y-m-d"),
                'jenis' => $parameter['pelayanan_jenis']
            ));

            $sync_content = $sync_sep['content'];

            if(intval($sync_content['metaData']['code']) === 200 && trim($sync_content['metaData']['message']) === 'Sukses') {
                $data_sync = $sync_content['response']['sep'];
                foreach ($data_sync as $dKey => $dValue) {
                    $SEPuid = parent::gen_uuid();
                    //Save History

                    //get pasien local info
                    $penjamin_data = self::$query->select('pasien_penjamin', array(
                        'pasien',
                        'penjamin',
                        'rest_meta'
                    ))
                        ->where(array(
                            'pasien_penjamin.deleted_at' => 'IS NULL'
                        ), array(
                            //
                        ))
                        ->execute();
                    $targetPasien = '';
                    foreach ($penjamin_data['response_data'] as $PJKey => $PJValue) {
                        $data_api_read = json_decode($PJValue['rest_meta'], true);

                        if($PJValue['penjamin'] === __UIDPENJAMINBPJS__) {
                            if($data_api_read['response']['peserta']['noKartu'] == $dValue['noKartu']) {
                                $targetPasien = $PJValue['pasien'];
                            }
                        }
                    }

                    //Check duplicate
                    $check = self::get_sep_detail_dup($dValue['noSep']);
                    if(count($check['response_data']) > 0) {
                        $sep_log = self::$query->update('bpjs_sep', array(
                            'uid' => $SEPuid,
                            'pasien' => $targetPasien,
                            'pelayanan_jenis' => $dValue['jnsPelayanan'],
                            'kelas_rawat' => $dValue['kelasRawat'],
                            'asal_rujukan_nomor' => $dValue['noRujukan'],
                            'diagnosa_kode' => $dValue['diagnosa'],
                            'poli_tujuan' => $dValue['poli'],
                            'pegawai' => $UserData['data']->uid,
                            'sep_no' => $dValue['noSep'],
                            'sep_tanggal' => $dValue['tglSep'],
                            'sep_selesai' => $dValue['tglPlgSep'],
                            'created_at' => parent::format_date(),
                            'updated_at' => parent::format_date()
                        ))
                            ->where(array(
                                'bpjs_sep.deleted_at' => 'IS NULL',
                                'AND',
                                'bpjs_sep.uid' => '= ?'
                            ), array(
                                $check['response_data'][0]['uid']
                            ))
                            ->execute();
                    } else {
                        if(isset($dValue['tglPlgSep'])) {
                            $sep_log = self::$query->insert('bpjs_sep', array(
                                'uid' => $SEPuid,
                                'pasien' => $targetPasien,
                                'pelayanan_jenis' => $dValue['jnsPelayanan'],
                                'kelas_rawat' => $dValue['kelasRawat'],
                                'asal_rujukan_nomor' => $dValue['noRujukan'],
                                'diagnosa_kode' => $dValue['diagnosa'],
                                'poli_tujuan' => $dValue['poli'],
                                'pegawai' => $UserData['data']->uid,
                                'sep_no' => $dValue['noSep'],
                                'sep_tanggal' => $dValue['tglSep'],
                                'sep_selesai' => $dValue['tglPlgSep'],
                                'created_at' => parent::format_date(),
                                'updated_at' => parent::format_date()
                            ))
                                ->execute();
                        } else {
                            $sep_log = self::$query->insert('bpjs_sep', array(
                                'uid' => $SEPuid,
                                'pasien' => $targetPasien,
                                'pelayanan_jenis' => $dValue['jnsPelayanan'],
                                'kelas_rawat' => $dValue['kelasRawat'],
                                'asal_rujukan_nomor' => $dValue['noRujukan'],
                                'diagnosa_kode' => $dValue['diagnosa'],
                                'poli_tujuan' => $dValue['poli'],
                                'pegawai' => $UserData['data']->uid,
                                'sep_no' => $dValue['noSep'],
                                'sep_tanggal' => $dValue['tglSep'],
                                'created_at' => parent::format_date(),
                                'updated_at' => parent::format_date()
                            ))
                                ->execute();
                        }
                    }
                    array_push($data_sync_record, $sep_log);
                }
            } else {
                if(isset($sync_content)) {
                    array_push($data_sync_record, $sync_content);
                }
            }
        }


        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'bpjs_sep.deleted_at' => 'IS NULL',
                'AND',
                'bpjs_sep.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'bpjs_sep.sep_no' => 'ILIKE ' . '\'%' . $parameter['no_sep'] . '%\''
            );

            $paramValue = array($parameter['dari'], $parameter['sampai']);
        } else {
            $paramData = array(
                'bpjs_sep.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'bpjs_sep.deleted_at' => 'IS NULL'
            );

            $paramValue = array($parameter['dari'], $parameter['sampai']);
        }

        if ($parameter['length'] < 0) {
            $data = self::$query->select('bpjs_sep', array(
                'uid',
                'pelayanan_jenis',
                'kelas_rawat',
                'asal_rujukan_jenis',
                'asal_rujukan_tanggal',
                'asal_rujukan_nomor',
                'asal_rujukan_ppk',
                'catatan',
                'diagnosa_kode',
                'diagnosa_nama',
                'poli_tujuan',
                'poli_eksekutif',
                'pasien_cob',
                'pasien_katarak',
                'laka_lantas',
                'laka_lantas_penjamin',
                'laka_lantas_tanggal',
                'laka_lantas_keterangan',
                'laka_lantas_suplesi',
                'laka_lantas_suplesi_sep',
                'laka_lantas_provinsi',
                'laka_lantas_kabupaten',
                'laka_lantas_kecamatan',
                'skdp_no_surat',
                'skdp_dpjp',
                'no_telp',
                'pegawai',
                'sep_no',
                'sep_tanggal',
                'sep_dinsos',
                'sep_prolanis',
                'sep_sktm',
                'asal_rujukan_nama',
                'pasien',
                'antrian',
                'created_at',
                'updated_at',
                'deleted_at'
            ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('bpjs_sep', array(
                'uid',
                'pelayanan_jenis',
                'kelas_rawat',
                'asal_rujukan_jenis',
                'asal_rujukan_tanggal',
                'asal_rujukan_nomor',
                'asal_rujukan_ppk',
                'catatan',
                'diagnosa_kode',
                'diagnosa_nama',
                'poli_tujuan',
                'poli_eksekutif',
                'pasien_cob',
                'pasien_katarak',
                'laka_lantas',
                'laka_lantas_penjamin',
                'laka_lantas_tanggal',
                'laka_lantas_keterangan',
                'laka_lantas_suplesi',
                'laka_lantas_suplesi_sep',
                'laka_lantas_provinsi',
                'laka_lantas_kabupaten',
                'laka_lantas_kecamatan',
                'skdp_no_surat',
                'skdp_dpjp',
                'no_telp',
                'pegawai',
                'sep_no',
                'sep_tanggal',
                'sep_dinsos',
                'sep_prolanis',
                'sep_sktm',
                'asal_rujukan_nama',
                'pasien',
                'antrian',
                'created_at',
                'updated_at',
                'deleted_at'
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
            $Pasien = new Pasien(self::$pdo);
            $data['response_data'][$key]['pasien'] = $Pasien->get_pasien_detail('pasien', $value['pasien'])['response_data'][0];
            $autonum++;
        }

        $data['sync_record'] = $data_sync_record;

        return $data;
    }

    private function get_sep_select2($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);
	    //Local SEP
        $data = self::get_local_sep(array(
            'pasien' => $parameter[2],
            'no_sep' => $parameter[3]
        ));

        if(count($data['response_data']) > 0) {
            //cross check remote server
            $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/SEP/' . $_GET['search']);
            if($content['metaData']['code'] === '200') {
                return $data;
            } else {
                return $content;
            }
        } else {
            //insert into local server
            $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/SEP/' . $_GET['search']);
            if($content['metaData']['code'] === '200') {
                $response_content = $content['response'];
                $catatan = $response_content['catatan'];
                $diagnosa = $response_content['diagnosa'];
                $jns_pelayanan = $response_content['jnsPelayanan'];
                $kelas_rawat = $response_content['kelasRawat'];
                $no_sep = $response_content['noSep'];
                $no_rujukan = $response_content['noRujukan'];
                $penjamin = $response_content['penjamin'];
                $poli = $response_content['poli'];
                $eksekutif = $response_content['poliEksekutif'];
                $tgL_sep = $response_content['tglSep'];
                $sep_log = self::$query->insert('bpjs_sep', array(
                    'uid' => parent::gen_uuid(),
                    'pelayanan_jenis' => $jns_pelayanan,
                    'kelas_rawat' => $kelas_rawat,
                    /*'asal_rujukan_jenis' => $asal_rujukan,
                    'asal_rujukan_tanggal' => $tanggal_rujukan,
                    'asal_rujukan_nomor' => $no_rujukan,
                    'asal_rujukan_ppk' => $ppk_rujukan,*/
                    'catatan' => $catatan,
                    'diagnosa_kode' => $diagnosa,
                    //'diagnosa_nama' => $parameter['diagnosa_kode'],
                    'poli_tujuan' => $poli,
                    'poli_eksekutif' => $eksekutif,
                    /*'pasien_cob' => $cob,
                    'pasien_katarak' => $katarak,
                    'laka_lantas' => $laka_lantas,
                    'laka_lantas_penjamin' => $penjamin_lokasi_laka,
                    'laka_lantas_tanggal' => $penjamin_tgl_kejadian,
                    'laka_lantas_keterangan' => $penjamin_keterangan,
                    'laka_lantas_suplesi' => $penjamin_suplesi_suplesi,
                    'laka_lantas_suplesi_sep' => $penjamin_suplesi_no_sep_suplesi,
                    'laka_lantas_provinsi' => $penjamin_lokasi_laka_provinsi,
                    'laka_lantas_kabupaten' => $penjamin_lokasi_laka_kabupaten,
                    'laka_lantas_kecamatan' => $penjamin_lokasi_laka_kecamatan,
                    'skdp_no_surat' => $skdp_no_surat,
                    'skdp_dpjp' => $skdp_kode_dpjp,
                    'no_telp' => $no_telp,*/
                    'pegawai' => $UserData['data']->uid,
                    'sep_no' => $no_sep,
                    'sep_tanggal' => $tgL_sep,
                    /*'sep_dinsos' => $proceed['content']['response']['sep']['informasi']['Dinsos'],
                    'sep_prolanis' => $proceed['content']['response']['sep']['informasi']['prolanisPRB'],
                    'sep_sktm' => $proceed['content']['response']['sep']['informasi']['noSKTM'],*/
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();

                return $content;
            } else {
                return $content;
            }
        }

    }

    private function get_rujukan_list($parameter) {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/Rujukan/List/Peserta/' . $parameter);
        foreach ($content['content']['response']['rujukan'] as $key => $value)
        {
            $selectedFaskes = 0;
            $selectedFaskesInfo = array();
            for($a = 1; $a <= 2; $a++)
            {
                $Faskes = self::get_faskes_info(array(
                    'type' => $a,
                    'kode' => $value['provPerujuk']['kode']
                ));

                if(count($Faskes['content']['response']['faskes']) > 0) {
                    $selectedFaskes = $a;
                    $selectedFaskesInfo = $Faskes['content']['response']['faskes'][0];
                    break;
                }
            }
            $content['content']['response']['rujukan'][$key]['provPerujuk']['jenis'] = $selectedFaskes;
            $content['content']['response']['rujukan'][$key]['provPerujuk']['info'] = $selectedFaskesInfo;
        }
        return $content;
    }

    private function get_rujukan($parameter) {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/Rujukan/' . $parameter);
        return $content;
    }

    private function sep_update($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);
        //Build Parameter
        $parameterBuilder = array(
            'request' => array(
                't_sep' => array(
                    'noSep' => $parameter['sep'],
                    'noKartu' => $parameter['no_kartu'],
                    'tglSep' => strval(date('Y-m-d')),
                    'ppkPelayanan' => $parameter['ppk_pelayanan'],
                    'jnsPelayanan' => '2',
                    'klsRawat' => $parameter['kelas_rawat'],
                    'noMR' => $parameter['no_mr'],
                    'rujukan' => array(
                        'asalRujukan' => $parameter['asal_rujukan'],
                        'tglRujukan' => $parameter['tgl_rujukan'],
                        'noRujukan' => $parameter['no_rujukan'],
                        'ppkRujukan' => $parameter['ppk_rujukan']
                    ),
                    'catatan' => $parameter['catatan'],
                    'diagAwal' => $parameter['diagnosa_awal'],
                    'poli' => array(
                        'tujuan' => $parameter['poli'],
                        'eksekutif' => $parameter['eksekutif']
                    ),
                    'cob' => array(
                        'cob' => $parameter['cob']
                    ),
                    'katarak' => array(
                        'katarak' => $parameter['katarak']
                    ),
                    'jaminan' => array(
                        'lakaLantas' => $parameter['laka_lantas'],
                        'penjamin' => array(
                            'penjamin' => $parameter['laka_lantas_penjamin'],
                            'tglKejadian' => $parameter['laka_lantas_tanggal_kejadian'],
                            'keterangan' => $parameter['laka_lantas_keterangan'],
                            'suplesi' => array(
                                'suplesi' => strval($parameter['laka_lantas_suplesi']),
                                'noSepSuplesi' => strval($parameter['laka_lantas_suplesi_nomor']),
                                'lokasiLaka' => array(
                                    'kdPropinsi' => strval($parameter['laka_lantas_suplesi_provinsi']),
                                    'kdKabupaten' => strval($parameter['laka_lantas_suplesi_kabupaten']),
                                    'kdKecamatan' => strval($parameter['laka_lantas_suplesi_kecamatan'])
                                )
                            )
                        )
                    ),
                    'skdp' => array(
                        'noSurat' => $parameter['skdp'],
                        'kodeDPJP' => $parameter['dpjp']
                    ),
                    'noTelp' => $parameter['telepon'],
                    'user' => $UserData['data']->nama
                )
            )
        );

        $proceed = self::putUrl('/' . __BPJS_SERVICE_NAME__ . '/SEP/1.1/Update', $parameterBuilder);

        if(intval($proceed['content']['metaData']['code']) === 200) {
            $sep_log = self::$query->update('bpjs_sep', array(
                'pelayanan_jenis' => '2',
                'kelas_rawat' => $parameter['kelas_rawat'],
                'asal_rujukan_jenis' => $parameter['asal_rujukan'],
                'asal_rujukan_tanggal' => $parameter['tgl_rujukan'],
                'asal_rujukan_nomor' => $parameter['no_rujukan'],
                'asal_rujukan_ppk' => $parameter['ppk_rujukan'],
                'catatan' => $parameter['catatan'],
                'diagnosa_kode' => $parameter['diagnosa_awal'],
                'diagnosa_nama' => $parameter['diagnosa_kode'],
                'poli_tujuan' => $parameter['poli'],
                'poli_eksekutif' => $parameter['eksekutif'],
                'pasien_cob' => $parameter['cob'],
                'pasien_katarak' => $parameter['katarak'],
                'laka_lantas' => $parameter['laka_lantas'],
                'laka_lantas_penjamin' => $parameter['laka_lantas_penjamin'],
                'laka_lantas_tanggal' => $parameter['laka_lantas_tanggal_kejadian'],
                'laka_lantas_keterangan' => $parameter['laka_lantas_keterangan'],
                'laka_lantas_suplesi' => $parameter['laka_lantas_suplesi'],
                'laka_lantas_suplesi_sep' => $parameter['laka_lantas_suplesi_nomor'],
                'laka_lantas_provinsi' => $parameter['laka_lantas_suplesi_provinsi'],
                'laka_lantas_kabupaten' => $parameter['laka_lantas_suplesi_kabupaten'],
                'laka_lantas_kecamatan' => $parameter['laka_lantas_suplesi_kecamatan'],
                'skdp_no_surat' => $parameter['skdp'],
                'skdp_dpjp' => $parameter['dpjp'],
                'no_telp' => $parameter['telepon'],
                'pegawai' => $UserData['data']->uid,
                'sep_no' => $proceed['content']['response']['sep']['noSep'],
                'sep_tanggal' => $proceed['content']['response']['sep']['tglSep'],
                'sep_dinsos' => $proceed['content']['response']['sep']['informasi']['Dinsos'],
                'sep_prolanis' => $proceed['content']['response']['sep']['informasi']['prolanisPRB'],
                'sep_sktm' => $proceed['content']['response']['sep']['informasi']['noSKTM'],
                'updated_at' => parent::format_date()
            ))
                ->where(array(
                    'bpjs_sep.uid' => '= ?',
                    'AND',
                    'bpjs_sep.deleted_at' => 'IS NULL'
                ), array(
                    $parameter['uid']
                ))
                ->execute();
        }
        return $proceed;
    }

    private function rujukan_baru($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $parameterBuilder = array(
            'request' => array(
                't_rujukan' => array(
                    'noSep' => $parameter['sep'],
                    'tglRujukan' => date('Y-m-d'),
                    'ppkDirujuk' => $parameter['tujuan'],
                    'jnsPelayanan' => $parameter['jenis_pelayanan'],
                    'catatan' => $parameter['catatan'],
                    'diagRujukan' => $parameter['diagnosa'],
                    'tipeRujukan' => $parameter['tipe'],
                    'poliRujukan' => $parameter['poli'],
                    'user' => $UserData['data']->nama
                )
            )
        );
        $proceed = self::postUrl('/' . __BPJS_SERVICE_NAME__ . '/Rujukan/insert', $parameterBuilder);
        $uid = parent::gen_uuid();
        if(intval($proceed['content']['metaData']['code']) === 200) {
            $uid = parent::gen_uuid();
            $rujukan_log = self::$query->insert('bpjs_rujukan', array(
                'uid' => $uid,
                'request_rujukan' => $parameter['rujukan'],
                'asal_rujukan_kode' => $proceed['content']['response']['rujukan']['AsalRujukan']['kode'],
                'asal_rujukan_nama' => $proceed['content']['response']['rujukan']['AsalRujukan']['nama'],
                'diagnosa_kode' => $proceed['content']['response']['rujukan']['diagnosa']['kode'],
                'diagnosa_nama' => $proceed['content']['response']['rujukan']['diagnosa']['nama'],
                'no_rujukan' => $proceed['content']['response']['rujukan']['noRujukan'],
                'poli_tujuan_kode' => $proceed['content']['response']['poliTujuan']['kode'],
                'poli_tujuan_nama' => $proceed['content']['response']['poliTujuan']['nama'],
                'tgl_rujukan' => $proceed['content']['response']['tglRujukan'],
                'tujuan_rujukan_kode' => $proceed['content']['response']['tujuanRujukan']['kode'],
                'tujuan_rujukan_nama' => $proceed['content']['response']['tujuanRujukan']['nama'],
                'catatan' => $parameter['catatan'],
                'sep' => $parameter['sep_uid'],
                'pegawai' => $UserData['data']->uid,
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();
        }

        return array(
            'bpjs' => $proceed,
            'log' => $rujukan_log
        );
    }

    private function sep_baru($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
	    //Build Parameter
        $parameterBuilder = array(
            'request' => array(
                't_sep' => array(
                    'noKartu' => $parameter['no_kartu'],
                    'tglSep' => strval(date('Y-m-d')),
                    'ppkPelayanan' => $parameter['ppk_pelayanan'],
                    'jnsPelayanan' => '2',
                    'klsRawat' => $parameter['kelas_rawat'],
                    'noMR' => $parameter['no_mr'],
                    'rujukan' => array(
                        'asalRujukan' => $parameter['asal_rujukan'],
                        'tglRujukan' => $parameter['tgl_rujukan'],
                        'noRujukan' => $parameter['no_rujukan'],
                        'ppkRujukan' => (isset($parameter['ppk_rujukan']) && !empty($parameter['ppk_rujukan']) && $parameter['ppk_rujukan'] != '') ? $parameter['ppk_rujukan'] : '00161001'
                    ),
                    'catatan' => $parameter['catatan'],
                    'diagAwal' => $parameter['diagnosa_awal'],
                    'poli' => array(
                        'tujuan' => $parameter['poli'],
                        'eksekutif' => $parameter['eksekutif']
                    ),
                    'cob' => array(
                        'cob' => $parameter['cob']
                    ),
                    'katarak' => array(
                        'katarak' => $parameter['katarak']
                    ),
                    'jaminan' => array(
                        'lakaLantas' => $parameter['laka_lantas'],
                        'penjamin' => array(
                            'penjamin' => $parameter['laka_lantas_penjamin'],
                            'tglKejadian' => $parameter['laka_lantas_tanggal_kejadian'],
                            'keterangan' => $parameter['laka_lantas_keterangan'],
                            'suplesi' => array(
                                'suplesi' => strval($parameter['laka_lantas_suplesi']),
                                'noSepSuplesi' => strval($parameter['laka_lantas_suplesi_nomor']),
                                'lokasiLaka' => array(
                                    'kdPropinsi' => strval($parameter['laka_lantas_suplesi_provinsi']),
                                    'kdKabupaten' => strval($parameter['laka_lantas_suplesi_kabupaten']),
                                    'kdKecamatan' => strval($parameter['laka_lantas_suplesi_kecamatan'])
                                )
                            )
                        )
                    ),
                    'skdp' => array(
                        'noSurat' => $parameter['skdp'],
                        'kodeDPJP' => $parameter['dpjp']
                    ),
                    'noTelp' => $parameter['telepon'],
                    'user' => $UserData['data']->nama
                )
            )
        );

	    $proceed = self::postUrl('/' . __BPJS_SERVICE_NAME__ . '/SEP/1.1/insert', $parameterBuilder);
        $uid = parent::gen_uuid();
	    if(intval($proceed['content']['metaData']['code']) === 200) {

            $sep_log = self::$query->insert('bpjs_sep', array(
                'uid' => $uid,
                'antrian' => $parameter['antrian'],
                'pelayanan_jenis' => '2',
                'kelas_rawat' => $parameter['kelas_rawat'],
                'asal_rujukan_jenis' => $parameter['asal_rujukan'],
                'asal_rujukan_tanggal' => $parameter['tgl_rujukan'],
                'asal_rujukan_nomor' => $parameter['no_rujukan'],
                'asal_rujukan_ppk' => $parameter['ppk_rujukan'],
                'catatan' => $parameter['catatan'],
                'diagnosa_kode' => $parameter['diagnosa_awal'],
                'diagnosa_nama' => $parameter['diagnosa_kode'],
                'poli_tujuan' => $parameter['poli'],
                'poli_eksekutif' => $parameter['eksekutif'],
                'pasien_cob' => $parameter['cob'],
                'pasien_katarak' => $parameter['katarak'],
                'laka_lantas' => $parameter['laka_lantas'],
                'laka_lantas_penjamin' => $parameter['laka_lantas_penjamin'],
                'laka_lantas_tanggal' => (isset($parameter['laka_lantas_tanggal_kejadian']) && !empty($parameter['laka_lantas_tanggal_kejadian'])) ? date('Y-m-d', strtotime($parameter['laka_lantas_tanggal_kejadian'])) : NULL,
                'laka_lantas_keterangan' => $parameter['laka_lantas_keterangan'],
                'laka_lantas_suplesi' => $parameter['laka_lantas_suplesi'],
                'laka_lantas_suplesi_sep' => $parameter['laka_lantas_suplesi_nomor'],
                'laka_lantas_provinsi' => $parameter['laka_lantas_suplesi_provinsi'],
                'laka_lantas_kabupaten' => $parameter['laka_lantas_suplesi_kabupaten'],
                'laka_lantas_kecamatan' => $parameter['laka_lantas_suplesi_kecamatan'],
                'skdp_no_surat' => $parameter['skdp'],
                'skdp_dpjp' => $parameter['dpjp'],
                'no_telp' => $parameter['telepon'],
                'pegawai' => $UserData['data']->uid,
                'sep_no' => $proceed['content']['response']['sep']['noSep'],
                'sep_tanggal' => $proceed['content']['response']['sep']['tglSep'],
                'sep_dinsos' => $proceed['content']['response']['sep']['informasi']['Dinsos'],
                'sep_prolanis' => $proceed['content']['response']['sep']['informasi']['prolanisPRB'],
                'sep_sktm' => $proceed['content']['response']['sep']['informasi']['noSKTM'],
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();
        }
	    return array(
	        'bpjs' => $proceed,
            'log' => $sep_log
        );
    }

    private function get_sep_log_untrack($parameter) {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/SEP/' . $parameter['search']['value']);
        $filteredData = array();
        //0066R0071120V000810
        if(intval($content['metaData']['code']) === 200) {
            array_push($filteredData, $content['response']);
        }

        return array(
            'response_data' => $filteredData,
            'response_draw' => 0,
            'recordsTotal' => count($filteredData),
            'recordsFiltered' => count($filteredData)
        );
    }

    private function get_sep_detail($parameter) {
        $data = self::$query->select('bpjs_sep', array(
            'uid',
            'pelayanan_jenis',
            'kelas_rawat',
            'asal_rujukan_jenis',
            'asal_rujukan_tanggal',
            'asal_rujukan_nomor',
            'asal_rujukan_ppk',
            'asal_rujukan_nama',
            'catatan',
            'pasien',
            'antrian',
            'diagnosa_kode',
            'diagnosa_nama',
            'poli_tujuan',
            'poli_eksekutif',
            'pasien_cob',
            'pasien_katarak',
            'laka_lantas',
            'laka_lantas_penjamin',
            'laka_lantas_tanggal',
            'laka_lantas_keterangan',
            'laka_lantas_suplesi',
            'laka_lantas_suplesi_sep',
            'laka_lantas_provinsi',
            'laka_lantas_kabupaten',
            'laka_lantas_kecamatan',
            'skdp_no_surat',
            'skdp_dpjp',
            'no_telp',
            'pegawai',
            'sep_no',
            'sep_tanggal',
            'sep_dinsos',
            'sep_prolanis',
            'sep_sktm',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'bpjs_sep.uid' => '= ?',
                'AND',
                'bpjs_sep.deleted_at' => 'IS NULL'
            ), array(
                $parameter
            ))
            ->execute();

        $Antrian = new Antrian(self::$pdo);
        $Pasien = new Pasien(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {


            $poli_detail = self::get_poli_detail($value['poli_tujuan']);
            $data['response_data'][$key]['poli_tujuan_detail'] = $poli_detail[0];

            $pasien = $Pasien->get_pasien_detail('pasien', $value['pasien']);
            foreach ($pasien['response_data'][0]['history_penjamin'] as $pKey => $pValue) {
                if($pasien['response_data'][0]['history_penjamin'][$pKey]['penjamin'] === __UIDPENJAMINBPJS__) {
                    $readData = preg_replace('/\\\\/', '', $pValue['penjamin_detail']['rest_meta']);
                    $pasien['response_data'][0]['history_penjamin'][$pKey]['penjamin_detail']['rest_meta_parse'] = json_decode($readData, true);
                }
            }
            $data['response_data'][$key]['pasien'] = $pasien['response_data'][0];

            $AntrianDetail = $Antrian->get_antrian_detail('antrian', $value['antrian']);
            $data['response_data'][$key]['antrian_detail'] = $AntrianDetail['response_data'][0];
        }

        return $data;
    }

    private function get_sep_list($parameter) {
        $data = self::$query->select('bpjs_sep', array(
            'uid',
            'pelayanan_jenis',
            'kelas_rawat',
            'asal_rujukan_jenis',
            'asal_rujukan_tanggal',
            'asal_rujukan_nomor',
            'asal_rujukan_ppk',
            'asal_rujukan_nama',
            'catatan',
            'pasien',
            'antrian',
            'diagnosa_kode',
            'diagnosa_nama',
            'poli_tujuan',
            'poli_eksekutif',
            'pasien_cob',
            'pasien_katarak',
            'laka_lantas',
            'laka_lantas_penjamin',
            'laka_lantas_tanggal',
            'laka_lantas_keterangan',
            'laka_lantas_suplesi',
            'laka_lantas_suplesi_sep',
            'laka_lantas_provinsi',
            'laka_lantas_kabupaten',
            'laka_lantas_kecamatan',
            'skdp_no_surat',
            'skdp_dpjp',
            'no_telp',
            'pegawai',
            'sep_no',
            'sep_tanggal',
            'sep_dinsos',
            'sep_prolanis',
            'sep_sktm',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'bpjs_sep.deleted_at' => 'IS NULL',
                'AND',
                'bpjs_sep.pasien' => '= ?'
            ), array(
                $parameter[2]
            ))
            ->execute();
        return $data;
    }

    private function get_sep_log($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);


        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'bpjs_sep.deleted_at' => 'IS NULL',
                'AND',
                'bpjs_sep.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'bpjs_sep.sep_no' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array(
                $parameter['from'], $parameter['to']
            );
        } else {
            $paramData = array(
                'bpjs_sep.deleted_at' => 'IS NULL',
                'AND',
                'bpjs_sep.created_at' => 'BETWEEN ? AND ?'
            );

            $paramValue = array(
                $parameter['from'], $parameter['to']
            );
        }

        if ($parameter['length'] < 0) {
            $data = self::$query->select('bpjs_sep', array(
                'uid',
                'pelayanan_jenis',
                'kelas_rawat',
                'asal_rujukan_jenis',
                'asal_rujukan_tanggal',
                'asal_rujukan_nomor',
                'asal_rujukan_ppk',
                'asal_rujukan_nama',
                'catatan',
                'pasien',
                'antrian',
                'diagnosa_kode',
                'diagnosa_nama',
                'poli_tujuan',
                'poli_eksekutif',
                'pasien_cob',
                'pasien_katarak',
                'laka_lantas',
                'laka_lantas_penjamin',
                'laka_lantas_tanggal',
                'laka_lantas_keterangan',
                'laka_lantas_suplesi',
                'laka_lantas_suplesi_sep',
                'laka_lantas_provinsi',
                'laka_lantas_kabupaten',
                'laka_lantas_kecamatan',
                'skdp_no_surat',
                'skdp_dpjp',
                'no_telp',
                'pegawai',
                'sep_no',
                'sep_tanggal',
                'sep_dinsos',
                'sep_prolanis',
                'sep_sktm',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {

            $data = self::$query->select('bpjs_sep', array(
                'uid',
                'pelayanan_jenis',
                'kelas_rawat',
                'asal_rujukan_jenis',
                'asal_rujukan_tanggal',
                'asal_rujukan_nomor',
                'asal_rujukan_ppk',
                'asal_rujukan_nama',
                'catatan',
                'pasien',
                'antrian',
                'diagnosa_kode',
                'diagnosa_nama',
                'poli_tujuan',
                'poli_eksekutif',
                'pasien_cob',
                'pasien_katarak',
                'laka_lantas',
                'laka_lantas_penjamin',
                'laka_lantas_tanggal',
                'laka_lantas_keterangan',
                'laka_lantas_suplesi',
                'laka_lantas_suplesi_sep',
                'laka_lantas_provinsi',
                'laka_lantas_kabupaten',
                'laka_lantas_kecamatan',
                'skdp_no_surat',
                'skdp_dpjp',
                'no_telp',
                'pegawai',
                'sep_no',
                'sep_tanggal',
                'sep_dinsos',
                'sep_prolanis',
                'sep_sktm',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = $parameter['start'] + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;

            $Pasien = new Pasien(self::$pdo);
            $PasienDetail = $Pasien::get_pasien_detail('pasien', $value['pasien']);
            $data['response_data'][$key]['pasien'] = $PasienDetail['response_data'][0];

            $autonum++;
        }

        $dataTotal = self::$query->select('bpjs_sep', array(
            'uid'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($dataTotal['response_data']);
        $data['recordsFiltered'] = count($dataTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;

    }

	private function get_faskes() {
		$hasil = array();
		for($a = 1; $a < 30; $a++) {
			curl_setopt(self::$ch, CURLOPT_URL, self::$base_url . '/' . __BPJS_SERVICE_NAME__ . '/referensi/spesialistik');
			$content = curl_exec(self::$ch);
			$err = curl_error(self::$ch);
			if (curl_errno(self::$ch)){
				$jlh = 1;
			}
			else{
				$jlh = 0;
			}
			

			$content = json_decode($content, TRUE);
			if(intval($content['metaData']['code']) != 201) {
				array_push($hasil, $content);
			}
		}
		return $hasil;
	}

	private function cek_peserta($parameter) {
		$no_bpjs = $parameter['no_bpjs'];
		$tglSEP = strval(date("Y-m-d"));

        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/Peserta/nokartu/' . $no_bpjs . '/tglSEP/' . $tglSEP . '');

		/*$content = json_decode($content, TRUE);*/
		$content['content']['response']['peserta']['tglLahir'] = date('d F Y', strtotime($content['content']['response']['peserta']['tglLahir']));
		$content['content']['response']['peserta']['tglCetakKartu'] = date('d F Y', strtotime($content['content']['response']['peserta']['tglCetakKartu']));
		return $content;
	}

    public function launchUrl($extended_url, $target_switch = 1) {
        $url = (($target_switch === 1) ? self::$base_url : __BASE_LIVE_BPJS_APLICARES__) . $extended_url;
        $data_api = (($target_switch === 1) ? self::$data_api : __DATA_API_LIVE_APLICARES__);
        $secretKey_api = (($target_switch === 1) ? self::$secretKey_api : __SECRET_KEY_LIVE_APLICARES_BPJS__);

        date_default_timezone_set('UTC');
        $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
        $signature = hash_hmac('sha256', $data_api ."&". $tStamp , $secretKey_api, true);
        $encodedSignature = base64_encode($signature);
        $headers = array(
            "X-cons-id: " . $data_api ." ",
            "X-timestamp: " .$tStamp ." ",
            "X-signature: " .$encodedSignature,
            "Content-Type: application/json; charset=utf-8"
        );


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');


        $content = curl_exec($ch);
        $err = curl_error($ch);

        $result = json_decode($content, true);
        $return_value = array("content"=>$result, "error"=>$err);

        return $return_value;
    }

    public function postUrl($extended_url, $parameter) {
        $url = self::$base_url . $extended_url;

        date_default_timezone_set('UTC');

        $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
        $signature = hash_hmac('sha256', self::$data_api ."&". $tStamp , self::$secretKey_api, true);
        $encodedSignature = base64_encode($signature);
        $headers = array(
            'X-cons-id: ' . self::$data_api . ' ',
            'X-timestamp: ' . $tStamp . ' ',
            'X-signature: ' .$encodedSignature,
            'Content-Type: Application/x-www-form-urlencoded',
            'Accept: Application/JSON'
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameter));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');


        $content = curl_exec($ch);
        $err = curl_error($ch);

        $result = json_decode($content, true);
        $return_value = array("content"=>$result, "error"=>$err);

        return $return_value;
    }


    public function deleteUrl($extended_url, $parameter) {
        $url = self::$base_url . $extended_url;

        date_default_timezone_set('UTC');

        $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
        $signature = hash_hmac('sha256', self::$data_api ."&". $tStamp , self::$secretKey_api, true);
        $encodedSignature = base64_encode($signature);
        $headers = array(
            'X-cons-id: ' . self::$data_api . ' ',
            'X-timestamp: ' . $tStamp . ' ',
            'X-signature: ' .$encodedSignature,
            'Content-Type: Application/x-www-form-urlencoded',
            'Accept: Application/JSON'
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameter));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');


        $content = curl_exec($ch);
        $err = curl_error($ch);

        $result = json_decode($content, true);
        $return_value = array("content"=>$result, "error"=>$err);

        return $return_value;
    }

    public function putUrl($extended_url, $parameter) {
        $url = self::$base_url . $extended_url;

        date_default_timezone_set('UTC');

        $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
        $signature = hash_hmac('sha256', self::$data_api ."&". $tStamp , self::$secretKey_api, true);
        $encodedSignature = base64_encode($signature);
        $headers = array(
            'X-cons-id: ' . self::$data_api . ' ',
            'X-timestamp: ' . $tStamp . ' ',
            'X-signature: ' .$encodedSignature,
            'Content-Type: Application/x-www-form-urlencoded',
            'Accept: Application/JSON'
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameter));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');


        $content = curl_exec($ch);
        $err = curl_error($ch);

        $result = json_decode($content, true);
        $return_value = array("content"=>$result, "error"=>$err);

        return $return_value;
    }
}
?>