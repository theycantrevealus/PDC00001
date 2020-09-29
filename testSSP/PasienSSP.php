<?php
 
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'pasien';
 
// Table's primary key
$primaryKey = 'uid';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'uid','dt' => 0 ),
    array( 'db' => 'no_rm','dt' => 1 ),
    array( 'db' => 'panggilan','dt' => 2 ),
    array( 'db' => 'tanggal_lahir', 'dt' => 3 ),
    array( 'db' => 'jenkel', 'dt' => 4 )
);

        
// SQL server connection information
$sql_details = array(
    'user' => 'postgres',
    'pass' => '123456medandev',
    'db'   => 'rsudpb',
    'host' => 'localhost',
    'port' => '5432'
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require('SSP.php');
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);