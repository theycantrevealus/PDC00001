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
				case 'load-po':
					return self::load_po();
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
					'po',
					'gudang',
					'supplier',
					'tgl_do',
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

			$data['response_data'][$key]['tgl_do'] = date('d F Y', strtotime($value['tgl_do']));

			$Supplier = new Supplier(self::$pdo);
			$SupplierInfo = $Supplier::get_detail($value['supplier']);
			$data['response_data'][$key]['supplier'] = $SupplierInfo;

			$Inventori = new Inventori(self::$pdo);
			$InventoriInfo = $Inventori::get_gudang_detail($value['gudang']);
			$data['response_data'][$key]['gudang'] = $InventoriInfo['response_data'][0];

			$Pegawai = new Pegawai(self::$pdo);
			$PegawaiInfo = $Pegawai::get_detail($value['pegawai']);
			$data['response_data'][$key]['pegawai'] = $PegawaiInfo['response_data'][0];
		}

		return $data;
	}

	private function get_do_detail($parameter){
		$data = self::$query
			->select('inventori_do_detail', array(
					'id',
					'do',
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
					'inventori_do_detail.do' => '= ?'
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

	private function load_po(){
		$po = new PO(self::$pdo);
		$dataPO = $po->get_po();

		//check po result
		if ($dataPO['response_result'] > 0){

			//load po response_data
			foreach ($dataPO['response_data'] as $dataKey => $value) {
				$dataPO['response_data'][$key]['items'] = [];

				//get from inv_do where row has inv_po uid
				$dataDO = self::get_do_where_po($value['uid']);
				$itemPO = $po->get_po_detail($value['uid']);

				//if inv_do row has inv_po uid
				if ($dataDO['response_result'] > 0){
					foreach ($dataDO['response_data'] as $key => $items) {
						//get inv_do_detail
						$itemDO = self::get_do_detail($items['uid']);

						//if inv_do_detail row has inv_do uid
						if ($itemDO['response_result'] > 0){
							//check if itemDO is in itemPO
							foreach ($itemDO as $key => $doItems) {
								//load itemPO
								foreach ($itemPO as $key => $poItems) {
									
									if ($doItems['barang'] == $poItems['barang']){
										$selisih = intval($poItems['qty']) - intval($doItems['qty']);

										if ($selisih > 0){
											array_push($dataPO['response_data'][$dataKey]['items'], $poItems);
										}
									}
								}
							}
						}
					}
				}

				if ($dataPO['response_data'][$key]['items'] == ""){
					unset($dataPO['response_data'][$key]);
				}
				//array_push($dataPO['response_data'][$key]['items'], $detailPO['response_data'][0]);
			}
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

		if ($do['response_result'] > 0) {
			$do_items = self::tambah_do_detail($parameter['dataItems'], $uid);

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

	private function tambah_do_detail($parameter, $uid_parent){
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		foreach ($parameter as $key => $value) {
			$value['uid_do'] = $uid_parent;
			$value['created_at'] = $value['updated_at'] = parent::format_date();

			$detail = self::$query
				->insert('inventori_do_detail', $value)
				->execute();
		}

		return $detail;
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