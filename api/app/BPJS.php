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
use \LZCompressor\LZString;


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
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        switch ($parameter[6]) {
            case 'SEP':
                return self::hapus_sep($parameter);
                break;
            case 'SPRI':
                return self::hapus_spri($parameter);
                break;
            default:
                return $parameter;
                break;
        }
    }

	public function __GET__($parameter = array()) {
		try {
			switch($parameter[1]) {
                case 'get_sep_pasien':
                    return self::get_sep_pasien($parameter);
                    break;
                case 'get_ruang_rawat':
                    return self::get_ruang_rawat($parameter);
                    break;
				case 'get_faskes':
					return self::get_faskes();
					break;
                case 'get_diagnosa':
                    return self::get_diagnosa();
					break;
                case 'get_procedure':
                    return self::get_procedure();
                    break;
                case 'get_poli':
                    return self::get_poli();
                    break;
                case 'get_poli_detail':
                    return self::get_poli_detail($parameter[2]);
                    break;
                case 'get_prb_program':
                    return self::get_prb_program();
                    break;
                case 'get_prb_generic':
                    return self::get_prb_generic();
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
                case 'get_cara_keluar_select2':
                    return self::get_cara_keluar_select2();
                    break;
                case 'get_kondisi_pulang_select2':
                    return self::get_kondisi_pulang_select2();
                    break;
                case 'get_sep_select2':
                    return self::get_sep_select2($parameter);
                    break;
                case 'get_dpjp':
                    return self::get_dpjp($parameter);
                    break;
                case 'get_dpjp_claim':
                    return self::get_dpjp_claim($parameter);
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
                case 'get_detail_spri':
                    return self::get_detail_spri($parameter[2]);
                    break;
                case 'get_referensi_cara_keluar':
                    $parameter = array(
                        'search' => array(
                            'value' => $_GET['search']
                        ),
                        'start' => 0,
                        'length' => 100,
                        'draw' => 1
                    );
                    return self::get_referensi_cara_keluar($parameter);
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
                case 'sep_pengajuan':
                    return self::sep_pengajuan($parameter);
                    break;
                case 'sep_baru':
                    return self::sep_baru($parameter);
                    break;
                case 'sep_edit':
                    return self::sep_edit($parameter);
                    break;
                case 'sep_update':
                    return self::sep_update($parameter);
                    break;
                case 'sep':
                    return self::get_sep($parameter);
                    break;
                case 'get_sep_internal':
                    return self::get_sep_internal($parameter);
                case 'get_sep_log':
                    return self::get_sep_log($parameter);
                    break;
                case 'get_sep_log_untrack':
                    return self::get_sep_log_untrack($parameter);
                    break;
                case 'get_history_sep_local':
                    return self::get_history_sep_local($parameter);
                    break;
                case 'get_history_sep':
                    return self::get_history_sep($parameter);
                    break;
                case 'hapus_sep':
                    return self::hapus_sep($parameter);
                    break;
                case 'rujukan_baru_v2':
                    return self::rujukan_baru_v2($parameter);
                    break;
                case 'rujukan_baru':
                    return self::rujukan_baru($parameter);
                    break;
                case 'rujukan_edit':
                    return self::rujukan_edit($parameter);
                    break;
                case 'tambah_claim':
                    return self::tambah_claim($parameter);
                    break;


                case 'cari_rujukan':
                    return self::cari_rujukan($parameter);
                    break;

                case 'prb_baru':
                    return self::prb_baru($parameter);
                    break;

                case 'rencana_kontrol_baru':
                    return self::rencana_kontrol_baru($parameter);
                    break;

                case 'spri_baru':
                    return self::spri_baru($parameter);
                    break;

                case 'spri_edit':
                    return self::spri_edit($parameter);
                    break;
                    

                case 'get_history_spri_local':
                    return self::get_history_spri_local($parameter);
                    break;



                case 'get_referensi_diagnosa':
                    return self::get_referensi_diagnosa($parameter);
                    break;
                case 'get_referensi_poli':
                    return self::get_referensi_poli($parameter);
                    break;
                case 'get_referensi_faskes':
                    return self::get_referensi_faskes($parameter);
                    break;
                case 'get_referensi_dpjp':
                    return self::get_referensi_dpjp($parameter);
                    break;
                case 'get_referensi_provinsi':
                    return self::get_referensi_provinsi($parameter);
                    break;
                case 'get_referensi_kabupaten':
                    return self::get_referensi_kabupaten($parameter);
                    break;
                case 'get_referensi_kecamatan':
                    return self::get_referensi_kecamatan($parameter);
                    break;
                case 'get_referensi_procedure':
                    return self::get_referensi_procedure($parameter);
                    break;
                case 'get_referensi_kelas_rawat':
                    return self::get_referensi_kelas_rawat($parameter);
                    break;
                case 'get_referensi_dokter':
                    return self::get_referensi_dokter($parameter);
                    break;
                case 'get_referensi_spesialistik':
                    return self::get_referensi_spesialistik($parameter);
                    break;
                case 'get_referensi_ruang_rawat':
                    return self::get_referensi_ruang_rawat($parameter);
                    break;
                case 'get_referensi_cara_keluar':
                    return self::get_referensi_cara_keluar($parameter);
                    break;
                case 'get_referensi_pasca_pulang':
                    return self::get_referensi_pasca_pulang($parameter);
                    break;
                case 'get_rencana_kontrol_all':
                    return self::get_rencana_kontrol_all($parameter);
                    break;
                case 'tambah_rencana_kontrol':
                    return self::tambah_rencana_kontrol($parameter);
                    break;
				default:
					return 'Unknown request';
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

    private function tambah_rencana_kontrol($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $parameterBuilder = array(
            'request' => array(
                'noSEP' => $parameter['no_sep'],
                'kodeDokter' => $parameter['kode_dokter'],
                'poliKontrol' => $parameter['poli_kontrol'],
                'tglRencanaKontrol' => date('Y-m-d', strtotime($parameter['tanggal_kontrol'])),
                'user' => $UserData['data']->nama
            )
        );

        $proceed = self::postUrl('/' . __BPJS_SERVICE_NAME__ . '/RencanaKontrol/insert', $parameterBuilder);
        return $proceed;

    }

    private function get_rencana_kontrol_all($parameter) {
        //
    }

	private static function get_referensi_diagnosa($parameter) {
        $cari = (isset($parameter['search']['value'])) ? $parameter['search']['value'] : $parameter['cari'];
        $content = self::getUrl2('/' . ((__BPJS_MODE__ === 2) ? __BPJS_SERVICE_NAME_LIVE__ :__BPJS_SERVICE_NAME_DEV__) . '/referensi/diagnosa/' . $cari);
        
        
        if(intval($content['metaData']['code']) === 200) {
            
            $data = $content['data']['diagnosa'];
            $autonum = 1;
            foreach ($data as $key => $value) {
                $data[$key]['autonum'] = $autonum;
                $autonum++;
            }

            $prepare = array(
                'data' => array_slice($data, intval($parameter['start']), intval($parameter['length'])),
                'recordsTotal' => count($data),
                'recordsFiltered' => count($data),
                'length' => intval($parameter['length']),
                'start' => intval($parameter['start']),
                'response_draw' => $parameter['draw']
            );

            return $prepare;
        } else {
            return array(
                'data' => array(),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'length' => 0,
                'start' => 0,
                'response_draw' => $parameter['draw']
            );
        }
    }

    private function get_referensi_poli($parameter) {
        $cari = (isset($parameter['search']['value'])) ? $parameter['search']['value'] : $parameter['cari'];
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/poli/' . $cari);
        if(intval($content['metaData']['code']) === 200) {
            $data = $content['data']['poli'];

            $autonum = 1;
            foreach ($data as $key => $value) {
                $data[$key]['autonum'] = $autonum;
                $autonum++;
            }

            $prepare = array(
                'data' => array_slice($data, intval($parameter['start']), intval($parameter['length'])),
                'recordsTotal' => count($data),
                'recordsFiltered' => count($data),
                'length' => intval($parameter['length']),
                'start' => intval($parameter['start']),
                'response_draw' => $parameter['draw']
            );

            return $prepare;


        } else {
            return array(
                'data' => array(),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'length' => 0,
                'start' => 0,
                'response_draw' => $parameter['draw']
            );
        }
    }




    private function get_referensi_faskes($parameter) {
        $cari = (isset($parameter['search']['value'])) ? $parameter['search']['value'] : $parameter['cari'];
        if(!empty($cari)) {
            $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/faskes/' . $cari . '/' . $parameter['jenis']);
            if(intval($content['metaData']['code']) === 200) {
                $data = $content['data']['faskes'];

                $autonum = 1;
                foreach ($data as $key => $value) {
                    $data[$key]['autonum'] = $autonum;
                    $autonum++;
                }

                $prepare = array(
                    'data' => array_slice($data, intval($parameter['start']), intval($parameter['length'])),
                    'recordsTotal' => count($data),
                    'recordsFiltered' => count($data),
                    'length' => intval($parameter['length']),
                    'start' => intval($parameter['start']),
                    'response_draw' => $parameter['draw']
                );

                return $prepare;


            } else {
                return array(
                    'data' => array(),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'length' => 0,
                    'start' => 0,
                    'response_draw' => $parameter['draw']
                );
            }
        }
    }






    private function get_referensi_dpjp($parameter) {

        $begin = new DateTime($parameter['from']);
        $end = new DateTime($parameter['to']);

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        $data_record = array();
        foreach ($period as $dt) {
            $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/dokter/pelayanan/' . $parameter['jenis'] . '/tglPelayanan/' . ($dt->format("Y-m-d")) .  '/Spesialis/' . $parameter['search']['value']);
            if(intval($content['metaData']['code']) === 200) {
                $data = $content['data']['list'];

                $autonum = 1;
                foreach ($data as $key => $value) {
                    $data[$key]['autonum'] = $autonum;
                    $autonum++;

                    array_push($data_record, $data[$key]);
                }
            }
        }


        $prepare = array(
            'data' => array_slice($data_record, intval($parameter['start']), intval($parameter['length'])),
            'recordsTotal' => count($data_record),
            'recordsFiltered' => count($data_record),
            'length' => intval($parameter['length']),
            'start' => intval($parameter['start']),
            'response_draw' => $parameter['draw']
        );

        return $prepare;
    }




    private function get_referensi_provinsi($parameter) {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/propinsi');
        if(intval($content['metaData']['code']) === 200) {
            $data = $content['data']['list'];

            $autonum = 1;
            foreach ($data as $key => $value) {
                $data[$key]['autonum'] = $autonum;
                $autonum++;
            }


            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                //$data_all = array_slice($data, intval($parameter['start']), intval($parameter['length']));
                $data_all = $data;

                $filter = array();

                foreach ($data_all as $key => $value) {

                    $checker = stripos($value['nama'],$parameter['search']['value']);
                    if($checker >= 0 && $checker !== false) {
                        array_push($filter, $data_all[$key]);
                    }
                }

                $prepare = array(
                    'data' => array_slice($filter, intval($parameter['start']), intval($parameter['length'])),
                    'recordsTotal' => count($filter),
                    'recordsFiltered' => count($filter),
                    'length' => intval($parameter['length']),
                    'start' => intval($parameter['start']),
                    'response_draw' => $parameter['draw']
                );
            } else {
                $prepare = array(
                    'data' => array_slice($data, intval($parameter['start']), intval($parameter['length'])),
                    'recordsTotal' => count($data),
                    'recordsFiltered' => count($data),
                    'length' => intval($parameter['length']),
                    'start' => intval($parameter['start']),
                    'response_draw' => $parameter['draw']
                );
            }

            return $prepare;


        } else {
            return array(
                'data' => array(),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'length' => 0,
                'start' => 0,
                'response_draw' => $parameter['draw']
            );
        }
    }



    private function get_referensi_kabupaten($parameter) {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/kabupaten/propinsi/' . $parameter['propinsi']);
        if(intval($content['metaData']['code']) === 200) {
            $data = $content['data']['list'];

            $autonum = 1;
            foreach ($data as $key => $value) {
                $data[$key]['autonum'] = $autonum;
                $autonum++;
            }

            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                //$data_all = array_slice($data, intval($parameter['start']), intval($parameter['length']));
                $data_all = $data;

                $filter = array();

                foreach ($data_all as $key => $value) {

                    $checker = stripos($value['nama'],$parameter['search']['value']);
                    if($checker >= 0 && $checker !== false) {
                        array_push($filter, $data_all[$key]);
                    }
                }

                $prepare = array(
                    'data' => array_slice($filter, intval($parameter['start']), intval($parameter['length'])),
                    'recordsTotal' => count($filter),
                    'recordsFiltered' => count($filter),
                    'length' => intval($parameter['length']),
                    'start' => intval($parameter['start']),
                    'response_draw' => $parameter['draw']
                );
            } else {
                $prepare = array(
                    'data' => array_slice($data, intval($parameter['start']), intval($parameter['length'])),
                    'recordsTotal' => count($data),
                    'recordsFiltered' => count($data),
                    'length' => intval($parameter['length']),
                    'start' => intval($parameter['start']),
                    'response_draw' => $parameter['draw']
                );
            }

            return $prepare;


        } else {
            return array(
                'data' => array(),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'length' => 0,
                'start' => 0,
                'response_draw' => $parameter['draw']
            );
        }
    }



    private function get_referensi_kecamatan($parameter) {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/kecamatan/kabupaten/' . $parameter['kabupaten']);
        if(intval($content['metaData']['code']) === 200) {
            $data = $content['data']['list'];

            $autonum = 1;
            foreach ($data as $key => $value) {
                $data[$key]['autonum'] = $autonum;
                $autonum++;
            }

            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                //$data_all = array_slice($data, intval($parameter['start']), intval($parameter['length']));
                $data_all = $data;

                $filter = array();

                foreach ($data_all as $key => $value) {

                    $checker = stripos($value['nama'],$parameter['search']['value']);
                    if($checker >= 0 && $checker !== false) {
                        array_push($filter, $data_all[$key]);
                    }
                }

                $prepare = array(
                    'data' => array_slice($filter, intval($parameter['start']), intval($parameter['length'])),
                    'recordsTotal' => count($filter),
                    'recordsFiltered' => count($filter),
                    'length' => intval($parameter['length']),
                    'start' => intval($parameter['start']),
                    'response_draw' => $parameter['draw']
                );
            } else {
                $prepare = array(
                    'data' => array_slice($data, intval($parameter['start']), intval($parameter['length'])),
                    'recordsTotal' => count($data),
                    'recordsFiltered' => count($data),
                    'length' => intval($parameter['length']),
                    'start' => intval($parameter['start']),
                    'response_draw' => $parameter['draw']
                );
            }

            return $prepare;


        } else {
            return array(
                'data' => array(),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'length' => 0,
                'start' => 0,
                'response_draw' => $parameter['draw']
            );
        }
    }


    private function get_referensi_procedure($parameter) {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/procedure/' . $parameter['search']['value']);
        if(intval($content['metaData']['code']) === 200) {
            $data = $content['data']['procedure'];

            $autonum = 1;
            foreach ($data as $key => $value) {
                $data[$key]['autonum'] = $autonum;
                $autonum++;
            }

            $prepare = array(
                'data' => array_slice($data, intval($parameter['start']), intval($parameter['length'])),
                'recordsTotal' => count($data),
                'recordsFiltered' => count($data),
                'length' => intval($parameter['length']),
                'start' => intval($parameter['start']),
                'response_draw' => $parameter['draw']
            );

            return $prepare;


        } else {
            return array(
                'data' => array(),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'length' => 0,
                'start' => 0,
                'response_draw' => $parameter['draw'],
                'content' => $content
            );
        }
    }



    private function get_referensi_kelas_rawat($parameter) {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/kelasrawat');
        if(intval($content['metaData']['code']) === 200) {
            $data = $content['data']['list'];

            $autonum = 1;
            foreach ($data as $key => $value) {
                $data[$key]['autonum'] = $autonum;
                $autonum++;
            }


            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                //$data_all = array_slice($data, intval($parameter['start']), intval($parameter['length']));
                $data_all = $data;

                $filter = array();

                foreach ($data_all as $key => $value) {

                    $checker = stripos($value['nama'],$parameter['search']['value']);
                    if($checker >= 0 && $checker !== false) {
                        array_push($filter, $data_all[$key]);
                    }
                }

                $prepare = array(
                    'data' => array_slice($filter, intval($parameter['start']), intval($parameter['length'])),
                    'recordsTotal' => count($filter),
                    'recordsFiltered' => count($filter),
                    'length' => intval($parameter['length']),
                    'start' => intval($parameter['start']),
                    'response_draw' => $parameter['draw']
                );
            } else {
                $prepare = array(
                    'data' => array_slice($data, intval($parameter['start']), intval($parameter['length'])),
                    'recordsTotal' => count($data),
                    'recordsFiltered' => count($data),
                    'length' => intval($parameter['length']),
                    'start' => intval($parameter['start']),
                    'response_draw' => $parameter['draw']
                );
            }

            return $prepare;


        } else {
            return array(
                'data' => array(),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'length' => 0,
                'start' => 0,
                'response_draw' => $parameter['draw']
            );
        }
    }



    private function get_referensi_dokter($parameter) {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/dokter/' . $parameter['search']['value']);
        if(intval($content['metaData']['code']) === 200) {
            $data = $content['data']['list'];

            $autonum = 1;
            foreach ($data as $key => $value) {
                $data[$key]['autonum'] = $autonum;
                $autonum++;
            }

            $prepare = array(
                'data' => array_slice($data, intval($parameter['start']), intval($parameter['length'])),
                'recordsTotal' => count($data),
                'recordsFiltered' => count($data),
                'length' => intval($parameter['length']),
                'start' => intval($parameter['start']),
                'response_draw' => $parameter['draw']
            );

            return $prepare;


        } else {
            return array(
                'data' => array(),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'length' => 0,
                'start' => 0,
                'response_draw' => $parameter['draw'],
                'content' => $content
            );
        }
    }




    private function get_referensi_spesialistik($parameter) {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/spesialistik');
        if(intval($content['metaData']['code']) === 200) {
            $data = $content['data']['list'];

            $autonum = 1;
            foreach ($data as $key => $value) {
                $data[$key]['autonum'] = $autonum;
                $autonum++;
            }


            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                //$data_all = array_slice($data, intval($parameter['start']), intval($parameter['length']));
                $data_all = $data;

                $filter = array();

                foreach ($data_all as $key => $value) {

                    $checker = stripos($value['nama'],$parameter['search']['value']);
                    if($checker >= 0 && $checker !== false) {
                        array_push($filter, $data_all[$key]);
                    }
                }

                $prepare = array(
                    'data' => array_slice($filter, intval($parameter['start']), intval($parameter['length'])),
                    'recordsTotal' => count($filter),
                    'recordsFiltered' => count($filter),
                    'length' => intval($parameter['length']),
                    'start' => intval($parameter['start']),
                    'response_draw' => $parameter['draw']
                );
            } else {
                $prepare = array(
                    'data' => array_slice($data, intval($parameter['start']), intval($parameter['length'])),
                    'recordsTotal' => count($data),
                    'recordsFiltered' => count($data),
                    'length' => intval($parameter['length']),
                    'start' => intval($parameter['start']),
                    'response_draw' => $parameter['draw']
                );
            }

            return $prepare;


        } else {
            return array(
                'data' => array(),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'length' => 0,
                'start' => 0,
                'response_draw' => $parameter['draw']
            );
        }
    }




    private function get_referensi_ruang_rawat($parameter) {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/ruangrawat');
        if(intval($content['metaData']['code']) === 200) {
            $data = $content['data']['list'];

            $autonum = 1;
            foreach ($data as $key => $value) {
                $data[$key]['autonum'] = $autonum;
                $autonum++;
            }


            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                //$data_all = array_slice($data, intval($parameter['start']), intval($parameter['length']));
                $data_all = $data;

                $filter = array();

                foreach ($data_all as $key => $value) {

                    $checker = stripos($value['nama'],$parameter['search']['value']);
                    if($checker >= 0 && $checker !== false) {
                        array_push($filter, $data_all[$key]);
                    }
                }

                $prepare = array(
                    'data' => array_slice($filter, intval($parameter['start']), intval($parameter['length'])),
                    'recordsTotal' => count($filter),
                    'recordsFiltered' => count($filter),
                    'length' => intval($parameter['length']),
                    'start' => intval($parameter['start']),
                    'response_draw' => $parameter['draw']
                );
            } else {
                $prepare = array(
                    'data' => array_slice($data, intval($parameter['start']), intval($parameter['length'])),
                    'recordsTotal' => count($data),
                    'recordsFiltered' => count($data),
                    'length' => intval($parameter['length']),
                    'start' => intval($parameter['start']),
                    'response_draw' => $parameter['draw']
                );
            }

            return $prepare;


        } else {
            return array(
                'data' => array(),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'length' => 0,
                'start' => 0,
                'response_draw' => $parameter['draw'],
                'content' => $content
            );
        }
    }





    private function get_referensi_cara_keluar($parameter) {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/carakeluar');
        if(intval($content['metaData']['code']) === 200) {
            $data = $content['data']['list'];

            $autonum = 1;
            foreach ($data as $key => $value) {
                $data[$key]['autonum'] = $autonum;
                $autonum++;
            }


            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                //$data_all = array_slice($data, intval($parameter['start']), intval($parameter['length']));
                $data_all = $data;

                $filter = array();

                foreach ($data_all as $key => $value) {

                    $checker = stripos($value['nama'],$parameter['search']['value']);
                    if($checker >= 0 && $checker !== false) {
                        array_push($filter, $data_all[$key]);
                    }
                }

                $prepare = array(
                    'data' => array_slice($filter, intval($parameter['start']), intval($parameter['length'])),
                    'recordsTotal' => count($filter),
                    'recordsFiltered' => count($filter),
                    'length' => intval($parameter['length']),
                    'start' => intval($parameter['start']),
                    'response_draw' => $parameter['draw']
                );
            } else {
                $prepare = array(
                    'data' => array_slice($data, intval($parameter['start']), intval($parameter['length'])),
                    'recordsTotal' => count($data),
                    'recordsFiltered' => count($data),
                    'length' => intval($parameter['length']),
                    'start' => intval($parameter['start']),
                    'response_draw' => $parameter['draw']
                );
            }

            return $prepare;


        } else {
            return array(
                'data' => array(),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'length' => 0,
                'start' => 0,
                'response_draw' => $parameter['draw'],
                'content' => $content
            );
        }
    }



    private function get_referensi_pasca_pulang($parameter) {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/pascapulang');
        if(intval($content['metaData']['code']) === 200) {
            $data = $content['data']['list'];

            $autonum = 1;
            foreach ($data as $key => $value) {
                $data[$key]['autonum'] = $autonum;
                $autonum++;
            }


            if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
                //$data_all = array_slice($data, intval($parameter['start']), intval($parameter['length']));
                $data_all = $data;

                $filter = array();

                foreach ($data_all as $key => $value) {

                    $checker = stripos($value['nama'],$parameter['search']['value']);
                    if($checker >= 0 && $checker !== false) {
                        array_push($filter, $data_all[$key]);
                    }
                }

                $prepare = array(
                    'data' => array_slice($filter, intval($parameter['start']), intval($parameter['length'])),
                    'recordsTotal' => count($filter),
                    'recordsFiltered' => count($filter),
                    'length' => intval($parameter['length']),
                    'start' => intval($parameter['start']),
                    'response_draw' => $parameter['draw']
                );
            } else {
                $prepare = array(
                    'data' => array_slice($data, intval($parameter['start']), intval($parameter['length'])),
                    'recordsTotal' => count($data),
                    'recordsFiltered' => count($data),
                    'length' => intval($parameter['length']),
                    'start' => intval($parameter['start']),
                    'response_draw' => $parameter['draw']
                );
            }

            return $prepare;


        } else {
            return array(
                'data' => array(),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'length' => 0,
                'start' => 0,
                'response_draw' => $parameter['draw'],
                'content' => $content
            );
        }
    }






































    private function get_sep_pasien($parameter) {
        $data = self::$query->select('bpjs_sep', array(
            'uid',
            'sep_no',
            'poli_tujuan',
            'created_at'
        ))
            ->where(array(
                'bpjs_sep.pasien' => '= ?'
            ), array(
                $parameter[2]
            ))
            ->order(array(
                'created_at' => 'DESC'
            ))
            ->execute();

        foreach($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['tanggal_sep'] = date('Y-m-d', strtotime($value['created_at']));
        }
        return $data;
    }


	private function get_ruang_rawat($parameter) {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/referensi/ruangrawat');
        $data = array();
        if(intval($content['content']['metaData']['code']) === 200) {
            foreach($content['content']['response']['list'] as $key => $value) {
                array_push($data, $value);
            }
            return $data;
        } else {
            return $content;
        }
    }

    public function info_bpjs($parameter) {
	    $Pasien = new Pasien(self::$pdo);
	    $data = $Pasien->get_pasien_detail('pasien', $parameter);
	    return $data;
    }

    private function get_poli_detail($parameter) {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/poli/' . $parameter);
        $data = array();
        foreach($content['data']['poli'] as $key => $value) {
            if($value['kode'] == $parameter) {
                array_push($data, $value);
            }
        }
        return $data;
    }

    private function get_poli() {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/poli/' . $_GET['search']);
        return $content;
    }

	private function get_diagnosa() {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/diagnosa/' . $_GET['search']);
        return $content;
    }

    private function get_procedure() {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/referensi/procedure/' . $_GET['search']);
        return $content;
    }

    private function get_prb_generic() {
        if(!empty($_GET['search'])) {
            $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/obatprb/' . $_GET['search']);
            return $content;    
        }
    }

    private function get_prb_program() {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/diagnosaprb');
        return $content;
    }

    private function get_provinsi() {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/propinsi');
        return $content;
    }

    private function get_kabupaten($parameter) {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/kabupaten/propinsi/' . $parameter);
        return $content;
    }

    private function get_kecamatan($parameter) {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/kecamatan/kabupaten/' . $parameter);
        return $content;
    }

    private function get_spesialistik() {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/spesialistik');
        return $content;
    }

    private function get_cara_keluar_select2() {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/referensi/carakeluar');
        return $content;
    }

    private function get_kondisi_pulang_select2() {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/referensi/pascapulang/');
        return $content;
    }

    private function get_dpjp($parameter) {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/dokter/pelayanan/' . $_GET['jenis'] . '/tglPelayanan/' . ((isset($_GET['tanggal'])) ? $_GET['tanggal'] : date('Y-m-d')) . '/Spesialis/' . $_GET['spesialistik']);
        return $content;
    }

    private function get_dpjp_claim($parameter) {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/referensi/dokter/' . $_GET['search']);
        return $content;
    }

    private function get_faskes_select2($parameter) {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/faskes/' . $_GET['search'] . '/' . $_GET['jenis']);
        return $content;
    }

    private function  get_faskes_info($parameter) {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/referensi/faskes/' . $parameter['kode'] . '/' . $parameter['type']);
        return $content;
    }

    private function get_kelas_rawat_select2() {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/kelasrawat');
        return $content;
    }

    private function get_sep_internal($parameter) {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/SEP/Internal/' . $parameter['search']['value']);
        return $content;
    }

    private function get_sep($parameter) {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/SEP/' . $parameter['kartu']);
        return $content;
    }

    private function hapus_spri($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $parameterBuilder = array('request' => array(
            't_suratkontrol' => array(
                'noSuratKontrol' => $parameter[7],
                'user' => $UserData['data']->nama
            )
        ));

        $deleteAct = self::deleteUrl2('/' . __BPJS_SERVICE_NAME__ . '/RencanaKontrol/Delete', $parameterBuilder);
        if(intval($deleteAct['value']['metaData']['code']) === 200 || intval($deleteAct['value']['metaData']['code']) === 201) {
            //Update SEP
            $DeleteSEP = self::$query->delete('bpjs_spri')
                ->where(array(
                    'bpjs_spri.no_spri' => '= ?'
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
                        'bpjs_spri',
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

    private function hapus_sep($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $parameterBuilder = array('request' => array(
            't_sep' => array(
                'noSep' => $parameter[7],
                'user' => $UserData['data']->nama
            )
        ));

        $deleteAct = self::deleteUrl2('/' . __BPJS_SERVICE_NAME__ . '/SEP/2.0/delete', $parameterBuilder);
        if(intval($deleteAct['metaData']['code']) === 200 || intval($deleteAct['metaData']['code']) === 201) {
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
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/Monitoring/Kunjungan/Tanggal/' . $parameter['tanggal'] . '/JnsPelayanan/' . $parameter['jenis']);
        return $content;
    }

    private function get_history_spri($parameter) {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/RencanaKontrol/ListRencanaKontrol/tglAwal/' . $parameter['dari'] . '/tglAkhir/' . $parameter['sampai'] . '/filter/2');
        return $content;
    }

    private function get_history_pelayanan($parameter) {
        $content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/Monitoring/HistoriPelayanan/NoKartu/' . $parameter['kartu'] . '/tglAwal/' . $parameter['dari'] . '/tglAkhir/' . $parameter['sampai']);
        return $content;
    }

    private function get_kontrol_dup ($parameter) {
        if($parameter['jenis'] == 1) {
            $data = self::$query->select('bpjs_spri', array(
                'uid'
            ))
                ->where(array(
                    'bpjs_spri.no_spri' => '= ?'
                ), array(
                    $parameter['no']
                ))
                ->execute();
        } else {
            $data = self::$query->select('bpjs_rencana_kontrol', array(
                'uid'
            ))
                ->where(array(
                    'bpjs_rencana_kontrol.no_surat_kontrol' => '= ?'
                ), array(
                    $parameter['no']
                ))
                ->execute();
        }
        
        return $data;
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

        $Antrian = new Antrian(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
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

        $BPJSLog = array();

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        $data_sync_record = array();
        if(isset($parameter['sync_bpjs']) && $parameter['sync_bpjs'] === 'Y') {
            foreach ($period as $dt) {
                $sync_sep = self::get_history_sep(array(
                    'tanggal' => $dt->format("Y-m-d"),
                    'jenis' => $parameter['pelayanan_jenis']
                ));

                $sync_content = $sync_sep;

                if(intval($sync_content['metaData']['code']) === 200) {
                    $data_sync = $sync_content['data']['sep'];
                    
                    foreach ($data_sync as $dKey => $dValue) {

                        array_push($BPJSLog, $dValue);

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
                                if($data_api_read['data']['peserta']['noKartu'] == $dValue['noKartu']) {
                                    $targetPasien = $PJValue['pasien'];
                                }
                            }
                        }

                        //Find SEP untuk hari ini dengan poli sesuai
                        //Sync Poli BPJS
                        $Poli = self::$query->select('master_poli', array(
                            'uid'
                        ))
                            ->where(array(
                                'master_poli.kode_bpjs' => '= ?',
                                'AND',
                                'master_poli.deleted_at' => 'IS NULL'
                            ), array(
                                $dValue['poli']
                            ))
                            ->execute();

                        $Antrian = self::$query->select('antrian', array(
                            'uid'
                        ))
                            ->where(array(
                                'antrian.departemen' => '= ?',
                                'AND',
                                'antrian.pasien' => '= ?',
                                'AND',
                                'antrian.penjamin' => '= ?',
                                'AND',
                                'antrian.created_at::date' => '= date(\'' . $dValue['tglSep'] . '\')',
                                'AND',
                                'antrian.deleted_at' => 'IS NULL',
                            ), array(
                                $Poli['response_data'][0]['uid'],
                                $targetPasien,
                                __UIDPENJAMINBPJS__
                            ))
                            ->execute();

                        /*Cek Tanggal kunjungan beda dengan pembuatan SEP
                         * if(count($Antrian['response_data']) > 0) {
                            $parameter['dataObj'] = array(
                                'departemen' => $parameter['poli'],
                                'pasien' => $parameter['pasien'],
                                'penjamin' => $parameter['penjamin'],
                                'prioritas' => $KunjunganData['response_data'][0]['prioritas'],
                                'dokter' => $KunjunganData['response_data'][0]['dokter']
                            );
                            $AntrianProses = $Antrian->tambah_antrian('antrian', $parameter, $parameter['kunjungan']);
                        }*/

                        //Check duplicate
                        $check = self::get_sep_detail_dup($dValue['noSep']);
                        if(count($check['response_data']) > 0) {
                            $sep_log = self::$query->update('bpjs_sep', array(
                                'uid' => $SEPuid,
                                'antrian' => $Antrian['response_data'][0]['uid'],
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
                                'deleted_at' => NULL
                                /*'created_at' => parent::format_date(),
                                'updated_at' => parent::format_date()*/
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
                                    'antrian' => $Antrian['response_data'][0]['uid'],
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
                                    'antrian' => $Antrian['response_data'][0]['uid'],
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

                        $sep_log['antrian'] = $Antrian;



                        array_push($data_sync_record, $sep_log);
                    }
                } else {
                    if(isset($sync_content)) {
                        //array_push($data_sync_record, $sync_content);
                    }
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
                ->order(array(
                    'created_at' => 'DESC'
                ))
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
        $Pegawai = new Pegawai(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;

            $data['response_data'][$key]['pasien'] = $Pasien->get_pasien_detail('pasien', $value['pasien'])['response_data'][0];

            //Check Claim Status
            $claimData = self::$query->select('bpjs_claim', array(
                'id',
                'tglMasuk',
                'tglKeluar',
                'jaminan',
                'poli',
                'ruangRawat',
                'kelasRawat',
                'spesialistik',
                'caraKeluar',
                'kondisiPulang',
                'diagnosa',
                'procedure',
                'tindakLanjut',
                'dirujukKe_kodePPK',
                'kontrolKembali_tanggal',
                'kontrolKembali_poli',
                'dpjp',
                'user',
                'pegawai',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'bpjs_claim.noSEP' => '= ?',
                    'AND',
                    'bpjs_claim.deleted_at' => 'IS NULL'
                ), array(
                    $value['sep_no']
                ))
                ->execute();
            foreach ($claimData['response_data'] as $CKey => $CValue) {
                $claimData['response_data'][$CKey]['pegawai'] = $Pegawai->get_detail($CValue['pegawai'])['response_data'][0];
            }

            $data['response_data'][$key]['claim'] = (isset($claimData['response_data'])) ? $claimData['response_data'] : array();
            $autonum++;
        }


        $data['sync_record'] = $data_sync_record;
        $data['response'] = $sync_sep;
        $data['bpjs_log'] = $BPJSLog;
        return $data;
    }

    private function get_sep_select2($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
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

    private function get_rujukan_detail($parameter) {
	    //$parameter = nomor rujukan

    }

    private function get_rujukan_list($parameter) {
        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/Rujukan/RS/List/Peserta/' . $parameter);
        //$content = self::launchUrl('/' . __BPJS_SERVICE_NAME__ . '/Rujukan/List/Peserta/' . $parameter);
        foreach ($content['data']['rujukan'] as $key => $value)
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

            $BPJSdiagnosa = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/diagnosa/' . $content['content']['response']['rujukan'][$key]['diagnosa']['kode']);
            $Diagnosa = (intval($BPJSdiagnosa['metaData']['code']) === 200) ? $BPJSdiagnosa['data']['diagnosa'][0] : array();
            $content['data']['rujukan'][$key]['diagnosa']['nama'] = $Diagnosa['nama'];

            $content['data']['rujukan'][$key]['provPerujuk']['jenis'] = $selectedFaskes;
            $content['data']['rujukan'][$key]['provPerujuk']['info'] = $selectedFaskesInfo;
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

    private function rujukan_edit($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $parameterBuilder = array(
            'request' => array(
                't_rujukan' => array(
                    'noRujukan' => $parameter['rujukan'],
                    'ppkDirujuk' => $parameter['tujuan'],
                    'jnsPelayanan' => $parameter['jenis_pelayanan'],
                    'catatan' => $parameter['catatan'],
                    'diagRujukan' => $parameter['diagnosa'],
                    'tipe' => $parameter['tipe'],
                    'tipeRujukan' => $parameter['tipe'],
                    'poliRujukan' => $parameter['poli'],
                    'user' => $UserData['data']->nama
                )
            )
        );

        $proceed = self::putUrl('/' . __BPJS_SERVICE_NAME__ . '/Rujukan/update', $parameterBuilder);
        if(intval($proceed['content']['metaData']['code']) === 200) {

            //Update Data Local
            //Get Data Local
            $rujukan_log = self::$query->update('bpjs_rujukan', array(
                'tujuan_rujukan_kode' => $parameter['tujuan'],
                'tujuan_rujukan_nama' => $parameter['tujuan_nama'],
                'pelayanan_kode' => $parameter['jenis_pelayanan'],
                'pelayanan_nama' => (intval($parameter['jenis_pelayanan']) === 1) ? 'Rawat Inap' : 'Rawat Jalan',
                'diagnosa_kode' => $parameter['diagnosa'],
                'diagnosa_nama' => $parameter['diagnosa_nama'],
                'jenis_faskes' => $parameter['jenis_faskes'],
                'jenis_faskes_nama' => $parameter['jenis_faskes_nama'],
                'catatan' => $parameter['catatan'],
                'tipe_rujukan' => $parameter['tipe'],
                'updated_at' => parent::format_date()
            ))
                ->where(array(
                    'bpjs_rujukan.uid' => '= ?',
                    'AND',
                    'bpjs_rujukan.deleted_at' => 'IS NULL'
                ), array(
                    $parameter['rujukan_uid']
                ))
                ->execute();
        }

        return array(
            'bpjs' => $proceed,
            'log' => $rujukan_log,
        );
    }

    private function rujukan_baru_v2($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $Pasien = new Pasien(self::$pdo);
        $PasienInfo =  $Pasien->get_pasien_detail('pasien', $parameter['pasien']);

        $parameterBuilder = array(
            'request' => array(
                't_rujukan' => array(
                    'noSep' => $parameter['sep'],
                    'tglRujukan' => date('Y-m-d'),
                    'tglRencanaKunjungan' => $parameter['tanggal_rencana_kunjungan'],
                    'ppkDirujuk' => $parameter['tujuan_faskes'],
                    'jnsPelayanan' => $parameter['jenis_pelayanan'],
                    'catatan' => $parameter['catatan'],
                    'diagRujukan' => $parameter['diagnosa'],
                    'tipeRujukan' => $parameter['tipe'],
                    'poliRujukan' => $parameter['tujuan_poli'],
                    'user' => $UserData['data']->nama
                )
            )
        );
        $proceed = self::postUrl('/' . __BPJS_SERVICE_NAME__ . '/Rujukan/2.0/insert', $parameterBuilder);
        if(intval($proceed['metaData']['code']) === 200) {
            $respData = $proceed['data']['rujukan'];
            $uid = parent::gen_uuid();
            $Rujukan = self::$query->insert('bpjs_rujukan', array(
                'uid' => $uid,
                'asal_rujukan_kode' => $respData['AsalRujukan']['kode'],
                'asal_rujukan_nama' => $respData['AsalRujukan']['nama'],
                'diagnosa_kode' => $respData['diagnosa']['kode'],
                'diagnosa_nama' => $respData['diagnosa']['nama'],
                'poli_tujuan_kode' => $respData['poliTujuan']['kode'],
                'poli_tujuan_nama' => $respData['poliTujuan']['nama'],
                'tgl_rujukan' => date('Y-m-d'),
                'tujuan_rujukan_kode' => $respData['tujuanRujukan']['kode'],
                'tujuan_rujukan_nama' => $respData['tujuanRujukan']['nama'],
                'catatan' => $parameter['catatan'],
                'sep' => $parameter['sep'],
                'pegawai' => $UserData['data']->uid,
                'pasien' => $parameter['pasien'],
                'peserta_jenis_peserta_kode' => $respData['peserta']['jnsPeserta'],
                'peserta_tanggal_lahir' => $respData['peserta']['tglLahir'],
                'peserta_mr_no' => $parameter['no_mr'],
                'peserta_mr_no_telp' => $parameter['telp'],
                'peserta_nama' => $parameter['nama_pasien'],
                'peserta_nik' => $parameter['nik'],
                'peserta_no_kartu' => $parameter['no_kartu'],
                'no_rujukan' => $respData['noRujukan'],
                'tipe_rujukan' => $parameter['tipe'],
                'tgl_rencana_kunjungan' => $respData['tglRencanaKunjungan'],
                'tgl_berlaku_kunjungan' => $respData['tglBerlakuKunjungan'],
            ))
                ->execute();
        }

        return array(
            'rujuk' => $Rujukan,
            'bpjs' => $proceed,
            'pasien_detail' => $PasienInfo
        );
    }

    private function rujukan_baru($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $Pasien = new Pasien(self::$pdo);
        $PasienInfo =  $Pasien->get_pasien_detail('pasien', $parameter['pasien']);

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
        $proceed = self::postUrl('/' . __BPJS_SERVICE_NAME__ . '/Rujukan/2.0/insert', $parameterBuilder);
        $uid = parent::gen_uuid();
        if(intval($proceed['metaData']['code']) === 200) {

            $proceed['content']['response'] = $proceed['data'];

            $uid = parent::gen_uuid();

            $BPJSdiagnosa = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/diagnosa/' . $parameter['diagnosa']);
            $Diagnosa = (intval($BPJSdiagnosa['metaData']['code']) === 200) ? $BPJSdiagnosa['data']['diagnosa'][0] : array();

            $rujukan_log = self::$query->insert('bpjs_rujukan', array(
                'uid' => $uid,
                'tipe_rujukan' => $parameter['tipe'],
                'request_rujukan' => $parameter['rujukan'],
                'asal_rujukan_kode' => (empty($proceed['content']['response']['rujukan']['AsalRujukan']['kode']) ? '' : $proceed['content']['response']['rujukan']['AsalRujukan']['kode']),
                'asal_rujukan_nama' => (empty($proceed['content']['response']['rujukan']['AsalRujukan']['nama']) ? '' : $proceed['content']['response']['rujukan']['AsalRujukan']['nama']),
                'diagnosa_kode' => (empty($proceed['content']['response']['rujukan']['diagnosa']['kode']) ? '' : $proceed['content']['response']['rujukan']['diagnosa']['kode']),
                'diagnosa_nama' => (empty($proceed['content']['response']['rujukan']['diagnosa']['nama']) ? $Diagnosa['nama'] : $proceed['content']['response']['rujukan']['diagnosa']['nama']),
                'no_rujukan' => (empty($proceed['content']['response']['rujukan']['noRujukan']) ? '' : $proceed['content']['response']['rujukan']['noRujukan']),
                'poli_tujuan_kode' => (empty($proceed['content']['response']['poliTujuan']['kode']) ? '' : $proceed['content']['response']['poliTujuan']['kode']),
                'poli_tujuan_nama' => (empty($proceed['content']['response']['poliTujuan']['nama']) ? '' : $proceed['content']['response']['poliTujuan']['nama']),
                'tgl_rujukan' => (empty($proceed['content']['response']['tglRujukan']) ? date('Y-m-d') : $proceed['content']['response']['tglRujukan']),
                'tujuan_rujukan_kode' => (empty($proceed['content']['response']['tujuanRujukan']['kode']) ? '' : $proceed['content']['response']['tujuanRujukan']['kode']),
                'tujuan_rujukan_nama' => (empty($proceed['content']['response']['tujuanRujukan']['nama']) ? '' : $proceed['content']['response']['tujuanRujukan']['nama']),
                'jenis_faskes' => $parameter['jenis_faskes'],
                'jenis_faskes_nama' => $parameter['jenis_faskes_nama'],
                'catatan' => $parameter['catatan'],
                'sep' => $parameter['sep_uid'],
                'pegawai' => $UserData['data']->uid,
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();

            //Update SEP
            /*$updateSEP = self::$query->update('bpjs_sep', array(
                ''
            ))
                ->where(array(), array())
                ->execute();*/

            $nomor_bpjs = "";
            foreach ($PasienInfo['response_data'] as $BPJSKey => $BPJSValue) {
                foreach ($BPJSValue['history_penjamin'] as $JSKey => $JSValue) {
                    if($JSValue['penjamin'] === __UIDPENJAMINBPJS__) {
                        $readJSON = json_decode($JSValue['rest_meta'], true);
                        $nomor_bpjs = $readJSON['response']['peserta']['noKartu'];
                    }
                }
            }

            
            $Rujukan = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/Rujukan/RS/List/Peserta/' . $nomor_bpjs);
            if(intval($Rujukan['metaData']['code']) === 200) {
                $data = $Rujukan['data']['rujukan'];
                foreach ($data as $key => $value) {
                    $check = self::$query->select('bpjs_rujukan', array(
                        'uid'
                    ))
                        ->where(array(
                            'bpjs_rujukan.deleted_at' => 'IS NULL',
                            'AND',
                            'bpjs_rujukan.sep' => '= ?'
                        ), array(
                            $parameter['sep_uid']
                        ))
                        ->execute();
                    if (count($check['response_data']) > 0) {

                        $checkItem = array(
                            'asal_rujukan_kode' => (empty($value['provPerujuk']['kode']) ? '' : $value['provPerujuk']['kode']),
                            'no_kunjungan' => (empty($value['noKunjungan']) ? '' : $value['noKunjungan']),
                            'asal_rujukan_nama' => (empty($value['provPerujuk']['nama']) ? '' : $value['provPerujuk']['nama']),
                            'diagnosa_kode' => (empty($value['diagnosa']['kode']) ? '' : $value['diagnosa']['kode']),
                            'diagnosa_nama' => (empty($value['diagnosa']['nama']) ? '' : $value['diagnosa']['nama']),
                            'poli_tujuan_kode' => (empty($value['poliRujukan']['kode']) ? '' : $value['poliRujukan']['kode']),
                            'poli_tujuan_nama' => (empty($value['poliRujukan']['nama']) ? '' : $value['poliRujukan']['nama']),
                            'tgl_rujukan' => (empty($value['tglKunjungan']) ? date('Y-m-d') : date('Y-m-d', strtotime($value['tglKunjungan']))),
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

                        );

                        $allowProceed = array();

                        foreach ($checkItem as $checkKey => $checkValue) {
                            if($checkValue !== '' && !empty($checkValue)) {
                                $allowProceed[$checkKey] = $checkValue;
                            }
                        }

                        //TODO : Check NULL

                        $proceedSync = self::$query->update('bpjs_rujukan', $allowProceed)
                            ->where(array(
                                'bpjs_rujukan.deleted_at' => 'IS NULL',
                                'AND',
                                'bpjs_rujukan.uid' => '= ?'
                            ), array(
                                $uid
                            ))
                            ->execute();
                    }
                }
            }
        }

        return array(
            'rujuk' => $Rujukan,
            'bpjs' => $proceed,
            'log' => $rujukan_log,
            'peserta' => $nomor_bpjs,
            'pasien_detail' => $PasienInfo
        );
    }

    private function sep_edit($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $parameterBuilder = array(
            'request' => array(
                't_sep' => array(
                    'noSep' => $parameter['sep'],
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
                        'eksekutif' => $parameter['eksekutif']
                    ),
                    'cob' => array(
                        'cob' => $parameter['cob']
                    ),
                    'katarak' => array(
                        'katarak' => $parameter['katarak']
                    ),
                    'skdp' => array(
                        'noSurat' => $parameter['skdp'],
                        'kodeDPJP' => $parameter['dpjp']
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
                    'noTelp' => $parameter['telepon'],
                    'user' => $UserData['data']->nama
                    /*'noKartu' => $parameter['no_kartu'],
                    'tglSep' => strval(date('Y-m-d')),
                    'ppkPelayanan' => $parameter['ppk_pelayanan'],
                    'jnsPelayanan' => '2',*/
                )
            )
        );

        $proceed = self::putUrl('/' . __BPJS_SERVICE_NAME__ . '/SEP/1.1/Update', $parameterBuilder);
        $uid = parent::gen_uuid();
        if(intval($proceed['content']['metaData']['code']) === 200) {
            $sep_log = self::$query->update('bpjs_sep', array(
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
                'skdp_dpjp_nama' => $parameter['dpjp_nama'],
                'no_telp' => $parameter['telepon'],
                'pegawai' => $UserData['data']->uid,
                //'sep_no' => $proceed['content']['response']['sep']['noSep'],
                //'sep_tanggal' => isset($proceed['content']['response']['sep']['tglSep']) ? date('Y-m-d', strtotime($proceed['content']['response']['sep']['tglSep'])) : date('Y-m-d'),
                //'sep_dinsos' => isset($proceed['content']['response']['sep']['informasi']['Dinsos']) ? $proceed['content']['response']['sep']['informasi']['Dinsos'] : '',
                //'sep_prolanis' => isset($proceed['content']['response']['sep']['informasi']['prolanisPRB']) ? $proceed['content']['response']['sep']['informasi']['prolanisPRB'] : '',
                //'pasien' => $parameter['pasien'],
                //'sep_sktm' => isset($proceed['content']['response']['sep']['informasi']['noSKTM']) ? $proceed['content']['response']['sep']['informasi']['noSKTM'] : '',
                'spesialistik_kode' => $parameter['spesialistik_kode'],
                'spesialistik_nama' => $parameter['spesialistik_nama'],
                'updated_at' => parent::format_date()
            ))
                ->where(array(
                    'bpjs_sep.deleted_at' => 'IS NULL',
                    'AND',
                    'bpjs_sep.uid' => '= ?'
                ), array(
                    $parameter['uid']
                ))
                ->execute();
        }
        return array(
            'bpjs' => $proceed,
            'log' => $sep_log
        );
    }

    private function cari_rujukan($parameter) {
        $proceed = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/Rujukan/RS/List/Peserta/' . $parameter['no_kartu']);
        return $proceed;
    }

    private function get_history_spri_local($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $Pasien = new Pasien(self::$pdo);
        $BPJSLog = array();

        $data_sync_record = array();
        if(isset($parameter['sync_bpjs']) && $parameter['sync_bpjs'] === 'Y') {
            $sync_sep = self::get_history_spri(array(
                'dari' => $parameter['dari'],
                'sampai' => $parameter['sampai']
            ));

            $sync_content = $sync_sep;

            if(intval($sync_content['metaData']['code']) === 200) {
                $data_sync = $sync_content['data']['list'];
                
                foreach ($data_sync as $dKey => $dValue) {

                    array_push($BPJSLog, $dValue);
                    $check = self::get_kontrol_dup(array(
                        'jenis' => $dValue['jnsKontrol'],
                        'no' => $dValue['noSuratKontrol']
                    ));

                    //Pasien
                    $SEP = self::$query->select('bpjs_sep', array(
                        'pasien'
                    ))
                        ->where(array(
                            'bpjs_sep.sep_no' => '= ?'
                        ), array(
                            $dValue['noSepAsalKontrol']
                        ))
                        ->execute()['response_data'][0];

                    $PasienDetail = $Pasien->get_pasien_detail('pasien', $SEP['pasien'])['response_data'][0];
                    
                    

                    if(count($check['response_data']) > 0) {

                        // $sep_log = self::$query->update('bpjs_sep', array(
                        //     'uid' => $SEPuid,
                        //     'antrian' => $Antrian['response_data'][0]['uid'],
                        //     'pasien' => $targetPasien,
                        //     'pelayanan_jenis' => $dValue['jnsPelayanan'],
                        //     'kelas_rawat' => $dValue['kelasRawat'],
                        //     'asal_rujukan_nomor' => $dValue['noRujukan'],
                        //     'diagnosa_kode' => $dValue['diagnosa'],
                        //     'poli_tujuan' => $dValue['poli'],
                        //     'pegawai' => $UserData['data']->uid,
                        //     'sep_no' => $dValue['noSep'],
                        //     'sep_tanggal' => $dValue['tglSep'],
                        //     'sep_selesai' => $dValue['tglPlgSep'],
                        //     'deleted_at' => NULL
                        //     /*'created_at' => parent::format_date(),
                        //     'updated_at' => parent::format_date()*/
                        // ))
                        //     ->where(array(
                        //         'bpjs_sep.deleted_at' => 'IS NULL',
                        //         'AND',
                        //         'bpjs_sep.uid' => '= ?'
                        //     ), array(
                        //         $check['response_data'][0]['uid']
                        //     ))
                        //     ->execute();
                    } else {
                        // Pisahkan jenis pelayanan
                        $kontroluid = parent::gen_uuid();
                        $kontrol_log = self::$query->insert('bpjs_spri', array(
                            'uid' => $kontroluid,
                            'pasien' => $SEP['pasien'],
                            'pegawai' => $UserData['data']->uid,
                            'no_sep' => $parameter['sep'],
                            'poli_tujuan' => $dValue['poliTujuan'],
                            'poli_tujuan_text' => $dValue['namaPoliTujuan'],
                            'jenis_layan' => ($dValue['jnsPelayanan'] === 'Rawat Jalan') ? 2 : 1,
                            'spesialistik' => "",
                            'spesialistik_text' => "",
                            'poli_asal' => $dValue['poliAsal'],
                            'tgl_rencana_kontrol' => $dValue['tglRencanaKontrol'],
                            'no_spri' => $dValue['noSuratKontrol'],
                            'no_kartu' => $dValue['noKartu'],
                            'user_name' => $UserData['data']->nama,
                            'dpjp_kode' => $dValue['kodeDokter'],
                            'dpjp_nama' => $dValue['namaDokter'],
                            'pasien_nama' => $dValue['nama'],
                            'pasien_kelamin' => $PasienDetail['jenkel_detail']['nama'],
                            'pasien_tgl_lahir' => $PasienDetail['tanggal_lahir'],
                            'created_at' => parent::format_date(),
                            'updated_at' => parent::format_date()
                        ))
                            ->execute();
                    }

                    array_push($data_sync_record, $kontrol_log);
                }
            }
        }


        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'bpjs_spri.deleted_at' => 'IS NULL',
                'AND',
                'bpjs_spri.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'bpjs_spri.no_spri' => 'ILIKE ' . '\'%' . $parameter['no_sep'] . '%\''
            );

            $paramValue = array($parameter['dari'], $parameter['sampai']);
        } else {
            $paramData = array(
                'bpjs_spri.created_at' => 'BETWEEN ? AND ?',
                'AND',
                'bpjs_spri.deleted_at' => 'IS NULL'
            );

            $paramValue = array($parameter['dari'], $parameter['sampai']);
        }

        if ($parameter['length'] < 0) {
            $data = self::$query->select('bpjs_spri', array(
                'uid',
                'pasien',
                'pasien_nama',
                'pasien_kelamin',
                'pasien_tgl_lahir',
                'no_spri',
                'tgl_rencana_kontrol',
                'dpjp_kode',
                'dpjp_nama',
                'no_kartu',
                'no_sep',
                'jenis_layan',
                'user_name',
                'poli_tujuan',
                'poli_tujuan_text',
                'poli_asal',
                'pegawai',
                'created_at',
                'updated_at',
                'deleted_at'
            ))
                ->where($paramData, $paramValue)
                ->order(array(
                    'created_at' => 'DESC'
                ))
                ->execute();
        } else {
            $data = self::$query->select('bpjs_sep', array(
                'uid',
                'pasien',
                'pasien_nama',
                'pasien_kelamin',
                'pasien_tgl_lahir',
                'no_spri',
                'tgl_rencana_kontrol',
                'dpjp_kode',
                'dpjp_nama',
                'no_kartu',
                'no_sep',
                'jenis_layan',
                'user_name',
                'poli_tujuan',
                'poli_tujuan_text',
                'poli_asal',
                'pegawai',
                'created_at',
                'updated_at',
                'deleted_at'
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
        $Pegawai = new Pegawai(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['created_at_parsed'] = date('d F y, H:i:s', strtotime($value['created_at']));
            $data['response_data'][$key]['pasien'] = $Pasien->get_pasien_detail('pasien', $value['pasien'])['response_data'][0];
            $autonum++;
        }


        $data['sync_record'] = $data_sync_record;
        $data['response'] = $sync_sep;
        $data['bpjs_log'] = $BPJSLog;
        return $data;

    }

    private function spri_edit($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        if(intval($parameter['jenis_layan']) == 1) {
            $parameterBuilder = array(
                'request' => array(
                    'noSPRI' => $parameter['no_spri'],
                    'kodeDokter' => $parameter['dpjp'],
                    'poliKontrol' => $parameter['poli_tujuan'],
                    'tglRencanaKontrol' => $parameter['tanggal'],
                    'user' => $UserData['data']->nama,
                )
            );
        } else {
            $parameterBuilder = array(
                'request' => array(
                    'noSuratKontrol' => $parameter['no_spri'],
                    'noSEP' => $parameter['sep'],
                    'kodeDokter' => $parameter['dpjp'],
                    'poliKontrol' => $parameter['poli_tujuan'],
                    'tglRencanaKontrol' => $parameter['tanggal'],
                    'user' => $UserData['data']->nama,
                )
            );
        }
        
        if(intval($parameter['jenis_layan']) == 1) {
            $proceed = self::putUrl('/' . __BPJS_SERVICE_NAME__ . '/RencanaKontrol/UpdateSPRI', $parameterBuilder);
        } else {
            $proceed = self::putUrl('/' . __BPJS_SERVICE_NAME__ . '/RencanaKontrol/update', $parameterBuilder);
        }
        
        $uid = parent::gen_uuid();
        if(intval($proceed['metaData']['code']) === 200) {
            $rk_log = self::$query->update('bpjs_spri', array(
                'poli_tujuan' => $parameter['poli_tujuan'],
                'poli_tujuan_text' => $parameter['poli_text'],
                'jenis_layan' => $parameter['jenis_layan'],
                'spesialistik' => $parameter['spesialistik'],
                'spesialistik_text' => $parameter['spesialistik_text'],
                'tgl_rencana_kontrol' => $proceed['data']['tglRencanaKontrol'],
                'dpjp_kode' => $parameter['dpjp'],
                'dpjp_nama' => $proceed['data']['namaDokter'],
                'updated_at' => parent::format_date()
            ))
                ->where(array(
                    'bpjs_spri.no_spri' => '= ?'
                ), array(
                    $parameter['no_spri']
                ))
                ->execute();
        }
        return array(
            'bpjs' => $proceed,
            'log' => $rk_log,
            'parameter' => $parameter
        );
    }

    private function spri_baru($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        if(intval($parameter['jenis_layan']) == 1) {
            $parameterBuilder = array(
                'request' => array(
                    'noKartu' => $parameter['kartu'],
                    'kodeDokter' => $parameter['dpjp'],
                    'poliKontrol' => $parameter['poli_tujuan'],
                    'tglRencanaKontrol' => $parameter['tanggal'],
                    'user' => $UserData['data']->nama,
                )
            );
        } else {
            $parameterBuilder = array(
                'request' => array(
                    'noSEP' => $parameter['sep'],
                    'kodeDokter' => $parameter['dpjp'],
                    'poliKontrol' => $parameter['poli_tujuan'],
                    'tglRencanaKontrol' => $parameter['tanggal'],
                    'user' => $UserData['data']->nama,
                )
            );
        }

        if(intval($parameter['jenis_layan']) == 1) {
            $proceed = self::postUrl('/' . __BPJS_SERVICE_NAME__ . '/RencanaKontrol/InsertSPRI', $parameterBuilder);
        } else {
            $proceed = self::postUrl('/' . __BPJS_SERVICE_NAME__ . '/RencanaKontrol/insert', $parameterBuilder);
        }
        
        
        $uid = parent::gen_uuid();
        if(intval($proceed['metaData']['code']) === 200) {
            $rk_log = self::$query->insert('bpjs_spri', array(
                'uid' => $uid,
                'pasien' => $parameter['pasien'],
                'pegawai' => $UserData['data']->uid,
                'no_sep' => $parameter['sep'],
                'poli_tujuan' => $parameter['poli_tujuan'],
                'poli_tujuan_text' => $parameter['poli_text'],
                'jenis_layan' => $parameter['jenis_layan'],
                'spesialistik' => $parameter['spesialistik'],
                'spesialistik_text' => $parameter['spesialistik_text'],
                'poli_asal' => $parameter['poli_asal'],
                'tgl_rencana_kontrol' => $proceed['data']['tglRencanaKontrol'],
                'no_spri' => (intval($parameter['jenis_layan']) == 1) ? $proceed['data']['noSPRI'] : $proceed['data']['noSuratKontrol'],
                'no_kartu' => $parameter['kartu'],
                'user_name' => $UserData['data']->nama,
                'dpjp_kode' => $parameter['dpjp'],
                'dpjp_nama' => $proceed['data']['namaDokter'],
                'pasien_nama' => $proceed['data']['nama'],
                'pasien_kelamin' => $proceed['data']['kelamin'],
                'pasien_tgl_lahir' => $proceed['data']['tglLahir'],
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();
        }
        return array(
            'bpjs' => $proceed,
            'log' => $rk_log,
            'parameter' => $parameter
        );
    }

    private function rencana_kontrol_baru($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $parameterBuilder = array(
            'request' => array(
                'noSEP' => $parameter['sep'],
                'kodeDokter' => $parameter['dpjp'],
                'poliKontrol' => $parameter['poli'],
                'tglRencanaKontrol' => $parameter['tanggal'],
                'user' => $UserData['data']->nama,
            )
        );
        $proceed = self::postUrl('/' . __BPJS_SERVICE_NAME__ . '/RencanaKontrol/insert', $parameterBuilder);
        $uid = parent::gen_uuid();
        if(intval($proceed['content']['metaData']['code']) === 200) {
            $rk_log = self::$query->insert('bpjs_rencana_kontrol', array(
                'uid' => $uid,
                'pasien' => $parameter['pasien'],
                'pegawai' => $UserData['data']->uid,
                'sep' => $parameter['sep'],
                'poli' => $parameter['poli'],
                'tgl_rencana_kontrol' => $proceed['data']['tglRencanaKontrol'],
                'no_surat_kontrol' => $proceed['data']['noSuratKontrol'],
                'kode_dokter' => $parameter['dpjp'],
                'nama_dokter' => $proceed['data']['namaDokter'],
                'pasien_nama' => $proceed['data']['nama'],
                'pasien_kelamin' => $proceed['data']['kelamin'],
                'pasien_tgl_lahir' => $proceed['data']['tglLahir'],
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();
        }
        return array(
            'bpjs' => $proceed,
            'log' => $rk_log,
            'parameter' => $parameter
        );
    }

    private function prb_baru($parameter) {

        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $parameterBuilder = array(
            'request' => array(
                't_prb' => array(
                    'noSep' => $parameter['sep'],
                    'noKartu' => $parameter['kartu'],
                    'alamat' => $parameter['alamat'],
                    'email' => $parameter['email'],
                    'programPRB' => $parameter['prb'],
                    'kodeDPJP' => $parameter['dpjp'],
                    'keterangan' => $parameter['keterangan'],
                    'saran' => $parameter['saran'],
                    'user' => $UserData['data']->nama,
                    'obat' => $parameter['obat']
                )
            )
        );
        $proceed = self::postUrl('/' . __BPJS_SERVICE_NAME__ . '/PRB/insert', $parameterBuilder);
        $uid = parent::gen_uuid();
        if(intval($proceed['content']['metaData']['code']) === 200) {
            $uid = parent::gen_uuid();
            $prb_log = self::$query->insert('bpjs_prb', array(
                'uid' => $uid,
                'sep' => $parameter['sep'],
                'pasien_no_kartu' => $proceed['data']['peserta']['noKartu'],
                'pasien_alamat' => $proceed['data']['peserta']['alamat'],
                'pasien_email' => $proceed['data']['peserta']['email'],
                'pasien_kelamin' => $proceed['data']['peserta']['kelamin'],
                'pasien_telp' => $proceed['data']['peserta']['noTelepon'],
                'pasien_tgl_lahir' => $proceed['data']['peserta']['tglLahir'],
                'pasien_asal_faskes_kode' => $proceed['data']['peserta']['asalFaskes']['kode'],
                'pasien_asal_faskes_nama' => $proceed['data']['peserta']['asalFaskes']['nama'],
                'program_prb' => $proceed['data']['programPRB'],
                'dpjp_kode' => $proceed['data']['DPJP']['kode'],
                'dpjp_nama' => $proceed['data']['DPJP']['nama'],
                'keterangan' => $proceed['data']['keterangan'],
                'saran' => $proceed['data']['saran'],
                'user' => $UserData['data']->nama,
                'no_srb' => $proceed['data']['noSRB'],
                'pegawai' => $UserData['data']->uid,
                'pasien' => $parameter['pasien'],
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();

            $prb_log['detail_log'] = array();

            $obatList = $parameter['obatInt'];
            foreach($obatList as $obKey => $obValue) {
                $listObat = self::$query->insert('bpjs_prb_obat', array(
                    'prb' => $uid,
                    'kd_obat' => $obValue['kdObat'],
                    'nama_obat' => $obValue['nmObat'],
                    'jml_obat' => $obValue['jmlObat'],
                    'signa1' => $obValue['signa1'],
                    'signa2' => $obValue['signa2'],
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();

                array_push($prb_log['detail_log'], $listObat);
            }
        }
        return array(
            'bpjs' => $proceed,
            'log' => $prb_log,
            'parameter' => $parameter
        );
    }

    private function tambah_claim($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $parameterBuilder = array(
            'request' => array(
                't_lpk' => array(
                    'noSep' => $parameter['sep'],
                    'tglMasuk' => $parameter['tgl_masuk'],
                    'tglKeluar' => $parameter['tgl_keluar'],
                    'jaminan' => $parameter['jaminan'],
                    'poli' => array(
                        'poli' => $parameter['poli']
                    ),
                    'perawatan' => array(
                        'ruangRawat' => /*$parameter['perawatan_ruang_rawat']*/'',
                        'kelasRawat' => /*$parameter['perawatan_kelas_rawat']*/'',
                        'spesialistik' => /*$parameter['perawatan_spesialistik']*/'',
                        'caraKeluar' => /*$parameter['perawatan_cara_keluar']*/'',
                        'kondisiPulang' => /*$parameter['perawatan_kondisi_pulang']*/''
                    ),
                    'diagnosa' => $parameter['diagnosa_kode'],
                    'procedure' => $parameter['procedure'],
                    'rencanaTL' => array(
                        'tindakLanjut' => $parameter['rencana_tl_tindak_lanjut'],
                        'dirujukKe' => array(
                            'kodePPK' => $parameter['rencana_tl_dirujuk_ke']
                        ),
                        'kontrolKembali' => array(
                            'tglKontrol' => $parameter['rencana_tl_kontrol_kembali_tanggal'],
                            'poli' => $parameter['rencana_tl_kontrol_kembali_poli']
                        )
                    ),
                    'DPJP' => $parameter['dpjp'],
                    'user' => $UserData['data']->nama
                )
            )
        );

        $proceed = self::postUrl('/' . __BPJS_SERVICE_NAME__ . '/LPK/insert', $parameterBuilder);
        $uid = parent::gen_uuid();
        if(intval($proceed['content']['metaData']['code']) === 200) {
            $lpk_log = self::$query->insert('bpjs_claim', array(
                'noSEP' => $parameter['sep'],
                'tglMasuk' => $parameter['tgl_masuk'],
                'tglKeluar' => $parameter['tgl_keluar'],
                'jaminan' => $parameter['jaminan'],
                'poli' => $parameter['poli'],
                'ruangRawat' => $parameter['perawatan_ruang_rawat'],
                'kelasRawat' => $parameter['perawatan_kelas_rawat'],
                'spesialistik' => $parameter['perawatan_spesialistik'],
                'caraKeluar' => $parameter['perawatan_cara_keluar'],
                'kondisiPulang' => $parameter['perawatan_kondisi_pulang'],
                'diagnosa' => strval(json_encode($parameter['diagnosa_kode'])),
                'procedure' => strval(json_encode($parameter['procedure'])),
                'tindakLanjut' => $parameter['rencana_tl_tindak_lanjut'],
                'dirujukKe_kodePPK' => $parameter['rencana_tl_dirujuk_ke'],
                'kontrolKembali_tanggal' => $parameter['rencana_tl_kontrol_kembali_tanggal'],
                'kontrolKembali_poli' => $parameter['rencana_tl_kontrol_kembali_poli'],
                'dpjp' => $parameter['dpjp'],
                'user' => $UserData['data']->nama,
                'pegawai' => $UserData['data']->uid,
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();
        }

        return array(
            'bpjs' => $proceed,
            'log' => $lpk_log
        );
    }

    private function sep_pengajuan($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $parameterBuilder = array(
            'request' => array(
                't_sep' => array(
                    'noKartu' => $parameter['no_kartu'],
                    'tglSep' => strval(date('Y-m-d')),
                    'jnsPelayanan' => '2',
                    'jnsPengajuan' => '2',
                    'keterangan' => $parameter['keterangan'],
                    'user' => $UserData['data']->nama
                )
            )
        );

        $proceed = self::postUrl('/' . __BPJS_SERVICE_NAME__ . '/SEP/2.0/insert', $parameterBuilder);
        return $proceed;
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
                    'ppkPelayanan' => __KODE_PPK__,
                    'jnsPelayanan' => (($parameter['poli']) === 'IGD') ? '2' : $parameter['jenis_pelayanan'],
                    'klsRawat' => array(
                        'klsRawatHak' => $parameter['kelas_rawat'],
                        'klsRawatNaik' => '',
                        'pembiayaan' => '',
                        'penanggungJawab' => ''
                    ),
                    'noMR' => $parameter['no_mr'],
                    'rujukan' => array(
                        'asalRujukan' => ($parameter['poli'] === 'IGD') ? '2' : ((empty($parameter['asal_rujukan'])) ? '2' : $parameter['asal_rujukan']),
                        'tglRujukan' => ($parameter['poli'] === 'IGD') ? '' : strval(date('Y-m-d')),
                        'noRujukan' => ($parameter['poli'] === 'IGD') ? '' : ((isset($parameter['fktp'])) ? '-' : $parameter['no_rujukan']),
                        'ppkRujukan' => ($parameter['poli'] === 'IGD') ? '' : ((isset($parameter['ppk_rujukan']) && !empty($parameter['ppk_rujukan']) && $parameter['ppk_rujukan'] != '') ? $parameter['ppk_rujukan'] : $parameter['fktp'])
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
                    'tujuanKunj' => $parameter['tujuan_kunjungan'],
                    'flagProcedure' => (intval($parameter['tujuan_kunjungan']) === 0) ? '' : $parameter['flag_procedure'],
                    'kdPenunjang' => (intval($parameter['tujuan_kunjungan']) === 0) ? '' : $parameter['kode_penunjang'],
                    'assesmentPel' => (isset($parameter['fktp'])) ? '' : (intval($parameter['tujuan_kunjungan']) === 0 || intval($parameter['tujuan_kunjungan']) === 2) ? $parameter['asesmen_pelayanan'] : '',
                    'jaminan' => array(
                        'lakaLantas' => $parameter['laka_lantas'],
                        'penjamin' => array(
                            'tglKejadian' => ($parameter['poli'] === 'IGD') ? '' : $parameter['laka_lantas_tanggal_kejadian'],
                            'keterangan' => $parameter['laka_lantas_keterangan'],
                            'suplesi' => array(
                                'suplesi' => ($parameter['poli'] === 'IGD') ? '' : strval($parameter['laka_lantas_suplesi']),
                                'noSepSuplesi' => ($parameter['poli'] === 'IGD') ? '' : strval($parameter['laka_lantas_suplesi_nomor']),
                                'lokasiLaka' => array(
                                    'kdPropinsi' => ($parameter['poli'] === 'IGD') ? '' : strval($parameter['laka_lantas_suplesi_provinsi']),
                                    'kdKabupaten' => ($parameter['poli'] === 'IGD') ? '' : strval($parameter['laka_lantas_suplesi_kabupaten']),
                                    'kdKecamatan' => ($parameter['poli'] === 'IGD') ? '' : strval($parameter['laka_lantas_suplesi_kecamatan'])
                                )
                            )
                        )
                    ),
                    'dpjpLayan' => (intval($parameter['jenis_pelayanan']) === 1) ? '' : $parameter['dpjp'],
                    'skdp' => array(
                        'noSurat' => ($parameter['poli'] === 'IGD') ? '' : $parameter['skdp'],
                        'kodeDPJP' => $parameter['dpjp']
                    ),
                    'noTelp' => $parameter['telepon'],
                    'user' => $UserData['data']->nama
                )
            )
        );
        
        $proceed = self::postUrl('/' . __BPJS_SERVICE_NAME__ . '/SEP/2.0/insert', $parameterBuilder);
        $uid = parent::gen_uuid();
        if(intval($proceed['metaData']['code']) === 200) {

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
                'skdp_dpjp_nama' => $parameter['dpjp_nama'],
                'no_telp' => $parameter['telepon'],
                'pegawai' => $UserData['data']->uid,
                'sep_no' => $proceed['data']['sep']['noSep'],
                'sep_tanggal' => isset($proceed['data']['sep']['tglSep']) ? date('Y-m-d', strtotime($proceed['data']['sep']['tglSep'])) : date('Y-m-d'),
                'sep_dinsos' => isset($proceed['data']['sep']['informasi']['Dinsos']) ? $proceed['data']['sep']['informasi']['Dinsos'] : '',
                'sep_prolanis' => isset($proceed['data']['sep']['informasi']['prolanisPRB']) ? $proceed['data']['sep']['informasi']['prolanisPRB'] : '',
                'pasien' => $parameter['pasien'],
                'sep_sktm' => isset($proceed['data']['sep']['informasi']['noSKTM']) ? $proceed['data']['sep']['informasi']['noSKTM'] : '',
                'spesialistik_kode' => $parameter['spesialistik_kode'],
                'spesialistik_nama' => $parameter['spesialistik_nama'],
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();
        }
        
        return array(
            'bpjs' => $proceed,
            'log' => $sep_log,
            'log_param' => array(
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
                'skdp_dpjp_nama' => $parameter['dpjp_nama'],
                'no_telp' => $parameter['telepon'],
                'pegawai' => $UserData['data']->uid,
                'sep_no' => $proceed['data']['sep']['noSep'],
                'sep_tanggal' => isset($proceed['data']['sep']['tglSep']) ? date('Y-m-d', strtotime($proceed['data']['sep']['tglSep'])) : date('Y-m-d'),
                'sep_dinsos' => isset($proceed['data']['sep']['informasi']['Dinsos']) ? $proceed['data']['sep']['informasi']['Dinsos'] : '',
                'sep_prolanis' => isset($proceed['data']['sep']['informasi']['prolanisPRB']) ? $proceed['data']['sep']['informasi']['prolanisPRB'] : '',
                'pasien' => $parameter['pasien'],
                'sep_sktm' => isset($proceed['data']['sep']['informasi']['noSKTM']) ? $proceed['data']['sep']['informasi']['noSKTM'] : '',
                'spesialistik_kode' => $parameter['spesialistik_kode'],
                'spesialistik_nama' => $parameter['spesialistik_nama'],
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ),
            'parameter' => $parameterBuilder
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
            'skdp_dpjp_nama',
            'no_telp',
            'pegawai',
            'sep_no',
            'sep_tanggal',
            'sep_dinsos',
            'sep_prolanis',
            'sep_sktm',
            'spesialistik_kode',
            'spesialistik_nama',
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
            $data['response_data'][$key]['sep_tanggal'] = date('d F Y', strtotime($value['sep_tanggal']));
            $poli_detail = self::get_poli_detail($value['poli_tujuan']);
            $data['response_data'][$key]['poli_tujuan_detail'] = $poli_detail[0];

            $pasien = $Pasien->get_pasien_detail('pasien', $value['pasien']);
            foreach ($pasien['response_data'][0]['history_penjamin'] as $pKey => $pValue) {
                if($pasien['response_data'][0]['history_penjamin'][$pKey]['penjamin'] === __UIDPENJAMINBPJS__) {
                    /*$readData = preg_replace('/\\\\/', '', $pValue['penjamin_detail']['rest_meta']);
                    $pasien['response_data'][0]['history_penjamin'][$pKey]['penjamin_detail']['rest_meta_parse'] = json_decode($readData, true);*/
                }
            }
            $data['response_data'][$key]['pasien'] = $pasien['response_data'][0];

            $AntrianDetail = $Antrian->get_antrian_detail('antrian', $value['antrian']);
            $data['response_data'][$key]['antrian_detail'] = $AntrianDetail['response_data'][0];

            //Kelas Rawat Detail
            $kelas = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/referensi/kelasrawat');
            if(intval($kelas['metaData']['code']) === 200) {
                foreach ($kelas['data']['list'] as $KKey => $KValue) {
                    if($KValue['kode'] === $value['kelas_rawat']) {
                        $data['response_data'][$key]['kelas_rawat'] = $KValue;
                        continue;
                    }
                }
            } else {
                $data['response_data'][$key]['kelas_rawat'] = array(
                    'kode' => 0,
                    'nama' => 'Tidak diketahui'
                );
            }
            $data['response_data'][$key]['kelas_rawat_info'] = $kelas['content']['metaData'];
            $data['response_data'][$key]['kelas_rawat_content'] = $kelas['content']['response']['list'];
        }

        return $data;
    }

    private function get_detail_spri($parameter) {
        $data = self::$query->select('bpjs_spri', array(
            'uid',
            'pasien',
            'pasien_nama',
            'pasien_kelamin',
            'pasien_tgl_lahir',
            'no_spri',
            'tgl_rencana_kontrol',
            'dpjp_kode',
            'dpjp_nama',
            'no_kartu',
            'no_sep',
            'user_name',
            'poli_tujuan',
            'poli_tujuan_text',
            'poli_asal',
            'pegawai',
            'spesialistik',
            'jenis_layan',
            'created_at',
            'updated_at',
            'deleted_at'
        ))
            ->where(array(
                'bpjs_spri.deleted_at' => 'IS NULL',
                'AND',
                'bpjs_spri.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        $Pasien = new Pasien(self::$pdo);
        foreach($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['pasien'] = $Pasien->get_pasien_detail('pasien', $value['pasien'])['response_data'][0];
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
        $UserData = $Authorization->readBearerToken($parameter['access_token']);


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

        $content = self::getUrl2('/' . __BPJS_SERVICE_NAME__ . '/Peserta/nokartu/' . $no_bpjs . '/tglSEP/' . $tglSEP . '');

		/*$content = json_decode($content, TRUE);*/
		// $content['content']['response']['peserta']['tglLahir'] = date('d F Y', strtotime($content['content']['response']['peserta']['tglLahir']));
		// $content['content']['response']['peserta']['tglCetakKartu'] = date('d F Y', strtotime($content['content']['response']['peserta']['tglCetakKartu']));
		return $content;
	}

    public function getUrl2($extended_url) {
        $url = ((__BPJS_MODE__ === 2) ? __BASE_LIVE_BPJS__ : __BASE_STAGING_BPJS__);
        $data_api = ((__BPJS_MODE__ === 2) ? __DATA_API_LIVE__ : __DATA_API_STAGING__);
        $secretKey_api = ((__BPJS_MODE__ === 2) ? __SECRET_KEY_LIVE_BPJS__ : __SECRET_KEY_DEV_BPJS__);

        date_default_timezone_set('UTC');
        $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
        $signature = hash_hmac('sha256', $data_api ."&". $tStamp , $secretKey_api, true);
        $encodedSignature = base64_encode($signature);

        $consid = (__BPJS_MODE__ === 2) ? __DATA_API_LIVE__ : __DATA_API_STAGING__;
        $passwd = (__BPJS_MODE__ === 2) ? __SECRET_KEY_LIVE_BPJS__ : __SECRET_KEY_DEV_BPJS__;
        $keyForDecompress = $consid . $passwd . $tStamp;

        $client = new \GuzzleHttp\Client([
            'base_uri' => $url,
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_SSL_VERIFYHOST => FALSE,
                CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'
            ],
            'headers' => [
                "X-cons-id" => $data_api,
                "X-timestamp" => $tStamp,
                "X-signature" => $encodedSignature,
                "Content-Type" => "application/x-www-form-urlencoded; charset=UTF-8;",
                "user_key" => ((__BPJS_MODE__ === 2) ? __USERKEY_LIVE_BPJS__ : __USERKEY_DEV_BPJS__)
            ]
        ]);

        
        
        $response = $client->getAsync($extended_url)->then(function($resp) {
            //return json_decode($resp->getBody()->getContents(), true);
            return json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $resp->getBody()->getContents()), true);
        });

        $promise = \GuzzleHttp\Promise\settle($response)->wait(true);
        
        $dataList = self::decryptor($promise[0]['value']['response'], $keyForDecompress);

        return array(
            'metaData' => $promise[0]['value']['metaData'],
            'data' => $dataList
        );
    }

    public function getUrl($extended_url) {
        $url = ((__BPJS_MODE__ === 2) ? __BASE_LIVE_BPJS__ : __BASE_STAGING_BPJS__) . $extended_url;
        $data_api = ((__BPJS_MODE__ === 2) ? __DATA_API_LIVE__ : __DATA_API_STAGING__);
        $secretKey_api = ((__BPJS_MODE__ === 2) ? __SECRET_KEY_LIVE_BPJS__ : __SECRET_KEY_DEV_BPJS__);

        date_default_timezone_set('UTC');
        $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
        $signature = hash_hmac('sha256', $data_api ."&". $tStamp , $secretKey_api, true);
        $encodedSignature = base64_encode($signature);
        $headers = array(
            "X-cons-id: " . $data_api ." ",
            "X-timestamp: " .$tStamp ." ",
            "X-signature: " .$encodedSignature,
            "Content-Type: application/json; charset=utf-8",
            "user_key: " . ((__BPJS_MODE__ === 2) ? __USERKEY_LIVE_BPJS__ : __USERKEY_DEV_BPJS__)
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

        $mh = curl_multi_init();
        curl_multi_add_handle($mh, $ch);


        $content = curl_exec($ch);
        $err = curl_error($ch);

        $result = json_decode($content, true);
        $return_value = array("content"=>$result, "error"=>$err);

        return $return_value;
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

    public function decryptor($string, $key) {
        $enc_method = 'AES-256-CBC';
        
        $key_hash = hex2bin(hash('sha256', $key));
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $enc_method, $key_hash, OPENSSL_RAW_DATA, $iv);
        return json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', self::decompress($output)), true);
    }

    public function decompress($string) {
        return \LZCompressor\LZString::decompressFromEncodedURIComponent($string);
    }

    public function postUrl($extended_url, $parameter) {
        //define('form_params', \GuzzleHttp\RequestOptions::FORM_PARAMS );
        
        $url = ((__BPJS_MODE__ === 2) ? __BASE_LIVE_BPJS__ : __BASE_STAGING_BPJS__);
        $data_api = ((__BPJS_MODE__ === 2) ? __DATA_API_LIVE__ : __DATA_API_STAGING__);
        $secretKey_api = ((__BPJS_MODE__ === 2) ? __SECRET_KEY_LIVE_BPJS__ : __SECRET_KEY_DEV_BPJS__);

        date_default_timezone_set('UTC');
        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
        $signature = hash_hmac('sha256', $data_api ."&". $tStamp , $secretKey_api, true);
        $encodedSignature = base64_encode($signature);

        $consid = (__BPJS_MODE__ === 2) ? __DATA_API_LIVE__ : __DATA_API_STAGING__;
        $passwd = (__BPJS_MODE__ === 2) ? __SECRET_KEY_LIVE_BPJS__ : __SECRET_KEY_DEV_BPJS__;
        $keyForDecompress = $consid . $passwd . $tStamp;


        $client = new \GuzzleHttp\Client([
            'base_uri' => $url,
            'verify' => false,
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_SSL_VERIFYHOST => FALSE,
                CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'
            ],
            'body' => json_encode($parameter),
            'headers' => [
                'X-cons-id' => $consid,
                'X-timestamp' => $tStamp,
                'X-signature' => $encodedSignature,
                'Accept' => '*/*',
                //'Content-Type' => "application/x-www-form-urlencoded; charset=UTF-8;",
                'Content-Type' => "text/plain",
                'user_key' => ((__BPJS_MODE__ === 2) ? __USERKEY_LIVE_BPJS__ : __USERKEY_DEV_BPJS__)
            ]
        ]);

        
        
        try{
            $response = $client->postAsync($extended_url)->then(function($resp) {
                return json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $resp->getBody()->getContents()), true);
            }, function (\GuzzleHttp\Exception\RequestException $e) {
                $response = $e->getResponse();
                $responseBodyAsString = $response->getBody()->getContents();
                return array(
                    'message' => $e->getMessage(),
                    'visual' => $responseBodyAsString
                );
            }, function (\GuzzleHttp\Exception\ClientException $e) {
                return 'Client : ' . $e->getMessage();
            });

            $promise = \GuzzleHttp\Promise\settle($response)->wait(true);
            if(intval($promise[0]['value']['metaData']['code']) === 200) {
                $dataList = self::decryptor($promise[0]['value']['response'], $keyForDecompress);
                return array(
                    'metaData' => $promise[0]['value']['metaData'],
                    'data' => $dataList
                );
            } else {
                return array(
                    'metaData' => $promise[0]['value']['metaData'],
                    'data' => $promise
                );
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return $e->getResponse();
        }
        
        //$dataList = self::decryptor($promise[0]['value']['response'], $keyForDecompress);

        // return array(
        //     'metaData' => $promise[0]['value']['metaData'],
        //     'data' => $dataList
        // );

        

        // $headers = array(
        //     'X-cons-id: ' . self::$data_api . ' ',
        //     'X-timestamp: ' . $tStamp . ' ',
        //     'X-signature: ' .$encodedSignature,
        //     "Content-Type" => "application/x-www-form-urlencoded; charset=UTF-8;",
        //     "user_key" => ((__BPJS_MODE__ === 2) ? __USERKEY_LIVE_BPJS__ : __USERKEY_DEV_BPJS__),
        //     'Accept: Application/JSON'
        // );

        // $ch = curl_init();

        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameter));
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');


        // $content = curl_exec($ch);
        // $err = curl_error($ch);

        // $result = json_decode($content, true);
        // $return_value = array("content"=>$result, "error"=>$err);

        // return $return_value;
    }

    public function deleteUrl2($extended_url, $parameter) {
        define('form_params', \GuzzleHttp\RequestOptions::FORM_PARAMS );
        
        $url = ((__BPJS_MODE__ === 2) ? __BASE_LIVE_BPJS__ : __BASE_STAGING_BPJS__);
        $data_api = ((__BPJS_MODE__ === 2) ? __DATA_API_LIVE__ : __DATA_API_STAGING__);
        $secretKey_api = ((__BPJS_MODE__ === 2) ? __SECRET_KEY_LIVE_BPJS__ : __SECRET_KEY_DEV_BPJS__);

        date_default_timezone_set('UTC');
        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
        $signature = hash_hmac('sha256', $data_api ."&". $tStamp , $secretKey_api, true);
        $encodedSignature = base64_encode($signature);

        $consid = (__BPJS_MODE__ === 2) ? __DATA_API_LIVE__ : __DATA_API_STAGING__;
        $passwd = (__BPJS_MODE__ === 2) ? __SECRET_KEY_LIVE_BPJS__ : __SECRET_KEY_DEV_BPJS__;
        $keyForDecompress = $consid . $passwd . $tStamp;


        $client = new \GuzzleHttp\Client([
            'base_uri' => $url,
            'verify' => false,
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_SSL_VERIFYHOST => FALSE,
                CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'
            ],
            'body' => json_encode($parameter),
            'headers' => [
                'X-cons-id' => $consid,
                'X-timestamp' => $tStamp,
                'X-signature' => $encodedSignature,
                'Accept' => '*/*',
                'Content-Type' => "application/x-www-form-urlencoded; charset=UTF-8;",
                //'Content-Type' => "text/plain",
                'user_key' => ((__BPJS_MODE__ === 2) ? __USERKEY_LIVE_BPJS__ : __USERKEY_DEV_BPJS__)
            ]
        ]);

        
        
        try{
            $response = $client->deleteAsync($extended_url)->then(function($resp) {
                return json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $resp->getBody()->getContents()), true);
            }, function (\GuzzleHttp\Exception\RequestException $e) {
                $response = $e->getResponse();
                $responseBodyAsString = $response->getBody()->getContents();
                return array(
                    'message' => $e->getMessage(),
                    'visual' => $responseBodyAsString
                );
            }, function (\GuzzleHttp\Exception\ClientException $e) {
                return 'Client : ' . $e->getMessage();
            });

            $promise = \GuzzleHttp\Promise\settle($response)->wait(true);
            if(intval($promise[0]['metaData']['code']) === 200) {
                return $promise[0];
            } else {
                return $promise[0];
            }
            return $promise[0];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return $e->getResponse();
        }
        // return array(
        //     'metaData' => $promise[0]['value']['metaData'],
        //     'data' => $dataList
        // );
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
        $url = ((__BPJS_MODE__ === 2) ? __BASE_LIVE_BPJS__ : __BASE_STAGING_BPJS__);
        $data_api = ((__BPJS_MODE__ === 2) ? __DATA_API_LIVE__ : __DATA_API_STAGING__);
        $secretKey_api = ((__BPJS_MODE__ === 2) ? __SECRET_KEY_LIVE_BPJS__ : __SECRET_KEY_DEV_BPJS__);

        date_default_timezone_set('UTC');
        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
        $signature = hash_hmac('sha256', $data_api ."&". $tStamp , $secretKey_api, true);
        $encodedSignature = base64_encode($signature);

        $consid = (__BPJS_MODE__ === 2) ? __DATA_API_LIVE__ : __DATA_API_STAGING__;
        $passwd = (__BPJS_MODE__ === 2) ? __SECRET_KEY_LIVE_BPJS__ : __SECRET_KEY_DEV_BPJS__;
        $keyForDecompress = $consid . $passwd . $tStamp;


        $client = new \GuzzleHttp\Client([
            'base_uri' => $url,
            'verify' => false,
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_SSL_VERIFYHOST => FALSE,
                CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'
            ],
            'body' => json_encode($parameter),
            'headers' => [
                'X-cons-id' => $consid,
                'X-timestamp' => $tStamp,
                'X-signature' => $encodedSignature,
                'Accept' => '*/*',
                //'Content-Type' => "application/x-www-form-urlencoded; charset=UTF-8;",
                'Content-Type' => "text/plain",
                'user_key' => ((__BPJS_MODE__ === 2) ? __USERKEY_LIVE_BPJS__ : __USERKEY_DEV_BPJS__)
            ]
        ]);

        
        
        try{
            $response = $client->putAsync($extended_url)->then(function($resp) {
                return json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $resp->getBody()->getContents()), true);
            }, function (\GuzzleHttp\Exception\RequestException $e) {
                $response = $e->getResponse();
                $responseBodyAsString = $response->getBody()->getContents();
                return array(
                    'message' => $e->getMessage(),
                    'visual' => $responseBodyAsString
                );
            }, function (\GuzzleHttp\Exception\ClientException $e) {
                return 'Client : ' . $e->getMessage();
            });

            $promise = \GuzzleHttp\Promise\settle($response)->wait(true);
            if(intval($promise[0]['value']['metaData']['code']) === 200) {
                $dataList = self::decryptor($promise[0]['value']['response'], $keyForDecompress);
                return array(
                    'metaData' => $promise[0]['value']['metaData'],
                    'data' => $dataList
                );
            } else {
                return array(
                    'metaData' => $promise[0]['value']['metaData'],
                    'data' => $promise
                );
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return $e->getResponse();
        }
    }
}
?>