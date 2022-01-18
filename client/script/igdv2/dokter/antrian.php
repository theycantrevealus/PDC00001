<script src="<?php echo __HOSTNAME__; ?>/plugins/ckeditor5-build-classic/ckeditor.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/paginationjs/pagination.min.js"></script>
<link href="<?php echo __HOSTNAME__; ?>/plugins/paginationjs/pagination.min.css" type="text/css" rel="stylesheet" />
<script type="text/javascript">
    $(function() {
        //var poliListRaw = <?php echo json_encode($_SESSION['poli']['response_data'][0]['poli']['response_data']); ?>;
        var poliListRaw = [];
        var poliListRawList = <?php echo json_encode($_SESSION['poli']['response_data']); ?>;
        var poliList = [];
        //var allICD10 = load_icd_10();
        var allICD10 = [];
        var allICD9 = [];
        var selectedICD10Kerja = [], selectedICD10Banding = [], selectedICD9 = [];

        var dataOdontogram = "";
        var dataMukaSimetris = "";
        var dataTMJ = "";
        var dataBibir = "";
        var dataLidah = "";
        var dataMukosa = "";
        var dataTorus = "";
        var dataGingiva = "";
        var dataFrenulum = "";
        var dataKebersihanMulut = "";
        var metaSwitchEdit = {
            txt_keluhan_utama: {
                placeholder: "Keluhan Utama...",
                data: "",
                editor: null
            },
            txt_keluhan_tambahan: {
                placeholder: "Keluhan Tambahan...",
                data: "",
                editor: null
            },
            txt_pemeriksaan_fisik: {
                placeholder: "Pemeriksaan Fisik...",
                data: "",
                editor: null
            },
            txt_diagnosa_kerja: {
                placeholder: "Diagnosa Kerja...",
                data: "",
                editor: null
            },
            txt_diagnosa_banding: {
                placeholder: "Diagnosa Banding...",
                data: "",
                editor: null
            },
            txt_planning: {
                placeholder: "Planning...",
                data: "",
                editor: null
            },
            txt_keterangan_resep: {
                placeholder: "Keterangan Resep...",
                data: "",
                editor: null
            },
            txt_keterangan_resep_racikan: {
                placeholder: "Keterangan Racikan...",
                data: "",
                editor: null
            }
        };

        var temporEdit = {
            txt_keluhan_utama: "",
            txt_keluhan_tambahan: "",
            txt_pemeriksaan_fisik: "",
            txt_diagnosa_kerja: "",
            txt_diagnosa_banding: "",
            txt_planning: "",
            keterangan_resep: "",
            keterangan_racikan: ""
        };


        //Filter Rawat Jalan
        for(var z in poliListRaw.tindakan) {
            if(poliListRaw.tindakan[z].kelas == __UID_KELAS_GENERAL_RJ__) {
                poliList.tindakan.push(poliListRaw.tindakan);
            }
        }

        var metaSelOrdo = {};
        var allowEdit = true;

        //Init
        //var editorKeluhanUtamaData, editorKeluhanTambahanData, editorPeriksaFisikData, editorKerja, editorBanding, editorKeteranganResep, editorKeteranganResepRacikan, editorPlanning;
        var editorTerapisAnamnesa, editorTerapisTataLaksana, editorTerapisEvaluasi, editorTerapisHasil, editorTerapisKesimpulan, editorTerapisRekomendasi;
        var antrianData, asesmen_detail;
        var prioritas_antrian = 0;
        var tindakanMeta = [];
        var usedTindakan = [];
        //var pasien_penjamin, pasien_penjamin_uid;
        var pasien_uid, pasien_nama, pasien_kontak, pasien_alamat, pasien_usia, pasien_rm, pasien_jenkel, pasien_tanggal_lahir, pasien_penjamin, pasien_penjamin_uid, pasien_tempat_lahir;
        var UID = __PAGES__[3];
        var kunjungan = {};
        $("#info-pasien-perawat").remove();
        $.ajax({
            url:__HOSTAPI__ + "/Antrian/antrian-detail/" + UID,
            async:false,
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type:"GET",
            success:function(response) {
                antrianData = response.response_package.response_data[0];

                if(antrianData.waktu_keluar !== null) {
                    $("#btnSelesai").remove();
                    allowEdit = false;

                    //Todo: Set as Viewer Page
                }

                prioritas_antrian = antrianData.prioritas;
                kunjungan = antrianData.kunjungan_detail;



                for(var poliSetKey in poliListRawList)
                {
                    if(poliListRawList[poliSetKey].poli.response_data[0] !== undefined) {
                        if(poliListRawList[poliSetKey].poli.response_data[0].uid == antrianData.departemen)
                        {
                            poliListRaw.push(poliListRawList[poliSetKey].poli.response_data[0]);
                        }
                    }
                }
                poliList = poliListRaw;

                if(antrianData.poli_info !== null) {
                    if(antrianData.poli_info.uid === __POLI_GIGI__ || antrianData.poli_info.uid === __POLI_ORTODONTIE__) {
                        $("#gigi_loader").show();
                    } else if(antrianData.poli_info.uid === __POLI_MATA__) {
                        $("#mata_loader").show();
                    } else {
                        $("#gigi_loader").hide();
                        $("#mata_loader").hide();
                    }
                    $("#heading_nama_poli").html(antrianData.poli_info.nama);
                } else {
                    if(antrianData.departemen === __POLI_INAP__) {
                        $("#heading_nama_poli").html("Rawat Inap");
                    }
                }



                pasien_uid = antrianData.pasien_info.uid;
                pasien_nama = antrianData.pasien_info.nama;
                pasien_usia = antrianData.pasien_info.usia;
                pasien_rm = antrianData.pasien_info.no_rm;
                pasien_kontak = antrianData.pasien_info.no_telp;
                pasien_jenkel = antrianData.pasien_info.jenkel_detail.nama;
                pasien_alamat = antrianData.pasien_info.alamat;
                pasien_tanggal_lahir = antrianData.pasien_info.tanggal_lahir;
                pasien_tempat_lahir = antrianData.pasien_info.tempat_lahir;
                pasien_penjamin = antrianData.penjamin_data.nama;
                pasien_penjamin_uid = antrianData.penjamin_data.uid;
                console.log(antrianData);

                $("#tombolKembaliInap").attr({
                    "href": __HOSTNAME__ + "/igdv2/dokter/asesmen-detail/" + pasien_uid + "/" + kunjungan.uid + "/" + pasien_penjamin_uid + "/" + __PAGES__[7]
                });
                $(".nama_pasien").html(pasien_nama + " <span class=\"text-info\">[" + pasien_rm + "]</span>");
                $("#target_pasien").html(pasien_nama + " <span class=\"text-info\">[" + pasien_rm + "]</span>").parent().attr({
                    "href": __HOSTNAME__ + "/igdv2/dokter/asesmen-detail/" + pasien_uid + "/" + kunjungan.uid + "/" + pasien_penjamin_uid + "/" + __PAGES__[7]
                });
                $("#nama-departemen").html("Asesmen");
                $(".jk_pasien").html(pasien_jenkel);
                $(".tanggal_lahir_pasien").html(pasien_tanggal_lahir);
                $(".penjamin_pasien").html(pasien_penjamin);

                $.ajax({
                    url:__HOSTAPI__ + "/Asesmen/antrian-detail/" + UID,
                    async:false,
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    type:"GET",
                    success:function(response) {
                        if(
                            response.response_package.response_data[0].status_asesmen !== null &&
                            response.response_package.response_data[0].status_asesmen !== undefined
                        ) {
                            if(
                                response.response_package.response_data[0].status_asesmen.status === "D"
                            )
                            {
                                /*$("#btnSelesai").remove();
                                $("#btnRI").remove();
                                $("#btnRujuk").remove();*/
                            }
                        }

                        if(response.response_package.response_data[0].asesmen_rawat != undefined) {
                            //loadAssesmen(response.response_package.response_data[0].asesmen_rawat);
                            loadPasien(UID);
                        }

                        if(response.response_package.response_data[0] === undefined) {
                            asesmen_detail = {};
                            tindakanMeta = generateTindakan(poliList[0].tindakan, antrianData, usedTindakan);
                        } else {
                            asesmen_detail = response.response_package.response_data[0];

                            if(asesmen_detail.tindakan !== undefined) {

                                if(asesmen_detail.tindakan.length > 0) {
                                    for(var tindakanKey in asesmen_detail.tindakan) {
                                        if(usedTindakan.indexOf(asesmen_detail.tindakan[tindakanKey].uid) < 0) {
                                            usedTindakan.push(asesmen_detail.tindakan[tindakanKey].uid);
                                            tindakanMeta = generateTindakan(poliList[0].tindakan, antrianData, usedTindakan);
                                            var hargaTindakan = generateTindakan2(asesmen_detail.tindakan[tindakanKey].uid, pasien_penjamin_uid);
                                            autoTindakan(tindakanMeta, {
                                                uid: asesmen_detail.tindakan[tindakanKey].uid,
                                                nama: asesmen_detail.tindakan[tindakanKey].nama,
                                                harga: hargaTindakan.harga
                                            }, antrianData);
                                        }
                                    }
                                } else {
                                    if(poliList[0] !== undefined) {
                                        tindakanMeta = generateTindakan(poliList[0].tindakan, antrianData, usedTindakan);
                                    } else {
                                        tindakanMeta = [];
                                    }
                                }
                            } else {
                                if(poliList[0] !== undefined) {
                                    tindakanMeta = generateTindakan(poliList[0].tindakan, antrianData, usedTindakan);
                                } else {
                                    tindakanMeta = [];
                                }
                            }

                            var keterangan_resep = "";
                            var keterangan_racikan = "";

                            if(response.response_package.response_data[0].resep !== undefined) {
                                if(response.response_package.response_data[0].resep.length > 0) {

                                    var resep_uid = response.response_package.response_data[0].resep[0].uid;
                                    var resep_obat_detail = response.response_package.response_data[0].resep[0].resep_detail;

                                    keterangan_resep = response.response_package.response_data[0].resep[0].keterangan;
                                    keterangan_racikan = response.response_package.response_data[0].resep[0].keterangan_racikan;

                                    for(var resepKey in resep_obat_detail) {
                                        autoResep({
                                            "obat": resep_obat_detail[resepKey].obat,
                                            "obat_detail": resep_obat_detail[resepKey].obat_detail,
                                            "aturan_pakai": resep_obat_detail[resepKey].aturan_pakai,
                                            "keterangan": resep_obat_detail[resepKey].keterangan,
                                            "signaKonsumsi": resep_obat_detail[resepKey].signa_qty,
                                            "signaTakar": resep_obat_detail[resepKey].signa_pakai,
                                            "signaHari": resep_obat_detail[resepKey].qty,
                                            "pasien_penjamin_uid": pasien_penjamin_uid
                                        });
                                    }

                                    if(resep_obat_detail.length > 0) {
                                        autoResep();
                                    }
                                }

                                var racikan_detail = response.response_package.response_data[0].racikan;
                                for(var racikanKey in racikan_detail) {
                                    autoRacikan({
                                        nama: racikan_detail[racikanKey].kode,
                                        keterangan: racikan_detail[racikanKey].keterangan,
                                        "signaKonsumsi": racikan_detail[racikanKey].signa_qty,
                                        "signaTakar": racikan_detail[racikanKey].signa_pakai,
                                        "signaHari": racikan_detail[racikanKey].qty,
                                        "item":racikan_detail[racikanKey].item,
                                        "aturan_pakai": racikan_detail[racikanKey].aturan_pakai
                                    });
                                    var itemKomposisi = racikan_detail[racikanKey].item;
                                    for(var komposisiKey in itemKomposisi) {
                                        var penjaminObatRacikanListUID = [];
                                        var penjaminObatRacikanList = itemKomposisi[komposisiKey].obat_detail.penjamin;
                                        for(var penjaminObatKey in penjaminObatRacikanList) {
                                            if(penjaminObatRacikanListUID.indexOf(penjaminObatRacikanList[penjaminObatKey].penjamin) < 0) {
                                                penjaminObatRacikanListUID.push(penjaminObatRacikanList[penjaminObatKey].penjamin);
                                            }
                                        }

                                        itemKomposisi[komposisiKey].satuan = "<b>" + itemKomposisi[komposisiKey].takar_bulat + "</b><sub nilaiExact=\"" + itemKomposisi[komposisiKey].ratio + "\">" + itemKomposisi[komposisiKey].takar_decimal + "</sub>";

                                        if(penjaminObatRacikanListUID.indexOf(pasien_penjamin_uid) > 0) {
                                            //infoPenjamin = "<b class=\"badge badge-success pull-rigth\"><i class=\"fa fa-check-circle\" style=\"margin-right: 5px;\"></i> Ditanggung Penjamin</b>";
                                        } else {
                                            //infoPenjamin = "<b class=\"badge badge-danger pull-rigth\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak Ditanggung Penjamin</b>";
                                        }

                                        //itemKomposisi[komposisiKey].obat_detail.nama += "<br />" + infoPenjamin;
                                        autoKomposisi((parseInt(racikanKey) + 1), itemKomposisi[komposisiKey]);
                                    }
                                }
                                if(racikan_detail.length > 0) {
                                    autoRacikan();
                                }
                            }
                            checkGenerateRacikan();
                        }

                        /*load_icd_10("#txt_icd_10_kerja", asesmen_detail.icd10_kerja);
                        load_icd_10("#txt_icd_10_banding", asesmen_detail.icd10_banding);*/

                        var rawSelectedKerja = [];
                        var rawSelectedBanding = [];
                        var rawSelectedFisik = [];

                        selectedICD10Kerja = asesmen_detail.icd10_kerja;
                        selectedICD10Banding = asesmen_detail.icd10_banding;
                        if(antrianData.poli_info !== null) {
                            if(antrianData.poli_info.uid === __UIDFISIOTERAPI__) {
                                if(asesmen_detail.icd9 !== undefined && asesmen_detail.icd9 !== null) {
                                    selectedICD9 = asesmen_detail.icd9;


                                    var icd9Parse = asesmen_detail.icd9;
                                    for(var icd9Key in icd9Parse) {
                                        if(rawSelectedFisik.indexOf(parseInt(icd9Parse[icd9Key].id)) < 0) {
                                            rawSelectedFisik.push(parseInt(icd9Parse[icd9Key].id));
                                            $("#txt_fisik_list tbody").append(
                                                "<tr targetICD=\"" + parseInt(icd9Parse[icd9Key].id) + "\">" +
                                                "<td>" + ($("#txt_fisik_list tbody tr").length + 1) + "</td>" +
                                                "<td>" + icd9Parse[icd9Key].nama + "</td>" +
                                                "<td><button class=\"btn btn-sm btn-danger btn_delete_icd_9\" targetICD=\"" + parseInt(icd9Parse[icd9Key].id) + "\"><i class=\"fa fa-trash\"></i></button></td>" +
                                                "</tr>"
                                            );
                                        }
                                    }
                                }
                            }
                        }



                        var icd10KerjaDataParse = asesmen_detail.icd10_kerja;
                        for(var icd10KerjaKey in icd10KerjaDataParse) {
                            if(rawSelectedKerja.indexOf(parseInt(icd10KerjaDataParse[icd10KerjaKey].id)) < 0) {
                                rawSelectedKerja.push(parseInt(icd10KerjaDataParse[icd10KerjaKey].id));
                                $("#txt_diagnosa_kerja_list tbody").append(
                                    "<tr targetICD=\"" + parseInt(icd10KerjaDataParse[icd10KerjaKey].id) + "\">" +
                                    "<td>" + ($("#txt_diagnosa_kerja_list tbody tr").length + 1) + "</td>" +
                                    "<td>" + icd10KerjaDataParse[icd10KerjaKey].nama + "</td>" +
                                    "<td><button class=\"btn btn-sm btn-danger btn_delete_icd_kerja\" targetICD=\"" + parseInt(icd10KerjaDataParse[icd10KerjaKey].id) + "\"><i class=\"fa fa-trash\"></i></button></td>" +
                                    "</tr>"
                                );
                            }
                        }

                        var icd10BandingDataParse = asesmen_detail.icd10_banding;
                        for(var icd10BandingKey in icd10BandingDataParse) {
                            if(rawSelectedBanding.indexOf(parseInt(icd10BandingDataParse[icd10BandingKey].id)) < 0) {
                                rawSelectedBanding.push(parseInt(icd10BandingDataParse[icd10BandingKey].id));
                                $("#txt_diagnosa_banding_list tbody").append(
                                    "<tr targetICD=\"" + parseInt(icd10BandingDataParse[icd10BandingKey].id) + "\">" +
                                    "<td>" + ($("#txt_diagnosa_banding_list tbody tr").length + 1) + "</td>" +
                                    "<td>" + icd10BandingDataParse[icd10BandingKey].nama + "</td>" +
                                    "<td><button class=\"btn btn-sm btn-danger btn_delete_icd_banding\" targetICD=\"" + parseInt(icd10BandingDataParse[icd10BandingKey].id) + "\"><i class=\"fa fa-trash\"></i></button></td>" +
                                    "</tr>"
                                );
                            }
                        }

                        if(asesmen_detail.muka_simetris !== undefined) {
                            $("input[name=\"simetris\"]").prop("checked", false);
                            $("input[name=\"simetris\"][value=\"" + asesmen_detail.muka_simetris + "\"]").prop("checked", true);
                        }

                        if(asesmen_detail.tmj !== undefined) {
                            $("input[name=\"sendi\"]").prop("checked", false);
                            $("input[name=\"sendi\"][value=\"" + asesmen_detail.tmj + "\"]").prop("checked", true);
                        }

                        if(asesmen_detail.bibir !== undefined) {
                            $("input[name=\"bibir\"]").prop("checked", false);
                            $("input[name=\"bibir\"][value=\"" + asesmen_detail.bibir + "\"]").prop("checked", true);
                        }

                        if(asesmen_detail.lidah !== undefined) {
                            $("input[name=\"lidah\"]").prop("checked", false);
                            $("input[name=\"lidah\"][value=\"" + asesmen_detail.lidah + "\"]").prop("checked", true);
                        }

                        if(asesmen_detail.mukosa !== undefined) {
                            $("input[name=\"mukosa\"]").prop("checked", false);
                            $("input[name=\"mukosa\"][value=\"" + asesmen_detail.mukosa + "\"]").prop("checked", true);
                        }

                        if(asesmen_detail.torus !== undefined) {
                            $("input[name=\"torus\"]").prop("checked", false);
                            $("input[name=\"torus\"][value=\"" + asesmen_detail.torus + "\"]").prop("checked", true);
                        }

                        if(asesmen_detail.gingiva !== undefined) {
                            $("input[name=\"gingiva\"]").prop("checked", false);
                            $("input[name=\"gingiva\"][value=\"" + asesmen_detail.gingiva + "\"]").prop("checked", true);
                        }

                        if(asesmen_detail.frenulum !== undefined) {
                            $("input[name=\"frenulum\"]").prop("checked", true);
                            $("input[name=\"frenulum\"][value=\"" + asesmen_detail.frenulum + "\"]").prop("checked", true);
                        }

                        if(asesmen_detail.kebersihan_mulut !== undefined) {
                            $("input[name=\"mulut_bersih\"]").prop("checked", false);
                            $("input[name=\"mulut_bersih\"][value=\"" + asesmen_detail.kebersihan_mulut + "\"]").prop("checked", true);
                        }

                        dataOdontogram = asesmen_detail.odontogram;
                        dataMukaSimetris = asesmen_detail.muka_simetris;
                        dataTMJ = asesmen_detail.tmj;
                        dataBibir = asesmen_detail.bibir;
                        dataLidah = asesmen_detail.lidah;
                        dataMukosa = asesmen_detail.mukosa;
                        dataTorus = asesmen_detail.torus;
                        dataGingiva = asesmen_detail.gingiva;
                        dataFrenulum = asesmen_detail.frenulum;
                        dataKebersihanMulut = asesmen_detail.kebersihan_mulut;



                        parse_icd_10("#txt_icd_10_kerja", allICD10, rawSelectedKerja);
                        parse_icd_10("#txt_icd_10_banding", allICD10, rawSelectedBanding);
                        parse_icd_9("#txt_icd_9", allICD9, rawSelectedFisik);

                        /*$("#txt_icd_10_kerja").select2();
                        $("#txt_icd_10_banding").select2();*/

                        metaSwitchEdit.txt_keluhan_utama.data = (asesmen_detail.keluhan_utama === undefined) ? "" : asesmen_detail.keluhan_utama;
                        metaSwitchEdit.txt_keluhan_tambahan.data = (asesmen_detail.keluhan_tambahan === undefined) ? "" : asesmen_detail.keluhan_tambahan;
                        metaSwitchEdit.txt_pemeriksaan_fisik.data = (asesmen_detail.pemeriksaan_fisik === undefined) ? "" : asesmen_detail.pemeriksaan_fisik;
                        metaSwitchEdit.txt_diagnosa_kerja.data = (asesmen_detail.diagnosa_kerja === undefined) ? "" : asesmen_detail.diagnosa_kerja;
                        metaSwitchEdit.txt_diagnosa_banding.data = (asesmen_detail.diagnosa_banding === undefined) ? "" : asesmen_detail.diagnosa_banding;
                        metaSwitchEdit.txt_planning.data = (asesmen_detail.planning === undefined) ? "" : asesmen_detail.planning;
                        metaSwitchEdit.txt_keterangan_resep.data = keterangan_resep;
                        metaSwitchEdit.txt_keterangan_resep_racikan.data = keterangan_racikan;

                        /*ClassicEditor
                            .create( document.querySelector( '#txt_keluhan_utama' ), {
                                extraPlugins: [ MyCustomUploadAdapterPlugin ],
                                placeholder: "Keluhan Utama...",
                                removePlugins: ['MediaEmbed']
                            } )
                            .then( editor => {
                                if(asesmen_detail.keluhan_utama === undefined) {
                                    editor.setData("");
                                } else {
                                    editor.setData(asesmen_detail.keluhan_utama);
                                }
                                editorKeluhanUtamaData = editor;
                                window.editor = editor;
                            } )
                            .catch( err => {
                                //console.error( err.stack );
                            } );

                        ClassicEditor
                            .create( document.querySelector( '#txt_keluhan_tambahan' ), {
                                extraPlugins: [ MyCustomUploadAdapterPlugin ],
                                placeholder: "Keluhan Tambahan...",
                                removePlugins: ['MediaEmbed']
                            } )
                            .then( editor => {
                                if(asesmen_detail.keluhan_tambahan === undefined) {
                                    editor.setData("");
                                } else {
                                    editor.setData(asesmen_detail.keluhan_tambahan);
                                }
                                editorKeluhanTambahanData = editor;
                                window.editor = editor;
                            } )
                            .catch( err => {
                                //console.error( err.stack );
                            } );

                        ClassicEditor
                            .create( document.querySelector( '#txt_pemeriksaan_fisik' ), {
                                extraPlugins: [ MyCustomUploadAdapterPlugin ],
                                placeholder: "Pemeriksaan Fisik...",
                                removePlugins: ['MediaEmbed']
                            } )
                            .then( editor => {
                                if(asesmen_detail.pemeriksaan_fisik === undefined) {
                                    editor.setData("");
                                } else {
                                    editor.setData(asesmen_detail.pemeriksaan_fisik);
                                }
                                editorPeriksaFisikData = editor;
                                window.editor = editor;
                            } )
                            .catch( err => {
                                //console.error( err.stack );
                            } );

                        ClassicEditor
                            .create( document.querySelector( '#txt_diagnosa_kerja' ), {
                                extraPlugins: [ MyCustomUploadAdapterPlugin ],
                                placeholder: "Diagnosa Kerja...",
                                removePlugins: ['MediaEmbed']
                            } )
                            .then( editor => {
                                if(asesmen_detail.diagnosa_kerja === undefined) {
                                    editor.setData("");
                                } else {
                                    editor.setData(asesmen_detail.diagnosa_kerja);
                                }
                                editorKerja = editor;
                                window.editor = editor;
                            } )
                            .catch( err => {
                                //console.error( err.stack );
                            } );

                        ClassicEditor
                            .create( document.querySelector( '#txt_diagnosa_banding' ), {
                                extraPlugins: [ MyCustomUploadAdapterPlugin ],
                                placeholder: "Diagnosa Banding...",
                                removePlugins: ['MediaEmbed']
                            } )
                            .then( editor => {
                                if(asesmen_detail.diagnosa_banding === undefined) {
                                    editor.setData("");
                                } else {
                                    editor.setData(asesmen_detail.diagnosa_banding);
                                }
                                editorBanding = editor;
                                window.editor = editor;
                            } )
                            .catch( err => {
                                //console.error( err.stack );
                            } );
                        ClassicEditor
                            .create( document.querySelector( '#txt_keterangan_resep' ), {
                                extraPlugins: [ MyCustomUploadAdapterPlugin ],
                                placeholder: "Keterangan resep...",
                                removePlugins: ['MediaEmbed']
                            } )
                            .then( editor => {
                                editor.setData(keterangan_resep);
                                editorKeteranganResep = editor;
                                window.editor = editor;
                            } )
                            .catch( err => {
                                //console.error( err.stack );
                            } );

                        ClassicEditor
                            .create( document.querySelector( '#txt_keterangan_resep_racikan' ), {
                                extraPlugins: [ MyCustomUploadAdapterPlugin ],
                                placeholder: "Keterangan racikan...",
                                removePlugins: ['MediaEmbed']
                            } )
                            .then( editor => {
                                editor.setData(keterangan_racikan);
                                editorKeteranganResepRacikan = editor;
                                window.editor = editor;
                            } )
                            .catch( err => {
                                //console.error( err.stack );
                            } );

                        ClassicEditor
                            .create( document.querySelector( '#txt_planning' ), {
                                extraPlugins: [ MyCustomUploadAdapterPlugin ],
                                placeholder: "Planning Tindakan",
                                removePlugins: ['MediaEmbed']
                            } )
                            .then( editor => {
                                if(asesmen_detail.planning === undefined) {
                                    editor.setData("");
                                } else {
                                    editor.setData(asesmen_detail.planning);
                                }
                                editorPlanning = editor;
                                window.editor = editor;
                            } )
                            .catch( err => {
                                //console.error( err.stack );
                            } );*/



                        /*$("#txt_tanda_vital_td").val(asesmen_detail.tekanan_darah);
                        $("#txt_tanda_vital_s").val(asesmen_detail.suhu);
                        $("#txt_tanda_vital_n").val(asesmen_detail.nadi);
                        $("#txt_tanda_vital_rr").val(asesmen_detail.pernafasan);
                        $("#txt_berat_badan").val(asesmen_detail.berat_badan);*/
                        /*alert(asesmen_detail.tinggi_badan);
                        alert(asesmen_detail.lingkar_lengan_atas);*/
                        //$("#txt_tinggi_badan").val(asesmen_detail.tinggi_badan);
                        //$("#txt_lingkar_lengan").val(asesmen_detail.lingkar_lengan_atas);

                        if(antrianData.poli_info !== null) {
                            if(antrianData.poli_info.uid === __POLI_MATA__) {
                                if(asesmen_detail.meta_resep !== undefined) {
                                    var readMetaResepMata = JSON.parse(asesmen_detail.meta_resep);
                                    for(var mataKey in readMetaResepMata)
                                    {
                                        $("#" + mataKey).val(readMetaResepMata[mataKey]);
                                    }

                                    var tujuanMata = asesmen_detail.tujuan_resep.split(",");
                                    for(tujuanMataKey in tujuanMata) {
                                        $(".tujuan_resep[value=\"" + tujuanMata[tujuanMataKey] + "\"]").prop("checked", true);
                                    }
                                }
                            }

                            //Terapis CKEDITOR
                            if(antrianData.poli_info.uid === __UIDFISIOTERAPI__) {
                                $("#txt_terapis_frekuensi_minggu").val(asesmen_detail.anjuran_minggu);
                                $("#txt_terapis_frekuensi_bulan").val(asesmen_detail.anjuran_bulan);
                                if(
                                    asesmen_detail.suspek_akibat_kerja !== undefined &&
                                    asesmen_detail.suspek_akibat_kerja !== null &&
                                    asesmen_detail.suspek_akibat_kerja != ""
                                ) {
                                    $("input[type=\"radio\"][name=\"suspek_kerja\"][value=\"y\"]").prop("checked", true);
                                    $("#suspek_kerja").val(asesmen_detail.suspek_akibat_kerja).removeAttr("disabled");
                                }
                                ClassicEditor
                                    .create( document.querySelector( '#txt_terapis_anamnesa' ), {
                                        extraPlugins: [ MyCustomUploadAdapterPlugin ],
                                        placeholder: "Anamnesa...",
                                        removePlugins: ['MediaEmbed']
                                    } )
                                    .then( editor => {
                                        if(asesmen_detail.anamnesa === undefined) {
                                            editor.setData("");
                                        } else {
                                            editor.setData(asesmen_detail.anamnesa);
                                        }
                                        editorTerapisAnamnesa = editor;
                                        window.editor = editor;
                                    } )
                                    .catch( err => {
                                        //console.error( err.stack );
                                    } );

                                ClassicEditor
                                    .create( document.querySelector( '#txt_terapis_tatalaksana' ), {
                                        extraPlugins: [ MyCustomUploadAdapterPlugin ],
                                        placeholder: "Tata Laksana KFR...",
                                        removePlugins: ['MediaEmbed']
                                    } )
                                    .then( editor => {
                                        if(asesmen_detail.tatalaksana === undefined) {
                                            editor.setData("");
                                        } else {
                                            editor.setData(asesmen_detail.tatalaksana);
                                        }
                                        editorTerapisTataLaksana = editor;
                                        window.editor = editor;
                                    } )
                                    .catch( err => {
                                        //console.error( err.stack );
                                    } );

                                ClassicEditor
                                    .create( document.querySelector( '#txt_terapis_evaluasi' ), {
                                        extraPlugins: [ MyCustomUploadAdapterPlugin ],
                                        placeholder: "Evaluasi...",
                                        removePlugins: ['MediaEmbed']
                                    } )
                                    .then( editor => {
                                        if(asesmen_detail.evaluasi === undefined) {
                                            editor.setData("");
                                        } else {
                                            editor.setData(asesmen_detail.evaluasi);
                                        }
                                        editorTerapisEvaluasi = editor;
                                        window.editor = editor;
                                    } )
                                    .catch( err => {
                                        //console.error( err.stack );
                                    } );

                                ClassicEditor
                                    .create( document.querySelector( '#txt_terapis_hasil' ), {
                                        extraPlugins: [ MyCustomUploadAdapterPlugin ],
                                        placeholder: "Hasil yang didapat...",
                                        removePlugins: ['MediaEmbed']
                                    } )
                                    .then( editor => {
                                        if(asesmen_detail.hasil === undefined || asesmen_detail.hasil === null) {
                                            editor.setData("");
                                        } else {
                                            editor.setData(asesmen_detail.hasil);
                                        }
                                        editorTerapisHasil = editor;
                                        window.editor = editor;
                                    } )
                                    .catch( err => {
                                        //console.error( err.stack );
                                    } );

                                ClassicEditor
                                    .create( document.querySelector( '#txt_terapis_kesimpulan' ), {
                                        extraPlugins: [ MyCustomUploadAdapterPlugin ],
                                        placeholder: "Kesimpulan...",
                                        removePlugins: ['MediaEmbed']
                                    } )
                                    .then( editor => {
                                        if(asesmen_detail.kesimpulan === undefined || asesmen_detail.kesimpulan === null) {
                                            editor.setData("");
                                        } else {
                                            editor.setData(asesmen_detail.kesimpulan);
                                        }
                                        editorTerapisKesimpulan = editor;
                                        window.editor = editor;
                                    } )
                                    .catch( err => {
                                        //console.error( err.stack );
                                    } );

                                ClassicEditor
                                    .create( document.querySelector( '#txt_terapis_rekomendasi' ), {
                                        extraPlugins: [ MyCustomUploadAdapterPlugin ],
                                        placeholder: "Rekomendasi...",
                                        removePlugins: ['MediaEmbed']
                                    } )
                                    .then( editor => {
                                        if(asesmen_detail.rekomendasi === undefined || asesmen_detail.rekomendasi === null) {
                                            editor.setData("");
                                        } else {
                                            editor.setData(asesmen_detail.rekomendasi);
                                        }
                                        editorTerapisRekomendasi = editor;
                                        window.editor = editor;
                                    } )
                                    .catch( err => {
                                        //console.error( err.stack );
                                    } );
                            } else {
                                $(".special-tab-fisioterapi").hide();
                            }
                        } else {
                            $(".special-tab-fisioterapi").hide();
                        }


                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            },
            error: function(response) {
                console.log(response);
            }
        });


        if(poliList.length > 1) {
            $("#change-poli").show();
            $("#current-poli").addClass("handy");
        } else {
            $("#change-poli").hide();
            $("#current-poli").removeClass("handy");
        }

        $("body").on("click", ".edit-switch-container", function() {
            var target = $(this).attr("target");
            $(this).html("Loading...");
            ClassicEditor
                .create( document.querySelector( "#" + target ), {
                    extraPlugins: [ MyCustomUploadAdapterPlugin ],
                    placeholder: metaSwitchEdit[target].placeholder,
                    removePlugins: ['MediaEmbed']
                } )
                .then( editor => {
                    editor.setData(metaSwitchEdit[target].data);
                    if(metaSwitchEdit[target].editor === null) {
                        metaSwitchEdit[target].editor = editor;
                    }
                } )
                .catch( err => {
                    //console.error( err.stack );
                });
            $(this).remove();
        });

        $("#btn_tambah_icd10_kerja").click(function() {
            var allowAdd = false;
            if(selectedICD10Kerja === undefined) {
                selectedICD10Kerja = [];
            }

            if(selectedICD10Kerja.length > 0) {
                for(var selectedKeyKerja in selectedICD10Kerja) {
                    if(selectedICD10Kerja[selectedKeyKerja].id !== parseInt($("#txt_icd_10_kerja").val())) {
                        allowAdd = true;
                    } else {
                        allowAdd = false;
                        break;
                    }
                }
            } else {
                allowAdd = true;
            }

            if(allowAdd && !isNaN(parseInt($("#txt_icd_10_kerja").val()))) {
                $("#txt_diagnosa_kerja_list tbody").append(
                    "<tr targetICD=\"" + parseInt($("#txt_icd_10_kerja").val()) + "\">" +
                    "<td>" + ($("#txt_diagnosa_kerja_list tbody tr").length + 1) + "</td>" +
                    "<td>" + $("#txt_icd_10_kerja option:selected").text() + "</td>" +
                    "<td><button class=\"btn btn-sm btn-danger btn_delete_icd_kerja\" targetICD=\"" + parseInt($("#txt_icd_10_kerja").val()) + "\"><i class=\"fa fa-trash\"></i></button></td>" +
                    "</tr>"
                );

                selectedICD10Kerja.push({
                    id: parseInt($("#txt_icd_10_kerja").val()),
                    nama: $("#txt_icd_10_kerja option[value=\"" + parseInt($("#txt_icd_10_kerja").val()) + "\"]").text()
                });

                $("#txt_icd_10_kerja option[value=\"" + parseInt($("#txt_icd_10_kerja").val()) + "\"]").remove();
                rebaseICD("#txt_diagnosa_kerja_list");
            }
        });

        $("body").on("click", ".btn_delete_icd_kerja", function() {
            var id = $(this).attr("targetICD");
            for(var selectedKeyKerja in selectedICD10Kerja) {
                if(selectedICD10Kerja[selectedKeyKerja].id == id) {
                    $("#txt_diagnosa_kerja_list tbody tr[targetICD=\"" + selectedICD10Kerja[selectedKeyKerja].id +"\"]").remove();
                    $("#txt_icd_10_kerja").prepend("<option value=\"" + selectedICD10Kerja[selectedKeyKerja].id + "\">" + selectedICD10Kerja[selectedKeyKerja].nama + "</option>");
                    selectedICD10Kerja.splice(selectedKeyKerja, 1);
                }
            }
            rebaseICD("#txt_diagnosa_kerja_list");
        });

        function rebaseICD(target) {
            $(target + " tbody tr").each(function(e) {
                $(this).find("td:eq(0)").html((e + 1));
            });
        }


        $("#btn_tambah_icd10_banding").click(function() {
            var allowAdd = false;
            if(selectedICD10Banding === undefined) {
                selectedICD10Banding = [];
            }
            if(selectedICD10Banding.length > 0) {
                for(var selectedKeyBanding in selectedICD10Banding) {
                    if(selectedICD10Banding[selectedKeyBanding].id != parseInt($("#txt_icd_10_banding").val())) {
                        allowAdd = true;
                    } else {
                        allowAdd = false;
                        break;
                    }
                }
            } else {
                allowAdd = true;
            }

            if(allowAdd) {
                $("#txt_diagnosa_banding_list tbody").append(
                    "<tr targetICD=\"" + parseInt($("#txt_icd_10_banding").val()) + "\">" +
                    "<td>" + ($("#txt_diagnosa_banding_list tbody tr").length + 1) + "</td>" +
                    "<td>" + $("#txt_icd_10_banding option:selected").text() + "</td>" +
                    "<td><button class=\"btn btn-sm btn-danger btn_delete_icd_banding\" targetICD=\"" + parseInt($("#txt_icd_10_banding").val()) + "\"><i class=\"fa fa-trash\"></i></button></td>" +
                    "</tr>"
                );

                selectedICD10Banding.push({
                    id: parseInt($("#txt_icd_10_banding").val()),
                    nama: $("#txt_icd_10_banding option[value=\"" + parseInt($("#txt_icd_10_banding").val()) + "\"]").text()
                });

                $("#txt_icd_10_banding option[value=\"" + parseInt($("#txt_icd_10_banding").val()) + "\"]").remove();
                rebaseICD("#txt_diagnosa_banding_list");
            }
        });

        $("body").on("click", ".btn_delete_icd_banding", function() {
            var id = $(this).attr("targetICD");
            for(var selectedKeyBanding in selectedICD10Banding) {
                if(selectedICD10Banding[selectedKeyBanding].id == id) {
                    $("#txt_diagnosa_banding_list tbody tr[targetICD=\"" + selectedICD10Banding[selectedKeyBanding].id +"\"]").remove();
                    $("#txt_icd_10_banding").prepend("<option value=\"" + selectedICD10Banding[selectedKeyBanding].id + "\">" + selectedICD10Banding[selectedKeyBanding].nama + "</option>");
                    selectedICD10Banding.splice(selectedKeyBanding, 1);
                }
            }
            rebaseICD("#txt_diagnosa_banding_list");
        });






        $("#btn_tambah_icd9").click(function() {
            var allowAdd = false;
            if(selectedICD9 === undefined) {
                selectedICD9 = [];
            }

            if(selectedICD9.length > 0) {
                for(var selectedKey9 in selectedICD9) {
                    if(selectedICD9[selectedKey9].id !== parseInt($("#txt_icd_9").val())) {
                        allowAdd = true;
                    } else {
                        allowAdd = false;
                        break;
                    }
                }
            } else {
                allowAdd = true;
            }

            if(allowAdd) {
                $("#txt_fisik_list tbody").append(
                    "<tr targetICD=\"" + parseInt($("#txt_icd_9").val()) + "\">" +
                    "<td>" + ($("#txt_fisik_list tbody tr").length + 1) + "</td>" +
                    "<td>" + $("#txt_icd_9 option:selected").text() + "</td>" +
                    "<td><button class=\"btn btn-sm btn-danger btn_delete_icd_9\" targetICD=\"" + parseInt($("#txt_icd_9").val()) + "\"><i class=\"fa fa-trash\"></i></button></td>" +
                    "</tr>"
                );

                selectedICD9.push({
                    id: parseInt($("#txt_icd_9").val()),
                    nama: $("#txt_icd_9 option[value=\"" + parseInt($("#txt_icd_9").val()) + "\"]").text()
                });

                $("#txt_icd_9 option[value=\"" + parseInt($("#txt_icd_9").val()) + "\"]").remove();
                rebaseICD("#txt_fisik_list");
            }
        });

        $("body").on("click", ".btn_delete_icd_9", function() {
            var id = $(this).attr("targetICD");
            for(var selectedKey9 in selectedICD9) {
                if(selectedICD9[selectedKey9].id == id) {
                    $("#txt_fisik_list tbody tr[targetICD=\"" + selectedICD9[selectedKey9].id +"\"]").remove();
                    $("#txt_icd_9").prepend("<option value=\"" + selectedICD9[selectedKey9].id + "\">" + selectedICD9[selectedKey9].nama + "</option>");
                    selectedICD9.splice(selectedKey9, 1);
                }
            }
            rebaseICD("#txt_fisik_list");
        });



        if(poliList[0] !== undefined) {
            $("#current-poli").prepend(poliList[0]['nama']);
        }

        function generateTindakan2(target, penjamin) {
            var returnedData;
            $.ajax({
                url:__HOSTAPI__ + "/Tindakan/get-harga-per-kelas/RJ/" + penjamin,
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    var tinData = response.response_package;
                    for(var tinKey in tinData) {

                        if(tinData[tinKey].tindakan === target) {
                            returnedData = tinData[tinKey];
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return returnedData;
        }

        function generateTindakan(poliList, antrianData, selected = []) {
            var tindakanMeta = {};
            $("#txt_tindakan option").remove();
            var __UID_KONSULTASI__ = <?php echo json_encode(__UID_KONSULTASI__); ?>;
            var __UID_KARTU__ = <?php echo json_encode(__UID_KARTU__); ?>;
            for(var key in poliList) {
                if(poliList[key].tindakan != null) {
                    if(tindakanMeta[poliList[key].uid_tindakan] === undefined) {
                        tindakanMeta[poliList[key].uid_tindakan] = [];
                        tindakanMeta[poliList[key].uid_tindakan].kelas = poliList[key].kelas;
                        tindakanMeta[poliList[key].uid_tindakan].nama = poliList[key].tindakan.nama;
                    }

                    if(poliList[key].penjamin != undefined){
                        if(antrianData.penjamin == poliList[key].uid_penjamin) {
                            tindakanMeta[poliList[key].uid_tindakan].push({
                                uid: poliList[key].uid_penjamin,
                                nama: poliList[key].penjamin.nama
                            });
                        }
                    }
                }
            }

            for(var key in tindakanMeta) {
                if(selected.indexOf(key) < 0 && tindakanMeta[key].nama != undefined && key != __UID_KONSULTASI__ && key != __UID_KARTU__) {
                    $("#txt_tindakan").append(
                        "<option value=\"" + key + "\" kelas=\"" + tindakanMeta[key].kelas + "\">" + tindakanMeta[key].nama + "</option>"
                    );
                }
            }
            return tindakanMeta;
        }

        $("#txt_tindakan").select2();

        $("#btnTambahTindakan").click(function(){
            var hargaTindakan = generateTindakan2($("#txt_tindakan").val(), pasien_penjamin_uid);
            if(hargaTindakan === undefined) {
                Swal.fire(
                    "Tindakan",
                    "Harga tindakan belum ditentukan. Tindakan tidak dapat digunakan",
                    "error"
                ).then((result) => {
                    //
                });
            } else {
                if(parseFloat(hargaTindakan.harga) > 0) {
                    autoTindakan(tindakanMeta, {
                        uid: $("#txt_tindakan").val(),
                        nama: $("#txt_tindakan option:selected").text(),
                        kelas: $("#txt_tindakan option:selected").attr("kelas"),
                        harga : parseFloat(hargaTindakan.harga)
                    }, antrianData);

                    if(usedTindakan.indexOf($("#txt_tindakan").val()) < 0) {
                        usedTindakan.push($("#txt_tindakan").val());
                        tindakanMeta = generateTindakan(poliList[0].tindakan, antrianData, usedTindakan);
                    }
                }
            }

            return false;
        });

        $("body").on("click", ".btnDeleteTindakan", function(){
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            $("#row_tindakan_" + id).remove();
            usedTindakan.splice(usedTindakan.indexOf($(this).val()), 1);
            tindakanMeta = generateTindakan(poliList[0].tindakan, antrianData, usedTindakan);
            return false;
        });

        function autoTindakan(penjaminMeta, setTindakan) {
            var newRowTindakan = document.createElement("TR");
            var newCellTindakanID = document.createElement("TD");
            var newCellTindakanTindakan = document.createElement("TD");
            var newCellTindakanPenjamin = document.createElement("TD");
            var newCellTindakanAksi = document.createElement("TD");

            $(newCellTindakanTindakan).html(setTindakan.nama).attr({
                "set-tindakan": setTindakan.uid
            }).attr("kelas", setTindakan.kelas);
            /*var newPenjamin = document.createElement("SELECT");

            for(var a = 0; a < penjaminMeta[setTindakan.uid].length; a++) {
                if(penjaminMeta[setTindakan.uid][a].uid == antrianData.penjamin) {
                    $(newPenjamin).append("<option " + ((penjaminMeta[setTindakan.uid][a].uid == selectedPenjamin.penjamin) ? "selected=\"selected\"" : "") + " value=\"" + penjaminMeta[setTindakan.uid][a].uid + "\">" + penjaminMeta[setTindakan.uid][a].nama + "</option>");
                }
            }*/

            $(newCellTindakanPenjamin).addClass("number_style").html(number_format(setTindakan.harga, 2, ".", ","));


            var newPenjaminDelete = document.createElement("BUTTON");
            $(newPenjaminDelete).addClass("btn btn-sm btn-danger btnDeleteTindakan").html("<i class=\"fa fa-ban\"></i>");
            $(newCellTindakanAksi).append(newPenjaminDelete);

            $(newRowTindakan).append(newCellTindakanID);
            $(newRowTindakan).append(newCellTindakanTindakan);
            $(newRowTindakan).append(newCellTindakanPenjamin);
            $(newRowTindakan).append(newCellTindakanAksi);

            $("#table-tindakan").append(newRowTindakan);
            rebaseTindakan();
        }

        function rebaseTindakan() {
            $("#table-tindakan tbody tr").each(function(e) {
                var id = (e + 1);
                $(this).attr({
                    "id": "row_tindakan_" + id
                });

                $(this).find("td:eq(0)").html(id);
                $(this).find("td:eq(3) button").attr({
                    "id": "delete_tindakan_" + id
                });
            });
        }

        function load_icd_10() {
            var icd10Data;
            $.ajax({
                url:__HOSTAPI__ + "/Icd/icd10",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    icd10Data = response.response_package.response_data;
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return icd10Data;
        }

        function parse_icd_10(target, icd10Data, selectedData = []) {
            /*$(target + " option").remove();

            for(var a = 0; a < icd10Data.length; a++) {
                if(selectedData.indexOf(parseInt(icd10Data[a].id)) < 0) {
                    $(target).append("<option value=\"" + icd10Data[a].id + "\">" + icd10Data[a].kode + " - " + icd10Data[a].nama + "</option>");
                }
            }*/
            $(target).select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function(){
                        return "ICD10 tidak ditemukan";
                    }
                },
                placeholder: "Cari ICD10",
                ajax: {
                    dataType: "json",
                    headers:{
                        "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                        "Content-Type" : "application/json",
                    },
                    url:__HOSTAPI__ + "/Icd/icd10_select2",
                    type: "GET",
                    data: function (term) {
                        return {
                            search:term.term
                        };
                    },
                    cache: true,
                    processResults: function (response) {
                        var data = response.response_package.response_data;
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama,
                                    id: item.id
                                }
                            })
                        };
                    }
                }
            }).addClass("form-control").on("select2:select", function(e) {
                var data = e.params.data;
            });
        }

        function parse_icd_9(target, icd10Data, selectedData = []) {
            $(target).select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function(){
                        return "ICD9 tidak ditemukan";
                    }
                },
                placeholder: "Cari ICD9",
                ajax: {
                    dataType: "json",
                    headers:{
                        "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                        "Content-Type" : "application/json",
                    },
                    url:__HOSTAPI__ + "/Icd/icd9_select2",
                    type: "GET",
                    data: function (term) {
                        return {
                            search:term.term
                        };
                    },
                    cache: true,
                    processResults: function (response) {
                        var data = response.response_package.response_data;
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama,
                                    id: item.id
                                }
                            })
                        };
                    }
                }
            }).addClass("form-control").on("select2:select", function(e) {
                var data = e.params.data;
            });
        }

        class MyUploadAdapter {
            static loader;
            constructor( loader ) {
                // CKEditor 5's FileLoader instance.
                this.loader = loader;

                // URL where to send files.
                this.url = __HOSTAPI__ + "/Upload";

                this.imageList = [];
            }

            // Starts the upload process.
            upload() {
                return new Promise( ( resolve, reject ) => {
                    this._initRequest();
                    this._initListeners( resolve, reject );
                    this._sendRequest();
                } );
            }

            // Aborts the upload process.
            abort() {
                if ( this.xhr ) {
                    this.xhr.abort();
                }
            }

            // Example implementation using XMLHttpRequest.
            _initRequest() {
                const xhr = this.xhr = new XMLHttpRequest();

                xhr.open( 'POST', this.url, true );
                xhr.setRequestHeader("Authorization", 'Bearer ' + <?php echo json_encode($_SESSION["admin_ciscard"]); ?>);
                xhr.responseType = 'json';
            }

            // Initializes XMLHttpRequest listeners.
            _initListeners( resolve, reject ) {
                const xhr = this.xhr;
                const loader = this.loader;
                const genericErrorText = 'Couldn\'t upload file:' + ` ${ loader.file.name }.`;

                xhr.addEventListener( 'error', () => reject( genericErrorText ) );
                xhr.addEventListener( 'abort', () => reject() );
                xhr.addEventListener( 'load', () => {
                    const response = xhr.response;

                    if ( !response || response.error ) {
                        return reject( response && response.error ? response.error.message : genericErrorText );
                    }

                    // If the upload is successful, resolve the upload promise with an object containing
                    // at least the "default" URL, pointing to the image on the server.
                    resolve( {
                        default: response.url
                    } );
                } );

                if ( xhr.upload ) {
                    xhr.upload.addEventListener( 'progress', evt => {
                        if ( evt.lengthComputable ) {
                            loader.uploadTotal = evt.total;
                            loader.uploaded = evt.loaded;
                        }
                    } );
                }
            }


            // Prepares the data and sends the request.
            _sendRequest() {
                const toBase64 = file => new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = () => resolve(reader.result);
                    reader.onerror = error => reject(error);
                });
                var Axhr = this.xhr;

                async function doSomething(fileTarget) {
                    fileTarget.then(function(result) {
                        var ImageName = result.name;

                        toBase64(result).then(function(renderRes) {
                            const data = new FormData();
                            data.append( 'upload', renderRes);
                            data.append( 'name', ImageName);
                            Axhr.send( data );
                        });
                    });
                }

                var ImageList = this.imageList;

                this.loader.file.then(function(toAddImage) {

                    ImageList.push(toAddImage.name);

                });

                this.imageList = ImageList;

                doSomething(this.loader.file);
            }
        }


        function MyCustomUploadAdapterPlugin( editor ) {
            editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
                var MyCust = new MyUploadAdapter( loader );
                var dataToPush = MyCust.imageList;
                hiJackImage(dataToPush);
                return MyCust;
            };
        }

        var imageResultPopulator = [];

        function hiJackImage(toHi) {
            imageResultPopulator.push(toHi);
        }


        function load_product_penjamin(target, obat, selectedData = "") {
            var productData;
            $.ajax({
                /*url:__HOSTAPI__ + "/Penjamin/get_penjamin_obat/" + obat,*/
                url:__HOSTAPI__ + "/Penjamin/get_penjamin_obat/" + obat,
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    $(target).find("option").remove();
                    productData = response.response_package.response_data;
                    for (var a = 0; a < productData.length; a++) {
                        $(target).append("<option " + ((productData[a].penjamin.uid == selectedData) ? "selected=\"selected\"" : "") + " value=\"" + penjaminData[a].penjamin.uid + "\">" + penjaminData[a].penjamin.nama + "</option>");
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return productData;
        }

        function load_product_resep(target, selectedData = "", appendData = true) {
            var selected = [];
            var productData = [];

            $(target).select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function(){
                        return "Barang tidak ditemukan";
                    }
                },
                ajax: {
                    dataType: "json",
                    headers:{
                        "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                        "Content-Type" : "application/json",
                    },
                    url:__HOSTAPI__ + "/Inventori/get_item_select2/" + $(".select2-search__field").val(),
                    type: "GET",
                    data: function (term) {
                        return {
                            search:term.term
                        };
                    },
                    cache: true,
                    processResults: function (response) {
                        var data = response.response_package.response_data;
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama,
                                    id: item.uid,
                                    satuan_terkecil: item.satuan_terkecil.nama
                                }
                            })
                        };
                    }
                }
            }).addClass("form-control item-amprah").on("select2:select", function(e) {
                var data = e.params.data;
                if(data.satuan_terkecil != undefined) {
                    $(this).children("[value=\""+ data.id + "\"]").attr({
                        "satuan-caption": data.satuan_terkecil
                    });
                } else {
                    return false;
                }
            });





            /*$.ajax({
                url:__HOSTAPI__ + "/Inventori",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					$(target).find("option").remove();
					$(target).append("<option value=\"none\">Pilih Obat</option>");
					productData = response.response_package.response_data;
					for (var a = 0; a < productData.length; a++) {
						var penjaminList = [];
						var penjaminListData = productData[a].penjamin;
						for(var penjaminKey in penjaminListData) {
							if(penjaminList.indexOf(penjaminListData[penjaminKey].penjamin.uid) < 0) {
								penjaminList.push(penjaminListData[penjaminKey].penjamin.uid);
							}
						}

						if(selected.indexOf(productData[a].uid) < 0 && appendData) {
							$(target).append("<option penjamin-list=\"" + penjaminList.join(",") + "\" satuan-caption=\"" + productData[a].satuan_terkecil.nama + "\" satuan-terkecil=\"" + productData[a].satuan_terkecil.uid + "\" " + ((productData[a].uid == selectedData) ? "selected=\"selected\"" : "") + " value=\"" + productData[a].uid + "\">" + productData[a].nama.toUpperCase() + "</option>");
						}
					}
				},
				error: function(response) {
					console.log(response);
				}
			});*/
            //return (productData.length == selected.length);
            return {
                allow: true,
                data: []
            };
        }

        checkGenerateResep();

        function checkGenerateResep(id = 0) {
            if($(".last-resep").length == 0) {
                autoResep();
            } else {
                var obat = $("#resep_obat_" + id).val();
                var jlh_hari = $("#resep_jlh_hari_" + id).inputmask("unmaskedvalue");
                var signa_konsumsi = $("#resep_signa_konsumsi_" + id).inputmask("unmaskedvalue");
                var signa_hari = $("#resep_signa_takar_" + id).inputmask("unmaskedvalue");
                var aturanPakai = $("#resep_aturan_pakai_" + id).val();
                if(
                    parseFloat(jlh_hari) > 0 &&
                    parseFloat(signa_konsumsi) > 0 &&
                    parseFloat(signa_hari) > 0 &&
                    obat != null &&
                    $("#resep_row_" + id).hasClass("last-resep")
                    //&& parseInt(aturanPakai) > 0
                ) {
                    autoResep();
                } else {
                    if(aturanPakai === "none") {
                        //notify_manual("info", "<i class=\"fa fa-info-circle\"></i> Aturan pakai harus diisi", 1000, "aturan_pakai_" + id, "#resep_aturan_pakai_" + id);
                    }
                }
            }
        }

        function autoAturanPakai() {
            var dataAturanPakai;
            $.ajax({
                url:__HOSTAPI__ + "/Terminologi/terminologi-items/15",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    dataAturanPakai = response.response_package.response_data;
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return dataAturanPakai;
        }

        function autoKategoriObat(obat) {
            var kategoriObat;
            $.ajax({
                url:__HOSTAPI__ + "/Inventori/kategori_per_obat/" + obat,
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    kategoriObat = response.response_package;
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return kategoriObat;
        }

        function checkPenjaminAvail(currentPenjamin, penjaminList, targetRow) {
            if(penjaminList.length > 0) {
                if(penjaminList.indexOf(currentPenjamin) > 0) {
                    //$("#resep_obat_" + targetRow).parent().find("div.penjamin-container").html("<b class=\"badge badge-success obat-penjamin-notifier\"><i class=\"fa fa-check-circle\" style=\"margin-right: 5px;\"></i> Ditanggung Penjamin</b>");
                } else {
                    //$("#resep_obat_" + targetRow).parent().find("div.penjamin-container").html("<b class=\"badge badge-danger obat-penjamin-notifier\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak Ditanggung Penjamin</b>");
                }
            } else {
                //$("#resep_obat_" + targetRow).parent().find("div.penjamin-container").html("<b class=\"badge badge-danger obat-penjamin-notifier\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak Ditanggung Penjamin</b>");
            }
        }

        $("#txt_racikan_takar_bulat").inputmask({
            alias: 'decimal',
            rightAlign: true,
            placeholder: "0.00",
            prefix: "",
            autoGroup: false,
            digitsOptional: true
        });

        function autoResep(setter = {
            "obat": "",
            "obat_detail": {},
            "aturan_pakai": 0,
            "keterangan": "",
            "signaKonsumsi": 0,
            "signaTakar": 0,
            "signaHari": 0,
            "pasien_penjamin_uid": "",
            "satuan_pemakaian": ""
        }) {
            $("#table-resep tbody tr").removeClass("last-resep");
            var newRowResep = document.createElement("TR");
            $(newRowResep).addClass("last-resep");
            var newCellResepID = document.createElement("TD");
            var newCellResepObat = document.createElement("TD");
            var newCellResepJlh = document.createElement("TD");
            var newCellResepSatuan = document.createElement("TD");
            var newCellResepSigna1 = document.createElement("TD");
            var newCellResepSigna2 = document.createElement("TD");
            var newCellResepSigna3 = document.createElement("TD");
            var newCellResepPenjamin = document.createElement("TD");
            var newCellResepAksi = document.createElement("TD");

            var newObat = document.createElement("SELECT");
            $(newCellResepObat).append(newObat);

            $(newCellResepObat).append(
                "<div class=\"row\" style=\"padding-top: 5px;\">" +
                "<div class=\"col-md-12\"><br /></div>" +
                "<div style=\"position: relative\" class=\"col-md-12 penjamin-container text-right\"></div>" +
                "<!--div class=\"col-md-7 aturan-pakai-container\"><span>Aturan Pakai</span></div-->" +
                "<div class=\"col-md-12 kategori-obat-container\"><!--span>Kategori Obat</span><br /--></div>" +
                "<div class=\"col-md-12\"><br /></div>" +
                "<div class=\"col-md-6 iterasi-container\"><span>Iterasi</span><br /></div><div class=\"col-md-6 satuan-pemakaian-container\"><span>Satuan Pemakaian</span><br /></div>" +
                "<div class=\"col-md-12\"><br /></div>" +
                "<div style=\"position: relative; padding-top: 5px;\" class=\"col-md-12 keterangan-container\"></div>" +
                "</div>");
            var newAturanPakai = document.createElement("SELECT");
            var dataAturanPakai = autoAturanPakai();
            $(newCellResepObat).find("div.aturan-pakai-container").append(newAturanPakai);

            $(newAturanPakai).addClass("form-control aturan-pakai-resep");
            $(newAturanPakai).append("<option value=\"none\">Pilih Aturan Pakai</option>").select2();
            for(var aturanPakaiKey in dataAturanPakai) {
                $(newAturanPakai).append("<option " + ((dataAturanPakai[aturanPakaiKey].id == setter.aturan_pakai) ? "selected=\"selected\"" : "") + " value=\"" + dataAturanPakai[aturanPakaiKey].id + "\">" + dataAturanPakai[aturanPakaiKey].nama + "</option>")
            }

            var newSatuanPemakaian = document.createElement("INPUT");
            $(newSatuanPemakaian).addClass("form-control resep-satuan-pemakaian").val(setter.satuan_pemakaian).attr({
                "placeholder": "Ex : PULV, PUFF"
            });
            $(newCellResepObat).find("div.satuan-pemakaian-container").append(newSatuanPemakaian);

            var newIterasi = document.createElement("INPUT");
            $(newIterasi).inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            }).addClass("form-control resep-iterasi").attr({
                "placeholder": "0"
            }).val((setter.iterasi == 0) ? "" : setter.iterasi);
            $(newCellResepObat).find("div.iterasi-container").append(newIterasi);


            var keteranganPerObat = document.createElement("TEXTAREA");
            $(newCellResepObat).find("div.keterangan-container").append("<span>Keterangan / Aturan Pemakaian</span>").append(keteranganPerObat);
            $(keteranganPerObat).addClass("form-control").attr({
                "placeholder": "Keterangan per Obat"
            }).val(setter.keterangan);

            var itemData = [];
            var parsedItemData = [];
            var obatNavigator = [];
            for(var dataKey in itemData) {
                var penjaminList = [];
                var penjaminListData = itemData[dataKey].penjamin;
                for(var penjaminKey in penjaminListData) {
                    if(penjaminList.indexOf(penjaminListData[penjaminKey].penjamin.uid) < 0) {
                        penjaminList.push(penjaminListData[penjaminKey].penjamin.uid);
                    }
                }

                obatNavigator.push(itemData[dataKey].uid);
                parsedItemData.push({
                    id: itemData[dataKey].uid,
                    "penjamin-list": penjaminList,
                    "satuan-caption": (itemData[dataKey].satuan_terkecil !== null) ? itemData[dataKey].satuan_terkecil.nama : "",
                    "satuan-terkecil": (itemData[dataKey].satuan_terkecil !== null) ? itemData[dataKey].satuan_terkecil.uid : "",
                    text: "<div style=\"color:" + ((itemData[dataKey].stok > 0) ? "#12a500" : "#cf0000") + ";\">" + itemData[dataKey].nama.toUpperCase() + "</div>",
                    html: 	"<div class=\"select2_item_stock\">" +
                        "<div style=\"color:" + ((itemData[dataKey].stok > 0) ? "#12a500" : "#cf0000") + "\">" + itemData[dataKey].nama.toUpperCase() + "</div>" +
                        "<div>" + itemData[dataKey].stok + "</div>" +
                        "</div>",
                    title: itemData[dataKey].nama
                });
            }

            $(newCellResepSatuan).html((setter.obat_detail !== undefined && setter.obat_detail.satuan_terkecil_info !== undefined) ? setter.obat_detail.satuan_terkecil_info.nama : "");

            $(newObat).addClass("form-control resep-obat").select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function(){
                        return "Barang tidak ditemukan";
                    }
                },
                ajax: {
                    dataType: "json",
                    headers:{
                        "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                        "Content-Type" : "application/json",
                    },
                    url:__HOSTAPI__ + "/Inventori/get_item_select2",
                    type: "GET",
                    data: function (term) {
                        return {
                            search:term.term
                        };
                    },
                    cache: true,
                    processResults: function (response) {
                        var data = response.response_package.response_data;
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    "id": item.uid,
                                    "satuan_terkecil": item.satuan_terkecil.nama,
                                    "data-value": item["data-value"],
                                    "penjamin-list": item["penjamin"],
                                    "satuan-caption": item["satuan-caption"],
                                    "satuan-terkecil": item["satuan-terkecil"],
                                    "text": "<div style=\"color:" + ((item.stok > 0) ? "#12a500" : "#cf0000") + ";\">" + item.nama.toUpperCase() + "</div>",
                                    "html": 	"<div class=\"select2_item_stock\">" +
                                        "<div style=\"color:" + ((item.stok > 0) ? "#12a500" : "#cf0000") + "\">" + item.nama.toUpperCase() + "</div>" +
                                        "<div>" + item.stok + "</div>" +
                                        "</div>",
                                    "title": item.nama
                                }
                            })
                        };
                    }
                },
                placeholder: "Pilih Obat",
                selectOnClose: true,
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    return data.html;
                },
                templateSelection: function(data) {
                    return data.text;
                }
            }).on("select2:select", function(e) {
                var data = e.params.data;
                var identifier = $(this).attr("id").split("_");
                identifier = identifier[identifier.length - 1];


                $(this).children("[value=\""+ data["id"] + "\"]").attr({
                    "data-value": data["data-value"],
                    "penjamin-list": data["penjamin-list"],
                    "satuan-caption": data["satuan-caption"],
                    "satuan-terkecil": data["satuan-terkecil"]
                });

                checkGenerateResep(data["id"]);

                //============KATEGORI OBAT

                if(setter.obat !== "") {
                    if($(newObat).val() != "none") {
                        var dataKategoriPerObat = autoKategoriObat(setter.obat);
                        var kategoriObatDOM = "";
                        if(dataKategoriPerObat.length > 0) {
                            for(var kategoriObatKey in dataKategoriPerObat) {
                                if(
                                    dataKategoriPerObat[kategoriObatKey].kategori !== undefined &&
                                    dataKategoriPerObat[kategoriObatKey].kategori !== null
                                ) {
                                    kategoriObatDOM += "<span class=\"badge badge-info badge-custom-caption resep-kategori-obat\">" + dataKategoriPerObat[kategoriObatKey].kategori.nama + "</span>";
                                }
                            }
                            $(newCellResepObat).find("div.kategori-obat-container").append(kategoriObatDOM);
                        }
                    }

                    var penjaminAvailable = [];
                    if(data["penjamin-list"] !== undefined) {
                        var penjaminString = data["penjamin-list"] + "";
                        penjaminAvailable = penjaminString.split(",");
                    }

                    if(penjaminAvailable.length > 0) {
                        if(penjaminAvailable.indexOf(setter.pasien_penjamin_uid) > 0) {
                            //$(newCellResepObat).find("div.penjamin-container").html("<b class=\"badge badge-success obat-penjamin-notifier\"><i class=\"fa fa-check-circle\" style=\"margin-right: 5px;\"></i> Ditanggung Penjamin</b>");
                        } else {
                            //$(newCellResepObat).find("div.penjamin-container").html("<b class=\"badge badge-danger obat-penjamin-notifier\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak Ditanggung Penjamin</b>");
                        }
                    } else {
                        //$(newCellResepObat).find("div.penjamin-container").html("<b class=\"badge badge-danger obat-penjamin-notifier\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak Ditanggung Penjamin</b>");
                    }
                }
                $("#resep_satuan_" + identifier).html(data.satuan_terkecil);
            });

            if(setter.obat != "") {
                $(newObat).append("<option title=\"" + setter.obat_detail.nama + "\" value=\"" + setter.obat + "\" penjamin-list=\"" + setter.obat_detail.penjamin.join(",") + "\">" + setter.obat_detail.nama + "</option>");
                $(newObat).select2("data", {id: setter.obat, text: setter.obat_detail.nama});
                $(newObat).trigger("change");

                if($(newObat).val() != "none") {
                    var dataKategoriPerObat = autoKategoriObat(setter.obat);
                    var kategoriObatDOM = "";
                    if(dataKategoriPerObat.length > 0) {
                        for(var kategoriObatKey in dataKategoriPerObat) {
                            if(
                                dataKategoriPerObat[kategoriObatKey].kategori !== undefined&&
                                dataKategoriPerObat[kategoriObatKey].kategori !== null
                            ) {
                                kategoriObatDOM += "<span class=\"badge badge-info badge-custom-caption resep-kategori-obat\">" + dataKategoriPerObat[kategoriObatKey].kategori.nama + "</span>";
                            }
                        }
                        $(newCellResepObat).find("div.kategori-obat-container").append(kategoriObatDOM);
                    }
                }

                /*var penjaminAvailable = [];
                if(data["penjamin-list"] !== undefined) {
                    var penjaminString = data["penjamin-list"] + "";
                    penjaminAvailable = penjaminString.split(",");
                }

                if(penjaminAvailable.length > 0) {
                    if(penjaminAvailable.indexOf(setter.pasien_penjamin_uid) > 0) {
                        $(newCellResepObat).find("div.penjamin-container").html("<b class=\"badge badge-success obat-penjamin-notifier\"><i class=\"fa fa-check-circle\" style=\"margin-right: 5px;\"></i> Ditanggung Penjamin</b>");
                    } else {
                        $(newCellResepObat).find("div.penjamin-container").html("<b class=\"badge badge-danger obat-penjamin-notifier\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak Ditanggung Penjamin</b>");
                    }
                } else {
                    $(newCellResepObat).find("div.penjamin-container").html("<b class=\"badge badge-danger obat-penjamin-notifier\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak Ditanggung Penjamin</b>");
                }*/

                //$(newCellResepSatuan).html(data["satuan-caption"]);
            }



            var newJumlah = document.createElement("INPUT");
            $(newCellResepJlh).append(newJumlah);
            $(newJumlah).addClass("form-control resep_jlh_hari").inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            }).attr({
                "placeholder": "0"
            }).val((setter.signaHari == 0) ? "" : setter.signaHari);

            var newKonsumsi = document.createElement("INPUT");
            $(newCellResepSigna1).append(newKonsumsi);
            $(newKonsumsi).addClass("form-control resep_konsumsi text-right").attr({
                "placeholder": "0"
            })/*.inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            })*/.val((setter.signaKonsumsi == 0) ? "" : setter.signaKonsumsi);

            $(newCellResepSigna2).html("<i class=\"fa fa-times signa-sign\"></i>");

            var newTakar = document.createElement("INPUT");
            $(newCellResepSigna3).append(newTakar);
            $(newTakar).addClass("form-control resep_takar text-right").attr({
                "placeholder": "0"
            })/*.inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            })*/.val((setter.signaTakar == 0) ? "" : setter.signaTakar);


            var newDeleteResep = document.createElement("BUTTON");
            $(newCellResepAksi).append(newDeleteResep);
            $(newDeleteResep).addClass("btn btn-sm btn-danger resep_delete").html("<i class=\"fa fa-ban\"></i>");

            $(newRowResep).append(newCellResepID);
            $(newRowResep).append(newCellResepObat);
            $(newRowResep).append(newCellResepSigna1);
            $(newRowResep).append(newCellResepSigna2);
            $(newRowResep).append(newCellResepSigna3);
            $(newRowResep).append(newCellResepJlh);
            $(newRowResep).append(newCellResepSatuan);
            $(newRowResep).append(newCellResepAksi);
            $("#table-resep").append(newRowResep);

            rebaseResep();
        }

        function rebaseResep() {
            $("#table-resep tbody tr").each(function(e) {
                var id = (e + 1);

                $(this).attr({
                    "id": "resep_row_" + id
                });

                $(this).find("td:eq(0)").html("<h5 class=\"autonum\">" + id + "</h5>");

                $(this).find("td:eq(1) select.resep-obat").attr({
                    "id": "resep_obat_" + id
                });

                $(this).find("td:eq(1) select:eq(1)").attr({
                    "id": "resep_aturan_pakai_" + id
                });

                $(this).find("td:eq(1) input.resep-iterasi").attr({
                    "id": "resep_iterasi_" + id
                });

                $(this).find("td:eq(1) input.resep-satuan-pemakaian").attr({
                    "id": "resep_satuan_pemakaian_" + id
                });

                //load_product_resep($(this).find("td:eq(1) select.resep-obat"), "");
                if($(this).find("td:eq(1) select.resep-obat").val() != "none") {
                    /*var penjaminAvailable = $(this).find("td:eq(1) select option:selected").attr("penjamin-list").split(",");
                    checkPenjaminAvail(pasien_penjamin_uid, penjaminAvailable, id);*/
                }

                $(this).find("td:eq(2) input:eq(0)").attr({
                    "id": "resep_signa_konsumsi_" + id
                });
                $(this).find("td:eq(4) input:eq(0)").attr({
                    "id": "resep_signa_takar_" + id
                });
                $(this).find("td:eq(5) input").attr({
                    "id": "resep_jlh_hari_" + id
                });
                $(this).find("td:eq(6)").attr({
                    "id": "resep_satuan_" + id
                });
                $(this).find("td:eq(7) button").attr({
                    "id": "resep_delete_" + id
                });
            });
        }

        function checkGenerateRacikan(id = 0) {
            if($(".last-racikan").length == 0) {
                autoRacikan();
            } else {
                var obat = $("#racikan_nama_" + id).val();
                var komposisi = $("#komposisi_" + id + " tbody tr").length;
                var jlh_obat = $("#racikan_jumlah_" + id).inputmask("unmaskedvalue");
                var signa_konsumsi = $("#racikan_signaA_" + id).inputmask("unmaskedvalue");
                var signa_hari = $("#racikan_signaB_" + id).inputmask("unmaskedvalue");
                var aturanPakai = $("#aturan_pakai_racikan_" + id).val();

                if(
                    parseFloat(jlh_obat) > 0 &&
                    parseFloat(signa_konsumsi) > 0 &&
                    parseFloat(signa_hari) > 0 &&
                    $("#row_racikan_" + id).hasClass("last-racikan") &&
                    //aturanPakai !== "none" &&
                    komposisi > 0
                ) {
                    if(obat === "") {
                        $("#racikan_nama_" + id).val("Racikan " + id);
                    }
                    autoRacikan();
                } else {
                    if(aturanPakai === "none") {
                        //notify_manual("info", "<i class=\"fa fa-info-circle\"></i> Aturan pakai harus diisi", 1000, "aturan_pakai_racikan_" + id, "#aturan_pakai_racikan_" + id);
                    }

                    if(komposisi === 0) {
                        notify_manual("info", "<i class=\"fa fa-info-circle\"></i> Komposisi racikan belum diisi", 1000, "komposisi_" + id, "#komposisi_" + id);
                    }

                    if(signa_hari === 0 || signa_konsumsi === 0) {
                        notify_manual("info", "<i class=\"fa fa-info-circle\"></i> Signa belum diisi", 1000, "racikan_signaA_" + id, "#racikan_signaA_" + id, "top");
                    }
                }
            }
        }

        function autoRacikan(setter = {
            "nama": "",
            "keterangan": "",
            "signaKonsumsi": "",
            "signaTakar": "",
            "signaHari": "",
            "aturan_pakai": "",
            "iterasi": 0,
            "item":[]
        }) {
            $("#table-resep-racikan tbody.racikan tr").removeClass("last-racikan");
            var newRacikanRow = document.createElement("TR");
            $(newRacikanRow).addClass("last-racikan racikan-master");

            var newRacikanCellID = document.createElement("TD");
            var newRacikanCellNama = document.createElement("TD");
            var newRacikanCellSignaA = document.createElement("TD");
            var newRacikanCellSignaX = document.createElement("TD");
            var newRacikanCellSignaB = document.createElement("TD");
            var newRacikanCellJlh = document.createElement("TD");
            var newRacikanCellAksi = document.createElement("TD");

            $(newRacikanCellID).addClass("master-racikan-cell");
            $(newRacikanCellNama).addClass("master-racikan-cell");
            $(newRacikanCellSignaA).addClass("master-racikan-cell");
            $(newRacikanCellSignaX).addClass("master-racikan-cell");
            $(newRacikanCellSignaB).addClass("master-racikan-cell");
            $(newRacikanCellJlh).addClass("master-racikan-cell");
            $(newRacikanCellAksi).addClass("master-racikan-cell");

            var newRacikanNama = document.createElement("INPUT");
            $(newRacikanCellNama).append(newRacikanNama);
            $(newRacikanNama).addClass("form-control nama_racikan").css({
                "margin-bottom": "20px"
            }).attr({
                "placeholder": "Nama Racikan"
            }).val(setter.nama);

            $(newRacikanCellNama).append(
                "<h6 style=\"padding-bottom: 10px;\">" +
                "Komposisi:" +
                "<button style=\"margin-left: 20px;\" class=\"btn btn-sm btn-info tambahKomposisi\"" +
                "<i class=\"fa fa-plus\"></i> Tambah" +
                "</button>" +
                "</h6>" +
                "<table class=\"table table-bordered komposisi-racikan largeDataType\" style=\"margin-top: 10px;\">" +
                "<thead class=\"thead-dark\">" +
                "<tr>" +
                "<th class=\"wrap_content\">No</th>" +
                "<th>Obat</th>" +
                /*"<th class=\"wrap_content\">@</th>" +*/
                /*"<th>Takaran</th>" +*/
                "<th>Kekuatan</th>" +
                "<th class=\"wrap_content\">Aksi</th>" +
                "<tr>" +
                "</thead>" +
                "<tbody class=\"komposisi-item\"></tbody>" +
                "</table>"
            );

            var newAturanPakaiRacikan = document.createElement("SELECT");

            var dataAturanPakai = autoAturanPakai();

            $(newAturanPakaiRacikan).addClass("form-control aturan-pakai-racikan");



            var newKeteranganRacikan = document.createElement("TEXTAREA");
            //$(newRacikanCellNama).append("<br /><span>Aturan Pakai</span>").append(newAturanPakaiRacikan).append("<br /><br /><span>Keterangan</span>").append(newKeteranganRacikan);
            var satuanKonsumsi = document.createElement("INPUT");
            $(satuanKonsumsi).addClass("form-control satuan_konsumsi").attr({
                "placeholder": "Ex : PULV, PUFF"
            });
            $(newRacikanCellNama).append("<br /><span>Satuan Pemakaian</span>").append(satuanKonsumsi).append("<br /><br /><span>Keterangan / Aturan Pakai</span>").append(newKeteranganRacikan);

            $(newAturanPakaiRacikan).append("<option value=\"none\">Pilih Aturan Pakai</option>").select2();
            for(var aturanPakaiKey in dataAturanPakai) {
                $(newAturanPakaiRacikan).append("<option " + ((dataAturanPakai[aturanPakaiKey].id == setter.aturan_pakai) ? "selected=\"selected\"" : "") + " value=\"" + dataAturanPakai[aturanPakaiKey].id + "\">" + dataAturanPakai[aturanPakaiKey].nama + "</option>")
            }
            $(newKeteranganRacikan).addClass("form-control").attr({
                "placeholder": "Keterangan racikan"
            }).val(setter.keterangan);

            var newIterasiRacikan = document.createElement("INPUT");
            $(newIterasiRacikan).inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            }).addClass("form-control racikan_iterasi").attr({
                "placeholder": "0"
            }).val((parseInt(setter.iterasi) === 0) ? "" : parseInt(setter.iterasi));

            $(newRacikanCellNama).append("<br /><span>Iterasi</span>").append(newIterasiRacikan);



            /*var newRacikanObat = document.createElement("SELECT");
            var newObatTakar = document.createElement("INPUT");
            $(newRacikanCellObat).append(newRacikanObat);
            var addAnother = load_product_resep(newRacikanObat, "");
            $(newRacikanCellObat).append("<br /><b>Takaran</b>");
            $(newRacikanCellObat).append(newObatTakar);
            $(newRacikanObat).addClass("form-control").select2();
            $(newObatTakar).addClass("form-control");*/

            var newRacikanSignaA = document.createElement("INPUT");
            $(newRacikanCellSignaA).append(newRacikanSignaA);
            $(newRacikanSignaA).addClass("form-control racikan_signa_a").attr({
                "placeholder": "0"
            }).val(setter.signaKonsumsi)/*.inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            })*/;

            $(newRacikanCellSignaX).html("<i class=\"fa fa-times signa-sign\"></i>");

            var newRacikanSignaB = document.createElement("INPUT");
            $(newRacikanCellSignaB).append(newRacikanSignaB);
            $(newRacikanSignaB).addClass("form-control racikan_signa_b").attr({
                "placeholder": "0"
            }).val(setter.signaTakar)/*.inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            })*/;

            var newRacikanJlh = document.createElement("INPUT");
            $(newRacikanCellJlh).append(newRacikanJlh);
            $(newRacikanJlh).addClass("form-control racikan_signa_jlh").attr({
                "placeholder": "0"
            }).val(setter.signaHari).inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            });

            var newRacikanDelete = document.createElement("BUTTON");
            $(newRacikanCellAksi).append(newRacikanDelete);
            $(newRacikanDelete).addClass("btn btn-danger btn-sm btn-delete-racikan").html("<i class=\"fa fa-ban\"></i>");

            $(newRacikanRow).append(newRacikanCellID);
            $(newRacikanRow).append(newRacikanCellNama);
            $(newRacikanRow).append(newRacikanCellSignaA);
            $(newRacikanRow).append(newRacikanCellSignaX);
            $(newRacikanRow).append(newRacikanCellSignaB);
            $(newRacikanRow).append(newRacikanCellJlh);
            $(newRacikanRow).append(newRacikanCellAksi);

            $("#table-resep-racikan tbody.racikan").append(newRacikanRow);
            rebaseRacikan();
        }

        function rebaseRacikan() {
            $("#table-resep-racikan > tbody.racikan > tr").each(function(e) {
                var id = (e + 1);

                $(this).attr({
                    "id": "row_racikan_" + id
                });

                $(this).find("td:eq(0)").html("<h5 class=\"autonum\">" + id + "</h5>");

                $(this).find("td:eq(1) input.nama_racikan").attr({
                    "id": "racikan_nama_" + id
                }).val("Racikan " + id);

                if($(this).find("td:eq(1) input.nama_racikan") == "") {
                    $(this).find("td:eq(1) input.nama_racikan").val("RACIKAN " + id);
                }

                $(this).find("td:eq(1) table").attr({
                    "id": "komposisi_" + id
                });

                $(this).find("td:eq(1) button.tambahKomposisi").attr({
                    "id": "tambah_komposisi_" + id
                });

                $(this).find("td:eq(1) select.aturan-pakai-racikan").attr({
                    "id": "aturan_pakai_racikan_" + id
                });

                $(this).find("td:eq(1) input.racikan_iterasi").attr({
                    "id": "iterasi_racikan_" + id
                });

                $(this).find("td:eq(2) input").attr({
                    "id": "racikan_signaA_" + id
                });

                $(this).find("td:eq(4) input").attr({
                    "id": "racikan_signaB_" + id
                });

                $(this).find("td:eq(5) input").attr({
                    "id": "racikan_jumlah_" + id
                });

                $(this).find("td:eq(6) button").attr({
                    "id": "racikan_delete_" + id
                });
            });
        }


        function autoKomposisi(id, setter = {}) {
            if(setter.obat != undefined || $("#komposisi_" + id + " tbody tr").length == 0 || $("#komposisi_" + id + " tbody tr:last-child td:eq(1)").html() != "") {
                var newKomposisiRow = document.createElement("TR");
                $(newKomposisiRow).addClass("komposisi-row");

                var newKomposisiCellID = document.createElement("TD");
                var newKomposisiCellObat = document.createElement("TD");
                //\var newKomposisiCellJumlah = document.createElement("TD");
                var newKomposisiCellSatuan = document.createElement("TD");
                var newKomposisiCellAksi = document.createElement("TD");



                var newKomposisiEdit = document.createElement("BUTTON");
                $(newKomposisiEdit).addClass("btn btn-sm btn-info btn_edit_komposisi").html("<i class=\"fa fa-pencil-alt\"></i>");

                var newKomposisiDelete = document.createElement("BUTTON");
                $(newKomposisiDelete).addClass("btn btn-sm btn-danger btn_delete_komposisi").html("<i class=\"fa fa-ban\"></i>");

                $(newKomposisiCellAksi).append("<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\"></div>");
                $(newKomposisiCellAksi).find("div").append(newKomposisiEdit);
                $(newKomposisiCellAksi).find("div").append(newKomposisiDelete);

                $(newKomposisiRow).append(newKomposisiCellID);
                $(newKomposisiRow).append(newKomposisiCellObat);
                //$(newKomposisiRow).append(newKomposisiCellJumlah);
                $(newKomposisiRow).append(newKomposisiCellSatuan);
                $(newKomposisiRow).append(newKomposisiCellAksi);

                $("#komposisi_" + id + " tbody").append(newKomposisiRow);

                /*if($("#komposisi_" + id + " tbody tr").length == 1) {
                    //autoModal
                    prepareModal(id);
                }*/
                if(setter.obat != undefined) {
                    $(newKomposisiCellObat).attr({
                        "uid-obat" : setter.obat
                    }).html(setter.obat_detail.nama.toUpperCase());

                    //$(newKomposisiCellJumlah).html(setter.ratio);
                    $(newKomposisiCellSatuan).html(setter.kekuatan);
                } else {
                    prepareModal(id);
                }

                rebaseKomposisi(id);
            }
        }

        function rebaseKomposisi(id) {
            $("#komposisi_" + id + " tbody tr").each(function(e) {
                var cid = (e + 1);

                $(this).attr({
                    "id": "single_komposisi_" + cid
                });

                $(this).find("td:eq(0)").html(cid);
                $(this).find("td:eq(1)").attr({
                    "id": "obat_komposisi_" + id + "_" + cid
                });
                /*$(this).find("td:eq(2)").attr({
                    "id": "jlh_komposisi_" + id + "_" + cid
                });*/
                $(this).find("td:eq(2)").attr({
                    "id": "takar_komposisi_" + id + "_" + cid
                });
                $(this).find("td:eq(3) button:eq(0)").attr({
                    "id": "button_edit_komposisi_" + id + "_" + cid
                });

                $(this).find("td:eq(3) button:eq(1)").attr({
                    "id": "button_delete_komposisi_" + id + "_" + cid
                });
            });
        }

        function prepareModal(id, setData = {
            obat: "",
            jlh: "",
            takarBulat: 1,
            takarDesimal: "",
            kekuatan: ""
        }) {
            $("#form-editor-racikan").modal("show");
            $("#modal-large-title").html($("#racikan_nama_" + id).val());

            //$("#txt_racikan_jlh").val(setData.jlh);
            //$("#txt_racikan_takar").val(setData.takar);
            $("#txt_racikan_takar").val(setData.takarDesimal);
            $("#txt_racikan_takar_bulat").val(setData.takarBulat);
            $("#txt_racikan_kekuatan").val(setData.kekuatan);

            var modalProduct = load_product_resep("#txt_racikan_obat", setData.obat, false);
            var itemData = modalProduct.data;
            var parsedItemData = [];
            for(var dataKey in itemData) {
                var penjaminList = [];
                var penjaminListData = itemData[dataKey].penjamin;
                for(var penjaminKey in penjaminListData) {
                    if(penjaminList.indexOf(penjaminListData[penjaminKey].penjamin.uid) < 0) {
                        penjaminList.push(penjaminListData[penjaminKey].penjamin.uid);
                    }
                }


                parsedItemData.push({
                    id: itemData[dataKey].uid,
                    "penjamin-list": penjaminList,
                    "satuan-caption": (itemData[dataKey].satuan_terkecil != undefined) ? itemData[dataKey].satuan_terkecil.nama : "",
                    "satuan-terkecil": (itemData[dataKey].satuan_terkecil != undefined) ? itemData[dataKey].satuan_terkecil.uid : "",
                    text: "<div style=\"color:" + ((itemData[dataKey].stok > 0) ? "#12a500" : "#cf0000") + ";\">" + itemData[dataKey].nama.toUpperCase() + "</div>",
                    html: 	"<div class=\"select2_item_stock\">" +
                        "<div style=\"color:" + ((itemData[dataKey].stok > 0) ? "#12a500" : "#cf0000") + "\">" + itemData[dataKey].nama.toUpperCase() + "</div>" +
                        "<div>" + itemData[dataKey].stok + "</div>" +
                        "</div>",
                    title: itemData[dataKey].nama
                });
            }

            $("#txt_racikan_obat").addClass("form-control resep-obat").select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function(){
                        return "Barang tidak ditemukan";
                    }
                },
                ajax: {
                    dataType: "json",
                    headers:{
                        "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                        "Content-Type" : "application/json",
                    },
                    url:__HOSTAPI__ + "/Inventori/get_item_select2",
                    type: "GET",
                    data: function (term) {
                        return {
                            search:term.term
                        };
                    },
                    cache: true,
                    processResults: function (response) {
                        var data = response.response_package.response_data;
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    "id": item.uid,
                                    "satuan_terkecil": item.satuan_terkecil.nama,
                                    "data-value": item["data-value"],
                                    "penjamin-list": item["penjamin"],
                                    "satuan-caption": item["satuan-caption"],
                                    "satuan-terkecil": item["satuan-terkecil"],
                                    "text": "<div style=\"color:" + ((item.stok > 0) ? "#12a500" : "#cf0000") + ";\">" + item.nama.toUpperCase() + "</div>",
                                    "html": 	"<div class=\"select2_item_stock\">" +
                                        "<div style=\"color:" + ((item.stok > 0) ? "#12a500" : "#cf0000") + "\">" + item.nama.toUpperCase() + "</div>" +
                                        "<div>" + item.stok + "</div>" +
                                        "</div>",
                                    "title": item.nama
                                }
                            })
                        };
                    }
                },
                selectOnClose: true,
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    return data.html;
                },
                templateSelection: function(data) {
                    return data.text;
                }
            }).val(setData.obat).trigger("change").on("select2:select", function(e) {
                var data = e.params.data;
                $(this).children("[value=\""+ data["id"] + "\"]").attr({
                    "data-value": data["data-value"],
                    "penjamin-list": data["penjamin-list"],
                    "satuan-caption": data["satuan-caption"],
                    "satuan-terkecil": data["satuan-terkecil"]
                });
            });

            if(setData.obat != "") {
                $("#txt_racikan_obat").append("<option title=\"" + setData.obat_nama + "\" value=\"" + setData.obat + "\">" + setData.obat_nama + "</option>");
                $("#txt_racikan_obat").select2("data", {id: setData.obat, text: setData.obat_nama});
                $("#txt_racikan_obat").trigger("change");
            }
        }

        $("#txt_racikan_obat").select2();
        /*$("#txt_racikan_jlh").inputmask({
            alias: 'decimal',
            rightAlign: true,
            placeholder: "0.00",
            prefix: "",
            autoGroup: false,
            digitsOptional: true
        });*/

        var currentRacikID = 1;
        var currentKomposisiID = $("#komposisi_" + currentRacikID + " tbody tr").length;
        var komposisiMode = "add";

        $("body").on("click", ".btn_edit_komposisi", function() {
            var id = $(this).attr("id").split("_");
            var thisID = id[id.length - 1];
            var Pid = id[id.length - 2];


            prepareModal(Pid, {
                obat: $("#obat_komposisi_" + Pid + "_" + thisID).attr("uid-obat"),
                obat_nama: $("#obat_komposisi_" + Pid + "_" + thisID).text(),
                takarBulat: $("#takar_komposisi_" + Pid + "_" + thisID).find("b").html(),
                takarDesimal: $("#takar_komposisi_" + Pid + "_" + thisID).find("sub").html(),
                kekuatan: $("#takar_komposisi_" + Pid + "_" + thisID).find("h6").html()
            });

            currentKomposisiID = thisID;
            currentRacikID = Pid;
        });

        $("body").on("click", ".btn-delete-racikan", function() {
            var id = $(this).attr("id").split("_");
            var thisID = id[id.length - 1];
            $("#row_racikan_" + thisID).remove();
            rebaseRacikan();
        });

        $("body").on("click", ".btn_delete_komposisi", function(){
            var id = $(this).attr("id").split("_");
            var thisID = id[id.length - 1];
            var Pid = id[id.length - 2];

            $("#single_komposisi_" + thisID).remove();
            rebaseKomposisi(Pid);
            return false;
        });

        $("body").on("click", ".tambahKomposisi", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            currentRacikID = id;
            currentKomposisiID = $("#komposisi_" + currentRacikID + " tbody tr").length + 1;

            autoKomposisi(id);
        });

        $("body").on("click", "#btnSubmitKomposisi", function() {
            var infoPenjamin = "";
            if($("#txt_racikan_obat").find("option:selected").attr("penjamin-list") !== undefined) {
                var penjaminCheck = $("#txt_racikan_obat").find("option:selected").attr("penjamin-list").split(",");
                if(penjaminCheck.length > 0) {
                    if(penjaminCheck.indexOf(pasien_penjamin_uid) > 0) {
                        //infoPenjamin = "<b class=\"badge badge-success pull-rigth\"><i class=\"fa fa-check-circle\" style=\"margin-right: 5px;\"></i> Ditanggung Penjamin</b>";
                    } else {
                        //infoPenjamin = "<b class=\"badge badge-danger pull-rigth\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak Ditanggung Penjamin</b>";
                    }
                } else {
                    //infoPenjamin = "<b class=\"badge badge-danger pull-rigth\"><i class=\"fa fa-ban\" style=\"margin-right: 5px;\"></i> Tidak Ditanggung Penjamin</b>";
                }

                $("#obat_komposisi_" + currentRacikID + "_" + currentKomposisiID)
                    .html($("#txt_racikan_obat").find("option:selected").text() + infoPenjamin)
                    .attr({
                        "uid-obat": $("#txt_racikan_obat").val()
                    });
            }

            //$("#jlh_komposisi_" + currentRacikID + "_" + currentKomposisiID).html($("#txt_racikan_jlh").val());
            $("#takar_komposisi_" + currentRacikID + "_" + currentKomposisiID).html("<b style=\"font-size: 15pt; display: none\">" + $("#txt_racikan_takar_bulat").val() + "</b><sub nilaiExact=\"" + eval($("#txt_racikan_takar").val()) + "\">" + $("#txt_racikan_takar").val() + "</sub><h6>" + $("#txt_racikan_kekuatan").val() + "</h6>");
            //if($("#txt_racikan_jlh").val() != "" && $("#txt_racikan_takar").val()) {
            $("#form-editor-racikan").modal("hide");
            //}

            checkGenerateRacikan(currentRacikID);
        });

        $("body").on("keyup", ".racikan_signa_a", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            checkGenerateRacikan(id);
        });

        $("body").on("keyup", ".racikan_signa_b", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            checkGenerateRacikan(id);
        });

        $("body").on("keyup", ".racikan_signa_jlh", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            checkGenerateRacikan(id);
        });
        //===========================================================================
        $("body").on("keyup", ".resep_konsumsi", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            checkGenerateResep(id);
        });

        $("body").on("keyup", ".resep_takar", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            checkGenerateResep(id);
        });

        $("body").on("keyup", ".resep_jlh_hari", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            checkGenerateResep(id);
        });

        $("body").on("select2:select", ".resep-obat", function(e) {
            var data = e.params.data;
            $(this).children("[value=\""+ data["id"] + "\"]").attr({
                "data-value": data["data-value"],
                "penjamin-list": data["penjamin-list"],
                "satuan-caption": data["satuan-caption"],
                "satuan-terkecil": data["satuan-terkecil"]
            });

            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            checkGenerateResep(id);

            if($(this).val() != "none") {
                var dataKategoriPerObat = autoKategoriObat(data['id']);
                var kategoriObatDOM = "";
                if(dataKategoriPerObat.length > 0) {
                    for(var kategoriObatKey in dataKategoriPerObat) {
                        if(
                            dataKategoriPerObat[kategoriObatKey].kategori !== undefined &&
                            dataKategoriPerObat[kategoriObatKey].kategori !== null
                        ) {
                            kategoriObatDOM += "<span class=\"badge badge-info resep-kategori-obat\">" + dataKategoriPerObat[kategoriObatKey].kategori.nama + "</span>";
                        }
                    }
                }

                $("#resep_row_" + id).find("td:eq(1) div.kategori-obat-container").html("<span>Kategori Obat</span><br />" + kategoriObatDOM);

                var penjaminAvailable = ($(this).find("option:selected").attr("penjamin-list") !== undefined) ? $(this).find("option:selected").attr("penjamin-list").split(",") : [];
                checkPenjaminAvail(pasien_penjamin_uid, penjaminAvailable, id);

                var satuanCaption = $(this).find("option:selected").attr("satuan-caption");
                $("#resep_satuan_" + id).html(satuanCaption);
                rebaseResep();
            } else {
                $("#resep_obat_" + id).parent().find("div.penjamin-container").html("");
                $("#resep_satuan_" + id).html("");
                $("#resep_row_" + id).find("td:eq(1) div.kategori-obat-container").html("<span>Kategori Obat</span><br />");
            }
        });

        $("body").on("change", ".aturan-pakai-resep", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            checkGenerateResep(id);
        });


        $("body").on("change", ".aturan-pakai-racikan", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            checkGenerateRacikan(id);
        });



        $("body").on("click", ".resep_delete", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            if(!$("#resep_row_" + id).hasClass("last-resep")) {
                $("#resep_row_" + id).remove();
            }

            rebaseResep();
            //$("#table-resep tbody tr").each(function(e));
        });

        function populateAllData() {
            //PREPARE FOR SAVE DATA
            var keluhanUtamaData = metaSwitchEdit.txt_keluhan_utama.editor.getData();
            var keluhanTambahanData = metaSwitchEdit.txt_keluhan_tambahan.editor.getData();
            var tekananDarah = $("#txt_tanda_vital_td").val();
            var nadi = $("#txt_tanda_vital_n").val();
            var suhu = $("#txt_tanda_vital_s").val();
            var pernafasan = $("#txt_tanda_vital_rr").val();
            var beratBadan = $("#txt_berat_badan").val();
            var tinggiBadan = $("#txt_tinggi_badan").val();
            var lingkarLengan = $("#txt_lingkar_lengan").val();
            var pemeriksaanFisikData = metaSwitchEdit.txt_pemeriksaan_fisik.editor.getData();
            var icd10kerja = $("#txt_icd_10_kerja").val();
            var icd10Banding = $("#txt_icd_10_banding").val();
            var icd10KerjaData = metaSwitchEdit.txt_diagnosa_kerja.editor.getData();
            var icd10BandingData = metaSwitchEdit.txt_diagnosa_banding.editor.getData();
            var planningData = metaSwitchEdit.txt_planning.editor.getData();

            var tindakan = [];
            $("#table-tindakan tbody tr").each(function() {
                var tindakanItem = $(this).find("td:eq(1)").attr("set-tindakan");
                var pilihanPenjamin = $(this).find("td:eq(2) select").val();
                tindakan.push({
                    "item": tindakanItem,
                    "itemName": $(this).find("td:eq(1)").html(),
                    "penjamin": pilihanPenjamin,
                    "penjaminName": $(this).find("td:eq(2) select option:selected").text()
                });
            });

            var resep = [];
            $("#table-resep tbody tr").each(function() {
                var obat = $(this).find("td:eq(1) select").val();
                var signaKonsumsi = $(this).find("td:eq(2) input").inputmask("unmaskedvalue");
                var signaTakar = $(this).find("td:eq(4) input").inputmask("unmaskedvalue");
                var signaHari = $(this).find("td:eq(5) input").inputmask("unmaskedvalue");
                var penjamin = $(this).find("td:eq(6) select").val();

                resep.push({
                    "obat": obat,
                    "signaKonsumsi": signaKonsumsi,
                    "signaTakar": signaTakar,
                    "signaHari": signaHari,
                    "penjamin": penjamin
                });
            });

            var keteranganResep = metaSwitchEdit.txt_keterangan_resep.editor.getData();
            var keteranganRacikan = metaSwitchEdit.txt_keterangan_resep_racikan.editor.getData();
        }

        $("#txt_tanda_vital_td").inputmask({
            alias: 'decimal',
            rightAlign: true,
            placeholder: "0.00",
            prefix: "",
            autoGroup: false,
            digitsOptional: true
        });

        $("#txt_tanda_vital_n").inputmask({
            alias: 'decimal',
            rightAlign: true,
            placeholder: "0.00",
            prefix: "",
            autoGroup: false,
            digitsOptional: true
        });

        $("#txt_tanda_vital_s").inputmask({
            alias: 'decimal',
            rightAlign: true,
            placeholder: "0.00",
            prefix: "",
            autoGroup: false,
            digitsOptional: true
        });

        $("#txt_tanda_vital_rr").inputmask({
            alias: 'decimal',
            rightAlign: true,
            placeholder: "0.00",
            prefix: "",
            autoGroup: false,
            digitsOptional: true
        });

        $("#txt_berat_badan").inputmask({
            alias: 'decimal',
            rightAlign: true,
            placeholder: "0.00",
            prefix: "",
            autoGroup: false,
            digitsOptional: true
        });

        $("#txt_tinggi_badan").inputmask({
            alias: 'decimal',
            rightAlign: true,
            placeholder: "0.00",
            prefix: "",
            autoGroup: false,
            digitsOptional: true
        });

        $("#txt_lingkar_lengan").inputmask({
            alias: 'decimal',
            rightAlign: true,
            placeholder: "0.00",
            prefix: "",
            autoGroup: false,
            digitsOptional: true
        });

        $("#txt_terapis_frekuensi_bulan").inputmask({
            alias: 'decimal',
            rightAlign: true,
            placeholder: "0.00",
            prefix: "",
            autoGroup: false,
            digitsOptional: true
        });

        $("#txt_terapis_frekuensi_minggu").inputmask({
            alias: 'decimal',
            rightAlign: true,
            placeholder: "0.00",
            prefix: "",
            autoGroup: false,
            digitsOptional: true
        });

        $("input[type=\"radio\"][name=\"suspek_kerja\"]").change(function() {
            if($(this).val() == "y") {
                $("#suspek_kerja").removeAttr("disabled");
            } else {
                $("#suspek_kerja").attr("disabled", "disabled");
            }
        });






        //DOKUMEN
        var tableDokumen = $("#table-dokumen").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Dokumen",
                type: "POST",
                data: function(d) {
                    d.request = "get_dokumen_back_end";
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];
                    if(response == undefined || response.response_package == undefined) {
                        returnedData = [];
                    } else {
                        returnedData = response.response_package.response_data;
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return returnedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Nama Barang"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span id=\"nama_surat_" + row.uid + "\">" + row.nama + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button id=\"dokumen_create_" + row.uid + "\" class=\"btn btn-info btn-sm btn-create-dokumen\">" +
                            "<i class=\"fa fa-pencil-alt\"></i> Buat" +
                            "</button>" +
                            "</div>";
                    }
                }
            ]
        });

        var targettedDokumen = "";
        var targettedTemplate = "";
        var targettedNamaSurat = "";

        var parameterIdenList = [];

        $("body").on("click", ".btn-create-dokumen", function() {
            var dokumen = $(this).attr("id").split("_");
            dokumen = dokumen[dokumen.length - 1];

            targettedDokumen = dokumen;

            $("#target-judul-surat").html($("#nama_surat_" + dokumen).html());
            targettedNamaSurat = $("#nama_surat_" + dokumen).html();
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Dokumen/detail/" + dokumen,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "GET",
                success: function(response) {
                    var data = response.response_package.response_data[0];

                    $("#load-parameter-surat").html("");

                    targettedTemplate = data.template_iden;
                    parameterIdenList = data.parameter;
                    for(var key in data.parameter)
                    {
                        var newParam = document.createElement("INPUT");
                        var regexParam = data.parameter[key].param_iden;

                        $(newParam).addClass("form-control parameter_identifier_dokumen").attr({
                            "placeholder": regexParam.replace(/{{__|__}}/g, "")
                        }).val(data.parameter[key].default);

                        var simpleRegex = regexParam.replace(/{{__|__}}/g, "");
                        data.template_iden = data.template_iden.replace(`{{__${simpleRegex}__}}`, "<b id=\"dokumen_regex_" + simpleRegex.toLowerCase() + "\">{{__" + simpleRegex + "__}}</b>");

                        if(data.parameter[key].default !== "") {
                            data.template_iden = data.template_iden.replace(`{{__${simpleRegex}__}}`, data.parameter[key].default);
                            $(newParam).attr("disabled", "disabled");
                        } else {
                            if(data.parameter[key].param_iden === '{{__DOKTER__}}') {
                                data.template_iden = data.template_iden.replace(/{{__DOKTER__}}/g, __MY_NAME__);
                                $(newParam).val(__MY_NAME__);
                                $(newParam).attr("disabled", "disabled");
                            }

                            if(data.parameter[key].param_iden === '{{__NAMAPASIEN__}}') {
                                data.template_iden = data.template_iden.replace(/{{__NAMAPASIEN__}}/g, pasien_nama);
                                $(newParam).val(pasien_nama);
                                $(newParam).attr("disabled", "disabled");
                            }

                            if(data.parameter[key].param_iden === '{{__TTL__}}') {
                                data.template_iden = data.template_iden.replace(/{{__TTL__}}/g, pasien_tempat_lahir + ", " + pasien_tanggal_lahir);
                                $(newParam).val(pasien_tempat_lahir + ", " + pasien_tanggal_lahir);
                                $(newParam).attr("disabled", "disabled");
                            }

                            if(data.parameter[key].param_iden === '{{__UMUR__}}') {
                                data.template_iden = data.template_iden.replace(/{{__UMUR__}}/g, pasien_usia);
                                $(newParam).val(pasien_usia);
                                $(newParam).attr("disabled", "disabled");
                            }

                            if(data.parameter[key].param_iden === '{{__JENKEL__}}') {
                                data.template_iden = data.template_iden.replace(/{{__JENKEL__}}/g, pasien_jenkel);
                                $(newParam).val(pasien_jenkel);
                                $(newParam).attr("disabled", "disabled");
                            }

                            if(data.parameter[key].param_iden === '{{__ALAMAT__}}') {
                                data.template_iden = data.template_iden.replace(/{{__ALAMAT__}}/g, pasien_alamat);
                                $(newParam).val(pasien_alamat);
                            }

                            if(data.parameter[key].param_iden === '{{__PENJAMIN__}}') {
                                data.template_iden = data.template_iden.replace(/{{__PENJAMIN__}}/g, pasien_penjamin);
                                $(newParam).val(pasien_penjamin);
                                $(newParam).attr("disabled", "disabled");
                            }
                        }

                        $("#load-parameter-surat").append(newParam);
                    }
                    $("#dokumen-viewer").html(data.template_iden);

                    $("#dokumen-viewer table").each(function() {
                        var checkType = $(this).find("tr td").length;
                        if(checkType == 2) {
                            $(this).css({
                                "table-layout": "fixed"
                            });
                        }
                    });

                    $("#dokumen-viewer table:eq(0) tr td:eq(0)").css({
                        "width": "10%"
                    }).html("<img src=\"" + __HOST__ + "/client/template/assets/images/logo-icon.png\" />");

                    $("#dokumen-viewer table").addClass("table form-mode largeDataType");

                    $("#compose-surat").modal("show");
                },
                error: function(response) {
                    console.clear();
                    console.log(response);
                }
            });
        });

        $("body").on("keyup", ".parameter_identifier_dokumen", function() {
            var paramIden = $(this).attr("placeholder");
            var target_value = $(this).val();
            if(target_value !== "") {
                $("#dokumen_regex_" + paramIden.toLowerCase()).html(target_value);
            } else {
                $("#dokumen_regex_" + paramIden.toLowerCase()).html("{{__" + paramIden + "__}}");
            }
        });

        $("#btnCetakSurat").click(function() {
            Swal.fire({
                title: "Data sudah benar?",
                showDenyButton: true,
                confirmButtonText: "Ya. Cetak",
                denyButtonText: "Belum",
            }).then((result) => {
                if (result.isConfirmed) {
                    var kunjungan = antrianData.kunjungan;
                    var antrian = UID;
                    var penjamin = antrianData.penjamin;
                    var pasien = antrianData.pasien;
                    var poli = antrianData.departemen;

                    var parsedIden = [];
                    $(".parameter_identifier_dokumen").each(function() {
                        var identifier = "{{__" + $(this).attr("placeholder") + "__}}";
                        var iden_value = $(this).val();
                        parsedIden.push({
                            identifier: identifier,
                            iden_value: iden_value
                        });
                    });

                    $.ajax({
                        url:__HOSTAPI__ + "/Dokumen",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        data:{
                            request:'cetak_dokumen',
                            dokumen: targettedDokumen,
                            kunjungan: kunjungan,
                            antrian: antrian,
                            penjamin, penjamin,
                            pasien: pasien,
                            poli: poli,
                            nilai: parsedIden
                        },
                        type:"POST",
                        success:function(response) {
                            if(response.response_package.response_result > 0) {
                                $("#dokumen-viewer").printThis({
                                    importCSS: true,
                                    base: false,
                                    pageTitle: targettedNamaSurat,
                                    afterPrint: function() {
                                        $("#compose-surat").modal("hide");
                                        $("#load-parameter-surat").html("");
                                        $("#dokumen-viewer").html("");
                                    }
                                });
                            } else {

                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });

                } else if (result.isDenied) {
                    //Swal.fire('Changes are not saved', '', 'info')
                }
            });
        });






        function simpanAsesmen(
            antrianData, UID, editorKeluhanUtamaData, editorKeluhanTambahanData, editorPeriksaFisikData, editorTerapisAnamnesa, editorTerapisTataLaksana, editorTerapisEvaluasi,
            editorTerapisHasil, editorTerapisKesimpulan, editorTerapisRekomendasi, editorKerja, editorBanding, editorPlanning, editorKeteranganResep, editorKeteranganResepRacikan,
            metaSwitchEdit,
            charge_invoice = "N"
        ) {
            var savingResult;
            var kunjungan = antrianData.kunjungan;
            var antrian = UID;
            var penjamin = antrianData.penjamin;
            var pasien = antrianData.pasien;
            var poli = antrianData.departemen;

            //POLI FORM
            var keluhanUtamaData = (editorKeluhanUtamaData === undefined || editorKeluhanUtamaData === null) ? metaSwitchEdit.txt_keluhan_utama.data : editorKeluhanUtamaData.getData();
            var keluhanTambahanData = (editorKeluhanTambahanData === undefined || editorKeluhanTambahanData === null) ? metaSwitchEdit.txt_keluhan_tambahan.data : editorKeluhanTambahanData.getData();
            var tekananDarah = $("#txt_tanda_vital_td").inputmask("unmaskedvalue");
            var nadi = $("#txt_tanda_vital_n").inputmask("unmaskedvalue");
            var suhu = $("#txt_tanda_vital_s").inputmask("unmaskedvalue");
            var pernafasan = $("#txt_tanda_vital_rr").inputmask("unmaskedvalue");
            var beratBadan = $("#txt_berat_badan").inputmask("unmaskedvalue");
            var tinggiBadan = $("#txt_tinggi_badan").inputmask("unmaskedvalue");
            var lingkarLengan = $("#txt_lingkar_lengan").inputmask("unmaskedvalue");
            var pemeriksaanFisikData = (editorPeriksaFisikData === undefined || editorPeriksaFisikData === null) ? metaSwitchEdit.txt_pemeriksaan_fisik.data : editorPeriksaFisikData.getData();

            if(antrianData.poli_info !== null) {
                if(antrianData.poli_info.uid === __UIDFISIOTERAPI__) {
                    var terapisAnamnesa = editorTerapisAnamnesa.getData();
                    var terapisTataLaksana = editorTerapisTataLaksana.getData();
                    var terapisEvaluasi = editorTerapisEvaluasi.getData();
                    var terapisAnjuranBulan = $("#txt_terapis_frekuensi_bulan").inputmask("unmaskedvalue");
                    var terapisAnjuranMinggu = $("#txt_terapis_frekuensi_minggu").inputmask("unmaskedvalue");
                    var terapisSuspek = $("#suspek_kerja").val();
                    var terapisHasil = editorTerapisHasil.getData();
                    var terapisKesimpulan = editorTerapisKesimpulan.getData();
                    var terapisRekomendasi = editorTerapisRekomendasi.getData();
                }
            }


            /*var icd10Kerja = $("#txt_icd_10_kerja").val();
            var icd10Banding = $("#txt_icd_10_banding").val();*/

            var diagnosaKerjaData = (editorKerja === undefined || editorKerja === null) ? metaSwitchEdit.txt_diagnosa_kerja.data : editorKerja.getData();
            var diagnosaBandingData = (editorBanding === undefined || editorBanding === null) ? metaSwitchEdit.txt_diagnosa_banding.data : editorBanding.getData();
            var planningData = (editorPlanning === undefined || editorPlanning === null) ? metaSwitchEdit.txt_planning.data : editorPlanning.getData();

            var tindakan = [];
            $("#table-tindakan tbody tr").each(function() {
                var tindakanItem = $(this).find("td:eq(1)").attr("set-tindakan");
                var pilihanPenjamin = $(this).find("td:eq(2) select").val();
                tindakan.push({
                    "kunjungan": kunjungan,
                    "antrian": antrian,
                    "pasien": pasien,
                    "kelas": $(this).find("td:eq(1)").attr("kelas"),
                    "poli": poli,
                    "item": tindakanItem,
                    "itemName": $(this).find("td:eq(1)").html(),
                    "penjamin": pilihanPenjamin,
                    "penjaminName": $(this).find("td:eq(2) select option:selected").text()
                });
            });

            var resep = [];
            $("#table-resep tbody tr").each(function() {
                var obat = $(this).find("td:eq(1) select.resep-obat").val();
                var aturanPakai = $(this).find("td:eq(1) select.aturan-pakai-resep").val();
                var keteranganPerObat = $(this).find("td:eq(1) textarea").val();
                var iterasi = $(this).find("td:eq(1) input.resep-iterasi").inputmask("unmaskedvalue");
                var satuanPemakaian = $(this).find("td:eq(1) input.resep-satuan-pemakaian").val();
                /*var signaKonsumsi = $(this).find("td:eq(2) input").inputmask("unmaskedvalue");
                var signaTakar = $(this).find("td:eq(4) input").inputmask("unmaskedvalue");*/
                var signaKonsumsi = $(this).find("td:eq(2) input").val();
                var signaTakar = $(this).find("td:eq(4) input").val();
                var signaHari = $(this).find("td:eq(5) input").inputmask("unmaskedvalue");
                //var penjamin = $(this).find("td:eq(6) select").val();
                if(
                    obat !== undefined &&
                    obat !== "none" &&
                    obat !== "" &&

                    /*parseFloat(signaKonsumsi) > 0 &&
                    parseFloat(signaTakar) > 0 &&*/
                    parseFloat(signaKonsumsi) !== "" &&
                    parseFloat(signaTakar) !== "" &&
                    parseFloat(signaHari) > 0

                ) {
                    resep.push({
                        "obat": obat,
                        "aturanPakai": parseInt(aturanPakai),
                        "keteranganPerObat": keteranganPerObat,
                        "signaKonsumsi": signaKonsumsi,
                        "signaTakar": signaTakar,
                        "signaHari": signaHari,
                        "iterasi": iterasi,
                        "satuanPemakaian": satuanPemakaian
                    });
                }
            });

            var keteranganResep = (editorKeteranganResep === undefined || editorKeteranganResep === null) ? metaSwitchEdit.txt_keterangan_resep.data : editorKeteranganResep.getData();
            var keteranganRacikan = (editorKeteranganResepRacikan === undefined || editorKeteranganResepRacikan === null) ? metaSwitchEdit.txt_keterangan_resep_racikan.data : editorKeteranganResepRacikan.getData();

            var racikan = [];
            $("#resep-racikan tbody.racikan tr.racikan-master").each(function() {
                var masterRacikanRow = $(this);
                var dataRacikan = {
                    "nama": "",
                    "item": [],
                    "keterangan": "",
                    "satuan_konsumsi": "",
                    "iterasi": 0,
                    "signaKonsumsi": 0,
                    "signaTakar": 0,
                    "signaHari": 0,
                    "aturanPakai": 0
                };

                dataRacikan.nama = masterRacikanRow.find("td.master-racikan-cell:eq(1) input.nama_racikan").val();
                dataRacikan.aturanPakai = (masterRacikanRow.find("td.master-racikan-cell:eq(1) select").val() === "none") ? 0 : parseInt(masterRacikanRow.find("td.master-racikan-cell:eq(1) select").val());
                dataRacikan.keterangan = masterRacikanRow.find("td.master-racikan-cell:eq(1) textarea").val();
                dataRacikan.iterasi = masterRacikanRow.find("td.master-racikan-cell:eq(1) input.racikan_iterasi").inputmask("unmaskedvalue");
                dataRacikan.satuan_konsumsi = masterRacikanRow.find("td.master-racikan-cell:eq(1) input.satuan_konsumsi").val();
                /*dataRacikan.signaKonsumsi = parseInt(masterRacikanRow.find("td.master-racikan-cell:eq(2) input").inputmask("unmaskedvalue"));
                dataRacikan.signaTakar = parseInt(masterRacikanRow.find("td.master-racikan-cell:eq(4) input").inputmask("unmaskedvalue"));*/
                dataRacikan.signaKonsumsi = masterRacikanRow.find("td.master-racikan-cell:eq(2) input").val();
                dataRacikan.signaTakar = masterRacikanRow.find("td.master-racikan-cell:eq(4) input").val();
                dataRacikan.signaHari = parseInt(masterRacikanRow.find("td.master-racikan-cell:eq(5) input").inputmask("unmaskedvalue"));




                masterRacikanRow.find("td:eq(1) table.komposisi-racikan tbody.komposisi-item tr.komposisi-row").each(function() {
                    var obat = $(this).find("td:eq(1)").attr("uid-obat");
                    //var qty = $(this).find("td:eq(2)").html();
                    var takaranBulat = $(this).find("td:eq(2) b").html();
                    var takaranDecimal = $(this).find("td:eq(2) sub").attr("nilaiExact");
                    var takaranDecimalText = $(this).find("td:eq(2) sub").html();
                    var takaranKekuatan = $(this).find("td:eq(2) h6").html();
                    var takaran = parseFloat(takaranBulat) + parseFloat(takaranDecimal);

                    if(obat !== undefined) {
                        dataRacikan.item.push({
                            "obat": obat,
                            "takaranBulat": takaranBulat,
                            "takaranDecimal": takaranDecimal,
                            "takaranDecimalText": takaranDecimalText,
                            "takaran": (isNaN(takaran) ? 1 : takaran),
                            "kekuatan": takaranKekuatan
                        });
                    }
                });

                if(
                    dataRacikan.nama !== "" &&
                    dataRacikan.item.length > 0 &&

                    /*dataRacikan.signaKonsumsi > 0 &&
                    dataRacikan.signaTakar > 0 &&*/
                    dataRacikan.signaKonsumsi !== "" &&
                    dataRacikan.signaTakar !== "" &&
                    dataRacikan.signaHari > 0
                ) {
                    racikan.push(dataRacikan);
                }
            });

            console.log(resep);
            console.log(racikan);

            var formData = {};


            if(antrianData.poli_info !== null) {
                if(antrianData.poli_info.uid === __UIDFISIOTERAPI__) {
                    formData = {
                        request: "update_asesmen_medis",
                        kunjungan: kunjungan,
                        antrian: antrian,
                        penjamin: penjamin,
                        pasien: pasien,
                        poli: poli,
                        charge_invoice: charge_invoice,
                        //==============================
                        keluhan_utama: keluhanUtamaData,
                        keluhan_tambahan: keluhanTambahanData,
                        tekanan_darah: parseFloat(tekananDarah),
                        nadi: parseFloat(nadi),
                        suhu: parseFloat(suhu),
                        pernafasan: parseFloat(pernafasan),
                        berat_badan: parseFloat(beratBadan),
                        tinggi_badan: parseFloat(tinggiBadan),
                        lingkar_lengan_atas: parseFloat(lingkarLengan),
                        icd9: selectedICD9,
                        pemeriksaan_fisik: pemeriksaanFisikData,
                        //icd10_kerja: parseInt(icd10Kerja),
                        icd10_kerja: selectedICD10Kerja,
                        diagnosa_kerja: diagnosaKerjaData,
                        //icd10_banding: parseInt(icd10Banding),
                        icd10_banding: selectedICD10Banding,
                        diagnosa_banding: diagnosaBandingData,
                        planning: planningData,
                        anamnesa: terapisAnamnesa,
                        tataLaksana: terapisTataLaksana,
                        evaluasi: terapisEvaluasi,
                        anjuranBulan: parseFloat(terapisAnjuranBulan),
                        anjuranMinggu: parseFloat(terapisAnjuranMinggu),
                        suspek: terapisSuspek,
                        hasil: terapisHasil,
                        kesimpulan: terapisKesimpulan,
                        rekomendasi: terapisRekomendasi,
                        //==============================
                        tindakan: tindakan,
                        resep: resep,
                        keteranganResep: keteranganResep,
                        keteranganRacikan: keteranganRacikan,
                        racikan: racikan
                    };
                } else if(antrianData.poli_info.uid === __POLI_GIGI__ || antrianData.poli_info.uid === __POLI_ORTODONTIE__) {
                    var simetris = $("input[name=\"simetris\"]:checked").val();
                    var sendi = $("input[name=\"sendi\"]:checked").val();
                    var bibir = $("input[name=\"bibir\"]:checked").val();
                    var lidah = $("input[name=\"lidah\"]:checked").val();
                    var mukosa = $("input[name=\"mukosa\"]:checked").val();
                    var torus = $("input[name=\"torus\"]:checked").val();
                    var gingiva = $("input[name=\"gingiva\"]:checked").val();
                    var frenulum = $("input[name=\"frenulum\"]:checked").val();
                    var mulut_bersih = $("input[name=\"mulut_bersih\"]:checked").val();

                    var keterangan_bibir = $("#keterangan_bibir").val();
                    var keterangan_lidah = $("#keterangan_lidah").val();
                    var keterangan_mukosa = $("#keterangan_mukosa").val();
                    var keterangan_torus = $("#keterangan_torus").val();
                    var keterangan_gingiva = $("#keterangan_gingiva").val();
                    var keterangan_frenulum = $("#keterangan_frenulum").val();

                    formData = {
                        request: "update_asesmen_medis",
                        kunjungan: kunjungan,
                        antrian: antrian,
                        penjamin: penjamin,
                        pasien: pasien,
                        poli: poli,
                        charge_invoice: charge_invoice,
                        //==============================
                        keluhan_utama: keluhanUtamaData,
                        keluhan_tambahan: keluhanTambahanData,
                        tekanan_darah: parseFloat(tekananDarah),
                        nadi: parseFloat(nadi),
                        suhu: parseFloat(suhu),
                        pernafasan: parseFloat(pernafasan),
                        berat_badan: parseFloat(beratBadan),
                        tinggi_badan: parseFloat(tinggiBadan),
                        lingkar_lengan_atas: parseFloat(lingkarLengan),
                        icd9: selectedICD9,
                        pemeriksaan_fisik: pemeriksaanFisikData,
                        icd10_kerja: selectedICD10Kerja,
                        diagnosa_kerja: diagnosaKerjaData,
                        icd10_banding: selectedICD10Banding,
                        diagnosa_banding: diagnosaBandingData,
                        planning: planningData,
                        anamnesa: terapisAnamnesa,
                        tataLaksana: terapisTataLaksana,
                        evaluasi: terapisEvaluasi,
                        anjuranBulan: parseFloat(terapisAnjuranBulan),
                        anjuranMinggu: parseFloat(terapisAnjuranMinggu),
                        suspek: terapisSuspek,
                        hasil: terapisHasil,
                        kesimpulan: terapisKesimpulan,
                        rekomendasi: terapisRekomendasi,
                        //==============================
                        tindakan: tindakan,
                        resep: resep,
                        keteranganResep: keteranganResep,
                        keteranganRacikan: keteranganRacikan,
                        racikan: racikan,

                        simetris: simetris,
                        sendi: sendi,
                        bibir: bibir,
                        lidah: lidah,
                        mukosa: mukosa,
                        torus: torus,
                        gingiva: gingiva,
                        frenulum: frenulum,
                        mulut_bersih: mulut_bersih,

                        keterangan_bibir: keterangan_bibir,
                        keterangan_lidah: keterangan_lidah,
                        keterangan_mukosa: keterangan_mukosa,
                        keterangan_torus: keterangan_torus,
                        keterangan_gingiva: keterangan_gingiva,
                        keterangan_frenulum: keterangan_frenulum,

                        odontogram: JSON.stringify(metaSelOrdo)
                    };
                } else if(antrianData.poli_info.uid === __POLI_MATA__) {
                    var mataDataList =  {};
                    $(".mata_input").each(function() {
                        if(mataDataList[$(this).attr("id")] === undefined) {
                            mataDataList[$(this).attr("id")] = 0
                        }

                        mataDataList[$(this).attr("id")] = $(this).inputmask("unmaskedvalue");
                    });

                    var tujuan_resep = [];
                    $(".tujuan_resep").each(function() {
                        if($(this).is(":checked")) {
                            tujuan_resep.push($(this).val())
                        }
                    });

                    formData = {
                        request: "update_asesmen_medis",
                        kunjungan: kunjungan,
                        antrian: antrian,
                        penjamin: penjamin,
                        pasien: pasien,
                        poli: poli,
                        charge_invoice: charge_invoice,
                        //==============================
                        keluhan_utama: keluhanUtamaData,
                        keluhan_tambahan: keluhanTambahanData,
                        tekanan_darah: parseFloat(tekananDarah),
                        nadi: parseFloat(nadi),
                        suhu: parseFloat(suhu),
                        pernafasan: parseFloat(pernafasan),
                        berat_badan: parseFloat(beratBadan),
                        tinggi_badan: parseFloat(tinggiBadan),
                        lingkar_lengan_atas: parseFloat(lingkarLengan),
                        icd9: selectedICD9,
                        pemeriksaan_fisik: pemeriksaanFisikData,
                        //icd10_kerja: parseInt(icd10Kerja),
                        icd10_kerja: selectedICD10Kerja,
                        diagnosa_kerja: diagnosaKerjaData,
                        //icd10_banding: parseInt(icd10Banding),
                        icd10_banding: selectedICD10Banding,
                        diagnosa_banding: diagnosaBandingData,
                        planning: planningData,

                        tindakan:tindakan,
                        resep: resep,
                        keteranganResep: keteranganResep,
                        keteranganRacikan: keteranganRacikan,
                        racikan: racikan,

                        mata_data: JSON.stringify(mataDataList),
                        tujuan_resep: tujuan_resep.join(",")
                    };
                } else {
                    formData = {
                        request: "update_asesmen_medis",
                        kunjungan: kunjungan,
                        antrian: antrian,
                        penjamin: penjamin,
                        pasien: pasien,
                        poli: poli,
                        charge_invoice: charge_invoice,
                        //==============================
                        keluhan_utama: keluhanUtamaData,
                        keluhan_tambahan: keluhanTambahanData,
                        tekanan_darah: parseFloat(tekananDarah),
                        nadi: parseFloat(nadi),
                        suhu: parseFloat(suhu),
                        pernafasan: parseFloat(pernafasan),
                        berat_badan: parseFloat(beratBadan),
                        tinggi_badan: parseFloat(tinggiBadan),
                        lingkar_lengan_atas: parseFloat(lingkarLengan),
                        icd9: selectedICD9,
                        pemeriksaan_fisik: pemeriksaanFisikData,
                        //icd10_kerja: parseInt(icd10Kerja),
                        icd10_kerja: selectedICD10Kerja,
                        diagnosa_kerja: diagnosaKerjaData,
                        //icd10_banding: parseInt(icd10Banding),
                        icd10_banding: selectedICD10Banding,
                        diagnosa_banding: diagnosaBandingData,
                        planning: planningData,
                        /*anamnesa:terapisAnamnesa,
                        tataLaksana: terapisTataLaksana,
                        evaluasi: terapisEvaluasi,
                        anjuranBulan: parseFloat(terapisAnjuranBulan),
                        anjuranMinggu: parseFloat(terapisAnjuranMinggu),
                        suspek: terapisSuspek,
                        hasil:terapisHasil,
                        kesimpulan:terapisKesimpulan,
                        rekomendasi:terapisRekomendasi,*/
                        //==============================
                        tindakan:tindakan,
                        resep: resep,
                        keteranganResep: keteranganResep,
                        keteranganRacikan: keteranganRacikan,
                        racikan: racikan
                    };
                }
            } else {
                formData = {
                    request: "update_asesmen_medis",
                    kunjungan: kunjungan,
                    antrian: antrian,
                    penjamin: penjamin,
                    pasien: pasien,
                    poli: poli,
                    charge_invoice: charge_invoice,
                    //==============================
                    keluhan_utama: keluhanUtamaData,
                    keluhan_tambahan: keluhanTambahanData,
                    tekanan_darah: parseFloat(tekananDarah),
                    nadi: parseFloat(nadi),
                    suhu: parseFloat(suhu),
                    pernafasan: parseFloat(pernafasan),
                    berat_badan: parseFloat(beratBadan),
                    tinggi_badan: parseFloat(tinggiBadan),
                    lingkar_lengan_atas: parseFloat(lingkarLengan),
                    icd9: selectedICD9,
                    pemeriksaan_fisik: pemeriksaanFisikData,
                    //icd10_kerja: parseInt(icd10Kerja),
                    icd10_kerja: selectedICD10Kerja,
                    diagnosa_kerja: diagnosaKerjaData,
                    //icd10_banding: parseInt(icd10Banding),
                    icd10_banding: selectedICD10Banding,
                    diagnosa_banding: diagnosaBandingData,
                    planning: planningData,
                    /*anamnesa:terapisAnamnesa,
                    tataLaksana: terapisTataLaksana,
                    evaluasi: terapisEvaluasi,
                    anjuranBulan: parseFloat(terapisAnjuranBulan),
                    anjuranMinggu: parseFloat(terapisAnjuranMinggu),
                    suspek: terapisSuspek,
                    hasil:terapisHasil,
                    kesimpulan:terapisKesimpulan,
                    rekomendasi:terapisRekomendasi,*/
                    //==============================
                    tindakan:tindakan,
                    resep: resep,
                    keteranganResep: keteranganResep,
                    keteranganRacikan: keteranganRacikan,
                    racikan: racikan
                };
            }




            //console.clear();

            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Asesmen",
                data: formData,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                success: function(response) {
                    savingResult = response;
                },
                error: function(response) {
                    console.clear();
                    console.log(response);
                }
            });

            // orderRadiologi(UID, listTindakanRadiologiTerpilih, listTindakanRadiologiDihapus, charge_invoice);
            listTindakanRadiologiDihapus = [];		//set back to empty
            return savingResult;
        }

        if(allowEdit) {
            $("#tab-asesmen-inap .nav-link").click(function() {
                if(allowEdit) {
                    const simpanDataProcess = new Promise(function(resolve, reject) {
                        resolve(simpanAsesmen(
                            antrianData,
                            UID,
                            metaSwitchEdit.txt_keluhan_utama.editor,
                            metaSwitchEdit.txt_keluhan_tambahan.editor,
                            metaSwitchEdit.txt_pemeriksaan_fisik.editor,
                            editorTerapisAnamnesa,
                            editorTerapisTataLaksana,
                            editorTerapisEvaluasi,
                            editorTerapisHasil,
                            editorTerapisKesimpulan,
                            editorTerapisRekomendasi,
                            metaSwitchEdit.txt_diagnosa_kerja.editor,
                            metaSwitchEdit.txt_diagnosa_banding.editor,
                            metaSwitchEdit.txt_planning.editor,
                            metaSwitchEdit.txt_keterangan_resep.editor,
                            metaSwitchEdit.txt_keterangan_resep_racikan.editor,
                            metaSwitchEdit));
                    }).then(function(result) {
                        if(result.response_package.response_result > 0) {
                            notification ("success", "Asesmen Berhasil Disimpan", 1000, "hasil_tambah_dev");
                        } else {
                            notification ("danger", "Gagal Simpan Data", 3000, "hasil_tambah_dev");
                        }
                    });
                }
            });
        }



        $("body").on("click", "#btnSimpan", function() {
            const simpanDataProcess = new Promise(function(resolve, reject) {
                resolve(simpanAsesmen(antrianData, UID, metaSwitchEdit.txt_keluhan_utama.editor, metaSwitchEdit.txt_keluhan_tambahan.editor, metaSwitchEdit.txt_pemeriksaan_fisik.editor, editorTerapisAnamnesa, editorTerapisTataLaksana, editorTerapisEvaluasi, editorTerapisHasil, editorTerapisKesimpulan, editorTerapisRekomendasi, metaSwitchEdit.txt_diagnosa_kerja.editor, metaSwitchEdit.txt_diagnosa_banding.editor, metaSwitchEdit.txt_planning.editor, metaSwitchEdit.txt_keterangan_resep.editor, metaSwitchEdit.txt_keterangan_resep_racikan.editor, metaSwitchEdit));
            }).then(function(result) {
                if(result.response_package.response_result > 0) {
                    notification ("success", "Asesmen Berhasil Disimpan", 3000, "hasil_tambah_dev");
                    //push_socket(__ME__, "permintaan_resep_baru", "*", "Permintaan resep dari dokter " + __MY_NAME__ + " untuk pasien a/n " + $(".nama_pasien").html(), "warning");
                    //location.href = __HOSTNAME__ + '/rawat_jalan/dokter';
                } else {
                    notification ("danger", "Gagal Simpan Data", 3000, "hasil_tambah_dev");
                }
            });
        });


        $("body").on("click", "#btnSelesai", function() {
            simpanAsesmen(antrianData, UID, metaSwitchEdit.txt_keluhan_utama.editor, metaSwitchEdit.txt_keluhan_tambahan.editor, metaSwitchEdit.txt_pemeriksaan_fisik.editor, editorTerapisAnamnesa, editorTerapisTataLaksana, editorTerapisEvaluasi, editorTerapisHasil, editorTerapisKesimpulan, editorTerapisRekomendasi, metaSwitchEdit.txt_diagnosa_kerja.editor, metaSwitchEdit.txt_diagnosa_banding.editor, metaSwitchEdit.txt_planning.editor, metaSwitchEdit.txt_keterangan_resep.editor, metaSwitchEdit.txt_keterangan_resep_racikan.editor, metaSwitchEdit);
            Swal.fire({
                title: 'Selesai isi asesmen medis?',
                text: 'Jika sudah selesai maka asesmen akan hilang dari antrian dan biaya tindakan serta obat akan langsung ditagihkan pada pasien.',
                showDenyButton: true,
                //showCancelButton: true,
                confirmButtonText: `Ya`,
                denyButtonText: `Belum`,
            }).then((result) => {
                if (result.isConfirmed) {
                    const simpanDataProcess = new Promise(function(resolve, reject) {
                        resolve(simpanAsesmen(antrianData, UID, metaSwitchEdit.txt_keluhan_utama.editor, metaSwitchEdit.txt_keluhan_tambahan.editor, metaSwitchEdit.txt_pemeriksaan_fisik.editor, editorTerapisAnamnesa, editorTerapisTataLaksana, editorTerapisEvaluasi, editorTerapisHasil, editorTerapisKesimpulan, editorTerapisRekomendasi, metaSwitchEdit.txt_diagnosa_kerja.editor, metaSwitchEdit.txt_diagnosa_banding.editor, metaSwitchEdit.txt_planning.editor, metaSwitchEdit.txt_keterangan_resep.editor, metaSwitchEdit.txt_keterangan_resep_racikan.editor, metaSwitchEdit, "Y"));
                    }).then(function(result) {
                        if(result.response_package.response_result > 0) {
                            notification ("success", "Asesmen Berhasil Disimpan", 3000, "hasil_tambah_dev");
                            push_socket(__ME__, "permintaan_resep_baru", "*", "Permintaan resep dari dokter " + __MY_NAME__ + " untuk pasien a/n " + $(".nama_pasien").html(), "warning").then(function() {
                                location.href = __HOSTNAME__ + "/igdv2/dokter/asesmen-detail/" + pasien_uid + "/" + kunjungan.uid + "/" + pasien_penjamin_uid + "/" + __PAGES__[7];
                            });

                        } else {
                            notification ("danger", "Gagal Simpan Data", 3000, "hasil_tambah_dev");
                        }
                    });
                } else if (result.isDenied) {
                    //Swal.fire('Changes are not saved', '', 'info')
                }
            });
        });


        //loadRadiologiTindakan('tindakan-radiologi');

        //$("#tindakan-radiologi").select2({});

        $("#tindakan_radiologi").select2({ //Tindakan Lab Sini
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Radiologi";
                }
            },
            placeholder:"Cari Radiologi",
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/Radiologi/get_tindakan_for_dokter",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.response_data;

                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.uid,
                                detail:item.detail,
                                harga:item.harga
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            let uidTindakanRad = $(this).val();

            $("#radiologi_tindakan_notifier").html("");
            if (pasien_penjamin_uid !== __UIDPENJAMINUMUM__){
                uid_penjamin_tindakan_rad = __UIDPENJAMINUMUM__;

                let html = '<p><b class="badge badge-warning"><i class="fa fa-exclamation-circle" style="margin-right: 5px;"></i>Akan ditanggung Penjamin Umum</b></p>';

                $.each(listRadiologiTindakan, function(key_tindakan, item_tindakan){
                    let statusLoop = true;

                    if (item_tindakan.uid === uidTindakanRad){

                        $.each(item_tindakan.harga, function(key_harga, item_harga){

                            if (pasien_penjamin_uid == item_harga.penjamin){
                                html = '<p><b class="badge badge-success"><i class="fa fa-check-circle" style="margin-right: 5px;"></i> Ditanggung Penjamin</b></p>';

                                //setter jika dijamin
                                uid_penjamin_tindakan_rad = pasien_penjamin_uid;
                                statusLoop = false;
                                return false;
                            }

                        });

                        if (statusLoop === false){
                            return false;
                        }

                    }

                });

                $("#radiologi_tindakan_notifier").html(html);
            }
        });

        /*function loadRadiologiTindakan(selector){
            var radiologiTindakan;
            $.ajax({
                url: __HOSTAPI__ + "/Radiologi/tindakan",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    if(response.response_package != null) {
                        radiologiTindakan = response.response_package.response_data;
                        if (radiologiTindakan.length > 0){
                            for(i = 0; i < radiologiTindakan.length; i++){
                                var selection = document.createElement("OPTION");
                                $(selection).attr("value", radiologiTindakan[i].uid).html(radiologiTindakan[i].nama);
                                $("#" + selector).append(selection);
                            }
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return radiologiTindakan;
        }*/

        function loadPasien(params){
            var MetaData = null;

            if (params != ""){
                $.ajax({
                    async: false,
                    url:__HOSTAPI__ + "/Asesmen/asesmen-rawat-detail/" + params,
                    type: "GET",
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    success: function(response){
                        if (response.response_package != ""){
                            MetaData = response.response_package;

                            $.each(MetaData.pasien, function(key, item){
                                $("#" + key).html(item);
                            });

                            $.each(MetaData.antrian, function(key, item){
                                $("#" + key).val(item);
                            });

                            if (MetaData.pasien.id_jenkel == 2){
                                $(".wanita").attr("hidden",true);
                            } else {
                                $(".pria").attr("hidden",true);
                            }

                            if (MetaData.asesmen_rawat != ""){
                                $.each(MetaData.asesmen_rawat, function(key, item){
                                    $("#" + key).val(item);

                                    if(key === "tinggi_badan") {
                                        $("#txt_" + key).val(item);

                                    } else {
                                        $("#txt_" + key).val(item);
                                    }

                                    checkedRadio(key, item);
                                    checkedCheckbox(key, item);
                                });
                            }
                        }
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            }

            return MetaData;
        }

        function checkedRadio(name, value){
            var $radios = $('input:radio[name='+ name +']');

            if ($radios != ""){
                if($radios.is(':checked') === false) {
                    if (value != null && value != ""){
                        $radios.filter('[value="'+ value +'"]').prop('checked', true);
                    }
                }
            }
        }

        function checkedCheckbox(name, value){
            var $check = $('input:checkbox[name='+ name +']');

            if ($check != ""){
                if($check.is(':checked') === false) {
                    if (value != null && value != ""){
                        $check.filter('[value="'+ value +'"]').prop('checked', true);
                    }
                }
            }
        }

        function loadDataPenjamin(){
            let dataPenjamin;

            $.ajax({
                url: __HOSTAPI__ + "/Penjamin/penjamin",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    if(response.response_package != null) {
                        dataPenjamin = response.response_package.response_data;
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });

            return dataPenjamin;
        }



        function load_cppt(data) {
            var returnHTML = "";
            $.ajax({
                url: __HOSTNAME__ + "/pages/rawat_jalan/dokter/cppt-single.php",
                async:false,
                data:{
                    setter:data
                },
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"POST",
                success:function(response_html) {
                    returnHTML = response_html;
                },
                error: function(response_html) {
                    console.log(response_html);
                }
            });
            return returnHTML;
        }



        /*=========================================================*/






















        /*========================= RADIOLOGI SCRIPT AREA START ==========================*/
        //load order with returning selectedTindakan
        function loadRadiologiOrder(uid_antrian){
            let dataOrder;
            let selectedTindakan = {};

            $.ajax({
                url: __HOSTAPI__ + "/Radiologi/get-radiologi-order/" + uid_antrian,
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    if(response.response_package != null) {
                        dataOrder = response.response_package.detail_order;

                        let no_urut = 1;
                        $.each(dataOrder, function(key_order, item_order){
                            let status_disabled = "disabled";

                            //check if order data has inserted, the delete button will disabled
                            if (item_order.keterangan == null && item_order.kesimpulan == null){
                                status_disabled = "";
                            }

                            let html = "<tr>\
									<td class='no_urut_rad'>"+ no_urut +"</td>\
									<td>"+ item_order.tindakan +"</td>\
									<td>"+ item_order.penjamin +"</td>\
									<td><button class='btn btn-danger btn-sm btnHapusTindakanRad' 					data-uid='"+ item_order.uid_tindakan +"' \
										data-nama='" + item_order.tindakan +"' "+ status_disabled +">\
										<i class='fa fa-trash'></button></td>\
								</tr>";

                            $("#table_tindakan_radiologi tbody").append(html);
                            no_urut++;

                            $('#tindakan_radiologi').val('').trigger('change');
                            selectedTindakan[item_order.uid_tindakan] = item_order.uid_penjamin;
                            $("#tindakan_radiologi option[value='"+ item_order.uid_tindakan +"']").remove();
                        });

                    }

                },
                error: function(response) {
                    console.log(response);
                }
            });

            return selectedTindakan;
        }

        function loadRadiologiTindakan(){
            var radiologiTindakan;

            $("#tindakan_radiologi").empty();
            $("#tindakan_radiologi").append("<option disabled selected value=''>Pilih Tindakan Radiologi</option>");

            $.ajax({
                url: __HOSTAPI__ + "/Radiologi/get_tindakan_for_dokter",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    if(response.response_package != null) {
                        radiologiTindakan = response.response_package.response_data;
                        if (radiologiTindakan.length > 0){
                            for(i = 0; i < radiologiTindakan.length; i++){

                                var selection = document.createElement("OPTION");
                                $(selection).attr("value", radiologiTindakan[i].uid).html(radiologiTindakan[i].nama);
                                $("#tindakan_radiologi").append(selection);
                            }
                        }
                    }

                    $("#tindakan_radiologi").select2({});
                },
                error: function(response) {
                    console.log(response);
                }
            });

            return radiologiTindakan;
        }


        //initiate radiologi tindakan data
        //var listRadiologiTindakan = loadRadiologiTindakan();

        //variable for collect selected Tindakan
        var listTindakanRadiologiTerpilih = loadRadiologiOrder(UID);

        //variable for collect deleted Tindakan
        var listTindakanRadiologiDihapus = [];

        //variable for load penjamin
        var listPenjamin = loadDataPenjamin();

        //this variable will be used in action tambahTindakan; default is uid penjamin umum
        var uid_penjamin_tindakan_rad = __UIDPENJAMINUMUM__;

        /*$("#tindakan_radiologi").on('select2:select', function(){
            let uidTindakanRad = $(this).val();

            $("#radiologi_tindakan_notifier").html("");
            if (pasien_penjamin_uid !== __UIDPENJAMINUMUM__){
                uid_penjamin_tindakan_rad = __UIDPENJAMINUMUM__;

                let html = '<p><b class="badge badge-warning"><i class="fa fa-exclamation-circle" style="margin-right: 5px;"></i>Akan ditanggung Penjamin Umum</b></p>';

                $.each(listRadiologiTindakan, function(key_tindakan, item_tindakan){
                    let statusLoop = true;

                    if (item_tindakan.uid === uidTindakanRad){

                        $.each(item_tindakan.harga, function(key_harga, item_harga){

                            if (pasien_penjamin_uid == item_harga.penjamin){
                                html = '<p><b class="badge badge-success"><i class="fa fa-check-circle" style="margin-right: 5px;"></i> Ditanggung Penjamin</b></p>';

                                //setter jika dijamin
                                uid_penjamin_tindakan_rad = pasien_penjamin_uid;
                                statusLoop = false;
                                return false;
                            }

                        });

                        if (statusLoop === false){
                            return false;
                        }

                    }

                });

                $("#radiologi_tindakan_notifier").html(html);
            }

        });*/

        $("#btnTambahTindakanRadiologi").click(function(){
            let uidTindakanRad = $("#tindakan_radiologi").val();
            if(uidTindakanRad !== null) {
                let dataTindakan = $("#tindakan_radiologi").select2('data');
                let namaPenjamin;

                $.each(listPenjamin, function(key, item){
                    if (item.uid == uid_penjamin_tindakan_rad){
                        namaPenjamin = item.nama;
                        return false;
                    }
                });

                let html = "<tr>" +
                    "<td class='no_urut_rad'></td>" +
                    "<td>"+ dataTindakan[0].text +"</td>" +
                    "<td>"+ namaPenjamin +"</td>" +
                    "<td><button class='btn btn-danger btn-sm btnHapusTindakanRad'><i class='fa fa-trash'></button></td>" +
                    "</tr>";

                $("#table_tindakan_radiologi tbody").append(html);

                $('#tindakan_radiologi').val('').trigger('change');
                listTindakanRadiologiTerpilih[uidTindakanRad] = uid_penjamin_tindakan_rad;
                $("#tindakan_radiologi option[value='"+ uidTindakanRad +"']").remove();

                setNomorUrut('table_tindakan_radiologi', 'no_urut_rad');

            }
        });

        $("#table_tindakan_radiologi").on('click', '.btnHapusTindakanRad', function(){
            let uid_tindakan = $(this).data("uid");
            let nama_tindakan = $(this).data("nama");

            delete listTindakanRadiologiTerpilih[uid_tindakan];
            listTindakanRadiologiDihapus.push(uid_tindakan);
            $(this).parent().parent().remove();

            //set back to list
            $("#tindakan_radiologi").append("<option value='"+ uid_tindakan +"'>"+ nama_tindakan +"</option>");

            setNomorUrut('table_tindakan_radiologi', 'no_urut_rad');
        });

        function orderRadiologi(uid_antrian, listTindakan, listTindakanDihapus, charge_invoice = "N"){
            let formData = {
                "request" : "add-order-radiologi",
                "uid_antrian" : uid_antrian,
                "listTindakan" : listTindakan,
                "listTindakanDihapus": listTindakanDihapus,
                "charge_invoice": charge_invoice
            }

            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Radiologi",
                data: formData,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                success: function(response) {
                    // if(response.response_package.response_result > 0) {
                    // 	notification ("success", "Asesmen Berhasil Disimpan", 3000, "hasil_tambah_dev");
                    // } else {
                    // 	notification ("danger", response.response_package, 3000, "hasil_tambah_dev");
                    // }
                },
                error: function(response) {
                    console.clear();
                    console.log(response);
                }
            });
        }
        /*======================= RADIOLOGI SCRIPT AREA STOP ==========================*/

        /*======================= LABORATORIUM SCRIPT AREA START ========================*/
        //load order with returning selectedTindakan
        /*function loadLabOrder(uid_antrian){
            let dataOrder;
            let selectedTindakan = {};

            $.ajax({
                url: __HOSTAPI__ + "/Laboratorium/get-radiologi-order/" + uid_antrian,
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php //echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					if(response.response_package != null) {
						dataOrder = response.response_package.detail_order;

						let no_urut = 1;
						$.each(dataOrder, function(key_order, item_order){
							let status_disabled = "disabled";

							//check if order data has inserted, the delete button will disabled
							// if (item_order.keterangan == null && item_order.kesimpulan == null){
							// 	status_disabled = "";
							// }

							let html = "<tr>\
									<td class='no_urut_lab'>"+ no_urut +"</td>\
									<td>"+ item_order.tindakan +"</td>\
									<td>"+ item_order.penjamin +"</td>\
									<td><button class='btn btn-danger btn-sm btnHapusTindakanLab' 					data-uid='"+ item_order.uid_tindakan +"' \
										data-nama='" + item_order.tindakan +"' "+ status_disabled +">\
										<i class='fa fa-trash'></button></td>\
								</tr>";

							$("#table_tindakan_lab tbody").append(html);
							no_urut++;

							$('#tindakan_lab').val('').trigger('change');
							selectedTindakan[item_order.uid_tindakan] = item_order.uid_penjamin;
							$("#tindakan_lab option[value='"+ item_order.uid_tindakan +"']").remove();
						});

					}

				},
				error: function(response) {
					console.log(response);
				}
			});

			return selectedTindakan;
		}*/

        function loadLabOrder(uid_antrian){

            $.ajax({
                url: __HOSTAPI__ + "/Laboratorium/get-laboratorium-order/" + uid_antrian,
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {

                    if(response.response_package != null) {

                        let no_urut = 1;
                        $.each(response.response_package.response_data, function(key, item){
                            let status_disabled = "disabled";

                            let html = "<tr>" +
                                "<td class=\"no_urut_lab\">"+ no_urut +"</td>" +
                                "<td>" + item.no_order + "</td>" +
                                "<td>" + item.waktu_order + "</td>" +
                                "<td>" + item.nama_dr_penanggung_jawab + "</td>" +
                                "<td>" +
                                "<button class=\"btn btn-warning btn-sm btnViewDetailOrder\" data-uid=\"" + item.uid + "\" data-dokterpj=\"" + item.uid_dr_penanggung_jawab + "\">" +
                                "<i class=\"fa fa-list\"></i></button>" +
                                "<button class=\"btn btn-danger btn-sm btnHapusOrderLab\" data-uid=\"" + item.uid + "\" data-order=\"" + item.no_order + "\" " + status_disabled + ">" +
                                "<i class=\"fa fa-trash\"></i></button></td>" +
                                "</tr>";

                            $("#table_order_lab tbody").append(html);
                            no_urut++;
                        });

                    }

                },
                error: function(response) {
                    console.log(response);
                }
            });

        }

        var dataTableLabOrder = $("#table_order_lab").DataTable({
            autoWidth: false,
            "ajax":{
                "url" : __HOSTAPI__ + "/Laboratorium/get-laboratorium-order/" + UID,
                "async" : false,
                "beforeSend" : function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                "type" : "GET",
                "dataSrc": function(response){
                    if (response.response_package != null){
                        return response.response_package.response_data;
                    } else {
                        return [];
                    }
                }
            },
            "columnDefs":[
                {"targets": [0], "className":"dt-body-left"}
            ],
            "columns" : [
                {
                    "data": null, "sortable": false, render: function (data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["no_order"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["waktu_order"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["nama_dr_penanggung_jawab"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {

                        let button = "<div>";

                        if (row['editable'] == 'true') {
                            button += "<button class='btn btn-warning btn-sm btnViewDetailOrder' data-uid='"
                                + row['uid'] +"' data-dokterpj='"+ row['uid_dr_penanggung_jawab'] +"' data-editable='" + row['editable'] + "'  >\
										<i class='fa fa-list'></i></button>" +

                                "<button class='btn btn-danger btn-sm btnHapusOrderLab' 					data-uid='"+ row['uid'] +"' data-order='" + row['no_order'] + "' " + ">\
										<i class='fa fa-trash'></i></button>";

                        } else if (row['editable'] == 'false') {
                            button += "<button class=\"btn btn-info btn-sm btnViewHasilOrder\" data-uid=\"" + row.uid +"\" data-dokterpj=\""+ row.nama_dr_penanggung_jawab +"\" data-editable=\"" + row.editable + "\"><i class=\"fa fa-eye\"></i></button>";
                        }

                        button += "</div>";

                        return button;
                    }
                }
            ]
        });

        function loadLabDetailOrder(uid_lab_order, status_disabled) {
            let dataDetail;
            let tindakanTerpilih = {};

            $("#table_tindakan_lab tbody").html("");

            $.ajax({
                url: __HOSTAPI__ + "/Laboratorium/get-laboratorium-order-detail/" + uid_lab_order,
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    if (response.response_package != null) {
                        dataDetail = response.response_package.response_data;

                        let no_urut = 1;
                        $.each(dataDetail, function(key, item){
                            let html = "<tr>" +
                                "<td class='no_urut_lab'>" + no_urut + "</td>" +
                                "<td>" + item.tindakan + "</td>" +
                                "<td>" + item.penjamin + "</td>" +
                                "<td><button " + status_disabled + " class='btn btn-sm btn-danger btnHapusTindakanLab' data-uid='" + item.uid_tindakan + "' data-nama='" + item.tindakan + "'><i class='fa fa-trash'></i></button></td>" +
                                "</tr>";


                            $("#table_tindakan_lab tbody").append(html);

                            tindakanTerpilih[item.uid_tindakan] = item.uid_penjamin;
                            $("#tindakan_lab option[value='"+ item.uid_tindakan +"']").remove();
                            no_urut++;
                        });
                    }

                },
                error: function(response) {
                    console.log(response);
                }
            });

            return tindakanTerpilih;
        }

        var selectedLabItemList = [];

        function setLabTindakan() {
            /*$("#tindakan_lab").empty();
            $("#tindakan_lab").append("<option disabled selected value=''>Pilih Tindakan Laboratorium</option>");

            if (listLabTindakan.length > 0){
                for(i = 0; i < listLabTindakan.length; i++){

                    var selection = document.createElement("OPTION");
                    $(selection).attr("value", listLabTindakan[i].uid).html(listLabTindakan[i].nama);
                    $("#tindakan_lab").append(selection);
                }

                $("#tindakan_lab").select2({
                    dropdownParent: $("#form-tambah-order-lab")
                });
            }*/

            var listTindakanLabTerpilih = {};
            var listTindakanLabDihapus = [];
            var listPenjamin = loadDataPenjamin();
            var LabMode;
            var uid_lab_order;
            var uid_penjamin_tindakan_lab = __UIDPENJAMINUMUM__;

            $("#tindakan_lab").select2({ //Tindakan Lab Sini
                minimumInputLength: 2,
                "language": {
                    "noResults": function(){
                        return "Laboratorium";
                    }
                },
                placeholder:"Cari Laboratorium",
                ajax: {
                    dataType: "json",
                    headers:{
                        "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                        "Content-Type" : "application/json",
                    },
                    url:__HOSTAPI__ + "/Laboratorium/get_tindakan_for_dokter",
                    type: "GET",
                    data: function (term) {
                        return {
                            search:term.term
                        };
                    },
                    cache: true,
                    processResults: function (response) {
                        var data = response.response_package.response_data;

                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama,
                                    id: item.uid,
                                    detail:item.detail,
                                    harga:item.harga
                                }
                            })
                        };
                    }
                }
            }).addClass("form-control").on("select2:select", function(e) {
                var data = e.params.data;

                for(var hargaKey in data.harga)
                {
                    if(pasien_penjamin_uid === data.harga[hargaKey].penjamin)
                    {
                        $("#tindakan_lab").attr({
                            "harga": parseFloat(data.harga[hargaKey].harga)
                        });
                    }
                }
                $("#lab_nilai_order").html("");

                if(listTindakanLabTerpilih[$("#tindakan_lab").val()] === undefined)
                {
                    listTindakanLabTerpilih[$("#tindakan_lab").val()] = {
                        "penjamin":"",
                        "item":[]
                    };
                }

                for(var key in data.detail)
                {
                    var LabSelectoriContainer = document.createElement("DIV");
                    if(data.detail[key].status === 'A') {
                        $(LabSelectoriContainer).addClass("col-md-4 d-flex align-items-center single_hover").html(
                            "<div class=\"flex\">" +
                            "<label for=\"lab_item_" + data.detail[key].id + "\" id=\"label_item_" + data.detail[key].id + "\">" + data.detail[key].keterangan + "</label>" +
                            "<div class=\"custom-control custom-checkbox-toggle custom-control-inline mr-1 pull-right text-right\">" +
                            "<input type=\"checkbox\" value=\"" + data.detail[key].id + "\" name=\"detail_lab_item\" id=\"lab_item_" + data.detail[key].id + "\" class=\"custom-control-input lab_order_item_detail pull-right\">" +
                            "<label class=\"custom-control-label\" for=\"lab_item_" + data.detail[key].id + "\">Ya</label>" +
                            "</div>" +
                            "</div>");
                    } else {
                        $(LabSelectoriContainer).addClass("col-md-4 d-flex align-items-center single_hover").html(
                            "<div class=\"flex\">" +
                            "<label style=\"text-decoration: line-through\" for=\"lab_item_" + data.detail[key].id + "\" class=\"text-muted\" id=\"label_item_" + data.detail[key].id + "\">" + data.detail[key].keterangan + "</label>" +
                            "</div>");
                    }

                    $("#lab_nilai_order").append(LabSelectoriContainer);

                    listTindakanLabTerpilih[$("#tindakan_lab").val()].item.push({
                        "id": data.detail[key].id,
                        "nama": data.detail[key].keterangan
                    });
                }
            });
        }

        function loadLabTindakan(){
            let labTindakan;

            $.ajax({
                url: __HOSTAPI__ + "/Laboratorium/get_tindakan_for_dokter",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    if(response.response_package != null) {
                        labTindakan = response.response_package.response_data;
                    }

                },
                error: function(response) {
                    console.log(response);
                }
            });

            return labTindakan;
        }

        function loadLabOrderItem(params){	        //params = uid lab_order
            let dataItem;

            if (params != ""){
                $.ajax({
                    async: false,
                    url:__HOSTAPI__ + "/Laboratorium/get-laboratorium-order-detail-item/" + params,
                    type: "GET",
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    success: function(response){

                        let html = "";
                        if (response.response_package.response_result > 0){
                            dataItem = response.response_package.response_data;

                            $.each(dataItem, function(key, item){

                                html = "<p><h7><b>" + item.nama + "</b></h7></p>" +
                                    "<table class=\"table table-bordered table-striped largeDataType\">" +
                                    "<thead class=\"thead-dark\">" +
                                    "<tr>" +
                                    "<th class=\"wrap_content\">No</th>" +
                                    "<th style=\"width: 200px\">Item</th>" +
                                    "<th>Nilai</th>" +
                                    "<th class=\"wrap_content\">Satuan</td>" +
                                    "<th class=\"wrap_content\">Nilai Min.</td>" +
                                    "<th class=\"wrap_content\">Nilai Maks.</td>" +
                                    "</tr>" +
                                    "</thead>" +
                                    "<tbody>";

                                if (item.nilai_item.length > 0){

                                    let nomor = 1;
                                    var requestedData = item.request_item.split(",").map(function(intItem) {
                                        return parseInt(intItem, 10);
                                    });
                                    $.each(item.nilai_item, function(key, items){
                                        if(requestedData.indexOf(parseInt(items.id_lab_nilai)) > -1)
                                        {
                                            let nilai = items.nilai;

                                            if (nilai == null){
                                                nilai = "";
                                            }
                                            // id untuk input nilai formatnya: nilai_<uid tindakan>_<id nilai lab>
                                            html += "<tr>" +
                                                "<td>"+ nomor +"</td>" +
                                                "<td>" + items.keterangan + "</td>" +
                                                "<td><input id=\"nilai_" + items.uid_tindakan + "_" + items.id_lab_nilai + "\" value=\"" + nilai + "\" readonly class=\"form-control inputItemTindakan\" placeholder=\"-\" /></td>" +
                                                "<td>" + items.satuan + "</td>" +
                                                "<td>" + items.nilai_min + "</td>" +
                                                "<td>" + items.nilai_maks + "</td>"
                                            "</tr>";
                                            nomor++;
                                        }
                                    });
                                }
                                html += "</tbody></table><hr />";
                                $("#lab_hasil_pemeriksaan").append(html);
                            });
                        }
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            }
        }

        setLabTindakan();


        $("body").on("change", ".lab_order_item_detail", function() {
            if(listTindakanLabTerpilih[$("#tindakan_lab").val()] === undefined)
            {
                listTindakanLabTerpilih[$("#tindakan_lab").val()] = {
                    "penjamin":"",
                    "item":[]
                };
            }

            if($(this).is(":checked")) {
                listTindakanLabTerpilih[$("#tindakan_lab").val()].item.push({
                    "id": $(this).val(),
                    "nama": $("#label_item_" + $(this).val()).text()
                });
            } else {
                for(var key in listTindakanLabTerpilih[$("#tindakan_lab").val()].item)
                {
                    if(listTindakanLabTerpilih[$("#tindakan_lab").val()].item[key].id === $(this).val())
                    {
                        delete listTindakanLabTerpilih[$("#tindakan_lab").val()].item[key];
                    }
                }
            }
        });

        $("#btnTambahOrderLab").click(function(){
            $("#lab_nilai_order").html("");
            $("#btnTambahTindakanLab").removeAttr("disabled");
            $("#btnSubmitOrderLab").removeAttr("hidden");
            LabMode = "new";
            uid_lab_order = "";
            $("#table_tindakan_lab tbody").html("");
            //$("#dr_penanggung_jawab_lab").val("").trigger('change');
            $("#form-tambah-order-lab").modal("show");

            listTindakanLabTerpilih = {};
            selectedLabItemList = [];
        });

        $("#table_order_lab tbody").on('click', '.btnViewDetailOrder', function(){
            let uidLabOrder = $(this).data('uid');
            let uidDokterPj = $(this).data('dokterpj');
            let statusEditable = $(this).data('editable');
            let status_disabled = "";
            setLabTindakan();

            if (statusEditable == false) {
                $("#btnTambahTindakanLab").prop("disabled", true);
                $("#btnSubmitOrderLab").prop("hidden", true);
                status_disabled = "disabled";
            } else {
                $("#btnTambahTindakanLab").prop('disabled', false);
                $("#btnSubmitOrderLab").prop('hidden', false);
            }

            LabMode = "edit";
            uid_lab_order = uidLabOrder;

            listTindakanLabTerpilih = loadLabDetailOrder(uidLabOrder, status_disabled);

            //$("#dr_penanggung_jawab_lab").val(uidDokterPj).trigger('change');
            $("#form-tambah-order-lab").modal("show");

            //listTindakanLabTerpilih = loadLabOrder(uidLabOrder);
        });


        $("#table_order_lab tbody").on('click', '.btnViewHasilOrder', function(){
            let uidLabOrder = $(this).data('uid');
            let namaDokterPj = $(this).data('dokterpj');

            $("#dr_penanggung_jawab_view_hasil").val(namaDokterPj);
            $("#lab_hasil_pemeriksaan").html("");

            loadLabOrderItem(uidLabOrder);
            $("#form-view-hasil-lab").modal("show");
        });

        $("#table_order_lab tbody").on('click', '.btnHapusOrderLab', function(){
            let uidLabOrder = $(this).data('uid');
            let noOrder = $(this).data('order');

            Swal.fire({
                title: 'Hapus order laboratorium ' + noOrder + '?',
                showDenyButton: true,
                type: 'warning',
                //showCancelButton: true,
                confirmButtonText: `Ya`,
                confirmButtonColor: `#ff2a2a`,
                denyButtonText: `Batal`,
                denyButtonColor: `#1297fb`
            }).then((result) => {
                if (result.isConfirmed) {
                    //Validation
                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/Laboratorium/lab_order/" + uidLabOrder,
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "DELETE",
                        success: function(response) {
                            if(response.response_package.response_result > 0) {
                                notification ("success", "Order Berhasil Dihapus", 3000, "hasil_tambah_dev");
                                dataTableLabOrder.ajax.reload();
                            } else {
                                notification ("danger", response.response_package, 3000, "hasil_tambah_dev");
                            }
                        },
                        error: function(response) {
                            console.clear();
                            console.log(response);
                        }
                    });

                } else if (result.isDenied) {
                    //Swal.fire('Changes are not saved', '', 'info')
                }
            });
        });

        $("#btnTambahTindakanLab").click(function(){
            let uidTindakanLab = $("#tindakan_lab").val();
            let hargaPenjamin = number_format($("#tindakan_lab").attr("harga"), 2, ".", ",");

            if(listTindakanLabTerpilih[uidTindakanLab] === undefined)
            {
                listTindakanLabTerpilih[uidTindakanLab] = {
                    "penjamin":"",
                    "item":[]
                };
            }

            if (
                listTindakanLabTerpilih[uidTindakanLab].item.length > 0 &&
                uidTindakanLab != null
            ) {
                $("#lab_nilai_order").html("");
                let dataTindakan = $("#tindakan_lab").select2('data');
                let namaPenjamin;



                listTindakanLabTerpilih[uidTindakanLab].penjamin = pasien_penjamin_uid;
                //listTindakanLabTerpilih[uidTindakanLab].item = selectedLabItemList;

                var listItem = "<ol>";
                for(var ItemLabKey in listTindakanLabTerpilih[uidTindakanLab].item)
                {
                    listItem += "<li>" + listTindakanLabTerpilih[uidTindakanLab].item[ItemLabKey].nama + "</li>";
                }
                listItem += "</ol>";

                /*$.each(listPenjamin, function(key, item) {
                    if (item.uid == uid_penjamin_tindakan_lab){
                        namaPenjamin = item.nama;
                        return false;
                    }
                });*/

                let html = "<tr>" +
                    "<td class=\"no_urut_lab\"></td>" +
                    "<td>" + dataTindakan[0].text + listItem + "</td>" +
                    "<td class=\"number_style\">" + hargaPenjamin + "</td>" +
                    "<td>" +
                    "<button class=\"btn btn-danger btn-sm btnHapusTindakanLab\" data-uid=\""+ uidTindakanLab + "\" data-nama=\"" + dataTindakan[0].text + "\"><i class=\"fa fa-trash\"></i></button>" +
                    "</td>" +
                    "</tr>";

                $("#table_tindakan_lab tbody").append(html);
                $('#tindakan_lab').val('').trigger('change');


                $("#tindakan_lab option[value='"+ uidTindakanLab +"']").remove();
                $("#lab_tindakan_notifier").html("");
                setNomorUrut('table_tindakan_lab', 'no_urut_lab');
            }
            else {

            }
        });

        $("#table_tindakan_lab tbody").on('click', '.btnHapusTindakanLab', function(){
            let uid_tindakan = $(this).data("uid");
            let nama_tindakan = $(this).data("nama");

            delete listTindakanLabTerpilih[uid_tindakan];
            listTindakanLabDihapus.push(uid_tindakan);
            $(this).parent().parent().remove();

            //set back to list
            $("#tindakan_lab").append("<option value='"+ uid_tindakan +"'>"+ nama_tindakan +"</option>");
            $("#lab_tindakan_notifier").html("");

            setNomorUrut('table_tindakan_lab', 'no_urut_lab');
        });

        function orderLab(LabMode, UID, listTindakanLabTerpilih, selectedLabItemList, dokterPJLabOrder, uid_lab_order, dataTableLabOrder, API, charge_invoice = "N") {
            let formData = {
                "request" : LabMode + "-order-lab",
                "uid_antrian" : UID,
                "listTindakan" : listTindakanLabTerpilih,
                "order_list": selectedLabItemList,
                "dokterPJ" : dokterPJLabOrder,
                "uid_lab_order": uid_lab_order,
                "charge_invoice": charge_invoice
            }


            $.ajax({
                async: false,
                url: API + "/Laboratorium",
                data: formData,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                success: function(response) {
                    if(response.response_package.response_result > 0) {
                        notification ("success", "Laboratorium Berhasil Diorder", 3000, "hasil_tambah_dev");
                    } else {
                        notification ("danger", response.response_package, 3000, "hasil_tambah_dev");
                    }
                    dataTableLabOrder.ajax.reload();
                    $("#form-tambah-order-lab").modal("hide");
                },
                error: function(response) {
                    console.clear();
                    console.log(response);
                }
            });
        }


        $("#btnSubmitOrderLab").click(function() {
            /*let dokterPJLabOrder = $("#dr_penanggung_jawab_lab").val();
            if (
                dokterPJLabOrder !== "" &&
                dokterPJLabOrder !== undefined &&
                dokterPJLabOrder !== null &&
                Object.keys(listTindakanLabTerpilih).length > 0
            ){
                orderLab(LabMode, UID, listTindakanLabTerpilih, selectedLabItemList, dokterPJLabOrder, uid_lab_order, dataTableLabOrder, __HOSTAPI__);
            }*/

            orderLab(LabMode, UID, listTindakanLabTerpilih, selectedLabItemList, "", uid_lab_order, dataTableLabOrder, __HOSTAPI__);

        });


        /*==================== UNIVERSAL FUNCTION =====================*/
        function loadDokterPJ(){
            let dokterPJ;

            $(".dr_penanggung_jawab").empty();
            $(".dr_penanggung_jawab").append("<option disabled selected value=''>Pilih Dokter Penanggung Jawab</option>");

            /*$.ajax({
                url: __HOSTAPI__ + "/Pegawai/get_all_dokter_select2",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					if(response.response_package != null) {
						dokterPJ = response.response_package.response_data;
						if (dokterPJ.length > 0){
							for(i = 0; i < dokterPJ.length; i++){

			                    var selection = document.createElement("OPTION");
			                    $(selection).attr("value", dokterPJ[i].uid).html(dokterPJ[i].nama_dokter);
			                    $(".dr_penanggung_jawab").append(selection);
			                }
						}
					}

					$("#dr_penanggung_jawab_lab").select2({
						dropdownParent: $("#form-tambah-order-lab")
					});
				},
				error: function(response) {

				}
			});*/



            return dokterPJ;
        }

        /*$("#dr_penanggung_jawab_lab").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Dokter tidak ditemukan";
                }
            },
            placeholder:"Cari Dokter",
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/Pegawai/get_all_dokter_select2",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                processResults: function (response) {
                    var data = response.response_package.response_data;
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama_dokter,
                                id: item.uid
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            var data = e.params.data;

        });*/

        function setNomorUrut(table_name, no_urut_class){
            /*set dynamic serial number*/
            var rowCount = $("#"+ table_name +" tr").length;
            var table = $("#"+ table_name);
            $("."+ no_urut_class).html("");

            for (var i = 0, row; i < rowCount; i++) {
                table.find('tr:eq('+ i +')').find('td:eq(0)').html(i);
            }
            /*--------*/
        }

        $("#btnKonsul").click(function() {
            $("#form-konsul").modal("show");

            loadPenjamin("konsul", pasien_penjamin_uid);
            loadPoli("konsul");
            loadPrioritas(prioritas_antrian);
        });

        $("#inap_kamar").change(function() {
            loadBangsal("inap", $("#inap_kamar").val());
        });

        $("#btnInap").click(function() {
            loadPenjamin("inap", pasien_penjamin_uid);
            loadPoli("inap");
            loadKamar("inap");
            loadBangsal("inap", $("#inap_kamar").val());
            loadDokter("inap", __POLI_INAP__);
            $("#form-inap").modal("show");
        });


        $("#btnProsesInap").click(function() {
            Swal.fire({
                title: 'Daftar untuk rawat inap?',
                showDenyButton: true,
                confirmButtonText: `Ya`,
                denyButtonText: `Belum`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url:__HOSTAPI__ + "/Inap",
                        type: "POST",
                        data: {
                            request: "tambah_inap",
                            pasien: pasien_uid,
                            waktu_masuk: $("#inap_tanggal_masuk").val(),
                            kamar: $("#inap_kamar").val(),
                            penjamin: $("#inap_penjamin").val(),
                            bed: $("#inap_bed").val(),
                            dokter: $("#inap_dokter").val(),
                            kunjungan: kunjungan.uid,
                            keterangan: $("#inap_keterangan").val()
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response){
                            if(response.response_package.response_result > 0) {
                                Swal.fire(
                                    "Rawat Inap",
                                    "Pasien berhasil didaftarkan untuk rawat inap",
                                    "success"
                                ).then((result) => {
                                    $("#form-inap").modal("hide");
                                });
                            } else {

                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                } else if (result.isDenied) {

                }
            });
        });

        $("#btnRujuk").click(function() {
            $("#form-rujuk").modal("show");
            loadPenjamin("rujuk", pasien_penjamin_uid);
        });

        $("#btnProsesRujuk").click(function() {
            Swal.fire({
                title: 'Data sudah benar?',
                showDenyButton: true,
                confirmButtonText: `Ya`,
                denyButtonText: `Belum`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url:__HOSTAPI__ + "/Rujukan",
                        type: "POST",
                        data: {
                            request: "tambah_rujukan",
                            antrian: UID,
                            pasien: pasien_uid,
                            poli: antrianData.poli_info.uid,
                            jenis: $("#rujuk_jenis").val(),
                            tipe: $("#rujuk_tipe").val(),
                            penjamin: $("#rujuk_penjamin").val(),
                            keterangan: $("#rujuk_catatan").val()
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response){
                            if(response.response_package.response_result > 0) {
                                Swal.fire(
                                    'Permintaan Rujuk',
                                    'Permintaan rujuk berhasil ditambahkan. Silahkan isi asesmen untuk informasi rujukan lanjutan',
                                    'success'
                                ).then((result) => {
                                    $("#form-rujuk").modal("hide");
                                });
                            } else {

                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                } else if (result.isDenied) {

                }
            });
        });

        $("#konsul_departemen").on('change', function(){
            var poli = $(this).val();

            if (poli != ""){
                loadDokter("konsul", poli);
            }
        });


        function loadPenjamin(target_ui, selected = "") {
            var dataPenjamin = null;

            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Penjamin/penjamin",
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var MetaData = response.response_package.response_data;

                    if (MetaData != ""){
                        for(i = 0; i < MetaData.length; i++){
                            var selection = document.createElement("OPTION");
                            $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
                            if(MetaData[i].uid === selected) {
                                $(selection).attr("selected", "selected");
                            }
                            $("#" + target_ui + "_penjamin").append(selection);
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });

            return dataPenjamin;
        }

        function loadPoli(target_ui, selected = ""){
            var dataPoli = null;

            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Poli/poli-available",
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var MetaData = dataPoli = response.response_package.response_data;

                    if (MetaData != ""){
                        for(i = 0; i < MetaData.length; i++){
                            var selection = document.createElement("OPTION");

                            $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
                            $("#" + target_ui + "_departemen").append(selection);
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });

            return dataPoli;
        }

        function loadPrioritas(selected = 0){
            var term = 11;

            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Terminologi/terminologi-items/" + term,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var MetaData = response.response_package.response_data;

                    if (MetaData != ""){
                        for(i = 0; i < MetaData.length; i++){
                            var selection = document.createElement("OPTION");

                            $(selection).attr("value", MetaData[i].id).html(MetaData[i].nama);
                            if(MetaData[i].id === selected) {
                                $(selection).attr("selected", "selected");
                            }
                            $("#konsul_prioritas").append(selection);
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function resetSelectBox(selector, name){
            $("#"+ selector +" option").remove();
            var opti_null = "<option value='' selected disabled>Pilih "+ name +" </option>";
            $("#" + selector).append(opti_null);
        }

        $("#btnProsesKonsul").click(function() {
            const proses_konsul = new Promise(function(proceed, reject) {
                var dataObj = {};
                $('.inputan_konsul').each(function() {
                    var key = $(this).attr("id").split("_");
                    var value = $(this).val();

                    dataObj[key[1]] = value;
                });

                dataObj.pasien = pasien_uid;
                dataObj.currentPasien = pasien_uid;
                dataObj.currentAntrianID = UID;
                dataObj.konsul = true;
                dataObj.antrian = UID;
                dataObj.kunjungan = kunjungan.uid;
                dataObj.pj_pasien = kunjungan.pj_pasien;
                dataObj.info_didapat_dari = kunjungan.info_didapat_dari;

                Swal.fire({
                    title: 'Data sudah benar?',
                    showDenyButton: true,
                    confirmButtonText: `Ya. Cetak`,
                    denyButtonText: `Belum`,
                }).then((result) => {
                    if (result.isConfirmed) {

                        const simpanDataProcess = new Promise(function(resolve, reject) {
                            resolve(simpanAsesmen(antrianData, UID, metaSwitchEdit.txt_keluhan_utama.editor, metaSwitchEdit.txt_keluhan_tambahan.editor, metaSwitchEdit.txt_pemeriksaan_fisik.editor, editorTerapisAnamnesa, editorTerapisTataLaksana, editorTerapisEvaluasi, editorTerapisHasil, editorTerapisKesimpulan, editorTerapisRekomendasi, metaSwitchEdit.txt_diagnosa_kerja.editor, metaSwitchEdit.txt_diagnosa_banding.editor, metaSwitchEdit.txt_planning.editor, metaSwitchEdit.txt_keterangan_resep.editor, metaSwitchEdit.txt_keterangan_resep_racikan.editor, metaSwitchEdit));
                        }).then(function(result) {
                            if(result.response_package.response_result > 0) {
                                $.ajax({
                                    async: false,
                                    url: __HOSTAPI__ + "/Antrian",
                                    data: {
                                        request : "tambah-kunjungan",
                                        dataObj : dataObj
                                    },
                                    beforeSend: function(request) {
                                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                    },
                                    type: "POST",
                                    success: function(response){
                                        proceed(response);
                                    },
                                    error: function(response) {
                                        console.log("Error : ");
                                        console.log(response);
                                    }
                                });
                            } else {
                                notification ("danger", "Gagal Simpan Data", 3000, "hasil_tambah_dev");
                            }
                        });

                        //simpanAsesmen(antrianData, UID, editorKeluhanUtamaData, editorKeluhanTambahanData, editorPeriksaFisikData, editorTerapisAnamnesa, editorTerapisTataLaksana, editorTerapisEvaluasi, editorTerapisHasil, editorTerapisKesimpulan, editorTerapisRekomendasi, editorKerja, editorBanding, editorPlanning, editorKeteranganResep, editorKeteranganResepRacikan);


                    } else if(result.isDenied) {
                        reject();
                    }
                });
            }).then(function(result) {
                var expected_response = ['K', 'P'];

                if(expected_response.indexOf(result.response_package.response_notif) >= 0) {
                    if(result.response_package.response_notif === 'K') {
                        push_socket(__ME__, "kasir_daftar_baru", "*", "Biaya daftar pasien umum konsul", "warning").then(function() {
                            Swal.fire(
                                'Berhasil konsul!',
                                'Silahkan arahkan pasien ke kasir',
                                'success'
                            ).then((result) => {
                                location.href = __HOSTNAME__ + '/igdv2/dokter';
                            });
                        });

                    } else if(result.response_package.response_notif === 'P') {
                        Swal.fire(
                            'Berhasil konsul!',
                            'Silahkan arahkan pasien ke poli tujuan',
                            'success'
                        ).then((result) => {
                            location.href = __HOSTNAME__ + '/igdv2/dokter';
                        });
                    } else {
                        console.log("command not found");
                    }
                } else {
                    console.log(result);
                }
            });
        });

        function loadDokter(target_ui, poli, selected = ""){
            resetSelectBox(target_ui + "_dokter", "Dokter");

            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Poli/poli-set-dokter/" + poli,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var MetaData = dataPoli = response.response_package.response_data;

                    if (MetaData != ""){
                        for(i = 0; i < MetaData.length; i++){
                            var selection = document.createElement("OPTION");

                            $(selection).attr("value", MetaData[i].dokter).html(MetaData[i].nama);
                            $("#" + target_ui + "_dokter").append(selection);
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            })
        }





        function loadKamar(target_ui, selected = ""){
            resetSelectBox(target_ui + "_kamar", "Ruangan");

            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Ruangan",
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var MetaData = dataPoli = response.response_package.response_data;

                    if (MetaData !== undefined) {
                        for(i = 0; i < MetaData.length; i++){
                            var selection = document.createElement("OPTION");

                            $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
                            $("#" + target_ui + "_kamar").append(selection);
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            })
        }

        function loadBangsal(target_ui, kamar, selected = ""){
            resetSelectBox(target_ui + "_bed", "Pilih Bangsal");

            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Bed/bed-ruangan/" + kamar,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var MetaData = dataPoli = response.response_package.response_data;

                    if (MetaData !== undefined){
                        for(i = 0; i < MetaData.length; i++){
                            var selection = document.createElement("OPTION");

                            $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
                            $("#" + target_ui + "_bed").append(selection);
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            })
        }

        $(".inputan_konsul").select2();
        $(".inputan_inap").select2();
        $(".inputan_rujuk").select2();


        if(antrianData.poli_info !== null) {
            if(antrianData.poli_info.uid === __POLI_GIGI__ || antrianData.poli_info.uid === __POLI_ORTODONTIE__) {
                if(dataOdontogram === undefined)
                {
                    $(".ordo-top").each(function() {
                        var id = $(this).attr("id").split("_");
                        id = id[id.length - 1];
                        if(metaSelOrdo[id] === undefined)
                        {
                            metaSelOrdo[id] = {
                                "top" : {
                                    "tambal": "",
                                    "caries": false
                                },
                                "left" : {
                                    "tambal": "",
                                    "caries": false
                                },
                                "middle" : {
                                    "tambal": "",
                                    "caries": false
                                },
                                "right" : {
                                    "tambal": "",
                                    "caries": false
                                },
                                "bottom" : {
                                    "tambal": "",
                                    "caries": false
                                },
                                "mahkota": {
                                    "type": ""
                                },
                                "predefined": "",
                                "sel_akar": false,
                                "hilang": false,
                                "sisa_akar": false,
                                "fracture": false
                            };
                        }
                    });
                } else {
                    metaSelOrdo = JSON.parse(dataOdontogram);
                    // ParseView


                    for(var dbT in metaSelOrdo) {
                        //Render Result
                        $("#gigi_" + dbT + " .single_gigi_small .side_small").each(function() {
                            var settedPiece = $(this).attr("class").split(" ");
                            if(metaSelOrdo[dbT][settedPiece[0]].tambal !== "")
                            {
                                $(this).removeClass (function (index, className) {
                                    return (className.match (/(^|\s)modeset_\S+/g) || []).join(' ');
                                }).removeAttr("mode-class").removeAttr("mode-set");

                                if($(this).hasClass(settedPiece[0])) {
                                    var getModeSet = metaSelOrdo[dbT][settedPiece[0]].tambal.split("_");

                                    $(this).addClass("modeset_" + getModeSet[getModeSet.length - 1]).attr({
                                        "mode-class": metaSelOrdo[dbT][settedPiece[0]].tambal,
                                        "mode-set": "modeset_" + getModeSet[getModeSet.length - 1]
                                    });
                                }
                            } else {
                                $(this).removeClass (function (index, className) {
                                    return (className.match (/(^|\s)modeset_\S+/g) || []).join(' ');
                                }).removeAttr("mode-class").removeAttr("mode-set");
                            }
                        });

                        if(metaSelOrdo[dbT].hilang) {
                            setMord("#gigi_" + dbT + " .single_gigi_small .global_assigner_small", "<i class=\"fa fa-times text-danger\"></i>");
                        } else if(metaSelOrdo[dbT].fracture) {
                            setMord("#gigi_" + dbT + " .single_gigi_small .global_assigner_small", "<i class=\"fa fa-hashtag text-info\"></i>");
                        } else if(metaSelOrdo[dbT].sisa_akar) {
                            setMord("#gigi_" + dbT + " .single_gigi_small .global_assigner_small", "<i class=\"text-primary\">&radic;</i>");
                        } else {
                            setMord("#gigi_" + dbT + " .single_gigi_small .global_assigner_small", "", true);
                        }

                        if(metaSelOrdo[dbT].sel_akar) {
                            $("#gigi_" + dbT + " .perawatan_akar_sign_small").css({
                                "visibility": "visible"
                            });
                        } else {
                            $("#gigi_" + dbT + " .perawatan_akar_sign_small").css({
                                "visibility": "hidden"
                            });
                        }

                        if(metaSelOrdo[dbT].mahkota.type === "mahkota_logam") {
                            setMahkota("#gigi_" + dbT + " .single_gigi_small", "mahkota_logam", ["mahkota_nonlogam"]);
                        } else if(metaSelOrdo[dbT].mahkota.type === "mahkota_nonlogam") {
                            setMahkota("#gigi_" + dbT + " .single_gigi_small", "mahkota_nonlogam", ["mahkota_logam"]);
                        } else {
                            setMahkota("#gigi_" + dbT + " .single_gigi_small", "mahkota_logam", ["mahkota_logam, mahkota_nonlogam"], true);
                            setMahkota("#gigi_" + dbT + " .single_gigi_small", "mahkota_nonlogam", ["mahkota_logam, mahkota_nonlogam"], true);
                        }

                        $("#gigi_" + dbT + " .predefined_small").html(metaSelOrdo[dbT].predefined);
                    }
                }
            }
        }




        var selected_teeth = "";
        var odon_mode = "";
        var currentOrdonMeta = {};




        $(".ordo-top").click(function() {
            modeOrdo = "free";
            targetWarnaOrdo = "";
            $(".set_gigi tr").each(function() {
                $(this).fadeIn();
            });

            setMord(".global_assigner", "", true);

            $(".perawatan_akar_sign").css({
                "visibility": "hidden"
            });

            setMahkota(".single_gigi", "mahkota_logam", ["mahkota_nonlogam", "mahkota_logam"], true);
            setMahkota(".single_gigi", "mahkota_nonlogam", ["mahkota_nonlogam", "mahkota_logam"], true);
            setPredefined("#predefined", "", true);

            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            $("#target_gigi").html(id);

            //Jika Gigi Seri
            if(parseInt(id.substr(1,1)) < 4) {
                $(".single_gigi .middle").hide();
            } else {
                $(".single_gigi .middle").show();
            }

            selected_teeth = id;
            currentOrdonMeta = metaSelOrdo[selected_teeth];
            $(".set_gigi tr").removeClass("selected_ordon");
            //Render currentOrdoMeta
            $(".single_gigi .side").each(function() {
                var settedPiece = $(this).attr("class").split(" ");
                if(currentOrdonMeta[settedPiece[0]].tambal !== "")
                {
                    if($(this).hasClass(settedPiece[0])) {
                        var getModeSet = currentOrdonMeta[settedPiece[0]].tambal.split("_");
                        //$(".set_gigi tr#tambal_" + getModeSet[getModeSet.length - 1]).addClass("selected_ordon");
                        $(this).addClass("modeset_" + getModeSet[getModeSet.length - 1]).attr({
                            "mode-class": currentOrdonMeta[settedPiece[0]].tambal,
                            "mode-set": "modeset_" + getModeSet[getModeSet.length - 1]
                        });
                    }
                } else {
                    $(this).removeClass (function (index, className) {
                        return (className.match (/(^|\s)modeset_\S+/g) || []).join(' ');
                    }).removeAttr("mode-class").removeAttr("mode-set");
                }
            });

            if(currentOrdonMeta.hilang) {
                $(".set_gigi tr#gigi_hilang").addClass("selected_ordon");
                setMord(".global_assigner", "<i class=\"fa fa-times text-danger\"></i>");
            } else if(currentOrdonMeta.fracture) {
                $(".set_gigi tr#fracture").addClass("selected_ordon");
                setMord(".global_assigner", "<i class=\"fa fa-hashtag text-info\"></i>");
            } else if(currentOrdonMeta.sisa_akar) {
                $(".set_gigi tr#sisa_akar").addClass("selected_ordon");
                setMord(".global_assigner", "<i class=\"text-primary\">&radic;</i>");
            } else {
                setMord(".global_assigner", "", true);
            }

            if(currentOrdonMeta.sel_akar) {
                $(".set_gigi tr#sel_akar").addClass("selected_ordon");
                $(".perawatan_akar_sign").css({
                    "visibility": "visible"
                });
            } else {
                $(".perawatan_akar_sign").css({
                    "visibility": "hidden"
                });
            }

            if(currentOrdonMeta.mahkota.type === "mahkota_logam") {
                $(".set_gigi tr#mahkota_logam").addClass("selected_ordon");
                setMahkota(".single_gigi", "mahkota_logam", ["mahkota_nonlogam"]);
            } else if(currentOrdonMeta.mahkota.type === "mahkota_nonlogam") {
                $(".set_gigi tr#mahkota_nonlogam").addClass("selected_ordon");
                setMahkota(".single_gigi", "mahkota_nonlogam", ["mahkota_logam"]);
            } else {
                setMahkota(".single_gigi", "mahkota_nonlogam", ["mahkota_logam, mahkota_nonlogam"], true);
            }

            setPredefined("#predefined", currentOrdonMeta.predefined.toUpperCase());
            if(currentOrdonMeta.predefined !== "") {
                $(".set_gigi tr#" + currentOrdonMeta.predefined).addClass("selected_ordon");
            }

            $("#form-ordonto").modal("show");


        });

        function setMahkota(target_gigi, jenis = "mahkota_logam", removal_item = [], removal = false) {
            if(removal)
            {
                $("#" + jenis).removeClass("selected_ordon");
                $(target_gigi).removeClass("active_" + jenis);
            } else
            {
                $("#" + jenis).addClass("selected_ordon");
                $(target_gigi).addClass("active_" + jenis);
                for(var a in removal_item)
                {
                    $(target_gigi).removeClass("active_" + removal_item[a]);
                    $(removal_item[a]).removeClass("selected_ordon");
                }
            }
        }

        function setMord(target_gigi, targetMord, removal = false) {
            if(removal)
            {
                $(target_gigi).css({
                    "visibility": "hidden"
                });
                $(target_gigi).html("");
            } else {
                $(target_gigi).css({
                    "visibility": "visible"
                });
                $(target_gigi).html(targetMord);
            }
        }

        function setPredefined(target_predefined, defined, removal = false)
        {
            if(removal)
            {
                $(target_predefined).html("");
            } else {
                $(target_predefined).html(defined);
            }
        }

        var activeSelected;
        var modeOrdo = "free";
        var targetWarnaOrdo = "";

        $(".side").click(function() {
            var id = activeSelected.attr("id");
            var targetSide = $(this).attr("class").split(" ");
            var targetModeSet = $(this).attr("mode-set");

            if(id === "tambal_logam")
            {
                targetWarnaOrdo = "modeset_logam";
            }

            if(id === "tambal_emas")
            {
                targetWarnaOrdo = "modeset_emas";
            }

            if(id === "tambal_pencega")
            {
                targetWarnaOrdo = "modeset_pencega";
            }

            if(id === "tambal_sewarna")
            {
                targetWarnaOrdo = "modeset_sewarna";
            }

            if(id === "tambal_nonlogam")
            {
                targetWarnaOrdo = "modeset_nonlogam";
            }

            if(id === "car")
            {
                targetWarnaOrdo = "modeset_caries";
            }

            var targetModeClass = targetWarnaOrdo;

            if(targetModeSet === undefined) {
                //Clear old modeset
                $(this).removeClass (function (index, className) {
                    return (className.match (/(^|\s)modeset_\S+/g) || []).join(' ');
                }).removeAttr("mode-class").removeAttr("mode-set");

                $(this).attr({
                    "mode-set": id,
                    "mode-class" : targetWarnaOrdo
                });

                if(modeOrdo === "selection")
                {
                    $(this).addClass(targetWarnaOrdo);
                }
            } else {
                $(this).removeClass(targetWarnaOrdo).removeAttr("mode-class").removeAttr("mode-set");
            }
        });

        $(".set_gigi tr").click(function() {
            var id = $(this).attr("id");

            if($(this).hasClass("selected_ordon"))
            {
                $(this).removeClass("selected_ordon");
                if(
                    id === "une" ||
                    id === "pre" ||
                    id === "ano"
                )
                {
                    setPredefined("#predefined", id.toUpperCase(), true);
                    currentOrdonMeta.predefined = "";
                }

                if(
                    id === "mahkota_logam"
                )
                {
                    setMahkota(".single_gigi", id, ["mahkota_nonlogam"], true);
                    currentOrdonMeta.mahkota.type = "";
                }

                if(id === "mahkota_nonlogam")
                {
                    setMahkota(".single_gigi", id, ["mahkota_logam"], true);
                    currentOrdonMeta.mahkota.type = "";
                }

                if(id === "sel_akar"){
                    currentOrdonMeta.sel_akar = false;
                    $(".perawatan_akar_sign").css({
                        "visibility": "hidden"
                    });
                }

                if(id === "fracture"){
                    setMord(".global_assigner", "<i class=\"fa fa-hashtag text-info\"></i>", true);
                    currentOrdonMeta.fracture = false;
                }

                if(id === "gigi_hilang"){
                    setMord(".global_assigner", "<i class=\"fa fa-times text-danger\"></i>", true);
                    currentOrdonMeta.hilang = false;
                }

                if(id === "sisa_akar"){
                    setMord(".global_assigner", "<i class=\"text-primary\">&radic;</i>", true);
                    currentOrdonMeta.sisa_akar = false;
                }

                if($(this).hasClass("need_selection"))
                {
                    modeOrdo = "free";
                    $(".set_gigi tr").each(function() {
                        $(this).fadeIn();
                    });
                }

            } else {
                var GroupSel = $(this).attr("group-selection");
                if(GroupSel !== "")
                {
                    $("tr[group-selection=\"" + GroupSel + "\"]").each(function() {
                        $(this).removeClass("selected_ordon");
                    });
                    $(this).addClass("selected_ordon");
                }

                if(
                    id === "une" ||
                    id === "pre" ||
                    id === "ano"
                )
                {
                    setPredefined("#predefined", id.toUpperCase());
                    currentOrdonMeta.predefined = id;
                }

                if(
                    id === "mahkota_logam"
                )
                {
                    setMahkota(".single_gigi", id, ["mahkota_nonlogam"]);
                    currentOrdonMeta.mahkota.type = id;
                }

                if(id === "mahkota_nonlogam")
                {
                    setMahkota(".single_gigi", id, ["mahkota_logam"]);
                    currentOrdonMeta.mahkota.type = id;
                }

                if(id === "sel_akar")
                {
                    $(".perawatan_akar_sign").css({
                        "visibility": "visible"
                    });
                    currentOrdonMeta.sel_akar = true;
                }

                if(id === "fracture"){
                    setMord(".global_assigner", "<i class=\"fa fa-hashtag text-info\"></i>");
                    currentOrdonMeta.fracture = true;
                    currentOrdonMeta.hilang = false;
                    currentOrdonMeta.sisa_akar = false;
                }

                if(id === "gigi_hilang"){
                    setMord(".global_assigner", "<i class=\"fa fa-times text-danger\"></i>");
                    currentOrdonMeta.hilang = true;
                    currentOrdonMeta.fracture = false;
                    currentOrdonMeta.sisa_akar = false;
                }

                if(id === "sisa_akar"){
                    setMord(".global_assigner", "<i class=\"text-primary\">&radic;</i>");
                    currentOrdonMeta.sisa_akar = true;
                    currentOrdonMeta.hilang = false;
                    currentOrdonMeta.fracture = false;
                }

                if($(this).hasClass("need_selection"))
                {
                    modeOrdo = "selection";
                    $(".set_gigi tr").each(function() {
                        if($(this).attr("id") !== id) {
                            $(this).fadeOut();
                        } else {
                            activeSelected = $(this);
                        }
                    });
                }
            }
        });

        $("#btnUpdateOrdo").click(function() {
            //Save MSODL
            $(".single_gigi .side").each(function() {
                var tambal = $(this).attr("mode-class");
                var settedPiece = $(this).attr("class").split(" ");
                currentOrdonMeta[settedPiece[0]].tambal = (tambal === undefined) ? "" : tambal;
                currentOrdonMeta[settedPiece[0]].caries = "";
            });

            //Render Result
            $("#gigi_" + selected_teeth + " .single_gigi_small .side_small").each(function() {
                var settedPiece = $(this).attr("class").split(" ");
                if(currentOrdonMeta[settedPiece[0]].tambal !== "")
                {
                    $(this).removeClass (function (index, className) {
                        return (className.match (/(^|\s)modeset_\S+/g) || []).join(' ');
                    }).removeAttr("mode-class").removeAttr("mode-set");

                    if($(this).hasClass(settedPiece[0])) {
                        var getModeSet = currentOrdonMeta[settedPiece[0]].tambal.split("_");

                        $(this).addClass("modeset_" + getModeSet[getModeSet.length - 1]).attr({
                            "mode-class": currentOrdonMeta[settedPiece[0]].tambal,
                            "mode-set": "modeset_" + getModeSet[getModeSet.length - 1]
                        });
                    }
                } else {
                    $(this).removeClass (function (index, className) {
                        return (className.match (/(^|\s)modeset_\S+/g) || []).join(' ');
                    }).removeAttr("mode-class").removeAttr("mode-set");
                }
            });

            if(currentOrdonMeta.hilang) {
                setMord("#gigi_" + selected_teeth + " .single_gigi_small .global_assigner_small", "<i class=\"fa fa-times text-danger\"></i>");
            } else if(currentOrdonMeta.fracture) {
                setMord("#gigi_" + selected_teeth + " .single_gigi_small .global_assigner_small", "<i class=\"fa fa-hashtag text-info\"></i>");
            } else if(currentOrdonMeta.sisa_akar) {
                setMord("#gigi_" + selected_teeth + " .single_gigi_small .global_assigner_small", "<i class=\"text-primary\">&radic;</i>");
            } else {
                setMord("#gigi_" + selected_teeth + " .single_gigi_small .global_assigner_small", "", true);
            }

            if(currentOrdonMeta.sel_akar) {
                $("#gigi_" + selected_teeth + " .perawatan_akar_sign_small").css({
                    "visibility": "visible"
                });
            } else {
                $("#gigi_" + selected_teeth + " .perawatan_akar_sign_small").css({
                    "visibility": "hidden"
                });
            }

            if(currentOrdonMeta.mahkota.type === "mahkota_logam") {
                setMahkota("#gigi_" + selected_teeth + " .single_gigi_small", "mahkota_logam", ["mahkota_nonlogam"]);
            } else if(currentOrdonMeta.mahkota.type === "mahkota_nonlogam") {
                setMahkota("#gigi_" + selected_teeth + " .single_gigi_small", "mahkota_nonlogam", ["mahkota_logam"]);
            } else {
                setMahkota("#gigi_" + selected_teeth + " .single_gigi_small", "mahkota_logam", ["mahkota_logam, mahkota_nonlogam"], true);
                setMahkota("#gigi_" + selected_teeth + " .single_gigi_small", "mahkota_nonlogam", ["mahkota_logam, mahkota_nonlogam"], true);
            }

            $("#gigi_" + selected_teeth + " .predefined_small").html(currentOrdonMeta.predefined);

            metaSelOrdo[selected_teeth] = currentOrdonMeta;

            //Reset Operation
            currentOrdonMeta = {};
            modeOrdo = "free";
            targetWarnaOrdo = "";
            $(".set_gigi tr").each(function() {
                $(this).fadeIn();
            });
            setMord(".global_assigner", "", true);
            $(".perawatan_akar_sign").css({
                "visibility": "hidden"
            });
            setMahkota(".single_gigi", "mahkota_logam", ["mahkota_nonlogam", "mahkota_logam"], true);
            setMahkota(".single_gigi", "mahkota_nonlogam", ["mahkota_nonlogam", "mahkota_logam"], true);
            setPredefined("#predefined", "", true);
            $("#form-ordonto").modal("hide");
        });


        if($("#mata-loader").length > 0) {
            for(var mKey = 1; mKey <= 2; mKey++)
            {
                $("#mata-loader").append("<div style=\"position: relative; min-height: 280px; " + ((mKey == 1) ? "border-right: solid 1px  #000" : "border-left: solid 1px  #000") + "\" class=\"col-md-6 eye-side-" + mKey + "\">" +
                    /*"<div style=\"" + ((mKey === 1) ? " right: 40px" : "left: 40px") + "; position: absolute; top: -40px; background: url('" + __HOST__ + "images/protractor.png') no-repeat; width: 400px; height: 400px; background-size: contain; background-position: center\"></div>" +*/
                    "<div style=\"" + ((mKey === 1) ? " right: 40px" : "left: 40px") + "; position: absolute; top: -40px; width: 400px; height: 400px; background-size: contain; background-position: center\"></div>" +
                    "<div style=\"" + ((mKey === 1) ? " right: 140px" : "left: 140px") + ";\" class=\"ocular-mata\"></div>" +
                    "</div>");
            }

            $(".mata_input").inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            });
        }
    });

</script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>


<div id="form-editor-racikan" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Order Laboratorium</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12">
                    <label for="txt_racikan_obat">Obat:</label>
                    <select class="form-control" id="txt_racikan_obat"></select>
                </div>
                <!-- <div class="form-group col-md-6">
                    <label for="txt_racikan_jlh">Jumlah:</label>
                    <input type="text" class="form-control" id="txt_racikan_jlh" />
                </div> -->
                <div class="form-group col-md-12">
                    <div class="kolom_kekuatan">
                        <label for="txt_racikan_kekuatan">Kekuatan:</label>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control" id="txt_racikan_kekuatan" placeholder="0" />
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="kolom_takar" style="display: none">
                        <label for="txt_racikan_takar">Takar:</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" value="1" class="form-control" id="txt_racikan_takar_bulat" placeholder="0" />
                            </div>
                            <div class="col-md-1">
                                <i class="fa fa-plus" style="margin-top: 10px;"></i>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="txt_racikan_takar" placeholder="a/b" />
                            </div>
                            <div class="col-md-3">
                                <small>Cth:<br />2 + 1/2</small>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="form-group col-md-12">
                    <label for="txt_racikan_satuan">Satuan:</label>
                    <select class="form-control" id="txt_racikan_satuan"></select>
                </div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-primary" id="btnSubmitKomposisi">Order</button>
            </div>
        </div>
    </div>
</div>


<div id="form-tambah-order-lab" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Order Laboratorium</h5>
            </div>
            <div class="modal-body">
                <!--div class="col-md-12 row form-group">
                    <div class="col-md-3">
                        <label for="dr_penanggung_jawab_lab">Dokter Penanggung Jawab</label>
                    </div>
                    <div class="col-md-6">
                        <select class="form-control dr_penanggung_jawab" id="dr_penanggung_jawab_lab">

                        </select>
                    </div>
                </div-->
                <div class="col-md-12 row form-group">
                    <div class="col-md-3">
                        <label for="tindakan_lab">Tindakan</label>
                    </div>
                    <div class="col-md-6">
                        <select class="form-control" id="tindakan_lab">

                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-info" id="btnTambahTindakanLab">
                            <i class="fa fa-plus"></i> Tambah Tindakan Laboratorium
                        </button>
                    </div>
                </div>
                <div class="col-md-12 row">
                    <!-- <div class="col-md-3"></div> -->
                    <div class="offset-md-3 col-md-2" style="padding-top: 8px;" id="lab_tindakan_notifier"></div>
                </div>
                <div class="col-md-12">
                    <div id="lab_nilai_order" class="row">

                    </div>
                </div>
                <div class="col-md-12 form-group" style="margin-top: 10px;">
                    <table class="table table-bordered largeDataType" id="table_tindakan_lab">
                        <thead class="thead-dark">
                        <tr>
                            <th class="wrap_content">No</th>
                            <th>Tindakan Laboratorium</th>
                            <th>Harga</th>
                            <th width='8%' class="wrap_content">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-primary" id="btnSubmitOrderLab">Order</button>
            </div>
        </div>
    </div>
</div>


<div id="form-view-hasil-lab" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Hasil Laboratorium</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 row form-group">
                    <div class="col-md-3">
                        <label for="dr_penanggung_jawab_view_hasil">Dokter Penanggung Jawab</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="dr_penanggung_jawab_view_hasil" readonly>
                    </div>
                </div>
                <div class="col-md-12">
                    <div id="lab_nilai_order" class="row">

                    </div>
                </div>
                <div class="col-md-12">
                    <div id="lab_hasil_pemeriksaan">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>


<div id="compose-surat" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="target-judul-surat"></h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div id="load-parameter-surat"></div>
                    </div>
                    <div class="col-md-8">
                        <div class="document-editor__toolbar"></div>
                        <div class="document-editor row">
                            <div class="document-editor__editable-container">
                                <div class="dokumen-viewer ck-content" id="dokumen-viewer"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnCetakSurat">
                    <i class="fa fa-print"></i> Cetak
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-ban"></i> Kembali
                </button>
            </div>
        </div>
    </div>
</div>


<div id="form-konsul" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Konsul Poliklinik</h5>
            </div>
            <div class="modal-body">
                <div class="card card-form">
                    <div class="row no-gutters">
                        <div class="col-lg-12 card-body">
                            <div class="form-row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Pembayaran <span class="red">*</span></label>
                                    <select id="konsul_penjamin" class="form-control select2 inputan_konsul" required disabled>
                                        <option value="" disabled selected>Pilih Jenis Pembayaran</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Prioritas <span class="red">*</span></label>
                                    <select id="konsul_prioritas" class="form-control select2 inputan_konsul" required disabled>
                                        <option value="" disabled selected>Pilih Prioritas</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Poliklinik <span class="red">*</span></label>
                                    <select id="konsul_departemen" class="form-control select2 inputan_konsul" required>
                                        <option value="" disabled selected>Pilih Poliklinik</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Dokter <span class="red">*</span></label>
                                    <select id="konsul_dokter" class="form-control select2 inputan_konsul" required>
                                        <option value="" disabled selected>Pilih Dokter</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnProsesKonsul">
                    <i class="fa fa-check"></i> Proses Konsul
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>









<div id="form-inap" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Pindah Rawat Inap</h5>
            </div>
            <div class="modal-body" id="inap-container">
                <div class="card card-form">
                    <div class="row no-gutters">
                        <div class="col-lg-12 card-body">
                            <div class="form-row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Pembayaran <span class="red">*</span></label>
                                    <select id="inap_penjamin" class="form-control select2 inputan_inap" required disabled>
                                        <option value="" disabled selected>Pilih Jenis Pembayaran</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Dokter <span class="red">*</span></label>
                                    <select id="inap_dokter" class="form-control select2 inputan_inap" required>
                                        <option value="" disabled selected>Pilih Dokter</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Kamar <span class="red">*</span></label>
                                    <select id="inap_kamar" class="form-control select2 inputan_inap" required>
                                        <option value="" disabled selected>Pilih Kamar</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Bangsal <span class="red">*</span></label>
                                    <select id="inap_bed" class="form-control select2 inputan_inap" required>
                                        <option value="" disabled selected>Pilih Bangsal</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3" id="group_inap_tanggal_masuk">
                                    <label>Tanggal Masuk <span class="red">*</span></label>
                                    <input type="date" id="inap_tanggal_masuk" class="form-control input-group" required />
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Keterangan <span class="red">*</span></label>
                                    <input type="text" id="inap_keterangan" class="form-control" placeholder="Keterangan" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnProsesInap">
                    <i class="fa fa-check"></i> Pindah Rawat Inap
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>








<div id="form-rujuk" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Pengajuan Rujuk</h5>
            </div>
            <div class="modal-body">
                <div class="card card-form">
                    <div class="row no-gutters">
                        <div class="col-lg-12 card-body">
                            <div class="form-row">
                                <div class="col-12 col-md-6 form-group">
                                    <label>Pembayaran <span class="red">*</span></label>
                                    <select id="rujuk_penjamin" class="form-control select2 inputan_rujuk" required disabled>
                                        <option value="" disabled selected>Pilih Jenis Pembayaran</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-4 form-group">
                                    <label>Jenis Pelayanan <span class="red">*</span></label>
                                    <select id="rujuk_jenis" class="form-control select2 inputan_rujuk" required>
                                        <option value="2">Rawat Jalan</option>
                                        <option value="1">Rawat Inap</option>
                                    </select>
                                </div>
                                <div class="col-7 form-group">
                                    <label>Tipe Rujukan <span class="red">*</span></label>
                                    <select id="rujuk_tipe" class="form-control select2 inputan_rujuk" required>
                                        <option value="0" selected>Penuh</option>
                                        <option value="1" selected>Partial</option>
                                        <option value="2" selected>Rujuk Balik</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-12 form-group">
                                    <label>Catatan <span class="red">*</span></label>
                                    <textarea class="form-control" id="rujuk_catatan" style="min-height: 150px"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnProsesRujuk">
                    <i class="fa fa-check"></i> Ajukan Rujukan
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>





<div id="form-ordonto" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title"><i class="fa fa-hashtag"></i> <b id="target_gigi"></b></h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 row form-group">
                    <div class="col-md-4" style="position: relative; padding-top: 10px">
                        <h3 class="text-center" id="predefined" style="padding-bottom: 120px;">&nbsp;</h3>
                        <div class="single_gigi">
                            <div class="top side"></div>
                            <div class="left side"></div>
                            <div class="bottom side"></div>
                            <div class="right side"></div>
                            <div class="middle side"></div>
                            <div class="global_assigner fa"></div>
                        </div>
                        <div class="perawatan_akar_sign"></div>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-striped table-bordered set_gigi">
                            <tr id="une" group-selection="mordor1">
                                <td class="wrap_content">UNE</td>
                                <td>Belum Erupsi</td>
                            </tr>
                            <tr id="pre" group-selection="mordor1">
                                <td>PRE</td>
                                <td>Erupsi Sebagian</td>
                            </tr>
                            <tr id="ano" group-selection="mordor1">
                                <td>ANO</td>
                                <td>Anomali Bentuk</td>
                            </tr>
                            <tr id="car" class="need_selection">
                                <td class="wrap_content">
                                    <i class="fa fa-qrcode"></i>
                                </td>
                                <td>Caries</td>
                            </tr>
                            <tr id="mahkota_logam" group-selection="mordor2">
                                <td>
                                    <i class="fa fa-vector-square mlogam"></i>
                                </td>
                                <td>Mahkota Logam</td>
                            </tr>
                            <tr id="mahkota_nonlogam" group-selection="mordor2">
                                <td>
                                    <i class="fa fa-stop mnonlogam"></i>
                                </td>
                                <td>Mahkota Non Logam</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fa fa-arrow-left">
                                </td>
                                <td>Migrasi Kiri</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fa fa-arrow-right">
                                </td>
                                <td>Migrasi Kanan</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fa fa-reply">
                                </td>
                                <td>Rotasi Kiri</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fa fa-reply flip">
                                </td>
                                <td>Rotasi Kanan</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-striped table-bordered set_gigi">
                            <tr id="tambal_logam" class="need_selection" group-selection="mordor4">
                                <td class="wrap_content">
                                    <i class="fa fa-stop logam"></i>
                                </td>
                                <td>Tambalan Logam</td>
                            </tr>
                            <tr id="tambal_emas" class="need_selection" group-selection="mordor4">
                                <td class="wrap_content">
                                    <i class="fa fa-stop emas"></i>
                                </td>
                                <td>Tambalan Emas</td>
                            </tr>
                            <tr id="tambal_sewarna" class="need_selection" group-selection="mordor4">
                                <td class="wrap_content">
                                    <i class="fa fa-stop sewarna"></i>
                                </td>
                                <td>Tambalan Sewarna</td>
                            </tr>
                            <tr id="tambal_pencega" class="need_selection" group-selection="mordor4">
                                <td class="wrap_content">
                                    <i class="fa fa-stop pencega"></i>
                                </td>
                                <td>Tambalan Pencega</td>
                            </tr>
                            <tr id="tambal_nonlogam" class="need_selection" group-selection="mordor4">
                                <td class="wrap_content">
                                    <i class="fa fa-stop nonlogam"></i>
                                </td>
                                <td>Tambalan Non Logam</td>
                            </tr>
                            <tr id="sel_akar">
                                <td>
                                    <i class="fa fa-caret-down"></i>
                                </td>
                                <td>Perawatan Sal. Akar</td>
                            </tr>
                            <tr id="gigi_hilang" group-selection="mordor3">
                                <td>
                                    <i class="fa fa-times text-danger"></i>
                                </td>
                                <td>Gigi Hilang</td>
                            </tr>
                            <tr id="sisa_akar" group-selection="mordor3">
                                <td>
                                    <span class="text-primary">&radic;</span>
                                </td>
                                <td>Sisa Akar</td>
                            </tr>
                            <tr id="fracture" group-selection="mordor3">
                                <td><i class="fa fa-hashtag text-info"></i></td>
                                <td>Fracture</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnUpdateOrdo" class="btn btn-success">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>































<div id="form-jadwal-operasi" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Pengajuan Jadwal Operasi</h5>
            </div>
            <div class="modal-body">
                <div class="card card-form">
                    <div class="row no-gutters">
                        <div class="col-lg-12 card-body">
                            <div class="form-row">
                                <div class="col-12 col-md-6 form-group">
                                    <label>Tanggal Operasi <span class="red">*</span></label>
                                    <input type="date" id="operasi_tanggal" class="form-control" placeholder="Tentukan tanggal operasi" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-6 form-group">
                                    <div class="form-row">
                                        <div class="col-12 col-md-6 form-group">
                                            <label>Lama Operasi <span class="red">*</span></label>
                                            <input type="number" id="operasi_durasi" class="form-control" placeholder="Est. Durasi Operasi (Jam)" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-12 form-group">
                                    <label>Catatan <span class="red">*</span></label>
                                    <textarea class="form-control" id="rujuk_catatan" style="min-height: 150px"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnProsesRujuk">
                    <i class="fa fa-check"></i> Ajukan Rujukan
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>