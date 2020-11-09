<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
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
                    return self::get_sep_select2();
                    break;
                case 'get_dpjp':
                    return self::get_dpjp($parameter);
                    break;
                case 'get_faskes_select2':
                    return self::get_faskes_select2();
                    break;
                case 'get_rujukan_list':
                    return self::get_rujukan_list($parameter[2]);
                    break;
                case 'get_kelas_rawat_select2':
                    return self::get_kelas_rawat_select2();
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
                case 'sep':
                    return self::get_sep($parameter);
                    break;
				default:
					return 'Unknown request';
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

    private function get_poli() {
        $content = self::launchUrl('/new-vclaim-rest/referensi/poli/' . $_GET['search']);
        return $content;
    }

	private function get_diagnosa() {
        $content = self::launchUrl('/new-vclaim-rest/referensi/diagnosa/' . $_GET['search']);
        return $content;
    }

    private function get_provinsi() {
        $content = self::launchUrl('/new-vclaim-rest/referensi/propinsi');
        return $content;
    }

    private function get_kabupaten($parameter) {
        $content = self::launchUrl('/new-vclaim-rest/referensi/kabupaten/propinsi/' . $parameter);
        return $content;
    }

    private function get_kecamatan($parameter) {
        $content = self::launchUrl('/new-vclaim-rest/referensi/kecamatan/kabupaten/' . $parameter);
        return $content;
    }

    private function get_spesialistik() {
        $content = self::launchUrl('/new-vclaim-rest/referensi/spesialistik');
        return $content;
    }

    private function get_dpjp($parameter) {
        $content = self::launchUrl('/new-vclaim-rest/referensi/dokter/pelayanan/' . $parameter[2] . '/tglPelayanan/' . date('Y-m-d') . '/Spesialis/' . $parameter[3]);
        return $content;
    }

    private function get_faskes_select2() {
        $content = self::launchUrl('/new-vclaim-rest/referensi/faskes/' . $_GET['search'] . '/' . $_GET['type']);
        return $content;
    }

    private function  get_faskes_info($parameter) {
        $content = self::launchUrl('/new-vclaim-rest/referensi/faskes/' . $parameter['kode'] . '/' . $parameter['type']);
        return $content;
    }

    private function get_kelas_rawat_select2() {
        $content = self::launchUrl('/new-vclaim-rest/referensi/kelasrawat');
        return $content;
    }

    private function get_sep($parameter) {
        $content = self::launchUrl('/new-vclaim-rest/SEP/' . $parameter['search']['value']);
        return $content;
    }

    private function get_sep_select2() {
        $content = self::launchUrl('/new-vclaim-rest/SEP/' . $_GET['search']);
        if($content['metaData']['code'] === '200') {
            return array($content['response']);
        } else {
            return array();
        }
    }

    private function get_rujukan_list($parameter) {
        $content = self::launchUrl('/new-vclaim-rest/Rujukan/List/Peserta/' . $parameter);
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
        $content = self::launchUrl('/new-vclaim-rest/Rujukan/' . $parameter);
        return $content;
    }

    private function sep_baru($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);
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

	    $proceed = self::postUrl('/new-vclaim-rest/SEP/1.1/insert', $parameterBuilder);
        return $proceed;
    }

	private function get_faskes() {
		$hasil = array();
		for($a = 1; $a < 30; $a++) {
			//curl_setopt(self::$ch, CURLOPT_URL, self::$base_url . '/VClaim-rest/referensi/dokter/pelayanan/1/tglPelayanan/' . date('Y-m-d') . '/Spesialis/' . $a);
			curl_setopt(self::$ch, CURLOPT_URL, self::$base_url . '/new-vclaim-rest/referensi/spesialistik');
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
		
		/*curl_setopt(self::$ch, CURLOPT_URL, self::$base_url . '/VClaim-rest/Peserta/nokartu/' . $no_bpjs . '/tglSEP/' . $tglSEP . '');
		$content = curl_exec(self::$ch);*/

        $content = self::launchUrl('/new-vclaim-rest/Peserta/nokartu/' . $no_bpjs . '/tglSEP/' . $tglSEP . '');

		/*$err = curl_error(self::$ch);
		
		if (curl_errno(self::$ch)) {
			$jlh=1;
		}
		else{
			$jlh=0;
		}

		$content = json_decode($content, TRUE);
		$content['response']['peserta']['tglLahir'] = date('d F Y', strtotime($content['response']['peserta']['tglLahir']));
		$content['response']['peserta']['tglCetakKartu'] = date('d F Y', strtotime($content['response']['peserta']['tglCetakKartu']));*/

		return $content;
	}

    public function launchUrl($extended_url) {
        $url = self::$base_url . $extended_url;

        date_default_timezone_set('UTC');
        $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
        $signature = hash_hmac('sha256', self::$data_api ."&". $tStamp , self::$secretKey_api, true);
        $encodedSignature = base64_encode($signature);
        $headers = array(
            "X-cons-id: " . self::$data_api ." ",
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

        $content = curl_exec($ch);
        $err = curl_error($ch);

        $result = json_decode($content, true);
        $return_value = array("content"=>$result, "error"=>$err);

        return $return_value;
    }
}
?>