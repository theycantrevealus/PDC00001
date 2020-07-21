<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
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

	public function get_po(){
		$data = self::$query
			->select('inventori_po', array(
					'uid',
					'supplier',
					'pegawai',
					'total',
					'disc',
					'disc_type',
					'total_after_disc',
					'tanggal_po',
					'created_at',
					'updated_at',
					'keterangan'
				)
			)
			->where(array(
					'deleted_at' => 'IS NULL'
				), array()
			)
			->execute();

		$autonum = 1;
		foreach ($data['response_data'] as $key => $value) {
			$data['response_data'][$key]['autonum'] = $autonum;
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

	private function tambah_po($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		//Calculate Total
		
		$uid = parent::gen_uuid();
		$worker = self::$query->insert('inventori_po', array(
			'uid' => $uid,
			'supplier' => $parameter['supplier'],
			'pegawai' => $UserData['data']->uid,
			'total' => 0,
			'disc' => $parameter['diskonAll'],
			'disc_type' => $parameter['diskonJenisAll'],
			'total_after_disc' => 0,
			'tanggal_po' => date("Y-m-d", strtotime($parameter['tanggal'])),
			'created_at' => parent::format_date(),
			'updated_at' => parent::format_date()
		))
		->execute();
		if($worker['response_result'] > 0) {
			//
		}
	}

	private function edit_po($parameter) {
		//
	}
}