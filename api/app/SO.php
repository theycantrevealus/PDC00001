<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class SO extends Utility {
	static $pdo;
	static $query;
	
	protected static function getConn(){
		return self::$pdo;
	}

	public function __construct($connection) {
		self::$pdo = $connection;
		self::$query = new Query(self::$pdo);
	}

    public function __POST__($parameter = array()) {
        switch ($parameter['request']) {
            case 'so_import_fetch':
                return self::so_import_fetch($parameter);
                break;
            default:
                return array();
                break;
        }
    }

    private function so_import_fetch($parameter) {
        //$Inventori = new Inventori(self::$pdo);

        if (!empty($_FILES['csv_file']['name'])) {
            $unique_name = array();

            $file_data = fopen($_FILES['csv_file']['tmp_name'], 'r');
            $column = fgetcsv($file_data); //array_head
            $row_data = array();
            while ($row = fgetcsv($file_data)) {
                if (!in_array($row[0], $unique_name)) {
                    array_push($unique_name, $row[0]);
                    $column_builder = array();
                    foreach ($column as $key => $value) {
                        $column_builder[$value] = $row[$key];
                    }

                    

                    //Check Obat di master
                    $CheckMasterObat = self::$query->select('master_inv', array(
                        'uid'
                    ))
                        ->where(array(
                            'master_inv.nama' => 'ILIKE ' . '\'%' . $column_builder['nama'] . '%\''
                        ), array())
                        ->execute();
                        
                    $column_builder['exist_master'] = count($CheckMasterObat['response_data']);
                    array_push($row_data, $column_builder);
                }
            }

            $build_col = array();
            foreach ($column as $key => $value) {
                array_push($build_col, array("data" => $value));
            }

            $output = array(
                'column' => $column,
                'row_data' => $row_data,
                'column_builder' => $build_col
            );
            return $output;
        }

        return '321';
    }

}


?>