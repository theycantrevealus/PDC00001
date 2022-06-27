<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Contoh extends Utility {
	static $pdo;
	static $query;
	
	protected static function getConn(){
		return self::$pdo;
	}

	public function __construct($connection) {
		self::$pdo = $connection;
		self::$query = new Query(self::$pdo);
	}

    public function __POST__($parameter = array()) {
        $Authorization = new Authorization();
        //return $Authorization->getSerialNumber($parameter);
        $worker = self::$query->select('master_poli', array(
	        'uid',
            'poli_asesmen'
        ))
            ->where(array(
                'master_poli.deleted_at' => 'IS NULL'
            ))
            ->execute();
        $conn = self::$pdo;
        foreach ($worker['response_data'] as $key => $value) {
            //$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
            //$delete_table = $conn->prepare('DROP TABLE asesmen_rawat_' . $value['poli_asesmen']);
            //$delete_table->execute();


            $new_table = $conn->prepare('
                CREATE TABLE asesmen_rawat_' . $value['poli_asesmen'] . ' (
                    uid uuid NOT NULL,
                    cara_masuk_lainnya character varying,
                    antrian uuid NOT NULL,
                    no_rm character varying(15) NOT NULL,
                    pasien uuid NOT NULL,
                    departemen uuid NOT NULL,
                    perawat2 uuid NOT NULL,
                    pj_pasien character varying,
                    info_dari character varying,
                    waktu_pengkajian timestamp without time zone NOT NULL,
                    kesadaran character varying,
                    tanda_vital_td character varying(20),
                    tanda_vital_n character varying(20),
                    tanda_vital_s character varying(20),
                    tanda_vital_rr character varying(20),
                    sikap_tubuh character varying(20),
                    cara_masuk character varying(20),
                    rujukan character varying,
                    rujukan_ket character varying,
                    rujukan_diagnosa text,
                    riwayat_sakit_sebelumnya character varying,
                    riwayat_operasi character varying,
                    riwayat_waktu_operasi date,
                    riwayat_dirawat character varying,
                    riwayat_waktu_dirawat character varying,
                    riwayat_diagnosa_dirawat character varying,
                    riwayat_pengobatan_dirumah_nama_obat character varying,
                    riwayat_alergi character varying,
                    riwayat_transfusi_golongan_darah character varying,
                    riwayat_merokok character varying,
                    riwayat_miras character varying,
                    riwayat_obt_terlarang character varying,
                    riwayat_imunisasi_dpt_1 character varying,
                    riwayat_imunisasi_dpt_2 character varying,
                    riwayat_imunisasi_dpt_3 character varying,
                    riwayat_imunisasi_campak character varying,
                    riwayat_imunisasi_bcg character varying,
                    riwayat_imunisasi_polio_1 character varying,
                    riwayat_imunisasi_polio_2 character varying,
                    riwayat_imunisasi_hepatitis character varying,
                    riwayat_imunisasi_mmr character varying,
                    riwayat_keluarga_asma character varying,
                    riwayat_keluarga_diabetes character varying,
                    riwayat_keluarga_hipertensi character varying,
                    riwayat_keluarga_cancer character varying,
                    riwayat_keluarga_anemia character varying,
                    riwayat_keluarga_jantung character varying,
                    riwayat_keluarga_lainnya character varying,
                    riwayat_keluarga_lainnya_ket character varying,
                    riwayat_hub_keluarga character varying,
                    menarche_umur character varying,
                    menarche_siklus character varying,
                    menarche_stat character varying,
                    menarche_lama_siklus character varying,
                    keluhan_haid character varying,
                    hpht character varying,
                    taksiran_persalinan character varying,
                    wanita_hamil character varying,
                    pria_prostat character varying,
                    program_kb character varying,
                    program_kb_iud character varying,
                    program_kb_susuk character varying,
                    program_kb_suntik character varying,
                    program_kb_pil character varying,
                    program_kb_steril character varying,
                    program_kb_vasectomi character varying,
                    program_kb_lama_pemakaian character varying,
                    program_kb_keluhan character varying,
                    ginekologi_status character varying,
                    ginekologi character varying,
                    ginekologi_lainnya character varying,
                    tgl_partus character varying,
                    usia_hamil character varying,
                    tempat_partus character varying,
                    jenis_partus character varying,
                    penolong_partus character varying,
                    nifas character varying,
                    jenkel_anak character varying,
                    bb_anak character varying,
                    keadaan_anak character varying,
                    keterangan_anak character varying,
                    nyeri character varying,
                    nyeri_lokasi character varying,
                    nyeri_frekuensi character varying,
                    nyeri_terbakar character varying,
                    nyeri_tertindih character varying,
                    nyeri_menyebar character varying,
                    nyeri_tajam character varying,
                    nyeri_tumpul character varying,
                    nyeri_denyut character varying,
                    nyeri_lainnya character varying,
                    nyeri_lainnya_ket character varying,
                    nyeri_tipe character varying,
                    nyeri_skala character varying,
                    nyeri_total_skor character varying,
                    psikososial character varying,
                    psikososial_hub_keluarga character varying,
                    psikososial_aktifitas_sosial character varying,
                    psikososial_pelaku_rawat character varying,
                    eliminasi_bab character varying,
                    eliminasi_frekuensi_bab character varying,
                    eliminasi_colostomy character varying,
                    eliminasi_bak character varying,
                    eliminasi_bak_lainnya character varying,
                    skrining_selera_makan character varying,
                    skrining_turun_berat character varying,
                    skrining_nilai_turun_berat character varying,
                    komunikasi_bicara character varying,
                    komunikasi_bicara_lainnya character varying,
                    komunikasi_hambatan character varying,
                    komunikasi_hambatan_lainnya character varying,
                    komunikasi_kebutuhan_belajar character varying,
                    komunikasi_kebutuhan_belajar_lainnya character varying,
                    kaji_resiko_sempoyongan character varying,
                    kaji_resiko_penopang character varying,
                    kaji_resiko_ke_dokter character varying,
                    kaji_resiko_jam_dokter character varying,
                    diagnosa_nyeri character varying,
                    diagnosa_pola_tidur character varying,
                    diagnosa_mobilitas character varying,
                    diagnosa_cedera character varying,
                    diagnosa_rawat_diri character varying,
                    diagnosa_kulit character varying,
                    diagnosa_suhu character varying,
                    diagnosa_eliminasi character varying,
                    diagnosa_pengetahuan character varying,
                    diagnosa_nutrisi character varying,
                    diagnosa_cairan character varying,
                    diagnosa_perifer character varying,
                    diagnosa_nafas character varying,
                    diagnosa_infeksi character varying,
                    tatalaksana_hub_baik character varying,
                    tatalaksana_terapeutik character varying,
                    tatalaksana_lingkungan character varying,
                    tatalaksana_timbang character varying,
                    tatalaksana_ukur_tinggi character varying,
                    tatalaksana_kaji_vital character varying,
                    tatalaksana_oral character varying,
                    tatalaksana_bersih_luka character varying,
                    tatalaksana_buka_jahit character varying,
                    tatalaksana_suction character varying,
                    tatalaksana_insisi character varying,
                    tatalaksana_siapkan_obat character varying,
                    tatalaksana_siapkan_obat_ket character varying,
                    tatalaksana_beri_obat character varying,
                    tatalaksana_beri_obat_ket character varying,
                    tatalaksana_konsul character varying,
                    tatalaksana_konsul_ket character varying,
                    tindak_lanjut character varying,
                    tindak_lanjut_ket text,
                    created_at timestamp without time zone NOT NULL,
                    updated_at timestamp without time zone NOT NULL,
                    deleted_at timestamp without time zone,
                    asesmen uuid,
                    kunjungan uuid,
                    tinggi_badan character varying(20),
                    berat_badan character varying(20),
                    status_pernikahan character varying(20),
                    kali_nikah character varying(20),
                    umur_nikah character varying
                );
            ');
            $new_table->execute();

        }


        return $worker;
    }

    private static function uji_class() {
        //return Inventori::get_satuan();
        $Inv = new Inventori(self::$pdo);
        return $Inv->get_satuan();
    }

	public function __GET__($parameter = array()) {
		try {

			switch($parameter[1]) {
                case 'ujiclass':
                    return self::uji_class();
                    break;
                case 'select':
					return

						self::$query
							->select('pegawai', array(
								'uid',
								'email'
							))

							->execute();
					break;
				case 'select_where_limit':
					return
						self::$query
							->select('pegawai', array(
								'uid',
								'email'
							))
							->offset(1)
							->limit(1)

							->execute();
					break;
				case 'select_where':
					return

						self::$query
							->select('pegawai', array(
								'uid',
								'email'
							))

							->where(array(
								'pegawai.deleted_at' => 'IS NULL',
								'OR',
								'pegawai.uid' => '= ?'
							), array(
								'8113652d-4cb7-e850-d487-281a1762042a'
							))

							->execute();
					break;
				case 'select_join':
					return

						self::$query
							->select('log_activity', array(
								'id AS id_akses'
							))

							->join('log_login', array(
								'id',
								'user_uid'
							))

							->join('pegawai', array(
								'nama'
							))

							->on(array(
								array('log_activity.login_id', '=', 'log_login.id'),
								array('log_activity.user_uid', '=', 'pegawai.uid')
							))

							->execute();
					break;
				case 'select_join_where':
					return

						self::$query
							->select('pegawai_akses', array(
								'id AS id_akses'
							))

							->join('pegawai', array(
								'uid',
								'email',
								'nama AS nama_pegawai'
							))

							->join('modul', array(
								'id AS id_modul',
								'nama AS nama_modul'
							))

							->on(array(
								array('pegawai_akses.uid_pegawai','=','pegawai.uid'),
								array('pegawai_akses.modul','=','modul.id')
							))

							->where(array(
								'pegawai.deleted_at' => 'IS NULL',
								'AND',
								'pegawai.uid' => '= ?'
							), array(
								'8113652d-4cb7-e850-d487-281a1762042a'
							))

							->execute();
					break;
				case 'insert':
					return
						self::$query
							->insert('modul', array(
								'nama' => 'Nama Modul',
								'identifier' => 'modul/test',
								'keterangan' => 'Keterangan Modul',
								'created_at' => parent::format_date(),
								'updated_at' => parent::format_date(),
								'parent' => 1,
								'icon' => 'person',
								'show_on_menu' => 'Y',
								'show_order' => 1,
								'menu_group' => 1
							))

							->execute();
				 	break;
				 case 'update_where':
					return
						self::$query
							->update('modul', array(
								'nama' => 'Nama Modul',
								'identifier' => 'modul/test',
								'keterangan' => 'Keterangan Modul',
								'updated_at' => parent::format_date(),
								'parent' => 1,
								'icon' => 'person',
								'show_on_menu' => 'Y',
								'show_order' => 1,
								'menu_group' => 1
							))

							->where(array(
								'modul.deleted_at' => 'IS NULL',
								'AND',
								'modul.id' => '= ?'
							), array(
								7
							))

							->execute();
				 	break;
				case 'delete':
					return
						self::$query
							->delete('modul')

							->where(array(
								'modul.id' => '= ?'
							), array(
								7
							))

							->execute();
					break;
				default:
					return 'Unknown request';
			}
		} catch (QueryException $e) {
			return 'Error => ' . $e;
		}
	}
}