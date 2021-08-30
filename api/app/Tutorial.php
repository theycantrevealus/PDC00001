<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Tutorial extends Utility
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
            case 'get_tutorial':
                return self::get_tutorial($parameter);
                break;

            case 'add_tutorial':
                return self::add_tutorial($parameter);
                break;

            case 'update_tutorial':
                return self::update_tutorial($parameter);
                break;

            case 'update_position':
                return self::update_position($parameter);
                break;

            case 'add_group':
                return self::add_group($parameter);
                break;

            case 'update_group':
                return self::update_group($parameter);
                break;

            case 'delete_group':
                return self::delete_group($parameter);
                break;

            case 'delete_tutor':
                return self::delete_tutor($parameter);
                break;
        }
    }

    public function __GET__($parameter = array()) {
        switch ($parameter[1])
        {
            case 'get_tutorial':
                return self::build_tutorial($parameter[2]);
                break;

            case 'get_detail':
                return self::get_detail($parameter[2]);
                break;

            case 'load_group':
                return self::load_group($parameter[2]);
                    break;

            default:
                return array();
                break;
        }
    }

    private function add_group($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $data = self::$query->insert('intro_group', array(
            'uid' => parent::gen_uuid(),
            'nama' => $parameter['nama'],
            'modul' => $parameter['modul'],
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->execute();
        return $data;
    }

    private function delete_tutor($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $data = self::$query->update('intro', array(
            'deleted_at' => parent::format_date()
        ))
            ->where(array(
                'intro.id' => '= ?'
            ), array(
                $parameter['id']
            ))
            ->execute();
        return $data;
    }

    private function delete_group($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $data = self::$query->update('intro_group', array(
            'deleted_at' => parent::format_date()
        ))
            ->where(array(
                'intro_group.uid' => '= ?'
            ), array(
                $parameter['uid']
            ))
            ->execute();
        return $data;
    }

    private function update_group($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $data = self::$query->update('intro_group', array(
            'nama' => $parameter['nama'],
            'modul' => $parameter['modul'],
            'updated_at' => parent::format_date()
        ))
            ->where(array(
                'intro_group.uid' => '= ?',
                'AND',
                'intro_group.deleted_at' => 'IS NULL'
            ), array(
                $parameter['uid']
            ))
            ->execute();
        return $data;
    }

    private function load_group($parameter) {
        $data = self::$query->select('intro_group', array(
            'uid', 'nama'
        ))
            ->where(array(
                'intro_group.modul' => '= ?',
                'AND',
                'intro_group.deleted_at' => 'IS NULL'
            ), array(
                $parameter
            ))
            ->execute();
        return $data;
    }

    private function update_position($parameter) {
        $successCounter = 0;
        foreach ($parameter['data'] as $key => $value) {
            $data = self::$query->update('intro', array(
                'step' => $value['position']
            ))
                ->where(array(
                    'intro.id' => '= ?',
                    'AND',
                    'intro.deleted_at' => 'IS NULL'
                ), array(
                    $value['id']
                ))
                ->execute();
            $successCounter += $data['response_result'];
        }
        return (($successCounter >= count($parameter['data'])) ? 1 : 0);
    }

    private function get_detail($parameter) {
        $data = self::$query->select('intro', array(
            'id',
            'judul',
            'modul',
            'step',
            'type',
            'element_target',
            'remark',
            'tooltip_pos',
            'show_progress',
            'show_bullet',
            'trigger_dom',
            'trigger_dom_type',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'intro.deleted_at' => 'IS NULL',
                'AND',
                'intro.id' => '= ?'
            ), array(
                $parameter
            ))
            ->order(array(
                'step' => 'ASC'
            ))
            ->execute();
        return $data;
    }

    private function build_tutorial($parameter) {
        $group = self::$query->select('intro_group', array(
            'uid, nama'
        ))
            ->where(array(
                'intro_group.modul' => '= ?',
                'AND',
                'intro_group.deleted_at' => 'IS NULL'
            ), array(
                $parameter
            ))
            ->execute();
        foreach ($group['response_data'] as $key => $value) {
            $data = self::$query->select('intro', array(
                'id',
                'judul',
                'modul',
                'step',
                'type',
                'element_target',
                'remark',
                'tooltip_pos',
                'show_progress',
                'show_bullet',
                'trigger_dom',
                'trigger_dom_type',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'intro.deleted_at' => 'IS NULL',
                    'AND',
                    'intro.modul' => '= ?',
                    'AND',
                    'intro.tutor_group' => '= ?'
                ), array(
                    $parameter, $value['uid']
                ))
                ->order(array(
                    'step' => 'ASC'
                ))
                ->execute();
            $group['response_data'][$key]['step'] = $data['response_data'];
        }

        return $group;
    }

    private function get_tutorial($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'intro.deleted_at' => 'IS NULL',
                'AND',
                'intro.modul' => '= ?',
                'AND',
                'intro.tutor_group' => '= ?',
                'AND',
                'intro.judul' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );
            $paramValue = array($parameter['module'], $parameter['tutor_group']);
        } else {
            $paramData = array(
                'intro.deleted_at' => 'IS NULL',
                'AND',
                'intro.modul' => '= ?',
                'AND',
                'intro.tutor_group' => '= ?',
            );
            $paramValue = array($parameter['module'], $parameter['tutor_group']);
        }

        if ($parameter['length'] < 0) {
            $data = self::$query->select('intro', array(
                'id',
                'judul',
                'modul',
                'step',
                'type',
                'element_target',
                'remark',
                'tooltip_pos',
                'show_progress',
                'show_bullet',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->order(array(
                    'step' => 'ASC'
                ))
                ->execute();
        } else {
            $data = self::$query->select('intro', array(
                'id',
                'judul',
                'modul',
                'step',
                'type',
                'element_target',
                'remark',
                'tooltip_pos',
                'show_progress',
                'show_bullet',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->order(array(
                    'step' => 'ASC'
                ))
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }

        $itemTotal = self::$query->select('intro', array(
            'id'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function update_tutorial($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $proses = self::$query->update('intro', array(
            'judul' => $parameter['nama'],
            'type' => $parameter['type'],
            'element_target' => $parameter['target'],
            'remark' => $parameter['remark'],
            'tooltip_pos' => $parameter['tool_pos'],
            'show_progress' => $parameter['progress'],
            'show_bullet' => $parameter['bullet'],
            'tutor_group' => $parameter['tutor_group'],
            'trigger_dom' => $parameter['expectDOM'],
            'trigger_dom_type' => $parameter['expectDOMType'],
            'updated_at' => parent::format_date()
        ))
            ->where(array(
                'intro.id' => '= ?',
                'AND',
                'intro.modul' => '= ?',
                'AND',
                'intro.deleted_at' => 'IS NULL'
            ), array(
                $parameter['id'],
                $parameter['modul']
            ))
            ->execute();

        return $proses;
    }

    private function add_tutorial($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        //get recent step
        $itemTotal = self::$query->select('intro', array(
            'id'
        ))
            ->where(array(
                'intro.modul' => '= ?',
                'AND',
                'intro.tutor_group' => '= ?',
                'AND',
                'intro.deleted_at' => 'IS NULL'
            ), array(
                $parameter['modul'], $parameter['tutor_group']
            ))
            ->execute();

        $proses = self::$query->insert('intro', array(
            'judul' => $parameter['nama'],
            'modul' => $parameter['modul'],
            'step' => (count($itemTotal['response_data']) + 1),
            'type' => $parameter['type'],
            'element_target' => $parameter['target'],
            'remark' => $parameter['remark'],
            'tooltip_pos' => $parameter['tool_pos'],
            'show_progress' => $parameter['progress'],
            'show_bullet' => $parameter['bullet'],
            'tutor_group' => $parameter['tutor_group'],
            'trigger_dom' => $parameter['expectDOM'],
            'trigger_dom_type' => $parameter['expectDOMType'],
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->execute();

        return $proses;
    }
}
?>