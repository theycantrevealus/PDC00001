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
            case 'get_dokumen_back_end':
                return self::get_dokumen_back_end($parameter);
                break;

            case 'tambah_dokumen':
                return self::tambah_dokumen($parameter);
                break;

            case 'edit_dokumen':
                return self::edit_dokumen($parameter);
                break;

            case 'cetak_dokumen':
                return self::cetak_dokumen($parameter);
                break;

            default:
                return array();
                break;
        }
    }

    private function cetak_dokumen($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization::readBearerToken($parameter['access_token']);
        $uid = parent::gen_uuid();

        //Get Asesmen
        $Asesmen = self::$query->select('asesmen', array(
            'uid'
        ))
            ->where(array(
                'asesmen.kunjungan' => '= ?',
                'AND',
                'asesmen.pasien' => '= ?',
                'AND',
                'asesmen.antrian' => '= ?',
                'AND',
                'asesmen.deleted_at' => 'IS NULL'
            ), array(
                $parameter['kunjungan'], $parameter['pasien'], $parameter['antrian']
            ))
            ->execute();

        $worker = self::$query->insert('asesmen_dokumen', array(
            'uid' => $uid,
            'tanggal' => parent::format_date(),
            'asesmen' => $Asesmen['response_data'][0]['uid'],
            'dokumen' => $parameter['dokumen'],
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->execute();
        if($worker['response_result'] > 0)
        {
            $detailResult = array();
            foreach ($parameter['nilai'] as $key => $value)
            {
                $detail_worker = self::$query->insert('asesmen_dokumen_value', array(
                    'asesmen_dokumen' => $uid,
                    'param_iden' => $value['identifier'],
                    'param_value' => $value['iden_value'],
                    'created_at' => parent::format_date(),
                    'updated_at' => parent::format_date()
                ))
                    ->execute();
                array_push($detailResult, $detail_worker);
            }
            $worker['detail_result'] = $detailResult;
            $worker['compoz'] = $parameter;
        }
        return $worker;
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
        foreach ($data['response_data'] as $key => $value)
        {
            $parameterBuilder = self::$query->select('master_dokumen_item', array(
                'id',
                'param_iden'
            ))
                ->where(array(
                    'master_dokumen_item.deleted_at' => 'IS NULL',
                    'AND',
                    'master_dokumen_item.dokumen' => '= ?'
                ), array(
                    $value['uid']
                ))
                ->execute();
            foreach ($parameterBuilder['response_data'] as $ParamIdenKey => $ParamIdenValue)
            {
                if($ParamIdenValue['param_iden'] === '{{__TODAY__}}')
                {
                    $parameterBuilder['response_data'][$ParamIdenKey]['default'] = date('d F Y');
                } else {
                    $parameterBuilder['response_data'][$ParamIdenKey]['default'] = '';
                }
            }
            $data['response_data'][$key]['parameter'] = $parameterBuilder['response_data'];
        }
        return $data;
    }

    private function get_dokumen_back_end($parameter)
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
            //Reset Old Data
            $paramIdenReset = self::$query->update('master_dokumen_item', array(
                'deleted_at' => parent::format_date()
            ))
                ->where(array(
                    'master_dokumen_item.dokumen' => '= ?'
                ), array(
                    $parameter['uid']
                ))
                ->execute();

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
                preg_match_all('/({{__+[A-Z]+_+[A-Z]+__}})|({{__+[A-Z]+__}})/', $raw, $match);
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