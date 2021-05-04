<?php

namespace PondokCoder;
use PondokCoder\QueryException as QueryException;
class Query {
	static $pdo;
	static $keyType;
	static $queryMode;
	static $keyReturn;
	static $queryString;
	static $queryStringOrder;
	static $queryParams = array();
	static $queryValues = array();
	static $joinString = array();
	static $whereParameter = array();
	static $whereLogic = array();
	private $tables = array();
	static $limit, $offset;

	public function __construct($connection) {
		self::$pdo = $connection;
		self::$queryString = '';
		self::$keyType = '';
	}

	//Migrate Utility

    /*
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     * */

	function insert($table, $parameter = array()) {
		$this->tables = array();
		self::$queryValues = array();
		self::$queryParams = array();
		self::$queryMode = 'insert';
		self::$queryString = 'INSERT INTO ';
		array_push($this->tables, $table);
		foreach ($parameter as $key => $value) {
			array_push(self::$queryParams, $key);
			array_push(self::$queryValues, $value);
		}

		return $this;
	}

	function returning($parameter) {
		self::$keyType = ' RETURNING '. $parameter;
		self::$keyReturn = $parameter;
		return $this;
	}

	function update($table, $parameter = array()) {
		$this->tables = array();
		self::$queryValues = array();
		self::$queryParams = array();
		self::$queryMode = 'update';
		self::$queryString = 'UPDATE ';
		array_push($this->tables, $table);
		foreach ($parameter as $key => $value) {
			array_push(self::$queryParams, $key);
			array_push(self::$queryValues, $value);
		}
		return $this;
	}

	function delete($table) {
		$this->tables = array();
		self::$queryMode = 'delete';
		self::$queryString = 'UPDATE ' . $table . ' SET deleted_at = NOW() ';
		array_push($this->tables, $table);
		return $this;
	}

	function hard_delete($table) {
		$this->tables = array();
		self::$queryValues = array();
		self::$queryParams = array();
		self::$queryMode = 'hard_delete';
		self::$queryString = 'DELETE FROM ' . $table . ' ';
		array_push($this->tables, $table);
		return $this;	
	}

	function where($parameter = array(), $values = array()) {
		self::$whereParameter = array();
		self::$whereLogic = array();
		foreach ($parameter as $key => $value) {
			if(is_int($key)) {
				array_push(self::$whereLogic, $value);
			} else {
				array_push(self::$whereParameter, $key . ' ' . $value);
			}
		}
		foreach ($values as $key => $value) {
			if($value == 'NULL'){
				array_push(self::$queryValues, '');
			} else {
				array_push(self::$queryValues, $value);
			}
		}
		return $this;
	}

	function order($parameter) {
		self::$queryStringOrder = '';
		$order_list = array();
		if(count($parameter) > 0) {
			foreach ($parameter as $key => $value) {
				$concat = $key . ' ' . strtoupper($value);
				array_push($order_list, $concat);
			}
			self::$queryStringOrder .= ' ORDER BY ' . implode(', ', $order_list);
		} else {
			self::$queryStringOrder = '';
		}
		return $this;
	}

	function select($table, $parameter = array(), $MODE = 1) {
		if($MODE == 1) {
			$this->tables = array();
			self::$queryValues = array();
			self::$queryParams = array();	
		}
		self::$limit = null;
		self::$offset = null;
		self::$queryMode = 'select';
		self::$queryString = 'SELECT ';
		$this->tables[$table] = array();
		foreach ($parameter as $key => $value) {
			array_push($this->tables[$table], $table . '.' . $value);
		}

		return $this;
	}

	function join($table, $parameter = array()) {

		$queryBuilder = array();
		if(isset($this->tables[$table])) {
			throw new QueryException('Duplicated table defined', 1);
		} else {
			self::select($table, $parameter, 2);
		}

		return $this;
	}

	function offset($parameter) {

		self::$offset = 'OFFSET ' . $parameter;
		return $this;

	}

	function limit($parameter) {

		self::$limit = 'LIMIT ' . $parameter;
		return $this;

	}

	function on($parameter = array()) {
		if(count($parameter) + 1 == count($this->tables)) {
			$buildJoin = array();
			foreach ($parameter as $key => $value) {
				array_push($buildJoin, implode(' ', $value));
			}

			self::$joinString = $buildJoin;
			return $this;
		} else {
			throw new QueryException('Invalid Join Method', 1);
		}
	}

	private function buildQuery() {
	
		$buildQuery = self::$queryString;
		if(self::$queryMode == 'select') {
			$tableArrange = array();
			$columnArrange = array();
			$buildJoinString = '';
			foreach ($this->tables as $key => $value) {
				array_push($tableArrange, $key);
				foreach ($value as $QKey => $QValue) {
					array_push($columnArrange, $QValue);
				}
				
				if($key != $tableArrange[0] && count(self::$joinString) > 0) {
					$joinString .= ' JOIN ' . $key . ' ON ' . self::$joinString[array_search($key, $tableArrange) - 1];
				}
			}
			if(count($this->tables) > 1) {
				$buildQuery .= implode(',', $columnArrange) . ' FROM ' . $tableArrange[0] . $joinString;
			} else {
				$buildQuery .= implode(',', $columnArrange) . ' FROM ' . $tableArrange[0];
			}

			if(count(self::$whereParameter) > 0) {
				$whereBuilder = array();
				foreach (self::$whereParameter as $key => $value) {
					array_push($whereBuilder, $value);
					array_push($whereBuilder, self::$whereLogic[$key]);
				}
				$buildQuery .= ' WHERE ' . implode(' ', $whereBuilder);
			}
			$buildQuery = trim($buildQuery);
			if(isset(self::$limit)) {
				if(isset(self::$offset) && intval(self::$offset) >= 0) {
					return $buildQuery . self::$queryStringOrder . ' ' . self::$limit . ' ' . self::$offset;	
				} else {
					return $buildQuery . self::$queryStringOrder . ' ' . self::$limit;
				}
			} else {
				return $buildQuery . self::$queryStringOrder;
			}
		} else if(self::$queryMode == 'insert') {
			$defineColumn = array();
			foreach (self::$queryParams as $key => $value) {
				array_push($defineColumn, '?');
			}
			$buildQuery .= $this->tables[0] . ' (' . implode(',', self::$queryParams) . ') VALUES (' . implode(',', $defineColumn) . ')';
			
			if(self::$keyType != '') {
				$buildQuery .= self::$keyType;
			}
			return $buildQuery;
		} else if(self::$queryMode == 'update') {
			$defineColumn = array();
			$buildQuery .= $this->tables[0] . ' SET ';
			$nullCol = array();
			for ($key = 0; $key < count(self::$queryParams); $key++) {
				if(is_null(self::$queryValues[$key])) {
					$buildQuery .= self::$queryParams[$key] . ' = NULL';
					//array_splice(self::$queryValues, $key, 1);
				} else {
					$buildQuery .= self::$queryParams[$key] . ' = ?';
				}
				
				if($key <= count(self::$queryParams) - 2) {
					$buildQuery .= ', ';
				} else {
					$buildQuery .= '';
				}
			}
			//$buildQuery .= implode(' = ?, ', self::$queryParams);
			if(count(self::$whereParameter) > 0) {
				$whereBuilder = array();
				foreach (self::$whereParameter as $key => $value) {
					array_push($whereBuilder, $value);
					array_push($whereBuilder, self::$whereLogic[$key]);
				}
				$buildQuery .= ' WHERE ' . implode(' ', $whereBuilder);
			}
			$buildQuery = trim($buildQuery);

			return $buildQuery;
		} else if(self::$queryMode == 'hard_delete') {
			if(count(self::$whereParameter) > 0) {
				$whereBuilder = array();
				foreach (self::$whereParameter as $key => $value) {
					array_push($whereBuilder, $value);
					array_push($whereBuilder, self::$whereLogic[$key]);
				}
				$buildQuery .= 'WHERE ' . implode(' ', $whereBuilder);
			}
			$buildQuery = trim($buildQuery);
			return $buildQuery;
		} else if(self::$queryMode == 'delete') {
			if(count(self::$whereParameter) > 0) {
				$whereBuilder = array();
				foreach (self::$whereParameter as $key => $value) {
					array_push($whereBuilder, $value);
					array_push($whereBuilder, self::$whereLogic[$key]);
				}
				$buildQuery .= 'WHERE ' . implode(' ', $whereBuilder);
			}
			$buildQuery = trim($buildQuery);
			return $buildQuery;
		} else {
			throw new QueryException('Unknown Method', 1);
		}
	}


	function execute() {
		$usedValues = array();
		try {
			$responseBuilder = array();
			$responseBuilder['response_query'] = self::buildQuery();// ⚠ AKTIFKAN HANYA PADA SAAT INGIN CEK QUERY !!
			$responseBuilder['response_values'] = self::$queryValues;
			$query = self::$pdo->prepare(self::buildQuery());
			foreach (self::$queryValues as $key => $value) {
				if(!is_null($value)) {
					array_push($usedValues, $value);
					//array_splice(self::$queryValues, $key, 1);
				}
			}
			//$query->execute(self::$queryValues);
			$query->execute($usedValues);
			
			if(self::$queryMode == 'select') {
				$read = $query->fetchAll(\PDO::FETCH_ASSOC);
				$responseBuilder['response_data'] = (count($read) > 0) ? $read : array();
			} else if(self::$queryMode == 'insert') {
				if(self::$keyType != '') {
					$getReturn = $query->fetchAll(\PDO::FETCH_ASSOC);
					$responseBuilder['response_unique'] = $getReturn[0][self::$keyReturn];
				}

				$responseBuilder['response_message'] = ($query->rowCount() > 0) ? 'Data berhasil ditambahkan' : 'Data gagal ditambahkan';
			} else if(self::$queryMode == 'update') {
				$responseBuilder['response_message'] = ($query->rowCount() > 0) ? 'Data berhasil diupdate' : 'Data gagal diupdate';
			} else if(self::$queryMode == 'delete') {
				$responseBuilder['response_message'] = ($query->rowCount() > 0) ? 'Data berhasil dihapus' : 'Data gagal dihapus';
			}
			$this->tables = array();
			self::$whereParameter = array();
			self::$joinString = array();
			self::$whereLogic = array();
			self::$queryValues = array();
			self::$queryParams = array();
			self::$queryString = '';
			self::$keyType = '';
			self::$keyReturn = '';
			self::$queryStringOrder = '';

			$responseBuilder['response_result'] = $query->rowCount();
			return $responseBuilder;
		} catch (\PDOException $e) {
			//throw new QueryException($e->getMessage(), 1);
			$responseBuilder = array();
			$responseBuilder['response_query'] = self::buildQuery();// ⚠ AKTIFKAN HANYA PADA SAAT INGIN CEK QUERY !!
			$responseBuilder['response_values'] = $usedValues;
            $responseBuilder['response_values_parse'] = implode(',' , $usedValues);
			$responseBuilder['response_params'] = self::$queryParams;
			return $responseBuilder;
		}
	}
}