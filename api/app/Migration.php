<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Migration extends Utility
{
    static $pdo;
    static $query;

    protected static function getConn()
    {
        return self::$pdo;
    }

    public function __construct($connection)
    {
        self::$pdo = $connection;
        self::$query = new Query(self::$pdo);
    }

    public function __POST__($parameter = array())
    {
        try {
            switch ($parameter['request']) {
                case 'populate_table':
                    return self::populate_table($parameter);
                    break;
                case 'sync_table':
                    return self::sync_table();
                    break;
                default:
                    return array();
            }
        } catch (QueryException $e) {
            return 'Error => ' . $e;
        }
    }

    private function check_current_table() {
        $tables = self::$query->select('information_schema', array())
            ->execute();
        return $tables;
    }

    private function populate_column($parameter) {
        //
    }

    private function proceed_table($parameter) {

    }

    private function sync_table() {

        $error_save = array();

        $sequences_data = self::$query->select('migration_sequences', array(
            'oid',
            'relname',
            'relnamespace',
            'reltype',
            'reloftype',
            'relowner',
            'relam',
            'relfilenode',
            'reltablespace',
            'relpages',
            'reltuples',
            'relallvisible',
            'reltoastrelid',
            'relhasindex',
            'relisshared',
            'relpersistence',
            'relkind',
            'relnatts',
            'relchecks',
            'relhasrules',
            'relhastriggers',
            'relhassubclass',
            'relrowsecurity',
            'relforcerowsecurity',
            'relispopulated',
            'relreplident',
            'relispartition',
            'relrewrite',
            'relfrozenxid',
            'relminmxid',
            'relacl',
            'reloptions',
            'relpartbound'
        ))
            ->where(array(
                'migration_sequences.deleted_at' => 'IS NULL'
            ))
            ->execute();
        foreach ($sequences_data['response_data'] as $key => $value) {
            $__SEQUENCES = self::$query->select('pg_class', array(
                'oid'
            ))
                ->where(array(
                    'pg_class.relkind' => '= ?',
                    'AND',
                    'pg_class.relname' => '= ?'
                ), array(
                    'S', $value['relname']
                ))
                ->execute();

            if(count($__SEQUENCES['response_data']) < 1) {
                $__SEQUENCES_proceed = self::$query->insert('pg_class', array(
                    'oid' => $value['oid'],
                    'relname' => $value['relname'],
                    'relnamespace' => $value['relnamespace'],
                    'reltype' => $value['reltype'],
                    'reloftype' => $value['reloftype'],
                    'relowner' => $value['relowner'],
                    'relam' => $value['relam'],
                    'relfilenode' => $value['relfilenode'],
                    'reltablespace' => $value['reltablespace'],
                    'relpages' => $value['relpages'],
                    'reltuples' => $value['reltuples'],
                    'relallvisible' => $value['relallvisible'],
                    'reltoastrelid' => $value['reltoastrelid'],
                    'relhasindex' => $value['relhasindex'],
                    'relisshared' => $value['relisshared'],
                    'relpersistence' => $value['relpersistence'],
                    'relkind' => $value['relkind'],
                    'relnatts' => $value['relnatts'],
                    'relchecks' => $value['relchecks'],
                    'relhasrules' => $value['relhasrules'],
                    'relhastriggers' => $value['relhastriggers'],
                    'relhassubclass' => $value['relhassubclass'],
                    'relrowsecurity' => $value['relrowsecurity'],
                    'relforcerowsecurity' => $value['relforcerowsecurity'],
                    'relispopulated' => $value['relispopulated'],
                    'relreplident' => $value['relreplident'],
                    'relispartition' => $value['relispartition'],
                    'relrewrite' => $value['relrewrite'],
                    'relfrozenxid' => $value['relfrozenxid'],
                    'relminmxid' => $value['relminmxid'],
                    'relacl' => $value['relacl'],
                    'reloptions' => $value['reloptions'],
                    'relpartbound' => $value['relpartbound']
                ))
                    ->execute();
            }
        }



        $exclude_column = array('table_id', 'created_at', 'updated_at', 'deleted_at');

        //Get table migration
        $tables = self::$query->select('migration_tables', array(
            'id',
            'name',
            'schemaname',
            'tableowner',
            'tablespace',
            'hasindexes',
            'hasrules',
            'hastriggers',
            'rowsecurity'
        ))
            ->where(array(
                'migration_tables.deleted_at' => 'IS NULL'
            ))
            ->execute();
        foreach ($tables['response_data'] as $key => $value) {
            //Check local structure
            $__TABLES = self::$query->select('pg_catalog.pg_tables', array(
                'schemaname',
                'tablename',
                'tableowner',
                'tablespace',
                'hasindexes',
                'hasrules',
                'hastriggers',
                'rowsecurity'
            ))
                ->where(array(
                    '(NOT pg_catalog.pg_tables.schemaname' => '= ?',
                    'AND',
                    'NOT pg_catalog.pg_tables.schemaname' => '= ?)',
                    'AND',
                    'pg_catalog.pg_tables.tablename' => '= ?'
                ), array(
                    'pg_catalog', 'information_schema', $value['name']
                ))
                ->execute();
            if(count($__TABLES['response_data']) > 0) {
                $proceed_table = self::$query->update('pg_catalog.pg_tables', array(
                    'tablespace' => isset($value['tablespace']) ? $value['tablespace'] : '',
                    'hasindexes' => isset($value['hasindexes']) ? $value['hasindexes'] : '',
                    'hasrules' => isset($value['hasrules']) ? $value['hasrules'] : '',
                    'hastriggers' => isset($value['hastriggers']) ? $value['hastriggers'] : '',
                    'rowsecurity' => isset($value['rowsecurity']) ? $value['rowsecurity'] : ''
                ))
                    ->where(array(
                        'pg_catalog.pg_tables.tablename' => '= ?'
                    ), array(
                        $value['name']
                    ))
                    ->execute();
            } else {
                //Create Table
                $proceed_table = self::$query->insert('pg_catalog.pg_tables', array(
                    'tablename' => $value['name'],
                    'tableowner' => isset($value['tableowner']) ? $value['tableowner'] : '',
                    'tablespace' => isset($value['tablespace']) ? $value['tablespace'] : '',
                    'hasindexes' => isset($value['hasindexes']) ? $value['hasindexes'] : '',
                    'hasrules' => isset($value['hasrules']) ? $value['hasrules'] : '',
                    'hastriggers' => isset($value['hastriggers']) ? $value['hastriggers'] : '',
                    'rowsecurity' => isset($value['rowsecurity']) ? $value['rowsecurity'] : ''
                ))
                    ->execute();
            }











            //Sync Column
            $columns = self::$query->select('migration_columns', array(
                'table_id',
                'table_catalog',
                'table_schema',
                'table_name',
                'column_name',
                'ordinal_position',
                'column_default',
                'is_nullable',
                'data_type',
                'character_maximum_length',
                'character_octet_length',
                'numeric_precision',
                'numeric_precision_radix',
                'numeric_scale',
                'datetime_precision',
                'interval_type',
                'interval_precision',
                'character_set_catalog',
                'character_set_schema',
                'character_set_name',
                'collation_catalog',
                'collation_schema',
                'collation_name',
                'domain_catalog',
                'domain_schema',
                'domain_name',
                'udt_catalog',
                'udt_schema',
                'udt_name',
                'scope_catalog',
                'scope_schema',
                'scope_name',
                'maximum_cardinality',
                'dtd_identifier',
                'is_self_referencing',
                'is_identity',
                'identity_generation',
                'identity_start',
                'identity_increment',
                'identity_maximum',
                'identity_minimum',
                'identity_cycle',
                'is_generated',
                'generation_expression',
                'is_updatable'
            ))
                ->where(array(
                    'migration_columns.deleted_at' => 'IS NULL',
                    'AND',
                    'migration_columns.table_name' => '= ?'
                ), array(
                    $value['name']
                ))
                ->execute();
            foreach ($columns['response_data'] as $CKey => $CValue) {
                $__COLUMNS = self::$query->select('information_schema.columns', array(
                    'table_catalog',
                    'table_schema',
                    'table_name',
                    'column_name',
                    'ordinal_position',
                    'column_default',
                    'is_nullable',
                    'data_type',
                    'character_maximum_length',
                    'character_octet_length',
                    'numeric_precision',
                    'numeric_precision_radix',
                    'numeric_scale',
                    'datetime_precision',
                    'interval_type',
                    'interval_precision',
                    'character_set_catalog',
                    'character_set_schema',
                    'character_set_name',
                    'collation_catalog',
                    'collation_schema',
                    'collation_name',
                    'domain_catalog',
                    'domain_schema',
                    'domain_name',
                    'udt_catalog',
                    'udt_schema',
                    'udt_name',
                    'scope_catalog',
                    'scope_schema',
                    'scope_name',
                    'maximum_cardinality',
                    'dtd_identifier',
                    'is_self_referencing',
                    'is_identity',
                    'identity_generation',
                    'identity_start',
                    'identity_increment',
                    'identity_maximum',
                    'identity_minimum',
                    'identity_cycle',
                    'is_generated',
                    'generation_expression',
                    'is_updatable'
                ))
                    ->where(array(
                        'information_schema.columns.table_schema' => '= ?',
                        'AND',
                        'information_schema.columns.table_name' => '= ?'
                    ), array(
                        'public',
                        $value['tablename']
                    ))
                    ->execute();
                if(count($__COLUMNS['response_data']) > 0) {
                    $columnItem = array(
                        'table_catalog' => '',
                        'ordinal_position' => '',
                        'column_default' => '',
                        'is_nullable' => '',
                        'data_type' => '',
                        'character_maximum_length' => '',
                        'character_octet_length' => '',
                        'numeric_precision' => '',
                        'numeric_precision_radix' => '',
                        'numeric_scale' => '',
                        'datetime_precision' => '',
                        'interval_type' => '',
                        'interval_precision' => '',
                        'character_set_catalog' => '',
                        'character_set_schema' => '',
                        'character_set_name' => '',
                        'collation_catalog' => '',
                        'collation_schema' => '',
                        'collation_name' => '',
                        'domain_catalog' => '',
                        'domain_schema' => '',
                        'domain_name' => '',
                        'udt_catalog' => '',
                        'udt_schema' => '',
                        'udt_name' => '',
                        'scope_catalog' => '',
                        'scope_schema' => '',
                        'scope_name' => '',
                        'maximum_cardinality' => '',
                        'dtd_identifier' => '',
                        'is_self_referencing' => '',
                        'is_identity' => '',
                        'identity_generation' => '',
                        'identity_start' => '',
                        'identity_increment' => '',
                        'identity_maximum' => '',
                        'identity_minimum' => '',
                        'identity_cycle' => '',
                        'is_generated' => '',
                        'generation_expression' => '',
                        'is_updatable' => ''
                    );

                    foreach ($columnItem as $CIKey => $CIValue) {
                        $columnItem[$CIKey] = $CValue[$CIKey];
                    }


                    $save_column = self::$query->update('information_schema.columns', $columnItem)
                        ->where(array(
                            'information_schema.columns.column_name' => '= ?',
                            'AND',
                            'information_schema.columns.table_catalog' => '= ?',
                            'AND',
                            'information_schema.columns.table_schema' => '= ?',
                        ), array(
                            $CValue['table_name'],
                            $CValue['table_catalog'],
                            $CValue['table_schema']
                        ))
                        ->execute();
                    if($save_column['response_result'] < 1) {
                        array_push($error_save, $save_column);
                    }
                } else {
                    $columnItem = array(
                        'table_catalog' => '',
                        'table_schema' => '',
                        'table_name' => '',
                        'column_name' => '',
                        'ordinal_position' => '',
                        'column_default' => '',
                        'is_nullable' => '',
                        'data_type' => '',
                        'character_maximum_length' => '',
                        'character_octet_length' => '',
                        'numeric_precision' => '',
                        'numeric_precision_radix' => '',
                        'numeric_scale' => '',
                        'datetime_precision' => '',
                        'interval_type' => '',
                        'interval_precision' => '',
                        'character_set_catalog' => '',
                        'character_set_schema' => '',
                        'character_set_name' => '',
                        'collation_catalog' => '',
                        'collation_schema' => '',
                        'collation_name' => '',
                        'domain_catalog' => '',
                        'domain_schema' => '',
                        'domain_name' => '',
                        'udt_catalog' => '',
                        'udt_schema' => '',
                        'udt_name' => '',
                        'scope_catalog' => '',
                        'scope_schema' => '',
                        'scope_name' => '',
                        'maximum_cardinality' => '',
                        'dtd_identifier' => '',
                        'is_self_referencing' => '',
                        'is_identity' => '',
                        'identity_generation' => '',
                        'identity_start' => '',
                        'identity_increment' => '',
                        'identity_maximum' => '',
                        'identity_minimum' => '',
                        'identity_cycle' => '',
                        'is_generated' => '',
                        'generation_expression' => '',
                        'is_updatable' => ''
                    );

                    foreach ($columnItem as $CIKey => $CIValue) {
                        $columnItem[$CIKey] = $CValue[$CIKey];
                    }


                    $save_column = self::$query->insert('information_schema.columns', $columnItem)
                        ->execute();
                    if($save_column['response_result'] < 1) {
                        array_push($error_save, $save_column);
                    }
                }
            }
        }

        return $error_save;
    }

    private function populate_table($parameter) {

        $delete_tables = self::$query->delete('migration_tables')->execute();
        $delete_columns = self::$query->delete('migration_columns')->execute();
        $exclude_column = array('id', 'table_id', 'created_at', 'updated_at', 'deleted_at');
        $error_save = array();











        $sequences_data = self::$query->select('pg_class', array(
            'oid',
            'relname',
            'relnamespace',
            'reltype',
            'reloftype',
            'relowner',
            'relam',
            'relfilenode',
            'reltablespace',
            'relpages',
            'reltuples',
            'relallvisible',
            'reltoastrelid',
            'relhasindex',
            'relisshared',
            'relpersistence',
            'relkind',
            'relnatts',
            'relchecks',
            'relhasrules',
            'relhastriggers',
            'relhassubclass',
            'relrowsecurity',
            'relforcerowsecurity',
            'relispopulated',
            'relreplident',
            'relispartition',
            'relrewrite',
            'relfrozenxid',
            'relminmxid',
            'relacl',
            'reloptions',
            'relpartbound'
        ))
            ->where(array(
                'pg_class.relkind' => '= ?'
            ), array(
                'S'
            ))
            ->execute();

        foreach ($sequences_data['response_data'] as $key => $value) {
            $check_seq = self::$query->select('migration_sequences', array(
                'id'
            ))
                ->where(array(
                    'migration_sequences.relname' => '= ?'
                ), array(
                    $value['relname']
                ))
                ->execute();
            if(count($check_seq['response_data']) > 0) {
                $selected_seq = $check_seq['response_data'][0]['id'];
                $accepted_seq = array(
                    'oid' => '',
                    'relname' => '',
                    'relnamespace' => '',
                    'reltype' => '',
                    'reloftype' => '',
                    'relowner' => '',
                    'relam' => '',
                    'relfilenode' => '',
                    'reltablespace' => '',
                    'relpages' => '',
                    'reltuples' => '',
                    'relallvisible' => '',
                    'reltoastrelid' => '',
                    'relhasindex' => '',
                    'relisshared' => '',
                    'relpersistence' => '',
                    'relkind' => '',
                    'relnatts' => '',
                    'relchecks' => '',
                    'relhasrules' => '',
                    'relhastriggers' => '',
                    'relhassubclass' => '',
                    'relrowsecurity' => '',
                    'relforcerowsecurity' => '',
                    'relispopulated' => '',
                    'relreplident' => '',
                    'relispartition' => '',
                    'relrewrite' => '',
                    'relfrozenxid' => '',
                    'relminmxid' => '',
                    'relacl' => '',
                    'reloptions' => '',
                    'relpartbound' => '',
                    'updated_at' => parent::format_date(),
                    'deleted_at' => NULL
                );

                foreach ($accepted_seq as $seqKey => $seqValue) {
                    if(isset($value[$seqKey])) {

                        if(!in_array($seqKey, $exclude_column)) {
                            $accepted_seq[$seqKey] = $value[$seqKey];
                        } else {
                            $accepted_seq[$seqKey] = $seqValue;;
                        }
                    } else {
                        if(!in_array($seqKey, $exclude_column)) {
                            $accepted_seq[$seqKey] = $value[$seqKey];
                        }
                    }
                }


                $proceed_seq = self::$query->update('migration_sequences', $accepted_seq)
                    ->where(array(
                        'migration_sequences.id' => '= ?'
                    ), array(
                        $selected_seq
                    ))
                    ->execute();
            } else {
                $accepted_seq = array(
                    'oid' => '',
                    'relname' => '',
                    'relnamespace' => '',
                    'reltype' => '',
                    'reloftype' => '',
                    'relowner' => '',
                    'relam' => '',
                    'relfilenode' => '',
                    'reltablespace' => '',
                    'relpages' => '',
                    'reltuples' => '',
                    'relallvisible' => '',
                    'reltoastrelid' => '',
                    'relhasindex' => '',
                    'relisshared' => '',
                    'relpersistence' => '',
                    'relkind' => '',
                    'relnatts' => '',
                    'relchecks' => '',
                    'relhasrules' => '',
                    'relhastriggers' => '',
                    'relhassubclass' => '',
                    'relrowsecurity' => '',
                    'relforcerowsecurity' => '',
                    'relispopulated' => '',
                    'relreplident' => '',
                    'relispartition' => '',
                    'relrewrite' => '',
                    'relfrozenxid' => '',
                    'relminmxid' => '',
                    'relacl' => '',
                    'reloptions' => '',
                    'relpartbound' => '',
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                );

                foreach ($accepted_seq as $seqKey => $seqValue) {
                    if(isset($value[$seqKey])) {
                        if(!in_array($seqKey, $exclude_column)) {
                            $accepted_seq[$seqKey] = $value[$seqKey];
                        } else {
                            $accepted_seq[$seqKey] = $seqValue;;
                        }
                    } else {
                        if(!in_array($seqKey, $exclude_column)) {
                            $accepted_seq[$seqKey] = '';
                        }
                    }
                }

                $proceed_seq = self::$query->insert('migration_sequences', $accepted_seq)
                    ->execute();
            }

            if($proceed_seq['response_result'] < 1) {
                array_push($error_save, $proceed_seq);
            }
        }
















        $tables = self::$query->select('pg_catalog.pg_tables', array(
            'schemaname',
            'tablename',
            'tableowner',
            'tablespace',
            'hasindexes',
            'hasrules',
            'hastriggers',
            'rowsecurity'
        ))
            ->where(array(
                '(NOT pg_catalog.pg_tables.schemaname' => '= ?',
                'AND',
                'NOT pg_catalog.pg_tables.schemaname' => '= ?)'
            ), array(
                'pg_catalog', 'information_schema'
            ))
            ->execute();

        if(count($tables['response_data']) > 0) {
            foreach ($tables['response_data'] as $key => $value) {
                $targetTable = 0;
                $check_table = self::$query->select('migration_tables', array(
                    'id'
                ))
                    ->where(array(
                        'migration_tables.name' => '= ?'
                    ), array(
                        $value['tablename']
                    ))
                    ->execute();
                if(count($check_table['response_data']) > 0) {
                    $targetTable = $check_table['response_data'][0]['id'];
                    $save_table = self::$query->update('migration_tables', array(
                        'name' => $value['tablename'],
                        'schemaname' => isset($value['schemaname']) ? $value['schemaname'] : '',
                        'tableowner' => isset($value['tableowner']) ? $value['tableowner'] : '',
                        'tablespace' => isset($value['tablespace']) ? $value['tablespace'] : '',
                        'hasindexes' => isset($value['hasindexes']) ? $value['hasindexes'] : '',
                        'hasrules' => isset($value['hasrules']) ? $value['hasrules'] : '',
                        'hastriggers' => isset($value['hastriggers']) ? $value['hastriggers'] : '',
                        'rowsecurity' => isset($value['rowsecurity']) ? $value['rowsecurity'] : '',
                        'updated_at' => parent::format_date(),
                        'deleted_at' => NULL
                    ))
                        ->where(array(
                            'migration_tables.id' => '= ?'
                        ), array(
                            $targetTable
                        ))
                        ->execute();
                } else {
                    $save_table = self::$query->insert('migration_tables', array(
                        'name' => $value['tablename'],
                        'schemaname' => isset($value['schemaname']) ? $value['schemaname'] : '',
                        'tableowner' => isset($value['tableowner']) ? $value['tableowner'] : '',
                        'tablespace' => isset($value['tablespace']) ? $value['tablespace'] : '',
                        'hasindexes' => isset($value['hasindexes']) ? $value['hasindexes'] : '',
                        'hasrules' => isset($value['hasrules']) ? $value['hasrules'] : '',
                        'hastriggers' => isset($value['hastriggers']) ? $value['hastriggers'] : '',
                        'rowsecurity' => isset($value['rowsecurity']) ? $value['rowsecurity'] : '',
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                    $targetTable = $save_table['response_unique'];
                }


                if($save_table['response_result'] > 0) {
                    $columns = self::$query->select('information_schema.columns', array(
                        'table_catalog',
                        'table_schema',
                        'table_name',
                        'column_name',
                        'ordinal_position',
                        'column_default',
                        'is_nullable',
                        'data_type',
                        'character_maximum_length',
                        'character_octet_length',
                        'numeric_precision',
                        'numeric_precision_radix',
                        'numeric_scale',
                        'datetime_precision',
                        'interval_type',
                        'interval_precision',
                        'character_set_catalog',
                        'character_set_schema',
                        'character_set_name',
                        'collation_catalog',
                        'collation_schema',
                        'collation_name',
                        'domain_catalog',
                        'domain_schema',
                        'domain_name',
                        'udt_catalog',
                        'udt_schema',
                        'udt_name',
                        'scope_catalog',
                        'scope_schema',
                        'scope_name',
                        'maximum_cardinality',
                        'dtd_identifier',
                        'is_self_referencing',
                        'is_identity',
                        'identity_generation',
                        'identity_start',
                        'identity_increment',
                        'identity_maximum',
                        'identity_minimum',
                        'identity_cycle',
                        'is_generated',
                        'generation_expression',
                        'is_updatable'
                    ))
                        ->where(array(
                            'information_schema.columns.table_schema' => '= ?',
                            'AND',
                            'information_schema.columns.table_name' => '= ?'
                        ), array(
                            'public',
                            $value['tablename']
                        ))
                        ->execute();
                    $tables['response_data'][$key]['columns'] = $columns['response_data'];
                    foreach ($columns['response_data'] as $CKey => $CValue) {
                        $check_column = self::$query->select('migration_columns', array(
                            'id'
                        ))
                            ->where(array(
                                'migration_columns.column_name' => '= ?'
                            ), array(
                                $CValue['column_name']
                            ))
                            ->execute();
                        if(count($check_column['response_data']) > 0) {
                            $columnItem = array(
                                'table_catalog' => '',
                                'table_schema' => '',
                                'table_name' => '',
                                'column_name' => '',
                                'ordinal_position' => '',
                                'column_default' => '',
                                'is_nullable' => '',
                                'data_type' => '',
                                'character_maximum_length' => '',
                                'character_octet_length' => '',
                                'numeric_precision' => '',
                                'numeric_precision_radix' => '',
                                'numeric_scale' => '',
                                'datetime_precision' => '',
                                'interval_type' => '',
                                'interval_precision' => '',
                                'character_set_catalog' => '',
                                'character_set_schema' => '',
                                'character_set_name' => '',
                                'collation_catalog' => '',
                                'collation_schema' => '',
                                'collation_name' => '',
                                'domain_catalog' => '',
                                'domain_schema' => '',
                                'domain_name' => '',
                                'udt_catalog' => '',
                                'udt_schema' => '',
                                'udt_name' => '',
                                'scope_catalog' => '',
                                'scope_schema' => '',
                                'scope_name' => '',
                                'maximum_cardinality' => '',
                                'dtd_identifier' => '',
                                'is_self_referencing' => '',
                                'is_identity' => '',
                                'identity_generation' => '',
                                'identity_start' => '',
                                'identity_increment' => '',
                                'identity_maximum' => '',
                                'identity_minimum' => '',
                                'identity_cycle' => '',
                                'is_generated' => '',
                                'generation_expression' => '',
                                'is_updatable' => '',
                                'updated_at' => parent::format_date(),
                                'deleted_at' => NULL
                            );

                            foreach ($columnItem as $CIKey => $CIValue) {
                                if(isset($CValue[$CIKey])) {
                                    if(!in_array($CIKey,$exclude_column)) {
                                        $columnItem[$CIKey] = $CValue[$CIKey];
                                    } else {
                                        $columnItem[$CIKey] = $CIValue;;
                                    }
                                } else {
                                    if(!in_array($CIKey, $exclude_column)) {
                                        $columnItem[$CIKey] = '';
                                    }
                                }
                            }


                            $save_column = self::$query->update('migration_columns', $columnItem)
                                ->where(array(
                                    'migration_columns.id' => '= ?'
                                ), array(
                                    $check_column['response_data'][0]['id']
                                ))
                                ->execute();
                            if($save_column['response_result'] < 1) {
                                array_push($error_save, $save_column);
                            }
                        } else {
                            $columnItem = array(
                                'table_id' => intval($targetTable),
                                'table_catalog' => '',
                                'table_schema' => '',
                                'table_name' => '',
                                'column_name' => '',
                                'ordinal_position' => '',
                                'column_default' => '',
                                'is_nullable' => '',
                                'data_type' => '',
                                'character_maximum_length' => '',
                                'character_octet_length' => '',
                                'numeric_precision' => '',
                                'numeric_precision_radix' => '',
                                'numeric_scale' => '',
                                'datetime_precision' => '',
                                'interval_type' => '',
                                'interval_precision' => '',
                                'character_set_catalog' => '',
                                'character_set_schema' => '',
                                'character_set_name' => '',
                                'collation_catalog' => '',
                                'collation_schema' => '',
                                'collation_name' => '',
                                'domain_catalog' => '',
                                'domain_schema' => '',
                                'domain_name' => '',
                                'udt_catalog' => '',
                                'udt_schema' => '',
                                'udt_name' => '',
                                'scope_catalog' => '',
                                'scope_schema' => '',
                                'scope_name' => '',
                                'maximum_cardinality' => '',
                                'dtd_identifier' => '',
                                'is_self_referencing' => '',
                                'is_identity' => '',
                                'identity_generation' => '',
                                'identity_start' => '',
                                'identity_increment' => '',
                                'identity_maximum' => '',
                                'identity_minimum' => '',
                                'identity_cycle' => '',
                                'is_generated' => '',
                                'generation_expression' => '',
                                'is_updatable' => '',
                                'created_at' => parent::format_date(),
                                'updated_at' => parent::format_date()
                            );

                            foreach ($columnItem as $CIKey => $CIValue) {
                                if(isset($CValue[$CIKey])) {
                                    if(!in_array($CIKey, $exclude_column)) {
                                        $columnItem[$CIKey] = $CValue[$CIKey];
                                    } else {
                                        $columnItem[$CIKey] = $CIValue;
                                    }
                                } else {
                                    if(!in_array($CIKey, $exclude_column)) {
                                        $columnItem[$CIKey] = '';
                                    }
                                }
                            }


                            $save_column = self::$query->insert('migration_columns', $columnItem)
                                ->execute();
                            if($save_column['response_result'] < 1) {
                                array_push($error_save, $save_column);
                            }
                        }
                    }
                }
            }
        }

        return array(
            //'seq' => $sequences_data['response_data'],
            'errors' => $error_save
        );
    }
}

?>