<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Pasien as Pasien;
use PondokCoder\Pegawai as Pegawai;
use PondokCoder\Anjungan as Anjungan;
use PondokCoder\Poli as Poli;
use PondokCoder\Utility as Utility;

class Invoice extends Utility {
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
					return self::get_biaya_pasien_detail($parameter[2]);
					break;
				case 'payment':
					return self::get_payment($parameter[2]);
					break;
				default:
					return self::get_biaya_pasien();
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function __POST__($parameter = array()) {
		try {
			switch($parameter['request']) {
				case 'proses_bayar':
					return self::proses_bayar($parameter);
					break;
				default:
					return self::get_biaya_pasien();
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	private function get_payment($parameter) {
		$payment = self::$query->select('invoice_payment', array(
			'uid',
			'nomor_kwitansi',
			'pasien',
			'invoice',
			'pegawai',
			'terbayar',
			'sisa_bayar',
			'keterangan',
			'metode_bayar',
			'tanggal_bayar'
		))
		->where(array(
			'invoice_payment.deleted_at' => 'IS NULL',
			'AND',
			'invoice_payment.uid' => '= ?'
		), array(
			$parameter
		))
		->execute();

		foreach ($payment['response_data'] as $key => $value) {
			//get payment detail
			$payment_detail = self::$query->select('invoice_payment_detail', array(
				'id',
				'invoice_payment',
				'item',
				'item_type',
				'qty',
				'harga',
				'subtotal',
				'discount',
				'discount_type',
				'keterangan'
			))
			->where(array(
				'invoice_payment_detail.deleted_at' => 'IS NULL',
				'AND',
				'invoice_payment_detail.invoice_payment' => '= ?'
			), array(
				$parameter
			))
			->execute();
			foreach ($payment_detail['response_data'] as $PDKey => $PDValue) {
				$Item = self::$query->select($PDValue['item_type'], array(
					'nama'
				))
				->where(array(
					$PDValue['item_type'] . '.uid' => '= ?'
				), array(
					$PDValue['item']
				))
				->execute();
				$payment_detail['response_data'][$PDKey]['item'] = $Item['response_data'][0]['nama'];
				$payment_detail['response_data'][$PDKey]['qty'] = floatval($PDValue['qty']);
				$payment_detail['response_data'][$PDKey]['harga'] = floatval($PDValue['harga']);
				$payment_detail['response_data'][$PDKey]['subtotal'] = floatval($PDValue['subtotal']);
				$payment_detail['response_data'][$PDKey]['discount'] = floatval($PDValue['discount']);
			}
			$payment['response_data'][$key]['detail'] = $payment_detail['response_data'];

			//Info Pegawai
			$Pegawai = new Pegawai(self::$pdo);
			$PegawaiInfo = $Pegawai::get_detail($value['pegawai']);
			$payment['response_data'][$key]['pegawai'] = $PegawaiInfo['response_data'][0];
			$payment['response_data'][$key]['tanggal_bayar'] = date("d F Y", strtotime($value['tanggal_bayar']));
			$payment['response_data'][$key]['terbayar'] = floatval($value['terbayar']);
			$payment['response_data'][$key]['sisa_bayar'] = floatval($value['sisa_bayar']);
		}

		return $payment;
	}

	private function proses_bayar($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$newPaymentUID = parent::gen_uuid();

		$totalPayment = 0;
		foreach ($parameter['invoice_item'] as $key => $value) { //Update status bayar pada invoice item
			$getPaymentDetail = self::$query->select('invoice_detail', array(
				'item',
				'item_type',
				'qty',
				'harga',
				'subtotal',
				'discount',
				'discount_type',
				'keterangan',
				'subtotal',
				'status_bayar'
			))
			->where(array(
				'invoice_detail.deleted_at' => 'IS NULL',
				'AND',
				'invoice_detail.id' => '= ?',
				'AND',
				'invoice_detail.invoice' => '= ?'
			), array(
				$value,
				$parameter['invoice']
			))
			->execute();

			//Payment Detail
			$paymentDetail = self::$query->insert('invoice_payment_detail', array(
				'invoice_payment' => $newPaymentUID,
				'item' => $getPaymentDetail['response_data'][0]['item'],
				'item_type' => $getPaymentDetail['response_data'][0]['item_type'],
				'qty' => $getPaymentDetail['response_data'][0]['qty'],
				'harga' => $getPaymentDetail['response_data'][0]['harga'],
				'subtotal' => $getPaymentDetail['response_data'][0]['subtotal'],
				'discount' => $getPaymentDetail['response_data'][0]['discount'],
				'discount_type' => $getPaymentDetail['response_data'][0]['discount_type'],
				'keterangan' => $getPaymentDetail['response_data'][0]['keterangan'],
				'created_at' => parent::format_date(),
				'updated_at' => parent::format_date()
			))
			->execute();

			$totalPayment += floatval($getPaymentDetail['response_data'][0]['subtotal']);

			$updateInvoiceDetail = self::$query->update('invoice_detail', array(
				'status_bayar' => 'Y',
				'updated_at' => parent::format_date()
			))
			->where(array(
				'invoice_detail.deleted_at' => 'IS NULL',
				'AND',
				'invoice_detail.id' => '= ?',
				'AND',
				'invoice_detail.invoice' => '= ?'
			), array(
				$value,
				$parameter['invoice']
			))
			->execute();
			if($updateInvoiceDetail['response_result'] > 0) {
				$newPaymentData = $getPaymentDetail['response_data'][0];
				$newPaymentData['status_bayar'] = 'Y';

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
						$value,
						$UserData['data']->uid,
						'invoice_detail',
						'U',
						json_encode($getPaymentDetail['response_data'][0]),
						json_encode($newPaymentData),
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class' => __CLASS__
				));
			}
		}

		//Invoice before payment
		$InvoicePre = self::$query->select('invoice', array(
			'total_after_discount'
		))
		->where(array(
			'invoice.deleted_at' => 'IS NULL',
			'AND',
			'invoice.uid' => '= ?'
		), array(
			$parameter['invoice']
		))
		->execute();

		//Last Payment
		$paymentCount = self::$query->select('invoice_payment', array(
			'uid'
		))
		->where(array(
			'EXTRACT(month FROM created_at)' => '= ?'
		), array(
			intval(date('m'))
		))
		->execute();

		
		$nomor_kwitansi = 'PBP/' . date('Y/m') . '/' . str_pad(strval(count($paymentCount['response_data']) + 1), 5, '0', STR_PAD_LEFT);
		$worker = self::$query->insert('invoice_payment', array(
			'uid' => $newPaymentUID,
			'invoice' => $parameter['invoice'],
			'nomor_kwitansi' => $nomor_kwitansi,
			'pasien' => $parameter['pasien'],
			'pegawai' => $UserData['data']->uid,
			'terbayar' => $totalPayment,
			'sisa_bayar' => (floatval($InvoicePre['response_data'][0]['total_after_discount']) - $totalPayment),
			'keterangan' => $parameter['keterangan'],
			'metode_bayar' => $parameter['metode'],
			'tanggal_bayar' => (isset($parameter['tanggal'])) ? $parameter['tanggal'] : date("Y-m-d"),
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
					$newPaymentUID,
					$UserData['data']->uid,
					'invoice_payment',
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

	private function get_biaya_pasien() {
		$data = self::$query->select('invoice', array(
			'uid',
			'nomor_invoice',
			'kunjungan',
			'pasien',
			'total_pre_discount',
			'discount',
			'discount_type',
			'total_after_discount',
			'keterangan',
			'created_at',
			'updated_at'
		))
		->where(array(
			'invoice.deleted_at' => 'IS NULL'
		), array())
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {

			$Pasien = new Pasien(self::$pdo);
			$PasienInfo = $Pasien::get_pasien_detail('pasien', $value['pasien']);
			$data['response_data'][$key]['pasien'] = $PasienInfo['response_data'][0];


			$statusLunas = false;
			//Detail Pembayaran
			$InvoiceDetail = self::$query->select('invoice_detail', array(
				'status_bayar'
			))
			->where(array(
				'invoice_detail.invoice' => '= ?',
				'AND',
				'invoice_detail.deleted_at' => 'IS NULL'
			), array(
				$value['uid']
			))
			->execute();
			$IDautonum = 1;
			foreach ($InvoiceDetail['response_data'] as $IDKey => $IDValue) {
				if($IDValue['status_bayar'] == 'Y') {
					$statusLunas = true;
				} else {
					$statusLunas = false;
					break;
				}
			}

			$data['response_data'][$key]['lunas'] = $statusLunas;

			//Antrian Info
			$AntrianKunjungan = self::$query->select('antrian_nomor', array(
				'id',
				'nomor_urut',
				'loket',
				'pegawai',
				'kunjungan',
				'antrian',
				'pasien',
				'poli',
				'status',
				'anjungan',
				'jenis_antrian',
				'dokter',
				'penjamin'
			))
			->where(array(
				'antrian_nomor.kunjungan' => '= ?',
				'AND',
				'antrian_nomor.status' => '= ?'
			), array(
				$value['kunjungan'],
				'K'
			))
			->execute();
			foreach ($AntrianKunjungan['response_data'] as $AKKey => $AKValue) {
				//Info Poliklinik
				$Poli = new Poli(self::$pdo);
				$PoliInfo = $Poli::get_poli_detail($AKValue['poli']);
				$AntrianKunjungan['response_data'][$AKKey]['poli'] = $PoliInfo['response_data'][0];

				//Info Pegawai
				$Pegawai = new Pegawai(self::$pdo);
				$PegawaiInfo = $Pegawai::get_detail($AKValue['pegawai']);
				$AntrianKunjungan['response_data'][$AKKey]['pegawai'] = $PegawaiInfo['response_data'][0];

				//Info Loket
				$Anjungan = new Anjungan(self::$pdo);
				$AnjunganInfo = $Anjungan::get_loket_detail($AKValue['loket']);
				$AntrianKunjungan['response_data'][$AKKey]['loket'] = $AnjunganInfo['response_data'][0];
			}
			$data['response_data'][$key]['antrian_kunjungan'] = $AntrianKunjungan['response_data'][0];

			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}








	private function get_biaya_pasien_detail($parameter) {
		$data = self::$query->select('invoice', array(
			'uid',
			'nomor_invoice',
			'kunjungan',
			'pasien',
			'total_pre_discount',
			'discount',
			'discount_type',
			'total_after_discount',
			'keterangan',
			'created_at',
			'updated_at'
		))
		->where(array(
			'invoice.deleted_at' => 'IS NULL',
			'AND',
			'invoice.uid' => '= ?'
		), array(
			$parameter
		))
		->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {

			$Pasien = new Pasien(self::$pdo);
			$PasienInfo = $Pasien::get_pasien_detail('pasien', $value['pasien']);
			$data['response_data'][$key]['pasien'] = $PasienInfo['response_data'][0];

			//Antrian Info
			$AntrianKunjungan = self::$query->select('antrian_nomor', array(
				'id',
				'nomor_urut',
				'loket',
				'pegawai',
				'kunjungan',
				'antrian',
				'pasien',
				'poli',
				'status',
				'anjungan',
				'jenis_antrian',
				'dokter',
				'penjamin'
			))
			->where(array(
				'antrian_nomor.kunjungan' => '= ?',
				'AND',
				'antrian_nomor.status' => '= ?'
			), array(
				$value['kunjungan'],
				'K'
			))
			->execute();
			foreach ($AntrianKunjungan['response_data'] as $AKKey => $AKValue) {
				//Info Poliklinik
				$Poli = new Poli(self::$pdo);
				$PoliInfo = $Poli::get_poli_detail($AKValue['poli']);
				$AntrianKunjungan['response_data'][$AKKey]['poli'] = $PoliInfo['response_data'][0];

				//Info Pegawai
				$Pegawai = new Pegawai(self::$pdo);
				$PegawaiInfo = $Pegawai::get_detail($AKValue['pegawai']);
				$AntrianKunjungan['response_data'][$AKKey]['pegawai'] = $PegawaiInfo['response_data'][0];

				//Info Loket
				$Anjungan = new Anjungan(self::$pdo);
				$AnjunganInfo = $Anjungan::get_loket_detail($AKValue['loket']);
				$AntrianKunjungan['response_data'][$AKKey]['loket'] = $AnjunganInfo['response_data'][0];
			}
			$data['response_data'][$key]['antrian_kunjungan'] = $AntrianKunjungan['response_data'][0];

			//Detail Pembayaran
			$InvoiceDetail = self::$query->select('invoice_detail', array(
				'id',
				'invoice',
				'item',
				'item_type',
				'qty',
				'status_bayar',
				'harga',
				'subtotal',
				'discount',
				'discount_type',
				'keterangan',
				'created_at',
				'updated_at'
			))
			->where(array(
				'invoice_detail.invoice' => '= ?',
				'AND',
				'invoice_detail.deleted_at' => 'IS NULL'
			), array(
				$parameter
			))
			->execute();
			$IDautonum = 1;
			foreach ($InvoiceDetail['response_data'] as $IDKey => $IDValue) {
				//Item parse
				$Item = self::$query->select($IDValue['item_type'], array(
					'nama'
				))
				->where(array(
					$IDValue['item_type'] . '.uid' => '= ?'
				), array(
					$IDValue['item']
				))
				->execute();
				$InvoiceDetail['response_data'][$IDKey]['item'] = $Item['response_data'][0];
				$InvoiceDetail['response_data'][$IDKey]['qty'] = floatval($IDValue['qty']);
				$InvoiceDetail['response_data'][$IDKey]['harga'] = floatval($IDValue['harga']);
				$InvoiceDetail['response_data'][$IDKey]['discount'] = floatval($IDValue['discount']);
				$InvoiceDetail['response_data'][$IDKey]['subtotal'] = floatval($IDValue['subtotal']);

				$InvoiceDetail['response_data'][$IDKey]['autonum'] = $IDautonum;
				$IDautonum++;
			}
			$data['response_data'][$key]['invoice_detail'] = $InvoiceDetail['response_data'];



			//History payment
			$history = self::$query->select('invoice_payment', array(
				'uid',
				'nomor_kwitansi',
				'invoice',
				'pegawai',
				'pasien',
				'terbayar',
				'sisa_bayar',
				'keterangan',
				'metode_bayar',
				'tanggal_bayar'
			))
			->where(array(
				'invoice_payment.deleted_at' => 'IS NULL',
				'AND',
				'invoice_payment.pasien' => '= ?'
			), array(
				$value['pasien']
			))
			->execute();
			$Hautonum = 1;
			foreach ($history['response_data'] as $HKey => $HValue) {
				$Pegawai = new Pegawai(self::$pdo);
				$PegawaiInfo = $Pegawai::get_detail($AKValue['pegawai']);

				$history['response_data'][$HKey]['tanggal_bayar'] = date('d F Y', strtotime($HValue['tanggal_bayar']));
				$history['response_data'][$HKey]['pegawai'] = $PegawaiInfo['response_data'][0];
				$history['response_data'][$HKey]['autonum'] = $Hautonum;

				$Hautonum++;
			}
			$data['response_data'][$key]['history'] = $history['response_data'];

			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}
		return $data;
	}

	public function create_invoice($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		//GET Last Invoice
		$lastNumber = self::$query->select('invoice', array(
			'nomor_invoice'
		))
		->where(array(
			'EXTRACT(month FROM created_at)' => '= ?'
		), array(
			intval(date('m'))
		))
		->execute();

		$InvoiceUID = parent::gen_uuid();
		$Invoice = self::$query->insert('invoice', array(
			'uid' => $InvoiceUID,
			'nomor_invoice' => 'INV/' . date('Y/m') . '/' . str_pad(strval(count($lastNumber['response_data']) + 1), 4, '0', STR_PAD_LEFT),
			'kunjungan' => $parameter['kunjungan'],
			'pasien' => $parameter['pasien'],
			'keterangan' => $parameter['keterangan'],
			'created_at' => parent::format_date(),
			'updated_at' => parent::format_date()
		))
		->execute();

		if($Invoice['response_result'] > 0) {
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
					$InvoiceUID,
					$UserData['data']->uid,
					'invoice',
					'I',
					parent::format_date(),
					'N',
					$UserData['data']->log_id
				),
				'class' => __CLASS__
			));
		}
		$Invoice['response_unique'] = $InvoiceUID;
		return $Invoice;
	}

	public function append_invoice($parameter) {
		$Invoice = self::$query->insert('invoice_detail', array(
			'invoice' => $parameter['invoice'],
			'item' => $parameter['item'],
			'item_type' => $parameter['item_origin'],
			'qty' => $parameter['qty'],
			'harga' => $parameter['harga'],
			'subtotal' => $parameter['subtotal'],
			'discount' => $parameter['discount'],
			'discount_type' => $parameter['discount_type'],
			'keterangan' => $parameter['keterangan'],
			'created_at' => parent::format_date(),
			'updated_at' => parent::format_date()
		))
		->returning('id')
		->execute();
		if($Invoice['response_result'] > 0) {
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
					$Invoice['response_unique'],
					$UserData['data']->uid,
					'invoice_detail',
					'I',
					parent::format_date(),
					'N',
					$UserData['data']->log_id
				),
				'class' => __CLASS__
			));


			//Calculate Invoice Master Total
			$masterInvoice = self::$query->select('invoice', array(
				'total_pre_discount',
				'discount',
				'discount_type',
				'total_after_discount'
			))
			->where(array(
				'invoice.deleted_at' => 'IS NULL',
				'AND',
				'invoice.uid' => '= ?'
			), array(
				$parameter['invoice']
			))
			->execute();
			$total_after_discount = 0;
			$total_pre_discount = $masterInvoice['response_data'][0]['total_pre_discount'] + $parameter['subtotal'];
			if($masterInvoice['response_data'][0]['discount_type'] == 'P') {
				$total_after_discount = $total_pre_discount - ($masterInvoice['response_data'][0]['discount'] / 100 * $total_pre_discount);
			} else if($masterInvoice['response_data'][0]['discount_type'] == 'A') {
				$total_after_discount = $total_pre_discount - $masterInvoice['response_data'][0]['discount'];
			} else {
				$total_after_discount = $total_pre_discount;
			}

			$updateInvoice = self::$query->update('invoice', array(
				'total_pre_discount' => $total_pre_discount,
				'total_after_discount' => $total_after_discount,
				'updated_at' => parent::format_date()
			))
			->where(array(
				'invoice.deleted_at' => 'IS NULL',
				'AND',
				'invoice.uid' => '= ?'
			), array(
				$parameter['invoice']
			))
			->execute();

			if($updateInvoice['response_result'] > 0) {
				$log = parent::log(array(
					'type' => 'activity',
					'column' => array(
						'unique_target',
						'user_uid',
						'table_name',
						'action',
						'logged_at',
						'old_value',
						'new_value',
						'status',
						'login_id'
					),
					'value' => array(
						$Invoice['response_unique'],
						$UserData['data']->uid,
						'invoice_detail',
						'U',
						parent::format_date(),
						json_encode($masterInvoice['response_data'][0]),
						json_encode($parameter),
						'N',
						$UserData['data']->log_id
					),
					'class' => __CLASS__
				));
			}	
		}

		return $Invoice;
	}

	public function get_harga_tindakan($parameter) {
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
			$parameter['poli'],
			$parameter['tindakan'],
			$parameter['penjamin']
		))
		->execute();
		return $harga;
	}
}