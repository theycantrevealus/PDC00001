<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Inventori as Inventori;
use PondokCoder\Pegawai as Pegawai;
use PondokCoder\Supplier as Supplier;
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
				case 'view':
					return self::get_po_info($parameter[2]);
					break;
				default:
					return self::get_po();
					break;
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

	private function get_po() {
		$data = self::$query->select('inventori_po', array(
			'uid',
			'nomor_po',
			'pegawai',
			'tanggal_po',
			'total',
			'total_after_disc',
			'supplier',
			'keterangan'
		))
		->where(array(
			'inventori_po.deleted_at' => 'IS NULL'
		))
		->execute();
		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			//Check Barang sudah sampai atau belum
			$PODetail = self::get_po_detail($value['uid'])['response_data'];
			foreach ($PODetail as $POKey => $POValue) {
				$PODetail[$POKey]['qty'] = floatval($POValue['qty']);
				$PODetail[$POKey]['harga'] = floatval($POValue['harga']);
				$PODetail[$POKey]['disc'] = floatval($POValue['disc']);
				$PODetail[$POKey]['subtotal'] = floatval($POValue['subtotal']);

				//Check DO
				$countBarang = 0;
				$checkDO = self::$query->select('inventori_do_detail', array(
					'qty'
				))
				->where(array(
					'inventori_do_detail.po' => '= ?',
					'AND',
					'inventori_do_detail.barang' => '= ?'
				), array(
					$value['uid'],
					$POValue['uid_barang']
				))
				->execute();

				foreach ($checkDO['response_data'] as $CDOKey => $CDOValue) {
					$countBarang += floatval($CDOValue['qty']);
				}

				$PODetail[$POKey]['sampai'] = $countBarang;
			}
			$data['response_data'][$key]['detail'] = $PODetail;

			$Pegawai = new Pegawai(self::$pdo);
			$InfoPegawai = $Pegawai::get_detail($value['pegawai']);

			$Supplier = new Supplier(self::$pdo);
			$InfoSupplier = $Supplier::get_detail($value['supplier']);
			
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['tanggal_po'] = date("d F Y", strtotime($value['tanggal_po']));
			$data['response_data'][$key]['pegawai'] = $InfoPegawai['response_data'][0];
			$data['response_data'][$key]['supplier'] = $InfoSupplier;
			$autonum++;
		}

		return $data;
	}

	public function get_po_detail($parameter){
		$data = self::$query
			->select('inventori_po_detail', array(
					'id',
					'po as uid_po',
					'barang as uid_barang',
					'qty',
					'satuan',
					'harga',
					'disc',
					'disc_type',
					'subtotal',
					'keterangan'
				)
			)
			->where(array(
					'inventori_po_detail.po' => '= ?'
				), array(
					$parameter
				)
			)
			->execute();

		return $data;
	}

	public function get_po_item_price($parameter = array()){
		$data = self::$query
			->select('inventori_po_detail', array(
					'harga',
					'disc',
					'disc_type',
					'subtotal'
				)
			)
			->where(array(
					'inventori_po_detail.po' => '= ?',
					'AND',
					'inventori_po_detail.barang' => '= ?'
				), array(
					$parameter[0],
					$parameter[1]
				)
			)
			->execute();

		return $data;
	}

	public function get_po_detail_barang($parameter){
		$data = self::$query
			->select('inventori_po_detail', array(
					'id',
					'po as uid_po',
					'barang as uid_barang',
					'qty',
					'satuan',
					'harga',
					'disc',
					'disc_type',
					'subtotal',
					'keterangan'
				)
			)
			->where(array(
					'inventori_po_detail.po' => '= ?',
					'AND',
					'inventori_po_detail.barang' => '= ?'
				), array(
					$parameter[1],
					$parameter[2]
				)
			)
			->execute();

		return $data;
	}

	public function get_po_info($parameter) {
		$po = self::$query->select('inventori_po', array(
			'uid',
			'nomor_po',
			'supplier',
			'pegawai',
			'total',
			'disc',
			'disc_type',
			'total_after_disc',
			'tanggal_po',
			'keterangan',
			'created_at',
			'updated_at'
		))
		->where(array(
			'inventori_po.deleted_at' => 'IS NULL',
			'AND',
			'inventori_po.uid' => '= ?'
		), array(
			$parameter
		))
		->execute();

		foreach ($po['response_data'] as $key => $value) {
			$Pegawai = new Pegawai(self::$pdo);
			$InfoPegawai = $Pegawai::get_detail($value['pegawai']);

			$Supplier = new Supplier(self::$pdo);
			$InfoSupplier = $Supplier::get_detail($value['supplier']);
			
			$po['response_data'][$key]['autonum'] = $autonum;
			$po['response_data'][$key]['tanggal_po'] = date("d F Y", strtotime($value['tanggal_po']));
			$po['response_data'][$key]['pegawai'] = $InfoPegawai['response_data'][0];
			$po['response_data'][$key]['supplier'] = $InfoSupplier;
		}
			

		if(count($po['response_data'])) {
			$po_detail = self::get_po_detail($parameter);
			foreach ($po_detail['response_data'] as $key => $value) {
				$Inventori = new Inventori(self::$pdo);
				$InventoriInfo = $Inventori::get_item_detail($value['uid_barang'])['response_data'][0];
				$InventoriSatuan = $Inventori::get_satuan_detail($InventoriInfo['satuan_terkecil'])['response_data'][0];
				$po_detail['response_data'][$key]['detail'] = $InventoriInfo;
				$po_detail['response_data'][$key]['detail']['satuan_caption'] = $InventoriSatuan;

			}
			$po['response_data'][0]['item'] = $po_detail['response_data'];


			//getDocument
			$document = self::$query->select('inventori_po_document', array(
				'id',
				'po',
				'document_name'
			))
			->where(array(
				'inventori_po_document.po' => '= ?'
			), array(
				$parameter
			))
			->execute();
			$po['response_data'][0]['document'] = $document['response_data'];
		}

		return $po;
	}

	private function tambah_po($parameter) {
		$result = array();
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$ObatDetail = new Inventori(self::$pdo);
		
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
		$total = 0;
		foreach (json_decode($parameter['itemList'], true) as $key => $value) {
			$subtotal = 0;
			if($value['jenis_diskon'] == 'N') {
				$subtotal = floatval($value['qty']) * floatval($value['harga']);
			} else if($value['jenis_diskon'] == 'P') {
				$subtotal = floatval($value['qty']) * floatval($value['harga']) - (floatval($value['diskon']) / 100 * (floatval($value['qty']) * floatval($value['harga'])));
			} else {
				$subtotal = (floatval($value['qty']) * floatval($value['harga'])) - floatval($value['diskon']);
			}

			$total += $subtotal;
		}

		if($parameter['diskonJenisAll'] == 'N') {
			$AllSubtotal = floatval($total);
		} else if($parameter['diskonJenisAll'] == 'P') {
			$AllSubtotal = floatval($total) - (floatval($parameter['diskonAll']) / 100 * (floatval($total)));
		} else {
			$AllSubtotal = $total - floatval($parameter['diskonAll']);
		}

		$uid = parent::gen_uuid();
		$worker = self::$query->insert('inventori_po', array(
			'uid' => $uid,
			'supplier' => $parameter['supplier'],
			'pegawai' => $UserData['data']->uid,
			'total' => $total,
			'disc' => floatval($parameter['diskonAll']),
			'disc_type' => $parameter['diskonJenisAll'],
			'total_after_disc' => $AllSubtotal,
			'nomor_po' => $set_code,
			'keterangan' => $parameter['keteranganAll'],
			'tanggal_po' => date("Y-m-d", strtotime($parameter['tanggal'])),
			'created_at' => parent::format_date(),
			'updated_at' => parent::format_date()
		))
		->execute();
		if($worker['response_result'] > 0) {
			$PODetailError = array();
			
			
			//Detail
			foreach (json_decode($parameter['itemList'], true) as $key => $value) {
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
					'disc' => floatval($value['diskon']),
					'disc_type' => $value['jenis_diskon'],
					'subtotal' => floatval($subtotal),
					'keterangan' => $value['keterangan']
				))
				->execute();
				if($po_detail['response_result'] <= 0) {
					array_push($PODetailError, $po_detail);	
				}
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
							$document_po = self::$query->insert('inventori_po_document', array(
								'po' => $uid,
								'document_name' => $set_code . '-' . ($a + 1) . '.pdf'
							))
							->execute();
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