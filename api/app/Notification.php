<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Notification extends Utility {
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
					//
					break;
				default:
					return self::get_notification();
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	public function __POST__($parameter = array()) {
		try {

			switch($parameter['request']) {
				case 'read_notif':
					return self::read_notif($parameter);
					break;
				case 'clear_notif':
					return self::clear_notif($parameter);
					break;
				default:
					return array();
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}

	private function get_notification() {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$data = self::$query->select('notification', array(
			'id', 'sender', 'receiver_type', 'receiver', 'protocols',
			'notify_content', 'type', 'created_at', 'status'
		))
		->where(array(
			'notification.deleted_at' => 'IS NULL',
			'AND',
			'((notification.receiver' => '= ?',
			'AND',
			'notification.receiver_type' => '= ?)',
			'OR',
			'(notification.receiver' => '= ?',
			'AND',
			'receiver_type' => '= ?))'
		), array(
			$UserData['data']->uid,
			'personal',
			$UserData['data']->jabatan,
			'group'
		))
		->execute();

		return $data;
	}

	private function read_notif($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$worker = self::$query->update('notification', array(
			'status' => 'R'
		))
		->where(array(
			'notification.deleted_at' => 'IS NULL',
			'AND',
			'((notification.receiver' => '= ?',
			'AND',
			'notification.receiver_type' => '= ?)',
			'OR',
			'(notification.receiver' => '= ?',
			'AND',
			'receiver_type' => '= ?))'
		), array(
			$UserData['data']->uid,
			'personal',
			$UserData['data']->jabatan,
			'group'
		))
		->execute();

		return $worker;
	}

	private function clear_notif($parameter) {
		$Authorization = new Authorization();
		$UserData = $Authorization::readBearerToken($parameter['access_token']);

		$worker = self::$query->update('notification', array(
			'deleted_at' => parent::format_date()
		))
		->where(array(
			'notification.deleted_at' => 'IS NULL',
			'AND',
			'((notification.receiver' => '= ?',
			'AND',
			'notification.receiver_type' => '= ?)',
			'OR',
			'(notification.receiver' => '= ?',
			'AND',
			'receiver_type' => '= ?))'
		), array(
			$UserData['data']->uid,
			'personal',
			$UserData['data']->jabatan,
			'group'
		))
		->execute();

		return $worker;
	}
}