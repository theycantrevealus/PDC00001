<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Dokumen extends Utility
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

    public function __GET__($parameter = array())
    {
        switch ($parameter[1]) {
            case 'detail':
                return self::detail_dokumen($parameter[2]);
                break;
            default:
                break;
        }
    }

    public function __POST__($parameter = array())
    {
        switch ($parameter['request'])
        {
            case 'get_supplier_back_end':
                return self::get_supplier_back_end($parameter);
                break;

            case 'tambah_dokumen':
                return self::tambah_dokumen($parameter);
                break;

            case 'edit_dokumen':
                return self::edit_dokumen($parameter);
                break;

            default:
                return array();
                break;
        }
    }

    private function detail_dokumen($parameter) {
        $data = self::$query->select('master_dokumen', array(
            'uid',
            'nama',
            'template_iden',
            'created_at'
        ))
            ->where(array(
                'master_dokumen.uid' => '= ?',
                'AND',
                'master_dokumen.deleted_at' => 'IS NULL'
            ), array(
                $parameter
            ))
            ->execute();
        return $data;
    }

    private function get_supplier_back_end($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'master_dokumen.deleted_at' => 'IS NULL'
            );

            $paramValue = array();
        } else {
            $paramData = array(
                'master_dokumen.deleted_at' => 'IS NULL',
                'AND',
                'master_dokumen.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array();
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('master_dokumen', array(
                'uid',
                'nama',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('master_dokumen', array(
                'uid',
                'nama',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
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

        $itemTotal = self::$query->select('master_dokumen', array(
            'uid'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function edit_dokumen($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);
        $old = self::detail_dokumen($parameter['uid']);
        $worker = self::$query->update('master_dokumen', array(
            'nama' => $parameter['nama'],
            'template_iden' => $parameter['template_iden'],
            'updated_at' => parent::format_date()
        ))
            ->where(array(
                'master_dokumen.uid' => '= ?',
                'AND',
                'master_dokumen.deleted_at' => 'IS NULL'
            ), array(
                $parameter['uid']
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
                    $parameter['uid'],
                    $UserData['data']->uid,
                    'master_inv',
                    'U',
                    json_encode($old),
                    json_encode($parameter),
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class' => __CLASS__
            ));


            //Get Input Parameter
            $match = array();
            $raw = trim(addslashes(strip_tags($parameter['template_iden'])));
            preg_match_all('/({{__+[A-Z]+__}})/', $raw, $match);
            $MatchProcess = array();
            foreach($match[0] as $MatchKey => $MatchValue) {
                //Check Identifier
                $PregMatchCheck = self::$query->select('master_dokumen_item', array(
                    'id'
                ))
                    ->where(array(
                        'master_dokumen_item.dokumen' => '= ?',
                        'AND',
                        'master_dokumen_item.param_iden' => '= ?'
                    ), array(
                        $parameter['uid'], $MatchValue
                    ))
                    ->execute();
                if(count($PregMatchCheck['response_data']) > 0) {
                    $WorkerMatch = self::$query->update('master_dokumen_item', array(
                        'param_iden' => $MatchValue,
                        'deleted_at' => NULL
                    ))
                        ->where(array(
                            'master_dokumen_item.id' => '= ?'
                        ), array(
                            $PregMatchCheck['response_data'][0]['id']
                        ))
                        ->execute();
                } else {
                    $WorkerMatch = self::$query->insert('master_dokumen_item', array(
                        'param_iden' => $MatchValue,
                        'dokumen' => $parameter['uid'],
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    ))
                        ->execute();
                }

                array_push($MatchProcess, $WorkerMatch);
            }

            $worker['raw'] = $raw;
            $worker['match'] = $match;
            $worker['iden'] = $MatchProcess;
        }

        return $worker;
    }

    private function tambah_dokumen($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);


        $check = self::duplicate_check(array(
            'table' => 'master_dokumen',
            'check' => $parameter['nama']
        ));

        if(count($check['response_data']) > 0) {
            $check['response_message'] = 'Duplicate data detected';
            $check['response_result'] = 0;
            unset($check['response_data']);
            return $check;
        } else {
            $uid = parent::gen_uuid();
            $worker = self::$query->insert('master_dokumen', array(
                'uid' => $uid,
                'nama' => $parameter['nama'],
                'template_iden' => $parameter['template_iden'],
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
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
                        'new_value',
                        'logged_at',
                        'status',
                        'login_id'
                    ),
                    'value' => array(
                        $uid,
                        $UserData['data']->uid,
                        'master_dokumen',
                        'I',
                        json_encode($parameter),
                        parent::format_date(),
                        'N',
                        $UserData['data']->log_id
                    ),
                    'class' => __CLASS__
                ));

                //Get Input Parameter
                $match = array();
                $raw = trim(addslashes(strip_tags($parameter['template_iden'])));
                preg_match_all('/({{__+[A-Z]+__}})/', $raw, $match);
                $MatchProcess = array();
                foreach($match[0] as $MatchKey => $MatchValue) {
                    //Check Identifier
                    $PregMatchCheck = self::$query->select('master_dokumen_item', array(
                        'id'
                    ))
                        ->where(array(
                            'master_dokumen_item.dokumen' => '= ?',
                            'AND',
                            'master_dokumen_item.param_iden' => '= ?'
                        ), array(
                            $parameter['uid'], $MatchValue
                        ))
                        ->execute();
                    if(count($PregMatchCheck['response_data']) > 0) {
                        $WorkerMatch = self::$query->update('master_dokumen_item', array(
                            'param_iden' => $MatchValue,
                            'deleted_at' => NULL
                        ))
                            ->where(array(
                                'master_dokumen_item.id' => '= ?'
                            ), array(
                                $PregMatchCheck['response_data'][0]['id']
                            ))
                            ->execute();
                    } else {
                        $WorkerMatch = self::$query->insert('master_dokumen_item', array(
                            'param_iden' => $MatchValue,
                            'dokumen' => $parameter['uid'],
                            'created_at' => parent::format_date(),
                            'updated_at' => parent::format_date()
                        ))
                            ->execute();
                    }

                    array_push($MatchProcess, $WorkerMatch);
                }

                $worker['raw'] = $raw;
                $worker['match'] = $match;
                $worker['iden'] = $MatchProcess;
            }

            return $worker;
        }
    }

    private function duplicate_check($parameter) {
        return self::$query
            ->select($parameter['table'], array(
                'uid',
                'nama'
            ))
            ->where(array(
                $parameter['table'] . '.deleted_at' => 'IS NULL',
                'AND',
                $parameter['table'] . '.nama' => '= ?'
            ), array(
                $parameter['check']
            ))
            ->execute();
    }
}

?>