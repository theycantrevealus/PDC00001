<?php
	error_reporting(0);
	session_start();
	//define('__SYNC__', '172.104.34.60');
	define('__SYNC__', '127.0.0.1');
	//define('__SYNC__', '192.168.104.161');
	//define('__SYNC__', '10.4.4.40');
	//define('__SYNC__', '45.127.134.54');
	//define('__SYNC__', '10.2.2.3');
	//define('__SYNC__', '10.3.3.41');
	//define('__SYNC__', '192.168.43.161');
	define('__SYNC_PORT__', '666');






	/*
	 * Sesuaikan dengan nama folder pada server
	 * Cth:
	 * define('__HOSTNAME__', 'http://' . $_SERVER['SERVER_ADDR'] . '/[FOLDER]/client');
	 * define('__HOSTAPI__', 'http://' . $_SERVER['SERVER_ADDR'] . '/[FOLDER]/api');
	 * define('__HOST__', 'http://' . $_SERVER['SERVER_ADDR'] . '/[FOLDER]/');
	 *
	*/
	define('__HOSTNAME__', 'http://' . $_SERVER['SERVER_ADDR'] . '/simrsv2/client');
	define('__HOSTAPI__', 'http://' . $_SERVER['SERVER_ADDR'] . '/simrsv2/api');
	define('__HOST__', 'http://' . $_SERVER['SERVER_ADDR'] . '/simrsv2/');








	/*define('__HOSTNAME__', 'http://192.168.100.137/simrs/client');
	define('__HOSTAPI__', 'http://192.168.100.137/simrs/api');
	define('__HOST__', 'http://192.168.100.137/simrs/');

	define('__HOSTNAME__', 'http://localhost/simrs/client');
	define('__HOSTAPI__', 'http://localhost/simrs/api');
	define('__HOST__', 'http://localhost/simrs/');

	define('__HOSTNAME__', 'http://192.168.100.44/simrs/client');
	define('__HOSTAPI__', 'http://192.168.100.44/simrs/api');
	define('__HOST__', 'http://192.168.100.44/simrs/');*/


	/*define('__PC_CUSTOMER__', 'SOLOMON HOSPITAL');
	define('__PC_CUSTOMER_GROUP__', 'We Make You As Wise As');
	define('__PC_CUSTOMER_ADDRESS__', 'Jln. Platina IV Titipapan');
	define('__PC_CUSTOMER_ADDRESS_SHORT__', 'Kota Medan, Sumatera Utara');
	define('__PC_CUSTOMER_CONTACT__' , '085261510202');
	define('__PC_IDENT__', 'solomon');
	define('__SYSTEM_DOMAIN__', 'solomon-his.com');
	define('__APPS_NAME__', 'SOLOMON::HIRMS v.2.0');*/


	//define('__PC_CUSTOMER__', 'Rumah Sakit Umum Daerah Petala Bumi');
	define('__PC_CUSTOMER__', 'Trikode Nusantara');
	//define('__PC_CUSTOMER_GROUP__', 'Pemerintahan Provinsi Riau');
	define('__PC_CUSTOMER_GROUP__', 'Trikode Nusantara');
	//define('__PC_CUSTOMER_ADDRESS__', 'Jalan Dr. Soetomo No. 65');
	define('__PC_CUSTOMER_ADDRESS__', 'Jalan Demo SIMRS');
	//define('__PC_CUSTOMER_EMAIL__', 'rsudpetalabumi@riau.go.id');
	define('__PC_CUSTOMER_EMAIL__', 'customer@trikodenusantara.com');
	//define('__PC_CUSTOMER_ADDRESS_SHORT__', 'Pekanbaru');
	define('__PC_CUSTOMER_ADDRESS_SHORT__', 'DEMO');
	//define('__PC_CUSTOMER_CONTACT__' , '(0761)23024');
	define('__PC_CUSTOMER_CONTACT__' , 'DEMO');
	//define('__PC_IDENT__', 'petala2');
	define('__PC_IDENT__', 'ais');
	//define('__SYSTEM_DOMAIN__', 'rsudpetalabumi.com');
	define('__SYSTEM_DOMAIN__', 'trikodenusantara.com');
	//define('__APPS_NAME__', 'RSUDPTB::SIMRSv.2.0');
	define('__APPS_NAME__', 'TN::SIMRSv.2.0');


	/*define('__PC_CUSTOMER__', 'RSUD Kabupaten Bintan');
	define('__PC_CUSTOMER_ADDRESS__', 'Jl.Kesehatan No.1 Kijang Kota 29151');
	define('__PC_CUSTOMER_ADDRESS_SHORT__', 'Bintan');
	define('__PC_CUSTOMER_CONTACT__', '085261510202');
	define('__SYSTEM_DOMAIN__', 'rsudbintan.com');*/



	define('__APLICARES__', 'https://dvlp.bpjs-kesehatan.go.id:8888/aplicaresws');

	

	//LIVE PTB
	define('__KODE_PPK__', '0069R035');
	define('__DATA_API_LIVE__', 15174); //PTB PAKE INI
	define('__SECRET_KEY_LIVE_APLICARES_BPJS__', '2pAB5273E9');
	define('__BPJS_SERVICE_NAME__', 'vclaim-rest');
	define('__BASE_LIVE_BPJS__', 'https://dvlp.bpjs-kesehatan.go.id');

	define('__DATA_API_LIVE_APLICARES__', 32435);
	define('__BASE_LIVE_BPJS_APLICARES__', 'https://new-api.bpjs-kesehatan.go.id');

	//DVLP PTB 0001454326918
	define('__BASE_STAGING_BPJS__', 'https://dvlp.bpjs-kesehatan.go.id');



	//BTN LIVE
	// define('__KODE_PPK__', '0066R007');
	// define('__DATA_API_LIVE__', 4119);
	// define('__SECRET_KEY_LIVE_BPJS__', '4uU7362B03');
	// define('__BPJS_SERVICE_NAME__', 'new-vclaim-rest');
	// define('__BASE_LIVE_BPJS__', 'https://new-api.bpjs-kesehatan.go.id:8080');





	/*
	 * Staging Aplicares
	 * */
	/*define('__DATA_API_LIVE_APLICARES__', 15174); //PTB Aplicares pakai yang ini
	define('__SECRET_KEY_LIVE_APLICARES_BPJS__', '5bCF2B4F83');  //PTB Aplicares pakai yang ini
	define('__KODE_PPK__', '0069R035');

	define('__SECRET_KEY_LIVE_BPJS__', 'EvuxgRoLkv'); //PTB PAKE INI*/

	//define('__SECRET_KEY_LIVE_BPJS__', '2pAB5273E9');
	//define('__SECRET_KEY_LIVE_BPJS__', '5bCF2B4F83');
	//define('__BASE_LIVE_BPJS__', 'http://api.bpjs-kesehatan.go.id');
	//define('__BASE_LIVE_BPJS__', 'https://new-api.bpjs-kesehatan.go.id');
	//0001454326918





	/*
	 * master_poli
	 * */
	/*
	 * master_penjamin*/
	define('__UIDPENJAMINUMUM__','499ed11a-911d-4661-b3b2-783e17615eb7');
	define('__UIDPENJAMINBPJS__','8509d734-22c3-421e-93c5-3de08fb0a506');
	define('__UIDPENJAMINBPJSOFFLINE__','cf8135f5-8bf6-4828-904d-2ceb0396a225');

	/*
	 * pegawai_jabatan*/
	define('__UIDADMIN__', 'b8c88459-f882-42f6-aba8-89e1cd65d948');
	define('__UID_PENDAFTARAN__', 'f5bbcc8a-2ad0-4bfc-9c62-879925d6a7f2');
	define('__UIDDOKTER__', 'c77f97be-f32f-4ca1-b571-3a0837dde7e8');
	define('__UIDPERAWAT__', '583f2700-dd4b-4817-b50a-6c0d23e7c14c');
	define('__UIDPETUGASLAB__', '8b1466d6-b923-4733-b214-584e2dfccbc1');
	define('__UIDPETUGASGUDANGFARMASI__', '8656cf62-567c-4b24-adff-cde9ca234eca');
	define('__UIDPETUGASRAD__', '9a27edc9-1ea0-4d07-aeb9-269859a62f38');
	define('__UIDAPOTEKER__', 'f2b5adb1-a1eb-441c-8273-a246d7ced670');
	define('__UIDKARUAPOTEKER__', 'fa2e9562-c755-4616-ba54-38ea9933bbea');
	define('__UIDKEPALAGUDANG__', '407a3dcb-518b-4093-8205-6bb89fcc4abf');

	/*
	 * master_unit*/
	//define('__UNIT_GUDANG__', '30d5c540-6a0f-d266-1f54-616a7464dcbb');
	define('__UNIT_GUDANG__', '9ab13031-3b00-4de7-88b1-17b69ea385ba');

	/*
	 * master_tindakan*/
	define('__UID_KONSULTASI__', '11a95e98-9dfc-4e89-aae3-8f698aac7d9d');
	define('__UIDKONSULDOKTER__','11a95e98-9dfc-4e89-aae3-8f698aac7d9d');
	define('__UIDKONSULDOKTER_GIGI__','4ba9ffe1-ea27-427c-9b4c-2e54bf7bb64e');
	define('__UIDKONSULDOKTER_SPESIALIS__','2cbc7223-abf1-499c-b7b4-29712f299852');
	define('__UID_KARTU__', '5b61bedf-6dde-4a12-99b2-ffa908017ea2');

	/*
	 * master_inv_gudang*/
	define('__GUDANG_UTAMA__', '5bda12c3-1589-40b6-97a5-2992a8a90677');
	define('__GUDANG_APOTEK__', 'e7273646-1d2e-40e8-bcbc-028f6a8ce1e0');
	define('__GUDANG_IGD__','e8489dbb-3e1b-44ac-8e04-fdb3f698dc9e');
	define('__GUDANG_DEPO_OK__','e6e55e20-4a13-4e4d-b02a-3820f9b05273');
	
	

	/*
	 * master_tindakan_kelas*/
	// Set semua tindakan tanpa kelas default ke general
	define('__UID_KELAS_GENERAL_RJ__', '64c374d4-3d35-432d-ad3d-e4b3e7ec448a');
	define('__UID_KELAS_GENERAL_RAD__', '0b1f5a95-5285-4dd3-b9b7-37ecc65fd700');
	define('__UID_KELAS_GENERAL_LAB__', 'a5771d26-72ed-4b76-a22f-817992f1f45c');

	/*
	 * master_poli*/
	define('__POLI_MATA__', 'd6eb19ad-4cc6-4eac-98db-8e72ee44701a');
	define('__POLI_GIGI__', 'e9c98b6b-a045-4a93-9804-77aa15596e76');
	define('__POLI_INAP__', '008ab102-96ed-469d-ab7a-e0ecda1eeb2e');
	define('__POLI_LAB__', '4bc8b22d-1bc0-44d5-a56d-eb97a898eec1');
	define('__POLI_IGD__', '13f41b73-071a-4da2-a2e4-5003d999c1f7');
	define('__POLI_ORTODONTIE__', 'f2c1fc1d-5d75-45fe-acaa-f9f85238502e');
	define('__POLI_OPERASI__', '77cc0eea-fd15-44be-b3b3-e2ebab39c21a');

	define('__POLI_FISIOTERAPI__', '581f4c0a-2f0a-49df-9bea-9cd3d77d3673');
	define('__POLI_STROKE__', '11f95113-a88c-49fe-908d-f260dd6906d8');
	define('__POLI_DD__', '11f95113-a88c-49fe-908d-f260dd6906d8');
	define('__REHABILITASI_MEDIK__', '581f4c0a-2f0a-49df-9bea-9cd3d77d3673');

	define('__UIDRADIOLOGI__', '581f4c0a-2f0a-49df-9bea-9cd3d77d3673');
	define('__UIDFISIOTERAPI__', '11f95113-a88c-49fe-908d-f260dd6906d8');


	//Priority
	define('__PRIORITY_HIGH__', 38);

	//Jenis Antrian
	define('__ANTRIAN_KHUSUS__', '6e71fddf-279c-486f-8958-edb81d532aef');


	/*
	 * terminologi_item*/
	// Kode Status Transaksi Inventori Stok Log
	define('__STATUS_BARANG_MASUK__', 51);
	define('__STATUS_STOK_AWAL__', 52);
	define('__STATUS_MUTASI_STOK__', 53);
	define('__STATUS_AMPRAH__', 54);
	define('__STATUS_RETUR_AMPRAH__', 55);
	define('__STATUS_RETUR_PEMASOK__', 56);
	define('__STATUS_OPNAME__', 57);
	define('__STATUS_BARANG_KELUAR__', 1658);
	define('__STATUS_BARANG_KELUAR_INAP__', 1791);
	define('__STATUS_BARANG_MASUK_INAP__', 1792);
	define('__STATUS_TEMPORARY_STOK_IN__', 1793);
	define('__STATUS_TEMPORARY_STOK_OUT__', 1794);
	define('__STATUS_BARANG_RETUR__', 1804);
	define('__AMPRAH_OPNAME_IN__', 1795);
	define('__AMPRAH_OPNAME_OUT__', 1796);
	define('__STATUS_BARANG_MASUK_OPNAME__', 1797);
	define('__STATUS_BARANG_KELUAR_OPNAME__', 1798);

	/*
	 * master_inv_obat_kategori*/
	define('__UID_ANTIBIOTIK__', '5363e662-ea21-4e30-bd7f-d4f654c8f594');
	define('__UID_FORNAS__', '5bd8f3a2-8372-e8d2-e8ea-aa7686b0c1ea');
	define('__UID_NARKOTIKA__', 'd94359dd-c59a-1fb9-7267-348dc6de2921');
	define('__UID_PSIKOTROPIKA__', '565e8bd8-a0a4-423d-96e5-5aa257c0d60b');
	define('__UID_GENERIK__', '9506cc62-a327-4596-9c33-d8fc1204e515');


	/*
	 * master_inv_kategori*/
	define('__UID_KATEGORI_OBAT', 'd021c875-1973-4352-a59a-f79f47568b8a');



	/*
	 * master_unit_ruangan*/
	define('__KAMAR_IGD__', '58b9320b-f53b-46c8-8f58-b89db7a218ab');


	/*
	 * master_mitra*/
	define('__MITRA_PTB__', '3d515c54-2433-3f2d-6865-9a1f150fe182'); // Prioritas Mitra
	define('__WNI__', 1690); // ID pengenal KTP. Jika selain WNI Passpor / SIM

	define('__MAX_UPLOAD_FILE_SIZE__', '5'); // Size upload maksimal (MB)
	define('__RULE_PRA_INAP_ALLOW_ADMINISTRASI__', 1); // Administrasi IGD - Inap (Harus lunas atau tidak?) 1 = harus lunas, 0 boleh langsung
	define('__RECIPE_TIME_TOLERANCE__', 24); // Toleransi Perubahan resep maksimal 24 jam
	define('__APOTEK_SERVICE_RESPONSE_TIME_TOLERATE__', 45); // UI Response Time Apotek <= 45 hijau, > 45 merah
	define('__BPJS_MODE__', 0);	//1 = Online, Selain 1 = Offline
	define('__AD_DOKTER_COUNT__', 20);// Row display kehadiran dokter




	/*self::$data_api = base64_decode("MTUxNzQ=");*/
	//LIVE
	//self::$data_api = 32435;
	/*self::$secretKey_api = base64_decode("NWJDRjJCNEY4Mw==");*/
	//LIVE//self::$secretKey_api = '2pAB5273E9';
	//STAGING APPLICARES
	//self::$secretKey_api = '5bCF2B4F8';
	//
	/*self::$base_url = "https://dvlp.bpjs-kesehatan.go.id:8888/aplicaresws";*/
	//self::$base_url = "http://api.bpjs-kesehatan.go.id/aplicaresws";
	//"https://dvlp.bpjs-kesehatan.go.id"
?>
