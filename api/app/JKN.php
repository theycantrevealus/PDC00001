<?php

namespace PondokCoder;

use Firebase\JWT\JWT;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class JKN extends Utility
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
        //
    }

    public function __POST__($parameter = array())
    {
        if(isset($parameter['username']) && isset($parameter['password'])) {
            return self::login($parameter);
        } else if(
            isset($parameter['nomorkartu']) &&
            isset($parameter['nik']) &&
            isset($parameter['notelp']) &&
            isset($parameter['tanggalperiksa']) &&
            isset($parameter['kodepoli']) &&
            isset($parameter['nomorreferensi']) &&
            isset($parameter['jenisreferensi']) &&
            isset($parameter['jenisrequest']) &&
            isset($parameter['polieksekutif'])
        ) {
            return self::get_antrian($parameter);
        } else {
            return $parameter;
        }
    }

    private function get_antrian($parameter) {

        return array();
    }

    private function login($parameter) {
        $responseBuilder = array();
        $query = self::$pdo->prepare('SELECT * FROM jkn_pengguna WHERE deleted_at IS NULL AND username = ?');
        $query->execute(array($parameter['username']));

        if($query->rowCount() > 0) {
            $read = $query->fetchAll(\PDO::FETCH_ASSOC);
            if (password_verify($parameter['password'], $read[0]['password'])) {
                $log = parent::log(array(
                    'type' => 'login',
                    'column' => array('user_uid','login_meta','logged_at'),
                    'value' => array($read[0]['uid'],'[' . $read[0]['uid'] . '][' . $read[0]['email'] . '] Success Logged In.', parent::format_date()),
                    'class' => 'JKN'
                ));



                //Register JWT
                $iss = __HOSTNAME__;
                $iat = time();
                $nbf = $iat + 10;
                $exp = $iat + 30;
                $aud = 'users_library';
                $user_arr_data = array(
                    'uid' => $read[0]['uid'],
                    'username' => $read[0]['username'],
                    'log_id' => $log
                );
                $secret_key = file_get_contents('taknakal.pub');
                $payload_info = array(
                    'iss' => $iss,
                    'iat' => $iat,
                    'nbf' => $nbf,
                    'exp' => $exp,
                    'aud' => $aud,
                    'data' => $user_arr_data,
                );
                $jwt = JWT::encode($payload_info, $secret_key);

                $responseBuilder['response'] = array('token' => $jwt);
                $responseBuilder['metadata'] = array('message' => 'Ok', 'code' => 200);
            } else {
                $responseBuilder['metadata'] = array('message' => 'Username atau password salah', 'code' => 403);
            }
        } else {
            $responseBuilder['metadata'] = array('message' => 'Username tidak ditemukan', 'code' => 404);
        }

        return $responseBuilder;
    }
}