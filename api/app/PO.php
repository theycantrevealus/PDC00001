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
				case 'list':
					return self::list_po();
					break;

				case 'view':
					return self::get_po_info($parameter[2]);
					//return array();
					break;
                case 'select2':
                    return self::get_po_select($parameter);
                    break;
				case 'all2':
					return self::get_po2($parameter);
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
			case 'get_po_backend':
				return self::get_po_backend($parameter);
				break;
			case 'po_data_detail':
				return self::po_data_detail($parameter);
				break;
			case 'edit_po':
				return self::edit_po($parameter);
				break;
			case 'po_data':
				return self::po_data($parameter);
				break;
			default:
				break;
		}
	}

	private function po_data($parameter) {
		if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
			$paramData = array(
                'inventori_po.deleted_at' => 'IS NULL',
                'AND',
                'inventori_po.nomor_po' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array();
		} else {
			$paramData = array(
                'inventori_po.deleted_at' => 'IS NULL'
            );

            $paramValue = array();
		}

		if ($parameter['length'] < 0) {
			$data = self::$query->select('inventori_po', array(
				'uid',
				'nomor_po',
				'pegawai',
				'tanggal_po',
				'total',
				'total_after_disc',
				'supplier',
				'sumber_dana',
				'keterangan'
			))
				->where($paramData, $paramValue)
				->order(array(
					'created_at' => 'DESC'
				))
				->execute();
		} else {
			$data = self::$query->select('inventori_po', array(
				'uid',
				'nomor_po',
				'pegawai',
				'tanggal_po',
				'total',
				'total_after_disc',
				'supplier',
				'sumber_dana',
				'keterangan'
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

		$Terminologi = new Terminologi(self::$pdo);
        $Supplier = new Supplier(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);

		foreach ($data['response_data'] as $key => $value) {
			//Check Barang sudah sampai atau belum
			// $PODetail = self::get_po_detail($value['uid'])['response_data'];
			// foreach ($PODetail as $POKey => $POValue) {
			// 	$PODetail[$POKey]['qty'] = floatval($POValue['qty']);
			// 	$PODetail[$POKey]['harga'] = floatval($POValue['harga']);
			// 	$PODetail[$POKey]['disc'] = floatval($POValue['disc']);
			// 	$PODetail[$POKey]['subtotal'] = floatval($POValue['subtotal']);

			// 	//Check DO
			// 	$countBarang = 0;
			// 	$checkDO = self::$query->select('inventori_do_detail', array(
			// 		'qty'
			// 	))
			// 	->where(array(
			// 		'inventori_do_detail.po' => '= ?',
			// 		'AND',
			// 		'inventori_do_detail.barang' => '= ?'
			// 	), array(
			// 		$value['uid'],
			// 		$POValue['uid_barang']
			// 	))
			// 	->execute();

			// 	foreach ($checkDO['response_data'] as $CDOKey => $CDOValue) {
			// 		$countBarang += floatval($CDOValue['qty']);
			// 	}

			// 	$PODetail[$POKey]['sampai'] = $countBarang;
			// }
			// $data['response_data'][$key]['detail'] = $PODetail;
			$data['response_data'][$key]['sumber_dana'] = $Terminologi->get_terminologi_items_detail('terminologi_item', $value['sumber_dana'])['response_data'][0];


			$InfoSupplier = $Supplier->get_detail($value['supplier']);
			$data['response_data'][$key]['supplier'] = $InfoSupplier;


			$InfoPegawai = $Pegawai->get_info($value['pegawai']);
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['tanggal_po'] = date("d F Y", strtotime($value['tanggal_po']));
			$data['response_data'][$key]['pegawai'] = $InfoPegawai['response_data'][0];
			
			$autonum++;
		}

		$itemTotal = self::$query->select('inventori_po', array(
            'uid'
        ))
            ->where(array(
                'inventori_po.deleted_at' => 'IS NULL'
            ))
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
	}

	private function list_po() {
		$data = self::$query->select('inventori_po', array(
			'uid',
			'nomor_po'
		))
		->where(array(
			'inventori_po.deleted_at' => 'IS NULL',
			'AND',
			'inventori_po.nomor_po' => '!= ?'
		), array(
			'STOK_AWAL'
		))
		->execute();

		// $Terminologi = new Terminologi(self::$pdo);
		// $Supplier = new Supplier(self::$pdo);
		// $autonum = 1;

		// foreach ($data['response_data'] as $key => $value) {
		// 	//Check Barang sudah sampai atau belum
		// 	$PODetail = self::get_po_detail($value['uid'])['response_data'];
		// 	foreach ($PODetail as $POKey => $POValue) {
		// 		$PODetail[$POKey]['qty'] = floatval($POValue['qty']);
		// 		$PODetail[$POKey]['harga'] = floatval($POValue['harga']);
		// 		$PODetail[$POKey]['disc'] = floatval($POValue['disc']);
		// 		$PODetail[$POKey]['subtotal'] = floatval($POValue['subtotal']);

		// 		//Check DO
		// 		$countBarang = 0;
		// 		$checkDO = self::$query->select('inventori_do_detail', array(
		// 			'qty'
		// 		))
		// 		->where(array(
		// 			'inventori_do_detail.po' => '= ?',
		// 			'AND',
		// 			'inventori_do_detail.barang' => '= ?'
		// 		), array(
		// 			$value['uid'],
		// 			$POValue['uid_barang']
		// 		))
		// 		->execute();

		// 		foreach ($checkDO['response_data'] as $CDOKey => $CDOValue) {
		// 			$countBarang += floatval($CDOValue['qty']);
		// 		}

		// 		$PODetail[$POKey]['sampai'] = $countBarang;
		// 	}
		// 	$data['response_data'][$key]['detail'] = $PODetail;
		// 	$data['response_data'][$key]['sumber_dana'] = $Terminologi->get_terminologi_items_detail('terminologi_item', $value['sumber_dana'])['response_data'][0];


		// 	$InfoSupplier = $Supplier->get_detail($value['supplier']);
		// 	$data['response_data'][$key]['supplier'] = $InfoSupplier;


			
		// 	$data['response_data'][$key]['autonum'] = $autonum;
		// 	$data['response_data'][$key]['tanggal_po'] = date("d F Y", strtotime($value['tanggal_po']));
			
		// 	$autonum++;
		// }

		return $data;
	}

	private function get_po_backend($parameter) {
		//TODO PO Back End
		$Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
		if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
			$paramData = array(
				'master_supplier.deleted_at' => 'IS NULL',
				'AND',
				'inventori_po.deleted_at' => 'IS NULL',
				'AND',
				'inventori_po.nomor_po' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
			);

			$paramValue = array();
		} else {
			$paramData = array(
				'master_supplier.deleted_at' => 'IS NULL',
				'AND',
				'inventori_po.deleted_at' => 'IS NULL'
			);

			$paramValue = array();
		}

		if ($parameter['length'] < 0) {
			$data = self::$query->select('inventori_po', array(
				'uid',
				'nomor_po',
				'pegawai',
				'tanggal_po',
				'total',
				'total_after_disc',
				'supplier',
				'sumber_dana',
				'keterangan'
			))
			->order(array(
				'inventori_po.created_at' => 'DESC'
			))
			->join('master_supplier', array(
				'nama as nama_supplier'
			))
			->on(array(
				array('inventori_po.supplier', '=', 'master_supplier.uid')
			))
			->where($paramData, $paramValue)
			->execute();
		} else {
			$data = self::$query->select('inventori_po', array(
				'uid',
				'nomor_po',
				'pegawai',
				'tanggal_po',
				'total',
				'total_after_disc',
				'supplier',
				'sumber_dana',
				'keterangan'
			))
			->order(array(
				'inventori_po.created_at' => 'DESC'
			))
			->join('master_supplier', array(
				'nama as nama_supplier'
			))
			->on(array(
				array('inventori_po.supplier', '=', 'master_supplier.uid')
			))
			->offset(intval($parameter['start']))
			->limit(intval($parameter['length']))
			->where($paramData, $paramValue)
			->execute();
		}

		$data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;

		$Terminologi = new Terminologi(self::$pdo);
        $Supplier = new Supplier(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);

		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['sumber_dana'] = $Terminologi->get_terminologi_items_detail('terminologi_item', $value['sumber_dana'])['response_data'][0];

			$InfoPegawai = $Pegawai->get_info($value['pegawai']);


			//Check Penerimaan
			$Det = self::$query->select('inventori_po_detail', array(
				'barang', 'qty as qty_pesan'
			))
				->join('inventori_do_detail', array(
					'qty as qty_sampai'
				))
				->on(array(
					array('inventori_po_detail.po', '=', 'inventori_do_detail.po')
				))
				->where(array(
					'inventori_po_detail.po' => '= ?',
					'AND',
					'inventori_po_detail.deleted_at' => 'IS NULL'
				), array(
					$value['uid']
				))
				->execute();

			$data['response_data'][$key]['tanggal_po'] = date("d F Y, H:i", strtotime($value['tanggal_po']));
			$data['response_data'][$key]['pegawai'] = $InfoPegawai['response_data'][0];

			$autonum++;
		}


		$itemTotal = self::$query->select('inventori_po', array(
            'uid'
        ))
			->join('master_supplier', array(
				'nama as nama_supplier'
			))
			->on(array(
				array('inventori_po.supplier', '=', 'master_supplier.uid')
			))
            ->where(array(
				'master_supplier.deleted_at' => 'IS NULL',
				'AND',
				'inventori_po.deleted_at' => 'IS NULL'
			), array())
            ->execute();

		$data['recordsTotal'] = count($itemTotal['response_data']);
		$data['recordsFiltered'] = count($data['response_data']);
		$data['length'] = intval($parameter['length']);
		$data['start'] = intval($parameter['start']);

		return $data;
	}

	public function get_po2($parameter) {
		$data = self::$query->select('inventori_po', array(
			'uid',
			'nomor_po',
			'pegawai',
			'tanggal_po',
			'total',
			'total_after_disc',
			'supplier',
			'sumber_dana',
			'keterangan'
		))
		->where(array(
			'inventori_po.deleted_at' => 'IS NULL',
			'AND',
			'inventori_po.nomor_po' => '!= ?'
		), array(
			'STOK_AWAL'
		))
		->order(array(
			'created_at' => 'DESC'
		))
		->execute();

		$autonum = 1;
		$Terminologi = new Terminologi(self::$pdo);
        $Supplier = new Supplier(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);

		foreach ($data['response_data'] as $key => $value) {
			//Check Barang sudah sampai atau belum
			
			$data['response_data'][$key]['sumber_dana'] = $Terminologi->get_terminologi_items_detail('terminologi_item', $value['sumber_dana'])['response_data'][0];


			$InfoSupplier = $Supplier->get_detail($value['supplier']);
			$data['response_data'][$key]['supplier'] = $InfoSupplier;


			$InfoPegawai = $Pegawai->get_info($value['pegawai']);

			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['tanggal_po'] = date("d F Y", strtotime($value['tanggal_po']));
			$data['response_data'][$key]['pegawai'] = $InfoPegawai['response_data'][0];
			
			$autonum++;
		}

		return $data;
	}

	public function get_po() {
		$data = self::$query->select('inventori_po', array(
			'uid',
			'nomor_po',
			'pegawai',
			'tanggal_po',
			'total',
			'total_after_disc',
			'supplier',
			'sumber_dana',
			'keterangan'
		))
		->where(array(
			'inventori_po.deleted_at' => 'IS NULL'
		))
		->execute();
		$autonum = 1;
		$Terminologi = new Terminologi(self::$pdo);
        $Supplier = new Supplier(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);

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
			$data['response_data'][$key]['sumber_dana'] = $Terminologi->get_terminologi_items_detail('terminologi_item', $value['sumber_dana'])['response_data'][0];


			$InfoSupplier = $Supplier->get_detail($value['supplier']);
			$data['response_data'][$key]['supplier'] = $InfoSupplier;


			$InfoPegawai = $Pegawai->get_detail($value['pegawai']);

			$data['response_data'][$key]['autonum'] = $autonum;
			$data['response_data'][$key]['tanggal_po'] = date("d F Y", strtotime($value['tanggal_po']));
			$data['response_data'][$key]['pegawai'] = $InfoPegawai['response_data'][0];
			
			$autonum++;
		}

		return $data;
	}

	public function get_po_detail($parameter) {
        $Inventori = new Inventori(self::$pdo);
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

        $Inventori = new Inventori(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
			$DO = self::$query->select('inventori_do', array(
				'no_do', 'no_invoice'
			))
				->where(array(
					'inventori_do.po' => '= ?'
				), array(
					$value['uid_po']
				))
				->execute();
			$data['response_data'][$key]['do'] = $DO['response_data'][0];
            $data['response_data'][$key]['barang_detail'] = $Inventori->get_item_detail($value['uid_barang'])['response_data'][0];
            $batchAvail = $Inventori->get_item_batch($value['uid_barang'])['response_data'];
            $parsedBatch = array();
            foreach ($batchAvail as $BKey => $BValue) {
                if($BValue['gudang']['uid'] === __GUDANG_UTAMA__) {
                    array_push($parsedBatch, $BValue);
                }
            }
            $data['response_data'][$key]['batch_avail'] = $parsedBatch;
        }

		return $data;
	}

	public function get_po_item_price($parameter){
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
					$parameter['po'],
					$parameter['barang']
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

    private function get_po_select($parameter) {
        $po = self::$query->select('inventori_po', array(
            'uid',
            'nomor_po',
            'supplier as uid_supplier',
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
            ->join('master_supplier', array(
                'nama as nama_supplier', 'supplier_type'
            ))
            ->join('inventori_do', array(
                'uid as uid_do'
            ))
            ->on(array(
                array('inventori_po.supplier', '=', 'master_supplier.uid'),
                array('inventori_do.po', '=', 'inventori_po.uid'),
            ))
            ->where(array(
                'inventori_po.deleted_at' => 'IS NULL',
                'AND',
                '(inventori_po.nomor_po' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\'',
                'OR',
                'master_supplier.nama' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\')'
            ))
            ->limit(10)
            ->execute();
        foreach ($po['response_data'] as $key => $value) {
            $po['response_data'][$key]['nomor_po'] = $value['nomor_po'] . ' - ' . $value['nama_supplier'];
            $po['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
        }
        return $po;
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

        $autonum = 1;
		$Supplier = new Supplier(self::$pdo);
		$Pegawai = new Pegawai(self::$pdo);

		foreach ($po['response_data'] as $key => $value) {
			
			$InfoPegawai = $Pegawai->get_info($value['pegawai']);
			$InfoSupplier = (isset($value['supplier']) && !empty($value['supplier'])) ? $Supplier->get_detail($value['supplier']) : array(
				'nama' => 'AUTO STOK AWAL'
			);
			
			$po['response_data'][$key]['autonum'] = $autonum;
			$po['response_data'][$key]['tanggal_po'] = date("d F Y", strtotime($value['tanggal_po']));
			$po['response_data'][$key]['pegawai'] = $InfoPegawai['response_data'][0];
			$po['response_data'][$key]['supplier'] = $InfoSupplier;
			$autonum++;
		}

		if(count($po['response_data'])) {
			$po_detail = self::get_po_detail($parameter);
			foreach ($po_detail['response_data'] as $key => $value) {
			    $po_detail['response_data'][$key]['qty'] = floatval($value['qty']);
				$Inventori = new Inventori(self::$pdo);
				$InventoriInfo = $Inventori->get_item_detail($value['uid_barang'])['response_data'][0];
				$InventoriSatuan = $Inventori->get_satuan_detail($InventoriInfo['satuan_terkecil'])['response_data'][0];

				$Accumulate = 0;
                //Informasi barang sudah sampai
                $DODetail = self::$query->select('inventori_do', array(
                    'uid'
                ))
                    ->where(array(
                        'inventori_do.po' => '= ?',
                        'AND',
                        'inventori_do.deleted_at' => 'IS NULL'
                    ), array(
                        $value['uid_po']
                    ))
                    ->execute();

                foreach ($DODetail['response_data'] as $doKey => $doVal) {
                    //get detail
                    $doDetailItem = self::$query->select('inventori_do_detail', array(
                        'qty'
                    ))
                        ->where(array(
                            'inventori_do_detail.do_master' => '= ?',
                            'AND',
                            'inventori_do_detail.barang' => '= ?',
                            'AND',
                            'inventori_do_detail.deleted_at' => 'IS NULL'
                        ), array(
                            $doVal['uid'], $value['uid_barang']
                        ))
                        ->execute();
                    foreach ($doDetailItem['response_data'] as $doDetailKey => $doDetailValue) {
                        $Accumulate += floatval($doDetailValue['qty']);
                    }
                    $DODetail['response_data'][$doKey]['detail'] = $doDetailItem['response_data'];
                }
                $po_detail['response_data'][$key]['do'] = $DODetail['response_data'];
                $po_detail['response_data'][$key]['sudah_sampai'] = $Accumulate;
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

	private function po_data_detail($parameter) {
		if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
			$paramData = array(
                'inventori_po_detail.po' => '= ?',
				'AND',
				'inventori_po_detail.qty' => '> 0'
            );

            $paramValue = array($parameter['uid']);
		} else {
			$paramData = array(
                'inventori_po_detail.po' => '= ?',
				'AND',
				'inventori_po_detail.qty' => '> 0'
            );

            $paramValue = array($parameter['uid']);
		}

		if ($parameter['length'] < 0) {
			$data = self::$query->select('inventori_po_detail', array(
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
			))
				->where($paramData, $paramValue)
				->execute();
		} else {
			$data = self::$query->select('inventori_po_detail', array(
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
			))
				->where($paramData, $paramValue)
				->offset(intval($parameter['start']))
				->limit(intval($parameter['length']))
				->execute();

		}

		$data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;

		$Terminologi = new Terminologi(self::$pdo);
        $Supplier = new Supplier(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);
		$Inventori = new Inventori(self::$pdo);

		foreach ($data['response_data'] as $key => $value) {
			// $InfoSupplier = $Supplier->get_detail($value['supplier']);
			// $data['response_data'][$key]['supplier'] = $InfoSupplier;


			// $InfoPegawai = $Pegawai->get_info($value['pegawai']);
			// $data['response_data'][$key]['autonum'] = $autonum;
			// $data['response_data'][$key]['tanggal_po'] = date("d F Y", strtotime($value['tanggal_po']));
			// $data['response_data'][$key]['pegawai'] = $InfoPegawai['response_data'][0];

			$data['response_data'][$key]['barang_detail'] = $Inventori->get_item_detail($value['uid_barang'])['response_data'][0];
            $batchAvail = $Inventori->get_item_batch($value['uid_barang'])['response_data'];
            $parsedBatch = array();
            foreach ($batchAvail as $BKey => $BValue) {
                if($BValue['gudang']['uid'] === __GUDANG_UTAMA__) {
                    array_push($parsedBatch, $BValue);
                }
            }
            $data['response_data'][$key]['batch_avail'] = $parsedBatch;
			$data['response_data'][$key]['autonum'] = $autonum;
			
			$autonum++;
		}

		$itemTotal = self::$query->select('inventori_po_detail', array(
            'id'
        ))
            ->where(array(
                'inventori_po_detail.po' => '= ?',
				'AND',
				'inventori_po_detail.qty' => '> 0'
			), array(
				$parameter['uid']
			))
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
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
			'sumber_dana' => $parameter['sumber_dana'],
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