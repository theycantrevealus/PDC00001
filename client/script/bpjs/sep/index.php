<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(function() {
        var selectedSEP = "",
            selectedSEPNo = "";
        var selectedLakaPenjamin = [];
        var isRujukan;

        var MODE = "ADD";

        $('#alert-sep-dt-kunjungan-container').hide();

        $("#jenis_pelayanan_dt_kunjungan").select2();
        $("#tgl_sep_dt_kunjungan").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        var getUrl = __BPJS_SERVICE_URL__ + "monitoring/sync.sh/datakunjungan?tglsep=" + $("#tgl_sep_dt_kunjungan").val() + "&jnspelayanan=" + $("#jenis_pelayanan_dt_kunjungan").val();

        $("#btn_search_dt_kunjungan").click(function() {
            $('#alert-sep-dt-kunjungan-container').fadeOut();
            getUrl = __BPJS_SERVICE_URL__ + "monitoring/sync.sh/datakunjungan?tglsep=" + $("#tgl_sep_dt_kunjungan").val() + "&jnspelayanan=" + $("#jenis_pelayanan_dt_kunjungan").val();
            MODE = "SEARCH";
            SEPList.ajax.url(getUrl).load();
        });

        var SEPList = $("#table-sep").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            serverMethod: "GET",
            "ajax": {
                url: getUrl,
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    refreshToken().then((test) => {
                        bpjs_token = test;
                    })

                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                dataSrc: function(response) {
                    if (parseInt(response.metadata.code) !== 200) {
                        if (MODE === "SEARCH") {
                            $('#alert-sep-dt-kunjungan').text(response.metadata.message);
                            $('#alert-sep-dt-kunjungan-container').fadeIn();
                        }
                        return [];
                    } else {
                        $('#alert-sep-dt-kunjungan-container').fadeOut();
                        return response.response;
                    }
                }
            },
            autoWidth: false,
            "bInfo": false,
            lengthMenu: [
                [-1],
                ["All"]
            ],
            aaSorting: [
                [0, "asc"]
            ],
            "columnDefs": [{
                "targets": 0,
                "className": "dt-body-left"
            }],
            "columns": [{
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.noSep;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.tglSep;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.nama;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.noKartu;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return (row.noRujukan !== undefined && row.noRujukan !== null) ? row.noRujukan : "-";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.jnsPelayanan;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.poli;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.diagnosa;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return (row.tglPlgSep !== undefined && row.tglPlgSep !== null) ? row.tglPlgSep : "-";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button class=\"btn btn-warning btn-sm btn-cetak-sep\" title=\"Cetak SEP\" id=\"" + row.noSep + "\">" +
                            "<i class=\"fa fa-print\"></i> Cetak" +
                            "</button>" +
                            "<button class=\"btn btn-info btn-sm btn-edit-sep\" title=\"Edit SEP\" id=\"" + row.noSep + "\">" +
                            "<i class=\"fa fa-pencil-alt\"></i> Edit" +
                            "</button>" +
                            "<button class=\"btn btn-danger btnHapusSEP\" title=\"Hapus SEP\" data-sep=\"" + row.noSep + "\" id=\"" + row.noSep + "\"><i class=\"fa fa-trash\"></i> Hapus</button>" +
                            "</div>";
                    }
                }
            ]
        });

        $("#txt_bpjs_rujuk_tipe_rujukan").select2();
        $("#txt_bpjs_rujuk_jenis_faskes").select2();
        $("#txt_bpjs_rujuk_jenis_pelayanan").select2();


        $("body").on("click", ".btn-cetak-sep", function() {
            var no_sep = $(this).attr("id");

            var SEPButton = $(this);
            SEPButton.html("Memuat SEP...").removeClass("btn-success").addClass("btn-warning");
            // $("#modal-sep-cetak").modal("show");

            $.ajax({
                url: __BPJS_SERVICE_URL__ + "sep/sync.sh/carisep?nomorsep=" + no_sep,
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    refreshToken().then((test) => {
                        bpjs_token = test;
                    })

                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                success: function(response) {

                    var dataSEP = response.response;
                    $("#sep_nomor").html(dataSEP.noSep);
                    $("#sep_tanggal").html(dataSEP.tglSep);

                    $("#sep_nomor_kartu").html(dataSEP.peserta.noKartu);
                    $("#sep_nama_peserta").html(dataSEP.peserta.nama + " <b class=\"text-info\">[No. Mr " + dataSEP.peserta.noMr + "]</b>");
                    $("#sep_tanggal_lahir").html(dataSEP.peserta.tglLahir);
                    $("#sep_nomor_telepon").html('-');
                    $("#sep_faskes_asal").html('-');
                    $("#sep_spesialis").html(dataSEP.poli);
                    $("#sep_diagnosa_awal").html(dataSEP.diagnosa);
                    $("#sep_catatan").html(dataSEP.catatan);

                    $("#sep_peserta").html(dataSEP.peserta.jnsPeserta);
                    $("#sep_cob").html((dataSEP.cob === '0') ? "0. Tidak" : "1. Ya");
                    $("#sep_jenis_rawat").html(dataSEP.jnsPelayanan);
                    $("#sep_kelas_rawat").html(dataSEP.kelasRawat);


                    // $("#sep_faskes_asal").html(((dataSEP.asal_rujukan_ppk !== null && dataSEP.asal_rujukan_ppk !== undefined) ? dataSEP.asal_rujukan_ppk : '[TIDAK DITEMUKAN]') + " - " + ((dataSEP.asal_rujukan_nama !== undefined && dataSEP.asal_rujukan_nama !== null && dataSEP.asal_rujukan_nama !== "null") ? dataSEP.asal_rujukan_nama : "[TIDAK DITEMUKAN]") + "<b class=\"text-info\">[No. Rujuk: " + ((dataSEP.asal_rujukan_nomor !== undefined && dataSEP.asal_rujukan_nomor !== null && dataSEP.asal_rujukan_nomor !== '') ? dataSEP.asal_rujukan_nomor : '-') + "]");

                    $("#modal-sep-cetak").modal("show");
                    SEPButton.html("<i class=\"fa fa-print\"></i> Cetak").removeClass("btn-warning").addClass("btn-success");
                },
                error: function(response) {
                    //
                }
            });
        });

        $("#btnCetakSEP").click(function() {
            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/bpjs_sep.php",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    __PC_CUSTOMER__: __PC_CUSTOMER__,
                    html_data_kiri: $("#data_sep_cetak_kiri").html(),
                    html_data_kanan: $("#data_sep_cetak_kanan").html(),
                    html_data_bawah: $("#data_sep_cetak_bawah").html()
                },
                success: function(response) {
                    //$("#dokumen-viewer").html(response);
                    var containerItem = document.createElement("DIV");
                    $(containerItem).html(response);
                    $(containerItem).printThis({
                        importCSS: true,
                        base: false,
                        pageTitle: "Cetak SEP",
                        afterPrint: function() {
                            //
                        }
                    });
                }
            });
        });

        $("body").on("click", ".btnHapusSEP", function() {
            var no_sep = $(this).attr("id");
            // var data_sep = $(this).attr("data-sep");
            // console.log(data_sep);

            Swal.fire({
                title: "Hapus SEP?",
                showDenyButton: true,
                confirmButtonText: "Ya. Hapus",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: __BPJS_SERVICE_URL__ + "sep/sync.sh/deletesep",
                        type: "DELETE",
                        dataType: "json",
                        crossDomain: true,
                        beforeSend: async function(request) {
                            refreshToken().then((test) => {
                                bpjs_token = test;
                            })

                            request.setRequestHeader("Accept", "application/json");
                            request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            request.setRequestHeader("x-token", bpjs_token);
                        },
                        data: {
                            "t_sep": {
                                "noSep": no_sep,
                                "user": __MY_NAME__
                            }
                        },
                        success: function(response) {
                            console.clear();
                            console.log(response);
                            if (parseInt(response.metadata.code) === 200) {
                                Swal.fire(
                                    'BPJS',
                                    'SEP Berhasil dihapus',
                                    'success'
                                ).then((result) => {
                                    SEPList.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    'BPJS',
                                    response.metadata.message,
                                    'error'
                                ).then((result) => {
                                    SEPList.ajax.reload();
                                });
                            }
                        },
                        error: function(response) {
                            console.clear();
                            console.log(response);
                        }
                    });
                }
            });
        });


        $("body").on("click", "#btnTest", function() {
            $("#modal-sep").modal("show");
        });

        $("body").on("click", ".btn-edit-sep", function() {
            var no_sep = $(this).attr("id");

            var SEPButton = $(this);
            SEPButton.html("Memuat SEP...").removeClass("btn-info").addClass("btn-warning");

            $.ajax({
                url: __BPJS_SERVICE_URL__ + "sep/sync.sh/carisep?nomorsep=" + no_sep,
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    refreshToken().then((test) => {
                        bpjs_token = test;
                    })

                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                success: function(response) {
                    var data = response.response;

                    console.clear();
                    console.log(data);

                    $("#txt_bpjs_no_sep").val(data.noSep);
                    $("#txt_bpjs_tgl_sep").val(data.tglSep);
                    $("#txt_bpjs_nama").val(data.peserta.nama);
                    $("#txt_bpjs_nomor").val(data.peserta.noKartu);
                    $("#txt_bpjs_rm").val(data.peserta.noMr);

                    $("#txt_bpjs_faskes").select2();
                    $("#txt_bpjs_jenis_layanan").select2();
                    if (data.jnsPelayanan === "Rawat Jalan") {
                        var jns_layan = "2";
                    } else {
                        var jns_layan = "1";
                    }
                    $("#txt_bpjs_jenis_layanan option[value=\"" + jns_layan + "\"]").prop("selected", true);
                    $("#txt_bpjs_jenis_layanan").trigger("change");

                    $("#txt_bpjs_kelas_rawat").select2();
                    $("#txt_bpjs_kelas_rawat option[value=\"" + data.klsRawat.klsRawatHak + "\"]").prop("selected", true);
                    $("#txt_bpjs_kelas_rawat").trigger("change");
                    $("#txt_bpjs_kelas_rawat").select2({
                        disabled: "readonly"
                    });

                    $("input[type=\"radio\"][name=\"radio_kelas_rawat_naik\"]").change(function() {
                        if (parseInt($(this).val()) === 1) {
                            $(".kelas_rawat_naik_container").fadeIn();
                        } else {
                            $(".kelas_rawat_naik_container").fadeOut();
                        }
                    });

                    if (parseInt(data.klsRawat.klsRawatNaik) > 0) {
                        $("input[name=\"radio_kelas_rawat_naik\"][value=\"1\"]").prop("checked", true);
                        $(".kelas_rawat_naik_container").show();
                    } else {
                        $("input[name=\"radio_kelas_rawat_naik\"][value=\"0\"]").prop("checked", true);
                        $(".kelas_rawat_naik_container").hide();
                    }

                    $("#txt_bpjs_kelas_rawat_naik").select2();
                    $("#txt_bpjs_kelas_rawat_naik option[value=\"" + data.klsRawat.klsRawatNaik + "\"]").prop("selected", true);
                    $("#txt_bpjs_kelas_rawat_naik").trigger("change");

                    $("#txt_bpjs_kelas_rawat_naik_pembiayaan").select2();
                    $("#txt_bpjs_kelas_rawat_naik_pembiayaan option[value=\"" + data.klsRawat.pembiayaan + "\"]").prop("selected", true);
                    $("#txt_bpjs_kelas_rawat_naik_pembiayaan").trigger("change");

                    $("#txt_bpjs_poli_tujuan").select2({
                        minimumInputLength: 2,
                        "language": {
                            "noResults": function() {
                                return "Poli tidak ditemukan";
                            }
                        },
                        dropdownParent: $("#group_poli"),
                        ajax: {
                            url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getpoli`,
                            type: "GET",
                            dataType: "json",
                            crossDomain: true,
                            beforeSend: async function(request) {
                                request.setRequestHeader("Accept", "application/json");
                                request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                request.setRequestHeader("x-token", bpjs_token);
                            },
                            data: function(term) {
                                return {
                                    kode: term.term
                                };
                            },
                            processResults: function(response) {
                                var data = response.response;
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.nama,
                                            id: item.kode
                                        }
                                    })
                                };
                            }
                        }
                    }).addClass("form-control").on("select2:select", function(e) {
                        //
                    });


                    $("#txt_bpjs_poli_tujuan").append("<option>" + data.poli + "</option>");
                    $("#txt_bpjs_poli_tujuan").select2("data", {
                        // id: data.poli_tujuan_detail.kode,
                        text: data.poli
                    });
                    $("#txt_bpjs_poli_tujuan").trigger("change");

                    $("input[name=\"txt_bpjs_poli_eksekutif\"][value=\"" + data.pasien_cob + "\"]").prop("checked", true);

                    $("#txt_bpjs_tujuan_kunjungan").select2();
                    $("#txt_bpjs_tujuan_kunjungan option[value=\"" + data.tujuanKunj.kode + "\"]").prop("selected", true);
                    $("#txt_bpjs_tujuan_kunjungan").trigger("change");
                    $("#txt_bpjs_tujuan_kunjungan").select2({
                        disabled: "readonly"
                    });

                    if (data.tujuanKunj.kode == '0') {
                        $(".group_txt_bpjs_flag_procedure").hide();
                        $(".group_txt_bpjs_kode_penunjang").hide();
                    } else {
                        $(".group_txt_bpjs_flag_procedure").show();
                        $(".group_txt_bpjs_kode_penunjang").show();
                    }

                    if (data.tujuanKunj.kode == '0' || data.tujuanKunj.kode == '2') {
                        $(".group_txt_bpjs_asesmen_pelayanan").show();
                    } else {
                        $(".group_txt_bpjs_asesmen_pelayanan").hide();
                    }

                    $("#txt_bpjs_flag_procedure").select2();
                    $("#txt_bpjs_flag_procedure option[value=\"" + data.flagProcedure.kode + "\"]").prop("selected", true);
                    $("#txt_bpjs_flag_procedure").trigger("change");
                    $("#txt_bpjs_flag_procedure").select2({
                        disabled: "readonly"
                    });

                    $("#txt_bpjs_kode_penunjang").select2();
                    $("#txt_bpjs_kode_penunjang option[value=\"" + data.kdPenunjang.kode + "\"]").prop("selected", true);
                    $("#txt_bpjs_kode_penunjang").trigger("change");
                    $("#txt_bpjs_kode_penunjang").select2({
                        disabled: "readonly"
                    });

                    $("#txt_bpjs_asesmen_pelayanan").select2();
                    $("#txt_bpjs_asesmen_pelayanan option[value=\"" + data.assestmenPel.kode + "\"]").prop("selected", true);
                    $("#txt_bpjs_asesmen_pelayanan").trigger("change");
                    $("#txt_bpjs_asesmen_pelayanan").select2({
                        disabled: "readonly"
                    });

                    $("#txt_bpjs_diagnosa_awal").select2({
                        minimumInputLength: 2,
                        "language": {
                            "noResults": function() {
                                return "Diagnosa tidak ditemukan";
                            }
                        },
                        dropdownParent: $("#group_diagnosa"),
                        ajax: {
                            url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getdiagnosa`,
                            type: "POST",
                            dataType: "json",
                            crossDomain: true,
                            beforeSend: async function(request) {
                                refreshToken().then((test) => {
                                    bpjs_token = test;
                                })

                                request.setRequestHeader("Accept", "application/json");
                                request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                request.setRequestHeader("x-token", bpjs_token);
                            },
                            data: function(term) {
                                return {
                                    kode: term.term
                                };
                            },
                            cache: true,
                            processResults: function(response) {
                                var data = response.response;
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.nama,
                                            id: item.kode
                                        }
                                    })
                                };
                            }
                        }
                    }).addClass("form-control").on("select2:select", function(e) {
                        //
                    });
                    $("#txt_bpjs_diagnosa_awal").append("<option>" + data.diagnosa + "</option>");
                    $("#txt_bpjs_diagnosa_awal").select2("data", {
                        // id: data.diagnosa_kode,
                        text: data.diagnosa
                    });
                    $("#txt_bpjs_diagnosa_awal").trigger("change");

                    $("#txt_bpjs_catatan").val(data.catatan);

                    loadSpesialistik("#txt_bpjs_dpjp_spesialistik");
                    $("#txt_bpjs_dpjp_spesialistik").select2();

                    $("#txt_bpjs_dpjp_spesialistik").select2({
                        dropdownParent: $("#group_spesialistik")
                    });

                    $("#txt_bpjs_jenis_layanan").change(function() {
                        loadDPJP("#txt_bpjs_dpjp", $("#txt_bpjs_jenis_layanan").val(), $("#txt_bpjs_dpjp_spesialistik").val());
                    });

                    $("#txt_bpjs_dpjp_spesialistik").change(function() {
                        loadDPJP("#txt_bpjs_dpjp", $("#txt_bpjs_jenis_layanan").val(), $("#txt_bpjs_dpjp_spesialistik").val());
                    });

                    $("#txt_bpjs_dpjp").select2({
                        "language": {
                            "noResults": function() {
                                return "DPJP tidak ditemukan";
                            }
                        },
                        dropdownParent: $("#group_dpjp")
                    });

                    $("input[name=\"txt_bpjs_cob\"][value=\"" + data.cob + "\"]").prop("checked", true);
                    $("input[name=\"txt_bpjs_katarak\"][value=\"" + data.katarak + "\"]").prop("checked", true);

                    $("#txt_bpjs_laka").select2();
                    $("#txt_bpjs_laka option[value=\"" + data.kdStatusKecelakaan + "\"]").prop("selected", true);
                    $("#txt_bpjs_laka").trigger("change");

                    if (parseInt(data.kdStatusKecelakaan) > 0) {
                        $(".laka_lantas_container").show();
                    } else {
                        $(".laka_lantas_container").hide();
                    }
                    if (parseInt(data.kdStatusKecelakaan) > 0) {
                        $(".laka_lantas_suplesi_container").show();
                    } else {
                        $(".laka_lantas_suplesi_container").hide();
                    }

                    $("#txt_bpjs_laka_tanggal").val(data.lokasiKejadian.tglKejadian);

                    $("#txt_bpjs_laka_tanggal").datepicker({
                        dateFormat: "yy-mm-dd",
                        autoclose: true
                    }).datepicker("setDate", new Date());

                    $("#txt_bpjs_laka_keterangan").val(data.lokasiKejadian.ketKejadian);
                    // $("input[name=\"txt_bpjs_laka_suplesi\"][value=\"" + data.laka_lantas_suplesi + "\"]").prop("checked", true);
                    // $("#txt_bpjs_laka_suplesi_nomor").val(data.laka_lantas_suplesi_sep);

                    var prov = loadProvinsi("#txt_bpjs_laka_suplesi_provinsi", data.lokasiKejadian.kdProp);
                    var kab = loadKabupaten("#txt_bpjs_laka_suplesi_kabupaten", $("#txt_bpjs_laka_suplesi_provinsi").val(), data.lokasiKejadian.kdKab);
                    var kec = loadKecamatan("#txt_bpjs_laka_suplesi_kecamatan", $("#txt_bpjs_laka_suplesi_kabupaten").val(), data.lokasiKejadian.kdKec);

                    $("#txt_bpjs_laka_suplesi_provinsi").select2({
                        dropdownParent: $("#group_provinsi"),
                        data: {
                            id: data.lokasiKejadian.kdProp,
                            text: prov
                        }
                    });


                    $("#txt_bpjs_laka_suplesi_kabupaten").select2({
                        dropdownParent: $("#group_kabupaten"),
                        data: {
                            id: data.lokasiKejadian.kdKab,
                            text: kab
                        }
                    });

                    $("#txt_bpjs_laka_suplesi_kecamatan").select2({
                        dropdownParent: $("#group_kecamatan"),
                        data: {
                            id: data.lokasiKejadian.kdKec,
                            text: kec
                        }
                    });



                    $("#txt_bpjs_kelas_rawat").select2({
                        dropdownParent: $("#group_kelas_rawat")
                    });

                    SEPButton.html("<i class=\"fa fa-pencil-alt\"></i> Edit").removeClass("btn-warning").addClass("btn-info");

                    $("#modal-sep").modal("show");

                },
                error: function(response) {
                    //
                }
            });
        });

        $(".kelas_rawat_naik_container").hide();
        $("input[type=\"radio\"][name=\"radio_kelas_rawat_naik\"]").change(function() {
            if (parseInt($(this).val()) === 1) {
                $(".kelas_rawat_naik_container").fadeIn();
            } else {
                $(".kelas_rawat_naik_container").fadeOut();
            }
        });

        $(".laka_lantas_container").hide();
        $("#txt_bpjs_laka").change(function() {
            if (parseInt($("#txt_bpjs_laka option:selected").val()) !== 0) {
                $(".laka_lantas_container").fadeIn();
            } else {
                $(".laka_lantas_container").fadeOut();
            }
        });

        $("input[type=\"radio\"][name=\"txt_bpjs_laka_suplesi\"]").change(function() {
            if (parseInt($(this).val()) === 1) {
                $(".laka_lantas_suplesi_container").fadeIn();
            } else {
                $(".laka_lantas_suplesi_container").fadeOut();
            }
        });

        $("#btnProsesSEP").click(function() {
            Swal.fire({
                title: 'Data sudah benar?',
                showDenyButton: true,
                confirmButtonText: `Sudah`,
                denyButtonText: `Belum`,
            }).then((result) => {
                if (result.isConfirmed) {
                    dataSetSEP = {
                        "t_sep": {
                            "noSep": $("#txt_bpjs_no_sep").val(),
                            "klsRawat": {
                                "klsRawatHak": $("#txt_bpjs_kelas_rawat option:selected").val(),
                                "klsRawatNaik": $("#txt_bpjs_kelas_rawat_naik option:selected").val(),
                                "pembiayaan": $("#txt_bpjs_kelas_rawat_naik_pembiayaan option:selected").val(),
                                "penanggungJawab": $("#txt_bpjs_kelas_rawat_naik_pembiayaan option:selected").text()
                            },
                            "noMR": $("#txt_bpjs_rm").val(),
                            "catatan": $("#txt_bpjs_catatan").val(),
                            "diagAwal": $("#txt_bpjs_diagnosa_awal").val(),
                            "poli": {
                                "tujuan": $("#txt_bpjs_poli_tujuan").val(),
                                "eksekutif": $("input[type=\"radio\"][name=\"txt_bpjs_poli_eksekutif\"]:checked").val()
                            },
                            "cob": {
                                "cob": $("input[type=\"radio\"][name=\"txt_bpjs_cob\"]:checked").val()
                            },
                            "katarak": {
                                "katarak": $("input[type=\"radio\"][name=\"txt_bpjs_katarak\"]:checked").val()
                            },
                            "jaminan": {
                                "lakaLantas": $("#txt_bpjs_laka option:selected").val(),
                                "noLP": $("#txt_bpjs_laka_no_lp").val(),
                                "penjamin": {
                                    "tglKejadian": $("#txt_bpjs_laka_tanggal").val(),
                                    "keterangan": $("#txt_bpjs_laka_keterangan").val(),
                                    "suplesi": {
                                        "suplesi": $("input[type=\"radio\"][name=\"txt_bpjs_laka_suplesi\"]:checked").val(),
                                        "noSepSuplesi": $("#txt_bpjs_laka_suplesi_nomor").val(),
                                        "lokasiLaka": {
                                            "kdPropinsi": $("#txt_bpjs_laka_suplesi_provinsi").val(),
                                            "kdKabupaten": $("#txt_bpjs_laka_suplesi_kabupaten").val(),
                                            "kdKecamatan": $("#txt_bpjs_laka_suplesi_kecamatan").val()
                                        }
                                    }
                                }
                            },
                            "dpjpLayan": $("#txt_bpjs_dpjp").val(),
                            "noTelp": $("#txt_bpjs_telepon").val(),
                            "user": __MY_NAME__
                        }
                    };

                    $.ajax({
                        url: __BPJS_SERVICE_URL__ + "sep/sync.sh/updatesep",
                        type: "PUT",
                        dataType: "json",
                        crossDomain: true,
                        beforeSend: async function(request) {
                            refreshToken().then((test) => {
                                bpjs_token = test;
                            })

                            request.setRequestHeader("Accept", "application/json");
                            request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            request.setRequestHeader("x-token", bpjs_token);
                        },
                        data: dataSetSEP,
                        success: function(response) {
                            SEPList.ajax.reload();
                            if (parseInt(response.metadata.code) === 200) {
                                Swal.fire(
                                    "Edit SEP Berhasil!",
                                    "SEP telah diedit",
                                    "success"
                                ).then((result) => {
                                    $("#modal-sep").modal("hide");
                                });
                            } else {
                                Swal.fire(
                                    "Gagal buat SEP",
                                    response.metadata.message,
                                    "warning"
                                ).then((result) => {
                                    console.log(response);
                                });
                            }
                        },
                        error: function(response) {
                            Swal.fire(
                                "Edit SEP",
                                'Aksi Gagal',
                                "error"
                            ).then((result) => {
                                //
                            });
                            console.log(response);
                        }
                    });
                } else if (result.isDenied) {}
            });
        });


        function loadSpesialistik(target, selected = {
            kode: "",
            nama: ""
        }, dpjp = {
            kode: "",
            nama: ""
        }) {
            $.ajax({
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getspesialis`,
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                success: function(response) {
                    var data = response.response;

                    $(target + " option").remove();
                    for (var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");
                        if (data[a].kode === selected.kode) {
                            $(selection).attr({
                                "selected": "selected"
                            });
                        }
                        $(selection).attr("value", data[a].kode).html(data[a].nama);
                        $(target).append(selection);
                    }
                    loadDPJP("#txt_bpjs_dpjp", $("#txt_bpjs_jenis_layanan").val(), $(target).val(), dpjp);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function loadDPJP(target, jenis, spesialistik, selected = {
            kode: "",
            nama: ""
        }) {
            $.ajax({
                url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getdokter?jnspelayanan=" + jenis + "&tglpelayanan=" + $('#txt_bpjs_tgl_sep').val() + "&kode=" + spesialistik,
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                success: function(response) {
                    var data = [];
                    if (response.response !== undefined && response.response !== null) {
                        data = response.response;
                    }

                    $(target + " option").remove();
                    for (var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");

                        if (data[a].kode === selected.kode) {
                            $(selection).attr({
                                "selected": "selected"
                            });
                        }

                        $(selection).attr("value", data[a].kode).html(data[a].kode + " - " + data[a].nama);
                        $(target).append(selection);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        loadProvinsi("#txt_bpjs_laka_suplesi_provinsi");
        loadKabupaten("#txt_bpjs_laka_suplesi_kabupaten", $("#txt_bpjs_laka_suplesi_provinsi option:selected").val());
        loadKecamatan("#txt_bpjs_laka_suplesi_kecamatan", $("#txt_bpjs_laka_suplesi_kabupaten option:selected").val());

        $("#txt_bpjs_laka_suplesi_provinsi").select2({
            dropdownParent: $("#group_provinsi")
        });

        $("#txt_bpjs_laka_suplesi_kabupaten").select2({
            dropdownParent: $("#group_kabupaten")
        });

        $("#txt_bpjs_laka_suplesi_kecamatan").select2({
            dropdownParent: $("#group_kecamatan")
        });

        $("#txt_bpjs_laka_suplesi_provinsi").on("change", function() {
            loadKabupaten("#txt_bpjs_laka_suplesi_kabupaten", $("#txt_bpjs_laka_suplesi_provinsi").val());
        });

        $("#txt_bpjs_laka_suplesi_kabupaten").on("change", function() {
            loadKecamatan("#txt_bpjs_laka_suplesi_kecamatan", $("#txt_bpjs_laka_suplesi_kabupaten").val());
        });

        function loadProvinsi(target, selected = "") {
            var selectedNama = "";
            $.ajax({
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getprov`,
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                success: function(response) {
                    var data = response.response;

                    $(target + " option").remove();

                    for (var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");

                        if (parseInt(data[a].kode) === parseInt(selected)) {
                            selectedNama = data[a].nama;
                            $(selection).attr({
                                "selected": "selected"
                            });
                        }
                        $(selection).attr("value", data[a].kode).html(data[a].nama);
                        $(target).append(selection);
                    }
                    loadKabupaten("#txt_bpjs_laka_suplesi_kabupaten", $("#txt_bpjs_laka_suplesi_provinsi option:selected").val());
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return selectedNama;
        }

        function loadKabupaten(target, provinsi, selected = "") {
            var selectedNama = "";
            $.ajax({
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getkab?kodeprov=` + provinsi,
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                success: function(response) {
                    var data = response.response;

                    $(target + " option").remove();
                    for (var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");
                        if (parseInt(data[a].kode) === parseInt(selected)) {
                            selectedNama = data[a].nama;
                            $(selection).attr({
                                "selected": "selected"
                            });
                        }
                        $(selection).attr("value", data[a].kode).html(data[a].nama);
                        $(target).append(selection);
                    }
                    loadKecamatan("#txt_bpjs_laka_suplesi_kecamatan", $("#txt_bpjs_laka_suplesi_kabupaten option:selected").val());
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return selectedNama;
        }

        function loadKecamatan(target, kabupaten, selected = "") {
            var selectedNama = "";
            $.ajax({
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getkec?kodekab=` + kabupaten,
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                success: function(response) {
                    var data = response.response;

                    $(target + " option").remove();
                    for (var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");
                        if (parseInt(data[a].kode) === parseInt(selected)) {
                            selectedNama = data[a].nama;
                            $(selection).attr({
                                "selected": "selected"
                            });
                        }
                        $(selection).attr("value", data[a].kode).html(data[a].nama);
                        $(target).append(selection);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return selectedNama;
        }

    });
</script>

<div id="modal-sep" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Surat Eligibilitas Peserta
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <div class="form-row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Pasien</h5>
                                </div>
                                <div class="card-body row">
                                    <div class="col-3 form-group">
                                        <label for="">No. SEP</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_no_sep" readonly>
                                    </div>
                                    <div class="col-3 form-group">
                                        <label for="">Tanggal SEP</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_tgl_sep" readonly">
                                    </div>
                                    <div class="col-3 form-group">
                                        <label for="">Nama Pasien</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
                                    </div>
                                    <div class="col-3 form-group">
                                        <label for="">No. Kartu</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nomor" readonly>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Rujukan</h5>
                                </div>
                                <div class="card-body row">
                                    <div class="col-6">
                                        <div class="col-12 form-group">
                                            <label for="">Nomor Medical Rahecord (MR)</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_rm" readonly>
                                        </div>
                                        <div class="col-12 form-group">
                                            <label for="">Faskes</label>
                                            <select class="form-control sep" id="txt_bpjs_faskes" disabled>
                                                <option value="<?php echo __KODE_PPK__; ?>">RSUD PETALA BUMI - KOTA PEKAN BARU</option>
                                            </select>
                                        </div>

                                        <div class="col-12 form-group">
                                            <label for="">Jenis Pelayanan</label>
                                            <select class="form-control sep" id="txt_bpjs_jenis_layanan">
                                                <option value="1">Rawat Inap</option>
                                                <option value="2">Rawat Jalan</option>
                                            </select>
                                        </div>
                                        <div class="col-12 mb-9 form-group" id="group_kelas_rawat">
                                            <label for="">Kelas Rawat Hak</label>
                                            <select class="form-control" id="txt_bpjs_kelas_rawat">
                                                <option value="1">Kelas 1</option>
                                                <option value="2">Kelas 2</option>
                                                <option value="3">Kelas 3</option>
                                            </select>
                                        </div>
                                        <div class="col-12 form-group">
                                            <label for="">Kelas Rawat Naik</label>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="radio_kelas_rawat_naik" value="0" checked />
                                                        <label class="form-check-label">
                                                            Tidak
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="radio_kelas_rawat_naik" value="1" />
                                                        <label class="form-check-label">
                                                            Ya
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="kelas_rawat_naik_container">
                                            <div class="col-12 mb-9 form-group" id="group_kelas_rawat_naik">
                                                <label for="">Kelas Rawat Naik</label>
                                                <select class="form-control" id="txt_bpjs_kelas_rawat_naik">
                                                    <option value="" selected></option>
                                                    <option value="1">VVIP</option>
                                                    <option value="2">VIP</option>
                                                    <option value="3">Kelas 1</option>
                                                    <option value="4">Kelas 2</option>
                                                    <option value="5">Kelas 3</option>
                                                    <option value="6">ICCU</option>
                                                    <option value="7">Diatas Kelas 1</option>
                                                </select>
                                            </div>
                                            <div class="col-12 mb-9 form-group" id="group_kelas_rawat_naik">
                                                <label for="">Pembiayaan</label>
                                                <select class="form-control" id="txt_bpjs_kelas_rawat_naik_pembiayaan">
                                                    <option value="" selected></option>
                                                    <option value="1">Pribadi</option>
                                                    <option value="2">Pemberi Kerja</option>
                                                    <option value="3">Asuransi Kesehatan Tambahan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Perobatan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-6 mb-6">
                                            <div class="col-12 form-group" id="group_poli">
                                                <label for="">Poli Tujuan</label>
                                                <select class="form-control" id="txt_bpjs_poli_tujuan"></select>
                                            </div>
                                            <div class="col-12 col-md-8 mb-4 form-group" id="group_poli">
                                                <label for="">Poli Eksekutif</label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_poli_eksekutif" value="0" checked />
                                                            <label class="form-check-label">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_poli_eksekutif" value="1" />
                                                            <label class="form-check-label">
                                                                Ya
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-4 form-group">
                                                        <label for="">Tujuan Kunjungan</label>
                                                        <select class="form-control uppercase sep" id="txt_bpjs_tujuan_kunjungan">
                                                            <option value="0">Normal</option>
                                                            <option value="1">Prosedur</option>
                                                            <option value="2">Konsul Dokter</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-8 form-group group_txt_bpjs_flag_procedure">
                                                        <label for="">Flag Prosedur</label>
                                                        <select class="form-control uppercase sep" id="txt_bpjs_flag_procedure">
                                                            <option value=""></option>
                                                            <option value="0">Prosedur Tidak Berkelanjutan</option>
                                                            <option value="1">Prosedur dan Terapi Berkelanjutan</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-8 mb-8 form-group group_txt_bpjs_kode_penunjang">
                                                <label for="">Kode Penunjang</label>
                                                <select class="form-control uppercase sep" id="txt_bpjs_kode_penunjang">
                                                    <option value=""></option>
                                                    <option value="1">Radioterapi</option>
                                                    <option value="2">Kemoterapi</option>
                                                    <option value="3">Rehabilitasi Medik</option>
                                                    <option value="4">Rehabilitasi Psikososial</option>
                                                    <option value="5">Transfusi Darah</option>
                                                    <option value="6">Pelayanan Gigi</option>
                                                    <option value="7">Laboratorium</option>
                                                    <option value="8">USG</option>
                                                    <option value="9">Farmasi</option>
                                                    <option value="11">MRI</option>
                                                    <option value="12">Hemodialisa</option>
                                                    <option value="10">Lain-lain</option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-12 mb-12 form-group group_txt_bpjs_asesmen_pelayanan">
                                                <label for="">Asesmen Pelayanan</label>
                                                <select class="form-control uppercase sep" id="txt_bpjs_asesmen_pelayanan">
                                                    <option value="1">Poli Spesialis tidak tersedia pada hari sebelumnya</option>
                                                    <option value="2">Jam Poli telah berakhir pada hari sebelumnya</option>
                                                    <option value="3">Dokter Spesialis yang dimaksud tidak praktek pada hari sebelumnya</option>
                                                    <option value="5">Tujuan Kontrol</option>
                                                    <option value="4">Atas Instruksi RS</option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-12 form-group" id="group_diagnosa">
                                                <label for="">Diagnosa Awal</label>
                                                <select class="form-control sep" id="txt_bpjs_diagnosa_awal"></select>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <label for="">Catatan</label>
                                                <textarea class="form-control" placeholder="Catatan Peserta" id="txt_bpjs_catatan" style="min-height: 200px"></textarea>
                                            </div>
                                            <div class="col-12 mb-8 form-group" id="group_spesialistik">
                                                <label for="">Spesialistik DPJP</label>
                                                <select class="form-control" id="txt_bpjs_dpjp_spesialistik"></select>
                                            </div>
                                            <div class="col-12 mb-9 form-group" id="group_dpjp">
                                                <label for="">Kode DPJP</label>
                                                <select class="form-control sep" id="txt_bpjs_dpjp"></select>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <label for="">COB</label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_cob" value="0" checked />
                                                            <label class="form-check-label">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_cob" value="1" />
                                                            <label class="form-check-label">
                                                                Ya
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <label for="">Katarak</label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_katarak" value="0" checked />
                                                            <label class="form-check-label">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="txt_bpjs_katarak" value="1" />
                                                            <label class="form-check-label">
                                                                Ya
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 mb-6">
                                            <div class="col-12 col-md-12 mb-2 form-groupn">
                                                <label for="">Jaminan Laka Lantas</label>
                                                <select class="form-control uppercase sep" id="txt_bpjs_laka">
                                                    <option value="0">Bukan Kecelakaan lalu lintas [BKLL]</option>
                                                    <option value="1">KLL dan bukan kecelakaan Kerja [BKK]</option>
                                                    <option value="2">KLL dan KK</option>
                                                    <option value="3">KK</option>
                                                </select>
                                            </div>
                                            <div class="laka_lantas_container">
                                                <div class="col-12 mb-4 form-group">
                                                    <label for="">No. LP</label>
                                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_laka_no_lp">
                                                </div>
                                                <div class="col-12 mb-4 form-group">
                                                    <label for="">Tanggal Kejadian</label>
                                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_laka_tanggal">
                                                </div>
                                                <div class="col-12 form-group">
                                                    <label for="">Keterangan</label>
                                                    <textarea class="form-control" placeholder="Catatan Peserta" id="txt_bpjs_laka_keterangan" style="min-height: 200px"></textarea>
                                                </div>
                                                <div class="col-12 form-group">
                                                    <label for="">Suplesi</label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="txt_bpjs_laka_suplesi" value="0" checked />
                                                                <label class="form-check-label">
                                                                    Tidak
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="txt_bpjs_laka_suplesi" value="1" />
                                                                <label class="form-check-label">
                                                                    Ya
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="laka_lantas_suplesi_container">
                                                    <div class="col-12 mb-4 form-group">
                                                        <label for="">Nomor SEP Suplesi</label>
                                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_laka_suplesi_nomor" />
                                                    </div>
                                                    <div class="col-12 mb-4 form-group" id="group_provinsi">
                                                        <label for="">Provinsi Kejadian</label>
                                                        <select class="form-control" id="txt_bpjs_laka_suplesi_provinsi"></select>
                                                    </div>
                                                    <div class="col-12 mb-4 form-group" id="group_kabupaten">
                                                        <label for="">Kabupaten Kejadian</label>
                                                        <select class="form-control" id="txt_bpjs_laka_suplesi_kabupaten"></select>
                                                    </div>
                                                    <div class="col-12 mb-4 form-group" id="group_kecamatan">
                                                        <label for="">Kecamatan Kejadian</label>
                                                        <select class="form-control" id="txt_bpjs_laka_suplesi_kecamatan"></select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesSEP">
                    <i class="fa fa-check"></i> Proses Edit
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div id="modal-sep-cetak" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-lg-8 offset-sm-2">
                    <h5 class="modal-title" id="modal-large-title">
                        <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> <span>Surat Eligibilitas Peserta</span>
                    </h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-5 offset-sm-2" id="data_sep_cetak_kiri">
                        <table class="table form-mode">
                            <tr>
                                <td style="width: 120px;">No. SEP</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_nomor"></td>
                            </tr>
                            <tr>
                                <td>Tgl. SEP</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_tanggal"></td>
                            </tr>
                            <tr>
                                <td>No. Kartu</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_nomor_kartu"></td>
                            </tr>
                            <tr>
                                <td>Nama Peserta</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_nama_peserta"></td>
                            </tr>
                            <tr>
                                <td>Tgl. Lahir</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_tanggal_lahir"></td>
                            </tr>
                            <tr>
                                <td>No. Telp</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_nomor_telepon"></td>
                            </tr>
                            <tr>
                                <td>Sub/Spesialis</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_spesialis"></td>
                            </tr>
                            <tr>
                                <td>Faskes Penunjuk</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_faskes_asal"></td>
                            </tr>
                            <tr>
                                <td>Diagnosa Awal</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_diagnosa_awal"></td>
                            </tr>
                            <tr>
                                <td>Catatan</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_catatan"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-4" id="data_sep_cetak_kanan">
                        <table class="table form-mode">
                            <tr>
                                <td style="width: 120px;">Peserta</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_peserta"></td>
                            </tr>
                            <tr>
                                <td>COB</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_cob"></td>
                            </tr>
                            <tr>
                                <td>Jenis Rawat</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_jenis_rawat"></td>
                            </tr>
                            <tr>
                                <td>Kelas Rawat</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_kelas_rawat"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-12 offset-sm-2" id="data_sep_cetak_bawah">
                        <small>
                            <i>
                                <ul type="*" style="margin: 0; padding: 10px;">
                                    <li>
                                        Saya menyetujui BPJS Kesehatan menggunakan informasi medis pasien jika diperlukan
                                    </li>
                                    <li>
                                        SEP bukan sebagai bukti penjaminan peserta
                                    </li>
                                </ul>
                            </i>
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnCetakSEP">
                    <i class="fa fa-print"></i> Cetak
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>