<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Documentation extends Utility
{
    static $pdo;
    static $query;

    protected static function getConn()
    {
        return self::$pdo;
    }

    public function __construct($connection) {
        self::$pdo = $connection;
        self::$query = new Query(self::$pdo);
    }

    public function __POST__($parameter = array()) {
        switch ($parameter['request']) {
            case 'add_folder':
                return self::add_folder($parameter);
                break;
            case 'edit_folder':
                return self::edit_folder($parameter);
                break;
            case 'add_file':
                return self::add_file($parameter);
                break;
            case 'edit_file':
                return self::edit_file($parameter);
                break;
            default:
                return $parameter;
        }
    }

    public function __GET__($parameter = array()) {
        switch ($parameter[1]) {
            case 'get_structure':
                return self::get_structure(0);
                break;
            case 'file_detail':
                return self::get_file_content($parameter[2]);
                break;
            default:
                return $parameter;
        }
    }

    private function get_structure($parameter) {
        $collected = array();
        $data = self::$query->select('documentation_folder', array(
            'id', 'name', 'shown', 'parent', 'show_order', 'created_at'
        ))
            ->where(array(
                'documentation_folder.deleted_at' => 'IS NULL',
                'AND',
                'documentation_folder.parent' => '= ?',
            ), array(
                strval($parameter)
            ))
            ->order(array(
                'show_order' => 'ASC'
            ))
            ->execute();
        foreach ($data['response_data'] as $key => $value) {
            array_push($collected, array(
                'id' => $value['id'],
                'text' => $value['name'],
                'type' => (intval($parameter) === 0) ? 'root' : 'child',
                'itemType' => 'folder',
                'state' => array(
                    'selected' => true
                ),
                'children' => self::get_structure($value['id'])
            ));
        }

        //File
        if(intval($parameter) > 0) {
            $file = self::$query->select('documentation_file', array(
                'id', 'folder', 'shown', 'show_order', 'title'
            ))
                ->where(array(
                    'documentation_file.folder' => '= ?',
                    'AND',
                    'documentation_file.deleted_at' => 'IS NULL'
                ), array(
                    strval($parameter)
                ))
                ->order(array(
                    'show_order' => 'ASC'
                ))
                ->execute();
            foreach ($file['response_data'] as $fKey => $fValue) {
                array_push($collected, array(
                    'id' => strval($parameter) . '-' . $fValue['id'],
                    'text' => $fValue['title'],
                    'icon' => 'fa fa-file-alt',
                    'type' => 'child',
                    'itemType' => 'file',
                    'state' => array(
                        'selected' => true
                    ),
                    'children' => array()
                ));
            }
        }

        return $collected;
    }

    private function get_folder_detail($parameter) {
        $data = self::$query->select('documentation_folder', array(
            'id', 'name', 'shown', 'parent', 'show_order', 'created_at'
        ))
            ->where(array(
                'documentation_folder.deleted_at' => 'IS NULL',
                'AND',
                'documentation_folder.id' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        return $data;
    }

    private function edit_folder($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $old = self::get_folder_detail($parameter['id']);

        $worker = self::$query->update('documentation_folder', array(
            'name' => $parameter['name']
        ))
            ->where(array(
                'documentation_folder.deleted_at' => 'IS NULL',
                'AND',
                'documentation_folder.id' => '= ?'
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
                    'documentation_folder',
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

    private function add_folder($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $nextOrder = self::$query->select('documentation_folder', array(
            'id'
        ))
            ->where(array(
                'documentation_folder.parent' => '= ?'
            ), array(
                $parameter['parent']
            ))
            ->execute();

        $worker = self::$query->insert('documentation_folder', array(
            'name' => $parameter['name'],
            'shown' => 'Y',
            'parent' => $parameter['parent'],
            'show_order' => count($nextOrder['response_data']) + 1,
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->returning('id')
            ->execute();
        if ($worker['response_result'] > 0) {
            $log = parent::log(array(
                'type' => 'activity',
                'column' => array(
                    'unique_target',
                    'user_uid',
                    'table_name',
                    'action',
                    'logged_at',
                    'status',
                    'login_id'
                ),
                'value' => array(
                    $worker['response_unique'],
                    $UserData['data']->uid,
                    'documentation_folder',
                    'I',
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class' => __CLASS__
            ));
        }
        return $worker;
    }

    private function get_file_content($parameter) {
        $id = explode('-', $parameter);
        $id = $id[count($id) - 1];

        $data = self::$query->select('documentation_file', array(
            'id', 'folder', 'shown', 'show_order', 'title', 'content'
        ))
            ->where(array(
                'documentation_file.deleted_at' => 'IS NULL',
                'AND',
                'documentation_file.id' => '= ?'
            ), array(
                $id
            ))
            ->execute();
        return $data;
    }

    private function get_file_detail($parameter) {
        $data = self::$query->select('documentation_file', array(
            'id', 'folder', 'shown', 'show_order', 'title'
        ))
            ->where(array(
                'documentation_file.deleted_at' => 'IS NULL',
                'AND',
                'documentation_file.id' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        return $data;
    }




    private function add_file($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $nextOrder = self::$query->select('documentation_file', array(
            'id'
        ))
            ->where(array(
                'documentation_file.folder' => '= ?'
            ), array(
                $parameter['folder']
            ))
            ->execute();

        $worker = self::$query->insert('documentation_file', array(
            'title' => $parameter['name'],
            'shown' => 'Y',
            'folder' => $parameter['folder'],
            'show_order' => count($nextOrder['response_data']) + 1,
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->returning('id')
            ->execute();
        if ($worker['response_result'] > 0) {
            $log = parent::log(array(
                'type' => 'activity',
                'column' => array(
                    'unique_target',
                    'user_uid',
                    'table_name',
                    'action',
                    'logged_at',
                    'status',
                    'login_id'
                ),
                'value' => array(
                    $worker['response_unique'],
                    $UserData['data']->uid,
                    'documentation_file',
                    'I',
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class' => __CLASS__
            ));
        }
        return $worker;
    }


    private function edit_file($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $id = explode('-', $parameter['id']);
        $id = $id[count($id) - 1];

        $old = self::get_file_detail($id);

        $worker = self::$query->update('documentation_file', array(
            'title' => $parameter['name'],
            'content' => $parameter['content']
        ))
            ->where(array(
                'documentation_file.deleted_at' => 'IS NULL',
                'AND',
                'documentation_file.id' => '= ?'
            ), array(
                $id
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
                    $id,
                    $UserData['data']->uid,
                    'documentation_file',
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
}

?>