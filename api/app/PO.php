<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Inventori as Inventori;
use PondokCoder\Utility as Utility;


class PO extends Utility {
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
				case 'detail':
					return self::get_po_detail($parameter[2]);
					break;
				default:
					return self::get_po($parameter[2]);
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function __POST__($parameter = array()) {
		switch ($parameter['request']) {
			case 'tambah_po':
				return self::tambah_po($parameter);
				break;
			case 'edit_po':
				return self::edit_po($parameter);
				break;
			default:
				break;
		}
	}

	private function tambah_po($parameter) {
		$result = array();
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		
		$latestPO = self::$query->select('inventori_po', array(
			'uid'
		))
		->where(array(
			'EXTRACT(MONTH FROM created_at)' => '= ?'
		), array(
			intval(date('m'))
		))
		->execute();
		
		$set_code = 'PO/' . str_pad(date('m'), 2, '0', STR_PAD_LEFT) . '/'. str_pad(count($latestPO['response_data']) + 1, 4, '0', STR_PAD_LEFT);

		$uid = parent::gen_uuid();
		$worker = self::$query->insert('inventori_po', array(
			'uid' => $uid,
			'supplier' => $parameter['supplier'],
			'pegawai' => $UserData['data']->uid,
			'total' => 0,
			'disc' => floatval($parameter['diskonAll']),
			'disc_type' => $parameter['diskonJenisAll'],
			'total_after_disc' => 0,
			'nomor_po' => $set_code,
			'tanggal_po' => date("Y-m-d", strtotime($parameter['tanggal'])),
			'created_at' => parent::format_date(),
			'updated_at' => parent::format_date()
		))
		->execute();
		if($worker['response_result'] > 0) {
			$PODetailError = array();
			//Detail
			foreach (json_decode($parameter['itemList'], true) as $key => $value) {

				$ObatDetail = new Inventori(self::$pdo);
				$ObatInfo = $ObatDetail::get_item_detail($value['item'])['response_data'][0];
				$subtotal = 0;

				if($value['jenis_diskon'] == 'N') {
					$subtotal = floatval($value['qty']) * floatval($value['harga']);
				} else if($value['jenis_diskon'] == 'P') {
					$subtotal = floatval($value['qty']) * floatval($value['harga']) - (floatval($value['diskon']) / 100 * (floatval($value['qty']) * floatval($value['harga'])));
				} else {
					$subtotal = (floatval($value['qty']) * floatval($value['harga'])) - floatval($value['diskon']);
				}

				$po_detail = self::$query->insert('inventori_po_detail', array(
					'po' => $uid,
					'barang' => $value['item'],
					'qty' => floatval($value['qty']),
					'satuan' => $ObatInfo['satuan_terkecil'],
					'harga' => floatval($value['harga']),
					'disc' => $value['diskon'],
					'disc_type' => $value['jenis_diskon'],
					'subtotal' => floatval($subtotal),
					'keterangan' => $value['keterangan']
				))
				->execute();
				array_push($PODetailError, $po_detail);
			}

			$result['po_detail'] = $PODetailError;

			if(is_writeable('../document/po')) {
				$set_code = str_replace('/', '_', $set_code);
				$result['response_upload'] = array();
				//$imageDatas = json_decode($_FILES['fileList'], true);
				for ($a = 0; $a < count($_FILES['fileList']); $a++) {
					if(!empty($_FILES['fileList']['tmp_name'][$a])) {
						if(move_uploaded_file($_FILES['fileList']['tmp_name'][$a], '../document/po/' . $set_code . '-' . ($a + 1) . '.pdf')) {
							array_push($result['response_upload'], 'Berhasil diupload');
						} else {
							array_push($result['response_upload'], 'Gagal diupload : ' . $_FILES['fileList']['tmp_name'][$a] . ' => ' . $set_code . '-' . $a . '.pdf');
						}
					}
				}
			} else {
				$result['response_upload'] = 'Cant write';
			}
		}
		$result['po_master'] = $worker;
		return $result;
	}

	private function edit_po($parameter) {
		//
	}
}