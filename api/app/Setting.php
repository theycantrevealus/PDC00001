<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Setting extends Utility
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
        switch ($parameter['request']) {
            case 'tambah_setting':
                return self::tambah_setting($parameter);
                break;
            case 'edit_setting':
                return self::edit_setting($parameter);
                break;
            default:
                return array();
                break;
        }
    }

    public function __GET__($parameter = array())
    {
        switch ($parameter[1]) {
            case 'admin_load_setting':
                return self::admin_load_setting($parameter);
                break;

            case 'admin_load_setting_detail':
                return self::admin_load_setting_detail($parameter);
                break;

            case 'get_tables_list':
                return self::get_tables_list($parameter);
                break;
        }
    }

    private function get_tables_list($parameter) {
        $query = self::$pdo->prepare('SELECT table_name
                          FROM information_schema.tables
                         WHERE table_schema=\'public\'
                           AND table_type=\'BASE TABLE\' AND table_name ILIKE \'%' . $_GET['search'] . '%\' ');
        $query->execute();
        if($query->rowCount() > 0) {
            $read = $query->fetchAll(\PDO::FETCH_ASSOC);
            return $read;
        } else {
            return array();
        }
    }

    private function edit_setting($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $old = self::$query->select('setting', array(
            'id',
            'param_iden', 'param_value',
            'created_at', 'updated_at',
            'param_table_link',
            'param_table_column',
            'param_table_caption'
        ))
            ->where(array(
                'setting.id' => '= ?',
                'AND',
                'setting.deleted_at' => 'IS NULL',
            ), array(
                $parameter['id']
            ))
            ->execute();
        $worker = self::$query->update('setting', array(
            'param_iden' => $parameter['param_iden'],
            'param_value' => addslashes($parameter['param_value']),
            'param_table_link' => $parameter['param_table_link'],
            'param_table_column' => $parameter['param_table_column'],
            'param_table_caption' => $parameter['param_table_caption'],
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->where(array(
                'setting.id' => '= ?',
                'AND',
                'setting.deleted_at' => 'IS NULL',
            ), array(
                $parameter['id']
            ))
            ->execute();
        if($worker['response_result'] > 0) {
            $log = parent::log(array(
                'type' => 'activity',
                'column' => array(
                    'unique_target',
                    'user_uid',
                    'table_name',
                    'action',
                    'old_value',
                    'new_value',
                    'logged_at',
                    'status',
                    'login_id'
                ),
                'value' => array(
                    $parameter['id'],
                    $UserData['data']->uid,
                    'setting',
                    'U',
                    json_encode($old['response_data'][0]),
                    json_encode($parameter),
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class' => __CLASS__
            ));
        }
        return $worker;
    }

    private function tambah_setting($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $worker = self::$query->insert('setting', array(
            'param_iden' => $parameter['param_iden'],
            'param_value' => addslashes($parameter['param_value']),
            'param_table_link' => $parameter['param_table_link'],
            'param_table_column' => $parameter['param_table_column'],
            'param_table_caption' => $parameter['param_table_caption'],
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->returning('id')
            ->execute();
        if($worker['response_result'] > 0) {
            $log = parent::log(array(
                'type' => 'activity',
                'column' => array(
                    'unique_target',
                    'user_uid',
                    'table_name',
                    'action',
                    'new_value',
                    'logged_at',
                    'status',
                    'login_id'
                ),
                'value' => array(
                    $worker['response_unique'],
                    $UserData['data']->uid,
                    'setting',
                    'I',
                    json_encode($parameter),
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class' => __CLASS__
            ));
        }
        return $worker;
    }

    private function admin_load_setting_detail($parameter) {
        $data = self::$query->select('setting', array(
            'id',
            'param_iden', 'param_value',
            'created_at', 'updated_at',
            'param_table_link',
            'param_table_column',
            'param_table_caption'
        ))
            ->where(array(
                'setting.deleted_at' => ' IS NULL',
                'AND',
                'setting.id' => ' = ?'
            ), array(
                $parameter[2]
            ))
            ->execute();
        foreach ($data['response_data'] as $key => $value) {
            if(!empty($value['param_table_link']) && isset($value['param_table_link']) && $value['param_table_link'] !== '') {
                //Check
                $Checker = self::$query->select($value['param_table_link'], array(
                    $value['param_table_caption']
                ))
                    ->where(array(
                        $value['param_table_link'] . '.' . $value['param_table_column'] => '= ?'
                    ), array(
                        $value['param_value']
                    ))
                    ->execute();
                $data['response_data'][$key]['valid'] = $Checker['response_data'][0];
            }
        }
        return $data;
    }

    private function admin_load_setting($parameter) {
        $data = self::$query->select('setting', array(
            'id',
            'param_iden', 'param_value',
            'created_at', 'updated_at',
            'param_table_link',
            'param_table_column',
            'param_table_caption'
        ))
            ->where(array(
                'setting.deleted_at' => ' IS NULL'
            ), array())
            ->order(array(
                'created_at' => 'ASC'
            ))
            ->execute();
        foreach ($data['response_data'] as $key => $value) {
            if(!empty($value['param_table_link']) && isset($value['param_table_link']) && $value['param_table_link'] !== '') {
                //Check
                $Checker = self::$query->select($value['param_table_link'], array(
                    $value['param_table_caption']
                ))
                    ->where(array(
                        $value['param_table_link'] . '.' . $value['param_table_column'] => '= ?'
                    ), array(
                        $value['param_value']
                    ))
                    ->execute();
                $data['response_data'][$key]['valid'] = $Checker['response_data'];
            }
        }
        return $data;
    }
}