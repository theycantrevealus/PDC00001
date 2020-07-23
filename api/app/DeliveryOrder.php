<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;
use PondokCoder\PO as PO;
use PondokCoder\Supplier as Supplier;
use PondokCoder\Inventori as Inventori;
use PondokCoder\Pegawai as Pegawai;

class DeliveryOrder extends Utility {
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

				/*case 'get_penjamin_tindakan':
					return self::get_penjamin_tindakan($parameter[2]);
					break;*/
				case 'load-po-available':
					return self::load_po_available();
					break;

				case 'load-po-supplier':
					return self::load_po_supplier($parameter[2]);
					break;

				case 'load-do-po':
					return self::get_do_where_po($parameter[2]);
					break;

				default:
					return self::get_do();
					break;
			}
		} catch (QueryException $e) {
			return 'Error => '. $e;
		}
	}

	public function __POST__($parameter = array()){
		switch ($parameter['request']) {
			case 'tambah-do':
				return self::tambah_do($parameter);
				break;

			default:
				# code...
				break;
		}
	}

	public function __DELETE__($parameter = array()){
		return self::delete_penjamin($parameter);
	}


	/*=======================GET FUNCTION======================*/
	private function get_do(){
		$data = self::$query
			->select('inventori_do', array(
					'uid',
					'gudang',
					'supplier',
					'waktu_input',
					'no_dokumen',
					'tgl_dokumen',
					'no_do',
					'no_invoice',
					'tgl_invoice',
					'keterangan',
					'status',
					'pegawai',
					'created_at',
					'updated_at'
				)
			)
			->where(array(
					'deleted_at' => 'IS NULL'
				),array()
			)
			->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;

			$supplier = new Supplier(self::$pdo);
			$arr = ['','detail', $value['supplier']];
			$get_supplier = $supplier->__GET__($arr);
			$data['response_data'][$key]['nama_supplier'] = $get_supplier['nama'];

			$inventori = new Inventori(self::$pdo);
			$arr = ['', 'gudang_detail', $value['gudang']];
			$get_gudang = $inventori->__GET__($arr);
			$data['response_data'][$key]['nama_gudang'] = $get_gudang['response_data'][0]['nama'];

			$pegawai = new Pegawai(self::$pdo);
			$get_pegawai = $pegawai->get_detail($value['pegawai']);
			$data['response_data'][$key]['nama_pegawai'] = $get_pegawai['response_data'][0]['nama'];
		}

		return $data;
	}

	private function get_do_detail($parameter){
		$data = self::$query
			->select('inventori_do_detail', array(
					'id',
					'uid_do',
					'barang',
					'kode_batch',
					'kedaluarsa',
					'qty',
					'satuan',
					'keterangan',
					'created_at',
					'updated_at',
				)
			)
			->where(array(
					'inventori_do_detail.deleted_at' => 'IS NULL',
					'AND',
					'inventori_do_detail.uid_do' => '= ?'
				), array(
					$parameter
				)
			)
			->execute();

		return $data;
	}

	private function get_do_where_po($parameter){
		$data = self::$query
			->select('inventori_do', array(
					'uid',
					'po',
					'gudang',
					'supplier',
					'waktu_input',
					'no_dokumen',
					'tgl_dokumen',
					'no_do',
					'no_invoice',
					'tgl_invoice',
					'keterangan',
					'status',
					'pegawai',
					'created_at',
					'updated_at'
				)
			)
			->where(array(
					'deleted_at' => 'IS NULL',
					'AND',
					'po' => '= ?'
				),array(
					$parameter
				)
			)
			->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;
		}

		return $data;
	}	

	/*private function load_po(){
		$po = new PO(self::$pdo);
		$dataPO = $po->get_po();
		$test = [];

		//check po result
		if ($dataPO['response_result'] > 0){

			//load po response_data
			foreach ($dataPO['response_data'] as $dataKey => $value) {
				//$dataPO['response_data'][$dataKey]['items'] = [];

				//get from inv_do where row has inv_po uid
				$dataDO = self::get_do_where_po($value['uid']);
				$itemPO = $po->get_po_detail($value['uid']);

				//if inv_do row has inv_po uid
				if ($dataDO['response_result'] > 0){
					foreach ($dataDO['response_data'] as $key => $items) {
						//get inv_do_detail
						$itemDO = self::get_do_detail($items['uid']);

						$test = [$itemPO['response_data'], $itemDO['response_data']];
						//if inv_do_detail row has inv_do uid
						if ($itemDO['response_result'] > 0){
							//check if itemDO is in itemPO
							foreach ($itemDO as $doKey => $doItems) {
								//load itemPO
								foreach ($itemPO as $poKey => $poItems) {
									
									if ($doItems['barang'] == $poItems['barang']){
										$selisih = intval($poItems['qty']) - intval($doItems['qty']);

										if ($selisih > 0){
											$poItems['selisih'] = 0;
											array_push($dataPO['response_data'][$dataKey]['items'], $poItems);
											//$dataPO['response_data'][$dataKey]['items'] = $itemPO['response_data'];
										}
									} else {
										$poItems['selisih'] = 0;
										array_push($dataPO['response_data'][$dataKey]['items'], $poItems);
									}
								}
							}
						} else {
							$dataPO['response_data'][$dataKey]['items'] = $itemPO['response_data'];
						}

						foreach ($itemPO['response_data'] as $poKey => $poItems) {

							foreach ($itemDO['response_data'] as $doKey => $doItems) {
								
								if ($poItems['uid_barang'] == $doItems['barang']){
									//count difference qty in po and do 
									$selisih = intval($poItems['qty']) - intval($doItems['qty']);

									if ($selisih > 0){
										$poItems['selisih'] = $selisih;
										$dataPO['response_data'][$dataKey]['items'][$poKey] = $poItems;
									}
								} else {
									$poItems['selisih'] = $poItems['qty'];
									//$dataPO['response_data'][$dataKey]['items'][$poKey] = $poItems;
								}
								//$dataPO['response_data'][$dataKey]['items'][$doKey] = $doItems;
							}
							//$dataPO['response_data'][$dataKey]['items'][$poKey] = $poItems;
						}
						//array_push($test, [$poItems, $doItems]);

					}
				} else {
					$dataPO['response_data'][$dataKey]['items'] = $itemPO['response_data'];
				}

				if ($dataPO['response_data'][$dataKey]['items'] == ""){
					//unset($dataPO['response_data'][$dataKey]);
				}
				//array_push($dataPO['response_data'][$key]['items'], $detailPO['response_data'][0]);
			}
		}

		return $dataPO;
		//return $test;
	}*/

	private function load_po_available(){
		$po = new PO(self::$pdo);
		$dataPO = $po->get_po_detail_qty();

		$autonum = 1;
		foreach ($dataPO['response_data'] as $key => $value) {
			$dataPO['response_data'][$key]['autonum'] = $autonum;
			$autonum++;

			$po_detail = $po->get_po_detail($value['po']);
			$dataPO['response_data'][$key]['nomor_po'] = $po_detail['response_data'][0]['nomor_po'];
			$dataPO['response_data'][$key]['uid_supplier'] = $po_detail['response_data'][0]['supplier'];
		}

		return $dataPO;
	}

	public function load_po_supplier($parameter){
		$po = new PO(self::$pdo);
		$dataPO = $po->get_po_detail_qty();

		$autonum = 1;
		foreach ($dataPO['response_data'] as $key => $value) {
			$dataPO['response_data'][$key]['autonum'] = $autonum;
			$autonum++;

			$po_detail = $po->get_po_detail($value['po']);
			$dataPO['response_data'][$key]['nomor_po'] = $po_detail['response_data'][0]['nomor_po'];
			$dataPO['response_data'][$key]['uid_supplier'] = $po_detail['response_data'][0]['supplier'];
		}

		return $dataPO;
	}

	/*=========================CREATE DO======================*/
	private function tambah_do($parameter){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$uid = parent::gen_uuid();
		$parameter['dataInfo']['uid'] = $uid;
		$parameter['dataInfo']['status'] = 'N';
		$parameter['dataInfo']['pegawai'] = $UserData['data']->uid;
		$parameter['dataInfo']['waktu_input'] = 
			$parameter['dataInfo']['created_at'] = 
				$parameter['dataInfo']['updated_at'] = parent::format_date();

		$do = self::$query
			->insert('inventori_do', $parameter['dataInfo'])
			->execute();

		$uid_po = (isset($parameter['dataInfo']['po'])) ? $parameter['dataInfo']['po'] : null;

		if ($do['response_result'] > 0) {
			$do_items = self::tambah_do_detail($parameter['dataItems'], $uid, $uid_po);

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
						$uid,
						$UserData['data']->uid,
						'inventori_do',
						'I',
						parent::format_date(),
						'N',
						$UserData['data']->log_id
					),
					'class'=>__CLASS__
				)
			);
		}

		$result = ['master'=>$do, 'child'=>$do_items];

		return $result;
	}

	private function tambah_do_detail($parameter, $uid_parent, $uid_po){
		/*$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);*/

		foreach ($parameter as $key => $value) {
			$value['uid_do'] = $uid_parent;
			$value['created_at'] = $value['updated_at'] = parent::format_date();

			$detail = self::$query
				->insert('inventori_do_detail', $value)
				->execute();

			if ($detail['response_result'] > 0){
				if ($uid_po != ""){
					$cek_po = self::$query
						->select('inventori_do_detail', array(
							'qty', 'barang'))
						->join('inventori_do', array(
							'uid as uid_do'))
						->join('inventori_po', array(
							'uid as uid_po'))
						->on(array(
							array('inventori_do_detail.uid_do', '=', 'inventori_do.uid'),
							array('inventori_do.po', '=', 'inventori_po.uid')
						))
						->where(array(
								'inventori_do_detail.barang' => '= ?', 
								'AND',
								'inventori_po.uid' => '= ?'),
							array(
								$value['barang'], 
								$uid_po)
						)
						->execute();

					$qty_calculate = 0;
					if ($cek_po['response_result'] > 0){
						foreach ($cek_po['response_data'] as $key => $value) {
							$qty_calculate += intval($cek_po['response_data'][$key]['qty']);
						}

						$poUpdate = self::$query
							->update('inventori_po_detail', array(
									'qty_sampai' => $qty_calculate
								)
							)
							->where(array(
									'inventori_po_detail.barang' => '= ?',
									'AND',
									'inventori_po_detail.po' => '= ?'
								), array(
									$value['barang'],
									$uid_po
								)
							)
							->execute();
					}
				}
			}
		}

		return ['detail'=>$detail,'poUpdate'=>$poUpdate];
	}

	/*===============FUNGSI TAMBAHAN================*/
	private function duplicate_check($parameter) {
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