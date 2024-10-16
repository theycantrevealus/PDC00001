<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Log extends Utility
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
            case 'log_activity':
                return self::get_log_activity($parameter);
                break;
            case 'get_log_activity_dt':
                return self::get_log_activity_dt($parameter);
                break;
        }
    }

    public function __GET__($parameter = array())
    {
        //
    }

    private function get_log_activity_dt($parameter) {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array();
            $paramValue = array();
        } else {
            if($parameter['actionType'] === 'all') {
                if($parameter['issuer'] === 'all') {
                    $paramData = array();
                    $paramValue = array();
                } else {
                    $paramData = array(
                        'log_activity.user_uid' => '= ?'
                    );
                    $paramValue = array(
                        $parameter['issuer']
                    );
                }
            } else {
                if($parameter['issuer'] === 'all') {
                    $paramData = array(
                        'log_activity.action' => '= ?'
                    );
                    $paramValue = array(
                        $parameter['actionType'],
                    );
                } else {
                    $paramData = array(
                        'log_activity.action' => '= ?',
                        'AND',
                        'log_activity.user_uid' => '= ?'
                    );
                    $paramValue = array(
                        $parameter['actionType'],
                        $parameter['issuer']
                    );
                }
            }
        }

        if ($parameter['length'] < 0) {
            $data = self::$query->select('log_activity', array(
                'id', 'user_uid', 'table_name', 'action', 'logged_at', 'old_value', 'new_value', 'status', 'login_id', 'unique_target'
            ))
                ->where($paramData, $paramValue)
                ->order(array(
                    'logged_at' => 'DESC'
                ))
                ->execute();
        } else {
            $data = self::$query->select('log_activity', array(
                'id', 'user_uid', 'table_name', 'action', 'logged_at', 'old_value', 'new_value', 'status', 'login_id', 'unique_target'
            ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->order(array(
                    'logged_at' => 'DESC'
                ))
                ->execute();
        }

        $autonum = intval($parameter['start']) + 1;
        $Pegawai = new Pegawai(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['logged_at_date_parsed'] = date('d F Y', strtotime($value['logged_at']));
            $data['response_data'][$key]['logged_at_time_parsed'] = date('H:i:s', strtotime($value['logged_at']));
            $data['response_data'][$key]['pegawai'] = $Pegawai->get_detail_pegawai($value['user_uid'])['response_data'][0];
            $autonum++;
        }

        $itemTotal = self::$query->select('log_activity', array(
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

    private function get_log_activity($parameter) {
        $Grouper = array();
        if($parameter['actionType'] === 'all') {
            if($parameter['issuer'] === 'all') {
                $paramKey = array();
                $paramValue = array();
            } else {
                $paramKey = array(
                    'log_activity.user_uid' => '= ?'
                );
                $paramValue = array(
                    $parameter['issuer']
                );
            }
        } else {
            if($parameter['issuer'] === 'all') {
                $paramKey = array(
                    'log_activity.action' => '= ?'
                );
                $paramValue = array(
                    $parameter['actionType']
                );
            } else {
                $paramKey = array(
                    'log_activity.user_uid' => '= ?',
                    'AND',
                    'log_activity.action' => '= ?'
                );
                $paramValue = array(
                    $parameter['issuer'],
                    $parameter['actionType']
                );
            }
        }

        $data = self::$query->select('log_activity', array(
            'user_uid', 'table_name', 'action', 'logged_at', 'old_value', 'new_value', 'status', 'login_id', 'unique_target'
        ))
            ->where($paramKey, $paramValue)
            ->order(array(
                'logged_at' => 'DESC'
            ))
            ->execute();

        $data['response_data'] = array_reverse($data['response_data']);

        foreach ($data['response_data'] as $key => $value) {
            $dayGrouper = date('d_m_Y', strtotime($value['logged_at']));
            $timeGrouper = date('H_i', strtotime($value['logged_at']));

            if(!isset($Grouper[$dayGrouper])) {
                $Grouper[$dayGrouper] = array();
            }

            if(!isset($Grouper[$dayGrouper][$timeGrouper])) {
                $Grouper[$dayGrouper][$timeGrouper] = array();
            }

            array_push($Grouper[$dayGrouper][$timeGrouper], $value);
        }

        return $Grouper;
    }
}
?>