<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;

class Kasir extends Utility
{
    static $pdo;
    static $query;

    public function __construct($connection)
    {
        self::$pdo = $connection;
        self::$query = new Query(self::$pdo);
    }

    protected static function getConn()
    {
        return self::$pdo;
    }

    public function __GET__($parameter = array())
    {
        try {
            switch ($parameter[1]) {
                case 'konsul-dokter':
                    return self::get_konsul_dokter();
                    break;

                case 'penjamin-detail':
                    return self::get_penjamin_detail($parameter[2]);
                    break;

                case 'get_penjamin_obat':
                    return self::get_penjamin_obat($parameter[2]);
                    break;

                /*case 'get_penjamin_tindakan':
                    return self::get_penjamin_tindakan($parameter[2]);
                    break;*/

                default:
                    # code...
                    break;
            }
        } catch (QueryException $e) {
            return 'Error => ' . $e;
        }
    }

    private function get_konsul_dokter()
    {
        $table = 'antrian_nomor';

        $data = self::$query
            ->select($table,
                array(
                    'id',
                    'nomor_urut',
                    'loket as uid_loket',
                    'pegawai as uid_pegawai',
                    'kunjungan as uid_kunjungan',
                    'pasien as uid_pasien',
                    'poli as uid_poli',
                    'dokter as uid_dokter',
                    'penjamin as uid_penjamin',
                    'status'
                )
            )
            ->join('pasien', array(
                    'nama as pasien',
                    'no_rm'
                )
            )
            ->join('master_poli', array(
                    'nama as poli'
                )
            )
            ->join('pegawai', array(
                    'nama as pegawai'
                )
            )
            ->on(array(
                    array('pasien.uid', '=', $table . '.pasien'),
                    array('master_poli.uid', '=', $table . '.poli'),
                    array('pegawai.uid', '=', $table . '.pegawai')
                )
            )
            ->where(array(
                    'antrian_nomor.status' => '= \'K\'',
                    'AND',
                    'antrian_nomor.loket' => 'IS NOT NULL',
                    'AND',
                    'antrian_nomor.pegawai' => 'IS NOT NULL',
                    'AND',
                    'antrian_nomor.kunjungan' => 'IS NOT NULL',
                    'AND',
                    'antrian_nomor.pasien' => 'IS NOT NULL',
                    'AND',
                    'antrian_nomor.poli' => 'IS NOT NULL'
                )
            )
            ->order(
                array(
                    $table . '.nomor_urut' => 'DESC'
                )
            )
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;

            if ($value['uid_penjamin'] == __UIDPENJAMINUMUM__) {
                $harga = self::get_poli_tindakan_detail(array($value['uid_poli'], __UIDKONSULDOKTER__, __UIDPENJAMINUMUM__));
                $harga_bayar = intval($harga['response_data'][0]['harga']);
            }

            $cek_pasien_baru = self::cek_pasien_baru($value['uid_pasien']);

            if ($cek_pasien_baru['response_result'] <= 0) {
                $harga_bayar += 10000;
            }

            $data['response_data'][$key]['harga'] = $harga_bayar;
        }

        return $data;
    }

    private function get_poli_tindakan_detail($parameter)
    {
        $data = self::$query
            ->select('master_poli_tindakan_penjamin', array(
                    'id',
                    'harga',
                    'uid_poli',
                    'uid_tindakan',
                    'uid_penjamin',
                    'created_at',
                    'updated_at'
                )
            )
            ->where(array(
                'master_poli_tindakan_penjamin.deleted_at' => 'IS NULL',
                'AND',
                'master_poli_tindakan_penjamin.uid_poli' => '= ?',
                'AND',
                'master_poli_tindakan_penjamin.uid_tindakan' => '= ?',
                'AND',
                'master_poli_tindakan_penjamin.uid_penjamin' => '= ?'
            ),
                array(
                    $parameter[0],
                    $parameter[1],
                    $parameter[2]
                )
            )
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            /*$data['response_data'][$key]['autonum'] = $autonum;
            $Penjamin = new Penjamin(self::$pdo);
            $Tindakan = new Tindakan(self::$pdo);
            $data['response_data'][$key]['tindakan'] = $Tindakan::get_tindakan_detail($value['uid_tindakan'])['response_data'][0];
            $data['response_data'][$key]['penjamin'] = $Penjamin::get_penjamin_detail($value['uid_penjamin'])['response_data'][0];
            */
            $autonum++;
        }

        return $data;
    }


    /*=======================GET FUNCTION======================*/

    private function cek_pasien_baru($parameter)
    {
        $data = self::$query
            ->select('antrian', array('*'))
            ->where(array(
                'antrian.pasien' => '= ?'),
                array($parameter)
            )
            ->execute();

        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }

        return $data;
    }

    public function __POST__($parameter = array())
    {
        switch ($parameter['request']) {
            case 'konsul-dokter':
                return self::proses_ke_antrian($parameter);
                break;

            case 'edit_penjamin':
                return self::edit_penjamin($parameter);
                break;

            default:
                # code...
                break;
        }
    }

    private function proses_ke_antrian($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $proses = self::$query
            ->update('antrian_nomor', array('status' => 'P'))
            ->where(array(
                'antrian_nomor.kunjungan' => '= ?',
                'AND',
                'antrian_nomor.poli' => '= ?',
                'AND',
                'antrian_nomor.dokter' => '= ?',
                'AND',
                'antrian_nomor.pasien' => '= ?',
                'AND',
                'antrian_nomor.penjamin' => '= ?'
            ), array(
                    $parameter['kunjungan'],
                    $parameter['poli'],
                    $parameter['dokter'],
                    $parameter['pasien'],
                    $parameter['penjamin']
                )
            )
            ->execute();

        if ($proses['response_result'] > 0) {
            $uid = parent::gen_uuid();
            $no_antrian = self::ambilNomorAntrianPoli($parameter['poli']);

            $antrian = self::$query
                ->insert('antrian', array(
                        'uid' => $uid,
                        'no_antrian' => $no_antrian,
                        'kunjungan' => $parameter['kunjungan'],
                        'prioritas' => 36,
                        'pasien' => $parameter['pasien'],
                        'departemen' => $parameter['poli'],
                        'dokter' => $parameter['dokter'],
                        'penjamin' => $parameter['penjamin'],
                        'waktu_masuk' => parent::format_date(),
                        'created_at' => parent::format_date(),
                        'updated_at' => parent::format_date()
                    )
                )
                ->execute();

            if ($antrian['response_result'] > 0) {
                $antrian_nomor = self::$query
                    ->update('antrian_nomor', array(
                        'antrian' => $uid
                    ))
                    ->where(array(
                        'antrian_nomor.pasien' => '= ?',
                        'AND',
                        'antrian_nomor.poli' => '= ?',
                        'AND',
                        'antrian_nomor.dokter' => '= ?',
                        'AND',
                        'antrian_nomor.penjamin' => '= ?'
                    ), array(
                            $parameter['pasien'],
                            $parameter['poli'],
                            $parameter['dokter'],
                            $parameter['penjamin']
                        )
                    )
                    ->execute();

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
                            $uid,
                            $UserData['data']->uid,
                            'antrian',
                            'I',
                            parent::format_date(),
                            'N',
                            $UserData['data']->log_id
                        ),
                        'class' => __CLASS__
                    )
                );
            }
        }

        $result = [$proses, $antrian, $antrian_nomor];

        return $result;
    }

    /*================================================*/

    private function ambilNomorAntrianPoli($poli)
    {
        $waktu = date("Y-m-d", strtotime(parent::format_date()));

        $data = self::$query
            ->select('antrian', array('no_antrian'))
            ->where(array(
                'antrian.deleted_at' => 'IS NULL',
                'AND',
                'antrian.departemen' => '= ?',
                'AND',
                'DATE(antrian.waktu_masuk)' => '= ?'
            ), array(
                    $poli,
                    $waktu
                )
            )
            ->order(array('no_antrian' => 'DESC'))
            ->limit(1)
            ->execute();


        $nomor = 1;
        if ($data['response_result'] > 0) {
            $nomor = intval($data['response_data'][0]['no_antrian']) + 1;
        }

        return $nomor;
    }

    public function __DELETE__($parameter = array())
    {
        return self::delete_penjamin($parameter);
    }

    private function cek_di_nomor_antrian($parameter)
    {
        $data = self::$query
            ->select('antrian_nomor', array('*'))
            ->where(array(
                'antrian_nomor.kunjungan' => '= ?',
                'AND',
                'antrian_nomor.poli' => '= ?',
                'AND',
                'antrian_nomor.dokter' => '= ?',
                'AND',
                'antrian_nomor.pasien' => '= ?'
            ), array(
                    $parameter[0],
                    $parameter[1],
                    $parameter[2],
                    $parameter[3]
                )
            )
            ->execute();

        return $data;
    }

    private function duplicate_check($parameter)
    {
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