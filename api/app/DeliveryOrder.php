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

                case 'detail':
                    return self::get_do_info($parameter[2]);
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
			/*case 'tambah-do':
				return self::tambah_do($parameter);
				break;*/
			case 'tambah_do':
				return self::tambah_do($parameter);
				break;
			case 'get_do_back_end':
				return self::get_do_back_end($parameter);
				break;
			default:
				return $parameter;
				break;
		}
	}

	public function __DELETE__($parameter = array()){
		//return self::delete_penjamin($parameter);
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
            ->order(array(
                'created_at' => 'DESC'
            ))
			->execute();

		$autonum = 1;
		$Pegawai = new Pegawai(self::$pdo);
		$Inventori = new Inventori(self::$pdo);
		$Supplier = new Supplier(self::$pdo);
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$autonum++;

			$data['response_data'][$key]['tgl_do'] = date('d F Y', strtotime($value['tgl_do']));

			
			$SupplierInfo = $Supplier::get_detail($value['supplier']);
			$data['response_data'][$key]['supplier'] = $SupplierInfo;

			
			$InventoriInfo = $Inventori::get_gudang_detail($value['gudang']);
			$data['response_data'][$key]['gudang'] = $InventoriInfo['response_data'][0];

			
			$PegawaiInfo = $Pegawai->get_info($value['pegawai']);
			$data['response_data'][$key]['pegawai'] = $PegawaiInfo['response_data'][0];
		}

		return $data;
	}

	public function get_do_info($parameter) {
	    $data = self::$query->select('inventori_do', array(
	        'uid',
            'gudang',
            'supplier',
            'tgl_do',
            'no_do',
            'no_invoice',
            'tgl_invoice',
            'keterangan',
            'status',
            'pegawai',
            'po'
        ))
            ->where(array(
                'inventori_do.uid' => '= ?',
                'AND',
                'inventori_do.deleted_at' => 'IS NULL'
            ), array(
                $parameter
            ))
            ->execute();
        $Supplier = new Supplier(self::$pdo);
        $Gudang = new Inventori(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);
        $PO = new PO(self::$pdo);
	    foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['supplier'] =  $Supplier->get_detail($value['supplier']);
            $data['response_data'][$key]['gudang'] =  $Gudang->get_gudang_detail($value['gudang'])['response_data'][0];
            $data['response_data'][$key]['pegawai'] =  $Pegawai->get_detail_pegawai($value['pegawai'])['response_data'][0];
            $data['response_data'][$key]['po'] =  $PO->get_po_detail($value['po'])['response_data'][0];
            $data['response_data'][$key]['tgl_do'] = date('d F Y', strtotime($value['tgl_do']));
            $data['response_data'][$key]['tgl_invoice'] = date('d F Y', strtotime($value['tgl_invoice']));
            $data['response_data'][$key]['po'] =
            $detail = self::get_do_detail($parameter);
	        $data['response_data'][$key]['detail'] =  $detail['response_data'];
        }

	    return $data;
    }

	private function get_do_detail($parameter){
		$data = self::$query
			->select('inventori_do_detail', array(
					'id',
					'do_master',
					'barang',
					'batch',
					'kadaluarsa',
					'qty',
					'keterangan',
					'created_at',
					'updated_at',
                    'po'
				)
			)
			->where(array(
					'inventori_do_detail.deleted_at' => 'IS NULL',
					'AND',
					'inventori_do_detail.do_master' => '= ?'
				), array(
					$parameter
				)
			)
			->execute();
		$autonum = 1;
        $Inventori = new Inventori(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['batch'] = $Inventori->get_batch_detail($value['batch'])['response_data'][0];
            $data['response_data'][$key]['barang'] = $Inventori->get_item_detail($value['barang'])['response_data'][0];
            $data['response_data'][$key]['kadaluarsa'] = date('d F Y', strtotime($value['kadaluarsa']));
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }
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
				$dataPO['response_data'][$dataKey]['items'] = [];

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

	private function get_do_back_end($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
			$paramData = array(
                'inventori_do.deleted_at' => 'IS NULL',
                'AND',
                'inventori_do.no_do' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array();
		} else {
			$paramData = array(
                'inventori_do.deleted_at' => 'IS NULL'
            );

            $paramValue = array();
		}

		if ($parameter['length'] < 0) {
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
					->where($paramData, $paramValue)
					->order(array(
						'created_at' => 'DESC'
					))
					->execute();
		} else {
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
					->where($paramData, $paramValue)
					->offset(intval($parameter['start']))
					->limit(intval($parameter['length']))
					->order(array(
						'created_at' => 'DESC'
					))
					->execute();
		}

		$data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;

		$Supplier = new Supplier(self::$pdo);
		$Inventori = new Inventori(self::$pdo);
		$Pegawai = new Pegawai(self::$pdo);

        foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			
			$data['response_data'][$key]['tgl_do'] = date('d F Y', strtotime($value['tgl_do']));
			
			$SupplierInfo = $Supplier->get_detail($value['supplier']);
			$data['response_data'][$key]['supplier'] = $SupplierInfo;
			
			$InventoriInfo = $Inventori->get_gudang_detail($value['gudang']);
			$data['response_data'][$key]['gudang'] = $InventoriInfo['response_data'][0];
			
			$PegawaiInfo = $Pegawai->get_info($value['pegawai']);
			$data['response_data'][$key]['pegawai'] = $PegawaiInfo['response_data'][0];
			$autonum++;
		}

		$itemTotal = self::$query->select('inventori_do', array(
            'uid'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
	}

	/*=========================CREATE DO======================*/
	/*private function tambah_do($parameter){
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
	}*/

	/*private function tambah_do_detail($parameter, $uid_parent){
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
	}*/




	private function tambah_do($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		//Tambah DO Master
		/*$latestDO = self::$query->select('inventori_po', array(
			'uid'
		))
		->where(array(
			'EXTRACT(MONTH FROM created_at)' => '= ?'
		), array(
			intval(date('m'))
		))
		->execute();*/

		$checkDup = self::$query->select('inventori_stok_log', array(
			'id'
		))
			->where(array(
				'inventori_stok_log.keterangan' => 'ILIKE \'%' . $parameter['supplier'] . '[' . $parameter['nomor_do'] . ']%\''
			), array())
			->execute();

		if(count($checkDup['response_data']) <= 0) {
			$uid = parent::gen_uuid();
			$do = self::$query->insert('inventori_do', array(
				'uid' => $uid,
				'po' => $parameter['po'],
				'gudang' => $parameter['gudang'],
				'supplier' => $parameter['supplier'],
				'tgl_do' => $parameter['tgl_dokumen'],
				'no_do' => $parameter['nomor_do'],
				'no_invoice' => $parameter['no_invoice'],
				'tgl_invoice' => $parameter['tgl_invoice'],
				'keterangan' => isset($parameter['keterangan']) ? $parameter['keterangan'] : '',
				'status' => 'N',
				'pegawai' => $UserData['data']->uid,
				'created_at' => parent::format_date(),
				'updated_at' => parent::format_date()
			))
			->execute();

			//Tambah DO Detail
			if($do['response_result'] > 0) {
				$itemReport = array();
				$stok_change = array();
				foreach ($parameter['item'] as $key => $value) {
					//Cek Batch
					$cek = self::$query->select('inventori_batch', array(
						'uid'
					))
					->where(array(
						'inventori_batch.barang' => '= ?',
						'AND',
						'inventori_batch.batch' => '= ?',
						'AND',
						'inventori_batch.deleted_at' => 'IS NULL'
					), array(
						$value['item'],
						$value['batch']
					))
					->execute();
					
					$UIDBatch = '';
					if(count($cek['response_data']) > 0) {
						$UIDBatch = $cek['response_data'][0]['uid'];
					} else {
						$UIDBatch = parent::gen_uuid();
						$newBatch = self::$query->insert('inventori_batch', array(
							'uid' => $UIDBatch,
							'barang' => $value['item'],
							'expired_date' => $value['tanggal_exp'],
							'batch' => $value['batch'],
							'po' => $parameter['po'],
							'do_master' => $uid,
							'created_at' => parent::format_date(),
							'updated_at' => parent::format_date()
						))
						->execute();
					}


					$do_detail = self::$query->insert('inventori_do_detail', array(
						'do_master' => $uid,
						'barang' => $value['item'],
						'batch' => $UIDBatch,
						'kadaluarsa' => $value['tanggal_exp'],
						'qty' => floatval($value['qty']),
						'po' => $parameter['po'],
						'keterangan' => $value['keterangan'],
						'created_at' => parent::format_date(),
						'updated_at' => parent::format_date()
					))
					->execute();

					array_push($itemReport, $do_detail);

					//Update Stock
					if($do_detail['response_result'] > 0) {
						$cekStok = self::$query->select('inventori_stok', array(
							'id',
							'stok_terkini'
						))
						->where(array(
							'inventori_stok.barang' => '= ?',
							'AND',
							'inventori_stok.batch' => '= ?',
							'AND',
							'inventori_stok.gudang' => '= ?'
						), array(
							$value['item'],
							$UIDBatch,
							$parameter['gudang']
						))
						->execute();

						if(count($cekStok['response_data']) > 0) {
							//Add Stok
							$stokWorker = self::$query->update('inventori_stok', array(
								'stok_terkini' => floatval($cekStok['response_data'][0]['stok_terkini']) + floatval($value['qty'])
							))
							->where(array(
								'inventori_stok.barang' => '= ?',
								'AND',
								'inventori_stok.batch' => '= ?',
								'AND',
								'inventori_stok.gudang' => '= ?'
							), array(
								$value['item'],
								$UIDBatch,
								$parameter['gudang']
							))
							->execute();
						} else {
							//New Stok
							$stokWorker = self::$query->insert('inventori_stok', array(
								'barang' => $value['item'],
								'batch' => $UIDBatch,
								'gudang' => $parameter['gudang'],
								'stok_terkini' => $value['qty']
							))
							->execute();
						}

						if($stokWorker['response_result'] > 0) {
							array_push($stok_change, $stokWorker);
							//Saldo Stok
							$getSaldo = self::$query->select('inventori_stok', array(
								'stok_terkini'
							))
							->where(array(
								'inventori_stok.barang' => '= ?',
								'AND',
								'inventori_stok.batch' => '= ?',
								'AND',
								'inventori_stok.gudang' => '= ?'
							), array(
								$value['item'],
								$UIDBatch,
								$parameter['gudang']
							))
							->execute();

							//Stok Log
							$stokLog = self::$query->insert('inventori_stok_log', array(
								'barang' => $value['item'],
								'batch' => $UIDBatch,
								'gudang' => $parameter['gudang'],
								'masuk' => $value['qty'],
								'keluar' => 0,
								'saldo' => $getSaldo['response_data'][0]['stok_terkini'],
								'type' => __STATUS_BARANG_MASUK__,
								'jenis_transaksi' => 'inventori_do',
								'uid_foreign' => $uid,
								'keterangan' => 'Penerimaan barang dengan Surat Jalan : ' . $parameter['supplier'] . '[' . $parameter['nomor_do'] . ']'
							))
							->execute();
						}
					}
				}
			}
			
			$do['response_detail'] = $itemReport;
			$do['response_stok'] = $stok_change;
		}

		
		return $do;
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