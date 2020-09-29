<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Contoh extends Utility {
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
				case 'select':
					return

						self::$query
							->select('pegawai', array(
								'uid',
								'email'
							))

							->execute();
					break;
				case 'select_where_limit':
					return
						self::$query
							->select('pegawai', array(
								'uid',
								'email'
							))
							->offset(1)
							->limit(1)

							->execute();
					break;
				case 'select_where':
					return

						self::$query
							->select('pegawai', array(
								'uid',
								'email'
							))

							->where(array(
								'pegawai.deleted_at' => 'IS NULL',
								'OR',
								'pegawai.uid' => '= ?'
							), array(
								'8113652d-4cb7-e850-d487-281a1762042a'
							))

							->execute();
					break;
				case 'select_join':
					return

						self::$query
							->select('log_activity', array(
								'id AS id_akses'
							))

							->join('log_login', array(
								'id',
								'user_uid'
							))

							->join('pegawai', array(
								'nama'
							))

							->on(array(
								array('log_activity.login_id', '=', 'log_login.id'),
								array('log_activity.user_uid', '=', 'pegawai.uid')
							))

							->execute();
					break;
				case 'select_join_where':
					return

						self::$query
							->select('pegawai_akses', array(
								'id AS id_akses'
							))

							->join('pegawai', array(
								'uid',
								'email',
								'nama AS nama_pegawai'
							))

							->join('modul', array(
								'id AS id_modul',
								'nama AS nama_modul'
							))

							->on(array(
								array('pegawai_akses.uid_pegawai','=','pegawai.uid'),
								array('pegawai_akses.modul','=','modul.id')
							))

							->where(array(
								'pegawai.deleted_at' => 'IS NULL',
								'AND',
								'pegawai.uid' => '= ?'
							), array(
								'8113652d-4cb7-e850-d487-281a1762042a'
							))

							->execute();
					break;
				case 'insert':
					return
						self::$query
							->insert('modul', array(
								'nama' => 'Nama Modul',
								'identifier' => 'modul/test',
								'keterangan' => 'Keterangan Modul',
								'created_at' => parent::format_date(),
								'updated_at' => parent::format_date(),
								'parent' => 1,
								'icon' => 'person',
								'show_on_menu' => 'Y',
								'show_order' => 1,
								'menu_group' => 1
							))

							->execute();
				 	break;
				 case 'update_where':
					return
						self::$query
							->update('modul', array(
								'nama' => 'Nama Modul',
								'identifier' => 'modul/test',
								'keterangan' => 'Keterangan Modul',
								'updated_at' => parent::format_date(),
								'parent' => 1,
								'icon' => 'person',
								'show_on_menu' => 'Y',
								'show_order' => 1,
								'menu_group' => 1
							))

							->where(array(
								'modul.deleted_at' => 'IS NULL',
								'AND',
								'modul.id' => '= ?'
							), array(
								7
							))

							->execute();
				 	break;
				case 'delete':
					return
						self::$query
							->delete('modul')

							->where(array(
								'modul.id' => '= ?'
							), array(
								7
							))

							->execute();
					break;
				default:
					return 'Unknown request';
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}
}