<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Pasien as Pasien;
use PondokCoder\Pegawai as Pegawai;
use PondokCoder\Penjamin as Penjamin;
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
				case 'kwitansi':
					return self::get_kwitansi($parameter);
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
				case 'retur_biaya':
					return self::retur_biaya($parameter);
					break;
				case 'kwitansi_data':
					return self::get_kwitansi($parameter);
					break;
				default:
					return self::get_biaya_pasien();
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	private function get_kwitansi($parameter) {
		if(isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
			$paramData = array(
				'invoice_payment.deleted_at' => 'IS NULL',
				'AND',
				'invoice_payment.tanggal_bayar' => 'BETWEEN ? AND ?',
				'AND',
				'invoice_payment.nomor_kwitansi' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
			);

			$paramValue = array(
				$parameter['from'], $parameter['to']
			);
		} else {
			$paramData = array(
				'invoice_payment.deleted_at' => 'IS NULL',
				'AND',
				'invoice_payment.tanggal_bayar' => 'BETWEEN ? AND ?'
			);

			$paramValue = array(
				$parameter['from'], $parameter['to']
			);
		}

		if($parameter['length'] < 0) {
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
			->where($paramData, $paramValue)
			->execute();
		} else {
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
			->where($paramData, $paramValue)
			->offset(intval($parameter['start']))
			->limit(intval($parameter['length']))
			->execute();
		}
			
		$payment['response_draw'] = $parameter['draw'];
		$autonum = 1;
		foreach ($payment['response_data'] as $key => $value) {
			if(!isset($value['pasien']) || !isset($value['pegawai'])) {
				unset($payment['response_data'][$key]);
			} else {
				$Pegawai = new Pegawai(self::$pdo);
				$PegawaiInfo = $Pegawai::get_detail($value['pegawai']);
				$payment['response_data'][$key]['pegawai'] = $PegawaiInfo['response_data'][0];

				$Pasien = new Pasien(self::$pdo);
				$PasienInfo = $Pasien::get_pasien_detail('pasien', $value['pasien']);
				$payment['response_data'][$key]['pasien'] = $PasienInfo['response_data'][0];
				
				$payment['response_data'][$key]['terbayar'] = number_format($value['terbayar'], 2, '.', ',');
				$payment['response_data'][$key]['autonum'] = $autonum;
				$autonum++;
			}
		}
		
		$paymentTotal = self::$query->select('invoice_payment', array(
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
		->where($paramData, $paramValue)
		->execute();

		$payment['recordsTotal'] = count($paymentTotal['response_data']);
		$payment['recordsFiltered'] = count($paymentTotal['response_data']);
		$payment['length'] = intval($parameter['length']);
		$payment['start'] = intval($parameter['start']);

		return $payment;
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
				'keterangan',
				'status'
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
				$payment_detail['response_data'][$PDKey]['item_uid'] = $PDValue['item'];
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
			$Pasien = new Pasien(self::$pdo);
			$PasienInfo = $Pasien::get_pasien_detail('pasien', $value['pasien']);
			$payment['response_data'][$key]['pasien'] = $PasienInfo['response_data'][0];
		}

		return $payment;
	}

	private function proses_bayar($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);
		$KunjunganUID = $parameter['kunjungan'];

		$newPaymentUID = parent::gen_uuid();
		$allowAntrian = false;
		$totalPayment = 0;

		$ResepMaster = self::$query->select('resep', array(
			'uid'
		))
		->where(array(
			'resep.kunjungan' => '= ?',
			'AND',
			'resep.pasien' => '= ?',
			'AND',
			'resep.status_resep' => '= ?',
			'AND',
			'resep.deleted_at' => 'IS NULL',
		), array(
			$parameter['kunjungan'],
			$parameter['pasien'],
			'K'
		))
		->execute();

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
				'status_bayar',
				'pasien'
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
				'penjamin' => $parameter['penjamin'],
				'pasien' => $parameter['pasien'],
				'created_at' => parent::format_date(),
				'updated_at' => parent::format_date()
			))
			->execute();

			if($paymentDetail['response_result'] > 0) { //Berhasil Bayar

				//Jika Resep maka ubah status jadi lunas agar apotek bisa proses obat
				if(
					count($ResepMaster['response_data']) > 0 &&
					$getPaymentDetail['response_data'][0]['item_type'] == 'master_inv'
				) { //Obat
					$updateResep = self::$query->update('resep_detail', array(
						'status' => 'L'
					))
					->where(array(
						'resep_detail.obat' => '= ?',
						'AND',
						'resep_detail.resep' => '= ?',
						'AND',
						'resep_detail.deleted_at' => 'IS NULL'
					), array(
						$getPaymentDetail['response_data'][0]['item'],
						$ResepMaster['response_data'][0]['uid']
					))
					->execute();
				}

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
		}

		if($parameter['penjamin'] == __UIDPENJAMINUMUM__) {
			foreach ($parameter['invoice_item'] as $key => $value) {
				//Check Pembayaran Kartu dan Konsultasi
				$getPaymentResult = self::$query->select('invoice_detail', array(
					'item',
					'item_type',
					'qty',
					'harga',
					'subtotal',
					'discount',
					'discount_type',
					'keterangan',
					'subtotal',
					'penjamin',
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
				if($getPaymentResult['response_data'][0]['item'] != __UID_KARTU__)  {
					if(
						$getPaymentResult['response_data'][0]['item'] == __UID_KONSULTASI__ &&
						$getPaymentResult['response_data'][0]['item_type'] == 'master_tindakan' &&
						$getPaymentResult['response_data'][0]['status_bayar'] == 'Y'
					) {
						$allowAntrian = true;
					} else {
						$allowAntrian = false;
						break;
					}
				}
			}
		} else {
			$allowAntrian = true;
		}

		$checkStatusPasien = self::$query->select('antrian', array(
			'uid'
		))
		->where(array(
			'antrian.pasien' => '= ?',
			'AND',
			'antrian.deleted_at' => 'IS NULL'
		), array(
			$parameter['pasien']
		))
		->execute();
		if($allowAntrian) {
			if(count($checkStatusPasien['response_data']) > 0) {
				$allowAntrian = true;
			} else {
				$checkPayment = self::$query->select('invoice_payment_detail', array(
					'id'
				))
				->where(array(
					'invoice_payment_detail.pasien' => '= ?',
					'AND',
					'invoice_payment_detail.item' => '= ?',
					'AND',
					'invoice_payment_detail.item_type' => '= ?',
					'AND',
					'invoice_payment_detail.deleted_at' => 'IS NULL'
				), array(
					$parameter['pasien'],
					__UID_KARTU__,
					'master_tindakan'
				))
				->execute();

				if(count($checkPayment['response_data']) > 0) {
					$allowAntrian = true;
				} else {
					$allowAntrian = false;
				}
			}
		}

		//Update Invoice Discount and Total First
		//$parameter['discount']
		//$parameter['discount_type']
		
		if(count($checkStatusPasien['response_data']) > 0) {
			$UpdateResepMaster = self::$query->update('resep', array(
				'status_resep' => 'L',
				'updated_at' => parent::format_date()
			))
			->where(array(
				'resep.kunjungan' => '= ?',
				'AND',
				'resep.pasien' => '= ?',
				'AND',
				'resep.uid' => '= ?',
				'AND',
				'resep.deleted_at' => 'IS NULL'
			), array(
				$KunjunganUID,
				$parameter['pasien'],
				$ResepMaster['response_data'][0]['uid']
			))
			->execute();
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

			

		if($totalPayment > 0) {
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

				if($allowAntrian == true) {
					//Pembayaran Kartu Non Umum Segera masukkan pada antrian poliklinik
					$KunjunganData = self::$query->select('antrian_nomor', array(
						'dokter'
					))
					->where(array(
						'antrian_nomor.pasien' => '= ?',
						'AND',
						'antrian_nomor.kunjungan' => '= ?'
					), array(
						$parameter['pasien'],
						$parameter['kunjungan']
					))
					->execute();

					$Antrian = new Antrian(self::$pdo);
					$parameter['dataObj'] = array(
						'departemen' => $parameter['poli'],
						'pasien' => $parameter['pasien'],
						'penjamin' => $parameter['penjamin'],
						'prioritas' => 36,
						'dokter' => $KunjunganData['response_data'][0]['dokter']
					);
					$AntrianProses = $Antrian::tambah_antrian('antrian', $parameter, $parameter['kunjungan']);
				}
			}
			return $worker;
		} else {
			return $allowAntrian;
		}
			
	}

	private function retur_biaya($parameter){
		$PaymentUID = parent::gen_uuid();
		//Get Invoice
		$InvoicePayment = self::$query->select('invoice_payment', array(
			'uid'
		))
		->where(array(
			'invoice_payment.invoice' => '= ?',
			'AND',
			'invoice_payment.uid' => '= ?'
		), array(
			$parameter['invoice'],
			$parameter['payment']
		))
		->execute();
		if(count($InvoicePayment['response_data'])) {
			$detailUpdate = array();
			foreach ($parameter['item'] as $key => $value) {
				$worker = self::$query->update('invoice_payment_detail', array(
					'status' => 'R'
				))
				->where(array(
					'invoice_payment_detail.invoice_payment' => '= ?',
					'AND',
					'invoice_payment_detail.item' => '= ?'
				), array(
					$InvoicePayment['response_data'][0]['uid'],
					$value
				))
				->execute();
				array_push($detailUpdate, $worker);
			}
			return $detailUpdate;
		} else {
			//
		}
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

			//Asesmen Information
			$Asesmen = self::$query->select('asesmen', array(
				'status'
			))
			->where(array(
				'asesmen.kunjungan' => '= ?'
			), array(
				$value['kunjungan']
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
				'penjamin',
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
					'uid',
					'nama'
				))
				->where(array(
					$IDValue['item_type'] . '.uid' => '= ?'
				), array(
					$IDValue['item']
				))
				->execute();

				$Penjamin = new Penjamin(self::$pdo);
				$PenjaminInfo = $Penjamin::get_penjamin_detail($IDValue['penjamin']);
				$InvoiceDetail['response_data'][$IDKey]['penjamin'] = $PenjaminInfo['response_data'][0];

				$InvoiceDetail['response_data'][$IDKey]['item'] = $Item['response_data'][0];
				$InvoiceDetail['response_data'][$IDKey]['item']['allow_retur'] = ($IDValue['item'] == __UID_KARTU__) ? false : true;
				$InvoiceDetail['response_data'][$IDKey]['status_berobat'] = $Asesmen['response_data'][0];
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
			'status_bayar' => isset($parameter['status_bayar']) ? $parameter['status_bayar'] : 'N',
			'subtotal' => $parameter['subtotal'],
			'discount' => $parameter['discount'],
			'discount_type' => $parameter['discount_type'],
			'pasien' => $parameter['pasien'],
			'penjamin' => $parameter['penjamin'],
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
		/*$harga = self::$query->select('master_poli_tindakan_penjamin', array(
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
		->execute();*/

		return self::$query->select('master_tindakan_kelas_harga', array(
			'id',
			'tindakan',
			'penjamin',
			'kelas',
			'harga',
			'created_at',
			'updated_at'
		))
		->where(array(
			'master_tindakan_kelas_harga.deleted_at' => 'IS NULL',
			'AND',
			'master_tindakan_kelas_harga.tindakan' => '= ?',
			'AND',
			'master_tindakan_kelas_harga.penjamin' => '= ?',
			'AND',
			'master_tindakan_kelas_harga.kelas' => '= ?'
		), array(
			$parameter['tindakan'],
			$parameter['penjamin'],
			$parameter['kelas']
		))
		->execute();;
	}
}